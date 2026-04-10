class itemCarrinho{
    constructor(id = false, container = false, configs = {}, extra = {}){
        this.id = id;
        this.catalog = false;
    
        this.resumo = {};
         this.transportadoras = {};
        
        if(!id || !container){
            return;
        }
        
        
        this.container = container;
        this.configs = {
            bg : true,
            shadow : true,
            stick : true,
            visible : true,
            onlybutton: false,
        }
        
        this.extra = {
            img: false,
            txts : [],
            bgColor: false,
            txtFinalizarPedido: "Finalizar Pedido",
            txtRemover: "Remover",
            txtVerCarrinho: "Ver Carrinho",
            txtComprar: "Comprar",
            txtAssinar: "Assinar",
            txtAdicionarCarrinho: "Adicionar ao Carrinho"
        }
        
    
        
        
        
        
        if(extra.txts && extra.txts.length > 0){
            var i = 0;
            while(i < extra.txts.length){
                this.extra.txts.push(extra.txts[i])
                i++;
            }
        }
        
        if(extra.img){
            this.extra.img = extra.img
        }
        
        if(extra.bgColor){
            this.extra.bgColor = extra.bgColor
        }
        
        if(extra.textColor){
            this.extra.textColor = extra.textColor
        }
        
        for(let c in configs){
            this.configs[c] = configs[c];
        }
        

    }
    
    isCatalog(link = false){
        this.catalog = true;
        this.catalogLink = link
    }
    
    preRender(){
        if(document.getElementById(`vendavel-${this.id}`)){
            document.getElementById(`vendavel-${this.id}`).remove();
        }
       
        
        
        var div = document.createElement("DIV")
        div.classList.add("cardPrecoNown")
        div.id = `vendavel-${this.id}`;
        
        
        if(this.configs.onlybutton){
            div.innerHTML = `
            <div class="btns">
                    <div class="d-none">
                        <button class="btn btn-n-primaria d-block w-100 btn-nown-style btnComprar animate__animated animate__fadeIn" disabled>${this.extra.txtComprar}</button>
                    </div>
                     <div class="d-none">
                        <button class="btn btn-n-primaria w-100 btn-nown-style btnFinalizar animate__animated animate__fadeIn" disabled>${this.extra.txtFinalizarPedido}</button>
                    </div>
                    <div class="d-none">
                        <button class="btn btn-n-secundaria w-100 btn-nown-style btnAddCarrinho animate__animated animate__fadeIn" disabled>${this.extra.txtAdicionarCarrinho}</button>
                    </div>
                    <div class="d-none">
                        <button class="btn w-100 text-primary btnVerCarrinho animate__animated animate__fadeIn" disabled>${this.extra.txtRemover}</button>
                    </div>
                </div>
            `
            this.container.appendChild(div)
            return;
        }
        
        
         let img = '';
        if(!this.extra.img){
            img = ``;
        }
        else{
            img = `<div><img src="${this.extra.img}" class="w-100 he-150" style="object-fit:contain"></div>`;
        }
         let txts = document.createElement("DIV")
        if(this.extra.txts.length > 0){
            var conteudo = document.createElement("DIV")
            conteudo.classList.add("conteudo", "d-flex" , "flex-column" , "gap-1")
            
            var i = 0;
            let textos = this.extra.txts;
            let tem = false;
            while(i < textos.length){
                tem = true;
                var texto = textos[i]
                
                if(texto.texto){
                    if(texto.tipo){
                        var diva = document.createElement(texto.tipo)
                    }else{
                        var diva = document.createElement("p")
                    }
                    
                    diva.innerText = texto.texto
                    if(texto.classe){
                        diva.className = texto.classe
                    }
                    conteudo.appendChild(diva)
                    
                }
                
                i++;
            }
            
            if(tem){
       
                txts.appendChild(conteudo);
            }
        }
        txts = txts.outerHTML;
        

        
        
        if(this.configs.bg){
            div.classList.add("back")
        }
        
        
        if(this.configs.stick){
            div.classList.add("sticky-top")
        }
        
        
        
        
        this.card = div;
        
        
        
        
        
        
        div.innerHTML = `
            <div class="supertopo">
            ${img}
            ${txts}
            </div>
            <div class="top">
            <div class="conteudo">
                <div class="preco">
                    <div class="cifra">R$</div>
                    <div class="inteiro"><div class="bg-carregando wi-50 he-50"></div></div>
                    <div class="centavo"><div class="bg-carregando wi-20 he-20"></div></div> 
                </div>
                <div class="text-center fs-14 my-1 condicao">
                    ou parcele em até
                </div>
                <div class="parcela">
                    <div class="quantidade"></div>
                    <div class="intermedio">x de </div>
                    <div class="valor"></div>
                </div>

            </div>
            <div class="conteudo divQuantificador py-0">
                <div class="quantificador">
                    <div class="q">Quantidade: </div>
                    <div>
                        <select class="quantidade">
                            <option>1</option>
                        </select>
                        <input class="quantidadePlus form-control d-none" placeholder="Quantidade" value="6">
                    </div>
                </div>
            </div>
            
    
            <div class="conteudo d-none listaItens">
                <ul class="list-group list-group-flush">
                  
<div class="list-group-item list-group-item-action shipping-item doProduto d-none" style="border-left: 4px solid rgb(40, 167, 69);">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="badge-container">
                            <div class="company-logo border-warning">
                               <i class="bi bi-cart-check text-success fs-20"></i>
                            </div>
  
                        </div>
                    </div>
                    <div class="col">
                        <div class="company-name"><span class="fw-700 quantidade"></span> itens</div>
                        <small class="text-muted">Subtotal: <span class="fw-700 subtotal"></span></small>
                        <span class="badge bg-success shipping-badge"><i class="bi bi-check"></i> No Carrinho</span>
                    </div>
                    <div class="col-auto text-end">
                        <button class="btn text-danger fw-700 fs-30 cancelaCarrinho"><i class="bi bi-x"></i></button>
                       
                    </div>
                </div>
            </div>


                </ul>
            </div>
                    </div>
                    
                    <div class="conteudo py-0">
                     <div class="d-none">
                        <button class="btn btn-n-secundaria w-100 btn-nown-style btnAddCarrinho animate__animated animate__fadeIn" disabled>${this.extra.txtAdicionarCarrinho}</button>
                    </div>
                    <div class="d-none">
                        <button class="btn w-100 text-primary btnVerCarrinho animate__animated animate__fadeIn" disabled>${this.extra.txtVerCarrinho}</button>
                    </div>
                    </div>
                    
            <div class="conteudo animate__animated animate__fadeIn calcfrete">
                <div class="d-flex justify-content-between">
                    <div class="input-group">
                        <input type="text" class="form-control buscacep" placeholder="Calcular Frete" data-mascara="3">
                        <button class="btn btn-n-primaria btnCep"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="conteudo d-none">
            <div class="list-group listFretes">
 

</div>
            </div>
            <div class="end">
            <div class="conteudo">
                <div class="btns">
                    <div class="d-none">
                        <button class="btn btn-n-primaria d-block w-100 btn-nown-style btnComprar animate__animated animate__fadeIn" disabled>${this.extra.txtComprar}</button>
                    </div>
                     <div class="d-none">
                        <button class="btn btn-n-primaria w-100 btn-nown-style btnFinalizar animate__animated animate__fadeIn" disabled>${this.extra.txtFinalizarPedido}</button>
                    </div>
                   
                    <div class="d-none">
                        <a class="btn btn-n-primaria d-block w-100 btn-nown-style btnVerCatalogo animate__animated animate__fadeIn" disabled>Ver Produto</a>
                    </div>
                </div>
            </div>
            <div class="conteudo mensagens d-none">
              
            </div>
            </div>
        `
        this.container.appendChild(div)
        
        
        if(this.configs.visible){

        
        this.observador(this.container, (isVisible) => {
            if (isVisible) {
                this.fixador.bind(this)(0);
                
            } else {
                
                this.fixador.bind(this)(1);
                

                
            }
        });
            
        }
        
        this.map = {
            btns: {
                procurarCep : this.container.getElementsByClassName("btnCep")[0],
                comprar: this.container.getElementsByClassName("btnComprar")[0],
                addcarrinho: this.container.getElementsByClassName("btnAddCarrinho")[0],
                vercarrinho: this.container.getElementsByClassName("btnVerCarrinho")[0],
                finalizar: this.container.getElementsByClassName("btnFinalizar")[0],
                cancelaFrete: this.container.getElementsByClassName("cancelaFrete")[0],
                cancelaCarrinho: this.container.getElementsByClassName("cancelaCarrinho")[0],
                catalogo: this.container.getElementsByClassName("btnVerCatalogo")[0]
            },
            divs:{
                preco: this.container.getElementsByClassName("preco")[0],
                btns: this.container.getElementsByClassName("btns")[0],
                inteiro: this.container.getElementsByClassName("inteiro")[0],
                centavo: this.container.getElementsByClassName("centavo")[0],
                parcela: this.container.getElementsByClassName("parcela")[0],
                parcelas: this.container.getElementsByClassName("parcela")[0].getElementsByClassName("quantidade")[0],
                parcelasvalor: this.container.getElementsByClassName("parcela")[0].getElementsByClassName("valor")[0],
                condicao: this.container.getElementsByClassName("condicao")[0],
                quantificador: this.container.getElementsByClassName("divQuantificador")[0],
                quantidadeItens : this.container.getElementsByClassName("divQuantificador")[0].getElementsByClassName("quantidade")[0],
                quantidadeItensPlus : this.container.getElementsByClassName("divQuantificador")[0].getElementsByClassName("quantidadePlus")[0],
                buscacep : this.container.getElementsByClassName("buscacep")[0],
                ul: this.container.getElementsByClassName("list-group")[0],
                resumoProduto: this.container.getElementsByClassName("doProduto")[0],
                mensagens: this.container.getElementsByClassName("mensagens")[0],
                top: this.container.getElementsByClassName("top")[0],
                listacep: this.container.getElementsByClassName("listFretes")[0]
            }
        }
        evento(this.map.divs.buscacep, "focus", ()=>{
            this.map.divs.buscacep.placeholder = "Digite seu CEP"
        })
        
        evento(this.map.divs.buscacep, "blur", ()=>{
            this.map.divs.buscacep.placeholder = "Calcular Frete"
        })
        
        evento(this.map.divs.quantidadeItens, "change", this.quantificou.bind(this))
        evento(this.map.divs.quantidadeItensPlus , "input", this.quantificouPlus.bind(this))
        evento(this.map.divs.quantidadeItensPlus , "blur", this.desfoquequantificouPlus.bind(this))
       
        evento(this.map.btns.cancelaFrete, "click", this.cancelaFrete.bind(this))
        evento(this.map.btns.cancelaCarrinho, "click", this.cancelaCarrinho.bind(this))
    }
    
    init() {
        if(this.container){
            this.preRender.bind(this)();
        }


    this.selecionados = 1;

    return new Promise((resolve, reject) => {
        let request = new Request("conteudo/modulos/pagamento/admins/produto.php");
        request.addData({
            "acao": "info",
            "produto": this.id
        });

        request.send().then((r) => {
            if(this.container){
                this.render.bind(this)(r);
            }
             
            resolve(r, this);
        }).catch((r) => {
            //this.container.innerHTML = "";
            reject(r);
        });
    });
}

    onlybuttons(){
       // this.container.innerHTML = "";
        this.container.appendChild(this.map.divs.btns)
    }
    
    render(r){
        
        for(let c in r.config){
            this.configs[c] = r.config[c]
        }
        
        
        
 
        this.carrinho = r.carrinho
        this.produto = r.produto
        
        
        if(!this.parcelas){
            this.parcelas = 0;
            
            if(this.configs?.parcelas?.ativo == "true" && this.configs?.parcelas.tamanho){
                this.parcelas = parseInt(this.configs?.parcelas.tamanho)
            }
        }
        

        
        if(!this.parcelas && this.map?.divs?.condicao){
            this.map.divs.condicao.remove();
        }

        
        /* Fluxo q controla os preços */
        var floatado = parseFloat(this.produto.preco)
        if(this.produto.preco && this.produto.preco != "0" && floatado){
            this.resumo.preco = paraPreco(this.produto.preco);
            var trato = this.produto.preco.split(".");
            if(trato.length == 1){
            
            }else{
                if(this.map){
                   this.map.divs.inteiro.innerText = trato[0]
                   this.map.divs.centavo.innerText = `.${trato[1]}` 
                }
                
            }
            
            

            var preco = paraPreco(parseFloat(this.produto.preco) / this.parcelas);
            
            if(this.map && this.parcelas){
               this.map.divs.parcelas.innerHTML = this.parcelas
               this.map.divs.parcelasvalor.innerHTML = preco 
            }else{
                if(this.map?.divs?.parcela){
                    this.map.divs.parcela.remove();
                }
                
            }
            
            
            
        }else{
            this.resumo.preco = "Grátis";
            if(this.map){
                this.map.divs.preco.innerHTML = `<h2 class="fs-20 fs-xl-40 fw-700 m-0">GRÁTIS</h2>`
                this.map.divs.parcela.remove();
                this.map.divs.condicao.remove();
                this.map.divs.quantificador.remove();
                this.produto.zera = true;
                if(this.extra.txtComprar == "Comprar"){
                    this.map.btns.comprar.innerText = "Obter Grátis"
                }
            }
        }
        

        /* Fluxo q controla frete */
        if(parseInt(this.produto.tipo) == 2 && !this.catalog){
            
        var request = new Request(`${dominio}/conteudo/modulos/fretes/admins/api.php`);
        request.addData({
             "acao":"transportadoras"
        });
        request.send().then((r)=>{
          
            var zu = 0;
            while(zu < r.lista.length){
                var tra = r.lista[zu]
              
                this.transportadoras[tra.name] = tra;
                zu++;
            }
        }, (r)=>{
            console.log(r)
        })
            
            if(this.map){
                new Mascaras([this.map.divs.buscacep]);
            evento(this.map.btns.procurarCep , "click", this.procurarCep.bind(this))
            evento(this.map.divs.buscacep , "input", ()=>{
                if(this.map.divs.buscacep.value.length == 9){
                    this.procurarCep.bind(this)();
                }
            })
            
            if(pegaLocal("lastcep")){
                this.map.divs.buscacep.value = pegaLocal("lastcep");
                this.procurarCep.bind(this)();
            } 
            }
           
            
            
        }else{
            if(this.map){
               this.map.divs.buscacep.closest(".conteudo").remove(); 
            }
        }
        
        
        
        let showAddCarrinho = true;
        let showComprar = true;
        let showVerCarrinho = true;
        let showFinalizar = true;
        
        /*Fluxo Limita Venda */
        this.maximo = 999;
        if(this.produto.limitador){
            if(this.map){
                 this.map.divs.quantidadeItens.value = 1;
                 this.map.divs.quantidadeItens.setAttribute("disabled", "")
                 this.map.divs.quantidadeItensPlus.remove();
                 this.map.divs.quantidadeItens.classList.add("limitador")
            }
           
            
            if(this.carrinho[this.produto.id]){
                this.carrinho[this.produto.id] = 1;
            }
            
            
        }else{
        
            if(this.produto.estoque){
                if(!this.produto.metas.estoque || this.produto.metas.estoque == "0"){
                    this.maximo = 0;
                    showAddCarrinho = false;
                    showComprar = false;
                    showVerCarrinho = false;
                    showFinalizar = false;
                    
                    this.novaMensagem.bind(this)(`<h2 class="text-center fs-24 fw-700 text-danger">Produto Indiponível</h2><p class="text-center m-0">Produto fora de estoque</p>`)
                    if(this.map){
                        this.map.divs.ul.remove();
                        this.map.divs.top.remove();
                        this.map.divs.quantificador.remove();
                        this.map.divs.btns.parentNode.remove();
                        this.map.divs.buscacep.closest(".conteudo").remove();
                    }

                }else{
                    this.maximo = parseInt(this.produto.metas.estoque)
                }
            }
        }
        
        if(this.carrinho[this.produto.id] && this.maximo < this.carrinho[this.produto.id]){
            this.carrinho[this.produto.id] = this.maximo;
        }
        
        if(this.map){
             var i = 0;
        while(i < this.maximo + 1){
            if(i > 1){
                 let option = document.createElement("OPTION")
                 option.innerText = i < 6 ? i : "+"
                 this.map.divs.quantidadeItens.appendChild(option) 
            }
            

            i++;
            
            if(i == 7){
                break;
            }
        }
        }
       
        
        if(this.produto.zera){
            showVerCarrinho = false;
            delete this.carrinho[this.produto.id];
            showAddCarrinho = false;
            showFinalizar = false;
            //this.map.divs.quantificador.remove();
            this.selecionados = 1;
            
            if(this.map){
                this.card.classList.add("emLinha")
            }
            
            
        }
        

        if(this.produto.recorrente){
            delete this.carrinho[this.produto.id];
            showVerCarrinho = false;
            showFinalizar = false;
            showAddCarrinho = false;
            
            if(this.map){
                 this.map.btns.comprar.innerHTML = this.extra.txtAssinar
                 this.card.classList.add("emLinha")
                 
                 this.map.divs.quantificador.remove();
                 var ciclo = this.produto.metas?.ciclo || "mês";
                 this.map.divs.parcela.innerHTML = `por ${ciclo}`
                 this.map.divs.condicao.remove();
            }
           
            
            
        }
        
        if(this.carrinho[this.produto.id]){
            this.selecionados = this.carrinho[this.produto.id];
            if(this.map){
                this.resumoItem.bind(this)(this.carrinho[this.produto.id])
            }
            
            
            
            showComprar = false;
            showAddCarrinho = false;

        }
        
        
        if(!this.map){
            this.map = {
                btns: {
                    comprar: this.container.getElementsByClassName("btnComprar")[0],
                    addcarrinho: this.container.getElementsByClassName("btnAddCarrinho")[0],
                    vercarrinho: this.container.getElementsByClassName("btnVerCarrinho")[0],
                    finalizar: this.container.getElementsByClassName("btnFinalizar")[0],
                }
            }
        }
        
        
        
        evento(this.map.btns.comprar, "click", this.paraCheckout.bind(this))
        
        if(floatado){
            evento(this.map.btns.addcarrinho, "click", this.addNoCarrinho.bind(this))
        }else{
            this.map.btns.addcarrinho.classList.add("d-none")
            if(this.extra.txtComprar == "Comprar"){
                this.map.btns.comprar.innerText = "Obter Grátis"
            }
            
        }
        
        
        evento(this.map.btns.vercarrinho, "click", this.paraCarrinho.bind(this))
        evento(this.map.btns.finalizar, "click", this.paraCheckout.bind(this))
        
         if(!this.catalog){
             
        if(showComprar){
            showFinalizar = false;
        }
        
        if(showAddCarrinho){
            showVerCarrinho = false;
        }
        
        if(showAddCarrinho){
            this.ativaBtn.bind(this)(this.map.btns.addcarrinho);
        }
        
        if(showComprar){
            this.ativaBtn.bind(this)(this.map.btns.comprar);
        }
        
        if(showVerCarrinho){
            this.ativaBtn.bind(this)(this.map.btns.vercarrinho);
        }
        
        if(showFinalizar){
            this.ativaBtn.bind(this)(this.map.btns.finalizar);
        }
        
       
            
        }else{
            if(this.catalogLink){
                this.ativaBtn.bind(this)(this.map.btns.catalogo);
                this.map.btns.catalogo.href = this.catalogLink
            }
            
            
        
        }
        
       

    }
    
    observador(element, callback) {
  // Configuração do observador
  const observerOptions = {
    root: null, // Usar o viewport como root
    rootMargin: '0px',
    threshold: 0.1 // Chama o callback quando 10% do elemento está visível
  };

  // Função de callback para o observador
  const observerCallback = (entries, observer) => {
    entries.forEach(entry => {
      // Chama a função de callback passada como argumento
      callback(entry.isIntersecting);
    });
  }
     const observer = new IntersectionObserver(observerCallback, observerOptions);

  // Observar o elemento
  observer.observe(element);
  
  
    }
    
    fixador(visivel){
        if(visivel){
             this.card.classList.add("rodapeia")
             this.card.classList.remove("sticky-top")
        }else{
            this.card.classList.remove("rodapeia")
            if(this.configs.stick){
                this.card.classList.add("sticky-top")
            }
            
        }
    }
    
    novaMensagem(html){
        if(this.map?.divs?.mensagens || false){
            this.map.divs.mensagens.classList.remove("d-none")
            this.map.divs.mensagens.innerHTML = html
        }
    }
    
    quantificouPlus(){
        let quantidade = this.map.divs.quantidadeItensPlus.value;
        if(quantidade){
            quantidade = parseInt(quantidade)
            if(quantidade > this.maximo){
                this.map.divs.quantidadeItensPlus.value = this.maximo
                quantidade = this.maximo
            }
        }else{
            quantidade = 1;
        }
        
        this.selecionados = quantidade
        
        
    }
    
    quantificou(){
        var valor = this.map.divs.quantidadeItens.value
        if(valor == "+"){
            this.map.divs.quantidadeItensPlus.classList.remove("d-none")
            this.map.divs.quantidadeItens.classList.add("d-none")
            this.selecionados = 6;
        }else{
            this.selecionados = parseInt(valor)
        }
    }
    
    desfoquequantificouPlus(){
          var valor = parseInt(this.map.divs.quantidadeItensPlus.value)
          if(valor < 6){
              this.map.divs.quantidadeItens.value = valor
              this.map.divs.quantidadeItensPlus.value = 6;
              this.map.divs.quantidadeItensPlus.classList.add("d-none")
              this.map.divs.quantidadeItens.classList.remove("d-none")

          }
    }
    
    ativaBtn(btn){
        var pai = btn.parentNode
        btn.removeAttribute("disabled")
        pai.classList.remove("d-none")
    }
    
    desativaBtn(btn){
         var pai = btn.parentNode
        btn.setAttribute("disabled", "")
        pai.classList.add("d-none")
    }
    
    desativaBuscaCep(){
        this.map.btns.procurarCep.setAttribute("disabled", "")
        this.map.divs.buscacep.setAttribute("disabled", "")
        this.map.btns.procurarCep.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        `
    }
    
    ativaBuscaCep(){
        this.map.btns.procurarCep.removeAttribute("disabled")
        this.map.divs.buscacep.removeAttribute("disabled")
        this.map.btns.procurarCep.innerHTML = `<i class="bi bi-search"></i>`
    }
    
    procurarCep(){
        this.desativaBuscaCep.bind(this)();
        if(this.map.divs.buscacep.value.length != 9){
            this.ativaBuscaCep.bind(this)();
            iziToast.error({
                icon: 'bi bi-geo-alt-fill',
                title: 'Atenção',
                message: 'O CEP digitado é inválido'
            });
            return;
        }
        
        defineLocal("lastcep", this.map.divs.buscacep.value);
        this.resumoFrete.bind(this)(this.map.divs.buscacep.value)
        
        
    }
    
    resumoFrete(cep){
        if(this.map.divs.ul.parentNode){
           this.map.divs.ul.parentNode.classList.remove("d-none")
           this.map.divs.buscacep.closest(".conteudo").classList.add("d-none") 
        }
        
        
        var request = new Request(`${dominio}/conteudo/modulos/fretes/admins/api.php`);
        request.addData({
             "produto":"5",
             "acao":"calcular-frete",
             "destino":cep
        });
        request.send().then((r) => {
   
    var lista = r.opcoes_frete;
    var i = 0;
    var fragmento = document.createDocumentFragment();
    
   
    
    // Filtrar apenas opções sem erro
    var opcoes_validas = lista.filter(item => !item.possui_erro);
    
    // Encontrar o mais barato e o mais rápido
    var mais_barato = null;
    var mais_rapido = null;
    
    if (opcoes_validas.length > 0) {
        // Encontrar o mais barato
        mais_barato = opcoes_validas.reduce((prev, current) => {
            return (parseFloat(prev.preco) < parseFloat(current.preco)) ? prev : current;
        });
        
        // Encontrar o mais rápido (menor prazo máximo)
        mais_rapido = opcoes_validas.reduce((prev, current) => {
            return (parseInt(prev.prazo_range.max) < parseInt(current.prazo_range.max)) ? prev : current;
        });
    }
    
    while(i < lista.length) {
        var item = lista[i];
        if(!item.possui_erro) {
            var transportadora = this.transportadoras[item.empresa];
            console.log(transportadora);
            
            var div = document.createElement("DIV");
            div.classList.add("list-group-item", "list-group-item-action", "shipping-item");
            
            // Verificar se é o mais barato ou mais rápido
            var isMaisBarato = mais_barato && item.empresa === mais_barato.empresa && 
                              parseFloat(item.preco) === parseFloat(mais_barato.preco);
            var isMaisRapido = mais_rapido && item.empresa === mais_rapido.empresa && 
                              parseInt(item.prazo_range.max) === parseInt(mais_rapido.prazo_range.max);
            
            // Criar badges
            var badges = '';
            if (isMaisBarato) {
                badges += '<span class="badge bg-success shipping-badge"><i class="bi bi-currency-dollar"></i> Mais barato</span>';
            }
            if (isMaisRapido) {
                badges += '<span class="badge bg-primary shipping-badge"><i class="bi bi-lightning-fill"></i> Mais rápido</span>';
            }
            
            // Adicionar classe especial se for destaque
            var extraClass = '';
            if (isMaisBarato || isMaisRapido) {
                extraClass = ' border-warning';
                div.style.borderLeftColor = isMaisBarato ? '#28a745' : '#007bff';
                div.style.borderLeftWidth = '4px';
                div.style.borderLeftStyle = 'solid';
            }
            
            div.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="badge-container">
                            <div class="company-logo${extraClass}">
                                <img src="${transportadora.picture}" alt="${item.empresa}" style="width: 50px">
                            </div>
                            ${(isMaisBarato || isMaisRapido) ? '<div class="price-badge"><i class="bi bi-star-fill text-warning"></i></div>' : ''}
                        </div>
                    </div>
                    <div class="col">
                        <div class="company-name">${item.empresa}</div>
                        <small class="text-muted">Entrega ${item.nome}</small>
                        ${badges}
                    </div>
                    <div class="col-auto text-end">
                        <div class="price">${paraPreco(item.preco)}</div>
                        <div class="delivery-time">
                            <i class="bi bi-clock"></i>
                            de ${item.prazo_range.min} a ${item.prazo_range.max} dias
                        </div>
                    </div>
                </div>
            `;
            
            // Adicionar evento de clique para seleção
            div.addEventListener('click', function() {
                // Remove seleção anterior
                document.querySelectorAll('.shipping-item').forEach(shippingItem => {
                    shippingItem.classList.remove('selected');
                });
                
                // Adiciona seleção ao item clicado
                this.classList.add('selected');
                
                // Pequena animação de feedback
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Aqui você pode adicionar lógica para capturar a seleção
                console.log('Frete selecionado:', item);
            });
            
            fragmento.appendChild(div);
        }
        i++;
    }
    
    var destino = r.destino
    console.log(destino)
    // Limpar e adicionar nova lista
    this.map.divs.listacep.innerHTML = "";
    
    // Adicionar card com dados do CEP no topo
    var cardCep = document.createElement('div');
    cardCep.className = 'alert alert-info mb-3 border-0 shadow-sm';
    cardCep.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center flex-wrap">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                <span class="fw-bold me-3">${destino.cep || 'CEP não informado'}</span>
                <span class="text-muted me-3">${destino.logradouro || 'Endereço não informado'}</span>
                <span class="text-muted">${destino.localidade || 'Cidade'}, ${destino.uf || 'Estado'}</span>
            </div>
            <button type="button" class="btn-close" 
                    
                    title="Remover endereço">
            </button>
        </div>
    `;
    evento(cardCep.getElementsByTagName("button")[0], "click", this.cancelaFrete.bind(this))
    this.map.divs.listacep.closest(".conteudo").classList.remove("d-none")
    this.map.divs.listacep.appendChild(cardCep);
    
    // Adicionar título se houver opções
    if (opcoes_validas.length > 0) {
        var titulo = document.createElement('div');
        titulo.className = 'mb-3 text-center';
        titulo.innerHTML = `
            <h5 class="text-center">
                <i class="bi bi-truck text-primary me-2"></i>
                Opções de Frete Disponíveis
            </h5>
            <small class="text-muted">
                ${opcoes_validas.length} opção${opcoes_validas.length > 1 ? 'ões' : ''} encontrada${opcoes_validas.length > 1 ? 's' : ''}
            </small>
        `;
        this.map.divs.listacep.appendChild(titulo);
    }
    
    this.map.divs.listacep.appendChild(fragmento);
    
    // Adicionar resumo no final se houver opções
    if (opcoes_validas.length > 0) {
        var resumo = document.createElement('div');
        resumo.className = 'mt-3 p-3 bg-light rounded';
        resumo.innerHTML = `
            <div class="row text-center">
                <div class="col-6">
                    <small class="text-muted d-block">Mais barato</small>
                    <strong class="text-success">${mais_barato ? mais_barato.empresa + ' - ' + paraPreco(mais_barato.preco) : 'N/A'}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Mais rápido</small>
                    <strong class="text-primary">${mais_rapido ? mais_rapido.empresa + ' - ' + mais_rapido.prazo_range.max + ' dias' : 'N/A'}</strong>
                </div>
            </div>
        `;
        this.map.divs.listacep.appendChild(resumo);
    }
}, (r)=>{
            console.log(r)
        })
        /*
         <div type="button" class="list-group-item list-group-item-action " >
    The current button
  </div>
        */
        
    }
    
    resumoItem(quantidade){
        if(this.map.divs.ul.parentNode){
            this.map.divs.ul.parentNode.classList.remove("d-none")
            this.map.divs.resumoProduto.classList.remove("d-none")
            
            this.map.divs.resumoProduto.getElementsByClassName("quantidade")[0].innerText = quantidade
            this.map.divs.resumoProduto.getElementsByClassName("subtotal")[0].innerText = paraPreco(parseFloat(this.produto.preco) * quantidade)
            this.map.divs.quantificador.classList.add("d-none")
        }
    }
    
    paraCheckout(){

         let request = new Request("conteudo/modulos/pagamento/admins/produto.php");
          request.addData({"acao":"fechaUnico", "produto" : this.id, "quantidade": this.selecionados})
          request.send().then((r)=>{
              goUrl(`pagamento/${r.url}`)
          }, (r)=>{
              if(r.mensagem == "Usuário não está logado"){
                  goUrl(`restrito${window.location.href.split(dominio)[1]}`)
              }
          })

    }
    
    paraCheckoutComCarrinho(){
        console.log("fechar o carrinho")
    }
    
    paraCarrinho(){
        console.log(this)
        if(this.configs.onlybutton){
            this.cancelaCarrinho.bind(this)();
            return;
        }
        goUrl("carrinho");
    }
    
    addNoCarrinho(){
        if(this.map.divs){
            this.resumoItem.bind(this)(this.selecionados);
        }
        
        this.statusControler(2);
        
        let request = new Request("conteudo/modulos/pagamento/admins/produto.php");
          request.addData({"acao":"addCard", "produto" : this.id, "quantidade": this.selecionados})
          request.send().then((r)=>{
              this.update.bind(this)();
          }, (r)=>{
              if(r.mensagem == "Usuário não está logado"){
                  goUrl(`restrito${window.location.href.split(dominio)[1]}`)
              }
          })
        
        
    }
    
    cancelaFrete(){

        if(this.map.divs.resumoProduto.classList.contains("d-none")){
            this.map.divs.ul.parentNode.classList.add("d-none")
        }
        
        this.map.divs.buscacep.closest(".conteudo").classList.remove("d-none")
        this.ativaBuscaCep.bind(this)()
        this.map.divs.buscacep.value = ""
        removeLocal("lastcep");
        this.map.divs.listacep.innerHTML = "";
    }
    
    cancelaCarrinho(){
         if(this.map.divs){
           
            this.map.divs.resumoProduto.classList.add("d-none")
            this.map.divs.resumoProduto.getElementsByClassName("quantidade")[0].innerText = ""
            this.map.divs.resumoProduto.getElementsByClassName("subtotal")[0].innerText = ""
            this.map.divs.quantificador.classList.remove("d-none")
            
            this.map.divs.quantidadeItensPlus.value = 1;
            this.desfoquequantificouPlus.bind(this)();
         }
       
        this.statusControler.bind(this)(1)
        
        
        this.selecionados = 1;

          let request = new Request("conteudo/modulos/pagamento/admins/produto.php");
          request.addData({"acao":"remover", "produto" : this.id})
          request.send().then((r)=>{
              this.update.bind(this)()
          })

        
    }
    
    statusControler(status){
        switch(status){
        case 1:
            // Estado Inicial - btn carrinho + btn comprar
            
            if(this.produto.zera){
                 this.desativaBtn.bind(this)(this.map.btns.addcarrinho);
            }else{
                this.ativaBtn.bind(this)(this.map.btns.addcarrinho);
            }
            
            this.ativaBtn.bind(this)(this.map.btns.comprar);
            
            
            this.desativaBtn.bind(this)(this.map.btns.vercarrinho);
            this.desativaBtn.bind(this)(this.map.btns.finalizar);
            break;
        case 2:
            // Com item no carrinho
            
            
            this.ativaBtn.bind(this)(this.map.btns.vercarrinho);
            this.ativaBtn.bind(this)(this.map.btns.finalizar);
            
            this.desativaBtn.bind(this)(this.map.btns.comprar);
            this.desativaBtn.bind(this)(this.map.btns.addcarrinho);
            break;
        case 3:
            break;
        case 4:
            break;
        }
        
        
     
        
        
    }
    
    update(){
        start.carrinho.update();
    }
}


/*
function paginaCarrinhonovo(){
    var card = new FuturoCarrinho(
        17,
        document.getElementById("boxProduto"), 
        {
            stick: true,
            onlybutton: false,
        },
        {img:true, txts: [
            {texto: "ola mundo", tipo: "h2", classe:"text-center fs-18 m-0"},
            {texto: "Ola felipe meu amigo", tipo: "p", classe:"text-center fs-12 m-0"}
            ]
        });
    card.init().then((r)=>{
        console.log(r)
    });
    

}

*/