<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);


include __DIR__."/../../../../admin/conn.php";
include __DIR__."/../../../../admin/notificacao.php";

class NotificacaoLicitacoes{
    private $conn;
    private $cacheDir;
    
    function __construct(){
        $this->conn = conn();
        $this->cacheDir = __DIR__ . '/../caches';
    }
    
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    function oportunidades(){
        $sql = "SELECT licitacoes_oportunidade_tags, licitacoes_oportunidade_autor, licitacoes_oportunidade_regioes
                FROM licitacoes_oportunidades";
        $resultado = $this->conn->query($sql);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $tags = $dado['licitacoes_oportunidade_tags'];
                $regioes = $dado['licitacoes_oportunidade_regioes'];
                $autor = $dado['licitacoes_oportunidade_autor'];
                
                if($this->isJson($tags)){
                    $tags = json_decode($tags);
                }
                
                if($this->isJson($regioes)){
                    $regioes = json_decode($regioes);
                }
                
                
                if(empty($tag) && empty($regioes)){
                    continue;
                }
                
                $lista[] = [
                    'autor'=> $autor,
                    'tags'=>$tags,
                    'regioes'=> $regioes,
                    'licitacoes'=> []
                ];
            }
            
            $data = new DateTime(); 
            $array = $this->getOportunidades($data->format('Y-m-d'));
            foreach($lista as &$l){
                foreach ($array as $a) {
                    // Agora trabalhe diretamente com a referência
                    if (!in_array($a, $l['licitacoes'])) {  
                        $continua = false;
                        $tags = $l['tags'];
                        foreach ($tags as $t) {
                            foreach($a['descricao'] as $d){
                                if ($d !== null && strpos(strtolower((string)$d), strtolower($t)) !== false) { 
                                    $continua = true; 
                                    break;
                                }
                            }
                            
                            if($continua){
                                break;
                            }
                        }
                        $regioes = $l['regioes'];
                        // Verificação das regiões
                        foreach ($regioes as $r) {
                            if (strtolower($a['regiao']) == strtolower($r) && $continua) {
                                $continua = true; 
                                break;
                            }
                        }
                
                        if ($continua) {
                            $l['licitacoes'][] = $a;
                        }
                    }
                }
            }
            
            return $this->mandarOportunidades($lista);
            
        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhum usuário cadastrou oportunidades no sistema'];
        }
    }
    
    private function mandarOportunidades($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem oportunidades'];
        }
        
        $respostas = [];
        foreach($lista as $l){
            if(count($l['licitacoes']) == 0){
                continue;
            }

            $corpo = [
                'header'=> 'Você tem novas oportunidades',
                'body'=> 'Foi disponibilizada novas oportunidades de licitações: '.count($l['licitacoes']) ,
                'link'=> 'licitacoes/oportunidades'
            ];
            
            $notificacao = new Notificacao($l['autor']);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            if(isset($resposta['erro'])){
                $respostas[] = ['mensagem'=> 'deu erro'];
            }
            
            $respostas[] = ['mensagem'=> 'mandou'];
            
        }
        
        return $respostas;
    }
    
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
    
    function getOportunidades($key){
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            $fileTime = filemtime($filePath);
            $data = file_get_contents($filePath);
            return json_decode($data, true);
        }
        return null;
    }
    
    function abertas(){
        $data = new DateTime(); 
        $dataFormatada = $data->format('Y-m-d');
        
        $sql = "SELECT lm_licitacao FROM licitacoes_meta WHERE lm_chave = 'dataAberturaPropostaPncp' AND lm_valor LIKE '%{$dataFormatada}%'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $lista = [];
            while($dado = $resultado->fetch_assoc()){
                $lista[] = $dado['lm_licitacao'];
            }
            
            $ids = implode("','", $lista);
            
            $sql = "SELECT licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_url, licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores  FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_numero IN ('{$ids}')";
            
            $resultado = $this->conn->query($sql);
            
            if($resultado->num_rows > 0){
                $licitacoes = [];
                while($dado = $resultado->fetch_assoc()){
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
                    
                    $licitacoes[] = [
                        'autores'=> $autores,
                        'licitacao'=> $dado['licitacoes_pesquisa_pesquisa'],
                        'link'=> $dado['licitacoes_pesquisa_url'],
                    ];
                }
                
                
                return $this->mandarMensagemAberta($licitacoes);
            }else{
                return ['erro'=> true, 'mensagem'=> 'Nenhuma licitação foi vinculada com abertura nessa data'];
            }
            
        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma licitação foi aberta nessa data'];
        }
        
    } 
    
    function fechadas(){
        $data = new DateTime(); 
        $data->modify('+1 day');
        $dataFormatada = $data->format('Y-m-d');
        
        $sql = "SELECT lm_licitacao FROM licitacoes_meta WHERE lm_chave = 'dataEncerramentoPropostaPncp' AND lm_valor LIKE '%{$dataFormatada}%'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $lista = [];
            while($dado = $resultado->fetch_assoc()){
                $lista[] = $dado['lm_licitacao'];
            }
            
            $ids = implode("','", $lista);
            
            $sql = "SELECT licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_url, licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores  FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_numero IN ('{$ids}')";
            
            $resultado = $this->conn->query($sql);
            
            if($resultado->num_rows > 0){
                $licitacoes = [];
                while($dado = $resultado->fetch_assoc()){
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
                    
                    $licitacoes[] = [
                        'autores'=> $autores,
                        'licitacao'=> $dado['licitacoes_pesquisa_pesquisa'],
                        'link'=> $dado['licitacoes_pesquisa_url'],
                    ];
                }
                
                
                return $this->mandarMensagemFechada($licitacoes);
            }else{
                return ['erro'=> true, 'mensagem'=> 'Nenhuma licitação foi vinculada com fechamento nessa data'];
            }
            
        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma licitação será fechada nessa data'];
        }
        
    } 
    
    function mandarMensagemAberta($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem licitações que serão abertas hoje'];
        }
        
        
        foreach($lista as $l){
            if(count($l['autores']) == 0){
                continue;
            }
            
            
            $autores = $l['autores'];
            
            foreach($autores as $a){
                $corpo = [
                    'header'=> 'Abertura de Licitação',
                    'body'=> 'A licitação de número '. $l['licitacao'] .' será aberta as propostas hoje.',
                    'link'=> 'licitacoes/'.$l['link'] 
                ];
                
                $notificacao = new Notificacao($a);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                if(isset($resposta['erro'])){
                    $respostas[] = ['mensagem'=> 'deu erro para '.$a . ' na licitacao '. $l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
                }
                
                $respostas[] = ['mensagem'=> 'mandou para '.$a. ' na licitacao '.$l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
            }
            
            
        }
        
        return $respostas;
    }
    
    function mandarMensagemFechada($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem licitações que serão fechadas amanhã'];
        }
        
        
        foreach($lista as $l){
            if(count($l['autores']) == 0){
                continue;
            }
            
            
            $autores = $l['autores'];
            
            foreach($autores as $a){
                $corpo = [
                    'header'=> 'Prazo de Licitação Próximo',
                    'body'=> 'A licitação de número '. $l['licitacao'] .' será fechada as propostas amanhã.',
                    'link'=> 'licitacoes/'.$l['link'] 
                ];
                
                $notificacao = new Notificacao($a);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                if(isset($resposta['erro'])){
                    $respostas[] = ['mensagem'=> 'deu erro para '.$a . ' na licitacao '. $l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
                }
                
                $respostas[] = ['mensagem'=> 'mandou para '.$a. ' na licitacao '.$l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
            }
            
            
        }
        
        return $respostas;
    }
    
    function pegarAutores($usuarios){
        if(empty($usuarios)){
            return [];
        }
        
        
        $ids = implode("','", $usuarios);
        
        $sql = "SELECT usuario_user ,usuario_id FROM usuarios WHERE usuario_user IN ('$ids')";
        
        $resultado = $this->conn->query($sql);
        $userid = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $userid[$dado['usuario_user']] = $dado['usuario_id'];
            }
        }
        
        return $userid;
    }
    
    function tarefas_vencer(){
        $data = new DateTime(); 
        $data->modify('+1 day');
        $dataAtual = $data->format('Y-m-d');
        
        $data->modify('+4 day');
        
        $dataCincoDias = $data->format('Y-m-d');
        
        $sql = "SELECT licitacoes_tarefa_nome, licitacoes_tarefa_usuario,licitacoes_tarefa_usuario_display, licitacoes_tarefa_autor FROM licitacoes_tarefas WHERE licitacoes_tarefa_andamento = '0' AND licitacoes_tarefa_prazo  BETWEEN '{$dataAtual}' AND '{$dataCincoDias}'";

        $resultado = $this->conn->query($sql);
        $usuarios = [];
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!empty($dado['licitacoes_tarefa_usuario_display']) && !in_array($dado['licitacoes_tarefa_usuario_display'], $usuarios)){
                    $usuarios[] = $dado['licitacoes_tarefa_usuario_display'];
                }
                
                $lista[] = [
                    'tarefa'=> $dado['licitacoes_tarefa_nome'],
                    'outro_usuario'=> $dado['licitacoes_tarefa_usuario'],
                    'autores'=> [$dado['licitacoes_tarefa_autor']],
                    'display'=> $dado['licitacoes_tarefa_usuario_display'],
                ];
            }
            
            
            $usuariosid = $this->pegarAutores($usuarios);
            
            
            foreach($lista as &$l){
                if(intval($l['outro_usuario']) && !empty($l['display'])){
                    if(!empty($usuariosid[$l['display']])){
                        if(!in_array($usuariosid[$l['display']], $l['autores'])){
                            $l['autores'][] = $usuariosid[$l['display']];
                        }
                    }
                }
                
            }
            
            return $this->mandarMensagemTarefaVencer($lista);

        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma tarefa no banco está preste a extourar o prazo'];
        }
            
    } 
    
    
    function mandarMensagemTarefaAtrasada($lista){
       if(empty($lista)){
            return['erro'=> true, 'mensagem'=> 'A lista de tarefas atrasadas foi passada sem as tarefas'];
        }
        
        
        foreach($lista as $l){
            if(count($l['autores']) == 0){
                continue;
            }
            
            
            $autores = $l['autores'];
            
            foreach($autores as $a){
                $corpo = [
                    'header'=> 'Você tem novas oportunidades',
                    'body'=> 'A tarefa '. $l['tarefa'] . ' está atrasada. Revise sua agenda e conclua o mais rápido possível',
                ];
                
                $notificacao = new Notificacao($a);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                if(isset($resposta['erro'])){
                    $respostas[] = ['mensagem'=> 'deu erro para '.$a . ' a tarefa '. $l['tarefa']];
                }
                
                $respostas[] = ['mensagem'=> 'mandou para '.$a . ' a tarefa '. $l['tarefa']];
            }
            
            
        }
        
        return $respostas;
    }
    
    function licitacoes_vencer(){
        $data = new DateTime(); 
        $data->modify('+1 day');
        $dataAtual = $data->format('Y-m-d');
        
        $data->modify('+4 day');
        
        $dataCincoDias = $data->format('Y-m-d');
        
        
        $sql = "SELECT lm_licitacao FROM licitacoes_meta WHERE lm_chave = 'dataEncerramentoPropostaPncp' AND lm_valor  BETWEEN '{$dataAtual}' AND '{$dataCincoDias}'";

        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $lista = [];
            
            while($dado = $resultado->fetch_assoc()){
                $lista[] = $dado['lm_licitacao'];
            }
            
            $ids = implode("','", $lista);
            
            $sql = "SELECT licitacoes_pesquisa_pesquisa, licitacoes_pesquisa_url, licitacoes_pesquisa_autor, licitacoes_pesquisa_visualizadores  FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_numero IN ('{$ids}')";
            
            $resultado = $this->conn->query($sql);
            
            if($resultado->num_rows > 0){
                $licitacoes = [];
                while($dado = $resultado->fetch_assoc()){
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
                    
                    $licitacoes[] = [
                        'autores'=> $autores,
                        'licitacao'=> $dado['licitacoes_pesquisa_pesquisa'],
                        'link'=> $dado['licitacoes_pesquisa_url'],
                    ];
                }
                
                return $this->mandarMensagemVencer($licitacoes);
            }else{
                return ['erro'=> true, 'mensagem'=> 'Nenhuma licitação foi vinculada com fechamento nos proximos dias'];
            }
        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma tarefa no banco está para fechar prospostas'];
        }
            
    } 
    
    function mandarMensagemVencer($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem licitações que serão fechada nos próximos 5 dias'];
        }
        
        
        foreach($lista as $l){
            if(count($l['autores']) == 0){
                continue;
            }
            
            
            $autores = $l['autores'];
            
            foreach($autores as $a){
                $corpo = [
                    'header'=> 'Prazo de Licitação Próximo',
                    'body'=> 'A licitação de número '. $l['licitacao'] .' terá as propostas fechada nos próximos 5 dias.',
                    'link'=> 'licitacoes/'.$l['link'] 
                ];
                
                $notificacao = new Notificacao($a);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                if(isset($resposta['erro'])){
                    $respostas[] = ['mensagem'=> 'deu erro para '.$a . ' na licitacao '. $l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
                }
                
                $respostas[] = ['mensagem'=> 'mandou para '.$a. ' na licitacao '.$l['licitacao'] . ' com link em licitacoes/'.$l['licitacao']];
            }
            
            
        }
        
        return $respostas;
    }
    
    function tarefa_atraso(){
        $data = new DateTime(); 
        $dataFormatada = $data->format('Y-m-d');
        
        $sql = "SELECT licitacoes_tarefa_nome, licitacoes_tarefa_usuario,licitacoes_tarefa_usuario_display, licitacoes_tarefa_autor FROM licitacoes_tarefas WHERE licitacoes_tarefa_andamento = '0' AND licitacoes_tarefa_prazo < '{$dataFormatada}'";
        
        $resultado = $this->conn->query($sql);
        $usuarios = [];
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!empty($dado['licitacoes_tarefa_usuario_display']) && !in_array($dado['licitacoes_tarefa_usuario_display'], $usuarios)){
                    $usuarios[] = $dado['licitacoes_tarefa_usuario_display'];
                }
                
                $lista[] = [
                    'tarefa'=> $dado['licitacoes_tarefa_nome'],
                    'outro_usuario'=> $dado['licitacoes_tarefa_usuario'],
                    'autores'=> [$dado['licitacoes_tarefa_autor']],
                    'display'=> $dado['licitacoes_tarefa_usuario_display'],
                ];
            }
            
            
            $usuariosid = $this->pegarAutores($usuarios);
            
            
            foreach($lista as &$l){
                if(intval($l['outro_usuario']) && !empty($l['display'])){
                    if(!empty($usuariosid[$l['display']])){
                        if(!in_array($usuariosid[$l['display']], $l['autores'])){
                            $l['autores'][] = $usuariosid[$l['display']];
                        }
                    }
                }
                
            }
            
            return $this->mandarMensagemTarefaAtrasada($lista);

        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma tarefa no banco está atrasada'];
        }
    }
    
    function mandarMensagemTarefaVencer($lista){
         if(empty($lista)){
            return['erro'=> true, 'mensagem'=> 'A lista de tarefas que estão preste a vencer foi passada sem tarefas'];
        }
        
        
        
        foreach($lista as $l){
            if(count($l['autores']) == 0){
                continue;
            }
            
            
            $autores = $l['autores'];
            
            foreach($autores as $a){
                $corpo = [
                    'header'=> 'Tarefa Próxima do Vencimento',
                    'body'=> 'Atenção! A tarefa '. $l['tarefa'] . ' vence nos próximos 5 dias. Conclua antes do prazo!',
                ];
                
                $notificacao = new Notificacao($a);
                $notificacao->mensagem($corpo);
                $resposta = $notificacao->all();
                
                if(isset($resposta['erro'])){
                    $respostas[] = ['mensagem'=> 'deu erro para '.$a . ' a tarefa '. $l['tarefa']];
                }
                
                $respostas[] = ['mensagem'=> 'mandou para '.$a . ' a tarefa '. $l['tarefa']];
            }
            
            
        }
        
        return $respostas;
    }
    
    function documentos_atrasado(){
        $data = new DateTime(); 
        $dataFormatada = $data->format('Y-m-d');
        
        $sql = "SELECT licitacoes_habilitacao_nome, licitacoes_habilitacao_autor FROM licitacoes_habilitacoes WHERE licitacoes_habilitacao_data_validade < '{$dataFormatada}'";

        $resultado = $this->conn->query($sql);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[] = [
                    'documento'=> $dado['licitacoes_habilitacao_nome'],
                    'autor'=> $dado['licitacoes_habilitacao_autor'],
                ];
            }
            
            
            return $this->mandarMensagemDocumentoAtrasados($lista);

        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhum documento no banco está expirado'];
        }
    }
    
    function mandarMensagemDocumentoAtrasados($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem documentos com validade expirada'];
        }
        
        
        foreach($lista as $l){
            if(empty($l['autor'])){
                continue;
            }
            
            
            $corpo = [
                'header'=> 'Documento Expirado',
                'body'=> 'O documento '. $l['documento'] .' expirou. Envie uma nova versão para continuar participando das licitações.',
                'link'=> 'licitacoes/documentos' 
            ];
            
            $notificacao = new Notificacao($l['autor']);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            if(isset($resposta['erro'])){
                $respostas[] = ['mensagem'=> 'deu erro para '.$l['autor'] . ' no documento '. $l['documento']];
            }
            
            $respostas[] = ['mensagem'=> 'mandou para '.$l['autor'] . ' no documento '. $l['documento']];
            
            
            
        }
        
        return $respostas;
    }
    
    function documentos_vencer(){
        $data = new DateTime(); 
        $data->modify('+1 day');
        $dataAtual = $data->format('Y-m-d');
        
        $data->modify('+4 day');
        
        $dataCincoDias = $data->format('Y-m-d');
        
        $sql = "SELECT licitacoes_habilitacao_nome, licitacoes_habilitacao_autor FROM licitacoes_habilitacoes WHERE licitacoes_habilitacao_data_validade  BETWEEN '{$dataAtual}' AND '{$dataCincoDias}'";

        $resultado = $this->conn->query($sql);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[] = [
                    'documento'=> $dado['licitacoes_habilitacao_nome'],
                    'autor'=> $dado['licitacoes_habilitacao_autor'],
                ];
            }
            
            
            return $this->mandarMensagemDocumentoVencer($lista);

        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhuma documento no banco está preste a extourar o prazo'];
        }
    }
    
    function mandarMensagemDocumentoVencer($lista){
        if(empty($lista)){
            return ['erro'=> true, 'mensagem'=> 'A lista passada está sem documentos que irão vencer nos próximos 5 dias'];
        }
        
        
        foreach($lista as $l){
            if(empty($l['autor'])){
                continue;
            }
            
            
            $corpo = [
                'header'=> 'Documento Prestes a Expirar',
                'body'=> 'O documento '. $l['documento'] .'  expira nos próximos 5 dias. Atualize para evitar problemas futuros',
                'link'=> 'licitacoes/documentos' 
            ];
            
            $notificacao = new Notificacao($l['autor']);
            $notificacao->mensagem($corpo);
            $resposta = $notificacao->all();
            
            if(isset($resposta['erro'])){
                $respostas[] = ['mensagem'=> 'deu erro para '.$l['autor'] . ' no documento '. $l['documento']];
            }
            
            $respostas[] = ['mensagem'=> 'mandou para '.$l['autor'] . ' no documento '. $l['documento']];
            
            
            
        }
        
        return $respostas;
    }
    
    function init(){
        if(empty($_GET['acao'])){
            return ['erro'=> true, 'mensagem'=> 'Ação não foi passada'];
        }
        
        switch($_GET['acao']){
            case 'oportunidades':
                return $this->oportunidades();
                break;
            case 'licitacoes_abertas':
                return $this->abertas();
                break;
            case 'licitacoes_fechadas':
                return $this->fechadas();
                break;
            case 'licitacoes_vencer':
                return $this->licitacoes_vencer();
                break;
            case 'tarefas_atrasada':
                return $this->tarefa_atraso();
                break;
            case 'tarefas_vencer':
                return $this->tarefas_vencer();
                break;
            case 'documentos_atrasado':
                return $this->documentos_atrasado();
                break;
            case 'documentos_vencer':
                return $this->documentos_vencer();
                break;
            default:
                return ['erro'=> true, 'mensagem'=> 'Ação não foi encontrada'];
                break;
        }
    }
}

$acao = new NotificacaoLicitacoes();

$resposta = $acao->init();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);