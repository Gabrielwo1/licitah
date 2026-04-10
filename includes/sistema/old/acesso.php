<style>

.btn-ativado{
    background-color: red;
    color: white;
}
.btn-desativado{
    
}

#pesoSenha{
        
    }
    
#pesoSenha div{
        width: 100%;
        margin: 5px;
        height: 5px;
        background-color: gray;
    }
    
    
.botao {
  /* Reset de estilos */
  border: none;
  outline: none;
  background: none;
  padding: 0;
  margin: 0;
  cursor: pointer;
  text-decoration: none;
  color: inherit;
  
  /* Estilos específicos para remover aparência de clique */
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  
  /* Estilos específicos para remover aparência de ativo */
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
}

/* Estilos adicionais para hover (opcional) */
.botao:hover {
  /* Adicione estilos de hover aqui */
}

  .form-login.is-invalid {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 4px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid red;
  border-radius: 0.25rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

 .form-login.is-invalid + label{
  color: red !Important;
}

.form-login + label + .feedback{
    display:none;
}
.form-login.is-invalid + label + .feedback{
    display:block !important;
    color: red !Important;
}

    .form-login {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 4px);
  padding: 0.375rem 0.75rem;
  font-size: 12px;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-login:focus {
  color: #495057;
  background-color: #fff;
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-login::placeholder {
  color: #6c757d;
  opacity: 1;
}

.form-login:disabled {
  background-color: #e9ecef;
  opacity: 1;
}


  .form-group {
    position: relative;
    margin-bottom: 20px;
  }

  

  .form-login:focus + label,
 .form-login:not(:placeholder-shown) + label {
    top: -10px;
    left: 10px;
    font-size: 12px;
    background-color: white;
    padding: 0 5px;
    color: #666;
  }

 label {
    position: absolute;
    top: 10px;
    left: 20px;
    font-size: 12px;
    pointer-events: none;
    transition: 0.2s;
  }


.btnSocial{
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
}



</style>
<?php

class BoxAuxiliar{
    public $configs;
    public $logo;
    public $titulo;
    public $texto;
    public $backgroundColor;
    public $fontColor;
    public $right;
    
    function __construct(){
       
    }
    
    function html($setup){
         if(true){
    
        
    
        
        $img = ""; 
    
        if(isset($setup["auxiliar"])){
            switch($setup["auxiliar"]){
                 case '1':
                    $img = '
                    <div class="d-flex justify-content-center align-items-center">
                        <div style="width: 180px; height: 180px" class="bg-white">
                            <div id="qrcode" class="p-3 bg-white"></div>
                        </div>
                    </div>
                    ';
                    break;
                case '2':
                    if(isset($setup["imgauxiliar"])){
                        $imagem = json_decode($setup["imgauxiliar"], true);
                        $imagem = SETUP["dominio"]."conteudo/uploads/".$imagem[0];
                         $img = '<div><img src="'.$imagem.'" class="w-100"></div>';
                    }else{
                        $img = "";
                    }

                    break;
               
                default:
                    $img = "";
                    break;
            }
            
        }
        
        if(isset($setup["corbox"]) && $setup["corbox"]){
            $bg = $setup["corbox"];
            $color = $setup["cortexto"];
        }else{
            $bg = "#000";
            $color = "#fff";
        }

        
        
        $titulo = "";
        if(isset($setup["tituloauxiliar"])){
            $titulo = '
             <div>
                <h2 class="fs-18 fw-500 text-center m-0" style="color:'.$color.'">'.$setup["tituloauxiliar"].'</h2>
            </div>
            ';
        }
        
        $texto = "";
        if(isset($setup["textoauxiliar"])){
            $texto = '
             <div>
                <p class="text-center fs-14" style="color:'.$color.' ; line-height:16px">
                    '.$setup["textoauxiliar"].'
                </p>
            </div>
            ';
        }
        
        
   
        
        return '
        <div class="d-none d-lg-block col-4 py-3" style="background-color: '.$bg.'">
            <div class="container d-flex flex-column justify-content-center gap-3 h-100">
                '.$titulo.$img.$texto.'
            </div>
        </div>';
        
    }
    return "";
        
    }
}

class Footer{
    public $menu;
    public $copy;
    
    function __construct(){
        
    }
    
    
    function html(){
        $menuRodape = [];
        
        $menus = "";
        
        foreach($menuRodape as $item){
   
        $menus .= '
        <li class="nav-item">
            <a class="nav-link text-dark" href="'.SETUP["dominio"].$item["link"].'" data-noprevent="true">'.$item["nome"].'</a>
        </li>';
        }
   
        return '<div>
        <div>
            <ul class="nav justify-content-center fs-12">'.$menus.'</ul>
        </div>
        <div class="text-center pb-2">
            <p class="fs-10 m-0">Copyright © 2023 '.$this->copy.'</p>
        </div>
    </div>';
    }
    
    
}

class Pagina{
    
    public $rodape;
    public $cadastro = "";
    public $social = "";
    public $box = "";
    public $size;
    public $pagina;
    public $setupLogin;
    public $setupCadastro;
    public $setup;
    
    function __construct($pagina, $caminho){
        $this->pagina = $pagina;
        $this->caminho = $caminho;
        $this->setupLogin = v(["paginas", "login"], []);
        $this->setupCadastro = v(["paginas", "cadastro"], []);
    
         switch($this->pagina){
            case 'acesso':
                $this->setup = $this->setupLogin;

                break;
            case 'cadastro':
                $this->setup = $this->setupCadastro; 
                break;
            case 'default':
                $this->setup = false; 
                break;
            
        }



        
       
        $this->cadastro = v(["geral", "geral", "cadastro"], "false") != "false" ? '<div class="text-dark mt-3 fs-14 fw-300 d-flex jutify-content-start align-items-center gap-2 "><span>Primeira vez por aqui?</span> <a href="/cadastro" data-noprevent="true" class="text-decoration-none">Criar uma conta</a></div>' : "";
      
        
        
        if(isset(SETUP["config"]["login"])){
            
              if(v(["login", "google", "ativo"], false)){
                  if($pagina == "acesso"){
                        $this->social .= '
                        <div id="g_id_onload" data-client_id="931092977486-5c0075so2m9iakfa87b84kt4ohjho0rg.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="loginGoogle" data-auto_prompt="false"></div>
                        <div class="g_id_signin" data-type="icon" data-shape="square" data-theme="outline" data-text="signin_with" data-size="large"></div>';
                    }else{
                         $this->social .= '
                         <div id="g_id_onload" data-client_id="931092977486-5c0075so2m9iakfa87b84kt4ohjho0rg.apps.googleusercontent.com" data-context="signup" data-ux_mode="popup" data-callback="cadastroGoogle" data-nonce="" data-itp_support="true"></div>
                         <div class="g_id_signin" data-type="icon" data-shape="square" data-theme="outline" data-text="signin_with" data-size="large"></div>';
                    
                    }
            }
            
              if(v(["login", "facebook", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-facebook"></i></button>';
            }
            
              if(v(["login", "apple", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-apple"></i></button>';
            }
            
            
             if(v(["login", "linkedin", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-linkedin"></i></button>';
            }
            
             if(v(["login", "amazon", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-amazon"></i></button>';
            }
            
            
             if(v(["login", "microsoft", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-microsoft"></i></button>';
            }
            
             if(v(["login", "github", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-github"></i></button>';
            }
            

            if(v(["login", "twiter", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-twitter-x"></i></button>';
            }
            
            
             if(v(["login", "tiktok", "ativo"], false)){
                   $this->social .= '<button type="button" class="btn btn-light btn-lg btnSocial btn-sm mx-1" title="Acesse com sua conta"><i class="bi bi-tiktok"></i></button>';
            }
            
        
            
            
            
          
            
            
 
    
    if(!empty($this->social)){
        $txt = $pagina == "acesso" ? "ACESSE" : "CADASTRE-SE";
        $this->social = '
        <div class="mt-4 mb-3 d-flex justify-content-between align-items-center gap-2">
                <div class="border-bottom flex-fill"></div>
                <div class="px-3 fs-12 text-dark flex-fill text-center">OU '.$txt.' COM</div>
                <div class="border-bottom flex-fill"></div>
            </div>
            <div class="d-flex justify-content-center">
                '.$this->social.'
            </div>';
    }
    
}

        $box = new BoxAuxiliar();
        $this->box = $box->html($this->setup);
        $this->size = $this->box ? "col-lg-8" : "col-lg-12";
        
    }
    
    function login(){
                
        $array = ["E-MAIL"];
        if(v(["paginas", "login", "cpf"], "false") == "true"){
            array_push($array, "CPF");
        }
        
        if(v(["paginas", "login", "telefone"], "false") == "true"){
            array_push($array, "TELEFONE");
        }
        
        if(v(["paginas", "login", "usuario"], "false") == "true"){
            array_push($array, "NOME DE USUÁRIO"); 
        }
        //USUÁRIO, E-MAIL , TELEFONE OU CPF
        $string = implode(" , ", $array);
      
        if(!isset($this->caminho[1])){
              return '<h2 class="fs-20 text-dark text-uppercase fw-700">Entrar</h2>
                                 <p class="text-dark fs-14">Estamos felizes em ter você aqui novamente.</p>
                                 <div class="form-group">
                                    <input class="form-login" id="usuario" placeholder="">
                                    <label for="usuario">'.$string.'</label>
                                 </div>
                                 
                                 <div>
                                    <button id="btnLogin" class="btn  w-100 btn-desativado btn-sm fs-12" data-pagina="login1">CONTINUAR</button>
                                 </div>
                                 '.$this->social.$this->cadastro.'
                                 ';
        }else{
            
            
             $capcha = ver(["seguranca", "recapcha", "recapcha"]);
             $front = v(["seguranca", "recapcha", "chave_site"], false);
             if($capcha && $front){
                
                $btn = '
                <button id="btnLogin" data-pagina="login2" class="g-recaptcha btn w-100 btn-desativado btn-sm fs-12" data-sitekey="'.$front.'"  data-callback="onSubmit" data-action="click" type="button">ENTRAR</button>';
             }else{
                 $btn = '<button id="btnLogin" class="btn w-100 btn-desativado btn-sm fs-12" data-pagina="login2" type="button">ENTRAR</button>';
             }
            
            
              return '<h2 class="fs-20 text-dark text-uppercase fw-700">Digie sua senha</h2>
                                 <p class="text-dark fs-14">Você está a um clique de usufruir do melhor.</p>
                                 <div class="card mb-4 bg-light">
                                    <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-12 fw-700 text-dark text-uppercase">'.PAGINA[1].'</div>
                                            <span style="width: 30px; height: 30px" class="bg-danger"></span>
                                            <span class="text-dark fs-16" id="loginData">'.PAGINA[2].'</span>
                                        </div>
                                        <div><a href="/acesso" data-noprevent="true"><button class="btn btn-light d-flex justify-content-center align-items-center"><i class="bi bi-x"></i></button></a></div>
                                    </div>
                                    </div>
                                 </div>
                                 <div class="form-group my-3">
                                    <input class="form-login" id="senha" placeholder="" type="password">
                                    <label for="senha">SENHA</label>
                                    <button class="pe-3 botao fs-12 position-absolute top-50 end-0 translate-middle-y text-dark"><i class="bi bi-eye"></i></button>
                                 </div>
                                 <div class="mb-4">
                                 <div class="form-check form-switch">
                                 <input class="form-check-input" type="checkbox" role="switch" id="lembrarme" checked>
                                 <span class="fs-14 text-dark">Me mantenha conectado</span>
                                 </div>
                                 </div>
                                 <div>
                                    '.$btn.'
                                 </div>
                                 <div class="mt-3">
                                 <a href="/senha-perdida" data-noprevent="true" class="text-decoration-none fw-500 fs-14">Esqueceu sua senha?</a>
                                 </div>
                                 
                                 ';
        }
      
    }
    
    function cadastro(){
       
        if(isset($this->caminho[1]) && isset($this->caminho[2]) && isset($this->caminho[3])){
            $array = [$this->caminho[1], $this->caminho[2], $this->caminho[3]];
            
            $capcha = ver(["seguranca", "recapcha", "recapcha"]);
             $front = v(["seguranca", "recapcha", "chave_site"], false);
             if($capcha && $front){
                
                $btn = '
                <button id="btnLogin" class="g-recaptcha btn w-100 btn-desativado btn-sm fs-12" data-pagina="cadastro2" data-sitekey="'.$front.'"  data-callback="onSubmit" data-action="click" type="button">CRIAR CONTA</button>';
             }else{
                 $btn = '<button id="btnLogin" class="btn  w-100 btn-desativado btn-sm fs-12" data-pagina="cadastro2">CRIAR CONTA</button>';
             }
            
            
            return '<div id="dataPre" class="d-none">'.json_encode($array).'</div><h2 class="fs-20 text-dark text-uppercase fw-700">Crie sua senha</h2>
                                 <p class="text-dark fs-14">Crie uma senha segura para sua conta.</p>
                                
                                     <div class="form-group mt-3">
                                    <input class="form-login" id="senha" placeholder="" type="password">
                                    <label for="senha"  >SENHA</label>
                                    <button class="pe-3 botao fs-12 position-absolute top-50 end-0 translate-middle-y text-dark"><i class="bi bi-eye"></i></button>
                                 </div>
                                 
                                 <div class="d-flex justify-content-between align-items-center mt-3 mb-1" id="pesoSenha">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                 </div>
                                 <div id="mensagemPeso"></div>
                                 <div class="my-4">
                                 <div class="form-check form-switch">
                                 <input class="form-check-input" type="checkbox" role="switch" id="politica" checked>
                                 <span class="fs-14 text-dark">Aceito as <a href="#" class="text-decoration-none" data-noprevent="true">politicas de privacidade</a></span>
                                 </div>
                                 </div>
                                 <div>
                                    '.$btn.'
                                 </div>
                                 
                                 <div>
                                 <p class="my-3 fs-12 text-dark">Criando uma conta, você passará a receber nossa newsletter em seu e-mail. Para mais informações, consulte nossa Política de privacidade.</p>
                                 </div>
                            
                                  <div class="text-dark mt-3 fs-14 fw-300">
            <span>Já tem uma conta?</span> 
            <a href="/acesso" class="text-decoration-none" data-noprevent="true">Entrar</a>
        </div>';
            
        }else{
              return '<h2 class="fs-20 text-dark text-uppercase fw-700">Criar uma conta</h2>
                                 <p class="text-dark fs-14">Boas-vindas! Insira seus dados.</p>
                                 <div class="form-group">
                                    <input class="form-login" id="nome" placeholder="">
                                    <label for="nome">NOME COMPLETO</label>
                                 </div>
                                 <div class="form-group my-3">
                                    <input class="form-login" id="email" placeholder="">
                                    <label for="email">EMAIL</label>
                                 </div>
                                 <div class="form-group mb-3">
                                    <input class="form-login" id="celular" placeholder="" >
                                    <label for="telefone">CELULAR</label>
                                    <div class="feedback fs-12">Digite um número de celular válido</div>
                                 </div>
                                 <div>
                                    <button id="btnLogin" class="btn  w-100 btn-desativado btn-sm fs-12" data-pagina="cadastro1">CONTINUAR</button>
                                 </div>
                                 '.$this->social.'
                                  <div class="text-dark mt-3 fs-14 fw-300">
            <span>Já tem uma conta?</span> 
            <a href="/acesso" class="text-decoration-none" data-noprevent="true">Entrar</a>
        </div>';
        }
       
    }
    
    function senhaPerdida(){
        if(isset($this->caminho[1])){
            $email = $this->caminho[1];
             return '<h2 class="fs-20 text-dark text-uppercase fw-700">Código de Validação</h2>
                                 <p class="text-dark fs-14">Digite o código enviado para '.$email.'.</p>
                                
                                    
                                  <div class="my-3">
                                            <div class="row">
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                                <div class="col-2 p-1">
                                                    <input class="form-control">
                                                </div>
                                             </div>
                                    </div>
                                
                                
                                 <div>
                                    <button id="btnLogin" class="btn  w-100 btn-desativado btn-sm fs-12" data-pagina="senha1">RECUPERAR SENHA</button>
                                 </div>
                                 
                                 
                            
                                  <div class="text-dark mt-3 fs-14 fw-300">
            <span>Lembrou da sua senha?</span> 
            <a href="/acesso" class="text-decoration-none" data-noprevent="true">Entrar</a>
        </div>';
        }else{
             return '<h2 class="fs-20 text-dark text-uppercase fw-700">Recupere sua senha</h2>
                                 <p class="text-dark fs-14">Digite seu e-mail para recuperar sua senha.</p>
                                
                                     <div class="form-group mt-3">
                                    <input class="form-login" id="email" placeholder="" type="text">
                                    <label for="email">SEU E-MAIL</label>
                                 </div>
                                 
                                
                                
                                 <div>
                                    <button id="btnLogin" class="btn  w-100 btn-desativado btn-sm fs-12" data-pagina="senha1">RECUPERAR SENHA</button>
                                 </div>
                                 
                                 
                            
                                  <div class="text-dark mt-3 fs-14 fw-300">
            <span>Lembrou da sua senha?</span> 
            <a href="/acesso" class="text-decoration-none" data-noprevent="true">Entrar</a>
        </div>';
        }
         
    }
    
    function html(){
        $render = "";
        switch($this->pagina){
            case 'acesso':
                $render = $this->login();
                break;
            case 'cadastro':
                $render = $this->cadastro();
                break;
            case 'senha-perdida':
                $render = $this->senhaPerdida();
                break;
            
        }
        
 
        
        $largura = isset($this->setup["largura"]) ? $this->setup["largura"] : "6";
   

        if(isset($this->setup["box"]) && $this->setup["box"] == "true"){
            if(!isset($this->setup["posicaobox"])){
                $this->setup["posicaobox"] = "1";
            }
            
             $boxEsquerda = $this->setup["posicaobox"] == "1" ? $this->box : "";
             $boxDireita = $this->setup["posicaobox"] == "1" ?  "" : $this->box;
             $coll = 8;
        }else{
            $boxEsquerda = "";
            $boxDireita = "";
            $coll = 12;
        }
       
       
       if(isset($this->caminho[1])){
            $boxEsquerda = "";
            $boxDireita = "";
            $coll = 12;
       }
        
        $logo = json_decode(v(["geral", "logotipo", "logo"] , "[]"), true);
        $logoMarca = json_decode(v(["geral", "logotipo", "logomarca"] , "[]"), true);
        $imagem = false;
        if(isset($logo[0]) && isset($logoMarca[0])){
            $tipo = v(["paginas", "login", "logo"], "logo");
            if($tipo == "logo"){
                $imagem = $logo[0];
            }else{
                $imagem = $logoMarca[0];
            }
        }
        
        if(!$imagem && isset($logo[0])){
            $imagem = $logo[0];
        }
        
        if(!$imagem && isset($logoMarca[0])){
            $imagem = $logoMarca[0];
        }
            
            

         if($imagem){
             $imagem = SETUP["dominio"]."conteudo/uploads/".$imagem;
            $logo = '<img src="'.$imagem.'"  style="max-height: 60px">';
        }else{
            $logo = '<h1 class="fw-700">< NOWN <span class="text-danger">/</span> ></h1>';
        }
        
        
        
        $bg = isset($this->setup["corbackground"]) ? $this->setup["corbackground"]  : "#f6f8fc";
        return '
        <div class="d-flex flex-column  justify-content-center text-dark h-100" style="background-color: '.$bg.'">
            <div>
                <div class="mb-4">
                    <div class="text-center">
                        <a class="text-center text-decoration-none text-dark" href="/" data-noprevent="true">
                           '.$logo.'
                        </a>
                    </div>
                </div>
                <div class="w-100 container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-'.$largura.'">
                            <div class="card bg-white border-0">
                                <div class="card-body p-0">
                                    <div class="row m-0">
                                        '.$boxEsquerda.'
                                        <div class="col-12 col-lg-'.$coll.' bg-white d-flex flex-column justify-content-center">
                                            <div class="p-3 p-lg-5">
                                                '.$render.'
                                            </div>
                                        </div>
                                        '.$boxDireita.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
    }
}
define("PAGINA", $this->caminho);
$pagina = isset($this->caminho[0]) ? $this->caminho[0] : "acesso";
$pagina = new Pagina($pagina, $this->caminho);
echo $pagina->html();
echo '<div class="toast-container position-fixed bottom-0 end-0 p-3"  id="toasts" style="z-index: 1000"></div>';
?>


