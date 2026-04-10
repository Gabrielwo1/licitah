class configModuloArtigosTopo{
    constructor(div){
        this.div = div
        this.box = document.getElementById("boxConfig")
        this.select = this.box.getElementsByTagName("select")[0]
        evento(this.select, "input", this.mudou.bind(this))
        
        
        this.lis = [];
        this.estrutura.bind(this)()
        
       
    }
    
    mudou(){
        if(this.select.value > this.lis.length){
            var calc = this.select.value - this.lis.length
            
            var i = 0;
            while(i < calc){
                
                 this.ul.appendChild(this.lidiv.bind(this)())
                i++;
            }
        }else{
            var calc =  this.lis.length - this.select.value;
            console.log(calc)
            var i = 0;
            while(i < calc){
                this.lis[this.lis.length - 1].remove();
                this.lis.pop();
                i++;
            }
        }
    }
    
    lidiv(titulo = false){
        var li = document.createElement("LI")
        li.classList.add("list-group-item", "d-flex", "row", "m-0", "p-0")
        
        var col1 = document.createElement("DIV")
        col1.classList.add("col-6", "p-1")
        if(titulo){
            
        }else{
             var input = document.createElement("SELECT")
             input.classList.add("form-select")
        }
       
        
        var tamanhos = {
            1: 100,
            2: 50,
            3: 33,
            4: 25,
            6: 20, 
            12: 10,

        }
        for(let c in tamanhos){
            var option = document.createElement("OPTION")
            option.value = c
            option.innerText = `${tamanhos[c]}%`
            input.appendChild(option)
        }
        
        col1.appendChild(input)
        li.appendChild(col1)
        
        
        var col1 = document.createElement("DIV")
        col1.classList.add("col-6", "p-1")
        
        var input = document.createElement("INPUT")
        input.classList.add("form-control")
        input.type = "number"
        input.min = "1"
        input.max = "4"
        input.placeholder = "Número de Filhos"
        input.value = "1"
        col1.appendChild(input)
        li.appendChild(col1)
        this.lis.push(li)
        return li;

    }
    
    estrutura(){
        this.ul = document.createElement("UL")
        this.ul.classList.add("list-group")
        
        this.ul.appendChild(this.lidiv.bind(this)(true))
        
        var i = 0;
        while(i < this.select.value){
            this.ul.appendChild(this.lidiv.bind(this)())
            i++;
        }
        this.div.appendChild(this.ul)
    }
}

function configModuloArtigos(chave, c , div){
    console.log(chave, c , div)
  
}