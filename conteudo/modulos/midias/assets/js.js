 
    function boxMidiaAdmin(config){
        
        console.log(config)
        var pasta = config.url.split("/")[1];
     
        
        var div = document.createElement("DIV")
        div.classList.add("itemMidia")
        div.dataset.pasta = pasta;
        
        var card = document.createElement("DIV")
        card.classList.add("card", "border-0")
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "p-1")
        
        var quadrado = document.createElement("DIV")
        quadrado.classList.add("quadrado")
        
        var inner = document.createElement("DIV")
        inner.classList.add("ratio","ratio-1x1")
        
        
        switch(parseInt(config.tipo)){
            case 1:
              // Tipo Imagem
                var image = trataImagem(config.url, "pequena")
                  var conteudo = document.createElement("DIV")
                conteudo.classList.add("w-100", "h-100", "img-thumbnail")
                if(image){
                       conteudo.style = `background: url(${image});background-repeat: no-repeat;background-size: cover;`
                }else{
                       conteudo.classList.add("bg-danger")
                }
              
             
                inner.appendChild(conteudo)
                break;
            case 2:
                // Tipo Doc
                var icone = "bi bi-file-earmark-text";
                  switch(config.formato){
                      case 'csv':
                           var icone = "bi bi-filetype-csv";
                          break;
                       case 'pdf':
                           var icone = "bi bi-filetype-pdf";
                           break;
                      default:
                      var icone = "bi bi-file-earmark-text";
                        break;
                  }

                var conteudo = document.createElement("DIV")
                conteudo.classList.add("w-100", "h-100", "img-thumbnail", "d-flex", "align-items-center", "justify-content-center")
                
                var span = document.createElement("I")
                span.className = icone;
                span.classList.add("fs-30")
                conteudo.appendChild(span)
                inner.appendChild(conteudo)
                break;
            case 3:
                // Tipo Vídeo
                break;
       
        }
        
        
        var deleta = document.createElement("BUTTON")
        deleta.innerHTML = '<span class="material-symbols-outlined" style="font-size: 14px">delete</span>'
      //  evento(deleta, "click", this.apaga.bind(this))


        var ver = document.createElement("BUTTON")
        ver.innerHTML = '<span class="material-symbols-outlined text-light">visibility</span>'
       // evento(ver, "click", this.edita.bind(this))

        
       // inner.appendChild(deleta)
        //inner.appendChild(ver)
        
        quadrado.appendChild(inner)
        body.appendChild(quadrado)
        card.appendChild(body)
        div.appendChild(card)
        return div;

    }

class ModuloMidias{
    constructor(){
        this.itens = [];
        this.pai =   document.getElementById("listaMidia");
        this.init.bind(this)();
        
        this.pastas = {};
        this.tipos = {}
        this.formatos = {}
    }
    
    
    box(config){
        
        
        var pasta = config.caminho.split("/")[1];
        if(!this.pastas[pasta]){
            this.pastas[pasta] = true;
        }
        
        
        var div = document.createElement("DIV")
        div.classList.add("col-6", "col-md-4", "col-lg-3", "col-xl-2", "p-1", "itemMidia")
        div.dataset.pasta = pasta;
        
        var card = document.createElement("DIV")
        card.classList.add("card", "border-0")
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "p-1")
        
        var quadrado = document.createElement("DIV")
        quadrado.classList.add("quadrado")
        
        var inner = document.createElement("DIV")
        inner.classList.add("ratio","ratio-1x1")
        
        
        switch(parseInt(config.tipo)){
            case 1:
              // Tipo Imagem
                var image = trataImagem(config.caminho, "pequena")
                  var conteudo = document.createElement("DIV")
                conteudo.classList.add("w-100", "h-100", "img-thumbnail")
                if(image){
                       conteudo.style = `background: url(${image});background-repeat: no-repeat;background-size: cover;`
                }else{
                       conteudo.classList.add("bg-danger")
                }
              
             
                inner.appendChild(conteudo)
                break;
            case 2:
                // Tipo Doc
                var icone = "bi bi-file-earmark-text";
                  switch(config.formato){
                      case 'csv':
                           var icone = "bi bi-filetype-csv";
                          break;
                       case 'pdf':
                           var icone = "bi bi-filetype-pdf";
                           break;
                      default:
                      var icone = "bi bi-file-earmark-text";
                        break;
                  }

                var conteudo = document.createElement("DIV")
                conteudo.classList.add("w-100", "h-100", "img-thumbnail", "d-flex", "align-items-center", "justify-content-center")
                
                var span = document.createElement("I")
                span.className = icone;
                span.classList.add("fs-30")
                conteudo.appendChild(span)
                inner.appendChild(conteudo)
                break;
            case 3:
                // Tipo Vídeo
                break;
       
        }
        
        
        var deleta = document.createElement("BUTTON")
        deleta.innerHTML = '<span class="material-symbols-outlined" style="font-size: 14px">delete</span>'
        evento(deleta, "click", this.apaga.bind(this))


        var ver = document.createElement("BUTTON")
        ver.innerHTML = '<span class="material-symbols-outlined text-light">visibility</span>'
        evento(ver, "click", this.edita.bind(this))

        
       // inner.appendChild(deleta)
        //inner.appendChild(ver)
        
        quadrado.appendChild(inner)
        body.appendChild(quadrado)
        card.appendChild(body)
        div.appendChild(card)
        this.pai.insertBefore(div, this.pai.firstChild);
    }
    
    init(){
        new Filtro("midias", "I5fhsxMCYAOYjhc", document.getElementById("listaMidia"));
        
        return;
        
        let request = new Request(`/conteudo/modulos/midias/admins/ajax.php`);
        request.send().then((r)=>{
            let lista = r.lista
            
        var i = 0;
        while(i < lista.length){
             var item  = this.box.bind(this)(lista[i]);

            i++;
        }
        
 
        })
    }
    
     apaga(){
        console.log("apagando")
    }
    
    edita(){
        console.log("editando")
    }
    
 
}


function moduloMidias(){
    new ModuloMidias();
}