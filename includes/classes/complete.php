<?
function linhas($quantidade = 1, $tamanho = 16, $largura = 100){
    $render = '<div class="d-flex flex-column justify-content-start gap-2">';
    $i = 0;
    while($i < $quantidade){
        $render .= '<div class="bg-carregando" style="max-width: '.$largura.'%; height: '.$tamanho.'px"></div>';
        $i++;
    }
    
    $render .= '</div>';
    return $render;
}

function componente($modulo, $pasta, $modelo){
    if(file_exists(__DIR__."/../../conteudo/modulos/".$modulo."/public/includes/".$pasta."/".$modelo.".php")){
        include __DIR__."/../../conteudo/modulos/".$modulo."/public/includes/".$pasta."/".$modelo.".php";
    }else{
        echo "componente nao existe";
    }
}
?>