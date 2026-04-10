<?
class Log{
    public $user;
    public $conn;
    public $identificador;
    public $banco;
    public $acao;
    function __construct($identificador, $banco, $acao){
        $this->user = $_SESSION["id"] ?? false;
        $this->conn = conn();
        
        $this->identificador = $identificador;
        $this->banco = $banco;
        $this->acao = $acao;
        

       }
       
       
       function tabelaExiste() {
           $query = "SHOW TABLES LIKE 'logs'";
           $resultado = $this->conn->query($query);
           if($resultado->num_rows == 0){
               return false;
           }
           return true;
       }


       
       function sucesso(){
           
           if(!$this->tabelaExiste()){
               return;
           }
           
        if(!$this->banco || !$this->acao || !$this->identificador || !$this->user){
            
        }
        
        
        
        
        $banco = $this->banco;
        $acao = $this->acao;
        $id = $this->identificador;
        $user = $this->user;
        
        
        $acao = "INSERT INTO logs (log_banco,log_identificador,log_acao,log_autor, log_estado) VALUES ('$banco', '$id', '$acao' , '$user', '1')";
        $this->conn->query($acao);
    } 
     
    function erro(){
        
    }

}




?>