<?
header('Content-Type: application/json; charset=utf-8');
require_once(__DIR__.'/bibliotecas/vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Acao{
    public $cartao;
    
    function __construct(){
        $this->cartao = $_POST["cartao"] ?? ($_GET["cartao"] ?? false);
    }
    
        function valida() {
    $numero = $this->cartao;
    

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
        return "unionPay";
    }


    return "desconhecidade";
    }
    
    function render(){
        
        
        if(!$this->cartao){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um cartão válido"];
        }
        
        return ["sucesso"=>true, "bandeira"=>$this->valida()];
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);






?>