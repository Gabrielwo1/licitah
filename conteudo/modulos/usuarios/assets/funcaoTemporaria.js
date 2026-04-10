class testeTemporario{
    constructor(){
        this.hash = pegaHash();
        this.idAcordion = geraId();
        this.enderecos = []
        this.init.bind(this)();
    }
    
    init(){
        var api = new ApiNown('usuarios', 'dGYUJTlfPx2jNwN')
        api.setHash(this.hash)
        api.send().then((r)=>{
            if(r.item){
                this.funcao = r.item.id
                this.chamarUsuarios.bind(this)()
            }
        }, (r)=>{
            console.log(r)
        })
    }
    
    chamarUsuarios(){
        var api = new ApiNown('usuarios', 'utO3HNkbInUXdHA')
        api.send().then((r)=>{
            var lista = r.lista
          
            var i = 0
            
            var fragmento = document.createDocumentFragment()
            
            while(i < lista.length){
                this.acordion = document.createElement("DIV")
                this.acordion.classList.add("accordion");
                this.acordion.id = this.idAcordion;
                
                var drop = lista[i]
                let html = this.item.bind(this)(drop);
                if(html && this.funcao == lista[i].funcao){
                    
                   this.acordion.appendChild(html) 
                   fragmento.appendChild(this.acordion)
                }
                
                
                i++
            }
            
            document.getElementById("usuarios").getElementsByClassName("card-body")[1].appendChild(fragmento)
        })
    }
    
    item(info){
        var id = geraId();
        var nome = info.display ?? 'Usuário sem nome'
        var drop = document.createElement("DIV")
        drop.classList.add("accordion-item");
        drop.innerHTML = `
            <h2 class="accordion-header">
              <button class="accordion-button collapsed d-flex align-items-center gap-2" data-id="${info.id}" type="button" data-bs-toggle="collapse" data-bs-target="#${id}" aria-expanded="false" aria-controls="${id}">
               <span><i class="bi bi-person"></i></span>
               <span>${nome}</span>
              </button>
            </h2>
            <div id="${id}" class="accordion-collapse collapse" data-bs-parent="#${this.idAcordion}">
              <div class="accordion-body d-flex flex-column gap-4">
                
               
                
              </div>
            </div>

            `
        evento(drop.getElementsByClassName('accordion-button')[0], 'click', this.abriuAccordion.bind(this))  
        return drop
    }
     
    abriuAccordion(){
        var botao = event.currentTarget
        var colapso = botao.getAttribute('data-bs-target');
        var id = colapso.replace('#', '');


        var elemento = document.getElementById(id).getElementsByClassName('accordion-body')[0];
        if(elemento.innerHTML.trim() == ''){
            elemento.innerHTML = `
            <div class="">
                <div class="bg-carregando w-100 he-20 mb-1"></div>
                <div class="bg-carregando w-100 he-20 mb-1"></div>
                <div class="bg-carregando w-100 he-20 mb-1"></div>
                <div class="bg-carregando w-100 he-20 mb-1"></div>
                <div class="bg-carregando w-100 he-20 mb-1"></div>
            </div>
            `
            var api = new ApiNown('usuarios', 'SrsUXPIM0z0SBrl')
            api.setHash(botao.dataset.id)
            api.send().then((r)=>{
                if(r.item){
                    this.usuario = r.item.id
                    this.definirUsuario.bind(this)(elemento, r.item)
                }
                
            })
            
            
        }else{
            console.log('já foi clicado antes')
        }
    }
    
    definirUsuario(div, infos){
        var ul = document.createElement('ul'); 
        ul.classList.add('list-group')

        for (var [key, value] of Object.entries(infos)) {
            if(key != 'Foto'){
                var li = document.createElement('li');
                li.classList.add('list-group-item')
                
                switch(key){
                    case 'Data de Entrada': 
                        var valor = dataFormatada(value)
                        break;
                    default:
                        var valor = value
                        break
                }
                
                li.textContent = `${key}: ${valor}`;
                if(value.trim() != ''){
                    ul.appendChild(li);
                }
            }else{
                var imagem = value
            }
            
            
        }
        
        if(imagem && JSON.parse(imagem) && JSON.parse(imagem.length) > 0){
            var foto = trataImagem(imagem)

            var img = `<img class="w-100 rouned rounded-4" style="objec-fit:contain" src="${foto}">`

        }
        
        div.innerHTML = ``
        
        if(foto){
            div.innerHTML = `
            <div class="row">
                <div class="col-lg-3 col-12">
                ${img}
                </div>
                <div class="col-lg-9 col-12">
                </div>
            </div>
            `
            div.getElementsByClassName('col-lg-9')[0].appendChild(ul)
        }else{
             div.appendChild(ul)
        }
        
        var endereco = document.createElement('div');
        endereco.classList.add('div-endereco')
        endereco.innerHTML = `
   
            <h3>Endereços: </h3>
            <div class="user-endereco">
                <div class="row g-2">
                    <div class="col-lg-6 col-12">
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                    </div>
                    
                    <div class="col-lg-6 col-12">
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                        <div class="bg-carregando w-100 he-20 mb-1"></div>
                    </div>
                </div>
            </div>
        `
                
        div.appendChild(endereco)
        
        this.puxarEndereco.bind(this)(div)
        
    }
    
    puxarEndereco(div){
        if(this.enderecos.length == 0){
            var api = new ApiNown('enderecos', '95wESMwISiUTeyu')
            api.send().then((r)=>{
                this.enderecos = r.lista
                this.preencherEndereco.bind(this)(div)
            }, (r)=>{
                console.log(r)
            })
        }else{
            this.preencherEndereco.bind(this)(div)
        }
        
    }
    
    async preencherEndereco(div){
        var lista = this.enderecos
        
        var i = 0
        var enderecos = []
        while(i < lista.length){
            if(this.usuario == lista[i].identificador && lista[i].banco == 'usuarios'){
                enderecos.push(lista[i])
            }
            i++
        }
        
        
        
        var j = 0
        
        var lista = document.createElement('div'); 
        lista.classList.add('row', 'g-2')
        
        while(j < enderecos.length){
            
            var endereco = await this.completarEndereco.bind(this)(enderecos[j].endereco)

            if(endereco){
                lista.appendChild(endereco)
            }
           
            j++
        }

        div.getElementsByClassName('user-endereco')[0].innerHTML = ``
        if(enderecos.length > 0 && lista.children.length > 0){
            div.getElementsByClassName('user-endereco')[0].appendChild(lista)
        }else{
            div.getElementsByClassName('user-endereco')[0].innerHTML = `<p>Nenhum endereço cadastrado</p>`
        }

       
    }
    
    async completarEndereco(hash){
        var api = new ApiNown('enderecos', 'Ynuy2vD4Zkh80Bp')
        api.setHash(hash)
        
        try{
            var resposta = await api.send()
            
            if(resposta.item){
                var ul = document.createElement('ul'); 
                ul.classList.add('list-group')
                
                for (var [key, value] of Object.entries(resposta.item)) {
                    
                    var li = document.createElement('li');
                    li.classList.add('list-group-item')
7
                    switch(key){
                        case 'Última Atualização': 
                            var valor = dataFormatada(value)
                            break;
                        default:
                            var valor = value
                            break
                    }
                    
                    li.textContent = `${key}: ${valor}`;
      
                    if(value && value.trim() != ''){
                        ul.appendChild(li);
                    }
                }
   
                var coluna = document.createElement('div'); 
                coluna.classList.add('col-xl-6', 'col-12')
                
                coluna.appendChild(ul)
                
                return coluna
            }else{
                return false
            }
            
            
        }
        catch(error){
            console.log(error)
            return false
        }
        
        
    }
}