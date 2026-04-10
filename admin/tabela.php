<?
header('Content-Type: application/json; charset=utf-8');
session_start();

include __DIR__."/parser.php";
include __DIR__."/table.php";


$tabela = new Tabela();
$resposta = $tabela->render();
$tabela->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>