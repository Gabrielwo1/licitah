<?

$switch = new Swith([
    "name"=>"manutencao",
    "descricao"=>"Deslogue todos usuários e apresente uma mensagem de manutenção",
    "titulo"=>"Modo Manutenção",
    ]);
echo $switch->html();


$input = new Input("INPUT");
$input->set("name", "cabecalho");
$input->set("label", "Cabeçalho da Página");
$input->set("descricao", "Mensagem de Manutenção em destaque na página");
echo $input->html();


$input = new Input("TEXTAREA");
$input->set("name", "descricao");
$input->set("label", "Descrição");
$input->set("descricao", "Deixe uma mensagem explicando a situação para o usuário");
echo $input->html();



$switch = new Swith([
    "name"=>"cronometro",
    "descricao"=>"Ative um timer na página de manutenção.",
    "titulo"=>"Cronometro",
    ]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "data");
$input->set("label", "Data do cronometro");
$input->set("type", "date");
$input->set("descricao", "Defina a data final do cronometro");
echo $input->html();


$input = new Input("INPUT");
$input->set("name", "horario");
$input->set("label", "Horário do cronometro");
$input->set("type", "time");
$input->set("descricao", "Defina o horário final do cronometro");
echo $input->html();


?>