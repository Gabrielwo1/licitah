<?
class Card{
    private $cabecalho;
    private $corpo;
    private $rodape;
    function __construct($configs = []){
        $this->cabecalho = false;
        $this->corpo = false;
        $this->rodape = false;
        
        foreach($configs as $chave=>$item){
            $this->$chave = $item;
        }
    }
    
    function head(){
        if($this->cabecalho){
            $conteudo = "";
            foreach($this->cabecalho as $item){
                $conteudo .= $item;
            }
            
            return '<div class="card-header d-flex justify-content-between align-items-center">'.$conteudo.'</div>';
        }
         return '';
    }
    
    function body(){
        if($this->corpo){
            $conteudo = "";
            foreach($this->corpo as $item){
                $conteudo .= is_string($item) ? $item : $item->html();
            }
            
            return '<div class="card-body">'.$conteudo.'</div>';
        }
       
    }
    
    function set($chave, $valor){
        $this->$chave = $valor;
    }
    
    function footer(){
        if($this->rodape){
            $conteudo = "";
            foreach($this->rodape as $item){
                $conteudo .= $item;
            }
            
            return '<div class="card-footer"></div>';
        }
         return '';
    }
    
    function html(){
        $head = $this->head();
        $body = $this->body();
        $footer = $this->footer();
        
        return '
        <div class="card mb-4 border-0 shadow">
        '.$head.$body.$footer.'
        </div>
        ';
    }
}
?>