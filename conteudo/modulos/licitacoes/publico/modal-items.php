<div class="modal primeiro fade" tabindex="-1" aria-labelledby="modalItems" id="modalItems">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background-color: #F6F8FC">
            <div class="modal-header">
                <h5 class="modal-title">Itens</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-striped table-sm ">
                    <thead>
                      <tr>
                        <th class="w-80" scope="col">Nome</th>
                        <th class="w-20" scope="col">Quantidade</th>
                      </tr>
                    </thead>
                    <tbody id="tableDados">
                        <?
                            $i = 0;
                            while($i < 5){
                                ?>
                                <tr>
                                    <td><div class="bg-carregando wi-50 he-20"></div></td>
                                    <td><div class="bg-carregando wi-50 he-20"></div></td>
                                </tr>
                                <?
                                $i++;
                            }
                        ?>
                      
                    </tbody>
                  </table>
                </div>
                <div class="d-flex w-100 justify-content-center align-items-end" id="paginacaoItem">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>