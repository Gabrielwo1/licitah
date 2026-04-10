<?
include 'sessao.php';
$resposta = [];

include 'conn.php';


class Social{
    private $acao;
    private $rede;
    private $conn;
    function __construct($rede, $acao){
        $this->rede = $rede;
        $this->acao = $acao;
        $this->conn = conn();
    }
    
    
    function loginGoogle(){
          if(isset($_POST["infos"])){
            $conn = $this->conn;
            $infos = json_decode($_POST["infos"], true);
            $codigoGoogle = $infos["sub"];
            
            $seleciona = "SELECT um_usuario FROM usuarios_meta WHERE um_chave='social_google' AND um_valor='$codigoGoogle'";
            $teste = $conn->query($seleciona);
            if($teste->num_rows == 1){
                $info = $teste->fetch_assoc();
                $id = $info["um_usuario"];
                
                $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$id'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                return criasessao($dado);
            }else{
                return ["erro"=>true, "mensagem"=>"Usuário não atvio"];
            }
            
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi encontrado ."];
        }
          }else{
              return ["erro"=>true, "mensagem"=>"Não foram envidas as informações corretamente."];
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
    
    
    function meta($id, $chave, $valor){
        $conn = $this->conn;
        $cadastra = "INSERT INTO usuarios_meta (um_usuario,um_chave,um_valor) VALUES ('$id', '$chave', '$valor')";
        $conn->query($cadastra);
        
    }
    
    function cadastra($email, $nome, $sobrenome, $display, $foto, $codigoGoogle){
        $senha = md5($this->hasher());
        $conn = $this->conn;
        
        $hash = $this->hasher();
        $usuario = $this->usuario($display);
        $acao = "INSERT INTO usuarios (usuario_display, usuario_email, usuario_senha, usuario_hash,usuario_user) VALUES  ('$display', '$email', '$senha', '$hash', '$usuario')";
        if($conn->query($acao) == true){
            $id = $conn->insert_id;
            

            $this->meta($id, "nome", $nome);
            $this->meta($id, "sobrenome", $sobrenome);
            $this->meta($id, "imagem_foto", $foto);
            $this->meta($id, "social_google", $codigoGoogle);
            
            $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$id'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 1){
                return criasessao($dado);
            }else{
                return ["erro"=>true, "mensagem"=>"Erro ao Cadastrar Usuário"];
            }
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao Cadastrar Usuário"];
        }
    }
    
    function cadastroGoogle(){
        if(isset($_POST["infos"])){
            $conn = $this->conn;
            
            $infos = json_decode($_POST["infos"], true);
            
            $email = $infos["email"];
            $sobrenome = $infos["family_name"];
            $nome = $infos["given_name"];
            $display = $infos["name"];
            $foto = $infos["picture"];
            $codigoGoogle = $infos["sub"];
            
            
            
            $seleciona = "SELECT * FROM usuarios WHERE usuario_email='$email'";
            $resultado = $conn->query($seleciona);
            if($resultado->num_rows == 0){
                return $this->cadastra($email, $nome, $sobrenome, $display, $foto, $codigoGoogle);
            }else{
                $dado = $resultado->fetch_assoc();
                
                $id = $dado["usuario_id"];
                
                $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='social_google' AND um_valor='$codigoGoogle'";
                $teste = $conn->query($seleciona);
                if($teste->num_rows == 1){
                    return criasessao($dado);
                }else{
                    return ["erro"=>true, "mensagem"=>"E-mail já cadastrado, mas sem login social ativado. Acesse usando sua senha."];
                }
                
                
            }

            
        }else{
            return ["erro"=>true, "mensagem"=>"Cadastro Inválido"];
        }
    }
    
    function acao(){
        $resposta = [];
        if($this->acao == "login" || $this->acao == "cadastro"){
            switch($this->rede){
                case 1:
                    if($this->acao == "login"){
                        $resposta["sucesso"] = $this->loginGoogle();
                    }else{
                        $resposta["sucesso"] = $this->cadastroGoogle();
                    }
                    break;
            }
        }else{
            return false;
        }
        return $resposta;
        
        
    }
}


if(isset($_POST["rede"]) && isset($_POST["acao"])){
    switch(strtolower($_POST["rede"])){
        case 'google':
            $rede = new Social(1, $_POST["acao"]);
            $resposta["sucesso"] = $rede->acao();
            break;
        case 'facebook':
            $rede = new social(2, $_POST["acao"]);
            $resposta["sucesso"] = $rede->acao();
            break;
        case 'apple':
            $rede = new social(3, $_POST["acao"]);
            $resposta["sucesso"] = $rede->acao();
            break;
        default:
            $resposta["sucesso"] = false;
            break;
    }
}


echo json_encode($resposta);
?>