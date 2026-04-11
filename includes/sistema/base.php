<?

function v($array, $alt = ""){
        if(!SETUP){
            return $alt;
        }
        $caminho = SETUP;
        
        $ultimo = count($array) - 1;
        $i = 0;
        
        
        if(count($array) != 3){
            return $alt;
        }
   
   
        
        if(isset($caminho[$array[0]]) && isset($caminho[$array[0]][$array[1]]) && isset($caminho[$array[0]][$array[1]][$array[2]])){
            $resposta = $caminho[$array[0]][$array[1]][$array[2]];
            
            
            if($resposta === "true"){
                $resposta = true;
            }
                    
            if($resposta === "false"){
                $resposta = false;
            }
            return $resposta;
            
        }else{
            return $alt;
        }
      
        
        return $alt;
}

$setup = false;
$nome;
$tag;


$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
$dominioAtual = $protocolo . '://' . $_SERVER['HTTP_HOST'];
$dominioHost = $_SERVER['HTTP_HOST'];
$setup = null;

// Verificar se existe o arquivo de setup
if(file_exists(__DIR__."/../../conteudo/setup.json")){
    $setupJson = file_get_contents(__DIR__."/../../conteudo/setup.json");
    $setup = json_decode($setupJson, true);
}

// Sempre usar o host real da requisição como domínio base.
// O setup.json só sobrescreve se o domínio configurado for um domínio próprio
// (diferente do host atual), para não quebrar deploys em URLs temporárias.
$dominioSetup = ($setup && isset($setup["dominio"])) ? $setup["dominio"] : null;
if($dominioSetup && $dominioSetup !== $_SERVER['HTTP_HOST']){
    $dominio = "https://" . $dominioSetup;
} else {
    $dominio = $dominioAtual;
}

define("DOMINIO", $dominio);

if(defined("ISWILDCARD")){
    define("WILDCARD", $dominioAtual);
} else {
    define("WILDCARD", $dominio);
} 


define("SETUP", $setup);


$nome = v(["geral","geral","nome"], false);

if(v(["geral","geral","tag"], false)){
    $tag = v(["geral","geral","tag"], false);
}

if(v(["manutencao","modo-manutencao","modomanutencao"], false) && !$_SESSION["id"]){
    if($_SERVER["REQUEST_URI"] != "/acesso"){
        include __DIR__."/manutencao.php";
        return;
    }
}


?>
<html lang="pt-BR">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui=1">
      <meta name="nown" content="ativo">
     
      
      <?
      
      echo '<meta name="csrf-token" content="'.$seguranca->gerarCsrf().'">';
      
      if(v(["estilo", "cores", "theme"], false) && v(["estilo", "cores", "tema"], false)){
         echo '<meta name="theme-color" content="'.v(["estilo", "cores", "tema"], false).'">';
      }
     
      ?>
      <title><?=$nome ?? ""?></title>
     
      <?
      if(v(["apis", "google-tag-mananger", "ativo"], false) && v(["apis", "google-tag-mananger", "tag"], false)){
          $tag = v(["apis", "google-tag-mananger", "tag"], false);
          echo "
          <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','$tag');</script>
          </script>";
      }
      
      if(v(["apis", "google-ads", "ativo"], false) && v(["apis", "google-ads", "tag"], false)){
          $tag = v(["apis", "google-ads", "tag"], false);
          echo '<script async src="https://www.googletagmanager.com/gtag/js?id='.$tag.'"></script>';
          echo "<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);} gtag('js', new Date());gtag('config', '$tag');</script>";
      }
      
      if(v(["apis", "google-ads", "ativodois"], false) && v(["apis", "google-ads", "tagdois"], false)){
          $tag = v(["apis", "google-ads", "tagdois"], false);
          echo '<script async src="https://www.googletagmanager.com/gtag/js?id='.$tag.'"></script>';
          echo "<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);} gtag('js', new Date());gtag('config', '$tag');</script>";
      }
      
      if(v(["apis", "google-analitcs", "ativo"], false) && v(["apis", "google-analitcs", "tag"], false)){
          $tag = v(["apis", "google-analitcs", "tag"], false);
          echo '<script async src="https://www.googletagmanager.com/gtag/js?id='.$tag.'"></script>';
          echo "<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '$tag');</script>";
      }
      
      
      if(v(["apis", "google-adsense", "ativo"], false) && v(["apis", "google-adsense", "capub"], false)){
          echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-'.v(["apis", "google-adsense", "capub"], false).'"
     crossorigin="anonymous"></script>';
      }
         
      if(v(["apis", "bing-ads" , "ativo"], false) && v(["apis", "bing-ads", "id"], false)){
          $tag = v(["apis", "bing-ads", "id"], false);
          echo '<script> (function(w, d, t, r, u) {
    var f, n, i;
    w[u] = w[u] || [], f = function() {
        var o = {
            ti: "'.$tag.'",
            enableAutoSpaTracking: true
        };
        o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
    }, n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function() {
        var s = this.readyState;
        s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
    }, i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
})(window, document, "script", "//bat.bing.com/bat.js", "uetq"); </script>';
      }
      
       if(v(["apis", "taboola" , "ativo"], false) && v(["apis", "taboola", "id"], false)){
          $tag = v(["apis", "taboola", "id"], false);
          echo "
<script>
  window._tfa = window._tfa || [];
  window._tfa.push({notify: 'event', name: 'page_view', id: ".$tag."});
  !function (t, f, a, x) {
         if (!document.getElementById(x)) {
            t.async = 1;t.src = a;t.id=x;f.parentNode.insertBefore(t, f);
         }
  }(document.createElement('script'),
  document.getElementsByTagName('script')[0],
  '//cdn.taboola.com/libtrc/unip/".$tag."/tfa.js',
  'tb_tfa_script');
</script>
";
      }
      
      
      
      if(v(["apis", "pixel-facebook", "ativo"], false) && v(["apis", "pixel-facebook", "id"], false) && trim(v(["apis", "pixel-facebook", "id"], false))){
          $id = v(["apis", "pixel-facebook", "id"], false);
          echo '
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,"script",
"https://connect.facebook.net/en_US/fbevents.js");
fbq("init", "'.$id.'");
fbq("track", "PageView");
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id='.$id.'&ev=PageView&noscript=1"
/></noscript>';
      }

      
      
      if(file_exists(__DIR__."/../../conteudo/assets/tema.css")){
          $conteudo = file_get_contents(__DIR__."/../../conteudo/assets/tema.css");
          echo '<style>'.$conteudo.'
          
          /* Estilo base para os botões */
.btn-m-primaria,
.btn-m-secundaria,
.btn-m-terciaria {
    display: inline-block;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;

    &:focus {
        outline-offset: 2px;
    }

    &:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
}

/* Botão Primário */
.btn-m-primaria {
    background-color: var(--nown-n-primaria-container);
    color: var(--nown-n-on-primaria-container);

    &:hover ,
    &:active {
        background-color: var(--nown-n-primaria);
        color: var(--nown-n-on-primaria);
    }
}

/* Botão Secundário */
.btn-m-secundaria {
    background-color: var(--nown-n-secundaria-container);
    color: var(--nown-n-on-secundaria-container);

    &:hover,
    &:active {
        background-color: var(--nown-n-secundaria);
        color: var(--nown-n-on-secundaria);
    }

    &:active {
        background-color: var(--nown-n-secundaria-dark);
    }
}

/* Botão Terciário */
.btn-m-terciaria {
    background-color: var(--nown-n-terciaria-container);
    color: var(--nown-n-on-terciaria-container);

    &:hover {
        background-color: var(--nown-n-terciaria);
        color: var(--nown-n-on-terciaria);
    }

    &:active {
        background-color: var(--nown-n-terciaria-dark);
    }
}



          
          </style>';
      }
      ?>
      
      


    <script>
        var dominio = window.location.origin;
        var dominioscript = window.location.origin;
        var dominioAdress = dominio;
        
       
        
      </script>

 
      <?
    
      if(is_dir(__DIR__."/../../conteudo/modulos/aplicativo")){
         if(defined("ISWILDCARD")){
             $urlManifesto = WILDCARD;
         }else{
             $urlManifesto = DOMINIO;
         }
         echo '<link rel="manifest" href="'.$urlManifesto.'/manifest.json">'; 
         echo '<link rel="manifest" href="'.$urlManifesto.'/app.webmanifest">'; 
      }
      ?>
      
       <?
       echo ' <style>';
        if(v(["geral","barra-de-rolagem","personalizar"], false)){
            ?>
           
              ::-webkit-scrollbar {
                width: <?=intval(v(["geral","barra-de-rolagem","largura"], 2));?>!important;
              }
              
               ::-webkit-scrollbar-track {
                   background: <?=v(["geral","barra-de-rolagem","corbarra"], "#f1f1f1");?>!important;
               }
               
              ::-webkit-scrollbar-thumb {
                  background: <?=v(["geral","barra-de-rolagem","cormarcador"], "#888");?>!important;
                  border-radius: 0px !important;
              }

            <?
        }
            
    

        if (v(["estilo", "light-dark-mode", "bodylight"], false)) {
    echo 'body.light #conteudo{';
    
    // Cor de fundo no modo Light
    echo 'background-color: '.v(["estilo", "light-dark-mode", "corbody"], "#f6f8fc").'!important;';
    
    // Imagem de background no modo Light
    if ($bgImageLight = v(["estilo", "light-dark-mode", "backgroundlight"], false)) {
        $img = json_decode($bgImageLight, true)[0];
        echo 'background-image: url("https://'.DOMINIO.'/conteudo/uploads/'.$img.'")!important;';
        
        // Tamanho do background
        $bgSizeLight = v(["estilo", "light-dark-mode", "backgroundsizelight"], "cover");
        echo 'background-size: '.$bgSizeLight.'!important;';
        
        // Repetição do background
        $bgRepeatLight = v(["estilo", "light-dark-mode", "backgroundrepeatlight"], "no-repeat");
        echo 'background-repeat: '.$bgRepeatLight.'!important;';
        
        // Posição do background
        $bgPositionLight = v(["estilo", "light-dark-mode", "backgroundpositionlight"], "center");
        echo 'background-position: '.$bgPositionLight.'!important;';
    }

    echo '}';
}

// Verificar se o modo Dark está ativado e aplicar estilos
if (v(["estilo", "light-dark-mode", "bodydark"], false)) {
    echo 'body.dark #conteudo{';
    
    // Cor de fundo no modo Dark
    echo 'background-color: '.v(["estilo", "light-dark-mode", "cordark"], "#121212").'!important;';
    
    // Imagem de background no modo Dark
    if ($bgImageDark = v(["estilo", "light-dark-mode", "backgrounddark"], false)) {
        $img = json_decode($bgImageDark, true)[0];
        echo 'background-image: url("https://'.DOMINIO.'/conteudo/uploads/'.$img.'")!important;';
        
        // Tamanho do background
        $bgSizeDark = v(["estilo", "light-dark-mode", "backgroundsizedark"], "cover");
        echo 'background-size: '.$bgSizeDark.'!important;';
        
        // Repetição do background
        $bgRepeatDark = v(["estilo", "light-dark-mode", "backgroundrepeatdark"], "no-repeat");
        echo 'background-repeat: '.$bgRepeatDark.'!important;';
        
        // Posição do background
        $bgPositionDark = v(["estilo", "light-dark-mode", "backgroundpositiondark"], "center");
        echo 'background-position: '.$bgPositionDark.'!important;';
    }

    echo '}';
}
        
        echo '</style>';
        ?>
      
      <style>
      .material-symbols-outlined{
          display: none;
      }
      
          .floating-logo {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

   




    </style>
     
   </head>
   
   
   <?

   switch(intval(v(["estilo", "sistema", "formularios"], 1))){
       case 1:
           $estiloForm = "";
           break;
       case 2:
           $estiloForm = "formModerno";
           break;
   }
   
   

   ?>
   
   
   
   <body class="light <?=$estiloForm?>">

       <?
       if(v(["apis", "google-tag-mananger", "ativo"], false) && v(["apis", "google-tag-mananger", "tag"], false)){
           echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.v(["apis", "google-tag-mananger", "tag"], false).'" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
       }
       ?>



       
   <?
   if(v(["geral","pre-carregamento","precarregamento"], false)){
           $bg = v(["geral","pre-carregamento","corbackground"], "white");
           $imagem = v(["geral","pre-carregamento","imagem"], false);
           $texto =  v(["geral","pre-carregamento","textocarregamento"], false);
           $img = false;
           if($imagem){
               $array = json_decode($imagem, true);
               if(count($array) == 1){
                   $img = $array[0];
               }
           }

           ?>
           <div style="color: white; position: fixed; top: 0px; left: 0px; width: 100%; height: 100vh; z-index: 2000; background-color: <?=$bg?>; display: flex; align-items: center; justify-content: space-between; flex-direction: column" id="firstLoad">
               <div>
                   
               </div>
              <div cstyle="display: flex; flex-direction: column; justify-content: center; gap: 20px">
              <?
              if($img){
                  $size = v(["geral","pre-carregamento","altura"], 100);
                  $url = DOMINIO."/conteudo/uploads/".$img;
                  $nova = str_replace('/original.png', '/media.webp', $url);
                  echo '<div><img src="'.$nova.'" style="height: '.$size.'px; margin: auto; display: block"></div>';
              }
              
           
   
              if($texto){
                  $cor = v(["geral","pre-carregamento","cortextos"], "white");
                  echo '<div  style="font-size: 20px; font-weight: 700px; text-align: center;  font-family: system-ui; color:'.$cor.'">'.$texto.'</div>';
              }    
              ?>
              
            
            </div>
              <div>
                 <?
                    if($offline){
                  echo '
                  <div style="margin-bottom: 10px">
                    <h2 style="margin-bottom: 10px; font-size: 20px; font-weight: 900px; text-align: center;  font-family: system-ui; color:'.$cor.'">SEM INTERNET</h2>
                    <p style="font-size: 16px; font-weight: 900px; text-align: center;  font-family: system-ui; color:'.$cor.'">Para uma melhor experiência, conecte-se a rede.</p>
                  </div>';
             
              }
                 ?>
               </div>
        </div>

           <?
       } 
   
   $cache = "";
   if(!$offline && true){
       $manifestPath = __DIR__ . "/../../admin/manifest.json";
       $manifest = json_decode(file_get_contents($manifestPath), true);
       $versao = str_replace(".", "", $manifest["versao"]);
       $cache = '?v='.$versao;
   }
   
   
   if(v(["performance", "velocidade", "cache"], false) || true){
        $cache = '?v='.rand(999, 999999999);
   }
    $cache = '?v='.rand(999, 999999999);
   if(is_dir(__DIR__."/../../conteudo/modulos/aplicativo")){
       echo '<script src="'.DOMINIO.'/assets/js/pwa.js'.$cache.'" data-v="'.$versao.'" id="scriptPwa"></script>';
   }
   
   
 
   ?>
   <script src="<?=DOMINIO?>/assets/aplicativo/peerjs/peerjs.min.js"></script>
   <script src="<?=DOMINIO?>/assets/js/index.js<?=$cache;?>"></script>

       
   </body>
</html> 