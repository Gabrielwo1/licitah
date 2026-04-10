<?php
require_once(__DIR__.'/../bibliotecas/vendor/autoload.php');



       
class Processador{
    public $token;
    public $url;
    public $end;
    public $pedido;
    public $usuario;
    public $resumoPedido;
    public $producao;
    public $total;
    public $retorno;
    public $idPedido;
    public $tipo;
     
    function __construct($pedido = false, $usuario = false){
        
        
        $this->producao = verModulo("configuracoes", "producao" ,false);
        
        
        $this->pedido = $pedido;
        $this->usuario = $usuario;
        $this->total = 0;
        $this->retorno = [];
        $this->tipo = false;
    
        $this->resumoPedido = [];
        

    }
    
    function load(){
        if($this->producao){

            $this->token = verModulo("pagbank", "tokenproducao" ,false);
            $this->url = "https://api.pagseguro.com/";
        }else{
            $this->token = verModulo("pagbank", "tokensandbox" ,false);
            $this->url = "https://sandbox.api.pagseguro.com/";
        }
        
        return $this->token;
        
    }
    
    function chavePublica(){
        $this->end = "public-keys";
        $body = '{"type":"card"}';
        $resposta = $this->request($body);
        $this->chave = $resposta["public_key"];
    }
    


    function definePedido($pedido){
        $this->resumoPedido["reference_id"] = $pedido;
        $this->resumoPedido["notification_urls"] = ["https://nown.com.br/conteudo/modulos/pagamento/admins/notificacao.php?meio=pagbank"];
        
        $this->idPedido = $pedido;
        
        $this->resumoPedido["customer"] = [
            "name" =>  $this->usuario["display"],
            "email" => $this->usuario["email"],
            "tax_id" => filtrarNumeros($this->usuario["cpf"]),
            "phones" => [
                [
                    "country" => 55,
                    "area" => 35,
                    "number" => 992574384,
                    "type" => "MOBILE"
                ]
                ]
            ];
        
        $this->resumoPedido["shipping"] = [
        "address" => [
            "street" => "Rua Rio Grande do Sul",
            "number" => "268",
            "city" => "Pocos de Caldas",
            "region_code" => "MG",
            "country" => "BRA",
            "postal_code" => "37701143",
            "locality" => "Centro"
        ]];
        
        
        $itens = [];
        

        
        foreach($this->pedido["itens"] as $item){
            $produto = $item["infos"];
            


            $valor = paraCents($produto["preco"]);
            array_push($itens,  [
            "name" => $produto["hash"],
            "quantity" => intval($produto["comprado"] ?? 1),
            "unit_amount" => $valor
            ]);
            $this->total = calculadora($valor , intval($produto["comprado"] ?? 1), $this->total);
        }
        
        $this->resumoPedido["items"] = $itens;
        

    }
    
    function pedidoCredito($cartao){
        $this->end = "orders"; 
       $this->resumoPedido["charges"] = [
           [
               "reference_id" => $this->idPedido,
               "description" => "TesteCompra",
               "amount" => [
                   "value" => 500,
                   "currency" => "BRL"
                   ],
               "payment_method" => [
                       "type" => "CREDIT_CARD",
                       "soft_descriptor"=>"Nown",
                       "installments" => 1,
                       "capture" => true,
                       "card" => [
                           "encrypted" => $cartao["encriptado"],
                           "store" => false
                           ],
                    "holder" => [
                    "name" => $cartao["titular"],
                    "tax_id" => $cartao["documentoTratado"]
                    ]
                ]
            ]
            
        ];
    }
    
    function pedidoPrimeiraRecorrencias(){
        $this->resumoPedido["charges"] = [
    [
        "reference_id" => "referencia_da_cobranca",
        "description" => "descricao_da_cobranca",
        "amount" => [
            "value" => 500,
            "currency" => "BRL"
        ],
        "payment_method" => [
            "type" => "CREDIT_CARD",
            "installments" => 1,
            "capture" => true,
            "card" => [
                "encrypted" => "VfC6DIK1XyGymJHYLjG+XVUeqPdb44UopeCZukfpY1TPy1tVI1ic79ikrLT6wSk/w6u01T8y4Qqcp9hzJZPAcmLfXE52OXTqPGimo2u/ET/HQnHlWNpLdc2aYs2rYwiqoHdoArjUHU2cdAdMF2pZjskvvxxd3rmhH53JTletpoIuqOs9oqVkajfu3GPb9pV/bnBJ5jWCGgrfjU8UGHcKCRtLO4Dpns7cj59NloRyEn1zNx5YP4OwHoZ6z0mFzlFlzcwjbjoaI7F8AVvCkd4MHJB5WwenkKHq107bkcqIH2mK/MVes7kBx9WtgU98ZIgc8RHSLu70Gy0YSmTFAo06pg==",
                "security_code" => "123",
                "holder" => [
                    "name" => "Jose da Silva",
                    "tax_id" => "65544332211"
                ],
                "store" => true
            ]
        ],
        "recurring" => [
            "type" => "INITIAL"
        ]
    ]
];
    }
    
    function pedidoMaisRecorrencias(){
        $this->resumoPedido["charges"] = $charges = [
            [
                "reference_id" => "referencia_da_cobranca",
                "description" => "descricao_da_cobranca",
                "amount" => [
                    "value" => 500,
                    "currency" => "BRL"
                    ],
                    "payment_method" => [
                        "type" => "CREDIT_CARD",
                        "installments" => 1,
                        "capture" => true,
                        "card" => [
                            "id" => "CARD_CCFE8D12-79E9-4ADF-920B-A54E51D8DA6E",
                            "holder" => [
                                "name" => "Jose da Silva",
                                "tax_id" => "65544332211"
                                ],
                            "store" => true
                            ]
        ],
        "recurring" => [
            "type" => "SUBSEQUENT"
        ]
    ]
];
    }
    
    function pedidoPix(){
        $this->tipo = "pix";
        $this->end = "orders"; 
        
        $this->resumoPedido["qr_codes"] = [
        [
            "amount" => [
                "value" => intval($this->total)
            ]
        ]
        ];


        
    }
    
    function pedidoBoleto(){
         $this->tipo = "boleto";
        $this->end = "orders"; 
        $data = date('Y-m-d', strtotime('+2 days'));

        
        $this->resumoPedido["charges"] = [
    [
        "reference_id" => "44654654",
        "description" => "um boleto muito louco",
        "amount" => [
            "value" => intval($this->total),
            "currency" => "BRL"
        ],
        "payment_method" => [
            "type" => "BOLETO",
            "boleto" => [
                "due_date" => $data,
                "instruction_lines" => [
                    "line_1" => "Pagamento processado para DESC Fatura",
                    "line_2" => "Via PagSeguro"
                ],
                "holder" => [
                    "name" => $this->usuario["display"],
                    "tax_id" => filtrarNumeros($this->usuario["cpf"]),
                    "email" => $this->usuario["email"],
                    "address" => [
                        "country" => "Brasil",
                        "region" => "São Paulo",
                        "region_code" => "SP",
                        "city" => "Sao Paulo",
                        "postal_code" => "01452002",
                        "street" => "Avenida Brigadeiro Faria Lima",
                        "number" => "1384",
                        "locality" => "Pinheiros"
                    ]
                ]
            ]
        ]
    ]
];


        
    }
    
    function cobrar(){
    
        $body = json_encode($this->resumoPedido);
        
        $resposta = $this->request($body);
        
        
        print_r($resposta);
        switch($this->tipo){
            case 'pix':
                 return [
                "id"=>$resposta["id"],
                "expiracao"=>$resposta["expiration_date"],
                "codigo"=>$resposta["text"],
                ];
                break;
            case 'boleto':
                return json_encode([
                "id"=>$resposta["id"],
                "expiracao"=>$resposta["expiration_date"],
                "codigo"=>$resposta["text"],
                ]
                );
                break;
            case 'cartao':
                 return [
                    "id"=>$resposta["id"],
                    "transaocao"=>$resposta,
                    "pago"=>$resposta["charges"][0]["status"] == "PAID" ? true: false,
                ];
                break;
        }
        
        
    }
    
    function consultarPedido($pedido){
        $this->end = "orders/".$pedido; 
        
        $this->request('{}');

    }
    
    function split(){
        
        $splits = [];
        $splits["method"] = "PERCENTAGE";
        
        
        
        $receivers = [];
        
        foreach([1,2,3] as $item){
            array_push($receivers , [
                "account"=>["id"=>"ACCO_12345"],
                "amount"=>["value"=>"60"]
                ]);
        }
        
          $splits["receivers"] = $receivers;
        
        /*
        PERCENTAGE
        FIXED
        */
        
        
        
    }
    
    function pegaRetorno($tipo){
        if(isset($this->retorno["erro"]) && $this->retorno["erro"]){
            return false;
        }
        switch($tipo){
            case 'pix':
                return [
                "id"=>$this->retorno["id"],
                "expiracao"=>$this->retorno["expiration_date"],
                "codigo"=>$this->retorno["text"],
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
                    "pago"=>$this->retorno["charges"][0]["status"] == "PAID" ? true: false,
                ];
                break;
        }
    }
    
    function request($body) {

    $client = new \GuzzleHttp\Client();
    $url = $this->url . $this->end;
    $token = $this->token;

    try {
        
  
        $response = $client->request('POST', $url, [
            'body' => $body,
            'headers' => [
                'Authorization' => "Bearer $token",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);

        // Decodificar o corpo da resposta
        $content = json_decode($response->getBody(), true);
        $this->retorno = $content;
        return $content;

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        // Tratamento de erros de cliente, como 4xx
        
        return ["erro"=>true, "mensagem"=>json_decode($e->getResponse()->getBody()->getContents(), true)];
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        // Tratamento de erros de servidor, como 5xx
        ["erro"=>true, "mensagem"=>json_decode($e->getResponse()->getBody()->getContents(), true)];
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // Tratamento de problemas na execução da requisição, como problemas de rede
        if ($e->hasResponse()) {
            ["erro"=>true, "mensagem"=>json_decode($e->getResponse()->getBody()->getContents(), true)];
        } else {
            ["erro"=>true, "mensagem"=>json_decode($e->getMessage())];

        }
        return null;
    } catch (\Exception $e) {
        ["erro"=>true, "mensagem"=>$e->getMessage()];
    }
}

    
}


/*
$pagseguro->definePedido();
$pagseguro->pedidoBoleto();
$acao = $pagseguro->cobrar();
print_r($acao);
*/


?>
