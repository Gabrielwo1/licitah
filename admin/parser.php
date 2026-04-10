<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once __DIR__."/conn.php";



class Parse{
    public $tabela;
    public $url;
    public $conn;
    public $coluna;
    public $id;
    function __construct($tabela = false, $url = false){
        $this->tabela = $tabela;
        $this->url = $url;
        $this->conn = conn();
        $this->coluna = [];
    }
    
    function infoTabela() {
         $tabela = $this->tabela;

        $sqlTabelaExiste = "SHOW TABLES LIKE '$tabela'";
        $resultadoTabela = $this->conn->query($sqlTabelaExiste);

        if ($resultadoTabela->num_rows > 0) {

            $sqlColunas = "SHOW COLUMNS FROM $tabela";
            $resultadoColunas = $this->conn->query($sqlColunas);

            $colunaUrl = null;
            $colunaId = null;
            $colunaAutor = null;

            while ($coluna = $resultadoColunas->fetch_assoc()) {
                if (preg_match('/_url$/', $coluna['Field'])) {
                    $this->coluna["url"] = $coluna['Field'];
                }
                if (preg_match('/_id$/', $coluna['Field'])) {
                    $this->coluna["id"] = $coluna['Field'];
                }
                
                if (preg_match('/_autor$/', $coluna['Field'])) {
                    $this->coluna["autor"] = $coluna['Field'];
                }
                
                 if (preg_match('/_vendavel$/', $coluna['Field'])) {
                    $this->coluna["vendavel"] = $coluna['Field'];
                }
                
            }
            
            if($tabela == "usuarios"){
                  return ['url' => "user", 'id' => $this->coluna["id"]];
            }else{
                if (isset($this->coluna["url"]) && isset($this->coluna["id"])) {
                return ['url' => $this->coluna["url"], 'id' => $this->coluna["id"]];
            }
            }

            
        }


        return false;
    }
    
    function pegaId($tabela){
        $identificador = $tabela["id"];
        $urlizador = $tabela["url"];
        $tabela = $this->tabela;
        $url = $this->url;
        $seleciona = "SELECT $identificador FROM $tabela WHERE $urlizador='$url'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return $dado[$identificador];
    }
    
    function getVendavel(){
        if(!isset($this->coluna["vendavel"])){
            return false;
        }
        $vendavel = $this->coluna["vendavel"];
        $colunaId = $this->coluna["id"];
        $tabela = $this->tabela;
        $id = $this->id;
        $seleciona = "SELECT $vendavel FROM $tabela WHERE  $colunaId='$id'";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }
        $dado = $resultado->fetch_assoc();
        return $dado[$vendavel];
    }  
    
    
    function render(){
        
        if(!$this->tabela || !$this->url){
            return false;
        }
        
        $tabela = $this->infoTabela();

        if(!$tabela){
            return false;
        }
        
        
        $id = $this->pegaId($tabela);
        

        $this->id = $id;
        
        return $id;
    }
}


?>