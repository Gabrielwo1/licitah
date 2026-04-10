<?
session_start();

include __DIR__."/../../../../admin/conn.php";

class Acao{
    private $user;
    private $acao;
    private $conn;
    function __construct(){
        $this->user = $_SESSION["id"] ?? false;
        $this->acao = $_POST["acao"] ?? false;
        $this->conn = conn();
     }
    
    function listar($id = false) {
        // Primeiro, busca os IDs dos endereços associados ao usuário atual
        if(!$id){
                $seleciona = "SELECT endereco_id as id 
                     FROM enderecos 
                     LEFT JOIN enderecos_associacoes ON enderecos.endereco_id = enderecos_associacoes.endereco_associacao_endereco 
                     WHERE enderecos.endereco_autor = '{$this->user}' 
                     AND enderecos_associacoes.endereco_associacao_banco = 'enderecos'";
        }else{
                $seleciona = "SELECT endereco_id as id 
                     FROM enderecos 
                     LEFT JOIN enderecos_associacoes ON enderecos.endereco_id = enderecos_associacoes.endereco_associacao_endereco 
                     WHERE enderecos.endereco_url = '{$id}' AND enderecos.endereco_autor = '{$this->user}'";
        }
    
        
        $resultado = $this->conn->query($seleciona);
        $ids = [];
        
        if ($resultado->num_rows > 0) {
            while ($dado = $resultado->fetch_assoc()) {
                $ids[] = $dado["id"];
            }
        }
        
        $enderecos = array(); // Array para armazenar todos os endereços
        
        if (!empty($ids)) {
            $lista = implode(",", $ids);
            
            // Consulta para buscar informações dos endereços
            $seleciona = "SELECT 
                            e.endereco_id,
                            e.endereco_nome,
                            e.endereco_rua,
                            e.endereco_url,
                            e.endereco_bairro,
                            e.endereco_numero,
                            e.endereco_latitude,
                            e.endereco_longitude,
                            c.endereco_cidade_nome AS cidade,
                            es.endereco_estado_nome AS estado,
                            es.endereco_estado_codigo AS uf
                         FROM 
                            enderecos e
                         LEFT JOIN 
                            enderecos_cidades c ON e.endereco_cidade = c.endereco_cidade_id
                         LEFT JOIN 
                            enderecos_estados es ON e.endereco_estado = es.endereco_estado_id
                         WHERE 
                            e.endereco_id IN ({$lista})
                         ORDER BY 
                            e.endereco_nome ASC";
            
            $resultado = $this->conn->query($seleciona);
            
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    // Formatar o endereço no formato solicitado
                    $endereco = [
                        "nome" => $row['endereco_nome'],
                        "rua" => $row['endereco_rua'],
                        "url" => $row['endereco_url'],
                        "cidade" => $row['cidade'],
                        "estado" => $row['estado'],
                        "uf" => $row['uf'],
                        "bairro" => $row['endereco_bairro'],
                        "numero" => $row['endereco_numero'],
                        "latitude" => $row['endereco_latitude'],
                        "longitude" => $row['endereco_longitude']
                    ];
                    
                    $enderecos[] = $endereco;
                }
            }
        }
        
        return ["sucesso"=>true, "enderecos"=>$enderecos, "selecionado"=>$_SESSION["endereco"] ?? false];
    }

    private function formatarEndereco($endereco) {
        $partes = array();
        
        // Adiciona o nome do endereço (se existir)
        if (!empty($endereco['endereco_nome'])) {
            $partes[] = $endereco['endereco_nome'];
        }
        
        // Adiciona rua e número
        $ruaNumero = $endereco['endereco_rua'];
        if (!empty($endereco['endereco_numero'])) {
            $ruaNumero .= ', ' . $endereco['endereco_numero'];
        }
        $partes[] = $ruaNumero;
        
        // Adiciona complemento (se existir)
        if (!empty($endereco['endereco_complemento'])) {
            $partes[] = $endereco['endereco_complemento'];
        }
        
        // Adiciona bairro (se existir)
        if (!empty($endereco['endereco_bairro'])) {
            $partes[] = $endereco['endereco_bairro'];
        }
        
        // Adiciona cidade/UF
        $cidadeUF = '';
        if (!empty($endereco['cidade_nome'])) {
            $cidadeUF .= $endereco['cidade_nome'];
        }
        if (!empty($endereco['estado_uf'])) {
            $cidadeUF .= ' - ' . $endereco['estado_uf'];
        }
        if (!empty($cidadeUF)) {
            $partes[] = $cidadeUF;
        }
        
        // Adiciona CEP (se existir)
        if (!empty($endereco['endereco_cep'])) {
            $partes[] = 'CEP: ' . $endereco['endereco_cep'];
        }
        
        return implode(', ', $partes);
    }
    
    function close(){
                $this->conn->close();
    }
    
    function pegaEndereco(){
        $hash = $_POST["hash"] ?? false;
        if(!$hash){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um hash válido"];
        }
        $listar = $this->listar($hash);
        if(isset($lista["erro"])){
            return $lista;
        }
        $enderecos = $listar["enderecos"];
        if(count($enderecos) == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrados endereços válidos"];
        }
        $_SESSION["endereco"] = $hash;
        return ["sucesso"=>true, "endereco"=>$enderecos[0]];
    }
    
    function nova(){
        if(empty($_POST['cep'])){
            return ['erro'=> true, 'mensagem'=> 'CEP não informado'];
        }
        
        
        include __DIR__.'/endereco.php';
        
        $endereco = new Endereco($_POST['cep']);
        
        $endereco->api();
        
        $idEndereco = $endereco->idcadastrado;
        $autor = $this->user;
        if($idEndereco){
            $cadastra = "INSERT INTO enderecos_associacoes (endereco_associacao_endereco,endereco_associacao_banco,endereco_associacao_identificador,endereco_associacao_autor) VALUES ('$idEndereco', 'enderecos', '$autor', '$autor')";
            $this->conn->query($cadastra);
            
            
            $selecionado = "SELECT endereco_url FROM enderecos WHERE endereco_id = '$idEndereco'";
            
            $resultado = $this->conn->query($selecionado);
            
            if($resultado->num_rows == 1){
                $url = $resultado->fetch_assoc()['endereco_url'];
                return ['sucesso'=> true, 'mensagem'=> 'Endereço cadastrado', 'endereco'=> $url];
            }else{
                return ['erro'=> true, 'mensagem'=> 'Falha ao pegar endereço'];
            }
            
        }
        
        return ['erro'=> true, 'mensagem'=> 'Falha ao cadastrar endereço'];

    }
    
    function render(){
        if(!$this->user){
            return ["erro"=>true, "mensagem"=>"Ação não permitida para usuários deslogados"];
        }
        
        if(!$this->acao){
            return ["erro"=>true, "mensagem"=>"Não foi definida uma ação"];
        }
        
        switch($this->acao){
            case 'listar':
                $hash = false;
                if(!empty($_POST['hash'])){
                    $hash = $_POST['hash'];
                }
                return $this->listar($hash);
                break;
            case 'pegaEndereco':
                return $this->pegaEndereco();
                break;
            case 'nova':
                return $this->nova();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi enviada uma ação válida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();
$acao->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>