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

function destransformarData(informacao){
    let dados = informacao.trim().split(' ')
    
    let data = dados[0] ?? false
    let hora = dados[1] ?? false
    
    if(data){
        let [dia, mes, ano] = data.split("/")
        data = `${ano}-${mes}-${dia}`
    }else{
        data = ''
    }
    
    if(hora){
       let [horas, minutos] = hora.split(":")
       hora = ` ${horas}:${minutos}:00`
    }else{
        hora = ''
    }
    
    let resposta =  `${data}${hora}`
    
    return resposta.trim()
}

function __getDados(produto, nome) {
    const valor = produto ? produto[nome] : null

    if (valor && typeof valor === 'string' && isJsonString(valor)) {
        try {
            const parsedValue = JSON.parse(valor)
            return Array.isArray(parsedValue) ? parsedValue[parsedValue.length - 1] || parsedValue : parsedValue
        } catch (e) {
            console.error('Erro ao parsear o valor JSON:', e)
            return ''
        }
    } else if (valor && (typeof valor === 'object' || Array.isArray(valor))) {
        return valor
    } else if(valor && typeof valor === 'string'){
        return valor
    }else {
        return ''
    }
}

function isJsonString(str) {
    try {
        JSON.parse(str)
    } catch (e) {
        return false
    }
    return true
}

function trataRS(numero){
    if (String(numero).includes(',')) {
        // Retorna o nÃºmero original
        return numero
    } else {
        // Formata o nÃºmero com duas casas decimais e usando o formato brasileiro
        return Number(Number(numero).toFixed(2)).toLocaleString('pt-BR', { 
            maximumFractionDigits: 2, 
            minimumFractionDigits: 2 
        })
    }
}

function trataSoma(valor){
    var preco = valor
    if (valor.includes(',')) {
        preco = preco.replaceAll('.', '')
        preco = preco.replace(',', '.')
    }
   
    return parseFloat(preco)
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

function baixarArquivos(arquivos){
    let i = 0
    
    while(i < arquivos.length){
        let a = document.createElement('a')
        let link = dominio+'/conteudo/uploads/'+arquivos[i].getElementsByClassName('arquivo')[0].innerHTML
        a.href = link
        a.download = arquivos[i].dataset.nome
        a.click()
        i++
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

function retornaId(item){
    let id
        
    if(item.id){
        id = item.id
    }else{
        id = item.url
    }
    
    return id
}

function validaSituacao(dataAbertura, dataEncerramento) {
    const hoje = new Date()

    const parseDate = (data) => {
        var [dia, mes, anoHora] = data.split('/')
        if(!anoHora){
            var [dia, mes, anoHora] = data.split('-')
        }
        const [ano, hora] = anoHora.split(' ')
        return new Date(`${ano}-${mes}-${dia}T${hora}`)
    }

    if (!dataAbertura || !dataEncerramento) {
        return 'Fechado'
    }

    const abertura = parseDate(dataAbertura)
    const encerramento = parseDate(dataEncerramento)

    if (hoje < abertura) {
        return 'Publicada'
    }

    if (hoje >= abertura && hoje <= encerramento) {
        return 'Aberto'
    }

    if (hoje > encerramento) {
        return 'Fechado'
    }
    return 'Fechado'
}

class PaginasLicitacoes{
    constructor(){
        this.iniciar.bind(this)()
        
        new Mascaras()
    }
    
    iniciar(){
        var url = window.location.href.split(`/licitacoes/`)[1].split("/")
        var i = 0
        var final = []
        while(i < url.length){
            if(url[i]){
               final.push(url[i]) 
            }
            i++
        }
        
        if(final.length == 0){
            this.home.bind(this)()
        }else{
            if(final.length > 0){
                switch(final[0]){
                    case 'oportunidades':
                        this.oportunidades.bind(this)()
                        break
                    case 'filtro':
                        nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/filtro.js`]).then(()=>{
                            new FiltroLicitacoes()  
                        })
                        break
                    case 'favoritos':
                        this.favoritos.bind(this)()
                        break
                    case 'log':
                        nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/card-notificacao.js`]).then(()=>{
                            this.logs.bind(this)()  
                        })
                        break
                    case 'documentacoes':
                        nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/publico/documentacoes/js.js`]).then(()=>{
                            paginaDocumentacoes()
                        })
                        break
                    case 'gerenciar-licitacoes':
                        nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/gerenciar-licitacoes.js`]).then(()=>{
                            new EstruturaGerenciarLicitacoes()
                        })
                        break
                    default:
                        nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/itemLicitacao.js`]).then(()=>{
                            new ItemLicitacao()
                        })
                        break
                }
            }
        }
    }
    
    favoritos(){
        new PaginaFavoritos()
    }
    
    oportunidades(){
        new PaginaOportunidades()
        chamarOportunidades()
    }
    
    logs(){
        new PaginaLogs()
    }
    
}

class PaginaOportunidades{
    constructor(){
        this.items = []
        this.dados = []
        
        this.modalOportunidades = new bootstrap.Modal('#modalOportunidades', {
          keyboard: false
        })
        
        this.modalItems = new bootstrap.Modal('#modalItems', {
          keyboard: false
        })
        
        this.div = document.getElementById('boletinsWrap')
        this.bgCarregando = this.div.innerHTML
        this.noReg = document.getElementById('boletinsWrap').getElementsByClassName('nenhum-registro')[0]
        this.noReg.classList.remove('d-none')
        this.data = ''
        evento(Array.from(document.getElementsByClassName('btn-oportunidade')), 'click', (e) => {
            this.data = ''
            this.carregaDados.bind(this)(e.currentTarget.dataset.filtro)
        })
        
        evento(document.getElementById('modalItems'), 'hidden.bs.modal', () => {
            this.modalOportunidades.show()
        })
        
        this.preencheTotal.bind(this)()
        
        new ModalOportunidade()
    }
    
    async preencheTotal(){
        this.carregaDados.bind(this)(1)
        this.carregaDados.bind(this)(2)
        this.carregaDados.bind(this)(3)
        this.chamarCalendario.bind(this)()
    }
    
    chamarCalendario(){
        nownFiles.add(['https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', 
        'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js']).then(()=>{
            this.calendarDiv = document.getElementById('calendar')
            this.bgCalendar = this.calendarDiv.innerHTML
            this.dataSelecionada = new Date()
            this.botaosTipo = document.getElementsByClassName('btn-escolha-calendario');
            evento(Array.from(this.botaosTipo), 'click', this.escolhendoCalendario.bind(this));
            this.tipoCalendario = 2; 
            
            evento(document.getElementById('anteriorBotao'), 'click', () => {
                this.calendar.prev();
                this.definirCalendario.bind(this)()
            });
        
            evento(document.getElementById('nextButton'), 'click', () => {
                if(this.calendar.getDate() > new Date){
                    iziToast.error({
                        message: "Ainda não há licitações disponíveis para datas futuras. Por favor, selecione uma data válida deste mês.",
                        timeout: 5000,
                        position: 'topRight',
                    });
                    
                    return 
                }
                this.calendar.next();
                this.definirCalendario.bind(this)()
            });
            
            this.definirCalendario.bind(this)()
        })
    }
    
    definirCalendario() {
        const mesAnoDisplay = document.getElementById('mesAnoDisplay');
        
        const opcoes = { year: 'numeric', month: 'long' };
        
        if(!this.calendar){
            const mesAnoFormatado = this.dataSelecionada.toLocaleDateString('pt-BR', opcoes);
            mesAnoDisplay.textContent = mesAnoFormatado;
            this.criarCalendario.bind(this)()
            return;
        }
        
        const dataAtual = this.calendar.getDate();
        
        if (!(dataAtual instanceof Date)) {
            console.error('dataAtual não é um objeto Date:', dataAtual);
        }
        this.dataSelecionada = dataAtual
        const mesAnoFormatado = this.dataSelecionada.toLocaleDateString('pt-BR', opcoes);
        mesAnoDisplay.textContent = mesAnoFormatado;
        this.criarCalendario.bind(this)()
    }
    
    escolhendoCalendario() {
        if (event.currentTarget.classList.contains('btn-padrao')) {
            return;
        }
    
        var i = 0;
        while (i < this.botaosTipo.length) {
            this.botaosTipo[i].classList.remove('btn-padrao');
            this.botaosTipo[i].classList.add('btn-padrao-2');
            i++;
        }
    
        event.currentTarget.classList.remove('btn-padrao-2');
        event.currentTarget.classList.add('btn-padrao');
    
        this.tipoCalendario = event.currentTarget.dataset.tipo;
        this.criarCalendario.bind(this)()
    }
    
    async criarCalendario(){
        if (this.calendar) {
            this.calendar.destroy();
        }
        
        this.calendarDiv.innerHTML = this.bgCalendar
        
        var tipagem =  parseInt(parseInt(this.tipoCalendario) + 1)
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'pegarOportunidades',
            'tipo': tipagem,
            'paginacao' : 100000,
            'data': this.dataSelecionada.toISOString(),
            'pagina': 1
        })
         
        var lista = []
        try{
            var api = await request.send()
            
            var i = 0;
            var groupedEvents = {}; // Objeto para agrupar as notificações por data
        
            // Loop através das licitações para agrupar por data
            while (i < api.licitacoes.length) {
                var licitacao = api.licitacoes[i];
                
                // Formata as datas para pegar apenas a parte da data (sem horas)
                var publicacao = licitacao.publicacao.replace(/T/g, ' ').split(' ')[0]; // Considera apenas a data
                var encerramento = licitacao.encerramento.replace(/T/g, ' ').split(' ')[0];
                var atualizacao = licitacao.atualizacao.replace(/T/g, ' ').split(' ')[0]; // Usando a data de atualização para agrupamento
                // Agrupando as notificações por data de publicacao
                
                if (!groupedEvents[atualizacao]) {
                    groupedEvents[atualizacao] = [];
                }
                
                if(groupedEvents[atualizacao]){
                    groupedEvents[atualizacao].push(licitacao);
                }

                
        
                i++;
            }
            
            for (var date in groupedEvents) {
                var notifications = groupedEvents[date];
                
               
                var firstUpdate = new Date(notifications[0].atualizacao.replace(/T/g, ' '));
                var start = new Date(firstUpdate.getTime() + 60 * 60 * 1000).toISOString();
                var end = new Date(firstUpdate.getTime() + 60 * 60 * 1000).toISOString();

                // Cria um evento para o dia com o título mostrando o número de notificações
                lista.push({
                    id: date,
                    title: `Licitações: ${notifications.length}`,
                    'start': start,
                    'end': end,
                    textColor: '#fff',
                    backgroundColor: `var(--color-2)`,
                    extendedProps: {
                        notifications: notifications // Armazena todas as notificações daquele dia
                    },
                    classNames: ['notificacao'] // Classe específica para o evento
                });
            }
        }catch(erro){
            console.log(erro)   
        }
        
        let tipo;
        switch (parseInt(this.tipoCalendario)) {
            case 1:
                tipo = 'timeGridWeek';
                break;
            case 2:
                tipo = 'dayGridMonth';
                break;
        }
        
        this.calendarDiv.innerHTML = ''
        
        this.calendar = new FullCalendar.Calendar(this.calendarDiv, {
            initialView: tipo,
            events: lista,
            eventContent: function(arg) {
                return { html: `<span class="notificacao">${arg.event.title}</span>` };
            },
            locale: 'pt-br',
            headerToolbar: {
                left: '',
                center: 'title',
                right: ''
            },
            allDayText: 'Dia Todo',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            eventClick: (info) => {
                const hoje = new Date();
                const hojeZerado = this.zerarHoras.bind(this)(hoje);
                
                const eventDate = new Date(info.event.start); 
                eventDate.setDate(eventDate.getDate() + 1);
                
                const eventDateZerado = this.zerarHoras.bind(this)(eventDate);
                
                this.data = eventDate.toISOString();

                if (eventDateZerado.getTime() === hojeZerado.getTime()) {
                    
                    this.data = ''
                }

                this.carregaDados(1)
                return false;
            }
        });
    
        this.calendar.render();
    
        if (this.dataSelecionada) {
            this.definicaoData.bind(this)();
        }
    }
    
    zerarHoras(date) {
        const dataZerada = new Date(date); // Faz uma cópia da data
        dataZerada.setHours(0, 0, 0, 0);  // Zera as horas, minutos, segundos e milissegundos
        return dataZerada;
    }
    
    definicaoData() {
        this.calendar.gotoDate(this.dataSelecionada);
        this.calendar.render();
    }
    
    mudar(r){
        this.carregaDados.bind(this)(this.filtro, r)
    }
    
    async carregaDados(filtro, pagina = 1){
        if(this.data){
            this.filtro = this.data
            filtro = 1
        }else{
            filtro = parseInt(filtro)
            this.filtro = filtro
        }
        if(event && event.type == 'click'){
            var evento = true
            var eventado = event.currentTarget
            var textoEventado = eventado.innerHTML 
        }
        
        this.paginaAtual = pagina
        
        this.div.innerHTML = this.bgCarregando
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'pegarOportunidades',
            'tipo': parseInt(filtro),
            'paginacao' : 2,
            'pagina': this.paginaAtual,
            'data' : this.data
        })
         
         
        try{
            
            if(evento){
                this.modalOportunidades.show()
            }
            
            if(this.dados[this.filtro] && this.dados[this.filtro][this.paginaAtual]){
                await this.percorreLista.bind(this)()
            }else{
                let r = await request.send()
                if(!this.dados[this.filtro]){
                    this.dados[this.filtro] = []
                }
                
                var paginacao = false
                
                this.totalPaginas = Math.ceil(r.total / 2)
                
                if(this.totalPaginas > 1){
                    this.pagination = new CriaPaginacao(this, this.mudar.bind(this))
                    var paginacao = true
                }
                
                if(!this.dados[this.filtro][this.paginaAtual]){
                    this.dados[this.filtro][this.paginaAtual] = []
                    this.dados[this.filtro][this.paginaAtual]['cards'] = []
                }

                if(paginacao){
                    this.dados[this.filtro][this.paginaAtual]['paginacao'] = this.pagination
                }
                
                this.dados[this.filtro][this.paginaAtual]['lista'] = r.licitacoes
                
                if(evento){
                    await this.percorreLista.bind(this)()
                }else{
                    switch(filtro){
                        case 3:
                            var div = document.getElementsByClassName('total-mes')[0]
                            break
                        case 2:
                            var div = document.getElementsByClassName('total-semana')[0]
                            break
                        default:
                            var div = document.getElementsByClassName('total-dia')[0] 
                            break
                    }
                    
                    div.innerHTML = `<span class="quantidade">${r.total}</span> ${r.total > 1 ? 'Licitações' : 'Licitação'}`
                }
            }
        }catch (e){
            switch(filtro){
                case 3:
                    var div = document.getElementsByClassName('total-mes')[0]
                    break
                case 2:
                    var div = document.getElementsByClassName('total-semana')[0]
                    break
                default:
                    var div = document.getElementsByClassName('total-dia')[0] 
                    break
            }
            
            if(div){
                div.innerHTML = `Nenhuma licitação encontrada`
            }
            
            this.div.innerHTML = ``
            document.getElementById('paginacao').innerHTML = ''
            this.div.appendChild(this.noReg)
        } 
    }
    
    modais(){
        this.modalOportunidades.hide()
        this.modalItems.show()
    }
    
    async percorreLista(){
        let i = 0
        
        var fragmento = document.createDocumentFragment()
        
        if(this.dados[this.filtro][this.paginaAtual]['cards'] && this.dados[this.filtro][this.paginaAtual]['cards'].length > 0){
            while(i < this.dados[this.filtro][this.paginaAtual]['cards'].length){
                fragmento.appendChild(this.dados[this.filtro][this.paginaAtual]['cards'][i])
                i++
            }
        }else{
            while(i < this.dados[this.filtro][this.paginaAtual]['lista'].length){
                var card = await new CardPadrao2(this.dados[this.filtro][this.paginaAtual]['lista'][i], this.modais.bind(this))
                this.dados[this.filtro][this.paginaAtual].cards.push(card)
                fragmento.appendChild(card)
                i++
            }
        }
        
        document.getElementById('paginacao').innerHTML = ''
        this.div.innerHTML = ''
        this.div.appendChild(fragmento)
        if(this.dados[this.filtro][this.paginaAtual]['paginacao']){
            document.getElementById('paginacao').appendChild(this.dados[this.filtro][this.paginaAtual]['paginacao'].paginacao)
            this.dados[this.filtro][this.paginaAtual]['paginacao'].verificarBotoes('paginacao')
        }
       
        new Favoritos()
        
    }
}

class PaginaFavoritos{
    constructor(){
        this.modalItems = new bootstrap.Modal('#modalItems', {
          keyboard: false
        })
        
        this.div = document.getElementById('favoritosWrap')
        this.noReg = this.div.getElementsByClassName('nenhum-registro')[0] ? this.div.getElementsByClassName('nenhum-registro')[0] : ''
        
        this.api = new ApiNown('licitacoes', '3vwhPuFcaQyXoUh')
        this.api.isNew()
        
        this.init.bind(this)()
    }
    
    init(){
        let request = new Request(`${dominio}/conteudo/modulos/favoritos/admins/lista.php`)
        request.addData({
            acao: 'listar',
            'modulo' : 'licitacoes'
        })
        request.send().then((r)=>{
            if(r.lista && r.lista.licitacoes && r.lista.licitacoes.length > 0){
                this.api.setItens(r.lista.licitacoes)
                this.api.send().then((r) => {
                    this.lista = r.lista
                    this.noReg.classList.add('d-none')
                    this.percorreLista.bind(this)()
                }, (r) => {
                    this.noReg.classList.remove('d-none')
                    this.div.innerHTML = ''
                    this.div.appendChild(this.noReg)
                })
            }else{
                this.noReg.classList.remove('d-none')
                this.div.innerHTML = ''
                this.div.appendChild(this.noReg)
            }
        }, (r)=>{
            this.noReg.classList.remove('d-none')
            this.div.innerHTML = ''
            this.div.appendChild(this.noReg)
        })
        
    }
    
    async percorreLista(){
        let i = 0 
        let fragmento = document.createDocumentFragment()
        
        while(i < this.lista.length){
            let div = document.createElement('div')
            div.classList.add('col-xl-6', 'col-12')
            
            div.appendChild(await new CardPadrao2(this.lista[i]))
            
            fragmento.appendChild(div)
            
            i++
        }
        
        this.div.innerHTML = ''
        this.div.appendChild(fragmento)
        
        new Favoritos()
    }
}

class PaginaLogs{
    constructor(){
        this.div = document.getElementById('notificacoesWrap')
        
        this.paginas = []
        
        this.paginacao = 12
        
        // this.api = new ApiNown('licitacoes', 'miJWYkjpgwzxadM')
        // this.api.isNew()
        
        this.api = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        
        
        
        this.init.bind(this)()
    }
    
    init(pagina = 1){
        this.paginaAtual = parseInt(pagina)
        
        if(this.paginas[this.paginaAtual]){
            this.percorreLista.bind(this)()
        }else{
            this.api.addData({
                'acao': 'pegarLogs',
                'pagina': pagina
            })
            this.api.send().then((r) => {
                this.paginas[this.paginaAtual] = r.lista
                
                this.totalPaginas = Math.ceil(r.numeros.total / this.paginacao)
                this.pagination = false
                
                if(this.totalPaginas > 1){
                    this.pagination = new CriaPaginacao(this, this.init.bind(this))
                }
                
                this.percorreLista.bind(this)()
                
            })
        }
    }
    
    percorreLista(){
        let i = 0
        let fragmento = document.createDocumentFragment()
        
        var lista = this.paginas[this.paginaAtual]
        
        while(i < lista.length){
            let div = document.createElement('div')
            div.classList.add('col-xl-6', 'col-12')
            
            div.appendChild(new CardNotificacao(lista[i]))
            
            fragmento.appendChild(div)
            i++
        }
        
        this.div.innerHTML = ''
        this.div.appendChild(fragmento)
        
        document.getElementById('paginacao').innerHTML = ''
        
        if(this.pagination){
            document.getElementById('paginacao').appendChild(this.pagination.paginacao)
            this.pagination.verificarBotoes('paginacao')
        }
        
    }
}

class CardPadrao2{
    constructor(item, cb = false){
        this.item = item
        if(cb){
            this.cb = cb
        }
        return this.montaCard.bind(this)(item)
    }
    
    async montaCard(dado){
        let card = document.createElement('div')
        
        card.classList.add('boletins-wrap', 'd-flex', 'flex-column')

        let url
        if(dado.url){
            url = dado.url
        }
        
        let favorito = new ApiNown('licitacoes','sGziXEtBonGxVt2')
        favorito.setHash(url)
        favorito.isNew()
        
        try{
            let resposta = await favorito.send()
            var fav = resposta?.item?.n?.favoritado || false
        }catch(e){
            console.log(e)
        }
        
        let data = dado.att ? transformarData(__getDados(dado, 'att').replace(/T/g, ' ')) : transformarData(__getDados(dado, 'data').replace(/T/g, ' '))

    
        let objeto = __getDados(dado, 'objeto')
        
        let abertura = transformarData(__getDados(dado, 'dataAberturaPropostaPncp').replace(/T/g, ' '))
        let encerramento = transformarData(__getDados(dado, 'dataEncerramentoPropostaPncp').replace(/T/g, ' '))
        
        if(abertura && encerramento){
            var datas = `
            <div class="content">
                <span>Datas:</span><p>Abertura: ${abertura}</p>
                <p>Encerramento: ${encerramento}</p>
            </div>
            `
        }
        else if(abertura && !encerramento){
            var datas = `
            <div class="content">
                <span>Datas:</span><p>Abertura: ${abertura}</p>
            </div>
            `
        }else{
            var datas = `
           
            `
        }
        
        // let inclusao = transformarData(__getDados(dado, 'dataInclusaoPncp').replace(/T/g, ' '))
        // let publicacao = transformarData(__getDados(dado, 'dataPublicacaoPncp').replace(/T/g, ' '))
        
        let edital = __getDados(dado, 'numeroCompra')
        let orgao = __getDados(dado, 'unidadeOrgaoNomeUnidade')
        let cidade = __getDados(dado, 'unidadeOrgaoMunicipioNome')
        let uf = __getDados(dado, 'unidadeOrgaoUfSigla')
        let localizacao = `${cidade} - ${uf}`
        let situacao = validaSituacao(abertura, encerramento)
        let valorEst = __getDados(dado, 'valorTotalEstimado')
        let valorHom = __getDados(dado, 'valorTotalHomologado')
        
        let vincular = `<button class="btn license vincular"><i class="bi bi-plus-circle"></i><span class="text">Gerenciar licitação</span></button>`
        
        let valorEstText = valorEst ? `${valorEst.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}` : 'Não informado'
        
        let styleEstimado
        
        switch(situacao){
            case 'Fechado':
                styleEstimado = 'fechado'
                break
            case 'Aberto':
                styleEstimado = 'aberto'
                break
            default:
                styleEstimado = 'publicado'
                break
        }
        
        card.innerHTML = `
                            <div class="cabecalho">
                                <div>
                                    <div class="d-flex gap-2">
                                        <button class="btn favorite btnFavoritar" data-estrutura="licitacoes" data-url="${dado.url}" data-ativo="${fav}"></button>
                                    </div>
                                </div>
                                <div class="att">
                                    <span class="atualizada">Atualizada em: </span><span class="data">${data}</span>
                                </div>
                            </div>
                            <div class="infos flex-fill">
                                <div class="content"><span>Objeto:</span><p class="">${objeto}</p></div>
                                ${datas}
                                <div class="content"><span>Número da Licitação:</span><p>${edital}</p></div>
                                <div class="content"><span>Órgão:</span><p class="text-uppercase">${orgao}</p></div>
                                <div class="content"><span>Cidade:</span><p>${localizacao}</p></div>

                                <div class="content">
                                    <div class="bout-valor flex-column align-items-start">
                                        <div class="d-flex gap-3">
                                            <span class="align-content-center">Situação: </span><div class="notificacao ${styleEstimado}">${situacao}</div>
                                        </div>
                                        <div class="valor-estimado">Valor estimado: <span class="valor">${valorEstText}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                ${vincular}
                                <button class="btn download"><i class="bi bi-arrow-up-right"></i><span class="text">Consultar edital</span></button>
                                <div>
                                    <button class="btn itens h-100 btn-show-modalItems" type="button" data-bs-target="#modalItems" data-bs-toggle="modal">
                                        <span class="text">Itens</span></i>
                                    </button>
                                </div>
                            </div>
                         `
                         
                         
        if(dado.itens && JSON.parse(dado.itens) && JSON.parse(dado.itens).length > 0){
            var items = JSON.parse(dado.itens)
        }else{
            var items = []
        }   
        
        evento(card.getElementsByClassName('btn-show-modalItems')[0], 'click', () => {
            this.arrayItems = items
            this.classeItems = new FormarItems(this)
            this.classeItems.init()
            if(this.cb){
                this.cb()
            }
            // this.formarItems.bind(this)()
            
        })
        
        evento(card.getElementsByClassName('vincular')[0], 'click', () => {
            var id = dado.id ?? url
            this.vincularLicitacao.bind(this)(id)
        })
        
        evento(card.getElementsByClassName('download')[0], 'click', this.baixaEdital.bind(this))
        
        
        return card
    }
    
    vincularLicitacao(id){
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'vincularLicitacao',
            'id': id
        })
        
        request.send().then((r) => {
            if(r.sucesso){
                goUrl(dominio+'/licitacoes/'+r.item.url)
            }
        }, (r)=>{
            if(r.autorizado){
                Swal.fire({
                    title: "Erro",
                    text: r.mensagem,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "var(--color-2)",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ver Planos",
                    cancelButtonText: "Entendi"
                }).then((result) => {
                    if (result.isConfirmed) {
                        goUrl('assinaturas')
                    }
                })
            }
        })
    }
    
    baixaEdital(){
        // esse é o padrao da url, ela vai ser direcionada para um captcha do governo, tem que abrir em uma janelinha, vem o numero da unidade gestora, o numero do processo e numero da modalidade ou do modo de disputa(ainda não tenho certeza) e esse último é see é nacional , mas todas praticamente sãa
        // http://comprasnet.gov.br/ConsultaLicitacoes/Download/Download.asp?coduasg=380241&numprp=900642024&modprp=5&bidbird=N
        
        let anoCompra = this.item.anoCompraPncp
        let orgaoCnpj = this.item.orgaoEntidadeCnpj
        let compraPncp = this.item.sequencialCompraPncp
        
        var abrir = false
        
        if(anoCompra && orgaoCnpj && compraPncp){
            var abrir = true
        }
        
        
        var url = `https://pncp.gov.br/app/editais/${orgaoCnpj}/${anoCompra}/${compraPncp}`
        
        if(abrir){
            let width = 800
            let height = 600;
            let left = (window.screen.width - width) / 2;
            let top = (window.screen.height - height) / 2;
    
            window.open(
                url,
                '_blank',
                `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
            );
        }else{
            iziToast.error({
                message: "Licitação sem edital",
                timeout: 5000,
                position: 'topRight',
            });
        }
        
    }
    
    
}

class FormarItems{
    constructor(pai){
        this.pai = pai
        this.apiItems = new ApiNown('licitacoes','7Yk1ZktWreHdTA0')
        this.apiItems.isNew()
        this.apiItems.setItens(this.pai.arrayItems)
        this.div = document.getElementById('tableDados')
        return
    }
    
    init(pagina = 1){
        this.apiItems.setPagina(pagina)
        
        this.apiItems.send().then((r)=>{
            this.div.innerHTML = ''
            this.paginaAtual = parseInt(pagina)
            this.totalPaginas = Math.ceil(r.numeros.total / 12)
            if(this.totalPaginas > 1){
                var paginacao = new CriaPaginacao(this, this.init.bind(this))
                
                this.paginacao = paginacao.paginacao
            }
            let lista = r.lista
            
            let i = 0
            while(i < lista.length){
                var row = document.createElement('tr')
                row.classList.add('fs-14')
                row.innerHTML = `
                                    <td>${lista[i].item_material && lista[i].item_material.desc ? lista[i].item_material.desc : lista[i].desc}</td>
                                    <td>${lista[i].quantidade ?? 'Não Informado'}</td>
                                `
                this.div.appendChild(row)
                i++
            }
            
            document.getElementById('paginacaoItem').innerHTML = ''
            if(this.paginacao){
                document.getElementById('paginacaoItem').appendChild(this.paginacao)
                paginacao.verificarBotoes('paginacaoItem')
            }
        })
    }
}

class CriaPaginacao{
    constructor(pai, cb = false){
        this.cb = cb
        this.totalPaginas = pai.totalPaginas
        this.pai = pai
        this.criaPaginacao(this.totalPaginas)
    }
    
    verificarBotoes(div){
        
        var botoes = document.getElementById(div).getElementsByClassName('page-item')
        var i = 0

        while(i < botoes.length){
            var botao = botoes[i].getElementsByClassName('page-link')[0]
            if(parseInt(botao.dataset.acao)){
                if(parseInt(botao.dataset.acao) == this.pai.paginaAtual){
                    botoes[i].classList.add('active')
                }else{
                    botoes[i].classList.remove('active')
                }
            }else{
                if(botao.dataset.acao == 'anterior'){
                    if(this.pai.paginaAtual == 1){
                        botoes[i].classList.add('disabled')
                    }else{
                        botoes[i].classList.remove('disabled')
                    }
                }else{
                    if(this.pai.paginaAtual == this.totalPaginas){
                        botoes[i].classList.add('disabled')
                    }else{
                        botoes[i].classList.remove('disabled')
                    } 
                }
            }
            i++
        }

    }
    
    criaPaginacao(paginas) {
        this.paginacao = document.createElement('div')
        
        var i = 1
        var elemento = document.createElement('div')
        elemento.classList.add('mt-3')
        elemento.innerHTML =    `  
                                    <nav>
                                        <ul class="pagination justify-content-center align-items-center gap-2">
                                            <li class="page-item h-100">
                                                <a class="page-link d-flex align-items-center btn" data-acao="anterior"><i class="bi bi-arrow-left-short"></i></a>
                                            </li>
                                        </ul>
                                    </nav>
                                `
    
        var lista = elemento.querySelector('.pagination')
        
        if(this.pai.paginaAtual > 2){
            let item = document.createElement('li')
            item.classList.add('page-item')
            item.innerHTML = `<a class="page-link btn" data-acao="1">1</a>`
            lista.appendChild(item)
            
            if(this.pai.paginaAtual != 3){
                let reticencias = document.createElement('span')
                reticencias.classList.add('mx-2', 'fs-18')
                reticencias.style = "letter-spacing: 2px"
                reticencias.innerHTML = '...'
                lista.appendChild(reticencias)
            }
           
        }
    
        //Botões de Pagina Numero
        while(i >= 1) {
            if(this.pai.paginaAtual - i > 0){
                var pagina = parseInt(this.pai.paginaAtual) - parseInt(i)
                let item = document.createElement('li')
                item.classList.add('page-item')
                item.innerHTML = `<a class="page-link btn" data-acao="${pagina}">${pagina}</a>`
                lista.appendChild(item)
            }
            
            i--
        }
        
        let item = document.createElement('li')
        item.classList.add('page-item')
        item.innerHTML = `<a class="page-link btn" data-acao="${this.pai.paginaAtual}">${this.pai.paginaAtual}</a>`
        lista.appendChild(item)
        
        
        if(this.pai.paginaAtual < this.totalPaginas - 1){
            
            let item = document.createElement('li')
            item.classList.add('page-item')
            item.innerHTML = `<a class="page-link btn" data-acao="${parseInt(this.pai.paginaAtual) + 1}">${parseInt(this.pai.paginaAtual) + 1}</a>`
            lista.appendChild(item)
            
            if(this.pai.paginaAtual < this.totalPaginas - 2){
                let reticencias = document.createElement('span')
                reticencias.classList.add('mx-2', 'fs-18')
                reticencias.style = "letter-spacing: 2px"
                reticencias.innerHTML = '...'
                lista.appendChild(reticencias)
            }
            
        }
    
        if(this.pai.paginaAtual <= this.totalPaginas - 1){
            let item = document.createElement('li')
            item.classList.add('page-item')
            item.innerHTML = `<a class="page-link btn" data-acao="${this.totalPaginas}">${this.totalPaginas}</a>`
            lista.appendChild(item)
        }
        
        // Botão de navegação Próximo
        let proxItem = document.createElement('li')
        proxItem.classList.add('page-item')
        proxItem.innerHTML = `<a class="page-link d-flex align-items-center btn" data-acao="proximo"><i class="bi bi-arrow-right-short"></i></a>`
        lista.appendChild(proxItem)
        
        evento(Array.from(elemento.getElementsByClassName('page-link')), 'click', this.trocaPagina.bind(this))
        
        this.paginacao.appendChild(elemento)
    }
    
    trocaPagina(){
        var botao = event.currentTarget
        var pagina = null
        
        if(botao.dataset.acao == 'anterior'){
            pagina = parseInt(this.pai.paginaAtual - 1) < 1 ? 1 : this.pai.paginaAtual - 1
        }else if(botao.dataset.acao == 'proximo'){
            pagina = parseInt(this.pai.paginaAtual + 1) > this.totalPaginas ? this.totalPaginas : this.pai.paginaAtual + 1
        }else {
            pagina = botao.dataset.acao
        }
        
        this.voltarSessaoTopo.bind(this)(botao)
        if(this.cb){
            this.cb(pagina)
        }
        // this.verificarBotoes.bind(this)()
        // this.pai.formarItems(pagina)
        // this.pai.init()
    }
    
    voltarSessaoTopo(botao){
        if(!botao.closest('#modalItems')){
            if(document.getElementById('sessao-topo')){
                 document.getElementById('sessao-topo').scrollIntoView({
                     behavior: 'smooth'
                 })
            }
        }
    }
}

async function baixaEdital(licitacao, orgao, items){
    let edital = {}
        
    edital.numero_processo = licitacao.numero_processo
    edital.objeto = licitacao.objeto
    edital.nome_modalidade = licitacao.nome_modalidade
    edital.tipo_pregao = licitacao.tipo_pregao
    edital.situacao_aviso = licitacao.situacao_aviso
    edital.valor_homologado_total = licitacao.valor_homologado_total
    edital.data_abertura_proposta = licitacao.data_abertura_proposta
    edital.data_entrega_proposta = licitacao.data_entrega_proposta
    edital.nome_responsavel = licitacao.nome_responsavel
    edital.funcao_responsavel = licitacao.funcao_responsavel
    edital.unidade_gestora = licitacao.unidade_gestora
    edital.nome = orgao.nome
    edital.siglaUf = orgao.siglaUf
    edital.nomeMunicipioIbge = orgao.nomeMunicipioIbge
    edital.codigoMunicipioIbge = orgao.codigoMunicipioIbge
    edital.nomeUnidadePolo = orgao.nomeUnidadePolo
    edital.codigoUnidadePolo = orgao.codigoUnidadePolo
    edital.cnpjCpfUasg = orgao.cnpjCpfUasg
    edital.orgao = orgao.orgao
    edital.items = {}
    
    let i = 0
    
    while(i < items.length){
        var apiItem = new ApiNown('licitacoes', '3TxiLViAgnrrpWm')
        apiItem.setHash(items[i])
        
        try{
            let r = await apiItem.send()
            let item = r.item
            
            edital.items[i] = {}
            edital.items[i].nome = item.desc
            edital.items[i].quantidade = item.quantidade
            
        }catch(e){
            console.log(e)
        }
        
        i++
    }
}

function paginaLicitacoes(){
    nownFiles.add([`${dominio}/conteudo/modulos/licitacoes/assets/licitacoes.css`]).then(()=>{
        new PaginasLicitacoes()
    })
}
