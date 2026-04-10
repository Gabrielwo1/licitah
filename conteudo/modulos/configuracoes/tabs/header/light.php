<?


echo '<div class="row">';

echo '<div class="col-12">';
echo titularizador("Estilo");
echo '</div>';

echo '<div class="col-12 col-lg-3">';
$input = new Input("INPUT");
$input->set("label", "Cor do Fundo");
$input->set("name", "headerBackground");
$input->set("type", "color");
echo $input->html();
echo '</div>';


echo '<div class="col-12 col-lg-3">';
$input = new Input("INPUT");
$input->set("label", "Cor das palavras");
$input->set("name", "headerColor");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-12 col-lg-3">';
$input = new Input("INPUT");
$input->set("label", "Hover das palavras");
$input->set("name", "headerColorHover");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-12 col-lg-3">';
$input = new Input("INPUT");
$input->set("label", "Background Hover");
$input->set("name", "headerBackgroundHover");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-12 my-4">';
echo titularizador("Logo");
echo '</div>';

echo '<div class="col-12 col-lg-4">';
$upload = new Upload(
    [
    "accept"=>"image/*", 
    "multiple"=>false,
    "pasta"=>"configuracoes",
    "id"=>"logo",
    "layout"=>2
    ]
    );
echo $upload->html();
echo '</div>';


echo '<div class="col-12 my-4">';
echo titularizador("Imagem de Background");
echo '</div>';

echo '<div class="col-12 col-lg-4">';
$upload = new Upload(
    [
    "accept"=>"image/*", 
    "multiple"=>false,
    "pasta"=>"configuracoes",
    "id"=>"background",
    "layout"=>2
    ]
    );
echo $upload->html();
echo '</div>';

echo '</div>';

?>