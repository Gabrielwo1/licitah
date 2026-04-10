<?

$conn = $this->conn;

include __DIR__."/../../../../admin/config.php";

$itens = [];
if(v(["seguranca", "autenticacao-de-dois-fatores", "ativar"], false)){
    array_push($itens, ["titulo"=>"Autenticação via Telefone / eMail", "texto"=>"Receba códigos de verificação via WhatsApp e/ou eMail.", "icone"=>"bi bi-envelope", "btnAction"=>"viaNumero"]);
}

if(v(["seguranca", "authenticator", "ativo"], false)){
    array_push($itens,  ["titulo"=>"Autenticação via metodo TOTP", "texto"=>" Aumente a segurança com um aplicativo  autenticador.", "icone"=>"bi bi-phone","btnAction"=>"viaAutenticator"]);
}

if(v(["seguranca", "impressao-digital", "ativo"], false)){
    array_push($itens,  ["titulo"=>"Impressão Digital", "texto"=>" Ativa a validação via impressão digital, aumentando a segurança da sua conta.", "icone"=>"bi bi-fingerprint","btnAction"=>"viaDigital"]);
}




?>

<div class="card border-0">
    <div class="card-header bg-transparent">
        <h2 class="fs-18 fw-700 m-0">Autentificaçao de 2 fatores</h2>
        <p class="fs-14 m-0">Aumente a segurança da sua conta.</p>
    </div>
    
    <div class="card-body">
        
        <div class="d-flex flex-column gap-3">
                            <ul class="list-group list-group-flush">
            <?
        
            
            foreach($itens as $item){

  echo '<li class="list-group-item py-3">
  <div class="row">
  <div class="col-1 d-flex justify-content-center align-items-center">
  <i class="'.$item["icone"].' fs-30"></i>
  </div>
                        <div class="col-8 d-flex flex-column gap-1">
                            <h2 class="m-0 fs-16">'.$item["titulo"].'</h2>
                            <p class="m-0 fs-14">
                                '.$item["texto"].'
                            </p>
                        </div>
                        <div class="col-3 d-flex justify-content-end align-items-center">
                            <div class="d-flex justify-content-between align-items-center btnAcaoSecure" data-target="'.$item["btnAction"].'">
                            
                            </div>
                        </div>
                    </div>
                    </li>';


            }
            
            ?>
            </ul>

        </div>
        
        
    </div>
    
    
    
</div>

<div class="modal fade" id="modalTotp" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-18 fw-500 m-0" id="exampleModalLabel">Autenticação</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">
  <li class="list-group-item"> 
  <div class="d-flex align-items-center justify-content-start gap-3">
      <i class="bi bi-1-square fs-14"></i>
      <div class="fs-14">
           Instale um aplicativo autenticador no seu dispositivo móvel
      </div>
  </div>
 
  </li>
  <li class="list-group-item">
      
       <div class="d-flex align-items-center justify-content-start gap-3">
      <i class="bi bi-2-square fs-14"></i>
      <div class="fs-14">
           Leia o seguinte código QR no aplicativo autenticador
           
               <div id="qrCodeArea" class="mt-2">
      </div>
      </div>
  </div>
  
      
  
      
      </li>
  <li class="list-group-item">
      <div class="d-flex align-items-center justify-content-start gap-3">
      <i class="bi bi-3-square fs-14"></i>
      <div class="fs-14 flex-fill">
           Insira o código do aplicativo autenticador abaixo
            <input class="form-control" data-mascara="18" id="codigoApp"> 
      </div>
      </div>

  </li> 
  
  <li class="list-group-item">
      <div class="d-flex align-items-center justify-content-start gap-3">
      <i class="bi bi-4-square fs-14"></i>
      <div class="fs-14 flex-fill">
            Digite a sua senha
            <input class="form-control" type="password" id="senhaApp"> 
      </div> 

  </li>

</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-n-primaria" disabled id="btnValidar">Validar</button>
      </div>
    </li>
    </ul>
</div>
</div>
  </div>
</div>