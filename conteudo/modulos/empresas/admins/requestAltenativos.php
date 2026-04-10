<?

header('Content-Type: application/json; charset=utf-8');


include __DIR__."/../../../../admin/conn.php";

include __DIR__."/../../../../admin/validador.php";



error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);

session_start();

class Acoes{
    public $acao;
    public $user;
    public $conn;
    
    function __construct(){
        $this->conn = conn();
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST['acao'] ?? false;
    }
    
    private function pegarMinhasEmpresas(){
        $usuario = $this->user;
        
        $seleciona = "SELECT ea_empresa FROM empresas_associacao WHERE ea_usuario ='$usuario' AND ea_funcao = '0'";
        
        $resultado = $this->conn->query($seleciona);
        $empresas = [];
        if($resultado->num_rows > 0){
            while ($dado = $resultado->fetch_assoc()) {
                $empresas[] = $dado['ea_empresa'];
            }
        }
        
        
        $infos = [];

        if (count($empresas) > 0) {
            $placeholders = implode(',', array_fill(0, count($empresas), '?'));
            $queryEmpresas = "SELECT empresa_nome, empresa_id FROM empresas WHERE empresa_id IN ($placeholders)";
            $stmtEmpresas = $this->conn->prepare($queryEmpresas);

            $types = str_repeat('i', count($empresas));
            $stmtEmpresas->bind_param($types, ...$empresas);
            $stmtEmpresas->execute();
            $resultadoEmpresas = $stmtEmpresas->get_result();

            while ($empresa = $resultadoEmpresas->fetch_assoc()) {
                $infos[] = [
                    'v'=> $empresa['empresa_id'],
                    't'=> $empresa['empresa_nome']
                ];
            }
            
        }
        
        return ['sucesso'=> true, 'lista'=> $infos];
    }
    
    
    function render(){
        if(!$this->acao){
            return ['erro'=> true, 'mensagem'=> 'Ação não foi passada.'];
        }
        
        if(!$this->user){
            return ['erro'=> true, 'mensagem'=> 'Usuário não está logado.'];
        }
        
        switch($this->acao){
            case 'pegarMinhasEmpresas':
                return $this->pegarMinhasEmpresas();
                break;
            default:
                return ['erro'=> true, 'mensagem'=> 'Ação não foi encontrada.'];
                break;
        }
    }
    
    

}

$acao = new Acoes();

$resposta = $acao->render();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

?>