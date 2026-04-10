class EstruturaGerenciarLicitacoes{
    constructor(){
        this.div = document.getElementById('licitacoesContent')
        this.request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        this.request.addData({
            acao: 'listagemlicitacoesAutor'
        })
        
        this.init.bind(this)()
    }
    
    init(){
        
        this.request.send().then((r) => {
            let lista = r.lista
            
            if(lista.length == 0){
                this.div.innerHTML = `
                <div class="d-flex justify-content-center w-100 he-200 align-items-center"> 
                    <span class="fs-28 fw-700 text-center">Nenhuma Licitação foi encontrada.</span>
                </div>`
                return
            }
            new CarregaGerenciarLicitacoes(lista, this.div)
        }, (r)=>{
            this.div.innerHTML = `
                <div class="d-flex justify-content-center w-100 he-200 align-items-center"> 
                    <span class="fs-28 fw-700 text-center">Falha ao buscar suas licitações no banco de dados.</span>
                </div>`
        })
    }
}

class CarregaGerenciarLicitacoes{
    constructor(lista, div){
        this.div = div
        this.pesquisa = lista
        
        this.montagem.bind(this)()
    }
    
    montagem(){
        this.fragmento = document.createDocumentFragment()
        
        
        var i = 0
        if(this.pesquisa){
            while ( i < this.pesquisa.length){
                this.buscaLicitacao.bind(this)(this.pesquisa[i])
                i++
            }
            
            this.div.innerHTML = ''
            this.div.appendChild(this.fragmento)
        }else{
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
        card.classList.add('col-lg-4', 'col-12',)
        
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
                        <div class="position-relative licitacao geral h-100">
                            <div class="content objeto"><span>Objeto:</span><p>${objeto}</p></div>
                            <div class="content edital"><span>Nº Licitação:</span><p>${licit}</p></div>
                            <div class="content edital"><span>Modalidade:</span><p>${modalidade}</p></div>
                            <div class="content data"><span class="align-self-center">Data de Abertura:</span><p class="m-0">${dataC}</p></div>
                            ${tarefaMenor}
                            <a class="stretched-link" data-url="licitacoes/${url}" href="${dominio}/licitacoes/${url}"></a>
                        </div>
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