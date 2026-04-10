<?

function verModulo($grupo, $chave, $callback = false){
    
    if(!MODULO){
        return $callback;
    }
    
    if(!isset(MODULO[$grupo])){
        return $callback;
    }
    
    if(!isset(MODULO[$grupo][$chave])){
        return $callback;
    }
    
    if(MODULO[$grupo][$chave] == "" || !MODULO[$grupo][$chave] ){
        return $callback;
    }
    
    if(MODULO[$grupo][$chave] === "true"){
        return true;
    }
    
    if(MODULO[$grupo][$chave] === "false"){
        return false;
    }

    return MODULO[$grupo][$chave];
}

function configModulo($modulo){
    $conn = conn();
    $seleciona = "SELECT * FROM configuracoes_modulos WHERE config_pasta='$modulo'";
    $resultado = $conn->query($seleciona);
    $configs = [];
    if($resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            if(!isset($configs[$dado["config_arquivo"]])){
                $configs[$dado["config_arquivo"]] = [];
            }
            
            $configs[$dado["config_arquivo"]][$dado["config_chave"]] = $dado["config_valor"];
          
        }
    }
    define("MODULO", $configs);
}

?>