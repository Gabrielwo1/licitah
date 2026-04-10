<?
// include __DIR__."/../../../../admin/configmodulo.php";

// include __DIR__."/apis/atualizarDados.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

// session_start();



// function call_salvo($cb, $obj){

    
    // if(isset($cb["sucesso"]) && isset($obj->data["jVHdr7KFhzaaRs5SdOiIiByc0MLfHg"])){
    //     $input = $obj->data["jVHdr7KFhzaaRs5SdOiIiByc0MLfHg"];
    //     $autor = $_SESSION['id'];
        
    //     $dado_falso = false;
        
    //     $sql = "SELECT * FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_pesquisa = '$input' AND licitacoes_pesquisa_autor = '$autor'";
        
    //     $resultado = $obj->conn->query($sql);
        
    //     if($resultado->num_rows == 1){
    //         $dado = $resultado->fetch_assoc();
    //         $id = $dado['licitacoes_pesquisa_id'];
    //         $api = new APiDeDados($input);
    //         $resposta = $api->pesquisarLicitacao();


    //         if(!isset($resposta['sucesso']) || !$resposta['sucesso']){
    //             $dado_falso = true;
    //             $mensagem = $resposta['mensagem'];
    //         }else{
    //             $licitacao = $resposta['licitacao'];
                
    //             $update_query = "UPDATE licitacoes_pesquisas SET licitacoes_pesquisa_numero = '$licitacao' WHERE licitacoes_pesquisa_id = '$id'";
                
    //             $update_resultado = $obj->conn->query($update_query);
    //         }
            
            
            


    //     }
    //     else{
    //         $dado_falso = true;
    //         $mensagem = 'Essa pesquisa já foi feita';
    //     }
        
    //     if($dado_falso){
    //         $id = $obj->novoId;
                
    //         $sql = "DELETE FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
            
    //         $resultado = $obj->conn->query($sql);
            
    //         return ['erro'=>true, 'custom-message'=> $mensagem];
    //     }
    // }

    
    
    // return $cb;
    
    
    // configModulo("licitacoes");
    // $api = verModulo("api", "chave", '');
    
    // if(isset($api) && $api){
    //     $dado_falso = false;
    
    //     if(isset($cb["sucesso"]) && isset($obj->data["jVHdr7KFhzaaRs5SdOiIiByc0MLfHg"])){
            
            
    //         $input = $obj->data["jVHdr7KFhzaaRs5SdOiIiByc0MLfHg"];
    //         $autor = $_SESSION['id'];
            
    //         $sql = "SELECT * FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_pesquisa = '$input' AND licitacoes_pesquisa_autor = '$autor'";
            
    //         $resultado = $obj->conn->query($sql);
            
    //         if($resultado->num_rows == 1){
    //             $vincular = new APiDeDados($api, $obj);
    //             $resposta = $vincular->inicio();
                
    //             if(isset($resposta['sucesso']) && $resposta['sucesso']){
    //                 return $cb;
    //             }
    //             else{
    //                 $dado_falso = true;
    //                 $mensagem = 'Número de processo inválido';
    //             }
    //         }
    //         else{
    //             $dado_falso = true;
    //             $mensagem = 'Essa pesquisa já foi feita';
    //         }
    //     }
    // }
    // else{
    //     $dado_falso = true;
    //     $mensagem = 'Passe o parâmetro de api do governo';
    // }
    
    // if($dado_falso){
    //     $id = $obj->novoId;
            
    //     $sql = "DELETE FROM licitacoes_pesquisas WHERE licitacoes_pesquisa_id = '$id'";
        
    //     $resultado = $obj->conn->query($sql);
        
    //     return ['erro'=>true, 'custom-message'=> $mensagem];
    // }
        
    // return $cb;

// }



// class APiDeDados{
//     public $chave;
//     public $conn;
//     public $processo;
//     public $dados;
//     public $obj;
//     public $maisAtualizado;
//     public $autor;
//     public $participantes;
//     public $itens;
//     public $contratos;
//     public $modalidades;
    
//     function __construct($chave, $obj){
//         $this->chave = $chave;
//         $this->conn = $obj->conn;
//         $this->processo = $obj->data["jVHdr7KFhzaaRs5SdOiIiByc0MLfHg"];
//         $this->obj = $obj;
//         $this->autor = $_SESSION['id'];
//     }
    
//     function mapDados($dados){
//         return [
//             'governo' => $dados['id'],
//             'Licitacao_Numero' => $dados['licitacao']['numero'],
//             'objeto' => $dados['licitacao']['objeto'],
//             'Licitacao_Numero_Processo' => $dados['licitacao']['numeroProcesso'],
//             'Contato_Responsavel'=> $dados['licitacao']['contatoResponsavel'],
//             'Data_Resultado_Compra' => $dados['dataResultadoCompra'],
//             'Data_Abertura' => $dados['dataAbertura'],
//             'Data_Referencia' => $dados['dataReferencia'],
//             'Data_Publicacao' => $dados['dataPublicacao'],
//             'Situacao_Compra' => $dados['situacaoCompra'],
//             'Modalidade_Licitacao' => $dados['modalidadeLicitacao'],
//             'Instrumento_Legal' => $dados['instrumentoLegal'],
//             'Valor' => $dados['valor'],
//             'Municipio_Codigo_IBGE' => $dados['municipio']['codigoIBGE'],
//             'Municipio_Nome_IBGE' => $dados['municipio']['nomeIBGE'],
//             'Municipio_Codigo_Regiao' => $dados['municipio']['codigoRegiao'],
//             'Municipio_Nome_Regiao' => $dados['municipio']['nomeRegiao'],
//             'Municipio_Pais' => $dados['municipio']['pais'],
//             'Municipio_UF_Sigla' => $dados['municipio']['uf']['sigla'],
//             'Municipio_UF_Nome' => $dados['municipio']['uf']['nome'],
//             'Unidade_Gestora_Codigo' => $dados['unidadeGestora']['codigo'],
//             'Unidade_Gestora_Nome' => $dados['unidadeGestora']['nome'],
//             'Unidade_Gestora_Descricao_Poder' => $dados['unidadeGestora']['descricaoPoder'],
//             'Orgao_Vinculado_Codigo_SIAFI' => $dados['unidadeGestora']['orgaoVinculado']['codigoSIAFI'],
//             'Orgao_Vinculado_CNPJ' => $dados['unidadeGestora']['orgaoVinculado']['cnpj'],
//             'Orgao_Vinculado_Sigla' => $dados['unidadeGestora']['orgaoVinculado']['sigla'],
//             'Orgao_Vinculado_Nome' => $dados['unidadeGestora']['orgaoVinculado']['nome'],
//             'Orgao_Maximo_Codigo' => $dados['unidadeGestora']['orgaoMaximo']['codigo'],
//             'Orgao_Maximo_Sigla' => $dados['unidadeGestora']['orgaoMaximo']['sigla'],
//             'Orgao_Maximo_Nome' => $dados['unidadeGestora']['orgaoMaximo']['nome'],
//         ];

//     }
    
//     function meta($id, $chave, $valor, $infos) {
//         if (empty($valor) || empty($id) || empty($chave) || empty($infos)) {
//             return;
//         }
    
//         $banco = $infos['banco'];
//         $prefixo = $infos['prefixo'];
//         $sufixo = $infos['sufixo'];
    
//         $tableName = "{$banco}_meta";
    
//         $query = "SELECT {$sufixo}_id FROM $tableName WHERE {$sufixo}_$prefixo=? AND {$sufixo}_chave=?";
//         $stmt = $this->conn->prepare($query);
//         $stmt->bind_param("is", $id, $chave);
//         $stmt->execute();
//         $result = $stmt->get_result();
    
//         if ($result->num_rows == 0) {
//             $insertQuery = "INSERT INTO $tableName ({$sufixo}_$prefixo, {$sufixo}_chave, {$sufixo}_valor) VALUES (?, ?, ?)";
//             $stmt = $this->conn->prepare($insertQuery);
//             $stmt->bind_param("iss", $id, $chave, $valor);
//         } else {
//             $row = $result->fetch_assoc();
//             $updateQuery = "UPDATE $tableName SET {$sufixo}_valor=? WHERE {$sufixo}_id=?";
//             $stmt = $this->conn->prepare($updateQuery);
//             $stmt->bind_param("si", $valor, $row["{$sufixo}_id"]);
//         }
    
//         $stmt->execute();
//         $stmt->close();
//     }
    
//     function hasher($length = 32) {
//         // Definindo o conjunto de caracteres para incluir números, letras maiúsculas e minúsculas
//         $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//         $charactersLength = strlen($characters);
//         $randomString = '';
    
//         // Gera uma string aleatória de $length caracteres
//         for ($i = 0; $i < $length; $i++) {
//             $randomString .= $characters[random_int(0, $charactersLength - 1)];
//         }
    
//         return $randomString;
//     }
    
//     function update_vinculo($id_banco, $id){
//         $sql = "UPDATE licitacoes_pesquisas SET licitacoes_pesquisa_numero = '$id_banco' WHERE licitacoes_pesquisa_id = '$id'" ;
            
//         $resultado = $this->conn->query($sql);
//     }
    
//     function modalidade(){
//         if(!isset($this->modalidades)){
//             $url =  'https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/modalidades';
        
//             $modalidades = $this->mandarUrl($url);
            
//             $this->modalidades = $modalidades;
//         }
//         else{
//             $modalidades = $this->modalidades;
//         }
        
        
//         $modalidadeEncontrado = 0;
        
//         foreach ($modalidades as $modalidade) {
//             if ($modalidade["descricao"] === $this->dados['Modalidade_Licitacao']) {
//                 $modalidadeEncontrado = $modalidade["codigo"];
//                 break;
//             }
//         }
        
        
        
//         return $modalidadeEncontrado;
//     }
    
//     function mandarUrl($url){
        
//         $client = curl_init($url);
                
//         $headers = ['chave-api-dados:'. $this->chave];
        
//         curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
        
//         curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
        
//         $response = curl_exec($client);
        
//         $dado = json_decode($response, TRUE);
        
//         return $dado;
//     }
    
//     function conferirParticipante($part){
//         $dado_participante = mysqli_real_escape_string($this->conn, $part['idParticipante']);
        
//         $sql_existe = "SELECT * FROM licitacoes_participantes WHERE licitacoes_participante_participante = '$dado_participante'";
        
//         $resultado_existe = $this->conn->query($sql_existe);
        
//         if($resultado_existe->num_rows == 0){
            
//             $cpf_cnpj =  mysqli_real_escape_string($this->conn, $part['cpfCnpj']);
//             $nome =  mysqli_real_escape_string($this->conn, $part['nome']);
//             $tipo = mysqli_real_escape_string($this->conn, $part['tipoParticipante']);
//             $hash = mysqli_real_escape_string($this->conn, $this->hasher());

//             $sql = "INSERT INTO licitacoes_participantes (licitacoes_participante_participante, licitacoes_participante_nome, licitacoes_participante_cpf_cnpj, licitacoes_participante_tipo, licitacoes_participante_hash)
//             VALUES ('$dado_participante', '$nome', '$cpf_cnpj', '$tipo', '$hash')";

//             $resultado = $this->conn->query($sql);
            
//             $id = $this->conn->insert_id;

//         }
//         else{
//             $registro_existente = $resultado_existe->fetch_assoc();
//             $id = $registro_existente['licitacoes_participante_id'];
//         }
        
//         array_push($this->participantes, ['id'=> $id, 'id_governo'=> $dado_participante]);
//     }
    
//     function instalarParticipantes(){
//         $this->participantes = [];
        
//         $modalidade = intval($this->modalidade());
        
//         if(intval($modalidade)){
//             $pagina = 1;
        
//             $ug = intval($this->dados['Unidade_Gestora_Codigo']);
            
//             $numero_licitacao = $this->dados['Licitacao_Numero'];
            
//             while(true){
//                 $url =  'https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/participantes?codigoUG='.$ug.'&numero='.$numero_licitacao.'&codigoModalidade='.$modalidade.'&pagina='.$pagina;
            
//                 $dado = $this->mandarUrl($url);
                
//                 if(count($dado) > 0){
                    
//                     foreach($dado as $participante){
//                         $this->conferirParticipante($participante);
//                     }
                    
//                     $pagina++;
//                 }
//                 else{
//                   break;
//                 }
//             }
//         }
        
//     }
    
//     function conferirItem($item){
        
//         $codigo_compra = mysqli_real_escape_string($this->conn, $item['codigoItemCompra']);
        
//         $sql_existe = "SELECT * FROM licitacoes_itens WHERE licitacoes_item_codigo = '$codigo_compra'";
        
//         $resultado_existe = $this->conn->query($sql_existe);
        
//         if($resultado_existe->num_rows == 0){
//             $numero_item = mysqli_real_escape_string($this->conn, $item['numero']);
//             $desc = mysqli_real_escape_string($this->conn, $item['descricao']);
//             $quantidade = mysqli_real_escape_string($this->conn, $item['quantidade']);
//             $valor = mysqli_real_escape_string($this->conn, str_replace('.', '', $item['valor']).str_replace(',', '.', $item['valor']));
//             $desc_complementar = mysqli_real_escape_string($this->conn, $item['descComplementarItemCompra']);
//             $desc_unidade = mysqli_real_escape_string($this->conn, $item['descUnidadeFornecimento']);
//             $hash = mysqli_real_escape_string($this->conn, $this->hasher());
            
            
//             foreach($this->participantes as $part){
//                 if($part['id_governo'] == $item['idVencedor']){
//                     $participante = intval($part['id']);
//                     break;
//                 }
//             }
            
//             $sql = "INSERT INTO licitacoes_itens (licitacoes_item_codigo, licitacoes_item_numero, licitacoes_item_quantidade, licitacoes_item_valor, licitacoes_item_desc, licitacoes_item_desc_complementar, licitacoes_item_desc_unidade, licitacoes_item_participante, licitacoes_item_hash)
//             VALUES ('$codigo_compra', '$numero_item', '$quantidade', '$valor', '$desc', '$desc_complementar','$desc_unidade', '$participante' ,'$hash')";
            
//             $resultado = $this->conn->query($sql);
            
//             $id = $this->conn->insert_id;
//         }
//         else{
//             $registro_existente = $resultado_existe->fetch_assoc();
//             $id = $registro_existente['licitacoes_item_id'];
//         }
        
//         array_push($this->itens, ['id'=> $id, 'id_governo'=> $codigo_compra]);
//     }
    
//     function instalarItens(){
//         $this->itens = [];
         
//         $modalidade = intval($this->modalidade());
        
//         if(intval($modalidade)){
//             $pagina = 1;
        
//             $id_governo = intval($this->dados['governo']);
            
//             while(true){
//                 $url =  'https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/itens-licitados?id='.$id_governo.'&pagina='.$pagina;
                
//                 $dado = $this->mandarUrl($url); 

//                 if(count($dado) > 0){
//                     foreach($dado as $item){
//                         $this->conferirItem($item);
//                     }
                    
//                     $pagina++;
//                 }
//                 else{
//                   break;
//                 }
//             }
//         }
//     }
    
//     function mapContrato($dados){
//         return [
//             'contrato'=> $dados['id'],
//             'numero'=> $dados['numero'],
//             'objeto'=> $dados['objeto'],
//             'fundamento_legal'=> $dados['fundamentoLegal'],
//             'Numero_Processo'=> $dados['numeroProcesso'],
//             'Situacao_Contrato'=> $dados['situacaoContrato'],
//             'Unidade_Gestora_Codigo' => $dados['unidadeGestora']['codigo'],
//             'Unidade_Gestora_Nome' => $dados['unidadeGestora']['nome'],
//             'Unidade_Gestora_Descricao_Poder' => $dados['unidadeGestora']['descricaoPoder'],
//             'Orgao_Vinculado_Codigo_SIAFI' => $dados['unidadeGestora']['orgaoVinculado']['codigoSIAFI'],
//             'Orgao_Vinculado_CNPJ' => $dados['unidadeGestora']['orgaoVinculado']['cnpj'],
//             'Orgao_Vinculado_Sigla' => $dados['unidadeGestora']['orgaoVinculado']['sigla'],
//             'Orgao_Vinculado_Nome' => $dados['unidadeGestora']['orgaoVinculado']['nome'],
//             'Orgao_Maximo_Codigo' => $dados['unidadeGestora']['orgaoMaximo']['codigo'],
//             'Orgao_Maximo_Sigla' => $dados['unidadeGestora']['orgaoMaximo']['sigla'],
//             'Orgao_Maximo_Nome' => $dados['unidadeGestora']['orgaoMaximo']['nome'],
//             'Data_Assinatura'=> $dados['dataAssinatura'],
//             'Data_Publicacao_DOU'=> $dados['dataPublicacaoDOU'],
//             'Data_Inicio_vigencia'=> $dados['dataInicioVigencia'],
//             'Valor_Inicial'=> $dados['valorInicialCompra'],
//             'Data_Fim_Vigencia'=> $dados['dataFimVigencia'],
//             'Valor_Final'=> $dados['valorFinalCompra'],
//         ];
//     }
    
//     function instalarFornecedor($forn){
//         $id_fornecedor = mysqli_real_escape_string($this->conn, $forn['id']);
        
//         $sql_existe = "SELECT * FROM licitacoes_fornecedores WHERE licitacoes_fornecedor_codigo = '$id_fornecedor'";
        
//         $resultado_existe = $this->conn->query($sql_existe);
        
//         if($resultado_existe->num_rows == 0){
//             $hash = mysqli_real_escape_string($this->conn, $this->hasher());
//             $cpf = mysqli_real_escape_string($this->conn, $forn['cpfFormatado']);
//             $cnpj = mysqli_real_escape_string($this->conn, $forn['cnpjFormatado']);
//             $numero_inscricao = mysqli_real_escape_string($this->conn, $forn['numeroInscricaoSocial']);
//             $nome = mysqli_real_escape_string($this->conn, $forn['nome']);
//             $razao_social = mysqli_real_escape_string($this->conn, $forn['razaoSocialReceita']);
//             $nome_fantasia = mysqli_real_escape_string($this->conn, $forn['nomeFantasiaReceita']);
//             $tipo = mysqli_real_escape_string($this->conn, $forn['tipo']);
            
//             $sql = "INSERT INTO licitacoes_fornecedores (licitacoes_fornecedor_codigo, licitacoes_fornecedor_cpf, licitacoes_fornecedor_cnpj, licitacoes_fornecedor_numero_inscricao_social, licitacoes_fornecedor_nome, licitacoes_fornecedor_razao_social, licitacoes_fornecedor_nome_fantasia, licitacoes_fornecedor_tipo, licitacoes_fornecedor_hash)
//             VALUES ('$id_fornecedor', '$cpf', '$cnpj', '$numero_inscricao','$nome', '$razao_social', '$nome_fantasia', '$tipo', '$hash')";
            
//             $resultado = $this->conn->query($sql);
            
//             $id = $this->conn->insert_id;
//         }
//         else{
//             $registro_existente = $resultado_existe->fetch_assoc();
//             $id = $registro_existente['licitacoes_fornecedor_id'];
//         }
        
//         return $id; 
//     }
    
//     function conferirContratos($cont){
//         $infosCont = $this->mapContrato($cont);
        
//         $id_governo = mysqli_real_escape_string($this->conn, $infosCont['contrato']);
        
//         $sql_existe = "SELECT * FROM licitacoes_contratos WHERE licitacoes_contrato_contrato = '$id_governo'";
        
//         $resultado_existe = $this->conn->query($sql_existe);
        
//         if($resultado_existe->num_rows == 0){
//             $hash = mysqli_real_escape_string($this->conn, $this->hasher());
//             $numero = mysqli_real_escape_string($this->conn, $infosCont['numero']);
//             $objeto = mysqli_real_escape_string($this->conn, $infosCont['objeto']);
//             $fundamento = mysqli_real_escape_string($this->conn, $infosCont['fundamento_legal']);
//             $fornecedor = mysqli_real_escape_string($this->conn, $this->instalarFornecedor($cont['fornecedor']));
            
//             $sql = "INSERT INTO licitacoes_contratos (licitacoes_contrato_contrato, licitacoes_contrato_numero, licitacoes_contrato_objeto, licitacoes_contrato_fundamento_legal, licitacoes_contrato_fornecedor, licitacoes_contrato_hash)
//             VALUES ('$id_governo', '$numero', '$objeto', '$fundamento', '$fornecedor', '$hash')";
            
//             $resultado = $this->conn->query($sql);
            
//             $id = $this->conn->insert_id;
            
//             $chaves_excluidas = ['numero', 'contrato', 'objeto', 'fornecedor', 'licitacao', 'fundamento_legal'];
            
//             $banco = [
//                 'banco'=>'licitacoes_contratos',
//                 'prefixo'=>'licitacoes_contrato',
//                 'sufixo'=>'lcm'
//             ];
            
//             $id = $this->conn->insert_id;
    
//             foreach ($infosCont as $chave => $valor) {
//                 if (!in_array($chave, $chaves_excluidas)) {
//                     $this->meta($id, $chave, $valor, $banco);
//                 }
//             }
            
//         }else{
//             $registro_existente = $resultado_existe->fetch_assoc();
//             $id = $registro_existente['licitacoes_contratos_id'];
//         }
        
//         array_push($this->contratos, ['id'=> $id, 'id_governo'=> $id_governo]);
//     }
    
//     function instalarContratos(){
//         $this->contratos = [];
         
//         $modalidade = intval($this->modalidade());
        
//         if(intval($modalidade)){
//             $ug = intval($this->dados['Unidade_Gestora_Codigo']);
            
//             $numero_licitacao = $this->dados['Licitacao_Numero'];
            
//             $url =  'https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/contratos-relacionados-licitacao?codigoUG='.$ug.'&numero='.$numero_licitacao.'&codigoModalidade='.$modalidade;
            
//             $dado = $this->mandarUrl($url); 
                
//             if(count($dado) > 0){
//                 foreach($dado as $contrato){
//                     $this->conferirContratos($contrato);
//                 }
//             }
//         }
//     }
    
//     function updateContrato($contrato, $licitacao){
//         if (empty($contrato) || empty($licitacao)) {
//             return false; // ou lançar uma exceção
//         }
        
        
//         $query = "UPDATE licitacoes_contratos SET licitacoes_contrato_licitacao = ? WHERE licitacoes_contrato_id = ?";
         
//         if ($stmt = $this->conn->prepare($query)) {
//             $stmt->bind_param("ii", $licitacao, $contrato);
    
//             if ($stmt->execute()) {
//                 $stmt->close();
//                 return true;
//             } else {
//                 $stmt->close();
//                 return false;
//             }
//         } else {
//             return false;
//         }
//     }
    
//     function cadastrar($dados){
//         $this->dados = $this->mapDados($dados);
        
//         $id_governo = mysqli_real_escape_string($this->conn, $this->dados['governo']);
        
//         $sql_existe = "SELECT * FROM licitacoes WHERE licitacao_governo = '$id_governo'";
        
//         $resultado_existe = $this->conn->query($sql_existe);

//         if($resultado_existe->num_rows == 0){
//             $objeto = mysqli_real_escape_string($this->conn, $this->dados['objeto']);
//             $hash = mysqli_real_escape_string($this->conn, $this->hasher());
//             $url = mysqli_real_escape_string($this->conn, $this->hasher(16));
            
//             $this->instalarParticipantes();
//             $this->instalarItens();
//             $this->instalarContratos();
            
//             $participantes = [];
            
//             foreach($this->participantes as $participante){
//                 array_push($participantes, $participante['id']);
//             }
            
//             $participantes = mysqli_real_escape_string($this->conn, json_encode($participantes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            
            
//             $itens = [];
            
//             foreach($this->itens as $item){
//                 array_push($itens, $item['id']);
//             }
            
//             $itens = mysqli_real_escape_string($this->conn, json_encode($itens, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            
            
//             $contratos = [];
            
//             foreach($this->contratos as $contrato){
//                 array_push($contratos, $contrato['id']);
//             }
            
//             $contratosJSON = mysqli_real_escape_string($this->conn, json_encode($contratos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            
//             $sql = "INSERT INTO licitacoes (licitacao_governo, licitacao_objeto, licitacao_hash, licitacao_url, licitacao_participantes, licitacao_itens, licitacao_contratos)
//             VALUES ('$id_governo', '$objeto', '$hash', '$url', '$participantes', '$itens', '$contratosJSON')";
            
//             $resultado = $this->conn->query($sql);
            
//             $id = $this->conn->insert_id;
            
//             $chaves_excluidas = ['governo', 'objeto'];
            
//             $banco = [
//                 'banco'=>'licitacoes',
//                 'prefixo'=>'licitacao',
//                 'sufixo'=>'lm'
//             ];
    
//             foreach ($this->dados as $chave => $valor) {
//                 if (!in_array($chave, $chaves_excluidas)) {
//                     $this->meta($id, $chave, $valor, $banco);
//                 }
//             }
            
//             if(count($contratos) > 0){
//                 foreach($contratos as $c){
//                     $this->updateContrato($c, $id);
//                 }
//             }
//         }
//         else{
//             $registro_existente = $resultado_existe->fetch_assoc();
//             $id = $registro_existente['licitacao_id'];
            
//         }
        
//         if($this->maisAtualizado == $this->dados['governo']){
//             $this->update_vinculo($id, $this->obj->novoId);
//         }
//     }
    
//     function inicio(){
//         $processo = $this->processo;
        
//         $url = 'https://api.portaldatransparencia.gov.br/api-de-dados/licitacoes/por-processo?processo='.$processo;
        
//         $dado = $this->mandarUrl($url);
   
//         $variavel = false;
        
//         $latestLicitacao = array_reduce($dado, function ($carry, $item) {
//             if ($carry === null || strtotime($item['dataReferencia']) > strtotime($carry['dataReferencia'])) {
//                 return $item;
//             }
//             return $carry;
//         });
        
//         $this->maisAtualizado = $latestLicitacao['id'];
        
//         foreach($dado as $d){
//             if(isset($d['id']) && intval($d['id'])){
                
//                 $resposta = $this->cadastrar($d);
                
//                 $variavel = true;
//             }
//         }
        
//         if($variavel){
//             return ['sucesso'=>true];
//         }
//         else{
//             return['erro'=>true];
//         }
        
//     }
// }

?>