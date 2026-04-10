class ConfiguracaoIdioma{
    constructor(memoria, div){
        this.div = div;
        this.memoria = memoria;
        
        this.selects = [];
        
        setTimeout(()=>{
            this.pai = this.div.closest(".card-body")
            this.textarea = this.pai.getElementsByTagName("textarea")[0]
            this.select = this.pai.getElementsByTagName("select")[0]
            this.render.bind(this)()
        }, 200)
        
        
    
        
    }
    
    calc(){
        this.selecionados = this.textarea.value ? JSON.parse(this.textarea.value) : [];
        
        this.idiomas = {};
        this.travado = {};
        
        var options = this.select.options
        var i = 0;
        while(i < options.length){
            var op = options[i]
            if(!this.selecionados[op.value]){
                 this.idiomas[op.value] = op.innerText
            }else{
                this.travado[op.value] = op.innerText
            }
            i++;
        }
        this.processa.bind(this)()
    }
    
    render(){
        
        
        
        
        var div = document.createElement("DIV")
        div.classList.add("d-flex","justify-content-end","align-items-center")
        
        var btn = document.createElement("BUTTON")
        btn.classList.add("btn","btn-success")
        btn.innerText = "Adicionar Idioma"
        evento(btn, "click", this.novoIdioma.bind(this))
        div.appendChild(btn)
        
        
        
        this.lista = document.createElement("DIV")
        this.lista.classList.add("list-group", "mt-3")
        
        
        this.div.appendChild(div)
        this.div.appendChild(this.lista)
        
        this.calc.bind(this)()
        
    }
    
    processa(){
        this.lista.innerHTML = "";

        for(let i in this.selecionados){
            var idioma = this.selecionados[i]
            this.linha.bind(this)(idioma)
        }

    }
    
    selecionado(){
        var valores = [];
        var selects = document.getElementsByClassName("idiomasSecundarios")
        
        for(let i in selects){
            if(selects[i].value){
                valores.push(selects[i].value)
            }
            
        }
        this.textarea.value = JSON.stringify(valores)
        this.calc.bind(this)()
 
    }
    
    linha(valor){
         const listItem = document.createElement('div');
         listItem.classList.add('list-group-item', 'list-group-item-action');
         
         const rowDiv = document.createElement('div');
         rowDiv.classList.add('row');
         
         const col1Div = document.createElement('div');
         col1Div.classList.add('col-1');
         const button1 = document.createElement('button');
         button1.classList.add('btn', 'btn-contrast');
         const span1 = document.createElement('span');
         span1.classList.add('material-symbols-outlined');
         span1.textContent = 'pan_tool';
         button1.appendChild(span1);
         col1Div.appendChild(button1);
         
         const col10Div = document.createElement('div');
         col10Div.classList.add('col-10');
         const selectElement = document.createElement('select');
         selectElement.classList.add('form-select', "idiomasSecundarios");
         
         
         
         
         for(let i in this.idiomas){
             var id = this.idiomas[i]
             var option = document.createElement("OPTION")
             option.value = i
             option.innerText = id
             selectElement.appendChild(option)
         }
         
        var option = document.createElement("OPTION")
        option.value = valor
        option.innerText = this.travado[valor]
        selectElement.appendChild(option)
        selectElement.value = valor
         
        evento(selectElement, "input", this.selecionado.bind(this))

         
         col10Div.appendChild(selectElement);
         
         const col1DeleteDiv = document.createElement('div');
         col1DeleteDiv.classList.add('col-1');
         const buttonDelete = document.createElement('button');
         buttonDelete.classList.add('btn', 'btn-danger');
         const spanDelete = document.createElement('span');
         spanDelete.classList.add('material-symbols-outlined');
         spanDelete.textContent = 'delete';
         buttonDelete.appendChild(spanDelete);
         col1DeleteDiv.appendChild(buttonDelete);
         
         rowDiv.appendChild(col1Div);
         rowDiv.appendChild(col10Div);
         rowDiv.appendChild(col1DeleteDiv);
         
         listItem.appendChild(rowDiv);
         this.lista.appendChild(listItem)

       
    }
    
    novoIdioma(){
        for(let i in this.idiomas){
            var idioma = this.idiomas[i]
            this.selecionados.push(i)
            this.textarea.value = JSON.stringify(this.selecionados)
            this.calc.bind(this)()
            break;
        }
    }
}

function configuracoesNownIdioma(){
    new ConfiguracaoIdioma();
}