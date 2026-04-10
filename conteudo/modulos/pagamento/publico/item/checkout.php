<div  style="min-height: 100vh" class="d-flex justify-content-between flex-column">
    
<?
echo '<div class="mb-5">';
include __DIR__."/../../../../../includes/sistema/simplheader.php";
echo '</div>';


?>
    
    
    
<div id="pedidoControler" data-pedido="<?=$pedido;?>" data-obrigado="<?=verModulo("configuracoes","obrigado", false)?>" data-addid="<?=verModulo("configuracoes","idagradecimento", false)?>"  data-assinatura="<?=$assinatura?>"></div>


<div class="overlay-loading-cartao"></div>
<div class="loading-cartao">
    <div class="texto">Processando o pagamento...</div>
    <div class="cartao"></div>
    <div class="circle"></div>
    <div class="loading"></div>
    <div class="check">
        <svg class="checkmark addClass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle addClass" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check addClass" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
    </div>
    <div class="erro">
        <svg class="crossmark addClass" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="crossmark__circle addClass" cx="26" cy="26" r="25" fill="none"/> <path class="cross__path cross__path--right addClass" fill="none" d="M16,16 l20,20" /> <path class="cross__path cross__path--left addClass" fill="none" d="M16,36 l20,-20" /> </svg>
    </div>
    <div class="frase">Realizando o pagamento...</div>
</div>


<div class="container my-5">
    <div class="d-flex justify-content-between align-items-xl-center flex-column flex-xl-row">
        <div>

             <div class="d-flex justify-content-start align-items-center fs-14 gap-3">
                <span><img src="https://pay.doutornature.com/img/ssl.png" style="height: 30px"></span>
                <span>Criptografia de ponta a ponta</span>
            </div>
             



          
        </div>
        <div class="fs-14"><?=date("Y")?> © <?=verModulo("faturas","nome", "")?> - CNPJ: <?=verModulo("faturas","cnpj", "")?></div>
        <div class="d-flex gap-2 align-items-center gap-2">
            
            <?
            if(verModulo("pagamentos", "pix", false)){
                echo '<span><img src="https://blog.gabrf.com/assets/img/pix-logo.png" style="height: 50px"></span>';
            }
            
            if(verModulo("pagamentos", "cartao", false)){
                echo ' <span><img src="https://4.bp.blogspot.com/-nBfU68NX10s/Vxp8J0dG9DI/AAAAAAAAEE0/-YgXYn0UsaQzM6Yk8hpTPAZs9jgU-xiugCLcB/s1600/cart%25C3%25A3o%2Bde%2Bcr%25C3%25A9dito_comunica%25C3%25A7%25C3%25A3o%2Bvisual.png" style="height: 50px"></span>';
            }

if(verModulo("pagamentos", "boleto", false)){
    //array_push($meios, ["nome"=>"Boleto", "icone"=>"bi-upc", "html"=>"boleto"]);
}


if(verModulo("pagamentos", "cripto", false)){
    //array_push($meios, ["nome"=>"Criptomoedas", "icone"=>"bi-currency-bitcoin", "html"=>"cripto"]);
}

if(verModulo("pagamentos", "paypal", false)){
   // array_push($meios, ["nome"=>"Paypal", "icone"=>"bi-paypal", "html"=>""]);
}

if(verModulo("pagamentos", "offline", false)){
    //array_push($meios, ["nome"=>verModulo("pagamentos", "offlinenome", "Pagamento Offline"), "icone"=>"bi bi-cash-coin", "html"=>""]);
}

            
            ?>
            
          
        </div>
    </div>
</div>

</div>