<?
header('Content-Type: application/json; charset=utf-8');

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Acao{
    public $acao;
    public $id;
    public $modulo;
    public $autor;
    function __construct(){
        $this->modulo = $_POST["modulo"] ?? false;
        $this->id = $_POST["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->autor = $_SESSION["id"] ?? false;
    }
    
    function listarInputs($array, $entradas) {
    foreach ($array as $item) {

        $input = [
            "t" => $item["tipo"],
            "n" => $item["nome"],
            "o" => $item["obrigatorio"]
        ];
        
        $proibidos = ["coluna", "col", "card", "seo","cardSalvar","categoria","tags","imagemDestaque", "grupo", "tab"];
        
     
        if(!in_array($item["tipo"], $proibidos)){
            // Adiciona o input ao array de entradas
            $entradas[$item["hash"]] = $input;

        }
        

        // Verifica se há filhos e chama a função recursivamente
        if (isset($item["filhos"]) && is_array($item["filhos"]) && count($item["filhos"]) > 0) {
            $entradas = $this->listarInputs($item["filhos"], $entradas);
        }
    }
    return $entradas;
}
    
    function map() {
        $caminho = __DIR__ . "/../conteudo/modulos/" . $this->modulo;
        
        if (!is_dir($caminho)) {
            return ["erro" => true, "mensagem" => "O módulo definido não existe"];
        }
        
        if (!file_exists($caminho . "/admins/tabelas/" . $this->id . ".json")) {
            return ["erro" => true, "mensagem" => "A tabela definida não existe"];
        }
        
        $tabela = json_decode(file_get_contents($caminho . "/admins/tabelas/" . $this->id . ".json"), true);
        
        $banco = $tabela["banco"];
        
        $aut = $tabela["acoes"]["opcoes"]["importador"] ?? false;
        
        if(!$aut){
            return ["erro"=>true, "mensagem"=>"Esse módulo não aceita importação"];
        }
        
        if (!file_exists($caminho . "/admins/configs/" . $banco . ".json")) {
            return ["erro" => true, "mensagem" => "O banco de dados não existe"];
        }
        
        $estrutura = json_decode(file_get_contents($caminho . "/admins/configs/" . $banco . ".json"), true);
        
        
         if (!file_exists($caminho . "/admins/formularios/" . $banco . ".json")) {
            return ["erro" => true, "mensagem" => "O formulario não existe"];
        }
        
        $formulario = json_decode(file_get_contents($caminho . "/admins/formularios/" . $banco . ".json"), true);
        
        
        $entradas = [];
        
        $inputs = $formulario["inputs"];
        $entradas = $this->listarInputs($inputs, $entradas);
        
        foreach($entradas as $chave=>$valor){
            if($valor["n"] == null){
                $nome = false;
                
                foreach($estrutura["colunas"] as $entrada){
                   if($entrada["input"] == $chave){
                       $nome = $entrada["nome"];
                   }
                }
                
                
                
                if(!$nome && isset($estrutura["metas"]) && isset($estrutura["metas"][$chave])){
                    $nome = $estrutura["metas"][$chave];
                }
                
                
                if($nome){
                    $entradas[$chave]["n"] = $nome;
                }
                
            }
        }
        
        
        
        
         $especiais = ["imagemDestaque","galeria","categoria","tags","seo"];
            $e = [];
           foreach($especiais as $item){
               if($estrutura["estrutura"][$item]){
                   array_push($e, $estrutura["estrutura"][$item]);
               }
           }
        
   
        return ["sucesso" => true, "mensagem" => "O módulo existe", "entradas"=>$entradas, "especiais"=>$especiais, "identificador"=>$banco, "modulo"=>$this->modulo];
           
       }
       
    function newmap() {
        $caminho = __DIR__ . "/../conteudo/modulos/" . $this->modulo;
        
        if (!is_dir($caminho)) {
            return ["erro" => true, "mensagem" => "O módulo definido não existe"];
        }
        
        if (!file_exists($caminho . "/admins/tabelas/" . $this->id . ".json")) {
            return ["erro" => true, "mensagem" => "A tabela definida não existe"];
        }
        
        $tabela = json_decode(file_get_contents($caminho . "/admins/tabelas/" . $this->id . ".json"), true);
        
        $banco = $tabela["banco"];
        
        $aut = $tabela["acoes"]["opcoes"]["importador"] ?? false;
        
        if(!$aut){
            return ["erro"=>true, "mensagem"=>"Esse módulo não aceita importação"];
        }
        
        if (!file_exists($caminho . "/admins/configs/" . $banco . ".json")) {
            return ["erro" => true, "mensagem" => "O banco de dados não existe"];
        }
        
        $estrutura = json_decode(file_get_contents($caminho . "/admins/configs/" . $banco . ".json"), true);
        
        
         if (!file_exists($caminho . "/admins/formularios/" . $banco . ".json")) {
            return ["erro" => true, "mensagem" => "O formulario não existe"];
        }
        
        $formulario = json_decode(file_get_contents($caminho . "/admins/formularios/" . $banco . ".json"), true);
        
        
        $entradas = [];
        
        $inputs = $formulario["inputs"];
        $entradas = $this->listarInputs($inputs, $entradas);
        
        

        
        
        
        
         $especiais = ["imagemDestaque","galeria","categoria","tags","seo"];
            $e = [];
           foreach($especiais as $item){
               if($estrutura["estrutura"][$item]){
                   array_push($e, $estrutura["estrutura"][$item]);
               }
           }
        
   
        return ["sucesso" => true, "mensagem" => "O módulo existe", "entradas"=>$entradas, "especiais"=>$especiais, "identificador"=>$banco, "modulo"=>$this->modulo];
           
       }
    
    function render(){
        if(!$this->id || !$this->modulo){
            return ["erro"=>true, "mensagem"=>"Informações vitais não foram definidas"];
        }
        
        if(!$this->autor){
            return ["erro"=>true, "mensagem"=>"Ação Permitida somente para usuários logados"];
        }

        switch($this->acao){
            case 'map':
                return $this->map();
                break;
            case 'newmap':
                return $this->newmap();
                break;
            case 'importar':
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi definida uma ação válida"];
                break;
        }
    }
}

$acao = new Acao();
echo json_encode($acao->render(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>