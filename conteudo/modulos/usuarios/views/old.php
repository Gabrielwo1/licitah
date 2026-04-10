<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fs-22 fw-900 mb-1">Funções</h2>
            <p class="fs-16 fw-400 m-0">Gerencie as funções de usuário do sistema</p>
        </div>
        <div>
            <button class="btn btn-nown-style btn-n-primaria" id="novaFuncao" disabled>Adicionar Função</button>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row m-0 p-0" id="lista">
   
    </div>
</div>



<div class="modal fade" id="modalFuncoes" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Atualizar Função</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
            <label class="fs-16 fw-900 mb-2" for="nomeDaFuncao">Digite o nome da Função</label>
            <input class="form-control" id="nomeDaFuncao">
        </div>
        <div class="mt-4">
             <label class="fs-16 fw-900 mb-2" for="tipoDaFuncao">Tipo Função</label>
            <select class="form-control" id="tipoDaFuncao">
                <option value="1">Equipe</option>
                <option value="2">Cliente</option>
            </select>
        </div>
        <h2 class="fs-16 fw-900 mt-4">Permissões</h2>
        <ul class="list-group list-group-flush" id="listaModulos">
            
        </ul>
      </div>
      <div class="modal-footer d-flex justify-content-center gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> 
        <button type="button" class="btn btn-n-primaria" id="salvarFuncao">Salvar</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modalDeletaFuncao" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Deletar Função</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
  <h4 class="alert-heading fs-20">MUITA ATENÇÃO!</h4>
  
  <p>Ao deletar essa função, todos os usuários vinculados a ela serão associados a nova função selecionada. Caso haja um sistema de cobrança recorrente, ele será vinculado ao novo plano. As configurações da função deletada, não são exportadas para a função vinculada.</p>
  <hr>
  <p class="mb-0">Essa ação é irreversivel e pode afetar todo o seu <strong>modelo de negócios</strong>! </p>
</div>

        <div class="row">
            <div class="col-xl-6">
                <label for="funcaoDeletada" class="fs-16 fw-900 mb-2">Função a ser deleta</label>
                <select class="form-select" disabled id="funcaoDeletada"></select>
            </div>
              <div class="col-xl-6">
                <label class="fs-16 fw-900 mb-2" for="funcaoVinculada">Função a ser vinculada</label>
                <select class="form-select" id="funcaoVinculada"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnDeleta" disabled>Apagar</button>
      </div>
    </div>
  </div>
</div>