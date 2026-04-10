<?
function shorcode($modulo = false, $pagina = false){
    $diretorio = __DIR__."/../../modulos/";
    if(is_dir($diretorio.$modulo) && file_exists($diretorio.$modulo."/public/".$pagina."/index.php")){
        include $diretorio.$modulo."/public/".$pagina."/index.php";
    }else{
        echo false;
    }
}

?>