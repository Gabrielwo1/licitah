<?php

$switch = new Swith([
    "name" => "ativo",
    "descricao" => "Habilite o login e cadastro social usando o Facebook",
    "titulo" => "Logar com o Facebook",
]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "id");
$input->set("label", "App ID");
$input->set("descricao", "Criado ao registrar seu aplicativo no Facebook Developers.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "segredo");
$input->set("label", "App Secret");
$input->set("descricao", "Gerado junto com o App ID.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "redirecionamento");
$input->set("label", "Redirect URI");
$input->set("descricao", "A URL para onde o usuário será redirecionado após a autenticação.");
echo $input->html();

?>
