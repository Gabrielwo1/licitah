<?

class Pedido{
    public $acao;
    public $conn;
    public $pedido;
    
    function __construct($pedido = false){
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
        $this->pedido = $pedido;
    }
    
    function pedidoInfo(){
        if(!$this->pedido){
            return $lista;
        }   
        

        
        
        $pedido = $this->pedido["id"];
        $seleciona = "SELECT * FROM  pay_pedidos WHERE pedido_hash='$pedido'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
             return false;
        }
        $dado = $resultado->fetch_assoc();
        return [
            "id"=>$dado["pedido_id"],
            "usuario"=>$dado["pedido_usuario"],
            "status"=>intval($dado["pedido_estado"]),
            "carrinho"=>$dado["pedido_carrinho"]
            ];
    }
    
    function pegaPedido(){
        return $this->pedido["itens"];
    }
    
    function pegaInfos($banco, $id){
        
        $result = $this->conn->query("SHOW TABLES LIKE '$banco'");
        if ($result->num_rows == 0) {
            return false;
        }
        
        $prefixo = "SHOW COLUMNS FROM `$banco`";
        $resultado = $this->conn->query($prefixo);
        
        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            $prefixo = explode("_", $linha['Field'])[0];
            $coluna = false;
            while($dado = $resultado->fetch_assoc()){
                if($dado['Field'] == $prefixo."_nome"){
                    $query = $prefixo."_nome";
                    break;
                }
                
                if($dado['Field'] == $prefixo."_titulo"){
                    $query = $prefixo."_titulo";
                    break;
                }
            
            }

        }else{
            return false; 
        }
        
    $inde = $prefixo."_id";
    $seleciona = "SELECT ".$query." FROM $banco WHERE $inde = '$id'";
    $resultado = $this->conn->query($seleciona);
    
    if($resultado->num_rows == 1){
        $dado = $resultado->fetch_assoc();
        return $dado[$query]." - ($banco)";

    } else {
        return false; 
    }
}

    function metas($id){
        $seleciona = "SELECT * FROM vendaveis_meta WHERE vm_vendavel='$id'";
        $resultado = $this->conn->query($seleciona);
        $metas = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $metas[$dado["vm_chave"]] = $dado["vm_valor"];
            }
        }
        return $metas;
    }

    function pegaProduto($id = false){
        $sql = "SHOW TABLES LIKE 'vendaveis'";
        $result = $this->conn->query($sql);
        if($result->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum produto com esse ID"];
        }
        
        
        $id = $id == false ? $this->id : $id;
        $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum produto com esse ID"];
        }
        
        $dado = $resultado->fetch_assoc();
        $produto = [
            "id"=>$dado["vendavel_id"],
            "banco"=>$dado["vendavel_banco"],	
            "referencia"=>$dado["vendavel_referencia"],
            "preco"=>number_format(floatval($dado["vendavel_preco"]),2),	
            "tipo"=>$dado["vendavel_tipo"],	
            "recorrente"=>$dado["vendavel_recorrente"] == "0" ? false : true,	
            "zera"=>$dado["vendavel_zera"] == "0" ? false : true,	
            "limitador"=>$dado["vendavel_limitador"] == "0" ? false : true,	
            "estoque"=>$dado["vendavel_controleestoque"] == "0" ? false : true,	
            "hash"=>$dado["vendavel_hash"],
            "autor"=>$dado["vendavel_autor"],	
            "data"=>$dado["vendavel_data"],	
            "update"=>$dado["vendavel_update"],	
            "status"=>$dado["vendavel_status"],	
            "visibilidade"=>$dado["vendavel_visibilidade"],	
            "agendamento"=>$dado["vendavel_agendamento"],
            "metas"=>$this->metas($dado["vendavel_id"]),
            "pontos"=>$dado["vendavel_pontos"]
            ];
            

            return ["sucesso"=>true, "produto"=>$produto];

    }
    
    function listaItens(){
        $lista = [];
        
        $seleciona = "SELECT vendavel_id,vendavel_banco,vendavel_referencia, vendavel_preco FROM vendaveis";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $id = $dado["vendavel_id"];
                $info = $this->pegaInfos($dado["vendavel_banco"], $dado["vendavel_referencia"]);
                if($info){
                    array_push($lista, ["id"=>$id, "nome"=>$info, "preco"=>$dado["vendavel_preco"]]);
                }
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista, "pedido"=>$this->pegaPedido()];
    }
    
    function calcularDataPassada($dias) {
        $data = new DateTime();
        $data->modify("+$dias days");
        return $data->format('Y-m-d');
    }
    
    function hasher(){
        return bin2hex(random_bytes(16));
    }

    
    function processar(){

        $pedido = $this->pedido;
        

   
        if(!$pedido){
            return ["erro"=>true, "mensagem"=>"O pedido não existe"];
        }
        

        if($pedido["estado"] == 0){
            return ["erro"=>true, "mensagem"=>"O pedido foi cancelado e não pode sofrer alterações"];
        }
        
        if($pedido["estado"] == 3 || $pedido["estado"] == 4){
           return ["erro"=>true, "mensagem"=>"O pedido já foi processado"];
        }
        
    
        
  
        $id = $pedido["id"];
        $update = "UPDATE pay_pedidos SET pedido_estado='2' WHERE pedido_id='$id'";
 
        
        if($this->conn->query($update) == TRUE){
            if(isset($pedido["carrinho"]) && $pedido["carrinho"]){

            $idCarrinho = intval($pedido["carrinho"]);
            $deleta = "DELETE FROM pay_carrinhos WHERE carrinho_id='$idCarrinho'";
            $this->conn->query($deleta);
        }

        
        
        switch($pedido["tipo"]){
            case 0:
                $itens = $this->pegaPedido();
        
                foreach($itens as $item){
            $comprados = $item["infos"]->comprado ?? 1;
            $comprados = intval($comprados);
            
     
            $produto = $this->pegaProduto($item["id"]);
            if(isset($produto["sucesso"])){
                $produto = $produto["produto"];
                
               

                
                if($produto["recorrente"]){
                   $ciclo = new Ciclos($produto["id"], $pedido["usuario"]);
                   $ciclo->assina();
                }
                
                if($produto["estoque"]){
                    $quantidade = $produto["metas"]["estoque"] ?? 0;
                    $calc = $quantidade - $comprados;
                    if($calc < 1){
                        $calc = 0;
                    }
                    
                    $id = $item["infos"]->id;
                    $acao = "UPDATE vendaveis_meta SET vm_valor='$calc' WHERE vm_vendavel='$id' AND vm_chave='estoque'";
                    $this->conn->query($acao);
                    
                }
                
                if($produto["pontos"]){
              
                    
                     $pontos = intval($produto["metas"]["pontos"] ?? 1);
                     $expira = intval($produto["metas"]["pontosExpiracao"] ?? 0);
                     $tipo = intval($produto["metas"]["pontosTipo"] ?? 0);
                     $expiracao = 0;
                     if($expira){
                         $expiracao = 1;
                         $data = $this->calcularDataPassada($expira);
                     }else{
                         $data = $this->calcularDataPassada(365);
                     }
                     
                     
                     
                     $calc = $comprados * $pontos;
                     $usuario = $pedido["usuario"];
                     
                     
         
                     $acao = "INSERT INTO pay_pontuacao 
                     (pontuado_usuario,pontuado_pontos,pontuado_expira,pontuado_tipo,pontuado_expiracao) VALUES 
                     ('$usuario', '$calc', '$expira', '$tipo', '$data')";
                     $this->conn->query($acao);
                     

                }

            }
            }
                break;
            case 1:
                
                
                $usuario = (int)$pedido["usuario"]; 
                $saldo = number_format((float)$pedido["total"], 2, '.', '');
                $hash = $this->hasher();
                
                $seleciona = "SELECT * FROM pay_saldos WHERE pay_saldo_usuario = '$usuario'";
                $resultado = $this->conn->query($seleciona);
                
         
                
                if ($resultado->num_rows == 0) {

    $acao = "
        INSERT INTO pay_saldos 
        (pay_saldo_usuario, pay_saldo_saldo, pay_saldo_hash) 
        VALUES ('$usuario', '$saldo', '$hash')";
} else {

    $dado = $resultado->fetch_assoc();
    $id = (int)$dado["pay_saldo_id"];
    
    $acao = "
        UPDATE pay_saldos 
        SET pay_saldo_saldo = pay_saldo_saldo + $saldo 
        WHERE pay_saldo_id = '$id'";
}


if ($this->conn->query($acao) === false) {
    return ["erro"=>true, "mensagem"=>"Falha ao atualizar pedido"];
}
                

                break;
        }


            
            return ["sucesso"=>true, "mensagem"=>"Pedido processado com Sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao Processar Pedido"];
        }

    }
    
    function render(){
        switch($this->acao){
            case 'listaItens':
                return $this->listaItens();
                break;
            case 'processa':
                return $this->processar();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é válida"];
                break;
        }
    }

}




?>