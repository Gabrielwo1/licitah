<?


$date = new DateTime($dado["pedido_data"]);



$status = [
    "Cancelado", "Aguarndando Pagamento", "Pedido Pago", "Em Andamento", "Concluído"
    ];


?>

<style>
    #fatura{
        color: #002e6e;
        
        
        .itemPedido{
            h5{
                text-transform: uppercase;
                font-size: 14px;
                letter-spacing: 1px;
                color: #88939f;
                margin-bottom: 0 !important;
            
            }
            
            p{
                font-size: 23px;
                font-weight: 600;
                color: #002e6e;
            }
        }
        
        
        table{
            width: 100%;
            
            tr{
                th{
                        background-color: #36304a;
                        color: white;
                        padding: 20px 10px;
                }
            }
        }
    }
    
        .lista-do-pedido {
    list-style: none !important;
    margin: -20px 0;
    padding: 0;
    position: relative;
    
    &:before {
        display: block;
        height: 100%;
        border-left: 3px solid #ddd !important;
        position: absolute;
        left: 0;
    }
    
    li {
        position: relative;
        padding: 20px 0 20px 50px;
        
        &:before {
            content: '';
            display: block;
            height: 100%;
            border-left: 3px solid #ddd;
            position: absolute;
            top: 0;
            left: 16px;
        }
        
        &:first-of-type:before {
            height: 50%;
            top: 50%;
        }
        
        &:last-of-type:before {
            height: 50%;
            bottom: 50%;
        }
        
        .numero {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translatey(-50%);
            background: var(--nown-primaria);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 35px;
            height: 35px;
            font-size: 17px;
            font-weight: bold;
            border-radius: 50%;
            border: 3px solid var(--nown-primaria-darker);
            user-select: none;
        }
        
        .data {
            display: inline-block;
            background: #e9e9e9;
            font-size: 12px;
            padding: 0 7px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        h4.status {
            font-size: 19px;
            margin: 5px 0 0;
        }
        
        p.texto {
            font-size: 15px;
            margin: 0;
        }
    }
}

.lista-do-pedido:not(.lista-do-pedido:has(li + li)) {
    li:before {
        display: none !important;
    }
}
</style>

<?

switch(intval($dado["pedido_estado"])){
    case 0:
        $mensagem = "Pedido Cancelado";
        $icone = "bi bi-x-lg text-danger";
        break;
    case 2:
        $mensagem = "Pagamento Confirmado";
        $icone = "bi bi-cash-coin text-success";
        break;
    case 3:
        $mensagem = "Pedido em Andamento";
        $icone = "bi bi-truck text-primary";
        break;
    case 4:
        $mensagem = "Pedido Concluído";
        $icone = "bi bi-check-circle text-success";
        break;
}




$imagem = "";
$logo = json_decode(verModulo("faturas","logo", '[]'), true);
if(!empty($logo)){
    $imagem = '<img src="'.SETUP["dominio"].'/conteudo/uploads/'.$logo[0].'" class="he-80">';
}


$botaoVoltar = "";
$voltar = verModulo("faturas","voltar", false);
if(!empty($voltar)){
    $botaoVoltar = '<a class="btn me-0 pe-0" href="/">🏠 Página inicial</a>';
}


$forma = "INDEFINIDA";

$linhaTempo = [];

$atualizacao = $dado["pedido_update"];
array_push($linhaTempo, ["titulo"=> "Pedido", "mensagem"=>"O pedido foi criado", "data"=>$dado["pedido_data"]]);


$estado = $dado["pedido_estado"];
$id = $dado["pedido_id"];

$pega = "SELECT * FROM pay_pagamentos WHERE pagamento_pedido='$id' AND pagamento_estado='2'";
$resultado = $conn->query($pega);

if($resultado->num_rows == 1){
    $info = $resultado->fetch_assoc();
    
    $comecado = $info["pagamento_data"];
    $pago = $info["pagamento_update"];
    
    $forma = $info["pagamento_tipo"];
    
    array_push($linhaTempo, ["titulo"=> "Transação Iniciada", "mensagem"=>"O usuário iniciou o pagamento", "data"=>$comecado]);
    array_push($linhaTempo, ["titulo"=> "Pedido Pago", "mensagem"=>"O sistema recebeu o pagamento", "data"=>$pago]);
}

switch(intval($estado)){ 
    case 0:
        array_push($linhaTempo, ["titulo"=> "Pedido Cancelado", "mensagem"=>"O pedido foi cancelado", "data"=>$atualizacao]);
        break;
    case 2:
        array_push($linhaTempo, ["titulo"=> "Pedido Conclúido", "mensagem"=>"O sistema concluiu o pedido", "data"=>$pago ?? false]);
        break;
}

function dataBela($dataOriginal) {
    if(!$dataOriginal){
        return;
    }
    $data = new DateTime($dataOriginal);
    
    // Formata a data para o formato desejado: 'd/m/Y às H:i'
    return $data->format('d/m/Y \à\s H:i:s');
}



$itens = [
    ["titulo"=>"NÚMERO DO PEDIDO", "dado"=>$date->format("Y")."-".$dado["pedido_id"]],
    ["titulo"=>"DATA DO PEDIDO", "dado"=>$date->format('d/m/Y')],
    ["titulo"=>"STATUS DO PEDIDO", "dado"=>$status[$dado["pedido_estado"]]],
    ["titulo"=>"FORMA DE PAGAMENTO", "dado"=>$forma],
    ["titulo"=>"VALOR DO PEDIDO", "dado"=>"R$ ".$dado["pedido_total"]],
    ["titulo"=>"VALOR DO FRETE", "dado"=>"R$ 0,00"],
    ["titulo"=>"DESCONTO DO PEDIDO", "dado"=>"R$ 0,00"],
    ["titulo"=>"TOTAL DO PEDIDO", "dado"=>"R$ ".$dado["pedido_total"]],
    ["titulo"=>"NÚMERO DA NOTA FISCAL", "dado"=>""],
    ["titulo"=>"TRANSPORTADORA", "dado"=>""]
    ];

?>



<div class="container my-5">
    <div class="m-auto" style="max-width: 1200">
        <div class="d-flex justify-content-end">
            <?= $botaoVoltar ?>
        </div>
        <div class="card card-nown bg-white" id="fatura"  data-bs-theme="light">
            <div class="card-header px-5 py-3" style="background-color: #f7f8f9;">
                <div class="d-flex flex-column flex-xl-row justify-content-between align-items-center gap-5">
                    <div class="flex-fill order-2 order-xl-1">
                        <h2 style="font-weight: bold;color: #002e6e ;font-size: 24px; text-uppercase" class="m-0">
                            <span><i class="<?=$icone?>"></i></span>
                            <span><?=$mensagem?></span>
                        </h2>
                    </div>
                    <div class="d-flex flex-column gap-2 order-1 order-xl-2">
                        <div>
                            <?=$imagem?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                
                <div class="row">
                    
                    <?
                    foreach($itens as $item){
                 
                        echo '
                          <div class="col-12 col-xl-3 itemPedido">
                                <h5>'.$item["titulo"].'</h5>
                                <p>'.$item["dado"].'</p>
                        </div>
                        
                        ';
                    }
                    
                    ?>
                  
                </div>
                
            </div>
            <div class="card-body p-5">
                                <h2 style="font-weight: bold;color: #002e6e ;font-size: 24px;">Itens do Pedido</h2>
            <table class="table table-striped w-100"  data-bs-theme="light">
                <tbody id="itensPedido">
                     <tr style="background-color: #36304a">
                         <th >Item</th>
                         <th class="text-center">Quantidade</th>
                         <th class="text-center">Preço Unitario</th>
                         <th class="text-center">Total</th>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="card-body p-5">
                          <div>
                            <h2 style="font-weight: bold;color: #002e6e ;font-size: 24px;">Histórico do Pedido</h2>
                            <ul class="lista-do-pedido" id="lista-do-pedido">
                                <?
                                $i = 0;
                                foreach($linhaTempo as $etapa){
                                    $i++;
                                    echo '<li class="item">
      <span class="numero">'.$i.'</span><span class="data">'.dataBela($etapa["data"]).'</span>
      <h4 class="status">'.$etapa["titulo"].'</h4>
      <p class="texto">'.$etapa["mensagem"].'</p>
   </li>';
                                }
                                ?>
   

</ul>
                        </div>
            </div>
            <div class="card-footer p-5 border-0 d-flex justify-content-between flex-column flex-xl-row" style="background-color: #f7f8f9;">
                <?
                $whatsapp = verModulo("faturas","whatsapp", false);
                $telefone = verModulo("faturas","telefone", false);
                $email = verModulo("faturas","email", false);
                $endereco = verModulo("faturas","endereco", false);
                $cnpj = verModulo("faturas","cnpj", false);
                $nome = verModulo("faturas","nome", false);
                
                ?>
                
                <div class="d-flex justify-content-start align-items-center gap-3">
                    <div>
                        <h2 class="fs-12 fw-700">Link do Seu Pedido</h2>
                        <div class="p-2 bg-white rounded">
                            <div id="qrCode"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex flex-column gap-2">
                    <?
                    if($nome){
                       echo '<div class="d-flex gap-2 flex-column flex-xl-row"><strong>Empresa:</strong>'.$nome.'</div>'; 
                    }
                    
                    if($cnpj){
                       echo '<div class="d-flex gap-2 flex-column flex-xl-row"><strong>CNPJ:</strong>'.$cnpj.'</div>';
                    }
                    
                    
                    if($endereco){
                        echo '<div class="d-flex gap-2 flex-column flex-xl-row"><strong>Endereço:</strong>'.$endereco.'</div>';
                    }
                    ?>
       
                </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <div class="text-start text-xl-end"><strong>Suporte</strong></div>
                   <?
                   if($email){
                      echo '<div class="d-flex gap-2 justify-content-start justify-content-xl-end"><strong class="fs-16">Email:</strong> <a href="mail:'.$email.'" class="text-decoration-none fs-16 fw-500" style="color: #002e6e">'.$email.'</a></div>';
                   }
                   
                   if($telefone){
                       echo '<div class="d-flex gap-2 justify-content-start justify-content-xl-end"><strong>Telefone: </strong> <a href="tel:'.preg_replace('/\D/', '', $telefone).'" class="text-decoration-none fs-16 fw-500" style="color: #002e6e">'.$telefone.'</a></div>';
                   }
                   
                   if($whatsapp){
                       echo '<div class="d-flex gap-2 justify-content-start justify-content-xl-end"><strong>WhatsApp:</strong><a target="_blank" href="https://wa.me/'.preg_replace('/\D/', '', $whatsapp).'" class="text-decoration-none fs-16 fw-500" style="color: #002e6e">'.$whatsapp.'</a></div>';
                   }
                   
                   ?>
                   <div class="d-flex gap-2 justify-content-start justify-content-xl-end">
                       <?= $botaoVoltar ?>
                   </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

