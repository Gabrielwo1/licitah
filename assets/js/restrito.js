class Restricao{
    constructor(){

        var url = window.location.href
        this.redir = url.split("restrito/")[1];
        
        evento(document.getElementById("btnCadastro"), "click", this.cadastro.bind(this))
        evento(document.getElementById("btnLogin"), "click", this.login.bind(this))
    }
    
    defineLocal(index, value){
        var obj = {
            foco: value,
            time: Date.now()
        }
        var obj = JSON.stringify(obj)
    if (typeof localStorage !== "undefined") {
        localStorage.setItem(index, obj);
    }
        
    }
    
    cadastro(){
        this.defineLocal("rediraffterlogin", this.redir);
        event.currentTarget.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        setTimeout(()=>{
            goUrl("cadastro");
        }, 400)
    }
    
    login(){
        this.defineLocal("rediraffterlogin", this.redir);
         event.currentTarget.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        setTimeout(()=>{
            goUrl("acesso");
        }, 400)
    }
}

function paginaRestrito(){
    new Restricao();
}