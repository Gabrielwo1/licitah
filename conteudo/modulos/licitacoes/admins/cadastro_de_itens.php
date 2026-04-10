<?php
/**
 * Cron Job de Itens - VERSÃO ULTRA-LEVE
 * 
 * Otimizado para executar MUITO rapidamente com cron-job.org
 * Processa apenas 5 licitações por execução para evitar timeout
 */

// Configurações iniciais
error_reporting(E_ALL);
ini_set("display_errors", 0);
ini_set('max_execution_time', 180); // 3 minutos (MENOR)
ini_set('memory_limit', '128M');
set_time_limit(180);

// Configuração de cabeçalhos
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Parâmetros via URL (podem ser ajustados no cron-job.org)
$max_items = isset($_GET['max_items']) ? intval($_GET['max_items']) : 5; // APENAS 5 LICITAÇÕES POR PADRÃO!
$retries = isset($_GET['retries']) ? intval($_GET['retries']) : 1;
$debug = isset($_GET['debug']);

// Configurações ultra-leves
$CONFIG = [
    'max_execution_time' => 120,   // Apenas 2 minutos de execução
    'max_items_per_run' => $max_items, // 5 itens por execução por padrão!
    'batch_size' => 10,            // Lotes pequenos
    'api_timeout' => 10,           // Timeout da API reduzido
    'api_retries' => $retries,     // Tentativas para a API
    'debug' => $debug
];

// Definir caminho do lock file
$lock_file = __DIR__ . '/itens_cron_lock.txt';

// Verificar se já existe um processo rodando
if (file_exists($lock_file)) {
    $lock_time = filemtime($lock_file);
    if (time() - $lock_time < 300) { // Lock por 5 minutos
        echo "Outro processo já está em execução. Saindo.\n";
        exit;
    } else {
        // Lock muito antigo, provavelmente de um processo que falhou
        unlink($lock_file);
    }
}

// Criar arquivo de lock
file_put_contents($lock_file, date('Y-m-d H:i:s'));

// Registrar função para remover lock ao finalizar
register_shutdown_function(function() use ($lock_file) {
    if (file_exists($lock_file)) {
        unlink($lock_file);
    }
});

// Incluir arquivo de conexão
include __DIR__."/../../../../admin/conn.php";

// Variáveis globais
$start_time = microtime(true);

// Função para log simplificada
function log_message($message) {
    echo date('Y-m-d H:i:s') . " - $message\n";
}

// Verificar se o tempo está acabando
function is_time_running_out() {
    global $start_time, $CONFIG;
    return (microtime(true) - $start_time) > ($CONFIG['max_execution_time'] - 30);
}

// Classe de progresso simplificada
class ProgressTracker {
    private $filePath;
    private $progress;
    
    public function __construct($filePath = '../caches/itens_cronjob_progress.json') {
        $this->filePath = $filePath;
        
        if (!file_exists(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0777, true);
        }
        
        $this->loadProgress();
    }
    
    private function loadProgress() {
        if (file_exists($this->filePath)) {
            $data = file_get_contents($this->filePath);
            $this->progress = json_decode($data, true) ?: [];
        }
        
        if (empty($this->progress)) {
            $this->progress = [
                'last_processed_id' => 0,
                'processed_items' => [],
                'last_run' => null,
                'total_processed' => 0
            ];
        }
    }
    
    public function save() {
        $this->progress['last_run'] = date('Y-m-d H:i:s');
        file_put_contents($this->filePath, json_encode($this->progress, JSON_PRETTY_PRINT));
    }
    
    public function getLastProcessedId() {
        return $this->progress['last_processed_id'];
    }
    
    public function markAsProcessed($id) {
        $this->progress['last_processed_id'] = max($this->progress['last_processed_id'], $id);
        
        if ($id < $this->progress['last_processed_id']) {
            $this->progress['processed_items'][] = $id;
            $this->progress['processed_items'] = array_slice(array_unique($this->progress['processed_items']), -500);
        }
        
        $this->progress['total_processed']++;
        
        // Salvar a cada atualização
        $this->save();
    }
    
    public function isProcessed($id) {
        return $id <= $this->progress['last_processed_id'] || in_array($id, $this->progress['processed_items']);
    }
}

// Classe principal ultra-simplificada
class MicroProcessor {
    private $conn;
    private $progressTracker;
    private $apiUrl;
    private $maxItems;
    private $batchSize;
    private $apiTimeout;
    private $apiRetries;
    private $totalProcessed = 0;
    
    public function __construct($config) {
        $this->conn = conn();
        $this->conn->set_charset("utf8");
        $this->progressTracker = new ProgressTracker();
        $this->apiUrl = "https://dadosabertos.compras.gov.br/modulo-contratacoes/2.1_consultarItensContratacoes_PNCP_14133_Id?tipo=idCompra";
        $this->maxItems = $config['max_items_per_run'];
        $this->batchSize = $config['batch_size'];
        $this->apiTimeout = $config['api_timeout'];
        $this->apiRetries = $config['api_retries'];
    }
    
    public function processItems() {
        log_message("Iniciando processamento micro-lote (max: {$this->maxItems} licitações)");
        
        // Obter progresso atual
        $lastProcessedId = $this->progressTracker->getLastProcessedId();
        log_message("Último ID processado: $lastProcessedId");
        
        // Buscar apenas algumas licitações para processamento
        $licitacoes = $this->getLicitacoesSemItens($lastProcessedId, $this->maxItems);
        
        if (empty($licitacoes)) {
            log_message("Nenhuma licitação encontrada para processamento");
            return;
        }
        
        log_message("Encontradas " . count($licitacoes) . " licitações para processar");
        
        foreach ($licitacoes as $licitacao) {
            // Verificar se já processamos esse ID (dupla verificação)
            if ($this->progressTracker->isProcessed($licitacao['id'])) {
                log_message("Licitação {$licitacao['id']} já processada, pulando");
                continue;
            }
            
            log_message("Processando licitação ID: {$licitacao['id']} (Governo: {$licitacao['id_governo']})");
            
            // Verificar tempo antes de fazer requisição para a API
            if (is_time_running_out()) {
                log_message("Tempo quase esgotado, interrompendo processamento");
                break;
            }
            
            try {
                // Buscar itens da API
                $itens = $this->getItensFromAPI($licitacao['id_governo']);
                
                if (!empty($itens)) {
                    $this->processarItens($itens, $licitacao['id']);
                } else {
                    log_message("Nenhum item encontrado para a licitação {$licitacao['id']}");
                }
                
                // Marcar como processado independentemente do resultado
                $this->progressTracker->markAsProcessed($licitacao['id']);
                $this->totalProcessed++;
                
            } catch (Exception $e) {
                log_message("ERRO: " . $e->getMessage());
                
                // Garantir que o progresso seja salvo antes de sair em caso de erro
                $this->progressTracker->save();
                break;
            }
            
            // Verificar limites após cada licitação
            if (is_time_running_out() || $this->totalProcessed >= $this->maxItems) {
                log_message("Limite atingido após processar {$this->totalProcessed} licitações");
                break;
            }
        }
        
        log_message("Total processado: {$this->totalProcessed} licitações");
    }
    
    // Buscar licitações sem itens - versão ultra-simplificada
    private function getLicitacoesSemItens($lastId, $limit) {
        $sql = "SELECT l.licitacao_id, l.licitacao_governo 
                FROM licitacoes l 
                WHERE l.licitacao_id > ? 
                AND NOT EXISTS (
                    SELECT 1 FROM licitacoes_itens i 
                    WHERE i.licitacoes_item_licitacao = l.licitacao_id
                )
                ORDER BY l.licitacao_id 
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $lastId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $licitacoes = [];
        while ($row = $result->fetch_assoc()) {
            $licitacoes[] = [
                'id' => $row['licitacao_id'],
                'id_governo' => $row['licitacao_governo']
            ];
        }
        
        $stmt->close();
        return $licitacoes;
    }
    
    // Buscar itens da API - versão simplificada com retry
    private function getItensFromAPI($idCompra) {
        $url = "{$this->apiUrl}&codigo={$idCompra}";
        log_message("Consultando API: $url");
        
        for ($attempt = 0; $attempt <= $this->apiRetries; $attempt++) {
            if ($attempt > 0) {
                log_message("Tentativa $attempt para $idCompra");
                usleep(500000); // 500ms entre tentativas
            }
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->apiTimeout,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; LicitacoesCron/1.0)'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                
                if ($attempt == $this->apiRetries) {
                    throw new Exception("Falha na API após $attempt tentativas: $error");
                }
                
                continue;
            }
            
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Erro ao decodificar JSON");
                }
                
                return $data["resultado"] ?? [];
            } else {
                if ($attempt == $this->apiRetries) {
                    throw new Exception("HTTP Error $httpCode");
                }
            }
        }
        
        return [];
    }
    
    // Processar itens - versão ultra simplificada
    private function processarItens($itens, $idLicitacao) {
        if (empty($itens)) {
            return;
        }
        
        $this->conn->begin_transaction();
        
        try {
            // Processar em micro-lotes
            $chunks = array_chunk($itens, $this->batchSize);
            $totalInseridos = 0;
            
            foreach ($chunks as $chunk) {
                // Verificar tempo a cada lote
                if (is_time_running_out()) {
                    throw new Exception("Tempo de execução limite atingido");
                }
                
                $novosItens = $this->inserirItens($chunk, $idLicitacao);
                $totalInseridos += $novosItens;
            }
            
            // Atualizar referências apenas se inserimos algum item
            if ($totalInseridos > 0) {
                $this->atualizarReferencias($idLicitacao);
            }
            
            $this->conn->commit();
            log_message("Inseridos $totalInseridos itens para licitação $idLicitacao");
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw new Exception("Erro processando itens: " . $e->getMessage());
        }
    }
    
    // Inserir itens - versão simplificada
    private function inserirItens($itens, $idLicitacao) {
        $totalInseridos = 0;
        
        foreach ($itens as $item) {
            $codigo = $item['idCompraItem'] ?? '';
            
            if (empty($codigo)) {
                continue;
            }
            
            // Verificar se já existe
            $check = $this->conn->prepare("SELECT 1 FROM licitacoes_itens WHERE licitacoes_item_codigo = ?");
            $check->bind_param("s", $codigo);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                $check->close();
                continue;
            }
            
            $check->close();
            
            // Inserir item
            $descricao = $item['descricaoResumida'] ?? '';
            $beneficio = $item['tipoBeneficioNome'] ?? '';
            $att = $item['dataAtualizacaoPncp'] ?? date('Y-m-d H:i:s');
            $hash = $this->gerarHash();
            
            $stmt = $this->conn->prepare("INSERT INTO licitacoes_itens 
                (licitacoes_item_codigo, licitacoes_item_desc, licitacoes_item_beneficio, 
                licitacoes_item_att, licitacoes_item_hash, licitacoes_item_licitacao) 
                VALUES (?, ?, ?, ?, ?, ?)");
                
            $stmt->bind_param("sssssi", $codigo, $descricao, $beneficio, $att, $hash, $idLicitacao);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $idItem = $this->conn->insert_id;
                $totalInseridos++;
                
                // Inserir apenas os metadados essenciais
                $this->inserirMetasEssenciais($item, $idItem);
            }
            
            $stmt->close();
        }
        
        return $totalInseridos;
    }
    
    // Inserir apenas metadados essenciais
    private function inserirMetasEssenciais($item, $idItem) {
        // Lista de metadados prioritários
        $chavesPrioritarias = ['quantidadeHomologada', 'valorUnitarioHomologado', 'valorTotalHomologado'];
        
        foreach ($chavesPrioritarias as $chave) {
            if (isset($item[$chave]) && $item[$chave] !== null) {
                $valor = $item[$chave];
                
                $stmt = $this->conn->prepare("INSERT INTO licitacoes_itens_meta 
                    (lim_licitacoes_item, lim_chave, lim_valor) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $idItem, $chave, $valor);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    
    // Atualizar referências da licitação - versão ultra leve
    private function atualizarReferencias($idLicitacao) {
        // Buscar IDs com uma única consulta eficiente
        $stmt = $this->conn->prepare("SELECT GROUP_CONCAT(licitacoes_item_id) AS ids FROM licitacoes_itens WHERE licitacoes_item_licitacao = ?");
        $stmt->bind_param("i", $idLicitacao);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $ids_string = $row['ids'];
            
            if (!empty($ids_string)) {
                $idsItens = explode(',', $ids_string);
                $itens_ids = json_encode($idsItens);
                
                // Atualizar licitação
                $update = $this->conn->prepare("UPDATE licitacoes SET licitacao_itens = ? WHERE licitacao_id = ?");
                $update->bind_param("si", $itens_ids, $idLicitacao);
                $update->execute();
                $update->close();
            }
        }
        
        $stmt->close();
    }
    
    // Gerar hash simples
    private function gerarHash($length = 16) {
        $bytes = random_bytes($length / 2);
        return bin2hex($bytes);
    }
}

// Execução principal - super simplificada
try {
    log_message("=== INICIANDO MICRO-PROCESSAMENTO DE ITENS ===");
    
    // Criar e executar processador
    $processor = new MicroProcessor($CONFIG);
    $processor->processItems();
    
    $execution_time = round(microtime(true) - $start_time, 2);
    log_message("Execução concluída em $execution_time segundos");
} catch (Exception $e) {
    log_message("ERRO CRÍTICO: " . $e->getMessage());
} finally {
    log_message("=== MICRO-PROCESSAMENTO FINALIZADO ===");
}
?>