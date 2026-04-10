class NewAnalytics{
    constructor(){
        this.heartbeatInterval = 30000; 
        this.heartbeatTimer = null;
    
        this.url = `${dominio}/conteudo/modulos/analytics/admins/analitycs.php`;
        this.registraEventos.bind(this)();
        this.init.bind(this)();
        
    }
    
    init(){
        var request = new RequestRote("analytics", "analitycs");
        request.addData({
            acao: "start", 
            url: window.location.href.split(dominio)[1]
        })
        request.send().then((r)=>{
            this.aba = r.aba
            
            
            if(!r.dispositivo){
                 nownFiles.add(`${dominio}/conteudo/modulos/analytics/assets/dispositivo.js`).then((r)=>{
                      this.assinarDispositivo.bind(this)();
                 })
            }
            
            if(!r.ip || !r.localizacao){
                nownFiles.add(`${dominio}/conteudo/modulos/analytics/assets/localizacao.js`).then((r)=>{
                      this.assinarLocalizacao.bind(this)();
                 })
            }
            
            this.comecarBatida.bind(this)();

        }, (r)=>{
            console.log(r)
        })
    }
    
    async assinarDispositivo(){
        var dispositivo = new Dispositivo();
        var dados = await dispositivo.obterDadosFixos();
        
        var request = new Request(this.url);
        request.addData({
            acao: "registra-dispositivo",
            dispositivo: JSON.stringify(dados),
            assinatura: pegaLocal("digital-dispositivo")
        })
        request.send().then((r)=>{
            if(r.dispositivo){
                defineLocal("digital-dispositivo", r.dispositivo)
            }
            
   
        }, (r)=>{
            console.log(r)
        })
    }
    
    async assinarLocalizacao(){
        var localizacao = new Localizacao();
        localizacao.getTodasInformacoes().then((r)=>{
            var request = new Request(this.url);
            request.addData({
                acao: "registrar-localizacao", 
                "localizacao": JSON.stringify(r)
            })
            request.send().then((r)=>{
                 console.log(r)
            }, (r)=>{
                console.log(r)    
            
            })
        })
    }
    
    deslogado(){
        this.fechouAba.bind(this)();
        clearInterval(this.heartbeatTimer);
        setTimeout(()=>{
            this.init();
        }, 500)
    }
    
    logado(){
        this.sendRequest({acao: "registra-usuario"})
     
    }
    
    sendRequest(obj){
         if (navigator.sendBeacon) {
            const data = new FormData();
            for(let c in obj){
                data.append(c, obj[c]);
            }
            navigator.sendBeacon(this.url , data);
        } else {
            fetch(this.url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(obj),
                keepalive: true
            });
        }
    }
    
    carregada(){
       this.sendRequest(
           {
               acao: "pagina",
               aba: this.aba,
               url: window.location.href.split(dominio)[1]
            })
    }
    
    fechouAba(){
        clearInterval(this.heartbeatTimer);
        this.sendRequest(
            {
                acao: "fechamento",
                aba: this.aba
                }
            )
    }
    
    registraEventos() {

        window.addEventListener("pagina-carregada", this.carregada.bind(this));
        window.addEventListener("usuario-deslogado", this.deslogado.bind(this));
        window.addEventListener("usuario-logado", this.logado.bind(this));


        window.addEventListener('pagehide', (e) => {this.fechouAba()});

        
        this.visibilidadeAba.bind(this)();
    }
    
    batida(){
        if(this.aba){
            this.sendRequest({
                    acao: "batida",
                    aba: this.aba
            })
        }
        
    }
    
    abaInvisivel(){
        if(this.aba){
            this.sendRequest({
                    acao: "abainvisivel",
                    aba: this.aba,
                })
        }
        
    }
    
    visibilidadeAba() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearInterval(this.heartbeatTimer);
                this.abaInvisivel.bind(this)();
            } else {
                this.comecarBatida();
                this.batida();
            }
        });
    }
    
    comecarBatida() {
        this.heartbeatTimer = setInterval(() => {
            if (!document.hidden) {
                this.batida.bind(this)();
            }
        }, this.heartbeatInterval);
    }
}

setTimeout(()=>{
    const analytics = new NewAnalytics();
}, 500)


