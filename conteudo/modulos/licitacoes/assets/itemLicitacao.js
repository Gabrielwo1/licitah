class MontaSubtarefa{
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
                                <input class="form-check-input" ${checkado} name="skip" type="checkbox" value="" id="flexCheckDefault">
                                    <div class="nome-subtarefa">
                                        <div class="w-100">
                                            <input type="text" value="${nome}" name="skip" class="form-control rounded-0 bg-transparent" style="border: 0px !important">
                                        </div>
                                        <div class="wi-220">
                                            <input type="" data-mascara="9" value="${data}" name="skip" class="form-control rounded-0 bg-transparent mascaraInput" style="border: 0px !important">
                                        </div>
                                    </div>
                                <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
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

class MontaHabilitacao{
    constructor(habilitacao, pai){
        this.habilitacao = habilitacao
        this.pai = pai
        this.item = this.pai.item
        this.modal = this.pai.modalAnexo
        return this.criar.bind(this)()
        
    }
    
    criar(){
        let habilitacao = this.habilitacao
        
        this.tr = document.createElement('tr')
        
        let nome = habilitacao.nome && habilitacao.nome.length > 0 ? habilitacao.nome : ''
        this.tr.setAttribute('data-nome', nome)
        
        let arquivo = habilitacao.documento && JSON.parse(habilitacao.documento).length > 0 ? JSON.parse(habilitacao.documento)[0] : []
        this.arquivoP = `${dominio}/conteudo/uploads/${arquivo}`
        let dataCriacao = habilitacao.dataCriacao && habilitacao.dataCriacao.length > 0 ? transformarData(habilitacao.dataCriacao.split(' ')[0]) : ''
        
        this.tr.innerHTML = this.pai.montandoLinha(nome, arquivo, dataCriacao)
        
        evento(this.tr.getElementsByClassName('btn-modal')[0], 'click', this.passaDados.bind(this))
        evento(this.tr.getElementsByClassName('btn-trash')[0], 'click', this.desvinculaHabilitacao.bind(this))
        
        return this.tr
    }
    
    desvinculaHabilitacao(){
        Swal.fire({
          title: "Você tem certeza?",
          text: "Você está prestes a fazer a desvinculação, mas poderá recuperar o dado posteriormente.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "var(--color-2)",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, desvincular!",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
                this.desvinculandoHabilitacao.bind(this)()
          }
        })
        
    }
    
    desvinculandoHabilitacao(){
        let id = retornaId(this.item)
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'apagarHabilitacao',
            'id': id,
            'id_habilitacao': this.habilitacao.id
        })
        request.send().then((r) =>{
            iziToast.success({
                message: "Habilitação desvinculada com sucesso!",
                timeout: 5000,
                position: 'topRight',
             })
             
             this.pai.habilitacoes = this.pai.removerItemPorIdSemObjeto(this.pai.habilitacoes, parseInt(this.habilitacao.id))
            
                this.pai.montaLinhaSemReg.bind(this)(this.tr)
                 this.tr.remove()
        }, (r) => {
            iziToast.error({
                message: "Falha ao desvincular a habilitação!",
                timeout: 5000,
                position: 'topRight',
             })
        })    
    }
    
    passaDados(){
        
        var divMod = document.getElementById('modalAnexoContent')
        var divModH = divMod.getElementsByClassName('modal-title')[0]
        var divModB = divMod.getElementsByClassName('modal-body')[0]
        
        divModH.innerHTML = `
                                ${this.habilitacao.nome}
                            `
        
        if(this.arquivoP.includes('imagens')){
            divModB.innerHTML = `
                                    <img src="${this.arquivoP}" class="w-100 h-100" style="object-fit: contain">
                                `   
        }else{
            divModB.innerHTML = `
                                    <iframe class="w-100 h-100 position-relative overflow-hidden" frameborder="0" allowfullscreen src="${this.arquivoP}">
                                    </iframe>
                                `  
        }                    
        
        
        
        this.modal.show()
    }
}

class MontaAnexo{
    constructor(anexo, pai){
        this.anexo = anexo
        this.pai = pai
        this.item = this.pai.item
        this.modal = this.pai.modalAnexo
        return this.criar.bind(this)()
    }

    criar(){
        let anexo = this.anexo
        
        this.tr = document.createElement('tr')
        
        let nome = anexo.nome && anexo.nome.length > 0 ? anexo.nome : ''
        this.tr.setAttribute('data-nome', nome)
        
        let arquivo = anexo.documento && JSON.parse(anexo.documento).length > 0 ? JSON.parse(anexo.documento)[0] : []
        this.arquivoP = `${dominio}/conteudo/uploads/${arquivo}` 
        let dataCriacao = anexo.dataCriacao && anexo.dataCriacao.length > 0 ? transformarData(anexo.dataCriacao.split(' ')[0]) : ''
        
        this.tr.innerHTML = this.pai.montandoLinha(nome, arquivo, dataCriacao)
                       
        evento(this.tr.getElementsByClassName('btn-modal')[0], 'click', this.passaDados.bind(this))
        evento(this.tr.getElementsByClassName('btn-trash')[0], 'click', this.excluirAnexo.bind(this))
                       
        return this.tr
    }
    
    excluirAnexo(){
        Swal.fire({
          title: "Você tem certeza?",
          text: "Voce não será capaz de recuperar este dado!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "var(--color-2)",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, excluir!",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
                this.excluindoAnexo.bind(this)()
          }
        })
        
    }
    
    excluindoAnexo(){
        let id = retornaId(this.item)
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'apagarAnexo',
            'id': id,
            'id_anexo': this.anexo.id
        })
        request.send().then((r) =>{
            iziToast.success({
                message: "Anexo excluido com sucesso!",
                timeout: 5000,
                position: 'topRight',
             })
  
               
                this.pai.montaLinhaSemReg.bind(this)(this.tr)
                this.tr.remove()
             
        }, (r) => {
            iziToast.error({
                message: "Falha ao excluir sua anexo!",
                timeout: 5000,
                position: 'topRight',
             })
        })    
    }
    
    passaDados(){
        
        var divMod = document.getElementById('modalAnexoContent')
        var divModH = divMod.getElementsByClassName('modal-title')[0]
        var divModB = divMod.getElementsByClassName('modal-body')[0]
        
        divModH.innerHTML = `
                                ${this.anexo.nome}
                            `
        
        if(this.arquivoP.includes('imagens')){
            divModB.innerHTML = `
                                    <img src="${this.arquivoP}" class="w-100 h-100" style="object-fit: contain">
                                `   
        }else{
            divModB.innerHTML = `
                                    <iframe class="w-100 h-100 position-relative overflow-hidden" frameborder="0" allowfullscreen src="${this.arquivoP}">
                                    </iframe>
                                `  
        }                    
        
        
        
        this.modal.show()
    }
}

class MontaAnotacao{
    constructor(anotacao, pai){
        this.anotacao = anotacao
        this.pai = pai
        this.item = this.pai.item
        return this.criar.bind(this)()
    }
    
    criar(){
        let anotacao = this.anotacao
        
        let texto = anotacao.texto && anotacao.texto.length > 0 ? anotacao.texto : ''
        let dataCriacao = anotacao.dataCriacao && anotacao.dataCriacao.length > 0 ? transformarData(anotacao.dataCriacao.split(' ')[0]) : ''
        let autor = anotacao.autor.display && anotacao.autor.display.length > 0 ? anotacao.autor.display : ''
        
        this.div = document.createElement('div')
        this.div.classList.add('col-lg-6')
        
        this.div.innerHTML = `
                            <div class="anotacao position-relative">
                              <div class="overflow-text show me-3">
                                <p class="anot-text">${texto}</p>
                              </div>
                              <div class="content data"><span><i class="bi bi-calendar3"></i></span><p>${dataCriacao}</p></div>
                              <div class="footer-anot">
                                <div><i class="bi bi-person-fill"></i><span>Criado por: </span><span class="user">${autor}</span></div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-edit-anotacao"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                <div>
                              </div>
                              <div class="position-absolute end-0 top-0 mt-3">
                                <button class="btn btn-overflow"><i class="bi bi-chevron-down fs-18"></i></button>    
                              </div>
                            </div>
                        `
        
        evento(this.div.getElementsByClassName('btn-trash')[0], 'click', this.excluirAnotacao.bind(this))
        evento(this.div.getElementsByClassName('btn-edit-anotacao')[0], 'click', this.passaDados.bind(this))
        var paragrafo = this.div.getElementsByClassName('anot-text')[0]
        
        if(paragrafo.innerHTML.length < 200){
            this.div.getElementsByClassName('btn-overflow')[0].remove()
        }else{
            evento(this.div.getElementsByClassName('btn-overflow')[0], 'click', this.verMais.bind(this))    
        }
        
        return this.div
    }
    
    excluirAnotacao(){
        Swal.fire({
          title: "Você tem certeza?",
          text: "Voce não será capaz de recuperar este dado!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "var(--color-2)",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, excluir!",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
                this.excluindoAnotacao.bind(this)()
          }
        })
        
    }
    
    excluindoAnotacao(){
        let id = retornaId(this.item)
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': 'apagarAnotacao',
            'id': id,
            'id_anotacao': this.anotacao.id
        })
        request.send().then((r) =>{
            iziToast.success({
                message: "Anotação excluida com sucesso!",
                timeout: 5000,
                position: 'topRight',
             })
             this.div.remove()
        }, (r) => {
            iziToast.error({
                message: "Falha ao excluir sua anotação!",
                timeout: 5000,
                position: 'topRight',
             })
        })
        
    }
    
    passaDados(){
        this.pai.idAnotacao = this.anotacao.id
        this.pai.classeAnotacao = this
        
        document.getElementById('textoAnotacao').value = this.anotacao.texto
        
        this.pai.modalAnotacao.show()
    }
    
    verMais(){
        var textOverflow = this.div.getElementsByClassName('show')[0]
        
        
        if(textOverflow.classList.contains('overflow-text')){
            textOverflow.classList.remove('overflow-text')
            event.currentTarget.classList.add('rotate') 
        }else{
            textOverflow.classList.add('overflow-text')
            event.currentTarget.classList.remove('rotate')
        }
    }
}

class MontaTarefa{
    constructor(tarefa, pai, infos){
        this.tarefa = tarefa
        this.pai = pai
        this.item = this.pai.item
        this.tarefaPosicao= infos[0]
        this.tarefaNumero = infos[1]
        return this.criar.bind(this)()
    }
    
    criar(){
        let tarefa = this.tarefa
        
        var tr = document.createElement('tr')
        let nome = tarefa.nome ? tarefa.nome : ''
        let licitacao = this.item.numero.numero_processo ? this.item.numero.numero_processo : ''
        let data = tarefa.prazo ? transformarData(tarefa.prazo) : false
        let prioridade = tarefa.prioridade ? tarefa.prioridade : false
        let dataCriacao = tarefa.dataCriacao ? tarefa.dataCriacao : false
        let tipo, porcentagem, cor, bgConcluir

        bgConcluir = 'bg-success'
        
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
            bgConcluir = 'bg-danger'
            porcentagem = 100
            tempoResta = `<div class="restam"><span class="time">Concluído</span></div>`
        }
        
        tr.innerHTML = `
                        <th scope="row"><button type="button" class="eye btn botaoTarefa" ><i class="bi bi-eye"></i></button></th>
                        <td><span class="nome">${nome}</span></td>
                        <td><span>${licitacao}</span></td>
                        <td>
                            <div class="progresso">
                                <div class="labels"><span class="data">${data}</span>${tempoResta}</div>
                                <div class="progress progress-barra ${cor}" role="progressbar" aria-label="Basic example" aria-valuenow="${porcentagem}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: ${porcentagem}%"></div>
                                </div>
                            </div>
                        </td>
                        <td><div class="notificacao ${tipo}">${prioridade}</div></td>
                        <td>
                            <button class="w-100 btn btn-concluir ${bgConcluir} text-white" tooltip="Concluir Tarefa"><i class="bi bi-check-circle"></i></button>
                        </td>
                       `
                       
        if(!parseInt(tarefa.andamento)){
            tarefa.prioridade = prioridade 
        }                       
                       
        evento(tr.getElementsByClassName('botaoTarefa')[0], 'click', this.passaDados.bind(this))
        evento(tr.getElementsByClassName('btn-concluir')[0], 'click', () => {
            let andamento
            if(parseInt(this.tarefa.andamento)){
                andamento = 0
            }else{
                andamento = 1
            }
            this.concluirTarefa.bind(this)(this.tarefa.id, andamento)
        })
        
        return tr   
    }
    
    concluirTarefa(id, andamento){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        request.addData({'acao': 'statusTarefa',
            'id_tarefa': id,
            'andamento': andamento,
            'id': this.item.id
        })
        request.send().then( (r)=>{
            this.pai.idTarefa = this.tarefa.id
            this.pai.tarefaPosicao = this.tarefaPosicao
            this.pai.tarefaNumero = this.tarefaNumero
            
            this.pai.mudarStatus(andamento)
            iziToast.success({
               message: "Tarefa concluida com sucesso!",
               timeout: 5000,
               position: 'topRight',
            })
        }, (r) => {
            iziToast.error({
                message: "Erro ao concluir a tarefa!",
                timeout: 5000,
                position: 'topRight',
             })
        })
    }
    
    passaDados(){
        this.pai.acaoTarefa = 'editarTarefa'

        this.pai.idTarefa = this.tarefa.id
        this.pai.tarefaPosicao = this.tarefaPosicao
        this.pai.tarefaNumero = this.tarefaNumero

        
        var modal = document.getElementById('staticBackdrop2')
        document.getElementById('staticBackdropLabel').innerHTML = `Tarefa: <span class="nome-formulario">${this.tarefa.nome}</span>`
        var inputs = modal.getElementsByClassName('form-control')
        var selects = modal.getElementsByClassName('form-select')
        
        var btnConcluir = modal.getElementsByClassName('btn-concluir-tarefa')[0]
        
        modal.getElementsByClassName('nome-subtarefa')[0].value = ''
        modal.getElementsByClassName('data-subtarefa')[0].value = ''
        modal.getElementsByClassName('btn-excluir-tarefa')[0].removeAttribute('disabled')
        modal.getElementsByClassName('btn-excluir-tarefa')[0].classList.remove('d-none')
        btnConcluir.removeAttribute('disabled')
        btnConcluir.classList.remove('d-none')
        
        if(parseInt(this.tarefa.andamento)){
            btnConcluir.innerText = 'Retomar tarefa'
            this.pai.tarefaAndamento = 0
        }else{
            btnConcluir.innerText = 'Concluir tarefa'
            this.pai.tarefaAndamento = 1
        }
    
        
        var i = 0
        while(i < inputs.length){
            var nome = inputs[i].name
            if (this.tarefa.hasOwnProperty(nome)) {
                if(nome == 'prazo'){
                    inputs[i].value = this.tarefa[nome].replace(' ', 'T')
                } else{
                    inputs[i].value = this.tarefa[nome]
                }
            }
            i++
        }
        
        i = 0
        while(i < selects.length){
            var nome = selects[i].name
            if (this.tarefa.hasOwnProperty(nome)) {
                selects[i].value = this.tarefa[nome]
            }
            i++
        }
        
        var selectUsuarios = document.getElementById('select-usuario')

        var event = new Event('change')

        selectUsuarios.dispatchEvent(event)
        
        var div = document.getElementById('subtarefasW')
        
        div.innerHTML = ``
        
        var subtarefas = JSON.parse(this.tarefa.subtarefas)
        i = 0
        while(i < subtarefas.length){
            div.appendChild(new MontaSubtarefa(subtarefas[i], this.tarefa))
            i++
        }
        
        this.pai.modalTarefas.show()
    }
}

class AcessosLicitacoes{
    constructor(lista, autor){
        this.lista = lista
        this.autor = autor
        this.autor['desativado'] = true
        this.autor['selecionado'] = true
        this.usuario = infoUser().id
        this.div = document.getElementById('lista-de-usuarios')
        this.modal = new bootstrap.Modal('#modal-quadro', {
          keyboard: false
        })
        
        this.inicio.bind(this)()
        
        this.preencherSelectUsuarioTarefa.bind(this)()
        evento(document.getElementById('quadro-de-acessos'), 'click', this.separar.bind(this))
    }
    
    inicio(){
        this.usuarios = []
        this.usuarios.push(this.autor)
        var i = 0
        
        while(i < this.lista.length){
            if(this.autor.user != this.lista[i].usuario.user){
                this.lista[i].usuario['selecionado'] = false
                this.usuarios.push(this.lista[i].usuario)
                
            }
            
            i++
        }
        
    }
    
    separar(){
        this.inicio.bind(this)()
        
        this.abrirModal.bind(this)()
    }
    
    preencherSelectUsuarioTarefa(){
        var select = document.getElementById('usuarios-select-tipo')
        
        var i = 0
        
        while(i < this.usuarios.length){
            var option = document.createElement('option')
            option.innerText = this.usuarios[i].display
            option.value = this.usuarios[i].user
            select.appendChild(option)
            i++
        }
    }
    
    abrirModal(){
        var api2 = new ApiNown('licitacoes', 'zcantt1cqFJBYdr')
        api2.isNew()
        api2.setHash(caminho.hash())
        api2.send().then((r) => {
            if(r.item){
                var visualizadores = r.item.visualizadores
                var visualizadores =  visualizadores && JSON.parse(visualizadores) && JSON.parse(visualizadores).length > 0  ? JSON.parse(visualizadores) : []
                var api = new ApiNown('usuarios', 'cBWbdxSMQd0ZSQt')
                api.isNew()

                api.setItens(visualizadores)
                api.send().then((r)=>{
                    if(r.lista && r.lista.length > 0){
                        var lista = r.lista
                    }else{
                        var lista = []
                    }

                    var i = 0
            
                    while(i < lista.length){
                        var j = 0
                    
                        while(j < this.usuarios.length){
                            if(lista[i].user == this.usuarios[j].user){
                                this.usuarios[j].selecionado = true
                                break
                            }
                            j++
                        }
                        
                        i++
                    }
                    
                    this.formarModal.bind(this)()
                }, (r)=>{
                    this.formarModal.bind(this)()
                })
            }
        }, (r)=>{
            console.log(r)
        })
        
        
    }
    
    formarModal(){
        var i = 0
        this.div.innerHTML = ''
        while(i < this.usuarios.length){
            this.div.appendChild(new UsuariosItem(this.usuarios[i]))
            i++
        }
        
        this.modal.show()
    }
}

class UsuariosItem{
    constructor(usuario){
        this.dado = usuario
        return this.criar.bind(this)()
    }
    
    criar(){
        var li = document.createElement('li')
        li.classList.add('list-group-item', 'd-flex', 'align-items-center', 'justify-content-between')
        
        if(this.dado.selecionado){
            var checkado = 'checked'
        }else{
            var checkado = ''
        }
        
        if(this.dado.desativado){
            var desativado = 'disabled'
        }else{
            var desativado = ''
        }
        
        li.innerHTML = `
            ${this.dado.display}
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" ${desativado} ${checkado}>
            </div>
            
        `
        evento(li.getElementsByClassName('form-check-input')[0], 'change', this.mudando.bind(this))
        return li
    }
    
    mudando(){
        var evento = event.currentTarget
        
        
        if(this.dado.desativado){
            evento.checked = true
            iziToast.error({
                message: 'Esse usuário é o autor, ele não pode ser desvinculado',
                timeout: 5000,
                position: 'topRight',
            })
            return 
        }
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        var data = {
            'id' : pegaHash(),
            'usuario': this.dado.user
        }
        
        evento.setAttribute('disabled', true)
        if(evento.checked){
            data['acao'] = 'liberarLicitacao'
            var mensagem = 'Liberação Salva'
  
        }else{
            data['acao'] = 'bloquearLicitacao'
            var mensagem = 'Bloqueio Salvo'
        }
        
        request.addData(data)
        request.send().then((r)=>{
            evento.removeAttribute('disabled')
            iziToast.success({
                message: mensagem,
                timeout: 5000,
                position: 'topRight',
            })
        }, (r)=>{
            iziToast.error({
                message: 'Erro ao realizar ação, tente de novo mais tarde ou contate o suporte',
                timeout: 5000,
                position: 'topRight',
            })
            evento.removeAttribute('disabled')
        })
    }
}

class ItemLicitacao{
    constructor(){
        this.modalTarefas =  new bootstrap.Modal('#staticBackdrop2', {
          keyboard: false
        })
        this.modalAnotacao =  new bootstrap.Modal('#modalAnotacao', {
          keyboard: false
        })
        this.modalAnexo = new bootstrap.Modal('#modalAnexo', {
            keyboard: false
        })
        this.modalUploader = new bootstrap.Modal('#exampleModal', {
            keyboard: false
        })
        
        
        
        this.habilitacoes = []
        this.acaoTarefa = false
        this.idTarefa = false
        this.idAnotacao = false
        
        evento(document.getElementsByClassName('btn-criar-tarefa')[0], 'click', this.adicionarTarefa.bind(this))
        evento(document.getElementsByClassName('btn-salvar-tarefa')[0], 'click',  this.salvarTarefa.bind(this))
        evento(document.getElementsByClassName('btn-excluir-tarefa')[0], 'click',  this.excluirTarefa.bind(this))
        evento(document.getElementsByClassName('btn-concluir-tarefa')[0], 'click',  this.concluirTarefa.bind(this))
        evento(document.getElementById('btn-subtarefa'), 'click', this.adicionaSubTarefa.bind(this))
        evento(document.getElementById('btn-send-anotacao'), 'click', this.adicionaAnotacao.bind(this))
        evento(document.getElementById('btn-confirma-anotacao'), 'click', this.editarAnotacao.bind(this))
        evento(Array.from(document.getElementsByClassName('anexoUploader')), 'click', this.upload.bind(this))
        evento(document.getElementById('btn-salva-upload'), 'click', this.salvaUpload.bind(this))
        evento(document.getElementsByClassName('download-anexo')[0], 'click', this.baixarAnexos.bind(this))
        evento(document.getElementsByClassName('download-habilitacao')[0], 'click', this.baixarHabilitacoes.bind(this))
        
        this.selectUsuarios.bind(this)()
        
        this.fragmento = document.createDocumentFragment()
        this.fragmento3 = document.createDocumentFragment()
        this.fragmento4 = document.createDocumentFragment()
        this.fragmento5 = document.createDocumentFragment()

        this.tabelaAnotacoes = document.getElementById('tabelaAnotacoes')
        this.tabelaAnexos = document.getElementById('tabelaAnexos')
        this.tabelaHabilitacao = document.getElementById('tabelaHabilitacoes')
        var div = document.getElementById('boletinsWrap')
        
        this.tabelaAnotacoes.innerHTML = ''
        this.tabelaAnexos.innerHTML = ''
        this.tabelaHabilitacao.innerHTML = ''

        this.api = new ApiNown('licitacoes', 'zcantt1cqFJBYdr')
        this.api.isNew()
        this.api.setHash(caminho.hash())
        this.api.send().then( async (r) => {
            if(r.sucesso){
                this.item = r.item
                this.autor = r.item.autor
                // await this.montaLicitacao.bind(this)(r.item.numero)
                this.preencherModalTarefa.bind(this)()
                this.acessoEdistribuicao.bind(this)()
                this.fragmento = await new CardPadrao2(r.item.numero)
                
                await this.tarefa.bind(this)()
                await this.anotacoes.bind(this)()
                await this.anexos.bind(this)()
                await this.habilitacao.bind(this)()
            }else{
                naoExiste()
            }
            
            div.innerHTML = ''
            div.appendChild(this.fragmento)
            
            if(document.getElementById('boletinsWrap').getElementsByClassName('vincular').length > 0){
                document.getElementById('boletinsWrap').getElementsByClassName('vincular')[0].remove()
            }
           
            this.tabelaAnotacoes.appendChild(this.fragmento3)
            this.tabelaAnexos.appendChild(this.fragmento4)
            this.tabelaHabilitacao.appendChild(this.fragmento5)
            
            this.montaLinhaSemReg.bind(this)(this.tabelaAnexos)
            this.montaLinhaSemReg.bind(this)(this.tabelaHabilitacao)
            
            this.pesquisar.bind(this)()
            new Favoritos()
        },(r) => {
            naoExiste()
        })
        
        this.uploader = new Uploader(document.getElementById("uploader"))
        this.uploader.scriptsCode().then(()=>{
            this.uploader.render()
            var i = 0
            var btnsAdicionar = document.getElementsByClassName('anexoUploader')
            while(i < btnsAdicionar.length){
                btnsAdicionar[i].removeAttribute('disabled')
                i++
            }
            document.getElementById("uploader").classList.remove("d-none")
        })
        
        
        this.reabrirUploader = false
        
        evento(document.getElementById('modalAnexo'), 'hidden.bs.modal', () => {
             if (this.reabrirUploader) {
                this.reabrirUploader = false // Reseta flag
                
                console.log(this.reabrirUploader)
                
                this.modalUploader.show()
            }
        })
    }
    
    acessoEdistribuicao(){
        var api = new ApiNown('empresas', 'vwVL3BucxAlYUDA')
        api.isNew()
        
        var local = JSON.parse(pegaLocal("nown"))

        if(local.sub){
            var empresa = local.sub.id
            api.setExtra(empresa)
            api.send().then((r)=>{
                if(r.lista && r.lista.length > 0){
                    new AcessosLicitacoes(r.lista, this.autor)
                }
            }, (r)=>{
                console.log(r)
            })
        }
    }
    
    montandoLinha(nome, arquivo, dataCriacao){
        return `<th scope="row">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                </th>
                <td class="nome">${nome}</td>
                <td class="arquivo">${arquivo}</td>
                <td>${dataCriacao}</td>
                <td>
                    <div class="options">
                        <button class="btn btn-modal">
                            <div class="eye">
                                <i class="bi bi-eye"></i>
                            </div>
                        </button>
                        <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></butto>
                    </div>
                </td>`
    }
    
    baixarAnexos(){
        let linhas = Array.from(this.tabelaAnexos.getElementsByTagName('tr'))
        let i = 0
        let arquivos = []
        

        while (i < linhas.length) {
            let input = linhas[i].getElementsByTagName('input')

            if (input.length > 0 && input[0].checked) {
                arquivos.push(linhas[i])
            }
            
            i++
        }
        
        baixarArquivos(arquivos)
    }
    
    baixarHabilitacoes(){
        let linhas = Array.from(this.tabelaHabilitacao.getElementsByTagName('tr'))
        let i = 0
        let arquivos = []
        

        while (i < linhas.length) {
            let input = linhas[i].getElementsByTagName('input')

            if (input.length > 0 && input[0].checked) {
                arquivos.push(linhas[i])
            }
            
            i++
        }
        
        baixarArquivos(arquivos)
        
    }
    
    preencherModalTarefa(){
        let modal = document.getElementById('staticBackdrop2')
        let modalTopo = modal.getElementsByClassName('modal-topo')[0]
        
        let objeto = __getDados(this.item.numero, 'objeto')
        
        let edital = __getDados(this.item.numero, 'numeroCompra')
        let orgao = __getDados(this.item.numero, 'unidadeOrgaoNomeUnidade')
        
        modalTopo.innerHTML = `
                                    <div class="content"><span>Objeto:</span><p>${objeto}</p></div>
                                    <div class="content"><span>N° da Licitação:</span><p>${edital}</p></div>
                                    <div class="content"><span>Orgão:</span><p>${orgao}</p></div> `
    }
    
    async montaLicitacao(licitacao){
        
        var cabecalho = document.createElement('div')
        var infos = document.createElement('div')
        var actions = document.createElement('div')
        cabecalho.classList.add('cabecalho')
        infos.classList.add('infos')
        actions.classList.add('actions','mt-3')
        let modal = document.getElementById('staticBackdrop2')
        let modalTopo = modal.getElementsByClassName('modal-topo')[0]
        
        var ulItems = document.createElement('ul')
        ulItems.classList.add('dropdown-menu')
        
        var apiOrgao = new ApiNown('licitacoes','HKN3sqJBWleAyob')
        apiOrgao.setHash(licitacao.unidade_gestora)
        
        try{
            let r = await apiOrgao.send()
            
            this.orgao = r.item
            
            let orgao = this.orgao.nome && this.orgao.nome.trim() != '' ? this.orgao.nome : ''
            
            let items = JSON.parse(licitacao.itens)
            
            this.itemsLicitacao = items
            
            let i = 0
            
            while(i < items.length){
                var apiItem = new ApiNown('licitacoes', '3TxiLViAgnrrpWm')
                apiItem.setHash(items[i])
                
                try{
                    let r = await apiItem.send()
                    let item = r.item
                    let li = document.createElement('li')
                    
                    li.innerHTML = `<span class="dropdown-item">${item.desc} / Quantidade: ${item.quantidade}</span>`
                    
                    ulItems.appendChild(li)
                }catch(e){
                    console.log(e)
                }
                
                i++
            }
            
            actions.innerHTML = `
                                    <button class="btn download"><i class="bi bi-download"></i><span class="text">Baixar edital</span></button>
                                    <div class="dropdown">
                                        <button class="btn itens h-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text">Itens</span></i>
                                        </button>
                                        ${ulItems.outerHTML}
                                    </div>
                                `
            
            let update = licitacao.att && licitacao.att.trim() != '' ? transformarData(licitacao.att.replace(/T/g, ' ')) : '' 
            
            let objeto = licitacao.objeto && licitacao.objeto.trim() != '' ? licitacao.objeto : false 
            objeto = objeto.replace('Objeto: ', '')
            
            let abertura = licitacao.data_abertura_proposta && licitacao.data_abertura_proposta.trim() != '' ? transformarData(licitacao.data_abertura_proposta) : "Data não informada" 
            
            let edital = licitacao.numero_processo && licitacao.numero_processo.trim() != '' ? licitacao.numero_processo : ''
            
            let cidade = this.orgao.nomeMunicipioIbge && this.orgao.nomeMunicipioIbge.trim() != '' ? this.orgao.nomeMunicipioIbge : ''
            let uf = this.orgao.siglaUf && this.orgao.siglaUf.trim() != '' ? this.orgao.siglaUf : ''
            let localizacao = `${cidade} - ${uf}`
            
            let situacao = licitacao.situacao_aviso ? licitacao.situacao_aviso : '' 
            
            let valorEst = licitacao.valor_homologado_total && licitacao.valor_homologado_total.trim() != '' ? trataRS(licitacao.valor_homologado_total) : trataRS('0')
            
            cabecalho.innerHTML = `
                                    <button class="btn favorite"><i class="bi bi-star"></i>Favoritar</button>
                                    <div class="att">
                                        <span class="atualizada">Atualizada em: </span><span class="data">${update}</span>
                                    </div>
                                  `
                                  
            infos.innerHTML = `
                                <div class="content"><span>Objeto:</span><p>${objeto}</p></div>
                                <div class="content"><span>Data de Abertura:</span><p>${abertura}</p></div>
                                <div class="content"><span>Edital:</span><p>${edital}</p></div>
                                <div class="content"><span>Orgão:</span><p class="text-uppercase">${orgao}</p></div>
                                <div class="content"><span>Endereço Edit.:</span><p>${localizacao}</p></div>
                                <div class="content"><div class="bout-valor"><span>Situação: </span><div class="notificacao" style="color: black">${situacao}</div><div class="valor-estimado">Valor estimado: <span class="valor">R$ ${valorEst}</span></div></div></div>
                              `
            
            evento(actions.getElementsByClassName('download')[0], 'click', () => {
                this.baixaEdital.bind(this)()
            }) 
                             
            this.fragmento.appendChild(cabecalho)
            this.fragmento.appendChild(infos)
            this.fragmento.appendChild(actions)
            
            //modal
            
            modalTopo.innerHTML = `
                                    <div class="content"><span>Objeto:</span><p>${objeto}</p></div>
                                    <div class="content"><span>Edital:</span><p>${edital}</p></div>
                                    <div class="content"><span>Orgão:</span><p>${orgao}</p></div>
                                  `
        }
        catch(e){
            console.log(e)
        }

    }
    
    removerDuplicadosPorId(array) {
        let idsVistos = new Set() // Usado para armazenar IDs já vistos
        return array.filter(item => {
            // Verifica se o id já foi adicionado ao Set
            if (idsVistos.has(item.id)) {
                return false // Se o ID já foi visto, ignora o item (duplicado)
            } else {
                idsVistos.add(item.id) // Adiciona o novo ID ao Set
                return true // Mantém o item no array
            }
        })
    }
    
    removerItemPorId(array, id){
        return array.filter(item => item.id !== id)
    }
    
    removerItemPorIdSemObjeto(array, id){
        return array.filter(item => parseInt(item) !== id)
    }
    
    //Baixar EDITAL
    async baixaEdital(){
        let edital = {}
        
        edital.numero_processo = this.item.numero.numero_processo
        edital.objeto = this.item.numero.objeto
        edital.nome_modalidade = this.item.numero.nome_modalidade
        edital.tipo_pregao = this.item.numero.tipo_pregao
        edital.situacao_aviso = this.item.numero.situacao_aviso
        edital.valor_homologado_total = this.item.numero.valor_homologado_total
        edital.data_abertura_proposta = this.item.numero.data_abertura_proposta
        edital.data_entrega_proposta = this.item.numero.data_entrega_proposta
        edital.nome_responsavel = this.item.numero.nome_responsavel
        edital.funcao_responsavel = this.item.numero.funcao_responsavel
        edital.unidade_gestora = this.item.numero.unidade_gestora
        edital.nome = this.orgao.nome
        edital.siglaUf = this.orgao.siglaUf
        edital.nomeMunicipioIbge = this.orgao.nomeMunicipioIbge
        edital.codigoMunicipioIbge = this.orgao.codigoMunicipioIbge
        edital.nomeUnidadePolo = this.orgao.nomeUnidadePolo
        edital.codigoUnidadePolo = this.orgao.codigoUnidadePolo
        edital.cnpjCpfUasg = this.orgao.cnpjCpfUasg
        edital.orgao = this.orgao.orgao
        edital.items = {}
        
        let i = 0
        
        while(i < this.itemsLicitacao.length){
            var apiItem = new ApiNown('licitacoes', '3TxiLViAgnrrpWm')
            apiItem.setHash(this.itemsLicitacao[i])
            
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
    
    // tarefas
    async tarefa(){
        if(isJsonString(this.item.tarefas)){
            var tarefa = JSON.parse(this.item.tarefas)
            if(tarefa.length > 0){
                await this.listarTarefas.bind(this)(tarefa)    
            }else{
                await this.listarTarefas.bind(this)([])   
            }
        }else{
            await this.listarTarefas.bind(this)([])  
        }
    }
    
    async listarTarefas(items){
        this.acoesTab = ['dia', 'atrasado', 'progresso', 'concluida']
        this.tarefas = {}
        this.todasTarefas = []
        var j = 0
        while (j <  this.acoesTab.length){
            this.tarefas[this.acoesTab[j]] = []
            j++
        }
        
        var apiTarefa = new ApiNown('licitacoes', 'N9bI36ltk5LG5Dy')
        apiTarefa.isNew()

        apiTarefa.setItens(items)
        
        try{
            var resposta = await apiTarefa.send()
            var task = resposta.lista
        }catch(error){
            var task = []
            console.log(error)
        }
        
        var tarefas = []
        var i = 0

        while(i < task.length){
            // var apiTarefa = new ApiNown('licitacoes', 'N9bI36ltk5LG5Dy')
            // apiTarefa.setHash(task[i])
            // await apiTarefa.send().then((r) => {
            //     if(r.sucesso && r.item){
                    tarefas.push(task[i])
            //     }
            // })
            i++
        }
        
        nownFiles.add(['https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', 
        'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js']).then(()=>{
            // Carrega o FullCalendar
            this.botaosTipo = document.getElementsByClassName('btn-escolha-calendario');
            evento(Array.from(this.botaosTipo), 'click', this.escolhendoCalendario.bind(this));
            this.tipoCalendario = 1; 
            this.usuariosTotal = []; 
            this.responsavelSelect = document.getElementById('responsavel');
            this.statusSelect = document.getElementById('status-tarefas');
            this.separarTarefas.bind(this)(tarefas);
            this.filtroTarefas.bind(this)();
            this.simulacaoCliqueTab.bind(this)();
            this.criarMiniCalendario.bind(this)();
        
            evento(document.getElementById('anteriorBotao'), 'click', () => {
                this.miniCalendar.prev();
                this.definirDataMiniCalendario.bind(this)();
            });
        
            evento(document.getElementById('nextButton'), 'click', () => {
                this.miniCalendar.next();
                this.definirDataMiniCalendario.bind(this)();
            });
        
            evento(this.responsavelSelect, 'change', this.selecionandoUsuario.bind(this));
            evento(this.statusSelect, 'change', this.selecionandoStatus.bind(this));
        })
    }
    
    selecionandoUsuario() {
        this.criarCalendario.bind(this)();
    }

    selecionandoStatus() {
        this.criarCalendario.bind(this)();
    }

    definirDataMiniCalendario() {
        const mesAnoDisplay = document.getElementById('mesAnoDisplay');
        const dataAtual = this.miniCalendar.getDate();
    
        if (!(dataAtual instanceof Date)) {
            console.error('dataAtual não é um objeto Date:', dataAtual);
            return;
        }
    
        const opcoes = { year: 'numeric', month: 'long' };
        const mesAnoFormatado = dataAtual.toLocaleDateString('pt-BR', opcoes);
        mesAnoDisplay.textContent = mesAnoFormatado;
    }
    
    definirParametrosCalendariosTask(tarefa){
        for(let numero in this.tarefas){
            var posicao = this.tarefas[numero]
            var dado1 = numero
            let achado = false
            for(let dado in posicao){
                var dado2 = dado
                if(posicao[dado].id == tarefa.id){
                    achado = true
                    break
                }
            }
            if(achado){
                break;
            }
        }
        const cor = this.corNocalendario(tarefa);
        tarefa['numero']= dado2
        tarefa['posicao'] = dado1
        return {
            id: tarefa.id,
            title: tarefa.nome,
            start: new Date(tarefa.prazo).toISOString(),
            end: new Date(new Date(tarefa.prazo).setHours(new Date(tarefa.prazo).getHours() + 1)).toISOString(),
            backgroundColor: `var(--color-${cor})`,
            textColor: '#fff',
            extendedProps: {
                raw: tarefa
            },
            classNames: [`fc-event-${cor}`] // Adiciona uma classe específica para o evento
        };
    }

    criarMiniCalendario() {
        if (!this.todasTarefas || this.todasTarefas.length === 0) {
            console.error('Nenhuma tarefa encontrada.');
        }
    
        const eventos = this.todasTarefas.map(tarefa => {
            return this.definirParametrosCalendariosTask.bind(this)(tarefa)
        });

        // Verifica se o mini calendário já foi inicializado
        if (this.miniCalendar) {
            this.miniCalendar.destroy(); // Destrói o calendário existente antes de criar um novo
        }
    
        // Inicializa o mini calendário
        this.miniCalendar = new FullCalendar.Calendar(document.getElementById('mini-calendar'), {
            initialView: 'dayGridMonth',
            events: eventos,
            locale: 'pt-br',
            eventContent: function(arg) {
                return { html: `<span class="task">${arg.event.title}</span>` };
            },
            headerToolbar: false,
            height: 'auto',
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            dateClick: (info) => {
                // Remove a classe de destaque de qualquer dia previamente selecionado
                const diasSelecionados = document.querySelectorAll('#mini-calendar .fc-day-selected');
                diasSelecionados.forEach(dia => dia.classList.remove('fc-day-selected'));
    
                // Adiciona a classe de destaque ao dia clicado
                info.dayEl.classList.add('fc-day-selected');
    
                // Atualiza a data selecionada
                this.dataSelecionada = info.date;
                this.tipoCalendario = 1;
                this.definicaoData.bind(this)();
    
                // Navega para a data clicada
                this.miniCalendar.gotoDate(this.dataSelecionada);
    
                // Retorna false para evitar comportamentos padrão do FullCalendar
                return false;
            },
            eventClick: (info) => {
                // Clique em uma tarefa (evento)
                // Remove a classe de destaque de qualquer dia previamente selecionado
                const diasSelecionados = document.querySelectorAll('#mini-calendar .fc-day-selected');
                diasSelecionados.forEach(dia => dia.classList.remove('fc-day-selected'));
    
                // Adiciona a classe de destaque ao dia da tarefa clicada
                const diaTarefa = document.querySelector(`#mini-calendar .fc-day[data-date="${info.event.start.toISOString().slice(0, 10)}"]`);
                if (diaTarefa) {
                    diaTarefa.classList.add('fc-day-selected');
                }
    
                // Atualiza a data selecionada
                this.dataSelecionada = info.event.start;
                this.tipoCalendario = 1;
                this.definicaoData.bind(this)();
    
                // Navega para a data da tarefa clicada
                this.miniCalendar.gotoDate(this.dataSelecionada);
    
                // Retorna false para evitar comportamentos padrão do FullCalendar
                return false;
            },
            datesSet: (info) => {
                // Verifica se há uma data selecionada
                if (this.dataSelecionada) {
                    // Encontra o elemento do dia selecionado no mini calendário
                    const diaSelecionado = document.querySelector(`#mini-calendar .fc-day[data-date="${this.dataSelecionada.toISOString().slice(0, 10)}"]`);
                    if (diaSelecionado) {
                        // Adiciona a classe de destaque ao dia selecionado
                        diaSelecionado.classList.add('fc-day-selected');
                    }
                }
            }
        });
    
        this.miniCalendar.render();
    }

    definirCalendario() {
        var usuarios = [];
        var status = [];
        if (this.todasTarefas && this.todasTarefas.length > 0) {
            var i = 0;
            while (i < this.todasTarefas.length) {
                var variavel = false;
                if (parseInt(this.todasTarefas[i].andamento)) {
                    variavel = `1-Concluída`;
                } else {
                    variavel = `0-${this.todasTarefas[i].prioridade}`;
                }
    
                if (variavel && !status.includes(variavel)) {
                    status.push(variavel);
                }
    
                variavel = false;
    
                if (!parseInt(this.todasTarefas[i].usuario)) {
                    if (this.todasTarefas[i].nome_responsavel) {
                        variavel = `0-${this.todasTarefas[i].nome_responsavel}`;
                    }
                } else {
                    if (this.todasTarefas[i].usuario_display) {
                        variavel = `1-${this.todasTarefas[i].usuario_display}`;
                    }
                }
    
                if (variavel && !usuarios.includes(variavel)) {
                    usuarios.push(variavel);
                }
    
                i++;
            }
        }

        this.definirUsuariosCalendario.bind(this)(usuarios);
        this.definirStatusCalendario.bind(this)(status);
        this.criarCalendario.bind(this)();
    }

    definirStatusCalendario(status) {
        var i = 0;
        this.statusSelect.innerHTML = '';
    
        var option = document.createElement('option');
        option.innerText = 'Selecione uma opção';
        option.value = 0;
        this.statusSelect.appendChild(option);
    
        while (i < status.length) {
            var tipos = status[i].split('-');
            var texto = tipos[1];
            var option = document.createElement('option');
            option.innerText = texto;
            option.value = status[i];
            this.statusSelect.appendChild(option);
            i++;
        }
    }

    criarCalendario() {
        if (this.calendar) {
            this.calendar.destroy();
        }
        
        var tarefas = [];
        var responsavel = false
        if(this.responsavelSelect.value){
            var tipos = this.responsavelSelect.value.split('-')
            
            if(tipos.length == 2){
                responsavel = tipos[1]
            }
        }
        
        var status = false
        if(this.statusSelect.value){
            var tipos2 = this.statusSelect.value.split('-')
            
            if(tipos2.length == 2){
                status = tipos2[1]
            }
        }
        var i = 0
        while(i < this.todasTarefas.length){
            let prazo = new Date(this.todasTarefas[i].prazo); // Cria um objeto Date a partir do prazo
            
            // console.log(prazo)
            
            // Define a data de início como o prazo
            let start = prazo.toISOString(); // Data de início no formato ISO
            
            // Adiciona uma hora ao prazo para definir a data de término
            prazo.setHours(prazo.getHours() + 1); // Adiciona uma hora ao prazo
            let end = prazo.toISOString();
            
            var puxa = false
            
            if(!responsavel){
                puxa = true
            }else{
                if(((!parseInt(tipos[0]) && responsavel == this.todasTarefas[i].nome_responsavel) || (parseInt(tipos[0]) && responsavel == this.todasTarefas[i].usuario_display))){
                    puxa = true
                }
            }
            
            if(puxa){
                if(!status){
                    puxa = true
                }else{
                    if((parseInt(tipos2[0]) && parseInt(this.todasTarefas[i].andamento)) || (!parseInt(tipos2[0]) && this.todasTarefas[i].prioridade == status)){
                        puxa = true
                    }else{
                        puxa = false
                    }
                }
            }
        
            if(puxa){
                let tarefa = this.todasTarefas[i]
                tarefas.push(
                    this.definirParametrosCalendariosTask.bind(this)(tarefa)
                );
            }
            
            i++;
        }
        
        let tipo;
        switch (parseInt(this.tipoCalendario)) {
            case 1:
                tipo = 'timeGridDay';
                break;
            case 2:
                tipo = 'timeGridWeek';
                break;
            case 3:
                tipo = 'dayGridMonth';
                break;
        }
    
        this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: tipo,
            events: tarefas,
            eventContent: function(arg) {
                return { html: `<span class="task">${arg.event.title}</span>` };
            },
            eventClick: (info) => {
                this.calendarioModal.bind(this)(info.event.extendedProps.raw)
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
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']
        });
    
        this.calendar.render();
    
        if (this.dataSelecionada) {
            this.definicaoData.bind(this)();
        }
    }
    
    calendarioModal(tarefa){
        console.log(tarefa)
        this.acaoTarefa = 'editarTarefa'

        this.idTarefa = tarefa.id
        this.tarefaPosicao = tarefa.posicao
        this.tarefaNumero = tarefa.numero

        
        var modal = document.getElementById('staticBackdrop2')
        document.getElementById('staticBackdropLabel').innerHTML = `Tarefa: <span class="nome-formulario">${tarefa.nome}</span>`
        var inputs = modal.getElementsByClassName('form-control')
        var selects = modal.getElementsByClassName('form-select')
        
        var btnConcluir = modal.getElementsByClassName('btn-concluir-tarefa')[0]
        
        modal.getElementsByClassName('nome-subtarefa')[0].value = ''
        modal.getElementsByClassName('data-subtarefa')[0].value = ''
        modal.getElementsByClassName('btn-excluir-tarefa')[0].removeAttribute('disabled')
        modal.getElementsByClassName('btn-excluir-tarefa')[0].classList.remove('d-none')
        btnConcluir.removeAttribute('disabled')
        btnConcluir.classList.remove('d-none')
        
        if(parseInt(tarefa.andamento)){
            btnConcluir.innerText = 'Retomar tarefa'
            this.tarefaAndamento = 0
        }else{
            btnConcluir.innerText = 'Concluir tarefa'
            this.tarefaAndamento = 1
        }
    
        
        var i = 0
        while(i < inputs.length){
            var nome = inputs[i].name
            if (tarefa.hasOwnProperty(nome)) {
                if(nome == 'prazo'){
                    inputs[i].value = tarefa[nome].replace(' ', 'T')
                } else{
                    inputs[i].value = tarefa[nome]
                }
            }
            i++
        }
        
        i = 0
        while(i < selects.length){
            var nome = selects[i].name
            if (tarefa.hasOwnProperty(nome)) {
                selects[i].value = tarefa[nome]
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
            div.appendChild(new MontaSubtarefa(subtarefas[i], tarefa))
            i++
        }
        
        this.modalTarefas.show()
    }
    
    corNocalendario(tarefa){
        let tipo;
        if (parseInt(tarefa.andamento)) {
            tipo = 'concluida'; // Corrigido: aspas de fechamento
        } else {
            switch (tarefa.prioridade) {
                case 'Baixa':
                    tipo = 'baixa';
                    break;
                case 'Média':
                    tipo = 'media';
                    break;
                case 'Alta':
                    tipo = 'alta';
                    break;
                case 'Urgente':
                    tipo = 'urgente';
                    break;
            }
        }
        return tipo
    }

    definicaoData() {
        this.calendar.gotoDate(this.dataSelecionada);
        this.calendar.render();
    }
    
    async pegarNome(user) {
        var api = new ApiNown('usuarios', 'KCx660YQMFVbmNH');
        api.isNew();
        api.setExtra(user);
        try {
            var resposta = await api.send();
            console.log(resposta)
            if (resposta.lista.length > 0) {
                return resposta.lista[0].display;
            } else {
                return 'Não Identificado';
            }
        } catch (erro) {
            return 'Não Identificado';
        }
    }
    
    async definirUsuariosCalendario(usuarios) {
        var i = 0;
        this.responsavelSelect.innerHTML = '';
    
        var option = document.createElement('option');
        option.innerText = 'Selecione uma opção';
        option.value = 0;
        this.responsavelSelect.appendChild(option);
        console.log(usuarios)
        while (i < usuarios.length) {
            var tipos = usuarios[i].split('-');
            var texto = tipos[1];
    
            if (parseInt(tipos[0])) {
                texto = await this.pegarNome.bind(this)(tipos[1]);
            }
    
            var option = document.createElement('option');
            option.innerText = texto;
            option.value = usuarios[i];
            this.responsavelSelect.appendChild(option);
            i++;
        }
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
    
    separarTarefas(tarefas){
        var i = 0
        
        this.todasTarefas = []
        while(i < tarefas.length){
        this.todasTarefas.push(tarefas[i])
           this.separandoTarefas.bind(this)(tarefas[i])
    
            i ++
        }
        
        this.criarMiniCalendario.bind(this)()
        this.definirCalendario.bind(this)()
        
        this.contaTarefas.bind(this)()
        
    }
    
    separandoTarefas(tarefa){
        var j = 0
        
        
        while (j <  this.acoesTab.length){
            this.verificarPosicaoData.bind(this)(this.acoesTab[j])

            j++
        }
        
        let now = new Date()
        
        if(parseInt(tarefa.andamento)){
            this.tarefas.concluida.push(tarefa)
        }
        
        if(dataFormatada(transformarData(tarefa.prazo, false)).getTime() ==  new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime()){
             this.tarefas.dia.push(tarefa)
        }
        
        if(!parseInt(tarefa.andamento)){
            this.tarefas.progresso.push(tarefa)
        }
        
        if(dataFormatada(transformarData(tarefa.prazo)) < new Date() && !parseInt(tarefa.andamento)){
            this.tarefas.atrasado.push(tarefa)
        }
        
        var j = 0
        while (j <  this.acoesTab.length){
            
            this.verificarPosicaoData.bind(this)(this.acoesTab[j])

            j++
        }
        
    }
    
    contaTarefas(){
        var botoes = document.getElementsByClassName('btn-filtro-tarefa')
        var j = 0
        
        while(j < botoes.length){
            var card = botoes[j].closest('.card')
            card.getElementsByTagName('h3')[0].innerHTML = this.tarefas[botoes[j].dataset.tarefa].length
            j++
        }
    }
    
    verificarPosicaoData(data){
        if (this.tarefas[data].length > 1) {
            this.removerDuplicadosPorId.bind(this)(this.tarefas[data])
            this.tarefas[data].sort((b, a) => a.id - b.id)
        }
    }
    
    filtroTarefas(){
        evento(Array.from(document.getElementsByClassName('btn-filtro-tarefa')), 'click', this.filtrandoTarefa.bind(this))        
    }
    
    filtrandoTarefa(){
        var dataTarefa = event.currentTarget.dataset.tarefa
        var i = 0
        
        var botoes = document.getElementsByClassName('btn-filtro-tarefa')
        
        var titulo = document.getElementById('titulo-tabs')
        titulo.innerHTML = event.currentTarget.closest('.card').getElementsByClassName('title')[0].innerHTML
        
        while(i < botoes.length){
            
            if(dataTarefa == botoes[i].dataset.tarefa){
                botoes[i].closest('.task').classList.add('day')
                botoes[i].closest('.task').classList.remove('border-0')
            }else{
                botoes[i].closest('.task').classList.remove('day')
                botoes[i].closest('.task').classList.add('border-0')   
            }
            i++
        }
        
        this.preencherTarefas.bind(this)(dataTarefa)
    }
    
    preencherTarefas(tarefa){
        document.getElementById('tabelaTarefas').innerHTML = ''
        if(this.tarefas[tarefa].length > 0){
            var i = 0
        
            while(i < this.tarefas[tarefa].length){
                document.getElementById('tabelaTarefas').appendChild(new MontaTarefa(this.tarefas[tarefa][i], this, [tarefa, i]))
                i++
            }    
        }else{
            var p = document.createElement('p')
            p.classList.add('mt-3')
            p.innerText = `Nenhuma tarefa foi encontrada!`
            document.getElementById('tabelaTarefas').appendChild(p)
        }
    }
    
    simulacaoCliqueTab(){
        var botao = document.getElementsByClassName('btn-filtro-tarefa')
        var i = 0
        
        while(i < botao.length){
            if(botao[i].closest('.card').classList.contains('day')){
                botao[i].click()
                break
            }
            i++
        }
    }
    
    adicionarTarefa(){
        this.acaoTarefa = 'cadastrarTarefa'
        this.idTarefa = false
        var modal = document.getElementById('staticBackdrop2')
        document.getElementById('staticBackdropLabel').innerHTML = `Nova Tarefa`
        var inputs = modal.getElementsByClassName('form-control')
        var selects = modal.getElementsByClassName('form-select')
        
        modal.getElementsByClassName('btn-excluir-tarefa')[0].setAttribute('disabled', true)
        modal.getElementsByClassName('btn-excluir-tarefa')[0].classList.add('d-none')
        modal.getElementsByClassName('btn-concluir-tarefa')[0].setAttribute('disabled', true)
        modal.getElementsByClassName('btn-concluir-tarefa')[0].classList.add('d-none')
        
        var i = 0
        while(i < inputs.length){
            inputs[i].value = ''
            i++
        }
        
        i = 0
        while(i < selects.length){
            selects[i].value = ''
            i++
        }
        
        var selectUsuarios = document.getElementById('select-usuario')

        var event = new Event('change')

        selectUsuarios.dispatchEvent(event)
        
        var div = document.getElementById('subtarefasW')
        
        div.innerHTML = ``
        
        this.modalTarefas.show()
    }
    
    excluirTarefa(){
        this.acaoTarefa = "apagarTarefa"
        Swal.fire({
          title: "Você tem certeza?",
          text: "Voce não será capaz de recuperar este dado!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "var(--color-2)",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, excluir!",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {
            this.excluindoTarefa.bind(this)()
          }else{
              this.acaoTarefa = 'editarTarefa'
          }
        })
    }
    
    excluindoTarefa(){
        let id = retornaId(this.item)
        
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData({
            'acao': this.acaoTarefa,
            'id_tarefa': this.idTarefa,
            'id': id
        })
        request.send().then((r) =>{
            iziToast.success({
                message: "Tarefa excluida com sucesso!",
                timeout: 5000,
                position: 'topRight',
             })
             
            var i = 0
            var tarefas = []
            while(i < this.todasTarefas.length){
                if(this.todasTarefas[i].id != this.idTarefa){
                    tarefas.push(this.todasTarefas[i])
                    
                    break;
                }
                i++
            }
            
            this.todasTarefas = tarefas
             
            this.tarefaExcluida.bind(this)()
            this.contaTarefas.bind(this)()
            this.simulacaoCliqueTab.bind(this)()
            this.criarMiniCalendario.bind(this)()
            this.definirCalendario.bind(this)()
            this.modalTarefas.hide()
        }, (r) => {
            iziToast.error({
                message: "Falha ao excluir sua tarefa!",
                timeout: 5000,
                position: 'topRight',
             })
        })
    }
    
    tarefaExcluida(){
        var id = this.tarefas[this.tarefaPosicao][this.tarefaNumero].id
        
        var j = 0
        while (j <  this.acoesTab.length){
            this.tarefas[this.acoesTab[j]] = this.removerItemPorId.bind(this)(this.tarefas[this.acoesTab[j]], id)
            j++
        }
        
        this.removerItemPorId.bind(this)(this.todasTarefas, id)
        
    }
    
    salvarTarefa(){
        var modal = document.getElementById('staticBackdrop2')
        var subtarefas = document.getElementById('subtarefasW').getElementsByClassName('subtarefa')
        var inputs = modal.getElementsByClassName('form-control')
        var selects = modal.getElementsByClassName('form-select')
        var data = {}
        var fica = false
        
        if(this.item.id){
            data['id'] = this.item.id
        }else{
            data['id'] = this.item.url
        }
        var i = 0
        
        while(i < inputs.length){
            var nome = inputs[i].name
            if(nome != 'skip'){
                if(nome == 'prazo'){
                    data[nome] = inputs[i].value.replace('T', ' ')
                }else{
                    data[nome] = inputs[i].value
                }
            }
            if(inputs[i].classList.contains('obrigatory') && inputs[i].value.trim() == ''){
                fica = true
                inputs[i].classList.add('is-invalid')
            }else{
                inputs[i].classList.remove('is-invalid')
            }
            i++
        }
        
        
        
        i = 0
         while(i < selects.length){
            var nome = selects[i].name
            if(nome != 'skip'){
                data[nome] = selects[i].value
            }
            if(selects[i].classList.contains('obrigatory') && selects[i].value.trim() == ''){
                fica = true
                selects[i].classList.add('is-invalid')
            }else{
                selects[i].classList.remove('is-invalid')
            }
            i++
        }
        
        i=0
        var subtarefasDados = []
        var subtarefasChaves = ['4rskpGQrbviFfYmZK6gf4Ur6ZbhHmQ', 'a7eLkVHjVBZrS0B9FG1NX7egKDzOWM', 'Bjy1xeEockAS28Vr2qfBFlIBIcC5Z0']
        while(i < subtarefas.length){
            var inputs = subtarefas[i].getElementsByTagName('input')
            var j = 0
            var obj = {}
            while(j < inputs.length){
                if(inputs[j].type == 'checkbox'){
                    if(inputs[j].checked){
                        var valor = 1
                    }else{
                        var valor = 0
                    }
                }else{
                    var valor = inputs[j].value
                }
                obj[subtarefasChaves[j]] = valor
                j++    
            }
            
            subtarefasDados.push(obj)
            i++
        }
        
        data['subtarefas'] = JSON.stringify(subtarefasDados)
        
        if(!fica){
            if(this.acaoTarefa != 'cadastrarTarefa'){
                data['id_tarefa'] = this.idTarefa
            }
            data['acao'] = this.acaoTarefa
            this.enviarTarefa.bind(this)(data)
        }else{
            console.log('fica = true')
        }
        
    }
    
    enviarTarefa(data){
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        request.addData(data)
        request.send().then((r) => {
             var mensagem = ''
             if(this.acaoTarefa == 'cadastrarTarefa'){
                mensagem = 'Tarefa criada com sucesso!'
    
                this.todasTarefas.push(r.item)
             }else{
                    
               
                mensagem = 'Tarefa atualizada com sucesso!'
                
                let originalObject = this.tarefas[this.tarefaPosicao][this.tarefaNumero]
                
                let substituicaoObject = r.item

                if (typeof originalObject === 'object' && originalObject !== null && typeof substituicaoObject === 'object' && substituicaoObject !== null) {
                        for (let key in substituicaoObject) {
                            if (substituicaoObject.hasOwnProperty(key)) {
                                originalObject[key] = substituicaoObject[key]
                            }
                        }
                
                        originalObject['subtarefas'] = originalObject['subtarefas'].replace(/\\/g, "")
                        
                        r.item = originalObject
                    }
                    
                var i = 0
            
                while(i < this.todasTarefas.length){
                    if(this.todasTarefas[i].id == r.item.id){
                        this.todasTarefas[i] = r.item
                        
                        break;
                    }
                    i++
                }
                this.tarefaExcluida.bind(this)()
             }
             
             iziToast.success({
                message: mensagem,
                timeout: 5000,
                position: 'topRight',
             })
             
            
            this.separandoTarefas.bind(this)(r.item)
            this.contaTarefas.bind(this)()
            this.simulacaoCliqueTab.bind(this)()
            this.criarMiniCalendario.bind(this)()
            this.definirCalendario.bind(this)()
            this.modalTarefas.hide()
        }, (r) => {
            
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
                
                return
            }
            
            var mensagem = ''
            if(this.acaoTarefa == 'cadastrarTarefa'){
                mensagem = 'Falha ao criar tarefa!'
            }else{
                mensagem = 'Falha ao atualizar tarefa!'
            }
            iziToast.error({
                message: mensagem,
                timeout: 5000,
                position: 'topRight',
            })
        })
    }
    
    mudarStatus(andamento){
        var tarefa = this.tarefas[this.tarefaPosicao][this.tarefaNumero]
        
            this.tarefaExcluida.bind(this)()
            
            tarefa.andamento = andamento
            
            var i = 0
            
            while(i < this.todasTarefas.length){
                if(this.todasTarefas[i].id == tarefa.id){
                    this.todasTarefas[i].andamento = andamento
                    break;
                }
                i++
            }
            
            this.separandoTarefas.bind(this)(tarefa)
            this.contaTarefas.bind(this)()
            this.simulacaoCliqueTab.bind(this)()
            this.criarMiniCalendario.bind(this)()
            this.definirCalendario.bind(this)()
            this.modalTarefas.hide()
    }
    
    concluirTarefa(){
        var request = new Request(`${dominio}/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php`)
        request.addData({'acao': 'statusTarefa',
                         'id_tarefa': this.idTarefa,
                         'andamento': this.tarefaAndamento,
                        'id': this.item.id
        })
        request.send().then((r) => {
            this.mudarStatus(this.tarefaAndamento)
            
            iziToast.success({
               message: "Tarefa concluida com sucesso!",
               timeout: 5000,
               position: 'topRight',
            })
        }, (r) => {
            iziToast.error({
                message: "Erro ao concluir a tarefa!",
                timeout: 5000,
                position: 'topRight',
             })
        })
    }
    
    adicionaSubTarefa(){
        
        var div = document.getElementById('subtarefasW')
        var data = document.getElementsByClassName('data-subtarefa')[0].value
        var nome = document.getElementsByClassName('nome-subtarefa')[0].value
        var fica = false

        var subtarefa = {}
        if(nome.length < 1){
            fica = true
            document.getElementsByClassName('nome-subtarefa')[0].classList.add('is-invalid')
        }else{
            document.getElementsByClassName('nome-subtarefa')[0].classList.remove('is-invalid')
            subtarefa['a7eLkVHjVBZrS0B9FG1NX7egKDzOWM'] = nome
        }
        
        
        if(data.length < 1){
            fica = true
            document.getElementsByClassName('data-subtarefa')[0].classList.add('is-invalid')
        }else{
            document.getElementsByClassName('data-subtarefa')[0].classList.remove('is-invalid')
            subtarefa['Bjy1xeEockAS28Vr2qfBFlIBIcC5Z0'] = data
        }
        
        subtarefa['4rskpGQrbviFfYmZK6gf4Ur6ZbhHmQ'] = 0
        
        if(!fica){
            div.appendChild(new MontaSubtarefa(subtarefa))
            document.getElementsByClassName('nome-subtarefa')[0].value = ''
            document.getElementsByClassName('data-subtarefa')[0].value = ''
        }
    }
    
    selectUsuarios(){
        evento(document.getElementById('select-usuario'), 'change', this.checaUsuarios.bind(this))   
    }
    
    checaUsuarios(){
        var valor = event.target.value
        var div = document.getElementsByClassName('input-usuarios')[0]
        var div2 = document.getElementsByClassName('select-usuarios')[0]
        let select = document.getElementById('usuarios-select-tipo')
        let input = div.getElementsByTagName('input')[0]
        
        if(parseInt(valor)){
            div2.classList.remove('d-none')
            input.classList.remove('obrigatory')
            select.classList.add('obrigatory')
            div.classList.add('d-none')
        }else{
            div2.classList.add('d-none')
            input.classList.add('obrigatory')
            select.classList.remove('obrigatory')
            div.classList.remove('d-none')
            
            input.value = infoUser().nome ? infoUser().nome : ''
        }
    }
    
    //  anotações
    async anotacoes(){
        if(isJsonString(this.item.anotacoes)){
            var anotacao = JSON.parse(this.item.anotacoes)
            if(anotacao.length > 0){
                await this.listarAnotacoes.bind(this)(anotacao)
            }else {
                await this.listarAnotacoes.bind(this)([])
            }
        }else {
            await this.listarAnotacoes.bind(this)([])
        }
    }
    
    async listarAnotacoes(items){
        
        var apiAnotacao = new ApiNown('licitacoes', 'hmyWgsBlcPG0Euj')
        apiAnotacao.isNew()
        apiAnotacao.setItens(items)
        
        try{
            var resposta = await apiAnotacao.send()
            var lista = resposta.lista
        }catch(error){
            var lista = []
            console.log(error)
        }
        
        lista = lista.reverse()
        var i = 0
        while(i < lista.length){
            // var apiAnotacao = new ApiNown('licitacoes', 'm7dUM4aUcyXsmQz')
            // apiAnotacao.setHash(anotacao[i])
            // await apiAnotacao.send().then((r) => {
                // if(r.sucesso && r.item){
                    this.fragmento3.appendChild(new MontaAnotacao(lista[i], this))  
            //     }
            // })
            i++
        }
    }
    
    adicionaAnotacao(){
        this.idAnotacao = false
        var div = document.getElementById('tabAnotacoes')
        var anotacao = div.getElementsByClassName('text-anotacao')[0].value
        var fica = false
        
        var data = {}
        if(anotacao.length < 1){
            fica = true
            document.getElementsByClassName('text-anotacao')[0].classList.add('is-invalid')
        }else{
            document.getElementsByClassName('text-anotacao')[0].classList.remove('is-invalid')
            data['texto'] = anotacao
        }
        
        let id = retornaId(this.item)
        
        if(!fica){
            data['id'] = id
            data['acao'] = 'cadastrarAnotacao'
            this.enviaAnotacao.bind(this)(data)
            div.getElementsByClassName('text-anotacao')[0].value = ''
        }
    }
    
    enviaAnotacao(data){
        var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
        var mensagem = ''
        request.addData(data)
        request.send().then((r) => {
            var apiAnotacao = new ApiNown('licitacoes', 'm7dUM4aUcyXsmQz')
            apiAnotacao.setHash(r.item.id)
            apiAnotacao.send().then((r) => {
                if(r.sucesso && r.item){
                    if(parseInt(this.idAnotacao)){
                        mensagem = 'Anotação editada com sucesso!'
                    }else{
                        mensagem = 'Anotação criada com sucesso!'
                    }
                    iziToast.success({
                        message: mensagem,
                        timeout: 5000,
                        position: 'topRight',
                    })
                    
                    if(parseInt(this.idAnotacao)){
                        this.classeAnotacao.anotacao = r.item
                        this.classeAnotacao.div.getElementsByClassName('anot-text')[0].innerHTML = r.item.texto
                        this.modalAnotacao.hide()    
                    }else{
                        this.tabelaAnotacoes.insertAdjacentElement('afterbegin', new MontaAnotacao(r.item, this))    
                    }
                }
            }, (r) => {
                if(parseInt(this.idAnotacao)){
                    mensagem = 'Falha ao editar anotação!'
                }else{
                    mensagem = 'Falha ao criar anotação!'
                }
                iziToast.error({
                    message: mensagem,
                    timeout: 5000,
                    position: 'topRight',
                })
            })
        }, (e) => {
            console.log('erro', e)
        })
    }
    
    editarAnotacao(){
        let id = retornaId(this.item)
        
        var anotacao = document.getElementById('textoAnotacao')
        var data = {
            'acao': 'editarAnotacao',
            'id': id,
            'id_anotacao': this.idAnotacao,
        }
        
        if(anotacao.length < 1){
            anotacao.classList.add('is-invalid')       
        }else{
            anotacao.classList.remove('is-invalid')
            data['texto'] = anotacao.value
            this.enviaAnotacao(data)
        }
    }
    
    pesquisar(){
        evento(Array.from(document.getElementsByClassName('btn-search')), 'input', this.pesquisando.bind(this))
    }
    
    pesquisando(){
        var eventado = event.target
        var tabPane = eventado.closest('.tab-pane')
        var linhas = tabPane.getElementsByTagName('tr')
        var i = 1

        while(i < linhas.length){
            if(!linhas[i].classList.contains('nenhum-registro')){
                var linha = linhas[i].getAttribute('data-nome').toLowerCase()
                if(linha.includes(eventado.value)){
                    linhas[i].classList.remove('d-none')
                }else{
                    linhas[i].classList.add('d-none')
                }
            }
            
            i++
        }
        
        this.montaLinhaSemReg.bind(this)(eventado)
    }
    
    montaLinhaSemReg(parametro = false){
        if(!parametro){
            return
        }
        
        var tabPane = parametro.closest('.tab-pane')
        
        var linhas = tabPane.getElementsByTagName('tr')
        var i = 1
        var achou = false
        var linhaSemReg = tabPane.querySelector('.nenhum-registro')
        
        while(i < linhas.length){
            if(!linhas[i].classList.contains('nenhum-registro')){
                if(!linhas[i].classList.contains('d-none') && linhas[i] != parametro){
                    achou = true
                    break
                }
            }
            
            i++
        }
        
        if(!achou){
            if(!linhaSemReg){
                var novaLinha = document.createElement('tr')
                    novaLinha.classList.add('nenhum-registro')
                    
                var novaCol = document.createElement('td')
                    novaCol.colSpan = 5
                    novaCol.textContent = 'Nenhum registro foi encontrado!'
                    
                novaLinha.appendChild(novaCol)
                tabPane.querySelector('tbody').appendChild(novaLinha)
            }
        }else{
            if(linhaSemReg){
                linhaSemReg.remove()
            }
        }
    }
    
    montaModalSemReg(){
        var tabPane = document.getElementById('tabelaHabilitacao')
        var linhas = tabPane.getElementsByClassName('linha')
        var i = 0
        var achou = false
        var linhaSemReg = tabPane.querySelector('.nenhum-registro')
        
        if(linhas.length > 0){
            achou = true
        }
        
        if(!achou){
            tabPane.innerHTML = ''
            if(!linhaSemReg){
                var novaLinha = document.createElement('tr')
                    novaLinha.classList.add('nenhum-registro')
                    
                var novaCol = document.createElement('td')
                    novaCol.colSpan = 5
                    novaCol.textContent = 'Nenhum registro foi encontrado!'
                    
                novaLinha.appendChild(novaCol)
                tabPane.appendChild(novaLinha)
            }
        }else{
            if(linhaSemReg){
                linhaSemReg.remove()
            }
        }
    }
    
    async habilitacao(){
        if(isJsonString(this.item.habilitacoes)){
            var habilitacao = JSON.parse(this.item.habilitacoes)
            if(habilitacao.length > 0){
                await this.listarHabilitacao.bind(this)(habilitacao)
            }
            else{
                await this.listarHabilitacao.bind(this)([])
            }
        }else{
            await this.listarHabilitacao.bind(this)([])
        }
    }
    
    async listarHabilitacao(items){
        
        
        var apiHabilitacao = new ApiNown('licitacoes', 'JtClsGxek6mnQhC')
        apiHabilitacao.isNew()
        apiHabilitacao.setItens(items)
        this.habilitacoes = items
        try{
            var resposta = await apiHabilitacao.send()
            var habilitacao = resposta.lista
        }catch(error){
            var habilitacao = []
            console.log(error)
        }
        
        var i = 0

        while(i < habilitacao.length){
            // var apiHabilitacao = new ApiNown('licitacoes', 'JtClsGxek6mnQhC')
            // apiHabilitacao.setHash(habilitacao[i])
            // await apiHabilitacao.send().then((r) => {
                this.fragmento5.appendChild(new MontaHabilitacao(habilitacao[i], this))
            // })
            i++
        }
    }
    
    async anexos(){
        if(isJsonString(this.item.anexos)){
            var anexo = JSON.parse(this.item.anexos)
            if(anexo.length > 0){
               await this.listarAnexos.bind(this)(anexo) 
            }else{
                await this.listarAnexos.bind(this)([])
            }
        }else{
            await this.listarAnexos.bind(this)([])
        }
    }
    
    async listarAnexos(items){
        
        var apiAnexo = new ApiNown('licitacoes', 'QIt4Z0RYXNE5TlQ')
        apiAnexo.isNew()
        apiAnexo.setItens(items)
        
        try{
            var resposta = await apiAnexo.send()
            var anexo = resposta.lista
        }catch(error){
            var anexo = []
            console.log(error)
        }
        
        var i = 0
        while(i < anexo.length){
            // var apiAnexo = new ApiNown('licitacoes', 'QIt4Z0RYXNE5TlQ')
            // apiAnexo.setHash(anexo[i])
            // await apiAnexo.send().then((r) => {
            //     if(r.sucesso && r.item){
                    this.fragmento4.appendChild(new MontaAnexo(anexo[i], this))
            //     }
            // })
            i++
        }
    }
    
    //Anexos
    salvaUpload(){
        if(this.acaoUploader == 'adicionarAnexo'){
            this.salvaAnexo.bind(this)()
        }else{
            this.salvaHabilitacao.bind(this)()
        }
    }
    
    salvaAnexo(){
        let id = retornaId(this.item)
        
        var nomeAnexo = document.getElementById('nomeUploader')
        var imagem = this.uploader.get()
        var data = {
            'acao': this.acaoUploader,
            'id': id,
        }
        var fica = false
        
        if(nomeAnexo.value.length > 0){
            nomeAnexo.classList.remove('is-invalid')
        }else{
            nomeAnexo.classList.add('is-invalid')
            fica = true
        }
        
        if(!imagem.length > 0){
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Erro ao enviar o arquivo. Tente novamente.",
              timer: 2000,
              timerProgressBar: true
            })
            
            fica = true
        }
        
        if(!fica){
            data['nome'] = nomeAnexo.value,
            data['documento'] = JSON.stringify(imagem)
            
            var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
            request.addData(data)
            request.send().then((r) =>{
                iziToast.success({
                    message: "Anexo salvo com sucesso!",
                    timeout: 5000,
                    position: 'topRight',
                 })
                 if(r.item){
                     
                    var item = r.item
                
                    item['documento'] = r.item['documento'].replace(/\\/g, "")
                        
                    this.tabelaAnexos.appendChild(new MontaAnexo(item, this))
                    this.montaLinhaSemReg.bind(this)(this.tabelaAnexos)
                     
                 }
                 this.modalUploader.hide()
            }, (r) => {
                iziToast.error({
                    message: "Falha ao salvar anexo!",
                    timeout: 5000,
                    position: 'topRight',
                 })
            })
        }
    }
    
    salvaHabilitacao(){
        let id = retornaId(this.item)
        
        var nomeAnexo = document.getElementById('nomeUploader')
        var dataValidade = document.getElementById('dataValidade')
        var imagem = this.uploader.get()
        var data = {
            'acao': this.acaoUploader,
            'id': id
        }
        var fica = false
        
        if(nomeAnexo.value.length > 0){
            nomeAnexo.classList.remove('is-invalid')
        }else{
            nomeAnexo.classList.add('is-invalid')
            fica = true
        }
        
        if (dataValidade.value.length > 0) {
            dataValidade.classList.remove("is-invalid");
        } else {
            dataValidade.classList.add("is-invalid");
            fica = true
        }
        
        if(!imagem.length > 0){
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Erro ao enviar o arquivo. Tente novamente.",
              timer: 2000,
              timerProgressBar: true
            })
            
            fica = true
        }
        
        if(!fica){
            data['nome'] = nomeAnexo.value,
            data['documento'] = JSON.stringify(imagem)
            data['data_validade'] = dataValidade.value
            
            
            console.log(data)
            
            var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
            request.addData(data)
            request.send().then((r) =>{
                iziToast.success({
                    message: "Habilitação salvo com sucesso!",
                    timeout: 5000,
                    position: 'topRight',
                 })
                 if(r.item){
                     
                    var item = r.item
                
                    item['documento'] = r.item['documento'].replace(/\\/g, "")
                    this.habilitacoes.push(item.id)   
                    this.tabelaHabilitacao.appendChild(new MontaHabilitacao(item, this))
                    this.montaLinhaSemReg.bind(this)(this.tabelaHabilitacao)
                 }
                 this.modalUploader.hide()
            }, (r) => {
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
                    
                    return
                }
                
                
                iziToast.error({
                    message: "Falha ao salvar habilitação!",
                    timeout: 5000,
                    position: 'topRight',
                 })
            })
        }
    }
    
    upload(){
        var botao = event.currentTarget
        
        let divNome = document.getElementsByClassName('col-nome')[0]
        let divData = document.getElementsByClassName('col-data')[0]
        let inputData = document.getElementById('dataValidade')
        
        if(botao.dataset.uploader == 'anexo'){
            this.acaoUploader = 'adicionarAnexo'
            
            divNome.classList.remove('col-lg-6')
            divData.classList.add('d-none')
            inputData.classList.remove('obrigatory')
        }else{
            this.acaoUploader = "adicionarHabilitacao"
            
            divNome.classList.add('col-lg-6')
            divData.classList.remove('d-none')
            inputData.classList.add('obrigatory')
        }
        
        this.anexoUpload.bind(this)()
    }
    
    anexoUpload(){
        document.getElementById('nomeUploader').value = ''
        document.getElementById('nomeUploader').classList.remove('is-invalid')
        var uploads = document.getElementsByClassName('filepond--list')[0]
        if(uploads.children.length > 0){
            document.getElementsByClassName('filepond--action-revert-item-processing')[0].click()
        }
        
        var docSalvos = document.getElementById('listaAnexos')
        if(this.acaoUploader == 'adicionarAnexo'){
            docSalvos.classList.add('d-none')
        }else{
            docSalvos.classList.remove('d-none')
            this.carregaArquivos.bind(this)()
        }

        this.modalUploader.show()
    }
    
    filtraHabilitacoes(lista){
        var dados = []
        
        var i = 0
        var j = 0
            
        while(j < this.habilitacoes.length){
            this.habilitacoes[j] = parseInt(this.habilitacoes[j])
            j++
        }
            
        while(i < lista.length){
            
            if(!this.habilitacoes.includes(parseInt(lista[i].id))){
                dados.push(lista[i])
            }
            i++
        }
        
        var lista = dados
        
        var fragmento = document.createDocumentFragment()
        if(lista.length > 0){
            var local = JSON.parse(pegaLocal("nown"))
            var empresa = local.sub.id
            var i = 0
            let acho = false
            while(i < lista.length){
                if(lista[i].subconta == empresa){
                    fragmento.appendChild(this.montandoPadrao.bind(this)(lista[i]))
                    acho = true
                }
                
                i++
            }
            
            if(!acho){
                this.montaModalSemReg.bind(this)() 
                return
            }
             
            var div = document.getElementById('tabelaHabilitacao')
            div.innerHTML = ''
            div.appendChild(fragmento)
        }else{
            this.montaModalSemReg.bind(this)()   
        }
        
        
    }
    
    montandoPadrao(lista){
        var linha = this.montandoLinha(lista.nome, JSON.parse(lista.documento)[0], transformarData(lista.dataCriacao.split(' ')[0]))
        
        var tr = document.createElement('tr')
            tr.classList.add('linha')
            tr.innerHTML = this.montandoLinha(lista.nome, JSON.parse(lista.documento)[0], transformarData(lista.dataCriacao.split(' ')[0]))
        
        if(tr.getElementsByClassName('btn-trash').length > 0){
            tr.getElementsByClassName('btn-trash')[0].remove()
            tr.getElementsByTagName('th')[0].innerHTML = ''
        }
        
        var options = tr.getElementsByClassName('options')[0]
        options.classList.add('habilitacao', 'd-flex')
        
        var botao = document.createElement('button')
        botao.classList.add('btn','btn-adicionar', 'd-flex', 'justify-content-center', 'align-items-center')
        botao.innerHTML= `<i class="bi bi-plus"></i>`
        
        options.appendChild(botao)
        
        evento(botao, 'click', ()=>{
            this.vinculaHabilitacao.bind(this)(lista)
            }
        )
        
        evento(options.getElementsByClassName('btn-modal')[0], 'click', () => {
            this.passaDados(lista)
        })
        
        return tr
        
    }
    
    passaDados(item){
        
        var divMod = document.getElementById('modalAnexoContent')
        var divModH = divMod.getElementsByClassName('modal-title')[0]
        var divModB = divMod.getElementsByClassName('modal-body')[0]
        
        divModH.innerHTML = `
                                ${item.nome}
                            `
                            
        let arquivo = `${dominio}/conteudo/uploads/${JSON.parse(item.documento)[0]}` 
        
        if(arquivo.includes('imagens')){
            divModB.innerHTML = `
                                    <img src="${arquivo}" class="w-100 h-100" style="object-fit: contain">
                                `   
        }else{
            divModB.innerHTML = `
                                    <iframe class="w-100 h-100 position-relative overflow-hidden" frameborder="0" allowfullscreen src="${arquivo}">
                                    </iframe>
                                `  
        }
        
        this.reabrirUploader = true
        
        this.modalUploader.hide()
        this.modalAnexo.show()
    }
    
    vinculaHabilitacao(lista){
        let id = retornaId(this.item)
        
        var botao = event.currentTarget
        if(lista.id){
            var data = {
                'acao': 'vincularHabilitacao',
                'id': id,
                'id_habilitacao': lista.id
            }
            
            var request = new Request(dominio+'/conteudo/modulos/licitacoes/admins/apis/acoesFronts.php')
                request.addData(data)
                request.send().then((r) =>{
                    iziToast.success({
                        message: "Habilitação vinculada com sucesso!",
                        timeout: 5000,
                        position: 'topRight',
                     })
                     if(r.sucesso){
                        var item = lista
                        botao.closest('.linha').remove()
                        this.habilitacoes.push(item.id)
                        this.tabelaHabilitacao.appendChild(new MontaHabilitacao(item, this))
                        this.montaLinhaSemReg.bind(this)(this.tabelaHabilitacao)
                        this.montaModalSemReg.bind(this)()
                     }
                }, (r) => {
                    iziToast.error({
                        message: "Falha ao vincular Habilitação!",
                        timeout: 5000,
                        position: 'topRight',
                     })
                })
            
            return 
        }
        
        
        
    }
    
    carregaArquivos(){
        var apiHabilitacaoAutor = new ApiNown('licitacoes', '9KWGttMqtTaFoh5') 
        apiHabilitacaoAutor.isNew()
        apiHabilitacaoAutor.send().then((r) => {
            console.log(r.lista)
            var lista = r.lista
            this.filtraHabilitacoes.bind(this)(lista)
        })
    }
}