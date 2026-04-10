<?php

$switch = new Swith([
    "name" => "ativo",
    "descricao" => "Habilite o login e cadastro social usando o Twitter",
    "titulo" => "Logar com o Twitter",
]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "id");
$input->set("label", "API Key");
$input->set("descricao", "Obtida ao criar uma aplicação no Twitter Developer.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "segredo");
$input->set("label", "API Secret Key");
$input->set("descricao", "Gerada junto com a API Key.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "redirecionamento");
$input->set("label", "Redirect URI");
$input->set("descricao", "A URL para onde o usuário será redirecionado após a autenticação.");
echo $input->html();

?>
