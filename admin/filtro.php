<?
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
 ini_set("display_errors", 1);

include __DIR__."/conn.php";

class Filtro{
    private $conn;
    private $modulo;
    private $chave;
    private $dir;
    private $filtro;
    private $api;
    private $formulario;
    private $configs;
    
    function __construct(){
        $this->conn = conn();   
        $this->modulo = $_POST["modulo"] ?? false;
        $this->chave = $_POST["chave"] ?? false;
    }
    
    function encontrarItemPorHash($hash) {
    // Função recursiva para buscar o item em toda a estrutura
    $buscarItem = function($item, $hash) use (&$buscarItem) {
        // Verifica se o item atual tem o hash procurado
        if (isset($item->hash) && $item->hash === $hash) {
            return $item;
        }
        
        // Verifica se o item tem filhos
        if (isset($item->filhos) && is_array($item->filhos)) {
            // Busca em cada filho
            foreach ($item->filhos as $filho) {
                $resultado = $buscarItem($filho, $hash);
                if ($resultado !== null) {
                    return $resultado;
                }
            }
        }
        
        // Verifica itens em inputs (caso especial para a raiz)
        if (isset($item->inputs) && is_array($item->inputs)) {
            foreach ($item->inputs as $input) {
                $resultado = $buscarItem($input, $hash);
                if ($resultado !== null) {
                    return $resultado;
                }
            }
        }
        
        return null;
    };
    
    // Inicia a busca a partir do objeto formulário
    return $buscarItem($this->formulario, $hash);
}
    
    private function pegaDatas($banco, $coluna){

    // Query otimizada para buscar MIN e MAX em uma única consulta
    $sql = "SELECT MIN($coluna) AS min_date, MAX($coluna) AS max_date 
            FROM $banco
            WHERE '{$coluna}' IS NOT NULL";

    // Executa a query
    $result = $this->conn->query($sql);
    
    // Verifica se a query foi bem-sucedida
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'min' => $row['min_date'],
            'max' => $row['max_date']
        ];
    }
    
    // Retorna null se não houver resultados
    return null;
    }
    
    public function buscarContagensDinamicas(
        $tabelaFonte,
        $colunaChaveEstrangeira,
        $tabelaAlvo,
        $colunaChavePrimaria,
        $campoNome,
        array $condicoes = [],$ordenacao = 'contagem DESC') {
            // Validações básicas
            if (empty($tabelaFonte) || empty($colunaChaveEstrangeira) || empty($tabelaAlvo) || empty($colunaChavePrimaria) || empty($campoNome)) {
                return [];
            }
    
            // Monta os campos a serem selecionados
            $camposSelecionados = [
                "t.{$this->conn->real_escape_string($colunaChavePrimaria)} AS valor",
                "t.{$this->conn->real_escape_string($campoNome)} AS nome",
                "COUNT(f.{$this->conn->real_escape_string($colunaChaveEstrangeira)}) AS contagem"
            ];
            $camposSelecionadosStr = implode(', ', $camposSelecionados);
    
            // Monta a consulta SQL
            $sql = "SELECT $camposSelecionadosStr
                    FROM {$this->conn->real_escape_string($tabelaAlvo)} t
                    LEFT JOIN {$this->conn->real_escape_string($tabelaFonte)} f
                    ON t.{$this->conn->real_escape_string($colunaChavePrimaria)} = f.{$this->conn->real_escape_string($colunaChaveEstrangeira)}";
    
            // Adiciona condições, se houver
            if (!empty($condicoes)) {
                $sql .= ' WHERE ' . implode(' AND ', array_map(function ($condicao) {
                    return $this->conn->real_escape_string($condicao);
                }, $condicoes));
            }
    
            // Agrupa e ordena
            $sql .= " GROUP BY t.{$this->conn->real_escape_string($colunaChavePrimaria)}, t.{$this->conn->real_escape_string($campoNome)}";
            $sql .= " HAVING contagem > 0";
            if ($ordenacao) {
                $sql .= " ORDER BY {$this->conn->real_escape_string($ordenacao)}";
            }
            
    
            // Executa a consulta
            $resultado = $this->conn->query($sql);
    
            // Processa os resultados
            if ($resultado && $resultado->num_rows > 0) {
                $resultados = [];
                while ($linha = $resultado->fetch_assoc()) {
                    $resultados[] = [
                        'contagem' => $linha['contagem'],
                        'valor' => $linha['valor'],
                        'nome' => $linha['nome']
                    ];
                }
                return $resultados;
            }
    
            // Retorna array vazio se não houver resultados
            return [];
        }
    
    private function buscarMaiorMenor($tabela, $coluna) {
        // Validações básicas
        if (empty($tabela) || empty($coluna)) {
            return null;
        }

        // Monta a query otimizada
        $sql = "SELECT MAX({$this->conn->real_escape_string($coluna)}) AS maior,
                       MIN({$this->conn->real_escape_string($coluna)}) AS menor
                FROM {$this->conn->real_escape_string($tabela)}
                WHERE {$this->conn->real_escape_string($coluna)} IS NOT NULL";

        // Executa a query
        $resultado = $this->conn->query($sql);

        // Verifica se a query foi bem-sucedida
        if ($resultado && $resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();
            return [
                'maior' => $linha['maior'],
                'menor' => $linha['menor']
            ];
        }

        // Retorna null se não houver resultados
        return null;
    }
    
    public function buscarCidadesPorImoveis(
        $tabelaFonte,
        $colunaChaveEstrangeira,
        array $condicoes = [],$ordenacao = 'contagem DESC') {
        // Validações básicas
        if (empty($tabelaFonte) || empty($colunaChaveEstrangeira)) {
            return [];
        }

        // Monta a query
        $sql = "SELECT 
                    ec.endereco_cidade_id,
                    ec.endereco_cidade_nome,
                    ec.endereco_cidade_url,
                    COUNT(i.imovel_id) AS contagem
                FROM {$this->conn->real_escape_string($tabelaFonte)} i
                INNER JOIN enderecos e 
                    ON i.{$this->conn->real_escape_string($colunaChaveEstrangeira)} = e.endereco_id
                INNER JOIN enderecos_cidades ec 
                    ON e.endereco_cidade = ec.endereco_cidade_id";

        // Adiciona condições padrão e personalizadas
        $condicoesPadrao = [
            'i.imovel_status = 1',
            'e.endereco_status = 1',
            'ec.endereco_cidade_status = 1'
        ];
        $todasCondicoes = array_merge($condicoesPadrao, $condicoes);
        if (!empty($todasCondicoes)) {
            $sql .= ' WHERE ' . implode(' AND ', array_map(function ($condicao) {
                return $this->conn->real_escape_string($condicao);
            }, $todasCondicoes));
        }

        // Agrupa e ordena
        $sql .= " GROUP BY ec.endereco_cidade_id, ec.endereco_cidade_nome, ec.endereco_cidade_url";
        if ($ordenacao) {
            $sql .= " ORDER BY {$this->conn->real_escape_string($ordenacao)}";
        }

        // Executa a query
        $resultado = $this->conn->query($sql);

        // Processa os resultados
        if ($resultado && $resultado->num_rows > 0) {
            $resultados = [];
            while ($linha = $resultado->fetch_assoc()) {
                $resultados[] = [
                    'id' => $linha['endereco_cidade_id'],
                    'nome' => $linha['endereco_cidade_nome'],
                    'url' => $linha['endereco_cidade_url'],
                    'contagem' => $linha['contagem']
                ];
            }
            return $resultados;
        }

        // Retorna array vazio se não houver resultados
        return [];
    }

    public function buscaArray(
        $tabelaFonte,
        $colunaArray,
        $tabelaAlvo,
        $colunaChavePrimaria,
        $campoNome,
        array $condicoes = [],$ordenacao = 'contagem DESC') {
        // Validações básicas
        if (empty($tabelaFonte) || empty($colunaArray) || empty($tabelaAlvo) || empty($colunaChavePrimaria) || empty($campoNome)) {
            return [];
        }

        // Monta a query para buscar os arrays
        $sql = "SELECT {$this->conn->real_escape_string($colunaArray)} AS tags
                FROM {$this->conn->real_escape_string($tabelaFonte)}
                WHERE {$this->conn->real_escape_string($colunaArray)} IS NOT NULL
                AND {$this->conn->real_escape_string($colunaArray)} != '[]'
                AND {$this->conn->real_escape_string($colunaArray)} != ''";

        // Executa a query
        $resultado = $this->conn->query($sql);

        // Processa os arrays em PHP
        $tagIds = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                // Tenta decodificar como JSON
                $tags = json_decode($linha['tags'], true);
                if (is_array($tags)) {
                    foreach ($tags as $tagId) {
                        if (is_numeric($tagId)) {
                            $tagIds[] = (int)$tagId;
                        }
                    }
                }
            }
        }

        // Se não houver IDs válidos, retorna vazio
        if (empty($tagIds)) {
            return [];
        }

        // Conta as incidências de cada ID
        $contagens = array_count_values($tagIds);

        // Monta a query para buscar informações da tabela alvo
        $camposSelecionados = [
            "t.{$this->conn->real_escape_string($colunaChavePrimaria)} AS valor",
            "t.{$this->conn->real_escape_string($campoNome)} AS nome"
        ];
        $camposSelecionadosStr = implode(', ', $camposSelecionados);

        // Prepara a lista de IDs para a query
        $idsSanitizados = implode(',', array_map('intval', array_keys($contagens)));

        $sql = "SELECT $camposSelecionadosStr
                FROM {$this->conn->real_escape_string($tabelaAlvo)} t
                WHERE t.{$this->conn->real_escape_string($colunaChavePrimaria)} IN ($idsSanitizados)";

        // Adiciona condições, se houver
        if (!empty($condicoes)) {
            $sql .= ' AND ' . implode(' AND ', array_map(function ($condicao) {
                return $this->conn->real_escape_string($condicao);
            }, $condicoes));
        }

        // Executa a query (sem ORDER BY, pois contagem é ordenada em PHP)
        $resultado = $this->conn->query($sql);

        // Processa os resultados
        $resultados = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $id = $linha['valor'];
                $resultados[] = [
                    'contagem' => isset($contagens[$id]) ? $contagens[$id] : 0,
                    'valor' => $id,
                    'nome' => $linha['nome']
                ];
            }
        }

        // Ordena os resultados pela contagem em PHP
        if (strpos($ordenacao, 'contagem DESC') !== false) {
            usort($resultados, function ($a, $b) {
                return $b['contagem'] - $a['contagem'];
            });
        } elseif (strpos($ordenacao, 'contagem ASC') !== false) {
            usort($resultados, function ($a, $b) {
                return $a['contagem'] - $b['contagem'];
            });
        }

        return $resultados;
    }

    private function avaliacoes($tabela) {
        // Validação básica
        if (empty($tabela)) {
            return ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0];
        }

        // Monta a query com subquery para calcular médias
        $sql = "SELECT media_arredondada, COUNT(*) AS contagem
                FROM (
                    SELECT ROUND(AVG(a.avaliacao_estrelas)) AS media_arredondada
                    FROM avaliacoes a
                    WHERE a.avaliacao_modulo = '{$this->conn->real_escape_string($tabela)}'
                    AND a.avaliacao_status = 1
                    GROUP BY a.avaliacao_identificador
                    HAVING media_arredondada BETWEEN 1 AND 5
                ) medias
                GROUP BY media_arredondada";

        // Executa a query
        $resultado = $this->conn->query($sql);

        // Inicializa o resultado com zeros
        $resultados = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0
        ];

        // Processa os resultados
        if ($resultado && $resultado->num_rows > 0) {
            while ($linha = $resultado->fetch_assoc()) {
                $media = (int)$linha['media_arredondada'];
                $resultados[(string)$media] = (int)$linha['contagem'];
            }
        }

        return $resultados;
    }
    
    private function pegarDatasMeta($nome){

        $prefixo = $this->configs->prefixo;
        
        
        
        $banco = $this->configs->banco.'_meta';
        
        $string = $this->configs->banco;
        $partes = explode('_', $string); // Divide a string pelo '_'
        
        $iniciais = '';
        foreach ($partes as $parte) {
            if (!empty($parte)) { // Verifica se a parte não está vazia
                $iniciais .= $parte[0]; // Pega o primeiro caractere de cada parte
            }
        }
        
        $iniciais.= 'm';

        $coluna = $iniciais.'_valor';
        
        // Query otimizada para buscar MIN e MAX em uma única consulta
        $sql = "SELECT MIN($coluna) AS min_date, MAX($coluna) AS max_date 
                FROM $banco
                WHERE {$iniciais}_chave = '{$nome}'";
        // Executa a query
        $result = $this->conn->query($sql);
        
        // Verifica se a query foi bem-sucedida
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return [
                'min' => $row['min_date'],
                'max' => $row['max_date']
            ];
        }
        
        // Retorna null se não houver resultados
        return null;
    }
    
    private function pegarDatasPrincipais($nome){

        $prefixo = $this->configs->prefixo;
        
        $banco = $this->configs->banco;
        
        $coluna = $prefixo.'_'.$nome;
        // Query otimizada para buscar MIN e MAX em uma única consulta
        $sql = "SELECT MIN($coluna) AS min_date, MAX($coluna) AS max_date 
                FROM $banco";
        // Executa a query
        $result = $this->conn->query($sql);
        
        // Verifica se a query foi bem-sucedida
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return [
                'min' => $row['min_date'],
                'max' => $row['max_date']
            ];
        }
        
        // Retorna null se não houver resultados
        return null;
    }
    
    private function extracItem($dados, $hash, $nome){
        switch($dados->tipo){
            case 'text':
            case 'rich':
            case 'textarea':
                return ["tipo"=>"input", "key"=>$hash];
                break;
            case 'number':
                return ["tipo"=>"number"];
                break;
            case 'data':
                $principal = false;
                foreach($this->configs->colunas as $coluna){
                    if($dados->hash == $coluna->input){
                        $principal = $coluna->nome;
                        break;
                    }
                }
                
                
                if($principal){
                    return ["tipo"=>"data", "datas"=>$this->pegarDatasPrincipais($principal), 'key'=> $hash];
                }else{
                    return ["tipo"=>"data", "datas"=>$this->pegarDatasMeta($nome), 'key'=> $hash];
                }
                
                break;
            case 'select':
                $opcoes = [];
                if (!empty($dados->infos->opcoes->opcoes) && is_array($dados->infos->opcoes->opcoes) && count($dados->infos->opcoes->opcoes) > 1) {
                    foreach ($dados->infos->opcoes->opcoes as $opção) {
                        $opcoes[] = [
                            'contagem' => 0,
                            'valor' => $opção->valor,
                            'nome' => $opção->nome
                        ];
                    }
                }
            
                
                if($dados->infos->dinamica->{'opcoes-dinamicas'} == 1){
                    $config = $dados->infos->dinamica->configuracoes;
                    $modulo = $config->modulo;
                    $banco = $config->formulario;
                    $dir = __DIR__."/../conteudo/modulos/".$modulo;

                    $formulario = json_decode(file_get_contents($dir."/admins/configs/".$banco , true));

                    if($formulario){
                         $opcoes = $this->buscarContagensDinamicas(
                               $this->configs->banco,
                               $this->configs->prefixo.'_'.$nome,
                               $formulario->banco,
                               $formulario->prefixo.'_id',
                               $formulario->prefixo.'_nome',
                               [],
                               'contagem DESC'
                               );
                    }
             
                   
    
                }
   
                return ["tipo"=>"check", "opcoes"=>$opcoes,  "key"=>$hash];
                break;
        }
    }
    
    private function pegaDados($p){

        $chave = $p->itens[0]->nome;
        $tipo = intval($p->itens[0]->tipo);
        $hash = $p->itens[0]->hash;
        
        $banco = $this->configs->banco;
        $prefixo = $this->configs->prefixo;

        
        switch($tipo){
            case 1:
                $p = false;
                foreach($this->configs->colunas as $coluna){
                    if($coluna->nome == $chave){
                        $p = $coluna->input;
                        break;
                    }
                }
                if($p){
                    $infos = $this->encontrarItemPorHash($p);
                    
                    if($infos){
                             return $this->extracItem($infos, $hash, $chave);
                    }
          
     
                }
                break;
            case 2:
        
               switch($chave){
                   case 'dataUpdate':
                       return [
                           "tipo"=>"data",
                           "datas"=>$this->pegaDatas($banco, $prefixo."_update"),
                           "key"=>$hash,
                           "auxiliar"=>"Imagem Principal"
                           ];
                       break;
                   case 'dataCriacao':
                       return [
                           "tipo"=>"data",
                           "datas"=>$this->pegaDatas($banco, $prefixo."_data"),
                           "key"=>$hash 
                        ];
                       break;
 
                   case 'galeria':
                       return [
                           "tipo"=>"imagem",
                           "key"=>$hash,
                           "auxiliar"=>"Galeria"
                           ];
                       break;
                    case 'preco':
                        return [
                           "tipo"=>"preco",
                           "key"=>$hash 
                           ];
                       break;
                   case 'tags':
                       return [
                           "tipo"=>"check",
                           "key"=>$hash,
                           "opcoes"=>$this->buscaArray($banco, $prefixo.'_tags','categorias','categoria_id','categoria_nome' ,[],'contagem DESC')
                           ];
                       break;
                   case 'categoria':
                              return [
                           "tipo"=>"check",
                           "key"=>$hash,
                           "opcoes"=>$this->buscarContagensDinamicas(
                               $banco,
                               $prefixo.'_categoria',
                               'categorias',
                               'categoria_id',
                               'categoria_nome',
                               [],
                               'contagem DESC'
                               )
                           ];
                       break;
                   case 'autor':
                        return [
                           "tipo"=>"check",
                           "key"=>$hash,
                           "opcoes"=>$this->buscarContagensDinamicas(
                               $banco, $prefixo.'_autor',
                               'usuarios',
                               'usuario_id',
                               'usuario_display',
                               [],
                               'contagem DESC'
                               )
                           
                           ];
                       break;
                   case 'wildcard':
                        return [
                           "tipo"=>"check",
                           "key"=>$hash,
                           "opcoes"=>$this->buscarContagensDinamicas(
                               $banco, $prefixo.'_wildcard',
                               'wildcards_contas',
                               'wc_id',
                               'wc_nome',
                               [],
                               'contagem DESC'
                               )
                           
                           ];
                       break;
                   case 'enderecos':
                       return [
                           "tipo"=>"enderecos",
                           "key"=>$hash,
                           "opcoes"=>$this->buscarCidadesPorImoveis($banco, $prefixo.'_endereco')
                           ];
                       break;
 
                   case 'imagemDestaque':
                        return [
                           "tipo"=>"imagem",
                           "key"=>$hash,
                           "auxiliar"=>"Imagem Principal"
                           ];
                       break;
                   case 'avaliacoes':
                        return [
                           "tipo"=>"avaliacoes",
                           "key"=>$hash,
                           "valores"=>$this->avaliacoes($banco)
                           ];
                       break;
               }
                break;
            case 3:

                $p = false;
                foreach($this->configs->metas as $metaHash=>$valor){
                    if($valor == $chave){
                        $p = $metaHash;
                        break;
                    }
                }

                if($p){
                    $infos = $this->encontrarItemPorHash($p);

                    if($infos){
                            return $this->extracItem($infos, $hash, $chave);
                    }
          
     
                }
         
                
                break;
        }
    }

    private function extract($principais){
        
   
            $basic = [];
         foreach($principais as $principal){
            $nome = $principal->nome;
            $itens = $principal->itens[0];
            
            $name = $itens->nome;
            $tipo = intval($itens->tipo);
            
            $basic[] = [
                "nome"=>$nome,
                "dados"=>$this->pegaDados($principal)
                ];
        }
        if(empty($basic)){
            return false;
        }
        return $basic;
    }
    
    function render(){
        if(!$this->modulo && !$this->chave){
            return ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros necessarios"];
        }
        
        $this->dir = __DIR__."/../conteudo/modulos/".$this->modulo;
        if(!is_dir($this->dir)){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um módulo válido"];
        }
        
        if(!file_exists($this->dir."/admins/filtros/".$this->chave.".json")){
            return ["erro"=>true, "mensagem"=>"Não foi enviado um filtro válido"];
        }
        
        
        $conteudo = json_decode(file_get_contents($this->dir."/admins/filtros/".$this->chave.".json", true));
        
        
        $this->filtro = $conteudo;
    
        
        
        if(!file_exists($this->dir."/admins/apis/".$conteudo->api.".json")){
            return ["erro"=>true, "mensagem"=>"A API referênciada não existe"];
        }
        
        $this->api = json_decode(file_get_contents($this->dir."/admins/apis/".$conteudo->api.".json", true));
        
        
        if(!file_exists($this->dir."/admins/formularios/".$this->api->banco.".json")){
            return ["erro"=>true, "mensagem"=>"O formulário referênciada não existe"];
        }
        
        $this->formulario = json_decode(file_get_contents($this->dir."/admins/formularios/".$this->api->banco.".json", true));
        
        if(!file_exists($this->dir."/admins/configs/".$this->api->banco.".json")){
            return ["erro"=>true, "mensagem"=>"O formulário referênciada não existe"];
        }

        
        $this->configs = json_decode(file_get_contents($this->dir."/admins/configs/".$this->api->banco.".json", true));
        
        
        $modelos = [];
        foreach($conteudo->modelosExibicao as $modelo){
            if($modelo->ativo && $modelo->callback){
                $modelos[] = ["cb"=>$modelo->callback, "itens"=>$modelo->itensPorLinha];
            }
        }

        
        $itens = $conteudo->filtros;
    
        $ordenadores = [];
        foreach($conteudo->ordenadores as $ordem){
            $ordenadores[] =  $ordem->id;
        }
        
        
        
        $filtro = [
            "js"=>file_exists($this->dir."/assets/componentes.js"),
            "ordenadores"=>$ordenadores,
            "exibicao"=>$conteudo->exibicao ?? false,
            "modelos"=>$modelos,
            "filtro"=>$conteudo->filtroConfig ?? false,
            "basico"=>$this->extract($itens->principais),
            "avancados"=>$this->extract($itens->avancados),
            "api"=>$this->filtro->api,
            "layout"=>$this->filtro->layout ?? 1
            ];
        
     
        return ["sucesso"=>true, "filtro"=>$filtro];
    }
    
    function close(){
        $this->conn->close();
    } 
}

$filtro = new Filtro();
$resposta = $filtro->render();
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$filtro->close();





?>