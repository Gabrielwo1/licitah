<?

session_start();
include __DIR__."/conn.php";
include __DIR__."/sessao.php";
    
    
class Auth{
    public $user;
    public $acao;
    function __construct($acao = false ,$user = false){
        $this->user = $user;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function update(){
        $user = $_SESSION["id"] ?? false;
        if(!$user){
            return ["erro"=>true, "mensagem"=>"Usuário não definido"];
        }

        $conn = conn();
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$user'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return ["sucesso"=>true, "sessao"=>criasessao($dado), "status"=>$dado["usuario_ativo"]];
          }
        
    }
    
    
    function autoriza(){
        $user = $_POST["user"] ?? false;
        $status = $_POST["status"] ?? 0;
        

        
        if(!$user){
            return ["erro"=>true, "mensagem"=>"Usuário não definido"];
        }
        
        $update = "UPDATE usuarios SET usuario_ativo='$status' WHERE usuario_id='$user'";
        $conn = conn();
        $conn->query($update);
        
        include __DIR__."/sockets/websocket.php";
        $pusher = websocket();
        
        $pusher->trigger("usuario-".$user, 'userAprove', true);
        return ["sucesso"=>true];
    }
    
    function render(){
        switch($this->acao){
            case 'autoriza':
                return $this->autoriza();
                break;
            case 'updateSession':
                return $this->update();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A açaão definida não é válida"];
                break;
        }
    }
}

$acao = new Auth();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
