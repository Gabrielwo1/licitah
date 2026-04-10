class InputConfig{
    constructor(chave, input){
        this.input = input
        this.tipo = this.input.tipo.toLowerCase()
        this.label = chave
    }
    
    get(){
        
    }
    
    set(){
        
    }
    
    entrada(){
        let input;
        switch(this.tipo){
            case 'input':
                input = document.createElement("INPUT")
                input.type = "text"
                input.classList.add("form-control")
                this.conteudo = input;
                break;
            case 'textarea':
                input = document.createElement("TEXTAREA")
                input.classList.add("form-control")
                this.conteudo = input;
                break;
            case 'select':
                input = document.createElement("SELECT")
                input.classList.add("form-select")
                this.conteudo = input;
                break;
            case 'check':
                input = document.createElement("DIV")
                input.classList.add("form-check","form-switch")
                var swith = document.createElement("INPUT")
                swith.style = "width: 80px; height: 40px"
                swith.classList.add("form-check-input")
                swith.type = "checkbox"
                swith.setAttribute("role" ,"switch")
                input.appendChild(swith)
                this.conteudo = swith;
                break;
            case 'file':
                input = document.createElement("DIV")
                input.classList.add("ratio","ratio-1x1", "bg-secondary", "wi-250")
                this.conteudo = input;
                break;
            case 'color':
                 input = document.createElement("INPUT")
                 input.type = "color"
                 input.style = "width: 50px; height: 50px"
                 this.conteudo = input;
                break;
        }
       
        return input;
    }
    
    render(){
        var row = document.createElement("DIV")
        row.classList.add("row", "border-bottom", "pb-4")
        
        var col = document.createElement("DIV")
        col.classList.add("col-4")

        var label = document.createElement("LABEL")
        label.classList.add("fs-14", "fw-700")
        this.label = this.label.replace(/{dominio}/g , dominio);

        label.innerText = this.label
        
        
        var descricao = document.createElement("SPAN")
        descricao.classList.add("d-block", "fs-12")
        descricao.innerText = "Sou uma simples descrição"
        

        
        col.appendChild(label)
                col.appendChild(descricao)
        
        var col2 = document.createElement("DIV")
        col2.classList.add("col-8")
        col2.appendChild(this.entrada.bind(this)())
        
        row.appendChild(col)
        row.appendChild(col2)
        
        
        
        return row;
        
        
    }
}

class ConfiguracoesModulos{
    constructor(){
        this.btns = document.getElementsByClassName("btnsConfig")
        evento(Array.from(this.btns), "click", this.seleciona.bind(this))
        this.ajax = this.ajax.bind(this)
        this.corpo = document.getElementById("corpoConfig")
        
        this.btnResetar = document.getElementById("resetar")
        this.btnRestaurar = document.getElementById("restaurar")
        this.btnSalvar = document.getElementById("salvar")
        
        evento(this.btnResetar, "click", this.resetar.bind(this))
        evento(this.btnRestaurar, "click", this.restaurar.bind(this))
        evento(this.btnSalvar, "click", this.salvar.bind(this))
        
        this.btns[0].click();
        
        this.lista = {};
    }
    
    
    salvar(){
        console.log(this.lista)
    }
    
    restaurar(){
        console.log("restaurar")
    }
    
    resetar(){
         console.log("resetar")
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
        this.loading.bind(this)()
        var btn = event.currentTarget
        
        this.modulo = btn.dataset.modulo
        this.grupo = btn.dataset.grupo
        
        this.btnResetar.setAttribute("disabled", "")
        this.btnRestaurar.setAttribute("disabled", "")
        this.btnSalvar.setAttribute("disabled", "")
        
        var data = new FormData();
        data.append("acao", "html")
        this.ajax(data, this.monta.bind(this))
    }
    
    monta(r){
        this.lista = {};
        this.corpo.innerHTML = ""
        if(r.html){
            let html = r.html;
            for(let c in html){
                if(html[c].tipo != "titulo"){
                    var input = new InputConfig(c, html[c])
                    this.lista[paraUrl(c)] = input
                     var render = input.render();
                }else{
                    var render = document.createElement("H2")
                    render.innerText = c
                    render.classList.add("fs-20")
                }
                
               
                this.corpo.appendChild(render)
            }
            this.btnResetar.removeAttribute("disabled")
            this.btnRestaurar.removeAttribute("disabled")
            this.btnSalvar.removeAttribute("disabled")
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
    }
    
    ajax(data, cb = false){
        data.append("modulo", this.modulo)
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
        request.open("POST", `${dominio}/conteudo/modulos/configuracoes/admins/modulos.php`)
        request.send(data)
    }
}