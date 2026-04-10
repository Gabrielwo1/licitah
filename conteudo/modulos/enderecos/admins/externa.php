<?php
// header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$cep = $_POST['cep'] ?? $_GET['cep'];
$response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
echo $response;
?>
