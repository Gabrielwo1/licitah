<style>
    #ocPesquisa{
        width:90% !important;
        
        @media(min-width: 992px){
            width:600px !important;
        }
        
    }
    
    .iziToast-wrapper {
        z-index: 999999 !important;
    }
    
    #lista-de-pesquisa {
        opacity: 0; /* Inicialmente invisível */
        visibility: hidden; /* Esconde o elemento sem ocupar espaço */
        transition: opacity 0.3s ease, visibility 0.3s ease; /* Transição suave */
        position:fixed;
        top:150px;
        z-index:999999;
        left: 50% !important;
        transform: translatex(-50%);
        width:90% !important;
        
        @media(min-width: 992px){
            width:600px !important;
        }
        
        
        &.visivel{
            opacity: 1;
            visibility: visible;
        }
        
        &.invisivel{
            opacity: 0;
            visibility: hidden;
        }
    }
</style>

<div class="nown-canvas" id="ocPesquisa">
   <div class="nown-canvas-content">
        <header>
            <h3>Pesquisa</h3>
            <button class="btn-close-nown-canvas" data-close-canvas=""></button>
        </header>
        <main>
            <div class="d-flex gap-2 justify-content-between mb-3">
                <div class="position-relative flex-fill">
                    <input type="search" id="inputPesquisadorGlobal" class="form-control-lg w-100 form-control rounded-pill" placeholder="Faça sua pesquisa...">
                    <div class="position-absolute h-100 end-0 top-0">
                        <button title="pesquisar" id="pesquisadorGlobalNown" class="btn btn-n-primaria btn-lg rounded-circle h-100"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div>
                    <button title="pesquisa por Fala" id="btnVozNown" class="btn btn-n-secundaria btn-lg rounded-circle h-100"><i class="bi bi-mic-fill"></i></button>
                </div>
            </div>
        </main>
    </div>
    <div class="controller"></div>
</div>

<div id="lista-de-pesquisa" class="invisivel card card-nown rounded-top-0">
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?
                $i = 0;
                
                while($i < 10){
                    ?>
                    <li class="list-group-item"><button class="btn w-100 text-start"><div class="bg-carregando w-100 he-20"></div></button></li>
                    <?
                    $i++;
                }
            ?>
        </ul>
    </div>
</div>