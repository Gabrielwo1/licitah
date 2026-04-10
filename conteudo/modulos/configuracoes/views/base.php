<?
function toKebabCase($text) {
    // Normaliza a string removendo acentos e caracteres especiais
    $normalizedText = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

    // Remove qualquer coisa que não seja letra ou espaço
    $normalizedText = preg_replace('/[^a-zA-Z\s]/', '', $normalizedText);

    // Transforma a string para minúsculas
    $normalizedText = strtolower($normalizedText);

    // Divide a string em palavras separadas por espaços
    $words = explode(' ', $normalizedText);

    // Remove espaços extras e junta as palavras com um hífen entre elas
    $kebabCase = implode('-', array_filter($words, function($word) {
        return trim($word) !== '';
    }));

    return $kebabCase;
}
?>

<style>

    #rodape{
        display:none !Important;
    }
    .btnsConfig {
        border: 0px !important;
    }
    
    .btnsConfig:hover{
        background-color: var(--c-hover) !important;
    }
 
    
    .btnsConfig.ativo{
        background-color: var(--c-active) !important;
        color: white;

    }
    
    @media(max-width: 1200px){
        #conteudo{
            padding-left: 0px;
            padding-right: 0px;
        }
        #boxConfig{
            padding: 0px;
        }
        
      
    }
</style>

<div class="container">
    <div class="h-100">
   <div class="row h-100">
     
      <div class="col-12 col-xl-3 p-0" id="opcoesConfig">
         <div class="card card-nown h-100 position-relative">
                <div class="card-body overflow-y-auto">
                    <div class="accordion rounded-0" id="menuLateralConfig">
               <?
               $i = 0;
                  foreach($configs as $modulo){
                      $i++;
                      $id = "bloco-".$i;
                      $cor = $modulo["cor"] ?? "danger";
                     ?>
               <div class="accordion-item border-0" data-nome="<?=$modulo["nome"]?>" data-icone="<?=$modulo["icone"] ?>" data-cor="<?=$cor;?>">
                  <h2 class="accordion-header">
                     <button class="btnBig bg-contrast accordion-button py-2 px-3 collapsed d-flex justify-content-start gap-2 aling-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#<?=$id?>" aria-expanded="false" aria-controls="collapseOne" style="width: 100%;">
                        <div class="wi-40 he-40 text-<?=$cor;?>-emphasis bg-<?=$cor;?>-subtle  d-flex justify-content-center align-items-center rounded-circle">
                           <i class="<?=$modulo["icone"] ?>"></i>
                        </div>
                        <span class="texto text-contrast fs-16" ><?=$modulo["nome"] ?></span> 
                     </button>
                  </h2>
                  <div class="accordion-collapse collapse" id="<?=$id?>" data-bs-parent="#menuLateralConfig">
                     <div class="accordion-body p-0" style="width: 100%;">
                        <ul class="list-group list-group-flush">
                           <?
                              foreach($modulo["subs"] as $valor){
                                  echo '<li class="list-group-item"> 
                                  <button class="btn btn-lg w-100 btnsConfig fs-14 text-start rounded-0 selecionado d-flex justify-content-start gap-2 align-items-center"  data-n="'.toKebabCase($modulo["nome"]).'" data-g="'.toKebabCase($valor["nome"]).'" data-grupo="'.$valor["nome"].'" style="--c-hover: var(--bs-'.$cor.'-bg-subtle); --c-active: var(--bs-'.$cor.')">
                                  
                                  <span class="icon-btnsConfig material-symbols-outlined text-'.$cor.'">'.(isset($valor["icone"]) ? $valor["icone"] : 'chevron_right').'</span> 
                              
                                  <span>'.$valor["nome"].'</span>
                                  </button> </li>';
                              }
                              
                              ?>
                        </ul>
                     </div>
                  </div>
               </div>
               <?
                  }
                  ?>
            </div>
                </div>
          </div>
          
      </div>
      <div class="col-12 col-xl-9" id="boxConfig">
         <div class="card h-100 bg-transparent border-0">
            <div class="card-header bg-transparent border-0 pt-0">
               <div class="card card-nown text-primary-emphasis bg-primary-subtle border border-primary-subtle" id="headerControler">
                  <div class="card-header border-0 bg-transparent d-flex justify-content-start align-items-center gap-2 py-4">
                     <div>
                        <span class="material-symbols-outlined icone"></span>
                     </div>
                     <h2 class="m-0 fs-20 fw-500"><span class="modulo"></span> - <span class="grupo"></span></h2>
                  </div>
               </div>
            </div>
            <div class="card-body d-flex flex-column gap-4 overflow-y-auto">
               <div class="card card-nown h-100">
                  <div class="card-body d-flex flex-column gap-3 overflow-y-auto py-5 px-3 p-xl-5" id="corpoConfig">
                  </div>
               </div>
            </div>
            <div class="card-footer bg-transparent border-0 pb-0">
               <div class="card card-nown">
                  <div class="card-body border-0">
                     <div class="d-flex justify-content-between aling-items-center gap-2">
                         <div class="d-block d-xl-none"> <button class="btn"><lord-icon
    src="https://cdn.lordicon.com/xkmjbjuw.json"
    trigger="loop"
    delay="2000"
    style="width:50px;height:50px">
</lord-icon></button></div>
                        <div class="d-flex justify-content-end justify-content-xl-between w-100 gap-2">
                            <button title="Resetar" class="btn btn-nown-style btn-danger d-flex justify-content-center align-items-center gap-2" id="resetar" disabled>
                        <i class="bi bi-trash3"></i>
                        <span class="d-none d-xl-block">Resetar</span>
                        </button>
                        <div class="d-flex justify-content-end aling-items-center gap-2">
                           <button title="Recuperar" class="btn btn-nown-style btn-primary d-flex justify-content-center align-items-center gap-2" id="restaurar" disabled>
                            <i class="bi bi-hourglass-bottom"></i>
                           <span class="d-none d-xl-block">Recuperar</span>

                           </button>
                           <button title="Salvar" class="btn btn-nown-style btn-success d-flex justify-content-center align-items-center gap-2" id="salvar" disabled>
                            <i class="bi bi-floppy"></i>
                           <span class="d-none d-xl-block">Salvar</span>
                           </button>
                        </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>

