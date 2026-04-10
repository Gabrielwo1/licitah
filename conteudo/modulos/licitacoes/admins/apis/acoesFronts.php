<?

header('Content-Type: application/json; charset=utf-8');



error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);

session_start();

include __DIR__."/../../../../../admin/conn.php";
include __DIR__."/../../../../../admin/notificacao.php";

class AcoesLicitah{
    public $conn;
    public $autor;
    public $acao;
    public $id;
    public $tipo;
    public $empresa;
    public $pesquisas;
    public $usuarios;
    public $cacheDir;
    public $tempoCache;
    private $autorizacoes;
    
    function __construct(){
        $this->conn = conn();
        $this->autor = $_SESSION['id'] ?? false;
        $this->acao = $_POST['acao'] ?? false;
        $this->id = $_POST['id'] ?? false;
        $this->empresa = $_SESSION['sub'] ?? false;
        $this->cacheDir = __DIR__ . '/../../caches';
        $this->pesquisas = [];
        $this->usuarios = [];
        $this->tempoCache = 86400000;
        $this->autorizacoes = $this->quantidadePorFuncao();
    }
    
    function camposBanco($chavesParaRemover = []){
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $this->conn->real_escape_string($value);
        }

        foreach ($chavesParaRemover as $chave) {
            if (isset($data[$chave])) {
                unset($data[$chave]);
            }
        }

        return $data;
    }
    
    function definirPrefixo($prefixo, $array){
        $dataComPrefixo = [];
        foreach ($array as $key => $value) {
            $dataComPrefixo[$prefixo . $key] = $value; // Adicionar prefixo antes de cada chave
        }

        return $dataComPrefixo;
    }
    
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    function hasher($length = 32) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
       
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, strlen($chars) - 1)];
        }
    
        return $randomString;
        
    }
    
    function inserirDados($tabela, $data){
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));

        $sql = "INSERT INTO $tabela ($columns) VALUES ('$values')";
  
        if ($this->conn->query($sql) === TRUE) {
            
            return ['sucesso' => true, 'mensagem' => 'Dados cadastrados com sucesso'];
        } else {
            return ['erro' => true, 'mensagem' => 'falha no cadastro da tarefa'];
        }
    }
    
    function atualizarDados($tabela, $data, $prefixo, $id) {
        $sets = [];
        foreach ($data as $coluna => $valor) {
            $sets[] = "$coluna = '$valor'";
        }
        
        $setsStr = implode(", ", $sets);
        
        $coluna = $prefixo.'id';
    
        $sql = "UPDATE $tabela SET $setsStr WHERE $coluna = '$id'";
    
        if ($this->conn->query($sql) === TRUE) {
            return ['sucesso' => true, 'mensagem' => 'Dados atualizados com sucesso'];
        } else {
            return ['erro' => true, 'mensagem' => 'Falha na atualização dos dados'];
        }
    }
    
    function apagarDados($tabela, $prefixo, $id) {
        $coluna = $prefixo . 'id';
    
        $sql = "DELETE FROM $tabela WHERE $coluna = '$id'";
    
        if ($this->conn->query($sql) === TRUE) {
            return ['sucesso' => true, 'mensagem' => 'Dados apagados com sucesso'];
        } else {
            return ['erro' => true, 'mensagem' => 'Falha ao apagar os dados'];
        }
    }
    
    function vincularparaPesquisa($coluna, $id = false){
        if($id && intval($id)){
            $novoValor = $id;
        }else{
            $novoValor = $this->conn->insert_id;
        }
        
        $id = $this->id;
        $sqlSelect = "SELECT $coluna FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
        $result = $this->conn->query($sqlSelect);
        
        
        if ($result && $result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $colunaValor = $row[$coluna];
    
            $dadosJson = json_decode($colunaValor, true);
    
            if (json_last_error() === JSON_ERROR_NONE) {

                if (!in_array($novoValor, $dadosJson)) {

                    $dadosJson[] = $novoValor;
    
                    $novoJson = json_encode($dadosJson);
    
                     $sqlUpdate = "UPDATE licitacoes_pesquisas SET $coluna = '$novoJson' WHERE licitacoes_pesquisa_id = '$id'";
                    
                    if ($this->conn->query($sqlUpdate)) {
                        return ['sucesso'=>true, 'mensagem'=> 'Item foi updatado com sucesso'];
                    } else {
                        return ['erro'=>true, 'mensagem'=> 'Erro ao atualizar o registro'];
                    }
                } else {
                    return ['sucesso'=>true, 'mensagem'=> 'O arquivo já estava vinculado'];
                }
            } else {
                $dadosJson = [$novoValor];

                $novoJson = json_encode($dadosJson);
    
                $sqlUpdate = "UPDATE licitacoes_pesquisas SET $coluna = '$novoJson' WHERE licitacoes_pesquisa_id = '$id'";
                if ($this->conn->query($sqlUpdate)) {
                    return ['sucesso'=>true, 'mensagem'=> 'Item foi updatado com sucesso'];
                } else {
                     return ['erro'=>true, 'mensagem'=> 'Erro ao atualizar o registro'];
                }
            }
        } else {
          return ['erro'=>true, 'mensagem'=> 'Licitação não encontrada'];
        }
    }
    
    function desvincularParaPesquisa($coluna, $idRemovido) {
        $ultimoRemovidoId = $idRemovido; // ID do último registro inserido
        $id = $this->id; // ID da pesquisa
        $sqlSelect = "SELECT $coluna FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
        $result = $this->conn->query($sqlSelect);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $colunaValor = $row[$coluna];
    
            $dadosJson = json_decode($colunaValor, true);
    
            if (json_last_error() === JSON_ERROR_NONE) {
                if (in_array($ultimoRemovidoId, $dadosJson)) {
                    $dadosJson = array_diff($dadosJson, [$ultimoRemovidoId]);
    
                    $dadosJson = array_values($dadosJson);
    
                    $novoJson = json_encode($dadosJson);
    
                    $sqlUpdate = "UPDATE licitacoes_pesquisas SET $coluna = '$novoJson' WHERE licitacoes_pesquisa_id = '$id'";
                    
                    if ($this->conn->query($sqlUpdate)) {
                        return ['sucesso' => true, 'mensagem' => 'Item foi desvinculado com sucesso'];
                    } else {
                        return ['erro' => true, 'mensagem' => 'Erro ao atualizar o registro'];
                    }
                } else {
                    return ['sucesso' => true, 'mensagem' => 'O ID não estava vinculado'];
                }
            } else {
                $dadosJson = [];

                $novoJson = json_encode($dadosJson);
    
                $sqlUpdate = "UPDATE licitacoes_pesquisas SET $coluna = '$novoJson' WHERE licitacoes_pesquisa_id = '$id'";
                if ($this->conn->query($sqlUpdate)) {
                    return ['sucesso'=>true, 'mensagem'=> 'Item foi updatado com sucesso'];
                } else {
                     return ['erro'=>true, 'mensagem'=> 'Erro ao atualizar o registro'];
                }
            }
        } else {
            return ['erro' => true, 'mensagem' => 'Licitação não encontrada'];
        }
    }
    
    function infosMeta($tabela, $id, $prefixoPrincipal, $prefixoMeta){
        $query = "SELECT {$prefixoMeta}_chave, {$prefixoMeta}_valor FROM {$tabela}_meta WHERE {$prefixoMeta}_{$prefixoPrincipal}=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $metas = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $metas[$row["{$prefixoMeta}_chave"]] = $row["{$prefixoMeta}_valor"];
            }
        }
        
        return $metas;
    }
    
    function removerPrefixo($array, $prefixo) {
        $novoArray = [];
        
        foreach ($array as $chave => $valor) {
            // Remove o prefixo da chave
            $novaChave = preg_replace("/^" . preg_quote($prefixo, '/') . "/", '', $chave);
            
            // Mantém o valor, lidando com arrays aninhados
            $novoArray[$novaChave] = is_array($valor) ? removerPrefixo($valor, $prefixo) : $valor;
        }
        
        return $novoArray;
    }
    
    function pegarUsuario($id){
        $sql = "SELECT usuario_id FROM usuarios WHERE usuario_user = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['usuario_id'];
        
        return $id;
    }
    
    function displayUsuarios($id){
        if(isset($this->usuarios[$id])){
            return $this->usuarios[$id];
        }
        
        $sql = "SELECT usuario_display FROM usuarios WHERE usuario_id = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $display = $resultado->fetch_assoc()['usuario_display'];
        
        $this->usuarios[$id] = [
            'display'=> $display
        ];
        
        return $this->usuarios[$id];
    }
    
    /**
 * Verifica e retorna dados do cache se existirem
 * 
 * @param string $key Chave do cache
 * @return mixed|null Dados do cache ou null se expirado/não encontrado
 */
    private function checkAndReturnCache($key) {
        $filePath = $this->getFilePath($key);

        if (!file_exists($filePath)) {
            return null;
        }
    
        $fileModificationTime = filemtime($filePath);
        $currentTime = time();
        
        // Verificar expiração
        if (($currentTime - $fileModificationTime) > $this->tempoCache) {
            unlink($filePath);
            return null;
        }
    
        // Verificar restrições de horário (não servir cache antes das 9h)
        $fileDate = new DateTime();
        $fileDate->setTimestamp($fileModificationTime);
        if ($fileDate->format('H') < 9) {
            unlink($filePath);
            return null;
        }
        
        // Para consultas complexas, aumentar TTL
        $isComplexQuery = false;
        if (isset($_POST) && is_array($_POST)) {
            // Determinar complexidade baseado no número de filtros ativos
            $activeFilters = array_filter($_POST, function($value) {
                return !empty($value) && $value !== '0';
            });
            
            $isComplexQuery = count($activeFilters) > 3;
        }
        
        // Se for uma consulta simples, verificar tempo mais curto
        if (!$isComplexQuery && ($currentTime - $fileModificationTime) > ($this->tempoCache / 2)) {
            unlink($filePath);
            return null;
        }
    
        $data = file_get_contents($filePath);
        // Tocar no arquivo para atualizar mtime (evitar expiração de caches quentes)
        touch($filePath);
        
        return json_decode($data, true);
    }
    
    /**
     * Armazena dados no cache
     * 
     * @param string $key Chave do cache
     * @param mixed $data Dados a serem armazenados
     * @return bool Sucesso/falha
     */
    private function guardarCache($key, $data) {
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        
        // Criar subdiretórios para diferentes tipos de cache
        $subDirs = ['queries', 'results', 'metadata'];
        foreach ($subDirs as $dir) {
            $path = $this->cacheDir . '/' . $dir;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }
        
        // Determinar tipo de cache baseado em heurística
        $type = 'results'; // Tipo padrão
        
        if (isset($data['sucesso']) && isset($data['licitacoes'])) {
            // É um resultado de consulta completo
            $filePath = $this->cacheDir . '/results/' . md5($key) . '.cache';
            
            // Verificar tamanho do cache (evitar arquivos muito grandes)
            $jsonData = json_encode($data);
            if (strlen($jsonData) > 10 * 1024 * 1024) { // > 10MB
                // Comprimir ou truncar dados muito grandes
                $data['_compressed'] = true;
                $data['licitacoes'] = array_slice($data['licitacoes'], 0, 100); // Limitar a 100 resultados
            }
        } else {
            // Outros tipos de dados
            $filePath = $this->cacheDir . '/metadata/' . md5($key) . '.cache';
        }
        
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        return file_put_contents($filePath, $jsonData) !== false;
    }
    
    /**
     * Gera o caminho para o arquivo de cache com otimizações
     */
    private function getFilePath($key) {
        // Criar hash mais curto e eficiente
        $hashKey = md5($key);
        
        // Se for uma consulta, armazenar em subdiretório
        if (strpos($key, 'filtro_') === 0) {
            return $this->cacheDir . '/results/' . $hashKey . '.cache';
        }
        
        // Se for um resultado de consulta comum
        if (strpos($key, 'modalidades') !== false || 
            strpos($key, 'esferas') !== false || 
            strpos($key, 'situacao') !== false) {
            return $this->cacheDir . '/metadata/' . $hashKey . '.cache';
        }
        
        // Caminho padrão
        return $this->cacheDir . '/' . $hashKey . '.cache';
    }
    
    /**
     * Limpa caches antigos ou inválidos
     * Pode ser chamada por um cron job para manutenção
     */
    public function cleanupCache($maxAge = null) {
        $maxAge = $maxAge ?? $this->tempoCache;
        $currentTime = time();
        $cleaned = 0;
        
        // Limpar cache principal
        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            if (($currentTime - filemtime($file)) > $maxAge) {
                unlink($file);
                $cleaned++;
            }
        }
        
        // Limpar subdiretórios
        $subDirs = ['queries', 'results', 'metadata'];
        foreach ($subDirs as $dir) {
            $path = $this->cacheDir . '/' . $dir;
            if (file_exists($path)) {
                $files = glob($path . '/*.cache');
                foreach ($files as $file) {
                    if (($currentTime - filemtime($file)) > $maxAge) {
                        unlink($file);
                        $cleaned++;
                    }
                }
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Invalidar cache após atualizações de dados
     */
    public function invalidateCache($pattern = null) {
        if ($pattern === null) {
            // Invalidar todo o cache
            $this->cleanupCache(0);
            return true;
        }
        
        // Invalidar apenas caches que correspondem ao padrão
        $files = glob($this->cacheDir . '/*' . $pattern . '*.cache');
        $files = array_merge($files, glob($this->cacheDir . '/queries/*' . $pattern . '*.cache'));
        $files = array_merge($files, glob($this->cacheDir . '/results/*' . $pattern . '*.cache'));
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return count($files);
    }
    
    /**
 * Cria ou verifica índices necessários para otimização de consultas
 * Esta função deve ser executada uma vez durante a instalação ou atualização
 */
    public function criarIndicesOtimizados() {
        try {
            // Índices para a tabela principal de licitações
            $this->executarSQLSeguro("ALTER TABLE licitacoes ADD INDEX IF NOT EXISTS idx_licitacao_att (licitacao_att)");
            $this->executarSQLSeguro("ALTER TABLE licitacoes ADD INDEX IF NOT EXISTS idx_licitacao_objeto (licitacao_objeto(255))");
            $this->executarSQLSeguro("ALTER TABLE licitacoes ADD INDEX IF NOT EXISTS idx_licitacao_governo (licitacao_governo)");
            $this->executarSQLSeguro("ALTER TABLE licitacoes ADD INDEX IF NOT EXISTS idx_licitacao_url (licitacao_url)");
    
            // Índices para a tabela de metadados
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_lm_chave (lm_chave)");
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_lm_licitacao (lm_licitacao)");
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_lm_licitacao_chave (lm_licitacao, lm_chave)");
    
            // Índices para consultas específicas frequentes
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_modalidade (lm_chave, lm_valor(50)) 
                                 WHERE lm_chave = 'modalidadeNome'");
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_data_abertura (lm_chave, lm_valor(20)) 
                                 WHERE lm_chave = 'dataAberturaPropostaPncp'");
            $this->executarSQLSeguro("ALTER TABLE licitacoes_meta ADD INDEX IF NOT EXISTS idx_data_encerramento (lm_chave, lm_valor(20)) 
                                 WHERE lm_chave = 'dataEncerramentoPropostaPncp'");
    
            // Índices para a tabela de itens
            $this->executarSQLSeguro("ALTER TABLE licitacoes_itens ADD INDEX IF NOT EXISTS idx_item_licitacao (licitacoes_item_licitacao)");
            
            // Adicionar índice de texto completo se for MySQL 5.6+
            $this->executarSQLSeguro("ALTER TABLE licitacoes_itens ADD FULLTEXT IF NOT EXISTS ft_item_desc (licitacoes_item_desc)");
            
            // Otimizar tabelas
            $this->executarSQLSeguro("OPTIMIZE TABLE licitacoes, licitacoes_meta, licitacoes_itens");
            
            return ['sucesso' => true, 'mensagem' => 'Índices otimizados criados com sucesso'];
        } catch (Exception $e) {
            error_log("Erro ao criar índices: " . $e->getMessage());
            return ['erro' => true, 'mensagem' => 'Erro ao criar índices: ' . $e->getMessage()];
        }
    }
    
    /**
     * Executa SQL com tratamento de erros
     */
    private function executarSQLSeguro($sql) {
        try {
            $this->conn->query($sql);
            return true;
        } catch (Exception $e) {
            // Ignorar erros de índice já existente
            if (strpos($e->getMessage(), 'Duplicate key name') !== false ||
                strpos($e->getMessage(), 'already exists') !== false) {
                return true;
            }
            // Registrar outros erros e propagar
            error_log("Erro SQL: " . $e->getMessage() . " - Query: " . $sql);
            throw $e;
        }
    }
    
    /**
     * Verifica as configurações do MySQL e ajusta para otimizar desempenho
     * Esta função deve ser executada com privilégios administrativos
     */
    public function otimizarMySQLConfig() {
        // Verificar privilégios primeiro
        $query = "SELECT @@max_connections, @@query_cache_size, @@innodb_buffer_pool_size";
        $result = $this->conn->query($query);
        $config = $result->fetch_assoc();
        
        $recommendations = [];
        
        if ((int)$config['@@max_connections'] < 100) {
            $recommendations[] = "Aumentar max_connections para pelo menos 100";
        }
        
        if ((int)$config['@@query_cache_size'] < 20 * 1024 * 1024) { // < 20MB
            $recommendations[] = "Aumentar query_cache_size para pelo menos 20MB";
        }
        
        // Recomendações de InnoDB
        if ((int)$config['@@innodb_buffer_pool_size'] < 128 * 1024 * 1024) { // < 128MB
            $recommendations[] = "Aumentar innodb_buffer_pool_size para pelo menos 128MB";
        }
        
        return [
            'configuracao_atual' => $config,
            'recomendacoes' => $recommendations,
            'instrucoes' => "Para aplicar estas configurações, edite o arquivo my.cnf ou my.ini e reinicie o MySQL."
        ];
    }
     
    
    // logs
    
    function inserirLog($tipo, $alvo, $id, $licitacao){
        $autor = $this->autor;
        $hash = $this->hasher();
        
        $sql = "INSERT INTO licitacoes_logs (licitacoes_log_acao, licitacoes_log_acao_alvo, licitacoes_log_dado, licitacoes_log_pesquisa, licitacoes_log_autor, licitacoes_log_hash) VALUES ('$tipo', '$alvo', '$id', '$licitacao', '$autor', '$hash')"; 
        
        $resultado = $this->conn->query($sql);
    }
    
    
    // licitacoes
    
    
    function infoLicitacao($id){
        $sql = "SELECT * FROM licitacoes WHERE licitacao_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            
            $dado = $resultado->fetch_assoc();
            
            $array = $this->removerPrefixo($dado, 'licitacao_');
            
            $metas = $this->infosMeta('licitacoes', $id, 'licitacao', 'lm');
            
            foreach($metas as $v => $m){
                $array[$v] = $m;
            }
            
            
            return $array;
        }
        else{
            return false;
        }
    }
    
    function listagensLicitacoesAutor(){
        $empresa = $this->empresa;
        $autor = $this->autor;
        
        $sql = "SELECT licitacoes_pesquisa_numero, licitacoes_pesquisa_tarefas, licitacoes_pesquisa_url, licitacoes_pesquisa_visualizadores, licitacoes_pesquisa_autor
                FROM licitacoes_pesquisas 
                WHERE licitacoes_pesquisa_empresa = '$empresa'";
        
        $resultado = $this->conn->query($sql);
        $lista = [];
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $info = false;
                if(intval($dado['licitacoes_pesquisa_autor']) == intval($autor)){
                    $info = true;
                } else {
                    if($this->isJson($dado['licitacoes_pesquisa_visualizadores'])){
                        $array = json_decode($dado['licitacoes_pesquisa_visualizadores'], TRUE);
                    } else {
                        $array = [];
                    }
                    
                    foreach($array as $a){
                        if(intval($a) == intval($autor)){
                            $info = true;
                            break;
                        }
                    }
                }
                
                if($info){
                    $licitacao = $dado['licitacoes_pesquisa_numero'];
                    
                    $tarefas = $dado['licitacoes_pesquisa_tarefas'];
                    $infosTarefas = [];
                    if($tarefas){
                        $tarefas = json_decode($tarefas, TRUE);
                        
                        
                        if(count($tarefas) > 0){
                            foreach($tarefas as $t){
                                $iTarefa = $this->infoTarefa($t);
                                
                                if($iTarefa){
                                    $infosTarefas[] = $iTarefa;
                                }
                            }
                        }
                    }
                    
                    $infos = $this->infoLicitacao($licitacao);
                    
                    
                    if($infos){
                        $lista[] = [
                            'url'=> $dado['licitacoes_pesquisa_url'],
                            'licitacao' =>$infos,
                            'tarefas' =>$infosTarefas
                        ];
                    }
                    
                }
            }
        }
        
        return ['sucesso'=> true, 'lista'=> $lista];
    }
    
    function pegarIdLicitacao($id){
        $sql = "SELECT licitacao_id FROM licitacoes WHERE licitacao_url = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacao_id'];
        
        return $id;
    }
    
    function pegarIdLicitacaoPesquisa($id){
        $sql = "SELECT licitacoes_pesquisa_id FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_url = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacoes_pesquisa_id'];
        
        return $id;
    }
    
    function quantidadePorFuncao(){
        $funcao = intval($_SESSION['funcao']) ? intval($_SESSION['funcao']) : 0;
        
        $array = [
            'cnpj'=> 0,
            'licitacoes'=> 0,
            'tarefas'=> 0,
            'documentos'=> 0,
        ];
        switch($funcao){
            case 1:
            case 0:
            case 19:
                $array['cnpj'] = 9999999;
                $array['licitacoes'] = 9999999;
                $array['tarefas'] = 9999999;
                $array['documentos'] = 9999999;
                break;
            case 18:
                $array['cnpj'] = 2;
                $array['licitacoes'] = 10;
                $array['tarefas'] = 10;
                $array['documentos'] = 15;
                break;
            case 2:
            case 20:
                $array['cnpj'] = 1;
                $array['licitacoes'] = 3;
                $array['tarefas'] = 3;
                $array['documentos'] = 5;
                break;
        }
        
        
        return $array;
    }
    
    function vincularLicitacao(){
        if(!$_POST['id']){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $autor = $this->autor;
        $empresa = $this->empresa;
        $id = $_POST['id'];
        
        $sql = "SELECT COUNT(DISTINCT licitacoes_pesquisa_id) as total FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_autor = '$autor' AND licitacoes_pesquisa_empresa = '$empresa'";
        
        $resultado = $this->conn->query($sql);
        
        
        $total = 0;
        if($resultado->num_rows == 1){
            $total = $resultado->fetch_assoc()['total'];
        }

        $autorizados = $this->autorizacoes['licitacoes'];
        
        if($total >= $autorizados){
            return [
                'erro'=> true, 
                'mensagem'=> 'Sua função não permite a você vincular mais licitações, para vincular mais licitações verifique nossos planos',
                'autorizado' => true
            ];
        }

        $sql = "SELECT licitacoes_pesquisa_url FROM licitacoes_pesquisas WHERE 
        (
            licitacoes_pesquisa_numero = '$id' 
            OR licitacoes_pesquisa_numero IN (
                SELECT 
                    licitacao_id 
                FROM 
                    licitacoes 
                WHERE 
                    licitacao_url = '$id'
            )
        ) 
        AND licitacoes_pesquisa_autor = '$autor' AND licitacoes_pesquisa_empresa = '$empresa'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $dado = $resultado->fetch_assoc();
            
            $url = $dado['licitacoes_pesquisa_url'];
        }else{
            $hash = $this->hasher();
            $url = $this->hasher(16);
            
            if(!intval($id)){
                $id = $this->pegarIdLicitacao($id);
                
                if(!$id){
                    return ['erro'=> true, 'mensagem'=>'licitacão não encontrada'];
                }
            }
            
            $id = intval($id);
            
            $infoLicitacao = $this->infoLicitacao($id);
            
            $processo = 0;
            
            if($infoLicitacao){
                $processo = $infoLicitacao['processo'];
            }
            
            $visualizadores = json_encode([]);
            
            $sql = "INSERT INTO licitacoes_pesquisas (licitacoes_pesquisa_url, licitacoes_pesquisa_hash, licitacoes_pesquisa_numero, licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_autor, licitacoes_pesquisa_empresa, licitacoes_pesquisa_visualizadores)
            VALUES ('$url', '$hash', '$id', '$processo',  '$autor', '$empresa', '$visualizadores')";
            
            $resultado = $this->conn->query($sql);
 
            $this->inserirLog(1, 1, json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->conn->insert_id);
        }
        
        return['sucesso'=> true, 'item'=> ['url'=>$url]];
        
    }
    
    function liberar(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        if(!isset($_POST['usuario'])){
            return['erro'=> true, 'mensagem'=> 'usuario não foi passado'];
        }
        
        $idUser = $this->pegarUsuario($_POST['usuario']);
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        $id = $this->id;
        
        $sql = "SELECT licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            if($dado['licitacoes_pesquisa_visualizadores'] && $this->isJson($dado['licitacoes_pesquisa_visualizadores'])){
                $visualizadores = json_decode($dado['licitacoes_pesquisa_visualizadores'], TRUE);
            }else{
                $visualizadores = [];
            }
            
            if($idUser != $dado['licitacoes_pesquisa_autor']){
                if(!in_array($idUser, $visualizadores)){
                    $corpo = [
                        'header'=> 'Nova Licitação',
                        'body'=> 'Você foi incluso a uma nova licitação por outro usuário de sua empresa',
                    ];
                    
                    $notificacao = new Notificacao($idUser);
                    $notificacao->setRemetente($this->autor);
                    $notificacao->mensagem($corpo);
                    $resposta = $notificacao->all();
                    
                    $visualizadores[] = $idUser;
                }
                
                $visualizadores = json_encode($visualizadores, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
         
                $sql = "UPDATE licitacoes_pesquisas SET licitacoes_pesquisa_visualizadores = '$visualizadores' WHERE licitacoes_pesquisa_id = '$id'";
                
                if($this->conn->query($sql)){
                    return['sucesso'=> true, 'mensagem'=> 'licitação atualizada'];
                }else{
                    return['erro'=> true, 'mensagem'=> 'Falha ao atualzar o banco de dados, tente novamente mais tarde, ou contate o suporte'];
                }
            }else{
                return['erro'=> true, 'mensagem'=> 'Esse usuário não pode ser alterado, pois ele é o autor'];
            }
            
            
            
        }else{
            return['erro'=> true, 'mensagem'=> 'licitação não foi encontrada'];
        }
    }
    
    function bloquear(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        if(!isset($_POST['usuario'])){
            return['erro'=> true, 'mensagem'=> 'usuario não foi passado'];
        }
        
        $idUser = $this->pegarUsuario($_POST['usuario']);
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        $id = $this->id;
        
        $sql = "SELECT licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            if($dado['licitacoes_pesquisa_visualizadores'] && $this->isJson($dado['licitacoes_pesquisa_visualizadores'])){
                $visualizadores = json_decode($dado['licitacoes_pesquisa_visualizadores'], TRUE);
            }else{
                $visualizadores = [];
            }
            
            if($idUser != $dado['licitacoes_pesquisa_autor']){
                if (in_array($idUser, $visualizadores)) {
                    // Encontra a posição do valor no array
                    $key = array_search($idUser, $visualizadores);
                    
                    // Remove o valor do array usando a chave
                    if ($key !== false) {
                        unset($visualizadores[$key]);
                    }
                }
                
                $visualizadores = json_encode($visualizadores, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                
                $sql = "UPDATE licitacoes_pesquisas SET licitacoes_pesquisa_visualizadores = '$visualizadores' WHERE licitacoes_pesquisa_id = '$id'";
                
                if($this->conn->query($sql)){
                    return['sucesso'=> true, 'mensagem'=> 'licitação atualizada'];
                }else{
                    return['erro'=> true, 'mensagem'=> 'Falha ao atualzar o banco de dados, tente novamente mais tarde, ou contate o suporte'];
                }
            }else{
                return['erro'=> true, 'mensagem'=> 'Esse usuário não pode ser alterado, pois ele é o autor'];
            }
            
            
            
        }else{
            return['erro'=> true, 'mensagem'=> 'licitaçãp não foi encontrada'];
        }
    }
    
    
    // tarefas
    
    
    function pegarIdTarefa($id){
        $sql = "SELECT licitacaos_tarefa_id FROM licitacoes_tarefas WHERE licitacoes_tarefa_hash = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacaos_tarefa_id'];
        
        return $id;
    }
    
    function cadastrarTarefa(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        $autor = $this->autor;
        
        $sql = "SELECT lp.licitacoes_pesquisa_tarefas as tarefas FROM licitacoes_pesquisas as lp WHERE lp.licitacoes_pesquisa_id = '{$this->id}'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $tarefas = $resultado->fetch_assoc()['tarefas'];
            
            if(empty($tarefas)){
                $total = 0;
            }else{
                if($this->isJson($tarefas)){
                    $array = json_decode($tarefas, true);
                }else{
                    if(is_array($tarefas)){
                        $array = $tarefas;
                    }else{
                        $array = [];
                    }
                }
                
                
                $ids = implode("','", $array);
                
                $sql = "SELECT COUNT(licitacoes_tarefa_id)  as total FROM licitacoes_tarefas WHERE licitacoes_tarefa_autor = '{$autor}' AND licitacoes_tarefa_id IN ('{$ids}')";
                
                $resultado = $this->conn->query($sql);
                
                if($resultado->num_rows == 1){
                    $consulta = $resultado->fetch_assoc();
                    $total = $consulta['total'] ? intval($consulta['total']) : 0;
                }else{
                    $total = 0;
                }
            }
        }else{
            return ['erro'=> true, 'mensagem'=> 'Informações da licitação não foram encontradas no banco'];
        }

        $autorizados = $this->autorizacoes['tarefas'];
        
        if($total >= $autorizados){
            return [
                'erro'=> true, 
                'mensagem'=> 'Sua função não permite a você vincular mais tarefas na licitação, para vincular mais tarefas verifique nossos planos',
                'autorizado' => true
            ];
        }
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if(empty($_POST['nome']) || empty($_POST['prazo']) || empty($_POST['prioridade'])){
            return['erro'=> true, 'mensagem'=> 'dados obrigatórios não foram passados'];
        }

        $chavesParaRemover = ['acao', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_tarefa_';
       
        $data = $this->camposBanco($chavesParaRemover);
        
        $data['hash'] = $this->hasher();
        $data['autor'] = $this->autor;
        
        $dados = $this->definirPrefixo($prefixo, $data);
        
        
        $criacao = $this->inserirDados('licitacoes_tarefas', $dados);

        $data['id'] = $this->conn->insert_id;
        $data['dataCriacao'] = date("Y-m-d H:i:s");
        
        $dataArray = json_decode(stripslashes($data['subtarefas']), true);
        
        // Exibindo os dados
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['subtarefas'] = json_encode($dataArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        
        if(isset($criacao['sucesso'])){
            
            $resposta = $this->vincularparaPesquisa('licitacoes_pesquisa_tarefas');
            
            if(isset($resposta['sucesso'])){
                if(intval($data['usuario']) && isset($data['usuario_display'])){
                    $_POST['usuario'] = $data['usuario_display'];
                    
                    $this->liberar();
                    
                    
                    if($_SESSION['user'] != $data['usuario_display']){
                        $idUser = $this->pegarUsuario($data['usuario_display']);
                        
                        $infoLicitacao = $this->infoLicitacao($this->id);
                        
                        $corpo = [
                            'header'=> 'Nova Tarefa Atribuída',
                            'body'=> 'Uma nova tarefa foi atribuída a você: '.$data['nome'],
                        ];
                        
                        $notificacao = new Notificacao($idUser);
                        $notificacao->setRemetente($this->autor);
                        $notificacao->mensagem($corpo);
                        $resposta = $notificacao->all();
                    }
                }
                
                $this->inserirLog(1, 2, json_encode(['id'=>$data['id'], 'nome'=> $data['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo criado e vinculado', 'item'=> $data];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $criacao;
        }
        
    }
    
    function atualizarTarefa(){
        if(!isset($_POST['id_tarefa'])){
            return['erro'=> true, 'mensagem'=> 'tarefa não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        $id_tarefa  = $_POST['id_tarefa'];
        
        if(!intval($_POST['id_tarefa'])){
            $id_tarefa  = $this->pegarIdTarefa($_POST['id_tarefa']);
        }
        
        if((!isset($_POST['nome']) || $_POST['nome'] == '') && (!isset($_POST['prazo']) || $_POST['prazo'] == '') && (!isset($_POST['prioridade']) || $_POST['prioridade'] == '')){
            return['erro'=> true, 'mensagem'=> 'dados obrigatórios não foram passados'];
        }
        
        $chavesParaRemover = ['acao', 'id_tarefa', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_tarefa_';
        
        $data = $this->camposBanco($chavesParaRemover);
        
        $dados = $this->definirPrefixo($prefixo, $data);
        
        $tarefaDados = $this->infoTarefa($id_tarefa);
        
        $atualizacao = $this->AtualizarDados('licitacoes_tarefas', $dados, $prefixo, $id_tarefa);
        $data['id'] =  $id_tarefa;
        if(isset($atualizacao['sucesso'])){
            if(intval($data['usuario']) && isset($data['usuario_display'])){
                $_POST['usuario'] = $data['usuario_display'];
                $this->liberar();
                
                if($_SESSION['user'] != $data['usuario_display'] && $data['usuario_display'] != $tarefaDados['usuario_display']){
                    $idUser = $this->pegarUsuario($data['usuario_display']);
                    
                    $infoLicitacao = $this->infoLicitacao($this->id);
                    
                    $corpo = [
                        'header'=> 'Nova Tarefa Atribuída',
                        'body'=> 'Uma nova tarefa foi atribuída a você: '.$data['nome'],
                    ];
                    
                    $notificacao = new Notificacao($idUser);
                    $notificacao->setRemetente($this->autor);
                    $notificacao->mensagem($corpo);
                    $resposta = $notificacao->all();
                }
                    
                   
            }
            
            $this->inserirLog(2, 2, json_encode(['id'=>$id_tarefa, 'nome'=> $data['nome'], 'antiga'=> $tarefaDados['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
            
            return ['sucesso' => true, 'mensagem' => 'Dados atualizados com sucesso', 'item'=> $data];
        } else {
            return ['erro' => true, 'mensagem' => 'Falha na atualização dos dados'];
        }
        
    }
    
    function apagarTarefa(){
        if(!isset($_POST['id']) && intval($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
         
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if(!isset($_POST['id_tarefa'])){
            return['erro'=> true, 'mensagem'=> 'tarefa não foi passada'];
        }
        
        $id_tarefa  = $_POST['id_tarefa'];
        
        if(!intval($_POST['id_tarefa'])){
            $id_tarefa  = $this->pegarIdTarefa($_POST['id_tarefa']);
        }
        
        
        $tarefaDados = $this->infoTarefa($id_tarefa);
        $apagacao = $this->apagarDados('licitacoes_tarefas', 'licitacoes_tarefa_', $id_tarefa);
        
        if(isset($apagacao['sucesso'])){
            $resposta = $this->desvincularParaPesquisa('licitacoes_pesquisa_tarefas', $id_tarefa);
            
            if(isset($resposta['sucesso'])){
                
                $this->inserirLog(3, 2, json_encode(['id'=>$id_tarefa, 'nome'=> $tarefaDados['nome']]), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo apagado e desvinculado'];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $apagacao;
        }
    }
    
    function statusTarefa(){
        if(!isset($_POST['id_tarefa'])){
            return['erro'=> true, 'mensagem'=> 'tarefa não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        $id_tarefa  = $_POST['id_tarefa'];
        
        if(!intval($_POST['id_tarefa'])){
            $id_tarefa  = $this->pegarIdTarefa($_POST['id_tarefa']);
        }
        
        
        $chavesParaRemover = ['acao', 'id_tarefa', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_tarefa_';
        
        $data = $this->camposBanco($chavesParaRemover);
        
        $dados = $this->definirPrefixo($prefixo, $data);
        $tarefaDados = $this->infoTarefa($id_tarefa);
        $atualizacao = $this->AtualizarDados('licitacoes_tarefas', $dados, $prefixo, $id_tarefa);

        if(isset($atualizacao['sucesso'])){
            if(intval($data['andamento'])){
                $sql = "SELECT licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_url, licitacoes_pesquisa_visualizadores, licitacoes_pesquisa_autor  FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '{$this->id}'";
                
                $resultado = $this->conn->query($sql);
                
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $autores = [];
                    
                    if($this->isJson($dado['licitacoes_pesquisa_visualizadores'])){
                        $autores = json_decode($dado['licitacoes_pesquisa_visualizadores']);
                    }
                    else{
                        if(is_array($dado['licitacoes_pesquisa_visualizadores'])){
                            $autores = $dado['licitacoes_pesquisa_visualizadores'];
                        }
                    }
                    
                    array_push($autores, $dado['licitacoes_pesquisa_autor']);
                    
                    
                    foreach($autores as $a){
                        
                        if(intval($this->autor) == intval($a)){
                            continue;
                        }
                        $corpo = [
                            'header'=> 'Tarefa Concluída',
                            'body'=> 'A tarefa '.$tarefaDados['nome'].' foi concluída com sucesso!',
                            'link'=> 'licitacoes/'.$dado['licitacoes_pesquisa_url']
                        ];
                        
                        $notificacao = new Notificacao($a);
                        $notificacao->setRemetente($this->autor);
                        $notificacao->mensagem($corpo);
                        $resposta = $notificacao->all();
                    }
                    
                }
                
            }
            $this->inserirLog(2, 2, json_encode(['id'=>$id_tarefa, 'nome'=> $tarefaDados['nome'], 'antiga'=>$tarefaDados['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
            
            return ['sucesso' => true, 'mensagem' => 'Dados atualizados com sucesso'];
        } else {
            return ['erro' => true, 'mensagem' => 'Falha na atualização dos dados'];
        }
    }
    
    function infoTarefa($id){
        $sql = "SELECT * FROM licitacoes_tarefas WHERE licitacoes_tarefa_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $array = $this->removerPrefixo($dado, 'licitacoes_tarefa_');
            
            return $array;
        }
        else{
            return false;
        }
    } 
    
    
    // aotacoes
    
    function pegarIdAnotacao($id){
        $sql = "SELECT licitacoes_anotacao_id FROM licitacoes_anotacoes WHERE licitacoes_anotacao_hash = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacoes_anotacao_id'];
        
        return $id;
    }
    
    function infoAnotacao($id){
        $sql = "SELECT * FROM licitacoes_anotacoes WHERE licitacoes_anotacao_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $array = $this->removerPrefixo($dado, 'licitacoes_anotacao_');
            
            return $array;
        }
        else{
            return false;
        }
    } 
    
    function cadastrarAnotacao(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if((!isset($_POST['texto']))){
            return['erro'=> true, 'mensagem'=> 'anotação não passada'];
        }
        
        
        $chavesParaRemover = ['acao', 'id', 'id_tarefa', 'csrf_token'];
        
        
        $prefixo = 'licitacoes_anotacao_';
       
        $data = $this->camposBanco($chavesParaRemover);
        $data['hash'] = $this->hasher();
        $data['autor'] = $this->autor;
        
        $dados = $this->definirPrefixo($prefixo, $data);
        
        $criacao = $this->inserirDados('licitacoes_anotacoes', $dados);

        $data['id'] = $this->conn->insert_id;
        $data['dataCriacao'] = date("Y-m-d H:i:s");
 
        if(isset($criacao['sucesso'])){
            $resposta = $this->vincularparaPesquisa('licitacoes_pesquisa_anotacoes');
            
            if(isset($resposta['sucesso'])){
                $this->inserirLog(1, 5, json_encode(['id'=>$data['id'], 'nome'=> $data['texto']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo criado e vinculado', 'item'=> $data];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $criacao;
        }
    }
    
    function apagarAnotacao(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if(!isset($_POST['id_anotacao'])){
            return['erro'=> true, 'mensagem'=> 'anotação não foi passada'];
        }
        
        $id_anotacao  = $_POST['id_anotacao'];
        
        if(!intval($_POST['id_anotacao'])){
            $id_anotacao  = $this->pegarIdAnotacao($_POST['id_anotacao']);
        }
        
        $dadoAnotacao = $this->infoAnotacao($id_anotacao);
        
        $apagacao = $this->apagarDados('licitacoes_anotacoes', 'licitacoes_anotacao_', $id_anotacao);
        
        if(isset($apagacao['sucesso'])){
            $resposta = $this->desvincularParaPesquisa('licitacoes_pesquisa_anotacoes', $id_anotacao);
            
            if(isset($resposta['sucesso'])){
                
                $this->inserirLog(3, 5,  json_encode(['id'=>$id_anotacao, 'nome'=> $dadoAnotacao['texto']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo apagado e desvinculado'];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $apagacao;
        }
    }
    
    function atualizarAnotacao(){
        if(!isset($_POST['id_anotacao'])){
            return['erro'=> true, 'mensagem'=> 'anotação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        $id_anotacao  = $_POST['id_anotacao'];
        
        if(!intval($_POST['id_anotacao'])){
            $id_anotacao  = $this->pegarIdAnotacao($_POST['id_anotacao']);
        }
        
        if((!isset($_POST['texto']))){
            return['erro'=> true, 'mensagem'=> 'testo de anotação não passado'];
        }
        
        $chavesParaRemover = ['acao', 'id_anotacao', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_anotacao_';
        
        $data = $this->camposBanco($chavesParaRemover);
        
        $dados = $this->definirPrefixo($prefixo, $data);
        $dadoAnotacao = $this->infoAnotacao($id_anotacao);
        $atualizacao = $this->AtualizarDados('licitacoes_anotacoes', $dados, $prefixo, $id_anotacao);
        $data['id'] =  $id_anotacao;
        if(isset($atualizacao['sucesso'])){
             $this->inserirLog(2, 5, json_encode(['id'=>$id_anotacao, 'nome'=> $data['texto'], 'antiga'=>$dadoAnotacao['texto']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
            
            return ['sucesso' => true, 'mensagem' => 'Dados atualizados com sucesso', 'item'=> $data];
        } else {
            return ['erro' => true, 'mensagem' => 'Falha na atualização dos dados'];
        }
        
    }
    
    
    // anexos
    
    function pegarIdAnexo($id){
        $sql = "SELECT licitacoes_anexo_id FROM licitacoes_anexos WHERE licitacoes_anexo_hash = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacoes_anexo_id'];
        
        return $id;
    }
    
    function cadastrarAnexo(){
        if(!$_POST['id']){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if((!isset($_POST['nome'])) || (!isset($_POST['documento']))){
            return['erro'=> true, 'mensagem'=> 'dados obrigatórios não passados'];
        }
        
        $chavesParaRemover = ['acao', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_anexo_';
       
        $data = $this->camposBanco($chavesParaRemover);
        $data['hash'] = $this->hasher();
        $data['autor'] = $this->autor;
        
        $dados = $this->definirPrefixo($prefixo, $data);
        
        $criacao = $this->inserirDados('licitacoes_anexos', $dados);

        $data['id'] = $this->conn->insert_id;
        $data['dataCriacao'] = date("Y-m-d H:i:s");
 
        if(isset($criacao['sucesso'])){
            $resposta = $this->vincularparaPesquisa('licitacoes_pesquisa_anexos');
            
            if(isset($resposta['sucesso'])){
                
                $this->inserirLog(1, 3, json_encode(['id'=>$data['id'], 'nome'=> $data['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo criado e vinculado', 'item'=> $data];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $criacao;
        }
    }
    
    function apagarAnexo(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        
        if(!isset($_POST['id_anexo'])){
            return['erro'=> true, 'mensagem'=> 'anexo não foi passado'];
        }
        
        $id_anexo  = $_POST['id_anexo'];
        
        if(!intval($_POST['id_anexo'])){
            $id_anexo  = $this->pegarIdAnexo($_POST['id_anexo']);
        }
        
        $dadoAnexo = $this->infoAnexos($id_anexo);
        $apagacao = $this->apagarDados('licitacoes_anexos', 'licitacoes_anexo_', $id_anexo);
        
        if(isset($apagacao['sucesso'])){
            $resposta = $this->desvincularParaPesquisa('licitacoes_pesquisa_anexos', $id_anexo);
            
            if(isset($resposta['sucesso'])){
                
                $this->inserirLog(3, 3, json_encode(['id'=>$id_anexo, 'nome'=> $dadoAnexo['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo apagado e desvinculado'];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $apagacao;
        }
    }
    
    function infoAnexos($id){
        $sql = "SELECT * FROM licitacoes_anexos WHERE licitacoes_anexo_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $array = $this->removerPrefixo($dado, 'licitacoes_anexo_');
            
            return $array;
        }
        else{
            return false;
        }
    } 
    
    
    // habilitacoes
    
    function pegarIdHabilitacao($id){
        $sql = "SELECT licitacoes_habilitacao_id FROM licitacoes_habilitacoes WHERE licitacoes_habilitacao_hash = '$id'";
                
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            return false;
        }
        
        $id = $resultado->fetch_assoc()['licitacoes_habilitacao_id'];
        
        return $id;
    }
    
    function cadastrarHabilitacao(){
        if(!$_POST['id']){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        
        $sql = "SELECT COUNT(licitacoes_habilitacao_id) as total FROM licitacoes_habilitacoes WHERE licitacoes_habilitacao_autor = '{$this->autor}' AND licitacoes_habilitacao_subconta = '{$this->empresa}'";
        
        $resultado = $this->conn->query($sql);
        
        $autorizados = $this->autorizacoes['documentos'];
        
        $total = 0;
        if($resultado->num_rows == 1){
            $total = $resultado->fetch_assoc()['total'];
        }
        
        if($total >= $autorizados){
            return [
                'erro'=> true, 
                'mensagem'=> 'Sua função não permite a você vincular mais documentos, para vincular mais documentos verifique nossos planos',
                'autorizado' => true
            ];
        }
        
        
        
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if((!isset($_POST['nome'])) || (!isset($_POST['documento']))){
            return['erro'=> true, 'mensagem'=> 'dados obrigatórios não passados'];
        }
        
        $chavesParaRemover = ['acao', 'id', 'csrf_token'];
        
        $prefixo = 'licitacoes_habilitacao_';
       
        $data = $this->camposBanco($chavesParaRemover);
        $data['hash'] = $this->hasher();
        $data['autor'] = $this->autor;
        $data['subconta'] = $this->empresa;
        $dados = $this->definirPrefixo($prefixo, $data);
        
        $criacao = $this->inserirDados('licitacoes_habilitacoes', $dados);

        $data['id'] = $this->conn->insert_id;
        $data['dataCriacao'] = date("Y-m-d H:i:s");
 
        if(isset($criacao['sucesso'])){
            $resposta = $this->vincularparaPesquisa('licitacoes_pesquisa_habilitacoes');
            
            if(isset($resposta['sucesso'])){
                
                $this->inserirLog(1, 4, json_encode(['id'=>$data['id'], 'nome'=> $data['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
                
                return ['sucesso'=> true, 'mensagem'=>'arquivo criado e vinculado', 'item'=> $data];
            }
            else{
                return $resposta;
            }

        }
        else{
            return $criacao;
        }
    }
    
    function apagarHabilitacao(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if(!isset($_POST['id_habilitacao'])){
            return['erro'=> true, 'mensagem'=> 'habilitação não foi passada'];
        }
        
        $id_habilitacao  = $_POST['id_habilitacao'];
        
        if(!intval($_POST['id_habilitacao'])){
            $id_habilitacao  = $this->pegarIdHabilitacao($_POST['id_habilitacao']);
        }
        
        $dadoHab = $this->infoHabilitacao($id_habilitacao);
        
        $resposta = $this->desvincularParaPesquisa('licitacoes_pesquisa_habilitacoes', $id_habilitacao);
        
        if(isset($resposta['sucesso'])){
            $this->inserirLog(3, 4, json_encode(['id'=>$id_habilitacao, 'nome'=> $dadoHab['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
            
            return ['sucesso'=> true, 'mensagem'=>'arquivo desvinculado'];
        }
        else{
            return $resposta;
        }

      
    }
    
    function vincularHabilitacao(){
        if(!isset($_POST['id'])){
            return['erro'=> true, 'mensagem'=> 'licitação não foi passada'];
        }
        
        $this->id = $_POST['id'];
        
        if(!intval($_POST['id'])){
            $this->id = $this->pegarIdLicitacaoPesquisa($_POST['id']);
        }
        
        if(!isset($_POST['id_habilitacao'])){
            return['erro'=> true, 'mensagem'=> 'habilitacao não foi passada'];
        }
        
        $id  = $_POST['id_habilitacao'];
        
        if(!intval($_POST['id_habilitacao'])){
            $id  = $this->pegarIdHabilitacao($_POST['id_habilitacao']);
        }
        
        $dadoHab = $this->infoHabilitacao($id);
        
        $this->inserirLog(2, 4, json_encode(['id'=>$id, 'nome'=> $dadoHab['nome']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $this->id);
        
        return $this->vincularparaPesquisa('licitacoes_pesquisa_habilitacoes', $id);
    }
    
    function infoHabilitacao($id){
        $sql = "SELECT * FROM licitacoes_habilitacoes WHERE licitacoes_habilitacao_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $array = $this->removerPrefixo($dado, 'licitacoes_habilitacao_');
            
            return $array;
        }
        else{
            return false;
        }
    } 
    
    
    // oportunidades
    
    function pegarOportunidades() {
        $this->tipo = isset($_POST['tipo']) && intval($_POST['tipo']) ? intval($_POST['tipo']) : 4;
        $autor = $this->autor;
    
        // Busca as tags e regiões do autor
        $sql = "SELECT licitacoes_oportunidade_tagmento, licitacoes_oportunidade_regioes 
                FROM licitacoes_oportunidades 
                WHERE licitacoes_oportunidade_autor = ? AND licitacoes_oportunidade_empresa = ?";
        $result = $this->fetchOne($sql, [$autor, $this->empresa], "ii");
    
        $infos = [
            'tags' => $result['licitacoes_oportunidade_tagmento'] ?? json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'regioes' => $result['licitacoes_oportunidade_regioes'] ?? json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ];
    
        return $this->compararOportunidades($infos);
    }
    
    function compararOportunidades($dados){
        $pagina = isset($_POST['pagina']) && (int)$_POST['pagina'] > 0 ? (int)$_POST['pagina'] : 1;
        $itensPorPagina = isset($_POST['paginacao']) ? (int)$_POST['paginacao'] : 12;
        $offset = ($pagina - 1) * $itensPorPagina;
        
        if (empty($dados['tags'])) {
            return ['erro' => true, 'mensagem' => 'Oportunidades não encontradas no banco de dados'];
        }
        
        $tags = json_decode($dados['tags'], true);
        if (empty($tags)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma tag válida fornecida'];
        }
        
        $regioes = json_decode($dados['regioes'], true); // Corrigido para 'regioes' em vez de 'tags'
        if (empty($regioes)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma região fornecida'];
        }
        
        
        if (!empty($_POST['data'])) {
            $data = new DateTime($_POST['data']); // Cria um objeto DateTime com a data do POST
        } else {
            $data = new DateTime();
        }
        
        switch ($this->tipo) {
            case 1:
                $data->modify('-1 day'); 
                $variavel = $data->format('Y-m-d');
                break;
            case 2:
                if ($data->format('l') == 'Sunday') {
                    $data->modify('+1 day');
                }
                $variavel = $data->format('Y') . '-' . $data->format('W');
                break;
            case 3:
                $variavel = $data->format('Y') . '-' . $data->format('m');
                break;

        }

        $array = $this->getOportunidades($variavel);  // Obtendo as oportunidades de acordo com a variável

        $licitacoes = [];
        if(!empty($array)){
            foreach ($array as $a) {
                // Verifica se o ID da oportunidade já está na lista de licitações
                if (!in_array($a, $licitacoes)) {  
                    $continuaTag = false;
                    $continuaRegiao = false;
                    
                    foreach ($tags as $t) {
                        foreach($a['descricao'] as $d){
                            if (strpos(strtolower($d), strtolower($t)) !== false) { 
                                $continuaTag = true; 
                                break;
                            }
                        }
                        
                        if (strpos(strtolower($a['objeto']), strtolower($t)) !== false) { 
                            $continuaTag = true; 
                        }
                        
                        if($continuaTag){
                            break;
                        }
                    }
            
                    // Verificação das regiões
                    foreach ($regioes as $r) {
                        if (strtolower($a['regiao']) == strtolower($r) && $continuaTag) {
                            $continuaRegiao = true; 
                            break;
                        }
                    }
            
                    if ($continuaTag && $continuaRegiao) {
                        $licitacoes[] = $a;
                    }
                }
            }
            
        }
        // Paginando os resultados com base no offset e itens por página
        $licitacoesPaginadas = array_slice($licitacoes, $offset, $itensPorPagina);
        
        if(count($licitacoes) > 0){
            $licitacoes2 = [];
            
            foreach($licitacoesPaginadas as $l){
                if($itensPorPagina < 10){
                    $licitacoes2[] = $this->infoLicitacao($l['id']);
                }else{
                    $licitacoes2[] = $l;
                }
                
            }

            return [
                'sucesso' => true,
                'licitacoes' => $licitacoes2,
                'pagina_atual' => $pagina,
                'total' => count($licitacoes)
            ];
        }else{
             return ['erro' => true, 'mensagem' => 'Nenhuma licitação encontrada com as condições fornecidas'];
        }
        
        
        
    }
    
    function getOportunidades($key, $ttl = 86400){
        $filePath = $this->getFilePath($key);
       
        if (file_exists($filePath)) {
            $fileTime = filemtime($filePath);
            $data = file_get_contents($filePath);
            return json_decode($data, true);
        }
        return null;
    }
    
    function compararOportunidades2($dados) {
        $pagina = isset($_POST['pagina']) && (int)$_POST['pagina'] > 0 ? (int)$_POST['pagina'] : 1;
        $itensPorPagina = isset($_POST['paginacao']) ? (int)$_POST['paginacao'] : 12;
        $offset = ($pagina - 1) * $itensPorPagina;
    
        if (empty($dados['tags'])) {
            return ['erro' => true, 'mensagem' => 'Oportunidades não encontradas no banco de dados'];
        }
    
        $tags = json_decode($dados['tags'], true);
        if (empty($tags)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma tag válida fornecida'];
        }
    
        // Prepara as tags para a consulta
        $tagsEscapadas = array_map([$this->conn, 'real_escape_string'], $tags);
        $tagsMatch = implode(" ", $tagsEscapadas);
    
        // Filtro de data
        $filtroData = $this->filtragemData();
        if (empty($filtroData)) {
            return ['erro' => true, 'mensagem' => 'Filtro de data inválido'];
        }
    
        // Consulta para obter os itens
        $sql = "
            SELECT DISTINCT li.licitacoes_item_licitacao
            FROM licitacoes_itens li
            INNER JOIN licitacoes_meta AS lm_pub ON lm_pub.lm_licitacao = li.licitacoes_item_licitacao AND lm_pub.lm_chave = 'dataPublicacaoPncp'
            INNER JOIN licitacoes_meta AS lm_enc ON lm_enc.lm_licitacao = li.licitacoes_item_licitacao AND lm_enc.lm_chave = 'dataEncerramentoPropostaPncp'
            LEFT JOIN licitacoes_meta AS lm_reg ON lm_reg.lm_licitacao = li.licitacoes_item_licitacao AND lm_reg.lm_chave = 'unidadeOrgaoUfSigla'
            WHERE MATCH (li.licitacoes_item_desc) AGAINST (? IN BOOLEAN MODE) AND $filtroData AND lm_enc.lm_valor > NOW()
            ORDER BY lm_pub.lm_valor ASC
            LIMIT ?, ?;
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sii', $tagsMatch, $offset, $itensPorPagina);
        $stmt->execute();
        $resultadoItens = $stmt->get_result();
    
        // Consulta para contar o total de registros
        $sqlCount = "
            SELECT COUNT(DISTINCT li.licitacoes_item_licitacao) AS total
            FROM licitacoes_itens li
            INNER JOIN licitacoes_meta AS lm_pub ON lm_pub.lm_licitacao = li.licitacoes_item_licitacao AND lm_pub.lm_chave = 'dataPublicacaoPncp'
            INNER JOIN licitacoes_meta AS lm_enc ON lm_enc.lm_licitacao = li.licitacoes_item_licitacao AND lm_enc.lm_chave = 'dataEncerramentoPropostaPncp'
            LEFT JOIN licitacoes_meta AS lm_reg ON lm_reg.lm_licitacao = li.licitacoes_item_licitacao AND lm_reg.lm_chave = 'unidadeOrgaoUfSigla'
            WHERE MATCH (li.licitacoes_item_desc) AGAINST (? IN BOOLEAN MODE) AND $filtroData AND lm_enc.lm_valor > NOW();
        ";
    
        $stmtCount = $this->conn->prepare($sqlCount);
        $stmtCount->bind_param('s', $tagsMatch);
        $stmtCount->execute();
        $totalRegistrosRow = $stmtCount->get_result()->fetch_assoc();
        $totalRegistros = $totalRegistrosRow['total'];
    
        if (!$totalRegistros) {
            return ['erro' => true, 'mensagem' => 'Nenhuma licitação encontrada com as condições fornecidas'];
        }
    
        // Obtém as licitações
        $licitacoes = [];
        while ($linha = $resultadoItens->fetch_assoc()) {
            $licitacoes[] = $this->infoLicitacao($linha['licitacoes_item_licitacao']);
        }
    
        return [
            'sucesso' => true,
            'licitacoes' => $licitacoes,
            'pagina_atual' => $pagina,
            'total' => $totalRegistros
        ];
    }
    
    function filtragemData() {
        $dataHoje = date('Y-m-d', strtotime('-1 day'));
        $inicioSemana = date('Y-m-d', strtotime('last Monday'));
        $fimSemana = date('Y-m-d', strtotime('next Sunday'));
        $inicioMes = date('Y-m-01');
        $fimMes = date('Y-m-t');
    
        switch ($this->tipo) {
            case 1:
                return "DATE(lm_pub.lm_valor) = '$dataHoje'";
            case 2:
                return "DATE(lm_pub.lm_valor) BETWEEN '$inicioSemana' AND '$fimSemana'";
            case 3:
                return "DATE(lm_pub.lm_valor) BETWEEN '$inicioMes' AND '$fimMes'";
            default:
                return "1=1"; // Retorna todas as datas se nenhum filtro for aplicado
        }
    }
    
    function definirOportunidades(){
        $autor = $this->autor;
        
        if(!isset($_POST['tags']) || !isset($_POST['regioes'])){
            return ['erro'=> true, 'mensagem'=> 'Um ou mais parâmetros não foram passados, os parâmetros são tags e região'];
        }
        
        // $grupos = $_POST['grupos'];
        // $classes = $_POST['classes'];
        $tags = $_POST['tags'];
        $regioes = $_POST['regioes'];
        $hash = $this->hasher();
        
        $sql = "SELECT * FROM licitacoes_oportunidades WHERE licitacoes_oportunidade_autor = '$autor' AND licitacoes_oportunidade_empresa = '{$this->empresa}'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 0){
            $sql2 = "INSERT INTO licitacoes_oportunidades(licitacoes_oportunidade_autor, licitacoes_oportunidade_regioes, licitacoes_oportunidade_tagmento, licitacoes_oportunidade_hash, licitacoes_oportunidade_empresa) VALUES ('$autor', '$regioes', '$tags',  '$hash', '{$this->empresa}')";
        }else{
            $dado = $resultado->fetch_assoc();
            
            $id = $dado['licitacoes_oportunidade_id'];

            $sql2 = "UPDATE licitacoes_oportunidades
            SET 
                licitacoes_oportunidade_regioes = '$regioes',
                licitacoes_oportunidade_tagmento = '$tags'
            WHERE 
                licitacoes_oportunidade_id = '$id'";
           
        }
        
        $resultado2 = $this->conn->query($sql2);
        
        return ['sucesso'=> true, 'mensagem'=> 'Oportunidades Definidas'];
    }
    
    function pegarClassesporGrupos(){
        if(!isset($this->id) || !intval($this->id)){
            return ['erro'=> true, 'mensagem'=> 'Grupo não foi passado'];
        }
        
        $id = $this->id;
        
        $sql = "SELECT licitacoes_classe_nome, licitacoes_classe_codigo, licitacoes_classe_id FROM licitacoes_classes WHERE licitacoes_classe_grupo = '$id'";
        
        
        $resultado = $this->conn->query($sql);
        
        
        
        if($resultado->num_rows > 0){
            $lista = [];
            
            while($dado = $resultado->fetch_Assoc()){
                $array = $this->removerPrefixo($dado, 'licitacoes_classe_');
                
                array_push($lista, $array);
            }
            
            
            return ['sucesso'=> true, 'lista'=> $lista];
            
        }else{
            return ['sucesso'=> true, 'lista'=> false];
        }
        
        
    }
    
    function pegarMinhasOportunidades(){
        $autor = $this->autor;
        
        $sql = "SELECT * FROM licitacoes_oportunidades WHERE licitacoes_oportunidade_autor = '$autor' AND licitacoes_oportunidade_empresa = '{$this->empresa}'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $dado = $resultado->fetch_assoc();
            $tags = $dado['licitacoes_oportunidade_tagmento'];
            
            // $infos = [
            //     'grupos'=> json_decode($dado['licitacoes_oportunidade_grupos'], TRUE),
            //     'classes'=> json_decode($dado['licitacoes_oportunidade_classes'], TRUE)
            // ];
            
            // $sql_grupo = "SELECT licitacoes_grupo_id, licitacoes_grupo_nome FROM licitacoes_grupos WHERE licitacoes_grupo_id IN (" . implode(',', $infos['grupos']) . ")";
            // $resultado_grupo = $this->conn->query($sql_grupo);
            // $grupos = [];
            // while ($row = $resultado_grupo->fetch_assoc()) {
            //     $grupos[] = [
            //         'id'=> $row['licitacoes_grupo_id'], 
            //         'nome'=>$row['licitacoes_grupo_nome']
            //     ];
            // }
            
            // $sql_classe = "SELECT licitacoes_classe_id, licitacoes_classe_nome FROM licitacoes_classes WHERE licitacoes_classe_id IN (" . implode(',', $infos['classes']) . ")";
            // $resultado_classe = $this->conn->query($sql_classe);
            // $classes = [];
            // while ($row = $resultado_classe->fetch_assoc()) {
            //     $classes[] = [
            //         'id'=> $row['licitacoes_classe_id'], 
            //         'nome'=>$row['licitacoes_classe_nome']
            //     ];
            // }
            
            $dados = [
                'tags'=> $tags,
                // 'grupos'=> $grupos,
                // 'classes'=> $classes
            ];
            
            return ['sucesso'=>true, 'item'=> $dados];
        }else{
            return ['sucesso'=>true, 'item'=> false];
        }
        
        
    }
    
    
    // filtro
    
    function pegarEstado($id) {
        $sql = "SELECT endereco_estado_codigo FROM enderecos_estados WHERE endereco_estado_id = ?";
        $result = $this->fetchOne($sql, [$id], "i");
        return $result ? $result['endereco_estado_codigo'] : false;
    }

    function pegarCidade($id) {
        $sql = "SELECT endereco_cidade_nome FROM enderecos_cidades WHERE endereco_cidade_id = ?";
        $result = $this->fetchOne($sql, [$id], "i");
        return $result ? $result['endereco_cidade_nome'] : false;
    }

    /**
 * Método otimizado para buscar valores distintos com cache eficiente
 */
    function puxarValoresDistintos($chave) {
        // Criar chave de cache mais específica e eficiente
        $cacheKey = 'distinct_values_' . md5($chave);
        $cacheData = $this->checkAndReturnCache($cacheKey);
    
        if (!empty($cacheData)) {
            return $cacheData;
        }
        
        // Usar um TTL maior para esse tipo de dado (muda raramente)
        $ttl = $this->tempoCache * 2;
        
        // Adicionar limite para evitar resultados muito grandes
        $sql = "SELECT DISTINCT lm_valor FROM licitacoes_meta 
                WHERE lm_chave = ? 
                ORDER BY lm_valor 
                LIMIT 500";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $chave);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $lista = [];
        while ($row = $result->fetch_assoc()) {
            $lista[] = $row['lm_valor'];
        }
        
        $response = ['sucesso' => true, 'lista' => $lista ?: false];
        
        // Guardar em cache com TTL mais longo
        $this->guardarCache($cacheKey, $response);
        
        return $response;
    }
    
    /**
     * Método otimizado para buscar modalidades
     */
    function puxarModalidades() {
        return $this->puxarValoresDistintos('modalidadeNome');
    }
    
    /**
     * Método otimizado para buscar esferas
     */
    function puxarEsferas() {
        return $this->puxarValoresDistintos('orgaoEntidadeEsferaId');
    }
    
    /**
     * Método otimizado para buscar situações
     */
    function puxarSituacao() {
        return $this->puxarValoresDistintos('situacaoCompraNomePncp');
    }
    
    /**
     * Método otimizado para buscar tipos de concorrência
     */
    function puxarConcorrencia() {
        return $this->puxarValoresDistintos('tipoInstrumentoConvocatorioNome');
    }
    
    /**
     * Versão otimizada do filtro com paginação por cursor
     * Pode ser usado como alternativa ao filtro padrão para casos específicos
     */
    function filtroCursor() {
        try {
            // Verificação de cache
            $cacheKey = 'filtro_cursor_' . md5(serialize($_POST));
            $cacheData = $this->checkAndReturnCache($cacheKey);
            if (!empty($cacheData)) {
                return $cacheData;
            }
            
            $itensPorPagina = isset($_POST['paginacao']) ? (int)$_POST['paginacao'] : 12;
            
            // Obter cursor da requisição
            $afterId = isset($_POST['after_id']) ? (int)$_POST['after_id'] : 0;
            $afterDate = isset($_POST['after_date']) ? $_POST['after_date'] : '';
            
            // Construir parâmetros da consulta
            $params = [];
            $types = "";
            $conditions = ["1=1"];
            $joins = [];
            
            // Adicionar todos os filtros normais
            // ... (mesma lógica do método filtro())
            
            // Adicionar condição de cursor para paginação eficiente
            if ($afterId > 0 && !empty($afterDate)) {
                $conditions[] = "(l.licitacao_att < ? OR (l.licitacao_att = ? AND l.licitacao_id < ?))";
                $params[] = $afterDate;
                $params[] = $afterDate;
                $params[] = $afterId;
                $types .= "ssi";
            }
            
            // Consulta principal
            $query = "SELECT l.licitacao_id, l.licitacao_att FROM licitacoes l " . 
                    implode(" ", $joins) . 
                    " WHERE " . implode(" AND ", $conditions) . 
                    " ORDER BY l.licitacao_att DESC, l.licitacao_id DESC LIMIT ?";
            
            // Adicionar limite + 1 para verificar se há mais páginas
            $params[] = $itensPorPagina + 1;
            $types .= "i";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Processar resultados
            $licitacoes = [];
            $licitacaoIds = [];
            $nextCursor = null;
            $hasMore = false;
            
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                $count++;
                
                // Se temos mais que o limite, há mais páginas
                if ($count > $itensPorPagina) {
                    $hasMore = true;
                    $nextCursor = [
                        'after_id' => $row['licitacao_id'],
                        'after_date' => $row['licitacao_att']
                    ];
                    break;
                }
                
                $licitacaoIds[] = $row['licitacao_id'];
            }
            
            // Buscar detalhes das licitações
            $licitacoes = $this->buscarDetalhesLicitacoes($licitacaoIds);
            
            $response = [
                'sucesso' => true,
                'licitacoes' => $licitacoes,
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor
            ];
            
            $this->guardarCache($cacheKey, $response);
            
            return $response;
        } catch (Exception $e) {
            error_log("Erro no filtroCursor: " . $e->getMessage());
            return ['erro' => true, 'mensagem' => 'Erro ao processar a consulta'];
        }
    }
    
    /**
     * Método inteligente para escolher entre filtro normal e cursor baseado em parâmetros
     */
    function filtroInteligente() {
        // Se tiver parâmetros de cursor, usar paginação por cursor
        if (isset($_POST['after_id']) && isset($_POST['after_date'])) {
            return $this->filtroCursor();
        }
        
        // Analisar complexidade da consulta
        $numFilters = 0;
        foreach ($_POST as $key => $value) {
            if (!empty($value) && $key !== 'acao' && $key !== 'pagina' && $key !== 'paginacao') {
                $numFilters++;
            }
        }
        
        // Para consultas muito complexas com paginação profunda, sugerir cursor
        if ($numFilters > 5 && isset($_POST['pagina']) && $_POST['pagina'] > 10) {
            // Adicionar aviso na resposta
            $response = $this->filtro();
            if (isset($response['sucesso']) && $response['sucesso']) {
                $response['aviso'] = 'Para melhor desempenho em consultas complexas com muitas páginas, considere usar paginação por cursor.';
                $response['suporte_cursor'] = true;
            }
            return $response;
        }
        
        // Consulta padrão
        return $this->filtro();
    }
    
    function query($sql, $params = [], $types = "") {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    function fetchAll($sql, $params = [], $types = "") {
        $result = $this->query($sql, $params, $types);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function fetchOne($sql, $params = [], $types = "") {
        $result = $this->query($sql, $params, $types);
        return $result->fetch_assoc();
    }

    function filtro() {
        try {
            // Criar uma chave de cache mais eficiente
            $relevantParams = array_filter($_POST, function($value) {
                return !empty($value) && $value !== '0';
            });
            
            $cacheKey = 'filtro_cache_' . md5(serialize($relevantParams));
            $cacheData = $this->checkAndReturnCache($cacheKey);

            if (!empty($cacheData)) {
                return $cacheData;
            }

            $pagina = isset($_POST['pagina']) && (int)$_POST['pagina'] > 0 ? (int)$_POST['pagina'] : 1;
            $itensPorPagina = isset($_POST['paginacao']) ? (int)$_POST['paginacao'] : 12;
            $offset = ($pagina - 1) * $itensPorPagina;
    
            $params = [];
            $types = "";
            $conditions = ["1=1"];
            $joins = [];
            $tables = ["licitacoes l"];
    
            // Construir filtros
            $filters = [
                'objeto' => ['field' => 'l.licitacao_objeto', 'type' => 's', 'operator' => isset($_POST['busca_exata']) ? '=' : 'LIKE'],
                'num_processo' => ['field' => 'lm_processo.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_processo ON lm_processo.lm_licitacao = l.licitacao_id AND lm_processo.lm_chave = "processo"'],
                'modalidade' => ['field' => 'lm_modalidade.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_modalidade ON lm_modalidade.lm_licitacao = l.licitacao_id AND lm_modalidade.lm_chave = "modalidadeNome"', 'operator' => '='],
                'id_gov' => ['field' => 'lm_numero.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_numero ON lm_numero.lm_licitacao = l.licitacao_id AND lm_numero.lm_chave = "numeroCompra"'],
                'orgao_nome' => ['field' => 'lm_orgao.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_orgao ON lm_orgao.lm_licitacao = l.licitacao_id AND lm_orgao.lm_chave = "unidadeOrgaoCodigoUnidade"'],
                'cod_orgao' => ['field' => 'lm_cod_orgao.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_cod_orgao ON lm_cod_orgao.lm_licitacao = l.licitacao_id AND lm_cod_orgao.lm_chave = "orgaoEntidadeRazaoSocial"'],
                'esfera' => ['field' => 'lm_esfera.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_esfera ON lm_esfera.lm_licitacao = l.licitacao_id AND lm_esfera.lm_chave = "orgaoEntidadeEsferaId"', 'operator' => '='],
                'data_abertura_min' => ['field' => 'lm_abertura_min.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_abertura_min ON lm_abertura_min.lm_licitacao = l.licitacao_id AND lm_abertura_min.lm_chave = "dataAberturaPropostaPncp"', 'operator' => '>='],
                'data_abertura_max' => ['field' => 'lm_abertura_max.lm_valor', 'type' => 's', 'join' => 'licitacoes_meta lm_abertura_max ON lm_abertura_max.lm_licitacao = l.licitacao_id AND lm_abertura_max.lm_chave = "dataAberturaPropostaPncp"', 'operator' => '<=']
            ];
    
            // Aplicar filtros
            foreach ($filters as $key => $filter) {
                if (isset($_POST[$key]) && !empty($_POST[$key])) {
                    if (isset($filter['join']) && !in_array($filter['join'], $joins)) {
                        $joins[] = "INNER JOIN {$filter['join']}";
                    }
                    
                    $operator = $filter['operator'] ?? 'LIKE';
                    $value = $_POST[$key];
                    
                    if (strpos($operator, '<') !== false || strpos($operator, '>') !== false) {
                        $valor = explode('/', $value);
                        if (count($valor) === 3) {
                            $value = $valor[2] . '-' . $valor[1] . '-' . $valor[0];
                        }
                        
                        $conditions[] = "DATE({$filter['field']}) $operator ?";
                    } else {
                        $conditions[] = "{$filter['field']} $operator ?";
                    }
                    
                    $params[] = $operator === 'LIKE' ? "%$value%" : $value;
                    $types .= $filter['type'];
                }
            }
    
            // Processar concorrências
            if (isset($_POST['concorrencias']) && !empty($_POST['concorrencias'])) {
                $concorrencias = json_decode($_POST['concorrencias'], true);
                if (!empty($concorrencias)) {
                    $joins[] = "INNER JOIN licitacoes_meta lm_concorrencia ON lm_concorrencia.lm_licitacao = l.licitacao_id AND lm_concorrencia.lm_chave = 'tipo_recurso'";
                    
                    $placeholders = implode(',', array_fill(0, count($concorrencias), '?'));
                    $conditions[] = "lm_concorrencia.lm_valor IN ($placeholders)";
                    
                    foreach ($concorrencias as $c) {
                        $params[] = $c;
                        $types .= "s";
                    }
                }
            }
    
            // Processar situação
            if (isset($_POST['situacao']) && !empty($_POST['situacao'])) {
                $hoje = date('Y-m-d');
                $joins[] = "INNER JOIN licitacoes_meta lm_abertura ON lm_abertura.lm_licitacao = l.licitacao_id AND lm_abertura.lm_chave = 'dataAberturaPropostaPncp'";
                $joins[] = "INNER JOIN licitacoes_meta lm_encerramento ON lm_encerramento.lm_licitacao = l.licitacao_id AND lm_encerramento.lm_chave = 'dataEncerramentoPropostaPncp'";
                
                switch ($_POST['situacao']) {
                    case 1:
                        $conditions[] = "DATE(lm_abertura.lm_valor) > '$hoje'";
                        break;
                    case 2:
                        $conditions[] = "DATE(lm_abertura.lm_valor) < '$hoje' AND DATE(lm_encerramento.lm_valor) > '$hoje'";
                        break;
                    default:
                        $conditions[] = "DATE(lm_encerramento.lm_valor) < '$hoje'";
                        break;
                }
            }
    
            // Processar UF
            if (isset($_POST['uf']) && !empty($_POST['uf'])) {
                $estado = $this->pegarEstado($_POST['uf']);
                if ($estado) {
                    $joins[] = "INNER JOIN licitacoes_meta lm_estado ON lm_estado.lm_licitacao = l.licitacao_id AND lm_estado.lm_chave = 'unidadeOrgaoUfSigla'";
                    $conditions[] = "lm_estado.lm_valor = ?";
                    $params[] = $estado;
                    $types .= "s";
                }
            }
    
            // Processar cidade
            if (isset($_POST['cidade']) && !empty($_POST['cidade'])) {
                $cidade = $this->pegarCidade($_POST['cidade']);
                if ($cidade) {
                    $joins[] = "INNER JOIN licitacoes_meta lm_cidade ON lm_cidade.lm_licitacao = l.licitacao_id AND lm_cidade.lm_chave = 'unidadeOrgaoMunicipioNome'";
                    $conditions[] = "lm_cidade.lm_valor = ?";
                    $params[] = $cidade;
                    $types .= "s";
                }
            }
    
            // Processar item_nome ou oportunidades
            if (isset($_POST['item_nome']) || isset($_POST['oportunidades'])) {
                $condicoesOport = [];
                
                if (isset($_POST['item_nome']) && !empty($_POST['item_nome'])) {
                    $condicoesOport[] = "licitacoes_item_desc LIKE ?";
                    $params[] = "%" . $_POST['item_nome'] . "%";
                    $types .= "s";
                }
                
                if (isset($_POST['oportunidades']) && !empty($_POST['oportunidades'])) {
                    $oportunidades = json_decode($_POST['oportunidades'], true);
                    if (!empty($oportunidades)) {
                        $oportunidadeConditions = [];
                        foreach ($oportunidades as $o) {
                            $oportunidadeConditions[] = "licitacoes_item_desc LIKE ?";
                            $params[] = "%" . $o . "%";
                            $types .= "s";
                        }
                        if (!empty($oportunidadeConditions)) {
                            $condicoesOport[] = "(" . implode(' OR ', $oportunidadeConditions) . ")";
                        }
                    }
                }
                
                if (!empty($condicoesOport)) {
                    $joins[] = "INNER JOIN licitacoes_itens li ON li.licitacoes_item_licitacao = l.licitacao_id";
                    $conditions[] = "(" . implode(' OR ', $condicoesOport) . ")";
                }
            }
    
            // Montagem da query otimizada (sem SQL_CALC_FOUND_ROWS)
            $query = "SELECT DISTINCT l.licitacao_id FROM licitacoes l " . implode(" ", $joins) . " WHERE " . implode(" AND ", $conditions) . " ORDER BY l.licitacao_att DESC";
            
            // Query de contagem separada (mais eficiente)
            $countQuery = "SELECT COUNT(DISTINCT l.licitacao_id) as total FROM licitacoes l " . implode(" ", $joins) . " WHERE " . implode(" AND ", $conditions);
            
            // Adicionar paginação à query principal
            $queryPaginated = $query . " LIMIT ?, ?";
            $params[] = $offset;
            $params[] = $itensPorPagina;
            $types .= "ii";
    
            // Executar query paginada
            $stmt = $this->conn->prepare($queryPaginated);
            if (!$stmt) {
                throw new Exception("Erro na preparação da consulta: " . $this->conn->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Se não houver resultados, retornar erro
            if ($result->num_rows === 0) {
                $errorResponse = ['erro' => true, 'mensagem' => 'Nenhuma licitação encontrada para os itens relacionados'];
                $this->guardarCache($cacheKey, $errorResponse);
                return $errorResponse;
            }
            
            // Coletar IDs das licitações
            $licitacaoIds = [];
            while ($row = $result->fetch_assoc()) {
                $licitacaoIds[] = $row['licitacao_id'];
            }
    
            // Buscar detalhes das licitações em lote
            $licitacoes = $this->buscarDetalhesLicitacoes($licitacaoIds);
            
            // Executar query de contagem
            $stmt = $this->conn->prepare($countQuery);
            // Remover parâmetros de paginação
            array_pop($params);
            array_pop($params);
            $types = substr($types, 0, -2);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $totalResult = $stmt->get_result();
            $totalRow = $totalResult->fetch_assoc();
            $totalData = $totalRow['total'];
            
            // Montar resposta
            $response = [
                'sucesso' => true,
                'licitacoes' => $licitacoes,
                'pagina_atual' => $pagina,
                'total' => $totalData
            ];
            
            // Guardar em cache
            $this->guardarCache($cacheKey, $response);
            
            return $response;
        } catch (Exception $e) {
            error_log("Erro na função filtro: " . $e->getMessage());
            return ['erro' => true, 'mensagem' => 'Erro interno ao processar a consulta'];
        }
    }

    // Método para buscar detalhes das licitações em lote
    private function buscarDetalhesLicitacoes($ids) {
        if (empty($ids)) {
            return [];
        }
        
        $licitacoes = [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        // Buscar dados básicos das licitações
        $query = "SELECT * FROM licitacoes WHERE licitacao_id IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $types = str_repeat("i", count($ids));
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Mapear IDs para posições no array final para manter a ordem
        $idMap = array_flip($ids);
        $licitacoesTemp = array_fill(0, count($ids), null);
        
        while ($row = $result->fetch_assoc()) {
            $position = $idMap[$row['licitacao_id']];
            $licitacoesTemp[$position] = $this->removerPrefixo($row, 'licitacao_');
        }
        
        // Buscar metadados das licitações em uma única consulta
        $query = "SELECT lm_licitacao, lm_chave, lm_valor FROM licitacoes_meta WHERE lm_licitacao IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Agrupar metadados por ID de licitação
        $metadados = [];
        while ($row = $result->fetch_assoc()) {
            $licitacaoId = $row['lm_licitacao'];
            if (!isset($metadados[$licitacaoId])) {
                $metadados[$licitacaoId] = [];
            }
            $metadados[$licitacaoId][$row['lm_chave']] = $row['lm_valor'];
        }
        
        // Combinar dados básicos com metadados
        foreach ($licitacoesTemp as $index => $licitacao) {
            if ($licitacao !== null) {
                $id = $licitacao['id'];
                if (isset($metadados[$id])) {
                    foreach ($metadados[$id] as $chave => $valor) {
                        $licitacao[$chave] = $valor;
                    }
                }
                $licitacoes[] = $licitacao;
            }
        }
        
        return $licitacoes;
    }

    // logs
    
    function pegarNotificacoes(){
        $empresa = $this->empresa;
        $autor = $this->autor;
        
        $pagina = isset($_POST['pagina']) && (int)$_POST['pagina'] > 0 ? (int)$_POST['pagina'] : false;
        $itensPorPagina = isset($_POST['paginacao']) ? (int)$_POST['paginacao'] : 12;
        $offset = $pagina ? ($pagina - 1) * $itensPorPagina : 0;
        
        // Consulta para pegar as licitações
        $sql = "SELECT licitacoes_pesquisa_pesquisa ,licitacoes_pesquisa_url,licitacoes_pesquisa_id,licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores
                FROM licitacoes_pesquisas 
                WHERE licitacoes_pesquisa_empresa = '$empresa'";
        
        $resultado = $this->conn->query($sql);
        $lista = [];
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $info = false;
                if(intval($dado['licitacoes_pesquisa_autor']) == intval($autor)){
                    $info = true;
                } else {
                    if($this->isJson($dado['licitacoes_pesquisa_visualizadores'])){
                        $array = json_decode($dado['licitacoes_pesquisa_visualizadores'], TRUE);
                    } else {
                        $array = [];
                    }
                    
                    foreach($array as $a){
                        if(intval($a) == intval($autor)){
                            $info = true;
                            break;
                        }
                    }
                }
                
                if($info){
                    $lista[] = [
                        'id'=> $dado['licitacoes_pesquisa_id'],
                        'url'=> $dado['licitacoes_pesquisa_url'],
                        'pesquisa'=> $dado['licitacoes_pesquisa_pesquisa']
                    ];
                }
                
                $this->pesquisas[$dado['licitacoes_pesquisa_id']] = [
                    'url'=> $dado['licitacoes_pesquisa_url'],
                    'pesquisa'=> $dado['licitacoes_pesquisa_pesquisa']
                ];
            }
        }
        
        $dados = [];
        $totalItens = 0;
        if(count($lista) > 0){
            $ids = array_map(function($item) { return $item['id']; }, $lista);  // Pega os IDs da lista
            $sql = "licitacoes_log_pesquisa IN ('" . implode("','", $ids) . "')";
            
            // Consulta para pegar os logs com LIMIT para paginação
            $sqlCompleto = "SELECT * FROM licitacoes_logs WHERE $sql ORDER BY licitacoes_log_id DESC LIMIT $offset, $itensPorPagina";
        
            $resultadoLogs = $this->conn->query($sqlCompleto);
            if($resultadoLogs->num_rows > 0){
                while($log = $resultadoLogs->fetch_assoc()){
                    $dados[] = [
                        'acao'=> $log['licitacoes_log_acao'],
                        'aca_alvo'=> $log['licitacoes_log_acao_alvo'],
                        'pesquisa'=> $this->pesquisas[$log['licitacoes_log_pesquisa']],
                        'autor'=> $this->displayUsuarios($log['licitacoes_log_autor']),
                        'dataCriacao'=> $log['licitacoes_log_data']  // Corrigido para acessar o campo de data corretamente
                    ];
                }
            }
    
            // Consulta para pegar o total de registros sem o LIMIT
            $sqlTotal = "SELECT COUNT(*) as total FROM licitacoes_logs WHERE $sql";
            $resultadoTotal = $this->conn->query($sqlTotal);
            
            if($resultadoTotal->num_rows > 0){
                $totalItens = $resultadoTotal->fetch_assoc()['total'];
            }
        }
        
        // Retorna os dados e o total de itens
        return [
            'sucesso' => true,
            'lista' => $dados,
            'numeros' => [
                'total'=>$totalItens
            ]
        ];
    }

    function render(){
        if(!$this->acao){
            return['erro'=> true, 'mensagem'=> 'ação não enviada'];
        }
        
        if(!$this->autor){
            return['erro'=> true, 'mensagem'=> 'usuário não está logado'];
        }
        
        if(!$this->empresa){
            return['erro'=> true, 'mensagem'=> 'Usuário sem empresa'];
        }
        
        switch($this->acao){
            // tarefas
            case 'cadastrarTarefa':
                return $this->cadastrarTarefa();
                break;
            case 'apagarTarefa':
                return $this->apagarTarefa();
                break;
            case 'editarTarefa':
                return $this->atualizarTarefa();
                break;
            case 'statusTarefa':
                return $this->statusTarefa();
                break;
              
                
            // aotacoes
            case 'cadastrarAnotacao':
                return $this->cadastrarAnotacao();
                break;
            case 'apagarAnotacao':
                return $this->apagarAnotacao();
                break;
            case 'editarAnotacao':
                return $this->atualizarAnotacao();
                break;
                
            
            
            // anexos
            case 'adicionarAnexo':
                return $this->cadastrarAnexo();
                break;
            case 'apagarAnexo':
                return $this->apagarAnexo();
                break;
                
                
            // habilitacoes
            case 'adicionarHabilitacao':
                return $this->cadastrarHabilitacao();
                break;
            case 'apagarHabilitacao':
                return $this->apagarHabilitacao();
                break;
            case 'vincularHabilitacao':
                return $this->vincularHabilitacao();
                break;
                
                
            // licitacoes
            case 'listagemlicitacoesAutor':
                return $this->listagensLicitacoesAutor();
                break;
            case 'vincularLicitacao':
                return $this->vincularLicitacao();
                break;
            case 'liberarLicitacao':    
                return $this->liberar();
                break;
            case 'bloquearLicitacao':
                return $this->bloquear();
                break;
                
                
            
              
            // oportunidades
            case 'definirOportunidades':
                return $this->definirOportunidades();
                break;
            case 'pegarOportunidades':
                return $this->pegarOportunidades();
                break;
            case 'pegarClassesPorGrupo':
                return $this->pegarClassesporGrupos();
                break;
            case 'pegarMinhasOportunidades':
                return $this->pegarMinhasOportunidades();
                break;
                
                
            // logs
            case 'pegarLogs':
                return $this->pegarNotificacoes();
                break;
                
                
            // filtro
            case 'pegarModalidades':
                return $this->puxarModalidades();
                break;
            case 'pegarEsferas':
                return $this->puxarEsferas();
                break;
            case 'pegarSituacao':
                return $this->puxarSituacao();
                break;
            case 'pegarConcorrencia':
                return $this->puxarConcorrencia();
                break;
                
            case 'filtrar':
                $this->criarIndicesOtimizados();
                return $this->filtro();
                break;
            case 'invalidarCache':
                return $this->invalidateCache('filtro_');
                break;
                
                
            default:
                return['erro'=> true, 'mensagem'=> 'parâmetro de ação não encontrado'];
                break;
        }
    }
    
}

$acao = new AcoesLicitah();

$resposta = $acao->render();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

?>