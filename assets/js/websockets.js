class WebSocketNown{
    constructor(){
        
    
        
        this.ativo = false;
        
        
        window.addEventListener("usuario-logado", this.logou.bind(this))
        window.addEventListener("usuario-deslogado", this.deslogou.bind(this))
        
        this.online = {}
        
        
    
        var obj = JSON.parse(pegaLocal("nown"));
        
        var configuracao = obj?.config?.apis?.websockets || {}
   
      
        
        if(configuracao?.websockets || false){
            
            nownFiles.add("https://js.pusher.com/8.4.0/pusher.min.js").then(()=>{
            
        
            this.ativo = true;
            
            this.pusher = new Pusher(configuracao.key, {
                cluster: configuracao.cluster,
                authEndpoint : `${dominio}/admin/sockets/presence.php`,
                channelAuthorization: {
                    endpoint: `${dominio}/admin/sockets/presence.php`,
                }
             });

             this.pusher.channel_auth_endpoint = `${dominio}/admin/sockets/presence.php`; 
             
             this.channelAllUsers = this.pusher.subscribe("nown-user");
             
             if(autenticado()){ 
                 this.logou();
             }

        })
         }

    }
    
    logou(){
        if(autenticado()){

            this.userLogado = parseInt(autenticado());
            
            this.channel = this.inscrever(`usuario-${this.userLogado}`);
            this.channelLogados = this.inscrever(`nova-mensagem`);
            this.desinscrever('usuariosDeslogados');
            
            
            
            this.presenca = this.pusher.subscribe('presence-channel');
            this.setPresenca("pusher:subscription_succeeded", this.inscrito.bind(this))
            this.setPresenca("pusher:member_added", this.adicionado.bind(this))
            this.setPresenca("pusher:member_removed", this.removido.bind(this))
            
           // this.set.bind(this)("desconectado", this.desconectado.bind(this))
           // this.set.bind(this)("userAprove", this.atualizasessao.bind(this));
            
            
        }
    }
    
    deslogou(){
        if (this.userLogado) {
            desinscrever(`usuario-${this.userLogado}`);
            this.chanelDeslogados = inscrever(`usuariosDeslogados`);
            desinscrever('usuariosLogados');
        }
    }
    
    inscrever(chave){
        return this.pusher.subscribe(chave);
    }
    
    desinscrever(chave){
        this.pusher.unsubscribe(chave);
    }
    
    desconectado(r){
        var sessao = pegaLocal("sessao");
        if(r.message == 0){
            deslogar();
        }else{
            if(sessao == r.message){
               deslogar();
            }
        }
    }
    
    inscrito(r){
        this.online = r.members

    }
    
    adicionado(r){
        
       
        
        this.online[r.id] = r.info
        
        
      
        
        var user = r.info.user_info

        
        iziToast.show({
    title: user.nome,
    message: 'Está Online',
    image: trataImagem(user.foto, "mini"),
    imageWidth: 50,
    icon: 'bi bi-circle-fill',
    iconColor: 'green'
});
        
        
    

    }
    
    getOnlines(){
        return this.online
    }
    
    removido(r){
        console.log("removido")
    }
    
    setPresenca(evento, callBack){
        this.presenca.bind(evento, function(data) {
            callBack(data);
        });
    }
    
    set(evento, callBack){
        this.channel.bind(evento, function(data) {
            callBack(data);
        });
    }
    
    atualizasessao(r){
        var request = new Request("admin/autoriza.php");
        request.addData({"acao": "updateSession"})
        request.send().then((e)=>{
            goUrl("");
        })
        
        
    }

}

const websocket = new  WebSocketNown();