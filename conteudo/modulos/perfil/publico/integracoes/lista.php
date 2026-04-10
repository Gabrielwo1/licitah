<?



$pagamentos = [
    ["tipo"=>"pagamentos", "chave"=>"mercadopago", "titulo"=>"Mercado Pago"],
    ["tipo"=>"pagamentos", "chave"=>"pagbank", "titulo"=>"Pagseguro"],
    ["tipo"=>"pagamentos", "chave"=>"efi", "titulo"=>"EFI"],
    ];
    
$pagamentosFinal = [];

foreach($pagamentos as $item){
    if(v(["integracoes", $item["tipo"], $item["chave"]], false)){
        array_push($pagamentosFinal, $item);
    }
}
    
$frete = [
    ["tipo"=>"frete", "chave"=>"correios", "titulo"=>"Correios"],
    ["tipo"=>"frete", "chave"=>"melhorenvios", "titulo"=>"Melhor Envios"],
    ["tipo"=>"frete", "chave"=>"loggi", "titulo"=>"Loggi"],
];

$fretefinal =  [];

foreach($frete as $item){
    if(v(["integracoes", $item["tipo"], $item["chave"]], false)){
        array_push($fretefinal , $item);
    }
} 


$erps = [
    ["tipo"=>"erps", "chave"=>"bling", "titulo"=>"Bling"],
];

$erpsFinal = [];
foreach($erps as $item){
    if(v(["integracoes", $item["tipo"], $item["chave"]], false)){
        array_push($erpsFinal , $item);
    }
}


$dominio = SETUP["dominio"];
?>

<div class="container">
    <div class="d-flex flex-column gap-4">
        <div class="card card-nown">
        <div class="card-body">
            <h1 class="m-0 fs-18">
                <i class="bi bi-gear"></i>
                <span>Integrações e APIs</span>
            </h1>
        </div>
    </div>
    <div>
        
    </div>
    <?
    if(!empty($erpsFinal)){
        $itens = [];
        $i = 0;
        foreach($erpsFinal as $item){
            $i++;
            array_push($itens, '  <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#erp-'.$i.'" aria-expanded="false" aria-controls="erp-'.$i.'">
                        '.$item["titulo"].'
                    </button>
                </h2>
                <div id="erp-'.$i.'" class="accordion-collapse collapse" data-bs-parent="#opcoesErp">
                    <div class="accordion-body" id="integracao-'.$item["chave"].'">
                        <button class="btn btn-nown-lg btn-n-primaria">Integrar com '.$item["titulo"].'</button>
                    </div>
                </div>
            </div>');
        }
        
        $itens = implode("", $itens);
        
        echo '<div class="card card-nown">
                    <div class="card-header bg-transparent">

                 <h2 class="m-0 fs-18 d-flex align-items-center gap-2">
                     <img src="'.$dominio.'/conteudo/modulos/perfil/midia/erp.svg" class="wi-35">
                <span>ERPs</span>
                     
                 </h2>

             </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="opcoesErp">
                    '.$itens.'
                </div>
            </div>
        </div>';
    }
    
    
    if(!empty($pagamentosFinal)){
        
        $itens = [];
        $i = 0;
        foreach($pagamentosFinal as $item){
            $i++;
            array_push($itens, '  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pagamento-'.$i.'" aria-expanded="false" aria-controls="pagamento-'.$i.'">
        '.$item["titulo"].'
      </button>
    </h2>
    <div id="pagamento-'.$i.'" class="accordion-collapse collapse" data-bs-parent="#opcoesPagamento">
      <div class="accordion-body">
dasdas
      </div>
    </div>
  </div>');
        }
        
        $itens = implode("", $itens);
        
        echo '<div class="card card-nown">
             <div class="card-header bg-transparent">

                 <h2 class="m-0 fs-18 d-flex align-items-center gap-2">
                     <img src="'.$dominio.'/conteudo/modulos/perfil/midia/valor.svg" class="wi-35">
                <span>Pagamentos</span>
                     
                 </h2>

             </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="opcoesPagamento">
                    '.$itens.'
                </div>
            </div>
        </div>';
    }
    
    
    if(!empty($fretefinal)){
           $itens = [];
        $i = 0;
        foreach($fretefinal as $item){
            $i++;
            array_push($itens, '  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#frete-'.$i.'" aria-expanded="false" aria-controls="frete-'.$i.'">
        '.$item["titulo"].'
      </button>
    </h2>
    <div id="frete-'.$i.'" class="accordion-collapse collapse" data-bs-parent="#opcoesFrete">
      <div class="accordion-body">
dasdas
      </div>
    </div>
  </div>');
        }
        
        $itens = implode("", $itens);
        
        echo '<div class="card card-nown">
             <div class="card-header bg-transparent">

                 <h2 class="m-0 fs-18 d-flex align-items-center gap-2">
                     <img src="'.$dominio.'/conteudo/modulos/perfil/midia/logistica.svg" class="wi-35">
                <span>Logística</span>
                     
                 </h2>

             </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="opcoesFrete">
                    '.$itens.'
                </div>
            </div>
        </div>';
    }
    ?>

    </div>
</div>