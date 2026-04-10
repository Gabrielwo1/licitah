<?
if(!logado()){
    restrito();
    return;
}


function calcularDiferencaMinutos($dataString){
    // Criar um objeto DateTime para a data fornecida
    $data = new DateTime($dataString);

    // Obter a data e hora atual
    $agora = new DateTime();

    // Calcular a diferença entre as duas datas
    $diferenca = $data->diff($agora);

    // Converter a diferença para minutos
    $diferencaMinutos = ($diferenca->days * 24 * 60) + ($diferenca->h * 60) + $diferenca->i;

    return $diferencaMinutos;
}

$usuario = $_SESSION["id"];
    $conn = conn();
    $selecionar = "SELECT * FROM pay_pedidos WHERE pedido_usuario='$usuario' AND pedido_estado='1'";
    $resultado = $conn->query($selecionar);
     
    ?>
     <div class="container my-4">
            
            <h1 class="fs-24 fw-700 mb-4">Pagamentos em Aberto</h1>
            <div class="card card-nown">

            <div class="card-body">
                <ul class="list-group list-group-flush">

                    <?
                    $contador = 0;
                    while($dado = $resultado->fetch_assoc()){
                        
                        $pode = true;
                        if(verModulo("configuracoes", "expiracao", false)){
                            $tempo = intval(verModulo("configuracoes", "expiracaotempo", 5));
                            $data = $dado["pedido_data"];
                            
                            if(calcularDiferencaMinutos($data) > $tempo + 2){
                                $pode = false;
                            }

                  

                        }
                        
                        $status  = $dado["pedido_estado"];
                        $url = $dado["pedido_url"];
                        $total = $dado["pedido_total"];
                        $hash = $dado["pedido_hash"];
                        $preco = $total == "0.00" ? "Grátis" : ' R$ '.$total.'';
                        $id = geraId();
                        
                        if($pode){
                            $contador++;
                             echo '
                          <li class="list-group-item">
                        <div class="row">
                            <div class="col-8 d-flex align-items-center">
                              <h2 class="d-flex justify-content-start align-items-center fs-16 m-0 gap-2">
                                <strong class="fw-700">Pedido:</strong> <span class="fw-500">'.$hash.'</span>
                            </h2>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                            <span class="fw-700">
                                '.$preco.'
                                </span>
                            
                            </div>
                            <div class="col-2">
                                <a href="pagamento/'.$url.'" class="btn btn-n-primaria d-flex justify-content-center align-items-center gap-2 d-block w-100">
                                    <span><i class="bi bi-cash-coin"></i></span>
                                    <span>Pagar</span>
                                </a>
                            </div>
                            
                        </div>
                        </li>
                        ';
                        }
                        else{
                            $id = $dado["pedido_id"];
                            $atualiza = "UPDATE pay_pedidos SET pedido_estado='0' WHERE pedido_id='$id'";
                            $conn->query($atualiza);
                        }
                       
                        
                    }
                    
                    if(!$contador){
                         echo '
                          <li class="list-group-item">
                            <h2 class="m-0 fs-22 fw-700 text-center">Não há nada por aqui!</h2>
                        </li>
                        ';
                    }
                    
                    ?>
                    

                    </ul>
            </div>
        </div>
        </div>
    
    <?
?>