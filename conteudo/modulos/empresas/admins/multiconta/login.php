<?
function selecionaConta($conn , $id){
    $seleciona = "SELECT empresa_id FROM empresas where empresa_hash='$id' LIMIT 1";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return ["erro"=>true, "mensagem"=>"Não foi encontrada uma empresa com o ID selecionado"];
    }
    if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $_SESSION["needsub"] = false;
                    $_SESSION["sub"] = intval($dado["empresa_id"]);
                    $_SESSION["subtipo"] = "empresas";
                    return ["sucesso"=>true, "mensagem"=>"Sub Conta Selecionada com Sucesso"];
                }
                
                
   
}

?>