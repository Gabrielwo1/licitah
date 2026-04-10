 
    <style>
        /* Page Analytics Detail Styles */
        #secao-header-pagina {
            .header-container {
                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05) 0%, rgba(0, 0, 128, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                
                .pagina-info {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    
                    .pagina-icon {
                        width: 60px;
                        height: 60px;
                        border-radius: 15px;
                        background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 1.5rem;
                        
                        i {
                            color: white;
                            font-size: 1.8rem;
                        }
                    }
                    
                    .pagina-detalhes {
                        flex: 1;
                        
                        .pagina-url {
                            font-family: 'Courier New', monospace;
                            font-size: 1.1rem;
                            font-weight: 600;
                            color: var(--nown-primaria);
                            background: rgba(236, 1, 102, 0.1);
                            padding: 0.5rem 1rem;
                            border-radius: 8px;
                            margin-bottom: 0.5rem;
                            display: inline-block;
                        }
                        
                        .pagina-titulo {
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #2c3e50;
                            margin-bottom: 0.25rem;
                        }
                        
                        .pagina-descricao {
                            color: #6c757d;
                            margin: 0;
                        }
                    }
                    
                    .pagina-acoes {
                        display: flex;
                        gap: 0.5rem;
                        
                        .btn-acao {
                            border-radius: 10px;
                            font-weight: 600;
                            padding: 0.5rem 1rem;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                transform: translateY(-2px);
                            }
                        }
                    }
                }
                
                .periodo-selecionado {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 1rem;
                    background: rgba(255,255,255,0.7);
                    border-radius: 12px;
                    backdrop-filter: blur(10px);
                    
                    .periodo-info {
                        display: flex;
                        align-items: center;
                        
                        .calendario-icon {
                            width: 35px;
                            height: 35px;
                            border-radius: 8px;
                            background: linear-gradient(135deg, #2196F3, #1976D2);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 0.75rem;
                            
                            i {
                                color: white;
                                font-size: 1rem;
                            }
                        }
                        
                        .periodo-texto {
                            font-weight: 600;
                            color: #2c3e50;
                        }
                    }
                    
                    .btn-alterar-periodo {
                        font-size: 0.8rem;
                        padding: 0.25rem 0.75rem;
                        border-radius: 15px;
                    }
                }
            }
        }

        #secao-metricas-pagina {
            .metricas-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
                
                .metrica-card {
                    background: white;
                    border-radius: 15px;
                    padding: 1.5rem;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.05);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                    
                    &:before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 3px;
                        background: linear-gradient(90deg, var(--nown-primaria), var(--nown-primaria-darker));
                        transform: scaleX(0);
                        transition: transform 0.3s ease;
                    }
                    
                    &:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                        
                        &:before {
                            transform: scaleX(1);
                        }
                    }
                    
                    .metrica-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 1rem;
                        
                        .metrica-icon {
                            width: 45px;
                            height: 45px;
                            border-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            
                            i {
                                font-size: 1.3rem;
                                color: white;
                            }
                            
                            &.pageviews { background: linear-gradient(135deg, #2196F3, #1976D2); }
                            &.visitantes { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                            &.tempo-pagina { background: linear-gradient(135deg, #FF9800, #F57C00); }
                            &.bounce-rate { background: linear-gradient(135deg, #f44336, #d32f2f); }
                            &.saidas { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                            &.valor-pagina { background: linear-gradient(135deg, #00BCD4, #0097A7); }
                        }
                        
                        .metrica-tendencia {
                            display: flex;
                            align-items: center;
                            font-size: 0.75rem;
                            font-weight: 600;
                            
                            &.positiva {
                                color: #4CAF50;
                            }
                            
                            &.negativa {
                                color: #f44336;
                            }
                            
                            &.neutra {
                                color: #6c757d;
                            }
                            
                            i {
                                margin-right: 0.25rem;
                            }
                        }
                    }
                    
                    .metrica-valor {
                        font-size: 1.8rem;
                        font-weight: 800;
                        color: var(--nown-primaria);
                        margin-bottom: 0.25rem;
                    }
                    
                    .metrica-label {
                        color: #6c757d;
                        font-size: 0.85rem;
                        margin-bottom: 0.5rem;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    
                    .metrica-comparacao {
                        font-size: 0.7rem;
                        color: #6c757d;
                        
                        .destaque {
                            font-weight: 600;
                            color: var(--nown-primaria);
                        }
                    }
                }
            }
        }

        #secao-grafico-temporal {
            .grafico-container {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                border: 1px solid rgba(0,0,0,0.05);
                
                .grafico-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                    
                    .grafico-title {
                        display: flex;
                        align-items: center;
                        
                        .title-icon {
                            width: 40px;
                            height: 40px;
                            border-radius: 10px;
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 1rem;
                            
                            i {
                                color: white;
                                font-size: 1.2rem;
                            }
                        }
                        
                        h5 {
                            margin: 0;
                            font-weight: 700;
                            color: #2c3e50;
                        }
                    }
                    
                    .grafico-controles {
                        display: flex;
                        gap: 0.5rem;
                        
                        .btn-metrica {
                            border: 2px solid rgba(236, 1, 102, 0.2);
                            background: rgba(236, 1, 102, 0.05);
                            color: var(--nown-primaria);
                            border-radius: 8px;
                            padding: 0.4rem 0.8rem;
                            font-size: 0.8rem;
                            font-weight: 600;
                            transition: all 0.3s ease;
                            
                            &:hover, &.active {
                                background: var(--nown-primaria);
                                color: white;
                                border-color: var(--nown-primaria);
                            }
                        }
                    }
                }
                
                .grafico-conteudo {
                    width: 100%;
                    height: 400px;
                }
            }
        }

        #secao-dados-detalhados {
            .dados-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
                
                .dados-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.05);
                    
                    .dados-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 1.5rem;
                        padding-bottom: 1rem;
                        border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                        
                        .dados-title {
                            display: flex;
                            align-items: center;
                            
                            .title-icon {
                                width: 35px;
                                height: 35px;
                                border-radius: 8px;
                                background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 0.75rem;
                                
                                i {
                                    color: white;
                                    font-size: 1rem;
                                }
                            }
                            
                            h6 {
                                margin: 0;
                                font-weight: 700;
                                color: #2c3e50;
                            }
                        }
                        
                        .dados-opcoes {
                            .btn-opcoes {
                                border: none;
                                background: rgba(236, 1, 102, 0.1);
                                color: var(--nown-primaria);
                                border-radius: 6px;
                                padding: 0.25rem 0.5rem;
                                font-size: 0.7rem;
                                
                                &:hover {
                                    background: var(--nown-primaria);
                                    color: white;
                                }
                            }
                        }
                    }
                    
                    .dados-conteudo {
                        .dados-item {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 0.75rem 0;
                            border-bottom: 1px solid rgba(0,0,0,0.05);
                            transition: all 0.3s ease;
                            
                            &:hover {
                                background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                                margin: 0 -0.5rem;
                                padding-left: 0.5rem;
                                padding-right: 0.5rem;
                                border-radius: 8px;
                            }
                            
                            &:last-child {
                                border-bottom: none;
                            }
                            
                            .item-info {
                                display: flex;
                                align-items: center;
                                flex: 1;
                                
                                .item-icon {
                                    width: 30px;
                                    height: 30px;
                                    border-radius: 6px;
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(0, 0, 128, 0.1));
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    
                                    i {
                                        color: var(--nown-primaria);
                                        font-size: 0.9rem;
                                    }
                                }
                                
                                .item-detalhes {
                                    .item-nome {
                                        font-weight: 600;
                                        color: #2c3e50;
                                        margin-bottom: 0.1rem;
                                        font-size: 0.9rem;
                                    }
                                    
                                    .item-extra {
                                        font-size: 0.75rem;
                                        color: #6c757d;
                                        margin: 0;
                                    }
                                }
                            }
                            
                            .item-metricas {
                                text-align: right;
                                
                                .metrica-principal {
                                    font-weight: 700;
                                    color: var(--nown-primaria);
                                    font-size: 0.9rem;
                                    margin-bottom: 0.1rem;
                                }
                                
                                .metrica-secundaria {
                                    font-size: 0.75rem;
                                    color: #6c757d;
                                    margin: 0;
                                }
                                
                                .progress-mini {
                                    width: 60px;
                                    height: 4px;
                                    background: rgba(236, 1, 102, 0.1);
                                    border-radius: 2px;
                                    margin-top: 0.25rem;
                                    
                                    .progress-bar-mini {
                                        height: 100%;
                                        background: linear-gradient(90deg, var(--nown-primaria), var(--nown-primaria-darker));
                                        border-radius: 2px;
                                        transition: width 0.3s ease;
                                    }
                                }
                            }
                        }
                        
                        .grafico-pequeno {
                            width: 100%;
                            height: 150px;
                            margin-top: 1rem;
                        }
                    }
                }
            }
        }

        #secao-comportamento-usuario {
            .comportamento-container {
                background: linear-gradient(135deg, rgba(0, 0, 128, 0.05) 0%, rgba(236, 1, 102, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                
                .comportamento-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    
                    .header-icon {
                        width: 50px;
                        height: 50px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, #9C27B0, #7B1FA2);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 1rem;
                        
                        i {
                            color: white;
                            font-size: 1.5rem;
                        }
                    }
                    
                    .header-texto {
                        h5 {
                            margin: 0;
                            font-weight: 700;
                            color: #2c3e50;
                        }
                        
                        .subtitulo {
                            font-size: 0.9rem;
                            color: #6c757d;
                            margin: 0;
                        }
                    }
                }
                
                .comportamento-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1rem;
                    
                    .comportamento-metric {
                        background: rgba(255,255,255,0.8);
                        border-radius: 12px;
                        padding: 1.5rem;
                        text-align: center;
                        backdrop-filter: blur(10px);
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        }
                        
                        .metric-icon {
                            width: 40px;
                            height: 40px;
                            border-radius: 8px;
                            margin: 0 auto 1rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            
                            i {
                                color: white;
                                font-size: 1.1rem;
                            }
                            
                            &.entrada { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                            &.scroll { background: linear-gradient(135deg, #2196F3, #1976D2); }
                            &.cliques { background: linear-gradient(135deg, #FF9800, #F57C00); }
                            &.formularios { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                        }
                        
                        .metric-valor {
                            font-size: 1.3rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .metric-label {
                            font-size: 0.8rem;
                            color: #6c757d;
                            margin: 0;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                    }
                }
            }
        }

        /* Dark Mode Styles */
        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-header-pagina {
                .header-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .pagina-info {
                        .pagina-detalhes {
                            .pagina-titulo {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                    
                    .periodo-selecionado {
                        background: rgba(45, 45, 45, 0.7);
                        
                        .periodo-info {
                            .periodo-texto {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                }
            }
            
            #secao-metricas-pagina {
                .metricas-grid {
                    .metrica-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .metrica-label, .metrica-comparacao {
                            color: #aaa;
                        }
                    }
                }
            }
            
            #secao-grafico-temporal {
                .grafico-container {
                    background: #2d2d2d;
                    border-color: #444;
                    
                    .grafico-header {
                        .grafico-title {
                            h5 {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                }
            }
            
            #secao-dados-detalhados {
                .dados-grid {
                    .dados-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .dados-header {
                            .dados-title {
                                h6 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                        
                        .dados-conteudo {
                            .dados-item {
                                border-bottom-color: rgba(255,255,255,0.1);
                                
                                .item-info {
                                    .item-detalhes {
                                        .item-nome {
                                            color: var(--nown-terciaria);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            #secao-comportamento-usuario {
                .comportamento-container {
                    background: linear-gradient(135deg, rgba(0, 0, 128, 0.08) 0%, rgba(236, 1, 102, 0.08) 100%);
                    
                    .comportamento-header {
                        .header-texto {
                            h5 {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                    
                    .comportamento-grid {
                        .comportamento-metric {
                            background: rgba(58, 58, 58, 0.8);
                        }
                    }
                }
            }
        }

        /* Light Mode Styles */
        body.light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            
            h1 {
                font-weight: 700;
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
                background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .subtitle {
                color: #6c757d;
                font-size: 1.1rem;
                margin: 0;
            }
        }
    </style>
    <div class="container-fluid py-4">
        <!-- Header da Página -->
        <div class="page-header">
            <h1><i class="bi bi-file-text me-3"></i>Analytics da Página</h1>
            <p class="subtitle">Análise detalhada do desempenho de uma página específica</p>
        </div>

        <!-- Seção Header da Página Específica -->
        <section id="secao-header-pagina">
            <div class="header-container">
                <div class="pagina-info">
                    <div class="pagina-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="pagina-detalhes">
                        <div class="pagina-url">/produtos/smartphone-galaxy-s24</div>
                        <div class="pagina-titulo">Galaxy S24 Ultra - Smartphone Premium</div>
                        <div class="pagina-descricao">Página de produto com especificações técnicas, preços e opções de compra</div>
                    </div>
                    <div class="pagina-acoes">
                        <button class="btn btn-outline-secondary btn-acao">
                            <i class="bi bi-eye me-1"></i>Ver Página
                        </button>
                        <button class="btn btn-n-primaria btn-acao">
                            <i class="bi bi-share me-1"></i>Compartilhar
                        </button>
                    </div>
                </div>
                <div class="periodo-selecionado">
                    <div class="periodo-info">
                        <div class="calendario-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="periodo-texto">Últimos 30 dias (05/05/2025 - 05/06/2025)</div>
                    </div>
                    <button class="btn btn-outline-primary btn-alterar-periodo">
                        <i class="bi bi-pencil me-1"></i>Alterar Período
                    </button>
                </div>
            </div>
        </section>

        <!-- Seção de Métricas da Página -->
        <section id="secao-metricas-pagina">
            <div class="metricas-grid">
                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon pageviews">
                            <i class="bi bi-eye"></i>
                        </div>
                        <div class="metrica-tendencia positiva">
                            <i class="bi bi-arrow-up"></i>
                            +15.3%
                        </div>
                    </div>
                    <div class="metrica-valor" id="totalPageviews">18,756</div>
                    <div class="metrica-label">Visualizações</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">+2,489</span> visualizações</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon visitantes">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="metrica-tendencia positiva">
                            <i class="bi bi-arrow-up"></i>
                            +12.7%
                        </div>
                    </div>
                    <div class="metrica-valor" id="visitantesUnicos">14,234</div>
                    <div class="metrica-label">Visitantes Únicos</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">+1,607</span> visitantes</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon tempo-pagina">
                            <i class="bi bi-stopwatch"></i>
                        </div>
                        <div class="metrica-tendencia positiva">
                            <i class="bi bi-arrow-up"></i>
                            +8.5%
                        </div>
                    </div>
                    <div class="metrica-valor" id="tempoPagina">3m 42s</div>
                    <div class="metrica-label">Tempo Médio na Página</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">+18s</span> a mais</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon bounce-rate">
                            <i class="bi bi-arrow-return-left"></i>
                        </div>
                        <div class="metrica-tendencia negativa">
                            <i class="bi bi-arrow-down"></i>
                            -4.2%
                        </div>
                    </div>
                    <div class="metrica-valor" id="taxaRejeicao">32.8%</div>
                    <div class="metrica-label">Taxa de Rejeição</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">-1.42%</span> melhor</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon saidas">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <div class="metrica-tendencia neutra">
                            <i class="bi bi-dash"></i>
                            +0.8%
                        </div>
                    </div>
                    <div class="metrica-valor" id="taxaSaida">28.5%</div>
                    <div class="metrica-label">Taxa de Saída</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">+0.23%</span> similar</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon valor-pagina">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="metrica-tendencia positiva">
                            <i class="bi bi-arrow-up"></i>
                            +23.1%
                        </div>
                    </div>
                    <div class="metrica-valor" id="valorPagina">R$ 127,45</div>
                    <div class="metrica-label">Valor Médio da Página</div>
                    <div class="metrica-comparacao">vs. período anterior: <span class="destaque">+R$ 23,89</span> a mais</div>
                </div>
            </div>
        </section>

        <!-- Seção de Gráfico Temporal -->
        <section id="secao-grafico-temporal">
            <div class="grafico-container">
                <div class="grafico-header">
                    <div class="grafico-title">
                        <div class="title-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5>Desempenho ao Longo do Tempo</h5>
                    </div>
                    <div class="grafico-controles">
                        <button class="btn-metrica active" data-metric="pageviews">Visualizações</button>
                        <button class="btn-metrica" data-metric="visitantes">Visitantes</button>
                        <button class="btn-metrica" data-metric="tempo">Tempo na Página</button>
                        <button class="btn-metrica" data-metric="bounce">Taxa Rejeição</button>
                    </div>
                </div>
                <div class="grafico-conteudo" id="graficoTemporal"></div>
            </div>
        </section>

        <!-- Seção de Dados Detalhados -->
        <section id="secao-dados-detalhados">
            <div class="dados-grid">
                <!-- Fontes de Tráfego -->
                <div class="dados-card">
                    <div class="dados-header">
                        <div class="dados-title">
                            <div class="title-icon">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <h6>Fontes de Tráfego</h6>
                        </div>
                        <div class="dados-opcoes">
                            <button class="btn-opcoes">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dados-conteudo">
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Busca Orgânica</div>
                                    <div class="item-extra">Google, Bing, Yahoo</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">7,234</div>
                                <div class="metrica-secundaria">38.6%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 38.6%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-link-45deg"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Tráfego Direto</div>
                                    <div class="item-extra">URL digitada diretamente</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">5,891</div>
                                <div class="metrica-secundaria">31.4%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 31.4%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-share"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Redes Sociais</div>
                                    <div class="item-extra">Facebook, Instagram, Twitter</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">3,456</div>
                                <div class="metrica-secundaria">18.4%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 18.4%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Email Marketing</div>
                                    <div class="item-extra">Campanhas e newsletters</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">1,567</div>
                                <div class="metrica-secundaria">8.4%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 8.4%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-megaphone"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Anúncios Pagos</div>
                                    <div class="item-extra">Google Ads, Facebook Ads</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">608</div>
                                <div class="metrica-secundaria">3.2%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 3.2%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dispositivos -->
                <div class="dados-card">
                    <div class="dados-header">
                        <div class="dados-title">
                            <div class="title-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                            <h6>Dispositivos</h6>
                        </div>
                    </div>
                    <div class="dados-conteudo">
                        <div class="grafico-pequeno" id="graficoDispositivos"></div>
                    </div>
                </div>

                <!-- Páginas de Saída -->
                <div class="dados-card">
                    <div class="dados-header">
                        <div class="dados-title">
                            <div class="title-icon">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                            <h6>Fluxo de Saída</h6>
                        </div>
                    </div>
                    <div class="dados-conteudo">
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">/carrinho</div>
                                    <div class="item-extra">Página do carrinho</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">2,347</div>
                                <div class="metrica-secundaria">43.8%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 43.8%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-list-ul"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">/produtos</div>
                                    <div class="item-extra">Lista de produtos</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">1,234</div>
                                <div class="metrica-secundaria">23.1%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 23.1%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-house"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">/home</div>
                                    <div class="item-extra">Página inicial</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">892</div>
                                <div class="metrica-secundaria">16.7%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 16.7%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">Saída do Site</div>
                                    <div class="item-extra">Abandono direto</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">883</div>
                                <div class="metrica-secundaria">16.4%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 16.4%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Palavras-chave -->
                <div class="dados-card">
                    <div class="dados-header">
                        <div class="dados-title">
                            <div class="title-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h6>Palavras-chave</h6>
                        </div>
                    </div>
                    <div class="dados-conteudo">
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">galaxy s24 ultra</div>
                                    <div class="item-extra">Termo principal</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">3,456</div>
                                <div class="metrica-secundaria">47.8%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 47.8%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">smartphone premium</div>
                                    <div class="item-extra">Termo genérico</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">1,789</div>
                                <div class="metrica-secundaria">24.7%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 24.7%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">samsung galaxy</div>
                                    <div class="item-extra">Marca</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">967</div>
                                <div class="metrica-secundaria">13.4%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 13.4%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">celular camera</div>
                                    <div class="item-extra">Funcionalidade</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">634</div>
                                <div class="metrica-secundaria">8.8%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 8.8%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="item-detalhes">
                                    <div class="item-nome">preço galaxy s24</div>
                                    <div class="item-extra">Termo comercial</div>
                                </div>
                            </div>
                            <div class="item-metricas">
                                <div class="metrica-principal">389</div>
                                <div class="metrica-secundaria">5.3%</div>
                                <div class="progress-mini">
                                    <div class="progress-bar-mini" style="width: 5.3%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção de Comportamento do Usuário -->
        <section id="secao-comportamento-usuario">
            <div class="comportamento-container">
                <div class="comportamento-header">
                    <div class="header-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="header-texto">
                        <h5>Comportamento do Usuário</h5>
                        <p class="subtitulo">Como os visitantes interagem com esta página</p>
                    </div>
                </div>
                <div class="comportamento-grid">
                    <div class="comportamento-metric">
                        <div class="metric-icon entrada">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div class="metric-valor" id="paginaEntrada">67.3%</div>
                        <div class="metric-label">Página de Entrada</div>
                    </div>
                    <div class="comportamento-metric">
                        <div class="metric-icon scroll">
                            <i class="bi bi-arrow-down"></i>
                        </div>
                        <div class="metric-valor" id="scrollMedio">78.5%</div>
                        <div class="metric-label">Scroll Médio</div>
                    </div>
                    <div class="comportamento-metric">
                        <div class="metric-icon cliques">
                            <i class="bi bi-cursor"></i>
                        </div>
                        <div class="metric-valor" id="cliquesBtn">2,847</div>
                        <div class="metric-label">Cliques em Botões</div>
                    </div>
                    <div class="comportamento-metric">
                        <div class="metric-icon formularios">
                            <i class="bi bi-card-text"></i>
                        </div>
                        <div class="metric-valor" id="formInteracao">34.2%</div>
                        <div class="metric-label">Interação Formulário</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
