<?
$switch = new Swith([
    "name"=>"light-dark",
    "descricao"=>"Ative ou desative a funcionalidade de light / dark mode",
    "titulo"=>"Light / Dark Mode",
    ]);
echo $switch->html();



$input = new Input("SELECT");
$input->set("name", "startColor");
$input->set("label", "Cor Primaria");
$input->set("descricao", "Essa cor será a inicial do sistema, mas seguirá a última configuração de cada usuário");
$input->set("opcoes",[
["chave"=>"Dark","valor"=>0],
["chave"=>"Light","valor"=>1],
]);
echo $input->html();

?>