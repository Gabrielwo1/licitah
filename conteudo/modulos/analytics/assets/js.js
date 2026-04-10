

class  FluxoHomeAnalytics extends CicloVida {
    constructor(){
        super();
        this.url = `${dominio}/conteudo/modulos/analytics/admins/relatorios.php`
        this.map.bind(this)();
        this.go.bind(this)();
        
        
        this.setInterval(this.go.bind(this), 30000);
     
    }
    
    map(){
        this.divAtivos = document.getElementById("usuariosOnline");
        this.divLogados = document.getElementById("usuariosLogados");
        this.divDeslogados = document.getElementById("usuariosDeslogados")
        this.divAbertas = document.getElementById("paginasAbertas")
        this.tabelaLogados = document.getElementById("tabelaLogados")
    }
    
    go(){
        var request = new RequestRote("analytics", "relatorios")
        request.addData({acao: "live"})
        request.send().then((r)=>{
            

            var sessoes = r.sessoes

            this.divAtivos.innerText = parseInt(sessoes.usuarios.ativos);
            this.divLogados.innerText = parseInt(sessoes.usuarios.logados);
            this.divDeslogados.innerText = parseInt(sessoes.usuarios.nao_logados);
            this.divAbertas.innerText = parseInt(r.abas.total_abas);
            
            
            var logados = r.logados.usuarios;
            if(logados.length > 0){
                var fragmento = document.createDocumentFragment();
                var i = 0;
                while(i < logados.length){
                    var logado = logados[i]
                    var img = trataImagem(logado.foto, "mini");
                    if(!img){
                        img = gerarAvatar(logado.name, 100);
                    }
                     var tr = document.createElement("TR")
                tr.innerHTML = `
                <td class="fonte-item"><img src="${img}" class="wi-50 he-50 rounded-circle img-thumbnail"></td>
                                    <td class="metrica-valor">${logado.name}</td>
                                    <td>
                                        <button class="btn btn-n-primaria btn-sm">Ver</button>
                                    </td>
                
                `
                fragmento.appendChild(tr);
                    
                    i++;
                }
               
                
                
            }
            this.tabelaLogados.innerHTML = ``
            this.tabelaLogados.appendChild(fragmento)
            

            
        })
    }
}

function moduloAnalytics(){
     var fluxo = fluxoPage("analytics");
     if(fluxo.length == 0){
         var home = new FluxoHomeAnalytics();
     }
}