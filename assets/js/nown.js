// Garante que dominio sempre aponte para o servidor atual,
// independente do que o PHP tenha gerado no HTML.
(function(){
    var _origin = window.location.origin;
    if(typeof dominio === 'undefined' || dominio !== _origin){
        try { dominio = _origin; } catch(e){}
    }
    if(typeof dominioscript === 'undefined' || dominioscript !== _origin){
        try { dominioscript = _origin; } catch(e){}
    }
    if(typeof dominioAdress === 'undefined' || dominioAdress !== _origin){
        try { dominioAdress = _origin; } catch(e){}
    }
})();

class Grafico{
    constructor(modulo = false, chave = false, div = false, filtro = false){
        this.modulo = modulo;
        this.chave = chave;
        this.div = div;
        
        this.comeco = moment().format('YYYY-MM-DD');
        this.fim = moment().format('YYYY-MM-DD');
        

        if(this.modulo && this.chave && div){
            this.init.bind(this)();
        }
    }
     
    render(r){

          let x = false;
          let y = false;


     
        var lista = r.lista;
        

    
            const indices = Object.keys(lista).reverse();

            if(r.comparativo){
                var tratados = {}
                var z = 0;
                while(z < r.itens.length){
                    tratados[r.itens[z]] = [];
                    z++;
                }
                

                for(let c in indices){
                    var bloco = lista[indices[c]];
                    for(let i in tratados){
                        if(bloco[i]){
                            tratados[i].push(parseInt(bloco[i]))
                        }else{
                            tratados[i].push(0)
                        }
                    }
         
                }
                

              
            }else{
                const valores = Object.values(lista);
                

                
                var tratados = [valores];
            }
            
            

     
   
            var layout = r.setup?.layout || 'bar'
            var detalhes = r.setup?.[layout] || [];
            
            
            
        if(parseInt(r.setup?.indice || 1) == 1){
        x = {
            type: 'category',
            data: indices
        }
        y = {
            type: 'value'
        }
    }else{
         y = {
            type: 'category',
            data: indices
        }
        x = {
            type: 'value'
        }
    }
    
    
     
    var listaFinal = [];
    
    
    

    for(let c in tratados){
        let itemLista =  {
                data: tratados[c].reverse(),
                type: layout
                
            }
            
            switch(layout){
        case 'line':

            if(detalhes.smooth && detalhes.smooth == "true"){
                itemLista.smooth = true;
            }
            
            if(detalhes.areaStyle && detalhes.areaStyle == "true"){
                itemLista.areaStyle = {};
            }
            
            break;
        case 'bar':
            break;
        case 'pie':
            break;
    }        
    
        listaFinal.push(itemLista)
    }
    
    
    
      switch(layout){
        case 'line':

            
            break;
        case 'bar':
            break;
        case 'pie':
            x = false;
            y = false;
            var novo = [];
            
            let dados = listaFinal[0].data;
            var i = 0;
       
            while(i < dados.length){
                var it = {
                    value: parseInt(dados[i]),
                    name: indices[i]
                }
                novo.push(it)
                i++;
            }
            
        
            listaFinal[0].data = novo
            break;
    }   
        
            
            import(`${dominioscript}/assets/aplicativo/graficos/index.js`).then(() => {

    const echarts = window.echarts;

    var myChart = echarts.init(this.div);
    var option;
    
   
    option = {
        series: listaFinal,
        tooltip: {
            trigger: 'item'
        }
    };
    
    
    var global = r.setup?.global || {}
    if(global.legenda){
        option.legend = {}
        console.log("tem legenda")
    }
    

    
    if(x){
        option.xAxis = x;
    }
    
    if(y){
        option.yAxis = y;
    }

    option && myChart.setOption(option);

                
            }).catch((error) => {
    console.error('Erro ao carregar o m贸dulo ECharts:', error);
});
    }
    
    init() {
    return new Promise((resolve, reject) => {
        let request = new Request(`${dominioscript}/admin/apiNumbers.php`);
        request.addData({acao: "start", modulo: this.modulo, chave: this.chave, comeco: this.comeco, fim: this.fim});
        
        request.send().then((r) => {
            if (this.div) {
                this.render(r);
            }
            resolve(r); 
        }).catch((r) => {
            console.log(r);
            reject(r); 
        });
    });
}

    update(start, end){
        var comeco = start.format('YYYY-MM-DD');
        var fim = end.format('YYYY-MM-DD');
        
        console.log(comeco, fim)

    }

}

class CicloVida {
    constructor() {
        this._eventListeners = new Map();
        this._intervals = new Set();
        this._timeouts = new Set();
        this._observers = new Set();
        this._destroyed = false;
        
        evento(window, "pagina-carregada", this.mata.bind(this));
    }

    addEventListener(element, event, handler, options = {}) {
        if (this._destroyed) return;
        
        const wrappedHandler = (e) => {
            if (!this._destroyed) {
                handler.call(this, e);
            }
        };
        
        element.addEventListener(event, wrappedHandler, options);
        
        if (!this._eventListeners.has(element)) {
            this._eventListeners.set(element, []);
        }
        this._eventListeners.get(element).push({
            event,
            handler: wrappedHandler,
            options
        });
        
        return wrappedHandler;
    }
    
    setInterval(callback, delay) {
        if (this._destroyed) return;
        
        const wrappedCallback = () => {
            if (!this._destroyed) {
                callback.call(this);
            }
        };
        
        const intervalId = setInterval(wrappedCallback, delay);
        this._intervals.add(intervalId);
        return intervalId;
    }
    
    setTimeout(callback, delay) {
        if (this._destroyed) return;
        
        const wrappedCallback = () => {
            if (!this._destroyed) {
                callback.call(this);
 
                this._timeouts.delete(timeoutId);
            }
        };
        
        const timeoutId = setTimeout(wrappedCallback, delay);
        this._timeouts.add(timeoutId);
        return timeoutId;
    }
    
    addMutationObserver(target, options, callback) {
        if (this._destroyed) return;
        
        const observer = new MutationObserver((mutations) => {
            if (!this._destroyed) {
                callback.call(this, mutations);
            }
        });
        
        observer.observe(target, options);
        this._observers.add(observer);
        return observer;
    }
    
    removeEventListener(element, event, handler = null) {
        if (!this._eventListeners.has(element)) return;
        
        const listeners = this._eventListeners.get(element);
        const toRemove = listeners.filter(listener => {
            const shouldRemove = listener.event === event && 
                                (handler === null || listener.handler === handler);
            
            if (shouldRemove) {
                element.removeEventListener(event, listener.handler, listener.options);
            }
            
            return !shouldRemove;
        });
        
        if (toRemove.length === 0) {
            this._eventListeners.delete(element);
        } else {
            this._eventListeners.set(element, toRemove);
        }
    }
    
    clearInterval(intervalId) {
        if (this._intervals.has(intervalId)) {
            clearInterval(intervalId);
            this._intervals.delete(intervalId);
        }
    }
    
    clearTimeout(timeoutId) {
        if (this._timeouts.has(timeoutId)) {
            clearTimeout(timeoutId);
            this._timeouts.delete(timeoutId);
        }
    }
    
    addCleanupTask(task) {
        if (!this._cleanupTasks) {
            this._cleanupTasks = [];
        }
        this._cleanupTasks.push(task);
    }
    
    mata() {
        if (this._destroyed) return;
        
        // Hook: antes de destruir
        if (typeof this.antesDestruir === 'function') {
            try {
                this.antesDestruir();
            } catch (error) {
                console.error('Erro no antesDestruir:', error);
            }
        }
        
        this._destroyed = true;
        
        this._eventListeners.forEach((listeners, element) => {
            listeners.forEach(({ event, handler, options }) => {
                element.removeEventListener(event, handler, options);
            });
        });
        this._eventListeners.clear();
        
        this._intervals.forEach(intervalId => {
            clearInterval(intervalId);
        });
        this._intervals.clear();
        
        this._timeouts.forEach(timeoutId => {
            clearTimeout(timeoutId);
        });
        this._timeouts.clear();
        
        this._observers.forEach(observer => {
            observer.disconnect();
        });
        this._observers.clear();
        
        if (this._cleanupTasks) {
            this._cleanupTasks.forEach(task => {
                try {
                    task.call(this);
                } catch (error) {
                    console.error('Erro na tarefa de cleanup:', error);
                }
            });
            this._cleanupTasks = [];
        }
        
        if (typeof this.destruido === 'function') {
            try {
                this.destruido();
            } catch (error) {
                console.error('Erro no destruido:', error);
            }
        }
        
        this._quebrarReferenciasCirculares();
        
        this._destruirObjetos();
        
        this._anularFuncoes();
        
        this._limpezaTotal();
        
        this._quebrarPrototype();
    
    }
    
    _quebrarReferenciasCirculares() {
        const visited = new WeakSet();
        
        const quebrarReferencias = (obj, depth = 0) => {
            if (depth > 10 || !obj || typeof obj !== 'object' || visited.has(obj)) {
                return;
            }
            
            visited.add(obj);
            
            Object.keys(obj).forEach(key => {
                const value = obj[key];
                if (value && typeof value === 'object') {
                    // Se é um objeto que aponta de volta para this, quebrar a referência
                    if (value === this || (value.constructor && value.constructor === this.constructor)) {
                        obj[key] = null;
                    } else {
                        quebrarReferencias(value, depth + 1);
                    }
                }
            });
        };
        
        quebrarReferencias(this);
    }
    
    _destruirObjetos() {
        Object.keys(this).forEach(key => {
            const value = this[key];
            
            if (value instanceof Map) {
                value.clear();
            } else if (value instanceof Set) {
                value.clear();
            } else if (value instanceof WeakMap) {
                // WeakMap não tem método clear, apenas anular
                this[key] = null;
            } else if (value instanceof WeakSet) {
                // WeakSet não tem método clear, apenas anular
                this[key] = null;
            } else if (Array.isArray(value)) {
                // Limpar array completamente
                value.length = 0;
                value.splice(0);
            } else if (value && typeof value === 'object' && value.constructor === Object) {
                // Objeto literal - limpar todas as propriedades
                Object.keys(value).forEach(objKey => {
                    delete value[objKey];
                });
            } else if (value && typeof value === 'object' && value.destroy) {
                // Se o objeto tem método destroy, chamar
                try {
                    value.destroy();
                } catch (e) {
                    console.warn('Erro ao destruir objeto:', e);
                }
            }
        });
    }
    
    _anularFuncoes() {
        // Anular métodos da instância
        Object.getOwnPropertyNames(this).forEach(key => {
            if (typeof this[key] === 'function' && !key.startsWith('_')) {
                this[key] = null;
            }
        });
        
        // Anular métodos do prototype
        let proto = Object.getPrototypeOf(this);
        while (proto && proto !== Object.prototype) {
            Object.getOwnPropertyNames(proto).forEach(key => {
                if (typeof proto[key] === 'function' && 
                    key !== 'constructor' && 
                    !key.startsWith('_')) {
                    try {
                        this[key] = null;
                    } catch (e) {
                        // Algumas propriedades podem ser read-only
                    }
                }
            });
            proto = Object.getPrototypeOf(proto);
        }
    }
    
    _limpezaTotal() {
        // Primeiro, tentar deletar propriedades configuráveis
        Object.getOwnPropertyNames(this).forEach(key => {
            try {
                const descriptor = Object.getOwnPropertyDescriptor(this, key);
                if (descriptor && descriptor.configurable) {
                    delete this[key];
                } else {
                    // Se não conseguir deletar, anular
                    this[key] = null;
                }
            } catch (e) {
                // Propriedades que não conseguimos mexer
                try {
                    this[key] = null;
                } catch (e2) {
                    // Ignorar erros de propriedades read-only
                }
            }
        });
        
        // Anular symbols se houver
        Object.getOwnPropertySymbols(this).forEach(symbol => {
            try {
                this[symbol] = null;
            } catch (e) {
                // Ignorar erros
            }
        });
    }
    
    _quebrarPrototype() {
        try {
            // Definir prototype como null (torna o objeto "órfão")
            Object.setPrototypeOf(this, null);
        } catch (e) {
            // Em alguns casos isso pode falhar, mas tudo bem
            console.warn('Não foi possível quebrar prototype:', e);
        }
    }
    
    isDestroyed() {
        return this._destroyed;
    }
    
    selfDestruct(motivo = 'Autodestruição solicitada') {
        console.log(`${this.constructor.name} se autodestruindo: ${motivo}`);
        this.mata();
    }
}

function formatarValorZeros(valor, trato = true) {
  // Se não for para tratar, retorna o valor numérico original
  if (!trato) return valor;

  // Função auxiliar para inserir pontos a cada 3 dígitos na parte inteira
  function formatarInteiro(inteiro) {
    return inteiro.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  if (valor >= 1) {
    // Para valores >= 1, formata com 2 casas decimais
    let formatted = Number(valor).toFixed(2);
    let [parteInteira, parteDecimal] = formatted.split('.');
    parteInteira = formatarInteiro(parteInteira);
    return `${parteInteira},${parteDecimal}`;
  } else {
    // Para valores < 1, usamos precisão alta para capturar dígitos significativos
    let strValor = valor.toFixed(18);
    // Remove zeros à direita que não são significativos
    strValor = strValor.replace(/(\.\d*?[1-9])0+$/, "$1").replace(/\.0+$/, "");
    
    const partes = strValor.split('.');
    if (partes.length < 2) return strValor;
    
    const parteInteira = partes[0];
    const parteDecimal = partes[1];
    
    // Localiza o primeiro dígito não zero na parte decimal
    const indicePrimeiroNaoZero = parteDecimal.search(/[^0]/);
    if (indicePrimeiroNaoZero === -1) return strValor;
    
    // Preserva os zeros iniciais e pega os 2 dígitos significativos seguintes
    const zerosIniciais = parteDecimal.substring(0, indicePrimeiroNaoZero);
    const doisDigitosSignificativos = parteDecimal.substring(indicePrimeiroNaoZero, indicePrimeiroNaoZero + 2);
    
    const inteiroFormatado = formatarInteiro(parteInteira);
    
    return `${inteiroFormatado},${zerosIniciais}${doisDigitosSignificativos}`;
  }
}

function widget(modulo) {
    return new Promise((resolve, reject) => {
        
            if(!modulo || !modulo.trim()){
        reject();
    }
    modulo = modulo.trim();


        nownFiles.add(`https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js`)
            .then(() => {
                try {
                    const hashMD5 = CryptoJS.MD5(modulo).toString();
                    
                    if (dataModule(hashMD5)) {
                        resolve(); 
                    } else {
                        reject();
                    }
                } catch (error) {
                    reject(); 
                }
            })
            .catch(err => {
                reject(new Error(`Falha ao carregar o script: ${err.message}`));
            });
    });
}

class Slides{
    constructor(token = false, container){
        this.token = token;
        this.container = container;
        if(this.token && container){
            this.init.bind(this)();
        }
    }
    
    init(){
        let request = new Request(`${dominioscript}/conteudo/modulos/slides/admins/api.php`);
        request.addData({
              "acao":"slide",
              "hash": this.token 
        })
        request.send().then((r)=>{
            var item = r.item;
        
            switch(item.folhas.length){
                case 0:
                    break;
                case 1:
                    break;
                default:
 
                    this.folhaSlide(item)
                    break;
            }
            
            
        }, (r)=>{
            console.log(r)
        })
 
    }
    
    folhaSlide(item){
        
 
        let config = item.config;
        
        var folhas = item.folhas
        var id = geraId();
        
        var div = document.createElement("DIV")
        div.id = id
        div.classList.add("carousel","slide")
    
    
        console.log(config)

        let indicadores = false;
        if(config.marcadoresprogresso == "1"){
            indicadores = document.createElement("DIV")
            indicadores.classList.add("carousel-indicators")
            div.appendChild(indicadores)
        }

       
       
  
 
  
  var inner = document.createElement("DIV")
  inner.classList.add("carousel-inner")
  
  var i = 0;
  while(i < folhas.length){
      var folha = document.createElement("DIV")
     
      if(folhas[i].link){
           if (typeof folhas[i].link === 'string') {
               let url;
               var string = folhas[i].link;
               if (string.startsWith("https://") || string.startsWith("http://")) {
        url = string;
    } else if (string.startsWith("/")) {
        url =  dominio + string;
    } else {
        url = dominio + "/" + string;
    }
    
    
             var prevent = true;
              if (url.split(".").length == 2 && url.split(".")[1] && [".php", ".html", ".css"].some(ext => url.endsWith(ext))) {
                  prevent = false;
              } 
        
               var a = document.createElement("A")
          
               a.href = url;
} else {
    a = false;
}
    }else{
        var a = false;
    }
        
      folha.classList.add("carousel-item")
      if(i == 0){
          folha.classList.add("active")
      }
    
        var f = folhas[i]
        
        if(f.layout == 1){
            var mobile = trataImagem(f.sizes?.mobile?.img || false, "otimizada");
            var tablet = trataImagem(f.sizes?.tablet?.img || false, "otimizada");
            var pc = trataImagem(f.sizes?.pc?.img || false, "otimizada");
            var wide = trataImagem(f.sizes?.wide?.img || false, "otimizada");
            
            if(mobile){
                var m = document.createElement("DIV")
                m.classList.add("d-block", "d-md-none", "background")
                m.style.backgroundImage = `url(${mobile})`;
                var altura = window.innerHeight - 150;
                m.style.height = `${altura}px`;
       
                
                 if(a){
                    var clone = a.cloneNode()
                    clone.appendChild(m)
                    if(prevent){
                         evento(clone, "click", preventLink)
                    }
                    folha.appendChild(clone)
                }else{
                     folha.appendChild(m)
                }
            }
            
            if(tablet){
                var t = document.createElement("DIV")
                t.classList.add("d-none", "d-md-block", "d-lg-none","background")
                t.style.backgroundImage = `url(${tablet})`;
                t.style.height = `560px`;
         
                
                if(a){
                    var clone = a.cloneNode()
                    if(prevent){
                         evento(clone, "click", preventLink)
                    }
                    clone.appendChild(t)
                    folha.appendChild(clone)
                }else{
                     folha.appendChild(t)
                }

            }
            
            if(pc){
                var p = document.createElement("DIV")
                p.classList.add("d-none", "d-lg-block", "d-xl-none", "background")
                p.style.height = `560px`;
                p.style.backgroundImage = `url(${pc})`;
                
                
                  if(a){
                    var clone = a.cloneNode()
                    clone.appendChild(p)
                    if(prevent){
                         evento(clone, "click", preventLink)
                    }
                    folha.appendChild(clone)
                }else{
                     folha.appendChild(p)
                }
            }
            
            if(wide){
                var w = document.createElement("DIV")
                w.classList.add("d-none", "d-xl-block", "m-auto", "background")
                w.style.height = `560px`;
                w.style.backgroundImage = `url(${wide})`;
                if(a){
                    var clone = a.cloneNode()
                    clone.appendChild(w)
                    if(prevent){
                         evento(clone, "click", preventLink)
                    }
                     
                    folha.appendChild(clone)
                }else{
                     folha.appendChild(w)
                }
               
            }
            
            
            if(indicadores){
                
                
     
                var indicador = document.createElement("BUTTON")
                indicador.type = "button"
                indicador.setAttribute("data-bs-target", `#${id}`)
                indicador.setAttribute("data-bs-slide-to", `${i}`)
                if(i == 0){
                    indicador.classList.add("active")
                    indicador.setAttribute("aria-current", `true`)
                    indicador.setAttribute("aria-label", folha.nome)
                }
   
                switch(parseInt(config.layoutmarcador ?? 1)){
                    case 1:
                        break;
                    case 2:
                        indicador.style = `width: 20px; height: 20px; border-radius: 50%`
                        break;
                    case 3:
                        indicador.style = `width: 20px; height: 20px;`
                        break;
                    case 4:
                        indicador.style.width = `50px`
                        indicador.style.height = `50px`
                        indicador.classList.add("background")
                        indicador.classList.add("border", "border-light", "border-2")
                        indicador.style.backgroundImage = `url(${trataImagem(f.sizes?.pc?.img || false , "mini")})`;
                        
                        
                        switch(parseInt(config?.layoutprevia || false)){
                            case 1:
                                break;
                            case 2:
                                indicador.style.borderRadius= `20px`;
                                break;
                            case 3:
                                 indicador.style.borderRadius= `50%`;
                                break;
                                
                        }
         
                        
                        
                        
                        break;
                }
                
                
                indicadores.appendChild(indicador)
                
              
                
            }
            
            
            
        }else{
            
        }
        
      
        inner.appendChild(folha)
        
         
        
      i++;
  }
    div.appendChild(inner)
  if(config.btnsprogresso){
      var vai = document.createElement("BUTTON")
      vai.classList.add("carousel-control-prev")
      vai.type = "button"
      vai.setAttribute("data-bs-target", `#${id}`)
      vai.setAttribute("data-bs-slide", "prev")
      vai.innerHTML = `<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>`
      
       var volta = document.createElement("BUTTON")
      volta.classList.add("carousel-control-next")
      volta.type = "button"
      volta.setAttribute("data-bs-target", `#${id}`)
      volta.setAttribute("data-bs-slide", "next")
      volta.innerHTML = `<span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>`
    
    
   div.appendChild(vai)
   div.appendChild(volta)
   
   
   
   
 
  }
  
    if(config.avancado && config.avancado == "1"){
      
         let obj = {
             interval: parseInt(config?.intervalo || 2) * 1000,
             touch: config.touch == "1" ? true : false,
             keyboard: config.teclado == "1" ? true : false,
             pause: false
            
         };
          
         if(config.rolagemautomatica == "1"){
             obj.ride = true;
             
             if(config.estilorolagem == "2"){
                 obj.ride = "carousel"
             }
             
             if(config.pausador == "1"){
                 var button = document.createElement("BUTTON")
                 button.dataset.id = id
                 button.classList.add("position-absolute", "bg-transparent", "border-2", "border-light", "rounded-pill", "px-3", "d-flex", "justify-content-center", "align-items-center", "gap-1")
                 evento(button, "click", this.playpause.bind(this))
                 button.style = "top: 20px; left: 20px; z-index: 9999"
                 
                 button.innerHTML = `
                  <div>
        <i class="bi bi-pause-fill"></i>
    </div>
    <span>Pausar</span>
                 `
                 
                 
                 
                 
                div.appendChild(button)
             }
             
             
         } 
         
         
         
          
         this.slide = new bootstrap.Carousel(div , obj)

   }
  
  
  var pre = document.createElement("DIV")
  pre.style.maxWidth = `1920px`;
  pre.appendChild(div)
  pre.classList.add("position-relative", "m-auto")
  this.container.appendChild(pre)
  

  
  
       
    }
    
    playpause() {
    var btn = event.currentTarget;
    var id = btn.dataset.id;
    var carousel = $(`#${id}`);
    var timer = this.timer || 2000; // Tempo do slide (milissegundos)
    var interval; 
    var progressCircle;

    if (btn.classList.contains("pausado")) {
        // Remover o estado pausado e iniciar o ciclo
        btn.classList.remove("pausado");
        btn.innerHTML = `
            <div>
        <i class="bi bi-pause-fill"></i>
    </div>
    <span>Pausar</span>
        `;


      

        // Iniciar o carrossel
        carousel.carousel('cycle');

    } else {
        // Adicionar o estado pausado e parar o ciclo
        btn.classList.add("pausado");
        btn.innerHTML = `
            <div>
                <i class="bi bi-play-fill"></i>
            </div>
            <span>Reproduzir</span>
        `;

        // Ocultar a barra de progresso
        btn.classList.remove("playing");

       
        carousel.carousel('pause');
    }
}

}

function dataPicker(input, cb, extra = {}) {
    return new Promise((resolve, reject) => {
        const itens = [
            "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js",
            "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"
        ];

        nownFiles.add(itens).then(() => {
            try {
                const defaultConfig = {
                    opens: 'left',
                    ranges: {
                        "Hoje": [moment(), moment()],
                        "Ontem": [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                        "Últimas 24 Horas": [moment().subtract(24, 'hours'), moment()],
                        "Últimos 7 dias": [moment().subtract(7, 'days'), moment()],
                        "Última Semana": [moment().startOf('week').startOf('day'), moment()],
                        "Últimos 30 dias": [moment().subtract(29, "days"), moment()],
                        "Esse mês": [moment().startOf('month').startOf('day'), moment().endOf('month')],
                        "Último Mês": [moment().subtract(30, 'days'), moment()]
                    },
                    locale: {
                        customRangeLabel: "Personalizado",
                        applyLabel: "Aplicar",
                        cancelLabel: "Cancelar",
                        daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                        monthNames: [
                            "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                            "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
                        ],
                        firstDay: 0
                    }
                };

                // Mescla as configurações padrão com as opções extras
                const config = Object.assign({}, defaultConfig, extra);

                // Inicializa o daterangepicker com as configurações mescladas
                var picker = $(input).daterangepicker(config, function(start, end) {
                    if (typeof cb === 'function') {
                        cb(start, end);
                    }
                });

                // Resolve a Promise com o input criado
                resolve(input);
            } catch (error) {
                reject(error); // Rejeita a Promise em caso de erro
            }
        }).catch(error => {
            reject(error); // Rejeita a Promise caso o carregamento dos arquivos falhe
        });
    });
}

function createRanges(minDate, maxDate) {
    // Converte as datas para moment
    const min = moment(minDate);
    const max = moment(maxDate);

    // Inicializa os ranges com "Todo Período"
    const ranges = {
        "Todo Período": [min, max]
    };

    // Intervalos possíveis
    const possibleRanges = {
        "Hoje": [moment().startOf('day'), moment().endOf('day')],
        "Ontem": [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
        "Últimas 24 Horas": [moment().subtract(24, 'hours'), moment()],
        "Últimos 7 dias": [moment().subtract(7, 'days'), moment()],
        "Última Semana": [moment().startOf('week').startOf('day'), moment()],
        "Últimos 30 dias": [moment().subtract(29, 'days'), moment()],
        "Esse mês": [moment().startOf('month').startOf('day'), moment().endOf('month')],
        "Último Mês": [moment().subtract(30, 'days'), moment()]
    };

    // Adiciona apenas intervalos válidos
    Object.keys(possibleRanges).forEach(key => {
        let [start, end] = possibleRanges[key];

        // Verifica se o intervalo está completamente dentro de [min, max]
        if (start.isSameOrAfter(min) && end.isSameOrBefore(max)) {
            ranges[key] = [start, end];
        }
    });

    return ranges;
}

function pegaCompras(modulo = false, url = false) {
    return new Promise((resolve, reject) => {
        
        if(!modulo || !url){
             reject("Não foram enviados o módulo e a url pública");
        }
        
        let request = new Request(`${dominioscript}/conteudo/modulos/pagamento/admins/api-pedido.php`);
        
        request.addData({
            acao: "apiJsNown",
            modulo: modulo,
            url: url
        });

        request.send().then((r) => {
            resolve(r);  // Sucesso: resolve a Promise
        }).catch((r) => {
            reject(r);   // Erro: rejeita a Promise
        });
    });
}

class PreTable{
    constructor(modulo, tabela, container){
        this.modulo = modulo
        this.tabela = tabela
        this.container = container

        this.init.bind(this)();
    }
    
    init(){
         let obj = {
             modulo: this.modulo,
             id: this.tabela,
             master: false,
             identificador: this.tabela,
             tipo: "preTabela"
            }
        
        let request = new Request("admin/brain.php");
        request.addData(obj)
        request.send().then((r)=>{
            console.log(r.tabela.conteudo)
            
             var layout = r.tabela.conteudo?.layout?.modo || 1;
             if(layout == 1){
                 nownFiles.add(`${dominio}/assets/js/tabelo.js`).then(()=>{
                     this.tabelo = new Tabelo(obj, this.container, false);
                 })
                 
             }else{
                 obj.subDetalhes = r.tabela.conteudo.layout

                 this.tabelo = new Grade(obj, false, this.container);
             }
            
            
            
        }, (r)=>{
            console.log(r)
        })
        
        
        return;
        new Tabelo(r, false, this.container);
    }
}

function fecharTodosModais() {
    const modais = document.querySelectorAll('.modal.show');
    modais.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
}

async function fastCadastro(email) {
    return new Promise(async (resolve, reject) => {
        const valido = new ValidaInfo(email, "email");

        if (autenticado()) {
            Swal.fire({
                icon: "error",
                title: "Atenção",
                showConfirmButton: false,
                text: "Usuário já logado!",
                timer: 1500
            });
            return reject(new Error("Usuário já logado!"));
        }

        if (!valido.valida()) {
            Swal.fire({
                icon: "error",
                title: "Atenção",
                showConfirmButton: false,
                text: "Digite um e-mail válido!",
                timer: 1500
            });
            return reject(new Error("Digite um e-mail válido!"));
        }

        try {
            let request = new Request(`${dominio}/admin/login.php`);
            request.addData({ acao: "fastCadastro", email });

            let r = await request.send();

            let int = parseInt(dataSys(["paginas", "cadastro", "tiporedirecionamento"], 0));
            let url = "";

            switch (int) {
                case 1: {
                    let person = dataSys(["paginas", "cadastro", "urlpersonalizada"], false);
                    if (person) url = person;
                    break;
                }

                case 2: {
                    let produto = dataSys(["paginas", "cadastro", "idprodutocarrinho"], 0);
                    if (produto > 0) {
                        let requestProduto = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
                        requestProduto.addData({ acao: "addCard", produto, quantidade: 1 });

                        try {
                            await requestProduto.send();
                            url = "carrinho";
                        } catch (error) {
                            console.error("Erro ao adicionar produto ao carrinho:", error);
                        }
                    }
                    break;
                }

                case 3: {
                    let produto = dataSys(["paginas", "cadastro", "idprodutocheckout"], 0);
                    if (produto > 0) {
                        let requestCheckout = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
                        requestCheckout.addData({ acao: "fechaUnico", produto, quantidade: 1 });

                        try {
                            let response = await requestCheckout.send();
                            url = `pagamento/${response.url}`;
                        } catch (error) {
                            console.error("Erro ao fechar compra:", error);
                        }
                    }
                    break;
                }
            }

            url = url.startsWith('/') ? url.slice(1) : url;
            
             nownFiles.add(`${dominio}/assets/js/login.js`).then(()=>{
               criaSessao(r);
               start.ajax().then(()=>{
                   fecharTodosModais() 
                   goUrl(url);  
               })
               
                 
        })
            

            resolve(r);
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Atenção",
                showConfirmButton: false,
                text: error.mensagem || "Ocorreu um erro inesperado",
                timer: 1500
            });
            if (error.go) goUrl(error.go);
            reject(error);
        }
    });
}

class ModalCrop{
    constructor(img, tamanho){
        this.img = img;
        this.tamanho = tamanho
        if(document.getElementById("modalCrop")){
            document.getElementById("modalCrop").remove();
        }
        this.render.bind(this)();
    }
    
    callBack(r){
        this.retorno = r
    }
    
    crop(){
        var trato = this.tamanho.split(",")
        var size = `${parseInt(trato[0])} / ${parseInt(trato[1])}`
        
        
        
        
 
        nownFiles.add(["https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css", "https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"]).then(()=>{
            this.cropper = new Cropper(this.img, {
                aspectRatio: eval(size),
            });
         
        })
    }
    
    corta(){
        const croppedCanvas = this.cropper.getCroppedCanvas({
            maxWidth: 600, 
        });
            if (croppedCanvas) {
                 croppedCanvas.toBlob((blob) => {
                const file = new File([blob], 'cropped_image.webp', { type: 'image/webp' });
                this.modal.hide();
                this.retorno(file);
            }, 'image/webp');
            
            
            }
    }
    
    render(){
        var modal = document.createElement("DIV");
        modal.classList.add("modal","fade");
        modal.setAttribute("id", "modalCrop");
        modal.setAttribute("tabindex", "-1");
  
        
        var dialog = document.createElement("DIV")
        dialog.classList.add("modal-dialog", "modal-dialog-centered")
        
        var content = document.createElement("DIV")
        content.classList.add("modal-content")
        
        var header = document.createElement("DIV")
        header.classList.add("modal-header")
        
        var body = document.createElement("DIV")
        body.classList.add("modal-body", "p-0")
        
        this.div = document.createElement("DIV")
        var trato = this.tamanho.split(",")
        this.div.classList.add("ratio",`ratio-${trato[0]}x${trato[1]}`, "bg-carregando")
        
        
        var footer = document.createElement("DIV")
        footer.classList.add("modal-footer", "d-flex", "justify-content-between", "align-items-center")
        
        
             
        var btn = document.createElement("BUTTON")
        btn.innerText = "Cancelar"
        btn.setAttribute("data-bs-dismiss" , "modal");

        btn.classList.add("btn", "btn-danger", "rounded-0")
        footer.appendChild(btn)
        
        
        var btn = document.createElement("BUTTON")
        evento(btn, "click", this.corta.bind(this))
        btn.innerText = "Salvar"
        btn.classList.add("btn", "btn-n-primaria", "rounded-0")
        footer.appendChild(btn)
        
        var img = document.createElement("IMG")
        img.classList.add("w-100", "d-none")
        img.setAttribute("loading","lazy")
        img.src = this.img
        this.img = img
        
        var pai = document.createElement("DIV")
     
        pai.appendChild(img)
        this.div.appendChild(pai)
        body.appendChild(this.div)
        
        
        content.appendChild(header)
        content.appendChild(body)
        content.appendChild(footer)
        dialog.appendChild(content)
        modal.appendChild(dialog)
        document.getElementsByTagName("body")[0].appendChild(modal)
        this.modal = new bootstrap.Modal('#modalCrop', {
            backdrop: 'static', keyboard: false
        })
        


        
        this.modal.show();
        
        setTimeout(this.crop.bind(this), 500)
        
   
        
        
    }
}
 
function belaData(input) {


    const now = new Date();
    if (input === 0) {
        return "Agora Mesmo";
    }
    
    const inputDate = new Date(input);

    const diffInMilliseconds = now - inputDate;

    const seconds = Math.floor(diffInMilliseconds / 1000);
    const minutes = Math.floor(diffInMilliseconds / 60000);
    const hours = Math.floor(diffInMilliseconds / 3600000);
    const days = Math.floor(diffInMilliseconds / 86400000);
    const weeks = Math.floor(diffInMilliseconds / (7 * 86400000));
    const months = Math.floor(diffInMilliseconds / (30 * 86400000));
    const years = Math.floor(diffInMilliseconds / (365 * 86400000));

    if (seconds < 60) {
    return seconds === 1 ? `há 1 seg.` : `há ${seconds} seg.`;
} else if (minutes < 60) {
    return minutes === 1 ? `há 1 min.` : `há ${minutes} min.`;
} else if (hours < 24) {
    return hours === 1 ? `há 1 hora` : `há ${hours} horas`;
} else if (days < 7) {
    return days === 1 ? `há 1 dia` : `há ${days} dias`;
} else if (weeks < 4) {
    return weeks === 1 ? `há 1 sem.` : `há ${weeks} sem.`;
} else if (months < 12) {
    return months === 1 ? `há 1 mês` : `há ${months} meses`;
} else {
    return years === 1 ? `há 1 ano` : `há ${years} anos`;
}
}

function getHash(){
    var url = window.location.href.split("/")
    url = url[url.length - 1]
    return url;
}

async function geraPDF(content) {
            try {
                await nownFiles.add([
                    'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'
                ]);

                const { jsPDF } = window.jspdf;


                const canvas = await html2canvas(content, {
                    scale: 2,
                    useCORS: true
                });

                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'pt', 'a4');
                const imgWidth = 595.28; // Largura em pontos da página A4
                const pageHeight = 841.89; // Altura em pontos da página A4
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('document.pdf');
            } catch (error) {
                console.error('Failed to generate PDF:', error);
            }
        }
        
function  openPopup(url, title, width, height) {
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            const options = `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${width}, height=${height}, top=${top}, left=${left}`;

            window.open(url, title, options);
}

function getAutor(id){
    var banco = new BancoDeDados("usuarios", usuarios);
    
    
    
}

function animaEntrada(){
    
    /*
https://kimmobrunfeldt.github.io/progressbar.js/
https://ricostacruz.com/nprogress/
https://app.lottiefiles.com/project/4edb7d31-9ee4-4e67-a7eb-3ade74959a5e
https://github.hubspot.com/drop/docs/welcome/?__hstc=143835306.af08dfcb3c48d1ce7797d09293aa5539.1730480013193.1730480013193.1730480013193.1&__hssc=143835306.1.1730480013194&__hsfp=1985122391

*/    
    var array = [
        `${dominioscript}/assets/aplicativo/aos/aos.css`,
        `${dominioscript}//assets/aplicativo/aos/aos.js`
        ];
    nownFiles.add(array).then(()=>{
         var itens = document.getElementsByClassName("animaShow")
         
         var animacoes = ["fade", "flip", "zoom", "zoom-in", "zoom-out"];
         var direcoes = ["topo", "baixo", "esquerda", "direita"]
         
         var i = 0;
         if(itens.length > 0){
              while(i < itens.length){
             var item = itens[i]
             item.classList.remove("animaShow")
             var animacao = "fade"
             if(item.dataset.animacao && animacoes.includes(item.dataset.animacao)){
                 animacao = item.dataset.animacao;
             }
             
             if(item.dataset.direcao && direcoes.includes(item.dataset.direcao)){
                 switch(item.dataset.direcao){
                     case 'topo':
                          animacao = `${animacao}-up`
                         break;
                     case 'baixo':
                         animacao = `${animacao}-down`
                         break;
                     case 'esquerda':
                         animacao = `${animacao}-left`
                         break;
                     case 'direita':
                         animacao = `${animacao}-right`
                         break;
                 }
                 
                 
                 
             }
             
             item.dataset.aos = animacao
             
             i++;
         }
         
            AOS.init();
             
            
         }
        
         
         
         
         
         
         
    })
}

class FastMap{
    constructor(item, html = false){

        this.item = item;
        this.html = html;
        
        if(this.item.render){
            this.renderizados = JSON.parse(this.item.render)
        }else{
            this.renderizados = [];
        }
        

        
        /*
        Somente textos ser達o renderizados no render
        Para adicionar imagens, chama a funcaos imagem
        para adiiconar os links, chamar a funcao links
        */

    }
    
    imagens(mapa){
    
        var conteudo = !this.html ? document.getElementById("conteudo") : this.html;
        var otimiza = false;
        for(let c in mapa){
            if(Array.isArray(mapa[c])){
                var query = mapa[c][0]
                var otimiza = mapa[c][1]
            }else{
                var query = mapa[c]
            }
            
            
            
            
            var componente = conteudo.querySelector(query);
            if(componente){
                if(this.item[c]){
                    componente.innerHTML = "";
                    var imagem = document.createElement("IMG")

                     
                     let img = trataImagem(this.item[c], otimiza);
                  
                    
                    if(img){
          
                      imagem.src = img
                      imagem.classList.add("w-100")
                      componente.appendChild(imagem)  
                    }else{
                       componente.remove(); 
                    }
                    
                }else{
                    componente.remove();
                }
              
                
            }

        }
    }
    
    imagensbg(mapa){

        var conteudo = !this.html ? document.getElementById("conteudo") : this.html;
     
        for(let c in mapa){
            var query = mapa[c]
            var componente = conteudo.querySelector(query);
            var otimiza = false;
               if(Array.isArray(mapa[c])){
                var query = mapa[c][0]
                var otimiza = mapa[c][1]
            }else{
                var query = mapa[c]
            }
            
            
            
            
            if(componente){
                if(this.item[c]){
                    componente.innerHTML = "";
                    var imagem = document.createElement("DIV")
                    imagem.classList.add("w-100", "h-100")
                  
                     
                     let img = trataImagem(this.item[c], otimiza);
                    
                    
                    if(img){
          
                      imagem.style.backgroundImage = `url(${img})`
                      imagem.style.backgroundSize = `cover`
                      imagem.style.position = `center center`
                      componente.appendChild(imagem)  
                    }else{
                       componente.remove(); 
                    }
                    
                }else{
                    componente.remove();
                }
              
                
            }

        }
    }
    
    render(mapa){
        var conteudo = !this.html ? document.getElementById("conteudo") : this.html;
        for(let c in mapa){
            var query = Array.isArray(mapa[c]) ? (mapa[c][0] ? mapa[c][0] : null) : mapa[c];
            var componente = conteudo.querySelector(query);
            if(componente){
                if(this.item[c]){
                    componente.innerHTML = ""
                    if(Array.isArray(mapa[c])){
                         componente.innerHTML = mapa[c][1](this.item[c])
                    }else{
                        componente.innerHTML = this.item[c]
                    }
                    
                   
                }else{
                    componente.remove();
                }
              
                
            }

        }
    }
    
    links(mapa){

         var conteudo = !this.html ? document.getElementById("conteudo") : this.html;
        for(let c in mapa){
            var query = mapa[c][0]
            var componente = conteudo.querySelector(query);
    
            if(componente){
                if(this.item[c]){
                    componente.href = `${mapa[c][1]}${this.item[c]}`
                    evento(componente, "click", preventLink)

                }else{
                    componente.remove();
                }
              
                
            }
        }
        
        
    }
    
    evento(mapa){
         var conteudo = !this.html ? document.getElementById("conteudo") : this.html;
        for(let c in mapa){
            var query =  mapa[c].componente
            var componente = conteudo.querySelector(query);
            if(componente){
                evento(componente, mapa[c].evento, ()=>{
                    mapa[c].callback(this.item)
                })
              
                
            }

        } 
    }
}

class CustomMap {
  constructor(container, latitude = -23.5505, longitude = -46.6333, zoom = 16) {
    this.container = typeof container === 'string' ? document.getElementById(container) : container;
    this.latitude = latitude;
    this.longitude = longitude;
    this.zoom = zoom;
    this.map = null;
    this.marker = null;
    this.initialized = false;
    this.mutationObserver = null;

    // Carrega os arquivos CSS e JS via Nown
    this.ready = nownFiles.add([
      'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
      'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
    ]).then(() => {
      // Verifica se o container está visível antes de inicializar
      if (this._isVisible(this.container)) {
        this._initMap();
      } else {
        // Se não estiver visível, configuramos um observer para detectar mudanças de visibilidade
        this._setupVisibilityObserver();
      }
    });
  }

  // Verifica se o elemento está visível
  _isVisible(element) {
    if (!element) return false;
    
    // Verifica se o elemento ou algum dos seus pais tem display: none
    const style = window.getComputedStyle(element);
    if (style.display === 'none') return false;
    
    // Verifica tamanho do container
    if (element.offsetWidth === 0 || element.offsetHeight === 0) return false;
    
    return true;
  }

  // Configura um observer para detectar quando o container fica visível
  _setupVisibilityObserver() {
    // Observa mudanças no estilo do container
    this.mutationObserver = new MutationObserver((mutations) => {
      if (this._isVisible(this.container) && !this.initialized) {
        this._initMap();
        // Uma vez inicializado, podemos parar de observar
        if (this.mutationObserver) {
          this.mutationObserver.disconnect();
        }
      }
    });
    
    // Observa mudanças em atributos de estilo
    this.mutationObserver.observe(this.container, {
      attributes: true,
      attributeFilter: ['style', 'class']
    });
    
    // Como alternativa, também verificamos periodicamente
    // útil para quando a visibilidade muda por JavaScript ou CSS externo
    this._checkVisibilityInterval = setInterval(() => {
      if (this._isVisible(this.container) && !this.initialized) {
        this._initMap();
        clearInterval(this._checkVisibilityInterval);
        if (this.mutationObserver) {
          this.mutationObserver.disconnect();
        }
      }
    }, 500); // Verifica a cada 500ms
  }

  // Inicializa o mapa após o carregamento dos arquivos
  _initMap() {
    if (this.initialized) return;
    
    try {
      if (this.map) {
        this.map.remove(); // Limpa mapa existente se houver
      }
      
      this.map = L.map(this.container).setView([this.latitude, this.longitude], this.zoom);
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(this.map);
      
      this.marker = L.marker([this.latitude, this.longitude]).addTo(this.map);
      this.initialized = true;
      
      // Força o recálculo do tamanho quando o mapa é inicializado
      setTimeout(() => {
        if (this.map) {
          this.map.invalidateSize();
        }
      }, 100);
    } catch (error) {
      console.error('Erro ao inicializar mapa:', error);
    }
  }

  // Método para forçar a reinicialização do mapa
  invalidateSize() {
    if (this.map && this.initialized) {
      this.map.invalidateSize();
    }
  }

  // Atualiza o marcador e centraliza o mapa
  async update(lat, lng) {
    await this.ready;
    
    // Se o mapa ainda não foi inicializado mas o container está visível, inicialize-o
    if (!this.initialized && this._isVisible(this.container)) {
      this._initMap();
    }
    
    this.latitude = lat;
    this.longitude = lng;
    
    if (this.map && this.marker) {
      this.marker.setLatLng([lat, lng]);
      this.map.setView([lat, lng], this.map.getZoom());
      this.map.invalidateSize(); // Garante que o mapa se redimensiona corretamente
    }
  }

  // Retorna as coordenadas atuais
  async getCoordinates() {
    await this.ready;
    return { latitude: this.latitude, longitude: this.longitude };
  }
  
  // Método para limpar recursos quando o mapa não é mais necessário
  destroy() {
    if (this.mutationObserver) {
      this.mutationObserver.disconnect();
      this.mutationObserver = null;
    }
    
    if (this._checkVisibilityInterval) {
      clearInterval(this._checkVisibilityInterval);
    }
    
    if (this.map) {
      this.map.remove();
      this.map = null;
    }
    
    this.initialized = false;
  }
}

function generateFingerprint() {
    // Obter informações sobre o dispositivo (navegador, sistema operacional, etc.)
    const userAgent = navigator.userAgent;
    const platform = navigator.platform;
    const language = navigator.language;
    const screenWidth = window.screen.width;
    const screenHeight = window.screen.height;
    const timezoneOffset = new Date().getTimezoneOffset();

    // Gerar uma string de fingerprint a partir dessas informações
    const fingerprintString = userAgent + platform + language + screenWidth + screenHeight + timezoneOffset;

    // Usar uma função hash simples para gerar uma impressão digital única (pode usar uma biblioteca mais robusta, se necessário)
    const fingerprint = btoa(fingerprintString); // Codifica em Base64

    return fingerprint;
}

function getFingerprint() {
    // Verifica se a impressão digital já foi armazenada
    let fingerprint = localStorage.getItem('device_fingerprint');

    if (!fingerprint) {
        // Se não existir, gera uma nova e armazena no localStorage
        fingerprint = generateFingerprint();
        localStorage.setItem('device_fingerprint', fingerprint);
    }

    return fingerprint;
}

class ApiNown{
    constructor(modulo, chave, memoria = false, procurar = false){
        this.request = new Request(`${dominio}/admin/api.php`);
        this.obj = {};

        this.ajax = this.ajax.bind(this)
        this.modulo = modulo;
        this.chave = chave;
        this.pagina = false;
        this.page = 0;
        this.quantidade = false;
        this.size = false;
        this.extra = false;
        this.itens = false;
        
        
        if(procurar){
            new UltraSearch(procurar, {modulo: modulo, identificador: chave, foco: "apis"});
          
        }
        
        
        
         this.memoria = memoria
        if(this.memoria){
            this.banco = new BancoDeDados(this.modulo, this.memoria);
        }
    }
    
    isFilter(filtro){
        this.filter = filtro;
        
    }
    
    isNew(){
        this.request = new Request(`${dominio}/admin/api2.php`);
    }
    
    setItens(array){
        this.itens = JSON.stringify(array);
    }
    
    setExtra(item){
        this.extra = item
    }
    
    parseHash(hash){
        this.parser = hash
    }
    
    addData(obj){
        this.obj["info"] = JSON.stringify(obj)
    }
    
    paginacao(quantidade){
        
        if(!this.pagina){
           this.pagina = 1; 
        }
        this.page = true;
        this.size = quantidade;
        this.quantidade = quantidade
    }
    
    proximo(){
        this.pagina = this.pagina + 1;
    }
    
    setPagina(pagina){
        this.pagina = pagina
    }
    
    setHash(hash){
        this.hash = hash
    }
    
    start(cb){
        
        this.obj.paginacao = true;
        this.obj.quantidade = this.quantidade
        this.obj.pagina = this.pagina
        
        this.ajax(cb)
    }
    
    async promisse() {
    return new Promise(async (resolve, reject) => {
        if(this.hash){
             this.obj.hash = this.hash;
        }
        if(!dataSys(["performance","velocidade","indexdb"], false)){
             if (this.hash) {
           
            
            if (this.memoria) {
                try {
                    const resultado = await this.banco.umItem(this.obj.hash);
                    if(resultado){
                         resolve({ item: resultado});
                    }

                } catch (err) {
                    return reject(err);
                }
            }
        }else{
            if(this.memoria){
                try{
                    const resultado = await this.banco.lista(this.quantidade, this.pagina);
         
                    if(resultado && resultado.length > 0){
           
                         resolve({ lista: resultado});
                    }
                    
                    
                }catch(err){
                    
                }
            }
        }
        }
        
        this.obj.modulo = this.modulo;
        this.obj.chave = this.chave;
        this.obj.quantidade = this.quantidade;
        this.obj.size = this.size;
        this.obj.pagina = this.pagina;
        this.obj.paginacao = this.page;
        this.obj.fingerprint = getFingerprint();
        this.request.addData(this.obj);
        this.request.send().then((r) => {
            if (this.memoria) {
                if (r.item) {
                    this.banco.novo([r.item]);
                }
                
              
                if(r.lista){
                    this.banco.novo(r.lista);
                }
                
                
            }
            
            
            
            
            resolve(this.concatena.bind(this)(r));
        }).catch((err) => {
            console.log("deu erro")
            reject(err);
        });
    });
}

    ajax(cb = false){
        
        
        if(this.hash){
            this.obj.hash = this.hash
        }
        
        this.obj.modulo = this.modulo
        this.obj.chave = this.chave
        this.obj.fingerprint = getFingerprint();
        this.request.addData(this.obj)
        this.request.send().then((r)=>{
            if(cb){
              
                
                
                cb(this.concatena.bind(this)(r))
                
            }
        },()=>{
            console.log(erro, r)
        })

    }
    
    concatena(r){
        if(r.lista){
            if(r.estrangeiras){
            
            
            
            var estrangeiras = r.estrangeiras;
            delete r.estrangeiras;
            
            var novaLista = [];
            var lista = r.lista
            
            var i = 0;
            while(i < lista.length){
                var item = lista[i]
                
                for(let c in estrangeiras){
                    if(item[c]){
                        item[c] = estrangeiras[c][item[c]]
                    }else{
                         item[c] = false;
                    }
                    
                }
                
                novaLista.push(item);

                
                i++;
            }
            
            
            r.lista = novaLista;

            
        }
        
            if(r.numbers){
           
                var i = 0;
                for(let c in r.lista){
                    
                    var id = r.lista[c].id
                   
                    r.lista[c]["numbers"] = {
                        favoritos: r.numbers?.favoritos?.[id] || false
                    }
                    

                    
                }
            }
        }
        
        if(r.item){
            var estrangeiras = r.estrangeiras;
            if(r.estrangeiras){
                for(let c in estrangeiras){
                    
     
                    if(r.item[c]){
                        r.item[c] = estrangeiras[c][r.item[c]]
                    }else{
                         r.item[c] = false;
                    }
                    
                }
                
                
            }

        }
        


        

        return r;
    }
    
    
    setAdress(estado = false, cidade = false){
        
        if(!estado || !cidade){
            console.error('Estado ou cidade não foi passado')
            return
        }
        
        this.obj.adress = {
            'estado': estado,
            'cidade': cidade
        }
    }
    
    send() {
        return new Promise((resolve, reject) => {

        
        this.obj.modulo = this.modulo
        this.obj.chave = this.chave
        if(this.extra){
            this.obj.extra = this.extra;
        }
        
        if(this.itens){
            this.obj.itens = this.itens;
        }
        
        if(this.hash){
            this.obj.hash = this.hash
        }
        
        if(this.parser){
            this.obj.parser = this.parser;
        }
        
        if(this.pagina){
            this.obj.pagina = this.pagina;
            this.obj.paginacao = true;
            this.obj.page = this.pagina;
        }
        
        if(this.quantidade){
            this.obj.quantidade = this.quantidade
        }
        
        if(this.filter){
            this.obj.filtro = JSON.stringify(this.filter)
        }
        
        if(!this.obj.adress){
            var endereco = pegaEndereco();
            if(endereco){
               this.obj.adress = endereco;
            }
        }
       
        this.obj.adress = JSON.stringify(this.obj.adress);
        
        if(dominio != dominioscript){
            const match = dominio.match(/https?:\/\/([^.]+)\./);
            if (match) {
                this.obj.wildcard = match[1];
            } 


            
        }

        
        this.request.addData(this.obj)
        this.request.send().then((r)=>{
            resolve(this.concatena.bind(this)(r))
        },(r)=>{
             reject(r)
        })
    });
}

}

function pegaEndereco(){
    var url = window.location.href.split(dominio)[1].split("/")
    const trato = url.filter(item => item !== "");
    var estado = false;
    var cidade = false;
    
    var i = 0;
    while(i < trato.length){
        var item = trato[i];
        
        if(item.length <= 3 && item.length > 1){
            var estado = item;
            
            if(trato[i + 1]){
                var cidade = trato[i + 1];
            }
            break;
        }
        
        i++;
    }
    
    if(!estado && !cidade){
        return false;
    }
    
    return {
        estado: estado,
        cidade: cidade
    };
}

class PlayerVideo{
    constructor(tipo = "youtube", id = false){
        this.tipo = tipo;
        this.id = id;
        this.atrs = {};
    
    }
    
    setURL(url){
        this.tipo = this.detectVideoType(url);
        this.id = this.extractVideoID(url);
    }
    
    atributos(atrs){

        this.atrs = atrs
    }
    
    detectVideoType(url) {
    const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?(?:.*&)?v=|v\/|embed\/|user\/.*\/|.*#\/watch\?(?:.*&)?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const vimeoRegex = /(?:https?:\/\/)?(?:www\.)?(vimeo\.com\/)([0-9]+)/;
    const mp4Regex = /\.mp4$/;

    if (youtubeRegex.test(url)) {
        return "youtube";
    } else if (vimeoRegex.test(url)) {
        return "vimeo";
    } else if (mp4Regex.test(url)) {
        return "mp4";
    } else {
        return ;
    }
    }
    
    extractVideoID(url) {
    const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?(?:.*&)?v=|v\/|embed\/|user\/.*\/|.*#\/watch\?(?:.*&)?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const vimeoRegex = /(?:https?:\/\/)?(?:www\.)?(vimeo\.com\/)([0-9]+)/;

    let match = url.match(youtubeRegex);
    if (match) {
        return match[1]; // ID do YouTube
    }

    match = url.match(vimeoRegex);
    if (match) {
        return match[2]; // ID do Vimeo
    }

    return null; // Não é possível extrair o ID
}
    
    render(query){

      nownFiles.add([`https://cdn.plyr.io/3.7.8/plyr.css` , `https://cdn.plyr.io/3.7.8/plyr.js`]).then(()=>{
          var div = document.createElement("DIV")
          div.id = "player"
          div.setAttribute("data-plyr-provider", this.tipo)
          div.setAttribute("data-plyr-embed-id", this.id)

          for(let c in this.atrs){
              console.log(c, this.atrs[c])
               div.setAttribute(c, this.atrs[c]);
          }
       
          

          query.appendChild(div)
          
          if(document.getElementById("videoLoading")){
              document.getElementById("videoLoading").remove();
          }
          
          this.player = new Plyr('#player');
          
              this.player.on('play', () => {
                query.classList.add('videoTocando');
            });

            // Remove classe quando o vídeo pausa ou para
            this.player.on('pause', () => {
                query.classList.remove('videoTocando');
            });
            
            this.player.on('ended', () => {
                query.classList.remove('videoTocando');
            });


          

      })
    }
}

class PlayVideo{
    constructor(container = false , video = false){
        console.log("um")
        if(!container || !video){
            return;
        }
        
        this.container = container;
        this.video = video;
        this.init();
    }
    
    init(){
    
        var tipo = this.pegaTipo(this.video);
        var id = this.pegaId(this.video);
        var identificador = geraId();
          nownFiles.add([`https://cdn.plyr.io/3.7.8/plyr.css` , `https://cdn.plyr.io/3.7.8/plyr.js`]).then(()=>{
          var div = document.createElement("DIV")
          div.id =  identificador
          div.setAttribute("data-plyr-provider", tipo)
          div.setAttribute("data-plyr-embed-id", id)

         
       
          

          this.container.appendChild(div)

          
          if(document.getElementById("videoLoading")){
              document.getElementById("videoLoading").remove();
          }
          
          this.player = new Plyr(`#${identificador}`);
          
              this.player.on('play', () => {
                query.classList.add('videoTocando');
            });

            // Remove classe quando o vídeo pausa ou para
            this.player.on('pause', () => {
                query.classList.remove('videoTocando');
            });
            
            this.player.on('ended', () => {
                query.classList.remove('videoTocando');
            });


          

      })
        
    }
    
    
    pegaId(url){

    const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?(?:.*&)?v=|v\/|embed\/|user\/.*\/|.*#\/watch\?(?:.*&)?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const vimeoRegex = /(?:https?:\/\/)?(?:www\.)?(vimeo\.com\/)([0-9]+)/;

    let match = url.match(youtubeRegex);
    if (match) {
        return match[1]; // ID do YouTube
    }

    match = url.match(vimeoRegex);
    if (match) {
        return match[2]; // ID do Vimeo
    }

    return null; // Não é possível extrair o ID

    }
    
     pegaTipo(url) {
    const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?(?:.*&)?v=|v\/|embed\/|user\/.*\/|.*#\/watch\?(?:.*&)?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const vimeoRegex = /(?:https?:\/\/)?(?:www\.)?(vimeo\.com\/)([0-9]+)/;
    const mp4Regex = /\.mp4$/;

    if (youtubeRegex.test(url)) {
        return "youtube";
    } else if (vimeoRegex.test(url)) {
        return "vimeo";
    } else if (mp4Regex.test(url)) {
        return "mp4";
    } else {
        return ;
    }
    }
}

class CarregarComponente{
    constructor(caminho, arquivo , cb = false, pagina = false){
        var data = new FormData();
        data.append("caminho", caminho)
        data.append("arquivo", arquivo)
        if(pagina){
            data.append("pagina", pagina)
        }
        this.ajax.bind(this)(data, cb);
    }
    
    ajax(data, cb){
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(cb){
                    cb(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominioscript}/admin/rotacomponente.php`, true)
        request.send(data)
 
    }
}

class Mascaras{
    constructor(input = false){
        
        if(!input){
            var input = document.getElementsByClassName("mascaraInput")
        }
        
           
          
          
         
        let array = [
            `${dominioscript}/assets/aplicativo/sweetalert2/min.js`,
            `${dominioscript}/assets/bibliotecas/mascaras/mask.js`
            ];
    
        
        nownFiles.add(array).then(()=>{
            for(let c in input){
                var entrada = input[c]
                
                if(entrada instanceof HTMLElement){
            
                   var mascara = entrada.dataset.mascara
                   this.maska.bind(this)(entrada, mascara)
                }
                
                
            }
        })
          

        
    }

    maska(input, mascara){
       
        
        const masks = {
            1: ['000.000.000-00'],
            2: ['00.000.000/0000-00'],
            3: ['00000-000'],
            4: ['?(00) 0000-0000'],
            5: ['(00) 0000-0000'],
            6: ['(00) 00000-0000'],
            7: ['00/00/0000'],
            8: ['00:00'],
            9: ['00/00/0000 00:00:00'],
            10: ['0.00'],
            11: ['##0.00'],
            12: ['##0.00', true],
            13: ['##0,00%'],
            14: ['000 0000 0000 0000'],
            15: "url",
            16: "email",
            17: "cpf/cnpj",
            18: ['000 000'],
            19:  ["#.##0,00", true]
 
            
        };
        
  
        if(masks[mascara]){
            
            switch(parseInt(mascara)){
                case 4:
                    var SPMaskBehavior = function (val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    spOptions = {
                        onKeyPress: function(val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };
                    
                    $(input).mask(SPMaskBehavior, spOptions);
                    break;
                case 12:
                     var valor = input.value;
                    
                    const num = Number(valor);
                    if(Number.isInteger(num)){
                        valor = num
                    }else{
                        var trato = valor[valor.length - 3]
                        var meio = valor.split(trato)
                        
                        
                         var preco = meio[0].replaceAll('.', '')
                         var preco = preco.replace(',', '.')
                         
                         var final = `${preco}.${meio[1]}`
                        
                         input.value = final;
                    }

                    
                    
                    
                    input.type = "number"
                    input.setAttribute("min","0") 
                    input.setAttribute("step","0.01")
                    
                    break;
                case 15:
                    $(input).mask('https://Z', {
    translation: {
        'Z': { pattern: /./, recursive: true } // Allow any character after "https://"
    },
    placeholder: "https://" 
});
                    $(input).on('blur', function() {
    var valor = $(this).val();

    // More Robust URL Validation
    if (valor) { 
        // Use a more comprehensive regular expression for validation
        var urlPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/i;

        if (!urlPattern.test(valor)) {
           

            $(this).focus(); // or $(this).val(''); 
        } else if (!valor.startsWith("https://")) { 
            $(this).val('https://' + valor);
        }
    }
});

                    break;
                    case 16:
                       
    $(input).on('blur', function() {
        var valor = $(this).val();

        // Expressão regular para validação de email
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
       
        if (valor && !emailPattern.test(valor)) {
             invalido(input, false , "E-mail inválido");
            iziToast.error({
                icon: 'bi bi-envelope',
                title: 'Atenção',
                message: 'O email digitado é inváldio'
            });
           
        }
    });

    break;
                case 17:

                    var cpfCnpjMaskBehavior = function (val) {
                        return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
                    },
                    cpfCnpjpOptions = {
                        onKeyPress: function(val, e, field, options) {
                            field.mask(cpfCnpjMaskBehavior.apply({}, arguments), options);
                        }
                    };
          
                   $(input).mask(cpfCnpjMaskBehavior, cpfCnpjpOptions);

                    break;
                default:
                  const mask = masks[mascara][0];
                  var reverso = masks[mascara][1] ? masks[mascara][0] : false;
                  $(input).mask(mask, {reverse: reverso}); 
                    break;
            }
            
      
            
        }
    }
    
}

function animarCss(element, animation, prefix = 'animate__') {
  return new Promise((resolve, reject) => {
    const animationName = `${prefix}${animation}`;
    const node = document.querySelector(element);

    if (!node) {
      reject(`Element "${element}" não encontrado`);
      return;
    }

    const handleAnimationEnd = (event) => {
      event.stopPropagation();
      node.classList.remove(`${prefix}animated`, animationName);
      
      resolve('Animação concluída');
    };

    node.classList.add(`${prefix}animated`, animationName);
    node.addEventListener('animationend', handleAnimationEnd, { once: true });
  });
}

function remover(element) {
  if (element) {
      element.style.setProperty('--animate-duration', '0.5s');
    // Adiciona as classes do animate.css para a animação
    element.classList.add("animate__animated", "animate__lightSpeedOutLeft");

    // Ouve o evento de término da animação
    element.addEventListener('animationend', function() {
      // Remove o elemento após o término da animação
      element.remove();
    });

    // Aguarda um tempo suficiente para a animação terminar
    setTimeout(() => {
      // Remove a classe de animação após um tempo maior que a duração da animação
      element.classList.remove("animate__animated", "animate__lightSpeedOutLeft");
    }, 1000); // Tempo correspondente à duração da animação em milissegundos (1s no exemplo)
  }
}

class Uploader{
    constructor(item, details = {}){

        this.item = item;
        this.details = details;
        this.itens = [];
        
        if(details.multiple){
            this.item.setAttribute("multiple", "");
        }
        
        this.sucesso = false;
        this.startado = false;
        
  
    }
    
    async scriptsCode() {
    return new Promise((resolve, reject) => {
        this.array = [
            `${dominio}/assets/aplicativo/filepond/min.js?v=1`,
            `${dominio}/assets/aplicativo/filepond/min.css?v=1`,
            `${dominio}/assets/aplicativo/filepond/filepond-plugin-file-validate-type.js`,
            `${dominio}/assets/aplicativo/filepond/filepond-plugin-image-crop.min.js`,
            `${dominio}/assets/aplicativo/filepond/filepond-plugin-media-preview.min.js`
        ];

        nownFiles.add(this.array, false)
            .then(() => {
                this.startado = true;
                resolve();
            })
            .catch(reject);
    });
}   

    setSucesso(e){
        this.sucesso = e;
    }
    
    render() {

          FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageResize,
            FilePondPluginImageTransform,
            FilePondPluginFilePoster,
            FilePondPluginPdfPreview,
            FilePondPluginFileValidateType,
            FilePondPluginImageCrop,
             FilePondPluginMediaPreview
            );
            
            
        var txt = `Arraste ou solte seu arquivo aqui`
        if(this.details['texto-upload'] && this.details['texto-upload'].trim()){
            txt = this.details['texto-upload'];
            txt = `${txt} <span class="filepond--label-action" tabindex="0">Navegue</span>`
        }
        
        if(this.details['fullTxt']){
            txt = this.details['fullTxt'];
        }
        
    
        var modulo = this.details.modulo ?? "";

        let options = {
             labelIdle: txt,
                       allowImageResize: true,
                       imageResizeTargetWidth: 1200,
                       labelInvalidField: "Campo contém arquivos inválidos",
                       labelFileWaitingForSize: "Aguardando tamanho",
                       labelFileSizeNotAvailable: "Tamanho não disponível",
                       labelFileLoading: "Carregando",
                       labelFileLoadError: "Erro ao carregar",
                       labelFileProcessing: "Processando",
                       labelFileProcessingComplete: "Upload concluído",
                       labelFileProcessingAborted: "Upload cancelado",
                       labelFileProcessingError: "Erro durante o upload",
                       labelFileProcessingRevertError: "Erro ao reverter",
                       labelFileRemoveError: "Erro ao remover",
                       labelTapToCancel: "toque para cancelar",
                       labelTapToRetry: "toque para tentar novamente",
                       labelTapToUndo: "toque para desfazer",
                       labelButtonRemoveItem: "Remover",
                       labelButtonAbortItemLoad: "Abortar",
                       labelButtonRetryItemLoad: "Tentar novamente",
                       labelButtonAbortItemProcessing: "Cancelar",
                       labelButtonUndoItemProcessing: "Desfazer",
                       labelButtonRetryItemProcessing: "Tentar novamente",
                       labelFileTypeNotAllowed: 'Formato de arquivo inválido',
                       fileValidateTypeLabelExpectedTypes: 'Aceito arquivos do tipo: {lastType}',
                       labelButtonProcessItem: "Upload",
                     
                       credits: {},
                       allowPdfPreview: true,
    pdfPreviewHeight: 320,
    pdfComponentExtraParams: 'toolbar=0&view=fit&page=1',
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort) => {
  
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);

                    fetch(`${dominio}/admin/processador.php?modulo=${modulo}`, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            load(data.nomeArquivo);
                            if(this.sucesso){
                                this.sucesso(data);
                            }
                            
                        } else {
                            error(data.mensagem);
                        }
                    })
                    .catch(err => {
                        error('Falha ao enviar arquivo');
                    });

                    return {
                        abort: () => {
                            // Lógica para abortar o upload, se necessário
                            abort();
                        }
                    };
                },
                revert: (uniqueFileId, load, error) => {
                    // Implementação para remover o arquivo do servidor
                    fetch(`${dominio}/admin/remover.php`, {
                        method: 'POST',
                        body: JSON.stringify({ nomeArquivo: uniqueFileId }),
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            load();
                            // A remoção do nome do arquivo do array é tratada no evento onremovefile
                        } else {
                            error('Falha ao remover arquivo');
                        }
                    })
                    .catch(err => {
                        error('Erro de rede ao remover arquivo');
                    });
                },
            },
        };
        FilePond.setOptions(options);
        
        let obj = {};
        
        if(this.details.arredondado){
            obj.imagePreviewHeight = 170,
            obj.imageCropAspectRatio = '1:1',
            obj.imageResizeTargetWidth = 200,
            obj.imageResizeTargetHeight = 200,
            obj.stylePanelLayout = 'compact circle',
            obj.styleLoadIndicatorPosition = 'center bottom',
            obj.styleProgressIndicatorPosition = 'center bottom',
            obj.styleButtonRemoveItemPosition = 'center bottom',
            obj.styleButtonProcessItemPosition = 'center bottom'
        }else{
            
        }
        
        var cropper = this.details?.cropper || false;
        let setHeight = false;
        if (cropper && cropper.ativo && cropper.largura && cropper.altura) {
            setHeight = cropper.altura;
            var id = geraId();
            obj.imagePreviewHeight = parseInt(cropper.altura);
            obj.imageCropAspectRatio = `${parseInt(cropper.largura)}:${parseInt(cropper.altura)}`;
            obj.imageResizeTargetWidth = parseInt(cropper.largura);
            obj.imageResizeTargetHeight = parseInt(cropper.altura);
            obj.stylePanelLayout = 'compact integrated';
            obj.styleLoadIndicatorPosition = 'center bottom';
            obj.styleProgressIndicatorPosition = 'right bottom';
            obj.styleButtonRemoveItemPosition = 'right bottom';
            obj.styleButtonProcessItemPosition = 'right bottom';

        }


        
        

        if (this.item) {
            this.pond = FilePond.create(this.item, obj);
            this.pond.element.closest(".card").classList.remove("d-none")
            
            
            if(this.espera){
                this.pond.files = this.espera
            }

            this.pond.on('processfile', (error, file) => {
                if (!error) {
                    if(this.sucesso){
                      //  this.sucesso(file.serverId)
                    }
                    this.itens.push(file.serverId); 
                }
                
                
                if(document.getElementById("cardSalvarBtnSalvar")){
                    document.getElementById("cardSalvarBtnSalvar").dataset.uploads = parseInt(document.getElementById("cardSalvarBtnSalvar").dataset.uploads) - 1
                    let upando = parseInt(document.getElementById("cardSalvarBtnSalvar").dataset.uploads);
                    if(upando == 0 && document.getElementById("cardSalvarBtnSalvar").dataset.autosave){
                        document.getElementById("cardSalvarBtnSalvar").removeAttribute("data-autosave")
                        document.getElementById("cardSalvarBtnSalvar").click();
                    }

                }
                
                
            });
            
            this.pond.on('addfilestart', (file) => {
                if(document.getElementById("cardSalvarBtnSalvar")){
                    document.getElementById("cardSalvarBtnSalvar").dataset.uploads = parseInt(document.getElementById("cardSalvarBtnSalvar").dataset.uploads) + 1
                }
            });
            
            
            this.pond.on('init', () => {
                if(setHeight){
                   this.pond.element.style.height = `${setHeight}px`
                }
            });


        }
    }
    
    getFileMimeType(fileName) {
    // Extrai a extensão do arquivo do nome
    const extension = fileName.split('.').pop().toLowerCase();

    // Mapeamento expandido de extensões para tipos MIME
    const mimeTypes = {
        // Imagens
        'jpg': 'image/jpeg',
        'jpeg': 'image/jpeg',
        'png': 'image/png',
        'gif': 'image/gif',
        'bmp': 'image/bmp',
        'svg': 'image/svg+xml',
        'webp': 'image/webp',
        'tif': 'image/tiff',
        'tiff': 'image/tiff',
        'ico': 'image/x-icon',
        // Documentos
        'pdf': 'application/pdf',
        'doc': 'application/msword',
        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'ppt': 'application/vnd.ms-powerpoint',
        'pptx': 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'xls': 'application/vnd.ms-excel',
        'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'odt': 'application/vnd.oasis.opendocument.text',
        'ods': 'application/vnd.oasis.opendocument.spreadsheet',
        'odp': 'application/vnd.oasis.opendocument.presentation',
        'txt': 'text/plain',
        'rtf': 'application/rtf',
        // Vídeos
        'mp4': 'video/mp4',
        'avi': 'video/x-msvideo',
        'mpeg': 'video/mpeg',
        'mpg': 'video/mpeg',
        'mov': 'video/quicktime',
        'wmv': 'video/x-ms-wmv',
        'flv': 'video/x-flv',
        'mkv': 'video/x-matroska',
        'webm': 'video/webm',
        // Áudio
        'mp3': 'audio/mpeg',
        'wav': 'audio/wav',
        'ogg': 'audio/ogg',
        'm4a': 'audio/mp4',
        'flac': 'audio/flac',
        // Adicione mais tipos conforme necessário
    };
    


    // Retorna o tipo MIME com base na extensão, ou um valor padrão se desconhecido
    return mimeTypes[extension] || 'application/octet-stream';
}

    async  getFileSizeFromUrl(url) {
        
    try {
        const response = await fetch(url, { method: 'HEAD' });
        const contentLength = response.headers.get('Content-Length');
        return contentLength ? parseInt(contentLength, 10) : null; // Retorna o tamanho em bytes
    } catch (error) {
        console.error('Erro ao obter o tamanho do arquivo:', error);
        return null;
    }
}

    async set(imagePaths) {
        if (!Array.isArray(imagePaths)) {
            try{
                imagePaths = JSON.parse(imagePaths);
            }catch(e){
                console.error('O parâmetro fornecido não é um array.');
                return;  
            }
          
        }

        let arquivos = [];
        for (let path of imagePaths) {
            const url = `${dominio}/conteudo/uploads/${path}`;
        
            const name = path.split('/').pop();
            const size = await this.getFileSizeFromUrl(url);
            const type = this.getFileMimeType(name); 

            var arquivo = {
                source: path,
                options: {
                    type: 'local',
                    file: {
                        name: name,
                        size: size,
                        type: type,
                    },
                    metadata: {
                        poster: url,
                    }
                }
            };
            arquivos.push(arquivo);
        }

        if(arquivos.length > 0){
             if(this.pond){
               this.pond.files = arquivos;  
             }else{
                 this.espera = arquivos
             }
             
        }
       
    }
    
    get() {
        var arquivos = [];
         this.pond.getFiles().map(file => {
             if(file.serverId){
                arquivos.push(file.serverId) 
             }
              
          });
        return arquivos
          
        
    }
    
    limpa(){
        this.pond.getFiles().forEach(file => {
      this.pond.removeFile(file.id);
    });
    }
}

function paraPreco(preco, simbolo = "R$") {
    


    if (typeof preco === 'number') {
        
        preco = preco.toString();
    } else if (typeof preco === 'string') {

        preco = preco.replace(/[^0-9.,]/g, '');
        

        let numeroNormalizado = preco.replace(/,/g, '').replace(/\./g, '');
        

        if (isNaN(numeroNormalizado)) {
            throw new Error('O valor fornecido não é um número válido.');
        }
    } else {
        throw new Error('O valor fornecido não é um número nem uma string.');
    }

    // Substitui vírgulas por nada e pontos por vírgulas para corresponder ao formato numérico do JavaScript
    let numeroNormalizado = preco.replace(/,/g, '').replace(/\./, ',');

    // Converte a string para um número flutuante
    let floatValue = parseFloat(numeroNormalizado.replace(',', '.'));

    // Usa toLocaleString para formatar o número no formato brasileiro
    let formattedFloat = floatValue.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    return `${simbolo} ${formattedFloat}`;
}

class ImagemNown{
    constructor(input, container, config){
        this.config = config;
        this.card = input;
     
        //this.card.getElementsByClassName("card-footer")[0].remove()
    }
    
    render(){
        var input = document.createElement("INPUT")
 
        input.type = "file"
       

        if(this.config.accept){
             input.setAttribute("accept", this.config.accept)
        }
        
    
        this.card.getElementsByClassName("card-body")[0].appendChild(input)
        
        this.uploader = new Uploader(input, this.config);
        this.uploader.scriptsCode().then(()=>{
            this.uploader.render()
        })
        

        return input;
    }

    biblioteca(){
        
    }
    
    set(r){
      try{
          var obj = JSON.parse(r)

          this.uploader.set(obj)
      }catch(e){
          
      }
    }
    
    get(){
        return this.uploader.get();
    }
}

class BancoDeDados {
    constructor(tabela, agrupamento) {
        this.tabela = tabela;
        this.agrupamento = agrupamento;
        this.db = new Dexie(this.tabela);
        let storesConfig = {};
        if(tabela == "paginas"){
             storesConfig[this.agrupamento] = '++url';
        }else{
             storesConfig[this.agrupamento] = '++url, autor, categoria, dataCriacao';
        }
      
        this.db.version(1).stores(storesConfig);
    }
    
    async lista(quantidade, paginacao) {
    try {
        if (quantidade && paginacao) {
             paginacao =  paginacao - 1;
            const items = await this.db[this.agrupamento]
                .orderBy('dataCriacao')
                .reverse()
                .offset(paginacao * quantidade)
                .limit(quantidade)
                .toArray();
            return items;
        } else {
            console.log("entou no else")
            // Caso contrário, retorne todos os itens ordenados por data decrescente
            const items = await this.db[this.agrupamento]
                .orderBy('dataCriacao')
                .reverse()
                .toArray();
            return items;
        }
    } catch (error) {
        console.error('Erro ao listar os itens:', error);
    }
}


    async umItem(url) {
        try {

            const item = await this.db[this.agrupamento].get(url);

            if (item) {
                return item;
            } else {
                return false;
            }
        } catch (error) {
            console.error('Erro ao procurar o item:', error);
        }
    }

    novo(array) {
        this.db[this.agrupamento].bulkPut(array) 
    }
    
    async pesquisarPorAutor(autor) {
        try {
            const items = await this.db[this.agrupamento]
                .where('autor')
                .equals(autor)
                .toArray();
            return items;
        } catch (error) {
            console.error('Erro ao procurar por autor:', error);
        }
    }

    async pesquisarPorCategoria(categoria) {
        try {
            const items = await this.db[this.agrupamento]
                .where('categoria')
                .equals(categoria)
                .toArray();
            return items;
        } catch (error) {
            console.error('Erro ao procurar por categoria:', error);
        }
    }

}

class Maping{
    constructor(html, map, caminhoUrl = false){
        this.html = html;
        this.caminhoUrl = caminhoUrl ? `/${caminhoUrl}` : "";
        this.map = map;
  
        this.render.bind(this)()
    }
    
    render(){
        this.html.dataset.renderizado = true;
        if(this.map.url){
            var url = `${dominio}${this.caminhoUrl}/${this.map.url}`
            if(this.html.getElementsByClassName("url").length == 1){
                this.html.getElementsByClassName("url")[0].href = url
            }
            
            
        }
        
        
        
        for(let c in this.map){
            var item = this.map[c]
            
            if(this.html.getElementsByClassName(c).length == 1){
                var div = this.html.getElementsByClassName(c)[0]
                div.innerHTML = ""
                div.innerHTML = item
            }

        }
    }
}

function paraUrl(str) {
    const specialChars = {
        'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A',
        'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a',
        'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E',
        'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e',
        'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
        'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
        'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O',
        'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o',
        'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U',
        'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u',
        'Ñ': 'N', 'ñ': 'n',
        'Ç': 'C', 'ç': 'c',
        'ÿ': 'y', 'Ÿ': 'Y', 'ý': 'y', 'Ý': 'Y',
        'Æ': 'AE', 'æ': 'ae', 'Ø': 'O', 'ø': 'o', 'Þ': 'TH', 'þ': 'th', 'ß': 'ss'
    };

    const convertedStr = str.replace(/[^\w\s]/g, char => specialChars[char] || char)
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-{2,}/g, '-')
        .toLowerCase();

    return encodeURI(convertedStr);
}

class ValidaInfo {
    constructor(string, validacao) {
        this.string = string;
        this.validacao = validacao;
    }

    cpf() {
         const cpf = this.string.replace(/\D/g, ''); // Remove non-digit characters

        if (
            cpf.length !== 11 || // CPF must have 11 digits
            cpf === '00000000000' || // CPF cannot be a sequence of the same digit
            cpf === '11111111111' ||
            cpf === '22222222222' ||
            cpf === '33333333333' ||
            cpf === '44444444444' ||
            cpf === '55555555555' ||
            cpf === '66666666666' ||
            cpf === '77777777777' ||
            cpf === '88888888888' ||
            cpf === '99999999999'
        ) {
            return false;
        }

        let sum = 0;
        let remainder;

        for (let i = 1; i <= 9; i++) {
            sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        }

        remainder = (sum * 10) % 11;

        if (remainder === 10 || remainder === 11) {
            remainder = 0;
        }

        if (remainder !== parseInt(cpf.substring(9, 10))) {
            return false;
        }

        sum = 0;

        for (let i = 1; i <= 10; i++) {
            sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        }

        remainder = (sum * 10) % 11;

        if (remainder === 10 || remainder === 11) {
            remainder = 0;
        }

        if (remainder !== parseInt(cpf.substring(10, 11))) {
            return false;
        }
        
        return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    }

    cnpj() {
          const cnpj = this.string.replace(/\D/g, ''); // Remove non-digit characters

        if (
            cnpj.length !== 14 || // CNPJ must have 14 digits
            /^(\d)\1+$/.test(cnpj) // CNPJ cannot be a sequence of the same digit
        ) {
            return false;
        }

        let size = cnpj.length - 2;
        let numbers = cnpj.substring(0, size);
        const digits = cnpj.substring(size);
        let sum = 0;
        let pos = size - 7;

        for (let i = size; i >= 1; i--) {
            sum += parseInt(numbers.charAt(size - i)) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }

        let result = sum % 11 < 2 ? 0 : 11 - (sum % 11);

        if (result !== parseInt(digits.charAt(0))) {
            return false;
        }

        size += 1;
        numbers = cnpj.substring(0, size);
        sum = 0;
        pos = size - 7;

        for (let i = size; i >= 1; i--) {
            sum += parseInt(numbers.charAt(size - i)) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }

        result = sum % 11 < 2 ? 0 : 11 - (sum % 11);

        if (result !== parseInt(digits.charAt(1))) {
            return false;
        }

        return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');

        

    }

    email() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format regex
        if(emailRegex.test(this.string)){
            return this.string
        }
        return false;
    }

    telefone() {
     const inputNumerico = this.string.replace(/\D/g, '');
  if (/^\d{10,11}$/.test(inputNumerico)) {
    const telefone = inputNumerico.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1)$2-$3');
        
    
    return telefone;
  }
    }
    
    usuario(){
        if (/^[a-zA-Z0-9]{8,}$/.test(this.string)) {
      if(this.string.length > 7 && this.string.length < 20){
          if(!/^\d+$/.test(this.string)){
             return this.string;
          }
          
      }
      return false;
      
    
  }

  return false;
    }
    
    cep() {
        const cepRegex = /^\d{5}-?\d{3}$/; // Formato básico de CEP
        if (cepRegex.test(this.string)) {
            return this.string;
        }
        return false;
    }

    valida() {
        switch (this.validacao) {
            case 'cpf':
                return this.cpf();
            case 'cnpj':
                return this.cnpj();
            case 'email':
                return this.email();
            case 'telefone':
                return this.telefone();
            case 'usuario':
                return this.usuario();
            case 'cep':
                return this.cep();
            default:
                return false; // Return false if validation type is not recognized
        }
    }
} 

function capitalize(str) {
    if(str){
         return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }else{
        return "Home";
    }
   
}

class SmartAds {
    constructor() {
        this.init();
    }

    init() {
        const divsSemDataAtivo = document.querySelectorAll('.smartAds:not([data-ativo])');
        this.renders = {};

        divsSemDataAtivo.forEach(tag => {
            const modulo = tag?.dataset?.modulo || false;
            const pagina = tag?.dataset?.pagina || false;
            const code = tag?.dataset?.code || false;

            if (modulo && pagina && code) {
                this.renders[code] = {
                    tag: tag,
                    modulo: modulo,
                    pagina: pagina,
                    code: code
                };

                tag.setAttribute("data-ativo", "true"); // Corrigido setAttribute
                tag.classList.remove("smartAds");
                tag.removeAttribute("data-modulo");
                tag.removeAttribute("data-pagina");
                tag.removeAttribute("data-code");
            } else {
                tag.remove(); // Remove divs que não possuem os atributos necessários
            }
        });

        if (Object.keys(this.renders).length > 0) {
            this.start();
        }
    }

    start() {
        let request = new Request(`${dominioscript}/conteudo/modulos/anuncios/admins/api.php`);
        var ads = [];
        var i = 0;
        for(let c in this.renders){
            ads.push({
                code: this.renders[c].code,
                modulo: this.renders[c].modulo,
                pagina: this.renders[c].pagina
            })
        }
        request.addData({
            acao: "getEspacos",
            dados: JSON.stringify(ads)
        })
        request.send().then((r)=>{
            var lista = r.lista;
            for(let c in this.renders){
                if(lista[c]){
                    var i = 0;
                    while(i < lista[c].length){
                        var item = lista[c][i]
                        var div = document.createElement("DIV")
                        div.classList.add("nownAds", "my-4")
                        div.dataset.code = item
                        this.renders[c].tag.appendChild(div)
                        i++;
                    }
                
                }else{
                    this.renders[c].tag.remove();
                }
            }
            
            new  AdsControles();
                new SmartAds();
        })
       
    }
}

class AdsControles{
    constructor(){
        this.espacos = document.getElementsByClassName("nownAds")
        
        /*
        
        */
        if(this.espacos.length > 0){
            if(dataModule("0f109f341095deb67a5ec13f4c18dd1d")){
                var i =0;
                while(i < this.espacos.length){
                    
                    this.render.bind(this)(this.espacos[i])
                    
                    i++;
                }
            }else{
                console.log("Módulo de Anuncios Desativado");
            }
        }
    }
    
    render(container){
        if(container.dataset.code){
             let request = new Request(`${dominioscript}/conteudo/modulos/anuncios/admins/api.php`)
             request.addData({hash: container.dataset.code , acao: "ads"})
             request.send().then((r)=>{
                 
                 let espaco = r.espaco;
                 let criativo = r.criativo;
                 
                 container.classList.add("d-flex", "justify-content-center", "align-items-center")
                 
                 var div = document.createElement("DIV")
                 switch(parseInt(espaco.display)){
                     case 1:
                         break;
                     case 2:
                         div.classList.add("d-none", "d-xl-block")
                         break;
                     case 3:
                         div.classList.add("d-block", "d-xl-none")
                         break;
                 }
                 
                 
                 var metas = espaco.metas
                 switch(parseInt(metas.layout)){
                     case 1:
                         var size = metas.formato
                         var trato = size.split("x")
      
                             var width = parseInt(trato[0]);
                             var height = parseInt(trato[1]);
                             var imagem = document.createElement("DIV");
                             imagem.classList.add("bg-secondary");
                             
                           
                            
                             var img = trataImagem(criativo.arte, "otimizada");
                             if(img){
                                 imagem.style.backgroundImage = `url(${img})`;
                                 imagem.style.backgroundSize = "cover";
                                 imagem.style.backgroundPosition = "center center";
                                 }
                          
                                
                             imagem.style.width = `${width}px`;
                             imagem.style.height = `${height}px`;
                               
                              var a = document.createElement("A")
                              a.appendChild(imagem)
                             if(criativo.link){
                                 if (criativo.link.includes(dominio)) {
                                   a.href = criativo.link;
                                   evento(a, "click", preventLink)
                             } else {
                                 a.href = `${dominio}/conteudo/modulos/anuncios/admins/click.php?id=${criativo.id}&hash=${criativo.hash}&url=${window.location.href}`;
                                 a.target = "_blank"
                             }
                             }
                             
                           
                         
                            
                             div.appendChild(a)
                         
                         break;
                     case 2:
                         break;
                     case 3:
                         break;
                 }
                 
                 console.log(espaco, div)
                 container.appendChild(div)
             }, (r)=>{
                console.log(r)  
             })
        }
    }
}

function loading(pai) {
    return;
    let load = document.querySelector('.loader-wrap') ? document.querySelector('.loader-wrap') : false
    load ? load.style.display = "block" : "";
    if (pai) {
        pai.innerHTML = `
       <div class="d-flex justify-content-center align-items-center h-100 carregandoPage">
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Carregando ...</span>
            </div>
        </div>`
    }
}

function criarIcone(classeIcone) {
    var trato = classeIcone.split(" ");
    var span = document.createElement("SPAN")
    span.classList.add("icone")
    if (trato.length == 2 && trato[0] == "bi") {

        var i = document.createElement("I")
        i.classList.add("bi", trato[1], "text-contrast")
     
        span.appendChild(i)

    } else {
        // Ícone Google Material Icons
        var i = document.createElement("I")
        i.className = classeIcone
         i.style.fontSize = "22px"
        span.appendChild(i)


    }

    return span;
}

function mobileMenuControl(){
     
    if(window.innerWidth  < 1200){
        document.getElementsByTagName("body")[0].classList.remove("ativo")
        
        if(document.getElementsByClassName("openMenu").length > 0){
             document.getElementsByClassName("openMenu")[0].classList.remove("ativo")
        }
       
    }
    
}

function preventLink() {
    var isGooglebot = /Googlebot/.test(navigator.userAgent);
    if (!isGooglebot) {
        var link = event.currentTarget.href;
        mobileMenuControl();
        
        if (link.includes(dominio)) {
            event.preventDefault();
            
            if (link.split("#")[0] !== window.location.href.split("#")[0]) {
                var caminho = link.replace(`${dominio}/`, "");

                var idioma = pegaLocal("idioma");
                var url = idioma ? `${dominio}/${idioma}/${caminho}` : `${dominio}/${caminho}`;
                
                // Remove barras duplicadas na URL
                url = url.replace(/([^:]\/)\/+/g, "$1");

                window.history.pushState(null, null, url);
                start.go(url);
            } else {
                if (link.split("#").length === 2) {
                    var foco = link.split("#")[1];
                    var focoElement = document.getElementById(foco);
                    if (focoElement) {
                        focoElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            }
        } else {
            window.location.href = link;
        }
    }
}

function evento(item, acao, funcao, valor = false) {
  
    acao = acao.toLowerCase();

    function addEvento(elemento, valor) {

        if (valor) {
            elemento.addEventListener(acao, function() {
                funcao(valor)
            });
        } else {
            elemento.addEventListener(acao, funcao);
        }

        var registro = {};
        registro.item = elemento
        registro.acao = acao
        registro.funcao = funcao

        ouvidoresEventos.push(registro);
    }

    if (Array.isArray(item)) {
        var i = 0;
        while (i < item.length) {
            addEvento(item[i], valor)

            i++;
        }
    } else {
        if (item) {
            addEvento(item, valor)
        }

    }
}

function Oldajax(infos, acao, callback = false) {

    var data = new FormData();
    data.append("infos", JSON.stringify(infos))
    data.append("acao", acao);

    if (document.getElementById("typerDoc")) {
        data.append("typerDoc", document.getElementById("typerDoc").dataset.tipo);
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (callback) {
            callback(this.responseText)

        } else {
            console.log(this.responseText);
        }

    }
    xhttp.open("POST", `${dominio}/admin/ajax.php`);
    xhttp.send(data);
}

function loadGoogleMaterialIcons() {
    return new Promise((resolve, reject) => {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = "https://fonts.googleapis.com/icon?family=Material+Icons";

        link.onload = () => {
            resolve("Google Material Icons loaded");
        };

        link.onerror = () => {
            reject(new Error("Failed to load Google Material Icons"));
        };

        document.head.appendChild(link);
    });
}

function geraId(size = 10) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let randomId = '';

    for (let i = 0; i < size; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomId += characters.charAt(randomIndex);
    }

    return randomId;
}

function goUrl(url) {

    // Verifica se a URL começa com "http://" ou "https://"
    if (url.startsWith("http://") || url.startsWith("https://")) {
        // Remove barras extras ao final da URL
        url = url.replace(/\/+$/, '/');
        window.open(url, '_blank');
        return;
    }
    
    
    $extensoesPermitidas = [".php", ".html", ".css"];
    

    var idioma = pegaLocal("idioma");
    var urlPrefix = dominio;

    // Verifica se o idioma existe e se a URL começa com "/"
    if (idioma) {
        urlPrefix += (url && url[0] === "/") ? `${idioma}` : `/${idioma}`;
    }

    // Monta a URL final e remove barras extras
    url = `${urlPrefix}${url ? `/${url}` : ''}`.replace(/\/+$/, '/');

    /*
    if(url.split(`${dominio}/a/`).length == 2 && !autenticado()){
        new Restrito();
        return;
    } else {
        console.log();
    }
    */
    
    
    if (url.split(".").length == 2 && url.split(".")[1] && [".php", ".html", ".css"].some(ext => url.endsWith(ext))) {
        window.open(url, '_blank')
        return;
    }
    

    window.history.pushState(null, null, url);
    start.go(url);
}

var ouvidoresEventos = [];

function trataImagem(imagem, otimiza = false) {
    if(!imagem || imagem == "[]") {
        return false;
    }
   
   
    if (Array.isArray(imagem)) {
        if (imagem.length == 0) {
            return false;
        }
        imagem = imagem[0]
    }else {
        try {
            var trato = JSON.parse(imagem)
            if (Array.isArray(trato) && trato.length > 0) {
                imagem = trato[0]
            }


        } catch (e) {

        }
    }

    if(!imagem){
        return false;
    }
    
    if (!imagem.startsWith("imagens/")) {
        return imagem;
    }


    if (otimiza) {
        var trato = imagem.split("/")
        trato.pop();
        trato.push(`${otimiza}.webp`)
        var imagem = trato.join("/");
    }
    
    const imagePattern = /\.(png|jpe?g|webp|gif)$/i;
    if(!imagePattern.test(imagem)){
        return false;
    }
    

    return `${dominioscript}/conteudo/uploads/${imagem}`
}

function dataFormatada(dataStr, numerico = false){

  // Crie um objeto Date a partir da string de data
  const data = new Date(dataStr);

  // Array para os nomes dos meses
  const meses = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
  ];

  // Extraia o dia, mês e ano da data
  const dia = data.getDate();
  const mes = meses[data.getMonth()];
  const ano = data.getFullYear();

   let hora = data.getHours();
  hora = hora < 10 ? `0${hora}` : hora;
  let minuto = data.getMinutes();
  minuto = minuto < 10 ? `0${minuto}` : minuto;

  // Construa a string formatada
  if (numerico) {
    return `${dia}/${data.getMonth() + 1}/${ano} ${hora}:${minuto}`;
  } else {
    return `${dia} de ${mes} de ${ano} ${hora}:${minuto}`;
  }
  

}

class ModalForm{
    constructor(btn, modulo, id){

        this.id = id;
        this.modulo = modulo;
        this.btn = btn;
        this.cbManual = false;
        setTimeout(()=>{
            this.txtInicial = this.btn.innerHTML;
        }, 50)
                
        
        this.startado = false;
        
         var url = window.location.href.split(dominio)[1].split("/")
        var urlFiltrada = url.filter(function(item) {
            return item !== "";
        });

        this.master = false;
        if(urlFiltrada[0] == "m"){
            this.master = true;
        }
        
        this.init.bind(this)();
    }
    
    init(){
        evento(this.btn, "click", this.show.bind(this))
    }
    
    show(){
          this.btn.setAttribute("disabled", "");
            this.btn.innerHTML = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`
            
        if(!this.startado){
            this.startado = true;
            let request = new Request("admin/brain.php");
            request.addData({
                identificador: this.id,
                modulo: this.modulo,
                tipo: "modal",
                master: this.master
            })
            request.send().then((r)=>{
                
                if(r.modal.erro){
                    this.voltaBtn.bind(this)(true);
                    return;
                }
                
                
               
                this.modalSetup = r.modal.conteudo
                
                if(r.formulario.sucesso){
                    this.formulario = r.formulario.conteudo;
                }
                
                this.monta.bind(this)();
            },(r)=>{
              this.voltaBtn.bind(this)(true);
              
              return;
            })
        }else{
            if(!this.startado){
                this.voltaBtn.bind(this)(true);
                return;
            }else{
                this.modal.show();
                this.voltaBtn();
            }
        }

    }
    
    monta(){
        
        let size;
        let sizePai = false;
        let center;
  
        switch(this.modalSetup.tamanho){
            case 'sm':
                size = "modal-sm"
                break;
            case 'md':
                break;
            case 'lg':
                size = "modal-lg"
                break;
            case 'xl':
                size = "modal-xl"
                break;
            case 'full':
                sizePai = "modal-full"
                break;
        }
        
        if(this.modalSetup.centralizar){
            center = "modal-dialog-centered";
        }
        
        this.id = geraId();
        var html = document.createElement("DIV")
        html.classList.add("modal","fade");
        if(sizePai){
           html.classList.add(sizePai) 
        }else{
            html.classList.add("modal-nown")
        }

        
        let closeTop = "";
        if(this.modalSetup.fecharTopo){
            closeTop = '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>'
        }
        
        let closeBottom;
        if(this.modalSetup.fecharFooter){
            closeBottom = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>'
        }
        
        
        let titulo =  this.modalSetup?.modal.titulo || "";
        if(titulo){
            titulo = `<h2 class="fs-20 m-0 fw-700">${titulo}</h2>`;
        }


        html.id = this.id;
        html.setAttribute("tabindex","-1") 
        html.setAttribute("aria-hidden","true")
        html.innerHTML = `
        <div class="modal-dialog ${size} modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    ${titulo}
                    ${closeTop}
                </div>
                <div class="modal-body d-flex flex-column justify-content-center"></div>
   
    </div>
  </div>
        `
        
        var conteudo = document.createElement("DIV")
        html.getElementsByClassName("modal-body")[0].appendChild(conteudo)
        if(this.modalSetup.container){
            conteudo.classList.add("container")
        }
        
        document.getElementById("conteudo").appendChild(html)
        
       

        
        
        
        
        if(this.formulario){
            nownFiles.add(`${dominio}/assets/js/renderForm.js`).then(()=>{
                
                
                
            var key = geraId();
            
            conteudo.classList.add("formulario-nown")
        
            var formulario = new FormularioRenderizado(this.formulario.inputs, conteudo , key, false, 
            {
                modulo: this.modulo,
                identificador: this.formulario.hash,
                master: this.master,
                editMode: false,
                dados: false
                
            })
            if(this.js){
               formulario.setCb(this.js); 
            }
            
            formulario.size(this.formulario.tamanho)
            
         

            if(parseInt(this.modalSetup.tipo) == 2){
                let cb = this.modalSetup.callback || this.cbSimples.bind(this)
                formulario.interpepta(cb);
            }else{
               if(!this.modalSetup.redirecionar && this.modalSetup.redirCb){
                formulario.posSucesso(this.modalSetup.redirCb);
                }else{
                formulario.posSucesso(this.registrado.bind(this));
                } 
            }
            
            
            
            

            formulario.processa();
            
            this.form = formulario;
                
                
                
            })

        }
        
        this.modal = new bootstrap.Modal(`#${this.id}`, {
            keyboard: false
        })
        
        
        this.modal.show();
        this.voltaBtn();

    }
    
    cbSimples(r){
        console.log(r)
    }
    
    setCb(r){
        this.cbManual = r
    } 
    
    registrado(r){

        if(this.cbManual){
            this.cbManual(r)
        }
    
        if(r.erro){
            return;
        }

        iziToast.success({
            icon: 'bi bi-check-circle',
            title: 'Sucesso',
            message: 'Cadastro feito com Sucesso'
        });
        
        this.modal.hide();

        switch(parseInt(this.modalSetup.redirecionar)){
            case 0:

                if (this.modalSetup.redirCb && typeof window[this.modalSetup.redirCb] === 'function') {
                    window[this.modalSetup.redirCb]();
                }


                break;
            case 1:
                var tipo = this.modalSetup?.redirecionarTipo || 1;
                var chaves = ["", "id", "url"]
                goUrl(`${this.modalSetup.redirCb}/${r[chaves[tipo]]}`);
                break;
            case 2:
                if(start.conteudo.tabelo){
                    start.conteudo.tabelo.novaLinha(r);
                }
                this.form.reset();
                break;
        }

    }
    
    voltaBtn(erro = false){
        if(erro){
            iziToast.error({
                icon: 'bi bi-x-lg',
                title: 'Atenção',
                message: 'Erro no Modal , contacte o administrador.'
            });
        }
        
        this.btn.innerHTML = this.txtInicial
        this.btn.removeAttribute("disabled")
    }
}
 
class HeaderPage{
    constructor(r, url = false){
        

        this.url = url
        this.hash = r.hash;
        this.r = r
        if(r.titulo || (r.btns && r.btns.length > 0) || r.breadcrumbs){
            this.titulo = r.titulo
            this.btns = r.btns.length > 0 ? r.btns : false;
      
            this.breadcrumbs = r.breadcrumbs
            
            this.render.bind(this)();
        }
    }
    
    render(){
        var container = document.createElement("DIV")
        container.classList.add("container")
        
        var card = document.createElement("DIV")
        card.classList.add("card", "border-0", "bg-transparent")

        var body = document.createElement("DIV")
        body.classList.add("card-body", "d-flex", "justify-content-between", "align-items-center", "px-0")
        card.appendChild(body)
        
        
        var esquerda = document.createElement("DIV")
        var direita = document.createElement("DIV")
        
        body.appendChild(esquerda)
        body.appendChild(direita)
        direita.classList.add("d-flex", "justify-content-end", "aling-items-center", "gap-2")
        
        if(this.titulo){
            var h1 = document.createElement("H1")
            h1.classList.add("fs-20", "fw-600", "m-0", "aqui")
            
            if (this.titulo.includes('{novo}')) {
                 if(this.hash){
                    this.titulo = this.titulo.replaceAll('{novo}', "Editar")
                }else{
                    this.titulo = this.titulo.replaceAll('{novo}', "Novo")
                }
                
            }
            
            if (this.titulo.includes('{nova}')) {
                 if(this.hash){
                    this.titulo = this.titulo.replaceAll('{nova}', "Editar")
                }else{
                    this.titulo = this.titulo.replaceAll('{nova}', "Nova")
                }
                
            }
             
             h1.innerText = this.titulo
           
            
            
            
           
            esquerda.appendChild(h1)
        }
        
        this.esquerda = esquerda;
        this.direita = direita;
        if(this.btns){
            var i = 0;
            var colores = ["primaria", "secundaria",  "terciaria", "quaternaria"];
            
           
            while(i < this.btns.length){
                var info = this.btns[i]
              
        
                var btn = false;
                switch(parseInt(info.tipo)){
                    case 1:
                        
                    var btn = document.createElement("A")
                    if(info.callback){
                        btn.href = info.callback.startsWith('http') ? info.callback : `${dominio}/${info.callback}`
                    }
                    evento(btn, "click", preventLink)
                        break;
                    case 2:
                         var btn = document.createElement("BUTTON")
                     evento(btn, "click", ()=>{
                         if (typeof window[info.callback] === 'function') {
                         evento(btn, "click", eval(info.callback))
                         
                     } else {
                         console.log('A função não existe:',  info.callback);
                         
                     }
                         
                     })
                        break;
                    case 3:
                         var btn = document.createElement("BUTTON")
                        break;
                    case 4:
                        if(this.url){
                            var btn = document.createElement("A")
                            var caminho = `${dominio}/${info.callback}/${this.url}`
                            caminho = caminho.replace(/([^:]\/)\/+/g, "$1");
                            btn.href = caminho
                            btn.setAttribute("target", "_blank")
                        }else{
                            var btn = false;
                        }
                        break;
                    case 5:
                         var btn = document.createElement("BUTTON")
                         new ModalForm(btn, this.r.modulo, info.callback);

                        break;
                }
                
                if(btn){
               
                btn.classList.add("btn", `btn-n-${colores[i]}`, "btn-nown-style", "d-flex", "justify-content-center", "gap-2", "align-items-center")
                
                if(info.id && info.id.trim()){
                    btn.id = info.id
                }
                
                
                if(info.icone && info.icone.trim()){
                    var i = document.createElement("I")
                    i.className = info.icone.trim()
                    btn.appendChild(i)
                }
                
                var span = document.createElement("SPAN")
                span.innerText = info.texto;
                
                btn.appendChild(span)
                direita.appendChild(btn) 
                }
           
                i++;
            }
        }
        
        if(this.breadcrumbs){
            
            
            var url = window.location.href.split("//")
            var trato = url[1].split("/");
            let arrayFiltrado = trato.filter(function(elemento) {
                return elemento !== '' && elemento !== null && elemento !== undefined && elemento !== false;
            });
            


            var flex = document.createElement("NAV")
            flex.setAttribute("aria-label","breadcrumb")
            flex.style = `--bs-breadcrumb-divider: '/';`

            
            var ol = document.createElement("OL")
            ol.classList.add("breadcrumb")
      
            var caminho = "https://"
            for(let c in arrayFiltrado){
                
                var item = arrayFiltrado[c]
      
                
                var li = document.createElement("LI")
                li.classList.add("breadcrumb-item")
                
                if(item != "a" && item != "m" && (c != arrayFiltrado.length -1)){
                    var span = document.createElement("a")
                    
                    evento(span, "click", preventLink)
                }else{
                    var span = document.createElement("SPAN")
                }
                
                span.classList.add("text-decoration-none", "fs-12", "fw-900", "text-primaria")
                span.innerText = item
                caminho = `${caminho}${item}/`
                span.href = caminho
                li.appendChild(span)
                ol.appendChild(li)
                
          
                
            }
            flex.appendChild(ol);
            if(this.titulo){
                flex.classList.add("mt-1")
            }
            esquerda.appendChild(flex)
        }

        if(this.hash && this.url && this.r.editUrl){

            var footer = document.createElement("DIV")
            footer.classList.add("card-footer", "bg-transparent", "border-0", "px-0")
            
            var div = document.createElement("DIV")
            div.classList.add("input-group")
            
            var esquerda = document.createElement("SPAN")
            esquerda.classList.add("input-group-text")
            esquerda.innerText = "URL"
            
            var input = document.createElement("INPUT")
            input.classList.add("form-control")
            input.setAttribute("disabled", "")
            input.value = this.url
            this.original = this.url
            evento(input, "INPUT", this.trato.bind(this))
            this.input = input
            
            direita = document.createElement("BUTTON")
            direita.classList.add("btn", "btn-contrast", "d-flex", "justify-content-center", "align-items-center")
            direita.innerHTML = `<i class="bi bi-pencil-square"></i>`
            evento(direita, "click", this.editaUrl.bind(this))
            this.edita = direita
            this.editando = false;
         
            div.appendChild(esquerda)
            div.appendChild(input)
            div.appendChild(direita)
            
            footer.appendChild(div)
            
            card.appendChild(footer)
            card.classList.add("mb-3")
            
        }

        container.appendChild(card)
        
        
        
        
        var md = document.createElement("DIV")
        md.classList.add("mdTopo")
        md.innerHTML = `
        <style>#conteudo{
            padding-top: 0px;
        }</style>
        <div class="button-container">
        <button class="icon-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M3 18.0001V16.0001H21V18.0001H3ZM3 13.0001V11.0001H21V13.0001H3ZM3 8.00012V6.00012H21V8.00012H3Z" fill="#1D1B20"></path>
            </svg>
        </button>
    </div>
    <div class="title-container">
        <h1 class="title-text">${this.titulo}</h1>
    </div>
    <div style="display: flex;
height: 48px;
justify-content: flex-end;
align-items: center;">
        <div class="button-container">
          <button class="icon-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M11.5 22.0001C9.96667 22.0001 8.66667 21.4668 7.6 20.4001C6.53333 19.3335 6 18.0335 6 16.5001V6.00012C6 4.90012 6.39167 3.95846 7.175 3.17512C7.95833 2.39179 8.9 2.00012 10 2.00012C11.1 2.00012 12.0417 2.39179 12.825 3.17512C13.6083 3.95846 14 4.90012 14 6.00012V15.5001C14 16.2001 13.7583 16.7918 13.275 17.2751C12.7917 17.7585 12.2 18.0001 11.5 18.0001C10.8 18.0001 10.2083 17.7585 9.725 17.2751C9.24167 16.7918 9 16.2001 9 15.5001V6.00012H10.5V15.5001C10.5 15.7835 10.5958 16.021 10.7875 16.2126C10.9792 16.4043 11.2167 16.5001 11.5 16.5001C11.7833 16.5001 12.0208 16.4043 12.2125 16.2126C12.4042 16.021 12.5 15.7835 12.5 15.5001V6.00012C12.5 5.30012 12.2583 4.70846 11.775 4.22512C11.2917 3.74179 10.7 3.50012 10 3.50012C9.3 3.50012 8.70833 3.74179 8.225 4.22512C7.74167 4.70846 7.5 5.30012 7.5 6.00012V16.5001C7.5 17.6001 7.89167 18.5418 8.675 19.3251C9.45833 20.1085 10.4 20.5001 11.5 20.5001C12.6 20.5001 13.5417 20.1085 14.325 19.3251C15.1083 18.5418 15.5 17.6001 15.5 16.5001V6.00012H17V16.5001C17 18.0335 16.4667 19.3335 15.4 20.4001C14.3333 21.4668 13.0333 22.0001 11.5 22.0001Z" fill="#49454F"></path>
</svg>
        </button>
    </div>
     <div class="button-container">
          <button class="icon-button">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M9 16.5001C8.3 16.5001 7.70833 16.2585 7.225 15.7751C6.74167 15.2918 6.5 14.7001 6.5 14.0001C6.5 13.3001 6.74167 12.7085 7.225 12.2251C7.70833 11.7418 8.3 11.5001 9 11.5001C9.7 11.5001 10.2917 11.7418 10.775 12.2251C11.2583 12.7085 11.5 13.3001 11.5 14.0001C11.5 14.7001 11.2583 15.2918 10.775 15.7751C10.2917 16.2585 9.7 16.5001 9 16.5001ZM5 22.0001C4.45 22.0001 3.97917 21.8043 3.5875 21.4126C3.19583 21.021 3 20.5501 3 20.0001V6.00012C3 5.45012 3.19583 4.97929 3.5875 4.58762C3.97917 4.19596 4.45 4.00012 5 4.00012H6V2.00012H8V4.00012H16V2.00012H18V4.00012H19C19.55 4.00012 20.0208 4.19596 20.4125 4.58762C20.8042 4.97929 21 5.45012 21 6.00012V20.0001C21 20.5501 20.8042 21.021 20.4125 21.4126C20.0208 21.8043 19.55 22.0001 19 22.0001H5ZM5 20.0001H19V10.0001H5V20.0001Z" fill="#49454F"></path>
</svg>
        </button>
    </div>
    <div class="button-container">
        <button class="icon-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 20.0001C11.45 20.0001 10.9792 19.8043 10.5875 19.4126C10.1958 19.021 10 18.5501 10 18.0001C10 17.4501 10.1958 16.9793 10.5875 16.5876C10.9792 16.196 11.45 16.0001 12 16.0001C12.55 16.0001 13.0208 16.196 13.4125 16.5876C13.8042 16.9793 14 17.4501 14 18.0001C14 18.5501 13.8042 19.021 13.4125 19.4126C13.0208 19.8043 12.55 20.0001 12 20.0001ZM12 14.0001C11.45 14.0001 10.9792 13.8043 10.5875 13.4126C10.1958 13.021 10 12.5501 10 12.0001C10 11.4501 10.1958 10.9793 10.5875 10.5876C10.9792 10.196 11.45 10.0001 12 10.0001C12.55 10.0001 13.0208 10.196 13.4125 10.5876C13.8042 10.9793 14 11.4501 14 12.0001C14 12.5501 13.8042 13.021 13.4125 13.4126C13.0208 13.8043 12.55 14.0001 12 14.0001ZM12 8.00012C11.45 8.00012 10.9792 7.80429 10.5875 7.41262C10.1958 7.02096 10 6.55012 10 6.00012C10 5.45012 10.1958 4.97929 10.5875 4.58762C10.9792 4.19596 11.45 4.00012 12 4.00012C12.55 4.00012 13.0208 4.19596 13.4125 4.58762C13.8042 4.97929 14 5.45012 14 6.00012C14 6.55012 13.8042 7.02096 13.4125 7.41262C13.0208 7.80429 12.55 8.00012 12 8.00012Z" fill="#49454F"></path>
            </svg>
        </button>
    </div>
    </div>
        
        
        `
        
        
        
        
        
        
        document.getElementById("conteudo").insertBefore(container, document.getElementById("conteudo").firstChild);
        
        //document.getElementById("conteudo").insertBefore(md , document.getElementById("conteudo").firstChild);
        new setSEO();

       
    }
    
    trato(){
        this.input.value = paraUrl(this.input.value);
    }
    
    editaUrl(){
        if(!this.editando){
            this.editando = true;
            this.edita.classList.remove("bg-contrast")
            this.edita.classList.add("btn-n-primaria")
            this.edita.innerHTML = `<i class="bi bi-floppy"></i>`
            this.input.removeAttribute("disabled")
        }else{
             if(this.original == this.input.value){
                 this.editacaoConcluida.bind(this)()
             }else{
                 this.edita.setAttribute("disabled", "")
                 this.edita.innerHTML = `
                 <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`
                
                
                this.ajax.bind(this)()

             }
        }
    }
    
    ajax(){
        let request = new Request(`${dominio}/admin/brain.php`);
        request.addData({
            modulo: this.r.modulo,
            tipo: "url",
            identificador: this.r.id,
            hash: pegaHash(),
            url: this.input.value
        })
        request.send().then((obj)=>{
             this.editacaoConcluida.bind(this)(this.input.value);
                    iziToast.success({
                        icon: 'bi bi-check-circle',
                        title: 'Sucesso',
                        message: 'URL atualizada!',
                    });
        },(r)=>{
            this.editacaoConcluida.bind(this)(this.original);
                     this.input.value = this.original
                    Swal.fire({
                        title: "Erro ao Atualizar URL",
                        text: obj.mensagem,
                        icon: "error"
                    });
        })
        
        /*
        var data = new FormData();
        data.append("modulo", this.r.modulo)
        data.append("tipo", "url")
        data.append("identificador", this.r.id)
        data.append("hash", caminho.hash())
        data.append("url", this.input.value)
        
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
               
                if(obj.sucesso){
                   
                }else{
                     
                }
                
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/brain.php`); 
        request.send(data)
        */
        
    }
    
    editacaoConcluida(url){
        this.original = url
        this.editando = false;
        this.edita.removeAttribute("disabled")
        this.edita.classList.add("bg-contrast")
        this.edita.classList.remove("btn-n-primaria")
        this.edita.innerHTML = `<i class="bi bi-pencil-square"></i>`
        this.input.setAttribute("disabled", "")
    }
}

function invalido(elemento, valido, mensagem = false) {
  var pai = elemento.closest("DIV");
  var el;

    if(mensagem){
  if (valido) {
    if (pai.getElementsByClassName("valid-feedback").length === 0) {
      el = document.createElement("DIV");
      el.classList.add("valid-feedback");
      pai.appendChild(el);
    } else {
      el = pai.getElementsByClassName("valid-feedback")[0];
    }
  } else {
    if (pai.getElementsByClassName("invalid-feedback").length === 0) {
      el = document.createElement("DIV");
      el.classList.add("invalid-feedback");
      pai.appendChild(el);
    } else {
      el = pai.getElementsByClassName("invalid-feedback")[0];
    }
  }
    
    
       el.innerText = mensagem; 
    }
  

  if (valido) {
    elemento.classList.add("is-valid");
    elemento.classList.remove("is-invalid");
  } else {
    elemento.classList.add("is-invalid");
    elemento.classList.remove("is-valid");

    elemento.addEventListener("input", function () {
      elemento.classList.remove("is-invalid");
      elemento.removeEventListener("input", arguments.callee);
    });
  }
}

class PageCategoria{
    constructor(estrutura){

        this.modulo = estrutura.modulo
        this.master = estrutura.master
        this.id = estrutura.id
        this.container = document.getElementById("conteudo")
        
        this.container.innerHTML = "";

        var texto = parseInt(estrutura.tipo) == 3 ? "Nova Categoria" : "Nota Tag";
        estrutura.btns = [{tipo: 3, texto: texto, icone: "bi bi-plus-circle-fill"}]
        
        new HeaderPage(estrutura)
        
        this.estrutura.bind(this)()
        
        let request = new Request("/admin/brain.php");
        request.addData({tipo: "listaCategorias", modulo: this.modulo, master: this.master, identificador: this.id});
        request.send().then((r)=>{
            console.log("sucesso")
            console.log(r)
        }, (r)=>{
            console.log("erro")
            console.log(r)
        })
    }
    
    estrutura(){
        var container = document.createElement("DIV")
        container.classList.add("container")
        
        var card = document.createElement("DIV")
        card.classList.add("card", "card-nown")
        
        var body = document.createElement("DIV")
        body.classList.add("card-body");
        card.appendChild(body)
        
        body.innerHTML = `
        <table id="myTable" class="tabelaNown w-100">
    <thead>
        <tr>
            <th class="wi-50 text-center"> <input class="form-check-input wi-25 he-25 m-0" type="checkbox" value="" id="defaultCheck1"> </th>
            <th>Nome</th>
             <th class="wi-50">Ação</th>
        </tr>
    </thead>
    <tbody>

        <tr>
           <td class="text-center"><input class="form-check-input wi-25 he-25 m-0" type="checkbox" value="" id="defaultCheck1"></td>
            <td>Row 2 Data 1</td>
            <td >  <div class="d-flex gap-2">
                        <button class="btn-ferramenta border-primary border text-primary wi-40 he-40"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn-ferramenta border-danger border text-danger wi-40 he-40"><i class="bi bi-trash3"></i></button>
                    </div></td>
        </tr>
         <tr>
           <td class="text-center"><input class="form-check-input wi-25 he-25 m-0" type="checkbox" value="" id="defaultCheck1"></td>
            <td>Row 2 Data 1</td>
            <td >  <div class="d-flex gap-2">
                        <button class="btn-ferramenta border-primary border text-primary wi-40 he-40"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn-ferramenta border-danger border text-danger wi-40 he-40"><i class="bi bi-trash3"></i></button>
                    </div></td>
        </tr>

    </tbody>
</table>
        
       
  
        
        `
        
        
        
        container.appendChild(card)
        
        this.container.appendChild(container)
        
        
        
    }
    
    
    
}

class FormEstrutura{
    constructor(r, cb = false, js){
        this.r = r
        this.ajax = this.ajax.bind(this)
        this.identificador = r.id
        this.modulo = r.modulo
        this.master = r.master
        this.hash = r.hash
        this.cb = cb;
        this.js = js;
        
        this.container = document.getElementById("conteudo")
        
        this.array = [
            `${dominio}/assets/aplicativo/quill/min.js`,
            `${dominio}/assets/aplicativo/quill/min.css`,
            `${dominio}/assets/aplicativo/filepond/min.js`,
            `${dominio}/assets/aplicativo/filepond/min.css`,
            `${dominio}/assets/aplicativo/sweetalert2/11105.js`,
            `${dominio}/assets/bibliotecas/tagify/tagify.js`,
            `${dominio}/assets/bibliotecas/tagify/tagify.css`,
            `${dominio}/assets/aplicativo/select2/41.css`,
            `${dominio}/assets/aplicativo/select2/41.js`,
            ];
        this.estrutura.bind(this)()
        
         
    }
    
    estrutura(){
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var col1 = document.createElement("DIV")
        col1.classList.add("col-12", "col-xl-9" , "d-flex", "flex-column", "gap-2")
        
        
        var i = 0;
        while(i < 5){
              var div = document.createElement("DIV")
        div.classList.add("he-50", "bg-carregando")
        col1.appendChild(div)
        
        
         var div = document.createElement("DIV")
        div.classList.add("he-200", "bg-carregando")
        col1.appendChild(div)
            
            i++;
        }
        
        
        var col2 = document.createElement("DIV")
        col2.classList.add("col-12", "col-xl-3", "d-flex", "flex-column", "gap-2")
        
        var i = 0;
        while(i < 4){
            var box = document.createElement("DIV")
        box.classList.add("ratio","ratio-1x1", "bg-carregando")
        col2.appendChild(box)
            
            i++;
        }
        
        row.appendChild(col1)
        row.appendChild(col2)
        
        this.container.innerHTML = ""
        
        this.cont = document.createElement("DIV")
        this.cont.classList.add("container")
        this.cont.appendChild(row)
        this.container.appendChild(this.cont)
       
       
       
       nownFiles.add(this.array).then(()=>{
           var data = new FormData()
           data.append("tipo", "formulario")
           this.ajax(data, this.monta.bind(this))
        })

    }
    
    monta(r){
  
        if(!r.formulario){
            let titulo = "Item não encontrado";
            let mensagem = "O item que você está tentando editar não existe.";
            if(r.pontos){
                titulo = "Atenção"
                mensagem = "Você não tem permissão para acessar essa página";
            }
             this.container.innerHTML = `
                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                <div>
                 <div class="mt-5"><h2 class="text-center corGlobal text-uppercase fs-30">${titulo}</h2></div>
                 <div class="my-5"><p class="corGlobal text-center">${mensagem}</p></div>
                 <div class="text-center">
                 <a class="btn btn-nown-style btn-n-primaria" href="${dominio}">Voltar para a Home</a>
                 </div>
                 </div>
                 </div>
                 `;
                 evento(this.container.getElementsByTagName("a")[0], "click", preventLink)
            return;
        }
        
        
        
        var url = false;
        if(r.dados && r.dados.especiais && r.dados.especiais.url){
            url = r.dados.especiais.url;
        }
  
        new HeaderPage(this.r, url)
   
        nownFiles.add(`${dominio}/assets/js/renderForm.js`).then(()=>{
            var key = geraId();
            
             this.cont.classList.add("formulario-nown")
              
              
            var formulario = new FormularioRenderizado(r.formulario.inputs, this.cont , key, false, 
            {
                modulo: this.modulo,
                identificador: r.formulario.hash,
                master: this.master,
                editMode: this.hash,
                dados: r.dados
                
            })
            if(this.js){
               formulario.setCb(this.js); 
            }
            
            formulario.size(r.formulario.tamanho)
            formulario.processa();
        })
    }
    
    ajax(data, cb = false){
         data.append("identificador", this.identificador);
         data.append("modulo", this.modulo);
         data.append("master", this.master);
         data.append("hash", this.hash)
        
         
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(obj.sucesso){
                    if(cb){
                        cb(obj)
                    }else{
                        console.log(obj)
                    }
                }else{
                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/brain.php`);
        request.send(data)
    }
}

class OpcoesDinamicas{
    constructor(input , tipo , foco, cb = false){
        
        this.input = input
        this.tipo = tipo
        this.foco = foco
        this.type = "select"
       
       this.big = false;

        
        this.cb = !cb ? this.monta.bind(this) : cb;
       
       
    }
    
    isBig(){
        this.big= true;
    }
    
    setTipo(tipo){
         this.type = tipo
    }
    
    render() {
    return new Promise((resolve, reject) => {
        let request = new Request("/admin/opcoes.php");
        request.addData({"tipo": this.tipo, "foco": this.foco});
        
        request.send().then((r) => {

            this.cb(r);
            resolve(r); 
        }).catch((r) => {
    
            reject(r); 
        });
    });
}
  
    opt(valor, text){
        
        
         switch(this.type){
                case 'select':
                   var opt = document.createElement("OPTION")
                                    opt.value = valor
                                    opt.innerText = text
                                
                    break;
                case 'radio':
                case 'checkbox':
                    var id = geraId();
                    opt  = document.createElement("DIV")
                    opt.classList.add("form-check")
                    
                    var input = document.createElement("INPUT")
                    input.classList.add("form-check-input")
                    
  
                    if(this.type == "radio"){
                        input.type = "radio" 
                    }else{
                        input.type = "checkbox"
                    }
                    
                    if(this.input.dataset.grupo){
                        input.name = this.input.dataset.grupo
                    }
                   

                    input.id = id;
                    input.value = valor
                    
                    var label = document.createElement("label")
                    label.classList.add("form-check-label")
                    label.setAttribute("for", id)
                    label.innerText = text
                    
                    opt.appendChild(input)
                    opt.appendChild(label)

                    break;
            }
        
        

        return opt;
    }
    
    monta(r){
        var lista = r.lista
    
        for(let i in lista){
        
           var option = document.createElement("OPTION")
           this.input.appendChild(this.opt(lista[i], i))
        }
        
    
        if(this.input.dataset.PreValor){
            var trato = this.input.dataset.PreValor.split(",")
            if(trato.length == 1){
                 this.input.value = trato[0]
            }else{

                var option = this.input.getElementsByTagName("option")
            for(let c in trato){
                var item = trato[c]
          
                var i = 0;
                while(i < option.length){
                    if(option[i].value == item){
                        option[i].selected = true;
                        break;
                    }
                    i++;
                }
       
            }
            
            }
        }
        
        
        if(this.input.dataset.valor){
            var valor = this.input.dataset.valor
            this.input.value = valor
        }
    }
}

function tratoEspaco(inputString) {
   let words = inputString.split(/[\s-]+/);


  let formattedWords = words.map((word, index) => {
    if (index === 0) {
      return word;
    } else {
      return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    }
  });

  // Junta as palavras formatadas
  let result = formattedWords.join('');

  return result; 
}


async function futuro(url){
  var link = url.replace(`${dominio}/`, "");
  var link = link.split("#")[0]
  var banco = new BancoDeDados("paginas", "paginas");
  let previa = await banco.umItem(link);
  
        
        var admin = parseInt(start.estrutura.userInfo("funcao", 2)) > 1 ? false : true

        if(start.estrutura.dataInfo("manutencao", "modo-manutencao", "modomanutencao", false) && !admin && !link.startsWith("acesso")){
            return;
        }
        
        RequestRoute.quick("nown", "rotas", {p: link, rotas: link}).then((obj)=>{
 
               try{
                     if (obj.html) {
                    cripto(JSON.stringify(obj)).then(hash => {
                        if (previa.hash != hash) {
                            obj.hash = hash
         
                            if(obj.type){
                                switch(obj.type){
                                    case "item":
                                        var modulo = obj.url.split("/")[0]
                                        var url = `${modulo}/*`
                                        obj.url = url
                                        banco.novo([obj]);
                                        break;
                                    case "filho":
                                        var trato = obj.url.split("/")
                                        var url = `${trato[0]}/${trato[1]}/*`
                                        obj.url = url
                                        banco.novo([obj]);
                                        break;
                                    default:
                                        banco.novo([obj]);
                                        break;
                                        
                                }
                            }else{
                                banco.novo([obj]);
                            }
                        } 
                    })
                }
                    
                    
                }catch(e){
                    console.log(e)
                    console.log(xhttp.responseText)
                    
                }
                
        
        }, (r)=>{
            console.log("erro", r)
        })

    }

class ItemGrade{
    constructor(pai, item){
        this.pai = pai;
        this.item = item
    }
    
    render(){
        var div = document.createElement("DIV")
        div.innerHTML = this.pai.html
        
        
        for(let c in this.pai.map){

            const element = div.querySelector(`[data-map="${c}"]`);

            if(element && this.item[this.pai.map[c]]){
                element.innerText = this.item[this.pai.map[c]]
                element.removeAttribute("data-map")
            }
        }
        
        var btns = Array.from(div.querySelectorAll(`[data-btn]`));
        var i = 0;
        while(i < btns.length){
            evento(btns[i], "click", this.acao.bind(this))
            i++;
        }
 
        this.html = div.firstChild
        return this.html;
    }
    
    showAcoes(item){
 
        if(item.parentNode.getElementsByClassName("blocoAcoesLista").length > 0){
            document.getElementsByClassName("blocoAcoesLista")[0].remove();
            return;
        }else{
           if(document.getElementsByClassName("blocoAcoesLista").length > 0){
            document.getElementsByClassName("blocoAcoesLista")[0].remove();
        } 
        }
        
        
     

        
        var div = document.createElement("UL")
        div.classList.add("dropdown-menu", "blocoAcoesLista", "show", "dropdown-nown")

        for(let c in this.pai.acoes){
            var li = document.createElement("LI")
  
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn","dropdown-item","d-flex","justify-content-start","gap-2","align-items-center","btn-sm", "fs-14")
            
            var icone = document.createElement("I")

     
            var texto = document.createElement("SPAN")
  
            btn.appendChild(icone)
            btn.appendChild(texto)
            li.appendChild(btn)
            
            switch(this.pai.acoes[c].tipo){
                case 'editar':
                    icone.className = "bi bi-pencil-square"
                    texto.innerText = "Editar"
                    evento(btn, "click", this.edita.bind(this))
                    this.rotaEdita = this.pai.acoes[c].rota
                    break;
                case 'apagar':
                    icone.className = "bi bi-trash3"
                    texto.innerText = "Apagar"
                    evento(btn, "click", this.apaga.bind(this))
                    break;
                case 'vizualizar':
                    this.rotaVer = this.acoes[c].rota
                    icone.className = "bi bi-eye"
                    texto.innerText = "Visualizar"
                    evento(btn, "click", this.ver.bind(this))
                    this.rotaVer = this.pai.acoes[c].rota;
                    break;
                case 'outros':
                    var item = this.acoes[c].rota;
                    icone.className = item.icone
                    texto.innerText = item.texto
                    evento(btn, "click", ()=>{ this.custom.bind(this)(item.callback)})
                    break;

            }

            div.appendChild(li)
        }
        
 
        var pai = item.parentNode;

        pai.classList.add("dropdown", "dropAcoes")
        pai.appendChild(div)
        console.log(pai)

    }
    
    edita(){
        goUrl(`${this.rotaEdita}/${this.item.extra.s}`)
    }
    
    ver(){
        goUrl(`${this.rotaVer}/${this.item.extra.p}`)
    }
    
    apaga(){
        Swal.fire({
            icon: "question",
            title: "Tem certeza?",
            text: "Após confirmar , esse item será apagado totalmente do banco de dados.",
            showCancelButton: true,
            confirmButtonText: "Apagar",
            cancelButtonText: `Cancela`
        }).then((result) => {
            if (result.isConfirmed) {
                this.pai.remove(this.item.extra.s, this.item.id)
            } 
        });
    }
    
    acao(){
        if(!event.currentTarget.dataset.foco){
            return;
        }
        var btn = event.currentTarget
        var foco = btn.dataset.foco;

        switch(foco){
            case 'drop':
                this.showAcoes.bind(this)(btn);
                break;
            case 'editar':
                this.edita.bind(this)()
                break;
            case 'vizualizar':
                this.ver.bind(this)()
                break;
            case 'apagar':
                this.apaga.bind(this)()
                break;
            default:
                break;
        }
    }
}

class Grade{
    constructor(r, js, container = false){
        
        
        
        this.js = js;
        this.r = r
        

        
        this.identificador = r.id
        this.modulo = r.modulo
        this.master = r.master
        this.container = !container ? document.getElementById("conteudo") : container;
        this.container.innerHTML = "";
        
        this.itens = {};
        
        this.init.bind(this)();
        //evento(window, "click", this.clickout.bind(this))
        
        
    }
    
    morre(){
        
    }
    
     clickout(){
         var target = event.target
         var pai = target.closest(".dropAcoes")
   
         if(!pai){
             if(document.getElementsByClassName("blocoAcoesLista").length > 0){
                 document.getElementsByClassName("blocoAcoesLista")[0].remove();
             }
             
         }
    }
    
    init(){
        new  HeaderPage(this.r);
        
        let detalhes = this.r.subDetalhes;
        
        this.pai = document.createElement("DIV")
        
        
        if(detalhes.pai && detalhes.pai.trim()){
            this.pai.className = detalhes.pai.trim();
        }
        
        
        
        if(detalhes.container == "0"){
            var container = document.createElement("DIV")
            container.classList.add("container")
            container.appendChild(this.pai)
            this.container.appendChild(container)
        }else{
            this.container.appendChild(this.pai)
        }
        
        
        
        
        let request = new Request("/admin/brain.php");
        request.addData(
            {
                "tipo": "tabela",
                "identificador": this.identificador,
                "modulo": this.modulo,
                "master": this.master
            }
        )
        request.send().then((r)=>{
            this.monta.bind(this)(r)
            
        }, (r)=>{
            
        })
    }
    
    geraHtml(){
        console.log("teste")
    }
    
    monta(r){
        
        this.map = {};
        var i = 0;
        while(i < r.cabecalho.length){
            var item = r.cabecalho[i]
            this.map[item] = i;
            i++;
        }
        
        
        this.acoes = r.acoes;
        this.resposta = r;
        this.html = r.html;
        
        if(!this.html && !this.html.trim()){
            return;
        }

        var i = 0;
        var fragmento = document.createDocumentFragment();
        while(i < r.lista.length){
            var item = r.lista[i]
            this.itens[item.id] = new ItemGrade(this, item);
            fragmento.appendChild(this.itens[item.id].render())
            i++
        }

        this.pai.appendChild(fragmento)
        
    }
    
    remove(hash, id){
        this.itens[id].html.remove();
        let request = new Request("/admin/brain.php");
        request.addData(
            {
                "tipo": "apagar",
                "hash": hash,
                "identificador": this.identificador,
                "modulo": this.modulo,
                "master": this.master
            }
        )
        request.send().then((r)=>{
            if(r.sucesso){
                delete this.itens[id];
                Swal.fire({icon: "success",title: "Apagado",showConfirmButton: false,timer: 1500});
            }else{
                Swal.fire({icon: "error",title: "Atenção", text: r.mensagem ,showConfirmButton: false,timer: 1500});
            }
            
        }, (r)=>{
            console.log(r)
        })
  
    }
    
     novaLinha(r){
        console.log("nova linha", r)
        let request = new Request("/admin/brain.php");
        request.addData( {
                "tipo": "tabela",
                "unicidade": r.id,
                "identificador": this.identificador,
                "modulo": this.modulo,
                "master": this.master
            })
 
        request.send().then((r)=>{
               this.itens[r.lista[0].id] = new ItemGrade(this, r.lista[0]);
               this.pai.appendChild(this.itens[r.lista[0].id].render())

            this.listaUm.bind(this)(r)
        }, ()=>{
            this.naoEncontrado.bind(this)();
            alert(`Erro na Tabela: ${obj.mensagem}`);
        })

    }
    
    
    
}

function naoExiste(){
    RequestRoute.quick("nown", "rotas",{ p: "erro404",rotas: "erro404"}).then((r)=>{
        document.getElementById("conteudo").innerHTML = r.html
    })
}

class CarregarPagina {
    constructor() {
        this.conteudo = document.getElementById("conteudo") ? document.getElementById("conteudo") : false;
        this.banco = new BancoDeDados("paginas", "paginas");
        this.historico = [];
        this.eventoLoad = new CustomEvent('paginaCarregada', {
            detail: { mensagem: 'Este é meu evento personalizado!' }
        });
        
        try{
            new FluxoBubble();
        }catch(e){
            
        }
        
    }
    
    barra() {
      const progressBar = document.getElementById("barraCarregamento");
        if (progressBar) {
            progressBar.classList.remove("carregando", "d-none");
            progressBar.classList.add("carregando");

            setTimeout(() => {
                progressBar.classList.remove("carregando");
                progressBar.classList.add("d-none");
            }, 600);
        } else {
            console.error("Elemento #barraCarregamento não encontrado.");
        }
    }

    async naoExiste() {

        document.getElementById("estruturaNown").style.display = "block"
        if (this.conteudo) {
            let resultado = await this.banco.umItem("erro404");
            if(resultado){
                console.log(resultado)
                this.conteudo.innerHTML = resultado.html
                return;
            }
     
            
            naoExiste();
        }
    }

    layout(num) {
        var body = document.body
        var num = !num ? 0 : num
        switch (num) {
            case 0:
                body.classList.remove("hideTop", "hideLateral")
                break;
            case 1:
                body.classList.remove("hideTop")
                body.classList.add("hideLateral")
                break;
            case 2:
                body.classList.add("hideTop", "hideLateral")
                break;
        }
    }

    ajustaLinks() {
        var links = this.conteudo.getElementsByTagName("a")
        evento(Array.from(links), "click", preventLink)
    }

    getulr(r) {


        if (!r.translate) {
    
            var urlCompleta = window.location.href;
            var dominio = window.location.protocol + "//" + window.location.host;

            var urlRestante = r.url

            if (urlRestante.charAt(0) === "/") {
                urlRestante = urlRestante.substr(1);
            }

            urlRestante = urlRestante.split("/"); // Remova 'var' nesta linha

        } else {
            urlRestante = r.translate;
        }
        console.log(urlRestante)

        if (urlRestante[0] === "a") {
            var tipo = "modulo";
            urlRestante.shift();
        } else if (urlRestante[0] === "m") {
            var tipo = "master";
            urlRestante.shift();
        } else {
            var tipo = "pagina";

        }

        if (urlRestante.length > 0) {
            // Verifique se o último valor de urlRestante é vazio
            if (urlRestante[urlRestante.length - 1] === '') {
                // Remova o último valor de urlRestante usando o método pop()
                urlRestante.pop();
            }
        }

        if (urlRestante.length > 1 && tipo == "modulo") {
            urlRestante = `${this.capitalize.bind(this)(urlRestante[0])}${this.capitalize.bind(this)(urlRestante[1])}`
        } else {
            urlRestante = this.capitalize.bind(this)(urlRestante[0])
        }


        

        urlRestante = urlRestante.split(" ")[0];

        var string = `${tipo}${urlRestante}`;
     
        return string;
    }

    chama(funcaoJS, ext = true) {
        var funcaoJS =  tratoEspaco(funcaoJS);

        if (funcaoJS) {
            var funcaoJS = funcaoJS.split("-");
            funcaoJS = funcaoJS.join("")

            if (funcaoJS in window && typeof window[funcaoJS] === 'function') {
                    const result = window[funcaoJS].call(this);
                
    
            } else {

                if (ext) {
                    var trato = funcaoJS.split(/(?=[A-Z])/);
                    if (trato.length > 2) {
                        var novaFuncao = `${trato[0]}${trato[1]}Excecao`
                        console.log(novaFuncao)
                        if (novaFuncao in window && typeof window[novaFuncao] === 'function') {
                            const result = eval(`${novaFuncao}()`);
                        }

                    }

                }

            }
        } else {
            console.log("aqui chamava a home");
            //home();
        }

    }

    capitalize(str) {
        if (str) {
            let texto = str
            if(str.includes('-')){
                let partes = str.split('-');
                texto = partes[0].charAt(0).toUpperCase() + partes[0].slice(1).toLowerCase();
                for (let i = 1; i < partes.length; i++) {
                    texto += partes[i].charAt(0).toUpperCase() + partes[i].slice(1).toLowerCase();
                }
            }else{
                texto = texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
            }
            return texto
        } else {
            return "Home";
        }

    }

    loadscript(r, funcao = false) {
       


    if(r.js){
        if(this.lastJs && this.lastJs.code){
            var codigo = this.lastJs.code;
            if(codigo === r.js && window.location.href == this.lastJs.url){
                //return; 
            }
        }
        
        if (!this.lastJs) {
            this.lastJs = {};
            
        }
            this.lastJs.code = r.js;
            this.lastJs.hash = caminho.hash();
            this.lastJs.url = window.location.href
            this.lastJs.lastUpdated = new Date();
        
        
       var src = `${dominioscript}/${r.js}`;
       nownFiles.add(src).then(() => {
        var funcaoJS = !funcao ? this.getulr.bind(this)(r) : funcao;
        setTimeout(() => {
            
           
           
            
            this.chama.bind(this)(funcaoJS)
        }, 20);
    }); 
    }
    

    }

    async go() {
        
        //start.analise.update();
       // start.analise2.update();
        
        
        if(this.formulario){
            this.formulario = null;
        }
        
        if(this.tabelo){
             this.tabelo.morre();
             this.tabelo = null;
        }
        
        this.barra.bind(this)()
        var sucesso = true;
        this.previa = false;
        
     
    
        var restricao = ["acesso", "cadastro"];
        
        if(autenticado()){
            var trato = window.location.href.split(dominio)
            if(trato.length > 1){
                var trato = trato[1].split("/")
                if(trato.length > 1){
                    if(restricao.includes(trato[1])){
                        goUrl("");
                        return;
                    }

                 
                }
              
            }
        }else{
             if(window.location.href.split(`${dominio}/a/`).length == 2 && window.location.href.split(`${dominio}/restrito`).length == 1){
                 new Restrito();
                 return;
             }
        }
        
        if(window.location.href.split("?").length > 1){
            goUrl(window.location.href.split("?")[0].split(dominio)[1]);
            return;
        }
        
        /*
        try {

            let link = window.location.href.replace(`${dominio}/`, "");
            link = link.split("#")[0];
            link = !link ? "home" : link;
            
            

            let resultado = await this.banco.umItem(link);
            if(!resultado){
                var trato = link.split("/").filter(function(item) {
                    return item.trim() !== "";
                    
                });
                
                if(trato.length > 1){
                    trato[trato.length - 1] = "*";
                }
                
                var volta = trato.join("/")
                 
                resultado =  await this.banco.umItem(volta);
              

            }
        

            if (resultado) {
                this.render(resultado, false)
                sucesso = true;
                this.previa = resultado;
            } else {
                sucesso = false;
            }
        } catch (error) {
            sucesso = false;
        }
        */
     
        let suceso = false;


        if (!sucesso) {
            loading(this.conteudo);
        }
        this.ajax();
    }

    render(r, javascripta = true) {
        
        this.setBody(r)
        limpaevento();
        caminho.set(r.caminho);

        this.layout.bind(this)(r.layout)

        if (r.html) {
             const fragment = document.createDocumentFragment();
            if(r.css){
                var style = document.createElement("STYLE")
                style.setAttribute("scoped", "")
                style.innerText = r.css
                fragment.appendChild(style)
            }
    
            var html = r.html.replace(/<img /g, '<img loading="lazy" ');
            
           
            var div = document.createElement("DIV")
            div.innerHTML = html
            
            fragment.appendChild(div);
            
            
            
        
            this.conteudo.innerHTML = ""
            this.conteudo.appendChild(fragment)
            if(document.getElementById("restritoPage")){
                new Restrito();
                return;
            }
            
            
            
            if(document.getElementById("paginaRender")){
                var render = document.getElementById("paginaRender");
                    if(render){
                     
                        nownFiles.add(`${dominioscript}/conteudo/modulos/paginas/assets/renderPage.js`).then((r)=>{
                            console.log("foi aqui")
                            var obj = render.innerText
                            render.innerHTML = "";
                            render.classList.remove("d-none")
                            var obj = JSON.parse(obj);
                            new RenderPage(obj, render, false);
                        }, (r)=>{
                            console.log("falha ao carregar arquivo");
                        })
                    }
            }
            
            new AdsControles();
            new SmartAds(); 
            this.ajustaLinks.bind(this)()
            this.loadscript.bind(this)(r)
            
            document.getElementById("estruturaNown").classList.remove("d-none")
            if(document.getElementById("estruturaNown").style.display == "none"){
                document.getElementById("estruturaNown").style.display = "block"
            }
             
            
            document.dispatchEvent(this.eventoLoad);
            
            

           
            
            this.moduloControler.bind(this)()
            document.getElementById("conteudo").scrollTop = 0;
            
            new setSEO();
        }
        
        
    }

    tabelas(){
        var t = document.getElementsByClassName("tabelaLoad")
        
        if(t.length){
            nownFiles.add(`${dominioscript}/assets/js/classes/tabela.js`).then((r)=>{
                  var i = 0;
    while(i < t.length){
        var id = t[i].id
        var filtro = t[i].dataset.filtro
             if(id && filtro){
                 new TabelaLoad(filtro, id)
                 
             }

         i++;
     }
            })
            
            
              
            
        }
        

    } 
    
    moduloControler(){
        let config = JSON.parse(pegaLocal("nown"));
        if(autenticado()){
            
            
            
   
        }
        
    
    }
    
    setBody(r){

        if(r.mapModulo){
            document.body.setAttribute("data-modulo", r.mapModulo)
            if(r.mapPage){
                document.body.setAttribute("data-page", r.mapPage)
            }else{
                document.body.removeAttribute("data-page")
            }
            
            if(r.publico){
                document.body.setAttribute("data-publico", true)
            }else{
                 document.body.removeAttribute("data-publico")
            }
        }else{
            document.body.removeAttribute("data-modulo")
            document.body.removeAttribute("data-page")
            document.body.removeAttribute("data-publico")
        }
        
 
    }

    ajax() {
 
        
        var link = window.location.href.replace(`${dominio}/`, "");
        var link = link.split("#")[0]
        
  
        
        var admin = parseInt(start.estrutura.userInfo("funcao", 2)) > 1 ? false : true

        if(start.estrutura.dataInfo("manutencao", "modo-manutencao", "modomanutencao", false) && !admin && !link.startsWith("acesso")){
            link = "manutencao"
        }
        

        var data = new FormData();
        data.append("p", link)
        data.append("rotas", link)
        this.historico.push(link)
        
        RequestRoute.quick("nown", "rotas", {p: link, rotas: link}).then((obj)=>{
           
                window.dispatchEvent(new Event("pagina-carregada"));

                if(document.getElementById("firstLoad")){
                setTimeout(()=>{
                    
                    document.getElementById("firstLoad").remove();
              
                }, 100)
                }
                
                
                try{
                    
                     if (obj.html) {
                    cripto(JSON.stringify(obj)).then(hash => {
                        if (this.previa.hash != hash) {
                            obj.hash = hash
                            this.render.bind(this)(obj)
                            
                            
                            if(obj.type){
                                switch(obj.type){
                                    case "item":
                                        var modulo = obj.url.split("/")[0]
                                        var url = `${modulo}/*`
                                        obj.url = url
                                        this.banco.novo([obj]);
                                        break;
                                    case "filho":
                                        var trato = obj.url.split("/")
                                        var url = `${trato[0]}/${trato[1]}/*`
                                        obj.url = url
                                        this.banco.novo([obj]);
                                        break;
                                    default:
                                        this.banco.novo([obj]);
                                        break;
                                        
                                }
                            }else{
                                this.banco.novo([obj]);
                            }
                        } 
                    })
                }else if(obj.estrutura){
                     this.setBody(obj)
                    
     
                    var estrutura = obj.estrutura
                    
                    var js = obj.js ? {obj: obj, pai: this} : false;
                    switch(parseInt(estrutura.tipo)){
                        case 1:
                            this.formulario = new FormEstrutura(estrutura, false, js);
                            break;
                        case 2:
                            if(obj.big){
                                    nownFiles.add(`${dominio}/assets/js/big.js`).then(()=>{
                                        
                                        document.getElementById("conteudo").innerHTML = ``
                                        new HeaderPage(estrutura)
                
                                        new  NovaTabela(estrutura.modulo, estrutura.id, estrutura.master)
                                        
                                        //new BigData(estrutura, js);
                                        
                                    })
                            }else{
                            
                            
                            if(obj.sub == 1){
         
                                 nownFiles.add(`${dominio}/assets/js/tabelo.js`).then(()=>{
                                    this.tabelo = new Tabelo(estrutura, js);
                                    this.tipoTabela = 1;
                                 })
                  
                           
                            }else{
                                estrutura.subDetalhes = obj.subDetalhes
                                this.tabelo = new Grade(estrutura, js);

                            }
                            
                            }
                           
                            break;
                        case 3:
                            this.categoria = new PageCategoria(estrutura);
                            break;
                        case 4:
                            this.categoria = new PageCategoria(estrutura);
                            break;
                        case 5:
                             nownFiles.add(`${dominio}/assets/js/calendario.js`).then(()=>{
                                 this.calendario = new PageCalendario(estrutura);
                             })
                            
                            break;
                        case 6:
                            break;
                        default:
                        console.log("tipo indefinido")
                        this.naoExiste.bind(this)()
                            break;
                    }
                   
                   document.getElementById("estruturaNown").style.display = "block"
        
                    
                }else {
                    
                    
                    this.naoExiste.bind(this)()
                }
                    
                    
                }catch(e){
                    console.log(e)
                    console.log(xhttp.responseText)
                    
                }
                
                 this.updateTags.bind(this)()
                
                
  
               
            
        })
        
        if(dominio != dominioscript){
            data.append("wildcard", dominio);
        }
        

        if(window.location.href.split("#ref=").length == 2){
            var referencia =  window.location.href.split("#ref=")[1]
             window.history.pushState(null, null, window.location.href.split("#ref=")[0]);
             new Afiliacao(referencia);
        }
        
 
    }
    
    updateTags(){
        if(window.nownAnalitcs) {
       
        gtag('config', window.idAnalitcs, {
            'page_path': window.location.href.split(dominio)[1],
            'page_title': document.title
        });
    }
    
    if(window.nownAds) {
        gtag('config', window.idAds, {
            'page_path': window.location.href.split(dominio)[1],
            'page_title': document.title
        });
    }
    
    
     
    }
    
}

function pegaHash(){
    var url = window.location.href;  // Pega a URL atual
    var trato = url.split("/");      // Divide a URL em um array por cada "/"

    trato = trato.filter(function(item) { 
        return item !== "";          // Remove os itens vazios do array
    });

    var hash = trato[trato.length - 1]; 
    if(hash.length != 32){
      
    }else{
       
    }
    return hash;
    
}

class Caminho {
    constructor(caminho) {
        this.caminho = caminho
    }

    set(caminho) {
        this.caminho = caminho;
    }
    
    url(){
        
    }
    
    add(chave){
        this.caminho = window.location.href.replaceAll(dominio, "").split("/")
        this.caminho.push(chave)
        var url = this.caminho.join("/");
        window.history.pushState(null, null, url);
    }

    hash() {
        if(!this.caminho){
            this.caminho = window.location.href.replaceAll(dominio, "").split("/")
            this.caminho = this.caminho.filter(item => item !== null && item !== undefined && item !== "");
        }
        return this.caminho[this.caminho.length - 1]
    }

    rotas() {
        return this.caminho
    }
}

const caminho = new Caminho(false);

function limpaevento() {
    var i = 0;

    while (i < ouvidoresEventos.length) {
        var o = ouvidoresEventos[i]
        o.item.removeEventListener(o.acao, o.funcao);

        i++;
    }
    ouvidoresEventos = [];
}

class Permissoes {
  constructor() {
      var push = parseInt(dataSys(["notificacoes", "push-notification", "provedor"], 0));
      switch(push){
          case 1:
              var instancia = dataSys(["notificacoes", "push-notification", "instanceid"], false);
              if(instancia){
                   this.pusher.bind(this)(instancia);
              }
             
              break;
          case 2:
              break;
      }
   

  }
  
  pusher(instancia){
       nownFiles.add('https://js.pusher.com/beams/1.0/push-notifications-cdn.js')
      .then(() => {
        
          var id = autenticado();
    
       
          
          const tokenProvider = new PusherPushNotifications.TokenProvider({
              url: `${dominioscript}/admin/push.php`,
          })
          const beamsClient = new PusherPushNotifications.Client({
              instanceId: instancia,
          })
          
          beamsClient.start()
    .then(() => {
        // Definição do ID do usuário
        return beamsClient.setUserId(`user-${id}`, tokenProvider);
    })
    .then(() => {

        // Adição do interesse no dispositivo
        return beamsClient.addDeviceInterest('nown');
    })
    .catch(e => {
        console.error('Could not authenticate with Beams:', e);
    });
          
      })
  }
}

class Lgpd {
    constructor() {
        if (document.getElementById("lgpdBar")) {
            this.timer = parseInt(document.getElementById("lgpdBar").dataset.timer);
            this.init.bind(this)();
            
            document.getElementById("lgpdBar").getElementsByClassName("aceitar")[0].addEventListener("click", this.aceitar.bind(this));
            document.getElementById("lgpdBar").getElementsByClassName("close")[0].addEventListener("click", this.fechar.bind(this));
            
            
            let request = new Request(`${dominio}/admin/politicas.php`);
            request.addData({"politica":"lgpd"})
            request.send().then((r)=>{
                document.getElementById("canvasLgpd").getElementsByClassName("offcanvas-body")[0].innerHTML = r.texto;

            })
        }
    }
    
    aceitar() {
        localStorage.setItem('lgpdAccepted', 'true');
        document.getElementById("lgpdBar").remove();
    }
    
    fechar() {
        const closeTime = new Date().getTime();
        localStorage.setItem('lgpdClosed', closeTime);
        document.getElementById("lgpdBar").remove();
    }
    
    init() {
        const lgpdAccepted = localStorage.getItem('lgpdAccepted');
        const lgpdClosed = localStorage.getItem('lgpdClosed');
        const currentTime = new Date().getTime();
        
        if (lgpdAccepted === 'true') {
            return;
        }
        
        if (lgpdClosed && (currentTime - lgpdClosed) < 24 * 60 * 60 * 1000) {
            return;
        }
        
        setTimeout(() => {
            document.getElementById("lgpdBar").classList.remove("d-none");
        }, this.timer);
    }
}

class Themer {
    constructor() {
        // Obtendo os dados de configuração
        const dados = JSON.parse(pegaLocal("nown"));
        const temas = dados?.config?.estilo || false;

        if (!temas) {
            return;
        }

        // Verificando tema light
        const light = temas?.["tema-light"]?.ativo || false;
        if (light) {
            this.light = temas["tema-light"];
        }

        // Verificando tema dark
        const dark = temas?.["tema-dark"]?.ativo || false;
        if (dark) {
            this.dark = temas["tema-dark"]; // Ajuste do nome correto da chave
        }

        // Inicializar se um dos temas estiver ativo
        if (this.dark || this.light) {
            var cor = pegaLocal("colorMode") ?? "light"
            this.init.bind(this)(cor)
        }
    }

    // Define variáveis CSS no :root
    setCSSVariable(variable, value) {
        document.documentElement.style.setProperty(variable, value);
    }

    // Alterna tema aplicando a classe no body


    // Inicializa as configurações de temas
    init(chave) {

        // Aplica variáveis CSS do tema ativo
        const activeTheme = this[chave]


        for (let c in activeTheme) {
            if (c !== "ativo") {
                this.setCSSVariable(`--nown-${c}`, activeTheme[c]);
            }
        }

    }
    
    update(chave = false){
        var chave = document.body.classList.contains("dark") ? "dark" : "light"
        this.init(chave) 
  
    }
}

function gerarAvatar(nome, tamanho) {
    // Cores disponíveis
    const cores = [
        'FF6B6B', 'FF8E53', 'FF7043', 'FFA726', 'FFCA28',
        'FFEB3B', 'CDDC39', '8BC34A', '4CAF50', '26A69A',
        '26C6DA', '29B6F6', '42A5F5', '5C6BC0', '7E57C2',
        '9C27B0', 'E91E63', 'F06292', 'BA68C8', 'AB47BC'
    ];
    
    // Extrai iniciais do nome
    const obterIniciais = (nome) => {
        if (!nome) return 'U';
        const palavras = nome.trim().split(' ');
        let iniciais = '';
        for (const palavra of palavras) {
            if (palavra.length > 0) {
                iniciais += palavra[0].toUpperCase();
                if (iniciais.length >= 2) break;
            }
        }
        return iniciais || 'U';
    };
    
    // Gera cor baseada no nome (sempre a mesma para o mesmo nome)
    const obterCor = (nome) => {
        let hash = 0;
        for (let i = 0; i < nome.length; i++) {
            hash = ((hash << 5) - hash) + nome.charCodeAt(i);
            hash = hash & hash;
        }
        const index = Math.abs(hash) % cores.length;
        return cores[index];
    };
    
    const iniciais = obterIniciais(nome);
    const cor = obterCor(nome);
    
    // Retorna URL do UI Avatars
    return `https://ui-avatars.com/api/?name=${iniciais}&size=${tamanho}&background=${cor}&color=FFFFFF&rounded=true&bold=true`;
}

class BaseSistema {
    constructor() {
        this.render = this.render.bind(this)
        this.ominiChanel = this.ominiChanel.bind(this)
        this.multiLanguage = this.multiLanguage.bind(this)
        this.chat = this.chat.bind(this)
        this.notificacao = this.notificacao.bind(this)
        this.darkMode = this.darkMode.bind(this)
        this.carrinho = this.carrinho.bind(this)
        this.menuTopo = this.menuTopo.bind(this)

        this.btn = this.btn.bind(this)
        this.limpa = this.limpa.bind(this)
        this.openMenu = this.openMenu.bind(this)

        
        this.dataInfo = this.dataInfo.bind(this)
        this.userInfo = this.userInfo.bind(this)
        
       

        
        this.topo = document.getElementById("topo") ? document.getElementById("topo") : false;
        this.direita = this.topo.getElementsByClassName("direita")[0]
        this.esquerda = this.topo.getElementsByClassName("esquerda")[0]
        this.centro = this.topo.getElementsByClassName("centro")[0]

        this.footer = document.getElementById("rodape") ? document.getElementById("rodape") : false;
        
 
        setTimeout(()=>{
             if(document.getElementById("btnBackAdmin")){
            document.getElementById("btnBackAdmin").addEventListener("click", this.backAdmin.bind(this))
         }
         
               if (this.dataInfo("geral", "voltar-ao-topo", "ativar", false)) {
                   this.content = document.getElementById("conteudo");
                   this.content.addEventListener("scroll", this.controlerRolagem.bind(this));
                   document.getElementById("backTop").addEventListener("click", ()=>{
                        this.content.scrollTo({top: 0,behavior: 'smooth'});
                   })
               }else{
                   document.getElementById("backTop").remove()
               }
            
        }, 1000)
        
        this.lgpd = new Lgpd();
        
        document.getElementById("corpo").addEventListener("click", ()=>{
            if(!event.target.classList.contains("openSmart")){
                 document.getElementById("corpo").classList.remove("show")
            }
        })

    }
    
    controlerRolagem() {
    if (this.content.scrollTop > 300) {
        document.getElementById("backTop").classList.add('show');
    } else {
       document.getElementById("backTop").classList.remove('show');
    }
}
    
    backAdmin(){
        var request = new Request("/admin/login.php")
        request.addData({"acao": "backAdmin"})
        request.send().then((e)=>{
            window.location.href = `${dominio}/a/usuarios/`
        })
    }

    sair() {
        if(this.dataInfo("geral", "geral", "sair", false)){
            Swal.fire({
                icon: "question",
                title: "Tem certeza?",
                text: "Você está prestes a ser deslogado!",
                showCancelButton: true,
                confirmButtonText: "Deslogar",
                cancelButtonText: `Cancelar`
            }).then((result) => {
                if (result.isConfirmed) {
                    deslogar();
                } 
            });
        }else{
            deslogar();
        }
    }
    
    dataInfo(setup, grupo, item, erro = false) {
        if(!this.config){
            return false;
        }
        var configs = this.config.config;
        if (configs && configs[setup] && configs[setup][grupo] && configs[setup][grupo][item]) {
            return configs[setup][grupo][item];
        }
        return erro;
    }
    
    userInfo(chave, erro){
        if(!this.config){
            return erro;
        }
        
        var configs = this.config.usuario
        if(configs && configs["usuario"] && configs["usuario"][chave]) {
            return configs["usuario"][chave];
        }
        return erro;
    }
    
    manutencao(){
        console.log("teste")
    }

    render(config) {
        
        let limpa = [];
        limpa = limpa.concat(Array.from(this.direita.getElementsByClassName("componente")));
        limpa = limpa.concat(Array.from(this.esquerda.getElementsByClassName("componente")));
        limpa = limpa.concat(Array.from(this.centro.getElementsByClassName("componente")));
       

        this.config = config
 
        
        this.logoMobile()
                   
        var manutencao = this.dataInfo("manutencao", "modo-manutencao", "manutencao", 0);
        var admin = parseInt(this.userInfo("funcao", 2)) > 1 ? false : true
        
        if(parseInt(manutencao) && !admin){
            this.manutencao.bind(this)()
        }
        
        
     
        
        if (this.config.menus.lateral.length > 0) {
            document.getElementById("lateral").classList.remove("d-none")
            document.getElementById("corpo").style = "";
  

            this.menuLateral(this.config.menus.lateral);
            var lateral = true;
        } else {
            
            
            
  
            document.getElementById("lateral").classList.add("d-none")
            document.getElementById("corpo").style = "margin-left: 0px";
            var lateral = false;
        }
        
       

        if (this.config.menus.topo.length > 0) {
            this.menuTopo(this.config.menus.topo);
        
        }

  
        if (this.config.menus.footer.length > 0) {
            this.menuFooter.bind(this)(this.config.menus.footer);
        }

        this.darkMode();
        
        if(this.dataInfo("geral", "botao-omnichannel", "ativo", false)){
            this.omnichannel.bind(this)(); 
        }


        if(!this.dataInfo("geral", "itens-topo", "carrinho", false)){
            this.carrinho.bind(this)(); 
        }
        
        if(!this.dataInfo("geral", "itens-topo", "notificacao", false)){
             this.chat();
        }
        
        if(!this.dataInfo("geral", "itens-topo", "chat", false)){
             this.notificacao();
        }
        
        if(this.dataInfo("idioma", "multi", "multi", false)){
          
            this.multiLanguage();
        }
        
        if(this.config.usuario.autorizado){
            configModulo("pagamento", "saldo", "ativo", false).then((r)=>{
            if(dataModule("7c3c1e38c951d1eddb92f19e66e74fa9")){
               this.direita.insertBefore(this.btn('bi bi-wallet', "ocWalet", "Carteira"), this.direita.firstChild); 
            }
        })
        }
        
        
        


        this.limpa(this.topo);


        if (lateral && !this.noLateral) {
            this.openMenu()
        }
        
        
        
        this.custonsBtns.bind(this)()

        if (this.config.usuario.autorizado) {
            this.logado.bind(this)();
        } else {
            this.deslogado.bind(this)();
        }
        
        
         var menuSmart = document.createElement("BUTTON")
        menuSmart.classList.add("btn", "componente", "d-none", "openSmart")
        menuSmart.innerHTML = `<i class="bi bi-list fs-30 openSmart"></i>`
        menuSmart.addEventListener("click", ()=>{
            document.getElementById("corpo").classList.toggle("show")
        })
        document.getElementById("voltaNormal").addEventListener("click", ()=>{
            document.getElementById("corpo").classList.toggle("show")
        })
        this.topo.getElementsByClassName("direita")[0].appendChild(menuSmart)
        
        limpa.forEach(element => {
            element.remove();
        });
        
        // !lateral && this.esquerda.getElementsByClassName("logomarca").length == 0
        if(false){
 
                var a = document.createElement("A")
                a.classList.add("d-none", "d-xl-block", "componente")
                a.href = dominio
                a.addEventListener("click", preventLink)
                
                var img = document.createElement("IMG")
                img.src = this.mode["light"].logomarca
                img.style.height = "40px"
                img.classList.add("logomarca", "onlyLight")
                a.appendChild(img)
                
                var img = document.createElement("IMG")
                img.src = this.mode["dark"].logomarca
                img.style.height = "40px"
                img.classList.add("logomarca", "onlyDark")
                a.appendChild(img)
                
                this.esquerda.appendChild(a)
            } 
    }
    
    omnichannel(){
        var div = document.createElement("DIV")
        div.classList.add("componente")
                
         var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "d-flex", "justify-content-center", "align-items-center", "gap-2")
        botao.innerText = "teste";
        div.appendChild(botao)
                
        this.topo.getElementsByClassName("direita")[0].appendChild(div)
                
    }
    
    custonsBtns(){
        var itens = ["um", "dois", "tres"];
        
        var i = 0;
        while(i < itens.length){
            var item = itens[i]
            

            
            let chave = `botao-personalizado-${item}`

            if(this.dataInfo("geral", chave , "ativar", false)){
                
                var div = document.createElement("DIV")
                div.classList.add("componente", "d-none", "d-xl-none")
                
           
                if(!this.dataInfo("geral", chave , "hidemomobile", false)){
                    div.classList.remove("d-none")
                
                }
                
                if(!this.dataInfo("geral", chave , "hidedesk", false)){
                    div.classList.remove("d-xl-none")
                    div.classList.add("d-xl-block")
                }
              
                
                
                var botao = document.createElement("BUTTON")
                botao.classList.add("btn", "d-flex", "justify-content-center", "align-items-center", "gap-2", "he-30")
                botao.type = "button"
                
                
                if(this.dataInfo("geral", chave, "link", false)){
                    botao.addEventListener("click", ()=>{
                        goUrl(this.dataInfo("geral", chave, "link", false));
                    })
                }
                
                
                
                
                if(this.dataInfo("geral", chave , "cor", false)){
                    botao.classList.add(this.dataInfo("geral", chave, "cor", false).trim());
                    
                    if(this.dataInfo("geral", chave, "cor", false).trim() != "none"){
                        botao.classList.add("btn-nown-style");
                    }
    
                }
            
            
            if(this.dataInfo("geral", chave, "icone", false) && this.dataInfo("geral", chave , "icone", false).trim()){
                var spanIcone = document.createElement("SPAN")
                var ic = document.createElement("I")
                ic.className = this.dataInfo("geral", chave, "icone", false)
                spanIcone.appendChild(ic)
            }
            
            
            if(this.dataInfo("geral", chave , "texto", false) && this.dataInfo("geral", chave, "texto", false).trim()){
                var spanTexto = document.createElement("SPAN")
                spanTexto.classList.add("fs-12")
                spanTexto.innerText = this.dataInfo("geral", chave, "texto", false);
                
            }
            
            
            
            switch(parseInt(this.dataInfo("geral", chave, "posicao", 1))){
                case 1:
                    if(spanIcone){
                         botao.appendChild(spanIcone)
                    }
                    
                    if(spanTexto){
                       botao.appendChild(spanTexto) 
                    }

                    break;
                case 2:
                    if(spanTexto){
                       botao.appendChild(spanTexto) 
                    }
                    
                    if(spanIcone){
                         botao.appendChild(spanIcone)
                    }

                    break;
            }
            
                
                
                
                
                
                
                if(spanTexto || spanIcone){
                    div.appendChild(botao)
                    
                    let pode = true;
                    if(this.config.usuario.autorizado){
                        pode = this.dataInfo("geral", chave, "logados", false);
                    }else{
                         pode = this.dataInfo("geral", chave, "deslogados", false);
                    }
                    
                    if(pode){
                       if(this.dataInfo("geral", chave , "esquerda", false)){
                         this.topo.getElementsByClassName("esquerda")[0].appendChild(div)
                    }else{
                         this.topo.getElementsByClassName("direita")[0].appendChild(div)
                    } 
                    }
                    
                   
                }
            }
            
            
            
            i++;
        }
        
    }
    
    carrinho(){
        if(this.config.usuario.autorizado){
           this.direita.insertBefore(this.btn('bi bi-cart-fill', "ocCart", "Carrinho"), this.direita.firstChild); 
        }
        
    }
    
    marcadorfooter(){
        var el = event.currentTarget
        this.linhafooter.style.width = `${el.offsetWidth}px`;
        this.linhafooter.style.left = `${el.offsetLeft}px`;
    }
    
    menuFooter(itens) {
        var focar = false;
       
        var row = document.createElement("DIV")
        row.classList.add("row", "m-0", "p-0", "he-70")

        var i = 0;
        while (i < itens.length) {
            
            var item = itens[i]
         
       
            var col = document.createElement("DIV")
            col.classList.add("col", "p-0")

            var a = document.createElement("A")
            var link = `${dominio}/${item.link}`
            a.href = link
            a.classList.add("btn", "w-100", "h-100", "border-0", "rounded-0", "p-1", "d-flex", "flex-column", "justify-content-center", "itemDeMenu", "menuFotMobile", "text-white")
            a.addEventListener("click", preventLink)
            a.addEventListener("click", this.marcadorfooter.bind(this))
            
            if(window.location.href == link){
                a.classList.add("ativo")
                focar = a;
            }

            var div = document.createElement("DIV")

            var divIcone = document.createElement("DIV")
            var icone = document.createElement("I")
            if(item.icone){
                 icone.className = item.icone
            }else{
                 icone.className = 'bi bi-record-circle-fill'
            }
           
            divIcone.appendChild(icone)

            var texto = document.createElement("DIV")
            texto.classList.add("fs-12", "text-center", "mt-2")
            texto.innerText = item.nome

            div.appendChild(divIcone)
            div.appendChild(texto)
            a.appendChild(div)
            col.appendChild(a)
            
            
            if(visibilidade(item.visibilidade, item.selecionados)){
                row.appendChild(col)
            }
            

            i++;
        }


        this.footer.innerHTML = "";
        
        this.marcafooter = document.createElement("DIV")
        this.marcafooter.classList.add("position-absolute", "he-5", "w-100", "bottom-0", "start-0")
        this.linhafooter = document.createElement("SPAN")
        this.linhafooter.classList.add("marcadorModile")
       
        this.marcafooter.appendChild(this.linhafooter)
        this.footer.appendChild(row)
        this.footer.appendChild(this.marcafooter)
        
        if(focar){
             var el = focar
        this.linhafooter.style.width = `${el.offsetWidth}px`;
        this.linhafooter.style.left = `${el.offsetLeft}px`;
        }



    }

    itemMenu(item, pre = true) {
        
        if(pre){
             var mob = this.itemMenu(item, false);
             document.getElementById("listaMobile").appendChild(mob)
        }
       
        
        var li = document.createElement("LI")
        li.classList.add("py-1")
        if(!pre){
            li.classList.add("list-group-item")
        }
        
        
        switch (item.tipo) {
            case 1:
                // Tipo Título
                li.classList.add("fs-10", "fw-700")
                li.innerText = item.texto
                 return li;
                break;
            case 2:
                // Tipo Link


                var a = document.createElement("A")
                a.classList.add("dropdown-item")
                a.href = `${dominio}${item.url}`
                a.addEventListener("click", preventLink)
        
     
                var span = document.createElement("SPAN")
                span.className = item.icone
               

                var texto = document.createElement("SPAN")
                texto.innerText = item.texto

                a.appendChild(span)
                a.appendChild(texto)
                li.appendChild(a)
                 return li;
                break;
            case 3:
                // Tipo Sair

                var a = document.createElement("BUTTON")
                a.classList.add("dropdown-item")
                a.addEventListener("click", this.sair.bind(this))

                var span = document.createElement("SPAN")
                span.className = item.icone
               

                var texto = document.createElement("SPAN")
                texto.innerText = item.texto

                a.appendChild(span)
                a.appendChild(texto)
                li.appendChild(a)
                 return li;
                break;
            case 4:
                if (isApp()) {


                var a = document.createElement("A")
                a.classList.add("dropdown-item")
                a.href = `${dominio}${item.url}`
                a.addEventListener("click", preventLink)
        

                var span = document.createElement("SPAN")
                span.className = item.icone


                var texto = document.createElement("SPAN")
                texto.innerText = item.texto

                a.appendChild(span)
                a.appendChild(texto)
                li.appendChild(a)
                
                 return li;
                } 
                
                break;
        }
        
       
    }

    btnNown() {

        if (document.getElementById("lateral")) {
            var footer = document.getElementById("lateral").getElementsByClassName("card-footer")[0]
            footer.innerHTML = ""
            var btn = document.createElement("A")
            btn.classList.add("w-100", "h-100", "btn", `btn-n-secundaria`, "btn-nown-style")
            btn.setAttribute("href", `${dominio}/sistema`)
            btn.addEventListener("click", preventLink)
            


            var span = document.createElement("SPAN")
            span.classList.add("texto")
            span.innerText = "NOWN"
            btn.appendChild(span)

            footer.appendChild(btn)
        }
    }
    
    logos() {
    this.mode = {
        light: {},
        dark: {},
    }
    
    var chaves = ["light", "dark"];
    var variacoes = ["logo", "logomarca", "logotipo"];
    
    for(let c in chaves){
        for(let i in variacoes){
             this.mode[chaves[c]][variacoes[i]] = dataSys(["geral", "logotipo", variacoes[i]] , false);
        }
   }
   
    for(let c in chaves){
        for(let i in variacoes){
              if(dataSys(["header", `${chaves[c]}-mode`, variacoes[i]], false)){
                  var obj = JSON.parse(dataSys(["header", `${chaves[c]}-mode`, variacoes[i]], false));
                  if(obj.length > 0){
                      this.mode[chaves[c]][variacoes[i]] = dataSys(["header", `${chaves[c]}-mode`, variacoes[i]], false);
                  }
              }
        }
   }
   
   
    for(let c in chaves){
        for(let i in variacoes){
              if(this.mode[chaves[c]][variacoes[i]]){
                  var obj = JSON.parse(this.mode[chaves[c]][variacoes[i]])
                  if(obj.length == 0){
                      this.mode[chaves[c]][variacoes[i]] = false;
                  }else{
                      this.mode[chaves[c]][variacoes[i]] = trataImagem(obj[0], "media")
                  }
              }
        }
   }
   

   
  
 
}

    logoMobile(){
        
        this.logos.bind(this)()
        
        let modes = ["light", "dark"];
        document.getElementById("logoRetratil").innerHTML = ""
        
        var a = document.createElement("A")
        a.classList.add("componente", "d-block", "d-xl-none")
        a.href = dominio
        a.addEventListener("click", preventLink)
        
        for(let c in modes){
    
            var img = document.createElement("IMG")
            img.classList.add("logocontroler", modes[c], "logo")
            img.style.height = "50px"
            img.src = this.mode[modes[c]].logo
            a.appendChild(img)
        }
        
        
        
        this.esquerda.appendChild(a)
        
        
        var a = document.createElement("A")
        a.classList.add("text-decoration-none")
        a.href = dominio
        a.addEventListener("click", preventLink)
        
        for(let c in modes){
            
            var div = document.createElement("DIV")
            div.classList.add("logoAnimada","d-flex","gap-2","justify-content-center","align-items-center")
            
            var primeiro = document.createElement("DIV")
            primeiro.classList.add("logo", "align-items-center", "justify-content-center")
            
            var img = document.createElement("IMG")
            img.setAttribute("loading","lazy")
            img.src = this.mode[modes[c]].logo
            img.classList.add("w-100")
            primeiro.appendChild(img)
            
            div.appendChild(primeiro)
            
            var segundo = document.createElement("DIV")
            segundo.classList.add("logomarca")
        
        var img2 = document.createElement("IMG")
    
        img2.src = this.mode[modes[c]].logomarca;
        img2.style = "max-width: 100%"
        img2.onload = ()=>{
            var calc = img2.width / img2.height
            if(calc < 4){
                img2.classList.add("h-100")
            }else{
                img2.classList.add("w-100")
            }
   
                 
        }
        segundo.appendChild(img2)
        
        div.appendChild(segundo)
        
        var cont = document.createElement("DIV")
        cont.appendChild(div)
        cont.classList.add("logocontroler", modes[c])
         a.appendChild(cont)
        }
        
        document.getElementById("logoRetratil").appendChild(a)
        

        if(!this.config.usuario.autorizado){
           if(!this.dataInfo("paginas", "deslogadas", "lateral", false)){ 
     
               var a = document.createElement("A")
               a.classList.add("componente", "d-xl-block", "d-none", "logomarca")
               a.href = dominio
               a.addEventListener("click", preventLink)
               
               
               for(let c in modes){
                   var div = document.createElement("DIV")
                   div.classList.add("logocontroler", modes[c])
                   
                   var img = document.createElement("IMG")
                   img.src = this.mode[modes[c]].logo
                   img.style.height = "40px"
                   img.classList.add("d-block", "d-xl-none")
                   div.appendChild(img)
                   
                   
                   var img = document.createElement("IMG")
                   img.src = this.mode[modes[c]].logomarca
                   img.style.height = "40px"
                   img.classList.add("d-none", "d-xl-block")
                   div.appendChild(img)
                   
                   a.appendChild(div)
               }
               
               this.esquerda.appendChild(a)
       
            }
        }
        else{
            if(!this.dataInfo("paginas", "logadas", "lateral", false)){
                if(this.config.menus.lateral.length == 0){
                    
                 this.semLateral.bind(this)()
                    
                
                    
                }
            }
        }
            
        this.headControl.bind(this)()
    }
    
    semLateral(){

        let modes = ["light", "dark"];
        var a = document.createElement("A")
               a.classList.add("componente", "d-xl-block", "d-none")
               a.href = dominio
               a.addEventListener("click", preventLink)
               
               
               for(let c in modes){
                   var div = document.createElement("DIV")
                   div.classList.add("logocontroler", modes[c])
                   
                   var img = document.createElement("IMG")
                   img.src = this.mode[modes[c]].logo
                   img.style.height = "40px"
                   img.classList.add("d-block", "d-xl-none")
                   div.appendChild(img)
                   
                   
                   var img = document.createElement("IMG")
                   img.src = this.mode[modes[c]].logomarca
                   img.style.height = "40px"
                   img.classList.add("d-none", "d-xl-block")
                   div.appendChild(img)
                   
                   a.appendChild(div)
               }
               
               this.esquerda.appendChild(a)
               
              
    }
    
    headControl(){
        if(this.config.usuario.autorizado){
            if(document.getElementById("topoPersonalizadoDeslogado")){
                    document.getElementById("topoPersonalizadoDeslogado").remove();
                }
            
            if(dataSys(["header","layout","customheaderlogado"], false)){
                document.body.classList.add("hideTopFixed");
                
                
                if(!document.getElementById("topoPersonalizadoLogado")){
                    var topoCustom = document.createElement("DIV")
                topoCustom.id = "topoPersonalizadoLogado"
                
                if(dataSys(["header","layout","headerlogado"], false)){
                    var pagina = dataSys(["header","layout","headerlogado"], "0");
                    var request = new Request(`${dominio}/conteudo/modulos/configuracoes/admins/api.php`)
                    request.addData({
                        acao: "getHeaders",
                        "header": pagina
                    })
                    request.send().then((r)=>{
                        if(r.html){
                            topoCustom.innerHTML = r.html
                        }
                    })
                    
                }

                
                document.getElementById("topo").insertAdjacentElement("afterend", topoCustom);
                }
                

                
                
            }else{
                document.body.classList.remove("hideTopFixed");
            }
            
        }else{
            if(document.getElementById("topoPersonalizadologado")){
                    document.getElementById("topoPersonalizadoDeslogado").remove();
                }
            
            if(dataSys(["header","layout","customheaderdeslogado"], false)){
                document.body.classList.add("hideTopFixed");
                
                
                if(!document.getElementById("topoPersonalizadoDeslogado")){
                    var topoCustom = document.createElement("DIV")
                topoCustom.id = "topoPersonalizadoDeslogado"
                
                if(dataSys(["header","layout","headerdeslogado"], false)){
                    var pagina = dataSys(["header","layout","headerdeslogado"], "0");
                    var request = new Request(`${dominio}/conteudo/modulos/configuracoes/admins/api.php`)
                    request.addData({
                        acao: "getHeaders",
                        "header": pagina
                    })
                    request.send().then((r)=>{
                        if(r.html){
                            topoCustom.innerHTML = r.html
                        }
                    })
                    
                }

                
                document.getElementById("topo").insertAdjacentElement("afterend", topoCustom);
                }
                

            }else{
                document.body.classList.remove("hideTopFixed");
            }
            
        }
    }
    
    changeModeColor(){
        if(document.getElementsByTagName("body")[0].classList.contains("light")){
            
             document.getElementsByTagName("body")[0].classList.remove("light")
                document.getElementsByTagName("body")[0].classList.add("dark")
                document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","dark")
            
            
            
        }else{
             document.getElementsByTagName("body")[0].classList.remove("dark")
                document.getElementsByTagName("body")[0].classList.add("light")
                document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","light")
        }
    }

    logado() {

        var obj = JSON.parse(pegaLocal("nown"))
        var usuario = obj.usuario.usuario

        if(obj.sub){

            if(document.getElementById("cardContaSelector")){
                document.getElementById("cardContaSelector").remove();
            }
            
            let doc = obj.sub.cnpj ? `<div class="fs-14">CNPJ: ${obj.sub.cnpj}</div>` : '';
            var card = document.createElement("DIV")
            card.id = "cardContaSelector"
            card.classList.add("card", "bg-primaria", "rounded")
            card.innerHTML = `
            <div class="card-body rounded d-flex flex-column gap-2" style="color: var(--nown-primaria-text-over); background-color: var(--nown-primaria)">
            <div class="fs-12 fw-700 m-0">Sua Empresa</div>
            <h3 class="fs-14 fw-700 m-0"><a href="${dominio}/conta" class="stretched-link text-decoration-none" style="color: var(--nown-primaria-text-over);">${obj.sub.nome}</a></h3>
            ${doc}
            </div>
            `
            card.getElementsByTagName("a")[0].addEventListener("click", preventLink)
            
            var parent = document.getElementById("lateral").getElementsByClassName("card-body")[0]
            
            parent.insertBefore(card , parent.firstChild);
        }
        
        
         var fotinha = gerarAvatar(usuario.nome, 100)
        
         this.permissoes = new Permissoes();
    
         
        if(usuario.foto && usuario.foto != "false"){
            var fotoTrato = trataImagem(usuario.foto, "mini");
            if(fotoTrato){
                fotinha = fotoTrato
            }
        }


        if (parseInt(usuario.funcao) == 0 || parseInt(usuario.funcao) == 1) {
            this.btnNown.bind(this)();
        } else {
            if (document.getElementById("lateral")) {
                var footer = document.getElementById("lateral").getElementsByClassName("card-footer")[0]
                footer.innerHTML = ""
                footer.classList.add("d-none")
            }
        }

        var div = document.createElement("DIV")
        div.classList.add("componente")
        
        var container = document.createElement("DIV")
        container.classList.add("dropdown","dropdown-menu-user")
        div.appendChild(container)
        
        
        if(this.dataInfo("geral", "botao-perfil", "customizarbotao", false)){
            var botao = document.createElement("BUTTON")
            botao.classList.add("btn", "d-flex", "justify-content-center", "gap-2",  "align-items-center")
          
            
            
            
            
            if(this.dataInfo("geral", "botao-perfil", "cor", false)){
                botao.classList.add(this.dataInfo("geral", "botao-perfil", "cor", false).trim());
                
                if(this.dataInfo("geral", "botao-perfil", "cor", false).trim() != "none"){
                   botao.classList.add("btn-nown-style");
                }
                
            }
            
            
            if(this.dataInfo("geral", "botao-perfil", "iconebtn", false) && this.dataInfo("geral", "botao-perfil", "iconebtn", false).trim()){
                var spanIcone = document.createElement("SPAN")
                var i = document.createElement("I")
                i.className = this.dataInfo("geral", "botao-perfil", "iconebtn", false)
                spanIcone.appendChild(i)
            }
            
            
            if(this.dataInfo("geral", "botao-perfil", "textobtn", false) && this.dataInfo("geral", "botao-perfil", "textobtn", false).trim()){
                var spanTexto = document.createElement("SPAN")
                spanTexto.classList.add("fs-12")
                spanTexto.innerText = this.dataInfo("geral", "botao-perfil", "textobtn", false);
                
            }
            
            
            
            switch(parseInt(this.dataInfo("geral", "botao-perfil", "posicao", 1))){
                case 1:
                    if(spanIcone){
                         botao.appendChild(spanIcone)
                    }
                    
                    if(spanTexto){
                       botao.appendChild(spanTexto) 
                    }

                    break;
                case 2:
                    if(spanTexto){
                       botao.appendChild(spanTexto) 
                    }
                    
                    if(spanIcone){
                         botao.appendChild(spanIcone)
                    }

                    break;
            }
            
            
            
            
            //textobtn
            
           // botao.innerText = "aqui"
        }else{
           var botao = document.createElement("BUTTON")
        botao.classList.add("btn-menu-user")


        
        var img = document.createElement("IMG")
        
        
  
        img.src = fotinha
        
        botao.appendChild(img) 
        }
        
        
        if(!this.dataInfo("geral", "botao-perfil", "dropdown", false)){
            botao.type="button"
            botao.setAttribute("data-bs-toggle", "dropdown")
            botao.setAttribute("aria-expanded", "false")
        }else{
            switch(parseInt(this.dataInfo("geral", "botao-perfil", "acao", 1))){
                case 1:
                    botao.addEventListener("click", ()=>{
                        goUrl("/perfil") 
                    })
                    break;
                case 2:
                    if(this.dataInfo("geral", "botao-perfil", "link", false)){
                        botao.addEventListener("click", ()=>{
                            goUrl(this.dataInfo("geral", "botao-perfil", "link", false));
                        })
                    }
                    
                    break;
            }
        }
        
        
        
        container.appendChild(botao)
        
        
        var drop = document.createElement("DIV")
        drop.classList.add("dropdown-menu","dropdown-menu-end","dropdown-nown")
        
        const infoDiv = document.createElement('div');
        infoDiv.className = 'info';
        
        const fotoDiv = document.createElement('div');
        fotoDiv.className = 'foto';
        
        const imga = document.createElement('img');
        imga.src = fotinha
        fotoDiv.appendChild(imga);
        
        infoDiv.appendChild(fotoDiv);
        
        const dadosDiv = document.createElement('div');
        dadosDiv.className = 'dados';
        
        const nomeH5 = document.createElement('h5');
        nomeH5.textContent = usuario.nome;
        dadosDiv.appendChild(nomeH5);
        
        const emailSpan = document.createElement('span');
        emailSpan.textContent = usuario.email;
        dadosDiv.appendChild(emailSpan);
        infoDiv.appendChild(dadosDiv);
        drop.appendChild(infoDiv)
        
        
        
     
          var ul = document.createElement("UL")
          ul.classList.add("menu-user-group", "d-xl-none")
          drop.appendChild(ul)
          
    
        if(this.dataInfo("geral", "light-dark-mode", "ativo", false)){
                    var li = document.createElement("LI")
        li.classList.add("py-1")
                    var corStart = parseInt(this.dataInfo("geral", "light-dark-mode", "primario", "1"))

                      
                      li.classList.add("fs-10", "fw-700")
                      
                      var a = document.createElement("BUTTON")
                      a.classList.add("dropdown-item")
                      
                      var span = document.createElement("SPAN")
                      span.className = "bi bi-brightness-high onlyDark"
                      
                      var texto = document.createElement("SPAN")
                      texto.classList.add("onlyDark")
                      texto.innerText = "Modo Claro"
                      
                      a.appendChild(span)
                      a.appendChild(texto)
                      
                     var span = document.createElement("SPAN")
                      span.className = "bi bi-moon-fill onlyLight"
                      
                      var texto = document.createElement("SPAN")
                      texto.classList.add("onlyLight")
                      texto.innerText = "Modo Escuro"
                      a.appendChild(span)
                      a.appendChild(texto)
                      
                      
                      
                      
                      a.addEventListener("click", this.changeModeColor.bind(this))
                      
                      li.appendChild(a)
                      ul.appendChild(li)
                  
              }
      
          
        if(!this.dataInfo("paginas", "logadas", "desabilitar-fixos", false) && dataModule("9019cbe4458150159d9cc2f1cd473cf1")){
            var links = [];
            links.push({tipo: 2,icone: "bi bi-person-gear",texto: "Editar Perfil",url: "/perfil"})
            links.push({tipo: 2,icone: "bi bi-incognito",texto: "Privacidade",url: "/perfil/privacidade"})
            links.push({tipo: 2,icone: "bi bi-bell-fill",texto: "Notificações",url: "/perfil/notificacoes"})
            links.push({tipo: 2,icone: "bi bi-file-earmark-lock",texto: "Segurança", "url": "/perfil/gerenciar-sessoes"})
            
            
            var ul = document.createElement("UL")
            ul.classList.add("menu-user-group")
            for(let c in links){
                var link = links[c]
                var li = this.itemMenu(link);
                if(li){
                    ul.appendChild(li)
                }
                
                
            }
            drop.appendChild(ul)

        }
        
        
        if(dataSys(["paginas","logadas","btnpersonalizado"], false) && dataSys(["paginas","logadas","btnspersonalizados"], false)){
            try{
                var obj = JSON.parse(dataSys(["paginas","logadas","btnspersonalizados"], false))
                if(obj.length > 0){
                            var ul = document.createElement("UL")
        ul.classList.add("menu-user-group")
        for(let c in obj){
                var link = {tipo: 2,icone: obj[c].i ,texto: obj[c].t, url: obj[c].l}
                var li = this.itemMenu(link);
                if(li){
                    ul.appendChild(li)
                }
                
                
            }
        drop.appendChild(ul)
        
        

                }
                
        
            
            }catch(e){
                
            }
        }
        
        
        
        
   var links = [];   
   
   
   
   if(!this.dataInfo("paginas", "logadas", "desabilitar-fixos", false) && dataModule("9eb3f68b1df55837553be41f5ffe5b20")){
       links.push({tipo: 2,icone: "bi bi-cash-coin",texto: "Meus Pagamentos", url: "/pagamento/meus-pagamentos"});
       links.push({tipo: 2,icone: "bi bi-box-seam-fill",texto: "Meu Pedidos", url: "/pagamento/meus-pedidos"});
   }
   
   
 
   
   if(!this.dataInfo("paginas", "logadas", "desabilitar-fixos", false) && dataModule("913dac1cb001c9f3f39f5f8ae8ac755d")){
       links.push({tipo: 2,icone: "bi bi-card-checklist",texto: "Minhas Assinaturas", url: "/pagamento/minhas-assinaturas"});
   }
   
   if(!this.dataInfo("paginas", "logadas", "desabilitar-fixos", false) && dataModule("b49228b8fa98840355de71ec58f2d714")){
       links.push({tipo: 2, icone: "bi bi-question-circle",texto: "Suporte", url: "/suporte"});
   }
   
    if(!this.dataInfo("paginas", "logadas", "desabilitar-fixos", false) && dataModule("47a6f6bcb1457a3123e24eada0ca981f")){
           links.push({tipo: 2,icone: "bi bi-intersect",texto: "Área do Afiliado", url: "/afiliados"});
    }
   
   if(isApp()){
       links.push({tipo: 4,icone: "bi bi-qr-code-scan" ,texto: "Login sem Senha",url: "/loginqr"});
   }
   
    

        if(links.length > 0){
             var ul = document.createElement("UL")
            ul.classList.add("menu-user-group")
            for(let c in links){
                var link = links[c]
                var li = this.itemMenu(link);
                if(li){
                    ul.appendChild(li)
                }
                
                
            }
            drop.appendChild(ul)
        }
        
        
          if(dataModule("bc5986ebbf4dde661f229fd527ad82f4")){
   
        var links = [{tipo: 2,icone: "bi bi-shop" , texto: "Área do vendedor", url: "/marketplace/loja"}];

        var ul = document.createElement("UL")
        ul.classList.add("menu-user-group")
        for(let c in links){
                var link = links[c]
                var li = this.itemMenu(link);
                if(li){
                    ul.appendChild(li)
                }
                
                
            }
        drop.appendChild(ul)
   }
   
    
        
        var links = [];
        links.push({tipo: 3,icone: "bi bi-box-arrow-right",texto: "Sair"})
        var ul = document.createElement("UL")
        ul.classList.add("menu-user-group")
        for(let c in links){
                var link = links[c]
                var li = this.itemMenu(link);
                if(li){
                    ul.appendChild(li)
                }
                
                
            }
        drop.appendChild(ul)
        
        
        container.appendChild(drop)

        if (this.topo) {
            this.btnPerfil = div;
            this.topo.getElementsByClassName("direita")[0].appendChild(div)
            
            
            if(this.dataInfo("geral", "botao-sair", "ativar", false)){
                var div = document.createElement("DIV")
                div.classList.add("componente")
   
                var botao = document.createElement("BUTTON")
                botao.type = "button"
                botao.classList.add("btn", "d-flex", "justify-content-center", "gap-2", "align-items-center")
           
                if(this.dataInfo("geral", "botao-sair", "cor", false)){
                    botao.classList.add(this.dataInfo("geral", "botao-sair", "cor", false).trim())
                    
                    if(this.dataInfo("geral", "botao-sair", "cor", false).trim() != "none"){
                        botao.classList.add("btn-nown-style");
                    }
                }
           
                
                if(this.dataInfo("geral", "botao-sair", "icone", false)){
                    var spanIcone = document.createElement("SPAN")
                    var i = document.createElement("I")
                    i.className = "bi bi-box-arrow-in-right"
                    spanIcone.appendChild(i)
                    botao.appendChild(spanIcone)
                    
                }
                
                if(this.dataInfo("geral", "botao-sair", "texto", false)){
                    var spanTexto = document.createElement("SPAN")
                    spanTexto.classList.add("fs-12")
                    spanTexto.innerText = "Sair"
                    botao.appendChild(spanTexto)
                
            }
                
                if(this.dataInfo("geral", "botao-sair", "texto", false) || this.dataInfo("geral", "botao-sair", "icone", false)){
                    botao.addEventListener("click", ()=>{
                        this.sair.bind(this)();
                    })
                    
                    
                    
                    
                    div.appendChild(botao)
                    this.topo.getElementsByClassName("direita")[0].appendChild(div)
                }
               

            }
            
            
            
        }
        
        
        
    
  


    }

    deslogado() {

        if(this.dataInfo("geral","geral","login", false)){
            
              var a = document.createElement("A")
        a.classList.add("text-decoration-none", "btn", "text-light", "d-flex", "justify-content-center", "align-items-center", "btn-n-primaria", "btn-nown-style", "fs-14", "btn-sm", "gap-2", "he-30", "componente")
        a.href = `${dominio}/acesso`;
        a.addEventListener("click", preventLink)

        var span = document.createElement("SPAN")
        var icone = document.createElement("I")
        icone.className = "bi bi-person-fill-lock fs-14"
        span.appendChild(icone)
        
            a.appendChild(span)
        var txt = this.dataInfo("geral","geral","textologin", false);
        if(txt){
            var texto = document.createElement("SPAN")
            texto.classList.add("d-none", "d-lg-block", "fs-500", "fs-14")
            texto.innerText = txt
             a.appendChild(texto)
        }



    
        


        if (this.topo) {
            this.btnLogin = a;
            this.topo.getElementsByClassName("direita")[0].appendChild(a)
        }
            
        }
        
      
      

    }

    abreMenu() {
        var btn = event.currentTarget
        if (btn.classList.contains("ativo")) {
            btn.classList.remove("ativo")
            document.body.classList.remove("ativo")
        } else {
            btn.classList.add("ativo")
            document.body.classList.add("ativo")
        }
    }

    openMenu() {
        var btn = document.createElement("BUTTON")
        btn.classList.add("btn", "he-30", "wi-30", "rounded", "d-flex", "justify-content-center", "align-items-center", "position-relative", "openMenu", "componente")
        
        if(document.body.classList.contains("ativo")){
            btn.classList.add("ativo")
        }
        
        
        btn.addEventListener("click", this.abreMenu.bind(this))

        var um = document.createElement("SPAN")
        um.classList.add("barra")
        var dois = document.createElement("SPAN")
        dois.classList.add("barra")
        var tres = document.createElement("SPAN")
        tres.classList.add("barra")

        btn.appendChild(um)
        btn.appendChild(dois)
        btn.appendChild(tres)



        this.esquerda.insertBefore(btn, this.esquerda.firstChild);
    }

    limpa(pai) {
        var elementos = pai.getElementsByClassName("bg-carregando");

        var elementosArray = Array.from(elementos);
        elementosArray.forEach(function(elemento) {
            elemento.parentNode.removeChild(elemento);
        });
    }
    
    geraIcone(classe){
        var trato =  classe.trim();
        if(!trato.startsWith("nown")){
            var spanIc = document.createElement("I")
            spanIc.style.fontSize = "18px"
            spanIc.className = trato
        }else{
            var spanIc = document.createElement("i-nown")
            var t = trato.split(" ");
            spanIc.setAttribute("icone", t[t.length - 1])
        }
        return spanIc;
    }
 
    menuTopo(items) {
        var ul = document.createElement("UL")
        ul.classList.add("nav", "justify-content-center", "d-none", "d-xl-flex", "componente")

        var i = 0;
        while (i < items.length) {
            var item = items[i]
            if(item.tipo == 1){
                
              if(visibilidade(item.visibilidade, item.selecionados)){
                    var li = document.createElement("LI")
            li.classList.add("nav-item")
            if(!item.filhos || item.filhos.length == 0){
               
            var a = document.createElement("A")
            a.classList.add("nav-link", "fw-600", "itemDeMenu")
            
            a.addEventListener("click", closeLateral);
            
            
            
            
            if(item.link.includes("http://") || item.link.includes("https://")){
                var link = item.link
                if(!item.link.includes(dominio) ){
                    a.setAttribute("target", "blank_")
                }else{
                    a.addEventListener("click", preventLink)
                }
            }else{
                var link = `${dominio}/${item.link}`;
                a.addEventListener("click", preventLink)
            }
   
            
            

            a.setAttribute("href", link)
            
            if(window.location.href == link){
                a.classList.add("ativo")
            }
       

            var span = document.createElement("SPAN")
            span.innerText = item.nome
            
            if(item.icone && item.icone.trim()){
               
                a.classList.add("d-flex", "justify-content-center", "gap-2", "align-items-center")


                spanIc = this.geraIcone(item.icone);
                
                a.appendChild(spanIc)
            }


            a.appendChild(span)
            li.appendChild(a)
            
            }else{
                
                var div = document.createElement("DIV")
                div.classList.add("dropdown")
                
                var button = document.createElement("BUTTON")
                button.classList.add("nav-link", "fw-600", "itemDeMenu", "dropdown-toggle")
                button.setAttribute("type","button")
                button.setAttribute("data-bs-toggle","dropdown")
                button.setAttribute("aria-expanded", "false")
                
                var span = document.createElement("SPAN")
                span.innerText = item.nome
                
                  if(item.icone){
                button.classList.add("d-flex", "justify-content-center", "gap-2", "align-items-center")
                var spanIc = document.createElement("SPAN")
                spanIc.style.fontSize = "18px"
                spanIc.className = item.icone
                button.appendChild(spanIc)
            }

                
                button.appendChild(span)
                
                div.appendChild(button)
                
                
                var ul2 = document.createElement("UL")
                ul2.classList.add("dropdown-menu")
                
                
           
                
                div.appendChild(ul2)
            
                var j = 0;
                while(j < item.filhos.length){
                    var rest = item.filhos[j]
                    if(rest.tipo == 1){
                          var sub = document.createElement("LI")
                    var a = document.createElement("A")
                    a.classList.add("dropdown-item")
                    a.innerText = rest.nome
                    sub.appendChild(a)
                    ul2.appendChild(sub)
                    
                    var link = rest.link ? rest.link : false;
             
                    if(rest.link && (link.includes("http://") || link.includes("https://"))){
                        if(!link.includes(dominio) ){
                            a.setAttribute("target", "blank_")
                        }
                    }
                    else{
                        link = `${dominio}/${link}`
                       a.addEventListener("click", preventLink) 
                    }
            
                    a.setAttribute("href", link)
                    }
                  
            
                    
                    
                    j++;
                    
                }
                
                
                li.appendChild(div)
                
            
                
                
            }
           
           ul.appendChild(li)
              }
                
                
          
            }
             
            i++;
        }

        this.centro.appendChild(ul)

    }

    clicaFilho(){
        
        var botao = event.currentTarget;
        var pai = botao.closest(".menu-lateral-item");
        var primeiro = pai.getElementsByClassName("itemDeMenu")[0];
        
        var link = primeiro.href
        if(link !=  window.location.href){
             if (link.includes(dominio)) {
                 
                   mobileMenuControl();
                 
                 
                 var caminho = link.replace(`${dominio}/`, "")
                 var url = `${dominio}/${caminho}`
        window.history.pushState(null, null, url);
        start.go(url);
    } else {
        window.location.href = link
    }
        }

   

        
        
      
    }
    
    menuLateral(itens) {
        
        
        let contador = 0;
        var pai = document.getElementById("menuLateral")
        pai.innerHTML = ""
        var pre = pai.getElementsByClassName("accordion-item")
        if (pre.length > 0) {
    while (pre.length > 0) {
        pai.removeChild(pre[0]);
    }
}
   
        var i = 0;
        while (i < itens.length) {
            var id = geraId();
            var item = itens[i]
         
  

            if (item.filhos && item.filhos.length > 0) {
                var divAccordionItem = document.createElement("div");
                divAccordionItem.classList.add("menu-lateral-item", "accordion-item", "border-0");

                // Criar a tag h2 com classe "accordion-header"
                var h2 = document.createElement("DIV");
                h2.classList.add("accordion-header", "border-0");

                // Criar o botão com classe "accordion-button"
                var button = document.createElement("button");
                button.classList.add("accordion-button", "p-3", "gap-2", "menuGrupo", "shadow-none", "collapsed"); 
                button.style.width = "100%"
                button.setAttribute("type", "button");
                button.setAttribute("data-bs-toggle", "collapse");
                button.setAttribute("data-bs-target", `#${id}`);
                button.setAttribute("aria-expanded", "false");
                button.addEventListener("click", this.clicaFilho.bind(this))
       


                var spanIcone = document.createElement("span");

                var icon = item.icone ? this.geraIcone(item.icone) : criarIcone("radio_button_checked");

                

                var spanTexto = document.createElement("span");
                spanTexto.classList.add("texto" , "fs-14", "fw-600");
                spanTexto.textContent = item.nome

                button.appendChild(icon);
                button.appendChild(spanTexto);

                h2.appendChild(button);

                var divCollapse = document.createElement("div");
                divCollapse.classList.add("accordion-collapse", "collapse");
                divCollapse.setAttribute("id", id);
                divCollapse.setAttribute("data-bs-parent", "#menuLateral");

                var divAccordionBody = document.createElement("div");
                divAccordionBody.classList.add("accordion-body", "py-0", "border-0", "p-0");
                divAccordionBody.style.width = "100%"


                var ul = document.createElement("ul");
                ul.classList.add("list-group", "list-group-flush", "border-0");
                
                
                var open = false;
                item.filhos.forEach(function(filho) {
                    var li = document.createElement("a");
                    li.classList.add("text-decoration-none" , "fs-14", "fw-500", "list-group-item", "border-0", "itemDeMenu", "d-flex", "justify-content-start", "gap-2", "align-items-center")
                    var link = `${dominio}/${filho.link}`
                    li.setAttribute("href", link)
                     if(window.location.href == link){
                         li.classList.add("ativo")
                         open = true;
                         
                     }
                    
                    
                    li.addEventListener("click", preventLink)
                    
                    var spanDot = document.createElement("SPAN")

                    spanDot.style = "width: 5px; height: 5px; background-color: #e0e3e9; border-radius: 50%; display:block";
                    li.appendChild(spanDot)
                    
                    var spanText = document.createElement("SPAN")
                    spanText.innerText = filho.nome
                    li.appendChild(spanText)

                    ul.appendChild(li);
                });
                
                if(open == true){
                    divCollapse.classList.add("show")
                }


                divAccordionBody.appendChild(ul);


                divCollapse.appendChild(divAccordionBody);


                divAccordionItem.appendChild(h2);
                divAccordionItem.appendChild(divCollapse);
            } else {

                var divAccordionItem = document.createElement("div");
                divAccordionItem.classList.add('menu-lateral-item');



                var h2 = document.createElement("DIV");
                h2.classList.add("accordion-header")

      
                if (item.tipo == 1) {

                    var button = document.createElement("a");
                    button.classList.add("btn", "p-3", "menuGrupo", "gap-2",  "w-100", "itemDeMenu");
                    button.setAttribute("type", "button");
                    var link = `${dominio}/${item.link}`
                    button.setAttribute("href", link)
                    button.addEventListener("click", preventLink)
                     if(window.location.href == link){
                         button.classList.add("ativo")
                     }


                    var icon = item.icone ? this.geraIcone(item.icone) : criarIcone("radio_button_checked");


                    // Criar a segunda span com classe "texto"
                    var spanTexto = document.createElement("span");
                    spanTexto.classList.add("texto",  "fs-14", "fw-600");
                    spanTexto.textContent = item.nome


                    if (icon) {
                        button.appendChild(icon);
                    }


                    // Adicionar a primeira span e a segunda span como filhos do botão

                    button.appendChild(spanTexto);

                } else {
       
                    var button = document.createElement("DIV")
                    button.classList.add("texto", "menu-lateral-title")
                    button.style.width = "250px"
                    button.innerText = item.nome
                }



                // Adicionar o botão como filho do h2
                h2.appendChild(button);
                divAccordionItem.appendChild(h2)

            }


            if(visibilidade(item.visibilidade, item.selecionados)){
                 pai.appendChild(divAccordionItem)
                 contador++;
            }
           

            i++;
        }
        
            
 
      if(contador == 0){
         document.getElementById("lateral").classList.add("d-none")
        document.getElementById("corpo").style = "margin-left: 0px";
        this.semLateral.bind(this)()
        this.noLateral = true;
  
    }else{
        this.noLateral = false;
    }
        
    var carregando = pai.getElementsByClassName("bg-carregando")
    if (carregando.length > 0) {
    while (carregando.length > 0) {
        pai.removeChild(carregando[0]);
    }
    
    
  
    
}
       


    }

    btn(icone, foco = false, titulo) {
        
        
        var btn = document.createElement("BUTTON")
        btn.title = titulo
        if(foco){
            

            btn.setAttribute("type", "button") 
            btn.setAttribute("data-nown-canvas", foco)
            btn.setAttribute("data-desktop", "right")
            btn.setAttribute("data-mobile", "bottom")
            btn.setAttribute("data-eterno", true)
            
      
            /*
            btn.setAttribute("data-bs-target", `#${foco}`)
            btn.setAttribute("aria-controls", foco);
            btn.id = `btn-${foco}`
            */
        }
        
        btn.classList.add("btn-topbar", "componente", "btn-canvaNown")
        if(this.dataInfo("geral", "itens-topo" , "personalizar", false) && this.dataInfo("geral", "itens-topo" , "bg", false)){
        btn.style = `background-color: ${this.dataInfo("geral", "itens-topo" , "bg", false)}!important;`;
        }
        
        

        var ic = document.createElement("SPAN")
        ic.className = icone
        if(this.dataInfo("geral", "itens-topo" , "personalizar", false) && this.dataInfo("geral", "itens-topo" , "cor", false)){
            ic.style = `color: ${this.dataInfo("geral", "itens-topo" , "cor", false)}!important; font-size: 13px;`;
        }
        

        var notificado = document.createElement("SPAN")
        notificado.classList.add("quantidade")
        btn.appendChild(notificado)
        
        /*
        <span class="visually-hidden">unread messages</span>
        */
        
        

        btn.appendChild(ic)
        return btn;
        
 
    }

    ominiChanel() {

    }

    multiLanguage() {
        /*
                 <div class="dropdown">
                 <button class="btn bg-success wi-35 he-35" role="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                 <ul class="dropdown-menu p-0" style="min-width: 35px">
                    <li class="wi-35"><button class="btn bg-danger wi-35 he-35 rounded-0"></button></li>
                    <li class="wi-35"><button class="btn bg-warning wi-35 he-35 rounded-0"></button></li>
                    <li class="wi-35"><button class="btn bg-info wi-35 he-35 rounded-0"></button></li>
                 </ul>
              </div>
              
              */
              
        if(!this.dataInfo("idioma", "multi", "idiomas", false)){
          return;
        }      
        
        
    
         let idioma = this.dataInfo("idioma", "geral", "idioma", "pt-Br");
         let ids = [];
         var idiomas = JSON.parse(this.dataInfo("idioma", "multi", "idiomas", '[]'));
         var i = 0;
         while(i < idiomas.length){
              ids.push(idiomas[i].code)
              i++;
          }
          
          removeLocal("idioma");
         
         
        var url = window.location.href.split(dominio)[1].split("/");
        var trato = [];
        var i = 0;
        while(i < url.length){
            if(url[i]){
                trato.push(url[i])
            }
            i++;
        }
        
        if(trato.length > 0){
             var primeiro = trato[0];
            if (ids.includes(primeiro)){
                idioma = primeiro;
                defineLocal("idioma", idioma)
            }
        }
        
        
         
          
        var button = document.createElement("BUTTON")
        button.classList.add("btn", "wi-30", "he-30", "rounded-circle", "background", "componente", "btnIdioma")
        button.style.backgroundImage = `url(${dominio}/includes/midia/bandeiras/${idioma}.svg)`;
        button.addEventListener("click", this.showLanguages.bind(this))
        this.direita.insertBefore(button  , this.direita.firstChild);

    }
    
    showLanguages(){
        if(!this.modalLanguages){
            this.idiomas = [];
            var div = document.createElement("DIV")
            div.classList.add("modal", "fade", "modal-nown")
            div.id = "modalIdiomas"
            div.setAttribute("tabindex", "-1")
            div.setAttribute("aria-labelledby", "seletorIdioma")
            div.setAttribute("aria-hidden","true")
            div.innerHTML = `
            <div class="modal-dialog modal-lg  modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
             <h1 class="modal-title fs-5" id="seletorIdioma">Selecione o Idioma</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
    
    </div>
  </div>
            
            `
            var idiomas = JSON.parse(this.dataInfo("idioma", "multi", "idiomas", false));
            
            var row = document.createElement("DIV")
            row.classList.add("row", "g-2")
            
            var idioma = this.dataInfo("idioma", "geral", "idioma", "pt-Br");
            
            /*
            var col = document.createElement("DIV")
                col.classList.add("col-12", "col-xl-3")
                
                var button = document.createElement("BUTTON")
                button.classList.add("btn", "d-flex", "justify-content-center", "align-items-center", "gap-2")
                button.dataset.code = idioma;
                
                var span = document.createElement("SPAN")
                span.classList.add("wi-30", "he-30", "rounded-circle", "background" , "d-block")
                span.style.backgroundImage = `url(${dominio}/includes/midia/bandeiras/${idioma}.svg)`;
                
                button.appendChild(span)
                button.addEventListener("click", this.selecionaIdioma.bind(this))
                
                var span = document.createElement("SPAN")
                span.innerText = idioma
                button.appendChild(span)
                col.appendChild(button)
                row.appendChild(col)
                */
                
            
            
            
            var i = 0;
            while(i < idiomas.length){
             
                var col = document.createElement("DIV")
                col.classList.add("col-12", "col-xl-3")
                
                var button = document.createElement("BUTTON")
                button.classList.add("btn", "d-flex", "justify-content-center", "align-items-center", "gap-2")
                button.dataset.code = idiomas[i].code;
                
                var span = document.createElement("SPAN")
                span.classList.add("wi-30", "he-30", "rounded-circle", "background" , "d-block")
                span.style.backgroundImage = `url(${dominio}/includes/midia/bandeiras/${idiomas[i].code}.svg)`;
                this.idiomas.push(idiomas[i].code)
                button.appendChild(span)
                button.addEventListener("click", this.selecionaIdioma.bind(this))
                
                var span = document.createElement("SPAN")
                span.innerText = idiomas[i].value
                button.appendChild(span)
                col.appendChild(button)
                row.appendChild(col)
                i++;
            }
            div.getElementsByClassName("modal-body")[0].appendChild(row)
            document.body.appendChild(div)
            
            this.modalLanguages = new bootstrap.Modal('#modalIdiomas', {
                keyboard: false
            })
        }
        
        this.modalLanguages.show();
    }
    
    selecionaIdioma(){
        this.modalLanguages.hide();
        var idioma = event.currentTarget.dataset.code
        var padrao =  this.dataInfo("idioma", "geral", "idioma", "pt-Br");
        
        if(idioma == padrao){
            removeLocal("idioma")
        }else{
            defineLocal("idioma", idioma)
        }
        
        var url = window.location.href.split(dominio)[1].split("/");
        var trato = [];
        var i = 0;
        while(i < url.length){
            if(url[i]){
                trato.push(url[i])
            }
            i++;
        }
        
        if(trato.length == 0){
            console.log("fluxo da home")
        }else{
            var primeiro = trato[0];
            if (this.idiomas.includes(primeiro)){
                trato.shift();
            }
            
        }
        
       
        
        var junta = trato.join("/")
        var url = goUrl(junta);
        
        
        
        document.getElementsByClassName("btnIdioma")[0].style.backgroundImage = `url(${dominio}/includes/midia/bandeiras/${idioma}.svg)`;
    }
    
    chat() {
        if(dataModule("aa8af3ebe14831a7cd1b6d1383a03755")){

            if(this.config.usuario.autorizado){
                this.direita.insertBefore(this.btn('bi bi-chat-square-text-fill', "ocChat", "Chat"), this.direita.firstChild);
                
            }
        }

    }

    notificacao() {
        
        if(this.dataInfo("notificacoes","configuracoes","inapp", false) && dataModule("faf0de14c0a3d0e5e5d0479a73a9be1d")){
         if(this.config.usuario.autorizado){
             this.direita.insertBefore(this.btn('bi bi-bell-fill', 'ocNotif', "Notificações"), this.direita.firstChild);
         }
            }
        
    }

    lightDarkMode() {
        var item = event.currentTarget
        document.body.classList.remove("light", "dark")
        if (item.checked) {
            document.body.setAttribute("data-bs-theme", "dark")
            document.body.classList.add("dark")
            defineLocal("colorMode", "dark");
        } else {
            document.body.classList.add("light")
            document.body.setAttribute("data-bs-theme", "light")
            defineLocal("colorMode", "light");
        }
        
    
    }

    darkMode() {
         if(!this.dataInfo("geral", "light-dark-mode", "ativo", false)){
             return;
         }
        
        
        
        if(parseInt(this.dataInfo("geral", "light-dark-mode", "layout", 1)) == 1){
        
               
               
               var label = document.createElement('label');
               label.className = "bg-white he-30 wi-60 rounded d-block position-relative p-2 border d-none d-xl-block componente m-0 colorMode";
               var span = document.createElement('span');
               span.className = "row m-0 position-absolute h-100 w-100 top-0 start-0 z-2";
               
               var moonIcon = document.createElement('div');
               moonIcon.className = "col-6 m-0 p-0 d-flex justify-content-center align-items-center";
               moonIcon.innerHTML = '<i class="bi bi-brightness-high-fill text-white fs-12"></i>';
               
               var sunIcon = document.createElement('div');
               sunIcon.className = "col-6 m-0 p-0 d-flex justify-content-center align-items-center";
               sunIcon.innerHTML = '<i class="bi bi-moon-fill text-white fs-12"></i>';
               
               span.appendChild(moonIcon);
               span.appendChild(sunIcon);
               
               var input = document.createElement('input');
               input.type = 'checkbox';
               input.className = 'd-none darkMode';
               input.addEventListener("input", this.lightDarkMode.bind(this))
               this.inputDarkModel = input
               
               var div = document.createElement('div');
               div.className = 'z-1';
               
               label.appendChild(span);
               label.appendChild(input);
               label.appendChild(div);
               
               
        if(this.dataInfo("geral", "light-dark-mode", "ativo", false)){
            this.direita.insertBefore(label, this.direita.firstChild);
        }
        
  
        
        var corStart = parseInt(this.dataInfo("geral", "light-dark-mode", "primario", "1"))
      
        
        if(corStart == 2){
            input.setAttribute("checked", "");
            document.getElementsByTagName("body")[0].classList.remove("light")
            document.getElementsByTagName("body")[0].classList.add("dark")
            document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","dark")
        }else{
            input.removeAttribute("checked");
            document.getElementsByTagName("body")[0].classList.add("light")
            document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","light")
        }
        
        

        if(this.dataInfo("geral", "light-dark-mode", "ativo", false)){
            if(pegaLocal("colorMode")){
                    document.getElementsByTagName("body")[0].classList.remove("light", "dark")
            if(pegaLocal("colorMode") == "dark"){
                 input.setAttribute("checked", "");
               
                 document.getElementsByTagName("body")[0].classList.add("dark")
                 document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","dark")
            }else{
                input.removeAttribute("checked");
                document.getElementsByTagName("body")[0].classList.add("light")
                document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","light")
            }
            }
          
            
        }
        
        }else{
            var label = document.createElement("DIV")
            label.classList.add("nown-header-ddown", "componente", "d-none","d-xl-block")
            
            
            
             var corStart = parseInt(this.dataInfo("geral", "light-dark-mode", "primario", "1"))
      
        
            var modo = "light"
            if(corStart == 2){
                modo = "dark";
            }
            
            if(pegaLocal("colorMode")){
                switch(pegaLocal("colorMode")){
                    case 'light':
                    modo = "light"
                    break;
                case 'dark':
                    modo = "dark";
                    break;
                }
            }
            
            
            if("modo" == "dark"){
                document.getElementsByTagName("body")[0].classList.remove("light")
                document.getElementsByTagName("body")[0].classList.add("dark")
                document.getElementsByTagName("body")[0].setAttribute("data-bs-theme","dark")
            }
        
        
            this.btnTopBar = document.createElement("BUTTON")
            this.btnTopBar.classList.add("btn-topbar")
            this.btnTopBar.innerHTML = `<i class="bi bi-sun-fill onlylight"></i><i class="bi bi-moon-stars onlydark text-light"></i>`
            
            var drop = document.createElement("DIV")
            drop.classList.add("nown-ddown-content")
            
            var itens = [
                {icone: "bi-sun-fill", "texto": "Claro", "target":"light"},
                {icone: "bi-moon-stars", "texto": "Dark", "target":"dark"},
                {icone: "bi-display", "texto": "Sistema", "target":"sistema"},
                ]
            
            for(let c in itens){
                var b = document.createElement("BUTTON")
                b.classList.add("nown-ddown-item")
                var ic = document.createElement("I")
                ic.classList.add("bi", itens[c].icone)
                var span = document.createElement("SPAN")
                span.innerText = itens[c].texto
                b.dataset.target = itens[c].target
                b.appendChild(ic)
                b.appendChild(span)
                b.addEventListener("click", this.colerMode.bind(this)) 
                drop.appendChild(b)
            }
            
            label.appendChild(this.btnTopBar)
            label.appendChild(drop)
            
               if(this.dataInfo("geral", "light-dark-mode", "ativo", false)){
                    this.direita.insertBefore(label, this.direita.firstChild);
                }
        }
        

    }
    
    colerMode(){
        var itens = {
                "light":{icone: "bi-sun-fill", "target":"light"},
                "dark": {icone: "bi-moon-stars","target":"dark"},
                "sistema": {icone: "bi-display", "target": parseInt(this.dataInfo("geral", "light-dark-mode", "primario", "1")) == 1 ? "light" : "dark" }
        }
        
        var selecionado = itens[event.currentTarget.dataset.target]

        
        document.getElementsByTagName("body")[0].classList.remove("light", "dark")
        document.getElementsByTagName("body")[0].classList.add(selecionado.target)
        document.getElementsByTagName("body")[0].setAttribute("data-bs-theme", selecionado.target)
        defineLocal("colorMode", selecionado.target);
        document.getElementsByClassName("body")[0].click();


    }
}

function iconeAnimado(nome) {

    var url = `${dominio}/assets/icones-animados/${nome}.json`;

    return new Promise((resolve, reject) => {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                action: 'cacheResources',
                urls: [url]
            });
            resolve(url);
        } else {
            reject('Service Worker não está disponível ou controlador não está ativo.');
        }
    });
}

function visibilidade(tipo, regra){
     var v = tipo ?? 0
     v = parseInt(v);
     
    var info = JSON.parse(pegaLocal("nown"))
    
    var me= [];
    var estado = info.usuario.autorizado ? "logados" : "deslogado";
    me.push(estado)
    if(estado == "logados"){
        me.push(info.usuario.usuario.funcao)
        
        if(info.usuario.usuario.funcao == "0"){
            me.push("1");
        }
        
        
        me.push(info.usuario.usuario.tipouser)
    }
    

     switch(v){
         case 0:
             return true;
             break;
         case 1:
              if(!Array.isArray(regra)){
                  return true;
              }
    
              var commonItems = me.filter(item => regra.includes(item));
              if (commonItems.length == 0) {
                  return false;
              } else {
                  return true;
                  
              }

             break;
         case 2:
             if(!Array.isArray(regra)){
                  return false;
              }

             var commonItems = me.filter(item => regra.includes(item));
              if (commonItems.length == 0) {
                  return true;
              } else {
                  return false;
                  
              } 
              
              
             break;
         default:
            console.log("cai no default do menu")
            break;
     }


}

function infoUser(){
    var info = JSON.parse(pegaLocal("nown"))
    if(!info.usuario.autorizado){
        return false;
    }
    return info.usuario.usuario;
}

function isApp(){
    return window.matchMedia('(display-mode: browser)').matches ? false : true;
}

class Restrito{
    constructor(){
        var url = window.location.href
        var redir = url.split(dominio)[1];
        goUrl(`restrito${redir}`);

    }
}

function dataSys(array, alt){
    var info = pegaLocal("nown")
    if(!info || array.length > 3){
        return alt;
    }
    
    
    
    try{
        var obj = JSON.parse(info)
        
        if(!obj.config){
            return alt;
        }
        
        var config = obj.config;
       
        if(config[array[0]] && config[array[0]][array[1]] && config[array[0]][array[1]][array[2]]){
            var data = config[array[0]][array[1]][array[2]];
            if(data === "true"){
                data = true;
            }
            
            if(data === "false"){
                data = false;
            }
            
            
            return data;
            
        }else{
            return alt;
        }
    }catch(e){
        return alt;
    }
    
}

function dataModule(hash = false){
    var info = pegaLocal("nown")
    if(!info && !hash){
        return false;
      
    }
    try{

         var obj = JSON.parse(info)
         if(!obj.mods){
             return false;
         }
         
         if(!obj.mods[hash]){
             return false;
         }
         
         return true;
    }catch(e){
        return false;
    }
}

class El {
    constructor(tipo) {
        this.e = document.createElement(tipo);
    }


    // Controla as Classes
    c(a) {
        var valor = a.split(" ");
        var i = 0;
        while (i < valor.length) {
            this.e.classList.add(valor[i])
            i++;
        }

        return this;


    }

    // Controla Texto Interno
    t(c) {
        this.e.innerText = c
        return this;
    }


    // Controlar Inner HTML
    inner(html) {
        this.e.innerHTML = html
        return this;
    }


    // Controla os Elementos Fihlos
    f(valor) {
        if (Array.isArray(valor)) {
            var i = 0;
            while (i < valor.length) {
                if (valor[i]) {
                    this.e.appendChild(valor[i])
                }

                i++;
            }
        } else {
            if (valor) {
                this.e.appendChild(valor)
            }
        }

        return this;

    }


    // Controla Eventos
    evento(acao, retorno, conteudo = false) {
        if (!conteudo) {
            this.e.addEventListener(acao, retorno)
        } else {
            this.e.addEventListener(acao, function() {
                retorno(conteudo)
            })
        }

        return this;

    }

    // Controla Atributos
    a(valor) {
        for (var chave in valor) {
            this.e.setAttribute(chave, valor[chave])
        }

        return this;
    }

    style(s) {
        this.e.setAttribute('style', s);
        return this;
    }

    // Retorna o Componente
    html() {
        return this.e;
    }
}

class BoxToast{
    constructor(obj){
        this.obj = obj
        
        this.tempo = 3000;
        
        
        
        var chaves = ["texto", "header", "tempo", "foto", "cor"];
        for(let c in chaves){
            this.fecha.bind(this)(chaves[c]);
        }
       
    }
    
    fecha(chave){
        if(this.obj[chave]){
            this[chave] = this.obj[chave]
        }else{
            this.obj[chave] = false;
        }
    }
    
    render(){
         var toast = document.createElement('div');
         toast.className = 'toast';
         toast.setAttribute('role', 'alert');
         toast.setAttribute('aria-live', 'assertive');
         toast.setAttribute('aria-atomic', 'true');

        if(this.header){
            var header = this.header
        }else{
            var header = "Notificação"
        }
        
        var head = document.createElement("DIV")
        head.classList.add("toast-header", "d-flex", "justify-content-between", "align-items-center")
        
        var strong = document.createElement("STRONG")
        if(this.cor){
            var cor = document.createElement("DIV")
            cor.classList.add(`bg-${this.cor}`, "rounded-circle", "he-20", "wi-20")
            var span = document.createElement("SPAN")
            span.innerText = header
            strong.classList.add("d-flex", "justify-content-start", "gap-2", "align-items-center")
            strong.appendChild(cor)
            strong.appendChild(span)
        }else{
            strong.innerText = header
        }
        
        
        var fechar = document.createElement("BUTTON")
        fechar.classList.add("btn-close")
        fechar.type = "button"
        fechar.setAttribute("data-bs-dismiss","toast")
        fechar.setAttribute("aria-label","Fechar")
        
        head.appendChild(strong)
        head.appendChild(fechar)
        toast.appendChild(head)
        
        if(this.texto){
            var body = document.createElement("DIV")
            body.classList.add("toast-body")
            
            if(this.foto){
                console.log(this.foto)
                body.classList.add("d-flex", "justify-content-start", "gap-3", "align-items-center")
                var img = document.createElement("DIV")
                img.classList.add("bg-danger", "rounded-circle", "he-30", "wi-30")
                body.appendChild(img);
                var foto = trataImagem(this.foto, "mini");
                img.style = `background-image: url(${foto});background-size: cover;background-position: center center`;
            }
            
            var span = document.createElement("SPAN")
            span.innerText =  this.texto
            span.classList.add("fs-16", "fw-500")
            body.appendChild(span)
            toast.appendChild(body)
        }


  // Adicionar Toast ao documento
  document.getElementById("toastContainer").appendChild(toast);
    document.getElementById("toastContainer").classList.add("pe-2", "pb-2")
  // Inicializar o Toast do Bootstrap
  var bsToast = new bootstrap.Toast(toast);

  // Mostrar o Toast
  bsToast.show();

  // Remover o Toast após o tempo especificado
  setTimeout(function() {
    bsToast.hide(); // Oculta o Toast após o tempo especificado
    setTimeout(function() {
      toast.remove(); // Remove o elemento do Toast
          document.getElementById("toastContainer").classList.remove("pe-2", "pb-2")
    }, 500); // Tempo para remover completamente após o Toast ser ocultado
  }, this.tempo);
    }
}

function criarToast(texto, tempo) {
  
  var toast = document.createElement('div');
  toast.className = 'toast';
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');
  toast.setAttribute('aria-atomic', 'true');

  // Adicionar estrutura do Toast ao elemento div
  toast.innerHTML = `
    <div class="toast-header">
      <strong class="me-auto">Notificação</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
    <div class="toast-body">${texto}</div>
  `;

  // Adicionar Toast ao documento
  document.getElementById("toastContainer").appendChild(toast);

  // Inicializar o Toast do Bootstrap
  var bsToast = new bootstrap.Toast(toast);

  // Mostrar o Toast
  bsToast.show();

  // Remover o Toast após o tempo especificado
  setTimeout(function() {
    bsToast.hide(); // Oculta o Toast após o tempo especificado
    setTimeout(function() {
      toast.remove(); // Remove o elemento do Toast
    }, 500); // Tempo para remover completamente após o Toast ser ocultado
  }, tempo);
}

function deslogar(){
    RequestRoute.quick("nown", "desloga").then((r)=>{
        defineLocal("token-csrf", r.token);
        defineLocal("atualizarAbas", "true");
              window.dispatchEvent(new Event("usuario-deslogado"));
            setTimeout(()=>{
                removeLocal("atualizarAbas");
            }, 1000)
            removeLocal("sessao")
            start.ajax();
            goUrl("");
      
            document.getElementById("corpo").classList.remove("show")

    })
}

function siteInfo(p = false, s = false , t = false , n = false){
    let dados = pegaLocal("nown")
    if(!dados){
        return n;
    }
    
    
    var info = JSON.parse(dados).config

    if(!info[p] || !info[p][s] || !info[p][s][t]){
        return n;
    }
    
    return info[p][s][t];
    
    
}

function preSEO(config = false){
    var container = document.getElementById("conteudo")
    if(!config){
        return;
    }
    
    if (typeof config === "string") {
        var config = JSON.parse(config);
    }
    
    var seo = document.createElement("SEO")
    
    if(config.descricao){
        seo.dataset.descricao = config.descricao
    }
    
    if(config.palavra){
         seo.dataset.palavra = config.palavra
    }
    
    if(config.titulo){
        seo.dataset.titulo = config.titulo
    }

    container.appendChild(seo)
    
    new setSEO();

}

class setSEO{
    constructor(){
        this.titulo = false;
        this.descricao = false;
        this.keyword = false;
        

        this.base.bind(this)()
        
        
         if(!this.titulo){
             var h1 = document.getElementsByTagName("h1")
             var h2 = document.getElementsByTagName("h2")
             
             if(h1.length > 0){
                 this.titulo = h1[0].innerText
             }
             
             if(!this.titulo && h2.length > 0){
                 this.titulo = h2[0].innerText
             }
         }
         
         if(!this.descricao){
             var p = document.getElementById("conteudo").getElementsByTagName("p")
             if(p.length > 0){
                 
             }
         }
         
         if(!this.keyword){
             
         }
         
         
         var obj = pegaLocal("nown");
        var sessao = pegaLocal("sessao");
        if (obj) {
            var obj = JSON.parse(obj)
        

            var titulo = "Nown 1001"
            var descricao = "Mil Projetos, Uma Solução"
            if(obj.config && obj.config.geral && obj.config.geral.geral){
                var geral = obj.config.geral.geral
                if(geral.nome){
                    titulo = geral.nome
                }
                
                if(geral.tag){
                    descricao = geral.tag
                }
 
            }
           


       
        }
         
         
         
         if(this.titulo){
             this.newtag("title", this.titulo)
         }else{
             this.newtag("title", titulo)
         }
         
         
         
    
    
    }
    
    base(){
        var tag = document.getElementsByTagName("SEO")
        
 
    if(tag.length == 1){
        var tag = tag[0]
        
        if(tag.dataset.descricao){
            this.descricao = tag.dataset.descricao;
        }
        
        if(tag.dataset.palavra){
           this.keyword = tag.dataset.palavra;
        }
        
        
         if(tag.dataset.titulo){
            this.titulo = tag.dataset.titulo;
        }
        
        tag.remove();

    }
    }
    

    newtag(tagName, atributos) {
  let tag;
  
  if (tagName === 'title') {
    // Para a tag title, apenas busca e atualiza ou cria e adiciona ao head
    tag = document.querySelector('title');
    if (!tag) {
      tag = document.createElement('title');
      document.head.appendChild(tag);
    }
    
    tag.innerText = atributos
    
    // Se atributos.value for fornecido, atualiza o conteúdo da tag title
    if (atributos.value) tag.textContent = atributos.value;
  } else if (tagName === 'meta') {
    // Para meta tags, verifica com base no atributo name
    tag = document.querySelector(`meta[name="${atributos.name}"]`);
    if (!tag) {
      tag = document.createElement('meta');
      tag.setAttribute('name', atributos.name);
      document.head.appendChild(tag);
    }
    // Se atributos.content for fornecido, atualiza o conteúdo da meta tag
    if (atributos.content) tag.setAttribute('content', atributos.content);
  }

  return tag;
}
}

class Afiliacao{
    constructor(code = false){
        if(!code){
            return;
        }
        
        setTimeout(()=>{
            if(!dataModule("47a6f6bcb1457a3123e24eada0ca981f")){
            return;
        }
        
        this.code = code;
        this.init.bind(this)();
        }, 3000)
    }
    
    init(){
        let request = new Request(`${dominio}/conteudo/modulos/afiliados/admins/api.php`);
        request.addData(
            { 
                acao: "clique",
                url: window.location.href,
                code: this.code
            }
            )
        request.send().then((r)=>{
            console.log(r)
            if(r.acao && r.acao == "afiliar"){
                var id = r.afiliado
                defineLocal("afiliar", id);
            }
        }, (r)=>{
            console.log(r)
        })
    }
}