<?
include __DIR__."/seguranca-include.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'conn.php';
include_once 'sessao.php';
include_once 'notificacao.php';
include_once 'validador.php';

class Acao{
    public $key;
    public $login;
    public $senha;
    public $tipo;
    public $conn;
    public $acao;
    public $capcha;
    public $token;
    public $nome;
    public $email;
    public $celular;
    public $cpf;
    public $setup;
    public $cep;
    public $cnpj;
    public $hash;
    
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
        $this->capcha = $_POST["capcha"] ?? false;
        $this->token = $_POST["token"] ?? false;
        $this->conn = conn();
        
        $this->login = $_POST["login"] ?? false;
        $this->senha = $_POST["senha"] ?? false;
        if($this->senha){
            $this->senha = md5($this->senha);
        }
        
        $this->nome = $_POST["nome"] ?? false;
        $this->email = $_POST["email"] ?? false;
        $this->celular = $_POST["celular"] ?? false;
        $this->cpf = $_POST["cpf"] ?? false;
        
        $this->configs();
    }
  
    function tokenLogin(){
        $token = $_POST["token"] ?? false;
        if(!$token){
            return ["erro"=>true, "mensagem"=>"Token não enviado", "status"=>0];
        }
        
        
        $seleciona = "SELECT * FROM tokenLogin WHERE token_publico='$token' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
          $dado = $resultado->fetch_assoc();
          $user = $dado["token_user"]; 
          
          $deleta = "DELETE FROM tokenLogin WHERE token_user='$user'";
          $this->conn->query($deleta);
          
          $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$user'";
          $resultado = $this->conn->query($seleciona);
          if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
             
             $sessao = criasessao($dado);
             return ["sucesso"=>true, "mensagem"=>"Login feito com sucesso", "status"=>1, "sessao"=>$sessao, "token"=>$_SESSION['csrf_token']["token"]];
          }else{
              return ["sucesso"=>true, "mensagem"=>"Token Inválido", "status"=>0];
          }
    }else{
        return ["sucesso"=>true, "mensagem"=>"Token Não Encontrado", "status"=>0];
    }
}

    function rastro($sucesso = false){
          $ipAddress = $_SERVER['REMOTE_ADDR'];
        if($sucesso){
            $sql = "DELETE FROM login_attempts WHERE ip_address = '$ipAddress'";
            $this->conn->query($sql);
        } else {
            $sql = "INSERT INTO login_attempts (ip_address) VALUES ('$ipAddress')";
            $this->conn->query($sql);
        }
    }
    
    function loginCNPJ(){
        $seleciona = "SELECT empresa_autor FROM  empresas WHERE empresa_cnpj='{$this->login}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        
        $dado = $resultado->fetch_assoc();
        return $dado["empresa_autor"];
        
        
    }

    function login(){
        if(!$this->login || !$this->senha || !$this->tipo){
            return ["erro"=>true, "mensagem"=>"Não foram enviadas informações válidas de login"];
        }
        
         $login = $this->login;
         
        switch($this->tipo){
            case 'email':
                $coluna = "usuario_email";
                break;
            case 'cpf':
                 $coluna = "usuario_cpf";
                break;
            case 'usuario':
                 $coluna = "usuario_user";
                break;
            case 'telefone':
                 $coluna = "usuario_telefone";
                break;
            case 'cnpj':
                $cnpj = $this->loginCNPJ();
                if(!$cnpj){
                    return ["erro"=>true, "mensagem"=>"Não foi encontrado o CNPJ desejado"];
                }
                $coluna = "usuario_id";
                $login = $cnpj;
                break;
            default:
                return ["erro"=>true, "mensagem"=>"O tipo enviando não é válido"];
                break;
        }
       
        $seleciona = "SELECT * FROM usuarios WHERE ".$coluna."='$login'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $this->rastro(false);
            return ["sucesso"=>true, "mensagem"=>"Não foi encontrao um usuário com as credenciais enviadas", "status"=>0];
        }
        
        $dado = $resultado->fetch_assoc();
        $senha = $dado["usuario_senha"];
        
        if($senha == $this->senha){
            $this->rastro(true);
            return ["sucesso"=>true, "mensagem"=>"Login feito com sucesso", "status"=>1, "sessao"=>$this->loginPorId($dado["usuario_id"])["sessao"], "token"=>$_SESSION['csrf_token']["token"]];
        }else{
             $this->rastro(false);
             return ["sucesso"=>true, "mensagem"=>"Não foi encontrao um usuário com as credenciais enviadas", "status"=>0];
        }
    
   
    }
    
    function tentativas(){
        date_default_timezone_set('America/Sao_Paulo');

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $attemptsAllowed = 5;
        $attemptsInterval = 10 * 60; 
        
        $sql = "SELECT COUNT(*) AS num_attempts FROM login_attempts WHERE ip_address = '$ipAddress' AND timestamp >= (NOW() - INTERVAL $attemptsInterval SECOND)";
        
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $numAttempts = $row['num_attempts'];
        return $numAttempts;
        }
        return 0;
    }
    
    function secureCheck(){
         date_default_timezone_set('America/Sao_Paulo');

    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Definir o fuso horário para o Brasil
    date_default_timezone_set('America/Sao_Paulo');

    // Consulta SQL para buscar o último registro associado ao endereço IP específico
    $sql = "SELECT * FROM login_attempts WHERE ip_address = '$ipAddress' ORDER BY timestamp DESC LIMIT 1";
    
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["timestamp"]; 
    }
    
    return false;
    }
    
    function loginPorId($id, $sessaoPre = false){
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            $segue = true;
            if($this->v(["paginas","cadastro","validaremail"], false)){
                $seleciona = "SELECT * FROM  usuarios_meta WHERE um_usuario='$id' AND um_chave='email-valido' AND um_valor='1'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    $segue = false;
                }

              
            }
            
            if($this->v(["paginas","cadastro","validartelefone"], false) && $segue){
                $seleciona = "SELECT * FROM  usuarios_meta WHERE um_usuario='$id' AND um_chave='telefone-valido' AND um_valor='1'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    $segue = false;
                }
            }
            
            
            if(!$segue){
                $_SESSION["validaCredenciais"] = true;
            }
            
            $sessao = criasessao($dado, $sessaoPre);
            return ["sucesso"=>true, "status"=>1, "sessao"=>$sessao, "token"=>$_SESSION['csrf_token']["token"]];
        }
         return ["sucesso"=>true, "status"=>0, "ensagem"=>"Não foi encontrado nenhum usuário com o ID definido"];
    }
    
    function loginSocial(){
        $infos = $_POST["infos"] ?? false;
        if(!$infos){
            return ["erro"=>true, "mensagem"=>"Não foram enviadas informações válidas"];
        }
        $array = json_decode($infos, true);
        $rede = "login_".$array["login"];
        $id = $array["id"];
        
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_chave='$rede' AND um_valor='$id'";
 
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $usuario = $dado["um_usuario"];
            return $this->looginPorId($usuario);
        }
        
        if(isset($array["email"])){
            $email = $array["email"];
            $seleciona = "SELECT * FROM usuarios WHERE usuario_email='$email'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $usuario = $dado["usuario_id"];
                return $this->loginPorId($usuario);
            }
        }
        
        return ["sucesso"=>true, "mensagem"=>"Não foi encontrado um usuário com as credenciais definidas", "status"=>0];
        
 
    }
    
    function cadastroSocial(){
     
       $ja = $this->loginSocial();
        
        if($ja["status"] == 1){
          return $ja;
        }
       
        

        $infos = $_POST["infos"] ?? false;
        if(!$infos){
            return ["erro"=>true, "mensagem"=>"Não foram enviadas informações válidas"];
        }
        $array = json_decode($infos, true);
        $rede = "login_".$array["login"];
        $id = $array["id"];
        
        
        
        $nome = $array["nome"];
        $foto = json_encode($array["imagemURL"] ? [$array["imagemURL"]] : []);
        $email = $array["email"];
  
        
        
        
        $senha = md5($this->hasher());
        $hash = $this->hasher();
        $nomeprevo = str_replace(' ', '', strtolower(substr($nome, 0, 10))); 
        $username = $nomeprevo;
        while ($this->checkUsernameExists($username)) {
                $randomNumber = mt_rand(1, 9999);
                $username .= $nomeprevo.$randomNumber;
                if (strlen($username) > 15) {
                    $username = substr($username, 0, 15);
                }
            }
            
            
        $atividade = $this->v(["geral", "geral", "aprovacao"], false) ? 0 : 1;
        $novaFuncao = $this->pegaNovaFuncao();
        $cadastra = "INSERT INTO usuarios 
            (usuario_display ,usuario_email,  usuario_telefone, usuario_cpf,  usuario_user,  usuario_senha,  usuario_funcao,usuario_subfuncao,usuario_hash,usuario_ativo, usuario_foto) VALUES 
            ('$nome',   '$email', '',  '',  '$username',   '$senha',   '$novaFuncao' , '2', '$hash', '$atividade', '$foto')";

            if($this->conn->query($cadastra) == true){
                $id = $this->conn->insert_id;
                $trato = explode(" ", $nome, 2);
                $this->metaUser($id, "nome", $trato[0]);
                $this->metaUser($id, "sobrenome", $trato[1]);
                
                return $this->loginPorId($id);
                
            }
            
            
            
        
        
        return ["sucesso"=>true, "mensagem"=>"Não foi encontrado um usuário com as credenciais definidas", "status"=>0];
        
        
        
        
        
      
        
        
    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function hasherSimple($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function v($array, $alt = ""){
        $caminho = $this->setup;
        
        $ultimo = count($array) - 1;
        $i = 0;
        foreach($array as $item){
            if(isset($caminho[$item])){
                if($ultimo == $i){
                    if($caminho[$item] === "false"){
                        return false;
                    }
                    
                    if($caminho[$item] === "true"){
                        return true;
                    }
                    return $caminho[$item];
                }
                $caminho = $caminho[$item];
            }else{
                return $alt;
            }
            $i++;
        }
        
        return $alt;
}
    
    function configs(){
        $seleciona = "SELECT * FROM configuracoes";
        $resultado = $this->conn->query($seleciona);
        
        $resposta = [];
        while($dado = $resultado->fetch_assoc()){
            $pasta = $dado["config_pasta"];
            $arquivo = $dado["config_arquivo"];
            $chave = $dado["config_chave"];
            $valor = $dado["config_valor"];
            if(!array_key_exists($pasta , $resposta)){
                $resposta[$pasta] = [];
            }
            if(!array_key_exists($arquivo, $resposta[$pasta])){
                $resposta[$pasta][$arquivo] = [];
            }
            $resposta[$pasta][$arquivo][$chave] = $valor ? $valor : false;
}

        $this->setup = $resposta;

    }
    
    function loginQr(){

        if(!isset($_SESSION["id"])){
            return ["erro"=>true, "mensagem"=>"É preciso estar logado para proseguir"];
        }
        
        $this->hash = $_POST["hash"] ?? false;
        if(!$this->hash){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um código válido de login"];
        }
        
        $token = $this->hasher(60);
        $user = $_SESSION["id"];
        
        $deleta = "DELETE FROM tokenLogin WHERE token_user='$user'";
        $this->conn->query($deleta);
        
        $cadastra = "INSERT INTO tokenLogin (token_publico, token_user) VALUES ('$token', '$user')";
        if($this->conn->query($cadastra) == true){
            
            include_once __DIR__.'/sockets/websocket.php';
            
            $pusher = websocket();
            $data['message'] =  $token;
            $pusher->trigger("login-".$this->hash , 'login', $data);
            
            
            return ["sucesso"=>true, "token"=>$token];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao gerar Token de Segurança", "sql"=>$this->conn->error];
        }
    }
    
    function autoLogin(){
        $sessao = $_POST["sessao"] ?? false;
        if(!$sessao){
            return ["erro"=>true, "mensagem"=>"Sessão não enviada"];
        }
        
        $seleciona = "SELECT * FROM  sessoes WHERE sessao_hash='$sessao'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $id = $dado["sessao_usuario"];
            return $this->loginPorId($id, $sessao);
        }else{
           return ["erro"=>true, "mensagem"=>"Sessão não encontrada"];  
        }
    }
    
    function testeExistencia($chave, $valor){
        $seleciona = "SELECT * FROM usuarios WHERE ".$chave."='".$valor."'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }else{
            return true;
        }
    }
    
    function testeEmpresa($empresa){
        $seleciona = "SELECT * FROM empresas WHERE empresa_cnpj='$empresa'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        return true;
    }
    
    function metaUser($id, $chave, $valor){
        if(!$chave){
            return;
        }
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='$chave'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $acao = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$id', '$chave', '$valor')";
        }else{
            $acao = "UPDATE usuarios_meta set um_valor='$valor' WHERE um_usuario='$id' AND um_chave='$chave'";
        }
        
  
        $this->conn->query($acao);
    }
    
    function cadastro(){
        if(!$this->v(["geral","geral","cadastro"], false)){
            return ["erro"=>true, "mensagem"=>"O cadastro de novos usuários esta desabilitado"];
        }
        
        $campos = ["nome", "email"];
        
        if($this->v(["paginas","cadasro","cpf"], false)){
            array_push($campos, "cpf");
            
        }
         
        if($this->v(["paginas","cadastro","endereco"], false)){
            array_push($campos, "cep");
        }
        
        if($this->v(["paginas","cadastro","celular"], false)){
            array_push($campos, "celular");
        }
        
         
        
        if($this->v(["paginas","cadastro","empresa"], false)){
            array_push($campos, "cnpj");
        }
        
        $all = true;
        foreach($campos as $item){
            $this->$item = $_POST[$item] ?? false;
            if(!isset($_POST[$item]) || !$_POST[$item]){
                $all = false;
            }else{
                if($item != "nome"){
                    $this->$item = trim($this->$item, " ");
                }
            }
        }
        
        if($all){
        
            $nome = $_POST["nome"];
            $erros = [];
            $valido = true;
            if (!$nome || strlen($nome) < 10 || count(explode(" ", $nome)) < 2) {
                $valido = false;
                array_push($erros, "nome");
            } 
            
            $this->email = preg_replace('/\s+/', '', $this->email);
            $validador = new ValidaInfo($this->email, 'email');
            if(!$validador->valida()){
                $valido = false;
                array_push($erros, "email");
                $email = $this->email;
            }
            
            if(isset($this->celular) && $this->celular){
               $this->celular = preg_replace('/\s+/', '', $this->celular);
                $validador = new ValidaInfo($this->celular, 'telefone');
                if(!$validador->valida()){
                    $valido = false;
                    array_push($erros, "telefone");
                }
            }
            
            if(isset($this->cpf) && $this->cpf){
                $this->cpf = preg_replace('/\s+/', '', $this->cpf);
                $validador = new ValidaInfo($this->cpf, 'cpf');
                if(!$validador->valida()){
                    $valido = false;
                    array_push($erros, "cpf");
                }
            }
            
            if(isset($this->cnpj) && $this->cnpj){
                 $this->cnpj = preg_replace('/\s+/', '', $this->cnpj);
                $validador = new ValidaInfo($this->cnpj, 'cnpj');
                if(!$validador->valida()){
                    $valido = false;
                    array_push($erros, "cnpj");
                }
            }
         
            if(isset($this->cep) && $this->cep){
                $this->cep = preg_replace('/\s+/', '', $this->cep);
                $validador = new ValidaInfo($this->cep, 'cep');
                if(!$validador->valida()){
                    $valido = false;
                    array_push($erros, "cep");
                }
            }
            

            if(!$valido){
                return ["erro"=>true, "mensagem"=>"Alguma informação passada não é valida", "campos"=>implode(",", $erros)];
            }
            
            
           if($this->testeExistencia("usuario_email", $this->email)){
               return ["sucesso"=>true, "status"=>0, "mensagem"=>"E-mail em uso", "foco"=>"email"];
           }
           
           if($this->celular && $this->testeExistencia("usuario_telefone", $this->celular)){
               return ["sucesso"=>true, "status"=>0, "mensagem"=>"Telefone em uso","foco"=>"celular"];
           }
           
           if($this->cpf && $this->testeExistencia("usuario_cpf", $this->cpf)){
               return ["sucesso"=>true, "status"=>0, "mensagem"=>"CPF em uso", "foco"=>"cpf"];
           }
           
           if($this->cnpj && $this->testeEmpresa($this->cnpj)){
                return ["sucesso"=>true, "status"=>0, "mensagem"=>"Empresa já cadastrada", "foco"=>"cnpj"];
           }
           
           
            $nome = $this->nome;
            $email = $this->email;
            $cpf = $this->cpf ?? NULL;
            $celular = $this->celular ?? NULL;
            $senha = md5($this->hasher());
            $hash = $this->hasher();
            
            $nomeprevo = str_replace(' ', '', strtolower(substr($nome, 0, 10))); 
            $username = $nomeprevo;
            while ($this->checkUsernameExists($username)) {
                $randomNumber = mt_rand(1, 9999);
                $username .= $nomeprevo.$randomNumber;
                if (strlen($username) > 15) {
                    $username = substr($username, 0, 15);
                }
            }
            
            
            $atividade = $this->v(["geral", "geral", "aprovacao"], false) ? 0 : 1;
            $novaFuncao = $this->pegaNovaFuncao();
            $cadastra = "INSERT INTO usuarios 
            (usuario_display ,usuario_email,  usuario_telefone, usuario_cpf,  usuario_user,  usuario_senha,  usuario_funcao,usuario_subfuncao,usuario_hash,usuario_ativo) VALUES 
            ('$nome',         '$email',       '$celular',       '$cpf',       '$username',   '$senha',   '$novaFuncao' , 2, '$hash', '$atividade')";
            if($this->conn->query($cadastra) == true){
                $id = $this->conn->insert_id;
                $trato = explode(" ", $nome, 2);
                $this->metaUser($id, "nome", $trato[0]);
                $this->metaUser($id, "sobrenome", $trato[1]);
                
                $sessao = $this->loginPorId($id)["sessao"];
                
                $pass = false;
                if(!$this->v(["paginas","cadastro","desativarsenha"], false)){
                    $_SESSION["needPass"] = true;
                    $pass = true;
                }
                
                try{
                    if($this->cep && file_exists("../conteudo/modulos/enderecos/admins/endereco.php")) {
                       include_once __DIR__."/../conteudo/modulos/enderecos/admins/endereco.php";
                       $endereco = new Endereco($this->cep);
                       
                       
                       
                       if($_POST["complemento"] ?? false){
                          $endereco->setComplemento($_POST["complemento"]);
                       }
                       
                       if($_POST["numero"] ?? false){
                           $endereco->setNumero($_POST["numero"]);
                       }
                       
                       
                       $endereco->api();
                       $endereco->associa($id);
                       
                       
                       $idEndereco = $endereco->idcadastrado;
                       
                       if($idEndereco){
                           $update = "UPDATE usuarios SET usuario_endereco='{$idEndereco}' WHERE usuario_id='$id'";
                           $this->conn->query($update);
                       }
                        
                    }
                     
                    if($this->cnpj && file_exists(__DIR__."/../conteudo/modulos/empresas/admins/empresa.php")){
                        include_once __DIR__."/../conteudo/modulos/empresas/admins/empresa.php";
                        $empresa = new Empresa($this->cnpj);
                        $empresa->consulta();
                        $empresa->associa($id);
                      }
                    

                    
                }catch(Exception $e){
                   
                }
                
                
                $validacao = false;
                if($this->v(["paginas","cadastro","validaremail"], false) || $this->v(["paginas","cadastro","validartelefone"], false)){
                    $_SESSION["validaCredenciais"] = true;
                }
                
                
               

                
                
                return ["sucesso"=>true, "status"=>1, "mensagem"=>"Cadastro feito com sucesso", "sessao"=>$sessao, "senha"=>$pass];
            }else{
                return ["erro"=>true, "mensagem"=>"Não foi possível completar o cadastro"];
            }

        }else{
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os campos obrigatórios para cadastro"];
        }
        
        
        
        
    }
    
    function pegaNovaFuncao(){
        if(!$this->v(["geral","geral","novosusuarios"] , false)){
            return 2;
        }
        
        $numero = intval($this->v(["geral","geral","novosusuarios"] , 2));

    
        if($numero <= 0){
            $numero = 2;
        }
    
        return $numero;
    }

    function checkUsernameExists($username) {
    
    $conn = $this->conn; 

    $seleciona = "SELECT COUNT(*) FROM usuarios WHERE usuario_user = '$username'";
    $resultado = $conn->query($seleciona);
    $resultado->num_rows;
}

    function novaSenha(){

        $senha = $_POST["senha"] ?? false;
        if(!$senha){
            return ["erro"=>true, "mensagem"=>"A senha definida é inválida"];
        }
        
        $senha = trim(md5($senha), "");
        $id = $_SESSION["id"];
        $atualiza = "UPDATE usuarios SET usuario_senha='$senha' WHERE usuario_id='$id'";
        if($this->conn->query($atualiza) == true){
             $_SESSION["needPass"] = false;
            return ["sucesso"=>true, "mensagem"=>"Senha atualizada com sucesso", "sessao"=>$_SESSION["sessao_hash"]];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao atualizar a senha", "sql"=>$this->conn->error];
        }
    }
    
    function validarTipo($string) {
    // Regex para validar email
    $regexEmail = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/";
    // Regex para validar número de telefone (formato simples para fins de exemplo)
    // Considera formatos comuns como (xx) xxxx-xxxx ou xx xxxxx-xxxx
    $regexTelefone = "/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/";

    if (preg_match($regexEmail, $string)) {
        return "email";
    } elseif (preg_match($regexTelefone, $string)) {
        return "telefone";
    } else {
        return false;
    }
}

    function formatarTelefone($telefone) {
    // Removendo caracteres não numéricos
    $numeros = preg_replace('/\D/', '', $telefone);

    // Verificando o comprimento e formatando
    if (strlen($numeros) == 10) {
        // Formato de telefone fixo (xx)xxxx-xxxx
        $formatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1)$2-$3', $numeros);
    } elseif (strlen($numeros) == 11) {
        // Formato de telefone móvel (xx)xxxxx-xxxx
        $formatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $numeros);
    } else {
        // Retorna a string original se não corresponder a um telefone válido
        return "Formato de telefone inválido.";
    }

    return $formatado;
}
    
    function recuperacao($id = false){
        $valor = $_POST["valor"] ?? false;
        
        if(!$valor || !$this->validarTipo($valor)){
            if(!$id){
                return ["erro"=>true, "mensagem"=>"O valor definido não é valido"];
            }
        }
        
        
        
        if($this->validarTipo($valor) == "telefone"){
            $valor = $this->formatarTelefone($valor);
            
           $seleciona = "SELECT usuario_id FROM usuarios WHERE usuario_telefone='$valor' LIMIT 1";
            
        }else{
            $seleciona = "SELECT usuario_id FROM usuarios WHERE usuario_email='$valor' LIMIT 1";
        }
        
        if($id){
            $seleciona = "SELECT usuario_id FROM usuarios WHERE usuario_id='$id' LIMIT 1";
        }
        
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["sucesso"=>true, "resultados"=>0];
        }

        $publico = $this->hasher(32);
        $privado = $this->hasherSimple(6);
        
        $dado = $resultado->fetch_assoc();
        $id = $dado["usuario_id"];
        
        $deleta = "DELETE from tokenLogin WHERE token_user='$id'";
        $this->conn->query($deleta);
        
        $cadastra = "INSERT INTO tokenLogin (token_publico,token_privado,token_user,token_tipo) VALUES ('$publico', '$privado', '$id', 1)";
        $this->conn->query($cadastra);
        
        $notificacao = new Notificacao($id);
        $notificacao->mensagem(["header"=>"Recuperação de Senha", "body"=>'<p>Recebemos uma solicitação para redefinir a senha da sua conta. Para continuar com esse processo, use o código de verificação abaixo:</p>
            
            <div class="code-container">
                <div class="verification-code">'.$privado.'</div>
                <div class="expiry-notice">Este código expira em 5 minutos.</div>
            </div>
            <p>Se preferir, você também pode redefinir sua senha clicando diretamente no botão abaixo:</p>
            
        
            
            <div class="security-notice">
                <p><strong>Nota de segurança:</strong> Se você não solicitou esta recuperação de senha, por favor ignore este email ou entre em contato com nosso suporte imediatamente, pois alguém pode estar tentando acessar sua conta.</p>
            </div>
            
            ']);
        $notificacao->email();
        
        return ["sucesso"=>true, "resultados"=>1, "publico"=>$publico];
    }
    
    function validaToken(){
    $publico = strtolower($_POST["publico"]) ?? false;
    $privado = $_POST["privado"] ?? false;

    if(!$publico && !$privado){
        return ["erro"=>true, "mensagem"=>"Não foram enviados as chaves pública e privada"];
    }

    $seleciona = "SELECT * FROM tokenLogin WHERE token_publico='$publico' AND token_privado='$privado'";
    $resultado = $this->conn->query($seleciona);

    if($resultado->num_rows == 0){
        return ["sucesso"=>true, "status"=>0];
    }else{
        $dado = $resultado->fetch_assoc();
        $usuario = $dado["token_user"];
        $data = $dado["token_data"];


        $dataToken = strtotime($data);

        $agora = time();

        $diferencaMinutos = ($agora - $dataToken) / 60;

        if($diferencaMinutos > 5){
            return ["sucesso"=>true, "status"=>2, "novo"=>$this->recuperacao($usuario)];
        }else{
             $deleta = "DELETE from tokenLogin WHERE token_user='$usuario'";
             $this->conn->query($deleta);
            $this->loginPorId($usuario);
            return ["sucesso"=>true, "status"=>1, "usuario"=>$usuario];
        }
    }
}
    
    function acessarOutro() {
   
    /*
    if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
        return ["erro" => true, "mensagem" => "Acesso negado. Usuário não é administrador."];
    }
    */

    $id = $_POST["id"] ?? null;
    if (!$id) {
        return ["erro" => true, "mensagem" => "Não foram enviadas informações válidas"];
    }
    
    $seleciona = "SELECT usuario_id FROM usuarios WHERE usuario_hash='{$id}' LIMIT 1";
    $resultado = $this->conn->query($seleciona);
    if($resultado->num_rows == 0){
        return ["erro" => true, "mensagem" => "O usuário definido não existe"];
    }

    if ($id == $_SESSION["id"]) {
        return ["erro" => true, "mensagem" => "Você já está logado com esse usuário"];
    }
    $dado = $resultado->fetch_assoc();
    $id = $dado["usuario_id"];
    

    $_SESSION["adminAcess"] = [
        "id" => $_SESSION["id"],
        "nome" => $_SESSION["nome"],
        "foto" => $_SESSION["foto"]
    ];
    
    $novo = $this->loginPorId($id);
    if (!$novo) {
        return ["erro" => true, "mensagem" => "Falha ao realizar o login com o ID fornecido"];
    }
    
    return ["sucesso" => true, "sessao" => $novo];
}
    
    function backAdmin() {

    if (!isset($_SESSION["adminAcess"]) || !$_SESSION["adminAcess"]) {
        return ["erro" => true, "mensagem" => "Você não logou como administrador"];
    }

    $admin = $_SESSION["adminAcess"];
    $id = $admin["id"];
    
    $novo = $this->loginPorId($id);
    if (!$novo) {
        return ["erro" => true, "mensagem" => "Falha ao restaurar sessão do administrador"];
    }

    $_SESSION["adminAcess"] = false;
    
    return ["sucesso" => true, "sessao" => $novo];
}

    function fastCadastro(){
        
        if(!$this->v(["paginas","cadastro","ativarcadastrorapido"], false)){
            return ["erro"=>true, "mensagem"=>"A função está desativada no sistema", "go"=>"cadastro"];
        }
        
    
        
        
        $email = $_POST["email"] ?? false;
        
        if(!$email){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um e-mail válido", "go"=>"cadastro"];
        }
        
        $valido =  new ValidaInfo($email, 'email');
        
        if(!$valido){
             return ["erro"=>true, "mensagem"=>"Não foi enviado um e-mail válido", "go"=>"cadastro"];
        }
        
        
        $seleciona = "SELECT * FROM usuarios WHERE usuario_email='$email'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            return ["erro"=>true, "mensagem"=>"Usuário já cadastrado", "go"=>"acesso"];
        }
        
        
         $senha = md5($this->hasher());
         $hash = $this->hasher();
        
        $nomeprevo = str_replace(' ', '', strtolower(substr($email, 0, 10))); 
        $username = $nomeprevo;
            while ($this->checkUsernameExists($username)) {
                $randomNumber = mt_rand(1, 9999);
                $username .= $nomeprevo.$randomNumber;
                if (strlen($username) > 15) {
                    $username = substr($username, 0, 15);
                }
            }
            
            
            $atividade = $this->v(["geral", "geral", "aprovacao"], false) ? 0 : 1;
            $novaFuncao = $this->pegaNovaFuncao();
        
        $foto = '[]';
        
        $cadastra = "INSERT INTO usuarios 
            (usuario_display ,usuario_email,  usuario_user,  usuario_senha,  usuario_funcao,usuario_subfuncao,usuario_hash,usuario_ativo, usuario_foto) VALUES 
            ('$email',   '$email', '$username',   '$senha',   '$novaFuncao' , '2', '$hash', '$atividade', '$foto')";

            if($this->conn->query($cadastra) == true){
                $id = $this->conn->insert_id;
                
                $this->metaUser($id, "needPass", true);

        
                return $this->loginPorId($id);
                
            }
            
            
            
        
        return ["erro"=>true, "mensagem"=>"Não foi fazer o cadastro rápido", "go"=>"acesso"];
        
        
        
        
    }
    
    function subconta(){
        $id = $_POST["id"] ?? false;
        if(!$id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um ID válido"];
        }
        
        
        $tipo = v(["paginas","multi-contas", "tipo"], false);
        if(!$tipo){
            return ["erro"=>true, "mensagem"=>"Erro ao selecionar o tipo de conta"];
        }
        
        if(!file_exists(__DIR__."/../conteudo/modulos/".$tipo."/admins/multiconta/login.php")){
            return ["erro"=>true, "mensagem"=>"O arquivo de login de multiconta não foi criado"];
        }
        
        include __DIR__."/../conteudo/modulos/".$tipo."/admins/multiconta/login.php";
        if(!function_exists('selecionaConta')){
            return ["erro"=>true, "mensagem"=>"A função de seleção de conta não existe"];
        }
        
        return selecionaConta($this->conn, $id);
    }
    
    function render(){
        if(!$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma ação válida"];
        }
        
        $tentativas = $this->tentativas();

        if($tentativas > 5){
            return ["sucesso"=>true, "mensagem"=>"Usuário bloquedo por excesso de tentativas", "status"=>2, "block"=>$this->secureCheck()];
        }
        
        switch($this->acao){
            case 'login':
                return $this->login();
                break;
            case 'subconta':
                return $this->subconta();
                break;
            case 'loginQr':
                return $this->loginQr();
                break;
            case 'loginToken':
                return $this->tokenLogin();
                 break;
            case 'autoLogin':
                return $this->autoLogin();
                break;
            case 'loginSocial':
                return $this->loginSocial();
                break;
            case 'cadastroSocial':
                return $this->cadastroSocial();
                break;
            case 'cadastro':
                return $this->cadastro();
                break;
            case 'novaSenha':
                return $this->novaSenha();
                break;
            case '2fatores':
                break;
            case 'recuperacao':
                return $this->recuperacao();
                break;
            case 'secure':
                return ["sucesso"=>true, "secure"=>$this->secureCheck()];
                break;
            case 'validaToken':
                return $this->validaToken();
                break;
            case 'otherlogin':
                return $this->acessarOutro();
                break;
            case 'backAdmin':
                return $this->backAdmin();
                break;
            case 'fastCadastro':
                return $this->fastCadastro();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Nenhuma ação válida"];
                break;
        }
        
    }
}


$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE);


?>