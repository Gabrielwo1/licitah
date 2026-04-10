<?
include __DIR__."/../../../../admin/conn.php";
session_start();

class Acao{
    public $modulo;
    public $url;
    public $acao;
    public $conn;
    public $identificador;
    public $urlindex;
    public $user;
    public $conta;
    function __construct(){
        $this->modulo = $_POST["modulo"] ?? false;
        $this->url = $_POST["url"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->conta = $_SESSION["sub"] ?? 0;
    }
    
    
    function parse(){
        $modulo = $this->modulo;
        $acao = "SHOW columns from $modulo";
        $resultado = $this->conn->query($acao);
        
        while($dado = $resultado->fetch_assoc()){
            if (substr($dado["Field"], -3) === "_id") {
                $this->identificador = $dado["Field"];
            }
           
           
           
           if(count(explode("_url", $dado["Field"])) == 2){
                $this->urlindex = $dado["Field"];
           }
           
           
        }

    }
    
    function identifica(){
        $modulo = $this->modulo;
        $identificador = $this->identificador;
        $index = $this->urlindex;
        $url = $this->url;
        $seleciona = "SELECT $identificador FROM $modulo WHERE $index='$url'";
        
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return $dado[$identificador];
    }
    
    function favoritar($id){
        $modulo = $this->modulo;
        $user = $this->user;

        $seleciona = "SELECT * FROM favoritos WHERE	favorito_modulo='$modulo' AND favorito_identificador='$id' AND favorito_autor='$user' AND favorito_conta = '{$this->conta}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $cadastro = "INSERT INTO favoritos (favorito_modulo, favorito_identificador, favorito_autor, favorito_conta) VALUES ('$modulo', '$id', '$user', '{$this->conta}')";
            
            $this->conn->query($cadastro );
        }
        
        return ["sucesso"=>true, "mensagem"=>"Item favoritado com sucesso"];
       
    }
    
    function desfavoritar($id){
        $modulo = $this->modulo;
        $user = $this->user;
        $deleta = "DELETE FROM favoritos WHERE	favorito_modulo='$modulo' AND favorito_identificador='$id' AND favorito_autor='$user' AND favorito_conta = '{$this->conta}'";
        $this->conn->query($deleta);
        return ["sucesso"=>true, "mensagem"=>"Item desfavoritado com sucesso"];
    }
    
    
    function render(){
        if(!$this->modulo || !$this->url || !$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros válidos"];
        }
        
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        $this->parse();
        
        if(!$this->identificador || !$this->urlindex){
            return ["erro"=>true, "mensagem"=>"Ação não permitida"];
        } 
        
        
        $id = $this->identifica();
        if(!$id){
            return ["erro"=>true, "mensagem"=>"O item enviado não foi encontrado"];
        }
        

        
        switch($this->acao){
            case 'favoritar':
                return $this->favoritar($id);
                break;
            case 'desfavoritar':
                return $this->desfavoritar($id);
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviado não é válida"];
                break;
        }
        
    }

}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>