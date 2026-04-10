<?php
include __DIR__."/seguranca-include.php";

$cssItem = false;
$htmlItem = false;

if(defined("ISWILDCARD")){
    if(is_dir(__DIR__."/../conteudo/wildcard/setup/".$dominio->hash."/sistema")){
        $resposta = scandir(__DIR__."/../conteudo/wildcard/setup/".$dominio->hash."/sistema");  
    }else{
        $resposta = [];
    }
    $caminho = "conteudo/wildcard/setup/".$dominio->hash."/sistema/";
}else{
    $resposta = scandir(__DIR__."/../conteudo/assets/sistema");
    $caminho = "conteudo/assets/sistema/";
}

foreach($resposta as $r){
    if($r != "." && $r != ".."){
        $cssItem = $caminho.$r;
        break;
    }
}

if(!$cssItem && isset($wildcard) && $wildcard){
    $tento = scandir(__DIR__."/../conteudo/assets/sistema");
    
    foreach($tento as $r){
        if($r != "." && $r != ".."){
            $cssItem = "conteudo/assets/sistema/".$r;
            break;
        }
    }
}

if(file_exists(__DIR__."/../includes/sistema/speed.php")){
    ob_start();
    include(__DIR__."/../includes/sistema/speed.php");
    $htmlItem = ob_get_clean();
    
    // Limpar e normalizar o HTML
    if($htmlItem){
        // Remove quebras de linha desnecessárias e espaços em excesso
        $htmlItem = trim($htmlItem);
        
        // Remove \r\n e substitui por espaços simples onde necessário
        $htmlItem = preg_replace('/\r\n\s*/', ' ', $htmlItem);
        
        // Remove múltiplos espaços
        $htmlItem = preg_replace('/\s+/', ' ', $htmlItem);
        
        // Remove espaços antes e depois de tags
        $htmlItem = preg_replace('/>\s+</', '><', $htmlItem);
    }
}

// Definir header para JSON
header('Content-Type: application/json; charset=utf-8');

// Criar resposta
$resposta = [
    "sucesso" => true,
    "css" => $cssItem,
    "html" => $htmlItem
];

// Gerar JSON com flags apropriadas
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT | JSON_HEX_APOS);
?>