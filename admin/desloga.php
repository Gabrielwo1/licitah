<?php
include __DIR__."/seguranca-include.php";

$sessaoHash = $_SESSION["sessao_hash"] ?? null;
$userId = $_SESSION["id"] ?? null;


if ($sessaoHash && $userId) {
    try {
        include_once __DIR__ . '/conn.php';
        $conn = conn();
        
        $stmt = $conn->prepare("DELETE FROM sessoes WHERE sessao_hash = ?");
        $stmt->bind_param("s", $sessaoHash);
        $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        error_log("Erro no logout: " . $e->getMessage());
    }
}

session_unset();
session_destroy();

        
session_start();
        
$novoToken = $seguranca->gerarCsrf();
echo json_encode([
    "sucesso" => true,
    "mensagem" => "Usuário deslogado com sucesso",
    "token" => $novoToken
]);

?>