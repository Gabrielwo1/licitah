<?
header('Content-Type: application/json; charset=utf-8');

class Api{
    public $acao;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function paises(){
        $url = 'https://restcountries.com/v3.1/all';
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        $countries = json_decode($response, true);
        $lista = [];
        foreach ($countries as $country) {
             $name = $country['translations']['por']['common'] ?? $country['name']['common'];
             $code = $country['cca2'];
             $item = ["t" => $name, "v" => $code];
             if($name == "Brasil"){
                 $item["c"] = true;
             }
             
             $lista[] = $item;
        }
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    
    function render(){
        switch($this->acao){
            case 'paises':
                return $this->paises();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}

$api = new Api();
$resposta = $api->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>