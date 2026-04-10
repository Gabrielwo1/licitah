<?


echo '<div class="row"><div class="col-12 col-lg-4">';
$upload = new Upload(
    [
    "accept"=>"image/*", 
    "multiple"=>false,
    "pasta"=>"configuracoes",
    "id"=>"destaque",
    "layout"=>2
    ]
    );
echo $upload->html();

echo '</div>';

echo '<div class="col-12 col-lg-8">';

$input = new Input("INPUT");
$input->set("label", "Nome do Site");
$input->set("name", "nome");
$input->set("descricao", "Defina qaul será o nome do sistema");
echo $input->html();



$input = new Input("INPUT");
$input->set("label", "Tagline");
$input->set("name", "tagline");
$input->set("descricao", "Descrição do sistema. Essa campo irá aparecer para o Google (SEO)");
echo $input->html();



$input = new Input("INPUT");
$input->set("label", "Texto Logo");
$input->set("name", "texto-logo");
$input->set("descricao", "Defina o texto alternativo para a Logo");
echo $input->html();


echo '</div></div>';








?>


