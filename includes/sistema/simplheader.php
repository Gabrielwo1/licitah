<?
include __DIR__."/../../admin/logo.php";
?>


<style>
    #lateral{display: none !Important;}
    #corpo{margin-left: 0px !Important; }
    #topo{display: none !Important;}
    #rodape{display: none !Important;}
    #conteudo{padding: 0px !Important;}
    #topoPersonalizadoDeslogado{display: none!important;}
    #topoPersonalizadologado{display: none!important;}

</style>


<div class="card rounded-0 border-0">
    <div class="card-body">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                   <a href="<?=SETUP["dominio"];?>">
                        <?
                    echo '<div class="d-block d-xl-none">';
                    if($logoLight){
                        echo '<img src="'.$logoLight.'" class="onlyLight he-50">';
                    }
                    if($logoDark){
                        echo '<img src="'.$logoDark.'" class="onlyDark he-50">';
                    }
                    echo '</div>';
                    
                    echo '<div class="d-none d-xl-block">';
                    if($logoMarcaLight ){
                        echo '<img src="'.$logoMarcaLight.'" class="onlyLight he-50">';
                    }
                    
                    if($logoMarcaDark){
                         echo '<img src="'.$logoMarcaDark.'" class="onlyDark he-50">';
                    }
                    echo '</div>';
                    
               
                    ?>
              
                   </a>

                </div>
                <div id="btnAreas" class="d-flex justify-content-end gap-2">
                </div>
            </div>
        </div>
    </div>
</div>