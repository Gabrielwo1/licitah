<?

function pegaCep($caminho){
    if(isset($caminho[1])){
        $str = $caminho[1];
        if (strlen($str) === 8 && ctype_digit($str)) {
        return $str;
    } else {
        return false;
    }
    }else{
        return false;
    }
}


$cep = pegaCep($caminho);
$preCep = $cep ? 'data-cep="'.$cep.'"' : "";

?>
<div class="card rounded-0" id="cadastroPagamento">
                 <div class="card-header border-0 bg-primary text-light rounded-0">
                     <div class="d-flex justify-content-between align-items-center">
                         <div><h2 class="m-0 fs-18">Cadastra-se</h2></div>
                         <div class="d-flex justify-content-end align-items-center"><span>Já tem uma conta?</span> <button class="btn btn-light btn-sm ms-2 btnGo" data-link="pagamento/acesso">Acesse</button></div>
                     </div>
                 </div>
                 <div class="card-body">
                    
                     
                     <div class="row">
                         
                         <div class="col-12 col-lg-6">
                                       
                         <label class="form-label">Nome Completo</label>
                         <input class="form-control" id="nome">
                     </div>
                     
                     <div class="col-12 col-lg-6 mt-3 mt-lg-0">
                                     
                         <label  class="form-label">CPF</label>
                         <input class="form-control" id="cpf">
                     </div>
                         
                         
                         
                         <div class="col-12 col-lg-6 mt-3">
                                    
                         <label  class="form-label">E-mail</label>
                         <input class="form-control" id="email">
                  
                         </div>
                         <div class="col-12 col-lg-6 mt-3">
                             
                         <label  class="form-label">Celular</label>
                         <input class="form-control" id="celular">
                     
                         </div>
                     </div>
                     
                      <div class="mt-3">
                         <label  class="form-label">CEP</label>
                         <input class="form-control" id="cep" <?=$preCep; ?>>
                     </div>
                        
                    
                     
                 </div>
                
             </div> 
             
              <div id="containerEndereco">
                  
              </div>
             
             <div class="card mt-3">
                  <div class="card-footer border-0">
                     <div class="d-flex justify-content-end">
                         <button class="btn btn-desativado" disabled id="btnCadastro">Criar Conta e Continuar</button>
                     </div>
                 </div>
             </div>