<?

include_once __DIR__ . "/../../../../admin/conn.php";
include_once __DIR__ . "/../../../../admin/notificacao.php";

class Acao {
    private $conn;
    private $dominio;
    function __construct() {
        $this->conn = conn(); 
        configModulo('notificacoes');
        $this->dominio = verModulo('configuracoes', 'dominio', '');
    }
    
     function hasher($tamanho) {
    return bin2hex(random_bytes(ceil($tamanho / 2)));
        
    }
    
    function replacePlaceholders($texto, $data) {

        return preg_replace_callback('/\{\{(\w+)\}\}/', function($match) use ($data) {
            $chave = $match[1]; 
            
            if (!empty($data[$chave])) {
                
                $isImagem = (strpos($chave, '_imagem') !== false);
                $isGaleria = (strpos($chave, '_galeria') !== false);
                
                if ($isImagem || $isGaleria) {
                    // Lógica especial para imagens/galerias
                    if (!empty($data[$chave])) {
                        return $this->processarImagemOuGaleria($data[$chave]); // Você define essa função
                    } else {
                        return ''; // Ou mantenha o placeholder original
                    }
                }
                
                return $data[$chave];
                
                
                
            } else {
                return $match[0]; 
            }
        }, $texto);
    }
    
    
    private function pegaEnviados($identificacao) {
    $usuarios = [];

    $query = "SELECT notificacoes_envio_usuario FROM notificacoes_envios WHERE notificacoes_envio_pre = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $identificacao);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado) {
        $usuarios = array_column($resultado->fetch_all(MYSQLI_ASSOC), 'notificacoes_envio_usuario');
    }

    return $usuarios;
}

    private function processarImagemOuGaleria($valor){

        if(is_string($valor) && json_decode($valor) !== null){
           $valor = json_decode($valor, true);
        }
        
        if(is_array($valor) || ($isJson && is_array(json_decode($valor, true)))){
            $valor = $valor;
        }
        
        $img = '';
        
        if(count($valor) > 0){
            $img = $valor[0];
        }
        
       
        
        $img = ltrim($img, '/');
        $img = rtrim($this->dominio, '/') . '/conteudo/uploads/' . $img;
        
        return '<img style="max-width:100%" src="'.$img.'">';
    }
    
    public function render(){
        $seleciona = "SELECT * FROM notificacoes_pre";
        $resultado = $this->conn->query($seleciona);
  
        if($resultado->num_rows > 0){
            
            
            $dado = $resultado->fetch_assoc();
            
           
            
            
            $identificacao = $dado["notificacao_pre_id"];
            
            $conteudo = json_decode($dado["notificacao_pre_conteudo"], true);
            $setups = json_decode($dado["notificacao_pre_setup"], true);
            
        
            
            
            foreach($setups as $chave=>$setup ){
                
              
                
            $usuarios = [];
            switch(intval($setup["quem"])){
                case 1:
                    // Autor
                    $autorKey = array_values(array_filter(array_keys($conteudo), function($key) {
                        return preg_match('/_autor$/', $key);
                    }));
                    $autorValor = !empty($autorKey) ? $conteudo[$autorKey[0]] : null;
                    $usuarios[] = $autorValor;
                    break;
                case 2:
                    // Usuários Especificos
                    $usuarios = json_decode($setup["usuarios"] ?? '[]');
                    break;
                case 3:
                    // Funções Especificas
                    $funcoes = json_decode($setup["funcao"] ?? '[]');
                    
                    if(!empty($funcoes)){
                        if (in_array(1, $funcoes)) {
                            array_push($funcoes, 0);
                        }
                        
                        
                        $lista = implode(",", $funcoes);
                        $seleciona = "SELECT usuario_id as id FROM usuarios WHERE usuario_funcao IN ({$lista})";
                        $resultado = $this->conn->query($seleciona);
                        $ids = $resultado->fetch_all(MYSQLI_ASSOC);
                        $usuarios = array_column($ids, 'id');
                    }
                    
                    break;
                case 4:
                    // Todo Mundo
                    $seleciona = "SELECT usuario_id as id FROM usuarios";
                    $resultado = $this->conn->query($seleciona);
                    $ids = $resultado->fetch_all(MYSQLI_ASSOC);
                    $usuarios = array_column($ids, 'id');
                    break;
            }
            
            
            $inApp = intval($setup["app"] ?? 1);
            $email = intval($setup["email"] ?? 1);
            $push = intval($setup["push"] ?? 1);
            $sms = intval($setup["sms"] ?? 1);
            $zap = intval($setup["whatsapp"] ?? 1);
            $controlavel = intval($setup["controlavel"] ?? 1);
            
            
            $remove = $this->pegaEnviados($identificacao);
            
            $usuarios = array_values(array_diff($usuarios , $remove));

          
            $users = [];
            if(!empty($usuarios)){
                $lista = implode(",", $usuarios);
                $seleciona = "SELECT usuario_id as user_id , usuario_display as user_display ,  usuario_user as user_user ,  usuario_email as user_email, usuario_telefone as user_telefone  FROM usuarios WHERE usuario_id IN ({$lista})";
                $resultado = $this->conn->query($seleciona);
                
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        
                        $titulo = trim($setup["titulo"]);
                        $corpo = trim($setup["corpo"]);
                        
                        foreach($conteudo as $chave=>$valor){
                            $dado[$chave] = $valor;
                        }
                        
                        
                      
                    
                        $corpo = $this->replacePlaceholders($corpo, $dado);
                        
                        $titulo = $this->replacePlaceholders($titulo , $dado);
                        
                        $usuario = $dado["user_id"];
                     
                        $notificacao = new Notificacao($usuario);
                        $notificacao->mensagem(["header"=>$titulo, "body"=>$corpo]);
                        
                        
                        
                        if($inApp){
                            $notificacao->inApp($usuario);
                        }
                        
                        
                        if($email){
                            $notificacao->email();
                        }
                        
                        if($push){
                            $notificacao->push();
                        }
                        
                        if($zap){
                            $notificacao->whatsApp();
                        }
                        
                        if($sms){
                            $notificacao->sms();
                        }
                        
                        
                        
                        
                        $url = $this->hasher(12);
                        $hash =$this->hasher(32);
                        
                        $cadastra = "INSERT INTO  notificacoes_envios 
                        (notificacoes_envio_pre,notificacoes_envio_usuario,notificacoes_envio_transacional,notificacoes_envio_estado,notificacoes_envio_email,notificacoes_envio_app,notificacoes_envio_whatsapp,notificacoes_envio_push,notificacoes_envio_sms,notificacoes_envio_hash,notificacoes_envio_url) VALUES 
                        ('{$identificacao}', '{$usuario}', '0', '0', '{$email}', '{$inApp}', '{$zap}', '{$push}', '{$sms}', '{$hash}', '{$url}' )";
                        $this->conn->query($cadastra);
                        
                 

                    }
                }

            }
            else{
               echo "nenhum usuário";
            }
            
            
            
            
           
            }
            
             $deleta = "DELETE FROM notificacoes_pre WHERE notificacao_pre_id='$identificacao'";
             $this->conn->query($deleta);
        }
        
        return ["sucesso"=>true, "mensagem"=>"Mensagens enviadas com sucesso"];
        
        
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
