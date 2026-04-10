<?

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/conn.php";

class Acao{
    public $acao;
    public $conn;
    public $user;
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
    }
    
    function bandeira($numero) {



    $primeirosDigitos = substr($numero, 0, 6);


    if (preg_match("/^4/", $numero)) {
        return "visa";
    }

    elseif (preg_match("/^(5[1-5]|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[0-1][0-9]|2720)/", $primeirosDigitos)) {
        return "mastercard";
    }

    elseif (preg_match("/^3[47]/", $primeirosDigitos)) {
        return "american";
    }

    elseif (preg_match("/^(30[0-5]|36|38)/", $primeirosDigitos)) {
        return "diners";
    }

    elseif (preg_match("/^(6011|65|64[4-9]|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-2][0-5]))/", $primeirosDigitos)) {
        return "discover";
    }

    elseif (preg_match("/^(636368|438935|504175|451416|5090(4[89]|67|69|50|74|68|40|45|51|46|66|52|47|42|53|64|60|70))/", $primeirosDigitos)) {
        return "elo";
    }

    elseif (preg_match("/^507860/", $primeirosDigitos)) {
        return "aura";
    }

    elseif (preg_match("/^606282/", $primeirosDigitos)) {
        return "hipercard";
    }

    elseif (preg_match("/^62/", $primeirosDigitos)) {
        return "unionpay";
    }


    return "vazio";
    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function salvaCartao(){
  
        $cartao = $_POST["cartao"] ?? false;
        $cripto = $_POST["cripto"] ?? false;
        $validade = $_POST["validade"] ?? false;
        if(!$cartao || !$cripto || !$validade){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os dados necessarios"];
        }
        
       
        
        $user = $this->user;
        
        $seleciona = "SELECT * FROM pagamento_cartoes WHERE pc_user='$user'";
        $resultado = $this->conn->query($seleciona);

        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if($dado["pc_cripto"] == $cripto){
                    return ["sucesso"=>true, "cartao"=>["numero"=>$dado["pc_numbers"], "hash"=>$dado["pc_hash"], "flag"=>$dado["pc_flag"]]];
                }
            }
        }
        
        $hash = $this->hasher(32);
        $bandeira = $this->bandeira($cartao);
        $last = substr($cartao , -4);
        $cadastra = "INSERT INTO pagamento_cartoes (pc_user,pc_hash,pc_numbers,pc_cripto,pc_flag,pc_validade)  VALUES ('$user', '$hash', '$last', '$cripto', '$bandeira', '$validade')";
        if($this->conn->query($cadastra) == true){
            $update = "UPDATE pagamento_cartoes SET pc_default='0' WHERE pc_user='$user' AND pc_hash !='$hash'";
            $this->conn->query($update);
            
            return ["sucesso"=>true, "cartao"=>["numero"=>$last, "hash"=>$hash, "flag"=>$bandeira]];
        }else{
             return ["erro"=>true, "mensagem"=>"Não foi possível salvar o cartão"];
        }
                

    }
    
    function meusCartoes(){

        $lista = [];
        $user = $this->user;
        $seleciona = "SELECT * FROM pagamento_cartoes WHERE pc_user='$user' ORDER BY pc_default DESC, pc_data DESC";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($lista, [
                    "hash"=>$dado["pc_hash"],
                    "numero"=>$dado["pc_numbers"],
                    "flag"=>$dado["pc_flag"],
                    "validade"=>$dado["pc_validade"],
                    "default"=>$dado["pc_default"]
                    ]);
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        switch($this->acao){
            case 'saveCard':
                return $this->salvaCartao();
                break;
            case 'mycards':
                return $this->meusCartoes();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}



$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>