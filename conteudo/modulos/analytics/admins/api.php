<?
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include __DIR__."/../../../../admin/conn.php";


class Acao{
    public $acao;
    public $ip;
    public $url;
    public $conn;
    public $usuario;
    public $sessao;
    public $origem;
    public $digital;
    public $cidade;
    public $estado;
    
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->ip = $this->getIp();
        $this->url = $_POST["url"] ?? false;
        $this->conn = conn();
        $this->usuario = $_POST["usuario"] ?? 0;
        $this->sessao = $_POST["sessao"] ?? false;
        $this->origem = $_POST["origem"] ?? false;
        $this->cidade = false;
        $this->estado = false;
    }
    
    function pegaOrigem(){
        $origem = $this->origem;
        if(!$origem){
            return 0;
        }
        
        $seleciona = "SELECT anaor_id FROM analytics_origens WHERE anaor_origem='$origem'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["anaor_id"];
        }
        
        $cadastra = "INSERT INTO analytics_origens (anaor_origem) VALUES ('$origem')";
        if($this->conn->query($cadastra) == true){
              return $this->conn->insert_id;
        }else{
            return 0;
        }
    }
    
    function hasher($length = 32) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, strlen($chars) - 1)];
        }

    return $randomString;
        
    }
    
    function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    function startPage($id){
        if(!$this->url){
            return ["erro"=>true, "mensagem"=>"URL não definida"];
        }
        

        $url = $this->url;
        $cadastra = "INSERT INTO analytics_urls (anaurl_fluxo,anaurl_url,anaurl_entrada,anaurl_saida,anaurl_timer) VALUES ('$id', '$url', NOW(), '0', '0')";
        $this->conn->query($cadastra);
    }
    
    function pegaLocal($ip){
        $token = "f30dff94f38e2c";
        $old = "71440cbc5d80ae";
        $url = "http://ipinfo.io/$ip?token=$token";
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);

        if(curl_errno($ch)) {
            echo 'Erro no Curl: ' . curl_error($ch);
        } else {
            $data = json_decode($response, true);
            return $data;
        }
        
        curl_close($ch);
        return false;
        
    }
    
    function pegaIp($ip){
        $seleciona = "SELECT * FROM analytics_ips WHERE anaip_ip='$ip'"; 
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
             $this->cidade =$dado["anaip_cidade"];
             $this->estado = $dado["anaip_regiao"];
            return $dado["anaip_ip"];
        }
        
        $dado = $this->pegaLocal($this->ip);
        
        
        if($dado && !isset($dado["error"])){
              $ip = $dado["ip"];
              $hostname = $dado["hostname"];
              $cidade = $dado["city"];
              $estado = $dado["region"];
              $pais = $dado["country"];
              $latitude = explode("," , $dado["loc"])[0];
              $longitude = explode("," , $dado["loc"])[1];
              $empresa = $dado["org"];
              $cep = $dado["postal"];
              $timezone = $dado["timezone"];
              $this->cidade = $cidade;
              $this->estado = $estado;
            $cadastra = "INSERT INTO analytics_ips (anaip_ip, anaip_cidade, anaip_regiao, anaip_pais, anaip_latitude, anaip_longitude, anaip_cep, anaip_hostname, anaip_empresa, anaip_timezone)  VALUES 
                                                ('$ip', '$cidade', '$estado', '$pais', '$latitude', '$longitude', '$cep', '$hostname', '$empresa', '$timezone')";


        if ($this->conn->query($cadastra) === TRUE) {
            return $this->conn->insert_id;
        }
        }
        
 
        return 0;
    }
    
    function metaDispositivo($id, $dispositivo){
        $conexaoTipo = $dispositivo["connection"]["type"] ?? '';
        $downlink = $dispositivo["connection"]["downlink"] ?? 0;
        $rtt = $dispositivo["connection"]["rtt"] ?? 0;
        $baterianivel = intval($dispositivo["battery"]["level"] ?? 0);
        $carregando = intval($dispositivo["battery"]["charging"] ?? 0);
        $latitude = $dispositivo["location"]["latitude"] ?? 0;
        $longitude = $dispositivo["location"]["longitude"] ?? 0;
        
        $cadastra = "INSERT INTO analytics_extra 
        (analex_tipo , analex_dispositivo , analex_rtt , analex_carregando, analex_nivel , analex_latitude , analex_longitude , analex_velocidade) VALUES 
        ('$conexaoTipo', '$id', '$rtt', '$carregando', '$baterianivel', '$latitude', '$longitude', '$downlink ')";
        $this->conn->query($cadastra);
    }
    
    function pegaDispositivo(){
    $dispositivo = $_POST["dispositivo"] ?? false;
    if(!$dispositivo){
        return 0;
    }
    
    $dispositivo = json_decode($dispositivo, true);
    if (!is_array($dispositivo)) {
        return 0; 
    }
    
    
   
    $aparelho = intval($dispositivo["dispositivo"] ?? 0);
    $width = intval($dispositivo["screenWidth"] ?? 0);
    $height = intval($dispositivo["screenHeight"] ?? 0);
    $agent = $dispositivo["userAgent"] ?? '';
    $plataforma = $dispositivo["platform"] ?? '';
    $idioma = $dispositivo["language"] ?? '';
    $cookie = $dispositivo["cookiesEnabled"] ?? 0;
    
    
    $memoria = intval($dispositivo["memory"] ?? 0);
    $cores = intval($dispositivo["cores"] ?? 0);
    
    $gpuvendor = $dispositivo["gpu"]["vendor"] ?? '';
    $gpurenderer = $dispositivo["gpu"]["renderer"] ?? '';
    
    
    $firgerprint = $dispositivo["firgerprint"] ?? '';


    if(!$firgerprint){
        $finger = $this->hasher(32);
        $this->digital = $finger;
        $cadastra = "
        INSERT INTO analytics_dispositivos 
        (anadis_digital, anadis_cookies, anadis_gpurender, anadis_gpuvendor, anadis_memoria, anadis_cores, anadis_plataforma, anadis_agent, anadis_altura, anadis_largura, anadis_tipo) VALUES
        ('$finger', '$cookie', '$gpurenderer', '$gpuvendor', '$memoria', '$cores', '$plataforma', '$agent', '$height', '$width', '$aparelho')
        ";

        $this->conn->query($cadastra); 
        $id = $this->conn->insert_id;
        $this->metaDispositivo($id, $dispositivo);
        return $id;
    }else{
        $this->digital = $firgerprint;
        $seleciona = "SELECT anadis_id FROM analytics_dispositivos WHERE anadis_digital='$firgerprint'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
             $dado = $resultado->fetch_assoc();
             $id = $dado["anadis_id"];
             $this->metaDispositivo($id, $dispositivo);
             return $id;
        }
        return 0;
       
    }
    return 0;
}

    function start(){
        $ip = $this->pegaIp($this->ip);
        $hash = $this->hasher();
        $usuario = $this->usuario;
        $origem = $this->pegaOrigem();
        $dispositivo = $this->pegaDispositivo();
        $cadastra = "INSERT INTO analytics (analytic_ip, analytic_ativo, analytic_hash, analytic_usuario, analytic_origem, analytic_dispositivo) VALUES ('$ip', '1', '$hash', '$usuario', '$origem', '$dispositivo')";
        if($this->conn->query($cadastra) == true){
            $id =  $this->conn->insert_id;
            $this->startPage($id);
            $this->pegaLocal($ip);
   
            return ["sucesso"=>true, "id"=>$id, "hash"=>$hash, "digital"=>$this->digital, "cidade"=>$this->cidade, "estado"=>$this->estado];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível criar acesso"];
        }
    }
    
    function end(){
        if(!$this->sessao){
            return ["erro"=>true, "mensagem"=>"ID da sessão não enviado"];
        }
        
        $id = $this->sessao;
        $acao = "UPDATE analytics SET analytic_ativo='0' WHERE analytic_id='$id'";
        if($this->conn->query($acao) == true){
            $this->update();
    
            return ["sucesso"=>true, "mensagem"=>"Sessão Encerrada"];
        }else{
             return ["erro"=>true, "mensagem"=>"Não foi possível criar acesso"];
        }
        
    }
    
    function update(){
        if(!$this->sessao){
            return ["erro"=>true, "mensagem"=>"ID da sessão não enviado"];
        }
        $sessao = $this->sessao;
        $update = "UPDATE analytics_urls SET anaurl_saida=NOW(), anaurl_timer = TIMESTAMPDIFF(SECOND, anaurl_entrada, NOW())  WHERE anaurl_fluxo='$sessao' AND anaurl_saida='0'";
        if($this->conn->query($update) == true){
            return ["sucesso"=>true, "mensagem"=>"Página Finalizada"];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível encerrar vizualização de página"];
        }
        
     
    }
    
    function qrcode(){
        $ip = $this->pegaIp($_POST["ip"]);
        $codeId = $_POST["code"];
    }
    
    function close(){
        $this->conn->close();
    }
    
    
    function render(){
        switch($this->acao){
            case 'start':
                return $this->start();
                break;
            case 'end':
                return $this->end();
                break;
            case 'update':
                $update = $this->update();
                $this->startPage($this->sessao); 
                return $update;
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida inválida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>