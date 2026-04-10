function tokenLogin(){
    const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  let token = '';

  for (let i = 0; i < 32; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    token += characters.charAt(randomIndex);
  }

  return token;
}

function validarInput(input) {
  // Remover caracteres não numéricos
  const inputNumerico = input.replace(/\D/g, '');

  // Validar CPF
  function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
      return false;
    }
    let sum = 0;
    let remainder;
    for (let i = 1; i <= 9; i++) {
      sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) {
      remainder = 0;
    }
    if (remainder !== parseInt(cpf.substring(9, 10))) {
      return false;
    }
    sum = 0;
    for (let i = 1; i <= 10; i++) {
      sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) {
      remainder = 0;
    }
    if (remainder !== parseInt(cpf.substring(10, 11))) {
      return false;
    }
    return true;
  }

  // Validar CPF
  if (/^\d{11}$/.test(inputNumerico)) {
    if (validarCPF(inputNumerico)) {
      const cpf = inputNumerico.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
      return {tipo:"cpf", valor:cpf.trim()};
    }
  }

  // Validar telefone
  if (/^\d{10,11}$/.test(inputNumerico)) {
    const telefone = inputNumerico.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1)$2-$3');

    return {tipo:"telefone", valor:telefone.trim()};
  }

  // Validar e-mail
  if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input)) {
    return {tipo:"e-mail", valor:input};
  }

  // Validar nome de usuário
  if (/^[a-zA-Z0-9]{8,}$/.test(input)) {
      if(input.length > 7 && input.length < 20){
          if(!/^\d+$/.test(input)){
              return {tipo:"usuario", valor:input.trim()};
          }
          
      }
      
    
  }

  return false;
}

function efeitoInput(){

    var inputs = document.getElementsByTagName("input")
    var i = 0;
        while(i < inputs.length){
            new InputEfeito(inputs[i]);
            i++;
        }
        
}

function showSenha(){
        if(event.target.classList.contains("botao")){
            var btn = event.target
        }else{
            var btn = event.target.closest(".botao")
        }
        
        
        var input = btn.closest("DIV").getElementsByTagName("INPUT")[0]
        if(input.type == "text"){
            input.type = "password";
            btn.innerHTML = '<i class="bi bi-eye"></i>';
        }else{
            input.type = "text";
            btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
        }
    }
    
function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
  var cookieName = name + '=';
  var cookies = document.cookie.split(';');
  
  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i].trim();
    
    if (cookie.indexOf(cookieName) === 0) {
      return cookie.substring(cookieName.length, cookie.length);
    }
  }
  
  return '';
}

function trataNome(nome){
    let str = nome;

// Converter todas as letras para minúsculas
str = str.toLowerCase();

// Remover espaços em branco extras no início e no final da string
str = str.trim();

// Dividir a string em palavras
let words = str.split(" ");

// Converter a primeira letra de cada palavra em minúscula
for (let i = 0; i < words.length; i++) {
  words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
}

// Juntar as palavras novamente sem espaços
let result = words.join("");

return result;

}

function getAndDestroyCookie(name) {
  var cookies = document.cookie.split(";");

  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i].trim();

    if (cookie.startsWith(name + "=")) {
      var value = cookie.substring(name.length + 1);
      
      // Remove o cookie
      document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

      return value;
    }
  }

  // Caso o cookie não exista, retorne uma mensagem de erro
  return false;
}

class InputEfeito{
    constructor(input){
        
        this.start = this.start.bind(this)
        this.end = this.end.bind(this)
        
        this.input = input
        this.label =  this.input.nextElementSibling;
        if(this.input.type == "text" || this.input.type == "password"){
            this.input.addEventListener('focus', this.end);
            this.input.addEventListener('blur', this.start);
        }
        
        this.start();
    }
    
    start(){
        if (this.input.value === '') {
            this.label.style.top = '10px';
            this.label.style.left = '20px';
            this.label.style.fontSize = '12px';
            this.label.style.backgroundColor = 'transparent';
            this.label.style.padding = '0';
            this.label.style.color = '#000';
        }
             
    }
    
    end(){
    this.label.style.top = '-10px';
    this.label.style.left = '10px';
    this.label.style.fontSize = '12px';
    this.label.style.backgroundColor = 'white';
    this.label.style.padding = '0 5px';
    this.label.style.color = '#666';
    }
    
}

class Toast {
  constructor(mensagem, duracao, background) {
    this.mensagem = mensagem;
    this.duracao = duracao;
    this.background = background;
    this.toastElement = null;
    this.toast = null;
  }

  exibir() {
    // Criar o elemento de toast
    this.toastElement = document.createElement("div");
    this.toastElement.classList.add("toast", "mt-2");
    this.toastElement.classList.add("show");

    this.toastElement.setAttribute("role", "alert");
    this.toastElement.setAttribute("aria-live", "assertive");
    this.toastElement.setAttribute("aria-atomic", "true");

    // Criar o elemento do header do toast
    var toastHeaderElement = document.createElement("div");
    toastHeaderElement.classList.add("toast-header", "bg-danger", "text-light");

    // Criar o elemento do título do header do toast
    var toastTitleElement = document.createElement("strong");
    toastTitleElement.classList.add("me-auto");
    toastTitleElement.textContent = document.getElementsByTagName("title")[0].dataset.start
    toastHeaderElement.appendChild(toastTitleElement);

    // Criar o elemento do timestamp do header do toast
    var toastTimestampElement = document.createElement("small");
    toastTimestampElement.textContent = "Agora Mesmo"; // Insira o timestamp desejado
    toastHeaderElement.appendChild(toastTimestampElement);

    // Adicionar o botão de fechar
    var closeButtonElement = document.createElement("button");
    closeButtonElement.type = "button";
    closeButtonElement.classList.add("btn-close");
    closeButtonElement.setAttribute("data-bs-dismiss", "toast");
    closeButtonElement.setAttribute("aria-label", "Close");
    toastHeaderElement.appendChild(closeButtonElement);

    // Adicionar o elemento do header ao toast
    this.toastElement.appendChild(toastHeaderElement);

    // Criar o elemento do corpo do toast
    var toastBodyElement = document.createElement("div");
    toastBodyElement.classList.add("toast-body");
    toastBodyElement.textContent = this.mensagem;
    this.toastElement.appendChild(toastBodyElement);

    // Adicionar o toast à div de toasts existente no DOM
    this.toastsContainer = document.getElementById("toasts")
    
   
    this.toastsContainer.appendChild(this.toastElement);

    // Instanciar o toast do Bootstrap JS
    this.toast = new bootstrap.Toast(this.toastElement);

    // Exibir o toast
    this.toast.show();

    // Definir tempo de duração do toast
    setTimeout(() => {
      this.fechar();
    }, this.duracao * 1000);
  }

  fechar() {
    this.toast.hide();
  }

  remover() {
    this.toastElement.remove();
  }
}

class Login{

    constructor(etapa){
        this.etapa = etapa

        this.socialControl = this.socialControl.bind(this)
        this.loginFacebook = this.loginFacebook.bind(this)
        this.loginApple = this.loginApple.bind(this)
        this.qr = this.qr.bind(this)
        this.canal = this.canal.bind(this)
        this.login = this.login.bind(this)
        this.erroLogin = this.erroLogin.bind(this)
        this.removerClasses = this.removerClasses.bind(this)
        this.btnControl = this.btnControl.bind(this)
        this.nexLogin = this.nexLogin.bind(this)
   
        this.card = document.getElementsByClassName("card")[0]
        this.capcha = false;
        

        

        if(document.getElementById("usuario")){
            this.usuario = document.getElementById("usuario")
            this.usuario.removeAttribute("id")
            evento(this.usuario, "input", this.btnControl)
        }
        
        if(document.getElementById("senha")){
            this.senha = document.getElementById("senha")
            this.senha.removeAttribute("id")
            evento(this.senha, "input", this.btnControl)

            this.btnSenha = this.senha.closest(".form-group").getElementsByTagName("button")[0]
            this.btnSenha.addEventListener("click", showSenha)
        }
        
        
        if(document.getElementById("lembrarme")){
            this.lembrarMe = document.getElementById("lembrarme")
        }
        
        if(document.getElementById("btnLogin")){
            this.btnLogin = document.getElementById("btnLogin")
            this.btnLogin.removeAttribute('id');
            if(!this.btnLogin.classList.contains("g-recaptcha")){
                 this.btnLogin.addEventListener("click", this.nexLogin)
              
            }
              this.btnLogin.addEventListener("click", this.loading.bind(this))
                 ls("https://www.google.com/recaptcha/api.js")
        }
        
        
        this.socialControl();
        this.token = tokenLogin();
        
        var script = document.createElement("SCRIPT")
        script.src = `${dominio}/assets/bibliotecas/qrcode/index.js`
        document.getElementsByTagName("HEAD")[0].appendChild(script)
        script.onload = ()=>{
            this.qr(this.token);
        }
        
        var script = document.createElement("SCRIPT")
        script.src = "https://js.pusher.com/7.2/pusher.min.js"
        document.getElementsByTagName("HEAD")[0].appendChild(script)
        script.onload = ()=>{
          
            this.pusher = new Pusher('0369df9c6f1501b83a2a', {
              cluster: 'sa1'
             });
             this.canal(this.token); 
        }
          
           
   
    }
    
    loading(){
            
        this.btnLogin.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border spinner-border-sm" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
     this.btnLogin.classList.remove("btn-ativado")
     this.btnLogin.classList.add("btn-desativado")
     console.log("ta de brinques")
    }
    
    validado(){
       this.login();
    }
    
    nexLogin(){
        if(this.etapa == 1){
            var valido = validarInput(this.usuario.value);
            if(valido){
                goUrl(`acesso/${valido.tipo}/${valido.valor}`)
            }else{
                this.erroLogin();
                var toast = new Toast("Credenciais Inválidas", 5, "#f8d7da");
                toast.exibir();
            }
        }else{
            this.login();
        }
    }
    
    removerClasses() {
        this.card.classList.remove("animate__animated", "animate__shakeX");
        
    }
    
    canal(token){
        this.channel = this.pusher.subscribe(`login-${token}`);
        this.channel.bind('login', function(data) {
            var d = new FormData();
        d.append("token", data.message)
        d.append("key", 2)
        const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    var obj = JSON.parse(this.responseText)
    if(obj.token){
        location.reload();
    }
  }
  xhttp.open("POST", `${dominio}/admin/login.php`);
  xhttp.send(d);
  
  
           
        }); 
    }
    
    socialControl(){
        /*
        var btns = document.getElementsByClassName("btnSocial")
        
        var i = 0;
        
        while(i < btns.length){
            var rede = btns[i].dataset.rede.toLowerCase()
            console.log(rede)
            switch(rede){
                case "facebook":
                     this.btnFacebook = btns[i];
                     this.btnFacebook.addEventListener("click", this.loginFacebook)
                     break;
                case "apple":
                     this.btnApple = btns[i];
                     this.btnApple.addEventListener("click", this.loginApple)
                    break;
            }
            
            i++;
        }
        
        */
    }
    
    loginApple(){
          AppleID.auth.signIn();
    }
    
    initializeAppleSignIn() {
      // Substitua 'SEU_CLIENT_ID' pelo ID do cliente obtido no Apple Developer Console
      const clientId = 'SEU_CLIENT_ID';

      // Configuração para autenticação com a Apple
      const authOptions = {
        clientId: clientId,
        scope: 'email', // Pode ser 'name' ou 'email' ou ambos
        redirectURI: 'http://localhost:3000/auth/apple/callback', // Substitua pela URL correta
      };

      // Inicializar o Apple Sign-In com as opções de autenticação
      AppleID.auth.init(authOptions);
    }
    
    loginFacebook(){
        console.log("here")
      FB.login(function(response){
  console.log(response)
});
    }
    
    qr(token){
        if(document.getElementById("qrcode")){
           document.getElementById("qrcode").innerHTML = "";
        this.qrcode = new QRCode(document.getElementById("qrcode"), {
            text:  token,
	width: 150,
	height: 150,
	colorDark : "#000000",
	colorLight : "#ffffff",
	correctLevel : QRCode.CorrectLevel.H
});
        setInterval(()=>{
            this.token = tokenLogin();
            this.qrcode.makeCode(this.token)
            this.canal(this.token);
        }, 10000)  
        }
       
    }
    
    erroLogin(){
        console.log("erro login")
        this.card.classList.add("animate__animated","animate__shakeX");
        this.card.addEventListener("animationend", this.removerClasses);
 
    }
    
    login(){
        
        
    
       
        
        var dataDiv = document.getElementById("loginData")
        var tipo = dataDiv.closest("DIV").getElementsByTagName("DIV")[0].innerText.toLowerCase()
        if(tipo == "cpf" || tipo == "telefone"){
            var user = document.getElementById("loginData").innerText.replace(/\D/g, '');
        }else{
             var user = document.getElementById("loginData").innerText
        }
        
    
    
    
    
       
        var data = new FormData();
        if(user && this.senha.value){
         data.append("login", user)
        data.append("senha", this.senha.value)
        data.append("key", 1)
        const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
      
    var obj = JSON.parse(xhttp.responseText)
    console.log(obj)
    switch(parseInt(obj.message)){
        case 1:
            if(document.getElementById("lembrarme") && document.getElementById("lembrarme").checked){
                setCookie("sessaoAcesso", obj.hash , 365);
            }else{
                setCookie("sessaoAcesso", obj.hash , 1);
            }
            window.location.href = `${dominio}`;
            break;
        case 2:
            var toast = new Toast("Verifique as informações e tente novamente.", 500, "#f8d7da");
             toast.exibir();
            break;
        case 3:
            var toast = new Toast("Acesso bloqueado por segurança. Volte em alguns minutos", 5, "#f8d7da");
            toast.exibir();
            break;
        default:
            
            this.erroLogin();
            break;
    }
    
      this.btnLogin.innerHTML = `LOGIN`
     this.btnLogin.classList.add("btn-ativado")
  this.btnLogin.classList.remove("btn-desativado")
    
    
  }.bind(this);
  xhttp.open("POST", `${dominio}/admin/login.php`);
  xhttp.send(data); 
        }else{
            this.erroLogin();
        }
        
    }
    
    btnControl(){
        var input = this.etapa == 1 ? this.usuario : this.senha
        input.value = input.value.trim();
        if(input.value){
            console.log("ativado")
            this.btnLogin.classList.remove("btn-desativado")
            this.btnLogin.classList.add("btn-ativado")
            this.btnLogin.removeAttribute("disabled")
        }else{
           this.btnLogin.setAttribute("disabled", "")
           this.btnLogin.classList.add("btn-desativado")
           this.btnLogin.classList.remove("btn-ativado")
        }
    }
   
    
}

class Cadastro {
  constructor(etapa) {
    this.etapa = etapa;
    this.btnLogin = document.getElementById("btnLogin")
    this.ajax = this.ajax.bind(this)
    
    if(this.etapa == 1){
        this.btnLogin.addEventListener("click", this.nextStep.bind(this));
    }else{
        
        
        if(!this.btnLogin.classList.contains("g-recaptcha")){
            this.btnLogin.addEventListener("click", this.cadastra.bind(this));
        }
        
        this.btnLogin.addEventListener("click", this.loading.bind(this))
        
        if(document.getElementById("dataPre")){
            this.data = JSON.parse(document.getElementById("dataPre").innerText)
            document.getElementById("dataPre").remove();
        }
        
    }
    

    
    if (this.etapa === 1) {
      this.nome = document.getElementById("nome");
      this.email = document.getElementById("email");
      this.celular = document.getElementById("celular");
      
      
       ls(`${dominio}/assets/bibliotecas/mascaras/mask.js`, this.mascara.bind(this));
     
      
      this.nome.addEventListener("input", this.analisador.bind(this));
      this.email.addEventListener("input", this.analisador.bind(this));
      this.celular.addEventListener("input", this.analisador.bind(this));
    }
    else{
        if(document.getElementById("senha")){
            this.senha = document.getElementById("senha")
            this.senha.removeAttribute("id")
            if(document.getElementById("pesoSenha")){
                this.peso = document.getElementById("pesoSenha");
                this.senha.addEventListener("input", this.validasenha.bind(this));
            }
            
            this.btnSenha = this.senha.closest(".form-group").getElementsByTagName("button")[0]
            this.btnSenha.addEventListener("click", showSenha)
            
            
        }
    }
    
    
    
  }
  
     validado(){
       this.cadastra();
    }
    
  
      loading(){
            
        this.btnLogin.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border spinner-border-sm" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
     this.btnLogin.classList.remove("btn-ativado")
  this.btnLogin.classList.add("btn-desativado")
    }
    
  
  mascara(){
       var SPMaskBehavior = function (val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
spOptions = {
  onKeyPress: function(val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
};

$('#celular').mask(SPMaskBehavior, spOptions);

  }
  
  ajax(data){
      const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    var obj = JSON.parse(this.responseText)

    if(obj.resposta.erro){
        var msg = "";
        switch(obj.resposta.codigo){
            case 1:
                msg = "Falha no Cadastro, começa o processo do Zero";
                break;
            case 2:
                msg = "Esse e-mail já está sendo usado em outra conta, cadastre-se usando outro e-mail ou acesse usando seu e-mail";
                break;
            case 3:
                msg = "Esse número de telefone já está sendo usado em outra conta, cadastre-se usando outro telefone ou acesse usando seu telefone";
                break;
        }
        window.history.back();
        setCookie("mensagemStart", msg, 1);
    }else{
   
        window.location.href = `${dominio}`;

    }
    
    
  }
  xhttp.open("POST", `${dominio}/admin/cadastro.php`);
  xhttp.send(data);
  }
  
  cadastra(){
      var aprovados = 0;

      var senha = this.senha.value
      var nome = this.data[0]
      var email = this.data[2]
      var telefone = this.data[1]
      
      if(this.validasenha(senha) && nome && this.validarEmail(email) && this.validarCelular(telefone)){
          var data = new FormData();
          data.append("nome", nome)
          data.append("email", email);
          data.append("telefone", telefone)
          data.append("senha", senha)
          data.append("acao", "cadastro")
          
          var usuario = new HashUser();
          data.append("usuario", usuario.cookieValido("usuario"))
          
           if(usuario.cookieValido("referencia")){
               data.append("referencia", usuario.cookieValido("referencia"))
               usuario.destruirCookie("referencia");
           }
            
          
          
          this.ajax(data);
          
          
      }else{
          console.log("reprovado")
      }
  }
  
  validasenha(){

 var senha = this.senha.value;
  var comprimentoMinimo = 8;
  var possuiNumero = /\d/.test(senha);
  var possuiLetraMaiuscula = /[A-Z]/.test(senha);
  var possuiLetraMinuscula = /[a-z]/.test(senha);
  var possuiCaracterEspecial = /[!@#$%^&*()\-_=+{}[\]:";<>?|,.\/~`]/.test(senha);

  // Verificar o peso da senha com base nos critérios
  var peso = 0;

 if (senha.length >= 8) {
     peso++;

  }

  if (possuiNumero) {
    peso += 1;
  }

  if (possuiLetraMaiuscula) {
    peso += 1;
  }

  if (possuiLetraMinuscula) {
    peso += 1;
  }

  if (possuiCaracterEspecial) {
    peso += 1;
  }
  
  
    
  var divs = this.peso.getElementsByTagName("DIV")  
  var mensagem = document.getElementById("mensagemPeso")
  divs[0].className = "";
  divs[1].className  = "";
  divs[2].className  = "";
  mensagem.className = "";
  var aprovado = false;

  if(peso < 3){
      divs[0].classList.add("bg-danger")
      mensagem.innerText = "Sua senha é muito fraca. Crie uma senha mais forte."
      mensagem.classList.add("text-danger", "fs-12")
  }else if(peso < 4){
     divs[0].classList.add("bg-primary")
     divs[1].classList.add("bg-primary")
     mensagem.innerText = "Está bem, mas você deveria criar uma senha mais difícil."
     mensagem.classList.add("text-primary", "fs-12")
     aprovado = true;
  }else{
     divs[0].classList.add("bg-success")
     divs[1].classList.add("bg-success")
     divs[2].classList.add("bg-success")
     mensagem.innerText = "Ótimo! Você criou uma senha forte."
     mensagem.classList.add("text-success", "fs-12");
     aprovado = true;
  }
  
  if(aprovado){
            this.btnLogin.classList.remove("btn-desativado")
            this.btnLogin.classList.add("btn-ativado")
            return true
    }else{
           this.btnLogin.classList.add("btn-desativado")
           this.btnLogin.classList.remove("btn-ativado")
           return false;
        }
        
  
        
    
  
  }
  
  analisador() {
    var nome = this.nome.value;
    var email = this.email.value;
    var celular = this.celular.value;
    
    // Chamar os validadores específicos
    if (this.etapa === 1) {
        if(nome && email && celular){
            var livre = true;
            
            if(this.nome.classList.contains("is-invalid")){
                livre = false;
            }
            
            if(this.email.classList.contains("is-invalid")){
                if(!this.validarEmail(email)){
                    livre = false;
                }else{
                    this.email.classList.remove("is-invalid")
                }
                
            }
            
            if(this.celular.classList.contains("is-invalid")){
                if(!this.validarCelular(celular)){
                     livre = false;
                }else{
                    this.celular.classList.remove("is-invalid")
                }
               
            }
            
            
            if(livre){
                 this.btnLogin.classList.remove("btn-desativado")
                 this.btnLogin.classList.add("btn-ativado")
            }
        }else{
            this.btnLogin.classList.add("btn-desativado")
            this.btnLogin.classList.remove("btn-ativado")
        }
    }
  }
  
  validarEmail(email) {
    // Expressão regular para validação de e-mail
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
      // E-mail inválido, faça a manipulação adequada (exemplo: exibir mensagem de erro)
      return false;
    } else {
      // E-mail válido, faça a manipulação adequada (exemplo: remover mensagem de erro)
      return true;
    }
  }
  
   validarCelular(celular) {
  // Expressão regular para validação de número de celular
  const celularRegex = /^\(\d{2}\)\d{4,5}-\d{4}$/;

  if (!celularRegex.test(celular)) {
    // Número de celular inválido, faça a manipulação adequada (exemplo: exibir mensagem de erro)
    return false;
  } else {
    // Número de celular válido, faça a manipulação adequada (exemplo: remover mensagem de erro)
    return true;
  }
}
  
  nextStep(){
      var valido = true;
      
      if(this.nome.value < 10){
          valido = false;
          this.nome.classList.add("is-invalid")
      }
      
      if(!this.validarCelular(this.celular.value.replace(/\s/g, ''))){
          valido = false;
          this.celular.classList.add("is-invalid")
      }
      
      if(!this.validarEmail(this.email.value.replace(/\s/g, ''))){
          valido = false;  
          this.email.classList.add("is-invalid")
      }
      
      
      if(!valido){
          this.btnLogin.classList.add("btn-desativado")
          this.btnLogin.classList.remove("btn-ativado")
      }else{
          window.location.href = `${dominio}/cadastro/${trataNome(this.nome.value)}/${this.celular.value.replace(/\s/g, '')}/${this.email.value.replace(/\s/g, '')}`;
      }
  }
}

class Senha{
    constructor(){
        this.btnControl = this.btnControl.bind(this)
        this.recupera = this.recupera.bind(this)
        
        this.btnLogin = document.getElementById("btnLogin")
        this.email = document.getElementById("email")
        this.email.addEventListener("input", this.btnControl);
        this.btnLogin.addEventListener("click", this.recupera)
        
    }
    
    recupera(){
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
        if(!emailRegex.test(this.email.value)) {
            this.email.classList.add("is-invalid")
            this.btnLogin.classList.add("btn-desativado")
            this.btnLogin.classList.remove("btn-ativado")
        }else{
             window.location.href = `${dominio}/senha-perdida/${this.email.value}`;
        }
    }
    
    btnControl(){
      var input = this.email
        input.value = input.value.trim();
        if(input.value){
            this.btnLogin.classList.remove("btn-desativado")
            this.btnLogin.classList.add("btn-ativado")
        }else{
           this.btnLogin.classList.add("btn-desativado")
           this.btnLogin.classList.remove("btn-ativado")
        }
    }
    
}

function decodeJWT(jwt) {
  const base64Url = jwt.split('.')[1]; // Obtém a parte do payload do JWT
  const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/'); // Corrige a URL base64
  const jsonPayload = decodeURIComponent(escape(atob(base64))); // Decodifica o payload JSON
  return JSON.parse(jsonPayload);
}

function ajaxsocial(data){
     const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    console.log(this.responseText);
    var r = JSON.parse(this.responseText)
    if(r.sucesso.sucesso.erro){
        
    }else{
        window.location.href = `${dominio}`;
    }
  }
  xhttp.open("POST", `${dominio}/admin/social.php`);
  xhttp.send(data);
}

function loginGoogle(r){
     var infos = JSON.stringify(decodeJWT(r.credential));
     var data = new FormData();
     data.append("acao", "login");
     data.append("rede", "google");
     data.append("infos", infos);
     
     
     
     ajaxsocial(data)
 }
 
function cadastroGoogle(r){
     var infos = JSON.stringify(decodeJWT(r.credential));
     var data = new FormData();
     data.append("acao", "cadastro")
     data.append("rede", "google")
     data.append("infos", infos)
     ajaxsocial(data)
 }
 
class PaginaLogin{
    constructor(){
        if(getCookie("sessaoAcesso")){
            var chave = getCookie("sessaoAcesso");
            var data = new FormData();
            data.append("acao", "sessao");
            data.append("chave", chave);
            this.ajax.bind(this)(data);
        }
        
        if(document.getElementById("btnLogin") && document.getElementById("btnLogin").dataset.pagina){
            var pagina = document.getElementById("btnLogin").dataset.pagina;
            document.getElementById("btnLogin").removeAttribute("pagina");
            switch(pagina){
                case 'login1':
                    this.page = new Login(1);
                    break;
                case 'login2':
                    this.page =  new Login(2);
                    break;
                case 'cadastro1':
                    this.page =  new Cadastro(1);
                    break;
                case 'cadastro2':
                    this.page =  new Cadastro(2);
                    break;
                case 'senha1':
                    this.page =  new Senha(1);
                    break;
            }
        }
        
        var msg = getAndDestroyCookie("mensagemStart");
        if(msg){
        console.log("existia")
        var toast = new Toast(msg, 5, "#f8d7da");
        toast.exibir();
    }
}

    ajax(data){
       const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    var obj = JSON.parse(this.responseText)
    if(obj.resposta){
        setCookie("sessaoAcesso", obj.resposta , 365);
        window.location.href = dominio

        
    }else{
         document.cookie = "sessaoAcesso" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
  }
  xhttp.open("POST", `${dominio}/admin/cadastro.php`);
  xhttp.send(data);
    }
    
    capcha(code){
        this.page.loading();
    }
    
    validado(){
   
        this.page.validado();
    }
 
}

var pagina;
function paginaLoginCarregada(){
    efeitoInput();
    pagina = new PaginaLogin();
}

function onSubmit(token) {
    console.log("chamou o submit")
    pagina.capcha();
    var data = new FormData();
    data.append("g-recaptcha-response", token)
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
    var obj = JSON.parse(this.responseText)
    if(obj.seguro){
        pagina.validado();
    }else{
        alert("Navegação Não Autorizada");
    }
        }
        xhttp.open("POST", `/admin/recapcha.php`);
        xhttp.send(data);
}


function paginaAcesso(){
    paginaLoginCarregada();
}

