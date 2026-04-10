class Reacoes{
    constructor(){
        /*
        Para adicionar as reações, adicione uma div de container, com a classe "".reacoes"
        
        Na div, passse os parametros data-banco, nome do modulo ou banco de dados, tem q ser um identificador único
        Data Url - Parametro de url publica
        Data Id, o id da reacao do usuario  data-url="${this.item.u}" data-id="${meReaction}"
        
        caso queira mostrar o contador de curtidas. crie um container pai que tenha a .reacoes  com a classe .reacoesPai
        
        dentro desse pai, adicione a classe .reacoesContador e nele será mostrado a contagem
        */
         import("https://unpkg.com/@popperjs/core@2").then(() => {
               return import("https://unpkg.com/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js");})
               .then(() => {
                  
                    this.init.bind(this)(); 
                
               })
               .catch(error => {
                   console.error("Erro ao carregar os scripts:", error);
               });

    }
    
    init(){
        this.reacoes = {};
        let request = new ApiNown(`reacoes`, "AkDyjc2LExv3HK6");
        request.send().then((r)=>{
            var lista = r.lista
            if(lista.length == 0){
                return;
            }
            
            this.default = false;
            var i = 0;
            while(i < lista.length){
                var item = lista[i]
            
                
                var lord = trataImagem(item.lord);
                if(lord){
                    lord = `${dominio}/conteudo/uploads/${lord}`
                }
                
        
                let obj = {
                    cor: item.cor,
                    titulo: item.texto,
                    lord: lord,
                    icone: item.iconesimples,
                    id: item.id
                }
                this.reacoes[item.id] = obj
                
                if(i == 0){
                    this.default = obj;
                }
                
                i++;
            }
            
              nownFiles.add(`https://cdn.lordicon.com/lordicon.js`).then(()=>{
        this.timer = 0;
        
        this.trigers = document.getElementsByClassName("reacoes")
        var i = 0;
        while(i < this.trigers.length){
            if(!this.trigers[i].dataset.start){
                this.trigers[i].dataset.start = true;
                
                if(this.trigers[i].dataset.open){
                    
                }else{
                    this.renderMultiplo.bind(this)(this.trigers[i])
                }
                
            }
            
            i++;
        }
        
                 
        })
     

        }, (r)=>{
            console.log(r)
        })
    }
    
    reacao(btn){
        var pai = btn.closest(".reacoes")

        if(pai.getElementsByClassName("reacoesFloat").length > 0){
            pai.getElementsByClassName("reacoesFloat")[0].remove();
            return;
        }
        
        var resto = document.getElementsByClassName("reacoesFloat")
        if(resto.length > 0){
            resto[0].remove();
        }
        
        var div = document.createElement("DIV")
        div.classList.add("reacoesFloat","animate__animated","animate__bounceIn")
        
         evento(div, 'mouseout', ()=>{
              const targetElement = document.elementFromPoint(event.clientX, event.clientY);
    
    if (targetElement) {
        if(!targetElement.closest(".reacoesFloat")){
            event.currentTarget.remove();
        }
    }
        })
        
        
        
   
        var contador = 3;
        var y = 0;
        for(let i in this.reacoes){
            
            let reacao = this.reacoes[i]
            var btn = document.createElement("DIV")
            btn.classList.add("reacao","animate__animated","animate__bounceIn")
            
            btn.dataset.id = i
            btn.title = reacao.titulo
            btn.style = `animation-delay: 0.${contador + y}s;`
            btn.title = reacao.titulo
            btn.innerHTML = `
            <lord-icon
                src="${reacao.lord}"
                trigger="hover"
                style="width:50px;height:50px">
            </lord-icon>
            `  
            evento(btn, "click", this.simples.bind(this))
            div.appendChild(btn)
            y++;
           
        }
        pai.appendChild(div)
        
        setTimeout(()=>{
                var btns = div.getElementsByClassName("reacao")
                var i = 0;
                while(i < btns.length){
                     btns[i].classList.remove("animate__animated","animate__bounceIn")
                    i++;
                }
               
            }, 1000)
            
            

    }
    
    simples(){
        
        var item = event.currentTarget
        
        var depois = item?.dataset?.id || 0;
        

        
        if(!item.dataset.id){
            item.dataset.id = this.default.id
        }
        


        
        var pai = item.closest(".reacoes")
        if(this.timer){
             clearTimeout(this.timer);
             this.timer = null;
        }
        var resto = document.getElementsByClassName("reacoesFloat")
        if(resto.length > 0){
            resto[0].remove();
        }
        

         var id = parseInt(item.dataset.id);
        var btn = pai.getElementsByClassName("reagir")[0]
        var antes = btn.dataset.antes;
        
       

        
        var icone = btn.getElementsByTagName("span")[0]
        var texto = btn.getElementsByTagName("span")[1]
        
        
        if(id != 0){
             let reacao = this.reacoes[id];
             texto.innerText = reacao.titulo
             texto.style.color = reacao.cor
             if(reacao.lord){
                   icone.innerHTML = `
            <lord-icon
                src="${reacao.lord}"
                trigger="loop"
                style="width:20px;height:20px">
            </lord-icon>
            `
             }else{
                   icone.innerHTML = ``
             }
           
        }else{
            
             let reacao = this.reacoes[id];
             texto.innerText = this.default.titulo
             texto.style = ""
             if(this.default.icone){
                  icone.innerHTML = `<i class="${this.default.icone}"></i>`
             }else{
                  icone.innerHTML = ``
             }
            
        }
        
        // let request = new Request(`${dominio}/conteudo/modulos/reacoes/admins/api.php`);
        let request = new RequestRote('reacoes', 'api');
        request.addData({
            acao: "interacao",
            "reacao": id,
            "modulo": pai.dataset.banco,
            "url": pai.dataset.url
        })
        request.send().then((r)=>{
            console.log(r)
        }, (r)=>{
            console.log(r)
        })

        
        

        if(id == 0){
            btn.dataset.id = this.default.id
        }else{
            btn.dataset.id = 0;
        }  
        btn.dataset.antes = depois;
        

  
         var pai = btn.closest(".reacoesPai");
         if(pai){
               var filho = pai.getElementsByClassName("reacoesContador")[0]
               if(filho){
                   var memoria = filho.getElementsByClassName("memoria")[0];
                   var obj = JSON.parse(memoria.innerText.trim());
                    antes = parseInt(antes)
                    depois = parseInt(depois)
                    
                    if(depois == 0){
                        obj[antes] = parseInt(obj[antes]) - 1;
                    }else{
                        
                        if(!obj[depois]){
                            obj[depois] = 0;
                        }
                        obj[depois] = parseInt(obj[depois]) + 1;
                        
                        
                        if(antes != 0){
                            
                            obj[antes] = parseInt(obj[antes]) - 1;
                            
                        }
                        
                        
                        
                    } 
           
                   
                 memoria.innerText = JSON.stringify(obj)
                 this.renderPai.bind(this)(btn);
 
               }
         }
      

    }
    
    renderMultiplo(item){
   
        var btn = document.createElement("btn")
        btn.classList.add("d-flex", "justify-content-center","align-items-center","w-100","btn","gap-2","fw-700","reagir")
        
        
        var span = document.createElement("SPAN")
        span.innerHTML = `<i class="${this.default.icone}"></i>`
        btn.dataset.antes = 0;
        var span2 = document.createElement("SPAN")
        span2.innerText = this.default.titulo
        
        
         if(item.dataset.id && item.dataset.id != "0"){
            var reacao = this.reacoes[parseInt(item.dataset.id)];
             span2.innerText = reacao.titulo
             span2.style.color = reacao.cor
             if(reacao.lord){
                   span.innerHTML = `
            <lord-icon
                src="${reacao.lord}"
                trigger="loop"
                style="width:20px;height:20px">
            </lord-icon>
            `
             }else{
                   span.innerHTML = ``
             }
             btn.dataset.id = "0"
             btn.dataset.antes = item.dataset.id;
             
  
        }
        
        
        btn.appendChild(span)
        btn.appendChild(span2)
        

        evento(btn, "click", this.simples.bind(this))
        
        
        evento(btn, 'mouseover', ()=>{
            var btn = event.currentTarget;
            this.timer = setTimeout(() => {
                this.reacao.bind(this)(btn);
            }, 500);
        }) 
        
        evento(btn, 'mouseout', ()=>{
            clearTimeout(this.timer);
            
            
              const targetElement = document.elementFromPoint(event.clientX, event.clientY);
    
    if (targetElement) {
        if(!targetElement.closest(".reacoes")){

            if(document.getElementsByClassName("reacoesFloat").length > 0){
                document.getElementsByClassName("reacoesFloat")[0].remove();
            }
            
       
        }

 
    }
            
        })
        
        
        if(btn.dataset.simples){
           
        }else{
            
        }
        

        item.appendChild(btn)
        
        
        if(item.closest(".reacoesPai")){
            this.renderPai(item);
        }
        
        

    }
    
    renderPai(item){
        
        let clicaveis = [];
        var pai = item.closest(".reacoesPai");
        var filho = pai.getElementsByClassName("reacoesContador")
        var contador = 0;
        var fragmento = document.createDocumentFragment();
        var obj = [];
          
         
        if(filho.length == 1){
            filho = filho[0]
            
            if(filho.getElementsByClassName("memoria").length == 1){
                  var obj = JSON.parse(filho.getElementsByClassName("memoria")[0].innerText.trim())
            }else{
                  var obj = JSON.parse(filho.innerText.trim())
            }
            

            filho.innerHTML = "";
            
          
            
            for(let c in obj){
                if(obj[c]){
            
                    var span = document.createElement("SPAN")
                    span.classList.add("link")
                    span.dataset.id = c;
                    clicaveis.push({
                        html: span
                    })
                      contador += parseInt(obj[c])
                    var reacao = this.reacoes[parseInt(c)];
                    if(reacao.lord){
                    span.innerHTML = `
                    <lord-icon
                    src="${reacao.lord}"
                    trigger="loop"
                    style="width:20px;height:20px">
                    </lord-icon>
                    `
                    fragmento.appendChild(span)
                }
                     
            }
            
           
      

        }
        
            if(contador > 0){
                var span = document.createElement("SPAN")
                span.dataset.id
                clicaveis.push({
                        html: span
                    })
                span.classList.add("fs-14", "link", "link-hover")
                span.innerText = contador;
                fragmento.appendChild(span)
            }
    }
    
    filho.appendChild(fragmento);
     
     let memoria = document.createElement("SPAN")
     memoria.classList.add("d-none", "memoria")
     memoria.innerText = JSON.stringify(obj)
     filho.appendChild(memoria)
     
     filho.classList.remove("d-none")
     filho.classList.add("d-flex", "align-items-center", "gap-1")
     
     this.fecha.bind(this)(clicaveis);
    }
    
    modal(){
      
        if(!document.getElementById("boxReacoes")){
            var div = document.createElement("DIV")
            div.classList.add("backReacao")
            div.id = "boxReacoes";
            div.innerHTML = `
      
    <div class="card" style="width: 500px; height: 500px">
        <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="nav nav-underline lista"></ul>
                    <div>
                        <button class="btn btn-secondary wi-35 he-35 rounded-circle d-flex justify-content-center align-items-center btnClose">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
        </div>
        <div class="card-body overflow-y-auto d-flex flex-column gap-2 parceiros">
      
            
           
        </div>
    </div>

            
            
            `
            evento(div, "click", ()=>{
                   if(event.target.id == "boxReacoes"){
                        this.close.bind(this)();
                   }
               
            })
            evento(div.getElementsByClassName("btnClose")[0], "click", this.close.bind(this))
            document.getElementById("conteudo").appendChild(div)
            
            
        }else{
            var div = document.getElementById("boxReacoes");
        }
        
        let btn = event.currentTarget;
        var id = parseInt(btn.dataset?.id || 0)
        var pai = btn.closest(".reacoesPai").getElementsByClassName("reacoes")[0];
        var contador = btn.closest(".reacoesPai").getElementsByClassName("reacoesContador")[0]
        if(contador.getElementsByClassName("memoria").length == 1){
            var obj = JSON.parse(contador.getElementsByClassName("memoria")[0].innerText.trim())
        }else{
            var obj = JSON.parse(contador.innerText.trim())
        }
        
        
        var ul = div.getElementsByClassName("lista")[0]
        var parceiros = div.getElementsByClassName("parceiros")[0]
        ul.innerHTML = "";
        parceiros.innerHTML = "";
        var fragmento = document.createDocumentFragment();
        for(let c in obj){
            var reacao = this.reacoes[parseInt(c)];
            var li = document.createElement("LI")
            li.classList.add("nav-item")
            
            var button = document.createElement("BUTTON")
            button.classList.add("nav-link")
            
            if(id == c){
                button.classList.add("active")
            }
            
                    if(reacao.lord){
                    button.innerHTML = `
                    <lord-icon
                    src="${reacao.lord}"
                    trigger="loop"
                    style="width:20px;height:20px">
                    </lord-icon>
                    `
                    fragmento.appendChild(button)
            
      
            }
            
        }
        ul.appendChild(fragmento)
 
        
        div.classList.add("ativo")
        
        // let request = new Request(`${dominio}/conteudo/modulos/reacoes/admins/api.php`);
        let request = new RequestRote('reacoes', 'api')
        request.addData({
            "acao": "infos",
            "reacao": id,
            "modulo": pai.dataset.banco,
            "url": pai.dataset.url
        })
        request.send().then((r)=>{
            var lista = r.lista
            var i = 0;
            var fragmento = document.createDocumentFragment();
            while(i < lista.length){
                var item = lista[i]
                 var reacao = this.reacoes[parseInt(item.reacao)];
             
                var foto = trataImagem(item.foto, "mini")
                if(!foto){
                     var foto = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`
                }
                var div = document.createElement("DIV")
                div.innerHTML = `
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex justify-cotent-start gap-4 align-items-center">
                            <div>
                                <div class="he-40 wi-40 rounded-circle position-relative" style="background-image: url(${foto}); background-size: cover; background-position: center center;">
                                    <div class="he-20 wi-20 d-flex justify-content-center align-items-center rounded-circle position-absolute" style="bottom: 0px; right: -10px">
                                        <lord-icon
                    src="${reacao.lord}"
                    trigger="loop"
                    style="width:20px;height:20px">
                    </lord-icon>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>${item.nome}</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button>Amigos</button>
                    </div>
                </div>
                `
                fragmento.appendChild(div)
                i++;
            }
            parceiros.appendChild(fragmento)
        }, (r)=>{
            console.log(r)
        })
        
  
        
        
    }
    
    close(){
        document.getElementById("boxReacoes").classList.remove("ativo")
    }
    
    fecha(clicaveis){

 var i = 0;
                   while(i < clicaveis.length){
                       var clique = clicaveis[i]
                       evento(clique.html, "click", ()=>{
                           this.modal.bind(this)()
                       })
                       
              
                        tippy(clique.html , {
                            arrow: true,
                            interactive: true,
                            allowHTML: true,
                            delay: 500,
                            content: `
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>`,
                            onShow(instance) {
                                    var id = parseInt(instance.reference?.dataset?.id || 0)
                                var pai = clique.html.closest(".reacoesPai").getElementsByClassName("reacoes")[0];
                                
                                //   let request = new Request(`${dominio}/conteudo/modulos/reacoes/admins/api.php`);
                                let request = new RequestRote('reacoes', 'api');
                                request.addData({
                                   "acao": "infos",
                                   "reacao": id,
                                   "modulo": pai.dataset.banco,
                                   "url": pai.dataset.url
                               })
                               request.send().then((r)=>{
                                   var lista = r.lista;
                                   var div = document.createElement("DIV")
                                   var i = 0;
                                   while(i < lista.length){
                                       var item = lista[i]
                                       var span = document.createElement("DIV")
                                       span.classList.add("fs-12")
                                       span.innerText = item.nome
                                       div.appendChild(span)
                                       i++;
                                   }
                                   
                                
                                      instance.setContent(div);
                                   
                               }, (r)=>{
                                   console.log(r)
                                   
                               })
                                
                            },
                            
                            
                        });
             
                       i++;
                   }
                   
       
    }

}
 
function formatNumberdecimal(num) {
  if (num < 1 && num > 0) {
    // Converte o número em string e remove o "0."
    let str = num.toString();
    let match = str.match(/^0\.(\d+)/);  // Captura os números após o ponto
    
    if (match) {
      let significantDigits = match[1].slice(0, 4); // Pega até 4 dígitos significativos
      return '0.' + significantDigits;  // Retorna o número formatado
    }
  }

  return num.toString();  // Retorna o número como está se for maior que 1
}

function formatNumber(value) {
  if (typeof value !== 'number') {
    throw new Error('O valor deve ser um número.');
  }

  // Marca se o valor é negativo
  const negativo = value < 0;
  value = Math.abs(value);  // Trabalha com o valor absoluto

  // Aplica a formatação decimal
  value = formatNumberdecimal(value);

  // Usa Intl.NumberFormat para formatar separadores de milhar com ponto (.) e decimais com vírgula (,)
  let formattedValue = new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: value % 1 === 0 ? 0 : 3,  // Se for inteiro, não mostra decimais
    maximumFractionDigits: 3   // Limita a 3 casas decimais
  }).format(value);

  // Substitui o ponto por vírgula para parte decimal e garante separadores de milhar com ponto
  formattedValue = formattedValue.replace('.', ',');  // Substitui ponto decimal por vírgula

  // Adiciona o sinal negativo se necessário
  return (negativo ? '-' : '') + formattedValue;
}

class Emoji {
  constructor(input = false, btn = false, container = false, cb = false) {
    this.input = input; 
    this.btn = btn; 
    this.container = container;
    this.cb = cb;
    this.container.classList.add("overflow-y-auto");

    if (!this.input || !this.btn || !this.container) {
      console.log("Não foram enviados os parâmetros válidos");
      return;
    }

    this.cursorPos = null; 
    this.input.addEventListener("click", this.updateCursorPos.bind(this));
    this.input.addEventListener("keyup", this.updateCursorPos.bind(this)); 

    this.evento(this.btn, "click", this.show.bind(this));

    this.render();
  }

  evento(element, event, callback) {
    element.addEventListener(event, callback);
  }

  updateCursorPos() {
    if (this.input instanceof HTMLInputElement || this.input instanceof HTMLTextAreaElement) {
      this.cursorPos = this.input.selectionStart;
    } else if (this.input.isContentEditable) {
      const selection = window.getSelection();
      this.cursorPos = selection.focusOffset; 
    }
  }

  async render() {
    try {
      let response = await fetch(`${dominio}/conteudo/modulos/rede-social/media/array.min.json`);
      let r = await response.json();
      let lista = r.emojis;

      this.aberto = false;
      let colapso = document.createElement("DIV");
      colapso.classList.add("collapse");

      colapso.innerHTML = `
        <div class="p-2">
          <div class="listaEmojis row m-0"></div>
        </div>
      `;

      let emojiContainer = colapso.querySelector(".listaEmojis");
      let id = this.geraId();
      colapso.id = id;
      this.container.appendChild(colapso);

      this.colapso = new bootstrap.Collapse(`#${id}`, {
        toggle: false
      });

      this.adicionaEmojisEmLote(emojiContainer, lista, 50);

    } catch (error) {
      console.error("Erro ao carregar os emojis:", error);
    }
  }

  adicionaEmojisEmLote(container, lista, lote) {
    let total = lista.length;
    let i = 0;

    const adicionarLote = () => {
      if (i >= total) return; 

      let fragmento = document.createDocumentFragment();
      let limite = Math.min(i + lote, total);

      while (i < limite) {
        let button = document.createElement("BUTTON");
        button.innerText = lista[i];
        button.classList.add("btn", "col-1", "p-1");
        button.addEventListener("click", () => this.inserirEmoji());
        fragmento.appendChild(button);
        i++;
      }

      container.appendChild(fragmento);

      requestAnimationFrame(adicionarLote);
    };

    requestAnimationFrame(adicionarLote); 
    
 
  }

  inserirEmoji() {
      let emoji = event.currentTarget.innerHTML;
     
    let texto;
    
    if (this.input instanceof HTMLInputElement || this.input instanceof HTMLTextAreaElement) {
      texto = this.input.value;

      if (this.cursorPos !== null) {
          
   
        const inicio = texto.slice(0, this.cursorPos);
        const fim = texto.slice(this.cursorPos);
        this.input.value = `${inicio}${emoji}${fim}`;
        

        this.cursorPos += emoji.length;
      } else {

        this.input.value += emoji;
      }


      this.input.setSelectionRange(this.cursorPos, this.cursorPos);
      this.input.focus(); 
    } else if (this.input.isContentEditable) {

      const selection = window.getSelection();
      const range = selection.getRangeAt(0);
      range.deleteContents();
      range.insertNode(document.createTextNode(emoji));
      range.collapse(false); 
      selection.removeAllRanges();
      selection.addRange(range);
      
      

       if(this.cb){
        this.cb();
    }
    }
  }

  show() {
    if (!this.aberto) {
      this.colapso.show();
      this.aberto = true;
      this.btn.innerHTML = `<i class="bi bi-x-lg"></i>`;
    } else {
      this.colapso.hide();
      this.aberto = false;
      this.btn.innerHTML = `<i class="bi bi-emoji-heart-eyes"></i>`;
    }
  }

  geraId() {
    return 'emoji-' + Math.random().toString(36).substr(2, 9);
  }
}

function removeAll(className) {
  const elements = document.querySelectorAll(`.${className}`);
  
  // Itera sobre a lista de elementos e remove cada um
  elements.forEach(element => {
    element.remove();
  });
}

class Favoritos{
    constructor(textoSemFavoritar = false, textoFavoritado = false){
        

        this.textInit = textoSemFavoritar ? textoSemFavoritar : `<i class="bi bi-heart"></i>`
        
        this.textFinit = textoFavoritado ? textoFavoritado : '<i class="bi bi-heart-fill text-primaria"></i>'
        
        
        this.init.bind(this)()
    }
    
    init(){
        
        if(!autenticado()){
            removeAll("btnFavoritar")
            return;
        }
        
        this.btns = document.getElementsByClassName("btnFavoritar")
        

        var i = 0;
        var renders = [];
        while(i < this.btns.length){
            if(!this.btns[i].dataset.renderizado){
                this.btns[i].dataset.renderizado = true;
                renders.push(this.btns[i])
            }
            
            i++;
        }
        

        if(renders.length > 0){
            evento(renders, "click", this.favorita.bind(this))
            
            var i = 0;
            while(i < renders.length){
            
                if(renders[i].dataset.ativo && renders[i].dataset.ativo == "true"){
                      renders[i].innerHTML = this.textFinit
                      renders[i].classList.add("ativo")
                    
                }else{
                  renders[i].innerHTML = this.textInit
                }
                
                i++;
            }
            

        }

    }
    
    favorita(){
        
        var btn = event.currentTarget
        
        var modulo = btn.dataset.estrutura
        var url = btn.dataset.url
        
        if(btn.dataset.ativo && btn.dataset.ativo=="true"){
            btn.dataset.ativo = false;
            btn.classList.remove("ativo")
            btn.innerHTML = this.textInit
            var acao = "desfavoritar"
            iziToast.success({
                icon: "bi bi-check-circle-fill",
    title: 'Sucesso',
    message: 'Item desfavoritado com sucesso'
});

                       
        }else{
            btn.dataset.ativo = true;
            btn.classList.add("ativo")
            btn.innerHTML = this.textFinit  
            var acao = "favoritar"
            
               iziToast.success({
                    icon: "bi bi-check-circle-fill",
    title: 'Sucesso',
    message: 'Item favoritado com sucesso'
});


        }
        
        
        // let request = new Request(`${dominio}/conteudo/modulos/favoritos/admins/api.php`)
        let request = new RequestRote('favoritos', 'api');
        request.addData({
            acao: acao,
            modulo: modulo,
            url: url
        })
        request.send().then((r)=>{
            console.log(r)
        }, (r)=>{
            console.log(r)
        })
        
        
    }
}

class Gif{
     constructor(grade, search, cb) {
        this.row = document.createElement("DIV");
        this.row.classList.add("listaGifs");
        grade.appendChild(this.row);
        this.cb = cb;
        this.search = search;

        nownFiles.add("https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js").then(() => {
            this.pesquisa("Brasil");
        });

        this.debounceTimeout;

        this.search.addEventListener('input', this.debounce(() => this.handleInput(), 300));
    }

    debounce(func, delay) {
        return () => {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(func, delay);
        };
    }

    handleInput() {
        var busca = !this.search.value ? "Brasil" : this.search.value;
        this.pesquisa(busca);
    }
    
    pesquisa(termo){
    
        this.row.innerHTML = "";
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            var obj = JSON.parse(request.responseText)
            var imgs = obj.data;
            var i = 0;
            var fragment = document.createDocumentFragment();
             let divWidth = this.row.offsetWidth / 3;
            while(i < imgs.length){
                var img = imgs[i]

              
                var imagem = document.createElement("IMG")
                var url = img.images["fixed_width"].url;
                imagem.classList.add("gifs")
                imagem.dataset.imagem = url
                imagem.src = url;
                imagem.height = (parseInt(img.images["fixed_width"].height) / 200) * divWidth
                imagem.width = divWidth
                evento(imagem, "click", this.cb.bind(this))

                fragment.appendChild(imagem)
                i++;
            }
            this.row.appendChild(fragment)
               


                  new Masonry( this.row, {

  itemSelector: '.gifs',
  columnWidth: divWidth
});
 
        }
        request.open("GET", `https://api.giphy.com/v1/gifs/search?api_key=t1CRQZid50OOha1BYjckIDJoT3UPDZX3&q=${termo}&limit=25&offset=0&rating=g&lang=pt&bundle=messaging_non_clips`)
        request.send()
    }
}

class ProfileView{
    constructor(){
       
        const items = document.querySelectorAll('.profileView:not([data-ativo="true"])');
        this.processar = [];
        items.forEach(item => {
            item.dataset.ativo = true;
            if(item.dataset.user){
                this.processar.push({
                    componente: item,
                    user: item.dataset.user
                })
            }
        });
        
        if(this.processar.length > 0){
           import("https://unpkg.com/@popperjs/core@2").then(() => {
               return import("https://unpkg.com/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js");})
               .then(() => {this.render(); })
               .catch(error => {
                   console.error("Erro ao carregar os scripts:", error);
               });

            
        
        }
    }
    
    render(){

        var i = 0;
        while(i < this.processar.length){
            let user = this.processar[i].user;
            evento(this.processar[i].componente, "click", ()=>{
                 goUrl(`usuarios/${user}`)
            })
           
            tippy(this.processar[i].componente , {
                arrow: true,
                interactive: true,
                allowHTML: true,
                delay: 500,
                content: `
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`,

                onShow(instance) {
                    // let request = new Request(`${dominioscript}/conteudo/modulos/usuarios/admins/api.php`)
                    let request = new RequestRote('usuarios', 'api');
                    request.addData({acao: "userInfor", user: user})
                    request.send().then((r)=>{
                        var user = r.user
                        var imagem = trataImagem(user.f, "mini");
                        if(!imagem){
                            var imagem = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`
                        }
                        
                        var capa = trataImagem(user.c, "media")
                        if(!capa){
                            capa = `${dominio}/conteudo/modulos/usuarios/midias/capa.webp` 
                        }
                        
                        
                          var div = document.createElement("DIV")
                          div.classList.add("card", "wi-250")
                        div.innerHTML = `
                        <div class="card-header p-0">
                          <div class="he-80 position-relative background" style="background-image: url(${capa});")>
                                <div class="position-absolute top-100 start-50 translate-middle wi-70 he-70 rounded-circle background" style="background-image: url(${imagem});")></div>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                        <h2 class="text-center fs-16 m-0 fw-500">${user.d}</h2>
                                <div class="text-center fs-14">@${user.u}</div>
                                <div class="row my-3">
                                    <div class="col-6">
                                        <div class="fs-16 text-center fw-700">${user.seguidores}</div>
                                        <div class="fs-14 text-center">Seguindo</div>
                                    </div>
                                    <div class="col-6 border-start border-end">
                                        <div class="fs-16 text-center fw-700">${user.seguindo}</div>
                                        <div class="fs-14 text-center">Seguindo</div>
                                    </div>
                                </div>
                                <div class="containerBtn">
                                
                                </div>
                        
                        </div>
                    `
                    
                    var btn = document.createElement("BUTTON")
                    btn.classList.add("btn","text-primaria","d-block","w-100","fw-700")
                    btn.innerText = `Ver Perfil`
                    div.getElementsByClassName("containerBtn")[0].appendChild(btn)
                    evento(btn, "click",  ()=>{
                        goUrl(`usuarios/${user.u}`)
                    })
                     instance.setContent(div);
                         
                    }, (r)=>{
                        
                    })
                     
                },
            });
            
            i++;
        }

    }
    
    go(){
       console.log("ta indo") 
    }
}

class Mensionador {
    constructor(input) {
        this.input = input;
        
        if (!autenticado()) {
            return; 
        }


        const arquivos = [
            `${dominioscript}/assets/aplicativo/zurb/tribute.min.js`,
            `${dominioscript}/assets/aplicativo/zurb/tribute.css`
        ];

        nownFiles.add(arquivos).then(() => {
            this.init(); // Chama diretamente sem bind
        });
    }
    
    procurar(text, cb){
        // let request = new Request(`${dominioscript}/conteudo/modulos/usuarios/admins/api.php`)
        let request = new RequestRote('usuarios', 'api');
        request.addData({"acao": "procurar", "termo": text})
        request.send().then((r)=>{
            var resultados = r.resultados
            var tratados = [];
            var i = 0;
            while(i < resultados.length){
                resultados[i].key = resultados[i].d
                resultados[i].value = resultados[i].u
                tratados.push(resultados[i])
                i++;
            }
             cb(tratados);
        })
    }

    init() {
        const tribute = new Tribute({
            trigger: '@',
            values: (text, cb)=> {
                this.procurar(text, users => cb(users));
            },
            selectTemplate: function (item) {
                return `<span class="text-primaria mensionadorArroba" contenteditable="false" data-i="${item.original.i}">@${item.original.value}</span>`;
            },
             menuItemTemplate: function (item) {
                 var  imagem = trataImagem(item.original.f, "mini");
                 if(!imagem){
                     imagem = `${dominioscript}/conteudo/modulos/usuarios/midias/perfil.jpg`
                 }
                 return '<div class="d-flex justify-content-start gap-2 align-items-center"><img src="'+imagem + '" class="wi-35 he-35 rounded-circle"><span>' + item.string +'</span></div>';
                 
             }
        });

        tribute.attach(this.input);
    }
    
    
    get(){
        var mensoes = {}
        var divs = this.input.getElementsByClassName("mensionadorArroba");
        
        if(divs.length > 0){
            var i = 0;
            while(i < divs.length){
                var div = divs[i]
                mensoes[div.dataset.i] = div.innerText
                i++;
            }
        }
        return mensoes;


    }
}

function loadMore(pai, callback, inverso = false, options = {}) {
    // Remove loader anterior, se existir
    const antigo = pai.querySelector('.loader-observer');
    if (antigo) antigo.remove();

    // Cria o marcador de interseção
    const marcador = document.createElement('div');
    marcador.className = 'loader-observer';
    marcador.style.height = '2px';
    marcador.style.width = '100%';

    // Adiciona o marcador no início ou fim, conforme o parâmetro 'inverso'
    if (!inverso) {
        pai.appendChild(marcador);
    } else {
        pai.insertBefore(marcador, pai.firstChild);
    }

    // Cria o observer com opções customizáveis
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                callback(marcador);
                if (!options.keepObserving) {
                    obs.disconnect();
                }
            }
        });
    }, {
        root: null,
        rootMargin: options.rootMargin || '0px',
        threshold: options.threshold || 0.1
    });

    observer.observe(marcador);
}

class Compartilhar{
    constructor(){
        this.init.bind(this)();
    }
    
    init(){
        let itens = document.querySelectorAll('.btnCompartilhar:not([data-ativo="true"])');
        
        if(itens.length > 0){
            this.estrutura.bind(this)();
        }
        
        var i = 0;
        while(i < itens.length){
            itens[i].dataset.ativo = true;
            evento(itens[i], "click", this.compartilhar.bind(this))
            i++;
        }
        this.estrutura.bind(this)();
    }
    
    compartilhar(){
        console.log('aqui')
        var btn = event.currentTarget
        var estrutura = btn.dataset.estrutura;
        var id = btn.dataset.url
        
        if(!id || !estrutura){
            var url = window.location.href
        }else{
            var url = `${dominio}/${estrutura}/${id}`
        }
        this.url = url;
        
        this.div.getElementsByClassName("urlinput")[0].innerText = this.url
        
        this.fluatuante.show();
    }
    
    btn(item){

        var btn = document.createElement("BUTTON")
        btn.classList.add("btn", "rounded-circle", "wi-50","he-50","d-flex","justify-content-center","align-items-center")
        var icone = document.createElement("I")
        btn.title = item.texto
        btn.dataset.foco = item.foco
        icone.classList.add("bi" , item.icone.trim() , "fs-24", "text-light")
        btn.style.backgroundColor = `#${item.cor}`
        btn.appendChild(icone)
        evento(btn, "click", this.share.bind(this))
        var div = document.createElement("DIV")
        div.appendChild(btn)
        
        return div;
        
    }
    
    share() {
    var foco = event.currentTarget.dataset.foco;
    var url = encodeURIComponent(this.url); 
    
    let shareUrl = '';
    let largura = 600;
    let altura = 400;
    let left = (screen.width / 2) - (largura / 2);
    let top = (screen.height / 2) - (altura / 2);
    
    switch (foco) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            break;
        case 'whatsapp':
            shareUrl = `https://api.whatsapp.com/send?text=${url}`;
            break;
        case 'twiter':
            shareUrl = `https://twitter.com/intent/tweet?url=${url}`;
            break;
        case 'email':
            shareUrl = `mailto:?subject=Compartilhamento&body=${url}`;
            break;
        case 'pinteres':
            shareUrl = `https://pinterest.com/pin/create/button/?url=${url}`;
            break;
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${url}`;
            break;
        default:
            alert("Plataforma de compartilhamento não reconhecida.");
            return;
    }
    
    // Abre uma nova janela pop-up com o link de compartilhamento
    window.open(shareUrl, 'Compartilhar', `width=${largura},height=${altura},top=${top},left=${left},toolbar=no,menubar=no,scrollbars=no,resizable=no`);
}

    estrutura(){
        if(document.getElementById("boxFlutuadorCompartilhar")){
            this.div = document.getElementById("boxFlutuadorCompartilhar");
            this.definirFlutuante.bind(this)()
            return;
        }
        
        let interno = "";
        if(autenticado() && dataModule("d56c396ec8f3800e3f1b218da4634a00")){
            interno = `
             <div class="text-center">
                    Compartilhar em uma postagem
                    
                    <div class="d-flex justify-content-center align-items-center">
                        <button class="btn btn-dark rounded-pill my-3">Criar Postagem</button>
                    </div>
                </div>
            <hr>
            
            `
        }
        
        var div = document.createElement("DIV")
        div.id = "boxFlutuadorCompartilhar"
        div.classList.add("d-none")
        div.innerHTML = `
        <div class="controller"></div>
            <div class="px-3 pt-5 pb-3 pre">
                    ${interno}
                
                    <h2 class="text-center">Compartilhar</h2>
               
               <div>
             
               
               <div class="d-flex my-4 gap-3 justify-content-center btns flex-wrap">
                 
               </div>
               </div>
                
                <div>
                <div class="position-relative m-auto form-control rounded-pill copy" style="max-width:500px">
                    <div class="d-block w-100 border-0 urlinput fs-14 border-0"></div>
                    <button class="btn btn-n-primaria rounded-pill position-absolute top-50 translate-middle-y" style="right: 5px">Copiar</button>
                </div> 
                </div>
            </div>
        </div>
        `
        
        div.getElementsByClassName("copy")[0].addEventListener("click", this.copiar.bind(this))
        var redes = [
            {icone: "bi-facebook", "texto": "Facebook", "cor":"3b5998", "foco":"facebook"},
            {icone: "bi-whatsapp", "texto": "WhatsApp", "cor":"25d366", "foco":"whatsapp"},
            {icone: "bi-twitter-x", "texto": "X", "cor": "000000" ,"foco":"twiter"},
            {icone: "bi-envelope", "texto": "E-mail", "cor": "7533f9" ,"foco":"email"},
            {icone: "bi-pinterest", "texto": "Pinterest", "cor": "e60023" ,"foco":"pinteres"},
            {icone: "bi-linkedin", "texto": "Linkedin", "cor": "0073b2", "foco":"linkedin"},
            ];
            
            var i = 0;
            while(i < redes.length){
                div.getElementsByClassName("btns")[0].appendChild(this.btn.bind(this)(redes[i]))
                i++;
            }
        
        this.div = div;
        document.getElementById("conteudo").appendChild(this.div)
        
        this.definirFlutuante.bind(this)()
        
    }
    
    definirFlutuante(){
        this.fluatuante = new nownCanvas("boxFlutuadorCompartilhar", {
            dirDesktop : "bottom",
            dirMobile : "bottom",
            backdropBlur : true,
            persist:  true,
            // backdropClose : false,
            // escapeClose : false,
            // desktopPan : false,
            // mobilePan : false,
        });
    }
    
    copiar(){
         if (navigator.clipboard) {
    navigator.clipboard.writeText(this.url)
      .then(() => {
            this.fluatuante.hide();
            
            
        setTimeout(()=>{
            Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: "Link copiado com Sucesso",
            showConfirmButton: false,
            timer: 1500
            
        });
        },100)
      
      })
      .catch(err => {
        console.error('Failed to copy: ', err);
      });
  } else {
    console.error('Clipboard API not supported');
  }
    }
}

function fluxoPage(quebra) {
    var url = window.location.href;
    var prefixo = dominio;
       if(pegaLocal("idioma")){
            prefixo = `${prefixo}/${pegaLocal("idioma")}`;
        }
    
    var quebraPath = `${prefixo}/${quebra}`;
    
    if (!url.includes(quebraPath)) {
        return [];
    }
    
    var trato = url.split(quebraPath)[1]; 

    if (!trato) {
        return [];
    }
    
    var resto = trato.split("/");
    var final = [];
    
    for (var i = 0; i < resto.length; i++) {
        if (resto[i]) {
            final.push(resto[i]);
        }
    }
    
    return final;
}

function dataBela(dataString){

        const data = new Date(dataString);
        
        const agora = new Date();
        
        if (data.toDateString() === agora.toDateString()) {
            return data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        }


    const ontem = new Date(agora);
    ontem.setDate(ontem.getDate() - 1);
    if (data.toDateString() === ontem.toDateString()) {
        return "Ontem";
    }


    return data.toLocaleDateString('pt-BR');
}

function tempoRelativo(dataPHP) {
    // Converte a string de data vinda do PHP em um objeto Date
    var dataItem = new Date(dataPHP);
    var agora = new Date();
    
    // Calcula a diferença em milissegundos
    var diferencaMS = agora - dataItem;
    var diferencaMinutos = Math.floor(diferencaMS / 60000); // Milissegundos para minutos
    var diferencaHoras = Math.floor(diferencaMS / 3600000); // Milissegundos para horas
    var diferencaDias = Math.floor(diferencaMS / 86400000); // Milissegundos para dias

    // Se a diferença for menor ou igual a 1 minuto
    if (diferencaMinutos < 1) {
        return "Agora Mesmo";
    }
    // Se for menor que 60 minutos
    else if (diferencaMinutos < 60) {
        return diferencaMinutos === 1 ? "1 minuto" : `${diferencaMinutos} minutos`;
    }
    // Se for menor que 24 horas
    else if (diferencaHoras < 24) {
        return diferencaHoras === 1 ? "1 hora" : `${diferencaHoras} horas`;
    }
    // Se foi ontem
    else if (diferencaDias === 1) {
        return "Ontem";
    }
    // Se for menor que 31 dias
    else if (diferencaDias < 31) {
        return diferencaDias === 1 ? "1 dia" : `${diferencaDias} dias`;
    }
    // Se for menor que 7 dias, retorna o dia da semana
    else if (diferencaDias < 7) {
        var diasSemana = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
        return diasSemana[dataItem.getDay()];
    }
    // Se for menor que 12 meses, retorna em meses
    else if (diferencaDias < 365) {
        var diferencaMeses = Math.floor(diferencaDias / 30); // Aproximação de meses
        return diferencaMeses === 1 ? "1 mês" : `${diferencaMeses} meses`;
    }
    // Se for mais de 1 ano, retorna em anos
    else {
        var diferencaAnos = Math.floor(diferencaDias / 365);
        return diferencaAnos === 1 ? "1 ano" : `${diferencaAnos} anos`;
    }
}

function limpaLoad() {
    var componentes = document.querySelectorAll('.componenteCarregando');
    componentes.forEach(function(componente) {
        componente.remove();
    });
}

function observador(containerSelector, itemSelector, cb) {
    const container = containerSelector
    const item = itemSelector;

    if (!container || !item) {
        console.error('Container ou item não encontrados!');
        return;
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                cb(true)
            } else {
                cb(false)
            }
        });
    }, {
        root: container,  // Definimos o container como a área de visualização
        threshold: 0.1    // Define a visibilidade mínima (10%) para considerar o item visível
    });

    observer.observe(item);
}

function videoDetector(url) {
   
    const youtubeRegex = /(?:youtube\.com\/.*[?&]v=|youtu\.be\/)([^&#]+)/;
    const vimeoRegex = /vimeo\.com\/(\d+)/;

    let resultado = {};

    // Verifica se é uma URL do YouTube
    let youtubeMatch = url.match(youtubeRegex);
    if (youtubeMatch) {
        resultado.tipo = "youtube";
        resultado.id = youtubeMatch[1];
        return resultado;
    }

    // Verifica se é uma URL do Vimeo
    let vimeoMatch = url.match(vimeoRegex);
    if (vimeoMatch) {
        resultado.tipo = "vimeo";
        resultado.id = vimeoMatch[1];
        return resultado;
    }

    // Se não for um vídeo válido, retorna false
    return false;
}

function dataBr(dataPHP) {
    var partesDataHora = dataPHP.split(' ');
    var data = partesDataHora[0].split('-'); // Separa ano, mês e dia
    var hora = partesDataHora[1].split(':'); // Separa hora e minutos
    
    var ano = data[0];
    var mes = data[1];
    var dia = data[2];
    
    var horas = hora[0];
    var minutos = hora[1];
    
    // Retorna a data no formato 15/10/2024 20h55
    return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
}

function dropopcoes(cb = false){
    var btn = event.currentTarget
    let drop = start.cardOpcoes;
    drop.loading();
    drop.show()
    
    if(cb){
        cb(drop , btn)
    }
    
    
}

class FluxoBubble{
    constructor(){
 
        this.showing = false;
        
        var config = JSON.parse(pegaLocal("nown") || '{}');
        

        this.logo = trataImagem(config.config?.geral?.logotipo?.logo ?? `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`);
        this.config = config?.config?.atendimento || false;
        
        if(!this.config || !this.valor("configuracao", "ativar", false)){ 
            return;
        }
        
        
        this.setup.bind(this)();


        this.render.bind(this)();
    }
    
    setup(){
      switch (parseInt(this.valor("configuracao", "layout", 1))) {
    case 1:
        // WhatsApp
        this.infos = {
            btn: "#21bd5b",
            header: "#008069",
            icone: "bi bi-whatsapp",
            mensagem: "Conversar pelo WhatsApp"    
            
        };
        break;
    case 2:
        // Telegram
        this.infos = {
            btn: "#0088cc",
            header: "#0088cc",
            icone: "bi bi-telegram",
            mensagem: "Conversar pelo Telegram"
        };
        break;
    case 3:
        // Facebook
        this.infos = {
            btn: "#1877f2",
            header: "#1877f2",
            icone: "bi bi-facebook",
            mensagem: "Conversar pelo Facebook"
        };
        break;
    case 4:
        // Instagram
        this.infos = {
            btn: "#e4405f",
            header: "#833ab4",
            icone: "bi bi-instagram",
            mensagem: "Conversar pelo Instagram"
        };
        break;
    case 5:
        // Caso 5 ainda n達o foi definido, pode ser preenchido depois
        this.infos = {
            btn: "#cccccc",
            header: "#aaaaaa",
            icone: "bi bi-question-circle",
            mensagem: "Iniciar Conversa",
        };
        break;
    default:
        // Caso padr達o para valores n達o esperados
        this.infos = {
            btn: "#000000",
            header: "#000000",
            icone: "bi bi-exclamation-triangle"
        };
}
    
    
       

    }
    
    valor(um, dois, fallback) {
        let valor = this.config?.[um]?.[dois] ?? fallback;
        
        if (valor === "true" || valor === "false") {
            return JSON.parse(valor);
            
        }
        
        return valor;
        
    }

    box() {
        
        var url = false;
        
        let nome = "Atendimento";
        let status = "Online";
        let foto = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`;
        let boas = "Seja muito bem-vindo!\n Posso te ajudar?"
        let multiplo = false;
        if(this.valor("configuracao", "atendentes", "1") === "1"){ 
             var atendente = parseInt(this.valor("configuracao", "atendente", "1"));
            if(this.valor(`atendente-${atendente}`, "link", false)){
                url = this.valor(`atendente-${atendente}`, "link", false);
            }
            
           
            
            nome = this.valor(`atendente-${atendente}`, "nome", false) ?? nome;
            status = this.valor(`atendente-${atendente}`, "texto", false) ?? texto;
            if(this.valor(`atendente-${atendente}`, "foto", false) && this.valor(`atendente-${i}`, "foto", false) != "[]"){
                foto = trataImagem(this.valor(`atendente-${atendente}`, "foto", false), "mini");
            }
            boas = this.valor(`atendente-${atendente}`, "boas", false) ?? boas;
        }else{
            multiplo = true;
            status = "Escolhe o atendente"
            foto = this.logo;

        }
        
        
        
        
        

    // Cria a div principal do card
    var div = document.createElement("div");
    div.classList.add("card", "card-nown", "position-absolute","overflow-hidden", "shadow", "d-none", "animate__fadeInUp", "animate__animated");
    div.style = 'width: 380px; bottom: calc(100% + 20px); max-width: calc(100vw - 40px); z-index: 9999;';
    if(this.valor("configuracao", "posicao", "1") === "1"){
        div.classList.add("end-0")
    }else{
        div.classList.add("start-0")
    }

    // Card Header
    var header = document.createElement("div");
    header.classList.add("card-header", "d-flex", "justify-content-between", "p-4");
    header.style.backgroundColor =  this.infos.header;

    // Header Left Section
    var headerLeft = document.createElement("div");
    headerLeft.classList.add("d-flex", "gap-2", "align-items-center");

    // Avatar Container
    var avatarContainer = document.createElement("div");
    avatarContainer.classList.add("he-50", "wi-50", "background", "rounded-circle", "position-relative");
    avatarContainer.style.backgroundImage = `url(${foto})`;

    var statusDot = document.createElement("div");
    statusDot.style = "width: 10px; height: 10px; background-color: green; border: 1px solid white;";
    statusDot.classList.add("position-absolute", "bottom-0", "end-0", "rounded-circle");
    avatarContainer.appendChild(statusDot);

    // User Info
    var userInfo = document.createElement("div");
    var userName = document.createElement("h2");
    userName.classList.add("m-0", "fs-16", "text-light");
    userName.textContent = nome;

    var userStatus = document.createElement("span");
    userStatus.classList.add("text-light", "fs-12");
    userStatus.textContent = status;

    userInfo.appendChild(userName);
    userInfo.appendChild(userStatus);

    headerLeft.appendChild(avatarContainer);
    headerLeft.appendChild(userInfo);

    // Close Button
    var closeButton = document.createElement("button");
    closeButton.classList.add("btn");

    var closeIcon = document.createElement("i");
    closeIcon.classList.add("bi", "bi-x", "text-white");
    closeButton.appendChild(closeIcon)
    closeButton.addEventListener("click", this.show.bind(this));

    header.appendChild(headerLeft);
    header.appendChild(closeButton);

    // Card Body
    var body = document.createElement("div");
    body.classList.add("card-body", "background", "d-flex", "flex-column", "gap-4", "p-4");
    body.style.backgroundImage = "url(https://static.elfsight.com/apps/all-in-one-chat/patterns/background-whatsapp.jpg)";

    // Message
    var messageContainer = document.createElement("div");
    messageContainer.classList.add("d-flex");

   if(!multiplo){
        var messageBox = document.createElement("div");
    messageBox.classList.add("bg-white", "p-2", "rounded");
    var messageText = document.createElement("p");
    messageText.classList.add("m-0", "fs-14", "text-dark");
    messageText.innerHTML = boas;

    messageBox.appendChild(messageText);
    messageContainer.appendChild(messageBox);
   }else{
       messageContainer.classList.add("flex-column", "gap-2")
       
       
       var i = 1;
       while(i < 4){
           if(this.valor(`atendente-${i}`, "link", false)){
               
                let nomeatentente = "Atendimento";
                let statusatendente = "Online";
                let fotoatendente = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`;
               
                if(this.valor(`atendente-${i}`, "nome", false)){
                    nomeatentente = this.valor(`atendente-${i}`, "nome", false);
                }
                
                if(this.valor(`atendente-${i}`, "texto", false)){
                   statusatendente = this.valor(`atendente-${i}`, "texto", false); 
                }
                
                
                if(this.valor(`atendente-${i}`, "foto", false) && this.valor(`atendente-${i}`, "foto", false) != "[]"){
                    fotoatendente = trataImagem(this.valor(`atendente-${i}`, "foto", false), "mini");
                }
               
               
               var card = document.createElement("DIV")
               card.classList.add("card", "bg-white", "border-0", "card-nown")
               
               var corpo = document.createElement("DIV")
               corpo.classList.add("card-body")
               card.appendChild(corpo)
               corpo.innerHTML = `
               <div class="d-flex gap-2 align-items-center">
               <div class="he-50 wi-50 background rounded-circle position-relative" style="background-image: url(${fotoatendente});">
                <div class="position-absolute bottom-0 end-0 rounded-circle" style="width: 10px; height: 10px; background-color: green; border: 1px solid white;"></div>
                </div>
                <div>
                <h2 class="m-0 fs-16 text-dark">${nomeatentente}</h2>
                <span class="text-dark fs-12">${statusatendente}</span>
                </div>
                </div>
               
               `
               var a = document.createElement("A")
               a.href = this.valor(`atendente-${i}`, "link", false)
               a.classList.add("text-decoration-none")
               a.setAttribute("target", "_blank")
               a.appendChild(card)
               
               messageContainer.appendChild(a)
           }
           i++;
       }
       
       
       
       
   }
 
    // WhatsApp Button
    var buttonContainer = document.createElement("div");
    buttonContainer.classList.add("d-flex", "justify-content-center");

    var whatsAppButton = document.createElement("A");
    whatsAppButton.classList.add("btn", "rounded-pill", "btn-nown-style", "fs-18", "fw-700", "text-light", "d-flex", "align-items-center", "gap-2");
    whatsAppButton.style.backgroundColor = this.infos.btn;
    whatsAppButton.href = url;
    whatsAppButton.setAttribute("target", "_blank")

    var iconSpan = document.createElement("span");
    var whatsAppIcon = document.createElement("i");
    whatsAppIcon.className = this.infos.icone
    whatsAppIcon.classList.add("fs-20", "text-white");
    iconSpan.appendChild(whatsAppIcon);

    var buttonText = document.createElement("span");
    buttonText.textContent = this.infos.mensagem

    whatsAppButton.appendChild(iconSpan);
    whatsAppButton.appendChild(buttonText);
    buttonContainer.appendChild(whatsAppButton);

   
    body.appendChild(messageContainer);
    
    if(url && !this.valor("configuracao", "entrada", false)){
       body.appendChild(buttonContainer);
       
    }
    

    div.appendChild(header);
    div.appendChild(body);
    
    
    if(url && this.valor("configuracao", "entrada", false)){
        var footer = document.createElement("DIV")
        footer.classList.add("card-footer", "d-flex", "justify-content-between", "gap-2", "align-items-center")
        footer.innerHTML = `
        <div class="flex-fill"><input class="form-control he-50" placeholder="Digite sua mensagem ..."></div>
        <div><a href="${url}" target="_blank" class="btn he-50 wi-50 d-flex justify-content-center align-items-center" style="background-color:${this.infos.btn}"><i class="bi bi-chevron-right"></i></a></div>
        `
        div.appendChild(footer)
        
    }
    
    

    this.boxmensagem = div;
    this.container.appendChild(this.boxmensagem);
}

    show(){
        if(!this.boxmensagem){
            this.box.bind(this)();
        }
        
        if(this.showing){
              this.primeButton.classList.remove("show")

             this.showing = false;
             this.boxmensagem.classList.add("d-none")
        }else{
             this.showing = true;
             this.boxmensagem.classList.remove("d-none")
             this.primeButton.classList.add("show")

        }
    }
    
    render(){
        
        if(document.getElementById("backTop")){
            document.getElementById("backTop").classList.add("btnFlowContato")
        }
        
        
        var div = document.createElement("DIV")
        div.classList.add("position-absolute")
        
        if(this.valor("configuracao", "posicao", "1") === "1"){
            div.style = "right: 30px; bottom: 30px";
        }else{
             div.style = "left: 30px; bottom: 30px";
        }
        
       
        
        if(this.valor("configuracao", "modelo", "1") === "1"){
            var button = document.createElement("BUTTON")
            button.addEventListener("click", this.show.bind(this))
            button.innerHTML = `<i class="${this.infos.icone} fs-25 text-white icone"></i><i class="bi bi-x-lg fs-30 icone2"></i>`

        }else{
            var atendente = parseInt(this.valor("configuracao", "atendente", "1"));
            
            var button = document.createElement("A")
            button.href = this.valor(`atendente-${atendente}`, "link", "");
            button.setAttribute("target", "_blank")
            button.innerHTML = `<i class="${this.infos.icone} fs-25 text-white"></i>`
        }
        
        
        button.classList.add("btn","he-50","wi-50","rounded-circle","shadow","d-flex","align-items-center","justify-content-center", "btnFlowContato")
        button.style = `background-color: ${this.infos.btn}`;
      
        this.primeButton = button;
        
        div.appendChild(button)
        this.container = div;

        document.getElementsByTagName("body")[0].appendChild(div)
    }
}

class Denunciar{
    constructor(estrutura , url){
        this.estrutura = estrutura;
        this.url = url
        this.init.bind(this)();
    }
    
    init(){
        if(document.getElementById("boxFlutuadorDenunciar")){
            this.div = document.getElementById("boxFlutuadorDenunciar");
            return;
        }
        
        let request = new Request(`${dominioscript}/conteudo/modulos/denuncias/api/tipos.json`);
        request.send().then((r)=>{
            
            
            var ul = document.createElement("DIV")
            ul.classList.add("list-group","list-group-flush")
            
            
            var lista = r.lista
            
            var i = 0;
            while(i < lista.length){
                var button = document.createElement("BUTTON")
                button.classList.add("list-group-item","list-group-item-action", "d-flex", "justify-content-between", "align-items-center", "w-100")
                button.addEventListener("click", this.proximo.bind(this))
                button.dataset.valor = lista[i].v
                var span = document.createElement("SPAN")
                span.classList.add("fs-16", "fw-700")
                span.innerText = lista[i].t
                
                var icone = document.createElement("SPAN")
                icone.innerHTML = `<i class="bi bi-chevron-right"></i>`
                
                button.appendChild(span)
                button.appendChild(icone)
                
                ul.appendChild(button)
                
                i++;
            }
            
            
            var div = document.createElement("DIV")
        div.id = "boxFlutuadorDenunciar"
        div.classList.add("d-none")
        div.innerHTML = `
        <div class="controller"></div>
            <div class="px-3 pt-5 pb-3 pre">
               
            
                    
                    
                    <div id="caroselDenuncia" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
                  <div class="lista">
                  <h2 class="text-center">Denunciar</h2>
                  </div>
    </div>
    <div class="carousel-item">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0">Denunciar</h2>
        <div>
            <button class="btn fw-700 text-decoration-underline btnBack">Voltar</button>
        </div>
    </div>
        <textarea class="form-control he-300 inputDenuncia" placeholder="Descreva sua denúncia"></textarea>
        <button class="d-block mt-3 w-100 btn btn-n-primaria btnEnviar" disabled>
            Enviar Denúncia
        </button>
        
    </div>

  </div>


</div>
               
    
                
                <div>
               
                </div>
            </div>
        </div>
        `
            div.getElementsByClassName("lista")[0].appendChild(ul)
            this.btn = div.getElementsByClassName("btnEnviar")[0]
            this.input = div.getElementsByClassName("inputDenuncia")[0]
            this.input.addEventListener("input", this.digitando.bind(this))
            div.getElementsByClassName("btnBack")[0].addEventListener("click", ()=>{
                    this.carrosel.to(0);
            })
            this.btn.addEventListener("click", this.denunciar.bind(this))
            document.getElementById("canvasControler").appendChild(div)
            this.carrosel = new bootstrap.Carousel(document.getElementById("caroselDenuncia"), {
                      interval: 2000,
                      touch: false
                      
                  })
                  
            this.fluatuante = new nownCanvas("boxFlutuadorDenunciar", {
                dirDesktop : "bottom",
                dirMobile : "bottom",
                backdropBlur : true,
                persist:  true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
            setTimeout(()=>{
                this.fluatuante.show();
            }, 100)
          
        }, (r)=>{
            console.log(r)
        })

    }
    
    denunciar(){
        let request = new Request(`${dominioscript}/conteudo/modulos/denuncias/api.php`);
        request.addData({
            valor: this.valor,
            estrutura: this.estrutura,
            url: this.url,
            texto: this.input.value
        })
        request.send((r)=>{
            console.log(r)
        }, (r)=>{
            console.log(r)
        })
        this.fluatuante.hide();
    }
    
    digitando(){
        if(this.input.value){
            this.btn.removeAttribute("disabled")
        }else{
            this.btn.setAttribute("disabled", "")
        }
    }
    
    proximo(){
        this.valor = event.currentTarget.dataset.valor
        this.carrosel.to(1);
        setTimeout(()=>{
             this.input.focus();
         }, 500)
    }
    
    comeca(estrutura, url){
        
        this.estrutura = estrutura;
        this.url = url
        
        
        this.btn.setAttribute("disabled", "")
        this.input.value = "";
        this.carrosel.to(0);
        this.fluatuante.show();
    }
}

function denunciar(estrutura = false, url = false){
    if(!autenticado()){
        new Restrito();
        return;
    }
    
    
    if(!estrutura || !url){
        console.log("Não foram enviados os módulos e URL válidos");
        return;
    }
    
    
    if(!start.dropDenuncia){
        start.dropDenuncia = new Denunciar(estrutura, url);
    }else{
        start.dropDenuncia.comeca(estrutura, url);
    }
}

function bounce(inputElement, delay = 500, cb) {
  let timer;

  // Adiciona um listener para o evento de entrada de dados
  inputElement.addEventListener("input", () => {
    // Limpa o timer anterior se o usuário ainda está digitando
    clearTimeout(timer);

    // Inicia um novo timer com o tempo especificado
    timer = setTimeout(() => {
      // Executa o callback com o valor do input quando o usuário para de digitar
      cb(inputElement.value);
    }, delay);
  });
}

function pegaCores(imagemSrc, quantidadeDeCores = 5) {
    return new Promise((resolve, reject) => {
        const imagem = new Image();
        imagem.crossOrigin = 'Anonymous'; // Necessário para imagens externas
        imagem.src = imagemSrc;

        imagem.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = imagem.width;
            canvas.height = imagem.height;
            ctx.drawImage(imagem, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;

            const cores = {};
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                
                const cor = `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
                cores[cor] = (cores[cor] || 0) + 1;
            }

            const coresOrdenadas = Object.entries(cores)
                .sort((a, b) => b[1] - a[1])
                .slice(0, quantidadeDeCores)
                .map(c => c[0]);
            
            resolve(coresOrdenadas);
        };

        imagem.onerror = () => {
            reject(new Error("Erro ao carregar a imagem."));
        };
    });
}

class Graficos{
    constructor(modulo = false, identificador = false, container = false){
        this.modulo = modulo;
        this.identificador = identificador;
        console.log(this.modulo, this.identificador)
        if(!this.modulo || !this.identificador){
            return;
        }
        
        if(container){
            this.pre.bind(this)(container);
        }
        this.graficos = true;
        this.inicializado = false;
        this.prefixo = "";
        this.init.bind(this)();
    }
    
    pre(container){
        var div = document.createElement("DIV")
        div.classList.add("card", "card-nown", "h-100")
        this.html = div;
        this.header = document.createElement("DIV")
        this.header.classList.add("card-header", "d-flex", "justify-content-between", "align-items-center", "border-0")
        this.header.innerHTML = `<div class="esquerda d-flex gap-1 align-items-center"><div class="live-indicator d-none">
  <span class="pulse"></span> 
</div></div><div class="direita"></div>`
        div.appendChild(this.header)
        
        
        this.body = document.createElement("DIV")
        this.body.classList.add("card-body")
        this.body.innerHTML = `
       <div class="grafico h-100"></div>
        
        `
        
        
        div.appendChild(this.body)
        
        this.footer = document.createElement("DIV")
        this.footer.classList.add("card-footer", "text-end", "border-0", "fs-12")
        div.appendChild(this.footer)
        
        container.appendChild(div)
    }
    
    highlightUpdate(element) {
  element.classList.add('updated');
  setTimeout(() => element.classList.remove('updated'), 2000);
}
    
    proporcional(dados, layout){
        


        let obj =  {

  tooltip: {
    trigger: 'item'
  },
  legend: {
    orient: 'vertical',
    left: 'left'
  },
  series: [
    {
      name: 'Access From',
      type: 'pie',
      radius: '50%',
      data: dados,
      emphasis: {
        itemStyle: {
          shadowBlur: 10,
          shadowOffsetX: 0,
          shadowColor: 'rgba(0, 0, 0, 0.5)'
        }
      }
    }
  ]
};

  
        return obj;
    }
    
    temporal(dados, layout = "line"){
      
              var i = 0;
                    var indices = [];
                    var valores = [];
                    while(i < dados.length){
                        var coluna = dados[i]
     
                        indices.push(coluna.tempo);
                        valores.push(coluna.value)
                        i++;
                    }
                    
                 
        
        let obj = {
  xAxis: {
    type: 'category',
    data: indices
  },
  yAxis: {
    type: 'value'
  },
  series: [
    {
      data: valores,
      type: layout,
      areaStyle: {}
    }
  ]
};

        return obj;
    }
    
    estrutura(r){
        if(r.timer){
            this.header.getElementsByClassName("live-indicator")[0].classList.remove("d-none")
        }

        
        switch(parseInt(r?.extra?.layout || 1)){
            case 2:
                this.html.classList.add(r.extra.listaCores)
                
                var cor = colorContrast(this.html);
                this.html.classList.add(`text-${cor}`)

                
                
                break;
            case 3:
                this.html.style.backgroundColor = r.extra.bg
            this.html.style.color = r.extra.color
                break;
        }
        
        this.body.style= `background-image: url(${dominio}/includes/midia/bg/10.png); background-size: cover`;
    
                
                this.inicializado = true;
                
            
                const daysDifference = moment().diff(moment(r.tempo[0], "YYYY-MM-DD HH:mm"), 'days');
      
         
   
                 let ranges = {};
                 ranges["Hoje"] = [moment().startOf('day'), moment()]; // Começo do dia de hoje até o momento atual
                 if(r.layout.ho){
                    ranges["Última Hora"] = [moment().subtract(1, 'hour'), moment()]; // Última hora 
                 }
                 ranges["Ontem"] = [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')]; // Ontem completo
                 ranges["Últimas 24 Horas"] = [moment().subtract(24, 'hours'), moment()]; // Últimas 24 horas
                 ranges["Últimos 7 dias"] = [moment().subtract(7, 'days'), moment()]; // Últimos 7 dias
                 ranges["Última Semana"] = [moment().startOf('week').startOf('day'), moment()]; // Começo da semana até o momento atual
                 ranges["Esse mês"] = [moment().startOf('month').startOf('day'), moment().endOf('month')]; // Começo do mês até o final do mês
                 ranges["Últimos 30 dias"] = [moment().subtract(30, 'days'), moment()]; // Últimos 30 dias
                 ranges["Todo Período"] = [moment(r.tempo[0], "YYYY-MM-DD HH:mm"), moment(r.tempo[1], "YYYY-MM-DD HH:mm")]; // Período customizado
                
                
             
            if(r.layout.rod){
                this.footer.classList.add("d-none")
            }
            
            if(r.layout.sp){
                


                
                
                switch(r.layout.pi){
                    case "Hoje":
                        var comeco = moment().startOf('day');
                        break;
                    case "24 Horas":
                        var comeco = moment().subtract(24, 'hours')
                        break;
                    case "Total":
                        var comeco = r.tempo[0];
                        break;
                    case "Última Semana":
                        var comeco = moment().startOf('week').startOf('day')
                        break;
                    case "Últimos 7 Dias":
                        var comeco = moment().subtract(7, 'days')
                        break;
                    case "Último Mês":
                        var comeco = moment().startOf('month').startOf('day');
                        break;
                    case "Últimos 30 Dias":
                        var comeco = moment().subtract(30, 'days')
                        break;
                }

                
                let config = {
                    startDate: moment(comeco , "YYYY-MM-DD HH:mm"), 
                    endDate: moment(r.tempo[1], "YYYY-MM-DD HH:mm"), 
                    minDate: moment(r.tempo[0], "YYYY-MM-DD HH:mm"),
                    maxDate: moment(r.tempo[1], "YYYY-MM-DD HH:mm"),
                    ranges: ranges
                };
                
                if(r.layout.ho){
                    config.timePicker = true;
                    config.timePickerIncrement = 1;
                    config.timePicker24Hour = true;
                }

                var input = document.createElement("INPUT")
                input.classList.add("form-control", "form-control-sm", "rounded-pill", "px-2", "fs-12")
                
                var div = document.createElement("DIV")
                div.appendChild(input)
                this.input = input;
                this.header.getElementsByClassName("direita")[0].appendChild(div)
                dataPicker(input, (start, end) => {
                    this.comeco = start.format("YYYY-MM-DD HH:mm");
                    this.fim = end.format("YYYY-MM-DD HH:mm");
                    
                    
                    var string = dataBr(this.comeco) + `  -  ` + dataBr(this.fim);
                    this.footer.innerText = string
                    this.init.bind(this)(true);
                     
                 }, config);
            }
            
            if(r.layout.ti){
                var div = document.createElement("DIV")
                var h2 = document.createElement("DIV")
                h2.classList.add("m-0", "fs-18","fw-700")
                h2.innerText = r.layout.ti
                div.appendChild(h2)
                  this.header.getElementsByClassName("esquerda")[0].appendChild(div)
            }
            
            if(r.layout.pi){
                this.footer.innerText = r.layout.pi

            }else{
                this.footer.innerText =  "Todo Periodo"
            }
            
            
    }
    
    trato(valor, trato){
        if(!trato){
            return valor;
        }else{
            console.log(trato)
            switch(trato){
                case 'inteiro':
                    return parseInt(valor);
                    break;
                case 'dinheiro':
                     return parseFloat(valor).toFixed(2);
                    break;
            }

        }
    }
    
    init(){
        var request = new Request(`${dominio}/admin/graficos.php`);
        var obj = {
            modulo: this.modulo,
            identificador:this.identificador,
        };
        if(this.comeco){
            obj.comeco = this.comeco
        }
        if(this.fim){
            obj.fim = this.fim
        }
        
        
        
        request.addData(obj)
        request.send().then((r)=>{
            
            
            if(r.timer){
                setTimeout(()=>{
                    this.init.bind(this)();
                }, (parseInt(r.timer) * 1000))
            }
 
            var layouts = {
                "linha": "line",
                "área": 'line',
                "barras temporais": 'bar',
                "pizza": "pie"
            }
            
 
            switch(r.t){
                case 'numerico':
                    
                    if(r.ag == 1){
                        this.prefixo = r.extra.prefixo ?? "";
                        this.sufixo = r.extra.sufixo ?? "";
                        this.total =  `${this.prefixo} ${this.trato(r.dados[0].value ?? 0, r.layout.tra)} ${r.extra.sufixo}`.trim();
                        this.graficos = false;
                        
                        
                        
                    }
                   
                    break;
                case "proporcional":
    
                    var option = this.proporcional.bind(this)(r.dados, layouts[r.layout.gr[0]]);
                    break;
                case "tempo":

                    var option = this.temporal.bind(this)(r.dados, layouts[r.layout.gr[0]]);
                    break;
            }
    

            
            console.log(this.inicializado)

            if(!this.inicializado){
                
                    
                 
                
                 this.estrutura.bind(this)(r)
                
                 if(this.graficos){
                        const echarts = window.echarts;
                        this.body.classList.add("p-0")
                        var chartDom = this.body.getElementsByClassName("grafico")[0]
                        this.chart = echarts.init(chartDom);
          

                    
                    
                    option && this.chart.setOption(option);
                 }else{
                        this.div = document.createElement("DIV")
                        this.div.classList.add("d-flex", "fw-900")
                        
                        this.texto = document.createElement("SPAN")
                        
                        
                        if(this.total.length < 5){
                            this.texto.classList.add("fs-70")
                        }else if(this.total.length < 10){
                            this.texto.classList.add("fs-55")
                        }else if(this.total.length < 15){
                            this.texto.classList.add("fs-40")
                        }else{
                            this.texto.classList.add("fs-30")
                        }
                        this.div.appendChild(this.texto)
                        this.body.getElementsByClassName("grafico")[0].appendChild(this.div)
                        
                        
                        
    
                        this.texto.innerText = this.total
                    }
                  
                 
                
                 
                
                    
            }
            else{
                if(this.graficos){
                    this.chart.setOption(option);
                }else{
                    this.texto.className = "";
                    if(this.total.length < 5){
                            this.texto.classList.add("fs-70")
                        }else if(this.total.length < 10){
                            this.texto.classList.add("fs-55")
                        }else if(this.total.length < 15){
                            this.texto.classList.add("fs-40")
                        }else{
                            this.texto.classList.add("fs-30")
                        }

                     this.texto.innerText = this.total
                     this.highlightUpdate.bind(this)(this.texto) 
                }
                
            }
        })
    }
    
    update(a, b, valor = false){
         this.comeco = a.format("YYYY-MM-DD HH:mm");
         this.fim = b.format("YYYY-MM-DD HH:mm"); 
         
         if(valor){
             if(this.input){
                  this.input.value = valor
             }
            
         }

         
         var string = dataBr(this.comeco) + `  -  ` + dataBr(this.fim);
         this.footer.innerText = string
         this.init.bind(this)();
    }
}

function colorContrast(element){
    
    const bgColor = window.getComputedStyle(element).backgroundColor;

    // Converte a cor para valores RGB
    const rgb = parseColor(bgColor);

    // Calcula a luminância relativa
    const luminance = calculateLuminance(rgb);

    // Retorna o contraste ideal
    return luminance > 0.5 ? "dark" : "light";

function parseColor(color) {

    if (color.startsWith("rgb")) {
        const match = color.match(/(\d+),\s*(\d+),\s*(\d+)/);
        return { r: parseInt(match[1]), g: parseInt(match[2]), b: parseInt(match[3]) };
    }
    // Caso seja HEX
    if (color.startsWith("#")) {
        return hexToRgb(color);
    }
    // Caso seja um nome de cor
    const tempElement = document.createElement("div");
    tempElement.style.color = color;
    document.body.appendChild(tempElement);
    const computedColor = window.getComputedStyle(tempElement).color;
    document.body.removeChild(tempElement);
    return parseColor(computedColor); // Reprocessa como RGB
}

// Função para converter cores HEX para RGB
function hexToRgb(hex) {
    hex = hex.replace("#", "");
    if (hex.length === 3) {
        hex = hex.split("").map(c => c + c).join("");
    }
    const bigint = parseInt(hex, 16);
    return {
        r: (bigint >> 16) & 255,
        g: (bigint >> 8) & 255,
        b: bigint & 255,
    };
}

// Função para calcular a luminância relativa
function calculateLuminance({ r, g, b }) {
    const a = [r, g, b].map(v => {
        v /= 255;
        return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
    });
    return 0.2126 * a[0] + 0.7152 * a[1] + 0.0722 * a[2];
}

}

class EstilizadorQr{
    constructor(div = false, url = false){
        this.div = div
        this.url = url
        
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
        
        objQrCode.data = this.url
        
        objQrCode.backgroundOptions = this.pegaCor(local?.corbg || 1, "#fff", local.corbgpersonalizada , local.corbginicial , local.corbgfinal , local.corbgrotacao);
        
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
        
        this.objQrCode = objQrCode
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
    
    render(){
        console.log(this.div)
        if(!this.div){
            console.log('O elemento da div para insercao do qr não foi passado')
            return
        }
        
        if(!this.url){
            console.log('O elemento para gerar a url não foi passado')
            return
        }
        
        nownFiles.add(`${dominio}/assets/aplicativo/qrcode/index.js`).then(()=>{
            const qrCode = new QRCodeStyling(this.objQrCode);
            qrCode.append(this.div);
        })
    }
}

class VideoModal {
            /**
             * Construtor
             * @param {string} target - Seletor CSS do botão que ativará o modal (ex: '#meuBotao')
             * @param {string} url - URL do vídeo (YouTube, Vimeo ou MP4)
             * @param {string} title - Título do vídeo para exibir no modal
             */
            constructor(target, url, title) {
                // Propriedades
                this.target = document.querySelector(target);
                this.url = url;
                this.title = title;
                this.modalId = 'modal-' + Math.random().toString(36).substr(2, 9);
                this.playerId = 'player-' + Math.random().toString(36).substr(2, 9);
                
                // Dependências
                this.dependencies = {
                    loaded: false,
                    plyrCss: 'https://cdnjs.cloudflare.com/ajax/libs/plyr/3.7.8/plyr.min.css',
                    plyrJs: 'https://cdnjs.cloudflare.com/ajax/libs/plyr/3.7.8/plyr.min.js'
                };
                
                // Player e Modal
                this.player = null;
                this.bsModal = null;
                this.modalElement = null;
                this.loading = false;
                
                // Verificar se o elemento alvo existe
                if (!this.target) {
                    console.error(`Elemento alvo "${target}" não encontrado.`);
                    return;
                }
                
                // Inicializar
                this.init();
            }
            
            /**
             * Inicializa a classe
             */
            init() {
                // Adicionar evento de clique ao botão alvo
                this.target.addEventListener('click', () => this.handleButtonClick());
            }
            
            /**
             * Manipula o clique no botão
             */
            async handleButtonClick() {
                // Se já estiver carregando, não faz nada
                if (this.loading) return;
                
                this.loading = true;
                
                try {
                    // Carregar dependências se ainda não foram carregadas
                    if (!this.dependencies.loaded) {
                        await this.loadDependencies();
                    }
                    
                    // Criar e mostrar o modal
                    this.createAndShowModal();
                } catch (error) {
                    console.error('Erro ao carregar dependências do player:', error);
                } finally {
                    this.loading = false;
                }
            }
            
            /**
             * Carrega as dependências do Plyr usando nownFiles
             * @returns {Promise} Promise resolvida quando tudo estiver carregado
             */
            loadDependencies() {
                return new Promise((resolve, reject) => {
                    // Array de arquivos para carregar
                    const filesToLoad = [
                        this.dependencies.plyrCss,
                        this.dependencies.plyrJs
                    ];
                    
                    // Usar a função nownFiles para carregar os arquivos
                    nownFiles.add(filesToLoad)
                        .then(() => {
                            this.dependencies.loaded = true;
                            resolve();
                        })
                        .catch(err => {
                            console.error('Erro ao carregar arquivos:', err);
                            reject(err);
                        });
                });
            }
            
            /**
             * Detecta o tipo de URL do vídeo
             * @returns {Object} Informações do tipo de vídeo
             */
            detectVideoType() {
                // YouTube
                if (/youtube\.com\/watch\?v=([^&]+)/.test(this.url) || 
                    /youtu\.be\/([^?]+)/.test(this.url)) {
                    
                    const matches = this.url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/);
                    const videoId = matches ? matches[1] : null;
                    
                    return {
                        type: 'youtube',
                        id: videoId,
                        provider: 'youtube',
                        attributes: {
                            'data-plyr-provider': 'youtube',
                            'data-plyr-embed-id': videoId
                        }
                    };
                }
                
                // Vimeo
                if (/vimeo\.com\/(\d+)/.test(this.url)) {
                    const matches = this.url.match(/vimeo\.com\/(\d+)/);
                    const videoId = matches ? matches[1] : null;
                    
                    return {
                        type: 'vimeo',
                        id: videoId,
                        provider: 'vimeo',
                        attributes: {
                            'data-plyr-provider': 'vimeo',
                            'data-plyr-embed-id': videoId
                        }
                    };
                }
                
                // MP4 ou outra URL direta
                if (/\.(mp4|webm|ogv)(\?|$)/.test(this.url)) {
                    return {
                        type: 'video',
                        url: this.url,
                        provider: 'html5',
                        tag: 'video',
                        attributes: {
                            src: this.url,
                            controls: true,
                            preload: 'metadata',
                            poster: '' // Você pode adicionar uma URL de poster se necessário
                        }
                    };
                }
                
                // Default: assumir YouTube e tentar extrair ID
                // Isso pode acontecer para URLs não padrão do YouTube
                const possibleYoutubeId = this.url.split('/').pop().split('?')[0].split('&')[0];
                
                return {
                    type: 'youtube',
                    id: possibleYoutubeId,
                    provider: 'youtube',
                    attributes: {
                        'data-plyr-provider': 'youtube',
                        'data-plyr-embed-id': possibleYoutubeId
                    }
                };
            }
            
            /**
             * Cria o elemento HTML do modal
             * @returns {HTMLElement} Elemento do modal
             */
            createModalElement() {
                const videoInfo = this.detectVideoType();
                
                // Criar container do modal
                const modalElement = document.createElement('div');
                modalElement.className = 'modal fade video-modal';
                modalElement.id = this.modalId;
                modalElement.setAttribute('tabindex', '-1');
                modalElement.setAttribute('aria-labelledby', `${this.modalId}-label`);
                modalElement.setAttribute('aria-hidden', 'true');
                
                // Estrutura do modal
                modalElement.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="${this.modalId}-label">${this.title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <div id="${this.playerId}"></div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Adicionar ao DOM
                document.body.appendChild(modalElement);
                
                return modalElement;
            }
            
            /**
             * Inicializa o player de vídeo baseado no tipo
             */
            initializePlayer() {
                // Verificar se o Plyr está disponível
                if (typeof Plyr === 'undefined') {
                    console.error('Plyr não está disponível. Dependências podem não ter sido carregadas corretamente.');
                    return;
                }
                
                const videoInfo = this.detectVideoType();
                const playerContainer = document.getElementById(this.playerId);
                
                // Configurar o elemento de acordo com o tipo de vídeo
                if (videoInfo.type === 'video') {
                    // Vídeo HTML5 direto
                    const videoElement = document.createElement('video');
                    
                    // Adicionar atributos
                    Object.entries(videoInfo.attributes).forEach(([key, value]) => {
                        videoElement.setAttribute(key, value);
                    });
                    
                    playerContainer.appendChild(videoElement);
                    
                    // Inicializar Plyr
                    this.player = new Plyr(videoElement, {
                        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen']
                    });
                    
                    // Adicionar evento para reiniciar o vídeo quando terminar
                    this.player.on('ended', () => {
                        this.player.currentTime = 0; // Resetar para o início quando terminar
                    });
                } else {
                    // YouTube ou Vimeo
                    // Adicionar atributos ao container
                    Object.entries(videoInfo.attributes).forEach(([key, value]) => {
                        playerContainer.setAttribute(key, value);
                    });
                    
                    // Inicializar Plyr
                    this.player = new Plyr(playerContainer, {
                        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
                        [videoInfo.provider]: {
                            noCookie: true,
                            rel: 0,
                            showinfo: 0,
                            iv_load_policy: 3,
                            modestbranding: 1
                        }
                    });
                    
                    // Adicionar evento para reiniciar o vídeo quando terminar
                    this.player.on('ended', () => {
                        this.player.restart(); // Método específico para reiniciar vídeos de plataformas
                    });
                }
            }
            
            /**
             * Cria e exibe o modal
             */
            createAndShowModal() {
                // Verificar se o modal já foi criado
                if (!this.modalElement) {
                    // Criar o modal
                    this.modalElement = this.createModalElement();
                    
                    // Inicializar o modal Bootstrap com opções para impedir fechamento ao clicar fora
                    this.bsModal = new bootstrap.Modal(this.modalElement, {
                        backdrop: 'static',  // Impede que o modal feche ao clicar fora
                        keyboard: false      // Impede que o modal feche com a tecla ESC
                    });
                    
                    // Configurar eventos
                    this.modalElement.addEventListener('hidden.bs.modal', () => this.onModalHidden());
                    this.modalElement.addEventListener('shown.bs.modal', () => this.onModalShown());
                }
                
                // Mostrar o modal
                this.bsModal.show();
            }
            
            /**
             * Manipulador de evento quando o modal é exibido
             */
            onModalShown() {
                // Inicializar o player se ainda não foi feito
                if (!this.player) {
                    this.initializePlayer();
                }
                
                // Iniciar a reprodução
                if (this.player) {
                    setTimeout(() => {
                        this.player.play();
                    }, 300); // Pequeno delay para garantir que o player esteja pronto
                }
            }
            
            /**
             * Manipulador de evento quando o modal é fechado
             */
            onModalHidden() {
                // Pausar o vídeo e voltar para o início
                if (this.player) {
                    this.player.pause();
                    this.player.currentTime = 0; // Resetar o vídeo para o início
                }
            }
            
            /**
             * Destrói a instância, removendo eventos e elementos
             */
            destroy() {
                // Remover evento do botão
                this.target.removeEventListener('click', () => this.handleButtonClick());
                
                // Destruir o player
                if (this.player) {
                    this.player.destroy();
                }
                
                // Remover o modal do DOM
                if (this.modalElement) {
                    this.modalElement.remove();
                }
                
                // Limpar referências
                this.player = null;
                this.bsModal = null;
                this.modalElement = null;
            }
        }
        
class DigitalAuth {
    /**
     * Inicializa a classe DigitalAuth
     * @param {Object} options - Opções de configuração
     * @param {Function} options.onError - Callback para tratamento de erros
     * @param {Function} options.onStatus - Callback para atualização de status
     */
    constructor(options = {}) {
        this.options = {
            onError: (error) => console.error('DigitalAuth error:', error),
            onStatus: (message) => console.log('DigitalAuth status:', message),
            ...options
        };
        
        // Verificar compatibilidade do navegador com WebAuthn
        this.isWebAuthnSupported = typeof window !== 'undefined' && !!window.PublicKeyCredential;
        
        // Verificar suporte a autenticação biométrica
        this.isBiometricSupported = false;
        
        if (this.isWebAuthnSupported) {
            PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable()
                .then(available => {
                    this.isBiometricSupported = available;
                    
                    if (!available) {
                        this.options.onStatus('Seu dispositivo não possui sensor de impressão digital ou autenticação biométrica.');
                    }
                })
                .catch(error => {
                    this.options.onError('Erro ao verificar autenticador biométrico: ' + error.message);
                });
        } else {
            this.options.onStatus('Seu navegador não suporta WebAuthn. Autenticação biométrica não disponível.');
        }
    }
    
    /**
     * Verifica se o dispositivo e navegador suportam WebAuthn e autenticação biométrica
     * @returns {Object} Objeto com informações de compatibilidade
     */
    checkCompatibility() {
        return {
            webAuthnSupported: this.isWebAuthnSupported,
            biometricSupported: this.isBiometricSupported
        };
    }
    
    /**
     * Captura a impressão digital do usuário
     * @param {string} userId - Identificador único do usuário (ex: email)
     * @returns {Promise} Promise que resolve com os dados da impressão digital
     */
    async pega(userId) {
        if (!this.isWebAuthnSupported) {
            throw new Error('WebAuthn não suportado neste navegador');
        }
        
        try {
            this.options.onStatus('Iniciando captura de impressão digital...');
            
            // Gerar um desafio aleatório
            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);
            
            // Criar ID de usuário a partir do userId fornecido
            const userIdBuffer = new TextEncoder().encode(userId);
            
            // Preparar opções para a criação da credencial
            const publicKeyCredentialCreationOptions = {
                challenge: challenge,
                rp: {
                    name: 'Sistema de Autenticação Biométrica',
                    id: window.location.hostname || 'localhost'
                },
                user: {
                    id: userIdBuffer,
                    name: userId,
                    displayName: userId
                },
                pubKeyCredParams: [
                    { type: 'public-key', alg: -7 },   // ES256
                    { type: 'public-key', alg: -257 }  // RS256
                ],
                authenticatorSelection: {
                    authenticatorAttachment: 'platform', // Sensor biométrico do dispositivo
                    userVerification: 'required',        // Exige verificação biométrica
                    requireResidentKey: false
                },
                timeout: 60000,  // 60 segundos
                attestation: 'none'
            };
            
            // Solicitar ao navegador para criar a credencial
            const credential = await navigator.credentials.create({
                publicKey: publicKeyCredentialCreationOptions
            });
            
            // Processar os dados da credencial
            const credentialData = {
                id: credential.id,
                rawId: this._arrayBufferToBase64(credential.rawId),
                type: credential.type,
                response: {
                    attestationObject: this._arrayBufferToBase64(credential.response.attestationObject),
                    clientDataJSON: this._arrayBufferToBase64(credential.response.clientDataJSON)
                },
                authenticatorAttachment: credential.authenticatorAttachment,
                registeredAt: new Date().toISOString()
            };
            
            this.options.onStatus('Impressão digital capturada com sucesso!');
            
            // Retorna os dados da credencial para armazenamento no backend
            return credentialData;
            
        } catch (error) {
            this.options.onError('Erro ao capturar impressão digital: ' + error.message);
            throw error;
        }
    }
    
    /**
     * Valida a impressão digital do usuário
     * @param {string} userId - Identificador único do usuário
     * @param {Object} credentialData - Dados da credencial previamente armazenados
     * @returns {Promise} Promise que resolve com o resultado da validação
     */
    async valida(userId, credentialData) {
        if (!this.isWebAuthnSupported) {
            throw new Error('WebAuthn não suportado neste navegador');
        }
        
        if (!credentialData || !credentialData.id || !credentialData.rawId) {
            throw new Error('Dados de credencial inválidos ou ausentes');
        }
        
        try {
            this.options.onStatus('Iniciando verificação de impressão digital...');
            
            // Gerar um desafio aleatório
            const challenge = new Uint8Array(32);
            window.crypto.getRandomValues(challenge);
            
            // Preparar opções para a verificação da credencial
            const publicKeyCredentialRequestOptions = {
                challenge: challenge,
                rpId: window.location.hostname || 'localhost',
                allowCredentials: [{
                    type: 'public-key',
                    id: this._base64ToArrayBuffer(credentialData.rawId),
                    transports: ['internal']
                }],
                timeout: 60000,  // 60 segundos
                userVerification: 'required'  // Exige verificação biométrica
            };
            
            // Solicitar ao navegador para verificar a credencial
            const assertion = await navigator.credentials.get({
                publicKey: publicKeyCredentialRequestOptions
            });
            
            // Processar a resposta da asserção
            const assertionResponse = {
                id: assertion.id,
                rawId: this._arrayBufferToBase64(assertion.rawId),
                type: assertion.type,
                response: {
                    authenticatorData: this._arrayBufferToBase64(assertion.response.authenticatorData),
                    clientDataJSON: this._arrayBufferToBase64(assertion.response.clientDataJSON),
                    signature: this._arrayBufferToBase64(assertion.response.signature),
                    userHandle: assertion.response.userHandle ? 
                        this._arrayBufferToBase64(assertion.response.userHandle) : null
                }
            };
            
            // Neste ponto, uma validação real verificaria a assinatura, o desafio, etc.
            // Como o foco é apenas na captura, retornamos o resultado da asserção para
            // validação externa conforme solicitado
            
            this.options.onStatus('Verificação de impressão digital concluída!');
            
            return {
                success: true,
                userId: userId,
                assertionResponse: assertionResponse,
                timestamp: new Date().toISOString()
            };
            
        } catch (error) {
            this.options.onError('Erro ao verificar impressão digital: ' + error.message);
            throw error;
        }
    }
    
    /**
     * Converte ArrayBuffer para Base64
     * @private
     * @param {ArrayBuffer} buffer - Buffer a ser convertido
     * @returns {string} String codificada em Base64
     */
    _arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.byteLength; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    }
    
    /**
     * Converte Base64 para ArrayBuffer
     * @private
     * @param {string} base64 - String Base64 a ser convertida
     * @returns {ArrayBuffer} ArrayBuffer resultante
     */
    _base64ToArrayBuffer(base64) {
        const binaryString = window.atob(base64);
        const bytes = new Uint8Array(binaryString.length);
        for (let i = 0; i < binaryString.length; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }
        return bytes.buffer;
    }
}

function createRanges(minDate, maxDate) {
    // Converte as datas para moment
    const min = moment(minDate);
    const max = moment(maxDate);
    
    // Inicializa os ranges com "Todo Período"
    const ranges = {
        "Todo Período": [min, max]
    };
    
    // Intervalos possíveis
    const possibleRanges = {
        "Hoje": [moment().startOf('day'), moment().endOf('day')],
        "Ontem": [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
        "Últimas 24 Horas": [moment().subtract(24, 'hours'), moment()],
        "Últimos 7 dias": [moment().subtract(7, 'days'), moment()],
        "Última Semana": [moment().startOf('week').startOf('day'), moment()],
        "Últimos 30 dias": [moment().subtract(29, 'days'), moment()],
        "Esse mês": [moment().startOf('month').startOf('day'), moment().endOf('month')],
        "Último Mês": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    };
    
    // Adiciona intervalos que têm pelo menos alguma sobreposição com [min, max]
    Object.keys(possibleRanges).forEach(key => {
        let [start, end] = possibleRanges[key];
        
        // Verifica se há sobreposição:
        // - Se o fim do intervalo é posterior ao início do período
        // - E o início do intervalo é anterior ao fim do período
        if (end.isSameOrAfter(min) && start.isSameOrBefore(max)) {
            // Ajusta os limites para não ultrapassar o período [min, max]
            const adjustedStart = start.isBefore(min) ? min.clone() : start;
            const adjustedEnd = end.isAfter(max) ? max.clone() : end;
            ranges[key] = [adjustedStart, adjustedEnd];
        }
    });
    
    return ranges;
}

class Filtro{
    constructor(modulo = false, hash = false, container = false, api = false, layout = {}){
        console.log("temos um filtro")
        this.modulo = modulo
        this.hash = hash;
        this.container = container;
        this.modelSelecionado = 0;
        this.pagina = 1;
        this.api = {}
        if(api && !this.verificarExtraObj(api)){
            this.api['extra'] = api
        }else if(api && this.verificarExtraObj(api)){
            this.api = api
        }
        
        this.layout = layout
        
        this.callbacks = [];
        this.ordenador = "recentes"
        this.map = {};
        this.filtrosValues = {}
        
        if(!this.modulo || !this.hash || !this.container){
            return;
        }
        
        this.init.bind(this)();
    }
    
    compararDatas(comeco, fim, min, max) {
        // Parsear as datas no formato brasileiro (DD/MM/YYYY)
        var dataComeco = moment(comeco, 'DD/MM/YYYY');
        var dataFim = moment(fim, 'DD/MM/YYYY');
        
        // Parsear as datas no formato americano (YYYY-MM-DD)
        var dataMin = moment(min, 'YYYY-MM-DD');
        var dataMax = moment(max, 'YYYY-MM-DD');
        
        // Verificar se as datas são válidas
        if (!dataComeco.isValid() || !dataFim.isValid() || !dataMin.isValid() || !dataMax.isValid()) {
            return false;
        }
        
        // Comparar se comeco é igual a min e fim é igual a max
        return dataComeco.isSame(dataMin, 'day') && dataFim.isSame(dataMax, 'day');
    }
    
    getCheckedInputs(componente) {
        // Seleciona todos os inputs do tipo checkbox que estão marcados dentro do componente
        const checkboxes = componente.querySelectorAll('input[type="checkbox"]:checked');
        
        // Converte a NodeList em um array e extrai o valor de cada checkbox
        return Array.from(checkboxes).map(checkbox => checkbox.value);
    }

    entradaDados(){
        

        this.filtrosValues = {};
        console.log(this.map)
        for(let c in this.map){
            var item = this.map[c]
            
       
            console.log(item)
            switch(item.dados.dados.tipo){
                case 'input':

                    this.filtrosValues[c] = item.html.getElementsByClassName("form-control")[0].value;
                    break;
                case 'data':
                    console.log(item)
                    var timeOnly = moment(item.dados.dados.datas.max, ['HH:mm:ss', 'HH:mm', 'H:mm', 'H:mm:ss'], true);
                    
                    if(timeOnly.isValid()){
                        var data = item.html.getElementsByClassName("entradaDados")[0]
                        var comeco = data.dataset.inicio
                        var fim = data.dataset.fim
                    }else{
                        var data = item.html.getElementsByClassName("entradaDados")[0].value.split("-");
            
                        var comeco = data[0].trim();
                        var fim = data[1].trim();
                    }
                    
                    
                    
 
                    var min = item.dados.dados.datas.min
                    var max = item.dados.dados.datas.max
                    
                    var filtrado = this.compararDatas(comeco, fim, min, max);
                    
                    if(!filtrado){
                        this.filtrosValues[c] = [comeco, fim];
                    }
                    
                    break;
                case 'imagem':
                    if(item.html.getElementsByClassName("entradaDados")[0].value){
                        this.filtrosValues[c] = item.html.getElementsByClassName("entradaDados")[0].value == "sim" ? true : false
                    }
                    break;

                case 'avaliacoes':
                case 'check':
                case 'enderecos':
                    var entradas = this.getCheckedInputs(item.html)
                    if(entradas.length > 0){
                        this.filtrosValues[c] = entradas;
                    }
                    break;
                    
                default:
                    
                    break;
            }

        }
        
        this.geraTags.bind(this)();
        
        this.zeraTudo.bind(this)();
  
    }
    
    tag(chave, texto, valor = false){
       var div = document.createElement("DIV")
       div.dataset.k = chave
       div.dataset.v = valor;
       div.classList.add("filter-tag", "animate__bounceIn", "animate__animated")
       var span = document.createElement("SPAN")
       span.innerHTML = texto
       
       var close = document.createElement("I")
       close.className = "bi bi-x close"
       evento(close, "click", this.removeFilter.bind(this))
       div.appendChild(span)
       div.appendChild(close)
       return div;
    }
    
    removeFilter(){
        var k = event.currentTarget.closest(".filter-tag").dataset.k
        var valor = event.currentTarget.closest(".filter-tag").dataset.v
        
        var html = this.map[k].html
        var item = this.map[k].dados.dados
        
        
        
        switch(item.tipo){
            case 'check':
            case 'avaliacoes':
            case 'enderecos':
                const checkbox = html.querySelector(`input[type="checkbox"][value="${valor}"]`);
                checkbox.checked = false;
                break;
            case 'input':
                html.getElementsByClassName("form-control")[0].value = "";
                break;
            case 'data':
                var min = item.datas.min
                var max = item.datas.max
                var timeOnly = moment(item.dados.dados.datas.max, ['HH:mm:ss', 'HH:mm', 'H:mm', 'H:mm:ss'], true);
                if(timeOnly.isValid()){
                    
                }else{
                    html.getElementsByClassName("entradaDados")[0].value = moment(min).isValid() && moment(max).isValid()
                    ? `${moment(min).format('DD/MM/YYYY')} - ${moment(max).format('DD/MM/YYYY')}`
                    : '';
                    
                    html.getElementsByClassName("entradaDados")[0].dispatchEvent(new Event("input"));
                }
                
                
        
                break;
            case 'imagem':
                html.getElementsByClassName("entradaDados")[0].value = "";
                break;
  
        }
        
        this.entradaDados.bind(this)();
        
    }
    
    geraTags(){
        if(Object.keys(this.filtrosValues).length === 0){
            this.filterTags.classList.add("d-none")
            return;
        }
         this.filterTags.innerHTML = "";
         this.filterTags.classList.remove("d-none")
         
         
         var fragmento = document.createDocumentFragment();
         for(let c in this.filtrosValues){
             var valores = this.filtrosValues[c];
             var filtro = this.map[c].dados.dados
             
             switch(filtro.tipo){
                 case 'check':
                     var i = 0;
                     while(i < valores.length){
                         var valor = valores[i]
                         
                         var texto = this.idToText(valor, filtro.opcoes)
                         fragmento.appendChild(this.tag(c, texto, valor))
                         
                         
                         i++;
                     }
                     
                     break;
                 case 'input':
                    if(valores){
                     fragmento.appendChild(this.tag(c, valores, valores))   
                    }
                     
                     break;
                 case 'data':
                     fragmento.appendChild(this.tag(c, `${valores[0]} - ${valores[1]}`))
                     break;
                 case 'imagem':
            
                     if(valores){
                          fragmento.appendChild(this.tag(c, `Com ${filtro.auxiliar}`))
                     }else{
                         fragmento.appendChild(this.tag(c, `Sem ${filtro.auxiliar}`))
                     }
                 
                     break;
                 case 'endereco':
                     break;
                 case 'avaliacoes':
                     
                     var i = 0;
                     while(i < valores.length){
                         fragmento.appendChild(this.tag(c, `${valores[i]} <i class="bi bi-star-fill fs-16 text-warning"></i>`, valores[i] ))
                         i++;
                     }
                    
                     
                     break;
                 
             }
             
             
         }
         this.filterTags.appendChild(fragmento)
         
         
   
    }
    
    datou(){
        console.log('aqui2')
        setTimeout(()=>{
            this.entradaDados.bind(this)();
        }, 200)
    }
    
    idToText(id, opcoes){
        id = parseInt(id);
        var i  = 0;
        while(i < opcoes.length){
            if(id == opcoes[i].valor){
                return opcoes[i].nome;
                break;
            }
            
            i++;
        }
        return false;
    }
    
    
    carregarSlide(div, minHour, maxHour){
        nownFiles.add(['https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css', 'https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js']).then(()=>{
             // Inicializar noUiSlider
            
            let rangeDiv = div.getElementsByClassName('time-range-slider')[0]
            noUiSlider.create(rangeDiv, {
                start: [minHour, maxHour],
                connect: true,
                range: {
                    'min': minHour,
                    'max': maxHour
                },
                step: 15, // Intervalos de 15 minutos
                format: {
                    to: function(value) {
                        return Math.round(value);
                    },
                    from: function(value) {
                        return Number(value);
                    }
                }
            });
            let timer;
            // Event listener para atualizar os valores
            rangeDiv.noUiSlider.on('update', (values, handle)=> {

                clearTimeout(timer);
                 
                timer = setTimeout(() => {
                    this.datou()
                }, 500);
                
                var startMinutes = parseInt(values[0]);
                var endMinutes = parseInt(values[1]);
                
                var startTime = moment().startOf('day').add(startMinutes, 'minutes').format('HH:mm');
                var endTime = moment().startOf('day').add(endMinutes, 'minutes').format('HH:mm');
                
                div.getElementsByTagName('span')[0].getElementsByTagName('strong')[0].textContent = startTime;
                div.getElementsByTagName('span')[1].getElementsByTagName('strong')[0].textContent = endTime;
                
                div.setAttribute('data-inicio', startTime)
                div.setAttribute('data-fim', endTime)

                
                
                
                
            });
        })
    }
    
    section(filter, horizontal = false){
         
            
            var section = document.createElement("DIV")
            section.classList.add("filter-section")
            console.log(filter.dados)
            if(filter?.dados?.key || false){
                this.map[filter.dados.key] = {html: section, dados: filter}
            }
            
            var id = geraId();
            var btn = document.createElement("DIV")
            btn.classList.add("filter-section-title")
            
            let chevron = ''
            if(!horizontal){
              btn.setAttribute("data-bs-toggle","collapse");
              btn.setAttribute("data-bs-target",`#${id}`);  
              chevron = '<i class="bi bi-chevron-down"></i>'
            }
             
              
              
              
             btn.innerHTML = `${filter.nome} ${chevron}` 
             
             
             var colapse = document.createElement("DIV")
             colapse.classList.add("collapse","show")
             colapse.id = id;
            
             switch(filter?.dados?.tipo || false){
                case 'input':
                case 'number':
                     var input = document.createElement("INPUT")
                     if(filter?.dados?.tipo == 'number'){
                         input.type = "number"
                     }else{
                         input.type = "text"
                     }
                     
                     input.classList.add("form-control")
                     bounce(input, 500, this.entradaDados.bind(this))
                     input.setAttribute("placeholder", "Procurar ...")
                     input.style.paddingLeft = "50px"
                     
                     
                     var f = document.createElement("DIV")
                     f.classList.add("position-relative")
                     
                     var icone = document.createElement("SPAN")
                     icone.classList.add("position-absolute", "top-50", "translate-middle-y")
                     icone.style.left = "20px";
                     icone.innerHTML = `<i class="bi bi-search"></i>`
                     
                     f.appendChild(input)
                     f.appendChild(icone)
                     
                     colapse.appendChild(f)
                     
                     
                      break;
                 case 'data':
                     
                     
                    var dataObj = filter.dados
                    var timeOnly = moment(dataObj.datas.max, ['HH:mm:ss', 'HH:mm', 'H:mm', 'H:mm:ss'], true);
                    if(timeOnly.isValid()){
                        var minHour = dataObj.datas.min.trim() != '' ? moment(dataObj.datas.min, 'HH:mm').hour() * 60 + moment(dataObj.datas.min, 'HH:mm').minute() : moment('00:00', 'HH:mm').hour() * 60 + moment('00:00', 'HH:mm').minute()
                        var maxHour = dataObj.datas.max.trim() != '' ? moment(dataObj.datas.max, 'HH:mm').hour() * 60 + moment(dataObj.datas.max, 'HH:mm').minute() : moment('00:00', 'HH:mm').hour() * 60 + moment('00:00', 'HH:mm').minute()
                        
                        // Criar container para o dual range
                        var input = document.createElement('DIV');
                        input.className = 'dual-range-container entradaDados';
                        input.style.margin = '20px 0';
                        
                        var rangeDiv = document.createElement('div');
                        rangeDiv.className = 'time-range-slider';
                        rangeDiv.style.margin = '20px 0';
                        
                        var displayDiv = document.createElement('div');
                        console.log(minHour, maxHour)
                        displayDiv.innerHTML = `
                            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                                <span>Início: <strong>${dataObj.datas.min.trim() != '' ? dataObj.datas.min.trim() : '00:00'}</strong></span>
                                <span>Fim: <strong>${dataObj.datas.max}</strong></span>
                            </div>
                        `;
                        
                        this.carregarSlide.bind(this)(input, minHour, maxHour)
                        
                        input.appendChild(rangeDiv);
                        input.appendChild(displayDiv);
                        
                       
                        
                        
                    } else {
                        
                            var input = document.createElement("INPUT")
                         input.type = "text"
                         input.classList.add("form-control", "entradaDados")
                        // Se é data completa, usar datePicker normal
                        dataPicker(input, this.datou.bind(this), {
                            ranges: createRanges(dataObj.datas.min, dataObj.datas.max),
                            startDate: moment(dataObj.datas.min),
                            endDate: moment(dataObj.datas.max),
                            minDate: moment(dataObj.datas.min),
                            maxDate: moment(dataObj.datas.max),
                            locale: {
                                format: 'DD/MM/YYYY',
                                customRangeLabel: "Personalizado",
                                applyLabel: "Aplicar",
                                cancelLabel: "Cancelar",
                                daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                                monthNames: [
                                    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                                    "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
                                ],
                                firstDay: 0
                            },
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerSeconds: true
                        });
                    }
                    
                      
                     colapse.appendChild(input)
                     break;
                 case 'imagem':
                     var input = document.createElement("SELECT")
                     input.classList.add("form-select", "entradaDados")
                     input.innerHTML = `
                     <option value="">Todo os itens</option>
                     <option value="sim">Com Imagens</option>
                     <option value="nao">Sem Imagens</option>
                     `
                     colapse.appendChild(input)
                     break;
                 case 'check':
                     var div = document.createElement("DIV");
                     var i = 0;
                     while(i < filter.dados.opcoes.length){
                         var opcao = filter.dados.opcoes[i]
           
                         var f = document.createElement("DIV")
                         f.classList.add("form-check","custom-checkbox","mb-2")
                         f.dataset.key = filter.dados.key
                         f.dataset.t = filter.dados.t
                         
                         var inpu = document.createElement("INPUT")
                         inpu.classList.add("form-check-input", "entradaDados")
                         inpu.type="checkbox" 
                         inpu.value = opcao.valor
                      
                          
                         var id = geraId();
                         inpu.id = id;
                            
                         var label = document.createElement("LABEL")
                         label.setAttribute("for", id)
                         label.classList.add("form-check-label")
                         label.innerText = opcao.nome;
                         
                         var span = document.createElement("SPAN")
                         span.classList.add("price-count")
                         span.innerText = `(${opcao.contagem})`
                         
                         label.appendChild(span)
                         
                         f.appendChild(inpu)
                         f.appendChild(label)
                         div.appendChild(f)
                         

                         
                   
                         i++;
                     }
                     
                     if(horizontal){
                 
                         
                        
                        var drop = document.createElement("DIV")
                        drop.classList.add("dropdown")
                        
                        var btna = document.createElement("BUTTON")
                        btna.classList.add("form-control" ,"dropdown-toggle", "d-flex", "w-100", "justify-content-between", "align-items-center")
                        btna.setAttribute("type","button");
                        btna.setAttribute("data-bs-toggle","dropdown");
                        btna.setAttribute("aria-expanded","false");
                        btna.innerHTML = `<span class="d-flex gap-2 justify-content-start align-items-center"><i class="bi bi-circle"></i> Nenhum Item Selecioando</span>`;
                        var ul = document.createElement("UL")
                        ul.classList.add("dropdown-menu", "w-100", "p-4")
                        drop.appendChild(btna)
                        drop.appendChild(ul)
                        
                        ul.appendChild(div)
                        /*
                        
                        <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    Dropdown button
  </button>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#">Action</a></li>
    <li><a class="dropdown-item" href="#">Another action</a></li>
    <li><a class="dropdown-item" href="#">Something else here</a></li>
  </ul>
</div>
                        */
                         colapse.appendChild(drop)
                         
                         
                         
                     }else{
                         colapse.appendChild(div)
                    
                     }
                     
                     
                     break;
                 case 'avaliacoes':
                     var div = document.createElement("DIV")
                     
                     for(let stars in filter.dados.valores){
                         var quantidade = filter.dados.valores[stars]
                         
                         var f = document.createElement("DIV")
                         f.classList.add("form-check","custom-checkbox","mb-2")
                         
                         var inpu = document.createElement("INPUT")
                         inpu.classList.add("form-check-input", "entradaDados")
                         inpu.type="checkbox" 
                         inpu.value = stars
                          var id = geraId();
                         inpu.id = id;
                         
                         var label = document.createElement("LABEL")
                         label.classList.add("form-check-label")
                         label.for = id;
                         
                         
                     
                         
                         var ratin = document.createElement("DIV")
                         ratin.classList.add("product-rating", "gap-1", "d-flex")
                         
                         
                         var z = 1;
                         while(z < 6){
                             var icone = document.createElement("I")
                             if(stars >= z){
                                  icone.classList.add("bi","bi-star-fill", "fs-18")
                             }else{
                                icone.classList.add("bi","bi-star", "fs-18")
                             }
                             ratin.appendChild(icone)
                             
                             z++;
                         }
                         
                         
                   
                         
                    
                         label.appendChild(ratin)
                         
                         var span = document.createElement("SPAN")
                         span.classList.add("price-count")
                         span.innerText = `(${quantidade})`;
                         label.appendChild(span)
                         
                         f.appendChild(inpu)
                         f.appendChild(label)
                         colapse.insertBefore(f, colapse.firstChild);
             
                         
                         
                  
                     }
                 
                     break;
                 case 'enderecos':
                     
                       var div = document.createElement("DIV");
                       
                       var input = document.createElement("INPUT")
                     input.type = "text"
                     input.classList.add("form-control")
                     bounce(input, 500, this.entradaDados.bind(this))
                     input.setAttribute("placeholder", "Digite o bairro ou rua")
                     input.style.paddingLeft = "50px"
                     
                     
                     var f = document.createElement("DIV")
                     f.classList.add("position-relative", "mb-3")
                     
                     var icone = document.createElement("SPAN")
                     icone.classList.add("position-absolute", "top-50", "translate-middle-y")
                     icone.style.left = "20px";
                     icone.innerHTML = `<i class="bi bi-search"></i>`
                     
                     f.appendChild(input)
                     f.appendChild(icone)
                     div.appendChild(f)
                       
                     var i = 0;
                     while(i < filter.dados.opcoes.length){
                         var opcao = filter.dados.opcoes[i]
           
                         var f = document.createElement("DIV")
                         f.classList.add("form-check","custom-checkbox","mb-2")
                         f.dataset.key = filter.dados.key
                         f.dataset.t = filter.dados.t
                         
                         var inpu = document.createElement("INPUT")
                         inpu.classList.add("form-check-input", "entradaDados")
                         inpu.type="checkbox" 
                         inpu.value = opcao.valor
                      
                          
                         var id = geraId();
                         inpu.id = id;
                            
                         var label = document.createElement("LABEL")
                         label.setAttribute("for", id)
                         label.classList.add("form-check-label")
                         label.innerText = opcao.nome;
                         
                         var span = document.createElement("SPAN")
                         span.classList.add("price-count")
                         span.innerText = `(${opcao.contagem})`
                         
                         label.appendChild(span)
                         
                         f.appendChild(inpu)
                         f.appendChild(label)
                         div.appendChild(f)
                         

                         
                   
                         i++;
                     }
                     
                     colapse.appendChild(div)
                     
                     break;
                 default:
                    colapse.innerHTML = `
             <div class="mt-2">
                               
                                <div class="form-check custom-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="category2">
                                    <label class="form-check-label" for="category2">
                                        Acessórios para Celular
                                        <span class="price-count">(157)</span>
                                    </label>
                                </div>
                                <div class="form-check custom-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="category3">
                                    <label class="form-check-label" for="category3">
                                        Tablets
                                        <span class="price-count">(68)</span>
                                    </label>
                                </div>
                                <div class="form-check custom-checkbox mb-2">
                                    <input class="form-check-input" type="checkbox" value="" id="category4">
                                    <label class="form-check-label" for="category4">
                                        Smartwatches
                                        <span class="price-count">(34)</span>
                                    </label>
                                </div>
                            </div>
             
             `
                    break;
             }
          
             section.appendChild(btn)
             section.appendChild(colapse)
             return section;
             
    }
    
    card(){
         var formato = parseInt(this.setup.filtro.ativo) === 3 || parseInt(this.setup.filtro.ativo) ==-4 ? true : false;
         
         
        var pai = document.createElement("DIV")
        pai.classList.add("filter-column")
        
        var card = document.createElement("DIV")
        card.classList.add("card","card-nown","filter-panel")
        
        var h4 = document.createElement("H4")
        h4.innerText = "Filtro"
        h4.classList.add("filter-title")
        
        var flexTopo = document.createElement("DIV")
        flexTopo.classList.add("d-flex", "justify-content-between", "align-items-center")
        flexTopo.appendChild(h4)
        
        if(formato){
            flexTopo.appendChild(this.btnLimpa())
        }
        
        
        card.appendChild(flexTopo)
        
       
        if(formato){
            var row = document.createElement("DIV")
            row.classList.add("row")
        }
        
        var i = 0;
        while(i < this.setup.basico.length){
            var filter = this.setup.basico[i]
            

            var section = this.section.bind(this)(filter, formato);
            
            if(formato){
                var col = document.createElement("DIV")
                col.classList.add("col")
                col.appendChild(section)
                row.appendChild(col)
            }else{
                 card.appendChild(section)
            }
            
             
             
            
            i++;
        }
        
        
          if(formato){
                 card.appendChild(row)
            }
        
        evento(Array.from(card.getElementsByClassName("entradaDados")), "input", this.entradaDados.bind(this))
        
        var i = 0;
        if(this.setup.avancados && this.setup.avancados.length > 0){
            var avancados = this.setup.avancados;
            var master = document.createElement("DIV")
           
            
           var avancado = avancados[i]
                
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn","btn-link","p-0", "text-decoration-none", "d-flex", "text-center", "align-items-center", "gap-2", "justify-content-center", "w-100", "my-3")
            btn.innerHTML = `<i class="bi bi-sliders me-1"></i>Filtros avançados `
            btn.setAttribute("type", "button")
            btn.setAttribute("data-bs-toggle", "collapse")
            var id = geraId();
            btn.setAttribute("data-bs-target",`#${id}`)
            btn.setAttribute("aria-expanded","true")
            btn.setAttribute("aria-controls", "filtrosAvancados")
            
            master.appendChild(btn)
            
             var beta = document.createElement("DIV")
             beta.classList.add("collapse")
             beta.id = id;

             master.appendChild(beta)
            
            
            while(i < avancados.length){
               
                var avancado = avancados[i]
                var section = this.section.bind(this)(avancado);

                beta.appendChild(section)
             
                
                
                i++;
            }
            card.appendChild(master)
        }
        
        
        if(!formato){
            card.appendChild(this.btnLimpa())
        }
        
        

        pai.appendChild(card)
        return pai;
    }
    
    btnLimpa(){
        var div = document.createElement("DIV")
        var limpar = document.createElement("BUTTON")
        limpar.classList.add("btn","text-danger","text-decoration-underline",  "fw-700", "text-uppercase", "fs-12")
        limpar.innerText = "Limpar Filtros"
        div.appendChild(limpar)
        return div;
    }
    
    header(card){
  
    
        
        var div = document.createElement("DIV")
        div.classList.add("results-header")
        
        var results = document.createElement("DIV")
        results.classList.add("results-count")
        var h5 = document.createElement("H5")
        h5.innerText = `Procurar itens ...`
        this.controlerResultados = h5;
        results.appendChild(h5)
        div.appendChild(results);
        
        
        var sort = document.createElement("DIV")
        sort.classList.add("sort-options")
        
        if(this.setup.ordenadores && this.setup.ordenadores.length > 1){
            
          
            var drop = document.createElement("DIV")
            drop.classList.add("dropdown");
            var id = geraId();
            var buttonPrincipal = document.createElement("BUTTON")
            buttonPrincipal.className ="btn btn-outline-secondary dropdown-toggle"
            buttonPrincipal.type = "button" 
            buttonPrincipal.setAttribute("data-bs-toggle","dropdown")
            buttonPrincipal.setAttribute("aria-expanded","false")
            buttonPrincipal.id = id;
            
         this.itensOrder = {
    "recentes": { nome: "Mais Recentes", icone: "bi-clock" },
    "antigos": { nome: "Mais Antigos", icone: "bi-hourglass-split" },
    "caros": { nome: "Preço: Maior para o Menor", icone: "bi-sort-down-alt" },
    "baratos": { nome: "Preço: Menor para Maior", icone: "bi-sort-up" },
    "vendidos": { nome: "Mais Vendidos", icone: "bi-bag-check" },
    "avaliados": { nome: "Bem avaliados", icone: "bi-star-fill" },
    "comentados": { nome: "Mais Comentados", icone: "bi-chat-dots" },
    "promocao": { nome: "Em Promoção", icone: "bi-tags" },
    "favoritados": { nome: "Mais Favoritados", icone: "bi-heart-fill" },
    "meus-favoritados": { nome: "Meus Favoritos", icone: "bi-heart" }
};


            
            buttonPrincipal.innerText = this.itensOrder[this.setup.ordenadores[0]].nome;
          
            this.buttonOrder = buttonPrincipal;
            var ul = document.createElement("UL")
            ul.classList.add("dropdown-menu", "dropdown-nown")
            ul.setAttribute("aria-labelledby" , id);
            
            var i = 0;
            while(i < this.setup.ordenadores.length){
                var ordenador = this.setup.ordenadores[i]
                if(i == 0){
                      this.ordenador = ordenador;
                }
        
                var li = document.createElement("LI")
                var button = document.createElement("BUTTON")
                button.classList.add("dropdown-item", "d-flex", "align-items-center", "gap-2")
                button.dataset.index = ordenador
                button.innerHTML = `<i class="bi ${this.itensOrder[this.setup.ordenadores[i]].icone} fs-12"></i> <span>${this.itensOrder[this.setup.ordenadores[i]].nome}</span>`
                li.appendChild(button)
                ul.appendChild(li)
                i++;
            }
            evento(Array.from(ul.getElementsByClassName("dropdown-item")), "click", this.changeOrder.bind(this))
            drop.appendChild(buttonPrincipal)
            drop.appendChild(ul)
            sort.appendChild(drop)
            div.appendChild(sort)
            
        }
        
        if(this.setup.modelos && this.setup.modelos.length > 0){
            if(this.setup.modelos.length > 1){
                  var i = 0;
            var btns = document.createElement("DIV")
            btns.classList.add("view-buttons")
            while(i < this.setup.modelos.length){
                var modelo = this.setup.modelos[i]
                
         
                       
                var btn = document.createElement("BUTTON")
                btn.classList.add("view-btn")
                         
                if(i == 0){
                     btn.classList.add("active");
                }
      
                this.callbacks[i] = window[modelo.cb];
                      
                btn.dataset.model = i;
                         
                switch(parseInt(modelo.itens)){
                    case 1:
                         btn.innerHTML = `<i class="bi bi-list-task"></i>`
                        break;
                    case 2:
                         btn.innerHTML = `<i class="bi bi-grid-fill"></i>`
                        break;
                    case 3:
                         btn.innerHTML = `<i class="bi bi-grid-3x3-gap-fill"></i>`
                        break;
                    case 4:
                         btn.innerHTML = `<i class="bi bi-dice-4"></i>`
                        break;
                    case 5:
                         btn.innerHTML = `<i class="bi bi-dice-5"></i>`
                        break;
                    case 6:
                        btn.innerHTML = `<i class="bi bi-dice-6"></i>`
                        break;
                    default:
                        btn.innerHTML = `<i class="bi bi-grid-3x3-gap-fill"></i>`
                        break;
                }
               
                btns.appendChild(btn)
                evento(btn, "click", this.changeModel.bind(this))
                
                i++;
            }
            
            
              sort.appendChild(btns)
            }else{
                 this.callbacks[0] = window[this.setup.modelos[0].cb];
            }
          
            
  
            
         
      
            
        }
        
       
        this.pai = document.createElement(this.setup.layout.pai)
        
        if(parseInt(this.setup?.layout?.gap || 0) > 0){
            this.pai.classList.add(`g-${this.setup.layout.gap}`)
        }
        
        switch(this.setup.layout.pai.toLowerCase()){
            case 'table':
                var pai = document.createElement('tbody')
                break
            default: 
                this.pai.classList.add("row")
                break;
        }
        
        
        
        
        
     
        
        card.appendChild(div)
        if(pai){
            let thead = document.createElement('thead')
            this.pai.appendChild(thead)
            if(this.layout['tableHeader']){
                thead.appendChild(this.layout['tableHeader'])
            }
            this.pai.appendChild(pai)
        }
        
        card.appendChild(this.pai)
        
        if(pai){
            this.pai = pai
        }
        
        this.plus = false;
        if(this.setup.exibicao.tipo == 2){
            this.paginacaoSpace = document.createElement("DIV")
            this.paginacaoSpace.classList.add("mt-4")
            card.appendChild(this.paginacaoSpace)
            this.plus = 2;
        }
        
        if(this.setup.exibicao.tipo == 3){
            this.plus = 3;
        }
        
        
        
        this.dados.bind(this)();
    }
    
    changeOrder(){
        if(this.ordenador != event.currentTarget.dataset.index){
            this.ordenador = event.currentTarget.dataset.index
            this.buttonOrder.innerText = this.itensOrder[event.currentTarget.dataset.index].nome;
            this.zeraTudo.bind(this)();
        }
    }
    
    zeraTudo(){
        this.pagina = 1;
        this.pai.innerHTML = "";
        this.dados.bind(this)();
    }
    
    changeModel(){

        var modelo = parseInt(event.currentTarget.dataset.model || 0)
        if(this.modelSelecionado != modelo){
            event.currentTarget.closest(".view-buttons").getElementsByClassName("active")[0].classList.remove("active")
            event.currentTarget.classList.add("active")
            this.modelSelecionado = modelo;
            
            if(this.setup.exibicao.tipo == 3){
                this.pagina = 1;
                this.pai.innerHTML = "";
            }
            
            this.dados.bind(this)();
        }
      
    }
    
    go(num){
        const conteudo = document.getElementById('conteudo');

    // Função para rolar até o topo
    const scrollToTop = () => {
        if (conteudo && conteudo instanceof Element) {
            console.log('Rolando #conteudo para o topo');
            conteudo.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        } else {
            console.error('Div #conteudo não encontrada no DOM. Verifique o ID ou a inicialização.');
            // Não usa window como fallback, já que o scroll deve ser interno a #conteudo
        }
    };


    scrollToTop();
    
        this.pagina = parseInt(num)
        this.dados.bind(this)();
    }
    
    verificarExtraObj(item){
        if(Array.isArray(item) || typeof item === 'object'){
            return true
        }else{
            return false
        }
    }
    
    dados() {

        var size = parseInt(this.setup.modelos[this.modelSelecionado].itens);

        var refs = {
        1: 'col-xl-12',
        2: 'col-xl-6',
        3: 'col-xl-4',
        4: 'col-xl-3',
        5: 'col-nown-xl-5',
        6: 'col-nown-xl-6',
        7: 'col-nown-xl-7',
        8: 'col-nown-xl-8',
        9: 'col-nown-xl-9',
        10: 'col-nown-xl-10',
    }
    
        var quantidade = this.setup.exibicao.tipo < 4 ? parseInt(this.setup.exibicao.itensPerLoad) : 9999;

        // Chamada à API e construção do DOM
        var api = new ApiNown(this.modulo, this.setup.api);
        api.isNew();
        api.paginacao(quantidade);
        if(this.api['extra']){
            api.setExtra(this.api['extra'])
        }
        

        if(this.api.estado && this.api.cidade){
            api.setAdress(this.api.estado, this.api.cidade)
        }
        
        if(this.api.hash && this.api.hash){
            api.setHash(this.api.hash)
        }
            
        api.isFilter({
            filtro: this.hash,
            valores: this.filtrosValues,
            ordem: this.ordenador
        })
        api.setPagina(this.pagina);
        api.send().then((r) => {
           if(this.plus == 2){
                this.pai.innerHTML = ``
                this.criarPaginacao(r.numeros.total , this.pagina, quantidade);
            }
            
        
            let lista = r.lista;
            let i = 0;
            var fragmento = document.createDocumentFragment();
            console.log(this.layout)
            if(this.layout['itemAnterior']){
                var div = document.createElement("DIV");
                div.className = refs[size]
                div.appendChild(this.layout['itemAnterior'])
                fragmento.appendChild(div)
            }
            
            while (i < lista.length) {
                switch(this.setup.layout.pai.toLowerCase()){
                    case 'table':
                        var div = document.createDocumentFragment()
                        break
                    default: 
                        var div = document.createElement("DIV");
                        div.className = refs[size]
                        break;
                }
                
                if(this.setup?.layout?.animacao || false){
                    div.classList.add("animate__animated", `animate__${this.setup.layout.animacao}`)
                }
                div.appendChild(this.callbacks[this.modelSelecionado](lista[i]));
                fragmento.appendChild(div);
    
                i++;
            }
            
            if(this.layout['itemPosterior']){
                var div = document.createElement("DIV");
                div.className = refs[size]
                div.appendChild(this.layout['itemPosterior'])
                fragmento.appendChild(div)
            }
            this.pai.appendChild(fragmento);
            
            // opção aqui por enquanto só para sempre carregar os favoritos caso haja
            new Favoritos()
            
            if(this.plus == 3){
        
                if((this.pagina * quantidade)  < r.numeros.total){
                      loadLore(this.pai, this.scroll.bind(this));
                }
                
              
            }
            
            
            this.controlerResultados.innerText = `${r.numeros.total} Resultaldos Encontrado`
           
            
           
            
    
        });
    }
    
    scroll(){
         this.pagina++;
         this.dados.bind(this)();
    }
    
    start(filtro){
        this.setup = filtro;
        
        if(!this.setup.modelos || this.setup.modelos.length == 0){
            console.log("Não há modelos válidos de cards")
            return;
        }
        
        

        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        
        var colcards = document.createElement("DIV")
        var cards = document.createElement("DIV")
        colcards.appendChild(cards)
        
        
        this.filterTags = document.createElement("DIV")
        this.filterTags.classList.add("filter-tags", "d-none")

        cards.appendChild(this.filterTags)
        
        
        this.header.bind(this)(cards);
        
   
        
        
        
        let filter = filtro.filtro;
        if(parseInt(filter.ativo) > 0){
            var card = this.card.bind(this)();
            var lateral = document.createElement("DIV")
            lateral.appendChild(card)
            
            var sizeCol = "col-3";
            var sizeCards = "col-xl-9"
            switch(filter.largura){
                case '20':
                    var sizeCol = "col-3";
                    var sizeCards = "col-xl-9"
                    break;
                case 25:
                    var sizeCol = "col-3";
                    var sizeCards = "col-xl-9"
                    break;
                case 33:
                    var sizeCol = "col-4";
                    var sizeCards = "col-xl-8"
                    break;
                case 50:
                    var sizeCol = "col-6";
                    var sizeCards = "col-xl-6"
                    break;
            }
            
            switch(parseInt(filter.ativo)){
                case 1:
                    // Lateral Esquerda
                    lateral.classList.add(sizeCol, "d-none", "d-xl-block")
                    colcards.classList.add(sizeCards, "col-12")
                    row.appendChild(lateral)
                    row.appendChild(colcards)
                    
                    
                    break;
                case 2:
                    // Lateral Direita
                    lateral.classList.add(sizeCol, "d-none", "d-xl-block")
                    colcards.classList.add(sizeCards, "col-12")
                    row.appendChild(colcards)
                    row.appendChild(lateral)
                    break;
                case 3:
                    // Topo
                    lateral.classList.add("col-12")
                    colcards.classList.add("col-12")
                    row.appendChild(lateral)
                    row.appendChild(colcards)
                    break;
                case 4:
                    // Abaixo
                    lateral.classList.add("col-12")
                    colcards.classList.add("col-12")
                    row.appendChild(colcards)
                    row.appendChild(lateral)
                    break;
            }
        }else{
            colcards.classList.add("col-12")
            row.appendChild(colcards)
        }
        
        this.container.appendChild(row)
        //<select class="form-select"> <option value="0">Desativado</option> <option value="1">Lateral Esquerda</option> <option value="2">Lateral Direita</option> <option value="3">Topo</option> <option value="4">Abaixo</option> </select>
    }
    
    init(){
        var request = new Request(`${dominio}/admin/filtro.php`);
        request.addData({
            modulo: this.modulo,
            chave: this.hash
        })
        request.send().then((r)=>{
            if(r.js){
                nownFiles.add(`${dominio}/conteudo/modulos/${this.modulo}/assets/componentes.js`).then((r)=>{
                    this.start.bind(this)(r.filtro);
                })
            }else{
                this.start.bind(this)(r.filtro); 
            }
        }, (r)=>{
            console.log(r)
        })
    }
    
    criarPaginacao(totalItems, currentPage, itemsPerPage) {
  // Armazenamos a referência ao elemento de paginação
  this.paginacaoSpace = this.paginacaoSpace
  
  // Se não houver itens suficientes para paginar, retorna div vazia
  if (totalItems <= itemsPerPage) {
    this.paginacaoSpace.innerHTML = '';
    return document.createElement('div');
  }

  // Limpa o conteúdo atual da paginação
  this.paginacaoSpace.innerHTML = '';
  
  // Calcula o total de páginas
  const totalPages = Math.ceil(totalItems / itemsPerPage);
  
  // Se a página atual estiver fora dos limites, ajustamos
  currentPage = Math.max(1, Math.min(currentPage, totalPages));
  
  // Criando o elemento nav principal
  const nav = document.createElement('nav');
  nav.setAttribute('aria-label', 'Navegação de páginas');
  
  // Criando a lista de páginas
  const ul = document.createElement('ul');
  ul.className = 'pagination justify-content-center';
  
  // Botão "Anterior"
  const prevLi = document.createElement('li');
  prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
  
  const prevLink = document.createElement('a');
  prevLink.className = 'page-link';
  prevLink.href = '#';
  prevLink.setAttribute('aria-label', 'Anterior');
  prevLink.innerHTML = '<span aria-hidden="true">&laquo;</span>';
  
  if (currentPage > 1) {
    prevLink.addEventListener('click', (e)=> {
      e.preventDefault();
      this.go(currentPage - 1);
    });
  }
  
  prevLi.appendChild(prevLink);
  ul.appendChild(prevLi);
  
  // Determine quais páginas mostrar
  let startPage = Math.max(1, currentPage - 2);
  let endPage = Math.min(totalPages, startPage + 4);
  
  // Ajusta para mostrar sempre 5 páginas quando possível
  if (endPage - startPage < 4) {
    startPage = Math.max(1, endPage - 4);
  }
  
  // Botão para primeira página (se necessário)
  if (startPage > 1) {
    const firstLi = document.createElement('li');
    firstLi.className = 'page-item';
    
    const firstLink = document.createElement('a');
    firstLink.className = 'page-link';
    firstLink.href = '#';
    firstLink.textContent = '1';
    
    firstLink.addEventListener('click', (e)=> {
      e.preventDefault();
      this.go(1);
    });
    
    firstLi.appendChild(firstLink);
    ul.appendChild(firstLi);
    
    // Adiciona elipses se houver um gap
    if (startPage > 2) {
      const ellipsisLi = document.createElement('li');
      ellipsisLi.className = 'page-item disabled';
      
      const ellipsisSpan = document.createElement('a');
      ellipsisSpan.className = 'page-link';
      ellipsisSpan.textContent = '...';
      
      ellipsisLi.appendChild(ellipsisSpan);
      ul.appendChild(ellipsisLi);
    }
  }
  
  // Páginas numéricas
  for (let i = startPage; i <= endPage; i++) {
    const pageLi = document.createElement('li');
    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
    
    const pageLink = document.createElement('a');
    pageLink.className = 'page-link';
    pageLink.href = '#';
    pageLink.textContent = i;
    
    if (i === currentPage) {
      pageLink.setAttribute('aria-current', 'page');
    } else {
      pageLink.addEventListener('click', (e)=> {
        e.preventDefault();
        this.go(i);
      });
    }
    
    pageLi.appendChild(pageLink);
    ul.appendChild(pageLi);
  }
  
  // Botão para última página (se necessário)
  if (endPage < totalPages) {
    // Adiciona elipses se houver um gap
    if (endPage < totalPages - 1) {
      const ellipsisLi = document.createElement('li');
      ellipsisLi.className = 'page-item disabled';
      
      const ellipsisSpan = document.createElement('a');
      ellipsisSpan.className = 'page-link';
      ellipsisSpan.textContent = '...';
      
      ellipsisLi.appendChild(ellipsisSpan);
      ul.appendChild(ellipsisLi);
    }
    
    const lastLi = document.createElement('li');
    lastLi.className = 'page-item';
    
    const lastLink = document.createElement('a');
    lastLink.className = 'page-link';
    lastLink.href = '#';
    lastLink.textContent = totalPages;
    
    lastLink.addEventListener('click', (e)=> {
      e.preventDefault();
      this.go(totalPages);
    });
    
    lastLi.appendChild(lastLink);
    ul.appendChild(lastLi);
  }
  
  // Botão "Próximo"
  const nextLi = document.createElement('li');
  nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
  
  const nextLink = document.createElement('a');
  nextLink.className = 'page-link';
  nextLink.href = '#';
  nextLink.setAttribute('aria-label', 'Próximo');
  nextLink.innerHTML = '<span aria-hidden="true">&raquo;</span>';
  
  if (currentPage < totalPages) {
    nextLink.addEventListener('click', (e)=> {
      e.preventDefault();
      this.go(currentPage + 1);
    });
  }
  
  nextLi.appendChild(nextLink);
  ul.appendChild(nextLi);
  
  nav.appendChild(ul);
  
  // Adicionar o elemento ao paginacaoSpace
  this.paginacaoSpace.appendChild(nav);
  
  return nav;
}

}

class BuscaLocal {
    
    constructor(container = false, botaoConfirmar = null, cb = false) {
        this.cb = cb
        this.container = container;
        this.botaoConfirmar = botaoConfirmar;
        this.procurarTermo = null;
        this.cidadeSelecionada = false;
        
        if (!this.container) {
            return;
        }
        
        if (this.botaoConfirmar) {
            this.botaoConfirmar.disabled = true;
        }
        
        this.render();
    }
    
    destacarTermoPesquisado(item, termo) {
    if (!termo || termo.length < 2) return item;
    
    const regex = new RegExp(`(${termo})`, 'gi');
    return item.replace(regex, '<mark>$1</mark>');
}



// Método modificado para criar cidade com destaque no termo pesquisado
    cidade(item) {
    const li = document.createElement("LI");
    li.classList.add("list-group-item", "list-group-item-action", "btn");
    li.setAttribute("role", "option");
    li.setAttribute("aria-selected", "false");
    li.setAttribute("tabindex", "0");
    li.dataset.valor = `${item.e}/${item.u}`;
    li.dataset.cidade = item.n;
    li.dataset.estado = item.e;
    
    // Destacar o termo pesquisado no nome da cidade
    const cidadeDestacada = this.destacarTermoPesquisado(item.n, this.input.value);
    
    li.innerHTML = `
        <div class="d-flex justify-content-start gap-3 align-items-center">
            <div><i class="bi bi-geo-alt text-primaria"></i></div>
            <div class="item-cidade">${cidadeDestacada}</div>
            <div class="item-estado ms-auto">${item.e.toUpperCase()}</div>
        </div>
    `;
    
    this.adicionarEventos(li);
    
    // Adicionar efeito de entrada com delay baseado no índice
    li.style.opacity = '0';
    li.style.transform = 'translateX(10px)';
    
    setTimeout(() => {
        li.style.opacity = '1';
        li.style.transform = 'translateX(0)';
    }, 50);
    
    return li;
}
    
    adicionarEventos(elemento) {
        elemento.addEventListener("click", this.seleciona.bind(this));
        
        // Adicionando navegação por teclado
        elemento.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                this.seleciona({ currentTarget: elemento });
            } else if (e.key === "ArrowDown") {
                e.preventDefault();
                const next = elemento.nextElementSibling;
                if (next) next.focus();
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                const prev = elemento.previousElementSibling;
                if (prev) prev.focus();
            }
        });
    }
    
    seleciona(event) {
    const target = event.currentTarget;
    this.procurarTermo = target.dataset.valor;
    this.cidadeSelecionada = true;
         
    // Atualizar o valor do input com formatação visual melhorada
    this.input.value = `${target.dataset.cidade} - ${target.dataset.estado.toUpperCase()}`;
    
    // Fechar o dropdown de resultados com animação
    this.resultados.style.opacity = '0';
    this.resultados.style.transform = 'translateY(10px)';
    
    // Adicionar a classe após um pequeno delay para permitir a animação
    setTimeout(() => {
        this.resultados.classList.add("d-none");
        this.resultados.removeAttribute("style");
    }, 200);
    
    // Habilitar o botão de confirmar
    if (this.botaoConfirmar) {
        this.botaoConfirmar.disabled = false;
        // Adicionar animação ao botão confirmar
        this.botaoConfirmar.classList.add("btn-animated");
        setTimeout(() => {
            
            this.botaoConfirmar.classList.remove("btn-animated");
            if(this.cb){
                this.cb()
            }
        }, 1000);
    }
    
    // Adicionar classe visual para indicar seleção com efeito de animação
    this.input.classList.add("is-valid");
    
    // Adicionar ícone de verificação após o input se ainda não existir
    this.adicionarIconeVerificacao();
    
    // Garantir que o botão limpar fique visível após seleção
    this.btnLimpar.classList.remove("d-none");
    
    // Remover foco do input após seleção
    this.input.blur();
    
    // Disparar evento personalizado para notificar que uma cidade foi selecionada
    const eventoSelecao = new CustomEvent('cidadeSelecionada', {
        detail: {
            cidade: target.dataset.cidade,
            estado: target.dataset.estado,
            valor: target.dataset.valor
        }
    });
    this.container.dispatchEvent(eventoSelecao);
}
    
    // Novo método para adicionar ícone de verificação
    adicionarIconeVerificacao() {
    // Verificar se já existe um ícone de verificação
    if (!this.container.querySelector('.verificacao-icone')) {
        const iconeVerificacao = document.createElement('DIV');
        iconeVerificacao.classList.add('verificacao-icone');
        iconeVerificacao.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                <path d="M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 01-1.06 0L2.22 9.28a.75.75 0 011.06-1.06L6 10.94l6.72-6.72a.75.75 0 011.06 0z"/>
            </svg>
        `;
        
        // Posicionar o ícone dentro do wrapper
        const wrapper = this.input.parentElement.parentElement;
        wrapper.style.position = 'relative';
        wrapper.appendChild(iconeVerificacao);
    }
}

    
    // Método modificado para limpar seleção
    limparSelecao() {
    this.procurarTermo = null;
    this.cidadeSelecionada = false;
    
    // Animação para limpar o input
    this.input.classList.add("limpar-animacao");
    setTimeout(() => {
        this.input.value = "";
        this.input.classList.remove("limpar-animacao");
    }, 150);
    
    // Desabilitar botão confirmar
    if (this.botaoConfirmar) {
        this.botaoConfirmar.disabled = true;
    }
    
    // Remover ícone de verificação se existir
    const iconeVerificacao = this.container.querySelector('.verificacao-icone');
    if (iconeVerificacao) {
        iconeVerificacao.classList.add('fade-out');
        setTimeout(() => {
            iconeVerificacao.remove();
        }, 300);
    }
   
    
    // Remover classe de validação
    this.input.classList.remove("is-valid");
    this.input.focus();
}
    
    render() {
        const div = document.createElement("DIV");
        div.classList.add("busca-local-wrapper");
        
        // Ícone de pesquisa
        const icone = document.createElement("DIV");
        icone.classList.add("search-icon");
        icone.innerHTML = `<i class="bi bi-search"></i>`;
        
        // Spinner de carregamento
        const spiner = document.createElement("DIV");
        spiner.classList.add("spinner-loader", "d-none");
        spiner.innerHTML = `
        <div class="spinner-border spinner-border-sm text-primaria" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        `;
        this.spiner = spiner;
        
        // Botão para limpar
        const btnLimpar = document.createElement("BUTTON");
        btnLimpar.type = "button";
        btnLimpar.classList.add("btn-clear", "d-none");
        btnLimpar.setAttribute("aria-label", "Limpar seleção");
        btnLimpar.innerHTML = `<i class="bi bi-x-circle"></i>`;
        
        btnLimpar.addEventListener("click", (e) => {
            e.preventDefault();
            this.limparSelecao();
            btnLimpar.classList.add("d-none");
        });
        
        this.btnLimpar = btnLimpar;
        
        // Criar um wrapper de formulário para melhor controle
        const formWrapper = document.createElement("FORM");
        formWrapper.setAttribute("onsubmit", "return false;"); // Prevenir envio
        formWrapper.setAttribute("autocomplete", "off");
        
        // Input de busca
        const input = document.createElement("INPUT");
        input.id = this.geraId();
        
        
        
        // Desabilitar completamente o autocompletar do navegador
        input.setAttribute("autocomplete", "new-password");
        input.setAttribute("autocorrect", "off");
        input.setAttribute("autocapitalize", "off");
        input.setAttribute("spellcheck", "false");
        
        // Nome aleatório para evitar reconhecimento de padrões pelo navegador
        input.setAttribute("name", `cidade_${Math.random().toString(36).substring(2, 15)}`);
        
        input.setAttribute("aria-autocomplete", "list");
        input.setAttribute("role", "combobox");
        input.setAttribute("aria-expanded", "false");
        input.setAttribute("aria-owns", `resultados-${input.id}`);

        
        input.classList.add("form-control", "px-5", "busca-input-moderno");
    
    // Adicionar um placeholder mais informativo
    input.placeholder = "Digite o nome da cidade...";
    
        this.input = input;
        
        // Campo oculto para enganar o navegador
        const hiddenField = document.createElement("INPUT");
        hiddenField.type = "text";
        hiddenField.style.display = "none";
        hiddenField.setAttribute("autocomplete", "false");
        hiddenField.setAttribute("tabindex", "-1");
        
        // Lista de resultados
        const resultados = document.createElement("DIV");
        resultados.id = `resultados-${input.id}`;
        resultados.classList.add("resultados-busca", "d-none");
        resultados.setAttribute("role", "listbox");
        this.resultados = resultados;
        
        // Card para resultados
        const card = document.createElement("DIV");
        card.classList.add("card", "lista-cidades", "shadow-sm");
        
        // Corpo do card
        const body = document.createElement("DIV");
        body.classList.add("p-2");
        
        // Lista de cidades
        const ul = document.createElement("UL");
        ul.classList.add("list-group", "list-group-flush");
        ul.setAttribute("role", "listbox");
        ul.setAttribute("tabindex", "-1");
        this.ul = ul;
        
        // Mensagem de nenhum resultado
        const semResultados = document.createElement("DIV");
        semResultados.classList.add("sem-resultados", "d-none");
        semResultados.textContent = "Nenhuma cidade encontrada";
        this.semResultados = semResultados;
        
        // Configurar eventos do input
        this.configurarEventosInput();
        
        // Montar estrutura
        body.appendChild(ul);
        body.appendChild(semResultados);
        card.appendChild(body);
        resultados.appendChild(card);
        
        formWrapper.appendChild(hiddenField);
        formWrapper.appendChild(input);
        
        div.appendChild(icone);
        div.appendChild(formWrapper);
        div.appendChild(spiner);
        div.appendChild(btnLimpar);
        div.appendChild(resultados);
        
        // Limpar o container antes de adicionar o componente
        this.container.innerHTML = "";
        this.container.appendChild(div);
    }
    
    configurarEventosInput() {
        // Usar debounce para evitar muitas requisições
        this.debounce(this.input, 500, this.procurar.bind(this));
        
        // Mostrar spinner ao digitar
        this.input.addEventListener("input", () => {
            this.spiner.classList.remove("d-none");
            this.resultados.setAttribute("aria-expanded", "true");
            
            // Mostrar botão limpar apenas se houver texto
            if (this.input.value) {
                this.btnLimpar.classList.remove("d-none");
            } else {
                this.btnLimpar.classList.add("d-none");
                this.cidadeSelecionada = false;
                if (this.botaoConfirmar) {
                    this.botaoConfirmar.disabled = true;
                }
                this.input.classList.remove("is-valid");
            }
            
            // Se o usuário digitar algo, considerar que não há cidade selecionada
            this.cidadeSelecionada = false;
            if (this.botaoConfirmar) {
                if(this.cb){
                    this.cb()
                }
                this.botaoConfirmar.disabled = true;
            }
            this.input.classList.remove("is-valid");
            
             if (!this.resultados.classList.contains("d-none")) {
            this.resultados.style.opacity = '0';
            this.resultados.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                this.resultados.style.opacity = '1';
                this.resultados.style.transform = 'translateY(0)';
            }, 50);
        }
        });
        
        // Esconder resultados ao perder foco
        this.input.addEventListener("blur", () => {
            setTimeout(() => {
                this.spiner.classList.add("d-none");
                this.resultados.classList.add("d-none");
                this.resultados.setAttribute("aria-expanded", "false");
                
                // Se não houver cidade selecionada e input estiver vazio, esconder o botão limpar
                if (!this.cidadeSelecionada && !this.input.value) {
                    this.btnLimpar.classList.add("d-none");
                }
            }, 200);
        });
        
        // Mostrar resultados ao focar
        this.input.addEventListener("focus", () => {
            if (this.input.value && !this.cidadeSelecionada) {
                this.resultados.classList.remove("d-none");
                this.resultados.setAttribute("aria-expanded", "true");
            }
            
            // Mostrar botão limpar se houver texto
            if (this.input.value) {
                this.btnLimpar.classList.remove("d-none");
            }
        });
        
        // Navegação por teclado no input
        this.input.addEventListener("keydown", (e) => {
            if (e.key === "ArrowDown" && !this.resultados.classList.contains("d-none")) {
                e.preventDefault();
                const primeiroItem = this.ul.querySelector("li");
                if (primeiroItem) primeiroItem.focus();
            } else if (e.key === "Escape") {
                e.preventDefault();
                this.resultados.classList.add("d-none");
                this.resultados.setAttribute("aria-expanded", "false");
                this.input.blur();
            }
        });
        
        
    }
    
    procurar() {
        // Verifica se o valor do input é muito curto
        if (this.input.value.length < 2) {
            this.spiner.classList.add("d-none");
            this.resultados.classList.add("d-none");
            return;
        }
        
        // Request para buscar cidades
        const request = new Request("conteudo/modulos/enderecos/admins/busca.php");
        request.addData({
            "acao": "buscacidade",
            "texto": this.input.value
        });
        
        request.send().then((r) => {
            this.spiner.classList.add("d-none");
            const lista = r.lista;
            this.ul.innerHTML = "";
            
            if (lista.length === 0) {
                this.ul.classList.add("d-none");
                this.semResultados.classList.remove("d-none");
                this.resultados.classList.remove("d-none");
                return;
            }
            
            this.ul.classList.remove("d-none");
            this.semResultados.classList.add("d-none");
            
            const fragmento = document.createDocumentFragment();
            for (let i = 0; i < lista.length; i++) {
                fragmento.appendChild(this.cidade(lista[i]));
            }
            
            this.ul.appendChild(fragmento);
            this.resultados.classList.remove("d-none");
            
        }, () => {
            this.ul.innerHTML = "";
            this.spiner.classList.add("d-none");
            this.resultados.classList.add("d-none");
            this.ul.classList.remove("d-none");
            this.semResultados.classList.add("d-none");
        });
    }
    
    get() {
        if (this.procurarTermo && this.cidadeSelecionada) {
            return this.procurarTermo;
        } else {
            return false;
        }
    }
    
    // Função para gerar ID único
    geraId() {
        return 'busca-local-' + Math.random().toString(36).substr(2, 9);
    }
    
    // Função de debounce para evitar múltiplas requisições
    debounce(elemento, delay, callback) {
        let timer;
        elemento.addEventListener("input", (e) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                callback(e);
            }, delay);
        });
    }
} 


// classe feita para sbir bibliotecas de imagens no nown, so passar a div e a galeria e elá irá montar o grid dinamicamente

class Imaginator {
    constructor(div, galeria) {
        this.div = div;
        this.galeria = galeria;
        this.selecionado = 0;
        this.animationDuration = 300; // Duração da animação em ms
        
        // Propriedades para o sistema de drag
        this.isDragging = false;
        this.startX = 0;
        this.startScrollLeft = 0;
        this.momentumID = null;
        this.velocity = 0;
        this.timestamp = 0;
        this.lastDragX = 0;
        this.configurado = false;
        
        // Adicionar estilo global para o componente
        this.adicionarEstilosGlobais();
        
        this.criar.bind(this)(galeria);
    }
    
    adicionarEstilosGlobais() {
        // Verificar se os estilos já existem para evitar duplicação
        if (!document.getElementById('imaginator-styles')) {
            const style = document.createElement('style');
            style.id = 'imaginator-styles';
            style.textContent = `
                /* Estilos do modal */
                .imaginator-modal {
                    background-color: rgba(0, 0, 0, 0.85);
                    backdrop-filter: blur(5px);
                    transition: opacity 0.3s ease;
                }
                
                /* Botões de navegação com hover effect */
                .nav-button {
                    opacity: 0.7;
                    transition: all 0.2s ease;
                    width: 48px;
                    height: 48px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10;
                }
                
                .nav-button:hover {
                    opacity: 1;
                    transform: scale(1.1);
                    background-color: rgba(255, 255, 255, 0.9) !important;
                }
                
                /* Imagem principal com espaço para respirar */
                .imaginator-main-image {
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                    transition: transform 0.3s ease, opacity 0.3s ease;
                }
                
                /* Estilos para galeria de miniaturas */
                .imaginator-gallery {
                    scrollbar-width: thin;
                    scroll-behavior: smooth;
                    -webkit-overflow-scrolling: touch;
                    position: relative;
                    padding: 10px 5px;
                    border-radius: 10px;
                    background-color: rgba(0, 0, 0, 0.3);
                }
                
                .imaginator-gallery::-webkit-scrollbar {
                    height: 6px;
                }
                
                .imaginator-gallery::-webkit-scrollbar-track {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 10px;
                }
                
                .imaginator-gallery::-webkit-scrollbar-thumb {
                    background-color: rgba(255, 255, 255, 0.4);
                    border-radius: 10px;
                }
                
                /* Estilos para miniaturas */
                .miniatura-item {
                    transition: transform 0.2s ease, opacity 0.2s ease;
                }
                
                .miniatura-wrapper {
                    position: relative;
                    overflow: hidden;
                    border-radius: 8px;
                    transition: transform 0.2s ease;
                }
                
                .miniatura-wrapper:hover {
                    transform: translateY(-3px);
                }
                
                .miniatura-wrapper img {
                    transition: all 0.3s ease;
                }
                
                .miniatura-wrapper.active::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border: 3px solid var(--nown-primaria, #0066ff);
                    border-radius: 8px;
                    pointer-events: none;
                }
                
                /* Estilos para a galeria em modo de arrasto */
                .imaginator-gallery.dragging {
                    cursor: grabbing !important;
                    scroll-behavior: auto !important;
                    user-select: none;
                }
                
                .imaginator-gallery:not(.dragging) {
                    cursor: grab;
                }
                
                /* Contador de imagens */
                .imaginator-counter {
                    background-color: rgba(0, 0, 0, 0.5);
                    border-radius: 20px;
                    padding: 5px 15px;
                    font-size: 14px;
                    font-weight: 500;
                }
                
                /* Animações para transições de imagens */
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                @keyframes scaleIn {
                    from { transform: scale(0.9); opacity: 0; }
                    to { transform: scale(1); opacity: 1; }
                }
                
                .scale-in {
                    animation: scaleIn 0.3s ease forwards;
                }
                
                .fade-in {
                    animation: fadeIn 0.3s ease forwards;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    videoDetector(url) {
        const youtubeRegex = /(?:youtube\.com\/.*[?&]v=|youtu\.be\/)([^&#]+)/;
        const vimeoRegex = /vimeo\.com\/(\d+)/;
    
        let resultado = {};
    
        // Verifica se é uma URL do YouTube
        let youtubeMatch = url.match(youtubeRegex);
        if (youtubeMatch) {
            resultado.tipo = "youtube";
            resultado.id = youtubeMatch[1];
            return resultado;
        }
    
        // Verifica se é uma URL do Vimeo
        let vimeoMatch = url.match(vimeoRegex);
        if (vimeoMatch) {
            resultado.tipo = "vimeo";
            resultado.id = vimeoMatch[1];
            return resultado;
        }
    
        // Se não for um vídeo válido, retorna false
        return false;
    }
    
    criar(array) {
        this.div.innerHTML = '';
        
        if (array.length <= 0) {
            console.log('Imagens Não Passadas');
            return;
        }
        
        let i = 0;
        let fragmento = document.createDocumentFragment();
        
        let item1 = 0;
        let item2 = 2;
        let item3 = 5;
        
        while (i < 7 && i < array.length) {
            if (i == 0) {
                let div = document.createElement('div');
                div.classList.add('main-image-container', 'position-relative');
                
                let video = this.videoDetector.bind(this)(array[i])
                
                if(video){
                    div.innerHTML = `
                    <div class="view-all-photos p-2 bg-white bg-opacity-75 rounded-3 position-absolute start-2 d-flex align-items-center gap-1" style="z-index: 1; cursor: pointer;">
                        <i class="bi bi-images"></i>
                        <span>Ver todas as fotos</span>
                    </div>
                    <div class="gallery-count p-2 bg-opacity-75 rounded-3 position-absolute end-2" style="z-index: 1;">
                        <span>1/${array.length} foto(s)</span>
                    </div>
                    `
                    
                    let div2 = document.createElement('div')
                    div2.setAttribute("data-plyr-provider", video.tipo)
                    div2.setAttribute("data-plyr-embed-id", video.id)
                
                    nownFiles.add([`https://cdn.plyr.io/3.7.8/plyr.css`, "https://cdn.plyr.io/3.7.8/plyr.js"]).then(()=>{
                        var v = new Plyr(div2);
                        v.on('ready', () => {
                            
                        })
                    })
                    let div3 = document.createElement('div')
                    div3.classList.add('position-absolute', 'w-100', 'h-90', 'image-detail', 'top-0')
                    div3.dataset.image = i
                    div.appendChild(div2)
                    div.appendChild(div3)
                }else{
                    div.innerHTML = `
                        <div class="view-all-photos p-2 bg-white bg-opacity-75 rounded-3 position-absolute start-2 d-flex align-items-center gap-1" style="z-index: 1; cursor: pointer;">
                            <i class="bi bi-images"></i>
                            <span>Ver todas as fotos</span>
                        </div>
                        <div class="gallery-count p-2 bg-opacity-75 rounded-3 position-absolute end-2" style="z-index: 1;">
                            <span>1/${array.length} foto(s)</span>
                        </div>
                        <img src="${trataImagem(array[i], 'grande')}" alt="${array[i]}" class="main-image image-detail w-100 rounded-3 shadow-sm" data-image="${i}" style="object-fit: cover; height: 100%;">
                    `;
                }
                
                
                
                // Adicionar animação de entrada com fade-in
                div.style.opacity = '0';
                div.style.transition = `opacity ${this.animationDuration}ms ease-in-out`;
                setTimeout(() => { div.style.opacity = '1'; }, 50);
                
                fragmento.appendChild(div);
            } else if (i > item1 && i < (item2 + 1)) {
                if (i == (item1 + 1)) {
                    var segunda = document.createElement('div');
                    segunda.classList.add('gallery-side');
                    
                    // Adicionar animação de entrada deslizando da direita
                    segunda.style.transform = 'translateX(50px)';
                    segunda.style.opacity = '0';
                    segunda.style.transition = `transform ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-in-out`;
                    setTimeout(() => { 
                        segunda.style.transform = 'translateX(0)';
                        segunda.style.opacity = '1';
                    }, 100 * i);
                }
                
                let div = document.createElement('div');
                div.classList.add('gallery-side-item');
                
                let video = this.videoDetector.bind(this)(array[i])
                
                if(video){
                    let div3 = document.createElement('div')
                    div3.classList.add('w-100', 'h-100', 'image-detail', 'd-flex', 'align-items-center', 'justify-content-center', 'bg-primaria')
                    div3.dataset.image = i
                    div3.innerHTML = `
                        <i class="bi bi-play-circle-fill fs-40" style="color: var(--nown-primaria-text-over)"></i>
                    `
                    div.appendChild(div3)
                }else{
                    div.innerHTML = `
                        <img src="${trataImagem(array[i], 'media')}" alt="${array[i]}" class="gallery-side-image image-detail w-100 h-100 rounded-3 shadow-sm" data-image="${i}" style="object-fit: cover;">
                    `;
                }
                
                
                segunda.appendChild(div);
                
                if (i == (array.length - 1) || i == item2) {
                    fragmento.appendChild(segunda);
                }
            } else if (i > item2 && i < (item3 + 1)) {
                if (i == (item2 + 1)) {
                    var terceira = document.createElement('div');
                    terceira.classList.add('gallery-bottom');
                    
                    // Adicionar animação de entrada deslizando de baixo
                    terceira.style.transform = 'translateY(50px)';
                    terceira.style.opacity = '0';
                    terceira.style.transition = `transform ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-in-out`;
                    setTimeout(() => { 
                        terceira.style.transform = 'translateY(0)';
                        terceira.style.opacity = '1';
                    }, 150 * i);
                }
                
                let div = document.createElement('div');
                div.classList.add('gallery-bottom-item');
                let video = this.videoDetector.bind(this)(array[i])
                
                if(video){
                    let div3 = document.createElement('div')
                    div3.classList.add('w-100', 'h-100', 'image-detail', 'd-flex', 'align-items-center', 'justify-content-center', 'bg-primaria')
                    div3.dataset.image = i
                    div3.innerHTML = `
                        <i class="bi bi-play-circle-fill fs-40" style="color: var(--nown-primaria-text-over)"></i>
                    `
                    div.appendChild(div3)
                }else{
                    div.innerHTML = `
                        <img src="${trataImagem(array[i], 'media')}" alt="${array[i]}" class="gallery-bottom-image image-detail w-100 h-100 rounded-3 shadow-sm" data-image="${i}" style="object-fit: cover;">
                    `;
                }
                
                
                
                
                terceira.appendChild(div);
                
                if (i == item3) {
                    fragmento.appendChild(terceira);
                }
            } else {
                let div = document.createElement('div');
                div.classList.add('video-container');

                let video = this.videoDetector.bind(this)(array[i])
                
                if(video){
                    let div3 = document.createElement('div')
                    div3.classList.add('w-100', 'h-100', 'image-detail', 'd-flex', 'align-items-center', 'justify-content-center', 'bg-primaria')
                    div3.dataset.image = i
                    div3.innerHTML = `
                        <i class="bi bi-play-circle-fill fs-40" style="color: var(--nown-primaria-text-over)"></i>
                    `
                    div.appendChild(div3)
                }else{
                    let image = '';
                
                    if (array.length > 7) {
                        image = `
                            <div class="video-overlay image-detail d-flex justify-content-center align-items-center" data-image="${i}">
                                <div class="video-play-button bg-white text-dark bg-opacity-75 rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-plus"></i>
                                </div>
                            </div>
                        `;
                    }
                    
                    div.innerHTML = `
                        ${image}
                        <img src="${trataImagem(array[i], 'media')}" alt="${array[i]}" class="video-thumbnail w-100 h-100 rounded-3 shadow-sm" style="object-fit: cover;">
                    `;
                    
                    // Adicionar animação de entrada com zoom
                    div.style.transform = 'scale(0.8)';
                    div.style.opacity = '0';
                    div.style.transition = `transform ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-in-out`;
                    setTimeout(() => { 
                        div.style.transform = 'scale(1)';
                        div.style.opacity = '1';
                    }, 200 * i);
                }
                
                
                fragmento.appendChild(div);
            }
            
            i++;
        }
        
        let div = document.createElement('div');
        div.classList.add('gallery-wrapper');
        div.appendChild(fragmento);
        
        if (array.length == 1) {
            div.style.gridTemplateColumns = 'repeat(1, 1fr)';
            div.style.gridTemplateRows = '400px 0px';
        } else if (array.length == 2) {
            div.style.gridTemplateColumns = 'repeat(2, 1fr)';
            div.style.gridTemplateRows = '400px 0px';
        } else if (array.length < (item3 + 1)) {
            div.style.gridTemplateRows = '400px 0px';
        }
        
        this.div.appendChild(div);
        
        // Criar modal com fundo escuro - nova implementação melhorada
        this.divEscura = document.createElement('div');
        this.divEscura.classList.add('position-fixed', 'w-100', 'h-100', 'top-0', 'start-0', 'd-none', 'imaginator-modal');
        this.divEscura.style.zIndex = '1050'; // Valor alto para garantir que fique acima de outros elementos
        
        this.divEscura.innerHTML = `
        <div class="btn fechar-modal position-absolute top-3 end-3 rounded-circle p-2 bg-white nav-button" style="z-index: 1051;">
            <i class="bi bi-x-lg fs-4"></i>
        </div>
        <div class="w-100 h-100 d-flex justify-content-center align-items-center modal-background">
            <div class="d-flex flex-column gap-4 modal-content-wrapper">
                <div class="imagem text-center position-relative"  style="max-height: 90vh; max-width: 90vw;">
                    <!-- As setas de navegação -->
                    <div class="btn navegar-anterior position-absolute top-50 translate-middle-y start-3 rounded-circle p-2 bg-white nav-button">
                        <i class="bi bi-chevron-left fs-4"></i>
                    </div>
                    <div class="btn navegar-proximo position-absolute top-50 translate-middle-y end-3 rounded-circle p-2 bg-white nav-button">
                        <i class="bi bi-chevron-right fs-4"></i>
                    </div>
                </div>
                <div style="max-height: 95vh; max-width: 95vw;">
                    <div class="galeria imaginator-gallery d-flex gap-3 align-items-center overflow-auto px-3 py-2">
                        <!-- Miniaturas serão adicionadas aqui -->
                    </div>
                </div>
                <div class="contador text-center">
                    <span class="imaginator-counter text-white px-3 py-1 rounded-pill">1/${array.length}</span>
                </div>
            </div>
        </div>
        `;
        
        document.body.appendChild(this.divEscura);
        
        // Configurar eventos
        evento(this.divEscura.querySelector('.fechar-modal'), 'click', (e) => {
            e.stopPropagation();
            this.fechando();
        });
        
        evento(this.divEscura.querySelector('.navegar-anterior'), 'click', (e) => {
            e.stopPropagation(); // Evitar que o clique feche o modal
            this.imagemAnterior();
        });
        
        evento(this.divEscura.querySelector('.navegar-proximo'), 'click', (e) => {
            e.stopPropagation(); // Evitar que o clique feche o modal
            this.proximaImagem();
        });
        
        evento(div.querySelector('.view-all-photos'), 'click', this.abrirImagens.bind(this));
        
        // Adicionar evento para fechar ao clicar em qualquer lugar sem ação específica
        evento(this.divEscura, 'click', (e) => {
            // Lista de classes que devem ser ignoradas (elementos com ações específicas)
            const elementosInterativos = [
                'miniatura-wrapper', 'nav-button', 'navegar-anterior', 'navegar-proximo', 
                'fechar-modal', 'imaginator-main-image', 'bi-chevron-left', 'bi-chevron-right',
                'bi-x-lg'
            ];
            
            // Verificar se o clique foi em um elemento interativo ou em um de seus filhos
            let elementoAtual = e.target;
            let ehInterativo = false;
            
            while (elementoAtual && elementoAtual !== this.divEscura) {
                // Verificar se o elemento tem alguma das classes interativas
                if (elementosInterativos.some(classe => elementoAtual.classList.contains(classe)) ||
                    elementoAtual.tagName === 'IMG' || // Imagens são interativas
                    elementoAtual.tagName === 'BUTTON' || // Botões são interativos
                    elementoAtual.hasAttribute('data-index')) { // Elementos com data-index são interativos
                    ehInterativo = true;
                    break;
                }
                elementoAtual = elementoAtual.parentElement;
            }
            
            // Se não for interativo, fechar o modal
            if (!ehInterativo) {
                this.fechando();
            }
        });
        
        // Associar evento de clique a todas as imagens de thumbnail
        const imagesDetail = div.querySelectorAll('.image-detail');
        evento(Array.from(imagesDetail), 'click', this.abrirImagem.bind(this));
        
        // Adicionar evento de teclado para navegação
        evento(document, 'keydown', this.navegacaoTeclado.bind(this));
    }
    
    navegacaoTeclado(e) {
        if (this.divEscura.classList.contains('d-none')) return;
        
        if (e.key === 'ArrowLeft') {
            this.imagemAnterior();
        } else if (e.key === 'ArrowRight') {
            this.proximaImagem();
        } else if (e.key === 'Escape') {
            this.fechando();
        }
    }
    
    fechando() {
        // Limpar eventos de drag
        this.limparEventosDrag();
        
        // Adicionar animação de saída
        const modalContent = this.divEscura.querySelector('.modal-content-wrapper');
        modalContent.style.transition = `transform ${this.animationDuration}ms ease-in-out, opacity ${this.animationDuration}ms ease-in-out`;
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';
        
        // Adicionar fade-out ao fundo
        this.divEscura.style.transition = `opacity ${this.animationDuration}ms ease-in-out`;
        this.divEscura.style.opacity = '0';
        
        setTimeout(() => {
            this.divEscura.classList.add('d-none');
            // Resetar estilos para próxima abertura
            modalContent.style.transform = '';
            modalContent.style.opacity = '';
            this.divEscura.style.opacity = '1';
        }, this.animationDuration);
        
        // Remover evento de teclado quando fechar o modal
        document.removeEventListener('keydown', this.navegacaoTeclado);
        
        if(this.divEscura.querySelector('.imaginator-main-image')){
            this.divEscura.querySelector('.imaginator-main-image').remove()
        }
    }
    
    limparEventosDrag() {
        // Cancelar qualquer animação de momentum
        if (this.momentumID) {
            cancelAnimationFrame(this.momentumID);
            this.momentumID = null;
        }
        
        const galeria = this.divEscura.querySelector('.galeria');
        if (!galeria) return;
        
        // Remover eventos de drag
        if (this._boundIniciarDrag) {
            galeria.removeEventListener('mousedown', this._boundIniciarDrag);
            galeria.removeEventListener('touchstart', this._boundIniciarDrag);
            document.removeEventListener('mousemove', this._boundDuranteDrag);
            document.removeEventListener('touchmove', this._boundDuranteDrag);
            document.removeEventListener('mouseup', this._boundFinalizarDrag);
            document.removeEventListener('touchend', this._boundFinalizarDrag);
        }
    }
    
    imagemAnterior() {
        const novoIndex = (this.selecionado > 0) ? this.selecionado - 1 : this.galeria.length - 1;
        this.mostrarImagem(novoIndex);
    }
    
    proximaImagem() {
        const novoIndex = (this.selecionado < this.galeria.length - 1) ? this.selecionado + 1 : 0;
        this.mostrarImagem(novoIndex);
    }
    
    mostrarImagem(index) {
        
        const imagemAtual = this.divEscura.querySelector('.imaginator-main-image');
        const contadorEl = this.divEscura.querySelector('.contador span');
        
        // Aplicar animação de transição
        if (imagemAtual) {
            imagemAtual.style.transition = `opacity ${this.animationDuration/2}ms ease-in-out, transform ${this.animationDuration/2}ms ease-in-out`;
            imagemAtual.style.opacity = '0';
            imagemAtual.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                this.selecionado = index;
                this.atualizarImagemModal();
                
                // Atualizar contador
                contadorEl.textContent = `${index + 1}/${this.galeria.length}`;
                
                // Atualizar carrossel e destacar a miniatura selecionada
                this.formarGaleria();
                
                // Fade in da nova imagem
                setTimeout(() => {
                    const novaImagem = this.divEscura.querySelector('.imagem img');
                    novaImagem.style.opacity = '1';
                    novaImagem.style.transform = 'scale(1)';
                }, 50);
            }, this.animationDuration/2);
        } else {
            this.selecionado = index;
            this.atualizarImagemModal();
            contadorEl.textContent = `${index + 1}/${this.galeria.length}`;
            this.formarGaleria();
        }
    }
    
    atualizarImagemModal() {
        const div = this.divEscura.querySelector('.imagem');
        
        
        let video = this.videoDetector.bind(this)(this.galeria[this.selecionado])
        
        if(video){
            const setas = Array.from(div.querySelectorAll('.nav-button'));
            let divEntre = document.createElement('div')
            divEntre.classList.add('imaginator-main-image', 'rounded-3', 'overflow-hidden')
            let div2 = document.createElement('div')
            div2.setAttribute("data-plyr-provider", video.tipo)
            div2.setAttribute("data-plyr-embed-id", video.id)
        
            nownFiles.add([`https://cdn.plyr.io/3.7.8/plyr.css`, "https://cdn.plyr.io/3.7.8/plyr.js"]).then(()=>{
                var v = new Plyr(div2);
                v.on('ready', () => {
                    
                })
            })
            div.innerHTML = '';
            
            divEntre.appendChild(div2)
            div.appendChild(divEntre)
            // Recolocar as setas
            setas.forEach(seta => div.appendChild(seta));
            return
        }
        
        // Verificar se já temos uma imagem
        const imagemExistente = div.querySelector('img');
        
        // Se já existe uma imagem, atualizamos apenas o src e os estilos
        if (imagemExistente) {
            imagemExistente.src = trataImagem(this.galeria[this.selecionado], 'grande');
            imagemExistente.style.opacity = '0';
            imagemExistente.style.transform = 'scale(0.95)';
        } else {
            // Se não existe, criamos uma nova imagem e preservamos as setas
            const setas = Array.from(div.querySelectorAll('.nav-button'));
            
            // Criar a nova imagem
            const img = document.createElement('img');
            img.className = 'imaginator-main-image rounded-3';
            img.src = trataImagem(this.galeria[this.selecionado], 'grande');
            img.style.maxHeight = '65vh';
            img.style.maxWidth = '100%';
            img.style.objectFit = 'contain';
            img.style.opacity = '0';
            img.style.transform = 'scale(0.95)';
            
            // Limpar div e adicionar a imagem
            div.innerHTML = '';
            div.appendChild(img);
            
            // Recolocar as setas
            setas.forEach(seta => div.appendChild(seta));
        }
    }
    
    abrirImagem(event) {
        let index = 0;
        if (event && event.currentTarget && event.currentTarget.dataset.image) {
            index = parseInt(event.currentTarget.dataset.image);
        }
        
        this.selecionado = index;
        
        // Atualizar contador
        const contadorEl = this.divEscura.querySelector('.contador span');
        contadorEl.textContent = `${index + 1}/${this.galeria.length}`;
        
        // Atualizar imagem principal
        this.atualizarImagemModal();
        
        // Formar a galeria de miniaturas
        this.formarGaleria();
        
        
        // Mostrar o modal com animação
        this.divEscura.classList.remove('d-none');
        
        // Adicionar animação de entrada
        const modalContent = this.divEscura.querySelector('.modal-content-wrapper');
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';
        modalContent.style.transition = `transform ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-in-out`;
        
        // Fade-in do fundo
        this.divEscura.style.opacity = '0';
        this.divEscura.style.transition = `opacity ${this.animationDuration}ms ease-in-out`;
        
        requestAnimationFrame(() => {
            this.divEscura.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
            
            // Animar a imagem após o modal estar visível
            setTimeout(() => {
                const img = this.divEscura.querySelector('.imagem img');
                if (img) {
                    img.style.opacity = '1';
                    img.style.transform = 'scale(1)';
                }
            }, 100);
        });
    }
    
    formarGaleria() {
        let galeria = this.divEscura.querySelector('.galeria');
        let fragmento = document.createDocumentFragment();
        
        // Verificar se é a primeira vez que estamos formando a galeria
        const primeiraVez = galeria.innerHTML === '';
        
        // Limpar galeria atual
        galeria.innerHTML = '';
        
        // Adicionar container para facilitar o drag
        const galeriaContainer = document.createElement('div');
        galeriaContainer.classList.add('galeria-container');
        galeriaContainer.style.display = 'flex';
        galeriaContainer.style.gap = '12px';
        galeriaContainer.style.padding = '5px';
        
        // Adicionar indicadores visuais para muitas imagens
        if (this.galeria.length > 6) {
            // Adicionar gradientes nas bordas para indicar que há mais conteúdo
            galeria.style.maskImage = 'linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%)';
            galeria.style.webkitMaskImage = 'linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%)';
        }
        
        // Adicionar miniaturas
        this.galeria.forEach((img, i) => {
            let div = document.createElement('div');
            div.classList.add('miniatura-item');
            
            const isActive = i === this.selecionado;
            const activeClass = isActive ? 'active' : '';
            
            
            let video = this.videoDetector.bind(this)(img)
                
            if(video){
                div.innerHTML = `
                    <div class="miniatura-wrapper ${activeClass}" style="width: 120px; height: 80px;" data-index="${i}">
                        <div class="d-flex align-items-center justify-content-center fs-20 h-100 w-100">
                            <i class="bi bi-play-circle-fill fs-25 text-white"></i>
                        </div>
                    </div>
                `;
            }else{
                div.innerHTML = `
                    <div class="miniatura-wrapper ${activeClass}" style="width: 120px; height: 80px;" data-index="${i}">
                        <img class="w-100 h-100 rounded-3" style="object-fit: cover; cursor: pointer;" src="${trataImagem(img, 'mini')}">
                    </div>
                `;
            }
           
            
            // Adicionar evento de clique para selecionar a imagem
            evento(div.querySelector('.miniatura-wrapper'), 'click', (e) => {
                e.stopPropagation(); // Evitar que o clique feche o modal
                this.mostrarImagem(i);
            });
            
            // Adicionar animação de entrada apenas na primeira vez
            if (primeiraVez) {
                div.style.opacity = '0';
                div.style.transform = 'translateY(10px)';
                div.style.transition = `transform ${this.animationDuration}ms ease-out, opacity ${this.animationDuration}ms ease-in-out`;
                
                // Animar a entrada de cada miniatura em sequência
                setTimeout(() => {
                    div.style.opacity = '1';
                    div.style.transform = 'translateY(0)';
                }, 50 * i);
            }
            
            galeriaContainer.appendChild(div);
        });
        
        fragmento.appendChild(galeriaContainer);
        galeria.appendChild(fragmento);
        
        // Scrollar para garantir que a miniatura selecionada esteja visível
        const activeMiniature = galeria.querySelector('.active');
        if (activeMiniature) {
            setTimeout(() => {
                activeMiniature.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }, 100);
        }
    }
    
    abrirImagens() {
        this.abrirImagem({ currentTarget: { dataset: { image: 0 } } });
    }
    
    // Sistema de drag melhorado para a galeria
    iniciarDrag(e) {
        const galeria = this.divEscura.querySelector('.galeria');
        if (!galeria) return;
        
        this.isDragging = true;
        galeria.classList.add('dragging');
        
        // Capturar posição inicial (funciona tanto para mouse quanto para touch)
        this.startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        this.startScrollLeft = galeria.scrollLeft;
        this.timestamp = Date.now();
        this.lastDragX = this.startX;
        this.velocity = 0;
        
        // Parar qualquer momentum anterior
        if (this.momentumID) {
            cancelAnimationFrame(this.momentumID);
            this.momentumID = null;
        }
    }
    
    duranteDrag(e) {
        const galeria = this.divEscura.querySelector('.galeria');
        if (!this.isDragging || !galeria) return;
        
        // Prevenir comportamento padrão (como scrolling da página)
        e.preventDefault();
        
        // Calcular movimento
        const currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        const deltaX = this.startX - currentX;
        
        // Aplicar resistência nos extremos para feedback tátil
        let newScrollLeft = this.startScrollLeft + deltaX;
        const maxScroll = galeria.scrollWidth - galeria.clientWidth;
        
        if (newScrollLeft < 0) {
            // Resistência ao início
            newScrollLeft = -Math.pow(-newScrollLeft, 0.7);
        } else if (newScrollLeft > maxScroll) {
            // Resistência ao final
            newScrollLeft = maxScroll + Math.pow(newScrollLeft - maxScroll, 0.7);
        }
        
        galeria.scrollLeft = newScrollLeft;
        
        // Calcular velocidade para inércia
        const now = Date.now();
        const dt = now - this.timestamp;
        if (dt > 0) {
            // Suavizar a velocidade para evitar valores extremos
            const newVelocity = (this.lastDragX - currentX) / dt;
            this.velocity = 0.7 * this.velocity + 0.3 * newVelocity;
        }
        
        this.timestamp = now;
        this.lastDragX = currentX;
    }
    
    finalizarDrag(e) {
        const galeria = this.divEscura.querySelector('.galeria');
        if (!galeria || !this.isDragging) return;
        
        this.isDragging = false;
        galeria.classList.remove('dragging');
        
        // Verificar se é preciso retornar para os limites
        const maxScroll = galeria.scrollWidth - galeria.clientWidth;
        
        if (galeria.scrollLeft < 0) {
            // Animar para o início se ultrapassou o limite
            this.animarScroll(galeria, 0);
        } else if (galeria.scrollLeft > maxScroll) {
            // Animar para o final se ultrapassou o limite
            this.animarScroll(galeria, maxScroll);
        } 
        // Aplicar inércia/momentum se houver velocidade significativa
        else if (Math.abs(this.velocity) > 0.5) {
            this.aplicarMomentum(galeria);
        }
    }
    
    animarScroll(galeria, destino) {
        const inicio = galeria.scrollLeft;
        const distancia = destino - inicio;
        const duracao = 300; // ms
        let inicioTempo;
        
        const animar = (tempoAtual) => {
            if (!inicioTempo) inicioTempo = tempoAtual;
            const tempoDecorrido = tempoAtual - inicioTempo;
            const progresso = Math.min(tempoDecorrido / duracao, 1);
            
            // Função de easing
            const ease = t => t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
            
            galeria.scrollLeft = inicio + distancia * ease(progresso);
            
            if (progresso < 1) {
                requestAnimationFrame(animar);
            }
        };
        
        requestAnimationFrame(animar);
    }
    
    aplicarMomentum(galeria) {
        // Fator de decaimento (menor = mais deslizamento)
        const decayFactor = 0.95;
        // Ajuste dinâmico da velocidade com base no tamanho da galeria
        const velocityScale = Math.min(20, Math.max(10, galeria.scrollWidth / 1000));
        let velocity = this.velocity * velocityScale;
        
        // Limites de scroll
        const maxScroll = galeria.scrollWidth - galeria.clientWidth;
        
        const animateMomentum = () => {
            // Se a galeria não estiver mais disponível, cancele a animação
            if (!galeria || !document.body.contains(galeria)) {
                this.momentumID = null;
                return;
            }
            
            // Aplicar velocidade atual
            galeria.scrollLeft += velocity;
            
            // Verificar se bateu nos limites
            if (galeria.scrollLeft <= 0) {
                galeria.scrollLeft = 0;
                velocity = 0;
            } else if (galeria.scrollLeft >= maxScroll) {
                galeria.scrollLeft = maxScroll;
                velocity = 0;
            }
            
            // Reduzir velocidade gradualmente com decaimento não linear para mais naturalidade
            velocity *= (Math.abs(velocity) > 1) ? decayFactor : decayFactor * 0.9;
            
            // Continuar animação até que a velocidade seja quase zero
            if (Math.abs(velocity) > 0.2) {
                this.momentumID = requestAnimationFrame(animateMomentum);
            } else {
                this.momentumID = null;
            }
        };
        
        this.momentumID = requestAnimationFrame(animateMomentum);
    }

}

class OverflowOverlay {
            constructor() {
                this.overlay = document.getElementById('overflow-overlay');
                this.title = document.getElementById('overlay-title');
                this.subtitle = document.getElementById('overlay-subtitle');
                this.message = document.getElementById('overlay-message');
                this.actions = document.getElementById('overlay-actions');
                this.icon = document.getElementById('spinner-icon');
                this.isVisible = false;
            }

            show(options = {}) {
                const defaults = {
                    title: 'Carregando...',
                    subtitle: 'Por favor, aguarde enquanto processamos sua solicitação.',
                    message: 'Isso pode levar alguns segundos.',
                    icon: 'bi-gear',
                    showActions: false,
                    autoHide: true,
                    duration: 3000
                };

                const config = { ...defaults, ...options };

                // Atualiza o conteúdo
                this.title.textContent = config.title;
                this.subtitle.textContent = config.subtitle;
                this.message.textContent = config.message;
                this.icon.className = `bi ${config.icon}`;

                // Mostra/esconde ações
                this.actions.style.display = config.showActions ? 'flex' : 'none';

                // Mostra o overlay
                this.overlay.classList.add('show');
                this.isVisible = true;

                // Auto-hide se configurado
                if (config.autoHide && config.duration > 0) {
                    setTimeout(() => {
                        this.hide();
                    }, config.duration);
                }

                return this;
            }

            hide() {
                this.overlay.classList.remove('show');
                this.isVisible = false;
                return this;
            }

            toggle() {
                return this.isVisible ? this.hide() : this.show();
            }

            updateContent(title, subtitle = '', message = '') {
                if (title) this.title.textContent = title;
                if (subtitle) this.subtitle.textContent = subtitle;
                if (message) this.message.textContent = message;
                return this;
            }

            showActions() {
                this.actions.style.display = 'flex';
                return this;
            }

            hideActions() {
                this.actions.style.display = 'none';
                return this;
            }
        }

const overlay = new OverflowOverlay();

      

     


