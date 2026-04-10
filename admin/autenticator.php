<?
header('Content-Type: application/json; charset=utf-8');

session_start();
date_default_timezone_set('America/Sao_Paulo'); 
include __DIR__."/bibliotecas/vendor/autoload.php";
include __DIR__."/config.php";


use OTPHP\TOTP;
use Psr\Clock\ClockInterface;

class MyClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}

class Autenticator{
    public $aplicacao;
    public $acao;
    public $conn;
    public $user;
    public $email;

    function __construct(){
        $this->aplicacao = SETUP["config"]["geral"]["geral"]["nome"] ?? SETUP["dominio"];
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->email = $_SESSION["email"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }

    function gerar(){
        
        $clock = new MyClock();
        $totp  = TOTP::generate($clock);

        
        //$totp = TOTP::create();
        $secret = $totp->getSecret();
        $totp->setLabel($this->aplicacao.' - '.$this->email);
        

        
        $user = $this->user;
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='$user' AND um_chave='autenticator-provisorio'";
        $this->conn->query($deleta);

        $cadastra = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$user', 'autenticator-provisorio', '$secret')";
        $this->conn->query($cadastra);

        $totp->setIssuer($this->aplicacao);
        $qrCodeUrl = $totp->getProvisioningUri();
        return ["sucesso" => true, "qrcode" => $qrCodeUrl];
    }

    function geral(){
        $user = $this->user;
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$user'";
        $resultado = $this->conn->query($seleciona);
        $respostas = [];

        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $respostas[$dado["um_chave"]] = $dado["um_valor"];
            }
        }

        $r = [];
        if (!empty($_POST["viaNumero"])) $r["doisFatores"] = boolval($respostas["dois-fatores"] ?? false);
        if (!empty($_POST["viaAutenticator"])) $r["totp"] = boolval($respostas["totp"] ?? false);
        if (!empty($_POST["viaDigital"])) $r["digital"] = boolval($respostas["digital"] ?? false);

        return ["sucesso" => true, "setup" => $r];
    }

    function vincular(){
        $senha = $_POST["senha"] ?? false;
        $code = trim(preg_replace('/\s+/', ' ', $_POST["code"]));  

        if(!$senha || !$code){
            return ["erro" => true, "mensagem" => "Parâmetros necessários ausentes"];
        }

        $md5 = md5($senha);
        $user = $this->user;
        $seleciona = "SELECT count(*) as total FROM usuarios WHERE usuario_id='$user' AND usuario_senha='$md5'";
        $resultado = $this->conn->query($seleciona);
        $dado = $resultado->fetch_assoc();

        if($dado["total"] == 0){
            return ["erro" => true, "mensagem" => "Senha inválida"];
        }

        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$user' AND um_chave='autenticator-provisorio'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro" => true, "mensagem" => "Código provisório não encontrado"];
        }

        $dado = $resultado->fetch_assoc();
        $chaveSecreta = $dado["um_valor"];
        
        $clock = new MyClock();
        $otp = TOTP::createFromSecret($chaveSecreta , $clock);

        if($otp->now() == $code){
            
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='$user' AND um_chave='autenticator-provisorio'";
        $this->conn->query($deleta);
        
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='$user' AND um_chave='chave-totp'";
        $this->conn->query($deleta);

        $cadastra = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$user', 'chave-totp', '$chaveSecreta')";
        $this->conn->query($cadastra);
        
        $cadastra = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$user', 'totp', 'true')";
        $this->conn->query($cadastra);
        
        

            return ["sucesso" => true, "mensagem" => "Código válido"];
        }else{
            return ["erro" => true, "mensagem" => "Código inválido"];
        }

    }
    
    function valida(){
        $code = trim(preg_replace('/\s+/', ' ', $_POST["code"])) ?? false;  
        if(!$code){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um código válido"];
        }
        $user = $this->user;
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$user' AND um_chave='chave-totp'";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não existe uma chave de validação"];
        }
        
        $dado = $resultado->fetch_assoc();
        $chaveSecreta = $dado["um_valor"];
        
        $clock = new MyClock();
        $otp = TOTP::createFromSecret($chaveSecreta , $clock);
        
        if($otp->now() == $code){
            $_SESSION["2fa"] = false;
            return ["sucesso"=>true, "mensagem"=>"Código correto"];
        }else{
            return ["erro"=>true, "mensagem"=>"O código digitado não é valido"];
        }
    }
    
    function desvincular(){
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='{$this->user}' AND um_chave='autenticator-provisorio'";
        $this->conn->query($deleta);
        
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='{$this->user}' AND um_chave='chave-totp'";
        $this->conn->query($deleta);
        
        $deleta = "DELETE FROM usuarios_meta WHERE um_usuario='{$this->user}' AND um_chave='totp'";
        $this->conn->query($deleta);
        
        return ["sucesso"=>true, "mensagem"=>"Conta desvinculado com sucesso"];
    }

    function render(){
        if(!$this->user){
            return ["erro" => true, "mensagem" => "Usuário não autenticado"];
        }

        switch($this->acao){
            case 'init':
                return $this->geral();
            case 'vincular':
                return $this->vincular();
            case 'gerar':
                return $this->gerar();
            case 'valida':
                return $this->valida();
            case 'desvincular':
                return $this->desvincular();
                break;
            default:
                return ["erro" => true, "mensagem" => "Ação inválida"];
        }
    }
}





$autenticacao = new Autenticator();
$resposta = $autenticacao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>