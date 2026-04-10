class LinhaComentario{
    constructor(pai){
        this.pai = pai
        this.respondendo = false;
    }
    
    set(autor, mensagem){
        var me = pegaLocal("nown");
        if(me){
            var me = JSON.parse(me)
            
            if(!me.usuario.autorizado){
                me = false;
            }else{
                //console.log(me.usuario.usuario)
            }
        }
        
        
        this.autor = autor
        this.comentario = mensagem.texto
        this.myId = mensagem.id
        
        this.data = mensagem.data == 0 ? "Agora Mesmo" : this.formataData(mensagem.data)
        
        this.atributos = mensagem;

    }
    
     formataData(dataString) {
  const data = new Date(dataString);
  const agora = new Date();
  const umDia = 24 * 60 * 60 * 1000; 


  if (
    data.getDate() === agora.getDate() &&
    data.getMonth() === agora.getMonth() &&
    data.getFullYear() === agora.getFullYear()
  ) {
    return `${('0' + data.getHours()).slice(-2)}:${('0' + data.getMinutes()).slice(-2)}`;
  }


  const ontem = new Date(agora - umDia);
  if (
    data.getDate() === ontem.getDate() &&
    data.getMonth() === ontem.getMonth() &&
    data.getFullYear() === ontem.getFullYear()
  ) {
    return 'Ontem';
  }


  for (let i = 1; i <= 6; i++) {
    const dia = new Date(agora - i * umDia);
    if (
      data.getDate() === dia.getDate() &&
      data.getMonth() === dia.getMonth() &&
      data.getFullYear() === dia.getFullYear()
    ) {
      const diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
      return diasSemana[dia.getDay()];
    }
  }


  const diaFormatado = ('0' + data.getDate()).slice(-2);
  const mesFormatado = ('0' + (data.getMonth() + 1)).slice(-2);
  const anoFormatado = data.getFullYear();
  return `${diaFormatado}/${mesFormatado}/${anoFormatado}`;
}
    
    responder(){
        if(!this.respondendo){
            var comentario = new BoxComentario(document.getElementById("containerComentario"), 0)

            this.respondendo  = new BoxComentario(this.containerResposta, this.myId);
            this.respondendo.set(this.pai.grupo, this.pai.identificador)
            this.respondendo.render();
            this.respondendo.listar();
        }else{
            this.containerResposta.innerHTML = ""
            this.respondendo = false;
        }
    }
    
    render(){
    const div1 = document.createElement('div');
    div1.classList.add('d-flex', 'justify-content-start', 'gap-3');
    
    const div2 = document.createElement('div');
    const div3 = document.createElement('div');
    div3.classList.add('bg-danger', 'wi-50', 'he-50', 'rounded-circle');
    div2.appendChild(div3);
    
    const div4 = document.createElement('div');
    div4.classList.add("w-100")
    const innerDiv = document.createElement('div');
    innerDiv.classList.add('d-flex', 'justify-content-start', 'gap-2', 'align-items-center');
    
    const h2 = document.createElement('h2');
    h2.classList.add('fs-12', 'm-0', 'fw-700');
    h2.textContent = '@patriciadeoliveirasilva8858';
    
    const span = document.createElement('span');
    span.classList.add('fs-12');
    span.textContent = this.data;
    
    
    
    innerDiv.appendChild(h2);
    innerDiv.appendChild(span);
    
   
    
    
    
    const div5 = document.createElement('div');
    const button1 = document.createElement('button');
    button1.setAttribute('type', 'button');
    button1.classList.add('btn', 'fs-12');
    button1.innerHTML = '<i class="bi bi-hand-thumbs-up-fill"></i>';
    
    const button2 = document.createElement('button');
    button2.setAttribute('type', 'button');
    button2.classList.add('btn', 'fs-12');
    button2.innerHTML = '<i class="bi bi-hand-thumbs-down-fill"></i>';
    
    const button3 = document.createElement('button');
    button3.setAttribute('type', 'button');
    button3.classList.add('btn', 'fs-12', 'fw-500');
    button3.textContent = 'Responder';
    evento(button3, "click", this.responder.bind(this))
    
    if(this.atributos.respostas > 0){
        button3.classList.add("d-flex", "justify-content-center", "align-items-center", "gap-3")
        if(this.atributos.respostas == 1){
            button3.innerHTML = `<span class="text-primaria">1 Resposta</span><span>Responder</span>`;
        }else{
            button3.innerHTML = `<span class="text-primaria">${this.atributos.respostas} Respostas</span><span>Responder</span>`;
        }
    }
    
    div5.appendChild(button1);
    div5.appendChild(button2);
    div5.appendChild(button3);
    
    
    
    div4.appendChild(innerDiv);
    
    
     const p = document.createElement('p');
    p.classList.add('p-0', 'fs-14', 'm-0');
    if(this.comentario.texto){
        p.textContent = this.comentario.texto
    }
    
    div4.appendChild(p);
    
    if(this.comentario.anexos){
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var i = 0;
        while(i < this.comentario.anexos.length){
            var div = document.createElement("DIV")
            div.classList.add("col-4")
      

            var quadro = document.createElement("DIV")
            quadro.classList.add("ratio", "ratio-1x1")
            div.appendChild(quadro)
            quadro.style = `background-image: url(${this.comentario.anexos[i]});background-size: cover; background-position: center center;`
             
    
            row.appendChild(div)
            
            i++;
        }
        div4.appendChild(row)
    }
    

    div4.appendChild(div5);
    
    this.containerResposta = document.createElement("DIV")
    div4.appendChild(this.containerResposta);
    
    
    div1.appendChild(div2);
    div1.appendChild(div4);
    
    this.pai.body.appendChild(div1);
    
    if(this.pai.body.getElementsByClassName("sejaOprimeiro").length == 1){
        this.pai.body.getElementsByClassName("sejaOprimeiro")[0].remove();
    }

    }
}

class BoxComentario{
    constructor(container, pai){
        this.container = container;
        this.comentarioPai = pai
        this.geraBtn = this.geraBtn.bind(this)
        this.anexos = [];
        if(pai == 0){
            this.createCardComponent.bind(this)()
        }
        
    }
    
    showMobile(){
         this.card.classList.add("ativo")
    }
    createCardComponent() {
  // Criando elementos HTML
  const cardDiv = document.createElement('div');
  evento(cardDiv, "click", this.showMobile.bind(this))
  cardDiv.className = 'card mb-3 card-nown rounded-1  d-block d-xl-none';

  const cardHeaderDiv = document.createElement('div');
  cardHeaderDiv.className = 'card-header bg-transparent border-0 pb-0';
  const headerSpan = document.createElement('span');
  headerSpan.className = 'fs-14 fw-700';
  headerSpan.textContent = 'Comentários';
  cardHeaderDiv.appendChild(headerSpan);

  const cardBodyDiv = document.createElement('div');
  cardBodyDiv.className = 'card-body py-1';
  const bodyContentDiv = document.createElement('div');
  bodyContentDiv.className = 'd-flex justify-content-start gap-3 align-items-center';
  const avatarDiv = document.createElement('div');
  avatarDiv.innerHTML = '<div class="wi-50 he-50 bg-danger rounded-circle"></div>';
  const textDiv = document.createElement('div');
  const paragraph = document.createElement('p');
  paragraph.className = 'm-0 text-start fs-12';
  paragraph.style.cssText = 'overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;';
  paragraph.textContent = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...'; // Texto cortado por questões de tamanho

  textDiv.appendChild(paragraph);
  bodyContentDiv.appendChild(avatarDiv);
  bodyContentDiv.appendChild(textDiv);
  cardBodyDiv.appendChild(bodyContentDiv);

  const cardFooterDiv = document.createElement('div');
  cardFooterDiv.className = 'card-footer bg-transparent border-0 pt-0';
  const button = document.createElement('button');
  button.className = 'btn btn-sm w-100 fw-700';
  button.id = 'verComentarios';
  button.textContent = 'Ver Comentários';
  cardFooterDiv.appendChild(button);

  // Adicionando os elementos criados à div principal
  cardDiv.appendChild(cardHeaderDiv);
  cardDiv.appendChild(cardBodyDiv);
  cardDiv.appendChild(cardFooterDiv);

  this.container.appendChild(cardDiv);
}

    geraBtn(ic, titulo, cb){
        var button = document.createElement("BUTTON")
        button.classList.add("btn")
        button.title = titulo
        
        
        var icone = document.createElement("I")
        icone.classList.add("bi", ic)
        
        button.appendChild(icone)
        evento(button, "click", cb)
        return button;
    }
    
    call(){
     alert("rodo muito bem")   
    }
    
    comentando(){
        this.fakeInput.classList.add("ativo")
        this.input.focus();
    }
    
    digitando(){
         this.closeGif.bind(this)()
 
        if(this.input.innerText || this.anexos.length > 0){
            this.input.classList.remove("inputVazio")
            this.fakeInput.classList.add("ativo")
            this.btnEnvia.removeAttribute("disabled")

        
            
        }else{
           this.input.classList.add("inputVazio")
            this.fakeInput.classList.remove("ativo")
            this.btnEnvia.setAttribute("disabled", "")
        }
    }
    
    cadastrado(r){
        console.log(r)
    }
    
    envia(){
        let comentario = {};
        var ativo = false;
        var texto = {};
         if(this.input.innerText){
             texto.texto = this.input.innerText
             ativo = true;
         }
         
         if(this.anexos.length > 0){
             texto.anexos = this.anexos;
             ativo = true;
         }
         
         
         if(ativo){
             
              comentario.data = 0;
              comentario.texto = texto
              
             this.input.innerHTML = ""
             this.btnImg.removeAttribute("disabled")
             this.btnGif.removeAttribute("disabled")
             this.FooterAnexo.getElementsByClassName("row")[0].innerHTML = ""
             this.FooterAnexo.classList.add("d-none")
             this.anexos = [];
             var novo = new LinhaComentario(this);
             novo.set(0, comentario)
             novo.render();
             this.digitando.bind(this)()
             
             var data = new FormData();
             data.append("acao", "novo")
             data.append("tipo", "comentario");
             data.append("grupo", this.grupo);
             data.append("identificador", this.identificador)
             data.append("pai", this.comentarioPai)
             data.append("comentario", JSON.stringify(texto))
             this.ajax.bind(this)(data, this.cadastrado.bind(this))

         }
         
    }
    
    clicouFora(){
        this.EmojiContainer.innerHTML = ""
    }
    
    naoTem(){
        var div = document.createElement("DIV")
        div.classList.add("sejaOprimeiro")
        div.innerHTML = `
        <h2 class="text-center fs-18 fw-700">Ainda não existem comentarios</h2>
        <p class="text-center m-0">Seja o primeiro a fazer um comentário</p>
        `
        this.body.appendChild(div)
    }
    
    setCursorPosition(input, position) {
    if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(position, position);
    } else if (input.createTextRange) {
        const range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', position);
        range.moveStart('character', position);
        range.select();
    }
}

    setEmoji(r) {
  
        const selection = window.getSelection();
        var posicao = selection.anchorOffset;
        
        var texto = this.input.innerText
        
    
        const textoParte1 = texto.substring(0, posicao); 
        const textoParte2 = texto.substring(posicao); 
        
        var icone = `  ${r.native}  `
        
        this.input.innerText = `${textoParte1} ${r.native} ${textoParte2}`

        const range = document.createRange();
        const sel = window.getSelection();
        
        range.setStart(this.input.childNodes[0], posicao + 3);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);

        this.digitando();
}

    emoji(){
        this.closeGif.bind(this)()
        loadResources("https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js").then(()=>{
             const pickerOptions = { 
                 onEmojiSelect: this.setEmoji.bind(this),
                 onClickOutside: this.clicouFora.bind(this)
                 
             }
             const picker = new EmojiMart.Picker(pickerOptions);
          
             
             
             this.EmojiContainer.appendChild(picker)
       })
    }
    
    enterDetection(){
         if (event.key === 'Enter') {
             event.preventDefault()
             this.envia.bind(this)()
         }
    }
    
    hideMobile(){
        this.card.classList.remove("ativo")
    }
    
    show(){
         this.card.classList.add("ativo")
    }
    
    gif(){
        this.FooterGif.classList.add("ativo")
        this.inputGif.value = ""
        this.procurarGif.bind(this)()
        setTimeout(()=>{
            this.inputGif.focus();
        }, 100)
    }
    
    selecionaGif(){
        this.addAnexo.bind(this)(event.currentTarget.dataset.url);
        this.closeGif.bind(this)()
    }
    
    procurarGif(){
        var termo = !this.inputGif.value ? "Brasil" : this.inputGif.value;
        
        this.resultadosGif.innerHTML = ""
        
        const API_KEY = 't1CRQZid50OOha1BYjckIDJoT3UPDZX3';
        const searchTerm = termo;

    


    fetch(`https://api.giphy.com/v1/gifs/search?api_key=${API_KEY}&q=${searchTerm}`)
        .then(response => response.json())
        .then(data => {
     this.resultadosGif.innerHTML = ""
            data.data.forEach(gif => {
              
              
               var div = document.createElement("DIV")
            div.classList.add("col-4", "col-md-3", "col-xl-2", "p-2")
            
            var quadro = document.createElement("DIV")
            quadro.classList.add("ratio", "ratio-1x1", "btn")
            div.appendChild(quadro)
           
            
                   
             
                
                const imageUrl = gif.images.fixed_height.url;
               
                evento(quadro, "click", this.selecionaGif.bind(this))
                quadro.dataset.url = imageUrl

                quadro.style = `
                background-image: url(${imageUrl});
    background-size: cover;
    background-position: center center;
                
                `
         this.resultadosGif.appendChild(div)
               
            });
          

     
        })
        .catch(error => {
            console.error('Houve um erro ao buscar os GIFs:', error);
        });

        
        
        
        
        
        
        var i = 0;
        while(i < 24){
            var div = document.createElement("DIV")
            div.classList.add("col-4", "col-md-3", "col-xl-2", "p-2")
            
            var quadro = document.createElement("DIV")
            quadro.classList.add("ratio", "ratio-1x1", "bg-carregando")
            div.appendChild(quadro)
            this.resultadosGif.appendChild(div)
            i++;
        }
        
        
    }
    
    closeGif(){
        this.FooterGif.classList.remove("ativo")
        this.resultadosGif.innerHTML = ""
        this.inputGif.value = ""
    }
    
    deletaAnexo(){
        var pai =  event.currentTarget.closest(".anexo")
        var anexo = pai.dataset.url
        console.log(anexo)
        const palavra = anexo
        const array = this.anexos
        const index = array.indexOf(palavra); 
        if (index !== -1) {
            this.anexos.splice(index, 1); 
        }
        
        pai.remove();
  
        if(this.anexos.length == 0){
            this.FooterAnexo.classList.add("d-none")
            this.btnImg.removeAttribute("disabled")
            this.btnGif.removeAttribute("disabled")
        }
    }
    
    addAnexo(url){
        this.btnImg.setAttribute("disabled", "")
        this.btnGif.setAttribute("disabled", "")
        
        this.anexos.push(url)
        this.digitando.bind(this)()
        
         var div = document.createElement("DIV")
            div.classList.add("col-4", "p-2", "anexo","position-relative")
            div.dataset.url = url
            
            var quadro = document.createElement("DIV")
            quadro.classList.add("ratio", "ratio-1x1")
            div.appendChild(quadro)
            quadro.style = `background-image: url(${url});background-size: cover; background-position: center center;
             `
             
            let deleta = document.createElement("BUTTON")
            deleta.classList.add("btn", "btn-danger", "text-white", "position-absolute", "top-0","end-0", "btn-sm", "mt-3", "me-3", "wi-25", "he-25", "d-flex", "justify-content-center", "align-items-center")
            evento(deleta, "CLICK", this.deletaAnexo.bind(this))
            deleta.innerText = "X"
            div.appendChild(deleta)
             
             this.FooterAnexo.classList.remove("d-none")
         this.FooterAnexo.getElementsByClassName("row")[0].appendChild(div)
    }
    
    render(){
        var card = document.createElement("DIV")
        card.classList.add("card", "boxComentarios", "border-0")
        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "d-flex","justify-content-between","gap-2", "border-0", "bg-transparent", "order-3", "order-xl-2")
        
        var containerFoto = document.createElement("DIV")
        var foto = document.createElement("DIV")
        foto.classList.add("bg-danger","wi-50","he-50","rounded-circle")
        containerFoto.appendChild(foto)
        
        header.appendChild(containerFoto)
        
        var containerInput = document.createElement("DIV")
        containerInput.classList.add("w-100")
        
        var fakeInput = document.createElement("DIV")
        fakeInput.classList.add("form-control", "formComentario", "justify-content-between")
        this.fakeInput = fakeInput
        evento(fakeInput, "click", this.comentando.bind(this))
        
        var editavel = document.createElement("DIV")
        editavel.classList.add("inputVazio", "inputComentario")
        editavel.setAttribute("contenteditable", "true")
        this.input = editavel
        evento(this.input, "INPUT", this.digitando.bind(this))
        evento(this.input, "keypress", this.enterDetection.bind(this))
        
        var grupoBtnsEsquerda = document.createElement("DIV")
        grupoBtnsEsquerda.classList.add("position-relative")
        grupoBtnsEsquerda .appendChild(this.geraBtn("bi-emoji-laughing-fill", "Adicionar Emoji", this.emoji.bind(this)));
        this.EmojiContainer = document.createElement("DIV")
        this.EmojiContainer.classList.add("position-absolute")
        this.EmojiContainer.style.zIndex = 5
        
        grupoBtnsEsquerda .appendChild(this.EmojiContainer);
        this.btnImg = this.geraBtn("bi-image", "Adicionar Imagem", this.call.bind(this));
        this.btnGif = this.geraBtn("bi-filetype-gif", "Adicionar GIF", this.gif.bind(this))
        
        grupoBtnsEsquerda .appendChild(this.btnImg);
        grupoBtnsEsquerda .appendChild(this.btnGif);
        
        
        this.btnEnvia = this.geraBtn("bi-arrow-right-circle-fill", "Enviar", this.envia.bind(this));
        this.btnEnvia.setAttribute("disabled", "")
        this.btnEnvia.classList.add("btnEnviar")
        
        var grupoBtns = document.createElement("DIV")
        grupoBtns.classList.add("d-flex", "justify-content-between")
        
        grupoBtns.appendChild(grupoBtnsEsquerda)
        grupoBtns.appendChild(this.btnEnvia)
        
        
        fakeInput.appendChild(editavel)
        fakeInput.appendChild(grupoBtns)
        
        containerInput.appendChild(fakeInput)
        
        header.appendChild(containerInput)
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "d-flex", "flex-column", "gap-3", "overflow-y-auto", "order-2", "order-xl-4")
        this.body = body;
        this.naoTem.bind(this)()
        
        var headerMobile = document.createElement("DIV")
        headerMobile.classList.add("card-header", "d-flex","justify-content-between","gap-2", "border-0", "bg-transparent", "order-1", "align-items-center")
        
        var titulo = document.createElement("H2")
        titulo.classList.add("fs-18", "fw-700", "m-0")
        this.tituloSize = titulo
        if(this.comentarioPai > 0){
            headerMobile.classList.add("d-xl-none")
        }
        
        
        var fechar = document.createElement("BUTTON")
        fechar.classList.add("btn", "d-xl-none")
        fechar.innerText = "X"
        evento(fechar, "click", this.hideMobile.bind(this))
        
        headerMobile.appendChild(titulo)
        headerMobile.appendChild(fechar)
        card.appendChild(headerMobile)
        
        this.FooterGif = document.createElement("DIV")
        this.FooterGif.classList.add("card-footer", "order-4", "order-xl-3", "border-0", "comentarioGif")
        
        var grupo = document.createElement("DIV")
        grupo.classList.add("input-group")
        
        var fechaGif = document.createElement("BUTTON")
        fechaGif.innerText = "X"
        fechaGif.classList.add("btn", "fw-700")
        evento(fechaGif, "click", this.closeGif.bind(this))
        
        
        var span = document.createElement("SPAN")
        span.classList.add("input-group-text")
        span.innerHTML = `<i class="bi bi-search"></i>`
        
        var input = document.createElement("INPUT")
        input.classList.add("form-control")
        this.inputGif = input
        this.timeoutId= null;
        evento(input, "INPUT", () =>{
            clearTimeout(this.timeoutId); 
            this.timeoutId = setTimeout(() => {
                this.procurarGif.bind(this)()
        }, 500);
})
        
        

        input.placeholder = "Procurar GIF"
        grupo.appendChild(span)
        grupo.appendChild(input)
        
        this.resultadosGif = document.createElement("DIV")
        this.resultadosGif.classList.add("row", "overflow-y-auto", "p-0", "m-0")
        this.resultadosGif.style = "max-height: 400px"
        
        var flex = document.createElement("DIV")
        flex.classList.add("mb-3", "d-flex", "justify-content-between", "align-items-center", "gap-3")
        flex.appendChild(grupo)
        flex.appendChild(fechaGif)
        
        this.FooterGif.appendChild(flex)
        this.FooterGif.appendChild(this.resultadosGif)
        
        
        this.FooterAnexo = document.createElement("DIV")
        this.FooterAnexo.classList.add("card-footer", "order-4", "order-xl-3", "border-0", "comentarioAnexo", "d-none")
        var row = document.createElement("DIV")
        row.classList.add("row")
        this.FooterAnexo.appendChild(row)
        
        card.appendChild(header)
        card.appendChild(body)
        card.appendChild(this.FooterGif)
        card.appendChild(this.FooterAnexo)
        this.card = card
        this.container.appendChild(card)
        
        
        
  
    }
    
    listado(r){
        var i = 0;
        while(i < r.lista.length){
            var item = r.lista[i]
        
             var novo = new LinhaComentario(this);
             novo.set(0, item)
             novo.render();
            
            i++;
        }
        
        switch(parseInt(r.tamanho)){
            case 0:
                this.tituloSize.innerText = "Nenhum comentário"
                break;
            case 1:
                this.tituloSize.innerText = "1 comentário"
                break;
            default:
                this.tituloSize.innerText = `${r.tamanho} comentários`;
                break;
        }

    }
    
    set(grupo, id){
        this.grupo = grupo
        this.identificador = id
    }
    
    listar(){
        var data = new FormData();
        data.append("acao", "listar")
        data.append("tipo", "comentario");
        data.append("grupo", this.grupo);
        data.append("identificador", this.identificador)
        data.append("pai", this.comentarioPai)
        this.ajax.bind(this)(data, this.listado.bind(this))
    }
    
    ajax(data, cb = false){
        var request = new XMLHttpRequest();
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
        request.open("POST", `${dominio}/admin/atributos.php`)
        request.send(data)
        
    }
}