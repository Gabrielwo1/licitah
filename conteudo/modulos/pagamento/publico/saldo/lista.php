<?
if(!verModulo("saldo", "ativo", false)){
    return;   
}

if(!logado()){
     echo '<div id="restritoPage"></div>';
     return;
}


echo '<div class="mb-5">';
include __DIR__."/../../../../../includes/sistema/simplheader.php";
echo '</div>';



$predefinidos = [];

if(verModulo("saldo", "predefinidos", false)){
    $valores =  verModulo("saldo", "valores", "");
    if($valores && trim($valores)){
         $trato = explode(",", $valores);
         foreach($trato as $item){
             array_push($predefinidos, $item);
         }
    }
}

$personalizar = verModulo("saldo", "personalizar", false);

$minimo = false;
$maximo = false;
if(verModulo("saldo", "minimo", false)){
    $valor = verModulo("saldo", "valorminimo", "");
    if(trim($valor)){
        $minimo = $valor;
    }
}

if(verModulo("saldo", "maximo", false)){
    $valor = verModulo("saldo", "valormaximo", "");
    if(trim($valor)){
        $maximo = $valor;
    }
}

if(!$minimo && !$maximo){
    $textoMM = "Não existem valores mínimos e máximos para depósito, ficando a sua livre escolha o valor a ser pago.";
}else{
    if($minimo && $maximo){
        $textoMM = "O valor mínimo de depósito é de <strong>R$ $minimo,00 </strong>. O sistema aceita pagamentos de até  <strong>R$ $maximo,00 </strong>, com o intuito de garantir maior segurança ao sistema.";
    }elseif($minimo && !$maximo){
        $textoMM = "O valor mínimo de depósito é de  <strong>R$ $minimo,00 </strong>. Não há limite máximo para pagamento.";
    }elseif(!$minimo && $maximo){
        $textoMM = "Não há valor mínimo para depósito. O sistema aceita pagamentos de até  <strong>R$ $maximo,00 </strong>, garantindo a segurança do sistema.";
    }
}


$saque = "Não, o valor depositado é destinado exclusivamente para uso dentro da plataforma, como pagamento de serviços e produtos. Não é possível sacar o saldo de volta para a sua conta bancária.";
if(verModulo("saldo", "saque", false)){
    $saque = "Sim , o valor depositado poderá ser transferido de volta para sua conta a qualquer momento, bastando fazer a solicitação.";
}



     $faqDeposito = [
                    [
                        "pergunta" => "O que é o sistema de depósito?",
                        "resposta" => "O sistema de depósito permite que você adicione um valor à sua conta na plataforma. Esse valor ficará disponível para uso futuro, como pagamento de serviços ou produtos, sem a necessidade de realizar novos pagamentos toda vez."
                    ],
                    [
                        "pergunta" => "Como faço para depositar dinheiro na minha conta?",
                        "resposta" => "Basta selecionar o valor que você quer depositar e proseguir para o pagamento. Assim que o valor for processado , aparecerá disponível para você."
                    ],
                    [
                        "pergunta" => "Qual é o valor mínimo/máximo de depósito?",
                        "resposta" => $textoMM
                        ],
                    [
                        "pergunta" => "Posso sacar o valor depositado?",
                        "resposta" => $saque
                    ],
                    [
                        "pergunta" => "O valor depositado tem validade?",
                        "resposta" => "Não, o valor depositado em sua conta não tem validade. Ele ficará disponível para uso enquanto você tiver uma conta ativa na plataforma."
                    ],
                    [
                        "pergunta" => "Como acompanho meu saldo disponível?",
                        "resposta" => "Você pode acompanhar seu saldo diretamente no seu painel de usuário, na seção 'Minha Conta'. O saldo será atualizado automaticamente após cada depósito ou transação feita na plataforma."
                    ],
                    [
                        "pergunta" => "Posso usar o saldo para pagar parte de um pedido?",
                        "resposta" => "Sim, você pode usar o saldo disponível para cobrir parte de um pagamento. Se o saldo não for suficiente para pagar o valor total, você poderá complementar com outro método de pagamento."
                    ],
                    [
                        "pergunta" => "O que acontece se eu cancelar um serviço pago com o saldo da conta?",
                        "resposta" => "Se um serviço for cancelado e você tiver pago com seu saldo na plataforma, o valor será automaticamente reembolsado no seu saldo com descontos respecitvo ao uso ou custos da operação, caso houver , e poderá ser utilizado em uma nova transação."
                    ],
                    [
                        "pergunta" => "Receberei algum comprovante após o depósito?",
                        "resposta" => "Sim, após concluir o depósito, você receberá um comprovante por e-mail com todos os detalhes da transação, além de poder acessá-lo na seção 'Histórico de Transações' no seu painel de usuário."
                        ]
                    ];
?>




    
    <!-- Conteúdo Principal -->
    <div id="secao-deposito">
         <div class="content-wrapper" id="paginaSaldo">
        <div class="container">
            <div class="row g-4">
                <!-- Seção de Depósito -->
                <div class="col-lg-6 mb-4">
                    <div class="glass-card h-100">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="bi bi-wallet2"></i>
                                Escolha o Valor para Depósito
                            </h2>
                        </div>
                        
                        <div class="card-body">
                             <div class="amount-grid">
                            
                            
                            <?
            $have = false;
            $tamanho = count($predefinidos);
            $colsize = 12;
            switch($tamanho){
                case 0:
                    $colsize = 12;
                    break;
                case 1:
                    $colsize = 12;
                    if($personalizar){
                        $colsize = 6;
                    }
                    break;
                case 2:
                    $colsize = 6;
                    if($personalizar){
                        $colsize = 4;
                    }
                    break;
 
                default:
                    $colsize = 4;
                    break;
                
            }
            
            foreach($predefinidos as $item){
                $have = true;
                echo '
                <label class="amount-option" for="amount-'.$item.'">
                                    <input type="radio" name="depositAmount" id="amount-'.$item.'" value="'.$item.'">
                                    <p class="amount-value">R$ '.number_format($item, 2, '.', '').'</p>
                                    <span class="amount-label">Depósito</span>
                                </label>
                                
        
                ';
            }
            
            if($personalizar){
                if($colsize < 4){
                    $colsize = 4;
                }
          
                if($have){
                                   echo '
                                   
                                   
                

     <label class="amount-option" for="amount-custom">
                                    <input type="radio" name="depositAmount" id="amount-custom" value="custom">
                                    <p class="amount-value"><i class="fas fa-pencil-alt" style="font-size: 1rem;"></i></p>
                                    <span class="amount-label">Personalizado</span>
                                </label>';
          
          
          

                
    
                }else{
                    echo '<div class="input-group parente">
  <span class="input-group-text bg-primaria"">R$</span>
  <input type="number" class="form-control" placeholder="10 - 20" id="customValor" data-maximo="'.$maximo.'" data-minimo="'.$minimo.'">
</div>';
                }
          

            }
            
            
            
            
        
            
            ?>
                            
                            
                            
                            <!-- Valores Predefinidos -->
                           
              
                                
                             
                            </div>
                            
                            <!-- Campo Personalizado -->
                            <?
                                   if($have){
                echo '
                   <div class="custom-amount-container d-none" id="customAmountContainer">
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" id="customAmountInput" placeholder="Digite o valor desejado" min="'.$minimo.'" max="'.$maximo.'">
                                </div>
                            </div>';
            }
                            ?>
                            
                            <!-- Alerta de Informação -->
                            <?
                            if($minimo || $maximo){
                                echo '   <div class="premium-alert">
                                <i class="fas fa-info-circle alert-icon"></i>
                                <div class="alert-content">
                                    '.$textoMM.'
                                </div>
                            </div>';
                            }
                            ?>
                         
                            
                            <!-- Resumo do Valor -->
                            <div class="amount-summary">
                                <div class="summary-label">Valor Total</div>
                                <div class="summary-value" id="totalAmountDisplay">R$ 0,00</div>
                            </div>
                            
                            <!-- Botão de Pagamento -->
                            <button class="btn-premium" id="paymentButton" disabled>
                                <i class="bi bi-credit-card"></i>
                                Realizar Pagamento
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Seção de FAQ -->
                <div class="col-lg-6 mb-4">
                    <div class="glass-card h-100">
                        <div class="card-header">
                            <h2 class="card-title">
                               <i class="bi bi-question-circle"></i>
                                Perguntas Frequentes
                            </h2>
                        </div>
                        
                        <div class="card-body">
                            <div class="accordion faq-accordion" id="faqAccordion">
                                
                                <?
                                $k = 0;
                                foreach($faqDeposito as $pergunta){
                                    $k++;
                                    ?>
                                       <div class="accordion-item">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?=$k?>" aria-expanded="false">
                                            <?=$pergunta["pergunta"];?>
                                        </button>
                                    </h3>
                                    <div id="faq-<?=$k?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            <?=$pergunta["resposta"];?>
                                        </div>
                                    </div>
                                </div>
                                    <?
                                }
                                ?>
           
          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
   
<style>
       #secao-deposito {
            .content-wrapper {
                padding: 2rem 0;
                min-height: 100vh;
                
                .container {
                    max-width: 1200px;
                    
                    .row {
                        margin: 0 -15px;
                        
                        .col-lg-6 {
                            padding: 0 15px;
                            margin-bottom: 2rem;
                        }
                    }
                }
                
                .glass-card {
                    background: white;
                    border-radius: 20px;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                    transition: all 0.3s ease;
                    height: 100%;
                    overflow: hidden;
                    
                    &:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
                    }
                    
                    .card-header {
                        padding: 2rem 2rem 1rem;
                        border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                        background: transparent;
                        
                        .card-title {
                            font-size: 1.5rem;
                            font-weight: 700;
                            margin: 0;
                            color: #2c3e50;
                            display: flex;
                            align-items: center;
                            gap: 0.75rem;
                            
                            i {
                                color: var(--nown-primaria);
                                font-size: 1.3rem;
                            }
                        }
                    }
                    
                    .card-body {
                        padding: 2rem;
                        
                        .amount-grid {
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                            gap: 1rem;
                            margin-bottom: 2rem;
                            
                            .amount-option {
                                position: relative;
                                border-radius: 12px;
                                border: 2px solid #e9ecef;
                                background-color: white;
                                cursor: pointer;
                                height: 85px;
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                                align-items: center;
                                padding: 1rem;
                                transition: all 0.3s ease;
                                overflow: hidden;
                                
                                &:hover {
                                    border-color: var(--nown-primaria);
                                    background-color: rgba(236, 1, 102, 0.05);
                                    transform: translateY(-3px);
                                    box-shadow: 0 8px 25px rgba(236, 1, 102, 0.15);
                                }
                                
                                &.selected {
                                    border-color: var(--nown-primaria);
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(236, 1, 102, 0.05));
                                    transform: translateY(-3px);
                                    box-shadow: 0 8px 25px rgba(236, 1, 102, 0.2);
                                    
                                    &::before {
                                        content: '';
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 4px;
                                        height: 100%;
                                        background: var(--nown-primaria);
                                    }
                                }
                                
                                input {
                                    position: absolute;
                                    opacity: 0;
                                    pointer-events: none;
                                }
                                
                                .amount-value {
                                    font-size: 1.1rem;
                                    font-weight: 700;
                                    color: #2c3e50;
                                    margin: 0;
                                    
                                    i {
                                        color: var(--nown-primaria);
                                    }
                                }
                                
                                .amount-label {
                                    font-size: 0.8rem;
                                    color: #6c757d;
                                    margin-top: 0.25rem;
                                    font-weight: 500;
                                }
                            }
                        }
                        
                        .custom-amount-container {
                            margin: 1.5rem 0 2rem;
                            border-radius: 12px;
                            border: 2px solid #e9ecef;
                            background: white;
                            overflow: hidden;
                            transition: all 0.3s ease;
                            
                            &:focus-within {
                                border-color: var(--nown-primaria);
                                box-shadow: 0 0 0 0.2rem rgba(236, 1, 102, 0.15);
                            }
                            
                            .input-group-text {
                                background: transparent;
                                border: none;
                                font-weight: 600;
                                color: #2c3e50;
                                padding-left: 1.25rem;
                                font-size: 1.1rem;
                            }
                            
                            .form-control {
                                border: none;
                                box-shadow: none;
                                color: #2c3e50;
                                font-size: 1.1rem;
                                font-weight: 600;
                                padding: 1.25rem 1rem;
                                background-color: transparent;
                                
                                &:focus {
                                    outline: none;
                                    box-shadow: none;
                                    background-color: transparent;
                                }
                                
                                &::placeholder {
                                    color: #6c757d;
                                    font-weight: 400;
                                }
                            }
                        }
                        
                        .premium-alert {
                            background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(33, 150, 243, 0.05));
                            border-left: 4px solid var(--bs-lightblue);
                            border-radius: 12px;
                            padding: 1.25rem;
                            margin-bottom: 2rem;
                            display: flex;
                            gap: 1rem;
                            align-items: flex-start;
                            
                            .alert-icon {
                                color: var(--bs-lightblue);
                                font-size: 1.25rem;
                                margin-top: 0.125rem;
                                flex-shrink: 0;
                            }
                            
                            .alert-content {
                                color: #6c757d;
                                font-size: 0.9rem;
                                line-height: 1.6;
                                
                                strong {
                                    color: #2c3e50;
                                    font-weight: 600;
                                }
                            }
                        }
                        
                        .amount-summary {
                            background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(236, 1, 102, 0.05));
                            border-radius: 12px;
                            padding: 1.5rem;
                            margin-bottom: 2rem;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border: 1px solid rgba(236, 1, 102, 0.2);
                            
                            .summary-label {
                                font-size: 1rem;
                                color: #2c3e50;
                                font-weight: 600;
                            }
                            
                            .summary-value {
                                font-size: 1.75rem;
                                font-weight: 800;
                                color: var(--nown-primaria);
                                letter-spacing: -0.02em;
                            }
                        }
                        
                        .btn-premium {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 0.75rem;
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            color: white;
                            border: none;
                            border-radius: 12px;
                            padding: 1.25rem 1.5rem;
                            font-size: 1rem;
                            font-weight: 600;
                            width: 100%;
                            transition: all 0.3s ease;
                            position: relative;
                            overflow: hidden;
                            box-shadow: 0 8px 25px rgba(236, 1, 102, 0.3);
                            
                            &::before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: -100%;
                                width: 100%;
                                height: 100%;
                                background: rgba(255, 255, 255, 0.1);
                                transform: skewX(-20deg);
                                transition: all 0.6s ease;
                            }
                            
                            &:hover {
                                background: linear-gradient(135deg, var(--nown-primaria-darker), var(--nown-primaria));
                                color: white;
                                transform: translateY(-3px);
                                box-shadow: 0 12px 35px rgba(236, 1, 102, 0.4);
                                
                                &::before {
                                    left: 100%;
                                }
                            }
                            
                            &:active {
                                transform: translateY(-1px);
                            }
                            
                            i {
                                font-size: 1.1rem;
                            }
                        }
                        
                        .faq-accordion {
                            margin-top: 0.5rem;
                            
                            .accordion-item {
                                border: 1px solid #e9ecef;
                                background: white;
                                border-radius: 12px;
                                margin-bottom: 1rem;
                                overflow: hidden;
                                transition: all 0.3s ease;
                                
                                &:hover {
                                    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                                }
                                
                                .accordion-header {
                                    margin: 0;
                                    
                                    .accordion-button {
                                        padding: 1.5rem;
                                        font-weight: 600;
                                        color: #2c3e50;
                                        background: white;
                                        border: none;
                                        box-shadow: none;
                                        transition: all 0.3s ease;
                                        
                                        &:not(.collapsed) {
                                            color: var(--nown-primaria);
                                            background: rgba(236, 1, 102, 0.05);
                                            font-weight: 700;
                                        }
                                        
                                        &:focus {
                                            box-shadow: none;
                                            border-color: var(--nown-primaria);
                                        }
                                        
                                        &::after {
                                            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23ec0166' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
                                        }
                                    }
                                }
                                
                                .accordion-body {
                                    padding: 1.5rem;
                                    background: #f8f9fa;
                                    color: #6c757d;
                                    line-height: 1.6;
                                    font-size: 0.95rem;
                                    
                                    strong {
                                        color: #2c3e50;
                                        font-weight: 600;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /* Dark Mode */
        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            
            #secao-deposito {
                .content-wrapper {
                    .glass-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .card-header {
                            border-bottom-color: rgba(236, 1, 102, 0.2);
                            
                            .card-title {
                                color: var(--nown-terciaria);
                            }
                        }
                        
                        .card-body {
                            .amount-grid {
                                .amount-option {
                                    background-color: #3a3a3a;
                                    border-color: #555;
                                    
                                    &:hover {
                                        background-color: rgba(236, 1, 102, 0.1);
                                        border-color: var(--nown-primaria);
                                    }
                                    
                                    &.selected {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.15), rgba(236, 1, 102, 0.08));
                                        border-color: var(--nown-primaria);
                                    }
                                    
                                    .amount-value {
                                        color: var(--nown-terciaria);
                                    }
                                    
                                    .amount-label {
                                        color: #adb5bd;
                                    }
                                }
                            }
                            
                            .custom-amount-container {
                                background: #3a3a3a;
                                border-color: #555;
                                
                                .input-group-text {
                                    color: var(--nown-terciaria);
                                }
                                
                                .form-control {
                                    color: var(--nown-terciaria);
                                    
                                    &::placeholder {
                                        color: #adb5bd;
                                    }
                                }
                            }
                            
                            .premium-alert {
                                background: linear-gradient(135deg, rgba(173, 216, 230, 0.15), rgba(173, 216, 230, 0.08));
                                
                                .alert-content {
                                    color: #adb5bd;
                                    
                                    strong {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .amount-summary {
                                background: linear-gradient(135deg, rgba(236, 1, 102, 0.15), rgba(236, 1, 102, 0.08));
                                border-color: rgba(236, 1, 102, 0.3);
                                
                                .summary-label {
                                    color: var(--nown-terciaria);
                                }
                            }
                            
                            .faq-accordion {
                                .accordion-item {
                                    background: #3a3a3a;
                                    border-color: #555;
                                    
                                    .accordion-header {
                                        .accordion-button {
                                            background: #3a3a3a;
                                            color: var(--nown-terciaria);
                                            
                                            &:not(.collapsed) {
                                                background: rgba(236, 1, 102, 0.1);
                                                color: var(--nown-primaria);
                                            }
                                        }
                                    }
                                    
                                    .accordion-body {
                                        background: #2d2d2d;
                                        color: #adb5bd;
                                        
                                        strong {
                                            color: var(--nown-terciaria);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /* Responsividade */
        @media (max-width: 992px) {
            #secao-deposito {
                .content-wrapper {
                    .glass-card {
                        .card-body {
                            padding: 1.5rem;
                            
                            .amount-grid {
                                grid-template-columns: repeat(2, 1fr);
                            }
                        }
                    }
                }
            }
        }

        @media (max-width: 768px) {
            #secao-deposito {
                .content-wrapper {
                    .glass-card {
                        .card-header {
                            padding: 1.5rem 1.5rem 1rem;
                            
                            .card-title {
                                font-size: 1.3rem;
                            }
                        }
                        
                        .card-body {
                            .amount-summary {
                                .summary-value {
                                    font-size: 1.5rem;
                                }
                            }
                        }
                    }
                }
            }
        }

        @media (max-width: 576px) {
            #secao-deposito {
                .content-wrapper {
                    .container {
                        .col-lg-6 {
                            margin-bottom: 1.5rem;
                        }
                    }
                    
                    .glass-card {
                        .card-body {
                            .amount-grid {
                                grid-template-columns: repeat(1, 1fr);
                                
                                .amount-option {
                                    height: 75px;
                                }
                            }
                            
                            .card-header {
                                padding: 1.25rem;
                            }
                        }
                    }
                }
            }
        }
</style>