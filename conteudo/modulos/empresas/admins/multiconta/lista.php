<?

function listaItens($conn = false, $form = false, $hash = false){
    if(!$conn){
        return ["erro"=>true, "mensagem"=>"Não foi enviada uma conexão válida com o banco de dados"];
    }
    
    $usuario = $_SESSION["id"];
    
    $seleciona = "SELECT ea_empresa as empresa FROM empresas_associacao WHERE ea_usuario='{$usuario}'";
    $res = $conn->query($seleciona);
    $ids = [];
    if($res->num_rows > 0){
    while($dado = $res->fetch_assoc()){
        $ids[] = $dado["empresa"];
    }
        
    }
    $lista = false;
    if(!empty($ids)){
    $ids = implode(",", $ids);
    if($hash){
        $seleciona = "SELECT empresa_nome as nome, empresa_hash as id FROM  empresas WHERE empresa_id IN ({$ids})";
    }else{
        $seleciona = "SELECT empresa_nome as nome, empresa_id as id FROM  empresas WHERE empresa_id IN ({$ids})";
    }
    
    $resultado = $conn->query($seleciona);
        if($resultado->num_rows > 0){
                $lista = [];

            while($dado = $resultado->fetch_assoc()){
                $lista[$dado["id"]] = $dado["nome"];
            }
        }
     
    }
    return ["sucesso"=>true, "lista"=>$lista];

}
?>