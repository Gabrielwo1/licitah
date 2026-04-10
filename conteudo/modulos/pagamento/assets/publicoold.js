class CartaoCredito{
    constructor(){
        var urls = [
            `${dominio}/assets/aplicativo/jquery/371.js`,
            `${dominio}/assets/bibliotecas/credito/card.css`,
            `${dominio}/assets/bibliotecas/credito/jquery.card.js`
            ];
        
 
        packLoad(urls).then(()=>{
            $('#cartaoForm').card({

    container: '#cartao',
    formSelectors: {
        numberInput: 'input#numero',
        expiryInput: 'input#expira', 
        cvcInput: 'input#cvc',
        nameInput: 'input#nome' 
    },

    width: 400,
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
    debug: false
});
        })
        
       
    }
}

class PayPal{
    constructor(pedido){

          var script = document.createElement("SCRIPT")
                script.src = `https://www.paypal.com/sdk/js?client-id=AcqidPVcUF8aOp0D2GaZgi2IIILsglLuIsd0cc7FAFCWsvy2vBfWGpuPxNQVA1_-vEYslgd6kZS6EfE2&currency=BRL`
                document.getElementsByTagName("head")[0].appendChild(script)
                script.onload = ()=>{
                    console.log("paypal carregado")
                     paypal.Buttons({
    createOrder: function(data, actions) {
      // Função para criar a ordem de pagamento
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: pedido.total
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // Função a ser executada quando o pagamento for aprovado
      return actions.order.capture().then(function(details) {
        // Exibir mensagem de confirmação
        alert('Pagamento completado! ID do Pedido: ' + data.orderID);
      });
    }
  }).render('#paypal-button-container');
                }
    }
}

class PagSeguro{
    constructor(){
        const options = {
  method: 'POST',
  headers: {
    accept: 'application/json',
    Authorization: 'Bearer <token>',
    'content-type': 'application/json'
  },
  body: JSON.stringify({type: 'card'})
};

fetch('https://sandbox.api.pagseguro.com/public-keys', options)
  .then(response => response.json())
  .then(response => console.log(response))
  .catch(err => console.error(err));
    }
}

class PaginaPagamento{
    constructor(){
        this.ajax = this.ajax.bind(this)
        this.pedido = caminho.hash()
        this.base.bind(this)();
        
        this.btnEmula = document.getElementById("emularPagamento")
        evento(this.btnEmula , "click", this.emular.bind(this))
    }
    
    emular(){
        document.getElementById("processandoPagamento").classList.remove("d-none")
        document.getElementById("processandoPagamento").classList.add("d-flex")
        this.btnEmula.setAttribute("disabled", "")
        this.btnEmula.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`
        
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            console.log(request.responseText)
                 setTimeout(()=>{
            goUrl("")
            Swal.fire({

  icon: "success",
  title: "Pagamento feito com sucesso",
  showConfirmButton: false,
  timer: 1500
});
        }, 3000)
        }
        request.open("GET", `${dominio}/conteudo/modulos/pagamento/admins/status.php`)
        request.send()
        
   
    }
    
    base(){
        var data = new FormData();
        data.append("acao", "checkout");
        data.append("produto", 1);
        data.append("pedido", this.pedido);
        this.ajax(data, this.monta.bind(this))
    }
    
    atualizaTimer(segundos){
        const minutos = Math.floor(segundos / 60); // Obtém a quantidade de minutos
        const segundosRestantes = segundos % 60; // Obtém os segundos restantes
        
        const minutosFormatados = String(minutos).padStart(2, '0');
        const segundosFormatados = String(segundosRestantes).padStart(2, '0');
        if(document.getElementById("timer")){
                    document.getElementById("timer").innerHTML = `Seu pedido expira em : <strong>${minutosFormatados}:${segundosFormatados}</strong>`
        }

    }
    
    tempo() {
        if(this.interval){
            clearInterval(this.interval);
        }
        var str =  this.vencimento;

  const regex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
  if (!regex.test(str)) {
    console.log("Formato inválido da data.");
    return false;
  }


  const dataInserida = new Date(str);
  const dataAtual = new Date();

  const diferenca = dataAtual - dataInserida;
  if (diferenca > 300000) {

    this.recarrega.bind(this)()
  } else {
   
    this.tempoRestante = 300000 - diferenca;
    
    var faltante = parseInt(this.tempoRestante / 1000)
    console.log(faltante)
   

     this.interval = setInterval(() => {
      this.tempoRestante -= 1000;

      if (this.tempoRestante > 0) {
           var faltante = parseInt(this.tempoRestante / 1000)
           if(document.getElementById("timer")){
               document.getElementById("timer").classList.add("mb-2")
            this.atualizaTimer.bind(this)(faltante)
           }else{
                clearInterval(this.interval);
           }
            
      } else {
        clearInterval(this.interval);

        setTimeout(this.recarrega.bind(this), 1000);
      }
    }, 1000);
    return true;
  }
}

    carrinho(){
        goUrl("carrinho")
    }
    
    recarrega(){
         var hash = caminho.hash();
          goUrl(`pagamento/${hash}`)
    }
    
    itemLista(item){
        var li = document.createElement("LI")
        li.classList.add("list-group-item","d-flex","justify-content-between","align-items-center")
        
        var esquerda = document.createElement("DIV")
        esquerda.classList.add("fw-500","fs-14")
        esquerda.innerText = item.infos.nome
        
        var direita = document.createElement("DIV")
        direita.classList.add("fw-500","fs-14")
        direita.innerText = `${item.comprado} x R$${item.preco}`
        
        li.appendChild(esquerda)
        li.appendChild(direita)
        document.getElementById("listaProdutos").insertBefore(li, document.getElementById("listaProdutos").firstChild);

    }
    
    monta(r){
      
        this.cancelado = document.getElementById("cancelado")
        this.getways = document.getElementById("getways")
        if(r.pedido){
           let pedido = r.pedido
           this.pedido = pedido
          
          
        var itens = pedido.itens;
        var i = 0;
        while(i < itens.length){
            this.itemLista.bind(this)(itens[i])
            i++;
        }
        
        if(pedido.total){
            document.getElementById("totalPrice").innerText = pedido.total
        }
           
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
        }
        else{
           // goUrl("pagamento");
        }
        
    }
    
    ajax(data, cb = false){
        var request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                var obj = JSON.parse(request.responseText)
                if(obj.sucesso && cb){
                    cb(obj)
                }else{
                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/produto.php`)
        request.send(data)
    }
    
    
}

function paginaPagamento(){
    if(document.getElementById("totalPrice")){
        new PaginaPagamento();
    }
}