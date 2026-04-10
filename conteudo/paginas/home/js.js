function transformarData(informacao, horaP = true){
    let dados= informacao.trim().split(' ')
        
    let data = dados[0] ?? false
    let hora = dados[1] ?? false
    
    if(data){
        let [ano, mes, dia] = data.split("-")
        data = `${dia}/${mes}/${ano}`
    }else{
        data = ''
    }
    
    if(hora && horaP){
       let [horas, minutos, segundos] = hora.split(":")
       hora = ` ${horas}:${minutos}`
    }else{
        hora = ''
    }
    
    let resposta =  `${data}${hora}`
    
    return resposta.trim()
}

function dataFormatada(dataF){
    var data = dataF.split(' ')
    var dataD = data[0].split('/')
    var horaD = data[1] ? data[1].split(':') : [0, 0, 0] // Se não houver horas, assume 00:00:00
    
    var dia = dataD[0]
    var mes = dataD[1]
    var ano = dataD[2]
    
    var horas = horaD[0] ?? 0
    var minutos = horaD[1] ?? 0
    var segundos = horaD[2] ?? 0
    
    // Criando a data com dia, mês, ano, horas, minutos e segundos
    var dataFormatada = new Date(ano, mes - 1, dia, horas, minutos, segundos)
    
    return dataFormatada
}

function filtrarPorData(dados) {
    const hoje = new Date(); // Data atual
    const inicio = new Date(hoje.getFullYear(), hoje.getMonth(), hoje.getDate() - 1); // Um dia antes
    const fim = new Date(hoje.getFullYear(), hoje.getMonth(), hoje.getDate()); // Data de hoje
    
    return dados.filter((item) => {
        const dataItem = new Date(item.dataCriacao.split(" ")[0]); // Ignora as horas
        return dataItem >= inicio && dataItem <= fim;
    });
}

class MontaSubtarefaHome{
    constructor(subtarefa = false, tarefa = false){
        this.tarefa = tarefa
        this.subtarefa = subtarefa
        this.pai = this.tarefa.pai
        
        return this.criar.bind(this)()
    }
    
    criar(){
        this.subtarefaLinha = document.createElement('div')
        this.subtarefaLinha.classList.add('subtarefa')
        
        var nome = this.subtarefa['a7eLkVHjVBZrS0B9FG1NX7egKDzOWM'] && this.subtarefa['a7eLkVHjVBZrS0B9FG1NX7egKDzOWM'] !== '' ? this.subtarefa['a7eLkVHjVBZrS0B9FG1NX7egKDzOWM'] : ''
        var data = this.subtarefa['Bjy1xeEockAS28Vr2qfBFlIBIcC5Z0'] && this.subtarefa['Bjy1xeEockAS28Vr2qfBFlIBIcC5Z0'] !== '' ? this.subtarefa['Bjy1xeEockAS28Vr2qfBFlIBIcC5Z0'] : ''
        var check = this.subtarefa['4rskpGQrbviFfYmZK6gf4Ur6ZbhHmQ'] && this.subtarefa['4rskpGQrbviFfYmZK6gf4Ur6ZbhHmQ'] !== 0 ? this.subtarefa['4rskpGQrbviFfYmZK6gf4Ur6ZbhHmQ'] : 0
        data = transformarData(data)
        
        if(parseInt(check)){
            var checkado = 'checked'
        }
        else{
            var checkado = ''
        }
        this.subtarefaLinha.innerHTML = `
                                <input class="form-check-input" ${checkado} name="skip" type="checkbox" value="" id="flexCheckDefault" disabled=true>
                                    <div class="nome-subtarefa">
                                        <div class="w-100">
                                            <input type="text" value="${nome}" disabled=true name="skip" class="form-control rounded-0 bg-transparent" style="border: 0px !important">
                                        </div>
                                        <div class="wi-220">
                                            <input type="" data-mascara="9" value="${data}" disabled=true name="skip" class="form-control rounded-0 bg-transparent mascaraInput" style="border: 0px !important">
                                        </div>
                                    </div>
                                    <button class="btn btn-trash disabled"><i class="bi bi-trash-fill"></i></button>
                              `
        var inputData = [this.subtarefaLinha.getElementsByTagName('input')[2]]
        var mascara = new Mascaras(inputData)
        
   
        evento(this.subtarefaLinha.getElementsByClassName('btn-trash')[0], 'click', this.excluirLinha.bind(this))
        return this.subtarefaLinha
    }
    
    excluirLinha(){
        this.subtarefaLinha.remove()
    }
}

class CarregaLicitacoes{
    constructor(lista){
        this.pesquisa = lista
        
        this.montagem.bind(this)()
    }
    
    montagem(){
        this.fragmento = document.createDocumentFragment()
        this.div = document.getElementById('sliderLicitacoes')
        
        var i = 0
        if(this.pesquisa){
            while ( i < this.pesquisa.length){
                this.buscaLicitacao.bind(this)(this.pesquisa[i])
                i++
            }
            
            if(document.getElementById('btnFiltro')){
                document.getElementById('btnFiltro').classList.add('d-none')
            }
            
            this.div.innerHTML = ''
            this.div.appendChild(this.fragmento)
        }else{
            if(document.getElementById('btnFiltro')){
                document.getElementById('btnFiltro').classList.remove('d-none')
            }
            this.div.innerHTML = `  
                                    <div class="d-flex justify-content-center w-100 he-200 align-items-center"> 
                                        <span class="fs-28 fw-700 text-center">Nenhuma Licitação foi encontrada.</span>
                                    </div>
                                 `
        }
    }
    
    buscaLicitacao(item){
        var tarefaMenorPrazo 
        var menorPrazo = false

        if(item.tarefas.length > 0){
            var i = 0
            while(i < item.tarefas.length){
                let dataCriacao = item.tarefas[i].data ? item.tarefas[i].data : false
                let data = item.tarefas[i].prazo ? transformarData(item.tarefas[i].prazo) : false
                let prioridade = item.tarefas[i].prioridade ? item.tarefas[i].prioridade : false
                let tipo, porcentagem, cor
                
                dataCriacao = new Date(dataCriacao)

                let dataPrazo = dataFormatada(data)
                
                let dataAtual = new Date()
                
                const tempoTotal = dataPrazo - dataCriacao
                
                const tempoRelat = dataAtual - dataCriacao
                
                let tempoResta = dataPrazo - dataAtual
                
                tempoResta = (((tempoResta / 1000) / 60) / 60)

                if(tempoResta < menorPrazo || !menorPrazo){
                    menorPrazo = tempoResta
                    
                    switch (true) {
                        case (tempoResta <= 0):
                            tempoResta = `<div class="restam"><span class="time">Atrasado</span></div>`
                            break
                        
                        case (tempoResta < 1):
                            var minutos = Math.ceil((tempoResta * 60))
                            tempoResta = `${minutos} Minuto(s)`
                            tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                            break
                    
                        case (tempoResta < 24):
                            tempoResta = `${parseInt(tempoResta)} Hora(s)`
                            tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                            break
                        
                        default:
                            var dia = parseInt(tempoResta / 24)
                            tempoResta = `${dia} Dia(s)`
                            tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                            break
                    }
                
                    porcentagem = parseInt((tempoRelat / tempoTotal) * 100)

                    if(porcentagem >= 100 || porcentagem < 0){
                        porcentagem = 100
                    }
                
                    switch(prioridade){
                        case 'Baixa':
                            tipo = 'baixa'
                            break
                        case 'Média':
                            tipo = 'media'
                            break
                        case 'Alta':
                            tipo = 'alta'
                            break
                        case 'Urgente':
                            tipo = 'urgente'
                            break
                    }
                
                    switch(true){
                        case (porcentagem < 25):
                            cor = 'baixa'
                            break
                        case (porcentagem < 50):
                            cor = 'media'
                            break
                        case (porcentagem < 75):
                            cor = 'alta'
                            break
                        default:
                            cor = 'urgente'
                            break
                    }
                    
                    if(!parseInt(item.tarefas[i].andamento)){
                        tarefaMenorPrazo = {
                            nome: item.tarefas[i].nome,
                            tempoRestante: tempoResta,
                            tipo: tipo,
                            cor: cor,
                            prioridade: prioridade,
                            porcentagem: porcentagem
                        }
                    }
                }
                i++
            }
        }
        
        this.fragmento.appendChild(this.montaCard.bind(this)(item.licitacao, tarefaMenorPrazo, item.url))  
    }
    
    montaCard(licitacao, tarefa, url){
        var card = document.createElement('div')
        card.classList.add('licitacao', 'position-relative')
        
        if(tarefa){
            var tarefaMenor = `
                                <div class="border-0 border-bottom border-1 w-100 mb-2"></div>
                                <div class="notificacao ${tarefa.tipo}">${tarefa.prioridade}</div>
            
                                <div class="progresso">
                                    <div class="labels"><span class="nome">${tarefa.nome}</span>${tarefa.tempoRestante}</div>
                                    <div class="progress progress-barra ${tarefa.cor}" role="progressbar" aria-label="Basic example" aria-valuenow="${tarefa.porcentagem}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar" style="width: ${tarefa.porcentagem}%"></div>
                                    </div>
                                </div>
                              `
        }else{
            var tarefaMenor = ` 
                                <div class="border-0 border-bottom border-1 w-100 mb-2"></div>
                                <div class="content fs-16 fw-600"><span>Nenhuma tarefa em andamento para esta Licitação</span></div>
                              `
        }
        
        let objeto      = licitacao.objeto && licitacao.objeto.length > 0 ? licitacao.objeto : 'Não informado'
        let modalidade  = licitacao.modalidadeNome && licitacao.modalidadeNome.length > 0 ? licitacao.modalidadeNome : 'Não informado'
        let licit       = licitacao.numeroCompra && licitacao.numeroCompra.length > 0 ? licitacao.numeroCompra : 'Não informado'
        let orgao       = licitacao.Orgao_Vinculado_Nome && licitacao.Orgao_Vinculado_Nome.trim() != '' ? licitacao.Orgao_Vinculado_Nome : ''
        let dataC       = licitacao.dataAberturaPropostaPncp && licitacao.dataAberturaPropostaPncp.length > 0 ? transformarData(licitacao.dataAberturaPropostaPncp.replace(/T/g, ' ')) : 'Não informado'

        if(objeto.includes('Objeto:')){
            objeto = objeto.replace('Objeto: ', '')
        }
        
        if(licitacao.data_abertura_proposta && licitacao.data_abertura_proposta.length > 0){
            dataC = dataC.split(" ")[0] //separa o dia da hora
            let [ano, mes, dia] = dataC.split("-") //separa por - e guarda nas variaveis
            dataC = `${dia}/${mes}/${ano}` //escreve a data formatada
        }
        
        card.innerHTML = `
                            <div class="content objeto"><span>Objeto:</span><p>${objeto}</p></div>
                            <div class="content edital"><span>Nº Licitação:</span><p>${licit}</p></div>
                            <div class="content edital"><span>Modalidade:</span><p>${modalidade}</p></div>
                            <div class="content data"><span class="align-self-center">Data de Abertura:</span><p class="m-0">${dataC}</p></div>
                            ${tarefaMenor}
                            <a class="stretched-link" data-url="licitacoes/${url}" href="${dominio}/licitacoes/${url}">
                         `
                         
        evento(card.getElementsByClassName('stretched-link')[0], 'click', this.redireciona.bind(this))
        
        return card
    }
    
    redireciona(){
        event.preventDefault()
        var botao = event.currentTarget
        var link = botao.dataset.url
        
        goUrl(link)
    }
}

class CarregaTarefas{
    constructor(lista){
        this.modalTarefas =  new bootstrap.Modal('#modalTarefas', {
          keyboard: false
        })
        
        this.selectUsuarios.bind(this)()
        
        this.fragmento = document.createDocumentFragment()
        
        this.div = document.getElementById('tabelaTarefas')

        this.lista = lista
        this.listaTarefas.bind(this)()
    }
    
    listaTarefas(){
        var i = 0 
        
        var tarefas = false
        
        if(this.lista){
            
            while (i < this.lista.length) {
                if (this.lista[i].tarefas.length > 0) {
                    tarefas = true
                    var j = 0
                    while (j < this.lista[i].tarefas.length) {
                        if(!parseInt(this.lista[i].tarefas[j].andamento)){
                            this.fragmento.appendChild(this.montaTarefa.bind(this)(this.lista[i].licitacao, this.lista[i].tarefas[j]))
                        }
                        j++
                    }
                }
                i++
            }
            
            this.div.innerHTML = ''
            if(tarefas){
                this.div.appendChild(this.fragmento)
            }
            
        }
        
        
        if(!tarefas){
            this.div.innerHTML = `
               <tr>
                   <td colspan="5" class="text-center">
                       <div class="d-flex justify-content-center align-items-center w-100">
                           <span class="fs-18 fw-700">Nenhuma Tarefa está disponível no momento.</span>
                       </div>
                   </td>
               </tr>
            `
        }
    }
    
    montaTarefa(licitacao, tarefa){
        
        var tr = document.createElement('tr')
        let nome = tarefa.nome ? tarefa.nome : ''
        let nrlicitacao = licitacao.Licitacao_Numero ? licitacao.Licitacao_Numero : ''
        let data = tarefa.prazo ? transformarData(tarefa.prazo) : false
        let prioridade = tarefa.prioridade ? tarefa.prioridade : false
        let dataCriacao = tarefa.data ? tarefa.data : false
        let tipo, porcentagem, cor

        dataCriacao = new Date(dataCriacao)

        let dataPrazo = dataFormatada(data)
        
        let dataAtual = new Date()
        
        const tempoTotal = dataPrazo - dataCriacao
        
        const tempoRelat = dataAtual - dataCriacao
        
        let tempoResta = dataPrazo - dataAtual
        
        tempoResta = (((tempoResta / 1000) / 60) / 60)
        
        switch (true) {
            case (tempoResta <= 0):
                tempoResta = `<div class="restam"><span class="time">Atrasado</span></div>`
                break
            
            case (tempoResta < 1):
                var minutos = Math.ceil((tempoResta * 60))
                tempoResta = `${minutos} Minuto(s)`
                tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                break
        
            case (tempoResta < 24):
                tempoResta = `${parseInt(tempoResta)} Hora(s)`
                tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                break
            
            default:
                var dia = parseInt(tempoResta / 24)
                tempoResta = `${dia} Dia(s)`
                tempoResta = `<div class="restam">Restam <span class="time">${tempoResta}</span></div>`
                break
        }
        
        porcentagem = parseInt((tempoRelat / tempoTotal) * 100)
        
        if(porcentagem >= 100 || porcentagem < 0){
            porcentagem = 100
        }
        
        switch(prioridade){
            case 'Baixa':
                tipo = 'baixa'
                break
            case 'Média':
                tipo = 'media'
                break
            case 'Alta':
                tipo = 'alta'
                break
            case 'Urgente':
                tipo = 'urgente'
                break
        }
        
        switch(true){
            case (porcentagem < 25):
                cor = 'baixa'
                break
            case (porcentagem < 50):
                cor = 'media'
                break
            case (porcentagem < 75):
                cor = 'alta'
                break
            default:
                cor = 'urgente'
                break
        }
        
        if(parseInt(tarefa.andamento)){
            prioridade = 'Concluida'
            tipo = 'concluida'
            cor = 'concluida'
            porcentagem = 100
            tempoResta = `<div class="restam"><span class="time">Concluído</span></div>`
        }
        
        tr.innerHTML = `
                        <th scope="row"><button type="button" class="eye btn botaoTarefa" ><i class="bi bi-eye"></i></button></th>
                        <td><span class="nome">${nome}</span></td>
                        <td>
                            <div class="progresso">
                                <div class="labels"><span class="data">${data}</span>${tempoResta}</div>
                                <div class="progress progress-barra ${cor}" role="progressbar" aria-label="Basic example" aria-valuenow="${porcentagem}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: ${porcentagem}%"></div>
                                </div>
                            </div>
                        </td>
                        <td><div class="notificacao w-100 ${tipo}">${prioridade}</div></td>
                      `
                       
        if(!parseInt(tarefa.andamento)){
            tarefa.prioridade = prioridade
        }
        
        evento(tr.getElementsByClassName('botaoTarefa')[0], 'click', this.passaDados.bind(this, licitacao, tarefa))
        
        return tr
    }
    
    passaDados(licitacao, tarefa){
        var modal = document.getElementById('modalTarefas')
        document.getElementById('staticBackdropLabel').innerHTML = `Tarefa: <span class="nome-formulario">${tarefa.nome}</span>`
        var inputs = modal.getElementsByClassName('form-control')
        var selects = modal.getElementsByClassName('form-select')
        
        modal.getElementsByClassName('nome-subtarefa')[0].value = ''
        modal.getElementsByClassName('data-subtarefa')[0].value = ''
        
        var modalTopo = modal.getElementsByClassName('modal-topo')[0].getElementsByTagName('p')
        
        var i = 0
        while(i < inputs.length){
            var nome = inputs[i].name
            if (tarefa.hasOwnProperty(nome)) {
                if(nome == 'prazo'){
                    inputs[i].value = transformarData(tarefa[nome])
                } else{
                    inputs[i].value = tarefa[nome]
                }
                
                inputs[i].disabled = true
            }
            i++
        }
        
        i = 0
        while(i < selects.length){
            var nome = selects[i].name
            if (tarefa.hasOwnProperty(nome)) {
                selects[i].value = tarefa[nome]
            }
            
            selects[i].disabled = true
            i++
        }
        
        console.log(licitacao)
        
        
        i = 0
        while(i < modalTopo.length){
            switch(i){
                case 0:
                    modalTopo[i].innerText = licitacao.objeto.replace('Objeto: ', '')
                    break
                case 1:
                    modalTopo[i].innerText = licitacao.processo
                    break
                case 2:
                    modalTopo[i].innerText = licitacao.unidadeOrgaoNomeUnidade
                    break
            }
            i++
        }
        
        var selectUsuarios = document.getElementById('select-usuario')

        var event = new Event('change')

        selectUsuarios.dispatchEvent(event)
        
        var div = document.getElementById('subtarefasW')
        
        div.innerHTML = ``
        
        var subtarefas = JSON.parse(tarefa.subtarefas)
        i = 0
        while(i < subtarefas.length){
            div.appendChild(new MontaSubtarefaHome(subtarefas[i], tarefa))
            i++
        }
        
    
        this.modalTarefas.show()   
    }
    
    selectUsuarios(){
        evento(document.getElementById('select-usuario'), 'change', this.checaUsuarios.bind(this))   
    }
    
    checaUsuarios(){
        var valor = event.target.value
        var div = document.getElementsByClassName('input-usuarios')[0]
        var div2 = document.getElementsByClassName('select-usuarios')[0]
        
        if(parseInt(valor)){
            div2.classList.remove('d-none')
            div.classList.add('d-none')
        }else{
            div2.classList.add('d-none')
            div.classList.remove('d-none') 
        }
    }
}

class CarregaLogs{
    constructor(){
        this.div = document.getElementById('notificacoesWrap')
        this.fragmento = document.createDocumentFragment()
        
        this.api = new ApiNown('licitacoes', 'miJWYkjpgwzxadM')
        this.api.isNew()
        
        this.init.bind(this)()
    }
    
    init(){
        this.api.send().then( (r) => {
            this.lista = r.lista

            if(this.lista.length > 0){
                this.percorreLista.bind(this)()
            }else{
                this.div.innerHTML = `<div class="notificacao-2 justify-content-center">
                                            <div class="left">
                                                <div class="tarefa-wrap">
                                                    <p>Nenhuma notificação no momento.</a></p>
                                                </div>
                                            </div>
                                        </div>
                                     `
            }
        })
    }
    
    percorreLista(){
        let i = 0

        while(i < this.lista.length){
            this.fragmento.appendChild(this.montaCard.bind(this)(this.lista[i]))
            i++
        }
        
        this.div.innerHTML = ''
        this.div.appendChild(this.fragmento)
    }
    
    montaCard(dado){
        let card = document.createElement('div')
        card.classList.add('notificacao-2')
    
        let acao = __getDados(dado, 'acao')
        let acaoAlvo = __getDados(dado, 'acao_alvo')
        let licitacao = __getDados(dado, 'pesquisa').pesquisa

        let url = __getDados(dado, 'pesquisa').url
        licitacao = licitacao == '' ? url : licitacao
        
        let licitacaoText = `<a class="text-decoration-none" style="color: inherit" href="${dominio+'/licitacoes/'}${url ? url : ''}">Licitação: ${licitacao ? licitacao : ''}</a>`
    
        let tipoText
        switch (acaoAlvo) {
            case 2:
                tipoText = 'Tarefa'
                break
            case 3:
                tipoText = 'Anexo'
                break
            case 4:
                tipoText = 'Habilitação'
                break
            case 5:
                tipoText = 'Anotação'
                break
            default:
                tipoText = 'Licitação'
                break
        }
    
        let acaoText, acaoText2, icone
        switch (acao) {
            case 2:
                if (tipoText === 'Habilitação') {
                    acaoText = 'vinculada'
                    acaoText2 = 'vinculou' 
                } else {
                    acaoText = tipoText === 'Anexo' ? 'editado' : 'editada'
                    acaoText2 = 'editou'
                }
                icone = `<i class="bi bi-pencil-square"></i>`
                break
            case 3:
                acaoText = tipoText === 'Anexo' ? 'excluido' : 'excluida'
                acaoText2 = 'excluiu'
                icone = `<i class="bi bi-trash"></i>`
                break
            default:
                if (tipoText === 'Licitação') {
                    acaoText = 'vinculada'
                    acaoText2 = 'vinculou'
                } else {
                    acaoText = tipoText === 'Anexo' ? 'criado' : 'criada'
                    acaoText2 = 'criou'
                }
                icone = `<i class="bi bi-plus-circle"></i>`
                break
        }
    
        let artigo = tipoText === 'Anexo' ? 'um' : 'uma'
 
        let pessoa = dado.autor && infoUser().nome == dado.autor.display ? 'Você' : infoUser().nome
    
        card.innerHTML = `
            <div class="left">
                ${icone}
                <div class="tarefa-wrap">
                    <p class="m-0">${pessoa} ${acaoText2} ${artigo} ${tipoText.toLowerCase()} na ${licitacaoText}</p>
                </div>
            </div>
        `

        evento(card.getElementsByTagName('a')[0], 'click', ()=>{
            event.preventDefault()
            goUrl(`${dominio+'/licitacoes/'}${url ? url : ''}`)
        })
    
        return card
    }

}

function paginaHome(){
    nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/licitacoes.css`, `${dominioscript}/conteudo/modulos/licitacoes/assets/publico.js`]).then(() => {
        // new CarregaLicitacoes()
        // new CarregaTarefas()
        
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        request.addData({
            acao: 'listagemlicitacoesAutor'
        })
        request.send().then((r) => {
            new CarregaLicitacoes(r.lista)
            new CarregaTarefas(r.lista)
        })
        
        new CarregaLogs()
        chamarOportunidades()
        
    })
    nownFiles.add([`${dominioscript}/assets/aplicativo/select2/min.js`, 
    `${dominioscript}/assets/aplicativo/select2/min.css`]).then(()=>{
        new ModalOportunidade(chamarOportunidades)
        new ListagemEmpresas()
    })
}

function paginaGerenciarLicitacoes(){
     nownFiles.add(`${dominio}/conteudo/modulos/licitacoes/assets/licitacoes.css`)
}

class ListagemEmpresas{
    constructor(){
        this.init.bind(this)()
        
    }
    
    init(){
        document.getElementById('lista-empresas').innerHTML = ''
        document.getElementById('lista-empresas').appendChild(this.card.bind(this)())
    }
    
    card(){
        let nown = JSON.parse(pegaLocal('nown'))
        let empresa = nown.sub
        
        let div = document.createElement('div')
        div.classList.add('col-12')
        
       
        
        div.innerHTML = `
        <div class="card border-0 shadow-sm rounded overflow-hidden">
            <div style="height: 4px; background: linear-gradient(90deg, var(--nown-terciaria-lighter), var(--nown-terciaria));"></div>
                
            <div class="card-body p-4">
                <!-- Ícone e título -->
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle p-2 me-3 text-primaria">
                        <i class="bi bi-building fs-5"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">${empresa.nome}</h5>
                </div>
              
                <!-- Conteúdo -->
                <div class="ps-2 border-start border-terciaria border-opacity-25 mb-4">
                    <div class="mb-2">
                        <small class="text-muted me-2">CNPJ:</small>
                        <span class="fw-medium">${empresa.cnpj}</span>
                    </div>
                
                    <div class="mb-2 funcao-dado">
                        
                    </div>
                    
                    <div class="mt-3 acordion-dados">
                        <small class="text-muted d-block mb-1">Chaves de vínculo:</small>
                        <div class="accordion accordion-flush" id="accordionVinculos">
                            <div class="accordion-item border border-light rounded">
                                <h2 class="accordion-header" id="headingVinculos">
                                    <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVinculos-${empresa.cnpj}" aria-expanded="false" aria-controls="collapseVinculos-${empresa.cnpj}">
                                        <small class="fw-medium">Ver chaves</small>
                                    </button>
                                </h2>
                                <div id="collapseVinculos-${empresa.cnpj}" class="accordion-collapse collapse" aria-labelledby="headingVinculos" data-bs-parent="#accordionVinculos">
                                    <div class="accordion-body p-2">
                                        <ul class="list-group list-group-flush">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
                <!-- Botões de ação -->
                <div class="d-flex justify-content-end desvinculador">
            
                </div>
            </div>
        </div>
        
        `
        
        
        let api = new ApiNown('empresas', 'ZG2V2rCcN8vz2xD')
        api.isNew()
        api.paginacao(100)
        api.setExtra(infoUser().id)
        api.send().then((r)=>{
            let funcao = 0
            if(r.lista){
                let i = 0
                let associativo
                while(i < r.lista.length){

                    if(empresa.cnpj == r.lista[i].empresa.cnpj){
                        associativo = r.lista[i]
                        break
                    }
                    
                    i++
                }
                
                
                if(associativo){
                    
                    if(associativo.funcao != 0){
                        funcao = 1
                    }
                    let funcaoNome = associativo.funcao == 0 ? 'Dono/ Administrador' : associativo.funcao.nome
                    div.getElementsByClassName('funcao-dado')[0].innerHTML = `
                        <small class="text-muted me-2">Função:</small>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">${funcaoNome}</span>
                    `
                }
                
            }

            if(funcao != 0){
                div.getElementsByClassName('desvinculador')[0].innerHTML = `
                    <button class="btn btn-sm btn-outline-danger d-flex align-items-center btn-desvincular">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Desvincular
                    </button>
                `
                
                evento(div.getElementsByClassName('btn-desvincular')[0], 'click', ()=>{
                    
                    Swal.fire({
                        title: "Você tem certeza?",
                        text: "Você está prestes a fazer a desvinculação da empresa.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "var(--color-2)",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sim, desvincular!",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let request = new Request(`${dominio}/conteudo/modulos/empresas/admins/api.php`)
                            request.addData({
                                'acao': 'desvinculo',
                                'empresa': empresa.id
                            })
                            request.send().then((r)=>{
                                Swal.fire({
                                    title: "Sucesso",
                                    text: 'Desvinculação feita com sucesso',
                                    icon: "success",
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 5000,                   // Fecha após 5 segundos
                                    timerProgressBar: true, 
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                })
                                goUrl('conta')
                            }, (r)=>{
                                Swal.fire({
                                    title: "Erro ao fazer desvinculação",
                                    icon: "error",
                                    showCancelButton: false,
                                    confirmButtonColor: "var(--color-2)",
                                    confirmButtonText: "Entendi",
                                    timer: 5000,                   // Fecha após 5 segundos
                                    timerProgressBar: true,        // Mostra barra de progresso
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                })
                            })
                        }
                    })
                        
                })
            }
            
            if(funcao == 0){
                this.pegarChavesDisponiveis(div)
            }else{
                div.getElementsByClassName('acordion-dados')[0].remove()
            }
            
            
        }, (r)=>{
            console.log(r)
        })
        
        return div 
    }
    
    pegarChavesDisponiveis(div){
        let nown = JSON.parse(pegaLocal('nown'))
        let empresa = nown.sub
        let api = new ApiNown('empresas', '8njhw0UONRspqwT')
        api.isNew()
        api.setExtra(empresa.id)
        api.send().then((r)=>{
            let ul = div.getElementsByClassName('list-group-flush')[0]
            if(r.lista.length > 0){
                let i = 0
                
                while(i < r.lista.length){
                    
                    let li = document.createElement('li')
                    li.classList.add('list-group-item', 'd-flex', 'align-items-center', 'py-2', 'px-3', 'bg-transparent')
                    let usuario = 'Não foi usada'
                    if(parseInt(r.lista[i].uso)){
                        if(r.lista[i].usuario){
                            usuario = r.lista[i].usuario.display
                        }else{
                            usuario = 'Sem informação do usuário que usou'
                        }
                        
                    }
                    li.innerHTML = `

                        <i class="bi bi-key-fill me-2 text-warning"></i>
                        <span class="font-monospace small">${r.lista[i].url}</span>
                        <button class="btn btn-sm  text-primaria ms-auto p-0">${r.lista[i].funcao.nome}</button>
                        <button class="btn btn-sm  text-primaria ms-auto p-0">${usuario}</button>
                        
                    `
                    
                    ul.appendChild(li)
                    i++
                }
            }
            

            let li = document.createElement('li')
            li.classList.add('list-group-item', 'd-flex', 'align-items-center', 'py-2', 'px-3', 'bg-transparent')
            li.innerHTML = `
                <button class="btn btn-sm btn-light w-100 d-flex align-items-center justify-content-center criarConta">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    <small>Gerar nova chave</small>
                </button>
            `
            
            ul.appendChild(li)
            
            new ModalForm(ul.getElementsByClassName('criarConta')[0], 'empresas', 'sIqVXtW6ximnlUN');
            
            
        }, (r)=>{
            console.log(r)
        })
    }
}

function voltaClasseEmpresas(){
    new ListagemEmpresas()
}