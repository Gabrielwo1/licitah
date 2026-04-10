<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nown 1001</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

:root {
    --c-txt: #666;
    --c-1: #6D00B9;
}

body {
    font-family: 'Inter';
    background: linear-gradient(135deg, #eaeafc, white);
}

.row-install {
    min-height: 100svh;
    padding: 50px 0;
}

.install-header {
    background: var(--c-1) linear-gradient(to right, transparent, rgba(0,0,0,0.43));
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 0 65px rgba(0,0,0,0.15), 0 5px 25px rgba(0,0,0,0.03);
    margin-bottom: 50px;
}

.install-header img {
    filter: brightness(0) invert(1);
}

.install-header h1 {
    color: white !important;
    font-weight: bold;
    margin-bottom: 5px !important;
    font-size: 35px !important;
}

.install-header h5 {
    font-weight: 300;
    font-size: 18px !important;
    color: white !important;
}

.install-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 0 65px rgba(0,0,0,0.15), 0 5px 25px rgba(0,0,0,0.03);
    margin-bottom: 50px;
}

.install-card-header {
    background: rgba(0,0,0,0.03);
    padding: 30px 30px;
    display: flex;
    gap: 10px;
    align-items: center;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}

.install-card-header i {
    width: 40px;
    font-size: 30px;
    color: var(--c-1);
    display: flex;
    justify-content: center;
}

.install-card-header h2 {
    font-size: 22px;
    font-weight: bold;
    margin: 0 !important;
}

.install-card-body {
    padding: 30px 20px;
}

.nown-label {
    display: block;
    font-size: 14px;
    font-weight: bold;
    color: var(--c-txt);
    margin-bottom: 5px;
}

.nown-input {
    background: rgba(0,0,0,0.02) !important;
    display: block;
    width: 100%;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.04) !important;
    outline: none !important;
    box-shadow: none !important;
    transition: .27s ease;
}

.nown-input:focus {
    border-color: var(--c-1) !important;
}

.install-error {
    background: #F99B9D;
    padding: 20px 30px;
    border-radius: 15px;
    border: 1px solid #F13636;
    color: #541414 !important;
    font-size: 15px;
    margin-top: -30px;
    margin-bottom: 20px !important;
}

.install-error i {
    font-size: 24px !important;
    vertical-align: middle;
    display: inline-block;
    margin-right: 10px;
}

p.install-hint {
    font-size: 13px;
    color: var(--c-txt);
}

p.install-hint a {
    color: var(--c-1);
}

.btn.btn-install {
    background: var(--c-1) !important;
    padding: 10px 17px;
    font-weight: 900;
    color: white;
    box-shadow: none !important;
    border: none !important;
    position: relative;
    overflow: hidden;
    font-size: 20px;
}

.btn.btn-install span {
    position: relative;
    z-index: 2;
}

.btn.btn-install:before {
    content: '';
    display: block;
    width: 0;
    height: 100%;
    background: rgba(0,0,0,0.3);
    position: absolute;
    left: 0;
    top: 0;
    transition: .27s ease;
}

.btn.btn-install:hover:before {
    width: 100%;
}

@media (min-width: 901px) and (max-width: 1500px){
    .install-header {
        margin-bottom: 30px !important;
    }
    
    .install-header h1 {
        font-size: 24px !important;
    }
    
    .install-card {
        margin-bottom: 30px;
    }
    
    .install-card-header {
        padding: 20px 30px !important;
    }
    
    .install-card-body {
        padding: 20px !important;
    }
    
    .install-error {
        margin-top: -10px !important;
    }
}

.install-password-input {
    position: relative;
}

.install-password-input .btn-show-password {
    position: absolute;
    top: 0;
    right: 0;
    width: 50px;
    height: 100%;
    background: var(--c-1) !important;
    color: white !important;
    font-size: 18px;
    border-radius: 0 10px 10px 0 !important;
    border: none !important;
}

.btn-show-password .fa-eye-slash {
    display: none;
}

.btn-show-password.is-visible .fa-eye-slash {
    display: block;
}

.btn-show-password.is-visible .fa-eye {
    display: none !important;
}

.install-generator {
    font-size: 14px;
    font-weight: bold;
    color: var(--c-1) !important;
}

.btn-purple {
    background: var(--c-1) !important;
    color: white !important;
}

.modal-header {
    background: rgba(0,0,0,0.03) !important;
    padding: 25px 30px !important;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}

.modal-title {
    font-size: 22px;
    font-weight: bold;
    margin: 0 !important;
}

.modal-header .btn-close {
    box-shadow: none !important;
}

.modal-footer {
    background: rgba(0,0,0,0.03) !important;
    padding: 20xp !important;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
        </style>
    </head>
    <body>
        <div class="container-fluid install-wrap">
            <div class="container">
                <div class="row row-install justify-content-center align-items-center">
                    <div class="col-lg-9">
                        <!--install header-->
                        <div class="install-header">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-lg-8">
                                    <h1>Boas vindas ao <strong>NOWN</strong>!</h1>
                                    <h5>Siga os passos para completar a instalação do sistema.</h5>
                                </div>
                                <div class="col-lg-4">
                                    <img src="https://media.discordapp.net/attachments/1174005169411850293/1182737937201365042/logo-color.png?ex=6585c98a&is=6573548a&hm=0be9302107000da4901c02652f33afeb25d3b05d9c7c37847341a066ae9f8bd4&=&format=webp&quality=lossless&width=1440&height=194" class="d-block m-auto w-100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="install-error" style="display: none;">
                            <i class="fad fa-exclamation"></i> <strong>Oops...</strong> <span id="install_error"></span>
                        </div>
                        
                        <div class="install-body row gx-5">
                            <div class="col-lg-6">
                                <div class="install-card">
                                    <div class="install-card-header">
                                        <i class="fad fa-server"></i>
                                        <h2>Banco de Dados</h2>
                                    </div>
                                    <div class="install-card-body">
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="servidor">Servidor</label>
                                            <input class="nown-input" id="servidor" value="localhost">
                                        </div>
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="banco">Banco de Dados</label>
                                            <input class="nown-input" id="banco">
                                        </div>
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="usuario">Usuário do Banco de Dados</label>
                                            <input class="nown-input" id="usuario">
                                        </div>
                                        <div class="install-input">
                                            <label class="nown-label" for="senha">Senha do Banco de Dados</label>
                                            <input class="nown-input" id="senha">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="install-card">
                                    <div class="install-card-header">
                                        <i class="fad fa-user-cog"></i>
                                        <h2>Usuário</h2>
                                    </div>
                                    <div class="install-card-body">
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="nome">Nome Completo</label>
                                            <input class="nown-input" id="nome">
                                        </div>
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="email">E-mail</label>
                                            <input class="nown-input" type="email" id="email">
                                        </div>
                                        <div class="install-input mb-3">
                                            <label class="nown-label" for="celular">Celular</label>
                                            <input class="nown-input mask-celular" id="celular">
                                        </div>
                                        <div class="install-input">
                                            <div class="d-flex justify-content-between">
                                                <label class="nown-label" for="senhaSistema">Senha do Sistema</label>
                                                <a href="#" class="install-generator" data-bs-toggle="modal" data-bs-target="#passwordModal">Gerador de Senha</a>
                                            </div>
                                            <div class="install-password-input">
                                                <input class="nown-input" type="password" id="senhaSistema">
                                                <button type="button" class="btn btn-show-password" id="togglePassword"><i class="fas fa-eye"></i><i class="fas fa-eye-slash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="install-card mb-0">
                                    <div class="install-card-body">
                                        <div class="row justify-content-between align-items-center" style="margin: -10px 0 !important;">
                                            <div class="col-lg-8">
                                                <p class="m-0 install-hint">Precisa de ajuda? Consulte a <a href="#">nossa documentação</a>.</p>
                                            </div>
                                            <div class="col-lg-3">
                                                <button class="btn btn-install w-100 text-uppercase" id="instalar"><span>Instalar</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<!-- Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="passwordModalLabel">Gerador de Senha</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="install-input mb-3">
                    <label class="nown-label" for="email">Senha Segura</label>
                    <div class="d-flex justify-content-between">
                        <input class="nown-input" type="text" id="senhaSegura" disabled>
                        <button type="button" class="btn btn-secondary" id="generatePass" style="width: 310px !important; margin-left: 10px;">Gerar Nova Senha</button>
                    </div>
                    <p style="margin-top: 15px !important; font-size: 14px !important; margin-bottom: 0 !important;"><strong>Lembre-se de copiar e salvar sua senha em um local seguro.</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-purple" id="usePass">Utilizar Senha</button>
            </div>
        </div>
    </div>
</div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
        jQuery(document).ready(function(){
        	$(".mask-celular").on("focus", function() {
        		if($(this).val().length < 14) {
        			$(this).mask('(00) 0000-00000');
        		}
        	});
        
        	$(".mask-celular").keyup(function() {
        		if($(this).val().length < 14) {
        			$(this).mask('(00) 0000-00000');
        		} else {
        			$(this).mask('(00) 00000-0000');
        		}
        	});
        });
        
        const btnTogglePass = document.getElementById("togglePassword");
        const inputSenha = document.getElementById("senhaSistema");
        btnTogglePass.addEventListener("click", function(){
            btnTogglePass.classList.toggle("is-visible");
            var type = inputSenha.getAttribute('type') == 'password' ? 'text' : 'password';
            inputSenha.setAttribute("type",type)
        });
        
        function safePass(){
            var randPassword = new Array(10).fill("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#&()").map(x => (function(chars) { let umax = Math.pow(2, 32), r = new Uint32Array(1), max = umax - (umax % chars.length); do { crypto.getRandomValues(r); } while(r[0] > max); return chars[r[0] % chars.length]; })(x)).join('');
            var format = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
            if(format.test(randPassword)){
                randPassword = randPassword;
            } else {
                var r = Math.floor(1 * 6);
                randPassword = randPassword.slice(0, r -1) + "@" + randPassword.slice(r);
            }
            return randPassword;
        }
        
        const passModal = document.getElementById('passwordModal');
        const passInput = document.getElementById('senhaSegura');
        passModal.addEventListener('show.bs.modal', event => {
            var randPassword = safePass();
            passInput.value = randPassword;
        })
        
        const generatePass = document.getElementById('generatePass');
        generatePass.addEventListener("click", function(){
            var randPassword = safePass();
            passInput.value = randPassword;
        });
        
        const usePass = document.getElementById('usePass');
        usePass.addEventListener("click", function(){
            let modal = bootstrap.Modal.getOrCreateInstance(passModal);
            modal.hide();
            inputSenha.value = passInput.value
        })
        </script>
  </body>
</html>



<script>

    class Instalacao{
        constructor(){
            document.getElementById("instalar").addEventListener("click", this.instalar.bind(this))
            
            this.servidor = document.getElementById("servidor")
            this.banco = document.getElementById("banco")
            this.usuario = document.getElementById("usuario")
            this.senha = document.getElementById("senha")
            this.nome = document.getElementById("nome")
            this.email = document.getElementById("email")
            this.celular = document.getElementById("celular")
            this.senhaSite = document.getElementById("senhaSistema");
            
            this.limpa = this.limpa.bind(this)
            
            this.servidor.addEventListener("input", this.limpa)
            this.senha.addEventListener("input", this.limpa)
            this.usuario.addEventListener("input", this.limpa)
            this.banco.addEventListener("input", this.limpa)
            
            
            this.senhaSite.addEventListener("input", this.limpa)
            this.celular.addEventListener("input", this.limpa)
            this.email.addEventListener("input", this.limpa)
            
        }
        
        limpa(){
            event.target.value = event.target.value.trim(); 
            // event.target.value = event.target.value.replace(/\s/g, '');
        }
        
        instalar(){
            
            if(this.servidor.value && this.banco.value && this.usuario.value && this.senha.value && this.nome.value && this.email.value && this.senhaSite.value && this.celular.value){
                var data = new FormData();
                data.append("servidor", this.servidor.value)
                data.append("banco", this.banco.value)
                data.append("usuario", this.usuario.value)
                data.append("senha", this.senha.value)
                data.append("nome", this.nome.value)
                data.append("email", this.email.value)
                data.append("celular", this.celular.value)
                data.append("senhaSistema", this.senhaSite.value)
                this.ajax.bind(this)(data)
            }else{
                document.querySelectorAll(".install-error")[0].style.display = 'block';
                document.querySelectorAll("#install_error")[0].innerHTML = 'Preencha todos os campos para continuar.';
                setTimeout(function(){
                    document.querySelectorAll(".install-error")[0].style.display = 'none';
                    document.querySelectorAll("#install_error")[0].innerHTML = '';
                }, 5000);
            }
            
            
        }
        
        ajax(data){
             const xhttp = new XMLHttpRequest();
             xhttp.onload = () =>{
                 try{
                     console.log(xhttp.responseText)
                      var obj = JSON.parse(xhttp.responseText)
                 if(obj.status == "erro"){
                    document.querySelectorAll(".install-error")[0].style.display = 'block';
                    document.querySelectorAll("#install_error")[0].innerHTML = 'Verifique as informações do banco de dados e tente novamente.';
                    setTimeout(function(){
                        document.querySelectorAll(".install-error")[0].style.display = 'none';
                        document.querySelectorAll("#install_error")[0].innerHTML = '';
                    }, 5000);
                 }else{
                     location.reload(true);
                 }
                 }catch(e){
                     console.log(e)
                     console.log(xhttp.responseText)
                 }
                
             }
             xhttp.open("POST", "admin/install.php");
             xhttp.send(data);
        }
    }
    

    window.addEventListener("load", carregado);
    
    function carregado(){
       new Instalacao();
    }
</script>