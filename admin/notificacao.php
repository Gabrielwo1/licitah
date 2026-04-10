<?



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include_once __DIR__."/conn.php";
include_once __DIR__.'/config.php';
include_once __DIR__ . "/configmodulo.php";

include_once __DIR__."/bibliotecas/vendor/autoload.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificacaoControler{
    public $id;
    public $conn;
    public $acao;
    public $paginacao;
    function __construct(){
        if (session_status() == PHP_SESSION_NONE) { 
            session_start();
        }
         
         include_once __DIR__."/conn.php";
         
          $this->conn = conn();
          $this->paginacao = $_POST["paginacao"] ?? 1;
          $this->id = $_SESSION["id"] ?? NULL;
          $this->acao = $_POST["acao"] ?? false;
    }
    
    function lista(){
        $id = $this->id;
        
        $perPage = 20; 
        $offset = ($this->paginacao - 1) * $perPage; 

    
    $seleciona = "SELECT * FROM notificacoes WHERE notificacao_destinatario = '$id' AND notificacao_inapp = '1' ORDER BY notificacao_data DESC LIMIT $perPage OFFSET $offset";

        
        $resultado = $this->conn->query($seleciona);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($lista,
                    [
                    "id"=>$dado["notificacao_id"],
                    "cabecalho"=>$dado["notificacao_cabecalho"],	
                    "body"=>$dado["notificacao_body"],
                    "imagem"=>$dado["notificacao_imagem"],
                    "data"=>$dado["notificacao_data"],
                    "lido"=>$dado["notificacao_lido"],
                    "hash"=>$dado["notificacao_hash"],
                    "link"=>$dado["notificacao_link"] ?? false
                    ]
                    );
            }
        }
        
        $total = 0;
        if($this->paginacao == 1){
             $seleciona = "SELECT notificacao_id FROM notificacoes WHERE notificacao_destinatario='$id' AND notificacao_inapp='1' AND notificacao_lido='0'";
             $resultado = $this->conn->query($seleciona);
             $total = $resultado->num_rows;
        }
        
        
        return ["sucesso"=>true, "lista"=>$lista, "total"=>$total];
    }
    
    function ler(){
        $id = $_POST["id"] ?? false;
        if(!$id){
            return ["erro"=>true, "mensagem"=>"ID não definido"];
        }
        
        $atualiza = "UPDATE notificacoes SET notificacao_lido='1' WHERE notificacao_id='$id'";
        if($this->conn->query($atualiza) == true){
            return ["sucesso"=>true];
        }else{
            return ["erro"=>true];
        }
    }
    
    function render(){
        if(!$this->id){
            return ["erro"=>"true", "mensagem"=>"Para verificar essa informação é preciso estar logado"];
        }
        
        switch($this->acao){
            case 'allNotifications':
                return $this->lista();
                break;
            case 'lerNotifications':
                return $this->ler();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação é inválida"];
                break;
        }
        
        
    }
}


class Notificacao{
    public $destinatario;
    public $conn;
    public $user;
    public $beamsClient;
    
    public $header;
    public $body;
    public $imagem;
    public $link;
    
    public $remetente;
    public $dominio;

    function __construct($destinatario){
        $this->destinatario = $destinatario;
        $this->remetente = false;
        $this->conn = conn();
        $this->setup();
        $this->user = $this->loadUserInfos();
        configModulo('notificacoes');
        $this->dominio = verModulo('configuracoes', 'dominio', '');
       
    }
    
    function setRemetente($id){
        $seleciona = "SELECT * FROM  usuarios WHERE usuario_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $this->remetente = false;
        }else{
            $dado = $resultado->fetch_assoc();
            $this->remetente = [
                "nome"=>$dado["usuario_display"],
                "email"=>$dado["usuario_email"] ?? false,
                "telefone"=>$dado["usuario_telefone"] ?? false,
                "id"=>$id
            ];
        }
    }
    
    function mensagem($array){
        $this->header = $array["header"] ?? "";
        $this->body = $array["body"] ?? "";
        $this->imagem = $array["imagem"] ?? false;
        $this->link = $array["link"] ?? false;
    }
    
    function setup(){
        /* Push */

        $setup = file_get_contents(__DIR__.'/../conteudo/setup.json');
        
        $setup = json_decode($setup, true);

        $config = $setup['notificacoes']['push-notification'];
        
        $instanceId = $config['instanceid'];
        $secretkey = $config['secretkey'];
        
        $this->beamsClient = new \Pusher\PushNotifications\PushNotifications(
        array(
            "instanceId" => $instanceId,
            "secretKey" => $secretkey,
        ));
        
        
        
    }
    
    function loadUserInfos(){
        $usuario = $this->destinatario;
        $seleciona = "SELECT * FROM  usuarios WHERE usuario_id='$usuario'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        
        $dado = $resultado->fetch_assoc();
        return [
            "nome"=>$dado["usuario_display"],
            "email"=>$dado["usuario_email"] ?? false,
            "telefone"=>$dado["usuario_telefone"] ?? false,
            "id"=>$usuario
            ];
    }

    function all(){
        $this->email();
        $this->push();
        $this->whatsApp();
        $this->inApp();
    }
    
    function sms(){
        
    }
    
    function email(){
        if(!v(["notificacoes", "configuracoes", "email"], false) || !v(["notificacoes", "email", "remetente"], false) || !v(["notificacoes", "email", "nomeremetente"], false)){
            return ["erro"=>true, "mensagem"=>"Alguma configuração básica não foi feita"];
        }
        
        $emailRemetente = trim(v(["notificacoes", "email", "remetente"], false));
        $nomeRemetente = trim(v(["notificacoes", "email", "nomeremetente"], false));
        
        if(!$this->user || !$this->user["email"]){
            return ["erro"=>true, "mensagem"=>"Remetente inválido"];
        }
        $body = $this->body;
        
        
        $bg = v(["notificacoes", "layout", "bg"], '#000');
        $logo = v(["notificacoes", "layout", "logo"], false);
        $tamanho = v(["notificacoes", "layout", "tamanhoimg"], 300);
        
        $htmlLogo = "";
        if($logo){
            $logo = json_decode($logo, true);
            if(!empty($logo)){
                $htmlLogo = '<img src="'.$this->dominio.'conteudo/uploads/'.$logo[0].'" style="max-width: 100%; width: '.$tamanho.'px">';
            }
        }
        
        $mensagem = v(["notificacoes", "layout", "mensagem"], false);
        $htmlmsg = "";
        if($mensagem && trim($mensagem)){
            $htmlmsg = '<p style="font-size: 16px; margin: 0 0 10px;">'.trim($mensagem).'</p>';
        }
        
        
        
        $empresa = v(["notificacoes", "layout", "empresa"], false);
        $htmlempresa = ['&copy; '.date("Y")];
        if($empresa && trim($empresa)){
            array_push($htmlempresa, $empresa);
        }
        array_push($htmlempresa, 'Todos os direitos reservados');
        $htmlempresa = implode(" - ", $htmlempresa);
        
        $links = [];
        foreach([1,2,3] as $item){
            
            if(v(["notificacoes", "layout", "link".$item], false)){
                array_push($links, '<a href="'.v(["notificacoes", "layout", "url".$item], false).'">'.v(["notificacoes", "layout", "texto".$item], false).'</a>');
            }
            
            
        }
        $htmllinks = "";
        if(count($links) > 0){
             $htmllinks = '<p>'.implode(" | ", $links).'</p>';
        }
        
        
        
        $template = <<<HTML
       
        

        <!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->header}</title>
    <style>
        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .header {
            background-color:{$bg};
            padding: 20px;
            text-align: center;
            color: white;
        }
        
        .logo {
            width: 120px;
            height: 40px;
            margin: 0 auto 10px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 500;
        }
        
        .content {
            padding: 30px;
            background-color: white;
        }
        
        .code-container {
            margin: 25px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .verification-code {
            font-family: 'Courier New', monospace;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #0066cc;
            margin: 10px 0;
        }
        
        .expiry-notice {
            font-size: 13px;
            color: #777;
            margin-top: 15px;
        }
        
        .button {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            margin-top: 20px;
        }
        
        .security-notice {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            font-size: 13px;
            color: #777;
        }
        
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        @media screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
            
            .verification-code {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{$htmlLogo}</div>
            <h1>{$this->header}</h1>
        </div>
        
        <div class="content">
            <p>Olá {$this->user["nome"]},</p>
            
            {$body}
            
            
    
        </div>
        
        <div class="footer">
            <p>{$htmlempresa}</p>
            <p>Este é um email automático, por favor não responda.</p>
            {$htmllinks}
        </div>
    </div>
</body>
</html>
HTML;
        
         switch(intval(v(["notificacoes", "email", "disparador"] , 1))){
            case 1:
                // SETUP SMTP
                
                $host = trim(v(["notificacoes", "email", "host"], false));
                $usuario = trim(v(["notificacoes", "email", "usuarioemail"], false));
                $senha= trim(v(["notificacoes", "email", "senhaemail"], false));
                $porta = trim(v(["notificacoes", "email", "portaemail"], false));
                $protocolo = trim(v(["notificacoes", "email", "protocolo"], false));
                
                if(!$host || !$usuario || !$senha || !$porta || !$protocolo){
                    return ['erro'=> true, 'mensagem'=> 'Alguns dos campos obrigatórios não foram declarados'];
                }

                $mail = new PHPMailer(true);
                
                try {
                    $mail->isSMTP();
                    $mail->Host = $host; // Servidor SMTP
                    $mail->CharSet = 'UTF-8';
                    $mail->SMTPAuth = true;
                    $mail->Username = $usuario;
                    $mail->Password = $senha;
                    if(intval($protocolo) == 1){
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    }else{
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    }
                    $mail->Port = $porta; // Porta do SMTP (pode ser 465 para SSL)
                    
                    // $mail->SMTPOptions = array(
                    //     'ssl' => array(
                    //         'verify_peer' => false,
                    //         'verify_peer_name' => false,
                    //         'allow_self_signed' => true,
                    //     ),
                    // );
                    // $mail->SMTPDebug = 2;
                    // $mail->Debugoutput = 'html';
                    
                    // Remetente e destinatário
                    $mail->setFrom($emailRemetente, $nomeRemetente);
                    $mail->addAddress($this->user["email"], $this->user["nome"]);
                
                    // Conteúdo do e-mail
                    $mail->isHTML(true);
                    $mail->Subject = $this->header;
                    $mail->Body    = $template;
                    $mail->Timeout = 15; // Tempo limite em segundos
                    $mail->SMTPKeepAlive = false;
                    
                    $envio = $mail->send();
                    
                    $mail->smtpClose();

                    if ($envio) {
                        return ["sucesso"=>true];
                    } else {
                        return ["erro"=>true, 'mensagem'=> $envio];
                    }
                } catch (Exception $e) {
                    return ["erro"=>true, 'mensagem'=> $e];
                }
                
                
                break;
            case 2:
                // Brevo
                 $apiKey = v(["notificacoes", "email", "apibrevo"], false);
                 if($apiKey){
                
                $emailData = array(
                    "sender" => array(
                        "name" => $nomeRemetente,
                        "email" => $emailRemetente
                    ),
                "to" => array(
                    array(
                        "email" => $this->user["email"],
                        "name" => $this->user["nome"]
                        )
                    ),
                    "subject" => $this->header,
                    "htmlContent" => $template
                );
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
                
                $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'api-key: ' . $apiKey;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    return ["erro"=>true];
                }
                curl_close($ch);
                return ["sucesso"=>true];
 
                 }
                break;
            default:
                return ["erro"=>true];
                break;
        }
       
        
      
    }
    
    function push(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Remetente inválido"];
        }
        
        
        $array = array(
            "title" => $this->header,
            "body" => strip_tags($this->body),
            "link"=>$this->link,
            "tipo"=>"notificacao"
            );
             
 
        if($this->beamsClient){
            $foco = "user-".$this->user["id"];
            $this->beamsClient->publishToUsers(
                array($foco),
                array(
                    "fcm" => array("notification" => $array),
                    "apns" => array("aps" => array("alert" => $array)),
                    "web" => array("notification" => $array)
                ));
        }  
    }
    
    function hasher($tamanho) {
        return bin2hex(random_bytes(ceil($tamanho / 2)));
        
    }
    
    function inApp($remetente = false) {
        $hash = $this->hasher(32);
        $imagem = json_encode($this->imagem ? [$this->imagem] : []);
        
    
        if(empty($remetente)){
            $remetente = !empty($this->remetente) ? $this->remetente['id'] : 1;
        }

        // Escapando corretamente as strings
        $header = $this->conn->real_escape_string($this->header);
        $body = $this->conn->real_escape_string($this->body);
        $destinatario = $this->conn->real_escape_string($this->destinatario);
        $link = $this->conn->real_escape_string($this->link);
        $imagem = $this->conn->real_escape_string($imagem);
    
        $sql = "
            INSERT INTO notificacoes (
                notificacao_cabecalho,
                notificacao_body,
                notificacao_destinatario,
                notificacao_lido,
                notificacao_inapp,
                notificacao_link,
                notificacao_hash,
                notificacao_autor,
                notificacao_imagem
            ) VALUES (
                '{$header}',
                '{$body}',
                '{$destinatario}',
                '0',
                '1',
                '{$link}',
                '{$hash}',
                {$remetente},
                '{$imagem}'
            )
        ";
        
        if ($this->conn->query($sql) === true) {
            return ["sucesso" => true, "mensagem" => "Notificação em App feita com sucesso"];
        } else {
            return ["erro" => true, "mensagem" => "Não foi possível gerar notificação em APP: " . $this->conn->error];
        }
    }

    function whatsApp(){
         if(!$this->user || !$this->user["telefone"]){
            return ["erro"=>true, "mensagem"=>"Remetente inválido"];
        }
        
         if(!v(["notificacoes", "configuracoes", "whatsapp"], false) || !v(["notificacoes", "whatsapp", "id"], false) || !v(["notificacoes", "whatsapp", "token"], false)){
            return ["erro"=>true, "mensagem"=>"Alguma configuração básica não foi feita"];
        }
        
        $telefone = preg_replace('/\D/', '', $this->user["telefone"]);

        $mensagem = "";
        if($this->header){
            $mensagem .= $this->header;
        }
        
        if($this->header && $this->body){
            $mensagem .= " | ";
        }
        
        if($this->body){
            $mensagem .= strip_tags($this->body);
        }
        
        
        $params=array(
            'token' => trim(v(["notificacoes", "whatsapp", "token"], false)),
            'to' => '55'.$telefone,
            'body' => $mensagem
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ultramsg.com/".trim(v(["notificacoes", "whatsapp", "id"], false))."/messages/chat",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                
                if ($err) {
                    return ["erro"=>true];
                } else {
                    return ["sucesso"=>true];
                }
        
    }
}


$requests = ["lerNotifications", "allNotifications"];
if(isset($_POST["acao"]) && in_array($_POST["acao"] , $requests)){
    $notificacao = new NotificacaoControler();
    echo json_encode($notificacao->render());
}


//$notificacao = new Notificacao(30);
//$notificacao->mensagem(["header"=>"Recuperação de senha", "body"=>"Seu código é 5299"]);
//$notificacao->email();
//$notificacao->whatsApp()
//$notificacao->push();
//$notificacao->email();

/*
 <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{$this->header}</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
            <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" border="0" style="padding-top: 30px; padding-bottom: 30px">
                <tr>
                    <td align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border: 1px solid #ddd;">
                            <tr>
                                <td align="center" bgcolor="{$bg}" style="padding: 20px;">
                                    {$htmlLogo}
                                </td>
                            </tr>
                             <tr>
                                <td style="padding: 20px; color: #333;">
                                    <h1 style="font-size: 24px; margin: 0 0 10px;">{$this->header}</h1>
                                    <p style="font-size: 16px; margin: 0 0 10px;">Olá {$this->user["nome"]},</p>
                                    {$body}
                                </td>
                            </tr>
                            <tr>
                                <td align="center" bgcolor="#f4f4f4" style="padding: 10px; color: #777; font-size: 12px;">
                                    <p style="margin: 0;">{$htmlempresa}</p>
                                    {$htmllinks}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        */


?>


