<?
include_once __DIR__."/../../../../admin/conn.php";
session_start();

class Acao{
    public $conn;
    public $acao;
    public $me;
    public $modulo;
    public $id;
    public $url;
    public $reacao;
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->me = $_SESSION["id"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->id = $_POST["id"] ?? false;
        $this->url = $_POST["url"] ?? false;
        $this->reacao = $_POST["reacao"] ?? 0;
     
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
    
    function interacao(){
        if(!$this->me){
            return ["erro"=>true, "mensagem"=>"Essa ação somente é permitida para usuários logados"];
        }
        
        if(!$this->url || !$this->modulo){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os parametros mínimos"];
        }
        
        $tabela = $this->infoTabela();
        if(!$tabela){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma tabela válida"];
        }
        
        $id =  $this->pegaId($tabela, $this->url);
         if(!$id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um identificador válido"];
        }
        
        
        $reacao =  intval($this->reacao);
        $banco = $this->modulo;
        $me = $this->me;
        
        if(!$reacao){
            $acao = "DELETE FROM reacoes_interacoes WHERE reacoes_interacao_banco='$banco' AND reacoes_interacao_identificador='$id' AND reacoes_interacao_autor='$me'";
            if($this->conn->query($acao) == true){
                return ["sucesso"=>true, "mensagem"=>"O item foi descutido com sucesso"];
            }else{
                return ["erro"=>true, "mensagem"=>"Não foi possível descutir o item"];
            }
        }
        
        
        
        $seleciona = "SELECT * FROM reacoes_interacoes WHERE reacoes_interacao_banco='$banco' AND reacoes_interacao_identificador='$id' AND reacoes_interacao_autor='$me'";
        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows == 0){
            $acao = "INSERT INTO reacoes_interacoes (reacoes_interacao_banco, reacoes_interacao_identificador, reacoes_interacao_autor, reacoes_interacao_reacao) VALUES ('$banco', '$id', '$me', '$reacao')";
        }else{
            $dado = $resultado->fetch_assoc();
            $inde = $dado["reacoes_interacao_id"];
            $acao = "UPDATE reacoes_interacoes SET reacoes_interacao_reacao='$reacao' WHERE reacoes_interacao_id='$inde'";
        }
        
        if($this->conn->query($acao) == true){
            return ["sucesso"=>true, "mensagem"=>"O item foi curtido com sucesso"];
        }else{
            return ["erro"=>true, "mensagem"=>"Não foi possivel curtir o item"];
        }
    }
    
    function infoLista(){
        
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
   
    
    if (!$this->me) {
        $this->me = $_POST["me"] ?? false;
    }
  
     $me = $this->me;
     
       
    if (!$this->me) {
        return ["erro" => true, "mensagem" => "Ação não permitida para usuários deslogados"];
    }
    

    $seleciona = "
        SELECT 
            reacoes_interacao_identificador, 
            reacoes_interacao_reacao,
            COUNT(*) AS total_reacoes
        FROM 
            reacoes_interacoes 
        WHERE 
            reacoes_interacao_banco = '$modulo' 
            AND reacoes_interacao_identificador IN ($ids)
        GROUP BY 
            reacoes_interacao_identificador, 
            reacoes_interacao_reacao
    ";
    
    $resultado = $this->conn->query($seleciona);
    

    $interacoes = [];
    
  
    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $identificador = $row['reacoes_interacao_identificador'];
            $reacao = $row['reacoes_interacao_reacao'];
            $total = $row['total_reacoes'];
            

            if (!isset($interacoes[$identificador])) {
                $interacoes[$identificador] = [
                    'r' => [],
                    'm' => 0
                ];
            }
            
         
            $interacoes[$identificador]['r'][$reacao] = $total;
        }
    }


    $verificaReacaoUsuario = "
        SELECT 
            reacoes_interacao_identificador, 
            reacoes_interacao_reacao
        FROM 
            reacoes_interacoes 
        WHERE 
            reacoes_interacao_banco = '$modulo'
            AND reacoes_interacao_identificador IN ($ids)
            AND reacoes_interacao_autor = $me
    ";
    

    $resultadoUsuario = $this->conn->query($verificaReacaoUsuario);
    

    if ($resultadoUsuario && $resultadoUsuario->num_rows > 0) {
        while ($rowUsuario = $resultadoUsuario->fetch_assoc()) {
            $identificador = $rowUsuario['reacoes_interacao_identificador'];
            $reacaoUsuario = $rowUsuario['reacoes_interacao_reacao'];
            

            if (isset($interacoes[$identificador])) {
                $interacoes[$identificador]['m'] = $reacaoUsuario; 
            }
        }
    }


    foreach ($lista as $id) {
        if (!isset($interacoes[$id])) {
            $interacoes[$id] = [
                'r' => [], 
                'm' => 0
            ];
        }
    }
    
    return ["sucesso" => true , "dados" => $interacoes];
}

    function infos() {
    // Verificação inicial dos parâmetros obrigatórios
    if (!$this->url || !$this->modulo) {
        return ["erro" => true, "mensagem" => "Não foram enviados os parâmetros mínimos"];
    }

    // Valida a tabela
    $tabela = $this->infoTabela();
    if (!$tabela) {
        return ["erro" => true, "mensagem" => "Não foi enviada uma tabela válida"];
    }

    // Valida o identificador
    $id = $this->pegaId($tabela, $this->url);
    if (!$id) {
        return ["erro" => true, "mensagem" => "Não foi enviado um identificador válido"];
    }

    $reacao = intval($this->reacao);  // Garantir que a reação seja um número inteiro
    $banco = $this->modulo;
    
    // Consulta segura com prepared statement para contar as interações
    $query = "SELECT count(*) as total FROM reacoes_interacoes 
              WHERE reacoes_interacao_banco = ? 
              AND reacoes_interacao_identificador = ?";
    
    if ($reacao) {
        $query .= " AND reacoes_interacao_reacao = ?";
    }

    // Preparação da consulta
    $stmt = $this->conn->prepare($query);
    if ($reacao) {
        $stmt->bind_param("ssi", $banco, $id, $reacao);
    } else {
        $stmt->bind_param("ss", $banco, $id);
    }

    // Execução da consulta e validação do resultado
    if (!$stmt->execute()) {
        return ["erro" => true, "mensagem" => "Erro ao executar a consulta"];
    }

    $resultado = $stmt->get_result();
    $dado = $resultado->fetch_assoc();
    $total = $dado['total'] ?? 0;

    $lista = [];
    
   $mapa = [];
    // Se o total for maior que 0, busca os autores
    if ($total > 0) {
        $queryAutores = "SELECT reacoes_interacao_autor as total, reacoes_interacao_reacao as reaction FROM reacoes_interacoes 
                         WHERE reacoes_interacao_banco = ? 
                         AND reacoes_interacao_identificador = ?";

        if ($reacao) {
            $queryAutores .= " AND reacoes_interacao_reacao = ?";
        }

        $queryAutores .= " LIMIT 10";
        
        // Preparação da segunda consulta para buscar autores
        $stmtAutores = $this->conn->prepare($queryAutores);
        if ($reacao) {
            $stmtAutores->bind_param("ssi", $banco, $id, $reacao);
        } else {
            $stmtAutores->bind_param("ss", $banco, $id);
        }

        if ($stmtAutores->execute()) {
            $resultadoAutores = $stmtAutores->get_result();
            while ($dado = $resultadoAutores->fetch_assoc()) {
                $lista[] = $dado["total"];
                $mapa[$dado["total"]] = $dado["reaction"];
            }
        }
    }
    
    $nomes = [];

    if(!empty($lista)){
        $ids = implode(",", $lista);
        $seleciona = "SELECT usuario_id as id, usuario_display as nome, usuario_user as user, usuario_foto as foto FROM usuarios WHERE usuario_id  IN ($ids)";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                array_push($nomes, [
                    "nome"=>$dado["nome"],
                    "user"=>$dado["user"],
                    "foto"=>$dado["foto"],
                    "reacao"=>$mapa[$dado["id"]]
                    ]);
            }
        }
        
    }

    return ["sucesso" => true, "total" => $total, "lista" => $nomes];
}


    function render(){
        switch($this->acao){
            case 'interacao':
                return $this->interacao();
                break;
            case 'infoUni':
                break;
            case 'infoLista':
                return $this->infoLista();
                break;
            case 'infos':
                return $this->infos();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é válida"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>