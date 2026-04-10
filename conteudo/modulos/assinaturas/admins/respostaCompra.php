<?


include __DIR__.'/../../../../admin/conn.php';

function pegarUrlAtual() {
    
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $caminho = $_SERVER['REQUEST_URI'];
    return $protocolo . $host;
    
}

function verificarPalavraNaUrl() {

    $urlAtual = pegarUrlAtual();
    
    $palavras = ['ferramental', 'nown'];


    foreach ($palavras as $palavra) {
        if (strpos($urlAtual, $palavra) !== false) {
            return $palavra;
        }
    }
    

    return null;
}


class MudancasEmpresas{
    public $acao;
    public $conn;
    public $usuario; 
    public $produto;
    
    function __construct(){
        $this->acao = $_POST['acao'] ?? false;
        $this->conn = conn();
        $this->usuario = $_POST['usuarios'] ?? false;
        $this->produto =  $_POST['referencia'] ?? false;
        
    } 
    
    function mudarPremium(){
        
        if($this->acao == 'pedidosSucesso'){
            $valor = 1;
        }
        else{
            $valor = 0;
        }
        
        $usuario = $this->usuario;
        
        $sql = "SELECT * FROM empresas WHERE empresa_autor = '$usuario'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $id = $dado['empresa_id'];
                
                $sql_meta = "SELECT * FROM empresas_meta WHERE em_empresa =  '$id' AND em_chave = 'premium'";
                
                $resultadoMeta = $this->conn->query($sql_meta);;
                
                if($resultadoMeta->num_rows == 1){
                    $info = $resultadoMeta->fetch_assoc();
                    $dadoValor = $info['em_valor'];
                    $id_meta = $info['em_id'];
                    
                    if($dadoValor != $valor){
                        $this->updatePremium($valor, $id_meta);
                    }
                    
                }
                else{
                    $this->criarPremium($valor, $id);
                }
            }
            
            return ['sucesso'=> true, 'mensagem'=> 'Empresas Atualizadas'];
        }
        else{
            return ['erro'=> true, 'mensagem'=> 'Esse usuário não tem nenhuma empresa'];
        }
    }
    
    function updatePremium($valor, $id){
        $sql = "SELECT * FROM empresas_meta WHERE em_id = '$id'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $update = "UPDATE empresas_meta SET em_valor = '$valor' WHERE em_id = '$id'";
        
            $this->conn->query($update);
               
        }
    }
    
    function criarPremium($valor, $id){
        $sql = "INSERT INTO empresas_meta (em_empresa, em_chave, em_valor) VALUES ('$id', 'premium', '$valor')";
        
        $this->conn->query($sql);
               
    }
    
    function iniciar(){
        $produto = $this->produto;
        $sql = "SELECT * FROM planos WHERE plano_id = '$produto'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            
            if($dado['plano_categoria'] == 57){
                return $this->mudarPremium();
            }
            else{
                return ['erro'=> true, 'mensagem'=> 'Esse plano não é empresarial'];
            }
        }
        else{
            return ['erro'=> true, 'mensagem'=> 'Não foi achado nenhum plano com esse id'];
        }
    }
}


$url = verificarPalavraNaUrl();


switch ($url) {
    case 'ferramental':
        $resposta = new MudancasEmpresas();
        $acao = $resposta->iniciar();
        break;
    case 'nown':
        $resposta = new MudancasEmpresas();
        $acao = $resposta->iniciar();
        break;
    default:
        break;
}

echo json_encode($acao, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


?>