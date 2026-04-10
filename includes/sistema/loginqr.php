<?
if(!isset($_SESSION["id"])){
    ?>
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div id="containerCard" data-caminho="1">
   <div class="card card-nown overflow-hidden w-100" style="max-width: 700px; width: 100%;" id="cardLogin">
      <div class="card-body p-0">
         <div class="row m-0 p-0">
            <div class="col-12 p-5 d-flex flex-column justify-content-center gap-4">
               <h2 class="fs-20 fw-700 m-0 text-center">BAIXE NOSSO APP</h2>
               <p class="fs-14 m-0 text-center">Para logar sem senha, é necessário estar usando nosso aplicativo.</p>
               
               <div class="row">
                   <div class="col-12 col-xl-6">
                       <button class="btn w-100 btn-light btn-nown-style fw-500"><i class="bi bi-google-play"></i> Android</button>
                   </div>
                    <div class="col-12 col-xl-6">
                       <button class="btn w-100 btn-dark btn-nown-style fw-500 mt-3 mt-xl-0"><i class="bi bi-apple"></i> Apple</button>
                   </div>
               </div>
              
              
            </div>
            <div class="col-12 px-0">
               <div class="holder-qr-code-login text-light text-center d-flex justify-content-center gap-3 flex-column py-5">
                  <h3 class="fs-20 fw-700 m-0">ACESSE SEM APLICATIVO?</h3>
                  <p class="fs-14 m-0"><a class="text-light fw-700" href="/acesso">Acesse nosso site diretamente pelo navegador aqui</a></p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
    </div>
    <?
}else{
    ?>
    <style>
    #lateral{display: none !Important;}
    #corpo{margin-left: 0px !Important; }

    #conteudo{padding: 0px !Important;}

</style>
 
    <div class="position-relative overflow-hidden h-100" id="refQr">
        <div id="reader"></div>
        <div class="position-absolute w-100 top-0 start-0">
            <div class="text-center">
                <p class="w-75 mt-5 m-auto mb-2 text-light fw-700">Posicione a câmera sobre o QR code e aguarde</p>
                <lord-icon src="assets/icones-animados/scan-qr.json" trigger="loop" delay="500" style="width:50px;height:50px"></lord-icon>
            </div>
        </div>
    </div>
    <?
}
?>