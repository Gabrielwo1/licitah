<?

class Ciclos {
    public $acao;
    public $plano;
    public $usuario;
    public $conn;
    public $planoId;
    public $usuarioId;
    function __construct($produto, $usuario) {
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
    
        $this->planoId = $produto;
        $this->usuarioId = $usuario;
        
        $this->plano = $this->pegaPlano();
        $this->usuario = $this->pegaUser();
    }

    function metas($id) {
        $seleciona = "SELECT * FROM vendaveis_meta WHERE vm_vendavel='$id'";
        $resultado = $this->conn->query($seleciona);
        $metas = [];
        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $metas[$dado["vm_chave"]] = $dado["vm_valor"];
            }
        }
        return $metas;
    }

    function pegaPlano() {
        $id = $this->planoId;
        $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id='$id'";

        $resultado = $this->conn->query($seleciona);
        if ($resultado->num_rows == 0) { // Corrigido o operador de comparação
            return false;
        }

        $dado = $resultado->fetch_assoc();

        return [
            "id" => $dado["vendavel_id"],
            "metas" => $this->metas($dado["vendavel_id"])
        ];
    }

    function pegaUser() {
        $hash = $this->usuarioId;
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$hash'";
        $resultado = $this->conn->query($seleciona);

        if ($resultado->num_rows == 0) {
            return false;
        }

        $dado = $resultado->fetch_assoc();
        return [
            "id" => $dado["usuario_id"],
            "funcao" => $dado["usuario_funcao"]
        ];
    }
    
    function incrementarData($dias) {
          $dataAtual = new DateTime();
          $dataAtual->modify("+$dias days");
          return $dataAtual->format('Y-m-d H:i:s');
          
      }

    function mudaFuncao($id, $associar){
        $seleciona = "SELECT * FROM funcoes WHERE funcao_id='$associar'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $update = "UPDATE usuarios SET usuario_funcao='$associar' WHERE usuario_id='$id'";
            $this->conn->query($update);
        }
    }

    function assina() {
        if(!$this->usuario){
            return ["erro"=>true, "O usuario passado não é válido"];
        }
        if(!$this->plano){
            return ["erro"=>true, "O plano enviado não é valido"];
        }
        

        $plano = $this->plano;
        $planoId = $this->planoId;
        

        $ciclo = $plano["metas"]["ciclo"];
        $cobrar = $plano["metas"]["cobrar"] ?? 1;
        
        switch($ciclo){
            case 'mes':
                $dias = 32;
                break;
            case 'ano':
                $dias = 366;
                break;
        }
        
        
        $calc = $dias / $cobrar;
        
        $dataAtual = new DateTime();
        $hoje = $dataAtual->format('Y-m-d H:i:s');
        $fim = $this->incrementarData($calc);
        $id = $this->usuario["id"];
        $sessao = $_SESSION["id"] ?? false;
        
        
        $seleciona = "SELECT * FROM pay_assinaturas WHERE payassi_usuario='$id'AND payassi_plano='$planoId'	AND payassi_estado='1'";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            $acao = "INSERT INTO pay_assinaturas (payassi_usuario,payassi_plano,payassi_estado,payassi_inicio,payassi_fim,payassi_autor) VALUES ('$id', '$planoId', '1', '$hoje', '$fim', '$id')";
        }else{
            $dado = $resultado->fetch_assoc();
            $p = $dado["payassi_id"];
            $acao = "UPDATE pay_assinaturas SET payassi_fim='$fim' WHERE  payassi_id='$p'";
        }
        

       if($this->conn->query($acao) == TRUE){
           $funcao = $this->plano["metas"]["funcao"]  ?? false;
           $associar = $this->plano["metas"]["aoassinar"] ?? false;
           if ($funcao == "1" && $associar) {
               
               $associar = intval($this->plano["metas"]["aoassinar"]);
               $meu = intval($this->usuario["funcao"]);
               if($meu != $associar){
                   $this->mudaFuncao($id, $associar);
               }

               
           }

           
           
           return ["sucesso"=>true, "mensagem"=>"Plano atualizado com sucesso"];
       }else{
          return ["erro"=>true, "mensagem"=>"Falha ao atualizar o plano"];
       }
        
        
        

    }

    function desasina() {
        // Lógica para a função desasina
    }

    function render() {
        switch ($this->acao) {
            case 'assina':
                return $this->assina();
                break;
            case 'desasina':
                return $this->desasina();
                break;
            default:
                return ["erro" => true, "mensagem" => "A ação definida não é válida"];
        }
    }
}

class AprovacaoPedido{
    public $id;
    public $conn;
    public $transacao;
    
    function __construct($transacao){
        $this->transacao = $transacao;
        $this->conn = conn();
        
 
        
        $this->processador($this->transacao);
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
     

            return ["sucesso"=>true, "produto"=>$produto];

    }
    
    function pegaPedido($id){
         $produtos = [];
        $seleciona = "SELECT ppi_item, ppi_quantidade FROM pay_pedidos_itens WHERE ppi_pedido='$id'";
        $resultado = $this->conn->query($seleciona);
        $ids = [];
        $mapa = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($ids, $dado["ppi_item"]);
                $mapa[$dado["ppi_item"]] = $dado["ppi_quantidade"];
    
            }
        }
        
        if(!empty($ids)){
            $ids = implode("," , $ids);
            $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id IN ($ids)";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 0){
                return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum produto com esse ID"];
            }
            
            while($dado = $resultado->fetch_assoc()){

                array_push($produtos, [
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
                    "pontos"=>$dado["vendavel_pontos"],
                    "quantidade"=>$mapa[$dado["vendavel_id"]], 
                    "metas"=>$this->metas($dado["vendavel_id"]),
                    "refbd"=>$dado["vendavel_referenciabd"]
                    ]);
            }
 
        }
        return $produtos;
    }
    
    function verificarPrecoCallback($inputs) {
    // Verifica o array de inputs e busca o tipo 'preco' com callback
    foreach ($inputs as $input) {
        if (isset($input['tipo']) && $input['tipo'] === 'preco') {
            if (isset($input['infos']['callback']['url'])) {
                return $input['infos']['callback']['url'];
            }
        }
        
        // Verifica se o input possui filhos e faz a chamada recursiva
        if (isset($input['filhos']) && is_array($input['filhos'])) {
            $resultadoFilhos = $this->verificarPrecoCallback($input['filhos']);
            if ($resultadoFilhos !== false) {
                return $resultadoFilhos;
            }
        }
    }
    return false;
}

    function geraUlr($cb) {
        
        $setup = SETUP["dominio"];
        if (strpos($cb, 'http') === 0) {
            return $cb; 
        }
        
        $dominio = rtrim($setup , '/');
        $cb = ltrim($cb, '/');
        return $dominio . '/' . $cb;
    }
    
    function enviarPostCurl($url, $dados) {
    // Inicializa o cURL
    $ch = curl_init($url);

    // Configurações do cURL para uma requisição POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dados); // Dados enviados no POST


    $resposta = curl_exec($ch);

    // Verifica erros
    if (curl_errno($ch)) {
       
        return false;
    }


    curl_close($ch);


    return $resposta;
}

    function processador($id){
      
        $seleciona = "SELECT * FROM pay_pagamentos WHERE pagamento_estrangeira='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return;
        }
        $dado = $resultado->fetch_assoc();
        $pedido = $dado["pagamento_pedido"];
        $estado = $dado["pagamento_estado"];
        $idcoluna = $dado["pagamento_id"];
        
        if($estado != "2"){
            $acao = "UPDATE pay_pagamentos SET pagamento_estado='2' WHERE pagamento_id='$idcoluna'";
            $this->conn->query($acao);
        }
        

        $seleciona = "SELECT * FROM  pay_pedidos WHERE  pedido_id='$pedido'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return;
        }
        
        $dado = $resultado->fetch_assoc();
     
        $saldo = $dado["pedido_total"];
        $usuario = (int)$dado["pedido_usuario"];
        
        if($dado["pedido_estado"] != 2){
            $acao = "UPDATE pay_pedidos SET pedido_estado='2' WHERE pedido_id='$pedido'";
            $this->conn->query($acao);
    
            switch(intval($dado["pedido_tipo"])){
                case 0:
                      $itens = $this->pegaPedido($pedido);
        
        
                foreach($itens as $produto){
                    
                    
                    $comprados = intval($produto["quantidade"] ?? 1);
                    
                    if($produto["recorrente"]){
                        $ciclo = new Ciclos($produto["id"], $usuario);
                        $ciclo->assina();
                    }
                
                    if($produto["estoque"]){
                         $quantidade = $produto["metas"]["estoque"] ?? 0;
                         $calc = $quantidade - $comprados;
                         
                         
                    if($calc < 1){
                        $calc = 0;
                    }
                    
                    $id = $produto["id"];
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
                     
                     
                     $acao = "INSERT INTO pay_pontuacao 
                     (pontuado_usuario,pontuado_pontos,pontuado_expira,pontuado_tipo,pontuado_expiracao) VALUES 
                     ('$usuario', '$calc', '$expira', '$tipo', '$data')";
                     $this->conn->query($acao);

                }
                
                    if($produto["refbd"]){
                        $ref = $produto["refbd"];
                        
                        if($ref){
                           $trato = explode("/",  $ref);
                           if(count($trato) == 2){
                               $modulo = $trato[0];
                               $ref = $trato[1];
                               if(file_exists("../../../".$modulo."/admins/configs/".$ref.".json")){
                                   $conteudo = json_decode(file_get_contents("../../../".$modulo."/admins/formularios/".$ref.".json"), true);
                                   $cb = $this->verificarPrecoCallback($conteudo["inputs"]);
                                 
                                   if($cb && trim($cb)){
                                       
                                       $url =  $this->geraUlr(trim($cb));
                                       
                                       if($url){
                                            $dados = [
                                                "acao"=>"pedidoSucesso",
                                                "usuarios"=>$usuario,
                                                "pedido"=>$pedido,
                                                "vendavel"=>$produto["id"],
                                                "referencia"=>$produto["referencia"]
                                           ];
                                           
                                        $resposta = $this->enviarPostCurl($url , $dados);  
                                       
                                       
                                       }
                                       
            
                                       
                                       
                                   }
                                   
                                   
                               }
                               
                            
                           }
                        }
                        
                        
                        
                    }
                
                    
                
                
                }
                
                
                    
                    break;
                case 1:
                    $usuario = $usuario;
                    $saldo = number_format((float)$saldo, 2, '.', '');
                    $hash = $this->hasher();
                    $seleciona = "SELECT * FROM pay_saldos WHERE pay_saldo_usuario = '$usuario'";
                    $resultado = $this->conn->query($seleciona);
                     if ($resultado->num_rows == 0) {
                        $acao = "INSERT INTO pay_saldos (pay_saldo_usuario, pay_saldo_saldo, pay_saldo_hash) VALUES ('$usuario', '$saldo', '$hash')";
                     } else {
                         $dado = $resultado->fetch_assoc();
                         $id = (int)$dado["pay_saldo_id"];
                         $acao = "UPDATE pay_saldos SET pay_saldo_saldo = pay_saldo_saldo + $saldo WHERE pay_saldo_id = '$id'";
                     }
                     $this->conn->query($acao);

                    break;
                case 2:
                    break;
            }
            
        }
  
    }
    
    function calcularDataPassada($dias) {
        $data = new DateTime();
        $data->modify("+$dias days");
        return $data->format('Y-m-d');
    }
    
    function hasher(){
        return bin2hex(random_bytes(16));
    }
    
}

?>