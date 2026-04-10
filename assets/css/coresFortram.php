<?
header("Content-type: text/css");

class NovaCor{
    function __construct($nome, $hexa, $rgb){
        $this->nome = strtolower(str_replace(' ', '',  $nome));
        $this->hexa = $hexa;
        $this->rgb = $rgb;
    }
    
    function render(){
        return '
        :root{
           --bs-'.$this->nome.': '.$this->hexa.';
           --bs-'.$this->nome.'-rgb: '.$this->rgb.';
           --bs-'.$this->nome.'-bg-subtle: rgba(var(--bs-'.$this->nome.'-rgb), 0.1);
           --bs-'.$this->nome.'-border-subtle: rgba(var(--bs-'.$this->nome.'-rgb), 0.5);
           --bs-'.$this->nome.'-text-emphasis: '.$this->hexa.'; 
        }
        
        .bg-'.$this->nome.' {
        background-color: var(--bs-'.$this->nome.') !important;
        }
        
        .text-'.$this->nome.' {
        color: var(--bs-'.$this->nome.') !important;
            
        }
        
        .bg-'.$this->nome.'-subtle, .bg-hover-'.$this->nome.'-subtle:hover {
            background-color: var(--bs-'.$this->nome.'-bg-subtle) !important;
        }
        .text-'.$this->nome.'-emphasis {
            color: var(--bs-'.$this->nome.'-text-emphasis) !important;
        }
        .border-'.$this->nome.'-subtle {
            border-color: var(--bs-'.$this->nome.'-border-subtle) !important;
        }

        ';
    }
}

function minifyCSS($css) {
    $css = preg_replace('/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css); // Remove comments
    $css = preg_replace('/\s+/', ' ', $css); // Remove unnecessary spaces
    $css = preg_replace('/\s?([:,;{}])\s?/', '$1', $css); // Remove spaces around delimiters
    return $css;
}



$cores = [
    ["nome"=>"Purple", "hexa"=>"#800080" , "rgb"=>"128, 0, 128"],
    ["nome"=>"Navy Blue", "hexa"=>"#000080" , "rgb"=>"0, 0, 128"],
    ["nome"=>"Light Blue", "hexa"=>"#ADD8E6" , "rgb"=>"173, 216, 230"],
    ["nome"=>"Navy Green", "hexa"=>"#355E3B" , "rgb"=>"53, 94, 59"],
    ["nome"=>"Light Green", "hexa"=>"#90EE90" , "rgb"=>"144, 238, 144"],
    ["nome"=>"Brown", "hexa"=>"#A52A2A" , "rgb"=>"65, 42, 42"],
    ["nome"=>"Aqua", "hexa"=>"#00FFFF" , "rgb"=>"0, 255, 255"],
    ["nome"=>"Indigo", "hexa"=>"#4b0082" , "rgb"=>"75, 0, 130"],
    ["nome"=>"Pink", "hexa"=>"#ff69b4" , "rgb"=>"255, 105, 180"],
    ["nome"=>"Orange", "hexa"=>"#ffa500" , "rgb"=>"255, 165, 0"],
    ["nome"=>"Dark Red", "hexa"=>"#C20000" , "rgb"=>"194, 0, 0"],
    ];

$minify = "";
foreach($cores as $item){
    $cor = new NovaCor($item["nome"], $item["hexa"], $item["rgb"]);
    $minify .= $cor->render();
}


echo minifyCSS($minify);
?>

