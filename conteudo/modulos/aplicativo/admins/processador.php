<?php

include __DIR__ . "/geradorIcone.php";

class AppConstructor {
    public $conn;
    private $grupo;
    private $dirIco;
    private $iconSizes;
    private $dominio;
    private $mapa;
    private $icone;

    function __construct($grupo = false) {
        try {
            $this->conn = conn(); // Certifique-se de que a função conn() está definida
            $this->grupo = $grupo;
            $this->dirIco = __DIR__ . "/../../../";
            
            // Usando a mesma lista da classe GeraIcone para evitar duplicação
            $this->iconSizes = [
                '71', '89', '107', '142', '284', '150', '188', '225', '300', '600',
                '310x150', '388x188', '465x225', '620x300', '1240x600', '310', '388',
                '465', '620', '1240', '44', '55', '66', '88', '176', '50', '63', '75', '100',
                '200', '775x375', '930x450', '2480x1200', '16', '20', '24', '30', '32', '36',
                '40', '48', '60', '64', '72', '80', '96', '256', '512', '192', '144', '29', '57',
                '58', '76', '87', '114', '120', '128', '152', '167', '180', '1024'
            ];
            
            // Carrega configurações do domínio
            $this->loadDomainConfig();
            
            // Carrega configurações do banco
            $this->load();
            
            // Processa ícones se necessário
            $this->processIcons();
            
            // Atualiza manifest
            $this->update();
            
        } catch (Exception $e) {
            error_log("Erro no AppConstructor: " . $e->getMessage());
            throw $e;
        }
    }
    
    private function loadDomainConfig() {
        $setupPath = __DIR__ . "/../../../../conteudo/setup.json";
        
        if (!file_exists($setupPath)) {
            throw new Exception("Arquivo de configuração setup.json não encontrado");
        }
        
        $setupContent = file_get_contents($setupPath);
        if ($setupContent === false) {
            throw new Exception("Erro ao ler arquivo setup.json");
        }
        
        $setup = json_decode($setupContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erro ao decodificar JSON do setup.json");
        }
        
        $this->dominio = $setup["dominio"] ? "https://".$setup["dominio"] : '';
    }
    
    private function processIcons() {
        if ($this->grupo !== "Icones") {
            return;
        }
        
        if (!isset($this->mapa["icones"]["icone"])) {
            return;
        }
        
        $this->icone = json_decode($this->mapa["icones"]["icone"], true);
        if (empty($this->icone) || !isset($this->icone[0])) {
            return;
        }
        
        $caminho = __DIR__ . "/../../../uploads/" . $this->icone[0];
        
        // Verifica se o arquivo existe antes de processar
        if (!file_exists($caminho)) {
            error_log("Arquivo de ícone não encontrado: " . $caminho);
            return;
        }
        
        try {
            $gerador = new GeraIcone(false);
            $gerador->geraImagens($caminho);
        } catch (Exception $e) {
            error_log("Erro ao gerar ícones: " . $e->getMessage());
        }
    }
    
    function load() {
        $seleciona = "SELECT * FROM configuracoes_modulos WHERE config_pasta = ?";
        $stmt = $this->conn->prepare($seleciona);
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar consulta SQL");
        }
        
        $pasta = 'aplicativo';
        $stmt->bind_param("s", $pasta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $mapa = [];
        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $arquivo = $dado["config_arquivo"];
                $chave = $dado["config_chave"];
                $valor = $dado["config_valor"];
                
                if (!isset($mapa[$arquivo])) {
                    $mapa[$arquivo] = [];
                }
                
                $mapa[$arquivo][$chave] = $valor;
            }
        }
        
        $stmt->close();
        $this->mapa = $mapa;
    }
    
    function hash($tamanho = 32) {
        $tamanhoBytes = ceil($tamanho / 2);
        return substr(bin2hex(random_bytes($tamanhoBytes)), 0, $tamanho);
    }

    function obterDimensoesEFormato($caminhoImagem) {
        // Verifica se o arquivo existe
        if (!file_exists($caminhoImagem)) {
            return false;
        }

        // Obtém as informações da imagem
        $infoImagem = getimagesize($caminhoImagem);

        // Verifica se a função retornou informações válidas
        if ($infoImagem === false) {
            return false;
        }

        // Extrai as dimensões e o tipo
        $largura = $infoImagem[0];
        $altura = $infoImagem[1];
        $tipo = image_type_to_mime_type($infoImagem[2]);

        // Retorna as informações em um array
        return [
            'largura' => $largura,
            'altura' => $altura,
            'tipo' => $tipo
        ];
    }
    
    function processAtalhos(&$manifesto){
        if(!isset($this->mapa["atalhos"])){
            return;
        }
        
        $atalhos = [];
        $i = 1;
        while($i < 11){
            if($this->mapa["atalhos"]["atalho".$i] == true){
                
                if(trim($this->mapa["atalhos"]["nome".$i]) && trim($this->mapa["atalhos"]["url".$i])){
                    $atalho = [
                     "name"=> trim($this->mapa["atalhos"]["nome".$i]),
                     "url"=> trim($this->mapa["atalhos"]["url".$i])
                    ];
                    
        
                    $atalhos[] = $atalho;
                }
    
                
            }
            
            $i++;
        }
        
   
        if(!empty($atalhos)){
            $manifesto["shortcuts"] = $atalhos;
        }
    }

    function update() {
        // Monta o manifesto base
        $manifesto = [
            "name" => $this->mapa["manifesto"]["nome"] ?? "",
            "short_name" => $this->mapa["manifesto"]["nomecurto"] ?? "",
            "id" => $this->mapa["manifesto"]["id"] ?? "",
            "description" => $this->mapa["manifesto"]["descricao"] ?? "",
            "theme_color" => $this->mapa["manifesto"]["cor"] ?? "",
            "background_color" => $this->mapa["manifesto"]["corbg"] ?? "",
            "start_url" => "/",
            "dir" => $this->mapa["configuracoes"]["direcao"] ?? "auto",
            "scope" => $this->mapa["configuracoes"]["escopo"] ?? "/",
            "lang" => $this->mapa["configuracoes"]["idioma"] ?? "pt",
            "orientation" => $this->mapa["configuracoes"]["orientacao"] ?? "any",
            "display" => $this->mapa["configuracoes"]["display"] ?? "fullscreen",
            "display_override"=> ["window-controls-overlay"]
        ];
      
        // Processa categorias
        $this->processCategories($manifesto);
        
        // Processa ícones
        $this->processManifestIcons($manifesto);
        
        // Processa screenshots
        $this->processScreenshots($manifesto);
        
        $this->processAtalhos($manifesto);
        
        // Adiciona service worker
        $manifesto["service_worker"] = [
            "src" => "/service-worker.js",
            "scope" => "/"
        ];
        
        
        

        // Salva o manifest
        $this->saveManifest($manifesto);
        
    }
    
    private function processCategories(&$manifesto) {
        if (!isset($this->mapa["categorias"])) {
            return;
        }
        
        $categorias = [];
        foreach ($this->mapa["categorias"] as $chave => $cat) {
            if ($cat === "true") {
                $categorias[] = $chave;
            }
        }
        
        if (!empty($categorias)) {
            $manifesto["categories"] = $categorias;
        }
    }
    
    private function processManifestIcons(&$manifesto) {
        if (!isset($this->mapa["icones"]["icone"])) {
            return;
        }
        
        $this->icone = json_decode($this->mapa["icones"]["icone"], true);
        if (empty($this->icone)) {
            return;
        }
        
        $icones = [];
        foreach ($this->iconSizes as $size) {
            $nome = count(explode("x", $size)) == 2 ? $size : $size . "x" . $size;
            $icones[] = [
                "src" => $this->dominio."/conteudo/icones/ico-" . $size . ".png",
                "sizes" => $nome
            ];
        }
        $manifesto["icons"] = $icones;
    }
    
    private function shots($imagens, $tipo, $modelo){
     
        
        for ($i = 1; $i <= 10; $i++) {
            $pasta = $this->mapa["screenshots-".$tipo]["imagem" . $i] ?? '[]';
            $obj = json_decode($pasta, true);
            
            if (empty($obj) || !isset($obj[0])) {
                continue;
            }
            
            $caminhoImagem = __DIR__ . "/../../../uploads/" . $obj[0];
            $dados = $this->obterDimensoesEFormato($caminhoImagem);
            
            if ($dados === false) {
                error_log("Erro ao obter dimensões da imagem: " . $caminhoImagem);
                continue;
            }
            
            $imagens[] = [
                "src" => $this->dominio . "/conteudo/uploads/" . $obj[0],
                "sizes" => $dados['largura'] . "x" . $dados['altura'], // Corrigido: largura x altura
                "type" => $dados["tipo"],
                "form_factor"=>$modelo
                
            ];
        }   
        
        return $imagens;
    }
    
    private function processScreenshots(&$manifesto) {
        $imagens = [];
        $imagens = $this->shots($imagens, "mobile", "narrow");
        $imagens = $this->shots($imagens, "pc", "wide");
        
        if (!empty($imagens)) { 
            $manifesto["screenshots"] = $imagens;   
        }
    }
    
    private function saveManifest($manifesto) {
        $json = json_encode($manifesto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if ($json === false) {
            error_log("Erro ao codificar JSON do manifesto");
            return false;
        }
        
        $caminho = __DIR__ . "/../../../../conteudo/manifest.json";
        
        // Verifica se o diretório existe
        $diretorio = dirname($caminho);
        if (!is_dir($diretorio)) {
            if (!mkdir($diretorio, 0755, true)) {
                error_log("Erro ao criar diretório: " . $diretorio);
                return false;
            }
        }
        
        if (file_put_contents($caminho, $json) === false) {
            error_log("Erro ao salvar arquivo manifest.json");
            return false;
        }
        
        return true;
    }
}

?>