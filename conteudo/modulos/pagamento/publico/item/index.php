<?

$pedido = $this->caminho[1];


$conn = conn();
$selecionar = "SELECT * FROM pay_pedidos WHERE pedido_url='$pedido'";
$resultado = $conn->query($selecionar);


if($resultado->num_rows == 0){
    switch($this->caminho[1]){
        case 'sucesso':
             include __DIR__."/sucesso.php";
            break;
        case 'meus-pagamentos':
            include __DIR__."/meus-pagamentos.php";
            break;
        case 'meus-pedidos':
             include __DIR__."/meus-pedidos.php";
            break;
        case 'minhas-assinaturas':
             include __DIR__."/minhas-assinaturas.php";
            break;
        default:
            include __DIR__."/inexistente.php";
            break;
    }
   
}else{
    $dado = $resultado->fetch_assoc();
    
    $tipo =  intval($dado["pedido_tipo"]);
    $pedId = $dado["pedido_id"];
    $frete = false;
    if($tipo == 0){
    $seleciona = "SELECT * FROM pay_pedidos_itens WHERE ppi_pedido='$pedId'";
    $resultado = $conn->query($seleciona);
    
    if($resultado->num_rows == 0){
        return;
    }
    
    $itens = [];
    while($info = $resultado->fetch_assoc()){
        $espelho = json_decode($info["ppi_espelho"], true);
        
        if(intval($espelho["tipo"]) === 2){
            $frete = true;
        }
        array_push($itens, $espelho);
        
    }
    
    $assinatura = false;
    if(count($itens) == 1 && $itens[0]["recorrente"]){
        $assinatura = true;
        
    }
}


    switch(intval($dado["pedido_estado"])){
        
        case 1:
            if($frete){
                include __DIR__."/frete.php";
                return;
            }else{
                $number = floatval($dado["pedido_total"]);
 
            if($number == 0){
                include 'gratis.php';
            }else{
                
                include 'checkout.php';
            }
            }
            
            break;
        case 0:
        case 2:
        case 3:
        case 4:
            include 'fatura.php';
            break;
    }
    
}

?>
