<?
$total = $dado["pedido_total"];
$saldo = 0;

$user = $_SESSION["id"];

$seleciona = "SELECT * FROM pay_saldos WHERE pay_saldo_usuario='$user'";
$resultado = $conn->query($seleciona);
if($resultado->num_rows == 1){
    $info = $resultado->fetch_assoc();
    $saldo = $info["pay_saldo_saldo"];
}


$pode  = podeFazerPedido($total, $saldo);

function formataDinheiro($valor) {
    // Verifica se o valor é zero
    if ($valor == 0) {
        return "Sem Saldo";
    }

    // Formata o valor para o formato de dinheiro brasileiro
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function podeFazerPedido($total, $saldo) {
    // Converte os valores para floats, garantindo que strings de números sejam tratadas corretamente
    $total = floatval($total);
    $saldo = floatval($saldo);

    // Verifica se o saldo é suficiente para cobrir o total do pedido
    if ($saldo >= $total) {
        return true;  // Pode fazer o pedido
    }
    return false;  // Não pode fazer o pedido
}
?>


<div class="row row-metodo-checkout justify-content-center">
    <div class="col-12">
        <h3 class="mb-4">Pague com Saldo em Carteira</h3>
    </div>

    <div class="col-xl-12 mb-4">
        <div class="ib-checkout">
            <div class="icone">
                <i class="bi bi-wallet"></i>
            </div>
            <div class="conteudo">
                <h5>Saldo em Conta</h5>
                <p><?=formataDinheiro($saldo)?></p>
            </div>
        </div>
    </div>
    
    <?
    if(!$pode){
         $total = floatval($total);
         $saldo = floatval($saldo);
         $calc = $total - $saldo;
         
        echo '    <div class="col-xl-12 mb-4">
        <div class="ib-checkout bg-danger text-light">
            <div class="d-flex">
                <div class="text-uppercase fs-18">Adicione '.formataDinheiro($calc).' de saldo a sua conta para completar o pagamento.</div>
            </div>
        </div>
    </div>';
    }
   
    
    ?>

    <div class="col-12">
        <?include('resumo.php')?>
        
        <?
        if($pode){
            echo '<button class="btn btn-n-primaria btn-nown-style btn-final-checkout" data-target="carteira">PAGAR COM SALDO <i class="bi bi-arrow-right"></i></button>';
        }else{
            echo '
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>Você não tem saldo suficiente</div>
                <a class="btn text-primaria" data-target="carteira" href="/pagamento/saldo">Adicionar Saldo a Carteira <i class="bi bi-arrow-right"></i></a>
            </div>';
        }
        
        ?>
       
    </div>
    
</div>