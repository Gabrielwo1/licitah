<?
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Seguranca{
    private $logAttempts = false;
    private $maxTentativas = 5;
    private $tempoBloqueioBrute = 900;
    private $limite = 30;
    private $janela = 60;
    
    private $sqlPatterns = [
        '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
        '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
        '/(\b(OR|AND)\s+[\'"]?\w+[\'"]?\s*=\s*[\'"]?\w+[\'"]?)/i',
        '/(\'|\")(\s*)(OR|AND)(\s*)(\'|\")/i',
        '/(\-\-|\#|\/\*|\*\/)/i',
        '/(\b(INFORMATION_SCHEMA|MYSQL|PERFORMANCE_SCHEMA)\b)/i',
        '/(BENCHMARK|SLEEP|DELAY|WAITFOR)/i',
        '/(\b(CHAR|ASCII|SUBSTRING|CONCAT|LENGTH)\s*\()/i',
        '/(\b(LOAD_FILE|INTO\s+OUTFILE|INTO\s+DUMPFILE)\b)/i',
        '/(0x[0-9a-f]+)/i'
    ];
    
    private $xssPatterns = [
        '/<script[^>]*>.*?<\/script>/is',
        '/<iframe[^>]*>.*?<\/iframe>/is',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/on\w+\s*=/i',
        '/<object[^>]*>.*?<\/object>/is',
        '/<embed[^>]*>.*?<\/embed>/is',
        '/<form[^>]*>.*?<\/form>/is',
        '/expression\s*\(/i',
        '/data\s*:\s*text\/html/i'
    ];
    
    function cabecalhos() {
    
     header('Content-Type: application/json; charset=utf-8');
    // Prevenção de MIME sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Proteção contra clickjacking (ajuste conforme necessidade)
    header('X-Frame-Options: DENY'); // ou SAMEORIGIN se precisar de iframes
    
    // ✅ SUBSTITUI X-XSS-Protection por CSP moderno
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
    
    // Força HTTPS (se usando SSL)
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    
    // Previne vazamento de referrer
    header('Referrer-Policy: strict-origin-when-cross-origin');
    

}

    function __construct($direto = false){

        if(!$direto){
            $this->bloquearDiretos();
        }
        
        $this->verificarHTTPS();
        $this->verificarUserAgent();
       $this->converteEntradas();
    
       //$_POST = $this->sanitiza($_POST);
     //$_GET = $this->sanitiza($_GET);
       //$_REQUEST = $this->sanitiza($_REQUEST);
    }
    
    private function converteEntradas(){
        $json = json_decode(file_get_contents("php://input"), true);

if (is_array($json)) {

    $_POST = array_merge($_POST, $json);
}
    }
    
    private function logarTentativaHack(){
        
    }
    
    private function isValidJson($string) {
    // Verifica se começa e termina com { } ou [ ]
    $string = trim($string);
    if (!((str_starts_with($string, '{') && str_ends_with($string, '}')) || 
          (str_starts_with($string, '[') && str_ends_with($string, ']')))) {
        return false;
    }
    
    $decoded = json_decode($string);
    return json_last_error() === JSON_ERROR_NONE &&  (is_object($decoded) || is_array($decoded));
        
    }

    public function onlyDocument(){
        if (($_SERVER['HTTP_SEC_FETCH_DEST'] ?? '') !== 'document' && ($_SERVER['HTTP_SEC_FETCH_MODE'] ?? '') !== 'navigate' && strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html') !== 0) {
            $this->mata(404);
        }

    }
    
    public function csrf() {
    $headers = getallheaders();
    $tokenRecebido = $headers['X-CSRF-Token'] ?? $_POST["csrf_token"] ?? '';

    $block = false;

    // Verifica se existe o token na sessão e se está no formato correto
    if (!isset($_SESSION['csrf_token']) || !is_array($_SESSION['csrf_token'])) {
        $block = true;
    } elseif (empty($_SESSION['csrf_token']['token'])) {
        $block = true;
    } elseif (!is_string($tokenRecebido) || 
              !hash_equals($_SESSION['csrf_token']['token'], $tokenRecebido)) {
        $block = true;
    }

    if ($block) {
        //$this->mata(403, 'Token de Segurança Inválido');
    }
    }
    
    public static function gerarCsrf(){
        if(!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])){
            $token = bin2hex(random_bytes(32));
            
            $_SESSION['csrf_token'] = [];
            $_SESSION['csrf_token']["token"] = $token;
            $usuario = null;
            
          if(isset($_SESSION["id"])){
             $usuario = [
              "id"=>$_SESSION["id"],
              "funcao"=>$_SESSION["funcao"],
              "ativo"=>$_SESSION["ativo"]
              ]; 
          }
          $_SESSION['csrf_token'][$token] = $usuario;
        }else{
            $token = $_SESSION['csrf_token']["token"];
        }
        return $token;
    }

    public function sanitiza($data, $options = []) {
        // Configurações padrão
        $config = array_merge([
            'trim' => true,
            'html_entities' => true,
            'strip_tags' => true,
            'remove_null_bytes' => true,
            'detect_sql_injection' => true,
            'detect_xss' => true,
            'max_length' => 10000,
            'allowed_tags' => '', // Tags HTML permitidas
            'encoding' => 'UTF-8'
        ], $options);
        
        // Se for array, processa recursivamente
        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $cleanKey = $this->sanitizaString($key, $config);
                $sanitized[$cleanKey] = $this->sanitiza($value, $config);
            }
            return $sanitized;
        }
        
        // Se não for string, retorna como está
        if (!is_string($data)) {
            return $data;
        }
        
        return $this->sanitizaString($data, $config);
    }
    
    private function sanitizaString($string, $config) {
        if (empty($string)) {
            return $string;
        }
        
    
    // Detecta se é JSON ANTES de aplicar qualquer sanitização
    if ($this->isJsonObject($string)) {
        // Para JSON, apenas valida estrutura e remove null bytes
        if ($config['remove_null_bytes']) {
            $string = str_replace(chr(0), '', $string);
        }
        
        // Valida se o JSON é seguro
        $this->validarJsonSeguro($string);
        
        return $string; // Retorna JSON intacto
    }
        
        // Remove null bytes
        if ($config['remove_null_bytes']) {
            $string = str_replace(chr(0), '', $string);
        }
        
        // Limita o tamanho
        if (strlen($string) > $config['max_length']) {
            $string = substr($string, 0, $config['max_length']);
        }
        
        // Trim
        if ($config['trim']) {
            $string = trim($string);
        }
        
        // Detecta tentativas de SQL injection
        if ($config['detect_sql_injection']) {
            $this->detectarSQLInjection($string);
        }
        
        // Detecta tentativas de XSS
        if ($config['detect_xss']) {
            $this->detectarXSS($string);
        }
        
        // Remove tags HTML (mantém apenas as permitidas)
        if ($config['strip_tags']) {
            $string = strip_tags($string, $config['allowed_tags']);
        }
        
        // Converte caracteres especiais em HTML entities
        if ($config['html_entities']) {
            $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, $config['encoding']);
        }
        
        return $string;
    }
    
    private function detectarSQLInjection($string) {
    // Ignora campos que sabemos ser JSON válidos
    if ($this->isValidJson($string)) {
        return;
    }
    
  
    foreach ($this->sqlPatterns as $pattern) {
        if (preg_match($pattern, $string)) {
    
            $this->logarTentativaHack('SQL_INJECTION', $string);
            $this->mata(400, "Tentativa de SQL injection detectada");
        }
    }
}
    
    private function detectarXSS($string) {
        foreach ($this->xssPatterns as $pattern) {
            if (preg_match($pattern, $string)) {
                $this->logarTentativaHack('XSS', $string);
                $this->mata(400, "Tentativa de XSS detectada");
            }
        }
    }
    
    public function setLimite($limite = 30, $janela = 60){
        $this->limite = $limite;
        $this->janela = $janela;
         $this->verificarRateLimiting();
    }
    
    private function getIpReal() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    private function getDeviceFingerprint() {
        $components = [
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '',
            $_SERVER['HTTP_ACCEPT'] ?? '',
            $_SERVER['HTTP_DNT'] ?? '',
            $_SERVER['HTTP_SEC_CH_UA'] ?? '',
            $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ?? '',
            $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] ?? '',
            // Fallback para IP em caso de proxies/VPNs
            $this->getIpReal()
        ];
        
        // Remove valores vazios e cria um hash único
        $fingerprint = implode('|', array_filter($components));
        return hash('sha256', $fingerprint);
    }
    
    private function getDeviceToken() {
        // Se já existe um token na sessão, usa ele
        if (isset($_SESSION['device_token'])) {
            return $_SESSION['device_token'];
        }
        
        // Gera um novo token baseado no fingerprint + timestamp
        $fingerprint = $this->getDeviceFingerprint();
        $token = hash('sha256', $fingerprint . microtime(true));
        
        // Salva na sessão para persistir durante a navegação
        $_SESSION['device_token'] = $token;
        
        return $token;
    }
    
    private function verificarRateLimiting() {
        
       
        
        $deviceToken = $this->getDeviceToken();
            

        $chave = "rate_limit_device_" . $deviceToken;
        $agora = time();
        $limite = $this->limite;
        $janela = $this->janela;
        
        // Inicializa controle para novo dispositivo
        if (!isset($_SESSION[$chave])) {
            $_SESSION[$chave] = [
                'count' => 1, 
                'time' => $agora, 
                'bloqueado_ate' => null,
                'device_info' => [
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                    'ip' => $this->getIpReal(),
                    'first_seen' => date('Y-m-d H:i:s')
                ]
            ];
            return;
        }
        
        $dados = $_SESSION[$chave];
        
    
        // Verifica se dispositivo ainda está bloqueado
        if ($dados['bloqueado_ate'] && $agora < $dados['bloqueado_ate']) {
            $segundos_restantes = $dados['bloqueado_ate'] - $agora;
            $this->mata(429, "Dispositivo bloqueado temporariamente", 
                "Tente novamente em {$segundos_restantes} segundos");
        }
        
        // Reset da janela de tempo
        if ($agora - $dados['time'] > $janela) {
            $_SESSION[$chave] = [
                'count' => 1, 
                'time' => $agora, 
                'bloqueado_ate' => null,
                'device_info' => $dados['device_info'] // Mantém info do dispositivo
            ];
            return;
        }
        
        // Incrementa contador
        $total = $_SESSION[$chave]['count'];

        $_SESSION[$chave]['count'] = $total + 1;
        
        // Aplica bloqueio se exceder limite
        if ($_SESSION[$chave]['count'] > $limite) {
            $_SESSION[$chave]['bloqueado_ate'] = $agora + 60; // 5 minutos
            
            // Log detalhado para análise
            if ($this->logAttempts) {
                $this->logarTentativaAcesso(429, "Rate limit excedido", [
                    'device_token' => $deviceToken,
                    'requests_count' => $_SESSION[$chave]['count'],
                    'time_window' => $janela,
                    'device_info' => $dados['device_info']
                ]);
            }
            
            $this->mata(429, "Muitas requisições do dispositivo", "Dispositivo bloqueado por 1 minuto devido ao excesso de requisições");
        }
    }
    
    public function mata($codigo = 403, $mensagem = "Acesso Negado", $detalhes = null) {
        // Log da tentativa de acesso negado
        if ($this->logAttempts) {
            $this->logarTentativaAcesso($codigo, $mensagem, $detalhes);
        }
        
        http_response_code($codigo);
        
        
        $response = [
            "erro" => true,
            "mensagem" => $mensagem,
            "timestamp" => date('Y-m-d H:i:s'),
            "codigo" => $codigo
        ];
        
        if ($detalhes && !empty($detalhes)) {
            $response["detalhes"] = $detalhes;
        }
        
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    public function onlyLogados(){
        $camposObrigatorios = ["id", "funcao", "tipouser", "ativo", "user"];
        
        foreach ($camposObrigatorios as $campo) {
            if (!isset($_SESSION[$campo])) {
                $this->mata(401, "Usuário não autenticado");
            }
        }
        
        // Verifica se usuário está ativo
        if ($_SESSION["ativo"] != 1 && $_SESSION["ativo"] !== true) {
            $this->mata(403, "Usuário inativo");
        }
    }
    
    public function onlyAdmins(){
        $this->onlyLogados();
        
        if (intval($_SESSION["funcao"]) > 1) {
            $this->mata(403, "Acesso restrito a administradores");
        }
    }
    
    public function onlyEquipe(){
        $this->onlyLogados();
        if($_SESSION["tipouser"] != "equipe"){
             $this->mata(403, "Acesso restrito à equipe");
        }
    }
    
    public function onlyClientes(){
        $this->onlyLogados();
        
        $this->onlyLogados();
        if($_SESSION["tipouser"] != "clientes"){
            $this->mata(403, "Acesso restrito a clientes");
        }
    }
    
    public function bloquearDiretos(){
        if(!defined("ACESSO_PERMITIDO")){
            $this->mata(403, "Acesso direto negado");
        }
    }
    
    private function verificarUserAgent() {
        if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
            $this->mata(400, "User-Agent obrigatório");
        }
    }
    
    private function verificarHTTPS() {
    if (isset($_SERVER['HTTP_CF_VISITOR'])) {
        $cfVisitor = json_decode($_SERVER['HTTP_CF_VISITOR'], true);
        if ($cfVisitor && $cfVisitor['scheme'] === 'https') {
            return; // HTTPS OK
        }
    }
    
 
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        if (getenv('APP_ENV') === 'production') {
            $this->mata(400, "HTTPS obrigatório");
        }
    }
}

    private function isJsonObject($string) {
    $string = trim($string);
    if (!(str_starts_with($string, '{') && str_ends_with($string, '}'))) {
        return false;
    }
    
    $decoded = json_decode($string, true);
    return json_last_error() === JSON_ERROR_NONE && is_array($decoded);
}

    private function validarJsonSeguro($jsonString) {
    // Decodifica para validar estrutura
    $data = json_decode($jsonString, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $this->mata(400, "JSON inválido");
    }
    
    // Valida o conteúdo do JSON recursivamente para XSS/SQL
    $this->validarDadosJson($data);
}

    private function validarDadosJson($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Aplica apenas detecções de segurança, sem modificar
                foreach ($this->sqlPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->mata(400, "Conteúdo perigoso no JSON");
                    }
                }
                
                foreach ($this->xssPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->mata(400, "Conteúdo perigoso no JSON");
                    }
                }
            } elseif (is_array($value)) {
                $this->validarDadosJson($value);
            }
        }
    }
}
}

?>