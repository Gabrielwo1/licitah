<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/onlyapi.php";

include __DIR__."/../../../../admin/conn.php";

class Acao{
    public $acao;
    public $foco;
    public $usuario;
    public $conn;
    public $colunas;
    public $estrangeiras;
    public $queryPrincipal;

    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->foco = $_POST["foco"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
        $this->conn = conn();
        /*
        fazer fluxo de edição de terceiro, que pega o hash e faz um id
        */
    }
    
    function minificarPHP($php) {
        $search = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s'
        );
        
        $replace = array('>', '<', '\\1');
        $minificado = preg_replace($search, $replace, $php);
        return $minificado;
    }
    
    function html(){
        $arquivo = __DIR__."/../content/".$this->foco.".php";
        if(file_exists($arquivo)){
             ob_start();
             include($arquivo);
             $html = $this->minificarPHP(ob_get_clean());
        }else{
            $html = false;
        }
        return ["sucesso"=>true, "html"=>$html];
    }
    
    function fotos(){
        $perfil = $_POST["perfil"] ?? NULL;
        $capa = $_POST["capa"] ?? NULL;
        
       $ftcapa = $capa == "false" ? NULL : json_encode([$capa]);
       $ftperfil = $perfil == "false" ? NULL : json_encode([$perfil]);
        
        $id = $this->usuario;
        if($capa){
           $capa = addslashes($capa);
        }
        
        if($perfil){
            $perfil = addslashes($perfil);
        }
        $atualiza = "UPDATE usuarios SET usuario_foto='$ftperfil', usuario_capa='$ftcapa' WHERE usuario_id='$id'";
        if($this->conn->query($atualiza) == true){
            if($_SESSION["id"] == $this->usuario){
                if($perfil){
                    
                    $_SESSION["foto"] = $perfil;
                }
                
                if($capa){
                     $_SESSION["capa"] = $capa;
                }
            }
            
            
             return ["sucesso"=>true, "mensagem"=>"Perfil Atualizado com Sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Erro ao atualizar foto de perfil", "sql"=>$this->conn->error];
        }
    }
    
   function pegaGrupos($id){
    $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id'";
    $resultado = $this->conn->query($seleciona);
    $infos = [];
    if($resultado && $resultado->num_rows > 0){ 
         while($dado = $resultado->fetch_assoc()){
             $infos[$dado["um_chave"]] = $dado["um_valor"];
         }
      
    }
    return $infos;
}

    function infos(){
        $id = $this->usuario;
        $seleciona = "SELECT * FROM usuarios WHERE usuario_id='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $usuario = [
                "display"=>$dado["usuario_display"],
                "email"=>$dado["usuario_email"],
                "funcao"=>$dado["usuario_funcao"],
                "subfuncao"=>$dado["usuario_subfuncao"],
                "ativo"=>$dado["usuario_ativo"],
                "telefone"=>$dado["usuario_telefone"],
                "cpf"=>$dado["usuario_cpf"],
                "usuario"=>$dado["usuario_user"],
                "foto"=>$dado["usuario_foto"],
                "capa"=>$dado["usuario_capa"],
                "grupos"=>$this->pegaGrupos($id)
                ];
            return ["sucesso"=>true, "usuario"=>$usuario];
            
        }
        return ["erro"=>true, "mensagem"=>"Usuário não existe"];
    }
    
    function vital(){
        $foco = $_POST["item"] ?? false;
        $novo = $_POST["novo"] ?? false;
        
        if(!$foco || !$novo){
            return ["erro"=>true, "mensagem"=>"É necessario enviar um foco e string válidos"];
        }
        
        switch($foco){
            case "email":
                $seleciona = "SELECT * FROM usuarios WHERE usuario_email='$novo'";
                break;
            case "cpf":
                $seleciona = "SELECT * FROM usuarios WHERE usuario_cpf='$novo'";
                break;
            case "usuario":
                $seleciona = "SELECT * FROM usuarios WHERE usuario_user='$novo'";
                break;
            case "telefone":
                $seleciona = "SELECT * FROM usuarios WHERE usuario_telefone='$novo'";
                break;
            default:
                return  ["erro"=>true, "mensagem"=>"É necessario enviar um foco válido"];
                break;
        }
        
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            $id = $this->usuario;
             switch($foco){
            case "email":
                $atualiza = "UPDATE usuarios set usuario_email='$novo' WHERE usuario_id='$id'";
                break;
            case "cpf":
                $atualiza = "UPDATE usuarios set usuario_cpf='$novo' WHERE usuario_id='$id'";
                break;
            case "usuario":
                $atualiza = "UPDATE usuarios set usuario_user='$novo' WHERE usuario_id='$id'";
                break;
            case "telefone":
                $atualiza = "UPDATE usuarios set usuario_telefone='$novo' WHERE usuario_id='$id'";
                break;
             }
             
             if($this->conn->query($atualiza) == true){
                 $chave = "update_".$foco;
                 $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='$chave'";
                 $resultado = $this->conn->query($seleciona);
                 $agora = date("Y-m-d H:i:s");
                 if($resultado->num_rows == 0){
                     $acao = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$id', '$chave', '$agora')";
                 }else{
                     $acao = "UPDATE usuarios_meta set um_valor='$agora' WHERE um_usuario='$id' AND um_chave='$chave'";
                 }
                 
                 $this->conn->query($acao);
                 
                 
                 return ["sucesso"=>true,  "mensagem"=>"Informações trocadas com sucesso", "status"=>1]; 
             }else{
                return ["erro"=>true,  "mensagem"=>"Não foi possível salvar as informações", "sql"=>$this->conn->error]; 
             }
        }else{
            return ["sucesso"=>true, "status"=>"0", "mensagem"=>"Já existe um usuário com essas informações"];
        }
        
    }
    
    function hasher($length = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
        
    }
    
    function novoEndereco(){
        if(!$_POST["info"]){
            return ["erro"=>true, "mensagem"=>"Não foi enviado informações de cadastro"];
        }
        
        $info = json_decode($_POST["info"], true);
        
        $nome = $info["nome"];
        $cep = $info["cep"];
        $endereco = $info["endereco"];
        $numero = $info["numero"];
        $complemento = $info["complemento"];
        $cidade = $info["cidade"];
        $estado = $info["estado"];
        $bairro = $info["bairro"];
        $descricao = $info["descricao"];
        $autor = $this->usuario;
        $hash = $this->hasher();
        
        
        $seleciona = "SELECT * FROM enderecos WHERE endereco_autor='$autor'";
        $resultado = $this->conn->query($seleciona);

        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if($dado["endereco_nome"] == $nome){
                    return ["sucesso"=>true, "status"=>0, "log"=>1];
                }
                
                if($dado["endereco_cep"] == $cep && $dado["endereco_numero"] == $numero){
                    return ["sucesso"=>true, "status"=>0, "log"=>2];
                }
                
            }
        }
        
        $cadastra = "INSERT INTO enderecos 
        (endereco_nome,endereco_cep,endereco_rua,endereco_numero,endereco_complemento,endereco_bairro,endereco_cidade,endereco_uf,endereco_descricao,endereco_hash,endereco_autor) VALUES 
        ('$nome', '$cep', '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$descricao', '$hash', $autor)";
        if($this->conn->query($cadastra) == true){
             return ["sucesso"=>true, "mensagem"=>"O endereço foi cadastrado com sucesso", "id"=>$this->conn->insert_id, "status"=>1];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível cadastrar o endereço", "sql"=>$this->conn->error];
        }
        	
 
        
    }
    
    function listaEnderecos(){
        $lista = [];
        $autor = $this->usuario;
        $seleciona = "SELECT * FROM enderecos WHERE endereco_autor='$autor'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $item = [
                    "id"=>$dado["endereco_id"],
                    "nome"=>$dado["endereco_nome"],
                    "cep"=>$dado["endereco_cep"],
                    "rua"=>$dado["endereco_rua"],
                    "numero"=>$dado["endereco_numero"],
                    "complemento"=>$dado["endereco_complemento"],
                    "bairro"=>$dado["endereco_bairro"],
                    "cidade"=>$dado["endereco_cidade"],
                    "uf"=>$dado["endereco_uf"],
                    "descricao"=>$dado["endereco_descricao"],
                    "hash"=>$dado["endereco_hash"]
                    ];
                array_push($lista, $item);
            }
        }
        
        
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function blocoInfos(){
        $usuario = $this->usuario;
        $infos = [];
        $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$usuario'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $infos[$dado["um_chave"]] = $dado["um_valor"];
            }
        }
        return $infos;
    }
    
    function infoPerfil(){
        $infos = $this->blocoInfos();
        
        
        $array = [
            "nome",
            "sobrenome",
            "sobre",
            "genero",
            "outro-genero",
            "aniversario"
            ];
        $trato = [];
        
        foreach($array as $item){
            $trato[$item] = isset($infos[$item]) ? $infos[$item] : false;
        }
        
        return ["sucesso"=>true, "campos"=>$trato];
        
    }
    
    function salva(){
        $infos = $_POST["infos"] ?? false;
        
        $obj = json_decode($infos, true);
        $usuario = $this->usuario;
        
        foreach($obj as $chave=>$valor){
            $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$usuario' AND um_chave='$chave'";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 0){
                $acao = "INSERT INTO usuarios_meta (um_usuario, um_chave, um_valor) VALUES ('$usuario', '$chave', '$valor')";   
            }else{
                $dado = $resultado->fetch_assoc();
                $id = $dado["um_id"];
                $acao = "UPDATE usuarios_meta SET um_valor='$valor' WHERE um_id='$id'";
            }
            $this->conn->query($acao);
        }
        
        return ["sucesso"=>true];

    }
    
    function senha(){
        $senha = $_POST["senha"] ?? false;
        if(!$senha){
            return ["erro"=>true,  "mensagem"=>"Não foi enviada uma senha válida"];
        }
        $usuario = $this->usuario;
        $senha = md5($senha);
        $update = "UPDATE usuarios SET usuario_senha='$senha' WHERE usuario_id='$usuario'";
        if($this->conn->query($update) == true){
            return ["sucesso"=>true, "mensagem"=>"Senha alterada com sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível trocar a senha"];
        }
        
    }
    
    function render(){
        if(!$this->usuario){
            return ["erro"=>true, "mensagem"=>"É preciso estar logado para acessar a funcionalidade"];
        }
        
        if(!$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foi definida uma ação "];
        }
        
        switch($this->acao){
            case 'infos':
                return $this->infos();
                break;
            case 'fotos': 
                return $this->fotos();
                break;
            case 'senha':
                return $this->senha();
                break;
            case 'html':
                if(!$this->foco){
                    return ["erro"=>true, "mensagem"=>"Não foi definida um foco válido"];
                }
                return $this->html();
                break;
            case 'vital':
                return $this->vital();
                break;
            case 'novoEndereco':
                return $this->novoEndereco();
                break;
            case 'listaEnderecos':
                return $this->listaEnderecos();
                break;
            case 'infoPerfil':
                return $this->infoPerfil();
                break;
            case 'salvar':
                return $this->salva();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi definida uma ação válida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();


echo json_encode($resposta);

?>