<?

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$resposta = [];

include 'conn.php';



class Acao{
    public $tipo;
    public $isItem;
    public $url;
    public $modulo;
    public $banco;
    public  $identificador;
    public $paginacao; 
    function __construct(){
        $this->tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : false;
        $this->isItem = isset($_POST["isItem"]) ? $_POST["isItem"] : false;
        $this->url = isset($_POST["url"]) ? $_POST["url"] : false;
        $this->modulo = isset($_POST["modulo"]) ? $_POST["modulo"] : false;
        $this->paginacao = isset($_POST["paginacao"]) ? $_POST["paginacao"] : 0;
    }
    
    function autor(){
        
    }
    
    function post(){
        
    }
    
    function populares(){
        
    }
    
    function semelhantes(){
        
    }
    
    function proximoAnterior(){
        
    }
    
    function urlParaId(){
        $conn = conn();
        if($this->identificador && $this->banco){
             $seleciona = "SELECT $this->identificador FROM $this->banco WHERE url_publica='$this->url'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                 return ["sucesso"=>true, "id"=>$dado[$this->identificador]];
            }else{
                 return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum item com a URL pública enviada: $this->url no banco $this->banco"];
            }
        }else{
            return ["erro"=>true, "mensagem"=>"O banco de dados e o identificador não foram definidos"];
        }
       
    }
    
    
    function lista(){
        $conn = conn();
        if($this->identificador && $this->banco){
            $lista = [];
            $seleciona = "SELECT $this->identificador FROM $this->banco ORDER BY $this->identificador DESC LIMIT $this->paginacao , 10";
            $resultado = $conn->query($seleciona);
            while($dado = $resultado->fetch_assoc()){
                array_push($lista, $dado[$this->identificador]);
            }
            
            return ["sucesso"=>true, "lista"=>$lista];

        }else{
             return ["erro"=>true, "mensagem"=>"O banco de dados e o identificador não foram definidos"];
        }
        
    }
    
    function render(){
        if(file_exists(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/publico.php")){
         
        $this->banco = false;  
        $this->identificador = false;
        
        include __DIR__."/../conteudo/modulos/".$this->modulo."/admins/publico.php";
        
        $this->banco = $banco;
        $this->identificador = $identificador;
        
        switch($this->tipo){
            case 'item':
                if($this->url){
                    $id = $this->urlParaId();
                    if(isset($id["erro"])){
                        return $id;
                    }else{
                       $id = $id["id"];
                       $item = new Item($id);
                       return ["sucesso"=>true, "resposta"=>$item->render()]; 
                    }
                }else{
                    return ["erro"=>true, "mensagem"=>"Envie uma URL como parametro"];
                }
                break;
            case 'autores':
                break;
            case 'categorias':
                break;
            case 'tags':
                break;
            case 'home':
                $lista = $this->lista();
                if(isset($lista["erro"])){
                    return $lista;
                }else{
                    $array = [];
                    foreach($lista["lista"] as $id){
                        $item = new Item($id);
                        array_push($array, $item->render());
                        
                    }
                    return ["sucesso"=>true, "resposta"=>$array];
                }
  
                break;
        }
        
       return ["sucesso"=>$this->tipo];
            

        }else{
            return ["erro"=>true, "mensagem"=>"Sem API pública definida"];
        }
    }
}

if(isset($_POST["modulo"]) && isset($_POST["tipo"])){
    $acao = new Acao();
    $resposta = $acao->render();
}else{
    $resposta = ["erro" => true, "mensagem"=>"Não foram enviados os parametros válidos"];
}


echo json_encode($resposta,  JSON_UNESCAPED_UNICODE);
?>