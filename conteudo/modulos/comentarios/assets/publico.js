class Comentario{
    constructor(pai, infos){
        this.pai = pai
        

        
        this.texto = infos.texto
        this.gif = infos.gif
        this.video = infos.video
        this.foto = infos.foto
        
        this.html.bind(this)()

    }
    
    caixaTexto(){
         if(this.texto){
             var texto = document.createElement("DIV")
        texto.classList.add("bg-light", "p-2")
        
        var p = document.createElement("P")
        p.classList.add("m-0")
        p.innerText = this.texto
        
        texto.appendChild(p)
        return texto;
         }else{
             return false;
         }
     }
     
    caixaFoto(){
         if(this.foto){
             
         }else{
             return false;
         }
     }
     
    caixaVideo(){
         if(this.video){
             
         }else{
             return false;
         }
     }
     
    caixaGif(){
         if(this.gif){
             
         }else{
             return false;
         }
     }
     
    estruturaBasica(){
        
    }
    
    html(){
        var texto = this.caixaTexto();
        if(texto){
            this.pai.appendChild(texto)
        }
    }
}

class Comentarios{
    constructor(pai){
        this.pai = pai
        
        this.html.bind(this)();
        
        
        this.gif = false;
        this.foto = false;
        this.video = false;
        this.texto = this.textarea.value
        

        
    }
    
    infos(){
        return {
            texto: this.textarea.value,
            gif: this.gif,
            video: this.video,
            foto: this.foto
            
        };
    }
    
    isValido(){
        this.texto = this.textarea.value
        if(!this.texto && !this.gif && !this.foto && !this.video){
            return false;
        }else{
            return true;
        }
        
    }
    
    validacao(){
        if(!this.isValido.bind(this)()){
            this.enviar.setAttribute("disabled", "")
        }else{
             this.enviar.removeAttribute("disabled")
        }
    }
    
    boxTexto(){
        const cardBodyDiv = document.createElement('div');
        cardBodyDiv.className = 'card-body';
        
        this.boxComentario = document.createElement('div');
        this.boxComentario.className = 'boxComentario form-control';
        
        this.textarea = document.createElement('textarea');
        this.textarea.className = 'w-100 border-0 texto';
        
        
        evento(this.textarea, "focus", this.ativa.bind(this))
        evento(this.textarea, "blur", this.desativa.bind(this))
        evento(this.textarea, "input", this.validacao.bind(this))

        

        const ferramentasDiv = document.createElement('div');
        ferramentasDiv.className = 'ferramentas';
        
        const primeiroConjuntoDiv = document.createElement('div');
        
        const button1 = document.createElement('button');
        button1.className = 'btn';
        const icon1 = document.createElement('span');
        icon1.className = 'material-symbols-outlined';
        icon1.textContent = 'mood';
        button1.appendChild(icon1);
        
        const button2 = document.createElement('button');
        button2.className = 'btn';
        const icon2 = document.createElement('span');
        icon2.className = 'material-symbols-outlined';
        icon2.textContent = 'gif_box';
        button2.appendChild(icon2);
        
        const button3 = document.createElement('button');
        button3.className = 'btn';
        const icon3 = document.createElement('span');
        icon3.className = 'material-symbols-outlined';
        icon3.textContent = 'photo_camera';
        button3.appendChild(icon3);
        
        const button4 = document.createElement('button');
        button4.className = 'btn';
        const icon4 = document.createElement('span');
        icon4.className = 'material-symbols-outlined';
        icon4.textContent = 'video_camera_front';
        button4.appendChild(icon4);
        
        primeiroConjuntoDiv.appendChild(button1);
        primeiroConjuntoDiv.appendChild(button2);
        primeiroConjuntoDiv.appendChild(button3);
        primeiroConjuntoDiv.appendChild(button4);
        
        const segundoConjuntoDiv = document.createElement('div');
        
        this.enviar = document.createElement('button');
        this.enviar.className = 'btn';
        const icon5 = document.createElement('span');
        icon5.className = 'material-symbols-outlined';
        icon5.textContent = 'send';
        this.enviar.appendChild(icon5);
        this.enviar.setAttribute("disabled", "")
        evento(this.enviar, "click", this.submissao.bind(this))
        

        segundoConjuntoDiv.appendChild(this.enviar);
        
        ferramentasDiv.appendChild(primeiroConjuntoDiv);
        ferramentasDiv.appendChild(segundoConjuntoDiv);
        this.boxComentario.appendChild(this.textarea);
        this.boxComentario.appendChild(ferramentasDiv);
        cardBodyDiv.appendChild(this.boxComentario);
        return cardBodyDiv;


    }
    
    submissao(){
        
        var infos = this.infos.bind(this)();
        
        var comentario = new Comentario(this.box, infos);
        
        
        this.gif = false;
        this.foto = false;
        this.video = false;
        this.texto = false;
        this.textarea.value = "";
        this.desativa.bind(this)()
    }
    
    boxComentarios(){
        this.box = document.createElement("DIV")
        this.box.classList.add("card-body")
        

        return this.box;

    }
    
    html(){
        var card = document.createElement("DIV")
        card.classList.add("card")
        
        card.appendChild(this.boxTexto.bind(this)())
        card.appendChild(this.boxComentarios.bind(this)())
        
        
        
        this.pai.appendChild(card)
    }
    
    ativa(){
        this.boxComentario.classList.add("ativo")
    }
    
    desativa(){
         if(!this.isValido.bind(this)()){
               this.boxComentario.classList.remove("ativo")
         }
      
    }
}


function  paginaComentarios(){
    var comentarios = document.getElementsByClassName("widthComentario")
    
    var i = 0;
    while(i < comentarios.length){
        
       //  new Comentarios(comentarios[i]);
        i++;
    }


}