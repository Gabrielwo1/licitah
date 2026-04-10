<div class="card card-nown w-100" id="cardSenha">
   <div class="card-body p-0">
      <div class="row m-0 p-0">
         <div class="col-12 p-xl-5">
            <h2 class="fs-20 fw-700">DIGITE SUA SENHA</h2>
            <p class="fs-14 mb-4">Você está a um clique de usufruir do melhor que separamos para você.</p>
            <div class="login-form mb-4">
               <div class="bg-light p-3 mb-2 d-flex justify-content-between align-items-center login-box-credencial" style="border-radius: 10px;">
                  <div>
                     <div class="fw-700 fs-14 text-uppercase" id="tipo"></div>
                     <div class="fw-500 fs-12" id="credenciais"></div>
                  </div>
                  <div>
                     <button class="btn goPage" data-page="login"><i class="bi bi-x-lg"></i></button>
                  </div>
               </div>
               <div class="form-floating login-criar-senha mb-3">
                  <button class="btn btn-light btnTogglePass" data-target="loginSenha"><i class="bi bi-eye"></i><i class="bi bi-eye-slash"></i></button>
                  <input type="password" class="form-control nown-control" id="loginSenha" placeholder="Senha">
                  <label for="loginSenha">Senha</label>
               </div>
               <button class="btn w-100  mb-3 btn-nown-style btn-n-primaria" disabled id="btnContinuar"> ACESSAR </button>
               <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="manterLogado" checked>
                  <label class="form-check-label" for="manterLogado">Me mantenha conectado</label>
               </div>
            </div>
            <p class="text-center fs-14 mb-0">Esqueceu sua senha? <button class="btn text-decoration-none text-primary goPage" data-page="recuperar">Recupere aqui</button></p>
         </div>
      </div>
   </div>
</div>