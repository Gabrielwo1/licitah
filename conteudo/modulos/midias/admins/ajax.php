<?
session_start();
class Acao{
    function __construct(){
        $this->conn = conn();
    }
    
    
    function lista(){
        $conn = $this->conn;
        $seleciona = "SELECT * FROM uploads ORDER BY upload_id ASC";
        $resultado = $conn->query($seleciona);
        
        $array = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "id"=>$dado["upload_id"],
                    "nome"=>$dado["upload_nome"],
                    "tipo"=>$dado["upload_tipo"],
                    "data"=>$dado["upload_data"],
                    "caminho"=>$dado["upload_url"],
                    "formato"=>$dado["upload_formato"],
                    "tamanho"=>$dado["upload_tamanho"],	
                    "autor"=>$dado["upload_autor"]
                ];
                array_push($array, $item);
            }
        }
        
        return ["sucesso"=>true, "lista"=>$array];
    }
    
    function render(){
        return $this->lista();
    }
}


$resposta = [];


if(isset($_SESSION["id"])){
    $id = $_SESSION["id"];
    $resposta["acesso"] = true;
    
    include __DIR__."/../../../../admin/conn.php";
    
    $acao = new Acao();
    $resposta = $acao->render();
    
    
}else{
    $resposta["acesso"] = false;
}


echo json_encode($resposta);
?>