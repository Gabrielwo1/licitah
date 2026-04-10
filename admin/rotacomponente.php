<?
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


class Acao{
    public $dir;
    public $caminho;
    public $arquivo;
    public $html;
    public $js;
    public $pagina;
    function __construct(){
        $this->dir = __DIR__."/../";
        $this->caminho = $_POST["caminho"] ?? false;
        $this->arquivo = $_POST["arquivo"] ?? false;
        $this->pagina = $_POST["pagina"] ?? false;
        
        
        $this->html = false;
        $this->js = false;
        

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


    function prepara(){
         if(!$this->caminho || !$this->arquivo){
            return;
        }
        

        switch($this->caminho){
            case 'sistema':
                $linha = $this->dir."includes/sistema/componentes/".$this->arquivo;
                break;
            case "modulo":
                $linha = $this->dir."conteudo/modulos/sistema/componentes/".$this->arquivo;
                break;
            case 'pagina':
                if(!$this->pagina){
                    return;
                }
                $linha = $this->dir."conteudo/paginas/".$this->pagina."/componentes/".$this->arquivo;
                break;
            default:
                 return;
                break;
        }

        
        if(is_dir($linha)){
            $resposta = [];

            if(file_exists($linha."/index.php")){
                 ob_start();
                 include($linha."/index.php");
                 $this->html = $this->minificarPHP(ob_get_clean());
            }
            
  
        }
        
        
        
    }
    
    function render(){
        $this->prepara();
        return ["sucesso"=>true, "html"=>$this->html, "js"=>$this->js];
    }
}


$acao = new Acao();
$resposta = $acao->render();

echo json_encode($resposta);
   

?>