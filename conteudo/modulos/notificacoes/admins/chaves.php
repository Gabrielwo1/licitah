<?


session_start();

if(empty($_SESSION["id"])){
    $resposta = ["erro"=>true, "mensagem"=>"O usuário não está autenticado"];
}

$modulo = $_POST["modulo"] ?? false;
$form = $_POST["form"] ?? false;

if(!$modulo || !$form){
    $resposta = ["erro"=>true, "mensagem"=>"Não foram enviados todos os parametros válidos"];
}

if(!is_dir(__DIR__."/../../".$modulo) || !file_exists(__DIR__."/../../".$modulo."/admins/configs/".$form.".json")){
    $resposta = ["erro"=>true, "mensagem"=>"Não foram encontrados informações com os paramtros enviados"];
}

$dado = json_decode(file_get_contents(__DIR__."/../../".$modulo."/admins/configs/".$form.".json"), true);

$prefixo = $dado["prefixo"];
$colunas = [];


foreach($dado["colunas"] as $coluna){
    $colunas[$prefixo."_".$coluna["nome"]] = $prefixo."_".$coluna["nome"];
}

foreach($dado["colunas"] as $coluna){
    $colunas[$prefixo."_".$coluna["nome"]] = $prefixo."_".$coluna["nome"];
}


if(!empty($dado["url"])){
    $colunas[$prefixo."_url"] = $prefixo."_url";
}


if(!empty($dado["estrutura"]['imagemDestaque'])){
    $colunas[$prefixo."_imagem"] = $prefixo."_imagem";
}



$resposta = ["sucesso"=>true,"itens"=>$colunas];
echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);



?>