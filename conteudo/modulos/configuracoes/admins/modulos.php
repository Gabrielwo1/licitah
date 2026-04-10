<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/config.php";
include __DIR__."/css.php";

class Logos {
    public $mode = [];
    public $setup = [];  

    public function __construct($setup) {
        $this->setup = $setup;
        $this->mode = [
            'light' => [],
            'dark' => []
        ];
        
        $chaves = ['light', 'dark'];
        $variacoes = ['logo', 'logomarca', 'logotipo'];

        foreach ($chaves as $chave) {
            foreach ($variacoes as $variacao) {
                $this->mode[$chave][$variacao] = $this->v(['geral', 'logotipo', $variacao], false);
            }
        }

        foreach ($chaves as $chave) {
            foreach ($variacoes as $variacao) {
                $headerData = $this->v(['header', "{$chave}-mode", $variacao], false);
                if ($headerData) {
                    $obj = json_decode($headerData, true);
                    if (count($obj) > 0) {
                        $this->mode[$chave][$variacao] = $headerData;
                    }
                }
            }
        }

        foreach ($chaves as $chave) {
            foreach ($variacoes as $variacao) {
                if ($this->mode[$chave][$variacao]) {
                    $obj = json_decode($this->mode[$chave][$variacao], true);
                    if (count($obj) == 0) {
                        $this->mode[$chave][$variacao] = false;
                    } else {
                        $this->mode[$chave][$variacao] = $this->trataImagem($obj[0], "media");
                    }
                }
            }
        }
    }
    
    private function v($array, $alt = ""){
        $caminho = $this->setup;

        
        $ultimo = count($array) - 1;
        $i = 0;
        foreach ($array as $item) {
            if (isset($caminho[$item])) {
                if ($ultimo == $i) {
                    switch ($caminho[$item]) {
                        case 'false':
                            return false;
                        case 'true':
                            return true;
                        default:
                            return $caminho[$item]; 
                    }
                }
                $caminho = $caminho[$item];
            } else {
                return $alt;
            }
            $i++;
        }
        
        return $alt;
    }

    private function trataImagem($imagem, $otimiza = false) {
        if (!$imagem || $imagem == "[]") {
            return false;
        }
        return SETUP["dominio"]."conteudo/uploads/".$imagem;

        try {
            $array = json_decode($imagem, true);
            $imagem = $array[0] ?? null;

            if (!$imagem || !str_starts_with($imagem, "imagens/")) {
                return $imagem;
            }

            if ($otimiza && $imagem) {
                $trato = explode("/", $imagem);
                array_pop($trato);
                $trato[] = "{$otimiza}.webp";
                $imagem = implode("/", $trato);
            }

            return "http://dominio/conteudo/uploads/{$imagem}";
        } catch (Exception $e) {

            return $imagem;
        }
    }
    
    public function imagens() {
        return $this->mode;
    }
}

class Acao{
    public $acao;
    public $tipo;
    public $foco;
    public $grupo;
    public $conn;
    public $dir;

 
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
        $this->foco = $_POST["foco"] ?? false;
        $this->grupo = $_POST["grupo"] ?? false;
        $this->conn = conn();
        $this->dir = __DIR__."/../../";
    }
    
    function stringParaURL($string) {
   
    $url = strtolower(trim($string));
    $url = str_replace(' ', '-', $url);

    $caracteres_especiais = array(
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n',
        'ß' => 'ss',
    );

    $url = strtr($url, $caracteres_especiais);


    $url = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $url);

 
    $url = preg_replace('/\s+/', '-', $url);

    $url = preg_replace('/-+/', '-', $url);
    $url = rtrim($url, '-');

    return $url;
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

    function pegaRespostas(){
          $foco = $this->stringParaURL($this->foco);
        $grupo = $this->stringParaURL($this->grupo);
        
         switch(intval($this->tipo)){
            case 1:
                $banco = "configuracoes";
                break;
            case 2:
                $banco = "configuracoes_modulos";
                break;
            case 3:
                $banco = "configuracoes_wirecard";
                
                
                $wirecard = $_POST["wirecard"] ?? false;
                if(!$wirecard){
                    return ["erro"=>true, "mensagem"=>"Não foi definido um cliente válido"];
                }
                
                $seleciona = "SELECT * FROM wildcards_contas WHERE wc_hash='$wirecard'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    return ["erro"=>true, "mensagem"=>"Cliente não encontrado"];
                }
                
                $dado = $resultado->fetch_assoc();
                $conta = $dado["wc_id"];
                break;
        }
    
      
        if(intval($this->tipo) == 3){
            $seleciona = "SELECT * FROM $banco WHERE config_pasta='$foco' AND config_arquivo='$grupo' AND config_conta='$conta'";
        }else{
            $seleciona = "SELECT * FROM $banco WHERE config_pasta='$foco' AND config_arquivo='$grupo'";
        }
        
        $resultado = $this->conn->query($seleciona);
        $respostas = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $respostas[$dado["config_chave"]] = $dado["config_valor"];
            }
        }
        return $respostas;
    }
    
    function html(){
        $grupo = $this->stringParaURL($this->grupo);
        $foco = $this->stringParaURL($this->foco);
        
        switch(intval($this->tipo)){
            case 1:
                $caminho = __DIR__."/../tabs/$foco/";
                $jsCaminho = $caminho."configuracoes.js";
                $js = "conteudo/modulos/configuracoes/tabs/".$foco."/configuracoes.js";
                break;
            case 2:
                 $caminho = $this->dir.$foco."/admins/setup/";
                $jsCaminho = $this->dir.$foco."/assets/configuracoes.js";
                $js = "/conteudo/modulos/".$foco."/assets/configuracoes.js";
                break;
            case 3:
                $caminho = $this->dir."wildcard/admins/setupuser/";
                $jsCaminho = $this->dir."wildcard/assets/configuracoes.js";
                $js = $jsCaminho;
                break;
        }
      
        
       
        
        if(!is_dir($caminho)){
             return ["sucesso"=>true, "html"=>false, "mensagem"=>"A pasta não existe", "caminho"=>$caminho];
        }
        

        if(file_exists($caminho.$grupo.".json")){
 
            try{ 
             
           $conteudo = file_get_contents($caminho.$grupo.".json");
        
           return ["sucesso"=>true, "obj"=>json_decode($conteudo, true), "tipo"=>"json", "respostas"=>$this->pegaRespostas(), "js"=>file_exists($jsCaminho) ? $js : false];
            }catch(Exception $e){
             return ["erro"=>true, "mensagem"=>"Erro ao converter arquivo"];
            }
        }
        
        if(file_exists($caminho.$grupo.".php")){
             ob_start();
             include($caminho.$grupo.".php");
             return ["sucesso"=>true, "html"=>$this->minificarPHP(ob_get_clean()), "tipo"=>"html", "respostas"=>$this->pegaRespostas(),"js"=>file_exists($jsCaminho) ? $js : false];
        }
        
        
         return ["sucesso"=>true, "html"=>false,  "mensagem"=>"Arquivo de configuração não existe", "arquivo"=>$caminho.$grupo.".json"];
    }
    
    function arquivo(){
        $seleciona = "SELECT * FROM configuracoes";
        $resultado = $this->conn->query($seleciona);
        $resposta = [];
        if($resultado->num_rows > 0){
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
                
                
    
                if($valor == "false"){
                    $resposta[$pasta][$arquivo][$chave] = false;
                }else{
                    $resposta[$pasta][$arquivo][$chave] = $valor ? $valor : false;
                }
                
            }
        }
        
        $logos = new Logos($resposta);
        $resposta["logotipos"] = $logos->imagens();
        
        
        
        
        
$dir = __DIR__."/../../../";




// Caminho completo do arquivo JSON
$filename = $dir."setup.json";
    $resposta["dominio"] = $_SERVER['HTTP_HOST'];
// Verifica se o arquivo já existe
if (!file_exists($filename)) {
    // Cria o arquivo com conteúdo JSON

    file_put_contents($filename, json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
} else {
    // Opcional: Atualiza o arquivo com o novo conteúdo JSON
    file_put_contents($filename, json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

        
    }
    
    function salvar(){
           
        $foco = $this->stringParaURL($this->foco);
        $grupo = $this->stringParaURL($this->grupo);
        
        
        
        switch(intval($this->tipo)){
            case 1:
                $banco = "configuracoes";
                break;
            case 2:
                $banco = "configuracoes_modulos";
                break;
            case 3:
                $banco = "configuracoes_wirecard";
                
                
                $wirecard = $_POST["wirecard"] ?? false;
                if(!$wirecard){
                    return ["erro"=>true, "mensagem"=>"Não foi definido um cliente válido"];
                }
                
                $seleciona = "SELECT * FROM wildcards_contas WHERE wc_hash='$wirecard'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    return ["erro"=>true, "mensagem"=>"Cliente não encontrado"];
                }
                
                $dado = $resultado->fetch_assoc();
                $conta = $dado["wc_id"];
                break;
        }
        
        

        $dados = json_decode($_POST["dados"], true);
        $sucesso = 0;
        $tentativas = 0;
        foreach($dados as $chave=>$valor){
            $tentativas++;
            $chave = $this->stringParaURL($chave);
            
            
            if(intval($this->tipo) == 3){
                $seleciona = "SELECT * FROM $banco WHERE config_pasta='$foco' AND config_arquivo='$grupo' AND  config_chave='$chave' AND config_conta='$conta'";
            }else{
                $seleciona = "SELECT * FROM $banco WHERE config_pasta='$foco' AND config_arquivo='$grupo' AND  config_chave='$chave'";
            }
            
            
            
            if(is_array($valor)){
                $valor = addslashes(json_encode($valor));
            }
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 0){
                if(intval($this->tipo) == 3){
                    $acao = "INSERT INTO $banco (config_pasta,config_arquivo,config_chave, config_valor, config_conta) VALUES ('$foco', '$grupo', '$chave', '$valor', '$conta')";
                }else{
                    $acao = "INSERT INTO $banco (config_pasta,config_arquivo,config_chave, config_valor) VALUES ('$foco', '$grupo', '$chave', '$valor')";
                }
                
            }else{
                $dado = $resultado->fetch_assoc();
                $id = $dado["config_id"];
                $acao = "UPDATE $banco SET config_valor='$valor' WHERE config_id='$id'";
            }
      
            if($this->conn->query($acao) == true){
                $sucesso++;
            }
        }
        
        $calc = $tentativas - $sucesso == 0 ? "Todos os itens foram salvos" : "Algum item não deu certo";



        //gera css do tema
        if($this->tipo == 1){
            $css = new generateCss();
            $css->generateFile("SELECT * FROM configuracoes", __DIR__.'/../../../assets/sistema/');
        }
        
        if($this->tipo == 3){
            $nome = $wirecard;
            $css = new generateCss();
            
        
            $css->generateFile("SELECT * FROM configuracoes_wirecard WHERE config_conta='$conta'" , __DIR__ . "/../../../assets/wildcard/".$nome."/sistema/");
        }
        
        if ($this->tipo == 3) {
    $seleciona = "SELECT * FROM $banco WHERE config_conta='$conta'";
    $resultado = $this->conn->query($seleciona);
    $setup = [];
    
    if ($resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            if (!isset($setup[$dado["config_pasta"]])) {
                $setup[$dado["config_pasta"]] = [];
            }
            
             if (!isset($setup[$dado["config_pasta"]][$dado["config_arquivo"]])) {
                $setup[$dado["config_pasta"]][$dado["config_arquivo"]] = [];
            }
            
 
            
            /*
            $json = json_decode($dado["config_valor"], true);
            
            if (json_last_error() == JSON_ERROR_NONE) {
                $setup[$dado["config_pasta"]][$dado["config_arquivo"]][$dado["config_chave"]] = $json;
            } else {
             
            }
            */
            
               $setup[$dado["config_pasta"]][$dado["config_arquivo"]][$dado["config_chave"]] = $dado["config_valor"];
        }
        
        
        
        $sistema = json_decode(file_get_contents(__DIR__."/../../../setup.json"), true);
        
        foreach($setup as $chave=>$item){
            if(!isset($sistema[$chave])){
                $sistema[$chave] = [];
            }
            
            foreach($item as $chave2=>$item2){
                if(!isset($sistema[$chave][$chave2])){
                    $sistema[$chave][$chave2] = [];
                }
                
                foreach($item2 as $chave3=>$item3){
                    if($item3 != ""){
                        if($item3 === "false"){
                            $item3 = false;
                        }
                        
                        if($item3 === "true"){
                            $item3 = true;
                        }
                        
                         $sistema[$chave][$chave2][$chave3] = $item3;
                    }
                   
                }
                   
            }
         
        }
        
        
        $nome = $wirecard;
        $diretorio = __DIR__ . "/../../../wildcard/setup/".$nome;
        
        // Verifique se a pasta setup existe, se não, crie-a
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        
        // Caminho completo do arquivo a ser salvo
        $caminhoArquivo = $diretorio . "/" ."setup.json";
        
        // Salve o conteúdo do array $setup no formato JSON dentro do arquivo
        file_put_contents($caminhoArquivo, json_encode($sistema, JSON_PRETTY_PRINT));
    }
}

        
        $this->arquivo();
        
        
        if($this->tipo == "2" && $this->foco == "Aplicativo"){
            include __DIR__."/../../aplicativo/admins/processador.php";
            new AppConstructor($this->grupo);
        } 
        
        if($this->foco == "Estilo"){
            include __DIR__."/tema.php";
            $tema = new Tema();
            $resposta = $tema->render();
        }
        
        return ["sucesso"=>true, "mensagem"=>$calc];
        
    }
    
    function render(){
        if(!$this->acao || !$this->tipo || !$this->foco || !$this->grupo ){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma ação, modulo ou grupo"];
        }
        
        
        switch($this->acao){
            case 'html':
                return $this->html();
                break;
            case 'salvar':
                return $this->salvar();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Nenhuma ação válida foi enviada"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();

echo json_encode($resposta);
?>