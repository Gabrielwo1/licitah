<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__."/../../../../admin/conn.php";

class Acao{
    public $acao;
    public $conn;
    public $maps;
    public $user;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->maps = [];
        
    }
    
      function pegaNome($referencia, $id){

    if(!isset($this->maps[$referencia])){

        $checkTableQuery = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$referencia'";


        $tableExistsResult = $this->conn->query($checkTableQuery);

        if($tableExistsResult->num_rows > 0){

            $query = "SHOW COLUMNS FROM " . $referencia;

            $result = $this->conn->query($query);
            
            // Verifica se a consulta retornou resultados
            if($result->num_rows > 0){
                // Inicializa um array para armazenar os nomes das colunas
                $columns = array();
                
                // Itera sobre os resultados e armazena os nomes das colunas
                while($row = $result->fetch_assoc()){
                    $trato = explode("_", $row['Field']);
                    
                    
               
                    if(count($trato) > 1 && ($trato[1] == "nome" || $trato[1] == "titulo")){
                        $this->maps[$referencia] =  $row['Field'];
                        break;
                    }
                }
                
         
            }
        } else {
            return null;
        }
    }
    
    if(isset($this->maps[$referencia])){
        $chave = $this->maps[$referencia];
        $prefixo = explode("_", $chave)[0]."_id";
        $seleciona = "SELECT ".$chave." FROM ".$referencia." WHERE ".$prefixo." = '$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado[$chave]." (".$referencia.")";
        }else{
            return null;
        }
    }else{
        return null;
    }
    

}
    
    function listar(){
        $seleciona = "SELECT vendavel_id,vendavel_banco,vendavel_referencia FROM vendaveis";
        $resultado = $this->conn->query($seleciona);
        
        $lista = [];
        
        if($resultado->num_rows > 0){
             while($dado = $resultado->fetch_assoc()){
                 $nome = $this->pegaNome($dado["vendavel_banco"], $dado["vendavel_referencia"]);
                 if($nome){
                   $item = ["v"=>$dado["vendavel_id"], "t"=>$nome];
                   $lista[] = $item;  
                 }
                 
             }
        }
        return ["sucesso"=>true, "lista"=>$lista];
        
    }
    
    function saldo(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"O usuário não tem saldo suficiente"];
        }
        
        $user = $this->user;
        
        $seleciona = "SELECT * FROM pay_saldos WHERE pay_saldo_usuario='$user'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $saldo = 0;
        }else{
            $dado = $resultado->fetch_assoc();
            $saldo = $dado["pay_saldo_saldo"];
        }
        
        return ["sucesso"=>true, "saldo"=>$saldo];
    }
    
    function render(){
        switch($this->acao){
            case 'listar':
                return $this->listar();
                break;
            case 'saldo':
                return $this->saldo();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida é inválida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);




?>