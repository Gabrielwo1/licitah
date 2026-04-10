class ModuloMenuItem{
    constructor(pai, item, id){
        this.pai = pai
        this.menus = pai.menus
        this.info = item
        this.id = id
        
        this.tipos = {
            1: "Item Menu",
            2: "Separador"
        }
    }
    
    set(item){
        this.info = item
        this.divNome.innerText = this.info.nome
    }
    
    get(){
        return this.info
    }
    
    edita(){
        this.pai.edita(this);
    }
    
    render(){
  

       var item = document.createElement("DIV")
        item.classList.add("btn", "border-secondary", "d-block", "itemDeMenu")
        item.dataset.id = this.id
        
        var div = document.createElement("DIV")
        div.classList.add("d-flex", "justify-content-between", "align-items-center", "gap-2")
        
        const container = document.createElement('div');
        container.classList.add('w-100', 'h-100', 'handle', 'd-flex', 'justify-content-between', 'align-items-center', "p-2");
        
        const nomeDoItem = document.createElement('div');
        this.divNome = nomeDoItem
        nomeDoItem.textContent = this.info.nome;
        
        const tipo = document.createElement('div');
        tipo.textContent = this.tipos[this.info.tipo];
        
        container.appendChild(nomeDoItem);
        container.appendChild(tipo);
        
        const botao = document.createElement('BUTTON');
        botao.classList.add("btn")
        evento(botao, "click", this.edita.bind(this))

        
        const span = document.createElement('span');
        span.classList.add("bi","bi-pencil-square");
        
        botao.appendChild(span);
        div.appendChild(container);
        div.appendChild(botao);
        
        item.appendChild(div)
        
        if(this.info.tipo == 1){
            this.filhos = document.createElement("DIV")
        this.filhos.classList.add("filhosContainer", "flex-column", "gap-2")
        this.filhos.style = "min-height: 5px"
        
        item.appendChild(this.filhos)
         nownFiles.add(`${dominio}/assets/aplicativo/sortable/index.js`, false).then(()=>{
         new Sortable(this.filhos, {
             handle: '.handle', 
             animation: 150,
             group: 'shared',
         });
         });
        }
        

        this.menus.appendChild(item) 
        this.html = item
        document.getElementById("renderizadoMenu").classList.remove("d-none")
    }
}

class ModuloMenu{
    constructor(){
        
        this.listaItems = {};
        this.edita = this.edita.bind(this)
        
        this.url = false;
        this.link = false;
        this.separador = false;
        
        this.montaModal.bind(this)()
        
        this.acordion = document.getElementById("opcoesMenu")
        
        var card = document.getElementById("renderizadoMenu")
        card.innerHTML = "";
        card.classList.add("card", "d-none", "card-nown")
        
        this.textArea = document.getElementById("conteudo").getElementsByTagName("textarea")[0]
        this.textArea.closest(".containerInput").classList.add("d-none")
        
        this.opt = this.opt.bind(this)
        
         var opts = new  OpcoesDinamicas(document.getElementById("listadeFuncoes") , 1, "funcoes,funcao_id,funcao_nome", this.memoriaFuncoes.bind(this));
         opts.render().then((r)=>{
             opts.monta(r);
         }, (r)=>{
             console.log(r)
         })
        
        var style = document.createElement("style");
        style.innerHTML = `
        .filhosContainer{
    display:flex;
}
        .filhosContainer .filhosContainer {
        display: none;
    }
    `;
    document.head.appendChild(style);
        
        var body = document.createElement("DIV")
        body.classList.add("card-body")
        card.appendChild(body)
        
        var footer = document.createElement("DIV")
        footer.classList.add("card-footer","d-flex", "justify-content-end", "bg-transparent")
        
        var btnsSalvar = document.createElement("BUTTON")
        btnsSalvar.classList.add("btn", "btn-n-primaria", "btn-nown-style")
        btnsSalvar.innerText = "Salvar"
        evento(btnsSalvar, "click", this.salvar.bind(this))
        footer.appendChild(btnsSalvar)
        
        
        
        card.appendChild(footer)
        
        this.menus = document.createElement("DIV")
        this.menus.classList.add("d-flex", "flex-column", "gap-2")
        this.menus.id = "listaM"
        nownFiles.add(`${dominio}/assets/aplicativo/sortable/index.js`, false).then(()=>{
            new Sortable(this.menus, {
                handle: '.handle', 
                animation: 150,
                group: 'shared'
            });
        })
        
        body.appendChild(this.menus)
        
        this.menuRender = this.menuRender.bind(this)

        
        this.acordion.classList.add("accordion")
       
       var item = this.itemMenu.bind(this)("Link");
       
       item.getElementsByClassName("accordion-body")[0].appendChild(this.entrada.bind(this)("URL", "url"))
       item.getElementsByClassName("accordion-body")[0].appendChild(this.entrada.bind(this)("Texto", "nome"))
       
       var div = document.createElement("DIV")
       div.classList.add("d-flex","justify-content-end", "mt-2")
       
       var botao = document.createElement("BUTTON")
       botao.classList.add("btn", "btn-primary")
       evento(botao, "click", this.novoLink.bind(this))
       botao.innerText = "Adicionar Item"
       div.appendChild(botao)
       item.getElementsByClassName("accordion-body")[0].appendChild(div)
    
       var item = this.itemMenu.bind(this)("Separador");
       item.getElementsByClassName("accordion-body")[0].appendChild(this.entrada.bind(this)("Texto", "separador"))
       
        var div = document.createElement("DIV")
       div.classList.add("d-flex","justify-content-end", "mt-2")
       
       var botao = document.createElement("BUTTON")
       botao.classList.add("btn", "btn-primary")
       botao.innerText = "Adicionar Item"
       evento(botao, "click", this.novoSeparador.bind(this))
       div.appendChild(botao)
       item.getElementsByClassName("accordion-body")[0].appendChild(div)
    }
    
    memoriaFuncoes(r){
        this.listaDeFuncoes = r.lista
    }
    
    salvar(){
         var respostas = [];
    let divsPais = document.querySelectorAll('#listaM > div.itemDeMenu');

    divsPais.forEach(divPai => {
        var item = this.listaItems[divPai.dataset.id].get();
        var filhos = [];

        var divFilhosContainer = this.listaItems[divPai.dataset.id].html.querySelector(".filhosContainer");

        if (divFilhosContainer) {
            var divsFilhas = divFilhosContainer.querySelectorAll(".itemDeMenu");

            divsFilhas.forEach(divFilha => {
                var filhoId = divFilha.dataset.id;
                var filhoItem = this.listaItems[filhoId].get();
                filhoItem.filhos = [];
                filhos.push(filhoItem);
            });
        }

        item.filhos = filhos;
        respostas.push(item);
         
    });
    
    
      this.textArea.value = JSON.stringify(respostas);
        document.getElementById("cardSalvarBtnSalvar").click();
        setTimeout(()=>{
            console.log("fuimo")
             start.ajax();
        }, 500)



 


    }
    
    montaModal(){

        var pai = document.getElementById("modalMenu");
        pai.classList.add("modal","fade")
        pai.setAttribute("tabindex","-1")
        pai.setAttribute("aria-labelledby","modalMenu")
        pai.setAttribute("aria-hidden","true")
        
        
        var dialog = document.createElement("DIV")
        dialog.classList.add("modal-dialog")
        
        var content = document.createElement("DIV")
        content.classList.add("modal-content")
        
        
        var header = document.createElement("DIV")
        header.classList.add("modal-header")
        
        var titulo = document.createElement("H2")
        titulo.classList.add("fs-18", "m-0", "fw-500")
        titulo.innerText = "Editar Menu"
        
        var fechar = document.createElement("BUTTON")
        fechar.setAttribute("type", "button")
        fechar.classList.add("btn-close")
        fechar.setAttribute("data-bs-dismiss","modal") 
        fechar.setAttribute("aria-label","Close")
        header.appendChild(titulo)
        header.appendChild(fechar)
        
        
        content.appendChild(header)
        
        
        this.modalbody = document.createElement("DIV")
        this.modalbody.classList.add("modal-body", "d-flex", "flex-column", "gap-3")
        
    

        content.appendChild(this.modalbody)
        
        
        var footer = document.createElement("DIV")
        footer.classList.add("modal-footer", "d-flex", "justify-content-between", "align-items-center")
        
        var apagar = document.createElement("BUTTON")
        apagar.innerText = "Apagar"
        apagar.classList.add("btn", "text-danger")
        evento(apagar, "click", this.apagarItem.bind(this))
        
        var salvar = document.createElement("BUTTON")
        salvar.classList.add("btn", "btn-primary")
        salvar.innerText = "Salvar" 
        evento(salvar, "click", this.salvarItem.bind(this))
        
        footer.appendChild(apagar)
        footer.appendChild(salvar)
        content.appendChild(footer)
        
        dialog.appendChild(content)
        pai.appendChild(dialog)
        
       this.modal = new bootstrap.Modal(pai, {
           keyboard: false
       })
       
       setTimeout(()=>{
           this.completa.bind(this)()
       }, 100)



    }
    
    completa(){
          if(!this.textArea.value){
           this.textArea.value = "[]"
       }else{
            var memo = JSON.parse(this.textArea.value)
            var i = 0;
            while(i < memo.length){
                var item = memo[i]
                 var id = geraId();
                 this.listaItems[id] = new ModuloMenuItem(this, item, id);
                 this.listaItems[id].render();
                 
                 
                 if(item.filhos && item.filhos.length > 0){
                     var html = this.listaItems[id].html.getElementsByClassName("filhosContainer")[0]
                     
                     var z = 0;
                     while(z < item.filhos.length){
                         var filho = item.filhos[z]
                         var id = geraId();
                         this.listaItems[id] = new ModuloMenuItem(this, filho , id);
                         this.listaItems[id].render();
                         html.appendChild(this.listaItems[id].html)
               
                         z++;
                     }
                 }
                 
                i++;
            }
       }
    }
    
    apagarItem(){
        let divsFilhas = this.listaItems[this.foco.id].html.querySelectorAll('.itemDeMenu');
        divsFilhas.forEach(div => {
            this.menus.appendChild(div)
        });
        
        this.listaItems[this.foco.id].html.remove();
        delete this.listaItems[this.foco.id]
        this.modal.hide();
        
        if(Object.keys(this.listaItems).length == 0){
            document.getElementById("renderizadoMenu").classList.add("d-none")
        }
    }
    
    salvarItem(){
        if(this.foco.info.tipo == 1){
              var item = {
                tipo: 1,
                "nome": this.editaTexto.value,
                "link": this.editaUrl.value,
                "icone": this.icone.value,
                "etiqueta": this.etiqueta.value
            }
        }else{
              var item = {
                tipo: 2,
                "nome": this.editaTexto.value,
                "icone": this.icone.value,
            }
        }
        
        item.visibilidade = this.btnVisibilidade.value;
        
        if(item.visibilidade > 0){

  var valoresSelecionados = [];


  for (var i = 0; i < this.inputFuncoes.options.length; i++) {
    var option = this.inputFuncoes.options[i];

    // Se a opção estiver selecionada, adiciona o valor ao array
    if (option.selected) {
      valoresSelecionados.push(option.value);
    }
  }

  // Retorna um array com os valores selecionados
  item.selecionados = valoresSelecionados;
        
        }
        
        
        

        this.listaItems[this.foco.id].set(item)
        this.modal.hide();
    }
    
    opt(chave, valor, select){
        var option = document.createElement("OPTION")
        option.value = valor
        option.innerText = chave
        select.appendChild(option)
    }
    
    controlVizibilidade(){
        if(this.btnVisibilidade.value == 0){
            this.agrupado.classList.add("d-none")
        }else{
            this.agrupado.classList.remove("d-none")
        }
    }
    
    visibilidade(){
        var valor = false;
        var pai = document.createElement("DIV")
         var div = document.createElement("DIV")
        
        var lab = document.createElement("LABEL")
        lab.classList.add("form-label")
        lab.innerText = "Visibilidade"
        
        var input = document.createElement("SELECT")
        input.classList.add("form-select")
        
        this.opt("Mostrar para todos" , 0 , input)
        this.opt("Mostrar  para", 1 , input)
        this.opt("Esconder para", 2 , input)
        
        if(valor){
            input.value = valor
        }
        this.btnVisibilidade = input
        evento(input, "input", this.controlVizibilidade.bind(this))
        
        this.regra = input
        
        div.appendChild(lab)
        div.appendChild(input)
        pai.appendChild(div)
        
        
        div = document.createElement("DIV")
        div.classList.add("mt-4", "d-none")
        
        var lab = document.createElement("LABEL")
        lab.classList.add("form-label")
        lab.innerText = "Grupo de Usuários"
        
        var input = document.createElement("SELECT")
        input.setAttribute("multiple", "")
        input.classList.add("form-select")
        this.inputFuncoes = input
        var chaves = {
            logados:"Logados",
            deslogados:"Deslogados",
            equipe:"Equipe",
            clientes:"Clientes",
        }
        
        
        
        for(let c in chaves){
            this.opt(chaves[c], c , input)
        }
        
        for(let c in this.listaDeFuncoes){
             this.opt(c , this.listaDeFuncoes[c] , input)
        }

   
 

        
        
        
        
        if(valor){
            input.value = valor
        }
        
        evento(input, "input", this.limpa.bind(this))
        
        this.grupo = input
        
        div.appendChild(lab)
        div.appendChild(input)
        this.agrupado = div;
        pai.appendChild(div)
        
        
        return pai;
    }
    
    edita(item){
        this.foco = item
 
        this.modalbody.innerHTML = ""
        if(this.foco.info.tipo == 1){
            this.modalbody.appendChild(this.entrada("Texto", "editaTexto", this.foco.info.nome))
            this.modalbody.appendChild(this.entrada("Url", "editaUrl", this.foco.info.link))
            this.modalbody.appendChild(this.entrada("Etiqueta", "etiqueta", this.foco.info.etiqueta))
            this.modalbody.appendChild(this.entrada("Icone", "icone", this.foco.info.icone))
        }else{
            this.modalbody.appendChild(this.entrada("Texto", "editaTexto", this.foco.info.nome))
            this.modalbody.appendChild(this.entrada("Icone", "icone", this.foco.info.icone))
        }
        this.modalbody.appendChild(this.visibilidade.bind(this)())

        
        if(this.foco.info.visibilidade && parseInt(this.foco.info.visibilidade) > 0){
            this.btnVisibilidade.value = this.foco.info.visibilidade
            this.controlVizibilidade.bind(this)()
            
            
            for(let c in this.foco.info.selecionados){
                var item = this.foco.info.selecionados[c]
                
                var options = this.inputFuncoes.options
                var i = 0;
                while(i < options.length){
                    if(options[i].value == item){
                        options[i].selected = true;
                        break;
                    }
                    
                    i++;
                }
                
                console.log(item)
            }
            
            
        }
        
        
        this.modal.show();
    }
    
    novoLink(){
 
        var alt = true;
       
        if(!this.nome.value){
            this.nome.classList.add("is-invalid")
            alt = false;
        }
        
        if(alt){
            var item = {
                tipo: 1,
                "nome": this.nome.value,
                "link": this.url.value
            }
            
            this.nome.value = ""
            this.url.value = ""
            
            this.menuRender(item);
        }else{
            alert("Adicione as informações de Nome e Link")
        }
        
    }
    
    novoSeparador(){
          if(!this.separador.value){
            this.separador.classList.add("is-invalid")
            alert("Adicione um texto ao separador")
        }else{
            var item = {
                tipo: 2,
                "nome": this.separador.value
            }
            this.separador.value = ""
            
            this.menuRender(item);
        }
        
    }
    
    menuRender(item){
        var id = geraId();
        this.listaItems[id] = new ModuloMenuItem(this, item, id);
        this.listaItems[id].render();
    }
    
    limpa(){
        event.currentTarget.classList.remove("is-invalid")
    }
    
    entrada(label, marcador, valor = false){
        var div = document.createElement("DIV")
        
        var lab = document.createElement("LABEL")
        lab.classList.add("form-label")
        lab.innerText = label
        
        var input = document.createElement("INPUT")
        input.classList.add("form-control")
        
        if(valor){
            input.value = valor
        }
        
        evento(input, "input", this.limpa.bind(this))
        
        this[marcador] = input
        
        div.appendChild(lab)
        div.appendChild(input)
        return div;
   
    }
    
    itemMenu(titulo){
         
        var id = geraId();
        var item = document.createElement("DIV")
        item.classList.add("accordion-item")
        
        var header = document.createElement("H2")
        header.classList.add("accordion-header")
        
        var botao = document.createElement("BUTTON")
        botao.classList.add("accordion-button","collapsed","text-uppercase","fs-14","fw-500")
        botao.setAttribute("type","button")
        botao.setAttribute("data-bs-toggle","collapse")
        botao.setAttribute("data-bs-target",`#${id}`)
        botao.setAttribute("aria-expanded","false")
        botao.setAttribute("aria-controls", id)
        botao.innerText = titulo
        header.appendChild(botao)
        
        var body = document.createElement("DIV")
        body.classList.add("accordion-body", "d-flex", "flex-column", "gap-2")
        
        item.appendChild(header)
        
        var div = document.createElement("DIV")
        div.id= id
        div.classList.add("accordion-collapse","collapse")
        div.setAttribute("data-bs-parent","#opcoesMenu")
        
        div.appendChild(body)
        item.appendChild(div)
    
        this.acordion.appendChild(item)
        return item
    }
    
}

function disponibilidade(divId, ciclos = 10) {
  return new Promise((resolve, reject) => {
    let cicloAtual = 0;
    const interval = setInterval(() => {
      const divExistente = document.getElementById(divId);
      cicloAtual++;

      if (divExistente) {
        clearInterval(interval);
        resolve(true);
      }

      if (cicloAtual === ciclos) {
        clearInterval(interval);
        reject(new Error(`A div com ID ${divId} não foi encontrada após ${ciclos} ciclos.`));
      }
    }, 200);
  });
}

function moduloMenusMenu(){
    disponibilidade("opcoesMenu").then(()=>{
        new ModuloMenu();
    })
}

