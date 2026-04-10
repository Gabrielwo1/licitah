<?

session_start();

if(!isset($_SESSION["id"]) || !$_SESSION["id"]){
    return;
}



$pedido = $_POST["pedido"];
$conn = conn();
$seleciona = "SELECT * FROM pay_pedidos WHERE pedido_url ='$pedido'";
$resultado = $conn->query($seleciona);
if($resultado->num_rows == 0){
    return;
}

$dado = $resultado->fetch_assoc();



$assinatura = false;
if(isset($_POST["assinatura"]) && $_POST["assinatura"] == "1"){
    $assinatura = true;
}


if(!verModulo("configuracoes", "ativo", false)){
    ?>
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="card card-nown ratio ratio-1x1" style="max-width: 500px">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="w-75 m-auto">
                            <div class="text-center fs-40">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                         <h2 class="text-center fs-22">Pagamentos Desativados</h2> 
                     <p class="m-0 text-center">Os pagamentos no momento estão desativados e não poderá haver novas tranzações no momento. Volte mais tarde.</p>
                    </div>
                </div> 
            </div>
    </div>
    
    <?
   return;
}


$meios = [];

$tipoPedido = intval($dado["pedido_tipo"]);
$carteira = false;

switch($tipoPedido){
    case 0:
        if(verModulo("saldo", "ativo", false)){
        array_push($meios, ["nome"=>"Saldo em Conta", "icone"=>"bi bi-cash-coin", "html"=>"carteira"]);
        }
        $carteira = true;
        break;
    case 1:
        break;
    case 2:
        break;
}


if(true){
   if($assinatura){
   
  
   if(verModulo("recorrencia", "cartao", false)){
    array_push($meios, ["nome"=>"Cartão de Crédito", "icone"=>"bi-credit-card", "html"=>"credito"]);
}

if(verModulo("recorrencia", "paypal", false)){
    array_push($meios, ["nome"=>"Paypal", "icone"=>"bi-paypal", "html"=>""]);
}

  if(verModulo("recorrencia", "pix", false)){
   array_push($meios, ["nome"=>"Pix", "icone"=>"bi-qr-code", "html"=>"pix"]);
       
   }
   
   $funcao = intval($_SESSION["funcao"]);
if(($funcao === 0 || $funcao === 1) && verModulo("recorrencia", "administrador", false)){
    array_push($meios, ["nome"=>'Simular Pagamento (Somente para Administradores)' , "icone"=>"bi bi-currency-exchange", "html"=>"administradores"]);
}
   
   
    
    
}else{
   if(verModulo("pagamentos", "pix", false)){
    array_push($meios, ["nome"=>"Pix", "icone"=>"bi-qr-code", "html"=>"pix"]);
       
   }


if(verModulo("pagamentos", "cartao", false)){
    array_push($meios, ["nome"=>"Cartão de Crédito", "icone"=>"bi-credit-card", "html"=>"credito"]);
}

if(verModulo("pagamentos", "boleto", false)){
    array_push($meios, ["nome"=>"Boleto", "icone"=>"bi-upc", "html"=>"boleto"]);
}


if(verModulo("pagamentos", "cripto", false)){
    array_push($meios, ["nome"=>"Criptomoedas", "icone"=>"bi-currency-bitcoin", "html"=>"cripto"]);
}

if(verModulo("pagamentos", "paypal", false)){
    array_push($meios, ["nome"=>"Paypal", "icone"=>"bi-paypal", "html"=>""]);
}

if(verModulo("pagamentos", "offline", false)){
    array_push($meios, ["nome"=>verModulo("pagamentos", "offlinenome", "Pagamento Offline"), "icone"=>"bi bi-cash-coin", "html"=>""]);
} 

if(verModulo("pagamentos", "mercadopago", false)){
    array_push($meios, ["nome"=>"Mercado Pago", "icone"=>"bi bi-cash-coin", "html"=>"mercado-pago"]);
}


$funcao = intval($_SESSION["funcao"]);
if(($funcao === 0 || $funcao === 1) && verModulo("pagamentos", "administrador", false)){
    array_push($meios, ["nome"=>'Simular Pagamento (Somente para Administradores)' , "icone"=>"bi bi-currency-exchange", "html"=>"administradores"]);
}
}
 
}








if(!verModulo("configuracoes" , "producao",  false)){

    echo '
    <div class="container mb-5">
        <div class="alert alert-success" role="alert">
            <p class="mb-0"><strong>Modo Teste Ativado.</strong> As transações não serão processadas.</p>
        </div>   
    </div>';
}





?>



<div class="container">
    <div style="max-width: 800px" class="m-auto">
        <div class="card card-nown card-nown-checkout">
            <div class="card-header bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    
                 
                    <?
                    if($assinatura){
                        echo     '<h1>Complete sua Assinatura</h1>';
                    }else{
                        echo     '<h1>Pagamento</h1>';
                    }
                    
                    
                    
                    if(verModulo("configuracoes", "expiracao", false)){
                        echo ' <span class="expira" data-time="'.verModulo("configuracoes", "expiracaotempo", 5).'"><i class="bi bi-clock-history"></i> Expira em <span class="tempo">00:00</span></span>';
                    }
                    ?>
                   
                </div>
            </div>
        <div class="card-body">
            <div class="accordion accordion-flush accordion-checkout" id="accordionExample">
                <?
                $i = 0;
                foreach($meios as $meio){
                    $i++;
                    $id = geraId();
                    $show = "";
                    $btn = "collapsed";
                    $expanded = "false";
                    if($i == 1){
                        $show = "show";
                        $expanded = "true";
                        $btn = "";
                    }
                    
                    ?>
                      <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button <?=$btn?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?=$id?>" aria-expanded="<?=$expanded?>" aria-controls="<?=$id?>">
        <div class="d-flex justify-content-start gap-3 align-items-center text-uppercase fs-16 fw-700 py-2">
            <i class="bi <?=$meio["icone"]?>"></i> <span><?=$meio["nome"]?></span>
        </div>
      </button>
    </h2>
    <div id="<?=$id?>" class="accordion-collapse collapse <?=$show?>" data-bs-parent="#accordionExample">
      <div class="accordion-body">
          <?
          if($meio["html"]){
              include __DIR__."/meios/".$meio["html"].".php";
          }
          ?>
      </div>
    </div>
  </div>
                    <?
                }
                
                
                ?>


</div>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-end gap-2 align-items-center">
            <span class="fw-500 fs-14 text-uppercase">ID do Pedido:</span>
            <span class="fw-700 fs-14" id="idPedido"></span>
            <button class="btn" title="Copiar Pedido" id="copyId"><i class="bi bi-copy"></i></button>
        </div>
    </div>
    </div>
</div>
