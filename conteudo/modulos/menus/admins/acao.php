<?
$setup = [];
$setup["banco"] = [
    "bancoNome"=>"menus",
    "prefixo"=>"menu",
    "hash"=>"menu_hash",
    ];

$setup["lista"] = [
    ["chave"=>"menu_id", "valor"=>"id", "meta"=>false, "header"=>"id"],
    ["chave"=>"menu_nome", "valor"=>"titulo", "meta"=>false, "header"=>"Título"],
    ["chave"=>"menu_espaco", "valor"=>"espaco", "meta"=>false, "header"=>"Espaço"],
    ];

$setup["chaves"] = [
    ["chave"=>"menu_nome", "obrigatorio"=>false, "meta"=>false, "map"=>"titulo"],
    ["chave"=>"menu_espaco", "obrigatorio"=>false, "meta"=>false, "map"=>"espaco"],
    ["chave"=>"menu_estrutura", "obrigatorio"=>false, "meta"=>false, "map"=>"estrutura"]
    ];
$setup["acoes"] =[
    ["nome"=>"editar", "tipo"=>"url" , "destino"=>"a/menus/editar/"],
    ["nome"=>"deletar", "tipo"=>"funcion", "destino"=>"deleta" ,"chave"=>"menus_deleta"]
    ];
$setup["callBack"] = [
    "cadastro"=>"a/menus/editar/",
    ];
$setup["mensagens"] = [
    "cadastro"=>["sucesso"=>"Jogador Cadastrado com sucesso", "erro"=>"Falha ao Cadastrar Usuário"],
    "editar"=>["sucesso"=>"Jogador Editado com Sucesso", "erro"=>"Falha ao editar Jogador"],
    "deleta"=>["sucesso"=>"", "erro"=>""],
    ];
?>