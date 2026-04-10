class PlanosPage{
    constructor(){
         nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/carrinho.js`).then(()=>{
             
            var api = new ApiNown("assinaturas", "eQl4hH0wpDvRbOa")
            api.isNew()
            api.paginacao(99)
            api.send().then((r)=>{
                this.lista.bind(this)(r)
            })
            // api.start(this.lista.bind(this))
        
        
         })
        
        this.topos = [];
        this.cards = [];
        this.selecionado = false;
    }
    
    topoMobile(plano){
        var col = document.createElement("DIV")
        col.classList.add("col-4", "btn", "p-1", "colMob")
        col.dataset.plano = plano.preco.id
        col.dataset.comprado = plano.preco.comprado ? 1: 0
        var tag = "";
        if(plano.tag && plano.tag.trim()){
            tag = `<p class="fs-lg-14 fs-12 m-0 text-start">${plano.tag.trim()}</p>`;
        }
        
        col.innerHTML = `
        <div class="card ratio ratio-1x1 seletorMobile overflow-hidden">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h2 class="fs-lg-14 fs-12 fw-700 mb-1">${plano.nome}</h2>
                    ${tag}
                </div>
                <div class="text-end icone">
                    <i class="bi bi-check2-circle fs-30"></i>
                </div>
            </div>
        </div>
        `
        
        
        
        evento(col, "click", this.selector.bind(this))
        this.topos.push(col)
        document.getElementById("controlerMobile").appendChild(col)
    }
    
    lis(chave, valor){
       var li = document.createElement("LI")
       li.classList.add("list-group-item","py-3","bg-transparent")
       li.innerHTML = `
       <div class="d-flex d-lg-block justify-content-between align-items-center">
            <div class="fs-14 fw-700">${chave}</div>
            <div class="fs-16 fw-900">${valor}</div>
        </div>
       `
       return li;
    }
    
    lis2(chave, valor){
       var li = document.createElement("LI")
       li.classList.add("list-group-item","py-3","bg-transparent")
       li.innerHTML = `
       <div class="d-flex justify-content-between align-items-center">
            <div class="fs-14 fw-700">${chave}</div>
            <div class="fs-16 fw-900">${valor}</div>
        </div>
       `
       return li;
    }
    
    card(plano){
        var col = document.createElement("DIV")
        col.classList.add("col-12","col-xl-4","colAssinatura")
        col.dataset.plano = plano.preco.id
        
        evento(col, "click", this.selectCard.bind(this))
        
        var tag = "";
        if(plano.tag && plano.tag.trim()){
            tag = `<p style="color: var(--nown-primaria-text-over)" class="fs-14 m-0">${plano.tag.trim()}</p>`;
        }
        

        col.innerHTML = `
       
        <div class="cards-replicantes">
            <div class="card card-plano overflow-hidden">
                
                <div class="compra-plano">
                </div>
                <div class="card-header bg-transparent border-0 d-none d-xl-block">
                    <div class="topoplano">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fs-18 fw-700 mb-1 nome" style="color: var(--nown-primaria-text-over)">${plano.nome}</h2>
                                ${tag}
                            </div>
                            <div class="icone">
                                <i class="bi bi-check-circle-fill fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush atributos">
                        
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0 d-none">
                
                </div>
            </div>
        </div>
        `
        
        
        
        var fragmento = document.createDocumentFragment()

        let li = document.createElement("LI")
        li.classList.add("list-group-item","py-3","bg-transparent")
        
        fragmento.appendChild(li)

        
        var atributos = JSON.parse(plano.atr_pl)
        var j = 0
        
        while(j < atributos.length){
            fragmento.appendChild(this.lis2.bind(this)(atributos[j]['OcmI9TMzVAxSokfPZvpPJEXdlxSdrT'], atributos[j]['X2j0fgRJs3m26g4mbYwCv6TZPVCtbo']))
            j++
        }
        
        col.getElementsByClassName('atributos')[0].appendChild(fragmento)
        
        let divFaixa = col.getElementsByClassName('compra-plano')[0]
        let itemFaixa = col.getElementsByClassName('atributos')[0].getElementsByClassName('list-group-item')[0]
        
        
        this.cards.push(col)
        
        var card = new itemCarrinho(
        plano.preco.id,
        col.getElementsByClassName("card-footer")[0], 
        {onlybutton: true});
        card.init().then((r)=>{
            var ciclo = "";
            switch(r.produto.metas.ciclo){
                case 'mes':
                    ciclo = `Preço / Mês`
                    break;
                case 'ano':
                    ciclo = `Preço / Ano` 
                    break;
                default:
                    ciclo = `Preço / ${r.produto.metas.ciclo}`
                    break;
            }
            
            let funcao = r?.produto?.metas?.aoassinar
        
            if(funcao == infoUser().funcao){
                divFaixa.innerHTML = `
                <div class="d-none d-xl-block ribbon-wrapper position-absolute bg-success p-2 text-white w-100 text-center" style="top: 21px; right: -115px; transform: rotate(45deg);">
                    <div class="">
                        <span class="fw-bold">ADQUIRIDO</span>
                    </div>
                </div>`;
                
                itemFaixa.innerHTML = `
                <div class="d-block d-xl-none bg-success p-2 text-white text-center rounded-pill">
                    ADQUIRIDO
                </div>
                `
                
                col.dataset.comprado = 1
                
            }else{
                this.verificaPlano.bind(this)(divFaixa, 
                itemFaixa, 
                plano.id)
            }
            var li = this.lis.bind(this)(ciclo, paraPreco(plano.preco.preco))
            col.getElementsByTagName("ul")[0].appendChild(li)
        });

        document.getElementById("planosPlay").appendChild(col)

    }
    
    async verificaPlano(div, div2, id){
        try{
            const api = new ApiNown('pagamento', 'X1CJtX94HiUOvY4');
            api.isNew();
            api.paginacao(999);
            api.setExtra(infoUser().id);
            const response = await api.send();
            const lista = response.lista;
                
            if (lista.length > 0) {
                const agora = new Date();
                let comprado = false;
                
                for (const itemPagamento of lista) {
                    const dataInicio = new Date(itemPagamento.inicio);
                    const dataFim = new Date(itemPagamento.fim);

                    if (id === itemPagamento.plano.referencia && (agora >= dataInicio && agora <= dataFim)) {
                        comprado = true;
                        break;
                    }
                }
                
                let col = div.closest('.colAssinatura')
                
                if (comprado) {
                    div.innerHTML = `
                    <div class="d-none d-xl-block ribbon-wrapper position-absolute bg-success p-2 text-white w-100 text-center" style="top: 21px; right: -115px; transform: rotate(45deg);">
                        <div class="">
                            <span class="fw-bold">ADQUIRIDO</span>
                        </div>
                    </div>`;
                    
                    div2.innerHTML = `
                    <div class="d-block d-xl-none bg-success p-2 text-white text-center rounded-pill">
                        ADQUIRIDO
                    </div>
                    `
                    
                    col.dataset.comprado = 1
                }else{
                    col.dataset.comprado = 0
                }
            }
            
        }catch(erro){
            console.log(erro)
        }
    }
    
    selector(){
        var i = 0;
        while(i < this.topos.length){
            this.topos[i].getElementsByClassName("card")[0].classList.remove("ativo")
            
            i++;
        }
        var plano = event.currentTarget.dataset.plano
        this.selecionado = plano
        var i  = 0;
        while(i < this.cards.length){
            var card = this.cards[i]

            if(card.dataset.plano == plano){
                card.classList.add("ativo")
            }else{
                card.classList.remove("ativo")
            }
            i++;   
        }
        
        let comprado =  event.currentTarget.dataset.comprado;
        
        if(parseInt(comprado)){
            document.getElementById('fechaPlano').setAttribute('disabled', true)
        }else{
            document.getElementById('fechaPlano').removeAttribute('disabled')
        }
        
        event.currentTarget.getElementsByClassName("card")[0].classList.add("ativo")
      
    }
    
    selectCard() {
        var plano = event.currentTarget.dataset.plano;
        var i = 0;
        while(i < this.topos.length){
            var topo = this.topos[i]
            if(topo.dataset.plano == plano && !topo.classList.contains("ativo")){
                topo.click();
            }
            
            i++;
        }
        
        let comprado =  event.currentTarget.dataset.comprado;
        
        if(parseInt(comprado)){
            document.getElementById('fechaPlano').setAttribute('disabled', true)
        }else{
            document.getElementById('fechaPlano').removeAttribute('disabled')
        }
    
    }
    
    lista(r){
        var fragmento = document.createDocumentFragment();
        var lista = r.lista
        var i = 0
        
        this.planos = [];
        
        while(i < lista.length){
            var plano = lista[i]
            
            if(parseInt(plano.principal) === 1){
                this.topoMobile.bind(this)(plano);
                this.card.bind(this)(plano)
            }
            
            i++;
        }
        
        if(lista.length > 0){
            evento(document.getElementById("fechaPlano"), "click", this.fecha.bind(this))
            document.getElementById("fechaPlano").classList.remove("d-none")
        }else{
            document.getElementById("fechaPlano").remove();
        }
        
        
        this.topos[0].click();
    }
    
    fecha(){
        if(document.getElementById(`vendavel-${this.selecionado}`)){
            document.getElementById(`vendavel-${this.selecionado}`).getElementsByClassName("btnComprar")[0].click();
        }
    }
}

function paginaAssinaturas(){

    if(!autenticado()){
        new Restrito();
        return;
    }
    
    nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/carrinho.js`).then(()=>{
        new PlanosPage();
    })
  
}