<?

function  gerarID(){
    $num = rand(1, 1000);
    return "div-".$num;
}

class Tab{
    function __construct($tabs, $configs = []){
        $this->tabs = $tabs;
        $this->topo = true;
        
        foreach($configs as $chave=>$valor){
            $this->$chave = $valor;
        }
    }
    
    function set($chave , $valor){
        $this->$chave = $valor;
    }
    
    function html(){
        $tabulacao = "";
        $conteudo = "";
        
        $start = true;
        
        $i = 0;
        foreach($this->tabs as $tab){
            $i++;
             $classe  = "";
             
             $id = gerarID();
             
            if($start){$classe = "active";$start = false;}
            
            $tabulacao .= '<button class="tab nav-link '.$classe.'" aria-current="page" data-target="'.$id.'">'.$tab["nome"].'</button>';
            
            
            $content = "";
            foreach($tab["conteudo"] as $c){
                $content .=  is_string($c)  ? $c : $c->html();
            }
            
            $conteudo .= '<div id="'.$id.'" class="nav-content '.$classe.'">'.$content.'</div>';
            
            
            
            
        }
        
        if($this->topo){
            $classes = ["col-12", "col-12", ""];
        }else{
            $classes = ["col-3", "col-9", "flex-column"];
        }
        
        return '
        <div class="row">
            <div class="'.$classes[0].'">
                <nav class="nav nav-pills nav-justified mb-4  '.$classes[2].' tabulacao">'.$tabulacao.'</nav>
            </div>
            <div class="'.$classes[1].'">
                <div class="navcontent">
                    '.$conteudo.'
                </div>
            </div>
        </div>
        
        ';
    }
}
?>