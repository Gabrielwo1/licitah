class ManifestControler{
    constructor(){
        var data = new FormData();
        data.append("acao", "start")
        this.ajax(data, this.start.bind(this))
    }
    
    start(r){
        if(r.arquivo){
            var conteudo = r.conteudo
            for(let c in conteudo){
                var input = document.querySelector(`.entradaManifest[name="${c}"]`);
                if(input){
                    input.value = conteudo[c]
                }
            }
            
            
            var categorias = conteudo.categories
            var i = 0;
            while(i < categorias.length){
                var cat = categorias[i]
                 var input = document.querySelector(`.entradaManifestCat[value="${cat}"]`);
                 if(input){
                     input.checked = true;
                 }
       
                i++;
            }
            
        }
        
        
        this.entradas = document.getElementsByClassName("entradaManifest")
        evento(Array.from(this.entradas), "input", this.inputa.bind(this))
        
        this.cats = document.getElementsByClassName("entradaManifestCat")
        evento(Array.from(this.cats), "input", this.inputa.bind(this))
    }
    
    inputa(){

        var obj = {};
        var i = 0;
        while(i < this.entradas.length){
            var entrada = this.entradas[i]
            obj[entrada.name] = entrada.value
            i++;
        }
        
        var categorias = [];
        var i = 0;
        while(i < this.cats.length){
            if(this.cats[i].checked){
                categorias.push(this.cats[i].value)
            }
            
            i++;
        }
        
        obj["categories"] = categorias
        
        
        var data = new FormData();
        data.append("acao", "update")
        data.append("manifesto", JSON.stringify(obj))
        this.ajax(data, this.salvo.bind(this))
    }
    
    salvo(r){
        console.log(r)
    }
    
    ajax(data, cb = false){
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
        request.open("POST", `${dominio}/conteudo/modulos/aplicativo/admin/processador.php`)
        request.send(data)
    }
}


function moduloAplicativoManifest(){
    new ManifestControler();
}