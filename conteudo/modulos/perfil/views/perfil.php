<?

function transformString($str) {
    // Transforma a string em minúsculas
    $str = strtolower($str);

    // Substitui caracteres especiais por letras sem acento
    $specialChars = array(
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n'
    );
    $str = str_replace(array_keys($specialChars), array_values($specialChars), $str);

    // Substitui espaços por traços
    $str = str_replace(' ', '-', $str);

    return $str;
}

/*
    ["nome"=>"Segurança e Acesso", "destino"=>"seguranca-e-acesso" , "icone"=>"lock"],
    ["nome"=>"Privacidade", "destino"=>"privacidade" , "icone"=>"admin_panel_settings"],
    ["nome"=>"Meus Endereços", "destino"=>"enderecos" , "icone"=>"travel_explore"],
    */

$menus = [
    ["nome"=>"Configurações", "destino"=>"perfil", "icone"=>"settings"],
    ["nome"=>"Localização", "destino"=>"localizacao" , "icone"=>"add_location"],
    ["nome"=>"Redes Sociais", "destino"=>"redes-sociais" , "icone"=>"group"],
    ["nome"=>"Segurança e Acesso", "destino"=>"seguranca" , "icone"=>"group"],
    ];

$renderMenu = "";
foreach($menus as $item){
    $nome = $item["nome"];
    $bloco = $item["destino"];
    $icone = $item["icone"];

    $renderMenu .= <<<HTML
    <li class="nav-item">
        <button class="nav-link btnPerfil d-flex justify-content-start gap-3 align-items-center w-100 text-contrast fs-14 rounded-0" data-bloco="$bloco">
            <span class="material-symbols-outlined" style="font-size: 14px">$icone</span>
            <span>$nome</span>
        </button>
    </li>
HTML;
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


<div class="row">
    <div class="col-12 col-lg-3 px-0 pe-lg-2" id="lateralPerfil">
          <div class="card h-100">
              <div class="card-body">
            <ul class="nav nav-pills d-flex flex-column">
                <?
                echo $renderMenu;
                ?>
            </ul>
        </div>
          </div>
    </div>
    <div class="col-12 col-lg-9 d-flex flex-column justify-content-start gap-2">
 

<div id="conteudoPerfil"></div>


<div class="card d-none d-lg-block">
    <div class="card-body">
        <div class="d-flex justify-content-between">
    <div></div>
    <div>
        <button class="btn btn-primary d-flex justify-content-center align-items-center gap-2 btnSalvar">
            <span class="material-symbols-outlined">save</span>
            <span>Salvar</span>
            </button>
    </div>
</div>

    </div>
</div>

<style>

#btn-menuPerfil {
  position: relative;
  width: 30px;
  height: 30px;
  border: none;
  cursor: pointer;
}

#btn-menuPerfil .barra {
  position: absolute;
  width: 100%;
  height: 2px;
  background-color: white;
  transition: all 0.3s ease-in-out;
  left: 0px;
}

#btn-menuPerfil .barra:nth-child(1) {
  top: 5px;
}

#btn-menuPerfil .barra:nth-child(2) {
  top: 50%;
  transform: translateY(-50%);
}

#btn-menuPerfil .barra:nth-child(3) {
  bottom: 5px;
}

#btn-menuPerfil.ativo .barra:nth-child(1) {
  transform: rotate(45deg) translate(7px, 7px);
}

#btn-menuPerfil.ativo .barra:nth-child(2) {
  opacity: 0;
}

#btn-menuPerfil.ativo .barra:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -7px);
}
</style>
<div class="d-block d-lg-none">
    <div class="fixed-bottom he-75 bg-contrast w-100" style="z-index: 1000;">
        <div class="container h-100">
            <div class="d-flex justify-content-between align-items-center h-100">
            <div>
                <button id="btn-menuPerfil" class="btn d-flex align-items-center">
                     <span class="barra"></span>
                     <span class="barra"></span>
                     <span class="barra"></span>
                     <span class="text-contrast fs-14 ms-4 text-uppercase fw-700">Configurações</span>
                 </button>
            </div>
            <div><button class="btn btn-primary btnSalvar">Salvar</button></div>
        </div>
        </div>
    </div>
</div>
    </div>
</div>