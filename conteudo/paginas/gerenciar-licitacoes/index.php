<div class="container">
    <div class="suas-licitacoes">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="titulo">Suas licitações</h3>
        </div>
        <div class="row g-3">
            <?
                $i = 0;
                
                while($i < 12){
            ?>
            <div class="col-lg-4 col-12">
                <div class="licitacao">
                    <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                    <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                    <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                    <div class=" he-40 bg-carregando mt-2 w-100"></div>
    
                    <div class="progresso">
                        <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                        <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
            <?
                    $i++;
                }
            ?>
        </div>
        <div class="d-flex">
            
        </div>
    </div>
</div>