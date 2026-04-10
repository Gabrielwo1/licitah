class UserRules{
      constructor(){
          this.div = document.getElementById("controleUsuario")
 
        var data = new FormData();
        data.append("acao","itens")
        this.ajax.bind(this)(data, this.listar.bind(this))

    }
    
  
    listar(r){
        this.lista = r.lista
        
        console.log(this.div)
        
        this.number = this.div.closest(".card-body").querySelector('input[type="number"]')
        this.number.closest(".row").classList.add("d-none")
        var numero = this.number.value != "" ? this.number.value : "2";
        

        
        var div = document.createElement("DIV")
        div.classList.add("mb-3")
        var label = document.createElement("LABEL")
        label.classList.add("fs-14","fw-700")
        label.innerText = "Função de Novos Usuários"
        var span = document.createElement("SPAN")
        span.classList.add("d-block","fs-12")
        span.innerText = `Defina qual a função base de novos usuários (Somente funções do tipo cliente)`
        div.appendChild(label)
        div.appendChild(span)
        this.div.appendChild(div)
        
        var select = document.createElement("SELECT")
        select.classList.add("form-select")
        evento(select, "input", this.muda.bind(this))
        
        for(let c in this.lista){
            var item = this.lista[c]
            if(item.id != 1 && item.tipo == 2){
                var option = document.createElement("OPTION")
                option.value = item.id
                option.innerText = item.nome
                select.appendChild(option)
            }

        }
        
        setTimeout(()=>{
            select.value = numero
        }, 50)
        this.select = select
        this.div.appendChild(select)
      
    }
    
    muda(){
        this.number.value = this.select.value
    }
    
    ajax(data, cb = false){
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{
                let obj = JSON.parse(request.responseText)
                if(cb){
                    cb(obj)
                }else{
                    console.log(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/conteudo/modulos/usuarios/admins/funcoes.php`)
        request.send(data)
    }
}

function configuracoesNownGeral(grupo, r){
    switch(grupo){
        case 'geral':
             new UserRules();
            break;
    }

 
}