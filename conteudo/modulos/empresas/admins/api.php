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
    
    private function pegarEmpresas(){
        $usuario = $this->user;
        
        $seleciona = "SELECT ea_empresa FROM empresas_associacao WHERE ea_usuario ='$usuario'";
        
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
    
    private function pegarUsuariosEmpresas(){
        $empresa = intval($_POST["valor"]);
        
        $seleciona = "SELECT ea_usuario FROM empresas_associacao WHERE ea_empresa ='$empresa'";
        $resultado = $this->conn->query($seleciona);
        $usuarios = [];
        if($resultado->num_rows > 0){
            while ($dado = $resultado->fetch_assoc()) {
                $usuarios[] = $dado['ea_usuario'];
            }
        }
        
        $infos = [];

        if (count($usuarios) > 0) {
            $placeholders = implode(',', array_fill(0, count($usuarios), '?'));
            $queryUsuarios = "SELECT usuario_display, usuario_id FROM usuarios WHERE usuario_id IN ($placeholders)";
            $stmtUsuarios = $this->conn->prepare($queryUsuarios);

            $types = str_repeat('i', count($usuarios));
            $stmtUsuarios->bind_param($types, ...$usuarios);
            $stmtUsuarios->execute();
            $resultadoUsuarios = $stmtUsuarios->get_result();

            while ($usuario = $resultadoUsuarios->fetch_assoc()) {
                $infos[] = [
                    'v'=> $usuario['usuario_id'],
                    't'=> $usuario['usuario_display']
                ];
            }
            
        }
        
        return ['sucesso'=> true, 'lista'=> $infos];
    }
    
    function nova(){
        $this->cnpj = $_POST["cnpj"] ?? false;
        if(!$this->cnpj){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um CNPJ"];
        }
        
        $valida = new ValidaInfo($this->cnpj, "cnpj");
        
        if(!$valida->valida()){
            return ["erro"=>true, "mensagem"=>"O CNPJ digitado não é válido"];
        }
        
        $seleciona = "SELECT empresa_id  as id FROM empresas WHERE empresa_cnpj='{$this->cnpj}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            include_once __DIR__."/empresa.php";
            $empresa = new Empresa($this->cnpj);
            $empresa->consulta();
            $empresa->associa($this->user);
            
            return ["sucesso"=>true, "mensagem"=>"Empresa Cadastrada com Sucesso"];
            
            
         
        }
        
        $dado = $resultado->fetch_assoc();
        $id = $dado["id"];
        
        $seleciona = "SELECT * FROM empresas_associacao WHERE ea_empresa='{$id}' AND ea_usuario='{$this->user}' LIMIT 1";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 1){
            return ["erro"=>true, "mensagem"=>"Essa empresa já é sua"];
        }
        
        $cadastra = "INSERT INTO empresas_associacao (ea_empresa,ea_usuario,ea_funcao) VALUES ('$id', '$this->user', '0')";
        if($this->conn->query($cadastra) == true){
             return ["sucesso"=>true, "mensagem"=>"Empresa Associada com Sucesso"];
        }else{
             return ["erro"=>true, "mensagem"=>"Não foi possível fazer associação a esssa empresa"];
        }
   
    }
    
    private function vinculo(){
        $url = !empty($_POST['hash']) ? $_POST['hash'] : false;
        
        if(!$url){
            return ['erro'=> true, 'mensagem'=> 'Código não passado'];
        }
        
        
        $sql = "SELECT 
        ec.empresas_codigo_empresa as empresa, 
        ec.empresas_codigo_funcao as funcao, 
        e.empresa_hash as hash,
        ec.empresas_codigo_uso as uso
        FROM empresas_codigos as ec
        LEFT JOIN empresas as e ON e.empresa_id = ec.empresas_codigo_empresa
        WHERE empresas_codigo_url = '$url'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dados = $resultado->fetch_assoc();
            
            $empresa = $dados['empresa'];
            $funcao = $dados['funcao'];
            $hash = $dados['hash'];
            
            if(intval($dados['uso'])){
                return ['erro'=> true, 'mensagem'=> 'Código já foi usado'];
            }
            
        }else{
            return ['erro'=> true, 'mensagem'=> 'Código não é válido'];
        }
        
        $cadastra = "INSERT INTO empresas_associacao (ea_empresa,ea_usuario,ea_funcao) VALUES ('$empresa', '{$this->user}', '$funcao')";
        
        if($this->conn->query($cadastra) == true){
            if(!intval($dados['uso'])){
                $sql2 = "UPDATE empresas_codigos SET empresas_codigo_uso = '1', empresas_codigo_usuario = '{$this->user}' WHERE empresas_codigo_url = '$url'";
                
                $resultado2 = $this->conn->query($sql2);
            }
            
            return ["sucesso"=>true, "mensagem"=>"Empresa Associada com Sucesso", "hash"=> $hash];
        }else{
             return ["erro"=>true, "mensagem"=>"Não foi possível fazer associação a essa empresa"];
        }
    }
    
    
    function render(){
        if(!$this->acao){
            return ['erro'=> true, 'mensagem'=> 'Ação não foi passada.'];
        }
        
        if(!$this->user){
            return ['erro'=> true, 'mensagem'=> 'Usuário não está logado.'];
        }
        
        switch($this->acao){
            case 'pegarUsuariosEmpresas':
                return $this->pegarUsuariosEmpresas();
                break;
            case 'pegarEmpresas':
                return $this->pegarEmpresas();
                break;
            case 'novo':
                return $this->nova();
                break;
            case 'vinculo':
                return $this->vinculo();
                break;
            default:
                return ['erro'=> true, 'mensagem'=> 'Ação não foi encontrada.'];
                break;
        }
    }
    
    

}


if(file_exists(__DIR__."/../../licitacoes/admins/controlecnpj.php")){
    include __DIR__."/../../licitacoes/admins/controlecnpj.php";
    
    
    if(isset($existencia['erro'])){
        echo json_encode($existencia, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        return;
    }
}

$acao = new Acoes();

$resposta = $acao->render();


echo json_encode($resposta, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

?>