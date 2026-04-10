<?
class Processador{
    public $pedido;
    public $usuario;
    public $producao;
    public $token;
    public $url;
    public $resumoPedido;
    public $end;
    public $clientid;
    public $total;
    public $idPedido;
    public $retorno;
    public $tipo;
    public $plan;
    public $conn;
    public $tokenCard;
    public $endereco;
    public $titular;
    public $documento;
    function __construct($pedido = false, $usuario = false){
        
        $this->titular = $_POST["titular"] ?? false;
        $this->documento = $_POST["documento"] ?? false;
        
        $this->producao = verModulo("configuracoes", "producao" ,false);
        $this->pedido = $pedido;
        $this->usuario = $usuario;
        $this->resumoPedido = [];
        $this->end = "";
        $this->total = 0;
        $this->conn = conn();
        
        
    }
    
    function load(){
        if($this->producao){
            $this->token = verModulo("mercado-pago", "accesstokenproducao" ,false);
            $this->url = "https://api.mercadopago.com/";
        }else{
            $this->token = verModulo("mercado-pago", "accesstokensand" ,false);
            $this->url = "https://api.mercadopago.com/";
        }
        $this->clientid = verModulo("mercado-pago", "clientid" ,false); 
        if(!$this->clientid){
            return false;
        }
        
        return $this->token;
    }
    
    function colunas($nomeTabela) {
        $query = "SHOW COLUMNS FROM $nomeTabela";
        $result = $this->conn->query($query);
        
        if (!$result) {
            throw new Exception("Erro ao buscar colunas da tabela: " . $this->conn->error);
        }
        
        $array = ["nome", "titulo", "id", "imagem", "categoria", "vendavel", "descricao"];
        
        $colunas = [];
        
        while ($row = $result->fetch_assoc()) {
            foreach ($array as $termo) {
                if (str_ends_with($row['Field'], $termo)) {
                    $colunas[$termo] = $row['Field'];
                break;
            }
        }
    }
    return $colunas;
}   

    function formatarTelefone($telefone) {
        if(!$telefone){
            return [];
        }

    $telefoneLimpo = preg_replace('/\D/', '', $telefone);
    
    $codigoArea = substr($telefoneLimpo, 0, 2);
    $numero = substr($telefoneLimpo, 2);

    return [$codigoArea, $numero];
}

    function cliente(){
        
        if(!isset($this->usuario["display"])){
            return false;
        }
        $idmercado = false;
        $id = $this->usuario["id"];
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='mercadoPagoId'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["um_valor"];
        }
        
        $date = new DateTime($_SESSION["data"]);
        $timezone = new DateTimeZone('-03:00');
        $date->setTimezone($timezone);
        $dataFormatada = $date->format('Y-m-d\TH:i:s.vP');
        
        
        $telefone = $this->formatarTelefone($this->usuario["telefone"]);
        $tratonome = explode(" ", $this->titular ?? $this->usuario["display"]);
        $urlToken = $this->url . "/v1/customers";
        $body = json_encode(
            [
                "email" => $this->usuario["email"],
                "first_name" => $tratonome[0],
                "last_name" => $tratonome[count($tratonome) - 1],
                "phone" => [
                    "area_code" => $telefone[0] ?? false,
                    "number" => $telefone[1] ?? false
                    ],
                "identification" => [
                    "type" => "CPF",
                    "number" => filtrarNumeros($this->documento ?? $this->usuario["cpf"])
                ],
                "default_address" => "Home",
                "address" => [
                    "id" => $this->endereco ? preg_replace('/[^0-9]/', '', $this->endereco["cep"]) : false,
                    "zip_code" => $this->endereco ? preg_replace('/[^0-9]/', '', $this->endereco["cep"]) : false,
                    "street_name" => $this->endereco ? $this->endereco["logradouro"]: false,
                    "street_number" => 1,
                    "city" => [
                        "name"=>$this->endereco  ? $this->endereco["localidade"]: false,
                        ]
                    ],
                    "date_registered" => $dataFormatada,
                    "description" => $this->usuario["usuario"],
                    "default_card" => "None"
                    ]
            
            );
            

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $urlToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "Authorization: Bearer $this->token"
            ],
            CURLOPT_POSTFIELDS => $body
        ]);

        $response = json_decode(curl_exec($curl), true);
   
        if(isset($response["status"]) && $response["status"] == 400){
            
            $url = "https://api.mercadopago.com/v1/customers/search?email=".$this->usuario["email"];
            $authorization = "Bearer ".$this->token;
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: $authorization"
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            } else {
                $obj = json_decode($response, true);
                
                if($obj["paging"]["total"] == 1){
                     $idmercado = $obj["results"][0]["id"];
                }

            }
            curl_close($ch);

        }
        else{
            $idmercado = $response["id"];
        }
        

        if($idmercado){
            $cadastra = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$id', 'mercadoPagoId', '$idmercado')";
            $this->conn->query($cadastra);
            return $idmercado;
        }
        return false;

    }
    
    function setEndereco($endereco){
        $this->endereco = $endereco;
    }

    function definePedido($pedido){
        
    
        $this->end = "v1/payments";
        $itens = [];
        
        
        
        switch(intval($this->pedido["tipo"] ?? 0)){
            case 0:
                foreach($this->pedido["itens"] as $item){
            $produto = $item["infos"];


            $banco = $produto["banco"];
            $colunas = $this->colunas($banco);
            $parse = [];
            foreach($colunas as $chave=>$coluna){
                array_push($parse, $coluna." as ".$chave);
            }
            $parse = implode(",", $parse);
            $vendavel = $colunas["vendavel"];
            $id = $produto["id"];
            $seleciona = "SELECT $parse FROM $banco WHERE $vendavel='$id'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $date = new DateTime($produto["data"]);
                $timezone = new DateTimeZone('-03:00');
                $date->setTimezone($timezone);
                $dataFormatada = $date->format('Y-m-d\TH:i:s.vP');
            
 
                  
            $imagem = false;
            if (isset($colunas["imagem"]) && isset($dado["imagem"])) {
                $imagemArray = json_decode($dado["imagem"], true);
                if (is_array($imagemArray) && count($imagemArray) == 1) {
                    $imagem = $imagemArray[0];
                }
                
            }


  

            array_push($itens, [
                "id" => $id,
                "title" => $dado["titulo"] ?? $dado["nome"] ?? false,
                "description" => $dado["descricao"] ?? false,
                "picture_url" => $imagem ? SETUP["dominio"]."/conteudo/uploads/".$imagem : false,
                "category_id" => $dado["titulo"] ?? $banco ?? false,
                "quantity" => intval($produto["comprado"] ?? 1),
                "unit_price" => round(($produto["preco"] ?? 0), 2),
                "type" => $produto["banco"] ?? false,
                "event_date" => $dataFormatada ?? false,
                "warranty" => false,
                "category_descriptor" => [
                    "passenger" => [],
                    "route" => []
                ]
            ]);

            
            
            $this->total = round($this->total + $produto["preco"], 2);
                
                
                
                
            }
        }
                break;
            case 1:
                 array_push($itens, [
                    "id" => "saldo",
                    "title" => "Adicionar de R$ ".$this->pedido["total"]." em saldo de carteira",
                    "description" => "Adição de  R$ ".$this->pedido["total"]." em saldo de carteira para tranzações futuras",
                    "category_id" => "saldo",
                    "quantity" => 1,
                    "unit_price" => round(($this->pedido["total"] ?? 0), 2),
                    "type" => "saldo",
                    "event_date" => false,
                    "warranty" => false,
                    "category_descriptor" => [
                    "passenger" => [],
                    "route" => []
                ]
            ]);

            $this->total = $this->pedido["total"];
                break;
            case 2:
                $nome = verModulo("faturas", "nome", "");
                array_push($itens, [
                    "id" => "doacao",
                    "title" => "Doação de R$ ".$this->pedido["total"],
                    "description" => "Doação de  R$ ".$this->pedido["total"]." para ".$nome,
                    "category_id" => "doacao",
                    "quantity" => 1,
                    "unit_price" => round(($this->pedido["total"] ?? 0), 2),
                    "type" => "doacao",
                    "event_date" => false,
                    "warranty" => false,
                    "category_descriptor" => [
                    "passenger" => [],
                    "route" => []
                ]
            ]);

            $this->total = $this->pedido["total"];
                       
                break;
        }
        

        
        $this->resumoPedido["additional_info"] = [];
        $this->resumoPedido["additional_info"]["items"] = $itens;
        
        
        $tratonome = explode(" ", $this->titular ?? $this->usuario["display"]);
        $telefone = $this->formatarTelefone($this->usuario["telefone"]);
        

        $this->resumoPedido["additional_info"]["payer"] = [
            "first_name"=>$tratonome[0],
            "last_name"=> $tratonome[count($tratonome) - 1],
            "phone"=> [
                "area_code"=> $telefone[0] ?? false,
                "number"=> $telefone[1] ?? false
            ],
            "address"=> [
                "zip_code"=> $this->endereco && isset($this->endereco["cep"]) ? preg_replace('/[^0-9]/', '', $this->endereco["cep"]) : false,
                "street_name"=>$this->endereco["logradouro"] ?? false,
                "street_number"=>1
                ]
            ];
        
       
        $this->resumoPedido["transaction_amount"] = round($this->total, 2);
        $this->resumoPedido["description"] = verModulo("faturas", "nome", "");
        
        

        $this->resumoPedido["payer"] = [
            "first_name"=>$tratonome[0],
            "last_name"=>$tratonome[count($tratonome) - 1],
            "entity_type"=>"individual",
            
            "email"=> $this->usuario["email"],
            "identification"=> [
                "type"=> "CPF",
                "number"=> filtrarNumeros($this->documento ?? $this->usuario["cpf"])
            ]
        ];
        
     
        
        $cliente = $this->cliente();
        if($cliente){
            $this->resumoPedido["payer"]["type"] = "customer";
            $this->resumoPedido["payer"]["id"] = $cliente;
        }
 
    
        $this->resumoPedido["external_reference"] = $this->pedido["hash"];
        $this->resumoPedido["application_fee"] = null;
        $this->resumoPedido["binary_mode"] = false;
        $this->resumoPedido["campaign_id"] = null;
        $this->resumoPedido["capture"] = true;
        $this->resumoPedido["coupon_amount"] = null;
        $this->resumoPedido["differential_pricing_id"]= null;
        $this->resumoPedido["installments"] = 1;
        $this->resumoPedido["metadata"] = null;
        $this->resumoPedido["notification_url"] = SETUP["dominio"]."/conteudo/modulos/pagamento/admins/notificacao.php?meio=mercado-pago";
        $this->resumoPedido["statement_descriptor"] = "Compra no site ".SETUP["dominio"];
        


    }
    
    function pedidoPix(){
        $this->tipo = "pix";
        $this->resumoPedido["payment_method_id"] = "pix";
    }
    
    function pedidoBoleto(){
        $this->tipo = "boleto";
        $this->resumoPedido["payment_method_id"] = "bolbradesco";
        $this->resumoPedido["transaction_amount"] = $this->total;
    }
    
    function pedidoCredito($dados){
        
        
        
        $this->tipo = "cartao";
        
   
        $this->tokenCard = $dados["token"];
        
        $this->salvaCartao();
        $this->resumoPedido["transaction_amount"] = $dados["transaction_amount"];
        $this->resumoPedido["token"] = $dados["token"];
        $this->resumoPedido["installments"] = $dados["installments"];
        $this->resumoPedido["payment_method_id"] = $dados["payment_method_id"];
        $this->resumoPedido["issuer_id"] = $dados["issuer_id"];
        $this->resumoPedido["payer"] = [
             "email" => $dados["payer"]["email"],
             "identification" => [
                 "type" => $dados["payer"]["identification"]["type"],
                 "number" => $dados["payer"]["identification"]["number"]
                ]
            ];

    }
    
    function pegaRetorno($tipo){
        if(isset($this->retorno["erro"]) && $this->retorno["erro"]){
            return false;
        }
        //collector_id
        

        switch($tipo){
            case 'pix':
                return [
                "id"=>$this->retorno["id"],
                "expiracao"=>false,
                "codigo"=>$this->retorno["point_of_interaction"]["transaction_data"]["qr_code"],
                ];
                break;
            case 'boleto':
                return json_encode([
                "id"=>$this->retorno["id"],
                "expiracao"=>$this->retorno["expiration_date"],
                "codigo"=>$this->retorno["text"],
                ]
                );
                break;
            case 'cartao':
                   return [
                    "id"=>$this->retorno["id"],
                    "transaocao"=>$this->retorno,
                    "pago"=>$this->retorno["status"]  == "approved" ? true: false,
                ];
                break;
        }
    }
    
    function plano($nome, $intervalo, $repeticao, $preco){
             $urlToken = $this->url . "/preapproval_plan";
             
        $diaAtual = date("d");


 
        $body = json_encode([
            "reason" => $nome,
            "auto_recurring" => [
                "frequency" => 1,
                "frequency_type" => "months",
                "repetitions" => 12,
                "billing_day" => $diaAtual,
                "billing_day_proportional" => true,
                "free_trial" => [
                    "frequency" => 0,
                    "frequency_type" => "months"
                ],
                "transaction_amount" => $preco,
                "currency_id" => "BRL"
                ],
                "payment_methods_allowed" => [
                    "payment_types" => [(object)[]
                ],
                "payment_methods" => [
                    (object)[]
                    ]
                    ],
                    "back_url" => SETUP["dominio"]
                    ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $urlToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "Authorization: Bearer $this->token"
            ],
            CURLOPT_POSTFIELDS => $body
        ]);

        $response = curl_exec($curl);
  
        if (curl_error($curl)) {
            return ["erro" => true, "mensagem" => curl_error($curl)];
        }
        
   
        $obj = json_decode($response, true);

        $this->plan = $obj["id"] ?? null;
        
        
        return $this->plan;
    }
    
    function salvaCartao(){
        $token = $this->tokenCard;
        $usuario = $this->cliente();
        
        $url = 'https://api.mercadopago.com/v1/customers/'.$usuario.'/cards';
        $authorization = 'Bearer '.$this->token;
        
        $data = [
            'token' => $token
            ];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $authorization
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $response = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    return false;
                    
                } else {
                   return json_decode($response, true);
                    
                }
                curl_close($ch);

        
        
    }
    
    function cobrar(){
        
        $assinatura = false;
         if(count($this->pedido["itens"]) == 1 && $this->pedido["itens"][0]["infos"]["recorrente"] == 1 && $this->tipo == "cartao"){
             $assinatura = true;
             $plano = $this->pedido["itens"][0];
             $ciclo = $plano["infos"]["metas"]["ciclo"];
             $cobrar = $plano["infos"]["metas"]["cobrar"];
             $vezes = $plano["infos"]["metas"]["vezes"] ?? 120;
             $nome = $plano["infos"]["infos"]["nome"];
             $preco = $plano["infos"]["preco"];
             
             $plano =  $this->plano($nome, $cobrar, $vezes, $preco);
             

             
             $date = new DateTime();
             $date->setTimezone(new DateTimeZone('UTC'));
             $inicio = $date->format('Y-m-d\TH:i:s.v\Z');  
             $date->modify('+10 years');
             $fim = $date->format('Y-m-d\TH:i:s.v\Z');
             
             
                $cartao = $this->salvaCartao();
                 $this->end = "preapproval"; 
                if(isset($this->resumoPedido["token"])){
                   
                    $this->resumoPedido["card_token_id"] =  $this->resumoPedido["token"];
                }
                
        
         
        
                $this->resumoPedido["preapproval_plan_id"] = $plano;
                $this->resumoPedido["reason"] = $nome;
                $this->resumoPedido["external_reference"] = $this->pedido["url"];
                $this->resumoPedido["payer_email"] = $this->resumoPedido["payer"]["email"] ?? $_SESSION["email"];

         
            
                $this->resumoPedido["auto_recurring"] = [
                    "frequency"=> 1,
                    "frequency_type"=> "months",
                    "start_date"=>$inicio,
                    "end_date"=> $fim,
                    "transaction_amount"=> $preco,
                    "currency_id"=> "BRL"
                ];

                $this->resumoPedido["status"] = "authorized";
                


         }
         else{
             if($this->resumoPedido["payer"]["type"] ?? false){
                  unset($this->resumoPedido["payer"]["type"]);
                  unset($this->resumoPedido["payer"]["id"]);
                 
                 
             }
            

         }
         
    

        $body = json_encode($this->resumoPedido);
        

  
        $resposta = $this->request($body);
       
        
   
   
        if(isset($resposta["status"]) && ($resposta["status"] == "400" || $resposta["status"] == "401")){
            return false;
        }
        
        switch($this->tipo){
            case 'pix':
                return [
                "id"=>$resposta["id"],
                "expiracao"=>false,
                "codigo"=>$resposta["point_of_interaction"]["transaction_data"]["qr_code"],
                ];
                break;
            case 'boleto':
                return [
                "id"=>$resposta["id"],
                "vencimento"=>$resposta["date_of_expiration"],
                "codigo"=>$resposta["transaction_details"]["barcode"]["content"],
                "link"=>$resposta["transaction_details"]["external_resource_url"],
                "qrcode"=>false
                ];
                break;
            case 'cartao':
   
                
                 return [
                    "id"=>$resposta["id"],
                    "transaocao"=>$resposta,
                    "pago"=>$resposta["status"]  == "approved" ? true: false,
                ];
                
                
                if($assinatura){
                     return [
                    "id"=>$resposta["id"],
                    "transaocao"=>$resposta,
                    "pago"=>$resposta["status"]  == "authorized" ? true: false,
                ];
                }else{
                     return [
                    "id"=>$resposta["id"],
                    "transaocao"=>$resposta,
                    "pago"=>$resposta["status"]  == "approved" ? true: false,
                ];
           
                }
                
                break;
        }
    
  
    }
    
    function walletConnect(){
        $clientId = $this->usuario["hash"]; 
        $data = [
            'return_uri' => 'https://www.mercadopago.com/',
            'external_flow_id' => 'EXTERNAL_FLOW_ID',
            'external_user' => [
                'id' => 'usertest',
                'description' => 'Test account',
                ],
    'agreement_data' => [
        'validation_amount' => 3.14,
        'description' => 'Test agreement',
    ],
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v2/wallet_connect/agreements?client.id=$clientId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "x-platform-id: nown",
    "Authorization: Bearer $this->token",
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    echo 'Resposta: ' . $response;
}

curl_close($ch);
        
        
        
    }
    
        function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function pegaStatus($id, $direto = false){

        $token = $this->token; 
        
        $url = "https://api.mercadopago.com/v1/payments/{$id}";
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
            ]);
            
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Erro: ' . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                $obj = json_decode($response, true);
                
      
            
                if(isset($obj["status"]) && $obj["status"] == "approved"){
                    if(!$direto){
                         new AprovacaoPedido($id);
                    }else{
                        $url = $obj["external_reference"];
                        $seleciona = "SELECT * FROM  pay_pedidos WHERE pedido_url='$url'";
                        $resultado = $this->conn->query($seleciona);
                        if($resultado->num_rows == 1){
                            $dado = $resultado->fetch_assoc();
                            $pedido = $dado["pedido_id"];
                            $status = $dado["pedido_status"];
                            $usuario = $dado["pedido_usuario"];
                            $total = $dado["pedido_total"];
                            $hash = $this->hasher();
                            if($status != 2){
                                    $dados = json_encode($obj);
                                    $cadastra = "INSERT INTO pay_pagamentos 
                                      (pagamento_pedido , pagamento_tipo , pagamento_estado , pagamento_dados , pagamento_estrangeira, pagamento_usuario, pagamento_getways, pagamento_valor, pagamento_hash) VALUES 
                                      ('$pedido', 'mercado-pago', '1', '$dados' , '$id', '$usuario', '$processador', '$total', '$hash')";
                                    $this->conn->query($cadastra);
                                    new AprovacaoPedido($id);
                                    
                            }
                        
                            
                            
                        }
       
                    }
                   
                }
                
            }
            curl_close($ch);

    }
    
    function request($body) {
        $curl = curl_init();
        $url =  $this->url.$this->end;
        $hash = $this->pedido["hash"];
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "Authorization: Bearer $this->token",
                "X-Idempotency-Key: $hash"
    ],
]);

$response = curl_exec($curl);

$err = curl_error($curl);

$obj = json_decode($response, true);

if($obj["status"] == 403){
    $this->retorno = ["erro"=>true, "mensagem"=>$obj["message"]];
}else{
    $this->retorno = $obj;
}

if ($err) {
    $this->retorno =  ["erro"=>true, "mensagem"=>"Não foi possível fazer a solicitação"];
} 

    return $this->retorno;   
    
    }
    
    function preferencia(){
        $pedido = $_POST["pedido"];
       $items = [];
       foreach($this->resumoPedido["additional_info"]["items"] as $item){
           array_push($items, ["title" => $item["title"],
                "quantity" => $item["quantity"],
                "unit_price" => $item["unit_price"]]);
       }
   
       $data = [
           "items" =>  $items
        ];
        
        $data["back_urls"] = [
            "success"=>SETUP["dominio"],
            "failure"=> SETUP["dominio"],
            "pending"=> SETUP["dominio"]
            ];
        $data["auto_return"] = "approved";
        $data["notification_url"] = SETUP["dominio"]."/conteudo/modulos/pagamento/admins/notificacao.php?meio=mercado-pago-externo";
        $data["external_reference"] = $pedido;
        $data["payer"] = $this->resumoPedido["payer"];
        

        
        $ch = curl_init("https://api.mercadopago.com/checkout/preferences");
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$this->token,
            "Content-Type: application/json",
            "X-Idempotency-Key: $pedido" 
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
   
        if ($response === false) {
            return ["erro"=>true, "erro"=>$ch];
            
        } else {

    $responseData = json_decode($response, true);
    return ["sucesso"=>true, "item"=>$responseData["id"]];
            
        }
        curl_close($ch);


    }

}
