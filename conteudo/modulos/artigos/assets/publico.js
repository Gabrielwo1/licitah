class Temperatura {
    constructor() {
        // Inicializa o processo ao instanciar a classe
        setTimeout(()=>{
            this.init();
        }, 1000)
    }

   
   removerAcentos(str) {
    // Usa normalize para converter caracteres acentuados em suas versões não acentuadas
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}
   
    init() {
        var cidade = pegaLocal("cidade")
        if(cidade){
            
            

            
            
            
           this.apiKey = '044bf97b2a48ad89a84ed0a77341e8ef'; 
        this.city = cidade;
        this.apiUrl = `https://api.openweathermap.org/data/2.5/forecast?q=${this.removerAcentos(this.city)}&appid=${this.apiKey}&units=metric&lang=pt_br`;

        // Faz a requisição para a API
        fetch(this.apiUrl)
            .then(response => response.json())
            .then(data => {
                this.renderCard(data);
            })
            .catch(error => console.error('Erro ao obter dados: ', error)); 
        }
        
    }
    
    arredondar(numero) {

    const num = parseFloat(numero);

   
    if (isNaN(num)) {
        throw new Error("Entrada inválida: deve ser um número ou uma string que representa um número.");
    }

    const parteDecimal = num % 1;

    if (parteDecimal > 0.5) {
        return Math.ceil(num);
    } else {
        return Math.floor(num); 
    }
}

    // Função para renderizar o card com os dados da previsão do tempo
    renderCard(data) {
        

        console.log(data.li)
        const today = new Date().getDate();

        // Obtém a previsão para manhã, tarde e noite do dia atual
        const previsaoManha = data.list[0];
        const previsaoTarde = data.list[19];
        const previsaoNoite = data.list[39];
        


        // Verifica se as previsões existem antes de tentar acessá-las
        if (!previsaoManha || !previsaoTarde || !previsaoNoite) {
            console.error('Previsão para o dia atual não encontrada em alguns horários.');
            return;
        }

        // Obtém as temperaturas mínima e máxima para o dia
        const tempMin = Math.min(...data.list.filter(item => new Date(item.dt_txt).getDate() === today).map(item => item.main.temp_min));
        const tempMax = Math.max(...data.list.filter(item => new Date(item.dt_txt).getDate() === today).map(item => item.main.temp_max));

        // Monta o HTML para o card com as informações da previsão
        const cardHtml = `
           <div class="card card-nown">
           <div class="card-header">
            <h2 class="m-0 fs-18">Previsão do Tempo</h2>
           </div>
   <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">${this.city}</h5>
      <div>
        <span>${this.arredondar(tempMax)}°C</span>
        <span>/</span>
        <span>${this.arredondar(tempMin)}°C</span>
      </div>
      </div>

      <div class="d-flex justify-content-center gap-2 align-items-center">
         <div>
            <h6 class="mt-3 text-center mb-0">Manha</h6>
            <img src="https://openweathermap.org/img/wn/${previsaoManha.weather[0].icon}@2x.png" alt="${previsaoManha.weather[0].description}">
         </div>
         <div>
            <h6 class="mt-3 text-center mb-0">Tarde</h6>
            <img src="https://openweathermap.org/img/wn/${previsaoTarde.weather[0].icon}@2x.png" alt="${previsaoTarde.weather[0].description}">
         </div>
         <div>
            <h6 class="mt-3 text-center mb-0">Noite</h6>
            <img src="https://openweathermap.org/img/wn/${previsaoNoite.weather[0].icon}@2x.png" alt="${previsaoNoite.weather[0].description}">
         </div>
      </div>
   </div>
</div>
        `;

        // Adiciona o card ao elemento da página
        document.getElementById('lateralBlog').innerHTML = cardHtml;
        
      
    }
}

class FluxoArtigos{
    constructor(){
        this.init.bind(this)();
    }
    
    cardLoading(){
        var div = document.createElement("DIV")
        div.classList.add("componenteCarregando")
        div.innerHTML = `
        
        <div>
                <div class="row">
                    <div class="col-12 col-xl-5">
                    <div class="ratio ratio-16x9 bg-carregando rounded">
                        
                    </div>
                </div>
                <div class="col-12 col-xl-7">
                    <div class="d-flex flex-column gap-2">
                        <div class="fs-14 fw-700 mt-2">
                            <div class="bg-carregando" style="height: 14px"></div>
                        </div>
                  
                        <h2 class="m-0 fs-22">
                            <div class="bg-carregando" style="height: 22px"></div>
                            <div class="bg-carregando mt-2" style="height: 22px"></div>
                        </h2>
                 
                    <div class="fs-14">
                          <div class="bg-carregando" style="height: 14px"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-12">
                                  <div class="bg-carregando  wi-100" style="height: 14px"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <div class="wi-20 he-20 bg-carregando">
                              </div>
                              <div class="wi-20 he-20 bg-carregando">
                              </div>
                              <div class="wi-20 he-20 bg-carregando">
                              </div>
                            
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
       
        `
        return div;
     
    }
    
    card(item){
        

        
        var fav = item?.numbers?.favoritos.i || false;

        
        let imgSize = ``
        let colSize = "col-xl-12"
        var imagem = trataImagem(item.imagemDestaque, "media")
        if(imagem){
            imgSize = `
            <div class="col-12 col-xl-5">
                   <a class="text-decoration-none linkColor" href="${dominio}/artigos/${item.url}">
                    <div class="ratio ratio-16x9 rounded background" style="background-image: url(${imagem})" >
                        
                    </div>
                    </a>
                </div>
            `
            colSize = "col-xl-7"
        }
        
        let categoria = item?.categoria?.nome || false;
        var htmlcategoria = "";
        if(categoria){
           htmlcategoria = `<div class="fs-14 fw-700 mt-2 mt-xl-0"><a class="linkColor text-decoration-none" href="${dominio}/artigos/categorias/${item.categoria.url}">${categoria}</a></div>`;
        } 
        
        let descricao = "";
        if(item.descricao && item.descricao.trim()){
            descricao = `<div class="fs-16">
            <a class="text-decoration-none linkColor" href="${dominio}/artigos/${item.url}">
                        ${item.descricao}
                        </a>
                    </div>`
        }
        
        
        let mt = "";
        if(!htmlcategoria){
            mt = "mt-3";
        }
        
         var div = document.createElement("DIV")
         div.innerHTML = `
    
                <div class="row">
                    ${imgSize}
                <div class="col-12 ${colSize}">
                    <div class="d-flex flex-column gap-2">
                      ${htmlcategoria}  
                    <a href="${dominio}/artigos/${item.url}" class="text-decoration-none ${mt}">
                        <h2 class="m-0 fs-24">${item.titulo}</h2>
                    </a>
                    ${descricao}
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-12" title="${dataBela(item.dataCriacao)}">Há ${tempoRelativo(item.dataCriacao)}</div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btnFavoritar" data-estrutura="artigos" data-url="${item.url}" data-ativo="${fav}"></button>
                              <button class="btn btn-sm btnCompartilhar" data-url="${item.url}" data-estrutura="artigos">
                                <i class="bi bi-share"></i>
                            </button>
                              <button class="btn btn-sm plus" data-url="${item.url}" data-estrutura="artigos">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            
                        </div>
                    </div>
                    </div>
                </div>
                </div>
          
            
         
         `
         evento(div.getElementsByClassName("plus")[0], "click", ()=>{
             dropopcoes(this.opcoes.bind(this))
         }) 
         evento(Array.from(div.getElementsByTagName("a")), "click", preventLink)
         return div;
    }
    
    opcoes(canva, btn){
        console.log(btn)
        
        var objs = [
            {"nome": "Salvar", "icone": "bi bi-floppy", "cb": false },
            {"nome": "Denunciar", "icone": "bi bi-flag", "cb": this.denunciar.bind(this) , "data": {estrutura: btn.dataset.estrutura, "url": btn.dataset.url}}
            ]
            
        canva.add(objs)
    
    }
    
    denunciar(){
        var btn = event.currentTarget
        denunciar(btn.dataset.estrutura, btn.dataset.url);
    }
    
    load(url = false){
        this.api = new ApiNown("artigos", "c5BdK2RDSYwM9jk", "artigo");
        this.api.paginacao(12)
        this.api.pagina = this.pagina
        this.api.promisse().then((r)=>{
            var lista = r.lista;
            var fragmento = document.createDocumentFragment();
            var i = 0;
            while(i < lista.length){
                if(!url || url != lista[i].url){
                    let card = this.card.bind(this)(lista[i]);
                    var border = document.createElement("DIV")
                    border.classList.add("border-bottom")
                    if(document.getElementsByClassName("onlyPc").length > 0){
                        let cc = document.getElementsByClassName("onlyPc")[0];
                        card.classList.add("d-block", "d-xl-none")
                        border.classList.add("d-block", "d-xl-none")
                        
                        var a = document.createElement("A")
                        a.href  = `${dominio}/artigos/${lista[i].url}`
                        a.innerText = lista[i].titulo;
                        a.classList.add("stretched-link","text-decoration-none","linkColor")
                        evento(a, "click", preventLink)
                        cc.getElementsByClassName("titulo")[0].innerHTML = "";
                        cc.getElementsByClassName("titulo")[0].appendChild(a)
                        
                        
                        if(cc.getElementsByClassName("descricao").length == 1){
                             var desc = lista[i].descricao.trim();
                             document.getElementsByClassName("onlyPc")[0].getElementsByClassName("descricao")[0].innerText = desc ? desc : "";
                        }
                        
                        var categoria = lista[i]?.categoria?.nome || false;
                        cc.getElementsByClassName("categoria")[0].innerHTML = "";
                        if(categoria){
                            var ac = document.createElement("A")
                            ac.innerText = categoria
                            ac.classList.add("stretched-link","text-decoration-none","linkColor")
                            ac.href = `${dominio}/artigos/categorias/${lista[i].categoria.url}`
                            document.getElementsByClassName("onlyPc")[0].getElementsByClassName("categoria")[0].appendChild(ac);
                        }
                        
                        
  
                        var imagem = trataImagem(lista[i].imagemDestaque , "media")
                        if(imagem && i != 0){
                            cc.classList.add("background")
                            
                            var black = document.createElement("DIV")
                            black.classList.add("w-100", "h-100", "position-absolute", "top-0", "start-0", "z-1")
                            black.style.backgroundColor = `rgba(0, 0, 0, 0.50)`;
                            cc.insertBefore(black, cc.firstChild);
                            cc.classList.add("overflow-hidden")
                            cc.getElementsByClassName("card-body")[0].classList.add("position-relative", "z-2")

                            cc.style.backgroundImage = `url(${imagem})`
                            if(a){
                                a.classList.remove("linkColor")
                                a.classList.add("text-light")
                            }
                            if(ac){
                                ac.classList.remove("linkColor")
                                ac.classList.add("text-light")
                            }
                        }
                        
                        cc.classList.remove("onlyPc")
                      
                        
                    }
                    
                    fragmento.appendChild(card);
                    
                    
                    fragmento.appendChild(border)
                }
                
                
                
                i++;
            }
            this.grid.appendChild(fragmento)
            new Compartilhar();
            new Favoritos();
            
                 if(lista.length == 12){
                    loadLore(this.grid, this.mais.bind(this))
                 }
                 limpaLoad();
         
        })
    }
    
    mais(){
        this.carregando.bind(this)();
        this.pagina++;
        this.load.bind(this)();
    }
    
    carregando(){
        var fragmento = document.createDocumentFragment()
        var i = 0;
        while(i < 10){
            fragmento.appendChild(this.cardLoading())
            var div = document.createElement("DIV")
            div.classList.add("border-bottom", "componenteCarregando")
            fragmento.appendChild(div)
            i++;
        }
        this.grid.appendChild(fragmento)
    }
    
    home(){
        this.grid = document.getElementById("gridArtigos");
        this.carregando.bind(this)();
        this.pagina = 1;
        this.load.bind(this)();
        
    }
    
    item(url){

        this.api = new ApiNown("artigos", "wW4IcmPAZWm39ta", "artigo");
        this.api.setHash(url);
        this.api.promisse().then((r)=>{
            if(r.item){
                var item = r.item
                console.log(item)
                document.getElementById("titulo").innerText = item.titulo
                
                if(item.descricao && item.descricao.trim()){
                    document.getElementById("descricao").innerText = item.descricao
                }else{
                    document.getElementById("descricao").remove();
                }
                document.getElementsByClassName("comments")[0].dataset.url = item.url
                document.getElementsByClassName("btnCompartilhar")[0].dataset.url = item.url
                
                document.getElementById("textoArea").innerHTML = item.texto
                
                var autor = item?.autor?.display || false;
                if(autor){
          
                    document.getElementById("autorArea").innerHTML = `Por <a class="text-primaria text-decoration-none" href="${dominio}/artigos/autores/${item.autor.user}">${item.autor.display}</a>`
                }else{
                    document.getElementById("autorArea").remove();
                }
                
                if(item.fonte){
                    var texto = item.nome_fonte ?? item.fonte
                    document.getElementById("fonteArea").innerHTML = `Fonte: <a class="text-primaria text-decoration-none" target="_blank" href="${item.fonte}">${texto}</a>`
                }
                 
       
                var data = dataBr(item.dataCriacao);
                
                if(item.dataCriacao != item.dataUpdate){
                    data = `${data} Atualizado há ${tempoRelativo(item.dataUpdate)}`
                }
                
                
                
                document.getElementById("dataArea").innerText = data;
                let video = false;
                if(item.video && item.video.trim()){
                    video = item.video.trim();
                    var div = document.createElement("DIV")
                    div.id = `videoArea`
                    this.divVideo = div;
                    document.getElementById("containerImagem").appendChild(div)
                    var v = new PlayerVideo();
                    v.setURL(video)
                    v.render(document.getElementById("videoArea"))
                    setTimeout(()=>{
                         document.getElementById("containerImagem").getElementsByClassName("bg-carregando")[0].remove(); 
      
                         
                          observador(document.getElementById("conteudo"), div, this.tocando.bind(this));
                    }, 200)
                  
                    
                    
                    
                    
                }
                
                if(!video){
                    var imagem = trataImagem(item.imagemDestaque, "grande")
                if(imagem){
                    var div = document.createElement("DIV")
                    var img = document.createElement("IMG")
                    img.src = imagem
                    img.classList.add("w-100", "rounded")
                    div.appendChild(img)
                    document.getElementById("containerImagem").appendChild(div)
                    img.onload = ()=>{
                       document.getElementById("containerImagem").getElementsByClassName("bg-carregando")[0].remove(); 
                    }
                }else{
                   document.getElementById("containerImagem").remove(); 
                }
                }
                
                if(item.galeria && JSON.parse(item.galeria).length > 0){
                    this.galeriaAtt(JSON.parse(item.galeria));
                }
                
                
                nownFiles.add(`${dominio}/conteudo/modulos/comentarios/assets/widgets.js`).then(()=>{
                new Comentarios();
            })
                
                this.grid = document.getElementById("gridArtigos");
                this.pagina = 1;
                this.load.bind(this)(url);
                preSEO(item.seo);
                
                
            }else{
                naoExiste();
            }
        }, (r)=>{
           naoExiste();
        })
    }
    
    shuffle(imagem, numero){
        var shuffleItem = document.createElement('div');
        shuffleItem.classList.add('col-12', 'col-md-6', 'col-lg-4', 'shuffle-item');
    
        // Cria o link
        var link = document.createElement('a');
        link.href = imagem;
        link.classList.add('item', 'lightbox-link', 'hover-zoom-rotate');
    
        // Cria o wrapper da imagem
        var imageWrapper = document.createElement('div');
        imageWrapper.classList.add('image-wrapper', 'small-shadow', 'rounded');
    
        // Cria a imagem
        var img = document.createElement('img');
        img.src = imagem;
        img.classList.add('w-100');
        img.alt = `Imagem ${numero + 1}`;
    
        // Cria a overlay
        var overlay = document.createElement('div');
        overlay.classList.add('overlay', 'black-50');
    
        // Monta a estrutura
        imageWrapper.appendChild(img);
        imageWrapper.appendChild(overlay);
        link.appendChild(imageWrapper);
        shuffleItem.appendChild(link);
    
        return shuffleItem;
    }
    
    funcaoParaEsperar(galeria){
        var i = 0
        var fragmento = document.createDocumentFragment();

        galeria.forEach((img, i) => {
            if (img) {
                fragmento.appendChild(this.shuffle.bind(this)(trataImagem(img, 'media'), i));
            }
        });
     
        document.getElementsByClassName('artigo-galeria')[0].getElementsByClassName('shuffle-container')[0].appendChild(fragmento)
    }
    
    async galeriaAtt(galeria){
        await this.funcaoParaEsperar.bind(this)(galeria)
        
        setTimeout(() => {
            this.formarGaleria();
        }, 750);
    }
    
    formarGaleria(){
        'use strict';

        var section = $('.shuffle');
        
        section.each(function (index) {
        
        	var $this = $(this);
        	var count = index + 1;
        
        	$this.find('.shuffle-container').addClass('shuffle-container-' + count);
        	$this.find('.shuffle-item').addClass('shuffle-item-' + count);
        	$this.find('.shuffle-sizer').addClass('shuffle-sizer-' + count);
        	$this.find('.shuffle-button').addClass('shuffle-button-' + count);
        
        	var container = $('.shuffle-container-' + count);
        	var button = $('.shuffle-button-' + count);
        
        	var Filter = new Shuffle(container, {
        		itemSelector: '.shuffle-item-' + count,
        		sizer: '.shuffle-sizer-' + count,
        		buffer: 1,
        	})
        
        	button.on('click', function () {
        
        		var button = $(this);
        		var value = button.data('value');
        
        		$this.find('.shuffle-button').removeClass('active');
        		button.addClass('active');
        
        		if (value == 'All') {
        			Filter.filter();
        
        		} else {
        			Filter.filter(value);
        		}
        	})
        })
        
        setTimeout(()=>{
            $('.artigo-galeria').lightGallery({
                selector: '.artigo-galeria .lightbox-link:not(.prevent)',
                thumbnail: true,
                share: false,
                download: false,
            });
        }, 500)
         
    }
    
    tocando(show){
        if(!show){
            this.divVideo.classList.remove("sticky")
        }else{
            this.divVideo.classList.add("sticky")
        }
    }
    
    init(){
        var pagina = fluxoPage("artigos");
     
        console.log(pagina)
        if(pagina.length == 0){
            this.home.bind(this)();
        }else{
            switch(pagina[0]){
                case 'categorias':
                    break;
                case 'tags':
                    break;
                case 'autores':
                    break;
                default:
                    var array = [
                        'https://code.jquery.com/jquery-3.7.1.min.js',
                        `${dominio}/assets/aplicativo/suffle/lightbox.min.js`,
                        `${dominio}/assets/aplicativo/suffle/lightbox.min.css`,
                        `${dominio}/assets/aplicativo/suffle/shuffle.min.js`
                    ];
                    
                    nownFiles.add(array).then(()=>{
                        this.item.bind(this)(pagina[0]);
                    })
                    break;
            }
        }
        
    }
}

function paginaArtigos(){
    new FluxoArtigos();
}

new Temperatura();