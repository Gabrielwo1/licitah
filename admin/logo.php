<?
$wild = false;
if($this->wildcard ?? false){
    $wild = true;
}


function construirCaminhoLogo($logo, $dominio) {
    return strpos($logo, $dominio) === 0 ? $logo : $dominio . "conteudo/uploads/" . $logo;
}

function trataLogo($array, $wild) {
    $trato = [];
    
    
    $dominio = SETUP["dominio"];
    
   




    foreach ($array as $chave => $item) {
        if ($item === null || $item === '') {
            $trato[$chave] = false;
        } else {
            $obj = json_decode($item, true);
            if ($obj === null && json_last_error() !== JSON_ERROR_NONE) {
                // Se não é um JSON válido, trata como um valor direto
                $trato[$chave] = $item;
            } elseif (is_array($obj) && count($obj) === 1) {
                // Se é um array com um elemento, verifica e ajusta o caminho do logo
                $trato[$chave] = construirCaminhoLogo($obj[0], $dominio);
      
            } else {
                $trato[$chave] = false;
            }
        }
    }
    return $trato;
}


$logo = [
    "logo" => v(["geral", "logotipo", "logo"], false),
    "logomarca" => v(["geral", "logotipo", "logomarca"], false),
    "logotipo" => v(["geral", "logotipo", "logotipo"], false)
];

$logo = trataLogo($logo, $wild);



if(!$wild){
    $light = [
    "logo"=>v(["logotipos","light","logo"], false),
    "logomarca"=>v(["logotipos","light","logomarca"], false),
    "logotipo"=>v(["logotipos","light","logotipo"], false)
];
$light = trataLogo($light, $wild);

$dark = [
    "logo"=>v(["logotipos","dark","logo"], false),
    "logomarca"=>v(["logotipos","dark","logomarca"], false),
    "logotipo"=>v(["logotipos","dark","logotipo"], false)
];
$dark = trataLogo($dark, $wild);
}else{
    $dark = [];
    $light = [];
}


function otimizaImg($url){
    $parts = explode('/', $url);
    array_pop($parts);
    $newUrl = implode('/', $parts);
    return $newUrl."/media.webp";
}

foreach($light as $chave=>$l){
    $light[$chave] =  otimizaImg($l);
}

foreach($dark as $chave=>$l){
    $dark[$chave] =  otimizaImg($l);
}



$logoLight = $light["logo"] ?? $logo["logo"]; 
$logoDark = $dark["logo"] ?? $logo["logo"];  





$logoMarcaLight = $light["logomarca"] ?? $logo["logomarca"]; 
$logoMarcaDark = $dark["logomarca"] ?? $logo["logomarca"]; 

$logoTipoLight = $light["logotipo"] ?? $logo["logotipo"]; 
$logoTipoDark = $dark["logotipo"] ?? $logo["logotipo"]; 


?>