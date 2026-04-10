<?php

$switch = new Swith([
    "name" => "ativo",
    "descricao" => "Habilite o login e cadastro social usando a Apple",
    "titulo" => "Logar com a Apple",
]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "id");
$input->set("label", "Service ID");
$input->set("descricao", "Criado no Apple Developer Program.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "redirecionamento");
$input->set("label", "Redirect URI");
$input->set("descricao", "A URL para onde o usuário será redirecionado após a autenticação.");
echo $input->html();

?>
