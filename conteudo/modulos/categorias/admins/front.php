<?

// header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
ini_set("display_errors", 1);

session_start();

include __DIR__.'/../../../../admin/conn.php';

class Acoes{
    private $user;
    private $acao;
    private $conn;
    
    function __construct(){
        $this->user = $_SESSION['id'] ?? false;
        $this->acao = $_POST['acao'] ?? false;
        $this->conn = conn();
    }
    
    
    private function listarTudo(){
        
        $tipo = $_POST['formulario'];
        
        $sql = "SELECT * FROM categorias WHERE categoria_tipo = '$tipo'";
        
        $condicoes = $this->condicionais();
        
        return $sql;
        
    }
    
    private function condicionais(){
        $where = [];
        if(!empty($_POST['categoria']) || !empty($_POST['tag'])){
            $condicao = "(";
            if(!empty($_POST['categoria'])){
                $condicao .= "categoria_iscat == '1'";
            }
            
            if(!empty($_POST['categoria']) && !empty($_POST['tag'])){
                 $condicao .= " OR ";
            }
            
            if(!empty($_POST['tag'])){
                $condicao .= "categoria_iscat == '0'";
            }
            
            $condicao .= ")";
            
            $where[] = $condicao;
        }
        
        return $where;
    }
    
    private function listagem(){
        if(empty($_POST['modulo'])){
            return ['erro'=> true, 'mensagem'=> 'módulo não passado'];
            
        }
        
        if(empty($_POST['formulario'])){
            return ['erro'=> true, 'mensagem'=> 'formulário não passado'];
        }
        
        $modulo = $_POST['modulo'];
        $formulario = $_POST['formulario'];
        
        $caminho = __DIR__.'/../../'.$modulo.'/admins/configs/'.$formulario.'.json';
        
        if(!file_exists($caminho)){
            return ['erro'=> true, 'mensagem'=> 'formulário não existe no módulo passado ou módulo pode ter sido passado errado'];
        }
        
        
        
        if(empty($POST['hash'])){
            $sql = $this->listarTudo();
        }else{
            
        }
        
        $resultado = $this->conn->query($sql);
        $categorias = [];
        $tags = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $info = [
                    'url'=> $dado['categoria_url'],
                    'nome'=> $dado['categoria_nome'],
                    'cor'=> $dado['categoria_cor']
                ];
                if(intval($dado['categoria_iscat'])){
                    $categorias[] = $info;
                }else{
                    $tags[] = $info;
                }
            }
        }
        
        
        
        
        return ['sucesso'=> true, 'categorias'=> $categorias, 'tags'=> $tags];
    }
    
    function render(){
        if(empty($this->user)){
            return ['erro'=> true, 'mensagem'=> 'Ação só é permitida para usuários logados'];
        }
        
        if(empty($this->acao)){
            return ['erro'=> true, 'mensagem'=> 'Não foi passado um parâmetro de ação'];
        }
        
        
        switch($this->acao){
            case 'listar':
                return $this->listagem();
                break;
        }
    }
}


$acao = new acoes();
$resultado= $acao->render();

// Retornar o resultado em formato JSON
echo json_encode($resultado, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);