<?

class ApiAnalitica{
  
    public $modulo;
    public $banco;
    public $prefixo;
    public $tipo;
    public $quantidade;
    public $pagina;
    public $paginacao;
    public $usuario;
    public $conn;
    public $data;
    public $base;

  
    function __construct($pai, $base = "artigos"){
        
        $this->modulo = $pai->modulo;
        $this->banco = $pai->banco;
        $this->prefixo = $pai->prefixo;
        $this->tipo = $pai->api["subtipo"] ?? false;
        $this->quantidade = $pai->quantidade ?? 10; 
        $this->pagina = $pai->pagina ?? 1;
        $this->paginacao = $pai->paginacao ?? false;
        $this->usuario =  $pai->usuario;
        $this->data = $_POST["data"] ?? false;
        $this->conn = $pai->conn;
        $this->base = $base;
        /*
        $this->key = $key;
        $this->modulo = $modulo;
        $this->banco = $banco;
        $this->conn = conn();
        $this->size = 10;
        $this->data = false;
        $this->recente = true;
        */
        
        
    }
    
    // Lembrar de criar algoritimo que limita a data;
    
    function render(){
        $ids = [];
        $maps = [];
        switch(intval($this->tipo)){
            case 1:
                // Populares - Algoritimo de acesso e tempo neles
                break;
            case 2:
                // Mais acessados
                
                if(!$this->base){
                    return $ids;
                }
                
                
                $data = $this->data;
                $complementoData = "";
                if($data){
                    if(is_array($variavel)){
                        $complementoData = "AND analuurl_data BETWEEN '$variavel[0]' AND '$variavel[1]";
                    }else{
                       if(is_numeric($string)){
                           $variavel = intval($variavel);
                           $complementoData = "AND analuurl_data >= NOW() - INTERVAL $variavel DAY";
                       }else{
                           if($this->recente){
                               $complementoData = "AND analuurl_data > '$data'";
                           }else{
                               $complementoData = "AND analuurl_data < '$data'";
                           }
                           
                       }
                    }
                }
                
                
                $limite = $this->quantidade;
                $base = $this->base;
                $query = "
                    SELECT anaurl_url, COUNT(*) AS quantidade 
                    FROM analytics_urls 
                    WHERE anaurl_url LIKE '/$base/%' 
                    ".$complementoData."
                    GROUP BY anaurl_url 
                    ORDER BY quantidade DESC 
                    LIMIT $limite";
                    

                
                $resultado= $this->conn->query($query);
                $urls = [];
                if($resultado->num_rows > 0){
                    $mapa = [];
                   while($dado = $resultado->fetch_assoc()){
                       $trato = explode("$base/", $dado["anaurl_url"])[1];
                       array_push($urls, $trato);
                       $mapa[$trato] = $dado["quantidade"];
                       
                   }
                } 
                
                $urls_in = "'" . implode("','", $urls) . "'";
                
                $seleciona = "SELECT ".$this->prefixo."_id, ".$this->prefixo."_url FROM ".$this->banco." WHERE ".$this->prefixo."_url IN ($urls_in)";
    
                $result = $this->conn->query($seleciona);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($ids, $row[$this->prefixo."_id"]);
                        $maps[$row[$this->prefixo."_id"]] = $mapa[$row[$this->prefixo."_url"]];  
                    }
                    
                }
                
                break;
            case 3:
                // Novidades
                break;
            case 4:
                // Melhores Avaliados:
                break;
            case 5:
                // Recomendados
                break;
            case 6:
                // Destacados
                echo "caiu aqui";
                break;
            case 7:
                // Mais Vendidos
                break;
            case 8:
                // Em Promoção
                break;
            case 9:
                // Tendencias
                break;
            case 10:
                // Relacionados
                break;
            case 11:
                // Mais comentados
                break;
            case 12:
                // Mais favoritados
                break;
            case 13:
                // Favoritos do usuário
                break;
            case 14:
                // Visualizado Recentemente do Usuário
                
                $user = '30'; 
                
                $query_analytics = "SELECT DISTINCT analytic_id FROM analytics WHERE analytic_usuario = '$user'";
                $result_analytics = $conn->query($query_analytics);
                
                
                $analytics_ids = array();
                
                while ($row = $result_analytics->fetch_assoc()) {
                    $analytics_ids[] = $row['analytic_id'];
                }
                
                prinr_r($analytics_ids);
                
                if (!empty($analytics_ids)) {
                    $query_urls = "SELECT * FROM analytics_urls WHERE anaurl_fluxo IN (" . implode(",", $analytics_ids) . ") AND anaurl_url LIKE '/artigos%'";
                    $result_urls = $conn->query($query_urls);
                    
                    if ($result_urls->num_rows > 0) {
                        
                        while ($row = $result_urls->fetch_assoc()) {
                            echo "ID: " . $row['anaurl_id'] . "<br>";
                            echo "URL: " . $row['anaurl_url'] . "<br>";
                            echo "<br>";
                
                        }
                } else {
                    echo "Nenhum resultado encontrado.";
                    
                }
} else {
    echo "Nenhum ID de analytics encontrado para o usuário especificado.";
}

// Fechar a conexão
$conn->close();


                
  
                break;
            
        }
        
        arsort($maps);
        $keysArray = array_keys($maps);
        
        
        return ["ids"=>$ids, "maps"=>$keysArray];
    }
}




?>