class UltraSearch {
    constructor(container, setup = {}) {
        this.container = container;
        

        this.config = setup;

        if (!this.config.foco) {
            return;
        }
        
        switch(this.config.foco){
            case 'tabelas':
                this.url = `${dominio}/admin/tabela.php`;
                break;
            case 'apis':
                this.url = `${dominio}/admin/api.php`;
                break;
        }
        
        this.iniciado = false;
        this.render.bind(this)();
        
    }

    pegaHash(attribute) {
        const hash = window.location.hash.slice(1); // Remove o "#" do início
        const params = new URLSearchParams(hash); // Converte o hash em parâmetros de busca

        // Obtém o valor do parâmetro especificado
        let valor = params.get(attribute) || null;

        if (valor) {
            // Substitui os "+" por espaços e os "-" por espaço
            valor = valor
                .replace(/\+/g, " ") // Substitui "+" por espaço
                .replace(/-/g, " "); // Substitui "-" por espaço
        }

        return valor; // Retorna o valor tratado ou null se não encontrado
    }

    pesquisa() {
        if (this.input.value && this.url) {
            let request = new Request(this.url);
            request.addData({
                acao: "pesquisa",
                modulo: this.config.modulo,
                identificador: this.config.identificador,
                termo: this.input.value
            })
            request.send().then((r) => {
                this.ul.innerHTML = ``;
                
                this.tabela = r
                
                var i = 0;
                var fragmento = document.createDocumentFragment();
                while (i < r.r.length) {
                    var li = this.sugestao(r.r[i], 1);
                   
                    fragmento.appendChild(li);
                    i++;
                }
                this.ul.appendChild(fragmento);
            })

            this.drop.classList.remove("d-none")
        }
    }

    sugestao(item, live = false) {
        
        let conteudo = "";
        if(live){
           var config = this.tabela.m;

           var contador = 0;
           var row = document.createElement("DIV")
           row.classList.add("row")
            for(let c in config){
                var coluna = config[c]
                if(coluna.colpesquisavel){
                    console.log(coluna)
                    var col = document.createElement("DIV")
                    col.classList.add("col-12")
                    col.innerText = item[c];
                    row.appendChild(col)
                    contador++;
                }
            } 
            
            if(contador > 0){
                conteudo = row.outerHTML;
            }else{
                conteudo = JSON.stringify(item)
            }
        }else{
            conteudo = item.termo
        }
        
        
    
        
        var hash = item.H ?? false;
        var url = item.U ?? false;
        var icone = live ? 'bi-search' : 'bi-clock-history '
        var li = document.createElement("LI")
        li.classList.add("list-group-item", "list-group-item-action", "d-flex", "justify-content-between", "align-items-center", "position-relative", "itemPesquisa")
        li.innerHTML = `
        <div>
            <div class="d-flex justify-content-start gap-2 align-items-center">
                <div>
                    <i class="bi ${icone} fs-12"></i>
                </div>
                <div class="fs-14">
                    ${conteudo}
                </div>
            </div>
        </div>
        `
        if (!live) {
            var span = document.createElement("BUTTON")
            span.classList.add("p-0", "btn", "text-decoration-underline", "fs-12")
            span.innerText = "Remover"
            li.appendChild(span)

        }
        
         evento(li, "click", ()=>{
             console.log("clicado")
                        this.go(item, live);
                    })
       
    
        return li;

    }
    
    go(item, live){
        if(dataModule("c3be1ab410cda1a15c51c2ba44827854")){
            let request = new Request(`${dominio}/conteudo/modulos/historico-de-pesquisa/admins/api.php`);
            request.addData({
                acao: "pesquisa",
                modulo: this.config.modulo,
                identificador: this.config.identificador,
                termo: this.input.value,
                tipo: this.config.foco
            })
            request.send().then((r)=>{
                console.log(r)
            }, (r)=>{
                console.log(r)
            })
        }
        
        if(live){
            if(this.config.foco == "tabelas"){
                if(this.tabela.a.edit){
                    goUrl(`${this.tabela.a.edicao}/${item.H}`);
                }

            }
        }

    }
    
    emFoco(){
        if(dataModule("c3be1ab410cda1a15c51c2ba44827854")){
            let request = new Request(`${dominio}/conteudo/modulos/historico-de-pesquisa/admins/api.php`);
            request.addData({
                acao: "sugestao",
                modulo: this.config.modulo,
                identificador: this.config.identificador,
                termo: this.input.value ? this.input.value : false,
                tipo: this.config.foco
            })
            request.send().then((r)=>{
                var lista = r.lista;
                if(lista.length > 0){
                       var i = 0;
                var fragmento = document.createDocumentFragment();
                while (i < lista.length) {
                    var li = this.sugestao(lista[i], false);
                   
                    fragmento.appendChild(li);
                    i++;
                }
                this.ul.appendChild(fragmento);
                this.drop.classList.remove("d-none") 
                }
            }, (r)=>{
                console.log(r)
            })
        }
    }

    ouve() {
        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.lang = 'pt-BR';
            recognition.interimResults = true; // Resultados intermediários (true para mostrar parcial)
            recognition.maxAlternatives = 1; // Número de alternativas para o texto reconhecido

            recognition.onstart = () => {
                console.log('Reconhecimento iniciado. Fale algo...');
            };

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;

                this.input.value = transcript;
            };

            recognition.onerror = (event) => {
                console.error('Erro no reconhecimento:', event.error);
            };

            recognition.onend = () => {
                this.pesquisa.bind(this)();
                
            };

            recognition.start();
        }

    }

    pagina() {
        setTimeout(() => {
            this.ul.innerHTML = "";
            this.drop.classList.add("d-none");
        }, 200);

        let valor = this.input.value.trim();
        
        if (valor) {
            valor = valor
                .replace(/[^a-zA-Z0-9À-ÿ\s\+]/g, "-") // Substitui caracteres especiais (exceto acentos, espaços e '+') por "-"
                .replace(/\s+/g, "+") // Substitui espaços por "+"
                .replace(/-+/g, "-") // Substitui múltiplos "-" consecutivos por um único
                .replace(/^-|-$/g, ""); // Remove "-" no início ou fim

            if(this.config.cb){
                this.config.cb(this.input.value.trim());
            }else{
                window.location.hash = `#pesquisa=${encodeURIComponent(valor)}`;
            }
            
        } else {
            if(this.config.cb){
                this.config.cb(false);
            }else{
                 window.location.hash = ``;
            }
           
        }
    }

    render() {
        console.log("ta indo")
        var div = document.createElement("DIV")
        div.innerHTML = `
        

        <div style="max-width: 540px" class="m-auto d-flex justify-content-between gap-2">
   <div class="form-control rounded-pill d-flex justify-content-between p-0">
      <div class="d-flex align-items-center he-40 flex-fill position-relative">
         <input class="h-100 d-flex align-items-center border-0 w-100 ps-5 pe-3 procurar" style="border-radius: 50px 00rem 0rem 50px" type="search" placeholder="Pesquisar"> 
         <span class="position-absolute top-50  translate-middle-y" style="left: 15px"><i class="bi bi-search"></i></span>
         <div class="position-absolute w-100 py-1 top-100 bottom-0 d-none drop" style="z-index:5">
            <div class="card card-nown overflow-hidden">
               <div class="card-body p-0">
                  <div class="list-group list-group-flush">

                  </div>
               </div>
            </div>
         </div>
      </div>
      <button class="btn btn-dark he-40 wi-75 btbProcurar" style="border-radius: 0px 50rem 50rem 0px"><i class="bi bi-search"></i></button> 
   </div>
   <button class="btn he-40 wi-40 rounded-circle btn-dark d-flex justify-content-center align-items-center microfone"> <i class="bi bi-mic-fill"></i> </button> 
</div>
        `
        this.input = div.getElementsByClassName("procurar")[0]
        bounce(this.input, 400, this.pesquisa.bind(this))
        this.ul = div.getElementsByClassName("list-group")[0]
        this.microfone = div.getElementsByClassName("microfone")[0]
        this.drop = div.getElementsByClassName("drop")[0]
        this.btn = div.getElementsByClassName("btbProcurar")[0]

        var pesquisa = this.pegaHash("pesquisa");
        if (pesquisa) {
            this.input.value = pesquisa;
        }

        evento(this.btn, "click", () => {
            this.pagina.bind(this)();
        })

        evento(this.input, "focus", () => {
            if(!this.iniciado){
                this.emFoco.bind(this)();
                this.iniciado = true;
            }else{
                 this.drop.classList.remove("d-none");
            }
        })

        evento(this.input, "blur", () => {
            setTimeout(()=>{
               this.drop.classList.add("d-none") 
            }, 300)
        })

        evento(this.input, "keydown", (e) => {
            if (e.key === "Enter") {
                this.pagina.bind(this)();
            }
        });


        evento(this.microfone, "click", this.ouve.bind(this))


        this.container.appendChild(div)
    }
}