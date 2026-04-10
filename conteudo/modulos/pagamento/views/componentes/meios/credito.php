<?
$salvacard = verModulo("cartao-de-credito", "armazenamento", false);

$entradas = <<<HTML
   <form id="form-checkout">
<div class="form-cartao">
    <div class="grupo-inputs">
        <h5>Dados do cartão:</h5>
        <div class="holder-inputs-cartao">
            <div class="inputs-cartao">
                <div class="input-cartao">
                    <i class="bi bi-credit-card"></i>
                    <input type="text" id="numero" placeholder="1234 1234 1234 1234">
                </div>
            </div>
            <div class="inputs-cartao">
                <div class="input-cartao">
                    <i class="bi bi-calendar-event"></i>
                    <input type="text" id="expira" placeholder="MM/AAAA">
                </div>
                <div class="input-cartao">
                    <i class="bi bi-lock"></i>
                    <input type="text" id="cvv" placeholder="CVV">
                </div>
            </div>
            <div class="d-none">
                <select id="form-checkout__identificationType" class="form-select"></select>
                    <select id="form-checkout__installments" class="form-control"></select>
                    <select id="form-checkout__issuer" class="form-select"></select>
                    <input type="email" id="form-checkout__cardholderEmail"  class="form-control" value="{$_SESSION["email"]}"/>
                      <input type="text" class="form-control" id="documentoespelho" placeholder="Documento">
                     <button type="submit" id="form-checkout__submit" class="btn btn-n-primaria">Pagar</button>
            </div>
        </div>
    </div>
    
    <div class="grupo-inputs">
        <h5>Dados do titular:</h5>
        <div class="holder-inputs-cartao">
            <div class="inputs-cartao">
                <div class="input-cartao">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control" id="nome" placeholder="Nome Impresso no Cartão">
                </div>
            </div>
            <div class="inputs-cartao">
                <div class="input-cartao">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" class="form-control" id="documento" placeholder="CPF / CNPJ do Titular">
                </div>
            </div>
            <div class="inputs-cartao">
                <div class="input-cartao">
                      <i class="bi bi-geo-alt-fill"></i>
                    <input type="text" class="form-control" id="cep" placeholder="Seu CEP">
                </div>
            </div>
        </div>
    </div>

</div>
</form>
    <!--div class="input-group mb-3">
        <span class="input-group-text">
            <i class="bi bi-credit-card"></i>
        </span>
        <div class="form-floating">
            <input type="text" class="form-control" id="numero" placeholder="Número do Cartão">
            <label for="numero">Número do Cartão</label>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <div class="form-floating">
                    <input type="text" class="form-control" id="documento" placeholder="CPF / CNPJ do Titular">
                    <label for="expira">Expiração</label>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group mb-3">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <div class="form-floating">
                    <input type="text" class="form-control" id="cvv" placeholder="CVV">
                    <label for="cvv">CVV</label>
                </div>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text">
            <i class="bi bi-person"></i>
        </span>
        <div class="form-floating">
            <input type="text" class="form-control" id="nome" placeholder="Número do Cartão" data-mascara="17">
            <label for="nome">Titular do Cartão</label>
        </div>
    </div>
    <div class="input-group mb-3">
        <select class="input-group-text form-select w-25">
            <option>CPF</option>
            <option>CNPJ</option>
        </select>
        <div class="form-floating w-75">
            <input type="text" class="form-control" id="documento" placeholder="Documento do Titular">
            <label for="expiracao">Documento do Titular</label>
        </div>
    </div>
    <div>
    <input class="form-control">
    </div>
</div-->
HTML;


if($assinatura){
    $processador = verModulo("recorrencia", "processadorcartao", false);
}else{
    $processador = verModulo("pagamentos", "processadorcartao", false);
}



$extra = "";
$keyProducao = false;
$keySandbox = false;
if($processador){
    switch($processador){
        case 'mercadopago':
            if(verModulo("configuracoes", "producao", false)){
                $key = verModulo("mercado-pago", "publickeyproducao", false);
            }else{
                $key = verModulo("mercado-pago", "publickeysand", false);
            }
            

            
            break;
    }
}


?>


 

<div class="row row-metodo-checkout justify-content-center" id="containerCartao" data-processador="<?=$processador;?>" data-key="<?=$key?>">
    <?
    if($salvacard){
    ?>
    <div class="col-12">
        <h3 class="mb-2">Selecione o cartão de crédito:</h3>
    </div>
    <div class="col-12" >
        
        <div class="grid-cartoes">
         
            <div class="card-choice">
                <button type="button" class="fake-card-add" data-bs-toggle="modal" data-bs-target="#modalNewCard"> <i></i> </button>
                <div class="text-center fs-12">Adicionar Cartão</div>
            </div>
        </div>
    </div>
    <?
    } else {
   ?>
    <div id="formularioDoCartao">
        <div class="row align-items-center">
            <div class="col-xl-7">
                <div id="previaDoCartao" class="d-flex align-items-center my-4" data-size="400"></div>
            </div>
            <div class="col-xl-5"> <?=$entradas?> </div>
        </div>
    </div>
    <?
   }?>
    <div class="col-lg-12">
        
        <?
        if(verModulo("cartao-de-credito", "parcelamento", false) && !$assinatura){
            echo '<h5 class="fs-16 mt-1 mb-2">Selecione a quantidade de parcelas:</h5>
               <select class="form-select mb-3" id="parcelas">
            <option value="1">1 x de R$1920,00</option>
        </select>
            ';
            
        }
        
        
        
        echo $extra;
        ?>
        
        
        
        <?include('resumo.php')?>
        <button class="btn btn-n-primaria btn-nown-style btn-final-checkout" id="btnPagarCredito" disabled data-target="cartao">PAGAR COM CARTÃO DE CRÉDITO <i class="bi bi-arrow-right"></i></button>
    </div>
</div>



<?
if($salvacard){
?>

<div class="modal fade modal-credito" id="modalNewCard" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="corpoModalNewCard">
        <div class="modal-content">
            <div class="modal-body">
                <div class="titulo-cartao">
                    <h2 id="exampleModalLabel">Novo Cartão</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="formularioDoCartao">
                    <div id="previaDoCartao" class="d-flex align-items-center justify-content-center mb-4" data-size="350" style="min-height: 250px"></div> 
                    <?=$entradas?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-n-primaria btn-nown-style" id="addCartaoModal" disabled >Adicionar Cartão</button>
            </div>
        </div>
    </div>
</div>
<?
}
?>