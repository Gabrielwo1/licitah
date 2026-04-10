<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


include __DIR__."/../../../../admin/plugins.php";
include __DIR__."/../../../../admin/conn.php";

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
                    <span class="fs-12 text-white text-uppercase">Desativado</span>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <span><i class="bi bi-check2 text-light"></i></span>
                    <span class="fs-12 text-white text-uppercase">Ativado</span>
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
        private $configs;
    
    function __construct(){
        $this->conn = conn();
        $this->acao = isset($_POST["acao"]) ? $_POST["acao"] : false;
        $this->pasta = isset($_POST["pasta"]) ? $_POST["pasta"] : false;
        $this->configs = isset($_POST["configs"]) ? json_decode($_POST["configs"], true) : false;
    }
    
    function html(){
        if($this->pasta){
            $pasta = $this->pasta;

            
            $dir = __DIR__."/../tabs/".$pasta.".php";
            if(file_exists($dir)){
                ob_start();
                include($dir);
                return minificarPHP(ob_get_clean());
            }else{
               return ["erro"=> true, "mensagem"=>"Arquivo $pasta não existe"]; 
            }
        }else{
            return ["erro"=> true, "mensagem"=>"Faltam o caminho de consulta"];
        }
    }
    
    function baseInfo(){
        
        $conn = $this->conn;
        $id = $_SESSION["id"];
        
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id'";
        $resultado = $conn->query($seleciona);
        $retorno = [];
        if($resultado->num_rows > 0){
           while($dado = $resultado->fetch_assoc()){
               $retorno[$dado["um_chave"]] = $dado["um_valor"];
           }
            
         return $retorno;

       
        }else{
            return ["sucesso"=>true, "auth"=>false];
        }
        
    }
    
    function fotoPerfil(){
         $conn = $this->conn;
        if($_POST["chave"] == "false"){
            $id = $_SESSION["id"];
            $chave = "usuario_id";
        }else{
            $id = $_POST["chave"];
            $chave = "usuario_hash";
        }
        
        
        $seleciona = "SELECT usuario_id FROM usuarios WHERE $chave='$id'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $id = $dado["usuario_id"];
             
             $midia = $_POST["midia"];
             $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='imagem_foto'";
             $resultado = $conn->query($seleciona);
             if($resultado->num_rows == 1){
                 $acao = "UPDATE usuarios_meta SET um_valor='$midia' WHERE um_usuario='$id' AND um_chave='imagem_foto'";
             }else{
                $acao = "INSERT INTO usuarios_meta (um_chave, um_usuario, um_valor) VALUES ('imagem_foto', '$id', '$midia')";
             }
             
             $conn->query($acao);
              return ["sucesso"=>true, "retorno"=>"imagem atualizada"];
            
            return $dado;
        }else{
            return ["sucesso"=>true, "retorno"=>"usuario nao encontrado"];
        }
    }
    
    function pegafoto($id){
        $conn = $this->conn;
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='imagem_foto'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["um_valor"];
        }else{
            return false;
        }
    }
    
    function salva(){
        $id = $_SESSION["id"];
        $conn = $this->conn;
        if(isset($_POST["valores"])){
            $array = json_decode($_POST["valores"], true);
            foreach($array as $chave=>$valor){
                if(!empty($valor)){
                    
                    $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='$chave'";
                    $resultado = $conn->query($seleciona);
                    if($resultado->num_rows == 0){
                        $acao = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$id', '$chave', '$valor')";
                    }else{
                        $acao = "UPDATE usuarios_meta SET um_valor='$valor' WHERE um_usuario='$id' AND um_chave='$chave'";
                    }
                    
                    $conn->query($acao);

                }
            }
            return ["sucesso"=>true, "mensagem"=>"Dados Salvos com Sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Não fora enviados dados válidos"];
        }
    }
    
    
    
    function info(){
        if($this->acao && isset($_SESSION["id"])){
            switch($this->acao){
                case 'blocohtml':
                    return $this->html();
                    break;
                case 'baseInfo':
                    return $this->baseInfo();
                case 'imagemPerfil':
                    return $this->fotoPerfil();
                    break;
                case 'salvainfo':
                    return $this->salva();
                    break;
      
                case 'cadastra':
                    return $this->salva();
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