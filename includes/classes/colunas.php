<?

class Colunas{
    private $colunas;
    function __construct($colunas = []){
        $this->colunas = $colunas;
    }
    
    function html(){
        $colunas = "";
        
 
        foreach($this->colunas as $col){
            $mobile = isset($col["sizes"][0]) && $col["sizes"][0] ? "col-".$col["sizes"][0] : "col-12";
            $tablet = isset($col["sizes"][1]) && $col["sizes"][1] ? "col-md-".$col["sizes"][1] : "col-md-12";
            $pc = isset($col["sizes"][2]) && $col["sizes"][2] ?"col-lg-".$col["sizes"][2] : "col-lg-12";
            $wide = isset($col["sizes"][3]) && $col["sizes"][3] ? "col-xl-".$col["sizes"][3] : "col-xl-12";
            
            $conteudo = "";
            foreach($col["conteudo"] as $content){
                $conteudo .= is_string($content) ? $content : $content->html();
            }
            
            $colunas .= '<div class="'.$mobile.' '.$tablet.' '.$pc.' '.$wide.'">
            '.$conteudo.'
            </div>';
            
        }
        
        
        return '
        <div class="row">
        '.$colunas.'
        </div>
        ';
    }
}




?>