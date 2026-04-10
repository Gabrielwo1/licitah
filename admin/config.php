<?

include __DIR__."/ver.php";


function pegaMenuId($id = false){

    if($id == false){
        return [];
    }
    $conn = conn();
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

function pegaMenu($menu = false){
    switch($menu){
        case 'topo':
            $banco = "menuTopo";
            break;
        case 'lateral':
            $banco = "menuLateral";
            break;
        case 'rodape':
            $banco = "menuFooter";
            break; 
        default:
            return [];
            break;
    }
    
    if(LOGIN){
        $id = v(["paginas", "logado", $banco], 0);
    }else{
        $id = v(["paginas", "deslogados", $banco], 0);
    }
    
    if($id == 0){
        return [];
    }
    $conn = conn();
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

function getDomain($force = false) {
    if(defined("ISWILDCARD")){
        return "https://".WILDCARD;
    }

    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $url = "https://" . $domain;
    return $url;
}

$array = [];

include_once __DIR__."/conn.php";


$dominioMaster = false;
if(file_exists(__DIR__."/../conteudo/setup.json")){
    $conteudo = file_get_contents(__DIR__."/../conteudo/setup.json");
    $array = json_decode($conteudo, true);
    }
    


$conn = conn();

$configuracoes = $array;


function displayerror($erro){
    if($erro){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
    }
}

$setup = array(
    "dominio"=>getDomain()."/",
    "dominioJs"=>getDomain(true),
    "developerMode"=>true,
    "consoleErro"=>true,
    "config"=>$configuracoes    
    );

displayerror(true);

define("SETUP", $setup);


?>