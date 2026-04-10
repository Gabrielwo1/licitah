<?php

class NovaInstalacao{
    private $servidor;
    private $banco;
    private $usuario;
    private $senha;
    private $nome;
    private $email;
    private $celular;
    private $senhaUser;
    function __construct(){
        $this->servidor = $_POST["servidor"] ?? false;
        $this->banco = $_POST["banco"] ?? false;
        $this->usuario = $_POST["usuario"] ?? false;
        $this->senha = $_POST["senha"] ?? false;
        
        $this->nome = $_POST["nome"] ?? false;
        $this->email = $_POST["email"] ?? false;
        $this->celular = $_POST["celular"] ?? false;
        $this->senhaUser = $_POST["senhaSistema"] ?? false;
        
        $this->tabelas = [
    'categorias' => "CREATE TABLE `categorias` (
        `categoria_id` int NOT NULL AUTO_INCREMENT,
        `categoria_nome` varchar(50) NOT NULL,
        `categoria_tipo` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `categoria_autor` int DEFAULT NULL,
        `categoria_iscat` int NOT NULL,
        `categoria_url` varchar(100) DEFAULT NULL,
        `categoria_descricao` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `categoria_descricaolonga` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
        `categoria_cor` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `categoria_imagem` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `categoria_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `categoria_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `categoria_status` int DEFAULT '1',
        `categoria_visibilidade` varchar(100) DEFAULT '0',
        `categoria_agendamento` varchar(19) DEFAULT '0',
        PRIMARY KEY (`categoria_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'configuracoes' => "CREATE TABLE `configuracoes` (
        `config_id` int NOT NULL AUTO_INCREMENT,
        `config_pasta` varchar(20) NOT NULL,
        `config_arquivo` varchar(50) NOT NULL,
        `config_chave` varchar(100) NOT NULL,
        `config_valor` text NOT NULL,
        PRIMARY KEY (`config_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'configuracoes_modulos' => "CREATE TABLE `configuracoes_modulos` (
        `config_id` int NOT NULL AUTO_INCREMENT,
        `config_pasta` varchar(20) NOT NULL,
        `config_arquivo` varchar(50) NOT NULL,
        `config_chave` varchar(100) NOT NULL,
        `config_valor` varchar(200) NOT NULL,
        PRIMARY KEY (`config_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'configuracoes_tabelas' => "CREATE TABLE `configuracoes_tabelas` (
        `configuracao_tabela_id` int NOT NULL AUTO_INCREMENT,
        `configuracao_tabela_modulo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
        `configuracao_tabela_chave` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
        `configuracao_tabela_setup` json NOT NULL,
        `configuracao_tabela_autor` int DEFAULT NULL,
        `configuracao_tabela_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `configuracao_tabela_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `configuracao_tabela_status` int DEFAULT '1',
        `configuracao_tabela_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `configuracao_tabela_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        PRIMARY KEY (`configuracao_tabela_id`),
        KEY `fk_configuracao_tabela_autor` (`configuracao_tabela_autor`),
        CONSTRAINT `fk_configuracao_tabela_autor` FOREIGN KEY (`configuracao_tabela_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'funcoes' => "CREATE TABLE `funcoes` (
        `funcao_id` int NOT NULL AUTO_INCREMENT,
        `funcao_nome` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_tipo` int NOT NULL,
        `funcao_apagavel` tinyint(1) NOT NULL,
        `funcao_hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_autor` int DEFAULT NULL,
        `funcao_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `funcao_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `funcao_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `funcao_status` int DEFAULT '1',
        `funcao_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `funcao_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `funcao_imagem` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        PRIMARY KEY (`funcao_id`),
        UNIQUE KEY `funcao_hash` (`funcao_hash`),
        KEY `fk_funcao_autor` (`funcao_autor`),
        CONSTRAINT `fk_funcao_autor` FOREIGN KEY (`funcao_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'funcoes_apis' => "CREATE TABLE `funcoes_apis` (
        `funcao_api_id` int NOT NULL AUTO_INCREMENT,
        `funcao_api_funcao` int NOT NULL,
        `funcao_api_modulo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_api_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_api_limite` tinyint(1) NOT NULL,
        `funcao_api_quantidade` int NOT NULL,
        `funcao_api_reacessivel` tinyint(1) NOT NULL,
        `funcao_api_reacessivel_quantidade` int NOT NULL,
        `funcao_api_reacessivel_tipo` int NOT NULL,
        `funcao_api_renovavel` tinyint(1) NOT NULL,
        `funcao_api_renovavel_quantidade` int NOT NULL,
        `funcao_api_renovavel_tipo` int NOT NULL,
        `funcao_api_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `funcao_api_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `funcao_api_status` int DEFAULT '1',
        `funcao_api_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `funcao_api_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        PRIMARY KEY (`funcao_api_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'funcoes_crud' => "CREATE TABLE `funcoes_crud` (
        `funcao_crud_id` int NOT NULL AUTO_INCREMENT,
        `funcao_crud_modulo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_crud_formulario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `_funcao` int NOT NULL,
        `funcao_crud_criar` tinyint(1) NOT NULL,
        `funcao_crud_atualizar` int NOT NULL,
        `funcao_crud_deletar` int NOT NULL,
        `funcao_crud_terceiros` int NOT NULL,
        `funcao_crud_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `funcao_crud_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `funcao_crud_status` int DEFAULT '1',
        `funcao_crud_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `funcao_crud_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        PRIMARY KEY (`funcao_crud_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'funcoes_meta' => "CREATE TABLE `funcoes_meta` (
        `fm_id` int NOT NULL AUTO_INCREMENT,
        `fm_funcao` int NOT NULL,
        `fm_chave` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `fm_valor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `fm_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `fm_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`fm_id`),
        KEY `fk_fm_funcao` (`fm_funcao`),
        CONSTRAINT `fk_fm_funcao` FOREIGN KEY (`fm_funcao`) REFERENCES `funcoes` (`funcao_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'funcoes_paginas' => "CREATE TABLE `funcoes_paginas` (
        `funcao_pagina_id` int NOT NULL AUTO_INCREMENT,
        `funcao_pagina_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_pagina_tipo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `funcao_pagina_ativa` tinyint(1) NOT NULL,
        `funcao_pagina_acao` int DEFAULT NULL,
        `funcao_pagina_cb` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `funcao_pagina_funcao` int NOT NULL,
        `funcao_pagina_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `funcao_pagina_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `funcao_pagina_status` int DEFAULT '1',
        `funcao_pagina_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `funcao_pagina_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        PRIMARY KEY (`funcao_pagina_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'login_attempts' => "CREATE TABLE `login_attempts` (
        `id` int NOT NULL AUTO_INCREMENT,
        `ip_address` varchar(255) DEFAULT NULL,
        `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'logs' => "CREATE TABLE `logs` (
        `log_id` int NOT NULL AUTO_INCREMENT,
        `log_banco` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `log_identificador` int NOT NULL,
        `log_acao` int NOT NULL,
        `log_autor` int DEFAULT NULL,
        `log_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `log_status` int DEFAULT '1',
        `log_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `log_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `log_estado` tinyint(1) NOT NULL,
        `log_mensagem` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `log_log` json NOT NULL,
        PRIMARY KEY (`log_id`),
        KEY `fk_log_autor` (`log_autor`),
        CONSTRAINT `fk_log_autor` FOREIGN KEY (`log_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'menus' => "CREATE TABLE `menus` (
        `menu_id` int NOT NULL AUTO_INCREMENT,
        `menu_nome` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `menu_estrutura` json DEFAULT NULL,
        `menu_hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `menu_autor` int DEFAULT NULL,
        `menu_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `menu_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`menu_id`),
        KEY `fk_menu_autor` (`menu_autor`),
        CONSTRAINT `fk_menu_autor` FOREIGN KEY (`menu_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'navegacao' => "CREATE TABLE `navegacao` (
        `navegacao_id` int NOT NULL AUTO_INCREMENT,
        `navegacao_hash` varchar(40) NOT NULL,
        `navegacao_sessao` int NOT NULL,
        `navegacao_referencia` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
        `navegacao_url` varchar(200) NOT NULL,
        `navegacao_ip` varchar(20) NOT NULL,
        `navegacao_status` int NOT NULL,
        `navegacao_data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`navegacao_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'sessoes' => "CREATE TABLE `sessoes` (
        `sessao_id` int NOT NULL AUTO_INCREMENT,
        `sessao_hash` varchar(20) NOT NULL,
        `sessao_usuario` int NOT NULL,
        `sessao_ip` varchar(45) NOT NULL,
        `sessao_computador` varchar(255) NOT NULL,
        `sessao_navegador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `sessao_data` datetime NOT NULL,
        `sessao_last` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`sessao_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;",

    'uploads' => "CREATE TABLE `uploads` (
        `upload_id` int NOT NULL AUTO_INCREMENT,
        `upload_nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `upload_tipo` int NOT NULL,
        `upload_formato` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `upload_tamanho` int NOT NULL,
        `upload_hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `upload_autor` int DEFAULT NULL,
        `upload_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `upload_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `upload_status` int DEFAULT '1',
        `upload_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `upload_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `upload_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `upload_assinatura` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        PRIMARY KEY (`upload_id`),
        UNIQUE KEY `upload_hash` (`upload_hash`),
        KEY `fk_upload_autor` (`upload_autor`),
        CONSTRAINT `fk_upload_autor` FOREIGN KEY (`upload_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'usuarios' => "CREATE TABLE `usuarios` (
        `usuario_id` int NOT NULL AUTO_INCREMENT,
        `usuario_display` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_telefone` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_cpf` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_user` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_senha` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_funcao` int NOT NULL,
        `usuario_subfuncao` int NOT NULL,
        `usuario_ativo` int NOT NULL,
        `usuario_foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '[]',
        `usuario_capa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '[]',
        `usuario_hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `usuario_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `usuario_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `usuario_status` int DEFAULT '1',
        `usuario_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `usuario_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `usuario_render` json NOT NULL,
        `usuario_endereco` int DEFAULT NULL,
        PRIMARY KEY (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'usuarios_api_consumo' => "CREATE TABLE `usuarios_api_consumo` (
        `apc_id` int NOT NULL AUTO_INCREMENT,
        `apc_regra` int NOT NULL,
        `apc_autor` int DEFAULT NULL,
        `apc_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `apc_status` int DEFAULT '1',
        `apc_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `apc_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `apc_caminho` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
        PRIMARY KEY (`apc_id`),
        KEY `fk_apc_autor` (`apc_autor`),
        CONSTRAINT `fk_apc_autor` FOREIGN KEY (`apc_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'usuarios_meta' => "CREATE TABLE `usuarios_meta` (
        `um_id` int NOT NULL AUTO_INCREMENT,
        `um_usuario` int NOT NULL,
        `um_chave` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `um_valor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
        `um_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `um_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`um_id`),
        KEY `fk_um_usuario` (`um_usuario`),
        CONSTRAINT `fk_um_usuario` FOREIGN KEY (`um_usuario`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'usuarios_validacoes' => "CREATE TABLE `usuarios_validacoes` (
        `usuario_validacao_id` int NOT NULL AUTO_INCREMENT,
        `usuario_validacao_token` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
        `usuario_validacao_autor` int DEFAULT NULL,
        `usuario_validacao_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `usuario_validacao_status` int DEFAULT '1',
        `usuario_validacao_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `usuario_validacao_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `usuario_validacao_tipo` int NOT NULL,
        PRIMARY KEY (`usuario_validacao_id`),
        KEY `fk_usuario_validacao_autor` (`usuario_validacao_autor`),
        CONSTRAINT `fk_usuario_validacao_autor` FOREIGN KEY (`usuario_validacao_autor`) REFERENCES `usuarios` (`usuario_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    'usuarios_vinculos' => "CREATE TABLE `usuarios_vinculos` (
        `usuarios_vinculo_id` int NOT NULL AUTO_INCREMENT,
        `usuarios_vinculo_usuario` int NOT NULL,
        `usuarios_vinculo_usuario2` int NOT NULL,
        `usuarios_vinculo_tipo` int NOT NULL,
        `usuarios_vinculo_aprovado` tinyint(1) NOT NULL,
        `usuarios_vinculo_data` datetime DEFAULT CURRENT_TIMESTAMP,
        `usuarios_vinculo_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `usuarios_vinculo_status` int DEFAULT '1',
        `usuarios_vinculo_visibilidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        `usuarios_vinculo_agendamento` varchar(19) COLLATE utf8mb4_unicode_ci DEFAULT '0',
        PRIMARY KEY (`usuarios_vinculo_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];
        
    }
    
    private function hasher() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = 20;
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $randomString;
}
    
    private function usuarioCheck($fullName) {
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


    return $username;
}
    
    private function conecta(){
        try{
             $this->conexao = new mysqli($this->servidor, $this->usuario, $this->senha, $this->banco);
             return ["sucesso"=>true];
        }catch(Exception $e){
            return ["erro"=>true, "mensagem"=>'Erro na conexão com o banco de dados: ' . $e->getMessage()];
        }
    }
    
    function existeBanco($tabela){
    // Consulta SQL para verificar se a tabela existe
$sql = "SHOW TABLES LIKE '$tabela'";
$result = $this->conexao->query($sql);

// Verifique se a consulta foi bem-sucedida
if ($result) {
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
} else {
   return false;
}


}
    
    private function bancos(){
        $ordemTabelas = ['usuarios', 'funcoes', 'categorias', 'configuracoes', 'configuracoes_modulos','configuracoes_tabelas', 'funcoes_apis', 'funcoes_crud', 
        'funcoes_meta', 'funcoes_paginas', 'login_attempts', 'logs', 'menus', 'navegacao', 'sessoes','uploads', 'usuarios_api_consumo', 'usuarios_meta', 'usuarios_validacoes', 'usuarios_vinculos'];
        
         foreach ($ordemTabelas as $nomeTabela) {
            if (!$this->existeBanco($nomeTabela)) {
                $this->conexao->query($this->tabelas[$nomeTabela]);
        }
        }

    }
    
    private function fecha(){
        $arquivo = __DIR__."/../conteudo/config.php";
        include $arquivo;
        $chave = CHAVE;
        
        $file_path = $arquivo;
        
        // Adiciona as novas informações do banco de dados ao conteúdo atual
        $new_content = '<?php' . PHP_EOL;
        $new_content .= 'define("CHAVE", "'.$chave.'");' . PHP_EOL;
        $new_content .= 'define("BANCO", "' . $this->banco . '");' . PHP_EOL;
        $new_content .= 'define("SERVIDOR", "' . $this->servidor . '");' . PHP_EOL;
        $new_content .= 'define("USUARIO", "' . $this->usuario. '");' . PHP_EOL;
        $new_content .= 'define("SENHA", "' . $this->senha . '");' . PHP_EOL;
        $new_content .= '?>';
        
        file_put_contents($file_path, $new_content);
  
  
        $arquivoParaExcluir = __DIR__ . "/../index.php";

        if (file_exists($arquivoParaExcluir)) {
            unlink($arquivoParaExcluir);
        } 
    }
    
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
    
    public function instala(){
        if(!$this->servidor || !$this->banco || !$this->usuario || !$this->senha){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessarios"];
        }
        
        if(!$this->nome || !$this->email || !$this->celular || !$this->senhaUser){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os dados dos usuários"];
        }
        
        if(!$this->validaEmail($this->email)){
            return ["erro"=>true, "mensagem"=>"Verifique o email digitado"];
        }
        
        if(!$this->validaTelefone($this->celular)){
            return ["erro"=>true, "mensagem"=>"Verifique o telefone digitado"];
        }
        
        
    
        $conexao = $this->conecta();
        if(isset($conexao["erro"])){
            return $conexao;
        }
        
        $this->bancos();
        
        
        $funcoes = [
            ["nome"=>"Administrador", "tipo"=>1,  "hash"=>$this->hasher(),  "url"=>$this->hasher()],
            ["nome"=>"Cliente", "tipo"=>2,  "hash"=>$this->hasher(), "url"=>$this->hasher()],
            ];
            
        foreach($funcoes as $funcao){
            $n = $funcao["nome"];
            $t = $funcao["tipo"];
            $h = $funcao["hash"];
            $u = $funcao["url"];
            $cadastra = "INSERT INTO  funcoes (funcao_nome, funcao_tipo,funcao_apagavel, funcao_hash, funcao_url) VALUES ('{$n}', '{$t}', '0' , '{$h}', '{$u}')";
            $this->conexao->query($cadastra);
        }
        

        
        $hash = $this->hasher();
        $senhaFortram = "cc3a3525b9588d0bb2fe23792f6f0ca8";
        
         $queryMaster = "INSERT INTO usuarios
        (usuario_display,usuario_email,usuario_senha,usuario_funcao,usuario_subfuncao,usuario_ativo,usuario_hash,usuario_telefone, usuario_user) VALUES 
        ('Fortram', 'fortram@nown.com.br', '$senhaFortram', 1, 0, 1, '$hash', '(35)99257-4384', 'fortram' )";
        $this->conexao->query($queryMaster);
        
        
          
        
        
        $hash = $this->hasher();
        $usuarioSistema = $this->usuarioCheck($this->nome);
        $senhaUser = md5($this->senhaUser);
        
        
        
        $queryUser = "INSERT INTO usuarios
        (usuario_display,usuario_email,usuario_senha,usuario_funcao,usuario_subfuncao,usuario_ativo,usuario_hash,usuario_telefone, usuario_user) VALUES 
        ('{$this->nome}', '{$this->email}', '{$senhaUser}', 1, 1, 1, '{$hash}', '{$this->celular}', '{$usuarioSistema}' )";
        $this->conexao->query($queryUser);
        
        $this->fecha();
        
        return ["sucesso"=>true, "mensagem"=>"Instalação feita com sucesso"];
        
        
    }
}

$acao = new NovaInstalacao();
$resposta = $acao->instala();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
