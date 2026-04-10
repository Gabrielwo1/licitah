<?
include __DIR__."/seguranca-include.php";

include_once __DIR__.'/conn.php';

class Config{
    public $conn;
    public $config;
    function __construct(){
      $this->conn = conn();   
    }
    
    function pegaModulos($caminho, $titulo){
        $array = [];
        
        if(!is_dir(__DIR__."/../".$caminho)){
            return $array;
        }
        
        
        $diretorio = __DIR__;
        
        $modulos = scandir($diretorio."/../".$caminho);
        foreach($modulos as $item){
            if($item != "." && $item != ".." && is_dir($diretorio."/../".$caminho."/".$item)){
                    $modulo = $diretorio."/../".$caminho."/".$item;
                    if(file_exists($modulo."/manifest.json")){
                        $conteudo = file_get_contents($modulo."/manifest.json");
                        
                        
                        if(!empty($conteudo) && json_decode($conteudo, true)){
                            $a = json_decode($conteudo, true);
                            
                            
                            $filhos = [];
                            if(isset($a["sub"])){
                                foreach($a["sub"] as $f){
                                    $filho = [
                                        "nome"=> isset($f["name"]) ? $f["name"] : "",
                                        "link"=> isset($f["link"]) ? $f["link"] : "",
                                        ];
                                    array_push($filhos, $filho);
                                }
                                
                            }
                            
                               $menu = [
                               "nome"=> isset($a["name"]) ? $a["name"] : "",
                               "link"=> isset($a["link"]) ? $a["link"] : false,
                               "icone"=> isset($a["icon"]) ? $a["icon"] : false,
                               "tipo"=> isset($a["tipo"]) ? $a["tipo"] : 1,
                               "filhos"=> $filhos
                            ];
                            
                        
                            
                            
                            if(isset($a["ativo"]) && $a["ativo"] == "true" && empty($a["readonly"])){
                                array_push($array, $menu);  
                            }
                        }
                    }
               
                }
        }
            
        if(count($array) > 0){
                   array_unshift($array,  [
                               "nome"=> $titulo,
                               "icone"=> false,
                               "tipo"=> 2,
                            ]);
            }
        return $array;
    }
    
    function listaMods(){
        $dir = __DIR__."/../conteudo/modulos/";
        $lista = scandir($dir);
        $mods = [];
        foreach($lista as $item){
            if($item != "." && $item != ".." && is_dir($dir.$item)){
                if(file_exists($dir.$item."/manifest.json")){
                    $conteudo = json_decode(file_get_contents($dir.$item."/manifest.json"), true);
                    if(isset($conteudo["ativo"]) && $conteudo["ativo"] == "true"){
                        $mods[md5($item)] = $item;
                    }
                }
                
            }
        }
        return $mods;
    }
    
    function setup(){

        if(defined("ISWILDCARD")){

            return SETUPWILDCARD;
            
        }
        
        
        
        
        $array = [];
        if(file_exists(__DIR__."/../conteudo/setup.json")){
 
            $conteudo = file_get_contents(__DIR__."/../conteudo/setup.json");
            $array = json_decode($conteudo, true);
        }
        return $array;
    }
    
    function dominio(){
        $domain = $_SERVER['HTTP_HOST'];
        $url ="https://" . $domain;
        return $url;
    }
    
    function idiomas(){
        return $idiomas = [
            'en' => ["nome" => "Inglês", "cor" => "#BD3D44"],
            'es' => ["nome" => "Espanhol", "cor" => "#F1BF00"],
            'fr' => ["nome" => "Francês", "cor" => "#CE1126"],
            'de' => ["nome" => "Alemão", "cor" => "#000000"],
            'cn' => ["nome" => "Chinês (Mandarim)", "cor" => "#EE1C25"],
            'ar' => ["nome" => "Árabe", "cor" => "#165d31"],
            'ru' => ["nome" => "Russo", "cor" => "#ffffff"],
            'pt-BR' => ["nome" => "Português", "cor" => "#229E45"],
            'ja' => ["nome" => "Japonês", "cor" => "#BC002D"],
            'hi' => ["nome" => "Hindi", "cor" => "#FF9933"],
            'bn' => ["nome" => "Bengali", "cor" => "#006A4E"],
            'ur' => ["nome" => "Urdu", "cor" => "#0c590b"],
            'kr' => ["nome" => "Coreano", "cor" => "#cd2e3a"],
            'it' => ["nome" => "Italiano", "cor" => "#009246"],
            'nl' => ["nome" => "Holandês", "cor" => "#21468b"],
            'el' => ["nome" => "Grego", "cor" => "#0d5eaf"],
            'tr' => ["nome" => "Turco", "cor" => "#e30a17"],
            'sv' => ["nome" => "Sueco", "cor" => "#086eab"],
            'no' => ["nome" => "Norueguês", "cor" => "#ed2939"],
            'dk' => ["nome" => "Dinamarquês", "cor" => "#c2102d"],
            'fi' => ["nome" => "Finlandês", "cor" => "#c2102d"],
            'pl' => ["nome" => "Polonês", "cor" => "#dc143c"],
            'th' => ["nome" => "Tailandês", "cor" => "#a51931"],
            'he' => ["nome" => "Hebraico", "cor" => "#0038b8"],
            'id' => ["nome" => "Indonésio", "cor" => "#ffffff"],
        ];

    }
    
    function v($array, $alt = ""){
        $caminho = $this->config;
        
        $ultimo = count($array) - 1;
        $i = 0;
        foreach($array as $item){
            if(isset($caminho[$item])){
                if($ultimo == $i){
                    return $caminho[$item];
                }
                $caminho = $caminho[$item];
            }else{
                return $alt;
            }
            $i++;
        }
        
        return $alt;
}
    
    function pegaMenu($menu = false){
    switch($menu){
        case 'topo':
            $banco = "menutopo";
            break;
        case 'lateral':
            $banco = "menulateral";
            break;
        case 'rodape':
            $banco = "menufooter";
            break; 
        default:
            return [];
            break;
    }
    

    
    if(isset($_SESSION["id"])){
        $fluxo = "logadas";
    }else{
         $fluxo = "deslogadas";

    }
    

    if(!$this->v(["paginas", $fluxo, $menu], false)){
        return [];
    }
    
    $id = $this->v(["paginas", $fluxo, $banco], 0);

    if($id == 0){
        return [];
    }
    $conn = $this->conn;
    $seleciona = "SELECT * FROM menus WHERE menu_id='$id'";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return [];
    }
    
    $dado = $resultado->fetch_assoc();
    if(!$dado["menu_estrutura"]){
        return [];
    }
    
    return json_decode($dado["menu_estrutura"], true);
}

    function userInfo(){
        
        $chaves = ["id", "nome","email","funcao", "ativo","sessao_hash", "tipouser", "user"];

        $autorizado = true;
        $infos = [];
        

        

        foreach($chaves as $item){
            if(!isset($_SESSION[$item])){
                $autorizado = false;
                break;
            }else{
                 $infos[$item] = $_SESSION[$item];
            }
        }
        
        $infos["foto"] = $_SESSION["foto"] ?? false;
        $infos["capa"] = $_SESSION["capa"] ?? false;
        
     
        $resposta = [];
        $resposta["autorizado"] = $autorizado;
        $autorizado ? $resposta["usuario"] = $infos : "";
        return $resposta;

    }
    
    function pegaAssets(){
        $listaCss = [];
        $listaJs = [];
        
     
        $dir = __DIR__."/../conteudo/assets/css";
        $wildcard = false;
        if(defined("ISWILDCARD")){
            $hash = HASHWILDCARD;
            $wildcard = $hash;
            $dir = __DIR__."/../conteudo/assets/wildcard/".$hash."/sistema";
            if(is_dir($dir)){
             $css = scandir($dir);
             foreach($css as $item){
                 if($item != "." && $item != ".."){
                     array_push($listaCss, "sistema/".$item);
                 }
             }
            }
            
               $dir = __DIR__."/../conteudo/assets/wildcard/".$hash."/css";
        }
        
     
        
        
        if(is_dir($dir)){
             $css = scandir($dir);
             foreach($css as $item){
                 if($item != "." && $item != ".."){
                     array_push($listaCss, $item);
                 }
             }
        }
        
        
        
        
        
        $dir = __DIR__."/../conteudo/assets/js";
        if(defined("ISWILDCARD")){
            $dir = __DIR__."/../conteudo/assets/wildcard/".$hash."/js";
        }
        
        
         if(is_dir($dir)){
             $js = scandir($dir);
             foreach($js as $item){
                 if($item != "." && $item != ".."){
                     array_push($listaJs , $item);
                 }
             }
        }
       
        
        

        
        return ["css"=>$listaCss, "js"=>$listaJs, "wildcard"=>$wildcard];

    }
    
    function subs(){

        if(!isset($_SESSION["sub"])){
            return false;
        }
        
        $id = $_SESSION["sub"];
        
        if(empty($_SESSION["subtipo"])){
            return false;
        }
   
        if(!file_exists(__DIR__."/../conteudo/modulos/".$_SESSION["subtipo"]."/admins/multiconta/conta.php")){
            return false;
        }


        
        include __DIR__."/../conteudo/modulos/".$_SESSION["subtipo"]."/admins/multiconta/conta.php";
        
        
        return pegaConta($this->conn, $id);
        
        
   
    }
    
    function pegaWidget(){
                $pasta = __DIR__."/../conteudo/modulos/";
        $modulos = scandir($pasta);
        $caminho = [];
        foreach($modulos as $modulo){
        if($modulo != "." && $modulo != ".." && is_dir($pasta.$modulo)){
        if(file_exists($pasta.$modulo."/manifest.json")){
            $manifesto = file_get_contents($pasta.$modulo."/manifest.json");
            $conteudo = json_decode($manifesto, true);
            if(isset($conteudo["ativo"]) && $conteudo["ativo"] === "true" && file_exists($pasta.$modulo."/assets/widgets.js")){
                $caminho[] = $modulo;
            }
        }
    }}
        return $caminho;
    }
    
    function render(){
        $resposta = [];
        $this->config = $this->setup();
        $resposta["config"] = $this->config;

        $reposta["dominio"] = $this->dominio();
        
        $menus = [];
        $menus["lateral"] = $this->pegaMenu("lateral");
        $menus["topo"] = $this->pegaMenu("topo");
        $menus["footer"] = $this->pegaMenu("rodape");
        
        
        
        
        if(isset($_SESSION["id"])){
  
            if(!defined('ISWILDCARD')){
   
                switch(intval($_SESSION["funcao"])){
                  case 0:
                     $modulos = $this->pegaModulos("conteudo/modulos", "MÓDULOS");
                     if(count($modulos) > 0){
                         $menus["lateral"]  =  array_merge($menus["lateral"]  , $modulos);
                     }
                     $master = $array = $this->pegaModulos("master/modulos", "ADMINISTRAÇÃO");
                     if(count($master) > 0){
                         $menus["lateral"]  =  array_merge($menus["lateral"]  , $master);
                     }
                     break;
                 case 1:
                     $modulos = $this->pegaModulos("conteudo/modulos", "MÓDULOS");
                     if(count($modulos) > 0){
                         $menus["lateral"]  =  array_merge($menus["lateral"]  , $modulos);
                     }
                     break;
              }
            }

            
              
        }
        
        $resposta["menus"] = $menus;
        
        $resposta["assets"] = $this->pegaAssets();
        
        
        $resposta["sub"] = $this->subs();
        
        
        $resposta["usuario"] = $this->userInfo();
        
        
        $resposta["widgets"] = $this->pegaWidget();
        $resposta["sucesso"] = true;

        
        $resposta["mods"] = $this->listaMods(); 
        return $resposta;
    }
}

$resposta = [];

$acao = new Config();
$resposta = $acao->render();


echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>