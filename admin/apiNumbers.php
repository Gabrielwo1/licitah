<?
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//https://echarts.apache.org/examples/en/index.html#chart-type-scatter

include_once __DIR__."/conn.php";

class Acao{
    public $chave;
    public $modulo;
    public $conn;
    public $acao;
    public $grafico;
    public $config;
    public $banco;
    public $prefixo;
    public $quem;
    public $user;
    public $hash;
    public $id;
    public $comeco;
    public $fim;
    
    function __construct(){
        $this->chave = $_POST["chave"] ?? false;
        $this->modulo = $_POST["modulo"] ?? false;
        $this->conn = conn();
        $this->acao = $_POST["acao"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->hash = $_POST["hash"] ?? false;
        $this->comeco = $_POST["comeco"] ?? false;
        $this->fim = $_POST["fim"] ?? false;
        $this->id = false;

    }
    
    function init(){
        if(!is_dir(__DIR__."/../conteudo/modulos/".$this->modulo)){
            return ["erro"=>true, "mensagem"=>"O modulo enviado não existe"];
        }
        
        if(!file_exists(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/graficos/".$this->chave.".json")){
            return ["erro"=>true, "mensagem"=>"A chave de gráfico enviada não existe"];
        }
        
        $conteudo = file_get_contents(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/graficos/".$this->chave.".json");
        
        $this->grafico = json_decode($conteudo, true);
        
        if(!isset($this->grafico["banco"]) || $this->grafico["banco"] == "0"){
            return ["erro"=>true, "mensagem"=>"O banco definido não é valido"];
        }
        
        if(!file_exists(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/configs/".$this->grafico["banco"].".json")){
            return ["erro"=>true, "mensagem"=>"O banco definido não existe"];
        }
        
        
        $conteudo = file_get_contents(__DIR__."/../conteudo/modulos/".$this->modulo."/admins/configs/".$this->grafico["banco"].".json");
        $this->config = json_decode($conteudo, true);
        

        $this->banco = $this->config["banco"];
        $this->prefixo = $this->config["prefixo"];
        $this->quem = intval($this->grafico["quem"]);
        

 
        return ["sucesso"=>true];
    }
    
    function parseia(){
        if(!$this->hash){
            return;
        }
        
        if(is_integer($this->hash)){
            $this->id = $this->hash;
            return;
        }
        
        
        if(ctype_digit($this->hash)){
            $this->id = $this->hash;
        }

        $hash = $this->hash;

        switch($this->quem){
            case 2:
                // Usuario;
                $seleciona = "SELECT * FROM usuarios WHERE usuario_user='$hash'";
     
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows == 1){
                    $dado = $resultado->fetch_assoc();
                    $this->id = $dado["usuario_id"];
                }
                break;
            case 3:
            case 4:
                $tipo = $this->grafico["banco"];
                $sub = $this->quem == 3 ? '1' : '0';
                $seleciona = "SELECT * FROM categorias WHERE categoria_url='$hash' AND categoria_tipo='$tipo' AND categoria_iscat='$sub'";
                 $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    $dado = $resultado->fetch_assoc();
                    $this->id = $dado["categoria_id"];
                }
                break;
        }
        
        
    }

    function unico(){
        $banco = $this->banco;
        $id = $this->id;
        $periodo = "";
        $dataQuery = $this->prefixo."_data";
        
        
        
        switch(intval($this->grafico["periodo"])){
            case 1:
                // Periodo Total
                break;
            case 2:
                // Periodo Definido
                
                $q = intval($this->grafico["periodoQuantidade"] ?? 1);
                $c = intval($this->grafico["periodoCiclo"] ?? 1);
                
        
                
                switch($c){
    case 1:
        // Minuto
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' MINUTE';

        break;
    case 2:
        // Hora
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' HOUR';
        break;
    case 3:
        // Dia
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' DAY';
        break;
    case 4:
        // Semana
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' WEEK';
        break;
    case 5:
        // Mês
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' MONTH';
        break;
    case 6:
        // Ano
        $periodo = ' AND '.$dataQuery.' >= NOW() - INTERVAL '.$q.' YEAR';
        break;
    default:
        // Caso padrão, se nenhum ciclo for correspondente
        $periodo = '';
        break;
}
                

                break;
            case 3:
                // Periodo Passado pelo Usuário
                break;
        }
        
   
        
        
        
        switch($this->quem){
            case 0:
                // Todos
                if($periodo){
                    $query = "SELECT COUNT(*) as total FROM ".$banco." WHERE 1=1".$periodo;
                }else{
                    $query = "SELECT COUNT(*) as total FROM ".$banco.$periodo;
                }
                
                break;
            case 1:
                // Usuario Logado
                $pre = $this->prefixo."_autor";
                $user = $this->user;
                $query = "SELECT COUNT(*) as total FROM ".$banco." WHERE $pre='$user'".$periodo;
                break;
            case 2:
                // Usuario Passado
                $pre = $this->prefixo."_autor";
                $query = "SELECT COUNT(*) as total FROM ".$banco." WHERE $pre='$id'".$periodo;
                break;
            case 3:
                // Categoria
                $pre = $this->prefixo."_categoria";
                $query = "SELECT COUNT(*) as total FROM ".$banco." WHERE $pre='$id'".$periodo;
                break;
            case 4:
                // Por Tag;
                $pre = $this->prefixo."_tags";
                $query = "SELECT COUNT(*)  FROM ".$banco." WHERE JSON_CONTAINS(".$pre.", '".$id."', '$')".$periodo;
                break;
        }
    


        $resultado = $this->conn->query($query);
        
        if ($resultado->num_rows > 0) {
            $dado = $resultado->fetch_assoc();
            return ["sucesso"=>true, "total"=>$dado["total"]];
        }else{
            return ["sucesso"=>true, "total"=>0];
        }
    }
    
    function pegaReferencia($array, $banco){
        $lista = [];
        if(empty($array)){
            return $lista;
        }
        

        $string = implode(",", $array);
        switch($banco){
            case 1:
                $seleciona = "SELECT usuario_display, usuario_id FROM usuarios WHERE usuario_id IN ($string)";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $lista[$dado["usuario_id"]] = $dado["usuario_display"];
                    }
                }
                break;
            case 2:
            case 3:
                $seleciona = "SELECT categoria_nome, categoria_id FROM categorias WHERE categoria_id IN ($string)";
                $resultado = $this->conn->query($seleciona);
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $lista[$dado["categoria_id"]] = $dado["categoria_nome"];
                    }
                }
                break;
        }
        return $lista;
    }
    
    function cicla($ciclo, $cada){
         
         switch ($ciclo) {
             case 1:
            // Minuto
            $intervalo = "MINUTE";
            $format = "%Y-%m-%d %H:%i";
            break;
        case 2:
            // Hora
            $intervalo = "HOUR";
            $format = "%Y-%m-%d %H:%i";
            break;
        case 3:
            // Dia
            $intervalo = "DAY";
            $format = "%Y-%m-%d";
            break;
        case 4:
            // Semana
            $intervalo = "WEEK";
            $format = "%Y-%u";
            break;
        case 5:
            // Mês
            $intervalo = "MONTH";
            $format = "%Y-%m";
            break;
        case 6:
            // Ano
            $intervalo = "YEAR";
            $format = "%Y";
            break;
   
    }
    
    
     $query = "SELECT DATE_FORMAT(DATE_SUB(NOW(), INTERVAL n $intervalo), '$format') AS periodo 
              FROM (
                  SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
                  UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 
                  UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
              ) AS nums 
              WHERE n < $cada
              ORDER BY n;";

    $periodos = $this->conn->query($query);
    

    
    $array = ["periodos"=>$periodos, 
            "intervalo"=>$intervalo, 
            "formato"=>$format];
            
        return $array;
    }
    
    function comparativoCiclo() {
    $ciclo = $this->grafico["cicloComparacao"];
    $cada = $this->grafico["quantidadeComparacao"];
    $cicla = $this->cicla($ciclo, $cada);

    $intervalo = $cicla["intervalo"];
    $format = $cicla["formato"];
    $periodos = $cicla["periodos"];
    $itens = [];
    switch (intval($this->grafico["comparados"])) {
        case 1:
            $referencia = $this->prefixo."_autor";
            break;
        case 2:
            $referencia = $this->prefixo."_categoria";
            break;
        case 3:
            $referencia = $this->prefixo."_tags";
            break;
        case 4:
            $referencia = trim($this->grafico["comparadoPersonalizado"]);
            break;
        default:
            return ["sucesso" => false, "mensagem" => "Tipo de comparação inválido."];
    }

    $pre = $this->prefixo."_data";
    $banco = $this->banco;
    $query = "SELECT DATE_FORMAT(".$pre.", '$format') AS periodo, ".$referencia." , COUNT(*) AS quantidade 
              FROM ".$banco."
              WHERE ".$pre." >= NOW() - INTERVAL $cada $intervalo
              GROUP BY periodo, ".$referencia."
              ORDER BY periodo DESC;";

    $resultado = $this->conn->query($query);

    if ($resultado === false) {
        return ["sucesso" => false, "mensagem" => "Erro na query: " . $this->conn->error];
    }

    $dadosAgrupados = [];
    foreach ($periodos as $p) {
        $dadosAgrupados[$p['periodo']] = []; // Inicializa todos os períodos
    }

    if ($resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $periodo = $dado['periodo'];
            $valor = $dado[$referencia];
            $quantidade = $dado['quantidade'];

            $dadosAgrupados[$periodo][$valor] = $quantidade;
            if (!in_array($valor, $itens)) {
                array_push($itens, $valor);
                
            }

            
        }
    }

    // Convertendo o resultado para o formato desejado
    $resultadoFinal = [];
    foreach ($dadosAgrupados as $periodo => $statusQuantidades) {
        $resultadoFinal[$periodo] = $statusQuantidades;
    }




    return ["sucesso" => true, "lista" => $resultadoFinal, "comparativo"=>true, "itens"=>$itens, "referencia"=>$this->pegaReferencia($itens, $this->grafico["comparados"]), "setup"=>$this->grafico["publico"] ?? []];
}

    function comparativo(){
         $modelo = intval($this->grafico["comparacaoModelo"]);
           if($modelo == 2){
               return $this->comparativoCiclo();
            }
           
           
        $banco = $this->banco;
        $id = $this->id;
        
     
        switch(intval($this->grafico["comparados"])){
            case 1:
                // Autores
                $referencia = $this->prefixo."_autor";
                $query = "SELECT $referencia as item ,  COUNT(*) AS quantidade FROM  ".$banco." GROUP BY ".$referencia;
                break;
            case 2:
                // Categorias
                $referencia = $this->prefixo."_categoria";
                $query = "SELECT $referencia as item ,  COUNT(*) AS quantidade FROM  ".$banco." GROUP BY ".$referencia;
                break;
            case 3:
                // Tags
                $referencia = $this->prefixo."_tags";
           
            $query = "SELECT item, COUNT(*) AS quantidade FROM (
                SELECT JSON_UNQUOTE(JSON_EXTRACT($referencia, CONCAT('$[', n.idx, ']'))) AS item
                FROM " . $banco . " 
                JOIN (
                    SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                ) n
                ON JSON_LENGTH($referencia) > n.idx
            ) AS tags_extraidas
            GROUP BY item;";
            break;
                break;
            case 4:
                // Personalizado
                if(!$this->grafico["comparadoPersonalizado"] || !trim($this->grafico["comparadoPersonalizado"])){
                    return ["erro"=>true, "mensagem"=>"Não foi passada a coluna personalizada"];
                }
                $referencia = trim($this->grafico["comparadoPersonalizado"]);
                $query = "SELECT $referencia as item ,  COUNT(*) AS quantidade FROM  ".$banco." GROUP BY ".$referencia;
                break;
        }
        

        $resultado = $this->conn->query($query);
        $lista = [];
        $referenciais = [];
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){

                if($dado["item"]){
                    array_push($referenciais, $dado["item"]);
                    $lista[$dado["item"]] = $dado["quantidade"];
                }else{
                    $lista[0] = $dado["quantidade"];
                }
                
            }
        }
        
   
        return ["sucesso"=>true, "lista"=>$lista, "referencia"=>$this->pegaReferencia($referenciais, $this->grafico["comparados"]), "setup"=>$this->grafico["publico"] ?? []];
    }
    
    function progressivo() {
    $progresso = $this->grafico["progresso"];
    
    $ciclo = intval($progresso["ciclo"] ?? 1);
    $cada = intval($progresso["quantidade"] ?? 1);
    
    $intervalo = '';
    $format = '';
    
    $cicla =  $this->cicla($ciclo, $cada);
    

  
    $intervalo = $cicla["intervalo"];
    $format = $cicla["formato"];
    $periodos = $cicla["periodos"];
    $lista = [];
    
    
    $pre = $this->prefixo."_data";
    $banco = $this->banco;
    $query = "SELECT DATE_FORMAT(".$pre.", '$format') AS periodo, COUNT(*) AS quantidade 
              FROM ".$banco."
              WHERE ".$pre." >= NOW() - INTERVAL $cada $intervalo
              GROUP BY periodo 
              ORDER BY periodo DESC;";

    $resultado = $this->conn->query($query);
       if ($periodos && $periodos->num_rows > 0) {
        while ($p = $periodos->fetch_assoc()) {
            $lista[$p["periodo"]] = 0; // Inicializa com zero
        }
    }

    if ($resultado && $resultado->num_rows > 0) {
        while ($dado = $resultado->fetch_assoc()) {
            $lista[$dado["periodo"]] = $dado["quantidade"];
        }
    }

    return [
        "sucesso" => true, 
        "lista" => $lista,
        "setup"=>$this->grafico["publico"] ?? []
    ];
}

    function dataFormadata($date, $start = true) {

    $dateTime = new DateTime($date);

    if ($start) {
        
        return $dateTime->format('Y-m-d') . ' 00:00:00';
    } else {
        // Retorna a data com o último horário do dia
        return $dateTime->format('Y-m-d') . ' 23:59:59';
    }
}

    function render(){
        if(!$this->chave || !$this->modulo){
            return ["erro"=>true, "mensagem"=>"Não foram enviados o Modulo e/ou Chaves"];
        }

    
        $this->comeco = $this->dataFormadata($this->comeco , true);
        $this->fim = $this->dataFormadata($this->fim , true);

        $inicializacao = $this->init();
        if(isset($inicializaca["erro"])){
            return $inicializacao;
        }
        $this->parseia();
        

        if($this->quem > 1 && !$this->id){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um hash válido"];
        }
        
        

        switch($this->acao){
            case 'start':
            
                switch(intval($this->grafico["tipo"])){
                    case 1:
                        // Dado Único
                        return $this->unico();
                        break;
                    case 2:
                        // Comparativo
                        return $this->comparativo();
                        break;
                    case 3:
                        // Progressivo
                        return $this->progressivo();
                        break;
                }
                
                break;
            default:
                return ["erro"=>true, "mensagem"=>"A ação enviada não é válida"];
                break;
        }
    }
}


$acao = new Acao();
$resposta = $acao->render();
echo json_encode($resposta , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>