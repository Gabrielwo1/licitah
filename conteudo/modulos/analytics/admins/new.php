<?
header("Content-type: application/json; charset=utf-8");
include __DIR__."/../../../../admin/conn.php";
session_start();

class Ip {
    private $conn;  // Conexão ao banco
    private $ip;

    public function __construct() {
        $this->conn = conn();
        $this->ip = $this->detectIp();
    }

    private function detectIp(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    public function registraIp(array $dados): int|false{
    
    $ip = $dados['ip'];
    $cidade = $dados['city'] ?? null;
    $estado = $dados['region'] ?? null;
    $pais = $dados['country'] ?? null;
    $latitude = isset($dados['loc']) ? explode(",", $dados['loc'])[0] : null;
    $longitude = isset($dados['loc']) ? explode(",", $dados['loc'])[1] : null;
    $cep = $dados['postal'] ?? null;
    $hostname = $dados['hostname'] ?? null;
    $empresa = $dados['org'] ?? null;
    $timezone = $dados['timezone'] ?? null;

    $stmt = $this->conn->prepare("
        INSERT INTO analytics_ips 
        (anaip_ip, anaip_cidade, anaip_regiao, anaip_pais, anaip_latitude, anaip_longitude, anaip_cep, anaip_hostname, anaip_empresa, anaip_timezone) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssssss",
        $ip,
        $cidade,
        $estado,
        $pais,
        $latitude,
        $longitude,
        $cep,
        $hostname,
        $empresa,
        $timezone
    );

    return $stmt->execute() ? $this->conn->insert_id : false;
}

    public function parseIp(string $ip): array|false {
        
        $seleciona = "SELECT * FROM configuracoes_modulos WHERE config_pasta='analytics'";
        $resultado = $this->conn->query($seleciona);
        $mapa = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $mapa[$dado["config_chave"]] = $dado["config_valor"];
            }
        }
        
        if(!($mapa["ip"] ?? false) || !($mapa["token"] ?? false)){
            return false;
        }
        
        
        $token = $mapa["token"]; // Token da API
        $url = "http://ipinfo.io/$ip?token=$token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("Curl error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        $data = json_decode($response, true);

        return $data ?: false;
    }

    public function render(): array {
    
        $stmt = $this->conn->prepare("SELECT anaip_id AS id FROM analytics_ips WHERE anaip_ip = ?");
        $stmt->bind_param("s", $this->ip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $info = $this->parseIp($this->ip);
        
            if ($info && !isset($info['status'])) {
                $ipId = $this->registraIp($info);
                if ($ipId) {
                    return ["sucesso" => true, "id" => $ipId];
                }
            }else{
                $cadastra = "INSERT INTO analytics_ips (anaip_ip) VALUES ('{$this->ip}')";
                $this->conn->query($cadastra);
                return ["sucesso" => true, "id" => $this->conn->insert_id];
            }
            return ["erro" => true, "mensagem" => "Não foi possível detectar o IP do dispositivo"];
        } else {
            $dado = $result->fetch_assoc();
            return ["sucesso" => true, "id" => $dado['id']];
        }
    }
}

class Dispositivo{
    private mysqli $conn;
    public ?string $assinatura;
    public ?string $dispositivo;
    public string $digital;
 
    function __construct()
    {
      
        $this->conn = conn();
        $this->dispositivo = $_POST["dispositivo"] ?? false;
        $this->assinatura = $_POST["assinatura"] ?? false;
    }

    public function metaDispositivo(int $id, array $dispositivo): void
    {
        $conexaoTipo = $dispositivo["connection"]["type"] ?? '';
        $downlink = (float)($dispositivo["connection"]["downlink"] ?? 0.0);
        $rtt = (int)($dispositivo["connection"]["rtt"] ?? 0);
        $bateriaNivel = (int)($dispositivo["battery"]["level"] ?? 0);
        $carregando = (int)($dispositivo["battery"]["charging"] ?? 0);
        $latitude = (float)($dispositivo["location"]["latitude"] ?? 0.0);
        $longitude = (float)($dispositivo["location"]["longitude"] ?? 0.0);

        $stmt = $this->conn->prepare("
            INSERT INTO analytics_extra 
            (analex_tipo, analex_dispositivo, analex_rtt, analex_carregando, analex_nivel, analex_latitude, analex_longitude, analex_velocidade) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'siiiddsd',
            $conexaoTipo,
            $id,
            $rtt,
            $carregando,
            $bateriaNivel,
            $latitude,
            $longitude,
            $downlink
        );
        $stmt->execute();
    }

    public function salvaDispositivo()
    {
        if (!$this->dispositivo) {
            return ["erro" => true, "mensagem" => "Nenhum dado de dispositivo foi enviado."];
        }

        $dispositivo = json_decode($this->dispositivo, true);
        if (!is_array($dispositivo)) {
            return ["erro" => true, "mensagem" => "Os dados de dispositivo enviados são inválidos."];
        }

        // Extração de dados do dispositivo
        $aparelho = (int)($dispositivo["dispositivo"] ?? 0);
        $width = (int)($dispositivo["screenWidth"] ?? 0);
        $height = (int)($dispositivo["screenHeight"] ?? 0);
        $agent = $dispositivo["userAgent"] ?? '';
        $plataforma = $dispositivo["platform"] ?? '';
        $cookiesEnabled = (int)($dispositivo["cookiesEnabled"] ?? 0);
        $memoria = (int)($dispositivo["memory"] ?? 0);
        $cores = (int)($dispositivo["cores"] ?? 0);
        $gpuVendor = $dispositivo["gpu"]["vendor"] ?? '';
        $gpuRenderer = $dispositivo["gpu"]["renderer"] ?? '';
        $fingerprint = $dispositivo["fingerprint"] ?? $this->generateHash(32);

        // Salvar dispositivo
        $stmt = $this->conn->prepare("
            INSERT INTO analytics_dispositivos 
            (anadis_digital, anadis_cookies, anadis_gpurender, anadis_gpuvendor, anadis_memoria, anadis_cores, anadis_plataforma, anadis_agent, anadis_altura, anadis_largura, anadis_tipo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'sisiiissiii',
            $fingerprint,
            $cookiesEnabled,
            $gpuRenderer,
            $gpuVendor,
            $memoria,
            $cores,
            $plataforma,
            $agent,
            $height,
            $width,
            $aparelho
        );
        $stmt->execute();

        $id = $stmt->insert_id;
        if ($id) {
            //$this->metaDispositivo($id, $dispositivo);
            return ["id" => $id, "assinatura" => $fingerprint];
        }

        return ["erro" => true, "mensagem" => "Falha ao salvar dispositivo."];
    }

    public function render(): array
    {
        if (!$this->assinatura || $this->assinatura == "false") {
            return $this->salvaDispositivo();
        }

        $stmt = $this->conn->prepare("SELECT anadis_id AS id FROM analytics_dispositivos WHERE anadis_digital = ?");
        $stmt->bind_param('s', $this->assinatura);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 0) { 
            $_POST["assinatura"] = false;
            $dispositivo = new Dispositivo();
            return $dispositivo->render();
        }

        $dado = $resultado->fetch_assoc();
        return ["sucesso" => true, "id" => $dado["id"]];
    }

    private function generateHash(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}

class Api{
    public $conn;
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? 0;
        $this->pagina = $_POST["pagina"] ?? false;
        $this->sessao = $_POST["sessao"] ?? false;
    }
    
    function hasher($length = 32){
        return bin2hex(random_bytes($length / 2));
    }
    
    public function closePagina($id){
        
        $seleciona = "SELECT * FROM analytics_urls WHERE anaurl_fluxo='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            $update = "UPDATE analytics_urls SET anaurl_saida=NOW(), anaurl_timer = TIMESTAMPDIFF(SECOND, anaurl_entrada, NOW())  WHERE anaurl_fluxo='$id' AND anaurl_timer='0'";
            $this->conn->query($update);
        }
      
       

   
    }

    public function salvaPagina(int $id): bool{
        // Fecha a página anterior
        $this->closePagina($id);

        // Valida a URL
        if (empty($this->pagina)) {
            throw new InvalidArgumentException("Página não foi definida.");
        }

        // Insere nova entrada
        $stmt = $this->conn->prepare("
            INSERT INTO analytics_urls (anaurl_fluxo, anaurl_url, anaurl_entrada, anaurl_saida, anaurl_timer) 
            VALUES (?, ?, NOW(), '0000-00-00 00:00:00', 0)
        ");
        $stmt->bind_param('is', $id, $this->pagina);

        return $stmt->execute();
    }
    
    function novo(){
         $ip = new Ip();
        $ip = $ip->render();
        if(isset($ip["erro"])){
            return ["erro"=>true, "mensagem"=>"Falha ao obter IP"];
        }
        
        $ipId = $ip["id"];
        
        $dispositivo = new Dispositivo();
        $dispositivo = $dispositivo->render();
        
        if(isset($dispositivo["erro"])){
            print_r($dispositivo);
            return ["erro"=>true, "mensagem"=>"Falha ao obter dispositivo"];
        }
        if(isset($dispositivo["assinatura"])){
            $assinaturaDispositivo = $dispositivo["assinatura"];
        }
        
        $dispositivoId = $dispositivo["id"];
        
                $hash = $this->hasher(32);
                $cadastra = "INSERT INTO analytics (analytic_ip,analytic_ativo,analytic_usuario,analytic_origem,analytic_dispositivo,analytic_hash) VALUES
                ('{$ipId}', '1', '{$this->user}', '0', '{$dispositivoId}', '{$hash}')";
                $this->conn->query($cadastra);
                $id = $this->conn->insert_id;
                $this->salvaPagina($id);
                
                $obj = ["sucesso"=>true, "sessao"=>$hash];
                if(!empty($assinaturaDispositivo)){
                    $obj["dispositivo"] = $assinaturaDispositivo;
                }
                return $obj;
    }
    
    function parse($sessao){
        if(!$sessao){
            return false;
        }
        
        $seleciona = "SELECT analytic_id  as id FROM  analytics WHERE 	analytic_hash='{$sessao}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return $dado["id"];
        
    }
    
    public function desativarSessoesInativas(){
    // Passo 1: Seleciona as sessões ativas que não são atualizadas há mais de 1 minuto
    $query = "
        SELECT analytic_id as id, analytic_vida as vida, analytic_data as data
        FROM analytics
        WHERE analytic_ativo = 1
        AND TIMESTAMPDIFF(SECOND, analytic_update, NOW()) > 60
    ";
    $resultado = $this->conn->query($query);

    // Passo 2: Verifica se há sessões para desativar
    if ($resultado->num_rows > 0) {
        $idsParaDesativar = [];

        // Passo 3: Monta o array de IDs das sessões inativas
        while ($row = $resultado->fetch_assoc()) {
            $idsParaDesativar[] = $row['id'];

            // Somando a vida da sessão à data de atualização
            $dataAtualizada = date('Y-m-d H:i:s', strtotime($row['data'] . ' + ' . $row['vida'] . ' seconds'));

            // Atualiza as URLs associadas a essa sessão
            $update = "
                UPDATE analytics_urls 
                SET anaurl_saida = ?, 
                    anaurl_timer = TIMESTAMPDIFF(SECOND, anaurl_entrada, ?)
                WHERE anaurl_fluxo = ? 
                AND anaurl_timer = 0
            ";

            $stmt = $this->conn->prepare($update);
            $stmt->bind_param('ssi', $dataAtualizada, $dataAtualizada, $row['id']);
            $stmt->execute();
        }

        // Passo 4: Desativa as sessões
        $ids = implode(',', $idsParaDesativar);
        $updateQuery = "
            UPDATE analytics
            SET analytic_ativo = 0
            WHERE analytic_id IN ($ids)
        ";
        $this->conn->query($updateQuery);
    }
    return ["sucesso"=>"Sessões orfãos encerradas"];
}

    function render(){
     
        $obj = json_decode(file_get_contents("php://input"), true);
        if(!$this->acao){
             $this->acao = $obj["acao"] ?? false;
        }
       
       
        switch($this->acao){
            case 'novo':
                   if(!$this->pagina){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma página válida"];
        }
                return $this->novo();
                break;
            case 'update':
                   if(!$this->pagina){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma página válida"];
        }
                $id = $this->parse($this->sessao);
                 
                if(!$id){
                    return ["erro"=>true, "mensagem"=>"A sessão enviada não é válida"];
                }
                $this->salvaPagina($id);
                return ["sucesso"=>true, "mensagem"=>"Navegação salva com sucesso"];
                break;
            case 'out':
            
                $id = $this->parse($obj["sessao"]);
                 
                if(!$id){
                    return ["erro"=>true, "mensagem"=>"A sessão enviada não é válida"];
                }
                $this->closePagina($id);
                $update = "UPDATE analytics SET analytic_ativo='0' WHERE analytic_id ='$id'";
                $this->conn->query($update);
                return ["sucesso"=>true, "mensagem"=>"Sessão encerrada com sucesso"];
                break;
            case 'heartbeat':
                 $id = $this->parse($this->sessao);
                 if(!$id){
                    return ["erro"=>true, "mensagem"=>"A sessão enviada não é válida"];
                 }
                 $update = "UPDATE analytics set analytic_vida=TIMESTAMPDIFF(SECOND, analytic_data, NOW()) WHERE analytic_id='$id'";
                 $this->conn->query($update);
                 return ["sucesso"=>true, "mensagem"=>"Sessão atualizada com sucesso"];
                break;
            case 'desativa':
                return $this->desativarSessoesInativas();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
    
    function close(){
        $this->conn->close();
    }
}

$api = new Api();
$resposta = $api->render();
$api->desativarSessoesInativas();
$api->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>