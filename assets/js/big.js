class Importador{
    constructor(identificador = false, modulo = false, master = false){
       this.identificador = identificador;
       this.modulo = modulo;
    

     
       if(!this.identificador || !this.modulo){
           return;
       }
 
       
       this.btn = document.getElementById("importar")
       evento(this.btn, "click", this.importar.bind(this))
    }
    
    filepond(){
        this.fileImportar = document.getElementById("arquivoImportacao")
            
        
            
          
            
            
            var arquivos = [
                "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js",
                "https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js",
                `${dominio}/assets/aplicativo/filepond/min.js`,
                `${dominio}/assets/aplicativo/filepond/min.css`
                ]
            
            nownFiles.add(arquivos).then(()=>{
                 
                
                   FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageResize,
            FilePondPluginImageTransform,
            FilePondPluginFilePoster,
            FilePondPluginPdfPreview

            );
            
            
            var txt = `Arraste ou solte o arquivo de importação. São aceitos CSV E Excel(xls)`

            FilePond.setOptions({
             labelIdle: `${txt}`,
             
                       allowImageResize: true,
                       imageResizeTargetWidth: 1500,
                       labelInvalidField: "Campo contém arquivos inválidos",
                       labelFileWaitingForSize: "Aguardando tamanho",
                       labelFileSizeNotAvailable: "Tamanho não disponível",
                       labelFileLoading: "Carregando",
                       labelFileLoadError: "Erro ao carregar",
                       labelFileProcessing: "Processando",
                       labelFileProcessingComplete: "Upload concluído",
                       labelFileProcessingAborted: "Upload cancelado",
                       labelFileProcessingError: "Erro durante o upload",
                       labelFileProcessingRevertError: "Erro ao reverter",
                       labelFileRemoveError: "Erro ao remover",
                       labelTapToCancel: "toque para cancelar",
                       labelTapToRetry: "toque para tentar novamente",
                       labelTapToUndo: "toque para desfazer",
                       labelButtonRemoveItem: "Remover",
                       labelButtonAbortItemLoad: "Abortar",
                       labelButtonRetryItemLoad: "Tentar novamente",
                       labelButtonAbortItemProcessing: "Cancelar",
                       labelButtonUndoItemProcessing: "Desfazer",
                       labelButtonRetryItemProcessing: "Tentar novamente",
                       labelButtonProcessItem: "Upload",
                       credits: {},
                       acceptedFileTypes: ['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                       server: {
        process: (fieldName, file, metadata, load, error, progress, abort) => {
            this.converte.bind(this)(file);
            

        },

    },
                
            });
                
                
                
              this.pond = FilePond.create(this.fileImportar , {});
              
               this.modal.show();
              
            })
    }
    
    converte(file) {
        let request = new Request(`${dominio}/admin/importador.php`)
        request.addData({
            "id":  this.identificador,
            "modulo": this.modulo,
            "acao": "newmap"
        })
        request.send().then((r)=>{
            this.estruturaImportacao.bind(this)(file, r);
        }, (r)=>{
            this.pond.removeFile(file);
            iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação',message: r.mensagem});
        })

        
 
        
    }
    
    estruturaImportacao(file, r){
        
        this.rImport = r;
        

           this.pond.removeFile(file);

    var reader = new FileReader();
    

      reader.onload = (e) =>{

        try{
            var data = e.target.result;
 
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        var table = document.createElement('table');
        table.id = "tabelaimportacao"
        var thead = document.createElement('thead');
        var theadControl  = document.createElement('thead'); 
        
        var tbody = document.createElement('tbody');

        workbook.SheetNames.forEach((sheetName)=> {
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            this.fileEstrutura = XL_row_object

            // Adiciona o cabeçalho
            var headers = Object.keys(XL_row_object[0]);
            var headerRow = document.createElement('tr');
            headers.forEach(function(header) {
                var th = document.createElement('th');
                th.appendChild(document.createTextNode(header));
                headerRow.appendChild(th);
                
                var th = document.createElement('th');
                var select = document.createElement("SELECT")
                select.classList.add("mapImportacao")
                select.dataset.target = header;
                select.classList.add("form-select")
                
                var option = document.createElement("OPTION")
                option.value = 0
                option.innerText = "Selecione uma opção"
                select.appendChild(option)
                
                var foco = false;
                for(let c in r.entradas){
                    var option = document.createElement("OPTION")
                    option.value = c
                    option.innerText = r.entradas[c].n
                    if(r.entradas[c].n == header){
                        foco = c
                    }
                    
                    option.dataset.o = r.entradas[c].o
                    option.dataset.t = r.entradas[c].t

                    select.appendChild(option)
                }
                
                if(foco){
                    select.value = foco
                }else{
                    invalido(select, false , "");
                }
                

                
                
                th.appendChild(select);
                theadControl.appendChild(th);
            });
            thead.appendChild(headerRow);
            
          

            // Adiciona as linhas de dados
            XL_row_object.forEach(function(rowObject) {
                var row = document.createElement('tr');
                headers.forEach(function(header) {
                    var cell = document.createElement('td');
                    cell.appendChild(document.createTextNode(rowObject[header]));
                    row.appendChild(cell);
                });
                tbody.appendChild(row);
            });
        });

        table.appendChild(thead);
        table.appendChild(theadControl);
        table.appendChild(tbody);
        
        setTimeout(()=>{
         document.getElementById('renderImport').appendChild(table);
        document.getElementById("modalImportacao").getElementsByClassName("modal-content")[0].classList.add("d-none")
        document.getElementById("modalImportacao").getElementsByClassName("modal-content")[1].classList.remove("d-none")
        document.getElementById("modalImportacao").classList.add("modal-full")
        }, 100)
        }catch(e){
             iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação', message: "Arquivo não suportado"});
        }
    };


    reader.onerror = function(ex) {

       iziToast.error({icon: 'bi bi-x-square', title: 'Erro na Importação',message: r.mensagem});
    };
    
      reader.readAsBinaryString(file);
    }
    
    importar(){
        this.btn.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status" style="width:15px; height: 15px">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
this.btn.setAttribute("disabled", "")
        if(!this.modal){
            var modal = document.createElement("DIV")
            modal.classList.add("modal","fade", "modal-nown")
            modal.setAttribute("id", "modalImportacao")
            modal.setAttribute("tabindex", "-1"); 
            modal.setAttribute("aria-labelledby", "exampleModalLabel")
            modal.setAttribute("aria-hidden", "true")
            modal.innerHTML = `
            

  <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header d-block">
        <div class="d-flex justify-content-between align-items-center">
        <div></div>
        <div>
        <h2 class="m-0">Importar Conteúdo</h2>
        </div>
            <div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
            </div>
        </div>
      </div>
      <div class="modal-body">
        <input type="file" id="arquivoImportacao">
      </div>
      <div class="modal-footer">
        <button class="btn-ferramenta border-primary border text-primary">Baixar Documento Modelo</button>
      </div>
    </div>
    
    <div class="modal-content d-none">
      <div class="modal-header d-block">
        <div class="d-flex justify-content-between align-items-center">
        <div></div>
        <div>
        <h2 class="m-0">Importar Conteúdo</h2>
        </div>
            <div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
            </div>
        </div>
      </div>
      <div class="modal-body">
        <div id="renderImport"></div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
        <button>Importar</button>
      </div>
    </div>
  </div>

            
            
            
            `
            document.getElementById("conteudo").appendChild(modal)
            this.filepond.bind(this)();
            this.modal = new bootstrap.Modal('#modalImportacao', {
  keyboard: false
});
        }else{
             this.modal.show();
        }
       
    }
    
    
}

class NovaTabela extends CicloVida{
    constructor( modulo, identificador, master, container = document.getElementById("conteudo")){
       super();
        this.identificador = identificador 
        this.modulo = modulo 
        this.master = false
        this.tabvalor = false;
        this.container = container
        this.filtros = {}
        
        this.ordemIndex = {}
        
        
        this.pagina = 1;
        this.quantidade = 20;
        this.order = "desc";
        this.limite = 1;
        this.qntPaginas = 0;
        
        var pesquisa = this.pegaHash("pesquisa");
        if(pesquisa){
            this.pesquisa = pesquisa;
        }else{
            this.pesquisa = false;
        }
        
        this.comeco = false;
        this.fim = false;
        
        this.checkboxes = [];
        this.lastCheck = false;
        
        this.selecionados = [];
        
        this.render.bind(this)();
        
        this.init.bind(this)();
    }
    
    pegaHash(attribute) {
    const hash = window.location.hash.slice(1); // Remove o "#" do início
    const params = new URLSearchParams(hash); // Converte o hash em parâmetros de busca

    // Obtém o valor do parâmetro especificado
    let valor = params.get(attribute) || null;

    if (valor) {
        // Substitui os "+" por espaços e os "-" por espaço
        valor = valor
            .replace(/\+/g, " ")  // Substitui "+" por espaço
            .replace(/-/g, " ");   // Substitui "-" por espaço
    }

    return valor; // Retorna o valor tratado ou null se não encontrado
}

    formatarData(dataString) {
    const data = new Date(dataString);

    if (isNaN(data)) return ["Data inválida", "Data inválida"];

    const hoje = new Date();
    const anoAtual = hoje.getFullYear();
    const anoData = data.getFullYear();
    const dia = data.getDate().toString().padStart(2, '0');
    const mesAbreviado = data.toLocaleString('pt-BR', { month: 'short' }); // Exemplo: "ago."
    const mesCompleto = data.toLocaleString('pt-BR', { month: 'long' });  // Exemplo: "novembro"
    const horas = data.getHours().toString().padStart(2, '0');
    const minutos = data.getMinutes().toString().padStart(2, '0');
    const segundos = data.getSeconds().toString().padStart(2, '0');

    /*
    const primeiroFormato = anoAtual === anoData
        ? `${dia} de ${mesAbreviado.charAt(0).toUpperCase() + mesAbreviado.slice(1)} ${horas}:${minutos}`
        : `${dia}/${(data.getMonth() + 1).toString().padStart(2, '0')}/${anoData} ${horas}:${minutos}`;
    */
    
        const primeiroFormato =  `${dia}/${(data.getMonth() + 1).toString().padStart(2, '0')}/${anoData} ${horas}:${minutos}`;
        
    const segundoFormato = `${dia} de ${mesCompleto} de ${anoData} ${horas}:${minutos}:${segundos}`;

    return [primeiroFormato, segundoFormato];
}

    selectControl(add, div, hash){
        if(add == 1){
            if (!this.selecionados.includes(hash)) {
                this.selecionados.push(hash);
            }
            div.classList.add("selecionada");
            div.getElementsByClassName("selecionar")[0].checked = true;

        }else{
            const index = this.selecionados.indexOf(hash);
                if (index > -1) {
                    this.selecionados.splice(index, 1);
                }
                div.classList.remove("selecionada");
                div.getElementsByClassName("selecionar")[0].checked = false;

        }
    }
    
    edicaoMassa(){
    
        if(!this.inMassa){
            var id = geraId();
            var modalMassa = document.createElement("DIV")
            modalMassa.id = id
            modalMassa.classList.add("modal","fade", "modal-nown")
            modalMassa.setAttribute("tabindex", "-1") 
            modalMassa.setAttribute("aria-hidden","true")
            modalMassa.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edição em Massa</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column gap-4">
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-n-primaria btn-nown-style salvar">Salvar</button>
      </div>
    </div>
  </div>
            `
            

           evento(modalMassa.getElementsByClassName("salvar")[0], "click", this.salvaMassa.bind(this)) 
           this.bodyMassa = modalMassa.getElementsByClassName("modal-body")[0];
           this.container.appendChild(modalMassa)
           this.inMassa = new bootstrap.Modal(`#${id}`, {
  keyboard: false
})
 
        }
        this.bodyMassa.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`;
        this.inMassa.show();
        
        let request = new Request(`${dominio}/admin/tabela.php`);
        var obj = {
            "acao" : "editMassaStart",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "itens": JSON.stringify(this.selecionados)
        }
        request.addData(obj);
        request.send().then((r)=>{
            var inputs = r.inputs;
            this.inputsMassa = {};
            var fragmento = document.createDocumentFragment();
            for(let c in inputs){
                var input = inputs[c];
                var div = document.createElement("DIV")
                div.classList.add("d-flex", "flex-column", "gap-1")
                var label = document.createElement("LABEL")
                label.innerText = input.nome
                
                
                switch(input.tipo){
                    case 'input':
                        var entrada = document.createElement("INPUT")
                        entrada.classList.add("form-control", "entrada")
                        entrada.placeholder = "Digite o valor"
                        break;
                    case 'select':
                        var entrada = document.createElement("SELECT")
                        entrada.classList.add("form-select", "entrada")
                        
                        var option = document.createElement("OPTION")
                        option.value = ""
                        option.innerText = "Sem Alteração"
                        entrada.appendChild(option)
                        
                        if(input.valores){
                            var i = 0;
                            for(let i in input.valores){
                                var option = document.createElement("OPTION")
                                option.value = i
                                option.innerText = input.valores[i]
                                entrada.appendChild(option)
                                i++;
                            }
                        }
                        
                        
                        break;
                }
                
                
               
            
   
                
                div.appendChild(label)
                

                
                div.appendChild(entrada)
                
                if(input.valorglobal){
                    entrada.value = input.valorglobal;
                }else{
                    var capa = document.createElement("SMALL")
                    capa.classList.add("fs-12")
                    capa.innerText = "Caso não seja definido nenhum valor, nenhuma alteraçãos será aplicada."
                    div.appendChild(capa)
                }
                
                
                fragmento.appendChild(div)
                this.inputsMassa[c] = div;
            }
             this.bodyMassa.innerHTML = "";
             this.bodyMassa.appendChild(fragmento);
            console.log(inputs)
        })
        
        
    }
    
    salvaMassa(){
        var salvar = {};
        for(let c in this.inputsMassa){
            var div = this.inputsMassa[c]
            if(div.getElementsByClassName("entrada")[0].value){
                salvar[c] = div.getElementsByClassName("entrada")[0].value
            }
        }
        
        if(Object.keys(salvar).length == 0){
            this.inMassa.hide();
            return;
        }
        
        let request = new Request(`${dominio}/admin/tabela.php`);
        var obj = {
            "acao" : "editMassaSalva",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "itens": JSON.stringify(this.selecionados),
            "edicao": JSON.stringify(salvar)
        }
        request.addData(obj);
        request.send().then((r)=>{
            this.inMassa.hide();
            this.init.bind(this)();
        })
    }

    calcSelecionados(){

        var div = document.getElementsByClassName("itensSelecionados")[0];
        switch(this.selecionados.length){
            case 0:
            case 1:
                div.innerHTML = "";
                break;
            default:
                div.innerHTML = `<span class="d-block text-center py-2"><span class="fw-700">${this.selecionados.length}</span> itens selecionados</span>`
                break;
            
        }
        
        var tamanho = Object.keys(this.show).length;
        if(tamanho > 0){
            var marcados = 0;
            for(let c in this.show){
                  if (this.selecionados.includes(c)) {
                      marcados++;
                  }
            }

          if (marcados == 0) {
              this.seletorMultiplo.checked = false;
              this.seletorMultiplo.indeterminate = false;
             
          } else if (tamanho == marcados) {
              this.seletorMultiplo.checked = true;
              this.seletorMultiplo.indeterminate = false;
              
          } else {
              this.seletorMultiplo.checked = true;
              this.seletorMultiplo.indeterminate = true;  
          }
          
          
          if(marcados > 0){
              
 
              
              var editavel = false;
              var i = 0;
              while(i < this.ordem.length){
                  if(this.ordem[i].editavel){
                      editavel = true;
                      break;
                  }
                  i++;
              }
              
              if(editavel && !this.btnEdicaoMassa){
                  var btn = document.createElement("BUTTON")
                  btn.classList.add("btn")
                  btn.innerHTML = `<i class="bi bi-pencil-square fs-14"></i>`
                  evento(btn, "click", this.edicaoMassa.bind(this))
                  this.btnEdicaoMassa = btn;
                  document.getElementById("topBar").appendChild(this.btnEdicaoMassa)
              }else{
                  if(this.btnEdicaoMassa){
                      this.btnEdicaoMassa.classList.remove("d-none")
                  }
              }
              
              if(!this.btnDeletaMassa){
                  var btn = document.createElement("BUTTON")
                  btn.classList.add("btn")
                  btn.innerHTML = `<i class="bi bi-trash3 fs-14"></i>`
                  this.btnDeletaMassa = btn;
                  evento(this.btnDeletaMassa, "click", ()=>{
                      this.deleta.bind(this)(this.selecionados)
                  })
                  document.getElementById("topBar").appendChild(this.btnDeletaMassa)
              }else{
                  this.btnDeletaMassa.classList.remove("d-none")
              }
         
                this.refresh.classList.add("d-none")
          }
          else{
              if(this.btnEdicaoMassa){
                  this.btnEdicaoMassa.classList.add("d-none")
              }
              
              if(this.btnDeletaMassa){
                  this.btnDeletaMassa.classList.add("d-none")
              }
              
              this.refresh.classList.remove("d-none")
              
              
          }
          
          
            
        }else{
            this.seletorMultiplo.checked = false;
        }

        
    }
    
    linha(item){
        
        
         
          
        var div = document.createElement("DIV")
        div.classList.add("Linha","linhaItem")
 
        div.dataset.h = item.H;
        evento(div, "contextmenu", this.rightClick.bind(this))
        var seleciona = document.createElement("DIV")
        
        
        if(this.config.order){
            var coluna = document.createElement("DIV")
            coluna.classList.add("ordenador")
            coluna.innerHTML = '<i class="bi bi-three-dots-vertical"></i>';
            div.appendChild(coluna)
        }
        
        if(this.config.multiple){
           var input = document.createElement("INPUT")
        input.classList.add("selecionar")
        input.title = "Selecionar"
        this.checkboxes.push(input)
        input.type = "checkbox"
        evento(input, "click", ()=>{
            
            
            
            
            
            var marcado = false;
            if(input.checked) {
      
                this.selectControl(1, div,item.H);
   
                marcado = true;
            } else {
                this.selectControl(0, div,item.H);
            }
            
            
            
             if (!this.lastCheck) {
                    this.lastCheck = input
                }

                if (event.shiftKey) {
                    const currentIndex = Array.from(this.checkboxes).indexOf(input);
                    const lastIndex = Array.from(this.checkboxes).indexOf(this.lastCheck);

                    const [start, end] = [currentIndex, lastIndex].sort((a, b) => a - b);

                    for (let i = start; i <= end; i++) {
                        this.checkboxes[i].checked = marcado;
                        var pai = this.checkboxes[i].closest(".linhaItem");
                        
                        
                        
                        
                        if(marcado){
                            this.selectControl(1, pai,pai.dataset.h);
                            
                        }else{
                            this.selectControl(0, pai,pai.dataset.h);
                        }
                    }
                }

                this.lastCheck = input;
            
                
                this.calcSelecionados.bind(this)(); 
            
            
        })
        
        
        const index = this.selecionados.indexOf(item.H);
        if (index > -1) {
            input.setAttribute("checked", true); 
            div.classList.add("selecionada")
        }

        
        
        
        seleciona.appendChild(input)
        div.appendChild(seleciona)
        }
        
        

        var i = 0;
        while(i < this.ordem.length){
            var ordem = this.ordem[i]
            
         
 
            var coluna = document.createElement("DIV")
            

            coluna.classList.add("col")
            if(item[ordem.key]){
                if (ordem.render) {
           
                    switch (ordem.render) {
                        case 1:case '1':case 'imagem':
        
                            coluna.classList.add("d-flex", "justify-content-start", "align-items-center")
                            var imagem = document.createElement("DIV")
                            imagem.classList.add("wi-35", "he-35", "img-thumbnail")
                            coluna.appendChild(imagem)
                            
                            var img = trataImagem(item[ordem.key] , "mini");
                            if (img) {
                                imagem.style = `background-image: url('${img}');background-size: cover; background-position: center;`
                                
                            } else {
                                imagem.innerHTML = `<i class="bi bi-image-fill"></i>`
                                imagem.classList.add("d-flex", "align-items-center", "justify-content-center")
                            }
                            this.sizesMap[i] = 4;

             break;
         case 2:
         case '2':
         case 'data':
             // Data
             var data = this.formatarData(item[ordem.key]);
             coluna.innerText = data[0]
             coluna.title = data[1]

             break;
         case 3:
         case '3':
         case 'link':
             // Link
             td.innerHTML = `<a href="${this.infos[c]}" target="_blank" class="text-primaria">${this.infos[c]}</a>`
             break;
         case 4:
         case '4':
         case 'botao':
             // Botao
             break;
         case 5:
         case '5':
         case 'qr':
             // QR Code
             //var div = document.createElement("DIV")
             //div.classList.add("he-100", "wi-100", "qr-code", "bg-carregando")
             //div.dataset.code = this.infos[c]
             coluna.innerHTML = '<div class="he-70 wi-70 bg-danger"></div>'
             break;
         case 6:
         case '6':
         case 'personalizado':
       
             coluna.innerText = ordem.regra?.[item[ordem.key]]  || item[ordem.key];
             break;
         case 7:
         case '7':
         case 'render':
     
             coluna.innerText = item[ordem.key]
             break;
         case 8:
         case '8':
         case 'preco':
             
             coluna.innerText = paraPreco(item[ordem.key])
             break;
        case 9:
        case '9':
           var ativo = parseInt(item[ordem.key]);
            var button = document.createElement("BUTTON")
            button.classList.add("btn")
            if(ativo){
                button.innerHTML = `<i class="bi bi-star-fill text-warning"></i>`
                button.dataset.ativo = "true";
            }else{
                 button.innerHTML = `<i class="bi bi-star"></i>`
                 button.dataset.ativo = "false";
            }
            
            evento(button, "click", (event)=>{
                    this.destaca.bind(this)(event, item.H)
            })
            
            coluna.appendChild(button)
            break;
     }
 
                }else{
                    if(ordem.estrangeira){
                      
                        coluna.innerText = this?.ext?.[ordem.key]?.[item[ordem.key]] || "";  
                    }else{
                        coluna.innerText = item[ordem.key];
                    }
                    
                }
            }
            
            if(!ordem.invisivel){
                 div.appendChild(coluna)
            }
              
     
            
            
            i++;
        }
        
        
     

        var acoes = document.createElement("DIV")
        acoes.classList.add("acao")
        
        var plus = false;
        if(this.config.edit){
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn")
            btn.innerHTML = `<i class="bi bi-pencil-square fs-12"></i>`
            evento(btn, "click", (event)=>{
                    this.edita.bind(this)(event, item.H)
            })
            acoes.appendChild(btn)
            plus = true;
            btn.title = "Editar"
        }
        
        if(this.config.delete){
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn", "btn-sm")
            btn.innerHTML = `<i class="bi bi-trash fs-12"></i>`
            acoes.appendChild(btn)
            evento(btn, "click", ()=>{
                this.deleta.bind(this)([item.H]);
            })
            plus = true;
            btn.title = "Deletar"
        }
        
        if(this.config.view){
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn")
            btn.innerHTML = `<i class="bi bi-eye fs-12"></i>`
            acoes.appendChild(btn)
            evento(btn, "click", (event)=>{
                this.ver.bind(this)(event, item.U);
            })
            plus = true;
            btn.title = "Visualizar"
        }
        
        if(this.config.view){
            var btn = document.createElement("BUTTON")
            btn.classList.add("btn")
            btn.innerHTML = `<i class="bi bi-copy fs-12"></i>`
            acoes.appendChild(btn)
            evento(btn, "click", (event)=>{
                this.duplicar.bind(this)(event, item.H);
            })
            plus = true;
            btn.title = "Duplicar"
        }
        
        
        if(this.personalizador.length > 0){
            var i = 0;
            while(i < this.personalizador.length){
                var per = this.personalizador[i]

                var btn = document.createElement("BUTTON")
                btn.classList.add("btn")
                btn.title = per.texto ?? "";
                btn.dataset.evt = per.callback;
                btn.innerHTML = per.icone ? `<i class="${per.icone} fs-12"></i>` : `<i class="bi bi-hand-index-thumb fs-12"></i>`
                acoes.appendChild(btn)
                evento(btn, "click", (event)=>{
                    this.custom.bind(this)(event.currentTarget.dataset.evt, event.currentTarget.closest(".linhaItem").dataset.h);
                })
                plus = true;
        
                
                i++;
            }
        }
      
    
        if(plus){
            div.appendChild(acoes)
        }
        
        return div;
    }
    
    custom(cb, h) {
    var itemOriginal = this.datas[h];

    if (!itemOriginal) {
        console.warn(`Item não encontrado para h = ${h}`);
        return;
    }


    this.ordem.forEach(it => {
        itemOriginal[it.nome] = itemOriginal[it.key];
    });

    if (typeof window[cb] === 'function') {
        window[cb](event, itemOriginal);
    } else {
        console.warn(`Função "${cb}" não encontrada ou inválida.`);
    }
}
    
    destaca(event, hash){

        var btn = event.currentTarget
        if(btn.dataset.ativo == "true"){
            btn.dataset.ativo = "false";
            btn.getElementsByTagName("I")[0].className = "bi bi-star"
            var ativa = 0;
        }else{
            btn.dataset.ativo = "true";
            btn.getElementsByTagName("I")[0].className = "bi bi-star-fill text-warning"
            var ativa = 1;
        }
        this.destacaAcao.bind(this)([hash], ativa);
    }
    
    destacaAcao(array, ativa = 1){
        let request = new Request(`${dominio}/admin/tabela.php`);
        var obj = {
            "acao" : "destaca",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "itens": JSON.stringify(array),
            "ativa": ativa
        }
        request.addData(obj)
        request.send().then((r)=>{
           
        })
    }
    
    edita(event, hash){
        if (event.shiftKey || event.ctrlKey || event.metaKey) {
                       var url = `${dominio}/${this.config.edicao}/${hash}`
                  } else {
                      var url = `${this.config.edicao}/${hash}`
                      
                  }
                goUrl(url);
    }
    
    ver(event, hash){
        if (event.shiftKey || event.ctrlKey || event.metaKey) {
                var url = `${dominio}/${this.config.ver}/${hash}`
            } else {
                var url = `${this.config.ver}/${hash}`
            }
                  
        goUrl(url)
    }
    
    deleta(array){

        let request = new Request(`${dominio}/admin/tabela.php`);
        var obj = {
            "acao" : "deleta",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "itens": JSON.stringify(array)
        }
        request.addData(obj)
        request.send().then((r)=>{
            iziToast.success({
                title: 'Sucesso',
                message: array.length == 1 ? `Item Apagado com Sucesso` : `${array.length} itens foram apagados`,
                icon: `bi bi-trash`
            });
            
          
      
            this.selecionados = this.selecionados.filter(item => !array.includes(item));

  
            this.init.bind(this)(true);
            this.calcSelecionados();
        })

    }
    
    tab(texto,  valor = false){
        var div = document.createElement("DIV")
        if(valor){
            div.dataset.valor = valor;
        }
        div.classList.add("tab", "flex-fill")
        if(!valor){
            div.classList.add("ativa")
        }
        div.innerHTML = `

                        <div class="estado">
                           <div class="conteudo">
                              <div class="icone">
                                
                                 <label>
                                ${texto}
                                 </label>
                              </div>
                              <div class="marcador">
                              </div>
                           </div>
                        </div>

        `
        evento(div, "click", this.tabs.bind(this))
        return div;
    }
    
    tabs(){
        var item = event.currentTarget
        if(item.classList.contains("ativa")){
            return;
        }
        
        document.getElementById("tabsTabela").getElementsByClassName("ativa")[0].classList.remove("ativa")
        item.classList.add("ativa")
        
        
        if(item.dataset.valor){
            this.tabvalor = item.dataset.valor;
        }else{
            this.tabvalor = false;
        }
        this.init.bind(this)();
    }
    
    tabulacao(r){

    var map = {};
    for (let c in r.v) {
        var item = r.v[c];
        map[item.id] = item.total;
    }
    
    var sortedMap = Object.keys(map)
    .sort((a, b) => a - b) 
    .reduce((acc, key) => {
        acc[key] = map[key];
        return acc;
    }, {});
    
    
    const tamanho = Object.keys(sortedMap).length;
    if(tamanho < 2){
        return;
    }
    
    
    var nomes = r.r;
    var fragmento = document.createDocumentFragment();
    
    
        fragmento.appendChild(this.tab("Todos"))
    
    for(let c in sortedMap){
        
        fragmento.appendChild(this.tab(nomes[c] ?? c, c))
        
    }
    document.getElementById("tabsTabela").appendChild(fragmento);
    


  
    }
    
    ordemColuna() {

    var index = event.currentTarget.dataset.index; // Obtém o índice
    let estado;

    // Inicializa o array no índice específico se ele estiver `undefined`
    if (!this.ordemIndex[index]) {
        this.ordemIndex[index] = "up"; // Define o estado inicial como "up"
        estado = "up";
    } else {
        // Alterna o estado entre "up", "down" e remove o índice
        if (this.ordemIndex[index] === "up") {
            this.ordemIndex[index] = "down";
            estado = "down";
        } else {
            delete this.ordemIndex[index]; // Remove o índice
            estado = false;
        }
    }
    event.currentTarget.classList.remove("up", "down")
    if(estado){
       event.currentTarget.classList.add(estado) 
    }
    

    this.init.bind(this)()
}

    init(filter = false){
        this.sizesMap = {};
        this.checkboxes = [];
       
        var obj = {
            "acao" : "tabela",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "page": this.pagina,
            "size": this.quantidade,
            "ordeIndex": JSON.stringify(this.ordemIndex)
        }
        
        nownFiles.add(`${dominio}/conteudo/modulos/${this.modulo}/assets/json.js`)
        
        if(this.filtros){
            obj.filtros = JSON.stringify(this.filtros)
        }
        
        if(this.tabvalor){
            obj.tab = this.tabvalor;
        }
        
        if(this.pesquisa){
            obj.pesquisa = this.pesquisa;
        }

        if(this.comeco && this.fim){
            obj.comeco = this.comeco;
            obj.fim = this.fim;
        }
        
        
        RequestRoute.quick("nown", "tabela", obj).then((obj)=>{
            
            
            console.log(obj.a)
            
            if(!obj.a.export && this.map.btns.exportador[0]){
                this.map.btns.exportador[0].closest("div").remove()
            }
            
            if(!obj.a.filter &&  this.map.btns.filtrar){
                this.map.btns.filtrar.remove();
            }
            
            
            if(!obj.a.import && this.map.btns.importar){
                this.map.btns.importar.remove();
            }
            
      

            
            this.cols = obj.cols;
            
            if(parseInt(obj.n.s) == 0){
                if(this.html.getElementsByClassName("tabela-header").length == 1){
                    this.html.getElementsByClassName("tabela-header")[0].remove();
                }
                
            }
            
            
            if(!this.tabula && obj.t){
                this.tabulacao.bind(this)(obj.t);
                this.tabula = true;
            }
        

            this.config = obj.a
            this.personalizador = obj.per
            
            var ordem = this.config.order ? 1 : 0;
            
            this.ext = obj.e
            
            
            this.show = {}
            this.ordem = [];
            var i = 0;
       
            for(let c in obj.m){
         
                obj.m[c].key = c
                this.ordem[obj.m[c].posicao] = obj.m[c]
                i++;
            }
            

            
            var header = document.createElement("DIV")
            header.classList.add("cabecalho", "cabecalhoTabela")
            
            
            if(obj.a.multiple){
                var div = document.createElement("DIV")
                div.classList.add("col")
                div.style.maxWidth = "20px"
                header.appendChild(div)
            }
            
            var i = 0;
            while(i < this.ordem.length){
                 
                 

                  var div = document.createElement("DIV")
                  div.classList.add("col")
                  var botao = document.createElement("BUTTON")
                  botao.classList.add("p-0")
                  botao.dataset.index = i
                  var span = document.createElement("SPAN")
                  span.innerText = this.ordem[i].nome ?? "";
                  var order = true;
                  if(this.ordem[i].render){
                      switch(this.ordem[i].render){
                          case '1':
                          case 1:
                          case 'imagem':
                              /*
                                div.style.maxWidth = "50px";
                                span.innerText = ""
                                order = false;
                                */
                            break;
                      }
                  }
                  
                  botao.classList.add("botaoCabecalho")
                  
                
                  if(order){
                        var up = document.createElement("SPAN");
                  up.innerHTML = `<i class="bi bi-caret-up-fill fs-12"></i>`;
                  up.classList.add("up")
                  botao.appendChild(up);
                    
                var down = document.createElement("SPAN");
                down.innerHTML = `<i class="bi bi-caret-down-fill fs-12"></i>`;
                down.classList.add("down")
                botao.appendChild(down);
                
                 evento(botao , "click", this.ordemColuna.bind(this))
                          
                  }
                
                   
                 botao.appendChild(span)
               
               
                 if(!this.ordem[i].invisivel){
                       div.appendChild(botao)
                       header.appendChild(div)
                 }

                
                 
                  
                  i++;
              }
              if(document.getElementsByClassName("cabecalhoTabela").length == 0 && parseInt(obj.n.s) > 0){
                  document.querySelector(".mdTabela").querySelector(".cabeca").appendChild(header);
              }
            
            
            var r = obj.r
            this.datas = {};
            if(r.length > 0){
                var i = 0;
                var fragmento = document.createDocumentFragment();
                while(i < r.length){
                    
                    var linha = this.linha.bind(this)(r[i])
                    if(r[i].H){
                        this.show[r[i].H] = linha;
                        this.datas[r[i].H] = r[i]
                    }
                    if(ordem == 0){
                        fragmento.appendChild(linha);
                    }else{
                        fragmento.insertBefore(linha, fragmento.firstChild);

                    }
                    
                    i++;
                }
                document.getElementById("lista").innerHTML = "";
              
                document.getElementById("lista").appendChild(fragmento)
                if(this.config.order){
                     nownFiles.add(`${dominio}/assets/bibliotecas/sortable/js.js`).then(()=>{
                new Sortable(document.getElementById("lista"), {
                    handle: '.ordenador',
                    animation: 150,
                     onEnd: this.movido.bind(this)
                });
        })
                }
            }
            else{
                document.getElementById("lista").innerHTML = `<div class="py-4 fw-700 text-center"><div class="col text-center">Nenhum resultado encontrado</div></div>`;
                document.getElementById("controlers") ? document.getElementById("controlers").innerHTML = "" : "";
                this.spinner.classList.add("d-none")
                return;
            }
            
            this.size = obj.n.s;
            if(filter){
                 this.addControlers.bind(this)(obj.n.s)
            }
            if(!this.controlers){
                if(obj.f.length > 0){
                    var k = 0;
                    let fils = obj.f;
                    var secs = [];
                    console.log(this.ext)
                    while(k < fils.length){
                        var fil = fils[k]
                        
                        console.log(fil)
                        
                        
                        var div = document.createElement("DIV")
                 
                        
                        var select = document.createElement("SELECT")
                        select.classList.add("form-select")
                        select.setAttribute("multiple","multiple")

                        secs.push({input: select, nome: fil.nome, id: fil.id})
                        for(let c in fil.valores){
                            var option = document.createElement("OPTION")
                            option.value = c
                            option.innerText = fil.valores[c]
                            select.appendChild(option)
                        }
                        var capsula = document.createElement("DIV")
 
                        capsula.appendChild(select)
                        div.appendChild(capsula)
                        
                        
                    
                        
                        this.html.getElementsByClassName("filtroContainer")[0].appendChild(div)
                        k++;
                    }
                    
                    
                    nownFiles.add([`${dominioscript}/assets/aplicativo/select2/min.js`, `${dominioscript}/assets/aplicativo/select2/min.css`]).then(()=>{
                        var p  = 0;
                        while(p < secs.length){
                
                            this.two(secs[p]);

                            p++;
                        }
                        
                        })

                    
                }
                else{
                    
                }

                this.addControlers.bind(this)(obj.n.s)
                
                if(obj.n.o && obj.n.n){
                    document.querySelector(".filtroData").closest(".pesquisa").classList.remove("d-none")
                    this.datePicker.bind(this)(obj);
                }else{
                    document.querySelector(".filtroData").closest(".pesquisa").remove();
                }
                
                       
              
            }else{
                var size = parseInt(obj.n.s)
                var atual = ((this.pagina -  1) * this.quantidade)
                if(atual == 0){
                    atual = 1;
                }
                 var conta = this.pagina * this.quantidade;
                 if(conta > size){
                     conta = size;
                 }
                 if(conta > 0){
                     this.qntPaginas  = Math.ceil(size / this.quantidade)
                     var calc = `${atual} -  ${conta} de ${size.toLocaleString('pt-BR')}`
                     this.controlers.innerText = calc;
                 }else{
                      this.controlers.innerText = "Nenhum resultado"
                 }
                 
            }
            
            this.tollbarControle.bind(this)();
            
            
            var input = this.seletorMultiplo
            var show = this.show
            var selecionados = this.selecionados
            
            var total = Object.keys(show).length;
            var marcados = 0;
            for(let c in show){
                if (selecionados.includes(c)) {
                    marcados++;
                }
            }
            
            if(input){
                if(marcados == 0){
                input.checked = false;
                input.indeterminate = false;
            }else if(marcados == total){
                input.checked = true;
                 input.indeterminate = false;
            }else{
                input.checked = true;
                input.indeterminate = true;  
            } 
            }
           
            
            
            
            
            
             this.spinner.classList.add("d-none")
            
            setTimeout(()=>{
                this.alinharColunas.bind(this)();
            }, 10)
     
        }, (r)=>{
             this.spinner.classList.add("d-none")
            console.log(r)
        })
    }
    
    two(select){

        var input = select.input
            $(input).select2({
            tags: false,
            tokenSeparators: [','],
            placeholder: select.nome,
            allowClear: true,
        
        }).on('change',  (e)=> {
                            
                            this.pagina = 1;
                            this.quantidade = 20;
        
                            const valores = $(input).val() || [];
           
                            this.filtros[select.id] = valores;
                            this.init.bind(this)();

                        });
    }
    
    alinharColunas() {
        
    
     const tabela = document.querySelector('.tabela-body'); // Container das linhas
    const linhas = tabela.querySelectorAll('.linhaItem, .cabecalho');

    if (linhas.length === 0) return;

    // Número de colunas na primeira linha (assume estrutura consistente)
    const colunas = linhas[0].querySelectorAll('.col');
    const larguras = Array.from(colunas).map(col => col.offsetWidth);

    // Ajusta a largura de todas as colunas para ocupar 100% da tabela
    const totalLargura = tabela.offsetWidth;
    const larguraBase = totalLargura / larguras.length; // Divide igualmente o espaço

    linhas.forEach(linha => {
        const colunasLinha = linha.querySelectorAll('.col');
        colunasLinha.forEach((col, i) => {
            const largura = Math.max(larguras[i], larguraBase); // Usa maior entre conteúdo ou largura base
            col.style.flex = `1 1 ${largura}px`; // Ajusta largura dinâmica
        });
    });
}

    movido(e){

    let index;
    if(this.pagina == 1){
        index =  e.newIndex + 1;
    }else{
        index = (this.pagina * this.size) + e.newIndex + 1;
    }

        
        /*
    var calc = this.pagina == 1 ? "0" : (this.pagina - 1) * this.quantidade
    var index = this.size - (calc + e.newIndex);
   console.log(index)
   */

    var request = new Request(`${dominio}/admin/tabela.php`);
    var obj = {
            "acao" : "ordenador",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "index": index,
            "id": e.item.dataset.h,
        }
    request.addData(obj)
    request.send().then((r)=>{
        console.log(r)
    })

  
         
    }
    
    datePicker(obj){
        const daysDifference = moment().diff(moment(obj.n.o, "YYYY-MM-DD HH:mm"), 'days');
               
                
                var ranges = {}
                
                if(daysDifference > 1){
                    ranges["Hoje"] = [moment(), moment()];
                }
                
                if(daysDifference > 2){
                     ranges["Ontem"] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
                }
                
                if(daysDifference > 7){
                     ranges["Últimos 7 dias"] = [moment().subtract(6, "days"), moment()];
                }
                
                if(daysDifference > 30){
                    ranges["Últimos 30 dias"] = [moment().subtract(29, "days"), moment()];
                    ranges["Esse mês"] = [moment().startOf("month"), moment().endOf("month")];
                    ranges["Último Mês"] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
                }else{
                    if(daysDifference > 7){
                        ranges[`Últimos ${daysDifference} dias`] = [moment().subtract(daysDifference - 1, "days"), moment()];
                    }
                }
                
                ranges["Todo Periodo"] = [moment(obj.n.o, "YYYY-MM-DD HH:mm"), moment(obj.n.n, "YYYY-MM-DD HH:mm")]
                
                
                dataPicker(document.querySelector(".filtroData"), (start, end) => {
                    this.comeco = start.format("YYYY-MM-DD HH:mm");
                    this.fim = end.format("YYYY-MM-DD HH:mm");
                    this.pagina = 1;
                    this.quantidade = 20;
                    this.init.bind(this)(true);
                }, {
                    startDate: moment(obj.n.o, "YYYY-MM-DD HH:mm"), 
                    endDate: moment(obj.n.n, "YYYY-MM-DD HH:mm"), 
                    minDate: moment(obj.n.o, "YYYY-MM-DD HH:mm"),
                    maxDate: moment(obj.n.n, "YYYY-MM-DD HH:mm"),
                    ranges: ranges
                });
       
    }
    
    tollbarControle(){
        if(this.config.multiple){
            if(!this.seletorMultiplo){
                var div = document.createElement("DIV")
                
                var group = document.createElement("DIV")
                group.classList.add("input-group")
                
                this.seletorMultiplo = document.createElement("INPUT")
                this.seletorMultiplo.type ="checkbox"
                evento(this.seletorMultiplo, "click", ()=>{
                    if(this.seletorMultiplo.checked){
                        var acao = 1;
                    }else{
                        var acao = 0;
                    }
                    
                    for(let c in this.show){
                        this.selectControl(acao , this.show[c] ,c);
                    }
                    this.calcSelecionados.bind(this)();
                    
                })
                
     
                var button1 = document.createElement("DIV")
                button1.classList.add("btn","dropdown-toggle")
                button1.setAttribute("type","button") 
                button1.setAttribute("data-bs-toggle","dropdown") 
                button1.setAttribute("aria-expanded", "false")
                
                var ul = document.createElement("UL")
                ul.classList.add("dropdown-menu", "dropdown-nown", "dropdown-menu-start")
                
                var li = document.createElement("LI")
                var button = document.createElement("BUTTON")
                button.classList.add("dropdown-item")
                button.innerText = "Todos"
                evento(button, "click", ()=>{
                    
                    for(let c in this.show){
                        this.selectControl(1, this.show[c] ,c);
                    }
                    
                    this.seletorMultiplo.checked = true;
                    this.calcSelecionados.bind(this)();
                })
                li.appendChild(button)
                ul.appendChild(li)
                
                var li = document.createElement("LI")
                var button = document.createElement("BUTTON")
                button.classList.add("dropdown-item")
                button.innerText = "Nenhum"
                evento(button, "click", ()=>{
                    
                     for(let c in this.show){
                        this.selectControl(0 , this.show[c] , c);
                    }
                    this.seletorMultiplo.checked = false;
                    this.calcSelecionados.bind(this)();
                })
                li.appendChild(button)
                ul.appendChild(li)
                
                
                
                group.appendChild(this.seletorMultiplo);
                group.appendChild(button1);
                group.appendChild(ul);
                div.appendChild(group)
                document.getElementById("topBar").appendChild(div)
                
                
                this.refresh = document.createElement("BUTTON")
                this.refresh.innerHTML = `<i class="bi bi-arrow-clockwise"></i>`
                this.refresh.classList.add("btn")
                evento(this.refresh, "click", ()=>{
                    this.init.bind(this)()
                    iziToast.success({
                        title: 'Sucesso',
                        message: `Tabela atualizada`,
                        icon: `bi bi-arrow-clockwise`
                    });
                })
                document.getElementById("topBar").appendChild(this.refresh)
                
            }
        }
        
    
    }
    
    addControlers(size){
        document.getElementById("controlers").innerHTML = "";
        var size = parseInt(size)
        this.controlers = document.createElement("DIV")
        this.controlers.classList.add("fs-14")
        let btns = false;
        if(size < this.quantidade){ 
            if(size == 0){
                var calc = `Nenhum resultado`
            }else{
                var calc = `1 - ${size}`
            }
        }else{
            this.qntPaginas  = Math.ceil(size / this.quantidade)
            var calc = `${this.pagina} -  ${this.pagina * this.quantidade} de ${size.toLocaleString('pt-BR')}`
            btns = true;
        }
        this.controlers.innerText = calc;
        
        
        
        this.limite = Math.ceil(size / this.quantidade);

        
        
        
        var fragmento = document.createDocumentFragment();
        fragmento.appendChild(this.controlers)
        
        if(btns){
            var volta = document.createElement("DIV")
        this.btnVolta = document.createElement("BUTTON")
        this.btnVolta.classList.add("btn")
        this.btnVolta.innerHTML = `<i class="bi bi-chevron-left fs-14"></i>`
        evento(this.btnVolta, "click", ()=>{
            this.go.bind(this)(-1);
        })
        volta.appendChild(this.btnVolta)
        fragmento.appendChild(volta)
        
        var vai = document.createElement("DIV")
        this.btnVai = document.createElement("BUTTON")
        this.btnVai.classList.add("btn")
        this.btnVai.innerHTML = `<i class="bi bi-chevron-right fs-14"></i>`
        evento(this.btnVai, "click", ()=>{
            this.go.bind(this)(+1);
        })
        vai.appendChild(this.btnVai)
        fragmento.appendChild(vai)
        
        
      
        if(size > 20){
             var tamanhos = [20, 50, 100, 200];
             var select = document.createElement("SELECT")
             select.classList.add("border-0", "fs-14")
            var i = 0;
            while(i < tamanhos.length){
                if(tamanhos[i] < size){
                    var option = document.createElement("OPTION")
                    option.innerText = tamanhos[i]
                    option.value = tamanhos[i]
                    select.appendChild(option)
                }else{
                    break;
                }
                
                i++;
            }
            
            if(size < 200){
                 var option = document.createElement("OPTION")
                option.innerText = size;
                option.value = size;
                select.appendChild(option)
            }
            
            
            
            evento(select, "input", ()=>{
                this.quantidade = select.value
                this.limite = Math.ceil(size / this.quantidade);
                this.pagina = 1;
                
                this.init.bind(this)();
            })
            var div = document.createElement("DIV")
            div.appendChild(select)
            fragmento.appendChild(div)
        }
        
        }
        
        
        
        
        document.getElementById("controlers").appendChild(fragmento)
    }
    
    go(num){
        this.pagina = this.pagina + num
        if(this.pagina == 0){
            console.log("noa pode voltar mais")
            this.pagina = 1;
            return;
        }
        
        if(this.pagina > this.limite){
            console.log("nao pode proseguir")
            this.pagina = this.limite;
            return;
        }
    
        this.init.bind(this)();
         document.querySelector(".tabela-body").scrollTo({ top: 0, behavior: "smooth" }); 
    }
    
    duplicar(event, hash){
         let request = new Request(`${dominio}/admin/tabela.php`);
        var obj = {
            "acao" : "duplicar",
            "identificador" : this.identificador,
            "modulo" : this.modulo,
            "master" : this.master,
            "item": hash
        }
        request.addData(obj)
        request.send().then((r)=>{
            iziToast.success({
                icon: 'bi bi-check-circle',
    title: 'Sucesso',
    message: 'Item duplicado'
});
            this.init.bind(this)();
        }, (r)=>{
            console.log(r)
        })
    }
    
    rightClick(event){
         event.preventDefault();
            
            

            
            var h = event.currentTarget.dataset.h;

      
            const existingTippy = document.querySelector('.tippy-box');
            if (existingTippy) existingTippy.remove();
            
            var btns = [];
            if(this.config.view){
                
                btns.push({
                    i: 'bi bi-eye', t: 'Visualisar', cb: 1
                });
                
                 /*
                if(this.selecionados.length > 1){
                    btns.push({
                    i: 'bi bi-eye', t: 'Ver Todos', cb: 5
                    })
                }
                 */
            }
            
            if(this.config.delete){
                btns.push({
                    i: 'bi bi-trash', t: 'Deletar', cb: 2
                });
                
                 /*
                 if(this.selecionados.length > 1){
                    btns.push({
                    i: 'bi bi-trash', t: 'Deletar Todos', cb: 7
                    })
                }
                */
            }
            
            if(this.config.edit){
                btns.push({
                    i: 'bi bi-pencil-square', t: 'Editar', cb: 3
                });
                
                 /*
                 if(this.selecionados.length > 1){
                    btns.push({
                    i: 'bi bi-pencil-square', t: 'Editar Todos', cb: 6
                    })
                }
                */
            }
            
              if(this.config.duplicar){
                btns.push({
                    i: 'bi bi-copy', t: 'Duplicar', cb: 4, evt: this.duplicar.bind(this)
                });
                
                 /*
                 if(this.selecionados.length > 1){
                    btns.push({
                    i: 'bi bi-pencil-square', t: 'Editar Todos', cb: 6
                    })
                }
                */
            }
            
            if(this.personalizador.length > 0){
                var idx = 0;
               
                while(idx < this.personalizador.length){
                     var per = this.personalizador[idx]
                    btns.push({
                    i: per.icone , t: per.texto, cb: 4, evt: per.callback
                    });
                    idx++;
                }
            }
            

             if(this.config.order && this.qntPaginas > 1){
                 btns.push(
                     {
                    i: 'bi-arrows-move', t: 'Mover', cb: 4, "plus":true
                }
                );
                
                var index = Array.from(event.currentTarget.closest(".corpo").querySelectorAll('.linhaItem')).indexOf(event.currentTarget);
                
                

                
               
            }
            
            if(btns.length == 0){
                return;
            }
            
            var html = "";
            var i = 0;
            while(i < btns.length){
                if(!btns[i].plus){
                    html += `<button class="list-group-item list-group-item-action d-flex justify-content-start align-items-center gap-2" data-cb="${btns[i].cb}" data-evt="${btns[i].cb == 4 ? btns[i].evt : false}"><span><i class="bi ${btns[i].i}"></i></span><span>${btns[i].t}</span></button>`
                }else{
                     html += `<button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-2" data-cb="${btns[i].cb}" data-evt="${btns[i].cb == 4 ? btns[i].evt : false}">
                        <span class="d-flex justify-content-start align-items-center gap-2">
                        <span><i class="bi ${btns[i].i}"></i></span><span>${btns[i].t}</span>
                        </span>
                        <span>
                            <i class="bi bi-caret-right-fill fs-12"></i>
                        </span>
                     </button>`
                }
                
                i++;
            }
           
        
        let self = this;
            const instance = tippy(document.body, {
                content: `
                <div class="list-group list-group-flush" style="min-width: 300px">
                    ${html}
                </div>
                `,
                allowHTML: true,
                interactive: true,
                trigger: 'manual',
                placement: 'right',
                appendTo: document.body,
                onShow(instance) {

        const buttons = instance.popper.querySelectorAll('.list-group-item');
    
  
            buttons.forEach((button) => {
            button.addEventListener('click', (event) => {
                instance.hide();
                switch(parseInt(button.dataset.cb)){
                    case 1:
                        // Ver
                        self.ver.bind(self)(event, h);
                        break;
                    case 2:
                        // Deleta
                        self.deleta.bind(self)([h]);
                        break;
                    case 3:
                        // Edita
                        self.edita.bind(self)(event, h);
                        break;
                    case 4:
                        //
                         self.custom.bind(self)(event.currentTarget.dataset.evt, h);
                        break;
                    case 5:
                        // Ver Todos
                        self.verTodos.bind(self)();
                        break;
                    case 6:
                        // Editar Todos
                        self.editaTodos.bind(self)();
                        break;
                    case 7:
                        // Deletar Todos
                        break;
                }
            });
        });
    }
            });

            instance.setProps({
                getReferenceClientRect: () => ({
                    width: 0,
                    height: 0,
                    top: event.clientY,
                    bottom: event.clientY,
                    left: event.clientX,
                    right: event.clientX,
                }),
            });

            instance.show();
    }
    
    verTodos(){
         var selecionados = this.selecionados
        var i = 0;
        while(i < selecionados.length){
            var url = `${dominio}/${this.config.ver}//${selecionados[i]}`
            window.open(url , '_blank');
            i++;
        }
        
         
             var url = ``
    }
    
    editaTodos(){
        var selecionados = this.selecionados
        var i = 0;
        while(i < selecionados.length){
            var url = `${dominio}/${this.config.edicao}/${selecionados[i]}`
            window.open(url , '_blank');
            i++;
        }
    }
    
    render(){
        var div = document.createElement("DIV")
        
  
        var id = geraId();
        div.innerHTML = `
        <div class="card card-nown cabecaTabela">
   <div class="card-header">
      <div class="d-xl-flex justify-content-between align-items-center">
         <div class="title-container d-flex gap-2 align-items-center">
            <div class="position-relative flex-fill">
                <input class="form-control form-control-sm rounded-pill px-5 procurar" placeholder="Procurar ...">
                <span class="position-absolute top-50 translate-middle-y" style="left: 20px"><i class="bi bi-search fs-14"></i></span>
                <span class="position-absolute top-50 translate-middle-y spinner d-none" style="right: 20px">
                <div class="spinner-border" role="status" style="width:20px; height: 20px"><span class="visually-hidden">Loading...</span></div>
                </span>
            </div>
            <div class="d-block d-xl-none">
            <button class="btn"><i class="bi bi-list"></i></button>
            </div>
         </div>
         <div class="d-none d-xl-flex justify-content-xl-between gap-2 align-items-center">
            <div class="pesquisa d-none">
               <div class="position-relative">
                  <input class="form-control form-control-sm filtroData rounded-pill ps-5 fs-12" type="text" id="${id}">
                  <label class="position-absolute top-50 translate-middle-y" style="left: 20px" for="${id}"><i class="bi bi-calendar-check"></i></label>
               </div>
            </div>
            <div >
               <button class="btn-ferramenta border-danger border text-danger btn-filtrar" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
               <i class="bi bi-funnel"></i> <span class="d-none d-xl-block">Filtrar</span>
               </button>
            </div>
            <div >
               <div class="dropdown">
                  <button class="btn-ferramenta border-primary border text-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-box-arrow-down"></i> <span class="d-none d-xl-block">Exportar</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-nown">
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="pdf"><span><i class="bi bi-filetype-pdf fs-12"></i></span> <span class="fs-14">PDF</span></button></li>
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="imprimir"><span><i class="bi bi-printer-fill fs-12"></i></span> <span class="fs-14">IMPRIMIR</span></button></li>
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="csv"><span><i class="bi bi-filetype-csv fs-12"></i></span> <span class="fs-14">CSV</span></button></li>
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="excel"><span><i class="bi bi-file-earmark-excel fs-12"></i></span> <span class="fs-14">EXCEL</span></button></li>
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="html"><span><i class="bi bi-filetype-html fs-12"></i></span> <span class="fs-14">HTML</span></button></li>
                     <li><button class="dropdown-item d-flex gap-2 align-items-center exportador" data-foco="copiar"><span><i class="bi bi-copy fs-12"></i></span> <span class="fs-14">COPIAR</span></button></li>
                  </ul>
               </div>
            </div>
            <div>
               <button class="btn-ferramenta border-warning border text-warning btn-importar" id="importar"> 
               <i class="bi bi-box-arrow-up"></i>
               </button>
            </div>
            <div class="btnConfiguracao">
               <button class="icon-button btn">
               <i class="bi bi-gear"></i>
               </button>
            </div>
         </div>
      </div>
   </div>
   <div class="collapse" id="collapseExample">
      <div class="card-body filtroContainer d-flex flex-column gap-2"></div>
   </div>
   <div class="card-body d-none">
      <div class="containerTabela">
      
      </div>
   </div>
</div>
   <div class="cardTabela mt-4">
            <div class="tabela-header">
               <div class="linha d-flex justify-content-between align-item-center">
                  <div>
                     <div class="h-100 d-flex align-items-center justify-content-start " id="topBar">
                     </div>
                  </div>
                  <div>
                     <div class="h-100 d-flex align-items-center" id="controlers">
                     </div>
                  </div>
               </div>
            </div>
            <div class="tabela-body">
               <div class="mdTabela">
                  <div class="cabeca">
                     <div class="bg-secondary itensSelecionados text-center text-dark">
                     </div>
                     <div class="tabs">
                        <div class="grupo" id="tabsTabela">
                        </div>
                     </div>
                     <div class="linha">
                     </div>
                  </div>
                  <div class="corpo" id="lista">
                  </div>
                  <div class="rodape">
                  </div>
               </div>
            </div>
         </div>
        `
        
        this.map = {
            btns: {
                configuracao: div.getElementsByClassName("btnConfiguracao")[0],
                exportador: div.getElementsByClassName("exportador"),
                filtrar: div.getElementsByClassName("btn-filtrar")[0],
                importar: div.getElementsByClassName("btn-importar")[0]
            }
        }
        
        evento(this.map.btns.configuracao, "click", this.canvas.bind(this))
        evento(Array.from(this.map.btns.exportador), "click", this.exportar.bind(this))
        
        var i = 0;
        var fragmento = document.createDocumentFragment();
        while(i < 20){
            var carregando = document.createElement("DIV")
        carregando.classList.add("Linha","linhaItem")
        carregando.innerHTML = `
        <div><div style="height: 16px" class="bg-carregando"></div></div>
        <div class="col"><div style="height: 16px" class="bg-carregando"></div></div>
        <div class="col"><div style="height: 16px" class="bg-carregando"></div></div>
        <div class="col"><div style="height: 16px" class="bg-carregando"></div></div>
        <div class="col"><div style="height: 16px" class="bg-carregando"></div></div>
  
        `
        fragmento.appendChild(carregando)
            i++;
        }
        div.getElementsByClassName("corpo")[0].appendChild(fragmento)
       
        this.search = div.getElementsByClassName("procurar")[0];
        this.spinner = div.getElementsByClassName("spinner")[0];
        
        this

        bounce(this.search, 500, (e)=>{
            this.busca.bind(this)(this.search.value)
        })
        evento(this.search, "input", ()=>{
            this.spinner.classList.remove("d-none")
        })
        
    
        
        var container = document.createElement("DIV")
        container.classList.add("container")
        container.appendChild(div)
        this.html = div;
         //document.getElementById("conteudo").innerHTML = "";
        this.container.appendChild(container)
        

        
         new Importador(this.identificador, this.modulo , this.master);

    }
    
    montarUrlComGet(baseURL, params) {
  // Converte cada par (chave=valor) em chave=valor encodados
  const queryString = Object.keys(params)
    .map((key) => {
      return encodeURIComponent(key) + "=" + encodeURIComponent(params[key]);
    })
    .join("&");

  // Retorna a URL completa com ? e a query string
  return `${baseURL}?${queryString}`;
}

    exportar() {
    var foco = event.currentTarget.dataset.foco;
    
    var obj = {
        "identificador": this.identificador,
        "modulo": this.modulo,
        "master": this.master,
        "ordeIndex": JSON.stringify(this.ordemIndex),
        "arquivo": foco
    }
    
    if(this.filtros) {
        obj.filtros = JSON.stringify(this.filtros)
    }
    
    if(this.tabvalor) {
        obj.tab = this.tabvalor;
    }
    
    if(this.pesquisa) {
        obj.pesquisa = this.pesquisa;
    }
    
    if(this.comeco && this.fim) {
        obj.comeco = this.comeco;
        obj.fim = this.fim;
    }
    
    var request = new Request(`${dominio}/admin/exportador.php`);
    request.addData(obj);
    request.send().then((response)=>{
        console.log(response)
         let extensao = '';
                switch (foco) {
                    case 'html':
                    case 'imprimir':
                        extensao = 'html';
                        break;
                    case 'excel':
                        extensao = 'xls';
                        break;
                    case 'csv':
                        extensao = 'csv';
                        break;
                    case 'pdf':
                        extensao = 'pdf';
                        break;
                    default:
                        console.error("Tipo de arquivo não reconhecido");
                        return;
                }
                
                // Criar o URL de download
                const downloadUrl = `${dominio}/conteudo/provisorio/${response.hash}.${extensao}`;
                console.log(downloadUrl)
                // Criar um elemento de link temporário para iniciar o download
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.target = '_blank';
                
                // Definir o nome do arquivo para download
                const fileName = `exportacao_${this.modulo}_${new Date().toISOString().slice(0, 10)}.${extensao}`;
                link.download = fileName;
                
                // Adicionar o link ao documento, clicar nele e depois removê-lo
                document.body.appendChild(link);
                link.click();
                
                // Remover o link após um pequeno atraso
                setTimeout(() => {
                    document.body.removeChild(link);
                }, 100);
        
    });
}

    canvas(){

        if(!this.canvaSetup){
                var div = document.createElement("DIV")
        div.classList.add("nown-canvas")
        div.id = "tabelaConfig"
        div.innerHTML = `  <div class="nown-canvas-content">
        <header>
            <h3>Configurações</h3>
            <button class="btn-close-nown-canvas" data-close-canvas></button>
        </header>
        <main class="d-flex flex-column gap-4">
        <div class="card card-nown">
            <div class="card-header">
                <h2 class="m-0 fs-18">Layout da Tabela</h2>
            </div>
            <div class="card-body layouts d-flex flex-column gap-2">
             

            </div>
        </div>
        
        <div class="card card-nown">
            <div class="card-header">
                <h2 class="m-0 fs-18">Colunas</h2>
            </div>
            <div class="card-body  d-flex flex-column gap-2">
             <ul class="list-group list-group-flush colunas"></ul>

            </div>
        </div>
        </main>
   
    </div>
    <div class="controller"></div>`
    
    this.container.appendChild(div)
    
    
    var layouts = [
        {"texto": "Padrão", "chave":"padrao"},
        {"texto": "Dalmata", "chave":"dalmata"},
        {"texto": "Light Blue",  "chave":"light-blue"},
        {"texto": "Light Danger", "chave":"red-blaze"},
        {"texto": "Forest", "chave":"forest"},
        {"texto": "Sunset", "chave":"sunset"},
        {"texto": "Ocean", "chave":"ocean"},
        {"texto": "Autumn", "chave":"autumn"},
        {"texto": "Galaxy", "chave":"galaxy"}
        ];
    
    var fragmento = document.createDocumentFragment();
    for(let c in layouts){
        var l = layouts[c]
        var li = document.createElement("DIV")
        li.innerHTML = `
           <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="layoutTabela" id="${l.chave}">
                    <label class="form-check-label" for="${l.chave}">
   ${l.texto}
  </label>
                </div>

  <label class="form-check-label" for="${l.chave}">
    <img src="https://ssl.gstatic.com/ui/v1/icons/mail/quicksettings/nav/Nav_promo.png">
  </label>
</div>
        
        `
        evento(li.getElementsByClassName("form-check-input")[0], "input", this.changeLayout.bind(this))
        fragmento.appendChild(li)
        
        
        
    }
    div.getElementsByClassName("layouts")[0].appendChild(fragmento)
    
    this.cols.sort((a, b) => a.posicao - b.posicao);


    
    var fragmento = document.createDocumentFragment();
    var i = 0;
    this.visibilidadeColunas = [];
    while(i < this.cols.length){
        var coluna = this.cols[i]
        var checked = coluna.invisivel ? "" : "checked"
        var id = geraId();
        var li = document.createElement("LI")
        li.classList.add("list-group-item","d-flex","justify-content-between","align-items-center")
        li.innerHTML = `
        <label for="${id}">${coluna.nome}</label>
            <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="${id}" ${checked} data-id="${coluna.id}">
        </div>
        `
        evento(li.getElementsByClassName("form-check-input")[0], "input", this.changeVisibilidade.bind(this))
        this.visibilidadeColunas.push(li.getElementsByClassName("form-check-input")[0])
        fragmento.appendChild(li)
        i++;
    
    }
    
    var li = document.createElement("LI")
    li.classList.add("list-group-item","d-flex","justify-content-between","align-items-center")
    
    var btn = document.createElement("BUTTON")
    btn.innerText = "Resetar Configurações"
    btn.classList.add("btn", "btn-n-primaria", "btn-nown-style", "d-block", "w-100")
    evento(btn, "click", this.resetaSetup.bind(this))
    li.appendChild(btn)
    fragmento.appendChild(li)
    
        div.getElementsByClassName("colunas")[0].appendChild(fragmento)
        
        nownFiles.add(`${dominio}/assets/bibliotecas/sortable/js.js`).then(()=>{
                new Sortable(div.getElementsByClassName("colunas")[0], {
                    animation: 150,
                    onEnd: this.colunaOrdenada.bind(this)
                });
                
                
        })
    
    
    
     this.canvaSetup = new nownCanvas("tabelaConfig", {
                dirDesktop : "right",
                dirMobile : "bottom",
                backdropBlur : true,
                persist:  true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
            
        }
        
        this.canvaSetup.show();
    
            
        
        


    }
    
    colunaOrdenada(){
        var colunas = {}
        var inputs = this.visibilidadeColunas[0].closest(".colunas").getElementsByClassName("form-check-input");
          var i = 0;
        while(i < inputs.length){
            colunas[inputs[i].dataset.id] = i
            i++;
        }
        
        let request = new Request(`${dominio}/admin/tabela.php`)
        request.addData({
            "identificador":  this.identificador,
            "modulo": this.modulo,
            "acao": "ordemColuna",
            "colunas": JSON.stringify(colunas)
        })
        request.send().then((r)=>{
            document.getElementsByClassName("cabecalhoTabela")[0].remove();
            this.init.bind(this)();
        })
        

        
    }
    
    ajustarLarguraColunas() {
  // Selecionar todas as linhas
  const rows = document.querySelectorAll(".row");

  // Verificar o número de colunas pela primeira linha
  const numColunas = rows[0]?.children.length || 0;

  // Armazena a largura máxima para cada coluna
  const larguras = Array(numColunas).fill(0);

  // Calcular largura máxima de cada coluna
  rows.forEach(row => {
    Array.from(row.children).forEach((cell, index) => {
      // Obter largura do texto
      const largura = cell.offsetWidth || cell.scrollWidth;
      larguras[index] = Math.max(larguras[index], largura);
    });
  });

  // Aplicar largura máxima a todas as células
  rows.forEach(row => {
    Array.from(row.children).forEach((cell, index) => {
      cell.style.flex = `0 0 ${larguras[index]}px`;
    });
  });
}
    
    changeVisibilidade(){
        var colunas = {}
        var i = 0;
        while(i < this.visibilidadeColunas.length){
            var col = this.visibilidadeColunas[i]
            colunas[col.dataset.id] = col.checked
            i++;
        }
        
        let request = new Request(`${dominio}/admin/tabela.php`)
        request.addData({
            "identificador":  this.identificador,
            "modulo": this.modulo,
            "acao": "visibilidadeColuna",
            "colunas": JSON.stringify(colunas)
        })
        request.send().then((r)=>{
            document.getElementsByClassName("cabecalhoTabela")[0].remove();
            this.init.bind(this)();
        })
    }
    
    resetaSetup(){
       let request = new Request(`${dominio}/admin/tabela.php`)
        request.addData({
            "identificador":  this.identificador,
            "modulo": this.modulo,
            "acao": "resetVisibilidade",
        })
        request.send().then((r)=>{
            
            
            var map = r.map
             var i = 0;
        while(i < this.visibilidadeColunas.length){
            var col = this.visibilidadeColunas[i]
            if(map[col.dataset.id]){
                col.checked = false;
            }else{
                 col.checked = true;
            }
            i++;
        }
        
        
            
            document.getElementsByClassName("cabecalhoTabela")[0].remove();
            this.init.bind(this)();
        })
    }
    
    changeLayout(){
        this.html.getElementsByClassName("card")[0].dataset.layout = event.currentTarget.id
        this.html.getElementsByClassName("mdTabela")[0].dataset.layout = event.currentTarget.id
    }
    
    busca(r){
        this.pesquisa = r;
        this.pagina = 1;
        this.quantidade = 20;
        this.order = "desc";
        this.init.bind(this)();
    }
}