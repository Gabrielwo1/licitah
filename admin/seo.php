<?
class SetSeo{
    public $t;
    public $d;
    public $p;
    
    function __construct($titulo = false, $descricao = false, $palavras = false){
        $this->t = $titulo;
        $this->d = $descricao;
        $this->p = $palavras;
    }
    
    function titulo($t){
        $this->t = $t;
    }
    
    function descricao($d){
        $this->d = $d;
    }
    
    function palavras($p){
        $this->p = $p;
    }
    
    function render(){
        
        $titulo = $this->t ? 'data-titulo="'.$this->t.'"' : "";
        $descricao = $this->d ? 'data-descricao="'.$this->d.'"' : "";
        $palavras = $this->p ? 'data-palavras="'.$this->p.'"' : "";
        echo '<div id="setSeo" '.$titulo.' '.$descricao.' '.$palavras.'></div>';
    }
}


?>