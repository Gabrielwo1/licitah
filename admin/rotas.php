<?php
include __DIR__."/seguranca-include.php";

include 'config.php';
include 'afiliados.php';
include 'seo.php';
include 'configmodulo.php';
include 'googlebot-detector.php';

function restrito(){
    echo '<div id="restritoPage"></div>';
}

function meAndAdmins($posicao, $banco, $prefixo){
    if(!logado()){
        return false;
    }
    $conn = conn();
    $user = $_SESSION["id"] ?? false;
    $seleciona = "SELECT * FROM {$banco} WHERE {$prefixo}_hash='$posicao' LIMIT 1";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return false;
    }
    $dado = $resultado->fetch_assoc();
   if($dado[$prefixo."_autor"] == $user){
       return $dado;
   }
   
   if($_SESSION["tipouser"] === "equipe"){
       return  $dado;
   }
   return false;
}

function isEquipe(){
    if(!logado()){
        return false;
    }
    
    if($_SESSION["tipouser"] === "equipe"){
       return  true;
   }
   return false;
}

function ftv($valor) {
        // Se o valor for uma string que contém '%', retorna como está (é porcentagem)
        if (is_string($valor) && strpos($valor, '%') !== false) {
            return $valor;
        }
        // Caso contrário, assume que é um valor numérico e adiciona 'px'
        return $valor . 'px';
    }

function linhas($quantidade = 1, $altura = 20, $largura = "100%") {
    // Função auxiliar para checar se é uma porcentagem
  
    
    // Inicia uma string que conterá as divs geradas
    $output = '';
    
    // Loop para gerar a quantidade de divs especificada
    for ($i = 0; $i < $quantidade; $i++) {
        // Adiciona uma div com os estilos de altura e largura formatados
        $output .= '<div style="height: ' . ftv($altura) . '; width: ' . ftv($largura) . ';" class="bg-carregando"></div>';
    }
    
    // Retorna o HTML gerado
    return '<div class="d-flex flex-column gap-2">'.$output.'</div>';
}

function logado(){
    $itens = ["id", "nome", "email", "funcao"];
    
    $logado = true;
    foreach($itens as $item){
        if(!isset($_SESSION[$item])){
            $logado = false;
            break;
        }
    }
    return $logado;
}

function isMobile() {
    // Lista de agentes de usuário comuns para dispositivos móveis
    $mobileAgents = ['iPhone', 'iPod', 'Android', 'webOS', 'BlackBerry', 'Windows Phone'];

    // Recuperar a string do agente do usuário
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Verificar se algum dos agentes de usuário móveis está presente na string do agente de usuário
    foreach ($mobileAgents as $agent) {
        if (strpos($userAgent, $agent) !== false) {
            return true;
        }
    }

    return false;
}

if (strpos($_POST["p"], '/') === 0) {
    $_POST["p"] = ltrim($_POST["p"], '/');
}

$_POST["p"] = preg_replace('/\/+/', '/', $_POST["p"]);

if(isset($_SESSION["ativo"]) && !$_SESSION["ativo"]){
    $_POST["p"] = "aguardando-aprovacao";
}

if(isset($_SESSION["ativo"]) && $_SESSION["ativo"] == "2"){
     $_POST["p"] = "banido";
}

if(isset($_SESSION["needsub"]) &&  $_SESSION["needsub"] == true){
    $_POST["p"] = "conta";
}

if(isset($_SESSION["validaCredenciais"]) && $_SESSION["validaCredenciais"]){
    $_POST["p"] = "valida-acessos";
}

if(isset($_SESSION["needPass"]) && $_SESSION["needPass"]){
    $_POST["p"] = "senha";
}

if(isset($_SESSION["2fa"]) && $_SESSION["2fa"]){
    $_POST["p"] = $_SESSION["2fa"];
}

function trataImage($img){
    
}

function secureUrl($string){
    // Obtém o primeiro caractere da string
    $primeiroCaractere = substr($string, 0, 1);

    // Verifica se o primeiro caractere é uma letra ou número
    if (ctype_alnum($primeiroCaractere)) {
        return true; // Retorna true se for uma letra ou número
    } else {
        return false; // Retorna false se não for uma letra ou número
    }
}

function texto($termo){
    if(STRING[$termo] ?? false){
        echo STRING[$termo];
    }
}

function geraId(){
    $caracteres = "abcdefghijlmnopqrstuvxz";
    $i = 0;
    $final = "";
    while ($i < 10){
        $final .= $caracteres[rand(0, 22)];
        $i++;
    }

    return $final;
}

if(is_dir(__DIR__."/../conteudo/modulos/pagamento")){
    include __DIR__."/../conteudo/modulos/pagamento/admins/pegaPedido.php";     
}
       
class Rota{
    public $html;
    public $js;
    public $pagina;
    public $caminho;
    public $first;
    public $idioma;
    public $traducao;
    public $layout;
    public $url;
    public $estutural;
    public $mapModule;
    public $mapPage;
    public $publico;
    public $estrutural;
    public $wildcard;
    public $type;
    public $css;
    public $subtipo;
    public $subDetalhes;
    public $last;
    public $blockAction;
    public $big;
    public $idiomas;
    function __construct(){
        $this->html = false;
        $this->js = false;
        $this->css = false;
        $this->idioma = false;
        $this->layout = 0;
        $this->url = false;
        $this->estrutural  = false;
        $this->type = false;
        
        $this->idiomas = ["pt-BR", "en", "es", "fr", "de", "zh", "ar", "ru", "ja", "hi",
        "bn", "ur", "ko", "it", "nl", "el", "tr", "sv", "no", "da",
        "fi", "pl", "th", "he", "id"
        ];
        
     
      
        if(!isset($_SESSION["id"]) && !v(["paginas","deslogadas","deslogadas"], false)){
            $_POST["p"] = "acesso";
        }
        
        if(isset($_SESSION["id"]) && str_starts_with($_POST["p"], "restrito/")){
              $_POST["p"] = substr($_POST["p"] , strlen("restrito/"));
        }
        

        
        $idioma = "default";
        if($_POST["p"] ?? false){
            $trato = explode("/", $_POST["p"]);
            if(in_array($trato[0] , $this->idiomas)){
                $idioma = $trato[0];
                array_shift($trato);
                $_POST["p"] = implode("/", $trato);
            }
        }
        define("IDIOMA", $idioma);
        
        
        if(isset($_POST["p"])){
            $this->pagina = !$_POST["p"]  ? "home" : $_POST["p"];
            $caminho = strtolower($this->pagina);
            $caminho = explode("/", $caminho);
            end($caminho) === '' ? array_pop($caminho) : "";
            $this->caminho = $caminho;
            $this->url = implode("/", $caminho);
        }else{
            $this->pagina = false;
        }
        
        

        if($this->pagina){
            $this->first = $this->caminho[0];
            
            $this->last = $this->caminho[count($this->caminho) - 1];
            
            
            $this->fluxo();
        }
    }
    
    function loadTranslations($caminho) {
    
    $strings = [];

    if (file_exists($caminho."/idiomas/default.json")) {
        $strings = json_decode(file_get_contents($caminho."/idiomas/default.json"), true);
    }

    if (defined('IDIOMA') && IDIOMA != "default" && file_exists($caminho."/idiomas/".IDIOMA.".json")) {
        $sobreposicao = json_decode(file_get_contents($caminho."/idiomas/".IDIOMA.".json"), true);
        

        foreach ($sobreposicao as $chave => $valor) {
            $strings[$chave] = $valor;
        }
    }
    if (!defined('STRING')) {
        define("STRING", $strings);
    }
    
    }

    function modulo($pasta, $container){
        if(!$this->autorizacaoRestrito()){
            return;
        }

  
        $modulo = $this->caminho[1];
        $pastaDir = __DIR__ . "/../".$pasta."/modulos/".$modulo;

        if(is_dir($pastaDir)) {
            
            if(!file_exists($pastaDir."/manifest.json")){
                return;
            }
            
            $conteudo = json_decode(file_get_contents($pastaDir."/manifest.json"), true);
            if(!$conteudo["ativo"] || $conteudo["ativo"] == "false"){
                return;
            }
                 

            $caminho = count($this->caminho) == 2 ? $this->caminho[1] : $this->caminho[2];
            
       
            $arquivo = $pasta."/modulos/".$modulo."/views/" . $caminho. ".php";
            
           
            $arquivoJs =  $pasta."/modulos/".$modulo."/assets/js.js";
            $arquivoCss = $pasta."/modulos/".$modulo."/assets/css.css";

            $primario = $this->pegaArquivo($arquivo , $arquivoJs, $container, $arquivoCss);
                
            
            $this->mapModule = $modulo;
            $this->mapPage = $caminho;
            
            if(!$primario){
                 $arquivo = $pasta."/modulos/".$modulo."/views/excecao.php";
                 $arquivoJs =  $pasta."/modulos/".$modulo."/assets/excecao.js";
                 $arquivoCss = $pasta."/modulos/".$modulo."/assets/css.css";
                 $this->pegaArquivo($arquivo , $arquivoJs, false, $arquivoCss);
            }
            
            
            if(!$this->html){
                $arquivo = $pastaDir."/admins/paginas/".$caminho.".json";
     
                
                $this->estrutural = $arquivo;
                if(file_exists($arquivo)){
                    $estrutura = json_decode(file_get_contents($arquivo), true);
                    
                    if($estrutura["tipo"] == 2){
                        $this->subtipo = 1;
                        $arquivoTabela = $pastaDir."/admins/tabelas/".$estrutura["id"].".json";
                        
                        
                        
                        if(file_exists($arquivoTabela)){
                             $estruturaTabela = json_decode(file_get_contents($arquivoTabela), true);
                             
                             
                             if(isset($estruturaTabela["layout"]) && $estruturaTabela["layout"]["modo"] && $estruturaTabela["layout"]["modo"] == 2){
                                  $this->subtipo = 2;
                                  $this->subDetalhes = $estruturaTabela["layout"];
                             }
                             
                 
                             if($estruturaTabela["acoes"]["opcoes"]["bigdata"] ?? false){
                                 $this->big = true;
                             }
                        }
                        
                    }else{
                        $arquivoForm = $pastaDir."/admins/configs/".$estrutura["id"].".json";
                         if(file_exists($arquivoForm)){
                              $estruturaForm = json_decode(file_get_contents($arquivoForm), true);
                              $estrutura["editUrl"] = $estruturaForm["estrutura"]["urledit"] ?? false;
                         }
                        
                    }
                   
                    $estrutura["modulo"] = $modulo;
                    $estrutura["master"] = $pasta == "master" ? true : false;
                    

                    
                    $pegaHash = explode("/", $_POST["p"]);
                    $estrutura["hash"] = $pegaHash[3] ?? false;
                    $this->estrutural = $estrutura;
                    $this->mapModule = $modulo;
                    $this->mapPage = $caminho;
                    
                   
                    
                    $arquivoJs =  $pasta."/modulos/".$modulo."/assets/json.js";
           
                    if(file_exists(__DIR__."/../".$arquivoJs)){
                        $this->js = $arquivoJs;
                    }
                }
            }
      }
        
    }
    
    function traduz($idioma , $caminho){
    $conn = conn();
    $rota = implode("/", $caminho);
    $seleciona = "SELECT * FROM  traducoes WHERE traducao_idioma='$idioma' AND traducao_url='$rota'";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 1){
        $dado = $resultado->fetch_assoc();
        $conn->close();
        return $dado["traducao_referencia"];
    }else{
        $conn->close();
        return false;
    }
}
    
    function  tratarCaminhoPre($caminho) {
    // Verifica se a string começa com 'conteudo/paginas/'
    if (str_starts_with($caminho, 'conteudo/paginas/')) {
     
        return __DIR__.'/../conteudo/paginas/' . basename(dirname($caminho)) . '/';
    }
    return $caminho; // Retorna o caminho original se não houver correspondência
}
    
    function pegaArquivo($arquivo, $arquivoJS = false, $container = false, $arquivoCss = false){
       
        if(!$this->html){
           $html = __DIR__."/../".$arquivo;
           
       

           $js = $arquivoJS ? __DIR__."/../".$arquivoJS : false;
           $css = $arquivoCss ? __DIR__."/../".$arquivoCss : false;
           if($this->fileExists($html)) {
            
          
             ob_start();
             $string = $this->loadTranslations($this->tratarCaminhoPre($arquivo));
             include($html);
            
             if($container){
                 $this->html = '<div class="container">'.$this->minificarPHP(ob_get_clean()).'</div>';
             }else{
                 
                 $this->html = $this->minificarPHP(ob_get_clean());
             }

      
             
             if($this->html) {
                 if($js && $this->fileExists($js)){
                     $this->js = $arquivoJS;
                 }
                 
                 if($css && $this->fileExists($css)){
                     $this->css = $arquivoCss;
                 }
                 
             }
             
           }
        }
        
        if(!$this->html){
            return false;
        }else{
            return true;
        }
    }
    
    function fluxo(){

        switch($this->first){
            case 'a':
                if(isset($_SESSION["id"])){
                    $this->modulo("conteudo", true);
                }
                break;
            case 'm':
                if(isset($_SESSION["funcao"]) && $_SESSION["funcao"] == 0){
                    $this->modulo("master", false);
                }
                break;
            default: 
                /*
                if(isset($this->idiomas[$this->first])){
                     $this->idioma = $this->first;
                     array_shift($this->caminho);
                     $traducao = $this->traduz($this->idioma , $this->caminho);
                     if($traducao){
                         $this->caminho = explode("/", $traducao);
                         $this->traducao = $this->caminho;
                     }
                     
                      if(count($this->caminho) > 0){
                          $this->first = $this->caminho[0];
                      }
                }
                */
                if(count($this->caminho) > 0){
                    /*
                    V de Vizibilidade
                    0 para todos os usuarios
                    1 para deslogados
                    2 para logados
                    
                    L de layout
                    0 - Com header e lateral
                    1 - Somente header
                    2 - Sem header e sem lateral
            
                    */
                    $restritos = [
                        "carrinho"=>["v"=>0, "l"=>0, "js"=>"assets/js/carrinho.js"],
                        "acesso"=>["v"=>1, "l"=>2, "js"=>"assets/js/login.js"],
                        "conta"=>["v"=>2, "l"=>2, "js"=>"assets/js/login.js"],
                        "restrito"=>["v"=>1, "l"=>2, "js"=>"assets/js/restrito.js"],
                        "senha"=>["v"=>2, "l"=>2, "js"=>"assets/js/login.js"],
                        "valida-acessos"=>["v"=>2, "l"=>2, "js"=>"assets/js/login.js"],
                        "cadastro"=>["v"=>1, "l"=>2, "js"=>"assets/js/login.js"],
                        "recuperar-senha"=>["v"=>1, "l"=>2, "js"=>"assets/js/login.js"],
                        "dois-fatores"=>["v"=>1, "l"=>2, "js"=>"assets/js/login.js"],
                        "sistema"=>["v"=>2, "l"=>0, "js"=>"assets/js/sistema.js"],
                        "manutencao"=>["v"=>0, "l"=>2, "js"=>false],
                        "loginqr"=>["v"=>0, "l"=>0, "js"=>"assets/js/loginqr.js"],
                        "aguardando-aprovacao"=>["v"=>2, "l"=>2, "js"=>"assets/js/moderacao.js"],
                        "banido"=>["v"=>2, "l"=>2, "js"=>"assets/js/banido.js"],
                        "erro404"=>["v"=>0, "l"=>0, "js"=>"assets/js/erros.js"],
                        "reports-nown"=>["v"=>2, "l"=>0, "js"=>"assets/js/reports.js"],
                        "politicas"=>["v"=>0, "l"=>0, "js"=>false],
                        "acesso-bloqueado"=>["v"=>0, "l"=>0, "js"=>false],
                        "totp"=>["v"=>2, "l"=>2, "js"=>"assets/js/login.js"],
                        ];
                        
                        if(isset($restritos[$this->first])){
                            switch($restritos[$this->first]["v"]){
                                case 0:
                                    $this->pegaArquivo("includes/sistema/".$this->first.".php" , $restritos[$this->first]["js"]);
                                    $this->layout = $restritos[$this->first]["l"];
                                    break;
                                case 1:
                                    if(!isset($_SESSION["id"])){
                                        $this->pegaArquivo("includes/sistema/".$this->first.".php" , $restritos[$this->first]["js"]);
                                        $this->layout = $restritos[$this->first]["l"];
                                    }
                                    break;
                                case 2:
                                    if(isset($_SESSION["id"])){
                                        $this->pegaArquivo("includes/sistema/".$this->first.".php" , $restritos[$this->first]["js"]);
                                        $this->layout = $restritos[$this->first]["l"];
                                    }
                                    break;
                            }
                            
                        }else{
                       
                            if(defined("ISWILDCARD")){
                                $pagina = $this->pegaArquivo("conteudo/wildcard/".$this->first."/index.php", "conteudo/wildcard/".$this->first."/js.js" , false, "conteudo/wildcard/".$this->first."/css.css");
                            
                                   if(!$pagina){
                                if(is_dir(__DIR__."/../conteudo/modulos/".$this->first."/publico/")){
                                     ob_start();
                                     $publico = __DIR__."/../conteudo/modulos/".$this->first;
                                     
                                     if(file_exists($publico."/manifest.json")){
                                          $conteudo = json_decode(file_get_contents($publico."/manifest.json"), true);
                                           if(isset($conteudo["ativo"]) && $conteudo["ativo"] == "true"){
                                               $modulo = $this->first;
                                               configModulo($this->first);
                                               $string = $this->loadTranslations(__DIR__."/../conteudo/modulos/".$this->first);
                                     include("rotaspublicas.php");
                                     $this->html = $this->minificarPHP(ob_get_clean());
                                     
                                     $this->mapModule = $this->first;
                             
       
                                     $this->mapPage = $this->caminho[1] ??  $this->caminho[0];
                                     $this->publico = true;
                                     
                                     if($this->html){
                                         if(file_exists(__DIR__."/../conteudo/modulos/".$this->first."/assets/publico.js")){
                                             $this->js = 'conteudo/modulos/'.$this->first.'/assets/publico.js';
                                         }
                                         
                                         if(file_exists(__DIR__."/../conteudo/modulos/".$this->first."/assets/publico.css")){
                                             $this->css = 'conteudo/modulos/'.$this->first.'/assets/publico.css';
                                         }
                                         
                                     }
                                               
                                           }
                                     }

                                     
                                    
                                }
                            }
                                
                            }else{
                                
                                $pagina = false;
                                
                                
                                $pagina = $this->pegaArquivo("conteudo/paginas/".$this->first."/index.php", "conteudo/paginas/".$this->first."/js.js", false, "conteudo/paginas/".$this->first."/css.css");
                                
                                
                                if($this->html){
                                       $teste = implode("/", $this->caminho);
                                    if($this->autorizacaoAberto($teste, "paginas")){
                                        
                                        ob_start();
                                        include(__DIR__ . "/rodape.php");
                                        $html = ob_get_clean();
                                        if($html){
                                            $this->html .= $html;
                                        }
                                    
                                    }else{
                                        
                                        $this->html = false;
                                        $this->js = false;
                                        if($this->blockAction){
                                            $this->first = $this->blockAction;
                                            $this->url = $this->blockAction;
                                            $this->fluxo();
                                            return;
                                      
                               
                                        }
                                        
                                        
                                        
                                        
                                     
                                    }
                               
                                    
                                }
                                
                               
                                
                                
                                
                                
                                if(!$pagina){
                                if(is_dir(__DIR__."/../conteudo/modulos/".$this->first."/publico/")){
                                    $teste = count($this->caminho) > 1 ? implode("/", $this->caminho) : $this->first;
                                   
                                    if(!$this->autorizacaoAberto($teste, "publicas")){
                                         $this->html = false;
                                         $this->js = false;
                                        if($this->blockAction){
                                            $this->first = $this->blockAction;
                                            $this->url = $this->blockAction;
                                            $this->fluxo();
                                        }
                                         return;
                                    }
                                    
                                     ob_start();
                                     $publico = __DIR__."/../conteudo/modulos/".$this->first;
                                     
                                     if(file_exists($publico."/manifest.json")){
                                          $conteudo = json_decode(file_get_contents($publico."/manifest.json"), true);
                                           if(isset($conteudo["ativo"]) && $conteudo["ativo"] == "true"){
                                               $modulo = $this->first;
                                               configModulo($this->first);
                                               $string = $this->loadTranslations(__DIR__."/../conteudo/modulos/".$this->first);
                                     include("rotaspublicas.php");
                                     $this->html = $this->minificarPHP(ob_get_clean());
                                     
                                     $this->mapModule = $this->first;
                             
       
                                     $this->mapPage = $this->caminho[1] ??  $this->caminho[0];
                                     $this->publico = true;
                                     
                                     if($this->html){
                                         if(file_exists(__DIR__."/../conteudo/modulos/".$this->first."/assets/publico.js")){
                                             $this->js = 'conteudo/modulos/'.$this->first.'/assets/publico.js';
                                         }
                                         
                                         if(file_exists(__DIR__."/../conteudo/modulos/".$this->first."/assets/publico.css")){
                                             $this->css = 'conteudo/modulos/'.$this->first.'/assets/publico.css';
                                         }
                                         
                                     }
                                               
                                           }
                                     }

                                     
                                    
                                }
                            }
                            
                            if(!$pagina){
                                if(is_dir(__DIR__."/../conteudo/modulos/replicante") && file_exists(__DIR__."/../conteudo/modulos/replicante/admins/rotas.php")){
                                    include __DIR__."/../conteudo/modulos/replicante/admins/rotas.php";
                             }
      
        
        
    
                            }
                                
                                
                            }
                            
                            
                            
                            
                            
                        }
                }else{
                    $this->pegaArquivo("conteudo/paginas/home/index.php", "conteudo/paginas/home/js.js", false, "conteudo/paginas/home/css.css");
                }
                break;
            
        }
    }
    
    function minificarPHP($php) {
        $search = array(
    '/\>[^\S ]+/s',
    '/[^\S ]+\</s',
    '/(\s)+/s'
  );
        $replace = array('>', '<', '\\1');
        $minificado = preg_replace($search, $replace, $php);
        return $minificado;
    }

    function fileExists($path) {
        return file_exists($path) && is_file($path);
    }
    
    function minifyCSS($css) {
    // Remove comentários
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    $css = preg_replace('/\n\s*\n/', "\n", $css); 

    // Remove espaços em branco extras
    $css = preg_replace('/[\n\r \t]/', ' ', $css);
    $css = preg_replace('/ +/', ' ', $css);
    $css = preg_replace('/ ?([,:;{}]) ?/', '$1', $css);
    $css = preg_replace('/;}/', '}', $css); // Remove ponto e vírgula antes das chaves

    return trim($css);
}
    
    function getCss(){
        if($this->css){
            ob_start();
            include(__DIR__."/../".$this->css);
            return $this->minifyCSS(ob_get_clean());
        }
        return false;
    }
    
    function autorizacaoRestrito(){
        $url = $this->pagina;
        $conn = conn();
        if(!isset($_SESSION["funcao"])){
            return false;
        }
  
        
        $funcao = intval($_SESSION["funcao"]);

        if($funcao === 0 || $funcao === 1){
            return true;
        }

        
        $trato = explode("/", $url);
        if(count($trato) > 3 && $trato[0] == "a"){
            $url = "{$trato[0]}/{$trato[1]}/{$trato[2]}";
        }
 
        
        $seleciona = "SELECT * FROM funcoes_paginas WHERE funcao_pagina_tipo='admin' AND funcao_pagina_url='$url' AND funcao_pagina_ativa='1' AND funcao_pagina_funcao='$funcao'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
            $conn->close();
            return false;
        }
         $conn->close();
        return true;
    }
    
    function autorizacaoAberto($url, $tipo){

        $conn = conn();
        if(!isset($_SESSION["funcao"])){
            return true;
        }
  
        
        $funcao = intval($_SESSION["funcao"]);

        if($funcao === 0 || $funcao === 1){
            return true;
        }

     
        $seleciona = "SELECT * FROM funcoes_paginas WHERE 
        funcao_pagina_url='$url' AND 
        funcao_pagina_ativa='0' AND 
        funcao_pagina_funcao='$funcao' AND
        funcao_pagina_tipo='$tipo'";
      



        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
            $conn->close();
            return true;
        }
        
        
        $dado = $resultado->fetch_assoc();
        
        $acao = intval($dado["funcao_pagina_acao"]);
        
        $this->blockAction = false;
        switch($acao){
            case '2':
                $this->blockAction = "acesso-bloqueado";
                $this->caminho = ["acesso-bloqueado"];
                break;
            case 3:
                $this->blockAction = $dado["funcao_pagina_cb"] ?? false;
                if($this->blockAction){
                    $this->blockAction = ltrim($this->blockAction, '/');
                    $this->caminho = explode("/", $this->blockAction);
                }
                break;
        }
        
        $conn->close();
        return false;
    }
    
    function pegaWild($conn){
        $wildcard = 0;
        if(defined("ISWILDCARD")){
           $wildcard = ISWILDCARD;
        }
        return $wildcard; 
    }
    
    function pegaDinamica($conn, $wildcard){
        $manifesto = json_decode(file_get_contents(__DIR__."/../conteudo/modulos/paginas/manifest.json"), true);
        if($manifesto["ativo"]){
            
            if($this->first != "home"){
                $seleciona = "SELECT * FROM paginas WHERE paginas_url='{$this->first}' AND paginas_wildcard='{$wildcard}' LIMIT 1";
            }else{
                
                $seleciona = "SELECT wc_home as home FROM wildcards_contas WHERE wc_id='{$wildcard}' AND wc_home > '0' LIMIT 1";
                $resultado = $conn->query($seleciona);
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $id = $dado["home"];
                    $seleciona = "SELECT * FROM paginas WHERE paginas_id='{$id}' LIMIT 1";
                }else{
                    $seleciona = "SELECT * FROM paginas WHERE paginas_url='{$this->first}' AND paginas_wildcard='{$wildcard}' LIMIT 1";

                }
                 
            }
        
        
           
      
              $resultado = $conn->query($seleciona);
              
              if($resultado->num_rows == 0){
                  return false;
              }
             
             $dado = $resultado->fetch_assoc();
             $id = $dado["paginas_id"];
             
  
                include(__DIR__ . "/../conteudo/modulos/paginas/admins/renderBack.php");
                $render = new RenderBack($conn);
                $dado = $render->render($id);
                if($dado){
                    $this->html = $dado;
                }
        }
        
      
    }
    
    function render(){
        $status = !$this->html ? 404 : 200;
        referencia($status);
        
        
        if(!$this->html && !$this->estrutural && is_dir(__DIR__."/../conteudo/modulos/paginas/")){
            
            $conn = conn();
            $wild = $this->pegaWild($conn);
            
          
            $this->pegaDinamica($conn, $wild);
         
            
        
        }
        
      
        
        $resposta = [
            "sucesso"=>true,
            "html"=>$this->html,
            "js"=>$this->js,
            "layout"=>$this->layout,
            "url"=>$this->url,
            "estrutura"=>$this->estrutural,
            "mapModulo"=>$this->mapModule,
            "mapPage"=>$this->mapPage,
            "publico"=>$this->publico,
            "type"=>$this->type,
            "css"=>$this->getCss(),
            "cacheavel"=>defined("INCACHEAVEL") ? false : true
            ];
            
        if($this->subtipo){
            $resposta["sub"] = $this->subtipo;
            
            if($this->subtipo == "2"){
                $resposta["subDetalhes"] = $this->subDetalhes;
            }
        }
        
        if($this->big){
            $resposta["big"] = true;
        }
            
        return $resposta;
    }
}

$rota = new Rota();
$resposta = $rota->render();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);



?>
