class ItemTabela{
    constructor(array, chaves, action){

        this.botao = this.botao.bind(this);
        this.getacoes = this.getacoes.bind(this)
        this.html = this.html.bind(this)
        
        this.array = array;
        this.chaves = chaves
        this.action = action
        
    
        this.hash = array.hasher ? array.hasher : false;

  
        
       

    }
    
    botao(item, i){
        
         
         var li = document.createElement("LI")
         
         
        var btn = new El("BUTTON")
        btn.c("dropdown-item");
        btn.inner(item.texto)
    
        
        var btn = btn.html();
        evento(btn, "click", item.funcao, {hash:this.hash, data: item.data[i]})
        
        
        li.appendChild(btn)
        return li;
        
    }
    
    getacoes(){
        var lista = [];
        
        
        var i = 0;
        while(i < this.action.length){

            lista.push(this.botao(this.action[i], i))
            i++;
        }
        
        return lista;
    }
    
    formatarData(dataStr) {
  // Crie um objeto Date a partir da string de data
  const data = new Date(dataStr);

  // Array para os nomes dos meses
  const meses = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
  ];

  // Extraia o dia, mês e ano da data
  const dia = data.getDate();
  const mes = meses[data.getMonth()];
  const ano = data.getFullYear();

  // Construa a string formatada
  const dataFormatada = `${dia} de ${mes} de ${ano}`;

  return dataFormatada;
}

    html(){
        
        var chaves = this.chaves
        var array = this.array
        var action = this.action
        
        this.tr = document.createElement("TR")
           
  
        var y = 0;
        var colunas = array
 
        while(y < chaves.length){
                 var chave = chaves[y].trim();
                 var td = document.createElement("TD")
                 td.classList.add("text-contrast", "fs-14", "fw-500")
                 switch(chave){
                     case "imagem":
                          if(colunas[chave]){
                            var img   = Array.isArray(colunas[chave]) ? colunas[chave][0] : colunas[chave];
                            var url = img.startsWith("http") ? img  : `${dominioAdress}/conteudo/uploads/${img}`;
                            url = url.replace("original.webp", "mini.webp")
     
            
                        }else{
                            td.innerHTML = `<div style="max-width:60px; height: 60px" class="rounded bg-danger"></div>`
                        }
                        
                        td.classList.add("text-center")
                        td.style.width = "65px"
                        td.innerHTML = `<div style="width: 60px; height: 60px; background-image: url(${url}); background-size: cover; background-position: center center" class="rounded bg-danger img-thumbnail m-auto"></div>`
                         break;
                      case "data":
                          td.innerText = this.formatarData(colunas[chave]) 
                          
                          break;
                      default:
                       td.innerText = colunas[chave]  
                          break;
                 }
                 
                 
                 this.tr.appendChild(td)
                 y++;
             }
             
        if(action){

                    var td = document.createElement("TD")
                    var drop = document.createElement("DIV")
                    drop.classList.add("dropdown")
                    
                    var btn = document.createElement("BUTTON")
                    btn.classList.add("btn","btn-secondary","dropdown-toggle","btn-sm","w-100")
                    btn.setAttribute("type", "button")
                    btn.setAttribute("data-bs-toggle", "dropdown")
                    btn.setAttribute("aria-expanded","false")
                    btn.innerText = "Ação"
                    
                    var ul = document.createElement("UL")
                    ul.classList.add("dropdown-menu")
                    
                    var lista = this.getacoes()
                    
                    var i = 0;
                    while(i < lista.length){
                        ul.appendChild(lista[i])
                        i++;
                    }
                    
                    drop.appendChild(btn)
                    drop.appendChild(ul)
                    td.appendChild(drop)
                    
                     td.style.width = "80px";
                      this.tr.appendChild(td)
                      
                       

             }


        return this.tr;
    }
    
}

class Tabela{
    constructor(pai){
        this.html = this.html.bind(this)
        this.referencia = this.referencia.bind(this)
        this.script = this.script.bind(this)
        
        
        this.pai = pai;
        this.head = false;
        this.body = false;
        this.action = false;
        
    }
    
    header(itens = ""){
        var itens = itens.split(",")
        
        var tr = document.createElement("TR")
        tr.classList.add("text-contrast")
        
        var i = 0;
        while(i < itens.length){
            var th = document.createElement("TH")
            th.innerText = itens[i]
            tr.appendChild(th)
            i++;
        }
        
        if(this.action){
            var th = document.createElement("TH")
            th.innerText = ""
            tr.appendChild(th)
        }
        
        this.head = document.createElement("THEAD")
        this.head.appendChild(tr)
    }
    
 
    acao(act){
          var acoes = [];
                
                if(act){
                    var a = act
                    
                    var i = 0;
                    
                    while(i < a.length){
                        switch(a[i].nome){
                            case 'editar':
                                acoes.push({"texto":'<i class="bi bi-pencil-square"></i> Editar', "funcao":this.editar.bind(this), "data": a});
                                break;
                            case 'deletar':
                                acoes.push({"texto":'<i class="bi bi-trash3"></i> Deletar', "funcao": this.deletar.bind(this), "data": a});
                                break;
                            case 'vizualizar':
                                 acoes.push({"texto":'<i class="bi bi-trash3"></i> Vizualizar', "funcao": this.vizualizar.bind(this), "data": a});
                                break;
                      
                        }
              
                        i++;
                    }
                }
        
        
        this.action = acoes
    }
    
    corpo(array = [], chaves = ""){
 
         this.body = document.createElement("TBODY")
         
         var chaves = chaves.split(",")
         var i = 0;
         while(i < array.length){

             var item = new ItemTabela(array[i], chaves, this.action);
             this.body.appendChild(item.html())
             i++;
         }
         
        
        
        
        
    }
    
    html(){

        var t = document.createElement("TABLE")
        t.classList.add("display")
        
        this.head ? t.appendChild(this.head) : "";
        this.body ? t.appendChild(this.body) : "";
        
        
        if(this.pai){
            this.pai.innerHTML = "";
            this.pai.appendChild(t)
            
             loadResources("https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js").then((r) => {
                 this.script(t);
             })  
             
             loadResources("https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css").then((r) => {
                 
             })  
        }
        
    }
    
    script(t){
        
        try{
             this.table = new DataTable(t, {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json',
                },
            });
            
            this.pai.classList.remove("d-none")
            document.getElementById("preLoad").remove();
        }catch(e){
            console.log("entrou no segundo fluxo")
            setTimeout(()=>{
                   this.table = new DataTable(t, {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json',
                },
            });
            
            this.pai.classList.remove("d-none")
            document.getElementById("preLoad").remove();
            }, 500)
        }
    }
    
    referencia(){
        return this.table;
    }
    
    vizualizar(){
        console.log("vizualizando")
    }
    
    editar(r){
         if(r.data.tipo == "url"){
        goUrl(`${r.data.destino}${r.hash}`)
    }


    }
    
    deletar(r){
          var btn = event.currentTarget
    Swal.fire({
        icon: 'question',
  title: 'Você tem certeza?',
  showCancelButton: true,
  confirmButtonText: 'Apagar',
  cancelButtonText: 'Cancelar',
}).then((result) => {

  if (result.isConfirmed) {
    
         var info = {};
         info.hash = r.hash
         ajax(info, r.data.chave, false)
          const row = btn.closest("tr");
   
          this.table.row(row).remove().draw(false);
      
    Swal.fire('Deletado!', '', 'success')
  } 
})
    }
    
    
    
    
}

class TabelaLoad{
    constructor(filtro, id){
          this.lista = this.lista.bind(this)
          this.filtro = filtro
          this.id = id;
          
           ajax(false, `${filtro}_lista`, this.lista);
    }
    
    
  
    
    lista(r){
        try{
              var obj = JSON.parse(r)
  
        if(obj.sucesso){
              
                
                var mapa = obj.sucesso.mapping;
                var chaves = [];
                var headers = [];
                for (var chave in mapa) {
                    chaves.push(chave)
                    headers.push(mapa[chave])
                };
                
                var chaves = chaves.join(",");
                var headers = headers.join(",")

               var tabela = new Tabela(document.getElementById(this.id));
       
               tabela.acao(obj.sucesso.acoes)
               tabela.header(headers)
 
               tabela.corpo(obj.sucesso.lista, chaves)
                          
               tabela.html();

        }
        }catch(e){
            console.log(e)
            console.log(r)
        }
       
    }
}