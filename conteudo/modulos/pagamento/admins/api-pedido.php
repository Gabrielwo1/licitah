<?
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__."/../../../../admin/parser.php";


class CompraItem{
    public $user;
    public $banco;
    public $url;
    public $conn;
    function __construct($banco, $url){
        $this->banco = $banco ?? false;
        $this->url = $url ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->conn = conn();
    }
    
    
    function produto($id){
        $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id IN ($id)";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum produto com esse ID"];
        }
        
        $dado = $resultado->fetch_assoc();

                
                
        return [
            "id"=>$dado["vendavel_id"],
            "banco"=>$dado["vendavel_banco"],	
            "referencia"=>$dado["vendavel_referencia"],
            "preco"=>number_format(floatval($dado["vendavel_preco"]),2),	
            "tipo"=>$dado["vendavel_tipo"],	
            "recorrente"=>$dado["vendavel_recorrente"] == "0" ? false : true,	
            "zera"=>$dado["vendavel_zera"] == "0" ? false : true,	
            "limitador"=>$dado["vendavel_limitador"] == "0" ? false : true,	
            "estoque"=>$dado["vendavel_controleestoque"] == "0" ? false : true,	
            "autor"=>$dado["vendavel_autor"],	
            "data"=>$dado["vendavel_data"],	
            "update"=>$dado["vendavel_update"],	
            "status"=>$dado["vendavel_status"],	
            "visibilidade"=>$dado["vendavel_visibilidade"],	
            "agendamento"=>$dado["vendavel_agendamento"],
            "metas"=>$this->metas($dado["vendavel_id"]),
            "pontos"=>$dado["vendavel_pontos"],
            "metas"=>$this->metas($dado["vendavel_id"]),
            "refbd"=>$dado["vendavel_referenciabd"]
        ];
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
    
    function planodepontos($id){
        
        if(!$this->user){
            return ["erro"=>true, "pontos"=>0, "mensagem"=>"O usuário não está logado"];
        }
        
        $seleciona = "SELECT * FROM pay_pontos WHERE pontos_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "pontos"=>0, "mensagem"=>"Não foi encontrado sistema de pontos"];
        }
        
        $dado = $resultado->fetch_assoc();
        if($dado["pontos_banco"] == 0){
            return ["erro"=>true, "pontos"=>0, "mensagem"=>"O sistema não está vinculado a nenhum banco de dados"];
        }
        
        $metas = [];
        $seleciona = "SELECT * FROM pay_pontos_meta WHERE ppm_pontos='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $metas[$dado["ppm_chave"]] = $dado["ppm_valor"];
            }
        }
        
    
        if(!isset($metas["banco_dados"]) || !$metas["banco_dados"] || !isset($metas["referencia"]) || !$metas["referencia"]){
            return ["erro"=>true, "pontos"=>0, "mensagem"=>"Alguma informaçõa de vinculo não é válida"];
        }
        
        $contador = intval($metas["quantidade"] ?? 1);
        
        $tabela = $metas["banco_dados"];
        
        $sql = "SHOW TABLES LIKE '$tabela'";

        $result = $this->conn->query($sql);
        if ($result && $result->num_rows == 0) {
             return ["erro"=>true, "pontos"=>0, "mensagem"=>"O banco de dados não existe"];
        } 
        
        $coluna = $metas["referencia"];
        $query = "SHOW COLUMNS FROM $tabela LIKE '$coluna'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows == 0) {
            return ["erro"=>true, "pontos"=>0, "mensagem"=>"A coluna não existe"];
        }
        
        $data = false;
        $colunaData = explode("_", $metas["referencia"])[0]."_data";
        $query = "SHOW COLUMNS FROM $tabela LIKE '$colunaData'";
        $result = $this->conn->query($query);
        if ($result->num_rows == 1) {
           $data = true;
        }
        
        
        
        
        $usuario = $this->user;
        $trato = explode("_", $metas["referencia"])[0];
        $array = [$trato."_id"];
        if($data){
            array_push($array, $trato."_data");
        }
        
        $itens = [];
        $implode = implode(",", $array);
        $seleciona = "SELECT $implode FROM $tabela WHERE $coluna='$usuario'";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if($data){
                    array_push($itens, ["id"=>$dado[$trato."_id"], "data"=>$dado[$trato."_data"]]);
                }else{
                    array_push($itens, ["id"=>$dado[$trato."_id"], "data"=>$dado[$trato."_data"]]);
                }

            }
        }
        return ["pontos"=>$resultado->num_rows , "itens"=>$itens];

    }
    
    function pontos($id){
        $usuario = $this->user;
        $seleciona = "SELECT * FROM pay_pontuacao WHERE pontuado_usuario='$usuario' AND pontuado_tipo='$id'";

        $resultado = $this->conn->query($seleciona);
        $pontos = [];
        $soma = 0;
        $expirados = 0;
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($pontos, ["pontos"=>intval($dado["pontuado_pontos"]), "expiracao"=>$dado["pontuado_expiracao"]]);
                $soma += intval($dado["pontuado_pontos"]);
            }
        }
        return ["fluxo"=>$pontos, "pontos"=>$soma, "expirados"=>$expirados];
    }
    
    function pntsDisponiveis($id){
        $pontos = $this->pontos($id);
        $feitos = $this->planodepontos($id);
        
      
        $soma = $póntos["soma"] ?? 0;
        $calc = $soma - $feitos["pontos"];
        if($calc < 0){
            $calc = 0;
        }
        return [
            "disponiveis"=>$calc,
            "comprados"=>$pontos,
            "feitos"=>$feitos
            ];
        
        
    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"O usuário não está logado"];
        }
        
        if(!$this->banco || !$this->url){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os parametros necessarios"];
        }
        
        $this->banco = trim($this->banco);
        $this->url = trim($this->url);
        
        $parse = new Parse($this->banco, $this->url);
        $idproduto = $parse->render();
        if(!$idproduto){
            return ["erro"=>true, "mensagem"=>"O produto não existe"];
        }
 
        $pontos = false;
        $vendavel = $parse->getVendavel();
        if(!$vendavel){
            return ["erro"=>true, "mensagem"=>"O item de venda não existe"];
        }
        
        $produto = $this->produto($vendavel);
        if(!$produto){
            return ["erro"=>true, "mensagem"=>"O produto não existe"];
        }
        $mapa = [];
        $comprados = 0;
        $user = $this->user;
        $recorrente = false;
        $seleciona = "SELECT ppi_pedido, ppi_quantidade FROM pay_pedidos_itens WHERE ppi_item='$vendavel' AND ppi_usuario='$user'";
        $resultado = $this->conn->query($seleciona);
        $pedidos = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($pedidos, $dado["ppi_pedido"]);
                $mapa[$dado["ppi_pedido"]] = $dado["ppi_quantidade"];
            }
            
            if(!empty($pedidos)){
                $ids = implode(",", $pedidos);
                
                $seleciona = "SELECT * FROM pay_pedidos WHERE pedido_id IN ($ids) AND pedido_estado='2'";
                $resultado = $this->conn->query($seleciona);

                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                         $comprados += intval($mapa[$dado["pedido_id"]]); 
                    }
                }
            
                if($produto["recorrente"]){
                    $seleciona = "SELECT * FROM pay_assinaturas WHERE 	payassi_plano='$vendavel' AND payassi_usuario='$user' AND payassi_estado='1'";
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows == 1){
                        $dado = $resultado->fetch_assoc();
                        
                        $inicio = new DateTime($dado["payassi_inicio"]); 
                        $fim = new DateTime($dado["payassi_fim"]);
                        $hoje = new DateTime();
                        if($hoje >= $inicio && $hoje <= $fim) {
                            $recorrente = true;
                        } 
                    }
                }
                
                if($produto["pontos"]){
                    $pontos = $this->pntsDisponiveis($produto["metas"]["pontosTipo"]);
               

                }
                
            }

        }
        

        return ["sucesso"=>true, "produto"=>$produto, "pagos"=>$comprados, "assinante"=>$recorrente, "pontos"=>$pontos];
    }
}

if(isset($_POST["acao"]) && $_POST["acao"] == "apiJsNown"){
    $acao = new CompraItem($_POST["modulo"] ?? false, $_POST["url"] ?? false);
    $resposta = $acao->render();
    echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

?>