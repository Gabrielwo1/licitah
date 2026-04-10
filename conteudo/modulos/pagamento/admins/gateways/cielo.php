<?
require_once(__DIR__.'/../bibliotecas/vendor/autoload.php');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Cielo {
    private $merchantId;
    private $merchantKey;
    private $client;
    private $url;

    public function __construct($merchantId, $merchantKey) {
        $this->merchantId = $merchantId;
        $this->merchantKey = $merchantKey;
        $this->client = new Client();
        $this->url = "https://apisandbox.cieloecommerce.cielo.com.br/1/sales/";
        
        
    }

    public function realizarPagamento(array $paymentData) {
        try {
            $response = $this->client->request('POST', $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'MerchantId' => $this->merchantId,
                    'MerchantKey' => $this->merchantKey
                ],
                'json' => $paymentData
            ]);

            $content = json_decode($response->getBody(), true);
            return $content;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return ['error' => true, 'message' => $e->getResponse()->getBody()->getContents()];
            } else {
                return ['error' => true, 'message' => $e->getMessage()];
            }
        } catch (\Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}

$cielo = new Cielo('2891800901', '91WJp6Tc7dr7zhLhJgItMah6XAe3VAMWhkSneePw');

$paymentData = [
    'MerchantOrderId' => '2014111703',
    'Customer' => [
        'Name' => 'Comprador Teste'
    ],
    'Payment' => [
        'Type' => 'CreditCard',
        'Amount' => 15700,
        'Installments' => 1,
        'CreditCard' => [
            'CardNumber' => '0000000000000001',
            'Holder' => 'Teste Holder',
            'ExpirationDate' => '12/2021',
            'SecurityCode' => '123',
            'Brand' => 'Visa'
        ]
    ]
];

$result = $cielo->realizarPagamento($paymentData);
print_r($result);
?>