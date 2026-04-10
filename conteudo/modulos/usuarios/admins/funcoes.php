<?
header('Content-Type: application/json; charset=utf-8');

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include __DIR__."/../../../../admin/conn.php";
class Funcoes{
    public $usuario;
    public $funcao;
    public $acao;
    public $conn;
    function __construct(){

        $this->usuario = $_SESSION["id"] ?? false;
        $this->funcao =  intval($_SESSION["funcao"]) ?? 2;
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
    }
    
    function pegaArquivos($caminho, $tipo, $array = []){
     
        if(is_dir($caminho)){
            $pasta = scandir($caminho);
  
            foreach($pasta as $item){

                if($item != "." && $item != ".."){
                    $conteudo = json_decode(file_get_contents($caminho.$item), true);
                    
  
                    switch($tipo){
                        case 'form':
                            array_push($array, [
                                "nome"=>$conteudo["nome"],
                                "hash"=>$conteudo["hash"]
                                ]
                                );
                            break;
                        case 'pagina':
                            $trato = explode(".", $item)[0];
                            array_push($array, $trato);
                            break;
                        case 'tabela':
                            array_push($array, [
                                "nome"=>$conteudo["nome"],
                                "hash"=>$conteudo["banco"] ?? false
                                ]
                                );
                            break;
                        case 'apis':
                            if(isset($conteudo["nome"])){
                                array_push($array, [
                                    "nome"=>$conteudo["nome"],
                                    "key"=>explode(".", $item)[0]
                                    ]
                                );
                            }
                            break;
                    }
                }
            }
        }

        return $array;
        
    }
    
    function pegaPublicas($caminho){
        $lista = [];
        if(!is_dir($caminho."/publico")){
            return $lista;
        }
        foreach(scandir($caminho."/publico") as $item){
            if($item != "." && $item != ".."){
                array_push($lista, $item);
            }
        }
        return $lista;
    }
    
    function listar(){
        $modulos = [];
        $id = $this->pegaFuncao();
        if(!$id){
           return ["erro"=>true, "mensagem"=>"O ID informado é inválido"];
        }
        
        $respondidas = [];
        $seleciona = "SELECT * FROM funcoes_crud WHERE funcao_crud_funcao='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($respondidas[$dado["funcao_crud_modulo"]])){
                    $respondidas[$dado["funcao_crud_modulo"]] = [];
                }
                
                $respondidas[$dado["funcao_crud_modulo"]][$dado["funcao_crud_formulario"]] = ["c"=>$dado["funcao_crud_criar"],"u"=>$dado["funcao_crud_atualizar"],"d"=>$dado["funcao_crud_deletar"],"t"=>$dado["funcao_crud_terceiros"]];
            }
        }
        
        
        $pages = ["admin"=>[], "publicas"=>[], "paginas"=>[]];
        
        $seleciona = "SELECT * FROM funcoes_paginas WHERE funcao_pagina_funcao='$id'";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $url = $dado["funcao_pagina_url"];
                $tipo = $dado["funcao_pagina_tipo"];
                $pages[$tipo][$url] = [
                    "v"=>$dado["funcao_pagina_ativa"],
                    "a"=>$dado["funcao_pagina_acao"],
                    "cb"=>$dado["funcao_pagina_cb"]
                    ];
            }
        }
        
        $apisArray = [];
        
        $seleciona = "SELECT * FROM funcoes_apis WHERE funcao_api_funcao='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($apisArray[$dado["funcao_api_modulo"]])){
                    $apisArray[$dado["funcao_api_modulo"]] = [];
                }
                $apisArray[$dado["funcao_api_modulo"]][$dado["funcao_api_key"]] = [
                    "limite"=>intval($dado["funcao_api_limite"]),
                    "quantidade"=>intval($dado["funcao_api_quantidade"]),
                    "reacessivel"=>intval($dado["funcao_api_reacessivel"]),
                    "reacessivel_quantidade"=>intval($dado["funcao_api_reacessivel_quantidade"]),
                    "reacessivel_tipo"=>intval($dado["funcao_api_reacessivel_tipo"]),
                    "renovavel"=>intval($dado["funcao_api_renovavel"]),
                    "renovavel_quantidade"=>intval($dado["funcao_api_renovavel_quantidade"]),
                    "renovavel_tipo"=>intval($dado["funcao_api_renovavel_tipo"])
                    ];
            }
        }
        
        
        
        $caminho = __DIR__."/../../";
        $diretorio = scandir($caminho);
        
        foreach($diretorio as $item){
            if($item != "." && $item != ".." && is_dir($caminho.$item)){
                if(file_exists($caminho.$item."/manifest.json")){ 
                    $conteudo = json_decode(file_get_contents($caminho.$item."/manifest.json"), true);
                    
                    $formularios = $caminho.$item."/admins/formularios/";
                    $paginas = $caminho.$item."/admins/paginas/";
                    $tabelas = $caminho.$item."/admins/tabelas/";
                    $apis = $caminho.$item."/admins/apis/";
                
                    $array = [];
                    $paginasViews = $caminho.$item."/views/";
                    if(is_dir($paginasViews)){
                        $pasta = scandir($paginasViews);
                            foreach($pasta as $it){
                                if($it != "." && $it != ".."){
                                    $trato = explode(".", $it)[0];
                                    array_push($array, $trato);
                                }
                            }
                    }

                    $modulo = [
                        "pasta"=>$item,
                        "name"=>$conteudo["name"],
                        "icone"=>$conteudo["icon"],
                        "formularios"=>$this->pegaArquivos($formularios, "form"),
                        "paginas"=>$this->pegaArquivos($paginas, "pagina"),
                        "views"=>$array,
                        "tabelas"=>$this->pegaArquivos($tabelas, "tabela"),
                        "publicas"=>$this->pegaPublicas($caminho.$item),
                        "apis"=>$this->pegaArquivos($apis, "apis")
                    ];
                    array_push($modulos, $modulo);
     
                }
            }
           
        
        }
        
        return ["sucesso"=>true, "lista"=>$modulos, "isadmin"=>$id == "1" ? true : false, "respostas"=>["crud"=>$respondidas, "paginas"=>$pages, "apis"=>$apisArray]]; 
    }
   
    function novo(){
        

        $conn = conn();
        $id = $_POST["id"] ?? false;
        $regras = $_POST["regras"] ?? false;
        $tipo = $_POST["tipo"] ?? 2;
        $nome = $_POST["nome"] ?? false;
        
        if(!$regras || !$nome){
            return ["erro"=>true, "mensagem"=>"Os dados enviados não são válidos"];
        }
        
        if($this->acao == "novo"){
            $autor = $_SESSION["id"];
            $acao = "INSERT INTO  funcoes (funcao_nome, funcao_autor, funcao_regras, funcao_tipo) VALUES ('$nome', '$autor', '$regras', '$tipo')";
            if($conn->query($acao) == true){
                return ["sucesso"=>true, "mensagem"=>"Função cadastrada com sucesso", "id"=>$conn->insert_id];
           }else{
                return ["erro"=>true, "mensagem"=>"Falha ao cadastrar a função", "sql"=>$conn->error];
           }
            
        }else{
           if(!$id){
               return ["erro"=>true, "mensagem"=>"Não foi enviado um ID válido para ediação"];
           } 
           
           if($id == 1){
               return ["sucesso"=>true, "mensagem"=>"As autorizações de administrador não são atualizaveis"];
           }
           
           
           if($id == 2){
               $tipo = 2;
           }
           $acao = "UPDATE  funcoes SET funcao_regras='$regras', funcao_tipo='$tipo' WHERE funcao_id='$id'";
   
           if($conn->query($acao) == true){
                return ["sucesso"=>true, "mensagem"=>"Função atualizada com sucesso"];
           }else{
                return ["erro"=>true, "mensagem"=>"Falha ao atualizar a função", "sql"=>$conn->error];
           }
           
           
        }

    }
    
    function itens(){

        $conn = conn();
        
        $lista = [];
        $seleciona = "SELECT * FROM funcoes";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows >0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "id"=>$dado["funcao_id"],
                    "nome"=>$dado["funcao_nome"],
                    "apagavel"=> intval($dado["funcao_id"]) > 2 ? true : false,
                    "tipo"=>$dado["funcao_tipo"]
                    ];
                    array_push($lista, $item);
            }
        }
 
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function apagar(){
        $deletado = $_POST["deletado"] ?? false;
        $vinculado = $_POST["vinculo"] ?? false;
        
        if(!$deletado || !$vinculado){
            return ["erro"=>true, "mensagem"=>"Não foram enviadas informações válidas para essa ação"];
        }
        
        if(intval($deletado) < 3){
            return ["erro"=>true, "mensagem"=>"Essa função não pode ser deletada"];
        }
        
        if(intval($vinculado) == 1){
            return ["erro"=>true, "mensagem"=>"Essa função não pode ser vinculada"];
        }
        

        $conn = conn();
        
        
        
        
        $seleciona = "SELECT * FROM funcoes WHERE funcao_id='$deletado'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
             return ["erro"=>true, "mensagem"=>"Não existe a função a ser deletada"];
        }
        
        $seleciona = "SELECT * FROM funcoes WHERE funcao_id='$vinculado'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
             return ["erro"=>true, "mensagem"=>"Não existe a função a ser vinculada"];
        }
        
        
        $acao = "DELETE FROM funcoes WHERE funcao_id='$deletado'";
        if($conn->query($acao) == true){
             return ["sucesso"=>true, "mensagem"=>"Função deletada com sucesso", "sql"=>$conn->error];
        }else{
              return ["erro"=>true, "mensagem"=>"Não foi possível deletar a função", "sql"=>$conn->error];
        }
        
    }
    
    function paginas(){
        $lista = [];
        foreach(scandir(__DIR__."/../../../paginas") as $item){
            if(is_dir(__DIR__."/../../../paginas/".$item) && $item != "." && $item != ".."){
                array_push($lista, $item);
            }
        }
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function pegaFuncao(){
         $hash = $_POST["id"] ?? false;
        if(!$hash){
            return false;
        }
        $hash = trim($hash);
        $seleciona = "SELECT * FROM  funcoes WHERE funcao_hash='$hash'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
             return false;
        }
        $dado = $resultado->fetch_assoc();
        $id = $dado["funcao_id"];
        return $id;
    }
    
    function saveFluxo(){
       $id = $this->pegaFuncao();
       if(!$id){
           return ["erro"=>true, "mensagem"=>"O ID informado é inválido"];
       }
        
        $crud = $_POST["crud"] ?? false;
        if($crud){
            $crud = json_decode($crud, true);
            
            foreach($crud as $modulo=>$forms){
                foreach($forms as $form=>$chave){
                    
                    
                    
                    
                    $seleciona = "SELECT funcao_crud_id FROM funcoes_crud WHERE  funcao_crud_modulo='$modulo' AND funcao_crud_formulario='$form' AND	funcao_crud_funcao='$id'";
                    $resultado = $this->conn->query($seleciona);
                         $criar = $chave["c"];
                        $update = $chave["u"];
                        $deleta = $chave["d"];
                        $terceiros = $chave["t"];
                    if($resultado->num_rows == 0){
                        $acao = "INSERT INTO funcoes_crud (funcao_crud_modulo,funcao_crud_formulario,funcao_crud_funcao,funcao_crud_criar,funcao_crud_atualizar,funcao_crud_deletar,funcao_crud_terceiros) VALUES ('$modulo', '$form', '$id', '$criar', '$update', '$deleta', '$terceiros')";
                    }else{
                        $dado = $resultado->fetch_assoc();
                        $idUp = $dado["funcao_crud_id"];
                        $acao = "UPDATE funcoes_crud SET funcao_crud_criar='$criar',funcao_crud_atualizar='$update',funcao_crud_deletar='$deleta',funcao_crud_terceiros='$terceiros' WHERE funcao_crud_id='$idUp'";
                    }
                    $this->conn->query($acao);
                   
                }
            }
        }
        
        $paginas = $_POST["paginas"] ?? false;
        if($paginas){
                $paginas = json_decode($paginas, true);
            
                foreach($paginas as $grupo=>$urls){
                    foreach($urls as $url=>$setup){
                        $visivel = $setup["v"];
                        $acao = $setup["a"] ?? "";
                        $cb = $setup["cb"] ?? "";
            
                        // Sanitização das variáveis
                        $grupo = $this->conn->real_escape_string($grupo);
                        $url = $this->conn->real_escape_string($url);
                        $visivel = (int)$visivel;
            
                        $seleciona = "SELECT funcao_pagina_id FROM funcoes_paginas WHERE funcao_pagina_funcao='$id' AND funcao_pagina_url='$url' AND funcao_pagina_tipo='$grupo'";
                        $resultado = $this->conn->query($seleciona);
            
                        if($resultado->num_rows == 0){
                            $acao = "INSERT INTO funcoes_paginas (funcao_pagina_funcao, funcao_pagina_url, funcao_pagina_tipo, funcao_pagina_ativa, funcao_pagina_acao, funcao_pagina_cb) VALUES ('$id', '$url', '$grupo', '$visivel', '$acao', '$cb')";
                        } else {
                            $dado = $resultado->fetch_assoc();
                            $idPagina = $dado["funcao_pagina_id"];
                            $acao = "UPDATE funcoes_paginas SET funcao_pagina_ativa='$visivel', funcao_pagina_acao='$acao', funcao_pagina_cb='$cb' WHERE funcao_pagina_id='$idPagina'";
                        }
            
                        if (!$this->conn->query($acao)) {
                            // Tratamento de erro caso a query falhe
                            die("Erro ao executar a query: " . $this->conn->error);
                        }
                    }
                }
            }
            
            
        $apis = $_POST["apis"] ?? false;
        if($apis){
            $apis = json_decode($apis, true);
            
            foreach($apis as $modulo=>$api){
                foreach($api as $key=>$a){
                    
                     $limite = $a["limite"] ?? false;
                     $quantidade = $a["quantidade"] ?? 0;
                     $reacesso = $a["reacesso"] ?? false;
                     $reacessoNumero = $a["reacessoNumero"] ?? 1;
                     $reacessoTipo = $a["reacessoTipo"] ?? 0;
                     $renovavel = $a["renovavel"] ?? false;
                     $renovevalTipo = $a["renovavelTipo"] ?? 0;
                     $renovavelNumero = $a["renovavelNumbero"] ?? 1;
                    $autor = $this->usuario;
   
                    $seleciona = "SELECT funcao_api_id FROM  funcoes_apis WHERE funcao_api_funcao='$id' AND	funcao_api_modulo='$modulo' AND	funcao_api_key='$key'";
        
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows == 0){
                        $acao = "INSERT INTO  funcoes_apis ( funcao_api_funcao,funcao_api_modulo,	funcao_api_key,funcao_api_limite,funcao_api_quantidade,funcao_api_reacessivel,funcao_api_reacessivel_quantidade,funcao_api_reacessivel_tipo,funcao_api_renovavel,funcao_api_renovavel_quantidade,funcao_api_renovavel_tipo) VALUES (
                '$id', '$modulo', '$key', '$limite', '$quantidade', '$reacesso', '$reacessoNumero', '$reacessoTipo', '$renovavel', '$renovavelNumero', '$renovevalTipo')";
                    }else{
                        $dado = $resultado->fetch_assoc();
                        $ide = $dado["funcao_api_id"];
                        $acao = "UPDATE funcoes_apis SET funcao_api_limite='$limite', funcao_api_quantidade='$quantidade',funcao_api_reacessivel='$reacesso',funcao_api_reacessivel_quantidade='$reacessoNumero',funcao_api_reacessivel_tipo='$reacessoTipo',funcao_api_renovavel='$renovavel',funcao_api_renovavel_quantidade='$renovavelNumero',
                        funcao_api_renovavel_tipo='$renovevalTipo' WHERE funcao_api_id='$ide'";
                    }
                    $this->conn->query($acao);
                }
            }
            
        }
        
        return ["sucesso"=>true, "mensagem"=>"Itens Atualizados com Sucesso"];
    }
    
    function render(){
        if(!$this->usuario || $this->funcao > 1){
            return ["erro"=>true, "mensagem"=>"Função exclusiva para administradores"];
        }
        
        switch($this->acao){
            case "listar":
                return $this->listar();
                break;
            case 'paginas':
                return $this->paginas();
                break;
            case 'itens':
                return $this->itens();
                break;
            case 'novo':
            case 'editar':
                return $this->novo();
                break;
            case 'apagar':
                return $this->apagar();
                break;
            case 'saveFluxo':
                return $this->saveFluxo();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida é inválida"];
                break;
        }
        
        
    }
}

$funcoes = new Funcoes();
$resposta = $funcoes->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>