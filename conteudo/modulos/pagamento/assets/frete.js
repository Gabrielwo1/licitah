class PagamentoFrete{
    constructor(){
        this.map.bind(this)();
        this.init.bind(this)();
        this.enderecoSelecionado = false;
        this.pedido = pegaHash();
    }
    
    cardEndereco(item){
        var div = document.createElement("DIV")
        div.classList.add("endereco-item")
        div.dataset.id = item.hash
        
        var checked = "";
        if(this.enderecoSelecionado == item.hash){
            div.classList.add("selected")
            var checked = "checked";
            this.calcula.bind(this)();
        }
        
        div.innerHTML = `
            <div class="endereco-content">
                <div class="endereco-info">
                    <span class="endereco-tipo">${item.nome}</span>
                    <div class="endereco-detalhes">
                        <div class="endereco-linha1">${item.rua}, ${item.numero} - Apto 45</div>
                        <div class="endereco-linha2">${item.bairro} - São Paulo/SP</div>
                        <div class="endereco-linha3">CEP: ${item.cep}</div>
                    </div>
                </div>
                <div class="endereco-actions">
                    <div class="endereco-radio ${checked}"></div>
                    <button class="btn-editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
            </div>
            <div class="endereco-badge">Selecionado</div>
        `
        evento(div, "click", this.seleciona.bind(this))
        return div;
    }
    
    seleciona(){
        var hash = event.currentTarget.dataset.id;
        var card = event.currentTarget
        var clicado = event.target
        
        var editar = false;
        if(clicado.classList.contains("btn-editar") || clicado.parentNode.classList.contains("btn-editar")){
            editar = true;
        }
        
        
        if(editar){
            alert("tentando editar");
        }else{
            if(hash != this.enderecoSelecionado){
                if(this.enderecoSelecionado){
                    this.cards[this.enderecoSelecionado].classList.remove("selected");
                    this.cards[this.enderecoSelecionado].getElementsByClassName("endereco-radio")[0].classList.remove("checked")
                }
                this.enderecoSelecionado = hash;
                this.calcula.bind(this)();
            }
            card.classList.add("selected");
            card.getElementsByClassName("endereco-radio")[0].classList.add("checked")
        }
    }
    
    calcula(){
        var request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/frete.php`);
        request.addData({acao:"calcula", pedido:this.pedido, endereco: this.enderecoSelecionado})
        request.send().then((r)=>{
            console.log(r)
        })
    }
    
    init(){
        var request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/frete.php`);
        request.addData({acao:"meus"})
        request.send().then((r)=>{
            var lista = r.lista
            if(r.escolhido){
                this.enderecoSelecionado = r.escolhido
            }
            
            var i  = 0;
            var fragmento = document.createDocumentFragment();
            this.cards = {};
            while(i < lista.length){
                var endereco = lista[i];
                
                var card = this.cardEndereco.bind(this)(endereco);
                this.cards[endereco.hash] = card;
                fragmento.appendChild(card)
                
                
                i++;
            }
            document.getElementById("enderecos-lista").appendChild(fragmento)
        }, (r)=>{
            
        })
    }
    
    map(){
        
    }
}