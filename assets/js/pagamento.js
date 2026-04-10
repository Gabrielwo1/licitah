class PagamentoCadastro{
    constructor(){
        
        this.valida = this.valida.bind(this)
        
        this.btn = document.getElementById("btnCadastro")
        
        this.nome = document.getElementById("nome")
        this.celular = document.getElementById("celular")
        this.email = document.getElementById("email")
        this.cep = document.getElementById("cep")
        this.cpf = document.getElementById("cpf")
        
        
        evento(this.nome, "input", this.valida)
        evento(this.celular, "input", this.valida)
        evento(this.email , "input", this.valida)
        evento(this.cep, "input", this.valida)
        evento(this.cpf, "input", this.valida)
        
        
        if(this.cep.dataset.cep){
            this.viacep.bind(this)(this.cep.dataset.cep);
        }
        
        
        
         this.cep.addEventListener("input", ()=> {this.viacep.bind(this)(false)})
        
        this.endereco = false;
        
    }
    
    valida(){
        var ativo = true;
        
        if(!this.nome.value || !this.nome.value.length < 10){
            ativo = false;
        }
        
        if(this.numero && !this.numero.value){
            ativo = false;
        }
        
        
        if(!this.endereco){
            ativo = false;
        }
        
        if(!this.cpf.value || !this.validarCPF.bind(this)(this.cpf.value)){
            ativo = false;
        }
        
        if(!this.celular.value || !this.validaFone.bind(this)(this.celular.value)){
            ativo = false;
        }
        
        if(!this.email.value || !this.validaEmail.bind(this)(this.email.value)){
            ativo = false;
        }
        
        
        
        
        
        
        
        if(ativo){
            this.btn.classList.remove("btn-desativado")
            this.btn.classList.add("btn-ativado")
            this.btn.removeAttribute("disabled")
        }else{
             this.btn.classList.add("btn-desativado")
            this.btn.classList.remove("btn-ativado")
            this.btn.setAttribute("disabled", "")
        }
    }
    
    enderecoValido(r){
        this.endereco = r
        
        this.cep.closest("DIV").remove();
        
        var card = document.createElement("DIV")
        card.classList.add("card", "mt-3", "rounded-0", "border-0")
        
        
        var header = document.createElement("DIV")
        header.classList.add("card-header","border-0","bg-primary","text-light","rounded-0")
        
        var flex = document.createElement("DIV")
        flex.classList.add("d-flex","justify-content-between","align-items-center")
        
        var div = document.createElement("DIV")
        var h2 = document.createElement("H2")
        h2.classList.add("m-0","fs-18")
        h2.innerText = "Endereço"
        
        div.appendChild(h2)
        
        var div2 = document.createElement("DIV")
        div2.classList.add("d-flex","justify-content-end","align-items-center")
        
        var span = document.createElement("SPAN")
        span.innerText = "Trocar o endereço?"
        
         var btn = document.createElement("BUTTON")
        btn.classList.add("btn","btn-light","btn-sm","ms-2")
        btn.innerText = "Trocar Endereço"
        evento(btn, "click", this.deleta.bind(this))
        div2.appendChild(span)
        div2.appendChild(btn)
        
        flex.appendChild(div)
        flex.appendChild(div2)
        header.appendChild(flex)
        card.appendChild(header)
        
        
   
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "position-relative")
        
        var p = document.createElement("P")
        p.innerText = "Brasil"
        
        var p2 = document.createElement("P")
        p2.innerText = `${r.localidade}, ${r.uf}, ${r.cep}`
        
        var p3 = document.createElement("P")
        p3.innerText = r.logradouro
        
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var col = document.createElement("DIV")
        col.classList.add("col-12","col-lg-12", "d-lg-flex", "justify-content-start", "gap-3", "align-items-center")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "m-0")
        label.innerText = "Número"
        
        this.numero = document.createElement("INPUT")
        this.numero.classList.add("form-control")
        
        col.appendChild(label)
        col.appendChild(this.numero)
        
        evento(this.numero , "input", this.valida)
        
        
        var col2 = document.createElement("DIV")
        col2.classList.add("col-12","col-lg-12", "d-lg-flex", "justify-content-start", "gap-3", "align-items-center", "mt-3")
        
        var label = document.createElement("LABEL")
        label.classList.add("form-label", "m-0")
        label.innerText = "Complemento"
        
        this.complemento = document.createElement("INPUT")
        this.complemento.classList.add("form-control")
        
        evento(this.complemento , "input", this.valida)
        
        col2.appendChild(label)
        col2.appendChild(this.complemento)
        
        row.appendChild(col)
        row.appendChild(col2)
        
        body.appendChild(p)
        body.appendChild(p2)
        body.appendChild(p3)
        body.appendChild(row)
        card.appendChild(body)
        
       
        
        
        this.boxAdress = card
        
        
       
        
        document.getElementById("containerEndereco").innerHTML = ""
        document.getElementById("containerEndereco").appendChild(card)
        
        
        
    }
    
    deleta(){
        this.endereco = false;
        this.boxAdress.remove();
        
        var div = document.createElement("DIV")
        div.classList.add("mt-3")
        
        var label = document.createElement("LABEL")
        label.innerText = "CEP"
        
        this.cep = document.createElement("INPUT")
        this.cep.classList.add("form-control")
        
        div.appendChild(label)
        div.appendChild(this.cep)
        
        
        document.getElementById("cadastroPagamento").getElementsByClassName("card-body")[0].appendChild(div)
        
        
        evento(this.cep, "input", this.valida)
        this.cep.addEventListener("input", ()=> {this.viacep.bind(this)(false)})
        this.valida();
        
      
    }
    
    viacep(cep = false){
        if(this.cep.value.length == 9 || cep){
            var cep = !cep ? this.cep.value.replaceAll("-", "") : cep;
            const xhttp = new XMLHttpRequest();
  xhttp.onload = ()=> {
    var obj = JSON.parse(xhttp.responseText)
    if(obj.cep){
        this.enderecoValido.bind(this)(obj);
    }
    
  }
  xhttp.open("GET", `https://viacep.com.br/ws/${cep}/json/`);
  xhttp.send();
            
            
        }
    }
    
    validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
      return false;
    }
    let sum = 0;
    let remainder;
    for (let i = 1; i <= 9; i++) {
      sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) {
      remainder = 0;
    }
    if (remainder !== parseInt(cpf.substring(9, 10))) {
      return false;
    }
    sum = 0;
    for (let i = 1; i <= 10; i++) {
      sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) {
      remainder = 0;
    }
    if (remainder !== parseInt(cpf.substring(10, 11))) {
      return false;
    }
    return true;
  }
  
    validaFone(inputNumerico){
        inputNumerico = inputNumerico.replace(/\D/g, '');
        if (/^\d{10,11}$/.test(inputNumerico)) {
            const telefone = inputNumerico.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1)$2-$3');
            return true;
        }else{
            return false;
        }
    }
    
    validaEmail(input){
         if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input)) {
    return true;
        }else{
            return false;
        }
    }
    
  
}

function paginaPagamento(){
    if(document.getElementById("cadastroPagamento")){
        new PagamentoCadastro();
    }
}