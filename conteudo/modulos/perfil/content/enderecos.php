<div class="enderecos">
   <div class="card-endereco principal">
      <label for="e0"></label> <input type="radio" id="e0" name="enderecos" checked=""> 
      <div class="controller"> <span></span> </div>
      <div class="info">
         <h5>Nome do Endereço</h5>
         <p class="rua">R. Luis Zangiaconi, 89</p>
         <p class="bairro">Jardim Centenário</p>
         <p class="cidade">Poços de Caldas - MG</p>
         <p class="complemento">Casa 03 - Ponto de Referência</p>
      </div>
   </div>
   <div class="card-endereco ">
      <label for="e1"></label> <input type="radio" id="e1" name="enderecos"> 
      <div class="controller"> <span></span> </div>
      <div class="info">
         <h5>Nome do Endereço</h5>
         <p class="rua">R. Luis Zangiaconi, 89</p>
         <p class="bairro">Jardim Centenário</p>
         <p class="cidade">Poços de Caldas - MG</p>
         <p class="complemento">Casa 03 - Ponto de Referência</p>
      </div>
   </div>
   <div class="card-endereco ">
      <label for="e2"></label> <input type="radio" id="e2" name="enderecos"> 
      <div class="controller"> <span></span> </div>
      <div class="info">
         <h5>Nome do Endereço</h5>
         <p class="rua">R. Luis Zangiaconi, 89</p>
         <p class="bairro">Jardim Centenário</p>
         <p class="cidade">Poços de Caldas - MG</p>
         <p class="complemento">Casa 03 - Ponto de Referência</p>
      </div>
   </div>
   <button class="btn-add-endereco"> <i class="bi bi-plus-circle"></i> <span>Adicionar Endereço</span> </button> 
</div>

<div class="modal fade" id="modalNovoEndereco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" id="cardEnderecoModal">
      <div class="modal-header border-0">
        <h1 class="m-0 fs-16 fw-700">Novo Endereço</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formularioEndereco">
      <div class="modal-body d-flex flex-column gap-3">
         <div>
              <label class="form-label fs-14 fw-700 mb-1">Nome</label>
              <input class="form-control" id="nome">
         </div>
         <div>
              <label class="form-label fs-14 fw-700 mb-1">CEP</label>
              <div class="position-relative">
                  <input class="form-control ps-5 mascaraInput" id="cep" data-mascara="3">
                  <i class="bi bi-crosshair position-absolute top-50 translate-middle-y" style="left:15px" placeholder="Digite o CEP"></i>
              </div>
              
         </div>
             <div class="row">
             <div class="col-12 col-xl-6">
        
              <label class="form-label fs-14 fw-700 mb-1">Estado</label>
                 <div class="position-relative">
                    <select class="form-control ps-5" disabled id="estado">
                  
                  <?
                  $estadosBrasil = array(
                      'AC' => 'Acre',
                      'AL' => 'Alagoas',
                      'AP' => 'Amapá',
                      'AM' => 'Amazonas',
                      'BA' => 'Bahia',
                      'CE' => 'Ceará',
                      'DF' => 'Distrito Federal',
                      'ES' => 'Espírito Santo',
                      'GO' => 'Goiás',
                      'MA' => 'Maranhão',
                      'MT' => 'Mato Grosso',
                      'MS' => 'Mato Grosso do Sul',
                      'MG' => 'Minas Gerais',
                      'PA' => 'Pará',
                      'PB' => 'Paraíba',
                      'PR' => 'Paraná',
                      'PE' => 'Pernambuco',
                      'PI' => 'Piauí',
                      'RJ' => 'Rio de Janeiro',
                      'RN' => 'Rio Grande do Norte',
                      'RS' => 'Rio Grande do Sul',
                      'RO' => 'Rondônia',
                      'RR' => 'Roraima',
                      'SC' => 'Santa Catarina',
                      'SP' => 'São Paulo',
                      'SE' => 'Sergipe',
                      'TO' => 'Tocantins'
                      );

foreach($estadosBrasil as $sigla=>$estado){
    echo '<option value="'.$sigla.'">'.$estado.'</option>';
}
                  
                  ?>
                  
                  
              </select>
                  <i class="bi bi-geo position-absolute top-50 translate-middle-y" style="left:15px" placeholder="Digite o CEP"></i>
              </div>
              
              
            
         
             </div>
              <div class="col-12 col-xl-6">
        
              <label class="form-label fs-14 fw-700 mb-1">Cidade</label>
                <div class="position-relative">
                  <input class="form-control ps-5" disabled id="cidade">
                  <i class="bi bi-geo-alt-fill position-absolute top-50 translate-middle-y" style="left:15px" placeholder="Digite o CEP"></i>
              </div>
         
             </div>
         </div>
         
         <div>
              <label class="form-label fs-14 fw-700 mb-1">Endereço</label>
              <input class="form-control" disabled id="endereco">
         </div>
         <div>
              <label class="form-label fs-14 fw-700 mb-1">Bairro</label>
              <input class="form-control" disabled id="bairro">
         </div>
         <div class="row">
             <div class="col-12 col-xl-6">
        
              <label class="form-label fs-14 fw-700 mb-1">Número</label>
              <input class="form-control" id="numero">
         
             </div>
              <div class="col-12 col-xl-6">
        
              <label class="form-label fs-14 fw-700 mb-1">Complemento</label>
              <input class="form-control" id="complemento">
         
             </div>
         </div>
          
          <div>
              <label class="form-label fs-14 fw-700 mb-1">Descrição</label>
              <textarea class="form-control" id="descricao"></textarea>
         </div>
      </div>
      </form>
      <div class="modal-footer border-0">
        <button type="button" class="btn border-0 text-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-n-primaria btn-nown-style" disabled id="btnSalvar">Salvar</button>
      </div>
    </div>
  </div>
</div>