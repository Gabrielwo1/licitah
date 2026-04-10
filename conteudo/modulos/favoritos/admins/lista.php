<?
include __DIR__."/../../../../admin/conn.php";
session_start();

class Acao{
    private $modulo;
    private $acao;
    private $user;
    private $conn;
    private $sub;
    
    function __construct(){
        $this->modulo = $_POST["modulo"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->sub = $_SESSION["sub"] ?? 0;
        $this->conn = conn();
    }
    
    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
     private function listarFavoritos(){
        $modulos = $this->modulo;
        
        // Converte JSON para array se necessário
        if($this->isJson($this->modulo)){
            $modulos = json_decode($this->modulo, true);
        }
        
        // Garante que seja array
        if(!is_array($modulos)){
            $modulos = [$modulos];
        }
        
        // Verifica se não está vazio
        if(empty($modulos)){
            return ['erro'=> true, 'mensagem'=> 'Nenhum módulo foi passado'];
        }
        
        // Sanitiza os módulos (strings)
        $modulos_sanitizados = [];
        foreach($modulos as $modulo){
            // Converte para string e remove espaços
            $modulo = trim((string)$modulo);
            if(!empty($modulo)){
                $modulos_sanitizados[] = $modulo;
            }
        }
        
        if(empty($modulos_sanitizados)){
            return ['erro'=> true, 'mensagem'=> 'Nenhum módulo válido foi passado'];
        }
        
        // Cria placeholders para prepared statement
        $placeholders = str_repeat('?,', count($modulos_sanitizados) - 1) . '?';
        
        // Query com prepared statement para evitar SQL injection
        $sql = "SELECT * FROM favoritos 
                WHERE favorito_modulo IN ($placeholders) 
                AND favorito_autor = ? 
                AND favorito_conta = ?";
        
        $stmt = $this->conn->prepare($sql);
        
        if(!$stmt){
            return ['erro'=> true, 'mensagem'=> 'Erro na preparação da consulta: ' . $this->conn->error];
        }
        
        // Prepara os tipos para bind_param (s = string, i = integer)
        $tipos = str_repeat('s', count($modulos_sanitizados)) . 'ii'; // s = string para módulos, i = integer para user e sub
        $parametros = array_merge($modulos_sanitizados, [$this->user, $this->sub]);
        
        // Bind dos parâmetros
        $stmt->bind_param($tipos, ...$parametros);
        
        // Executa a query
        if(!$stmt->execute()){
            return ['erro'=> true, 'mensagem'=> 'Erro na execução da consulta: ' . $stmt->error];
        }
        
        $resultado = $stmt->get_result();
        $lista = [];
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($lista[$dado['favorito_modulo']])){
                    $lista[$dado['favorito_modulo']] = [];
                }
                
                $lista[$dado['favorito_modulo']][] = $dado['favorito_identificador'];
            }
        }
        
        $stmt->close();
        
        return ['sucesso'=> true, 'lista'=> $lista];
    }
    
    
    function render(){
        if(!$this->modulo || !$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros válidos"];
        }
        
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação permitida somente para usuários logados"];
        }
        
        switch($this->acao){
            case 'listar':
                return $this->listarFavoritos();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviado não é válida"];
                break;
        }
        
    }

}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>