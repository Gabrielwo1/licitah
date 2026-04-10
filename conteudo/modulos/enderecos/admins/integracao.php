<?


class AdressParse{
    private $acao;
    private $cidade;
    private $estado;
    private $pais;
    private $latitude;
    private $longitude;
    private $raio;
    private $conn;
    
    function __construct($pais = false, $estado = false, $cidade = false){
        $this->acao = $_POST["acao"] ?? false;
        $this->cidade = $cidade ?? false;
        $this->estado = $estado ?? false;
        $this->pais = $pais ?? false;
        $this->latitude = $_POST["latitude"] ?? false;
        $this->longitude = $_POST["longitude"] ?? false;
        $this->raio = $_POST["raio"] ?? false;
        $this->conn = conn();
    }
    
    function urllify($string) {
        if (is_null($string) || trim($string) === '') {
            return '';
        }
        
        $string = (string) $string;
        $string = mb_strtolower($string, 'UTF-8');
        $string = preg_replace('/[áàâãäå]/u', 'a', $string);
        $string = preg_replace('/[éèêë]/u', 'e', $string);
        $string = preg_replace('/[íìîï]/u', 'i', $string);
        $string = preg_replace('/[óòôõö]/u', 'o', $string);
        $string = preg_replace('/[úùûü]/u', 'u', $string);
        $string = preg_replace('/[ç]/u', 'c', $string);
        $string = preg_replace('/[ñ]/u', 'n', $string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
    
    function parse(){

        $estado = false;
        $cidade = false;
        
        $retorno = false;
        
        $pais = $this->urllify(!$this->pais ? "Brasil" : $this->pais);
        
        switch(strlen($pais)){
            case 2:
                $chave = "enderecos_pais_iso2";
                break;
            case 3:
                $chave = "enderecos_pais_iso3";
                break;
            default:
                $chave = "enderecos_pais_slug";
                break;
        }
        
        $seleciona = "SELECT enderecos_pais_id FROM enderecos_paises WHERE {$chave}='{$pais}'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado um país válido"];
        }
        
        $dado = $resultado->fetch_assoc();
        $pais = $dado["enderecos_pais_id"];

        
        
        
        if($this->estado){
            $estado = $this->urllify($this->estado);
            $codigo = strtoupper($estado);
            $seleciona = "SELECT * FROM enderecos_estados WHERE endereco_estado_pais='{$pais}' AND (endereco_estado_slug='$estado' OR endereco_estado_codigo='{$codigo}')";
            $resultado = $this->conn->query($seleciona);
            if($resultado->num_rows == 1){
                $dado = $resultado->fetch_assoc();
                $estado = $dado["endereco_estado_id"];
                $retorno = ["tipo"=>"estado", "id"=>$estado];
            }
        }
        
        if($this->cidade){
            $cidade = $this->urllify($this->cidade);
            
            if($estado){
                 $seleciona = "SELECT * FROM enderecos_cidades WHERE endereco_cidade_slug='$cidade' AND endereco_cidade_estado='{$estado}'";
            }else{
               $seleciona = "SELECT * FROM enderecos_cidades WHERE endereco_cidade_slug='$cidade'"; 
            }
            $resultado = $this->conn->query($seleciona);
            
            
            switch($resultado->num_rows){
                case 0:
                    $retorno = false;
                    break;
                case 1:
                    $dado = $resultado->fetch_assoc();
                    $retorno = ["tipo"=>"cidade", "id"=>$dado["endereco_cidade_id"], "pais"=>$dado["endereco_cidade_pais"], "estado"=>$dado["endereco_cidade_estado"]];
                    break;
                default:
                    return ["erro"=>true, "mensagem"=>"Existe mais uma cidade com essa nome, seja mais especifico, enviado o Estado"];
                    break;
            }
        }
        
        if(!$retorno){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado local com os parametros enviados"];
        }
        $retorno["sucesso"] = true;
        return $retorno;
    }
    
    function localizacao(){
        $id = $this->parse();
        if(isset($id["erro"])){
            return $id;
        }
        
        $chave = $id["tipo"];
        $id = $id["id"];
        $seleciona = "
        SELECT e.*
        FROM enderecos AS e
        JOIN enderecos_associacoes AS ea 
        ON ea.endereco_associacao_endereco = e.endereco_id
        WHERE e.endereco_{$chave} = '{$id}'
        AND ea.endereco_associacao_banco = 'usuarios'
        ";

        $resultado = $this->conn->query($seleciona);
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                echo '<pre>';
                print_r($dado);
                echo '</pre>';
            }
        }
        echo $resultado->num_rows;
    }
    
    function raio(){
        
    }
    
    function render(){
        switch($this->acao){
            case 'localizacao':
                return $this->localizacao();
                break;
            case 'raio':
                return $this->raio();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Nenhuma ação envida é válida"];
                break;
        }
    }
}

?>