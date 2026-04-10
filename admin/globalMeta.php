<?

class Meta{
    private $conn;
    private $bancoNome;
    private $item;
    private $prefixo;
    private $chave;
    private $valor;
    private $chaves;
    
    function __construct($setup = []){
        $this->conn = conn();
        foreach($setup as $chave=>$valor){
            $this->$chave = $valor;
        }
        
        
        if(isset($this->prefixo)){
            $this->chave = $this->prefixo."_chave";
            $this->valor = $this->prefixo."_valor";
        }
        
        
        
    }
    
    function set($chave, $valor){
        $this->$chave = $valor;
    }
    
    function item($id, $chave){
        $k = $this->chave;
        $pre = $this->item;
        $v = $this->valor;
        $conn = $this->conn;
        $banco = $this->bancoNome;
        
        $seleciona = "SELECT $v FROM $banco WHERE $k='$chave' AND $pre='$id'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows == 0){
            return false;
        }else{
            $dado = $resultado->fetch_assoc();
            return isJson($dado[$v]) ? json_decode($dado[$v], true) : $dado[$v];
        }
    }
    
    function lista($id){
        $conn = $this->conn;
        $banco = $this->bancoNome;
        
        $seleciona = "SELECT * FROM $banco WHERE $this->item='$id'";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows > 0){
            $infos = [];
            while($dado = $resultado->fetch_assoc()){
                
                if(isset($this->chaves)){
                    $existe = false;
                    foreach($this->chaves as $item){
                        if($item["chave"] == $dado[$this->chave] && $item["meta"]){
                            $existe = $item["map"];
                            break;
                        }
                    }
                    
                    
                    if(!$existe){
                         $infos[$dado[$this->chave]] =  isJson($dado[$this->valor]) ? json_decode($dado[$this->valor], true) : $dado[$this->valor];
                    }else{
                         $infos[$existe] =  isJson($dado[$this->valor]) ? json_decode($dado[$this->valor], true) : $dado[$this->valor];
                    }
                    
                    
                    
                }else{
                    $infos[$dado[$this->chave]] =  isJson($dado[$this->valor]) ? json_decode($dado[$this->valor], true) : $dado[$this->valor];
                }
            }
            return $infos;
        }else{
            return false;
        }
    }
    
    function cadastra($id, $item){
        $conn = $this->conn;
        $banco = $this->bancoNome;
        $entrada = implode("," , [$this->item, $this->chave ,$this->valor]);
        
        
   
        if(is_array($item["valor"])){
            $v = json_encode($item["valor"]);
        }else{
            $v = $item["valor"];
        }
    

        
        $valor = "'" .implode("','" , [$id , $item["chave"], $v]). "'";

    
        $cadastra = "INSERT INTO $banco ($entrada) VALUES ($valor)";
 
        $conn->query($cadastra);
    }
    
    function edita($id, $entradas){
        $conn = $this->conn;
        $banco = $this->bancoNome;
        
        $key = $this->chave;
        $value = $this->valor;
        $item = $this->item;
        
        $valor = is_array($entradas["valor"]) ? json_encode($entradas["valor"]) : $entradas["valor"];
        $chave = $entradas["chave"];
        
        $atualiza = "UPDATE $banco SET $value='$valor' WHERE $item='$id' AND $key='$chave'";
        $conn->query($atualiza);
        
    }
    
    function deleta($id, $entradas){
        
        $conn = $this->conn;
        $banco = $this->bancoNome;
        
        
        $key = $this->chave;

        $item = $this->item;
        
   
        $chave = $entradas["chave"];
        
        $deleta = "DELETE FROM $banco WHERE $item='$id' AND $key='$chave'";
        $conn->query($deleta);
    }

}

?>