class CardSalvar{
    constructor(input, container, info){

        this.input = input
        this.container = container
        this.opt = this.opt.bind(this)
        this.edit = this.container.edit
        this.info = info
        

        
        
        this.preData = false;
        if(container.memoria && container.memoria.especiais && container.memoria.especiais.data){
            this.preData = container.memoria.especiais.data;
        }
        
        this.btnsS = [];
        
        this.estado = true;
        this.visibilidade = true
        this.agendamento = true;
        
        if(this.info.infos && this.info.infos.funcoes){
            var infos = this.info.infos.funcoes
            
            if(infos["desabilitar-agendados"]){
                this.agendamento = false;
            }
            
            if(infos["desabilitar-status"]){
                this.estado = false;
            }
            
            if(infos["desabilitar-vizibilidade"]){
                this.visibilidade  = false;
            }
        }
        
        this.valorStatus = 1;
        this.valorVisibilidade = 0;
        this.valorAgendamento = 0;
        this.super = false;
        var usuario = infoUser();
        if(usuario && parseInt(usuario.funcao) < 2){
           this.super = true;
        }
    }
    
    define(chave, valor){
        this[chave] = valor;
    }
    
    liCardSalvar(text, icon, status, chave){
        var id = geraId();
        var li = document.createElement("LI")
        li.classList.add("list-group-item", "p-0")
        
        
          var accordionButton = document.createElement("BUTTON");
          accordionButton.classList.add("accordion-button", "collapsed");
          accordionButton.setAttribute("data-bs-toggle", "collapse");
          accordionButton.setAttribute("data-bs-target", `#${id}`);
          accordionButton.setAttribute("aria-expanded", "false");
          accordionButton.setAttribute("aria-controls", `${id}`);
          


        
        var flex = document.createElement("DIV")
        flex.classList.add("d-flex", "justify-content-between", "align-items-center")
        
        
        var esquerda = document.createElement("DIV")
        esquerda.classList.add("d-flex", "justify-content-start", "align-items-center", "gap-4")
        
        var icone = document.createElement("SPAN")
        icone.innerHTML = `<i class="bi ${icon} fs-12"></i>`
        
        
        var texto = document.createElement("DIV")
        texto.classList.add("d-flex", "justify-content-start", "align-items-center", "gap-4")

        var span = document.createElement("SPAN")
        span.classList.add("fs-12","text-uppercase")
        span.innerText = `${text}:`
        texto.appendChild(span)
        
        
        var span2 = document.createElement("SPAN")
        span2.classList.add("fs-12","text-uppercase", "fw-700")
        span2.innerText = status
        texto.appendChild(span2)
        this[chave] = span2
   
        
        esquerda.appendChild(icone)
        esquerda.appendChild(texto)
        
        var direita = document.createElement("DIV")

        
        flex.appendChild(esquerda)
        flex.appendChild(direita)
        accordionButton.appendChild(flex)
        
        li.appendChild(accordionButton)
        
        var collapseDiv = document.createElement("DIV");
        collapseDiv.classList.add("collapse");
        collapseDiv.setAttribute("id", id);
        collapseDiv.setAttribute("data-bs-parent", "#categorizador");
        
        var div = document.createElement("DIV")
        div.classList.add("card-body", "corpoLi")
        collapseDiv.appendChild(div)
        
        li.appendChild(collapseDiv)
        return li;
        
    }
    
    opt(texto, valor, select){
        var option = document.createElement("OPTION")
        option.value = valor
        option.text = texto
        select.appendChild(option)
        
    }
    
    setFixed(chave){
        this[chave] = true;
    }
    
    acesso(){
        this.selectShow.classList.add("d-none")
        this.selectHide .classList.add("d-none")
        var titulo = this.Todos
        switch(parseInt(this.selectUsuarios.value)){
            case 1:
                this.selectShow.classList.remove("d-none")
                titulo.innerText = "Mostrar"
                this.valorVisibilidade = 1;
                break;
            case 2:
                this.selectHide .classList.remove("d-none")
                titulo.innerText = "Esconder"
                this.valorVisibilidade = 2;
                break;
            default:
                titulo.innerText = "Todos"
                this.valorVisibilidade = 0;
                break;
        }
    }
    
    listaFunctions(input){
        
        var array = {
            "Logados" : "logados",
            "Deslogados" : "deslogados",
            "Equipe" : "equipe",
            "Clientes" : "clientes"
        }
        

        
        for(let chave in array){
            var option = document.createElement("OPTION")
            option.value = array[chave]
            option.innerText = chave
            input.appendChild(option)
        }
        
        
    }

    timer(){
        var valor = this.inputData.value
        
        var dataFornecida = new Date(valor);
        var dataAtual = new Date(); 

        
        if(dataFornecida <= dataAtual){
            if(!this.super){
                this["Imediatamente"].innerText = "Imediatamente";
                this.valorAgendamento = 0;
                const now = new Date();
                const year = now.getFullYear().toString().padStart(4, '0');
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const day = now.getDate().toString().padStart(2, '0');
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
                this.inputData.value = formattedDate;
            }else{
                 this["Imediatamente"].innerText = belaData(valor);
                 this.inputData.value = valor;
            }
           
        }else{
            this["Imediatamente"].innerText = "Agendado"; 
            this.inputData.value = valor;
            
            
        }
        return this.inputData.value;
        
        
        
        
        
        
        /*
       
        var dataAtual = new Date(); 
        if (dataFornecida <= dataAtual) {

        
        if(this.preData){
            this["Imediatamente"].innerText = belaData(this.preData);
            this.inputData.value = this.preData;
            this.valorAgendamento = this.preData;
        }else{
              
        if(!this.super){
            this["Imediatamente"].innerText = "Imediatamente";
            this.valorAgendamento = 0;
            const now = new Date();
            const year = now.getFullYear().toString().padStart(4, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const day = now.getDate().toString().padStart(2, '0');
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
            this.inputData.value = formattedDate;
        }else{
            this.inputData.value = valor;
            this.valorAgendamento = valor;
        }
            
            
            
        }
        
        
      
        
        
        
        }else {
        this["Imediatamente"].innerText = "Agendado"; 
        this.valorAgendamento = valor
        }

        return this.valorAgendamento;
        */
    }
    
    controlaEstado(){
        var valor = this.selectStatus.value
        if(valor == "1"){
            this["Publicado"].innerText = "Publicado"
            this.valorStatus = 1;
        }else{
            this["Publicado"].innerText = "Rascunhado"
            this.valorStatus = 0;
        }

    }
    
    apagar(){
        this.container.apagar();
    }
    
    render(){
        var body = this.input.getElementsByClassName("card-body")[0];
        
        var ul = document.createElement("UL")
        ul.classList.add("list-group","list-group-flush","accordion")
        ul.id = "categorizador"
        
        /* Status */
        var status = this.liCardSalvar.bind(this)("Status", 'bi-globe-americas', "Publicado", "Publicado")
        
        var select = document.createElement("SELECT")
        select.classList.add("form-select", "fs-12")
        this.selectStatus = select;
        status.getElementsByClassName("corpoLi")[0].appendChild(select)
        evento(select, "input", this.controlaEstado.bind(this))
        
        this.opt("PUBLICADO", "1", select)
        this.opt("RASCUNHADO", "0", select)
        status.getElementsByClassName("corpoLi")[0].appendChild(select)
        if(this.estado){
            ul.appendChild(status)
        }
        

        /* Vizibilidade */
        var visibilidade = this.liCardSalvar.bind(this)("Visibilidade", 'bi-eye', "Todos", "Todos");
        visibilidade.getElementsByClassName("corpoLi")[0].classList.add("d-flex","flex-column","gap-3")
        
        
        var select = document.createElement("SELECT")
        select.classList.add("form-select", "fs-12")
        this.selectUsuarios = select
        this.opt("TODOS USUÁRIOS", "0", select)
        this.opt("MOSTRAR PARA", "1", select)
        this.opt("ESCONDER PARA", "2", select)
        evento(select, "input", this.acesso.bind(this))
        

        
        visibilidade.getElementsByClassName("corpoLi")[0].appendChild(select)
        
        
        this.selectShow = document.createElement("DIV")
        this.selectShow.classList.add("d-none")    
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "fw-500")
        label.innerText = "Mostrar para"
        var select  = document.createElement("SELECT")
        select.classList.add("form-select")
        select.setAttribute("multiple", "")
        this.listaFunctions(select);
        var opts = new  OpcoesDinamicas(select, 1, "funcoes,funcao_id,funcao_nome");
        opts.render();
        
        this.selectShow.appendChild(label)
        this.selectShow.appendChild(select)
        visibilidade.getElementsByClassName("corpoLi")[0].appendChild(this.selectShow)
        
        
        this.selectHide = document.createElement("DIV")
        this.selectHide.classList.add("d-none")    
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "fw-500")
        label.innerText = "Esconder para"
        var select  = document.createElement("SELECT")
        select.classList.add("form-select")
        select.setAttribute("multiple", "")
        this.selectHide.appendChild(label)
        this.selectHide.appendChild(select)
        this.listaFunctions(select);
        var opts = new  OpcoesDinamicas(select, 1, "funcoes,funcao_id,funcao_nome");
        opts.render();
        visibilidade.getElementsByClassName("corpoLi")[0].appendChild(this.selectHide)
        
        
        
        
    
    
        if(this.visibilidade){
            ul.appendChild(visibilidade)
        }
        
        
        
        
        
        /* Data */
        if(this.preData){
            var data = this.liCardSalvar.bind(this)("Data", 'bi-calendar', belaData(this.preData), "Imediatamente")
        }else{
            var data = this.liCardSalvar.bind(this)("Data", 'bi-calendar', "Imediatamente", "Imediatamente")
        }
        
        
        var input = document.createElement("INPUT")
        this.inputData = input
        input.type = "datetime-local"
        input.classList.add("form-control")
        
        
        const now = new Date();
        const year = now.getFullYear().toString().padStart(4, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
        if(this.preData){
            input.value = this.preData;
            if(!this.super){
            input.setAttribute("disabled", "")
            }
        }else{
            input.value = formattedDate;
            if(!this.super){
            input.min = formattedDate;
        }
        }
        
        
        
        
        evento(input, "input", this.timer.bind(this))

        data.getElementsByClassName("corpoLi")[0].appendChild(input)
     
        if(this.agendamento){
            ul.appendChild(data)
        }
        

        body.classList.add("p-0")
        body.appendChild(ul)
        
        var btn = document.createElement("BUTTON")
        btn.classList.add("btn", "btn-n-primaria", "btn-sm", "btn-nown-style")
        btn.id = "cardSalvarBtnSalvar"
        btn.dataset.uploads = "0"
        btn.innerText = this.edit ? "Atualizar" : "Salvar"
        this.btnsS.push(btn)
        evento(btn, "click", this.salvar.bind(this))
        
        if(this.edit){
             var apagar = document.createElement("BUTTON")
             apagar.classList.add("btn", "text-danger", "text-decoration-underline")
             apagar.innerText = "Apagar"
             evento(apagar, "click", this.apagar.bind(this))
             this.input.getElementsByClassName("card-footer")[0].appendChild(apagar);
             this.input.getElementsByTagName("h3")[0].innerText = "Atualizar"
        }else{
            var span = document.createElement("SPAN")
            this.input.getElementsByClassName("card-footer")[0].appendChild(span);
        }
       
        this.input.getElementsByClassName("card-footer")[0].appendChild(btn);
        
        
        if(this["mobile"] || this["pc"]){
            
            if(this["mobile"]){
                this.input.classList.add("fixedMobilePost") 
                this.input.getElementsByClassName("card-footer")[0].classList.add("d-none")
                this.input.getElementsByClassName("card-header")[0].classList.add("d-none")
                if(screen.width < 1200){
                    this.input.getElementsByClassName("collapse")[0].classList.remove("show")
                }
                
            }
            
            if(!this["pc"]){
                this.input.getElementsByClassName("card-footer")[0].classList.add("d-xl-flex")
                this.input.getElementsByClassName("card-header")[0].classList.add("d-xl-block")
               
            }else{

                this.input.classList.add("fixedPcPost")
       
     
                 if(screen.width >= 1199){
                    this.input.getElementsByClassName("collapse")[0].classList.remove("show")
                }
                
                
                
                
                
            }
            
           
            
            
            
            var div = document.createElement("DIV")
            div.classList.add("card-header", "bg-transparent","border-0", "d-flex", "justify-content-between", "rounded-0")
            
     
            if(!this["pc"]){
                div.classList.add("d-xl-none")
            }else{
                div.classList.add("container")
            }
            
            if(!this["mobile"]){
                div.classList.add("d-none")
            }
  
            
            
            
            var h3 = document.createElement("BUTTON")
            h3.classList.add("fs-14", "text-uppercase","m-0","fw-700","text-contrast", "btn", "border-0", "w-100", "text-start")
            h3.innerHTML = this.edit ? `<i class="bi bi-caret-down-fill text-contrast"></i> Atualizar` : `<i class="bi bi-caret-down-fill text-contrast"></i> Salvar`
            h3.setAttribute("type","button")
            var foco = this.input.getElementsByClassName("collapse")[0].id
            h3.setAttribute("data-bs-toggle","collapse")
            h3.setAttribute("data-bs-target",`#${foco}`)
            h3.setAttribute("aria-expanded","false")
            h3.setAttribute("aria-controls",foco)
           
      
           
            div.appendChild(h3)
            
            
            var botao = document.createElement("BUTTON")
            var botao = document.createElement("BUTTON")
            botao.classList.add("btn", "btn-n-primaria", "btn-sm", "btn-nown-style")
            botao.id = "cardSalvarBtnSalvar"
            botao.dataset.uploads = "0"
            this.btnsS.push(botao)
            botao.innerText = this.edit ? "Atualizar" : "Salvar"
            evento(botao, "click", this.salvar.bind(this))
            div.appendChild(botao)
            
            this.input.insertBefore(div, this.input.firstChild);
            
            if(this.input.getElementsByClassName("list-group")[0].getElementsByTagName("li").length == 0){
                const element = this.input.querySelector('[data-bs-toggle="collapse"]');
                if(element){
                     element.removeAttribute('data-bs-toggle');
                     element.getElementsByTagName("I")[0].remove()
                }


            }

  
        }
        
    }
    
    pegaValores(select){
         const valoresSelecionados = [];
         for (let i = 0; i < select.options.length; i++) {
             const option = select.options[i];
             if (option.selected) {
                 valoresSelecionados.push(option.value); // Adiciona o valor selecionado ao array
            }
  }

  return valoresSelecionados; // Retorna os valores selecionados
    }
    
    salvar(){
        let upando = parseInt(event.currentTarget.dataset.uploads);
        if(upando > 0){
            var msg = upando == 1 ? "Existe 1 arquivo sendo carregado." : `Existem ${upando} arquivos sendo carregados.`
            iziToast.question({
                title: 'Atenção',
                message: msg
            });
            event.currentTarget.dataset.autosave = true;
            return;
        }
        
        
        for(let c in this.btnsS){
             this.btnsS[c].setAttribute("disabled", "")
             this.btnsS[c].classList.add("waitSave")
        this.btnsS[c].innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>` 
        }
      
        
        
        var infoStatus = 1;
        var infoVisibidade = 0;
        var infoAgendamento = 0;

        
        if(this.estado){
            infoStatus = this.valorStatus;
        }
        
        if(this.visibilidade){
            if(this.valorVisibilidade > 0){
                
                var valores = this.valorVisibilidade == 1 ? this.pegaValores(this.selectShow.getElementsByTagName("select")[0]) : this.pegaValores(this.selectHide.getElementsByTagName("select")[0])
                
                infoVisibidade = {
                    regra: this.valorVisibilidade,
                    valores: valores
                }
                
            }
        }
        
        if(this.agendamento){
            infoAgendamento = this.timer.bind(this)()

        }
    

        var info = {
            status: infoStatus,
            visibilidade: infoVisibidade,
            agendamento: infoAgendamento
        };
        
       
        
        if(infoStatus == 1 && infoVisibidade == 0 && infoAgendamento == 0){
            info = false;
        }
        
        this.container.salvar(info)
    }
    
    set(v){
        
        if(v.status == 0){
            this.selectStatus.value = 0;
            this.controlaEstado.bind(this)();
        }

        if(v.agendamento != 0){
             this.inputData.value = v.agendamento
             this.timer.bind(this)()
        }
        
        if(v.visibilidade != 0){
            var obj = v.visibilidade
            if(obj){
                 this.selectUsuarios.value = obj.regra
            var foco = obj.regra == 1 ? this.selectShow : this.selectHide
            this.acesso.bind(this)();
            
            
            if(obj.valores.length > 0){
                foco.getElementsByClassName("form-select")[0].dataset.PreValor = obj.valores.join(",")
            }
            
            
            var option = foco.getElementsByTagName("option")
            for(let c in obj.valores){
                var item = obj.valores[c]
                
                var i = 0;
                while(i < option.length){
                    if(option[i].value == item){
                        option[i].selected = true;
                        break;
                    }
                    i++;
                }
       
            } 
            }
          
        }
    }
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
                clipboard: {
            matchVisual: false // Desabilita estilos visuais ao colar
        }

            },
            
            placeholder: text,
            theme: 'snow' // or 'bubble'
        });
        
        this.quill.clipboard.addMatcher(Node.ELEMENT_NODE, (node, delta) => {
  let ops = []
  delta.ops.forEach(op => {

    if (op.insert && typeof op.insert === 'string') {
      ops.push({
        insert: op.insert
      })
    }
  })
  delta.ops = ops
  console.log(delta)
  return delta
})
        
        
        
        this.quill.getModule('toolbar').addHandler('image', this.upaImagem.bind(this));
  


      


    }
    
    upload(arquivo, pasta = false) {
        
         if(document.getElementById("cardSalvar")){
             pasta = document.getElementById("cardSalvar").dataset.modulo
         }else{
             pasta = "imagens"
         }
         
         
       
            const formData = new FormData();
            formData.append("filepond", arquivo);
            formData.append("pasta", pasta)
            
            const xhr = new XMLHttpRequest();

            xhr.open("POST", `${dominio}/admin/processador.php?modulo=${pasta}`, true);


    xhr.onreadystatechange = ()=> {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          var obj = JSON.parse(xhr.responseText)

           const imageUrl = `${dominioAdress}/conteudo/uploads/${obj.nomeArquivo}`;
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
    
    set(value) {
        this.quill.root.innerHTML = value;
    }


    get() {
        return this.quill.root.innerHTML;
    }

    render() {
        return this.quill;
    }
}

class BoxSeo{
    constructor(container, pai){
        this.container = container;
        this.pai = pai
        this.corpo = this.container.getElementsByClassName("card-body")[0];
        this.corpo.classList.add("d-flex", "flex-column", "justify-content-start", "gap-2")
        this.footer = this.container.getElementsByClassName("card-footer")[0]
        this.recomendacao = this.recomendacao.bind(this)
    }
    
    calc(){
        var palavra  = this.inputPalavra;
        var titulo = this.inputTitle
        var descricao = this.inputDescription
        
        this.lista.innerHTML = ""
        
        if(palavra.value){
            this.recomendacao(true, "Foi definida uma palavra-chave para a página");
            
            if(titulo.value && titulo.value.toLowerCase().includes(palavra.value.toLowerCase())){
                 this.recomendacao(true, "A Palavra-Chave aparece no Meta Title");
            }else{
                 this.recomendacao(false, "A Palavra-Chave precisa aparecer no Meta Title");
            }
            
            if(descricao.value && descricao.value.toLowerCase().includes(palavra.value.toLowerCase())){
                 this.recomendacao(true, "A Palavra-Chave aparece na Meta Descrição");
            }else{
                 this.recomendacao(false, "A Palavra-Chave precisa aparecer na Meta Descrição");
            }
        }else{
            this.recomendacao(false, "É necessario definir uma palavra-chave para a página");
        }
        
        if(titulo.value){
            if(titulo.value.length < 50){
                var calc = 50 - titulo.value.length
                this.recomendacao(false, `Meta Title pequeno , com  ${titulo.value.length} caracteres, adicione mais ${calc}.`);
                var cor = "red"
            }else if(titulo.value.length > 70){
                 var calc = titulo.value.length - 70
                  this.recomendacao(false, `Meta Title grande, com  ${titulo.value.length} caracteres, remova ${calc}`);
                  var cor = "red";
            }else{
                this.recomendacao(true, "Meta Title válido , contém entre 50 e 70 caracteres.");
                var cor = "green"
            }
            
            var calctitulo = 100 / 70 * titulo.value.length
            this.barra1.style = `width: ${parseInt(calctitulo)}%; background-color: ${cor}`;
            
        }else{
            this.recomendacao(false, "É necessario definir um Meta Title para a página");
            this.barra1.style = "width: 0px; background-color: red";
        }
        
        if(descricao.value){
             if(descricao.value.length < 130){
                var calc = 130 - descricao.value.length
                this.recomendacao(false, `Meta Description pequeno , com  ${descricao.value.length} caracteres, adicione mais ${calc}.`);
                  var cor2 = "red"
            }else if(descricao.value.length > 150){
                 var calc = titulo.value.length - 150
                  this.recomendacao(false, `Meta Description grande, com  ${descricao.value.length} caracteres, remova ${calc}`);
                    var cor2 = "red"
            }else{
                 this.recomendacao(true, "Meta Description válido, contém entre 130 e 150 caractees.");
                   var cor2 = "green"
            }
             var calcdescricao = 100 / 130 * descricao.value.length
            this.barra2.style = `width: ${parseInt(calcdescricao)}%; background-color: ${cor2}`;
        }else{
            this.recomendacao(false, "É necessario definir uma Meta Descrição para a página");
        }
        
    }
    
    recomendacao(aprove, mensagem){
        var li = document.createElement("LI")
        li.classList.add("list-group-item","d-flex","justify-content-start","align-items-center","gap-4")
        
        
        var icone = document.createElement("I")
        if(aprove){
            icone.classList.add("bi","bi-check-circle-fill","text-success")
        }else{
            icone.classList.add("bi","bi-x-circle-fill","text-danger")
        }
        
        var span = document.createElement("SPAN")
        span.classList.add("fs-14")
        span.innerText = mensagem
        li.appendChild(icone)
        li.appendChild(span)
        this.lista.appendChild(li)
    }
    
    render(){
  
        if(this.pai.memoria && this.pai.memoria.especiais.seo){
            var memoria = JSON.parse(this.pai.memoria.especiais.seo);
        }
        
        var grupo1 = document.createElement("DIV")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label")
        label.innerText = "Palavra Chave"
        
        var input = document.createElement("INPUT")
        input.classList.add("form-control")
        if(memoria && memoria.palavra){
            input.value = memoria.palavra
        }
        
        this.inputPalavra = input
        evento(this.inputPalavra, "input", this.calc.bind(this))
        
        grupo1.appendChild(label)
        grupo1.appendChild(input)
        
         var grupo2 = document.createElement("DIV")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label")
        label.innerText = "Meta Title"
        
        var input = document.createElement("INPUT")
        input.classList.add("form-control")
          if(memoria && memoria.titulo){
            input.value = memoria.titulo
        }
        
        this.inputTitle = input
        evento(this.inputTitle, "input", this.calc.bind(this))
        
        
        
        var progresso = document.createElement("DIV")
        progresso.classList.add("progress","rounded-0")
        
        progresso.setAttribute("role", "progressbar")
        progresso.setAttribute("aria-label", "Meta Title"); 
        progresso.setAttribute("aria-valuenow","0")
        progresso.setAttribute("aria-valuemin","0")
        progresso.setAttribute("aria-valuemax", "100") 
        progresso.style.height = "5px"
        
        var barra = document.createElement("DIV")
        barra.classList.add("progresss-bar")
        progresso.appendChild(barra)
        
        this.barra1 = barra;
        
        grupo2.appendChild(label)
        grupo2.appendChild(input)
        grupo2.appendChild(progresso)
        
        
        var grupo3 = document.createElement("DIV")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label")
        label.innerText = "Meta Descrição"
        
        var input = document.createElement("TEXTAREA")
        input.classList.add("form-control")
        if(memoria && memoria.descricao){
            input.value = memoria.descricao
        }
        
        this.inputDescription = input
        evento(this.inputDescription, "input", this.calc.bind(this))
        

        var progresso = document.createElement("DIV")
        progresso.classList.add("progress","rounded-0")
        
        progresso.setAttribute("role", "progressbar")
        progresso.setAttribute("aria-label", "Meta Title"); 
        progresso.setAttribute("aria-valuenow","0")
        progresso.setAttribute("aria-valuemin","0")
        progresso.setAttribute("aria-valuemax", "100") 
        progresso.style.height = "5px"
        
        var barra = document.createElement("DIV")
        barra.classList.add("progresss-bar")
        progresso.appendChild(barra)
        
        this.barra2 = barra;
        
        grupo3.appendChild(label)
        grupo3.appendChild(input)
        grupo3.appendChild(progresso)
        
        
    
     
        
        this.corpo.appendChild(grupo1)
        this.corpo.appendChild(grupo2)
        this.corpo.appendChild(grupo3)


        this.lista = document.createElement("UL")
        this.lista.classList.add("list-group","list-group-flush","w-100")
        this.footer.appendChild(this.lista)
        
        if(memoria){
            this.calc.bind(this)()
        }
    }
    
    get(){
        if(!this.inputPalavra.value && !this.inputTitle.value && !this.inputDescription.value){
            return false;
        }
        
        return {
            palavra : this.inputPalavra.value,
            titulo : this.inputTitle.value,
            descricao : this.inputDescription.value
        }
    }
}

class BoxCategoria{
    constructor(container, categoria = true, pai){
        this.pai = pai
        this.tipo = pai.brain.identificador;
        
        
        this.container = container;
        this.corpo = this.container.getElementsByClassName("card-body")[0];
        this.container.getElementsByClassName("card-footer")[0].remove();
        this.cat = categoria
        
        this.ajax = this.ajax.bind(this)
        
        
        
    }
    
    request(){
        var data = new FormData();
        data.append("acao", "listar")
        this.ajax(data, this.listar.bind(this))
    }
    
     adicionado(r) {
         var texto = r.texto
         var id = r.id
         this.select.querySelector(`option[value="${texto}"]`).value = id;

        this.selectElement.trigger('change');
        
       
       /*
        if(this.cat){
            this.selectElement.val(r.id); 
        }else{
            var array = this.get()
            array.push(r.id)
            this.selectElement.val(array); 
        }
        */
        
        
        
    }

    
    adicionaNovo(texto){
       var data = new FormData();
       data.append("acao", "novo")
       data.append("texto", texto);
       this.ajax(data, this.adicionado.bind(this))
    }
 
    render(){
       this.id = geraId();
       var select = document.createElement("SELECT")
       this.select = select;
       select.id = this.id
       select.classList.add("form-control")
       this.corpo.appendChild(select)
       
       if(!this.cat){
           select.setAttribute("multiple", "")
       }
       
       

       

    }
    
    get() {
    var selectElement = document.getElementById(this.id);
    if (this.cat) { 
        return selectElement.value;
    } else { 
        return Array.from(selectElement.selectedOptions).map(option => option.value);
    }
}

    setCat(valor){
        this.selectElement.val(valor).trigger('change');

    }

    listar(r){
    var lista = r.lista;
    var i = 0;
    
    if(this.cat){
        var option = document.createElement("OPTION")
        option.value = "0"
        option.innerText = "Selecione uma categoria"
        this.select.appendChild(option)
    }
    
    
    while(i < lista.length){
        var item = lista[i]
        var option = document.createElement("OPTION")
        option.value = item.id
        option.innerText = item.text
        this.select.appendChild(option)
        
        
        i++;
    }
   

    this.selectElement = $(`#${this.id}`);
    nownFiles.add([`${dominioscript}/assets/aplicativo/select2/min.js`, `${dominioscript}/assets/aplicativo/select2/min.css`]).then(()=>{
            this.selectElement.select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: {
                id: '-1',
                text: this.cat ? "Selecione a Categoria" : "Selecione as Tags"
            },
            allowClear: true,
            createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        }
        }).on("select2:select", (e) => {
            if (e.params.data.newOption) {
                this.adicionaNovo(e.params.data.text);
            }
        });
    })

    
    
    if(this.pai.memoria && this.pai.memoria.especiais){
        if(this.cat){
            if(this.pai.memoria.especiais.categoria){
               this.selectElement.val(this.pai.memoria.especiais.categoria); 
               this.selectElement.trigger('change'); 
            }
        }else{
             if(this.pai.memoria.especiais.tags){
                this.selectElement.val(JSON.parse(this.pai.memoria.especiais.tags)); 
                this.selectElement.trigger('change');
             }
        }
     
    }
   
}

    
    ajax(data, cb = false){
        data.append("categoria", this.cat)
        data.append("tipo", this.tipo)
         let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(obj.sucesso){
                    if(cb){
                        cb(obj)
                    }else{
                        console.log(obj)
                    }
                }else{

                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/categoria.php`);
        request.send(data)
    }
    

}

class PrecificadorBox{
    constructor(item, container){
        this.infos = item.infos && item.infos.basico ? item.infos.basico : false
        
        
        var marketplace = item.infos?.marketplace || {};
        if(marketplace["ativar-marketplace"]){
            this.marketplace = marketplace;
        }else{
            this.marketpalce = false;
        }
      //  this.marketplace = false
      
        
        this.item = this.item.bind(this)
        this.input = this.input.bind(this)
        this.select = this.select.bind(this)
        this.opt = this.opt.bind(this)
        
        this.container = container.getElementsByClassName("card-body")[0]
        this.container.classList.add("p-0")
             
        container.getElementsByClassName("card-footer")[0].remove();
    }
    
    opt(texto, valor, select){
        var option = document.createElement("OPTION")
        option.value = valor
        option.text = texto
        select.appendChild(option)
        
    }
    
    input(nome, chave, mascara = false){
        var div = document.createElement("DIV")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "fs-12")
        label.innerText = nome
        
        this[chave] = document.createElement("INPUT")
        this[chave].classList.add("form-control")
        
        if(mascara){
            this[chave].classList.add("mascaraInput")
            this[chave].dataset.mascara = mascara
        }
        
        div.appendChild(label)
        div.appendChild(this[chave])
        return div;
        }
        
    select(nome, chave){
        var div = document.createElement("DIV")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "fs-12")
        label.innerText = nome
        
        this[chave] = document.createElement("SELECT")
        this[chave].classList.add("form-select")
        
        div.appendChild(label)
        div.appendChild(this[chave])
        return div;
        }
    
    item(titulo, icon){
        var id = geraId();
        const accordionItem = document.createElement('div');
        
        accordionItem.classList.add('accordion-item', "border-top-0", "border-start-0" , "border-end-0", "rounded-0", "animate__animated","animate__fadeInLeft");
        const accordionHeader = document.createElement('h2');
        accordionHeader.classList.add('accordion-header');
        
        const button = document.createElement('button');
        button.classList.add('accordion-button');
        button.setAttribute('type', 'button');
        button.setAttribute('data-bs-toggle', 'collapse');
        button.setAttribute('data-bs-target', `#${id}`);
        button.setAttribute('aria-expanded', 'true');
        button.setAttribute('aria-controls', id);
        
        var div = document.createElement("DIV")
        div.classList.add("d-flex","justify-content-start","align-items-center","gap-4", "fs-12")
        
        var icone = document.createElement("I")
        icone.className = icon
        icone.classList.add("fs-12")

     
        
        var span = document.createElement("SPAN") 
        span.classList.add("fs-12", "text-uppercase")
        span.textContent = titulo;
        
        div.appendChild(icone)
        div.appendChild(span)
        
        button.appendChild(div)
        
        accordionHeader.appendChild(button);
        const accordionBody = document.createElement('div');
        accordionBody.id = id;
        accordionBody.classList.add('accordion-collapse', 'collapse', 'show');
        accordionBody.setAttribute('data-bs-parent', `#boxPreco`);
        
        const accordionContent = document.createElement('div');
        accordionContent.classList.add('accordion-body', "d-flex", "flex-column", "gap-3");

        
        accordionBody.appendChild(accordionContent);
        accordionItem.appendChild(accordionHeader);
        accordionItem.appendChild(accordionBody);
        
        if(this.pai.getElementsByClassName("accordion-item").length > 0){
            button.classList.add("collapsed")
            accordionBody.classList.remove("show")
        }
        
        this.pai.appendChild(accordionItem);
        return accordionContent;
    }
    
    controle(){
        if(this.controleEstoque.checked){
            this.divControle.classList.remove("d-none")
        }else{
            this.divControle.classList.add("d-none")
        }
    }
    
    render(){
        var div = document.createElement("DIV")
        this.pai = document.createElement("DIV")
        this.pai.classList.add("accordion", "border-0", "rounded-0", "accordion-flush")
        this.pai.setAttribute("id", "boxPreco")
        
        
        
       var geral = this.item("Geral", "bi bi-cash");
       
       var preco = this.input("Preço", "inputPreco", 12);

      
       geral.appendChild(preco)
       
       
       if(false){
              var marketplace = this.item("Marketplace", "bi bi-shop");
              
              var request = new Request(`${dominio}/conteudo/modulos/marketplace/admins/regras.php`);
              request.addData({
                  acao: "listar",
                  arquivo: this.marketplace.arquivo,
                  chave: this.marketplace.chave,
                  modulo: this.marketplace.modulo,
                  regra: this.marketplace.regra,
              })
              request.send().then((r)=>{
              
                  var lista = r.lista;
                  if(Object.keys(lista).length > 0){
                      var tamanho = Object.keys(lista).length;
                      var conta = this.select("Selecione uma conta", "inputContaMarketplace");
                          marketplace.appendChild(conta);
                          for(let c in lista){
                              var option = document.createElement("OPTION")
                              option.value = c
                              option.innerText = lista[c]
                              conta.getElementsByClassName("form-select")[0].appendChild(option)
                          }
                          
                          if(this.loja){
                              conta.getElementsByClassName("form-select")[0].value = this.loja
                          }
                          
                          evento(conta.getElementsByClassName("form-select")[0], "input", ()=>{
                              this.loja = conta.getElementsByClassName("form-select")[0].value
                          })
                          
                          if(tamanho == 1){
                              conta.getElementsByClassName("form-select")[0].setAttribute("disabled")
                          }
                      
                  }else{
                      Swal.fire({
                          title: "Atenção",
                          text: "Para criar essa item, você precisa ter uma conta de vendedor.",
                          icon: "error"
                          
                      });
                      naoExiste();
                  }
              }, (r)=>{
                   Swal.fire({
                          title: "Atenção",
                          text: r.mensagem,
                          icon: "error"
                          
                      });
                   naoExiste();
              })
       }else{
           this.loja = 0;
       }
       
       
       if(this.infos["ativar-sku-controler"]){
           geral.appendChild(this.input("SKU", "inputSKU"))
       }
       
       if(this.infos["limitador-de-venda"]){
           var select = this.select("Limitar uma venda por compra", "inputLimite");
           
           var options = {
               "Desativado": 0,
               "Ativado": 1,

           }
           
           for(let c in options){
               this.opt(c, options[c], select.getElementsByClassName("form-select")[0]);
           }
           geral.appendChild(select)
       }
       
       if(this.infos["zera-o-carrinho"]){
            var select = this.select("Zerar o carrinho ao ser adicionado", "inputZerar");
            this.selectZera = select.getElementsByClassName("form-select")[0];
           var options = {
               "Desativado": 0,
               "Ativado": 1,

           }
           
           for(let c in options){
               this.opt(c, options[c], select.getElementsByClassName("form-select")[0]);
           }
           geral.appendChild(select)
       }
       
       if(this.infos.recorrente){
           
            this.inputZerar.value = 1
            this.inputZerar.setAttribute("disabled", "")
            
            this.inputLimite.value = 1
            this.inputLimite.setAttribute("disabled", "")
           

           this.selectZera.value = 1;
           
           var ciclo = this.item("Ciclos", "forward_circle");
           
           var input = this.input("Cobrar ", "inputVezes")
           input.getElementsByClassName("form-control")[0].type = "number"
           input.getElementsByClassName("form-control")[0].min = 1
           input.getElementsByClassName("form-control")[0].max = 4
           input.getElementsByClassName("form-control")[0].value = 1
           ciclo.appendChild(input)
           
           var select = this.select("vez a cada", "inputCiclo");
           
           var options = {
               "Mês": "mes",
               "Ano": "ano"
           }
           
           for(let c in options){
               this.opt(c, options[c], select.getElementsByClassName("form-select")[0]);
           }
           
           ciclo.appendChild(select)
           
           
      
           
           var select = this.select("por", "inputQuantidade")
           this.opt("Enquanto estiver ativo" , 0 , select.getElementsByClassName("form-select")[0]);
           
            var i = 2;
            while(i < 61){
                this.opt(`${i} vezes` , i , select.getElementsByClassName("form-select")[0]);
                i++;
            }
           

           ciclo.appendChild(select)
           
           
           var select = this.select("Primeira Cobrança", "inputPrimeiraCobranca")
           this.inputPrimeiraCobranca = select.getElementsByClassName("form-select")[0]
           ciclo.appendChild(select)
           
           var select = this.select("Desativar Após", "inputVencimento")
           this.inputVecimentoPlano = select.getElementsByClassName("form-select")[0]
           ciclo.appendChild(select)
           
            var options = {
               "Imeiadamente": "0",
               "7 dias": "7",
               "10 dias": "10",
               "15 dias": "15",
               "21 dias": "21",
               "30 dias": "30"
           }
           
             
           for(let c in options){
                   this.opt(c, options[c], this.inputPrimeiraCobranca);
                   this.opt(c, options[c], this.inputVecimentoPlano);
           }
           
           
           
            var ciclo = this.item("Função de Usuário", "forward_circle");
            
            var select = this.select("Mudar Função de Usuário", "inputFuncao");
            this.inputFuncao = select.getElementsByClassName("form-select")[0]
            evento(this.inputFuncao, "input", this.mudaFuncao.bind(this))
             var options = {
               "Desativado": "0",
               "Ativado": "1"
           }
           
           for(let c in options){
             
                   this.opt(c, options[c], this.inputFuncao);
   
               
           }
            
            
            
            ciclo.appendChild(select)
            
            
            var select = this.select("Função ao Associar", "funcaoAssociacao");
            this.funcaoAssociacao = select.getElementsByClassName("form-select")[0];
            select.classList.add("d-none")
        
            ciclo.appendChild(select)
             
            var select = this.select("Função ao Vencer", "funcaoVencer");
            select.classList.add("d-none")
            this.funcaoVencer = select.getElementsByClassName("form-select")[0];
            ciclo.appendChild(select)
            
            
            let request = new Request("/admin/opcoes.php");
            request.addData({
                "tipo": "3",
                "foco": '{"modulo":"usuarios","formulario":"pZ5qfBZdIkw0Xsy.json","regra":"1"}'
            })
            request.send().then((r)=>{
            
                var lista = r.lista
                
                for(let c in lista){
                      if(lista[c] > 1){
                            this.opt(c, lista[c], this.funcaoAssociacao);
                            this.opt(c, lista[c], this.funcaoVencer);
                      }
                }
                
                if(this.objValues){
                    var aoComeco = parseInt(this.objValues.metas?.aoassinar || 0);
                    var aoFim = parseInt(this.objValues.metas?.aovencer || 0); 
                }

       
                if(aoComeco){
                    this.funcaoAssociacao.value = aoComeco;
                }
                
                if(aoFim){
                    this.funcaoVencer.value = aoFim;
                }
            })

       }
       
       if(this.infos["gestao-de-estoque"]){
            var estoque = this.item("Estoque", "bi bi-boxes");
            
            var swi = document.createElement("DIV")
            swi.classList.add("form-check","form-switch")
            
            var idSwi = geraId();
            
            this.controleEstoque = document.createElement("INPUT")
            this.controleEstoque.type = "checkbox"
            this.controleEstoque.classList.add("form-check-input")
            this.controleEstoque.role = "switch"
            this.controleEstoque.id = idSwi
            evento(this.controleEstoque, "INPUT", this.controle.bind(this))
            
            var label = document.createElement("LABEL")
            label.classList.add("form-check-label")
            label.for = idSwi
            label.innerText = "Controlar Estoque"
            
            swi.appendChild(this.controleEstoque)
            swi.appendChild(label)
            estoque.appendChild(swi)
            
            this.divControle = this.input("Disponíveis", "inputDisponiveis")
            this.divControle.classList.add("d-none")
            estoque.appendChild(this.divControle); 
            
            
       }
       
       if(this.infos["tipo-de-produto"] > 0){
            var entrega = this.item("Entrega", "bi bi-truck"); 
            entrega.appendChild(this.input("Peso ( Em gramas )", "inputPeso"))
            entrega.appendChild(this.input("Comprimento ( em centimetros )", "inputComprimento"))
            entrega.appendChild(this.input("Largura ( em centimetros )", "inputLargura"))
            entrega.appendChild(this.input("Altura ( em centimetros )", "inputAltura")) 
       }
       
       if(this.infos["sistema-de-pontos"]){
           var pontos = this.item("Pontos", "bi bi-1-square-fill"); 
           
           
            var swi = document.createElement("DIV")
            swi.classList.add("form-check","form-switch")
            
            var idSwi = geraId();
            
            this.controlePontosInput = document.createElement("INPUT")
            this.controlePontosInput.type = "checkbox"
            this.controlePontosInput.classList.add("form-check-input")
            this.controlePontosInput.role = "switch"
            this.controlePontosInput.id = idSwi
            evento(this.controlePontosInput, "INPUT", this.controlePontos.bind(this))
            
            var label = document.createElement("LABEL")
            label.classList.add("form-check-label")
            label.for = idSwi
            label.innerText = "Dar Pontos"
            
            swi.appendChild(this.controlePontosInput)
            swi.appendChild(label)
            pontos.appendChild(swi)
           
           
           var input = this.input("Quantidade de Pontos", "inputPontos");
           input.classList.add("d-none")
           input.getElementsByClassName("form-control")[0].type = "number"
           input.getElementsByClassName("form-control")[0].value = 1
           input.getElementsByClassName("form-control")[0].min = 1
           input.getElementsByClassName("form-control")[0].step = 1
           pontos.appendChild(input);
           this.inputPontos = input.getElementsByClassName("form-control")[0]
           
           
           var input = this.input("Expiram após quantos dias", "inputExpiracao");
           input.classList.add("d-none")
           input.getElementsByClassName("form-control")[0].type = "number"
           input.getElementsByClassName("form-control")[0].value = 0
           input.getElementsByClassName("form-control")[0].min = 0
           input.getElementsByClassName("form-control")[0].step = 1
           this.inputExpira = input.getElementsByClassName("form-control")[0]
             
           var p = document.createElement("P")
           p.innerText = "'0' = Pontos nunca irão expirar"
           p.classList.add("fs-12", "m-0")
           input.appendChild(p)
           pontos.appendChild(input);
           
           
           var select = this.select("Tipo de Pontos", "inputPontosTipo");
           select.classList.add("d-none")
           
           var options = {
               "Sistema": "0"

           }
           
           this.inputPontosTipo = select.getElementsByClassName("form-select")[0];
           for(let c in options){
               this.opt(c, options[c], select.getElementsByClassName("form-select")[0]);
           }
           
           let request = new Request(`${dominio}/admin/opcoes.php`);
           request.addData({
                tipo: 3,
                foco: JSON.stringify({"modulo":"pagamento","formulario":"18mQ9mjt3eo1wfMPfcJMeidBjIhWu5rQ.json","regra":"1"})
           })
           request.send().then((r)=>{
                var lista = r.lista;
                var i = 0;
                for(let c in lista){
                     this.opt(c, lista[c], this.inputPontosTipo);
                    
                    i++;
                }
               if(this.inputPontosTipo.dataset.valor){
                  this.inputPontosTipo.value = this.inputPontosTipo.dataset.valor
               }
                
           }, (r)=>{
               console.log()
           })
          
           
  
           pontos.appendChild(select)
           
     
        
           
         
           
    
       }
       
       if(dataModule("3e6f8bad39448653d293414876a887a9")){
           var fiscal = this.item("Fiscal", "bi bi-journal-medical");
           
           var input = this.input("NCM", "inputNcm");
           var small = document.createElement("SMALL")
           small.innerText = 'Nomenclatura Comum do Mercosul'
           small.classList.add("fs-12")
           input.appendChild(small)
            
           fiscal.appendChild(input);
           this.inputNcm = input.getElementsByClassName("form-control")[0]
           
           var input = this.input("CFOP", "inputCfop");
           var small = document.createElement("SMALL")
           small.innerText = 'Código Fiscal de Operações e Prestações'
           small.classList.add("fs-12")
           input.appendChild(small)
            
           fiscal.appendChild(input);
           this.inputCfop = input.getElementsByClassName("form-control")[0]
           
           
            var origem = this.select("Origem", "inputOrigem");
            this.selectOrigem = origem.getElementsByClassName("form-select")[0]
            var origens=  [
                {"codigo": 0,"descricao": "Nacional - Exceto as mercadorias com conteúdo de importação superior a 40%"},
                {"codigo": 1,"descricao": "Estrangeira - Importação direta, exceto a importação direta de países do Mercosul"},
                {"codigo": 2,"descricao": "Estrangeira - Adquirida no mercado interno"},
                {"codigo": 3,"descricao": "Nacional - Conteúdo de importação superior a 40%"},
                {"codigo": 4,"descricao": "Nacional - Produção conforme processos produtivos básicos (PPB)"},
                {"codigo": 5,"descricao": "Nacional - Conteúdo de importação inferior ou igual a 40%"},
                { "codigo": 6,"descricao": "Estrangeira - Importação direta de país do Mercosul"},
                {"codigo": 7,"descricao": "Estrangeira - Adquirida no mercado interno (Mercosul)"},
                {"codigo": 8,"descricao": "Nacional - Conteúdo de importação superior a 70%"}
            ];
            
            var i = 0;
            while(i < origens.length){
                  this.opt(origens[i].descricao, origens[i].codigo, this.selectOrigem);
                i++;
            }

            
            fiscal.appendChild(origem)
            
            
            
            
            var unidades =  [
                {"codigo": "UN","descricao": "Unidade"},
                {"codigo": "LT","descricao": "Litro"},
                {"codigo": "M","descricao": "Metro"},
                {"codigo": "CX","descricao": "Caixa"},
                {"codigo": "PC","descricao": "Peça"},
                {"codigo": "SC","descricao": "Saco"},
                {"codigo": "DZ","descricao": "Duzia"},
                {"codigo": "MT","descricao": "Metro"},
                {"codigo": "PCT","descricao": "Pacote"},
                {"codigo": "GL","descricao": "Galão"},
                {"codigo": "PR","descricao": "Par"}]
                
                var unidade = this.select("Unidade", "inputOrigem");
                this.selectUnidade = unidade.getElementsByClassName("form-select")[0];
                
                
                var i = 0;
            while(i < unidades.length){
                  this.opt(unidades[i].descricao, unidades[i].codigo, this.selectUnidade);
                i++;
            }


                fiscal.appendChild(unidade)
            
       }
       
       if(dataModule("30b9f4c606d0e34a25855acdc46d5ae8")){
           
           var credito = this.item("Créditos", "bi bi-coin");
           
           
           var swi = document.createElement("DIV")
            swi.classList.add("form-check","form-switch")
            
            var idSwi = geraId();
            
            this.creditos = document.createElement("INPUT")
            this.creditos.type = "checkbox"
            this.creditos.classList.add("form-check-input")
            this.creditos.role = "switch"
            this.creditos.id = idSwi
            
           
            
            var label = document.createElement("LABEL")
            label.classList.add("form-check-label")
            label.for = idSwi
            label.innerText = "Venda por Crédito"
            
            swi.appendChild(this.creditos)
            swi.appendChild(label)
            credito.appendChild(swi)
            
            
           
          
            var request = new Request(`${dominio}/conteudo/modulos/creditos/admins/api.php`);
            request.addData({acao: "modelos"})
            request.send().then((r)=>{
                var lista = r.lista
                if(lista.length == 0){
                    this.creditos.checked = false;
                    this.creditos.setAttribute("disabled", "");
                    var div = document.createElement("DIV")
                    div.innerText = "Para ativar venda por créditos cadastre um modelo de crédito";
                    div.classList.add("text-danger", "fs-12", "fw-700")
                    credito.appendChild(div)
                    return;
                }
                
                evento(this.creditos , "INPUT", this.controleCreditos.bind(this))
                 var modeloCredito = this.select("Modelo de Créditoss", "inputModeloCredito");
                
                modeloCredito.classList.add("d-none")
            this.modeloCredito = modeloCredito.getElementsByClassName("form-select")[0];
            credito.appendChild(modeloCredito);
            
            
            // this.modeloCredito
            console.log(lista)
            
            
            
     
           
           var input = this.input("Preço em Créditos", "inputPrecoCredito");
           input.classList.add("d-none")
           var small = document.createElement("SMALL")
           small.innerText = 'Preço do Item em Créditos'
           small.classList.add("fs-12")
           input.appendChild(small)
            
           credito.appendChild(input);
           this.precoCredito = input.getElementsByClassName("form-control")[0]
           this.precoCredito.type = "number"
           this.precoCredito.step = 1;
           this.precoCredito.value = 1;
           this.precoCredito.min = "1";
                
            })
           
           
           
           
           
           
           
       }
       
      
       div.appendChild(this.pai)
       this.container.appendChild(div)
    }
    
    controleCreditos(){
        if(this.creditos.checked){
            this.precoCredito.closest("div").classList.remove("d-none")
            this.modeloCredito.closest("div").classList.remove("d-none")
        }else{
            this.precoCredito.closest("div").classList.add("d-none")
            this.modeloCredito.closest("div").classList.add("d-none")
        }
    }
    
    controlePontos(){

        if(this.controlePontosInput.checked){
            this.inputPontos.closest("div").classList.remove("d-none")
            this.inputExpira.closest("div").classList.remove("d-none")
            this.inputPontosTipo.closest("div").classList.remove("d-none")
        }else{
            this.inputPontos.closest("div").classList.add("d-none")
            this.inputExpira.closest("div").classList.add("d-none")
            this.inputPontosTipo.closest("div").classList.add("d-none")
        }
    }
  
    mudaFuncao(){
        if(this.inputFuncao.value == "0"){
            this.funcaoAssociacao.closest("div").classList.add("d-none")
            this.funcaoVencer.closest("div").classList.add("d-none")
        }else{
           this.funcaoAssociacao.closest("div").classList.remove("d-none")
           this.funcaoVencer.closest("div").classList.remove("d-none") 
        }


    }
    
    get(){
        
        var qntEstoque = 0;
        if(this.inputDisponiveis && this.inputDisponiveis.value){
            qntEstoque = parseInt(this.inputDisponiveis.value);
        }
        
       
        var obj = {
            preco: this.inputPreco.value,
            tipo: this.inputComprimento ? 2 : 1,
            recorrente: this.inputCiclo ? 1 : 0,
            estoque: !this.controleEstoque || !this.controleEstoque.checked ? 0 : 1,
            limite: this.inputLimite ? parseInt(this.inputLimite.value) : 0,
            zerar: this.inputZerar ? parseInt(this.inputZerar.value) : 0,
            pontos: this.controlePontosInput && this.controlePontosInput.checked ? 1 : 0,
            metas: {
                estoque: qntEstoque,
                sku: this.inputSKU ? this.inputSKU.value : false,
                altura: this.inputAltura ? parseInt(this.inputAltura.value) : false,
                comprimento: this.inputComprimento ? parseInt(this.inputComprimento.value) : false,
                largura : this.inputLargura ? parseInt(this.inputLargura.value) : false,
                peso: this.inputPeso ? parseInt(this.inputPeso.value) : false,
                cobrar: this.inputVezes && this.inputVezes.value ? this.inputVezes.value : 0,
                ciclo: this.inputCiclo && this.inputCiclo.value ? this.inputCiclo.value : 0,
                vezes: this.inputQuantidade && this.inputQuantidade.value ? this.inputQuantidade.value : 0,
                funcao: this.inputFuncao && this.inputFuncao.value == 1 ? "1" : "0",
                aoassinar: this.funcaoAssociacao ? this.funcaoAssociacao.value : "0",
                aovencer: this.funcaoVencer ? this.funcaoVencer.value : "0"
            },
            loja: this.loja
            
        }
        
        if(this.infos["sistema-de-pontos"] && this.controlePontosInput.checked){
            obj.metas.pontos = this.inputPontos.value || 1;
            obj.metas.pontosExpiracao = this.infos["sistema-de-pontos"] ? this.inputExpira.value || 0 : 0
            obj.metas.pontosTipo = this.inputPontosTipo.value
        }
        
        if(dataModule("3e6f8bad39448653d293414876a887a9")){
            obj.metas.ncm = this.inputNcm.value
            obj.metas.cfop = this.inputCfop.value
            obj.metas.origemfiscal = this.selectOrigem.value
            obj.metas.unidadefiscal = this.selectUnidade.value
        }
   
        return obj;
  
    }
    
    set(obj){
     
        if(obj.preco){
            this.inputPreco.value = obj.preco
        }
        
        this.loja = obj.loja

                
        var map = {
            altura: "inputAltura",
            comprimento: "inputComprimento",
            estoque: "inputDisponiveis",
            largura: "inputLargura",
            peso: "inputPeso",
            sku: "inputSKU",
            cobrar: "inputVezes",
            ciclo: "inputCiclo",
            vezes: "inputQuantidade",
            cfop: "inputCfop",
            ncm: "inputNcm",
            origemfiscal: "selectOrigem",
            unidadefiscal: "selectUnidade",

        }
        
        

        
        if(this.controleEstoque && obj.estoque == "1"){
            this.controleEstoque.checked = true;
            this.controle.bind(this)()
        }
        
        if(obj.limitador && this.inputLimite){
            this.inputLimite.value = obj.limitador
        }
        
         if(obj.limitador && this.inputLimite){
            this.inputLimite.value = obj.limitador
        }
        
        if(obj.zerar && this.inputZerar){
            this.inputZerar.value = obj.zerar
        }
        

        for(let c in obj.metas){

            if(map[c] && this[map[c]]){
                this[map[c]].value = obj.metas[c]
            }
        }
        
        if(this.infos.recorrente){
            this.inputZerar.value = 1
            this.inputZerar.setAttribute("disabled", "")
            
            this.inputLimite.value = 1
            this.inputLimite.setAttribute("disabled", "")
            

            if(obj.metas.funcao == "1"){
                this.inputFuncao.value = 1;
                this.mudaFuncao.bind(this)();
                
                this.objValues = obj;
                
                
                var aoComeco = parseInt(this.objValues.metas?.aoassinar || 0);
                var aoFim = parseInt(this.objValues.metas?.aovencer || 0);
                
                
                if(aoComeco){
                    this.funcaoAssociacao.value = aoComeco;
                }
                
                if(aoFim){
                    this.funcaoVencer.value = aoFim;
                }

                
            }
            
            /*
             if(this.inputFuncao.value == "0"){
            this.funcaoAssociacao.closest("div").classList.add("d-none")
            this.funcaoVencer.closest("div").classList.add("d-none")
        }else{
           this.funcaoAssociacao.closest("div").classList.remove("d-none")
           this.funcaoVencer.closest("div").classList.remove("d-none") 
        }
        */

            

        }
        
        if(this.infos["sistema-de-pontos"] && obj.pontos === "1"){
            this.controlePontosInput.checked = true;
            this.controlePontos.bind(this)();
            
            
            this.inputPontos.value = obj.metas?.pontos || 1;
            this.inputExpira.value = obj.metas?.pontosExpiracao || 0;
            this.inputPontosTipo.value = obj.metas?.pontosTipo || 0
            this.inputPontosTipo.dataset.valor = obj.metas?.pontosTipo || 0
            
        }
        
        
        


    }
  
}

class LacoRepetidor{
    constructor(pai, container, filhos){
        this.pai = pai;
        this.container= container;
        this.filhos = filhos
        this.myId = false;
         this.itens = {};
         this.files = {};
    }
    
    deleta(){
     delete this.container.lacos[this.myId]
     remover(this.item);
    }
    
    render(id){
        this.myId = id;
        var id = geraId();
        
        const accordionItem = document.createElement('div');
        accordionItem.classList.add("itemRepetidor")

        
      
      
    
        
        const body = document.createElement('div');
        body.classList.add('w-100', 'd-flex', 'flex-column', "gap-4");
        
        
        var i = 0;
        while(i < this.filhos.length){
            var item = this.filhos[i]
            var input = new InputRenderizado(item, item.hash, this);
            body.appendChild(input.render())
            i++;
        }
        
        
        const mover = document.createElement('button');
        mover.classList.add("btn-move", "btn", "btn-primary", "d-flex", "justify-content-center", "align-items-center", "btn-sm", "gap-2", "fs-12");
        mover.setAttribute('type', 'button');
        mover.innerHTML = `<i class="bi bi-hand-index"></i> Mover`
        
        
        var trash = document.createElement("BUTTON")
        trash.classList.add("btn-delete", "btn", "btn-danger", "d-flex", "justify-content-center", "align-items-center", "btn-sm")
        trash.innerHTML = `<i class="bi bi-trash"></i> Deleta`
        trash.setAttribute('type', 'button');
        evento(trash, "click", this.deleta.bind(this))
        
        var div = document.createElement("DIV")
        div.classList.add("d-flex", "btnsRepetidor", "justify-content-between", "mt-2")
        div.appendChild(mover)
        div.appendChild(trash)
        

        
        //accordionItem.appendChild(mover)
        accordionItem.appendChild(body)
        accordionItem.appendChild(div)
  
  
        
   
        this.item = accordionItem
        this.item.classList.add("animate__animated","animate__fadeInUp")
        this.pai.classList.add("overflow-hidden")
        this.pai.appendChild(accordionItem);

    }
    
    add(item){
        this.itens[item.item.hash] = item
    }
    
    addFiles(item){
        this.files[item.item.hash] = item
      
    }

    allItems(item){

    }
    
    pega(){
      
        var data = {};
        for(let i in this.itens){
            var item = this.itens[i]
            data[item.item.hash] = item.input.value;
        }
        
         for(let chave in this.files){
            data[chave] = JSON.stringify(this.files[chave].destaque.get())
        }
        
        
        
        console.log(data)
        return data
    }

    set(obj){
        
        if(Object.keys(this.itens).length == 0){
            return;
        }
      
        for(let c in obj){
          
            if(obj[c] && this.itens[c]){
                var input = this.itens[c].input;
        
                if(obj[c]){
                
                switch(this.itens[c].item.tipo){
                      case 'select':
                          var valor = [];
                          if(input.multiple){
                              try{
                              var valor = JSON.parse(obj[c])
                          }catch(e){
                              var valor = [obj[c]];
                          }
                          
                   
                          
                    
                          var opc = input.options
                          var i = 0;
                          while(i < opc.length){
                              var o = opc[i]
                              if(valor){
                                   if(valor.includes(o.value)){
                                  o.selected = true;
                              }
                               
                              }
                             
                              i++;
                          }
 
                          }else{
                              input.value =  obj[c]
                          }
                          

                          
                           input.setAttribute("data-valor", obj[c])
                    break;
                    case 'file':
                   
                       
                        
                        break;
                    case 'switch':
                       
                        if(obj[c]){
                            if(obj[c] == "0"){
                                input.checked = false;
                            }else{
                                input.checked = true;
                            }
                            
                        }
                        break;
                    default:
             
                        input.value = obj[c]
                        break;
                }
                
                
               
            }
            }
            
            if(obj[c] && this.files[c]){

                 this.files[c].destaque.set(obj[c])
                
            }
            
            
            
            
 
        }
    }
  
}

class Repetidor{
    constructor(filhos, container, config = {}){
        this.container = container
        this.lacos = {}
        this.filhos = filhos
        
        this.itens = {};
        this.todos = {};
        this.config = config;
        
        
        var i = 0;
        while(i < this.filhos.length){
            this.contarFilhos(this.filhos[i])

            i++;
        }
        
 

    }
    
    contarFilhos(obj) {
        
    if(!obj){
        return;
    }

    var i = 0;
         this.container.allItems({"item": obj})
        while(i < obj.filhos.length){
            var filho = obj.filhos[i]
            this.container.allItems({"item": filho})

            this.contarFilhos(filho);
            i++;
        }
        

}
    
    set(r){
        
        console.log(r)
        var obj = JSON.parse(r)
     
        if(obj){
           var i  =0;
        while(i < obj.length){
                var id = geraId();
                this.lacos[id] = new  LacoRepetidor(this.lista, this, this.filhos);
                this.lacos[id].render(id);
                this.lacos[id].set(obj[i])
                this.cbAdd.bind(this)(this.lacos[id]);
            i++;
        }  
        }
        
       
    
    }
    
    getDivIndex(div) {
    // Verifica se o elemento passado é uma div
    if (!(div instanceof HTMLDivElement)) {
        throw new Error("O elemento fornecido não é uma div.");
    }

    // Obtém o pai do elemento
    const parent = div.parentNode;

    // Obtém todos os filhos do pai que são do mesmo tipo (divs)
    const children = Array.from(parent.children);
    
    // Encontra o índice da div dentro dos filhos
    return children.indexOf(div);
}
    
    get(){
        var data = [];
        
        

        for(let c in this.lacos){
            var laco = this.lacos[c]


            var obj = laco.pega()
            
 
            
            data[this.getDivIndex(laco.item)] = obj
        }
        
        return JSON.stringify(data);

    }
    
    
    cbAdd(item){
        if (this.config["on-add"] && this.config["on-add"].trim()) {
        let func = this.config["on-add"].trim();
        if (typeof window[func] === "function") {
            window[func](item);
        } else {
        console.error(`Function ${func} not found`);
        }
        }
    }
    
    novo(){
        var id = geraId();
        this.lacos[id] = new  LacoRepetidor(this.lista, this, this.filhos);
        this.lacos[id].render(id);
        
        this.cbAdd.bind(this)(this.lacos[id])
    }
    
    render(info){
        var div = document.createElement("DIV")
        div.classList.add("card", "border-0", "shadow-sm")
        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "d-flex", "justify-content-between", "align-items-center", "shadow-0", "border-0")
        
        var titulo = document.createElement("H2")
        titulo.classList.add("fs-16","text-uppercase","m-0","fw-700","text-contrast")
        
        if(info && info.basico && info.basico.label){
            titulo.innerText = info.basico.label
        }
        
        
        var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "btn-n-primaria", "d-flex", "justify-content-center", "align-items-center", "gap-2")
        botao.innerHTML =`<i class="bi bi-plus-circle"></i> <span class="d-none d-xl-block">Adicionar Novo</span>`
        evento(botao, "click", this.novo.bind(this))
        
        header.appendChild(titulo)
        header.appendChild(botao)
        
        var body = document.createElement("DIV")
        body.classList.add("card-body")
        
        this.lista = document.createElement("DIV")
        this.lista.classList.add("d-flex", "flex-column", "gap-3")
        this.lista.id = geraId();
        nownFiles.add("https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js").then(()=>{
            new Sortable(this.lista, {
    handle: '.btn-move', // handle's class
    animation: 150
});
        })
        
        
        body.appendChild(this.lista)
        
        
        div.appendChild(header)
        div.appendChild(body)
        
        
        return div;
    }
}

class InputIcone{
    constructor(){
        
    }
    
    render(){
        var div = document.createElement("DIV")
        div.classList.add("input-group")
        
        var button = document.createElement("BUTTON")
        button.classList.add("input-group-text")
        button.innerText = "Selecione o Icone"
        
        
        

        
        this.input  = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.input.setAttribute("readonly", "")
             
                
                div.appendChild(button)
                div.appendChild(this.input)
                return div;
    }
}

class TabAcao{
    constructor(input, item){
        this.itens = [];
        this.targets = [];
        this.input = input
        this.item = item
        
        setTimeout(()=>{
            this.render.bind(this)()
        }, 100)
    }
    
    seleciona(e){
        /*
        if(document.getElementById("progressButtonControl")){
            return;
        }
        */
        var i = 0;
        while(i < this.itens.length){
            this.itens[i].classList.remove("active")
            
            i++;
        }
        
        var i = 0;
        while(i < this.targets.length){
            if(document.getElementById(this.targets[i])){
                document.getElementById(this.targets[i]).classList.add("d-none")
            }
            
            
            i++;
        }
        
        var foco = e.currentTarget || e;
        foco.classList.add("active")
        
        if(foco.dataset.target){
            if(document.getElementById(foco.dataset.target)){
                
                document.getElementById(foco.dataset.target).classList.remove("d-none")
                
                if(this.primeiraInteracao){
                    foco.scrollIntoView({
  behavior: 'smooth',  // Adiciona uma rolagem suave
  block: 'start'       // Alinha o topo do elemento ao topo da janela de visualização
});
                }
                this.primeiraInteracao = true;
                

            }
        }
        
    }
    
    tab(btn){
        
        
        btn.id = btn.id.trim()
        var li = document.createElement("LI")
        li.classList.add("nav-item")
        
        var button = document.createElement("button")
        button.classList.add("nav-link", "d-flex", "gap-2", "align-items-center")
        
        switch(parseInt(this.item?.infos?.layout?.estilo || 0)){
            case 0:
                button.classList.add("contador")
                break;
            case 1:
                button.classList.add("texto")
                break;
            case 2:
                button.classList.add("somente-num")
                break;
        }
        
        
         if(this.item.infos?.layout?.vertical || false){
             button.classList.add("w-100")
        }
        
        
        if(btn.icone && btn.icone.trim()){
            var ic = document.createElement("I")
            ic.className = btn.icone
            button.appendChild(ic)
        }
        
        
        var span = document.createElement("SPAN")
        span.innerText = btn.texto
        
        button.appendChild(span)
        button.dataset.target = btn.id.trim();
        this.targets.push(btn.id)
        if(document.getElementById(btn.id)){
            document.getElementById(btn.id).classList.add("d-none")
        }
        
        
        evento(button, "click", this.seleciona.bind(this))
        this.itens.push(button)
        li.appendChild(button)
        this.ul.appendChild(li)
       
    }
    
    render(){
        this.ul = document.createElement("UL")
        this.ul.classList.add("nav","nav-tabs")
        this.input.classList.add("holder-nav-tabs")
        this.input.appendChild(this.ul)
        
        
        if(this.item.infos?.layout?.vertical || false){
            this.ul.classList.add("flex-column", "gap-3")
            this.ul.style = 'align-items:  stretch !important;'
        }
        
        
        if(this.item.infos && this.item.infos.botoes && this.item.infos.botoes.botoes){
            var botoes = this.item.infos.botoes.botoes
            var i = 0;
            this.tamanho = botoes.length;
            while(i < botoes.length){
                
                this.tab.bind(this)(botoes[i])
                i++;
            }
            
        }
        
        
        if(this.itens[0]){
            this.itens[0].click();
        }
    }
    
    proximo(){
        
    }
    
    anterior(){
        
    }
    
    reset(){
        this.seleciona.bind(this)(this.itens[0])
    }
}

class BtnsProgress{
    constructor(pai, tab){
        this.pai = pai;

        if(pai.container.tabs){
            pai.container.progressao = true;
            this.tabs = pai.container.tabs;
            this.tamanho = this.tabs?.item?.infos?.botoes?.botoes.length ?? 0;
            this.init.bind(this)()
        }else{
            this.initSimple.bind(this)()
        }
        
    }

    proximo(){
        this.start++;
        
        this.btnVoltar.classList.remove("d-none")
        console.log(this.tamanho, this.start)
        if(this.tamanho == this.start){
            this.btnSalvar.classList.remove("d-none");
            this.btnProximo.classList.add("d-none");
        }else{
            
        }
         this.calcBar.bind(this)()
    }
    
    salvar(){
        this.pai.container.salvar(false)
    }
    
    voltar(){
        this.start--;
        this.btnSalvar.classList.add("d-none");
        this.btnProximo.classList.remove("d-none");
        if(this.start == 1){
            this.btnVoltar.classList.add("d-none")
        }
        this.calcBar.bind(this)()

    }
    
    calcBar(){
        var etapa = this.start - 1;
        if(this.barra){
            var calc = parseInt((100 / this.tamanho) * etapa);
            this.progresso.setAttribute("aria-valuenow", calc)
            this.barra.style.width = `${calc}%`
            
        }
        this.tabs.seleciona(this.tabs.itens[etapa]);
        

    }
    
    isString(value) {
  return typeof value === 'string';
}

    initSimple(){
        
        var config = this.pai.infos;
        
        var esquerda = document.createElement("DIV")
        var direita = document.createElement("DIV")
        
        var pai = document.createElement("DIV")
        pai.classList.add("d-flex","justify-content-between","align-items-center")
        
        this.btnSalvar = document.createElement("BUTTON")
        this.btnSalvar.innerText = config?.salvar?.texto.trim() || "Salvar"
        this.btnSalvar.classList.add("btn", "btn-nown-style", `btn-n-primaria`)

        evento(this.btnSalvar, "click", this.salvar.bind(this))
        direita.appendChild(this.btnSalvar)
        
        pai.appendChild(esquerda)
        pai.appendChild(direita)
 
     
        this.pai.div.appendChild(pai)
    }
    
    init(){
    
        var config = this.pai.infos;
        
        if ((config?.basico?.["esconder-tabs"] || false)) {
            this.tabs.input.classList.add("d-none");

        }

        this.start = 1;
        
        var esquerda = document.createElement("DIV")
        var direita = document.createElement("DIV")
        
        var pai = document.createElement("DIV")
        pai.classList.add("d-flex","justify-content-between","align-items-center")
    

        this.btnVoltar = document.createElement("BUTTON")
        this.btnVoltar.classList.add("d-none", "btn", "btn-secondary", "btn-nown-style")

        
   
        
        this.btnVoltar.innerText =  config?.voltar?.texto || "Voltar"
        evento(this.btnVoltar, "click", this.voltar.bind(this))
        

        
        this.btnProximo = document.createElement("BUTTON")
        this.btnProximo.innerText = config?.proximo?.texto || "Próximo"
        this.btnProximo.classList.add("btn", `btn-n-primaria`,  "btn-nown-style")

        evento(this.btnProximo, "click", this.proximo.bind(this))
        
        this.btnSalvar = document.createElement("BUTTON")
        this.btnSalvar.innerText = config?.salvar?.texto.trim() || "Salvar"
        this.btnSalvar.classList.add("btn", "d-none", "btn-nown-style", `btn-n-primaria`)

        
        
        evento(this.btnSalvar, "click", this.salvar.bind(this))
        
        
        esquerda.appendChild(this.btnVoltar)
        
        direita.appendChild(this.btnProximo)
        direita.appendChild(this.btnSalvar)
        
  
        
        pai.appendChild(esquerda)
        pai.appendChild(direita)
        
        var p = config?.basico?.["barra-de-progresso"] || false;

        if(p){
        var progresso = document.createElement("DIV")
        progresso.classList.add("progress", "mb-4", "rounded-0", "he-10")
        progresso.setAttribute("role", "progressbar")
        progresso.setAttribute("aria-label", "Progresso Formulário")
        progresso.setAttribute("aria-valuenow","0")
        progresso.setAttribute("aria-valuemin","0")
        progresso.setAttribute("aria-valuemax","100")
        this.progresso = progresso;
        this.barra = document.createElement("DIV")
        this.barra.classList.add("progress-bar","bg-success")
        this.barra.style.width = "0%"
        progresso.appendChild(this.barra)
        this.pai.div.appendChild(progresso)
        }

        
      
        this.pai.div.appendChild(pai)
        
        setTimeout(()=>{
            this.desativa.bind(this)()
        }, 100)
     
    }
    
    desativa(){
    
        var btns = this.tabs.itens;
        var i = 0;
        while(i < btns.length){
            var btn = btns[i]
            console.log(btn.setAttribute("disabled", ""))
            
            i++;
        }
    }
    
    reset(){
        this.start = 1;
        this.btnVoltar.classList.add("d-none")
        this.btnSalvar.classList.add("d-none");
        this.btnProximo.classList.remove("d-none");
        this.calcBar.bind(this)()
    }
}

class AtribuidorLi{
    constructor(nome, id , grupo, idLi, pai){

        this.nome = nome;
        this.id = id;
        this.grupo = grupo;
        this.idLi = idLi;
        this.pai = pai;

    }
    
    
  
    get(descricao) {
        if (descricao) {
            return { [this.grupo]: this.id };
        } else {
            return this.id;
        }
    }


    
    marcou(){
        this.pai.marcou(event.currentTarget)
    }
    
    render(){
        var item = document.createElement("LI")
        item.classList.add("list-group-item")
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        
        
        var col = document.createElement("DIV");
        col.classList.add("col-1",'d-flex', 'align-items-center', "justify-content-center")
        
        var input = document.createElement("INPUT")
        input.dataset.id = this.idLi
        input.type = "checkbox"
        input.classList.add("selector")
        evento(input, "input", this.marcou.bind(this))
        col.appendChild(input)

        
        
        var col2 = document.createElement("DIV");
        col2.classList.add("col-1", 'd-flex', 'align-items-center', "justify-content-center")
        
        var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "btn-secondary")
        botao.innerHTML = `<i class="bi bi-hand-index"></i>`
        col2.appendChild(botao)
        
        var col3 = document.createElement("DIV");
        col3.classList.add("col-5")
        col3.innerText = this.nome
        
        var col4 = document.createElement("DIV");
        col4.classList.add("col-5")
        col4.innerText = this.grupo
        
        row.appendChild(col2)
        row.appendChild(col)
        row.appendChild(col3)
        row.appendChild(col4)
        
        item.appendChild(row)
        this.li = item;
        return item;
        
        
    }
}

class Atribuidor{
    constructor(pai, config){

        this.config = config;
        this.pai = pai;
        this.idModal = geraId();
        this.memoria = {};
    }
    
    get(){
        var lista = this.div.getElementsByClassName("listaBox")[0];
        var itens = lista.getElementsByClassName("selector");
        
        var i = 0;
        var selecionados = [];
        while(i < itens.length){

            selecionados.push(this.itens[itens[i].dataset.id].get(parseInt(this.pai.data("listagem","listar-de-multiplos", 0))));
            
            
            i++;
        }
 
        return JSON.stringify(selecionados);
        
    }
    
    item(){
        var li = document.createElement("LI")
        li.classList.add("list-group-item","py-2")
        
        li.innerHTML = `

                    <div class="row">
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <button class="btn btn-secondary"><i class="bi bi-hand-index"></i></button>
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-3">
                                    Nome teste
                                </div>
                                <div class="col-3">
                                    Nome teste
                                </div>
                                <div class="col-4">
                                    Nome teste
                                </div>
                                <div class="col-2">
                                    Nome teste
                                </div>
              
                                
                            </div>
                        </div>
                        <div class="col-1  d-flex align-items-center justify-content-center">
                            <button class="btn btn-secondary"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>

        `
        return li;
         
        
    }
    
    box(){
         let label =  this.pai.data("basico","label", false);
        

        var div = document.createElement("DIV")
        div.classList.add("card")
        div.innerHTML = `
         <div class="card-header">
             <h2 class="fs-20 m-0 fw-700">${label}</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <div class="position-relative">
            <input class="form-control ps-5">
            <span class="position-absolute top-50 translate-middle-y" style="margin-left:20px">
                <i class="bi bi-search"></i>
            </span>
        </div>
               
                </div>
                <div class="btnAcoes">

                    <button class="btn btn-nown-style btn-n-primaria" data-bs-toggle="modal" data-bs-target="#${this.idModal}">Adicionar Item</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush listaBox">
                
                
                <li class="list-group-item list-group-item-action active" >
                     <div class="row">
                        <div class="col-1">
                            Mover
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-3">
                                    Nome teste
                                </div>
                                <div class="col-3">
                                    Nome teste
                                </div>
                                <div class="col-4">
                                    Nome teste
                                </div>
                                <div class="col-2">
                                    Nome teste
                                </div>
              
                                
                            </div>
                        </div>
                        <div class="col-1">
                           Apagar
                        </div>
                    </div>

                </li>
         
                   
                    
                   

            </ul>
        </div>

        `
        
        if(this.pai.data("basico","agrupador", false)){
            var agrupador = document.createElement("BUTTON")
            agrupador.classList.add("btn","btn-nown-style","btn-n-secundaria")
            agrupador.innerText = "Novo Grupo"
            div.getElementsByClassName("btnAcoes")[0].appendChild(agrupador)
            
        
        }
        this.div = div;
    }
    
    modal(){
        var modal = document.createElement("DIV")
        modal.id = this.idModal;
        modal.setAttribute("tabindex","-1");
        modal.setAttribute("aria-hidden","true")
        modal.classList.add("modal", "fade")
        modal.innerHTML = `
        <div class="modal-dialog  modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Adicionar Itens</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <select class="form-control categorias">
                                <option>Selecione a opção</option>
                            </select>
                        </div>
                        <div>
                            <input class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush listaModal" data-lista="modal">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-nown-style btn-n-primaria btnAddLista" disabled>Adicionar Itens</button>
                </div>
            </div>
    </div>
        
        
        `
        
        this.modal = modal;
        this.btnAddLista = this.modal.getElementsByClassName("btnAddLista")[0]
        evento(this.btnAddLista, "click", this.seleciona.bind(this))
        document.getElementById("conteudo").appendChild(modal)
    }
    
    render(){
        this.modal.bind(this)();
        this.box.bind(this)();
       
       var estrutura = this.config.infos?.listagem?.estrutura || [];

        var i = 0;
        var totais = 0;
        while(i < estrutura.length){
            var item = estrutura[i]
            

            let request = new Request("/admin/opcoes.php");

            request.addData({
                tipo: 3,
                foco: JSON.stringify({
                    modulo: item.bd,
                    formulario: `${item.id}.json`,
                    regra: 1,
                }),
                grupo: item.dp,
                coluna: item.nm
            })
            request.send().then((r)=>{
                this.memoria[r.grupo] = r.lista
                totais++;
               
                if(totais == estrutura.length){
                    this.carregado.bind(this)();
                   
                }
            }, (r)=>{
                this.carregado.bind(this)();
                totais++;
            })
            
            
            i++;
        }

  
        
        return this.div;
        
    }
    
    opt(chave, valor){
        var option = document.createElement("OPTION")
        option.value = valor
        option.innerText = chave;
        return option;
    }
    
    marcou(item){
        var pai = item.closest(".list-group")

        if(pai.dataset.lista == "modal"){
            var fluxo = 1;
        }else{
           var fluxo = 2;
        }
        
        
        var itens = pai.getElementsByClassName("selector")
        var i = 0;
        var marcados = false;
        while(i < itens.length){
            if(itens[i].checked){
                marcados = true;
            }
            
            i++;
        }
         
    
         
    switch(fluxo){
        case 1:
            if(marcados){
                this.btnAddLista.removeAttribute("disabled")
            }else{
                this.btnAddLista.setAttribute("disabled", "")
            }
            break;
    }

    }
    
    seleciona(){
         var itens = this.modal.getElementsByClassName("listaModal")[0].getElementsByClassName("selector")
        var i = 0;
        var marcados = []
        while(i < itens.length){
            if(itens[i].checked){
               if(this.itens[itens[i].dataset.id]){
                   this.div.getElementsByClassName("listaBox")[0].appendChild(this.itens[itens[i].dataset.id].li)
                   continue;
               }
            }
            
            i++;
        }
        
        
        var itens = this.div.getElementsByClassName("listaBox")[0].getElementsByClassName("selector")
          var i = 0;

        while(i < itens.length){
           
           itens[i].checked = false;
           i++;
        }
         


    }
    
    carregado(){
        this.itens = {};
        var lista = this.modal.getElementsByClassName("listaModal")[0]
        var select = this.modal.getElementsByClassName("categorias")[0];
        let fragmento = document.createDocumentFragment();
        let fragmentoUm = document.createDocumentFragment();
        for(let c in this.memoria){
            fragmento.appendChild(this.opt(c, c))
            
            var listaitens = this.memoria[c]

            for(let d in listaitens){
                var id = geraId();
                this.itens[id] = new AtribuidorLi(d, listaitens[d], c, id, this);
                fragmentoUm.appendChild(this.itens[id].render())
            }
   

        }
        select.appendChild(fragmento)
        lista.appendChild(fragmentoUm);
        
    }
}

class AtributosControler{
    constructor(pai){
        this.pai = pai;
        this.config = pai?.infos || {}
        this.modulo = pai?.container?.brain?.modulo || false;
        this.banco = pai?.container?.brain?.identificador || false;
        this.editMode = pai?.container?.brain?.editMode || false;
        
        
        this.grupos = {}
        this.init.bind(this)();
    }
     
    init(){
      
        var request = new Request(`${dominio}/conteudo/modulos/atributos/admins/api.php`);
        request.addData({
            acao: "init",
            modulo: this.modulo,
            banco: this.banco,
            edit: this.editMode
        })
        request.send().then((r)=>{
            
            var lista = r.lista

            var i = 0;
            for(let c in lista){
                this.grupos[c] = {nome: lista[c].nome , itens: lista[c].lista}
                i++;
            }
            
            
            var ja = r.cadastros
            for(let c in ja){
                this.add.bind(this)({grupo: c, valores:ja[c]})
            }
     
        }, (r)=>{
     
        })
    }
    
    add(pre){
        

        var li = document.createElement("LI")
        li.classList.add("list-group-item")
        
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var esquerda = document.createElement("DIV")
        esquerda.classList.add("col-12", "col-xl-6")
        
        var grupo = document.createElement("INPUT")
        grupo.setAttribute("placeholder", "Atributo");
        grupo.classList.add("form-control", "grupo")
        
        
      
        esquerda.appendChild(grupo)
        
        var direita = document.createElement("DIV")
        direita.classList.add("col-12", "col-xl-5")
        
        var coldel = document.createElement("DIV")
        coldel.classList.add("col-xl-1", "d-flex", "justify-content-center", "align-items-center")
        var deleta = document.createElement("BUTTON")
        deleta.classList.add("btn")
        deleta.innerHTML = `<i class="bi bi-x-lg"></i>`
        evento(deleta, "click", this.remove.bind(this))
        coldel.appendChild(deleta)
        
        
        var atributo = document.createElement("INPUT")
        atributo.setAttribute("placeholder", "Valores");
        atributo.classList.add("form-control", "atributos")
        direita.appendChild(atributo)

          if(pre){
            grupo.value = JSON.stringify(
                [
                    {"value":this.grupos[pre.grupo].nome ,"id":pre.grupo}
                ]
                );
                

                
            var z = 0;
            var array = [];
            while(z < pre.valores.length){
                var valor = pre.valores[z]

                array.push({
                    value: this.grupos[pre.grupo].itens[valor],
                    id: valor
                })
                z++;
            }
            atributo.value  = JSON.stringify(array);
            
        }



        
        row.appendChild(esquerda)
        row.appendChild(direita)
        row.appendChild(coldel)
        
        nownFiles.add([`${dominio}/assets/aplicativo/taglify/tagify.js`, `${dominio}/assets/aplicativo/taglify/tagify.css`], false).then(()=>{
            
            var obj = {
                 maxTags: 10,
                 enforceWhitelist: true,
                 dropdown: {
                     maxItems: 20,           
                     classname: 'tags-look',
                     enabled: 0,    
                     closeOnSelect: false  
                    }
             };
             
             if(array){
                 obj.whitelist = array;
             }
            

             var a = new Tagify(atributo, obj)
             a.on('change', (e)=> {
                 this.renderVariacoes.bind(this)();
             })
              

             

            
            var white = [];
            for(let c in this.grupos){
                white.push({
                    value: this.grupos[c].nome,
                    id: c
                })
            }
            var g = new Tagify(grupo, 
            {
                mode : "select",
                enforceWhitelist: true,
                whitelist: white
            })
            if(pre){
                var filhos = this.grupos[pre.grupo].itens
                    
                var white = [];
            for(let c in filhos){
                white.push({
                    value: filhos[c],
                    id: c
                })
            }


        
        a.settings.whitelist = white;
        if(white.length > 0){
            this.renderVariacoes.bind(this)();
        }
 
     
       
            
            }
            
            
            g.on('change', (e)=> {
                atributo.value = "";
                const selectedData = e.detail.tagify.value[0];
                
                if(!selectedData){
                    return;
                }
                
                
                if(Object.keys(this.grupos[selectedData.id].itens).length > 0){
                    
                    var filhos = this.grupos[selectedData.id].itens
                    
                    var white = [];
            for(let c in filhos){
                white.push({
                    value: filhos[c],
                    id: c
                })
            }


        
        a.settings.whitelist = white;
 
                    
                    
                    
                    
                    
                    
                }
                
                
               

            });    
            
           
        })
        
        
    
        li.appendChild(row)
        this.ul.appendChild(li)

    }
    
    combinaArrays(arrays) {
    return arrays.reduce((acc, current) => {
        const combinations = [];
        acc.forEach(a => {
            current.forEach(b => {
                combinations.push([...a, b]);
            });
        });
        return combinations;
    }, [[]]);
        
    }

    renderVariacoes(){
        this.thead.innerHTML = "";
        this.tbody.innerHTML = "";
        const lis = this.ul.getElementsByClassName("list-group-item");
        
        const r = {};
    var atr = {};
    var header = document.createElement("TR")
    var th = document.createElement("TH")
    th.innerText = "Imagem"
    header.appendChild(th)
    for (let i = 0; i < lis.length; i++) {
        const li = lis[i];
        const grupo = JSON.parse(li.getElementsByClassName("grupo")[1]?.value || '[]');
        const atributos = JSON.parse(li.getElementsByClassName("atributos")[1]?.value || '[]');

        if (grupo.length === 1 && atributos.length > 0) {
            const grupoId = parseInt(grupo[0].id, 10);
            r[grupoId] = r[grupoId] || [];

            var th = document.createElement("TH")
            th.innerText = this.grupos[grupoId].nome
            header.appendChild(th)
      
            
            atributos.forEach((atributo) => {
                atr[atributo.id] = atributo.value
                r[grupoId].push(parseInt(atributo.id, 10));
            });
        }
        
        
    }
    
    var th = document.createElement("TH")
    th.innerText = "Preço"
    header.appendChild(th)
            
    var th = document.createElement("TH")
    th.innerText = "Quantidade"
    header.appendChild(th)
    
    if(Object.keys(r).length > 0){
        var tr = document.createElement("TR")
        var arrays = [];
        for(let c in r){
            var th = document.createElement("TH")
            th.innerText = this.grupos[c].nome
            tr.appendChild(th)
            
            this.grupos[c].nome
            
            arrays.push(r[c])
        }
        
        var tratados  = this.combinaArrays(arrays);
        var i = 0;
        while(i < tratados.length){
            var tratado = tratados[i]
            
            
            var tr = document.createElement("TR")
            
            var th = document.createElement("TH")
            tr.appendChild(th)
            
            var file = document.createElement("INPUT")
            file.type = "file"
            th.appendChild(file)
            
            let upload = new Uploader(file, {fullTxt: "Imagem"});
            upload.scriptsCode().then(()=>{
            upload.render()
        })
            
           
            
            
            var z = 0;
            while(z < tratado.length){
                var item = tratado[z]

                var th = document.createElement("TH")
                tr.appendChild(th)
           
                th.innerText = atr[item]
                
                
                z++;
            }
            
            
            var th = document.createElement("TH")
            tr.appendChild(th)
            th.innerHTML = `<input class="form-control mascaraInput" data-mascara="12" type="number" min="0" step="0.01">`
            
            
                
            var th = document.createElement("TH")
            tr.appendChild(th)
            th.innerHTML = `<input class="form-control">`
            
            
            this.tbody.appendChild(tr)
         
            
            console.log(tratado)
            
            i++;
        }
      
        
        this.thead.appendChild(header)
         
    }
    
    }
    
    remove(){
        event.currentTarget.closest(".list-group-item").remove();
    }
    
    render(){
        var pai = document.createElement("DIV")
        pai.classList.add("d-flex", "flex-column", "gap-4")
        
        var div = document.createElement("DIV")
        div.classList.add("card", "card-nown")
        div.dataset.card = "atributos"

        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "bg-transparent", "border-bottom")
        
        var h3 = document.createElement("H3")
        h3.classList.add("fs-14","text-uppercase","m-0","fw-700","text-contrast")
        h3.innerText = "Atributos";
        header.appendChild(h3)
        div.appendChild(header)
        
        this.body = document.createElement("DIV")
        this.body.classList.add("card-body")
        
        this.ul = document.createElement("UL")
        this.ul.classList.add("list-group","list-group-flush")
        
        this.body.appendChild(this.ul)
        

        var footer = document.createElement("DIV")
        footer.classList.add("card-footer", "bg-transparent", "border-top", "d-flex", "justify-content-between")
        
        var start = document.createElement("DIV")
        start.classList.add("d-flex", "justify-content-start", "gap-2")
        
        var novoGrupo = document.createElement("BUTTON")
        novoGrupo.classList.add("btn", "text-primaria",  "d-flex", "justify-content-center", "align-items-center", "gap-1", "fw-700")
        novoGrupo.innerHTML = `<span><i class="bi bi-plus"></i></span><span>Grupo</span>`
        start.appendChild(novoGrupo)
        evento(novoGrupo, "click", this.novoGrupo.bind(this))
        
        var novoAtributo = document.createElement("BUTTON")
        novoAtributo.classList.add("btn", "text-primaria", "d-flex", "justify-content-center", "align-items-center", "gap-1", "fw-700")
        novoAtributo.innerHTML = `<span><i class="bi bi-plus"></i></span><span>Atributo</span>`
        start.appendChild(novoAtributo)
        evento(novoAtributo, "click", this.novoAtributo.bind(this))
        
        footer.appendChild(start)
        
        var btnAdd = document.createElement("BUTTON")
        btnAdd.classList.add("btn", "btn-n-primaria", "btn-sm", "rounded-pill", "px-4", "py-2")
        btnAdd.innerText = "Adicionar Atributo"
        evento(btnAdd, "click", ()=>{
             this.add.bind(this)(false)
        })
        footer.appendChild(btnAdd)
        
        div.appendChild(this.body)
        div.appendChild(footer)
        
        pai.appendChild(div)
        
        
        if(this.config?.basico?.["forcar-atributos"] || 0 == 1){
            
            
            setTimeout(()=>{
                 if(this.pai.container.vendavel){
                     var card = document.createElement("DIV")
            card.classList.add("card", "card-nown")
            
            
            var header = document.createElement("DIV")
            header.classList.add("card-header", "bg-transparent", "border-bottom")
            
            var h3 = document.createElement("H3")
            h3.classList.add("fs-14","text-uppercase","m-0","fw-700","text-contrast")
            h3.innerText = "Variações";
            header.appendChild(h3)
            card.appendChild(header)
            
            var body = document.createElement("DIV")
            body.classList.add("card-body")
            
            this.tabela = document.createElement("TABLE")
            this.tabela.classList.add("table","table-striped")
            
            this.thead = document.createElement("THEAD")
            this.tbody = document.createElement("TBODY")
            
            this.tabela.appendChild(this.thead)
            this.tabela.appendChild(this.tbody)
            

            
            body.appendChild(this.tabela)
       
            
            card.appendChild(body)

            pai.appendChild(card)
                 }
            }, 200)
            
        }
        
        return pai;
    }

    novoAtributo(){
        if(!this.modalAtributo){
            var id = geraId();
            var div = document.createElement("DIV")
            div.classList.add("modal","fade")
            div.id = id;
            div.setAttribute("tabindex", "-1");
            div.setAttribute("aria-hidden", "true")
            div.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-700 fs-18 m-0">Novo Atributo</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div>
        <label class="form-label">Nome do Atributo</label>
        <input class="form-control nome">
      </div>
      
      <div>
        <label class="form-label">Grupo</label>
        <select class="form-control grupo"></select>
      </div>
      
           <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-n-primaria rounded-pill px-4 btnCriar">Criar Atributo</button>
      </div>
      </div>
 
    </div>
  </div>

            `
            this.selectgrupo = div.getElementsByClassName("grupo")[0]
            this.nomeatributo = div.getElementsByClassName("nome")[0]
            this.btnCriarAtributo = div.getElementsByClassName("btnCriar")[0];
            evento(this.btnCriarAtributo, "click", this.criarAtributo.bind(this))
            document.getElementById("conteudo").appendChild(div)
            this.modalAtributo = new bootstrap.Modal(div, {
                keyboard: false
            })
        }
        
        if(Object.keys(this.grupos).length == 0){
            alert("crie um grupo");
            return;
        }
        
        this.selectgrupo.innerHTML = "";
        var option = document.createElement("OPTION")
        option.value = "0"
        option.innerText = "Selecione o Grupo"
        this.selectgrupo.appendChild(option)
        
        
        for(let c in this.grupos){
            var option = document.createElement("OPTION")
            option.value = c
            option.innerText = this.grupos[c].nome
            this.selectgrupo.appendChild(option)
        }
        
        
        this.modalAtributo.show();
    }
    
    novoGrupo(){
         if(!this.modalGrupo){
            var id = geraId();
            var div = document.createElement("DIV")
            div.classList.add("modal","fade")
            div.id = id;
            div.setAttribute("tabindex", "-1");
            div.setAttribute("aria-hidden", "true")
            div.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-700 fs-18 m-0">Novo Grupo de Atributos</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div>
        <label class="form-label">Nome do Atributo</label>
        <input class="form-control nome">
      </div>
      
      
           <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-n-primaria rounded-pill px-4 btnCriar">Criar Grupo</button>
      </div>
      </div>
 
    </div>
  </div>
            `
            
            
            
            this.nomegrupo = div.getElementsByClassName("nome")[0];
            this.btnCriarGrupo = div.getElementsByClassName("btnCriar")[0];
            evento(this.btnCriarGrupo, "click", this.criarGrupo.bind(this))
            
            document.getElementById("conteudo").appendChild(div)
            this.modalGrupo = new bootstrap.Modal(div, {
                keyboard: false
            })
        }
        
        this.modalGrupo.show();
    }
    
    criarGrupo(){
        if(!this.modulo || !this.banco){
            console.log("Ação permitida somente no modo de produção")
        }
        
        
        if(!this.nomegrupo.value && !this.nomegrupo.value.trim()){
            return;
        }
        
        var request = new Request(`${dominio}/conteudo/modulos/atributos/admins/api.php`);
        request.addData({
            acao: "novoGrupo",
            nome: this.nomegrupo.value.trim(),
            modulo: this.modulo,
            banco: this.banco
        })
        request.send().then((r)=>{
            var item = r.item
            this.grupos[item.id] = {nome: item.nome, itens: {}}
            this.nomegrupo.value = "";
            this.modalGrupo.hide();
            

              
        }, (r)=>{
            console.log(r)
        })

    }

    criarAtributo(){

        if(!this.modulo || !this.banco){
            console.log("Ação permitida somente no modo de produção")
        }
        
        
        if(!this.nomeatributo.value || !this.nomeatributo.value.trim()){
            return;
        }
        
        if(this.selectgrupo.value  == "0"){
            return
        }
        
        
        var request = new Request(`${dominio}/conteudo/modulos/atributos/admins/api.php`);
        request.addData({
            acao: "novoAtributo",
            nome: this.nomeatributo.value,
            grupo: this.selectgrupo.value
        })
        request.send().then((r)=>{
            var item = r.item
            this.grupos[item.grupo].itens[item["id"]] = item.nome;
            
            this.modalAtributo.hide(); 
            
        }, (r)=>{
            console.log(r)
        })
        
        
        
    }
    
    get() {
    const lis = this.ul.getElementsByClassName("list-group-item");
    const r = {};

    for (let i = 0; i < lis.length; i++) {
        const li = lis[i];
        const grupo = JSON.parse(li.getElementsByClassName("grupo")[1]?.value || '[]');
        const atributos = JSON.parse(li.getElementsByClassName("atributos")[1]?.value || '[]');

        if (grupo.length === 1 && atributos.length > 0) {
            const grupoId = parseInt(grupo[0].id, 10);
            r[grupoId] = r[grupoId] || [];

            atributos.forEach((atributo) => {
                r[grupoId].push(parseInt(atributo.id, 10));
            });
        }
    }

    if(Object.keys(r).length == 0){
        return false;
    }
    return r;
}
}

class EnderecoControler{
    constructor(id, div, container, multiplo){
    
        this.id = id;
        this.input = div;
        this.container = container;
        this.multiplo = multiplo;
    }
    
    listaEnderecos(){
        if(this.multiplo){
              let request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/associa.php`);
         var obj = {
             editMode: this.container.brain.editMode,
             identificador: this.container.brain.identificador,
             master: this.container.brain.master,
             modulo: this.container.brain.modulo,
             acao: 'listar'
            }
            this.enderecos = [];
        request.addData(obj)
        request.send().then((r)=>{
            var enderecos = r.enderecos;
            if(enderecos.length > 0){
                var i = 0;
                var row = document.createElement("DIV")
                row.classList.add("row", "g-3")
                while(i < enderecos.length){
                    var endereco = enderecos[i]
                    this.enderecos[endereco.hash] = endereco;
                    var destaque = endereco.id == this.id ? "principal" : ""

                    var div = document.createElement("DIV")
                    div.classList.add("col-12", "col-xl-6")
                    div.dataset.endereco = endereco.hash
        
                    div.innerHTML = `
                    <div class="card-endereco ${destaque}">
                        <div class="info">
                            <h5>${endereco.nome}</h5>
                            <div class="fs-12 fw-700">${endereco.cep}</div>
                            <p class="rua d-flex gap-2">
                                <span data-map="Rua">${endereco.rua}</span>
                                <span data-map="Numero">${endereco?.numero || ""}</span>
                            </p>
                            <p>${endereco.bairro}</p>
                            <p class="cidade"><span>${endereco.cidade}</span> - <span>${endereco.estado}</span></p>
                        </div>
                        <div class="position-relative">   
                            <div class="dropdown">
                                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item btnEndereco" data-acao="editar">Editar</button></li>
                                    <li><button class="dropdown-item btnEndereco" data-acao="apagar">Apagar</button></li>
                                    <li><button class="dropdown-item btnEndereco" data-acao="principal">Definir como principal</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    ` 
                     evento(Array.from(div.getElementsByClassName("btnEndereco")), "click", this.acaoEndereco.bind(this))
                    if(destaque == "principal"){
                        row.insertBefore(div, row.firstChild);
                    }else{
                        row.appendChild(div)
                    }
                    
   
                    i++;
                }
                
                this.input.getElementsByClassName("listaEnderecos")[0].innerHTML = "";
                this.input.getElementsByClassName("listaEnderecos")[0].appendChild(row)
            }
            else{
                this.input.getElementsByClassName("listaEnderecos")[0].innerHTML = `<h2 class="m-0 text-center">Não há endereços cadastrados</H2>`
            }
            
        }, (r)=>{
           this.input.getElementsByClassName("listaEnderecos")[0].innerHTML = `<h2 class="m-0 text-center">Não há endereços cadastrados</H2>`
        })
        }else{
            var div = document.createElement("DIV")
            div.classList.add("row", "g-4")
            
            
        
            var cep = this.inputFloat("CEP", "cep", 12);
            this.cep = cep.getElementsByClassName("form-control")[0]
            
            this.cep.dataset.mascara = 3
            this.cep.classList.add("mascaraInput");
            
            evento(this.cep , "input", this.digitando.bind(this))
            
            div.appendChild(cep)
            
            
            
        
            var pais = this.inputFloat("País", "pais", 12, true);
            div.appendChild(pais)
            this.pais = pais.getElementsByClassName("form-control")[0]
            
            var estado  = this.inputFloat("Estado", "estado", 6, true);
            div.appendChild(estado)
            this.estado = estado.getElementsByClassName("form-control")[0]
            
            let cidade  = this.inputFloat("Cidade", "cidade", 6, true);
            div.appendChild(cidade)
            this.cidade = cidade.getElementsByClassName("form-control")[0]
            
            let rua  = this.inputFloat("Rua", "rua", 8, true);
            div.appendChild(rua)
            this.rua = rua.getElementsByClassName("form-control")[0]
            
            let numero  = this.inputFloat("Número", "numero", 4);
            div.appendChild(numero)
            this.numero = numero.getElementsByClassName("form-control")[0]
            
            let bairro  = this.inputFloat("Bairro", "bairro", 6, true);
            div.appendChild(bairro)
            this.bairro = bairro.getElementsByClassName("form-control")[0]
            
            let complemento  = this.inputFloat("Complemento", "complemento", 6);
            div.appendChild(complemento)
            this.complemento = complemento.getElementsByClassName("form-control")[0]
            
            let observacao  = this.inputFloat("Observação do cliente", "observacao", 12);
            div.appendChild(observacao)
            this.observacao = observacao.getElementsByClassName("form-control")[0]
            
            let referencia  = this.inputFloat("Ponto de Referência", "referencia", 12);
            div.appendChild(referencia)
            this.referencia = referencia.getElementsByClassName("form-control")[0]
            
            
            bounce(this.complemento, 500, this.update.bind(this));
            bounce(this.numero , 500, this.update.bind(this));
            bounce(this.observacao, 500, this.update.bind(this));
            bounce(this.referencia, 500, this.update.bind(this));
            
            var frame = document.createElement("DIV")
            frame.classList.add("he-400")
     
            div.appendChild(frame)
            
            this.meuMapa = new CustomMap(frame);
            
            this.input.getElementsByClassName("listaEnderecos")[0].innerHTML = "";
            this.input.getElementsByClassName("listaEnderecos")[0].appendChild(div)
            
            if(this.id){
                 let request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/associa.php`);
                 request.addData({id: this.id,acao: 'one'});
                 request.send().then((r)=>{
                     console.log(r)
                     var endereco = r.endereco;
                     
                     this.cep.value = endereco.cep
                     this.rua.value = endereco.rua
                     this.bairro.value = endereco.bairro
                     this.cidade.value = endereco.cidade
                     this.pais.value = endereco.pais
                     this.estado.value = endereco.estado
                     this.numero.value = endereco.numero
                     this.complemento.value = endereco.complemento
                     this.observacao.value = endereco.observacao
                     this.referencia.value = endereco.referencia
                     
                     if(endereco.latitude && endereco.longitude){
                           this.meuMapa.update(endereco.latitude, endereco.longitude); 
                         
                     }
                 })
            }
           
            
            
        }
    }
    
    update(){
        if(this.cep.value.length == 9){
              var request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/associa.php`);
        request.addData({
            acao: "uni",
            id: this.id,
            cep: this.cep.value,
            numero: this.numero.value,
            complemento: this.complemento.value,
            referencia: this.referencia.value,
            observacao: this.observacao.value
        })
        request.send().then((r)=>{
            this.id = r.id
            if(r.lat && r.long){
                this.meuMapa.update(r.lat, r.long); 
            }
        })
        }
      
    }
    
    digitando(){
        if(this.cep.value.length == 9){
            var cep = this.cep.value.replace(/\D/g, '');
            var ajax = new XMLHttpRequest();
            ajax.onload = ()=>{
                var texto = JSON.parse(ajax.responseText)
                
                this.bairro.value = texto.bairro
                this.estado.value = texto.estado
                this.cidade.value = texto.localidade
                this.rua.value = texto.logradouro
                this.pais.value = "Brasil"
                this.update.bind(this)();

            }
            ajax.open("GET", `${dominio}/conteudo/modulos/enderecos/admins/externa.php?cep=${cep}`);
            ajax.send();
        }

    }
    
    inputFloat(placeholder, id, size = 12, disabled = false){
        var div = document.createElement("DIV")
        div.classList.add("col-12", `col-xl-${size}`)
        var d = disabled ? "disabled" : "";
        div.innerHTML = `
        <div class="form-floating">
            <input type="text" class="form-control" id="${id}InputNown" placeholder="${placeholder}" ${d}>
            <label for="${id}InputNown">${placeholder}</label>
        </div>
        `
        return div;
    }
    
    apagar(endereco){
         Swal.fire({
                    title: "Atenção!", 
                    text: "Você quer apagar esse endereço?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: "Apagar",
                    cancelButtonText: `Cancelar`
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        var request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/associa.php`);
                        request.addData({acao: "apagar", hash: endereco})
                        request.send().then((r)=>{
                             Swal.fire({
                            icon: "success", 
                            title: "Endereço Apagado",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        if(r.id == this.id){
                            delete this.enderecos[endereco]
                            
                            for(let c in this.enderecos){
                                var endereco = this.enderecos[c]
                                console.log(endereco)
                                
                                
                            }
                            
                      
                        }
                        
                        
                        this.listaEnderecos.bind(this)();
                        }, (r)=>{
                            console.log("teste")
                        })
                       
                    } 
                });
    }
    
    acaoEndereco(){
        var acao = event.currentTarget.dataset.acao
        var endereco = event.currentTarget.closest(".col-12").dataset.endereco;
        
        switch(acao){
            case 'principal':
                this.id = this.enderecos[endereco].id;
                this.listaEnderecos.bind(this)();
                
                break;
            case 'apagar':
                this.apagar.bind(this)(endereco);
                break;
            case 'editar':
                break;
        }
        
        
        
    }
    
    associaEndereco(r){
            
        if(r.sucesso && r.id){
            var id = r.id;
            
            
            var obj = {
                endereco: id,
                editMode: this.container.brain.editMode,
                identificador: this.container.brain.identificador,
                master: this.container.brain.master,
                modulo: this.container.brain.modulo,
                acao: 'associar'
            }
            let request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/associa.php`);
            request.addData(obj)
            request.send().then((r)=>{
                this.id = r.id
                this.listaEnderecos.bind(this)();
            }, (r)=>{
                console.log(r)
            })
            
        }
    }
    
    get(){
        return this.id;
    }
}

class CodeEditor{
    constructor(){
        
    }
    
    render(){
          var input = document.createElement("TEXTAREA")
        nownFiles.add([
            "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css",
            "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"
            ]).then(()=>{
              setTimeout(()=>{
                    var editor = CodeMirror.fromTextArea(input , {
    mode: "html", // Suporta HTML, CSS e JS juntos
    theme: "default", // Troque por outro tema, ex: "dracula"
    lineNumbers: true, // Exibir número de linhas
    matchBrackets: true, // Destaca pares de chaves e colchetes
    autoCloseTags: true, // Fecha tags HTML automaticamente
    autoCloseBrackets: true, // Fecha parênteses automaticamente
});
              }, 500)

            })
      
        return input;
    }
}

class SubConta{
    constructor(modulo = false, formulario = false, div, valor){
        var nown = JSON.parse(pegaLocal("nown"))
        var sub = nown.sub
     
        
        this.modulo = modulo;
        this.formulario = formulario;
        this.div = div;
        this.valor = valor;
        

        if(nown.config.paginas["multi-contas"].tipo == this.modulo){
           if(valor){
               
           }else{
              this.valor = parseInt(sub.id)
           }
        }
        
        
     
    }
    
    get(){
        return this.input.value;
    }
    
    render(){
         var div = this.div
                
                 if(this.modulo && this.formulario){
                     this.input = document.createElement("SELECT");
                     this.input.classList.add("form-select")
                     this.input.innerHTML = `<option value="">Selecione a conta</option>`

               
                div.getElementsByClassName("card-body")[0].appendChild(this.input)
                var api = new Request(`${dominio}/admin/subconta.php`);
                api.addData({
                    acao: "lista",
                    modulo: this.modulo,
                    formulario: this.formulario
                    
                })
                api.send().then((r)=>{
                    if(r.lista){
                        var i = 0;
                        var lista = r.lista
                        for(let c in lista){
                            var option = document.createElement("OPTION")
                            option.value = c
                            option.innerText = lista[c]
                            this.input.appendChild(option)
                        }
                        if(this.valor){
                            this.input.value = this.valor;
                            this.input.setAttribute("disabled", "")
                        }
                    }else{
                    }
                }, (r)=>{
                     div.getElementsByClassName("card-body")[0].innerText = "Erro na Configuração de Subconta."
                })
                 }else{
                     div.getElementsByClassName("card-body")[0].innerText = "Erro na Configuração de Subconta."
                 }
                 return div;
                
    }
}

class InputRenderizado{
    constructor(item, key, container){
        this.item = item
        this.data = this.data.bind(this)
        this.key = key
        
        
        this.container = container
        this.pegaValor = this.pegaValor.bind(this)
    }
    
    data(grupo, chave, noErro = ""){

        if(this.infos[grupo]){
            if(this.infos[grupo][chave]){
                return this.infos[grupo][chave]
            }
        }
        
        return noErro;
       
    }
    
    cardModelo(){
        var id = geraId();
        var card = document.createElement("DIV")
        card.classList.add("card", "border-0", "shadow-sm", "overflow-hidden")
        
        
        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "bg-transparent", "border-0")
        
        var flex = document.createElement("DIV")
        flex.classList.add("d-flex","justify-content-between","align-items-center")
        
        var h3 = document.createElement("H3")
        h3.classList.add("fs-14","text-uppercase","m-0","fw-700","text-contrast")
      
        
        var btn = document.createElement("BUTTON")
        btn.classList.add("btn","btn-sm","seta")
        btn.innerHTML = `<i class="bi bi-caret-down-fill text-contrast"></i>`
        btn.setAttribute("type","button")
        btn.setAttribute("data-bs-toggle","collapse")
        btn.setAttribute("data-bs-target",`#${id}`)
        btn.setAttribute("aria-expanded","true")
        btn.setAttribute("aria-controls", `${id}`)
        
        flex.appendChild(h3)
        flex.appendChild(btn)
        
        header.appendChild(flex)
        
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "border-top")
        
        
        var drop = document.createElement("DIV")
        drop.classList.add("collapse","show")
        drop.id = id;
        
     
        
        var footer = document.createElement("DIV")
        footer.classList.add("card-footer", "border-0","bg-transparent", "d-flex", "justify-content-between")
        
        drop.appendChild(body)
        drop.appendChild(footer)
        
        card.appendChild(header)
        card.appendChild(drop)

        
        return card;
    }
    
    pegaValor(input){
        if(this.container.memoria){
            var hash = this.item.hash;
            var memoria = this.container.memoria;

            if(memoria[hash]){
                

                switch(this.item.tipo){
                      case 'select':
                          if(this.input.multiple){
                              try{
                              var valor = JSON.parse(memoria[hash])
                          }catch(e){
                              var valor = [memoria[hash]];
                          }
                          
                          var opc = this.input.options
                          var i = 0;
                          while(i < opc.length){
                              var o = opc[i]
                             if (valor && (Array.isArray(valor) || typeof valor === "string")) {
    if (valor.includes(o.value)) {
        o.selected = true;
    }
}
                             
                               
                              i++;
                          }
 
                          }else{
                              this.input.value =  memoria[hash]
                          }
                          

                           this.input.setAttribute("data-valor",  memoria[hash])
                           
                           let evento = new Event('input');
                           this.input.dispatchEvent(evento);

                    break;
                    case 'file':

                        this.destaque.set(memoria[hash])
                        
                        break;
                    case 'switch':
                       
                        if(memoria[hash]){
                            if(memoria[hash] == "0"){
                                this.input.checked = false;
                            }else{
                                this.input.checked = true;
                            }
                            
                        }
                        break;
              
                   
                    default:
                        this.input.value = memoria[hash]
                        break;
                }
                
                
               
            }
        }
    }
    
    ajax(data, cb){
    
        
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(obj.sucesso){
                    if(cb){
                        cb(obj)
                    }else{
                        console.log(obj)
                    }
                }else{

                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/brain.php`);
        request.send(data)
    }

    criptokey(info) {
    var formato = info.formato ?? "xxxxxxxxxxxxx";

    var caracteres = "";
    if (!info["desativar-numeros"]) {
        caracteres += "1234567890";
    }

    if (!info["desativar-letras-maiusculas"]) {
        caracteres += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }

    if (!info["desativar-letras-minusculas"]) {
        caracteres += "abcdefghijklmnopqrstuvwxyz";
    }

    // Agora, gere uma chave com base no formato e nos caracteres usados,
    // sabendo que o 'x' deve ser um caractere aleatório sorteado
    var chave = "";
    for (var i = 0; i < formato.length; i++) {
        if (formato[i] === "x") {
            chave += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        } else {
            chave += formato[i];
        }
    }

    return chave;
}

    lequeOpts(){
        this.whiteList = [];
        var opcoes = this.data("opcoes","opcoes", []);
        if(!Array.isArray(opcoes)){
            return;
        }
        var i = 0;
        while(i < opcoes.length){
            
            
            switch(this.prime){
                case 'select':
                    var option = document.createElement("OPTION")
                    option.innerText = opcoes[i].nome
                    option.value =  opcoes[i].valor
                    this.whiteList.push({ value: opcoes[i].nome , code: opcoes[i].valor})
                     
                    if(opcoes[i].selecionado){
                        option.setAttribute("selected", "")
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    var id = geraId();
                    option  = document.createElement("DIV")
                    option.classList.add("form-check")
                    
                    var input = document.createElement("INPUT")
                    input.classList.add("form-check-input")
                    
                    console.log(this.prime)
                    if(this.prime == "radio"){
                        input.type = "radio" 
                    }else{
                        input.type = "checkbox"
                    }
                   
                    input.name = this.input.dataset.grupo
                    input.id = id;
                    input.value = opcoes[i].valor
                    
                    var label = document.createElement("label")
                    label.classList.add("form-check-label")
                    label.setAttribute("for", id)
                    label.innerText = opcoes[i].nome
                    
                    option.appendChild(input)
                    option.appendChild(label)

                    break;
            }
            
            
            
                           
            this.input.appendChild(option)
            i++;
            }
    }
    
    fechaSelectT(){
        
    }
    
    setTo(jump = false){
        var render = 0;
        
        if(this.data("basico","select-2", false)){
            render = 1;
        }
        
        if(this.data("basico","render", false)){
            render = this.data("basico","render", false);
        }
        
        switch(parseInt(render)){
            case 0:
                // Sem Nada
                break;
            case 1:
                console.log(this)
                // Select 2
                    var selecionados = [];
                    var hash = this.item.hash;
                    
                    console.log(hash)
                    console.log(this.container)
                    
                    var memoria = this.container.memoria;
                   console.log(memoria)
                    if (memoria[hash]) {
                        try{
                            
                             var selecionados = JSON.parse(memoria[hash]);
                             if (!Array.isArray(selecionados)) {
                                 selecionados = [selecionados];
                             }
                        }catch(e){
                           
                        }
                           
    
                    }
           
           
            console.log(selecionados)

 
                 nownFiles.add([`${dominioscript}/assets/aplicativo/select2/min.js`, `${dominioscript}/assets/aplicativo/select2/min.css`]).then(()=>{
                     if(jump){
                          let obj = {}
                           if(this.data("label","placeholder", false) && this.data("label","placeholder", false).trim()){
                               obj.placeholder = this.data("label","placeholder", false)
                               obj.allowClear = true
                           }
                                          $(this.input).select2(obj);
                          $(this.input).on("select2:select", (e)=> { 
                              this.input.dispatchEvent(new Event("input"));
                            });
                         return;
                     }
                   
           
                           let obj = {}
                          if(this.data("label","placeholder", false) && this.data("label","placeholder", false).trim()){
                               obj.placeholder = this.data("label","placeholder", false)
                               obj.allowClear = true
                           }
                         var request = new Request(`${dominio}/admin/opcoes.php`);
                         request.addData({
                              tipo: 3,
                              foco: JSON.stringify(this.data("dinamica", "configuracoes", '{}')),
                              getSize: true
                         });
                         
                         
                         
                         
                         request.send().then((r)=>{
                             
                           
                             
                             
                              if(r.big){
                                    obj.ajax = {
                                 url: `${dominio}/admin/opcoes.php`,
                                 type: "POST",
                                 dataType: "json",
                                 delay: 250,
                                 data:  (params)=> {
                                     return {
                                         tipo: 3,
                                         foco: JSON.stringify(this.data("dinamica", "configuracoes", '{}')),
                                         big: true,
                                         termo: params.term
                                     };
                                 },
        processResults: function (data) {
            if (data.sucesso && data.lista) {
                let resultados = [];

                for (let nome in data.lista) {
                    resultados.push({
                        id: data.lista[nome],
                        text: nome
                    });
                }

                return {
                    results: resultados
                };
            } else {
                return {
                    results: []
                };
            }
        },
        cache: true
    };
                            obj.minimumInputLength = 1;
                            
                                 obj.language = {
                               inputTooShort:  (args)=> {
                                   var restante = args.minimum - args.input.length;
                                   return `Digite mais ${restante} caractere${restante > 1 ? 's' : ''}`;
                               },
                               searching: function () {return 'Buscando…';},
                               noResults: function () {return 'Nenhum resultado encontrado';},loadingMore: function () {return 'Carregando mais resultados…';},
                               errorLoading: function () {return 'Erro ao carregar resultados';},
                               inputTooLong: (args) =>{
                                   var excesso = args.input.length - args.maximum;
                                   return `Apague ${excesso} caractere${excesso > 1 ? 's' : ''}`;
                               },
                               maximumSelected: (args)=> {return `Você só pode selecionar ${args.maximum} item${args.maximum > 1 ? 's' : ''}`;}
                           }
                           
                           
                           $(this.input).select2(obj);
                          $(this.input).on("select2:select", (e)=> { 
                              this.input.dispatchEvent(new Event("input"));
                            });
            
                            if (selecionados.length > 0) {
        var request = new Request(`${dominio}/admin/opcoes.php`);
        request.addData({
            tipo: 3,
            foco: JSON.stringify(this.data("dinamica", "configuracoes", '{}')),
            big: true,
            memoria: JSON.stringify(selecionados)
        });

        request.send().then((r) => {
            // Verifica se o backend retornou sucesso e a lista no mesmo formato anterior
            if (r.sucesso && r.lista) {
                for (let nome in r.lista) {
                    let id = r.lista[nome];

                    // Cria e insere manualmente a opção no select
                    let option = new Option(nome, id, true, true);
                    $(this.input).append(option);
                }

                // Atualiza o select2
                $(this.input).trigger('change');
                this.input.dispatchEvent(new Event("input"));
            }
        });
    }
                            
                            
                            
                            
                              }
                              else{
                             
                                   for (let nome in r.lista) {
                    let id = r.lista[nome];

                    // Cria e insere manualmente a opção no select
                    let option = new Option(nome, id, false, false);
                    $(this.input).append(option);
                }
                
                            $(this.input).select2(obj);
                          $(this.input).on("select2:select", (e)=> { 
                              this.input.dispatchEvent(new Event("input"));
                            });
                            
                            $(this.input).val(selecionados).trigger('change');
                            this.input.dispatchEvent(new Event("input"));

                              }
                         })
                         
                         
                     
                    

                          
                          
                           
                          
                            
                          
                         
                          
                          
                          

           
                        

           
                          
                          
                    
                        
            })
                
                
                
                break;
            case 2:
                // Taglify
                
        
            
    
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                
                var hash = this.item.hash;
                var memoria = this.container.memoria;
              
              
                let obj = {
        whitelist: this.whiteList,
        enforceWhitelist: true,
        dropdown: {
            maxItems: 20,           
            classname: 'tags-look',
            enabled: 0,             
            closeOnSelect: false  
        }
    }
                
                
                 if(!this.data("opcoes","multiplo", false)){
                   obj.mode = "select";
                   obj.dropdown.closeOnSelect = true;
                }
                
                
     
                
                  nownFiles.add([`${dominio}/assets/aplicativo/taglify/tagify.js`, `${dominio}/assets/aplicativo/taglify/tagify.css`], false).then(()=>{
               
                                 var tag = new Tagify(this.input , obj)
                                 
                    
                                 
                                 
                    })
                    
                    
                break;
        }
        

    }
    
    render(){
        
        this.infos = this.item.infos

 
        var div = document.createElement("DIV")
        div.classList.add("containerInput")
        this.div = div
        
        if(this.infos.identificador){
            if(this.infos.identificador.id){
                if(this.infos.identificador.id.trim()){
                  div.id = this.infos.identificador.id.trim()  
                }
                
            }
            
            if(this.infos.identificador.classes.trim()){
                var classes = this.infos.identificador.classes.trim()
                var classes = classes.split(" ")
                for(let i in classes){
                    div.classList.add(classes[i])
                }
            }
          
        }
        
        
        var randomId = geraId();
        this.randId = randomId;

        let label= this.data("basico","label", "").trim();
        
        if(label && !this.data("basico", "esconder-label", false)){
            this.label = document.createElement("LABEL")
            if(this.data("basico", "obrigatorio", false)){
                this.label.classList.add("obrigatorio")
            }
            
            
            this.label.setAttribute("for", randomId)
            this.label.innerText = label
            
            
            var icone = this.data("label","icone", "").trim();
            if(icone){
                var ic = document.createElement("I")
                ic.className = icone
                ic.classList.add("me-2")
                
                
                this.label.insertBefore(ic , this.label.firstChild);
                
            }
            
            
            
            
            
        }else{
            label = false;
        }
        
        
        switch(this.item.tipo){
            case 'subconta':
                this.input = this.cardModelo.bind(this)();
                this.input.setAttribute("data-card", "subconta")
                this.input.getElementsByTagName("h3")[0].innerText = "Conta"
                
                this.subconta = new SubConta(this.data("configuracoes", "modulo", false), this.data("configuracoes", "formulario", false), this.input, this.container?.memoria?.especiais?.subconta || false);
                this.subconta.render();
                this.container.set("subconta", this.subconta)
                
             
    
   

                break;
            case 'text':
            case 'number':
            case 'color':
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                
                
                if(parseInt(this.data("valor", "tipo", 0)) > 0){
                      switch(parseInt(this.data("valor", "tipo", 0))){
                          case 1:
                              // Fixo
                              if(this.data("valor", "fixo", false)){
                                  this.input.value = this.data("valor", "fixo", false).trim();
                              }
                              break;
                          case 2:
                              // Sistema
                              var dinamico = parseInt(this.data("valor", "dinamico", 0));
                              switch(dinamico){
                                 case 0:
                                 case 1:
                                 case 2:
                                     // ID user
                                     var umap = ["id", "nome", "email"];
                                     var user = infoUser();
                                     if(user){
                                         this.input.value = user[umap[dinamico]]
                                     }
                                    
                                    break;
                                  case 3:
                                 
                                      
                                      break;

                              }

                              break;
                          case 3:
                              // Query
                                  var url = this.data("valor", "arquivo-query", false);
                                    
                               if(url){
                                   let request = new Request(url);
                                   var acao = this.data("valor", "acao-query", false);
                                   if(acao){
                                       request.addData({acao: acao})
                                   }
                                   request.send().then((r)=>{
                                       if(r.valor && !this.input.value){
                                           this.input.value = r.valor.trim();
                                       }
                                       
                                   })
                               }
                                     console.log(url)
                              break;
                      }
                }
                
                
                
          
              
                
                
                switch(this.item.tipo){
                    case "number":
                        this.input.type = "number"
                        if(this.data("valor", "minimo", false)){
                            this.input.min = parseInt(this.data("valor", "minimo", false));
                        }
                        
                        if(this.data("valor", "maximo", false)){
                            this.input.max = parseInt(this.data("valor", "maximo", false));
                        }
                        
                        
                        if(this.data("valor", "definido", false)){
                            this.input.value = parseInt(this.data("valor", "definido", false));
                        }
                        
                        
                        
                        break;
                    case "color":
                        this.input.type = "color"
                        this.input.classList.add("he-50")
                        this.input.value = this.data("basico", "comeco","#000000").trim();
                        break;
                    default:
                        this.input.type = "text"
                        break;
                }
               
               
             
                
                
                var mascara = this.data("validacao", "mascara", false);
                if(mascara){
                    this.input.classList.add("mascaraInput")
                    this.input.dataset.mascara = mascara
                }
    
                
                if(this.data("label", "placeholder", false)){
                   this.input.setAttribute("placeholder", this.data("label", "placeholder", false)) 
                }
                
                
                
                var size = this.data("estilo", "tamanho-do-input", 0);
                switch(parseInt(size)){
                    case 1:
                        this.input.classList.add("form-control-lg")
                        break;
                    case 2:
                         this.input.classList.add("form-control-sm")
                        break;
                }
                
                
                this.container.add(this)
                
                
                this.valorProReset = this.input.value
                this.pegaValor()
             
                break;
            case 'code':
                let code = new CodeEditor();
                this.input = code.render();

                break;
            case 'atribuidor':
   
                this.item.atributor = new Atribuidor(this, this.item);
                this.input = this.item.atributor.render();
                
                if(this.data("salvamento", "tipo", 0)){
                    this.container.add(this);
                } 
         
                break;
            case 'destaque':
             
                var div = this.cardModelo.bind(this)();
                div.setAttribute("data-card", "cardDestaque")
                div.getElementsByTagName("h3")[0].innerText = "Destacar"
                
                var grupo = document.createElement("DIV")
                grupo.classList.add("form-check", "form-switch")
                
                
          
                this.input = document.createElement("INPUT")
                if(parseInt(this.container.memoria?.especiais?.destaque || 0) == 1){
                    this.input.checked = true;
                }
                
                this.input.type = "checkbox"
                this.input.setAttribute("role", "switch")
                this.input.classList.add("form-check-input")
                this.container.set("itemDestaque", this.input);
                var id = geraId();
                this.input.id = id
                var lab = document.createElement("LABEL")
                lab.setAttribute("for", id)
                lab.classList.add("form-check-label")
                lab.innerText = "Destacar Item"
                
                grupo.appendChild(this.input)
                grupo.appendChild(lab)
                div.getElementsByClassName("card-body")[0].appendChild(grupo)
                div.getElementsByClassName("card-footer")[0].remove();
                
                break;
            case 'autoria':
                this.input = this.cardModelo.bind(this)();

                this.input.getElementsByTagName("h3")[0].innerText = "Autor"
                this.input.setAttribute("data-card", "autoria");

                break;
            case 'wildcard':
                var div = this.cardModelo.bind(this)();
                this.input = document.createElement("SELECT");
                this.input.classList.add("form-control")

                div.getElementsByTagName("h3")[0].innerText = "Conta"
                div.setAttribute("data-card", "wildcard");
                div.getElementsByClassName("card-body")[0].appendChild(this.input)
                
                let request = new Request(`${dominio}/conteudo/modulos/wildcard/admins/api.php`);
                request.addData({
                    acao: "contas"
                })
                request.send().then((r)=>{
                    var lista = r.lista
                    var i = 0;
                    
                    var wild = parseInt(this.container?.memoria?.especiais?.wildcard || 0);
         
                    while(i < lista.length){
                        var option = document.createElement("OPTION")
                        option.value = lista[i].v
                        option.innerText = lista[i].t
                        if(lista[i].v == wild){
                            option.setAttribute("selected", "");
                        }
                        this.input.appendChild(option)
                        
                        i++;
                    }
                 

                })
                
                 
                 
                 this.container.add(this)
                break;
            case 'tabela':
                
                this.input = document.createElement("DIV")
                
                /*
                let modulo = this.data("basico", "modulo", false);
                let tabela = this.data("basico", "id-tabela", false)
                if(modulo && modulo.trim() && tabela && tabela.trim()){
                    modulo = modulo.trim();
                    tabela = tabela.trim();
                    
                    this.tabelo = new PreTable(modulo, tabela, this.input);
                
     
                }
                */

                break;
            case 'espaco':
                this.input = document.createElement("DIV")

                
                var size = this.data("basico", "altura", 0)
                this.input.style.marginTop = `${size}px`;
                
                break;
            case 'formprogress':
                this.container.progressControl = new BtnsProgress(this, this.container.progressao);
                break;
            case 'video':
                 this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.container.add(this)
                this.pegaValor()
                break;
            case 'url':
                 this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.container.add(this)
                 this.pegaValor()
                break;
            case 'mapa':
                 this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.container.add(this)
                 this.pegaValor()
                break;
            case 'icone':
                var icone= new InputIcone();
                this.input = icone.render();
                this.container.add(this)
                this.pegaValor()
                break;
            case 'textarea':
                this.input = document.createElement("TEXTAREA")
                this.input.classList.add("form-control")
                this.container.add(this)
                
                this.pegaValor()
                break;
            case 'cripto':
                
  
    
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.input.type = "text"
                this.input.setAttribute("disabled", "")
                var cripto = this.criptokey.bind(this)(this.item.infos.formatacao)
                this.input.value = cripto
                this.pegaValor()
                this.container.add(this)
                break;
            case 'select':
            case 'radio':
            case 'checkbox':
                this.prime = this.item.tipo;
                if(this.item.tipo == "select"){
                    this.input = document.createElement("SELECT")
                    
                    this.input.classList.add("form-select")
                    if(this.data("dinamica" , "smart-triger", 0) && this.data("dinamica", "input-trigger", 0)){
                        var valor = this.data("dinamica", "input-trigger", 0).trim();
                        var select = this.container.itens[valor].input;
                        evento(select, "input", this.trigger.bind(this))
                    }
                
                
                
                
                
                if(this.data("opcoes","multiplo", false)){
                    this.input.setAttribute("multiple", "")
                }
                    
                    
                    
                }else{
                    this.input = document.createElement("DIV")
                    this.input.dataset.grupo = geraId();
                }
                
                
                

                switch(parseInt(this.data("dinamica", "opcoes-dinamicas", 0))){
                    case 0:
                        this.lequeOpts();
                        this.valorProReset = this.input.value
                        this.pegaValor();
                        this.setTo(true);
          
                        break;  
                 case 1:
                     this.lequeOpts();
           
                     var info = this.data("dinamica", "configuracoes", false)
                     if(info){
                         
                         var render = 0;
        
        if(this.data("basico","select-2", false)){
            render = 1;
        }
        
        if(this.data("basico","render", false)){
            render = this.data("basico","render", false);
        }
        
        if(render == 0){
            var dinamico = new OpcoesDinamicas(this.input, 3, JSON.stringify(info));
                        dinamico.setTipo(this.prime);
                        dinamico.render().then((r)=>{
                            this.valorProReset = this.input.value
                            this.pegaValor();
                            
                        },(r)=>{
                            
                            console.log("erro")
                        })
        }
        else{
            this.setTo();
        }
                
                        
                        }
                     break;
                case 2:
                 
                      this.lequeOpts();
                    var url = this.data("dinamica", "arquivo-query", false);
                      if(url){
                          let request = new Request(url);
                          var acao = this.data("dinamica", "acao-query", false);
                          if(acao){
                              request.addData({acao: acao.trim()})
                            }
                            request.send().then((r)=>{
                                var lista = r.lista
                                var i = 0;
            
                           
                                while(i < lista.length){
                                    var item = lista[i]
                                    
                                    
                                    switch(this.prime){
                case 'select':
                   var opt = document.createElement("OPTION")
                                    opt.value = item["v"]
                                    opt.innerText = item["t"]
                                    if(item["c"]){
                                        opt.setAttribute("selected", "");
                                    }
                    break;
                case 'radio':
                case 'checkbox':
                    var id = geraId();
                    opt  = document.createElement("DIV")
                    opt.classList.add("form-check")
                    
                    var input = document.createElement("INPUT")
                    input.classList.add("form-check-input")
                    
                    console.log(this.prime)
                    if(this.prime == "radio"){
                        input.type = "radio" 
                    }else{
                        input.type = "checkbox"
                    }
                   
                    input.name = this.randId
                    input.id = id;
                    input.value = opcoes[i].valor
                    
                    var label = document.createElement("label")
                    label.classList.add("form-check-label")
                    label.setAttribute("for", id)
                    label.innerText = opcoes[i].nome
                    
                    opt.appendChild(input)
                    opt.appendChild(label)

                    break;
            }
          
                                    
                                    this.input.appendChild(opt)
                                    i++;
                                }
                                    
                                 this.valorProReset = this.input.value
                                 this.pegaValor();
                                 this.setTo(true);

                            })
                      }
                    
                    
                    break;
                }
                
                this.container.add(this)
                break;


            case 'switch':
                
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-check-input")
                this.input.type = "checkbox"
                this.input.setAttribute("role","switch")
                
                switch(parseInt(this.data("estilo", "tamanho", 0))){
                    case 1:
                        this.input.style = `width: 60px; height: 30px`
                        break;
                    case 2:
                         this.input.style = `width: 80px; height: 40px`
                        break;
                }
                
                
                

                this.container.add(this)
                this.pegaValor()
                break;
            case 'file':
                this.input = this.cardModelo.bind(this)();
                this.input.classList.add("d-none")

                
                this.input.getElementsByTagName("h3")[0].innerText = this.data("card", "card-titulo" , "")
                this.input.setAttribute("data-card", "destaque");
                
                
 
                if(!this.data("card", "recolhivel", false)){
                     this.input.getElementsByClassName("card-header")[0].getElementsByTagName("button")[0].remove();
                }
                 
                 
                if(this.data("card", "desabilitar-card", false)){
                    this.input.getElementsByClassName("card-header")[0].remove();
                    this.input.getElementsByClassName("card-footer")[0].remove();
                    this.input.getElementsByClassName("card-body")[0].classList.add("p-0")
                }
                
                 var arredondado = false;
                if(this.data("basico", "layout", false) && !this.data("basico", "multiplo", false)){
                    this.input.classList.add("rounded-circle")
                    arredondado = true;
                }
                
                let cropper = {
                    ativo: this.data("cropper", "recortar", false),
                    largura: this.data("cropper", "largura", false),
                    altura: this.data("cropper", "altura", false),
                }
                
             
                var multiplo = this.data("basico", "multiplo", false) == false ? false : true;
                var config  = {
                    multiple : multiplo,
                    arredondado: arredondado,
                    "texto-upload": this.data("basico", "texto-upload", false) ?? "Arraste ou Solte seu Arquivo aqui",
                    modulo: this?.container?.brain?.modulo ?? false,
                    cropper: cropper
                }
         
                
                this.destaque = new ImagemNown(this.input, this.container, config);
                this.destaque.render();
                
        
                
               
                this.destaque.biblioteca();
               // this.container.set("tagDestaque", this.destaque)
                this.pegaValor();
                this.container.addFiles(this)
                break;
            case 'repetidor':

                var repetidor = new Repetidor(this.item.filhos, this.container, this.item.infos?.callback || {});
                this.input = repetidor.render(this.item.infos);
                this.container.setRepetidores(this.item.hash, repetidor)
                
                if(this.container.memoria && this.container.memoria[this.item.hash]){
                    repetidor.set(this.container.memoria[this.item.hash])   
                }
                break;
   
            case 'data':
                
                 this.input = document.createElement("INPUT")
                 
              
                switch(parseInt(this.data("basico", "dados", 0))){
                    case 0:
                        this.input.type = "date"
                        break;
                    case 1:
                        this.input.type = "datetime-local"
                        break;
                    case 2:
                        this.input.type = "time"
                        break;
                }
                
               
                this.input.classList.add("form-control")
                
                this.container.add(this)
                this.pegaValor()
                break;
            case 'password':
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.input.type = "password"
                this.container.add(this)
                break;
            case 'range':
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-range")
                this.input.type = "range"
                this.container.add(this)
                break;
            case 'hidden':
                this.input = document.createElement("INPUT")
                this.input.classList.add("form-control")
                this.input.type = "hidden"
                this.container.add(this)
                break;
            case 'card':
                this.input = document.createElement("div")
                this.input.classList.add("card", "border-0", "shadow-sm");
                
                this.header = document.createElement("div")
                this.header.classList.add("card-body","d-flex","justify-content-between","align-items-center")
                
                var h3 = document.createElement("H3")
                h3.classList.add("fs-16","text-uppercase","m-0","fw-700","text-contrast")
                h3.innerText =  this.data("basico", "label");
                
                
                var btnOpen = document.createElement("BUTTON")
                btnOpen.classList.add("btn","btn-sm","seta")
                btnOpen.innerHTML = `<i class="bi bi-caret-down-fill text-contrast"></i>`
                this.header.appendChild(h3)
                
                
                
                if(this.data("basico", "recolhivel", 0)){
                    
                    let id = geraId();
                    btnOpen.setAttribute("data-bs-toggle","collapse")
                    btnOpen.setAttribute("data-bs-target", `#${id}`)
                    btnOpen.setAttribute("role","button")
                    btnOpen.setAttribute("aria-expanded","true")
                    btnOpen.setAttribute("aria-controls", "collapseExample")
                    
                    this.header.appendChild(btnOpen)
                    
                    var colapso = document.createElement("DIV")
                    colapso.classList.add("collapse", "show")
                    colapso.id = id;
                    
                     
                     
        
                     this.corpo = document.createElement("DIV")
                     this.corpo.classList.add("card-body", "border-top")
                     colapso.appendChild(this.corpo)
                    
                    
                    
                }else{
                    
                     this.corpo = document.createElement("DIV")
                     this.corpo.classList.add("card-body", "border-top")
                     let colapso = false;
                }
                
                if(this.data("basico", "label", false)){
                    this.input.appendChild(this.header)
                }
               
                break;
            case 'grupo':
                this.input = document.createElement("div")
                this.input.classList.add("grupo", "border-0");
                
                

                
                break;
            case 'html':
                
               
                this.input = document.createElement("DIV")
               
                if(this.data("codigo", "html", false)){
                    var divHTML = document.createElement("DIV")
                    if(this.data("basico", "id-da-div", false)){
                    divHTML.id = this.data("basico", "id-da-div", false).trim();
                    divHTML.innerHTML = this.data("codigo", "html", false);
                        
                    }
                    
                    this.input.appendChild(divHTML)
                }
                
                break;
            case 'tab':
                
                this.input = document.createElement("DIV")
                this.input.classList.add("nav","nav-tabs")
                this.container.tabs = new TabAcao(this.input, this.item);
            
                break;
            case 'titulo':
                this.input = document.createElement("h2")
                
                var titulo = this.data("basico", "titulo", false);
                var subtitulo = this.data("basico", "subtitulo", false);
                var tag = this.data("basico", "tag-superior", false);
                
                var html = document.createElement("DIV")
                
                if(tag){
                    var span = document.createElement("SPAN")
                    span.classList.add("fs-14")
                    span.innerText = tag
                    html.appendChild(span)
                }
                if(titulo){
                    var h2 = document.createElement("H2")
                    h2.classList.add("fs-16", "fw-700")
                    h2.innerText = titulo
                    html.appendChild(h2)
                }
                if(subtitulo){
                    var h3 = document.createElement("H3")
                    h3.innerText = subtitulo
                    html.appendChild(h3)
                }

                this.input.appendChild(html)
                break;
            case 'botao':
              
                
                this.input = document.createElement("BUTTON")
                
                if(this.item?.infos?.basico?.label || false){
                    this.input.innerText = this.item.infos.basico.label;
                }
                this.input.classList.add("btn")
        
                if (this.data("basico","callback",false) && this.data("basico","callback",false).trim()){
                    this.input.addEventListener("click", () => {
                        eval(this.item.infos.basico.callback.trim())();
                    });
                }
                
                
                if(parseInt(this.data("estilo","largura",0)) > 0){
                    this.input.classList.add("d-block", `w-${this.item.infos.estilo.largura}`)
                }
                
                switch(this.data("estilo","tamanho")){
                    case 1:
                        this.input.classList.add("btn-sm")
                        break;
                    case 2:
                             this.input.classList.add("btn-lg")
                        break;
                }
          
                var cor = this.data("estilo","cor", "primary");
                 this.input.classList.add(`btn-${cor.trim()}`)
                
                break;
            case 'coluna':
                this.input =  document.createElement("DIV")
                this.input.classList.add("row")

                break;
            case 'col':
                var size = this.data("layout", "tamanho", 6);
                this.input = div
                this.input.classList.add("col-12", `col-xl-${size}`)
                break;
            case 'cardSalvar':
                this.input = this.cardModelo.bind(this)();

          
                
                
                this.input.setAttribute("data-card", "cardSalvar")
                this.input.getElementsByTagName("h3")[0].innerText = "Salvar"
                this.salvar = new CardSalvar(this.input, this.container, this.item);
                if(this.data("call-backs", "sucesso-atualizar", false) && this.data("call-backs", "sucesso-atualizar", false).trim()){
                      this.salvar.define("onUpdate", this.data("call-backs", "sucesso-atualizar", false).trim());
                }
                
                if(this.data("call-backs", "sucesso-salva", false) && this.data("call-backs", "sucesso-salva", false).trim()){
                    this.salvar.define("onSave", this.data("call-backs", "sucesso-salva", false).trim());
                }
                
                if(this.data("call-backs", "erro", false) && this.data("call-backs", "erro", false).trim()){
                    this.salvar.define("onErro", this.data("call-backs", "erro", false).trim());
                }
                
                
                
                this.container.set("cardSalvar", this.salvar)
                if(this.data("basico", "fixar-no-rodape-mobile", false)){
                   this.salvar.setFixed("mobile")
                }
                
                if(this.data("basico", "fixar-no-rodape-pc", false)){
                    this.salvar.setFixed("pc")
                }
                
                
                
                this.salvar.render();
                if(this.container.memoria.systemRules){
                    this.salvar.set(this.container.memoria.systemRules)
                }
                break;
            case 'preco':
                this.input = this.cardModelo.bind(this)();
                this.input.setAttribute("data-card", "preco")
                this.input.getElementsByTagName("h3")[0].innerText = "Cobrança"
                
             
                
                var precificador = new PrecificadorBox(this.item, this.input);
                precificador.render()

                if(this.container.memoria && this.container.memoria.especiais && this.container.memoria.especiais.vendavel){
                    precificador.set(this.container.memoria.especiais.vendavel);
                }
                
                this.container.setPreco(precificador);
                
                break;
            case 'categoria':
                 this.input = this.cardModelo.bind(this)();

                this.categoria = new BoxCategoria(this.input, true, this.container);
                this.categoria.render();
                this.categoria.request();
                this.container.set("tagCategoria", this.categoria)
              
                
                
                this.input.getElementsByTagName("h3")[0].innerText = "Categoria"
                this.input.setAttribute("data-card", "categoria")
                break;
            case 'tags':
                this.input = this.cardModelo.bind(this)();
                
                this.tags = new BoxCategoria(this.input, false, this.container);
                this.tags.render();
                this.tags.request();
                this.container.set("tagTags", this.tags)
                
                this.input.getElementsByTagName("h3")[0].innerText = "Tags"
                this.input.setAttribute("data-card", "tags")
                break;
            case 'imagemDestaque':

                this.input = this.cardModelo.bind(this)();
                this.input.getElementsByTagName("h3")[0].innerText = "Imagem Destaque"
                this.input.setAttribute("data-card", "destaque");
                var config = {
                    multiple : false,
                    accept: "image/*",
                    modulo: this?.container?.brain?.modulo ?? false
                }
                this.destaque = new ImagemNown(this.input, this.container, config);
                this.destaque.render();
                if(this.container.memoria && this.container.memoria.especiais.imagem){
                    this.destaque.set(this.container.memoria.especiais.imagem)
                }
                
                this.destaque.biblioteca();
                this.container.set("tagDestaque", this.destaque)
                break;
            case 'galeria':
                this.input = this.cardModelo.bind(this)();
                this.input.getElementsByTagName("h3")[0].innerText = "Galeria"
                this.input.setAttribute("data-card", "galeria")
                var config = {
                    multiple : true,
                    accept: "image/*",
                    modulo: this?.container?.brain?.modulo ?? false
                }
                this.galeria = new ImagemNown(this.input, this.container, config);
                this.galeria.render();
                if(this.container.memoria && this.container.memoria.especiais.galeria){
                    this.galeria.set(this.container.memoria.especiais.galeria)
                }
                this.galeria.biblioteca();
                this.container.set("tagGaleria", this.galeria)
                break;
            case 'seo':
                this.input = this.cardModelo.bind(this)();
                this.seo = new BoxSeo(this.input, this.container);
                this.seo.render();
                this.container.set("tagSeo", this.seo)
                this.input.getElementsByTagName("h3")[0].innerText = "SEO"
                this.input.setAttribute("data-card", "seo")
                break;
            case 'rich':
                this.input = document.createElement("DIV")
                this.input.style.height = "400px"
                break;
            case 'enderecos':
                
          
            
                var multiplo = parseInt(this.infos?.basico?.multiplo || 0); 
                this.input = this.cardModelo.bind(this)();
                this.input.setAttribute("data-card", "enderecos")
                this.input.getElementsByTagName("h3")[0].innerText = !multiplo ? "Endereço" : "Endereços"
                this.input.getElementsByTagName("button")[0].remove();
                var body = this.input.getElementsByClassName("card-body")[0];
                body.classList.add("listaEnderecos")
                
                
                
                 this.endereco = new EnderecoControler(this.container.memoria?.especiais?.endereco || 0, this.input, this.container, multiplo);
                if(multiplo && this.data("basico", "criar-novo", false)){
                     var button = document.createElement("BUTTON")
                     button.innerHTML = `<i class="bi bi-geo-alt"></i> Novo Endereço`
                     button.classList.add("btn", "btn-n-primaria")
                     this.input.getElementsByClassName("card-header")[0].getElementsByClassName("d-flex")[0].appendChild(button)
                     var modal = new ModalForm(button , "enderecos", "0zF2dvdEctsSxfk"); 
                     modal.setCb((r)=>{
                         this.endereco.associaEndereco(r);
                     })
                 }
                
                body.innerHTML = `
                <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
                </div>
                `
                
               
                this.endereco.listaEnderecos();
                
                this.container.set("endereco", this.endereco)
                
                break;
            case 'atributos':
                var atributos = new AtributosControler(this);
                this.input = atributos.render();
                this.container.setAttributos(atributos)

                break;
            default:
                console.log(this.item)
                break;
        }
        
        if(this.data("basico", "somente-leitura", false)){
                   this.input.setAttribute("readonly", "")
                   this.input.setAttribute("disabled", "")
             }

        if(this.input){
            this.input.id = randomId;
        }
        
        var invalid = document.createElement("DIV")
        invalid.classList.add("invalid-feedback")

        
        var valid = document.createElement("DIV")
        valid.classList.add("invalid-feedback")

        this.container.allItems(this);
        
        if(this.data("nown", "ativo", false)){
            var modulo = this.data("nown", "modulo", false)
            var paginacao = this.data("nown", "paginacao", false)
            var chave = this.data("nown", "chave", false)
            var valor = this.data("nown", "valor", false)
            
            if(paginacao && chave && valor){
                 configModulo(modulo.trim() , paginacao.trim(), chave.trim(), false).then((r)=>{
                console.log(valor, r)
                if(valor != r){
                    if(this.divFinal){
                        this.divFinal.classList.add("d-none")
                    }
                }else{
                    console.log("mostra")
                }
            })
            }else{
                console.log("Verifique o setup do Nown")
            }
           
            
        
        }
        
        this.divFinal = false;

        switch(this.item.tipo){
            case 'atributos':
                return this.input;
                break;
            case 'destaque':
                return div;
                break;
            case 'card':
                var form = new FormularioRenderizado(this.item.filhos, this.corpo, this.key, this.container)
                form.processa();
                if(colapso){
                     this.input.appendChild(colapso)
                }else{
                     this.input.appendChild(this.corpo)
                }
                div.appendChild(this.input)
                
                if(this.data("flex", "ativar", false)){
                     this.flexbiliza.bind(this)();
                 }
                
                this.divFinal = div;
                return div;
          
                break;
            case 'atribuidor':
            case 'subconta':
                return this.input;
                break;
            case 'formprogress':
                return div;
                break;
            case 'grupo':
                var form = new FormularioRenderizado(this.item.filhos, this.input, this.key, this.container)
                form.processa();
                div.appendChild(this.input)
                
                
                 if(this.data("flex", "ativar", false)){
                     this.flexbiliza.bind(this)();
                 }
                
                
                
                return div;
                break;
            case 'col':
            case 'coluna':
                var form = new FormularioRenderizado(this.item.filhos, this.input, this.key, this.container)
                form.processa();
                
                 if(this.data("flex", "ativar", false)){
                     this.flexbiliza.bind(this)();
                 }
                
                
                return this.input
                break;
            case 'botao':
            case 'html':
            case 'titulo':
                
                if(this.data("basico", "id", false)){
                    this.input.id = this.data("basico", "id", false).trim();
                }
                
                
                
                div.appendChild(this.input)
                return div
                break;
            case 'switch':
  
                var flex = document.createElement("DIV")
                flex.classList.add("d-flex", "align-items-center", "gap-3")
                
                var descricao = this.data("basico","descricao", '').trim() ? this.data("basico","descricao", 0) : false;
                
                if(descricao){
                   var divDescricao = document.createElement("DIV")
                   divDescricao.classList.add("fs-12", "mt-1")
                   divDescricao.innerText  = descricao
                }
            
                var linha = document.createElement("DIV")
                linha.classList.add("form-check","form-switch")
                
                  linha.appendChild(this.input)
                
                
                var textos = document.createElement("DIV")

                  if(label){
                     this.label.classList.add("form-check-label")
                     this.label.innerText = label
                     textos.appendChild(this.label)
                }
                
                 if(descricao){
                    textos.appendChild(divDescricao)  
                }
                
       
               switch(parseInt(this.data("estilo","justificar", 0))){
                   case 0:
                       flex.classList.add("justify-content-start")
                       break;
                   case 1:
                       flex.classList.add("justify-content-end")
                       break;
                   case 2:
                       flex.classList.add("justify-content-between")
                       break;
               }
               
               
               if(parseInt(this.data("estilo","ordem", 0)) == 0){
                     flex.appendChild(linha)
                     flex.appendChild(textos)
               }else{
                     flex.appendChild(textos)
                     flex.appendChild(linha)
               }
             
               
            
      
                div.appendChild(flex)

                        
           
                        
                        div.appendChild(invalid)
                        div.appendChild(valid)
                
                return div;


                
                break;
            case 'rich':
                if(label){
                    div.appendChild(this.label)
                }
                div.appendChild(this.input)
                this.container.rich(this.input, this.item.hash)
                return div
                break;
            case 'tabela':
                    return div;
                     break;
            case 'autoria':
                if(this.container.brain.editMode && parseInt(infoUser().funcao) < 2){
                    return this.input;
                }else{
                    return false;
                }
                break;
            case 'wildcard':
                if(dataModule("cae0206c31eaa305dd0e847330c5e837")){
                    this.container.wildcard(this);
                    return div;
                }else{
                    return false;
                }

                break;
            case 'tab':
            case 'cardSalvar':
            case 'categoria':
            case 'tags':
            case 'imagemDestaque':
            case 'galeria':
            case 'seo':
            case 'file':
            case 'preco':
            case 'repetidor':
            case 'espaco':
      
            
                div.appendChild(this.input)
                this.divFinal = div;
                return div
                break;
            case 'video':
            case 'url':
            case 'mapa':
                  var descricao = this.data("basico","descricao", '').trim() ? this.data("basico","descricao", 0) : false;
                if(descricao){
                   var divDescricao = document.createElement("DIV")
                   divDescricao.classList.add("fs-12", "mt-1")
                   divDescricao.innerText  = descricao
                }
                
                
                var icone = document.createElement("I")
                icone.classList.add("position-absolute","top-50", "translate-middle-y")
                
                
                
                switch(this.item.tipo){
                    case 'video':
                        icone.classList.add("bi","bi-play-circle")
                        break;
                    case 'url':
                        icone.classList.add("bi","bi-link-45deg")
                        this.input.classList.add("mascaraInput")
                        this.input.dataset.mascara = 15;
                        break;
                    case 'mapa':
                        icone.classList.add("bi","bi-geo-alt")
                        break;
                }
                
                icone.style.left = "15px"
                this.input.style.paddingLeft = "40px"
                
                
                
      
                
                var capsula = document.createElement("DIV")
                capsula.classList.add("position-relative")
                capsula.appendChild(this.input)
                
                capsula.appendChild(icone)
                
                          
                        capsula.appendChild(invalid)
                        capsula.appendChild(valid)
                
       
                     if(label){
                            this.label.classList.add("d-block")
                            div.appendChild(this.label)
                        }
                        
                        div.appendChild(capsula)
                        
                        if(descricao){
                          div.appendChild(divDescricao)  
                        }
                        
                    
                    if(this.data("visualizacao", "previa", false)){
                          var previa = document.createElement("DIV")
                          this.divPrevia = previa
                          div.appendChild(previa)
      
                          var debouncedValidaPrevia = debounce(() => {
                              validaPrevia(this);
                          }, 500); 
                          evento(this.input, "input", debouncedValidaPrevia);
                    }
                        
                 
              
                
                return div;
                break;
            case 'enderecos':
  
                div.appendChild(this.input);
                return div;
                break;
            default:
            
              
                var descricao = this.data("basico","descricao", '').trim() ? this.data("basico","descricao", 0) : false;
                if(descricao){
                   var divDescricao = document.createElement("DIV")
                   divDescricao.classList.add("fs-12", "mt-1")
                   divDescricao.innerText  = descricao
                }
            
                switch(parseInt(this.data("estilo","estilo", 0))){
                    case 0:
                     
                        
                        if(label){
                            this.label.classList.add("d-block")
                            div.appendChild(this.label)
                        }
                        
                        if(this.input){
                            div.appendChild(this.input)
                        }
                        
                        
                        
                        if(descricao){
                          div.appendChild(divDescricao)  
                        }
                        
                        div.appendChild(invalid)
                        div.appendChild(valid)
                        break;
                    case 1:
                       
                      
                        div.classList.add("row")
                        
                        var containerInput = document.createElement("DIV")
                        containerInput.classList.add("col-12", "col-lg-8")
  
                        containerInput.appendChild(this.input)
                        
                        
                         if(descricao){
                          containerInput.appendChild(divDescricao)  
                        }
                        
                        containerInput.appendChild(invalid)
                        containerInput.appendChild(valid)
                        
                        if(label){
                            this.label.classList.add("col-12", "col-lg-4")
                            div.appendChild(this.label)
                        }
                       
                        div.appendChild(containerInput)
                        
                    
                        break;
                    case 2:

                        this.input.type = "text"
                        div.classList.add("form-floating")
                        if(label){
                            div.appendChild(this.label)
                        }
                        
                        div.appendChild(this.input)
                        
                         if(descricao){
                          div.appendChild(divDescricao)  
                        }
                        
                        div.appendChild(invalid)
                        div.appendChild(valid)
                    
                        
                        break;
                }
                
                
                if(this.data("tamanho", "ativar", false)){
                    var contador = document.createElement("DIV")
                    contador.classList.add("position-absolute", "top-0", "bg-danger", "text-center", "d-none", "animate__fadeIn", "animate__animated",  "fs-12", "py-1", "text-light")
                    contador.style = "right:20px; border-radius: 10px 10px 0px 0px; min-width: 70px";
                    this.sizer = contador
                    evento(this.input, "input", this.tamanhador.bind(this))
                    contador.innerText = 1
                    div.classList.add("position-relative")
                    div.appendChild(contador)
                }
                
                let esquerda = false;
                if(this.data("label", "prefixo", false) && this.data("label", "prefixo", false).trim()){
                    esquerda = true;
                    var pai = document.createElement("DIV")
                    pai.classList.add("input-group")
                    
                    var txtPrefixo = this.data("label", "prefixo", false)
                    var span = document.createElement("SPAN")
                    span.classList.add("input-group-text")
                    span.id = geraId();
                    span.innerHTML = txtPrefixo
                    pai.appendChild(span)
                    
                    
                    pai.appendChild(this.input)

                    div.appendChild(pai)

                }
                
                if(this.data("label", "sufixo", false) && this.data("label", "sufixo", false).trim()){
                    if(!pai){
                        var pai = document.createElement("DIV")
                        pai.classList.add("input-group")
                        pai.appendChild(this.input)
                        div.appendChild(pai)
                    }
                    
                    let txtSufixo = this.data("label", "sufixo", false);
                    var span = document.createElement("SPAN")
                    span.classList.add("input-group-text")
                    span.id = geraId();
                    span.innerHTML = txtSufixo
                    pai.appendChild(span)

                }
                
                
                if(this.data("basico", "botao-ver", false)){
                     var pai = document.createElement("DIV")
                     pai.classList.add("position-relative")
                     pai.appendChild(this.input)
                     
                     var btn = document.createElement("BUTTON")
                     btn.classList.add("position-absolute", "top-50", "translate-middle-y", "btn")
                     btn.style.right = "10px"
                     btn.innerHTML = '<i class="bi bi-eye"></i>'
                     evento(btn, "click", this.showPass.bind(this))
                     pai.appendChild(btn)
                     
                     div.appendChild(pai)
                     
                }
                
                
                /*
                <div class="input-group mb-3">
  <span class="input-group-text" id="basic-addon1">@</span>
  <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
</div>
*/
                
                
                return div;
                
                break;

        }
        
    }

    trigger(){
        var valor = event.currentTarget.value;
   
        
        
        if(this.data("dinamica", "url-trigger", false)){
            let request = new Request(this.data("dinamica", "url-trigger", false));
            let data = {}
            data.valor = valor
            
            if(this.data("dinamica", "acao-trigger", false)){
                data.acao = this.data("dinamica", "acao-trigger", false).trim();
            }
            this.input.innerHTML = "";
            this.lequeOpts();
            request.addData(data)
            request.send().then((r)=>{
             var lista = r.lista
                var i = 0;
                while(i < lista.length){
                                    var item = lista[i]
          
                                    var opt = document.createElement("OPTION")
                                    opt.value = item["v"]
                                    opt.innerText = item["t"]
                                    if(item["c"]){
                                        opt.setAttribute("selected", "");
                                    }
                                    this.input.appendChild(opt)
                                    i++;
                                }
                
                if(this.input.dataset.valor){
                    this.input.value = this.input.dataset.valor
                    const event = new Event('input');
                    this.input.dispatchEvent(event);
                }
                
            })
            
            
        }
        
    }
    
    showPass(){
            var btn = event.currentTarget
            if(this.input.type == "password"){
                this.input.type = "text"
                btn.innerHTML = `<i class="bi bi-eye-slash"></i>`
            }else{
                this.input.type = "password"
                btn.innerHTML = `<i class="bi bi-eye"></i>`
            }
        }
    
    flexbiliza(){
        var direcao = this.data("flex", "direcao", 1);
        var justify = this.data("flex", "justify", 0);
        var align = this.data("flex", "align", 0);
        var gap = this.data("flex", "gap", 0);
        
        if(direcao == 1){
            this.input.classList.remove("flex-column")
        }
        
        if(justify != 0){
            this.input.classList.add(`justify-content-${justify}`.trim())
        }
        
        if(align != 0){
            this.input.classList.add(`align-items-${align}`.trim())
        }
        
        if(gap != 0){
            this.input.classList.add(`g-${gap}`.trim())
        }


        
    }
    
    tamanhador(){
        if(!this.input.value){
            this.sizer.classList.add("d-none")
        }else{
            var maximo = this.data("tamanho", "maximo", false);
            var minimo = this.data("tamanho", "minimo", false);
            
            this.sizer.classList.remove("d-none")
            
  
        if (maximo && this.input.value.length > maximo) {
            this.input.value = this.input.value.substring(0, maximo);
        }
            
            
            
            let txtmaximo;
            if(maximo){
                txtmaximo = `/ ${maximo}`
            }
            
            var texto = `${this.input.value.length} ${txtmaximo}`;
            this.sizer.innerText = texto.trim();
        }

    }
    
    reset(){
        this.input.value = this.valorProReset || "";
    }
}

function debounce(func, wait) {
    let timeout;
    return function (...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

function isValidUrl(string) {
    try {
        let url = new URL(string);
        return url.protocol === "http:" || url.protocol === "https:";
    } catch (_) {
        return false;
    }
}

function validaPrevia(obj) {
    var infos = obj.item.infos;
    var previa = obj.divPrevia;
    var valor = obj.input.value


    if (!valor) {
        previa.innerText = "";
    } else {
        switch (obj.item.tipo) {
            case 'video':
                previa.innerHTML = `
                    <div class="ratio ratio-16x9 mt-2">
                        <iframe src="${valor}" title="YouTube video" allowfullscreen></iframe>
                    </div>`;
                break;
            case 'url':
                previa.innerHTML  = "";
                 if (isValidUrl(valor)) {
                
                    
                    var div = document.createElement("DIV")
                    div.classList.add("ratio","ratio-16x9","mt-2")
                    
                    var iframe = document.createElement("IFRAME")
                    iframe.src = valor
                    iframe.setAttribute("allowfullscreen", "");
                    div.appendChild(iframe)
    

                    iframe.onload = ()=> {
                        console.log("carregou")
                    };
                    iframe.onerror = ()=> {
                        console.log("Erro ao carregar o iframe");
                        previa.innerText = "Erro ao carregar a URL.";
                    };
                    
                    previa.appendChild(div)
                } else {
                    previa.innerHTML = "";
                    invalido(obj.input, false , "A URL digitada não é valida");
                }
                break;
            case 'mapa':
                previa.innerHTML = `
                    <div class="ratio ratio-16x9 mt-2">
                        <iframe src="${valor}" allowfullscreen></iframe>
                    </div>`;
                break;
            default:
                previa.innerText = "Tipo não suportado.";
        }
    }
}

class Rule{
    constructor(div, logica, irmas){
        this.div = div
        this.logica = logica
        this.irmas = irmas
        this.render.bind(this)();
    }
    
    render(){
        var logicas = this.logica.logicas
        
        
        var i = 0;
        while(i < logicas.length){
            var logica = logicas[i]

            var hash = logica.i

            if(this.irmas[hash]){
                var verificador = this.irmas[hash]
                evento(verificador.input, "input", this.valida.bind(this))
            }
   
            
            i++;
        }
        
        this.valida.bind(this)()
    }
    
    valida() {
    var mostrar = this.logica.mostrar == 1 ? true : false;
    var todas = this.logica.todas == 1 ? true : false;

    var logicas = this.logica.logicas;
    var i = 0;
    var total = logicas.length;
    var pnts = 0;

    while (i < logicas.length) {
        var logica = logicas[i];
        var hash = logica.i;
        var regra = logica.r;
        var valor = logica.v;

        var verificador = this.irmas[hash];
        var inputValor = verificador.input.value
   
        if(verificador.item.tipo == "switch"){
            inputValor = verificador.input.checked  ? "ativo" : "desativado";
           
        }
  
        switch (parseInt(regra)) {
            case 0:
                // é
                if (inputValor == valor) {
                    pnts++;
                }
                break;
            case 1:
                // não é
                if (inputValor != valor) {
                    pnts++;
                }
                break;
            case 2:
                // estiver vazio
                if (!inputValor) {
                    pnts++;
                }
                break;
            case 3:
                // Não estiver vazio
                if (inputValor) {
                    pnts++;
                }
                break;
            case 4:
                // For Maior que
                if (inputValor > valor) {
                    pnts++;
                }
                break;
            case 5:
                // Form Menor que
                if (inputValor < valor) {
                    pnts++;
                }
                break;
            case 6:
                // Conter
                if (inputValor.includes(valor)) {
                    pnts++;
                }
                break;
            case 7:
                // Começar com
                if (inputValor.startsWith(valor)) {
                    pnts++;
                }
                break;
            case 8:
                // Finalizar com
                if (inputValor.endsWith(valor)) {
                    pnts++;
                }
                break;
        }

        i++;
    }
    
    
    if(mostrar){
        if(total == pnts){
            this.div.classList.remove("d-none")
        }else{
            if(!todas && parseInt(pnts) > 0){
                this.div.classList.remove("d-none")
            }else{
                 this.div.classList.add("d-none")
            }
        }
    }
    
    if(!mostrar){
        if(total == pnts){
            this.div.classList.add("d-none")
        }else{
             if(!todas && parseInt(pnts) > 0){
                 this.div.classList.add("d-none")
            }else{
                this.div.classList.remove("d-none")
            }
        }
    }

  
   
}

    
}

class FormularioRenderizado extends CicloVida{
    constructor(obj, pai, key, renderPai = false, brain = false){
        super();
        this.renderPai = !renderPai ? this : renderPai
        this.obj = obj
       
        this.pai = pai
        this.pai.innerHTML = "";
        this.key = key
        this.brain = brain
        this.files = {}
        this.repetidores = {};
        
        if(this.brain && this.brain.dados){
            this.edit = this.brain.editMode
            this.memoria = this.brain.dados;
        }else{
            this.memoria = false;
            this.edit = false;
        }
        
        
         this.data = this.data.bind(this)
         this.ajax = this.ajax.bind(this)
        
        if(!this.pai.classList.contains("row")){
             this.pai.classList.add("d-flex", "flex-column", "gap-4")
        }
      
        this.itens = {};
        this.todos = {};
        
        this.tagDestaque = false;
        this.tagGaleria = false;
        this.tagCategoria = false;
        this.tagTags = false;
        this.tagSalvar = false;
        this.tagSeo = false;
        
        this.ricos = {};
       
    }
    
    reset(){
        for(let c in this.itens){
            this.itens[c].reset();
        }
        
        if(this.tabs){
            if(this.progressControl){
                 this.progressControl.reset();
            }else{
                this.tabs.reset();
            }
        
        
            
            
            
        }
        
        
        
    }
    
    setRepetidores(hash, item){
        this.repetidores[hash] = item
        
    }
    
    addFiles(item){
        this.files[item.item.hash] = item
    }
    
    setPreco(r){
        this.vendavel = r
    }
    
    setAttributos(input){
        this.atributos = input 
    }
    
    set(ref, comp){
        this[ref] = comp;
    }
    
    data(item, grupo, chave, retorno){
        var item = item.item
        return item.infos[grupo] && item.infos[grupo][chave] ? item.infos[grupo][chave] : retorno
    }
    
    allItems(item){

            if(!this.todos[item.item.hash]){
               this.todos[item.item.hash] = item
            }else{
               
            }

          if(Object.keys(this.todos).length == this.tamanho){
              this.amarra.bind(this)()
          }
          
          
    }
    
    amarra(){
        if(document.getElementsByClassName("inputCor")){
               //cahmar biblioteca de cor
        }
     
        for (let chave in this.todos) {
            var entrada = this.todos[chave]
            
            
            

            if(this.data(entrada, "logica", "logica", 0)){
                if(entrada.div){
                    new Rule(entrada.div, entrada.item.infos.logica.setup, this.todos)
                }
                
            }
        }
        
        var two = document.querySelectorAll('.form-select[select2="true"]');
        
        
     
        setTimeout(()=>{
            new Mascaras();
        }, 200)
        
        if(this.jsCall){
             this.jsCall.pai.loadscript(this.jsCall.obj)
        }
        

    }
    
    rich(div, hash) {
        
         let array = [
            `${dominio}/assets/aplicativo/quill/min.js`,
            `${dominio}/assets/aplicativo/quill/min.css`,
            ];
        

         nownFiles.add(array).then(()=>{
            this.ricos[hash] = new Rich(div, this.brain.modulo);
          if(this.memoria && this.memoria[hash]){
         this.ricos[hash].set(this.memoria[hash])
    }
         })
        
        
        
 
}

    getMultiplicador(){
        console.log(this.itens)
    }
    
    get(info = false){
    
        var data = {};
        var auth = true;
        var camposobrigatorios = [];
        var camposInvalidos = [];
        
        if(info){
            data["systemRules"] = info
        }
        
     
        console.log(this.itens)
        for (let chave in this.itens) {
            var item = this.itens[chave]
            var input = item.input
            var item = item.item
            
     
          
            if(item.obrigatorio && parseInt(item.obrigatorio) == 1){

                if(!input.value){
                    item.input = input
                    camposobrigatorios.push(item)
                    auth = false;
                }else{
                    var mascara = item.infos?.validacao?.mascara || false
                    if(mascara){
                        
                        const masks = {
                            1: ['000.000.000-00'],
                            2: ['00.000.000/0000-00'],
                            3: ['00000-000'],
                            4: ['?(00) 0000-0000'],
                            5: ['(00) 0000-0000'],
                            6: ['(00) 00000-0000'],
                            7: ['00/00/0000'],
                            8: ['00:00'],
                            9: ['00/00/0000 00:00:00'],
                            10: ['0.00'],
                            11: ['##0.00'],
                            12: ['#.##0,00', true],
                            13: ['##0,00%'],
                            14: ['000 0000 0000 0000'],
                            15: "url"
                        };
                        
                         item.input = input
                        switch(parseInt(mascara)){
                            case 1:
                                var valida = new ValidaInfo(input.value, "cpf");
                                if(!valida.valida()){
                                       camposInvalidos.push(item)
                                       auth = false;
                                }
                                break;
                            case 2:
                                var valida = new ValidaInfo(input.value, "cnpj");
                                if(!valida.valida()){
                                        camposInvalidos.push(item)
                                       auth = false;
                                }
                                break;
                            case 3:
                                var valida = new ValidaInfo(input.value, "cep");
                                if(!valida.valida()){
                                        camposInvalidos.push(item)
                                       auth = false;
                                }
                                break;
                            case 4:
                            case 5:
                            case 6:
                                var valida = new ValidaInfo(input.value, "telefone");
                                if(!valida.valida()){
                                        camposInvalidos.push(item)
                                       auth = false;
                                }
                                break;
                            case 7:
                                break;
                            case 8:
                                break;
                            case 9:
                                break;
                            case 15:
                                break;
                        }
                        
                        
        
                     
                    }
                    
                    
                }
                
                if(item.tipo == "select" && input.value == "0"){
                    item.input = input
                    camposobrigatorios.push(item)
                    auth = false;
                }

            }
          
            switch(item.tipo){
                case 'select':
                    
                    var render = parseInt(item.infos?.basico?.render || 0);
                    
                    if(render == 2){
                        if(input.value){
                              var obj = JSON.parse(input.value)
                            if(input.multiple){
                                  
                            var r = [];
                            var k =  0;
                            while(k < obj.length){
                                r.push(obj[k].code)
                                k++;
                            }
           
                                  data[item.hash] =   JSON.stringify(r)
                             }else{
                                 data[item.hash] = obj[0].code;
                             }
                             
                             
                        
                        }else{
                             if(input.multiple){
                                  data[item.hash] = []
                             }else{
                                  data[item.hash] = "";
                             }
                           
                        }
                    }else{
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
                        data[item.hash] = input.value
                    } 
                    }
                   
                    break;
                case 'switch':
                     data[item.hash] = input.checked ? true : false;
                    break;
                case 'atribuidor':
                    data[item.hash] = item.atributor.get();
                    break;
                default:
                 data[item.hash] = input.value
                break;
            }
           
        };

        for(let chave in this.files){
            data[chave] = JSON.stringify(this.files[chave].destaque.get())
        }
        
       
       for(let chave in this.ricos){
           data[chave] = this.ricos[chave].get();
       }
       
        var chaves = {
            seo : "tagSeo",
            imagemDestaque: "tagDestaque",
            galeria: "tagGaleria",
            tags: "tagTags",
            categoria: "tagCategoria",
            endereco: "endereco",
            subconta: "subconta"
        }
        
        var especiais = {}
        for(let v in chaves){
            if(this[chaves[v]]){
                
                especiais[v] = this[chaves[v]].get();
            }
        }
        
        
        if(this.itemWildcard){
           especiais["wildcard"] = this.itemWildcard.input.value;
       }
       
       if(this.itemDestaque){
           especiais["destaque"] = this.itemDestaque.checked ? 1 : 0;
       }
        
        data["especiais"] = especiais;
        
        
        if(this.vendavel){
            data["vendavel"] = this.vendavel.get();
        }
     
        for(let c in this.repetidores){
            data[c] = this.repetidores[c].get();
        }
        
        
        if(this.atributos){
            data.atributos = this.atributos.get();
        }
        
        var obj = {
            data : data,
            auth: auth,
            atencao: camposobrigatorios,
            invalidos: camposInvalidos
        };
        
        
        
    
    
    
        return obj;
        
    }
    
    wildcard(item){
        this.itemWildcard = item
        
    }

   salvar = (info) => {

        var info = this.get.bind(this)(info);
        var data = info.data
        var auth = info.auth 
       
        if(auth){
            if(this.brain){
                if(this.intercipitaSalva){
                    this.intercipitaSalva(data);
                    return;
                }
                var req = new FormData();
                
                req.append("tipo", this.edit ? "editar" : "salvar")
                req.append("hash", this.edit)
                req.append("data", JSON.stringify(data))
                this.ajax(req, this.salvo.bind(this)).then(()=>{
                    
                }, (r)=>{
                    if(r.pontos){
                        var mensagem = "Você não tem permissão para fazer essa ação."
                    }else{
                        var mensagem = "Não foi possível salvar os dados no banco de dados";
                        var btns = document.getElementsByClassName("waitSave");
                    for (let c = 0; c < btns.length; c++) {
                        btns[c].removeAttribute("disabled");
                        btns[c].innerText = this.edit ? "Atualizar" : "Salvar";
                        
                    }

                    if(r.tipo){
                        switch(r.tipo){
                            case 'duplicado':
                                if(this.itens[r.chave]){
                                    invalido(this.itens[r.chave].input, false,  "Já existe um item com esse valor e ele não pode ser duplicado.")
                                    mensagem = `Já existe um item com o <u><strong>${this.itens[r.chave].item.nome}</strong></u> chamado <u><strong>${this.itens[r.chave].input.value}</strong></u> e não pode haver duplicidade`;
 
 
                                }
                                
                                break;
                            default:
                            
                        }
                    }
                    }
                    
                    
                    
                    
                     Swal.fire({
                            icon: "error",
                            title: "Atenção",
                            html: mensagem,
                            
                        });

                })
            }else{
                console.log("pronto para enviar", data)
            }
            
        }else{

            
            var lis = "";
            for(let c in info.atencao){
                var item = info.atencao[c]
                invalido(item.input, false, "O campo é obrigatório")

                lis = `<li class="list-group-item bg-transparent">${item.nome}</li>${lis}`
            }
            
            
            
       
            let html = "";
            if(info.atencao.length > 0){
                html = `
                <p>Existem Campos sem preencher:</p>
                    <ul class="list-group list-group-flush">
                        ${lis}
                    </ul>
                    `
            }
            
            
            var lis = "";
              for(let c in info.invalidos){
                 var item = info.invalidos[c]
                invalido(item.input, false, "O campo não é valido. Verifique os dados.")

                lis = `<li class="list-group-item bg-transparent"><u><strong>${item.nome}</strong></u> inválido</li>${lis}`
                
            }
            
    
            if(info.invalidos.length > 0){
                html = `${html}
                <p>Existem Campos inválidos:</p>
                    <ul class="list-group list-group-flush">
                        ${lis}
                    </ul>
                    `
            }
            
            
   
            
            Swal.fire({
                title: "Atenção",
                html: html,
  icon: "warning"
});
            
           var btns = document.getElementsByClassName("waitSave");
           for (let c = 0; c < btns.length; c++) {
    btns[c].removeAttribute("disabled");
    btns[c].innerText = this.edit ? "Atualizar" : "Salvar";
}
            
        }
        
        

    }
   
   posSucesso(cb){
       this.aposS = cb;
   }
   
   salvo(r){
       if(this.aposS){
           this.aposS(r)
           return;
       }

        if(r.sucesso){
            
           
            
            
            if(!this.edit){
                let link = window.location.href.replace(`${dominio}/`, "");
                goUrl(`${link}/${r.id}`);
                var texto = "Item criado com sucesso"
            }else{
                var texto = "Item atualizado com sucesso"
                
                setTimeout(()=>{
                   if (this.cardSalvar && this.cardSalvar.onUpdate && typeof window[this.cardSalvar.onUpdate] === 'function') {
                    window[this.cardSalvar.onUpdate]();
                    } 
                }, 200)
                
                
            }
            
            
                    
            var btns = document.getElementsByClassName("waitSave");
            for (let c = 0; c < btns.length; c++) {
    btns[c].removeAttribute("disabled");
    btns[c].innerText = this.edit ? "Atualizar" : "Salvar";
}
            
            
            Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    text: texto,
                    showConfirmButton: false,
                    timer: 1500
                });
            
        }
    
    }
   
   add(item){
        this.itens[item.item.hash] = item
    }
   
   filhos(){
        return this.itens;
    }

   size(r){
        this.tamanho = r
    }
    
   interpepta(cb){
       this.intercipitaSalva = cb
   }
    
   processa(){
        if(this.obj.length > 0){
            var i = 0;
            var fragmento = document.createDocumentFragment();
            while(i < this.obj.length){
         
                var item = this.obj[i]
                var input = new InputRenderizado(item, this.key, this.renderPai);
                var html = input.render();
                if(html){
                    fragmento.appendChild(html)
                }
                
                
                i++;
            }
            

            this.pai.appendChild(fragmento)
        }
    }
    
    setCb(cb){

        this.jsCall = cb;
    }
    
    apagar(){
              Swal.fire({
            icon: "question",
  title: "Tem certeza?",
  text: "Após confirmar , esse item será apagado totalmente do banco de dados.",
  showCancelButton: true,
  confirmButtonText: "Apagar",
  cancelButtonText: `Cancelar`
}).then((result) => {
  
  if (result.isConfirmed) {
       var req = new FormData();
       req.append("tipo", "apagar")
       req.append("hash", this.edit)
       this.ajax(req, this.apagado.bind(this))
  } 
});
    }
    
    apagado(){
        Swal.fire({
            icon: "success",
            title: "Apagado",
            showConfirmButton: false,
            timer: 1500
        });
        var tipo = this.brain.master ? "m" : "a";
        goUrl(`${tipo}/${this.brain.modulo}`)
    }

   ajax(data, cb) {
    return new Promise((resolve, reject) => {
        data.append("identificador", this.brain.identificador);
        data.append("modulo", this.brain.modulo);
        data.append("master", this.brain.master);

        let request = new XMLHttpRequest();
        request.onload = () => {
            try {
                var obj = JSON.parse(request.responseText);
                if (obj.sucesso) {
                    resolve(obj);
                } else {
                    reject(obj);
                }
            } catch (e) {
                reject(e);
            }
            if (cb) {
                cb(obj);
            }
        };
        request.onerror = (error) => {
            reject(error);
            if (cb) {
                cb({ erro: true, mensagem: "Erro na requisição AJAX" });
            }
        };

        request.open("POST", `${dominio}/admin/brain.php`);
        request.send(data);
    });
}
    
    
}

class RenderForm{
    constructor(pai = false, hash = false, dinamico = false){

        this.ajax = this.ajax.bind(this)
        this.pai = pai
        this.hash = hash
        
        
        /* A variavel dinamico irá decidir se ele irá consultar o formulário no banco de dados ou em uma estrutura de renderização 
        true = consultar em banco de dados
        false = capturar mais infos na div, para colegar o caminho
        
        */
        this.dinamico = dinamico;


        /* Precisa Criar o Fluxo de PHP */
        /*
        Ideia para componente
        <div class="renderForm" data-tipo="modulo" data-modulo="artigos" "data-formulario="artigos"></div>
        
        */
        
        if(!this.hash && !this.pai){
            var forms = document.getElementsByClassName("renderForm")
            var i = 0;
            while(i < forms.length){
                var item = forms[i]
                // Tipo : modulo, pagina, master ...
                if(item.dataset.tipo && item.dataset.nome){
                    var render = new RenderForm(item, item.dataset.nome, false);
                    render.tipo(item.dataset.tipo)
                    render.render();
                }else{
                    console.log("esse componente php falta informações para renderizar")
                }
                i++;
            }
            
        }
        /* Fim do Fluxo de PHP */
        else{
            this.ajax();
        }
    }
    
    ajax(){
        var data = new FormData();
        data.append("hash", this.hash)
        const xhttp = new XMLHttpRequest();
        xhttp.onload = ()=> {
           
            try{
                 var obj = JSON.parse(xhttp.responseText)
                      if(obj.sucesso){
                var key = geraId();

                var formulario = new FormularioRenderizado(obj.formulario.inputs, this.pai, key, false)
                formulario.size(obj.formulario.tamanho)
                formulario.processa();
            }
            }catch(e){
                console.log(e)
                console.log(xhttp.responseText)
            }
       
        }
        xhttp.open("POST", `${dominioAdress}/master/modulos/formularios/admins/render.php`);
        xhttp.send(data);
    }
    
    render(){
 
    }
}


