class Paginacao{
    constructor(lista, div, configs, card, map, cb = false){
        this.cb = cb
        var continuar = true
        if(!lista || lista.length == 0){
            console.log('lista para inserção não foi passada ou não tem tamanho algum')  
            continuar = false
        }
        
        if(!div){
            console.log('DIV para inserção não passada')   
            continuar = false
        }
        
        this.lista = lista
        this.id = div
        this.card = card
        this.map = map
        
        if(!continuar){
            return
        }
        
        this.configs = {
            itens: 10,
            referencias: false,
            pesquisa: false,
            total: false
        }
        
        if(configs.itens){
            this.configs.itens = configs.itens
        }
        
        if(configs.referencias){
            this.configs.referencias = configs.referencias
        }
        
        if(configs.pesquisa){
            this.configs.pesquisa = configs.pesquisa
            evento(this.configs.pesquisa, 'input', this.pesquisar.bind(this))
        }
        
        if(configs.total){
            this.configs.total = this.lista.length
        }
        
        this.dividirLista.bind(this)()
    }
    
    pesquisar(){
        var i = 0
        var lista = []
        while(i < this.lista.length){
            var fica = true
            
            if(!this.lista[i].nome.toLowerCase().includes(event.target.value.toLowerCase())){
                fica = false
            }
            
            if(fica){
                lista.push(this.lista[i])
            }
            
            i++
        }
        
        this.dividirLista.bind(this)(lista)
    }
    
    dividirLista(lista = false){
        if(!lista){
            lista = this.lista
        }
        
        this.paginacao = 0
        
        if (lista.length > this.configs.itens) {
            this.listacao = true
            var subArrays = [];

            for (let i = 0; i < lista.length; i += this.configs.itens) {
                subArrays.push(lista.slice(i, i + this.configs.itens));
            }
            
            
            
            this.listaNaPagina = subArrays
            
            this.adicionarCardsPagina.bind(this)(subArrays)
        }
        else{
            this.listaNaPagina = []
            this.listacao = false
            this.listaNaPagina[0] = lista
            this.adicionarCardsPagina.bind(this)(lista)
        }
        
    }
    
    quantidadeTotal(quantidade){
        var p = document.createElement('p')
        
        p.innerHTML = `Mostrando ${quantidade} de ${this.configs.total} itens`
        
        return p
    }
    
    adicionarCardsPagina(){
        var lista = this.listaNaPagina
        this.id.innerHTML = ''
        document.getElementById('paginacao').innerHTML = ''
        
        var i = 0
        
        while(i < lista[this.paginacao].length){
            this.id.appendChild(this.montaCard.bind(this)(lista[this.paginacao][i]))
            
            i++
        }

        if(lista.length > 1 && this.listacao){
            this.adicionarPaginacao.bind(this)()
        }
        
        if(this.configs.total){
            this.id.appendChild(this.quantidadeTotal.bind(this)(i))
        }
    }
    
    adicionarPaginacao(){
        const nav = document.createElement('nav');
        nav.setAttribute('aria-label', 'Page navigation example');
        
        const ul = document.createElement('ul');
        ul.classList.add('pagination', 'justify-content-center');
        console.log(this.configs.referencias, this.paginacao)
        if(this.paginacao == 0){
            ul.appendChild(this.criarLiPaginacao.bind(this)('disabled', '<i class="bi bi-arrow-left-short"></i>', 'anterior'))
            var i = 0
            while(i < this.listaNaPagina.length && i < 7){
                
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', this.paginacao + i + 1, this.paginacao + i + 1))
                
                i++
            }
            
            if(this.configs.referencias && this.listaNaPagina.length > 3 && this.paginacao < (this.listaNaPagina.length - 3)){
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', this.listaNaPagina.length, this.listaNaPagina.length))
            }
           
            ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', '<i class="bi bi-arrow-right-short"></i>', 'proximo'))
        }
        else if(this.paginacao + 1 == this.listaNaPagina.length){
            ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', '<i class="bi bi-arrow-left-short"></i>', 'anterior'))
            var i = 0
            var volta = []
            
            if(this.configs.referencias && this.paginacao > 3){
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', 1, 1))
            }
            
            while(i < 7 && (this.listaNaPagina.length - i) > 0){

                volta.push(this.listaNaPagina.length - i)
                
                i++

            }
            
            var j = 0
            
            volta.reverse()
            
            while(j < volta.length){
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', volta[j], volta[j]))
                j++
            }
            
            ul.appendChild(this.criarLiPaginacao.bind(this)('disabled', '<i class="bi bi-arrow-right-short"></i>', 'proximo'))
        }
        else{
            ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', '<i class="bi bi-arrow-left-short"></i>', 'anterior'))
            
          
            var i = 0
            var volta = []
            while(i < this.paginacao && i < 3){
               
                volta.push(this.paginacao - i)
                
                i++
            }
            
            if(this.configs.referencias && this.paginacao > 3){
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', 1, 1))
            }
            
            volta.reverse()
            var j = 0
            
            while(j < volta.length){
                
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', volta[j], volta[j]))
                j++
            }
            
            ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', this.paginacao + 1, this.paginacao + 1))
            
            var i = 0
            while(this.paginacao + i + 1 < this.listaNaPagina.length && i < 3){
                
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', this.paginacao + i + 2, this.paginacao + i + 2))
                
                i++
            }
            
            if(this.configs.referencias && this.listaNaPagina.length > 3 && this.paginacao < (this.listaNaPagina.length - 4)){
                ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', this.listaNaPagina.length, this.listaNaPagina.length))
            }
            
            ul.appendChild(this.criarLiPaginacao.bind(this)('habilitado', '<i class="bi bi-arrow-right-short"></i>', 'proximo'))
        }
        
        nav.appendChild(ul)
        
        document.getElementById('paginacao').appendChild(nav)
    }
    
    criarLiPaginacao(desabilitado, texto, acao){
        const li = document.createElement('li');
        
        li.classList.add('page-item', desabilitado);
        
        if(this.paginacao + 1 == texto){
            li.classList.add('active')
        }
        
        const a = document.createElement('a');
        a.classList.add('page-link', 'btn');
        a.setAttribute('data-acao', acao)
        a.innerHTML = texto;
        
        li.appendChild(a)
        
        evento(a, 'click', this.paginando.bind(this))
        
        return li
    }
    
    paginando(){
        if(event.currentTarget.dataset.acao == 'anterior'){
            this.paginacao --
        }
        else if(event.currentTarget.dataset.acao == 'proximo'){
            this.paginacao ++
        }
        else{
            this.paginacao = parseInt(event.currentTarget.dataset.acao) - 1
        }
        
        
        this.adicionarCardsPagina.bind(this)()  
        
    }
    
    montaCard(infos){
        var card = this.card
        
        var tempDiv = document.createElement('div');

        tempDiv.innerHTML = card.trim();
        var mapa = new FastMap(infos, tempDiv)
        
        if(this.map.render){
            mapa.render(this.map.render)
        }
        
        if(this.map.imagens){
            mapa.imagens(this.map.imagens)
        }
        
        if(this.map.imagensbg){
            mapa.imagensbg(this.map.imagensbg)
        }
        
        if(this.map.links){
            mapa.links(this.map.links)
        }
        
        var cardElement = tempDiv.firstChild;
        if(this.cb){
            this.cb(cardElement, infos)
        }
        
        return cardElement
    }
}