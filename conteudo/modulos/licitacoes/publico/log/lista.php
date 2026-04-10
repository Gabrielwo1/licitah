<div class="container-fluid" id="pageLogs">
    <div class="d-flex justify-content-center my-4" id="sessao-topo" style="scroll-margin-top: 80px">
        <div>
            <h1>Histórico de Notificações</h1>
        </div>
    </div>
    <div class="container">
        <div class="notificacoes">
            <div class="row g-3" id="notificacoesWrap">
                <?
                    $i = 0;
                    while($i < 8){
                ?>
                    <div class="col-xl-6 col-12">
                        <div class="notificacoes-wrap">
                            <div class="notificacao-2 flex-column gap-1">
                                <div class="tipo-acao">
                                    <div class="d-flex justify-content-between align-items-center">
    
                                        <div class="bg-carregando wi-150 he-20"></div>
    
                                        <div class="bg-carregando wi-150 he-20"></div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="tarefa-wrap d-flex flex-column gap-1">
                                        <div class="bg-carregando w-100 he-20"></div>
                                        <div class="bg-carregando w-100 he-20"></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                <?
                        $i++;
                    }
                ?>
            </div>
            <div id="paginacao">
                
            </div>
        </div>
    </div>
</div>