<?

$switch = new Swith([
    "name"=>"android",
    "descricao"=>"Habilite o donwload do aplicativo para celulares Android",
    "titulo"=>"App Android",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"apple",
    "descricao"=>"Habilite o donwload do aplicativo para celulares Apple",
    "titulo"=>"App Apple",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"pc",
    "descricao"=>"Habilite o donwload do aplicativo para Computadores",
    "titulo"=>"App Computador",
    ]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "nome");
$input->set("label", "Nome do Aplicativo");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "nomeCurto");
$input->set("label", "Nome Curto do Aplicativo");
echo $input->html();

$input = new Input("TEXTAREA");
$input->set("name", "descricao");
$input->set("label", "Descrição do Aplicativo");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "startUrl");
$input->set("label", "Url de Inicio");
echo $input->html();


$input = new Input("SELECT");
$input->set("name", "display");
$input->set("label", "Estilo de Display");
$input->set("opcoes", [
    ["chave" => "Fullscreen", "valor" => "fullscreen"],
    ["chave" => "Minimal UI", "valor" => "minimal-ui"],
    ["chave" => "Browser", "valor" => "browser"],
    ["chave" => "Standalone", "valor" => "standalone", "selecionado"=>true],
    ["chave" => "Popup", "valor" => "popup"],
    ["chave" => "Windowed", "valor" => "windowed"],
    ["chave" => "Overlay", "valor" => "overlay"],
    ["chave" => "Compact Overlay", "valor" => "compact-overlay"],
]);

echo $input->html();



$input = new Input("INPUT");
$input->set("name", "background");
$input->set("label", "Cor do Background");
$input->set("type", "color");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "tema");
$input->set("label", "Cor do tema");
$input->set("type", "color");
echo $input->html();


?>