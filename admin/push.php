<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

class Acao {
    private $setup;
    private $config;
    private $user;

    function __construct() {
        $this->setup = $this->carregarSetup();
        $this->config = $this->setup["push-notification"] ?? [];
        $this->user = $_SESSION["id"] ?? false;
        
        // Inclui arquivos necessários
        include __DIR__ . "/conn.php";
        include __DIR__ . "/bibliotecas/vendor/autoload.php";
    }
    
    private function carregarSetup(): array {
        $caminhoArquivo = __DIR__ . "/../conteudo/setup.json";
        
        // Verificações rápidas
        if (!file_exists($caminhoArquivo) || !is_readable($caminhoArquivo)) {
            return [];
        }
        
        try {
            // Lê e decodifica em uma linha, com validação
            $dados = json_decode(
                file_get_contents($caminhoArquivo) ?: '{}', 
                true, 
                512, 
                JSON_THROW_ON_ERROR
            );
            
            // Retorna notificações se existir e for array, senão array vazio
            return is_array($dados["notificacoes"] ?? null) 
                ? $dados["notificacoes"] 
                : [];
                
        } catch (JsonException $e) {
            error_log("Erro JSON em setup.json: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log("Erro ao carregar setup.json: " . $e->getMessage());
            return [];
        }
    }
    
    function render(): array {
        // Verifica se push notification está ativado
        if (!($this->setup["configuracoes"]["push"] ?? false)) {
            return ["erro" => true, "mensagem" => "Push Notification Desativado"];
        }
        
        // Verifica se push está configurado
        if (empty($this->config) || $this->config["provedor"] == "0") {
            return ["erro" => true, "mensagem" => "Push não configurado"];
        }
        
        // Processa conforme o provedor
        switch(intval($this->config["provedor"])) {
            case 1:
                return $this->processarPusher();
            case 2:
                return $this->processarFirebase();
            default:
                return ["erro" => true, "mensagem" => "Provedor não suportado"];
        }
    }
    
    private function processarPusher(): array {
        try {
            $beamsClient = new \Pusher\PushNotifications\PushNotifications([
                "instanceId" => $this->config["instanceid"],
                "secretKey" => $this->config["secretkey"],
            ]);
            
            $userId = "user-" . $this->user;
            $token = $beamsClient->generateToken($userId);
            
            return $token;
        } catch (Exception $e) {
            error_log("Erro no Pusher: " . $e->getMessage());
            return ["erro" => true, "mensagem" => "Erro ao gerar token Pusher"];
        }
    }
    
    private function processarFirebase(): array {
        // Implementar Firebase aqui
        return ["erro" => true, "mensagem" => "Firebase ainda não implementado"];
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>