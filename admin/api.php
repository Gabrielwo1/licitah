<?
header('Content-Type: application/json; charset=utf-8');

include __DIR__.'/conn.php';
include __DIR__."/core.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


/*
Tipos :
1- Item
2- Lista
3 - Agrupamentos :
Exemplo: Lista de Categorias, Lista de Autores
*/

class Api{
    public $modulo;
    public $chave;
    public $api;
    public $hash;
    public $quantidade;
    public $pagina;
    public $paginacao;
    public $usuario;
    public $conn;
    public $banco;
    public $prefixo;
    public $meta;
    public $orderDate;
    public $fullbanco;
    public $caminhoFile;
    public $funcao;
    public $wildcard;
    
    function __construct(){
        $this->modulo = $_POST["modulo"] ?? false;
        $this->chave = $_POST["chave"] ?? false;
        $this->hash = $_POST["hash"] ?? false;
        $this->quantidade = $_POST["quantidade"] ?? 10;
        $this->pagina = $_POST["pagina"] ?? 1;
        $this->paginacao = $_POST["paginacao"] ?? false;
        $this->usuario = $_SESSION["id"]  ?? false;
        $this->funcao = $_SESSION["funcao"] ?? -1;
        

        $this->conn = conn();
        
        $this->wildcard = 0;
        $wildcard = $_POST["wildcard"] ?? 0;
        
        
        if($wildcard){
            $this->parseWild($wildcard);
        }
        
        $this->carregarAPI();
        $this->loadDb();
        
        
    }
    
    function parseWild($wild){
        $seleciona = "SELECT wc_id FROM wildcards_contas WHERE wc_url='$wild'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $this->wildcard = $dado["wc_id"];
        }
    }
    
    public function carregarAPI() {
        if ($this->chave && $this->modulo) {
            $pasta = __DIR__ . "/../conteudo/modulos/" . $this->modulo . "/admins/apis/" . $this->chave . ".json";

            if (file_exists($pasta)) {
                $api = file_get_contents($pasta);
                
                
                try{
                    $this->api = json_decode($api, true);

                    
                    if($this->api["tipo"] == 2 & $this->api["subtipo"] == 2 && !$this->hash){
                        $this->hash = $this->usuario;
                    }

                    
                    
                }catch(Exception $e){
                     $this->api = false;
                }

            } else {
                $this->api = false;
            }
        }
    }
    
    public function loadDb(){
        if($this->api){
            $banco = $this->api["banco"];
            $this->caminhoFile = $banco;
            $pasta = __DIR__ . "/../conteudo/modulos/" . $this->modulo . "/admins/configs/" . $banco . ".json";
            if (file_exists($pasta)) {
                $banco = file_get_contents($pasta);

                try{
                    $bancoDeDados = json_decode($banco, true);
                    $this->fullbanco = $bancoDeDados;
                    $this->banco = $bancoDeDados["banco"];
                    $this->prefixo = $bancoDeDados["prefixo"];
                    $this->meta = $bancoDeDados["meta"];
                    $this->orderDate = $banco["estrutura"]["dataCriacao"] ?? false;
                }catch(Exception $e){
                     $this->banco = false;
                }
            } else {
                $this->banco = false;
            }
        }
        
    }
    
    function metas(){
        
    }
    
    function pegaItem(){
        $banco = $this->banco;
        $prefixo = $this->prefixo;
        $meta = $this->meta;
        $hash = $this->hash;
        
        $query = [$this->prefixo."_id"];
        $maps = [];
        $metas = [];
        $estrangeiras = [];
        $ids = [];
        
        $atributos = [];
        $pegaAtributos = false;

        foreach($this->api["itens"] as $item){
            
             if(isset($item["estrangeiro"]) && $item["estrangeiro"]){
                
                $estrangeiras[$item["nome"]] = $item["setupEstrangeira"];
                $ids[$item["chave"]] = [];

                
            }
            
            if($item["banco"] == "p"){
                
                if($item["nome"] != "render"){
                       if($item["nome"] == "atributos"){
                    $pegaAtributos = true;
                }else{
                     array_push($query, $prefixo."_".$item["nome"]);
                     $maps[$item["chave"]] = $prefixo."_".$item["nome"];
                }
                }
                
             
               
            }else{
                array_push($metas, $item);
            }
            
        }
       
        switch(intval($this->api["subtipo"])){
            case 1:
                $id = $prefixo."_url";
                break;
            case 2:
                $id = $prefixo."_id";
                break;
            case 3:
                $id = $prefixo."_hash";
                break;
        }
        
        if($banco == "usuarios" && intval($this->api["subtipo"]) == 3){
            $id = "usuario_user";
        }
        
        $fixos = implode(",", $query);
        $seleciona = "SELECT ".$fixos." FROM ".$banco." WHERE ".$id."='".$hash."'";

        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $identificador = $dado[$prefixo."_id"];
            
            $item = [];
            foreach($maps as $chave=>$valor){
                
                if(isset($ids[$chave])){
                    array_push($ids[$chave], $dado[$valor]);
                }
                
                
                $item[$chave] = $dado[$valor];
            }
            
            
            $id = $dado[$this->prefixo."_id"];

            if(count($metas) > 0 && $meta){
                $bancoMeta = $banco."_meta";
                $trato = explode("_", $bancoMeta);
                $start = "";
                foreach($trato as $c){
                    $start .= $c[0];
                }
                
                $respostas = [];
                $identificador = $start."_".$prefixo;
                $chave = $start."_chave";
                $valor = $start."_valor";
                $seleciona = "SELECT ".$chave.", ".$valor." FROM ".$bancoMeta." WHERE ".$identificador."='".$id."' ";
              
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
           
                    while($dado = $resultado->fetch_assoc()){
                        $respostas[$dado[$chave]] = $dado[$valor];
                    }
                    
                    
                    foreach($metas as $m){
                        if(isset($respostas[$m["nome"]])){
                            $item[$m["chave"]] = $respostas[$m["nome"]];
                        }
                    }
                    
                }
            }
            
            
            
            if($pegaAtributos && $identificador){

                $item["atributos"] = $this->pegaAtributos($identificador, $banco);
            }
 
            return ["sucesso"=>true, "item"=>$item, "estrangeiras"=>$this->pegaEstrangeira($estrangeiras, $ids)];
        }else{
            return ["sucesso"=>true, "item"=>false];
        }
     
        
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
    
    function infoTabelaBanco($tabela) {
    
    $tabela = $this->conn->real_escape_string($tabela);

   
    $query = "SHOW TABLES LIKE '$tabela'";

    $resultado = $this->conn->query($query);

    if (!$resultado || $resultado->num_rows == 0) {
        // Retorna um array vazio se a tabela não existir ou se houver erro
        return [];
    }

    // Obtém as colunas da tabela
    $query = "SHOW COLUMNS FROM `$tabela`";
    $resultado = $this->conn->query($query);

    if (!$resultado) {
        // Retorna um erro caso a consulta falhe
        throw new Exception("Erro na execução da consulta: " . $this->conn->error);
    }

    $columns = [];
    while ($row = $resultado->fetch_assoc()) {
        $columns[] = $row['Field'];
    }

    return $columns;
}
    
    function pegaLista($array = false){
        $banco = $this->banco;
        $prefixo = $this->prefixo;
        $meta = $this->meta;
        $hash = $this->hash;
        
        $extra = false;
        
        $idsTodos = [];
        
         $inicio = (intval($this->pagina) - 1) * intval($this->quantidade);
         
         if($this->paginacao){
             $limit = "LIMIT ".$inicio." ,  ".$this->quantidade."";
         }else{
             $limit = "";
         }
         
   
        if(intval($this->api["subtipo"]) > 1 && !$hash){
            return ["erro"=>true, "mensagem"=>"É necessario enviar um indentificador válido"];
        }
        
        $query = [$this->prefixo."_id"];
        
         $maps = [];
        
        
        $haverOrder = false;
        $infoTabela = $this->infoTabelaBanco($banco);
        if(in_array($this->prefixo."_ordem", $infoTabela)) {
            array_push($query , $this->prefixo."_ordem"); 
            $haverOrder = true;
            
            $identificador = $this->prefixo."_ordem";
            
            $maps["ordem"] = $this->prefixo."_ordem";


        }else{
            if($this->orderDate){
            $identificador = $this->prefixo."_data";
            }else{
            $identificador = $this->prefixo."_id";
            }
        }
        
        
       
  
       
        $metas = [];
        $estrangeiras = [];
        $ids = [];
        foreach($this->api["itens"] as $item){
      
            

            if(isset($item["estrangeiro"]) && $item["estrangeiro"]){
                
                $estrangeiras[$item["nome"]] = $item["setupEstrangeira"];
                
                
                $ids[$item["chave"]] = [];
                
            }
            

            if($item["banco"] == "p"){
                array_push($query, $prefixo."_".$item["nome"]);
                $maps[$item["chave"]] = $prefixo."_".$item["nome"];
            }else{
                array_push($metas, $item);
            }
            
        }
        
        
        
        
        $fixo = implode(",", $query);
        
        
        $ww = "";
        $we = "";
        $pega = "SELECT count(*) as total FROM " . $banco;
        $resultado = $this->conn->query($pega);
        $dado = $resultado->fetch_assoc();
        $totais = $dado["total"];

        $wildcard = "";
        if(in_array($this->prefixo."_wildcard", $infoTabela)){
            
            if($this->wildcard){
                $w = $this->wildcard;
            }else{
                $w = 0;
            }
            
            $ww = "WHERE ".$this->prefixo."_wildcard = '$w'";
            $we = "AND ".$this->prefixo."_wildcard = '$w'";
        }
        

        switch($this->api["subtipo"]){
            case 1:
                $seleciona = "SELECT ".$fixo." FROM ".$banco." ".$ww." ORDER BY ".$identificador." DESC ".$limit."";
                   
                break;
            case 2:
                $query = $prefixo."_autor";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";
       
                break;
            case 3:

                if(!filter_var($hash, FILTER_VALIDATE_INT)){
                     $cat = "SELECT * FROM categorias WHERE categoria_tipo='".$this->api["banco"]."' AND categoria_url='$hash'";

                    $res = $this->conn->query($cat);
                    if($res->num_rows == 0){
                        return ["erro"=>true, "mensagem"=>"Categoria Não Existe"];
                    }
                    $dado = $res->fetch_assoc();
                    $hash = $dado["categoria_id"];
                    $extra = [
                        "nome"=>$dado["categoria_nome"],
                        "url"=>$dado["categoria_url"]
                        ];
                }
                
                
                $isCat = intval($dado["categoria_iscat"]);
                if($isCat === 1 ){
                    $query = $prefixo."_categoria";
                    $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";
                }else{
                    $query = $prefixo."_tags";
                    $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE JSON_CONTAINS(".$query.", '".$hash."', '$') ORDER BY ".$identificador." DESC ".$limit."";
                }
      
                break;
            case 4:
                $query = $prefixo."_tags";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";

                break;
            case 5:
                $arrayIds = "'" . implode("','", $array) . "'";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$this->prefixo."_id IN ($arrayIds)";
                break;
        }
        
 
    
        $resultado = $this->conn->query($seleciona);
         
        
        $lista = [];
        if($resultado->num_rows > 0){
      
            while($dado = $resultado->fetch_assoc()){
                $item = [];
                
               
                //print_r($ids);


                foreach($maps as $chave=>$valor){
                    
                    if(isset($ids[$chave])){
                        array_push($ids[$chave], $dado[$valor]);
                    }
                    
                    $item[$chave] = $dado[$valor];
                }
                
                if($meta){
                   $bancoMeta = $banco."_meta";
                $trato = explode("_", $bancoMeta);
                $start = "";
                foreach($trato as $c){$start .= $c[0];}
                
                $identificador = $start."_".$prefixo;
                $chave = $start."_chave";
                $valor = $start."_valor";
                $id = $dado[$this->prefixo."_id"];
                array_push($idsTodos, $id);
                $seleciona = "SELECT ".$chave.", ".$valor." FROM ".$bancoMeta." WHERE ".$identificador."='".$id."' ";
                  $res = $this->conn->query($seleciona);
                  if($res->num_rows > 0){
           
                    while($info = $res->fetch_assoc()){
                        $respostas[$info[$chave]] = $info[$valor];
                    }
    
                    
                    foreach($metas as $m){
                        if(isset($respostas[$m["nome"]])){
                            $item[$m["chave"]] = $respostas[$m["nome"]];
                        }
                    }
                    
                }  
                }
                
               
                  

                
                
                
      
                array_push($lista, $item);
            
                
            }
        }
        
            
            
            $numbers = [];
            
            if(!empty($idsTodos)){
                  if($this->api["number"]["comentarios"] ?? false){
                $numbers["comentarios"] = $this->getComents($idsTodos);
            }
            
            if($this->api["number"]["reacoes"] ?? false){
                 $numbers["reacoes"] = $this->getReacts($idsTodos);
            }
            
            if($this->api["number"]["favoritos"] ?? false){
                 $numbers["favoritos"] = $this->getFavorits($idsTodos);
            }
            }
          
        


        return ["sucesso"=>true, "lista"=>$lista, "extra"=>$extra, "estrangeiras"=>$this->pegaEstrangeira($estrangeiras, $ids), "total"=>$totais, "numbers"=>$numbers];
    }
    
    function pegaListaNova($array = false){
        $banco = $this->banco;
        $prefixo = $this->prefixo;
        $meta = $this->meta;
        $hash = $this->hash;
        
        $extra = false;
        
        $idsTodos = [];
        
         $inicio = (intval($this->pagina) - 1) * intval($this->quantidade);
         
         if($this->paginacao){
             $limit = "LIMIT ".$inicio." ,  ".$this->quantidade."";
         }else{
             $limit = "";
         }
         
   
        if(intval($this->api["subtipo"]) > 1 && !$hash){
            return ["erro"=>true, "mensagem"=>"É necessario enviar um indentificador válido"];
        }
        
        $query = [$this->prefixo."_id as id"];
        
         $maps = [];
        
        
        $haverOrder = false;
        $infoTabela = $this->infoTabelaBanco($banco);
        if(in_array($this->prefixo."_ordem", $infoTabela)) {
            array_push($query , $this->prefixo."_ordem as ordem"); 
            $haverOrder = true;
            
            $identificador = $this->prefixo."_ordem";
            
            $maps["ordem"] = $this->prefixo."_ordem";


        }else{
            if($this->orderDate){
            $identificador = $this->prefixo."_data";
            }else{
            $identificador = $this->prefixo."_id";
            }
        }
        
        
       
        
        
        
        
       
        $metas = [];
        $estrangeiras = [];
        $ids = [];
        foreach($this->api["itens"] as $item){
      
            

            if(isset($item["estrangeiro"]) && $item["estrangeiro"]){
                
                $estrangeiras[$item["nome"]] = $item["setupEstrangeira"];
                
                
                $ids[$item["chave"]] = [];
                
            }
            

            if($item["banco"] == "p"){

                array_push($query, $prefixo."_".$item["nome"]." as ".$item["chave"]);
            }else{
                array_push($metas, $item);
            }
            
        }
        
        
        
        
        $fixo = implode(",", $query);
        
        
        $ww = "";
        $we = "";
        $pega = "SELECT count(*) as total FROM " . $banco;
        $resultado = $this->conn->query($pega);
        $dado = $resultado->fetch_assoc();
        $totais = $dado["total"];

        $wildcard = "";
        if(in_array($this->prefixo."_wildcard", $infoTabela)){
            
            if($this->wildcard){
                $w = $this->wildcard;
            }else{
                $w = 0;
            }
            
            $ww = "WHERE ".$this->prefixo."_wildcard = '$w'";
            $we = "AND ".$this->prefixo."_wildcard = '$w'";
        }
        

        switch($this->api["subtipo"]){
            case 1:
                $busca = "teste";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." ".$ww." ORDER BY ".$identificador." DESC ".$limit."";
                   
                break;
            case 2:
                $query = $prefixo."_autor";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";
       
                break;
            case 3:

                if(!filter_var($hash, FILTER_VALIDATE_INT)){
                     $cat = "SELECT * FROM categorias WHERE categoria_tipo='".$this->api["banco"]."' AND categoria_url='$hash'";

                    $res = $this->conn->query($cat);
                    if($res->num_rows == 0){
                        return ["erro"=>true, "mensagem"=>"Categoria Não Existe"];
                    }
                    $dado = $res->fetch_assoc();
                    $hash = $dado["categoria_id"];
                    $extra = [
                        "nome"=>$dado["categoria_nome"],
                        "url"=>$dado["categoria_url"]
                        ];
                }
                
                
                $isCat = intval($dado["categoria_iscat"]);
                if($isCat === 1 ){
                    $query = $prefixo."_categoria";
                    $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";
                }else{
                    $query = $prefixo."_tags";
                    $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE JSON_CONTAINS(".$query.", '".$hash."', '$') ORDER BY ".$identificador." DESC ".$limit."";
                }
      
                break;
            case 4:
                $query = $prefixo."_tags";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$query."='".$hash."' ORDER BY ".$identificador." DESC ".$limit."";

                break;
            case 5:
                $arrayIds = "'" . implode("','", $array) . "'";
                $seleciona = "SELECT ".$fixo." FROM ".$banco." WHERE ".$this->prefixo."_id IN ($arrayIds)";
                break;
        }
        
 
    
        $resultado = $this->conn->query($seleciona);
         
        
        $lista = [];
        if($resultado->num_rows > 0){
      
            while($dado = $resultado->fetch_assoc()){
                $item = $dado;
                
               
    

                foreach($maps as $chave=>$valor){
                    
                    if(isset($ids[$chave])){
                        array_push($ids[$chave], $dado[$valor]);
                    }
                    
                    $item[$chave] = $dado[$valor];
                }
       
                
                if($meta){
                   $bancoMeta = $banco."_meta";
                $trato = explode("_", $bancoMeta);
                $start = "";
                foreach($trato as $c){$start .= $c[0];}
                
                $identificador = $start."_".$prefixo;
                $chave = $start."_chave";
                $valor = $start."_valor";
                $id = $dado["id"];
                array_push($idsTodos, $id);
                $seleciona = "SELECT ".$chave.", ".$valor." FROM ".$bancoMeta." WHERE ".$identificador."='".$id."' ";
                  $res = $this->conn->query($seleciona);
                  if($res->num_rows > 0){
           
                    while($info = $res->fetch_assoc()){
                        $respostas[$info[$chave]] = $info[$valor];
                    }
    
                    
                    foreach($metas as $m){
                        if(isset($respostas[$m["nome"]])){
                            $item[$m["chave"]] = $respostas[$m["nome"]];
                        }
                    }
                    
                }  
                }
                
               
                  

                
               
                
      
                array_push($lista, $item);
            
                
            }
        }
        
            
            
            $numbers = [];
            
            if(!empty($idsTodos)){
                  if($this->api["number"]["comentarios"] ?? false){
                $numbers["comentarios"] = $this->getComents($idsTodos);
            }
            
            if($this->api["number"]["reacoes"] ?? false){
                 $numbers["reacoes"] = $this->getReacts($idsTodos);
            }
            
            if($this->api["number"]["favoritos"] ?? false){
                 $numbers["favoritos"] = $this->getFavorits($idsTodos);
            }
            }
          
        


        return ["sucesso"=>true, "lista"=>$lista, "extra"=>$extra, "estrangeiras"=>$this->pegaEstrangeira($estrangeiras, $ids), "total"=>$totais, "numbers"=>$numbers];
    }
    
    function getComents(){
        
    }
    
    function getReacts(){
        
    }
    
    function getFavorits($ids){
        $implode = implode(",", $ids);
        $banco = $this->banco;
        $user = $this->usuario;
        
        $arrayFinal = [];
        foreach($ids as $id){
            $arrayFinal[$id] = [
                "c"=>0,
                "i"=>0,
                ];
        }
        
        $seleciona = "SELECT favorito_identificador as id FROM favoritos WHERE favorito_modulo='$banco' AND favorito_identificador IN ($implode) AND favorito_autor='$user'";
 
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $arrayFinal[$dado["id"]]["i"] = true;
            }
        }
        
        $seleciona = "SELECT count(*) as total, favorito_identificador as id FROM favoritos WHERE favorito_identificador IN ($implode) AND favorito_modulo='$banco' GROUP BY favorito_identificador";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
               $arrayFinal[$dado["id"]]['c'] = $dado["total"]; 
            }
        }
        

        return $arrayFinal;
    }
    
    function pegaEstrangeira($estrangeiras, $ids){

        foreach($ids as $chave=>$valor){
            $ids[$chave] = array_values(array_unique($valor));
        }

        $final = [];
        
        

        
        foreach($estrangeiras as $chave=>$estrangeira){
            
            $ids[$chave] = array_filter($ids[$chave]);

        
      
            
            if(!empty($ids[$chave])){
                
     
        
                $identificadores = implode("," , $ids[$chave]);
            }else{
                $identificadores = false;
               
            }
            $final[$chave] = [];
   
            
            $modulo = $estrangeira["modulo"];
            $banco = $estrangeira["banco"];
            
            if(file_exists(__DIR__."/../conteudo/modulos/".$modulo."/admins/configs/".$banco)){

                $conteudo = json_decode(file_get_contents(__DIR__."/../conteudo/modulos/".$modulo."/admins/configs/".$banco), true);
                 
                 
    
                
                $bd = $conteudo["banco"];
                $px = $conteudo["prefixo"];
                
              
                
                $princs = [$px."_id as id"];
                if(!empty($estrangeira["principal"])){
                    
                    foreach($estrangeira["principal"] as $p){
                        array_push($princs, $px."_".$p." as ".$p);
                    }
                     
                    
                    $consulta = implode(",", $princs);
                    
                    if($identificadores){
                        $seleciona = "SELECT ".$consulta." FROM ".$bd." WHERE ".$px."_id IN (".$identificadores.")";
         
             

     
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $final[$chave][$dado["id"]] = $dado;
                        }
                    } 
                    }
                    
                   
 
                }
                
                
               
                if(!empty($estrangeira["metas"])){
                    
                     $metas = [];
                foreach($estrangeira["metas"] as $m){
                    array_push($metas, "'$m'");
                }
                
                $metas = implode(",", $metas);
                    
                    
                    
                    
                    $pre = "";
                    $bd = $bd."_meta";
                    $explode = explode("_", $bd);
                    foreach($explode as $e){
                        $pre .= $e[0];
                    }
                    
                    $idex = $pre."_".$px;
                    if($identificadores){
                      $seleciona = "SELECT ".$idex." as id, ".$pre."_chave as chave, ".$pre."_valor as valor FROM ".$bd." WHERE ".$pre."_chave IN (".$metas.") AND ".$idex." IN (".$identificadores.")";
    
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $final[$chave][$dado["id"]][$dado["chave"]] = $dado["valor"];
                        }
                    }  
                    }
                    

                }
    

            }
            
         
            
            
            
        }
        
        return $final;
    }
    
    function pegaAgrupamento(){
        $tipo = $this->api["subtipo"] ?? false;
        
        switch(intval($tipo)){
            case 1:
                // Autor
                break;
            case 2:
            case 3:
                // Categoria
                //print_r($this);
                $banco = $this->api["banco"];
                $cat = intval($tipo) == 2 ? 1 : 0;
                $seleciona = "SELECT * FROM categorias WHERE categoria_tipo='$banco' AND categoria_iscat='$cat'";
                $resultado = $this->conn->query($seleciona);
                $lista = [];
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        array_push($lista, [
                            "nome"=>$dado["categoria_nome"], 
                            "url"=>$dado["categoria_url"]
                            ])
                        ;
                    }
                }
                return ["sucesso"=>true, "lista"=>$lista];
                break;

            default:
                return ["erro"=>true, "mensagem"=>"Subtipo definido inválido"];
                break;
        }
    }
    
    function novo(){
        $info = $_POST["info"] ?? false;
        if(!$info){
            return ["erro"=>true, "mensagem"=>"Dados não informados"];
        }
        
        $info = json_decode($info, true);
        
        $mapa = [];
        foreach($this->fullbanco["colunas"] as $item){
            $mapa[$item["nome"]] = $item["input"];
        }
        
        $request = [];
        foreach($info as $chave=>$item){
            $request[$mapa[$chave]] = $item;
        }
        
        $_POST = [
            'tipo' => 'salvar',
            'hash' => false,
            'data' => json_encode($request),
            'identificador' => $this->caminhoFile,
            'modulo' => $this->modulo,
            'master' => false
        ];
        $acao = new Acao();
        $resposta = $acao->render();
        return $resposta;
 
    }
    
    function deletar(){
        
    }
    
    function processa(){
        if($this->api["tipo"] == "1" && !$this->hash){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um identificador válido. Normamalmente um pamametro de URL"];
        }
        
        $aut = $this->contabiliza($this->hash);
        if(!$aut){
            return ["erro"=>true, "mensagem"=>"Você estourou o limite de consumo da API"];
        }
        
        
        switch(intval($this->api["tipo"])){
            case 1:
                return $this->pegaItem();
                break;
            case 2:
                return $this->pegaLista();
                break;
            case 3:
                return $this->pegaAgrupamento();
                break;
            case 4:
                return $this->novo();
                break;
            case 5:
                return $this->deletar();
                break;
            case 6:
                include __DIR__."/apianalitica.php";
                $acao = new ApiAnalitica($this);
                $retorno = $acao->render();
        
                
                
                $this->api["subtipo"] = 5;
                $this->hash = "nown";
              
                if(!empty($retorno["ids"])){
                   $lista = $this->pegaLista($retorno["ids"]);
                   
                   if(!empty($retorno["ids"])){
                       
                  
                       
                       
                   }else{
                        return ["sucesso"=>true, "lista"=>[]];
                   }

                }else{
                    return ["sucesso"=>true, "lista"=>[]];
                    }
                break;
        }
        
        return [
            "banco"=>$this->banco, 
            "api"=>$this->api
            ];
    }
    
    function contabiliza($caminho){
        $funcao = $this->funcao;
        $modulo = $this->modulo;
        $chave = $this->chave;
        
        $seleciona = "SELECT * FROM funcoes_apis WHERE funcao_api_funcao='$funcao' AND funcao_api_modulo='$modulo' AND funcao_api_key='$chave' AND 	funcao_api_limite='1' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return true;
        }
        
        $dado = $resultado->fetch_assoc();
        
        $quantidade = intval($dado["funcao_api_quantidade"]);
        if($quantidade == 0){
            return false;
        }
        
        $id = $dado["funcao_api_id"];
        $reacessivel = $dado["funcao_api_reacessivel"];
        $reacessivelQuantidade = $dado["funcao_api_reacessivel_quantidade"];
        $reacessivelTipo = $dado["funcao_api_reacessivel_tipo"];
        $renovavel = $dado["funcao_api_renovavel"];
        $renovavelQuantidade = $dado["funcao_api_renovavel_quantidade"];
        $renovavelTipo = $dado["funcao_api_renovavel_tipo"];
        
                $usuario = $this->usuario;
        if($renovavel){
              switch ($renovavelTipo) {
            case 0:
                 $calc = 24 * 60 * 60 * 365;
                break;
            case 1:
                $calc = $renovavelQuantidade * 60;
                break;
            case 2:
                $calc = $renovavelQuantidade * 60 * 60;
                break;
            case 3:
                $calc = $renovavelQuantidade * (24 * 60 * 60);
                break;
            }
            
            $deleta = "DELETE FROM usuarios_api_consumo WHERE apc_regra='$id' AND apc_autor='$usuario' AND apc_data < (NOW() - INTERVAL $calc SECOND)";

            $this->conn->query($deleta);
            
            
        }

        $seleciona = "SELECT * FROM usuarios_api_consumo WHERE apc_regra='$id' AND	apc_autor='$usuario'";
        $resultado = $this->conn->query($seleciona);
        $q = $resultado->num_rows;
        
        $permitido = true;
        if($q >= $quantidade){
              $permitido = false;
        }
     
        
       $cadastra = true;
       if ($q > 0) {
           $dados = $resultado->fetch_assoc();
           $data = strtotime($dados["apc_data"]); 
           $dataAtual = time(); 
           
           if ($reacessivel) {
               switch ($reacessivelTipo) {
            case 0:
                 $calc = 24 * 60 * 60 * 365;
                break;
            case 1:
                $calc = $reacessivelQuantidade * 60;
                break;
            case 2:
                $calc = $reacessivelQuantidade * 60 * 60;
                break;
            case 3:
                $calc = $reacessivelQuantidade * (24 * 60 * 60);
                break;
            }
            
            
             if ($cadastra) {


            $seleciona = "
                SELECT * 
                FROM usuarios_api_consumo 
                WHERE apc_regra='$id' 
                AND apc_autor='$usuario'  
                AND apc_caminho='$caminho' 
                AND apc_data > (NOW() - INTERVAL $calc SECOND)
            ";
            }
        $teste = $this->conn->query($seleciona);
        if($teste->num_rows > 0){
              return true;
        }



        }
            else{
               
            }
           
       }

        
        

        if($cadastra && $permitido){
            $cadastra = "INSERT INTO usuarios_api_consumo (apc_regra, apc_autor, apc_caminho) VALUES ('$id', '$usuario', '$caminho')";
            $this->conn->query($cadastra);
        }

        return $permitido;
         
 
        
    }
    
    function close(){
        $this->conn->close();
    }
    
    function render(){
        if(!$this->modulo || !$this->chave){
            return ["erro"=>true, "mensagem"=>"É necessario o envio de um módulo e chave ativos"];
        }
        
        if(!$this->api){
            return ["erro"=>true, "mensagem"=>"A API solicitada não é valida", "limiteApi"=>true];
        }

        return $this->processa();

    }
}



$acao = new Api();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta,  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>