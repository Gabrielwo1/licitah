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

echo '<div class="col-12 col-lg-4">';

                    $upload = new Upload(
                        [
    "accept"=>"image/*", 
    "multiple"=>false,
    "pasta"=>"perfil",
    "id"=>"foto",
    "layout"=>2
    ]
         );
echo $upload->html();
         
                    

echo '</div>';

echo '<div class="col-12 col-lg-8">';

$nome = new Input("INPUT");
$nome->set("name", "nome");
$nome->set("label", "Primeiro Nome");
echo $nome->html();

$sobrenome = new Input("INPUT");
$sobrenome->set("name", "sobrenome");
$sobrenome->set("label", "Sobrenome");
echo $sobrenome->html();



$input = new Input("TEXTAREA");
$input->set("name", "sobre");
$input->set("label", "Sobre Mim");
echo $input->html();
echo '</div>';






?>
</div></div></div>
