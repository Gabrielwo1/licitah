<?
include 'secure.php';

include 'conn.php';
include 'installDb.php';


class Acao{
    public $acao;
    public $modulo;
    public $valor;
    public $dir;
    
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->valor = $_POST["valor"] ?? false;
        $this->dir = __DIR__ . "/../conteudo/modulos/";
    }

    function ativa(){
        if ($this->modulo){
            $dir = $this->dir;
            if (file_exists($dir . $this->modulo . "/manifest.json")){
                $conteudo = file_get_contents($dir . $this->modulo . "/manifest.json") ? json_decode(file_get_contents($dir . $this->modulo . "/manifest.json") , true) : [];
                $conteudo["ativo"] = $this->valor;
                $json = json_encode($conteudo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
                file_put_contents($dir . $this->modulo . "/manifest.json", $json);
                
                if($this->valor == true){
                    
                }
                
                
                
                return ["sucesso"=>true, "mensagem"=>"Môdulo Alterado com Sucesso"];
            }else{
                return ["erro"=>true, "mensagem"=>"O Môdulo enviado não foi encontrado."];
            }

        }
        else{
            return ["erro" => true, "mensagem" => "Modulo indefinido"];
        }
    }
    
    function pegaChave(){
        if (file_exists(__DIR__ . "/../conteudo/config.php")){
            include __DIR__ . "/../conteudo/config.php";

            if (CHAVE){
                return CHAVE;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        return false;
    }
    
    function licenca(){
        $chave = $this->pegaChave();

        $postData = array(
            'modulo' => 'sistema',
            'chave' => $chave,
            'acao' => "valida"
        );


        $url = 'https://install.nown.com.br';

        $ch = curl_init($url);

   
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $referer = 'https://' . $_SERVER['HTTP_HOST'];
        curl_setopt($ch, CURLOPT_REFERER, $referer);

  
        $response = curl_exec($ch);
 


        if (curl_errno($ch)){
            echo 'Erro ao enviar a solicitação: ' . curl_error($ch);
        }

        curl_close($ch);
        

        $resposta = json_decode($response, true);

        if (isset($resposta["erro"]) && $resposta["mensagem"] == 1){
            $modulos = scandir($this->dir);
            foreach ($modulos as $item){
                if ($item != "." && $item != ".." && is_dir($this->dir . $item)){
                    $this->modulo = $item;
                    $this->valor = false;
                    $this->ativa();
                }
            }
        }
        
    

        $resposta["meus"] = $this->pegaModulos();
        $resposta["sistema"] = json_decode(file_get_contents(__DIR__ . "/manifest.json") , true);

        return $resposta;
    }

    function pegaModulos(){
        $dir = __DIR__ . "/../conteudo/modulos";
        $meus = scandir($dir);
        $retorno = [];
        foreach ($meus as $m){
            if (is_dir($dir . "/" . $m) && $m != "." && $m != ".."){
                if (file_exists($dir . "/" . $m . "/manifest.json")){
                    $conteudo = json_decode(file_get_contents($dir . "/" . $m . "/manifest.json") , true);
                }else{
                    $conteudo = ["name" => $m];
                }
                $conteudo["modulo"] = $m;
                if (file_exists($dir . "/" . $m . "/" . $m . ".svg")){
                    $conteudo["icone"] = true;
                }else
                {
                    $conteudo["icone"] = false;
                }

                array_push($retorno, $conteudo);
            }
        }
        return $retorno;
    }

    function donwload(){
        if (isset($_POST["modulo"])){
            $modulo = $_POST["modulo"];
            
            $chave =    $chave = $this->pegaChave();
            if($chave){
                $postData = array(
                    'modulo' => $modulo,
                    'chave' => $chave,
                    'acao' => "donwload"
                );

                $url = 'https://install.nown.com.br';

                $ch = curl_init($url);

                // Configurar as opções do cURL
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $referer = 'https://' . $_SERVER['HTTP_HOST'];
                curl_setopt($ch, CURLOPT_REFERER, $referer);

                // Executar a solicitação e obter a resposta
                $response = curl_exec($ch);
                


                // Verificar por erros
                if (curl_errno($ch)){
                    return ["erro"=>true, "mensagem"=>"Erro ao enviar a solicitação",  "curl"=>curl_error($ch)];
                }

                // Fechar a sessão cURL
                curl_close($ch);

                $res = json_decode($response, true);
                if ($res["modulo"]){
                    $randomId = uniqid();
                    $zipURL = "https://nown.com.br/" . $res["modulo"];
                    $zipPath = __DIR__ . "/../conteudo/provisorio/" . $randomId . ".zip";
                    $extractPath = $_POST["modulo"] === "1" ? __DIR__ . "/../" : __DIR__ . "/../conteudo/modulos/";

                    if (file_put_contents($zipPath, file_get_contents($zipURL))){

                        $zip = new ZipArchive;

                        if ($zip->open($zipPath) === true){
                            $zip->extractTo($extractPath);
                            $zip->close();
                            unlink($zipPath);

                          
                            $this->modulo = $_POST["nomeModulo"] ?? false;
                            $this->valor = "true";
                            $this->ativa();
                            
                            $this->bancoDados();
                            
                            $dir = __DIR__ . "/../conteudo/modulos";
                            
                            
                            if($this->modulo == "1"){
                                 $manifesto = false;
                            }else{
                                if(file_exists($dir . "/" . $this->modulo . "/manifest.json")){
                                     $manifesto = json_decode(file_get_contents($dir . "/" . $this->modulo . "/manifest.json") , true);
                                }else{
                                    $manifesto = false;
                                }
                                
                               
                            }
                            
                            return ["sucesso" => true, "mensagem" => "Arquivo ZIP baixado e descompactado com sucesso!", "versao" => $res["versao"], "manifest"=>$manifesto];
                        }
                        else{
                            return ["erro" => true, "mensagem" => "Erro ao abrir o arquivo ZIP."];
                        }
                    }
                    else{
                        return ["erro" => true, "mensagem" => "Erro ao baixar o arquivo ZIP da URL."];
                    }
                }
                else{
                    return ["erro"=>true, "Mensagem"=>"Môdulo não encontrado"];
                }
            }else{
                return ["erro"=>true, "Mensagem"=>"Chave inválida"];
            }
            
            
            
        }
        else{
            return ["erro" => true, "mensagem" => "Módulo Indefinido"];
        }
    }
    
    function apagarFilhos($caminho){
         $arquivos = scandir($caminho);
          foreach ($arquivos as $arquivo) {
                if ($arquivo != "." && $arquivo != "..") {
                    if (is_dir($caminho . "/" . $arquivo)) {
                        $this->apagarFilhos($caminho . "/" . $arquivo);
                    } else {
                        unlink($caminho."/". $arquivo);
                    }
                }
            }
          rmdir($caminho);
    }
    
    function apagar(){
    if ($this->modulo) {
        $dir = $this->dir;
        if (is_dir($dir . $this->modulo) && is_writable($dir . $this->modulo)) {
             $arquivos = scandir($dir . $this->modulo);
            foreach ($arquivos as $arquivo) {
                if ($arquivo != "." && $arquivo != "..") {
                    if (is_dir($dir . $this->modulo . "/" . $arquivo)) {
                        $this->apagarFilhos($dir.$this->modulo."/". $arquivo);
                    } else {
                        unlink($dir . $this->modulo."/". $arquivo);
                    }
                }
            }
            if (rmdir($dir . $this->modulo)) {
                return ["sucesso" => true, "mensagem" => "Môdulo Deletado com Sucesso"];
            } else {
                return ["erro" => true, "mensagem" => "Falha ao apagar môdulo", "caminho" => $dir . $this->modulo];
            }
        } else {
            return ["erro" => true, "mensagem" => "Módulo não encontrado"];
        }
    } else {
        return ["erro" => true, "mensagem" => "Defina um Môdulo válido"];
    }
}

    function instala($nomeTabela, $query, $update = false){
    $conexao = conn();
    $sql = "SHOW TABLES LIKE '$nomeTabela'";
    $resultado = $conexao->query($sql);

    if($resultado->num_rows == 0) {
        $conexao->query($query);
    }else{
        if($update){
            
             $sql = "DESCRIBE $nomeTabela";
             $resultado = $conexao->query($sql);
             $colunasExistentes = [];
             while ($row = $resultado->fetch_assoc()) {
                 $colunasExistentes[$row['Field']] = $row['Type'];
             }
            
            $colunasFaltantes  = [];
            foreach($update as $up){
                $trato = explode(" ", $up);
                if(!isset($colunasExistentes[$trato[0]])){
                    $sql = "ALTER TABLE $nomeTabela ADD COLUMN $up";
                    $conexao->query($sql);
                }
            }
        }
    }

    $conexao->close();
}

    function bancoDados(){
          $dir = __DIR__ . "/../conteudo/modulos";
          $modulo =  $_POST["nomeModulo"] ?? false;
          
          if(is_dir($dir."/".$modulo."/admins/configs")){
              $arquivos = scandir($dir."/".$modulo."/admins/configs");
              foreach($arquivos as $arquivo){
                    if($arquivo != "." && $arquivo != ".."){
                      try{
                      $install = new Install($dir."/".$modulo."/admins/configs/".$arquivo);
                      $principal = $install->principal();
                      $meta = $install->meta();
                      
                      if($principal){
                          $this->instala($install->banco, $principal, $install->update());
                      }
                      
                      if($meta){
                           $this->instala($install->banco."_meta", $meta);
                      }
                  }catch (Exception $e){
                      
                  }
                  }
                  
                  
                  
                
              }

          }
          
    }
    
    function dependencias($modulo = false){
        if(!$modulo){
            $modulo = $_POST["modulo"] ?? false;
        }
        
        if(!$modulo){
            return ["erro"=>true, "mensagem"=>"Módulo indefinido"];
        }
        
        $_POST["nomeModulo"] = $modulo;
        $this->bancoDados();
        
        return ["sucesso"=>true, "mensagem"=>"Banco de Dados Instalado com Sucesso"];
        
        
    }
    
    function listaMeus(){
        $lista = [];
        
        $caminho = __DIR__."/../conteudo/modulos/";
        $modulos = scandir($caminho);
        foreach($modulos as $modulo){
            if($modulo != "." && $modulo != ".."){
                if(file_exists($caminho.$modulo."/manifest.json")){
                    $conteudo = json_decode(file_get_contents($caminho.$modulo."/manifest.json"), true);

                    $lista[] = ["v"=>$modulo , "t"=>$conteudo["name"]];


                }
            }
        }
        return ["sucesso"=>true, "lista"=>$lista];

        
    }
    
    function listaForms(){
        $valor = $_POST["valor"] ?? false;
        $lista = [];
        if(!$valor){
            return ["sucesso"=>true, $lista];
        }
        
        $caminho = __DIR__."/../conteudo/modulos/".$valor;
        if(!is_dir($caminho) || !is_dir($caminho."/admins/") || !is_dir($caminho."/admins/configs")){
            return ["sucesso"=>true, $lista];
        }
        
        
        $forms = scandir($caminho."/admins/configs");
        
        foreach($forms as $form){
            if($form != "." && $form != ".." && $form){
                $conteudo = json_decode(file_get_contents($caminho."/admins/configs/".$form), true);
                 $lista[] = ["v"=>explode(".", $form)[0] , "t"=>$conteudo["banco"] ?? ""];
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista];

    }
    
    function massaTudo($ativo){
        $dir = $this->dir;
        $diretorios = scandir($this->dir);
        foreach($diretorios as $d){
            if($d != "." && $d != ".." ){
                if (file_exists($dir . $d . "/manifest.json")){
                    $conteudo = file_get_contents($dir . $d . "/manifest.json") ? json_decode(file_get_contents($dir . $d . "/manifest.json") , true) : [];
                    $conteudo["ativo"] = $ativo;
                    $json = json_encode($conteudo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
                    file_put_contents($dir . $d . "/manifest.json", $json);
                }
            }
        }
        return ["sucesso"=>true, "mensagem"=>"Ação em massa feita com sucesso"];
    }
    
    function render(){
        switch ($this->acao)
        {
            case 'ativador':
                return $this->ativa();
            break;
            case 'verificaLicenca':
                return $this->licenca();
            break;
            case 'baixar':
                return $this->donwload();
            break;
            case 'apagar':
                return $this->apagar();
                break;
            case 'instalarDependencias':
                return $this->dependencias();
                break;
            case 'listarMeus':
                return $this->listaMeus();
                break;
            case 'formModulo':
                return $this->listaForms();
                break;
            case 'ativaTudo':
                return $this->massaTudo("true");
                break;
            case 'desativaTudo':
                return $this->massaTudo("false");
                break;
            default:
                return ["erro" => true, "mensagem" => "Ação inválida"];
            break;
        }
    }
}



$acao = new Acao();
echo json_encode($acao->render(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
