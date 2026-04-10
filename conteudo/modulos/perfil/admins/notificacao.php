<?
header('Content-Type: application/json; charset=utf-8');

session_start();
include __DIR__."/../../../../admin/conn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Acao{
    public $acao;
    public $user;
    public $conn;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->conn = conn();
    }
    
    function moduloValido($modulo){
        if(!$modulo || !is_dir(__DIR__."/../../".$modulo)){
            return false;
        }
        
        if(!file_exists(__DIR__."/../../".$modulo."/manifest.json")){
            return false;
        }
                
        $conteudo = json_decode(file_get_contents(__DIR__."/../../".$modulo."/manifest.json"), true);
        
        if($conteudo["ativo"] == "false"){
            return false;
        }
        
    
        return $conteudo["name"];
        
    }
    
    function metas($id){
        $seleciona = "SELECT * FROM notificacoes_transacionais_meta WHERE ntm_transacionais='$id'";
        $resultado = $this->conn->query($seleciona);
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[$dado["ntm_chave"]] = $dado["ntm_valor"];
            }
        }
        return $lista;
    }
    
    
    function setupUser($id){
        $user = $this->user;
        $selecione = "SELECT * FROM notificacoes_preferencias WHERE noti_pref_notificacao='$id'	AND noti_pref_user='$user'";
        $resultado = $this->conn->query($selecione);
        if($resultado->num_rows == 0){
            return ["p"=>1, "w"=>1, "e"=>1, "s"=>1];
        }
        
        $dado = $resultado->fetch_assoc();
        return ["p"=>$dado["noti_pref_push"], "w"=>$dado["noti_pref_whatsapp"], "e"=>$dado["noti_pref_email"], "s"=>$dado["noti_pref_sms"]];
    }
    
    function listaNotificacoes(){
        $seleciona = "SELECT * FROM notificacoes_transacionais WHERE transacionais_controlavel='1'";
        $resultado = $this->conn->query($seleciona);
        
        $lista = [];
        
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                
                $modulo = $this->moduloValido($dado["transacionais_modulo"]);
                if($modulo){
                    if(!isset($lista[$modulo])){
                        $lista[$modulo] = [];
                    }
                    
                    $dado = array_merge($dado, $this->metas($dado["transacionais_id"]));
                    //echo intval($dado["transacionais_quem"]);
                    //echo "<hr>";
                    switch(intval($dado["transacionais_quem"])){
                        case 1:
                            if($dado["push"] || $dado["whatsapp"] || $dado["email"] || $dado["sms"]){
                                $lista[$modulo][$dado["descricao"]]["setup"] = ["p"=>$dado["push"], "w"=>$dado["whatsapp"], "e"=>$dado["email"], "s"=>$dado["sms"]];
                                $lista[$modulo][$dado["descricao"]]["user"] = $this->setupUser($dado["transacionais_id"]); 
                            }
                            break;
                        case 2:
                            $usuarios = json_decode($dado["usuarios"]);
                            if (in_array($this->user, $usuarios)){
                                if($dado["push"] || $dado["whatsapp"] || $dado["email"] || $dado["sms"]){
                                    $lista[$modulo][$dado["descricao"]]["setup"] = ["p"=>$dado["push"], "w"=>$dado["whatsapp"], "e"=>$dado["email"], "s"=>$dado["sms"]];
                                     $lista[$modulo][$dado["descricao"]]["user"] = $this->setupUser($dado["transacionais_id"]); 
                                }
                            } 
                            break;
                        case 3:
                            $funcoes = json_decode($dado["funcao"]);
                            $funcao = $_SESSION["funcao"];
                            if (in_array($funcao, $funcoes)){
                                if($dado["push"] || $dado["whatsapp"] || $dado["email"] || $dado["sms"]){
                                    $lista[$modulo][$dado["descricao"]]["setup"] = ["p"=>$dado["push"], "w"=>$dado["whatsapp"], "e"=>$dado["email"], "s"=>$dado["sms"]];
                                    $lista[$modulo][$dado["descricao"]]["user"] = $this->setupUser($dado["transacionais_id"]); 
                                }
                            } 
                            
                            break;
                        case 4:
                            if($dado["push"] || $dado["whatsapp"] || $dado["email"] || $dado["sms"]){
                                $lista[$modulo][$dado["descricao"]]["setup"] = ["p"=>$dado["push"], "w"=>$dado["whatsapp"], "e"=>$dado["email"], "s"=>$dado["sms"]];
                                $lista[$modulo][$dado["descricao"]]["user"] = $this->setupUser($dado["transacionais_id"]); 
                            }
                            break;
                    }
                }
            }
        }
        
        
        foreach ($lista as $modulo => $itens) {
    foreach ($itens as $descricao => $detalhes) {
        if (empty($detalhes["setup"]) || empty($detalhes["user"])) {
            unset($lista[$modulo][$descricao]);
        }
    }
    // Se o módulo estiver vazio após a remoção dos itens, remova o módulo também
    if (empty($lista[$modulo])) {
        unset($lista[$modulo]);
    }
}

        
        
        $conteudo = json_decode(file_get_contents(__DIR__."/../../../setup.json"), true);
        
        
        return ["sucesso"=>true, "lista"=>$lista, "setup"=>$conteudo["notificacoes"]["configuracoes"] ?? []];

    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação não permitida para usuários deslogados"];
        }
        
        
        switch($this->acao){
            case 'minhas':
                return $this->listaNotificacoes();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação definida não é valida"];
                break;
        }
    }
}

$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>