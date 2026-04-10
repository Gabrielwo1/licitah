class CardProduto {
    constructor(id, container) {

        this.id = id;
        this.container = container;

        this.ajax = this.ajax.bind(this)


        this.prerender.bind(this)()

    }

    cep(){
        const divInputGroup = document.createElement('div');
        divInputGroup.classList.add('input-group', 'mb-4');

        const spanCEP = document.createElement('span');
        spanCEP.classList.add('btn', 'btn-n-primaria', 'btn-nown-style');
        spanCEP.setAttribute('id', 'basic-addon1');
        spanCEP.textContent = 'CEP';

        const inputCalcularFrete = document.createElement('input');
        inputCalcularFrete.setAttribute('type', 'text');
        inputCalcularFrete.classList.add('form-control');
        inputCalcularFrete.setAttribute('placeholder', 'Calcular Frete');
        inputCalcularFrete.setAttribute('aria-label', 'Username');
        inputCalcularFrete.setAttribute('aria-describedby', 'basic-addon1');
        
         divInputGroup.appendChild(spanCEP);
        divInputGroup.appendChild(inputCalcularFrete);
        
        var componenteCompleto = document.createElement("DIV")
        
        componenteCompleto.appendChild(divInputGroup);
        
        this.body.appendChild(componenteCompleto)
    }
    
    controleMax(){
        var valor = event.currentTarget.value
        if(valor > this.limite){
            event.currentTarget.value = this.limite
            alert(`Você so pode comprar no máximo: ${this.limite}`)
        }
        if(valor == "0" || !valor){
            event.currentTarget.value = 1;
        }
    }
    
    quantifica(){
        if(this.inputQuantidade.value == 0){
            var pai = this.inputQuantidade.closest("DIV")
            this.inputQuantidade.remove();
            this.inputQuantidade = document.createElement("INPUT")
            this.inputQuantidade.type = "number"
            this.inputQuantidade.min = 1
            this.inputQuantidade.max = this.limite
            this.inputQuantidade.classList.add("form-control")
            evento(this.inputQuantidade, "INPUT", this.controleMax.bind(this))
            this.inputQuantidade.value = 6
            pai.appendChild(this.inputQuantidade)
        }
    }
    
    quantidade(){
        if(this.produto.estoque){
            this.limite = this.produto.metas.estoque ? parseInt(this.produto.metas.estoque) : 0;
        }else{
            this.limite = 99999999999999999999;
        }
        
        if(this.limite > 0){
            const divDflex = document.createElement('div');
        divDflex.classList.add('d-flex', 'gap-4', 'justify-content-between', 'align-items-center');

        const divLabelQuantidade = document.createElement('div');
        const labelQuantidade = document.createElement('label');
        labelQuantidade.textContent = 'Quantidade';
        divLabelQuantidade.appendChild(labelQuantidade);

        const divSelectWrapper = document.createElement('div');
        divSelectWrapper.classList.add('w-100');

        this.inputQuantidade = document.createElement('select');
        this.inputQuantidade.classList.add('form-select', 'border-top-0', 'border-start-0', 'border-end-0', 'rounded-0');
        evento(this.inputQuantidade, "INPUT", this.quantifica.bind(this))
       
        
        var i = 1;
        while(i < 6){
            if(i < this.limite + 1){
               const optionUnidade = document.createElement('option');
               optionUnidade.setAttribute('value', i);
               optionUnidade.textContent = `${i} unidade`;
               this.inputQuantidade.appendChild(optionUnidade); 
            }
            
            
            i++;
        }
        
        if(this.limite > 5){
             const optionUnidade = document.createElement('option');
             optionUnidade.setAttribute('value', 0);
             optionUnidade.textContent = `Outra quantidade`;
             this.inputQuantidade.appendChild(optionUnidade); 
        }
        

       

        divSelectWrapper.appendChild(this.inputQuantidade);

        divDflex.appendChild(divLabelQuantidade);
        divDflex.appendChild(divSelectWrapper);

    
        this.body.appendChild(divDflex)
        }
        else{
            this.footer.remove();
            var botao = document.createElement("BUTTON")
            botao.classList.add("btn","btn-danger", "w-100")
            botao.innerText = "Fora de Estoque"
            
            var p = document.createElement("P")
            p.classList.add("my-2", "fs-12", "text-center")
            p.innerText = "Infelizmente não temos mais esse produto disponível."
            this.body.appendChild(botao)
            this.body.appendChild(p)
        }
        
    }
    
    prerender() {
        var ja = document.getElementById("cardProductPrice");
        if(ja){
            ja.classList.add("d-none")
            document.getElementById("cardProductPrice").id = "oldCard"
        }
        


        
        this.card = document.createElement("DIV")
        this.card.classList.add("card", "card-nown")
        this.card.id = "cardProductPrice"

        var header = document.createElement("DIV")
        header.classList.add("card-header", "bg-transparent", "border-0", "p-4")

        const divPrincipal = document.createElement('div');
        divPrincipal.classList.add('d-flex', 'justify-content-center');

        const divValores = document.createElement('div');
        divValores.classList.add('d-flex', 'justify-content-center', 'gap-2', 'align-items-start');

        this.cifra = document.createElement('span');
        this.cifra.classList.add('fs-20', 'fw-700');
        this.cifra.textContent = 'R$';

        const divValoresSecundarios = document.createElement('div');
        divValoresSecundarios.classList.add('d-flex', 'justify-content-start', 'align-items-end');

        this.reais = document.createElement('span');
        this.reais.classList.add('fs-50', 'fw-700');
        this.reais.style.lineHeight = "50px"
        this.reais.innerHTML = `<div class="bg-carregando he-50 wi-50"></div>`

        this.centavos = document.createElement('span');
        this.centavos.classList.add('fs-20', 'fw-700');
        this.centavos.innerHTML = '<span class="bg-carregando wi-20 he-20"></span>';

        const divParcelamento = document.createElement('div');

        const divParcelamentoTexto = document.createElement('div');
        divParcelamentoTexto.classList.add('fs-14', 'text-center', 'fw-300');
        divParcelamentoTexto.textContent = 'ou parcele em';

        const divParcelamentoValor = document.createElement('div');
        divParcelamentoValor.classList.add('fw-700', 'fs-20', 'text-center');
        divParcelamentoValor.innerHTML = '<span class="bg-carregando wi-150 he-20"></span>';
        this.parcelamento = divParcelamentoValor;

        divValores.appendChild(this.cifra);
        divValoresSecundarios.appendChild(this.reais);
        divValoresSecundarios.appendChild(this.centavos);
        divValores.appendChild(divValoresSecundarios);

        divParcelamento.appendChild(divParcelamentoTexto);
        divParcelamento.appendChild(divParcelamentoValor);

  
        divPrincipal.appendChild(divValores);
 
        header.appendChild(divPrincipal)
        header.appendChild(divParcelamento);

        this.card.appendChild(header)

        this.body = document.createElement("DIV")
        this.body.classList.add("card-body", "p-4")
        
        this.card.appendChild(this.body)

        this.footer = document.createElement('div');
        this.footer.classList.add('card-footer', 'bg-transparent', 'border-0', "p-4");

        const divFlexColumn = document.createElement('div');
        divFlexColumn.classList.add('d-flex', 'flex-xl-column', 'gap-3');

     
    
        const paragraph = document.createElement('p');
        paragraph.classList.add('my-2', 'text-center', 'fs-12', 'd-none', 'd-xl-block');
        paragraph.textContent = 'Compre agora e aproveite';

        this.footer.appendChild(divFlexColumn);
        this.footer.appendChild(paragraph);
        this.card.appendChild(this.footer)


        if (this.container) {
            this.container.appendChild(this.card);
        }


        var data = new FormData();
        data.append("acao", "info")
        data.append("produto", this.id)

        this.ajax(data, this.render.bind(this))
    }
    
    btnCarrinho(){
    
        this.bCarrinho = document.createElement('button');
        this.bCarrinho.classList.add('btn', 'btn-n-secundaria', 'w-100', 'btn-nown-style', 'd-flex', 'justify-content-center', 'gap-2');
        evento(this.bCarrinho , "click", this.carrinhar.bind(this))

        const spanIcone = document.createElement('span');
        spanIcone.classList.add('d-block', 'd-xl-none');
        spanIcone.innerHTML = '<i class="bi bi-cart-check"></i>';

        const spanTextoCarrinho = document.createElement('span');
        spanTextoCarrinho.classList.add('d-none', 'd-xl-block');
        spanTextoCarrinho.textContent = 'Adicionar ao ';

        const spanCarrinho = document.createElement('span');
        spanCarrinho.textContent = 'Carrinho';


        this.bCarrinho.appendChild(spanIcone);
        this.bCarrinho.appendChild(spanTextoCarrinho);
        this.bCarrinho.appendChild(spanCarrinho);

        if(this.footer){
            this.footer.getElementsByClassName("d-flex")[0].appendChild(this.bCarrinho);
        }
    }
    
    addCarrinho(botao, redir){
        if(this.bCarrinho){
           this.bCarrinho.setAttribute("disabled", "") 
        }
        
        if(this.bComprar){
           this.bComprar.setAttribute("disabled", "") 
        }
        
        botao.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`
        this.redir = redir;
        
        var data = new FormData();
        data.append("acao", "addCard")
        data.append("produto", this.id);
        data.append("fecha", this.redir)
        data.append("quantidade", this.inputQuantidade ? this.inputQuantidade.value : 1);
        this.ajax(data, this.processa.bind(this))
    }
    
    processa(r){
        if(r.sucesso){
            start.carrinho.update();
            if(r.status == 0){
                new Restrito();
            }
            
            if(r.status == 1){
                if(this.bCarrinho){
                     this.bCarrinho.removeAttribute("disabled", "")
                this.bCarrinho.innerHTML = `
                <span class="d-block d-xl-none">
                    <i class="bi bi-cart-check"></i>
                </span>
                <span class="d-none d-xl-block">Adicionar ao </span>
                <span>Carrinho</span>
                ` 
                }
              
                this.bComprar.removeAttribute("disabled", "")
                this.bComprar.innerText = "Comprar"
                if(this.redir){
                    if(r.pedido){
                        goUrl(`pagamento/${r.pedido}`);
                    }

                }else{
                    this.container.innerHTML = "";
                    this.prerender.bind(this)()
                }

            }
        }
    }
    
    comprar(){
       this.addCarrinho.bind(this)(event.currentTarget, true)
    }
    
    carrinhar(){
         this.addCarrinho.bind(this)(event.currentTarget, false)
    }
    
    btnComprar(){
        this.bComprar = document.createElement('button');
        this.bComprar.classList.add('btn', 'btn-n-primaria', 'w-100', 'btn-nown-style');
        this.bComprar.textContent = 'Comprar';
        evento(this.bComprar, "click", this.comprar.bind(this))
        this.footer.getElementsByClassName("d-flex")[0].appendChild(this.bComprar);
    }
    
    removeCarrinho(){
        event.currentTarget.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`
        var data = new FormData();
        data.append("acao", "remover")
        data.append("produto", this.id)

        this.ajax(data, this.removido.bind(this))
    }
    
    removido(r){
        if(r.sucesso){
            start.carrinho.update();
            this.container.innerHTML = "";
            this.prerender.bind(this)()
        }
    }
    
    carrinhado(q){
        var calc = q * parseFloat(this.produto.preco)

        var lista = document.createElement("UL")
        lista.classList.add("list-group","list-group-flush")
        var li = document.createElement("LI")
        li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center")
        
        var span = document.createElement("DIV")
        span.classList.add("fw-500", "fs-14")
        
        
        var div = document.createElement("div")
        div.innerHTML = `<span class="fw-700">${q}</span> itens no carrinho`
        
        var div2 = document.createElement("DIV")
        div2.innerHTML = `Subtotal: <span class="fw-700">R$ ${calc}</span>`
      
        span.appendChild(div)
        span.appendChild(div2)
        
        var botao = document.createElement("BUTTON")
        botao.innerHTML= '<i class="bi bi-x"></i>'
        botao.classList.add("btn", "text-danger", "fw-700", "fs-30")
        evento(botao, "click", this.removeCarrinho.bind(this))
        li.appendChild(span)
        li.appendChild(botao)
        
        lista.appendChild(li)
        this.body.appendChild(lista)
        
   
        
        this.bComprar = document.createElement('button');
        this.bComprar.classList.add('btn', 'btn-n-primaria', 'w-100', 'btn-nown-style');
        this.bComprar.textContent = 'Finalizar Pedido';
        evento(this.bComprar, "click", this.finalizar.bind(this))
        this.footer.getElementsByClassName("d-flex")[0].appendChild(this.bComprar);
        
        this.btnIrCarrinho = document.createElement('button');
        this.btnIrCarrinho.classList.add('btn', 'text-primary', 'w-100');
        this.btnIrCarrinho.textContent = 'Ver Carrinho';
        evento(this.btnIrCarrinho, "click", this.carrinhoGo.bind(this))
        this.footer.getElementsByClassName("d-flex")[0].appendChild(this.btnIrCarrinho);
    }
    
    finalizar(){
        this.btnIrCarrinho.setAttribute("disabled", "")
        this.bComprar.setAttribute("disabled", "")
        this.bComprar.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`

        this.redir = true;
        var data = new FormData();
        data.append("acao", "fechaPedido") 
        data.append("produto", 1)
        data.append("fecha", true)
        this.ajax(data, this.processa.bind(this))

    }
    
    carrinhoGo(){
         goUrl("carrinho");
    }

    render(r) {

        if(r.sucesso){
            this.produto = r.produto
            
    
            
            if(this.produto.preco == "0.00"){
                this.reais.innerHTML = `<span class="fs-30">GRATUITO</span>`
                this.centavos.remove();
                this.cifra.remove();
            }else{
                var trato = this.produto.preco.split(".")
                this.reais.innerHTML = trato[0]
                this.centavos.innerHTML = `,${trato[1]}`
                
                var parcelado = paraPreco(parseFloat(this.produto.preco) / 10);
                this.parcelamento.innerText = `10 x de ${parcelado}`
                
                
                if(r.carrinho && r.carrinho[this.id]){
                     this.carrinhado.bind(this)(r.carrinho[this.id]);
                }else{
                    if(!this.produto.limitador){
                    this.quantidade.bind(this)();
                }
                
                this.btnComprar.bind(this)()
                if(!this.produto.zera){
                    this.btnCarrinho.bind(this)()
                }
                }
                
                
                
                
                console.log(this.produto)
                
                 //this.cep.bind(this)();
                 //
                
                
                
                
            }
        }
 
    }

    ajax(data, cb = false) {
        let request = new XMLHttpRequest();
        request.onload = () => {
            try {
                let obj = JSON.parse(request.responseText)
                if (obj.sucesso && cb) {
                    cb(obj)
                } else {
                    console.log(obj)
                }
            } catch (e) {
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/conteudo/modulos/pagamento/admins/produto.php`)
        request.send(data)
    }
}