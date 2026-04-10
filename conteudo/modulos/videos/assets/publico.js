// class CardVideo{
//     constructor(item){
//         this.item = item;
 
//         console.log(item)
//         this.titulo = this.item.titulo ?? ""
//         this.url = `${dominio}/videos/${item.url}`
//         console.log(this.item)
//         this.img = trataImagem(this.item.imagemDestaque, "media");
//     }
    
//     render(){
//         const div = document.createElement('div');
// div.className = 'cardVideo position-relative';

// // Criação do elemento div.card
// const card = document.createElement('div');
// card.className = 'card ratio ratio-16x9 border-0';
// card.style.backgroundImage = `url(${this.img})`; 
// card.style.backgroundSize =  "cover";
// card.style.backgroundPosition = "center";
// card.style.backgroundRepeat = "no-repeat";


// // Criação do elemento div.card-body
// const cardBody = document.createElement('div');
// cardBody.className = 'card-body';

// // Adicionando div.card-body como filho de div.card
// card.appendChild(cardBody);

// // Adicionando div.card como filho de div
// div.appendChild(card);

// // Criação do elemento div.d-flex.justify-content-start.gap-3.mt-2
// const flexContainer = document.createElement('div');
// flexContainer.className = 'd-flex justify-content-start gap-3 mt-2 contexto';

// // Adicionando flexContainer como filho de div
// div.appendChild(flexContainer);

// // Criação do primeiro div dentro do flexContainer



// const circleDiv = document.createElement('div');
// circleDiv.className = 'he-40 wi-40 rounded-circle bg-light p-2';

// var imglogo = document.createElement("IMG")
// imglogo.src = start.estrutura.mode.light.logo
// imglogo.classList.add("w-100")
// circleDiv.appendChild(imglogo)


// // Adicionando circleDiv como filho de flexContainer


// var pai = document.createElement("DIV")
// pai.classList.add("containerCanal")
// pai.appendChild(circleDiv);

// flexContainer.appendChild(pai)

// // Criação do segundo div dentro do flexContainer
// const textDiv = document.createElement('div');
// textDiv.className = 'd-flex flex-column gap-1';

// // Adicionando textDiv como filho de flexContainer
// flexContainer.appendChild(textDiv);

// // Criação do elemento a.stretched-link
// const link = document.createElement('a');
// link.className = 'stretched-link text-decoration-none';
// link.href = this.url;
// evento(link, "click", preventLink);

// // Adicionando link como filho de textDiv
// textDiv.appendChild(link);

// // Criação do elemento h2 dentro do link
// const h2 = document.createElement('h2');
// h2.className = 'fs-16 fw-700 m-0 text-decoration-none';
// h2.textContent = this.titulo;

// // Adicionando h2 como filho de link
// link.appendChild(h2);

// // Criação do elemento a dentro de textDiv
// const channelLink = document.createElement('a');
// channelLink.href = 'https://facebook.com.br';

// // Adicionando channelLink como filho de textDiv
// textDiv.appendChild(channelLink);

// // Criação do elemento h3 dentro de channelLink
// const h3 = document.createElement('h3');
// h3.className = 'fs-14 fw-400 m-0 d-none';
// h3.textContent = 'Nome do Canal';

// // Adicionando h3 como filho de channelLink
// channelLink.appendChild(h3);

// // Criação do elemento div.d-flex.justify-content-start.fs-12.gap-2
// const infoDiv = document.createElement('div');
// infoDiv.className = 'd-flex justify-content-start fs-12 gap-2';

// // Adicionando infoDiv como filho de textDiv
// textDiv.appendChild(infoDiv);

// // Criação do primeiro span dentro de infoDiv
// const viewsSpan = document.createElement('span');
// viewsSpan.classList.add("d-none")
// viewsSpan.textContent = '141 mil visualizações';

// // Adicionando viewsSpan como filho de infoDiv
// infoDiv.appendChild(viewsSpan);

// // Criação do segundo span dentro de infoDiv
// const timeSpan = document.createElement('span');

// console.log(this.item)
// timeSpan.textContent = belaData(this.item.dataCriacao);

// // Adicionando timeSpan como filho de infoDiv
// infoDiv.appendChild(timeSpan);
// return div;
//     }
// }

class HomeVideos{
    constructor(categoria){
        this.modelo = document.getElementsByClassName("modelo")[0]
        if(categoria){
            this.categoria = categoria
            this.pagina = 1
            this.api = new ApiNown("videos", "QBqCLDZsiyjDhSr", false);
            this.api.paginacao(12)
            this.api.setHash(categoria)
            this.primeiro = true
            this.chamarApi.bind(this)()
        }
        else{
            this.pagina = 1
            this.api = new ApiNown("videos", "d98KuXD8I4GyBfa", "videos");
            this.api.paginacao(12)
            this.primeiro = true
            this.chamarApi.bind(this)()
        }
        
        
        
        //this.api.start(this.listar.bind(this))
        //this.lista = document.getElementById("lista")
         
        this.carregando = false;
         
        evento(document.getElementById("conteudo"), "scroll", this.rolagem.bind(this))
        
        evento(document.getElementsByClassName('form-control')[0], 'input', this.inputando.bind(this))
    }
    
    chamarApi(){
        this.api.promisse().then((r)=>{
            if(this.categoria){
                console.log(r.extra)
                var mapaCategoria = new FastMap(r.extra, document.getElementById('informacoes-categoria'))
                
                mapaCategoria.render({
                    nome: ".nome"
                });
            }
            
            var lista = r.lista
            var i = 0;
            
            
            this.template = document.getElementsByClassName("cardVideo")[0].cloneNode(true);
            
            var fragmento = document.createDocumentFragment();
            if(this.primeiro){
                document.getElementById("gridVideos").innerHTML = ''
            }
            
            while(i < lista.length){
                var video = lista[i]

                
                var clone = this.template.cloneNode(true);

                var render = JSON.parse(video.render)
                // clone.getElementsByClassName('canal')[0].innerHTML = (render && render.autor && render.autor.nome) ? render.autor.nome : 'Sem Nome de usuário'; 
                // clone.getElementsByClassName('canal-link')[0].href = (render && render.autor && render.autor.nome) ? `${dominio}/videos/canal/${render.autor.user}` : '#'
                // clone.getElementsByClassName('containerCanal')[0].innerHTML = (render && render.autor && render.autor.foto)? `<img class="w-100 h-100" style="object-fit:cover" src="${trataImagem(render.autor.foto, 'mini')}"}>` : ''
                // clone.getElementsByClassName('visualizacao')[0].innerHTML = `1,9 mil visualizações`
                var mapa = new FastMap(video, clone);
                mapa.render({
                    titulo: ".titulo",
                    dataCriacao: [".data", belaData]
                });
                mapa.imagensbg({
                    imagemDestaque: ".capa"
                });
                mapa.links({url: [".link", `${dominio}/videos/`]})
                var div = document.createElement("DIV")
                div.className = "col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 px-0 py-2 p-md-2";
                div.appendChild(clone)
                
                
                fragmento.appendChild(div)

                i++;
                
            }
            
            document.getElementById("gridVideos").appendChild(fragmento)

        }, (r)=>{
            console.log("erro", r)
        })
    }
    
    rolagem(){
        this.primeiro = false
        var conteudo = document.getElementById("conteudo");
        var calc = conteudo.offsetHeight + conteudo.scrollTop >= conteudo.scrollHeight;
        if(calc){
            if(!this.carregando){
                this.carregando = true;
                this.loading.bind(this)()
                this.api.proximo();
                this.chamarApi.bind(this)()
            }
        }
    }
    
    inputando(){
        var cards = document.getElementsByClassName('modelo')
        var i = 0
        
        while(i < cards.length){
            var fica = true
            if(fica && event.target.value != ''){
                if(!cards[i].dataset.nome.toLowerCase().includes(event.target.value.toLowerCase())){
                    fica = false;
                }
            }
            
            if(fica){
                cards[i].classList.remove('d-none')
            }
            else{
                 cards[i].classList.add('d-none')
            }
            
            i++;
        }
    }
    
    
    acabou(){
           const elements = document.querySelectorAll('.preLoad');

// Remove cada elemento encontrado
elements.forEach(element => {
    element.remove();
});
    }
    
    
    loading(){
        if(!this.carrengado){
        this.carrengado = document.createElement("DIV")
        this.carrengado.classList.add("carregando", "he-200")
        this.carrengado.innerHTML = `
        <div class="d-flex justify-content-center h-100 align-items-center">
            <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        `
        this.lista.appendChild(this.carrengado)
            
        }
    }
    

    listar(r){

        if(this.carrengado){
            this.carrengado.remove();
            this.carrengado = false;
        }
        
 
        var prerender = document.getElementsByClassName("render")

        if(r.lista.length > 0){
            var lista = r.lista
            for(let i in lista){
               var item = lista[i]
               
               var card = new CardVideo(item);
               var html = card.render();
               
               var col = document.createElement("DIV")
               col.classList.add("col-12","col-md-6","col-lg-4","col-xl-3","col-xxl-3", "px-0", "py-2", "p-md-2")
               col.appendChild(html)
               
                document.getElementById("gridVideos").appendChild(col)
                
            }
            
         
            
            
            if(r.lista.length == 10){
                this.carregando = false;
            }else{
                this.acabou.bind(this)()
            }
            
        }
        
         this.acabou.bind(this)()
        
        const elementosNaoRenderizados = document.querySelectorAll('.render:not([data-renderizado])');
                elementosNaoRenderizados.forEach(elemento => {
                    elemento.remove();
                });
    }
    
    
}

class VideoItem {
    constructor() {
        this.resumo = document.getElementById("resumoVideo")
        this.sugeridos = document.getElementById("videosSugeridos")
        this.contentContainer = document.getElementById("conteudo")
        this.comentarios = document.getElementById("boxComentarios")
          
        this.btnVerComentario = document.getElementById("verComentarios")
        evento(document.getElementById("fecharComentario"), "click", this.fecharComentario.bind(this))


        evento(this.contentContainer , 'scroll', this.view.bind(this))
          
        this.modelo = document.getElementsByClassName("modelo")[0]
            
        // this.api = new ApiNown("videos", "d98KuXD8I4GyBfa");
        // this.api.paginacao(11)
        // this.api.start(this.lista.bind(this))
        // this.lista = document.getElementById("videosSugeridos")
        
        this.modelo = document.getElementsByClassName("modelo")[0]
        this.pagina = 1
        this.api = new ApiNown("videos", "d98KuXD8I4GyBfa", "videos");
        this.api.paginacao(12)
        this.primeiro = true
        this.chamarApi.bind(this)()
             
        this.carregando = false;
          
        this.api = new ApiNown("videos", "w9UMQrCU9JmQcnm");
        this.api.setHash(caminho.hash())
        this.api.promisse().then((r)=>{
            this.video = r.item
            
            this.start.bind(this)()
        })
    }
    
    chamarApi(){
        this.api.promisse().then((r)=>{
            var lista = r.lista
            var i = 0;
            
            
            this.template = document.getElementsByClassName("cardVideo")[0].cloneNode(true);
            
            var fragmento = document.createDocumentFragment();
            if(this.primeiro){
                document.getElementById("proximosVideos").innerHTML = ''
            }
            
            while(i < lista.length){
                var video = lista[i]

                
                var clone = this.template.cloneNode(true);

                var render = JSON.parse(video.render)
                // clone.getElementsByClassName('canal')[0].innerHTML = (render && render.autor && render.autor.nome) ? render.autor.nome : 'Sem Nome de usuário'; 
                // clone.getElementsByClassName('canal-link')[0].href = (render && render.autor && render.autor.nome) ? `${dominio}/videos/canal/${render.autor.user}` : '#'
                // clone.getElementsByClassName('containerCanal')[0].innerHTML = (render && render.autor && render.autor.foto)? `<img class="w-100 h-100" style="object-fit:cover" src="${trataImagem(render.autor.foto, 'mini')}"}>` : ''
                // clone.getElementsByClassName('visualizacao')[0].innerHTML = `1,9 mil visualizações`
                
                var mapa = new FastMap(video, clone);
                mapa.render({
                    titulo: ".titulo",
                    dataCriacao: [".data", belaData]
                });
                mapa.imagensbg({
                    imagemDestaque: ".capa"
                });
                mapa.links({url: [".link", `${dominio}/videos/`]})
                var div = document.createElement("DIV")
                div.appendChild(clone)
                
                
                fragmento.appendChild(div)

                i++;
                
            }
            
            document.getElementById("proximosVideos").appendChild(fragmento)

        }, (r)=>{
            console.log("erro", r)
        })
    }
    
    loading(){
        if(!this.carrengado){
        this.carrengado = document.createElement("DIV")
        this.carrengado.classList.add("carregando", "he-200")
        this.carrengado.innerHTML = `
        <div class="d-flex justify-content-center h-100 align-items-center">
            <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        `
        this.lista.appendChild(this.carrengado)
            
        }
    }
    
    // lista(r){
    //     var url = window.location.href


    //     var explode = url.split(`${dominio}/`)
    //     var url = explode[1].split("/");
    //     var prerender = document.getElementsByClassName("render")
            
    //     if(r.lista.length > 0){
    //         var lista = r.lista
    //         for(let i in lista){
    //             if(lista[i].url != url[1]){
    //                  var item = lista[i]
    //                   var card = new CardVideo(item);
    //                   var html = card.render();
    //                   document.getElementById("proximosVideos").appendChild(html)
            
    //             }
    //         }
            
    //         if(r.lista.length == 10){
    //             this.carregando = false;
    //         }else{
    //             this.acabou.bind(this)()
    //         }
            
    //     }
        
    //     this.acabou.bind(this)();
        
    //     const elementosNaoRenderizados = document.querySelectorAll('.render:not([data-renderizado])');
    //         elementosNaoRenderizados.forEach(elemento => {
    //             elemento.remove();
    //     });
    // }
    
    acabou(){
       const elements = document.querySelectorAll('.preLoad');

// Remove cada elemento encontrado
elements.forEach(element => {
    element.remove();
});
    }
  
    extrairIdDoYouTube(url) {
      // Expressão regular para encontrar o ID do vídeo do YouTube na URL
      var regExp = /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
      
      // Testa a expressão regular na URL fornecida
      var match = url.match(regExp);
      
      if (match && match[1]) {
        // Se houver correspondência, retorna o ID do vídeo
        return match[1];
      } else {
        // Caso contrário, retorna uma mensagem de erro ou null
        return null;
      }
    }
    
    extrairIdDoVimeo(url) {
      // Expressão regular para encontrar o ID do vídeo do Vimeo na URL
      var regExp = /^(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com\/)(\d+)/;
      
      // Testa a expressão regular na URL fornecida
      var match = url.match(regExp);
      
      if (match && match[1]) {
        // Se houver correspondência, retorna o ID do vídeo
        return match[1];
      } else {
        // Caso contrário, retorna uma mensagem de erro ou null
        return null;
      }
    }
  
    start(){
        var map = new FastMap(this.video);

        map.render({
           titulo: ".titulo",
        });
        console.log(this.video)
        
        if(this.video.origem == 1){
            var video = new PlayerVideo("youtube", this.extrairIdDoYouTube(this.video.link));
        }else{
            var video = new PlayerVideo("vimeo", this.extrairIdDoVimeo(this.video.link));
        }
        
      
        var atributos = {};
      
        
        if(this.video.imagemDestaque && JSON.parse(this.video.imagemDestaque).length > 0){
            var capa = trataImagem(this.video.imagemDestaque);
        }
        else{
            var capa = ''
        }
      
        video.atributos({'poster':capa})
        video.render(document.getElementById("containerVideo"));
        
        
        //var comentario = new Comentario("videos", parseInt(this.video.id) , document.getElementById("cardComentario"), document.getElementById('containerVideo'));
        //comentario.setDeskTop(document.getElementById("caixaComentario"))
  }
  
    render(){
     loadResources(`${dominio}/assets/js/classes/comentarios.js`).then(()=>{
          var comentario = new BoxComentario(document.getElementById("containerComentario"), 0)
          comentario.set("videos", this.video.id)
          comentario.atributos({'poster' : 'https://nown.com.br/conteudo/uploads/imagens/newUploader/2024/04/c5vanw0da0ao5y3-1714487687/media.webp'})
          comentario.render();
          comentario.listar();
     }) 
  }
  
    view(){
        this.primeiro = true
      if (this.contentContainer.scrollTop < 100) {
    document.getElementById("containerVideo").classList.remove("ativo")
    document.getElementById("espaceMobile").classList.remove("ativo")

  } else {
      document.getElementById("containerVideo").classList.add("ativo")
      document.getElementById("espaceMobile").classList.add("ativo")
  
  }

  }
  
    fecharComentario(){
       this.comentarios.classList.remove("ativo")
      this.view.bind(this)()
  }
  
    verComentario(){
      document.getElementById("containerVideo").classList.add("ativo")
      this.comentarios.classList.add("ativo")
  }

 
}

function paginaVideos(){
    var url = window.location.href.split(dominio)[1].split("/")
    let newArray = url.filter(item => item !== '');
    
    
    if(newArray.length == 1){
        new HomeVideos();
    }else{
        switch(newArray[1]){
            case 'canal':
                break;
            default:
                new VideoItem();
                break;
            case 'categorias':
                if(newArray[2]){
                    new HomeVideos(newArray[2]);
                }
                break;
        }
    }

}