class ConfiguracoesIdiomaMultiplo{
    constructor(){
        evento(document.getElementById("novoIdioma"), "click" , this.novoPre.bind(this))
        
        this.idiomas = JSON.parse(document.getElementById("idiomas").innerText)
        this.lista = document.getElementById("listaIdiomas")
        
        
        this.texto = document.querySelector('textarea[name="segundarios"]');
        
        if(this.texto.value){
             this.posRender.bind(this)();
        }

    }
    
    novoPre(){
        this.novo.bind(this)(false)
    }
    
    novo(){
        var select = document.createElement("SELECT")
        select.classList.add("form-select","selectIdioma")
        
        
        
        var ja = document.getElementsByClassName("selectIdioma")
        
        
        var i = 0;
        var contador = 0;
        while(i < this.idiomas.length){
            var idioma = this.idiomas[i]
            var option = document.createElement("OPTION")
            option.value = idioma.valor
            option.innerText = idioma.chave
            
            
            var permisao = true;
            var k = 0;
            while(k < ja.length){
                
                if(ja[k].value == idioma.valor){
                    permisao = false;
                }
                k++;
            }
            
            
            if(permisao){
                select.appendChild(option)
               
                contador ++;
            }
            
            i++;
        }
        
        
        var div = document.createElement("DIV")
        div.classList.add("agrupador")
        
        
        
        
        var divapaga = document.createElement("DIV")
        var apaga = document.createElement("BUTTON")
        apaga.innerText = "Apagar";
        divapaga.appendChild(apaga)
         evento(apaga, "click", this.apagado.bind(this))
        
        
        
        div.appendChild(select)
        div.classList.add("d-flex", "justify-content-between")
        div.appendChild(divapaga)
        if(contador > 0){
            this.lista.appendChild(div)
             evento(select, "change", this.render.bind(this))
              this.render.bind(this)();
        }else{
            alert("nao ha idiomas disponiveis");
        }
        
        
        
       
    
    }
    
    render(){
        var idiomas = document.getElementsByClassName("selectIdioma")
        var i = 0;
        var array = [];
        while(i < idiomas.length){
            array.push(idiomas[i].value)
            i++;
        }
        this.texto.value = JSON.stringify(array)
        this.lista.innerHTML = "";
        this.posRender.bind(this)()
    }
    
    posRender(){
        var idiomas = JSON.parse(this.texto.value)
        this.agora = idiomas
        var i = 0;
        while(i < idiomas.length){
            this.novoPost.bind(this)(idiomas[i])
            i++;
        }

    }
    
    apagado(){
        var botao = event.target
        var pai = botao.closest(".agrupador")
        var select = pai.getElementsByClassName("form-select")[0]
        console.log(pai, select)
        var valor = select.value
        
        var idiomas = JSON.parse(this.texto.value)
        pai.remove();
        var i = 0;
        var novo = [];
        while(i < idiomas.length){
            if(idiomas[i] != valor){
                novo.push(idiomas[i])
            }
            i++;
        }
        
        
         this.texto.value = JSON.stringify(novo)
         this.lista.innerHTML = "";
         this.posRender.bind(this)()
        

    }
    
       novoPost(valor){
        var select = document.createElement("SELECT")
        select.classList.add("form-select","selectIdioma")
        
        
        var ja = this.agora
        
        
        var i = 0;
        while(i < this.idiomas.length){
            var idioma = this.idiomas[i]
            var option = document.createElement("OPTION")
            option.value = idioma.valor
            option.innerText = idioma.chave
            
            
            var permisao = true;
            var k = 0;
            while(k < ja.length){
                
                if(ja[k] == idioma.valor && ja[k] != valor){
                    permisao = false;
                }
                k++;
            }
            
            
            if(permisao){
                select.appendChild(option)
            }
            
            i++;
        }
        
        select.value = valor
        
        var div = document.createElement("DIV")
         div.classList.add("agrupador")
        
        var divapaga = document.createElement("DIV")
        var apaga = document.createElement("BUTTON")
        apaga.classList.add("btn", "btn-contrast")
        apaga.innerText = "Apagar";
        divapaga.appendChild(apaga)
        evento(apaga, "click", this.apagado.bind(this))
        
        
        div.appendChild(select)
        div.classList.add("d-flex", "justify-content-between", "gap-2")
        div.appendChild(divapaga)
        
        evento(select, "change", this.render.bind(this))
        this.lista.appendChild(div)

    
    }
}

function configuracoesIdiomaMultiplo(){
    new ConfiguracoesIdiomaMultiplo(); 
} 
