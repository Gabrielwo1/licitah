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

$cs = false;
if(is_dir(__DIR__."/../../conteudo/assets/sistema")){
    $csss = scandir(__DIR__."/../../conteudo/assets/sistema");
    foreach($csss as $item){
    if($item != "." && $item != ".."){
        $cs = $item;
    }
    }
}



if(file_exists(__DIR__."/../../conteudo/setup.json")){
    $setup = json_decode(file_get_contents(__DIR__."/../../conteudo/setup.json"), true);
}else{
    $setup = [];
}


function getDomain($force = false) {
    $wildcard = $_POST["wildcard"] ?? false;
    if($wildcard && $force){
        return $wildcard;
    }
    
    
    $domain = $_SERVER['HTTP_HOST'];
    $url ="https://" . $domain;
    return $url;
}



$setup["dominioprincipal"] = "https://".$setup["dominio"];
$setup["dominio"] = getDomain(false);


define("SETUP", $setup);



?>
    <div id="testededo">   
        <div class="load-container spinner" id="spinerDedo">
            <div class="color-spiner"></div>
            <div class="loader"></div>
        </div>
    </div>
       <?
    
       if(isset($_SESSION["adminAcess"]) && $_SESSION["adminAcess"]){
           ?>
           <div class="logged-as-top">
               <span>Navegando como <strong><?=$_SESSION["nome"];?></strong></span>
               <button id="btnBackAdmin" class="text-uppercase">VOLTAR AO <?=$_SESSION["adminAcess"]["nome"];?></button>
             </div>
           
           <?
       }
       
       

       ?>
      
      <div class="position-fixed start-0 h-100 d-none" id="lateral">
         <div class="card h-100 rounded-0 border-0">
            <div class="card-header rounded-0 border-0 shadow ">
               <div class="he-50" id="logoRetratil">
                 
               </div>
            </div>
            <div class="card-body">
               <div>
                  <div class="accordion" id="menuLateral">
                     <?
                        $i = 0;
                        while($i < 20){
                            echo '<div class="bg-carregando mb-2 he-50"></div>';
                            
                            $i++;
                        }
                        
                        ?>
                  </div>
               </div>
            </div>
            <div class="card-footer rounded-0" style="z-index: 99999999">
               <div class="he-30">
                  <div class="bg-carregando h-100">
                  </div>
               </div>
            </div>
         </div>
      </div>
           <div class="card h-100 position-absolute top-0 start-0 w-100 h-100 border-0 rounded-0 d-none" id="smartMenu">
               <div class="card-header border-0 bg-transparent">
                    
                            <div class="d-flex justify-content-between align-items-center">
                    <div><img src="https://nown.com.br/conteudo/uploads/imagens//2024/09/wo1x61dj3xskj4x-1725913809/media.webp" class="he-50"></div>
                    <button class="btn" id="voltaNormal"><i class="bi bi-x-lg fs-22"></i></button>
                </div>
               </div>
            <div class="card-body">
        
                
                <div class="accordion" id="menuLateral">
   <div class="menu-lateral-item">
      <div class="accordion-header">
         <div class="texto menu-lateral-title" style="width: 250px;">MÓDULOS</div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#DdIPNalaDz" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-journal-text text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Artigos</span></button></div>
      <div class="accordion-collapse collapse" id="DdIPNalaDz" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/artigos"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Todos os Artigos</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/artigos/artigo"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Adicionar Novo</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/artigos/categorias"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Categorias</span></a></ul>
         </div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#WvZgFlKcWG" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-arrow-repeat text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Assinaturas</span></button></div>
      <div class="accordion-collapse collapse" id="WvZgFlKcWG" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/assinaturas/planos"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Planos</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/assinaturas"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Assinaturas</span></a></ul>
         </div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#QUjjndRgqt" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-house text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Imóveis</span></button></div>
      <div class="accordion-collapse collapse" id="QUjjndRgqt" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/imoveis"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Todos os Imóveis</span></a></ul>
         </div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#FkApwOSyAi" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-folder text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Licitações</span></button></div>
      <div class="accordion-collapse collapse" id="FkApwOSyAi" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/licitacoes-pesquisadas"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Pesquisas</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Licitações</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/participantes"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Participantes</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/itens"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Itens</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/contratos"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Contratos</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/fornecedores"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Fornecedores</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/tarefas"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Tarefas</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/anotacoes"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Anotações</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/licitacoes/anexos"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Anexos</span></a></ul>
         </div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#NqSXowtGxv" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-egg-fried text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Restaurantes</span></button></div>
      <div class="accordion-collapse collapse" id="NqSXowtGxv" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/restaurantes"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Restaurantes</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/restaurantes/pratos"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Pratos</span></a></ul>
         </div>
      </div>
   </div>
   <div class="menu-lateral-item accordion-item border-0">
      <div class="accordion-header border-0"><button class="accordion-button p-3 gap-2 menuGrupo shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vyOnYDbRNy" aria-expanded="false" style="width: 100%;"><span class="icone"><i class="bi bi-people text-contrast"></i></span><span class="texto text-contrast fs-14 fw-600">Usuários</span></button></div>
      <div class="accordion-collapse collapse" id="vyOnYDbRNy" data-bs-parent="#menuLateral">
         <div class="accordion-body py-0 border-0 p-0" style="width: 100%;">
            <ul class="list-group list-group-flush border-0"><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/usuarios"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Usuários</span></a><a class="text-decoration-none text-contrast fs-14 fw-500 list-group-item border-0 itemDeMenu d-flex justify-content-start gap-2 align-items-center" href="https://nown.com.br/a/usuarios/funcoes"><span style="width: 5px; height: 5px; background-color: rgb(224, 227, 233); border-radius: 50%; display: block;"></span><span>Funções</span></a></ul>
         </div>
      </div>
   </div>

</div>
                
                
                
            </div>
 
            <div class="card-footer bg-transparent">
                <style>
                    #listaMobile{
                                    .dropdown-item {
                padding: 8px 10px !important;
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 15px;
                color: rgba(var(--bs-body-color-rgb), 0.8);
                    }
                    }
                </style>
                <div class="btn-group dropup">
  <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
    Dropup
  </button>
  <ul class="dropdown-menu" id="listaMobile">
 
  </ul>

</div>



             
            </div>
      </div>
      <div class="card h-100 rounded-0 border-0 position-relative"  id="corpo" style="margin-left: 0px">
         <div class="progress position-absolute top-0 w-100 start-0 rounded-0 bg-transparent" style="height:3px" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primaria"  id="barraCarregamento"></div>
         </div>
         <?
         if(v(["geral", "lgpd", "ativar"], false) && !isset($_SESSION["id"])){
             ?>
             <div class="nown-topbar animate__animated animate__fadeInDown d-none" id="lgpdBar" data-timer="<?=v(["geral", "lgpd", "timer"], 2000)?>">
    <div class="container">
        <div class="icone">
            <img src="<?=$setup["dominioprincipal"];?>/includes/midia/lgpd.png">
        </div>
        <div class="conteudo">
            <h5>Política Geral de Proteção de Dados</h5>
            <p>Ao utilizar nosso site, você concorda com todos os nossos termos e condições de uso baseado na LGPD.</p>
        </div>
        <div class="acoes">
            <button class="close">
                Fechar
            </button>
            <button href="#" class="btn btn-n-primaria btn-nown-style aceitar">
                <i class="bi bi-check-circle"></i> <span>Aceitar</span>
            </button>
            <button href="#" class="btn btn-n-secundaria btn-nown-style verTermos" type="button" data-bs-toggle="offcanvas" data-bs-target="#canvasLgpd" aria-controls="canvasLgpd">
                Ver Termos
            </button>
        </div>
    </div>
</div>

<style>
#canvasLgpd{
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="canvasLgpd" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="fs-16">LGPD</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">

  </div>
</div>
             <?

         }
         
         ?>
         
         <div class="card-header rounded-0 border-0 shadow" id="topo">
            <div class="he-50 d-flex justify-content-between align-items-center m-auto" style="max-width: 1320px">
               <div class="esquerda d-flex justify-content-between align-items-center gap-2">
                  <div class="bg-carregando he-50 wi-50"></div>
               </div>
               <div class="centro">
                  <div class="bg-carregando he-50 wi-100"></div>
               </div>
               <div class="direita d-flex justify-content-between align-items-center gap-1 gap-xl-4 header-actions">
                  <div class="bg-carregando he-30 wi-30"></div>
                  <div class="bg-carregando he-30 wi-30"></div>
                  <div class="bg-carregando he-30 wi-30"></div>
               </div>
            </div>
         </div>
         <div class="card-body overflow-y-auto overflow-x-hidden px-0" id="conteudo">
            <div class="d-flex justify-content-center align-items-center h-100">
               <div class="spinner-grow text-secondary" role="status">
                  <span class="visually-hidden">Carregando ...</span>
               </div>
            </div>
         </div>
         
         <div id="overflow-overlay">
        <div class="overlay-content">
            <div class="spinner-container">
                <div class="spinner-wrapper">
                    <div class="spinner-outer"></div>
                    <div class="spinner-inner"></div>
                    <div class="spinner-icon d-none">
                        <i class="bi bi-gear" id="spinner-icon"></i>
                    </div>
                </div>
                <div class="progress-dots">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            
            <h2 class="overlay-title text-center" id="overlay-title"></h2>
            <p class="overlay-subtitle text-center" id="overlay-subtitle"></p>
            <p class="overlay-message text-center" id="overlay-message"></p>
            
            <div class="overlay-actions" id="overlay-actions" style="display: none;">
                <button class="btn btn-n-primaria action-btn" onclick="hideOverlay()">
                    <i class="bi bi-check me-2"></i>Continuar
                </button>
                <button class="btn btn-outline-secondary action-btn" onclick="hideOverlay()">
                    <i class="bi bi-x me-2"></i>Cancelar
                </button>
            </div>
        </div>
    </div>

         <button id="backTop"></button>
         <div id="rodape" class="card-footer d-xl-none p-0 bg-dark rounded-0">
         </div>
      </div>
 
      <div class="position-fixed bottom-0 right-0"  style="right: 0px" id="toastContainer">
          <?
          /*
          <div class="card rounded-0 m-2" style="width: 100%; max-width: 400px">
              <div class="card-header d-flex justify-content-between">
                  <img src="https://nown.com.br/conteudo/uploads/imagens/newUploader/2024/03/qw7tjx5jg4sqh1a-1709920068/media.webp" style="height: 30px">
                  <button>Fechar</button>
              </div>
              <div class="card-body">
                  
              </div>
          </div>
          */
          ?>
      </div>
      
      <div id="canvasControler">
          <? 
          foreach(scandir(__DIR__."/../canvas/") as $item){
              if($item != ".." && $item != "."){
                   include __DIR__."/../canvas/".$item;
              }
              
          }
         
          ?>
      </div>
  
