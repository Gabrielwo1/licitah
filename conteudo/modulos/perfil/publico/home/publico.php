<?

$menus = [
    ["nome"=>"Configurações Gerais", "chave"=>"geral", "icone"=>"bi bi-gear"],
    ["nome"=>"Segurança e Acesso", "chave"=>"seguranca", "icone"=>"encrypted"],
    ["nome"=>"Meus Endereços", "chave"=>"enderecos", "icone"=>"location_on"]
    ];


if(isset($isPerfil) && $isPerfil){
    $menusAdmin = [
    ["divisor"=>true],    
    ["nome"=>"Histórico", "destino"=>"s/historico", "icone"=>"settings"],
    ["nome"=>"Funções e Permissões", "destino"=>"s/historico", "icone"=>"settings"],
    ];
    $menus = array_merge($menus, $menusAdmin);
}


?>

<style>
   /* celular */
@media (max-width: 991.98px) {
  #lateralPerfil{
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0px;
      left: -100%;
      z-index: 999;
      transition: all 0.3s ease-in-out;all
  }
  

    
    #lateralPerfil.ativo{
        left: 0%;
    }
    
}



/* pc */
@media (min-width: 992px) {
#lateralPerfil{
      position: relative;
  }
}

</style>

<div class="container">
    <div class="card card-nown mb-3 border-0">
    <div class="card-body">
        <ul class="nav">
            <?
            foreach($menus as $menu){
                echo '
                  <li class="nav-item">
                    <button class="nav-link btnPerfil d-flex justify-content-start gap-2 align-items-center w-100 text-secondary fs-13 rounded-0" data-bloco="'.$menu["chave"].'">
                        <i class="'.$menu["icone"].'" style="font-size: 14px"></i>
                        <span class="text-uppercase fw-600">'.$menu["nome"].'</span>
                    </button>
                </li>
                ';
            }
            ?>
  

    <li class="nav-item dropdown">
    <button class="nav-link btnPerfil d-flex justify-content-start gap-2 align-items-center w-100 text-secondary fs-13 rounded-0 dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
         <span class="material-symbols-outlined" style="font-size: 14px">menu</span>
        <span  class="text-uppercase fw-600"> Mais Opções</span>
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#">Action</a></li>
      <li><a class="dropdown-item" href="#">Another action</a></li>
      <li><a class="dropdown-item" href="#">Something else here</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="#">Separated link</a></li>
    </ul>
  </li>
</ul>
    </div>
</div>
<div class="row">
    <div class="col-4">
        <div class="card card-nown position-relative">
            
            <div class="ratio ratio-16x9 bg-warning position-relative">
                
            </div>
             <button class="position-absolute top-0 end-0 m-2"><span class="material-symbols-outlined">photo</span></button>
            <div class="position-relative">
                <div class="wi-120 he-120 position-absolute top-100 start-50 translate-middle ">
                    <div class="ratio ratio-1x1 bg-danger rounded-circle img-thumbnail">
                        <button class="btn position-absolute top-0 end-0"><span class="material-symbols-outlined">photo</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center gap-2 mt-5">
                    <h2 class="fs-18 m-0 fw-500">Kristen Oswalt</h2>
                    <span class="btn btn-info btn-sm fs-12">OFFLINE</span>
                </div>
                <div  class="d-flex justify-content-center align-items-center gap-2 my-3">
                    <button class="btn he-50 wi-50 bg-info rounded-circle"></button>
                    <button class="btn he-50 wi-50 bg-warning rounded-circle"></button>
                    <button class="btn he-50 wi-50 bg-danger rounded-circle"></button>
                    <button class="btn he-50 wi-50 bg-dark rounded-circle"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div id="conteudoPerfil"></div>
    </div>
</div>
</div>