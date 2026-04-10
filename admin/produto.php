<?
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conn.php';
session_start();

class Acao{
    public $acao;
    public $conn;
    public $usuario;
    public $quantidade;
    public $id;
    public $produto;

    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->id = $_POST["produto"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
        $this->quantidade = $_POST["quantidade"] ?? 0;
    }
    
    function metas(){
        $id = $this->id;
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
            "metas"=>$this->metas()
            ];
            

            return ["sucesso"=>true, "produto"=>$produto];

    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function fechaPedido(){
        $fecha = $_POST["fecha"] ?? "false";
        if($fecha == "false"){
            return false;
        }
        

        $usuario = $this->usuario;
        $copia = "SELECT * FROM carrinhos WHERE carrinho_usuario='$usuario'";
        $resultado = $this->conn->query($copia);
        if($resultado->num_rows == 0){
            return false;
        }
        
        $dado = $resultado->fetch_assoc();
        $carrinho = json_decode($dado["carrinho_carrinho"], true);
        
        $pedido = [];
        $calculo = 0;
        foreach($carrinho as $id=>$quantidade){
            $produto = $this->pegaProduto($id)["produto"];
            $produto["comprado"] = $quantidade;
            
            
            $preco = $produto["preco"];
            $calc = $preco * $quantidade;
            $calculo = $calculo + $calc;
           
            array_push($pedido, $produto);
        }
        
        $hash = $this->hasher();
        $url = $this->hasher();    
        
        $calculo = number_format(floatval($calculo),2);
        $itens = json_encode($pedido);
        $cadastra = "INSERT INTO pedidos (pedido_usuario,pedido_itens,pedido_estado,pedido_hash,pedido_autor,pedido_url, pedido_total) VALUES ('$usuario', '$itens', '1', '$hash', '$usuario', '$url', '$calculo')";
        if($this->conn->query($cadastra) == true){
            return $url;
        }else{
            return false;
        }
    }
    
    function addCard(){
        if(isset($this->produto["sucesso"])){
             if($this->quantidade > 0){
                    $quantidade = $this->quantidade;
                    
                    if($this->produto["produto"]["estoque"]){
                        $limite = $this->produto["produto"]["metas"]["estoque"] ?? 0;
                    }else{
                        $limite = 99999999999999;
                    }
                    
                    if($limite == 0){
                        return ["erro"=>true, "mensagem"=>"Produto sem estoque"];
                    }
                    
                    if($quantidade > $limite){
                        $quantidade = $limite;
                    }
                    
                    $usuario = $this->usuario;
                    $seleciona = "SELECT * FROM  carrinhos WHERE carrinho_usuario='$usuario'";
                    $resultado = $this->conn->query($seleciona);
                    if($resultado->num_rows == 0){
                        $card = [];
                        $card[$this->id] = $quantidade;
                        $new = true;
                    }else{
                        $dado = $resultado->fetch_assoc();
                        $card = [];
                        try{
                            $conteudo = $dado["carrinho_carrinho"];
                            if($conteudo){
                                $trato = json_decode($conteudo , true);
                                $card = $trato;
                            }
         
                        }catch(Exception $e){
                          
                        }
                        
                        $card[$this->id] = $quantidade;
                        $new = false;
                    }
                    
                    $card = json_encode($card);
                    $usuario = $this->usuario;
                    $url = $this->hasher($length = 32);
           
                    if($new){
                        $acao = "INSERT INTO carrinhos (carrinho_usuario,carrinho_carrinho, carrinho_url) VALUES ('$usuario', '$card', '$url')";
                    }else{
                        $acao = "UPDATE carrinhos SET carrinho_carrinho='$card' WHERE carrinho_usuario='$usuario'";
                    }
                    
                    if($this->conn->query($acao) == true){
                         return ["sucesso"=>true, "status"=>1, "pedido"=>$this->fechaPedido()];
                    }else{
                         return ["sucesso"=>true, "status"=>1, "sql"=>$this->conn->error];
                    }
                    
                    
                    
                 
             }else{
                 return ["erro"=>true, "mensagem"=>"A quantidade definida é inválida"];
             }
        }else{
            return $this->produto;
        }
        
    }
    
    function carrinho(){
        $carrinho = [];
        if($this->usuario){
            $usuario = $this->usuario;
            $seleciona = "SELECT * FROM carrinhos WHERE carrinho_usuario='$usuario'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $carrinho = json_decode($dado["carrinho_carrinho"], true);
            }
        }
        
        return $carrinho;
    }
    
    function remover(){
        $carrinho = $this->carrinho();
        $chaveParaRemover = $this->id;
        if (array_key_exists($chaveParaRemover, $carrinho)) {
            unset($carrinho[$chaveParaRemover]);
        } 
        
       $user = $this->usuario;
       if(count($carrinho) == 0){
           $acao = "DELETE FROM carrinhos WHERE carrinho_usuario='$user'";
       }else{
           $card = json_encode($carrinho);
           $acao = "UPDATE carrinhos SET carrinho_carrinho='$card' WHERE carrinho_usuario='$user'";
       }
       
       if($this->conn->query($acao) == true){
            return ["sucesso"=>true, "mensagem"=>"Item removido do carrinho com sucesso", "id"=>$this->id];
       }else{
           return ["erro"=>true, "mensagem"=>"Erro ao remover item do carrinho", "sql"=>$this->conn->error];
       }
        
    }
    
    function disposicao($banco){
        $colunasDisponiveis = [];
        $query = "SHOW COLUMNS FROM ".$banco."";
        $colunas = $this->conn->query($query);
       
       while ($info = $colunas->fetch_assoc()) {
           $colunasDisponiveis[$info["Field"]] = $info;
       }
        
        return $colunasDisponiveis;
    }
    
    function pegaInfos($produto){
    $prefixo = substr($produto["banco"], 0, -1);
    $banco = $produto["banco"];
    $referencia = $produto["referencia"];
    $inde = $prefixo."_id";
    
    // Verifica se a tabela existe
    $result = $this->conn->query("SHOW TABLES LIKE '$banco'");
    if ($result->num_rows == 0) {
        return false; // A tabela não existe
    }
    

    $colunas = $this->disposicao($banco);
    
    $query = [];
    if(isset($colunas[$prefixo."_nome"])){
        array_push($query, $prefixo."_nome");
        $nome = $prefixo."_nome";
    }else{
        $nome = $prefixo."_titulo";
        array_push($query, $prefixo."_titulo");
    }
    
     if(isset($colunas[$prefixo."_imagem"])){
         $imagem = true;
         array_push($query, $prefixo."_imagem");
     }else{
         $imagem = false;
         $imagem = "";
     }
    
    

    // A tabela e a coluna existem, procede com a seleção dos dados
    $query = implode(" , ", $query);
    $seleciona = "SELECT ".$query." FROM $banco WHERE $inde = '$referencia'";
    $resultado = $this->conn->query($seleciona);
    
    if($resultado->num_rows == 1){
        $dado = $resultado->fetch_assoc();
        return [
            "nome"=>$dado[$nome],
            "foto"=>$imagem ? $dado[$prefixo."_imagem"] : false
            ];

    } else {
        return false; 
    }
}

    function carrinhoCompleto(){
        
        
         $sql = "SHOW TABLES LIKE 'carrinhos'";
         $result = $this->conn->query($sql);
         
         if($result->num_rows == 0) {
             return ["sucesso"=>true, "carrinho"=>false];
         } 
        
        $carrinho = $this->carrinho();
        
        
        $lista = [];
        foreach($carrinho as $item=>$quantidade){
            $produto = $this->pegaProduto($item);
            if(isset($produto["sucesso"])){
                $info = $this->pegaInfos($produto["produto"]);
                $item = [
                    "produto"=>$produto["produto"],
                    "infos"=>$info,
                    "quantidade"=>$quantidade
                    ];
                    
                if($info){
                    array_push($lista, $item);
                }
                    
            }
            
            
        }
        return ["sucesso"=>true, "lista"=>$lista, "carrinho"=>true];
    }
    
    function validarData($dataStr) {
    $dataInserida = strtotime($dataStr); // Converte a string em um timestamp

    if ($dataInserida === false) {
        echo "Formato inválido da data.";
        return false;
    }

    $dataAtual = time(); // Obtém o timestamp da data atual
    $diferenca = $dataAtual - $dataInserida; // Calcula a diferença em segundos

    if ($diferenca > 300) { // 300 segundos equivalem a 5 minutos (5 minutos * 60 segundos)
        return true; // Se a diferença for maior que 5 minutos, retorna true
    } else {
        return false; // Caso contrário, retorna false
    }
    }
    
    function pedido(){
        $pedido = $_POST["pedido"] ?? false;
        if(!$pedido){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um pedido válido"];
        }
        
        $seleciona = "SELECT * FROM pedidos WHERE pedido_url='$pedido'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["sucesso"=>true, "pedido"=>false];
        }
        
        $dado = $resultado->fetch_assoc();
        
        
        $data = $dado["pedido_data"];
        $estado = $dado["pedido_estado"];
        
        if($estado == "1" && $this->validarData($data)){
            $estado = 0;
            $id = $dado["pedido_id"];
            $atualiza = "UPDATE pedidos SET pedido_estado='0' WHERE pedido_id='$id'";
            $this->conn->query($atualiza);
        }
        
        
        $produtos = json_decode($dado["pedido_itens"], true);
        $itens = [];
        foreach($produtos as $produto){
            $produto["infos"] = $this->pegaInfos($produto);
            array_push($itens, $produto);
        }
        
        $pedido = [
            "itens"=> $itens,
            "data"=> $data,
            "estado" => $estado,
            "total"=>$dado["pedido_total"]
            ];
        return ["sucesso"=>true, "pedido"=>$pedido];
    }
    
    function render(){
        if(!$this->acao){
            return ["erro"=>true, "mensagem"=>"Nenhuma ação válida foi enviada"];
        }
        
        if(!$this->id){
            return ["erro"=>true, "mensagem"=>"Nenhuma produto válido foi enviado"];
        }
        
        $this->produto = $this->pegaProduto(false);
        
        switch($this->acao){
            case 'info':
                $resposta = $this->produto;
                $resposta["carrinho"] = $this->carrinho();
                return $resposta;
                break;
            case 'addCard':
                if(!$this->usuario){
                    return ["sucesso"=>true, "status"=>0];
                }else{
                    return $this->addCard();
                }
                break;
            case 'remover':
                return $this->remover();
                break;
            case 'carrinho':
                return $this->carrinhoCompleto();
                break;
            case 'fechaPedido':
                return ["sucesso"=>true, "status"=>1, "pedido"=>$this->fechaPedido()];
                break;
            case 'checkout':
                return $this->pedido();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


?>