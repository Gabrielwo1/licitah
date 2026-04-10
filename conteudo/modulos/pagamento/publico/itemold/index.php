<style>
    #lateral{display: none !important;}
    #corpo{margin-left: 0px!important;}
    #topo{display: none!important;}
    #rodape{display: none!important;}
</style>

<div class="container">
   <div class="m-auto" style="max-width: 1000px">
      <div class="row">
         <div class="col-12 col-xl-8">
            <div class="card card-nown d-none" id="cancelado">
               <div class="card-body">
                  <h2 class="fs-20 text-center">Pedido Cancelado</h2>
                  <p class="w-75 m-auto text-center">
                     <strong>O pedido foi cancelado por motivo de segurança.</strong>
                     Quando um pedido é feito, ele deve ser concluido em menos de 5 minutos.
                  </p>
                  <div class="text-center mt-2">
                     <button class="btn btn-n-primaria btn-nown-style">
                     Voltar para o Carrinho
                     </button>
                  </div>
               </div>
            </div>
            <div id="timer">
            </div>
            <div class="card card-nown d-none" id="getways">
               <div class="card-body">
                  <h2 class="text-center fs-22 fw-700 mb-3 mt-2">Escolha um meio de pagamento</h2>
                  <div class="accordion accordion-flush" id="accordionExample">
                     <div class="accordion-item">
                        <h2 class="accordion-header">
                           <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              <div class="d-flex justify-content-start gap-3 align-items-center">
                                 <span class="d-flex justify-content-center align-items-center fs-20 wi-50 he-50 border rounded "><i class="bi bi-qr-code"></i></span>
                                 <span class="fs-18 text-uppercase m-0 fw-700">PIX</span>
                              </div>
                           </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                           <div class="accordion-body">
                              <div class="card border-0">
                                 <div class="card-body">
                                    <div style="max-width: 250px" class="m-auto">
                                       <div class="ratio ratio-1x1 border" style="background-image: url(https://14-imagem-777.s3.sa-east-1.amazonaws.com/20232614T21CX7RD0167S1C2112533W0XD74XO4R0CX1C51C.png); background-size: cover; background-position: center center;"></div>
                                    </div>
                                    <input class="form-control mt-4" value="00020101021226990014br.gov.bcb.pix2577pix.bpp.com.br/23114447/qrs1/v2/01Hq3RtzvnEwzUFMJN9qlYWBh9wlBQ9cg2zvZlvWjwuPw52040000530398654045.005802BR5925FORTRAM SOLUCOES WEB LTDA6009SAO PAULO62070503***63047037">
                                    <div class="d-flex justify-content-end mt-4">
                                       <button class="btn btn-n-primaria  btn-nown-style">Copiar </button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <h2 class="accordion-header">
                           <button class="accordion-button collapsed fs-14 text-uppercase m-0 fw-500" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              <div class="d-flex justify-content-start gap-3 align-items-center">
                                 <span class="d-flex justify-content-center align-items-center fs-20 wi-50 he-50 border rounded "><i class="bi bi-credit-card-2-front-fill"></i></span>
                                 <span class="fs-18 text-uppercase m-0 fw-700">Cartão de Crédito</span>
                              </div>
                           </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                           <div class="accordion-body">
                              <div id="cartaoForm">
                                 <div id="cartao" class="my-5">
                                 </div>
                                 <div class="row">
                                    <div class="col-12">
                                       <label class="form-label fs-12 mb-1">Número do Cartão</label>
                                       <input class="form-control" placeholder="1234 1234 1234 1234" id="numero">
                                    </div>
                                    <div class="col-12 col-xl-6 mt-3">
                                       <label class="form-label fs-12 mb-1">Data de Vencimento</label>
                                       <input class="form-control" placeholder="mm/aa" id="expira">
                                    </div>
                                    <div class="col-12 col-xl-6 mt-3">
                                       <label class="form-label fs-12 mb-1">Código de Segurança</label>
                                       <div class="position-relative">
                                          <input class="form-control" placeholder="123" id="cvc">
                                          <span class="position-absolute top-50 translate-middle-y" style="right: 10px"><i class="bi bi-credit-card"></i></span>
                                       </div>
                                    </div>
                                    <div class="col-12  mt-3">
                                       <label class="form-label fs-12 mb-1">Nome do Titular do Cartão</label>
                                       <input class="form-control" id="nome">
                                    </div>
                                    <div class="col-12 mt-3">
                                       <label class="form-label fs-12 mb-1">Documento do Titular</label>
                                       <div class="input-group">
                                          <select class="input-group-text form-select w-25">
                                             <option>CPF</option>
                                             <option>CNPJ</option>
                                          </select>
                                          <input type="text" class="form-control w-75" placeholder="999.999.999-99">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="d-flex justify-content-end mt-4">
                                    <button class="btn btn-n-primaria btn-nown-style">Pagar</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <h2 class="accordion-header">
                           <button class="accordion-button collapsed fs-14 text-uppercase m-0 fw-500" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              <div class="d-flex justify-content-start gap-3 align-items-center">
                                 <span class="d-flex justify-content-center align-items-center fs-20 wi-50 he-50 border rounded "><i class="bi bi-currency-bitcoin"></i></span>
                                 <span class="fs-18 text-uppercase m-0 fw-700">CRIPTOMOEDAS</span>
                              </div>
                           </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                           <div class="accordion-body">
                              <div class="row my-4">
                                 <div class="col-12 col-xl-4">
                                    <div class="card card-nown">
                                       <div class="card-header text-center bg-transparent border-0">
                                          <div class="text-center my-2">
                                             <img src="https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/btc.png" class="wi-75"> 
                                          </div>
                                          <h3 class="fs-16 fw-700 text-center">BITCOIN</h3>
                                       </div>
                                       <div class="card-body px-5">
                                          <div class="ratio ratio-1x1" style="background-image: url(https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/btcQr.png); background-size: cover; background-position: center center;">
                                          </div>
                                       </div>
                                       <div class="card-footer bg-transparent border-0 pt-0">
                                          <div class="fs-12 text-center p-2">13KB1nHk3Qf3ENNgEva1snzb3hguePDdv7</div>
                                          <p class="fs-14 text-center text-secondary mb-0 mt-1 fw-700">Usar a Rede BTC</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12 col-xl-4">
                                    <div class="card card-nown">
                                       <div class="card-header text-center bg-transparent border-0">
                                          <div class="text-center my-2">
                                             <img src="https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/eth.png" class="wi-75"> 
                                          </div>
                                          <h3 class="fs-16 fw-700 text-center">ETHERIUM</h3>
                                       </div>
                                       <div class="card-body px-5">
                                          <div class="ratio ratio-1x1" style="background-image: url(https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/btcQr.png); background-size: cover; background-position: center center;">
                                          </div>
                                       </div>
                                       <div class="card-footer bg-transparent border-0 pt-0">
                                          <div class="fs-12 text-center p-2">13KB1nHk3Qf3ENNgEva1snzb3hguePDdv7</div>
                                          <p class="fs-14 text-center text-secondary mb-0 mt-1 fw-700">Usar a Rede BTC</p>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12 col-xl-4">
                                    <div class="card card-nown">
                                       <div class="card-header text-center bg-transparent border-0">
                                          <div class="text-center my-2">
                                             <img src="https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/usdt.png" class="wi-75"> 
                                          </div>
                                          <h3 class="fs-16 fw-700 text-center">USDT</h3>
                                       </div>
                                       <div class="card-body px-5">
                                          <div class="ratio ratio-1x1" style="background-image: url(https://ecvitoria.com/conteudo/modulos/contribuicoes/midia/btcQr.png); background-size: cover; background-position: center center;">
                                          </div>
                                       </div>
                                       <div class="card-footer bg-transparent border-0 pt-0">
                                          <div class="fs-12 text-center p-2">13KB1nHk3Qf3ENNgEva1snzb3hguePDdv7</div>
                                          <p class="fs-14 text-center text-secondary mb-0 mt-1 fw-700">Usar a Rede BTC</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <h2 class="accordion-header">
                           <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefuor" aria-expanded="false" aria-controls="collapsefuor">
                              <div class="d-flex justify-content-start gap-3 align-items-center">
                                 <span class="d-flex justify-content-center align-items-center fs-20 wi-50 he-50 border rounded "><i class="bi bi-paypal"></i></span>
                                 <span class="fs-18 text-uppercase m-0 fw-700">PayPal</span>
                              </div>
                           </button>
                        </h2>
                        <div id="collapsefuor" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                           <div class="accordion-body">
                              <div id="paypal-button-container"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-12 col-xl-4">
            <div class="card card-nown py-3">
               <div class="card-body">
                  <h2 class="text-center fs-22 fw-700">Resumo do Pedido</h2>
                  <ul class="list-group list-group-flush" id="listaProdutos">
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="fw-500">Total</div>
                        <div class="fw-700 fs-22">R$ <span id="totalPrice"></span></div>
                     </li>
                  </ul>
                  <div class="text-center">
                     <p class="fs-12 my-3 w-75 m-auto">Seu Pagamento está seguro com criptografia de ponta a ponta e garantido por todas as leis do CDC.</p>
                     <div>
                        <h2 class="text-center fs-18 fw-400 text-secondary d-flex justify-content-center align-items-center gap-2"><span class="material-symbols-outlined">lock</span> <span>Pagamento Seguro</span></h2>
                     </div>
                  </div>
               </div>
            </div>
            <button class="btn btn-n-primaria w-100 mt-4" id="emularPagamento">Pagar</button>
         </div>
      </div>
   </div>
</div>
<div class="position-fixed top-0 start-0 w-100  h-100 d-none justify-content-center align-items-center" style="background-color: rgba(0, 0, 0, 0.8);" id="processandoPagamento">
   <div>
      <div class="d-flex justify-content-center">
         <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
         </div>
      </div>
      <h2 class="mt-4">Processando Pagamento</h2>
   </div>
</div>