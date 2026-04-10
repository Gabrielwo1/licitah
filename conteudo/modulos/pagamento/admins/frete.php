<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__."/../../../../admin/conn.php";

class Frete{
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
    }
    
    function meus(){
        $seleciona = "SELECT * FROM enderecos WHERE endereco_autor='{$this->user}' ORDER BY endereco_id DESC";
        $resultado = $this->conn->query($seleciona);
        $enderecos = [];
        $map = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $map[$dado["endereco_id"]] = $dado["endereco_hash"];
                $enderecos[] = [
                  "nome"=>$dado["endereco_nome"],
                  "cep"=>$dado["endereco_cep"],
                  "observacao"=>$dado["endereco_observacao"],
                  "numero"=>$dado["endereco_numero"],
                  "complemento"=>$dado["endereco_complemento"],
                  "hash"=>$dado["endereco_hash"],
                  "referencia"=>$dado["endereco_referencia"],
                  "latitude"=>$dado["endereco_latitude"],
                  "longitude"=>$dado["endereco_longitude"],
                  "pais"=>$dado["endereco_pais"],
                  "estado"=>$dado["endereco_estado"],
                  "cidade"=>$dado["endereco_cidade"],
                  "rua"=>$dado["endereco_rua"],
                  "bairro"=>$dado["endereco_bairro"],
                  "uf"=>$dado["endereco_uf"],
                  "descricao"=>$dado["endereco_descricao"]  
                 ];
            }
        }
        
        $padrao = "SELECT * FROM usuarios WHERE usuario_id='{$this->user}' LIMIT 1";
        $resultado = $this->conn->query($padrao);
        $dado = $resultado->fetch_assoc();
        if($dado["usuario_endereco"]){
            
        }
        
        return ["sucesso"=>true, "lista"=>$enderecos, "escolhido"=>$map[$dado["usuario_endereco"]] ?? false];
        
    }
    
    function calcula(){
        $pedido = $_POST["pedido"] ?? false;
        $endereco = $_POST["endereco"] ?? false;
        
        if(!$pedido || !$endereco){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os campos de cálculo necessarios"];
        }
        
        $seleciona = "SELECT * FROM pay_pedidos WHERE pedido_url='{$pedido}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um pedido válido"];
        }
        
        $pedido = $resultado->fetch_assoc();
        
        $seleciona = "SELECT * FROM enderecos WHERE endereco_hash='{$endereco}'";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um endereço válido"];
        }
        $endereco = $resultado->fetch_assoc();
        
        $idPedido = intval($pedido["pedido_id"]);
        
        $seleciona = "SELECT * FROM pay_pedidos_itens WHERE ppi_pedido='{$idPedido}'";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Esse pedido não tem itens válidos"];
        }
        
        $itens = [];
        $map = [];
        while($dado = $resultado->fetch_assoc()){
            $itens[] =  $dado["ppi_item"];
            $map[$dado["ppi_item"]] = $dado["ppi_quantidade"];
        }
        
   
        
        if(empty($itens)){
            return ["sucesso"=>true, "mensagem"=>"Não foram encontrados itens válidos"];
        }
        
        $lista = implode(",", $itens);
        $seleciona = "SELECT * FROM vendaveis WHERE vendavel_id IN ({$lista})";
        $resultado = $this->conn->query($seleciona);
        $lojas = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $idVendavel = intval($dado["vendavel_id"]);
                $quantidade = $map[$idVendavel];

                $preco = floatval($dado["vendavel_preco"]);
                $tipo = intval($dado["vendavel_tipo"]);
                $estocavel = $dado["vendavel_controleestoque"];
                $loja = $dado["vendavel_loja"];
                
                if(!isset($lojas[$loja])){
                    $lojas[$loja] = [];
                }
                
                $m = [];
                $metas = "SELECT * FROM vendaveis_meta WHERE vm_vendavel='{$idVendavel}'";
            
                $res = $this->conn->query($metas);
                
                if($res->num_rows > 0){
                    
                    while($info = $res->fetch_assoc()){
                        $m[$info["vm_chave"]] = $info["vm_valor"];
                    }
                }
                
                $altura = intval($m["altura"] ?? 0);
                $largura = intval($m["largura"] ?? 0);
                $comprimento = intval($m["comprimento"] ?? 0);
                $peso = intval($m["peso"] ?? 0);
                $estoque = intval($m["estoque"] ?? 0);
                
                $valido = true;
                $fretavel= false;
                if($tipo === 2){
                    $fretavel = true;
                   if($altura === 0 || $largura === 0 || $comprimento === 0 || $peso === 0){
                    $valido = false;
                   }
                }
                
                
                if($estocavel && ($estoque === 0 || $quantidade > $estoque)){
                    $valido = false;
                }
                
                
                
                $lojas[$loja][] = [
                    "id"=>$idVendavel,
                    "valido"=>$valido,
                    "quantidade"=>$quantidade,
                    "preco"=>$preco,
                    "frete"=>$fretavel,
                    "dimensoes"=>[
                        "altura"=>$altura,
                        "largura"=>$largura,
                        "comprimento"=>$comprimento,
                        "peso"=>$peso
                        ],
                    ];
                
                
            }
        }

        
        
        return ["sucesso"=>true, "pedido"=>$lojas];
    }
    
    function close(){
        $this->conn->close();
    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"A ação é permitida somente para usuários logados"];
        }
        switch($this->acao){
            case 'meus':
                return $this->meus();
                break;
            case 'calcula':
                return $this->calcula();
                break;
            case 'novo':
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A açaão definida não é válida"];
                break;
        }
    }

}

$_POST = [
    "acao"=>"calcula",
    "pedido"=>"Opv4bL6bUbze3HSBkHV6wvHk7lgNz1wI",
    "endereco"=>"baed5f4f664a869dca3c581ba42da262"
    ];



$frete = new Frete();
$resposta = $frete->render();
$frete->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>