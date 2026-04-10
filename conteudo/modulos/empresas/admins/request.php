<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include __DIR__."/unitario.php";

// URL para a qual será feita a requisição
$url = 'https://api.casadosdados.com.br/v5/cnpj/pesquisa';

$rapido = isset($_POST["fast"]) && $_POST["fast"] == "false" ? false : true;

if(isset($_POST["dados"])){
    $dados = json_decode($_POST["dados"], true);
}



$razao = [];
if (!empty($dados["razao"])) {
    if (is_array($dados["razao"])) {
        $razao = $dados["razao"];
    } else {
        $razao[] = $dados["razao"];
    }
}

$atividade = [];
if (!empty($dados["ativdade"])) {
    if (is_array($dados["ativdade"])) {
        $atividade = $dados["ativdade"];
    } else {
        $atividade[] = $dados["ativdade"];
    }
    
    $atividade = array_map(function($item) {
        return preg_replace('/[^A-Za-z0-9]/', '', $item);
    }, $atividade);
}


$bairro = [];
if (!empty($dados["bairro"])) {
    if (is_array($dados["bairro"])) {
        $bairro = $dados["bairro"];
    } else {
        $bairro[] = $dados["bairro"];
    }
}

$cep = [];
if (!empty($dados["cep"])) {
    if (is_array($dados["cep"])) {
        $cep = $dados["cep"];
    } else {
        $cep[] = $dados["cep"];
    }
}

$cidades = [];
if (!empty($dados["cidades"])) {
    if (is_array($dados["cidades"])) {
        $cidades = $dados["cidades"];
    } else {
        $cidades[] = $dados["cidades"];
    }
}

$com_contato_telefonico = [];
if (!empty($dados["com_contato_telefonico"])) {
    if (is_array($dados["com_contato_telefonico"])) {
        $com_contato_telefonico = $dados["com_contato_telefonico"];
    } else {
        $com_contato_telefonico[] = $dados["com_contato_telefonico"];
    }
}

$com_email = [];
if (!empty($dados["com_email"])) {
    if (is_array($dados["com_email"])) {
        $com_email = $dados["com_email"];
    } else {
        $com_email[] = $dados["com_email"];
    }
}

$ddd = [];
if (!empty($dados["ddd"])) {
    if (is_array($dados["ddd"])) {
        $ddd = $dados["ddd"];
    } else {
        $ddd[] = $dados["ddd"];
    }
}

$estado = [];
if (!empty($dados["estado"])) {
    if (is_array($dados["estado"])) {
        $estado = $dados["estado"];
    } else {
        $estado[] = $dados["estado"];
    }
}

$natureza = [];
if (!empty($dados["natureza"])) {
    if (is_array($dados["natureza"])) {
        $natureza = $dados["natureza"];
    } else {
        $natureza[] = $dados["natureza"];
    }
    
    $natureza = array_map(function($item) {
        return preg_replace('/[^A-Za-z0-9]/', '', $item);
    }, $natureza);
}




/*
        "termo" => $razao,
        "atividade_principal" => $atividade,
        "natureza_juridica" => $natureza,
        "uf" => $estado,
        "municipio" => $cidades,
        "bairro" => $bairro,
        "situacao_cadastral" => $dados["situacao"] ?? "",
        "cep" => $cep,
        "ddd" => $ddd
        */

$data = [
    "busca_textual" => [
        ["nome_fantasia"=>true,
        "nome_socio"=>true,
        "razao_social"=>true, 
        "texto"=>$razao,
        "tipo_busca"=>"exata"
        ]
    ],
    "uf"=>$estado,
    "municipio"=>$cidades,
    "situacao_cadastral"=>[$dados["situacao"]],
    "bairro"=>$bairro,
    "cep" => $cep,
    "ddd" => $ddd,
    "codigo_atividade_principal"=>$atividade,
    "codigo_natureza_juridica"=>$natureza,
    "mais_filtros" => [
        "com_email" => $dados["com_contato_telefonico"] ?? false,
        "com_telefone" => $dados["com_telefone"] ?? false,
        "somente_celular" => $dados["somente_celular"] ?? false,
        "somente_filial" => $dados["somente_filial"] ?? false,
        "somente_fixo" => $dados["somente_fixo"] ?? false,
        "somente_matriz" => $dados["somente_matriz"] ?? false,
    ],
    "limite"=> 20,
    "incluir_atividade_secundaria"=> false,
    "mei"=>["excluir_optante"=>$dados["excluir_mei"] ?? false, "optante"=>$dados["somente_mei"] ?? false],
    "pagina" => intval($_POST["pagina"] ?? 1),
    "page"=>intval($_POST["pagina"] ?? 1)
];



if($dados["capital-comeco"] ?? false){
    
}
 //   "capital_social"=>["minimo"=>$dados["capital-comeco"] ?? 0 , "maximo"=>$dados["capital-fim"] ?? 0],


        
        
if($dados["abertura-comeco"] ?? false){
    $data["data_abertura"]["inicio"] = $dados["abertura-comeco"];
}

if($dados["abertura-fim"] ?? false){
    $data["data_abertura"]["fim"] = $dados["abertura-fim"];
}





// Convertendo os dados para JSON
$jsonData = json_encode($data);

// Inicializando cURL
$ch = curl_init($url);

// Configurando opções do cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
    'Accept: application/json, text/plain, */*',
    'Accept-Language: en-US,en;q=0.9',
    'Connection: keep-alive',
    'Origin: https://api.casadosdados.com.br',
    'Referer: https://api.casadosdados.com.br/',
    'api-key: 98e9d2242d9b58f0d8fd23925ffc31685318e17d25adc0ed96a83d497a2047a6ac1f5bf749a5adb89399b7bc074749134ac920b42fde550c48644ac9c8f66fdb'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// Adicionando tempo de espera para evitar detecção de comportamento automatizado


function gerarIdentificador($nome, $cnpj) {
    $nomeFormatado = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $nome));
    $nomeFormatado = str_replace(' ', '-', $nomeFormatado);
    return $nomeFormatado . '-' . $cnpj;
}


// Executando a requisição e obtendo a resposta
$response = curl_exec($ch);

// Verificando se houve erros na requisição
if (curl_errno($ch)) {
    echo 'Erro no cURL: ' . curl_error($ch);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode == 200) {
        $array = json_decode($response, true);
        if(!$rapido){
             $contador = 0;
        foreach($array["cnpjs"] as $empresa){
            $url =  gerarIdentificador($empresa["razao_social"], $empresa["cnpj"]);
            
        
            $consultor = new CNPJConsultor();
            $resultado = $consultor->consultar($url);

            $array["cnpjs"][$contador]["extra"] = $resultado;
      
   
            $contador++;
        } 
        }
      
        echo json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo 'Erro na resposta da API: HTTP Code ' . $httpCode;
    }
}

// Fechando a conexão cURL
curl_close($ch);
?>
