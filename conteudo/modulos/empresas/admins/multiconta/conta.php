<?
function pegaConta($conn, $id){
      $seleciona = "SELECT * FROM empresas WHERE empresa_id='$id' LIMIT 1";
                $resultado = $conn->query($seleciona);
                
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    return [
                        "id"=>$dado["empresa_id"],
                        "nome"=>$dado["empresa_nome"],
                        "cnpj"=>$dado["empresa_cnpj"],
                        "imagem"=>$dado["empresa_imagem"]
                        ];
                }
                return false;
}


?>