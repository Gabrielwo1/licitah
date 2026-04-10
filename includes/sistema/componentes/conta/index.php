<?
session_start();
if(empty($_SESSION["id"])){
   
}
$usuario = $_SESSION["id"];
include __DIR__."/../../../../admin/config.php";
$diretorio = __DIR__."/../../../../";
$modulo = v(["paginas","multi-contas", "tipo"], false);
if(!is_dir($diretorio."/conteudo/modulos/".$modulo)){
    return;
}

if(!file_exists($diretorio."/conteudo/modulos/$modulo/admins/multiconta/lista.php")){
    echo "no tiene";
    return;
}

include $diretorio."/conteudo/modulos/$modulo/admins/multiconta/lista.php";

$resultado = listaItens($conn, false, true);
$lista = $resultado["lista"];

$variaveis = [];
if(file_exists($diretorio."/conteudo/modulos/$modulo/admins/multiconta/manifest.json")){
    $variaveis = json_decode(file_get_contents($diretorio."/conteudo/modulos/$modulo/admins/multiconta/manifest.json"), true);
}


?>

<style>
 .btnContas {
    position: relative;
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 2px solid transparent;
    
    .marcar {
        display: block;
        transition: opacity 0.2s ease-in-out;
    }
    
    .marcado {
        display: none;
        transition: opacity 0.2s ease-in-out;
    }
    
    &:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
    
    &.active {
        color: var(--nown-primaria);
        background-color: rgba(var(--bs-primary-rgb), 0.08);
        border: 2px solid var(--nown-primaria) !important;
        box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.15);
    }
    
    &.selecionado {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        border-left: 4px solid var(--nown-primaria);
        
        .marcar {
            display: none;
            opacity: 0;
        }
        
        .marcado {
            display: block;
            opacity: 1;
            animation: fadeIn 0.3s ease-in-out;
        }
    }
    
    &::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--nown-primaria);
        transition: width 0.3s ease;
    }
    
    &:hover::after,
    &.active::after {
        width: 100%;
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>


<div class="card card-nown" style="width: 600px; max-width: 100%" id="cardConta">
   <div class="card-body p-0">
      <div class="row m-0 p-0">
         <div class="col-12 p-5">
            <h2 class="fs-20 fw-700"><?=$variaveis["titulo"] ?? ""?></h2>
            <p class="fs-14 mb-4"><?=$variaveis["subtitulo"] ?? ""?></p>
            
            <div class="list-group list-group-flush">
            <?

            if($lista){
                foreach($lista as $id => $nome){
   
                    echo '
                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-start align-items-center gap-2 btnContas" data-foco="'.$id.'">
                    <span class="marcar"><i class="bi bi-circle fs-12"></i></span>
                    <span class="marcado"><i class="bi bi-check-circle fs-12"></i></span>
                    <span>'.$nome.'</span>
                    </button>';
                }
                
                echo '</div>';
                
                
                echo ' <button class="btn btn-n-primaria btn-nown-style d-block w-100 my-4" disabled id="btnContinuar">
                    Continuar
                </button>
                <div class="d-flex">
                    <button class="btn  d-block w-100 text-decoration-underline" id="addEmpresa">
                        Criar Nova Empresa
                    </button>
                    <button class="btn  d-block w-100 text-decoration-underline text-primaria" id="addVinculo">
                        Vincular em Empresa
                    </button>
                </div>
                ';
                
            }else{ 
                echo '<button class="btn btn-nown-style d-block w-100 btn-n-primaria text-uppercase mb-4" id="addEmpresa">'.$variaveis["textoPrimeira"].'</button>';
            }
      
            ?>
          
            
           

           
         </div>
      </div>
   </div>
      <div class="pb-5 px-5">
           <p class="fs-14 mb-0">Trocar de conta? <button id="deslogar" class="btn border-0 text-decoration-none text-primaria">Deslogar</button></p>
      </div>
</div>
</div>

<div class="modal fade modal-nown" id="modalNovaEmpresa" tabindex="-1" aria-labelledby="modalEmpresaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body d-flex flex-column gap-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Criar Empresa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div>
                    <input class="form-control" placeholder="Digite o CNPJ da empresa" data-mascara="2" id="cnpjNova">
                </div>
            
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-n-primaria btn-nown-style" id="btnCadastraEmpresa" disabled>Cadastrar Empresa</button>
                </div>
            </div>
    
        </div>
    </div>
</div>

<div class="modal fade modal-nown" id="modalNovoVinculo" tabindex="-1" aria-labelledby="modalVinculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body d-flex flex-column gap-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Criar Vinculo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div>
                    <input class="form-control" placeholder="Digite o código" id="hashNova">
                </div>
            
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-n-primaria btn-nown-style" id="btnCadastraVinculo" disabled>Vincular em Empresa</button>
                </div>
            </div>
    
        </div>
    </div>
</div>

