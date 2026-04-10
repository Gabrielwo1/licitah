var ouvidoresEventos = [];

function apiGlobal(data = false, callBack = false){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {

        try{
        var obj = JSON.parse(this.responseText);
        }catch{
          
            console.log("erro json")
            console.log(this.responseText)
        }
        
        if(obj){
            if(obj.erro){
                console.log(obj.mensagem)
            }else{
                callBack(obj.sucesso)
            }
            
        }

    }
    xhttp.open("POST", `${dominioAdress}/admin/publico.php`);
    xhttp.send(data);
}

class Sessao{
    constructor(){
        this.ajax = this.ajax.bind(this)
        this.logado = this.logado.bind(this)
        this.deslogado = this.deslogado.bind(this)
        this.ajax();
    }
    
    ajax(){
         const xhttp = new XMLHttpRequest();
         xhttp.onload = ()=> {
         var obj = JSON.parse(xhttp.responseText);


         if(obj.logado){
             this.logado(obj.infos)
         }else{
             this.deslogado();
         }
             
         }
         xhttp.open("GET", `${dominioAdress}/admin/sessionInfo.php`);
         xhttp.send();
    }
    
    
    logado(info){
       console.log(info)
    }
    
    deslogado(){
         if(document.getElementById("btnPerfil")){
             var pai = document.getElementById("btnPerfil").closest("DIV")
             pai.innerHTML = `<a class="text-decoration-none" href="/acesso">
                            <button class="btn text-light d-flex justify-content-center align-items-center btn-danger fs-14 btn-sm" >
                            <span><i class="bi bi-people-fill"></i></span>
                            <span class="d-none d-lg-block ms-2">ACESSAR CONTA</span>
                            </button>
                            </a>`
         }
    }
    
}

class BtnMobile{
    constructor(){
        this.btnMobile = this.btnMobile.bind(this)
        
        
        this.btn = document.getElementById("btn-menu")
        this.menu = document.getElementById("lateralMobile")
        this.btn.addEventListener("click", this.btnMobile)
    }
    
    btnMobile(){
        this.btn.classList.toggle("ativo");
        this.menu.classList.toggle("ativo");
    }
}

if(document.getElementById("btn-menu")){
    new BtnMobile();
}

class Caminho{
    constructor(caminho){
        this.caminho = caminho
    }
    
    set(caminho){
        this.caminho = caminho;    
    }
    
    hash(){
        return this.caminho[this.caminho.length - 1]
    }
    
    rotas(){
        return this.caminho
    }
}

var caminho = new Caminho(false);

function evento(item, acao, funcao, valor = false){

    acao = acao.toLowerCase();
    function addEvento(elemento, valor){

        if(valor){
             elemento.addEventListener(acao, function(){ funcao(valor)});
        }else{
             elemento.addEventListener(acao, funcao);
        }
        
         var registro = {};
         registro.item = elemento
         registro.acao = acao
         registro.funcao = funcao
        
         ouvidoresEventos.push(registro);
    }
    
    if(Array.isArray(item)){
        var i = 0;
        while(i < item.length){
            addEvento(item[i],valor)

            i++;
        }
    }else{
        if(item){
           addEvento(item, valor)
        }
        
    }
}

function limpaevento(){
    var i = 0;

    while(i < ouvidoresEventos.length){
        var o = ouvidoresEventos[i]
        o.item.removeEventListener(o.acao, o.funcao);

        i++;
    }
    ouvidoresEventos = [];
}

function evl(item, acao, funcao){
    if(Array.isArray(item)){
        var i = 0;
        while(i < item.length){
            item[i].addEventListener(acao, funcao)
            i++;
        }
    }else{
        if(item){
           item.addEventListener(acao, funcao) 
        }
        
    }
}

class Manutencao{
    constructor(){
        if(document.getElementById("cronometro")){
            var link = document.createElement("LINK")
            link.href = "https://pbutcher.uk/flipdown/css/flipdown/flipdown.css"
            link.setAttribute("rel", "stylesheet");
            document.getElementsByTagName("head")[0].appendChild(link)
            
            var script = document.createElement("SCRIPT")
            script.src = "https://pbutcher.uk/flipdown/js/flipdown/flipdown.js"
             document.getElementsByTagName("head")[0].appendChild(script)
             script.addEventListener("load", this.cronometro.bind(this))
                  
        
        }
    
        
    }
    
    cronometro(){
         // Unix timestamp (in seconds) to count down to
   var divCronometro = document.getElementById('cronometro');
  var data = divCronometro.getAttribute('data-data');
  var horario = divCronometro.getAttribute('data-horario');
  var dateTimeString = data + ' ' + horario;
  var targetTimestamp = new Date(dateTimeString).getTime() / 1000;

  var flipdown = new FlipDown(targetTimestamp, {headings: ["Dias", "Horas", "Minutos", "Segundos"]})

    // Start the countdown
    .start()

    // Do something when the countdown ends
    .ifEnded(() => {
      console.log('The countdown has ended!');
    });

  // Toggle theme
  var interval = setInterval(() => {
      document.body;
    body.classList.toggle('light');
    body.querySelector('#flipdown').classList.toggle('flipdown__theme-dark');
    body.querySelector('#flipdown').classList.toggle('flipdown__theme-light');
  }, 5000);
  

    }
}

function analitcs(){
    if(document.getElementById("googleAnalitcs") && document.getElementById("googleAnalitcs").dataset.code){
        var script = document.createElement("SCRIPT")
        var codigo = document.getElementById("googleAnalitcs").dataset.code
        var url = `https://www.googletagmanager.com/gtag/js?id=${codigo}`
        script.src = url
        document.getElementsByTagName("body")[0].appendChild(script)
        script.onload = ()=>{
             window.dataLayer = window.dataLayer || [];
             function gtag(){dataLayer.push(arguments);}
             gtag('js', new Date());
             gtag('config', codigo);
        }
    }
    
}

class Lgpd{
    constructor(){
        this.box = document.getElementById("lgpd")
        this.delay = this.box.dataset.delay ?  this.box.dataset.delay * 1000 : false;
        
        this.modal =  new bootstrap.Offcanvas('#lgpdPreferencias')
        
        if (document.cookie.indexOf("lgpd=") == -1) {
            if(this.delay){
                 setTimeout(this.show.bind(this), this.delay) 
            }else{
                setTimeout(this.show.bind(this), 5000) 
            }
            
            
           
        }else{
            console.log("ja foi aceito")
        }
         
        evento(this.box.getElementsByClassName("aceitar")[0], "click", this.aceitar.bind(this))
        evento(this.box.getElementsByClassName("gerenciar")[0], "click", this.gerenciar.bind(this))
        
        
   
    }
    
    show(){
        this.box.classList.remove("d-none")
    }
    
    aceitar(){
          this.box.classList.add("d-none")
          document.cookie = "lgpd=aceito; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/";

          
    }
    
    gerenciar(){
        this.modal.show();
    }
}

class Idiomas{
    constructor(){
      
        if(document.getElementById("idiomaAtivo")){
            this.ativo = document.getElementById("idiomaAtivo")
            this.itens = document.getElementsByClassName("btnIdioma")
            evento(Array.from(this.itens), "click", this.muda.bind(this))
            
            
            var dados = document.getElementsByClassName("seletorIdiomas")[0]
            this.original = dados.dataset.original
            this.secundarios = dados.dataset.secundarios.split(",")
            
            
            this.ajusta.bind(this)();

        }
    }
    
    ajusta(){
         var urlCompleta = window.location.href;
         var dominio = window.location.protocol + "//" + window.location.host;
            
            var urlRestante = urlCompleta.replace(dominio, "");
            
            if (urlRestante.charAt(0) === "/") {
                urlRestante = urlRestante.substr(1);
            }
            
        urlRestante = urlRestante.split("/");
 
        if(urlRestante.length > 0){
            if (this.secundarios.indexOf(urlRestante[0]) !== -1) {
             var tag = urlRestante[0];
             
             var btn = false;
             
             var i = 0;
             while(i < this.itens.length){
                
                 if(this.itens[i].dataset.tag == tag){
                     btn = this.itens[i]
                      break;
                 }
                
                 i++;
             }
             

             if(btn){

        
        var bandeiraTopo = this.ativo.getElementsByTagName("IMG")[0]
        var urlTopo = bandeiraTopo.src
        var corTopo = this.ativo.dataset.cor
        var tagTopo = this.ativo.dataset.tag
        
        
        var bandeiraBotao = btn.getElementsByTagName("IMG")[0]
        var urlBotao = bandeiraBotao.src
        var corBotao = btn.dataset.cor
        var tagBotao = btn.dataset.tag
        
        
        
        bandeiraTopo.src = urlBotao
        bandeiraBotao.src = urlTopo
        
        this.ativo.dataset.cor = corBotao
        this.ativo.dataset.tag = tagBotao
        
        btn.dataset.cor = corTopo
        btn.dataset.tag = tagTopo
        
        btn.style.borderLeft = `4px solid ${corTopo}`
        this.ativo.style.borderLeft = `4px solid ${corBotao}`
        
        this.go = tagBotao
        
        
             }
             
             
        }else{
            
        }
            
        }
        
        
        
    }
    
    muda(){
      
        var btn = event.target.classList.contains("btnIdioma") ? event.target : event.target.closest(".btnIdioma")
        
        var bandeiraTopo = this.ativo.getElementsByTagName("IMG")[0]
        var urlTopo = bandeiraTopo.src
        var corTopo = this.ativo.dataset.cor
        var tagTopo = this.ativo.dataset.tag
        
        
        var bandeiraBotao = btn.getElementsByTagName("IMG")[0]
        var urlBotao = bandeiraBotao.src
        var corBotao = btn.dataset.cor
        var tagBotao = btn.dataset.tag
        
        
        
        bandeiraTopo.src = urlBotao
        bandeiraBotao.src = urlTopo
        
        this.ativo.dataset.cor = corBotao
        this.ativo.dataset.tag = tagBotao
        
        btn.dataset.cor = corTopo
        btn.dataset.tag = tagTopo
        
        btn.style.borderLeft = `4px solid ${corTopo}`
        this.ativo.style.borderLeft = `4px solid ${corBotao}`
        
        this.go = tagBotao
        this.traduz.bind(this)()
    }
    
    traduz(){

        if (caminho.caminho && this.secundarios.indexOf(caminho.caminho[0]) !== -1) {
            
            
            var copia = [...caminho.caminho];
            if(this.go == this.original){
                copia.shift()
            }else{
                copia[0] = this.go;
            }

            var completa = copia.join("/")
            var url = `${dominioAdress}/${completa}`
        }else{
             var completa = caminho.caminho ? caminho.caminho.join("/") : ""
          
 
             var url = this.go == this.original ? `${dominioAdress}/${completa}` : `${dominioAdress}/${this.go}/${completa}`
        
        }
        
  
        window.history.pushState(null, null, url);
        //this.translate.bind(this)(url)
    }
    
    translate(r){
        if(this.go){
            var btns = document.getElementById("contentPage").getElementsByClassName("btnGo");
            var i = 0;
            while(i < btns.length){
                if(btns[i].dataset.idioma != this.go){
                    btns[i].dataset.idioma = this.go
                    btns[i].dataset.link = `${this.go}/${btns[i].dataset.link}`
                }
                i++;
            }
        }
    }
}

function sair(){
     const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    window.location.href = `${dominioAdress}/sair`;
  }
  xhttp.open("GET", `${dominioAdress}/admin/desloga.php`);
  xhttp.send();
}

function toggleLateral(){
    
    document.getElementsByTagName("body")[0].classList.toggle("ativo");
    
    document.getElementById("lateral").classList.toggle("completo")
        document.getElementById("corpo").classList.toggle("completo")
        
        if(!document.getElementById("corpo").classList.contains("completo")){
            var card = document.getElementById("lateral").getElementsByClassName("accordion-collapse")
            var i = 0;
            while(i < card.length){
                if(card[i].classList.contains("show")){
                    
                    card[i].parentNode.getElementsByTagName("button")[0].click();
                    break;
                }
                
                i++;
            }
        }
    
}

function ajustmobile(){
    if(document.getElementById("btn-menu") && document.getElementById("btn-menu").classList.contains("ativo")){
        document.getElementById("btn-menu").click();
    }
}

function ajustaModais(){
    if(document.getElementById("barraperfil")){
          document.getElementById("barraperfil").classList.remove("ativo")
    }
  
}

function go(){
    ajustaModais();
    var link = this.dataset.link;
    var url = `${dominioAdress}/${link}`
    window.history.pushState(null, null, url);
    carregarPagina();
    ajustmobile();
}

function goUrl(url){
    var url = `${dominioAdress}/${url}`
    window.history.pushState(null, null, url);
    carregarPagina();
}

function loading(){
   if(document.querySelector('.loader-wrap')){
        var loaderWrap = document.querySelector('.loader-wrap').style.display = "block"
   }

    if(document.getElementById("contentPage")){
            document.getElementById("contentPage").innerHTML = `
    <div class="d-flex justify-content-center h-100">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>`;
        
    }

     
    }
  
var quills = [];  
function richControl(){
     var r = document.getElementsByClassName("richInput")
     quills = [];
     var i = 0;
     
     while(i < r.length){
        
         var quill =  new Rich(r[i]);
         r[i].dataset.num = i
         quills.push(quill.render());
         
         i++;
     }
     

     
    
}

function ajaxPlugin(data, callback){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        try{
        var obj = JSON.parse(this.responseText);
        }catch{
            var obj = false;
            console.log("erro json")
            console.log(this.responseText)
        }
        
        if(obj){
            callback(obj.retorno)
        }

    }
    xhttp.open("POST", `${dominioAdress}/admin/subs.php`);
    xhttp.send(data);
}

class SelectDois{
    constructor(item){
        this.item = item
        ls("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", this.inicia.bind(this));
    }
    
    inicia(){
         $(this.item).select2();
    }
}

class Categorizador{
    constructor(){
        if(document.getElementById("pluginCategorizador")){
            this.novo = this.novo.bind(this)
            this.deleta = this.deleta.bind(this)
            this.salva = this.salva.bind(this)
            this.salvo = this.salvo.bind(this)
            this.lista = this.lista.bind(this)
            
            
            
            
            
            this.categoria = document.getElementById("pluginCategorizador")
           
            
            var data = new FormData();
            data.append("rota", "plugins");
            data.append("alvo", "categorizador");
            data.append("acao", "lista");
            data.append("tipo", this.categoria.dataset.tipo)
            ajaxPlugin(data, this.lista)
            
            
            this.btn = this.categoria.closest(".card").getElementsByClassName("nova")[0]
            this.variavel = this.categoria.closest(".card").getElementsByClassName("variavel")[0]
            evento(this.btn, "click", this.novo)
            
            
        }
        
        if(document.getElementById("pluginTagador")){
            this.tag = document.getElementById("pluginTagador")
        }
        
    }
    
    lista(r){
        var i = 0;
        while(i <r.length){
            var item = r[i]
            var option = new El("OPTION")
            option.t(item.nome)
            option.a({"value": item.id})
            this.categoria.appendChild(option.html())
            i++;
        }
        
        new SelectDois("#pluginCategorizador")
    }
    
    novo(){
        if(!this.btn.classList.contains("ativo")){
            this.btn.classList.remove("btn-primary")
            this.btn.classList.add("ativo")
            
            const divCardBody = new El('div');
            divCardBody.c('card-body pt-0');
            
            const divButtons = new El('div');
            divButtons.c('d-flex justify-content-between');
            
            const buttonCheck = new El('button');
            buttonCheck.c('btn btn-sm btn-contrast rounded-0');
            buttonCheck.evento("click", this.salva)
            const iconCheck = new El('i');
            iconCheck.c('bi bi-check2-square');
            buttonCheck.f(iconCheck.html());
            
            
            const inputField = new El('input');
            inputField.c('form-control rounded-0');
            this.input = inputField.html();
            
            const buttonTrash = new El('button');
            buttonTrash.c('btn btn-sm btn-contrast rounded-0');
            buttonTrash.evento("click", this.deleta)
            
            const iconTrash = new El('i');
            iconTrash.c('bi bi-trash-fill');
            buttonTrash.f(iconTrash.html());
            
            divButtons.f([buttonCheck.html(), this.input, buttonTrash.html()]);
            divCardBody.f(divButtons.html());
            this.body = divCardBody.html();
            this.variavel.appendChild(this.body);

        }
        
        
    }
    
    salva(){
        if(this.input.value){
             var valor = this.input.value
            var existe = false;
            var opcoes = this.categoria.getElementsByTagName("OPTION")
            var i = 0;
            while(i < opcoes.length){
                if(opcoes[i].innerText == valor ){
                    existe = opcoes[i].value;
                    break;
                }
                i++;
            }
            
            if(!existe){
                var data = new FormData();
                data.append("rota", "plugins");
                data.append("alvo", "categorizador");
                data.append("acao", "cadastra");
                data.append("nome", valor)
                data.append("tipo", this.categoria.dataset.tipo)
                ajaxPlugin(data, this.salvo)
            }else{
                this.categoria.value = existe;
            }
            
            
           
           
        }
        this.deleta();
    }
    
    salvo(r){
        var id = r.id
        var nome = r.nome
        if(r.sucesso){
            var option = new El("OPTION")
            option.t(nome)
            option.a({"value": id})
            this.categoria.appendChild(option.html())
        }
        
        this.categoria.value = id;
        
    }
    
    deleta(){
        this.body.remove();
        this.btn.classList.add("btn-primary")
        this.btn.classList.remove("ativo")
    }
}

class Cabecalho{
    constructor(){
        if(document.getElementById("cabecalhoAdmin")){
            this.monta.bind(this)();
        }
    }
    
    monta(){
        this.cabecalho = document.getElementById("cabecalhoAdmin");
        this.btns = this.cabecalho.getElementsByClassName("btn-cabecalho")
        
        var array = [];
        var i = 0;
        while(i < this.btns.length){
            array[i] = JSON.parse(this.btns[i].dataset.info)
            this.btns[i].removeAttribute("data-info")
            this.btns[i].dataset.id = i;
            i++;
        }
        this.array = array;
        
        evento(Array.from(this.btns), "click", this.clicou.bind(this))
    }
    
    clicou(){
        var btn = event.target.classList.contains("btn-cabecalho") ? event.target : event.target.closest(".btn-cabecalho")
        var acao = this.array[parseInt(btn.dataset.id)]
        switch(acao.acao){
            case 'go':
                console.log("aqui vai");
                break;
            case 'function':
                if(acao.foco){
                    this[acao.foco].bind(this)();
                }
                break;
            
        }
    }
    
    exportar(){
        console.log("teste")
    }
    
    renderizar(){
        ajax(false, `${caminho.caminho[1]}_render`, this.renderizado.bind(this));
    }
    
    renderizado(r){
        Swal.fire({
            icon: 'success',
            title: 'Renderizado',
            text: "Os componentes foram rederizados",
            showConfirmButton: false,
            timer: 1500
        })

    }
}

class HashUser{
    constructor(){
        if(!this.cookieValido.bind(this)("usuario")){
            var chave = "";
            var i = 0;
            while(i < 5){
                chave = i == 0 ?  geraId(5) : `${chave}-${geraId(5)}`
                i++;
            }
            this.setCookie.bind(this)("usuario", chave , 365);
        }
        
        
        this.afiliado.bind(this)();
    }

    afiliado(){
        this.url = window.location.href;
        if(this.url.split("#ref=").length == 2 && this.url.split("#ref=")[1].length > 0){
            var ref = this.url.split("#ref=")[1]
            this.setCookie.bind(this)("referencia", ref , 365);
            window.history.pushState(null, null, this.url.split("#ref=")[0]);
        }
    }
    
    
    destruirCookie(nomeCookie) {
    // Define a data de expiração para um momento no passado
    document.cookie = nomeCookie + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    
    setCookie(nome, valor, diasParaExpirar) {
        var dataExpiracao = new Date();
        dataExpiracao.setTime(dataExpiracao.getTime() + (diasParaExpirar * 24 * 60 * 60 * 1000));
        var expiracao = "expires=" + dataExpiracao.toUTCString();
        document.cookie = nome + "=" + valor + "; " + expiracao + "; path=/";
    }
    
    cookieValido(nomeCookie) {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(nomeCookie + "=") === 0) {
            var valorCookie = cookie.substring(nomeCookie.length + 1);
            // Verifica se o cookie não expirou
            if (valorCookie !== "" && !this.cookieExpirado.bind(this)(cookie)) {
                return valorCookie; // O cookie existe e ainda é válido   
            }
        }
    }

    return false; // O cookie não existe ou expirou
    }
    
    cookieExpirado(cookie) {
    var partes = cookie.split("=");
    var nome = partes[0].trim();
    var valor = partes[1].trim();

    if (!valor) {
        return true; // Cookie sem valor (inválido)
    }

    var expiracao = valor.charAt(0) === "{" ? JSON.parse(valor).expires : null;

    if (expiracao) {
        var dataExpiracao = new Date(expiracao);
        return dataExpiracao.getTime() <= Date.now(); // Verifica se o cookie expirou
    }

    return false; // Cookie sem data de expiração definida
}
}
function paraURL(name) {
    // Translitera caracteres acentuados para seus equivalentes não acentuados
    name = name.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Substitui espaços por hífens
    let url = name.replace(/\s+/g, '-');

    // Remove caracteres especiais
    url = url.replace(/[^A-Za-z0-9\-]/g, '');

    // Converte para minúsculas
    url = url.toLowerCase();

    return url;
}

class PreventLink{
    constructor(){
        this.links = [];
    }
    
    render(){
        var a = document.getElementsByTagName("a")
    
    var i = 0;
    while(i < a.length){
        if(!a[i].dataset.memoria){
            a[i].dataset.memoria = true;
            this.links.push(a[i])
            
            if(!a[i].dataset.noprevent){
                a[i].addEventListener("click", this.go.bind(this))
            }
            
        }

        i++;
    }
        
        
    }
    
    go(){
        event.preventDefault();
  
        var a = event.currentTarget
        var link = a.href

        if(link.includes(dominioAdress)){
            var caminho = link.replace(`${dominioAdress}/`, "")
            goUrl(caminho);
        }else{
             window.location.href = link
        }
        

    }
    
   
}

class Replicante{
    constructor(){
        if(document.getElementById("replicanteEstados")){
            this.estados.bind(this)()
        }
    }
    
    estados(){
        var obj = JSON.parse(document.getElementById("replicanteEstados").innerText)
        document.getElementById("replicanteEstados").innerHTML = "";

        var cidades = obj.cidades
        
        var palavraUrl = obj.palavraUrl
        var estadoUrl = obj.estadoUrl
        var artigoEstado = obj.estadoArtigo
        var palavra = obj.palavra
        var estado = obj.estado
        var sigla = obj.estadoUrl.toUpperCase();

        
        var start = "*"
        var i = 0;
        var html = "";
        while(i < cidades.length){
            var cidade = cidades[i]
            
            var cidadeUrl = paraURL(cidade)
            var url = `${dominioAdress}/${palavraUrl}/${estadoUrl}/${cidadeUrl}`
            if(cidade[0] != start){
       
                if(start != "*"){
                    html = `${html}</ul></div></div></div>`
                }
                
                
                start = cidade[0]
                
                
                
                var id = geraId();
          
                html = `${html}
                <div class="accordion-item col-4 p-0 rounded-0">
    <h2 class="accordion-header rounded-0">
      <button class="accordion-button collapsed fs-14" type="button" data-bs-toggle="collapse" data-bs-target="#${id}" aria-expanded="false" aria-controls="${id}">
        ${palavra} ${artigoEstado} ${estado}  em cidades com a letra ${start}
      </button>
    </h2>
    <div id="${id}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
      <div class="accordion-body">
       <ul class="list-group list-group-flush">
                
                
                
                `
            }
                
            html = `${html}<li class="list-group-item"><a href="${url}" class="text-dark text-decoration-none p-0 fs-14 d-flex justify-content-start gap-2"><i class="bi bi-arrow-right-circle"></i> <span>${palavra} em ${cidade}, ${sigla}</span> </a></li>`
                
            
            i++;
         
            
            
            
            
        }
            
            var row = document.createElement("DIV")
            row.classList.add("accordion","row")
            row.id = "accordionExample"

        
         row.innerHTML  = html;
         document.getElementById("replicanteEstados").appendChild(row)
        document.getElementById("replicanteEstados").classList.remove("d-none")
        
    }
}

function carregarPagina() {
    
        loading();
     
         
         var usuario = new HashUser();
         
        
        var link = window.location.href.replace(`${dominioAdress}/`, ""); 
        var link = link.split("#")[0]
    
    
    

       
       
       
  var data = new FormData();
  data.append("p", link)
  data.append("rotas", link)
  data.append("usuario", usuario.cookieValido("usuario"))
  
  if(usuario.cookieValido("referencia")){
      data.append("referencia", usuario.cookieValido("referencia"))
      usuario.destruirCookie("referencia");
  }
  
  
  
  
  
  
  
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        

        var r = JSON.parse(this.responseText)
        caminho.set(r.caminho);
    
        if(r.html){
            document.getElementById("contentPage").innerHTML =  r.html;
            
            new Mascaras();
            
           // new Pago();
            
            if(document.getElementById("btnPerfil")){
                 limpaevento();
                 uploader();
                 tabulacao();
                 condicionais();
                 richControl();
                 
                 if(class_exists("Seo")){
                      new Seo();
                 }
                 
                
                 new CardSalvar();
                 tabelacarrega();
                 new Categorizador();
                 new Cabecalho();
                
           
            }
     
            document.documentElement.scrollTop = 0; // Para navegadores modernos
            seoOnPage();

          
             new Replicante();
             
             preventLinks.render();
  document.body.scrollTop = 0;
        }else{
        
            
             seoOnPage(false, true);
            if(document.getElementById("contentPage")){
                 document.getElementById("contentPage").innerHTML = `
                
                 <div class="mt-5"><h2 class="text-center text-contrast text-uppercase fs-30">Página Não Existe</h2></div>
                 <div class="my-5"><p class="text-contrast text-center">A página que você está acessando não foi encontrada ou não existe. Volte para a Home e encontre o que você procurando.</p></div>
                 <div class="text-center">
                    <button class="btn btn-contrast">Voltar para a Home</button>
                 </div>
                 
                 `;
            }
           
        }
        
        
        idioma.translate(r);
        
        loadscript(r)
        
        memoria.info();
        
         
         
           
        
        

    }
    if(document.querySelector('.loader-wrap')){
         document.querySelector('.loader-wrap').style.display = "none"
    }
   
  };
  xhttp.open("POST", `${dominioAdress}/admin/rotas.php`, true);
  xhttp.send(data);
}

function class_exists(class_name) {
  // Verifica se a classe existe no escopo global
  if (typeof window[class_name] === "function") {
    return true;
  }

  // A classe não existe
  return false;
}

function baixarMatris(r){
    gerarMatris(r.hash);
}

function getulr(traducao) {
    
    
    if(!traducao){
            var urlCompleta = window.location.href;
            var dominio = window.location.protocol + "//" + window.location.host;
            
            var urlRestante = urlCompleta.replace(dominio, "");
            
            if (urlRestante.charAt(0) === "/") {
                urlRestante = urlRestante.substr(1);
            }
            
        urlRestante = urlRestante.split("/"); // Remova 'var' nesta linha
        
    }else{
        urlRestante = traducao;
    }

    

    if (urlRestante[0] === "a") { // Use 'urlRestante' ao invés de 'var urlRestante'
        var tipo = "modulo";
        urlRestante.shift();
    }else if(urlRestante[0] === "m"){
        var tipo = "master";
        urlRestante.shift();
    }else {
        var tipo = "pagina";
        
    }
    
    if (urlRestante.length > 0) {
        // Verifique se o último valor de urlRestante é vazio
        if (urlRestante[urlRestante.length - 1] === '') {
            // Remova o último valor de urlRestante usando o método pop()
            urlRestante.pop();
        }
    }
    
    if(urlRestante.length > 1 && tipo == "modulo"){
        urlRestante = `${capitalize(urlRestante[0])}${capitalize(urlRestante[1])}`
    }else{
        urlRestante = capitalize(urlRestante[0])
    }
    
  
  
    

    urlRestante = urlRestante.split(" ")[0];

    var string = `${tipo}${urlRestante}`;

    return string;
}

function capitalize(str) {
    if(str){
         return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }else{
        return "Home";
    }
   
}

function chama(funcaoJS, ext = true){
   
    
    if(funcaoJS){
         var funcaoJS = funcaoJS.split("-");
    funcaoJS = funcaoJS.join("")
         if (funcaoJS in window && typeof window[funcaoJS] === 'function') {
              const result = eval(`${funcaoJS}()`);
         }else{
             
             if(ext){
                 var trato = funcaoJS.split(/(?=[A-Z])/);
            if(trato.length > 2){
                var novaFuncao = `${trato[0]}${trato[1]}Excecao`
                if (novaFuncao in window && typeof window[novaFuncao] === 'function') {
                    const result = eval(`${novaFuncao}()`);
                }else{
                     console.log("funcao nao existe: ", funcaoJS);
                     console.log("execão também nao existe: ", novaFuncao);
                }
                
                
                
               
            }else{
                console.log("funcao nao existe: ", funcaoJS);
            }
                 
             }else{
                 console.log("funcao nao existe: ", funcaoJS);
             }
            
           

             
         }
    }else{
        home();
    }

}

function geraId(size = 10) {
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let randomId = '';

  for (let i = 0; i < size; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    randomId += characters.charAt(randomIndex);
  }

  return randomId;
}

function loadscript(r, funcao = false){

    
    if(r.js){
    src = `${dominioAdress}/${r.js}`

    var script = document.getElementsByTagName("script")
    var i = 0;
    var have = false;
    
    while(i < script.length){
        if(script[i].src.split("?")[0] == src){
            have = true;
        }
        i++;
    }
    var funcaoJS = !funcao ? getulr(r.translate) : funcao;

    if(!have){
        var script = new El("SCRIPT")
        
        script.a({"src": `${src}?v=${geraId()}`})
        document.getElementsByTagName("head")[0].appendChild(script.html())
        script.html().onload = function(){
            chama(funcaoJS)
        }
    }else{
          chama(funcaoJS)
    }
    
    
   
   
  
    }
    
}

function ls(url, callback = false){
    if(url){
    src = `${url}`

    var script = document.getElementsByTagName("script")
    var i = 0;
    var have = false;
    
    while(i < script.length){
        if(script[i].src.split("?")[0] == src){
            have = true;
        }
        i++;
    }
    
    
    if(!have){
        var script = new El("SCRIPT")
        script.a({"src": `${src}?v=${geraId()}`})
        document.getElementsByTagName("head")[0].appendChild(script.html())
        script.html().onload = function(){
            if(callback){
                callback();
            }
            
        }
    }else{
          if(callback){
                callback();
            }
    }
  
    }
    
}

function ajax(infos, acao, callback = false) {
    
    var data = new FormData();
    data.append("infos", JSON.stringify(infos))
    data.append("acao", acao);
    
    if(document.getElementById("typerDoc")){
         data.append("typerDoc", document.getElementById("typerDoc").dataset.tipo);
    }

     const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (callback) {
                callback(this.responseText)
      
            } else {
                console.log(this.responseText);
            }

        }
        xhttp.open("POST", `${dominioAdress}/admin/ajax.php`);
        xhttp.send(data);
}

class Rich {
    constructor(tag, text = false) {


        text = !text ? "Digite o texto aqui" : text
        this.quill = new Quill(tag, {
            modules: {
               
                toolbar: [
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        header: ["p", 1, 2, 3, 4, 5, 6]
                    }],
                    ['bold', 'italic', 'underline', 'link'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['image', 'video', 'code-block'],
               
                    [{
                        'align': []
                    }],

                    ['clean']

                ],

            },
            
            placeholder: text,
            theme: 'snow' // or 'bubble'
        });
        
        
        this.quill.getModule('toolbar').addHandler('image', this.upaImagem.bind(this));
  


      


    }
    
     upload(arquivo, pasta = false) {
         
         if(document.getElementById("cardSalvar")){
             pasta = document.getElementById("cardSalvar").dataset.modulo
         }else{
             pasta = "imagens"
         }
           
            const formData = new FormData();
            formData.append("file", arquivo);
            formData.append("pasta", pasta)
            
            const xhr = new XMLHttpRequest();
            xhr.open("POST", `${dominioAdress}/admin/processa.php`, true);


    xhr.onreadystatechange = ()=> {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          var obj = JSON.parse(xhr.responseText)

           const imageUrl = `${dominioAdress}/conteudo/uploads/${obj.file_path}`;
            const range = this.quill.getSelection();
            this.quill.insertEmbed(range.index, 'image', imageUrl);
      }
    };

    xhr.send(formData);
  }
  
    
    upaImagem(){
        const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (file) {
            this.upload.bind(this)(file, false);
        }
    });
    }


    render() {
        return this.quill;
    }
}




class Mascaras{
    constructor(){
        ls(`${dominioAdress}/assets/bibliotecas/mascaras/mask.js`, this.mascara.bind(this));
    }
    
    mascara(){
          var entrada = document.getElementsByClassName("entrada");
    var i = 0;

    while (i < entrada.length) {
        if (entrada[i].dataset.mask) {
            switch (entrada[i].dataset.mask) {
                case 'cpf':
                    entrada[i].classList.add("maskCPF");
                    $(".maskCPF").mask('000.000.000-00', { reverse: true });
                    break;
                case 'cnpj':
                    entrada[i].classList.add("maskCNPJ");
                    $(".maskCNPJ").mask('00.000.000/0000-00', { reverse: true });
                    break;
                case 'data':
                    entrada[i].classList.add("maskData");
                    $(".maskData").mask('00/00/0000');
                    break;
                case 'telefone':
                    entrada[i].classList.add("maskTelefone");
                    $(".maskTelefone").mask('(00) 0000-0000');
                    break;
                case 'celular':
                    entrada[i].classList.add("maskCelular");
                    $(".maskCelular").mask('(00) 00000-0000');
                    break;
                case 'telefonemisto':
                    entrada[i].classList.add("maskTelefoneMisto");
                    $(".maskTelefoneMisto").mask('?(00) 0000-0000');
                    break;
                case 'altura':
                    entrada[i].classList.add("maskAltura");
                    $(".maskAltura").mask('0.00');
                    break;
                case 'preco':
                    entrada[i].classList.add("maskPreco");
          
                     $('.maskPreco').mask('000000000000000.00', {reverse: true});
                    break;
                case 'peso':
                    entrada[i].classList.add("maskPeso");
                    $(".maskPeso").mask('##0.00');
                    break;
                case 'horario':
                    entrada[i].classList.add("maskHorario");
                    $(".maskHorario").mask('00:00');
                    break;
                case 'cep':
                    entrada[i].classList.add("maskCEP");
                    $(".maskCEP").mask('00000-000');
                    break;
                case 'porcentagem':
                    entrada[i].classList.add("maskPorcentagem");
                     $('.maskPorcentagem').mask('##0,00%', {reverse: true});

                    break;
                default:
                    break;
            }
        }
        i++;
    }
    }
    
}

function invalido(elemento, valido, mensagem) {
  var pai = elemento.closest("DIV");
  var el;

  if (valido) {
    if (pai.getElementsByClassName("valid-feedback").length === 0) {
      el = document.createElement("DIV");
      el.classList.add("valid-feedback");
      pai.appendChild(el);
    } else {
      el = pai.getElementsByClassName("valid-feedback")[0];
    }
  } else {
    if (pai.getElementsByClassName("invalid-feedback").length === 0) {
      el = document.createElement("DIV");
      el.classList.add("invalid-feedback");
      pai.appendChild(el);
    } else {
      el = pai.getElementsByClassName("invalid-feedback")[0];
    }
  }

  el.innerText = mensagem;

  if (valido) {
    elemento.classList.add("is-valid");
    elemento.classList.remove("is-invalid");
  } else {
    elemento.classList.add("is-invalid");
    elemento.classList.remove("is-valid");

    // Adiciona o event listener para remover a classe "is-invalid" quando houver interação
    elemento.addEventListener("input", function () {
      elemento.classList.remove("is-invalid");
      elemento.removeEventListener("input", arguments.callee);
    });
  }
}

function valida(el, validador) {
  var valor = el.value;
  var valido = false;
  var mensagem = "";

  switch (validador) {
    case "email":
      valido = validarEmail(valor);
      mensagem = "Email inválido";
      break;
    case "telefone":
      valido = validarTelefone(valor);
      mensagem = "Telefone inválido";
      break;
    case "cpf":
      valido = validarCPF(valor);
      mensagem = "CPF inválido";
      break;
    case "cnpj":
      valido = validarCNPJ(valor);
      mensagem = "CNPJ inválido";
      break;
    case "cep":
      valido = validarCEP(valor);
      mensagem = "CEP inválido";
      break;
    default:
      // Tipo de validador desconhecido
      console.error("Tipo de validador desconhecido: " + validador);
      return;
  }

  // Chama a função "invalido" passando o resultado da validação
  if(!valido){
      invalido(el, false, mensagem);
  }
  
  return valido;
  
}

class Mapeador{
    constructor(itens, tipo, maping){
        this.faxina = this.faxina.bind(this)
        this.itens = itens
        this.tipo = tipo
        this.maping = maping

    }
    
    faxina(elements){
   
    elements.forEach(function(element) {
        if (element instanceof HTMLElement) {
            element.parentNode.removeChild(element);
        }
    });

    }
    
    render(){
        var render = document.getElementsByClassName("render")
        var filtro = [];
        var i = 0;
        while(i < render.length){
            if(render[i].dataset.tipo == this.tipo){
                filtro.push(render[i]);
            }
            
            i++;
        }
        
        var i = 0;
        while(i < this.itens.length){
            var item = this.itens[i]
            if(item){
                var html = filtro[i]
                if(html){
                    for(var m in this.maping){
                    html.classList.remove("render")
                  
                    if(html.getElementsByClassName(m).length == 1){
                        if(m == "imagem"){
                            if(item[this.maping[m]]){
                                html.getElementsByClassName(m)[0].innerHTML = "";
                                var div = document.createElement("DIV")
                                div.classList.add("w-100", "h-100")
                                div.style = `background-image: url('${dominioAdress}/conteudo/uploads/${item[this.maping[m]]}');
                                background-size: cover; background-position: center; display: flex;justify-content: center;align-items: center;`
                                html.getElementsByClassName(m)[0].appendChild(div)
                            }
                        }else{
                            html.getElementsByClassName(m)[0].innerHTML = "";
                            html.getElementsByClassName(m)[0].innerHTML = item[this.maping[m]]
                        }
                
                    }
                    
                    
               
                }
                 var sobra = html.getElementsByClassName("bg-carregando")
                    var j =  sobra.length - 1;
                    while(j >= 0){
                        if(sobra[j]){
                           sobra[j].remove();
                        }
                        j--;
                    }
                
                }
                
                
                
                   
                
            }
            
            i++;
        }
        
        var limpa = [];
         var resto = document.getElementsByClassName("render")
         var i =0;
         while(i < resto.length){
            if(resto[i].dataset.tipo == this.tipo){
                limpa.push(resto[i]);
            }
            
            i++;
        }
        this.faxina(limpa)
    }
}

function validarEmail(email) {
  // Utilizando uma expressão regular para validar o formato do email
  var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regexEmail.test(email);
}

function validarTelefone(telefone) {
  // Removendo caracteres especiais e espaços em branco do número de telefone
  var numero = telefone.replace(/[^\d]/g, "");
  // Verificando se o número de telefone possui 10 ou 11 dígitos (considerando DDD)
  return numero.length === 10 || numero.length === 11;
}

function validarCPF(cpf) {
  // Removendo caracteres especiais e espaços em branco do CPF
  var numero = cpf.replace(/[^\d]/g, "");

  // Verificando se o CPF possui 11 dígitos
  if (numero.length !== 11) {
    return false;
  }

  // Verificando se todos os dígitos são iguais (CPF inválido)
  if (/^(\d)\1+$/.test(numero)) {
    return false;
  }

  // Validando os dígitos verificadores do CPF
  var soma = 0;
  var resto;

  // Verificando o primeiro dígito verificador
  for (var i = 1; i <= 9; i++) {
    soma += parseInt(numero[i - 1]) * (11 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) {
    resto = 0;
  }
  if (resto !== parseInt(numero[9])) {
    return false;
  }

  // Verificando o segundo dígito verificador
  soma = 0;
  for (var j = 1; j <= 10; j++) {
    soma += parseInt(numero[j - 1]) * (12 - j);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) {
    resto = 0;
  }
  if (resto !== parseInt(numero[10])) {
    return false;
  }

  return true;
}

function validarCNPJ(cnpj) {
  // Removendo caracteres especiais e espaços em branco do CNPJ
  var numero = cnpj.replace(/[^\d]/g, "");

  // Verificando se o CNPJ possui 14 dígitos
  if (numero.length !== 14) {
    return false;
  }

  // Verificando se todos os dígitos são iguais (CNPJ inválido)
  if (/^(\d)\1+$/.test(numero)) {
    return false;
  }

  // Validando os dígitos verificadores do CNPJ
  var tamanho = numero.length - 2;
  var digitos = numero.substring(tamanho);
  var soma = 0;
  var pos = tamanho - 7;
  for (var i = tamanho; i >= 1; i--) {
    soma += parseInt(numero.charAt(tamanho - i)) * pos--;
    if (pos < 2) {
      pos = 9;
    }
  }
  var resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado !== parseInt(digitos.charAt(0))) {
    return false;
  }

  tamanho += 1;
  digitos = numero.substring(tamanho);
  soma = 0;
  pos = tamanho - 7;
  for (var j = tamanho; j >= 1; j--) {
    soma += parseInt(numero.charAt(tamanho - j)) * pos--;
    if (pos < 2) {
      pos = 9;
    }
  }
  resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if (resultado !== parseInt(digitos.charAt(0))) {
    return false;
  }

  return true;
}

function validarCEP(cep) {
  // Removendo caracteres especiais e espaços em branco do CEP
  var numero = cep.replace(/[^\d]/g, "");

  // Verificando se o CEP possui 8 dígitos
  return numero.length === 8;
}

function deltaToHtml(delta) {
    let htmlContent = "";

    delta.ops.forEach((op) => {
      if (op.insert) {
        if (typeof op.insert === 'string') {
          htmlContent += op.insert;
        } else if (typeof op.insert === 'object') {
          if (op.insert.image) {
            htmlContent += `<img src="${op.insert.image}">`;
          }
          // Add more cases for other embedded elements if needed
        }
      }
    });

    return htmlContent;
  }
  
function pegaformulario(div = false){
    var inputs = !div ? document.getElementsByClassName("entrada") : div.getElementsByClassName("entrada")
    
    var i = 0;
    var infos = {};
    var stop = false;
    var erros = [];
    while(i < inputs.length){
        if(inputs[i].dataset.obrigatorio && !inputs[i].value){
            if(inputs[i].tagName == "DIV"){
                
            }else{
              stop = true;
              var item = {};
              item.nome = inputs[i].name.toLowerCase()
              item.erro = "Campo obrigatório"
              erros.push(item);
              invalido(inputs[i], false, "Esse campo é obrigatório");  
            }
            
            
        }

        if(inputs[i].tagName == "DIV"){
            var quill = quills[inputs[i].dataset.num]
            
           
  
            
            
            //var valor = quill.getContents().ops;
            var valor = quill.root.innerHTML;
            if(valor){
                infos[inputs[i].dataset.name] = valor
            }
            
            
            
        }else{
            infos[inputs[i].name] = inputs[i].value
        }
        

        i++;
    }
    
    
    if(document.getElementById("seoPlugin")){
        var seo = document.getElementById("seoPlugin")
        var keyWord = seo.getElementsByClassName("form-control")[0]
        var titulo = seo.getElementsByClassName("form-control")[1]
        var descricao = seo.getElementsByClassName("form-control")[2]
        
        var seo = {
            seo : keyWord.value,
            titulo : titulo.value,
            descricao : descricao.value
        }
        infos["seo"] = seo
        
    }
    
      
    if(true){
         var i = 0;
        while(i < inputs.length){
        if(inputs[i].dataset.valida){
            var retorno = valida(inputs[i], inputs[i].dataset.valida)
            if(!retorno){
                stop = true;
            }
        }
        i++;
    }
        
    }
   
    
    if(stop){
        console.log(erros)
        Swal.fire({
  icon: 'error',
  title: 'Atenção',
  text: 'Verifique todos os campos, antes de salvar',
  showConfirmButton: false,
  timer: 1500
})
    }else{
        
        return infos;
     
    }
  
}

function condicionais(){
    var c = document.getElementsByClassName("condicional")
    var i = 0;
    while(i < c.length){
        new Condicional(c[i])
        i++;
    }
}

function formatarData(dataString) {
  const meses = [
    'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
    'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
  ];

  const partes = dataString.split('-');
  const dia = parseInt(partes[2]);
  const mes = parseInt(partes[1]);
  const ano = parseInt(partes[0]);

  const dataFormatada = `${dia} de ${meses[mes - 1]} de ${ano}`;

  return dataFormatada;
}

function verificarDataHora(dataHoraString) {
  const dataHora = new Date(dataHoraString);
  const agora = new Date();

  if (dataHora > agora) {
    return false;
  } else {
    return true;
  }
}

function getUploaders(num = false){
    var uploader = document.getElementsByClassName("uploader")
    
    if(num){
        var itens = [uploader[num]];
    }else{
        var itens = uploader
    }
    
    var i = 0;
    
    var imgs = [];
    
    while(i < itens.length){
        var item = itens[i];
        var pack = [];
        var imagens = item.getElementsByClassName("itemPrevia")
        var y = 0;
        while(y < imagens.length){
            if(imagens[y].dataset.url){
                pack.push(imagens[y].dataset.url);
            }
            y++;
        }
        
        if(pack.length > 0){
            var bloco = {
                id : item.getElementsByTagName("input")[0].id,
                srcs : pack
            }
            imgs.push(bloco)
        }
         
        
        i++;
    }
    
    if(imgs.length > 0){
        return  imgs;
    }else{
        return false;
    }
    
    
    
}

function cadastrosucesso(r){

    var obj = JSON.parse(r)
    if(obj.acao && obj.sucesso){
      
        if(obj.sucesso.callBack){
             var url = "";
            url += obj.sucesso.callBack
            
            if(obj.sucesso.id){
            url += obj.sucesso.id
            }
            
               

            goUrl(url);
            
        } 
        
         Swal.fire({
  icon: 'success',
  title: 'Sucesso',
  text: 'Item salvo com sucesso',
  showConfirmButton: false,
  timer: 1500
})
        
        
        
     
        
        
    }
}

function deletar(r, cb = false){
    
    var btn = event.target
    Swal.fire({
        icon: 'question',
  title: 'Você tem certeza?',
  showCancelButton: true,
  confirmButtonText: 'Apagar',
  cancelButtonText: 'Cancelar',
}).then((result) => {

  if (result.isConfirmed) {
    
         var info = {};
         info.hash = r.hash
         ajax(info, r.data.chave, cb)
          const row = btn.closest("tr");
          if(tabelaglobal){
              tabelaglobal.referencia().row(row).remove().draw();
          }
          
      
    Swal.fire('Deletado!', '', 'success')
  } 
})
}

function editar(r){
    if(r.data.tipo == "url"){
        goUrl(`${r.data.destino}${r.hash}`)
    }
}

function seoOnPage(obj = false, erro = false){
    if(erro){
 
        var titulo  = `404 - ${document.getElementsByTagName("title")[0].dataset.start}`
        var descricao = "Página Não Encontrada"
     
    }else{
         var titulo = false;
        var descricao = false;
        var palavras = false;
    
        titulo = obj.titulo ? obj.titulo : titulo;
        descricao = obj.descricao ? obj.descricao : descricao
        palavras = obj.palavras ? obj.palavras : palavras
    
    
    
        if(!obj && document.getElementById("setSeo")){
            titulo = document.getElementById("setSeo").dataset.titulo ? document.getElementById("setSeo").dataset.titulo : titulo
            descricao = document.getElementById("setSeo").dataset.descricao ? document.getElementById("setSeo").dataset.descricao : descricao
            palavras = document.getElementById("setSeo").dataset.palavras ? document.getElementById("setSeo").dataset.palavras : palavras
            document.getElementById("setSeo").remove();
        }
        
        
        if(!titulo && document.getElementById("contentPage").getElementsByTagName("h1").length == 1){
            titulo = document.getElementById("contentPage").getElementsByTagName("h1")[0].innerHTML ? document.getElementById("contentPage").getElementsByTagName("h1")[0].innerHTML : titulo
          
            if(document.getElementById("contentPage").getElementsByTagName("p").length > 0){
                 descricao = document.getElementById("contentPage").getElementsByTagName("p")[0].innerHTML ? document.getElementById("contentPage").getElementsByTagName("p")[0].innerHTML  : descricao
            }
            
        }
        
        
        
        
        
    
   
    
    }
        


    document.getElementsByTagName("title")[0].innerText = titulo ? titulo : document.getElementsByTagName("title")[0].dataset.start,
    document.querySelector('meta[name="description"]').content = descricao ? descricao : document.querySelector('meta[name="description"]').dataset.start

}

class Lazys{
    constructor(){
        var text = JSON.parse(document.getElementById("scriptsLazy").innerText)
        document.getElementById("scriptsLazy").remove();
        this.contador = 0;
        this.item = this.item.bind(this)
        
        var links = [];
        var i = 0;
        while(i < text.classes.length){
            var classe = text.classes[i]

            links.push(`${dominioAdress}/assets/js/classes/${classe}`)
       
            i++;
        }
        
        if(text.customCSS){
            var i = 0;
            while(i < text.customCSS.length){
                var classe = text.customCSS[i]
                links.push(`${dominioAdress}/conteudo/assets/css/${classe}`)
                i++;
            }
        }
        
        if(text.customJS){
            var i = 0;
            while(i < text.customJS.length){
                var classe = text.customJS[i]
                links.push(`${dominioAdress}/conteudo/assets/js/${classe}`)
           
                i++;
            }
        }
        

        var i = 0;
        while(i < text.plugins.length){
            var plugin = text.plugins[i]
            
            links.push(`${dominioAdress}/includes/plugins/${plugin}/${plugin}.js`)
            
            i++;
            
        }
       
        
        var i = 0;
        while(i < text.extra.length){
            var extra = text.extra[i].url
            
            var url = extra.startsWith('https://') ? extra : `${dominioAdress}/${extra}`
            links.push(url)
            
            i++;
        }
        
        
        
        
        if(text.pwa){
            links.push(`${dominioAdress}/assets/js/pwa.js`); 
        }
        
        
        this.total = links.length
        var i = 0;
        while(i < links.length){
            
            this.item(links[i])
            i++;
        }
    }
    
    
    item(url){
        if (url.endsWith('.css')) {
            var elemento = document.createElement('link');
            elemento.rel = 'stylesheet';
            elemento.type = 'text/css';
            elemento.href = url;
            elemento.as = 'style';
        }else{
            var url = `${url}?v=${geraId()}`
            var elemento = document.createElement('script');
            elemento.type = 'text/javascript';
            elemento.src = url;
            elemento.async = true; 
            elemento.defer = true;
        }
        
        document.head.appendChild(elemento);
        elemento.addEventListener("load", ()=>{
            this.contador++;

            if(this.contador == this.total){
                this.start.bind(this)()
            }
        })
    }
    
    start(){
        evl(document.getElementById("btnMenu"), "click", toggleLateral)
        

        evl(document.getElementById("btnChat"), "click", function(){document.getElementById("barraChat").classList.toggle("ativo")})
        evl(document.getElementById("sair"), "click", sair)
        evl(document.getElementById("modoEscuro"), "click", function(){ document.getElementsByTagName("body")[0].classList.toggle("light"); })

        
        document.getElementById("paginaManutencao") ? new Manutencao() : carregarPagina();
        document.getElementById("lgpd") ? new Lgpd() : false;

   
    new Sessao();
    analitcs();
    idioma = new Idiomas();
    }
}

class ControleMemoria{
    constructor(){
        
    }
    
    
    info(){
        

        setTimeout(()=>{
            var memoryUsage = window.performance.memory;
            if (memoryUsage) {
  console.log("Uso de memória:", memoryUsage.usedJSHeapSize / (1024 * 1024), "MB");
  console.log("Limite de memória:", memoryUsage.jsHeapSizeLimit / (1024 * 1024), "MB");
}
        }, 1000)

    }
}

class Estrutura{
    constructor(){
        if(document.getElementById("dataRender")){
            
            this.paginacao = 0; 
            this.dataRender = document.getElementById("dataRender");
            
            this.tipo = this.dataRender.dataset.tipo
            this.isItem = this.dataRender.dataset.item
            this.url = this.dataRender.dataset.url
            this.modulo = this.dataRender.dataset.modulo
            
            
            this.config = {
                tipo: this.tipo,
                isItem: this.isItem,
                url: this.url,
                modulo: this.modulo
            }
            
            this.dataRender.remove();

        }
    }
    
    
    page(num){
        this.paginacao = this.paginacao + num
    }
    
    ajax(callback = false){
        var data = new FormData();
        data.append("tipo", this.tipo ? this.tipo : false)
        data.append("isItem", this.isItem ? this.isItem : false)
        data.append("url", this.url ? this.url : false)
        data.append("modulo", this.modulo ? this.modulo : false)
        data.append("paginacao", this.paginacao ? this.paginacao : 0)
        
        const xhttp = new XMLHttpRequest();
        xhttp.onload = ()=> {
           
            if(callback){

                var obj = JSON.parse(xhttp.responseText)
                if(obj.erro){
                    callback.erro(obj.resposta)
                }
            
    
                if(obj.sucesso){
                 
                     callback.processa(obj.resposta)
                     
                     if(!this.isItem){
                         var tamanho = obj.resposta.length
                         if(tamanho == 10){
                         this.page(tamanho)
                        
                         this.ajax(callback)
                         }
                        
                     }
                     
                }
                
                
            }else{
                console.log(this.responseText)
            }
        }
        xhttp.open("POST", `${dominioAdress}/admin/estrutura.php`);
        xhttp.send(data);  
    }
}

/*

window.addEventListener('beforeunload', function (event) {

  // Lógica que você deseja executar antes de descarregar a página
  var confirmationMessage = 'Tem certeza que deseja sair?';

  // (Padrão) Alguns navegadores exigem que você atribua o retorno à propriedade event.returnValue
  event.returnValue = confirmationMessage;
  return confirmationMessage;
});

*/

class BtnComprar{
    constructor(infos = false){
        console.log(infos)
  
        this.ajax = this.ajax.bind(this)
        this.infos = infos
              
        this.id = infos.id
        this.preco = this.infos.preco
        this.total = this.preco
        this.target = geraId();
        this.quantidadeSelecionado = 1;

        this.estoque = this.infos.estoque ? this.infos.estoqueQuantidade : 999;
        
        if(document.getElementById("containerBtnComprar")){
                    this.um = this.header.bind(this)();
                    this.dois = this.frete.bind(this)();
                    this.tres = this.quantidade.bind(this)();
                    this.quatro = this.rodape.bind(this)();
                    this.html.bind(this)();
             
        }
    }
    
 
    
    html(){
       
        var card = document.createElement("DIV")
        card.classList.add("card","rounded-0", "cardBtnComprar")
        
        // Header de Preço
        card.appendChild(this.um)
        
        // Endereco
        if(this.infos.fisico){
            card.appendChild(this.dois)
        }
        
        //Quantidade
        if(this.estoque > 1 && !this.infos.vendaUnica){
            card.appendChild(this.tres)
        }
        
        
        // Btns de Compra
        card.appendChild(this.quatro)
        document.getElementById("containerBtnComprar").appendChild(card)
    }
    
    header() {
  // Cria o elemento de card-header com as classes "card-header", "d-flex", "justify-content-between" e "align-items-center"
  var cardHeader = document.createElement("DIV");
  cardHeader.classList.add("card-header", "d-flex", "justify-content-between", "align-items-center");

  // Cria o primeiro contêiner
  var container1 = document.createElement("DIV");

  // Cria o título com a classe "fs-20" e define o texto
  var title = document.createElement("H2");
  title.classList.add("fs-20", "m-0");
  title.innerHTML = `<span class="fs-12 me-2">R$</span><span>${this.infos.preco}</span>`;

  // Cria o subtítulo com a classe "fs-14" e define o texto
  var subtitle = document.createElement("DIV");
  subtitle.classList.add("fs-14");
  
  var parcela = (this.infos.preco / 10).toFixed(2);
  subtitle.innerText = `10 x de R$ ${parcela}`;

  // Adiciona o título e o subtítulo ao primeiro contêiner
  container1.appendChild(title);
  container1.appendChild(subtitle);

  // Cria o segundo contêiner (para dispositivos menores)
  var container2 = document.createElement("DIV");
  container2.classList.add("d-block", "d-xl-none");

  // Cria o botão com as classes e atributos necessários para controlar o colapso
  var button = document.createElement("BUTTON");
  button.classList.add("btn", "text-primary", "text-decoration-underline");
  button.setAttribute("type", "button");
  button.setAttribute("data-bs-toggle", "collapse");
  button.setAttribute("data-bs-target", `#${this.target}`);
  button.setAttribute("aria-expanded", "false");
  button.setAttribute("aria-controls",  this.target);
  button.innerText = "Ver Frete";

  // Adiciona o botão ao segundo contêiner
  if(this.infos.fisico){
      container2.appendChild(button);
  }
  

  // Adiciona os contêineres ao elemento de card-header
  cardHeader.appendChild(container1);
  cardHeader.appendChild(container2);

  // Adiciona o elemento de card-header ao documento (ou ao local desejado)
  return cardHeader;
}

    frete() {
  // Cria o elemento de colapso com a classe "collapse d-xl-block" e o ID "collapseExample"
  var collapse = document.createElement("DIV");
  collapse.classList.add("collapse", "d-xl-block");
  collapse.id =   this.target;

  // Cria o elemento de card-body
  var cardBody = document.createElement("DIV");
  cardBody.classList.add("card-body");

  // Cria o botão com as classes "btn", "text-primary", "text-decoration-underline", "p-0", e "mb-1"
  var button = document.createElement("BUTTON");
  button.classList.add("btn", "text-primary", "text-decoration-underline", "p-0", "mb-1");
  button.innerText = "37701143";

  // Cria um elemento <div>
  var div = document.createElement("DIV");

  // Cria os parágrafos com classes "mb-1"
  var paragraph1 = document.createElement("P");
  paragraph1.classList.add("mb-1");
  paragraph1.innerText = "Rua Juscelino Barbosa";

  var paragraph2 = document.createElement("P");
  paragraph2.classList.add("mb-1");
  paragraph2.innerText = "Poços de Caldas, MG";

  // Cria um parágrafo com classes "m-0" e "fw-700"
  var freightParagraph = document.createElement("P");
  freightParagraph.classList.add("m-0", "fw-700");
  freightParagraph.innerText = "Frete: R$29,80";

  // Adiciona os elementos criados ao card-body
  div.appendChild(paragraph1);
  div.appendChild(paragraph2);
  cardBody.appendChild(button);
  cardBody.appendChild(div);
  cardBody.appendChild(freightParagraph);

  // Adiciona o card-body ao elemento de colapso
  collapse.appendChild(cardBody);

  // Adiciona o elemento de colapso ao documento (ou ao local desejado)
  return collapse;
}

    quantizou2(){
        var valor = event.target.value

        if(valor == "0"){
            valor = 1;
        }
        
        if(valor > this.estoque){
            valor = this.estoque
            alert("O número máximo para esse produto é de "+this.estoque)
        }
        
        event.target.value = valor
        this.total = parseInt(valor) * this.preco
        this.totalizador.innerHTML = `Total: ${parseFloat(this.total).toFixed(2)}`;
        this.quantidadeSelecionado =  valor
        
        if(!valor){
            this.total = 0
            this.totalizador.innerHTML = `Defina uma quantidade`;
            this.quantidadeSelecionado =  0
        }
        
        
        
    }
    
    testeInput(){
        if(!event.target.value){
            event.target.value = 1
            var valor = 1;
            
            this.total = parseInt(valor) * this.preco
            this.totalizador.innerHTML = `Total: ${parseFloat(this.total).toFixed(2)}`;
            this.quantidadeSelecionado =  valor

        }
    }
    
    quantizou(){
        var valor = event.target.value;
        var pai = event.target.parentNode;
        if(valor == "+"){
            var input = document.createElement("INPUT")
            input.classList.add("form-control")
            input.type = "number"
            input.min = 1
            input.value = 1
            input.max = this.estoque
            pai.innerHTML = "";
            evento(input, "input", this.quantizou2.bind(this))
            evento(input, "blur", this.testeInput.bind(this))
             this.total = this.preco
             this.quantidadeSelecionado = 1
             this.totalizador.innerHTML = `Total: ${parseFloat(this.total).toFixed(2)}`;
            
            
             pai.appendChild(input)
             input.focus();
        }else{
            this.total = parseInt(valor) * this.preco
            this.totalizador.innerHTML = `Total: ${parseFloat(this.total).toFixed(2)}`;
            this.quantidadeSelecionado =  valor
        }
    }

    quantidade() {
        var cardBody = document.createElement("DIV");
        cardBody.classList.add("card-body", "border-top");
        
        var flexContainer = document.createElement("DIV");
        flexContainer.classList.add("d-flex", "justify-content-between", "align-items-center", "gap-2");

  
  var leftContainer = document.createElement("DIV");

  // Cria um label com as classes "form-label" e "d-none d-xl-block" e define o texto
  var label = document.createElement("LABEL");
  label.classList.add("form-label", "d-none", "d-xl-block", "mb-1");
  label.innerText = "Quantidade";

  // Cria um elemento <div> para exibir o "Total"
  var totalDiv = document.createElement("DIV");
  var totalSpan = document.createElement("SPAN");
  totalSpan.classList.add("fw-700");
  totalSpan.innerText = `Total: ${this.total}`;
  this.totalizador = totalSpan
    
  totalDiv.appendChild(totalSpan);

  leftContainer.appendChild(label);
  leftContainer.appendChild(totalDiv);

  // Cria um subcontainer para o lado direito
  var rightContainer = document.createElement("DIV");

  // Cria um elemento <select> com a classe "form-select"
  var select = document.createElement("SELECT");
  select.classList.add("form-select");
  evento(select, "change", this.quantizou.bind(this))
    
  var valores =  this.estoque > 5 ? 5 : this.estoque

  var i = 0;
  while(i <  valores){
       var option = document.createElement("OPTION");
       option.innerText = i + 1
       option.value = i + 1
       select.appendChild(option)
       i++;
  }
  

    if(this.estoque > 5){
       var option = document.createElement("OPTION");
       option.innerText = "+"
       option.value = "+"
       select.appendChild(option)
    }
 



  rightContainer.appendChild(select);

  // Adiciona os subcontainers ao contêiner flexível
  flexContainer.appendChild(leftContainer);
  flexContainer.appendChild(rightContainer);

  // Adiciona o contêiner flexível ao elemento de cardBody
  cardBody.appendChild(flexContainer);


  return cardBody;
}

    rodape(){
        var footer = document.createElement("DIV")
        footer.classList.add("card-footer")
        
        var row = document.createElement("DIV")
        row.classList.add("row", "m-0")
        

        if(this.estoque > 0){
            var esquerda = document.createElement("DIV")
        esquerda.classList.add("col-6","col-xl-12")
        
        var direita = document.createElement("DIV")
        direita.classList.add("col-6","col-xl-12", "mt-0","mt-xl-2")
        
        
        var carrinho = document.createElement("BUTTON")
        carrinho.classList.add("btn","btn-primary","w-100")
        
        var span1 = document.createElement("SPAN")
        span1.classList.add("d-none","d-xl-inline")
        span1.innerText = "Adicionar ao "
        
        var span2 = document.createElement("SPAN")
        span2.innerText = "Carrinho"
        
        carrinho.appendChild(span1)
        carrinho.appendChild(span2)
        evento(carrinho, "click", this.clickCarrinho.bind(this))
        
        
     
        

        var comprar = document.createElement("BUTTON")
        comprar.classList.add("btn","btn-dark","w-100")
        comprar.innerText = "Comprar"
         evento(comprar, "click", this.clickComprar.bind(this))
        
        esquerda.appendChild(comprar)
        direita.appendChild(carrinho)
        row.appendChild(esquerda)
        row.appendChild(direita)
        }
        else{
            var col = document.createElement("DIV")
            col.classList.add("col-12")
            var btn = document.createElement("DIV")
            btn.classList.add("bg-danger", "w-100", "text-center", "p-2", "text-light")
            btn.innerText = "ITEM INDISPONÍVEL"
            col.appendChild(btn)
            row.appendChild(col)
        }
        
        
        
        
        
        footer.appendChild(row)
        
       return footer
    }
    
    
    comprado(){
        if(!this.infos.zeraCarrinho){
            goUrl("carrinho");
        }else{
            goUrl("pagamento");
        }
    }
    
    ajax(data, cb){
        const xhttp = new XMLHttpRequest();
  xhttp.onload = ()=> {
      if(cb){
          cb(xhttp.responseText)
      }else{
          console.log(xhttp.responseText)
      }
   
  }
  xhttp.open("POST", `${dominioAdress}/conteudo/modulos/ecommerce/admins/carrinho.php`);
  xhttp.send(data);
    }
    
    
    pegaDemanda(){
       var data = new FormData();
    data.append("acao", "adiciona")
        data.append("produto", parseInt(this.id))
        data.append("quantidade", parseInt(this.quantidadeSelecionado)) 
        return data;
    }
    
    clickComprar(){
        var data =  this.pegaDemanda.bind(this)()
        this.ajax(data, this.comprado.bind(this))
    }
    
    clickCarrinho(){
        var data = this.pegaDemanda.bind(this)()
        
        
  
        
    }
}

var idioma;
var preventLinks;
var memoria;
var tabelaglobal = false;

class Desligado{
    constructor(){
        if(document.getElementById("corpo")){
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show my-2';
            alertDiv.setAttribute('role', 'alert');
            
            const strongElement = document.createElement('strong');
            strongElement.textContent = 'Você está sem internet !';
            
            const messageText = document.createTextNode(' Algumas funcionalidades e conteúdos ficaram limitados enquanto você estiver offline ');
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'btn-close';
            closeButton.setAttribute('data-bs-dismiss', 'alert');
            closeButton.setAttribute('aria-label', 'Close');
            

            alertDiv.appendChild(strongElement);
            alertDiv.appendChild(messageText);
            alertDiv.appendChild(closeButton);
            
            var container = document.createElement("DIV")
            container.classList.add("container")
            container.appendChild(alertDiv)
            
            const firstChild = document.getElementById("corpo").firstElementChild;
            document.getElementById("corpo").insertBefore(container, firstChild);
        }
     
    }
}

function handleOnlineStatusChange() {
  if (navigator.onLine) {
    online();
  } else {
    new Desligado();
  }
}

function online(){
    preventLinks =  new PreventLink();
    preventLinks.render();
    memoria = new ControleMemoria();
    new Lazys(); 
}

window.onload = ()=>{
    if(navigator.onLine){
       online(); 
    }else{
        new Desligado();
    }
}

window.addEventListener('online', handleOnlineStatusChange);
window.addEventListener('offline', handleOnlineStatusChange);





