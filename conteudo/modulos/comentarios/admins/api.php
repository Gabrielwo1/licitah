<?
header('Content-Type: application/json; charset=utf-8');

include __DIR__."/../../../../admin/conn.php";
session_start();


class Acao{
    public $conn;
    public $user;
    public $modulo;
    public $url;
    public $id;
    public $acao;
    public $pai;
    public $prefixo;
    public $paginacao;
    public $ordenagem;
    
    function __construct(){
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->url = $_POST["url"] ?? false;
        $this->id = $_POST["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false; 
        $this->pai = $_POST["pai"] ?? 0;
        $this->prefixo = $_POST["prefixo"] ?? false;
        $this->paginacao = $_POST["paginacao"] ?? false;
        $this->ordenagem = $_POST["ordenagem"] ?? "populares";
    }
    
    function nova(){
        
    }
    
    function infoTabela() {
         $tabela = $this->modulo;
        // Verificar se a tabela existe no banco de dados
        $sqlTabelaExiste = "SHOW TABLES LIKE '$tabela'";
        $resultadoTabela = $this->conn->query($sqlTabelaExiste);

        if ($resultadoTabela->num_rows > 0) {
            // Tabela existe, agora verificar as colunas
            $sqlColunas = "SHOW COLUMNS FROM $tabela";
            $resultadoColunas = $this->conn->query($sqlColunas);

            $colunaUrl = null;
            $colunaId = null;
            $colunaAutor = null;

            // Verificar se alguma coluna termina com _url ou _id
            while ($coluna = $resultadoColunas->fetch_assoc()) {
                if (preg_match('/_url$/', $coluna['Field'])) {
                    $colunaUrl = $coluna['Field'];
                }
                if (preg_match('/_id$/', $coluna['Field'])) {
                    $colunaId = $coluna['Field'];
                }
                
                if (preg_match('/_autor$/', $coluna['Field'])) {
                    $colunaAutor = $coluna['Field'];
                }
            }

            // Se ambas as colunas forem encontradas, retorne o array com os nomes
            if ($colunaUrl && $colunaId) {
                return ['url' => $colunaUrl, 'id' => $colunaId, "autor"=>$colunaAutor];
            }
        }

        // Caso contrário, retorne false
        return false;
    }
    
    function pegaId($tabela, $url){
        $identificador = $tabela["id"];
        $urlizador = $tabela["url"];
        $tabela = $this->modulo;
        $seleciona = "SELECT $identificador FROM $tabela WHERE $urlizador='$url'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return $dado[$identificador];
    }
    
    function hasher($size) {
        $size = $size / 2;
        return bin2hex(random_bytes($size)); 
    }
    
    function novo(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação não permitida para usuários não logados"];
        }
        
        
        $base = $this->parseador();
        if(isset($base["erro"])){
            return $base;
        }

        $id = $base["id"];
        
        $modulo = $this->modulo;
        
        $mensagem = $_POST["texto"] ?? false;
        if(!$mensagem){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma mensagem válida"];
        }
        $hash = $this->hasher(32);
        $url = $this->hasher(12);
        $autor = $this->user;
        $pai = $this->pai;
        
        $cadastra = "INSERT INTO comentarios 
        (comentario_modulo, comentario_identificador, comentario_comentario, comentario_hash, comentario_url, comentario_autor, comentario_pai) VALUES 
        ('$modulo', '$id', '$mensagem', '$hash',  '$url', '$autor', '$pai')";
        if( $this->conn->query($cadastra) == true){
            $id = $this->conn->insert_id;
            
            if($pai > 0){
                  $update = "UPDATE comentarios SET comentario_filhos = comentario_filhos + 1 WHERE comentario_id = $pai";
                  $this->conn->query($update);
            }
            
            
            
            
            return ["sucesso"=>true, "id"=>$id];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possível cadastrar o comentário"];
        }
        
        
        
        
    }
    
    function parseador(){
        if(!$this->url){
            return ["erro"->true, "mensagem"=>"Não foi enviada uma URL válida"];
        }
        
        $tabela = $this->infoTabela();
        if(!$tabela){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma tabela válida"];
        }
        
       
        $id = $this->pegaId($tabela, $this->url);
        if(!$id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um identificador válido"];
        }
        
        return ["id"=>$id];
    }
    
    function pegaAutores($autores) {
    $lista = [];

    // Verificar se o array de autores está vazio
    if (empty($autores) || !is_array($autores)) {
        return $lista;
    }

    // Garantir que os valores são únicos e inteiros
    $autores = array_unique(array_map('intval', $autores));
    
    // Se não houver autores válidos, retorna a lista vazia
    if (empty($autores)) {
        return $lista;
    }

    // Converter o array em uma string para a consulta
    $itens = implode(",", $autores);

    // Selecionar os usuários que correspondem aos IDs no array $autores
    $seleciona = "SELECT * FROM usuarios WHERE usuario_id IN ($itens)";
    $resultado = $this->conn->query($seleciona);

    // Verificar se houve resultados
    if ($resultado && $resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $lista[$dado["usuario_id"]] = [
                "d" => $dado["usuario_display"],
                "u" => $dado["usuario_user"],
                "f" => $dado["usuario_foto"]
            ];
        }
    }

    return $lista;
}

    function init(){
        $base = $this->parseador();
        if(isset($base["erro"])){
            return $base;
        }

        $id = $base["id"];
        
        $modulo = $this->modulo;
        
        $seleciona = "SELECT count(*) as total FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id'";
        $resultado = $this->conn->query($seleciona);
        
        if ($resultado) {
            $dado = $resultado->fetch_assoc();
            $total = intval($dado["total"]);
        } else {
            $total = 0;
        }
        
        $seleciona = "SELECT count(*) as total FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id' AND comentario_pai='0'";
        $resultado = $this->conn->query($seleciona);
        
        if ($resultado) {
            $dado = $resultado->fetch_assoc();
            $totalnivel = intval($dado["total"]);
        } else {
            $totalnivel = 0;
        }
        
        $pega = $this->pegaLista($modulo, $id, 0);
        
        
        return ["sucesso"=>true, "total"=>$total, "totalNivel"=>$totalnivel, "lista"=>$pega["lista"], "autores"=>$pega["autores"], "id"=>$id];
     
        
    }
    
    function pegaLista($modulo, $id, $pai, $paginacao = 1){
    $lista = [];
    $autores = [];
    
    $limite = 20;
    
    // Calcular o OFFSET
    $offset = ($paginacao - 1) * $limite;
    

    
    // Selecionar os comentários com base na paginação
    
    switch($this->ordenagem) {
    case 'recentes':
        $seleciona = "SELECT * FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id' AND comentario_pai='$pai' ORDER BY comentario_data DESC LIMIT $limite OFFSET $offset";
        break;
    case 'antigos':
        $seleciona = "SELECT * FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id' AND comentario_pai='$pai' ORDER BY comentario_data ASC LIMIT $limite OFFSET $offset";
        break;
    default:
        $seleciona = "SELECT * FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id' AND comentario_pai='$pai' ORDER BY comentario_filhos DESC, comentario_data ASC LIMIT $limite OFFSET $offset";
        break;
    }

    
    $resultado = $this->conn->query($seleciona);
    
    if($resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            array_push($lista, [
                "i"=>intval($dado["comentario_id"]),
                "a"=>intval($dado["comentario_autor"]),
                "t"=>$dado["comentario_comentario"],
                "d"=>$dado["comentario_data"],
                "p"=>intval($dado["comentario_pai"]),
                "f"=>intval($dado["comentario_filhos"])
            ]);
            array_push($autores, $dado["comentario_autor"]);
        }
    }
    
    // Obter informações dos autores
    $autores = $this->pegaAutores($autores);
    
    // Retornar os comentários, autores e dados de paginação
    return [
        "lista" => $lista,
        "autores" => $autores
    ];
}

    function respostas(){
        if(!$this->pai){
            return ["erro"=>true, "mensagem"=>"Não foi enviado parametros válidos"];
        }
        
       
        $id = $this->id;
        $pai = $this->pai;
  
        
        $seleciona = "SELECT * FROM comentarios WHERE comentario_id='$pai'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um comentário válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        $modulo = $dado["comentario_modulo"];
        $id = $dado["comentario_identificador"];


        $pega = $this->pegaLista($modulo, $id, $pai);
        
        $seleciona = "SELECT count(*) as total FROM comentarios WHERE comentario_modulo='$modulo' AND comentario_identificador='$id' AND comentario_pai='$pai'";
        $resultado = $this->conn->query($seleciona);
        $dado = $resultado->fetch_assoc();
        $totalnivel = intval($dado["total"]);
        
        
        return ["sucesso"=>true, "lista"=>$pega["lista"], "autores"=>$pega["autores"], "totalNivel"=>$totalnivel];
    }
    
    function more(){
        if(!$this->id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um identificador válido"];
        }
        
        if(!$this->modulo){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um módulo válido"];
        }
        
        $modulo = $this->modulo;
        $pai = $this->pai ?? 0;
        $id = $this->id;
        $paginacao = $this->paginacao;
        $pega = $this->pegaLista($modulo, $id, $pai, $paginacao);
        
        return ["sucesso"=>true, "lista"=>$pega["lista"], "autores"=>$pega["autores"]];
        

    }
    
    function listInfo() {
        
        $lista = $_POST["lista"] ?? false;
        if (!$lista) {
            return ["erro" => true, "mensagem" => "Não foi enviada uma lista de IDs válidos"];
        }
        
        if (!$this->modulo) {
            return ["erro" => true, "mensagem" => "Não foi enviado um módulo válido"];
        }
        
        if (empty($lista)) {
            return ["erro" => true, "mensagem" => "Foi enviada uma lista vazia"];
        }
        
        $ids = implode(",", $lista); 
        $modulo = $this->modulo;
        
        if (!$this->user ) {
            $this->user  = $_POST["me"] ?? false;
        }
        $me = $this->user;
        
        if (!$me) {
            return ["erro" => true, "mensagem" => "Ação não permitida para usuários deslogados"];
        }

   $seleciona = "SELECT comentario_identificador , COUNT(*) AS total_comentarios
              FROM comentarios
              WHERE comentario_modulo = '$modulo'
              AND comentario_identificador IN ($ids)
              GROUP BY comentario_identificador";
                  
       


    $result = $this->conn->query($seleciona);

    if (!$result) {
        return ["erro" => true, "mensagem" => "Erro ao buscar comentários no banco de dados"];
    }
  
    $comentarios = [];
    while ($row = $result->fetch_assoc()) {
        $comentarios[$row['comentario_identificador']] = $row['total_comentarios'];
    }

    return ["sucesso" => true , "comentarios" => $comentarios];
}

    
    function render(){
        switch($this->acao){
            case 'init':
                if(!$this->modulo){
                    return ["erro"=>true, "mensagem"=>"Não foi enviado um módulo válido"];
                }
                return $this->init();
                break;
            case 'novo':
                return $this->novo();
                break;
            case 'respostas':
                return $this->respostas();
                break;
            case 'more':
                return $this->more();
                break;
            case 'listInfo':
                return $this->listInfo();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi enviada uma ação válida"];
                break;
        }
    }
    
    
}


$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_UNESCAPED_UNICODE |  JSON_PRETTY_PRINT);

?>