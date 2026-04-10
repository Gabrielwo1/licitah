<?

include __DIR__."/config.php";

class Acao{
    public $conn;
    public $politica;
    function __construct(){
        $this->politica = $_POST["politica"] ?? false;
        $this->conn = conn();
    }
    
    function pega($id) {
    $conn = $this->conn;
    $seleciona = "SELECT * FROM politicas WHERE politica_id='$id'";
    
    $resultado = $conn->query($seleciona); 

    if ($resultado === false || $resultado->num_rows == 0) {
        return false;
    }


    $dado = $resultado->fetch_assoc();

    return $dado["politica_texto"];
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
        if(!$this->politica){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma politica válida"];
        }


        if($this->politica == "lgpd"){
            $pasta = "geral";
        }else{
            $pasta = "politicas";
        }
        
        
        
        $empresa = '<span class="fw-700 fs-20">'.v(["geral", "empresa", "nome"], false).'</span>';
        $nomeEmpresa = v(["geral", "empresa", "nome"], "");
        $emailEmpresa = v(["geral", "empresa", "email"], "");
        $personalizada = false;
        if(v([$pasta ,$this->politica, "ativar"], false)){

            $texto = "";
            $termo = $this->politica;
            
            if(v([$pasta, $termo, "personalizar"], false) && v([ $pasta , $termo, "personalizada"], false)){
            $personalizada = $this->pega(v([$pasta , $termo, "personalizada"], false));
            

           
        }
        
               if(!$personalizada){
            if(file_exists(__DIR__."/../includes/sistema/politicas/".$termo.".php")){
                ob_start();
                include(__DIR__."/../includes/sistema/politicas/".$termo.".php");
                $personalizada = ob_get_clean(); 
            }
            }
            
             return ["sucesso"=>true, "texto"=>$this->minificarPHP($personalizada)];
            
            
            
        }else{
            return ["erro"=>true, "mensagem"=>"A politica enviada não está ativa ou válida"];
        }
        
        return ["erro"=>true, "mensagem"=>"Nada foi encontrado"];
        
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>