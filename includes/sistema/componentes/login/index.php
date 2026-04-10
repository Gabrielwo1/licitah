<?
include __DIR__."/../../../../admin/config.php";


//print_r(SETUP);


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
        $span = '<span class="text-uppercase">Logar com '.$valor["nome"].'</span>';
    }else{
        $wi = "wi-40";
    }
    
    
    
    if(v(["login-social", $chave ,"ativo"], false)){
        $html .= '<button class="he-40 '.$wi.'  btn btn-light btnSocial fs-12 fw-700 d-flex justify-content-center align-items-center gap-2" data-target="'.$chave.'" title="Acessar com '.$valor["nome"].'">
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
    if(v(["login-social","configuracoes", "somentelogin"], false)){
        $none = "d-none";
    }
    
    
    $htmlSocial = '
    <div>
        <div class="position-relative mb-3 '.$none.'">
            <div class="position-absolute top-50 start-50 translate-middle w-100 z-1 bg-secondary" style="height: 1px"></div>
            <div class="text-center fs-14 position-relative z-2">
                <span class="px-2 fs-12 login-texto-divisor">OU ACESSE COM</span>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-2 login-sociais '.$flex.'">
            '.$html.'
        </div>
    </div>
    
    ';
}

$htmlCripto = "";

$wallets = [
    'metamask' => [
        'label' => 'LOGAR COM METAMASK',
        'img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/MetaMask_Fox.svg/768px-MetaMask_Fox.svg.png',
        'class' => 'btn-dark'
    ],
    'phantom' => [
        'label' => 'LOGAR COM PHANTON',
        'img' => 'https://s5-recruiting.cdn.greenhouse.io/external_greenhouse_job_boards/logos/400/073/700/original/1200x1200.png?1712005160',
        'class' => 'btn-info'
    ],
    'binance' => [
        'label' => 'LOGAR COM BINANCE',
        'img' => 'https://wpsmartcontracts.com/assets/binance-wallet.png',
        'class' => 'btn-warning'
    ]
];




if (is_dir(__DIR__."/../../../../conteudo/modulos/web3") && (v(["web-3", "login", "metamask"], false) || v(["web-3", "login", "phantom"], false) || v(["web-3", "login", "binance"], false))) {
    $disposicao =  intval(v(["web-3", "login", "layout"], 1)) == 1 ? "flex-column" : "flex-row";
    $htmlCripto .= '<div>
     <div class="position-relative mb-3 ">
   <div class="position-absolute top-50 start-50 translate-middle w-100 z-1 bg-secondary" style="height: 1px"></div>
   <div class="text-center fs-14 position-relative z-2"> <span class="px-2 fs-12 login-texto-divisor">ACESSE COM SUA WALLET</span> </div>
</div>
    
    <div class="d-flex gap-2 '.$disposicao.' justify-content-center">
    
   
    ';
    foreach ($wallets as $key => $wallet) {
        if (v(["web-3", "login", $key], false)) {
            
            $layout = "";
            $max = "";
            if(intval(v(["web-3", "login", "layout"], 1)) == 1){
                $layout = '<span>'.$wallet['label'].'</span>';
            }else{
                $max = 'style="max-width: 30px"';
            }
            
            
            $htmlCripto .= '<button class="btn w-100 btn-nown-style '.$wallet['class'].' fw-700 loginWallet d-flex align-items-center justify-content-center gap-2" data-wallet="'.$key.'" type="button" '.$max.' title="'.$wallet['label'].'">
            <img src="'.$wallet['img'].'" style="width: 20px"> '.$layout.'
                </button>';
         
        }
    }
    $htmlCripto .= '</div></div>';
}




 $linkCadastro = "";
if(v(["geral","geral","cadastro"], false)){
    $linkCadastro = '
        <p class="text-center fs-14 mb-0">
            <span>Primeira vez por aqui? </span>
            <button  class="btn text-decoration-none text-primaria goPage" data-page="cadastro">Criar uma conta</button>
        </p>
';
}

$stringLogin = ["E-MAIL"];
if(v(["paginas","login","usuario"] , false)){
    array_push($stringLogin, "USUÁRIO");
}

if(v(["paginas","login","cpf"] , false)){
    array_push($stringLogin, "CPF");
}

if(v(["paginas","login","telefone"] , false)){
    array_push($stringLogin, "TELEFONE");
}

if(v(["paginas","login","cnpj"] , false)){
    array_push($stringLogin, "CNPJ");
}

$stringLogin = implode(" , ", $stringLogin);
$stringLogin = preg_replace('/,([^,]*)$/', ' ou\1', $stringLogin);



$esquerda = "";
$direita = "";
$size = "12";
if(v(["paginas","login","box"] , false)){
    
    $tituloHTML = "";
if(v(["paginas","login","tituloauxiliar"], false)){
    $tituloHTML = '<h3 class="fs-20 fw-700 m-0" style="color: var(--nown-primaria-text-over)">'.v(["paginas","login","tituloauxiliar"], "").'</h3>';
}

$textoHTML = "";
if(v(["paginas","login","textoauxiliar"], false)){
    $textoHTML = '<p class="fs-14 m-0">'.v(["paginas","login","textoauxiliar"], "").'</p>';
}
    
    
    
    $imgBackBox = false;
    $box = "";
    $auxiliar = "";
    switch(intval(v(["paginas","login","auxiliar"] , 0))){
        case 1:
            $auxiliar = '
            <div>
             <p class="fs-14 mb-0">Abra nosso app e escaneie o QRCode:</p>
            <div class="ratio ratio-1x1 qr-code-login m-auto mt-2 bg-white" id="qrCodeApp"> 
            <div class="d-flex justify-content-center h-100 align-items-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>
            </div>
            </div>';
            
            break;
        case 2:
            if(v(["paginas","login","imgaux"], false)){
                $img = json_decode(v(["paginas","login","imgaux"], false))[0];
               $auxiliar = '<div>
               <img data-src="'.$img .'" id="imgAuxiliar" class="w-100">
               </div>';
            }
            
            if(v(["paginas","login","imgauxfull"], false)){
                $imgBackBox = 'style="background-image: url('.SETUP["dominio"]."conteudo/uploads/".$img.');"';
                $box = '
     <div class="d-none d-xl-block col-xl-5 px-0 background" '.$imgBackBox.'>
            <div class="wi-250"></div>
         </div>
    ';
    $size = 7; 
            }
            
            break;

    }
    


    
    if(!$imgBackBox){
          $box = '
     <div class="d-none d-xl-block col-xl-4 px-0 background" '.$imgBackBox.'>
            <div class="holder-qr-code-login text-light text-center d-flex justify-content-center gap-3 flex-column py-5">
              '.$tituloHTML.'
               '.$auxiliar.'
               '.$textoHTML.'
            </div>
         </div>
    ';
    $size = 8; 
    }
 
    
    
    if(v(["paginas","login","posicaobox"] , "esquerda") == "esquerda"){
        $esquerda = $box;
    }else{
        $direita = $box;
        
    }

}

switch(intval(v(["paginas","login","layout"] , 1) )){
    case 1:
        break;
    case 2:
        break;
    case 3:
        break;
}


?>

<div class="card card-nown overflow-hidden w-100 m-auto"  style="max-width: 700px; width: 100%;" id="cardLogin">
   <div class="card-body p-0">
      <div class="row m-0 p-0">
         <?=$esquerda;?>
         <div class="col-12 col-xl-<?=$size?> p-xl-5 d-flex flex-column justify-content-center gap-4">
            <h2 class="fs-20 fw-700 m-0">ENTRAR</h2>
            <p class="fs-14 m-0"><?=v(["paginas", "login", "mensagemboas"], "Estamos felizes em ter você aqui novamente.");?></p>
            
            <?
             if(!v(["login-social","configuracoes", "somentelogin"], false)){
                ?>
                 <div class="login-form">
               <div class="form-floating mb-4">
                  <input type="email" class="form-control nown-control" id="dadosAcesso" placeholder="<?=$stringLogin?>">
                  <label for="dadosAcesso" class="fs-14"><?=$stringLogin?></label>
               </div>
               <button class="btn w-100 btn-nown-style btn-n-primaria" disabled id="btnContinuar" type="button"> CONTINUAR </button>
            </div>
                
                <?
            }
            
  
            echo $htmlSocial;
            echo $htmlCripto;
            echo $linkCadastro;
            ?>
         </div>
         <?=$direita;?>
       
      </div>
   </div>
</div>