<?

error_reporting(E_ALL);
ini_set("display_errors", 1);

include __DIR__.'/admin/conn.php';

$arquivo_sql = __DIR__."/licitacoes_itens.sql";

function executarArquivoSQL($arquivo_sql) {
    // Verifica se o arquivo existe
    if (!file_exists($arquivo_sql)) {
        die("Erro: O arquivo $arquivo_sql não foi encontrado.");
    }

    // Tenta conectar ao banco de dados
    try {
        $conexao = conn();
        echo "Conexão com o banco de dados estabelecida com sucesso.<br>";

        // Abre o arquivo para leitura
        $handle = fopen($arquivo_sql, "r");
        if ($handle === false) {
            die("Erro: Não foi possível abrir o arquivo $arquivo_sql.");
        }

        // Contador de consultas executadas com sucesso
        $contador = 0;
        $consulta = '';
        
        // Lê o arquivo linha por linha
        while (($linha = fgets($handle)) !== false) {
            // Ignora comentários e linhas vazias
            $linha = trim($linha);
            if (empty($linha) || strpos($linha, '--') === 0 || strpos($linha, '#') === 0) {
                continue;
            }
            
            // Adiciona a linha à consulta atual
            $consulta .= $linha . ' ';
            
            // Verifica se a consulta termina com ponto e vírgula
            if (substr(rtrim($linha), -1) === ';') {
                // Executa a consulta
                try {
                    if ($conexao->query($consulta)) {
                        $contador++;
                        
                        // Mostra feedback a cada 100 consultas
                        if ($contador % 100 === 0) {
                            echo "Executadas $contador consultas até agora...<br>";
                            flush(); // Força a saída para o navegador
                        }
                    } else {
                        echo "Erro na consulta: " . $conexao->error . "<br>";
                        echo "Consulta problemática: " . $consulta . "<br>";
                    }
                } catch (Exception $e) {
                    echo "Erro na consulta: " . $e->getMessage() . "<br>";
                    echo "Consulta problemática: " . $consulta . "<br>";
                }
                
                // Limpa a consulta para a próxima
                $consulta = '';
            }
        }
        
        // Fecha o arquivo
        fclose($handle);
        $conexao->close();
        echo "Foram executadas $contador consultas com sucesso.<br>";
        echo "O arquivo SQL foi processado com êxito!";
        
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}

// Exibe informações sobre limites de memória (opcional)
echo "Limite de memória atual: " . ini_get('memory_limit') . "<br>";

// Executa a função
executarArquivoSQL($arquivo_sql);
?>