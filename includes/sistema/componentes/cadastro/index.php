<?
include __DIR__."/../../../../admin/config.php";


$sociais = [
    "google"=>["icone"=>"bi-google", "nome"=>"o Google"],
    "facebook"=>["icone"=>"bi-facebook", "nome"=>"o Facebook"],
    "tiktok"=>["icone"=>"bi-tiktok", "nome"=>"o TikTok"],
    "apple"=>["icone"=>"bi-apple", "nome"=>"a Apple"],
    "github"=>["icone"=>"bi-github", "nome"=>"o Github"],
    "amazon"=>["icone"=>"bi-amazon", "nome"=>"a Amazon"],
    "microsoft"=>["icone"=>"bi-windows", "nome"=>"a Microsoft"],
    "linkedin"=>["icone"=>"bi-linkedin", "nome"=>"o LinkedIn"],
    "twiter"=>["icone"=>"bi-twitter-x", "nome"=>"o Twiter (X)"],
    "gov"=>["icone"=>"bi-fingerprint", "nome"=>"o GOV"]
    ];


$html = "";
$contador = 0;
foreach($sociais as $chave=>$valor){
    
    $span = "";
    $wi = "";
    if(v(["login-social","configuracoes", "layoutcompleto"], false)){
        $span = '<span class="text-uppercase">Cadastrar com '.$valor["nome"].'</span>';
    }else{
        $wi = "wi-40";
    }
    
    
    
    if(v(["login-social", $chave ,"ativo"], false)){
        $html .= '<button class="he-40 '.$wi.'  btn btn-light btnSocial fs-12 fw-700 d-flex justify-content-center align-items-center gap-2" data-target="'.$chave.'" title="Cadastrar com '.$valor["nome"].'">
            <i class="bi '.$valor["icone"].'"></i>
            '.$span.'
        </button>';
        $contador ++;
    }
}

$htmlSocial = "";
if($contador > 0){
    $flex = v(["login-social","configuracoes", "layoutcompleto"], false) ? "flex-column" : "";
    $none = "";
    if(v(["login-social","configuracoes", "somentecadastro"], false)){
        $none = "d-none";
    }
    
    $htmlSocial = '
    <div>
        <div class="position-relative mb-3 '.$none.'">
            <div class="position-absolute top-50 start-50 translate-middle w-100 z-1 bg-secondary" style="height: 1px"></div>
            <div class="text-center fs-14 position-relative z-2">
                <span class="login-texto-divisor px-2 fs-12">OU CADASTRE-SE COM</span>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-2 login-sociais '.$flex.'">
            '.$html.'
        </div>
    </div>
    
    ';
}

$inputs = "";
$contador = 0;

if(v(["paginas", "cadastro" ,"cpf"], false)){
    $inputs .= '<div class="form-floating">
                  <input type="email" class="form-control nown-control mascaraInput" data-mascara="1" id="cpf" placeholder="Seu CPF">
                  <label for="cpf">CPF</label>
               </div>';
               $contador++;
    
}

if(true){
    $inputs .= '
    <div class="form-floating">
                  <input type="email" class="form-control nown-control" id="email" placeholder="Seu E-mail">
                  <label for="email">E-mail</label>
               </div>
    ';
}


if(v(["paginas", "cadastro" ,"celular"], false)){
    $inputs .= '<div class="form-floating">
                  <input type="email" class="form-control nown-control mascaraInput" data-mascara="4" id="celular" placeholder="Seu E-mail">
                  <label for="celular">Celular</label>
               </div>';
               $contador++;
}






if(v(["paginas", "cadastro" ,"endereco"], false)){
    $inputs .= '
    <div class="position-relative">
    <div class="form-floating">
                  <input type="email" class="form-control nown-control mascaraInput" data-mascara="3" id="cep" placeholder="Seu CEP">
                  <label for="cep">CEP</label>
               </div>
               
               <span class="position-absolute  top-50 translate-middle-y resultado" style="right: 20px">
         


               </span>
                </div>
               
               ';
               
                   $inputs .= '<div id="resumoEndereco" class="d-none">
                 <div class="form-control">
                    <div class="d-flex justify-content-between align-items-center fw-12 fw-500">
                    <div class="resumo">
          
                    </div>
                    <div>
                        <button class="btn cancelCep"><i class="bi bi-x-lg"></i></button>
                    </div>
                    </div>
                 
                 </div>
                 
               </div>'; 
               
           $inputs .= '<div class="form-floating d-none">
                  <input type="text" class="form-control nown-control"  id="numero" placeholder="Número Endereço">
                  <label for="numero">Número</label>
               </div>'; 
               
                $inputs .= '<div class="form-floating d-none">
                  <input type="email" class="form-control nown-control"  id="complemento" placeholder="Complemento">
                  <label for="complemento">Complemento</label>
               </div>';  
               
               
               $contador++;
}

if(v(["paginas", "cadastro" ,"empresa"], false)){
    $inputs .= '<div class="form-floating">
                  <input type="text" class="form-control nown-control mascaraInput" data-mascara="2" id="cnpj" placeholder="CNPJ da sua Empresa">
                  <label for="cep">CNPJ da sua Empresa</label>
               </div>';
               $contador++;
}


$politicas = [
    "termos-e-condicoes"=>"os termos e condições",
    "politica-de-privacidade"=>"as politicas de privacidade",
    "politica-de-seguranca-da-informacao"=>"a politicas de segurança da informação"
    ,"politica-anti-spam"=>"as politicas de anti-spam",
    "avisos-legais"=>"os avisos legais",
    "politica-de-privacidade-para-criancas"=>"a politicas de privacidade para crianças",
    "politica-de-devolucao-e-reembolso"=>"a politicas de devolução e reembolso",
    "politica-de-cookies"=>"a politicas de cookies"
    ];
$htmlPoliticas = "";
foreach($politicas as $item=>$texto){
    if(v(["politicas" , $item , "ativar"], false) && v(["politicas" , $item , "cadastro"], false) ){
        $htmlPoliticas .= '
        <div class="d-flex justify-content-start gap-2 align-items-center">
        <div class="form-check form-switch"><input class="form-check-input politicas" type="checkbox" role="switch" id="politica-'.$item.'" checked></div>
        <label class="form-check-label fs-14 d-flex justify-content-start gap-1 align-items-center" for="politica-'.$item.'">Aceito <button data-bs-toggle="offcanvas" data-bs-target="#canvaPoliticas" aria-controls="offcanvasExample" data-target="'.$item.'" class="btn text-decoration-none text-primaria btn-sm m-0 p-0 fs-14 showpolitica">'.$texto.'</button></label>
        </div>
        
        ';
    }
}




if(v(["geral", "geral" ,"cadastro"], false)){
?>

<div class="card card-nown" style="max-width: 100%; width:500px;" id="cardCadastro">
   <div class="card-body p-0">
      <div class="row m-0 p-0">
         <div class="col-12 p-xl-5">
            <h2 class="fs-20 fw-700">CRIAR UMA CONTA</h2>
            
            <?
             if(!v(["login-social","configuracoes", "somentecadastro"], false)){
                 ?>
                 
                           <p class="fs-14 mb-4">Insira seus dados para se cadastrar em nosso site.</p>
            <div class="login-form mb-4">
               <div class="d-flex flex-column gap-3">
                   <div class="form-floating">
                  <input type="email" class="form-control nown-control" id="nomeCompleto" placeholder="Seu E-mail">
                  <label for="nomeCompleto">Nome Completo</label>
               </div>
               
               <?=$inputs;?>
               <div class="d-flex flex-column gap-2">
                   <?=$htmlPoliticas?>
               </div>
                  <button class="btn w-100  btn-nown-style btn-n-primaria" disabled id="btnContinuar"> CONTINUAR </button>
               </div>
               
                <p class="fs-12 my-3">Criando uma conta, você passará a receber nossa newsletter em seu e-mail. Para mais informações, consulte nossa Política de privacidade.</p>
            </div>
                 
                 <?
             }
            ?>
  
            <?=$htmlSocial;
            ?>
            <p class="text-center fs-14 mb-0">Já possui uma conta? <button class="btn text-decoration-none text-primaria goPage" data-page="login">Entrar</button></p>
         </div>
      </div>
   </div>
</div>

<?
}
?>