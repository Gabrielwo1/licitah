<?
http_response_code(204); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include __DIR__."/../../../../admin/conn.php";
include __DIR__."/agente.php";
include __DIR__."/../../enderecos/admins/integracao.php";

class Analytics{
    private $conn;
    private $acao;
    private $usuario;

    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
    }
    
    function hasher($length = 32){
        return bin2hex(random_bytes($length / 2));
    }
    
    function registrarDispositivo(){
       
        if(empty($_SESSION["sessao"]["id"])){
            return ["erro"=>true, "mensagem"=>"O dispositivo não pode ser registrado sem uma sessão"];
        }
        
        if(!empty($_SESSION["sessao"]["dispositivo"])){
            return ["sucesso"=>true, "mensagem"=>"Dispositivo já registrado"];
        }
        
        $sessao = $_SESSION["sessao"]["id"];
        
        $assinatura = $_POST["assinatura"] ?? false;
        if($assinatura){
            $seleciona = "SELECT analytics_dispositivo_id as id, analytics_dispositivo_hash as hash FROM analytics_dispositivos WHERE analytics_dispositivo_hash='{$assinatura}' LIMIT 1";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $_SESSION["sessao"]["dispositivo"] = $dado["id"];
                $this->updateSessao();
                return ["sucesso"=>true, "mensagem"=>"Dispositivo já estava registrado", "dispositivo"=>$dado["hash"]];
            }
        }
        
        $ex = json_decode($_POST["dispositivo"] ?? '[]');
        $extra = addSlashes(json_encode($ex, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        
        $userAgentInfo = UserAgentParser::parse();
        
        $navegador = $userAgentInfo["browser"]["name"];
        $sistemaOperacional = $userAgentInfo["os"]["name"];
        $browsers = [
            'Edge' => 2,
            'Chrome' => 1,
            'Firefox' => 3,
            'Safari' => 4,
            'Opera' => 5,
            'Internet Explorer' => 6,
            'Internet Explorer 11' => 6
        ];
        $device = $userAgentInfo["device"];
        $plataforma = $userAgentInfo["platform"];
        $browser = $browsers[$navegador] ?? 7;
        
        $hash = $this->hasher(32);
        $url = $this->hasher(12);
        $bot = empty($userAgentInfo["is_bot"]) ? 0 : 1;
        
        
        $cadastra = "INSERT INTO analytics_dispositivos (
            analytics_dispositivo_tipo,
            analytics_dispositivo_plataforma,
            analytics_dispositivo_navegador,
            analytics_dispositivo_bot,
            analytics_dispositivo_idioma,
            analytics_dispositivo_extra,
            analytics_dispositivo_hash,	
            analytics_dispositivo_url
            ) VALUES (
                '$device',
                '$plataforma',
                '$browser',
                '$bot',
                'pt-br',
                '$extra',
                '$hash',
                '$url'
                )";
        if($this->conn->query($cadastra) == true){
            $_SESSION["sessao"]["dispositivo"] = $this->conn->insert_id;
            $this->updateSessao();
            return ["sucesso"=>true, "mensagem"=>"Dispositivo registrado com sucesso", "dispositivo"=>$hash];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível registrar dispositivo"];
        }
            
    }
    
    function updateSessao(){
        if(empty($_SESSION["sessao"]["id"])){
            return ["erro"=>true, "mensagem"=>"A sessão não pode ser atualizada se não for iniciada"];
        }
        
        $sessao = $_SESSION["sessao"]["id"];
        $sets = [];
        
        if(!empty($_SESSION["sessao"]["dispositivo"])){
                $dispositivo = $_SESSION["sessao"]["dispositivo"];
                $sets[] = "analytic_dispositivo = '$dispositivo'";
        }
        
        if(!empty($_SESSION["id"])){
                $usuario = $_SESSION["id"];
                $sets[] = "analytic_usuario = '$usuario'";
        }
        
        if(!empty($_SESSION["sessao"]["ip"])){
            $ip = $_SESSION["sessao"]["ip"];
            $sets[] = "analytic_ip = '$ip'";
        }
        
        if(!empty($sets)){
            $join = implode(",", $sets);
            $update = "UPDATE analytics SET {$join} WHERE analytic_id='{$sessao}'";
            $this->conn->query($update);
        }
    }
    
    function registrarLocalizacao(){
        if(empty($_SESSION["sessao"]["id"])){
            return ["erro"=>true, "mensagem"=>"A sessão não pode ser atualizada se não for iniciada"];
        }
        
        
        $localizacao = $_POST["localizacao"] ?? false;
        
        $obj = json_decode($localizacao , true);
        
        if(!$localizacao){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma localização válida"];
        }
        
        $ipInfo = $obj["ip"];
        
    
        
        if(empty($_SESSION["sessao"]["ip"])){

            
            $ip = $ipInfo["ip"];
            $seleciona = "SELECT * FROM analytics_ips WHERE analytics_ip_ip='{$ip}' LIMIT 1";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
 
                $dado = $resultado->fetch_assoc();
                $_SESSION["sessao"]["ip"] = $dado["analytics_ip_id"];
            }else{
              
                $empresa = $ipInfo["empresa"];
                $cidade = $ipInfo["cidade"];
                $pais = $ipInfo["pais"];
                $pais = $pais == "Brazil" ? "Brasil" : $pais;
                $latitude = $ipInfo["latitude"];
                $longitude = $ipInfo["longitude"];
                $estado = $ipInfo["estado"];
     
                $endereco = new AdressParse($pais, $estado, $cidade);
                $retorno = $endereco->parse();
                
              
                if($retorno["sucesso"]){
         
                    $cidade = $retorno["id"]; 
                    $pais = $retorno["pais"];
                    $estado = $retorno["estado"];
                    
                    
                   
                }else{
                     $cidade = NULL; 
                    $pais = NULL;
                    $estado = NULL;
                    
                }
                
                $hash = $this->hasher(32);
                $url = $this->hasher(12);
                
                 $cadastra = "INSERT INTO  analytics_ips 
                    (analytics_ip_ip,analytics_ip_cidade,analytics_ip_estado,analytics_ip_pais,analytics_ip_latitude,analytics_ip_longitude,analytics_ip_empresa,analytics_ip_hash,analytics_ip_url) VALUES 
                    ('{$ip}','{$cidade}','{$estado}','{$pais}','{$latitude}','{$longitude}','{$empresa}','{$hash}','{$url}')";
                    if($this->conn->query($cadastra) === true){
         
                        $id = $this->conn->insert_id;
                        $_SESSION["sessao"]["ip"] = $id;
                    }
            }

        }
        
        
      
        
       if($obj["gps"] ?? false){
           $gpsInfo = $obj["gps"];
           
           $latitude = $gpsInfo["latitude"] ?? false;
           $longitude = $gpsInfo["longitude"] ?? false;
           $accuracy = $gpsInfo["accuracy"] ?? false;
           $altitude = $gpsInfo["altitude"] ?? false;
           $altitudeAccuracy = $gpsInfo["altitudeAccuracy"] ?? false;
           $heading = $gpsInfo["heading"] ?? false;
           $speed =$gpsInfo["speed"] ?? false;
         
           
           
       }
        
        
        
        
        $this->updateSessao();
        
        
        return ["sucesso"=>true, "mensagem"=>"Localização registrada com sucesso"];
        
    }
    
    function iniciarSessao(){
        $hash = $this->hasher(32);
        $url = $this->hasher(12);
        
        $usuario = $this->usuario ? "'{$this->usuario}'" : "NULL";
        
        $cadastra = "INSERT INTO analytics (analytic_ativo, analytic_usuario, analytic_hash, analytic_url, analytic_ultima) VALUES ('1', $usuario, '{$hash}', '{$url}', NOW())";
        
        if($this->conn->query($cadastra) == true){
            $_SESSION["sessao"] = [
                "id"=>$this->conn->insert_id
            ];
            return ["sucesso"=>true, "mensagem"=>"Sessão iniciada com sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Falha ao iniciar sessão", "erro"=>$this->conn->error];
        }
            
    
    }

    function start(){
  
        $url = $_POST["url"] ?? false;
        
        if(empty($_SESSION["sessao"])){
            $this->iniciarSessao();
        }
        
        if(empty($_SESSION["sessao"])){
            return ["erro"=>true, "mensagem"=>"Não existe uma sessão iniciada"];
        }
        
  
        
        $hash = $this->hasher(32);
        $url = $this->hasher(12);
        $sessao = $_SESSION["sessao"]["id"];
        
        $cadastra = "INSERT INTO analytics_abas (
            analytics_aba_sessao , analytics_aba_ativa , analytics_aba_visivel , analytics_aba_hash , analytics_aba_url, analytics_aba_ultima) VALUES 
            ('$sessao', '1', '1', '{$hash}', '{$url}', NOW())";
        
        if($this->conn->query($cadastra) == true){
            $id = $this->conn->insert_id;
            $_SESSION["sessao"]["abas"][$hash] = $id;
            $this->pagina(false, $id);
            return [
                "sucesso"=>true,
                "mensagem"=>"Aba registrada com sucesso", 
                "aba"=>$hash, 
                "localizacao"=>empty($_SESSION["sessao"]["localizacao"]) ? false : true,
                "ip"=>empty($_SESSION["sessao"]["ip"]) ? false : true,
                "dispositivo"=>empty($_SESSION["sessao"]["dispositivo"]) ? false : true
                ];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi posspivel iniciar aba"];
        }
    }
    
    function fechamento(){
        $sessao = $_SESSION["sessao"]["id"] ?? false;
        
        if(!$sessao){
            return ["erro"=>true, "mensagem"=>"Não foi encontrada uma sessão válida"];
        }
        
        $aba = $_POST["aba"] ?? false;
        if(!$aba){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma aba valida"];
        }
        
        $id = $_SESSION["sessao"]["abas"][$aba] ?? false;
        if(!$id){
            return ["erro"=>true, "mensagem"=>"A aba enviada não é válida"];
        }
        
        
        $this->mataUltimaPagina($sessao, $id);
        
        $update = "UPDATE analytics_abas SET analytics_aba_ativa='0', analytics_aba_visivel='0' WHERE analytics_aba_id='{$id}'";
        $sucesso = $this->conn->query($update);
        unset($_SESSION["sessao"]["abas"][$aba]);
        if (empty($_SESSION["sessao"]["abas"])) {
            $this->conn->query("UPDATE analytics SET analytic_ativo='0' WHERE analytic_id='$sessao'");
            unset($_SESSION["sessao"]);
        }
        return $sucesso ? ["sucesso"=>true, "mensagem"=>"Aba encerrada com sucesso"] : ["erro"=>true, "mensagem"=>"Não foi possível fechar a aba"];

    }
    
    function registraUsuario(){
        $usuario = $_SESSION["id"] ?? false;
        $sessao = $_SESSION["sessao"]["id"] ?? false;
        
        if(!$usuario || !$sessao){
            return ["erro"=>true, "mensagem"=>"Não foi possível registrar usuário"];
        }
        $this->updateSessao();
        return ["sucesso"=>true, "mensagem"=>"Usuário registrado com sucesso"];
    }
    
    function parseInfo($aba = false){
        if(empty($_SESSION["sessao"])){
            echo "nao tem sessao";
            return ["erro"=>true, "mensagem"=>"Não pode registrar batida sem uma sessão"];
        }
        
        if(!$aba){
            $aba = $_POST["aba"] ?? false;
        if(!$aba){
            return ["erro"=>true, "mensagem"=>"É necessario enviar uma aba"];
        }
        
        if(!isset($_SESSION["sessao"]["abas"][$aba])){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma aba válida"];
        }
        $idAba = $_SESSION["sessao"]["abas"][$aba];
        }else{
            $idAba = $aba;
        }
        
        
        
        $sessao = $_SESSION["sessao"]["id"];
        return ["sucesso"=>true, "aba"=>$idAba, "sessao"=>$sessao];
    }
    
    function batida(){
        
        $info = $this->parseInfo();
        if(isset($info["erro"])){
            return $info;
        }
        
        $sessao = $info["sessao"];
        $aba = $info["aba"];
        
        $update = "UPDATE analytics_abas SET analytics_aba_ultima=NOW() , 	analytics_aba_ativa='1', analytics_aba_visivel='1' WHERE analytics_aba_id ='{$aba}'";
        $this->conn->query($update);
        
        $update = "UPDATE analytics SET analytic_ativo='1', analytic_ultima=NOW() WHERE analytic_id='{$sessao}'";
        $this->conn->query($update);
        
        return ["sucesso"=>true, "mensagem"=>"Sessão atualizada com sucesso"];
    }
    
    function abainvisivel(){
        $info = $this->parseInfo();
        if(isset($info["erro"])){
            return $info;
        }
        
      
        $aba = $info["aba"];
        
        $update = "UPDATE analytics_abas SET analytics_aba_ultima=NOW() , analytics_aba_visivel='0' WHERE analytics_aba_id ='{$aba}'";
        $this->conn->query($update);
        
        
        return ["sucesso"=>true, "mensagem"=>"Sessão atualizada com sucesso"];
    }
    
    function getIdPagina($url){
        $url = preg_replace('~^/+|/+$~', '', $url);
        if(!$url){
            $url = "home";
        }
        $seleciona = "SELECT analytics_url_id as id FROM analytics_urls WHERE analytics_url_url='$url' LIMIT 1";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["id"];
        }
        $hash = $this->hasher(32);
        $cadastra = "INSERT INTO analytics_urls (analytics_url_url, analytics_url_hash) VALUES ('{$url}', '{$hash}')";
        if($this->conn->query($cadastra) == true){
            return $this->conn->insert_id;
        }else{
            echo $this->conn->error();
        }
        
        return false;
    }
    
    function mataUltimaPagina($sessao, $aba){
        $seleciona = "SELECT * FROM analytics_acessos WHERE analytics_acesso_sessao='$sessao' AND	analytics_acesso_aba='{$aba}' ORDER BY analytics_acesso_data DESC LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
             $calc = time() - strtotime($dado['analytics_acesso_data']);
             if ($calc < 0) {               
                 $calc = 0;
             }
             
             $id = $dado['analytics_acesso_id'];
             
             $update = "UPDATE analytics_acessos SET analytics_acesso_vida='$calc' WHERE analytics_acesso_id='{$id}'";
             $this->conn->query($update);
        }
    }
    
    function pagina($batida = true, $aba = false){
        $url = $_POST["url"] ?? false;
  
        if(!$url){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma url válida"];
        }
     
        
        $info = $this->parseInfo($aba);
        if(isset($info["erro"])){
            return $info;
        }
        
    
        
        $sessao = $info["sessao"];
        $aba = $info["aba"];
        
        if($batida){
            $this->batida();
            $this->mataUltimaPagina($sessao, $aba);
        }
        
        
        $idPagina = $this->getIdPagina($url);
        if($idPagina){
            $hash = $this->hasher(32);
            $usuario = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 'NULL';

            $cadastra = "INSERT INTO analytics_acessos 
            (analytics_acesso_pagina,analytics_acesso_sessao, analytics_acesso_aba, analytics_acesso_usuario, analytics_acesso_vida,analytics_acesso_hash) VALUES 
            ('{$idPagina}', '{$sessao}', '{$aba}', $usuario, '0', '{$hash}')";
            $this->conn->query($cadastra);
        }
    }
    
    function render(){
        switch($this->acao){
            case 'start':
                return $this->start();
                break;
            case 'fechamento':
                return $this->fechamento();
                break;
            case 'registra-usuario':
                return $this->registraUsuario();
                break;
            case 'registra-dispositivo':
                return $this->registrarDispositivo();
                break;
            case 'registrar-localizacao':
                return $this->registrarLocalizacao();
                break;
            case 'batida':
                return $this->batida();
                break;
            case 'abainvisivel':
                return $this->abainvisivel();
                break;
            case 'pagina':
                return $this->pagina();
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


$acao = new Analytics();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>