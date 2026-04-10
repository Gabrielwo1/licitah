<?


include __DIR__."/../../admin/logo.php";


if(v(["paginas", "acesso" ,"logo"], "logo") == "logo"){
    $light = $logoLight;
    $dark = $logoDark;
}else{
    $light = $logoMarcaLight;
    $dark = $logoMarcaDark;
}


if($light || $dark){
   $logo = '<div class="containerLogo">
                <img src="'.$light.'" class="light" style="height: 75px" class="animate__animated animate__fadeIn">
                <img src="'.$dark.'" class="dark" style="height: 75px" class="animate__animated animate__fadeIn">
            </div>'; 
}else{
    $logo = '<div class="containerLogo">
      <img src="'.$light.'" class="d-none light" style="height: 75px" class="animate__animated animate__fadeIn">
                <img src="'.$dark.'" class="d-none dark" style="height: 75px" class="animate__animated animate__fadeIn">
    </div>';
}









?>
<style>
.btnFlowContato{
 display: none !Important;   
}
        #corpo{
            margin-left: 0px!important;
            
        }
        
        #lgpdBar{
            display: none !important; 
        }
        
            #topoPersonalizadoDeslogado{display: none!important;}
    #topoPersonalizadologado{display: none!important;}
        
    body.light {
        .containerLogo .light{
            display: block;
        }
        
        .containerLogo .dark{
         display: none;
        }
    }
    
     body.dark {
        .containerLogo .light{
            display: none;
        }
        
        .containerLogo .dark{
         display: block;
        }
    }
    
    .grecaptcha-badge{
    display: block;
}

@media (max-width: 1199.98px) {

  .card-nown {

    background-color: transparent;
    box-shadow: none !important;

  }
  
  .maxMobile{
      width: 90%;
  }
}


<?
if(v(["paginas","acesso","desabilitarcard"], false)){
    echo '.card-nown {
    background-color: transparent;
    box-shadow: none !important;

  }';
}
    





if(v(["paginas","acesso","backgroundpersonalizado"], false) && v(["paginas","acesso","corbackground"], false)){
    echo '
    #conteudo{
    background-color: '.v(["paginas","acesso","corbackground"], "red").'!important;
    }
    
    ';
}

?>


#canvaPoliticas{
        p{
            font-size: 12px !important;
            text-align-last: start !important; 
            text-align: justify !important; 
        }
        
        li{
           font-size: 12px !important;
            text-align-last: start !important; 
            text-align: justify !important;  
        }
        
         h2{
           font-size: 14px !important; 
           font-weight: 700 !important; 
        }
        
        h3{
           font-size: 14px !important; 
           font-weight: 700 !important; 
        }
        
    }



</style>

<?
$meia = "container";
switch(intval(v(["paginas","acesso","layout"], 1))){
    case 1:
        $classeLayout = "justify-content-xl-center";
        break;
    case 2:
        $classeLayout = "justify-content-xl-start";
        $meia = "d-flex justify-content-center w-100 w-xl-50";
        break;
    case 3:
        $classeLayout = "justify-content-xl-end";
        $meia = "d-flex justify-content-center  w-100 w-xl-50 ms-auto";
        break;
}



$bgImg = "";
$front = true;

if(v(["paginas","acesso","ativarimgbg"], false)){
    switch(intval(v(["paginas", "acesso", "layoutbg"], 1))){
        case 1:
            $w = "w-xl-100";
            $p = "start-0";
            $front = false;
            break;
        case 2:
            $w = "w-xl-50";
            $p = "start-0";
            
            if(intval(v(["paginas","acesso","layout"], 1)) == 2){
                $front = false;
            }
            
            
            break;
        case 3:
            $w = "w-xl-50";
            $p = "end-0";
            
            if(intval(v(["paginas","acesso","layout"], 1)) == 3){
                $front = false;
            }
            
            
            break;
    }
    
    $img = "";
    if(v(["paginas", "acesso", "imgbg"], false)){
        $img = v(["paginas", "acesso", "imgbg"] , false);
        $obj = json_decode($img, true);
        
        $bgImg = '<div class="d-none d-xl-block position-absolute w-100 '.$w.' h-100 '.$p.' top-0" style=" pointer-events: none; background-image: url('.SETUP["dominio"].'conteudo/uploads/'.$obj[0].'); background-size: cover; background-position: center center;"></div>';

    }
    

}



?>



  <?
    if(!$front){
        echo $bgImg;
    }
    ?>
<div class="<?=$meia;?> container-acesso position-relative">
  
    
    <div class="div-acesso d-flex justify-content-center <?=$classeLayout;?> align-items-center">
        <div class="d-flex flex-column gap-4 maxMobile">
            <div class="holder-login-logo text-center">
                <a href="/" class="text-decoration-none">
                    <?=$logo;?>
                </a>
            </div>

                <div id="containerCard" data-caminho="<?=count($this->caminho)?>"></div>

        </div>
    </div>
</div>

<?
if($front){
    echo $bgImg;
    
    }
?>



<div class="offcanvas offcanvas-start" tabindex="-1" id="canvaPoliticas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title"></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<div>
   
</div>
  </div>
    <div class="offcanvas-footer p-2 d-flex justify-content-end">
    <button class="btn btn-n-primaria d-flex justify-content-center align-items-center gap-2" id="btnAceitarPolitica"><i class="bi bi-check-circle"></i> <span>Aceitar</span></button>
  </div>
</div>
