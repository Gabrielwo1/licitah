<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


include __DIR__."/../../../../admin/plugins.php";
include __DIR__."/../../../../admin/config.php";



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

class Swith{
    private $name;
    private $descricao;
    private $titulo;
    
    function __construct($configs = []){
        foreach($configs as $chave=>$item){
            $this->$chave = $item;
        }
    }
    
    function html(){
        $id = geraId();
        return '<div class="d-flex justify-content-between align-items-center my-4">
    <div class="w-50">
        <h3 class="fs-18 fw-500">'.$this->titulo.'</h3>
        <p class="fs-14">'.$this->descricao.'</p>
    </div>
    <div>
    <input  type="checkbox" class="interruptor setupConfig" id="'.$id.'" name="'.$this->name.'">
    <label for="'.$id.'" class="shadow">
        <div class="row  m-0 p-0 position-absolute start-0 top-0 w-100 h-100" style="z-index:3">
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <span><i class="bi bi-x-lg text-light"></i></span>
                    <span class="fs-12 text-white text-uppercase">OFF</span>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <span><i class="bi bi-check2 text-light"></i></span>
                    <span class="fs-12 text-white text-uppercase">ON</span>
                </div>
                
            </div>
        </div>
    </label>
</div>
</div>';
    }  
}

function titularizador($texto){
    return '<div class="d-flex justify-content-between align-items-center gap-4">
        <h2 class="text-contrast fs-16 text-uppercase m-0">'.$texto.'</h2>
        <div style="height: 2px ;" class="flex-fill bg-white"></div>
    </div>';
}

function geraId(){
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $uniqueID = '';

    for ($i = 0; $i < 10; $i++) {
        $uniqueID .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $uniqueID;
}


class Config{
    private $conn;
    private $acao;
    private $pasta;
    private $arquivo;
    private $configs;
    
    function __construct(){
        $this->conn = conn();
        $this->acao = isset($_POST["acao"]) ? $_POST["acao"] : false;
        $this->pasta = isset($_POST["pasta"]) ? $_POST["pasta"] : false;
        $this->arquivo = isset($_POST["arquivo"]) ? $_POST["arquivo"] : false;
        $this->configs = isset($_POST["configs"]) ? json_decode($_POST["configs"], true) : false;
    }
    
    
    function html(){
        if($this->pasta && $this->arquivo){
            $pasta = $this->pasta;
            $arquivo = $this->arquivo;
            
            $dir = __DIR__."/../tabs/".$pasta."/".$arquivo.".php";
            if(file_exists($dir)){
                ob_start();
                include($dir);
                return minificarPHP(ob_get_clean());
            }else{
               return ["erro"=> true, "mensagem"=>"Arquivo $pasta/$arquivo não existe"]; 
            }
        }else{
            return ["erro"=> true, "mensagem"=>"Faltam o caminho de consulta"];
        }
    }
    
    function salva(){
        if($this->configs && $this->arquivo && $this->pasta){
            $infos = $this->configs;
            $arquivo = $this->arquivo;
            $pasta = $this->pasta;
            $conn = $this->conn;
            $resposta = [];
            foreach($infos as $chave=>$valor){
                $seleciona = "SELECT * FROM  configuracoes WHERE config_pasta='$pasta'	AND config_arquivo='$arquivo'	&& config_chave='$chave'";
                $resultado = $conn->query($seleciona);
                
                if($resultado->num_rows == 0){
                    $acao = "INSERT INTO configuracoes (config_pasta,config_arquivo,config_chave,config_valor) VALUES ('$pasta', '$arquivo', '$chave', '$valor')";
                }else{
                    $acao = "UPDATE configuracoes SET config_valor='$valor' WHERE config_pasta='$pasta'	AND config_arquivo='$arquivo'	&& config_chave='$chave'";
                }
                if($conn->query($acao) == true){
                    array_push($resposta, 1);
                }else{
                     array_push($resposta, 0);
                }
            }
            
            if(isset($_POST["imagens"]) && $_POST["imagens"] != "false"){
                $array = json_decode($_POST["imagens"], true);
                foreach($array as $item){
                    $chave = "imagem_".$item["id"];
                    $valor = json_encode($item["srcs"]);
                    
                     $seleciona = "SELECT * FROM  configuracoes WHERE config_pasta='$pasta'	AND config_arquivo='$arquivo'	&& config_chave='$chave'";
                $resultado = $conn->query($seleciona);
                
                if($resultado->num_rows == 0){
                    $acao = "INSERT INTO configuracoes (config_pasta,config_arquivo,config_chave,config_valor) VALUES ('$pasta', '$arquivo', '$chave', '$valor')";
                }else{
                    $acao = "UPDATE configuracoes SET config_valor='$valor' WHERE config_pasta='$pasta'	AND config_arquivo='$arquivo'	&& config_chave='$chave'";
                }
                if($conn->query($acao) == true){
                    array_push($resposta, 1);
                }else{
                     array_push($resposta, 0);
                }
                    
                    
                }
            }
            
            return $resposta;
        }else{
             return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessarios"];
        }
    }
    
    function recupera(){
        if($this->pasta && $this->arquivo){
            $arquivo = $this->arquivo;
            $pasta = $this->pasta;
            $conn = $this->conn;
            
            $resposta = [];
            $seleciona = "SELECT * FROM configuracoes WHERE config_pasta='$pasta'	AND config_arquivo='$arquivo'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows > 0){
                while($dado = $resultado->fetch_assoc()){
                    $resposta[$dado["config_chave"]] = $dado["config_valor"];
                }
            }
            return $resposta;
        }else{
             return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessarios"];
        }
    }
    
    
    function info(){
        if($this->acao && isset($_SESSION["id"])){
            switch($this->acao){
                case 'blocohtml':
                    return $this->html();
                    break;
                case 'salvainfo':
                    return $this->salva();
                    break;
                case 'recupera':
                    return $this->recupera();
                    break;
            }
            return ["erro"=>true, "mensagem"=>"Nenhuma ação válida encontrada"];

        }else{
            return ["erro"=>true, "mensagem"=>"Nenhuma ação definida"];
        }
    }
}

$resposta = [];

$config = new Config();
$resposta["retorno"] = $config->info();


echo json_encode($resposta);

?>