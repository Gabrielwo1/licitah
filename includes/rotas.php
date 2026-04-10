<?
include __DIR__."/../admin/seguranca.php";
$seguranca = new Seguranca(true);
$seguranca->onlyDocument();
$seguranca->setLimite(20, 60);


if(file_exists(__DIR__."/conteudo/blacklist.json")){
    $listanegra = file_get_contents(__DIR__."/../conteudo/blacklist.json");
    
    
    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ?  $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
    
    if($listanegra){
        $listanegra = json_decode($listanegra);
        
        
        
         $ip_na_lista_negra = false;
        foreach ($listanegra as $ip_lista) {
            if ($ip_lista === $ip) {
                $ip_na_lista_negra = true;
                break;
            }
            
            if(count(explode("*", $ip_lista)) > 1){
                if (strpos($ip, explode("*", $ip_lista)[0]) === 0) {
                    $ip_na_lista_negra = true;
                    break;
                }
                
            }
        }
        
        
        if($ip_na_lista_negra){
            include 'includes/uteis/bloqueado.php';
            die();
        }

        
    }
    
}

$dir = __DIR__;



if(file_exists(__DIR__."/../conteudo/config.php")){
    include_once __DIR__."/../conteudo/config.php";
    if(defined("SERVIDOR") && defined("USUARIO") && defined("SENHA") && defined("BANCO") && defined("CHAVE")){

        include __DIR__."/sistema/base.php";
    }else{
        include __DIR__.'/sistema/install.php';
    }
  
}else{
    echo include __DIR__.'/sistema/reinstall.php';
}
?>