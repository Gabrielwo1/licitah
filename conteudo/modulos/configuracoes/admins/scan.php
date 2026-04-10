<?
session_start();
$_POST["pasta"] = "header";
class Acao{
    function __construct(){
        $this->pasta = $_POST["pasta"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
    }
    
    function render(){
        if(!$this->pasta){
            return ["erro"=>true, "mensagem"=>"Pasta não definida"];
        }
        
        if(!$this->usuario){
            return ["erro"=>true, "mensagem"=>"Função exclusiva para usuários logados"];
        }
        
        
        $pasta = __DIR__."/../../../componentes";
        
        if(!is_dir($pasta)){
             return ["erro"=>true, "mensagem"=>"A pasta de componentes não foi definida"];
        }
        
        if(!is_dir($pasta."/".$this->pasta)){
            return ["erro"=>true, "mensagem"=>"A pasta definida não existe"];
        }
        
        
        $arquivos = scandir($pasta."/".$this->pasta);
        
        $lista = [];
        foreach($arquivos as $item){
            if($item != "." && $item != ".."){
                 array_push($lista, $item);
            }
           
        }
        
        
        return ["sucesso"=>true, "lista"=>$lista];
        
    }
}


$acao = new Acao();
echo json_encode($acao->render(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>