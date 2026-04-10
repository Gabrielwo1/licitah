<?php


class Tema {
    public $conn;

    function __construct() {
        $this->conn = conn();
    }

    function close() {
        $this->conn->close();
    }

    function cores($colors) {
        $chaves = [
            "cor", "primaria", "on-primaria", "primaria-container", "on-primaria-container", "secundaria", "on-secundaria", "secundaria-container", "on-secundaria-container",
            "terciaria", "on-terciaria", "terciaria-container", "on-terciaria-container", "background", "on-background", "surface", "on-surface", "error", "on-error", "error-container",
            "on-error-container", "outline", "surface-variant", "on-surface-variant", "inverse-surface", "on-inverse-surface", "inverse-primary"
        ];

        $css = [];
        foreach ($chaves as $chave) {
            if (isset($colors[$chave])) {
                $css["--nown-n-$chave"] = $colors[$chave];
            }
        }
        return $css;
    }

    function getTema() {
        $seleciona = "SELECT * FROM configuracoes WHERE config_arquivo IN('tema-light', 'tema-dark')";
        $resultado = $this->conn->query($seleciona);
        $array = ["tema-light" => [], "tema-dark" => []];

        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $array[$dado["config_arquivo"]][$dado["config_chave"]] = $dado["config_valor"];
            }
        }

        return $array;
    }

    function minificarCSS($css) {
    // Remover espaços em branco, quebras de linha e tabulações desnecessárias
    return preg_replace('/\s+/', ' ', $css);
    }


    function render() {
        $tema = $this->getTema();
        $css = [];

        if (!empty($tema["tema-light"]) && $tema["tema-light"]["ativo"] == "true") {
                $css["light"] = $this->cores($tema["tema-light"]);
        }

        if (!empty($tema["tema-dark"]) && $tema["tema-dark"]["ativo"] === "true") {
            $css["dark"] = $this->cores($tema["tema-dark"]);
        }

        $arquivoCSS = __DIR__ . "/../../../assets/tema.css";
        $conteudoCSS = ":root {
        ";



        // Gerar variáveis para o tema claro
        if (isset($css['light']) && $tema["tema-light"]["ativo"] === "true") {
            foreach ($css['light'] as $var => $valor) {
                $conteudoCSS .= "    $var: $valor;
            ";
            }
        }

        $conteudoCSS .= "}
        body.light {
";

        if (isset($css['light'])) {
            foreach ($css['light'] as $var => $valor) {
                $conteudoCSS .= "    $var: $valor;
";
            }
        }

        $conteudoCSS .= "}
        body.dark {
";

        // Gerar variáveis para o tema escuro
        if (isset($css['dark'])) {
            foreach ($css['dark'] as $var => $valor) {
                $conteudoCSS .= "    $var: $valor;
";
            }
        }

        $conteudoCSS .= "}
        ";

        // Salvar no arquivo CSS
        file_put_contents($arquivoCSS,  $this->minificarCSS($conteudoCSS));

        return [
            "status" => "success",
            "message" => "Arquivo CSS gerado com sucesso.",
            "path" => $arquivoCSS
        ];
    }
}




?>
