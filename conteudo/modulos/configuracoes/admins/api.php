<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__."/../../../../admin/conn.php";


class Acao{
    public $conn;
    public $acao;
    public $user;
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
    }
    
    function close(){
        $this->conn->close();
    }
    
    function headers(){
         if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Usuário deslogado"];
        }
        
        $lista = [];
        
        if(is_dir(__DIR__."/../../../headers")){
            $arquivos = scandir(__DIR__."/../../../headers");
            foreach($arquivos as $item){
                if($item != "." && $item != ".."){
                    array_push($lista, ["chave"=>explode(".", $item)[0] , "valor"=>$item]);
                }
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function getHeaders(){
    $header = filter_input(INPUT_POST, "header", FILTER_SANITIZE_STRING);
    $html = false;

    if ($header && strpos($header, '../') === false && file_exists(__DIR__."/../../../headers/".$header)) {
        ob_start();
        include(__DIR__."/../../../headers/".$header);
        $html = ob_get_clean();
        return ["sucesso" => true, "html" => $html];
    }

    return ["sucesso" => false, "html" => "Header não encontrado ou caminho inválido."];
}

    
    function render(){
       
        switch($this->acao){
            case 'headers':
                return $this->headers();
                break;
            case 'getHeaders':
                return $this->getHeaders();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>