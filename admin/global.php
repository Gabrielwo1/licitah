<?
function hasher($length = 15) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
}

function isJson($string) {
    // Tenta decodificar a string JSON
    if(is_array($string)){
        return true;
    }
    
    $decoded = json_decode($string);

    // Verifica se houve algum erro na decodificação
    return json_last_error() === JSON_ERROR_NONE;
}

include 'globalMeta.php';

class Acao{
    private $conn;
    private $bancoNome;
    private $prefixo;
    private $hash;
    private $autor;
    private $publico;
    private $render;
    private $chaves;
    private $renderizacao;
    private $lista;
    private $metalista;
    private $maping;
    private $meta;
    private $callBack;
    private $acoes;
 

    
    function __construct($setup = []){
        $this->conn = conn();
      
        if(isset($setup["banco"])){
            foreach($setup["banco"] as $chave=>$valor){
                $this->$chave = $valor;
            }
        }
        
        if(isset($setup["chaves"])){
            $this->chaves = $setup["chaves"];
        }
        
        if(isset($setup["renderizacao"]) && $setup["renderizacao"]){
            $this->renderizacao = $setup["renderizacao"];
        }
        
        if(isset($setup["lista"])){
            
            $lista = [];
            $metalistas = [];
            $maping = [];
            foreach($setup["lista"] as $item){
                if(!isset($item["meta"]) || $item["meta"] == false){
                    $lista[$item["chave"]] = $item["valor"];
                }else{
                     array_push($metalistas, $item);
                }
                $maping[$item["valor"]] = $item["header"];
                
            }

            $this->lista = $lista;
            $this->metalista = $metalistas;
            $this->maping = $maping;
        }
        
        if(isset($setup["meta"])){
            $this->meta = new Meta($setup["meta"]);
            if(isset($this->chaves)){
                $this->meta->set("chaves", $this->chaves);
            }
      
        }
        
        if(isset($setup["callBack"])){
            $this->callBack = $setup["callBack"];
        }
        
        if(isset($setup["acoes"])){
            $this->acoes = $setup["acoes"];
        }
        
       
    }
    
    function item($chaves = false){
         $infos = json_decode($_POST["infos"], true);
         if(!isset($infos["id"])){
             return false;
         }
            
            
        $id = $infos["id"];
        
        if(isset($this->hash) && $this->hash){
            $pesquisa = $this->hash;
        }else{
            $pesquisa = $this->prefixo."_id";
        }
        
        
        $banco = $this->bancoNome;
        $conn = $this->conn;
        
        $principal = [$this->prefixo."_id"];
        $keys = [];
         // Separa os bancos principais e as metas
         foreach($this->chaves as $item){
            if($item["meta"] == false){
                array_push($principal, $item["chave"]);
                array_push($keys, ["chave"=>$item["chave"], "map"=>$item["map"]]);
            }
        }
        
        $lista = implode(",", $principal);
        
        $seleciona = "SELECT $lista FROM $banco WHERE $pesquisa='$id'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $info = [];
            
            foreach($keys as $item){
                $info[$item["map"]] = isJson($dado[$item["chave"]]) ? json_decode($dado[$item["chave"]], true) : $dado[$item["chave"]];
            }
            $id = $dado[$this->prefixo."_id"];
            $metas = $this->meta ? $this->meta->lista($id) : [];
            
            if($metas){
                foreach($metas as $chave=>$valor){
                    $info[$chave] = $valor;
                }
            }
            
            return $info;
        }else{
            return false;
        }
        
        
        
        
        
        
        
    }
    
    function lista($condicao = false){
        
        $banco = $this->bancoNome;
        $conn = $this->conn;
        

        
        if(!isset($this->lista)){
            $tabelas = "*";
        }else{
            if(isset($this->hash) && $this->hash){
                $tabelas = [$this->hash];
                $this->lista[$this->hash] = "hasher";
            }else{
                $tabelas = [$this->prefixo."_id"];
                $this->lista[$this->prefixo."_id"] = "hasher";
            }
            

        

            foreach($this->lista as $chave=>$item){
                array_push($tabelas, $chave);
            }
            $tabelas = implode("," , $tabelas);
            
        }
        
        $organizador = $this->prefixo."_id";
        $seleciona = "SELECT $tabelas FROM $banco ORDER BY $organizador DESC";
        if ($condicao) {
            $seleciona .= " WHERE $condicao";
        }
        
        $resultado = $conn->query($seleciona);
        $resposta = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($this->lista)){
                    array_push($resposta, $dado);
                }else{
                    
                    $info = [];
                    foreach($this->lista as $chave=>$valor){
                        if(isset($dado[$chave])){
                            $info[$valor] = $dado[$chave];
                        }
                    }
                    
                    if(isset($this->metalista) && $this->metalista){
                        $id = $dado[$this->prefixo."_id"];
                        foreach($this->metalista as $ml){
                           $valorMeta = $this->meta->item($id, $ml["chave"]);
                           $info[$ml["valor"]] = $valorMeta;
                  
                        }
                    }
                    
                    
                    array_push($resposta, $info);
                }
            }
        }
        
        
        $r =[];
        $r["lista"] = $resposta;
        
        if(isset($this->maping) && $this->maping){
        $r["mapping"] = $this->maping;    
        }
       
        
        
        $r["acoes"] = isset($this->acoes) && $this->acoes ? $this->acoes : false;

        
        return $r;$resposta;
    
    }
    
    function trato($infos){
        $trato = [];
        
        
        if(is_array($infos)){
                 // Trata as entradas, deixando somente as validas no Setup
        foreach($infos as $chave=>$valor){
            
            $fica = false;
            foreach($this->chaves as $item){
                if($item["map"] == $chave){
                    $fica = true;
                }
            }
            
            if($fica){
                $trato[$chave] = isJson($valor) ? addslashes(json_encode($valor,true)) : addslashes($valor);
            }
        }
        }
   
        return $trato;
    }
    
    function todas($trato){
        // Verifica se todas as chaves obrigatorias estão ativas
        $todas = true;
        foreach($this->chaves as $item){
            if($item["obrigatorio"] && !isset($trato[$item["map"]])){
                $todas = false;
            }
        }
        return $todas;
    }
    
    function cadastra(){
        $infos = json_decode($_POST["infos"], true);
        
        $trato = $this->trato($infos);
        
        
        
        if(!$this->todas($trato)){
            return false;
        }
        
        
        $principalChaves = [];
        $principalValores = [];
        $metas = [];
        // Ajuste as entradas do banco de dados principal e as metas
        foreach($this->chaves as $item){
            if(isset($item["meta"]) && $item["meta"] == true){
                if(isset($trato[$item["map"]])  && isset($trato[$item["map"]]) && $trato[$item["map"]]){
                    array_push($metas,  ["chave"=>$item["map"], "valor"=>$trato[$item["map"]]]);
                }
            }else{
                if(isset($trato[$item["map"]])){
                    array_push($principalChaves, $item["chave"]);
                    array_push($principalValores, $trato[$item["map"]]);
                }
            }
         
        }
        
        
        // Verifica se Ha tags de imagens
        if(isset($infos["imagens"]) && $infos["imagens"]){
            foreach($infos["imagens"] as $img){
                 array_push($metas,  ["chave"=>"imagem_".$img["id"] , "valor"=>$img["srcs"]]);
            }
        }
        
        
        // Verifica se há tag de SEO
        if(isset($infos["seo"]) && $infos["seo"]){
            array_push($metas, ["chave"=>"seo", "valor"=>$infos["seo"]]);
        }
        
        // Verifica se há tag Categoria
        if(isset($infos["categoria"]) && $infos["categoria"]){
            array_push($metas, ["chave"=>"categoria", "valor"=>$infos["categoria"]]);

        }
        
        if(isset($this->hash) && $this->hash){
            $hash = hasher(15);
            array_push($principalChaves, $this->hash);
            array_push($principalValores, $hash);
        }
        
        $banco = $this->bancoNome;
        $conn = $this->conn;
        
        $principalChaves = implode("," ,  $principalChaves);
        $principalValores = "'" . implode("','", $principalValores) . "'";

        
        $resposta = [];
        
        
        
        $cadastra = "INSERT INTO $banco ($principalChaves) VALUES ($principalValores)";
        
        if($conn->query($cadastra)) {
            $id = $conn->insert_id;
            $resposta["sucesso"] = true;
            
            
            
            foreach($metas as $item){
                $this->meta->cadastra($id, $item);
            }
            
            
            $this->render($id);
            $this->url($id);
            $this->autor($id);
            
            
            $resposta["sucesso"] = true;
            $resposta["id"] = isset($this->hash) && $this->hash ? $hash : $id;
            if(isset($this->callBack) && $this->callBack && isset($this->callBack["cadastro"]) && $this->callBack["cadastro"]){
                $resposta["callBack"] = $this->callBack["cadastro"];
            }
            
            
        }else{
            $resposta["sucesso"] = false;
            $resposta["erro"] = $conn->error;
        }
        
        return $resposta;
        
        
       
    }
    
    function edita() {
        $infos = json_decode($_POST["infos"], true);


        if (!isset($infos["id"])) {
            return false;
        }

        $id = $infos["id"];
        
        $banco = $this->bancoNome;
        $conn = $this->conn;
        
        $identificador = $this->prefixo."_id";
        if(isset($this->hash) && $this->hash){
            $hash = $this->hash;
            $seleciona = "SELECT $identificador FROM $banco WHERE $hash='$id'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $id = $dado[$identificador];
            }else{
                return false;
            }
        }
        
        
    
    
        
        
         
        $trato = $this->trato($infos);
        
        
        
        if(!$this->todas($trato)){
            return false;
        }
        
        
        $query = [];
        $metas = [];
        // Ajuste as entradas do banco de dados principal e as metas
        foreach($this->chaves as $item){
            if(isset($item["meta"]) && $item["meta"] == true){
                if(isset($trato[$item["map"]])  && isset($trato[$item["map"]]) && $trato[$item["map"]]){
                    $metas[$item["map"]] = $trato[$item["map"]];
                }
            }else{
                if(isset($trato[$item["map"]])){
                    array_push($query, $item["chave"]."='".$trato[$item["map"]]."'");

                }
            }
        }
        
        
        // Verifica se Ha tags de imagens
        if(isset($infos["imagens"]) && $infos["imagens"]){
            foreach($infos["imagens"] as $img){
                 $metas["imagem_".$img["id"]] = $img["srcs"];
            }
        }
        
        
        // Verifica se há tag de SEO
        if(isset($infos["seo"]) && $infos["seo"]){
            $metas["seo"] = $infos["seo"];
        }
        
        // Verifica se há tag Categoria
        if(isset($infos["categoria"]) && $infos["categoria"]){
            $metas["categoria"] = $infos["categoria"];
        }
        
        
        $query = implode(",", $query);
        
        if($query){
            $atualiza = "UPDATE $banco SET $query WHERE $identificador='$id'";
            $update = $conn->query($atualiza);
        }else{
            $update = true;
        }
        
        
        if($update == true){
            
            $memoria = $this->meta ? $this->meta->lista($id) : [];
            
            
            foreach($metas as $chave=>$valor){
                if(!isset($memoria[$chave])){
                    $this->meta->cadastra($id, ["chave"=>$chave, "valor"=>$valor]);
                    unset($memoria[$chave]);
                }else if($memoria[$chave] != $metas[$chave] && $metas[$chave]){
                    $this->meta->edita($id, ["chave"=>$chave, "valor"=>$valor]);
                    unset($memoria[$chave]);
                }else if($memoria[$chave] == $metas[$chave]){
                    unset($memoria[$chave]);
                }
            }
            
            
           if(is_array($memoria)){
              foreach($memoria as $chave=>$item){
               $this->meta->deleta($id, ["chave"=>$chave, "valor"=>$valor]);
           } 
           }
           
           
            $this->render($id);

            return true;
            
        }else{
            return false;
        }
        
        
    

        
    }
    
    function deletar(){
        $infos = json_decode($_POST["infos"], true);
        if(isset($infos["hash"]) && $infos["hash"]){
            $hash = $infos["hash"];
            
            if(isset($this->hash) && $this->hash){
                $base = $this->hash;
            }else{
                $base = $this->prefixo."_id";
            }
            
            $identificador = $this->prefixo."_id";
            
            $banco = $this->bancoNome;
            $conn = $this->conn;
        
            
            $seleciona = "SELECT $identificador FROM $banco WHERE $base='$hash'";
            $resultado = $conn->query($seleciona);
            
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $id = $dado[$identificador];
                
                
                $lista = $this->meta ? $this->meta->lista($id) : [];
                if(is_array($lista)){
                    foreach($lista as $chave=>$valor){
                    $this->meta->deleta($id, ["chave"=>$chave]);
                    }
                    
                }
                
                
                $deleta = "DELETE FROM $banco WHERE $identificador='$id'";
                if($conn->query($deleta) == true){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
            
            
        }else{
            return false;
        }
 
    }
    
    function verColuna($coluna) {
    $conexao = $this->conn;
    $banco = $this->bancoNome;
    $consulta = "SHOW COLUMNS FROM $banco LIKE '$coluna'";
    $resultado = mysqli_query($conexao, $consulta);

    if($resultado){
        return mysqli_num_rows($resultado) > 0 ?  true : false;
    }else{
        return false;
    }
}

    function categoria($id){
        $conn = $this->conn;
        $seleciona = "SELECT * FROM categorias WHERE categoria_id='$id'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $dados = [
                "nome" => $dado["categoria_nome"],
                "tipo" => $dado["categoria_tipo"]
                ];
            return $dados;
        }else{
            return false;
        }

    }

    function render($id){
    
        $banco = $this->bancoNome;
        $seletor = $this->prefixo."_id";
        $conn = $this->conn;
        
        if($this->verColuna("render") &&  $this->renderizacao){
            $r = $this->renderizacao;
            $principal = $r["principal"];
            $metas = $r["metas"];
            $chaves = [];
            foreach($principal as $chave=>$p){
                array_push($chaves, $chave);
            }
            
            
            $query = "SHOW COLUMNS FROM $banco LIKE 'url_publica'";
            $result = $conn->query($query);
            $url = false;
             if ($result->num_rows > 0) {
                 array_push($chaves, "url_publica");
                 $url = true;
             }

            
            
            $chaves = implode(",", $chaves);
            $seleciona = "SELECT $chaves FROM $banco WHERE $seletor='$id'";
   
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $info = [];
                foreach($principal as $chave=>$p){
                $info[$p] = $dado[$chave];
                }
                
                foreach($metas as $m){
                    switch($m){
                        case 'categoria':
                             $info["categoria"] =  $this->categoria($this->meta->item($id, $m));
                            break;
                        default:
                             $info[$m] = $this->meta->item($id, $m);
                            break;
                    }
                   
                }
                
                
                if($url){
                    $info["url"] = $dado["url_publica"];
                }
                
                
                $json = addslashes(json_encode($info, JSON_UNESCAPED_UNICODE));
        
                $cadastra = "UPDATE $banco set  render='".$json."' WHERE $seletor='$id'";
 
                $conn->query($cadastra);
                
                
            }
        }

    }
    
    function autor($id){
        $banco = $this->bancoNome;
        $seletor = $this->prefixo."_id";
        $conn = $this->conn;
        if($this->verColuna("autor") && isset($_SESSION["id"])){
            $autor = $_SESSION["id"];
            $atualiza = "UPDATE $banco SET autor='$autor' WHERE $seletor='$id'";
            $conn->query($atualiza);
        }
    }
    
    function geraURL($name){

    $url = str_replace(' ', '-', $name);
 
    $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
    
    $url = strtolower($url);
    
    return $url;

    }

    function url($id){
        $banco = $this->bancoNome;
        $seletor = $this->prefixo."_id";
        $nome = false;
        $conn = $this->conn;
        
     
        if($this->verColuna($this->prefixo."_nome")){
            $nome = $this->prefixo."_nome";
        }
        
        if(!$nome && $this->prefixo."_titulo"){
            $nome = $this->prefixo."_titulo";
        }
        
        if($this->verColuna("url_publica") && $nome){
            $seleciona = "SELECT $nome FROM $banco WHERE $seletor='$id'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $url = $this->geraURL($dado[$nome]);
                
                $contador = 1;
                $final = false;
                $teste = $url;
                while(true){
                    
                    $verifica = "SELECT * FROM $banco WHERE url_publica='$teste'";
                    $resultado = $conn->query($verifica);
                    if($resultado->num_rows == 0){
                        $final = $teste;
                        break;
                    }else{
                        $contador++;
                        $teste = $url."-".$contador;
                    }
                }
                
                
                if($final){
                    $atualiza = "UPDATE $banco SET url_publica='$final' WHERE $seletor='$id'";
                    $conn->query($atualiza);
                }
                
                
                
                
                
                
            }
          
            
            
        }
    }
    
    function renderMassa(){
        $banco = $this->bancoNome;
        $seletor = $this->prefixo."_id";
        $conn = $this->conn;
        
        
        $nome = $this->verColuna($this->prefixo."_titulo") ? $this->prefixo."_titulo" : $this->prefixo."_nome";
        
        
        $seleciona = "SELECT $nome , $seletor, url_publica FROM $banco";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $id = $dado[$seletor];
                $this->render($id);
                
                if(!$dado["url_publica"]){
                
                    $this->url($id);
                }
                
                
            }
        }
        
        
        
        
        return true;
    }
    
    
}
?>