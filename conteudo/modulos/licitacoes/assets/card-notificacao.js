class CardNotificacao{
    constructor(item){
        return this.montaCard.bind(this)(item)
    }
    
    montaCard(dado){
        let card = document.createElement('div')
        card.classList.add('notificacoes-wrap', 'h-100')
        
        console.log(dado)
        
        let acao = __getDados(dado, 'acao')
        let acaoAlvo = __getDados(dado, 'acao_alvo')
        let autor = __getDados(dado, 'autor').display
        let data = transformarData(__getDados(dado, 'dataCriacao'))
        let licitacao = __getDados(dado, 'pesquisa').pesquisa
        let url = __getDados(dado, 'pesquisa').url
        let nomeTarefa = __getDados(dado, 'dado').nome
        let nomeAntigo = __getDados(dado, 'dado').antiga

        let licitacaoText = `Licitacao: <a class="text-decoration-none" style="color: inherit" href="${dominio+'/licitacoes/'}${url ? url : ''}">${licitacao ?  licitacao : ''}</a>`
        
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
        
        let acaoText
        let acaoText2
        let icone
        switch (acao) {
            case 2:
                if(tipoText === 'Habilitação'){
                    acaoText = 'vinculada'
                    acaoText2 = 'vinculou' 
                }else{
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
        
        let artigo
        switch (tipoText) {
            case 'Anexo':
                artigo = 'um'
                break
            default:
                artigo = 'uma'
                break
        }
        
        card.innerHTML = `
                            <div class="notificacao-2 flex-column gap-1 h-100">
                                <div class="tipo-acao">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="d-flex gap-2 align-items-center">
                                                ${icone}
                                                <p class="m-0 text-captalized">${tipoText} ${acaoText}</p>
                                            </div>
                                        </div>
                                        <p class="text-end m-0">${data}</p>
                                    </div>
                                </div>
                                <div class="left">
                                    <div class="tarefa-wrap">
                                        ${tipoText !== 'Licitação' 
                                            ? `<p>${autor} ${acaoText2} ${artigo} ${tipoText.toLowerCase()}: ${(nomeTarefa && nomeAntigo) && (nomeTarefa != nomeAntigo)
                                                                                                             ? `de '${nomeAntigo}' para '${nomeTarefa}'`
                                                                                                             : `'${nomeTarefa}'`} na ${licitacaoText}</p>`
                                            : `<p>${autor} ${acaoText2} ${artigo} ${licitacaoText}</p>`
                                        }
                                    </div>
                                </div>
                            </div>
                         `
        return card
    }
}