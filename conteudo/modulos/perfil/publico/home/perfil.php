<style>
    #rodape{
        display: none;
    }
</style>

<?

$lista = [
    ["n" => "Geral","i" => "bi bi-gear","c" => "danger","bg" => "danger","t" => "Configurações Gerais"],
];


$perfilInfos = verModulo("geral", "perfil", false);

if(!$perfilInfos){
    array_push($lista, ["n" => "Perfil","i" => "bi bi-person-badge","c" => "primary","bg" => "primary","t" => "Informações de Perfil"]);
}

$redeSocilaInfos = verModulo("geral", "rede-social", false);

if(!$redeSocilaInfos){
    array_push($lista, ["n" => "Redes Sociais","i" => "bi bi-app-indicator","c" => "warning","bg" => "warning","t" => "Redes Sociais"]);
}

array_push($lista,  false);
array_push($lista, ["n" => "Foto de Perfil","i" => "bi bi-camera","c" => "brown","bg" => "brown","t" => "Foto de Perfil"]);
array_push($lista, false);
array_push($lista, ["n" => "Senha","i" => "bi bi-lock","c" => "danger","bg" => "danger","t" => "Gerenciar Senha"]);


$loginSocial = verModulo("seguranca", "login-social", false);
$sessoes = verModulo("seguranca", "gerenciar-sessoes", false);
$dois =  verModulo("seguranca", "autenticacao-de-2-fatores", false);
$bloqueados = verModulo("seguranca", "usuarios-bloqueados", false);

if($loginSocial || $sessoes || $dois || $bloqueados){
    if($loginSocial){
        array_push($lista, ["n" => "Login Social","i" => "bi bi-box-arrow-in-right","c" => "orange","bg" => "orange", "t"=> "Login Social"]);
    }
    if($sessoes){
        array_push($lista, ["n" => "Gerenciar Sessões","i" => "bi bi-door-open-fill","c" => "purple","bg" => "purple","t" => "Sesões Ativas"]);
    }
    
    if($dois){
        array_push($lista, ["n" => "Autenticação de 2 fatores","i" => "bi bi-file-lock2","c" => "pink","bg" => "pink","t" => "Autenticação de 2 fatores"]);
    }
    
    if($bloqueados){
        array_push($lista, ["n" => "Usuários Bloqueados","i" => "bi bi-ban","c" => "brown","bg" => "brown","t" => "Usuários Bloqueados"]);
    }
    
}


if(true){
    array_push($lista, false);
    array_push($lista,["n" => "Perfil Público","i" => "bi bi-bell","c" => "navyblue","bg" => "navyblue","t" => "Perfil Público"]);
        
}


if(verModulo("configuracoes", "notificacoes", false) || verModulo("configuracoes", "privacidade", false)){
    array_push($lista, false);
    if(verModulo("configuracoes", "notificacoes", false)){
        array_push($lista,["n" => "Notificações","i" => "bi bi-bell","c" => "navyblue","bg" => "navyblue","t" => "Notificações"]);
    }
    
    if(verModulo("configuracoes", "privacidade", false)){
        array_push($lista, ["n" => "Privacidade","i" => "bi bi-shield-lock","c" => "info","bg" => "info","t" => "Privacidade"]);
    }
}

$informacoes = verModulo("integracoes-internas", "informacoes", false);
$enderecos = verModulo("integracoes-internas", "enderecos", false);
$empresas =  verModulo("integracoes-internas", "empresas", false);
$meios = verModulo("integracoes-internas", "meios-de-pagamento", false);
if($informacoes || $enderecos || $empresas || $meios){
    array_push($lista, false);
    
    if($informacoes){
        array_push($lista,  ["n" => "Informações","i" => "bi bi-info-square-fill","c" => "success","bg" => "success","t" => "Suas Informações"]);
    }
    
    if($enderecos){
        array_push($lista,  ["n" => "Endereços","i" => "bi bi-geo-alt-fill","c" => "pink","bg" => "pink","t" => "Seus Endereços"]);
    }
    
    if($empresas){
        array_push($lista,  ["n" => "Empresas","i" => "bi bi-building-fill-check","c" => "danger","bg" => "danger","t" => "Suas Empresas"]);
    }
    
    if($meios){
        array_push($lista,  ["n" => "Meios de Pagamento","i" => "bi bi-coin","c" => "indigo","bg" => "indigo","t" => "Seus Meios de Pagamento"]);
    }
    
}


if(verModulo("apagar-conta", "ativo", false)){
    array_push($lista, false);
    array_push($lista, ["n" => "Apagar a Conta","i" => "bi bi-person-x-fill","c" => "darkred","bg" => "darkred","t" => "Apagar Conta"]);
}



?>
<textarea class="d-none" id="listaSetup">
    <?=json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);?>
</textarea>
<div class="container">
   <div class="row">
      <div class="col-12 col-xl-4" id="opcoesPerfil">
         <div class="card card-nown py-2">
            <ul class="list-group list-group-flush rounded-0" id="listaMenu">
                <?
                $i = 0;
                while($i < 10){
                    ?>
                    <btn class="btn list-group-item list-group-item-action d-flex justify-content-start align-items-center gap-3 border-bottom-0 py-2 rounded-0 btnLoading">
                        <div class="he-40 wi-40 rounded-circle d-flex justify-content-center align-items-center bg-carregando"></div>
                        <span><div class="bg-carregando he-15 wi-150"></div></span>
                    </btn>
                    <?
                    $i++;
                }
                ?>
            </ul>
         </div>
      </div>
      <div class="col-12 col-xl-8 p-0">
         <div class="card card-nown">
            <div class="card-body">
               <div class="card border-0" id="cardTopo">
                  <div class="card-body d-flex justify-content-start gap-3 align-items-center">
                     <div class="wi-100 ratio ratio-1x1 rounded-circle img-thumbnail" style="width: 100px">
                         <div class="bg-secondary h-100 rounded-circle" id="imgGlobal">
                             
                         </div>
                     </div>
                     <div>
                        <h4 class="fs-14 fs-xl-18 fw-500"><?=$_SESSION["nome"];?></h4>
                        <h2 class="m-0 fs-20 fs-xl-30 fw-700"></h2>
                        <i  id="currentIcon"></i>
                     </div>
                  </div>
               </div>
               <div class="mt-4 conteudo-perfil" id="render">
                   <div class="d-flex flex-column gap-2">
                  <?
                  $i = 0;
                  while($i < 10){
                      
                      echo '<div class="he-50 bg-carregando"></div>';
                      $i++;
                  }
                  
                  ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<button id="btnCanva">
  
</button>


 
<div class="nown-canvas" id="canvaPerfil">
    <div class="nown-canvas-content">
        <header><h2 class="m-0 fs-18">Configurações</h2> <button class="btn-close-nown-canvas" data-close-canvas=""></button> </header>
        <main>
            <ul class="list-group list-group-flush rounded-0" id="listaMenuMobile">
                
            </ul>
        </main>
    </div>
    <div class="controller"></div>
</div>