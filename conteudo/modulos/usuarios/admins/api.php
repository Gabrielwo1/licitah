<?
header('Content-Type: application/json; charset=utf-8');
session_start();
include __DIR__."/../../../../admin/conn.php";

class Acao{
    public $conn;
    public $acao;
    public $user;
    public $me;
    public $parceiro;
    
    function __construct(){
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->me = $_SESSION["id"] ?? false;
        $this->user = $_POST["user"] ?? false;
        
        $this->parse();
    }
    
    function parse(){
        if(!$this->user){
            return;
        }
        $user = $this->user;
        $seleciona = "SELECT usuario_id  FROM usuarios WHERE usuario_user='$user'";
        $resultado = $this->conn->query($seleciona);

        if($resultado->num_rows == 1){
            $dado = $resultado->fetch_assoc();
            $this->parceiro = $dado["usuario_id"];
        }
    }
    
    function vinculo(){

        if(!$this->me || !$this->parceiro){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessários"];
        }
        
        $me = $this->me;
        $user = $this->parceiro;
        $seleciona = "SELECT * FROM usuarios_vinculos  WHERE (usuarios_vinculo_usuario = $me AND usuarios_vinculo_usuario2 = $user) OR (usuarios_vinculo_usuario = $user AND usuarios_vinculo_usuario2 = $me)";
        $resultado = $this->conn->query($seleciona);
        
        
        $vinculos = [];
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                if(!isset($vinculos[$dado["usuarios_vinculo_tipo"]])){
                    $vinculos[$dado["usuarios_vinculo_tipo"]] = [];
                }
                
        
                array_push($vinculos[$dado["usuarios_vinculo_tipo"]] , [
                    "um"=>$dado["usuarios_vinculo_usuario"],
                    "dois"=>$dado["usuarios_vinculo_usuario2"],
                    "aprovado"=>$dado["usuarios_vinculo_aprovado"]
                    ]);
            }
        }
        return ["sucesso"=>true, "vinculos"=>$vinculos];

    }
    
    function isBlocked(){
        $me = $this->me;
        $user = $this->parceiro;
        $seleciona = "SELECT * FROM usuarios_vinculos  WHERE ((usuarios_vinculo_usuario = $me AND usuarios_vinculo_usuario2 = $user) OR (usuarios_vinculo_usuario = $user AND usuarios_vinculo_usuario2 = $me)) AND usuarios_vinculo_tipo='5'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            return true;
        }
        return false;
    }
    
    function vincular(){
        $vinculo = $_POST["vinculo"] ?? false;
        
        $me = $this->me;
        $user = $this->parceiro;
        
        if(!$this->me || !$this->parceiro){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessários"];
        }
        
        if($this->me == $this->parceiro){
            return ["erro"=>true, "mensagem"=>"Um usuário não pode interagir com sigo mesmo"];
        }
        
        
        switch($vinculo){
            case 'seguir':
                if($this->isBlocked()){
                    return ["erro"=>true, "mensagem"=>"A ação não é permitida, pois existe um bloquei de algum dos usuários"];
                }
                
                
                $seleciona = "SELECT * FROM usuarios_vinculos WHERE usuarios_vinculo_usuario='$me' AND	usuarios_vinculo_usuario2='$user' AND usuarios_vinculo_tipo='2'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    $cadastra = "INSERT INTO usuarios_vinculos (usuarios_vinculo_usuario, usuarios_vinculo_usuario2, usuarios_vinculo_tipo) VALUES ('$me', '$user', '2')";
                    $this->conn->query($cadastra);
                }
                return ["sucesso"=>true, "mensagem"=>"Usuário sendo seguido com sucesso"];
                break;
            case 'desseguir':
                if($this->isBlocked()){
                    return ["erro"=>true, "mensagem"=>"A ação não é permitida, pois existe um bloquei de algum dos usuários"];
                }
                
                $deleta = "DELETE FROM usuarios_vinculos WHERE usuarios_vinculo_usuario='$me' AND	usuarios_vinculo_usuario2='$user' AND usuarios_vinculo_tipo='2'";
                $this->conn->query($deleta);
                return ["sucesso"=>true, "mensagem"=>"Usuário deixado de seguir com sucesso"];
                break;
            case 'amizade':
                 if($this->isBlocked()){
                    return ["erro"=>true, "mensagem"=>"A ação não é permitida, pois existe um bloquei de algum dos usuários"];
                }
                $seleciona = "SELECT * FROM usuarios_vinculos  WHERE ((usuarios_vinculo_usuario = $me AND usuarios_vinculo_usuario2 = $user) OR (usuarios_vinculo_usuario = $user AND usuarios_vinculo_usuario2 = $me)) AND usuarios_vinculo_tipo='1'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    $cadastra = "INSERT INTO usuarios_vinculos (usuarios_vinculo_usuario, usuarios_vinculo_usuario2, usuarios_vinculo_aprovado, usuarios_vinculo_tipo) VALUES ('$me', '$user', '0', '1')";
                    $this->conn->query($cadastra);
                    return ["sucesso"=>true, "mensagem"=>"Pedido de amizade enviado com sucesso"];
                }else{
                    $dado = $resultado->fetch_assoc();
                    $um = $dado["usuarios_vinculo_usuario"];
                    $dois = $dado["usuarios_vinculo_usuario2"];
                    $aprovado = intval($dado["usuarios_vinculo_aprovado"]);
                    $id = $dado["usuarios_vinculo_id"];
                    if($aprovado == 0 && $dois == $me){
                        $atualizar = "UPDATE usuarios_vinculos SET usuarios_vinculo_aprovado='1' WHERE usuarios_vinculo_id='$id'";
                        $this->conn->query($atualizar);
                        return ["sucesso"=>true, "mensagem"=>"Pedido de amizade aceito"];
                    }
                    
                }
                return ["sucesso"=>true, "mensagem"=>"Nenhuma ação de amizade necessaria"];
    
                break; 
            case 'desfazer-amizade':
                $seleciona = "SELECT * FROM usuarios_vinculos  WHERE ((usuarios_vinculo_usuario = $me AND usuarios_vinculo_usuario2 = $user) OR (usuarios_vinculo_usuario = $user AND usuarios_vinculo_usuario2 = $me)) AND usuarios_vinculo_tipo='1'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $id = $dado["usuarios_vinculo_id"];
                    
                    $deleta = "DELETE FROM usuarios_vinculos WHERE usuarios_vinculo_id='$id'";
                    $this->conn->query($deleta);
                    return ["sucesso"=>true, "mensagem"=>"Pedido de amizade deletado"];
                }
                return ["sucesso"=>true, "mensagem"=>"Nenhuma ação necessaria"];

                break;
            case 'bloquear':
                $seleciona = "SELECT * FROM usuarios_vinculos WHERE usuarios_vinculo_usuario='$me' AND	usuarios_vinculo_usuario2='$user' AND usuarios_vinculo_tipo='5'";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 0){
                    $cadastra = "INSERT INTO usuarios_vinculos (usuarios_vinculo_usuario, usuarios_vinculo_usuario2, usuarios_vinculo_tipo) VALUES ('$me', '$user', '5')";
                    $this->conn->query($cadastra);
                }
                 return ["sucesso"=>true, "mensagem"=>"Usuário bloqueado com sucesso"];
                
                break;
            case 'desbloquear':
                break;
        }
        
        
    }
    
    function init() {
    if (!$this->parceiro) {
        return ["erro" => true, "mensagem" => "Um usuário não pode interagir com sigo mesmo"];
    }

    $user = $this->parceiro;

    // Consulta otimizada para obter o número de "seguindo" e "seguidores" em uma única query
    $seleciona = "
        SELECT
            (SELECT count(*) FROM usuarios_vinculos WHERE usuarios_vinculo_usuario = ? AND usuarios_vinculo_tipo = '2') AS seguindo,
            (SELECT count(*) FROM usuarios_vinculos WHERE usuarios_vinculo_usuario2 = ? AND usuarios_vinculo_tipo = '2') AS seguidores
    ";

    // Preparar a consulta
    $stmt = $this->conn->prepare($seleciona);
    $stmt->bind_param("ii", $user, $user); // Duas vezes o mesmo parâmetro ($user)
    $stmt->execute();

    // Obter o resultado
    $resultado = $stmt->get_result();
    $dado = $resultado->fetch_assoc();

    // Retorna os dados de "seguindo" e "seguidores"
    return [
        "sucesso" => true,
        "numeros" => [
            "seguindo" => $dado["seguindo"],
            "seguidores" => $dado["seguidores"]
        ]
    ];
}

    function amigos(){
    $me = $this->me;
                      $amigos = [];
                      $friends = [];
    $seleciona = "SELECT usuarios_vinculo_usuario, usuarios_vinculo_usuario2 
                  FROM usuarios_vinculos  
                  WHERE ((usuarios_vinculo_usuario = '$me') OR (usuarios_vinculo_usuario2 = '$me')) 
                  AND usuarios_vinculo_tipo = '1' 
                  AND usuarios_vinculo_aprovado = '1'";

          $resultado = $this->conn->query($seleciona);
         if ($resultado && $resultado->num_rows > 0) {
             while ($dado = $resultado->fetch_assoc()) {
                 $amigo = ($dado["usuarios_vinculo_usuario"] != $me) ? $dado["usuarios_vinculo_usuario"] : $dado["usuarios_vinculo_usuario2"];
                 $amigos[] = $amigo;
                 
             }
            
         }
         
   if(!empty($amigos)){
             $amigos = implode(",", $amigos);
             $seleciona = "SELECT 	usuario_id as id, usuario_display as display, usuario_user as user , usuario_foto as foto , usuario_capa as capa FROM usuarios WHERE usuario_id IN ($amigos)";
             $resultado = $this->conn->query($seleciona);
             if($resultado->num_rows > 0){
                 while($dado = $resultado->fetch_assoc()){
                    array_push($friends, [
                        "i"=>$dado["id"],
                        "d"=>$dado["display"],
                        "u"=>$dado["user"],
                        "f"=>$dado["foto"],
                        "c"=>$dado["capa"]
                        ]);   
                 }
             }
             
         }
         
         return ["sucesso"=>true, "resultados"=>$friends];
                 
    }
    
    function procurar() {
        $termo = $_POST["termo"] ?? false;
        
        if (!$termo) {
            return $this->amigos();
            
        }
    
    $termo = '%' . addslashes($termo) . '%';
    

    $seleciona = "SELECT usuario_id AS id, usuario_display AS display, usuario_user AS user, 
                  usuario_funcao AS funcao, usuario_foto AS foto, usuario_capa AS capa
                  FROM usuarios 
                  WHERE usuario_display LIKE '$termo' 
                  OR usuario_user LIKE '$termo' 
                  LIMIT 12";
    
        $friends = [];
     $resultado = $this->conn->query($seleciona);
             if($resultado->num_rows > 0){
                 while($dado = $resultado->fetch_assoc()){
                    array_push($friends, [
                        "i"=>$dado["id"],
                        "d"=>$dado["display"],
                        "u"=>$dado["user"],
                        "f"=>$dado["foto"],
                        "c"=>$dado["capa"]
                        ]);   
                 }
             }
    return ["sucesso"=>true, "resultados"=>$friends];

    
}

    function userInfo(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um usuário válido"];
        }
        $user = $this->user;
        $seleciona = "SELECT  usuario_id as id, usuario_display AS display, usuario_user AS user, 
                  usuario_capa AS capa, usuario_foto AS foto
                  FROM usuarios 
                  WHERE usuario_user = '$user'"; 

                  
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"O usuário definido não é válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        $id = $dado["id"];
        $seleciona = "SELECT count(*) as total FROM usuarios_vinculos WHERE usuarios_vinculo_usuario2='$id' AND usuarios_vinculo_tipo='2'";

        $resultado = $this->conn->query($seleciona);
        $info = $resultado->fetch_assoc();
        $seguidores =  $info["total"];
        
        $seleciona = "SELECT count(*) as total FROM usuarios_vinculos WHERE usuarios_vinculo_usuario='$id' AND usuarios_vinculo_tipo='2'";
        $resultado = $this->conn->query($seleciona);
        $info = $resultado->fetch_assoc();
        $seguindo =  $info["total"];
        
        return ["sucesso"=>true, "user"=>[
                        "d"=>$dado["display"],
                        "u"=>$dado["user"],
                        "f"=>$dado["foto"],
                        "c"=>$dado["capa"],
                        "seguidores"=>$seguidores,
                        "seguindo"=>$seguindo
                        ]
                        ];
                  
                  
    }
    
    function idsToUsers($lista, $chave){
    $array = [];
    
    // Verifica se a lista de IDs está vazia
    if(empty($lista)){
        return $array;
    }
    
    // Transforma o array de IDs em uma string separada por vírgulas
    $ids = implode(",", array_map('intval', $lista)); // Sanitiza os IDs para evitar SQL injection
    
    // Monta a query para buscar os dados dos usuários
    $seleciona = "SELECT usuario_id, $chave FROM usuarios WHERE usuario_id IN ($ids)";
    $resultado = $this->conn->query($seleciona);

    // Verifica se a query foi bem-sucedida
    if($resultado && $resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            $array[$dado["usuario_id"]] = $dado; // Associa o ID do usuário ao resto dos dados
        }
    }

    return $array;
}

    function top(){
    // Query para obter os 10 usuários com mais seguidores (vinculo tipo 2)
    $seleciona = "
    SELECT usuarios_vinculo_usuario2, COUNT(*) as total_seguidores
    FROM usuarios_vinculos
    WHERE usuarios_vinculo_tipo = '2'
    GROUP BY usuarios_vinculo_usuario2
    ORDER BY total_seguidores DESC
    LIMIT 12";
    
    $resultado = $this->conn->query($seleciona);
    
    // Inicializa arrays para armazenar IDs e a contagem de seguidores
    $ids = [];
    $maps = [];
    
    // Verifica se a query foi bem-sucedida
    if($resultado && $resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            $ids[] = $dado["usuarios_vinculo_usuario2"]; // Armazena o ID do usuário
            $maps[$dado["usuarios_vinculo_usuario2"]] = $dado["total_seguidores"]; // Mapeia o total de seguidores
        }
    }
    
    // Busca os detalhes dos usuários com base nos IDs obtidos
    $usuarios = $this->idsToUsers($ids, "usuario_display as nome, usuario_user as user, usuario_foto as foto");
    
    // Combina os dados de seguidores e informações dos usuários
    $resultadoFinal = [];
    foreach($ids as $id){
        if(isset($usuarios[$id])){ 
            $resultadoFinal[] = array_merge($usuarios[$id], ['total_seguidores' => $maps[$id]]);
        }
    }

    return ["sucesso"=>true, "lista"=>$resultadoFinal];
}

    function close(){
        $this->conn->close();
    }

    function render(){
        switch($this->acao){
            case 'init':
                return $this->init();
                break;
            case 'vinculo':
                return $this->vinculo();
                break;
            case 'vincular':
                return $this->vincular();
                break;
            case 'amigos':
                return $this->amigos();
                break;
            case 'procurar':
                return $this->procurar();
                break;
            case 'userInfor':
                return $this->userInfo();
                break;
            case 'top':
                return $this->top();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é válida"];
                break;
        }
    }
}




$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>