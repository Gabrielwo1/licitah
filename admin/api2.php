<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include_once __DIR__."/conn.php";
include_once __DIR__."/functionNown.php";



class Api{
    public $modulo;
    public $chave;
    public $user;
    public $conn;
    public $hash;
    public $order;
    public $page;
    public $size;
    public $fingerprint;
    public $figerprint;
    public $extra;
    public $passados;
    public $api;
    public $bd;
    public $banco;
    public $prefixo;
    public $bancoMeta;
    public $iniciais;
    public $wheres;
    public $joinEnderecos;
    function __construct(){
        $this->conn = conn();
        $this->modulo = $_POST["modulo"] ?? false;
        $this->chave = $_POST["chave"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->hash = $_POST["hash"] ?? false;
        $this->order = $_POST["order"] ?? "DESC";
        $this->page = $_POST["page"] ?? 1;
        $this->size = $_POST["size"] ?? 12;
        if(!($_POST["size"] ?? false) && ($_POST["quantidade"] ?? false)){
            $this->size = $_POST["quantidade"] ?? 12;
        }
        
        $this->fingerprint = $_POST["fingerprint"] ?? false;
        $this->figerprint = $this->fingerprint;
        $wildcard = $_POST["wildcard"] ?? 0;
        $this->extra = $_POST["extra"] ?? false;
        
        if($this->extra && ($_POST["parser"] ?? false)){
            $trato = explode(",", trim($_POST["parser"]));
            if(count($trato) == 3){
                $seleciona = "SELECT {$trato["1"]}_id as id FROM {$trato["0"]} WHERE {$trato["1"]}_url='{$trato["2"]}' LIMIT 1";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $this->extra = $dado["id"];
                }

            }
        }
        $this->passados = json_decode($_POST["itens"] ?? '[]', true);
        
        

        if($wildcard){
            $this->parseWild($wildcard);
        }

    }
    
    function parseWild($wild){
        $seleciona = "SELECT wc_id FROM wildcards_contas WHERE wc_url='$wild'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $this->wildcard = $dado["wc_id"];
        }
    }
    
    function close(){
        $this->conn->close();
    }
    
    function getAutor(){
        if(!$this->hash){
            return ["erro"=>true, "mensagem"=>"Não foi um identificador válido para o autor"];
        }
        
        $user = trim($this->hash);
        $seleciona = "SELECT usuario_id as id FROM usuarios WHERE usuario_user='{$user}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return ["sucesso"=>true, "id"=>$dado["id"]];
        }
        return ["erro"=>true, "mensagem"=>"Não foi encontrado um autor com o identificador enviado"];
    }
    
    function getCategoria($isCat){
        if(!$this->hash){
            return ["erro"=>true, "mensagem"=>"Não foi um identificador válido para a categoria"];
        }
 
        $categoria = trim($this->hash);
        $seleciona = "SELECT categoria_id as id FROM categorias  WHERE categoria_url='{$categoria}' AND categoria_tipo='{$this->api["banco"]}' AND categoria_iscat='{$isCat}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return ["sucesso"=>true, "id"=>$dado["id"]];
        }
        return ["erro"=>true, "mensagem"=>"O identificador da categoria enviado não é válido"];
        
        
    }
    
    function publicador(){
        $AGORA = date('Y-m-d\TH:i');
        $seleciona = "UPDATE {$this->banco} SET {$this->prefixo}_agendamento = '0' WHERE {$this->prefixo}_agendamento != '0' AND {$this->prefixo}_data <= '$AGORA'";
        $resultado = $this->conn->query($seleciona);
    }
    
    function verificarTipo($variavel) {
    if (is_string($variavel)) {
        return "string";
    } elseif (is_bool($variavel)) {
       return "boleano";
    } elseif (is_array($variavel)) {
        return "array";
    } else {
        return "nenhuma";
    }
}
    
    function pegaFiltro(){
        $filtro = $_POST["filtro"] ?? false;
        if($filtro){
            try{
                $filtro = json_decode($filtro, true);
               
  
                if(!empty($filtro["filtro"]) && !empty($filtro["valores"])){
                    $setup = loadFile($this->modulo, $filtro["filtro"] , "filtros");
                    if($setup){
                       $filtros = $setup["conteudo"]["filtros"];
                       
                       $buscas = [];
                       
                       
                       $valores = $filtro["valores"];
                       foreach($valores as $chave=>$v){
                           $ja = false;
                           foreach($filtros["principais"] as $item){
                               
                               if($item["itens"][0]["hash"] == $chave){
                                   $buscas[] = [
                                       "termos"=>$v,
                                       "item"=>$item["itens"][0]
                                       ];
                                       $ja = true;
                                     break;
                               }
                           }
                           
                           if(!$ja){
                                 foreach($filtros["avancados"] as $item){
                               if($item["itens"][0]["hash"] == $chave){
                                   $buscas[] = [
                                       "termos"=>$v,
                                       "item"=>$item["itens"][0]
                                       ];
                                     break;
                               }
                           }
                           }
                          

                       }
                       
                 
                       if(empty($buscas)){
                           return false;
                       }
                       
                       $query = [
                           "principal"=>[],
                           "metas"=>[],
                           "estrangeiras"=>[]
                           ];
                           
                           foreach($buscas as $busca){
                               $termos = $busca["termos"];
                               $item = $busca["item"];
                            
                          
                               switch($item["tipo"]){
                                   case 1:
                                   case 3:
                                       $have = false;
                                       foreach($filtros["principais"] as $p){
                                           if($p["itens"][0]["hash"] == $item["hash"]){
                                               $have = $p;
                                           }
                                       }
                                       
                                       if(!$have){
                                           foreach($filtros["avancados"] as $p){
                                           if($p["itens"][0]["hash"] == $item["hash"]){
                                               $have = $p;
                                           }
                                       } 
                                       }
                                        $name = $have["itens"][0]["nome"];
                                       if(intval($item["tipo"]) === 1){
                                           $tipo = $this->verificarTipo($termos);
                                           
                                           switch($tipo){
                                               case 'string':
                                                    $query["principal"][] = "{$this->prefixo}_$name LIKE '%{$termos}%'";
                                                   break;
                                               case 'array':
                                                     $ids = implode(",", $termos);
                                                     $query["principal"][] = "{$this->prefixo}_$name IN ($ids)";
                                                   break;
                                               case 'boleano':
                                                   break;
                                           }
                                           
                                       }else{
                                           $query["metas"][] = ["chave"=>$name, "valor"=>$termos, "tipo"=>$this->verificarTipo($termos)];
                                       }
                                
                                       // Principais
                                       break;
                                   case 2:
                                       // Fixas
                     
                                       
                                       switch($item["nome"]){
                                           case 'tags':
                                               $conditions = [];
                                               foreach ($termos as $termo) {
                                                   $conditions[] = "JSON_CONTAINS({$this->prefixo}_tags, '[$termo]')";
                                               }
                                               $query["principal"][] = "(" . implode(" OR ", $conditions) . ")";
                                               break;
                                           case 'categoria':
                                               $ids = implode(",", $termos);
                                               $query["principal"][] = "{$this->prefixo}_categoria IN ($ids)";
                                               break;
                                           case 'wildcard':
                                                $ids = implode(",", $termos);
                                               $query["principal"][] = "{$this->prefixo}_wildcard IN ($ids)";
                                               break;
                                           case 'galeria':
                                               if($termos){
                                                   $query["principal"][] = "{$this->prefixo}_galeria != '[]'";
                                               }else{
                                                   $query["principal"][] = "{$this->prefixo}_galeria = '[]'";
                                               }
                                               
                                               break;
                                           case 'imagemDestaque':
                                                if($termos){
                                                   $query["principal"][] = "{$this->prefixo}_imagem != '[]'";
                                               }else{
                                                   $query["principal"][] = "{$this->prefixo}_imagem = '[]'";
                                               }
                                               
                                               break;
                                           case 'autor':
                                               $ids = implode(",", $termos);
                                               $query["principal"][] = "{$this->prefixo}_autor IN ($ids)";
                                               break;
                                          case 'dataUpdate':
                                          case 'dataCriacao':
                                              $dataInicio = DateTime::createFromFormat('d/m/Y', $termos[0]);
                                              $dataFim = DateTime::createFromFormat('d/m/Y', $termos[1]);
                                              $dataInicio->setTime(0, 0, 0);
                                              $dataFim->setTime(23, 59, 59);
                                              
                                              $dataInicioSQL = $dataInicio->format('Y-m-d H:i:s');
                                              $dataFimSQL = $dataFim->format('Y-m-d H:i:s');
                                              $coluna = $item["nome"] == "dataUpdate" ?  "{$this->prefixo}_update" : "{$this->prefixo}_data";
                                              $query["principal"][] = "($coluna BETWEEN '$dataInicioSQL' AND '$dataFimSQL')";
                                              break;
                                       }
                                       
                                       break;
                
                               }
                               
 
                               
                           }
                           
                           $principal = false;
                           if(!empty($query["principal"])){
                               $principal = implode(" AND ", $query["principal"]);
                           }
                           
     
                           $metas = false;
                           if(!empty($query["metas"])){
                               foreach($query["metas"] as $m){
                                  // print_r($m);
                               }
                               
                               
                           }
                           
                           
                       
                           
                           
                           return [
                               "p"=>$principal,
                               "m"=>false,
                               "e"=>false
                               ];
                       
                       
             
                    }
                    
                  
                    
                }
                
        
            }catch(Exception $e){
                return false;
            }
        }
        return false;
    }

    function pegaEndereco() {
    if (isset($this->api["geolocalizacao"]) && $this->api["geolocalizacao"]) {
        $endereco = $_POST["adress"] ?? false;
        if ($endereco) {
            $endereco = json_decode($endereco, true);
            
            include __DIR__."/../conteudo/modulos/enderecos/admins/integracao.php";
            
            // Inicializa os filtros de endereço como vazio
            $filtros = [];
            
            // Processa qualquer parâmetro de endereço fornecido
            if (!empty($endereco["pais"])) {
                $parse = new AdressParse($endereco["pais"], false, false);
                $retorno = $parse->parse();
                
                if (!isset($retorno["erro"])) {
                    $id = $retorno["id"];
                    if ($retorno["tipo"] == 'pais') {
                        $filtros[] = "e.endereco_pais = {$id}";
                    }
                }
            }
            
            if (!empty($endereco["estado"])) {
                $parse = new AdressParse(false, $endereco["estado"], false);
                $retorno = $parse->parse();
                
                if (!isset($retorno["erro"])) {
                    $id = $retorno["id"];
                    if ($retorno["tipo"] == 'estado') {
                        $filtros[] = "e.endereco_estado = {$id}";
                    }
                }
            }
            
            if (!empty($endereco["cidade"])) {
                $parse = new AdressParse(false, false, $endereco["cidade"]);
                $retorno = $parse->parse();
                
                if (!isset($retorno["erro"])) {
                    $id = $retorno["id"];
                    if ($retorno["tipo"] == 'cidade') {
                        $filtros[] = "e.endereco_cidade = {$id}";
                    }
                }
            }
            
            // Se temos bairro, rua ou cep, adicionamos filtros diretos
            if (!empty($endereco["bairro"])) {
                $bairro = $this->conn->real_escape_string($endereco["bairro"]);
                $filtros[] = "e.endereco_bairro LIKE '%{$bairro}%'";
            }
            
            if (!empty($endereco["rua"])) {
                $rua = $this->conn->real_escape_string($endereco["rua"]);
                $filtros[] = "e.endereco_rua LIKE '%{$rua}%'";
            }
            
            if (!empty($endereco["cep"])) {
                $cep = $this->conn->real_escape_string($endereco["cep"]);
                $filtros[] = "e.endereco_cep = '{$cep}'";
            }
            
            // Retorna os filtros construídos
            if (!empty($filtros)) {
                return implode(" AND ", $filtros);
            }
        }
    }
    return false;
}
    
    function lista($ids = []){
        
       
        $enderecoFiltro = $this->pegaEndereco();
        

        $this->publicador();
        foreach($this->api["itens"] as $chave=>$valor){
            if($valor["nome"] == "categoria"){
                $valor["estrangeiro"] = false;
                $this->api["itens"][$chave] = $valor;
            }
        }
        
        
        $entradasUser = $this->pegaFiltro();
        
        $this->wheres = [];
        $this->joinEnderecos = false;
        if ($enderecoFiltro) {
        
        $this->joinEnderecos = true;
        $this->wheres[] = $enderecoFiltro;
        
        }
        
        if($entradasUser){
            if($entradasUser["p"]){
                $this->wheres[] = $entradasUser["p"]; 
            }
           
        }
        
        if(!empty($ids)){
            $lista = implode(',', $ids);
            $this->wheres[] = "{$this->prefixo}_id IN ($lista)";
        }else{
            $sub = intval($this->api["subtipo"] ?? 1);
            
            switch($sub){
                case '2':
                case '5':
                    if($sub == 2){
                        $where = $this->getAutor();
                    }else{
                        if($this->user){
                            $where = ["sucesso"=>true, "id"=>$this->user];
                        }else{
                            $where = ["erro"=>true, "mensagem"=>"O usuário não está logado"];
                        }
                        
                    }
                    
                    if(isset($where["erro"])){
                        return $where;
                    }
                    $this->wheres[] = "{$this->prefixo}_autor = {$where["id"]}";
                    break;
                case '3':
                    $where = $this->getCategoria(1);
                    if(isset($where["erro"])){
                        return $where;
                    }
                    $this->wheres[] =  "{$this->prefixo}_categoria = {$where["id"]}";
                    break;
                case '4':
                    $where = $this->getCategoria(0);
                    if(isset($where["erro"])){
                        return $where;
                    }
                    $this->wheres[] = "JSON_CONTAINS({$this->prefixo}_tags, '{$where["id"]}')";
                    break;
            }
            
            
            if($this->api["extra"]["ativo"] ?? false){
                if(!$this->extra){
                    return ["erro"=>true, "mensagem"=>"Não foi enviado o parametro extra para consulta"];
                }
                
                $tipo = $this->api['extra']['tipo'] ?? "=";
                if($tipo == "LIKE"){
                    $this->wheres[] =  "{$this->prefixo}_{$this->api['extra']['coluna']} LIKE '%{$this->extra}%'";
                }else{
                    $this->wheres[] =  "{$this->prefixo}_{$this->api['extra']['coluna']} {$tipo} '{$this->extra}'";
                }
                
            }

        }
        

        // Somente os Públicados
        if(intval($this->api["tipo"] ?? 1) != 1){
             $this->wheres[] = "{$this->prefixo}_status = '1'";
        }else{
            if($this->user && intval($_SESSION["funcao"]) > 1){
                 $this->wheres[] = "({$this->prefixo}_status = '1' OR {$this->prefixo}_autor = '{$this->user}')";
            }
        }
        
        $this->wheres[] = "{$this->prefixo}_agendamento = '0'"; 
        
        
        
      
        if($this->api["subconta"] ?? false){
            $subconta = intval($_SESSION["sub"] ?? 0);
            $this->wheres[] = "{$this->prefixo}_subconta = '$subconta'"; 
        }

        
        if(!empty($this->bd["estrutura"]["wildcard"]) && isset($this->wildcard)){
            $this->wheres[] = "{$this->prefixo}_wildcard = '{$this->wildcard}'";
        }
        
        if($this->bd["estrutura"]["ordenador"] ?? false){
             $oderby =  "ORDER BY {$this->prefixo}_ordem {$this->order}";
        }else{
             $oderby =  "ORDER BY {$this->prefixo}_id {$this->order}";
        }


        $principais = ["{$this->prefixo}_id as id"];
        $metas = [];
        
        $colunas = $this->api["itens"];
        $idFica = false;
        $map = [];
        $atributos = false;
        foreach($colunas as $coluna){
            if($coluna["banco"] == "p"){
                
    
                if($coluna["nome"] != "atributos"){
                    if($coluna['nome'] == "enderecos"){
                        $coluna['nome'] = "endereco";
                    }
                    
                    
                     $principais[] = "{$this->prefixo}_{$coluna['nome']} as '{$coluna['chave']}'";
                }else{
                    $atributos = true;
                }
                
               
                if($coluna["nome"] == "id"){
                $idFica = true;
                }
            
            
            }else{
                $metas[] = $coluna['nome'];
            }
            
            
            $map[$coluna["nome"]] = $coluna; 
        }
        
        
        

        $join = implode(",", $principais);
        
        $w = !empty($this->wheres) ? "WHERE ".implode(" AND ", $this->wheres) : "";
    

        
         if ($this->joinEnderecos) {
         $seleciona = "SELECT count(*) as total FROM {$this->banco} i 
                      INNER JOIN enderecos e ON i.{$this->prefixo}_endereco = e.endereco_id 
                      {$w}";
             
         } else {
             $seleciona = "SELECT count(*) as total FROM {$this->banco} {$w}";
             
         }
    
    
        $resultado = $this->conn->query($seleciona);
        $dado = $resultado->fetch_assoc();
        $total = $dado["total"];
        
        
        $ids = [];
        $itens = [];
        
        $offset = ($this->page - 1) * $this->size;
        
        
          if ($this->joinEnderecos) {
                $seleciona = "SELECT {$join} FROM {$this->banco} i 
                              INNER JOIN enderecos e ON i.{$this->prefixo}_endereco = e.endereco_id 
                              {$w} {$oderby} LIMIT {$this->size} OFFSET {$offset}";
            } else {
                $seleciona = "SELECT {$join} FROM {$this->banco} {$w} {$oderby} LIMIT {$this->size} OFFSET {$offset}";
            }
       
      
        $resultado = $this->conn->query($seleciona);

        

        if ($resultado && $resultado->num_rows > 0) {
            while($dado = $resultado->fetch_assoc()){
                $id = $dado["id"];
                if(!$idFica){
                  unset($dado["id"]);  
                }
                
                $itens[$id] = $dado;
                $ids[] = $id;
            }
        }
        
        if(!empty($ids)){
            $categoria = false;
            $tags = false;
            $lista = implode(",", $ids);
            
            if(!empty($this->bancoMeta) && !empty($metas)){
                $metas = implode(",", array_map(function($meta) {
                    return "'" . $this->conn->real_escape_string($meta) . "'";
                }, $metas));
                $seleciona = "SELECT  {$this->iniciais}_{$this->prefixo} as item, {$this->iniciais}_chave as chave, {$this->iniciais}_valor as valor  FROM {$this->bancoMeta} WHERE {$this->iniciais}_{$this->prefixo} IN ({$lista}) AND {$this->iniciais}_chave IN ({$metas})";
                $resultado = $this->conn->query($seleciona);



                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        if($dado["item"] && trim($dado["item"])){
                            $itens[$dado["item"]][$map[$dado["chave"]]["chave"]] = $dado["valor"];
                        }
                    }
                }
            }
            
            foreach($map as $m){
                if(($m["estrangeiro"] == "1" ?? false) && ($m["setupEstrangeira"]["modulo"] ?? false)){
                   $lista = array_column($itens, $m["chave"]);
                   $estrangeiras = $this->getEstrangeiras($m["setupEstrangeira"], $lista);
                   if(isset($estrangeiras["sucesso"])){
                       $render = $estrangeiras["lista"];
                   foreach($itens as $chave=>$valor){
                       $valor = $valor[$m["chave"]];

                       if(isset($itens[$chave]) &&  isset($render[$valor])){
                           $itens[$chave][$m["chave"]] = $render[$valor];
                       } 

                    } 
                   }
                }
                
                if($m["nome"] == "categoria"){
                    $categoria = true;
                }
                
                if($m["nome"] == "tags"){
                    $tags = true;
                }
            }
            
            
            $lista = implode(",", $ids);
            
            if($this->api["number"]["comentarios"] ?? false){
                 
              $comentarios = $this->comentarios($lista);
              if(!empty($comentarios)){
                  foreach($comentarios as $chave=>$valor){
                      $itens[$chave]["n"]["comentarios"] = $valor;
                  }
              }
                
            }
            
            if($this->api["number"]["reacoes"] ?? false){
           $reacoes = $this->reacoes($lista);
            if(!empty($reacoes)){
                  foreach($reacoes as $chave=>$valor){
                      $itens[$chave]["n"]["reacoes"] = $valor;
                  }
              }
        }
        
            if($this->api["number"]["favoritos"] ?? false){

              $seleciona = "
        SELECT 
            favorito_identificador AS id
        FROM favoritos
        WHERE favorito_modulo = '{$this->banco}'
          AND favorito_identificador IN ({$lista})
          AND favorito_autor = '{$this->user}'
    ";

    $resultado = $this->conn->query($seleciona);


    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $itens[$row['id']]["n"]["favoritado"] = true;
        }
    }

            
        }
        
            if($this->api["number"]["visualizacoes"] ?? false){
            
            $visualizacoes = $this->visualizacoes($lista);
             if(!empty($visualizacoes)){
                  foreach($visualizacoes as $chave=>$valor){
                      $itens[$chave]["n"]["visualizacoes"] = $valor;
                  }
              }
            
            
            
        }
        
            if(isset($map["vendavel"])){
            $precos = array_column($itens, "preco");
   
            $comprados = $this->comprados();
    

            $string = implode(",", $precos);
            $mapprecos = [];
            $seleciona = "SELECT vendavel_id as id, vendavel_preco as preco, vendavel_referencia as referencia FROM vendaveis WHERE vendavel_id IN ($string)";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows > 0){
                while($dado = $resultado->fetch_assoc()){
                   $mapprecos[$dado["id"]] = [
                       "id" => $dado["id"], 
                       "preco" => $dado["preco"], 
                       "comprado" => in_array($dado["referencia"], $comprados)
                       ];
                }
            }
            
            foreach($itens as $chave=>$item){
                $itens[$chave]["preco"] = $mapprecos[$item["preco"]];
            }
            
        }
        
            if (!empty($categoria)) { 
        $categorias = array_column($itens, "categoria");
        

        $unicos = array_unique($categorias);
        $unicos = array_filter($unicos);
 
        if (!empty($unicos)) { 
                $cats = [];
                $implode = implode(",", $unicos); 
                $seleciona = "SELECT * FROM categorias WHERE categoria_id IN ({$implode})";
         
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $cats[$dado["categoria_id"]] = [
                            "id"=>intval($dado["categoria_id"]),
                            "nome"=>$dado["categoria_nome"],
                            "url"=>$dado["categoria_url"]
                            ];
                    }
                    
                    foreach($itens as $chave=>$item){
                        if($item["categoria"] && $cats[$item["categoria"]]){
                            $itens[$chave]["categoria"] = $cats[$item["categoria"]];
                        }else{
                            $itens[$chave]["categoria"] = false;
                        }
                        
                    }
 
                }
            }
        }
        
            if(!empty($tags)){
            $tags = array_column($itens, "tags"); 
            $t = [];
            foreach($tags as $tag){
                if($tag){
                   $tag = json_decode($tag, true);
                   $t = array_merge($t, $tag); 
                }
            }
            $unique = array_unique($t);
            if(!empty($unique)){
                $implode = implode(",", $unique);
                $cats = [];
                $seleciona = "SELECT * FROM categorias WHERE categoria_id IN ({$implode})";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $cats[$dado["categoria_id"]] = [
                            "id"=>intval($dado["categoria_id"]),
                            "nome"=>$dado["categoria_nome"],
                            "url"=>$dado["categoria_url"]
                            ];
                    }
                    
                    if(!empty($cats)){
                         foreach($itens as $chave=>$item){
                        if($item["tags"]){
                            $array = [];
                            $ts = json_decode($item["tags"], true);
                            foreach($ts as $t){
                                if($cats[$t]){
                                    $array[] = $cats[$t];
                                }
                            }
                            if(!empty($array)){
                                $itens[$chave]["tags"] = $array;
                            }else{
                                $itens[$chave]["tags"] = false;
                            }
     
                        }else{
                            $itens[$chave]["tags"] = false;
                        }
                        
                    }
                    }

                }
                
                
                
                
                
            }
        }
            
 
            
        }
        
        
 
        $trato = [];

        foreach($itens as $chave=>$item){
            
            if($atributos){
             $item["atributos"] = $this->pegaAtributos($chave, $this->banco);
            }
            
            
            $trato[] = $item;
        }
        

        
        if(intval($this->api["tipo"] ?? 1) == 1){
            if(!empty($trato)){
                return ["sucesso"=>true, "item"=>$trato[0]]; 
            }else{
                return ["erro"=>true, "mensagem"=>"Item não encontrado"]; 
            }
            
        }
        

        

        return ["sucesso"=>true, "lista"=>$trato, "numeros"=>["total"=>$total]]; 
    }
    
    function pegaItensAtributos($grupo, $id){
        $atributos = [];
        $seleciona = "SELECT atributo_item_atributo FROM atributos_itens WHERE atributo_item_grupo='$grupo' AND atributo_item_identificador='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($atributos, $dado["atributo_item_atributo"]);
            }
        }
        
        $atrs = [];
        if(!empty($atributos)){
            $ids = implode(",", $atributos);
            $seleciona = "SELECT * FROM atributos WHERE atributo_id IN ($ids)";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows > 0){
                while($dado = $resultado->fetch_assoc()){
                    
                    $atrs[$dado["atributo_id"]] = [
                        "nome"=>$dado["atributo_nome"],
                        "exibicao"=>$dado["atributo_exibicao"],
                        "cor"=>$dado["atributo_cor"],
                        "imagem"=>$dado["atributo_imagem"],
                        "url"=>$dado["atributo_url"]
                ];
                    
                    
                }
            }
            
            
    
        }
       
        
        return $atrs;
    } 
    
    function pegaAtributos($id, $banco){
        $seleciona = "SELECT * FROM atributos_grupos WHERE atributo_grupo_banco='$banco'";

        $resultado = $this->conn->query($seleciona);
        $lista = [];
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $grupo = $dado["atributo_grupo_id"];
                $itens = $this->pegaItensAtributos($grupo, $id);
                if(!empty($itens)){
                    $lista[$grupo] = ["nome"=>$dado["atributo_grupo_nome"], "url"=>$dado["atributo_grupo_url"], "atributos"=>$itens];
                }
                
            }
        }
        
        
        return $lista;
        
    }
    
    function visualizacoes($lista){
        $identificadorColuna = $this->user ? 'analitycs_consumo_usuario' : 'analitycs_consumo_digital';
        $identificadorValor = $this->user ? $this->user : $this->fingerprint;


        $seleciona = "
            SELECT 
                analitycs_consumo_identificador AS id,
                COUNT(*) AS total_visualizacoes, -- Total de visualizações
                COUNT(DISTINCT analitycs_consumo_digital) AS visualizacoes_unicas, -- Visualizações únicas
                SUM({$identificadorColuna} = '{$identificadorValor}') AS minhas_visualizacoes -- Visualizações do usuário atual
            FROM analitycs_consumos
            WHERE 
                analitycs_consumo_modulo = '{$this->modulo}'
                AND analitycs_consumo_banco = '{$this->banco}'
                AND analitycs_consumo_identificador IN ({$lista})
            GROUP BY analitycs_consumo_identificador
        ";
        
        return;
        $resultado = $this->conn->query($seleciona);
        
        $estatisticas = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $estatisticas[$row['id']] = [
                    'total' => (int)$row['total_visualizacoes'],
                    'unicos' => (int)$row['visualizacoes_unicas'],
                    'minhas' => (int)$row['minhas_visualizacoes'],
                ];
            }
        }
        
        // Retorna as estatísticas
        return $estatisticas;
    }
    
    function reacoes($lista){
          $seleciona = "
        SELECT 
            reacoes_interacao_identificador AS id,
            reacoes_interacao_reacao AS reacao,
            COUNT(*) AS total_reacoes,
            MAX(CASE WHEN reacoes_interacao_autor = '{$this->user}' THEN reacoes_interacao_reacao ELSE 0 END) AS usuario_reagiu
        FROM reacoes_interacoes
        WHERE reacoes_interacao_banco = '{$this->banco}'
          AND reacoes_interacao_identificador IN ({$lista})
        GROUP BY reacoes_interacao_identificador, reacoes_interacao_reacao
    ";
    $resultado = $this->conn->query($seleciona);

    $dados = [];
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $id = $row['id'];
            if (!isset($dados[$id])) {
                $dados[$id] = [
                    'total' => [],
                    'reacao' => 0,
                ];
            }

            $dados[$id]['total'][$row['reacao']] = (int)$row['total_reacoes'];
            $dados[$id]['reacao'] = max($dados[$id]['reacao'], (int)$row['reacao']);
        }
    }
    return $dados;
    }
    
    function comentarios($lista){
              $seleciona = "
        SELECT 
            comentario_identificador AS id, 
            COUNT(*) AS total, 
            COUNT(DISTINCT comentario_autor) AS unicos,
            MAX(CASE WHEN comentario_autor = '{$this->user}' THEN 1 ELSE 0 END) AS comentou
        FROM comentarios
        WHERE comentario_modulo = '{$this->banco}'
          AND comentario_identificador IN ($lista)
        GROUP BY comentario_identificador
    ";
    $resultado = $this->conn->query($seleciona);

    $dados = [];
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[$row['id']] = [
                'total' => (int) $row['total'],
                'unicos' => (int) $row['unicos'],
                'feito' => (bool) $row['comentou'],
            ];
        }
    }

    return $dados;
    }
    
    function limparArray($array) {
    return array_filter($array, function ($valor) {
        return !is_null($valor) && $valor !== '' && $valor !== false;
    });
    }
    
    function getEstrangeiras($setup , $ids){
        $ids = $this->limparArray($ids);
        
        
        if(empty($ids)){
            return ["erro"=>true, "mensagem"=>"A lista de ids está vazia"];
        }
        $modulo = $setup["modulo"];
        $banco = explode(".", $setup["banco"])[0];

        $info = loadFile($modulo, $banco , "configs");
        
    
        if(isset($info["erro"])){
            return $info;
        }
        
        $banco = $info["conteudo"]["banco"];
        $prefixo = $info["conteudo"]["prefixo"];
        
        $principal = ["{$prefixo}_id as id"];
        
        if(!empty($setup["principal"])){
            foreach($setup["principal"] as $p){
                $principal[] = "{$prefixo}_{$p} as '{$p}'";
            }
        }
        
        $lista = [];
        $colunas = implode(",", $principal);
        
 
       
        
        $ids = implode("," , $ids);
        $seleciona = "SELECT {$colunas} FROM {$banco} WHERE {$prefixo}_id IN ($ids)";
        $resultado = $this->conn->query($seleciona);  
        
        
        $ids = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $id = $dado["id"];
                unset($dado["id"]);
                $lista[$id] = $dado;
                $ids[] = $id;
            }
        }
        
        if(!empty($ids) && !empty($setup["metas"])){
            $metas = implode(",", array_map(function($meta) {
                    return "'" . $this->conn->real_escape_string($meta) . "'";
            }, $setup["metas"]));
            $ids = implode(",", $ids);
            $bancoMeta = $banco."_meta";
            $iniciais = iniciais($bancoMeta);
        
            $seleciona = "SELECT  {$iniciais}_{$prefixo} as item, {$iniciais}_chave as chave, {$iniciais}_valor as valor  FROM {$bancoMeta} WHERE {$iniciais}_{$prefixo} IN ({$ids}) AND {$iniciais}_chave IN ({$metas})";
            $resultado = $this->conn->query($seleciona);
             if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        if($dado["item"] && trim($dado["item"])){
                            $lista[$dado["item"]][$dado["chave"]] = $dado["valor"];
                        }
                    }
                }
            
   
        }
        
        return ["sucesso"=>true, "lista"=>$lista];

    }
    
    function getId(){
        if(!$this->hash){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um identificador válido"];
        }
        
        // codigo antigo
        
        // $url = trim($this->hash);
        // $seleciona = "SELECT {$this->prefixo}_id as id FROM {$this->banco} WHERE {$this->prefixo}_url='$url'";
        // $resultado = $this->conn->query($seleciona);
        // if($resultado->num_rows == 1){
        //     $dado = $resultado->fetch_assoc();
        //     return ["sucesso"=>true, "id"=>$dado["id"]];
        // }
        // return ["erro"=>true, "mensagem"=>"O identificar enviado não é válido"];
        
        
        
        // codigo do felipe
        
        switch($this->api['subtipo']){
            case 1:
                $sub = 'url';
                break;
            case 2:
                $sub = 'id';
                break;
            case 3:
                $sub = 'hash';
                break;
            default:
                return ["erro"=>true, "mensagem"=>"O parâmetro de indentificação não é válido"];
                break;
        }
        
        $url = trim($this->hash);
        $seleciona = "SELECT {$this->prefixo}_id as id FROM {$this->banco} WHERE {$this->prefixo}_{$sub}='$url'";
        
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return ["sucesso"=>true, "id"=>$dado["id"]];
        }
        return ["erro"=>true, "mensagem"=>"O identificar enviado não é válido"];
       
    }
    
    function view($id){
        $cadastra = "INSERT INTO analitycs_consumos 
        (analitycs_consumo_modulo,analitycs_consumo_banco,analitycs_consumo_identificador,analitycs_consumo_usuario,analitycs_consumo_digital) 
        VALUES (
          '{$this->modulo}', '{$this->banco}', '{$id}', '{$this->user}', '{$this->figerprint}'  
        )";
        
        return;
        $this->conn->query($cadastra);
    }
    
    function widgets(){
         $offset = ($this->page - 1) * $this->size; 
         $limit = $this->size;
         $itens = [];
        switch(intval($this->api["subtipo"] ?? 1)) {
            case 1: 
                //  Itens Populares - Visualizacoes Únicas
           
           $seleciona = "
           SELECT
           analitycs_consumo_identificador,
           SUM(CASE WHEN analitycs_consumo_usuario IS NOT NULL THEN 1 ELSE 0 END) AS visualizacoes_logado,
           SUM(CASE WHEN analitycs_consumo_digital IS NOT NULL THEN 1 ELSE 0 END) AS visualizacoes_deslogado
           FROM
           analitycs_consumos
           WHERE
           analitycs_consumo_banco = '{$this->banco}'
           GROUP BY
           analitycs_consumo_identificador
           ORDER BY
           (visualizacoes_logado + visualizacoes_deslogado) DESC
           LIMIT {$offset}, {$limit};
           ";
           return;
           $resultado_populares = $this->conn->query($seleciona);

           while ($row = $resultado_populares->fetch_assoc()) {
               $itens[] = $row['analitycs_consumo_identificador'];
           }

        break;

    case 2: // Mais acessados: Visualizacoes totais
        $seleciona = "
        SELECT
        analitycs_consumo_identificador,
        SUM(CASE WHEN analitycs_consumo_usuario IS NOT NULL THEN 1 ELSE 0 END) AS visualizacoes_logado,
        SUM(CASE WHEN analitycs_consumo_digital IS NOT NULL THEN 1 ELSE 0 END) AS visualizacoes_deslogado,
        COUNT(*) AS visualizacoes_totais
        FROM
        analitycs_consumos
        WHERE
        analitycs_consumo_banco = '{$this->banco}'
        GROUP BY
        analitycs_consumo_identificador
        ORDER BY
        visualizacoes_totais DESC
        LIMIT {$offset}, {$limit}
        ";
        return;
        $resultado_mais_acessados = $this->conn->query($seleciona);
        $itens_mais_acessados = [];
        if ($resultado_mais_acessados->num_rows > 0) {
            while ($row = $resultado_mais_acessados->fetch_assoc()) {
                $itens[] = $row['analitycs_consumo_identificador'];
            }
        } 

        break;

    case 3: // Novidades: Refere-se aos itens mais recentes.
        $seleciona = "SELECT {$this->prefixo}_id as id  FROM {$this->banco} ORDER BY {$this->prefixo}_data DESC LIMIT {$offset}, {$limit}";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $itens[] = $dado["id"];
            }
        }
        break;

    case 4: // Melhores Avaliados: Refere-se aos itens com as melhores avaliações.

        break;

    case 5: // Recomendados: Refere-se aos itens recomendados ao usuário.
       
        break;

    case 6: // Destacados: Refere-se aos itens em destaque.
    
        $coluna = $this->bd["estrutura"]["ordenador"] ? "ordem" : "id";
        $seleciona = "SELECT {$this->prefixo}_id as id FROM {$this->banco} WHERE {$this->prefixo}_destaque = '1' ORDER BY {$this->prefixo}_{$coluna} LIMIT 0, 12";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $itens[] = $dado["id"];
            }
        }
        
        break;
    case 7: // Mais Vendidos: Refere-se aos itens mais vendidos.
        $banco_safe = $this->conn->real_escape_string($this->banco);
        $seleciona = "
        SELECT 
        ppi_item, 
        COUNT(*) AS total_vendas
        FROM 
        pay_pedidos_itens
        WHERE 
        JSON_CONTAINS(ppi_espelho, '\"{$banco_safe}\"')
        GROUP BY 
        ppi_item
        ORDER BY 
        total_vendas DESC
        LIMIT 0, 12";

        $resultado_vendidos = $this->conn->query($seleciona);
        if ($resultado_vendidos->num_rows > 0) {
            while ($dado = $resultado_vendidos->fetch_assoc()) {
                $itens[] = $dado["ppi_item"];
                
            }
        } 
        
        break;

    case 8: // Em Promoção: Refere-se aos itens que estão em promoção.

        break;

    case 9: // Tendências: Refere-se aos itens que estão em alta ou sendo trend.
       
        break;

    case 10: // Relacionados: Refere-se aos itens relacionados a um item específico.
        

        break;

    case 11: // Mais comentados: Refere-se aos itens com mais comentários.
        $seleciona = "
        SELECT 
        comentario_identificador, 
        COUNT(*) AS total_comentarios
        FROM 
        comentarios 
        WHERE 
        comentario_modulo = '{$this->banco}'
        GROUP BY 
        comentario_identificador
        ORDER BY 
        total_comentarios DESC
        LIMIT {$offset}, {$limit};
        ";
        $resultado_comentados = $this->conn->query($seleciona);
        $itens = [];
        if ($resultado_comentados->num_rows > 0) {
            while ($dado = $resultado_comentados->fetch_assoc()) {
                $itens[] = $dado["comentario_identificador"];
            }
        }
        break;

    case 12: // Mais favoritados: Refere-se aos itens mais adicionados aos favoritos.
        $seleciona = "
        SELECT 
        favorito_identificador, 
        COUNT(*) AS total_favoritos
        FROM 
        favoritos 
        WHERE 
        favorito_modulo = '{$this->banco}'
        GROUP BY 
        favorito_identificador
        ORDER BY 
        total_favoritos DESC
        LIMIT {$offset}, {$limit};
        ";
        
        $resultado_favoritos = $this->conn->query($seleciona);
        $itens = [];
        if ($resultado_favoritos->num_rows > 0) {
            while ($dado = $resultado_favoritos->fetch_assoc()) {
                $itens[] = $dado["favorito_identificador"];
            }
        }
        break;

    case 13: // Favoritos do usuário: Refere-se aos itens favoritados pelo usuário logado.
        if($this->user){
            $seleciona = "SELECT * FROM favoritos WHERE favorito_modulo='$this->banco' AND 	favorito_autor='{$this->user}' ORDER BY favorito_data DESC  LIMIT {$offset}, {$limit}";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows){
            while($dado = $resultado->fetch_assoc()){
                $itens[] = $dado["favorito_identificador"];
            }
        } 
        }

        break;

    case 14:
        // Visualizados Recentemente
        $seleciona  = "
    SELECT DISTINCT
        analitycs_consumo_identificador
    FROM
        analitycs_consumos
    WHERE
        analitycs_consumo_banco = '{$this->banco}' AND
        (
            (analitycs_consumo_usuario = '{$this->user}' AND analitycs_consumo_usuario IS NOT NULL) OR
            (analitycs_consumo_digital = '{$this->fingerprint}' AND analitycs_consumo_digital IS NOT NULL)
        ) AND
        analitycs_consumo_data > NOW() - INTERVAL 30 DAY 
    ORDER BY
        analitycs_consumo_data DESC
    LIMIT {$offset}, {$limit}
    ";
        return;
        $resultado_populares = $this->conn->query($seleciona_populares);

           while ($row = $resultado_populares->fetch_assoc()) {
               $itens[] = $row['analitycs_consumo_identificador'];
           }


        break;
    case 15:
        $itens = $this->comprados();

      

        break;
    default: // Caso não previsto: Refere-se a uma opção inválida ou desconhecida.
       // Itens comprados
       // itens no carrinho
        break;
}


    return ["sucesso"=>true, "ids"=>$itens];
    }
    
    function comprados(){
        $itens = [];
        $map = [];
    $seleciona = "
    SELECT 
        ppi_pedido AS pedido , ppi_espelho as espelho
    FROM 
        pay_pedidos_itens 
    WHERE 
        ppi_usuario = '{$this->user}' 
        AND JSON_EXTRACT(ppi_espelho, '$.banco') = '{$this->banco}'";

$resultado = $this->conn->query($seleciona);
$ids = [];
if ($resultado->num_rows > 0) {
    while ($dado = $resultado->fetch_assoc()) {
        $ids[] = $dado["pedido"];
        $json = json_decode($dado["espelho"], true);
        $map[$dado["pedido"]] = $json["referencia"];
    }
}

if (!empty($ids)) {
    $lista = implode(",", $ids);
    
    $seleciona = "SELECT pedido_id FROM pay_pedidos WHERE pedido_estado > 1 AND pedido_id IN ($lista) ORDER BY pedido_data DESC";
    $resultado = $this->conn->query($seleciona);
    
  
    if ($resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            if(isset($map[$dado["pedido_id"]])){
                 $itens[] = $map[$dado["pedido_id"]];
            }
           
        }
    }
}
        return $itens;
    }
    
    function agrupamento(){
        $itens = [];
        $final = [];
        $w = "";
        $e = "";
        if(isset($this->bd["wildcard"]) && $this->bd["wildcard"]){
            $w = "WHERE {$this->prefixo}_wildcard = '{{$this->wildcard}}'";
            $e = "AND {$this->prefixo}_wildcard = '{{$this->wildcard}}'";
        }
        
        
        switch(intval($this->api["subtipo"] ?? 1)){
            case 1:
                // Autores
                $ids = [];
                $seleciona = "SELECT {$this->prefixo}_autor AS id, COUNT(*) AS total FROM {$this->banco} {$w} GROUP BY {$this->prefixo}_autor";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $itens[$dado["id"]]["total"] = $dado["total"];
                        $ids[] = $dado["id"];
                    }
                }
                
        
                if(!empty($ids)){
                    $lista = implode(",", $ids);
                    $seleciona = "SELECT usuario_display as display, usuario_user as user, usuario_foto as foto, usuario_capa as capa, usuario_id as id FROM usuarios WHERE usuario_id IN ($lista)";
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $final[] = [
                                "nome"=>$dado["display"],
                                "user"=>$dado["user"],
                                "foto"=>$dado["foto"],
                                "capa"=>$dado["capa"],
                                "total"=>$itens[$dado["id"]]["total"]
                                ];
                        }
                    }
                }
                
                break;
            case 2:
                $ids = [];
                $sem = 0;
                $seleciona = "SELECT {$this->prefixo}_categoria AS id, COUNT(*) AS total FROM {$this->banco} {$w} GROUP BY {$this->prefixo}_categoria";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        if($dado["id"]){
                            $itens[$dado["id"]]["total"] = $dado["total"];
                            $ids[] = $dado["id"];
                        }else{
                            $sem++;
                        }
                    }
                }
                
        
                if(!empty($ids)){
                    $lista = implode(",", $ids);
                    $seleciona = "SELECT categoria_nome as nome, categoria_url as url , categoria_id as id FROM categorias WHERE categoria_id IN ($lista)";
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $final[] = [
                                "nome"=>$dado["nome"],
                                "user"=>$dado["url"],
                                "total"=>$itens[$dado["id"]]["total"]
                                ];
                        }
                    }
                }
                
                
                return ["sucesso"=>true, "itens"=>$final, "orfaos"=>$sem];
                // Categorias
                break;
            case 3:
                // Tags
                $tags = [];
                $ids = [];
                $seleciona = "SELECT {$this->prefixo}_tags as tags FROM {$this->banco} WHERE {$this->prefixo}_tags IS NOT NULL {$e}";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $obj = json_decode($dado["tags"], true);
                        foreach($obj as $item){
                            if(!isset($tags[$item])){
                                $tags[$item] = 0;
                                $ids[] = $item;
                            }
                            $tags[$item]++;
                        }
                        
                    }
                 
                }
                
                 if(!empty($ids)){
                    $lista = implode(",", $ids);
                    $seleciona = "SELECT categoria_nome as nome, categoria_url as url , categoria_id as id FROM categorias WHERE categoria_id IN ($lista)";
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $final[] = [
                                "nome"=>$dado["nome"],
                                "user"=>$dado["url"],
                                "total"=>$tags[$dado["id"]]
                                ];
                        }
                    }
                }
                
                break;
        }
        return ["sucesso"=>true, "itens"=>$final];
        
        
    }
     
    function render(){
        if(!$this->chave || !$this->modulo){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os parametros válidos"];
        }
        
        $api = loadFile($this->modulo, $this->chave, "apis");
        if(isset($api["erro"])){
            return $api;
        }
        
        
        
        $this->api = $api["conteudo"];
        
        
        if($this->api["clone"]["ativo"] ?? false){
            $clone = trim($this->api["clone"]["api"]);
            
            $apiclone = loadFile($this->modulo, $clone , "apis");
            
            if(isset($apiclone["erro"])){
                return ["erro"=>true, "mensagem"=>"O clone referênciado não é válido"];
            }
            
            
            $clone = $apiclone["conteudo"];

            $this->api["itens"] = $clone["itens"];
            $this->api["number"] = $clone["number"];
            
        }
        
        $banco = loadFile($this->modulo, $this->api["banco"], "configs");
        if(isset($banco["erro"])){
            return $banco;
        }
        

        $this->bd = $banco["conteudo"];
        
        $this->banco = $this->bd["banco"];
        $this->prefixo = $this->bd["prefixo"];
        if($this->bd["meta"]){
            $this->bancoMeta = $this->banco."_meta";
            $this->iniciais = iniciais($this->bancoMeta);
        }
        

        $tipo = intval($this->api["tipo"] ?? 1);
        
        switch($tipo){
            case 1:
                // Um item
                $id = $this->getId();
                if(isset($id["erro"])){
                    return $id;
                }
                $this->view($id["id"]);
                 return $this->lista([$id["id"]]);
                break;
            case 2:
                // Uma lista
                return $this->lista();
                break;
            case 3:
                // Um agrupamento
                return $this->agrupamento();
                break;
            case 4:
                // Criar // Atualizar
                break;
            case 5:
                // Deletar
                break;
            case 6:
                // Widgets
                $ids = $this->widgets();
                if(isset($ids["erro"])){
                    return $ids;
                }
                
                
                if(empty($ids["ids"])){
                    return ["erro"=>true, "mensagem"=>"Nenhum item dentro do grupo de widgets"];
                }
                
                return $this->lista($ids["ids"]);
                break;
            case 7:
                if(empty($this->passados)){
                    return ["erro"=>true, "mensagem"=>"Os itens passados não são válidos"];
                }
                $lista = $this->passados;
                return $this->lista($lista);
                break;
        }
    }
}


$api = new Api();
$resposta = $api->render();
$api->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>