<?

class Input{
        private $tipo;
        private $name;
        private $obrigatorio;
        private $placeholder;
        private $icone;
        private $iconeEsquerda;
        private $type;
        private $mask;
        private $valida;
        private $condicional;
        private $switch;
        private $label;
        private $descricao;
        private $opcoes;



    function __construct($tipo, $configs = []){
        $this->tipo = strtolower($tipo);
        $this->name = false;
        $this->obrigatorio = false;
        $this->placeholder = false;
        $this->icone = false;
        $this->iconeEsquerda = true;
        $this->type = "text";
        $this->mask = false;
        $this->valida = false;
        $this->condicional = false;
        $this->switch = false;

        
        foreach($configs as $chave=>$item){
            $this->$chave = $item;
        }
    }
    
    function set($chave, $valor){
        $this->$chave = $valor;
    }
    
    function texto(){
        $obrigatorio = isset($this->obrigatorio) && $this->obrigatorio ? "obg" : ""; 
        
        
        if(isset($this->label) && $this->label){
            $label = '<label class="text-contrast '.$obrigatorio.'">'.$this->label.'</label>';
            return $label;
        }else{
            return '';
        }
    }
    
    function small(){
        if(isset($this->descricao) && $this->descricao){
            $label = '<small class="text-contrast">'.$this->descricao.'</small>';
            return $label;
        }else{
            return '';
        }
    }
    
    function options(){
        if($this->opcoes){
            $conteudo = "";
            
            foreach($this->opcoes as $item){
                if(isset($item["chave"]) && isset($item["valor"])){
                    $desabilitado = isset($item["desabilitado"]) && $item["desabilitado"] ? "disabled" : "";
                    $selecionado = isset($item["selecionado"]) && $item["selecionado"] ? "selected" : "";
                    $conteudo .= '<option value="'.$item["valor"].'" '.$selecionado.' '.$desabilitado.'>'.$item["chave"].'</option>';
                }else{
                    $conteudo .= '<option value="'.$item.'">'.$item.'</option>';
                }
            }
            return $conteudo;
            
        }
    }
    
    function radioOptions(){
    if($this->opcoes){
        $conteudo = "";
        
        foreach($this->opcoes as $item){
            if(isset($item["chave"]) && isset($item["valor"])){
                $selecionado = isset($item["selecionado"]) && $item["selecionado"] ? "checked" : "";
                $conteudo .= '
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="'.$this->name.'" value="'.$item["valor"].'" '.$selecionado.'>
                    <label class="form-check-label">'.$item["chave"].'</label>
                </div>';
            }else{
                $conteudo .= '
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="'.$this->name.'" value="'.$item.'" '.$selecionado.'>
                    <label class="form-check-label">'.$item.'</label>
                </div>';
            }
        }
        return $conteudo;
    }
}


function checkboxOptions(){
    if($this->opcoes){
        $conteudo = "";
        
        foreach($this->opcoes as $item){
            $selecionado = isset($item["selecionado"]) && $item["selecionado"] ? "checked" : "";
            $switch = isset($this->switch) && $this->switch ? "form-switch" : "form-check";
            
            if(isset($item["chave"]) && isset($item["valor"])){
                $conteudo .= '
                <div class="'.$switch.'">
                    <input class="form-check-input" type="'.($this->switch ? "checkbox" : "checkbox").'" name="'.$this->name.'[]" value="'.$item["valor"].'" '.$selecionado.'>
                    <label class="form-check-label">'.$item["chave"].'</label>
                </div>';
            }else{
                $conteudo .= '
                <div class="'.$switch.'">
                    <input class="form-check-input" type="'.($this->switch ? "checkbox" : "checkbox").'" name="'.$this->name.'[]" value="'.$item.'" '.$selecionado.'>
                    <label class="form-check-label">'.$item.'</label>
                </div>';
            }
        }
        return $conteudo;
    }
}



    function html(){
        $label = $this->texto();
        $small = $this->small();
        $name = $this->name;
        $placeholder = isset($this->placeholder) && $this->placeholder ? 'placeholder="'.$this->placeholder.'"' : "";
        $obrigatorio = isset($this->obrigatorio) && $this->obrigatorio ? true : false;  
        $mascara = isset($this->mask) && $this->mask ? 'data-mask="'.$this->mask.'"' : '';
        $valida = isset($this->valida) && $this->valida ? 'data-valida="'.$this->valida.'"' : "";
        
        $padding = "";
        if(isset($this->icone) && $this->icone){
            if($this->iconeEsquerda){
                 $padding = "padding-left: 40px";
                 $icone = '<span class="position-absolute" style="left: 12px; top: 7px"><i class="'.$this->icone.'"></i><span>';
            }else{
                $padding = "padding-right: 40px";
                 $icone = '<span class="position-absolute" style="right: 12px; top: 7px"><i class="'.$this->icone.'"></i><span>';
            }
           
        }else{
            $icone = "";
        }
        
        if(!$this->condicional){
             switch($this->tipo){
            case 'input':
                return '
                <div class="mt-3 inputContainer">
                '.$label.'
                <div class="position-relative">
                <input class="form-control entrada" name="'.$name.'" data-obrigatorio="'.$obrigatorio.'" '.$placeholder.' type="'.$this->type.'" style="'.$padding.'" '.$mascara.' '.$valida.'>
                '.$icone.'
                </div>
                '.$small.'
                </div>
                ';
                break;
            case 'textarea':
                 return '
                <div class="mt-3 inputContainer">
                '.$label.'
                <textarea class="form-control entrada" name="'.$name.'" data-obrigatorio="'.$obrigatorio.'" '.$placeholder.' '.$valida.'></textarea>
                '.$small.'
                </div>
                ';
                break;
            case 'rich':
                return '
                <div class="mt-3 inputContainer">
                '.$label.'
                <div class="form-control entrada richInput" data-name="'.$name.'" data-obrigatorio="'.$obrigatorio.'" '.$placeholder.' '.$valida.' style="min-height: 300px"></div>
                '.$small.'
                </div>
                ';
                break;
            case 'select':
                $options = $this->options();
                $multiple = isset($this->multiple) && $this->multiple ? "multiple" : "";
                
                
                return '
                <div class="mt-3 inputContainer">
                '.$label.'
                <div class="position-relative">
                <select class="form-select entrada" name="'.$name.'" '.$multiple.' data-obrigatorio="'.$obrigatorio.'" style="'.$padding.'" '.$valida.'>
                '.$options.'
                </select>
                '.$icone.'
                </div>
                '.$small.'
                </div>
                ';
                break;
            case 'radio':
    $radioOptions = $this->radioOptions();
    return '
    <div class="mt-3 inputContainer">
    '.$label.'
    <div class="position-relative">
    '.$radioOptions.'
    </div>
    '.$small.'
    </div>
    ';
    break;
    case 'checkbox':
    $checkboxOptions = $this->checkboxOptions();
    return '
    <div class="mt-3 inputContainer">
    '.$label.'
    <div class="position-relative">
    '.$checkboxOptions.'
    </div>
    '.$small.'
    </div>
    ';
    break;



            default:
                return 'erro';
                break;
        }
        }else{
             $atributos = get_object_vars($this);
             $atributos = json_encode($atributos);
            return '<div class="condicional d-none">'.$atributos.'</div>';
        }
       
    }
}
?> 
