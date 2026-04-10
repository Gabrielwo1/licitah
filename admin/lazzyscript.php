<?


$resposta = [];



$classes = scandir(__DIR__."/../assets/js/classes");
$scripts = [];
foreach($classes as $classe){
    if($classe != "." && $classe != ".." && !is_dir(__DIR__."/../assets/js/classes/".$classe)){
        array_push($scripts, $classe);
    }
    
  
}
$resposta["classes"] = $scripts;


$pasta = __DIR__.'/../includes/plugins/';
$plugins = scandir($pasta);
$scripts = [];
if(isset($_SESSION["id"])){
    foreach($plugins as $plugin){
    if ($plugin != '.' && $plugin != '..' && $plugin != 'index.php') {
        if (file_exists($pasta.$plugin."/".$plugin.".js")) {
            array_push($scripts, $plugin);
        }
    }
}
}

$resposta["plugins"] = $scripts;

/*

Níveis
0 - Todos
1 - Logado
2 - Deslogado
3 - Custom
*/

$extra = [
    ["url"=> "assets/bibliotecas/bootstrapIcons/css.css", "nivel"=>0],
    ["url"=> "assets/bibliotecas/jquery/js.js", "nivel"=>0],
    ["url"=> "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js", "nivel"=>0],
    ["url"=> "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css", "nivel"=>0],
    ["url"=> "assets/js/logado.js", "nivel"=>1],
    ["url"=> "assets/js/vitoria.js", "nivel"=>0],
    ["url"=> "https://cdn.jsdelivr.net/npm/sweetalert2@11", "nivel"=>0],
    ["url"=> "https://accounts.google.com/gsi/client", "nivel"=>2],
    ["url"=> "https://cdn.quilljs.com/1.3.6/quill.js" , "nivel"=>1],
    ["url"=> "https://cdn.quilljs.com/1.3.6/quill.bubble.css", "nivel"=>1],
    ["url"=> "https://cdn.quilljs.com/1.3.6/quill.snow.css", "nivel"=>1],
    ["url"=> "https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css", "nivel"=>0],
    ["url"=> "https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js", "nivel"=>0],
    ["url"=> "https://cdn.plyr.io/3.7.8/plyr.js", "nivel"=>0],
    ["url"=> "https://cdn.plyr.io/3.7.8/plyr.css", "nivel"=>0],
    ["url"=> "assets/bibliotecas/sortable/js.js", "nivel"=>1],
    ["url"=> "assets/js/websockets.js", "nivel"=>1],
    ["url"=> "assets/js/pago.js", "nivel"=>1],
    ["url"=> "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", "nivel"=>1],
    ["url"=>"https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js", "nivel"=>0]

    ];
 
 
$login = ["acesso", "cadastro", "senha-perdida"];
if(isset(PAGINA[0]) && in_array(PAGINA[0], $login)){
    $extra = [
    ["url"=> "assets/bibliotecas/bootstrapIcons/css.css", "nivel"=>0],
    ["url"=> "assets/bibliotecas/jquery/js.js", "nivel"=>0],
    ["url"=> "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js", "nivel"=>0],
    ["url"=> "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css", "nivel"=>0],
    ["url"=> "assets/js/start.js", "nivel"=>2],
    ["url"=> "https://cdn.jsdelivr.net/npm/sweetalert2@11", "nivel"=>0],
    ["url"=> "https://accounts.google.com/gsi/client", "nivel"=>2],
    ["url"=>"https://www.google.com/recaptcha/api.js", "nivel"=>2]
    ];
}
 
$tratamento = [];   
foreach($extra as $e){
    switch($e["nivel"]){
        case 0:
            array_push($tratamento, $e);
            break;
        case 1:
            if(isset($_SESSION["id"])){
                array_push($tratamento, $e); 
            }
            break;
        case 2:
            if(!isset($_SESSION["id"])){
                array_push($tratamento, $e); 
            }
            break;
    }
}

$resposta["extra"] = $tratamento;



  
$css = [];
if(is_dir(__DIR__."/../conteudo/assets/css")){
    $dir = scandir(__DIR__."/../conteudo/assets/css");
foreach($dir as $d){
    if($d != "." && $d != ".."){
        array_push($css, $d);
    }
}
    
}

$resposta["customCSS"] = count($css) > 0 ? $css : false;



$js = [];
if(is_dir(__DIR__."/../conteudo/assets/js")){
    $dir = scandir(__DIR__."/../conteudo/assets/js");
foreach($dir as $d){
    if($d != "." && $d != ".."){
        array_push($js, $d);
    }
}
}
$resposta["customJS"] = count($js) > 0 ? $js : false;





echo '<div class="d-none" style="display:none" id="scriptsLazy">'.json_encode($resposta).'</div>';

?>