<?

include __DIR__.'/../../../../admin/conn.php';
include __DIR__."/../../../../admin/config.php";
include __DIR__."/../../../../admin/configmodulo.php";
include __DIR__."/aprovacao-pedido.php";
configModulo("pagamento");



class Alteracao{
    public $body;
    public $meio;
    public $conn;
    
    function __construct(){
        $this->body = json_decode(file_get_contents('php://input'), true);
        $this->meio = $_GET["meio"] ?? "indefinido";
        $this->conn = conn();
    }
    
    function log(){
        $log_path = __DIR__.'/../logs';
        $body = json_decode(file_get_contents('php://input'), true);
        $request_data = array(
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REQUEST_URI'],
            'headers' => getallheaders(),
            'body' => $this->body,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        $timestamp_seconds = strtotime(date('Y-m-d H:i:s'));
        $log_filename = $log_path . '/' . $timestamp_seconds . '_'.$this->meio.'.json';
        file_put_contents($log_filename, json_encode($request_data, JSON_PRETTY_PRINT));
    }
   
    function update(){
  
         switch($this->meio){
             case 'safe2pay':


                 $transacao = $this->body["IdTransaction"];
                 $estado = $this->body["TransactionStatus"]["Code"];

                 $status = [
                     1=>"Pendente",
                     2=>"Processamento",
                     3=>"Autorizado",
                     6=>"Devolvido",
                     7=>"Baixado",
                     11=>"Liberado"
                    ];
                    
                
                if($estado == 3){
                    $this->pagou($transacao);
                }

            break;
        case 'mercado-pago':
        case 'mercadopago':
        case 'mercado-pago-externo':   
          
          $direto = false;
          if($this->meio == "mercado-pago-externo"){
              $direto = true;
          }
            $transacao = $this->body["data"]["id"] ?? false;
            
            if(!$transacao && isset($_GET["id"])){
                $transacao = $_GET["id"];
            }
            
     
            include __DIR__."/gateways/mercadopago.php";
            
            $processador = new Processador();
            $processador->load();
            $processador->pegaStatus($transacao, $direto);
            
 
            break;
        case 'efi':
            $efi = $this->efi();
            
            
            break;
    }
        
        
        
    }
    
    
}

$alteracao = new Alteracao();
$alteracao->log();
$alteracao->update();
?>