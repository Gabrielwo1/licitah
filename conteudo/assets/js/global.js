class ModalOportunidade{
    constructor(cb = false){
        if(cb){
            this.cb = cb
        }
        this.botao = document.getElementById('modal-de-oportunidades')
        this.botaoFinal = document.getElementById('botao-final')
        this.regioes = document.getElementById('dados-regionais').getElementsByClassName('regionais')
        this.checksRegionais = document.getElementById('dados-regionais').getElementsByClassName('check-regionais')
        
        this.divTags = document.getElementById('tagWrap')
        this.inputTags = document.getElementsByName('tag-cadastrada')[0]
        this.tagsCadastradas = []
        if(this.tagsCadastradas.length > 0){
            this.tagsCadastradas = JSON.stringify(this.tagsCadastradas)
        }
        
        
        
        
        this.modal = new bootstrap.Modal('#exampleModalToggle', {
          keyboard: false
        })
        
        this.modal2 = new bootstrap.Modal('#exampleModalToggle2', {
          keyboard: false
        })
        
        this.botaoAdicionarTag = document.getElementsByClassName('btn-adicionar-tag')[0]
        
        evento(this.botao, 'click', this.abrirModal.bind(this))
        evento(this.botaoFinal, 'click', this.selecionarDados.bind(this))
        evento(this.botaoAdicionarTag, 'click', this.adicionarTag.bind(this))
        
        evento(document.getElementById('exampleModalToggle').getElementsByClassName('form-control')[0] , 'keydown', (e)=>{
            console.log(e)
            if (e.key === 'Enter') {
                this.adicionarTag.bind(this)()
            }
        })
        
        evento(Array.from(this.regioes), 'change', this.selecionarChecks.bind(this))
        evento(Array.from(this.checksRegionais), 'change', this.verificarChecks.bind(this))

        if(!caminho.hash()){
            var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
            request.addData({
                acao: 'pegarMinhasOportunidades',
            })
    
            request.send().then((r) => {
                this.botao.remove()
                if(r.item && r.item.tags && JSON.parse(r.item.tags) && JSON.parse(r.item.tags).length == 0){
                    this.modal.show()
                    this.inputTags.value = JSON.stringify(this.tagsCadastradas)
                }
                if(!r.item){
                    this.modal.show()
                    this.inputTags.value = JSON.stringify(this.tagsCadastradas)
                }
            }, (r)=>{
                this.modal.show()
                this.inputTags.value = JSON.stringify(this.tagsCadastradas)
            })
        }
    }
    
    adicionarTag(){
        let input = document.getElementsByName('tag')[0]
        let inputValue = input.value.trim()
        let tagsCadastradas = JSON.parse(this.inputTags.value)
        
        if(!inputValue){
            alert('Digite uma tag Válida')
            return
        }
        
        if (!tagsCadastradas.some(tag => tag.toLowerCase() === inputValue.toLowerCase())) {
            tagsCadastradas.push(inputValue)
            this.inputTags.value = JSON.stringify(tagsCadastradas)
            input.value = ''
        }else {
            iziToast.error({
              message: "Tag já cadastrada!",
              timeout: 5000,
              position: 'bottomRight',
            })
        }
        
        this.montaTags.bind(this)()
        
    }
    
    montaTags(){
        let i = 0
        let fragmento = document.createDocumentFragment()
        let tagsCadastradas = JSON.parse(this.inputTags.value)
        
        while(i < tagsCadastradas.length){
            let tag = document.createElement('div')
            tag.classList.add('tag-cadastrada', 'bg-primaria')
            
            tag.innerHTML = `
                                <span>${tagsCadastradas[i]}</span>
                                <button class="excluir-tag btn p-0"><i class="bi bi-x-circle" style="color: white"></i></button>
                            `
            fragmento.appendChild(tag)
            i++
        }
        
        
        this.divTags.innerHTML = ''
        this.divTags.appendChild(fragmento)
        
        let botao = document.getElementById('exampleModalToggle').getElementsByClassName('btn-padrao')[0]
        if(tagsCadastradas.length <= 0){
            botao.setAttribute('disabled', true)
        }else{
            botao.removeAttribute('disabled')
        }
        
        evento(Array.from(this.divTags.getElementsByClassName('excluir-tag')), 'click', () => {
            this.excluirTag.bind(this)()
        })
    }
    
    excluirTag() {
        let elemento = event.currentTarget.closest('.tag-cadastrada')
        let tag = elemento.getElementsByTagName('span')[0].innerText
        let tagsCadastradas = JSON.parse(this.inputTags.value)
    
        let tagsAtualizadas = tagsCadastradas.filter(tags => tags.toLowerCase().trim() !== tag.toLowerCase().trim())
    
        this.inputTags.value = JSON.stringify(tagsAtualizadas)
        
        this.montaTags.bind(this)()
    }
    
    verificarChecks(){
        var check = event.currentTarget
        var li = check.closest('.list-group-item')
        
        var checkPrincipal = li.getElementsByClassName('regionais')[0]
        
        var checkado = true

        if(!check.checked){
            checkado = false
            
        }else{
            
            var i = 0
            
            var checks = li.getElementsByClassName('check-regionais')
            
            while(i < checks.length){
                if(!checks[i].checked){
                    checkado = false
                    break
                }
                
                i++
            }
        }
        
        
        if(checkado){
            checkPrincipal.checked = true
        }else{
            checkPrincipal.checked = false
        }
        
    }
    
    selecionarChecks(){
        var check = event.currentTarget
        var li = check.closest('.list-group-item')
        
        var checks = li.getElementsByClassName('check-regionais')
        
        var i = 0
        
        while(i < checks.length){
            if(check.checked){
                checks[i].checked = true
            }
            else{
                checks[i].checked = false
            }
            
            i++
        }
        
       
    }
    
    selecionarDados(){
        var evento = event.currentTarget
        evento.setAttribute('disabled', true)
        var texto = evento.innerHTML 
        evento.innerHTML = `
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        `
        
        
        // var grupo = []
        // var classes = []
        var regioes = []
        
        if(this.inputTags.value){
            var tags = this.inputTags.value
        }
        
        
        
        
        
        
        // var dado = this.select.value
        
        // if(dado != 0){
        //     grupo.push(parseInt(dado))
            
        //     var i = 0
        //     // var inputs = this.divClasses.getElementsByClassName('input-classe')
            
        //     while(i < inputs.length){
        //         if(inputs[i].checked){
        //             classes.push(parseInt(inputs[i].value))
        //         }
                
        //         i++
        //     }
            
        // }
    
        var i = 0
        
        while(i < this.checksRegionais.length){
            if(this.checksRegionais[i].checked){
                regioes.push(this.checksRegionais[i].value)
            }
            i++
        }
        
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        request.addData({
            acao: 'definirOportunidades',
            // grupos : JSON.stringify(grupo),
            // classes: JSON.stringify(classes),
            'tags': tags,
            regioes: JSON.stringify(regioes)
        })

        request.send().then((r) => {
            evento.removeAttribute('disabled', true)
            evento.innerHTML = texto
            this.modal.hide()
            this.modal2.hide()
            iziToast.success({
              message: "Oportunidades de Licitações Atualizadas!",
              timeout: 5000,
              position: 'bottomRight',
            })
            
            if(this.cb){
                this.cb()
            }
        }, (r)=>{
            evento.removeAttribute('disabled', true)
            evento.innerHTML = texto
            iziToast.error({
              message: "Erro ao consultar banco de dados, contate o suporte ou espere um pouco!",
              timeout: 5000,
              position: 'bottomRight',
            })
        })
        
    } 
     
    abrirModal(){
        this.classes = false
        var evento = event.currentTarget
        evento.setAttribute('disabled', true)
        var texto = evento.innerHTML 
        evento.innerHTML = `
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        `
        this.desmarcarTudo.bind(this)()
        let empresa = JSON.parse(pegaLocal('nown')).sub

        var api = new ApiNown('licitacoes', 'RUwWZKCKHA1F6Kw')
        api.isNew()
        // api.setHash(infoUser().user)
        api.setExtra(empresa.id)
        api.send().then((r)=>{
            evento.innerHTML = texto
            evento.removeAttribute('disabled', true)
            console.log(r.lista)
            if(r.lista.length > 0){
                var lista = r.lista.reverse()
                this.pegarMarcacoes.bind(this)(lista[0])
            }
            
            this.modal.show()
            this.inputTags.value = JSON.stringify(this.tagsCadastradas)
            this.montaTags.bind(this)()
            
        }, (r)=>{
            evento.innerHTML = texto
            evento.removeAttribute('disabled', true)
            iziToast.error({
               message: "Erro ao procurar Oportunidades, contate o suporte ou espere um pouco!",
               timeout: 5000,
               position: 'bottomRight',
            })
        })
    }
    
    desmarcarTudo(){
        // this.select.value = 0
        this.pegarClasses.bind(this)()
        
        var j = 0
        
        while(j < this.checksRegionais.length){
            

            this.checksRegionais[j].checked = false

            
            j++
        }
           
    }
    
    pegarMarcacoes(dados){
        if(dados['tagmento']){
            this.tagsCadastradas = JSON.parse(dados['tagmento'])
        }
        
        let header = document.getElementById('exampleModalToggle').getElementsByClassName('modal-header')[0]
        
        if(header.getElementsByClassName('btn-close').length > 0){
            header.getElementsByClassName('btn-close')[0].remove()
        }
        if(this.tagsCadastradas.length > 0){
            header.innerHTML += `
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            `
        }
        
        if(dados['regioes']){
            var i = 0
 
            var regioes = JSON.parse(dados['regioes']) && JSON.parse(dados['regioes']) ? JSON.parse(dados['regioes']) : 0
            
            while(i < regioes.length){
                var j = 0
                
                while(j < this.checksRegionais.length){
                    
                    if(regioes[i] == this.checksRegionais[j].value){
                        this.checksRegionais[j].checked = true
                        break
                    }
                    
                    j++
                }
                i++
            }
        }
        
        var j = 0
    
        while(j < this.regioes.length){
            var li = this.regioes[j].closest('.list-group-item')
            
            var checkado = true
            
            var i = 0
            
            var checks = li.getElementsByClassName('check-regionais')
            
            while(i < checks.length){
                if(!checks[i].checked){
                    checkado = false
                    break
                }
                
                i++
            }
            
            if(checkado){
                this.regioes[j].checked = true
            }else{
                this.regioes[j].checked = false
            }
            
            j++
        }
    }
    
    preencherSelect(lista){
        lista.sort((a, b) => {
          return a.nome.localeCompare(b.nome) // Ordena alfabeticamente
        })
        
        
        var i = 0
        var fragmento = document.createDocumentFragment()
        
        var option = document.createElement('option')
        option.value = 0
        option.innerText = 'Selecione um segmento'
        fragmento.appendChild(option)
        
        while(i < lista.length){
            var option = document.createElement('option')
            option.value = lista[i].id
            option.innerText = lista[i].nome
            fragmento.appendChild(option)
            i++
        }
        
        // this.select.innerHTML = ''
        // this.select.appendChild(fragmento)
        
        // evento(this.select, 'change', this.pegarClasses.bind(this))
    }
    
    pegarClasses(){
        // this.divClasses.innerHTML = ''
        
        // if(this.select.value != 0){
        //     // this.divClasses.innerHTML = this.bgCarregandoClasse
        //     var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        
        //     request.addData({
        //         acao: 'pegarClassesPorGrupo',
        //         id : this.select.value
        //     })
            
        //     request.send().then((r) => {
        //         this.carregarClasses.bind(this)(r.lista)
        //     })
        // }
    }
    
    carregarClasses(lista){
        // this.divClasses.innerHTML = ''
        
        var i = 0
        
        while(i < lista.length){
            // this.divClasses.appendChild(this.criarClasse.bind(this)(lista[i]))
            i++
        }
    }
    
    criarClasse(item){
        var checked = '' 
        if(this.classes){
            var i = 0
            
            while(i < this.classes.length){
                if(item.id == this.classes[i]){
                    checked = `checked='true'`
                    break
                }
                i++
            }
        }
        
        
        var div = document.createElement('div')
        div.classList.add('d-flex', 'align-items-center', 'gap-2', 'mb-2')
        div.innerHTML = `
            <input class="input-classe" ${checked} type="checkbox" value="${item.id}" id="classe-${item.codigo}">
            <label for="classe-${item.codigo}">${item.nome}</label>
        `
        return div
    }
}

function chamarOportunidades(){
    
    var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
    
    request.addData({
        acao: 'pegarOportunidades',
        tipo: 1
    })
    
    request.send().then((r) => {
        if (r.licitacoes) {
            let texto = `${r.total} ${r.total > 1 ? 'novas Licitações' : 'nova Licitação'}`
            document.getElementsByClassName('total-licitacoes')[0].innerHTML = texto
        }
    }).catch((e) => {
        let texto = 'Nenhuma Licitação encontrada!'
        document.getElementsByClassName('total-licitacoes')[0].innerHTML = texto
    })
}