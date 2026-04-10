class Seo{
    constructor(){
        this.pontuacao = this.pontuacao.bind(this)
        this.itemLista = this.itemLista.bind(this)
        if(document.getElementById("seoPlugin")){
           this.chaveInput = document.getElementById("seoPlugin").getElementsByClassName("form-control")[0]
           this.tituloInput = document.getElementById("seoPlugin").getElementsByClassName("form-control")[1]
           this.descricaoInput = document.getElementById("seoPlugin").getElementsByClassName("form-control")[2]
           this.lista = document.getElementById("seoPlugin").getElementsByClassName("list-group")[0];
           
           this.progressoUm = document.getElementById("seoPlugin").getElementsByClassName("progress-bar")[0]
           this.progressoDois = document.getElementById("seoPlugin").getElementsByClassName("progress-bar")[1]
           
           this.titulo = this.tituloInput.value
           this.descricao = this.tituloInput.value
           this.chave = this.chaveInput.value
           evento(this.tituloInput, "input" , this.pontuacao);
           evento(this.descricaoInput, "input" , this.pontuacao);
           evento(this.chaveInput , "input" , this.pontuacao);
        }
        
    }
    
    itemLista(texto, aprovado){
        var li = new El("DIV")
        li.c("list-group-item list-group-item-action bg-primary text-light py-1")
        
        var icone = new El("I")
        var span = new El("SPAN")
        span.c("ms-2 fs-12")
        span.t(texto)
        if(aprovado){
            li.c("bg-success")
            icone.c("bi bi-check-circle fs-10")
        }else{
            li.c("bg-danger")
            icone.c("bi bi-x-circle fs-10")
        }
        
        li.f([icone.html(), span.html()])
        
        this.lista.appendChild(li.html())
    }
    
    pontuacao(){
        this.titulo = this.tituloInput.value
        this.descricao = this.descricaoInput.value
        this.chave = this.chaveInput.value
        
 
        this.lista.innerHTML = ""
        
        if(!this.chaveInput.value){
            this.itemLista("Defina uma palavra chave", false);
        }else{
            this.itemLista("Palavra chave definida", true);
        }
        
        var calc = 100/60 * this.titulo.length
        this.progressoUm.style.width = `${calc}%`;
        this.progressoUm.classList.remove("bg-success", "bg-danger")
        if(this.titulo.length < 50){
            this.itemLista("Seu metatitle está muito pequeno", false);
            this.progressoUm.classList.add("bg-danger")
        }else if(this.titulo.length > 60){
            this.itemLista("Seu metatitle está muito grande", false);
            this.progressoUm.classList.add("bg-danger")
        }else{
            this.itemLista("Seu metatitle está no tamanho perfeito.", true);
            this.progressoUm.classList.add("bg-success")
        }
        
         var calc = 100/160 * this.descricao.length
        this.progressoDois.style.width = `${calc}%`;
        this.progressoDois.classList.remove("bg-success", "bg-danger")
        
        if(this.descricao.length < 140){
            this.itemLista("Sua metadescrição está muito pequena", false);
            this.progressoDois.classList.add("bg-danger")
        }else if(this.descricao.length > 160){
            this.itemLista("Sua metadescrição está muito grande", false);
            this.progressoDois.classList.add("bg-danger")
        }else{
            this.itemLista("Sua metadescrição está no tamanho perfeito", true);
            this.progressoDois.classList.add("bg-success")
        }
        
        
        
        
        
        
        
    }
   
}
