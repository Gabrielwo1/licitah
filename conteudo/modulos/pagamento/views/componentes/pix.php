<div style="max-width: 600px" class="row-metodo-checkout m-auto animate__animated animate__bounceInLeft">
   <div class="card card-nown card-nown-checkout card-nown-pagamento">
      <div class="card-body">
         <h4 class="d-flex align-items-center justify-content-center gap-2"><span>Pedido</span> <span id="idPedido" class="fs-14"></span></h4>
         <p>Realize o pagamento utilizando o QR Code abaixo ou utilizando a funcionalidade Pix Copia e Cola.</p>
         <div class="holder-pagamento">
            <div class="qr">
               <span><i class="bi bi-phone-fill"></i> Aponte a câmera</span>
               <div id="qrcode" class="he-300 wi-300 d-flex justify-content-center align-items-center">
                   
               </div>
            </div>
            <div class="input-copypaste">
               <input type="text" class="form-control" value="" readonly id="chavePix">
               <button class="btn btn-n-primaria btn-nown-style" id="copiaPix"><i class="bi bi-copy"></i></button>
            </div>
            <div class="total-compra">
               <h5>Pagamento</h5>
               <span class="total">R$ <span id="totalpreco"></span></span>
            </div>
         </div>
         <div class="countdown-pagamento">
            <h5>Expira em <span>05:00</span></h5>
            <span class="countdown remover_essa_classe"><span></span></span>
            <small>Redirecionamento automático após a confirmação do pagamento.</small>
         </div>
      </div>
   </div>
</div>