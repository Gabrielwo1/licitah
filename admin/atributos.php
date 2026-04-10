<?
/* Tipos de Atributo
Comentários
Like / Deslike
Reação
Avaliação
*/

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/conn.php";


session_start();
class Comentario{
    public $acao;
    public $usuario;
    public $comentario;
    public $pai;
    public $grupo;
    public $identificador;
    public $conn;
    function __construct($acao){
        $this->acao = $acao;
        $this->usuario = $_SESSION["id"] ?? false;
        $this->comentario = $_POST["comentario"] ?? false;
        $this->pai = $_POST["pai"] ?? 0;
        $this->grupo = $_POST["grupo"] ?? false;
        $this->identificador = $_POST["identificaor"] ?? false;
        $this->conn = conn();
        
        if($this->pai){
            $this->pai = intval($this->pai);
        }
        

    }
    
    function novo(){
         if(!$this->comentario){
            return ["erro"=>true, "mensagem"=>"Não foi definido um comentário válido"];
        }
        
        $acao = "INSERT INTO comentarios (comentario_autor, comentario_texto, comentario_pai, comentario_tipo, comentario_identificador) VALUES ('".$this->usuario."', '".$this->comentario."', '".$this->pai."', '".$this->grupo."', '".$this->identificador."')";
        if($this->conn->query($acao)){
            $id = $this->conn->insert_id;
            return ["sucesso"=>true, "mensagem"=>"Comentário adicionado com sucesso", "id"=>$id];
        }else{
            return ["erro"=>true,"mensagem"=>"Erro ao cadastrar no banco de dados", "sql"=>$this->conn->error];
        }

    }
    
    function pegaTamanho($id){
        $seleciona = "SELECT COUNT(*) AS quantidade_comentarios FROM comentarios WHERE comentario_pai = ".$id;
        $resultado = $this->conn->query($seleciona);
        
        if ($resultado) {
            $dado = $resultado->fetch_assoc();
            return $dado['quantidade_comentarios'];
        }else {
            return 0; 
        }
}

    
    function listar(){
        $lista = [];
        $seleciona = "SELECT * FROM comentarios WHERE comentario_tipo='".$this->grupo."' AND comentario_identificador='".$this->identificador."' AND comentario_pai=".$this->pai."";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "id"=>$dado["comentario_id"],
                    "autor"=>$dado["comentario_autor"],
                    "texto"=>json_decode($dado["comentario_texto"], true),
                    "data"=>$dado["comentario_data"],
                    "respostas"=>$this->pegaTamanho($dado["comentario_id"])
                    ];
                    array_push($lista, $item);
            }
        }
        return ["sucesso"=>true, "lista"=>$lista, "tamanho"=>$resultado->num_rows];
    }
    
    function render(){
        if(!$this->usuario){
            return ["erro"=>true, "mensagem"=>"Para essa ação é necessário estar logado"];
        }
        
      
        
        if(!$this->grupo && !$this->identificador){
            return ["erro"=>true, "mensagem"=>"Não foi definido um grupo e/ou identificador válidos"];
        }
        
        
        
        
        switch($this->acao){
            case 'listar':
                return $this->listar();
                break;
            case 'novo':
                return $this->novo();
                break;
            case 'editar':
                break;
            case 'deletar':
                break;
            case 'item':
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}

class Like{
    function __construct($acao){
        $this->acao = $acao;
    }
    
    function render(){
        
    }
}


class Reacao{
     function __construct(){
        
    }
    
    function render(){
        
    }
}

class Avaliacao{
      function __construct(){
        
    }
    
    function render(){
        
    }
}

class Acao{
    public $tipo;
    public $acao;
    function __construct(){
        $this->tipo = $_POST["tipo"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function render(){
        if(!$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foi definida uma ação válida"];
        }
        
        switch($this->tipo){
            case 'comentario':
                $atributo = new Comentario($this->acao);
                break;
            case 'like':
                $atributo = new Like($this->acao);
                break;
            case 'reacao':
                $atributo = new Reacao($this->acao);
                break;
            case 'avaliacao':
                $atributo = new Avaliacao($this->acao);
                break;
            default:
                return ["erro"=>true, "mensagem"=>"O tipo definido não é válido"];
                break;
        }
        
        return $atributo->render();
    }
}

$acao = new Acao();
$resposta = $acao->render();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE |  JSON_PRETTY_PRINT);





?>