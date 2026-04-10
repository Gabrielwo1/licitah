<?

function tipouser($id, $conn){
    if($id == "0"){
        return "equipe";
    }
    $seleciona = "SELECT funcao_tipo FROM  funcoes WHERE funcao_id='$id'";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return "clientes";
    }
    $dado = $resultado->fetch_assoc();
    return $dado["funcao_tipo"] == "1" ? "equipe" : "clientes";
}

function criasessao($row, $sessao = false){

    $conn = conn();
    $_SESSION["id"] = $row["usuario_id"];
    $_SESSION["nome"] = $row["usuario_display"];
    $_SESSION["email"] = $row["usuario_email"];
    $_SESSION["funcao"] = $row["usuario_funcao"];
    $_SESSION["tipouser"] = tipouser($row["usuario_funcao"], $conn);
    $_SESSION["ativo"] = intval($row["usuario_ativo"]);
    $_SESSION["user"] = $row["usuario_user"];
    $_SESSION["data"] = $row["usuario_data"];
    
    if($_POST["wildcard"] ?? false){ 
        $_SESSION["wildcard"] = $_POST["wildcard"]; 
    }
    
    $_SESSION['csrf_token'] = null;
    
    Seguranca::gerarCsrf();
    
    
    
    $mapa = [];
    $id = $row["usuario_id"];
    $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id'";
        $resultado = $conn->query($seleciona);
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $mapa[$dado["um_chave"]] = $dado["um_valor"];
            }
        }
        
        
     if($mapa["totp"] ?? false == true){
            $_SESSION["2fa"] = "totp";
     }
    
    
    
    if(v(["paginas","multi-contas","ativar"], false)){
        if(empty($_SESSION["sub"]) && empty($_SESSION["subtipo"])){
            $_SESSION["needsub"] = true;
        }
        
    }
    
    
    $img = false;
    $foto = $row["usuario_foto"] ??  false;
    if($foto){
        $foto = json_decode($row["usuario_foto"], true);
        if(!empty($foto)){
            $img = $foto[0];
             $_SESSION["foto"] = $img;
        }
    }
    
   

   

    
     $sessao_hash = substr(md5(uniqid(rand(), true)), 0, 20);
  
     if(!$sessao){
          $ip = $_SERVER['REMOTE_ADDR'];
          $computador = gethostbyaddr($ip);
          $navegador = $_SERVER['HTTP_USER_AGENT'];
          $data_login = date("Y-m-d H:i:s");
          $sql = "INSERT INTO sessoes (sessao_hash, sessao_usuario, sessao_ip, sessao_computador, sessao_navegador, sessao_data) VALUES ('$sessao_hash', '".$_SESSION["id"]."', '$ip', '$computador', '$navegador', '$data_login')";
          $conn->query($sql);
          $conn->close();
          $_SESSION["sessao_hash"] = $sessao_hash;
          return $sessao_hash;
     }
     $_SESSION["sessao_hash"] = $sessao;
     return $sessao;
     
 

}


?>