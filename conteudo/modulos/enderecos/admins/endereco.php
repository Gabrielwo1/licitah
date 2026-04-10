<?

class Endereco{
    public $cep;
    public $id;
    public $conn;
    public $nome;
    public $autor;
    public $idcadastrado;
    public $lat;
    public $long;
    private $numero;
    private $complemento;
    function __construct($cep){
        $this->cep = preg_replace('/\D/', '', $cep);
        $this->autor = $_SESSION["id"] ?? false;
        $this->conn = conn();
        $this->nome = false;
        $this->numero = false;
        $this->complemento = false;
    }
    
    function setNumero($numero){
        $this->numero = $numero;
    }
    
    function setComplemento($complemento){
        $this->complemento = $complemento;
    }
    
    function setName($nome){
        $this->nome = $nome;
    }
    
    function hasher($length = 32) {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
    
    function getCurl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
    
    function pegaNome(){
        if(!$this->nome){
            $autor =  $this->autor;
            $seleciona = "SELECT * FROM enderecos WHERE endereco_autor='$autor'";
            $resultado = $this->conn->query($seleciona);
            $total = $resultado->num_rows + 1;
            $nome = "Endereço ".$total;
        }else{
            $nome = $this->nome;
        }
        
        
        return $nome;
    }
    
    function meta($id, $chave, $valor){
    if(!$valor) {
        return;
    }

    $query = "SELECT em_id FROM enderecos_meta WHERE em_endereco=? AND em_chave=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("is", $id, $chave);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        $stmt = $this->conn->prepare("INSERT INTO enderecos_meta (em_endereco,em_chave,em_valor) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $chave, $valor);
    } else {
        $row = $result->fetch_assoc();
        $stmt = $this->conn->prepare("UPDATE enderecos_meta SET em_valor=? WHERE em_id=?");
        $stmt->bind_param("si", $valor, $row['em_id']);
    }
    $stmt->execute();
    
        
    }
    
    function pegaEstado($uv = false, $pais = false) {
        $seleciona = "SELECT endereco_estado_id as id FROM enderecos_estados WHERE endereco_estado_pais='{$pais}' AND endereco_estado_codigo='{$uv}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            return $dado["id"];
        }
        return 0;
    }
    
    function pegaCidade($pais = false, $estado = false, $cidade = false) {
        if(!$pais || !$estado || !$cidade){
            return 0;
        }
        $seleciona = "SELECT endereco_cidade_id as id FROM enderecos_cidades WHERE endereco_cidade_pais='{$pais}' AND endereco_cidade_estado='{$estado}' AND endereco_cidade_nome='{$cidade}'";
    
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            $dado = $resultado->fetch_assoc();
            return $dado["id"];
        }
        return 0;
    }

    function api($update = false) {

    $url = "https://viacep.com.br/ws/{$this->cep}/json";

    $dado = $this->getCurl($url);

    if (empty($dado) || !isset($dado['cep'])) {
        $this->simples(); 
        return;
    }


    $cep = $dado['cep'];
    $rua = $dado['logradouro'] ?? '';
    $autor = $this->autor;
    $complentorua = $dado['complemento'] ?? '';
    $bairro = $dado['bairro'] ?? '';
    $cidade = $dado['localidade'] ?? '';
    $estado = $dado['uf'] ?? '';
    $ibge = $dado['ibge'] ?? '';
    $gia = $dado['gia'] ?? '';
    $ddd = $dado['ddd'] ?? '';
    $regiao = $dado['regiao'] ?? '';
    $siafi = $dado['siafi'] ?? '';
    $hash = $this->hasher(32);
    $nome = $this->pegaNome();


    $endereco = "{$rua} {$cidade} {$estado}";
    $geocode = $this->geoCode($endereco);
    $latitude = $geocode['lat'] ?? 0;
    $longitude = $geocode['lon'] ?? 0;
    
    $pais = 31;
    $estado = $this->pegaEstado($estado, $pais);
    $cidade = $this->pegaCidade($pais, $estado , $cidade);
    $url = $this->hasher(16);
    
    
    $observacao = $POST["observacao"] ?? NULL;
    $numero = $this->numero ?? $_POST["numero"] ?? NULL;
    $complemento = $this->complemento ?? $_POST["complemento"] ?? NULL;
    $referencia = $_POST["referencia"] ?? NULL;
    



       // Inserção ou atualização
    if (!$update) {
        // Inserção
        $cadastra = $this->conn->prepare("
            INSERT INTO enderecos (
                endereco_nome, endereco_cep, endereco_hash, endereco_autor, 
                endereco_latitude, endereco_longitude, endereco_pais, 
                endereco_estado, endereco_cidade, endereco_bairro, endereco_complemento, endereco_rua, endereco_url, endereco_observacao, endereco_numero, endereco_referencia
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ?, ?, ? , ? , ?)
        ");

        if (!$cadastra) {
            die("Erro na preparação da query de inserção: " . $this->conn->error);
        }
        $this->lat = $latitude;
        $this->long = $longitude;

        $cadastra->bind_param('ssssddiiisssssss', $nome, $cep, $hash, $autor, $latitude, $longitude, $pais, $estado, $cidade, $bairro, $complentorua, $rua, $url, $observacao, $numero, $referencia);

        if (!$cadastra->execute()) {
            die("Erro ao executar query de inserção: " . $cadastra->error);
        }

        $id = $this->conn->insert_id;
        $cadastra->close();

    } else {
  
        $id = $update;
        $atualiza = $this->conn->prepare("
            UPDATE enderecos 
            SET endereco_latitude = ?, endereco_longitude = ?, 
                endereco_pais = ?, endereco_estado = ?, endereco_cidade = ? , endereco_bairro = ? , endereco_complemento = ?, endereco_rua = ?, endereco_observacao = ?, endereco_numero = ?, endereco_complemento = ?, endereco_referencia = ?
            WHERE endereco_id = ?
        ");

        if (!$atualiza) {
            die("Erro na preparação da query de atualização: " . $this->conn->error);
        }
        
        
        $this->lat = $latitude;
        $this->long = $longitude;
        $atualiza->bind_param('ddiiisssssssi', $latitude, $longitude, $pais, $estado, $cidade, $bairro, $complentorua, $rua , $observacao, $numero, $complemento, $referencia, $id);

        if (!$atualiza->execute()) {
            die("Erro ao executar query de atualização: " . $atualiza->error);
        }

        $atualiza->close();
    }


    $this->idcadastrado = $id;





}

    
    function geoCode($endereco) {
        
    $enderecoEncode = str_replace([' ', ','], '-', $endereco);
    $api = "671fc15cd3cb9522621940kcq22c469";
    $url = "https://geocode.maps.co/search?q={$enderecoEncode}&api_key={$api}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $obj = json_decode($response, true);
    
    if(empty($obj)){
        ["lat"=>0, "lon"=>0];
    }else{
        $geocode = $obj[0];
        
        return ["lat"=>$geocode["lat"], "lon"=>$geocode["lon"]];
    }
    
    
        
    }
    
    function simples(){
    $nome = $this->pegaNome();
    $hash = $this->hasher(32);
    $cep = $this->cep;
    $autor = $this->autor;
    $url = $this->hasher(16);
    
     $observacao = $_POST["observacao"] ?? NULL;
    $numero = $_POST["numero"] ?? NULL;
    $complemento = $_POST["complemento"] ?? NULL;
    $referencia = $_POST["referencia"] ?? NULL;
    
    
    $cadastra = "INSERT INTO enderecos (endereco_nome, endereco_cep , endereco_hash, endereco_autor, endereco_url, endereco_observacao, endereco_numero, endereco_complemento, endereco_referencia) VALUES ('$nome', '$cep', '$hash', '$autor', '$url', '$observacao', '$numero', '$complemento', '$referencia')";
    
    if ($this->conn->query($cadastra) === TRUE) {
        $this->idcadastrado = $this->conn->insert_id;
    }else{
        $this->idcadastrado = false;
    }
}

    function associa($id){

        if($this->idcadastrado){
            $endereco = $this->idcadastrado;
            $cadastra = "INSERT INTO enderecos_associacoes (endereco_associacao_endereco,endereco_associacao_banco,endereco_associacao_identificador,endereco_associacao_autor) VALUES ('$endereco', 'usuarios', '$id', '$id')";
            $this->conn->query($cadastra);
        }
    }

    
}


?>