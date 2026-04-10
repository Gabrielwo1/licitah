<?
    include __DIR__.'/../montador.php';
    $montador = new Montador(basename(dirname(__FILE__)));
    
    
    if(isset($this->caminho[1])){
        $montador->componentes('pagina', 'item');
    }
    else{
        $montador->componentes('pagina', 'home');
    }
    

?>