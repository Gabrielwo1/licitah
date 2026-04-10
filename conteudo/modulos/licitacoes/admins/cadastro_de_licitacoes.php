<?php
/**
 * Atualizador de Licitações - Versão Ultra Otimizada
 * 
 * Esta versão contém melhorias avançadas para evitar qualquer interrupção:
 * - Sistema de checkpoint para geração de cache
 * - Tamanhos de lote menores
 * - Melhor sistema de retomada
 * - Logs detalhados
 */

// Configurações básicas
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 240); // 4 minutos (para ter margem)
set_time_limit(240);
ini_set('memory_limit', '256M');
ini_set('mysqli.default_socket_timeout', 60);

// Configurações ajustáveis
$CONFIG = [
    'max_execution_time' => 200,       // Tempo máximo de execução (segundos) - menor que o limite do PHP
    'database_timeout' => 20,          // Timeout para consultas de banco de dados (segundos)
    'batch_size_api' => 30,            // Itens por página da API (reduzido para ser mais rápido)
    'batch_size_db' => 100,            // Tamanho do lote para operações no banco de dados
    'max_parallel_requests' => 2,      // Requisições paralelas
    'cache_chunk_size' => 200,         // Tamanho do lote para geração de cache (menor para evitar timeout)
    'debug' => false                   // Modo debug
];

// Configurações de cabeçalho
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Parâmetros
$reset = isset($_GET['reset']) ? (bool)$_GET['reset'] : false;
$apenas_cache = isset($_GET['apenas_cache']) ? (bool)$_GET['apenas_cache'] : false;
$max_modalidades = isset($_GET['max_modalidades']) ? (int)$_GET['max_modalidades'] : 1;
$max_paginas = isset($_GET['max_paginas']) ? (int)$_GET['max_paginas'] : 3;
$debug = isset($_GET['debug']) ? (bool)$_GET['debug'] : $CONFIG['debug'];

// Variáveis globais
$START_TIME = microtime(true);
$MAX_EXECUTION = $CONFIG['max_execution_time'];
$BATCH_SIZE = $CONFIG['batch_size_api'];
$MAX_PARALLEL_REQUESTS = $CONFIG['max_parallel_requests'];
$PROCESSING_ENABLED = true;

// Cache lock e checkpoint
$cache_lock_file = __DIR__ . '/cache_lock.txt';
$cache_checkpoint_file = __DIR__ . '/cache_checkpoint.json';

// Verificar se há outro processo gerando cache
if (file_exists($cache_lock_file) && (time() - filemtime($cache_lock_file)) < 600) {
    echo "Outro processo já está gerando cache. Pulando geração.\n";
    $apenas_cache = false;
} elseif ($apenas_cache) {
    file_put_contents($cache_lock_file, date('Y-m-d H:i:s'));
}

// Incluir arquivo de conexão
include __DIR__."/../../../../admin/conn.php";

// Configuração de log
function logMessage($message, $important = false, $debug_only = false) {
    global $debug;
    
    if ($debug_only && !$debug) {
        return;
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logLine = "[$timestamp] $message";
    echo $logLine . PHP_EOL;
    
    if ($important) {
        $logFile = __DIR__ . '/cron_log.txt';
        file_put_contents($logFile, $logLine . PHP_EOL, FILE_APPEND);
    }
}

// Verificar tempo de execução
function isTimeRunningOut($buffer_seconds = 30) {
    global $START_TIME, $MAX_EXECUTION;
    $elapsed = microtime(true) - $START_TIME;
    return ($elapsed > ($MAX_EXECUTION - $buffer_seconds));
}

// Executar consulta SQL com timeout
function executarComTimeout($conn, $sql, $timeoutSegundos = 20) {
    try {
        // Tentar definir o timeout
        $conn->query("SET SESSION max_statement_time = " . intval($timeoutSegundos));
        
        // Executar a consulta
        $result = $conn->query($sql);
        
        // Restaurar configuração
        $conn->query("SET SESSION max_statement_time = 0");
        
        return $result;
    } catch (Exception $e) {
        logMessage("Erro na consulta com timeout: " . $e->getMessage(), true);
        return false;
    }
}

/**
 * Gerenciador de estado - Mantém o progresso entre execuções
 */
class StateManager {
    private $stateFile;
    private $state;
    private $isModified = false;
    
    public function __construct($stateFile = null) {
        $this->stateFile = $stateFile ?: __DIR__ . '/cron_state.json';
        $this->loadState();
    }
    
    private function loadState() {
        if (file_exists($this->stateFile)) {
            $content = file_get_contents($this->stateFile);
            $this->state = json_decode($content, true);
            
            if (!$this->state) {
                $this->initState();
            }
        } else {
            $this->initState();
        }
    }
    
    private function initState() {
        $this->state = [
            'current_modality_index' => 0,
            'current_page' => 1,
            'completed' => false,
            'cache_generated' => false,
            'last_run' => null,
            'total_processed' => 0,
            'new_items' => 0,
            'last_processed_modality' => null,
            'remaining_pages' => 0,
            'last_error' => null,
            'pending_insertions' => []
        ];
        $this->isModified = true;
        $this->saveState();
    }
    
    public function saveState() {
        if (!$this->isModified) {
            return;
        }
        
        $this->state['last_run'] = date('Y-m-d H:i:s');
        file_put_contents($this->stateFile, json_encode($this->state, JSON_PRETTY_PRINT));
        $this->isModified = false;
    }
    
    public function incrementProcessedCount($count = 1) {
        $this->state['total_processed'] += $count;
        $this->isModified = true;
    }
    
    public function incrementNewItems($count = 1) {
        $this->state['new_items'] += $count;
        $this->isModified = true;
    }
    
    public function setLastError($error) {
        $this->state['last_error'] = $error;
        $this->isModified = true;
        $this->saveState(); // Save immediately on error
    }
    
    public function getCurrentModalityIndex() {
        return $this->state['current_modality_index'];
    }
    
    public function getCurrentPage() {
        return $this->state['current_page'];
    }
    
    public function isCompleted() {
        return $this->state['completed'];
    }
    
    public function isCacheGenerated() {
        return $this->state['cache_generated'];
    }
    
    public function setCurrentModalityIndex($index) {
        $this->state['current_modality_index'] = $index;
        $this->state['last_processed_modality'] = $index;
        $this->isModified = true;
    }
    
    public function setCurrentPage($page) {
        $this->state['current_page'] = $page;
        $this->isModified = true;
    }
    
    public function setRemainingPages($pages) {
        $this->state['remaining_pages'] = $pages;
        $this->isModified = true;
    }
    
    public function getRemainingPages() {
        return $this->state['remaining_pages'];
    }
    
    public function setCompleted($completed) {
        $this->state['completed'] = $completed;
        $this->isModified = true;
    }
    
    public function setCacheGenerated($generated) {
        $this->state['cache_generated'] = $generated;
        $this->isModified = true;
    }
    
    public function resetState() {
        $this->initState();
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function addPendingInsertions($items) {
        if (!empty($items)) {
            $this->state['pending_insertions'] = array_merge($this->state['pending_insertions'], $items);
            $this->isModified = true;
        }
    }
    
    public function getPendingInsertions() {
        return $this->state['pending_insertions'];
    }
    
    public function clearPendingInsertions() {
        $this->state['pending_insertions'] = [];
        $this->isModified = true;
    }
}

/**
 * Gerenciador de Checkpoint para geração de cache
 */
class CacheCheckpointManager {
    private $checkpointFile;
    private $checkpoint;
    
    public function __construct($checkpointFile = null) {
        $this->checkpointFile = $checkpointFile ?: __DIR__ . '/cache_checkpoint.json';
        $this->loadCheckpoint();
    }
    
    private function loadCheckpoint() {
        if (file_exists($this->checkpointFile)) {
            $content = file_get_contents($this->checkpointFile);
            $this->checkpoint = json_decode($content, true);
            
            if (!$this->checkpoint) {
                $this->initCheckpoint();
            }
        } else {
            $this->initCheckpoint();
        }
    }
    
    private function initCheckpoint() {
        $this->checkpoint = [
            'periodos_processados' => [],
            'ultimo_periodo' => null,
            'ultimo_offset' => 0,
            'ultima_execucao' => null,
            'completo' => false
        ];
        $this->saveCheckpoint();
    }
    
    public function saveCheckpoint() {
        $this->checkpoint['ultima_execucao'] = date('Y-m-d H:i:s');
        file_put_contents($this->checkpointFile, json_encode($this->checkpoint, JSON_PRETTY_PRINT));
    }
    
    public function resetCheckpoint() {
        $this->initCheckpoint();
    }
    
    public function getPeriodosProcessados() {
        if(isset($this->checkpoint['periodos_processados'])){
            return $this->checkpoint['periodos_processados'];
        }
        
        return [];
        
    }
    
    public function marcarPeriodoCompleto($periodo) {
        if(isset($this->checkpoint['periodos_processados'])){
            if (!in_array($periodo, $this->checkpoint['periodos_processados'])) {
                $this->checkpoint['periodos_processados'][] = $periodo;
            }
        }
        
        $this->saveCheckpoint();
    }
    
    public function isPeriodoProcessado($periodo) {
        if(isset($this->checkpoint['periodos_processados'])){
            return in_array($periodo, $this->checkpoint['periodos_processados']);
        }
        
        return false;
        
    }
    
    public function setUltimoPeriodo($periodo) {
        $this->checkpoint['ultimo_periodo'] = $periodo;
        $this->saveCheckpoint();
    }
    
    public function getUltimoPeriodo() {
        if(isset($this->checkpoint['ultimo_periodo'])){
            return $this->checkpoint['ultimo_periodo'];
        }
        
        return [];
    }
    
    public function setUltimoOffset($offset) {
        $this->checkpoint['ultimo_offset'] = $offset;
        $this->saveCheckpoint();
    }
    
    public function getUltimoOffset() {
        if(isset($this->checkpoint['ultimo_offset'])){
            return $this->checkpoint['ultimo_offset'];
        }
        
        return [];
    }
    
    public function setCompleto($completo) {
        $this->checkpoint['completo'] = $completo;
        $this->saveCheckpoint();
    }
    
    public function isCompleto() {
        return $this->checkpoint['completo'];
    }
}

/**
 * Processador de requisições assíncronas
 */
class AsyncRequestProcessor {
    private $urls = [];
    private $results = [];
    private $maxParallel;
    
    public function __construct($maxParallel = 2) {
        $this->maxParallel = $maxParallel;
    }
    
    public function addRequest($url, $id = null) {
        $this->urls[$id ?? count($this->urls)] = $url;
        return $this;
    }
    
    public function execute() {
        if (empty($this->urls)) {
            return $this->results;
        }
        
        // Inicializar cURL Multi
        $mh = curl_multi_init();
        $handles = [];
        
        // Adicionar todas as URLs
        foreach ($this->urls as $id => $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; LicitacoesCron/1.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            curl_multi_add_handle($mh, $ch);
            $handles[$id] = $ch;
        }
        
        // Executar as requisições em paralelo
        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) {
                curl_multi_select($mh);
            }
        } while ($running && $status == CURLM_OK);
        
        // Coletar os resultados
        foreach ($handles as $id => $ch) {
            $content = curl_multi_getcontent($ch);
            $this->results[$id] = json_decode($content, true);
            curl_multi_remove_handle($mh, $ch);
        }
        
        // Fechar cURL Multi
        curl_multi_close($mh);
        
        return $this->results;
    }
    
    public function getResults() {
        return $this->results;
    }
}

/**
 * Processador de Licitações
 */
class LicitacoesProcessor {
    private $conn;
    private $stateManager;
    private $cacheCheckpointManager;
    private $modalidades = [1, 2, 3, 5, 6, 7, 20, 22, 33, 44, 57];
    private $baseUrl = "https://dadosabertos.compras.gov.br/modulo-contratacoes/1_consultarContratacoes_PNCP_14133";
    private $inicio;
    private $fim;
    private $batchSize;
    private $maxParallelRequests;
    private $maxModalidades;
    private $maxPaginas;
    private $dbBatchSize;
    private $cacheChunkSize;
    private $dbTimeout;
    
    public function __construct(StateManager $stateManager, CacheCheckpointManager $cacheCheckpointManager, $config) {
        $this->conn = conn();
        $this->stateManager = $stateManager;
        $this->cacheCheckpointManager = $cacheCheckpointManager;
        $this->fim = date('Y-m-d');
        $this->inicio = date('Y-m-d', strtotime('-2 day'));
        $this->batchSize = $config['batch_size_api'];
        $this->maxParallelRequests = $config['max_parallel_requests'];
        $this->maxModalidades = $config['max_modalidades'];
        $this->maxPaginas = $config['max_paginas'];
        $this->dbBatchSize = $config['batch_size_db'];
        $this->cacheChunkSize = $config['cache_chunk_size'];
        $this->dbTimeout = $config['database_timeout'];
    }
    
    /**
     * Processa os dados de licitações de forma otimizada
     */
    public function process() {
        global $PROCESSING_ENABLED;
        
        logMessage("Iniciando processamento assíncrono de licitações", true);
        
        // Se já completou e gerou cache, resetamos para um novo ciclo
        if ($this->stateManager->isCompleted() && $this->stateManager->isCacheGenerated()) {
            logMessage("Ciclo anterior completo, iniciando novo ciclo", true);
            $this->stateManager->resetState();
            $this->cacheCheckpointManager->resetCheckpoint();
        }
        
        // Se completou mas não gerou cache, geramos o cache
        if ($this->stateManager->isCompleted() && !$this->stateManager->isCacheGenerated()) {
            logMessage("Todas as modalidades processadas, gerando cache", true);
            // $resultado = $this->gerarCache();
            
            // if ($resultado || isTimeRunningOut()) {
                $this->stateManager->setCacheGenerated(true);
                $this->stateManager->saveState();
                logMessage("Cache gerado com sucesso. Ciclo completo.", true);
            // } else {
            //     logMessage("Geração de cache incompleta, será retomada na próxima execução", true);
            // }
            return;
        }
        
        // Processar pendências de inserção primeiro
        $this->processarInserçõesPendentes();
        
        // Se estiver no limite de tempo, encerrar processamento
        if (isTimeRunningOut() || !$PROCESSING_ENABLED) {
            logMessage("Tempo de execução próximo do limite ou processamento desativado, finalizando", true);
            $this->stateManager->saveState();
            return;
        }
        
        // Preparar as requisições assíncronas
        $this->processarRequisições();
    }
    
    /**
     * Processa pendências de inserção (licitações já baixadas mas não inseridas)
     */
    private function processarInserçõesPendentes() {
        $pendencias = $this->stateManager->getPendingInsertions();
        
        if (empty($pendencias)) {
            return;
        }
        
        logMessage("Processando " . count($pendencias) . " licitações pendentes", true);
        
        // Processar em lotes pequenos
        $chunks = array_chunk($pendencias, 20);
        $processados = 0;
        
        foreach ($chunks as $chunk) {
            if (isTimeRunningOut()) {
                logMessage("Tempo de execução próximo do limite. Interrompendo processamento de pendências.", true);
                break;
            }
            
            $this->inserirLicitacoes($chunk);
            $processados += count($chunk);
            
            // Remover itens processados das pendências
            $this->stateManager->clearPendingInsertions();
            if ($processados < count($pendencias)) {
                $this->stateManager->addPendingInsertions(array_slice($pendencias, $processados));
            }
            $this->stateManager->saveState();
            
            // Verificar tempo a cada lote
            if (isTimeRunningOut()) {
                break;
            }
        }
        
        logMessage("Processadas $processados de " . count($pendencias) . " pendências", true);
    }
    
    /**
     * Processa requisições assíncronas para obter dados de licitações
     */
    private function processarRequisições() {
        global $PROCESSING_ENABLED;
        
        $currentModalityIndex = $this->stateManager->getCurrentModalityIndex();
        $currentPage = $this->stateManager->getCurrentPage();
        $remainingPages = $this->stateManager->getRemainingPages();
        
        // Verificar se já processamos todas as modalidades
        if ($currentModalityIndex >= count($this->modalidades)) {
            logMessage("Todas as modalidades processadas, marcando como completo", true);
            $this->stateManager->setCompleted(true);
            $this->stateManager->saveState();
            return;
        }
        
        // Quantas modalidades processar
        $modalidadesProcessadas = 0;
        $totalPaginasProcessadas = 0;
        
        // Processar modalidades até o limite ou até acabar o tempo
        while ($modalidadesProcessadas < $this->maxModalidades && 
               $currentModalityIndex < count($this->modalidades) && 
               !isTimeRunningOut() && 
               $PROCESSING_ENABLED) {
            
            $modalidade = $this->modalidades[$currentModalityIndex];
            logMessage("Processando modalidade {$modalidade}", true);
            
            // Resetar contagem de páginas para esta modalidade
            $paginasProcessadas = 0;
            
            // Processar páginas até o limite, acabar o tempo, ou não haver mais páginas
            while ($paginasProcessadas < $this->maxPaginas && 
                   ($remainingPages > 0 || $paginasProcessadas == 0) && 
                   !isTimeRunningOut() && 
                   $PROCESSING_ENABLED) {
                
                // Preparar lote de URLs para processar em paralelo
                $this->processarLoteDeRequisicoes($modalidade, $currentPage, $this->maxParallelRequests);
                
                // Atualizar contadores
                $paginasProcessadas += $this->maxParallelRequests;
                $totalPaginasProcessadas += $this->maxParallelRequests;
                
                // Verificar se acabou o tempo
                if (isTimeRunningOut()) {
                    logMessage("Tempo de execução próximo do limite, interrompendo processamento", true);
                    break;
                }
                
                // Verificar se ainda temos páginas para processar
                $remainingPages = $this->stateManager->getRemainingPages();
                if ($remainingPages <= 0 && $paginasProcessadas > 0) {
                    logMessage("Modalidade {$modalidade} concluída", true);
                    
                    // Avançar para próxima modalidade
                    $currentModalityIndex++;
                    $this->stateManager->setCurrentModalityIndex($currentModalityIndex);
                    $currentPage = 1;
                    $this->stateManager->setCurrentPage($currentPage);
                    break;
                }
                
                // Atualizar página atual
                $currentPage += $this->maxParallelRequests;
                $this->stateManager->setCurrentPage($currentPage);
                $this->stateManager->saveState();
            }
            
            // Se não avançamos para a próxima modalidade, é porque atingimos o limite de páginas
            if ($currentModalityIndex == $this->stateManager->getCurrentModalityIndex() && 
                $paginasProcessadas >= $this->maxPaginas && 
                $remainingPages > 0) {
                logMessage("Atingido limite de {$this->maxPaginas} páginas para a modalidade {$modalidade}", true);
                $this->stateManager->saveState();
                break;
            }
            
            $modalidadesProcessadas++;
        }
        
        logMessage("Processadas {$totalPaginasProcessadas} páginas de {$modalidadesProcessadas} modalidades", true);
        
        // Verificar se acabamos todas as modalidades
        if ($currentModalityIndex >= count($this->modalidades)) {
            logMessage("Todas as modalidades processadas, marcando como completo", true);
            $this->stateManager->setCompleted(true);
        }
        
        $this->stateManager->saveState();
        
        // Se estiver completo e não gerou cache, tenta gerar agora
        if ($this->stateManager->isCompleted() && !$this->stateManager->isCacheGenerated() && !isTimeRunningOut()) {
            logMessage("Gerando cache após completar todas as modalidades", true);
            // $resultado = $this->gerarCache();
            // if ($resultado || isTimeRunningOut()) {
                $this->stateManager->setCacheGenerated(true);
                $this->stateManager->saveState();
            // }
        }
    }
    
    // Outras funções permaneceram as mesmas...
    
    /**
     * Processa um lote de requisições em paralelo
     */
    private function processarLoteDeRequisicoes($modalidade, $paginaInicial, $numRequisicoes) {
        global $PROCESSING_ENABLED;
        
        // Criar processador de requisições assíncronas
        $requestProcessor = new AsyncRequestProcessor($numRequisicoes);
        
        // Adicionar URLs para cada página
        for ($i = 0; $i < $numRequisicoes; $i++) {
            $pagina = $paginaInicial + $i;
            $url = $this->construirUrl($modalidade, $pagina);
            $requestProcessor->addRequest($url, $pagina);
        }
        
        // Executar requisições em paralelo
        logMessage("Executando {$numRequisicoes} requisições em paralelo para modalidade {$modalidade} a partir da página {$paginaInicial}");
        $results = $requestProcessor->execute();
        
        // Processar resultados
        $ultimaPagina = 0;
        $paginasRestantes = 0;
        $totalProcessado = 0;
        
        foreach ($results as $pagina => $result) {
            if (isTimeRunningOut() || !$PROCESSING_ENABLED) {
                logMessage("Tempo de execução próximo do limite ou processamento desativado, interrompendo processamento de resultados", true);
                break;
            }
            
            if (!$result || !isset($result['resultado'])) {
                logMessage("Erro ou resposta vazia para página {$pagina}");
                continue;
            }
            
            $licitacoes = $result['resultado'];
            $count = count($licitacoes);
            
            // Atualizar estatísticas
            $totalProcessado += $count;
            $this->stateManager->incrementProcessedCount($count);
            
            // Atualizar informações de paginação
            if (isset($result['paginasRestantes'])) {
                $paginasRestantes = intval($result['paginasRestantes']);
            }
            
            if ($pagina > $ultimaPagina) {
                $ultimaPagina = $pagina;
            }
            
            logMessage("Página {$pagina}: Encontradas {$count} licitações");
            
            // Processar as licitações encontradas
            if ($count > 0) {
                $this->verificarLicitacoes($licitacoes);
            }
        }
        
        // Atualizar páginas restantes no estado
        $this->stateManager->setRemainingPages($paginasRestantes);
        $this->stateManager->saveState();
        
        logMessage("Modalidade {$modalidade}: Processadas {$totalProcessado} licitações, {$paginasRestantes} páginas restantes");
        
        return $paginasRestantes;
    }
    
    /**
     * Constrói URL para a API
     */
    private function construirUrl($modalidade, $pagina) {
        return "{$this->baseUrl}?pagina={$pagina}&tamanhoPagina={$this->batchSize}&dataPublicacaoPncpInicial={$this->inicio}&dataPublicacaoPncpFinal={$this->fim}&codigoModalidade={$modalidade}";
    }
    
    /**
     * Verifica quais licitações precisam ser inseridas e filtra as já existentes
     */
    private function verificarLicitacoes($licitacoes) {
        if (empty($licitacoes)) {
            return;
        }

        try {
            $ids = [];
            
            foreach ($licitacoes as $valor) {
                $ids[] = $valor["idCompra"];
            }
            
            if (empty($ids)) {
                return;
            }
            
            $valores_sql = implode(",", $ids);
            
            // Verificar licitações já cadastradas
            $sql = "SELECT DISTINCT licitacao_governo FROM licitacoes WHERE licitacao_governo IN ({$valores_sql})";
            
            $resultado = $this->conn->query($sql);
            
            if (!$resultado) {
                throw new Exception("Erro ao consultar banco: " . $this->conn->error);
            }
            
            $cadastradas = [];
            if ($resultado->num_rows > 0) {
                while ($dado = $resultado->fetch_assoc()) {
                    $cadastradas[] = $dado['licitacao_governo'];
                }
            }
            
            $licitacoes_diferentes = array_diff($ids, $cadastradas);
            
            if (empty($licitacoes_diferentes)) {
                logMessage("Todas as licitações já cadastradas");
                return;
            }
            
            $licitacoes_nao_cadastradas = array_filter($licitacoes, function ($licitacao) use ($licitacoes_diferentes) {
                return in_array($licitacao['idCompra'], $licitacoes_diferentes);
            });
            
            if (empty($licitacoes_nao_cadastradas)) {
                return;
            }
            
            $totalNovo = count($licitacoes_nao_cadastradas);
            logMessage("Encontradas {$totalNovo} novas licitações", true);
            $this->stateManager->incrementNewItems($totalNovo);
            
            // Verificar se estamos próximos do limite de tempo
            if (isTimeRunningOut()) {
                // Armazenar para processamento futuro
                logMessage("Tempo quase esgotado, armazenando {$totalNovo} licitações para processamento futuro", true);
                $this->stateManager->addPendingInsertions(array_values($licitacoes_nao_cadastradas));
                $this->stateManager->saveState();
                return;
            }
            
            // Processar em lotes pequenos
            $chunks = array_chunk(array_values($licitacoes_nao_cadastradas), 10);
            
            foreach ($chunks as $index => $chunk) {
                if (isTimeRunningOut()) {
                    // Armazenar o restante para processamento futuro
                    $processados = ($index) * 10;
                    $pendencias = array_slice(array_values($licitacoes_nao_cadastradas), $processados);
                    
                    if (!empty($pendencias)) {
                        logMessage("Tempo quase esgotado, armazenando " . count($pendencias) . " licitações para processamento futuro", true);
                        $this->stateManager->addPendingInsertions($pendencias);
                        $this->stateManager->saveState();
                    }
                    break;
                }
                
                $this->inserirLicitacoes($chunk);
            }
        } catch (Exception $e) {
            logMessage("Erro ao processar licitações: " . $e->getMessage(), true);
            $this->stateManager->setLastError($e->getMessage());
        }
    }
    
    /**
     * Insere as licitações no banco de dados
     */
    private function inserirLicitacoes($licitacoes) {
        if (empty($licitacoes)) {
            return;
        }
        
        $this->conn->begin_transaction();
        
        try {
            $valores = [];

            foreach ($licitacoes as $licitacao) {
                $id_governo = mysqli_real_escape_string($this->conn, $licitacao['idCompra']);
                $hash = mysqli_real_escape_string($this->conn, $this->gerarHash());
                $url = mysqli_real_escape_string($this->conn, $this->gerarHash(16));
                $infos = mysqli_real_escape_string($this->conn, $licitacao['informacaoComplementar'] ?? '');
                $objeto = mysqli_real_escape_string($this->conn, $licitacao['objetoCompra']);
                $att = mysqli_real_escape_string($this->conn, $licitacao['dataAtualizacaoPncp']);
                
                $valores[] = "('$id_governo', '$objeto', '$hash', '$url', '$att', '$infos')";
            }
            
            // Inserir licitações
            $sql = "INSERT INTO licitacoes (licitacao_governo, licitacao_objeto, licitacao_hash, licitacao_url, licitacao_att, licitacao_infos) 
                    VALUES " . implode(",", $valores);
            
            $resultado = $this->conn->query($sql);
            
            if (!$resultado) {
                throw new Exception("Erro ao inserir licitações: " . $this->conn->error);
            }
            
            $insert_id = $this->conn->insert_id;
            
            if ($insert_id === 0) {
                throw new Exception("Erro: Não foi gerado um ID de inserção.");
            }
            
            $primeiro_id = $insert_id;
            $i = 0;
            $chaves_excluidas = ['idCompra', 'informacaoComplementar', 'objetoCompra', 'dataAualizacaoPncp'];
            
            // Inserir meta dados
            foreach ($licitacoes as $licitacao) {
                $id = $primeiro_id + $i;
                $valoresMeta = [];
                $placeholdersMeta = [];
                
                foreach ($licitacao as $chave => $valor) {
                    if (!in_array($chave, $chaves_excluidas)) {
                        $valoresMeta[] = $id;
                        $valoresMeta[] = $chave;
                        $valoresMeta[] = $valor;
                        $placeholdersMeta[] = "(?, ?, ?)";
                    }
                }
                
                if (!empty($placeholdersMeta)) {
                    $sqlMeta = "INSERT INTO licitacoes_meta (lm_licitacao, lm_chave, lm_valor) VALUES " . implode(", ", $placeholdersMeta);
                    $stmtMeta = $this->conn->prepare($sqlMeta);
                    
                    if (!$stmtMeta) {
                        throw new Exception("Erro ao preparar consulta meta: " . $this->conn->error);
                    }
                    
                    $stmtMeta->bind_param(str_repeat("iss", count($placeholdersMeta)), ...$valoresMeta);
                    $stmtMeta->execute();
                    $stmtMeta->close();
                }
                
                $i++;
            }
            
            $this->conn->commit();
            logMessage("Lote de " . count($licitacoes) . " licitações inserido com sucesso");
        } catch (Exception $e) {
            $this->conn->rollback();
            logMessage("Erro ao inserir licitações: " . $e->getMessage(), true);
            $this->stateManager->setLastError($e->getMessage());
        }
    }
    
    /**
     * Gera o cache de licitações de forma otimizada e incremental
     * Usa sistema de checkpoint para continuar caso seja interrompido
     */
    public function gerarCache() {
        global $PROCESSING_ENABLED;
        
        try {
            logMessage("Iniciando geração de cache otimizada", true);
            $cache = new FileCache();
            
            // Verificar se o cache já está completo
            if ($this->cacheCheckpointManager->isCompleto()) {
                logMessage("Cache já está completo, resetando para novo ciclo", true);
                $this->cacheCheckpointManager->resetCheckpoint();
            }
            
            // Processar cada período separadamente
            $periodos = ['hoje', 'semana', 'mes'];
            $periodos_restantes = array_diff($periodos, $this->cacheCheckpointManager->getPeriodosProcessados());
            
            // Se não há mais períodos para processar, marcar como completo
            if (empty($periodos_restantes)) {
                logMessage("Todos os períodos já foram processados. Cache completo.", true);
                $this->cacheCheckpointManager->setCompleto(true);
                return true;
            }
            
            // Continuar de onde parou
            $ultimo_periodo = $this->cacheCheckpointManager->getUltimoPeriodo();
            $ultimo_offset = $this->cacheCheckpointManager->getUltimoOffset();
            
            if ($ultimo_periodo && !$this->cacheCheckpointManager->isPeriodoProcessado($ultimo_periodo)) {
                // Continuar o período que foi interrompido
                logMessage("Continuando geração do cache para período $ultimo_periodo a partir do offset $ultimo_offset", true);
                $this->processarPeriodoCache($ultimo_periodo, $ultimo_offset);
            }
            
            // Processar os períodos restantes
            foreach ($periodos_restantes as $periodo) {
                if (isTimeRunningOut(60) || !$PROCESSING_ENABLED) {
                    logMessage("Tempo de execução próximo do limite, interrompendo geração de cache", true);
                    return false;
                }
                
                // Pular períodos já processados
                if ($this->cacheCheckpointManager->isPeriodoProcessado($periodo)) {
                    continue;
                }
                
                // Processar período a partir do início (offset 0)
                if ($periodo != $ultimo_periodo) {
                    logMessage("Gerando cache para período: $periodo", true);
                    $this->processarPeriodoCache($periodo, 0);
                }
            }
            
            // Verificar se todos os períodos foram processados
            $todos_processados = true;
            foreach ($periodos as $periodo) {
                if (!$this->cacheCheckpointManager->isPeriodoProcessado($periodo)) {
                    $todos_processados = false;
                    break;
                }
            }
            
            if ($todos_processados) {
                logMessage("Cache gerado com sucesso para todos os períodos", true);
                $this->cacheCheckpointManager->setCompleto(true);
                return true;
            } else {
                logMessage("Geração de cache não foi concluída, será retomada na próxima execução", true);
                return false;
            }
        } catch (Exception $e) {
            logMessage("Erro ao gerar cache: " . $e->getMessage(), true);
            $this->stateManager->setLastError($e->getMessage());
            return false;
        }
    }
    
    /**
     * Processa um período específico do cache
     */
    private function processarPeriodoCache($periodo, $offset = 0) {
        $cache = new FileCache();
        $this->cacheCheckpointManager->setUltimoPeriodo($periodo);
        $this->cacheCheckpointManager->setUltimoOffset($offset);
        
        try {
            // Obter dados incrementalmente em pequenos lotes
            $dados = [];
            $completo = false;
            $current_offset = $offset;
            
            while (!$completo && !isTimeRunningOut(60)) {
                $lote = $this->buscarDadosPorPeriodoIncremental($periodo, $current_offset, $this->cacheChunkSize);
                
                if (empty($lote)) {
                    $completo = true;
                    break;
                }
                
                $dados = array_merge($dados, $lote);
                $current_offset += count($lote);
                $this->cacheCheckpointManager->setUltimoOffset($current_offset);
                
                logMessage("Processados $current_offset registros para o período $periodo", true);
                
                // A cada lote, verificar se estamos próximos do tempo limite
                if (isTimeRunningOut(60)) {
                    logMessage("Tempo próximo do limite. Salvando progresso para período $periodo", true);
                    break;
                }
            }
            
            // Se concluiu o período completo, salvar no cache
            if ($completo) {
                logMessage("Período $periodo concluído. Total: " . count($dados) . " registros", true);
                
                // Determinar a chave de cache com base no período
                $cacheKey = '';
                switch ($periodo) {
                    case 'hoje':
                        $cacheKey = date('Y-m-d');
                        break;
                    case 'semana':
                        $cacheKey = date('Y-W');
                        break;
                    case 'mes':
                        $cacheKey = date('Y-m');
                        break;
                }
                
                // Salvar no cache
                if (!empty($cacheKey)) {
                    $cache->set($cacheKey, $dados);
                    logMessage("Cache para $periodo salvo com sucesso", true);
                    $this->cacheCheckpointManager->marcarPeriodoCompleto($periodo);
                }
                
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            logMessage("Erro ao processar período $periodo: " . $e->getMessage(), true);
            return false;
        }
    }
    
    /**
     * Busca dados por período específico de forma incremental
     */
    private function buscarDadosPorPeriodoIncremental($periodo, $offset, $limit) {
        try {
            // Obter o filtro SQL apropriado para o período
            $filtro = $this->getFiltroParaPeriodo($periodo);
            logMessage("Filtro para $periodo (offset $offset, limit $limit): $filtro", false, true);
            
            // Consulta SQL otimizada e incremental
            $sql = "
                SELECT l.licitacao_id as id, 
                       l.licitacao_objeto as objeto,
                       l.licitacao_att as atualizacao
                FROM licitacoes l
                WHERE $filtro
                AND EXISTS (
                    SELECT 1 FROM licitacoes_meta enc 
                    WHERE enc.lm_licitacao = l.licitacao_id 
                    AND enc.lm_chave = 'dataEncerramentoPropostaPncp' 
                    AND enc.lm_valor > NOW()
                )
                ORDER BY l.licitacao_id ASC
                LIMIT $offset, $limit
            ";
            
            // Executar com timeout específico
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Erro ao preparar consulta: " . $this->conn->error);
            }
            
            $stmt->execute();
            $results = $stmt->get_result();
            
            $licitacoes = [];
            $processedIds = [];
            
            while ($row = $results->fetch_assoc()) {
                $id = $row['id'];
                $processedIds[] = $id;
                
                $licitacoes[$id] = [
                    'id' => $id,
                    'objeto' => $row['objeto'],
                    'atualizacao' => $row['atualizacao'],
                    'regiao' => null,
                    'publicacao' => null,
                    'encerramento' => null,
                    'descricao' => []
                ];
            }
            
            $stmt->close();
            
            if (empty($processedIds)) {
                return [];
            }
            
            // Agora vamos buscar os metadados em lotes, para evitar queries muito grandes
            $this->preencherMetadados($licitacoes, $processedIds);
            
            // E por fim buscar as descrições dos itens
            $this->preencherDescricoes($licitacoes, $processedIds);
            
            // Converter array associativo para array indexado
            $result = array_values($licitacoes);
            logMessage("Busca incremental para $periodo concluída. Lote de " . count($result) . " licitações", false, true);
            return $result;
        } catch (Exception $e) {
            logMessage("Erro ao buscar dados incrementais para $periodo: " . $e->getMessage(), true);
            return [];
        }
    }
    
    /**
     * Preenche os metadados (região, publicação, encerramento) para as licitações
     */
    private function preencherMetadados(&$licitacoes, $ids) {
        $chaves = ['unidadeOrgaoUfSigla', 'dataPublicacaoPncp', 'dataEncerramentoPropostaPncp'];
        $chavesMapeadas = [
            'unidadeOrgaoUfSigla' => 'regiao',
            'dataPublicacaoPncp' => 'publicacao',
            'dataEncerramentoPropostaPncp' => 'encerramento'
        ];
        
        // Processar em lotes de no máximo 200 IDs por vez para evitar timeouts
        $chunks = array_chunk($ids, 200);
        
        foreach ($chunks as $chunk) {
            if (isTimeRunningOut()) {
                logMessage("Tempo quase esgotado durante preenchimento de metadados", true);
                break;
            }
            
            $placeholders = implode(',', array_fill(0, count($chunk), '?'));
            
            $sql = "
                SELECT lm_licitacao, lm_chave, lm_valor
                FROM licitacoes_meta
                WHERE lm_licitacao IN ($placeholders)
                AND lm_chave IN ('unidadeOrgaoUfSigla', 'dataPublicacaoPncp', 'dataEncerramentoPropostaPncp')
            ";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                logMessage("Erro ao preparar consulta de metadados: " . $this->conn->error, true);
                continue;
            }
            
            // Criar os tipos para bind_param
            $types = str_repeat('i', count($chunk));
            $stmt->bind_param($types, ...$chunk);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $id = $row['lm_licitacao'];
                $chave = $row['lm_chave'];
                $valor = $row['lm_valor'];
                
                if (isset($licitacoes[$id]) && isset($chavesMapeadas[$chave])) {
                    $licitacoes[$id][$chavesMapeadas[$chave]] = $valor;
                }
            }
            
            $stmt->close();
        }
    }
    
    /**
     * Preenche as descrições dos itens para as licitações
     */
    private function preencherDescricoes(&$licitacoes, $ids) {
        // Processar em lotes de no máximo 200 IDs por vez
        $chunks = array_chunk($ids, 200);
        
        foreach ($chunks as $chunk) {
            if (isTimeRunningOut()) {
                logMessage("Tempo quase esgotado durante preenchimento de descrições", true);
                break;
            }
            
            $placeholders = implode(',', array_fill(0, count($chunk), '?'));
            
            $sql = "
                SELECT licitacoes_item_licitacao, licitacoes_item_desc
                FROM licitacoes_itens
                WHERE licitacoes_item_licitacao IN ($placeholders)
                AND licitacoes_item_desc IS NOT NULL AND licitacoes_item_desc != ''
                LIMIT 5000
            ";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt) {
                logMessage("Erro ao preparar consulta de descrições: " . $this->conn->error, true);
                continue;
            }
            
            // Criar os tipos para bind_param
            $types = str_repeat('i', count($chunk));
            $stmt->bind_param($types, ...$chunk);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $id = $row['licitacoes_item_licitacao'];
                $desc = $row['licitacoes_item_desc'];
                
                if (isset($licitacoes[$id]) && !empty($desc) && !in_array($desc, $licitacoes[$id]['descricao'])) {
                    $licitacoes[$id]['descricao'][] = $desc;
                }
            }
            
            $stmt->close();
        }
    }
    
    /**
     * Obtém o filtro SQL apropriado para cada período
     */
    private function getFiltroParaPeriodo($periodo) {
        $datas = $this->pegarDatasMensais();
        
        switch ($periodo) {
            case 'hoje':
                return "DATE(l.licitacao_att) = '{$datas['hoje']}'";
            case 'semana':
                return "DATE(l.licitacao_att) BETWEEN '{$datas['iS']}' AND '{$datas['fS']}'";
            case 'mes':
                return "DATE(l.licitacao_att) BETWEEN '{$datas['iM']}' AND '{$datas['fM']}'";
            default:
                return "1=1";
        }
    }
    
    /**
     * Gera hash aleatório
     */
    private function gerarHash($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
    
    /**
     * Obtém datas mensais para filtros
     */
    private function pegarDatasMensais() {
        $datas = [];
        $datas['hoje'] = date('Y-m-d', strtotime('-1 day'));
        
        $hoje = date('Y-m-d');
        
        if (date('l') == 'Monday') {
            $ultimaSegunda = $hoje;
        } else {
            $ultimaSegunda = date('Y-m-d', strtotime('last Monday'));
        }
        
        $datas['iS'] = $ultimaSegunda;
        $datas['fS'] = date('Y-m-d', strtotime('next Sunday'));
        $datas['iM'] = date('Y-m-01');
        $datas['fM'] = date('Y-m-t');
        
        return $datas;
    }
}

/**
 * Classe de cache
 */
class FileCache {
    private $cacheDir;
    
    public function __construct($cacheDir = '../caches') {
        $this->cacheDir = $cacheDir;
        
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }
    
    public function set($key, $data) {
        try {
            $filePath = $this->getFilePath($key);
            $jsonData = json_encode($data);
            
            if ($jsonData === false) {
                throw new Exception("Erro ao codificar dados para JSON: " . json_last_error_msg());
            }
            
            $result = file_put_contents($filePath, $jsonData);
            
            if ($result === false) {
                throw new Exception("Erro ao escrever no arquivo de cache");
            }
            
            logMessage("Cache salvo: $key (" . strlen($jsonData) . " bytes)");
        } catch (Exception $e) {
            logMessage("Erro ao salvar cache: " . $e->getMessage(), true);
        }
    }
    
    private function getFilePath($key) {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}

// Controle de sinais para garantir fechamento gracioso
function handleShutdown() {
    global $PROCESSING_ENABLED, $cache_lock_file, $apenas_cache;
    $PROCESSING_ENABLED = false;
    logMessage("Script interrompido, finalizando processamento...", true);
    
    // Remover arquivo de lock
    if ($apenas_cache && file_exists($cache_lock_file)) {
        unlink($cache_lock_file);
    }
}
register_shutdown_function('handleShutdown');

// ----- EXECUÇÃO PRINCIPAL -----

try {
    logMessage("===== INICIANDO EXECUÇÃO DO ATUALIZADOR DE LICITAÇÕES - VERSÃO ULTRA OTIMIZADA =====", true);
    logMessage("Data e hora: " . date('Y-m-d H:i:s'), true);
    
    // Inicializar gerenciadores
    $stateManager = new StateManager();
    $cacheCheckpointManager = new CacheCheckpointManager($cache_checkpoint_file);
    
    // Configurações para o processador
    $config = [
        'batch_size_api' => $BATCH_SIZE,
        'batch_size_db' => $CONFIG['batch_size_db'],
        'max_parallel_requests' => $MAX_PARALLEL_REQUESTS,
        'max_modalidades' => $max_modalidades,
        'max_paginas' => $max_paginas,
        'cache_chunk_size' => $CONFIG['cache_chunk_size'],
        'database_timeout' => $CONFIG['database_timeout']
    ];
    
    // Resetar o estado se solicitado
    if ($reset) {
        logMessage("Resetando estado do processamento", true);
        $stateManager->resetState();
        $cacheCheckpointManager->resetCheckpoint();
    }
    
    // Ver o estado atual
    $state = $stateManager->getState();
    logMessage("Estado atual: " . json_encode($state), true);
    
    // Criar o processador
    $processor = new LicitacoesProcessor($stateManager, $cacheCheckpointManager, $config);
    
    // Se for apenas para gerar cache
    if ($apenas_cache) {
        logMessage("Modo apenas cache ativado", true);
        // $processor->gerarCache();
    } else {
        // Processar licitações
        $processor->process();
    }
    
    $totalTime = round(microtime(true) - $START_TIME, 2);
    logMessage("===== EXECUÇÃO CONCLUÍDA EM {$totalTime} SEGUNDOS =====", true);
    
} catch (Exception $e) {
    logMessage("ERRO CRÍTICO: " . $e->getMessage(), true);
} finally {
    // Remover arquivo de lock ao finalizar
    if ($apenas_cache && file_exists($cache_lock_file)) {
        unlink($cache_lock_file);
    }
}