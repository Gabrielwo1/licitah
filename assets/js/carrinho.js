class ItemCarrinho{
    constructor(item, container){
        this.container = container
        this.id = item.produto.id
        this.item = item

    }
    
    deleta(){
        this.container.deleta(this.id);

    }
    

    render(){

        var infos = this.item.infos
        let nome = infos.nome;
        let produto = this.item.produto
        let quantidade = this.item.quantidade
        let preco = produto.preco
        let calc =  preco * quantidade;
        calc =  parseFloat(calc).toFixed(2)

     
        
        var img = trataImagem(infos.foto, "mini") || `https://placehold.co/100x100?text=${nome}`;
        
        this.html = document.createElement("LI")
        this.html.id = `carrinhoItem-${this.item.produto.id}`
        this.html.className = "item-card"
        this.html.innerHTML = `
       
        
                                <div class="item-content">
                                    <div class="item-image" style="background-image: url(${img});"></div>
                                    <div class="item-info">
                                        <div class="item-name">${nome}</div>
                                        <div class="item-category">Nome da Categoria</div>
                                        <div class="item-quantity">
                                            <div class="quantity-controls">
                                                <button class="qty-btn" data-action="decrease">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="qty-input" value="${quantidade}" min="1">
                                                <button class="qty-btn" data-action="increase">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                            <div class="unit-price">${paraPreco(preco)} cada</div>
                                        </div>
                                    </div>
                                    <div class="item-actions">
                                        <div class="item-price">${paraPreco(calc)}</div>
                                        <button class="btn btn-remove">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                           
        `
        evento(this.html.getElementsByClassName("btn-remove")[0] , "click", this.deleta.bind(this))
        
        /*
        this.html.classList.add("prod");
        this.html.innerHTML = `
        <div class="thumb">
            <div class="bg-carregando he-80 wi-80"></div>
        </div>
            <div class="info">
                <div class="nome">
                    <h4>${nome}</h4>
                    <p><div class="bg-carregando he-10"></div></p>
                </div>
                <div class="uni">
                    <div class="w-100 d-flex justify-content-end">
                        ${quantidade} x R$ ${preco}
                    </div>
                </div>
                <div class="total">
                    <div class="w-100 d-flex justify-content-end">
                        R$ ${calc}
                    </div>
                </div>
                <div class="acoes">
                    <button class="btn btn-deleta"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        `
        evento(this.html.getElementsByClassName("btn-deleta")[0] , "click", this.deleta.bind(this))
        
        
        */
        
        return this.html;
    }
 
}

class CarrinhoVazio{
    constructor(){
        document.getElementById("conteudo").innerHTML = `
          <style>
           #secao-carrinho-vazio {
            .carrinho-vazio-content {
                padding: 4rem 2rem;
                min-height: 60vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                
                &:before {
                    content: '';
                    position: absolute;
                    top: 20%;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 300px;
                    height: 300px;
                    background: radial-gradient(circle, rgba(var(--nown-primaria-rgb), 0.05) 0%, transparent 70%);
                    border-radius: 50%;
                    z-index: 1;
                }
                
                .empty-state {
                    text-align: center;
                    max-width: 600px;
                    width: 100%;
                    position: relative;
                    z-index: 2;
                    
                    .empty-icon-container {
                        position: relative;
                        margin: 0 auto 3rem;
                        
                        .empty-icon {
                            width: 140px;
                            height: 140px;
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto;
                            box-shadow: 0 20px 60px rgba(var(--nown-primaria-rgb), 0.25);
                            position: relative;
                            animation: float 3s ease-in-out infinite;
                            
                            &:before {
                                content: '';
                                position: absolute;
                                top: -15px;
                                left: -15px;
                                right: -15px;
                                bottom: -15px;
                                border-radius: 50%;
                                border: 2px solid rgba(var(--nown-primaria-rgb), 0.2);
                                animation: pulse 2s ease-in-out infinite;
                            }
                            
                            i {
                                font-size: 4rem;
                                color: white;
                                filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
                            }
                        }
                        
                        .floating-elements {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            width: 250px;
                            height: 250px;
                            pointer-events: none;
                            
                            .floating-item {
                                position: absolute;
                                background: rgba(var(--nown-primaria-rgb), 0.1);
                                border-radius: 50%;
                                
                                &:nth-child(1) {
                                    width: 8px;
                                    height: 8px;
                                    top: 20%;
                                    left: 15%;
                                    animation: float-1 4s ease-in-out infinite;
                                }
                                
                                &:nth-child(2) {
                                    width: 12px;
                                    height: 12px;
                                    top: 30%;
                                    right: 20%;
                                    animation: float-2 3.5s ease-in-out infinite 0.5s;
                                }
                                
                                &:nth-child(3) {
                                    width: 6px;
                                    height: 6px;
                                    bottom: 25%;
                                    left: 25%;
                                    animation: float-3 5s ease-in-out infinite 1s;
                                }
                                
                                &:nth-child(4) {
                                    width: 10px;
                                    height: 10px;
                                    bottom: 20%;
                                    right: 15%;
                                    animation: float-4 4.2s ease-in-out infinite 1.5s;
                                }
                            }
                        }
                    }
                    
                    .empty-content {
                        .empty-title {
                            font-weight: 800;
                            font-size: 2rem;
                            color: #2c3e50;
                            margin-bottom: 1.5rem;
                            text-transform: uppercase;
                            letter-spacing: 2px;
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                            background-clip: text;
                            position: relative;
                            
                            &:after {
                                content: '';
                                position: absolute;
                                bottom: -8px;
                                left: 50%;
                                transform: translateX(-50%);
                                width: 60px;
                                height: 3px;
                                background: linear-gradient(90deg, var(--nown-primaria), var(--nown-primaria-darker));
                                border-radius: 2px;
                            }
                        }
                        
                        .empty-subtitle {
                            color: #6c757d;
                            font-size: 1.2rem;
                            line-height: 1.6;
                            margin-bottom: 3rem;
                            font-weight: 400;
                            max-width: 400px;
                            margin-left: auto;
                            margin-right: auto;
                        }
                    }
                    
                    .empty-actions {
                        display: flex;
                        gap: 1.5rem;
                        justify-content: center;
                        flex-wrap: wrap;
                        margin-bottom: 3rem;
                        
                        .action-btn {
                            border-radius: 15px;
                            font-weight: 700;
                            padding: 1.2rem 2.5rem;
                            transition: all 0.3s ease;
                            font-size: 1rem;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                            position: relative;
                            overflow: hidden;
                            min-width: 200px;
                            
                            &:before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: -100%;
                                width: 100%;
                                height: 100%;
                                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                                transition: left 0.5s;
                            }
                            
                            &:hover {
                                transform: translateY(-3px);
                                
                                &:before {
                                    left: 100%;
                                }
                            }
                            
                            &.btn-primary {
                                background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                border: none;
                                box-shadow: 0 10px 30px rgba(var(--nown-primaria-rgb), 0.3);
                                
                                &:hover {
                                    box-shadow: 0 15px 40px rgba(var(--nown-primaria-rgb), 0.4);
                                    background: linear-gradient(135deg, var(--nown-primaria-darker), var(--nown-primaria));
                                }
                            }
                            
                            &.btn-outline {
                                background: transparent;
                                border: 2px solid var(--nown-primaria);
                                color: var(--nown-primaria);
                                
                                &:hover {
                                    background: var(--nown-primaria);
                                    color: white;
                                    box-shadow: 0 10px 30px rgba(var(--nown-primaria-rgb), 0.3);
                                }
                            }
                        }
                    }
                    
                    .empty-suggestions {
                        .suggestions-title {
                            color: #8a8a8a;
                            font-size: 0.95rem;
                            margin-bottom: 1.5rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }
                        
                        .suggestions-list {
                            display: flex;
                            gap: 1rem;
                            justify-content: center;
                            flex-wrap: wrap;
                            
                            .suggestion-chip {
                                background: rgba(255,255,255,0.8);
                                border: 2px solid rgba(var(--nown-primaria-rgb), 0.15);
                                border-radius: 25px;
                                padding: 0.75rem 1.5rem;
                                font-size: 0.9rem;
                                font-weight: 600;
                                color: var(--nown-primaria);
                                cursor: pointer;
                                transition: all 0.3s ease;
                                backdrop-filter: blur(10px);
                                
                                &:hover {
                                    background: var(--nown-primaria);
                                    color: white;
                                    transform: translateY(-2px);
                                    box-shadow: 0 8px 20px rgba(var(--nown-primaria-rgb), 0.25);
                                    border-color: var(--nown-primaria);
                                }
                                
                                i {
                                    margin-right: 0.5rem;
                                    font-size: 0.9rem;
                                }
                            }
                        }
                    }
                }
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.05); }
        }

        @keyframes float-1 {
            0%, 100% { transform: translate(0, 0); }
            25% { transform: translate(10px, -10px); }
            50% { transform: translate(-5px, -15px); }
            75% { transform: translate(-10px, -5px); }
        }

        @keyframes float-2 {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(-15px, 10px); }
            66% { transform: translate(5px, -12px); }
        }

        @keyframes float-3 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(12px, 8px); }
        }

        @keyframes float-4 {
            0%, 100% { transform: translate(0, 0); }
            25% { transform: translate(-8px, 12px); }
            75% { transform: translate(15px, -8px); }
        }

        /* Responsividade */
        @media (max-width: 767px) {
            #secao-carrinho-vazio {
                .carrinho-vazio-content {
                    padding: 2rem 1rem;
                    
                    &:before {
                        width: 200px;
                        height: 200px;
                    }
                    
                    .empty-state {
                        .empty-icon-container {
                            margin-bottom: 2rem;
                            
                            .empty-icon {
                                width: 100px;
                                height: 100px;
                                
                                i {
                                    font-size: 3rem;
                                }
                            }
                            
                            .floating-elements {
                                width: 180px;
                                height: 180px;
                            }
                        }
                        
                        .empty-content {
                            .empty-title {
                                font-size: 1.5rem;
                                letter-spacing: 1px;
                            }
                            
                            .empty-subtitle {
                                font-size: 1rem;
                                margin-bottom: 2rem;
                            }
                        }
                        
                        .empty-actions {
                            flex-direction: column;
                            gap: 1rem;
                            margin-bottom: 2rem;
                            
                            .action-btn {
                                width: 100%;
                                min-width: auto;
                            }
                        }
                        
                        .empty-suggestions {
                            .suggestions-list {
                                .suggestion-chip {
                                    font-size: 0.8rem;
                                    padding: 0.6rem 1.2rem;
                                }
                            }
                        }
                    }
                }
            }
        }

        /* Suporte a Dark Mode */
        body.light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-carrinho-vazio {
                .carrinho-vazio-content {
                    .empty-state {
                        .empty-content {
                            .empty-title {
                                color: var(--nown-terciaria);
                                background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-lighter));
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                background-clip: text;
                            }
                            
                            .empty-subtitle {
                                color: #aaa;
                            }
                        }
                        
                        .empty-suggestions {
                            .suggestions-title {
                                color: #999;
                            }
                            
                            .suggestions-list {
                                .suggestion-chip {
                                    background: rgba(45, 45, 45, 0.8);
                                    border-color: rgba(var(--nown-primaria-rgb), 0.25);
                                    color: var(--nown-primaria-lighter);
                                    
                                    &:hover {
                                        background: var(--nown-primaria);
                                        color: white;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    </style>
    <section id="secao-carrinho-vazio">
            <div class="carrinho-vazio-content">
                <div class="empty-state">
                    <div class="empty-icon-container">
                        <div class="empty-icon">
                            <i class="bi bi-cart-x"></i>
                        </div>
                        <div class="floating-elements">
                            <div class="floating-item"></div>
                            <div class="floating-item"></div>
                            <div class="floating-item"></div>
                            <div class="floating-item"></div>
                        </div>
                    </div>
                    
                    <div class="empty-content">
                        <h2 class="empty-title text-center">Seu Carrinho está Vazio</h2>
                        
                        <p class="empty-subtitle text-center">
                            Navegue em nosso site e descubra os melhores itens para você.
                        </p>
                    </div>
                    
                    <div class="empty-actions">
                        <button class="btn action-btn btn-primary">
                            <i class="bi bi-bag-plus me-2"></i>Continuar Comprando
                        </button>
                        <button class="btn action-btn btn-outline">
                            <i class="bi bi-fire me-2"></i>Ver Ofertas
                        </button>
                    </div>
                    
                    <div class="empty-suggestions">
                        <div class="suggestions-title">Explore nossas categorias</div>
                        <div class="suggestions-list">
                            <span class="suggestion-chip">
                                <i class="bi bi-phone"></i>Eletrônicos
                            </span>
                            <span class="suggestion-chip">
                                <i class="bi bi-bag"></i>Moda
                            </span>
                            <span class="suggestion-chip">
                                <i class="bi bi-house"></i>Casa & Jardim
                            </span>
                            <span class="suggestion-chip">
                                <i class="bi bi-bicycle"></i>Esportes
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        `
    }
}

class CarrinhoPage{
    constructor(){
        this.ajax = this.ajax.bind(this)
        
        this.carrinho = {};
        this.htmls = {};
        this.btnPagar = document.getElementById("btnPagar")
        evento(this.btnPagar, "click", this.pagar.bind(this))
        
        var data = new FormData();
        data.append("acao", "carrinho")
        data.append("produto", "all")
        this.ajax(data, this.monta.bind(this))
        
        evento(document.getElementById("btnLimpar"), "click", this.limparTudo.bind(this))
    }
    
    limparTudo(){
        Swal.fire({
            icon: "question",
            title: "Você tem certeza?",
            text: "Você quer limpar seu carrinho?",
            showCancelButton: true,
            confirmButtonText: "Apagar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                var i = 0;
                while(i < this.lista.length){
                    var item = this.lista[i]
  
  
                    let request = new Request("/conteudo/modulos/pagamento/admins/produto.php");
                    request.addData({"acao":"remover", "produto":item.produto.id})
                    request.send()
                    i++;
                }
                
                goUrl("carrinho");
            }
            
            })
    }
    
    pagar(){
        this.btnPagar.setAttribute("disabled", "")
        this.btnPagar.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`

        var data = new FormData();
        data.append("acao", "fechaPedido") 
        data.append("produto", 1)
        data.append("fecha", true)
        this.ajax(data, this.processa.bind(this))
    }
    
    processa(r){
        goUrl(`pagamento/${r.pedido}`);
    }
    
    calc(){
        let calculo = 0;
           for(let c in this.carrinho){
               var produto = this.carrinho[c].produto
               let quantidade = this.carrinho[c].quantidade
               let preco = produto.preco
               let calc =  preco * quantidade;
               calculo += calc
           }
        
        calculo =  parseFloat(calculo).toFixed(2)
        this.btnPagar.removeAttribute("disabled")
        document.getElementById("precoFinal").innerHTML = `R$ ${calculo}`
    }
        
    deleta(id){
        
        Swal.fire({
            icon: "question",
            title: "Você tem certeza?",
            text: "Você quer remover esse item do carrinho?",
            showCancelButton: true,
            confirmButtonText: "Apagar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                    this.btnPagar.setAttribute("disabled", "")
                Swal.fire({
  icon: "success",
  title: "Item removido",
  showConfirmButton: false,
  timer: 1500
});

                  iziToast.show({
                      icon: "bi bi-cart-check",
            title: 'Sucesso',
            message: 'Item Removido do Carrinho'
        });

                var data = new FormData();
                data.append("acao", "remover")
                data.append("produto", id)
                this.ajax(data, this.removido.bind(this))
            } 
        });
    }
    
    removido(r){
        start.carrinho.update();
        if(r.id){
            if(this.htmls[r.id]){
                this.htmls[r.id].html.remove();
                delete this.carrinho[r.id];
            }
           
            
            const numberOfKeys = Object.entries(this.carrinho).length;
            if(numberOfKeys == 0){
                new CarrinhoVazio();
            }else{
                this.calc.bind(this)()
            }

        }
    }
    
    render(){
        var fragmento = document.createDocumentFragment();
        for(let c in this.carrinho){

            if(!document.getElementById(`carrinhoItem-${this.carrinho[c].produto.id}`)){
                  this.htmls[c] = new ItemCarrinho(this.carrinho[c], this);
                  fragmento.appendChild(this.htmls[c].render());
            }
         
        }
        
        document.getElementById("listaCarrinho").appendChild(fragmento)
             const elementsToRemove = document.querySelectorAll('.itemLoading');
        elementsToRemove.forEach(element => {
            element.remove();
        });
        
        
        
        this.calc.bind(this)()
    }
    
    monta(r){
        if(r.lista.length > 0){
            this.lista = r.lista
            
            for(let c in this.lista){
                var item = this.lista[c]
                this.carrinho[item.produto.id] = item
            }
            
            this.render.bind(this)();
          
        }else{
            new CarrinhoVazio();
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

function paginaCarrinho(){
    new CarrinhoPage();
}