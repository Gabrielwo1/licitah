function ajustaDragMenu(){
    if(event.target.closest(".filhos")){
        var card = event.target.classList.contains("elementoMenu") ? event.target : event.target.closest(".elementoMenu")
        
        var subs = card.getElementsByClassName("elementoMenu")
        if(subs.length > 0){
            var tamanho = subs.length - 1;
            while(tamanho >= 0){
                document.getElementById("listaItens").appendChild(subs[tamanho])
                tamanho--;
            }
        }
        
        
    }
}

class ItemMenu{
    constructor(config, modal){
        for(var item in config){
            this[item] = config[item]
        }
        
        this.modal = modal;
        
        this.inputUrl = document.getElementById("setupMenu").querySelector('input[name="url"]')
        this.inputTexto = document.getElementById("setupMenu").querySelector('input[name="texto"]')
        this.inputVizibilidade = document.getElementById("setupMenu").querySelector('select[name="vizibilidade"]')
        this.inputEtiqueta = document.getElementById("setupMenu").querySelector('input[name="etiqueta"]')
        this.inputIcone = document.getElementById("setupMenu").querySelector('input[name="icone"]')

        
    }
    
    define(config){
        for(var item in config){
            this[item] = config[item]
        }
        
        this.titulo.innerText = this.texto
    }
    
    render(){
        var tipo = ["", "Link Interno", "Link Externo", "Separador"]
        var id = geraId();
        
        var span = new El("SPAN")
        span.t(tipo[this.tipo])
        
        
        var card = new El("DIV")
        card.c("card rounded-0 elementoMenu border-0")
        card.a({
            "data-id": this.id
        })
        
        var header = new El("DIV");
        header.c("card-header d-flex justify-content-between align-items-center p-1 bg-contrast border rounded-0 text-contrast")
        
        
        var botao = new El("BUTTON")
        botao.c("btn")
        var icone = new El("I")
        icone.c("bi bi-pencil-square text-contrast")
        botao.f([icone.html()])
        botao = botao.html();
        evento(botao, "click", this.configura.bind(this))
 
        
        
        var botao2 = new El("BUTTON")
        botao2.c("btn move w-100 d-flex justify-content-between align-items-center text-contrast")
        
        this.titulo = new El("SPAN")
        this.titulo.t(this.texto)
        this.titulo = this.titulo.html();
        botao2.f([this.titulo, span.html()])
        
     
        
        header.f([botao2.html(), botao])
        
   
        
        
        var filhos = new El("DIV")
        filhos.c("ps-5 flex-column gap-2 justify-content-around filhos")
        filhos = filhos.html();
        
        
        if(this.tipo != 3){
              card.f([header.html(), filhos])
               loadResources("https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js").then(()=>{
                   new Sortable(filhos, {
            handle: '.move', 
            animation: 150,
            group: 'nested',
            onEnd: function (evt) {
                ajustaDragMenu();
            },
        });
               })
                  
        }else{
            card.f([header.html()]) 
        }
      
        
        
        
        
        
        this.card = card.html();
        return this.card;
     
    }
    
    configura(){
        
        this.inputUrl.value = this.link
        this.inputTexto.value  = this.texto
        this.inputVizibilidade.value  = this.vizibilidade
        this.inputEtiqueta.value  = this.etiqueta
        this.inputIcone.value  = this.icone
        
        document.getElementById("setupMenu").getElementsByClassName("btnSalvar")[0].dataset.id = this.id
        this.modal.show();
    }
    
    deletar(){
        console.log("teste teste")
    }
    
    pegavalores(){
        return {
             link: this.link,
             texto : this.texto,
             vizilidade: this.vizibilidade,
             etiqueta:  this.etiqueta,
             icone: this.icone,
             tipo: this.tipo
        }
    }
    
    
    
  
}

class Menu{
    constructor(){
        this.itens = [];
        this.btns = document.getElementsByClassName("novo-item")
        evento(Array.from(this.btns) , "click" , this.novo.bind(this))
        
        this.lista = document.getElementById("listaItens")
        
        loadResources("https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js").then(()=>{
        new Sortable(this.lista, {
            handle: '.move', // handle's class
            animation: 150,
            group: 'nested',
            onEnd: function (evt) {
                ajustaDragMenu();
            }
        });
        })
        
        evento(document.getElementById("salvar"), "click", this.salvar.bind(this))
        evento(document.getElementById("deletarItem"), "click", this.deletaItem.bind(this))
        evento(document.getElementById("deletar"), "click", this.deletar.bind(this))
        
        this.inputUrl = document.getElementById("setupMenu").querySelector('input[name="url"]')
        this.inputTexto = document.getElementById("setupMenu").querySelector('input[name="texto"]')
        this.inputVizibilidade = document.getElementById("setupMenu").querySelector('select[name="vizibilidade"]')
        this.inputEtiqueta = document.getElementById("setupMenu").querySelector('input[name="etiqueta"]')
        this.inputIcone = document.getElementById("setupMenu").querySelector('input[name="icone"]')

        this.modal = new bootstrap.Modal('#setupMenu', {keyboard: false})
        evento(document.getElementById("setupMenu").getElementsByClassName("btnSalvar")[0], "click", this.salvaItem.bind(this))
        new Recupera(caminho.hash(), `menus_item`, this.completa.bind(this));
    }
    
    
    completa(){
      
        if(document.querySelector('textarea[name="estrutura"]').value){
            console.log(document.querySelector('textarea[name="estrutura"]').value)
            var obj = JSON.parse(document.querySelector('textarea[name="estrutura"]').value)
            console.log(obj)
            var i = 0;
            while(i < obj.length){
                var id = geraId();
                obj[i].config.id = id
                this.itens[id] = new ItemMenu(obj[i].config, this.modal);
                var pai = this.itens[id].render();
                this.lista.appendChild(pai)
                
                if(obj[i].filhos.length > 0){
                    var filhos = obj[i].filhos;
                    var z = 0;
                    while(z < filhos.length){
                        
                        var filho = filhos[z]
                        var id = geraId();
                        filho.config.id = id
                        this.itens[id] = new ItemMenu(filho.config, this.modal);
                        var fio = this.itens[id].render();
                        pai.getElementsByClassName("filhos")[0].appendChild(fio)
                        z++;
                    }
                }
                
                i++;
            }
        }
    }
    
    filhos(pai){
        var itens = pai.getElementsByClassName("elementoMenu")
        
        var f = [];
        
        var i = 0;
        while(i < itens.length){
            var item = {
                config: this.itens[itens[i].dataset.id].pegavalores(),
                filhos: this.filhos.bind(this)(itens[i])
            }
            f.push(item);
            i++;
            
        }
        
        return f;
    }
    
    salvar(){
        var itens = document.querySelectorAll('#listaItens > .elementoMenu');
        
        var lista = [];
        var i = 0;
        while(i < itens.length){
            var item = {
                config: this.itens[itens[i].dataset.id].pegavalores(),
                filhos: this.filhos.bind(this)(itens[i])
            }
            lista.push(item);
            i++;
        }
        
        var infos = {
            id: caminho.hash(),
            estrutura: lista,
            titulo: document.querySelector('input[name="titulo"]').value 
        }
        
        ajax(infos, "menus_edita", this.atualizado.bind(this))
    }
    
    deletaItem(){
        var id = document.getElementsByClassName("btnSalvar")[0].dataset.id
        var elemento = document.querySelector(`div[data-id="${id}"]`);
        var filhos = elemento.getElementsByClassName("filhos")[0].getElementsByClassName("elementoMenu")
        if(filhos.length > 0){
            var i = 0;
            while(i < filhos.length){
                
                this.lista.appendChild(filhos[i])
                i++;
            }
            
        }
        elemento.remove();
        this.modal.hide();
        

    }
    
    atualizado(r){
        var obj = JSON.parse(r)
        if(obj.sucesso){
            Swal.fire({
  icon: 'success',
  title: 'Menu Atualizado com Sucesso',
  showConfirmButton: false,
  timer: 1500
})
            start.ajax();
        }
    }
    
    deletar(){
        var chave = {
            chave: "menus_deleta"
        }
        var r = {
            hash: caminho.hash(),
            data: chave
        }
 
         deletar(r, this.deletado.bind(this));
    }
    
    deletado(r){
        var r = JSON.parse(r)
        if(r.sucesso){
             goUrl(`a/menus`)
        }
    }
    
    novo(){
        
        var pai = event.target.closest(".accordion-item")
        var link = pai.getElementsByClassName("link")[0]
        var texto = pai.getElementsByClassName("texto")[0]
        
        if(texto.value){
            var id = geraId();
            var config = {
            "tipo" : event.target.dataset.t,
            "link": link.value,
            "texto": texto.value,
            "vizibilidade": 0,
            "etiqueta": "",
            "icone": "",
            "id" : id
            }
            
            
            
            this.itens[id] = new ItemMenu(config, this.modal);
            this.lista.appendChild(this.itens[id].render())
            
            link.value = ""
            texto.value = ""
            
        }else{
            texto.classList.add("is-invalid")
        }
        
      
      
        
    }
    
    salvaItem(){
         var config = {
            "link": this.inputUrl.value,
            "texto": this.inputTexto.value,
            "vizibilidade": this.inputVizibilidade.value,
            "etiqueta": this.inputEtiqueta.value,
            "icone": this.inputIcone.value,
            }
        this.itens[event.target.dataset.id].define(config)
        this.modal.hide();
        
    }
}

function moduloMenusEditar(){
    new Menu();
}

class GeralMenus{
    constructor(){
        evento(document.getElementById("novoMenu"), "click", this.novo.bind(this))
    }
    
    novo(){
        ajax(false, "menus_cadastra", cadastrosucesso)
    }

}

function cadastrosucesso(r){
    var obj = JSON.parse(r)
    if(obj.sucesso){
        console.log(obj)
    }
}

function moduloMenus(){
    new GeralMenus();
}

