class ControleUserFuncao{
    constructor(){

        this.hash = pegaHash();      
        this.idAcordion = geraId();
        
        this.init.bind(this)();
        
    }
    
    init(){
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/funcoes.php`);
        request.addData({"acao":"listar", "id": this.hash})
        request.send().then((r)=>{
         
            this.respostas = r.respostas;
            
            
            this.admin = r.isadmin;
            
            new ContoleUserPage(this.respostas.paginas.paginas, this.admin);
            this.lista = r.lista;
            this.render.bind(this)();
        })
    }
     
    crud(modulo, chave){

        var div = document.createElement("DIV")
        div.classList.add("row", "crudFuncao")
        div.dataset.modulo = modulo.pasta;
        div.dataset.form = chave.hash
        
        var c = "";
        var u = "";
        var d = "";
        var t = "";
        if(this.admin){
             c = "disabled checked";
             u = "disabled checked";
             d = "disabled checked";
             t = "disabled checked";
        }else{
            if(this.respostas.crud?.[modulo.pasta]?.[chave.hash] || false){
                var r = this.respostas.crud[modulo.pasta][chave.hash]

                if(parseInt(r.c)){
                    c = "checked";
                }
                
                if(parseInt(r.u)){
                    u = "checked";
                }
                
                if(parseInt(r.d)){
                    d = "checked";
                }
                
                if(parseInt(r.t)){
                    t = "checked";
                }
            }
        }
        
        
        div.innerHTML = `
        <div class="col-4">${chave.nome}</div>
        <div class="col-2">
            <div>
                <input class="form-check-input" type="checkbox" ${c}>
            </div>
        </div>
        <div class="col-2">
           <input class="form-check-input" type="checkbox" ${u}>
        </div>
         <div class="col-2">
            <input class="form-check-input" type="checkbox" ${d}>
        </div>
        <div class="col-2">
            <input class="form-check-input" type="checkbox" ${t}>
        </div>
         <div class="col-3">
            <label class="fs-12">Limite</label>
                <select class="form-select">
                    <option value="0">Desativado</option>
                    <option value="1">Ativado</option>
                </select>
        </div>
         <div class="col-3">
              <label class="fs-12">Quantidade</label>
                <input class="form-control">
        </div>
         <div class="col-3">
                <label class="fs-12">Renovação</label>
                <select class="form-select">
                    <option value="0">Desativado</option>
                    <option value="1">Ciclo Assinatura</option>
                    <option value="2">Personalizado</option>
                </select>
        </div>
         <div class="col-3">
                <label class="fs-12">Limite</label>
                <select class="form-select">
                    <option value="0">Desativado</option>
                    <option value="1">Ativado</option>
                </select>
        </div>
        `
        
        var li = document.createElement("LI")
        li.appendChild(div)
        li.classList.add("list-group-item", "list-group-item-action")
        return li;
    }
    
    pagina(modulo, chave, tipo){
        

        var div = document.createElement("DIV")
        div.classList.add("row", "paginaModuloFuncao")
        div.dataset.modulo = modulo.pasta;
        div.dataset.pagina = chave
        

        
        if(modulo.pasta == chave){
            var url = `a/${modulo.pasta}`
        }else{
            var url = `a/${modulo.pasta}/${chave}`
        }
        
        
        var marcado = "";

        var selecionado = parseInt(this.respostas["paginas"]["admin"]?.[url]?.["v"] || 0);
        if(selecionado){
            marcado = "checked";
        }
        
        if(this.admin){
            marcado = "disabled checked";
        }
        
     
        
        

        div.innerHTML = `
        <div class="col-4">${chave}</div>
        <div class="col-4">
            ${tipo}
        </div>
        <div class="col-4">
            <input class="form-check-input" type="checkbox" ${marcado}>
        </div>

        `
        
        var li = document.createElement("LI")
        li.appendChild(div)
        li.classList.add("list-group-item", "list-group-item-action")
        return li;
    }
    
    paginaPublica(modulo, chave, tipo){

        var div = document.createElement("DIV")
        div.classList.add("row", "paginaPublicaModuloFuncao")
        div.dataset.modulo = modulo.pasta;
        div.dataset.pagina = chave
        

        switch(chave){
            case 'home':
                var url = `${modulo.pasta}`
                break;
            case 'item':
                var url = `${modulo.pasta}/*`
                break;
            default:
                var url = `${modulo.pasta}/${chave}`
                break;
        }
        

        var selecionado = false;
        if(!parseInt(this.respostas["paginas"]["publicas"]?.[url]?.["v"] || 0) == 1){
           selecionado = true;
        }
 
         var marcado = "";


      
        if(selecionado){
            marcado = "checked";
        }
        
        if(this.admin){
            marcado = "disabled";
        }
        
       
        

        div.innerHTML = `
        <div class="col-4 d-flex align-items-center">${chave}</div>
        <div class="col-2 d-flex align-items-center">
           <input class="form-check-input" type="checkbox" ${marcado}>
        </div>
        <div class="col-3">
            <select class="form-select form-select-sm acao">
                <option value="1">Página Não Existe</option>
                <option value="2">Bloquear</option>
                <option value="3">Redirecionar</option>
            </select>
        </div>
        <div class="col-3">
            <input class="form-control form-control-sm cb">
        </div>

        `
        
        if(this.respostas["paginas"]["publicas"]?.[url]?.cb || false){
           div.getElementsByClassName("cb")[0].value = this.respostas["paginas"]["publicas"]?.[url]?.cb
       }
       
       if(this.respostas["paginas"]["publicas"]?.[url]?.a || false){
           div.getElementsByClassName("acao")[0].value = this.respostas["paginas"]["publicas"]?.[url]?.a
       }
       
        
        var li = document.createElement("LI")
        li.appendChild(div)
        li.classList.add("list-group-item", "list-group-item-action")
        return li;
    }
    
    paginaApi(modulo, chave, tipo){

        var div = document.createElement("DIV")
        div.classList.add("row", "apisFuncao")
        div.dataset.modulo = modulo.pasta;
        div.dataset.pagina = chave.key
        


        div.innerHTML = `
        <div class="col-12 col-xl-4 d-flex align-items-center fs-14 gap-2"><strong>${chave.nome}</strong><span>(${chave.key})</span></div>
        <div class="col-12 col-xl-2 d-flex flex-column gap-2">
             <div class="form-check form-switch">
           <input class="form-check-input limite" type="checkbox" role="switch">
           </div>
            <input class="form-control limitevalor d-none" type="number" value="0">
        </div>
        <div class="col-12 col-xl-3  flex-column gap-2 d-none">
           <div class="form-check form-switch">
            <input class="form-check-input reacesso" type="checkbox" role="switch">
           </div>
           
          
            <input class="form-control reacessoNumber d-none" type="number" value="1">

            <select class="form-select form-select-sm acao reacessoTipo d-none">
                <option value="0">Sempre</option>
                <option value="1">Minuto</option>
                <option value="2">Hora</option>
                <option value="3">Dia</option>
            </select>
           
        </div>

         <div class="col-12 col-xl-3 flex-column gap-2 d-none">
         <div class="form-check form-switch">
           <input class="form-check-input renovavel" type="checkbox" role="switch">
           </div>
           
           
           <input class="form-control renovavelNumber" type="number" value="1">
           
               <select class="form-select form-select-sm acao renovavelTipo">
                <option value="0">Nunca</option>
                <option value="1">Minuto</option>
                <option value="2">Hora</option>
                <option value="3">Dia</option>
            </select>
        </div>
        `
        
        
        evento(div.getElementsByClassName("limite")[0], "input", this.apiControler.bind(this))
        evento(div.getElementsByClassName("reacesso")[0], "input", this.apiControler.bind(this))
        evento(div.getElementsByClassName("renovavel")[0], "input", this.apiControler.bind(this))
        evento(div.getElementsByClassName("reacessoTipo")[0], "input", this.apiControler.bind(this))
        evento(div.getElementsByClassName("renovavelTipo")[0], "input", this.apiControler.bind(this))
 
        if(this.respostas?.apis[modulo.pasta] || false){
            var mod = this.respostas?.apis[modulo.pasta];
     
      
            if(mod[chave.key] && mod[chave.key]?.limite || false){
                
                var api = mod[chave.key]


                if(api.limite){
                    div.getElementsByClassName("limite")[0].checked = true;
                }
                
                div.getElementsByClassName("limitevalor")[0].value = api?.quantidade || 0
                
                if(api.reacessivel){
                    div.getElementsByClassName("reacesso")[0].checked = true;
                    
                    div.getElementsByClassName("reacessoNumber")[0].value = api["reacessivel_quantidade"] ?? 0
                    div.getElementsByClassName("reacessoTipo")[0].value = api["reacessivel_tipo"] ?? 0
                }
                
                if(api.renovavel){
                    div.getElementsByClassName("renovavel")[0].checked = true;
                    div.getElementsByClassName("renovavelNumber")[0].value = api["renovavel_quantidade"] ?? 0
                    div.getElementsByClassName("renovavelTipo")[0].value = api["renovavel_tipo"] ?? 0
                }
           
           
            }
 
        }
        
        var event = new Event('input');
        div.getElementsByClassName("limite")[0].dispatchEvent(event);
        
        
        

        var li = document.createElement("LI")
        li.appendChild(div)
        li.classList.add("list-group-item", "list-group-item-action")
        return li;
    }
    
    apiControler(){
        var div = event.currentTarget.closest(".apisFuncao")
        
        let limite = div.getElementsByClassName("limite")[0];
        let reacesso = div.getElementsByClassName("reacesso")[0];
        let renovavel= div.getElementsByClassName("renovavel")[0];
        let limitevalor = div.getElementsByClassName("limitevalor")[0]
        let reacessoNumber =div.getElementsByClassName("reacessoNumber")[0]
        let reacessoTipo = div.getElementsByClassName("reacessoTipo")[0]
        let renovavelNumber = div.getElementsByClassName("renovavelNumber")[0]
        let renovavelTipo = div.getElementsByClassName("renovavelTipo")[0]
        
        

        
        reacesso.closest(".col-12").classList.remove("d-none", "d-flex")
        renovavel.closest(".col-12").classList.remove("d-none","d-flex")
        limitevalor.classList.add("d-none")
        reacessoNumber.classList.add("d-none")
        reacessoTipo.classList.add("d-none")
        renovavelNumber.classList.add("d-none")
        renovavelTipo.classList.add("d-none")
        
        
      
        reacesso.closest(".col-12").classList.add("d-flex")
        renovavel.closest(".col-12").classList.add("d-flex")
        limitevalor.classList.remove("d-none")
        
        if(!limite.checked){
            reacesso.closest(".col-12").classList.add("d-none")
            renovavel.closest(".col-12").classList.add("d-none")
            limitevalor.classList.add("d-none")
            return;
        }
        
        if(reacesso.checked){
            reacessoTipo.classList.remove("d-none")
            
            if(parseInt(reacessoTipo.value) > 0){
                reacessoNumber.classList.remove("d-none")
            }
        }
        
        if(renovavel.checked){
            renovavelTipo.classList.remove("d-none")
             if(parseInt(renovavelTipo.value) > 0){
                renovavelNumber.classList.remove("d-none")
            }
        }
        
        
        
        
        

        
        
        
        
        
        
   
    }
    
    item(info){
  
        var id = geraId();
        var drop = document.createElement("DIV")
        drop.classList.add("accordion-item");
        drop.innerHTML = `
          <h2 class="accordion-header">
      <button class="accordion-button collapsed d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#${id}" aria-expanded="false" aria-controls="${id}">
       <span><i class="${info.icone}"></i></span>
       <span>${info.name}</span>
      </button>
    </h2>
    <div id="${id}" class="accordion-collapse collapse" data-bs-parent="#${this.idAcordion}">
      <div class="accordion-body d-flex flex-column gap-4">
        <ul class="list-group listaCrud">
            <li class="list-group-item bg-success text-light">
                <div class="row">
                    <div class="col-4">Formulário</div>
                    <div class="col-2">Novo</div>
                    <div class="col-2">Atualizar</div>
                    <div class="col-2">Deletar</div>
                    <div class="col-2">Terceiros</div>
                </div>
            </li>
        </ul>
        <ul class="list-group listaPaginas">
            <li class="list-group-item bg-danger text-light">
                <div class="row">
                    <div class="col-4">Página Administrativas</div>
                    <div class="col-4">Tipo</div>
                    <div class="col-4">Acessivel</div>
                </div>
            </li>
        </ul>
        
        
         <ul class="list-group listaPublica">
            <li class="list-group-item bg-primary text-light">
                <div class="row">
                    <div class="col-4">Página Pública</div>
                    <div class="col-2">Bloquear</div>
                    <div class="col-3">Ação</div>
                    <div class="col-3">Callback</div>
                </div>
            </li>
        </ul>
           <ul class="list-group listaApis">
            <li class="list-group-item bg-info text-light">
                <div class="row">
                    <div class="col-4">Nome</div>
                    <div class="col-2">Limite</div>
                    <div class="col-3">Reacessivel</div>
                    <div class="col-3">Renovavel</div>
                </div>
            </li>
        </ul>
        
      </div>
    </div>

        `
        var conta = 4;
        if(info.formularios.length > 0){
            var i = 0;
            while(i < info.formularios.length){
                var form = info.formularios[i]

                drop.getElementsByClassName("listaCrud")[0].appendChild(this.crud.bind(this)(info, form))
                i++;
            }
            
        }else{
            drop.getElementsByClassName("listaCrud")[0].remove();
            conta--;
        }
        

        if(info.paginas.length > 0 || info.views.length > 0){
            var lista = info.paginas;
            var i = 0;
            while(i < lista.length){
                var pagina= lista[i]
                 drop.getElementsByClassName("listaPaginas")[0].appendChild(this.pagina.bind(this)(info, pagina, "Render"));
                i++;
            }
            
            
            var lista = info.views;
            var i = 0;
            while(i < lista.length){
                var pagina= lista[i]
                 drop.getElementsByClassName("listaPaginas")[0].appendChild(this.pagina.bind(this)(info, pagina, "Views"));
                i++;
            }
            

        }else{
            drop.getElementsByClassName("listaPaginas")[0].remove();
            conta--;
        }
        
        
        if(info.apis.length > 0){
            var apis = info.apis;
            
            while(i < apis.length){
                var api = apis[i]
                 drop.getElementsByClassName("listaApis")[0].appendChild(this.paginaApi.bind(this)(info, api , "Pública"));
                i++;
            }
            
        }else{
            drop.getElementsByClassName("listaApis")[0].remove();
            conta--;
        }
        
        
        
        if(info.publicas.length > 0){
            var lista = info.publicas;
            var i = 0;
            while(i < lista.length){
                var pagina= lista[i]
                 drop.getElementsByClassName("listaPublica")[0].appendChild(this.paginaPublica.bind(this)(info, pagina, "Pública"));
                i++;
            }
        }else{
             drop.getElementsByClassName("listaPublica")[0].remove();
            conta--;
        }
        
        

        
        
        if(conta == 0){
            return false;
        }else{
            return drop;
        }


    }
    
    render(){
        this.acordion = document.createElement("DIV")
        this.acordion.classList.add("accordion");
        this.acordion.id = this.idAcordion;
        
        var i = 0;
        while(i < this.lista.length){
            var drop = this.lista[i]
            let html = this.item.bind(this)(drop);
            if(html){
               this.acordion.appendChild(html) 
            }
            
            
            i++;
        }
        
        //console.log(document.getElementById("modulos").getElementsByClassName("card-body")[1])
        
        document.getElementById("modulos").getElementsByClassName("card-body")[1].appendChild(this.acordion)

  
 
 
    }
}

class ContoleUserPage{
    constructor(respostas, admin){
         this.hash = window.location.href.split("/funcao/")[1];
         this.respostas = respostas;
         this.admin = admin;
         
         this.init.bind(this)();
  
    }
    
     init(){
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/funcoes.php`);
        request.addData({"acao":"paginas"})
        request.send().then((r)=>{
            this.lista = r.lista;
            this.render.bind(this)();
        })
    }
    
     item(pagina){

        var div = document.createElement("DIV")
        div.classList.add("row", "paginasSite")
        div.dataset.pagina = pagina
        
        var marcado = "";
        if(!parseInt(this.respostas?.[pagina]?.["v"] || 1)){
            marcado = "checked"
        }
        
        if(this.admin){
            marcado = "disabled";
        }

        div.innerHTML = `
        <div class="col-4 d-flex align-items-center">${pagina}</div>
        <div class="col-2 d-flex align-items-center">
           <input class="form-check-input" type="checkbox" ${marcado}>
        </div>
        <div class="col-3">
            <select class="form-select form-select-sm acao">
                <option value="1">Página Não Existe</option>
                <option value="2">Bloquear</option>
                <option value="3">Redirecionar</option>
            </select>
        </div>
        <div class="col-3">
            <input class="form-control form-control-sm cb">
        </div>

        `
       if(this.respostas?.[pagina]?.cb || false){
           div.getElementsByClassName("cb")[0].value = this.respostas?.[pagina]?.cb
       }
       
       if(this.respostas?.[pagina]?.a || false){
           div.getElementsByClassName("acao")[0].value = this.respostas?.[pagina]?.a
       }
        
        var li = document.createElement("LI")
        li.appendChild(div)
        li.classList.add("list-group-item", "list-group-item-action")
        return li;
    }
    
    render(){
        var ul = document.createElement("UL")
        ul.classList.add("list-group", "paginasControle")
        ul.innerHTML = `
        <li class="list-group-item bg-primary text-light">
                <div class="row">
                    <div class="col-4">Página</div> 
                    <div class="col-2">Bloquear</div>
                    <div class="col-3">Ação</div>
                    <div class="col-3">Callback</div>
                </div>
            </li>
        
        `
        var i = 0;
        while(i < this.lista.length){
            var li = this.item.bind(this)(this.lista[i])
            ul.appendChild(li)
            i++;
        }
        
         document.getElementById("paginas").getElementsByClassName("card-body")[1].appendChild(ul)
        
    }
}

function fluxoFuncaoSave(){
    
    var cruds = document.getElementsByClassName("crudFuncao");
    
    var listaCruds = {}
    var i = 0;
    while(i < cruds.length){
        var div = cruds[i]
        
        if(!listaCruds[div.dataset.modulo]){
            listaCruds[div.dataset.modulo] = {};
        }
        listaCruds[div.dataset.modulo][div.dataset.form] = {
            c : div.getElementsByClassName("form-check-input")[0].checked,
            u : div.getElementsByClassName("form-check-input")[1].checked,
            d : div.getElementsByClassName("form-check-input")[2].checked,
            t : div.getElementsByClassName("form-check-input")[3].checked,
            };
        i++;
    }
    
    
    
    var listaPaginas = {"admin":{}, "publicas": {}, "paginas": {}}
    
    var listaApis = {};
    
    
    var administrativas = document.getElementsByClassName("paginaModuloFuncao")
    var publicas = document.getElementsByClassName("paginaPublicaModuloFuncao")
    var paginasReais = document.getElementsByClassName("paginasSite")
    var apis = document.getElementsByClassName("apisFuncao")
    
    
    
    

    var i = 0;
    while(i < administrativas.length){
        var pagina = administrativas[i]
        
        if(pagina.dataset.pagina == pagina.dataset.modulo){
            var url = `a/${pagina.dataset.modulo}`
        }else{
            var url = `a/${pagina.dataset.modulo}/${pagina.dataset.pagina}`
        }
        
        listaPaginas["admin"][url] = {v:pagina.getElementsByClassName("form-check-input")[0].checked}
        
        i++;
    }
    
    var i = 0;
    while(i < publicas.length){
        var pagina = publicas[i]
        switch(pagina.dataset.pagina){
            case 'home':
                var url = `${pagina.dataset.modulo}`
                break;
            case 'item':
                var url = `${pagina.dataset.modulo}/*`
                break;
            default:
                var url = `${pagina.dataset.modulo}/${pagina.dataset.pagina}`
                break;
        }
        
        listaPaginas["publicas"][url] = {
            v: !pagina.getElementsByClassName("form-check-input")[0].checked,
            a: pagina.getElementsByClassName("acao")[0].value,
            cb: pagina.getElementsByClassName("cb")[0].value,
            
        }

        i++;
    }
    
    
    
    var i = 0;
    while(i <  paginasReais.length){
        var pagina = paginasReais[i]
        listaPaginas["paginas"][pagina.dataset.pagina] = {
            v: !pagina.getElementsByClassName("form-check-input")[0].checked,
            a: pagina.getElementsByClassName("acao")[0].value,
            cb: pagina.getElementsByClassName("cb")[0].value,

        }
        i++;
    }
    
    var i = 0;
    while(i < apis.length){
        var div = apis[i]
        if(!listaApis[div.dataset.modulo]){
            listaApis[div.dataset.modulo] = {};
        }
        
      
        let limite = div.getElementsByClassName("limite")[0];
        let reacesso = div.getElementsByClassName("reacesso")[0];
        let renovavel= div.getElementsByClassName("renovavel")[0];
        let limitevalor = div.getElementsByClassName("limitevalor")[0]
        let reacessoNumber =div.getElementsByClassName("reacessoNumber")[0]
        let reacessoTipo = div.getElementsByClassName("reacessoTipo")[0]
        let renovavelNumber = div.getElementsByClassName("renovavelNumber")[0]
        let renovavelTipo = div.getElementsByClassName("renovavelTipo")[0]
        
         let obj = {limite: false};
        if(limite.checked){
            obj.limite = true;
            obj.quantidade = parseInt(limitevalor.value || 0)
            
            obj.reacesso = reacesso.checked
            if(obj.reacesso){
                obj.reacessoNumero = parseInt(reacessoNumber.value || 1)
                obj.reacessoTipo = parseInt(reacessoTipo.value)
                

                
                
                
            }
            
            
            obj.renovavel = renovavel.checked
            if(obj.renovavel){
                obj.renovavelTipo = parseInt(renovavelTipo.value)
                obj.renovavelNumbero = parseInt(renovavelNumber.value || 1)
            }

        }
        

        listaApis[div.dataset.modulo][div.dataset.pagina] = obj

        
        i++;
    }
    


     let id = window.location.href.split("/funcao/")[1];
     let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/funcoes.php`);
        request.addData({"id": id, "acao":"saveFluxo", "crud": JSON.stringify(listaCruds), "paginas": JSON.stringify(listaPaginas), "apis": JSON.stringify(listaApis)})
        request.send().then((r)=>{
            console.log(r)
        })

}