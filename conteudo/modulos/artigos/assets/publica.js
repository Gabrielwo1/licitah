class AudioManager {
    constructor() {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        this.oscillator = null;
        this.gainNode = null;
        this.isPlaying = false;
        this.scale = {
            1: 466.16, 
            2: 493.88, 
            3: 523.25, 
            4: 554.37, 
            5: 587.33,
            6: 311.13, 
            7: 329.63, 
            8: 349.23, 
            9: 369.99, 
            b: 493.88
        };
    }

    playSong(song) {
        let position = 0;

        const createOscillator = (freq) => {
            this.gainNode = this.audioContext.createGain();
            this.oscillator = this.audioContext.createOscillator();

            this.oscillator.connect(this.gainNode);
            this.gainNode.connect(this.audioContext.destination);
            this.gainNode.gain.setValueAtTime(0, this.audioContext.currentTime);
            this.gainNode.gain.linearRampToValueAtTime(1, this.audioContext.currentTime + 0.02);
            this.gainNode.gain.linearRampToValueAtTime(0, this.audioContext.currentTime + 0.35);

            this.oscillator.frequency.value = freq;
            this.oscillator.type = "sawtooth";
            this.oscillator.start();

            setTimeout(() => {
                this.oscillator.stop();
                this.oscillator.disconnect();
                this.gainNode.disconnect();
            }, 350)
        };

        const play = () => {
            const note = song.charAt(position);
            const freq = this.scale[note];
            position += 1;
            if (position >= song.length) {
                position = 0;
            }
            if (freq) {
                createOscillator(freq);
            }
        };

        if (!this.isPlaying) {
            this.isPlaying = true;
            this.intervalId = setInterval(play, 1000 / 4);
        }
    }

    stopSong() {
        clearInterval(this.intervalId);
        if (this.oscillator) {
            this.oscillator.stop();
            this.oscillator.disconnect();
        }
        if (this.gainNode) {
            this.gainNode.disconnect();
        }
        this.isPlaying = false;
    }
}

class ArtigosControler{
    constructor(){
        
        this.topoLoad = false;
        
        this.init.bind(this)();
        
    }
    
    init(){
        var url = window.location.href.split(dominio)[1].split("/");
        var filteredUrl = url.filter(function(element) {
            return element !== '';
            
        });
        
        this.caminho = filteredUrl;
        
        if(this.caminho.length == 1){
            this.home.bind(this)()
        }else{
            switch(this.caminho[1]){
                case 'autores':
                    break;
                case 'tags':
                    switch(this.caminho.length){
                        case 2:
                            console.log("home")
                            break;
                        case 3:
                            var api = new ApiNown("artigos", "wZq4rhTFnZapFo3")
                            api.setHash(pegaHash())
                            api.promisse().then((r)=>{
                                console.log(r)
                            }, (r)=>{
                                console.log(r)
                            })
                            break;
                    }
                    break;
                case 'categorias':
                      var categorias = new ApiNown("artigos", "IEh1i0y1OJeCGL3")
         categorias.send().then((r)=>{
             console.log(r)
         }, (r)=>{
             console.log(r)
         })
                    break;
                default:
                    var array = [
                        'https://code.jquery.com/jquery-3.7.1.min.js',
                        `${dominio}/assets/aplicativo/suffle/lightbox.min.js`,
                        `${dominio}/assets/aplicativo/suffle/lightbox.min.css`,
                        `${dominio}/assets/aplicativo/suffle/shuffle.min.js`
                    ];
                    
                    nownFiles.add(array).then(()=>{
                        this.item.bind(this)(this.caminho[1])
                    
                    })
                    break;
            }
        }
    }
    
    carregarLista(r){
         var lista = r.lista
            if(lista.length == 0){
                this.finitoLoad = true;
            }
    
            var i = 0;
            
            var topos = document.getElementsByClassName("artigo-topo")
            while(i < lista.length){
        
                var item = lista[i]
          
    
                var clone = this.modelo.cloneNode(true);
                var mapeio = new FastMap(item , clone);
                mapeio.render(
                    {titulo: ".titulo",
                     descricao: ".resumo",
                     dataCriacao: [".data", belaData]
                    });
                
                mapeio.imagens({imagemDestaque: ".thumb"});
                mapeio.links({url: [".overlay-link", `${dominio}/artigos/`]})
                this.lista.appendChild(clone)
                
                if(topos[i] && !this.topoLoad){
                
                     var topo = new FastMap(item , topos[i]);
                      topo.render(
                    {titulo: ".titulo",
                     descricao: ".resumo",
                     dataCriacao: [".data", belaData]
                    });
                    topo.imagens({imagemDestaque: ".thumb"});
                    topo.links({url: [".overlay-link", `${dominio}/artigos/`]})
                    clone.classList.add("d-xl-none")
                }else{
                    this.topoLoad = true;
                }
                
                
                

                i++;
            }
            
     
         
         document.querySelectorAll('.loading').forEach(elemento => {
             elemento.remove();
             
         });
    }
    
    home(){
        var div = document.createElement("DIV")
        div.innerHTML = document.getElementById("componentemodelo").innerHTML
        this.modelo = div.getElementsByClassName("artigo-card")[0].cloneNode(true);
        document.getElementById("componentemodelo").remove();
        this.lista = document.getElementById("lista")
        evento(document.getElementById("conteudo"), "scroll", this.rolagemPlus.bind(this))
        
        this.carregaMais = false;
        this.api = new ApiNown("artigos", "c5BdK2RDSYwM9jk", "artigo");
        this.api.paginacao(20)
        this.api.promisse().then((r)=>{
            console.log(r)
           this.carregarLista.bind(this)(r)
           futuro("artigos/nown");

        }, (r)=>{
            console.log("erro", r)
        })


        
    }
    
    async share(item) {
        //const audioManager = new AudioManager();
        
        //audioManager.playSong("49949949594994994959388388384838838838482772772737277277273716616616261661661626");
          
            
        if (navigator.share) {
            try {
                await navigator.share({
                    text: item.titulo,
                    url: `${dominio}/artigos/${item.url}`
    
                });
                console.log('Content shared successfully');
            } catch (error) {
                console.error('Error sharing content:', error);
            }
        } else {
           // alert('Web Share API is not supported in your browser.');
        }
    }
    
    shareFace(){
       const title = 'Teste';
       const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&t=${encodeURIComponent(title)}`;
       openPopup(facebookShareUrl, 'Compartilhar no Facebook', 600, 400);
    }

    shareZap(){
        const title = 'Teste';
        const message = `${title}: ${window.location.href}`;
        const whatsappShareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;
        openPopup(whatsappShareUrl, 'Compartilhar no WhatsApp', 600, 400);
    }
    
    rolagemPlus(){
        if(document.getElementById("refFim")){
            var referencial =  parseInt(document.getElementById("refFim").offsetTop) 
            var topo = parseInt(document.getElementById('conteudo').scrollTop) + parseInt(window.innerHeight);
            if(topo > referencial){
                if(!this.carregaMais && !this.finitoLoad){
                    this.carregaMais = true;
                    this.api.proximo();
                     this.api.promisse().then((r)=>{
                         this.carregarLista.bind(this)(r);
                         this.carregaMais = false;
                     })
                    
                }
            }
        }
        

    }
 
    item(hash){
        this.api = new ApiNown("artigos", "wW4IcmPAZWm39ta", "artigo");
        this.api.setHash(hash);
        this.api.promisse().then((r)=>{
            if(r.item){
                var item = r.item
                
                
                
                var render = JSON.parse(item.render);
                    
                    //console.log(dataFormatada(item.dataCriacao))
                    // (dataStr, numerico = false)
      
                
                var display = item?.autor?.display || false;
                if(display){
                    item.display = display
                }
                
                var map = new FastMap(item);
                map.render({
                       titulo: ".titulo",
                       texto:  ".artigo-conteudo",
                       descricao: ".descricaoCurta",
                       dataCriacao: [".data", dataFormatada],
                       display: ".autor"
                    });
                
                map.evento([{
                    componente: ".btn-facebook",
                    evento: "click",
                    callback: this.shareFace.bind(this)
                    },
                    {
                    componente: ".btn-whatsapp",
                    evento: "click",
                    callback: this.shareZap.bind(this)
                    },
                    {
                    componente: ".btn-global",
                    evento: "click",
                    callback: this.share.bind(this)
                    }
                    
                    
                    
                    ])
    
                preSEO(item.seo);
                
                if(item.video){
                    var video = new PlayerVideo();
                    video.setURL(item.video)
                    video.render(document.getElementById("videoArea"))
                    this.videoElement = document.getElementById("videoArea");
                    this.videoElement.classList.add("mb-4")
                    if(document.getElementsByClassName("artigo-midia").length > 0){
                        document.getElementsByClassName("artigo-midia")[0].remove();
                        
                        this.videoArea = document.getElementById("referenciaVideo")
                        evento(document.getElementById("conteudo"), 'scroll', this.handleScroll.bind(this));

                    }
                }else{
                    map.imagens({imagemDestaque : [".artigo-midia", "grande"]})
                }
                
                if(item.galeria && JSON.parse(item.galeria).length > 0){
                    this.galeriaAtt(JSON.parse(item.galeria));
                }
      
                if(item.fonte && item.fonte != ''){
                    map.render({
                       nome_fonte: ".fonte"
                    })
                    
            
                    document.getElementsByClassName("fonte")[0].href= item.fonte
                    document.getElementsByClassName("fonte")[0].setAttribute('target', '_blank')
                }
                else{
                     document.getElementsByClassName("pai-fonte")[0].remove();
                }

            }else{
                this.naoExiste.bind(this)();
            }
        }, (r)=>{
             this.naoExiste.bind(this)();
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
    
    handleScroll() {
        const scrollTop = document.getElementById('conteudo').scrollTop;
        const videoTop = this.videoElement.offsetTop;
        const videoHeight = this.videoElement.offsetHeight;
        const containerHeight = this.videoArea.offsetHeight;
        const containerTop = this.videoArea.offsetTop;
        
        if(scrollTop > containerTop){
            this.videoElement.classList.add('sticky');
        }else{
             this.videoElement.classList.remove('sticky');
        }

      
    }
    
    naoExiste(){
        start.conteudo.naoExiste();
    }
    
    categorias(){
        console.log("categorias estou")
    }
    
    autores(){
        console.log("autores estou")
    }
    
    tags(){
        console.log("tags estou")
    }
    
}

function paginaArtigos(){
   new ArtigosControler();
}