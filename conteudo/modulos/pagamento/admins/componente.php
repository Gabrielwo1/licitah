<?
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/config.php";
include __DIR__."/../../../../admin/configmodulo.php";
include __DIR__."/../../../../admin/geraId.php";


configModulo("pagamento");


class Render{
    public $pagina;
    function __construct(){
        $this->pagina = $_POST["pagina"] ?? false;
    }
    
    function minificarPHP($php) {
  $search = array(
    '/\>[^\S ]+/s',
    '/[^\S ]+\</s',
    '/(\s)+/s'
  );

  $replace = array('>', '<', '\\1');
  $minificado = preg_replace($search, $replace, $php);
  return $minificado;
}
    
    function render(){
        if(!$this->pagina){
            return ["erro"=>true, "mensagem"=>"Não foi definida uma página válida"];
        }
        $caminho = __DIR__."/../views/componentes/".$this->pagina.".php";
       if(file_exists($caminho)){
            ob_start();
            include($caminho);
            return ["sucesso"=>true, "html"=>$this->minificarPHP(ob_get_clean())];
       }else{
           return ["erro"=>true, "mensagem"=>"Não foi definida uma página válida"];
       }
    }
}

$render = new Render();
echo json_encode($render->render(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>