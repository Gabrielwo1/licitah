<?

class Tabela{
    public $acao;
    public $user;
    public $identificador;
    public $modulo;
    public $tabela;
    public $conn;
    public $bd;
    public $banco;
    public $prefixo;
    public $metas;
    public $bancoMeta;
    public $iniciais;
    public $page;
    public $size;
    public $editor;
    public $order;
    public $comeco;
    public $fim;
    public $pesquisa;
    public $tab;
    public $ordeIndex;
    public $filtrosSearch;
    
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->identificador = $_POST["identificador"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->conn = conn();
        $this->page = $_POST["page"] ?? 1;
        $this->size = $_POST["size"] ?? 20;
        $this->order = strtoupper($_POST["order"] ?? "desc");
        $this->comeco = $_POST["comeco"] ?? false;
        $this->fim = $_POST["fim"] ?? false;
        $this->pesquisa = $_POST["pesquisa"] ?? false;
        $this->tab = $_POST["tab"] ?? false;
        $this->ordeIndex = json_decode($_POST["ordeIndex"] ?? '[]', true);
        $this->filtrosSearch = json_decode($_POST["filtros"] ?? '[]', true);
        

        
    }
    
    function close(){
        $this->conn->close();
    }
    
    function loadFile($nome, $pasta) {
    $modulo = basename($this->modulo); 
    $id = basename($nome);
    $arquivo = __DIR__."/../conteudo/modulos/$modulo/admins/$pasta/$id.json";

    if (!file_exists($arquivo)) {
        return ["erro" => true, "mensagem" => "A tabela enviada não é válida"];
    }

    $conteudo = file_get_contents($arquivo);
    $array = json_decode($conteudo, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["erro" => true, "mensagem" => "Erro ao decodificar JSON"];
    }

    return ["sucesso" => true, "conteudo" => $array];
}

    function sortear(&$array) {
    if (empty($array)) {
        return null; // Retorna null se o array estiver vazio
    }

    // Sorteia um índice aleatório
    $indice = array_rand($array);

    // Obtém o valor correspondente
    $valorSorteado = $array[$indice];

    // Remove o item do array
    unset($array[$indice]);

    // Reorganiza os índices do array
    $array = array_values($array);

    return $valorSorteado;
}

    function buscaEstrangeira($termo, $banco, $coluna){
        $parse = new Parse($banco);
        $info = $parse->infoTabela();
        if($info["id"]){
            $seleciona = "SELECT {$info["id"]} as id FROM $banco WHERE {$coluna} LIKE '%{$termo}%'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows > 0){
                $ids = $resultado->num_rows > 0 ? array_column($resultado->fetch_all(MYSQLI_ASSOC), 'id') : [];
                return implode(",", $ids);
            }
        }
        return 'false';
     
    }
    
    function pegaCondicional($chave){

    // Obtém o tipo de comparação a partir do input do formulário (você pode modificar isso conforme a sua implementação)
    $tipoComparacao = $this->tabela[$chave]["tipo"] ?? '='; // Valor padrão é '='

    // Verifica se a condicional está ativa e se tipo e coluna estão configurados corretamente
    if($this->tabela[$chave]["ativo"] ?? 0 === "1"){
        $tipo = $tipoComparacao; // Usa o tipo de comparação obtido do select
        $coluna = $this->tabela[$chave]["coluna"] ?? false;
        $valor = $this->tabela[$chave]["valor"] ?? "";

        if(!$coluna){
            return false; // Se a coluna não estiver configurada, retorna false
        }

        // Verifica se o valor é uma lista separada por vírgulas
        if(count(explode(",", $valor)) > 1){
            $array = explode(",", $valor); // Converte o valor em um array
            $lista = implode(",", array_map('trim', $array)); // Remove espaços extras e junta os valores de volta
            return "{$this->prefixo}_{$coluna} {$tipo} ({$lista})"; // Retorna a condição para uma lista de valores
        } else {
            // Se o valor for único, aplica o tipo configurado (ex.: "=")
            return "{$this->prefixo}_{$coluna} {$tipo} '{$valor}'"; // Retorna a condição para um valor único
        }
    }

    return false; // Caso a condicional não esteja ativa
}

    function loadDados($pesquisa = false){
        $colunas = $this->tabela["colunas"];
        

        $setupPrev = [];
        $seleciona = "SELECT configuracao_tabela_setup FROM configuracoes_tabelas WHERE configuracao_tabela_modulo = '{$this->modulo}' AND configuracao_tabela_chave = '{$this->identificador}' AND configuracao_tabela_autor = '{$this->user}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $setupPrev = json_decode($dado["configuracao_tabela_setup"], true);
        }
        
        $condicional = $this->pegaCondicional("condicional");
        $condicional2 = $this->pegaCondicional("condicional2");
        
     
        $hash = false;
        $url = false;

        if(empty($colunas)){
            return ["erro"=>true, "mensagem"=>"As colunas enviadas estão vazias"];
        }else{
            if(!empty($setupPrev) && !empty($setupPrev["colunas"])){
                $colsset = $setupPrev["colunas"];
                
                foreach($colunas as $chave=>$coluna){
                    if(isset($colsset[$coluna["id"]])){
                        $ativo = $colsset[$coluna["id"]] ? false : true;
                        $colunas[$chave]["invisivel"] = $ativo;
                    }
                }

            }
      
        }
        
        $alfabeto = array_merge(range('a', 'z'), range('A', 'Z'));
        $remover = ['H', 'U'];
        $alfabeto = array_diff($alfabeto, $remover);
        $alfabeto = array_values($alfabeto);
        

        $principais = [$this->prefixo."_id as id"];
        
        if ($this->tabela["acoes"]["opcoes"]["acoes"] ?? false) {
           if (($this->tabela["acoes"]["opcoes"]["editar"] ?? false) || ($this->tabela["acoes"]["opcoes"]["acoes"]["apagar"] ?? false)) {
               $hash = true;
               if($this->editor == "hash"){
                   $principais[] = $this->prefixo."_hash as hash";
               }else{
                   $principais[] = $this->prefixo."_id as hash";
               }
               
           }
       }
        
        if($this->tabela["acoes"]["opcoes"]["vizualizar"] ?? false){
            $url = true;
            $principais[] = $this->prefixo."_url as url";
       }

        $filtros = [];
        $metas = [];
        $estrangeiras = [];
        $map = [];
        $mapInvertido = [];
        $tabula = false;
        $i = 0;
        $ordens = [];
        
        
        $colunasShow = [];
        $zz = 0;
        foreach($colunas as $coluna){
            
            
       
                
           
            if($coluna["filtravel"] ?? false){
                $filtros[] = $coluna;
            }
            
            if(isset($coluna["tabulavel"]) && $coluna["tabulavel"] && !$tabula){
                $colunatab = $coluna["id"];
                $seleciona = "
                SELECT {$this->prefixo}_{$coluna["id"]} id, COUNT(*) AS total
                FROM {$this->banco}
                GROUP BY {$this->prefixo}_{$coluna["id"]}
                ";

                $resultado = $this->conn->query($seleciona);
                
                $categorias = $resultado->num_rows > 0  ? $resultado->fetch_all(MYSQLI_ASSOC)  : [];
                $tabula = [];
                $tabula["v"] = $categorias;
                
                if(!empty($categorias)){
                    $mapaTab = [];
                    
                    if($coluna["render"] == "personalizado" || $coluna["render"] == "6"){
                        $regra = $coluna["regra"];
                        $tabula["r"] = $regra;
                    }
        
                }
                


                
            }
          
            
            if($coluna["tipo"] == 1){
                
                
                if(!$coluna["invisivel"]){
                    $principais[] = "{$this->prefixo}_{$coluna["id"]} as '{$coluna["id"]}'";
                }
                
                
          
            
                if(!empty($this->ordeIndex) && isset($this->ordeIndex[$i])){
                    if($this->ordeIndex[$i] == "up"){
                        $ordens[] = $this->prefixo."_".$coluna["id"].' DESC';
                    }else{
                        $ordens[] = $this->prefixo."_".$coluna["id"].' ASC';
                    }
                }
                
            }else{
                 if(!$coluna["invisivel"]){
                $metas[] = $coluna["id"];
                 }
            }
        

            $posicao = $i;
            
            if(isset($setupPrev["ordem"][$coluna["id"]]) ?? false){

                 $posicao = intval($setupPrev["ordem"][$coluna["id"]]);
               
            }
            
                 $colunasShow[] = [
                "nome"=>$coluna["nome"],
                "id"=>$coluna["id"],
                "invisivel"=>$coluna["invisivel"],
                "posicao"=> $posicao
                ];
                
            
           
            $obj = ["nome"=>$coluna["nome"], "invisivel"=>$coluna["invisivel"], "editavel"=>$coluna["editavel"], "posicao"=>$posicao];
            

            if(isset($coluna["colpesquisavel"]) && $coluna["colpesquisavel"]){
                $obj["colpesquisavel"] = true;
            }
            
            if($coluna["render"]){
                $obj["render"] = $coluna["render"];
                
                if($obj["render"] == 6 || $obj["render"] == "personalizado"){
                    $obj["regra"] = $coluna["regra"] ?? false;
                }
            }
            
            
            
            $letra = $this->sortear($alfabeto);
            $map[$letra] = $obj;
            $mapInvertido[$coluna["id"]] = $letra;
            
            if($coluna["estrangeira"] ?? false){
                $estrangeiras[$letra] = $coluna["confiEstrangeira"];
                $estrangeiras[$letra]["ids"] = [];
                $obj["estrangeira"] = $letra;
                $map[$letra]["estrangeira"] = 1;
            }
            
            
            $i++;

        }

        $join = implode(",", $principais);
        $banco = $this->banco;
        $contadores = ["count(*) AS s"];
        
        
        if($this->tabela["acoes"]["opcoes"]["filtrodata"] ?? false && $this->tabela["dataFilter"]){
            if($this->tabela["dataFilter"] != "0"){
                $contadores[] = " MIN({$this->prefixo}_{$this->tabela["dataFilter"]}) AS o";
                $contadores[] = " MAX({$this->prefixo}_{$this->tabela["dataFilter"]}) AS n";
            }
            
        }
        $in = implode("," , $contadores);
        $w = "";
        $wheres = [];
        
        
    
          if(!empty($this->tabela["acoes"]["opcoes"]["filtrosuconta"]) && !empty($_SESSION["sub"])){

            $idsub = $_SESSION["sub"];
           $wheres[] = "{$this->prefixo}_subconta ='{$idsub}'";
        }
        
        
        if($condicional){
            $wheres[] = $condicional;
        }
        
        if($condicional2){
            $wheres[] = $condicional2;
        }
        
 
        
        if(!empty($this->filtrosSearch)){
            foreach($this->filtrosSearch as $chave=>$filtro){
                if(!empty($filtro)){
                    $inp = implode(",", array_map(fn($item) => "'$item'", $filtro));

                        
                        
                        
                $wheres[] = "{$this->prefixo}_{$chave} IN ({$inp})";
    
                }
            
            }
        }

        if($this->comeco != false && $this->fim != false){
            if($this->tabela["dataFilter"] != "0"){
                $wheres[] = "{$this->prefixo}_{$this->tabela["dataFilter"]} BETWEEN '{$this->comeco}' AND '{$this->fim}'";
            }
        }
        
        if($this->tab && $colunatab){
            $wheres[] = "{$this->prefixo}_{$colunatab} = '{$this->tab}'";
        }
        
        
        if(!empty($wheres)){
            $w = "WHERE ".implode(" AND ", $wheres);
        }


        $pesquisaveis = [];
        $pesquisaQuery = "";
        if($pesquisa || $this->pesquisa){
            $termo = $pesquisa ? $pesquisa : $this->pesquisa;
            foreach($this->tabela["colunas"] as $col){
                if($col["pesquisavel"]){
                    if($col["estrangeira"] ?? false){
           
                         $pesquisaveis[] = "{$this->prefixo}_{$col['id']} IN ({$this->buscaEstrangeira($termo, $col["confiEstrangeira"]["banco"], $col["confiEstrangeira"]["coluna"])})";
                    }else{
                         $pesquisaveis[] = "{$this->prefixo}_{$col['id']} LIKE '%{$termo}%'";
                    }
                   
                }
            }
        }
        if(!empty($pesquisaveis)){
            $pesquisaQuery = "(".implode(" OR ", $pesquisaveis).")";
        }
        
        
      
        
        if($pesquisaQuery != ""){
            if($w == ""){
                $w = "WHERE {$pesquisaQuery}";
            }else{
                $w = "$w AND {$pesquisaQuery}";
            }
        }
        
        
        $pegaTamanho = "SELECT {$in} FROM {$banco} {$w}";
        

        $resultado = $this->conn->query($pegaTamanho);
        
        $numeros = $resultado->fetch_assoc();
    
        
        $offset = ($this->page - 1) * $this->size;
        $wheres = [];
        
        if(!empty($this->tabela["acoes"]["opcoes"]["filtrosuconta"]) && !empty($_SESSION["sub"])){
            $idsub = $_SESSION["sub"];
            $wheres[] = "{$this->prefixo}_subconta ='{$idsub}'";
        }
        
        
         if($condicional){
            $wheres[] = $condicional;
        }
        
        if($condicional2){
            $wheres[] = $condicional2;
        }
        
        
        $w = "";
        if($this->comeco != false && $this->fim != false){
            $wheres[] = "{$this->prefixo}_{$this->tabela["dataFilter"]} BETWEEN '{$this->comeco}' AND '{$this->fim}'";
        }
        
        if($this->tab && $colunatab){
            $wheres[] = "{$this->prefixo}_{$colunatab} = '{$this->tab}'";
        }
        
         if(!empty($this->filtrosSearch)){
            foreach($this->filtrosSearch as $chave=>$filtro){
                if(!empty($filtro)){
                   $inp = implode(",", array_map(fn($item) => "'$item'", $filtro));

                $wheres[] = "{$this->prefixo}_{$chave} IN ({$inp})";
                }
                
            }
        }


        
        if(!empty($wheres)){
            $w = "WHERE ".implode(" AND ", $wheres);
        }
        
        if($pesquisaQuery != ""){
            if($w == ""){
                $w = "WHERE {$pesquisaQuery}";
            }else{
                $w = "$w AND {$pesquisaQuery}";
            }
            if($pesquisa){
               $this->order = "DESC";
               $this->size = 10;
               $this->page = 1;
               $offset = ($this->page - 1) * $this->size; 
            }
            
        }
        
        $ordem = "{$this->prefixo}_id";
        
        if(isset($this->tabela["acoes"]["opcoes"]["ordenador"]) && $this->tabela["acoes"]["opcoes"]["ordenador"]){
            $ordem = "{$this->prefixo}_ordem"; 
        }
        
        
        
        if(!empty($ordens)){
            $ordem = implode(",", $ordens);
             $seleciona = "SELECT $join FROM $banco {$w} ORDER BY  {$ordem} LIMIT $this->size OFFSET $offset";

        }else{
            $seleciona = "SELECT $join FROM $banco {$w} ORDER BY {$ordem} {$this->order} LIMIT $this->size OFFSET $offset";
        }
        

       
        $respostas = [];
        $ids = [];

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $respostas[$dado["id"]] = $dado;
                $ids[] = $dado["id"];
            }
        }
        
        if (!empty($metas) && !empty($ids) && $this->metas) {
            $join = implode(",", array_map(function($meta) {
        return "'" . addslashes($meta) . "'";
    }, $metas));
            $lista = implode(",", array_map('intval', $ids)); 
            $inicias = $this->iniciais;
            $banco = $this->bancoMeta;
            
            $conteudo = "{$inicias}_{$this->prefixo} AS item, {$inicias}_chave AS chave, {$inicias}_valor AS valor";
            $seleciona = "
            SELECT {$conteudo} 
            FROM {$banco} 
            WHERE {$inicias}_{$this->prefixo} IN ({$lista}) 
            AND {$inicias}_chave IN ({$join})
        ";

    $resultado = $this->conn->query($seleciona);
    

    if ($resultado->num_rows > 0) {
        while($dado = $resultado->fetch_assoc()){
            if($dado["valor"] !== ""){
                $respostas[$dado["item"]][$dado["chave"]] = $dado["valor"];
            }
        }
    }
   
   
}

        $filters = [];
        if(!empty($filtros)){
            foreach($filtros as $filtro){
                
            
                $query = "SELECT DISTINCT {$this->prefixo}_{$filtro['id']} as id FROM {$this->banco}";

                $resultado = $this->conn->query($query);
                $ids = [];
                while($dado = $resultado->fetch_assoc()){
                    $ids[] = $dado["id"];
                }
                
                $ids = array_filter($ids);
                if(!empty($ids)){
                   

                    $lista = implode(",", $ids);
                    
                    if(isset($filtro["estrangeira"]) && $filtro["estrangeira"]){
                        $config = $filtro["confiEstrangeira"];
                        $modulo = $config["modulo"];
                        $banco = $config["banco"];
                        $tipo = $config["tipo"];
                        $coluna = $config["coluna"];
                        $trato = explode("_", $coluna);
                        array_pop($trato);
                        $trato = implode("_", $trato);

                        
                        
                        
                        
                        
                        $seleciona = "SELECT {$trato}_id as id, {$coluna} as valor  FROM {$banco} WHERE {$trato}_id IN ({$lista})";
   
 
                        $resultado = $this->conn->query($seleciona);
      
                        if($resultado->num_rows > 0){
        
              
                             $l = [];
                            while($dado = $resultado->fetch_assoc()){
                                $l[$dado["id"]] = $dado["valor"];
                            }
               
                            $filters[] = ["valores"=> $l , "nome"=>$filtro["nome"], "id"=>$filtro["id"]];
                        }
                    }else{
                    
                        $l = [];
                       foreach($ids as $id){
                            $l[$id] = $filtro["regra"][$id] ?? $id;
                        }
                         $filters[] = ["valores"=> $l , "nome"=>$filtro["nome"], "id"=>$filtro["id"]];
            
                    }
                    
                }
            }
        }
        


         $final = [];
    foreach($respostas as $resposta){
        $mapeado = [];
        foreach($respostas as $item){
            foreach($resposta as $chave=>$valor){
                if(isset($mapInvertido[$chave])){
                    if($valor !== null){
                        $mapeado[$mapInvertido[$chave]] = $valor;
            
                   if (!empty($estrangeiras) && isset($estrangeiras[$mapInvertido[$chave]])) {
                       if (!in_array($valor, $estrangeiras[$mapInvertido[$chave]]["ids"])) {
                           $estrangeiras[$mapInvertido[$chave]]["ids"][] = $valor;
                       }
                      }
                    }
                    
                }
                else{
                    if($chave === "hash"){
                        $mapeado["H"] = $valor;
                    }
                    
                    if($chave === "url"){
                        $mapeado["U"] = $valor;
                    }
                }
            }

        }
        $final[] = $mapeado;
    }
    
    $obj = [];
    $obj["sucesso"] = true;
    $obj["r"] = $final;
    $obj["m"] = $map;
    $obj["n"] = $numeros;
    $obj["cols"] = $colunasShow;
    $obj["per"] = $this->tabela["acoesPersonalizadas"] ?? [];
    
    if($tabula){
        $obj["t"] = $tabula;
    }


    if(!empty($estrangeiras)){
        $obj["e"] = $this->pegaEstrangeiras($estrangeiras);
    }


    $obj["a"] = [];
    if ($this->tabela["acoes"]["opcoes"]["acoes"] ?? false) {
        
        
        $opcoes = $this->tabela["acoes"]["opcoes"];
        $rotas = $this->tabela["acoes"]["rotas"];
        
        $obj["a"]["edit"] = $opcoes["editar"] ?? false;
        $obj["a"]["delete"] = $opcoes["apagar"] ?? false;
        $obj["a"]["view"] = $opcoes["vizualizar"] ?? false;
        $obj["a"]["duplicar"] = $opcoes["duplicar"] ?? false;
        $obj["a"]["import"] = $opcoes["importador"] ?? false;
        $obj["a"]["export"] = $opcoes["exportador"] ?? false;
        $obj["a"]["filter"] = $opcoes["filtro"] ?? false;
        $obj["a"]["coluns"] = $opcoes["colunas"] ?? false;
        $obj["a"]["multiple"] = $opcoes["multiplo"] ?? false;
        $obj["a"]["order"] = $opcoes["ordenador"] ?? false;
        
        
        if($obj["a"]["delete"] || $obj["a"]["edit"]){
            $obj["a"]["edicao"] = $rotas["editar"];
        }
        
        if($obj["a"]["view"]){
            $obj["a"]["ver"] = $rotas["vizualizar"];
        }
    }
    
    $obj["f"] = $filters;


    return $obj;

    }
    
    function pegaEstrangeiras($estrangeiras){
        $mapa = [];
        foreach($estrangeiras as $chave=>$estrangeira){

            if(!empty($estrangeira["ids"])){
       
                $parse = new Parse($estrangeira["banco"]);
                $info = $parse->infoTabela();
             
     
                if(isset($info["id"])){
          
                    $ids = $info["id"];
                    $banco = $estrangeira["banco"];
                    $coluna = $estrangeira["coluna"];
                    $lista = implode("," , $estrangeira["ids"]);
                    $query = "SELECT {$coluna} as coluna, {$ids} as id FROM {$banco} WHERE {$ids} IN ({$lista})";
                    $resultado = $this->conn->query($query);
                    
                    $mapa[$chave] = [];
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $mapa[$chave][$dado["id"]] = $dado["coluna"];
                        }
                    }
                }
                
            }
            
      
        }
        
        return $mapa;
    }
    
    function iniciais($texto) {
          $palavras = explode('_', $texto);
          $resultado = '';
          
          foreach ($palavras as $palavra) {
              $resultado .=  strtolower(substr($palavra, 0, 1));
          }
          
          return $resultado;
      }
 
    function tabela($pesquisa = false){
        $tabela = $this->loadFile($this->identificador, "tabelas");
        
        
        if(isset($tabela["erro"])){
            return $tabela;
        }
        $this->tabela = $tabela["conteudo"];
        
        $banco = $this->loadFile($this->tabela["banco"], "configs");
        if(isset($banco["erro"])){
            return $banco;
        }
        $this->bd = $banco["conteudo"];
        
        
        
        $this->banco = $this->bd["banco"];
        $this->metas = $this->bd["meta"] && $this->bd["metas"] ? $this->bd["metas"] : false;
        $this->editor = $this->bd["estrutura"]["hash"] ? "hash" : "id";
        
        if($this->metas && empty($this->metas)){
            $this->metas = false;
        }else{
            $this->bancoMeta = $this->banco."_meta";
            $this->iniciais = $this->iniciais($this->bancoMeta);
        }

        
        $this->prefixo = $this->bd["prefixo"];
        
        
     
       return $this->loadDados($pesquisa);

    }
    
    function deleta() {

    $array = json_decode($_POST["itens"] ?? '[]', true);


    if (empty($array) || !is_array($array)) {
        return ["erro" => true, "mensagem" => "Não foram enviados itens válidos"];
    }


    $tabela = $this->loadFile($this->identificador, "tabelas");
    if (isset($tabela["erro"])) {
        return $tabela;
    }
    $this->tabela = $tabela["conteudo"];


    $banco = $this->loadFile($this->tabela["banco"], "configs");
    if (isset($banco["erro"])) {
        return $banco;
    }
    $this->bd = $banco["conteudo"];


    $this->banco = $this->bd["banco"];
    $this->prefixo = $this->bd["prefixo"];
    $this->editor = $this->bd["estrutura"]["hash"] ? "hash" : "id";

      
    $ids = array_filter($array, function($item) {
        if ($this->editor === "id") {

            return is_int($item) || ctype_digit($item);
        } elseif ($this->editor === "hash") {
   
            return is_string($item) && strlen($item) === 32; 
        }
        return false;
    });
    
    

    if (empty($ids)) {
        return ["erro" => true, "mensagem" => "Os itens enviados não são válidos"];
    }


    $idsList = "'" . implode("','", array_map('addslashes', $ids)) . "'";
    
    if($this->bd["meta"]){
        $seleciona = "SELECT {$this->prefixo}_id FROM {$this->banco} WHERE {$this->prefixo}_{$this->editor} IN ({$idsList})";
        $resultado = $this->conn->query($seleciona);
         
        $listaIds = $resultado->fetch_all(MYSQLI_ASSOC);
        $listaIds = array_column($listaIds, "{$this->prefixo}_id");
        
        if(!empty($listaIds)){
            $join = implode(",", $listaIds);
            $this->bancoMeta = $this->banco."_meta";
            $this->iniciais = $this->iniciais($this->bancoMeta );
            $deleta = "DELETE FROM  {$this->bancoMeta} WHERE {$this->iniciais}_{$this->prefixo} IN ($join)";
            $this->conn->query($deleta);
        }
    }

    $query = "DELETE FROM {$this->banco} WHERE {$this->prefixo}_{$this->editor} IN ({$idsList})";


    if ($this->conn->query($query)) {

        return ["sucesso" => true, "mensagem" => "Itens deletados com sucesso"];
    } else {
        return ["erro" => true, "mensagem" => "Erro ao deletar os itens"];
    }
}

    function hashToId($itensEdicacao){
        $itensEdicacao = array_map(function($item) {
            return '"' . addslashes($item) . '"';
            
        }, $itensEdicacao);
        $lista = implode(",", $itensEdicacao);
        $seleciona = "SELECT {$this->prefixo}_id as id FROM {$this->banco} WHERE {$this->prefixo}_{$this->editor} IN ({$lista})";
    
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return [];
        }
        $dados = $resultado->fetch_all(MYSQLI_ASSOC);
        $ids = array_column($dados, 'id');
        return $ids;
    }

    function basic(){
        $tabela = $this->loadFile($this->identificador, "tabelas");
    if (isset($tabela["erro"])) {
        return $tabela;
    }
    $this->tabela = $tabela["conteudo"];


    $banco = $this->loadFile($this->tabela["banco"], "configs");
    if (isset($banco["erro"])) {
        return $banco;
    }
    $this->bd = $banco["conteudo"];


    $this->banco = $this->bd["banco"];
    $this->prefixo = $this->bd["prefixo"];
    $this->editor = $this->bd["estrutura"]["hash"] ? "hash" : "id";
    }

    function ordem(){
        $index = $_POST["index"] ?? false;
        $id = $_POST["id"] ?? false;
        
        if(!$id || !$index){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessários"];
        }
        
        $tabela = $this->loadFile($this->identificador, "tabelas");
        if (isset($tabela["erro"])) {
            return $tabela;
        }
        $this->tabela = $tabela["conteudo"];
        
        $banco = $this->loadFile($this->tabela["banco"], "configs");
        if (isset($banco["erro"])) {
            return $banco;
        }
        $this->bd = $banco["conteudo"];
        
        $this->banco = $this->bd["banco"];
        $this->prefixo = $this->bd["prefixo"];
        $this->editor = $this->bd["estrutura"]["hash"] ? "hash" : "id";
        
        $seleciona = "SELECT {$this->prefixo}_ordem as ordem 
              FROM {$this->banco} 
              WHERE {$this->prefixo}_{$this->editor} = '{$id}'";
              $resultado = $this->conn->query($seleciona);


if ($resultado->num_rows == 0) {
    return ["erro" => true, "mensagem" => "O item enviado não foi encontrado"];
}

$dado = $resultado->fetch_assoc();
$ordemAntiga = (int)$dado["ordem"];

if ($ordemAntiga == $index) {
    return ["sucesso" => true, "mensagem" => "Nenhuma alteração foi feita"];
}

$this->conn->begin_transaction();

try {
    if ($index > $ordemAntiga) {

        $update = "UPDATE {$this->banco} 
                   SET {$this->prefixo}_ordem = {$this->prefixo}_ordem - 1 
                   WHERE {$this->prefixo}_ordem > {$ordemAntiga} 
                     AND {$this->prefixo}_ordem <= {$index}";
    } else {
        
        $update = "UPDATE {$this->banco} 
                   SET {$this->prefixo}_ordem = {$this->prefixo}_ordem + 1 
                   WHERE {$this->prefixo}_ordem < {$ordemAntiga} 
                     AND {$this->prefixo}_ordem >= {$index}";
    }
    $this->conn->query($update);

    
    $updateItem = "UPDATE {$this->banco} 
                   SET {$this->prefixo}_ordem = {$index} 
                   WHERE {$this->prefixo}_{$this->editor} = '{$id}'";
    $this->conn->query($updateItem);


    $this->conn->commit();

    return ["sucesso" => true, "mensagem" => "Ordem atualizada com sucesso"];
} catch (Exception $e) {

    $this->conn->rollback();
    return ["erro" => true, "mensagem" => "Erro ao atualizar a ordem: " . $e->getMessage()];
}
  
    }
    
    function destaca(){
        $this->basic();
    $array = json_decode($_POST["itens"] ?? '[]', true);
    $ativo = intval($_POST["ativa"] ?? 0);
   

    // Verificar se foram enviados itens válidos
    if (empty($array) || !is_array($array)) {
        return ["erro" => true, "mensagem" => "Não foram enviados itens válidos"];
    }

    // Sanitizar os hashes (evitar injeção SQL)
    $lista = implode(",", array_map(function ($item) {
        return "'" . $this->conn->real_escape_string($item) . "'";
    }, $array));

    // Montar a query
    $update = "UPDATE {$this->banco} 
               SET {$this->prefixo}_destaque = '{$ativo}'
               WHERE {$this->prefixo}_hash IN ({$lista})";


    // Executar a query
    if ($this->conn->query($update)) {
        return ["sucesso" => true, "mensagem" => "Itens atualizados com sucesso"];
    } else {
        return ["erro" => true, "mensagem" => "Erro ao atualizar itens: " . $this->conn->error];
    }
        
    }
    
    function editMassaStart(){
        $itensEdicacao = json_decode($_POST["itens"] ?? '[]', true);
        if(empty($itensEdicacao)){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os itens para edição em massa"];
        }
        
        $this->basic();
        
        
        $inputs = [];
        
        $map = [];
        foreach($this->bd["colunas"] as $col){
            $map[$col["nome"]] = $col;
        }
        
 
        $ids = $this->hashToId($itensEdicacao);
        if(empty($ids)){
            return ["erro"=>true, "mensagem"=>"Não foram enviados identificadores válidos"];
        }
        

        $colunas = $this->tabela["colunas"];
        
        
        foreach($colunas as $chave=>$coluna){
            if(isset($map[$coluna["id"]])){
   
                if(($map[$coluna["id"]]["unico"] ?? false) && count($ids) > 1){
       
                     $colunas[$chave]["editavel"] = false;
                }

            }
        }
        
        
        
        $selects = [];
        $i = 0;
        $query = [];
        $metas = [];
        foreach($colunas as $coluna){
            if($coluna["editavel"]){
                
                $inputs[$coluna["id"]] = [
                    "nome"=>$coluna["nome"],
                    "id"=>$coluna["id"],
                    "tipo"=> !empty($coluna["estrangeira"]) ? "select" : "input"
                    ];
                    
                    
                    if($coluna["tipo"] == 1){
                        $query[] =  "
                CASE 
                    WHEN COUNT(DISTINCT {$this->prefixo}_{$coluna["id"]}) = 1 
                    THEN MIN({$this->prefixo}_{$coluna["id"]})
                    ELSE NULL 
                END AS {$coluna["id"]}";
                    }else{
                        $metas[] = $coluna["id"];
                    }
                    
                    if(!empty($coluna["estrangeira"])){
                        $selects[] =  $coluna;
                    }
            }
        }
        
        $q = implode("," , $query);
        $lista = implode(",", $ids);
        $seleciona = "SELECT {$q} FROM {$this->banco} WHERE {$this->prefixo}_id IN ({$lista})";
        $resultado = $this->conn->query($seleciona);
        $dados = $resultado->fetch_assoc();
        foreach($dados as $chave=>$valor){
            if($valor){
                $inputs[$chave]["valorglobal"] = $valor;
            }
        }  

        if(!empty($selects)){
            foreach($selects as $select){
                 
                $config = $select["confiEstrangeira"];  
 
                $modulo = $config["modulo"];
                $banco = $config["banco"];
                $coluna = $config["coluna"];
                $prefixo = explode("_", $coluna)[0];
                $tipo = $config["tipo"];
            
                switch($banco){
                    case 'categorias':
                        $seleciona = "SELECT {$coluna} as nome, {$prefixo}_id as id FROM {$banco} WHERE categoria_iscat='1' AND categoria_tipo='{$this->tabela["banco"]}'";
                        break;
                    default:
                        $seleciona = "SELECT {$coluna} as nome, {$prefixo}_id as id FROM {$banco}";
                        break;
                }
                
                $respostas = [];
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $respostas[$dado["id"]] = $dado["nome"];
                    }
                }
                
             $inputs[$select["id"]]["valores"] = $respostas;
             
            }
        }
        
        
        return ["sucesso"=>true, "inputs"=>$inputs];
    }
    
    function salvarMassaStart(){
        $itensEdicacao = json_decode($_POST["itens"] ?? '[]', true);
        if(empty($itensEdicacao)){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os itens para edição em massa"];
        }
        $edicao = json_decode($_POST["edicao"] ?? '[]', true);
        if(empty($edicao)){
            return ["erro"=>true, "mensagem"=>"Não foram enviados dados de edicação"];
        }
        
        $this->basic();
        
        $ids = $this->hashToId($itensEdicacao);
        if(empty($ids)){
            return ["erro"=>true, "mensagem"=>"Não foram enviados identificadores válidos"];
        }
        

        $colunas = $this->tabela["colunas"];
        $principal = [];
        $metas = [];

        foreach($colunas as $coluna){
            if($coluna["editavel"]){
                if(isset($edicao[$coluna["id"]])){
                    if($coluna["tipo"] == 1){
                        $principal[] = "{$this->prefixo}_{$coluna["id"]} = '{$edicao[$coluna["id"]]}'";
                    }else{
                        $metas[] =  "{$coluna["id"]}={$edicao[$coluna["id"]]}";
                    }
                }
            }
            
        }
        
        

        
        if(!empty($principal)){
            $lista = implode(",", $ids);
            $querys = implode(",", $principal);
            $update = "UPDATE {$this->banco} SET {$querys} WHERE {$this->prefixo}_id IN ({$lista})";
            $this->conn->query($update);
        }
        
        if(!empty($metas)){
            
        }
        
        return ["sucesso"=>true, "mensagem"=>"Os valores foram atualizados com sucesso"];

    }
    
    function resetVisibilidade() {
    $deleta = $this->conn->prepare("
        DELETE FROM configuracoes_tabelas 
        WHERE configuracao_tabela_modulo = ? 
          AND configuracao_tabela_chave = ? 
          AND configuracao_tabela_autor = ?
    ");

    // Bind parameters to prevent SQL injection
    $deleta->bind_param("sss", $this->modulo, $this->identificador, $this->user);

    // Execute the prepared statement
    $deleta->execute();

    // Check if the operation was successful
    if ($deleta->affected_rows > 0) {
        $tabela = $this->loadFile($this->identificador, "tabelas");
        if(isset($tabela["erro"])){
            return $tabela;
        }
        $this->tabela = $tabela["conteudo"];
        $map = [];
        foreach($this->tabela["colunas"] as $coluna){
            $map[$coluna["id"]] = $coluna["invisivel"];
        }
        

        return ["sucesso" => true, "mensagem" => "Configurações de visibilidade resetadas com sucesso", "map"=>$map];
    } else {
        return ["erro" => true, "mensagem" => "Nenhuma configuração foi encontrada para deletar"];
    }
}

    function setupUserTabela($chave){
        $colunas = $_POST["colunas"] ?? false;

    if (!$colunas) {
        return ["erro" => true, "mensagem" => "Não foram enviadas colunas válidas"];
    }

    // Decode JSON input early to avoid repeated calls
    $colunasDecoded = json_decode($colunas, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["erro" => true, "mensagem" => "Formato JSON inválido para colunas"];
    }

    $seleciona = $this->conn->prepare("
        SELECT * FROM configuracoes_tabelas 
        WHERE configuracao_tabela_modulo = ? 
          AND configuracao_tabela_chave = ? 
          AND configuracao_tabela_autor = ?
    ");
    $seleciona->bind_param("sss", $this->modulo, $this->identificador, $this->user);
    $seleciona->execute();
    $resultado = $seleciona->get_result();

    $acao = '';
    if ($resultado->num_rows === 0) {
        // Insert if no existing record is found
        $config = [$chave => $colunasDecoded];
        $setup = json_encode($config, JSON_UNESCAPED_UNICODE);

        $acao = $this->conn->prepare("
            INSERT INTO configuracoes_tabelas 
            (configuracao_tabela_modulo, configuracao_tabela_chave, configuracao_tabela_autor, configuracao_tabela_setup) 
            VALUES (?, ?, ?, ?)
        ");
        $acao->bind_param("ssss", $this->modulo, $this->identificador, $this->user, $setup);
    } else {
        // Update if a record exists
        $dado = $resultado->fetch_assoc();
        $config = json_decode($dado["configuracao_tabela_setup"], true);
        $config[$chave] = $colunasDecoded;
        $setup = json_encode($config, JSON_UNESCAPED_UNICODE);

        $id = $dado["configuracao_tabela_id"];
        $acao = $this->conn->prepare("
            UPDATE configuracoes_tabelas 
            SET configuracao_tabela_setup = ? 
            WHERE configuracao_tabela_id = ?
        ");
        $acao->bind_param("si", $setup, $id);
    }

    // Execute the action query
    $acao->execute();

    if ($acao->affected_rows > 0) {
        return ["sucesso" => true, "mensagem" => "Setup de tabela feito com sucesso"];
    } else {
        return ["erro" => true, "mensagem" => "Nenhuma alteração foi feita"];
    }
    }
    
    function exportador(){
        $conteudo = $this->tabela();

    }
    
    function hasher($length = 32) {
    // Calcula o número de bytes necessários
    $bytes = ceil($length * 0.75); // base64_encode gera uma string 4/3 vezes maior
    $randomBytes = random_bytes($bytes); // Gera bytes aleatórios
    $randomString = base64_encode($randomBytes); // Codifica os bytes em base64
    
    // Retorna a substring com o comprimento desejado
    return substr(str_replace(['+', '/', '='], '', $randomString), 0, $length);
}
    
    function duplicar(){
    $id = $_POST["item"] ?? false;
    if (!$id) {
        return ["erro" => true, "mensagem" => "Não foi enviado um ID válido"];
    }

    $tabela = $this->loadFile($this->identificador, "tabelas");
    if (isset($tabela["erro"])) {
        return $tabela;
    }
    $this->tabela = $tabela["conteudo"];

    $banco = $this->loadFile($this->tabela["banco"], "configs");
    if (isset($banco["erro"])) {
        return $banco;
    }
    $this->bd = $banco["conteudo"];

    $this->banco = $this->bd["banco"];
    $this->prefixo = $this->bd["prefixo"];
    $this->editor = $this->bd["estrutura"]["hash"] ? "hash" : "id";

    $seleciona = "SELECT * FROM {$this->banco} WHERE {$this->prefixo}_{$this->editor}='{$id}' LIMIT 1";
    $resultado = $this->conn->query($seleciona);
    if ($resultado->num_rows == 0) {
        return ["erro" => true, "mensagem" => "Não foi encontrado o item enviado"];
    }

    $dado = $resultado->fetch_assoc();

    $trocas = [
        $this->prefixo . "_hash",
        $this->prefixo . "_url",
        $this->prefixo . "_vendaveis",
        $this->prefixo . "_atributos",
        $this->prefixo . "_endereco"
    ];

    foreach ($trocas as $item) {
        if (isset($dado[$item])) {
            switch ($item) {
                case $this->prefixo . "_hash":
                    $dado[$item] = trim($this->hasher(32));
                    break;
                case $this->prefixo . "_url":
                    $dado[$item] = trim($this->hasher(10));
                    break;
                case $this->prefixo . "_vendaveis":
                case $this->prefixo . "_atributos":
                case $this->prefixo . "_endereco":
                    $dado[$item] = NULL;
                    break;
            }
        }
    }

    // Remover campos que não devem ser duplicados
    $chavesIgnoradas = [
        $this->prefixo . "_id",
        $this->prefixo . "_data",
        $this->prefixo . "_update"
    ];
    foreach ($chavesIgnoradas as $chave) {
        unset($dado[$chave]);
    }

    $campos = [];
    $valores = [];

    foreach ($dado as $chave => $valor) {
        $campos[] = "`$chave`";
        $valores[] = is_null($valor) ? "NULL" : "'" . $this->conn->real_escape_string($valor) . "'";
    }

    $camposSQL = implode(",", $campos);
    $valoresSQL = implode(",", $valores);

    $cadastra = "INSERT INTO {$this->banco} ({$camposSQL}) VALUES ({$valoresSQL})";
    $executa = $this->conn->query($cadastra);

    if (!$executa) {
        return ["erro" => true, "mensagem" => "Erro ao duplicar item: " . $this->conn->error];
    }

    $novoId = $this->conn->insert_id;

    return [
        "sucesso" => true,
        "mensagem" => "Item duplicado com sucesso",
        "id" => $novoId
    ];
}

    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"A ação não é permitida para usuários deslogados"];
        }
        
        if(!$this->identificador || !$this->modulo){
            return ["erro"=>true, "mensagem"=>"Não foram envidos os parametros necessarios"];
        }
        
        switch($this->acao){
            case 'tabela':
                return $this->tabela();
                break;
            case 'deleta':
                return $this->deleta();
                break;
            case 'pesquisa': 
                $termo = $_POST["termo"] ?? false;
                if(!$termo){
                    return ["erro"=>true, "mensagem"=>"Não foi enviado um termo válido"];
                }
                return $this->tabela($termo);
                break;
            case 'ordenador':
                return $this->ordem();
                break;
            case 'destaca':
                return $this->destaca();
                break;
            case 'editMassaStart':
                return $this->editMassaStart();
                break;
            case 'editMassaSalva':
                return $this->salvarMassaStart();
                break;
            case 'visibilidadeColuna':
                 return $this->setupUserTabela("colunas");
                break;
            case 'resetVisibilidade':
                return $this->resetVisibilidade();
                break;
            case 'ordemColuna':
                return $this->setupUserTabela("ordem");
                break;
            case 'exportador':
                return $this->exportador();
                break;
            case 'duplicar':
                return $this->duplicar();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}
?>