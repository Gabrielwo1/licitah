import { cripto, pegaLocal, defineLocal, loading, criarIcone, preventLink, evento, ajax, loadGoogleMaterialIcons, ls, lcss, loadResources, geraId } from 'https://nown.com.br//assets/js/modulos/functions.js?v=5454';


export default class BaseSistema {
    constructor() {
        this.render = this.render.bind(this)
        this.ominiChanel = this.ominiChanel.bind(this)
        this.multiLanguage = this.multiLanguage.bind(this)
        this.chat = this.chat.bind(this)
        this.notificacao = this.notificacao.bind(this)
        this.darkMode = this.darkMode.bind(this)
        this.carrinho = this.carrinho.bind(this)
        this.menuTopo = this.menuTopo.bind(this)
        this.menuLateral = this.menuLateral.bind(this)
        this.btn = this.btn.bind(this)
        this.limpa = this.limpa.bind(this)
        this.openMenu = this.openMenu.bind(this)
        
        
        this.topo = document.getElementById("topo") ? document.getElementById("topo") : false;
        this.direita = this.topo.getElementsByClassName("direita")[0]
        this.esquerda = this.topo.getElementsByClassName("esquerda")[0]
        this.centro = this.topo.getElementsByClassName("centro")[0]
        
        this.footer = document.getElementById("rodape") ? document.getElementById("rodape") : false;
    }

    sair() {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = () => {
            start.ajax();
            goUrl("");
        }
        xhttp.open("GET", `${dominio}/admin/desloga.php`);
        xhttp.send();
    }

    render(config) {
        /*
        this.direita.innerHTML = "";
        this.esquerda.innerHTML = "";
        this.centro.innerHTML = "";
        document.getElementById("menuLateral").innerHTML = "";
        */


        this.config = config

        if (this.config.menus.lateral.length > 0) {
            document.getElementById("lateral").classList.remove("d-none")
            document.getElementById("corpo").style = "";

            this.menuLateral(this.config.menus.lateral);
            var lateral = true;
        } else {
            document.getElementById("lateral").classList.add("d-none")
            document.getElementById("corpo").style = "margin-left: 0px";
            var lateral = false;
        }


        if (this.config.menus.topo.length > 0) {
            this.menuTopo(this.config.menus.topo);
        }

        
        
        if(this.config.menus.footer.length > 0){
            this.menuFooter.bind(this)(this.config.menus.footer);
        }


        this.darkMode();

        if (true) {
            this.chat();
            this.notificacao()
        }

        this.limpa(this.topo);


        if (lateral) {
            this.openMenu()
        }



        if (this.config.usuario.autorizado) {
            this.logado.bind(this)();
        } else {
            this.deslogado.bind(this)();
        }


    }
    
    menuFooter(itens){
   
        
        var row = document.createElement("DIV")
        row.classList.add("row","m-0","p-0","he-70")
        
        var i = 0;
        while(i < itens.length){
            var item = itens[i].config
            console.log(item)
            var col = document.createElement("DIV")
            col.classList.add("col","p-0")
            
            var a = document.createElement("A")
            a.classList.add("btn","bg-contrast","text-contrast", "w-100","h-100","border-0","rounded-0","p-1","d-flex","flex-column","justify-content-center")
            
            var div = document.createElement("DIV")
            
            var divIcone = document.createElement("DIV")
            var icone = document.createElement("I")
            icone.classList.add("material-symbols-outlined")
            icone.innerText = "settings"
            divIcone.appendChild(icone)
            
            var texto = document.createElement("DIV")
            texto.classList.add("fs-12", "text-center", "mt-2")
            texto.innerText = item.texto
            
            div.appendChild(divIcone)
            div.appendChild(texto)
            a.appendChild(div)
            col.appendChild(a)
            row.appendChild(col)
            
            i++;
        }
        
 
        this.footer.innerHTML = "";
        this.footer.appendChild(row)
        
        
        
    }

    itemMenu(item) {
        var li = document.createElement("LI")
        li.classList.add("list-group-item", "text-uppercase", "corGlobal", "py-2")
        switch (item.tipo) {
            case 1:
                // Tipo Título
                li.classList.add("fs-10", "fw-700")
                li.innerText = item.texto
                break;
            case 2:
                // Tipo Link
                li.classList.add("fw-300")
                var a = document.createElement("A")
                a.classList.add("d-flex", "justify-content-start", "align-items-center", "gap-2", "text-decoration-none", "corGlobal", "fs-12")

                var span = document.createElement("SPAN")
                span.classList.add("material-symbols-outlined")
                span.style.fontSize = "14px"

                var texto = document.createElement("SPAN")
                texto.classList.add("fs-12")
                texto.innerText = item.texto

                a.appendChild(span)
                a.appendChild(texto)
                li.appendChild(a)
                break;
            case 3:
                // Tipo Sair
                li.classList.add("fw-500")
                var a = document.createElement("BUTTON")
                a.classList.add("btn", "d-flex", "justify-content-start", "align-items-center", "gap-2", "text-decoration-none", "corGlobal", "fs-14")
                a.addEventListener("click", this.sair.bind(this))

                var span = document.createElement("SPAN")
                span.classList.add("material-symbols-outlined")
                span.style.fontSize = "14px"

                var texto = document.createElement("SPAN")
                texto.classList.add("fs-12")
                texto.innerText = item.texto

                a.appendChild(span)
                a.appendChild(texto)
                li.appendChild(a)
                break;
        }

        return li;
    }

    btnNown() {

        if (document.getElementById("lateral")) {
            var footer = document.getElementById("lateral").getElementsByClassName("card-footer")[0]
            footer.classList.remove("d-none")
            footer.innerHTML = ""
            var btn = document.createElement("BUTTON")
            btn.classList.add("w-100", "h-100", "btn", "btn-contrast", "btn-sm")

            var span = document.createElement("SPAN")
            span.classList.add("texto")
            span.innerText = "NOWN"
            btn.appendChild(span)

            footer.appendChild(btn)
        }
    }

    logado() {
        var obj = JSON.parse(pegaLocal("nown"))
        var usuario = obj.usuario.usuario



        if (parseInt(usuario.funcao) == 0 || parseInt(usuario.funcao) == 1) {
            this.btnNown.bind(this)();
        } else {
            if (document.getElementById("lateral")) {
                var footer = document.getElementById("lateral").getElementsByClassName("card-footer")[0]
                footer.innerHTML = ""
                footer.classList.add("d-none")
            }
        }

        var div = document.createElement("DIV")
        div.classList.add("dropdown")

        var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "p-0", "he-35", "wi-35", "img-thumbnail", "bg-secondary")
        botao.setAttribute("role", "button")
        botao.setAttribute("data-bs-toggle", "dropdown")
        botao.setAttribute("aria-expanded", "false")
        botao.setAttribute("type", "button")


        div.appendChild(botao)

        var corpo = document.createElement("DIV")
        corpo.classList.add("dropdown-menu", "p-0", "dropdown-menu-end")
        corpo.style.width = "300px"

        const cardHeaderDiv = document.createElement("div");
        cardHeaderDiv.classList.add("card-header");

        const innerDiv = document.createElement("div");
        innerDiv.classList.add("d-flex", "justify-content-start", "gap-2", "align-items-center");

        const imageDiv = document.createElement("div");
        imageDiv.style.cssText = "height: 50px; width: 50px";
        imageDiv.classList.add("rounded-circle", "d-flex", "justify-content-center", "align-items-center", "img-thumbnail");

        //imageDiv.innerHTML = fotoPerfil();

        const textInfoDiv = document.createElement("div");
        const nameHeading = document.createElement("h3");
        nameHeading.classList.add("fs-18", "m-0");
        nameHeading.textContent = usuario.nome;
        const emailParagraph = document.createElement("p");
        emailParagraph.classList.add("fs-12", "m-0");
        emailParagraph.textContent = usuario.email;

        textInfoDiv.appendChild(nameHeading);
        textInfoDiv.appendChild(emailParagraph);
        innerDiv.appendChild(imageDiv);
        innerDiv.appendChild(textInfoDiv);
        cardHeaderDiv.appendChild(innerDiv);

        var links = [{
                tipo: 1,
                texto: "Seu Perfil"
            },
            {
                tipo: 2,
                icone: "manufacturing",
                texto: "Configurações"
            },
            {
                tipo: 2,
                icone: "admin_panel_settings",
                texto: "Privacidade"
            },
            {
                tipo: 2,
                icone: "notifications",
                texto: "Notificações"
            },
            {
                tipo: 1,
                texto: "Histórico"
            },
            {
                tipo: 2,
                icone: "lock",
                texto: "Segurança"
            },
            {
                tipo: 2,
                icone: "account_balance_wallet",
                texto: "Meu Pedidos"
            },
            {
                tipo: 2,
                icone: "forward_media",
                texto: "Minhas Assinaturas"
            },
            {
                tipo: 2,
                icone: "payments",
                texto: "Todas Transições"
            },
            {
                tipo: 2,
                icone: "badge",
                texto: "Seus Dados"
            },
            {
                tipo: 2,
                icone: "contact_support",
                texto: "Suporte"
            },
            {
                tipo: 3,
                icone: "logout",
                texto: "Sair"
            }
        ];

        var body = document.createElement("DIV")
        body.classList.add("card-body")

        var ul = document.createElement("UL")
        ul.classList.add("list-group", "list-group-flush")

        var i = 0;
        while (i < links.length) {
            ul.appendChild(this.itemMenu(links[i]))
            i++;
        }

        body.appendChild(ul)

        corpo.appendChild(cardHeaderDiv)
        corpo.appendChild(body)
        div.appendChild(corpo)



        if (this.topo) {
            this.btnPerfil = div;
            this.topo.getElementsByClassName("direita")[0].appendChild(div)
        }




    }

    deslogado() {

        var a = document.createElement("A")
        a.classList.add("text-decoration-none", "btn", "text-light", "d-flex", "justify-content-center", "align-items-center", "btn-danger", "fs-14", "btn-sm", "gap-2", "he-35", "wi-35")
        a.href = `${dominio}/acesso`;
        a.addEventListener("click", preventLink)

        var span = document.createElement("SPAN")
        var icone = document.createElement("I")
        icone.classList.add("material-symbols-outlined")
        icone.innerText = "passkey"
        span.appendChild(icone)

        var texto = document.createElement("SPAN")
        texto.classList.add("d-none", "d-lg-block", "fs-500", "fs-14")
        texto.innerText = "Acessar Conta"



        a.appendChild(span)
       // a.appendChild(texto)


        if (this.topo) {
            this.btnLogin = a;
            this.topo.getElementsByClassName("direita")[0].appendChild(a)
        }

    }

    abreMenu() {
        var btn = event.currentTarget
        if (btn.classList.contains("ativo")) {
            btn.classList.remove("ativo")
            document.body.classList.remove("ativo")
        } else {
            btn.classList.add("ativo")
            document.body.classList.add("ativo")
        }
    }

    openMenu() {
        var btn = document.createElement("BUTTON")
        btn.classList.add("btn", "he-35", "wi-35", "rounded", "d-flex", "justify-content-center", "align-items-center", "position-relative", "openMenu")
        btn.addEventListener("click", this.abreMenu.bind(this))

        var um = document.createElement("SPAN")
        um.classList.add("barra")
        var dois = document.createElement("SPAN")
        dois.classList.add("barra")
        var tres = document.createElement("SPAN")
        tres.classList.add("barra")

        btn.appendChild(um)
        btn.appendChild(dois)
        btn.appendChild(tres)



        this.esquerda.insertBefore(btn, this.esquerda.firstChild);
    }

    limpa(pai) {
        var elementos = pai.getElementsByClassName("bg-carregando");

        var elementosArray = Array.from(elementos);
        elementosArray.forEach(function(elemento) {
            elemento.parentNode.removeChild(elemento);
        });
    }

    menuTopo(items) {
        var ul = document.createElement("UL")
        ul.classList.add("nav", "justify-content-center", "d-none", "d-xl-flex")

        var i = 0;
        while (i < items.length) {
            var item = items[i]
            var li = document.createElement("LI")
            li.classList.add("nav-item")
            var a = document.createElement("A")
            a.classList.add("nav-link", "text-contrast", "fs-14", "fw-600")
            a.setAttribute("href", `${dominio}/${item.config.link}`)
            a.addEventListener("click", preventLink)

            var span = document.createElement("SPAN")
            span.innerText = item.config.texto


            a.appendChild(span)
            li.appendChild(a)
            ul.appendChild(li)
            i++;
        }

        this.centro.appendChild(ul)

    }

    menuLateral(itens) {
        var pai = document.getElementById("menuLateral")
        var i = 0;
        while (i < itens.length) {
            var id = geraId();
            var item = itens[i]


            if (item.config.filhos && item.config.filhos.length > 0) {
                var divAccordionItem = document.createElement("div");
                divAccordionItem.classList.add("accordion-item", "border-0");

                // Criar a tag h2 com classe "accordion-header"
                var h2 = document.createElement("h2");
                h2.classList.add("accordion-header");

                // Criar o botão com classe "accordion-button"
                var button = document.createElement("button");
                button.classList.add("accordion-button", "p-3", "collapsed", "d-flex", "justify-content-start", "gap-2", "aling-items-center");
                button.style.width = "100%"
                button.setAttribute("type", "button");
                button.setAttribute("data-bs-toggle", "collapse");
                button.setAttribute("data-bs-target", `#${id}`);
                button.setAttribute("aria-expanded", "false");
                button.setAttribute("aria-controls", "collapseOne");


                var spanIcone = document.createElement("span");

                if (item.config.icone) {
                    var icon = criarIcone(item.config.icone);
                }



                var spanTexto = document.createElement("span");
                spanTexto.classList.add("texto", "text-contrast", "fs-14", "fw-600");
                spanTexto.textContent = item.config.texto


                if (icon) {
                    button.appendChild(icon);
                }



                button.appendChild(spanTexto);

                h2.appendChild(button);

                var divCollapse = document.createElement("div");
                divCollapse.classList.add("accordion-collapse", "collapse");
                divCollapse.setAttribute("id", id);
                divCollapse.setAttribute("data-bs-parent", "#menuLateral");

                var divAccordionBody = document.createElement("div");
                divAccordionBody.classList.add("accordion-body", "py-0");
                divAccordionBody.style.width = "100%"


                var ul = document.createElement("ul");
                ul.classList.add("list-group", "list-group-flush");


                item.config.filhos.forEach(function(filho) {
                    var li = document.createElement("li");
                    li.classList.add("list-group-item");
                    var a = document.createElement("A")
                    a.classList.add("text-decoration-none", "text-contrast", "fs-14", "fw-500")
                    a.setAttribute("href", `${dominio}/${filho.config.link}`)
                    a.addEventListener("click", preventLink)
                    a.innerText = filho.config.texto;
                    li.appendChild(a)
                    ul.appendChild(li);
                });


                divAccordionBody.appendChild(ul);


                divCollapse.appendChild(divAccordionBody);


                divAccordionItem.appendChild(h2);
                divAccordionItem.appendChild(divCollapse);
            } else {

                var divAccordionItem = document.createElement("div");



                var h2 = document.createElement("h2");
                h2.classList.add("accordion-header")


                if (item.config.tipo != 3) {

                    var button = document.createElement("a");
                    button.classList.add("text-contrast", "btn", "p-3", "d-flex", "justify-content-start", "gap-2", "aling-items-center", "w-100");
                    button.setAttribute("type", "button");
                    button.setAttribute("href", `${dominio}/${item.config.link}`)
                    button.addEventListener("click", preventLink)

                    if (item.config.icone) {
                        var icon = criarIcone(item.config.icone);
                    }


                    // Criar a segunda span com classe "texto"
                    var spanTexto = document.createElement("span");
                    spanTexto.classList.add("texto", "text-contrast", "fs-14", "fw-600");
                    spanTexto.textContent = item.config.texto


                    if (icon) {
                        button.appendChild(icon);
                    }


                    // Adicionar a primeira span e a segunda span como filhos do botão

                    button.appendChild(spanTexto);

                } else {
                    var button = document.createElement("DIV")
                    button.classList.add("texto", "fs-12", "p-3", "fw-600")
                    button.style.width = "250px"
                    button.innerText = item.config.texto
                }



                // Adicionar o botão como filho do h2
                h2.appendChild(button);
                divAccordionItem.appendChild(h2)

            }



            pai.appendChild(divAccordionItem)

            i++;
        }



    }

    btn(icone) {

        var btn = document.createElement("BUTTON")
        btn.classList.add("btn", "he-35", "wi-35", "rounded", "d-flex", "justify-content-center", "align-items-center", "position-relative")

        var i = document.createElement("SPAN")

        i.classList.add("material-symbols-outlined", "text-contrast")
        i.innerText = icone

        btn.appendChild(i)
        return btn;
    }

    ominiChanel() {

    }

    multiLanguage() {
        /*
                 <div class="dropdown">
                 <button class="btn bg-success wi-35 he-35" role="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                 <ul class="dropdown-menu p-0" style="min-width: 35px">
                    <li class="wi-35"><button class="btn bg-danger wi-35 he-35 rounded-0"></button></li>
                    <li class="wi-35"><button class="btn bg-warning wi-35 he-35 rounded-0"></button></li>
                    <li class="wi-35"><button class="btn bg-info wi-35 he-35 rounded-0"></button></li>
                 </ul>
              </div>
              
              */
    }

    chat() {
        this.direita.insertBefore(this.btn('sms'), this.direita.firstChild);
    }

    notificacao() {
        this.direita.insertBefore(this.btn('notifications_active'), this.direita.firstChild);
    }

    lightDarkMode() {
        if (this.checked) {
            document.body.classList.remove("light")
        } else {
            document.body.classList.add("light")
        }
    }

    darkMode() {
        const label = document.createElement('label');
        label.className = "bg-white he-35 wi-70 rounded d-block position-relative p-2 border d-none d-xl-block";
        const span = document.createElement('span');
        span.className = "row m-0 position-absolute h-100 w-100 top-0 start-0 z-2";

        const moonIcon = document.createElement('div');
        moonIcon.className = "col-6 m-0 p-0 d-flex justify-content-center align-items-center";
        moonIcon.innerHTML = '<i class="material-symbols-outlined text-white">light_mode</i>';

        const sunIcon = document.createElement('div');
        sunIcon.className = "col-6 m-0 p-0 d-flex justify-content-center align-items-center";
        sunIcon.innerHTML = '<i class="material-symbols-outlined text-white">dark_mode</i>';

        span.appendChild(moonIcon);
        span.appendChild(sunIcon);

        const input = document.createElement('input');
        input.type = 'checkbox';
        input.className = 'd-none darkMode';
        input.addEventListener("input", this.lightDarkMode)

        const div = document.createElement('div');
        div.className = 'z-1';

        label.appendChild(span);
        label.appendChild(input);
        label.appendChild(div);

        this.direita.insertBefore(label, this.direita.firstChild);


    }

    carrinho() {}

}