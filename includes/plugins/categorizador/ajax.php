<?

class Acao{
    private $conn;
    function __construct(){
        $this->conn = conn();
    }
    
    function cadastra(){
        $tipo = $_POST["tipo"];
        $nome = $_POST["nome"];
        
        $conn = $this->conn;
        
        
        $seleciona = "SELECT * FROM categorias WHERE categoria_nome='$nome' &&	categoria_tipo='$tipo'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
            $cadastra = "INSERT INTO categorias (categoria_nome, categoria_tipo) VALUES ('$nome', '$tipo')";
            if($conn->query($cadastra) == true){
                $id = $conn->insert_id;
                 return ["sucesso"=>true, "id"=>$id, "nome"=>$nome];
            }else{
                 return ["erro"=>true, "mensagem"=>"Erro ao cadastrar categoria"];
            }
        }else{
            $dado = $resultado->fetch_assoc();
            return ["id"=>$dado["categoria_id"], "nome"=>$dado["categoria_nome"]];
        }
    }
    
    function lista(){
        $tipo = $_POST["tipo"];
        $itens = [];
        $conn = $this->conn;
        
        $seleciona = "SELECT * FROM categorias WHERE categoria_tipo='$tipo'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows >0){
            while($dado = $resultado->fetch_assoc()){
                $item = [];
                $item["nome"] = $dado["categoria_nome"];
                $item["id"] = $dado["categoria_id"];
                array_push($itens, $item);
            }
        }
        
        return $itens;
    }
    
    function retorno($acao){
        switch($acao){
            case 'cadastra':
                return $this->cadastra();
                break;
            case 'edita':
                break;
            case 'deleta':
                break;
            case 'lista':
                return $this->lista();
                break;
        }
    }
}
?>