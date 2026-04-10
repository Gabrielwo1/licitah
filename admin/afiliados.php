<?

function referencia($status){
    
    $conn = conn();
    $id = isset($_SESSION["id"]) ? $_SESSION["id"] : 0;
    $referencia = isset($_POST["referencia"]) ? $_POST["referencia"] : 0;
    $usuario =  isset($_POST["usuario"]) ? $_POST["usuario"] : 0;
    $link = isset($_POST["p"]) ? $_POST["p"] : 0;
    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ?  $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
    
    $_SESSION["referencia"] = $usuario;
    

    $cadastra = "INSERT INTO navegacao 
    (navegacao_hash,navegacao_sessao,navegacao_referencia,navegacao_url, navegacao_ip, navegacao_status) VALUES  
    ('$usuario', '$id', '$referencia', '$link', '$ip', '$status')";
    
    $conn->query($cadastra);
    
}

function afilia($id) {
    $conn = conn();
    $hashe = false;

    $id = mysqli_real_escape_string($conn, $id);

    $seleciona = "SELECT * FROM navegacao WHERE navegacao_sessao='$id'";
    $resultado = $conn->query($seleciona);

    if ($resultado === false) {
       //echo "Erro na consulta: " . $conn->error;
        return;
    }

    if ($resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $hashe = $dado["navegacao_hash"];
        }
    } else {
      //echo "Nenhum resultado encontrado para a consulta: $seleciona";
        return;
    }

    $recente = true;
    $usuario = false;

    if ($hashe) {
        if ($recente) {
            $seleciona = "SELECT * FROM navegacao WHERE navegacao_referencia <> '0' AND navegacao_hash='$hashe' ORDER BY navegacao_id DESC";
        } else {
            $seleciona = "SELECT * FROM navegacao WHERE navegacao_referencia <> '0' AND navegacao_hash='$hashe' ORDER BY navegacao_id ASC";
        }

        $resultado = $conn->query($seleciona);

        if ($resultado === false) {
           // echo "Erro na consulta: " . $conn->error;
            return;
        }

        if ($resultado->num_rows > 0) {
           //  echo "Tem afiliação";
            while ($dado = $resultado->fetch_assoc()) {
                $ref = $dado["navegacao_referencia"];

                if ($usuario) {
                    $seleciona = "SELECT * FROM usuarios WHERE usuario_hash='$ref'";
                } else {
                    $seleciona = "SELECT * FROM empresas WHERE empresa_hash='$ref'";
                }

                $resultado = $conn->query($seleciona);
                if ($resultado === false) {
                  // echo "Erro na consulta: " . $conn->error;
                    return;
                }

                if ($resultado->num_rows == 1) {
                    $dado = $resultado->fetch_assoc();
                    $idAfiliado = $usuario ? $dado["usuario_id"] : $dado["empresa_id"];
                    $chave = $usuario ? "afiliado" : "empresa";

                    $cadastra = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$id', '$chave', '$idAfiliado')";
                    if ($conn->query($cadastra) === true) {
                      // echo "Cadastro com sucesso";
                    } else {
                      // echo "Erro ao cadastrar: " . $conn->error;
                    }
                }
            }
        } else {
          // echo "Nenhum resultado encontrado para a consulta: $seleciona";
        }
    }
}
?>