<?php
// SOLUÇÃO 1: SUBSTITUIR SEU CÓDIGO ATUAL POR ESTE
$empresa = $_GET["empresa"] ?? '';

if (empty($empresa)) {
    die("Erro: Parâmetro 'empresa' é obrigatório.");
}

// URL sem o fragmento #google_vignette
$url = "https://casadosdados.com.br/solucao/cnpj/" . urlencode($empresa);

// Inicializar cURL
$ch = curl_init();

// Configurações do cURL
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    
    // User-Agent de navegador real
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    
    // Headers que um navegador real enviaria
    CURLOPT_HTTPHEADER => [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language: pt-BR,pt;q=0.9,en;q=0.8',
        'Accept-Encoding: gzip, deflate, br',
        'Cache-Control: no-cache',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: none',
        'Sec-Fetch-User: ?1'
    ],
    
    // Decodificação automática de conteúdo comprimido
    CURLOPT_ENCODING => ''
]);

// Executar requisição
$content = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

// Fechar cURL
curl_close($ch);

// Verificar resultado
if ($content === false) {
    echo "Erro cURL: " . $error;
} elseif ($httpCode === 403) {
    echo "Erro 403: Acesso negado. Site bloqueou a requisição.";
} elseif ($httpCode === 404) {
    echo "Erro 404: Empresa não encontrada.";
} elseif ($httpCode !== 200) {
    echo "Erro HTTP: " . $httpCode;
} else {
    echo $content;
}
?>