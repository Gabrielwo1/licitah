loadjs=function(){var h=function(){},o={},c={},f={};function u(e,n){if(e){var t=f[e];if(c[e]=n,t)for(;t.length;)t[0](e,n),t.splice(0,1)}}function l(e,n){e.call&&(e={success:e}),n.length?(e.error||h)(n):(e.success||h)(e)}function p(t,r,i,s){var o,e,u,n=document,c=i.async,f=(i.numRetries||0)+1,l=i.before||h,a=t.replace(/[\?|#].*$/,""),d=t.replace(/^(css|img|module|nomodule)!/,"");if(s=s||0,/(^css!|\.css$)/.test(a))(u=n.createElement("link")).rel="stylesheet",u.href=d,(o="hideFocus"in u)&&u.relList&&(o=0,u.rel="preload",u.as="style");else if(/(^img!|\.(png|gif|jpg|svg|webp)$)/.test(a))(u=n.createElement("img")).src=d;else if((u=n.createElement("script")).src=d,u.async=void 0===c||c,e="noModule"in u,/^module!/.test(a)){if(!e)return r(t,"l");u.type="module"}else if(/^nomodule!/.test(a)&&e)return r(t,"l");!(u.onload=u.onerror=u.onbeforeload=function(e){var n=e.type[0];if(o)try{u.sheet.cssText.length||(n="e")}catch(e){18!=e.code&&(n="e")}if("e"==n){if((s+=1)<f)return p(t,r,i,s)}else if("preload"==u.rel&&"style"==u.as)return u.rel="stylesheet";r(t,n,e.defaultPrevented)})!==l(t,u)&&n.head.appendChild(u)}function t(e,n,t){var r,i;if(n&&n.trim&&(r=n),i=(r?t:n)||{},r){if(r in o)throw"LoadJS";o[r]=!0}function s(n,t){!function(e,r,n){var t,i,s=(e=e.push?e:[e]).length,o=s,u=[];for(t=function(e,n,t){if("e"==n&&u.push(e),"b"==n){if(!t)return;u.push(e)}--s||r(u)},i=0;i<o;i++)p(e[i],t,n)}(e,function(e){l(i,e),n&&l({success:n,error:t},e),u(r,e)},i)}if(i.returnPromise)return new Promise(s);s()}return t.ready=function(e,n){return function(e,t){e=e.push?e:[e];var n,r,i,s=[],o=e.length,u=o;for(n=function(e,n){n.length&&s.push(e),--u||t(s)};o--;)r=e[o],(i=c[r])?n(r,i):(f[r]=f[r]||[]).push(n)}(e,function(e){l(n,e)}),t},t.done=function(e){u(e,[])},t.reset=function(){o={},c={},f={}},t.isDefined=function(e){return e in o},t}();

function loadCSSResource(url) {
    return new Promise((resolve, reject) => {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        let rand = url.split("aplicativo").length == 2 ? 1 : Math.floor(Math.random() * 9999999999);
   
        link.href = `${url}?v=${rand}`;
        link.media = 'print'; 

        link.onload = () => {
            link.media = 'all';
            resolve();
             if ('serviceWorker' in navigator && navigator.serviceWorker.controller && url.split("aplicativo").length == 2) {
                navigator.serviceWorker.controller.postMessage({
                    action: 'cacheResources',
                    urls: [link.href]
                });
            }
            
        };

        link.onerror = reject;

        document.head.appendChild(link);
    });
}

function configModulo(modulo, arquivo = false, chave = false, erro = false) {
    return new Promise((resolve, reject) => {
        if(!arquivo || !chave || !modulo){
            reject("Não foram enviados os dados necessários"); 
        }
      
        let request = new Request(`${dominioscript}/admin/apiModulo.php`);
        request.addData({
            "acao": "infos", 
            "modulo": modulo,
            "arquivo": arquivo,
            "chave": chave,
            "erro": erro
        });
        
        request.send().then(response => {
            // resolve(response.dado); 
            
            // Felipe fez uma dapatacao
            if(response.dado){
              
                resolve(response.dado); 
            }else{
                resolve(erro);
            }
            
        }).catch(error => {
            console.log(error)
            reject(erro);
        });
    });
}

function cripto(jsonString) {
    return new Promise((resolve, reject) => {
        try {
            const encoder = new TextEncoder();
            const data = encoder.encode(jsonString);

            crypto.subtle.digest('SHA-256', data)
                .then(hashBuffer => {
                    const hashArray = Array.from(new Uint8Array(hashBuffer));
                    const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');
                    resolve(hashHex);
                })
                .catch(error => reject('Erro ao calcular o hash: ' + error));
        } catch (error) {
            reject('Erro ao converter objeto para JSON: ' + error);
        }
    });
}

function loadJSResource(url) {
    return new Promise((resolve, reject) => {
        var script = document.createElement('script');
        let rand = url.split("aplicativo").length == 2 ? 1 : Math.floor(Math.random() * 9999999999);
        script.src = `${url}?v=${rand}`;
        script.onload = () => {
            resolve(`Script loaded: ${url}`);

            if ('serviceWorker' in navigator && navigator.serviceWorker.controller && document.getElementById("pwaScript") && url.split("aplicativo").length == 2) {
                navigator.serviceWorker.controller.postMessage({
                    action: 'cacheResources',
                    urls: [url]
                });
            }
        };
        script.onerror = () => reject(new Error(`Script load error: ${url}`));
        
        document.body.appendChild(script);
            

    });
}

function ls(url, callback = false) {
    if (url) {
        let src = `${url}`

        var script = document.getElementsByTagName("script")
        var i = 0;
        var have = false;

        while (i < script.length) {
            if (script[i].src.split("?")[0] == src) {
                have = true;
            }
            i++;
        }


        if (!have) {
            var script = document.createElement("SCRIPT")
            script.setAttribute("src", `${src}?v=${geraId()}`)

            document.getElementsByTagName("head")[0].appendChild(script)
            script.onload = () => {
     
                if (callback) {
                    callback();
                }

            }
        } else {
            if (callback) {
                callback();
            }
        }

    }

}

function lcss(url) {
    return new Promise((resolve, reject) => {
        if (url) {
            const src = `${url}`;

            const links = document.getElementsByTagName("link");
            let i = 0;
            let have = false;

            while (i < links.length) {
                if (links[i].href.split("?")[0] === src) {
                    have = true;
                    break;
                }
                i++;
            }

            if (!have) {
                const link = document.createElement("link");
                link.setAttribute("href", `${src}?v=${geraId()}`);
                link.setAttribute("rel", "stylesheet");

                link.onload = () => {
                    resolve('CSS loaded');
                };

                link.onerror = () => {
                    reject(new Error('Failed to load CSS'));
                };

                document.getElementsByTagName("head")[0].appendChild(link);
            } else {
                resolve('CSS already loaded');
            }
        } else {
            reject(new Error('No URL provided'));
        }
    });
}

function loadResources(url) {
  return new Promise((resolve, reject) => {

    const extension = url.split('.').pop().toLowerCase();
    const isJS = extension === 'js';
    const isCSS = extension === 'css';

    if (!(isJS || isCSS)) {
      
      reject(new Error('Tipo de arquivo não suportado.'));
      return;
    }

    const scripts = isJS ? document.getElementsByTagName("script") : document.getElementsByTagName("link");
    let have = null;

    for (let script of scripts) {
      const arquivo = isJS ? script.src : script.href;
      const trato = arquivo.split("?");
      if (trato[0] == url) {
        have = script;
        break;
      }
    }

    if (have) {
      resolve(have);
    } else {
      const element = document.createElement(isJS ? 'script' : 'link');
      element[isJS ? 'src' : 'href'] = `${url}?v=${geraId()}`;

      element.onload = () => resolve(element);
      element.onerror = () => reject(new Error(`Erro ao carregar o recurso: ${url}`));

      if (isJS) {
        element.async = true;
        element.defer = true;
      } else {
        element.rel = 'stylesheet';
      }

      document.head.appendChild(element);
    }
  });
}

function importa(url) { 
  return new Promise((resolve, reject) => {
    import(url)
      .then(module => {
        resolve(module);
      })
      .catch(error => {
        reject(error);
      });
  });
}

function packLoad(array) {
    const promises = array.map(url => {
        if (url.endsWith('.css')) {
            return loadCSSResource(url); 
        } else if (url.endsWith('.js')) {
            return loadJSResource(url);
        }
        return Promise.resolve(); 
    });

    
    return Promise.all(promises)
        .then(() => {})
        .catch(error => {
            console.log("Erros nos scripts", error);
            throw error; 
        });
}

function pegaLocal(index) {
    if (typeof localStorage !== "undefined") {
        const data = localStorage.getItem(index);

        if (data && data !== undefined) {
            return data;
        }
    }

    return false;
}

function defineLocal(index, value) {
    if (typeof localStorage !== "undefined") {
        localStorage.setItem(index, value);
    }
}

function removeLocal(index) {
    if (typeof localStorage !== "undefined") {
        localStorage.removeItem(index);
    }
}

class FileLoader {
  constructor() {
    this.files = {};

    this.cacheavel = true;
    this.cache = false ? 1 : Math.floor(Math.random() * 9999999999);
  }
  
  load(item) {
    return new Promise((resolve, reject) => {
        cripto(item).then((r)=>{
            if(this.files[r]){
                resolve()
                return;
            }else{
                if(!this.cacheavel){
                    var end = ``;
                }else{
                     var end = `?v=${this.cache}`;
                }
                    

                loadjs(`${item}${end}` , ()=> {
                    this.files[r] = item;
                    resolve()
                }, (r)=>{
                    console.log(r)
                });
            }
        })
    });
}


  add(url = false, cacheavel = true) {
    this.cacheavel = cacheavel;
    return new Promise((resolve, reject) => {
      if (Array.isArray(url)) {
        const promises = url.map(item => this.load(item));
        Promise.all(promises)
          .then(results => resolve(results))
          .catch(error => reject(error));
      } else if (typeof url === 'string' && url) {
        this.load(url)
          .then(result => resolve(result))
          .catch(error => reject(error));
      } else {
        reject("URL inválida");
      }
    });
  }
}

var nownFiles = new FileLoader();

function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

class Request {
    constructor(url) {
        
   
        if (url.startsWith("https://") || url.startsWith("http://")) {
            this.url = url;
        } else {
            this.url = url.startsWith("/")
                ? `${dominioscript}${url}`
                : `${dominioscript}/${url}`;
        }

        this.data = new FormData();
        this.console = true;
    }

    addData(obj) {
        for (let key in obj) {
            this.data.append(key, obj[key]);
        }
    }

    getCsrfToken() {
        return pegaLocal("token-csrf");
    }

    async html() {
        if (!navigator.onLine) {
            throw new Error("Sem Internet");
        }

        try {
            var token = this.getCsrfToken();
       
            this.data.append("X-CSRF-Token", token);
            const response = await fetch(this.url, {
                method: "POST",
                headers: {
                    'X-CSRF-Token': token
                },
                body: this.data
            });

            return await response.text();
        } catch (e) {
            if (this.console) {
      
                console.log(e);
            }
            throw e;
        }
    }

    async send() {
        if (!navigator.onLine) {
            throw new Error("Sem Internet");
        }

        try {
            var token = this.getCsrfToken();
            this.data.append("csrf_token", token);
 
            const response = await fetch(this.url, {
                method: "POST",
                headers: {
                    'X-CSRF-Token': token
                },
                body: this.data
            });

            const json = await response.json();

            if (json.sucesso) {
                return json;
            } else {
                throw json;
            }
        } catch (e) {
            if (this.console) {
                console.log(this.url);
                console.log(e);
            }
            throw e;
        }
    }
}

function tokenSeguranca(){
     return pegaLocal("token-csrf")
}



class RequestRoute {
    constructor(mod = false, cam = false) {
        this.mod = mod;
        this.cam = cam;
        this.data = new FormData();
        this.console = true;
        
        // Cache do token CSRF
        this._csrfToken = null;
        
        // Cache do status online
        this._isOnline = navigator.onLine;
        
        // Controller para cancelar requisições
        this.controller = null;
        
        window.addEventListener("usuario-logado", this.changeLogin.bind(this))
        window.addEventListener("usuario-deslogado", this.changeLogin.bind(this))
    }
    
    changeLogin(){
        var token = pegaLocal("token-csrf")
        console.log("novo token", token)
        RequestRoute.setCSRFToken(token);
    }

    static getCSRFToken() {
        if (!RequestRoute._cachedToken) {
            RequestRoute._cachedToken = localStorage.getItem('token-csrf') || null;
        }
        return RequestRoute._cachedToken;
    }

    static setCSRFToken(token) {
        console.log("novo token definido");
        localStorage.setItem('token-csrf', token);
        RequestRoute._cachedToken = token;
    }

    addData(data) {
        if (!data) return this;

        if (data instanceof FormData) {
            for (const [key, value] of data.entries()) {
                this.data.append(key, value);
            }
            return this;
        }

        const entries = Object.entries(data);
        for (let i = 0; i < entries.length; i++) {
            const [key, value] = entries[i];
            this.data.append(key, value);
        }
        
   
        return this; // Permite chaining
    }

    _createRequest(url, timeout = 30000) {
        
        if (this.controller) {
            this.controller.abort();
        }

        this.controller = new AbortController();
        
        const csrfToken = RequestRoute.getCSRFToken();
        if (csrfToken && !this.data.has('csrf_token')) {
            this.data.append('csrf_token', csrfToken);
        }
    
        return fetch(url, {
            method: 'POST',
            body: this.data,
            signal: this.controller.signal,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    _validateRequest() {
        if (!this._isOnline) {
            throw new Error("Sem Internet");
        }

        if (!this.mod || !this.cam) {
            throw new Error("Não foram enviados todos os parâmetros necessários");
        }
    }

    async html(url, timeout = 30000) {
        try {
            const response = await this._createRequest(url, timeout);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            return await response.text();
            
        } catch (error) {
            if (error.name === 'AbortError') {
                throw new Error("Requisição cancelada");
            }
            
            if (this.console) {
                console.error('RequestRoute HTML Error:', error);
            }
            throw error;
        }
    }

    async send(timeout = 30000) {
        try {
            this._validateRequest();

            this.data.append("mod", this.mod);
            this.data.append("cam", this.cam);

            const response = await this._createRequest(`${window.location.origin}/request`, timeout);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType?.includes('application/json')) {
                throw new Error("Resposta não é JSON válido");
            }

            const data = await response.json();

            if (data.sucesso) {
                return data;
            } else {
                // Rejeita com o objeto de erro
                const error = new Error(data.mensagem || "Erro na requisição");
                error.data = data;
                throw error;
            }

        } catch (error) {
            if (error.name === 'AbortError') {
                throw new Error("Requisição cancelada");
            }

            if (this.console) {
                console.error('RequestRoute Send Error:', error);
            }
            throw error;
        }
    }

    cancel() {
        if (this.controller) {
            this.controller.abort();
            this.controller = null;
        }
        return this;
    }

    clear() {
        this.data = new FormData();
        return this;
    }

    async sendJSON(data = {}) {
        this.addData(data);
        return this.send();
    }

    static async quick(mod, cam, data = {}) {
        const request = new RequestRoute(mod, cam);
        request.addData(data);
        return request.send();
    }

    static monitorConnection() {
        window.addEventListener('online', () => {
            RequestRoute.prototype._isOnline = true;
        });
        
        window.addEventListener('offline', () => {
            RequestRoute.prototype._isOnline = false;
        });
    }

    static config(options = {}) {
        if (options.console !== undefined) {
            RequestRoute.prototype.console = options.console;
        }
        
        if (options.domain) {
            window.dominio = options.domain;
        }
    }
}

class RequestRote extends RequestRoute{
    constructor(mod = false, cam = false){
        super(mod, cam);
    }
}


function closeLateral(){
     if(document.body.classList.contains("ativo")){
             document.body.classList.remove("ativo")
        if(document.getElementsByClassName("openMenu").length > 0){
            document.getElementsByClassName("openMenu")[0].classList.remove("ativo")
        }
            
        }
}

class Idle {
    constructor(timeoutInMs) {
        this.timeout = timeoutInMs;
        this.timeoutID = null;
        this.isInactive = false; // Para rastrear o estado de inatividade
        
        this.setupListeners();
        this.resetTimer();
    }

    resetTimer() {
        if (this.timeoutID) {
            clearTimeout(this.timeoutID);
        }
        
        if (this.isInactive) {
            this.onActive();
        }
        
        this.isInactive = false;
        
        this.timeoutID = setTimeout(() => {
            this.onInactive();
        }, this.timeout);
    }

    setupListeners() {
        ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, () => this.resetTimer());
        });
    }

    onInactive() {
        console.log(`Usuário está inativo por ${this.timeout / 1000} segundos.`);
        this.isInactive = true;
    }

    onActive() {
        if(infoUser() && pegaLocal("sessao")){
             var sessao = pegaLocal("sessao");
             start.tentaLogin(sessao, true);
        }
    }
}

class Start {
    constructor() {
        
         let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
         document.querySelector('meta[name="csrf-token"]').remove();
         defineLocal("token-csrf", token)

        this.peerId = false;
        this.peer = false;
      
        this.noSpa = false;
        this.noCache = false;
        this.noIndex = false;
        
        if(navigator.onLine){
            
        RequestRoute.quick("nown", "base",  {"wildcard": dominio}).then((r)=>{
          
            defineLocal("htmlBase", r.html.replace(/[\n\r\s]+/g, " "))
            defineLocal("cssBase", r.css)
            this.estruturaInicial.bind(this)(r.html, r.css);
        }, (r)=>{
            console.log(r)
        })
        
        
        
        }else{
            var html = pegaLocal("htmlBase");
            if(html){
                this.estruturaInicial.bind(this)(html, pegaLocal("cssBase"));
            }
        }
        
        this.controleSessao.bind(this)();

    }
    
    estruturaInicial(html, css = false){
          var fragment = document.createDocumentFragment()
            var div = document.createElement("DIV")
     
            div.style.display = "none"
            div.id = "estruturaNown"
            div.innerHTML = html
            fragment.appendChild(div)
            document.body.appendChild(fragment)
            this.base.bind(this)(css)
            
    }
    
    base(css){
        window.addEventListener('online', this.on.bind(this));
        window.addEventListener('offline', this.off.bind(this));

        window.addEventListener('popstate', this.voltar.bind(this));

        let cacheaveis = [
            `${dominioscript}/assets/aplicativo/jquery/mine.js`, 
            `${dominioscript}/assets/aplicativo/bootstrap/min.css`,
            `${dominioscript}/assets/css/botstrapFortram.css`,
            `${dominioscript}/assets/css/coresFortram.css`,
            `${dominioscript}/assets/aplicativo/dexie/min.js`,
            `${dominioscript}/assets/aplicativo/animate/min.css`,
            `https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js`,
            `https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css`,
            `${dominioscript}/assets/aplicativo/izitoast/index.css`,
            `${dominioscript}/assets/aplicativo/izitoast/index.js`,
            `${dominioscript}/assets/aplicativo/sweetalert2/min.js`,
            `${dominioscript}/assets/aplicativo/hammer/index.js`,
            `${dominioscript}/assets/aplicativo/moment/js.js`,
            `${dominioscript}/assets/aplicativo/list/list.js`,
            `${dominioscript}/assets/aplicativo/tippyjs/popper.min.js`,
            `${dominioscript}/assets/aplicativo/tippyjs/tippy-bundle.umd.min.js`,
            "https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css",
            "https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js",
            `${dominioscript}/assets/aplicativo/splide/index.js`,
            `${dominioscript}/assets/aplicativo/splide/index.css`,
            `${dominioscript}/assets/js/developers.js`,
           
            ];
            
        
        let recursos = [
            `${dominioscript}/assets/css/index.css`,
            `${dominioscript}/assets/css/indexNew.css`,
            `${dominioscript}/assets/js/nown.js`,
            `${dominioscript}/assets/js/nowncanvas.js`,
            `${dominioscript}/assets/js/desenvolvimento.js`,
            `${dominioscript}/assets/css/md.css`,
            `${dominioscript}/assets/js/procurar.js`,
             `${dominioscript}/assets/js/websockets.js`
            ];
            
            
        if(css){
           recursos.push(`${dominioscript}/${css}`,) 
        }
        
        nownFiles.add(cacheaveis, false).then(()=>{
            
            nownFiles.add(recursos).then(()=>{
                 this.inicia.bind(this)()
            })
            
           
        })

    }
    
    refreshControl(){
    var hammertime = new Hammer(document.getElementById("topo"));
    var screenHeight = window.innerHeight;
    var maxDivHeight = (window.innerHeight / 5) * 2;
    var threshold = 300;
    var spinnerScaleMax = 1; // Escala máxima do spinner
    var lastDistance = 0; // Guardar a última distância registrada
    
    var testededo = document.querySelector('#testededo');
    var spinerDedo = document.querySelector('#spinerDedo');

    hammertime.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });

    hammertime.on('pan', function(e) {
        closeLateral();
       
        if (e.direction === Hammer.DIRECTION_DOWN || e.direction === Hammer.DIRECTION_UP) {
            testededo.style.transition = 'none';
            spinerDedo.style.transition = 'none';
        
            var divHeight = Math.min((e.distance / screenHeight) * 2 * maxDivHeight, maxDivHeight);
            var spinnerScale = Math.min((e.distance / (screenHeight * 2 / 5)), spinnerScaleMax);
            
            // Ajusta a altura da div e a rotação do spinner com base na distância
            testededo.style.height = divHeight + 'px';
            
            var calc = parseInt((360 / 300) * e.distance);
            spinerDedo.style.transform = `rotate(${calc}deg)`;
            
            spinerDedo.querySelector('.color-spiner').style.opacity = (e.deltaY < 99 ? '0.'+e.deltaY : 1)
            spinerDedo.querySelector('.color-spiner').style.transition = `none`
            spinerDedo.querySelector('.color-spiner').style.transform = `scale(${(e.deltaY < 99 ? '0.'+e.deltaY : 1)})`
            
            lastDistance = e.distance; // Atualiza a última distância
        }
        
        if (e.direction === Hammer.DIRECTION_DOWN && e.distance > threshold) {
            hammertime.off('pan');
            navigator.vibrate(50);
            spinerDedo.classList.add('finito')
            spinerDedo.style.transform = `rotate(0deg)`;
            
            setTimeout(() => {
                window.location.href = window.location.href; // Redireciona ou faz outra ação desejada
            }, 700)

        }
    });

    hammertime.on('panend', function(e) {
        if(e.distance < threshold) {
            testededo.style.transition = 'height .4s ease';
            testededo.style.height = '0px';
            spinerDedo.style.transition = 'all .4s ease';
            spinerDedo.style.transform = 'rotate(0deg)'; // Reseta a rotação do spinner
            spinerDedo.querySelector('.color-spiner').style.transform = `scale(0.2)`;
            spinerDedo.querySelector('.color-spiner').style.opacity = 0;
            spinerDedo.querySelector('.color-spiner').style.transition = `all .4s ease`;
        }
    });
}

    voltar(){
        goUrl(window.location.href.split(`${dominio}/`)[1])

    }
    
    inicia(){
        if(isMobile()){
           this.refreshControl() 
        }
        
        this.idle = new Idle(180000);

        
        this.estrutura = new BaseSistema();
        this.conteudo = new CarregarPagina();
       

        this.conteudo.go();

        this.ajax = this.ajax.bind(this)
        this.pegaInfos = this.pegaInfos.bind(this)
        this.render = this.render.bind(this)
        

        this.controlerScripts = 0;

        this.pegaInfos();
    }
    
    loadMaterialSymbolsOutlined() {
    // Verifica se o evento de carregamento da página já ocorreu
    if (document.readyState === 'complete') {
        // Carrega a fonte imediatamente se a página já foi carregada
        injectFont();
    } else {
        // Adiciona um ouvinte de eventos para carregar a fonte uma vez que a página esteja carregada
        window.addEventListener('load', injectFont);
    }

    function injectFont() {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200';
        document.head.appendChild(link);
    }
}

    manutencao() {

    }
     
    go(url){
        if(this.noSpa){
            window.location.href = url;
            return;
        }
        
        
        
        if(document.getElementsByClassName("marcadorModile").length == 1){
            document.getElementsByClassName("marcadorModile")[0].style.width = "0px";
        }
        
        this.conteudo.go();
        
        var elementosMenu = document.querySelectorAll(".itemDeMenu");

  // Remove a classe "ativo" de todos os elementos com a classe "itemDeMenu"
  elementosMenu.forEach(function(elemento) {
    elemento.classList.remove("ativo");
  });

  // Percorre os elementos novamente para encontrar o que corresponde à URL passada
  elementosMenu.forEach(function(elemento) {
    if (elemento.getAttribute("href") === url) {
      elemento.classList.add("ativo");
    }
  });
    }

    on() {
        iziToast.success({
            icon: "bi bi-wifi",
    title: 'Conectado',
    message: 'Você tem acesso novamente a internet.'
});
    }

    off() {
          iziToast.error({
            icon: "bi bi-wifi-off",
    title: 'Atenção',
    message: 'Você não está conectado na internet.'
});
    }
    
    tentaLogin(sessao, simple = false){
        RequestRoute.quick("nown", "login", {acao: "autoLogin", "sessao": sessao}).then((obj)=>{
             if(!simple){
                 this.ajax();
                goUrl(window.location.href.split(dominio)[1]);
             }

        }, (obj)=>{
              removeLocal("sessao");
                //this.render.bind(this)();
                this.ajax();
                return;
        })
     
       
    }
    
    criarFavicon(url) {
  // Verificar se a tag link com rel="icon" já existe
  var faviconTag = document.querySelector('link[rel="icon"]');

  if (!faviconTag) {
    // Se não existir, criar a tag
    faviconTag = document.createElement('link');
    faviconTag.rel = "icon";
    faviconTag.href = url;

    // Adicionar a nova tag ao head do documento
    var head = document.head || document.getElementsByTagName('head')[0];
    head.appendChild(faviconTag);
  } else {
    // Se a tag já existir, apenas atualizar o href
    faviconTag.href = url;
  }
}

    render() {
   
        var obj = pegaLocal("nown");
        var sessao = pegaLocal("sessao");
        if (obj) {
            var obj = JSON.parse(obj)
            
            if(obj.widgets.length > 0){
                var widgs = [];
                var i = 0;
                while(i < obj.widgets.length){
                    widgs.push(`${dominioscript}/conteudo/modulos/${obj.widgets[i]}/assets/widgets.js`)
                    i++;
                }
                
                nownFiles.add(widgs, false).then((r)=>{
                
                })
               
            }

            if(!autenticado() && sessao){
                this.tentaLogin.bind(this)(sessao)
                return;
            }else{
                
            }
            
            
            
            
            
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
            var item = {
                titulo : titulo,
                descricao : descricao
            }
            
            preSEO(item);

            
            
            if(obj.config && obj.config.geral && obj.config.geral.logotipo && obj.config.geral.logotipo.favicon){
                this.criarFavicon(trataImagem(obj.config.geral.logotipo.favicon, "mini"))

            }
            
            
            this.estrutura.render(obj);
        }
        else{
            this.ajax();
            return;
        }
        

        let aux = dominio == dominioscript ? "conteudo/assets" : `conteudo/assets/wildcard/${obj.assets.wildcard}`
        
     
        if(obj.assets && obj.assets.css && obj.assets.css.length > 0){
              let aux = dominio == dominioscript ? "conteudo/assets/css" : `conteudo/assets/wildcard/${obj.assets.wildcard}`
            var i = 0;
            while(i < obj.assets.css.length){
                var item = obj.assets.css[i]
                
                var url = `${dominioscript}/${aux}/${item}`

                loadCSSResource(url)
                
                i++;
            }
        }
        
     
        
        if(obj.assets && obj.assets.js && obj.assets.js.length > 0){
            let aux = dominio == dominioscript ? "conteudo/assets/js" : `conteudo/assets/wildcard/${obj.assets.wildcard}`
            var i = 0;
            while(i < obj.assets.js.length){
                var item = obj.assets.js[i]
          
                var url = `${dominioscript}/${aux}/${item}`
                loadResources(url)
                i++;
            }
        }
    }
    
    analitcs(){
   
    }

    pegaInfos() {
        let config = pegaLocal("nown");
        
      
  
        this.analitcs();
        
        
        let renderizado = false;

        if (config) {
            
              this.noSpa = JSON.parse(config)?.config?.performance?.velocidade?.spa || false;
              if(this.noSpa){
                  document.getElementById("estruturaNown").classList.remove("d-none")
              }
            
       
            renderizado = true;
            this.render();
        }
        
        
        loadJSResource(`${dominioscript}/assets/js/canvas.js`).then(()=>{
            this.carrinho = new CanvaCarrinho(this);
            if(dataModule("aa8af3ebe14831a7cd1b6d1383a03755")){
                this.chat = new CanvaChat(this);
            }
            
            this.notificacao = new CanvaNotificacao(this);
            this.omini = new CanvaOmini(this);
            this.carteira = new  CanvaSaldo(this);
            
            this.cardOpcoes = new CanvaOpcoes(this);
            
            
            
        })

        if (navigator.onLine) {
            this.ajax(config);
        } else {
            if (!renderizado) {
                alert("a aplicação morreu");
            }
        }
    }

    ajax() {

   return new Promise((resolve, reject) => {
      
       
      try {
    
        var obj = {};
        obj.wildcard = dominio == dominioscript ? false : dominio;
        RequestRoute.quick("nown", "configuracoes", obj).then((responseObj) => {
               try {
           

                  if (!responseObj) {
                     console.error('Erro: Resposta vazia ou inválida');
                     reject('Resposta vazia ou inválida');
                     return;
                  }

                  cripto(JSON.stringify(responseObj))
                     .then(hash => {
                        try {

                           let assinatura = pegaLocal("assinatura");

                           if (assinatura != hash) {

                              defineLocal("nown", JSON.stringify(responseObj));
                              defineLocal("assinatura", hash);
                              this.render();
                           }

                           resolve();
                        } catch (hashError) {
                           console.error('Erro no processamento do hash:', hashError);
                           reject(hashError);
                        }
                     })
                     .catch((err) => {
                        console.error('Erro na criptografia:', err);
                        reject(err);
                     });
               } catch (responseError) {
                  console.error('Erro ao processar a resposta:', responseError);
                  reject(responseError);
               }
            })
            .catch((err) => {
               console.error('Erro na requisição AJAX:', err);
               reject(err);
            });
      } catch (error) {
         console.error('Erro inesperado:', error);
         reject(error);
      }
   });
}
    
    setPeer(id, peer){
        this.peerId = id;
        this.peer = peer;
    }
    
    controleSessao(){
    
        window.addEventListener("storage", (event) => {
  if (event.key === "atualizarAbas" && event.newValue === "true") {
    // Verifica se a aba não está ativa antes de recarregar
    if (document.visibilityState === "hidden") {
      location.reload();
    }
  }
});
    }
}

function autenticado(){
      var info = pegaLocal("nown")
    if(!info){
        return false
    }
    
    
    
    try{
        var obj = JSON.parse(info)
        return obj.usuario.usuario.id;
    }catch(e){
        return false;
    }
}

const start = new Start();

var peer = new Peer();

peer.on('open', (id) => {
    start.setPeer(id, peer);
});

navigator.serviceWorker.addEventListener('message', function(event) {
    if (event.data.message === 'offline') {
        alert('Você está offline! Algumas funcionalidades podem estar indisponíveis.');
    }
});

window.addEventListener('beforeunload', (event) => {
    // O texto personalizado não é mais mostrado na maioria dos navegadores modernos
    // por questões de segurança (para evitar enganar usuários)
    //const confirmationMessage = 'Tem certeza que deseja sair? Suas alterações podem ser perdidas.';
    
    // Padrão para navegadores modernos
    //event.preventDefault();
    
    // Para navegadores mais antigos
    //event.returnValue = confirmationMessage;
    
    // Para compatibilidade com versões mais antigas (ainda necessário em alguns casos)
    //return confirmationMessage;
});

class Icone extends HTMLElement {
    static aceitos = ["materialicons", "materialiconsoutlined", "materialiconsround", "materialiconssharp", "materialiconstwotone"];

    constructor() {
        super();
        this.shadow = this.attachShadow({ mode: 'open' });

        // Cor padrão para temas light e dark
        this.defaultColor = this.getAttribute("cor") || "#fff";
        this.darkColor = this.getAttribute("dark-cor") || "#000";
    }

    async connectedCallback() {
        const icone = this.getAttribute("icone");
        const tipo = this.getAttribute("tipo") ?? "materialicons";

        if (!icone || !Icone.aceitos.includes(tipo)) {
            console.error("Ícone ou tipo inválido.");
            return;
        }

        const cachedSvg = await this.getIconFromCache(icone, tipo);
        if (cachedSvg) {
            this.render(cachedSvg);
        } else {
            await this.fetchAndCacheIcon(icone, tipo);
        }
    }

    render(svg) {
        const parser = new DOMParser();
        const svgDoc = parser.parseFromString(svg, "image/svg+xml");
        const svgElement = svgDoc.documentElement;

        const width = this.getAttribute("width") || "16";
        const height = this.getAttribute("height") || "16";

        svgElement.setAttribute("width", `${width}px`);
        svgElement.setAttribute("height", `${height}px`);
        svgElement.setAttribute("fill", this.defaultColor);

        // Limpa o shadow root e adiciona os elementos renderizados
        this.shadow.innerHTML = "";
        const container = document.createElement("div");
        container.className = "icon-container";
        container.appendChild(svgElement);

        const style = document.createElement("style");
        style.textContent = `
            .icon-container {
                width: ${width}px;
                height: ${height}px;
            }
            svg {
                transition: fill 0.3s ease;
            }
        `;

        this.shadow.appendChild(style);
        this.shadow.appendChild(container);

        // Guarda o elemento SVG para atualizações de cor
        this.svg = svgElement;

        // Observar mudanças no tema
        this.observeTheme();
    }

    observeTheme() {
        const updateColor = () => {
            const isDarkMode = document.body.classList.contains("dark");
            const fillColor = isDarkMode ? this.defaultColor : this.darkColor;
            if (this.svg) {
                this.svg.setAttribute("fill", fillColor);
            }
        };

        // Atualiza a cor inicial
        updateColor();

        // Observa mudanças no body
        const observer = new MutationObserver(updateColor);
        observer.observe(document.body, { attributes: true, attributeFilter: ["class"] });

        // Salva o observer para desconectar futuramente
        this._themeObserver = observer;
    }

    async fetchAndCacheIcon(icone, tipo) {
        if (typeof dominio === "undefined") {
            console.error("A variável 'dominio' não está definida.");
            return;
        }
        const url = `${dominio}/conteudo/modulos/icones/api.php?icone=${icone}&tipo=${tipo}`;
        try {
            const response = await fetch(url);
            if (response.ok) {
                const svgContent = await response.text();
                this.render(svgContent);
                this.saveIconToCache(icone, tipo, svgContent);
            } else {
                console.error(`Falha ao carregar SVG: ${response.status}`);
            }
        } catch (error) {
            console.error(`Erro ao buscar SVG: ${error}`);
        }
    }

    async getDBConnection() {
        if (!window.indexedDB) {
            console.warn("IndexedDB não é suportado neste navegador.");
            return null;
        }
        return new Promise((resolve, reject) => {
            const request = indexedDB.open("IconDB", 1);
            request.onupgradeneeded = () => {
                const db = request.result;
                if (!db.objectStoreNames.contains("icons")) {
                    db.createObjectStore("icons", { keyPath: "id" });
                }
            };
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    async getIconFromCache(icone, tipo) {
        const db = await this.getDBConnection();
        if (!db) return null;

        return new Promise((resolve, reject) => {
            const transaction = db.transaction("icons", "readonly");
            const store = transaction.objectStore("icons");
            const query = store.get(`${tipo}-${icone}`);
            query.onsuccess = () => resolve(query.result ? query.result.svgContent : null);
            query.onerror = () => {
                console.error(`Erro ao buscar ícone do cache: ${query.error}`);
                reject(query.error);
            };
        });
    }

    async saveIconToCache(icone, tipo, svgContent) {
        const db = await this.getDBConnection();
        if (!db) return;

        const transaction = db.transaction("icons", "readwrite");
        const store = transaction.objectStore("icons");
        store.put({ id: `${tipo}-${icone}`, svgContent });
    }

    disconnectedCallback() {
        if (this._themeObserver) {
            this._themeObserver.disconnect();
        }
    }
}

// Define o elemento customizado com um hífen
customElements.define('i-nown', Icone);