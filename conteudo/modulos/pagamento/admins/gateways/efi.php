<?php

class Processador {
    public $producao;
    public $cliente;
    public $chave;
    public $certificado;
    public $url;
    public $end;
    public $resumoPedido;
    public $total;
    public $pedido;
    public $usuario;
    public $token;
    public $retorno;
    public $tipo;
    public $plan;

    function __construct($pedido = false, $usuario = false) {
        $this->pedido = $pedido;
        $this->usuario = $usuario;
        $this->producao = verModulo("configuracoes", "producao", false);
        $this->end = "";
        $this->resumoPedido = [];
        $this->total = 0;
    }

    function definePedido($pedido) {
        $itens = [];
        foreach ($this->pedido["itens"] as $item) {
            $produto = $item["infos"];
            $valor = paraCents($produto["preco"]);
            array_push($itens, [
                "name" => $produto["hash"],
                "amount" => intval($produto["comprado"] ?? 1),
                "value" => $valor
            ]);
        
        }
        $this->resumoPedido["items"] = $itens;
    }

    function load() {
        $this->cliente = $this->producao ? verModulo("efi", "clienteid", false) : verModulo("efi", "clienteidsand", false);
        $this->chave = $this->producao ? verModulo("efi", "chavesecreta", false) : verModulo("efi", "chavesecretasand", false);
        $this->url = $this->producao ? "https://cobrancas.api.efipay.com.br" : "https://cobrancas-h.api.efipay.com.br";
        
        if ($this->cliente && $this->chave) {
            return $this->obterToken();
        }
        return false;
    }

    function obterToken() {
        $urlToken = $this->url . "/v1/authorize";

        $body = json_encode([
            'client_id' => $this->cliente,
            'client_secret' => $this->chave,
            "grant_type"=>"client_credentials"
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $urlToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json"
            ],
            CURLOPT_POSTFIELDS => $body
        ]);

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            return ["erro" => true, "mensagem" => curl_error($curl)];
        }

        $obj = json_decode($response, true);
        $this->token = $obj["access_token"] ?? null;
        return $this->token ? true : ["erro" => true, "mensagem" => "Falha ao obter token"];
    }
    
    function formatPhoneNumber($phone) {
    // Remove caracteres indesejados
    $cleaned = preg_replace('/\D/', '', $phone); // Remove não dígitos

    // Define o padrão para validar o número
    $pattern = '/^[1-9]{2}9?[0-9]{8}$/';

    if (preg_match($pattern, $cleaned)) {
        return $cleaned;
    } else {
        throw new Exception("A string não corresponde ao modelo.");
    }
}

    function pedidoBoleto() {
        $this->tipo = "boleto";
        $data = date('Y-m-d', strtotime('+2 days'));
        $this->resumoPedido["payment"] = [
            "banking_billet" => [
                "expire_at" => $data,
                "customer" => [
                    "name" => $this->usuario["display"],
                    "email" => $this->usuario["email"],
                    "cpf" => filtrarNumeros($this->usuario["cpf"]),
                    "birth" => "1977-01-15",
                    "phone_number" => $this->formatPhoneNumber($this->usuario["telefone"])
                ]
            ]
        ];
    }
    
    function pedidoPix(){
         $this->tipo = "pix";
        $data = date('Y-m-d', strtotime('+1 days'));
        $this->resumoPedido["payment"] = [
            "banking_billet" => [
                "expire_at" => $data,
                "customer" => [
                    "name" => $this->usuario["display"],
                    "email" => $this->usuario["email"],
                    "cpf" => filtrarNumeros($this->usuario["cpf"]),
                    "birth" => "1977-01-15",
                    "phone_number" => $this->formatPhoneNumber($this->usuario["telefone"])
                ]
            ]
        ];
    }
    
    function pedidoCredito(){
          $this->tipo = "cartao";
             $this->resumoPedido["payment"] = [
                 "credit_card" => [
                     "customer" => [
                         "name" => $this->usuario["display"],
                         "cpf" => filtrarNumeros($this->usuario["cpf"]),
                         "email" => $this->usuario["email"],
                         "birth" => "1990-08-29",
                         "phone_number" => $this->formatPhoneNumber($this->usuario["telefone"])
                    ],
                    "installments" => 1,
                    "payment_token" => "",
                    "billing_address" => [
                        "street" => "Avenida Juscelino Kubitschek",
                        "number" => "909",
                        "neighborhood" => "Bauxita",
                        "zipcode" => "35400000",
                        "city" => "Ouro Preto",
                        "complement" => "",
                        "state" => "MG"
                        ]
                 ]];
                 
    }
    
    function plano($nome, $intervalo, $repeticao){
             $urlToken = $this->url . "/v1/plan";

        $body = json_encode([
            "name"=> $nome,
            "interval"=> intval($intervalo),
            "repeats"=> intval($repeticao)
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
        $this->plan = $obj["data"]["plan_id"] ?? null;
        return $this->plan ? true : ["erro" => true, "mensagem" => "Falha ao obter plano"];
    }
    
    function charge($token){
        $this->end = '/v1/notification/'.$token;
        $pega = $this->request('[]');
        $this->log($pega);

    }
    
    function log($pega){
        $log_path = __DIR__.'/../../logs';

        $request_data = array(
            'conteudo' => $pega
        );
        
        $timestamp_seconds = strtotime(date('Y-m-d H:i:s'));
        $log_filename = $log_path . '/teste.json';
        file_put_contents($log_filename, json_encode($request_data, JSON_PRETTY_PRINT));
    }
        
    function cobrar() {
        if(count($this->pedido["itens"]) == 1 && $this->pedido["itens"][0]["infos"]["recorrente"] == 1){
            // Pedido Recorrente
            $plano = $this->pedido["itens"][0];
            $ciclo = $plano["infos"]["metas"]["ciclo"];
            $cobrar = $plano["infos"]["metas"]["cobrar"];
            $vezes = $plano["infos"]["metas"]["vezes"] ?? 120;
            $nome = $plano["infos"]["infos"]["nome"];
            
            $plano = $this->plano($nome, $cobrar , $vezes);
            
            if(!$this->plan){
                return ["erro"=>true, "mensagem"=>"Erro ao gerar plano"];
            }
            
            $plano = $this->plan;
            $this->end = "/v1/plan/$plano/subscription/one-step";
            
            
        }else{
            // Pedido Normal
            $this->end = "/v1/charge/one-step";
        }
        
        $dominio = "https://".$_SERVER['SERVER_NAME'];
        $this->resumoPedido["metadata"] = array('notification_url'=>$dominio."/conteudo/modulos/pagamento/admins/notificacao.php?meio=efi");
        
        
        $body = json_encode($this->resumoPedido);
        $obj = $this->request($body);
  
        $id = $this->plan ? $obj["data"]["charge"]["id"]  : $obj["data"]["charge_id"];
        switch($this->tipo){
            case 'boleto':
                return [
                    "id"=>$id,
                    "codigo" => $obj["data"]["barcode"],
                    "link" => $obj["data"]["pdf"]["charge"],
                    "qrcode"=>$obj["data"]["pix"]["qrcode"],
                    "vencimento"=>$obj["data"]["expire_at"]

                ];
                break;
            case 'pix':
        
                 return [
                "id"=>$id,
                "expiracao"=>$obj["data"]["expire_at"],
                "codigo"=>$obj["data"]["pix"]["qrcode"],
                ];
                break;
        }
        
        
        
    }

    function request($body) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . $this->end,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                "Authorization: Bearer $this->token",
                "X-Idempotency-Key: " . $this->pedido["hash"]
            ]
        ]);

        $response = curl_exec($curl);
        
   
        if (curl_error($curl)) {
            return ["erro" => true, "mensagem" => "Não foi possível fazer a solicitação"];
        }

        $obj = json_decode($response, true);
    
        if(isset($obj["error"])){
            return false;
        }
        
       
        return ($obj["status"] ?? null) == 403 ? ["erro" => true, "mensagem" => $obj["message"]] : $obj;
    }
}

?>
