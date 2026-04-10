<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=utf-8');

session_start();

if (!isset($_SESSION["id"])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado.']);
    exit;
}


//$allowedOrigin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

$serverName = $_SERVER['SERVER_NAME'];


if (!empty($allowedOrigin)) {
    $parsedUrl = parse_url($allowedOrigin);
    $originHost = $parsedUrl['host'] ?? '';

    if ($originHost == $serverName) {
        header("Access-Control-Allow-Origin: $allowedOrigin");
    } else {
        http_response_code(403); 
        echo json_encode(['error' => 'Acesso negado devido à política de mesmo domínio.']);
        exit;
    }
} else {
    //http_response_code(403);
    //echo json_encode(['error' => 'Acesso negado. Origem desconhecida.']);
    //exit;
}

?>