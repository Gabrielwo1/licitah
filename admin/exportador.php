<?php
session_start();

$exportador = true;

require __DIR__.'/bibliotecas/vendor/autoload.php'; 
include __DIR__."/parser.php";
include __DIR__."/table.php";

use Dompdf\Dompdf;

class Exportador {
    private $modulo;
    private $dados;
    private $diretorio;
    private $hash;
    private $cabecalhos;
    private $arquivo;
    private $master;
    private $identificador;
    private $ordeIndex;
    private $filtros;
    private $user;
    private $map;
    
    function __construct() {
        $this->arquivo = $_POST["arquivo"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->master = $_POST["master"] ?? false;
        $this->identificador = $_POST["identificador"] ?? false;
        $this->ordeIndex = $_POST["ordeIndex"] ?? '{}';
        $this->filtros = $_POST["filtros"] ?? '{}'; // Corrigido: estava usando ordeIndex
        $this->user = $_SESSION["id"] ?? false;
        $this->diretorio = __DIR__."/../conteudo/provisorio";
        $this->hash = $this->gerarHash();
        $this->dados = $this->buscarDados();
    }
 
    private function buscarDados() {
         
  
            $_POST["acao"] = "tabela";
            $_POST["page"] = 1;
            $_POST["size"] = 999999999;
            
            $tabela = new Tabela();
            $resposta = $tabela->render();
            $tabela->close();
        
            
            $array = [];
            $linhas = $resposta["r"];
            $this->map = $resposta["m"];
            $extrangeira = $resposta["e"] ?? [];
            
        
            
            $cabecalhos = [];
            $this->cabecalhos = [];
            foreach($resposta["m"] as $chave=>$valor){
                if(empty($valor["invisivel"])){
                    $valor["key"] = $chave;
                   
                    $cabecalhos[intval($valor["posicao"])] = $valor;
                }
               
            }
            usort($cabecalhos, function ($a, $b) {
                return $a['posicao'] <=> $b['posicao'];
            });
            
            foreach($cabecalhos as $c){
                 $this->cabecalhos[] = $c["nome"];
            }
            
            $dados = [];
    
            foreach($linhas as $linha){
                $dado = [];
                foreach($cabecalhos as $cab){
               
                    
                    $render = $cab["render"] ?? false;
                   
                    switch($render){
                        case 'personalizado':
                            $valor = $cab["regra"][$linha[$cab["key"]]] ?? $linha[$cab["key"]];
                            break;
                        default:
                            
                            if(!empty($cab["estrangeira"])){
                                $valor = $extrangeira[$cab["key"]][$linha[$cab["key"]]] ?? "tereaa";
                            }else{
                                $valor = $linha[$cab["key"]];
                            }
                            
        
                            break;
                    }
                    
                    
              
                    
                    
                    $dado[] = $valor;
                }
                $dados[] = $dado;
                
            }
            
        
            return $dados;
    }

    /**
     * Gera um hash aleatório de 20 caracteres
     */
    private function gerarHash() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = '';
        for ($i = 0; $i < 20; $i++) {
            $hash .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $hash;
    }

    /**
     * Verifica se o diretório existe e o cria se necessário
     */
    private function verificarDiretorio() {
        if (!file_exists($this->diretorio)) {
            mkdir($this->diretorio, 0755, true);
        }
    }

    function render() {
        $this->verificarDiretorio();
        $resultado = "";

        switch ($this->arquivo) {
            case 'html':
                $resultado = $this->exportarHTML();
                break;
            case 'excel':
                $resultado = $this->exportarExcel();
                break;
            case 'csv':
                $resultado = $this->exportarCSV();
                break;
            case 'pdf':
                $resultado = $this->exportarPDF();
                break;
            case 'imprimir':
                $resultado = $this->exportarImprimir();
                break;
            default:
                echo "Formato inválido ou não especificado.";
                break;
        }
        
        return $resultado;
    }

    private function exportarHTML() {
        $nomeArquivo = "{$this->hash}.html";
        $caminhoArquivo = "{$this->diretorio}/{$nomeArquivo}";
        
        $conteudo = "<h1>Dados Exportados (HTML)</h1>";
        $conteudo .= "<table border='1'>";
        $conteudo .= "<tr>";
        
        // Usar os cabecalhos dinâmicos como você fez no PDF
        foreach ($this->cabecalhos as $coluna) {
            $conteudo .= "<th>$coluna</th>";
        }
        
        $conteudo .= "</tr>";
        foreach ($this->dados as $linha) {
            $conteudo .= "<tr>";
            foreach ($linha as $valor) {
                $conteudo .= "<td>$valor</td>";
            }
            $conteudo .= "</tr>";
        }
        $conteudo .= "</table>";
        
        file_put_contents($caminhoArquivo, $conteudo);
        
        return $this->hash;
    }

    private function exportarExcel() {
        $nomeArquivo = "{$this->hash}.xls";
        $caminhoArquivo = "{$this->diretorio}/{$nomeArquivo}";
        
        $conteudo = "<table border='1'>";
        $conteudo .= "<tr>";
        
        // Usar os cabecalhos dinâmicos
        foreach ($this->cabecalhos as $coluna) {
            $conteudo .= "<th>$coluna</th>";
        }
        
        $conteudo .= "</tr>";
        foreach ($this->dados as $linha) {
            $conteudo .= "<tr>";
            foreach ($linha as $valor) {
                $conteudo .= "<td>$valor</td>";
            }
            $conteudo .= "</tr>";
        }
        $conteudo .= "</table>";
        
        file_put_contents($caminhoArquivo, $conteudo);
        
        return $this->hash;
    }

    private function exportarCSV() {
        $nomeArquivo = "{$this->hash}.csv";
        $caminhoArquivo = "{$this->diretorio}/{$nomeArquivo}";
        
        $fp = fopen($caminhoArquivo, 'w');
        
        // Usar os cabecalhos dinâmicos
        fputcsv($fp, $this->cabecalhos);

        foreach ($this->dados as $linha) {
            fputcsv($fp, $linha);
        }
        fclose($fp);
        
        return $this->hash;
    }

    private function exportarPDF() {
        $nomeArquivo = "{$this->hash}.pdf";
        $caminhoArquivo = "{$this->diretorio}/{$nomeArquivo}";
        
        $dompdf = new Dompdf();
        $html = '<h1>Relatório de Dados (PDF)</h1><table border="1"><tr>';

        foreach ($this->cabecalhos as $coluna) {
            $html .= "<th>$coluna</th>";
        }
        $html .= '</tr>';

        foreach ($this->dados as $linha) {
            $html .= '<tr>';
            foreach ($linha as $valor) {
                $html .= "<td>$valor</td>";
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Salva o PDF no arquivo ao invés de transmitir diretamente
        file_put_contents($caminhoArquivo, $dompdf->output());
        
        return $this->hash;
    }

    private function exportarImprimir() {
        $nomeArquivo = "{$this->hash}.html";
        $caminhoArquivo = "{$this->diretorio}/{$nomeArquivo}";
        
        $conteudo = "<h1>Relatório de Dados para Impressão</h1>";
        $conteudo .= "<table border='1'>";
        $conteudo .= "<tr>";
        
        // Usar os cabecalhos dinâmicos
        foreach ($this->cabecalhos as $coluna) {
            $conteudo .= "<th>$coluna</th>";
        }
        
        $conteudo .= "</tr>";
        foreach ($this->dados as $linha) {
            $conteudo .= "<tr>";
            foreach ($linha as $valor) {
                $conteudo .= "<td>$valor</td>";
            }
            $conteudo .= "</tr>";
        }
        $conteudo .= "</table>";
        $conteudo .= "<script>window.print();</script>";
        
        file_put_contents($caminhoArquivo, $conteudo);
        
        return $this->hash;
    }
}


// Instancia e chama o método
$exportador = new Exportador();
$hash = $exportador->render();

// Retorna o hash como resposta para o usuário
if ($hash) {
    echo json_encode(['sucesso' => true , 'hash' => $hash]);
} else {
    echo json_encode(['erro' => true, 'message' => 'Erro ao gerar o arquivo']);
}
?>