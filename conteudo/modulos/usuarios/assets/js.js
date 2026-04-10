class FuncaoItem{
    constructor(item, container){

        this.item = item
        this.container = container
        this.pai = document.getElementById("lista")
    }
    
    render() {
      
        var col = document.createElement("DIV")
        col.classList.add("col-12", "col-xl-4", "p-2")
        
        var card = document.createElement("div");
        card.className = "card card-nown";
        
        var cardBody = document.createElement("div");
        cardBody.className = "card-body";
        
        var dflex = document.createElement("DIV")
        dflex.classList.add("d-flex", "justify-content-between", "align-items-center")
        
        var esquerda = document.createElement("DIV")
        var direita = document.createElement("DIV")
        
        if(this.item.apagavel){
            var deleta = document.createElement("BUTTON")
            deleta.classList.add("btn", "text-white", "border-0")
            deleta.innerHTML = `<i class="bi bi-trash3"></i>`
            evento(deleta, "click", this.deleta.bind(this))
            direita.appendChild(deleta)
        }
        

        
        dflex.appendChild(esquerda)
        dflex.appendChild(direita)
        
        var h3 = document.createElement("h3");
        h3.className = "fs-20 fw-700";
        h3.textContent = `${this.item.nome}`;
        
        var p = document.createElement("p");
        p.className = "fs-14 fw-700 m-0";
        p.textContent = "Usuários atuais com a função: 5";
        
        var ul = document.createElement("ul");
        ul.className = "list-group list-group-flush";
        
        

        var regras = this.item.regras ?? {};
    

        
        var divButtons = document.createElement("div");
        divButtons.className = "d-flex justify-content-start align-items-center gap-2 mt-3";
        
        var buttonView = document.createElement("button");
        buttonView.className = "btn btn-n-primaria";
        buttonView.textContent = "Ver Função";
        
        var buttonEdit = document.createElement("button");
        buttonEdit.className = "btn btn-light";
        buttonEdit.textContent = "Editar Função";
        evento(buttonEdit, "click", this.edita.bind(this))
        
        divButtons.appendChild(buttonView);
        divButtons.appendChild(buttonEdit);
        
        esquerda.appendChild(h3);
        esquerda.appendChild(p);
        cardBody.appendChild(dflex);
        cardBody.appendChild(ul);
        cardBody.appendChild(divButtons);
        
        card.appendChild(cardBody);
        col.appendChild(card)
        this.col = col
        this.pai.appendChild(col);

    }
    
    edita(){
        this.container.editar(this);
    }
    
    deleta(){
         this.container.deleta(this);
    }
}

class ModalFuncoesDeleta{
    constructor(pai){
        this.pai = pai;
        
         this.modal = new bootstrap.Modal('#modalDeletaFuncao', {
            backdrop: 'static',
            keyboard: false
        })
        
        this.deletada = document.getElementById("funcaoDeletada")
        this.vinculada = document.getElementById("funcaoVinculada")
        evento(this.vinculada, "input", this.vincula.bind(this))
        this.btn = document.getElementById("btnDeleta")
        evento(this.btn , "click", this.apagar.bind(this))
        
        this.itens = {}
        this.foco = false;
    }

    apagar(){
        this.btn.setAttribute("disabled", "")
        this.btn.innerHTML = `
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        `
        if(this.foco && this.foco > 2 && this.vinculada.value > 1){
            var data = new FormData();
            data.append("acao", "apagar")
            data.append("deletado", this.foco)
            data.append("vinculo", this.vinculada.value)
            this.pai.ajax(data, this.apagado.bind(this))
        }
        
    }
    
    apagado(r){
        if(r.sucesso){
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: "Função Apagada com Sucesso",
                showConfirmButton: false,
                timer: 1500
            });
            delete  this.itens[this.foco]
           this.html.col.remove()
           
        }else{
             Swal.fire({
                icon: "error",
                title: "Erro ao Apagar função",
                text: r.mensagem,
                showConfirmButton: false,
                timer: 1500
            });
        }
        this.modal.hide(); 
    }
    
    vincula(){
        if(this.vinculada.value != "0"){
            this.btn.removeAttribute("disabled")
        }else{
            this.btn.setAttribute("disabled", "")
        }
    }
    
    reseta(){
        this.btn.innerHTML = "Apagar"
        this.deletada.innerHTML = ""
        this.vinculada.innerHTML = ""
        this.foco = false;
        this.btn.setAttribute("disabled", "")
    }
    
    option(text, valor, pai){
        var opt = document.createElement("OPTION")
        opt.value = valor
        opt.innerText = text
        pai.appendChild(opt)
    }
    
    deleta(item){
        this.reseta.bind(this)()
        if(item.item.id > 2){
           
           this.foco = item.item.id
           this.html = item
           
           this.option(item.item.nome , item.item.id, this.deletada)
           
           this.option("Selecione uma função" , 0 , this.vinculada)
           for(let c in this.itens){
               var item = this.itens[c]
               if(item.id != 1 && item.id != this.foco){
                   this.option(item.nome , item.id, this.vinculada)
               }
               
               
               
           }
           
           this.modal.show(); 
        }
        
        
        
    }
    
    add(item){
        this.itens[item.id] = item
    }
}

class ModalFuncoes{
    constructor(itens, pai){
        this.pai = pai;
        this.itens = itens
        this.modulos = {};
        
        this.btn = document.getElementById("salvarFuncao")
        evento(this.btn, "click", this.salvar.bind(this))
        
        this.modal = new bootstrap.Modal('#modalFuncoes', {
            backdrop: 'static',
            keyboard: false
        })
        
        this.checks = [];
        this.fulls = [];
        this.nome = document.getElementById("nomeDaFuncao")
        this.tipo = document.getElementById("tipoDaFuncao")
    }
    
    salvar(){
        if(!this.nome.value){
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Defina um nome para a função antes de salvar",
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }
        
        
        var i = 0;

        var regras = {};
        
    
        while(i < this.checks.length){
            var input = this.checks[i];
            
            if(!regras[input.dataset.pasta]){
                regras[input.dataset.pasta] = {};
            }
            
            if(!regras[input.dataset.pasta][input.dataset.grupo]){
                regras[input.dataset.pasta][input.dataset.grupo] = {};
            }
            
            if(!regras[input.dataset.pasta][input.dataset.grupo][input.dataset.foco]){
                regras[input.dataset.pasta][input.dataset.grupo][input.dataset.foco] = {};
            }
            
            regras[input.dataset.pasta][input.dataset.grupo][input.dataset.foco][input.dataset.acao] = input.checked;
            
            i++;
        }
        
        for(let c in regras){
            let selector = document.querySelector(`.acessoCompleto[data-pasta="${c}"]`);
            if(selector.checked){
                regras[c].fullAcess = true;
            }else{
                regras[c].fullAcess = false;
            }
        }
        
   
        this.packRules = regras;
        
        var data = new FormData();
        data.append("acao", this.acao)
        data.append("id", this.id)
        data.append("tipo", this.tipo.value)
        data.append("nome", this.nome.value)
        data.append("regras", JSON.stringify(regras))
        this.pai.ajax(data, this.salvo.bind(this))
        
        
        
    }
    
    salvo(r){
        
        
        if(this.acao == "novo"){
            var infos = {
                id: r.id,
                nome: this.nome.value,
                regras: this.packRules,
                tipo: this.tipo.value,
                apagavel: true
            }
            var item = new FuncaoItem(infos , this);
            item.render();
        }else{
            this.emFoco.regras = this.packRules;
        }
        
        
        this.cleanAll.bind(this)()
        this.modal.hide();
   
    }
    
    cleanAll(){
        var i = 0;
        while(i < this.checks.length){
            this.checks[i].checked = false;
            this.checks[i].disabled = false;
            i++;
        }
        this.nome.value = "";
        this.nome.disabled = false;
        
        var i = 0;
        while(i < this.fulls.length){
            this.fulls[i].checked = false;
            this.fulls[i].disabled = false;
            i++;
        }
        
        this.tipo.setAttribute("disabled", "")
        this.tipo.value = 2;
        
    }
    
    novo(){
        this.cleanAll.bind(this)()
        this.tipo.removeAttribute("disabled")
        this.acao = "novo"
        this.id = false;
        this.modal.show();
    }
    
    percorrerObjeto(obj, caminho = []) {
    let resultado = [];

    Object.entries(obj).forEach(([chave, valor]) => {
        // Se o valor for um objeto, faz uma chamada recursiva
        if (typeof valor === 'object' && valor !== null && !Array.isArray(valor)) {
            resultado = resultado.concat(this.percorrerObjeto(valor, caminho.concat(chave)));
        } else {
            // Se for um valor final (neste caso, booleano), adiciona o caminho e o valor ao resultado
            resultado.push(caminho.concat(chave, valor));
        }
    });

    return resultado;
}
    
    editar(obj){
        this.cleanAll.bind(this)()
     
         
        var item = obj.item;
        
        if(!item.apagavel){
            this.nome.disabled = true;
            
             if(item.id == 1){
                 var i = 0;
                while(i < this.checks.length){
            this.checks[i].checked = true;
            this.checks[i].disabled = true;
            i++;
        }
        
        
        var i = 0;
        while(i < this.fulls.length){
            this.fulls[i].checked = true;
            this.fulls[i].disabled = true;
            i++;
        }
                
             }
            
            
        }
        
        if(item.id != 1){
            var regras = obj.item.regras;
            for(let c in regras){
                var regra = regras[c]
                if(regra.fullAcess){
                       let selector = document.querySelector(`.acessoCompleto[data-pasta="${c}"]`);
                       selector.checked = true;
                       
                       let evento = new Event('input', {
                           bubbles: true,
                           cancelable: true, 

                       });
                       
                       selector.dispatchEvent(evento);
                       
                }else{
                   var pacotes = this.percorrerObjeto(regra);
                   var i = 0;
                   while(i < pacotes.length){
                       var pacote = pacotes[i]
                       if(pacote.length == 4 && pacote[3] == true){
                           let selector = document.querySelector(` .form-check-input[data-pasta="${c}"][data-grupo="${pacote[0]}"][data-foco="${pacote[1]}"][data-acao="${pacote[2]}"]`);
                           if(selector){
                               selector.checked = true;
                           }
                       }
                       
                       i++;
                   }
                }
            }
        }
        
        if(item.id < 3){
            this.tipo.setAttribute("disabled", "")
            if(item.id == 1){
                this.tipo.value = 1;
            }else{
                this.tipo.value = 2;
            }
            
        }else{
            this.tipo.value = obj.item.tipo
            this.tipo.removeAttribute("disabled")
        }
        
        this.acao = "editar"
        this.id = item.id
        this.emFoco = item

        this.nome.value = item.nome
        
     this.modal.show();
    }
    
    monta(){
        for(let c in this.itens){
            var obj = this.itens[c]
            this.modulos[obj.pasta] = this.item(obj)
        }
    }
    
    verifica(){
        if(!event.currentTarget.checked){
            var pai = event.currentTarget.closest(".list-group-item")
            pai.getElementsByClassName("acessoCompleto")[0].checked = false;
        }
  
    }
    
    check(texto, pasta , grupo , foco, acao){
        
       var id = geraId();
       const colDiv = document.createElement('div');
       colDiv.className = 'col-3';
       
       const formCheckDiv = document.createElement('div');
       formCheckDiv.className = 'form-check d-flex justify-content-start gap-2 align-items-center';
       
       const input = document.createElement('input');
       input.className = 'form-check-input';
       input.type = 'checkbox';
       input.value = '';
       input.id = id;
       evento(input, "input", this.verifica.bind(this))
       
       
       input.dataset.pasta = pasta 
       input.dataset.grupo = grupo
       input.dataset.foco = foco
       input.dataset.acao = acao
       
       
       this.checks.push(input)
       
       const label = document.createElement('label');
       label.className = 'form-check-label fs-12 mb-0 fst-italic';
       label.style.lineHeight = "12px"
       label.setAttribute('for', id);
       label.textContent = texto;
       
       formCheckDiv.appendChild(input);
       formCheckDiv.appendChild(label);
       colDiv.appendChild(formCheckDiv);
       
       return colDiv;
    }
    
    titulo(texto){
        var col = document.createElement("DIV")
            col.classList.add("col-12", "mt-2")
            var h2 = document.createElement("H2")
            h2.classList.add("fs-14", "fw-700", "text-decoration-underline")
            h2.innerText = texto
            col.appendChild(h2)
            return col;
    }
    
    validacaoMassa(){
        var pai = event.currentTarget.closest(".list-group-item")
        var inputs = pai.getElementsByClassName("form-check-input")
        
        var i =  0;
        while(i < inputs.length){
            inputs[i].checked = event.currentTarget.checked;
            i++;
        }
    }
    
    item(obj){
      
        
        
    
        var li = document.createElement("LI")
        li.classList.add("list-group-item")
        
        
        var topo = document.createElement("DIV")
        topo.classList.add("d-flex", "justify-content-between", "align-items-center")
        
        const h4 = document.createElement('h4');
        h4.className = 'fs-14 fw-700';
        h4.textContent = obj.name; 
        
        
        var id = geraId();
     
       
       const formCheckDiv = document.createElement('div');
       formCheckDiv.className = 'form-check d-flex justify-content-start gap-2 align-items-center';
       
       const input = document.createElement('input');
       input.className = 'form-check-input acessoCompleto';
       evento(input, "INPUT", this.validacaoMassa.bind(this))
       input.dataset.pasta = obj.pasta;
       input.type = 'checkbox';
       input.value = '';
       input.id = id;
       
       
       this.fulls.push(input)

       
       const label = document.createElement('label');
       label.className = 'form-check-label fs-12 mb-0 fw-900 text-danger';
       label.style.lineHeight = "12px"
       label.setAttribute('for', id);
       label.textContent = "Acesso Completo"
       
       formCheckDiv.appendChild(input);
       formCheckDiv.appendChild(label);
       
       topo.appendChild(h4)
       topo.appendChild(formCheckDiv);
         
        
        
      
        
        li.appendChild(topo)
        
        if(obj.paginas.length > 0){
            const row = document.createElement('div');
            row.className = 'row';
            row.appendChild(this.titulo("Páginas"))
            var i = 0;
          
            
            while(i < obj.paginas.length){
                var pagina = obj.paginas[i]
                row.appendChild(this.check(pagina, obj.pasta, "paginas", pagina, "ver"))
                i++;
            }
               li.appendChild(row);
            
        }
        
        if(obj.formularios.length > 0){
            const row = document.createElement('div');
            row.className = 'row';
            row.appendChild(this.titulo("Formulários"))
            var i = 0;
            while(i < obj.formularios.length){
                var form = obj.formularios[i]
            
                var col = document.createElement("DIV")
                col.classList.add("col-3", "fs-12", "fw-900")
                col.innerText = `${form.nome} :`
                row.appendChild(col)
                row.appendChild(this.check("Escrever", obj.pasta, "formulario", form.hash, "escrever"))
                row.appendChild(this.check("Deletar", obj.pasta, "formulario", form.hash, "deletar"))
                row.appendChild(this.check("Terceiros", obj.pasta, "formulario", form.hash, "terceiros"))
                i++;
            }
               li.appendChild(row);
            
        }
        
        
         if(obj.tabelas.length > 0){
            const row = document.createElement('div');
            row.className = 'row';
            row.appendChild(this.titulo("Tabelas"))
            var i = 0;
            while(i < obj.tabelas.length){
                var tabela = obj.tabelas[i]
                
                var col = document.createElement("DIV")
                col.classList.add("col-3", "fs-12", "fw-900")
                col.innerText = `${tabela.nome} :`
                row.appendChild(col)
                row.appendChild(this.check("Próprios", obj.pasta, "tabela", tabela.hash, "proprios"))
                row.appendChild(this.check("Terceiros", obj.pasta, "tabela", tabela.hash, "terceiros"))
                var col = document.createElement("DIV")
                col.classList.add("col-3")
                row.appendChild(col)
               
                i++;
            }
               li.appendChild(row);
            
        }
            
        

        
     
     
  

        document.getElementById("listaModulos").appendChild(li)
        return li;

    }
}

class Funcoes{
    constructor(){
        var data = new FormData();
        data.append("acao","itens")
        this.ajax.bind(this)(data, this.listar.bind(this))
        
        this.modalDeleta = new ModalFuncoesDeleta(this);
        
        this.btnNovo = document.getElementById("novaFuncao")
        evento(this.btnNovo, "click", this.novo.bind(this))
    }
    
    novo(){
        this.modal.novo();
    }
    
    editar(item){
        this.modal.editar(item);
    }
    
    estruturaModal(r){
        if(r.sucesso){
            this.modal = new ModalFuncoes(r.lista, this);
            this.modal.monta();
            
            
        }
        
        this.btnNovo.removeAttribute("disabled")
    }
    
    deleta(item){
        this.modalDeleta.deleta(item);
    }
    
    listar(r){
        this.lista = r.lista
        this.lista.sort((a, b) => parseInt(a.id) - parseInt(b.id));

        var i = 0;
        while(i < this.lista.length){
            var item = new FuncaoItem(this.lista[i], this);
            item.render();
            
            
            this.modalDeleta.add(this.lista[i])
            
            i++;
        }
        
        var data = new FormData();
        data.append("acao", "listar")
        this.ajax.bind(this)(data, this.estruturaModal.bind(this))
    }
    
    ajax(data, cb = false){
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                let obj = JSON.parse(request.responseText)
                if(cb){
                    cb(obj)
                }else{
                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/conteudo/modulos/usuarios/admins/funcoes.php`)
        request.send(data)
    }
}

function moduloUsuariosFuncoes(){
    console.log("funcoes");
    //loadResources(`https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js`)
    //new Funcoes();
}

 
function moduloUsuariosFuncao(){
    console.log("roda roda jequiti");
}


