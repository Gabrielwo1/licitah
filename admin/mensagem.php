<?

include __DIR__."/seguranca.php";
include __DIR__."/websocket.php";
include __DIR__."/conn.php";

$seguranca = new Seguranca(true);

$seguranca->csrf();

$seguranca->onlyLogados();




define('SECURE', true);



$modulo = $_POST["modulo"] ?? false;

if(!$modulo){
    $seguranca->mata(403, "Não foram enviados as informações bases necessarias");
}


if(!is_dir(__DIR__."/../conteudo/modulos/".$modulo) || !file_exists(__DIR__."/../conteudo/modulos/".$modulo."/admins/chat/api.php")){
    $seguranca->mata(403, "Não parametros bases envidos são inválidos");
}

define('ACESSO_PERMITIDO', true);
header('Content-Type: application/json; charset=utf-8');
include __DIR__."/../conteudo/modulos/".$modulo."/admins/chat/api.php";
?>