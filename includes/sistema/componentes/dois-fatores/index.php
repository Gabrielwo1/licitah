<div class="card card-nown w-100" style="max-width: 600px" id="cardDoisFatores">
   <div class="card-body p-0">
      <div class="row m-0 p-0">
         <div class="col-12 p-5">
            <h2 class="fs-20 fw-700">CÓDIGO DE VALIDAÇÃO</h2>
            <p class="fs-14 mb-4">Digite o código enviado para seu e-mail e/ou celular.</p>
            <div class="login-form mb-4">
               <div class="row mb-3">
                <?
                $i = 0;
                while($i < 6){
                    ?>
                       <div class="col p-1">
                           <div class="ratio ratio-1x1">
                                              <input type="text" class="form-control nown-control text-center " maxlength="1" data-index="<?=$i?>">
                           </div>
       
                  </div>
                    <?
                    $i++;
                }
                
                ?>
                 
               
               </div>
               <button class="btn w-100 btn-nown-style btn-n-primaria mb-3" disabled id="btnContinuar"> CONTINUAR </button>
            </div>
            <p class="text-center fs-14 mb-0">Não recebeu o código? <button class="btn goPage text-decoration-none text-primaria" data-page="recuperar-senha">Reenviar</button></p>
         </div>
      </div>
   </div>
</div>