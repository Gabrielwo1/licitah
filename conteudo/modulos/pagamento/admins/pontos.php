<?
header('Content-Type: application/json; charset=utf-8');
session_start();

include __DIR__."/../../../../admin/conn.php";
include __DIR__."/pegaPedido.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Acao{
    public $acao;
    public $id;
    public $user;
    public $grupo;
    public $conn;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->id = $_POST["id"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->grupo = $_POST["grupo"] ?? false;
        $this->conn = conn();
    }
    
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"O usuário não está logado"];
        }
        
        
        switch($this->acao){
            case 'compras':
                break;
            case 'assinaturas':
                break;
            case 'pontos':

                switch($this->grupo){
                    case 'vendavel':
                        break;
                    case 'banco':
                        $comprados = new  Comprados();

                        return ["sucesso"=>true, "pontos"=>[
                            "disponiveis"=>$comprados->bancoParaPontos($this->id),
                            "comprados"=>$comprados->get("comprados"),
                            "feitos"=>$comprados->get("feitos")
                            ]];
                        break;
                    default:
                         return ["erro"=>true, "mensagem"=>"O grupo enviado não é válido"];
                        break;
                }
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>