<?
function encripta($data, $key) {
    // Defina o método de criptografia
    $method = 'aes-256-cbc';

    // Gere um IV (Initialization Vector)
    $ivLength = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($ivLength);

    // Criptografe os dados
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);

    // Combine o IV e os dados criptografados
    $result = base64_encode($iv . $encrypted);

    return $result;
}

function decripta($data, $key) {
    // Defina o método de criptografia
    $method = 'aes-256-cbc';

    // Descodifique os dados
    $data = base64_decode($data);

    // Extrair o IV e os dados criptografados
    $ivLength = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);

    // Descriptografe os dados
    $decrypted = openssl_decrypt($encrypted, $method, $key, 0, $iv);

    return $decrypted;
}


?>