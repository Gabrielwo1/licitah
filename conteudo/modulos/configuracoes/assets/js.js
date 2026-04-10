function moduloConfiguracoes(){
    new ConfiguracaoControler(1);
}

function moduloConfiguracoesModulos(){
     new ConfiguracaoControler(2);
}

function subConfig(pagina){
    console.log(pagina)
    switch(pagina){
        case 'IdiomaMultiplo':
            loadscript({js: "conteudo/modulos/configuracoes/assets/idiomas.js"}, "configuracoesIdiomaMultiplo");
            break;
        case 'HeaderConstrutor':
            loadscript({js: "conteudo/modulos/configuracoes/assets/headerBuilder.js"}, "headerBuilder");
            break;
    }
}

function sortSelect(selElem) {
    var tmpAry = new Array();
    for (var i=0;i<selElem.options.length;i++) {
        tmpAry[i] = new Array();
        tmpAry[i][0] = selElem.options[i].text;
        tmpAry[i][1] = selElem.options[i].value;
    }
    tmpAry.sort();
    while (selElem.options.length > 0) {
        selElem.options[0] = null;
    }
    for (var i=0;i<tmpAry.length;i++) {
        var op = new Option(tmpAry[i][0], tmpAry[i][1]);
        selElem.options[i] = op;
    }
    return;
}

class InputConfig{
    constructor(chave, input){
        
       
        
        this.chave = chave
        this.input = input

        this.tipo = this.input.tipo.toLowerCase()
        this.label = this.input.label ?  this.input.label : chave
    }
    
    get(){
         switch(this.tipo){
            case 'input':
            case 'textarea':
            case 'select':
            case 'color':
            case 'number':
                return this.conteudo.value
                break;

            case 'check':
                return  this.conteudo.checked ? "true" : "false"
                break;
            case 'file':
                return JSON.stringify(this.midia.get());
                break;



        }
    }
    
    set(v){
         switch(this.tipo){
            case 'check':
                if(v == "true"){
                    this.conteudo.checked  = true;
                } 
                break;
            case 'file':
                return  this.midia.set(v);
                break;

            default:
               this.conteudo.dataset.PreValor = v
               this.conteudo.value = v
                break;

        }
    }
    
    entrada(){
        let input;
        switch(this.tipo){
            case 'input':
      
                input = document.createElement("INPUT")
                if(this.input.mask){
                    input.dataset.mascara = this.input.mask
                    input.classList.add("mascaraInput")
             
                }
                input.type = "input"
                input.classList.add("form-control")
                this.conteudo = input;
                
                if(this.input.mascara){
                    input.type = this.input.mascara
                }
                
                if(this.input.taglify){ 
                    nownFiles.add([`${dominio}/assets/aplicativo/taglify/tagify.js`, `${dominio}/assets/aplicativo/taglify/tagify.css`], false).then(()=>{
                                 new Tagify(input , {
        whitelist: this.input.opcoes,
        enforceWhitelist: true,
        dropdown: {
            maxItems: 20,           // <- mixumum allowed rendered suggestions
            classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
            enabled: 0,             // <- show suggestions on focus
            closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
        }
    })
                    })
                    
              
                    
                }
                
                break;
             case 'number':
                input = document.createElement("INPUT")
                input.type = "number"
                input.classList.add("form-control")
                this.conteudo = input;
                break;
            case 'rich':
                input = document.createElement("div")
                
                nownFiles.add([`${dominio}/assets/aplicativo/quill/min.js`, `${dominio}/assets/aplicativo/quill/min.css`,`${dominio}/assets/js/renderForm.js`]).then(()=>{
                     new Rich(input, false);
                })
               
                this.conteudo = input;

            case 'textarea':
                input = document.createElement("TEXTAREA")
                input.classList.add("form-control")
                this.conteudo = input;
                break;
            case 'select':
                input = document.createElement("SELECT")
                
                
                if(this.input.multiplo){
                    input.setAttribute("multiple", "")
                }
                
                
                input.classList.add("form-select")
                this.conteudo = input;
                input.dataset.chave = this.chave
                
                
                if(this.input.opcoes){
                    var i = 0;
                    while(i < this.input.opcoes.length){
                        var opcao = this.input.opcoes[i]
                        
                        var option = document.createElement("OPTION")
                        option.value = opcao?.valor || opcao?.v || "";
                        option.innerText = opcao?.chave || opcao?.t || "";
                        input.appendChild(option)
                        i++;
                    }
                }
                
                if(this.input.dinamico){

                    var tento = new OpcoesDinamicas(input , 1 , this.input.foco);
                    tento.render();
                }
                
              if(this.input.queryDinamica){
    var request = new Request(this.input.queryDinamica);
    if(this.input.querys){
        request.addData(this.input.querys);
    }
    request.send().then((r) => {

        var lista = r.lista;
        if(lista.length > 0){
            
            var i = 0;
            while(i < lista.length){
                console.log(lista[i])
                var option = document.createElement("OPTION");
                option.value = lista[i]?.valor || lista[i]?.v || "";
                option.innerText = lista[i]?.chave || lista[i].t || "";
                
                input.appendChild(option);
                i++;
            }
            
            var valor = input.getAttribute("data--pre-valor");
            if(valor){
                input.value = valor;
            }
            
        }
    }, (r) => {
        console.log(r);
    });
}

                break;
            case 'check':
                input = document.createElement("DIV")
                input.classList.add("form-check","form-switch")
              
                var swith = document.createElement("INPUT")
                swith.style = "width: 80px; height: 40px"
                swith.classList.add("form-check-input")
                swith.dataset.chave = this.chave
                swith.type = "checkbox"
                swith.setAttribute("role" ,"switch")
                input.appendChild(swith)
                this.conteudo = swith;
                break;
            case 'file':
                input = document.createElement("DIV")
                input.classList.add("card","wi-300")
                
                
                var body = document.createElement("DIV")
                body.classList.add("card-body")
                
                var footer = document.createElement("DIV")
                footer.classList.add("card-body")
                
                input.appendChild(body)
                input.appendChild(footer)
             
                var config  = {
                    "multiple" : false,
                    "arredondado": false
                }
                this.midia = new ImagemNown(input, false, config);
                this.midia.render();
                footer.remove()
               
                break;
            case 'color':
                 input = document.createElement("INPUT")
                 input.type = "color"
                 input.style = "width: 50px; height: 50px"
                 this.conteudo = input;
                break;

        }
        
        if(this.input.id){
            input.id = this.input.id
        }
        
        
        
        return input;
    }
    
    render(){
        var row = document.createElement("DIV")
        row.classList.add("row",  "pb-4")
        
        var col = document.createElement("DIV")
        col.classList.add("col-12", "col-xl-4")
        
        var label = document.createElement("LABEL")
        label.classList.add("fs-14", "fw-700")

        this.label = this.label.replace('{dominio}' , dominio);
        label.innerText = this.label
        
        
    

        
        col.appendChild(label)
        
        if(this.input.descricao){
            var descricao = document.createElement("SPAN")
            descricao.classList.add("d-block", "fs-12")
            this.input.descricao = this.input.descricao.replace(/{dominio}/g, dominio);
            descricao.innerHTML = this.input.descricao
            col.appendChild(descricao)
        }
        
       var col2 = document.createElement("DIV")
        if(this.tipo == "rich"){
            col2.classList.add("col-12", "col-xl-12")
            col.classList.remove("col-xl-4")
        }else{
            col2.classList.add("col-12", "col-xl-8")
         
        }
         
         col2.appendChild(this.entrada.bind(this)())
        row.appendChild(col)
        row.appendChild(col2) 
        
        
        if(this.input.hidden){
            row.classList.add("d-none")
        }
        
        return row;
        
        
    }
}

class ConfiguracaoControler{
    constructor(tipo, hash = false){
        
        this.tipo = tipo
        this.btns = document.getElementsByClassName("btnsConfig")
        this.bigs = document.getElementsByClassName("btnBig")
        this.hash = hash;
        
        evento(Array.from(this.btns), "click", this.seleciona.bind(this))
        evento(Array.from(this.bigs), "click", this.bigSelect.bind(this))
        
        this.header = document.getElementById("headerControler")
        this.corpo = document.getElementById("corpoConfig")
        this.ajax = this.ajax.bind(this)
          
    
        
        
        this.btnResetar = document.getElementById("resetar")
        this.btnRestaurar = document.getElementById("restaurar")
        this.btnSalvar = document.getElementById("salvar")
        evento(this.btnSalvar, "click", this.salvar.bind(this))
        
        this.tipog = false;
        this.cores = false;
        
        this.showMobile = false;
        this.btnControleMobile = document.getElementById("controleMobile")
        evento(this.btnControleMobile, "click", this.opcoesMobile.bind(this))
        
        let array = [
            "https://unpkg.com/filepond/dist/filepond.css",
            "https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css",
            "https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js",
            "https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js",
            "https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js",
            "https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js",
            "https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js",
            "https://unpkg.com/filepond/dist/filepond.js"
            ];
            
      packLoad(array).then(()=>{
          var trato = window.location.href.split("/a/")[1].split("/")
            
          switch(trato.length){
              case 1:
                  this.bigs[0].click()
                  break;
              case 2:
                  if(trato[1] == "modulos"){
                      this.bigs[0].click()
                  }else{
                    console.log("clicar no grupo");
                  }
                  break;
              case 3:
                  
                  if(trato[0] == "wildcard"){
                      this.bigs[0].click();
                      return;
                  }
                  
                  var btn = this.selectElementByDataNG(trato[1], trato[2]);
                  if(btn){
                      
                      
                      btn.click();
                      btn.closest(".accordion-collapse").classList.add("show")
                      btn.closest(".accordion-item").getElementsByClassName("btnBig")[0].classList.remove("collapsed")
                  }
                  break;
              case 4:
                 if(trato[0] == "wildcard"){
                      const selector = `[data-g="${trato[2]}"]`;
                      const btn = document.querySelector(selector);
                      
                      if(btn){
                          btn.click();
                          btn.closest(".accordion-collapse").classList.add("show")
                          btn.closest(".accordion-item").getElementsByClassName("btnBig")[0].classList.remove("collapsed") 
                      }

                      return;
                  }
                  
            
                  var btn = this.selectElementByDataNG(trato[2], trato[3]);
                  if(btn){
                      btn.click();
                      btn.closest(".accordion-collapse").classList.add("show")
                      btn.closest(".accordion-item").getElementsByClassName("btnBig")[0].classList.remove("collapsed")
                  }
                  break;
          }

      })
    }
    
    selectElementByDataNG(dataNValue, dataGValue) {
 
    const selector = `[data-n="${dataNValue}"][data-g="${dataGValue}"]`;
    const element = document.querySelector(selector);
    return element;
}
    
    opcoesMobile(){
        if(!this.showMobile){
            this.showMobile = true;
             this.btnControleMobile.classList.add("ativo")
             document.getElementById("opcoesConfig").classList.add("ativo")
             console.log('abriu');
        }else{
             this.showMobile = false;
             this.btnControleMobile.classList.remove("ativo")
             document.getElementById("opcoesConfig").classList.remove("ativo")
             console.log('fechou');
        }
       
    }
    
    bigSelect(){

        if(!event.currentTarget.classList.contains("collapsed") && event.currentTarget.closest(".accordion-item ").getElementsByClassName("btnsConfig").length > 0){
            event.currentTarget.closest(".accordion-item ").getElementsByClassName("btnsConfig")[0].click();
        }
    }
    
    salvar(){
        
  
        this.btnSalvar.setAttribute("disabled", "")
        this.btnSalvar.innerHTML = `
  
  <div class="spinner-border" role="status" style="width: 12px ; height: 12px;">
    <span class="visually-hidden">Loading...</span> 
  </div>

        `
        console.log(this.foco)
        switch(paraUrl(this.foco)){
            case "tipografia":
                var valores = tipografo.get();
                var map = {
                    "Corpo" : "body",
                    "Títulos H1" : "h1",
                    "Títulos H2" : "h2",
                    "Títulos H3" : "h3",
                    "Títulos H4" : "h4",
                    "Títulos H5" :"h5",
                    "Títulos H6" : "h6",
                    "Menu Lateral" : "#lateral :is(.itemDeMenu, .accordion-button)",
                    "Menu Topo" : "#topo",
                   " Menu Footer" : "#rodape",
                }
                if(map[this.grupo]){
                    valores.selector = map[this.grupo]
                }
                
   
                break;
            default:
              var valores = {};
        for(let c in this.lista){
            if(this.lista[c]  instanceof HTMLElement){
                
            }else{
                valores[c] = this.lista[c].get();
            }
            
        }
                break;
        }
        
      
        
        var data = new FormData();
        data.append("acao", "salvar")
        data.append("dados", JSON.stringify(valores))
        this.ajax(data, this.salvo.bind(this))
    }
    
    salvo(r){
        Swal.fire({
            icon: "success",
            title: "Configuração salva com sucesso",
            showConfirmButton: false,
            timer: 1500
        });
        this.btnSalvar.removeAttribute("disabled")
        this.btnSalvar.innerHTML = `
         <i class="bi bi-floppy"></i>
         <span class="d-none d-xl-block">Salvar</span>`
        
        start.ajax();
    }
    
    loading(){
        var div = document.createElement("DIV")
        div.classList.add("h-100", "d-flex", "justify-content-center", "flex-column", "align-items-center")
        div.innerHTML = `
           <div class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
        
        `
        this.corpo.innerHTML = ""
        this.corpo.appendChild(div)
        
        

    }
    
    seleciona(){
        

        if(document.getElementById("menuLateralConfig").getElementsByClassName("ativo").length > 0){
            document.getElementById("menuLateralConfig").getElementsByClassName("ativo")[0].classList.remove("ativo")
        }
        
        
       
        
        
        this.btn =  event.currentTarget
        this.pai = this.btn.closest(".accordion-item")
        this.btn.classList.add("ativo")
        
        
        var nome =  this.toKebabCase(this.pai.dataset.nome);
        var grupo = this.toKebabCase(this.btn.dataset.grupo);
        
        
        if(window.location.href.split("modulos").length == 2){
            var url = `${dominio}/a/configuracoes/modulos/${nome}/${grupo}`
        }else{
            var url = `${dominio}/a/configuracoes/${nome}/${grupo}`
        }
        
        if(window.location.href.split("wildcard").length == 2){
            var url = `${dominio}/a/wildcard/configuracao/${grupo}/${this.hash}`
        }
        
        
        window.history.pushState(null, null, url);
      
  
        this.header.getElementsByClassName("modulo")[0].innerText = this.pai.dataset.nome
        this.header.getElementsByClassName("grupo")[0].innerText = this.btn.dataset.grupo
        this.header.getElementsByClassName("icone")[0].innerHTML = ""
     
        var icone = document.createElement("I")
        icone.className = this.pai.dataset.icone
        this.header.getElementsByClassName("icone")[0].appendChild(icone)
        
        
        
        var classesDaDiv = this.header.classList;
        var classesArray = Array.from(classesDaDiv);
        classesArray.forEach((classe)=> {
            if (classe !== 'card' &&  classe !== 'border' && classe !== 'shadow' && classe !== "card-nown") {
                this.header.classList.remove(classe);
            }
        });
       
       var cor = this.pai.dataset.cor
       this.header.classList.add(`text-${cor}-emphasis`, `bg-${cor}-subtle`, `border-${cor}-subtle`)
       
       
       if(this.foco != this.pai.dataset.nome || this.grupo != this.btn.dataset.grupo){
           this.foco = this.pai.dataset.nome
           this.grupo = this.btn.dataset.grupo
       
           this.loading.bind(this)()
          var data = new FormData();
          data.append("acao", "html")
          this.ajax(data, this.monta.bind(this))
       }
       
       
    }
    
    toCamelCase(text) {
    // Divide a string em palavras separadas por espaço
    const words = text.split(' ');
    
    // Transforma a primeira palavra para minúscula
    words[0] = words[0].toLowerCase();
    
    // Transforma a primeira letra de cada palavra subsequente para maiúscula
    for (let i = 1; i < words.length; i++) {
        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
    }
    
    // Junta todas as palavras transformadas em uma única string
    return words.join('');
}

    toKebabCase(text) {
    let normalizedText = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Remove qualquer coisa que não seja letra (considera letras de A-Z, incluindo versões maiúsculas e minúsculas)
    normalizedText = normalizedText.replace(/[^a-zA-Z\s]/g, '');

    // Divide a string em palavras separadas por espaço
    const words = normalizedText.split(' ');

    // Transforma todas as palavras para minúsculas e remove espaços extras
    const lowercaseWords = words.filter(word => word.trim() !== '').map(word => word.toLowerCase());

    // Junta as palavras com um hífen entre elas
    return lowercaseWords.join('-');
}

    toUpperCase(text) {
  let normalizedText = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Remove qualquer coisa que não seja letra (considera letras de A-Z, incluindo versões maiúsculas e minúsculas)
    normalizedText = normalizedText.replace(/[^a-zA-Z\s]/g, '');

    // Divide a string em palavras separadas por espaço
    const words = normalizedText.split(' ');

    // Transforma a primeira letra de cada palavra para maiúscula e as demais para minúscula
    const capitalizedWords = words.filter(word => word.trim() !== '').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase());

    // Junta as palavras sem separador entre elas
    return capitalizedWords.join('');
}

    monta(r){
        this.lista = {};
        this.corpo.innerHTML = ""
        
        
        
        if(r.tipo){
            this.memoria = r.respostas
           if(r.tipo == "json"){
          
                let html = r.obj;
            for(let c in html){
      
                if(html[c].tipo != "titulo" && html[c].tipo != "div" && html[c].tipo != "hr"){
                    
                    var input = new InputConfig(c, html[c])
                    this.lista[paraUrl(c)] = input
                    var render = input.render();
                     
                
                     
                    if(r.respostas[c]){
                        this.lista[paraUrl(c)].set(r.respostas[c])
                    }
                    
                }else{
                    if(html[c].tipo == "titulo"){
                        var h2 =  document.createElement("H2")
                        h2.innerText = c
                        h2.classList.add("fs-18", "fw-700", "text-uppercase")
                        
                    var render = document.createElement("DIV")
                    this.lista[c] = render;
                    render.appendChild(h2)
                    if(html[c].subtitulo){
                        h2.classList.add("mb-1")
                        var sub = document.createElement("p")
                        sub.classList.add("fs-14")
                        sub.innerText = html[c].subtitulo
                        render.appendChild(sub)
                    }
                    }else if(html[c].tipo == "hr"){
                         var render = document.createElement("DIV")
                         render.style = "border: 1px dashed gray"

                        
                    }else{
                        var render = document.createElement("DIV")
                        render.id = c;
                        this.lista[c] = render;
                     
                    }
                    
                }
                
               
                this.corpo.appendChild(render)
            }
            
                   new Mascaras();
            
            var inteligentes = [];
            this.mudam = {};
             for(let c in html){
                 
                 if(html[c].condicional){
                     if(!this.mudam[paraUrl(html[c].condicional)]){
                         this.mudam[paraUrl(html[c].condicional)] = [];
                     }
                     this.mudam[paraUrl(html[c].condicional)].push(this.lista[c]);
                     inteligentes.push(c)
                
                 }

             }
           
            
            
            if(inteligentes.length > 0){
                var i = 0;
                let uniqueArray = [...new Set(inteligentes)];
                while(i < uniqueArray.length){
                 
                    var inte =  this.lista[paraUrl(html[uniqueArray[i]].condicional)] 
                    if(inte){
                        evento(inte.conteudo, "input", this.logica.bind(this))
                        this.logica.bind(this)(inte.conteudo);
                    }
                    
                    
                    i++;
                }
            }
            
            
            this.btnResetar.removeAttribute("disabled")
            this.btnRestaurar.removeAttribute("disabled")
            this.btnSalvar.removeAttribute("disabled")
            
   
            
           }else{
              
             this.corpo.innerHTML = r.html 

             
           }
            
      
            
            
           
           
        }else{
            var div = document.createElement("DIV")
            div.classList.add("d-flex", "h-100", "flex-column", "justify-content-center")
            div.innerHTML = `
            <div>
            <div class="text-center">
            <span class="material-symbols-outlined" style="font-size:100px">warning</span>
            </div>
            <h2 class="text-center fs-20 text-uppercase my-3">Erro ao carregar configurações</h2>
            <p class="text-center m-0">${r.mensagem}</p>
            </div>`
            this.corpo.innerHTML = ""
            this.corpo.appendChild(div)
            this.btnResetar.setAttribute("disabled", "")
            this.btnRestaurar.setAttribute("disabled", "")
            this.btnSalvar.setAttribute("disabled", "")
        }
        
        if(r.js){

            nownFiles.add(`${dominio}/${r.js}`).then(()=>{
                
                 var nome =  this.toUpperCase(this.pai.dataset.nome);
                 var grupo = this.toKebabCase(this.btn.dataset.grupo);
                 
                eval(`configuracoesNown${nome}`)(grupo, r)

            })
        }

    }
    
    logica(c){
 
        var foco = c instanceof HTMLElement ? c : event.currentTarget
       
       
       if(foco.tagName == "SELECT"){
           var valor = foco.value
           
           if(this.mudam[paraUrl(foco.dataset.chave)]){
                var tratos = this.mudam[paraUrl(foco.dataset.chave)]
                
                for(let c in tratos){
                if(tratos[c].tipo == "file"){
                    var t =  tratos[c].midia.card.closest(".row")
                }else{
                    console.log(tratos[c])
                    var t = tratos[c] instanceof HTMLElement ? tratos[c] : tratos[c]?.conteudo?.closest(".row") || false
                }
                
            
                
                if(valor == tratos[c].input.valorcondicao){
                    t.classList.remove("d-none")
                }else{
                    t.classList.add("d-none")
                }
            }
                
        
           }
           
           
           
       }else{
          var ativo = foco.checked
      
        if(this.mudam[paraUrl(foco.dataset.chave)]){
            var tratos = this.mudam[paraUrl(foco.dataset.chave)]
            for(let c in tratos){
            
                var t = tratos[c] instanceof HTMLElement ? tratos[c] : tratos[c].conteudo?.closest(".row") || false
                
                if(ativo){
                   if(t){
                        t.classList.remove("d-none")
                   }
                }else{
                    if(t){
                        t.classList.add("d-none") 
                    }
                   
                }
            }
        } 
       }
       
       
        
        
    }
    
    ajax(data, cb = false){
        data.append("tipo", this.tipo)
        if(this.tipo == 3){
            data.append("wirecard", this.hash)
        }
        data.append("foco", this.foco)
        data.append("grupo", this.grupo)
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(obj.sucesso && cb){
                    cb(obj)
                }else{
                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/conteudo/modulos/configuracoes/admins/modulos.php`);
        request.send(data)
    }
}