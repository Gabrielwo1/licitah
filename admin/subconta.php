<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__."/conn.php";

class Acao{
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->sub =  $_SESSION["sub"] ?? false;
    }
    
    function tipos(){
        $tipos = [];
        $modulos = scandir(__DIR__."/../conteudo/modulos");
        foreach($modulos as $modulo){
            if($modulo != "." && $modulo != ".."){
                $manifesto = __DIR__."/../conteudo/modulos/".$modulo."/manifest.json";
                if(file_exists($manifesto)){
                    $conteudo = json_decode(file_get_contents($manifesto), true);
                    if(!empty($conteudo["multiconta"])){
                        $tipos[$modulo] = [];
                        
                        $formularios = __DIR__."/../conteudo/modulos/".$modulo."/admins/formularios/";
                        foreach(scandir($formularios) as $formulario){
                            if($formulario != "." && $formulario != ".."){
                                $form = json_decode(file_get_contents($formularios.$formulario), true);
                                $tipos[$modulo][] = ["nome"=>$form["nome"], "hash"=>$form["hash"]];
                            }
                        }
                        
                        
                        
                    }
                }
            }
        }
        return ["sucesso"=>true, "lista"=>$tipos];
    }
    
function lista(){
    $modulo = $_POST["modulo"] ?? false;
    $formulario = $_POST["formulario"] ?? false;
    if(!$modulo || !$formulario){
        return ["erro" => true, "mensagem" => "Não foram enviados todos os parâmetros necessários"];
    }
    
    $modulo = trim($modulo);
    $dir = __DIR__ . "/../conteudo/modulos/" . $modulo;

    if(!is_dir($dir)){
        return ["erro" => true, "mensagem" => "O módulo enviado não existe"];
    }
    
    if(!is_dir($dir."/admins/multiconta")){
        return ["erro" => true, "mensagem" => "A pasta multiconta"];
    }

    if(!file_exists($dir . "/admins/multiconta/lista.php")){
        return ["erro" => true, "mensagem" => "O arquivo de multiconta não existe"];
    }

    // Esperamos que o multiconta.php retorne alguma coisa
    $resultado = include $dir . "/admins/multiconta/lista.php";
    if(!function_exists('listaItens')){
        return ["erro"=>true, "mensagem"=>"A função listaItens não existe"];
    }
    
    return listaItens($this->conn, $formulario);
}
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        switch($this->acao){
            case 'tipos':
                return $this->tipos();
                break;
            case 'lista':
                return $this->lista();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é válida"];
                break;
        }
    }
    
    function close(){
        $this->conn->close();
    }
}

$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>