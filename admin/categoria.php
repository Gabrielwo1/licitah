<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

include 'conn.php';


class Acao {
    public $acao;
    public $isCat;
    public $tipo;
    public $conn;
    public $usuario;

    function __construct() {
        $this->acao = $_POST["acao"] ?? false;
        $this->isCat = $_POST["categoria"] ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
        $this->conn = conn();
        $this->usuario = $_SESSION["id"] ?? false;

    }
    
    function listar(){
        $tipo = $this->tipo;
        $isCat = filter_var($this->isCat, FILTER_VALIDATE_BOOLEAN) ? "1" : "0";
         
        $lista = [];
        $selecionar = "SELECT * FROM  categorias WHERE categoria_tipo='$tipo' AND  categoria_iscat='$isCat'";
        $resultado = $this->conn->query($selecionar);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "text"=>$dado["categoria_nome"],
                    "id"=>$dado["categoria_id"]
                ];
                array_push($lista, $item);
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function stringParaURL($string) {


    $caracteres_especiais = array(
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n',
        'ß' => 'ss',
    );
    
       
    $url = strtolower(trim($string));
    $url = str_replace(' ', '-', $url);

    $url = strtr($url, $caracteres_especiais);


    $url = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $url);

 
    $url = preg_replace('/\s+/', '-', $url);

    $url = preg_replace('/-+/', '-', $url);
    $url = rtrim($url, '-');

    return $url;
} 
    
  function novo() {
    $texto = $_POST["texto"] ?? false;

    if (!$texto) {
        return ["erro" => true, "mensagem" => "O texto definido é inválido"];
    }

    $url = $this->stringParaURL($texto);

    $tipo = $this->tipo;
    $isCat = filter_var($this->isCat, FILTER_VALIDATE_BOOLEAN) ? "1" : "0";
    $autor = $this->usuario;

    $seleciona = "SELECT * FROM categorias WHERE categoria_nome='$texto' AND categoria_tipo='$tipo' AND categoria_iscat='$isCat'";
    $resultado = $this->conn->query($seleciona);

    if ($resultado->num_rows == 0) {
        $url = paraUrl($texto);
        $cadastra = "INSERT INTO categorias (categoria_nome, categoria_tipo, categoria_iscat, categoria_autor, categoria_url) VALUES ('$texto', '$tipo', '$isCat', '$autor', '$url')";

        if ($this->conn->query($cadastra) === true) {
            $id = $this->conn->insert_id;
            return ["sucesso" => true, "id" => $id, "texto" => $texto];
        } else {
            return ["erro" => true, "mensagem" => "Erro ao adicionar dado", "sql" => $this->conn->error];
        }
    }

    $dado = $resultado->fetch_assoc();
    return ["sucesso" => true, "id" => $dado["categoria_id"], "texto" => $texto];
}
    function render() {
        if(!$this->tipo){
            return ["erro" => true, "mensagem" => "Não foi enviado um tipo válido"];
        }
        
        if(!$this->usuario){
             return ["erro" => true, "mensagem" => "Ação exclusiva para usuários logados"];
        }
        
        
        
        switch ($this->acao) {
            case 'listar':
                return $this->listar();
                break;
            case 'novo':
                return $this->novo();
                break;
            default:
                return ["erro" => true, "mensagem" => "Ação definida inválida"];
                break;
        }
    }
}

// Instantiate the Acao class
$acao = new Acao();
$resposta = $acao->render();

echo json_encode($resposta);
?>
