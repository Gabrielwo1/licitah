class ControlerItensLogados{
    constructor(){
        this.estrutura();
        
        this.lis = [];
        
        this.text = document.getElementById("htmlbtns").closest(".card-nown").getElementsByTagName("textarea")[0];
        console.log(this.text)
        
        this.text.closest(".row").classList.add("d-none")
        if(this.text.value){
            var obj = JSON.parse(this.text.value)
            
            var i = 0;
            while(i < obj.length){
                this.item.bind(this)(obj[i])
                i++;
            }
            
            
            
            
        }
       
        
    }
    
    deleta(){
        event.currentTarget.closest(".list-group-item").remove();
        this.render.bind(this)()
    }
    
    render(){
        var itens = this.lista.getElementsByClassName("list-group-item")
        var resposta = []
        
        var i = 0;
        while(i < itens.length){
            var item = itens[i]
            
            
            resposta.push({
                i: item.getElementsByClassName("form-control")[0].value,
                t: item.getElementsByClassName("form-control")[1].value,
                l: item.getElementsByClassName("form-control")[2].value
            })
            
            i++;
        }
        
        this.text.value = JSON.stringify(resposta)

        
    }
    
    item(r = false){
        console.log(r)
        var li = document.createElement("LI")
        li.classList.add("list-group-item")
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var col = document.createElement("DIV")
        col.classList.add("col-3")
        var input = document.createElement("INPUT")
        evento(input, "input", this.render.bind(this))
        if(r){
            input.value = r.i
        }
        
        input.placeholder = "Icone"
        input.classList.add("form-control")
        col.appendChild(input)
        row.appendChild(col)
        
        
        var col = document.createElement("DIV")
        col.classList.add("col-3")
        var input = document.createElement("INPUT")
        evento(input, "input", this.render.bind(this))
         if(r){
            input.value = r.t
        }
        input.placeholder = "Texto"
        input.classList.add("form-control")
        col.appendChild(input)
        row.appendChild(col)
        
        
        var col = document.createElement("DIV")
        col.classList.add("col-5")
        var input = document.createElement("INPUT")
        evento(input, "input", this.render.bind(this))
        input.placeholder = "Link"
         if(r){
            input.value = r.l
        }
        input.classList.add("form-control")
        col.appendChild(input)
        row.appendChild(col)
        
        
         var col = document.createElement("DIV")
        col.classList.add("col-1", "d-flex", "justify-content-center", "align-items-center")
        var deleta = document.createElement("BUTTON")
        evento(deleta, "click", this.deleta.bind(this))
        deleta.classList.add("btn", "btn-danger", "wi-40", "he-40", "d-flex" , "justify-content-center", "align-items-center")
        deleta.innerHTML = `<i class="bi bi-trash3-fill"></i>`
        col.appendChild(deleta)
        row.appendChild(col)
        
        
        li.appendChild(row)

        this.lista.appendChild(li)
        this.render.bind(this)
    }
    
    estrutura(){
        this.base = document.getElementById("htmlbtns")
        
        var div = document.createElement("DIV")
        div.classList.add("d-flex", "justify-content-end")
        
        var btnAdd = document.createElement("BUTTON")
        btnAdd.classList.add("btn", "btn-n-primaria")
        btnAdd.innerText = "Adicionar Item"
        evento(btnAdd, "click", this.item.bind(this))
        div.appendChild(btnAdd);
        
        this.lista = document.createElement("UL")
        this.lista.classList.add("list-group","list-group-flush", "mt-2")
  
        
        this.base.appendChild(div)
        this.base.appendChild(this.lista)
   

    }
}

function configuracoesNownPaginas(p, r){
    switch(p){
        case 'logadas':
            new ControlerItensLogados();
            break;
    }
}