<?
header('Content-Type: application/json; charset=utf-8');

session_start();
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
    
    function getIdItem(){
        $identificador = $_POST["identificador"] ?? false;
        $modulo = $_POST["modulo"] ?? false;
        $hash = $_POST["editMode"] ?? false;
        if(!$hash || !$identificador || !$modulo){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessários"];
        }
        
       
        
        if(!file_exists(__DIR__."/../../".$modulo."/admins/configs/".$identificador.".json")){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado a estrutura enviada"];
        }
        
        $conteudo = json_decode(file_get_contents(__DIR__."/../../".$modulo."/admins/configs/".$identificador.".json"), true);
        
        $banco = $conteudo["banco"];
        $prefixo = $conteudo["prefixo"];
        $url = $prefixo."_hash";
        $ide = $prefixo."_id";
        $seleciona = "SELECT $ide FROM $banco WHERE $url='$hash'";

        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontradado o item a ser associado"];
        }
        
        $dado = $resultado->fetch_assoc();
        $idItem = $dado[$ide];
        return ["sucesso"=>true, "id"=>$idItem];
    }
    
    function associar(){
        $endereco = $_POST["endereco"] ?? false;
        if(!$endereco){
             return ["erro"=>true, "mensagem"=>"Não foi enviado um endereço válido"];
        }
        
        
        $seleciona = "SELECT * FROM enderecos WHERE endereco_hash='$endereco'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um endereço válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        $id = $dado["endereco_id"];
        
        
        $pegaId = $this->getIdItem();
        if(isset($pegaId["erro"])){
            return $pegaId;
        }
        
        $idItem = $pegaId["id"];
        $modulo = $_POST["modulo"] ?? false;

        $user = $this->user;
        $seleciona = "SELECT * FROM enderecos_associacoes WHERE endereco_associacao_endereco='$id' AND endereco_associacao_banco='$modulo' AND endereco_associacao_identificador='$idItem'";
        $teste = $this->conn->query($seleciona);
        
        if($teste->num_rows == 0 ){
            $cadastra = "INSERT INTO enderecos_associacoes (endereco_associacao_endereco,endereco_associacao_banco,endereco_associacao_identificador,endereco_associacao_autor) VALUES ('$id', '$modulo', '$idItem', '$user')";
            $this->conn->query($cadastra);
            $id = $this->conn->insert_id;
            return ["sucesso"=>true, "mensagem"=>"Endeço Associado com Sucesso", "id"=>$id];  
        }else{
           return ["sucesso"=>true, "mensagem"=>"Endeço já associado"];  
        }
    }
    
    function metas($id){
        $seleciona = "SELECT * FROM enderecos_meta WHERE em_endereco='$id'";
        $resultado = $this->conn->query($seleciona);
        $itens = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $itens[$dado["em_chave"]] = $dado["em_valor"];
            }
        }
        return $itens;
    }
    
    function parse($cidades, $estados, $paises){
        $volta = [
            "cidades"=>[],
            "estados"=>[],
            "paises"=>[]
            ];
            
            
            if(!empty($cidades)){
                $lista = implode(",", $cidades);
                $seleciona = "SELECT endereco_cidade_nome as nome, endereco_cidade_id as id FROM enderecos_cidades WHERE endereco_cidade_id IN ({$lista})";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $volta["cidades"][$dado["id"]] = $dado["nome"];
                    }
                }
            }
            
             if(!empty($estados)){
                $lista = implode(",", $estados);
                $seleciona = "SELECT endereco_estado_nome as nome, 	endereco_estado_id  as id FROM enderecos_estados WHERE 	endereco_estado_id  IN ({$lista})";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $volta["estados"][$dado["id"]] = $dado["nome"];
                    }
                }
            }
            
              if(!empty($paises)){
                $lista = implode(",", $paises);
                $seleciona = "SELECT enderecos_pais_nome as nome, 	enderecos_pais_id  as id FROM enderecos_paises WHERE 	enderecos_pais_id  IN ({$lista})";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $volta["estados"][$dado["id"]] = $dado["nome"];
                    }
                }
            }
        
        return $volta;
    }
    
    function listar(){
        $pegaId = $this->getIdItem();
        if(isset($pegaId["erro"])){
            return $pegaId;
        }
        
        $idItem = $pegaId["id"];
        $modulo = $_POST["modulo"] ?? false;
        
        $enderecos = [];
        $seleciona = "SELECT endereco_associacao_endereco FROM enderecos_associacoes WHERE endereco_associacao_banco='$modulo' AND endereco_associacao_identificador='$idItem'";
        $resultado = $this->conn->query( $seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($enderecos, $dado["endereco_associacao_endereco"]);
            }
        }
        
        $adress = [];
        $cidades = [];
        $paises = [];
        $estados = [];
        if(!empty($enderecos)){
            $ids = implode(",", $enderecos);
            $seleciona = "SELECT * FROM enderecos WHERE endereco_id IN ($ids)";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows > 0){
                while($dado = $resultado->fetch_assoc()){
                    $item = [
                        "id"=>$dado["endereco_id"],
                        "nome"=>$dado["endereco_nome"],
                        "cep"=>$dado["endereco_cep"],
                        "cidade"=>$dado["endereco_cidade"],
                        "estado"=>$dado["endereco_estado"],
                        "pais"=>$dado["endereco_pais"],
                        "rua"=>$dado["endereco_rua"],
                        "bairro"=>$dado["endereco_bairro"],
                        "hash"=>$dado["endereco_hash"]
                        ];
                        
                        $cidades[] = $dado["endereco_cidade"];
                        $paises[] = $dado["endereco_pais"];
                        $estados[] = $dado["endereco_estado"];
                        
                        
    
                        array_push($adress, $item);
                }
            }
        }
        
        
        if(!empty($cidades) || !empty($estados) || !empty($paises)){
            $volta = $this->parse($cidades, $estados , $paises);
            
       
            foreach($adress as $chave=>$item){
                $item["cidade"] = $volta["cidades"][$item["cidade"]] ?? "";
                $item["estado"] = $volta["estados"][$item["estado"]] ?? "";
                $item["pais"] = $volta["paises"][$item["pais"]] ?? "";
                $adress[$chave] = $item;
            }
        }
        
        return ["sucesso"=>true, "enderecos"=>$adress];
        
        
    }
    
    function apagar() {
    $hash = $_POST["hash"] ?? false;
    if (!$hash) { 
        return ["erro" => true, "mensagem" => "Não foi enviado um hash válido"];
    }
     
    // Escapar hash para evitar injeção
    $hash = $this->conn->real_escape_string($hash);
    
    // Selecionar o endereço com base no hash
    $seleciona = "SELECT endereco_id AS id FROM enderecos WHERE endereco_hash = '{$hash}'";
    $resultado = $this->conn->query($seleciona);
    
    if ($resultado->num_rows == 0) {
        return ["erro" => true, "mensagem" => "Não foi encontrado um endereço com o hash enviado"];
    }
    
    $dado = $resultado->fetch_assoc();
    $id = $dado["id"];
    
    // Iniciar transação
    $this->conn->begin_transaction();
    
    try {
        // Excluir as associações primeiro
        $deletaAssociacoes = "DELETE FROM enderecos_associacoes WHERE endereco_associacao_endereco = '{$id}'"; 
        $this->conn->query($deletaAssociacoes);
        
        // Excluir o endereço
        $deleta = "DELETE FROM enderecos WHERE endereco_id = '{$id}'";
        if (!$this->conn->query($deleta)) {
            throw new Exception("Erro ao deletar o endereço principal.");
        }
        
        // Confirmar transação
        $this->conn->commit();
        return ["sucesso" => true, "mensagem" => "Endereço deletado com sucesso", "id"=>$id];
    } catch (Exception $e) {
        // Reverter transação em caso de erro
        $this->conn->rollback();
        return ["erro" => true, "mensagem" => $e->getMessage()];
    }
}

    function uni(){
        $id = intval($_POST["id"] ?? 0);
        
        $cep = preg_replace('/\D/', '', $_POST['cep']);
        
        $response = json_decode(file_get_contents("https://viacep.com.br/ws/{$cep}/json/"), true);
        if(!empty($response["erro"])){
            return ["erro"=>true, "mensagem"=>"O CEP digitado não é válido"];
        }
        
        
        include __DIR__."/endereco.php";
        
        $endereco = new Endereco($cep);
        $resposta = $endereco->api($id);
        $id = $endereco->idcadastrado;
        
        
      
        
        return ["sucesso"=>true, "id"=>$id, "lat"=>$endereco->lat ?? false, "long"=>$endereco->long ?? false];
    }
    
    function oneADress(){
        $id = intval($_POST["id"] ?? 0);
        if(!$id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um ID válido"];
        }
        $seleciona = "SELECT * FROM enderecos WHERE endereco_id='{$id}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado nenhum endereço com o ID informado"];
        }
    
        
        $dado = $resultado->fetch_assoc();
        
        
        $cidade = false;
        $idCidade = $dado["endereco_cidade"];
        $seleciona = "SELECT * FROM enderecos_cidades WHERE endereco_cidade_id='$idCidade' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $info = $resultado->fetch_assoc();
            $cidade = $info["endereco_cidade_nome"];
        }
        
        $pais = false;
        $idPais = $dado["endereco_pais"];
        $seleciona = "SELECT * FROM  enderecos_paises WHERE enderecos_pais_id='$idPais' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $info = $resultado->fetch_assoc();
            $pais = $info["enderecos_pais_nome"];
        }
        
        $estado = false;
        $idEstado = $dado["endereco_estado"];
        $seleciona = "SELECT * FROM  enderecos_estados WHERE endereco_estado_id='$idEstado' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $info = $resultado->fetch_assoc();
            $estado = $info["endereco_estado_nome"];
        }
        
        
        
        
        $endereco = [
            "nome"=>$dado["endereco_nome"],
            "cep"=>$dado["endereco_cep"],
            "observacao"=>$dado["endereco_observacao"],
            "numero"=>$dado["endereco_numero"],
            "complemento"=>$dado["endereco_complemento"],
            "url"=>$dado["endereco_url"],
            "referencia"=>$dado["endereco_referencia"],
            "latitude"=>$dado["endereco_latitude"],
            "longitude"=>$dado["endereco_longitude"],
            "pais"=>$pais,
            "estado"=>$estado,
            "cidade"=>$cidade,
            "rua"=>$dado["endereco_rua"],
            "bairro"=>$dado["endereco_bairro"],
            "uf"=>$dado["endereco_uf"],
            "descricao"=>$dado["endereco_descricao"]
            ]; 
        return ["sucesso"=>true, "endereco"=>$endereco];
    }

    function render(){
        
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        
        switch($this->acao){
            case 'listar':
                return $this->listar();
                break;
            case 'associar':
                return $this->associar();
                break;
            case 'apagar':
                return $this->apagar();
                break;
            case 'uni':
                return $this->uni();
                break;
            case 'one':
                return $this->oneADress();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
    
    function close(){
        $this->conn->close();
    }
}


$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>