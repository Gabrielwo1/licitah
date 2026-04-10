class CanvaCarrinho{
    constructor(pai){
        

        if(!document.getElementById("ocCart")){
            return;
        }
        
         if(!dataModule("9eb3f68b1df55837553be41f5ffe5b20")){
            return;
        }
        

        this.canvas = document.getElementById("ocCart")
        this.body = this.canvas.getElementsByClassName("cards-cart")[0];
        
        
        const canva = document.querySelector('[data-nown-canvas="ocCart"]');
        this.btn = canva
        if(canva){
             this.nownc = new nownCanvas(canva.getAttribute('data-nown-canvas'), {
                dirDesktop : canva.dataset.desktop,
                dirMobile : canva.dataset.mobile,
                backdropBlur : (canva.getAttribute('data-nown-canvas') == 'teste' ? false : true),
                persist:  (canva.getAttribute('data-eterno') == 'true' ? true : false),
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
        }else{
            return;
        }
        
       
        this.request = new Request(`conteudo/modulos/pagamento/admins/produto.php`)
        
        this.request.addData(
            {"acao": "carrinho",
             "produto": "all",    
            }
            );
        this.request.send().then((r)=>{
            this.monta.bind(this)(r)
        })

    }
    
    update(){



        
          this.request.addData(
            {"acao": "carrinho",
             "produto": "all",    
            }
            );
        this.request.send().then((r)=>{
            this.monta.bind(this)(r)
        })
    }
    
    toPrice(preco, add = false){
        let floatValue = parseFloat(preco);
        let formattedFloat = floatValue.toFixed(2); 
        if(add){
    
          this.total += floatValue;  
        }
        
        return `R$ ${formattedFloat}`
    }
    
    go(){
        this.nownc.hide();
        setTimeout(()=>{
            goUrl("carrinho")
        }, 200)
    }
    
    monta(r){
     
        if(!r.carrinho || r.lista.length == 0){
            this.btn.classList.add("d-none")
            return;
        }else{
            this.btn.classList.remove("d-none")
        }
        
      
        this.canvas.getElementsByClassName("btnDoCarrinho")[0].addEventListener("click", this.go.bind(this));
        
        this.body.innerHTML = ""
        var lista = r.lista;
        var i = 0;
        this.total = 0;
        while(i < lista.length){
            var item = lista[i]
            this.itemCard.bind(this)(item)

            i++;
        }
        
        this.canvas.getElementsByClassName("totalCarrinho")[0].innerText = this.toPrice(this.total);
        
        this.btnControl(lista.length);

    }
    
    btnControl(num){
        if(this.btn){
            if(num == 0){
                this.btn.classList.add("d-none")
            }else{
                 this.btn.classList.remove("d-none")
                this.btn.getElementsByTagName("span")[0].innerText = num
            }
        }
    }
    
    itemCard(item){
     // console.log(item)
    const cardCart = document.createElement('div');
    cardCart.className = 'card-cart';

    const thumb = document.createElement('div');
    thumb.className = 'thumb';
    const img = document.createElement('img');
    
    var imagem = trataImagem(item.infos.foto, "mini");
    if(imagem){
         img.src = imagem;
        thumb.appendChild(img);
    }else{
        var light = document.createElement("IMG")
        light.src = start.estrutura.mode.light.logo
        light.style = "max-width: 50px; max-height: 50px";
        light.classList.add("onlyLight")
            
        var dark = document.createElement("IMG")
        dark.src = start.estrutura.mode.dark.logo
        dark.style = "max-width: 50px; max-height: 50px";
        dark.classList.add("onlyDark")
        
        thumb.classList.add("d-flex", "justify-content-center", "align-items-center")
        thumb.appendChild(light);
        thumb.appendChild(dark);
    }
   


    const info = document.createElement('div');
    info.className = 'info w-100';

    const h4 = document.createElement('h4');
    if (item.quantidade > 1) {
         
        const spanQtd = document.createElement('span');
        spanQtd.className = 'qtd';
        spanQtd.textContent = item.quantidade;
        h4.appendChild(spanQtd);
    }
    const spanProduto = document.createElement('span');
    spanProduto.className = 'produto';
    spanProduto.textContent = item.infos.nome;
    h4.appendChild(spanProduto);
    info.appendChild(h4);

    const precoDiv = document.createElement('div');
    precoDiv.className = 'preco';
    const spanUni = document.createElement('span');
    spanUni.className = 'uni';
    spanUni.textContent = `${item.quantidade} × ${this.toPrice(item.produto.preco)}`;
    precoDiv.appendChild(spanUni);

    const spanSub = document.createElement('span');
    spanSub.className = 'sub';
    
    var calc = item.quantidade * parseFloat(item.produto.preco);
    spanSub.textContent = this.toPrice(calc, true);
    precoDiv.appendChild(spanSub);
    info.appendChild(precoDiv);

    cardCart.appendChild(thumb);
    cardCart.appendChild(info);

    const link = document.createElement('a');
    link.href = '#';
    cardCart.appendChild(link);

   
    this.body.appendChild(cardCart);
        
    }
}

class CanvaChat{
    constructor(pai){
        
        this.me = autenticado();
        this.noRead = 0;
        
        if(!document.getElementById("ocChat")){
            return;
        }
        
          this.canvas = document.getElementById("ocChat")
          this.body = this.canvas.getElementsByTagName("main")[0];
          this.body.classList.add("list")
          this.init.bind(this)();

        
        
        const canva = document.querySelector('[data-nown-canvas="ocChat"]');

        if(canva){
                    this.btn = canva;
                    canva.addEventListener("click", ()=>{
            this.nownc.show();
        })
            
            
             this.nownc = new nownCanvas(canva.getAttribute('data-nown-canvas'), {
                dirDesktop : canva.dataset.desktop,
                dirMobile : canva.dataset.mobile,
                backdropBlur : (canva.getAttribute('data-nown-canvas') == 'teste' ? false : true),
                persist:  (canva.getAttribute('data-eterno') == 'true' ? true : false),
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });

            
        }else{
            return;
        }
        
       
    }
    
    
    go(){
        this.nownc.hide();
        document.body.classList.remove("ativo")
    }
    
     dataControler(data) {

    
    // Extrai a parte da data
    let currentDate = new Date();
    let dataFormatada = new Date(data.split(" ")[0]);

    // Calcula a diferença de dias entre a data e hoje
    let diffTime = currentDate - dataFormatada;
    let diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));  // Diferença em dias

    let dataTexto;

    // Verifica se a data é "Hoje", "Ontem", nos últimos 7 dias, ou mais antiga
    if (diffDays === 0) {
        var d = data.split(" ")[1].split(":");
        dataTexto = `${d[0]}:${d[1]}`
    } else if (diffDays === 1) {
        dataTexto = "Ontem";
    } else if (diffDays < 7) {
        // Obtém o nome do dia da semana (Ex: "Segunda-feira", "Terça-feira", etc.)
        let diasSemana = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
        dataTexto = diasSemana[dataFormatada.getDay() + 1];
    } else {
        // Formato "DD/MM/YYYY" para datas mais antigas
        dataTexto = dataFormatada.toLocaleDateString("pt-BR");
    }

   
    
    return dataTexto;
}
    
    box(conversa, tipo, chat){
        
        let count = "";
        var nl = parseInt(chat.nl[this.me].nl || 0);
        if(nl > 0){
            this.noRead++;
            count = `<div class="count">
                        ${nl}
                    </div>`;
        }
        
        let data = this.dataControler(chat.lm);
        
        var ultima = chat.l;
        let last = "";
        switch(parseInt(ultima.t)){
            case 1:
                last = ultima.txt;
                break;
        }

        
        let stamp = this.tostemp(chat.lm);
        
        
        let img = trataImagem(conversa.f, "mini");
        if(!img){
           img = "https://st3.depositphotos.com/9998432/13335/v/450/depositphotos_133352156-stock-illustration-default-placeholder-profile-icon.jpg";
        }
  
        
        var div = document.createElement("DIV")
        div.classList.add("card-chat","new","online")
        div.innerHTML = `
         <div class="thumb">
                <img src="${img}">
                <span class="status"></span>
            </div>
            <div class="body">
                <div class="message">
                    <a href="${dominio}/chat/${chat.u}" class="stretched-link text-decoration-none"><h5 class="nome">${conversa.d}</h5></a>
                    <p>${last}</p>
                </div>
                <div class="meta">
                    <div class="quando stamp" data-timestamp="${stamp}">
                        ${data}
                    </div>
                    ${count}
                </div>
            </div>
        
        `
        div.getElementsByTagName("a")[0].addEventListener("click", this.go.bind(this));
        div.getElementsByTagName("a")[0].addEventListener("click", preventLink);
        return div;
        
  
    }
    
    tostemp(dateTimeStr) {
    // Substitui o espaço entre a data e a hora por 'T' para o formato ISO 8601
    const isoFormat = dateTimeStr.replace(" ", "T");
    
    // Converte para timestamp
    const timestamp = Date.parse(isoFormat);
    
    // Retorna o valor do timestamp (em milissegundos)
    return timestamp / 1000; // Converte para segundos, se necessário
} 
   
     init(){
         return;
        let request = new Request(`${dominio}/conteudo/modulos/chat/admins/api.php`);
        request.addData({acao: "init"})
        request.send().then((r)=>{
            var listas = r.listas
            
            this.contatos = {};
            this.conversas = {};
            this.grupos = {};
            
            
            var i = 0;
            while(i < listas.amigos.length){
                let amigo = listas.amigos[i]
                this.contatos[amigo.i] = amigo;
                i++;
            }
            
           if(listas.grupos.length > 0){
               var i = 0;
               
               while(i < listas.grupos.length){
                   this.grupos[`${listas.grupos[i].i}`] =  listas.grupos[i]
                   i++;
               }
           }


            if(listas.chats.length > 0){
                var i = 0;
                let fragmento = document.createDocumentFragment();
                while(i < listas.chats.length){
                    var chat = listas.chats[i]
                        

                        let id;
                        if(!parseInt(chat.g)){
                            id = chat.p[0];
                            var box = this.box(this.contatos[id], "simples", chat);
                        }else{
                            id = `g-${chat.i}`
                            
                            var box = this.box(this.grupos[chat.i], "grupo", chat);
                        }
                      
                        
                      
                        
                           fragmento.appendChild(box);
                           
                           
                    /*
                      this.conversas[id].chatInfo = chat;
                      this.conversas[id].iniciado = chat.i
                      let box =  this.conversas[id].box(id);
                      evento(box, "click", ()=>{
                          this.selecionou.bind(this)(id);
                      })
                      */
                   
                    
                    
                    
                    i++;
                }
                
                this.body.appendChild(fragmento)
                
                
                var options = {
                    valueNames: [ 'nome', { name: 'stamp', attr: 'data-timestamp' }]
            
                };
                this.listConversa = new List('ocChat', options);
                
                    this.listConversa.sort('stamp', {
        order: "desc"
    });
                     
                
               //this.updateListConversas.bind(this)();
                
                
            }
            
            if(this.noRead > 0){
                this.btn.getElementsByClassName("quantidade")[0].innerText = this.noRead;
            }
            
        }, (r)=>{
            console.log(r)
        })
    }
    
    item(){
        
        
        
        

 
    }
}

class CanvaNotificacao{
    constructor(pai){
        if(!document.getElementById("ocNotif")){
            return;
        }
        
           
        const canva = document.querySelector('[data-nown-canvas="ocNotif"]');
        this.btn = canva
        if(canva){
             this.nownc = new nownCanvas(canva.getAttribute('data-nown-canvas'), {
                dirDesktop : canva.dataset.desktop,
                dirMobile : canva.dataset.mobile,
                backdropBlur : (canva.getAttribute('data-nown-canvas') == 'teste' ? false : true),
                persist:  (canva.getAttribute('data-eterno') == 'true' ? true : false),
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
        }else{
            return;
        }
        
        this.paginacao = 1;
        let request = new Request("admin/notificacao.php");
        request.addData({acao: "allNotifications" , paginacao: this.paginacao})
        request.send().then((r)=>{
            this.lista = {}
             if(parseInt(r.total) > 0){
                        this.btn.getElementsByClassName("quantidade")[0].innerText = r.total
                        this.nao = r.total;
                    }
                    
                    
            if(r.lista.length > 0){
                this.monta.bind(this)(r.lista)
            }
            

        })
     
        
    }
    
    componente(item){

  
        const container = document.createElement('div');
        container.addEventListener("click", this.ler.bind(this))
        container.dataset.target = item.hash;
        
        container.classList.add('optimized-component', 'btn');
        if(item.lido == "0"){
            container.classList.add("unread");
        }
        const content = document.createElement('div');
        container.appendChild(content);
        
        const avatarContainer = document.createElement('div');
        avatarContainer.classList.add('avatar-container');
        content.appendChild(avatarContainer);
        
        const avatar = document.createElement('div');
        avatar.classList.add('avatar');
        avatarContainer.appendChild(avatar);
        
        const status = document.createElement('div');
        status.classList.add('status');
        avatarContainer.appendChild(status);
        
        const textContent = document.createElement('div');
        textContent.classList.add('text-content');
        content.appendChild(textContent);
        
        const titleElement = document.createElement('h4');
        titleElement.textContent = item.cabecalho;
        textContent.appendChild(titleElement);
        
        const textElement = document.createElement('DIV');
        textElement.innerHTML = item.body;
        textContent.appendChild(textElement);
        
        var data = document.createElement("DIV")
        data.classList.add("fs-12", "text-n-primaria")
        data.innerText = belaData(item.data);
        textContent.appendChild(data)
        
        
        return container;
    }
    
    ler(){
        if(event.currentTarget.classList.contains("unread")){
            event.currentTarget.classList.remove("unread")
            document.getElementById("ocNotif").focus();
            this.nao--;
            this.btn.getElementsByClassName("quantidade")[0].innerText = this.nao == 0 ? "" :  this.nao;
            
            
            var id = this.lista[event.currentTarget.dataset.target].id
             let request = new Request("admin/notificacao.php");
             request.addData({acao: "lerNotifications" , id: id})
             request.send().then((r)=>{
                    console.log(r)
                 
             })
            

            
        }
        var url = this.lista[event.currentTarget.dataset.target].link;
        if(url){
            var trato = url.split(dominio);
            if(trato.length == 2){
                goUrl(trato[1])
                this.nownc.hide();
            }else{
                goUrl(url);
                 this.nownc.hide();
            }
            
        }
    }
    
    rolagem(event) {
  const listElement = event.target;
  const mainElement = document.getElementById("ocNotif").getElementsByTagName("main")[0]; // Cache mainElement

  if (listElement.scrollTop + listElement.clientHeight >= listElement.scrollHeight && this.mais) {
      this.mais = false;
        let request = new Request("admin/notificacao.php");
        this.paginacao++;
        request.addData({acao: "allNotifications" , paginacao: this.paginacao})
        request.send().then((r)=>{
            if(r.lista.length > 0){
                this.monta.bind(this)(r.lista)
            }
            

        })
      
      
  }
}

    monta(lista) {
  if (lista.length > 0) {
    const fragment = document.createDocumentFragment();
    const listContainer = document
      .getElementById("ocNotif")
      .getElementsByClassName("lista")[0];
    const mainElement = document.getElementById("ocNotif").getElementsByTagName("main")[0];

    for (const item of lista) {
      this.lista[item.hash] = item;
      fragment.appendChild(this.componente(item));
    }

    listContainer.appendChild(fragment);

    // Attach scroll event listener if not already attached
    if (lista.length == 20) { 
        this.mais = true;
        if(!mainElement.hasAttribute("data-scroll-listener")){
            mainElement.addEventListener("scroll", this.rolagem.bind(this));
            mainElement.setAttribute("data-scroll-listener", true);
        }
        
    }
  }
}
    
    item(){
  
    }
}

class CanvaOmini{
    constructor(pai){
        this.pai = pai;
    }
}

class Requesto{
    constructor(url){
        this.url = url;
    }
    
    ajax(data, cb = false) {
        let request = new XMLHttpRequest();
        request.onload = () => {
            try {
                let obj = JSON.parse(request.responseText)
                if (obj.sucesso && cb) {
                    cb(obj)
                } else {
                    console.log(obj)
                }
            } catch (e) {
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominioscript}/${this.url}`)
        request.send(data)
    }
}

class CanvaSaldo{
    constructor(){
        
        this.saldo = false;
        setTimeout(()=>{
            if(!document.getElementById("ocWalet")){
            return;
        }else{
            this.start.bind(this)();
        }
        
        }, 500)

    }
    
    start(){
        
        this.canvas = document.getElementById("ocWalet")
        
        
        const canva = document.querySelector('[data-nown-canvas="ocWalet"]');
        this.btn = canva
        if(canva){

             this.nownc = new nownCanvas(canva.getAttribute('data-nown-canvas'), {
                dirDesktop : "bottom",
                dirMobile : canva.dataset.mobile,
                backdropBlur : (canva.getAttribute('data-nown-canvas') == 'teste' ? false : true),
                persist:  (canva.getAttribute('data-eterno') == 'true' ? true : false),
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
            var btnadd = this.canvas.getElementsByClassName("addSaldo")[0];

            btnadd.addEventListener("click", ()=>{
                goUrl(`pagamento/saldo`)
                this.nownc.hide();
            })
            
            this.canvas.getElementsByClassName("verCarteira")[0].addEventListener("click", ()=>{
                 goUrl(`carteira`)
                this.nownc.hide();
            })
            
            this.btnVer = this.canvas.getElementsByClassName("btnver")[0];
            this.btnVer.addEventListener("click", ()=>{
                this.ver.bind(this)();
            })
            
            this.init.bind(this)();
  
        }else{
            return;
        }
    }
    
    ver(){
        if(this.btnVer.classList.contains("ativo")){
            this.canvas.getElementsByClassName("resumo-oc-cart")[0].classList.remove("visivel")
            this.btnVer.classList.remove("ativo")
            this.btnVer.innerHTML = `<i class="bi bi-eye"></i>`
            defineLocal("verSaldo", false)
        }else{
            this.canvas.getElementsByClassName("resumo-oc-cart")[0].classList.add("visivel")
            this.btnVer.classList.add("ativo")
            this.btnVer.innerHTML = `<i class="bi bi-eye-slash"></i>`
            defineLocal("verSaldo", true)
       
        }
    }
    
    load(){
        document.getElementsByClassName("totalSaldo")[0].innerText = formatarValorZeros(this.wallet.total.total.real);
    }
    
    carteira(){
        this.wallet = new MyWallet(this.load.bind(this));
        
        return;
        let request = new Request(`${dominioscript}/conteudo/modulos/pagamento/admins/api.php`)
        request.addData({acao: "saldo"})
        request.send().then((r)=>{
            var saldo = r.saldo;
            if(saldo){
                if(this.saldo && this.saldo != r.saldo){
                    
                    if(r.saldo > this.saldo){
                         var novo = r.saldo - this.saldo
                    if(novo > 0){
                         iziToast.success({
                        icon: 'bi bi-cash-coin',
                        title: 'Sucesso',
                        message: `Foram creditados R$ ${novo} em sua conta e já está disponível para uso.`
                        });
                    }
                    }else{
                         var novo = this.saldo - r.saldo
                    if(novo > 0){
                         iziToast.success({
                        icon: 'bi bi-cash-coin',
                        title: 'Sucesso',
                        message: `Foram debitados R$ ${novo} da sua conta.`
                        });
                        }
                    }

                }
                
                
                
                this.saldo = r.saldo
            }
            
            var ativo = pegaLocal("verSaldo")

            if(ativo == "true"){
                this.canvas.getElementsByClassName("resumo-oc-cart")[0].classList.add("visivel")
                this.btnVer.classList.add("ativo")
                this.btnVer.innerHTML = `<i class="bi bi-eye-slash"></i>`

            }
        })
    }
    
    init(){

        this.carteira.bind(this)();
    }
    
 
}

class CanvaOpcoes{
     constructor(){
        if(!document.getElementById("ocOpcoes")){
            return;
        }
        

        this.canvas = document.getElementById("ocOpcoes")
        this.canvas.style = "width: 400px; height: auto;"
        
         this.nownc = new nownCanvas('ocOpcoes', {
                dirDesktop : "bottom",
                dirMobile : "bottom",
                backdropBlur : true,
                persist:  true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
         this.list = this.canvas.getElementsByTagName("ul")[0];

    }
    
    loading(){
        this.list.innerHTML = `
         <li class="list-group-item">
                  <div class="he-20 w-100 bg-carregando"></div>
              </li>
              <li class="list-group-item">
                   <div class="he-20 w-100 bg-carregando"></div>
              </li>
              <li class="list-group-item">
                  <div class="he-20 w-100 bg-carregando"></div>
              </li>
              <li class="list-group-item">
                  <div class="he-20 w-100 bg-carregando"></div>
              </li>
              <li class="list-group-item">
                  <div class="he-20 w-100 bg-carregando"></div>
              </li>
        
        `;
      
    }
    
    add(array){
          var i = 0;
    var fragmento = document.createDocumentFragment();
    
    while (i < array.length) {
        var item = array[i];
        var li = document.createElement("LI");
        li.classList.add("list-group-item", "list-group-item-action");
        
        var btn = document.createElement("BUTTON");
        btn.classList.add("btn", "d-flex", "justify-content-start", "align-items-center", "gap-2");
        
          if(item.data){
            for(let c in item.data){
                btn.dataset[c] = item.data[c]
            }
        }
        
        
        
      
        if(item.cb){
            evento(btn, "click", ()=>{
                this.nownc.hide();
                item.cb();
            })
        }
        
        
        if (item.icone) {
            var iconWrapper = document.createElement("SPAN");
            var iconElement = document.createElement("I");
            iconElement.className = item.icone;
            iconWrapper.appendChild(iconElement);
            btn.appendChild(iconWrapper);
        }
        
        if (item.nome) {
            var span = document.createElement("SPAN");
            span.innerText = item.nome;
            btn.appendChild(span);
        }
        
        li.appendChild(btn);
        fragmento.appendChild(li); 
        i++;
    }

    this.list.innerHTML = "";
    this.list.appendChild(fragmento);
    
      this.canvas.style.height = "auto"
    
    }
    
    show(){
        this.nownc.show();
    }
    
    hide(){
        this.nownc.hide();
    }
}

class CanvaProcurar{
    constructor(){
        if(!document.getElementById("ocPesquisa")){
            return;
        }
        

        this.canvas = document.getElementById("ocPesquisa")
        this.canvas.style = "width: 400px; height: auto;"
        
        this.nownc = new nownCanvas('ocPesquisa', {
                dirDesktop : "top",
                dirMobile : "top",
                backdropBlur : true,
                persist:  true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
        setTimeout(()=>{
                evento(document.getElementById("btnTeste"), "click", this.show.bind(this))
            }, 5000)
            
        this.input = document.getElementById('inputPesquisadorGlobal')
        if(this.input){
            this.listaPesquisa = document.getElementById('lista-de-pesquisa')
            this.ulPesquisa = this.listaPesquisa.getElementsByClassName('list-group')[0]
            this.bgCarregandoTermo = this.ulPesquisa.innerHTML
            
            evento(this.input, 'keydown', this.pesquisando.bind(this))
            
            evento(this.input, 'focus', ()=>{
                this.listaPesquisa.classList.add('visivel')
                this.listaPesquisa.classList.remove('invisivel')
            })
            evento(this.input, 'blur', ()=>{
                this.listaPesquisa.classList.remove('visivel')
                this.listaPesquisa.classList.add('invisivel') 
            })
            
            
            this.pegarTermo.bind(this)()
        }
        
        this.botaoPesquisa = document.getElementById('pesquisadorGlobalNown')
        if(this.botaoPesquisa){
            evento(this.botaoPesquisa, 'click', this.pesquisarTermo.bind(this))
        }
        
        this.voz = document.getElementById("btnVozNown")
        
        if(this.voz){
            evento(this.voz, 'click', this.traduzindoFala.bind(this))
        }
            
      

    }
    
    show(){
        
        this.nownc.show();
    }

    traduzindoFala(){
        const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
          
        recognition.lang = "pt-BR"; // Define o idioma para portugu���s do Brasil
        recognition.start(); // Inicia o reconhecimento de voz
            
        recognition.onresult = (event)=> {
            let transcricao = event.results[0][0].transcript; // Captura o texto falado
            this.input.value = transcricao; // Insere no input
            this.pesquisando.bind(this)()
            this.pesquisarTermo.bind(this)()
        };
        
        recognition.onerror = (event)=> {
            console.log("Erro no reconhecimento de voz:", event.error);

            let mensagemErro = "Erro no reconhecimento de voz";
    
            switch (event.error) {
                case "not-allowed":
                    mensagemErro = "Permissão para acessar o microfone negada.";
                    break;
                case "network":
                    mensagemErro = "Erro de conexão. Verifique sua internet.";
                    break;
                case "no-speech":
                    mensagemErro = "Nenhuma fala detectada. Tente novamente.";
                    break;
                case "audio-capture":
                    mensagemErro = "Microfone não detectado. Verifique seu dispositivo.";
                    break;
            }
    
           this.iziError.bind(this)(mensagemErro)
        };
    }
    
    iziError(mensagem){
        iziToast.error({
            icon: "bi bi-x-circle",
            title: "Erro",
            message: mensagem,
            position: "topRight"
        });
    }
    
    pegarTermo(){
        var request = new Request('/conteudo/modulos/historico-de-pesquisa/admins/api.php')
        request.addData({
            'acao': 'sugestoesGlobais',
            'termo': this.input.value.trim(),
            'tipo': 'apis'
        })
        request.send().then((r)=>{
            
            var lista = r.lista
            if(lista.length > 0){
                var i = 0
                var fragmento = document.createDocumentFragment()
                while(i < lista.length){
                    var li = document.createElement('li')
                    li.classList.add('list-group-item')
                    li.innerHTML = `
                        <button class="btn w-100 text-start"><div class="d-flex align-items-center gap-2"><i class="bi bi-search"></i>${lista[i].url}</div></button>
                    `
                    fragmento.appendChild(li)
                    
                    evento(li.getElementsByClassName('btn')[0], 'click', this.inputarSugestao.bind(this))
                    i++
                }
                
                this.ulPesquisa.innerHTML = ''
                this.ulPesquisa.appendChild(fragmento)
            }else{
                this.ulPesquisa.innerHTML = '<li>Nenhuma sugestão foi encontrada para esse termo</li>'
            }
            
            
        }, (r)=>{
            this.ulPesquisa.innerHTML = ''
            this.iziError.bind(this)('Erro ao procurar sugestões em nosso banco de dados')
        })
    }
    
    inputarSugestao(){
        var evento = event.currentTarget
        
        this.input.value = evento.innerText
        
        this.pesquisando.bind(this)()
        this.pesquisarTermo.bind(this)()
    }
    
    pesquisando(){
        this.ulPesquisa.innerHTML = this.bgCarregandoTermo 
        let timeout = null;

        if (event.key === "Enter") {
            event.preventDefault();
            this.pesquisarTermo.bind(this)()
        }else{
            clearTimeout(timeout);

            timeout = setTimeout(()=> {
                this.pegarTermo.bind(this)();
            }, 500);
        }
    }
    
    pesquisarTermo(){
        if(!this.input.value.trim()){
            this.iziError.bind(this)('Nenhuma Pesquisa Foi Feita')
            
            return
        }
        
        
        this.input.setAttribute('disabled', true)
        this.botaoPesquisa.setAttribute('disabled', true)
        this.voz.setAttribute('disabled', true)
        
        this.botaoPesquisa.innerHTML = `<div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>`
        
        
        var request = new Request('/conteudo/modulos/historico-de-pesquisa/admins/api.php')
        request.addData({
            'acao': 'pesquisa',
            'termo': this.input.value.trim(),
            'tipo': 'apis',
            'modulo': 'global',
            'identificador' : 'global'
        })
        request.send().then((r)=>{
            this.input.removeAttribute('disabled', true)
            this.botaoPesquisa.removeAttribute('disabled', true)
            this.voz.removeAttribute('disabled', true)
            this.nownc.hide();
            goUrl(`historico-de-pesquisa/${paraUrl(this.input.value.trim())}`)
            
            
        }, (r)=>{
            this.input.removeAttribute('disabled', true)
            this.botaoPesquisa.removeAttribute('disabled', true)
            this.voz.removeAttribute('disabled', true)
        })
    }
}

