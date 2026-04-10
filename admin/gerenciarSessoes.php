<?

include 'conn.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Acao{
    public $conn;
    public $acao;
    public $user;
    function __construct(){
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    
    function listar(){
        $user = $this->user;
        $seleciona = "SELECT * FROM sessoes WHERE sessao_usuario='$user'";
        $resultado = $this->conn->query($seleciona);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "id"=>$dado["sessao_id"],	
                    "hash"=>$dado["sessao_hash"],	
                    "usuario"=>$dado["sessao_usuario"],	
                    "ip"=>$dado["sessao_ip"],	
                    "dispositivo"=>$dado["sessao_computador"],	
                    "navegador"=>$dado["sessao_navegador"],
                    "data"=>$dado["sessao_data"]
                    ];
                    array_push($lista, $item);
            }
        }
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function desconectar(){
        $id = $_POST["id"] ?? false;
        if(!$id){
            return ["erro"=>true, "mensagem"=>"O ID definido não é válido"];
        }
        $seleciona = "SELECT * FROM sessoes WHERE sessao_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não há sessões a serem desconectadas"];
        }
        
        $dado = $resultado->fetch_assoc();
        $usuario = $dado["sessao_usuario"];
        $hash = $dado["sessao_hash"];
        
        $deleta = "DELETE FROM sessoes WHERE sessao_id='$id'";
        if($this->conn->query($deleta) == true){
            
            include 'sockets/websocket.php';
            $pusher = websocket();
            $data['message'] =   $hash;
            $pusher->trigger("usuario-".$usuario , 'desconectado', $data);
            
            
            return ["sucesso"=>true, "mensagem"=>"Sessão encerrada com sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao pagar sessão"];
        }
    }
    
     function desconectarTudo(){
        $usuario = $this->user;
        $deleta = "DELETE FROM sessoes WHERE sessao_usuario='$usuario'";
        if($this->conn->query($deleta) == true){
            include 'sockets/websocket.php';
            $pusher = websocket();
            $data['message'] =   0;
            $pusher->trigger("usuario-".$usuario , 'desconectado', $data);
            return ["sucesso"=>true, "mensagem"=>"Sessão encerrada com sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao pagar sessão"];
        }
    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"As sessões só podem ser gerenciadas por usuários logados"];
        }
        switch($this->acao){
            case 'listar':
                return $this->listar();
                break;
            case 'desconectar':
                return $this->desconectar();
                break;
            case 'desconectarTudo':
                 return $this->desconectarTudo();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Nenhuma ação válida foi definida"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta);


?>