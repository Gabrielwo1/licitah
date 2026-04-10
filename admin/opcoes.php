<?
header('Content-Type: application/json; charset=utf-8');
session_start();

include __DIR__."/conn.php";

/*
1 - tipo banco de dados
2 - tipo elemento hmtl da pasta conteudo

*/

class Acao{
    public $tipo;
    public $foco;
    public $usuario;
    public $modulo;
    public $formulario;
    public $regra;
    public $coluna;
    public $grupo;
    private $conn;
    private $big;
    private $termo;
    private $memoria;
    private $getSize;
    function __construct(){
        $this->tipo = $_POST["tipo"] ?? false;
        $this->foco = $_POST["foco"] ?? false;
        $this->usuario = $_SESSION["id"] ?? false;
        $this->coluna = $_POST["coluna"] ?? false;
        $this->grupo = $_POST["grupo"] ?? false;
        $this->conn = conn();
        $this->big = $_POST["big"] ?? false;
        $this->termo = $_POST["termo"] ?? false;
        $this->memoria = $_POST["memoria"] ?? false;
        $this->getSize = $_POST["getSize"] ?? false;
        
    }
    
    function opcaoDinamica(){
         
     
        if(is_dir(__DIR__."/../conteudo/modulos/".$this->modulo)){
            if(file_exists(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/configs/".$this->formulario)){
                $conteudo = file_get_contents(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/configs/".$this->formulario);
                $array = json_decode($conteudo, true);
                
                $banco = $array["banco"];
                $prefixo = $array["prefixo"];
                $name = false;
                
             
                if($this->getSize){
                    $seleciona = "SELECT COUNT(*) as total FROM {$banco}";
                    $resultado = $this->conn->query($seleciona);
                    $dado = $resultado->fetch_assoc();
                    $total = $dado["total"];
                    if($total > 100){
                        return ["sucesso"=>true, "big"=>true];
                    }
                    
                }
                
                
                
                
                if(!$this->coluna){
                    foreach($array["colunas"] as $coluna){
                    if($coluna["nome"] == "nome"){
                        $name = "nome";
                        break;
                    }
                    
                    if($coluna["nome"] == "titulo"){
                        $name = "titulo";
                        break;
                    }
                }
                
                    if(!$name){
                    $name = $array["colunas"][0]["nome"];
                }
                
                
                        
                }else{
                    $name = $this->coluna;
                    
                }
                
                $valor = $prefixo."_id";
                
                
                $chave = $prefixo."_".$name;
                if($this->big){
                    $termo = $this->termo ?? false;
                    if(!$termo){
                        if(!$this->memoria){
                            return ["erro"=>true, "mensagem"=>"Não foi enviado um termo válido"];
                        }
                        
                        
                        $memoria = json_decode($this->memoria, true);
                        $l = implode(",", $memoria);
                        $seleciona = "SELECT $valor, $chave FROM $banco WHERE $valor IN ({$l})";
                       
                        
                    }else{
                        $seleciona = "SELECT $valor, $chave FROM $banco WHERE {$chave} LIKE '%{$termo}%' LIMIT 10";
                    }
                    
                  
                }else{
                    $seleciona = "SELECT $valor, $chave FROM $banco";
                }
                

                $resultado = $this->conn->query($seleciona);
                $lista = [];
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $lista[$dado[$chave]] = $dado[$valor];
                    }
                }
                $retorno = ["sucesso"=>true, "lista"=>$lista];
                if($this->grupo){
                    $retorno["grupo"] = $this->grupo;
                }
                
                return $retorno;
                
            }else{
                return ["erro"=>true, "mensagem"=>"O formulário não existe"]; 
            }
        }else{
            return ["erro"=>true, "mensagem"=>"O módulo não existe"];
        }

    }
    
    function render(){
        if(!$this->usuario){
            return ["erro"=>true, "mensagem"=>"Essa funcionalidade é exclusiva do sistema"];
        }
        
        if(!$this->foco || !$this->tipo){
                 return ["erro"=>true, "mensagem"=>"É necessário enviar um foco e tipo válidos"];
        }
        
       
        
        if($this->tipo){
            switch(intval($this->tipo)){
                case 1:
                    // Direto ao banco
                 
                    $mapa = explode("," , $this->foco);
                
                    $banco = $mapa[0];
                    $valor = $mapa[1];
                    $chave = $mapa[2];
                 
                    $seleciona = "SELECT $valor, $chave FROM $banco";

                    $resultado = $this->conn->query($seleciona);
                    $lista = [];
                    if($resultado->num_rows > 0){
                        while($dado = $resultado->fetch_assoc()){
                            $lista[$dado[$chave]] = $dado[$valor];
                        }
                    }
                    return ["sucesso"=>true, "lista"=>$lista];
                    break;
                case 2:
                    // Componente html
                    break;
                case 3:
                    // Modulo dinamico
                    $array = json_decode($this->foco, true);
                    $this->modulo = $array["modulo"] ?? false;
                    $this->formulario = $array["formulario"] ?? false;
                    $this->regra = $array["regra"] ?? false;
                    if($this->modulo && $this->formulario && $this->regra){
                        return $this->opcaoDinamica();
                    }else{
                         return ["erro"=>true, "mensagem"=>"As informaçõs de informação dinamica estão faltando"];
                    }
                    break;
            }
        }
        
        return ["erro"=>true, "mensagem"=>"Alguma informação passada não é válida"];
    }
    
    function close(){
        $this->conn->close();
    }
}




$acao = new Acao();
$resposta = $acao->render();
$acao->close();

echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

?>