<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);

include __DIR__."/../../../../admin/conn.php";
include __DIR__."/../../../../admin/notificacao.php";

class NotificacaoLicitacoes {
    private $conn;
    private $cacheDir;
    
    function __construct() {
        $this->conn = conn();
        $this->cacheDir = __DIR__ . '/../caches';
    }
    
    /**
     * Verifica se uma string é JSON válido
     */
    function isJson($string) {
        if (!is_string($string)) return false;
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    /**
     * Envia notificações para vários usuários
     */
    private function enviarNotificacoes($autores, $corpo) {
        $respostas = [];
        
        foreach ($autores as $autor) {
            $notificacao = new Notificacao($autor);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            $status = isset($resposta['erro']) ? 'erro' : 'enviado';
            $respostas[] = [
                'status' => $status,
                'destinatario' => $autor
            ];
        }
        
        return $respostas;
    }
    
    /**
     * Obtém o caminho do arquivo de cache
     */
    private function getFilePath($key) {
        $hashKey = md5($key);
        
        if (strpos($key, 'filtro_') === 0) {
            return $this->cacheDir . '/results/' . $hashKey . '.cache';
        }
        
        if (strpos($key, 'modalidades') !== false || 
            strpos($key, 'esferas') !== false || 
            strpos($key, 'situacao') !== false) {
            return $this->cacheDir . '/metadata/' . $hashKey . '.cache';
        }
        
        return $this->cacheDir . '/' . $hashKey . '.cache';
    }
    
    /**
     * Recupera dados de oportunidades do cache
     */
    function getOportunidades($key) {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            return json_decode($data, true);
        }
        return null;
    }
    
    /**
     * Decodifica JSON ou retorna array vazio
     */
    private function decodeJsonOrEmpty($json) {
        if ($this->isJson($json)) {
            return json_decode($json, true);
        }
        return is_array($json) ? $json : [];
    }
    
    /**
     * Obtém IDs de usuários a partir de seus nomes
     */
    function pegarAutores($usuarios) {
        if (empty($usuarios)) {
            return [];
        }
        
        $ids = implode("','", array_map([$this->conn, 'real_escape_string'], $usuarios));
        $sql = "SELECT usuario_user, usuario_id FROM usuarios WHERE usuario_user IN ('$ids')";
        
        $resultado = $this->conn->query($sql);
        $userid = [];
        
        if ($resultado && $resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $userid[$dado['usuario_user']] = $dado['usuario_id'];
            }
        }
        
        return $userid;
    }
    
    /**
     * Processa datas para busca no banco
     */
    private function processarDatas($dias = 0) {
        $data = new DateTime();
        if ($dias > 0) {
            $data->modify("+$dias day");
        }
        
        return $data->format('Y-m-d');
    }
    
    /**
     * Verifica oportunidades e envia notificações
     */
    function oportunidades() {
        $sql = "SELECT licitacoes_oportunidade_tags, licitacoes_oportunidade_autor, licitacoes_oportunidade_regioes 
                FROM licitacoes_oportunidades";
        $resultado = $this->conn->query($sql);
        
        if (!$resultado || $resultado->num_rows == 0) {
            return ['erro' => true, 'mensagem' => 'Nenhum usuário cadastrou oportunidades no sistema'];
        }
        
        $lista = [];
        
        while ($dado = $resultado->fetch_assoc()) {
            $tags = $this->decodeJsonOrEmpty($dado['licitacoes_oportunidade_tags']);
            $regioes = $this->decodeJsonOrEmpty($dado['licitacoes_oportunidade_regioes']);
            $autor = $dado['licitacoes_oportunidade_autor'];
            
            if (empty($tags) && empty($regioes)) {
                continue;
            }
            
            $lista[] = [
                'autor' => $autor,
                'tags' => $tags,
                'regioes' => $regioes,
                'licitacoes' => []
            ];
        }
        
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma configuração de oportunidade válida encontrada'];
        }
        
        $data = new DateTime();
        $data->modify('-1 day'); 
        $array = $this->getOportunidades($data->format('Y-m-d'));
        
        if (empty($array)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma oportunidade encontrada para a data atual'];
        }
        
        foreach ($lista as &$l) {
            foreach ($array as $a) {
                if (in_array($a, $l['licitacoes'])) {
                    continue;
                }
                
                $continua = false;
                
                // Verificar tags
                foreach ($l['tags'] as $t) {
                    foreach ($a['descricao'] as $d) {
                        if ($d !== null && stripos((string)$d, $t) !== false) {
                            $continua = true;
                            break 2;
                        }
                    }
                }
                
                // Verificar regiões se a tag foi encontrada
                if ($continua) {
                    foreach ($l['regioes'] as $r) {
                        if (strcasecmp($a['regiao'], $r) === 0) {
                            $l['licitacoes'][] = $a;
                            break;
                        }
                    }
                }
            }
        }
        
        return $this->enviarNotificacoesOportunidades($lista);
    }
    
    /**
     * Envia notificações de oportunidades
     */
    private function enviarNotificacoesOportunidades($lista) {
        $respostas = [];
        
        foreach ($lista as $l) {
            if (count($l['licitacoes']) == 0) {
                continue;
            }
            
            $corpo = [
                'header' => 'Você tem novas oportunidades',
                'body' => 'Foi disponibilizada novas oportunidades de licitações: '.count($l['licitacoes']),
                'link' => 'licitacoes/oportunidades'
            ];
            
            $notificacao = new Notificacao($l['autor']);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            $respostas[] = [
                'status' => isset($resposta['erro']) ? 'erro' : 'enviado',
                'destinatario' => $l['autor'],
                'quantidade' => count($l['licitacoes'])
            ];
        }
        
        return empty($respostas) ? 
            ['info' => true, 'mensagem' => 'Nenhuma notificação enviada'] : 
            ['sucesso' => true, 'detalhes' => $respostas];
    }
    
    /**
     * Busca licitações por data de abertura/encerramento
     */
    private function buscarLicitacoes($campo, $data) {
        $sql = "SELECT lm_licitacao FROM licitacoes_meta WHERE lm_chave = ? AND lm_valor LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $dataParam = "%$data%";
        $stmt->bind_param("ss", $campo, $dataParam);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $lista = [];
        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $lista[] = $dado['lm_licitacao'];
            }
            
            return $this->buscarLicitacoesPorIds($lista);
        }
        
        return [];
    }
    
    /**
     * Busca licitações por IDs e retorna informações completas
     */
    private function buscarLicitacoesPorIds($lista) {
        if (empty($lista)) {
            return [];
        }
        
        $ids = implode("','", array_map([$this->conn, 'real_escape_string'], $lista));
        $sql = "SELECT licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_url, licitacoes_pesquisa_autor, 
                licitacoes_pesquisa_visualizadores 
                FROM licitacoes_pesquisas 
                WHERE licitacoes_pesquisa_numero IN ('$ids')";
        
        $resultado = $this->conn->query($sql);
        $licitacoes = [];
        
        if ($resultado && $resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $autores = $this->decodeJsonOrEmpty($dado['licitacoes_pesquisa_visualizadores']);
                
                // Adicionar autor principal
                if (!in_array($dado['licitacoes_pesquisa_autor'], $autores)) {
                    $autores[] = $dado['licitacoes_pesquisa_autor'];
                }
                
                $licitacoes[] = [
                    'autores' => $autores,
                    'licitacao' => $dado['licitacoes_pesquisa_pesquisa'],
                    'link' => $dado['licitacoes_pesquisa_url'],
                ];
            }
        }
        
        return $licitacoes;
    }
    
    /**
     * Verifica licitações que abrem hoje
     */
    function abertas() {
        $dataHoje = $this->processarDatas();
        $licitacoes = $this->buscarLicitacoes('dataAberturaPropostaPncp', $dataHoje);
        
        if (empty($licitacoes)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma licitação será aberta hoje'];
        }
        
        return $this->enviarNotificacoesLicitacoes($licitacoes, 'abertura', 'Abertura de Licitação', 
            'A licitação de número %s será aberta as propostas hoje.');
    }
    
    /**
     * Verifica licitações que fecham amanhã
     */
    function fechadas() {
        $dataAmanha = $this->processarDatas(1);
        $licitacoes = $this->buscarLicitacoes('dataEncerramentoPropostaPncp', $dataAmanha);
        
        if (empty($licitacoes)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma licitação será fechada amanhã'];
        }
        
        return $this->enviarNotificacoesLicitacoes($licitacoes, 'fechamento', 'Prazo de Licitação Próximo', 
            'A licitação de número %s será fechada as propostas amanhã.');
    }
    
    /**
     * Verifica licitações que vencem nos próximos dias
     */
    function licitacoes_vencer() {
        $dataInicio = $this->processarDatas(1);
        $dataFim = $this->processarDatas(5);
        
        $licitacoes = $this->buscarLicitacoesEntreDatas('dataEncerramentoPropostaPncp', $dataInicio, $dataFim);
        
        if (empty($licitacoes)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma licitação vence nos próximos 5 dias'];
        }
        
        return $this->enviarNotificacoesLicitacoes($licitacoes, 'vencimento', 'Prazo de Licitação Próximo', 
            'A licitação de número %s terá as propostas fechada nos próximos 5 dias.');
    }
    
    /**
     * Busca licitações entre datas
     */
    private function buscarLicitacoesEntreDatas($campo, $dataInicio, $dataFim) {
        $sql = "SELECT lm_licitacao FROM licitacoes_meta 
                WHERE lm_chave = ? 
                AND lm_valor BETWEEN ? AND ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $campo, $dataInicio, $dataFim);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $lista = [];
        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $lista[] = $dado['lm_licitacao'];
            }
            
            return $this->buscarLicitacoesPorIds($lista);
        }
        
        return [];
    }
    
    /**
     * Envia notificações para licitações
     */
    private function enviarNotificacoesLicitacoes($lista, $tipo, $titulo, $mensagemTemplate) {
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => "Nenhuma licitação para $tipo encontrada"];
        }
        
        $respostas = [];
        
        foreach ($lista as $l) {
            if (empty($l['autores'])) {
                continue;
            }
            
            $mensagem = sprintf($mensagemTemplate, $l['licitacao']);
            $corpo = [
                'header' => $titulo,
                'body' => $mensagem,
                'link' => 'licitacoes/'.$l['link']
            ];
            
            foreach ($l['autores'] as $autor) {
                $notificacao = new Notificacao($autor);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                $respostas[] = [
                    'status' => isset($resposta['erro']) ? 'erro' : 'enviado',
                    'destinatario' => $autor,
                    'licitacao' => $l['licitacao']
                ];
            }
        }
        
        return empty($respostas) ? 
            ['info' => true, 'mensagem' => "Nenhuma notificação de $tipo enviada"] : 
            ['sucesso' => true, 'detalhes' => $respostas];
    }
    
    /**
     * Busca tarefas entre datas
     */
    private function buscarTarefasEntreDatas($dataInicio, $dataFim = null, $concluida = false) {
        $sql = "SELECT licitacoes_tarefa_nome, licitacoes_tarefa_usuario, 
                licitacoes_tarefa_usuario_display, licitacoes_tarefa_autor 
                FROM licitacoes_tarefas 
                WHERE licitacoes_tarefa_andamento = '".($concluida ? '1' : '0')."'";
                
        if ($dataFim) {
            $sql .= " AND licitacoes_tarefa_prazo BETWEEN '$dataInicio' AND '$dataFim'";
        } else {
            $sql .= " AND licitacoes_tarefa_prazo < '$dataInicio'";
        }
        
        $resultado = $this->conn->query($sql);
        $lista = [];
        $usuarios = [];
        
        if ($resultado && $resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                if (!empty($dado['licitacoes_tarefa_usuario_display'])) {
                    $usuarios[] = $dado['licitacoes_tarefa_usuario_display'];
                }
                
                $lista[] = [
                    'tarefa' => $dado['licitacoes_tarefa_nome'],
                    'outro_usuario' => $dado['licitacoes_tarefa_usuario'],
                    'autores' => [$dado['licitacoes_tarefa_autor']],
                    'display' => $dado['licitacoes_tarefa_usuario_display'],
                ];
            }
            
            // Buscar IDs de usuários
            if (!empty($usuarios)) {
                $usuariosId = $this->pegarAutores($usuarios);
                
                foreach ($lista as &$l) {
                    if (intval($l['outro_usuario']) && !empty($l['display'])) {
                        if (!empty($usuariosId[$l['display']])) {
                            if (!in_array($usuariosId[$l['display']], $l['autores'])) {
                                $l['autores'][] = $usuariosId[$l['display']];
                            }
                        }
                    }
                }
            }
        }
        
        return $lista;
    }
    
    /**
     * Verifica tarefas que vencem nos próximos dias
     */
    function tarefas_vencer() {
        $dataInicio = $this->processarDatas(1);
        $dataFim = $this->processarDatas(5);
        
        $lista = $this->buscarTarefasEntreDatas($dataInicio, $dataFim);
        
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma tarefa vence nos próximos 5 dias'];
        }
        
        return $this->enviarNotificacoesTarefas($lista, 'vencimento', 'Tarefa Próxima do Vencimento', 
            'Atenção! A tarefa %s vence nos próximos 5 dias. Conclua antes do prazo!');
    }
    
    /**
     * Verifica tarefas atrasadas
     */
    function tarefa_atraso() {
        $dataHoje = $this->processarDatas();
        $lista = $this->buscarTarefasEntreDatas($dataHoje);
        
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => 'Nenhuma tarefa está atrasada'];
        }
        
        return $this->enviarNotificacoesTarefas($lista, 'atraso', 'Tarefa Atrasada', 
            'A tarefa %s está atrasada. Revise sua agenda e conclua o mais rápido possível');
    }
    
    /**
     * Envia notificações para tarefas
     */
    private function enviarNotificacoesTarefas($lista, $tipo, $titulo, $mensagemTemplate) {
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => "Nenhuma tarefa para $tipo encontrada"];
        }
        
        $respostas = [];
        
        foreach ($lista as $l) {
            if (empty($l['autores'])) {
                continue;
            }
            
            $mensagem = sprintf($mensagemTemplate, $l['tarefa']);
            $corpo = [
                'header' => $titulo,
                'body' => $mensagem
            ];
            
            foreach ($l['autores'] as $autor) {
                $notificacao = new Notificacao($autor);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                $respostas[] = [
                    'status' => isset($resposta['erro']) ? 'erro' : 'enviado',
                    'destinatario' => $autor,
                    'tarefa' => $l['tarefa']
                ];
            }
        }
        
        return empty($respostas) ? 
            ['info' => true, 'mensagem' => "Nenhuma notificação de tarefa para $tipo enviada"] : 
            ['sucesso' => true, 'detalhes' => $respostas];
    }
    
    /**
     * Busca documentos entre datas
     */
    private function buscarDocumentosEntreDatas($dataInicio, $dataFim = null) {
        $sql = "SELECT licitacoes_habilitacao_nome, licitacoes_habilitacao_autor 
                FROM licitacoes_habilitacoes WHERE ";
                
        if ($dataFim) {
            $sql .= "licitacoes_habilitacao_data_validade BETWEEN '$dataInicio' AND '$dataFim'";
        } else {
            $sql .= "licitacoes_habilitacao_data_validade < '$dataInicio'";
        }
        
        $resultado = $this->conn->query($sql);
        $lista = [];
        
        if ($resultado && $resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $lista[] = [
                    'documento' => $dado['licitacoes_habilitacao_nome'],
                    'autor' => $dado['licitacoes_habilitacao_autor'],
                ];
            }
        }
        
        return $lista;
    }
    
    /**
     * Verifica documentos expirados
     */
    function documentos_atrasado() {
        $dataHoje = $this->processarDatas();
        $lista = $this->buscarDocumentosEntreDatas($dataHoje);
        
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => 'Nenhum documento está expirado'];
        }
        
        return $this->enviarNotificacoesDocumentos($lista, 'expirado', 'Documento Expirado', 
            'O documento %s expirou. Envie uma nova versão para continuar participando das licitações.');
    }
    
    /**
     * Verifica documentos que expiram em breve
     */
    function documentos_vencer() {
        $dataInicio = $this->processarDatas(1);
        $dataFim = $this->processarDatas(5);
        
        $lista = $this->buscarDocumentosEntreDatas($dataInicio, $dataFim);
        
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => 'Nenhum documento expira nos próximos 5 dias'];
        }
        
        return $this->enviarNotificacoesDocumentos($lista, 'vencimento', 'Documento Prestes a Expirar', 
            'O documento %s expira nos próximos 5 dias. Atualize para evitar problemas futuros');
    }
    
    /**
     * Envia notificações para documentos
     */
    private function enviarNotificacoesDocumentos($lista, $tipo, $titulo, $mensagemTemplate) {
        if (empty($lista)) {
            return ['erro' => true, 'mensagem' => "Nenhum documento para $tipo encontrado"];
        }
        
        $respostas = [];
        
        foreach ($lista as $l) {
            if (empty($l['autor'])) {
                continue;
            }
            
            $mensagem = sprintf($mensagemTemplate, $l['documento']);
            $corpo = [
                'header' => $titulo,
                'body' => $mensagem,
                'link' => 'a/licitacoes/documentos'
            ];
            
            $notificacao = new Notificacao($l['autor']);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            $respostas[] = [
                'status' => isset($resposta['erro']) ? 'erro' : 'enviado',
                'destinatario' => $l['autor'],
                'documento' => $l['documento']
            ];
        }
        
        return empty($respostas) ? 
            ['info' => true, 'mensagem' => "Nenhuma notificação de documento para $tipo enviada"] : 
            ['sucesso' => true, 'detalhes' => $respostas];
    }
    
    /**
     * Método principal que direciona as chamadas da API
     */
    function init() {
        if (empty($_GET['acao'])) {
            return ['erro' => true, 'mensagem' => 'Ação não foi passada'];
        }
        
        $acoes = [
            'oportunidades' => 'oportunidades',
            'licitacoes_abertas' => 'abertas',
            'licitacoes_fechadas' => 'fechadas',
            'licitacoes_vencer' => 'licitacoes_vencer',
            'tarefas_atrasada' => 'tarefa_atraso',
            'tarefas_vencer' => 'tarefas_vencer', 
            'documentos_atrasado' => 'documentos_atrasado',
            'documentos_vencer' => 'documentos_vencer'
        ];
        
        $acao = $_GET['acao'];
        
        if (isset($acoes[$acao])) {
            $metodo = $acoes[$acao];
            return $this->$metodo();
        }
        
        return ['erro' => true, 'mensagem' => 'Ação não foi encontrada'];
    }
}

$acao = new NotificacaoLicitacoes();
$resposta = $acao->init();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);