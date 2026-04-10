<div class="row">
<?
$redes = [
    ["nome"=>"Instagram", "icone"=>"bi bi-instagram"],
    ["nome"=>"Facebook", "icone"=>"bi bi-facebook"],
    ["nome"=>"X (Twiter)", "icone"=>"bi bi-twitter-x"],
    ["nome"=>"Github", "icone"=>"bi bi-github"],
    ["nome"=>"LinkedIn", "icone"=>"bi bi-linkedin"],
    ["nome"=>"TikTok", "icone"=>"bi bi-tiktok"]
    ];

foreach($redes as $rede){
    echo ' <div class="col-12 col-xl-6 mt-4">
        <label class="fw-700 d-flex justify-content-start gap-2 align-items-center mb-1"><i class="'.$rede["icone"].'"></i> '.$rede["nome"].'</label>
        <input class="form-control form-control-lg">
    </div>';
}
?>
</div>
<div class="d-flex justify-content-end mt-4">
    <button class="btn btn-nown-style btn-n-primaria" id="btnSalvar">Salvar</button>
</div>