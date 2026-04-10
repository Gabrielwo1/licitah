<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div><h3 class="m-0 fs-18 fw-500 text-uppercase">CONFIGURAÇÕES GERAIS</h3></div>
            <div>
             
            </div>
        </div>
    </div>
    <div class="card-body">
<div class="row">

<?
echo '<div class="col-12 col-lg-12">';

$input = new Input("INPUT");
$input->set("name", "facebook");
$input->set("label", "Facebook");
echo $input->html();

$input = new Input("INPUT");
$input->set("name", "instagram");
$input->set("label", "Instagram");
echo $input->html();


$input = new Input("INPUT");
$input->set("name", "twiter");
$input->set("label", "Twiter");
echo $input->html();











?>
</div></div></div>
