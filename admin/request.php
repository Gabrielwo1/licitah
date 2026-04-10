<?php
include_once __DIR__."/seguranca.php";
$seguranca = new Seguranca(true);
$seguranca-> cabecalhos();
//$seguranca->csrf();

define('SECURE', true);


$modulo = filter_input(INPUT_POST, "mod", FILTER_UNSAFE_RAW);
$caminho = filter_input(INPUT_POST, "cam", FILTER_UNSAFE_RAW);


if (!$modulo || !$caminho) {
    $seguraca->mata(403, "Não foram enviados as informações bases necessarias");
}


$caminho = basename($caminho);

if($modulo != "nown"){
    if(!is_dir(__DIR__."/../conteudo/modulos/".$modulo) || !file_exists(__DIR__."/../conteudo/modulos/".$modulo."/admins/$caminho.php")){
        $seguraca->mata(403, "Não parametros bases envidos são inválidos");
    }
    $arquivo = __DIR__ . "/../conteudo/modulos/{$modulo}/admins/{$caminho}.php";
}else{
    if(!file_exists(__DIR__."/".$caminho.".php")){
        $seguraca->mata(403, "Não parametros bases envidos são inválidos");
    }
    $arquivo = __DIR__."/".$caminho.".php";
}


define('ACESSO_PERMITIDO', true);
include $arquivo;
?>