<?


include __DIR__.'/../../../../admin/conn.php';


class Api{
    public $acao;
    public $conn;
    public $plano;
    
    function __construct(){
        $this->acao = $_POST['acao'] ?? false;
        $this->conn = conn();
        $this->plano = $_POST['plano'] ?? false;
    } 
    
    function users(){
        if(!$this->plano){
            return ['erro'=> true, 'mensagem'=> 'Plano não foi passado'];
        }
        
        $plano = $this->plano;
        
        $sql = "SELECT plano_vendavel FROM planos WHERE plano_id = '$plano'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows != 1){
            return ['erro'=> true, 'mensagem'=> 'Plano não foi achado'];
        }
        
        $plano = $resultado->fetch_assoc()['plano_vendavel'];
        
        $sql = "SELECT payassi_usuario as user FROM pay_assinaturas WHERE payassi_plano = '$plano'";
        
        $resultado = $this->conn->query($sql);
        
        if($resultado->num_rows > 0){
            $usuarios = [];
            while($dado = $resultado->fetch_assoc()){
                $usuarios[] = $dado['user'];
                
            }
        
            $implode = implode(',', $usuarios);
            $sql2 = "SELECT * FROM usuarios WHERE usuario_id IN ({$implode})";
            
            $resultado2 = $this->conn->query($sql2);
            
            $usuarios = [];
            while($user = $resultado2->fetch_assoc()){
                $usuarios[$user['usuario_id']] = [
                    'email' => $user['usuario_email'],
                    'nome' => $user['usuario_display'],
                    'cpf' => $user['usuario_cpf'],
                    'telefone' => $user['usuario_telefone'],
                    'enderecos' => []
                ];
            }
            
            
            
            $sql3 = "SELECT * FROM enderecos WHERE endereco_autor IN ({$implode})";
            
            $resultado3 = $this->conn->query($sql3);
            
            $enderecos = [];
            $paisesIds = [];
            $cidadesIds = [];
            $estadosIds = [];
            
            while ($endereco = $resultado3->fetch_assoc()) {

                if (!in_array($endereco['endereco_pais'], $paisesIds)) {
                    $paisesIds[] = $endereco['endereco_pais'];
                }
                if (!in_array($endereco['endereco_cidade'], $cidadesIds)) {
                    $cidadesIds[] = $endereco['endereco_cidade'];
                }
                if (!in_array($endereco['endereco_estado'], $estadosIds)) {
                    $estadosIds[] = $endereco['endereco_estado'];
                }
                
                $enderecos[] = [
                    'rua'=> $endereco['endereco_rua'],
                    'cep'=> $endereco['endereco_cep'],
                    'bairro'=> $endereco['endereco_bairro'],
                    'pais'=> $endereco['endereco_pais'],
                    'cidade'=> $endereco['endereco_cidade'],
                    'estado'=> $endereco['endereco_estado'],
                    'complemento'=> $endereco['endereco_complemento'],
                    'numero'=> $endereco['endereco_numero'],
                    'observacao'=> $endereco['endereco_observacao'],
                    'autor'=> $endereco['endereco_autor'],
                ];
            }
            
            $paises = $this->pegarPaises($paisesIds);
            $cidades = $this->pegarCidades($cidadesIds);
            $estados = $this->pegarEstados($estadosIds);
            // print_r($paises);
            // print_r($enderecos);
            foreach ($enderecos as &$endereco) {
                $endereco['pais'] = $paises[$endereco['pais']] ?? 'Desconhecido';
                $endereco['cidade'] = $cidades[$endereco['cidade']] ?? 'Desconhecido';
                $endereco['estado'] = $estados[$endereco['estado']] ?? 'Desconhecido';
            }
            
            foreach ($enderecos as $endereco) {
                if (isset($usuarios[$endereco['autor']])) {
                    $usuarios[$endereco['autor']]['enderecos'][] = $endereco;
                }
            }
            
             $usuariosOrdenados = array_values($usuarios); // Reindexar os usuários
            usort($usuariosOrdenados, function ($a, $b) {
                return strcmp($a['nome'], $b['nome']); // Ordenar por nome (ordem alfabética)
            });
    
            return ['sucesso'=> true, 'lista'=> $usuariosOrdenados];
        }else{
            return ['erro'=> true, 'mensagem'=> 'Nenhum usuário foi identificado nesse plano'];
        }
    }
    
    function pegarPaises($ids){
        if (empty($ids)) return [];
        
        $idsImplode = implode(',', $ids);
        $sql = "SELECT enderecos_pais_id as id , enderecos_pais_nome as nome FROM enderecos_paises WHERE enderecos_pais_id IN ({$idsImplode})";
        $resultado = $this->conn->query($sql);
    
        $paises = [];
        while ($pais = $resultado->fetch_assoc()) {
            $paises[$pais['id']] = $pais['nome'];
        }
        return $paises;
    }

    function pegarCidades($ids){
        if (empty($ids)) return [];
        
        $idsImplode = implode(',', $ids);
        $sql = "SELECT endereco_cidade_id as id , endereco_cidade_nome as nome FROM enderecos_cidades WHERE endereco_cidade_id IN ({$idsImplode})";
        $resultado = $this->conn->query($sql);
    
        $cidades = [];
        while ($cidade = $resultado->fetch_assoc()) {
            $cidades[$cidade['id']] = $cidade['nome'];
        }
        return $cidades;
    }

    function pegarEstados($ids){
        if (empty($ids)) return [];
        
        $idsImplode = implode(',', $ids);
        $sql = "SELECT endereco_estado_id as id , endereco_estado_nome as nome FROM enderecos_estados WHERE endereco_estado_id IN ({$idsImplode})";
        $resultado = $this->conn->query($sql);
    
        $estados = [];
        while ($estado = $resultado->fetch_assoc()) {
            $estados[$estado['id']] = $estado['nome'];
        }
        return $estados;
    }
    
    
    function render(){
        if(!$this->acao){
            return ['erro'=> true, 'mensagem'=> 'Ação não foi passada'];
        }
        
        
        switch($this->acao){
            case 'infosUser':
                return $this->users();
                break;
            default:
                return ['erro'=> true, 'mensagem'=> 'Ação não foi encontrada'];
                break;
        }
    }
}

$resposta = new Api();
$acao = $resposta->render();

echo json_encode($acao, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


?>