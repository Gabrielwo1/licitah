class LinhaTabelo{
    constructor(infos, pai, num){
    
        this.num = num
        let keys = Object.keys(infos);
        if (keys.length > 0) {
            let lastKey = keys.pop();
            this.extra = infos[lastKey]
            delete infos[lastKey];
        }
        
        this.infos = infos
        this.pai = pai
        
    }
    
    edita(){
        goUrl(`${this.rotaEdita}/${this.extra.s}`)
    }
    
    apaga(){
        Swal.fire({
            icon: "question",
            title: "Tem certeza?",
            text: "Após confirmar , esse item será apagado totalmente do banco de dados.",
            showCancelButton: true,
            confirmButtonText: "Apagar",
            cancelButtonText: `Cancela`
        }).then((result) => {
            if (result.isConfirmed) {
                this.pai.remove(this.tr, this.extra.s)

            } 
        });
    }
    
    ver(){
        goUrl(`${this.rotaVer}/${this.extra.p}`)
    }
    
    formatarData(dataStr) {
  // Crie um objeto Date a partir da string de data
  const data = new Date(dataStr);

  // Array para os nomes dos meses
  const meses = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
  ];

  // Extraia o dia, mês e ano da data
  const dia = data.getDate();
  const mes = meses[data.getMonth()];
  const ano = data.getFullYear();

  // Construa a string formatada
  const dataFormatada = `${dia} de ${mes} de ${ano}`;

  return dataFormatada;
}

    marcacao(){
         var filhos = document.getElementsByClassName("grupoMark");
         var contador = 0;
         for(var i = 0; i < filhos.length; i++) {
             if(filhos[i].checked){
                 contador++;
             }
        }
        
       
        if(filhos.length == contador){
            this.pai.marcaTodos.checked = true;
        }else{
            this.pai.marcaTodos.checked = false;
        }
        

        if(event.currentTarget.checked){
           this.pai.agrupados[this.extra.s] = this.tr
        }else{
            delete this.pai.agrupados[this.extra.s];
        }
        
        this.pai.acoesEmGrupo()

    }

    render(acoes){

        this.acoes = acoes;
        this.tr = document.createElement("TR")
    
        
        var i = 0;
     
          var td = document.createElement("TD")
          td.classList.add("d-none")
          
          
          
          if(this.pai.ordenado){
            td.innerText = this.extra.o
          }else{
              td.innerText = this.infos.id
          }
          
          this.tr.appendChild(td)
          
          
        if(this.pai.multiplo){
            var td = document.createElement("TD")
            this.marcaTodos = document.createElement("INPUT")
            this.marcaTodos.classList.add("form-check-input", "grupoMark")
            this.marcaTodos.type = "checkbox"
            evento(this.marcaTodos, "input", this.marcacao.bind(this))
            td.appendChild(this.marcaTodos)
            this.tr.appendChild(td)
        }
          
        var visibilidade = this.pai.visibilidade;

        for(let c in this.infos){
                
                if(this.pai.renderRules[parseInt(c)]){
                    let banco = this.pai.renderRules[parseInt(c)]["b"];
                    let chave = this.pai.renderRules[parseInt(c)]["c"];
                    var codeExt = parseInt(this.infos[c]);
     
                     this.infos[c] = this.pai?.estrangeiros?.[banco]?.[codeExt]?.[chave] || this.infos[c]

    
                }
                
            if(c != "id"){
                   var td = document.createElement("TD")
            var render = this.pai.render[i]
            if(!visibilidade[c]){
                td.classList.add("tdNoLoad")
            }

            switch(render){
                case 0:
                case '0':
                    // Sem render
                    td.innerText = this.infos[c]
           
                    break;
                case 1:
                case '1':
                case 'imagem':
                    // Imagem
                    var imagem = document.createElement("DIV")
                    imagem.classList.add("wi-50", "he-50", "img-thumbnail")
                    td.appendChild(imagem)
                    
                    var img = trataImagem(this.infos[c], "mini");
                    if(img){
                        imagem.style = `background-image: url('${img}');background-size: cover; background-position: center;`
                    }else{
                        imagem.innerHTML = `<i class="bi bi-image-fill"></i>`
                        imagem.classList.add("d-flex", "align-items-center", "justify-content-center")
                    }
                   
                    break;
                case 2:
                case '2':
                case 'data':
                    // Data
                    td.innerText = this.formatarData.bind(this)(this.infos[c])
                    break;
                case 3:
                case '3':
                case 'link':
                    // Link
                     td.innerHTML = `<a href="${this.infos[c]}" target="_blank" class="text-primaria">${this.infos[c]}</a>`
                    break;
                case 4:
                case '4':
                case 'botao':
                    // Botao
                    break;
                case 5:
                case '5':
                case 'qr':
                    // QR Code
                    var div = document.createElement("DIV")
                    div.classList.add("he-100", "wi-100", "qr-code", "bg-carregando") 
                    div.dataset.code = this.infos[c]
                    td.appendChild(div)
                    break;
                case 6:
                case '6':
                case 'personalizado':
                  
                    if(this.pai.regras[c]){
                       var obj = this.pai.regras[c];
                       if(obj[this.infos[c]]){
                           td.innerHTML = obj[this.infos[c]]
                       }else{
                           td.innerText = this.infos[c]
                       }

                    }else{
                         td.innerText = this.infos[c]
                    }
                    break;
                case 7:
                case '7':
                case 'render':
                    td.innerText = this.infos[c]
                    break;
                case 8:
                case '8':
                case 'preco':
                    td.innerText = paraPreco(this.infos[c])
                    break;
            }
            

            this.tr.appendChild(td)
            }
         
            i++;
        }
        
        if(acoes){
            var primario = document.createElement("BUTTON")
            primario.classList.add("btn-tabela-acoes", "btn-ferramenta", "rounded-pill", "border") 
            primario.setAttribute("type","button")
            primario.innerHTML = `<i class="bi bi-three-dots-vertical"></i>`
            
       
            evento(primario, "click", this.showAcoes.bind(this))
            
           
            var td = document.createElement("TD")
            td.style = "width: 100px"
            td.appendChild(primario)
            this.tr.appendChild(td)
        }
        
        if(this.num > 9){
            this.tr.classList.add("pre-hidden")
        }
        
        return this.tr;
    }
    
    showAcoes(){
        

        if(document.getElementsByClassName("blocoAcoesLista").length > 0){
            document.getElementsByClassName("blocoAcoesLista")[0].remove();
        }
        
        var div = document.createElement("UL")
        div.classList.add("dropdown-menu", "blocoAcoesLista", "show", "dropdown-nown")
        
        
      
        for(let c in this.acoes){
            var li = document.createElement("LI")
            
            
            if(this.acoes[c].tipo == "editar" || this.acoes[c].tipo == "vizualizar"){
                var btn = document.createElement("A")
            }else{
                var btn = document.createElement("BUTTON")
            }
            
            
            btn.classList.add("btn","dropdown-item","d-flex","justify-content-start","gap-2","align-items-center","btn-sm", "fs-14")
            
            var icone = document.createElement("I")

     
            var texto = document.createElement("SPAN")
  
            btn.appendChild(icone)
            btn.appendChild(texto)
            li.appendChild(btn)
            
            switch(this.acoes[c].tipo){
                case 'editar':
                    
                    icone.className = "bi bi-pencil-square"
                    texto.innerText = "Editar"
                    btn.href = `${dominio}/${this.acoes[c].rota}/${this.extra.s}`
                    evento(btn, "click", preventLink);
                    this.rotaEdita = this.acoes[c].rota
                    break;
                case 'apagar':
                    icone.className = "bi bi-trash3"
                    texto.innerText = "Apagar"
                     evento(btn, "click", this.apaga.bind(this))
                    break;
                case 'vizualizar':
                    this.rotaVer = this.acoes[c].rota
                    icone.className = "bi bi-eye"
                    texto.innerText = "Visualizar"
                    btn.href = `${dominio}/${this.acoes[c].rota}/${this.extra.p}`
                    evento(btn, "click",  preventLink);
                    break;
                case 'outros':
                    var item = this.acoes[c].rota;
                    icone.className = item.icone
                    texto.innerText = item.texto
                    btn.setAttribute("data-cb", item.callback);
                    evento(btn, "click", this.custom.bind(this))
                        
                    
 
                    break;

            }

            div.appendChild(li)
        }
        
       
       
  
        
        var pai = event.currentTarget.closest("TD")
        pai.classList.add("dropdown")
        pai.appendChild(div)

    }
    
    custom(){
        let cb = event.currentTarget.dataset.cb
        
   
         if (typeof window[cb] === 'function') {
        // Chama a função usando eval
        eval(cb + '(event, this)');
    } else {
        console.log(`Função ${cb} não encontrada ou nome inválido`);
    }
    }
}

class OrdemItem{
    constructor(info, categorias){
        this.info = info;
      
        if(Object.keys(categorias).length > 0){
            this.haveCat = true;
        }
        
        this.cat = "";
        if(this.info.c && categorias[this.info.c]){
            this.cat = categorias[this.info.c]
        }
    }
    
    get(){
        return this.info.id
    }
    
    render(id, index){
        var li = document.createElement("LI")
        this.li = li;
        li.dataset.id = id;

        
        
        
        
        
        var row = document.createElement("DIV")
        row.classList.add("row", "d-flex", "justify-content-between")
        
        
        var col = document.createElement("DIV")
        col.classList.add("col-1", "align-items-center", "justify-content-center", "indexGlobal")
        var span = document.createElement("SPAN")
        span.innerText = index;
        this.index = span;
        col.appendChild(span)
        row.appendChild(col)
        
        
           
        var size = 10;
        if(this.haveCat){
            var col = document.createElement("DIV")
            col.classList.add("col-1", "align-items-center", "justify-content-center", "indexCat")
            var span = document.createElement("SPAN")
            span.innerText = 1;
            this.indexCat = span;
            col.appendChild(span)
            row.appendChild(col)
            size --;
        }
        
        
     
        
        if(this.info.i){
            var obj = JSON.parse(this.info.i)
             var img = document.createElement("DIV")
            img.classList.add("wi-50","he-50","img-thumbnail","d-flex","align-items-center","justify-content-center")
            if(obj.length == 1){
                var imagem = trataImagem(obj[0], "mini")
                if(img){
                    img.style = `background-image: url(${imagem}); background-size: cover`;
                }
                
            }else{
                img.innerHTML = `<i class="bi bi-image-fill"></i>`
            }
            
            var col = document.createElement("DIV")
            col.appendChild(img)
            col.classList.add("col-1", "d-flex", "align-items-center")
            row.appendChild(col)
            
            size--;
           
        }
        
        
        if(this.info.t){
            var col = document.createElement("DIV")
            col.classList.add("col-6", "d-flex", "align-items-center")
            var span = document.createElement("SPAN")
            span.innerText = this.info.t
            col.appendChild(span)
            row.appendChild(col)
            
            size = size - 6;

        }
        
        if(this.haveCat){
            var col = document.createElement("DIV")
            col.classList.add("col-2", "d-flex", "align-items-center")
            var span = document.createElement("SPAN")
            span.innerText =  this.cat
            col.appendChild(span)
            row.appendChild(col)
            size = size - 2;
        }
        
        
        if(size > 0){
            var col = document.createElement("DIV")
            col.classList.add(`col-${size}`)
            row.appendChild(col)
        }
        
        
        
        var col = document.createElement("DIV")
        col.classList.add("col-1")
        var button = document.createElement("BUTTON")
        button.classList.add("btn", "btn-n-secundaria", "btn-nown-style", "btnMove")
        button.innerHTML = `<i class="bi bi-arrows-move"></i>`
        col.appendChild(button)
        row.appendChild(col)
        
        li.appendChild(row)

        
        li.classList.add("list-group-item", "py-2", "itemOrdem")
        return li;

    }
    
    novoCat(array){
        this.indexCat.innerText = array.length
    }
    
    novo(index){
        this.index.innerText = index
    }
    
}

class Ordenador{
    constructor(r){
  
        this.r = r;
        this.r.btns = []
        
        document.getElementById("conteudo").innerHTML = ``
        var header = new HeaderPage(this.r)
        
        var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "btn-n-primaria", "d-flex", "justify-content-center", "gap-2", "align-items-center", "btn-nown-style")
        botao.innerHTML = `<i class="bi bi-arrow-left-circle"></i> Voltar a Tabela`
        evento(botao, "click", ()=>{
            goUrl(window.location.href.split(`${dominio}/`)[1].split("#")[0])
        })
        header.direita.appendChild(botao)
        
        this.ordemInCat = {};
        

        
        this.itens = {};
        this.init.bind(this)();
    }

    init(){
        let request = new Request(`${dominio}/admin/brain.php`);
        request.addData(
            {
                "identificador": this.r.id,
                "modulo": this.r.modulo,
                "master": false,
                "tipo": "ordenador"
            })
        request.send().then((r)=>{

            this.lista = r.lista;
            this.categorias = r.categorias
            
            
            for(let c in this.categorias){
                this.ordemInCat[c] = [];
            }
            
            
            this.render.bind(this)();

        }, (r)=>{
            console.log(r)
        })
    }
    
    render(){
        var card = document.createElement("DIV")
        card.classList.add("card", "card-nown")
        
        
        var body = document.createElement("DIV")
        body.classList.add("card-body")

        
        var div = document.createElement("DIV")

        
        this.ul = document.createElement("UL")
        this.ul.classList.add("list-group","list-group-flush", "listaOrganizadoraItens")
        
        var i = 0;
        while(i < this.lista.length){
            var id = geraId();
            this.itens[id] = new OrdemItem(this.lista[i], this.categorias);

    
            this.ul.appendChild(this.itens[id].render(id, i + 1))
             if(this.lista[i].c){
                this.ordemInCat[this.lista[i].c].push(this.lista[i].id)
                this.itens[id].novoCat(this.ordemInCat[this.lista[i].c]);
            }
            

            i++;
            
        }
        
        div.appendChild(this.ul)
        body.appendChild(div)
        var container = document.createElement("DIV")
        container.classList.add("container")
        container.appendChild(card)
        
       var header = document.createElement("DIV")
       header.classList.add("card-header", "row", "m-0")
       
       let esquerda = document.createElement("DIV")
       esquerda.classList.add("col-6", "d-flex", "align-items-center")
       
       var h2 = document.createElement("H2")
       h2.classList.add("m-0", "fs-18", "fw-500")
       h2.innerText = "Reordenar Itens"
       esquerda.appendChild(h2)
       
       
       
       let direita = document.createElement("DIV")
       
       var input = document.createElement("INPUT")
       input.classList.add("form-control")
       input.placeholder = "Procurar ..."
       input.style.paddingLeft = "38px";
       evento(input, "input", this.search.bind(this))
       this.procura = input;
  
       
       let procurar = document.createElement("DIV")
       procurar.classList.add("position-relative", "col-6")
       procurar.appendChild(input)
       
       var icone = document.createElement("I")
       icone.classList.add("bi","bi-search", "position-absolute", "position-absolute","top-50","translate-middle-y")
       icone.style.left = "26px"
       procurar.appendChild(icone)

       
       direita.appendChild(procurar)
       direita.classList.add("col-6",  "row")
       
       
       if(Object.keys(this.categorias).length > 0){
          var select = document.createElement("SELECT")
          select.classList.add("form-select", "col-6")
          evento(select, "input", this.seleciona.bind(this))
          this.select = select;
          var o = document.createElement("OPTION")
          o.innerText = "Todas as Categorias"
          o.value = "0"
          select.appendChild(o)
          
          for(let c in this.categorias){
               var o = document.createElement("OPTION")
                o.innerText = this.categorias[c]
                o.value = c
                select.appendChild(o)
          }
          
          
          var categoria = document.createElement("DIV")
          categoria.classList.add("col-6")
          categoria.appendChild(select)
          direita.appendChild(categoria)
       }
       
       
       header.appendChild(esquerda)
       header.appendChild(direita)
       
       var footer = document.createElement("DIV")
       footer.classList.add("card-footer", "border-0", "d-flex", "justify-content-between", "align-items-center")
       
       
       var direitaR = document.createElement("DIV")
       direitaR.classList.add("form-check","form-switch")
       
       var id = geraId();
       var input = document.createElement("INPUT")
       input.classList.add("form-check-input")
       input.type = "checkbox"
       input.checked = true;
       input.id = id;
       this.automato = input
       
       var label = document.createElement("LABEL")
       label.innerText = "Salvamente Automático"
       label.classList.add("form-check-label")
       label.setAttribute("for", id)
       direitaR.appendChild(input)
       direitaR.appendChild(label)
       

       
       var btnsSalvar = document.createElement("BUTTON")
       btnsSalvar.classList.add("btn", "btn-n-primaria", "btn-nown-style")
       btnsSalvar.innerText = "Salvar"
       this.btnSalva = btnsSalvar
       evento(btnsSalvar, "click", this.salva.bind(this))

       
       var esquerdaR = document.createElement("DIV")
       esquerdaR.appendChild(btnsSalvar)
       
       footer.appendChild(direitaR)
       footer.appendChild(esquerdaR)
       
       card.appendChild(header)
       card.appendChild(body)
       card.appendChild(footer)
        
  
        document.getElementById("conteudo").appendChild(container)
        
        nownFiles.add(`${dominio}/assets/bibliotecas/sortable/js.js`).then(()=>{
                new Sortable(this.ul, {
                    handle: '.btnMove',
                    animation: 150,
                     onEnd: this.movido.bind(this)
                });
        })
    
    }
    
    search(){
        this.seleciona.bind(this)()
    }
    
    normalizeString(str) {
    return str
        .toLowerCase() 
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

    stringContains(haystack, needle) {
    const normalizedHaystack = this.normalizeString(haystack);
    const normalizedNeedle = this.normalizeString(needle);

    return normalizedHaystack.includes(normalizedNeedle);
}
    
    seleciona(){
        

        var valor = parseInt(this.select ? this.select.value : 0);
        if(valor > 0){
            this.ul.classList.add("categorizado")
        }else{
            this.ul.classList.remove("categorizado")
        }

        for(let c in this.itens){
            
            var aut = true;
            
            if(valor > 0){
            
                if(!this.itens[c].info.c || this.itens[c].info.c != valor){
                     aut = false;
                }

            }


            if(this.procura.value){
                if(!this.stringContains(this.itens[c].info.t , this.procura.value)){
                    aut = false;
                }
                

            }
            
            
            
            if(aut){
                 this.itens[c].li.classList.remove("d-none")
            }else{
                this.itens[c].li.classList.add("d-none")
            }
            
            

        }
    }
    
    movido(){
        var lis = this.ul.getElementsByTagName("li")
        var i = 0;
        var ordem = [];
        
        for(let c in this.ordemInCat){
            this.ordemInCat[c] = []
        }
       
        console.log(this.ordemInCat)
       
        while(i < lis.length){
            var li = lis[i]
            this.itens[li.dataset.id].novo(i + 1)
            
            
            if(this.itens[li.dataset.id].info.c){
                this.ordemInCat[this.itens[li.dataset.id].info.c].push(li.dataset.id) 
                this.itens[li.dataset.id].novoCat(this.ordemInCat[this.itens[li.dataset.id].info.c]);
            }
            
       
            
            
            
            i++;
        }
        
        
        if(this.automato.checked){
            this.salva.bind(this)();
        }
    }
    
    salva(){
        var lis = this.ul.getElementsByTagName("li")
        var i = 0;
        var ordem = [];
        while(i < lis.length){
            var li = lis[i]
            ordem.push(this.itens[li.dataset.id].get())
            i++;
        }
        
        ordem.reverse();

        
          let request = new Request(`${dominio}/admin/brain.php`);
        request.addData(
            {
                "identificador": this.r.id,
                "modulo": this.r.modulo,
                "master": false,
                "tipo": "salvaOrdenador",
                "ordem": JSON.stringify(ordem)
            })
        request.send().then((r)=>{
            iziToast.destroy(); 
            iziToast.success({
                icon: 'bi bi-check-circle',
                title: 'Sucesso',
                message: 'Itens reordenados com sucesso',

            });
        }, (r)=>{
             iziToast.error({
                icon: 'bi bi-x-lg',
                title: 'Atenção',
                message: r.mensagem
            });
        })

    }
}

class Tabelo{
    constructor(r, js, container = false){

        if(window.location.href.split("#").length == 2){
            switch(window.location.href.split("#")[1]){
                case 'ordenar':
                    new Ordenador(r)
                    return;
                    break;
            }
        }
        performance.mark('startTask');

        this.js = js;
        this.r = r
        this.ajax = this.ajax.bind(this)
        this.identificador = r.id
        this.modulo = r.modulo
        this.master = r.master
        this.typingTimer;
        this.doneTypingInterval = 250
        
        this.container = !container ? document.getElementById("conteudo") : container;
        this.estrutura.bind(this)()
        
        this.agrupados = {};
        this.itens = {};
        
        evento(window, "click", this.clickout.bind(this))
        
    }
    
    novaLinha(r){
        console.log("nova linha", r)
        
        /*
       
        */
        
        var data = new FormData();
        data.append("tipo", "tabela")
        data.append("unicidade", r.id)
        this.ajax(data, false).then((r)=>{
            this.listaUm.bind(this)(r)
        }, ()=>{
            this.naoEncontrado.bind(this)();
            alert(`Erro na Tabela: ${obj.mensagem}`);
        })

    }
    
    listaUm(r){
        var linha = new LinhaTabelo(r.lista[0], this, 0);
        var row = linha.render(this.acoes);
    
         this.table.row.add($(row));
         this.table.draw(false);
       
        
    }
    
    allItems(){
        this.massificador = true;
    }
    
    acoesEmGrupo(){
        var tamanho =  Object.keys(this.agrupados).length;
        
        if(tamanho == 0){
            this.controleMultiplo.hide();
        }else{
            this.controleMultiplo.show();
        }
        
        this.contaSel.innerText = tamanho == 1 ? "1 item selecionado" : `${tamanho} itens selecionados`

        this.ajustaMarcacao.bind(this)()
    }
    
    clickout(){
        if(event.target.classList.contains("btn-tabela-acoes") || event.target.closest(".btn-tabela-acoes")){
           
        }else{
            if(document.getElementsByClassName("blocoAcoesLista").length == 1){
                document.getElementsByClassName("blocoAcoesLista")[0].remove();
            }
        }
    }
    
    createButton(iconClass, text, grupo, tipo) {
        var button = document.createElement("button");
        button.dataset.grupo = grupo
        button.dataset.tipo = tipo
        button.classList.add("btn", "btn-ferramenta", "d-flex", "justify-content-start", "gap-2", "align-center");
        var icon = document.createElement("i");
        icon.classList.add("bi" , iconClass, "fs-18");
        var span = document.createElement("span");
        span.classList.add("fs-14", "text-uppercase");
        span.textContent = text;
        button.appendChild(icon);
        button.appendChild(span);
        evento(button, "click", this.eventoTabela.bind(this))
        return button;
    }
    
    add(item){
        this.itens[item.item.hash] = item
    }
    
    salvarMassa(){
        this.inputsMassa.classList.add("d-none")
        var data = {};
        for (let chave in this.itens) {
            var item = this.itens[chave]
            var input = item.input
            var item = item.item
         
          
            switch(item.tipo){
                case 'select':
                    if(input.multiple){
                        var array = [];
                        var opt = input.getElementsByTagName("option")
                        var z = 0;
                        while(z < opt.length){
                            if(opt[z].selected){
                                array.push(opt[z].value)
                            }
                            z++;
                        }

                        data[item.hash] = JSON.stringify(array);
                    }else{
                        if(input.value != 0){
                            data[item.hash] = input.value
                        }
                        
                    }
                    break;
                case 'switch':
                     data[item.hash] = input.checked ? true : false;
                    break;
                default:
                var valor = input.value.trim();
                if(valor){
                   data[item.hash] = input.value 
                    input.value = "";
                }
                 
                break;
            }
           
        };
        
        
        this.salvaFast(data)
       

    
    }
    
    salvaFast(dados){
         for(let index in this.agrupados){
             var item = this.agrupados[index]
              
              //this.table.row(item).remove().draw(false);
               var entrada = {
            tipo: "editar",
            hash: index,
            identificador: this.tabelaImportar,
            modulo: this.modulo,
            master: this.master,
            data: JSON.stringify(dados)
        }
        let request = new Request("/admin/brain.php");
        request.addData(entrada)
        request.send().then((r)=>{
            this.agrupados[index].getElementsByClassName("grupoMark")[0].checked = false
            delete this.agrupados[index];
            var tamanho =  Object.keys(this.agrupados).length;
             iziToast.success({
                      title: 'Sucesso',
                      message: 'Item Atualizado com Sucesso',
                      icon: 'bi bi-check-circle'
                  });
             
             if(tamanho > 0){
                      this.salvaFast(dados);
                  }else{
                      this.acoesEmGrupo.bind(this)()
                  }
        },(r)=>{
              delete this.agrupados[index];
                   iziToast.error({
                      title: 'Erro',
                      message: 'Não foi possível atualizar o item.',
                      icon: 'bi bi-x-circle-fill'
                  });
                  var tamanho =  Object.keys(this.agrupados).length;
                  if(tamanho > 0){
                        this.salvaFast(dados);
                  }else{
                      this.acoesEmGrupo.bind(this)()
                  }
        })

              
              
              
              
          
             break;
         }
         
       
    }
    
    edicaoEmMassa(){
        if(!this.massificador){
            var data = new FormData();
        data.append("tipo", "edicaoMassaEstrutura")
        this.ajax(data, false).then((r)=>{
            nownFiles.add(`${dominio}/assets/js/renderForm.js`).then(()=>{
                
                this.tabelaImportar = r.tabela
                var finais = r.final
                var flex = document.createElement("DIV")
                flex.classList.add("d-flex", "flex-column", "gap-4")
                for(let c in finais){
                    var item = finais[c]
                    
                    var input = new InputRenderizado(item, item.hash, this);
                    flex.appendChild(input.render())
                }
                
                new Mascaras();
                this.inputsMassa.classList.remove("d-none")
                
                var dflex = document.createElement("DIV")
                dflex.classList.add("d-flex", "justify-content-between", "align-items-center");
                
                var btnsalvar = document.createElement("BUTTON")
                btnsalvar.innerHTML = `<i class="bi bi-floppy"></i> Salvar`
                evento(btnsalvar, "click", this.salvarMassa.bind(this))
                btnsalvar.classList.add("btn", "btn-n-primaria", "btn-nown-style")
                dflex.appendChild(btnsalvar)
                
                flex.appendChild(dflex)
                
                this.inputsMassa.appendChild(flex)
                
            })
            
        }, (r)=>{
            console.log(r)
        })
        }
        else{
            if(this.inputsMassa.classList.contains("d-none")){
                  this.inputsMassa.classList.remove("d-none")
            }else{
                  this.inputsMassa.classList.add("d-none")
            }
          
        }
        
    }
    
    estrutura(){
        var box = document.createElement("DIV")
        if(this.container.id == "conteudo"){
           box.classList.add("card", "border-0", "card-nown")
        }
        
        box.id = "boxList"
        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "bg-transparent", "border-0", "m-0")
        
        
        var pai = document.createElement("DIV")
        pai.classList.add("d-xl-flex", "justify-content-between", "align-items-center")
        
        var filtros = document.createElement("DIV")
        filtros.classList.add("collapse")
        filtros.id = "filtrosAvancados";
        filtros.innerHTML = `
        <div class="card mt-4 border-0">
            <div class="card-body">Filtros</div>
        </div>
        
        `
        
         var colunas = document.createElement("DIV")
        colunas.classList.add("collapse")
        colunas.id = "filtrosColunas";
        colunas.innerHTML = `
        <div class="card mt-4 border-0">
            <div class="card-body d-flex justify-content-start gap-1 align-items-center overflow-x-auto"><span>Colunas:</span></div>
        </div>
        `
        
        this.seletorMultiplo = document.createElement("DIV")
        this.seletorMultiplo.classList.add("collapse")
        this.seletorMultiplo.id = "seletorMultiplo";
        
        const card = document.createElement('div');
        card.className = 'card mt-4 border-0';
        
        const cardBody = document.createElement('div');
        cardBody.className = 'card-body d-flex justify-content-between gap-1 align-items-center overflow-x-auto';
        
        this.contaSel = document.createElement('span');
        this.contaSel.textContent = '0 itens selecionados';
        
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add("d-flex", "gap-2")
        const deleteButton = document.createElement('button');
        deleteButton.className = 'btn-ferramenta border-danger border text-danger';
        deleteButton.innerHTML = '<i class="bi bi-trash"></i> Deletar';
        evento(deleteButton, "click", this.deletaTudo.bind(this))
        
        const editButton = document.createElement('button');
        editButton.className = 'btn-ferramenta border-primary border text-primary';
        editButton.innerHTML = '<i class="bi bi-pencil-square"></i> Editar';
        evento(editButton, "click", this.edicaoEmMassa.bind(this))
        
        buttonContainer.appendChild(deleteButton);
        buttonContainer.appendChild(editButton);
        
        cardBody.appendChild(this.contaSel);
        cardBody.appendChild(buttonContainer);
        
        this.inputsMassa = document.createElement("DIV")
        this.inputsMassa.classList.add("card-body", "d-none")
        card.appendChild(cardBody);
        card.appendChild(this.inputsMassa);
        
        this.seletorMultiplo.innerHTML = ''; 
        this.seletorMultiplo.appendChild(card);
        this.controleMultiplo = new bootstrap.Collapse(this.seletorMultiplo, {
  toggle: false
})
        
     
        
        var esquerda = document.createElement("DIV")
        esquerda.classList.add("d-flex", "justify-content-start", "gap-2", "mb-2", "mb-xl-0")
        var direita = document.createElement("DIV")
        
        var quanti = document.createElement("DIV")
        
        var quantidade = document.createElement("SELECT")
        quantidade.classList.add("form-select", "rounded-pill", "text-center")
        evento(quantidade, "input", this.quantificou.bind(this))
        var tamanhos = [10, 20, 50, 100, 200, "Todos"];
        for(let c in tamanhos){
            var q = tamanhos[c]
            var option = document.createElement("OPTION")
            option.value = q
            option.innerText = q
            quantidade.appendChild(option)
        }
        quanti.appendChild(quantidade)
        
        
        esquerda.appendChild(quanti)
        
        
        var relative = document.createElement("DIV")
        relative.classList.add("position-relative")
        
        var input = document.createElement("INPUT")
        input.type = "search"
        input.classList.add("form-control", "ps-5", "rounded-pill", "fuzzy-search")
        input.placeholder = "Procurar ..."
        evento(input, "keyup", this.procurar.bind(this))
        evento(input, "search", this.procurarFast.bind(this))
        relative.appendChild(input)
        
        
        
        
        var span = document.createElement("SPAN")
        span.innerHTML = `<i class="bi bi-search"></i>`
        span.classList.add("position-absolute","top-50","translate-middle")
        span.style.left = "30px"
        relative.appendChild(span)
        
        esquerda.appendChild(relative)
        
        
        var divDireita = document.createElement("DIV")
        divDireita.classList.add("d-flex", "justify-content-between", "gap-2")
        divDireita.id = "ferramentasTabela"
        
 
        direita.appendChild(divDireita)
        
        pai.appendChild(esquerda)
        pai.appendChild(direita)
        header.appendChild(pai)
        header.appendChild(filtros)
        header.appendChild(colunas)
        header.appendChild(this.seletorMultiplo)
        var body = document.createElement("DIV")
        body.classList.add("card-body")
        
        var i = 0;
        this.paiCarregando = document.createElement("DIV")
        this.paiCarregando.classList.add("d-flex", "flex-column", "gap-2", "justify-content-between")
        while(i < 10){
             const load = document.createElement('div');
             load.classList.add('bg-carregando', "he-50");
             this.paiCarregando.appendChild(load);
            
            
            i++;
        }
        
        
    
        
        
        
        
        body.appendChild(this.paiCarregando)
       
        this.body = body;
        
        
        
        box.appendChild(header)
    
        box.appendChild(body)
        
        this.footerPre.bind(this)()
        
        this.cont = document.createElement("DIV")
        if(this.container.id == "conteudo"){
           this.cont.classList.add("container") 
        }
        
        
        this.barra = document.createElement("DIV")
        this.barra.classList.add("progressoTabela")
        this.barra.width = "0px"
        this.cont.appendChild(box)
        this.cont.appendChild(this.barra)
        this.container.innerHTML = "";
        new  HeaderPage(this.r);
        this.container.appendChild(this.cont)
        
      
        var data = new FormData();
        data.append("tipo", "tabela")
        this.ajax(data, false).then((r)=>{
            this.lista.bind(this)(r)
        }, ()=>{
            this.naoEncontrado.bind(this)();
            alert(`Erro na Tabela: ${obj.mensagem}`);
        })
    }
    
    deletaTudo(){
         var tamanho =  Object.keys(this.agrupados).length;
         if(tamanho > 0){
              Swal.fire({
                  title: "Muita Atenção!",
                  text: tamanho == 1 ? `Você quer apagar o item que está selecionado?` : `Você quer apagar ${tamanho} itens selecionados?`,
                  icon: "question",
                  showCancelButton: true,
                  confirmButtonText: `Apagar`,
                  cancelButtonText: `Cancelar`,
                  
              }).then((r)=>{
                  if(r.isConfirmed){
                      this.removeFast();
                  }
              });
         }
    }
    
    removeFast(){
         for(let index in this.agrupados){
             var item = this.agrupados[index]
              this.table.row(item).remove().draw(false);
              var data = new FormData();
              data.append("tipo", "apagar")
              data.append("hash", index)
              this.ajax(data).then((r)=>{
                  delete this.agrupados[index];
                  var tamanho =  Object.keys(this.agrupados).length;
                  iziToast.success({
                      title: 'Sucesso',
                      message: 'Item Apagado com Sucesso',
                      icon: 'bi bi-check-circle'
                  });
                  if(tamanho > 0){
                      this.removeFast();
                  }else{
                      this.acoesEmGrupo.bind(this)()
                  }
              }, (r)=>{
                   delete this.agrupados[index];
                   iziToast.error({
                      title: 'Erro',
                      message: 'Não foi possível apagar o item.',
                      icon: 'bi bi-x-circle-fill'
                  });
                  var tamanho =  Object.keys(this.agrupados).length;
                  if(tamanho > 0){
                      this.removeFast();
                  }else{
                      this.acoesEmGrupo.bind(this)()
                  }
                 
              })
             break;
         }
         return;
       
    }
   
    eventoTabela(){
        var btn = event.currentTarget
        let mensagem = "";
        if(btn.dataset.grupo == "exportar"){
            switch(btn.dataset.tipo){
                case 'copiar':
                    document.getElementsByClassName("buttons-copy")[0].click();
                    mensagem = "Conteúdo Copiado"
                    break;
                case 'excel':
                    document.getElementsByClassName("buttons-excel")[0].click();
                    mensagem = "Excel Gerado. Aguarde o download."
                    break;
            }
             iziToast.success({
            icon: "bi bi-check-circle",
    title: `Sucesso`,
    message: mensagem,
    timeout: 3000,
                 
             });
            this.modalExportar.hide();
        }
    }
    
    exportar(){

        if (!document.getElementById("modalExportar")) {
    var modal = document.createElement("div");
    modal.classList.add("modal", "fade");
    modal.id = "modalExportar";
    modal.setAttribute("tabindex", -1);
    modal.setAttribute("aria-hidden", "true");

    // Criação do modal-dialog
    var modalDialog = document.createElement("div");
    modalDialog.classList.add("modal-dialog", "modal-dialog-centered", "modal-sm");

    // Criação do modal-content
    var modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");

    // Criação do modal-body
    var modalBody = document.createElement("div");
    modalBody.classList.add("modal-body");

    var header = document.createElement("div");
    header.classList.add("d-flex", "justify-content-between", "align-items-center");

    var title = document.createElement("h2");
    title.classList.add("fs-20", "m-0");
    title.textContent = "Exportar";

    var closeButton = document.createElement("button");
    closeButton.type = "button";
    closeButton.classList.add("btn-close");
    closeButton.setAttribute("data-bs-dismiss", "modal");
    closeButton.setAttribute("aria-label", "Close");

    header.appendChild(title);
    header.appendChild(closeButton);

    var buttonContainer = document.createElement("div");
    buttonContainer.classList.add("d-flex", "flex-column", "gap-2", "mt-4", "mb-2");

    

    buttonContainer.appendChild(this.createButton("bi-filetype-pdf", "PDF", "exportar", "pdf"));
    buttonContainer.appendChild(this.createButton("bi-printer-fill", "Imprimir",  "exportar", "imprimir"));
    buttonContainer.appendChild(this.createButton("bi-filetype-csv", "CSV",  "exportar", "csv"));
    buttonContainer.appendChild(this.createButton("bi-file-earmark-excel", "Excel",  "exportar", "excel"));
    buttonContainer.appendChild(this.createButton("bi-filetype-html", "HTML",  "exportar", "html"));
    buttonContainer.appendChild(this.createButton("bi-copy", "Copiar",  "exportar", "copiar"));

    modalBody.appendChild(header);
    modalBody.appendChild(buttonContainer);

    modalContent.appendChild(modalBody);
    modalDialog.appendChild(modalContent);
    modal.appendChild(modalDialog);

    document.getElementById("conteudo").appendChild(modal);

    this.modalExportar = new bootstrap.Modal(document.getElementById('modalExportar'), {
        keyboard: false
    });
}
        
        this.modalExportar.show();
        

    }
    
    filtrar(){
        alert("filtrando");
    }
    
    go(termo){
         this.table.search(termo).draw();
    }
    
    morre(){
        this.dados = false;
        this.table = false;
        for(let c in this){
            this[c] = false;
        }
    
    }
    
    procurar(){
        let termo = event.currentTarget.value
     
         clearTimeout(this.typingTimer); 
          this.typingTimer = setTimeout( ()=> {
        this.go.bind(this)(termo)
    }, this.doneTypingInterval);
  
    }
    
    procurarFast(){
        this.go.bind(this)("")
    }
    
    quantificou(){
        var quantidade = event.currentTarget.value == "Todos" ? this.dados.length : event.currentTarget.value
        this.table.page.len(quantidade).draw();
    }
    
    importar(){
        if(!this.modalImportar){
            var div = document.createElement("DIV")
            div.classList.add("modal","fade", "modal-nown")
            div.setAttribute("id","modalImportar");
            div.setAttribute("tabindex","-1");
            div.setAttribute("aria-hidden","true");
            
            
            var dialog = document.createElement("DIV")
            dialog.classList.add("modal-dialog","modal-dialog-centered","modal-dialog-scrollable")
            dialog.id = "sizeImporter"
            
            var primeiro = document.createElement("DIV")
            primeiro.classList.add("modal-content")
            
            var header = document.createElement("HEADER")
            header.classList.add("modal-header", "p-3")
            
            var h2 = document.createElement("H2")
            h2.classList.add("modal-title","fs-20","text-center","d-block","w-100","m-0")
            h2.innerText = "Importar Conteúdo"
            header.appendChild(h2)
            
            var fechar = document.createElement("BUTTON")
            fechar.classList.add("btn")
            fechar.innerText = "x"
            fechar.setAttribute("type", "button")
            fechar.setAttribute("data-bs-dismiss","modal")
            header.appendChild(fechar)
            
            var body = document.createElement("DIV")
            body.classList.add("modal-body")
            
            var container = document.createElement("DIV")
            container.classList.add("containerInput")
            
            var file = document.createElement("INPUT")
            file.setAttribute("type","file")
            file.setAttribute("id","arquivoImportacao")
            file.setAttribute("accept",".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
            container.appendChild(file)
            
            body.appendChild(container)
            
             primeiro.appendChild(header)
             primeiro.appendChild(body)
             
             
             var rodape = document.createElement("div")
             rodape.classList.add("modal-footer")
             
             var botaomodelo = document.createElement("BUTTON")
             botaomodelo.innerText = "Baixar Documento Modelo"
             botaomodelo.classList.add("btn-ferramenta","border-primary","border", "text-primary")
             rodape.appendChild(botaomodelo)

             
             primeiro.appendChild(rodape)
            
            
            
            
            
            var segundo = document.createElement("DIV")
            segundo.classList.add("modal-content", "d-none")
            
             var header = document.createElement("HEADER")
            header.classList.add("modal-header")
            
            var h2 = document.createElement("H2")
            h2.classList.add("modal-title","fs-25","text-center","d-block","w-100")
            h2.innerText = "Iniciar Importação"
            header.appendChild(h2)
            
            var body = document.createElement("DIV")
            body.classList.add("modal-body")
            
            var render = document.createElement("DIV")
            render.id = "renderImport"
            body.appendChild(render)
            
            
            segundo.appendChild(header)
            segundo.appendChild(body)
            
            
            var footer = document.createElement("DIV")
            footer.classList.add("modal-footer")
            
            var cancelar = document.createElement("BUTTON")
            cancelar.classList.add("btn","btn-secondary")
            cancelar.setAttribute("type", "button")
            cancelar.setAttribute("data-bs-dismiss","modal")
            cancelar.innerText = "Cancelar"
            
            
            var enviar = document.createElement("BUTTON")
            enviar.innerText = "Importar"
            enviar.setAttribute("type","button")
            enviar.classList.add("btn","btn-n-primaria");
            evento(enviar, "click", this.startImport.bind(this))
            
        
            footer.appendChild(cancelar)
            footer.appendChild(enviar)
            
            segundo.appendChild(footer)
            

         
         
            dialog.appendChild(primeiro)
            dialog.appendChild(segundo)
            div.appendChild(dialog)

            

            document.getElementById("conteudo").appendChild(div)
            this.fileImportar = document.getElementById("arquivoImportacao")
            
            this.modalImportar = new bootstrap.Modal('#modalImportar', {
                backdrop: 'static', keyboard: false
            
            })
            
            
             this.array = [
            `${dominio}/assets/aplicativo/filepond/min.js`,
            `${dominio}/assets/aplicativo/filepond/min.css`
            ];
            

            
            
            var arquivos = [
                "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js",
                "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js",
                `${dominio}/assets/aplicativo/filepond/min.js`,
                `${dominio}/assets/aplicativo/filepond/min.css`
                ]
            
            nownFiles.add(arquivos).then(()=>{
                 
                
                   FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageResize,
            FilePondPluginImageTransform,
            FilePondPluginFilePoster,
            FilePondPluginPdfPreview

            );
            
            
            var txt = `Arraste ou solte o arquivo de importação. São aceitos CSV E Excel(xls)`

            FilePond.setOptions({
             labelIdle: `${txt}`,
             
                       allowImageResize: true,
                       imageResizeTargetWidth: 1500,
                       labelInvalidField: "Campo contém arquivos inválidos",
                       labelFileWaitingForSize: "Aguardando tamanho",
                       labelFileSizeNotAvailable: "Tamanho não disponível",
                       labelFileLoading: "Carregando",
                       labelFileLoadError: "Erro ao carregar",
                       labelFileProcessing: "Processando",
                       labelFileProcessingComplete: "Upload concluído",
                       labelFileProcessingAborted: "Upload cancelado",
                       labelFileProcessingError: "Erro durante o upload",
                       labelFileProcessingRevertError: "Erro ao reverter",
                       labelFileRemoveError: "Erro ao remover",
                       labelTapToCancel: "toque para cancelar",
                       labelTapToRetry: "toque para tentar novamente",
                       labelTapToUndo: "toque para desfazer",
                       labelButtonRemoveItem: "Remover",
                       labelButtonAbortItemLoad: "Abortar",
                       labelButtonRetryItemLoad: "Tentar novamente",
                       labelButtonAbortItemProcessing: "Cancelar",
                       labelButtonUndoItemProcessing: "Desfazer",
                       labelButtonRetryItemProcessing: "Tentar novamente",
                       labelButtonProcessItem: "Upload",
                       credits: {},
                       acceptedFileTypes: ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                       server: {
        process: (fieldName, file, metadata, load, error, progress, abort) => {
            this.converte.bind(this)(file);
            

        },

    },
                
            });
                
                
                
              this.pond = FilePond.create(this.fileImportar , {});
            })
            
            
        }
       
         document.getElementById('renderImport').innerHTML = "";
        document.getElementById("modalImportar").getElementsByClassName("modal-content")[0].classList.remove("d-none")
        document.getElementById("modalImportar").getElementsByClassName("modal-content")[1].classList.add("d-none")
        document.getElementById("modalImportar").classList.remove("modal-full")
        
        
        
         this.modalImportar.show();
        
        
        
    }
    
    startImport(){
        console.log(this.fileEstrutura);
        
        var selects = document.getElementsByClassName("mapImportacao")
        
        var mapa = {};
        var aut = true;
        var i = 0;
        while(i < selects.length){
            if(selects[i].value != "0"){
                if(mapa[selects[i].value]){
                    aut = false;
                }else{
                    mapa[selects[i].value] = selects[i].dataset.target
                }
                
            }
            
            i++;
        }
        
        if(!aut){
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Um campo foi usado mais de uma vez",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
    
    
        if(Object.keys(mapa).length == 0){
             Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Você precisa mapear os dados a serem importados",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
        
        event.currentTarget.setAttribute("disabled", "")
        this.mapaImportacao = mapa
        this.importaFila(0);
    }
    
    importaFila(index){
    
        
        var data = {};
        
        for(let c in this.mapaImportacao){
            data[c] = this.fileEstrutura[index][this.mapaImportacao[c]]
        }
        
 
     
        let request = new Request(`${dominio}/admin/brain.php`)
        request.addData({
            "tipo": "salvar",
            "hash": false,
            "identificador": this.rImport.identificador,
            "data": JSON.stringify(data),
            "modulo": this.rImport.modulo,
            "master": false
        })
        request.send().then((r)=>{
            var proximo = index + 1;
            if(this.fileEstrutura[proximo]){
                this.importaFila(proximo)
                iziToast.success({
                    icon: 'bi bi-check-circle-fill',
    title: 'Sucesso',
    message: 'Conteudo Importado'
});
            }else{
                  this.modalImportar.hide();
                
            }

        }, (r)=>{
             var proximo = index + 1;
              if(this.fileEstrutura[proximo]){
                this.importaFila(proximo)
                iziToast.error({
                     icon: 'bi bi-x-lg',
                     title: 'Erro',
                     message: r.mensagem
                });
            }else{
                  this.modalImportar.hide();
                
            }
             
             
             
      
            console.log("erro", r)
        })
        
    
        
        


    }
    
    estruturaImportacao(file, r){
        
        this.rImport = r;
        

           this.pond.removeFile(file);

    var reader = new FileReader();
    

      reader.onload = (e) =>{

        try{
            var data = e.target.result;
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        var table = document.createElement('table');
        table.id = "tabelaimportacao"
        var thead = document.createElement('thead');
        var theadControl  = document.createElement('thead'); 
        
        var tbody = document.createElement('tbody');

        workbook.SheetNames.forEach((sheetName)=> {
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            this.fileEstrutura = XL_row_object

            // Adiciona o cabeçalho
            var headers = Object.keys(XL_row_object[0]);
            var headerRow = document.createElement('tr');
            headers.forEach(function(header) {
                var th = document.createElement('th');
                th.appendChild(document.createTextNode(header));
                headerRow.appendChild(th);
                
                var th = document.createElement('th');
                var select = document.createElement("SELECT")
                select.classList.add("mapImportacao")
                select.dataset.target = header;
                select.classList.add("form-select")
                
                var option = document.createElement("OPTION")
                option.value = 0
                option.innerText = "Selecione uma opção"
                select.appendChild(option)
                
                var foco = false;
                for(let c in r.entradas){
                    var option = document.createElement("OPTION")
                    option.value = c
                    option.innerText = r.entradas[c].n
                    if(r.entradas[c].n == header){
                        foco = c
                    }
                    
                    option.dataset.o = r.entradas[c].o
                    option.dataset.t = r.entradas[c].t

                    select.appendChild(option)
                }
                
                if(foco){
                    select.value = foco
                }else{
                    invalido(select, false , "");
                }
                

                
                
                th.appendChild(select);
                theadControl.appendChild(th);
            });
            thead.appendChild(headerRow);

            // Adiciona as linhas de dados
            XL_row_object.forEach(function(rowObject) {
                var row = document.createElement('tr');
                headers.forEach(function(header) {
                    var cell = document.createElement('td');
                    cell.appendChild(document.createTextNode(rowObject[header]));
                    row.appendChild(cell);
                });
                tbody.appendChild(row);
            });
        });

        table.appendChild(thead);
        table.appendChild(theadControl);
        table.appendChild(tbody);
        
        setTimeout(()=>{
         document.getElementById('renderImport').appendChild(table);
        document.getElementById("modalImportar").getElementsByClassName("modal-content")[0].classList.add("d-none")
        document.getElementById("modalImportar").getElementsByClassName("modal-content")[1].classList.remove("d-none")
        document.getElementById("modalImportar").classList.add("modal-full")
        }, 100)
        }catch(e){
             iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação', message: "Arquivo não suportado"});
        }
    };


    reader.onerror = function(ex) {

       iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação',message: r.mensagem});
    };
    
      reader.readAsBinaryString(file);
    }
    
    converte(file) {
        

        let request = new Request(`${dominio}/admin/importador.php`)
        request.addData({
            "id": this.r.id,
            "modulo": this.r.modulo,
            "acao": "map"
        })
        request.send().then((r)=>{
            this.estruturaImportacao.bind(this)(file, r);
        }, (r)=>{
            this.pond.removeFile(file);
            iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação',message: r.mensagem});
        })

        
 
        
    }
    
    footerPre(){
            this.fakeFooter = document.createElement("DIV")
        this.fakeFooter.innerHTML = `
        <div class="dataTables_info" id="eJUWoeaWyI_info" role="status" aria-live="polite"><div class="bg-carregando wi-300 he-15"></div></div>
        <div class="dataTables_paginate paging_simple_numbers">
    <a class="paginate_button previous disabled" ><i class="bi bi-chevron-left"></i></a>
    <span>
        <div class="wi-40 rounded"><div class="ratio ratio-1x1 bg-carregando"></div></div>
        <div class="wi-40 rounded"><div class="ratio ratio-1x1 bg-carregando"></div></div>
        <div class="wi-40 rounded"><div class="ratio ratio-1x1 bg-carregando"></div></div>
    </span>
    <a class="paginate_button next" ><i class="bi bi-chevron-right"></i></a>
</div>

        `
         this.body.appendChild(this.fakeFooter)
    }
    
    marcacao(event) {

    var filhos = document.getElementsByClassName("grupoMark");
  
    var evento = new Event("input")
    if(event.currentTarget.checked) {
        // Seleciona todos os filhos
        for(var i = 0; i < filhos.length; i++) {
            filhos[i].checked = true;
             filhos[i].dispatchEvent(evento); 
        }
    } else {
        // Desmarca todos os filhos
        for(var i = 0; i < filhos.length; i++) {
            filhos[i].checked = false;
            filhos[i].dispatchEvent(evento); 
        }
    }
}

    ajustaMarcacao(){
        if(this.multiplo){
          setTimeout(()=>{
                var filhos = document.getElementsByClassName("grupoMark");
            var pai = document.getElementsByClassName("grupoMarkMaster")[0]
            var marcados = 0;
            var i = 0;
            while(i < filhos.length){
                if(filhos[i].checked){
                    marcados++;
                }
                
                i++;
            }
            
            if(marcados == filhos.length){
                pai.checked = true;
            }else{
                pai.checked = false;
            }
          }, 200)

        }

    }
    
    cabecalhoHTML(){
        var thead = document.createElement("THEAD")
        var tr = document.createElement("TR")
        
        var th = document.createElement("TH")
        th.classList.add("d-none")
        th.innerText = "Id"
        tr.appendChild(th)
        
          var index = 1;
          
        if(this.multiplo){
            var th = document.createElement("TH")
            th.style.width = "20px"
            this.marcaTodos = document.createElement("INPUT")
            this.marcaTodos.type = "checkbox"
            this.marcaTodos.classList.add("form-check-input", "grupoMarkMaster")
            evento(this.marcaTodos, "input", this.marcacao.bind(this))
            th.appendChild(this.marcaTodos)
            tr.appendChild(th)
             var index = 2;
             var visibilidade = [0, 1, ...this.visibilidade]
        }else{
            var visibilidade = [0, ...this.visibilidade]
        }
            
        
    
   
      
        for(let c in this.cabecalho){
            let th = document.createElement("TH")
            th.classList.add("sort") 
            th.setAttribute("data-sort",this.cabecalho[c])
            th.innerText = this.cabecalho[c]
            
            
           
            if(this.rulesHeader.colunas){
                var botao = document.createElement("BUTTON")
                var span = document.createElement("SPAN")
                span.innerText = this.cabecalho[c]
                var i = document.createElement("I")
                if(visibilidade[index]){
                    i.classList.add("bi","bi-eye")
                }else{
                    i.classList.add("bi","bi-eye-slash")
                }
               
                botao.classList.add("btn", "d-flex", "justify-content-center", "gap-1", "align-items-center", "btn-sm")
                evento(botao, "click", this.controlVisibilidade.bind(this))
                botao.appendChild(i)
                botao.appendChild(span)
                
                botao.dataset.i = index
                botao.dataset.v = visibilidade[index]
                document.getElementById("filtrosColunas").getElementsByClassName("card-body")[0].appendChild(botao)
                index++;
            }
            
            
            
            tr.appendChild(th)
        }
        
        
        if(this.acoes){
            let th = document.createElement("TH")
            th.style.width = "120px"
            th.innerText = "Ação"
            tr.appendChild(th)
        }
        
        thead.appendChild(tr)
        this.tableInicial.appendChild(thead);
 
    }
    
    controlVisibilidade(){
        var btn = event.currentTarget;
        var index = btn.dataset.i
        
        let column = this.table.column(index);
        var icone = btn.getElementsByTagName("I")[0]
        icone.className = "";
        if(btn.dataset.v == 1){
            btn.dataset.v = 0;
            column.visible(false);
            icone.classList.add("bi","bi-eye-slash")
        }else{
            btn.dataset.v = 1;
            column.visible(true);
            icone.classList.add("bi","bi-eye")
        }
        
        
        

    }
    
    setApublico(url){
        this.rotaVer = url
    }
    
    bodyHTML(){
        this.loadplus = false;
        var body = document.createElement("TBODY")
        body.classList.add("list") 
        var fragmento = document.createDocumentFragment();

        var c = 0;
        while(c < this.dados.length){
            var linha = new LinhaTabelo(this.dados[c], this, c);
            var row = linha.render(this.acoes);

            fragmento.appendChild(row)
            c++;
            if(c == 50){
                this.loadplus = c;
                break;
            }
        } 

        body.appendChild(fragmento);
        this.tableInicial.appendChild(body)
    }
    
    fullComplete(){
        this.fakeFooter.remove();
        if(this.js){
            this.js.pai.loadscript(this.js.obj)
        }
        this.tableInicial.style = "";
        
        if(this.loadplus){
            setTimeout(()=>{this.carregaMais.bind(this)()}, 10)
        }
        
        // Task to measure
performance.mark('endTask');
performance.measure('Task duration', 'startTask', 'endTask');

 const measures = performance.getEntriesByType('measure');

  // Exibir a duração da tarefa medida
  measures.forEach((measure) => {
    console.log(`Nome: ${measure.name}`);
    console.log(`Duração: ${measure.duration}ms`);
  });
    }
    
    carregaMais() {
    let c = 50;
    const interval = 50; 
    const batchSize = 500; 
    const total = this.dados.length - 50; 
    
    const lotesTotais = Math.ceil(total / batchSize);
    const tempoTotalEstimado = lotesTotais * interval;
    const tempoMedio = tempoTotalEstimado / 1000 * 2; 
    
    
    this.barra.style.transition = `width ${tempoMedio}s ease`;
    this.barra.classList.add("ativo")



    const addRows = () => {
        let fragment = document.createDocumentFragment();
        let end = Math.min(c + batchSize, this.dados.length); 
        
        while (c < end) {
            var linha = new LinhaTabelo(this.dados[c], this, c);
            var row = linha.render(this.acoes);

            fragment.appendChild(row);
            c++;

            // Atualizar a barra de progresso

        }

        // Adicionar as linhas do fragmento ao DataTable
        $(fragment).children().each((index, element) => {
            this.table.row.add($(element));
        });

        if (c < this.dados.length) {
            setTimeout(addRows, interval); 
            this.table.draw(false); 
        } else {
            this.table.draw(); 
            this.barra.classList.remove("ativo")
        }
    };

    addRows();
    }
    
    pegaCor(tipo, padrao, cor, corum, cordois, direcao) {
        switch(parseInt(tipo)){
            case 1:
                return {"color": padrao};
                break;
            case 2:
                 return {"color": cor};
                break;
            case 3:
                return {
                    "gradient":{
                        "type":"linear",
                        "rotation": direcao,
                        "colorStops":[
                            {
                                "offset":0,
                                "color":corum
                            },
                            {
                                "offset":1,
                                "color":cordois
                            }
                        ]
                    }
                }
                break;
        }
    }
    
    clickqr(){
        this.configQr.data = event.currentTarget.dataset.url
        this.configQr.width = 1000;
        this.configQr.height = 1000;
        
        
        const qrCode = new QRCodeStyling(this.configQr);
        qrCode.download({ name: "qrcode", extension: "png" });

    }

    lista(r){
        
        
        var local = JSON.parse(pegaLocal("nown"))?.config?.estilo?.["qr-codes"] || {};
        
        var objQrCode = {
                    width: 100,
                    height: 100,
                    type: "svg",
                    margin: parseFloat(local?.margem || 0),
                    qrOptions:{
                        "typeNumber":"0",
                        "mode":"Byte",
                        "errorCorrectionLevel": (local?.niveldecomplexidade || "L").toUpperCase()
                    },
                    imageOptions: {
                        crossOrigin: "anonymous",
                    }
                }
        
        
        if(local.imagem === "true"){
            var img = trataImagem(local.arquivo, "mini");
             if(img){
                objQrCode.image = img
                objQrCode.imageOptions.margin = parseFloat(local?.marginarquivo || 0);
                objQrCode.imageOptions.imageSize = parseFloat(local?.tamanhoarquivo || 1)
            }
            
   
            if(local.imagembackground){
               objQrCode.hideBackgroundDots = false;
            }else{
                objQrCode.hideBackgroundDots = true;
            }
        }
        
    
    
        var pontos = this.pegaCor(local?.corpontos || 1, "#000", local.corpontospersonalizada , local.corpontosinicial, local.corpontosfinal, local.corpontosrotacao);
        
        objQrCode.dotsOptions = {
            type:  local.estilipontos
        }
        
        for(let c in pontos){
            objQrCode.dotsOptions[c] = pontos[c]
        }
        
        
        var cantos = this.pegaCor(local?.corcantos || 1, "#000", local.corcantospersonalizada , local.corcantosinicial , local.corcantosfinal, local.corcantosrotacao);
        
        objQrCode.cornersSquareOptions = {
            type: local.estilocantos
        }
        
        for(let c in cantos){
            objQrCode.cornersSquareOptions[c] = cantos[c]
        }
        
        
  
        
        var internos = this.pegaCor(local?.corinternos || 1, "#000", local.corinternospersonalizada , local.corinternosinicial , local.corcinternosfinal , local.corinternosrotacao);
        
         objQrCode.cornersDotOptions = {
            type: local.estilointernos,
        }
        
         for(let c in internos){
            objQrCode.cornersDotOptions[c] = internos[c]
        }
  
  
        objQrCode.backgroundOptions = this.pegaCor(local?.corbg || 1, "#fff", local.corbgpersonalizada , local.corbginicial , local.corbgfinal , local.corbgrotacao);
        
        this.configQr = objQrCode;
    

        this.ordenado = r.ordenado;
        this.cabecalho = r.cabecalho;
        this.dados = r.lista
        this.regras = r.regras
        this.acoes = r.acoes.length > 0 ? r.acoes : false;
        this.render = r.render
        this.estrangeiros = r.estrangeiros ?? {}
        this.renderRules = r.renderRules
        this.id = geraId();
        
        
        this.tableInicial = document.createElement("TABLE")
        this.tableInicial.classList.add("tabelaNown", "w-100")
        this.tableInicial.id = this.id
        this.rulesHeader = r.header;
        this.multiplo = r.header.multiplo;
        this.visibilidade = r.visibilidade;

        
        

        this.cabecalhoHTML.bind(this)()
        this.bodyHTML.bind(this)()
        
   
        this.body.insertBefore(this.tableInicial, this.body.firstChild);

       
        
        var qr = document.getElementsByClassName("qr-code")

        if(qr.length > 0){
            nownFiles.add(`${dominio}/assets/aplicativo/qrcode/index.js`).then(()=>{
               
                 var i = 0;
            while(i < qr.length){
                var q = qr[i]
                if(this.rotaVer){
                    var url = `${dominio}/${this.rotaVer}/${q.dataset.code}`
                }else{
                    var url = `${dominio}/conteudo/modulos/qr-codes/scan/index.php?code=${q.dataset.code}`
                }
                    
                    
                objQrCode.data = url
                    
        
                const qrCode = new QRCodeStyling(objQrCode);
                qrCode.append(q);
                q.dataset.url = url
                evento(q, "click", this.clickqr.bind(this))
         

    
    
               
               /*
                new QRCode(q, {
            text: url,
	width: 100,
	height: 100,
	colorDark : "#000000",
	colorLight : "#ffffff",
	correctLevel : QRCode.CorrectLevel.H
});

*/
                i++;
            }
 
            })
           
        }

        
        this.paiCarregando.remove()
        
    
        let array = [
            `${dominio}/assets/aplicativo/datatable/min.css`,
            `${dominio}/assets/aplicativo/jquery/min.js`,
            `${dominio}/assets/aplicativo/sweetalert2/min.js`,
            `${dominio}/assets/aplicativo/pdfmake/min.js`,
            `${dominio}/assets/aplicativo/datatable/min.js`
            ];
   
        var visibilidade = [{ targets: 0, visible: false }];
        var somador = 1;
        if(this.multiplo){
            somador = 2;
            visibilidade.push({ targets: 1, visible: true , orderable: false})

        }
        var z = 0;
        while(z <  r.visibilidade.length){
            visibilidade.push({ targets: z + somador, visible: r.visibilidade[z] == 0 ? false : true})
            z++;
        }
        
        
        
        nownFiles.add(array).then(()=>{
            //rowReorder: true,
            
            this.table = new DataTable(`#${this.id}`, {
                 dom: 'Bfrtip',
                 responsive: true,
                 orderClasses: false,
                 initComplete: this.fullComplete.bind(this),
                 colReorder: true,
                 scrollCollapse: true,
                 order: [[0, 'desc']],
                 columnDefs: visibilidade,
                 language: {
    processing:     "Processando...",
    search:         "Pesquisar:",
    lengthMenu:     "Mostrar _MENU_ registros",
    info:           "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    infoEmpty:      "Mostrando 0 até 0 de 0 registros",
    infoFiltered:   "(filtrado de _MAX_ registros no total)",
    infoPostFix:    "",
    loadingRecords: "Carregando...",
    zeroRecords:    "Nenhum registro encontrado",
    emptyTable:     "Nenhum item encontrado.",
    paginate: {
      first:      '<i class="bi bi-chevron-double-left"></i>',
      previous:   '<i class="bi bi-chevron-left"></i>',
      next:       '<i class="bi bi-chevron-double-right"></i>',
      last:       '<i class="bi bi-chevron-right"></i>'
    },
    aria: {
      sortAscending:  ": ativar para ordenar a coluna em ordem ascendente",
      sortDescending: ": ativar para ordenar a coluna em ordem descendente"
    }
  },
                 buttons: [
        'copy', 'excel', 'pdf', 'columnsToggle'
    ]
                
            });
            
            
            this.table.on('row-reorder', (e, diff, edit)=> {
                console.log(e, diff, edit)
            });
            

            this.table.on('page.dt', ()=> {
     this.ajustaMarcacao.bind(this)()
});

            this.table.on('length.dt', (e, settings, len)=> {
     this.ajustaMarcacao.bind(this)()
});
           
            
        })
        
        
         var fragment = document.createDocumentFragment();
        if(r.header.exportador){
              var btnExportar = document.createElement("BUTTON")
              btnExportar.type = "button"
              btnExportar.classList.add("btn-ferramenta", "border-primary", "border")

              
              btnExportar.innerHTML = `
              <i class="bi bi-box-arrow-down text-primary"></i>
              <span class="d-none  d-xl-inline text-primary">Exportar</span>
              `
              evento(btnExportar, "click", this.exportar.bind(this))
              fragment.appendChild(btnExportar)
        }
        
        if(r.header.importador){
              var btnImportar = document.createElement("BUTTON")
              btnImportar.type = "button"
              btnImportar.classList.add("btn-ferramenta", "border-danger", "border")
              btnImportar.innerHTML = `
              <i class="bi bi-upload text-danger"></i>
              <span class="d-none  d-xl-inline text-danger">Importar</span>
              `
              evento(btnImportar , "click", this.importar.bind(this))
              fragment.appendChild(btnImportar)
        }
        
        if(r.header.filtro){
              var btnFiltrar = document.createElement("BUTTON")
              btnFiltrar.type = "button"
              btnFiltrar.classList.add("btn-ferramenta", "border-success", "border")
              btnFiltrar.innerHTML = `
              <i class="bi bi-filter text-success"></i>
              <span class="d-none  d-xl-inline  text-success">Filtrar</span>
              `
               btnFiltrar.setAttribute("data-bs-toggle","collapse"); 
               btnFiltrar.setAttribute("data-bs-target", "#filtrosAvancados"); 
               btnFiltrar.setAttribute("aria-expanded","false"); 
               btnFiltrar.setAttribute("aria-controls","filtrosAvancados"); 
              
              
              fragment.appendChild(btnFiltrar)
        }
        
        if(r.header.colunas){
              var btnColunas = document.createElement("BUTTON")
              btnColunas.type = "button"
              btnColunas.classList.add("btn-ferramenta", "border-info", "border")
              btnColunas.innerHTML = `
              <i class="bi bi-layout-three-columns text-info"></i>
              <span class="d-none d-xl-inline text-info">Colunas</span>
              `
               btnColunas.setAttribute("data-bs-toggle","collapse"); 
               btnColunas.setAttribute("data-bs-target", "#filtrosColunas"); 
               btnColunas.setAttribute("aria-expanded","false"); 
               btnColunas.setAttribute("aria-controls","filtrosColunas"); 
              
              
              fragment.appendChild(btnColunas)
        }
        
          if(r.header.ordenador){
               var btnColunas = document.createElement("BUTTON")
              btnColunas.type = "button"
              btnColunas.classList.add("btn-ferramenta", "border-warning", "border")
              btnColunas.innerHTML = `
              <i class="bi bi-arrows-move text-warning"></i>
              <span class="d-none d-xl-inline text-warning">Reordenar</span>
              `
              evento(btnColunas, "click", ()=>{
                  goUrl(`${window.location.href.split(`${dominio}/`)[1]}#ordenar`)
              })
              fragment.appendChild(btnColunas)
        }
        
        
        document.getElementById("ferramentasTabela").appendChild(fragment)
        
        
    }
    
    remove(row, id){
        this.table.row(row).remove().draw(false);
        var data = new FormData();
        data.append("tipo", "apagar")
        data.append("hash", id)
        this.ajax(data).then((r)=>{
             Swal.fire({icon: "success",title: "Apagado",showConfirmButton: false,timer: 1500});
        }, (r)=>{
            Swal.fire({icon: "error",title: "Atenção", text: r.mensagem, showConfirmButton: false,timer: 1500});
        })
    }
    
    naoEncontrado(){
        this.container.innerHTML = 
        `<div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div>
                <div class="mt-5">
                    <h2 class="text-center corGlobal text-uppercase fs-30">Tabela Não Encontrada</h2>
                </div>
                <div class="my-5">
                    <p class="corGlobal text-center">Tabela não encontrata, contacte o suporte.</p>
                </div>
                <div class="text-center">
                 <button class="btn btn-contrast">Voltar para a Home</button>
                 </div>
                 </div>
                 </div>`
    }
    
    ajax(data, cb = false) {

    data.append("identificador", this.identificador);
    data.append("modulo", this.modulo);
    data.append("master", this.master);

    return new Promise((resolve, reject) => {
        let request = new XMLHttpRequest();

        request.onload = () => {
            try {
                var obj = JSON.parse(request.responseText);
                if (obj.sucesso) {
                    if (cb) {
                        cb(obj);
                        resolve(obj);  // Resolve a promessa mesmo se o callback for fornecido
                    } else {
                        resolve(obj);
                    }
                } else {
                    
                    reject(obj);
                }
            } catch (e) {
                console.log(e);
                console.log(request.responseText);
                reject(e);
            }
        };

        request.onerror = () => {
            reject(new Error('Network error'));
        };

        request.open("POST", `${dominio}/admin/brain.php`);
        request.send(data);
    });
}

}