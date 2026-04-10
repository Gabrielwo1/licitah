class SaldoPage {
    constructor() {
        
      
        // Valores iniciais
        this.valor = 0;
        this.reais = 0;
        
        // Referências de elementos DOM
        this.final = document.getElementById("totalAmountDisplay") || document.getElementById("valorFinal");
        this.paymentButton = document.getElementById("paymentButton") || document.getElementById("btnPagar");
        
        // Seleção de valores
        this.amountOptions = document.querySelectorAll('.amount-option') || document.getElementsByClassName("btnChoise");
        
        // Campo de valor personalizado
        this.customAmountContainer = document.getElementById("customAmountContainer");
        this.customInput = document.getElementById("customAmountInput") || document.getElementById("customValor");
        
        if (this.customInput) {
            this.maximo = this.customInput.max;
            this.minimo = this.customInput.min;
            
            
            console.log(this.maximo, this.minimo)
            
            this.parente = this.customInput.closest(".custom-amount-container") || this.customInput.closest(".parente");
            
            // Evento para valor personalizado
            evento(this.customInput, "input", () => {
                this.atualizarValorPersonalizado.bind(this)();
            });
            
            evento(this.customInput, "blur", () => {
                this.escolha.bind(this)(false);
            });
        }
        
        // Inicializar toggle de tema
        this.themeToggle = document.getElementById('themeToggle');
        if (this.themeToggle) {
            this.inicializarTema();
            evento(this.themeToggle, "click", this.alternarTema.bind(this));
        }
        
        // Inicializar opções de valor
        if (this.amountOptions.length > 0) {
            this.amountOptions.forEach(option => {
                evento(option, "click", () => {
                    this.escolha.bind(this)(option);
                });
            });
            
            // Selecionar a primeira opção por padrão
            this.escolha.bind(this)(this.amountOptions[0]);
        }
        
        // Evento de pagamento
        evento(this.paymentButton, "click", this.pagar.bind(this));
    }
    
    // Inicializar tema com base em preferências
    inicializarTema() {
        // Verifica se há preferência salva
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.body.className = savedTheme;
        } else {
            // Verifica preferência do sistema
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.className = 'dark';
            } else {
                document.body.className = 'light';
            }
        }
    }
    
    // Alternar entre temas
    alternarTema() {
        if (document.body.classList.contains('dark')) {
            document.body.className = 'light';
            localStorage.setItem('theme', 'light');
        } else {
            document.body.className = 'dark';
            localStorage.setItem('theme', 'dark');
        }
    }
    
    // Formatação de moeda
    formatarMoeda(valor) {
        return paraPreco ? paraPreco(valor) : `R$ ${parseFloat(valor).toFixed(2).replace('.', ',')}`;
    }
    
    // Tratar valor personalizado
    atualizarValorPersonalizado() {
        if (!this.customInput) return;
        
        const value = parseFloat(this.customInput.value) || 0;
        this.reais = value;
        
        // Validação visual
        if (this.minimo && value < parseFloat(this.minimo) && value !== 0) {
            this.customInput.classList.add('is-invalid');
            if (value !== 0) {
                this.notificarErro(`Valor mínimo de depósito é de ${this.formatarMoeda(this.minimo)}`);
            }
        } else if (this.maximo && value > parseFloat(this.maximo)) {
            this.customInput.classList.add('is-invalid');
            this.notificarErro(`Valor máximo de depósito é de ${this.formatarMoeda(this.maximo)}`);
        } else {
            this.customInput.classList.remove('is-invalid');
        }
        
        this.atualizarValorTotal();
    }
    
    // Método de escolha das opções
    escolha(opcao) {
        // Resetar escolha anterior
        if (this.amountOptions) {
            this.amountOptions.forEach(opt => {
                // Suporte ao novo design
                opt.classList.remove('selected');
                // Suporte ao design anterior
                if (opt.classList.contains("btn-n-primaria")) {
                    opt.classList.remove("btn-n-primaria");
                    opt.classList.add("btn-secondary");
                }
            });
        }
        
        if (opcao) {
            // Suporte ao novo design
            opcao.classList.add('selected');
            
            // Suporte ao design antigo
            if (opcao.classList.contains("btnChoise")) {
                opcao.classList.add("btn-n-primaria");
                opcao.classList.remove("btn-secondary");
                if (opcao.getElementsByClassName("form-check-input").length > 0) {
                    opcao.getElementsByClassName("form-check-input")[0].checked = true;
                }
            }
            
            // Obter valor da opção
            const valor = opcao.dataset.valor || (opcao.querySelector('input') ? opcao.querySelector('input').value : 0);
            
            // Verificar se é valor personalizado
            if (valor === '0' || valor === 'custom') {
                if (this.customAmountContainer) {
                    this.customAmountContainer.classList.remove('d-none');
                } else if (this.parente) {
                    this.parente.classList.remove("d-none");
                }
                
                if (this.customInput && this.customInput.value) {
                    this.reais = parseFloat(this.customInput.value);
                } else {
                    this.reais = 0;
                    if (this.customInput) {
                        this.customInput.focus();
                    }
                }
            } else {
                this.reais = parseFloat(valor);
                
                if (this.customAmountContainer) {
                    this.customAmountContainer.classList.add('d-none');
                } else if (this.parente) {
                    this.parente.classList.add("d-none");
                }
            }
        }
        
        // Validar valor máximo
        if (this.maximo && parseFloat(this.reais) > parseFloat(this.maximo)) {
            this.reais = parseFloat(this.maximo);
            if (this.customInput) {
                this.customInput.value = this.maximo;
            }
            this.notificarErro(`Valor máximo de depósito de ${this.formatarMoeda(this.maximo)}`);
        }
        
        // Validar valor mínimo
        if (this.minimo && parseFloat(this.reais) < parseFloat(this.minimo) && this.reais !== 0) {
            this.reais = parseFloat(this.minimo);
            if (this.customInput) {
                this.customInput.value = this.minimo;
            }
            this.notificarErro(`Valor mínimo de depósito de ${this.formatarMoeda(this.minimo)}`);
        }
        
        this.atualizarValorTotal();
    }
    
    // Atualizar exibição do valor total
    atualizarValorTotal() {
        if (this.final) {
            this.final.innerHTML = this.formatarMoeda(this.reais);
        }
        
        // Habilitar/desabilitar botão de pagamento
        if (this.paymentButton) {
            if (this.reais > 0 && 
                (!this.minimo || parseFloat(this.reais) >= parseFloat(this.minimo)) && 
                (!this.maximo || parseFloat(this.reais) <= parseFloat(this.maximo))) {
                this.paymentButton.disabled = false;
            } else {
                this.paymentButton.disabled = true;
            }
        }
    }
    
    // Notificação de erro (compatível com sistema existente ou novo)
    notificarErro(mensagem) {
        if (typeof iziToast !== 'undefined') {
            iziToast.error({
                title: 'Atenção',
                message: mensagem
            });
        } else {
            console.error(mensagem);
            // Alternativa básica de alerta
            // alert(mensagem);
        }
    }
    
    init() {
        // Método para inicialização adicional, mantido por compatibilidade
    }
    
    pagar() {
        if (this.reais <= 0) {
            this.notificarErro('Selecione um valor para depósito');
            return;
        }
        
        // Verificar valor mínimo
        if (this.minimo && parseFloat(this.reais) < parseFloat(this.minimo)) {
            this.notificarErro(`Valor mínimo de depósito é de ${this.formatarMoeda(this.minimo)}`);
            return;
        }
        
        // Verificar valor máximo
        if (this.maximo && parseFloat(this.reais) > parseFloat(this.maximo)) {
            this.notificarErro(`Valor máximo de depósito é de ${this.formatarMoeda(this.maximo)}`);
            return;
        }
        
        // Manter compatibilidade com o sistema de pagamento existente
        let request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
        request.addData({
            "acao": "addSaldo",
            "saldo": this.reais
        });
        request.send().then((r) => {
            goUrl(`pagamento/${r.url}`);
        }, (r) => {
            console.error(r);
            this.notificarErro('Erro ao processar pagamento');
        });
    }
}

/*
// Iniciar a página quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar efeitos decorativos
    
    
    // Inicializar a classe SaldoPage
    const saldoPage = new SaldoPage();
});
*/

class CheckOut{ 
    constructor(){
        if(document.getElementById("freeCheckout")){
            this.estruturaFree.bind(this)();
            return;
        }
        
        if(document.getElementById("fatura")){
            this.estruturaFatura.bind(this)();
            return;
        }
        
        
        if(document.getElementById("fretePage")){
            nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/frete.js`).then(()=>{
                new PagamentoFrete();
            });
            return;
        }
        
        if(document.getElementById("paginaSucesso")){
            document.getElementById("paginaSucesso").getElementsByTagName("a")[0].href = dominio
            
            if(dataSys(["apis","google-ads","ativo"], false) && dataSys(["apis","google-ads","label"], false)){
                
                if(pegaLocal("pedidoSucesso")){
                    var id = pegaLocal("pedidoSucesso");
                    
                    
                    let request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
                    request.addData({
                        "acao": "checkout",
                        "produto": 1,
                        "pedido": id
                    })
                    request.send().then((r)=>{
   
                        
                         let obj = {
                             'send_to': `${dataSys(["apis","google-ads","tag"], false).trim()}/${dataSys(["apis","google-ads","label"], false).trim()}`,
                             'currency': 'BRL'
                         }
                         
                         if(dataSys(["apis","google-ads","preco"], false)){
                             obj.value = parseFloat(r.pedido.total);
                         }
                         
                         if(dataSys(["apis","google-ads","idpedido"], false)){
                             obj["transaction_id"]  = id;
                         }
                         
                         if(dataSys(["apis","google-ads","cupom"], false)){
                             obj.coupon = false;
                         }
                         
                         if(dataSys(["apis", "google-ads", "idcliente"], false)){
                             let user = 0;
                             if(infoUser()){
                                 user = infoUser().id;
                             }
                             obj['customer_id'] = user

                             
                         }
                         
                         
          
                         
                if(dataSys(["apis","google-ads","itens"], false)){
                    var itens = r.pedido.itens
                    var i = 0;
                    var lista = [];
                    while(i < itens.length){
                        var item = itens[i]
 
                        lista.push(
                             {
                            'id': item.id,
                            'name': item.infos.nome, 
                            'category': item.banco, 
                            'quantity': parseInt(item.comprado), 
                            'price': parseFloat(item.preco)
                        }
                            
                            )
                        i++;
                    }
                    
             
                    obj.items = lista;
                }
                
            

               gtag('event', 'conversion', obj);
               
               if(dataSys(["apis","google-ads","ativodois"], false) && dataSys(["apis","google-ads","labeldois"], false)){
                   obj["send_to"] = `${dataSys(["apis","google-ads","tagdois"], false).trim()}/${dataSys(["apis","google-ads","labeldois"], false).trim()}`;
                   gtag('event', 'conversion', obj);
               }
               
               
               
               removeLocal("pedidoSucesso");
                         
                         
                        return;
                        
                    }, (r)=>{
                         goUrl("/");
                    })

                    
 
                    
                }else{
                    goUrl("/");
                }
            }
            
            return;
        }
        
        if(document.getElementById("paginaSaldo")){
            this.saldoControler.bind(this)();
            return;
        }
        
        this.container = document.getElementById("pedidoControler")
        console.log(this.container)

        this.pedidoId = this.container.dataset.pedido
        
 
        this.container.removeAttribute("id")
        this.container.removeAttribute("data-pedido")
        

        var url = window.location.href.toLowerCase().split(this.pedidoId)[1];
        if(!url){
            this.requestPage("pagamento", this.estrutura.bind(this));
            return;
        }

        switch(url.replace(/[^a-zA-Z0-9-]/g, '')){
            case 'pix':
               this.requestPage("pix", this.loadPix.bind(this));
                break;
            case 'cripto':
                this.requestPage("cripto");
                break;
            case 'boleto':
                this.requestPage("boleto", this.loadBoleto.bind(this));
                break;
            default:
                this.requestPage("pagamento", this.estrutura.bind(this));
                break;
        }
    }
    
    saldoControler(){
        new SaldoPage();
    }
    
    estruturaFatura(r){
         var request = new Request("conteudo/modulos/pagamento/admins/produto.php");
        request.addData(
            {"acao":"checkout",
            "produto" : 1,
            "pedido": caminho.hash()
            }
            );
        request.send().then((r)=>{
            if(r.pedido){
               var itens = r.pedido.itens;
               
               var fragmento = document.createDocumentFragment();
               var i = 0;
               while(i < itens.length){
                   var item = itens[i]
                   console.log(item)
                                  var tr = document.createElement("TR")
                                  var calc = parseFloat(item.preco) * parseInt(item.quantidade)

               tr.innerHTML = `
                  <td class="w-55 ">${item.infos.nome}</td>
                  <td class="w-15 text-center">${parseInt(item.quantidade)}</td>
                  <td class="w-15 text-center">${paraPreco(item.preco)}</td>
                  <td class="w-15 text-center">${paraPreco(calc)}</td>
               `
                   
                   fragmento.appendChild(tr)
                   i++;
               }
               document.getElementById("itensPedido").appendChild(fragmento)
               
       
                 nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/qrcode.min.js`).then(()=>{
                var qrcode = new QRCode(document.getElementById("qrCode"), {
                    text: window.location.href,
                    width: 150,
                    height: 150,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.L
                });
                 })

            }else{
                
            }
        })
    }
    
    estruturaFree(r){
        var request = new Request("conteudo/modulos/pagamento/admins/produto.php");
        request.addData(
            {"acao":"checkout",
            "produto" : 1,
            "pedido": caminho.hash()
            }
            );
        request.send().then((r)=>{
            if(r.pedido){
                var item = r.pedido.itens[0];
                console.log(item.banco)
                
                var imagem = trataImagem(item.infos.foto, "mini");
                if(!imagem){
                    imagem = "";
                }
               
                var li = document.createElement("LI")
                li.classList.add("list-group-item")
                li.innerHTML = `

                              <div class="card rounded-0 border-0">
                                 <div class="card-body p-1  position-relative">
                                    <div class="d-flex justify-content-between align-items-lg-center flex-column flex-xl-row">
                                       <div class="d-flex justify-content-start gap-3 align-items-center">
                                          <div class="img-thumbnail he-50 wi-50 he-xl-75 wi-xl-75" style="background-image: url(${imagem}); background-position: center center; background-size: cover"></div>
                                          <div>
                                             <h2 class="m-0 fs-16 fs-xl-18 fw-700">${item.infos.nome}</h2>
                                          </div>
                                       </div>
                                       <div class="fw-700 fs-18 text-success">
                                          GRÁTIS
                                       </div>
                                    </div>
                                 </div>
                              </div>

                `
       
  

               let pai = document.getElementById("conteudo").getElementsByClassName("list-group")[0];
               pai.insertBefore(li, pai.firstChild);
               evento(document.getElementById("btnPagar"), "click", this.pagarGratis.bind(this))
               
            }else{
                
            }
        })
    }
    
    pagarGratis(){
         var request = new Request("conteudo/modulos/pagamento/admins/gateways/index.php");
        request.addData(
            {
                pedido : pegaHash(),
                meio: "gratis",
            }) 
        request.send().then((r)=>{
            goUrl(`pagamento/${pegaHash()}`);
        }, (r)=>{
            console.log(r)
        })
    }
    
    verificaPix(pedido, pagamento) {
        var request = new Request("conteudo/modulos/pagamento/admins/gateways/index.php");
        request.addData(
            {
                pedido : pedido,
                meio: "verificaPix",
                pagento: pagamento
            })
        
 


    const intervalId = setInterval(() => {
        request.send().then((r)=>{
            if(r.update){
                 if(r.status == 0){
                     var titulo = "Atenção";
                     var texto = "Pedido cancelado, tente novamente";
                     var icone = "error"
                 }else{
                     var titulo = "Sucesso";
                     var texto = "Pedido feito com sucesso";
                     var icone = "success"
                 }
                 
                  clearInterval(intervalId)                 
                   Swal.fire({
                        icon: icone,
                        title: titulo,
                        text: texto,
                        showConfirmButton: false,
                        timer: 1500
                    });
                 
               
                    if(this.temRecorrencia){
                        start.tentaLogin(pegaLocal("sessao"));
                    }else{
                        if(this.container.dataset.obrigado){
                            defineLocal("pedidoSucesso", pedido);
                            
                             if(this.pedido.itens.length == 1 && this.container.dataset.addid){
                                goUrl(`pagamento/sucesso/${this.pedido.itens[0].id}`);
                            }else{
                                goUrl(`pagamento/sucesso`);
                            }
                        }else{
                            goUrl(`pagamento/${this.pedidoId}`);
                        }
                        
                    }
                    
                    start.carrinho.update();
                    start.carteira.carteira();
                
                
                
                
            }
        }, (r)=>{
            clearInterval(intervalId)
        })
  
    }, 5000);
}

    loadPix(){
        var request = new Request("conteudo/modulos/pagamento/admins/gateways/index.php");
        request.addData(
            {
                pedido : this.pedidoId,
                meio: "info",
                auxiliar: "pix"
            })
        request.send().then((r)=>{

            document.getElementById("idPedido").innerText = r.pedido.hash
            document.getElementById("chavePix").value = r.pagamento.codigo
            this.codePix = r.pagamento.codigo
            evento(document.getElementById("copiaPix"), "click", this.copiaPix.bind(this))
            document.getElementById("totalpreco").innerText = r.pedido.total
            nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/qrcode.min.js`).then(()=>{
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: r.pagamento.codigo,
                    width: 300,
                    height: 300,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.L
                });
                
                this.verificaPix.bind(this)(this.pedidoId, r.pagamento.id)
                
                
            })
        }, (r)=>{
            goUrl(`/pagamento/${this.pedidoId}`)

        })
    }
    
    downloadFile(url) {
  fetch(url)
    .then(response => response.blob())
    .then(blob => {
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = url;
      a.download = `boleto-${this.pedidoId}.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
    })
    .catch(err => console.error('Error downloading file:', err));
}

    copiaPix(){
        this.copyTextToClipboard(this.codePix, "Código Pix Copiado com Sucesso");
    }

    copyTextToClipboard(text, cb) {
  if (!navigator.clipboard) {
    return;
  }
  navigator.clipboard.writeText(text)
    .then(() => {
         iziToast.success({
    title: 'Sucesso',
    message: cb
})
    })
    .catch(err => {
      console.error('Could not copy text: ', err);
    });
}
    
    loadBoleto(){
        var request = new Request("conteudo/modulos/pagamento/admins/gateways/index.php");
        request.addData(
            {
                pedido : this.pedidoId,
                meio: "info",
                auxiliar: "boleto"
            })
        request.send().then((r)=>{

            if(r.vencimento){
                  var data = r.pagamento.vencimento.split("-")
                  document.getElementById("dtvencimento").innerText = `${data[2]}/${data[1]}/${data[0]}`
            }
          
            document.getElementById("codigoboleto").innerText = r.pagamento.codigo
            document.getElementById("totalpreco").innerText = r.pedido.total
            evento(document.getElementById("btnCopy"), "click", ()=>{
                this.copyTextToClipboard(r.pagamento.codigo, "Código de Barras Copiado");
            })
            
            evento(document.getElementById("btnDownload"), "click", ()=>{
                const width = 1200;
                const height = 800;
                const left = (window.innerWidth - width) / 2;
                const top = (window.innerHeight - height) / 2;
                window.open(r.pagamento.link , 'popup', `width=${width}, height=${height}, left=${left}, top=${top}`);
  
 
            })
            
            nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/barcode.min.js`).then(()=>{
                JsBarcode("#codigodebarras", r.pagamento.codigo, {
                    format: "CODE128",
                    lineColor: "#000000",
                    width:1,
                    height:60,
                    displayValue: false
                });
            })

        }, (r)=>{
            goUrl(`/pagamento/${this.pedidoId}`)
          
        })
    }
    
    requestPage(pagina, cb = false){

        let request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/componente.php`)
        request.addData({pagina: pagina, assinatura: this.container?.dataset?.assinatura || false ,  pedido: this.pedidoId})
        request.send().then((r)=>{
            this.container.innerHTML = r.html
            if(cb){
                cb();
            }
  
        }, (r)=>{
            console.log("erro")
 
        })
    }
    
    loadComp(pagina , cb = false){
        this.container.innerHTML = "";
        
        this.requestPage(pagina, cb);
        
        setTimeout(()=>{
            closeLoadingCredito();

        }, 1000)
    }
    
    copiaPedido(){
     
    
    this.copyTextToClipboard(this.idPedido, "Id de Pedido copiado com sucesso");
    
    }
    
    estrutura(){
     this.mostrado = false;
           
        if(document.getElementById("documento")){
       this.numero = document.getElementById("numero")
        this.expira = document.getElementById("expira")
        this.cvv = document.getElementById("cvv")
        this.nome = document.getElementById("nome")
        this.documento = document.getElementById("documento")
        
        this.request = new Request("/conteudo/modulos/pagamento/admins/tools.php");
        
  
        if(document.getElementById("modalNewCard")){
            const myModalEl = document.getElementById("modalNewCard")
            evento(myModalEl, "show.bs.modal", this.mostra.bind(this))
            evento(this.numero, "input", this.validaModal.bind(this))
            evento(this.expira, "input", this.validaModal.bind(this))
            evento(this.cvv, "input", this.validaModal.bind(this))
            evento(this.nome, "input", this.validaModal.bind(this))
            evento(this.documento,  "input", this.validaModal.bind(this))
            evento(this.expira, "blur", this.validaData.bind(this))
            
            this.btnSalvarCartao = document.getElementById("addCartaoModal")
            evento(this.btnSalvarCartao, "click", ()=>{this.salvarCartao.bind(this)(false)})
            this.mycards = {};
            this.cardselect = false;
            this.request.addData({"acao":"mycards"})
            this.request.send().then((r)=>{
                this.listcards(r);
            })
            
            
        }else{
            
            
            
            
          
            
            
            var container = document.getElementById("containerCartao")
            var processador = container.dataset.processador
           
            
            this.previaCartao.bind(this)()  
                
                
                
         this.btnPagar = document.getElementById("btnPagarCredito");
            
            
           this.cep = document.getElementById("cep");
         this.cep.dataset.mascara = 3
        new Mascaras([this.cep]);

            evento(this.cep, "input", this.valida.bind(this))
            evento(this.numero, "input", this.valida.bind(this))
            evento(this.numero, "input", this.valida.bind(this))
            evento(this.expira, "input", this.valida.bind(this))
            evento(this.expira, "blur", this.validaData.bind(this))
            evento(this.cvv, "input", this.valida.bind(this))
            evento(this.nome, "input", this.valida.bind(this))
            evento(this.documento,  "input", this.valida.bind(this))
            
            
       
        }
        }
        
        var request = new Request("conteudo/modulos/pagamento/admins/produto.php");
        request.addData(
            {"acao":"checkout",
            "produto" : 1,
            "pedido": caminho.hash(),
            "assinatura": this.container.dataset.assinatura
            }
            );
        request.send().then((r)=>{
            if(r.pedido){
                
                
                 if(processador == "mercadopago"){
                this.mercadoPago.bind(this)(r.pedido.total);
                }
                
                
                if(document.getElementById("wallet_container")){
                    nownFiles.add(`https://sdk.mercadopago.com/js/v2`).then((r)=>{
                        const mp = new MercadoPago(document.getElementById("wallet_container").dataset.key);
                        const bricksBuilder = mp.bricks();
                        
                        
                        let request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/gateways/index.php`)
                        request.addData({
                            pedido: pegaHash(),
                            meio: "preferenciaMercadoPago"
                        })
                        request.send().then((r)=>{
                     
                            mp.bricks().create("wallet", "wallet_container", {
                                initialization: {
                                    preferenceId: r.item,
                                    redirectMode: "blank"
                                },
                                customization: {
                                    texts: {
                                        valueProp: 'smart_option',
                                    },
                                },
                                callbacks: {
       onReady: () => {},
       onSubmit: () => {
           console.log(r.item)
           
       },
       onError: (error) => console.error(error),
     },
                            });


                        }, (r)=>{
                            console.log(r)
                        })
                        
                        



              
                    })
                }
                    
            
            
  
                
                this.idPedido = caminho.hash();
                document.getElementById("idPedido").innerText = this.idPedido;
                evento(document.getElementById("copyId"), "click", this.copiaPedido.bind(this))

                this.valido.bind(this)(r.pedido)
            }else{
                
            }
        })
    }
    
    mercadoPago(valor){
 
          var container = document.getElementById("containerCartao");
        var processador = container.dataset.processador
        this.processador = processador 
        var key = container.dataset.key
        
        if(!processador || !key){
             reject("Não foram configurados os sistemas de criptografia");
        } 
        
               nownFiles.add(`https://sdk.mercadopago.com/js/v2`).then(()=>{
                    const mp = new MercadoPago(key);
                    
    const cardForm = mp.cardForm({
      amount: valor,
      iframe: false,
      form: {
        id: "form-checkout",
        cardNumber: {
          id: "numero",
          placeholder: "Número do cartão",
        },
        expirationDate: {
          id: "expira",
          placeholder: "MM/YY",
        },
        securityCode: {
          id: "cvv",
          placeholder: "Código",
        },
        cardholderName: {
          id: "nome",
          placeholder: "Titular do cartão",
        },
        issuer: {
          id: "form-checkout__issuer",
          placeholder: "Banco emissor",
        },
        installments: {
          id: "form-checkout__installments",
          placeholder: "Parcelas",
        },        
        identificationType: {
          id: "form-checkout__identificationType",
          placeholder: "Tipo de documento",
        },
        identificationNumber: {
          id: "documentoespelho",
          placeholder: "Número do documento",
        },
        cardholderEmail: {
          id: "form-checkout__cardholderEmail",
          placeholder: "E-mail",
        },
      },
      callbacks: {
        onFormMounted: error => {
          if (error) return console.warn("Form Mounted handling error: ", error);
         
        },
        onSubmit: event => {
               loadingCredito("Processando Pagamento ...");
          event.preventDefault();

          const {
            paymentMethodId: payment_method_id,
            issuerId: issuer_id,
            cardholderEmail: email,
            amount,
            token,
            installments,
            identificationNumber,
            identificationType,
          } = cardForm.getCardFormData();


        
        var request = new Request(`conteudo/modulos/pagamento/admins/gateways/index.php`);
        var data = { 
            pedido : pegaHash() ,
            assinatura: this.container.dataset.assinatura,
            meio: "cartao", 
            titular: this.nome.value,
            documento: this.documento.value,
            "dados": JSON.stringify({
              token,
              issuer_id,
              payment_method_id,
              transaction_amount: Number(amount),
              installments: Number(installments),
              description: `Compra ${moment().format('DD/MM/YYYY HH:mm')}`,
              cep: this.cep.value, 
              payer: {
                email,
                identification: {
                  type: identificationType,
                  number: identificationNumber,
                },
              },
            })}
        request.addData(data)
        request.send().then((r)=>{
  
            
            
            if(r.sucesso){
                 sucessoCredito();
                 
                    if(this.container.dataset.obrigado){
                            defineLocal("pedidoSucesso", this.pedidoId)
                               if(this.pedido.itens.length == 1 && this.container.dataset.addid){
                                goUrl(`pagamento/sucesso/${this.pedido.itens[0].id}`);
                            }else{
                                goUrl(`pagamento/sucesso`);
                            }
                            setTimeout(()=>{
                         
                      closeLoadingCredito();

                }, 2000)
                        }else{
                            goUrl(`pagamento/${this.pedidoId}`);
                            setTimeout(()=>{
                         
                      closeLoadingCredito();

                }, 2000)
                        }
                        
                         start.carrinho.update();
                       start.carteira.carteira();
                
                
                
                     
                
            }else{
                 erroCredito()
                  setTimeout(()=>{
                      closeLoadingCredito();
                      this.habilitaBtns.bind(this)();
                }, 2000)
            }
        }, (r)=>{
            
            erroCredito()
             setTimeout(()=>{
                      closeLoadingCredito();
                      this.habilitaBtns.bind(this)();
                }, 2000)
        })

        }
      },
    });

                    

                })
    }
    
    validaData(){
        console.log(event.currentTarget.value.length)
        if(event.currentTarget.value.length == 5){
            var trato = event.currentTarget.value.split("/")
            event.currentTarget.value = `${trato[0].trim()}/20${trato[1].trim()}`
            this.valida.bind(this)();
        }
    }
    
    setcartao(){
        this.cardselect = event.currentTarget.dataset.hash;
    }
    
    cartaozinho(item){
 
        var id = geraId();
        const cardChoice = document.createElement('div');
        cardChoice.className = 'card-choice';
        
        const fakeCard = document.createElement('div');
        fakeCard.className = 'fake-card';
        
        const label = document.createElement('label');
        label.setAttribute('for', id);
        
        const input = document.createElement('input');
        input.dataset.hash = item.hash
        evento(input, "click", this.setcartao.bind(this))
        input.id = id;
        input.type = 'radio';
        input.name = 'cards';
        
        const spanC = document.createElement('span');
        spanC.className = 'c';
        
        const spanB = document.createElement('span');
        spanB.className = 'b';
        const img = document.createElement('img');
        img.loading = 'lazy';
        img.src = `${dominio}/conteudo/modulos/pagamento/imgs/flat-rounded/${item.flag}.svg`;
        spanB.appendChild(img);
        
        const spanN = document.createElement('span');
        spanN.className = 'n';
        const italic = document.createElement('i');
        spanN.appendChild(italic);
        
        var number = document.createElement("SPAN")
        number.innerText = `•••• ${item.numero}`;
        spanN.appendChild(number)
        
        fakeCard.appendChild(label);
        fakeCard.appendChild(input);
        fakeCard.appendChild(spanC);
        fakeCard.appendChild(spanB);
        fakeCard.appendChild(spanN);
        
        cardChoice.appendChild(fakeCard);
        
        document.getElementsByClassName("grid-cartoes")[0].appendChild(cardChoice);
        return cardChoice;
    }
    
    ativaBtnCartao(){
        document.getElementById("btnPagarCredito").removeAttribute("disabled")
    }
    
    listcards(r){
        
        if(r.lista.length > 0){
            var lista = r.lista
            var i = 0;
            while(i < lista.length){
                var item = lista[i]
                this.mycards[item.hash] = this.cartaozinho(item);
                if(item.default == "1"){
                    this.mycards[item.hash].getElementsByTagName("input")[0].click();
                    this.cardselect = item.hash;
                    this.ativaBtnCartao();
                }

                i++;
            }
        }


    }
    
    itemLista(item){
         
         var resumo = document.getElementsByClassName("resumo-compra")


         
        var li = document.createElement("LI")
        
        var label = document.createElement("LABEL")
        label.innerText =  item.infos.nome
        
        var contador = document.createElement("SPAN")
        contador.innerText = item.comprado
        contador.classList.add("count")
        label.appendChild(contador)
        
        var valor = document.createElement("SPAN")
        valor.classList.add("valor")
        valor.innerText = paraPreco(item.preco)
        
        li.appendChild(label)
        li.appendChild(valor)
        

        var i = 0;
        while(i < resumo.length){
             var clone = li.cloneNode(true);
             resumo[i].appendChild(clone);
             i++;
        }
    }
    
    diferencaData(data){
        const pedidoData = new Date(data); 
        const agora = new Date();  // Data e hora atuais
        
        const diferencaMs = agora - pedidoData;
        const diferencaSegundos = Math.floor(diferencaMs / 1000);
        return diferencaSegundos;
    }
    
    formatarTempo(segundos) {
            const minutos = Math.floor(segundos / 60);
            const segundosRestantes = segundos % 60;
            return `${String(minutos).padStart(2, '0')}:${String(segundosRestantes).padStart(2, '0')}`;
        }

    timer(segundos) {
            const elementoTempo = document.querySelector('.tempo');
            
            const atualizarTimer = () => {
                if (segundos > 0) {
                    segundos--;
                    elementoTempo.textContent = this.formatarTempo(segundos);
                } else {
                    clearInterval(intervalo);
                    this.expirado.bind(this)();
                }
            };

            const intervalo = setInterval(atualizarTimer, 1000);
            elementoTempo.textContent = this.formatarTempo(segundos);
        }
    
    expirado(){

        var timer = parseInt(document.getElementsByClassName("expira")[0].dataset.time || 5);   
        document.getElementsByClassName("card-nown-checkout")[0].innerHTML = `
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="m-0">Pedido Expirado</h1> 
                <p>Pedido feito a mais de ${timer} minutos</p>
            </div> 
        </div>
        <div class="card-body">
            <p>Lamentamos informar que o prazo para concluir a finalização do seu pedido, por alguns motivos:</p>
            
            <ul class="list-group">
                <li class="list-group-item"><strong>Segurança:</strong> Para garantir a segurança das transações e proteger os dados dos nossos clientes.</li>
                <li class="list-group-item"><strong>Limite de Estoque:</strong> Alguns itens podem ter disponibilidade limitada. Quando o prazo do pedido expira, os itens são liberados para outros clientes.</li>
                <li class="list-group-item"><strong>Atualização de Preços:</strong> O preço dos itens pode ser ajustado periodicamente. Se o pedido não for finalizado a tempo, o valor pode ser atualizado.</li>
            </ul>
        </div>
        <div class="card-footer" style="padding: 30px 25px; border: 0px">
            <button class="btn btn-n-primaria btn-nown-style">Voltar para a Home</button>
        </div>
        `
        
        iziToast.error({
            icon: 'bi bi-bag-x',
            title: 'Atenção',
            message: 'Pedido Expirado'
        });

    }
    
    valido(pedido){

        if(document.getElementsByClassName("expira").length == 1){
            var expira = parseInt(document.getElementsByClassName("expira")[0].dataset.time || 5) * 60;
            var feito = this.diferencaData(pedido.data);

            if(feito > expira){
                this.expirado.bind(this)();
                return false;
            }else{
                this.timer.bind(this)(expira - feito)
            }
        }

        

        this.pedido = pedido

        var itens = pedido.itens;
        var i = 0;
        this.temRecorrencia = false;
        while(i < itens.length){
            if(itens[i].recorrente){
                this.temRecorrencia = true;
            }
            this.itemLista.bind(this)(itens[i])
            i++;
        }
        
        if(pedido.total){
            var total = paraPreco(pedido.total);
            var totais = document.getElementsByClassName("totalCompra")
            var y = 0;
            while(y < totais.length){
                totais[y].innerText = total
                y++;
            }
        
        }
           
           /*
        switch(parseInt(pedido.estado)){
            case 0:
                this.cancelado.classList.remove("d-none")
                evento(this.cancelado.getElementsByTagName("button")[0], "click", this.carrinho.bind(this))
                this.getways.remove();
                break;
            case 1:
                new CartaoCredito();
                new PayPal(this.pedido);
              
              
                evento(document, 'visibilitychange', this.tempo.bind(this))

                this.getways.classList.remove("d-none")
                this.vencimento = pedido.data;
                this.tempo.bind(this)();
                break;
            default:
                console.log("fluxo de comprovante de pagamento")
                break;
        } 
        */
        
        this.btns = document.getElementsByClassName("btn-final-checkout")
        evento(Array.from(this.btns), "click", this.paga.bind(this))
    }
    
    desabilitaBtns(){
        var i = 0;
        while(i < this.btns.length){
            this.btns[i].setAttribute("disabled", "");
            
            i++;
        }
        
    }
    
    habilitaBtns(){
         var i = 0;
        while(i < this.btns.length){
            this.btns[i].removeAttribute("disabled");
            
            i++;
        }
    }
    
    paga(){
         var foco = event.currentTarget.dataset.target
         if(this.processador == "mercadopago" &&   foco == "cartao"){
             document.getElementById("form-checkout__submit").click();
             return;
         }

       
        loadingCredito("Processando Pagamento ...");
        this.desabilitaBtns.bind(this)();
        
        var request = new Request(`conteudo/modulos/pagamento/admins/gateways/index.php`);
        
        
        var data = { pedido : this.pedidoId , meio: foco,  assinatura: this.container.dataset.assinatura} 
        if(foco == "cartao"){
            if(this.criptografado){
                data.encrypted = this.criptografado
                data.titular = this.nome.value
                data.documento = this.documento.value
            }
        }
        
        request.addData(data)
        request.send().then((r)=>{
            setTimeout(()=>{
                sucessoCredito();
                 window.history.pushState(null, null, `${window.location.href}/${foco}`);
                  switch(foco){
                case 'pix':
                    this.loadComp.bind(this)("pix", this.loadPix.bind(this));
                    break;
                case 'boleto':
                    this.requestPage("boleto", this.loadBoleto.bind(this));
                    setTimeout(()=>{
                        closeLoadingCredito();
                    }, 2000)

                    break;
                case 'cripto':
                    break;
                case 'cartao':
                case 'administrador':
                case 'carteira':
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso",
                        text: "Pedido feito com sucesso",
                        showConfirmButton: false,
                        timer: 1500
                        
                    });
                    if(this.temRecorrencia){
                        start.tentaLogin(pegaLocal("sessao"));
                    }else{
                         if(this.container.dataset.obrigado){
                            defineLocal("pedidoSucesso", this.pedidoId)
                            
                            if(this.pedido.itens.length == 1 && this.container.dataset.addid){
                                goUrl(`pagamento/sucesso/${this.pedido.itens[0].id}`);
                            }else{
                                goUrl(`pagamento/sucesso`);
                            }
                            
                            
                            
                        }else{
                            goUrl(`pagamento/${this.pedidoId}`);
                        }
                    }
                    
                    
                    start.carteira.carteira()
                    start.carrinho.update();

        
                    break;
      
            }
            }, 3000)
          
        }, (r)=>{
            setTimeout(()=>{
              erroCredito();
                setTimeout(()=>{
                      closeLoadingCredito();
                      this.habilitaBtns.bind(this)();
                }, 2000)
            }, 3000)
            
        
   
        })
    }
    
    validaDoc(){
        var doc  = this.documento
        if(document.getElementById("documentoespelho")){
            document.getElementById("documentoespelho").value = doc.value.replace(/[^\d]/g, ''); 
        }

        
        if(doc.value.length == 14 || doc.value.length == 18){
           if(doc.value.length == 14){
               var validade = new  ValidaInfo(doc.value, "cpf");
               if(document.getElementById("form-checkout__identificationType")){
                   document.getElementById("form-checkout__identificationType").value = "CPF"
               }
               return validade.valida();
           }
           
           if(doc.value.length == 18){
               var validade = new  ValidaInfo(doc.value, "cnpj");
                if(document.getElementById("form-checkout__identificationType")){
                   document.getElementById("form-checkout__identificationType").value = "CNPJ"
               }
               return validade.valida();
           }
           
        }else{
            return false;
        }
        
        return false;
    }
    
    salvarCartao(cb = false) {
    return new Promise((resolve, reject) => {
        
        
        
        
        console.log(this)
        
        
        
        var container = document.getElementById("containerCartao");
        var processador = container.dataset.processador
        var key = container.dataset.key
        
        if(!processador || !key){
             reject("Não foram configurados os sistemas de criptografia");
        } 
        
        
        switch(processador){

            case 'pagbank':
                 nownFiles.add(`https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js`).then(() => {
            var trato = this.expira.value.split("/");
            var numero = this.numero.value.replace(/\s+/g, '');
           
            const card = PagSeguro.encryptCard({
                publicKey: key,
                holder: this.nome.value,
                number: numero,
                expMonth: trato[0],
                expYear: trato[1],
                securityCode: this.cvv.value
            });
            
            const encrypted = card.encryptedCard;
            const hasErrors = card.hasErrors;
            const errors = card.errors;

            if (hasErrors) {
                animarCss("#corpoModalNewCard", "shakeX");
                errors.forEach(erro => {
                    let mensagem;
                    switch (erro.code) {
                        case 'INVALID_EXPIRATION_YEAR':
                            mensagem = "Ano de Expiração Inválido";
                            break;
                        case 'INVALID_HOLDER':
                            mensagem = "Nome do Titular Incorreto";
                            break;
                        case 'INVALID_EXPIRATION_MONTH':
                            mensagem = "Mês de Expiração Inválido";
                            break;
                        default:
                            mensagem = erro.message;
                            break;
                    }
                    
                    console.log(erro);
                    iziToast.error({
                        icon: "bi bi-x-circle",
                        title: `Erro`,
                        message: mensagem
                    });
                });
                reject(errors);
            } else {
                if (!cb) {
                    this.request.addData({
                        "acao": "saveCard",
                        "cartao": numero,
                        "cripto": encrypted,
                        "validade": `${parseInt(trato[1])}-${parseInt(trato[0])}-01`
                    });
                    
                    this.request.send().then((r) => {
                        if (r.sucesso) {
                            var cartao = r.cartao;
                            if (!this.mycards[cartao.hash]) {
                                this.mycards[cartao.hash] = this.cartaozinho(cartao);
                            }
                            
                            this.mycards[cartao.hash].getElementsByTagName("input")[0].click();
                            this.cardselect = cartao.hash;
                            resolve(cartao);
                        } else {
                            reject('Erro ao salvar cartão');
                        }
                    }).catch(error => reject(error));
                } else {
                    resolve(encrypted);
                }
            }
        }).catch(error => reject(error));
                
                
                
                break;
        }
    });
}

    mostra(){
        if(!this.mostrado){
            this.mostrado = true;
            setTimeout(()=>{
                this.previaCartao.bind(this)()
            }, 1)
        }

    }
    
    validaModal(){
         if(this.numero.value.length == 19 && this.expira.value.length == 9 && this.cvv.value.length > 2 && this.nome.value.length > 8 && this.validaDoc.bind(this)()) {
            this.btnSalvarCartao.removeAttribute("disabled")
        }else{
            this.btnSalvarCartao.setAttribute("disabled", "")
        }
    }
    
    valida(){

        var valido = true;
        var erros = [];
        var numero = this.numero.value.replaceAll(" ", "");
        if(numero.length != 16){
            valido = false;
        }
        
        if(this.expira.value.length != 7){
             valido = false;
        }
        
        if(this.cvv.value.length < 3 ){
             valido = false;
        }
        
        if(this.nome.value.length < 8){
             valido = false;
        }
        
        if(!this.validaDoc.bind(this)()){
             valido = false;
        }
        
        if(this.cep.value.length < 9){
            valido = false;
        }
        

        
        if(valido){
             this.btnPagar.removeAttribute("disabled")
            var cartao = this.salvarCartao.bind(this)(true).then((r)=>{
                if(r){
                    this.criptografado = r;
                    this.btnPagar.removeAttribute("disabled")
                }
            });
        }else{
            this.btnPagar.setAttribute("disabled", "")
        }

    }
    
    previaCartao(){
        if(!document.getElementById("documento")){
            return;
        }
        document.getElementById("documento").dataset.mascara = 17
    
        
        
         var urls = [
            `${dominio}/assets/bibliotecas/credito/card.css`,
            `${dominio}/assets/bibliotecas/credito/jquery.card.js`,
            `${dominio}/assets/aplicativo/jquery/min.js`,
            `${dominio}/assets/bibliotecas/mascaras/mask.js` 
            ];
        

        nownFiles.add(urls).then(()=>{
            
            setTimeout(()=>{
                new Mascaras([document.getElementById("documento")]);
            }, 1000)

            var tamanhho = parseInt(document.getElementById("previaDoCartao").dataset.size)
            
            
            var card = new Card({
                form: '#formularioDoCartao', 
                container: '#previaDoCartao',

      formSelectors: {
        numberInput: 'input#numero',
        expiryInput: 'input#expira', 
        cvcInput: 'input#cvv',
        nameInput: 'input#nome' 
    },

    width: tamanhho, 
    formatting: true, 

     messages: {
        validDate: 'valid\ndate', 
        monthYear: 'mm/aa',
    },

    
        placeholders: {
        number: '•••• •••• •••• ••••',
        name: 'Nome Completo',
        expiry: '••/••',
        cvc: '•••'
    },

    masks: {
        cardNumber: '•' 
    },

    debug: true
});


   
      
        })
    }
    
    novoCartao(){
        
    }
}
 
function loadingCredito(texto){
    var overlay = document.querySelector('.overlay-loading-cartao');
    var modal = document.querySelector('.loading-cartao');
    
    overlay.style.display = 'block';
    setTimeout(()=>{ overlay.style.opacity = 1}, 50);
    
    modal.classList.add('active');
    setTimeout(()=>{ modal.classList.add('step-2') }, 2500);
    setTimeout(()=>{ modal.classList.add('is-loading') }, 2900);
    
    var frase = modal.querySelector('.frase');
    frase.innerHTML = texto;
}

function closeLoadingCredito(){
    var overlay = document.querySelector('.overlay-loading-cartao');
    var modal = document.querySelector('.loading-cartao');
    
    overlay.style.opacity = 0;
    setTimeout(()=>{ overlay.style.display = 'none' }, 270);
    
    modal.classList.remove('active');
    
    setTimeout(()=>{
        modal.className = 'loading-cartao';
    }, 1000);
}

function sucessoCredito(){
    var overlay = document.querySelector('.overlay-loading-cartao');
    var modal = document.querySelector('.loading-cartao');
    
    modal.classList.remove('is-loading', 'is-error');
    modal.classList.add('is-returned', 'is-success');
    
    var frase = modal.querySelector('.frase');
    frase.style.opacity = 0;
    setTimeout(()=>{
        frase.innerHTML = 'Sucesso!';
        frase.style.opacity = 1;
    }, 1000)
}

function erroCredito(){
    var overlay = document.querySelector('.overlay-loading-cartao');
    var modal = document.querySelector('.loading-cartao');
    
    modal.classList.remove('is-loading', 'is-success');
    modal.classList.add('is-returned', 'is-error');
    
    var frase = modal.querySelector('.frase');
    frase.style.opacity = 0;
    setTimeout(()=>{
        frase.innerHTML = 'Falha, tente novamente!';
        frase.style.opacity = 1;
    }, 1000)
}

function paginaPagamento(){
    var logs = ["meus-pagamentos", "minhas-assinaturas", "meus-pedidos"];
    var hash = pegaHash();
    if(logs.includes(hash)){
        nownFiles.add(`${dominio}/conteudo/modulos/pagamento/assets/analitcs.js`).then((r)=>{
            new AnalitcsPagamentos(hash);
        })
    }else{
        new CheckOut();
    }
   
}