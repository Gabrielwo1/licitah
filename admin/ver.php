<?
function v($array, $alt = ""){
    $caminho = SETUP["config"];
    
    foreach($array as $i => $item){
        if(isset($caminho[$item])){
            if($i == count($array) - 1){
                switch($caminho[$item]){
                    case "false":
                        if($caminho[$item] === "false"){
                            return false;
                        }
                        
                    case "true":
                        if($caminho[$item] === "true"){
                            return true;
                        }
                        

                    default:
                        return $caminho[$item] === "" ? $alt : $caminho[$item];
                }
            }
            $caminho = $caminho[$item];
        } else {
            return $alt;
        }
    }
    
    return $alt;
}

function ver($array){
        $caminho = SETUP["config"];
        
        $ultimo = count($array) - 1;
        $i = 0;
        foreach($array as $item){
            if(isset($caminho[$item])){
                if($ultimo == $i){
                    return $caminho[$item];
                }
                $caminho = $caminho[$item];
            }else{
                return 0;
            }
            $i++;
        }
        
        return 0;
    }
?>