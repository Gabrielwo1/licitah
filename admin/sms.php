<?php
$url = 'https://api.twilio.com/2010-04-01/Accounts/ACd4161a8d4053a6136171248420132682/Messages.json';

$token = "da323ecf78ec14cf48e9997e5c8d80c6";
// Os dados que você deseja enviar via POST
$data = array(
    'To' => '+5535999242962',
    'From' => '+12513579342',
    "Body"=>"Teste"
);

// Configura a autenticação básica
$username = 'ACd4161a8d4053a6136171248420132682';
$password = $token;

// Inicializa cURL
$ch = curl_init($url);

// Configura opções do cURL
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a solicitação POST
$response = curl_exec($ch);

// Fecha o cURL
curl_close($ch);

// Exibe a resposta
echo $response;
?>
