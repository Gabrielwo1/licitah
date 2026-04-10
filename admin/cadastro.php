<?
header('Content-Type: application/json; charset=utf-8');


include 'afiliados.php';

function validaTelefone($numeroTelefone) {
    $numeroTelefone = preg_replace('/[^0-9]/', '', $numeroTelefone);

    if (strlen($numeroTelefone) < 10 || strlen($numeroTelefone) > 14) {
        return false;
    }

    return true;
}

function validaEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }
    return true;
}

class Acao{
    private $conn;
    function __construct(){
        include 'conn.php';
        $this->conn = conn();
        

    }
    
    function cadastro(){
        $resposta = [];
        if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["telefone"]) && isset($_POST["senha"])){
             $nome = addslashes($_POST["nome"]);
             $email = $_POST["email"];
             $telefone = $_POST["telefone"];
             $senha = md5($_POST["senha"]);
             
             if(validaEmail($email) && validaTelefone($telefone)){
                 $seleciona = "SELECT * FROM usuarios WHERE usuario_email='$email'";
                 $resultado = $this->conn->query($seleciona);
                 
                 if($resultado->num_rows > 0){
                     $resposta["erro"] = true;
                     $resposta["codigo"] = 2;
                 }else{
                    $seleciona = "SELECT * FROM usuarios WHERE usuario_telefone='$telefone'";
                    $resultado = $this->conn->query($seleciona);
                    
                    if($resultado->num_rows > 0){
                        $resposta["erro"] = true;
                        $resposta["codigo"] = 3;
                    }else{
                        $usuario = $this->usuario($nome);
                        $hash = $this->hasher();
                        $cadastra = "INSERT INTO  usuarios 
                        (usuario_display, usuario_email	,usuario_senha ,usuario_funcao ,usuario_subfuncao ,usuario_ativo , usuario_hash	,usuario_telefone, usuario_user) VALUES
                        ('$nome', '$email', '$senha', '2', '1', '0', '$hash', '$telefone', '$usuario')
                        ";
                        
                        if($this->conn->query($cadastra) == true){
                            include 'sessao.php';
                            $resposta["erro"] = false;
                            
                            $row = [];
                            $row["usuario_id"] = $this->conn->insert_id;
                            $row["usuario_display"] = $nome;
                            $row["usuario_email"] = $email;
                            $row["usuario_funcao"] = 2;
                            $row["usuario_subfuncao"] = 1;
                            $row["usuario_ativo"] = 0;
                            $resposta["sucesso"] = criasessao($row);
                            
                            referencia($status);
                            afilia($row["usuario_id"]);
                            
                        }else{
                            $resposta["erro"] = true;
                            $resposta["codigo"] = $this->conn->error();
                        }
                    }
                      
                       
                        
                    
                 }
                 
                 
                 
             }else{
                 $resposta["erro"] = true;
                 $resposta["codigo"] = 1;
             }
        }else{
            $resposta["erro"] = true;
        }
        
        return $resposta;
    }
    
    function sessao(){
        if(isset($_POST["chave"]) && $_POST["chave"]){
            $chave = $_POST["chave"];
            
            $seleciona = "SELECT * FROM sessoes WHERE sessao_hash='$chave'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $user = $dado["sessao_usuario"];
                
                $seleciona = "SELECT * FROM usuarios where usuario_id='$user'";
                $resultado = $this->conn->query($seleciona);
                
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    
                    
                    $deleta = "DELETE FROM sessoes WHERE sessao_hash='$chave'";
                    $this->conn->query($deleta);
                    
                    include 'sessao.php';
                    return criasessao($dado);
                    
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    function hasher() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = 20;
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
}

   function usuario($fullName) {
    // Remova espaços em branco no início e no fim do nome completo
    $fullName = trim($fullName);

    // Converta o nome completo para letras minúsculas
    $fullName = strtolower($fullName);

    // Remova caracteres especiais e espaços em branco extras
    $fullName = preg_replace('/[^a-z0-9]+/', '', $fullName);

    // Verifique se o nome completo está vazio após a remoção de caracteres especiais
    if (empty($fullName)) {
        return false;
    }

    // Gere um novo nome de usuário
    $username = substr($fullName, 0, 10); // Pegue os primeiros 10 caracteres

    // Verifique se o nome de usuário já existe
    while ($this->checkUsernameExists($username)) {
        // O nome de usuário já existe, então gere um novo número aleatório e adicione ao final do nome de usuário
        $randomNumber = mt_rand(1, 9999);
        $username .= $randomNumber;

        // Verifique se o nome de usuário excede 15 caracteres após adicionar o número
        if (strlen($username) > 15) {
            $username = substr($username, 0, 15); // Truncate o nome de usuário para 15 caracteres
        }
    }

    return $username;
}

   function checkUsernameExists($username) {
    
    $conn = $this->conn; 

    $seleciona = "SELECT COUNT(*) FROM usuarios WHERE usuario_user = '$username'";
    $resultado = $conn->query($seleciona);
    $resultado->num_rows;
}
}


$resposta = [];

if(isset($_POST["acao"])){
    $acao = new Acao(); 
    switch($_POST["acao"]){
        
        case 'cadastro':
            $resposta["resposta"] = $acao->cadastro();
            break;
        case 'nova-senha':
            break;
        case 'set-senha':
            break;
        case 'sessao':
            $resposta["resposta"] = $acao->sessao();
            break;
        default:
             $resposta["resposta"] = false;
            break;

    }
}

echo json_encode($resposta);
?>