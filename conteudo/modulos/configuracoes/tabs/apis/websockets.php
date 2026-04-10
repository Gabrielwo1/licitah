<?

$switch = new Swith([
    "name"=>"websockets",
    "descricao"=>"Habilite a comunicação de backend com frontend via Websocket",
    "titulo"=>"Websockets",
    ]);
echo $switch->html();


$input = new Input("INPUT");
$input->set("label", "ID do App");
$input->set("name", "app_id");
echo $input->html();

$input = new Input("INPUT");
$input->set("label", "Key");
$input->set("name", "key");
echo $input->html();

$input = new Input("INPUT");
$input->set("label", "Secret");
$input->set("name", "secret");
echo $input->html();

$input = new Input("INPUT");
$input->set("label", "Cluster");
$input->set("name", "cluster");
echo $input->html();




?>