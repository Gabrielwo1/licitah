<?

function loadFile($modulo, $nome, $pasta) {
    $modulo = basename($modulo); 
    $id = basename($nome);
    $arquivo = __DIR__."/../conteudo/modulos/$modulo/admins/$pasta/$id.json";
    
    if (!file_exists($arquivo)) {
        return ["erro" => true, "mensagem" => "A tabela enviada não é válida"];
    }
    
    $conteudo = file_get_contents($arquivo);
    $array = json_decode($conteudo, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["erro" => true, "mensagem" => "Erro ao decodificar JSON"];
    }
        
    return ["sucesso" => true, "conteudo" => $array];
}
    

function iniciais($texto) {
    $palavras = explode('_', $texto);
    $resultado = '';
    
    foreach ($palavras as $palavra) {
        $resultado .=  strtolower(substr($palavra, 0, 1));
    }
          
    return $resultado;
}




?>