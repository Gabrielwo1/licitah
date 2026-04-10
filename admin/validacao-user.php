<?php
session_start();
include __DIR__."/conn.php";
include __DIR__."/notificacao.php";

class Validacao{
    private $user;
    private $conn;
    private $acao;
    private $tipo;
    
    function __construct(){
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
    }
    
    public function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ações permitidas somente para usuários"];
        }
        
        $this->conn = conn();
        
        // Limpar tokens vencidos (mais de 5 minutos)
        $this->limparTokensVencidos();
        
        switch($this->acao){
            case 'gerar':
                return $this->gerar();
                break;
            case 'validar':
                return $this->validar();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é válida"];
        }
    }
    
    private function limparTokensVencidos(){
        $sql = "DELETE FROM usuarios_validacoes WHERE usuario_validacao_data < DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
        $this->conn->query($sql);
    }
    
    public function gerar(){
        if(!$this->tipo || !in_array($this->tipo, ['email', 'telefone'])){
            return ["erro"=>true, "mensagem"=>"Tipo de validação inválido"];
        }
        
        $tipoId = ($this->tipo == 'email') ? 1 : 2;
        
        // Verificar se já existe um token válido para este usuário e tipo
        $sql = "SELECT usuario_validacao_token, usuario_validacao_data FROM usuarios_validacoes 
                WHERE usuario_validacao_autor = ? AND usuario_validacao_tipo = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $this->user, $tipoId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $tokenTime = strtotime($row['usuario_validacao_data']);
            $currentTime = time();
            
            // Se o token ainda é válido (menos de 5 minutos)
            if(($currentTime - $tokenTime) < 300){
                
                $notificacao = new Notificacao($this->user);
                $notificacao->mensagem(["header"=>"Validação de email", 
                
                "body"=>'<p>Valide seu esse e-mail é seu mesmo e complete sua conta.</p>
            
            <div class="code-container">
                <div class="verification-code">'.$row['usuario_validacao_token'].'</div>
                <div class="expiry-notice">Este código expira em 3 minutos.</div>
            </div>
            <p>Copie o código acima na caixa de verificação.</p>
            
        
            
            <div class="security-notice">
                <p><strong>Nota de segurança:</strong> Se você não se cadastrou em nosso site, por favor ignore este email ou entre em contato com nosso suporte imediatamente, pois alguém pode estar tentando criar uma conta com esse email.</p>
            </div>']);
                $notificacao->email();


                // Manda Email
                return [
                    "sucesso" => true, 
                    "mensagem" => "Token ainda válido"
                ];
            }
        }
        
        // Gerar novo token
        $token = $this->gerarTokenRandomico(4);
        
        // Deletar qualquer token existente para este usuário e tipo
        $sql = "DELETE FROM usuarios_validacoes WHERE usuario_validacao_autor = ? AND usuario_validacao_tipo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $this->user, $tipoId);
        $stmt->execute();
        
        // Inserir novo token
        $sql = "INSERT INTO usuarios_validacoes 
                (usuario_validacao_autor, usuario_validacao_token, usuario_validacao_tipo) 
                VALUES (?, ? ,  ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $this->user, $token, $tipoId);
        
        if($stmt->execute()){
            // Manda Email
            
             
                $notificacao = new Notificacao($this->user);
                $notificacao->mensagem(["header"=>"Validação de email", 
                
                "body"=>'<p>Valide seu esse e-mail é seu mesmo e complete sua conta.</p>
            
            <div class="code-container">
                <div class="verification-code">'.$token.'</div>
                <div class="expiry-notice">Este código expira em 3 minutos.</div>
            </div>
            <p>Copie o código acima na caixa de verificação.</p>
            
        
            
            <div class="security-notice">
                <p><strong>Nota de segurança:</strong> Se você não se cadastrou em nosso site, por favor ignore este email ou entre em contato com nosso suporte imediatamente, pois alguém pode estar tentando criar uma conta com esse email.</p>
            </div>']);
                $notificacao->email();
                

            return ["sucesso" => true, "mensagem" => "Token gerado com sucesso"];
        } else {
            return ["erro" => true, "mensagem" => "Erro ao gerar token: " . $this->conn->error];
        }
    }
    
    public function validar(){
        $token = $_POST["token"] ?? false;
        
        if(!$token){
            return ["erro" => true, "mensagem" => "Token não informado"];
        }
        
        if(!$this->tipo || !in_array($this->tipo, ['email', 'telefone'])){
            return ["erro" => true, "mensagem" => "Tipo de validação inválido"];
        }
        
        $tipoId = ($this->tipo == 'email') ? 1 : 2;
        
        // Buscar token
        $sql = "SELECT * FROM usuarios_validacoes 
                WHERE usuario_validacao_autor = ? AND usuario_validacao_token = ? AND usuario_validacao_tipo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $this->user, $token, $tipoId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 0){
            return ["erro" => true, "mensagem" => "Token inválido ou expirado"];
        }
        
        $row = $result->fetch_assoc();
        $tokenTime = strtotime($row['usuario_validacao_data']);
        $currentTime = time();
        
        // Verificar se token expirou
        if(($currentTime - $tokenTime) >= 300){
            $this->limparTokensVencidos();
            return ["erro" => true, "mensagem" => "Token expirado"];
        }
        
        // Token válido, remover da tabela
        $sql = "DELETE FROM usuarios_validacoes WHERE usuario_validacao_autor = ? AND usuario_validacao_tipo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $this->user, $tipoId);
        $stmt->execute();
        
        // Destruir sessão de credenciais
        if(isset($_SESSION["validaCredenciais"])){
            unset($_SESSION["validaCredenciais"]);
        }
        
        // Atualizar ou inserir na tabela usuarios_meta
        $chave = ($this->tipo == 'email') ? 'email-valido' : 'telefone-valido';
        $valor = '1';
        
        // Verificar se já existe registro
        $sql = "SELECT * FROM usuarios_meta WHERE um_usuario = ? AND um_chave = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $this->user, $chave);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            // Atualizar registro existente
            $sql = "UPDATE usuarios_meta SET um_valor = ? WHERE um_usuario = ? AND um_chave = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sis", $valor, $this->user, $chave);
        } else {
            // Inserir novo registro
            $sql = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $this->user, $chave, $valor);
        }
        
        if($stmt->execute()){
            return ["sucesso" => true, "mensagem" => ucfirst($this->tipo) . " validado com sucesso"];
        } else {
            return ["erro" => true, "mensagem" => "Erro ao atualizar registro: " . $this->conn->error];
        }
    }
    
    private function gerarTokenRandomico($tamanho){
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        
        for($i = 0; $i < $tamanho; $i++){
            $token .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        return $token;
    }
    
    public function close(){
        if($this->conn){
           $this->conn->close();
        }
        

    }
}

$valida = new Validacao();
$resposta = $valida->render();
$valida->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>