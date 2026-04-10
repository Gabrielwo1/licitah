class BoxComentario{
    constructor(item){
        this.item = item

    }
    
    render(){

            
            const component = document.createElement('div');
            component.classList.add("animate__fadeIn", "animate__animated")
            component.style.margin = '15px 0';
            
            
            const sobre = document.createElement("DIV")
            sobre.classList.add("d-flex", "justify-content-start", "gap-2")
            
            var nome = document.createElement("SPAN")
            nome.classList.add("fs-12", "fw-700")
            nome.innerText = "nome do cara"
            
            var data = document.createElement("SPAN")
            data.classList.add("fs-12", "fw-400")
            data.innerText = belaData(this.item.data)
            
            sobre.appendChild(nome)
            sobre.appendChild(data)
          

            const outerDiv = document.createElement('div');
            outerDiv.className = 'd-flex justify-content-start gap-3';

            const innerDiv1 = document.createElement('div');
            const circleDiv = document.createElement('div');
            circleDiv.className = 'he-40 wi-40 bg-danger rounded-circle';
            innerDiv1.appendChild(circleDiv);

            const innerDiv2 = document.createElement('div');
            innerDiv2.appendChild(sobre)
            const paragraph = document.createElement('p');
            paragraph.className = 'm-0 fs-16';
            paragraph.textContent = this.item.texto
            innerDiv2.appendChild(paragraph);

            const buttonDiv = document.createElement('div');
            buttonDiv.className = 'd-flex justify-content-start gap-3';

            const likeButton = document.createElement('button');
            likeButton.className = 'btn btn-sm px-0';
            likeButton.innerHTML = '<i class="bi bi-hand-thumbs-up-fill fs-12"></i>';

            const replyButton = document.createElement('button');
            replyButton.className = 'btn btn-sm d-flex justify-content-start align-items-center gap-2 px-0';
            replyButton.innerHTML = 'Responder';

            buttonDiv.appendChild(likeButton);
            buttonDiv.appendChild(replyButton);
            innerDiv2.appendChild(buttonDiv);

            outerDiv.appendChild(innerDiv1);
            outerDiv.appendChild(innerDiv2);

            component.appendChild(outerDiv);
            return component;
    }
}

class Comentario{
    constructor(tipo, id, target, limit = false){
        this.tipo = tipo;
        this.id = id;
        evento(target, "click", this.show.bind(this))
        this.limit = limit;
        
        this.startado = false;
        // this.request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/ajax.php`);
        this.request = new RequestRote('comentarios', 'ajax');
        this.renders = {};
        
        this.desktop = false;
        
        
        this.texto = "";
        
        
        if(this.limit){
            const containerVideo = this.limit;
            const windowHeight = window.innerHeight;
            const containerHeight = containerVideo.offsetHeight;
            const remainingHeight = windowHeight - containerHeight;
            
            this.janela = windowHeight;
            this.limitComponente = containerHeight;
            this.resto = remainingHeight;


            
        }

    }
    
    setDeskTop(componente){
        this.desktok = componente
        
        this.renderDesk.bind(this)()
    }
    
    renderDesk(){
            // Cria o container principal
    const card = document.createElement('div');
    card.className = 'card card-nown';

    // Cria o header do card
    const cardHeader = document.createElement('div');
    cardHeader.className = 'card-header bg-transparent';

    const headerContent = document.createElement('div');
    headerContent.className = 'd-flex justify-content-between align-items-center';

    const commentCount = document.createElement('div');
    commentCount.className = 'fs-26 fw-700';
    commentCount.textContent = '37.702 comentários';

    const selectContainer = document.createElement('div');
    const select = document.createElement('select');
    select.className = 'form-select';
    const optionRecentes = document.createElement('option');
    optionRecentes.textContent = 'Recentes';
    const optionPrincipais = document.createElement('option');
    optionPrincipais.textContent = 'Principais';
    select.append(optionRecentes, optionPrincipais);
    selectContainer.appendChild(select);

    headerContent.append(commentCount, selectContainer);
    cardHeader.appendChild(headerContent);

    // Cria a área do formulário de comentário
    const commentForm = document.createElement('div');
    commentForm.className = 'comment-form';

    const holderCommentForm = document.createElement('div');
    holderCommentForm.className = 'holder-comment-form';

    const thumb = document.createElement('div');
    thumb.className = 'thumb';
    const img = document.createElement('img');
    img.src = 'https://fortram.site/avatar/?size=40';
    thumb.appendChild(img);

    const input = document.createElement('div');
    input.className = 'input p-0';

    const textarea = document.createElement('textarea');
    this.textareaDesk = textarea
    evento(textarea, "keydown", this.digitando.bind(this))
    textarea.placeholder = 'Digite seu comentário...';

    const actions = document.createElement('div');
    actions.className = 'actions';
    const button = document.createElement('button');
    button.className = 'btn-comment';
    const icon = document.createElement('i');
    icon.className = 'bi bi-send-fill';
    button.appendChild(icon);
    actions.appendChild(button);

    input.append(textarea, actions);
    holderCommentForm.append(thumb, input);
    commentForm.appendChild(holderCommentForm);

    // Cria o corpo do card
    const cardBody = document.createElement('div');
    cardBody.className = 'card-body';
    const commentsList = document.createElement('div');
    commentsList.classList.add('comments-list');
    this.listaDesk = commentsList;
    cardBody.appendChild(this.listaDesk)
     var i = 0;
            while(i < 4){
                var linhas = document.createElement("DIV")
                linhas.style = "height: 90px; border-radius: 10px; margin: 15px 0;"
                linhas.classList.add("bg-carregando")
                this.listaDesk.appendChild(linhas)
            
                
                i++;
            }

    const noCommentsMessage = document.createElement('h2');
    noCommentsMessage.className = 'fs-12 text-center m-0';
    noCommentsMessage.textContent = 'Sem Comentários';

    cardBody.appendChild(noCommentsMessage);

    // Monta o card
    card.append(cardHeader, commentForm, cardBody);
     this.desktok.appendChild(card)
     
     
              this.request.addData(
                        {
                          "acao":"listar",
                          "tipo": this.tipo,
                          "idTipo": this.id,  
                        }
                        )
                    this.request.send().then((r)=>{
                        var lista = r.lista
                        var i = 0;
                        if(lista.length > 0){
                           while(i < lista.length){
                            var item = lista[i]
                            this.renders[item.id] = new BoxComentario(item);
                            this.listaDesk.appendChild(this.renders[item.id].render());
                            
                            i++;
                        } 
                        }
                        
                        this.limpa.bind(this)(this.listaDesk)
                        this.startado = true;
                    })

    }
    
    show(){

        this.render.bind(this)().then(()=>{
             this.lista.innerHTML = "";
             
             if(this.limit){
                this.limit.classList.add("comentarioAtivo")
             }
            
            var i = 0;
            while(i < 4){
                var linhas = document.createElement("DIV")
                linhas.style = "height: 90px; border-radius: 10px; margin: 15px 0;"
                linhas.classList.add("bg-carregando")
                this.lista.appendChild(linhas)
            
                
                i++;
            }
        

            this.ovComments.style.display = 'block';
                setTimeout(()=>{
                    this.ovComments.style.opacity = 1;
                }, 100)
                this.section.style.bottom = 0;
                
                
                if(!this.startado){
                    
                    this.request.addData(
                        {
                          "acao":"listar",
                          "tipo": this.tipo,
                          "idTipo": this.id,  
                        }
                        )
                    this.request.send().then((r)=>{
                        var lista = r.lista
                        var i = 0;
                        if(lista.length > 0){
                           while(i < lista.length){
                            var item = lista[i]
                            this.renders[item.id] = new BoxComentario(item);
                            this.lista.appendChild(this.renders[item.id].render());
                            
                            i++;
                        } 
                        }
                        
                        this.startado = true;
                    })
                }
                else{
                        if(Object.keys(this.renders).length > 0){
                            for(let c in this.renders){
                                this.lista.appendChild(this.renders[c].render());

                            }

                        }
                }
                
                this.limpa.bind(this)(this.lista)
                
        })
    }
    
    limpa(lista) {
    // Seleciona todos os elementos com a classe 'bg-carregando'
    
    
    const elements = lista.querySelectorAll('.bg-carregando');
    
    // Itera sobre cada elemento e remove-o do DOM
    elements.forEach(element => {
        element.remove();
    });
}
    
    fechar(){
        this.ovComments.style.opacity = 0;
        setTimeout(()=>{
            this.ovComments.style.display = 'none';
        }, 270)
        this.section.style.bottom = this.section.getBoundingClientRect().height * -1;
         if(this.limit){
                this.limit.classList.remove("comentarioAtivo")
             }
    }
    
    rules(){
        
    }
    
    enviar(){
 
        if(this.texto){
            var texto = this.texto
            this.texto = "";
            if(this.textarea){
                 this.textarea.value = "";
            }
            if(this.textareaDesk){
                this.textareaDesk.value = ""
            }
           
   
              this.request.addData(
                        {
                          "acao":"cadastrar",
                          "tipo": this.tipo,
                          "idTipo": this.id,  
                          "comentario": texto
                        }
                        )
                this.request.send().then((r)=>{
                     this.renders[r.id] = new BoxComentario(
                         {
                             "aprovado": "teste",
                             "autor": "teste",
                             "data": 0,
                             "id": r.id,
                             "texto": texto,
                             "tipo": this.tipo,
                             "tipoId": this.id

                         }
                         );
                         
                         if(this.lista){
                                var html = this.renders[r.id].render()
                                this.lista.appendChild(html);
                                
                                /*
                                se for do mobile
                                
                                            html.scrollIntoView({
                             behavior: 'smooth', // or 'auto'
                             block: 'end', // or 'end', 'center', 'nearest'
                             inline: 'end' // or 'start', 'center', 'end'
                             });
                             
                             */
                         }
                         
                         if(this.listaDesk){
                             var html = this.renders[r.id].render()
                             if (this.listaDesk.firstChild) {
    this.listaDesk.insertBefore(html, this.listaDesk.firstChild);
} else {
    this.listaDesk.appendChild(html);
}
                         }
                      



                }, (r)=>{
                    console.log("erro", r)
                })
            
            
            
            
        }    
    }
    
    digitando(){
        
 
        
        if (event.keyCode === 13) {
            event.preventDefault(); 
            this.enviar.bind(this)(); 
    }
    
    this.texto = event.currentTarget.value
    
    var foco = false;
    if(this.textareaDesk && this.textarea){
        if(this.textareaDesk == event.currentTarget){
            this.textarea.value = this.textareaDesk.value
        }else{
            this.textareaDesk.value = this.textarea.value
        }

    }

    }
    
    render(){
          return new Promise((resolve, reject) => {
        if(!document.getElementById("boxComentario")){
            
            var div = document.createElement("DIV")
            div.id = "boxComentario"
            
            this.ovComments = document.createElement('div');
            this.ovComments.classList.add('overlay-comments');
   
            
            this.section = document.createElement('section');
            this.section.classList.add('comments-viewer');
            
            
            if(this.limit){
                this.section.style.maxHeight = `${this.resto}px`
                this.section.style.borderRadius = `0px`
                this.ovComments.style.background = "transparent";
            }
            
            
            
            
            const controller = document.createElement('div');
            controller.classList.add('controller');
            
            const postComments = document.createElement('div');
            postComments.classList.add('post-comments');
            
            const commentsa = document.createElement('div');
            commentsa.classList.add('comments');
            
            const commentsTitle = document.createElement('h5');
            commentsTitle.textContent = 'Comentários da publicação';
            
            const commentsList = document.createElement('div');
            commentsList.classList.add('comments-list');
            this.lista = commentsList;

            const commentForms = document.createElement('div');
            commentForms.classList.add('comment-form');
            
            const holderCommentForm = document.createElement('div');
            holderCommentForm.classList.add('holder-comment-form');
            
            const thumb = document.createElement('div');
            thumb.classList.add('thumb');
            
            const img = document.createElement('img');
            img.setAttribute('src', 'https://fortram.site/avatar/?size=40');
            
            const input = document.createElement('div');
            input.classList.add('input');
            
            const textarea = document.createElement('textarea');
            textarea.setAttribute('placeholder', 'Digite seu comentário...');
            this.textarea = textarea;
            evento(textarea, "keydown", this.digitando.bind(this))
            if(this.texto){
                this.textarea.value = this.texto
            }
            
            const actions = document.createElement('div');
            actions.classList.add('actions');
            
            const btnComment = document.createElement('button');
            btnComment.classList.add('btn-comment');
            btnComment.innerHTML = '<i class="bi bi-send-fill"></i>';
            evento(btnComment, "click", this.enviar.bind(this))
            
            thumb.appendChild(img);
            input.appendChild(textarea);
            input.appendChild(actions);
            actions.appendChild(btnComment);
            holderCommentForm.appendChild(thumb);
            holderCommentForm.appendChild(input);
            commentForms.appendChild(holderCommentForm);
            commentsa.appendChild(commentsTitle);
            commentsa.appendChild(commentsList);
            postComments.appendChild(commentsa);
            postComments.appendChild(commentForms);
            this.section.appendChild(controller);
            this.section.appendChild(postComments);
            div.appendChild(this.ovComments);
            div.appendChild(this.section);
            
            
      
            document.getElementById("conteudo").appendChild(div)
            
            evento(this.ovComments, "click", this.fechar.bind(this))
            
                  var comments = this.section;
                  var ovComments = this.ovComments
                  
                  var hammerComments = new Hammer(comments, {});
                  
                  
            hammerComments.on('pan', (ev)=> {
                comments.style.transition = 'none';
    	        comments.style.bottom = (ev.deltaY >= 0 ? ev.deltaY : 0) * -1;
    	        if(ev.deltaY >= 0){
    	            ovComments.style.opacity = ((Math.round((ev.deltaY / comments.getBoundingClientRect().height) * 100) / 100) - 1) * -1;
    	        }
            });
            
            hammerComments.on('panend', (ev) =>{
                comments.style.transition = '.27s ease';
        	    if(ev.deltaY > comments.getBoundingClientRect().height * 0.6 || ev.velocityY > 0.8){
        	        this.fechar.bind(this)()
        	    } else {
        	        comments.style.bottom = 0;
        	    }
            });
            hammerComments.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });
            
            
            
            setTimeout(()=>{
                resolve();
            }, 200)
           
            
        }else{
           resolve(); 
        }
                
          })
          
                      reject('Element already exists');

    }

}



function paginaComentario(){

    var btn = document.getElementById("comentario")
    new Comentario("artigos", 20 , btn);
    

}