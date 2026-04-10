<?


include_once(__DIR__."/../../../../admin/onlyapi.php");

class Usuario{
    private $acao;
    private $cpf;
    private $email;
    private $nome;
    private $telefone;
    private $conn;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->usuario = [];
        $this->conn = conn();

        if (!empty($_POST["usuario"])) {

    $decodificado = json_decode($_POST["usuario"], true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decodificado)) {
        $this->usuario = $decodificado;
    }
}

    }
    
    function validarCPF($cpf) {
    // Remove tudo que não for número
    $cpf = preg_replace('/\D/', '', $cpf);

    // Verifica se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    // Validação dos dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = (10 * $soma) % 11;
        $digito = ($digito == 10 || $digito == 11) ? 0 : $digito;
        if ($cpf[$t] != $digito) {
            return false;
        }
    }

    // Retorna CPF formatado
    return substr($cpf, 0, 3) . '.' .
           substr($cpf, 3, 3) . '.' .
           substr($cpf, 6, 3) . '-' .
           substr($cpf, 9, 2);
}

    function validarTelefone($telefone) {
    // Remove tudo que não for número
    $telefone = preg_replace('/\D/', '', $telefone);

    // Verifica se tem 10 ou 11 dígitos
    if (!in_array(strlen($telefone), [10, 11])) {
        return false;
    }

    // Se for 11 dígitos, o terceiro dígito deve ser 9 (número de celular)
    if (strlen($telefone) === 11 && $telefone[2] !== '9') {
        return false;
    }

    // Formata
    $ddd = substr($telefone, 0, 2);
    if (strlen($telefone) === 10) {
        $parte1 = substr($telefone, 2, 4);
        $parte2 = substr($telefone, 6, 4);
    } else {
        $parte1 = substr($telefone, 2, 5);
        $parte2 = substr($telefone, 7, 4);
    }

    return "($ddd) $parte1-$parte2";
}
    
    function validaEmail($email) {
    // Remove espaços e normaliza
    $email = trim($email);

    // Validação
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }

    return false;
}

    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function checkUsernameExists($username) {
    
    $conn = $this->conn; 

    $seleciona = "SELECT COUNT(*) FROM usuarios WHERE usuario_user = '$username'";
    $resultado = $conn->query($seleciona);
    $resultado->num_rows;
}

    function somenteNumeros($string) {
    return preg_replace('/\D/', '', $string);
}
    
    function ativacao(){
        $seleciona = "SELECT * FROM usuarios WHERE usuario_email='{$this->email}' OR usuario_telefone='{$this->telefone}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            
            $nomeprevo = str_replace(' ', '', strtolower(substr($this->nome, 0, 10))); 
        $username = $nomeprevo;
            while ($this->checkUsernameExists($username)) {
                $randomNumber = mt_rand(1, 9999);
                $username .= $nomeprevo.$randomNumber;
                if (strlen($username) > 15) {
                    $username = substr($username, 0, 15);
                }
            }
            
            $senha = md5($this->somenteNumeros($this->cpf));
            $cadastra = "INSERT INTO usuarios (usuario_display,usuario_email,usuario_telefone,usuario_cpf,usuario_user, usuario_hash,usuario_funcao, usuario_ativo, usuario_senha) VALUES ('{$this->nome}', '{$this->email}', '{$this->telefone}', '{$this->cpf}', '{$username}', '{$this->hasher()}', '2', '1', '{$senha}')";
 
            if($this->conn->query($cadastra) == true){
                $user = $this->conn->insert_id;
                if(!empty($this->metas)){
                    foreach($this->metas as $chave=>$valor){
                        
                        $cadastra = "INSERT INTO usuarios_meta (um_usuario,um_chave,um_valor) VALUES ('{$user}', '{$chave}', '{$valor}')";
                        $this->conn->query($cadastra);
                        


                    }
                }
                
                
                return ["sucesso"=>true, "mensagem"=>"Usuário cadastrado com sucesso"];
            }else{
               return ["erro"=>true, "mensagem"=>"Não foi possível ativar o usuário"];
            }
            
           
        }
        
         $dado = $resultado->fetch_assoc();
            if($dado["usuario_ativo"] == 1){
                return ["sucesso"=>true, "mensagem"=>"O usuário já está cadastrado e ativo"];
            }
            
            $id = $dado["usuario_id"];
            
            $ativa = "UPDATE usuarios SET usuario_ativo='1' WHERE usuario_id='{$id}'";
            if($this->conn->query($ativa) == true){
                return ["sucesso"=>true, "mensagem"=>"O usuário foi ativado com sucesso"];
            }
        
        

    }
    
    function desativacao(){
         $seleciona = "SELECT * FROM usuarios WHERE usuario_email='{$this->email}' OR usuario_telefone='{$this->telefone}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"O usuário não está cadastrado no sistema"];
        }
        
        $dado = $resultado->fetch_assoc();
        $id = $dado["usuario_id"];
        $ativo = $dado["usuario_ativo"];
        
        if($ativo == "0"){
            return ["sucesso"=>true, "mensagem"=>"O usuário já está desativado"];
        }
        
        $update = "UPDATE usuarios SET usuario_ativo='0' WHERE usuario_id='$id'";
        if($this->conn->query($update) == true){
            return ["sucesso"=>true, "mensagem"=>"Usuário desativado com sucesso"];
        }
        
        return ["erro"=>true, "mensagem"=>"Não foi possível desativar o usuário"];
        
        
        
    }
    
    function status(){
        $seleciona = "SELECT * FROM usuarios WHERE usuario_email='{$this->email}' OR usuario_telefone='{$this->telefone}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Usuário não encontrado"];   
        }
        
        $dado = $resultado->fetch_assoc();
        $ativo = $dado["usuario_ativo"];
        $id = $dado["usuario_id"];
        return ["sucesso"=>true, "ativo"=>$ativo, "id"=>$id];
    }
    
    function validarCNPJ($cnpj) {
        if(!$cnpj){
            return false;
        }
    // Remove tudo que não for número
    $cnpj = preg_replace('/\D/', '', $cnpj);
    
    // Verifica se tem 14 dígitos
    if (strlen($cnpj) != 14) {
        return false;
    }
    
    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
        return false;
    }
    
    // Validação dos dígitos verificadores
    // Primeiro dígito verificador
    $soma = 0;
    $multiplicador = 5;
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $multiplicador;
        $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
    }
    $resto = $soma % 11;
    $dv1 = ($resto < 2) ? 0 : 11 - $resto;
    
    // Segundo dígito verificador
    $soma = 0;
    $multiplicador = 6;
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $multiplicador;
        $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
    }
    $soma += $dv1 * 2;
    $resto = $soma % 11;
    $dv2 = ($resto < 2) ? 0 : 11 - $resto;
    
    // Verifica se os dígitos verificadores estão corretos
    if ($cnpj[12] != $dv1 || $cnpj[13] != $dv2) {
        return false;
    }
    
    // Formata o CNPJ
    return substr($cnpj, 0, 2) . '.' .
           substr($cnpj, 2, 3) . '.' .
           substr($cnpj, 5, 3) . '/' .
           substr($cnpj, 8, 4) . '-' .
           substr($cnpj, 12, 2);
}

    function ativaCnpj() {
    // Verificar se o CNPJ foi fornecido
    if (empty($this->usuario["cnpj"])) {
        return ["erro" => true, "mensagem" => "CNPJ não fornecido"];
    }
    
    // Validar o CNPJ
    $cnpj = $this->validarCNPJ($this->usuario["cnpj"]);
    if (!$cnpj) {
        return ["erro" => true, "mensagem" => "O CNPJ informado é inválido"];
    }
    
    // Verificar se já existe um usuário com este email ou telefone
    $seleciona = "SELECT * FROM usuarios WHERE usuario_email='{$this->email}' OR usuario_telefone='{$this->telefone}' LIMIT 1";
    $resultado = $this->conn->query($seleciona);
    
    $userId = null;
    
    if ($resultado->num_rows == 0) {
        // Criar o usuário se não existir
        $nomeprevo = str_replace(' ', '', strtolower(substr($this->nome, 0, 10)));
        $username = $nomeprevo;
        
        while ($this->checkUsernameExists($username)) {
            $randomNumber = mt_rand(1, 9999);
            $username .= $randomNumber;
            if (strlen($username) > 15) {
                $username = substr($username, 0, 15);
            }
        }
        
        // Senha será o CNPJ somente números
        $cnpjSemFormatacao = $this->somenteNumeros($this->usuario["cnpj"]);
        $senha = md5($cnpjSemFormatacao);
        
        $cadastra = "INSERT INTO usuarios (usuario_display, usuario_email, usuario_telefone, usuario_cpf, usuario_user, usuario_hash, usuario_funcao, usuario_ativo, usuario_senha) VALUES ('{$this->nome}', '{$this->email}', '{$this->telefone}', '{$this->cpf}', '{$username}', '{$this->hasher()}', '2', '1', '{$senha}')";
        
        if ($this->conn->query($cadastra) == true) {
            $userId = $this->conn->insert_id;
            
            // Inserir metas do usuário, se existirem
            if (!empty($this->metas)) {
                foreach ($this->metas as $chave => $valor) {
                    $cadastraMeta = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('{$userId}', '{$chave}', '{$valor}')";
                    $this->conn->query($cadastraMeta);
                }
            }
        } else {
            return ["erro" => true, "mensagem" => "Não foi possível cadastrar o usuário"];
        }
    } else {
        $dado = $resultado->fetch_assoc();
        $userId = $dado["usuario_id"];
        
        // Ativar o usuário se estiver inativo
        if ($dado["usuario_ativo"] == 0) {
            $ativa = "UPDATE usuarios SET usuario_ativo='1' WHERE usuario_id='{$userId}'";
            $this->conn->query($ativa);
        }
    }
    
    // Se chegou até aqui, temos um ID de usuário válido
    if ($userId) {
        // Iniciar a sessão com o ID do usuário
        $_SESSION["id"] = $userId;
        
        // Incluir arquivo de empresa
        include_once(__DIR__."/../../empresas/admins/empresas.php");
        
        // Criar instância da classe Empresa
        $empresa = new Empresa($cnpj);
        
        // Consultar os dados da empresa na Receita Federal
        $resultado = $empresa->consulta();
        
        // Associar o usuário à empresa
        $empresa->associa($userId);
        
        return ["sucesso" => true, "mensagem" => "Usuário e empresa cadastrados com sucesso", "usuario_id" => $userId, "empresa_id" => $empresa->idInserido];
    }
    
    return ["erro" => true, "mensagem" => "Não foi possível completar a operação"];  
}
    
    function render(){
        
  
        if(empty($this->usuario["email"]) || (empty($this->usuario["cpf"]) && empty($this->usuario["cnpj"])) || empty($this->usuario["nome"])){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessarios"];
        }
        
        
        
        $this->email = $this->validaEmail($this->usuario["email"]);
        $this->cpf = $this->validarCPF($this->usuario["cpf"]);
        $this->nome = $this->usuario["nome"];
        $this->telefone = $this->validarTelefone($this->usuario["telefone"] ?? NULL);
        $this->cnpj= $this->validarCNPJ($this->usuario["cnpj"] ?? NULL);
        
        if(!$this->cpf && !$this->cnpj){
            return ["erro"=>true, "mensagem"=>"O documento informado é inválido"];
        }
        
        
        if(!$this->telefone){
            $this->telefone = NULL;
            //return ["erro"=>true, "mensagem"=>"O telefone informado é inválido"];
        }
        
        if(!$this->email){
            return ["erro"=>true, "mensagem"=>"O e-mail informado é inválido"];
        }
        
        $this->metas = $this->usuario["metas"] ?? [];
        
        
        
        switch($this->acao){
            case 'ativacao':
                return $this->ativacao();
                break;
            case 'cnpj':
                return $this->ativaCnpj();
                break;
            case 'desativacao':
                return $this->desativacao();
                break;
            case 'status':
                return $this->status();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi definida uma ação válida"];
                break;
        }
    }
}


$acao = new Usuario();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>