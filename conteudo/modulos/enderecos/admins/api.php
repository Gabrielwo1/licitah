<?
header('Content-Type: application/json; charset=utf-8');
include __DIR__."/../../../../admin/conn.php";
class Acao{
    public $acao;
    public $conn;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
    }
    
    function paises(){
        $lista = [];
        $seleciona = "SELECT enderecos_pais_nome, enderecos_pais_id FROM enderecos_paises";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                 $name = $dado["enderecos_pais_nome"];
             $code = $dado["enderecos_pais_id"];
             $item = ["t" => $name, "v" => $code];
             if($name == "Brasil"){
                 $item["c"] = true;
             }
             
             $lista[] = $item;
                
                
                
            }
        }
      
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function estados(){
        $lista = [];
        
        
        
        $id = intval($_POST["valor"]);
        $seleciona = "SELECT endereco_estado_nome, endereco_estado_id  FROM enderecos_estados WHERE endereco_estado_pais='$id'";
        $resultado = $this->conn->query($seleciona);
            
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[] =["t" => $dado["endereco_estado_nome"], "v" => $dado["endereco_estado_id"]];
            }
        }

        return ["sucesso"=>true, "lista"=>$lista];
    }

    function cidades(){
        $lista = [];
        
        $estado = intval($_POST["valor"]);
        
        $seleciona = "SELECT * FROM enderecos_cidades WHERE endereco_cidade_estado='$estado'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[] = ["t" => $dado["endereco_cidade_nome"] , "v" => $dado["endereco_cidade_id"]];
            }
        }
      
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function render(){
        switch($this->acao){
            case 'paises':
                return $this->paises();
                break;
            case 'estados':
                return $this->estados();
                break;
            case 'cidades':
                return $this->cidades();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}

$api = new Acao();
$resposta = $api->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>