<?php
/**
 * Cache Progressivo de Licitações - Versão Otimizada para Cron Jobs
 * 
 * - Otimizado para executar em menos de 30 segundos (compatível com console.cron-job.org)
 * - Processa licitações em micro-batches com salvamento de estado frequente
 * - Mantém a lógica original de agrupamento por dia/semana/mês
 * - Sistema anti-timeout integrado com salvamento de progresso automático
 * - Continua de onde parou na próxima execução se ocorrer timeout
 */

// Definições e constantes - Adaptadas para limite de 30 segundos
define('MAX_EXECUTION_TIME', 40); // 25 segundos (margem de segurança para o limite de 30s)
define('PROCESSING_TIME_LIMIT', 20); // 20 segundos (margem para salvamento)
define('MEMORY_LIMIT', '256M');
define('CACHE_DIR', __DIR__ . '/../caches');
define('CACHE_TTL', 86400); // 24 horas
define('BATCH_SIZE', 200); // Reduzido para processar menos por vez
define('MICRO_BATCH_SIZE', 200); // Micro-batches para processamento incremental
define('GC_FREQUENCY', 100); // Coleta de lixo mais frequente
define('FORCE_UPDATE_INTERVAL', 86400); // Forçar atualização a cada 24 horas
define('MAX_ATTEMPTS_WITHOUT_DATA', 3);
define('DIAS_HISTORICO', 15);
define('CHECKPOINT_INTERVAL', 5); // Salvar checkpoint a cada 5 segundos

// Configurações iniciais
error_reporting(E_ALL);
ini_set("display_errors", 0);
ini_set('max_execution_time', MAX_EXECUTION_TIME);
set_time_limit(MAX_EXECUTION_TIME);
ini_set('memory_limit', MEMORY_LIMIT);

// Configuração de cabeçalhos
if (!isset($_SERVER['REMOTE_ADDR']) || php_sapi_name() !== 'cli') {
    // header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}

// Inicialização
$tempo_inicio = microtime(true);
$tempo_limite = PROCESSING_TIME_LIMIT;
$ultimo_checkpoint = $tempo_inicio;

// Incluir arquivo de conexão
try {
    include __DIR__."/../../../../admin/conn.php";
} catch (Exception $e) {
    log_message("ERRO FATAL: Não foi possível incluir o arquivo de conexão: " . $e->getMessage());
    exit(1);
}

// Arquivos de estado
$checkpoint_file = __DIR__ . '/cache_checkpoint_oportunidades.json';
$status_file = __DIR__ . '/cache_status_oportunidades.json';
$log_file = __DIR__ . '/cache_log_oportunidades.txt';
$lock_file = __DIR__ . '/cache_lock_oportunidades.txt';

// Data padrão - sempre usar a data atual
$data_atual = date('Y-m-d');

// Garantir que o arquivo de log exista e tenha permissões
if (!file_exists($log_file)) {
    file_put_contents($log_file, "===== INICIALIZAÇÃO DO LOG DE CACHE EM " . date('Y-m-d H:i:s') . " =====\n");
    chmod($log_file, 0666);
}

/**
 * Implementação de trava para evitar execuções concorrentes
 * 
 * @return bool True se obteve a trava, False caso contrário
 */
function obterTrava() {
    global $lock_file;
    
    // Verificar se a trava existe e está ativa
    if (file_exists($lock_file)) {
        $lock_data = json_decode(file_get_contents($lock_file), true);
        
        // Verificar se a trava expirou (mais de 2 minutos)
        if ($lock_data && isset($lock_data['timestamp'])) {
            if (time() - $lock_data['timestamp'] < 120) {
                // Trava está ativa e dentro do tempo
                return false;
            }
        }
    }
    
    // Criar ou atualizar trava
    $lock_data = [
        'timestamp' => time(),
        'pid' => getmypid(),
        'instance' => uniqid()
    ];
    
    file_put_contents($lock_file, json_encode($lock_data));
    return true;
}

/**
 * Liberar a trava
 */
function liberarTrava() {
    global $lock_file;
    
    if (file_exists($lock_file)) {
        @unlink($lock_file);
    }
}

/**
 * Verificar se o tempo está acabando
 * 
 * @param float $inicio Timestamp de início
 * @param float $limite Limite em segundos
 * @param float $margem Margem de segurança em segundos
 * @return bool
 */
function tempo_esgotando($inicio, $limite, $margem = 5) {
    return (microtime(true) - $inicio) > ($limite - $margem);
}

/**
 * Função de log
 * 
 * @param string $message Mensagem a ser registrada
 * @param bool $is_error Se é uma mensagem de erro
 */
function log_message($message, $is_error = false) {
    global $log_file;
    echo $message.'<br>';
    $timestamp = date('Y-m-d H:i:s');
    $prefix = $is_error ? "[ERRO]" : "[INFO]";
    
    // Não usar echo para evitar overhead no cronjob
    // echo "[$timestamp] $prefix $message\n";
    
    // Registrar em um arquivo de log específico
    $log_message = "[$timestamp] $prefix $message\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
    
    // Opcionalmente, registrar erros no log padrão
    if ($is_error) {
        error_log("[$timestamp] $message");
    }
}

/**
 * Verifica se é hora de salvar um checkpoint baseado no intervalo
 * 
 * @param float $ultimo_checkpoint Timestamp do último checkpoint
 * @return bool
 */
function hora_de_checkpoint($ultimo_checkpoint) {
    return (microtime(true) - $ultimo_checkpoint) >= CHECKPOINT_INTERVAL;
}

/**
 * Salvar status de execução
 * 
 * @param array $status Informações de status
 * @return bool
 */
function salvarStatus($status) {
    global $status_file;
    
    try {
        $json_data = json_encode($status, JSON_PRETTY_PRINT);
        file_put_contents($status_file, $json_data);
        return true;
    } catch (Exception $e) {
        log_message("Erro ao salvar status: " . $e->getMessage(), true);
        return false;
    }
}

/**
 * Carregar status de execução
 * 
 * @return array
 */
function carregarStatus() {
    global $status_file;
    
    $status_default = [
        'ultima_execucao' => null,
        'ultima_atualizacao_completa' => null,
        'execucoes_sem_dados' => 0,
        'total_execucoes' => 0,
        'ultima_execucao_sucesso' => false,
        'batch_atual' => 0, // Novo: para controle de processamento em micro-batches
        'total_registros_processados' => 0 // Novo: contador de progresso
    ];
    
    if (!file_exists($status_file)) {
        return $status_default;
    }
    
    try {
        $data = file_get_contents($status_file);
        $status = json_decode($data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $status_default;
        }
        
        // Adicionar novos campos se não existirem
        if (!isset($status['batch_atual'])) {
            $status['batch_atual'] = 0;
        }
        
        if (!isset($status['total_registros_processados'])) {
            $status['total_registros_processados'] = 0;
        }
        
        return $status;
    } catch (Exception $e) {
        log_message("Erro ao carregar status: " . $e->getMessage(), true);
        return $status_default;
    }
}

/**
 * Determina se é necessário forçar uma atualização completa
 * 
 * @param array $status Informações de status
 * @return bool
 */
function precisaAtualizacaoCompleta($status) {
    try {
        
        
        // Verificar o último ID processado pelo checkpoint
        global $checkpoint_file;
        $ultimo_id_processado = 0;
        
        if (file_exists($checkpoint_file)) {
            $data = file_get_contents($checkpoint_file);
            $checkpoint = json_decode($data, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($checkpoint['ultimo_id'])) {
                $ultimo_id_processado = $checkpoint['ultimo_id'];
            }
        }
        
        // Buscar o último ID da tabela de licitações
        $conn = conn();
        if (!$conn) {
            throw new Exception("Erro de conexão com o banco de dados");
        }
        
        $sql = "SELECT MAX(licitacao_id) as ultimo_id FROM licitacoes";
        $result = $conn->query($sql);
        
        if ($result && $row = $result->fetch_assoc()) {
            $ultimo_id_banco = $row['ultimo_id'];
            
            // Se o último ID processado for igual ao último ID do banco,
            // isso indica que processamos todos os registros
            if ($ultimo_id_processado >= $ultimo_id_banco) {
                log_message("Último ID do banco ($ultimo_id_banco) foi alcançado. Forçando atualização completa.");
                return true;
            } else {
                log_message("Continuando atualização incremental. ID processado: $ultimo_id_processado, Último ID banco: $ultimo_id_banco");
                return false;
            }
        }
        
        // Se não conseguir obter o último ID do banco, usar a lógica antiga como fallback
        log_message("Não foi possível determinar o último ID do banco. Usando critérios alternativos.");
        
        // Mantém a primeira execução como lógica de fallback
        if (empty($status['ultima_atualizacao_completa'])) {
            return true;
        }
        
        return false;
    } catch (Exception $e) {
        log_message("Erro ao verificar último ID do banco: " . $e->getMessage(), true);
        
        // Em caso de erro, manter apenas a primeira execução como critério
        if (empty($status['ultima_atualizacao_completa'])) {
            return true;
        }
        
        return false;
    }
}

/**
 * Carregar checkpoint com bloqueio para evitar condições de corrida
 * 
 * @param bool $forcaAtualizacaoCompleta Forçar atualização completa
 * @return array
 */
function carregarCheckpoint($forcaAtualizacaoCompleta = false) {
    global $checkpoint_file, $data_atual;
    
    // Checkpoint padrão
    $checkpoint_default = [
        'ultimo_id' => 0,
        'data_execucao' => date('Y-m-d'),
        'completo' => false,
        'parcial' => false,
        'micro_batch_offset' => 0, // Novo: controle de offset para micro-batches
        'batch_atual' => 0, // Novo: para coordenar com o status
        'ultima_atualização' => date('Y-m-d H:i:s') // Timestamp
    ];
    
    if ($forcaAtualizacaoCompleta) {
        log_message("Atualização completa forçada. Reiniciando do zero.");
        salvarCheckpoint($checkpoint_default);
        return $checkpoint_default;
    }
    
    if (!file_exists($checkpoint_file)) {
        log_message("Arquivo de checkpoint não encontrado. Criando novo.");
        salvarCheckpoint($checkpoint_default);
        return $checkpoint_default;
    }
    
    try {
        // Usar file_get_contents para leitura rápida em vez do bloqueio
        // O bloqueio é tratado pelo obterTrava() no início do script
        $data = file_get_contents($checkpoint_file);
        
        if (empty($data)) {
            throw new Exception("Arquivo de checkpoint vazio");
        }
        
        $checkpoint = json_decode($data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erro ao decodificar JSON: " . json_last_error_msg());
        }
        
        // Adicionar novos campos se não existirem
        if (!isset($checkpoint['micro_batch_offset'])) {
            $checkpoint['micro_batch_offset'] = 0;
        }
        
        if (!isset($checkpoint['batch_atual'])) {
            $checkpoint['batch_atual'] = 0;
        }
        
        if (!isset($checkpoint['ultima_atualização'])) {
            $checkpoint['ultima_atualização'] = date('Y-m-d H:i:s');
        }
        
        // Verificar se é um novo dia
        if ($checkpoint['data_execucao'] != date('Y-m-d')) {
            log_message("Novo dia do sistema detectado. Reiniciando checkpoint do zero.");
            $checkpoint = $checkpoint_default;
            salvarCheckpoint($checkpoint);
        }
        
        return $checkpoint;
    } catch (Exception $e) {
        log_message("Erro ao carregar checkpoint: " . $e->getMessage(), true);
        // Em caso de erro, retornar checkpoint padrão
        return $checkpoint_default;
    }
}

/**
 * Salvar checkpoint com escrita otimizada para evitar bloqueios
 * 
 * @param array $checkpoint Dados do checkpoint
 * @return bool
 */
function salvarCheckpoint($checkpoint) {
    global $checkpoint_file;

    try {
        // Assegurar que o diretório existe
        $dir = dirname($checkpoint_file);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new Exception("Não foi possível criar o diretório para o checkpoint");
            }
        }
        
        // Atualizar timestamp
        $checkpoint['ultima_atualização'] = date('Y-m-d H:i:s');
        
        // Usar escrita direta e atômica com arquivo temporário
        $json_data = json_encode($checkpoint, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erro ao codificar JSON: " . json_last_error_msg());
        }
        
        // Criar arquivo temporário
        $temp_file = $checkpoint_file . '.tmp';
        file_put_contents($temp_file, $json_data);

        // Renomear para criar operação atômica
        if (!rename($temp_file, $checkpoint_file)) {
            // Fallback para cópia direta se rename falhar
            file_put_contents($checkpoint_file, $json_data);
        }
        
        return true;
    } catch (Exception $e) {
        log_message("Erro ao salvar checkpoint: " . $e->getMessage(), true);
        return false;
    }
}

/**
 * Classe melhorada para gerenciamento de cache em arquivo
 */
class FileCache {
    private $cacheDir;
    private $ttl = CACHE_TTL;

    /**
     * Constructor
     * 
     * @param string $cacheDir Diretório para armazenar arquivos de cache
     */
    public function __construct($cacheDir = CACHE_DIR) {
        $this->cacheDir = $cacheDir;

        // Garantir que o diretório de cache existe e é gravável
        if (!file_exists($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0755, true)) {
                throw new Exception("Não foi possível criar o diretório de cache: {$this->cacheDir}");
            }
        }
        
        if (!is_writable($this->cacheDir)) {
            throw new Exception("Diretório de cache não tem permissão de escrita: {$this->cacheDir}");
        }
    }

    /**
     * Salva dados no cache - Versão otimizada com escrita atômica
     * 
     * @param string $key Chave de cache
     * @param mixed $data Dados a serem armazenados
     * @return bool
     */
    public function set($key, $data) {
        try {
            $filePath = $this->getFilePath($key);
            echo $filePath;
            $tempPath = $filePath . '.tmp';
            $data = json_encode($data);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erro ao codificar dados para cache: " . json_last_error_msg());
            }
            
            // Escrever em arquivo temporário e então mover (operação mais atômica)
            file_put_contents($tempPath, $data);
            
            if (!rename($tempPath, $filePath)) {
                // Fallback para escrita direta se rename falhar
                file_put_contents($filePath, $data);
                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
            }
            
            return true;
        } catch (Exception $e) {
            log_message("Erro ao salvar cache: " . $e->getMessage(), true);
            return false;
        }
    }

    /**
     * Recupera dados do cache - Versão otimizada para leitura rápida
     * 
     * @param string $key Chave de cache
     * @return mixed|null
     */
    public function get($key) {
        try {
            $filePath = $this->getFilePath($key);
            
            if (!file_exists($filePath)) {
                return null;
            }
            
            $cacheTime = filemtime($filePath);
            if (time() - $cacheTime >= $this->ttl) {
                // Cache expirado
                return null;
            }
            
            // Leitura direta para alta performance
            $data = file_get_contents($filePath);
            
            if ($data === false) {
                throw new Exception("Falha ao ler arquivo de cache: $filePath");
            }
            
            $result = json_decode($data, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erro ao decodificar dados do cache: " . json_last_error_msg());
            }
            
            return $result;
        } catch (Exception $e) {
            log_message("Erro ao ler cache: " . $e->getMessage(), true);
            return null;
        }
    }

    /**
     * Verifica se uma licitação pertence ao período especificado - VERSÃO CORRIGIDA
     * 
     * @param array $item Licitação para verificar
     * @param string $periodo Período no formato ('YYYY-MM-DD', 'YYYY-W##', 'YYYY-MM')
     * @return bool
     */
    private function licitacaoPertenceAoPeriodo($item, $periodo) {
        if (!isset($item['atualizacao'])) {
            return false;
        }
        
        $dataAtualizacao = date('Y-m-d', strtotime($item['atualizacao']));
        $dataObj = new DateTime($dataAtualizacao);
        
        // Verificar se é um período de dia (YYYY-MM-DD)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $periodo)) {
            return $dataAtualizacao === $periodo;
        }
    
        // Verificar se é um período de semana (W-YYYY-WW)
        if (strpos($periodo, 'W-') === 0) {
            $semanaAtual = 'W-' . $dataObj->format('Y-W');
            return $semanaAtual === $periodo;
        }
        
        // Verificar se é um período de mês (YYYY-MM)
        if (preg_match('/^\d{4}-\d{2}$/', $periodo)) {
            return $dataObj->format('Y-m') === $periodo;
        }
        
        return false;
    }
    
    private function normalizarChaveCache($periodo) {
        // Remove prefixo W- das semanas
        if (strpos($periodo, 'W-') === 0) {
            return substr($periodo, 2);
        }
        return $periodo;
    }

    /**
     * Método otimizado para atualizar cache de períodos (menos bloqueio)
     * 
     * @param string $periodo Chave de período (YYYY-MM-DD, YYYY-W##, YYYY-MM)
     * @param array $dados Dados específicos deste período
     * @return bool Sucesso da operação
     */
    public function atualizarPeriodo($periodo, $dados) {
        try {
            if (empty($dados)) {
                log_message("Nenhum dado para atualizar no período: $periodo");
                return true;
            }
            // Filtrar dados para garantir que só incluímos licitações deste período específico
            $dadosFiltrados = [];
            foreach ($dados as $item) {
                if ($this->licitacaoPertenceAoPeriodo($item, $periodo)) {
                    $dadosFiltrados[] = $item;
                }
            }
           
            
            // Verificar se temos dados após a filtragem
            if (empty($dadosFiltrados)) {
                log_message("Nenhum dado pertencente ao período $periodo após filtragem");
                return true;
            }
            
             // CORREÇÃO: Preparar chave de cache limpa usando método helper
            $chaveCache = $this->normalizarChaveCache($periodo);
            
            if ($chaveCache !== $periodo) {
                log_message("Normalizando chave: $periodo -> $chaveCache");
            }
            
            // Obter dados existentes usando a chave limpa
            $dadosExistentes = $this->get($chaveCache) ?: [];
            
            // DEBUG: Log para verificar se encontrou dados existentes
            if (!empty($dadosExistentes)) {
                log_message("Encontrados " . count($dadosExistentes) . " registros existentes para $chaveCache");
            }
            
            // Indexar dados existentes por ID
            $indexado = [];
            foreach ($dadosExistentes as $item) {
                if (isset($item['id']) && $this->licitacaoPertenceAoPeriodo($item, $periodo)) {
                    $indexado[$item['id']] = $item;
                }
            }
            
            // Mesclar com novos dados
            $adicionados = 0;
            $atualizados = 0;
            
            foreach ($dadosFiltrados as $item) {
                if (!isset($item['id'])) continue;
                
                if (isset($indexado[$item['id']])) {
                    $indexado[$item['id']] = $item;
                    $atualizados++;
                } else {
                    $indexado[$item['id']] = $item;
                    $adicionados++;
                }
            }
            
            // Converter de volta para array
            $dadosAtualizados = array_values($indexado);
            
            // Salvar dados atualizados usando a chave limpa (sem W-)
            echo "Salvando período: $periodo -> chave cache: $chaveCache\n";
            $this->set($chaveCache, $dadosAtualizados);
            
            log_message("Período $periodo: $adicionados adicionados, $atualizados atualizados, total: " . count($dadosAtualizados));
            return true;
        } catch (Exception $e) {
            log_message("Erro ao atualizar período $periodo: " . $e->getMessage(), true);
            return false;
        }
    }

    /**
     * Obtém o caminho do arquivo para uma chave de cache
     * 
     * @param string $key Chave de cache
     * @return string Caminho do arquivo
     */
    private function getFilePath($key) {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
    
    /**
     * Limpa cache expirado - Versão otimizada para execução rápida
     * Agora processa apenas um pequeno lote de arquivos expirados por vez
     * 
     * @param int $max_files Número máximo de arquivos a processar
     * @return int Número de arquivos removidos
     */
    public function cleanExpired($max_files = 10) {
        $count = 0;
        $now = time();
        $processed = 0;
        
        if ($handle = opendir($this->cacheDir)) {
            while (false !== ($file = readdir($handle)) && $processed < $max_files) {
                if ($file != "." && $file != "..") {
                    $filePath = $this->cacheDir . '/' . $file;
                    if (is_file($filePath) && $now - filemtime($filePath) >= $this->ttl) {
                        if (unlink($filePath)) {
                            $count++;
                        }
                    }
                    $processed++;
                }
            }
            closedir($handle);
        }
        
        return $count;
    }
    
    /**
     * Obter lista de todas as chaves no cache
     * Versão limitada para retornar apenas um subconjunto
     * 
     * @param int $offset Posição inicial
     * @param int $limit Número máximo de chaves
     * @return array Lista de chaves
     */
    public function listarChaves($offset = 0, $limit = 20) {
        $chaves = [];
        $idx = 0;
        $count = 0;
        
        if ($handle = opendir($this->cacheDir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && pathinfo($file, PATHINFO_EXTENSION) == 'cache') {
                    if ($idx >= $offset && $count < $limit) {
                        $chaves[] = str_replace('.cache', '', $file);
                        $count++;
                    }
                    $idx++;
                }
            }
            closedir($handle);
        }
        
        return $chaves;
    }
}

/**
 * Obter período de data para consulta
 * 
 * @return array Array com datas inicial e final
 */
function obterPeriodoConsulta() {
    global $data_atual;
    
    try {
        // Calcular datas para o período de busca
        $dataFinal = new DateTime($data_atual);
        $dataInicial = clone $dataFinal;
        $dataInicial->modify('-' . DIAS_HISTORICO . ' days');
        
        return [
            'inicio' => $dataInicial->format('Y-m-d'),
            'fim' => $dataFinal->format('Y-m-d')
        ];
    } catch (Exception $e) {
        log_message("Erro ao calcular período de consulta: " . $e->getMessage(), true);
        
        // Fallback para período padrão
        return [
            'inicio' => date('Y-m-d', strtotime('-' . DIAS_HISTORICO . ' days')),
            'fim' => $data_atual
        ];
    }
}

/**
 * Busca dados de licitações em micro-batches para evitar timeouts
 * 
 * @param array $checkpoint Checkpoint atual
 * @return array Resultado da busca
 */
function buscarDadosDoBanco($checkpoint) {
    global $tempo_inicio, $tempo_limite, $ultimo_checkpoint;
    
    $ultimo_id = $checkpoint['ultimo_id'];
    $micro_batch_offset = $checkpoint['micro_batch_offset'];
    
    log_message("Iniciando micro-batch a partir do ID: $ultimo_id (offset: $micro_batch_offset)");
    $startTime = microtime(true);
    
    try {
        $conn = conn();
        if (!$conn) {
            throw new Exception("Erro de conexão com o banco de dados");
        }
        
        // Obter período para consulta
        $periodo = obterPeriodoConsulta();
        
        $licitacoes = [];
        $maiorIdProcessado = $ultimo_id;
        $totalProcessado = 0;
        $resultadoParcial = false;
        
        // Consulta SQL otimizada com tamanho de lote menor
        $batchSize = MICRO_BATCH_SIZE;
        $params = [$ultimo_id, $periodo['inicio'], $periodo['fim'], $batchSize];
        
        $sqlPrincipal = "
            SELECT l.licitacao_id as id, 
                   l.licitacao_att,
                   l.licitacao_objeto,
                   l.licitacao_data,
                   pub.lm_valor as publicacao,
                   enc.lm_valor as encerramento
            FROM licitacoes l
            LEFT JOIN licitacoes_meta pub ON pub.lm_licitacao = l.licitacao_id AND pub.lm_chave = 'dataPublicacaoPncp'
            LEFT JOIN licitacoes_meta enc ON enc.lm_licitacao = l.licitacao_id AND enc.lm_chave = 'dataEncerramentoPropostaPncp'
            WHERE l.licitacao_id > ? 
                AND DATE(CASE 
                    WHEN l.licitacao_att IS NULL OR l.licitacao_att = '' 
                    THEN DATE(l.licitacao_data) 
                    ELSE l.licitacao_att 
                END) BETWEEN ? AND ?
            ORDER BY l.licitacao_id ASC
            LIMIT ?
        ";

        // Executar apenas um lote por vez para evitar timeouts
        $stmt = $conn->prepare($sqlPrincipal);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta principal: " . $conn->error);
        }
        
        $stmt->bind_param('issi', $params[0], $params[1], $params[2], $params[3]);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar consulta principal: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        $countRecords = 0;
        $batchIds = [];
        $batchRecords = [];
        
        // Processar resultados deste micro-lote
        while ($row = $result->fetch_assoc()) {
            $batchIds[] = $row['id'];
            $batchRecords[$row['id']] = [
                'id' => $row['id'],
                'publicacao' => $row['publicacao'],
                'encerramento' => $row['encerramento'],
                'atualizacao' => !empty($row['licitacao_att']) ? $row['licitacao_att'] : $row['licitacao_data'],
                'objeto' => $row['licitacao_objeto'],
                'regiao' => null,
                'descricao' => []
            ];
            
            // Atualizar o maior ID processado
            $maiorIdProcessado = max($maiorIdProcessado, $row['id']);
            $countRecords++;
            
            // Verificar checkpoints frequentes para parar se necessário
            if (hora_de_checkpoint($ultimo_checkpoint) && countRecords > 0) {
                log_message("Verificando checkpoint intermediário durante processamento de registros");
                $ultimo_checkpoint = microtime(true);
                
                if (tempo_esgotando($tempo_inicio, $tempo_limite)) {
                    log_message("Tempo limite se aproximando durante processamento de registros. Salvando progresso parcial.");
                    $resultadoParcial = true;
                    break;
                }
            }
        }
        
        // Verificar se há registros para processar
        if (count($batchIds) > 0) {
            // Processar apenas um subconjunto de IDs se tivemos que interromper no meio do resultado
            if ($resultadoParcial) {
                // Atualizar offset para continuar do ponto correto
                $novos_batchIds = array_slice($batchIds, $micro_batch_offset);
                $micro_batch_offset += count($novos_batchIds);
                $batchIds = $novos_batchIds;
            } else {
                // Resetar offset se processamos todo o lote
                $micro_batch_offset = 0;
            }
            
            // Buscar dados adicionais (regiões e descrições)
            $regioes = buscarRegioes($conn, $batchIds);
            
            // Verificar novamente o tempo antes de buscar descrições
            if (!tempo_esgotando($tempo_inicio, $tempo_limite, 8)) {
                $descricoes = buscarDescricoes($conn, $batchIds);
            } else {
                $descricoes = []; // Pular descrições se tempo estiver acabando
                $resultadoParcial = true;
            }
            
            // Mesclar com os registros principais
            foreach ($batchIds as $id) {
                if (isset($batchRecords[$id])) {
                    if (isset($regioes[$id])) {
                        $batchRecords[$id]['regiao'] = $regioes[$id];
                    }
                    
                    if (isset($descricoes[$id])) {
                        $batchRecords[$id]['descricao'] = $descricoes[$id];
                    }
                    
                    // Adicionar ao resultado final
                    $licitacoes[] = $batchRecords[$id];
                }
            }
            
            $totalProcessado = count($licitacoes);
        }
        
        // Liberar recursos
        $result->close();
        $stmt->close();
        
        $executionTime = round(microtime(true) - $startTime, 2);
        log_message("Micro-batch concluído em {$executionTime}s. Registros: $totalProcessado");
        
        // Determinar se há mais dados
        $temMaisDados = $countRecords > 0;
        
        // Verificar se terminamos este micro-batch completamente
        $completo = !$resultadoParcial && !$temMaisDados;
        
        // Atualizar checkpoint
        $checkpoint['ultimo_id'] = $maiorIdProcessado;
        $checkpoint['micro_batch_offset'] = $resultadoParcial ? $micro_batch_offset : 0;
        $checkpoint['completo'] = $completo;
        $checkpoint['parcial'] = $resultadoParcial;
        
        // Salvar checkpoint se necessário
        if ($resultadoParcial) {
            log_message("Salvando checkpoint intermediário: ID $maiorIdProcessado, offset $micro_batch_offset");
            salvarCheckpoint($checkpoint);
        }
        
        return [
            'licitacoes' => $licitacoes,
            'checkpoint' => $checkpoint,
            'temNovosRegistros' => count($licitacoes) > 0
        ];
    } catch (Exception $e) {
        log_message("Erro ao buscar dados do banco: " . $e->getMessage(), true);
        
        // Atualizar o checkpoint para continuar na próxima execução
        $checkpoint['parcial'] = true;
        salvarCheckpoint($checkpoint);
        
        return [
            'licitacoes' => [],
            'checkpoint' => $checkpoint,
            'temNovosRegistros' => false
        ];
    }
}

/**
 * Busca regiões - versão otimizada para menor consumo de tempo
 * 
 * @param mysqli $conn Conexão com banco de dados
 * @param array $ids IDs para buscar
 * @return array Regiões por ID
 */
function buscarRegioes($conn, $ids) {
    if (empty($ids)) return [];
    global $tempo_inicio, $tempo_limite;
    
    // Usar todos os IDs de uma vez para busca mais eficiente
    $regioes = [];
    
    try {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT lm_licitacao as id, lm_valor as regiao 
                FROM licitacoes_meta 
                WHERE lm_licitacao IN ($placeholders) 
                AND lm_chave = 'unidadeOrgaoUfSigla'";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta de regiões: " . $conn->error);
        }
        
        // Bind parameters dinamicamente
        $types = str_repeat("i", count($ids));
        $bindParams = [$stmt, $types];
        foreach ($ids as $id) {
            $bindParams[] = &$ids[array_search($id, $ids)];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar consulta de regiões: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $regioes[$row['id']] = $row['regiao'];
        }
        
        $stmt->close();
        $result->close();
        
        return $regioes;
    } catch (Exception $e) {
        log_message("Erro ao buscar regiões: " . $e->getMessage(), true);
        return $regioes; // Retorna o que conseguiu obter
    }
}

/**
 * Busca descrições - versão otimizada com limite estrito para evitar timeouts
 * 
 * @param mysqli $conn Conexão com banco de dados
 * @param array $ids IDs para buscar
 * @return array Descrições por ID
 */
function buscarDescricoes($conn, $ids) {
    if (empty($ids)) return [];
    global $tempo_inicio, $tempo_limite;
    
    $descricoes = [];
    
    try {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT licitacoes_item_licitacao as id, licitacoes_item_desc 
                FROM licitacoes_itens 
                WHERE licitacoes_item_licitacao IN ($placeholders)
                LIMIT 500"; // Limite para evitar excesso de dados
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta de descrições: " . $conn->error);
        }
        
        // Bind parameters dinamicamente
        $types = str_repeat("i", count($ids));
        $bindParams = [$stmt, $types];
        foreach ($ids as $id) {
            $bindParams[] = &$ids[array_search($id, $ids)];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar consulta de descrições: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            if (!isset($descricoes[$row['id']])) {
                $descricoes[$row['id']] = [];
            }
            $descricoes[$row['id']][] = $row['licitacoes_item_desc'];
            
            // Verificar timeout durante processamento
            if (tempo_esgotando($tempo_inicio, $tempo_limite, 5)) {
                log_message("Tempo quase esgotado durante busca de descrições. Retornando resultados parciais.");
                break;
            }
        }
        
        $stmt->close();
        $result->close();
        
        return $descricoes;
    } catch (Exception $e) {
        log_message("Erro ao buscar descrições: " . $e->getMessage(), true);
        return $descricoes; // Retorna o que conseguiu obter
    }
}

/**
 * Agrupa licitações por dias, semanas e meses - versão otimizada
 * 
 * @param array $licitacoes Lista de licitações
 * @return array Dados agrupados
 */
function agruparDados($licitacoes) {
    global $tempo_inicio, $tempo_limite;
    
    if (empty($licitacoes)) {
        return [
            'dias' => [],
            'semanas' => [],
            'meses' => []
        ];
    }
    
    try {
        $dias = [];
        $semanas = [];
        $meses = [];
        
        foreach ($licitacoes as $licitacao) {
            if (!isset($licitacao['atualizacao'])) continue;
            
            // Extrair data da licitação
            $data = date('Y-m-d', strtotime($licitacao['atualizacao']));
            $dataObj = new DateTime($data);
            
            // Definir os períodos para esta licitação
            $dia = $data; // YYYY-MM-DD
            
            // SOLUÇÃO: Prefixar semanas com "W-" para evitar ambiguidade
            $semana = 'W-' . $dataObj->format('Y-W'); // Gera "W-2025-01", "W-2025-52"
            
            // Formato do mês (sem prefixo)
            $mes = $dataObj->format('Y-m'); // Gera "2025-01", "2025-12"
            
            // Registrar em qual dia esta licitação foi encontrada
            if (!isset($dias[$dia])) {
                $dias[$dia] = [];
            }
            $dias[$dia][] = $licitacao;
            
            // Registrar em qual semana esta licitação foi encontrada
            if (!isset($semanas[$semana])) {
                $semanas[$semana] = [];
            }
            $semanas[$semana][] = $licitacao;
            
            // Registrar em qual mês esta licitação foi encontrada
            if (!isset($meses[$mes])) {
                $meses[$mes] = [];
            }
            $meses[$mes][] = $licitacao;
            
            // Verificar timeout
            if (tempo_esgotando($tempo_inicio, $tempo_limite, 10)) {
                log_message("Tempo quase esgotado durante agrupamento. Processamento parcial.");
                break;
            }
        }
        
        // DEBUG: Log para verificar os formatos gerados (remover depois)
        if (!empty($semanas)) {
            $primeirasSemanas = array_slice(array_keys($semanas), 0, 3);
            log_message("DEBUG - Primeiras semanas geradas: " . implode(', ', $primeirasSemanas));
        }
        if (!empty($meses)) {
            $primeirosMeses = array_slice(array_keys($meses), 0, 3);
            log_message("DEBUG - Primeiros meses gerados: " . implode(', ', $primeirosMeses));
        }
        
        return [
            'dias' => $dias,
            'semanas' => $semanas,
            'meses' => $meses
        ];
    } catch (Exception $e) {
        log_message("Erro ao agrupar dados: " . $e->getMessage(), true);
        return [
            'dias' => [],
            'semanas' => [],
            'meses' => []
        ];
    }
}

/**
 * Atualiza caches para todos os períodos de uma só vez
 * Versão modificada para processar dias, semanas e meses juntos
 * 
 * @param array $dadosAgrupados Dados agrupados por período
 * @param FileCache $cache Instância do cache
 * @param array $status Status atual para controle de batches
 * @return array Estatísticas de atualização e status atualizado
 */
function atualizarCachesPorBatch($dadosAgrupados, $cache, $status) {
    global $tempo_inicio, $tempo_limite, $ultimo_checkpoint;
    
    $stats = [
        'dias_atualizados' => 0,
        'semanas_atualizadas' => 0,
        'meses_atualizados' => 0,
        'parcial' => false
    ];
    
    // Obter batch atual do status
    $batch_atual = $status['batch_atual'] ?? 0;
    
    try {
        // Processar todos os tipos de período em uma única execução
        log_message("Processando todos os períodos juntos (dias, semanas e meses)");
        
        // Processar dias
        if (!empty($dadosAgrupados['dias'])) {
            $dias = $dadosAgrupados['dias'];
            $chaves = array_keys($dias);
            
            foreach ($chaves as $dia) {
                if (tempo_esgotando($tempo_inicio, $tempo_limite, 8)) {
                    log_message("Tempo quase esgotado durante atualização dos dias. Salvando progresso parcial.");
                    $stats['parcial'] = true;
                    break;
                }
                
                if (!empty($dias[$dia])) {
                    $cache->atualizarPeriodo($dia, $dias[$dia]);
                    $stats['dias_atualizados']++;
                }
                
                // Checkpoint intermediário
                if (hora_de_checkpoint($ultimo_checkpoint)) {
                    $ultimo_checkpoint = microtime(true);
                    log_message("Checkpoint intermediário durante processamento de períodos");
                }
            }
        }

        // Se ainda temos tempo, processar semanas
        if (!$stats['parcial'] && !empty($dadosAgrupados['semanas'])) {
            $semanas = $dadosAgrupados['semanas'];
            $chaves = array_keys($semanas);

            foreach ($chaves as $semana) {
                if (tempo_esgotando($tempo_inicio, $tempo_limite, 8)) {
                    log_message("Tempo quase esgotado durante atualização das semanas. Salvando progresso parcial.");
                    $stats['parcial'] = true;
                    break;
                }
                
                if (!empty($semanas[$semana])) {
                    $cache->atualizarPeriodo($semana, $semanas[$semana]);
                    $stats['semanas_atualizadas']++;
                }
                
                if (hora_de_checkpoint($ultimo_checkpoint)) {
                    $ultimo_checkpoint = microtime(true);
                }
            }
        }
        
        // Se ainda temos tempo, processar meses
        if (!$stats['parcial'] && !empty($dadosAgrupados['meses'])) {
            $meses = $dadosAgrupados['meses'];
            $chaves = array_keys($meses);
            
            foreach ($chaves as $mes) {
                if (tempo_esgotando($tempo_inicio, $tempo_limite, 8)) {
                    log_message("Tempo quase esgotado durante atualização dos meses. Salvando progresso parcial.");
                    $stats['parcial'] = true;
                    break;
                }
                
                if (!empty($meses[$mes])) {
                    $cache->atualizarPeriodo($mes, $meses[$mes]);
                    $stats['meses_atualizados']++;
                }
                
                if (hora_de_checkpoint($ultimo_checkpoint)) {
                    $ultimo_checkpoint = microtime(true);
                }
            }
        }
        
        // Considerar ciclo completo se todos os períodos foram processados sem interrupção
        if (!$stats['parcial']) {
            log_message("Processamento completo de todos os períodos em uma única execução!");
            $batch_atual++;
        }
        
        // Atualizar status
        $status['batch_atual'] = $batch_atual;
        return [
            'stats' => $stats,
            'status' => $status
        ];
    } catch (Exception $e) {
        log_message("Erro ao atualizar caches: " . $e->getMessage(), true);
        $stats['parcial'] = true;
        return [
            'stats' => $stats,
            'status' => $status
        ];
    }
}

/**
 * Função principal para atualizar o cache - Versão otimizada para micro-batches
 * 
 * @param bool $forcaAtualizacaoCompleta Force atualização completa
 * @return array Dados processados
 */
function armazenarCache($forcaAtualizacaoCompleta = false) {
    global $tempo_inicio, $tempo_limite, $ultimo_checkpoint;
    
    log_message("Iniciando processamento de cache otimizado para cron jobs");
    
    $startTime = microtime(true);
    
    try {
        $cache = new FileCache();
        
        // Limpar apenas alguns caches expirados por vez
        $removidos = $cache->cleanExpired(5);
        if ($removidos > 0) {
            log_message("$removidos arquivos de cache expirados foram removidos.");
        }
        
        // Carregar estado atual do processamento
        $checkpoint = carregarCheckpoint($forcaAtualizacaoCompleta);
        $status = carregarStatus();
        
        // Buscar dados do banco continuando de onde parou
        $resultado = buscarDadosDoBanco($checkpoint);
        $dados = $resultado['licitacoes'];
        $checkpoint = $resultado['checkpoint']; // Checkpoint atualizado
        $temNovosRegistros = $resultado['temNovosRegistros'];
        
        // Atualizar status da execução
        $status['ultima_execucao'] = date('Y-m-d H:i:s');
        $status['total_execucoes']++;
        $status['total_registros_processados'] += count($dados);
        if ($temNovosRegistros) {
            $status['execucoes_sem_dados'] = 0;
        } else {
            $status['execucoes_sem_dados']++;
        }
        
        $totalCachesAtualizados = 0;
        $estatisticasCache = [
            'dias_atualizados' => 0,
            'semanas_atualizadas' => 0,
            'meses_atualizados' => 0
        ];
        
        // Verificar checkpoint intermediário
        if (hora_de_checkpoint($ultimo_checkpoint)) {
            $ultimo_checkpoint = microtime(true);
            salvarCheckpoint($checkpoint);
            salvarStatus($status);
        }

        if (!empty($dados)) {
            log_message("Processando " . count($dados) . " licitações encontradas");
            
            // Agrupar dados por dia, semana e mês
            $dadosAgrupados = agruparDados($dados);
            
            // Verificar checkpoint antes de atualizar caches
            if (hora_de_checkpoint($ultimo_checkpoint)) {
                $ultimo_checkpoint = microtime(true);
                salvarCheckpoint($checkpoint);
                salvarStatus($status);
            }
            
            // Atualizar caches por batch
            $resultado = atualizarCachesPorBatch($dadosAgrupados, $cache, $status);
            $estatisticasCache = $resultado['stats'];
            $status = $resultado['status']; // Status atualizado após o batch
            
            $totalCachesAtualizados = $estatisticasCache['dias_atualizados'] + 
                                     $estatisticasCache['semanas_atualizadas'] + 
                                     $estatisticasCache['meses_atualizados'];
            
            if ($estatisticasCache['parcial'] == false && 
                $checkpoint['completo'] == true) {
                $status['ultima_atualizacao_completa'] = date('Y-m-d H:i:s');
                log_message("Ciclo COMPLETO de atualização concluído com sucesso!");
            }
        } else {
            log_message("Nenhum novo dado encontrado neste micro-batch.");
            
            // Mesmo sem novos dados, podemos atualizar alguns timestamps dos caches existentes
            $offset = $status['batch_atual'] * 5; // Multiplicar por 5 para variar os caches atualizados
            $chaves = $cache->listarChaves($offset, 2); // Processar apenas 2 caches por vez
            
            foreach ($chaves as $chave) {
                if (tempo_esgotando($tempo_inicio, $tempo_limite, 5)) {
                    break;
                }
                
                $dados = $cache->get(md5($chave));
                if (!empty($dados)) {
                    $cache->set(md5($chave), $dados);
                    $totalCachesAtualizados++;
                }
            }
            
            // Avançar o batch mesmo sem dados para variar os caches atualizados
            $status['batch_atual']++;
            
            log_message("Atualizados timestamps de $totalCachesAtualizados caches existentes.");
        }
        
        // Salvar status atualizado
        $status['ultima_execucao_sucesso'] = true;
        salvarStatus($status);
        salvarCheckpoint($checkpoint);
        
        $executionTime = round(microtime(true) - $startTime, 2);
        log_message("Cache atualizado em {$executionTime}s");
        log_message("Total de caches atualizados: $totalCachesAtualizados");
        log_message("Dias: {$estatisticasCache['dias_atualizados']} | Semanas: {$estatisticasCache['semanas_atualizadas']} | Meses: {$estatisticasCache['meses_atualizados']}");
        
        return [
            'completo' => $checkpoint['completo'],
            'parcial' => $checkpoint['parcial'] || $estatisticasCache['parcial'],
            'caches_atualizados' => $totalCachesAtualizados,
            'estatisticas' => $estatisticasCache,
            'batch_atual' => $status['batch_atual']
        ];
    } catch (Exception $e) {
        log_message("Erro no processamento do cache: " . $e->getMessage(), true);
        
        // Atualizar status da execução como falha
        $status = carregarStatus();
        $status['ultima_execucao'] = date('Y-m-d H:i:s');
        $status['ultima_execucao_sucesso'] = false;
        salvarStatus($status);
        
        return [
            'completo' => false,
            'parcial' => true,
            'caches_atualizados' => 0
        ];
    } finally {
        // Garantir que a trava seja liberada mesmo em caso de erro
        liberarTrava();
    }
}

// Bloco principal - Otimizado para execução rápida
try {
    // Obter trava para evitar execuções concorrentes
    if (!obterTrava()) {
        log_message("Outra instância do script já está em execução. Saindo...");
        exit(0);
    }
    
    // Iniciar processamento
    log_message("===== INICIANDO PROCESSAMENTO DE CACHE OTIMIZADO =====");
    log_message("Tempo máximo de execução: " . MAX_EXECUTION_TIME . "s");
    
    // Carregar status e determinar se precisa forçar atualização completa
    $status = carregarStatus();
    $forcaAtualizacaoCompleta = precisaAtualizacaoCompleta($status);
    
    if ($forcaAtualizacaoCompleta) {
        log_message("Iniciando ATUALIZAÇÃO COMPLETA em micro-batches");
        // Resetar contadores de batch para iniciar do zero
        $status['batch_atual'] = 0;
        $status['total_registros_processados'] = 0;
        salvarStatus($status);
    } else {
        log_message("Continuando atualização incremental em micro-batches (batch: {$status['batch_atual']})");
    }
    
    $cacheData = armazenarCache($forcaAtualizacaoCompleta);
    
    $executionTime = round(microtime(true) - $tempo_inicio, 2);
    log_message("===== MICRO-BATCH CONCLUÍDO EM {$executionTime}s =====");
    
    if ($cacheData['parcial'] || !$cacheData['completo']) {
        log_message("PROGRESSO: Processamento continuará no próximo cron job (batch: {$cacheData['batch_atual']})");
    } else {
        log_message("SUCESSO: Micro-batch completado integralmente");
    }
    
    log_message("Total de caches atualizados: " . $cacheData['caches_atualizados']);
    
    // Liberar trava
    liberarTrava();
} catch (Throwable $e) {
    // Capturar qualquer erro não tratado
    $executionTime = round(microtime(true) - $tempo_inicio, 2);
    log_message("ERRO FATAL: " . $e->getMessage() . " em " . $e->getFile() . " linha " . $e->getLine(), true);
    log_message("===== PROCESSAMENTO INTERROMPIDO APÓS {$executionTime}s =====");
    
    // Liberar trava mesmo em caso de erro
    liberarTrava();
}
?>