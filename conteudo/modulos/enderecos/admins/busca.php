<?
header('Content-Type: application/json; charset=utf-8');


include __DIR__."/../../../../admin/conn.php";

class Busca{
    private $conn;
    private $texto;
    private $acao;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        $this->texto = $_POST["texto"] ?? false;
        $this->conn = conn();
    }
    
    function trata($string) {
    // Mapeamento de caracteres acentuados para sem acento
    $acentos = array(
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c', 'Ç' => 'c',
        ' ' => '-', // Substitui espaços por traços
    );

    // Substitui os acentos usando strtr
    $string = strtr($string, $acentos);
    
    // Remove caracteres especiais e substitui espaços múltiplos por um único traço
    $string = preg_replace('/[^a-z0-9-]/i', '', $string); // Remove caracteres não alfanuméricos

    // Remove espaços em excesso (considerando espaços simples entre palavras)
    $string = preg_replace('/-+/', '-', $string); // Remove traços duplicados
    
    // Converte para minúsculas
    $string = strtolower($string);
    
    // Remove traços no início e no final da string
    $string = trim($string, '-');

    return $string;
}

    function pegaEstado($id){
        $seleciona = "SELECT * FROM enderecos_estados WHERE endereco_estado_id = '$id'";
        
        $resultado = $this->conn->query($seleciona);
         
        if($resultado->num_rows == 1){
            return $resultado->fetch_assoc()['endereco_estado_codigo'];
        }
        
        return '';
    }
    
    function procuraCidade(){
        if(!$this->texto){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um texto válido"];
        }
        
        
        $texto = $this->trata($this->texto);
        
        // limitado somente ao brasil
        
        $seleciona = "SELECT * FROM enderecos_cidades WHERE  endereco_cidade_pais = '31' AND endereco_cidade_slug LIKE '%{$texto}%' LIMIT 10";

        $resultado = $this->conn->query($seleciona);
        
        $lista = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $lista[] = [
                    "n"=> $dado["endereco_cidade_nome"],
                    "e"=> strtolower($this->pegaEstado($dado["endereco_cidade_estado"])),
                    "u"=> $dado["endereco_cidade_slug"]
                ];
            }
        }
        
        return ["sucesso"=>true, "lista"=>$lista];
    }
    
    function render(){
        switch($this->acao){
            case 'buscacidade':
                return $this->procuraCidade();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A açaõ enviada não é válida"];
                break;
        }
    }
}

   
   
$busca = new Busca();
$resposta = $busca->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>