<?
session_start();
include __DIR__."/../../../../admin/conn.php";
class Infos{
    function __construct(){
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function close(){
        $this->conn->close();
    }
    

    
    function meusPagamentos(){
   $stmt = $this->conn->prepare("
        SELECT p.*, pp.*
        FROM pay_pagamentos p
        LEFT JOIN pay_pedidos pp ON p.pagamento_pedido = pp.pedido_id
        WHERE p.pagamento_usuario = ? ORDER BY p.pagamento_id DESC 
    ");
    $stmt->bind_param("i", $this->user);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $pedidos = [];

    while($dado = $resultado->fetch_assoc()) {
        $pedidos[] = [
          
            "estado" =>$dado["pagamento_estado"],
            "pagamento_tipo"=>$dado["pagamento_tipo"],
            "pagamento_dados"=>$dado["pagamento_dados"],
            "pagamento_getways"=>$dado["pagamento_getways"],
            "pagamento_valor"=>$dado["pagamento_valor"],
            "pagamento_hash"=>$dado["pagamento_hash"],
            "pagamento_data"=>$dado["pagamento_data"],
            "pagamento_update"=>$dado["pagamento_update"],
            "pedido_estado"=>$dado["pedido_estado"],
            "pedido_hash"=>$dado["pedido_hash"],
            "pedido_url"=>$dado["pedido_url"],
            "pedido_data"=>$dado["pedido_data"],
            "pedido_tipo"=>$dado["pedido_tipo"]
            ];
    }
    
    return ["sucesso"=>true, "lista"=>$pedidos];
    
    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        switch($this->acao){
            case 'meus-pagamentos':
                return $this->meusPagamentos();
                break;
            case 'meus-pedidos':
                return $this->meusPedidos();
                break;
            case 'minhas-assinaturas':
                return $this->minhasAssinaturas();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é valida"];
                break;
        }
    }
}


$info = new Infos();
$resposta = $info->reNder();
$info->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>