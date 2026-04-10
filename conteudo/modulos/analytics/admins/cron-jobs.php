<?php
include __DIR__."/../../../../admin/conn.php";
$conn = conn();

// Desabilitar autocommit para usar transação
$conn->autocommit(FALSE);

try {
    // Iniciar transação
    $conn->begin_transaction();
    
    // 1. Primeiro, atualizar tempo de vida das últimas páginas
    $sql = "UPDATE analytics_acessos ac
            INNER JOIN (
                SELECT 
                    a.analytics_acesso_id,
                    TIMESTAMPDIFF(SECOND, a.analytics_acesso_data, NOW()) as tempo_vida
                FROM analytics_acessos a
                INNER JOIN (
                    SELECT 
                        analytics_acesso_sessao,
                        analytics_acesso_aba,
                        MAX(analytics_acesso_data) as ultima_data
                    FROM analytics_acessos
                    WHERE analytics_acesso_vida = 0
                    GROUP BY analytics_acesso_sessao, analytics_acesso_aba
                ) ultimas ON a.analytics_acesso_sessao = ultimas.analytics_acesso_sessao 
                          AND a.analytics_acesso_aba = ultimas.analytics_acesso_aba 
                          AND a.analytics_acesso_data = ultimas.ultima_data
                INNER JOIN analytics_abas ab ON ab.analytics_aba_id = a.analytics_acesso_aba
                WHERE ab.analytics_aba_ativa = '1' 
                AND ab.analytics_aba_ultima < DATE_SUB(NOW(), INTERVAL 40 SECOND)
            ) calc ON ac.analytics_acesso_id = calc.analytics_acesso_id
            SET ac.analytics_acesso_vida = calc.tempo_vida";
    
    $conn->query($sql);
    
    // 2. Atualizar abas inativas
    $sql = "UPDATE analytics_abas 
            SET analytics_aba_ativa = '0', 
                analytics_aba_visivel = '0' 
            WHERE analytics_aba_ativa = '1' 
            AND analytics_aba_ultima < DATE_SUB(NOW(), INTERVAL 40 SECOND)";
    
    $conn->query($sql);
    
    // 3. Atualizar sessões inativas
    $sql = "UPDATE analytics 
            SET analytic_ativo = '0' 
            WHERE analytic_ativo = '1' 
            AND analytic_ultima < DATE_SUB(NOW(), INTERVAL 40 SECOND)";
    
    $conn->query($sql);
    
    // Confirmar transação
    $conn->commit();
    
} catch (Exception $e) {
    // Reverter em caso de erro
    $conn->rollback();
    echo "Erro: " . $e->getMessage();
}

// Reativar autocommit
$conn->autocommit(TRUE);
$conn->close();
?>