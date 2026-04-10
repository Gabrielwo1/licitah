<?
class Cabecalho{
    private $titulo;
    private $btns;
    private $exportar;
    function __construct($config){
        foreach($config as $chave=>$valor){
            $this->$chave = $valor;
        }
    }
    
    
function constantes($value) {
    $constants = get_defined_constants(true);

    foreach ($constants['user'] as $name => $val) {
        if ($name === $value) {
            return true;
        }
    }
    
    return false;
}


    function bread(){
        $url = SETUP["dominio"];
        $caminho = "";
        $paes =["teste", "teste"];
        foreach($paes as $item){
            $url = substr($url, -1) == "/" ? $url.$item : $url."/".$item;
            if($item != "a"){
                 $caminho .= '<li class="breadcrumb-item"><a href="'.$url.'" class="m-0 text-secondary fs-14 fw-500 text-decoration-none">'.$item.'</a></li>';
            }
           
        }
        
        

        return $caminho;
    }
    
    function botoes(){
        if(isset($this->btns)  && $this->btns){
            $btns = "";
            foreach($this->btns as $item){
                $id = isset($item["id"]) ? 'id="'.$item["id"].'"' : "";
                $string = json_encode($item);
                $texto = $item["texto"];
                $btns .= <<<HTML
                <button class="btn-cabecalho btn btn-contrast fs-12 fw-500" data-info='$string'  $id>$texto</button>
HTML;
            }
            return $btns;
        }
        return '';
    }
    
  
    
    function html(){
        $titulo = $this->constantes("EDITA") && isset($this->tituloEdita) ? $this->tituloEdita : $this->titulo;
        
        return '
        <div class="container">
        <div class="mb-4" id="cabecalhoAdmin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fs-16 text-uppercase m-0 fw-700 text-contrast">'.$titulo.'</h1>
                    <div>
                      <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">'.$this->bread().'</ol>
                     </nav>
                    </div>

                </div>
                <div>
                    <div class="d-flex justify-content-end gap-2">
                        '.$this->botoes().'
                    </div>
                </div>
            </div>
        </div></div>';
    }
}
?>