<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__."/../../../../admin/seguranca-include.php";
$seguranca->onlyLogados();

include __DIR__."/../../../../admin/conn.php";

class Procurar{
    private $conn;
    private $termo;
    private $tipo;
    private $pagina;
    private $acao;
    private $user;
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->tipo = $_POST["tipo"] ?? false;
        $this->termo = $_POST["termo"] ?? false;
        $this->pagina = $_POST["pagina"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
    }
    
    function amigos(){
        $seleciona = "SELECT usuarios_vinculo_usuario as um, usuarios_vinculo_usuario2 as dois FROM usuarios_vinculos WHERE (usuarios_vinculo_usuario='{$this->user}' 
        OR usuarios_vinculo_usuario2='{$this->user}') AND usuarios_vinculo_tipo='1' AND usuarios_vinculo_aprovado='1'";
        
   
        $amigos = [];
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $amigo = $dado["um"] == $this->user ? $dado["dois"] : $dado["um"];
                $amigos[] = $amigo;
            }
        }
        
        
 
        
        $friends = [];
        if(!empty($amigos)){
            $lista = implode(",", $amigos);
              $seleciona = "SELECT 
              usuario_id AS id,
              usuario_display AS display,
              usuario_user AS user, 
            usuario_funcao AS funcao, 
            usuario_foto AS foto, 
            usuario_capa AS capa
                  FROM usuarios 
                  WHERE usuario_id in ($lista) ORDER BY usuario_display ASC
                  LIMIT 12";
                
                  
                  $resultado = $this->conn->query($seleciona);
   
    if($resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            $friends[] = [
                "nome"=>$dado["display"],
                "user"=>$dado["user"],
                "foto"=>$dado["foto"]
                ];
        }
    }
        }
        
        return ["sucesso"=>true, "resultado"=>$friends];
        
    }
    
    function procurar(){
        if(!$this->termo){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um termo válido"];
        }
        
          
    $termo = '%' . addslashes($this->termo) . '%';
    
    
    $total = 0;
    
    $seleciona = "SELECT count(*) as total
                  FROM usuarios 
                  WHERE usuario_display LIKE '$termo' 
                  OR usuario_user LIKE '$termo'";
    
                  
    $resultado = $this->conn->query($seleciona);
    $dado = $resultado->fetch_assoc();
    $total = $dado["total"];
                  

    $seleciona = "SELECT usuario_id AS id, usuario_display AS display, usuario_user AS user, 
                  usuario_funcao AS funcao, usuario_foto AS foto, usuario_capa AS capa
                  FROM usuarios 
                  WHERE usuario_display LIKE '$termo' 
                  OR usuario_user LIKE '$termo' ORDER BY usuario_display ASC
                  LIMIT 12";
    $resultado = $this->conn->query($seleciona);
    $usuarios = [];
    if($resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            $usuarios[] = [
                "nome"=>$dado["display"],
                "user"=>$dado["user"],
                "foto"=>$dado["foto"]
                ];
        }
    }
    
    
    
    return ["sucesso"=>true, "resultado"=>$usuarios, "total"=>$total];
    }
    
    function misto(){
        
    }
    
    function pesquisa(){
        switch($this->acao){
            case 'amigos':
                // Lista e Pesuisa de Amigos
                return $this->amigos();
                break;
            case 'procurar':
                // Lista e Pesquisa por Todos Usuários do Sistema
                return $this->procurar();
                break;
            case 'misto':
                // Procurar por nome
                return $this->misto();
                break;
        }   
    }
    
    function close(){
        $this->conn->close();
    }
}

$procurar = new Procurar();
$resposta = $procurar->pesquisa();
$procurar->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>