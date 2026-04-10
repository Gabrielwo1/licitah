<?php
session_start();

function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');
    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));
    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);
        // Garante que o valor está entre 0 e 255
        $color = max(0, min(255, $color + $adjustAmount));
        $color = str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }
    return '#' . implode('', $hexCode);
}

function hexToRgb($hex, $alpha = false) {
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   if ( $alpha ) {
      $rgb['a'] = $alpha;
   }
   return $rgb;
}

function isDark($hex){
    $average = 450; // range 1 - 765
    if(strlen(trim($hex)) == 4){
        $hex = "#" . substr($hex,1,1) . substr($hex,1,1) . substr($hex,2,1) . substr($hex,2,1) . substr($hex,3,1) . substr($hex,3,1);
    }
    return ((hexdec(substr($hex,1,2))+hexdec(substr($hex,3,2))+hexdec(substr($hex,5,2)) < $average) ? true : false);
}

function minifyCSS($css) {
    $css = preg_replace('/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css); // Remove comments
    $css = preg_replace('/\s+/', ' ', $css); // Remove unnecessary spaces
    $css = preg_replace('/\s?([:,;{}])\s?/', '$1', $css); // Remove spaces around delimiters
    return $css;
}

function numberToPx($number){
    if(preg_match("/^-?\d+$/", $number)){
        return $number."px";
    } else {
        return $number;
    }
}

class generateCss{
public $css;
public $config;
public $conn;
public $families;
public $formatImport;
public $importFonts;
public $name;
public $hex;
public $rgb;
public $lighter;
public $darker;
public $text_color;
public $selector;
public $sizes;
public $family;
public $sub;
public $weight;
public $align;
public $transform;
public $sm_size;
public $sm_lheight;
public $sm_wspace;
public $sm_lspace;
public $md_size;
public $md_lheight;
public $md_wspace;
public $md_lspace;
public $lg_size;
public $lg_lheight;
public $lg_wspace;
public $lg_lspace;
public $xl_size;
public $xl_lheight;
public $xl_wspace;
public $xl_lspace;
public $modes;
public $mode;
public $bgColor;
public $txtColor;
public $txtColorHover;
public $bgMenu;
public $bgMenuHover;
public $bgMenuActive;

    function __construct()
    {
        $this->css = '';
        $this->config = [];
        $this->conn = conn();
    }
    
    function test()
    {
        return "<div style='display: inline-block; padding: 30px; background-color: ".$this->hex."'></div>
        <div style='display: inline-block; padding: 30px; background-color: ".$this->lighter."'></div>
        <div style='display: inline-block; padding: 30px; background-color: ".$this->darker."'></div>";
    }
    
    function renderColor($name, $hex)
    {
        $this->name = strtolower(str_replace(' ', '',  $name));
        $this->hex = $hex;
        $this->rgb = hexToRgb($hex);
        $this->rgb = $this->rgb['r'].",".$this->rgb['g'].",".$this->rgb['b'];
        $this->lighter = adjustBrightness($hex, 0.7);
        $this->darker = adjustBrightness($hex, -0.2);
        $this->text_color = (isDark($this->darker) ? 'white' : 'black');
        
        return '
            :root{
               --nown-'.$this->name.': '.$this->hex.';
               --nown-'.$this->name.'-rgb: '.$this->rgb.';
               --nown-'.$this->name.'-lighter: '.$this->lighter.';
               --nown-'.$this->name.'-darker: '.$this->darker.';
               --nown-'.$this->name.'-text-over: '.$this->text_color.';
            }
            .text-'.$this->name.' {
                color: var(--nown-'.$this->name.');
            }
            .text-'.$this->name.'-lighter {
                color: var(--nown-'.$this->name.'-lighter);
            }
            .text-'.$this->name.'-darker {
                color: var(--nown-'.$this->name.'-darker);
            }
            .bg-'.$this->name.', .bg-hover-'.$this->name.':hover {
                background-color: var(--nown-'.$this->name.');
            }
            .bg-'.$this->name.'-lighter, .bg-hover-'.$this->name.'-lighter:hover {
                background-color: var(--nown-'.$this->name.'-lighter);
            }
            .bg-'.$this->name.'-darker, .bg-hover-'.$this->name.'-darker:hover {
                background-color: var(--nown-'.$this->name.'-darker);
            }
            .border-'.$this->name.' {
                border-color: var(--nown-'.$this->name.');
            }
            .border-'.$this->name.'-lighter {
                border-color: var(--nown-'.$this->name.'-lighter);
            }
            .border-'.$this->name.'-darker {
                border-color: var(--nown-'.$this->name.'-darker);
            }
            .btn-n-'.$this->name.' {
                --bs-btn-color: var(--nown-'.$this->name.'-text-over);
                --bs-btn-bg: var(--nown-'.$this->name.');
                --bs-btn-border-color: var(--nown-'.$this->name.');
                --bs-btn-border-radius: .5rem;
                --bs-btn-hover-color: '.$this->text_color.';
                --bs-btn-hover-bg: var(--nown-'.$this->name.'-darker);
                --bs-btn-hover-border-color: var(--nown-'.$this->name.'-darker);
                --bs-btn-active-color: '.$this->text_color.';
                --bs-btn-active-bg: var(--nown-'.$this->name.'-darker);
                --bs-btn-active-border-color: var(--nown-'.$this->name.'-darker);
            }
            .btn-n-'.$this->name.'-darker {
                --bs-btn-color: '.$this->text_color.';
                --bs-btn-bg: var(--nown-'.$this->name.'-darker);
                --bs-btn-border-color: var(--nown-'.$this->name.'-darker);
                --bs-btn-border-radius: .5rem;
                --bs-btn-hover-color: '.$this->text_color.';
                --bs-btn-hover-bg: var(--nown-'.$this->name.'-darker);
                --bs-btn-hover-border-color: var(--nown-'.$this->name.'-darker);
                --bs-btn-active-color: '.$this->text_color.';
                --bs-btn-active-bg: var(--nown-'.$this->name.'-darker);
                --bs-btn-active-border-color: var(--nown-'.$this->name.'-darker);
            }
        ';
    }
    
    function renderRequiredFonts($typographies)
    {
        $this->families = [];
        foreach($typographies as $typo){
            if(isset($typo["familia"]) && isset($typo["pesos"])){
                 $this->families[$typo["familia"]]['weight'][] = $typo["pesos"];
            }
          
        }
        // return $this->families;
        $this->formatImport = '';
        foreach($this->families as $font => $info){
            $weights = '';
            $info_w = $info['weight'];
            $info_w = array_unique($info_w);
            sort($info_w);
            foreach($info_w as $weight){
                if(!empty($weight)){
                    $weights .= $weight.';';
                }
            }
            $weights = rtrim($weights, ";");
            $font_group = 'family='.$font.':wght@'.$weights."&";
            $this->formatImport .= $font_group;
        }
        $this->formatImport = rtrim($this->formatImport, "&");
        $this->importFonts = "@import url('https://fonts.googleapis.com/css2?".$this->formatImport."&display=swap');";
        return $this->importFonts;
    }
    
    function renderTypography($selector, $set)
    {
        $this->selector = $selector;
        $this->sizes = json_decode($set['tamanhos'], true);
        
        $this->family = (isset($set['familia']) ? $set['familia'] : null);
        $this->sub = (isset($set['alinhamento']) ? $set['alinhamento'] : null);
        $this->weight = (isset($set['pesos']) ? $set['pesos'] : null);
        $this->align = (isset($set['alinhamento']) ? $set['alinhamento'] : null);
        $this->transform = (isset($set['transformacao']) ? $set['transformacao'] : null);
        
        // mobile
        $this->sm_size = (isset($this->sizes['Mobile']['font-size']) ? numberToPx($this->sizes['Mobile']['font-size']) : null);
        $this->sm_lheight = (isset($this->sizes['Mobile']['altura-linha']) ? $this->sizes['Mobile']['altura-linha'] : null);
        $this->sm_wspace = (isset($this->sizes['Mobile']['espaco-palavra']) ? numberToPx($this->sizes['Mobile']['espaco-palavra']) : null);
        $this->sm_lspace = (isset($this->sizes['Mobile']['espaco-letra']) ? numberToPx($this->sizes['Mobile']['espaco-letra']) : null);
        
        // tablet
        $this->md_size = (isset($this->sizes['Tablet']['font-size']) ? numberToPx($this->sizes['Tablet']['font-size']) : null);
        $this->md_lheight = (isset($this->sizes['Tablet']['altura-linha']) ? $this->sizes['Tablet']['altura-linha'] : null);
        $this->md_wspace = (isset($this->sizes['Tablet']['espaco-palavra']) ? numberToPx($this->sizes['Tablet']['espaco-palavra']) : null);
        $this->md_lspace = (isset($this->sizes['Tablet']['espaco-letra']) ? numberToPx($this->sizes['Tablet']['espaco-letra']) : null);
        
        // pc
        $this->lg_size = (isset($this->sizes['Pc']['font-size']) ? numberToPx($this->sizes['Pc']['font-size']) : null);
        $this->lg_lheight = (isset($this->sizes['Pc']['altura-linha']) ? $this->sizes['Pc']['altura-linha'] : null);
        $this->lg_wspace = (isset($this->sizes['Pc']['espaco-palavra']) ? numberToPx($this->sizes['Pc']['espaco-palavra']) : null);
        $this->lg_lspace = (isset($this->sizes['Pc']['espaco-letra']) ? numberToPx($this->sizes['Pc']['espaco-letra']) : null);
        
        // wide
        $this->xl_size = (isset($this->sizes['WideScreen']['font-size']) ? numberToPx($this->sizes['WideScreen']['font-size']) : null);
        $this->xl_lheight = (isset($this->sizes['WideScreen']['altura-linha']) ? $this->sizes['WideScreen']['altura-linha'] : null);
        $this->xl_wspace = (isset($this->sizes['WideScreen']['espaco-palavra']) ? numberToPx($this->sizes['WideScreen']['espaco-palavra']) : null);
        $this->xl_lspace = (isset($this->sizes['WideScreen']['espaco-letra']) ? numberToPx($this->sizes['WideScreen']['espaco-letra']) : null);
        
        return '
        :root{
            --nown-'.$this->selector.'-font: "'.$this->family.'";
        }
        '.$this->selector.' {
            '.(isset($this->family) ? 'font-family: "'.$this->family.'";' : '').'
            '.(isset($this->weight) ? 'font-weight: '.$this->weight.';' : '').'
            '.(isset($this->align) ? 'text-align: '.$this->align.';' : '').'
            '.(isset($this->transform) ? 'text-transform: '.$this->transform.';' : '').'
        }
        
        @media (max-width: 767px){
            '.$this->selector.' {
                '.(isset($this->sm_size) ? 'font-size: '.$this->sm_size.';' : '').'
                '.(isset($this->sm_lheight) ? 'line-height: '.$this->sm_lheight.';' : '').'
                '.(isset($this->sm_wspace) ? 'word-spacing: '.$this->sm_wspace.';' : '').'
                '.(isset($this->sm_lspace) ? 'letter-spacing: '.$this->sm_lspace.';' : '').'
            }
        }
        
        @media (min-width: 768px) and (max-width: 991px){
            '.$this->selector.' {
                '.(isset($this->md_size) ? 'font-size: '.$this->md_size.';' : '').'
                '.(isset($this->md_lheight) ? 'line-height: '.$this->md_lheight.';' : '').'
                '.(isset($this->md_wspace) ? 'word-spacing: '.$this->md_wspace.';' : '').'
                '.(isset($this->md_lspace) ? 'letter-spacing: '.$this->md_lspace.';' : '').'
            }
        }
        
        @media (min-width: 992px) and (max-width: 1399px){
            '.$this->selector.' {
                '.(isset($this->lg_size) ? 'font-size: '.$this->lg_size.';' : '').'
                '.(isset($this->lg_lheight) ? 'line-height: '.$this->lg_lheight.';' : '').'
                '.(isset($this->lg_wspace) ? 'word-spacing: '.$this->lg_wspace.';' : '').'
                '.(isset($this->lg_lspace) ? 'letter-spacing: '.$this->lg_lspace.';' : '').'
            }
        }
        
        @media (min-width: 1400px){
            '.$this->selector.' {
                '.(isset($this->xl_size) ? 'font-size: '.$this->xl_size.';' : '').'
                '.(isset($this->xl_lheight) ? 'line-height: '.$this->xl_lheight.';' : '').'
                '.(isset($this->xl_wspace) ? 'word-spacing: '.$this->xl_wspace.';' : '').'
                '.(isset($this->xl_lspace) ? 'letter-spacing: '.$this->xl_lspace.';' : '').'
            }
        }
        
        ';
    }
    
    function renderHeaderCss($mode, $options)
    {
        $this->modes = [
            "light-mode" => "light",
            "dark-mode" => "dark",
        ];
        $this->mode = $this->modes[$mode];
        
        $this->bgColor = ($options["customcolors"] == 'true' && !empty($options["backgroundcolor"]) ? $options["backgroundcolor"] : ($this->mode == 'light' ? 'white' : 'black') );
        $this->txtColor = ($options["customcolors"] == 'true' && !empty($options["cortexto"]) ? $options["cortexto"] : ($this->mode == 'light' ? '#111' : '#fff') );
        $this->txtColorHover = ($options["customcolors"] == 'true' && !empty($options["corhover"]) ? $options["corhover"] : ($this->mode == 'light' ? 'var(--nown-primaria-text-over)' : 'var(--nown-primaria-text-over)') );
        $this->bgMenu = ($options["customcolors"] == 'true' && !empty($options["backgroundmenu"]) ? $options["backgroundmenu"] : ($this->mode == 'light' ? '#f1f1fc' : '#333') );
        $this->bgMenuHover = ($options["customcolors"] == 'true' && !empty($options["hover"]) ? $options["hover"] : ($this->mode == 'light' ? 'var(--nown-primaria)' : 'var(--nown-primaria-darker)') );
        $this->bgMenuActive = ($options["customcolors"] == 'true' && !empty($options["destaque"]) ? $options["destaque"] : ($this->mode == 'light' ? 'var(--nown-primaria-darker)' : 'var(--nown-primaria)') );
        
        return '
        
        body.'.$this->mode.' #topo {
            background: '.$this->bgColor.';
        }
       
        
        body.'.$this->mode.' .centro .nav > li .nav-link:not(.dropdown-menu .nav-link) {
            color: '.$this->txtColor.';
            border-radius: 5px;
            margin: 0 1px;
            background-color: '.$this->bgMenu.';
            text-transform: inherit;
        }
        
        body.'.$this->mode.' .centro .nav > li .nav-link:not(.dropdown-menu .nav-link):hover {
            background-color: '.$this->bgMenuHover.';
            color: '.$this->txtColorHover.';
        }
        
        body.'.$this->mode.' .centro .nav > li .nav-link.ativo:not(.dropdown-menu .nav-link.ativo) {
            background-color: '.$this->bgMenuActive.';
            color: '.$this->txtColorHover.';
        }
        
        body.'.$this->mode.' .direita > button span {
            color: '.$this->txtColor.';
        }
        
        ';
    }
    
    function generateFile($seleciona, $cssDir)
    {
        
        $resultado = $this->conn->query($seleciona);
        while($dado = $resultado->fetch_assoc()){
            $pasta = $dado["config_pasta"];
            $arquivo = $dado["config_arquivo"];
            $chave = $dado["config_chave"];
            $valor = $dado["config_valor"];
            
            $this->config[$pasta][$arquivo][$chave] = $valor;
        }
        //css import fonts
        if(isset($this->config['tipografia'])){
            $this->css .= $this->renderRequiredFonts($this->config['tipografia']);
        }
        //css cores
        if(isset($this->config['estilo']['cores'])){
            foreach($this->config['estilo']['cores'] as $key => $value){
                if($key != "theme"){
                      $this->css .= $this->renderColor($key, $value);
                }
                // $colors = new generateColor();
              
            }
        }
        //css typography
        if(isset($this->config['tipografia'])){
            foreach($this->config['tipografia'] as $typo){
                if(isset($typo['selector'])){
                    $this->css .= $this->renderTypography($typo['selector'],$typo);
                }
     
            }
        }
        //css header
        if(isset($this->config['header'])){
            foreach($this->config['header'] as $mode => $options){
                if(in_array($mode, ['light-mode', 'dark-mode'])){
                    $this->css .= $this->renderHeaderCss($mode,$options);
                }
            }
        }
        
        if (is_dir($cssDir)) {
    if ($dh = opendir($cssDir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {
                // Construct the file path
                $filePath = $cssDir . '/' . $file;
                // Check if it is a file and not a directory
                if (is_file($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }
        }
        closedir($dh);
    }
}
        else{
                mkdir($cssDir, 0777, true); 

        }
        
        $file = $cssDir."nown-theme.".rand(1, 9999).".css";
        
        file_put_contents($file, '/*css generated at '.date('y-m-d h:i:s', strtotime('now')).'*/'.minifyCSS($this->css));
    }
}