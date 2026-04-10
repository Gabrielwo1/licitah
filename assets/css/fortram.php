<?
header('Content-Type: text/css; charset=utf-8');

class Gera {
    function __construct($nome, $repeticoes, $incremento, $propriedades, $scala) {
        $this->nome = $nome;
        $this->repeticoes = $repeticoes + 1;
        $this->incremento = $incremento;
        $this->propriedades = $propriedades;
        $this->scala = $scala;
        
        $this->size(false, false, false);
        $this->size("min-width: 576px", "sm-");
        $this->size("min-width: 768px", "md-");
        $this->size("min-width: 992px", "lg-");
        $this->size("min-width: 1200px", "xl-");
    }
    
    function size($prop, $s, $important = "!important") {
        if ($this->repeticoes == 1) {
            $s = str_replace("-", "", $s);
        }
        
        $important = $important ? $important : "";
        
        if ($prop && $s) {
            echo '@media ('.$prop.') {';
        }
        
        $i = 0;
      
        while ($i < $this->repeticoes) {
            $calc = $i * $this->incremento;
            
            if ($this->repeticoes > 1) {
                echo ".".$this->nome."-".$s.$calc." {";
                
                // Loop para gerar as propriedades definidas
                foreach ($this->propriedades as $propriedade) {
                    echo $propriedade.": $calc$this->scala $important;";
                }
                
                echo "}";
            } else {
                echo ".".$this->nome."-".$s." {";
                
                // Loop para gerar as propriedades definidas
                foreach ($this->propriedades as $propriedade) {
                    echo $propriedade.": $this->scala $important;";
                }
                
                echo "}";
            }
            
            $i++;
        }
        
        if ($prop && $s) {
            echo '}';
        }
    }
}


//Altura
new Gera("h", 100, 1, ["height"], "%");
new Gera("he", 200, 5, ["height"], "px");

//Largura
new Gera("w", 100, 1, ["width"], "%");
new Gera("wi", 200, 5, ["width"], "px");

//Fonte
new Gera("fs", 150, 1, ["font-size"], "px");
new Gera("fw", 9, 100, ["font-weight"], "");

//Espaçamento
new Gera("padding", 30, 5, ["padding"], "px");
new Gera("padding-top", 30, 5, ["padding-top"], "px");
new Gera("padding-bottom", 30, 5, ["padding-bottom"], "px");
new Gera("padding-start", 30, 5, ["padding-left"], "px");
new Gera("padding-end", 30, 5, ["padding-right"], "px");
new Gera("padding-y", 30, 5, ["padding-top", "padding-bottom"], "px");
new Gera("padding-x", 30, 5, ["padding-left", "padding-right"], "px");

//Margem
new Gera("margin", 30, 5, ["margin"], "px");
new Gera("margin-top", 30, 5, ["margin-top"], "px");
new Gera("margin-bottom", 30, 5, ["margin-bottom"], "px");
new Gera("margin-start", 30, 5, ["margin-left"], "px");
new Gera("margin-end", 30, 5, ["margin-right"], "px");
new Gera("margin-y", 30, 5, ["margin-top", "margin-bottom"], "px");
new Gera("margin-x", 30, 5, ["margin-left", "margin-right"], "px");

//Position
new Gera("p-absolute", 0, 0, ["position"], "absolute");
new Gera("p-relative", 0, 0, ["position"], "relative");
new Gera("p-fixed", 0, 0, ["position"], "fixed");
new Gera("p-static", 0, 0, ["position"], "static");
new Gera("top", 100, 1, ["top"], "%");
new Gera("bottom", 100, 1, ["bottom"], "%");
new Gera("start", 100, 1, ["left"], "%");
new Gera("end", 100, 1, ["right"], "%");
?>
