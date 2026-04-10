<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include __DIR__."/../../../../admin/conn.php";
$conn = conn();

$pagina = intval($_GET["pagina"] ?? 1);

$date = new DateTime();
// Subtract one day
$date->modify('-1 day');
// Format the date in 'YYYY-MM-DD'
$yesterday = $date->format('Y-m-d');

$hoje = date('Y-m-d');

$modalidades = [1, 2, 3, 5, 6 , 7, 20, 22, 33, 44, 57];

$modalidade = intval($_GET["modalidade"] ?? 1);

// URL base para a API
$url_base = "https://dadosabertos.compras.gov.br/modulo-contratacoes/1_consultarContratacoes_PNCP_14133";
$data_inicial = "2024-01-01";
$data_final = "2024-11-19";

// Fazendo a requisição e obtendo os dados
$url = "{$url_base}?pagina={$pagina}&tamanhoPagina=500&dataPublicacaoPncpInicial={$data_inicial}&dataPublicacaoPncpFinal={$hoje}&codigoModalidade={$modalidade}";
$conteudo = file_get_contents($url);

// Decodificando o JSON
$array = json_decode($conteudo, true);
$resultados = $array["resultado"] ?? [];

// Preparando os valores para inserção
$valores = [];
if (!empty($resultados)) {
    foreach ($resultados as $resultado) {
        $id = preg_replace('/\D/', '', $conn->real_escape_string($resultado["idCompra"]));
        $processo = preg_replace('/[^a-zA-Z0-9 ]/', '', $conn->real_escape_string($resultado["processo"]));
        $valores[] = "('$id', '$processo')";
    }
}

$query = "
    ALTER TABLE licitacoes_parse 
    ADD CONSTRAINT unique_licitacao_pase_idgov UNIQUE (licitacao_pase_idgov);
";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    
}


// Inserindo os dados no banco
if (!empty($valores)) {
    $valores_sql = implode(",", $valores);
    $cadastra = "INSERT INTO licitacoes_parse (licitacao_pase_idgov, licitacao_pase_processo)
        VALUES $valores_sql
        ON DUPLICATE KEY UPDATE
            licitacao_pase_processo = licitacao_pase_processo;
    ";
    $conn->query($cadastra);
}



$conn->close();

function getDomain() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return "$protocol://$host";
}


?>

<script>
<?
    $continua = true;
    if (!empty($array)) {
        if(intval($array['paginasRestantes'])){
            $proxima_pagina = $pagina + 1;
        }else{
            $proxima_pagina = 1;
        
            $i = 0;
            
            while($i < count($modalidades)){
                if($modalidades[$i] == $modalidade){
                    if(isset($modalidades[$i + 1])){
                        $modalidade = $modalidades[$i + 1];
                    }else{
                        $continua = false;
                    }
                    
                    break;
                }
                $i++;
            }
        }
        
    }
    
   if($continua){
       ?>
        setTimeout(()=>{
            window.location.href = "<?=getDomain()?>/conteudo/modulos/licitacoes/admins/api.php?pagina=<?=$proxima_pagina?>&modalidade=<?=$modalidade?>";
        }, 100)
       
       <?
   }
   ?>
    
</script>

