<?php
include __DIR__."/../../../../admin/conn.php";

class Install {
    public $conn;
    
    function __construct() {
        $this->conn = conn();
    }

    function descompacta() {
        if (!file_exists(__DIR__."/dados.json")) {
            $zipFile = __DIR__ . "/dados.zip";
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo(__DIR__);
                $zip->close();
            }
        }
    }

    function hasher($tamanho = 32) {
        return substr(bin2hex(random_bytes($tamanho / 2)), 0, $tamanho);
    }

    function regiao($region, $region_id) {
        static $regioesCache = [];
        if (isset($regioesCache[$region_id])) return $regioesCache[$region_id];

        $seleciona = "SELECT * FROM enderecos_regioes WHERE enderecos_regiao_externo='$region_id'";
        $resultado = $this->conn->query($seleciona);
        if ($resultado->num_rows == 0) {
            $hash = $this->hasher(32);
            $url = $this->hasher(10);
            $cadastra = "INSERT INTO enderecos_regioes (enderecos_regiao_nome, enderecos_regiao_hash, enderecos_regiao_url, enderecos_regiao_externo) 
                         VALUES ('$region', '$hash', '$url', '$region_id')";
            $this->conn->query($cadastra);
            $regiao = $this->conn->insert_id;
        } else {
            $dado = $resultado->fetch_assoc();
            $regiao = $dado["enderecos_regiao_id"];
        }
        $regioesCache[$region_id] = $regiao;
        return $regiao;
    }

    function subregiao($regiao, $subregion_id, $subregion) {
        static $subregioesCache = [];
        if (isset($subregioesCache[$subregion_id])) return $subregioesCache[$subregion_id];

        $seleciona = "SELECT * FROM enderecos_subregioes WHERE enderecos_subregiao_externo='$subregion_id'";
        $resultado = $this->conn->query($seleciona);
        if ($resultado->num_rows == 0) {
            $hash = $this->hasher(32);
            $url = $this->hasher(10);
            $cadastra = "INSERT INTO enderecos_subregioes (enderecos_subregiao_nome, enderecos_subregiao_regiao, enderecos_subregiao_externo, enderecos_subregiao_hash, enderecos_subregiao_url)
                         VALUES ('$subregion', '$regiao', '$subregion_id', '$hash', '$url')";
            $this->conn->query($cadastra);
            $subregiao = $this->conn->insert_id;
        } else {
            $dado = $resultado->fetch_assoc();
            $subregiao = $dado["enderecos_subregiao_id"];
        }
        $subregioesCache[$subregion_id] = $subregiao;
        return $subregiao;
    }

    function pais($regiao, $subregiao, $item) {
        $id = $this->conn->real_escape_string($item['id']);
        $seleciona = "SELECT * FROM enderecos_paises WHERE enderecos_pais_externo='$id'";
        $resultado = $this->conn->query($seleciona);
        if ($resultado->num_rows == 0) {
            $hash = $this->hasher(32);
            $url = $this->hasher(10);
            $nome = $this->conn->real_escape_string($item['translations']['pt-BR'] ?? $item['name']);
            $capital = $this->conn->real_escape_string($item['capital'] ?? '');
            $nationality = $this->conn->real_escape_string($item['nationality'] ?? '');
            $tld = $this->conn->real_escape_string($item['tld'] ?? '');
            $native = $this->conn->real_escape_string($item['native'] ?? '');
            $emoji = $this->conn->real_escape_string($item['emoji'] ?? '');
            $currency = $this->conn->real_escape_string($item['currency'] ?? '');
            $simbolomoeda = $this->conn->real_escape_string($item['currency_symbol'] ?? '');
            $currency_name = $this->conn->real_escape_string($item['currency_name'] ?? '');
            $traducoes = $this->conn->real_escape_string(json_encode($item['translations'], JSON_UNESCAPED_UNICODE));
            $timezones = $this->conn->real_escape_string(json_encode($item['timezones'], JSON_UNESCAPED_UNICODE));
            $latitude = $this->conn->real_escape_string($item['latitude']);
            $longitude = $this->conn->real_escape_string($item['longitude']);
            $iso2 = $item['iso2'];
            $iso3 = $item['iso3'];
            $slug = $this->slugfy($nome);
            $cadastra = "INSERT INTO enderecos_paises (enderecos_pais_nome, enderecos_pais_capital, enderecos_pais_nascionalidade, enderecos_pais_tld, enderecos_pais_nativo, enderecos_pais_emoji, 
                        enderecos_pais_moeda, enderecos_pais_moedasimbolo, enderecos_pais_moedanome, enderecos_pais_traducoes, enderecos_pais_timezones, enderecos_pais_regiao, enderecos_pais_subregiao, enderecos_pais_latitude, 
                        enderecos_pais_longitude, enderecos_pais_externo, enderecos_pais_hash, enderecos_pais_url,enderecos_pais_iso3,enderecos_pais_iso2, enderecos_pais_slug)
                        VALUES ('$nome', '$capital', '$nationality', '$tld', '$native', '$emoji', '$currency', '$simbolomoeda',  '$currency_name', '$traducoes', '$timezones', '$regiao', '$subregiao', '$latitude', '$longitude', 
                        '$id', '$hash', '$url', '$iso3', '$iso2', '$slug')";
            $this->conn->query($cadastra);
            $pais = $this->conn->insert_id;
        } else {
            $dado = $resultado->fetch_assoc();
            $pais = $dado["enderecos_pais_id"];
        }
        return $pais;
    }

    function estado($regiao, $subregiao, $pais, $item) {
        $id = $this->conn->real_escape_string($item['id']);
        $seleciona = "SELECT * FROM enderecos_estados WHERE endereco_estado_externo='$id'";
        $resultado = $this->conn->query($seleciona);
        if ($resultado->num_rows == 0) {
            $hash = $this->hasher(32);
            $url = $this->hasher(10);

            $name = $this->conn->real_escape_string($item['name']);
            $slug = $this->slugfy($name);
            $cadastra = "INSERT INTO enderecos_estados (endereco_estado_nome, endereco_estado_externo, endereco_estado_codigo, endereco_estado_latitude, endereco_estado_longitude, 
                        endereco_estado_regiao, endereco_estado_subregiao, endereco_estado_pais, endereco_estado_hash, endereco_estado_url, endereco_estado_slug) 
                        VALUES ('$name', '$id', '{$item['state_code']}', '{$item['latitude']}', '{$item['longitude']}', '$regiao', '$subregiao', '$pais', '$hash', '$url', '$slug')";
            $this->conn->query($cadastra);
            $estado = $this->conn->insert_id;
        } else {
            $dado = $resultado->fetch_assoc();
            $estado = $dado["endereco_estado_id"];
        }
        return $estado;
    }
    
    function  slugfy($string) {

    $string = mb_strtolower($string, 'UTF-8');
    
    $string = preg_replace('/[áàâãäå]/u', 'a', $string);
    $string = preg_replace('/[éèêë]/u', 'e', $string);
    $string = preg_replace('/[íìîï]/u', 'i', $string);
    $string = preg_replace('/[óòôõö]/u', 'o', $string);
    $string = preg_replace('/[úùûü]/u', 'u', $string);
    $string = preg_replace('/[ç]/u', 'c', $string);
    $string = preg_replace('/[ñ]/u', 'n', $string);
    
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    
    $string = preg_replace('/[\s-]+/', '-', $string);
    
    $string = trim($string, '-');
    
    return $string;
}


    function load() {
    // Carregar o conteúdo do arquivo JSON
    
    
    $seleciona = "SELECT count(*) as total FROM enderecos_cidades";
    $resultado = $this->conn->query($seleciona);
    $dado = $resultado->fetch_assoc();
    if($dado["total"] > 0){
        return;
    }
    
    $conteudo = file_get_contents(__DIR__ . "/dados.json");
    $array = json_decode($conteudo, true);

    // Verifica se o JSON foi decodificado corretamente
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erro ao decodificar JSON: " . json_last_error_msg());
    }

    foreach ($array as $item) {
        $regiao = $this->regiao($item["region"], $item["region_id"]);
        $subregiao = $this->subregiao($regiao, $item["subregion_id"], $item["subregion"]);
        $pais = $this->pais($regiao, $subregiao, $item);
        
        foreach ($item['states'] as $state) {
            $estado = $this->estado($regiao, $subregiao, $pais, $state);
            $cidades = $state["cities"];
            $array = [];

            foreach ($cidades as $cidade) {
                $id = $cidade["id"];
                $nome = $cidade["name"];
                $latitude = $cidade["latitude"];
                $longitude = $cidade["longitude"];

                array_push($array, [
                    $id, $nome, $latitude, $longitude, $regiao, $subregiao, $pais, $estado, $this->hasher(32), $this->hasher(10), $this->slugfy($nome)
                ]);
            }

            $valores = [];
            foreach ($array as $linha) {
                // Use a conexão para escapar os valores
                $linhaEscapada = array_map(function($valor) {
                    return mysqli_real_escape_string($this->conn , $valor);
                }, $linha);

                $valores[] = "('" . implode("', '", $linhaEscapada) . "')";
            }

            // Executar a consulta SQL
            if (!empty($valores)) {
                $sql = "INSERT INTO enderecos_cidades (
                    endereco_cidade_externo,
                    endereco_cidade_nome,
                    endereco_cidade_latitude,
                    endereco_cidade_longitude,
                    endereco_cidade_regiao,
                    endereco_cidade_subregiao,
                    endereco_cidade_pais,
                    endereco_cidade_estado,
                    endereco_cidade_hash,
                    endereco_cidade_url,
                    endereco_cidade_slug
                ) VALUES " . implode(', ', $valores);
                
                $this->conn->query($sql);
            }
        }
    }
}

    function deleteFileIfExists() {
        $filePath = __DIR__."/dados.json";
    // Verifica se o arquivo existe
    if (file_exists($filePath)) {
        // Tenta deletar o arquivo
        if (unlink($filePath)) {
         
        } else {
            
        }
    } else {

    }
    }
    
    
    function extra(){
        $conteudo = json_decode(file_get_contents(__DIR__."/all.json"), true);
        echo '<pre>';
        print_r($conteudo);
        echo '</pre>';
    }

    function close(){
        $this->conn->close();
    }


}

$instalacao = new Install();
$instalacao->descompacta();
$instalacao->load();
$instalacao->deleteFileIfExists();
$instalacao->extra();
$instalacao->close();
