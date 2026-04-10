<?
$switch = new Swith([
    "name"=>"preCarregamento",
    "descricao"=>"Ativar Pré Carregamento",
    "titulo"=>"Pré Carregamento",
    ]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("label", "Loading Texto");
$input->set("name", "texto");
$input->set("descricao", "Texto de Loading");
echo $input->html();

$input = new Input("INPUT");
$input->set("label", "Loading Texto");
$input->set("type", "color");
$input->set("name", "corBackground");
$input->set("descricao", "Cor do Background");
echo $input->html();
?>