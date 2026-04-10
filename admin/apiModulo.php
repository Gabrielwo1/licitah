<?

include __DIR__."/conn.php";


class Acao{
    public $conn;
    public $modulo;
    public $arquivo;
    public $chave;
    public $erro;
    public $acao;
    function __construct(){
        $this->conn = conn();
        $this->modulo = $_POST["modulo"] ?? false;
        $this->arquivo = $_POST["arquivo"] ?? false;
        $this->chave = $_POST["chave"] ?? false;
        $this->erro = $_POST["erro"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function configs($modulo){
        $seleciona = "SELECT * FROM configuracoes_modulos WHERE config_pasta='$modulo'";
        $resultado = $this->conn->query($seleciona);
        $configs = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($configs[$dado["config_arquivo"]])){
                    $configs[$dado["config_arquivo"]] = [];
            }
            
            $configs[$dado["config_arquivo"]][$dado["config_chave"]] = $dado["config_valor"];
          
        }
       
        }
         return $configs;
     
    }
    
    function infos(){
        $modulo = $this->modulo;
        $arquivo = $this->arquivo;
        $chave = $this->chave;
        
        $configs = $this->configs($modulo);
        
        $dado = $configs[$arquivo][$chave] ?? false;
        if($dado === "false"){
            $dado = false;
        }
        
        if($dado === "true"){
            $dado = true;
        }
        
        
        return ["sucesso"=>true, "dado"=>$dado];
       
    }
    
    function render(){
        if(!$this->modulo || !$this->arquivo || !$this->chave){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os parametros válidos"];
        }
        
        switch($this->acao){
            case 'infos':
                return $this->infos();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi enviada uma ação válida"];
                break;
        }
        
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT || JSON_UNESCAPED_UNICODE);
?>