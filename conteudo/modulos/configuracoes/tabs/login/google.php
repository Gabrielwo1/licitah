<?


$switch = new Swith([
    "name"=>"ativo",
    "descricao"=>"Habilite o login e cadastro social usando o Google",
    "titulo"=>"Logar com o Google",
    ]);
echo $switch->html();

$input = new Input("INPUT");
$input->set("name", "id");
$input->set("label", "Client ID");
$input->set("descricao", " Obtido ao registrar seu aplicativo no Google Developers Console.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "segredo");
$input->set("label", "Client Secret");
$input->set("descricao", " Gerado junto com o Client ID.");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "redirecionamento");
$input->set("label", "Redirect URI");
$input->set("descricao", "A URL para onde o usuário será redirecionado após a autenticação.");
echo $input->html();


?>