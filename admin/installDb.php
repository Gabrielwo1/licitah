<?

/**
 * Encontra todos os itens no formulário por seu tipo
 * 
 * @param array $formulario O array de formulário completo
 * @param string $tipo O tipo de item a ser encontrado (ex: "subconta", "select", "texto", etc)
 * @return array Um array contendo todos os itens encontrados do tipo especificado
 */
  function buscarItensPorTipo($item, $tipoBuscado, &$resultados) {
        // Verifica se o item atual é do tipo buscado
        if (isset($item['tipo']) && $item['tipo'] === $tipoBuscado) {
            $resultados[] = $item;
        }
        
        // Verifica se o item tem inputs (caso seja o formulário raiz)
        if (isset($item['inputs']) && is_array($item['inputs'])) {
            foreach ($item['inputs'] as $input) {
                buscarItensPorTipo($input, $tipoBuscado, $resultados);
            }
        }
        
        // Verifica se o item tem filhos
        if (isset($item['filhos']) && is_array($item['filhos'])) {
            foreach ($item['filhos'] as $filho) {
                buscarItensPorTipo($filho, $tipoBuscado, $resultados);
            }
        }
    }
    
function encontrarItensPorTipo($formulario, $tipo) {
    $resultados = [];
    
    // Função recursiva interna para percorrer a estrutura
  
    
    // Inicia a busca a partir do formulário raiz
    buscarItensPorTipo($formulario, $tipo, $resultados);
    
    return $resultados;
}

/**
 * Encontra o primeiro item no formulário por seu tipo
 * 
 * @param array $formulario O array de formulário completo
 * @param string $tipo O tipo de item a ser encontrado (ex: "subconta", "select", "texto", etc)
 * @return array|false O primeiro item encontrado do tipo especificado ou false caso não encontre
 */
function encontrarItemPorTipo($formulario, $tipo) {
    $itens = encontrarItensPorTipo($formulario, $tipo);
    
    if (count($itens) > 0) {
        return $itens[0]; // Retorna o primeiro item encontrado
    } else {
        return false; // Nenhum item encontrado
    }
}


function encontrarItemPorHash($formulario, $hash) {
    // Função recursiva interna para percorrer a estrutura
    function buscarItem($item, $hashBuscado) {
        // Verifica se o item atual possui o hash buscado
        if (isset($item['hash']) && $item['hash'] === $hashBuscado) {
            return $item;
        }
        
        // Verifica se o item tem inputs (caso seja o formulário raiz)
        if (isset($item['inputs']) && is_array($item['inputs'])) {
            foreach ($item['inputs'] as $input) {
                $resultado = buscarItem($input, $hashBuscado);
                if ($resultado !== false) {
                    return $resultado;
                }
            }
        }
        
        // Verifica se o item tem filhos
        if (isset($item['filhos']) && is_array($item['filhos'])) {
            foreach ($item['filhos'] as $filho) {
                $resultado = buscarItem($filho, $hashBuscado);
                if ($resultado !== false) {
                    return $resultado;
                }
            }
        }
        
        return false;
    }
    
    // Inicia a busca a partir do formulário raiz
    return buscarItem($formulario, $hash);
}

class Install{
    public $caminho;
    public $configuracao;
    public $banco;
    public $prefixo;
    public $colunas;
    public $estrangeiras;
    public $queryPrincipal;
    public $estruturaPrincipal;
    public $form;
    public $formulario;

    function __construct($caminho){
        $this->caminho = $caminho;
        $this->configuracao = json_decode(file_get_contents($this->caminho));
        
        
        $this->form = str_replace("/configs/", "/formularios/", $this->caminho);

        $this->formulario = json_decode(file_get_contents($this->form), true);
        
       
        
        $this->banco = $this->configuracao->banco ?? false;
        $this->prefixo = $this->configuracao->prefixo ?? false;
        $this->colunas = $this->configuracao->colunas ?? false;
    
        
        $principal = $this->gerarQueries();
        
        

        $this->estruturaPrincipal = $principal;
        $this->queryPrincipal = implode(",", $principal);
        
     
   
    }
    
    /*
    
      usuario_status INT DEFAULT 1,
    usuario_vizibilidade VARCHAR(100) DEFAULT '0',
    usuario_agendamento VARCHAR(19) DEFAULT '2023-12-16 16:57:51'
    
    */
    
    function estrutura($chave){
         if(isset($this->configuracao->estrutura->$chave) && $this->configuracao->estrutura->$chave == "1"){
             return true;
         }
         
         return false;
    }
    
    function gerarQueries() {
        $queries = [];
        $prefixo = trim($this->prefixo);
        $estrangeiros = [];
        
        array_push($queries, "{$prefixo}_id INT AUTO_INCREMENT PRIMARY KEY");
        foreach ($this->colunas as $item) {
            $query = false;
            $nome = trim($item->nome);
            $nulo = isset($item->nulo) ? $item->nulo : false;
            $nuloStatus = ($nulo == true) ? 'NULL' : 'NOT NULL';
            $unico = (isset($item->unico) && $item->unico == 1) ? "UNIQUE" : "";
           

            switch ($item->tipo) {
                case 'varchar':
                    $size = intval($item->size ?? 200);
                    $query = "{$prefixo}_{$nome} VARCHAR({$size}) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci {$nuloStatus} {$unico}";
                    break;
                case 'text':
                    $query = "{$prefixo}_{$item->nome} TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci {$nuloStatus} {$unico}";
                    break;
                case 'int':
                    $query = "{$prefixo}_{$item->nome} INT {$nuloStatus}";
                    break;
                case 'estrangeiro':
                    $query = "{$prefixo}_{$item->nome} INT {$nuloStatus}";
                     
                     
                     if(isset($item->estrangeira) && isset($item->referencia)){
                         $estrangeira = trim($item->estrangeira);
                         $referencia = trim($item->referencia);
                         
                         array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_{$item->nome}  FOREIGN KEY ({$prefixo}_{$item->nome}) REFERENCES {$estrangeira}($referencia)");
                     }
 
                    break;
                case 'json':
                    $query = "{$prefixo}_{$item->nome} JSON {$nuloStatus}";
                    break;
                case 'boolean':
                    $query = "{$prefixo}_{$item->nome} BOOLEAN {$nuloStatus}";
                    break;
                case 'float':
                    $sizes = $item->sizes;
                    $int = $sizes->inteiro ?? 10;
                    $dec = $sizes->decimal ?? 2;
                    $query = "{$prefixo}_{$item->nome} DECIMAL({$int},{$dec}) {$nuloStatus}";
                    break;
                case 'data':
                    $dataTipo = $item->formato ?? 'datetime'; 
                    switch ($dataTipo) {
                        case 'datetime':
                            $query = "{$prefixo}_{$item->nome} DATETIME {$nuloStatus}";
                           break;
                        case 'timestemp':
                            $query = "{$prefixo}_{$item->nome} TIMESTAMP {$nuloStatus}";
                         break;
                         case 'date':
                             default:
                                 $query = "{$prefixo}_{$item->nome} DATE {$nuloStatus}";
                                 break;
                    }
                    break;

            }
            

            if ($query){
                array_push($queries, $query);
            }
        }
        
        
        
        if($this->estrutura("hash")){
            array_push($queries, "{$prefixo}_hash VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE");
        }
        
        if($this->estrutura("autor")){
             array_push($queries, "{$prefixo}_autor INT NULL");
             array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_autor FOREIGN KEY ({$prefixo}_autor) REFERENCES usuarios(usuario_id)");
        }
        
        if($this->estrutura("subconta")){
            $config = encontrarItemPorTipo($this->formulario, "subconta");
            
            $modulo = $config["infos"]["configuracoes"]["modulo"] ?? false;
            $formulario = $config["infos"]["configuracoes"]["formulario"] ?? false;
            
            if($modulo && $formulario){
                $conteudoSub = json_decode(file_get_contents(__DIR__."/../conteudo/modulos/".$modulo."/admins/configs/".$formulario.".json"), true);
                $bnk = $conteudoSub["banco"];
                $idpref = $conteudoSub["prefixo"]."_id";
                
                array_push($queries, "{$prefixo}_subconta INT NULL");
                array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_subconta FOREIGN KEY ({$prefixo}_subconta) REFERENCES {$bnk}({$idpref})");
                
            }
            
        }
        
        
        if($this->estrutura("enderecos")){
             array_push($queries, "{$prefixo}_endereco INT NULL");
             array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_endereco FOREIGN KEY ({$prefixo}_endereco) REFERENCES enderecos(endereco_id) ON DELETE SET NULL ON UPDATE CASCADE");
        } 
        
        if($this->estrutura("categoria")){
             array_push($queries, "{$prefixo}_categoria INT NULL");
             array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_categoria FOREIGN KEY ({$prefixo}_categoria) REFERENCES categorias(categoria_id) ON DELETE SET NULL ON UPDATE CASCADE"); 
        }
        
        if($this->estrutura("ordenador")){
             array_push($queries, "{$prefixo}_ordem INT NULL");
        }
        
        if($this->estrutura("wildcard")){
             array_push($queries, "{$prefixo}_wildcard INT NULL");
        }
        
        if($this->estrutura("destaque")){
             array_push($queries, "{$prefixo}_destaque BOOLEAN DEFAULT 0");
        }
        
        if($this->estrutura("preco")){
             array_push($queries, "{$prefixo}_vendavel INT NULL");
             array_push($estrangeiros, "CONSTRAINT fk_{$prefixo}_vendavel FOREIGN KEY ({$prefixo}_vendavel) REFERENCES vendaveis(vendavel_id) ON DELETE SET NULL ON UPDATE CASCADE");
        }
        
        if($this->estrutura("tags")){
             array_push($queries, "{$prefixo}_tags VARCHAR (100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        }
        
        if($this->estrutura("url")){
            array_push($queries, "{$prefixo}_url VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        }
        
        if($this->estrutura("seo")){
            array_push($queries, "{$prefixo}_seo VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        }
        
        if($this->estrutura("imagemDestaque")){
             array_push($queries,"{$prefixo}_imagem VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        }
        
        if($this->estrutura("galeria")){
             array_push($queries,"{$prefixo}_galeria VARCHAR(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL");
        }
        
        if($this->estrutura("dataCriacao")){
             array_push($queries, "{$prefixo}_data DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
        
        if($this->estrutura("dataUpdate")){
             array_push($queries,"{$prefixo}_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        }
         
        array_push($queries,"{$prefixo}_status INT DEFAULT '1'");
        array_push($queries,"{$prefixo}_visibilidade VARCHAR(100) DEFAULT '0'");
        array_push($queries,"{$prefixo}_agendamento VARCHAR(19) DEFAULT '0'");
       

        if(count($estrangeiros) > 0){
            $this->estrangeiras = ",".implode("," , $estrangeiros);
        }else{
            $this->estrangeiras = "";
        }
        
        return $queries;
    }

    function principal(){
        $banco = $this->banco;
        $prefixo = $this->prefixo;
        $principal = $this->queryPrincipal;
        $estrangeiras = $this->estrangeiras;

   

        $query = "CREATE TABLE $banco (
            {$principal}
            $estrangeiras
            ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            
            

        return $query;
        
    }
    
    function update(){
        return $this->estruturaPrincipal;
    }
    
    function converteString($texto) {
    $palavras = explode('_', $texto);
    $resultado = '';

    foreach ($palavras as $palavra) {
        $resultado .=  strtolower(substr($palavra, 0, 1));
    }


    return $resultado;
}

    function meta(){
        if($this->configuracao->meta && $this->banco && $this->prefixo){
            $banco = $this->banco;
            $prefixo = $this->prefixo;
            $primeiraLetra = $this->converteString($banco);
            
             $query = "CREATE TABLE {$banco}_meta (
            {$primeiraLetra}m_id INT AUTO_INCREMENT PRIMARY KEY,
            {$primeiraLetra}m_{$prefixo} INT NOT NULL,
            {$primeiraLetra}m_chave VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            {$primeiraLetra}m_valor VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            {$primeiraLetra}m_data DATETIME DEFAULT CURRENT_TIMESTAMP,
            {$primeiraLetra}m_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
            CONSTRAINT fk_{$primeiraLetra}m_{$prefixo} FOREIGN KEY ({$primeiraLetra}m_{$prefixo}) REFERENCES {$banco}({$prefixo}_id)
            ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            return $query;
        }
        return false;
    }
}
?>