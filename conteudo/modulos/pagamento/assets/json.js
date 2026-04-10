class StatusControlerPedido{
    constructor(){
        
        this.containerStatus = document.getElementById("controlerStatus")
        this.select = document.getElementById("statusSelect").getElementsByClassName("form-select")[0]
        document.getElementById("statusSelect").classList.add("d-none")
        this.status = parseInt(this.select.value);
        

        this.precoDesconto = document.getElementById("precoDesconto").getElementsByClassName("form-control")[0]
        this.precoFrete = document.getElementById("precoFrete").getElementsByClassName("form-control")[0]

        
        this.linhas = [];
        this.init.bind(this)();
        
    }
    
    init(){
        this.render.bind(this)()
    }
    
    linha(texto){
        let li = document.createElement("LI")
        li.classList.add("list-group-item","d-flex","gap-2","align-items-center","list-group-item-action")

        

        
        var aprovado = document.createElement("I")
        aprovado.classList.add("bi","bi-check", "d-none", "text-success", "fs-22")
        
        var reprovado = document.createElement("I")
        reprovado.classList.add("bi","bi-x", "d-none", "text-danger", "fs-22")
        
        var text = document.createElement("SPAN")
        text.innerText = texto
        

        var circulo = document.createElement("SPAN")
        circulo.classList.add("wi-30","he-30","d-block","bg-light", "rounded-circle","d-flex","align-items-center","justify-content-center","text-dark", "circulo")
        

        circulo.appendChild(aprovado)
        circulo.appendChild(reprovado)
        
        li.appendChild(circulo)
        li.appendChild(text)
        
        this.linhas.push(li)
        return li;
    }
    
    render(){

        var ul = document.createElement("UL")
        ul.classList.add("list-group","list-group-flush")
        
        this.cabecalho = document.createElement("LI")
        this.cabecalho.classList.add("list-group-item","d-flex","align-items-center","justify-content-center","fw-700","cabecalho","text-light")
        ul.appendChild(this.cabecalho)
        
        ul.appendChild(this.linha.bind(this)("Pedido Criado"))
        ul.appendChild(this.linha.bind(this)("Pagamento Recebido"))
        ul.appendChild(this.linha.bind(this)("Pedido em Andamento"))
        ul.appendChild(this.linha.bind(this)("Pedido Concluído"))
        
        this.containerStatus.appendChild(ul);
        
        this.btnsArea = document.createElement("DIV")
        
        this.containerStatus.appendChild(this.btnsArea);

        this.dinamico.bind(this)();
    }
    
    mudaStatus(index, status){
        var linha = this.linhas[index]
        if(linha){
            var icones = linha.getElementsByTagName("I")
            var circulo = linha.getElementsByClassName("circulo")[0]
            
            icones[0].classList.add("d-none")
            icones[1].classList.add("d-none")

            
            
            if(icones[status - 1]){
                icones[status - 1].classList.remove("d-none")
            }
            
            
            switch(status){
                case 0:
                    break;
                case 1:
                    circulo.classList.add("border", "border-success")
                    break;
                case 2:
                    circulo.classList.add("border", "border-danger")
                    break;
            }

            

            

        }
    }
    
    aprovar(){
        Swal.fire({
            title: "Você tem certeza",
            text: "O pedido será aprovado e irá influenciar em todos os fluxos do sistema.",
            icon: "question",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aprovar"
        }).then((result) => {
            if (result.isConfirmed) {
                this.select.value = 3;
                this.status = 3;
                this.dinamico()
            }
        });
    }
    
    cancelar(){
        Swal.fire({
            title: "Você tem certeza",
            text: "O pedido será cancelado e essa ação não poderá ser revertida.",
            icon: "question",
            showCancelButton: true,
            cancelButtonText: "Desistir",
            confirmButtonText: "Proseguir"
        }).then((result) => {
            if (result.isConfirmed) {
                this.select.value = 0;
                this.status = 0;
                this.dinamico()
            }
        });
    }
    
    concluir(){
        Swal.fire({
            title: "Você tem certeza",
            text: "O pedido será concluído e não poderá ser feitas novas edições.",
            icon: "question",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Concluir"
        }).then((result) => {
            if (result.isConfirmed) {
                this.select.value = 4;
                this.status = 4;
                this.dinamico()
            }
        });
    }
    
    geraBtn(index){
        var btn = document.createElement("BTN")
        btn.classList.add("btn", "d-block", "w-100", "btn-nown-style")
        switch(index){
            case 0:
                // BTN APROVAR
                btn.classList.add("btn-primary");
                btn.innerHTML = `Aprovar Pedido`
                evento(btn, "click", this.aprovar.bind(this))
                break;
            case 1:
                // BTN CANCELAR
                btn.classList.add("btn-danger");
                btn.innerHTML = `Cancelar Pedido`
                evento(btn, "click", this.cancelar.bind(this))
                break;
            case 2:
                // BTN Concluir Pedido
                btn.classList.add("btn-success")
                btn.innerHTML = `Concluir Pedido`
                evento(btn, "click", this.concluir.bind(this))
                break;
            case 3:
                break;
        }
        return btn;
    }
    
    
    dinamico(){
        this.mudaStatus(0, 1);
        this.btnsArea.innerHTML = "";
        var flex = document.createElement("DIV")
        flex.classList.add("d-flex", "gap-2", "flex-column", "mt-3")
        this.cabecalho.classList.remove("bg-danger", "bg-success", "bg-primary", "bg-info", "bg-primaria")
        switch(this.status){
            case 0:
                this.cabecalho.innerHTML = "Pedido Cancelado";
                this.cabecalho.classList.add("bg-danger")
                this.mudaStatus(1, 2);
                this.mudaStatus(2, 2);
                this.mudaStatus(3, 2);
                this.precoDesconto.setAttribute("disabled", "")
                this.precoFrete.setAttribute("disabled", "")
                break;
            case 1:
                this.cabecalho.innerHTML = "Aguardando Pagamento";
                this.cabecalho.classList.add("bg-primary")
                this.mudaStatus(1, 0);
                this.mudaStatus(2, 0);
    
            
                
                flex.appendChild(this.geraBtn(0))
                flex.appendChild(this.geraBtn(1))
                
                this.btnsArea.appendChild(flex)
                break;
            case 2:
                this.cabecalho.innerHTML = "Pagamento Recebido";
                this.cabecalho.classList.add("bg-primaria")
                this.mudaStatus(1, 1);
                this.mudaStatus(2, 0);
     
                
                flex.appendChild(this.geraBtn(2))
                flex.appendChild(this.geraBtn(1))
                this.btnsArea.appendChild(flex)
                
                this.precoDesconto.setAttribute("disabled", "")
                this.precoFrete.setAttribute("disabled", "")

                break;
            case 3:
                this.cabecalho.innerHTML = "Pedido em Andamento";
                this.cabecalho.classList.add("bg-info")
                this.mudaStatus(1, 1);
                this.mudaStatus(2, 1);
                this.mudaStatus(3, -1);
                
     
                flex.appendChild(this.geraBtn(2))
                flex.appendChild(this.geraBtn(1))
                this.btnsArea.appendChild(flex)
                
                this.precoDesconto.setAttribute("disabled", "")
                this.precoFrete.setAttribute("disabled", "")

                break;
            case 4:
                this.cabecalho.innerHTML = "Pedido Concluído";
                this.cabecalho.classList.add("bg-success")
                this.mudaStatus(1, 1);
                this.mudaStatus(2, 1);
                this.mudaStatus(3, 1);
                this.precoDesconto.setAttribute("disabled", "")
                this.precoFrete.setAttribute("disabled", "")
                break;
        }
        
    }
}

class ControlerPedidos{
    constructor(){
        var url = window.location.href.split("/");
        this.hash = url[url.length - 1]
        this.container = document.getElementById("itensPedido");
        
        
        this.init.bind(this)()
        
        this.precoTotal = document.getElementById("precoTotal").getElementsByClassName("form-control")[0]
        this.precoDesconto = document.getElementById("precoDesconto").getElementsByClassName("form-control")[0]
        this.precoFrete = document.getElementById("precoFrete").getElementsByClassName("form-control")[0]
        this.precoFinal = document.getElementById("precoFinal").getElementsByClassName("form-control")[0]
        
        evento([this.precoDesconto, this.precoFrete], "input", this.calculadora.bind(this));

        
    }
    
    init(){
        this.preRender();
    }
    
    
    
    
    novo(pedido){

        var li = document.createElement("LI")
        li.classList.add("list-group-item","list-group-item-action")
        li.innerHTML = `
        <div class="row">
            <div class="col-1 d-flex align-items-center justify-content-center">
            <input class="form-check-input" type="checkbox" value="">
            </div>
            <div class="col-6">
                <input class="form-control" value="${this.lista[pedido.infos.id]}">
            </div>
            <div class="col-2">
                <input type="number" class="form-control quantidade" min="1" value="${pedido.infos.comprado}">
            </div>
            <div class="col-3">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">R$</span>
                    <input type="text" class="form-control preco" value="${pedido.infos.preco}" data-mascara="12">
                </div>
        </div>
    </div>

        `
        new Mascaras([li.getElementsByClassName("preco")[0]]);
        
        evento(li.getElementsByClassName("preco")[0], "input", this.calculadora.bind(this))
        evento(li.getElementsByClassName("quantidade")[0], "input", this.calculadora.bind(this))
        
        return li;
        
        
          
    }
    
    preRender(){
        this.container.innerHTML = `
        <div class="card card-nown">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="fs-16 text-uppercase m-0 fw-700 text-contrast">ITENS DO PEDIDO</h3>
                <button class="btn btn-n-primaria btn-nown-style d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#novoItemPedido" aria-expanded="false" aria-controls="novoItemPedido">
                    <i class="bi bi-plus-circle"></i> 
                    <span>Adicionar Item</span>
                </button>
            </div>
            <div class="collapse" id="novoItemPedido">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12">
                        <label>Produto</label>
                        <select class="form-select produto">
                            <option value="0">Selecione o produto</option>
                        </select>
                    </div>
                      <div class="col-6">
                        <label>Quantidade</label>
                        <input class="form-control" type="number" value="1" min="1">
                    </div>
                      <div class="col-6">
                        <label>Preço</label>
                       <input class="form-control preco" data-mascara="11">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-n-primaria btn-nown-style">Adicionar</button>
                    </div>
                </div>
            </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item active" aria-current="true">
                        <div class="row">
                            <div class="col-1 d-flex align-items-center justify-content-center">
                                <input class="form-check-input" type="checkbox" value="">
                            </div>
                            <div class="col-6">Produto</div>
                            <div class="col-2">Quantidade</div>
                            <div class="col-3">Preço</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        `
        
        this.ul = this.container.getElementsByClassName("list-group")[0]
        this.btnNovo = this.container.getElementsByClassName("novo")[0]
        this.selectProduto = this.container.getElementsByClassName("produto")[0]
        this.selectPreco = this.container.getElementsByClassName("preco")[0]

        new Mascaras([this.selectPreco]);
        let request = new Request("/conteudo/modulos/pagamento/admins/pedido.php");
        request.addData({acao: "listaItens", pedido: this.hash})
        request.send().then((r)=>{
            this.render.bind(this)(r)

        })
        
        
       // evento(this.btnNovo, "click", this.novo.bind(this))
    }
    
    render(r){
        var lista = r.lista
        this.lista = {};
        var i = 0;

        while(i < lista.length){
            var item = lista[i]
            this.lista[item.id] = item.nome
            
            var option = document.createElement("OPTION")
            option.dataset.preco = item.preco;
            option.value = item.id
            option.innerText = item.nome
            this.selectProduto.appendChild(option)
            i++;
            }
        evento(this.selectProduto, "input", this.selecionouNovo.bind(this))
        
        
        var pedidos = r.pedido;
        this.pedidos = {};
        var i = 0;
        var fragmento = document.createDocumentFragment();
        while(i < pedidos.length){
            var pedido = pedidos[i]
            
            this.pedidos[pedido.id] = this.novo.bind(this)(pedido)

            fragmento.appendChild(this.pedidos[pedido.id])
            i++;
        }
        this.ul.appendChild(fragmento)
        
        this.calculadora.bind(this)();
    }
    
    calculadora(){
        var calc = 0;

        for(let c in this.pedidos){

            var pedido = this.pedidos[c]
       
            var quantidade = parseInt(pedido.getElementsByClassName("quantidade")[0].value)
            var preco = parseFloat(pedido.getElementsByClassName("preco")[0].value);
            var precalc = quantidade * preco
            calc += precalc;
        }
        
        
        
        this.precoTotal.value  = calc.toFixed(2);
 
        
        
        if(this.precoDesconto.value){
            console.log(this.precoDesconto.value)
            var desconto = parseFloat(this.precoDesconto.value)

            calc -= desconto
        }
        
        
        if(this.precoFrete.value){
            var frete = parseFloat(this.precoFrete.value)
            calc += frete
        }
        
        
        if(calc > 0){
            this.precoFinal.value = calc.toFixed(2);
        }else{
            this.precoFinal.value = 0
        }

        
        
        
        

    }
    
    selecionouNovo(){
        let selectedOption = this.selectProduto.options[this.selectProduto.selectedIndex];
        if(this.selectProduto.value != 0){
            var preco = selectedOption.dataset.preco
            this.selectPreco.value = preco;
        }
    }
}

function moduloPagamentoExcecao(){

    var url = window.location.href.split("/pagamento/")[1].split("/")
    
    let trato = url.filter(function(item) {
    return item !== null && item !== undefined && item !== '';
    });
    

    switch(trato[0]){
        case 'pedido':
            new ControlerPedidos();
            new StatusControlerPedido();
            break;
    }
}

