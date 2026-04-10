<?

$btns = [
["nome"=>"Geral", "tipo"=>0],
["nome"=>"Perfil", "tipo"=>1],
["nome"=>"Redes Sociais", "tipo"=>1],
["nome"=>"Configurações de notificação", "tipo"=>1],
["nome"=>"Avatar & Capa", "tipo"=>1],
["nome"=>"Privacidade", "tipo"=>1],
["nome"=>"Senha", "tipo"=>1],
["nome"=>"Gerenciar Sessões", "tipo"=>1],
["nome"=>"Autenticação de dois fatores", "tipo"=>1],
["nome"=>"Usuários Bloqueados", "tipo"=>1],
["nome"=>"Minha informação", "tipo"=>1],
["nome"=>"Meus endereços", "tipo"=>1],
["nome"=>"meus ganhos", "tipo"=>1],
["nome"=>"meus Afiliados", "tipo"=>1],
["nome"=>"Minha carteira", "tipo"=>1],
["nome"=>"Deletar conta", "tipo"=>1],
];
    
    
$btnshtml = "";

foreach($btns as $btn){
    if($btn["tipo"] == 1){
         $btnshtml .= '<button class="btn btn-contrast btn-sm mb-2 d-flex btnPerfil">
            <span><i class="bi bi-alarm-fill fs-16"></i></span>
            <span class="ms-2">'.$btn["nome"].'</span>
        </button>';
    }else{
        $btnshtml .= '
        <div class="position-relative he-25 mb-2">
            <div class="position-absolute start-50 top-50 translate-middle bg-dark px-2 fs-14 btnPerfil" style="z-index: 9">'.$btn["nome"].'</div>
            <div class="position-absolute top-50 start-50 translate-middle w-100">
            <div style="height: 2px;" class="bg-danger"></div>
        </div>
        </div>';
    }
   
}

?>


<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body d-flex flex-column">
               <?
               echo $btnshtml;
               ?>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body">
              
            </div>
        </div>
    </div>
</div>