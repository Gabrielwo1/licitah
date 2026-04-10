function desabilidaEdicao(){
    document.getElementById("destinatario").getElementsByTagName("select")[0].setAttribute("disabled", "")
    var desabilita = document.getElementsByClassName("desabilitar")
    var i = 0;
    while(i < desabilita.length){
        var item = desabilita[i]
        item.getElementsByTagName("input")[0].setAttribute("disabled", "")
        i++;
    }
}

class EstruturaNotificacaoTransacao{
    constructor(){
        this.body = document.getElementById("listavariaveis").getElementsByClassName("card-body")[1]
        
        
        this.ul = document.createElement("UL")
        this.ul.classList.add("list-group","list-group-flush")
        this.body.appendChild(this.ul);

        evento(Array.from(document.getElementById("disparador").getElementsByClassName("form-select")), "input", this.mudanca.bind(this))
        this.mudanca.bind(this)();
    }
    
    mudanca(){
        var modulo = document.getElementById("disparador").getElementsByClassName("form-select")[0].value
        var form = document.getElementById("disparador").getElementsByClassName("form-select")[1].value
        
        if(modulo !== "0" && form !== "0"){
            var request = new Request(`${dominio}/conteudo/modulos/notificacoes/admins/chaves.php`);
            request.addData({
                modulo: modulo,
                form: form
            })
            request.send().then((r)=>{
                var itens = r.itens;
                
                this.basico.bind(this)();
                
                
                var l = document.createElement("LI")
                l.classList.add("list-group-item", "fw-700");
                l.innerText = "Itens do Conteúdo"
                
                for(let c in itens){
                     this.li.bind(this)(c, itens[c]);
                }
                
                
                
            }, (r)=>{
                        this.basico.bind(this)();
            })
            
            
            
        }else{
                    this.basico.bind(this)();
        }

    }
    
    basico(){
        this.ul.innerHTML = "";
        
         var l = document.createElement("LI")
        l.classList.add("list-group-item");
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var span = document.createElement("SPAN")
        span.classList.add("fw-700", "fs-14", "col-6")
        span.innerText = `CHAVE`;
        row.appendChild(span)
        
        var span = document.createElement("SPAN")
        span.classList.add("fw-700", "fs-14", "col-6")
        span.innerText = "VALOR";
        row.appendChild(span)
        l.appendChild(row)
        this.ul.appendChild(l)
        
        
        var valores = [
            {chave: "user_display", valor: "Nome de Exibição do Usuário"},
            {chave: "user_id", valor: "ID do Usuário"},
            {chave: "user_email", valor: "E-mail do Usuário"},
            {chave: "user_telefone", valor: "Telefone do Usuário"},
            {chave: "user_user", valor: "Username do Usuário"},
            {chave: "user_nome", valor: "Primeiro Nome do Usuário"},
            {chave: "user_sobrenome", valor: "Sobrenome do Usuário"},
            ];
            
        var i = 0;
        while(i < valores.length){
            var casa = valores[i]
            console.log(casa)
            this.li.bind(this)(casa.chave, casa.valor);
            i++;
        }
        
    }
    
    copiarTexto() {
      const textoParaCopiar = `{{${event.currentTarget.dataset.chave}}}` 
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(textoParaCopiar)
          .then(() => {
            iziToast.success({
                title: 'Sucesso',
                message: 'Chave copiada com sucesso.',
                icone: 'bi bi-check-circle'
            });
          })
          .catch(err => {
            console.error("Falha ao copiar o texto: ", err);
          });
      } else {

        alert("Seu navegador não suporta a API de Clipboard.");
      }
    }
    
    li(chave, valor){
        var l = document.createElement("LI")
        l.classList.add("list-group-item", "list-group-item-action");
        l.dataset.chave = chave
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var span = document.createElement("SPAN")
        span.classList.add("fw-700", "fs-12", "col-6")
        span.innerText = `{{${chave}}}`;
        row.appendChild(span)
        
        var span = document.createElement("SPAN")
        span.classList.add("fw-500", "fs-12", "col-6")
        span.innerText = valor;
        row.appendChild(span)
        
        l.appendChild(row)
        evento(l, "click", this.copiarTexto.bind(this))
        this.ul.appendChild(l)

    }
}


function moduloNotificacoesExcecao(){
    
    var estrutura = fluxoPage("a/notificacoes");
    
    switch(estrutura[0]){
        case 'notificacao':
            if(estrutura.length > 1){
                 desabilidaEdicao(); 
            }
            break;
        case 'transacao':
            new EstruturaNotificacaoTransacao();
            break;
    }
    

}