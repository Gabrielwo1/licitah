<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div><h3 class="m-0 fs-18 fw-500 text-uppercase">CONFIGURAÇÕES DE LOCALIZAÇÃO</h3></div>
            <div>
           
            </div>
        </div>
    </div>
    <div class="card-body">
<div class="row">
<?

echo '<div class="col-12">';
$input = new Input("INPUT");
$input ->set("name", "cep");
$input ->set("label", "CEP");
echo $input ->html();
echo '</div>';

echo '<div class="col-6">';
$input  = new Input("INPUT");
$input ->set("name", "estado");
$input ->set("label", "Estado");
echo $input ->html();
echo '</div>';


echo '<div class="col-6">';
$input  = new Input("INPUT");
$input ->set("name", "cidade");
$input ->set("label", "Cidade");
echo $input ->html();
echo '</div>';

echo '<div class="col-8">';
$input = new Input("INPUT");
$input->set("name", "endereco");
$input ->set("label", "Endereço");
echo $input ->html();
echo '</div>';


echo '<div class="col-4">';
$input = new Input("INPUT");
$input ->set("name", "numero");
$input ->set("label", "Número");
echo $input->html();
echo '</div>';


echo '<div class="col-6">';
$input = new Input("INPUT");
$input->set("name", "complemento");
$input ->set("label", "Complemento");
echo $input ->html();
echo '</div>';


echo '<div class="col-6">';
$input = new Input("INPUT");
$input ->set("name", "bairro");
$input ->set("label", "Bairro");
echo $input->html();
echo '</div>';





?>
</div></div></div>