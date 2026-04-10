<?
class Vendavel{
    public $banco;
    public $id;
    public $conn;
    public $referencia;
    function __construct($banco, $id, $referencia = false){
        $this->banco = $banco;
        $this->id = $id;
        $this->conn = conn();
        $this->referencia = $referencia;
    }
    
    function hasher($length = 32) {
    // Calcula o número de bytes necessários
    $bytes = ceil($length * 0.75); // base64_encode gera uma string 4/3 vezes maior
    $randomBytes = random_bytes($bytes); // Gera bytes aleatórios
    $randomString = base64_encode($randomBytes); // Codifica os bytes em base64
    
    // Retorna a substring com o comprimento desejado
    return substr(str_replace(['+', '/', '='], '', $randomString), 0, $length);
}
    
    function metas($metas, $id){
    foreach($metas as $chave => $valor){
        if($valor){
             

            $seleciona = "SELECT vm_id FROM vendaveis_meta WHERE vm_vendavel='$id' AND vm_chave='$chave'";
            $resultado = $this->conn->query($seleciona);
         
            if($resultado->num_rows == 0){
             
                $inserir = "INSERT INTO vendaveis_meta (vm_vendavel, vm_chave, vm_valor) VALUES ('$id', '$chave', '$valor')";
                $this->conn->query($inserir);
                
            } else {
                
                $dado = $resultado->fetch_assoc();
                $vm_id = $dado["vm_id"];
                $atualizar = "UPDATE vendaveis_meta SET vm_valor='$valor' WHERE vm_id='$vm_id'";
                
                $this->conn->query($atualizar);
            }
        }
    }
}

    function novo($data){
        
        
        
        $id = $this->id;
        $banco = $this->banco;
        
        $preco = $this->trataPreco($data["preco"]);
        $tipo = $data["tipo"];
        $limite = $data["limite"];
        $hash = $this->hasher();
        $estoque = $data["estoque"];
        $zerar = $data["zerar"];
        $recorrente = $data["recorrente"];
        $pontos = $data["pontos"];
        $loja = intval($data["loja"] ?? 0);
        $referencia = $this->referencia ?? false;
        $cadastra = "INSERT INTO  vendaveis 
        (vendavel_banco, vendavel_referencia , vendavel_hash, vendavel_preco, vendavel_tipo, vendavel_controleestoque, vendavel_limitador, vendavel_zera, vendavel_recorrente,vendavel_pontos, vendavel_referenciabd, vendavel_loja) VALUES 
        ('$banco', '$id', '$hash', '$preco', '$tipo', '$estoque', '$limite', '$zerar', '$recorrente', '$pontos', '$referencia', '$loja')";

        if($this->conn->query($cadastra) == true){
            $id = $this->conn->insert_id;
            $this->metas($data["metas"], $id);
            return $id;
        }else{
            print_r($this->conn->error);
            return false;
        }
        
    }
    
    function atualiza($data){
        
       
        
         $id = $this->id;

        $banco = $this->banco;
        $preco = $this->trataPreco($data["preco"]);
        $tipo = $data["tipo"];
        $limite = $data["limite"];
        $hash = $this->hasher();
        $estoque = $data["estoque"];
        $zerar = $data["zerar"];
        $pontos = $data["pontos"];
        $loja = intval($data["loja"] ?? 0);
        
         $atualiza = "UPDATE vendaveis SET 
         vendavel_preco='$preco',
         vendavel_controleestoque = '$estoque', 
         vendavel_limitador = '$limite',
         vendavel_zera = '$zerar',
         vendavel_pontos='$pontos',
         vendavel_loja='$loja'
         WHERE vendavel_banco='$banco' AND vendavel_referencia='$id'";
         if($this->conn->query($atualiza)){
             $pegaPai = "SELECT vendavel_id FROM vendaveis WHERE vendavel_banco='$banco' AND vendavel_referencia='$id'";
             $resultado = $this->conn->query($pegaPai);
             if($resultado->num_rows == 1){
                 $dado = $resultado->fetch_assoc();
                 $idPai = $dado["vendavel_id"];
                 $this->metas($data["metas"], $idPai);
             }
         }
    }
    
    function render(){
        
    }
    
    function trataPreco($stringNumber){
       
        $floatNumber = floatval($stringNumber);
        $formattedNumber = number_format($floatNumber, 2);
        return $formattedNumber;
    }
    
    function info($id){
        $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id='$id'";
  
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        
        $metas = [];
        
        
        $seleciona = "SELECT * FROM vendaveis_meta WHERE vm_vendavel='$id'";
        $resultado = $this->conn->query($seleciona);
       if ($resultado->num_rows > 0) {
           while ($info = $resultado->fetch_assoc()) {
               $metas[$info["vm_chave"]] = $info["vm_valor"];
           }
       }
        
   
        return [
            "preco"=>$dado["vendavel_preco"],
            "estoque"=>$dado["vendavel_controleestoque"],
            "zerar"=>$dado["vendavel_zera"],
            "limitador"=>$dado["vendavel_limitador"],
            "metas"=>$metas,
            "tamanho"=>$seleciona,
            "pontos"=>$dado["vendavel_pontos"] ?? 0,
            "loja"=>$dado["vendavel_loja"] ?? 0,
            ];
    }
    
}

class Meta{
    public $banco;
    public $iniciais;
    public $id;
    public $conn;
    function __construct($banco, $prefixo, $conn){
        $this->banco = $banco."_meta";
        $this->iniciais = $this->iniciais($this->banco);
        $this->id = $this->iniciais."_".$prefixo;
        $this->conn = $conn;
    }
    
    function iniciais($texto) {
          $palavras = explode('_', $texto);
          $resultado = '';
          
          foreach ($palavras as $palavra) {
              $resultado .=  strtolower(substr($palavra, 0, 1));
          }
          
          return $resultado;
      }
    
    function listar($id){
        $seleciona = "SELECT ".$this->iniciais."_chave , ".$this->iniciais."_valor FROM ".$this->banco." WHERE ".$this->id."='$id'";

       $resultado = $this->conn->query($seleciona);
       $lista = [];
       if($resultado->num_rows > 0){
           
           while($dado = $resultado->fetch_assoc()){
               $lista[$dado[$this->iniciais."_chave"]] = $dado[$this->iniciais."_valor"];
           }
          

       }
       return $lista;
    }
    
    function adicionar($pai , $chave, $valor){
        $seleciona = "SELECT * FROM ".$this->banco." WHERE ".$this->id."='$pai' AND ".$this->iniciais."_chave='$chave'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $acao = "INSERT INTO ".$this->banco." (".$this->id.", ".$this->iniciais."_chave, ".$this->iniciais."_valor) VALUES ('$pai', '$chave', '$valor')";
        }else{
            $acao = "UPDATE ".$this->banco." SET ".$this->iniciais."_valor='$valor' WHERE ".$this->id."='$pai' AND ".$this->iniciais."_chave='$chave'";
        }
  
        $this->conn->query($acao);
    }
    
    function editar(){
        
    }
    
    function apagartudo($id){
        $deleta = "DELETE FROM ".$this->banco." WHERE ".$this->id."='".$id."'";
        $this->conn->query($deleta);
        
    }
    
    function item($chave, $id){
       $seleciona = "SELECT ".$this->iniciais."_valor FROM ".$this->banco." WHERE  ".$this->iniciais."_chave='$chave' AND ".$this->id."='$id'";
       $resultado = $this->conn->query($seleciona);
       if($resultado->num_rows == 1){
           $dado = $resultado->fetch_assoc();
           return $dado[$this->iniciais."_valor"];
       }else{
           return false;
       }
    }
}
 
class Acao{
    public $identificador;
    public $modulo;
    public $tipo;
    public $autor;
    public $diretorio;
    public $banco;
    public $meta;
    public $prefixo;
    public $conn;
    public $data;
    public $hash;
    public $master;
    public $tabela;
    public $db;
    public $queryId;
    public $formulario;
    public $disponiveis;
    public $modal;
    public $unicidade;
    public $novoId;
    public $mode;
    public $calendario;
    public $size;
    function __construct(){
        $this->identificador = $_POST["identificador"] ?? false;
        $this->modulo = $_POST["modulo"]  ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
        $this->autor = $_SESSION["id"] ?? false;
        $this->master = $_POST["master"] ?? false;
        $this->data = $_POST["data"] ?? false;
        $this->hash = $_POST["hash"] ?? false;
        $this->unicidade = $_POST["unicidade"] ?? false;
        
        $pasta = $this->master == "true" ? "master" : "conteudo";
        $this->diretorio = $this->modulo ? __DIR__."/../".$pasta."/modulos/".$this->modulo : false;
    }
    
    function pegaDadosModulo($modulo, $pasta, $nome){
            $diretorio = __DIR__."/../conteudo/modulos/".$modulo;
        if(!is_dir($diretorio."/admins")){
            return ["erro"=>true, "mensagem"=>"O diretório ADMINS não existe"];
        }
        
        if(!is_dir($diretorio."/admins/".$pasta)){
            return ["erro"=>true, "mensagem"=>"O diretório $pasta não existe"];
        }
        
        if(!file_exists($diretorio."/admins/".$pasta."/".$nome.".json")){
            return ["erro"=>true, "mensagem"=>"O arquivo $pasta / $nome não existe"];
        }
        
        try{
            $conteudo = json_decode(file_get_contents($diretorio."/admins/".$pasta."/".$nome.".json"), true);
            return ["sucesso"=>true, "conteudo"=> $conteudo];
        }catch(Exception $e){
             return ["erro"=>true, "mensagem"=>$e];
        }

    }
    
    function pegaDados($pasta, $nome){
        if(!is_dir($this->diretorio."/admins")){
            return ["erro"=>true, "mensagem"=>"O diretório ADMINS não existe"];
        }
        
        if(!is_dir($this->diretorio."/admins/".$pasta)){
            return ["erro"=>true, "mensagem"=>"O diretório $pasta não existe"];
        }
        
        if(!file_exists($this->diretorio."/admins/".$pasta."/".$nome.".json")){
            return ["erro"=>true, "mensagem"=>"O arquivo $pasta / $nome não existe"];
        }
        
        try{
            $conteudo = json_decode(file_get_contents($this->diretorio."/admins/".$pasta."/".$nome.".json"), true);
            return ["sucesso"=>true, "conteudo"=> $conteudo];
        }catch(Exception $e){
             return ["erro"=>true, "mensagem"=>$e];
        }

    }
    
    function conta($config){
    // Extrai os valores do array de configuração
    $modulo = $config["modulo"];
    $banco = $config["banco"];
    $tipo = $config["tipo"];
    $coluna = $config["coluna"];
    

    if (!$banco || !$coluna) {
        throw new Exception("Banco ou coluna não definidos corretamente.");
    }


    $query = "SELECT ".$coluna.", COUNT(*) as total FROM " . $banco . " GROUP BY " . $coluna;


    $resultado = $this->conn->query($query);


    $arrayResultado = [];


    if ($resultado) {
        while ($row = $resultado->fetch_assoc()) {

            $arrayResultado[$row[$coluna]] =  $row['total'];
        }
        
        return $arrayResultado; 
    } else {
        // Se houver um erro na consulta
        echo "Erro na consulta: " . $this->conn->errorInfo()[2];
        return [];
    }
}
    
    function instala($nomeTabela, $query){
        $nomeTabela = trim($nomeTabela);
    $conexao = $this->conn;
    $sql = "SHOW TABLES LIKE '$nomeTabela'";
    $resultado = $conexao->query($sql);
    
    if($resultado->num_rows == 0) {
    
        $conexao->query($query);
    } 
    
 
    }
    
    function setDb($arquivo){
        $arquivo = $arquivo.".json";
        $base = $this->db;
        $this->banco = trim($base["banco"]);
        $this->prefixo = trim($base["prefixo"]);
        $this->queryId = $this->db["estrutura"]["hash"] ? $this->prefixo."_hash" : $this->prefixo."_id";
        if($base["meta"]){
            $this->meta = new Meta($this->banco, $this->prefixo, $this->conn);
        }
        
      
        $consulta = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$this->banco."'";
        $resultado = $this->conn->query($consulta);
        if ($resultado && $resultado->num_rows == 0 && defined('BRAIN') && BRAIN === true) {
             $install = new Install($this->diretorio."/admins/configs/".$arquivo);
             $principal = $install->principal();
             $meta = $install->meta();
                      
            if($principal){
                    $this->instala($install->banco, $principal);
                }
                      
            if($meta){
                $this->instala($install->banco."_meta", $meta);
            }
            
            
            
        } 
    }
    
    function verificaTipo($input) {
    // Verifica se é numérico
    if (is_numeric($input)) {
        // Verifica se é um número inteiro
        if (ctype_digit($input)) {
            return $this->prefixo."_id";
        } else {
            return $this->prefixo."_hash";
        }
    } else {
        return $this->prefixo."_hash";
    }
}
    
    function listar(){
        
        
        $datas = [];
        $colunas = $this->infoTabelaBanco($this->banco);
        
        

        $queryPrincipal = [$this->prefixo."_id"];
        $metas = [];
        
        
        
        
        if($this->db["estrutura"]["hash"]){
            $hash = true;
            array_push($queryPrincipal, $this->prefixo."_hash");   
        }else{
            $hash = false;
        }
        
        
        $haverOrder = false;
        if(in_array($this->prefixo."_ordem", $colunas)) {
            array_push($queryPrincipal, $this->prefixo."_ordem"); 
            $haverOrder = true;
        }
        
        
        
        
        if($this->db["estrutura"]["url"]){
            $url = true;
            array_push($queryPrincipal, $this->prefixo."_url");   
        }else{
            $url = false;
        }
        
        $renders = [];
        $render = false;
        
        
        
        // Separamos o tipo de dado em Principal ou Meta
        foreach($this->tabela["colunas"] as $item){
            intval($item["tipo"]) == 1 ?  array_push($queryPrincipal, $this->prefixo."_".$item["id"]) : array_push($metas, $item);
        }
        
        
        
        
        $colunas = implode(",",  $queryPrincipal);
        $prefixId = $this->prefixo."_id";
        
        $regra = intval($this->tabela["regra"]);
        
        
        
        
       $search = "";
       $limite = "";
       
       /*
        $seleciona = "SELECT MIN(gov_compromisso_data_comeco) AS antiga, MAX(gov_compromisso_data_comeco) AS recente FROM gov_compromissos";
       $pega = $this->conn->query($seleciona);
       $dado = $pega->fetch_assoc();
       $datas = [
           "antiga"=>$dado["antiga"],
           "recente"=>$dado["recente"],
            ];
       
       
        */
        
       
       $limite = $_POST["size"] ?? 1000;
       $paginacao = isset($_POST["paginacao"]) && is_numeric($_POST["paginacao"]) && intval($_POST["paginacao"]) > 0  ? 'OFFSET ' . ((intval($_POST["paginacao"]) - 1) * $limite)  : '';
       $search = "";
        
        if(isset($_POST["pesquisa"])){
            
           $pesquisa = json_decode($_POST["pesquisa"], true);
              $wheres = [];
           $termo = $pesquisa["termo"] ?? false;
           if($termo){
            
               foreach($queryPrincipal as $q){
                   array_push($wheres, $q." LIKE '%$termo%'");
               }
               $search = "WHERE (".implode(" OR ", $wheres).")";
           }
           
           $comeco = $pesquisa["data"]["comeco"];
           $fim = $pesquisa["data"]["fim"];
           
           if($comeco != "all" && $fim != "all"){
               $start_date = $this->conn->real_escape_string($comeco);
               $end_date = $this->conn->real_escape_string($fim);
               if(empty($wheres)){
                   $search .= " WHERE gov_compromisso_data_comeco BETWEEN '$start_date' AND '$end_date'";
               }else{
                  $search .= " AND gov_compromisso_data_comeco BETWEEN '$start_date' AND '$end_date'";
               }
               
             
               
               



                   
               
            }
        }
        
        $tamanho = "SELECT count(*) as total FROM ".$this->banco. " ".$search."";


        $resultado = $this->conn->query($tamanho);
        $dado = $resultado->fetch_assoc();
        $this->size = intval($dado["total"]);

        if(!$this->unicidade){
            if($regra == 1){
           
           $limite = "";
            
      
           if($this->size > 1000 && ($this->tabela["acoes"]["opcoes"]["bigdata"] ?? false)){
               $limite = " LIMIT 1000 $paginacao";
           }
 
             $acao = "SELECT " . $colunas . " FROM " . $this->banco . " ".$search." ORDER BY " . $prefixId ." DESC ".$limite; 
  
            }else{
            $autortab =  $this->prefixo."_autor";
            $acao = "SELECT ".$colunas." FROM ".$this->banco." WHERE ".$autortab ."='".$this->autor."' ORDER BY ".$prefixId."  DESC";
            }
        }else{
            $inde = $this->verificaTipo($this->unicidade);

            $acao = "SELECT ".$colunas." FROM ".$this->banco." WHERE ".$inde ."='".$this->unicidade."'";

        }
        
        
        $estrangeiras = [];
        $resultado = $this->conn->query($acao);
        $lista = [];
        $cabecalho = [];
        

        $visiveis = [];
        $regras = [];
        $renderRules = [];
        if($resultado->num_rows > 0){
            $primeira = true;
            while($dado = $resultado->fetch_assoc()){
                $item = $dado;
                /*
                if($render && is_string($renderValue)){
                    try{
                        $render = json_decode($item[$this->prefixo."_render"], true);
                        unset($item[$this->prefixo."_render"]);
                        
                          foreach($renders as $chave=>$valor){
                        if(isset($item[$chave])){
                             $old = $item[$chave]; 
                            
                            
                            if(isset($render[$chave])){
                                $valor = $render[$chave][$valor] ?? $old;
                                $item[$chave] = $valor;
                            }else{
                        
                                switch($chave){
                                    case $this->prefixo."_categoria":
                                        $item[$chave] = $render["categoria"]["nome"] ?? $old;
                                        break;
                                    case $this->prefixo."_categoria":
                                        $item[$autor] = $render["autor"]["nome"] ?? $old;
                                        break;
                                }

                            }
                           
                            
                        }
                        
                    }
                    }catch(Exception $e){
                        
                    } 
                  
                    
                
                  
                    
              
            
                    
                    //print_r($render);
                    
                    
                    
                    //print_r($item);
                    //die();
                   // print_r($render);
                    
                }
              
                
                if($render){
    try{
        $renderValue = $item[$this->prefixo."_render"];
        if(is_string($renderValue)){
            $render = json_decode($renderValue, true);
            unset($item[$this->prefixo."_render"]);
            
            foreach($renders as $chave=>$valor){
                if(isset($item[$chave])){
                    $old = $item[$chave]; 
                    
                    if(isset($render[$chave])){
                        $valor = $render[$chave][$valor] ?? $old;
                        $item[$chave] = $valor;
                    }else{
                        switch($chave){
                            case $this->prefixo."_categoria":
                                $item[$chave] = $render["categoria"]["nome"] ?? $old;
                                break;
                            case $this->prefixo."_categoria":
                                $item[$autor] = $render["autor"]["nome"] ?? $old;
                                break;
                        }
                    }
                }
            }
        }
    }catch(Exception $e){
        
    }
}
                
                print_r($item);
                  */
       
         
                $id = $dado[$this->prefixo."_id"];
                if(count($metas) > 0){
                    foreach($metas as $meta){
                        if($this->meta ?? false){
                             $item[$meta["id"]] = $this->meta->item($meta["id"], $id);
                        }
                      
    
                    }
                }
                
                $final = [];
              
         
                
                $i = 0;
                
                foreach($this->tabela["colunas"] as $cols){
                    
                   if(isset($cols["estrangeira"]) && $cols["estrangeira"]){
                       $idExt = $cols["tipo"] == 1 ? $item[$this->prefixo."_".$cols["id"]] : $item[$cols["id"]];

                       $bk = $cols["confiEstrangeira"]["banco"] ?? false;
                       if($bk){
                           if(!isset($estrangeiras[$bk])){
                               $estrangeiras[$bk] = [];
                               $estrangeiras[$bk]["metas"] = [];
                               $estrangeiras[$bk]["principal"] = [];
                               $estrangeiras[$bk]["itens"] = [];
                               
             
                               
                               if(
                                   isset($cols["confiEstrangeira"]["banco"]) && $cols["confiEstrangeira"]["banco"] &&
                                   isset($cols["confiEstrangeira"]["tipo"]) && $cols["confiEstrangeira"]["tipo"] &&
                                   isset($cols["confiEstrangeira"]["coluna"]) && $cols["confiEstrangeira"]["coluna"]){ 
                                       switch(intval($cols["confiEstrangeira"]["tipo"])){
                                           case 1:
                                               array_push($estrangeiras[$bk]["principal"], $cols["confiEstrangeira"]["coluna"]);
                                               break;
                                           case 2:
                                               array_push($estrangeiras[$bk]["metas"], $cols["confiEstrangeira"]["coluna"]);
                                               break;
                                       }
                                   
                               }
                               
 
                               
                               
                              // array_push($estrangeiras[$bk]["configs"], $cols["confiEstrangeira"]);
                               
                           }
                           array_push($estrangeiras[$bk]["itens"], $idExt);
                           
                       }
                       
                   }
                    
                    if($primeira){
                        array_push($cabecalho, $cols["nome"]);
                        
                         $visivel = 1;
                    if(isset($cols["invisivel"]) && $cols["invisivel"] == 1){
                        $visivel = 0;
                    }
                        array_push($visiveis, $visivel);
                        
                    }
                    
                    
                    if($cols["tipo"] === "1"){
                        $final[$i] = $item[$this->prefixo."_".$cols["id"]] ?? false;
                    }else{
                        if($item[$cols["id"]]){
                             $final[$i] = $item[$cols["id"]];
                        }else{
                             $final[$i] = "";
                        }
                       
                    }
                    $i++;
                    
                }
                
                $primeira = false;
                $final["id"] = $dado[$prefixId];
                $final["extra"] = [
                    "s"=> $hash ? $dado[$this->prefixo."_hash"] : $dado[$this->prefixo."_id"],
                    "p"=> $url ? $dado[$this->prefixo."_url"] : false
                    ];
                    
                    
                if($haverOrder) {
                        $final["extra"]["o"] =  $dado[$this->prefixo."_ordem"]; 
                }
                
             
                
                array_push($lista, $final);
            }
            
            
            $acoes = $this->tabela["acoes"]["opcoes"];
            $rotas = $this->tabela["acoes"]["rotas"];
        
        $action = [];
        if($acoes["acoes"]){
            if($acoes["editar"]){
               array_push($action, ["tipo"=>"editar", "rota"=>$rotas["editar"]]);
            }
            
            if($acoes["apagar"]){
                array_push($action, ["tipo"=>"apagar"]);
            }
            
            if($acoes["vizualizar"]){
                array_push($action, ["tipo"=>"vizualizar", "rota"=>$rotas["vizualizar"]]);
            }
        }
        
        
        
        if(isset($this->tabela["acoesPersonalizadas"])){
            foreach($this->tabela["acoesPersonalizadas"] as $personalizado){
                array_push($action, ["tipo"=>"outros", "rota"=>$personalizado]);
            }
            
        }
       
        
        
        
        
       $render = [];
       $contabilizadas = [];
  
       $i = 0;

       foreach($this->tabela["colunas"] as $item){
           
           if(isset($item["estrangeira"]) && $item["estrangeira"]){
               switch($item["estrangeira"]){
                   case 1:
                       if($item["confiEstrangeira"]["banco"] && $item["confiEstrangeira"]["coluna"]){
                           $coluna = explode("_", $item["confiEstrangeira"]["coluna"]);
                           $coluna = $coluna[count($coluna) - 1];
                           array_push($renderRules, 
                           [
                               "b"=>$item["confiEstrangeira"]["banco"], 
                               "c"=>$coluna
                               ]);
                    }else{array_push($renderRules, false);}
                       
                       break;
                    case 2:
                        $contador = $this->conta($item["confiEstrangeira"]);
                        
               
                        $z = 0;
                        while($z < count($lista)){
                            $inde = $lista[$z][$i];
                     
                            if(isset($contador[$inde])){
                                $lista[$z][$i] = $contador[$inde];
                            }else{
                                $lista[$z][$i] = 0;
                            }
            
                            $z++;
                        }
                        
                     
                        break;
                    default:
                        array_push($renderRules, false);
                        
                        break;
               }
      
               
              

           }else{
               array_push($renderRules, false);
           }
           
           array_push($render, $item["render"]);
           
           if(intval($item["render"]) == 6 && isset($item["regra"])){
               $regras[$i] = $item["regra"];
           }
           
           
           $i++;
       }
       
       
       
        }
        else{
            foreach($this->tabela["colunas"] as $item){
                array_Push($cabecalho, $item["nome"]);
            }

            $action = [];
            $render = [];
        }
        
        $header = [
            "importador"=>$this->tabela["acoes"]["opcoes"]["importador"] ?? false,
            "exportador"=>$this->tabela["acoes"]["opcoes"]["exportador"] ?? false,
            "filtro"=>$this->tabela["acoes"]["opcoes"]["filtro"] ?? false,
            "colunas"=>$this->tabela["acoes"]["opcoes"]["colunas"] ?? false,
            "multiplo"=>$this->tabela["acoes"]["opcoes"]["multiplo"] ?? false,
            "ordenador"=>$this->tabela["acoes"]["opcoes"]["ordenador"] ?? false,
            ];
        
        if(isset($this->tabela["layout"]) && isset($this->tabela["layout"]["modo"]) && $this->tabela["layout"]["modo"] == 2){
            return ["sucesso"=>true, "lista"=>$lista, "cabecalho"=>$cabecalho, "acoes"=>$action, "render"=>$render, "regras"=>$regras, "header"=>$header, "visibilidade"=>$visiveis, "layout"=>2,  "html"=>$this->minifyHTML($this->tabela["layout"]["html"])];

        }
        
        foreach ($estrangeiras as $chave => $array) {

            $estrangeiras[$chave]["itens"] = array_values(array_unique($array["itens"]));
            $estrangeiras[$chave]["principal"] = array_values(array_unique($array["principal"]));
            $estrangeiras[$chave]["metas"] = array_values(array_unique($array["metas"]));
        }
        
        
        
        $estrangeiros = $this->pegaEstrangeiras($estrangeiras);
        
        
        return [
            "sucesso"=>true, 
            "lista"=>$lista, 
            "cabecalho"=>$cabecalho,
            "acoes"=>$action,
            "render"=>$render, 
            "regras"=>$regras,
            "header"=>$header, 
            "visibilidade"=>$visiveis,
            "layout"=>1,
            "estrangeiros"=>$estrangeiros, 
            "renderRules"=>$renderRules,
            "ordenado"=>$haverOrder,
            "tamanhoTotal"=>$this->size,
            "datas"=>$datas
            ];
    }
    
    function listarOrdenador(){
        $queryPrincipal = [$this->prefixo."_id"." as id"];
        
        $colunas = $this->infoTabelaBanco($this->banco);
        

        if(!in_array($this->prefixo."_ordem", $colunas)) {
            return ["erro"=>true, "mensagem"=>"O item não pode ser reordenado"];
        }
        
        array_push($queryPrincipal, $this->prefixo."_ordem"." as o");
        
        if(in_array($this->prefixo."_titulo" , $colunas)){
            array_push($queryPrincipal, $this->prefixo."_titulo"." as t");
        }
        
        if(in_array($this->prefixo."_nome" , $colunas)){
            array_push($queryPrincipal, $this->prefixo."_nome"." as t");
        }
        
        if(in_array($this->prefixo."_imagem" , $colunas)){
            array_push($queryPrincipal, $this->prefixo."_imagem"." as i");
        }
        
        if(in_array($this->prefixo."_categoria" , $colunas)){
            array_push($queryPrincipal, $this->prefixo."_categoria"." as c");
            $categorias = true;
        }else{
            $categorias = false;
        }
        
        
        $colunas = implode(",", $queryPrincipal);

 
        $lista = [];
        $listaCats = [];
        $ordenador =  $this->prefixo."_ordem";
        $acao = "SELECT ".$colunas." FROM ".$this->banco." ORDER BY $ordenador DESC";
        $resultado = $this->conn->query($acao);
        if($resultado->num_rows > 0){
            $index = 0;
            while($dado = $resultado->fetch_assoc()){
                $index++;
                
                
                if($dado["o"] == null){
                    $identificador = $this->prefixo."_id";
                    $id = $dado["id"];
                    $banco = $this->banco;
                    $ordenador = $this->prefixo."_ordem";
                    $update = "UPDATE $banco SET $ordenador='$index' WHERE $identificador='$id'";
                    $this->conn->query($update);
                    $dado["o"] = $index;

                }
                
                if($categorias && $dado["c"]){
                    if(!in_array($dado["c"], $listaCats)){
                        array_push($listaCats, $dado["c"]);
                    }
                }
                
                array_push($lista, $dado);
                
            }
        }
        
        $render = [];
        if(!empty($listaCats)){
            $itens = implode(",", $listaCats);
            $queryPrincipal = "SELECT categoria_nome, categoria_id  FROM categorias WHERE categoria_id IN ($itens)";
            $resultado = $this->conn->query($queryPrincipal);
            if($resultado->num_rows > 0){
                while($dado = $resultado->fetch_assoc()){
                    $render[$dado["categoria_id"]] = $dado["categoria_nome"];
                }
            }
        }    
    
        return ["sucesso"=>true, "lista"=>$lista, "categorias"=>$render];

    }
    
    function salvaOrdem(){
        $lista = $_POST["ordem"] ?? false;
        if(!$lista){
            return ["erro"=>true, "mensagem"=>"Não foram passados itens a serem ordenados"];
        }
        
         $colunas = $this->infoTabelaBanco($this->banco);
        
        if(!in_array($this->prefixo."_ordem", $colunas)) {
            return ["erro"=>true, "mensagem"=>"O item não pode ser reordenado"];
        }
        
        $lista = json_decode($lista);
        $i = 1;
        foreach($lista as $id){
            $identificador = $this->prefixo."_id";

            $banco = $this->banco;
            $ordenador = $this->prefixo."_ordem";
            $atualiza = "UPDATE $banco SET $ordenador='$i' WHERE $identificador='$id'";
            $this->conn->query($atualiza);
            
            $i++;
        }
        return ["sucesso"=>true, "mensagem"=>"Os itens foram reordenados com sucesso"];
    }
    
    function infoTabelaBanco($tabela) {
    // Escapa o nome da tabela para evitar SQL Injection
    $tabela = $this->conn->real_escape_string($tabela);

    // Verifica se a tabela existe no banco de dados
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
    
    function pegaEstrangeiras($estrangeiras) {
    $dados = [];
    
 
    if (empty($estrangeiras)) {
        return $dados;
    }

    foreach ($estrangeiras as $banco => $estrangeira) {
        if(!isset($dados[$banco])){
            $dados[$banco] = [];
            
            
            
        }

        if (!empty($estrangeira["itens"])) {
            foreach($estrangeira["itens"] as $item){
                if(intval($item)){
                    $dados[$banco][$item] = [];
                }
            }
            
            
            $listinha  = array_filter($estrangeira["itens"]); 
            $itens =   implode(",", $listinha);
            $bancoMeta = $banco . "_meta";
            $primeiraLetra = substr($banco, 0, 1);
           

            if (!empty($estrangeira["metas"])) {
                
         
                $estruturaMeta = $this->infoTabelaBanco($bancoMeta);
                
                if (!empty($estruturaMeta)) {
                     $metas = implode(",", $estrangeira["metas"]);
                      $identificadorMeta = $estruturaMeta[1];
                      $metaChave = $estruturaMeta[2];
                      $metaValor = $estruturaMeta[3];
           
                     
                     $query = "SELECT `$metaValor` FROM `$bancoMeta` WHERE `$metaChave` IN ($metas) AND $identificadorMeta IN ($itens)";
                     $resultado = $this->conn->query($query);
                     if($resultado->num_rows > 0){
                         while($dado = $resultado->fetch_assoc()){
                             $dados[$banco][$dado[$identificadorMeta]][$dado[$metaChave]] = $dado[$metaValor];
                         }
                     }

                }

            }

            // Verifica se há uma chave estrangeira principal
            if (!empty($estrangeira["principal"])) {
                
                $estruturaPrincipal = $this->infoTabelaBanco($banco);
                if (!empty($estruturaPrincipal)) {
                    
                    $principais = [];
                    
                    foreach($estrangeira["principal"] as $p){
                        if(in_array($p, $estruturaPrincipal)){
                            array_push($principais , $p);
                        }
                    }
                    
               
                    
                    if(!empty($principais)){
               
                            $prefixo = explode("_", $principais[0]);
                            array_pop($prefixo);
                            $prefixo = implode("_", $prefixo);
                            
                            $identificador = $prefixo."_id";
                            array_push($principais, $identificador);
                            $chavePrincipal = implode(",", $principais);
                    
                    if($itens){
                        $queryPrincipal = "SELECT $chavePrincipal FROM `$banco` WHERE `$identificador` IN ($itens)";
                    
                  
                    $resultado = $this->conn->query($queryPrincipal);
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            foreach($dado as $chave=>$info){
                                if($chave != $identificador){
                                    $trato = explode("_", $chave);
            
                                    $trato = $trato[count($trato) - 1];
                                    $dados[$banco][$dado[$identificador]][$trato] = $info;
                                }
                            }
                        }
                    }
                    }
                    
                    }
                }
            }
        }
    }
    
    return $dados;
}
    
    function minifyHTML($html) {
    // Remove comentários HTML
    $search = [
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'  // Remove HTML comments, tabs, and new lines
    ];
    
    // Remoção de espaços em branco, quebras de linha e tabs
    $replace = [
        ''  // Replace them with an empty string
    ];
    
    // Minificação do HTML
    $html = preg_replace($search, $replace, $html);
    
    // Remoção de espaços em branco entre tags
    $search = [
        '/>\s+</'  // Remove white space between tags
    ];
    $replace = [
        '><'  // Replace with no space
    ];
    
    // Aplicando a segunda substituição para minificar mais
    $html = preg_replace($search, $replace, $html);
    
    return $html;
}
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function disposicao(){
        $colunasDisponiveis = [];
        $query = "SHOW COLUMNS FROM ".$this->banco."";
        $colunas = $this->conn->query($query);
       
       while ($info = $colunas->fetch_assoc()) {
           $colunasDisponiveis[$info["Field"]] = $info;
       }
        
        $this->disponiveis = $colunasDisponiveis;
    }
    
    function pegaDp($chave){
        $chave = $this->prefixo."_".$chave;
        if(isset($this->disponiveis[$chave])){
             return $this->disponiveis[$chave];
        }else{
            return false;
        }
    }
    
    function autorRender($id){
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        
        $foto = "[]";
        $capa = "[]";
        try{
            if($dado["usuario_foto"] != null){
                $foto = json_decode($dado["usuario_foto"], true); 
            }
            if($dado["usuario_capa"] != null){
                $capa = json_decode($dado["usuario_capa"], true);
            }
           
            
        }catch(Exception $e){
        
        }
        
        
        return [
            "nome"=>$dado["usuario_display"],
            "user"=>$dado["usuario_user"],
            "foto"=>$foto,
            "capa"=>$capa
            ];
    }
    
    function categoriaRender($id){
        $seleciona = "SELECT * FROM categorias WHERE categoria_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return [
            "nome"=>$dado["categoria_nome"],
            "autor"=>$dado["categoria_autor"],
            "isCat"=>$dado["categoria_iscat"]
            ];
    }
    
    function tagRender($array){
    $categorias = [];
    foreach ($array as $id) {
        $seleciona = "SELECT * FROM categorias WHERE categoria_id = ?";
        if ($stmt = $this->conn->prepare($seleciona)) {
            $stmt->bind_param("i", $id); // Assumindo que categoria_id é um inteiro
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                while ($dado = $resultado->fetch_assoc()) {
                    $categorias[$id] = [
                        "nome" => $dado["categoria_nome"],
                        "autor" => $dado["categoria_autor"],
                        "isCat" => $dado["categoria_iscat"]
                    ];
                }
            }
            $stmt->close();
        }
    }
    return $categorias;
}
    
    function preProcessa($item, $dado){
    try{
        $dado = intval($dado);
        if(!$dado){
            return;
        }
    }catch(Exception $e){
        return false;
    }
    
    $render = $item["render"] ?? false;
    if(!$render){
        return false;
    }
    
    $modulo = $render["modulo"] ?? false;
    $banco = $render["banco"] ?? false;
    $colunas = $render["colunas"] ?? false;
    
    if(!$modulo || !$banco || !$colunas || count($colunas) == 0){
        return false;
    }
    
    $infos = $this->pegaDadosModulo($modulo, "configs", $banco); 
    if(!isset($infos["sucesso"])){
        return false;
    }
    
    $conteudo = $infos["conteudo"];
    $bancoDados = $conteudo["banco"];
    $prefixo = $conteudo["prefixo"];
    
    $principais = [$prefixo."_id"];
    $metas = [];
    
    foreach($colunas as $coluna){
        if(count(explode("_", $coluna)) == 2){
            array_push($principais, $coluna);
        }else{
            array_push($metas, $coluna);
        }
    }
    
    $seleciona = "SELECT * FROM ".$bancoDados." WHERE ".$prefixo."_id ='$dado'";
    $resultado = $this->conn->query($seleciona);
    if($resultado->num_rows == 0){
        return false;
    }
    
    $dado = $resultado->fetch_assoc();
    $resposta = [];
    foreach($principais as $item){
        $resposta[$item] = $dado[$item];
    }
    
    return $resposta;
}

    function permisaoUser($crud, $modulo, $form){
 
        $funcao = INTVAL($_SESSION["funcao"]);
        if($funcao === 0 || $funcao === 1){
            return true;
        }
        
        
        switch($crud){
            case 'c':
                $seleciona = "SELECT * FROM  funcoes_crud WHERE funcao_crud_funcao='$funcao' AND funcao_crud_modulo='$modulo' AND funcao_crud_formulario='$form' AND funcao_crud_criar='1'";
                break;
            case 'u':
                 $seleciona = "SELECT * FROM  funcoes_crud WHERE funcao_crud_funcao='$funcao' AND funcao_crud_modulo='$modulo' AND funcao_crud_formulario='$form' AND funcao_crud_atualizar='1'";
                break;
            case 'd':
                 $seleciona = "SELECT * FROM  funcoes_crud WHERE funcao_crud_funcao='$funcao' AND funcao_crud_modulo='$modulo' AND funcao_crud_formulario='$form' AND funcao_crud_deletar='1'";
                break;
            case 't':
                 $seleciona = "SELECT * FROM  funcoes_crud WHERE funcao_crud_funcao='$funcao' AND funcao_crud_modulo='$modulo' AND funcao_crud_formulario='$form' AND funcao_crud_terceiros='1'";
                break;
        }
        
   
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        
        return true;


    }
    
    function tabelaSize() {
    $sql = "SELECT COUNT(*) AS total FROM " . $this->banco;
    $resultado = $this->conn->query($sql);
    
    if ($resultado) {
        $dado = $resultado->fetch_assoc();
        return intval($dado["total"]) + 1;
    } else {
        return 1;
    }
}

    function salva($edit){

        if(isset($this->db["estrutura"]["pontos"]) && $this->db["estrutura"]["pontos"] && !$edit){
            $pontos = $this->pontuacao();
            if(!$pontos){
                return ["erro"=>true, "mensagem"=>"O usuário não tem pontos suficientes",  "pontos"=>true];
            }
        }
        
        if(!$this->permisaoUser($edit ? "u" : "c" , $this->modulo, $this->identificador)){
            return ["erro"=>true, "mensagem"=>"A funçaõ do usuário não tem permissão para essa ação",  "pontos"=>true, "crud"=>true];
        }
        
  
        
        
        
        $url = false;
        $preProcessado = [];
        
        if(!$this->data){
            return ["erro"=>true, "mensagem"=>"Não foi enviada nenhuma informação para ser salva"];
        }
        
        $this->data = json_decode($this->data, true);
        
        
        
        
        $this->disposicao();
        
        $queryPrincipal = [];
        $queryDados = [];
        
        foreach($this->db["colunas"] as $item){
         

            $input = $item["input"];
            $nome = $this->prefixo."_".$item["nome"];

            $unico = (isset($item["unico"]) && $item["unico"] == 1) ? true : false;
            if(isset($this->data[$input])){
  
                $dado = "'".addslashes($this->data[$input])."'";
                
                if ($unico) {
    // Prepare a consulta SQL usando prepared statements para evitar injeção de SQL
    if ($edit) {
        $unicidade = "SELECT COUNT(*) as total FROM ".$this->banco." WHERE $nome = ? AND ".$this->queryId." != ?";
    } else {
        $unicidade = "SELECT COUNT(*) as total FROM ".$this->banco." WHERE $nome = ?";
    }
    $stmt = $this->conn->prepare($unicidade);
    if ($edit) {
        $stmt->bind_param("ss", $this->data[$input], $this->hash);
    } else {
        $stmt->bind_param("s", $this->data[$input]);
    }

    // Execute a consulta preparada
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($total);
    $stmt->fetch();


    if ($total > 0) {
        return ["erro" => true, "mensagem" => "Valor já adicionado", "tipo"=>"duplicado", "chave"=>$item["input"]];
    }

    // Feche o statement e libere os recursos
    $stmt->close();
}
                
                
                
                
                array_push($queryPrincipal, $nome);
                array_push($queryDados, $dado);
                
                if($item["tipo"] == "estrangeiro"){
                    $proc = $this->preProcessa($item, $this->data[$input]);
                    if($proc){
                      $preProcessado[$nome] = $proc;
                    }
                    
                }
                
                
                
            }
           
        }
        
       
        if($this->db["estrutura"]["autor"] && !$edit){
            array_push($queryPrincipal, $this->prefixo."_autor");
            array_push($queryDados, "'".$_SESSION["id"]."'");
            $preProcessado["autor"] = $this->autorRender($_SESSION["id"]);
        }
        
        
        if(isset($this->db["estrutura"]["ordenador"]) && $this->db["estrutura"]["ordenador"] && !$edit){
            array_push($queryPrincipal, $this->prefixo."_ordem");
            
            
            
            array_push($queryDados,  $this->tabelaSize());
        }
        
        
        
        if($this->db["estrutura"]["hash"] && !$edit){
            $hashId = $this->hasher();
            array_push($queryPrincipal, $this->prefixo."_hash");
            array_push($queryDados, "'".$hashId."'");
        }else{
            $hashId = false;
        }
        
        
        $especiais = $this->data["especiais"] ?? false;
        
        if($this->db["estrutura"]["categoria"]){
            if(isset($especiais["categoria"]) && $especiais["categoria"] && $especiais["categoria"] > 0){
                 array_push($queryPrincipal, $this->prefixo."_categoria");
                 array_push($queryDados, "'".$especiais["categoria"]."'");
                 $preProcessado["categoria"] = $this->categoriaRender($especiais["categoria"]);
            }
        }
        
         if($this->db["estrutura"]["tags"]){
            if(isset($especiais["tags"]) && $especiais["tags"] && $especiais["tags"] > 0){
                 array_push($queryPrincipal, $this->prefixo."_tags");
                 
                 $finalTags = [];
                 foreach($especiais["tags"] as $tag){
                     array_push($finalTags, intval($tag));
                 }
                 
                 $imagem =  json_encode($finalTags , JSON_UNESCAPED_UNICODE);
                 
                 array_push($queryDados, "'".$imagem."'");
                 $preProcessado["tags"] = $this->tagRender($especiais["tags"]);
            }
        }
        
        if(isset($this->db["estrutura"]["destaque"]) && $this->db["estrutura"]["destaque"]){
            array_push($queryPrincipal, $this->prefixo."_destaque");
            $destaque = $especiais["destaque"] ?? false;
            array_push($queryDados, "'".$destaque."'");
        }
        
        
        
        if(isset($this->db["estrutura"]["wildcard"]) && $this->db["estrutura"]["wildcard"]){
            array_push($queryPrincipal, $this->prefixo."_wildcard");
            $wildcard = $especiais["wildcard"] ?? 0;
            array_push($queryDados, "'".$wildcard."'");
        }
        

        if(isset($this->db["estrutura"]["enderecos"]) && $this->db["estrutura"]["enderecos"]){

            array_push($queryPrincipal, $this->prefixo."_endereco");
            $endereco = intval($especiais["endereco"]);
            if(!$endereco){
                array_push($queryDados, "NULL");
            }else{
                array_push($queryDados, "'".$endereco."'");
            }
            
        }
        
        if(isset($this->db["estrutura"]["subconta"]) && $this->db["estrutura"]["subconta"]){

            array_push($queryPrincipal, $this->prefixo."_subconta");
            $subconta = intval($especiais["subconta"]);
            if(!$subconta){
                array_push($queryDados, "NULL");
            }else{
                array_push($queryDados, "'".$subconta."'");
            }
            
        }
        
      
        
        
       
        
        
        $status = 1;
        $visibilidade = 0;
        $agendamento = 0;
        if(isset($this->data["systemRules"])){
    
            if($this->data["systemRules"]["status"] == 0){
                $status = 0;
            }
            
            if($this->data["systemRules"]["visibilidade"] > 0){
                $visibilidade = json_encode($this->data["systemRules"]["visibilidade"]);
            }
            
            if($this->data["systemRules"]["agendamento"] != 0){
                $agendamento = $this->data["systemRules"]["agendamento"];
               
                
                if($this->db["estrutura"]["dataCriacao"]){
                    array_push($queryPrincipal, $this->prefixo."_data");
                    array_push($queryDados, "'".$agendamento."'");
                }

            }
        }
        
        if($this->pegaDp("status")){
            array_push($queryPrincipal, $this->prefixo."_status");
            array_push($queryDados, "'".$status."'");
        }
        
        if($this->pegaDp("visibilidade")){
             array_push($queryPrincipal, $this->prefixo."_visibilidade");
             array_push($queryDados, "'".$visibilidade."'");
        }
        
         if($this->pegaDp("agendamento")){
            array_push($queryPrincipal, $this->prefixo."_agendamento");
            array_push($queryDados, "'".$agendamento."'");
        }
        
        
        

        $imagens = [
            "galeria"=>"galeria",
            "imagemDestaque"=>"imagem",
            "seo"=>"seo",
    
            ];
        

       foreach($imagens as $c=>$v){
           if($this->pegaDp($v) && isset($this->db["estrutura"][$c]) && $this->db["estrutura"][$c] && isset($especiais[$c])){
               array_push($queryPrincipal, $this->prefixo."_".$v);
               $imagem =  json_encode($especiais[$c], JSON_UNESCAPED_UNICODE);
               array_push($queryDados, "'".$imagem."'");
           }
       }
       
        if(count($queryPrincipal) > 0 && count($queryDados) > 0){
            $colunas = implode(",", $queryPrincipal);
            $dados = implode(",", $queryDados);
            if(!$edit){
                $this->mode = "novo";
                $acao = "INSERT INTO ".$this->banco." (".$colunas.") VALUES (".$dados.")";
            }else{
                $this->mode = "edit";
                $queryCriator = [];
                $i = 0;
                foreach($queryPrincipal as $item){
                    array_push($queryCriator, "$item=$queryDados[$i]");
                    $i++;
                }
                $query = implode(",", $queryCriator);
                $acao = "UPDATE ".$this->banco." SET ".$query." WHERE ".$this->queryId."='".$this->hash."'";
            }
            
     
            if($this->conn->query($acao)){
                if(!$edit){
                     $id = $this->conn->insert_id;
                     
                     $this->atributos($id);
                     
                     $this->novoId = $id;
                     
                     $updates = [];

                     if($this->pegaDp("vendavel")){
                         
                        $referencia = $this->modulo."/".$this->identificador;
     
            
               
                        $venda = new Vendavel($this->banco, $id, $referencia);
                        $idVenda = $venda->novo($this->data["vendavel"]);

                        
                        array_push($updates, $this->prefixo."_vendavel = '".$idVenda."'");

                    }
                     
                     
                     
        
                     if($this->pegaDp("url")){
                         switch($this->db["url"]){
                             case 1:
                                 $url = $this->hasher(10);
                                 break;
                             case 2:
                                 $url = $id;
                                 break;
                             default:
                                 $url = $this->geraUrl($this->db["url"], $id);
                                 break;
                         }
                         array_push($updates , $this->prefixo."_url = '".$url."'");
                     }
                     
                     if(count($updates) > 0){
                         $updates = implode(",", $updates);
                         $atualiza = "UPDATE ".$this->banco." SET ".$updates." WHERE ".$this->prefixo."_id='$id'";
                         $this->conn->query($atualiza);

                     }
               
                     
                }else{
                    $pega = "SELECT ".$this->prefixo."_id FROM ".$this->banco." WHERE ".$this->queryId."='".$this->hash."'";
                    $resultado = $this->conn->query($pega);
                    $dado = $resultado->fetch_assoc();
                    
                    if(isset($dado[$this->prefixo."_id"])){
                          $id = $dado[$this->prefixo."_id"];
                          $this->novoId = $id;
                          
                          $this->atributos($id);
                    
                    if($this->pegaDp("vendavel")){
                        $referencia = $this->modulo."/".$this->identificador;
                        $venda = new Vendavel($this->banco, $id, $referencia);
                        if(isset($this->data["vendavel"])){
                             $venda->atualiza($this->data["vendavel"]);
                        }
                       
                    }
                    }
                  

                }
               

                if($this->db["meta"] && count($this->db["metas"]) > 0){
                   foreach($this->db["metas"] as $chave=>$valor){
                       if(isset($this->data[$chave])){
                           $this->meta->adicionar($id, $valor, $this->data[$chave]);
                       }
                       
                   }
                   
                }
                

                $hashId = $hashId ?? $id;
                

               // $log = new Log($this->novoId, $this->banco, !$edit ? 1 : 2);
               // $log->sucesso();
                return ["sucesso"=>true, "mensagem"=>"Informação salva com sucesso",  "id"=>$hashId, "url"=>$url];
            }else{
                return ["erro"=>true, "mensagem"=>"Erro ao salvar informações", "sql"=>$this->conn->error];
            }
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi encontrado dados válidos para serem salvos"];
        }
        
        
    
    }
    
    function atributos($item) {
        if(!isset($this->db["estrutura"]["atributos"]) || !$this->db["estrutura"]["atributos"]){
            return;
        }
        
    if (empty($this->db["estrutura"]["atributos"]) || empty($this->data["atributos"])) {
        $atributos = [];
    }else{
        $atributos = $this->data["atributos"];
    }

    
    $banco = $this->banco;
    $ids = [];
    
    // Consulta IDs do grupo de atributos
    $seleciona = "SELECT atributo_grupo_id AS id FROM atributos_grupos WHERE atributo_grupo_banco = '$banco'";
    $resultado = $this->conn->query($seleciona);
    if ($resultado && $resultado->num_rows > 0) {
        $ids = array_column($resultado->fetch_all(MYSQLI_ASSOC), 'id');
    }

    if (empty($ids)) return;
    
    // Consulta itens de atributos usando os IDs do grupo
    $idList = implode(",", $ids);
    $lista = "SELECT atributo_item_grupo AS grupo, atributo_item_atributo AS atributo, atributo_item_id AS id 
              FROM atributos_itens 
              WHERE atributo_item_identificador = '$item' 
                AND atributo_item_grupo IN ($idList)";
    $resultado = $this->conn->query($lista);
    $mapa = [];
    if ($resultado && $resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $mapa[$dado["grupo"]][$dado["atributo"]] = $dado["id"];
        }
    }

    $cadastros = [];
    $deletados = [];

    foreach ($atributos as $grupo => $grupoAtributos) {
        foreach ($grupoAtributos as $atributo) {
            if (isset($mapa[$grupo][$atributo])) {
                // Remove o atributo existente do mapa
                unset($mapa[$grupo][$atributo]);
            } else {
                // Adiciona novo atributo ao array de cadastros
                $cadastros[] = "('$item', '$grupo', '$atributo', '{$_SESSION["id"]}')";
            }
        }
    }

    // Adiciona itens remanescentes do mapa ao array de deletados
    foreach ($mapa as $grupo => $atributos) {
        $deletados = array_merge($deletados, array_values($atributos));
    }

    // Realiza inserção de novos cadastros
    if (!empty($cadastros)) {
        $cadastra = "INSERT INTO atributos_itens 
                     (atributo_item_identificador, atributo_item_grupo, atributo_item_atributo, atributo_item_autor) 
                     VALUES " . implode(", ", $cadastros);
        $this->conn->query($cadastra);
    }

    // Remove itens antigos não mais necessários
    if (!empty($deletados)) {
        $deleta = "DELETE FROM atributos_itens WHERE atributo_item_id IN (" . implode(",", $deletados) . ")";
        $this->conn->query($deleta);
    }
}

    function pegaInfos(){
        $seleciona = "SELECT * FROM ".$this->banco." WHERE ".$this->queryId ."='".$this->hash."'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        
        $this->disposicao();
        
        $dado = $resultado->fetch_assoc();
        
        $resultados = [];
        
        
        foreach($this->db["colunas"] as $col){
            $resultados[$col["input"]] = $dado[$this->prefixo."_".$col["nome"]] ?? false;
        }
        
        
        if($this->db["estrutura"]["meta"] && $this->db["metas"] && count($this->db["metas"]) > 0){
            $metas = $this->meta->listar($dado[$this->prefixo."_id"]);

            foreach($this->db["metas"] as $chave=>$valor){
  
                if(isset($metas[$valor])){
                    $resultados[$chave] = $metas[$valor];
                }
              
            }

        }
        

        $estrutura = $this->db["estrutura"];
        $especiais = [];
        
        
        
        
        if($estrutura["url"]){
            $especiais["url"] = $dado[$this->prefixo."_url"];
        }
        
        if($this->pegaDp("vendavel")){
            $venda = new Vendavel(false, false);
            $especiais["vendavel"] = $venda->info($dado[$this->prefixo."_vendavel"]);
        }
        
        if($this->pegaDp("imagem") && $estrutura["imagemDestaque"]){
            $especiais["imagem"] = $dado[$this->prefixo."_imagem"];
        }
        
        if($this->pegaDp("galeria") && $estrutura["galeria"]){
             $especiais["galeria"] = $dado[$this->prefixo."_galeria"];
        }
        
        if($this->pegaDp("categoria") && $estrutura["categoria"]){
            $especiais["categoria"] = $dado[$this->prefixo."_categoria"];
        }
        
        if($this->pegaDp("tags") && $estrutura["tags"]){
            $especiais["tags"] = $dado[$this->prefixo."_tags"];
        }
        
        if($this->pegaDp("seo") && $estrutura["seo"]){
            $especiais["seo"] = $dado[$this->prefixo."_seo"];
        }
        
        if($this->pegaDp("wildcard") && $estrutura["wildcard"]){
            $especiais["wildcard"] = $dado[$this->prefixo."_wildcard"];
        }
        

 
        if($this->pegaDp("endereco") && !empty($estrutura["enderecos"])){
            $especiais["endereco"] = $dado[$this->prefixo."_endereco"];
        }
        
        if($this->pegaDp("subconta") && !empty($estrutura["subconta"])){
            $especiais["subconta"] = $dado[$this->prefixo."_subconta"];
        }
        
        
        if($this->pegaDp("destaque") && $estrutura["destaque"]){
            $especiais["destaque"] = $dado[$this->prefixo."_destaque"];
        }
        
        if(isset($estrutura["dataCriacao"]) && isset($dado[$this->prefixo."_data"])){
            $especiais["data"] = $dado[$this->prefixo."_data"];
        }
        
        
        

         $status = $this->pegaDp("status") ? $dado[$this->prefixo."_status"] : false;
         $visibilidade = $this->pegaDp("visibilidade") ? $dado[$this->prefixo."_visibilidade"] : false;
         $agendamento = $this->pegaDp("agendamento") ? $dado[$this->prefixo."_agendamento"] : false;
        
        

        
      
        $resultados["systemRules"] = [
            "status"=> $status,
            "visibilidade"=> json_decode($visibilidade, true),
            "agendamento"=> $agendamento
        ];
        
        
        $resultados["especiais"] = $especiais;
 
        return $resultados;
        
    }
    
    function apagar(){
        if(!$this->hash){
            return ["erro"=>true, "mensgem"=>"Não foi enviado um ID válido para ser apagado"];
        }
        

        
         if(!$this->permisaoUser("d" , $this->modulo, $this->tabela["banco"])){
            return ["erro"=>true, "mensagem"=>"A funçaõ do usuário não tem permissão para essa ação",  "pontos"=>true, "crud"=>true];
        }
        
        

        
        $id = $this->prefixo."_id";
        $aut = $this->prefixo."_autor";
        $me = $_SESSION["id"];
        
        if(!$this->permisaoUser("t" , $this->modulo, $this->tabela["banco"])){ 
            $seleciona = "SELECT ".$id." FROM ".$this->banco." WHERE ".$this->queryId ."='".$this->hash."' AND ".$aut."='$me'";
        }else{
            $seleciona = "SELECT ".$id." FROM ".$this->banco." WHERE ".$this->queryId ."='".$this->hash."'";
        }
       

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um elemento válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        $identificador = $dado[$id];
        if($this->meta){
            $this->meta->apagartudo($dado[$id]);
        }
        
        
        try{
            $deleta = "DELETE FROM ".$this->banco." WHERE ".$this->queryId ."='".$this->hash."'";
            $this->conn->query($deleta);
            $log = new Log($identificador , $this->banco, 3);
            $log->sucesso();
            return ["sucesso"=>true, "mensagem"=>"Item apagado com sucesso"];
        }catch(Exception $e){
            return ["erro"=>true, "mensagem"=>["Não foi possível apagar o usuário definido"]];
        }
        
        
        
        
    }
    
    function url(){
        if(!isset($_POST["url"]) || !$_POST["url"]){
            return ["erro"=>true, "mensagem"=>"Envie uma url válida"];
        }
        
        $url = $this->stringParaURL($_POST["url"]);
        $prefixo = $this->prefixo."_url";
        $seleciona = "SELECT ".$prefixo." FROM ".$this->banco." WHERE ".$prefixo."='".$url."'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $atualiza = "UPDATE ".$this->banco." SET ".$prefixo."='".$url."' WHERE ".$this->queryId ."='".$this->hash."'";
            if($this->conn->query($atualiza) == true){
                return ["sucesso"=>true, "mensagem"=>"URL atualizada com sucesso"];
            }else{
                return ["erro"=>true, "mensagem"=>"Não foi possível atualizar a URL"];
            }
        }else{
            return ["erro"=>true, "mensagem"=>"Existe outro post com essa URL"];
        }
        
    
    }
    
    function stringParaURL($string) {


    $caracteres_especiais = array(
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n',
        'ß' => 'ss',
    );
    
       
    $url = strtolower(trim($string));
    $url = str_replace(' ', '-', $url);

    $url = strtr($url, $caracteres_especiais);


    $url = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $url);

 
    $url = preg_replace('/\s+/', '-', $url);

    $url = preg_replace('/-+/', '-', $url);
    $url = rtrim($url, '-');

    return paraURL($url);
} 

    function geraUrl($base, $id){
        $seleciona = "SELECT ".$this->prefixo."_".$base." FROM ".$this->banco." WHERE ".$this->prefixo."_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $nome = $dado[$this->prefixo."_".$base];
            if(!$nome){
                $nome = $this->hasher(10);
            }
            
            $original = $this->stringParaURL($nome);
            $url = $original;
            $i = 0;
            while(true){
                if($i == 0){
                    $url = $original;
                }else{
                    $url = $original."-".$i;
                }
                
                $seleciona = "SELECT ".$this->prefixo."_url FROM ".$this->banco." WHERE  ".$this->prefixo."_url='$url'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    break;
                }
                $i++;
            }
            return $url;
            
            
            
        }
    }
    
    function buscarInputPorHash($hash, $inputs) {
    foreach ($inputs as $input) {
        if ($input["hash"] === $hash) {
            return $input; // Encontrou o input direto
        }
        
        // Verifica se o input está dentro dos filhos de algum componente
        if (!empty($input["filhos"])) {
            $inputEncontrado = $this->buscarInputPorHash($hash, $input["filhos"]);
            if ($inputEncontrado !== null) {
                return $inputEncontrado; // Retorna o input encontrado nos filhos
            }
        }
    }
    
    return null; // Se não encontrou o input com o hash fornecido
}

    function estruturaMassa(){
        $colunas = [];
        foreach($this->tabela["colunas"] as $item){
            $editavel = $item["editavel"] ?? false;
            if($editavel){
                array_push($colunas, $item["id"]);
            }
        }
        
        $col = $this->db["conteudo"]["colunas"];
        $estrutura = $this->db["conteudo"]["estrutura"];
        $metas = $this->db["conteudo"]["metas"];
        
        $mapa = [];
        foreach($col as $item){
            if(in_array($item["nome"], $colunas)){
                $mapa[$item["nome"]] = $item["input"];
            }
        }
        
        $estruturais = [];
        foreach($estrutura as $chave=>$item){
             if(in_array($chave, $colunas)){
                   $estruturais[$chave] = true;
                }
        }
        
        foreach($metas as $chave=>$item){
            if(in_array($item, $colunas)){
                $mapa[$item] = $chave;
            }
        }
    
        $this->formulario = $this->pegaDados("formularios", $this->tabela["banco"]);
        

        $inputs = $this->formulario["conteudo"]["inputs"];
        $final = [];
        foreach($mapa as $chave=>$valor){
            $final[$chave] = $this->buscarInputPorHash($valor, $inputs);
        }
        
 
        
        return ["sucesso"=>true, "estrutura"=>$estruturais, "final"=>$final, "tabela"=>$this->tabela["banco"]];
    }
    
    function pegaModal(){
        $this->modal = $this->pegaDados("modais", $this->identificador);
        $this->formulario = false;
        
        if(isset($this->modal["sucesso"]) && isset($this->modal["conteudo"]["acao"])){
            $this->formulario = $this->pegaDados("formularios", $this->modal["conteudo"]["acao"]);
        }

        
        
        return ["sucesso"=>true, "modal"=>$this->modal, "formulario"=>$this->formulario];
    }
    
    function pontuacao(){
        if(!is_dir(__DIR__."/../conteudo/modulos/pagamento")){
            return 0;
        }
      
        include __DIR__."/../conteudo/modulos/pagamento/admins/pegaPedido.php";
      
      
        $comprados = new Comprados();
        $calc = $comprados->bancoParaPontos($this->db["conteudo"]["banco"] ?? $this->db["banco"]);
        return $calc;
  
    }
    
    function infoCalendar(){
       $banco = $this->banco;
       $prefixo = $this->prefixo;
       


       $colunas = $this->infoTabelaBanco($banco);
        
       $data = [$this->prefixo."_data as start"];
       
       if(in_array($prefixo."_nome", $colunas)){
            array_push($data, $prefixo."_nome as title");   
       }
       
       if(in_array($prefixo."_titulo", $colunas)){
            array_push($data, $prefixo."_titulo as title");   
       }
       
       if($this->calendario["imagem"] && in_array($prefixo."_imagem", $colunas)){
            array_push($data, $prefixo."_imagem as img");   
       }
       
 
       if(isset($this->calendario["linkar"]) && $this->calendario["linkar"] > 0){

           switch(intval($this->calendario["linkar"])){
               case 1:
                    if(in_array($prefixo."_url", $colunas)){
                        array_push($data, $prefixo."_url as url");  
                    }
                   break;
               case 2:
                    if(in_array($prefixo."_hash", $colunas)){
                        array_push($data, $prefixo."_hash as url");   
                    }
                   break;
               case 4:
  
                    if(in_array($link , $colunas)){
                        array_push($data, $link." as url");   
                    }
                   break;
           }
           
           
           
           
           
           
       
           
           
           
           
       }
       
 
       
       $implode = implode(",", $data);
   
       switch(intval($this->calendario["tipo"])){
           case 1:
               // Todos
               $seleciona = "SELECT ".$implode." FROM ".$banco;
               break;
           case 2:
               // Do usuário logado
               $autor = $prefixo."_autor";
               $usuario = $this->autor;
               $seleciona = "SELECT ".$implode." FROM ".$banco." WHERE ".$autor."='$usuario'";
               break;
           case 3:
               // Do usuario passado
               $autor = $prefixo."_autor";
               $usuario = $_POST["passado"] ?? false;
               if(!$usuario){
                   return ["erro"=>true, "mensagem"=>"Não foi passada a chave de usuário"];
               }
               $seleciona = "SELECT ".$implode." FROM ".$banco." WHERE ".$autor."='$usuario'";
               break;
           case 4:
               
               $custom = $this->calendario["custom"];
               if(!$custom ||!trim($custom)){
                   return ["erro"=>true, "mensagem"=>"Não foi definida uma coluna personalizada"];
               }
               
               $passado = $_POST["passado"] ?? false;
                if(!$passado){
                   return ["erro"=>true, "mensagem"=>"Não foi passada a chave de item"];
                }
                
               $passado = trim($passado);

               // Personalizado
               $seleciona = "SELECT ".$implode." FROM ".$banco." WHERE ".$custom."='$passado'";
               break;
       }
       
    
       $resultado = $this->conn->query($seleciona);
       $lista = [];
       
       if($resultado->num_rows >0){
           while($dado = $resultado->fetch_assoc()){
               array_push($lista, $dado);
           }
       }
       
       $configs = [];
       $configs["linkar"] = intval($this->calendario["linkar"] ?? 0);
       if($configs["linkar"] > 0 && $configs["linkar"] < 4){
           $configs["link"] = $this->calendario["url"];
       }
       
       
       
      // $conigs["linkar"] = 
       
       return ["sucesso"=>true, "lista"=>$lista, "config"=>$configs];
   
       
       
    }
    
    function close(){
        if($this->conn){
            $this->conn->close();
        }
        
    }
    
    function render(){
        if(!$this->identificador || !$this->modulo || !$this->tipo || !$this->autor){
            return ["erro"=>true, "mensagem"=>"Para acessar a API é necessário estar logado e enviar informações de ID, módulo e Tipagem"];
        }
        
        
        if(!is_dir($this->diretorio)){
            return ["erro"=>true, "mensagem"=>"O diretório definido não existe", $this->diretorio];
        }
        
        $callback = false;
        if(file_exists($this->diretorio."/admins/callback.php")){
            include $this->diretorio."/admins/callback.php";
            $callback = true;
        }
        
        $this->conn = conn();
        switch($this->tipo){
            case 'tabela':
                $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                
                if(isset($this->tabela["sucesso"])){
                    $this->tabela = $this->tabela["conteudo"];
                    
                 
                    
                    $this->db = $this->pegaDados("configs", $this->tabela["banco"]);
                    
                    if(isset($this->db["sucesso"])){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->tabela["banco"]);
                        return $this->listar();
                    }else{
                        return $this->db;
                    }
                }else{
                    return $this->tabela;
                }
                
                break;
            case 'ordenador':
                
                  $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                
                if(isset($this->tabela["sucesso"])){
                    $this->tabela = $this->tabela["conteudo"];
                    
                 
                    
                    $this->db = $this->pegaDados("configs", $this->tabela["banco"]);
                    
                    if(isset($this->db["sucesso"])){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->tabela["banco"]);
                        return $this->listarOrdenador();
                    }else{
                        return $this->db;
                    }
                }else{
                    return $this->tabela;
                }
                
                break;
            case 'salvaOrdenador':
                 $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                
                if(isset($this->tabela["sucesso"])){
                    $this->tabela = $this->tabela["conteudo"];
                    
                 
                    
                    $this->db = $this->pegaDados("configs", $this->tabela["banco"]);
                    
                    if(isset($this->db["sucesso"])){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->tabela["banco"]);
                        return $this->salvaOrdem();
                    }else{
                        return $this->db;
                    }
                }else{
                    return $this->tabela;
                }
                break;
            case 'preTabela':
                 $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                 if(isset($this->tabela["sucesso"])){
                     return ["sucesso"=>true, "tabela"=>$this->tabela];
                 }else{
                     return ["erro"=>true, "mensagem"=>"Tabela não encontrada"];
                 }
                 
                break;
            case 'formulario':
        
                $this->formulario = $this->pegaDados("formularios", $this->identificador);

                if(isset($this->formulario["sucesso"])){
        
                    $this->formulario = $this->formulario["conteudo"];
                    $this->db = $this->pegaDados("configs", $this->identificador);
                    
                    if(isset($this->db["conteudo"]["estrutura"]["pontos"]) && $this->db["conteudo"]["estrutura"]["pontos"] && $this->hash == "false"){
                        $pontos = $this->pontuacao();
                        if(!$pontos){
                             return ["sucesso"=>true, "formulario"=>false, "dados"=>false, "pontos"=>true];
                        }
                    }
                    
                    if($this->hash && isset($this->db["conteudo"]["estrutura"]["desabilitarEdicao"]) && $this->db["conteudo"]["estrutura"]["desabilitarEdicao"] == 1){
                
                        return ["sucesso"=>true, "formulario"=>false, "dados"=>false];
                    }
                   

                    
                    if($this->hash == "false" && isset($this->db["conteudo"]["estrutura"]["desabilitarNovo"]) && $this->db["conteudo"]["estrutura"]["desabilitarNovo"] == 1){
                    
                        return ["sucesso"=>true, "formulario"=>false, "dados"=>false];
                    }
              
                    
                    if(isset($this->db["sucesso"])){
                        $this->db = $this->db["conteudo"];
                        if($this->hash == "false"){
                            return ["sucesso"=>true, "formulario"=>$this->formulario, "dados"=>false];
                        }else{
                            $this->setDb($this->identificador);
                            $infos = $this->pegaInfos();
                            if(!$infos){
                                return ["sucesso"=>true, "formulario"=>false, "dados"=>false];
                            }else{
           
                                return ["sucesso"=>true, "formulario"=>$this->formulario, "dados"=>$infos];
                            }
                        }
                    }else{
    
                        return $this->db;
                    }
                }else{
                    return $this->formulario;
                }
                break;
            case 'salvar':
            case 'editar':
                $edit = $this->tipo == "editar" ? true : false;
                 $this->db = $this->pegaDados("configs", $this->identificador);
                 if($this->db["sucesso"]){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->identificador);
                        
                        if($callback && function_exists('call_salvo')){
                            $retorno =  call_salvo($this->salva($edit), $this);
                        }else{
                            $retorno = $this->salva($edit);
                        }
                        
                        $this->notificacao();
                        return $retorno;
                        
                    }else{
                        return $this->db;
                    }
                break;
            case 'apagar':
                   $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                if(isset($this->tabela["sucesso"])){
                    $this->tabela = $this->tabela["conteudo"];
                    $this->db = $this->pegaDados("configs", $this->tabela["banco"]);
                    
                    if($this->db["sucesso"]){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->tabela["banco"]);
                        return $this->apagar();
                    }else{
                        return $this->db;
                    }
                }else{
                    $this->db = $this->pegaDados("configs", $this->identificador);
                    if($this->db["sucesso"]){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->identificador);
                        return $this->apagar();
                    }
                     return $this->tabela;

                }
                break;
            case 'url':
                $this->db = $this->pegaDados("configs", $this->identificador);
                 if($this->db["sucesso"]){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->identificador);
                        return $this->url();
                    }else{
                        return $this->db;
                    }
                break;
            case 'edicaoMassaEstrutura':
                 $this->tabela = $this->pegaDados("tabelas", $this->identificador);
                if(isset($this->tabela["sucesso"])){
                    $this->tabela = $this->tabela["conteudo"];
                    $this->db = $this->pegaDados("configs", $this->tabela["banco"]);
                    return $this->estruturaMassa();
                }else{
                    return ["erro"=>true];
                }
                
                break;
            case 'modal':
                return $this->pegaModal();
                break;
            case 'infoCalendar':
                $this->calendario = $this->pegaDados("calendarios", $this->identificador);
                if(isset($this->calendario["sucesso"])){
                    $this->calendario = $this->calendario["conteudo"];
                    
                    $this->formulario = $this->pegaDados("formularios", $this->calendario["banco"]);
                    
                    $this->db = $this->pegaDados("configs", $this->calendario["banco"]);
                    
                    if(isset($this->db["sucesso"])){
                        $this->db = $this->db["conteudo"];
                        $this->setDb($this->calendario["banco"]);
                        return $this->infoCalendar();
                    }else{
                        return $this->db;
                    }
                    
         
                }else{
                    return $this->calendario;
                }
                
                
                
                break;
            default:
                return ["erro"=>true, "mensagem"=>"O tipo enviado é inválido"];
                break;
        }
        
        
    }
    
    function notificacao(){
        if(!is_dir(__DIR__."/../conteudo/modulos/notificacoes")){
            return;
        }
        
    $tipo = $this->tipo;
    $modulo = $this->modulo;
    $identificador = $this->identificador;
    $topicos = [];

    switch ($tipo) {
        case 'editar':
            $topicos = [2, 3];
            break;
        case 'salvar':
            $topicos = [1, 3];
            break;
        case 'deletar':
            $topicos = [4];
            break;
    }

    $lista = implode(",", $topicos);

    // Query para buscar as notificações e seus metadados
    $seleciona = "
        SELECT 
            nt.*,
            ntm.ntm_chave,
            ntm.ntm_valor
        FROM notificacoes_transacionais nt
        LEFT JOIN notificacoes_transacionais_meta ntm 
            ON nt.transacionais_id = ntm.ntm_transacionais
        WHERE nt.transacionais_modulo = '{$modulo}'
        AND nt.transacionais_formulario = '{$identificador}'
        AND nt.transacionais_acao IN ({$lista})
    ";

    $resultado = $this->conn->query($seleciona);

    $notificacoes = [];

    if ($resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $id = $dado["transacionais_id"];
            
            // Se a notificação ainda não foi adicionada ao array, inicializamos
            if (!isset($notificacoes[$id])) {
                $notificacoes[$id] = [
                    "quem" => $dado["transacionais_quem"],
                    "controlavel" => $dado["transacionais_controlavel"],
                    "titulo"=>$dado["transacionais_titulo"],
                    "corpo"=>$dado["transacionais_corpo"]
                ];
            }

            // Se houver metadados, adicionamos ao array "metas"
            if (!empty($dado["ntm_chave"])) {
                $notificacoes[$id][$dado["ntm_chave"]] = $dado["ntm_valor"];
            }
        }
    }else{
        return;
    }
    
    $consulta = "SELECT * FROM {$this->banco} WHERE {$this->prefixo}_id='{$this->novoId}'";
    $resultado = $this->conn->query($consulta);
    if($resultado->num_rows == 1){
        $dado = $resultado->fetch_assoc();
    }else{
        $dado = false;
    }
    
    if($this->meta){
        $metas = $this->meta->listar($this->novoId);
        foreach($metas as $chave=>$valor){
            $dado[$this->prefixo."_".$chave] = $valor;
        }
    }
    
    if(!$dado){
        return;
    }
    
    $dado = json_encode($dado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $notificacao = json_encode($notificacoes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $stmt = $this->conn->prepare("INSERT INTO notificacoes_pre (notificacao_pre_conteudo, notificacao_pre_setup) VALUES (?, ?)");
    if ($stmt) {
    $stmt->bind_param("ss", $dado, $notificacao);
    $stmt->execute();
    $stmt->close();
        
    } 
    }


}
?>