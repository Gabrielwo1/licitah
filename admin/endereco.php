<?


class Endereco{
    public $cep;
    public $id;
    public $conn;
    public $nome;
    public $autor;
    public $idcadastrado;
    
    function __construct($cep){
        $this->cep = $cep;
        $this->autor = $_SESSION["id"];
        $this->conn = conn();
        $this->nome = false;
    }
    
    function setName($nome){
        $this->nome = $nome;
    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
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
    
    function api(){
         $cep = preg_replace('/\D/', '', $this->cep);
         $url = "https://viacep.com.br/ws/$cep/json";

         $ch = curl_init($url);
                        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        
        $response = curl_exec($ch);
        curl_close($ch);
                        
       if ($response) {
           
           $dado = json_decode($response, true);
        
            if(!isset($dado["cep"])){
                $this->simples();
                return;
            }
            
               
            $cep = $dado["cep"];
            $rua = $dado["logradouro"] ?? "";
            
            $autor = $this->autor;
            $complentorua = $dado["complemento"] ?? "";
            $bairro = $dado["bairro"] ?? "";
            $cidade = $dado["localidade"] ?? "";
            $estado = $dado["uf"] ?? "";
            $ibge = $dado["ibge"] ?? "";
            $gia = $dado["gia"] ?? "";
            $ddd = $dado["ddd"] ?? "";
            $siafi = $dado["siafi"] ?? "";
            $hash = $this->hasher(32);
            $nome = $this->pegaNome();
            
            $cadastra = "INSERT INTO enderecos (endereco_nome, endereco_cep, endereco_hash, endereco_autor) VALUES ('$nome', '$cep', '$hash', '$autor')";


            if($this->conn->query($cadastra) == true){
     
                $id =  $this->conn->insert_id;
                $this->idcadastrado = $id;
                $this->meta($id, "pais", "Brasil");
                $this->meta($id, "bairro", $bairro);
                $this->meta($id, "complementobairro", $complentorua);
                $this->meta($id, "cidade", $cidade);
                $this->meta($id, "rua", $rua);
                $this->meta($id, "estado", $estado);
                $this->meta($id, "ibge", $ibge);
                $this->meta($id, "gia", $gia);
                $this->meta($id, "ddd", $ddd);
                $this->meta($id, "siafi", $siafi);
                
            }else{
         
                $this->simples();
            }
            }
            else{

                 $this->simples();
            }
    }
    
    function simples(){
    $nome = $this->pegaNome();
    $hash = $this->hasher(32);
    $cep = $this->cep;
    $autor = $this->autor;
    $cadastra = "INSERT INTO enderecos (endereco_nome, endereco_cep , endereco_hash, endereco_autor) VALUES ('$nome', '$cep', '$hash', '$autor')";
    
    if ($this->conn->query($cadastra) === TRUE) {
        $this->idcadastrado = $this->conn->insert_id;
    }else{
        $this->idcadastrado = false;
    }
}


    function associa($id){
        if($this->idcadastrado){
            $endereco = $this->idcadastrado;
            $cadastra = "INSERT INTO enderecos_associacao (enas_endereco,enas_banco,enas_identificador) VALUES ('$endereco', 'usuarios', '$id')";
            $this->conn->query($cadastra);
        }
    }

    
}

?>