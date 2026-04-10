<?
session_start();

$chaves = ["id","nome","email","funcao","subfuncao","ativo","sessao_hash","iniciais", "foto", "capa"];

$resposta = [];
$autorizado = true;
foreach($chaves as $item){
    if(!isset($_SESSION[$item])){
        $autorizado = false;
        break;
    }
}

$resposta["logado"] = $autorizado;

$infos = [];
if($autorizado){
    foreach($chaves as $item){
        $infos[$item] = $_SESSION[$item];
    }
    $resposta["infos"] = $infos;
}



echo json_encode($resposta);
?>