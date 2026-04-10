<ul class="list-group list-group-flush">
<?

$redes = [
    ["nome"=>"Google", "icone"=>"bi bi-google"],
    ["nome"=>"Facebook", "icone"=>"bi bi-facebook"],
    ["nome"=>"Apple", "icone"=>"bi bi-apple"],
    ["nome"=>"X (Twiter)", "icone"=>"bi bi-twitter"],
    ["nome"=>"Github", "icone"=>"bi bi-github"],
    ["nome"=>"Microsoft", "icone"=>"bi bi-microsoft"],
    ["nome"=>"Amazon", "icone"=>"bi bi-amazon"],
    ["nome"=>"LinkedIn", "icone"=>"bi bi-linkedin"],
    ["nome"=>"TikTok", "icone"=>"bi bi-tiktok"]
];


foreach($redes  as $rede){
    echo '  <li class="list-group-item py-3">
      <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex justify-content-start gap-3 align-items-center">
              <div><i class="fs-30 bi '.$rede["icone"].'"></i></div>
              <div>
                  <h2 class="fs-18 fw-700 m-0">'.$rede["nome"].'</h2>
                  <p class="m-0 fs-14">Acesse sua conta usando o '.$rede["nome"].'</p>
              </div>
          </div>
          <div>
               <div class="form-check form-switch">
               <input class="form-check-input" style="width: 80px; height: 40px" type="checkbox" role="switch" id="flexSwitchCheckDefault" >

</div>
            </div>
      </div>
      
  </li>';
   
}
?>

</ul>