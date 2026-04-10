<?
header('Content-Type: application/json; charset=utf-8');
session_start();

include __DIR__."/conn.php";

class Api {
    public $identificador;
    public $modulo;
    public $periodo;
    public $config;
    public $banco;
    public $prefixo;
    public $bd;
    public $conn;
    public $maior;
    public $menor;
    public $comeco;
    public $fim;
    public $user;
    public $passado;
    public $where;
    function __construct(){
        $this->identificador = $_POST["identificador"] ?? false; 
        $this->modulo = $_POST["modulo"] ?? false; 
        $this->conn = conn();
        $this->comeco = $_POST["comeco"] ?? false;
        $this->fim = $_POST["fim"] ?? false;
        $this->user = $_SESSION["id"] ?? false;
        $this->passado = $_POST["user-passado"] ?? false;
    }
    
    function close(){
        $this->conn->close();
    }

    function pegaPeriodo(){
        $periodo = $this->config["layout"]["periodoinicial"] ?? "Total";
        
        if(!$this->config["colunaData"]){
            return false;
        }
        
        $seleciona = "SELECT MIN({$this->prefixo}_{$this->config["colunaData"]}) AS menor, MAX({$this->prefixo}_{$this->config["colunaData"]}) as maior FROM {$this->banco}";
        $resultado = $this->conn->query($seleciona);
        if($resultado->num_rows > 0){
            $dado = $resultado->fetch_assoc();
            $this->maior = $dado["maior"];
            $this->menor = $dado["menor"];
        }
        
         if ($this->comeco && $this->fim) {
                   
       
             return "{$this->prefixo}_{$this->config["colunaData"]} BETWEEN '$this->comeco' AND '{$this->fim}'";
        
    }

        
        

    switch ($periodo) {
    case 'Total':
        return ""; // Sem filtro, retorna tudo.
        break;
    case 'Hoje':
        // Datas com o mesmo dia de hoje (da meia-noite até agora).
        return "DATE({$this->prefixo}_{$this->config['colunaData']}) = CURDATE()";
        break;
    case '24 Horas':
        // Últimas 24 horas exatas.
        return "{$this->prefixo}_{$this->config['colunaData']} >= NOW() - INTERVAL 1 DAY";
        break;
    case 'Última Semana':
        // Da meia-noite do último domingo até agora.
        return "{$this->prefixo}_{$this->config['colunaData']} >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 1 DAY)";
        break;
    case 'Últimos 7 Dias':
        // Últimos 7 dias exatos, incluindo o mesmo horário de 7 dias atrás.
        return "{$this->prefixo}_{$this->config['colunaData']} >= NOW() - INTERVAL 7 DAY";
        break;
    case 'Último Mês':
        // Desde o primeiro dia do mês atual.
        return "{$this->prefixo}_{$this->config['colunaData']} >= DATE_FORMAT(CURDATE(), '%Y-%m-01')";
        break;
    case 'Últimos 30 Dias':
        // Últimos 30 dias exatos, incluindo o mesmo horário de 30 dias atrás.
        return "{$this->prefixo}_{$this->config['colunaData']} >= NOW() - INTERVAL 30 DAY";
        break;
    default:
        // Caso não corresponda a nenhum período, retorna vazio.
        return "";
}
    }
    
    function pegaCondicional($chave){
    // Obtém o tipo de comparação a partir do input do formulário (você pode modificar isso conforme a sua implementação)
    $tipoComparacao = $this->config[$chave]["tipo"] ?? '='; // Valor padrão é '='

    // Verifica se a condicional está ativa e se tipo e coluna estão configurados corretamente
    if($this->config[$chave]["ativo"] ?? 0 === "1"){
        $tipo = $tipoComparacao; // Usa o tipo de comparação obtido do select
        $coluna = $this->config[$chave]["coluna"] ?? false;
        $valor = $this->config[$chave]["valor"] ?? "";

        if(!$coluna){
            return false; // Se a coluna não estiver configurada, retorna false
        }

        // Verifica se o valor é uma lista separada por vírgulas
        if(count(explode(",", $valor)) > 1){
            $array = explode(",", $valor); // Converte o valor em um array
            $lista = implode(",", array_map('trim', $array)); // Remove espaços extras e junta os valores de volta
            return "{$this->prefixo}_{$coluna} {$tipo} ({$lista})"; // Retorna a condição para uma lista de valores
        } else {
            // Se o valor for único, aplica o tipo configurado (ex.: "=")
            return "{$this->prefixo}_{$coluna} {$tipo} '{$valor}'"; // Retorna a condição para um valor único
        }
    }

    return false; // Caso a condicional não esteja ativa
}

    function init(){
       
       $array = [];
       $periodo = $this->pegaPeriodo();
       if($periodo){
           $array[] = $periodo;
       }
       
       if(intval($this->config["filtro"]) > 1){
           if(intval($this->config["filtro"]) == 2){
               $array[] = "{$this->prefixo}_autor = '{$this->user}'";
           }else{
               $array[] = "{$this->prefixo}_autor = '{$this->passado}'";
           }
       }
       
       $condicoes = $this->pegaCondicional("condicional");
       if($condicoes){
           $array[] = $condicoes;
       }
       
       $condicoes = $this->pegaCondicional("condicional2");
       if($condicoes){
           $array[] = $condicoes;
       }
       
       
       
       
       
       if(!empty($array)){
           $this->where = "WHERE ".implode(" AND ", $array);
       }else{
           $this->where = "";
       }
       

        switch($this->config["tipoGrafico"]){
            case 'numerico':
                return $this->numericos();
                break;
            case 'categorico':
                return $this->categoricos();
                break;
            case 'tempo':
                return $this->tempo();
                break;
            case 'proporcional':
                return $this->proporcionais();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"O tipo gráfico enviado não é válido"];
                break;
        }
        
    }
    
    function findColuna($nome){
        foreach($this->bd["colunas"] as $coluna){
            if($coluna["nome"] == $nome){
                return true;
            }
        }
        return false;
    }
    
    function numericos() {
    $subtipo = $this->config["grafico"]["subtipo"] ?? "medicao";
    
    if(!$subtipo){
        $subtipo = "medicao";
    }
    
    $agrupamento = intval($this->config["grafico"]["agrupamento"] ?? 1);
    $coluna = $this->config["grafico"]["coluna"] ?? false;
    $referencia = $this->config["grafico"]["referencia"] ?? false;

    if ($agrupamento == 2 && !$coluna) {
        return ["erro" => true, "mensagem" => "Não foi enviada a coluna a ser agrupada"];
    }



    switch ($subtipo) {
        case 'medicao':

            if ($agrupamento == 1) {
                if(!empty($this->config["grafico"]["unicos"])){
 
                $query = "SELECT COUNT(DISTINCT {$this->prefixo}_{$this->config['grafico']['referencia']}) AS value FROM {$this->banco} {$this->where}";

                }else{
                 $query = "SELECT COUNT(*) AS value FROM {$this->banco} {$this->where}";

                }
              
            } else {
                if (!$this->findColuna($coluna)) {
                    return ["erro" => true, "mensagem" => "A coluna enviada não é válida"];
                }
                $query = "SELECT {$this->prefixo}_{$coluna} AS grupo, COUNT(*) AS value FROM {$this->banco} {$this->where} GROUP BY {$this->prefixo}_{$coluna}";
            }
            
        

            break;

        case 'contagem':
            if (!$this->findColuna($coluna)) {
                return ["erro" => true, "mensagem" => "A coluna enviada não é válida"];
            }
            $query = "SELECT {$this->prefixo}_{$coluna} AS grupo, COUNT(*) AS value FROM {$this->banco} {$this->where} GROUP BY {$this->prefixo}_{$coluna}";
            break;

        case 'soma':
        case 'media':
        case 'maxima':
        case 'minima':
            $map = [
                "soma" => "SUM",
                "media" => "AVG",
                "maxima" => "MAX",
                "minima" => "MIN",
            ];
            $operacao = $map[$subtipo];

            if (!$referencia || !$this->findColuna($referencia)) {
                return ["erro" => true, "mensagem" => "Não foi passada uma referência válida."];
            }

            if ($agrupamento == 1) {
                $query = "SELECT $operacao({$this->prefixo}_{$referencia}) AS value FROM {$this->banco} {$this->where}";
            } else {
                if (!$this->findColuna($coluna)) {
        return ["erro" => true, "mensagem" => "A coluna enviada não é válida"];
    }
                $query = "SELECT {$this->prefixo}_{$coluna} AS agrupado, $operacao({$this->prefixo}_{$referencia}) AS value FROM {$this->banco} {$this->where} GROUP BY {$this->prefixo}_{$coluna};";
            }

            break;

        default:
            return ["erro" => true, "mensagem" => "Operação desconhecida"];
    }


    $resultado = $this->conn->query($query);
    if (!$resultado) {
        return ["erro" => true, "mensagem" => "Erro ao executar a consulta SQL: " . $this->conn->error];
    }


    $dados = [];
    while ($linha = $resultado->fetch_assoc()) {
        $dados[] = $linha;
    }

    return [
        "sucesso" => true,
        "dados" => $dados
    ];
}
    
    function proporcionais() {
        
        $coluna = $this->config["grafico"]["categoria"] ?? false;
        if(!$coluna){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma coluna válida para comparação"];
        }
        
        if(!$this->findColuna($coluna)){
            return ["erro"=>true, "mensagem"=>"A coluna configurada não é válida"];
        }
        
        $total = "SELECT count(*) AS value FROM {$this->banco} {$this->where}";

        $resultado = $this->conn->query($total);
        $dado = $resultado->fetch_assoc();
        $total = $dado["value"];
        
        $porcento = 100 / $total;

         $query = "SELECT {$this->prefixo}_{$coluna} AS name, COUNT(*) AS value FROM {$this->banco} {$this->where} GROUP BY {$this->prefixo}_{$coluna}";
         $resultado = $this->conn->query($query);
         $dados = [];
         if($resultado->num_rows > 0){
             while ($linha = $resultado->fetch_assoc()) {
             $linha["value"] = $porcento * $linha["value"];
             $dados[] = $linha;
            }
         }
         
         return [
        "sucesso" => true,
        "dados" => $dados
    ];

    }

    function categoricos() {
        $coluna = $this->config["grafico"]["categoria"] ?? false;
        if(!$coluna || !$this->findColuna($coluna)){
            return ["erro"=>true, "mensagem"=>"Não foi selecionado uma coluna de categoria válida"];
        }
        
         $query = "SELECT {$this->prefixo}_{$coluna} AS grupo, COUNT(*) AS value FROM {$this->banco} {$this->where} GROUP BY {$this->prefixo}_{$coluna}";
         $resultado = $this->conn->query($query);
         

    if (!$resultado) {
        return ["erro" => true, "mensagem" => "Erro ao executar a consulta SQL: " . $this->conn->error];
    }


    $dados = [];
    while ($linha = $resultado->fetch_assoc()) {
        $dados[] = $linha;
    }

    return [
        "sucesso" => true,
        "dados" => $dados
    ];
        
    }

    function tempo() {
    // Verifica o tipo de agrupamento
    $agrupamento = $this->config["grafico"]["agrupamento"] ?? 1;

    // Se for o agrupamento 2, valida a categoria
    if ($agrupamento == 2) {
        $categoria = $this->config["grafico"]["categoria"];
        if (!$categoria) {
            return ["erro" => true, "mensagem" => "Não foi enviada uma categoria válida"];
        }

        if (!$this->findColuna($categoria)) {
            return ["erro" => true, "mensagem" => "A categoria enviada não foi encontrada"];
        }
    }
    

    $sub = $this->config["grafico"]["sub"] ?? "contagem";
    
    if($sub != "contagem"){
        $conta = $this->config["grafico"]["conta"] ?? false;

        if(!$conta || !$this->findColuna($conta)){
            return ["erro"=>true, "mensagem"=>"Não foi enviada uma coluna de analise válida"];
        }
         $campo = "{$this->prefixo}_{$conta}";
    }
       
     switch($sub){
        case 'contagem':
            $agregacao = "COUNT(*) AS value";
            break;
        case 'soma':
            $agregacao = "SUM({$campo}) AS value";
            break;
        case 'media':
            $agregacao = "AVG({$campo}) AS value";
            break;
        case 'maxima':
            $agregacao = "MAX({$campo}) AS value";
            break;
        case 'minima':
            $agregacao = "MIN({$campo}) AS value";
            break;
        default:
            return ["erro" => true, "mensagem" => "Tipo de agregação inválido"];
    }


    $periodo = $this->config["grafico"]["periodo"] ?? "minuto";

    $colunaData = "{$this->prefixo}_{$this->config["colunaData"]}"; 

   
   $formatos = [
    'minuto' => '%Y-%m-%d %H:%i',
    'hora' => '%Y-%m-%d %H',
    'dia' => '%Y-%m-%d',
    'mes' => '%Y-%m'
];

// Verifica se o período é válido
if (!isset($formatos[$periodo])) {
    return ["erro" => true, "mensagem" => "Período desconhecido"];
}

// Seleciona o formato correspondente
$formatoTempo = $formatos[$periodo];

// Monta a consulta com base no agrupamento
if ($agrupamento == 1) {
    $query = "
        SELECT 
            DATE_FORMAT({$colunaData}, '{$formatoTempo}') AS tempo, 
            {$agregacao}
        FROM 
            {$this->banco}
           {$this->where}
        GROUP BY 
            DATE_FORMAT({$colunaData}, '{$formatoTempo}')
    ";
} else {
    $query = "
        SELECT 
            DATE_FORMAT({$colunaData}, '{$formatoTempo}') AS tempo, 
            {$this->prefixo}_{$categoria} AS grupo, 
            {$agregacao}
        FROM 
            {$this->banco}
            {$this->where}
        GROUP BY 
            DATE_FORMAT({$colunaData}, '{$formatoTempo}'), 
            {$this->prefixo}_{$categoria}
    ";
}



    
    $resultado = $this->conn->query($query);

    // Verifica se a consulta foi bem-sucedida
    if (!$resultado) {
        return ["erro" => true, "mensagem" => "Erro ao executar a consulta: " . $this->conn->error];
    }

    // Coleta os resultados
    $dados = [];
    while ($linha = $resultado->fetch_assoc()) {
        $dados[] = $linha;
    }

    // Retorna os dados
    return [
        "sucesso" => true,
        "dados" => $dados
    ];
}

    function loadConfig(){
        $dir = __DIR__."/../conteudo/modulos/{$this->modulo}/admins/graficos/{$this->identificador}.json";
        if(!file_exists($dir)){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado o arquivo desejado"];
        }
        
        $conteudo = json_decode(file_get_contents($dir), true);
        
        if (empty($conteudo["bancoDeDados"]) || empty($conteudo["tipoGrafico"])) {
            return ["erro" => true, "mensagem" => "Não foram enviados os parâmetros válidos"];
        }
        
        $this->config = $conteudo;
        return ["sucesso"=>true];
    }
    
    function loadBanco(){
        $banco = $this->config["bancoDeDados"];
        $dir = __DIR__."/../conteudo/modulos/{$this->modulo}/admins/configs/{$banco}.json";
        if(!file_exists($dir)){
            return ["erro"=>true, "mensagem"=>"Não foi encontrado o arquivo desejado"];
        }
        $conteudo = json_decode(file_get_contents($dir), true);
        
        $this->banco = $conteudo["banco"];
        $this->prefixo = $conteudo["prefixo"];
        $this->bd = $conteudo;
        
    }
    
    function render(){
        if(!$this->modulo || !$this->identificador){
            return ["erro"=>true, "mensagem"=>"Não foram enviados os parametos necessarios"];
        }
        $config = $this->loadConfig();
        if(isset($config["erro"])){
            return $config;
        }
        
        $banco = $this->loadBanco();
         if(isset($banco["erro"])){
            return $banco;
        }
        $init = $this->init();
        
        if(isset($init["sucesso"])){
            $init["tempo"] = [$this->menor , $this->maior];
        }else{
            return $init;
        }
      
        

        $graficos = [
            "numerico"=>["linha","barras","dispersão"],
            "categorico"=>["barras", "pizza"],
            "tempo"=>["linha", "área", "barras temporais"],
            "proporcional"=>["pizza", "donut", "barras empilhadas"],
            ];
            
            if ($this->config["layout"]["alteralayout"]) {
    $gra = $graficos[$this->config["tipoGrafico"]];

    if ($this->config["layout"]["primario"]) {
        array_unshift($gra, $this->config["layout"]["primario"]);
    }
}
            else{
                 if ($this->config["layout"]["primario"]) {
                     $gra = [$this->config["layout"]["primario"]];
                 }else{
                    $gra = [$graficos[$this->config["tipoGrafico"]]];
                 }
            }
        
        
        $init["t"] = $this->config["tipoGrafico"];
        if($this->config["tipoGrafico"] == "numerico"){
           $init["ag"] = intval($this->config["grafico"]["agrupamento"] ?? 1);
        }
        

    
        $init["layout"] = [
            "gr"=>array_unique($gra),
            "pi"=>$this->config["layout"]["periodoinicial"],
            "ti"=>$this->config["layout"]["titulo"],
            "sp"=>$this->config["layout"]["selecionarperiodo"] ?? false,
            "dr"=>$this->config["layout"]["drop"] ?? false,
            "ho"=>$this->config["layout"]["horas"] ?? false,
            "tra"=>$this->config["trato"] ?? false,
            "rod"=>$this->config["layout"]["rodape"] ?? false
            ];
        $init["extra"] = $this->config["extra"] ?? [];
        
        if($this->config["atualizacao"]["ativa"] ?? false){
            $init["timer"] = $this->config["atualizacao"]["segundos"];
        }
        

        return $init;
        
    }
}



$api = new  Api();
$resposta = $api->render();
$api->close();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>