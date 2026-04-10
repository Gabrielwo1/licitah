<?

class Empresa{
    public $cnpj;
    public $conn;
    public $user;
    public $idInserido;
    function __construct($cnpj){
        $this->cnpj = $cnpj;
        $this->conn = conn();
        $this->user = $_SESSION["id"];
    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function meta($id, $chave, $valor){
    if(!$valor) {
        return;
    }

    $query = "SELECT em_id FROM empresas_meta WHERE em_empresa=? AND em_chave=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("is", $id, $chave);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        $stmt = $this->conn->prepare("INSERT INTO empresas_meta (em_empresa,em_chave,em_valor) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $chave, $valor);
    } else {
        $row = $result->fetch_assoc();
        $stmt = $this->conn->prepare("UPDATE em_empresa SET em_valor=? WHERE em_id=?");
        $stmt->bind_param("si", $valor, $row['em_id']);
    }
    $stmt->execute();
    
        
    }
    
    function pegaReceita(){
          $cnpj = $this->cnpj;
          
          $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
          
          $url = "https://receitaws.com.br/v1/cnpj/$cnpj";
          
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Define um timeout para a requisição
          
          $response = curl_exec($ch);
          

          $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);
          
          if ($httpCode == 200) {
              $data = json_decode($response, true);
              
                if (isset($data['status']) && $data['status'] === 'ERROR') {
                     return ["erro"=>true, "mensagem"=>"Foi encontrado um erro"];
                }
              
               return ["sucesso"=>true, "dados"=>$data];
              
              
          }else{
              return ["erro"=>true, "mensagem"=>"A solicitação não foi completada"];
          }
    }
    
    function consulta(){
        
        $dados = $this->pegaReceita();
        if(isset($dados["erro"])){
            return $dados;
        }
        
        $obj = $dados["dados"];
        
        $usuario = $this->user;
        $abertura = $obj["abertura"];
             $situacao = $obj["situacao"];
             $tipo = $obj["tipo"];
             $nome = $this->conn->real_escape_string($obj["nome"]);
             $fantasia = $this->conn->real_escape_string($obj["fantasia"]);
             $porte = $obj["porte"];
             $natureza = $obj["natureza_juridica"];
             $hash = $this->hasher(32);
             $url = $this->hasher(10);
             $cnpj = $obj["cnpj"];
             
             $socios = [];
             
             foreach($obj["qsa"] as $socio){
                 array_push($socios, [
                     "xBy2zLn6EnOBHdsxzAEoSA95bvN3Sb"=>$socio["nome"],
                     "OmDOx2e0K6uCCCn0W4G0f7vNjslGuI"=>$socio["qual"]
                     ]);
             }
             
             $socios = json_encode($socios, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT); 
             
             $secundarias = [];
               foreach($obj["atividades_secundarias"] as $secundaria){
                 array_push($secundarias, [
                     "1vhlNNW8KtqPmTiqrtsAkIFV42jKIp"=>$secundaria["text"],
                     "jvkwJxhHz1rj7N1SUIfuUCpJNLnpYW"=>$secundaria["code"]
                     ]);
             }
             $secundarias = json_encode($secundarias, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT); 
             
             
             
              $emails = explode("/" , $obj["email"]);
              $telefones = explode("/" ,$obj["telefone"]);
              
              $tels = [];
              $mails = [];
              
              foreach($telefones as $telefone){
                  array_push($tels, ["Prkdpq8qtyU7pSxnLdspB2x23VyGwd"=>preg_replace('/\s+/', '', $telefone)]);
              }
              $tels  = json_encode($tels, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT); 
              
              foreach($emails as $email){
                  array_push($mails , ["eWgi9LEUy5K994Jrj6d2kki0MYKYKn"=>preg_replace('/\s+/', '', $email)]);
              }
              $mails  = json_encode($mails, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT); 
                
                
            $cadastra = "INSERT INTO empresas (
                empresa_nome , empresa_cnpj , empresa_telefones , empresa_emails , empresa_socios , empresa_autor , empresa_url , empresa_hash, empresa_secundaria) VALUES 
                ('$nome', '$cnpj' ,  '$tels', '$mails', '$socios', '$usuario', '$url', '$hash', '$secundarias')";

                if($this->conn->query($cadastra) == true){
                    $id =  $this->conn->insert_id;
      
                    
                    $this->meta($id, "razao_social", $nome);
                    $this->meta($id, "nome_fantasia", $fantasia);
                    $this->meta($id, "situação", $situacao);
                    $this->meta($id, "porte", $porte);
                    $this->meta($id, "abertura", $abertura);
                    $this->meta($id, "natureza_juridica", $natureza);
                    $this->meta($id, "primario_cnae", $obj["atividade_principal"][0]["code"]);
                    $this->meta($id, "primario_nome", $obj["atividade_principal"][0]["text"]);
                    $this->idInserido = $id; 
                }
        
    
  

    }
    
    function update($id){
        $dados = $this->pegaReceita();
        if(isset($dados["erro"])){
            return $dados;
        }
        
        $this->idInserido = $id;
        
        $obj = $dados["dados"];
        
        $usuario = $this->user;
        $abertura = $obj["abertura"];
        $situacao = $obj["situacao"];
        $tipo = $obj["tipo"];
        $nome = $obj["nome"];
        $fantasia = $obj["fantasia"];
        $porte = $obj["porte"];
        $natureza = $obj["natureza_juridica"];
        $cnpj = $obj["cnpj"];
        
        $socios = [];
        foreach($obj["qsa"] as $socio){
            array_push($socios, [
                "xBy2zLn6EnOBHdsxzAEoSA95bvN3Sb" => $socio["nome"],
                "OmDOx2e0K6uCCCn0W4G0f7vNjslGuI" => $socio["qual"]
            ]);
        }
        $socios = json_encode($socios, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $secundarias = [];
        foreach($obj["atividades_secundarias"] as $secundaria){
            array_push($secundarias, [
                "1vhlNNW8KtqPmTiqrtsAkIFV42jKIp" => $secundaria["text"],
                "jvkwJxhHz1rj7N1SUIfuUCpJNLnpYW" => $secundaria["code"]
            ]);
        }
        $secundarias = json_encode($secundarias, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $emails = explode("/", $obj["email"]);
        $telefones = explode("/", $obj["telefone"]);
        
        $tels = [];
        $mails = [];
        
        foreach($telefones as $telefone){
            array_push($tels, ["Prkdpq8qtyU7pSxnLdspB2x23VyGwd" => preg_replace('/\s+/', '', $telefone)]);
        }
        $tels = json_encode($tels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        foreach($emails as $email){
            array_push($mails, ["eWgi9LEUy5K994Jrj6d2kki0MYKYKn" => preg_replace('/\s+/', '', $email)]);
        }
        $mails = json_encode($mails, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $updateEmpresa = "UPDATE empresas SET 
            empresa_nome = ?, 
            empresa_cnpj = ?, 
            empresa_telefones = ?, 
            empresa_emails = ?, 
            empresa_socios = ?, 
            empresa_secundaria = ? 
            WHERE empresa_id = ?";
        
        $stmt = $this->conn->prepare($updateEmpresa);
        $stmt->bind_param("ssssssi", $nome, $cnpj, $tels, $mails, $socios, $secundarias, $id);
        $stmt->execute();
        
        // Atualiza os valores na tabela empresas_meta
        $this->meta($id, "razao_social", $nome);
        $this->meta($id, "nome_fantasia", $fantasia);
        $this->meta($id, "situação", $situacao);
        $this->meta($id, "porte", $porte);
        $this->meta($id, "abertura", $abertura);
        $this->meta($id, "natureza_juridica", $natureza);
        $this->meta($id, "primario_cnae", $obj["atividade_principal"][0]["code"]);
        $this->meta($id, "primario_nome", $obj["atividade_principal"][0]["text"]);
    }

    
    function associa($id){
        if($this->idInserido){
            $empresa = $this->idInserido;
            $cadastra = "INSERT INTO empresas_associacao (ea_empresa,ea_usuario,ea_funcao) VALUES ('$empresa', '$id', '0')";
            $this->conn->query($cadastra);
        }
    }
}



?>