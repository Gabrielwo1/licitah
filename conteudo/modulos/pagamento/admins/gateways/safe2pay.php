<?

class Processador{
    public $token;
    public $secret;
    public $producao;
    public $resumoPedido;
    public $pedido;
    public $usuario;
    public $retorno;
    public $dominio;
    
  function __construct($pedido = false, $usuario = false){
       $this->producao = verModulo("configuracoes", "producao" ,false);
       $this->resumoPedido = [];
       
       

       $this->pedido = $pedido;
       $this->usuario = $usuario;
       $this->dominio = "https://".$_SERVER['SERVER_NAME'];
 
       
       $this->retorno = [];
   }
   
   function load(){
       if($this->producao){
           $this->token = verModulo("safe2pay", "tokenproducao" ,false);
           $this->secret = verModulo("safe2pay", "secretproducao" ,false);
       }else{
           $this->token =  verModulo("safe2pay", "tokensand" ,false);
           $this->secret =  verModulo("safe2pay", "secretsand" ,false);
       }
       
       if(!$this->token || !$this->secret){
           return false;
       }
       
       return true;

   }
   
   function extrairNumeros($string) {
    return preg_replace('/\D/', '', $string);
   }
   
   function definePedido(){
       
       $this->resumoPedido["IsSandbox"] = $this->producao ? "true" : "false";
       $this->resumoPedido["Application"] = "Nown";
       $this->resumoPedido["Vendor"] = "Fortram";
       $this->resumoPedido["CallbackUrl"] = $this->dominio."/conteudo/modulos/pagamento/admins/notificacao.php?meio=safe2pay";
       $this->resumoPedido["Reference"] = "TESTE";

       
       $this->resumoPedido["Customer"] = [
           "Name"=> $this->usuario["display"],
           "Identity"=> $this->extrairNumeros($this->usuario["cpf"]),
           "Phone"=>$this->extrairNumeros($this->usuario["telefone"]),
           "Email"=>$this->extrairNumeros($this->usuario["email"]),
           "Address"=>[
                "ZipCode"=>"90670090",
                "Street"=>"Logradouro",
                "Number"=>"123",
                "Complement"=>"Complemento",
                "District"=>"Higienopolis",
                "CityName"=>"Porto Alegre",
                "StateInitials"=>"RS",
                "CountryName"=>"Brasil"
                ]
           ];
       
       $this->resumoPedido["Products"] = [];
       
       
       foreach($this->pedido["itens"] as $i){
           $produto = $i["infos"];

           $quantidade = $produto->comprado ?? 1;

           $item = [
               "Code"=>$i["id"],
               "Description"=>$i["infos"]->infos["nome"],
               "UnitPrice"=>$produto->preco,
               "Quantity"=>$quantidade
               ];
              array_push($this->resumoPedido["Products"], $item);
       }
   }
   
   function pedidoPix(){
       $this->resumoPedido["PaymentMethod"] = "6";
   }
   
   function cobrar(){
        $body = json_encode($this->resumoPedido);
        return $this->request($body);
    }
    
   function pegaRetorno($tipo){

       
       
       if(isset($this->retorno["HasError"]) && $this->retorno["HasError"]){
           return false;
       }
        switch($tipo){
            case 'pix':
                $pedido = $this->retorno["ResponseDetail"];
                return [
                "id"=>$pedido["IdTransaction"],
                "expiracao"=>false,
                "codigo"=>$pedido["Key"],
                "QrCode"=>$pedido["QrCode"]
                ];
                
                
                break;
        }
    }   
    
   function consulta($pedido){
       $pedido = trim($pedido);
       $opts = array(
           'http'=>array(
               'method'=>"GET",
               'header'=>"X-API-KEY:  $this->token"
               )
               );

               


$context = stream_context_create($opts);

$result = file_get_contents("https://api.safe2pay.com.br/v2/transaction/Reference?reference={$pedido}", false, $context);

if ($result === FALSE) { /* Handle error */ }

var_dump($result);


   }    
   
   function request($payload){
       $opts = array(
           'http'=>array(
               'method'=>"POST",
               'header'=>"X-API-KEY: $this->token\r\n" .
               "Content-type: application/json\r\n",
               'content'=> $payload
               )
               );
       
       
       $context = stream_context_create($opts);
       $result = file_get_contents('https://payment.safe2pay.com.br/v2/Payment', false, $context);
       if ($result === FALSE) { 
           
          
       }
       
       $content = json_decode($result, true);
       $this->retorno = $content;
       return $content;
   }

}

?>