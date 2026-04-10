<?
if(verModulo("configuracoes", "producao", false)){
        $key = verModulo("mercado-pago", "publickeyproducao", false);
}else{
        $key = verModulo("mercado-pago", "publickeysand", false);
    }
?>

<div class="row row-metodo-checkout justify-content-center">
    <div class="col-12">
       <div class="d-flex justify-content-start gap-2 align-items-center mb-4">
            <div>
            <img src="<?=SETUP["dominio"]?>/conteudo/modulos/pagamento/imgs/mercado-pago.png" class="wi-100">
        </div>
        <h3>Pague usando sua conta Mercado Pago</h3>
       </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="ib-checkout">
            <div class="icone">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div class="conteudo">
                <h5>Pagamento Seguro</h5>
                <p>Pague diretanemte usando o mercado pago.</p>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="ib-checkout">
            <div class="icone">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="conteudo">
                <h5>Aprovação Imediata</h5>
                <p>Sua compra aprovada em segundos.</p>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <?include('resumo.php')?>
         <div id="wallet_container" data-key="<?=$key?>"></div>
    </div>
    
</div>