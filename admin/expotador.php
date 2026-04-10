<?php

require __DIR__.'/bibliotecas/vendor/autoload.php'; // Para bibliotecas como Dompdf

use Dompdf\Dompdf;

class Exportador {
    private $modulo;
    private $dados;
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
        $this->page = 1;
        $this->size = 9999999999999999999999;
        $this->ordeIndex = $_POST["ordeIndex"] ?? '{}';
        $this->filtros = $_POST["ordeIndex"] ?? '{}';
        $this->dados = $this->buscarDados();
    }
 
    private function buscarDados() {
        // Simulação de dados do banco de dados
        return [
            ['ID' => 1, 'Nome' => 'João', 'Email' => 'joao@email.com'],
            ['ID' => 2, 'Nome' => 'Maria', 'Email' => 'maria@email.com']
        ];
    }

    function render() {
        switch ($this->arquivo) {
            case 'html':
                $this->exportarHTML();
                break;
            case 'excel':
                $this->exportarExcel();
                break;
            case 'csv':
                $this->exportarCSV();
                break;
            case 'pdf':
                $this->exportarPDF();
                break;
            case 'imprimir':
                $this->exportarImprimir();
                break;
            default:
                echo "Formato inválido ou não especificado.";
                break;
        }
    }

    private function exportarHTML() {
        echo "<h1>Dados Exportados (HTML)</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($this->dados[0]) as $coluna) {
            echo "<th>$coluna</th>";
        }
        echo "</tr>";
        foreach ($this->dados as $linha) {
            echo "<tr>";
            foreach ($linha as $valor) {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    private function exportarExcel() {
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=dados.xls");

        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($this->dados[0]) as $coluna) {
            echo "<th>$coluna</th>";
        }
        echo "</tr>";
        foreach ($this->dados as $linha) {
            echo "<tr>";
            foreach ($linha as $valor) {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    private function exportarCSV() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="dados.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($this->dados[0]));

        foreach ($this->dados as $linha) {
            fputcsv($output, $linha);
        }
        fclose($output);
    }

    private function exportarPDF() {
        $dompdf = new Dompdf();
        $html = '<h1>Relatório de Dados (PDF)</h1><table border="1"><tr>';

        foreach (array_keys($this->dados[0]) as $coluna) {
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
        $dompdf->stream("dados.pdf", ["Attachment" => 1]);
    }

    private function exportarImprimir() {
        echo "<h1>Relatório de Dados para Impressão</h1>";
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($this->dados[0]) as $coluna) {
            echo "<th>$coluna</th>";
        }
        echo "</tr>";
        foreach ($this->dados as $linha) {
            echo "<tr>";
            foreach ($linha as $valor) {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<script>window.print();</script>";
    }
}

// Instancia e chama o método
$exportador = new Exportador();
$resposta = $exportador->render();

?>
