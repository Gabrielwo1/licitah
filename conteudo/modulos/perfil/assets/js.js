class PerfilSeguranca{
    constructor(){
       this.btns = document.getElementsByClassName("trocar")
       evento(Array.from(this.btns), "click", this.troca.bind(this))
       this.modal = new bootstrap.Modal('#trocar', {
           keyboard: false
       })
       this.pop = document.getElementById("trocar");
       this.titulo = this.pop.getElementsByClassName("titulo")[0]
        
        
    }
    
    troca(){
        switch(event.target.dataset.foco){
            case 'email':
                var funcao = this.email.bind(this)
                this.titulo.innerText = "Trocar E-mail"
                break;
            case 'cpf':
                var funcao = this.cpf.bind(this)
                 this.titulo.innerText  = "Trocar CPF"
                break;
            case 'senha':
                var funcao = this.senha.bind(this)
                 this.titulo.innerText  = "Trocar Senha"
                break;
            case 'telefone':
                var funcao = this.telefone.bind(this)
                 this.titulo.innerText  = "Trocar Telefone"
                break;
            default:
                var funcao = false;
                break;
        }
        
        
        if(funcao){
            funcao();
             this.modal.show();
        }
       

    }
    
    email(){
        console.log("email")
    }
    
    senha(){
        console.log("senha")
    }
    
    telefone(){
        console.log("telefoe")
    }
    
    cpf(){
        console.log("cpf")
    }
}

function perfilSeguranca(){
   new PerfilSeguranca();
}


class ModuloPerfil{
    constructor(start){
        
        this.selecionar = this.selecionar.bind(this)
        this.perfil = this.perfil.bind(this)
        this.btns = document.getElementsByClassName("btnPerfil")
        this.ajax = this.ajax.bind(this)
        this.html = this.html.bind(this)
        this.imagem = this.imagem.bind(this)
        this.salvar = this.salvar.bind(this)
        this.formata = this.formata.bind(this)

        this.btnsSalvar = document.getElementsByClassName("btnSalvar")
        evento(Array.from(this.btnsSalvar), "click", this.salvar)
        
        this.conteudo = document.getElementById("conteudoPerfil")
        this.card = document.getElementById("cardPerfil")
        
        evento(Array.from(this.btns), "click", this.selecionar)
        
        
        var i = 0;

        while(i < this.btns.length){
            if(this.btns[i].dataset.bloco == start){
                 this.btns[i].click();
                break;
            }
           
            i++;
        }

    }
    
    selecionar(){
  
        var i = 0;

        while(i < this.btns.length){
            if(this.btns[i].classList.contains("active")){
                this.btns[i].classList.remove("active")
                break;
            }
            i++;
        }
        

var alvo = event.target.classList.contains("btnPerfil") ? event.target : event.target.closest(".btnPerfil");
alvo.classList.add("active");
var target = alvo.dataset.bloco;
window.history.pushState(null, null, `${dominioAdress}/a/perfil/${target}`); 
this.target = target

        
        this.conteudo.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        
        if(document.getElementById("btn-menuPerfil").classList.contains("ativo")){
            document.getElementById("btn-menuPerfil").classList.remove("ativo")
            document.getElementById("lateralPerfil").classList.remove("ativo")
        }
        
        var data = new FormData();
       data.append("pasta", target);
       data.append("acao", "blocohtml");
       this.ajax(data, this.html)
       
       
    }
    
    formata(inputString) {
  const words = inputString.split('-'); // Divide a string nos traços
  const formattedWords = words.map((word, index) => {
    return word.charAt(0).toUpperCase() + word.slice(1); 
  });

  return formattedWords.join('');
}
    
    html(r){

        this.conteudo.innerHTML = r
        
        
       
        var funcaoJS = `perfil${this.formata(this.target)}`;

        chama(funcaoJS, false);
        
        uploader();
        var data = new FormData();
        data.append("acao", "baseInfo")
        this.ajax(data, this.perfil)

    }
    
    ajax(data,callback = false){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            try{
                var obj = JSON.parse(this.responseText)
            }catch{
                var obj = false;
            }
            
            if(obj){
                if(obj.retorno.erro){
                    console.log(obj.retorno.mensagem)
                }else{
                    if(callback){
                        callback(obj.retorno)
                    }else{
                        console.log(obj.retorno)
                    }
                    
                }
            }else{
                 console.log(this.responseText);
            }
            
           
  }
  xhttp.open("POST", `${dominioAdress}/conteudo/modulos/perfil/admin/ajax.php`);
  xhttp.send(data);
    }
    
    
    imagem(c, v){

        var id = c.split("_")[1];
        var input = document.getElementById(id)

        if(input){
           var v = JSON.parse(v)

            var i = 0;
            while(i < v.length){
              var arquivo = new Arquivo(false, input, false, v[i])
                i++;
            }
           

        }
    }
    
    
    perfil(r){
       for(var chave in r){
            const elemento = document.querySelector(`[name="${chave}"]`);
            if(elemento){
                elemento.value = r[chave];
            }else{
                if(chave.startsWith("imagem_")){
                    this.imagem(chave, JSON.stringify([r[chave]]))
                }
            }

       }
        
    }
    
    
    salvar(){
        var entradas = pegaformulario();

        var data = new FormData();
        data.append("acao", "cadastra")
        data.append("valores", JSON.stringify(entradas));
        this.ajax(data, this.salvo.bind(this))
        
        
    }
    
    salvo(r){
       
        Swal.fire({

  icon: 'success',
  title: 'Sucesso',
  text: 'Dados Salvos com Sucesso',
  showConfirmButton: false,
  timer: 1500
})
    }
    
}

class BtnMobilePerfil{
    constructor(){
        this.btnMobile = this.btnMobile.bind(this)
        this.btn = document.getElementById("btn-menuPerfil")
        this.menu = document.getElementById("lateralPerfil")
        this.btn.addEventListener("click", this.btnMobile)
    }
    
    btnMobile(){
        this.btn.classList.toggle("ativo");
        this.menu.classList.toggle("ativo");
    }
}

function moduloPerfil(){
    new ModuloPerfil("perfil");
    new BtnMobilePerfil();
}

function moduloPerfilExcecao(){
    
    if(caminho.caminho[2]){
         new ModuloPerfil(caminho.caminho[2]);
         new BtnMobilePerfil();
    }else{
        console.log("modulo nao existe")
    }
}
