<?

function obg($obrigatorios){
    
    
    $infos = json_decode($_POST["infos"], true);
    

    foreach($obrigatorios as $item){
        if(!isset($infos[$item]) || $infos[$item] == "") {
            echo $item;
            return false;
        }
    }
    
    return true;
}

function limpa($chaves){
    $dados = [];
    $infos = json_decode($_POST["infos"], true);
    foreach($chaves as $chave){
        if(isset($infos[$chave])){
            $dados[$chave] = $infos[$chave]; 
        }
        
    }
    return $dados;
}


?>