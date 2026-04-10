<?php
/**
 * Exclusão de Registros Antigos
 * 
 * Esta página exclui registros com mais de 6 meses
 */
// Configurações iniciais
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 300); // 5 minutos
ini_set('memory_limit', '256M');

// Incluir arquivo de conexão
try {
    include __DIR__."/../../../../admin/conn.php";
} catch (Exception $e) {
    die("Erro ao incluir arquivo de conexão: " . $e->getMessage());
}

// Definir número de meses (padrão: 6)
$meses = isset($_GET['meses']) ? (int)$_GET['meses'] : 6;

// Definir a data limite (6 meses atrás)
$data_limite = date('Y-m-d', strtotime("-$meses months"));

// Conexão com o banco
$conn = conn();

// Função para executar comandos DELETE e retornar o número de linhas afetadas
function executarDelete($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $rows_affected = $stmt->affected_rows;
    $stmt->close();
    
    return $rows_affected;
}

// Iniciar transação
$conn->begin_transaction();

try {
    // 1. Primeiro excluir os metadados dos itens (mais profundo na hierarquia)
    // $sql_itens_meta = "
    //     DELETE FROM licitacoes_itens_meta 
    //     WHERE lim_licitacoes_item IN (
    //         SELECT licitacoes_item_id FROM licitacoes_itens
    //         WHERE licitacoes_item_licitacao IN (
    //             SELECT licitacao_id FROM licitacoes WHERE DATE(licitacao_att) < ?
    //         )
    //     )
    // ";
    // $meta_items_deleted = executarDelete($conn, $sql_itens_meta, [$data_limite]);
    // echo "Metadados de itens excluídos: $meta_items_deleted<br>";
    
    // 2. Em seguida, excluir os itens
    $sql_itens = "
        DELETE FROM licitacoes_itens 
        WHERE licitacoes_item_licitacao IN (
            SELECT licitacao_id FROM licitacoes WHERE DATE(licitacao_att) < ?
        )
    ";
    $items_deleted = executarDelete($conn, $sql_itens, [$data_limite]);
    echo "Itens excluídos: $items_deleted<br>";
    
    // 3. Depois, excluir os metadados das licitações
    $sql_meta = "
        DELETE FROM licitacoes_meta 
        WHERE lm_licitacao IN (
            SELECT licitacao_id FROM licitacoes WHERE DATE(licitacao_att) < ?
        )
    ";
    $meta_deleted = executarDelete($conn, $sql_meta, [$data_limite]);
    echo "Metadados de licitações excluídos: $meta_deleted<br>";
    
    // 4. Por último, excluir as licitações
    $sql_licitacoes = "
        DELETE FROM licitacoes WHERE DATE(licitacao_att) < ?
    ";
    $licitacoes_deleted = executarDelete($conn, $sql_licitacoes, [$data_limite]);
    echo "Licitações excluídas: $licitacoes_deleted<br>";
    
    
        $sql_itens_meta = "
        DELETE FROM licitacoes_itens_meta 
        WHERE lim_licitacoes_item NOT IN (
            SELECT licitacoes_item_id FROM licitacoes_itens
        )
    ";
    $meta_items_deleted = executarDelete($conn, $sql_itens_meta, []);
    echo "Metadados de itens órfãos excluídos: $meta_items_deleted<br>";
    
    // Confirmar todas as alterações
    $conn->commit();

} catch (Exception $e) {
    // Em caso de erro, reverter todas as alterações
    $conn->rollback();
    die("Erro durante a exclusão: " . $e->getMessage());
}
?>