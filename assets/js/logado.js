
export function tabulacao(){
    var tabulacao = document.getElementsByClassName("tabulacao")
    var i = 0;
    while(i < tabulacao.length){
        console.log("fui na tqabula")
        new Tabulacao(tabulacao[i]);
        i++;
    }
    
}

export class CardSalvar{
    constructor(){
        this.publicar = this.publicar.bind(this)
        this.editar = this.editar.bind(this)
        this.hideall = this.hideall.bind(this)
        this.ok = this.ok.bind(this)
        this.pegaMetas = this.pegaMetas.bind();
        
        if(document.getElementById("cardSalvar")){
            this.card = document.getElementById("cardSalvar")
            this.modulo = this.card.dataset.modulo
            
            this.card.removeAttribute("data-card");
            
            this.vizibilidade = this.card.getElementsByClassName("vizibilidade")[0]
            this.status = this.card.getElementsByClassName("status")[0]
            this.data = this.card.getElementsByClassName("data")[0]
            this.horario = this.card.getElementsByClassName("horario")[0]
            
            
            this.btn = this.card.getElementsByClassName("publicar")[0]
            this.acao = parseInt(this.btn.dataset.acao)
            this.btn.removeAttribute("data-acao")
            evento(this.btn, "click", this.publicar)
            
            
            if(this.acao == 2){
                new Recupera(caminho.hash(), `${this.modulo}_item`);
               
            }
            
            
            this.btnEditar = this.card.getElementsByClassName("editar")
            var i = 0;
            while(i < this.btnEditar.length){
                evento(this.btnEditar[i], "click", this.editar)
                
                i++;
            }
            
            var oks = this.card.getElementsByClassName("ok")
            var i = 0;
            while(i < oks.length){
                oks[i].dataset.t = i
                evento(oks[i], "click", this.ok)
                i++;
            }
            
        }
    }
    
    pegaMetas(){
        var metas = {};
        
        
        if(document.getElementById("pluginCategorizador") && document.getElementById("pluginCategorizador").value){
            metas.categoria = document.getElementById("pluginCategorizador").value
        }
        
        
        if(Object.keys(metas).length === 0){
            return false;
        }else{
            return metas;
        }
        
        
    }
    
    publicar(){
        var imagens = getUploaders();
        
    
   
        var infos = pegaformulario()
        
        var metadados = this.pegaMetas();
        
        
        
 
        if(infos){
            if(imagens){
                infos.imagens = imagens
            }
            
            if(metadados){
                for (let key in metadados) {
                    infos[key] = metadados[key]
                }
            }
            

            if(this.acao == 1){
                ajax(infos, `${this.modulo}_cadastra`, cadastrosucesso);
            }else{
                infos.id = caminho.hash();
                ajax(infos, `${this.modulo}_edita`, cadastrosucesso);
            }
            
        }
        
    }
    
    ok(){
        this.hideall();
        var i = event.target.dataset.t
        
        var setup = this.card.getElementsByClassName("setup")
        
        switch(parseInt(i)){
            case 0:
                setup[i].innerText = this.status.value
                break;
            case 1:
                setup[i].innerText = this.vizibilidade.value
                break;
            case 2:
                if(verificarDataHora(`${this.data.value} ${this.horario.value}`)){
                     setup[i].innerText = `IMEDIATAMENTE`
                }else{
                     setup[i].innerText = `${formatarData(this.data.value)} as ${this.horario.value}`
                }
               
                break;
        }
        
    }
    
    hideall(){
        var contents = this.card.getElementsByClassName("content")
        var i = 0;
        while(i < contents.length){
            if(contents[i]){
                contents[i].classList.add("d-none")
            }
            
            i++;
        }
    }
    
    editar(){
      
        this.hideall();
        
        
        var pai = event.target.closest(".list-group-item")
        var item = pai.getElementsByClassName("content")[0]
        item.classList.toggle("d-none")
        
        
        if(!this.card.dataset.data){
            const dataAtual = new Date();
            const horaAtual = dataAtual.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const dataAtualFormatada = dataAtual.toLocaleDateString()
            this.horario.value = horaAtual;
            this.data.value = dataAtualFormatada;
        }
    }
}

export class Recupera{
    constructor(id, rota, callBack = false){
        console.log("recuperando")
        this.monta = this.monta.bind(this)
        this.seo = this.seo.bind(this)
        this.imagem = this.imagem.bind(this)
        this.callBack = callBack;
        
        var info = {};
        info.id = id
        ajax(info, rota, this.monta);

    }
    
    monta(r){

        var obj = JSON.parse(r)
              
        if(obj.acao && obj.sucesso){
            var lista = obj.sucesso

            for(var chave in lista){
                var valor = lista[chave]
                
                
                var input = document.querySelector(`[name='${chave}']`);
                if(input){
        
                    input.value = typeof valor === 'object' ? JSON.stringify(valor) : valor
                    
                    
                    if(chave == "categoria"){
                        if ($('#pluginCategorizador').hasClass("select2-hidden-accessible")) {
                            console.log("ja ta na memoeria")
                        }
                    }
                    
                }else{
                    var input =  document.querySelector(`[data-name='${chave}']`);
                    
                    if(input){
                        var quill = quills[input.dataset.num]
                        const delta = quill.clipboard.convert(valor);
                        quill.setContents(delta);
                        /*
                        var conteudo = Array.isArray(valor) ? valor : JSON.parse(valor)
                        quill.setContents(conteudo);
                        */
                        
                        
              
                    }else{
                        if(chave == "seo"){
                            this.seo(valor);
                        }
                        
                        if(chave.startsWith("imagem_")){
                            this.imagem(chave, valor);
                        }
                    }

                }
  
            }
        }
        else{
            var tamanho = caminho.caminho.length
            var url = caminho.caminho;
             url.pop();
            if(tamanho == 4){
                 url.pop();
            }

            var novaUrl = url.join("/");
            goUrl(novaUrl);
             Swal.fire({
                 icon: 'error',
                 title: 'Item Não Encontrado',
                 text: 'O item que você está procurando foi alterado ou deletado',
                 showConfirmButton: false,
                 timer: 3000
             })
        }
        
        if(this.callBack){
            this.callBack();
        }
        
    }
    
    imagem(c, v){
        var id = c.split("_")[1];
        var input = document.getElementById(id)
        if(input){
            var i = 0;
            while(i < v.length){
              var arquivo = new Arquivo(false, input, false, v[i])
                i++;
            }
           

        }
    }
    
    seo(v){
        if(document.getElementById("seoPlugin")){
            var seo = document.getElementById("seoPlugin");
            var inputs = seo.getElementsByClassName("form-control")
            
            inputs[0].value = v.seo;
            inputs[1].value = v.titulo;
            inputs[2].value = v.descricao;
            
        }

    }
}

export class Condicional {
    constructor(elemento) {
        this.condicao = this.condicao.bind(this)
        this.teste = this.teste.bind(this)
        
        
        this.elemento = elemento;
        this.atributos = JSON.parse(elemento.innerText);
        elemento.innerHTML = "";
        elemento.classList.remove("d-none");
        this.condicao();
   
 
    }
    
    condicao(){
        var c = this.atributos.condicional

        
        var regras = c.regras
        var i = 0;
        while(i < regras.length){
         
         const elements = document.querySelectorAll(`[name="${regras[i].target}"]`);
         var y = 0;
         
         while(y < elements.length){
             
             evento(elements[y], "input", this.teste)
             y++;
         }
         
         
         i++;   
        }
    }


    teste(){
        var c = this.atributos.condicional;
        
        if(c.mostrar){
            // Condicional para mostrar
            var regras = c.regras
             var i = 0;
             var show = true;
             while(i < regras.length){
                 var regra = c.regras[i]
                 
                 var input = document.querySelectorAll(`[name="${regras[i].target}"]`);
                 if(input.length > 0){
                     switch(parseInt(regra.rule)){
                         case 1:
                             //  é
                             if(input[0].value != regra.aux){
                                 show = false;
                             }
                             break;
                         case 2:
                             // não é
                              if(input[0].value == regra.aux){
                                 show = false;
                             }
                             
                             break;
                         case 3:
                             // vazio
                             if(input[0].value){
                                 show = false;
                             }
                             break;
                         case 4:
                             // não está vazio
                             if(!input[0].value){
                                 show = false;
                             }
                             break;
                         case 5:
                            // maior q
                             if(input[0].value < regra.aux){
                                 show = false;
                             }
                             break;
                         case 6:
                             // menor q
                             if(input[0].value > regra.aux){
                                 show = false;
                             }
                             break;
                         case 7:
                             // contem
                             if(!input[0].value.includes(regra.aux)){
                                 show = false;
                             }
                             break;
                         case 8:
                             // começa com
                            if(!input[0].value.startsWith(regra.aux)){
                                show = false;
                            }
                             break;
                         case 9:
                             // termina com
                             if(!input[0].value.endsWith(regra.aux)){
                                show = false;
                            }
                             break;
                     }
                 }
         
                 
                 i++;
             }
             
             if(show){
          
                 var input = new Input(this.atributos)
                 this.elemento.innerHTML = input.html()
             }else{
                 this.elemento.innerHTML = ""
             }
        }else{
            // Condicional para esconder
            console.log("esoncndendo")
        }
        
       
    }
   
}

export class Tabulacao{
    constructor(pai){
        this.tabula = this.tabula.bind(this)
        console.log(this.tabula)
        
        this.pai = pai
        this.botoes = this.pai.getElementsByClassName("tab")
        
        
        var i = 0;
        while(i < this.botoes.length){
            evento(this.botoes[i], "click", this.tabula)
            i++;
        }
    }
    
    tabula(){
        var i = 0;
        while(i < this.botoes.length){
            this.botoes[i].classList.remove("active")
            i++;
        }
        
        event.target.classList.add("active")
        
        var target = event.target.dataset.target
        
        
        
        if(document.getElementById(target)){
            var conteudo = document.getElementById(target).closest(".navcontent")
            
            var filhos = document.getElementsByClassName("nav-content")
            var i = 0;
            while(i < filhos.length){
                
                filhos[i].id == target ? filhos[i].classList.add("active") : filhos[i].classList.remove("active")
                i++;
            }
            
        }
    }
}

export function autosave(){
    if(caminho.caminho[1] == "perfil"){
        fotosperfil();
    }
}
