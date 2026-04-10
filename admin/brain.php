<?
header('Content-Type: application/json; charset=utf-8');

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include __DIR__."/conn.php";
include __DIR__.'/installDb.php';
include __DIR__."/core.php";
include __DIR__."/log.php";

define('BRAIN', true);


$acao = new Acao();
$resposta = $acao->render();
$acao->close();

echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?> 