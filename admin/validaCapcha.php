<?
function validaCapcha($recaptchaResponse){
     // Chave secreta do seu site obtida durante o registro
    $secretKey = v(["seguranca", "recapcha", "chave_secreta"], false);

    // Configuração para a verificação
    $verificationURL = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
    ];

    // Inicia a solicitação cURL
    $curl = curl_init($verificationURL);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Executa a solicitação e obtém a resposta
    $response = curl_exec($curl);

    // Fecha a conexão cURL
    curl_close($curl);

    // Decodifica a resposta JSON
    $responseData = json_decode($response, true);
        
    $seguranca = [];    
    // Verifica se a resposta é válida
    if ($responseData['success']) {
        $seguranca["seguro"] = true;
        $_SESSION["secure"] = true;
    } else {
       $seguranca["seguro"] = false;
       
        $filePath = __DIR__."/../conteudo/blacklist.json";
        if(!file_exist($filePath)){
             $content = json_encode([]); 
             file_put_contents($filePath, $content);
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $conteudo = file_get_contents($filePath);
        $array = json_decode($conteudo, true);
        if (!in_array($ip , $array)){
            array_push($array, $ip);
            $newContent = json_encode($array);
            file_put_contents($filePath, $newContent);
        }
    }
    return $seguranca;
}

?>