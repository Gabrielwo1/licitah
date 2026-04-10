<?

function itemComprado($idItem){
    if(!isset($_SESSION["id"])){
        return 0;
    }
    $user = $_SESSION["id"];
    $conn = conn();

    $seleciona = "SELECT pedido_id FROM pay_pedidos WHERE pedido_usuario='$user' AND pedido_estado > 1";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return 0;
    }

    $itens = [];
    while($dado = $resultado->fetch_assoc()){
        array_push($itens, $dado["pedido_id"]);
    }
    

    $itensList = implode(',', $itens);
    
    $restante = "SELECT * FROM pay_pedidos_itens WHERE ppi_pedido IN ($itensList) AND ppi_item='$idItem'";
    $resultado = $conn->query($restante);
    

    return $resultado->num_rows;
}

class Comprados{
    public $conn;
    public $user;
    public $comprados;
    public $feitos;
    public $acao;
    
    function __construct(){
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
    }
    
    function daUrl($tabela, $link){
    $conn = $this->conn;
    $query = "SHOW TABLES LIKE '$tabela'";
    $resultado = $conn->query($query);
    
    if ($resultado->num_rows == 0) {
        return false;
    }
    
    $query = "SHOW COLUMNS FROM $tabela";
    $resultado = $conn->query($query);
    
    $colunas = [];
    
    $url = false;
    $vendavel = false;
    
    while($coluna = $resultado->fetch_assoc()){
        if (strpos($coluna['Field'], '_url') !== false) {
            $url = $coluna['Field'];
        }
        
        if (strpos($coluna['Field'], '_vendavel') !== false) {
            $vendavel = $coluna['Field'];
        }
    }
    
    if (!$url || !$vendavel) {
        return 0;
    }
    
    $seleciona = "SELECT $url, $vendavel FROM $tabela WHERE $url='$link'";
    $resultado = $conn->query($seleciona);
    
    if ($resultado->num_rows == 0) {
        return 0;
    }
    
    $dados = $resultado->fetch_assoc();
    $idProduto = $dados[$vendavel];
    
    if(!$idProduto){
        return 0;
    }
    
    

    return $this->item($idProduto);
    
}

    function item($id = false){
        if(!$this->user  || $id == false){
            return 0;
        }
        
        $user = $this->user;
        $conn = conn();

        $seleciona = "SELECT pedido_id FROM pay_pedidos WHERE pedido_usuario='$user' AND pedido_estado > 1";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
            return 0;
        }

        $itens = [];
        while($dado = $resultado->fetch_assoc()){
            array_push($itens, $dado["pedido_id"]);
        }
    

        $itensList = implode(',', $itens);
    
        $total = 0;
        $restante = "SELECT ppi_quantidade FROM pay_pedidos_itens WHERE ppi_pedido IN ($itensList) AND ppi_item='$id'";
        $resultado = $conn->query($restante);
        while($dado = $resultado->fetch_assoc()){
            $total += $dado["ppi_quantidade"] == 0 ? 1 : $dado["ppi_quantidade"];
        }
    
        return $total;
    }
    
    public function get($chave) {
        return isset($this->$chave) ? $this->$chave : false;
    }
    
    function bancoParaPontos($banco){
        $seleciona = "SELECT * FROM pay_pontos_meta WHERE ppm_chave='banco_dados' AND ppm_valor='$banco'";
        $resultado = $this->conn->query($seleciona);
        $array = [];
        if($resultado->num_rows > 0){
            while($dados = $resultado->fetch_assoc()){
                array_push($array, $dados["ppm_pontos"]);
            }
        }
        
        $feitos = $this->planodepontos($array[0] ?? false);
        
        
        $soma = 0;
        foreach($array as $item){
            $pontos = $this->pontos($item);
            $soma += $pontos["soma"];
        }
        $this->comprados = $soma;
        $this->feitos = $feitos["pontos"];
        
        $soma -= $feitos["pontos"];
        if($soma < 0){
            $soma = 0;
        }
        
        return $soma;
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
        return ["sucesso"=>true, "pontos"=>$resultado->num_rows , "itens"=>$itens];

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
        return ["fluxo"=>$pontos, "soma"=>$soma, "expirados"=>$expirados, "sucesso"=>true];
    }
    
    function pntsDisponiveis($id){
        $pontos = $this->pontos($id);
        return $pontos;
        $feitos = $this->planodepontos($id);
        
        print_r($pontos, $feitos);
        $calc = $póntos - $feitos;
        if($calc < 0){
            $calc = 0;
        }
        return ["sucesso"=>true, "pontos"=>[
                            "disponiveis"=>$calc,
                            "comprados"=>$pontos,
                            "feitos"=>$feitos
                            ]];
        
        
    }
    
    function render(){
        switch($this->acao){
            case 'item':
                return ["sucesso"=>true, "itens"=>$this->item($_POST["id"] ?? false)];
                break;
        }
    }
    
}


if(isset($_POST["api"]) && $_POST["api"] == "pedido"){
 
header('Content-Type: application/json; charset=utf-8');
    include __DIR__."/../../../../admin/conn.php";
    $acao = new Comprados();
    $resposta = $acao->render();
    echo json_encode($resposta);
}

