class AnalitcsPagamentos{
    constructor(hash){
       this.caminho = hash;
       
       this.init();
       
       this.status = {
           1: {t:"Aguardando Pagamento", c: "pending"},
           2: {t:"Aprovado", c: "approved"},
           3: {t:"Estornado", c: "refunded"}
       }
       
       this.meios = {
           'pix': {t: "Pix",c: "pix", "i": "bi-qr-code"},
           'carteira': {t: "Saldo em Carteira", c: "bank", "i": "bi-wallet2"},
           'admin': {t: "Pago como Administrador", c: "admin", "i": "bi-star"},
           'gratis': {t: "Pedido Grátis", c: "gratis", "i": "bi-piggy-bank"},
           'boleto': {t: "Boleto", c: "boleto", "i": "bi-file-earmark-text"},
           'cartao': {t: "Cartão de Crédito", c: "card", "i": "bi-credit-card"},
           2: {t: "teste", c: "card", "i": "bi-qr-code"}
       }
       
    }
    
    cardPagamento(item){
        var estado = item.estado
        
        var tipo = item["pagamento_tipo"];

        
        
        var div = document.createElement("DIV")
        div.classList.add("payment-card")
        div.innerHTML = `
        
                                <div class="payment-header">
                                    <div class="payment-info">
                                        <div class="payment-id">Transação #TXN-789456123</div>
                                        <div class="payment-date">08/06/2025 às 14:32</div>
                                    </div>
                                    <div class="payment-status">
                                        <span class="status-badge ${this.status[estado].c}">${this.status[estado].t}</span>
                                    </div>
                                </div>
                                
                                <div class="payment-details">
                                    <div class="payment-method-info">
                                        <div class="method-icon ${this.meios[tipo].c}">
                                            <i class="bi ${this.meios[tipo].i}"></i>
                                        </div>
                                        <div class="method-details">
                                            <div class="method-name">${this.meios[tipo].t}</div>
                                            <div class="method-description">Pagamento instantâneo • Aprovado em 15 segundos</div>
                                        </div>
                                        <div class="amount">${paraPreco(item.pagamento_valor)}</div>
                                    </div>
                                    
                                    <div class="transaction-details">
                                        <div class="detail-item">
                                            <div class="detail-label">ID da Transação</div>
                                            <div class="detail-value transaction-id">PIX789456123BR</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Chave PIX Utilizada</div>
                                            <div class="detail-value">***@gmail.com</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Instituição</div>
                                            <div class="detail-value">Banco do Brasil</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Taxa</div>
                                            <div class="detail-value">R$ 0,00</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="payment-order-info">
                                    <div class="order-header">
                                        <div class="order-icon">
                                            <i class="bi bi-bag-check"></i>
                                        </div>
                                        <div class="order-number">Pedido #12345 - TechStore Premium</div>
                                    </div>
                                    <div class="order-description">Smartphone Galaxy S24 Ultra + Capa Protetora</div>
                                </div>
                                
                                <div class="payment-actions">
                                    <button class="btn btn-n-primaria action-btn">
                                        <i class="bi bi-receipt me-1"></i>Ver Comprovante
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-download me-1"></i>Baixar PDF
                                    </button>
                                    <button class="btn btn-outline-success action-btn">
                                        <i class="bi bi-arrow-repeat me-1"></i>Repetir Pagamento
                                    </button>
                                </div>
                     
 
                                
                                <div class="payment-actions">
                                    <button class="btn btn-outline-warning action-btn">
                                        <i class="bi bi-clock me-1"></i>Aguardando Aprovação
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-info-circle me-1"></i>Detalhes
                                    </button>
                                    <button class="btn btn-outline-danger action-btn">
                                        <i class="bi bi-x-lg me-1"></i>Cancelar Transação
                                    </button>
                                </div>
                                
                           
                                
                                <div class="payment-actions">
                                    <button class="btn btn-n-primaria action-btn">
                                        <i class="bi bi-download me-1"></i>Baixar Boleto
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-copy me-1"></i>Copiar Código
                                    </button>
                                    <button class="btn btn-outline-warning action-btn">
                                        <i class="bi bi-credit-card me-1"></i>Pagar com Cartão
                                    </button>
                                </div>
                                
                               
                                
                                <div class="refund-info">
                                    <div class="refund-header">
                                        <div class="refund-icon">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </div>
                                        <div class="refund-title">Estorno Processado</div>
                                    </div>
                                    <div class="refund-details">O valor de R$ 159,90 foi estornado para seu cartão. O prazo para aparecer na fatura é de 5-7 dias úteis.</div>
                                </div>
                                
                                <div class="payment-actions">
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-receipt me-1"></i>Comprovante de Estorno
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-info-circle me-1"></i>Política de Estorno
                                    </button>
                                </div>
        
        `
        return div;
    }
    
    meusPagamentos(){
        var request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/infos.php`);
        request.addData({"acao":"meus-pagamentos"});
        request.send().then((r)=>{
           var lista = r.lista
             var somoAbertos = 0;
               var somaPagos = 0;
           var i = 0;
           var fragmento = document.createDocumentFragment();
           while(i < lista.length){
               var pagamento = lista[i]
             
               var valor = parseFloat(pagamento.pagamento_valor);
       
               if(pagamento.estado === 1){
                   // Abertos
                   somoAbertos += valor;
               }else{
                   // Concluidos
                   somaPagos += valor;
               }
               
               
                fragmento.appendChild(this.cardPagamento(pagamento))
               
               i++;
           }
           console.log(this.meios)
           document.getElementById("listaPagamentos").appendChild(fragmento)
                console.log(somaPagos)
                console.log(somoAbertos)
      
           
        })
    }
    
    init(){
        switch(this.caminho){
            case 'meus-pagamentos':
                this.meusPagamentos.bind(this)();
                break;
            case 'minhas-assinaturas':
                new AssinaturasNown()
                break;
        }
    }
}


// Classe AssinaturasNown - Gerenciamento de Assinaturas
class AssinaturasNown {
    constructor() {
        this.listaAssinaturas = []
        this.listaPagamentos = []
        this.assinaturasProcessadas = []
        this.pagamentosMostrados = {} // Para rastrear quantos pagamentos estão sendo mostrados por assinatura
        this.init.bind(this)()
    }

    // Verificar status da assinatura baseado nas datas
    verificarStatusAssinatura(pagamento) {
        const dataAtual = new Date()
        const dataInicio = new Date(pagamento.inicio)
        const dataFim = pagamento.fim ? new Date(pagamento.fim) : null

        // Se não tem data de início ou é futura, está pendente
        if (!pagamento.inicio || dataInicio > dataAtual) {
            return 'pending'
        }

        // Se tem data de fim e já passou, está expirada
        if (dataFim && dataFim < dataAtual) {
            return 'cancelled'
        }

        // Se tem data de fim futura, está ativa (mas vai expirar)
        if (dataFim && dataFim > dataAtual) {
            return 'active'
        }

        // Se não tem data de fim, está ativa permanente
        if (!dataFim) {
            return 'active'
        }

        return 'active'
    }

    // Processar dados combinando assinaturas e pagamentos (agrupado por assinatura)
    processarDados() {
        this.assinaturasProcessadas = []
        this.pagamentosMostrados = {} // Reset do contador
        
        // Agrupar pagamentos por referência do plano
        const pagamentosAgrupados = {}
        
        this.listaPagamentos.forEach(pagamento => {
            const planoId = pagamento.plano?.referencia
            if (!pagamentosAgrupados[planoId]) {
                pagamentosAgrupados[planoId] = []
            }
            pagamentosAgrupados[planoId].push(pagamento)
        })
        
        // Para cada grupo, criar uma assinatura
        Object.entries(pagamentosAgrupados).forEach(([planoId, pagamentos]) => {
            const plano = this.listaAssinaturas.find(ass => ass.id === planoId || ass.id === parseInt(planoId))
            
            if (plano) {
                // Ordenar pagamentos por data (mais recente primeiro)
                pagamentos.sort((a, b) => new Date(b.inicio) - new Date(a.inicio))
                
                // Usar o pagamento mais recente para determinar status
                const pagamentoAtual = pagamentos[0]
                const status = this.verificarStatusAssinatura(pagamentoAtual)
                
                const assinaturaProcessada = {
                    id: planoId, // Usar o ID do plano como ID da assinatura
                    plano: plano,
                    planoInfo: pagamentoAtual.plano,
                    status: status,
                    pagamentos: pagamentos, // Todos os pagamentos desta assinatura
                    dataInicio: pagamentoAtual.inicio,
                    dataFim: pagamentoAtual.fim,
                    usuario: pagamentoAtual.usuario,
                    estado: pagamentoAtual.estado,
                    autoRenovacao: !pagamentoAtual.fim,
                    precoAtual: parseFloat(pagamentoAtual.plano?.preco || 0),
                    precoOriginal: parseFloat(plano.preco?.preco || 0),
                    ciclo: pagamentoAtual.plano?.ciclo || 'mes',
                    proximaCobranca: this.calcularProximaCobranca(pagamentoAtual),
                    metodoPagamento: 'Não definido',
                    comprado: plano.preco?.comprado || false
                }

                // Inicializar contador de pagamentos mostrados (sempre começa com 2)
                this.pagamentosMostrados[planoId] = Math.min(2, pagamentos.length)

                this.assinaturasProcessadas.push(assinaturaProcessada)
            }
        })
        
        console.log('Assinaturas processadas:', this.assinaturasProcessadas)
        this.renderizarPagina()
    }

    // Calcular próxima cobrança baseado no ciclo
    calcularProximaCobranca(pagamento) {
        if (!pagamento.inicio) return null

        if (pagamento.fim) {
            const dataFim = new Date(pagamento.fim)
            const hoje = new Date()
            return dataFim > hoje ? dataFim : null
        }

        const dataInicio = new Date(pagamento.inicio)
        const proximaData = new Date(dataInicio)
        const hoje = new Date()
        const ciclo = pagamento.plano?.ciclo || 'mes'
        const diasCiclo = this.obterDiasCiclo(ciclo)
        
        while (proximaData <= hoje) {
            proximaData.setDate(proximaData.getDate() + diasCiclo)
        }

        return proximaData
    }

    // Converter ciclo em dias
    obterDiasCiclo(ciclo) {
        switch (ciclo?.toLowerCase()) {
            case 'dia': return 1
            case 'semana': return 7
            case 'mes': 
            case 'mensal': return 30
            case 'trimestre':
            case 'trimestral': return 90
            case 'semestre':
            case 'semestral': return 180
            case 'ano':
            case 'anual': return 365
            default: return 30
        }
    }

    // Renderizar cards de resumo (mantendo o layout original)
    renderizarResumo() {
        const ativas = this.assinaturasProcessadas.filter(a => a.status === 'active')
        const pausadas = this.assinaturasProcessadas.filter(a => a.status === 'paused')
        const canceladas = this.assinaturasProcessadas.filter(a => a.status === 'cancelled')
        
        const gastoMensal = ativas.reduce((total, a) => {
            let valorMensal = a.precoAtual
            switch(a.ciclo) {
                case 'ano': valorMensal = valorMensal / 12; break
                case 'trimestre': valorMensal = valorMensal / 3; break
                case 'semestre': valorMensal = valorMensal / 6; break
            }
            return total + valorMensal
        }, 0)

        // Próxima cobrança
        const proximasCobrancas = ativas
            .filter(a => a.proximaCobranca)
            .sort((a, b) => a.proximaCobranca - b.proximaCobranca)

        const proximaCobranca = proximasCobrancas[0]

        // Economia anual
        const economiaAnual = ativas.reduce((total, a) => {
            const economia = (a.precoOriginal - a.precoAtual) * 12
            return total + Math.max(0, economia)
        }, 0)

        // Atualizar o HTML mantendo a estrutura original
        document.querySelector('.summary-cards').innerHTML = `
            <div class="summary-card">
                <div class="summary-icon active">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="summary-value">${ativas.length}</div>
                <div class="summary-label">Assinaturas Ativas</div>
                <div class="summary-detail">Renovação automática ativada</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon monthly">
                    <i class="bi bi-calendar"></i>
                </div>
                <div class="summary-value">${paraPreco(gastoMensal)}</div>
                <div class="summary-label">Gasto Mensal</div>
                <div class="summary-detail">${proximaCobranca ? `Próxima cobrança em ${this.diasParaData(proximaCobranca.proximaCobranca)} dias` : 'Nenhuma cobrança pendente'}</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon next">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="summary-value">${proximaCobranca ? this.formatarData(proximaCobranca.proximaCobranca) : '--'}</div>
                <div class="summary-label">Próxima Cobrança</div>
                <div class="summary-detail">${proximaCobranca ? `${proximaCobranca.plano.nome} - ${paraPreco(proximaCobranca.precoAtual)}` : 'Nenhuma pendente'}</div>
            </div>
            <div class="summary-card">
                <div class="summary-icon savings">
                    <i class="bi bi-piggy-bank"></i>
                </div>
                <div class="summary-value">${paraPreco(economiaAnual)}</div>
                <div class="summary-label">Economia Anual</div>
                <div class="summary-detail">Com planos anuais</div>
            </div>
        `
    }

    // Renderizar lista de assinaturas para a tab "todas"
    renderizarAssinaturas() {
        const container = document.querySelector('#todas .subscriptions-grid')
        
        if (this.assinaturasProcessadas.length === 0) {
            container.innerHTML = this.criarEstadoVazio()
            return
        }

        const html = this.assinaturasProcessadas.map(assinatura => this.criarCardAssinatura(assinatura)).join('')
        container.innerHTML = html
    }

    // Criar card individual mantendo exatamente o layout original
    criarCardAssinatura(assinatura) {
        const statusConfig = {
            'active': { class: 'active', label: 'Ativa' },
            'paused': { class: 'paused', label: 'Pausada' },
            'cancelled': { class: 'cancelled', label: 'Cancelada' },
            'expired': { class: 'expired', label: 'Expirada' },
            'pending': { class: 'pending', label: 'Pendente' }
        }

        const status = statusConfig[assinatura.status] || statusConfig['active']
        let logoUrl = trataImagem(assinatura?.plano?.imagemDestaque, 'media')
        
        if(!logoUrl){
            logoUrl = gerarAvatar(assinatura.plano.nome, 50)
        }

        const temEconomia = assinatura.precoOriginal > assinatura.precoAtual
        const periodoTexto = this.obterPeriodoTexto(assinatura)

        return `
            <div class="subscription-card">
                <div class="subscription-header">
                    <div class="subscription-info">
                        <img src="${logoUrl}" alt="Logo" class="service-logo">
                        <div class="service-details">
                            <div class="service-name">${assinatura.plano.nome}</div>
                            <div class="service-plan">Plano ${this.formatarCiclo(assinatura.ciclo)}</div>
                            <div class="service-provider">${assinatura.plano.tag.trim()}</div>
                        </div>
                    </div>
                    <div class="subscription-status">
                        <span class="status-badge ${status.class}">${status.label}</span>
                    </div>
                </div>
                
                <div class="subscription-details">
                    <div class="pricing-info">
                        <div class="price-details">
                            <div class="current-price" data-price="${paraPreco(assinatura.precoAtual)}">${paraPreco(assinatura.precoAtual)} <span class="period">${periodoTexto}</span></div>
                            ${temEconomia ? `
                                <div class="original-price">${paraPreco(assinatura.precoOriginal)}</div>
                                <div class="savings">Economia de ${(((assinatura.precoOriginal - assinatura.precoAtual) / assinatura.precoOriginal) * 100).toFixed(0)}% • Oferta especial</div>
                            ` : ''}
                        </div>
                        <div class="auto-renewal">
                            <div class="next-billing">${this.obterTextoProximaCobranca(assinatura)}</div>
                        </div>
                    </div>
                    
                    ${this.criarSecaoFeatures(assinatura)}
                </div>
                
                ${this.criarSecaoHistorico(assinatura)}
                
                <div class="subscription-actions">
                    
                </div>
            </div>
        `
        
        // ${this.criarBotoesAcao(assinatura)}
    }

    // Criar seção de features mantendo o layout original
    criarSecaoFeatures(assinatura) {
        // Criar features baseadas nas informações disponíveis
        const features = this.gerarFeatures(assinatura)

        return `
            <div class="subscription-features">
                <div class="features-header">
                    <div class="features-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="features-title">Benefícios Inclusos</div>
                </div>
                <div class="features-list">
                    ${features.map(feature => `
                        <div class="feature-item">
                            ${feature}
                        </div>
                    `).join('')}
                </div>
            </div>
        `
    }

    // Gerar features baseadas no atr_pl do plano
    gerarFeatures(assinatura) {
        const features = []
        
        // Tentar fazer parse do atr_pl
        try {
            if (assinatura.plano.atr_pl) {
                const atrPl = JSON.parse(assinatura.plano.atr_pl)
                
                // Adicionar cada chave/valor como feature
                for (const [chave, valor] of Object.entries(atrPl)) {
                
                        const chaveFormatada = valor['OcmI9TMzVAxSokfPZvpPJEXdlxSdrT'].toString().trim()
                        const valorFormatado = valor['X2j0fgRJs3m26g4mbYwCv6TZPVCtbo'].toString().trim()
                        
                        if (chaveFormatada && chaveFormatada !== '') {
                            // Verificar se o valor é HTML ou string simples
                            if (this.isHTML(chaveFormatada)) {
                                features.push(`${chaveFormatada} <div class="feature-text">${valorFormatado}</div>`)
                            } else if(this.isHTML(valorFormatado)){
                                features.push(`${valorFormatado} <div class="feature-text">${chaveFormatada}</div>`)
                            }else{
                                // Se for string simples, usar dois pontos
                                features.push(`<div class="feature-text">${chaveFormatada}: ${valorFormatado}</div>`)
                            }
                        } else {
                            features.push(`<div class="feature-text">${valorFormatado}</div>`)
                        }
                    
                }
            }
        } catch (e) {
            console.log('Erro ao fazer parse do atr_pl:', e)
        }


            if (assinatura.precoAtual === 0) {
                features.push(`<i class="bi bi-check-circle-fill feature-icon"></i> <div class="feature-text">Plano gratuito</div>`)
                features.push(`<i class="bi bi-check-circle-fill feature-icon"></i> <div class="feature-text">Recursos básicos</div>`)
            } 

            // Features baseadas no ciclo
            switch(assinatura.ciclo) {
                case 'ano':
                    features.push(`<i class="bi bi-check-circle-fill feature-icon"></i> <div class="feature-text">Desconto anual</div>`)
                    break
                case 'mes':
                    features.push(`<i class="bi bi-check-circle-fill feature-icon"></i> <div class="feature-text">Flexibilidade mensal</div>`)
                    break
            }

        return features
    }

    // Verificar se o conteúdo é HTML
    isHTML(str) {
        // Verifica se contém tags HTML
        const htmlRegex = /<\/?[a-z][\s\S]*>/i
        return htmlRegex.test(str)
    }

    // Formatar chave da feature para exibição
    formatarChaveFeature(chave) {

        chave = chave.toLowerCase()
        
        // Se não encontrou formatação específica, capitalizar primeira letra
        return chave.charAt(0).toUpperCase() + chave.slice(1).toLowerCase()
    }

    // Criar seção de histórico mantendo layout original
    criarSecaoHistorico(assinatura) {
        return `
            <div class="billing-history">
                <div class="history-header">
                    <div class="history-title">
                        <div class="history-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h6>Últimas Cobranças</h6>
                    </div>
                </div>
                <div class="recent-charges" data-assinatura-id="${assinatura.id}">
                    ${this.gerarHistoricoCobrancas(assinatura)}
                </div>
                ${this.temMaisPagamentos(assinatura) ? `
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-secondary btn-sm ver-mais-btn" data-assinatura-id="${assinatura.id}">
                            <i class="bi bi-chevron-down me-1"></i>Ver Mais Pagamentos
                        </button>
                    </div>
                ` : ''}
            </div>
        `
    }

    // Gerar histórico de cobranças baseado no contador de pagamentos mostrados
    gerarHistoricoCobrancas(assinatura) {
        const pagamentos = assinatura.pagamentos || []
        
        if (pagamentos.length === 0) {
            return `
                <div class="charge-item">
                    <div class="charge-info">
                        <div class="charge-date">Nenhuma cobrança</div>
                        <div class="charge-method">Ainda não há cobranças realizadas</div>
                    </div>
                    <div class="charge-amount">-</div>
                </div>
            `
        }

        // Pegar quantos pagamentos devem ser mostrados para esta assinatura
        const quantidadeMostrar = this.pagamentosMostrados[assinatura.id] || 2
        const pagamentosParaMostrar = pagamentos.slice(0, quantidadeMostrar)

        return pagamentosParaMostrar.map(pagamento => `
            <div class="charge-item">
                <div class="charge-info">
                    <div class="charge-date">${this.formatarData(pagamento.inicio)}</div>
                    <div class="charge-method">Não definido</div>
                </div>
                <div class="charge-amount">${paraPreco(pagamento.plano?.preco || 0)}</div>
            </div>
        `).join('')
    }

    // Verificar se tem mais pagamentos para mostrar
    temMaisPagamentos(assinatura) {
        const totalPagamentos = (assinatura.pagamentos || []).length
        const mostrados = this.pagamentosMostrados[assinatura.id] || 2
        return totalPagamentos > mostrados
    }

    // Criar botões de ação mantendo layout original
    criarBotoesAcao(assinatura) {
        switch (assinatura.status) {
            case 'active':
                return `
                    <button class="btn btn-outline-success action-btn">
                        <i class="bi bi-arrow-up-circle me-1"></i>Fazer Upgrade
                    </button>
                    <button class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-credit-card me-1"></i>Alterar Pagamento
                    </button>
                    <button class="btn btn-outline-warning action-btn">
                        <i class="bi bi-pause-circle me-1"></i>Pausar
                    </button>
                    <button class="btn btn-outline-danger action-btn">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                `
            case 'paused':
                return `
                    <button class="btn btn-n-primaria action-btn">
                        <i class="bi bi-play-circle me-1"></i>Reativar Assinatura
                    </button>
                    <button class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-credit-card me-1"></i>Alterar Pagamento
                    </button>
                    <button class="btn btn-outline-danger action-btn">
                        <i class="bi bi-x-circle me-1"></i>Cancelar Definitivamente
                    </button>
                `
            case 'cancelled':
                return `
                    <button class="btn btn-n-primaria action-btn">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reativar Assinatura
                    </button>
                    <button class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-download me-1"></i>Baixar Dados
                    </button>
                `
            default:
                return `
                    <button class="btn btn-outline-secondary action-btn">
                        <i class="bi bi-info-circle me-1"></i>Ver Detalhes
                    </button>
                `
        }
    }

    // Estado vazio mantendo layout original
    criarEstadoVazio() {
        return `
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="empty-title">Nenhuma assinatura encontrada</div>
                <div class="empty-subtitle">Você ainda não possui assinaturas ativas.</div>
                <button class="btn btn-n-primaria empty-action">
                    <i class="bi bi-plus-circle me-2"></i>Explorar Planos
                </button>
            </div>
        `
    }

    // Atualizar contadores das tabs mantendo estrutura original
    atualizarContadoresTabs() {
        const todas = this.assinaturasProcessadas.length
        const ativas = this.assinaturasProcessadas.filter(a => a.status === 'active').length
        const pausadas = this.assinaturasProcessadas.filter(a => a.status === 'paused').length
        const canceladas = this.assinaturasProcessadas.filter(a => ['cancelled', 'expired'].includes(a.status)).length

        // Atualizar badges das tabs
        const todasBadge = document.querySelector('#todas-tab .badge')
        const ativasBadge = document.querySelector('#ativas-tab .badge')
        const pausadasBadge = document.querySelector('#pausadas-tab .badge')
        const canceladasBadge = document.querySelector('#canceladas-tab .badge')

        if (todasBadge) todasBadge.textContent = todas
        if (ativasBadge) ativasBadge.textContent = ativas
        if (pausadasBadge) pausadasBadge.textContent = pausadas
        if (canceladasBadge) canceladasBadge.textContent = canceladas
    }

    // Expandir histórico de cobranças (incrementar de 2 em 2)
    expandirHistorico(assinaturaId, botao) {
        const assinatura = this.assinaturasProcessadas.find(a => a.id === assinaturaId)
        console.log(assinatura)
        if (!assinatura) return

        const totalPagamentos = assinatura.pagamentos.length
        const mostradosAtualmente = this.pagamentosMostrados[assinaturaId] || 2

        // Incrementar de 2 em 2, mas não passar do total
        const novoTotal = Math.min(mostradosAtualmente + 2, totalPagamentos)
        this.pagamentosMostrados[assinaturaId] = novoTotal

        let pai = botao.closest('.tab-pane')
        const container = pai.querySelector(`[data-assinatura-id="${assinaturaId}"].recent-charges`)

        if (container) {
            container.innerHTML = this.gerarHistoricoCobrancas(assinatura)
        }
        console.log(botao)
        // Atualizar o botão
        if (botao) {
            if (novoTotal >= totalPagamentos) {
                // Se chegou no limite, esconder o botão ou alterar para "Ver menos"
                botao.innerHTML = '<i class="bi bi-chevron-up me-1"></i>Ver Menos'
            } else {
                // Ainda tem mais para mostrar
                botao.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Ver Mais Pagamentos'
            }
        }
    }

    // Recolher histórico de cobranças (voltar para 2)
    recolherHistorico(assinaturaId, botao) {
        const assinatura = this.assinaturasProcessadas.find(a => a.id === assinaturaId)
        if (!assinatura) return

        // Voltar para mostrar apenas 2
        this.pagamentosMostrados[assinaturaId] = 2
        
        let pai = botao.closest('.tab-pane')
        const container = pai.querySelector(`[data-assinatura-id="${assinaturaId}"].recent-charges`)
        
        if (container) {
            container.innerHTML = this.gerarHistoricoCobrancas(assinatura)
        }

        if (botao) {
            botao.innerHTML = '<i class="bi bi-chevron-down me-1"></i>Ver Mais Pagamentos'
        }
    }

    // Configurar event listeners
    configurarEventListeners() {
        // Event listener para botões "Ver mais" (usando event delegation)
        evento(Array.from(document.getElementsByClassName('ver-mais-btn')), 'click', ()=>{
            let btn = event.currentTarget
            const assinaturaId = btn.getAttribute('data-assinatura-id')
            console.log(assinaturaId)
            if (btn.textContent.includes('Ver Mais')) {
                this.expandirHistorico.bind(this)(assinaturaId, btn)
            } else {
                this.recolherHistorico.bind(this)(assinaturaId, btn)
            }
        })
        
        evento(Array.from(document.getElementById('assinaturasTabs').getElementsByClassName('nav-link')), 'click', ()=>{
            const tabLink = event.currentTarget.closest('#assinaturasTabs')
            if (tabLink) {
                const targetTab = tabLink.getAttribute('data-bs-target')
                if (targetTab) {
                    const filtro = targetTab.replace('#', '')
                    setTimeout(() => this.filtrarAssinaturas(filtro), 50)
                }
            }
        })
    }

    // Filtrar assinaturas por tab
    filtrarAssinaturas(filtro) {
        let assinaturasFiltradas = []
        
        switch (filtro) {
            case 'todas':
                assinaturasFiltradas = this.assinaturasProcessadas
                break
            case 'ativas':
                assinaturasFiltradas = this.assinaturasProcessadas.filter(a => a.status === 'active')
                break
            case 'pausadas':
                assinaturasFiltradas = this.assinaturasProcessadas.filter(a => a.status === 'paused')
                break
            case 'canceladas':
                assinaturasFiltradas = this.assinaturasProcessadas.filter(a => ['cancelled', 'expired'].includes(a.status))
                break
        }
        
        const container = document.querySelector(`#${filtro} .subscriptions-grid`)
        if (container) {
            if (assinaturasFiltradas.length === 0) {
                container.innerHTML = this.criarEstadoVazioFiltro(filtro)
            } else {
                const html = assinaturasFiltradas.map(assinatura => this.criarCardAssinatura(assinatura)).join('')
                container.innerHTML = html
            }
        }
    }

    // Estado vazio para filtros específicos
    criarEstadoVazioFiltro(filtro) {
        const estados = {
            'todas': {
                icon: 'bi-inbox',
                title: 'Nenhuma assinatura encontrada',
                subtitle: 'Você ainda não possui assinaturas.',
            },
            'ativas': {
                icon: 'bi-check-circle',
                title: 'Nenhuma assinatura ativa',
                subtitle: 'Suas assinaturas ativas aparecerão aqui.',
            },
            'pausadas': {
                icon: 'bi-pause-circle',
                title: 'Nenhuma assinatura pausada',
                subtitle: 'Assinaturas pausadas aparecerão aqui.',
            },
            'canceladas': {
                icon: 'bi-x-circle',
                title: 'Nenhuma assinatura cancelada',
                subtitle: 'Histórico de cancelamentos aparecerá aqui.',
            }
        }
        
        const estado = estados[filtro] || estados['todas']
        
        return `
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi ${estado.icon}"></i>
                </div>
                <div class="empty-title">${estado.title}</div>
                <div class="empty-subtitle">${estado.subtitle}</div>
            </div>
        `
    }

    // Utilitários
    formatarData(data) {
        return new Date(data).toLocaleDateString('pt-BR')
    }

    formatarDataCompleta(data) {
        return new Date(data).toLocaleDateString('pt-BR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
    }

    diasParaData(data) {
        const diff = new Date(data) - new Date()
        return Math.ceil(diff / (1000 * 60 * 60 * 24))
    }

    obterPeriodoTexto(assinatura) {
        const ciclo = assinatura.ciclo || 'mes'
        switch (ciclo.toLowerCase()) {
            case 'dia': return '/dia'
            case 'semana': return '/semana'
            case 'mes': return '/mês'
            case 'trimestre': return '/trimestre'
            case 'semestre': return '/semestre'
            case 'ano': return '/ano'
            default: return '/mês'
        }
    }

    formatarCiclo(ciclo) {
        switch (ciclo?.toLowerCase()) {
            case 'dia': return 'Diário'
            case 'semana': return 'Semanal'
            case 'mes': return 'Mensal'
            case 'trimestre': return 'Trimestral'
            case 'semestre': return 'Semestral'
            case 'ano': return 'Anual'
            default: return 'Mensal'
        }
    }

    obterTextoProximaCobranca(assinatura) {
        switch (assinatura.status) {
            case 'active':
                if (assinatura.dataFim) {
                    const diasRestantes = this.diasParaData(assinatura.dataFim)
                    return `Expira em: ${this.formatarData(assinatura.dataFim)}`
                }
                return assinatura.proximaCobranca ? 
                    `Próxima cobrança: ${this.formatarData(assinatura.proximaCobranca)}` : 
                    'Renovação automática ativa'
            case 'paused':
                return `Assinatura pausada desde ${this.formatarData(assinatura.dataFim || assinatura.dataInicio)}`
            case 'cancelled':
                return `Cancelada em ${this.formatarData(assinatura.dataFim)}`
            default:
                return 'Status indefinido'
        }
    }

    // Renderizar página completa
    renderizarPagina() {
        this.renderizarResumo()
        this.renderizarAssinaturas()
        this.atualizarContadoresTabs()
        
        // Filtrar outras tabs também na inicialização
        this.filtrarAssinaturas('ativas')
        this.filtrarAssinaturas('pausadas')
        this.filtrarAssinaturas('canceladas')
        
        this.configurarEventListeners()
        
        overlay.hide()
    }

    // Método para listar cobranças
    listaCobrancas() {
        console.log('Lista de cobranças processada')
    }

    // Pegar assinaturas (pagamentos)
    pegarAssinaturas() {
        let api = new ApiNown('pagamento', 'X1CJtX94HiUOvY4')
        api.isNew()
        api.setExtra(infoUser().id)
        api.paginacao(9999)
        api.send().then((r) => {
            console.log('Pagamentos recebidos:', r)
            
            if (r.lista.length == 0) {
                this.renderizarPagina()
                return
            }
            
            this.listaPagamentos = r.lista
            this.processarDados()
            
        }, (r) => {
            console.log('Erro ao buscar pagamentos:', r)
            this.renderizarPagina()
        })
    }

    // Inicialização
    init() {
        overlay.show({
            title: 'Carregando informações',
            subtitle: 'Processando dados',
            message: 'Por favor, aguarde.',
            icon: 'bi-arrow-clockwise',
            duration: 100000
        });
        let api = new ApiNown('assinaturas', 'eQl4hH0wpDvRbOa')
        api.isNew()
        api.paginacao(9999)
        api.send().then((r) => {
            console.log('Assinaturas recebidas:', r)
            
            if (r.lista.length == 0) {
                this.renderizarPagina()
                return
            }
            
            this.listaAssinaturas = r.lista
            this.pegarAssinaturas()
            
        }, (r) => {
            console.log('Erro ao buscar assinaturas:', r)
            this.renderizarPagina()
        })
    }
}
