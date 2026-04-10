<?
header('Content-Type: application/json; charset=utf-8');



session_start();


include __DIR__."/../../../../../admin/config.php";
include __DIR__."/../../../../../admin/configmodulo.php";
include __DIR__."/../aprovacao-pedido.php";

configModulo("pagamento");

function pegaEndereco($cep) {
    // Remove qualquer caractere que não seja número
    $cep = preg_replace('/[^0-9]/', '', $cep);


    // Verifica se o CEP tem 8 dígitos
    if (strlen($cep) !== 8) {
        return "CEP inválido. Deve conter exatamente 8 números.";
    }

    $url = "https://viacep.com.br/ws/$cep/json/";

    // Inicializa o cURL
    $ch = curl_init();

    // Define as opções do cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora a verificação do SSL

    // Executa o cURL e armazena a resposta
    $response = curl_exec($ch);

    // Fecha a conexão cURL
    curl_close($ch);

    // Converte o JSON da resposta em um array associativo
    $endereco = json_decode($response, true);

    // Verifica se houve algum erro na resposta
    if (isset($endereco['erro']) && $endereco['erro'] == true) {
        return "CEP não encontrado.";
    }

    return $endereco;
}

function paraCents($valorString) {
    $valorString = str_replace(',', '.', $valorString);
    $centavos = floatval($valorString) * 100;
    return $centavos;
}

function filtrarNumeros($string) {
        if(!$string){
            return "";
        }
        return preg_replace("/[^0-9]/", "", $string);
    }

function calculadora($valor, $quantidade, $total){
        $previa = $valor * $quantidade;
        $total += $previa;
        return $total;
    }

class Pagamento{
    public $pedido;
    public $usuario;
    public $id;
    public $conn;
    public $processador;
    public $dados;
    function __construct($pedido, $usuario, $id){
        $this->pedido = $pedido;

        $this->usuario = $usuario;
        $this->id = $id;
        $this->conn = conn();

    }

    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function pix(){
        
        
        $pagina = "pagamentos";
        if(isset($_POST["assinatura"]) && $_POST["assinatura"] == 1){
            $pagina = "recorrencia";
        }
 
        
        
        if(!verModulo($pagina , "pix", false)){
            return ["erro"=>true, "mensagem"=>"Pix Não Configurado"];
        }
        
        $processador = verModulo($pagina , "processadorpix", false);
        if(!$processador){
            return ["erro"=>true, "mensagem"=>"Processador de Pix não configurado"];
        }
        $this->processador = $processador;
        
        if(!file_exists(__DIR__."/".$processador.".php")){
            return ["erro"=>true, "mensagem"=>"Arquivo de Processamento via Pix não configurado"];
        }
        
        include __DIR__."/".$processador.".php";
        
        
        
        $ativo = $this->info("pix");
    
 
        if(isset($ativo["sucesso"])){
             return ["sucesso"=>true, "mensagem"=>"Pix feito com Sucesso"];
        }
        
        
        $pagamento = new Processador($this->pedido, $this->usuario);
        $credenciais = $pagamento->load();

        
        if(!$credenciais){
             return ["erro"=>true, "mensagem"=>"Credenciais de Pix não configurada"];
        }
        
        $pagamento->definePedido($this->id);
        $pagamento->pedidoPix();
        $resposta = $pagamento->cobrar();
     

        if(isset($resposta["erro"]) && $resposta["erro"]){
            return ["erro"=>true, "mensagem"=>$resposta["mensagem"]];
        }
  
     
        if($resposta){
            return $this->salva("pix", json_encode($resposta) , $resposta["id"]);
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível gerar o pagamento"];
        }
        
       
    }
    
    function salva($tipo, $dados , $id){
      $pedido = $this->pedido["id"];
      $total = $this->pedido["total"];
      $usuario = $this->usuario["id"];
      $processador = $tipo == "admin" ? "sistema" : $this->processador;
      $estado = 1;
      if($tipo == "admin" || $tipo == "carteira"){
          $estado = 2;
      }

      $hash = $this->hasher();
      $cadastra = "INSERT INTO pay_pagamentos 
      (pagamento_pedido , pagamento_tipo , pagamento_estado , pagamento_dados , pagamento_estrangeira, pagamento_usuario, pagamento_getways, pagamento_valor, pagamento_hash) VALUES 
      ('$pedido', '$tipo', '$estado', '$dados' , '$id', '$usuario', '$processador', '$total', '$hash')";

      if($this->conn->query($cadastra) == true){
          return ["sucesso"=>true, "mensagem"=>"Pix feito com Sucesso"];
      }else{
          return ["erro"=>true, "mensagem"=>"Erro ao gravar Pix"];
      }
    }
    
    function credito($cartao){
        
          $pagina = "pagamentos";
        if(isset($_POST["assinatura"]) && $_POST["assinatura"] == 1){
            $pagina = "recorrencia";
        }
 
        
        
        if(!verModulo($pagina, "cartao", false)){
            return ["erro"=>true, "mensagem"=>"Cartão Não Configurado"];
        }
        
        $processador = verModulo($pagina, "processadorcartao", false);
        if(!$processador){
            return ["erro"=>true, "mensagem"=>"Processador de Cartão não configurado"];
        }
        
        
        include __DIR__."/".$processador.".php";
        
        $pagamento = new Processador($this->pedido, $this->usuario);
        $credenciais = $pagamento->load();
        
        $pagamento->setEndereco(pegaEndereco($cartao["cep"] ?? false));
        $pagamento->definePedido($this->id);
        $pagamento->pedidoCredito($cartao);
        $resposta = $pagamento->cobrar();
        
        

         
         

         if($resposta && $resposta["pago"] == "TRUE"){
             $this->salva("cartao", json_encode($resposta) , $resposta["id"]);
             
             $pedido = new Pedido($this->pedido);
             $pedido->processar();
             
             return ["sucesso"=>true, "mensagem"=>"Item Pago"];
         }else{
             return ["erro"=>true, "mensagem"=>"Não foi possível concluir o pagamento"];
         }

                
        
        
    }
    
    function boleto(){
         if(!verModulo("pagamentos", "boleto", false)){
            return ["erro"=>true, "mensagem"=>"Boleto Não Configurado"];
        }
        
        $processador = verModulo("pagamentos", "processadorboleto", false);
        if(!$processador){
            return ["erro"=>true, "mensagem"=>"Processador de Boleto não configurado"];
        }
        
        if(!file_exists(__DIR__."/".$processador.".php")){
            return ["erro"=>true, "mensagem"=>"Arquivo de Processamento via Boleto não configurado"];
        }
        
        include __DIR__."/".$processador.".php";
        
        $pagamento = new Processador($this->pedido, $this->usuario);
        $credenciais = $pagamento->load();

        
        if(!$credenciais){
             return ["erro"=>true, "mensagem"=>"Credenciais de Boleto não configurada"];
        }
        
        $pagamento->definePedido($this->id);
        $pagamento->pedidoBoleto();
        $resposta = $pagamento->cobrar();
        
    
        $id = $resposta["id"];
            /*
        $cobranca = $resposta["charges"][0];
        $codigo = $cobranca["payment_method"]["boleto"]["formatted_barcode"];
        $link = $cobranca["links"][0]["href"];
        */
        $dados = json_encode(
            [
                "id"=> $resposta["id"],
                "link"=>$resposta["link"],
                "codigo"=>$resposta["codigo"],
                "qr"=>$resposta["qrcode"],
                "vencimento"=>$resposta["vencimento"]
            ]
            
            );
        
        return $this->salva("boleto", $dados, $id);
        
 
    
    }
    
    function cripto(){
        
    }
    
    function info($meio = false){
        if(!$meio){
            $meio = $_POST["auxiliar"] ?? false;
        }
        

        $meiosValidos = ['pix', 'boleto', 'cripto'];
        if(!$meio || !in_array($meio, $meiosValidos)){
            return ["erro"=>true, "mensagem"=>"Os dados enviados não são válidos"];
        }
        
        
        $id = $this->pedido["id"];
        
        $seleciona = "SELECT * FROM pay_pagamentos WHERE pagamento_pedido='$id' AND pagamento_status='1' AND pagamento_tipo='$meio'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
              return ["erro"=>true, "mensagem"=>"Não foi encontrado pagamento válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        
        
        
        return ["sucesso"=>true, "pagamento"=>json_decode($dado["pagamento_dados"], true), "pedido"=>$this->pedido];
    }
    
    function admin(){

        if(!isset($_SESSION["funcao"])){
            return ["erro"=>true, "mensagem"=>"Usuário precisa estar logado"];
        }
        $funcao = intval($_SESSION["funcao"]);
        if($funcao > 1){
             return ["erro"=>true, "mensagem"=>"Usuário não é administrador"];
        }
        
        $hash = $this->hasher(12);
        $this->salva("admin", json_encode(["administador"=>$_SESSION["id"]]) , "nown-".$hash);
        
        
        new AprovacaoPedido("nown-".$hash);
        return ["sucesso"=>true, "mensagme"=>"Pedido feito com sucesso"];

    }
    
    function saldo(){
        $user = $this->pedido["usuario"];
        $total = $this->pedido["total"];
        $seleciona = "SELECT * FROM pay_saldos WHERE pay_saldo_usuario='$user'";
        $saldo = 0;
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $info = $resultado->fetch_assoc();
            $saldo = $info["pay_saldo_saldo"];
        }
        
         $total = floatval($total);
         $saldo = floatval($saldo);
         
         if($saldo > $total){
             
             
              $debit = $saldo - $total;
              $updateSaldo = "UPDATE pay_saldos SET pay_saldo_saldo = '$debit' WHERE pay_saldo_usuario='$user'";
              $this->conn->query($updateSaldo);
             
             
             $hash = $this->hasher(12);
             $this->salva("carteira", json_encode(["carteira"=>$total]) , "carteira-".$hash);
             
              new AprovacaoPedido("carteira-".$hash);
              return ["sucesso"=>true, "mensagme"=>"Pedido feito com sucesso"];
        
         }else{
             return ["erro"=>true, "mensagem"=>"Não há saldo suficiente em carteira"];
         }
        

        
        
    }
    
    function gratis(){

    $total = round((float)$this->pedido["total"], 2);
    

    if ($total === 0.00) {
        $id = $this->pedido["id"];
        
        $hash = $this->hasher(12);
        $this->salva("gratis", json_encode(["gratis"=>$_SESSION["id"]]) , "gratis-".$hash);
        new AprovacaoPedido("gratis-".$hash);
        return ["sucesso"=>true, "mensagme"=>"Pedido feito com sucesso"];
        
  
    }
    
    return ["erro"=>true, "mensagem"=>"O item não é grátis"];

    }
    
    function referencia(){
        $pagina = "pagamentos";
        if(isset($_POST["assinatura"]) && $_POST["assinatura"] == 1){
            $pagina = "recorrencia";
        }
        
        
        include __DIR__."/mercadopago.php";
        
        $pagamento = new Processador($this->pedido, $this->usuario);
        $credenciais = $pagamento->load();

    
        
        $pagamento->definePedido($this->id);
        
        return $pagamento->preferencia();
        
        
    }
}


class Transacao{
    public $pedido;
    public $conn;
    public $usuario;
    public $meio;
    public $pedidoId;
    public $encrypted;
    public $titular;
    public $documento;
    public $dados;
    function __construct(){
        $this->pedidoId = $_POST["pedido"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
        $this->meio = $_POST["meio"] ?? false;
        $this->conn = conn();
        $this->encrypted = $_POST["encrypted"] ?? false;
        $this->titular = $_POST["titular"] ?? false;
        $this->documento = $_POST["documento"] ?? false;
            $this->dados = $_POST["dados"] ?? false;
        if($this->dados){
            $this->dados = json_decode($this->dados, true);
        }
    }
    
    function pegaPrefixo($tableName){

        $query = "SHOW COLUMNS FROM $tableName";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $columns = [];
            while ($row = $result->fetch_assoc()) {
                $columns[] = $row['Field'];
            }

            // Pega o prefixo do primeiro nome de coluna
            $firstColumn = $columns[0];
            $prefix = '';

            // Encontra o ponto de divergência nos nomes das colunas
            for ($i = 0; $i < strlen($firstColumn); $i++) {
                $char = $firstColumn[$i];
                foreach ($columns as $column) {
                    if ($column[$i] !== $char) {
                        break 2; // Sai dos dois loops
                    }
                }
                $prefix .= $char;
            }

            return $prefix;
        } else {
            return null; // Retorna nulo se não houver colunas ou a tabela não existir
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

        $prefixo = $this->pegaPrefixo($produto["banco"]);

        
        $banco = $produto["banco"];
        $referencia = $produto["referencia"];
        $inde = $prefixo."id";
    
        // Verifica se a tabela existe
        $result = $this->conn->query("SHOW TABLES LIKE '$banco'");
        if ($result->num_rows == 0) {
            return false; // A tabela não existe
        }
    

        $colunas = $this->disposicao($banco);
    
    $query = [];
    if(isset($colunas[$prefixo."nome"])){
        array_push($query, $prefixo."nome");
        $nome = $prefixo."nome";
    }else{
        $nome = $prefixo."titulo";
        array_push($query, $prefixo."titulo");
    }
    
     if(isset($colunas[$prefixo."imagem"])){
         $imagem = true;
         array_push($query, $prefixo."imagem");
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
            "foto"=>$imagem ? $dado[$prefixo."imagem"] : false
            ];

    } else {
        return false; 
    }
}
    
    function pegaItens($id){
        $lista = [];
        if(!$id){
            return $lista;
        }   
        

        $seleciona = "SELECT * FROM pay_pedidos_itens WHERE ppi_pedido='$id'";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                
                
                $item = json_decode($dado["ppi_espelho"], true);
                $item["infos"] = $this->pegaInfos($item);
                $item["quantidade"]= $dado["ppi_quantidade"]; 
                $item = ["id"=>$dado["ppi_id"], "infos"=>$item];
                array_push($lista, $item);
            }
        }
        
        return $lista;
        
        
    }
    
    function pegaPedido(){
        $pedido = $this->pedidoId;
        $seleciona = "SELECT * FROM pay_pedidos WHERE pedido_url='$pedido'";

        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        
        $dado = $resultado->fetch_assoc();
        return [
            "id"=>$dado["pedido_id"],
            "usuario"=>$dado["pedido_usuario"],
            "itens"=>$this->pegaItens($dado["pedido_id"]),
            "estado"=>$dado["pedido_estado"],
            "total"=>$dado["pedido_total"],
            "hash"=>$dado["pedido_hash"],
            "autor"=>$dado["pedido_autor"],
            "url"=>$dado["pedido_url"],
            "data"=>$dado["pedido_data"],
            "update"=>$dado["pedido_update"],
            "carrinho"=>$dado["pedido_carrinho"],
            "tipo"=>$dado["pedido_tipo"]
            ];
    }
    
    function pegaUsuario(){
        $usuario = $this->usuario;
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$usuario'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        

        $dado = $resultado->fetch_assoc();
        
        return [
            "id"=>$dado["usuario_id"],
            "display"=>$dado["usuario_display"],
            "email"=>$dado["usuario_email"],
            "telefone"=>$dado["usuario_telefone"],
            "cpf"=>$dado["usuario_cpf"],
            "usuario"=>$dado["usuario_user"],
            ];
    }
    
    function verificaPagamento(){
        $pedido = $this->pegaPedido();

        
        
        switch(intval($pedido["estado"])){
            case 0:
                return ["sucesso"=>true, "update"=>true, "status"=>$pedido["estado"]]; 
                break;
            case 1:
                return ["sucesso"=>true, "update"=>false, "status"=>$pedido["estado"]]; 
                break;
            default:
                return ["sucesso"=>true, "update"=>true, "status"=>$pedido["estado"]];
                break;
        }
   
        
        return ["erro"=>true, "mensagem"=>"Estado não consolidado"];

    }
    
    function render() {
    if (!$this->pedidoId) {
        return ["erro" => true, "mensagem" => "Não foi enviado um pedido válido"];
    }

    $pedido = $this->pegaPedido();
    if (!$pedido) {
        return ["erro" => true, "mensagem" => "Pedido não existe"];
    }

    if (!$this->usuario || $pedido["usuario"] != $this->usuario) {
        return ["erro" => true, "mensagem" => !$this->usuario ? "A ação só pode ser feita por usuários logados" : "Esse pedido não pertence a esse usuário"];
    }

    $usuario = $this->pegaUsuario();
    if (!$usuario) {
        return ["erro" => true, "mensagem" => "O usuário não existe"];
    }

    
    if ($pedido["estado"] != 1 && $this->meio != "verificaPix") {
        return ["erro" => true, "mensagem" => "O pedido não pode ser concluído"];
    }

    if (!$this->meio) {
        return ["erro" => true, "mensagem" => "Não foi definido um meio de pagamento válido"];
    }

    $meiosValidos = ['pix', 'cartao', 'boleto', 'cripto', 'info', 'administrador', 'verificaPix', 'carteira', 'preferenciaMercadoPago', "gratis"];
    if (!in_array($this->meio, $meiosValidos)) {
        return ["erro" => true, "mensagem" => "O meio de pagamento definido é inválido"];
    }
    

    $pagamento = new Pagamento($pedido, $usuario, $this->pedidoId);
    switch($this->meio){
        case 'pix':
            return $pagamento->pix();
            break;
        case 'cartao':
            if(!$this->dados){
                return ["erro"=>true, "mensagem"=>"Alguns dados não foram enviados"];
            }
            return $pagamento->credito($this->dados);
            break;
        case 'boleto':
            return $pagamento->boleto();
            break;
        case 'cripto':
            return $pagamento->cripto();
            break;
        case 'info':
            return $pagamento->info();
            break;
        case 'carteira':
            return $pagamento->saldo();
            break;
        case 'administrador':
            return $pagamento->admin();
            break;
        case 'verificaPix':
            return $this->verificaPagamento();
            break;
        case 'preferenciaMercadoPago':
            return $pagamento->referencia();
            break;
        case 'gratis':
            return $pagamento->gratis();
            break;
    }

    return ["sucesso" => true];
}

}

$transacao = new Transacao();
$resposta = $transacao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>