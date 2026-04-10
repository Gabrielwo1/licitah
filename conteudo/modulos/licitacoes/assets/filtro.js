class FiltroLicitacoes{
    constructor(){
        this.paginacao = 5
        this.init.bind(this)()
        this.div = document.getElementById('licitacoes-pesquisada')
        this.bgCarregando = this.div.innerHTML 
        evento(document.getElementById('pesquisar'), 'click', this.pesquisando.bind(this))
        new Mascaras()
        
        
    }
    
    async init(){
        await this.definirEstado.bind(this)()
        await this.definirMOdalidade.bind(this)()
        await this.definirEsfera.bind(this)()
        // await this.definirSituacao.bind(this)()
        await this.definirConcorrencia.bind(this)()
        await this.pegarClasses.bind(this)()
        
        this.pesquisando.bind(this)()
    }
    
    definirEstado(){
        var select = document.getElementById('estado')
        
        var request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/api.php`)
        
        request.addData({
            acao : 'estados',
            valor: 31
        })
        
        request.send().then((r)=>{
            var lista = r.lista
            var i = 0
            select.innerHTML = ''
            
            select.appendChild(this.criarOption.bind(this)({
                t: 'Selecione um opção',
                v: 0
            }))
            
            while(i < lista.length){
                select.appendChild(this.criarOption.bind(this)(lista[i]))
                i++
            }
            
            evento(select, 'change', this.definirCidade.bind(this))
        })
    }
    
    definirCidade(){
        var valor = event.currentTarget.value
        
        var select = document.getElementById('cidade')
        
        var request = new Request(`${dominio}/conteudo/modulos/enderecos/admins/api.php`)
        request.addData({
            acao : 'cidades',
            'valor' : valor
        })
        
        if(valor != 0){
            select.removeAttribute('disabled', true)
            request.send().then((r)=>{
                var lista = r.lista
                var i = 0
                select.innerHTML = ''
                select.appendChild(this.criarOption.bind(this)({
                    t: 'Selecione um opção',
                    v: 0
                }))
                while(i < lista.length){
                    select.appendChild(this.criarOption.bind(this)(lista[i]))
                    i++
                }

            })
        }else{
            select.innerHTML = ''
            select.appendChild(this.criarOption.bind(this)({
                t: 'Defina o estado',
                v: 0
            }))
            select.setAttribute('disabled', true)
        }
    }
    
    criarOption(info){
        var option = document.createElement('option')
        option.innerText = info.t
        option.setAttribute('value' , info.v)
        return option
    }
    
    definirMOdalidade(){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        
        var select = document.getElementById('modalidades')
        
        request.addData({
            acao : 'pegarModalidades'
        })
        
        request.send().then((r)=>{
            var lista = r.lista
            var i = 0
            select.innerHTML = ''
   
            select.appendChild(this.criarOption.bind(this)({
                t: 'Selecione um opção',
                v: 0
            }))
            
            while(i < lista.length){
                select.appendChild(this.criarOption.bind(this)({
                    't': lista[i],
                    'v': lista[i],
                }))
                i++
            }
        })
    }
    
    definirEsfera(){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        
        var select = document.getElementById('esferas')
        
        request.addData({
            acao : 'pegarEsferas'
        })
        
        request.send().then((r)=>{
            var lista = r.lista
            var i = 0
            select.innerHTML = ''
   
            select.appendChild(this.criarOption.bind(this)({
                t: 'Selecione um opção',
                v: 0
            }))
            
            while(i < lista.length){
                var nome = this.pegarNome.bind(this)(lista[i])
                select.appendChild(this.criarOption.bind(this)({
                    't': nome,
                    'v': lista[i],
                }))
                i++
            }
        })
    }
    
    pegarNome(inicial){
        switch(inicial.toLowerCase()){
            case 'f':
                return 'Federal';
            case 'e':
                return 'Estadual';
            case 'm':
                return 'Municipal';
            case 'd':
                return 'Distrital';
            default:
                return 'Não Informado'
        }
    }
    
    // definirSituacao(){
    //     var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        
    //     var select = document.getElementById('situacao')
        
    //     request.addData({
    //         acao : 'pegarSituacao' 
    //     })
        
    //     request.send().then((r)=>{
    //         var lista = r.lista
    //         var i = 0
    //         select.innerHTML = ''
   
    //         select.appendChild(this.criarOption.bind(this)({
    //             t: 'Selecione um opção',
    //             v: 0
    //         }))
            
    //         while(i < lista.length){
    //             select.appendChild(this.criarOption.bind(this)({
    //                 't': lista[i],
    //                 'v': lista[i],
    //             }))
    //             i++
    //         }
    //     })
    // }
    
    definirInput(info, number){
        var input = document.createElement('div')
        input.classList.add('form-check')
        var id = info
        var nome = info
        if(info.id){
            id = info.id
        }
        
        if(info.nome){
            nome = info.nome
        }
        
        input.innerHTML = `
                <input class="form-check-input" type="checkbox" value="${id}" name="${id}" id="i-${number}">
                    <label class="form-check-label" for="i-${number}">
                        ${nome}
                    </label>
        `
       return input
    }
    
    definirConcorrencia(){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        
        var divInputs = document.getElementById('concorencias')
        
        request.addData({
            acao : 'pegarConcorrencia'
        })
        
        request.send().then((r)=>{
            var lista = r.lista
            var i = 0
            divInputs.innerHTML = ''
   
        
            while(i < lista.length){
                divInputs.appendChild(this.definirInput.bind(this)(lista[i], i))
                i++
            }
        })
    }
    
    definirClasses(classes){
        var i = 0
        
        var divInputs = document.getElementById('oportunidades')
        divInputs.innerHTML = ''
        if(classes.length > 0){
            while(i < classes.length){
                 divInputs.appendChild(this.definirInput.bind(this)(classes[i], `l-info-${i}`))
                i++
            }
        }else{
            divInputs.innerHTML = this.botaoDefinirClasses
            new ModalOportunidade(this.pegarClasses.bind(this))
        }
        
    }
    
    pegarClasses(){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        this.botaoDefinirClasses = `<button class="btn btn-terciario" data-bs-target="#exampleModalToggle" id="modal-de-oportunidades">Definir Oportunidades</button>`
        request.addData({
            acao : 'pegarMinhasOportunidades'
        })
        
        request.send().then((r)=>{
            if(r.item){
                var tags = JSON.parse(r.item.tags)
                this.definirClasses.bind(this)(tags)
            }else{
                document.getElementById('oportunidades').innerHTML = this.botaoDefinirClasses 
                
                new ModalOportunidade(this.pegarClasses.bind(this))
            }
        })
        
    }
    
    pesquisando() {
        this.data = {};
    
        const fields = [
            { id: 'objeto', key: 'objeto', trim: true },
            { id: 'editalNumber', key: 'edital', trim: true },
            { id: 'data-inclusao', key: 'data_abertura_min', trim: true },
            { id: 'data-prazo', key: 'data_abertura_max', trim: true },
            { id: 'id-gov', key: 'id_gov', trim: true },
            { id: 'cod-orgao', key: 'cod_orgao', trim: true },
            { id: 'num-processo', key: 'num_processo', trim: true },
            { id: 'orgao-nome', key: 'orgao_nome', trim: true },
            { id: 'item-nome', key: 'item_nome', trim: true }
        ];
    
        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (element && element.value.trim().length > 0) {
                this.data[field.key] = field.trim ? element.value.trim() : element.value;
            }
        });
    
        const conditions = [
            { id: 'busca-exata', key: 'busca_exata', type: 'checked' },
            { id: 'estado', key: 'uf', type: 'value' },
            { id: 'cidade', key: 'cidade', type: 'value' },
            { id: 'modalidades', key: 'modalidade', type: 'value' },
            { id: 'esferas', key: 'esfera', type: 'value' },
            { id: 'situacao', key: 'situacao', type: 'value' },
            { id: 'concorencias', key: 'concorencias', type: 'checked', isClass: true },
            { id: 'oportunidades', key: 'oportunidades', type: 'checked', isClass: true }
        ];
    
        conditions.forEach(condition => {
            if (condition.type === 'checked') {
                if (condition.isClass) { // Para checkboxes de classe
                    const checkedValues = [];
                    document.getElementById(condition.id).querySelectorAll(`.form-check-input:checked`).forEach(element => {
                        checkedValues.push(element.value);
                    });
                    if (checkedValues.length > 0) {
                        this.data[condition.key] = JSON.stringify(checkedValues);
                    }
                } else {
                    const element = document.getElementById(condition.id);
                    if (element && element.checked) {
                        this.data[condition.key] = true;
                    }
                }
            } else if (condition.type === 'value') {
                const element = document.getElementById(condition.id);
                if (element && element.value != 0) {
                    this.data[condition.key] = element.value;
                }
            } else if (condition.type === 'radio') {
                const selectedRadio = document.querySelector(`input[name="${condition.id}"]:checked`);
                if (selectedRadio) {
                    this.data[condition.key] = selectedRadio.value;
                }
            }
        });
        
        this.puxarInfos.bind(this)()
    }
    
    async montaDados(lista){
        var i = 0
        console.log(lista)
        var fragmento = document.createDocumentFragment()
        while(i < lista.length){
            fragmento.appendChild(await new CardPadrao2(lista[i]))
            i++
        }
        
        this.div.innerHTML = ''
        
                
        if(this.pagination){
            paginacao.appendChild(this.pagination.paginacao)
        }
        
        this.div.appendChild(fragmento)
        
        if(this.pagination){
            this.pagination.verificarBotoes('paginacao')
        }
        
        new Favoritos()
    }
    
    puxarInfos(pagina = 1){
        this.paginaAtual = parseInt(pagina)
        this.div.innerHTML = this.bgCarregando
        var paginacao = document.getElementById('paginacao')
        paginacao.innerHTML = ''
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        this.data['acao'] = 'filtrar'
        this.data['pagina'] = this.paginaAtual
        this.data['paginacao'] = this.paginacao
        request.addData(this.data)
        request.send().then((r)=>{
            if(r.licitacoes.length > 0){
                this.totalPaginas = Math.ceil(r.total / this.paginacao)
                this.pagination = false
                if(this.totalPaginas > 1){
                    this.pagination = new CriaPaginacao(this, this.puxarInfos.bind(this))
                }
                
                
                this.montaDados.bind(this)(r.licitacoes)
            }
            else{
               this.div.innerHTML = `<div class="card card-nown he-150 d-flex justify-content-center align-items-center"><h2 class="text-center">Nenhuma Licitação Encontrada</h2></div>` 
               
               if(document.getElementById('sessao-topo')){
                     document.getElementById('sessao-topo').scrollIntoView({
                         behavior: 'smooth'
                     });
                }
            }
        }, (r)=>{
            this.div.innerHTML = `<div class="card card-nown he-150 d-flex justify-content-center align-items-center"><h2 class="text-center">Nenhuma Licitação Encontrada</h2></div>` 
            
            if(document.getElementById('sessao-topo')){
                 document.getElementById('sessao-topo').scrollIntoView({
                     behavior: 'smooth'
                 });
            }
        })
    }
    
    
    
}