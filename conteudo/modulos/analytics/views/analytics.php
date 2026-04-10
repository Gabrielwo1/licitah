  <style>
        /* Dashboard Analytics Styles */
        #secao-filtros {
            .filtros-container {
                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05) 0%, rgba(0, 0, 128, 0.05) 100%);
                border-radius: 20px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                
                .filtros-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1rem;
                    
                    .filter-icon {
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
                
                .filtros-controles {
                    display: flex;
                    gap: 1rem;
                    flex-wrap: wrap;
                    align-items: center;
                    
                    .form-control, .form-select {
                        border: 2px solid #e9ecef;
                        border-radius: 10px;
                        transition: all 0.3s ease;
                        
                        &:focus {
                            border-color: var(--nown-primaria);
                            box-shadow: 0 0 0 0.2rem rgba(236, 1, 102, 0.15);
                        }
                    }
                    
                    .btn-aplicar {
                        border-radius: 10px;
                        font-weight: 600;
                        padding: 0.5rem 1.5rem;
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 15px rgba(236, 1, 102, 0.3);
                        }
                    }
                }
            }
        }

        #secao-metricas-principais {
            .metricas-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
                            width: 50px;
                            height: 50px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            
                            i {
                                font-size: 1.5rem;
                                color: white;
                            }
                            
                            &.visitantes { background: linear-gradient(135deg, #2196F3, #1976D2); }
                            &.pageviews { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                            &.sessoes { background: linear-gradient(135deg, #FF9800, #F57C00); }
                            &.bounce { background: linear-gradient(135deg, #f44336, #d32f2f); }
                            &.duracao { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                            &.conversoes { background: linear-gradient(135deg, #00BCD4, #0097A7); }
                        }
                        
                        .metrica-variacao {
                            display: flex;
                            align-items: center;
                            font-size: 0.8rem;
                            font-weight: 600;
                            
                            &.positiva {
                                color: #4CAF50;
                            }
                            
                            &.negativa {
                                color: #f44336;
                            }
                            
                            i {
                                margin-right: 0.25rem;
                            }
                        }
                    }
                    
                    .metrica-valor {
                        font-size: 2rem;
                        font-weight: 800;
                        color: var(--nown-primaria);
                        margin-bottom: 0.25rem;
                    }
                    
                    .metrica-label {
                        color: #6c757d;
                        font-size: 0.9rem;
                        margin: 0;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    
                    .metrica-comparacao {
                        font-size: 0.75rem;
                        color: #6c757d;
                        margin-top: 0.5rem;
                    }
                }
            }
        }

        #secao-graficos-principais {
            .graficos-container {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 1.5rem;
                margin-bottom: 2rem;
                
                .grafico-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
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
                        
                        .grafico-opcoes {
                            display: flex;
                            gap: 0.5rem;
                            
                            .btn-opcao {
                                border: none;
                                background: rgba(236, 1, 102, 0.1);
                                color: var(--nown-primaria);
                                border-radius: 8px;
                                padding: 0.25rem 0.5rem;
                                font-size: 0.8rem;
                                transition: all 0.3s ease;
                                
                                &:hover {
                                    background: var(--nown-primaria);
                                    color: white;
                                }
                            }
                        }
                    }
                    
                    .grafico-conteudo {
                        width: 100%;
                        height: 350px;
                    }
                }
            }
        }

        
            .tabelas-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
                
                .tabela-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.05);
                    
                    .tabela-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 1.5rem;
                        padding-bottom: 1rem;
                        border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                        
                        .tabela-title {
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
                        
                        .ver-mais {
                            font-size: 0.8rem;
                            color: var(--nown-primaria);
                            text-decoration: none;
                            font-weight: 600;
                            
                            &:hover {
                                text-decoration: underline;
                            }
                        }
                    }
                    
                    .tabela-conteudo {
                        .table {
                            margin: 0;
                            
                            thead {
                                th {
                                    border: none;
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                    color: #2c3e50;
                                    font-weight: 600;
                                    font-size: 0.85rem;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    padding: 1rem 0.75rem;
                                }
                            }
                            
                            tbody {
                                td {
                                    border: none;
                                    padding: 0.75rem;
                                    vertical-align: middle;
                                    
                                    &.fonte-item {
                                        font-weight: 600;
                                        color: #2c3e50;
                                    }
                                    
                                    &.metrica-valor {
                                        font-weight: 700;
                                        color: var(--nown-primaria);
                                    }
                                    
                                    .progress {
                                        height: 6px;
                                        background: rgba(236, 1, 102, 0.1);
                                        
                                        .progress-bar {
                                            background: linear-gradient(90deg, var(--nown-primaria), var(--nown-primaria-darker));
                                        }
                                    }
                                }
                                
                                tr {
                                    transition: all 0.3s ease;
                                    
                                    &:hover {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        

        #secao-analytics-tempo-real {
            .tempo-real-container {
                background: linear-gradient(135deg, rgba(0, 0, 128, 0.05) 0%, rgba(236, 1, 102, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                
                .tempo-real-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 1.5rem;
                    
                    .header-title {
                        display: flex;
                        align-items: center;
                        
                        .pulse-icon {
                            width: 40px;
                            height: 40px;
                            border-radius: 10px;
                            background: linear-gradient(135deg, #4CAF50, #388E3C);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 1rem;
                            animation: pulse 2s infinite;
                            
                            i {
                                color: white;
                                font-size: 1.2rem;
                            }
                        }
                        
                        .title-text {
                            h5 {
                                margin: 0;
                                font-weight: 700;
                                color: #2c3e50;
                            }
                            
                            .subtitle {
                                font-size: 0.8rem;
                                color: #6c757d;
                                margin: 0;
                            }
                        }
                    }
                    
                    .usuarios-online {
                        display: flex;
                        align-items: center;
                        
                        .online-count {
                            font-size: 1.5rem;
                            font-weight: 800;
                            color: #4CAF50;
                            margin-right: 0.5rem;
                        }
                        
                        .online-label {
                            font-size: 0.8rem;
                            color: #6c757d;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                    }
                }
                
                .tempo-real-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1rem;
                    
                    .tempo-real-metric {
                        background: rgba(255,255,255,0.7);
                        border-radius: 12px;
                        padding: 1rem;
                        text-align: center;
                        backdrop-filter: blur(10px);
                        
                        .metric-value {
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

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Dark Mode Styles */
        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-filtros {
                .filtros-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .filtros-header {
                        h5 {
                            color: var(--nown-terciaria);
                        }
                    }
                    
                    .filtros-controles {
                        .form-control, .form-select {
                            background-color: #3a3a3a;
                            border-color: #555;
                            color: var(--nown-terciaria);
                            
                            &::placeholder {
                                color: #aaa;
                            }
                        }
                    }
                }
            }
            
            #secao-metricas-principais {
                .metricas-grid {
                    .metrica-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .metrica-label {
                            color: #aaa;
                        }
                        
                        .metrica-comparacao {
                            color: #aaa;
                        }
                    }
                }
            }
            
            #secao-graficos-principais {
                .graficos-container {
                    .grafico-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .grafico-header {
                            .grafico-title {
                                h6 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                    }
                }
            }
            
           
                .tabelas-container {
                    .tabela-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .tabela-header {
                            .tabela-title {
                                h6 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                        
                        .tabela-conteudo {
                            .table {
                                thead {
                                    th {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.08), rgba(0, 0, 128, 0.08));
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                tbody {
                                    td {
                                        color: #ccc;
                                        
                                        &.fonte-item {
                                            color: var(--nown-terciaria);
                                        }
                                        
                                        .progress {
                                            background: rgba(236, 1, 102, 0.15);
                                        }
                                    }
                                    
                                    tr:hover {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                    }
                                }
                            }
                        }
                    }
                }
            
            
            #secao-analytics-tempo-real {
                .tempo-real-container {
                    background: linear-gradient(135deg, rgba(0, 0, 128, 0.08) 0%, rgba(236, 1, 102, 0.08) 100%);
                    
                    .tempo-real-header {
                        .header-title {
                            .title-text {
                                h5 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                    }
                    
                    .tempo-real-grid {
                        .tempo-real-metric {
                            background: rgba(58, 58, 58, 0.7);
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
    <div class="container-nown py-4">
        <div class="page-header">
            <h1 class="text-center"><i class="bi bi-graph-up me-3"></i>Dashboard Analytics</h1>
            <p class="subtitle">Análise completa do desempenho do seu site</p>
        </div>

    
        <!-- Seção de Analytics em Tempo Real -->
        <section id="secao-analytics-tempo-real">
            <div class="tempo-real-container">
                <div class="tempo-real-header">
                    <div class="header-title">
                        <div class="pulse-icon">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <div class="title-text">
                            <h5>Analytics em Tempo Real</h5>
                            <p class="subtitle">Dados atualizados a cada 30 segundos</p>
                        </div>
                    </div>
                    <div class="usuarios-online">
                        <div class="online-count" id="usuariosOnline">0</div>
                        <div class="online-label">Online Agora</div>
                    </div>
                </div>
                <div class="tempo-real-grid">
                    <div class="tempo-real-metric">
                        <div class="metric-value" id="usuariosLogados">0</div>
                        <div class="metric-label">Usuários Logados</div>
                    </div>
                    <div class="tempo-real-metric">
                        <div class="metric-value" id="usuariosDeslogados">0</div>
                        <div class="metric-label">Usuários Deslogados</div>
                    </div>
                    <div class="tempo-real-metric">
                        <div class="metric-value" id="paginasAbertas">0</div>
                        <div class="metric-label">Páginas Abertas</div>
                    </div>
                    <div class="tempo-real-metric">
                        <div class="metric-value" id="cidadesAbertas">0</div>
                        <div class="metric-label">Cidades Acessando</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção de Gráficos Principais -->
        <section id="secao-graficos-principais">
            <div class="graficos-container">
                <div class="grafico-card">
                    <div class="grafico-header">
                        <div class="grafico-title">
                            <div class="title-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h6>Tráfego ao Longo do Tempo</h6>
                        </div>
                        <div class="grafico-opcoes">
                            <button class="btn-opcao" data-metric="visitantes">Visitantes</button>
                            <button class="btn-opcao" data-metric="pageviews">Pageviews</button>
                            <button class="btn-opcao" data-metric="sessoes">Sessões</button>
                        </div>
                    </div>
                    <div class="grafico-conteudo" id="graficoTrafego"></div>
                </div>
                <div class="tabelas-container">
                    <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                              <div class="title-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h6>Usuários Logados</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaLogados">
                              
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                 
            </div>
        </section>
        
         <section id="secao-metricas-principais">
            <div class="metricas-grid">
                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon visitantes">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="metrica-variacao positiva">
                            <i class="bi bi-arrow-up"></i>
                            +12.5%
                        </div>
                    </div>
                    <div class="metrica-valor" id="totalVisitantes">48,592</div>
                    <div class="metrica-label">Visitantes Únicos</div>
                    <div class="metrica-comparacao">vs. período anterior: +5,247 visitantes</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon pageviews">
                            <i class="bi bi-eye"></i>
                        </div>
                        <div class="metrica-variacao positiva">
                            <i class="bi bi-arrow-up"></i>
                            +8.3%
                        </div>
                    </div>
                    <div class="metrica-valor" id="totalPageviews">127,489</div>
                    <div class="metrica-label">Visualizações</div>
                    <div class="metrica-comparacao">vs. período anterior: +9,842 views</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon sessoes">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="metrica-variacao positiva">
                            <i class="bi bi-arrow-up"></i>
                            +15.7%
                        </div>
                    </div>
                    <div class="metrica-valor" id="totalSessoes">34,756</div>
                    <div class="metrica-label">Sessões</div>
                    <div class="metrica-comparacao">vs. período anterior: +4,726 sessões</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon bounce">
                            <i class="bi bi-arrow-return-left"></i>
                        </div>
                        <div class="metrica-variacao negativa">
                            <i class="bi bi-arrow-down"></i>
                            -2.1%
                        </div>
                    </div>
                    <div class="metrica-valor" id="taxaBounce">45.8%</div>
                    <div class="metrica-label">Taxa de Rejeição</div>
                    <div class="metrica-comparacao">vs. período anterior: -1.02%</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon duracao">
                            <i class="bi bi-stopwatch"></i>
                        </div>
                        <div class="metrica-variacao positiva">
                            <i class="bi bi-arrow-up"></i>
                            +6.4%
                        </div>
                    </div>
                    <div class="metrica-valor" id="duracaoMedia">2m 34s</div>
                    <div class="metrica-label">Duração Média</div>
                    <div class="metrica-comparacao">vs. período anterior: +9s</div>
                </div>

                <div class="metrica-card">
                    <div class="metrica-header">
                        <div class="metrica-icon conversoes">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="metrica-variacao positiva">
                            <i class="bi bi-arrow-up"></i>
                            +18.9%
                        </div>
                    </div>
                    <div class="metrica-valor" id="totalConversoes">1,847</div>
                    <div class="metrica-label">Conversões</div>
                    <div class="metrica-comparacao">vs. período anterior: +294 conversões</div>
                </div>
            </div>
        </section>


        <!-- Seção de Tabelas Detalhadas -->
        <section id="secao-tabelas-detalhadas">
            <div class="tabelas-container">
                <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                            <div class="title-icon">
                                <i class="bi bi-globe"></i>
                            </div>
                            <h6>Fontes de Tráfego</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fonte</th>
                                    <th>Visitantes</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-search me-2"></i>Busca Orgânica
                                    </td>
                                    <td class="metrica-valor">18,456</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 38%"></div>
                                        </div>
                                        <small>38.0%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-link-45deg me-2"></i>Tráfego Direto
                                    </td>
                                    <td class="metrica-valor">14,892</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 30.6%"></div>
                                        </div>
                                        <small>30.6%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-share me-2"></i>Redes Sociais
                                    </td>
                                    <td class="metrica-valor">8,734</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 18%"></div>
                                        </div>
                                        <small>18.0%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-envelope me-2"></i>Email Marketing
                                    </td>
                                    <td class="metrica-valor">4,127</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 8.5%"></div>
                                        </div>
                                        <small>8.5%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-megaphone me-2"></i>Campanhas Pagas
                                    </td>
                                    <td class="metrica-valor">2,383</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 4.9%"></div>
                                        </div>
                                        <small>4.9%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                            <div class="title-icon">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <h6>Páginas Mais Visitadas</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Página</th>
                                    <th>Visualizações</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fonte-item">/home</td>
                                    <td class="metrica-valor">42,891</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 33.6%"></div>
                                        </div>
                                        <small>33.6%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">/produtos</td>
                                    <td class="metrica-valor">18,234</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 14.3%"></div>
                                        </div>
                                        <small>14.3%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">/sobre</td>
                                    <td class="metrica-valor">12,456</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 9.8%"></div>
                                        </div>
                                        <small>9.8%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">/contato</td>
                                    <td class="metrica-valor">8,921</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 7%"></div>
                                        </div>
                                        <small>7.0%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">/blog</td>
                                    <td class="metrica-valor">6,734</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 5.3%"></div>
                                        </div>
                                        <small>5.3%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                            <div class="title-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <h6>Localização dos Visitantes</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>País</th>
                                    <th>Visitantes</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fonte-item">
                                        🇧🇷 Brasil
                                    </td>
                                    <td class="metrica-valor">28,945</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 59.6%"></div>
                                        </div>
                                        <small>59.6%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        🇺🇸 Estados Unidos
                                    </td>
                                    <td class="metrica-valor">7,234</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 14.9%"></div>
                                        </div>
                                        <small>14.9%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        🇦🇷 Argentina
                                    </td>
                                    <td class="metrica-valor">4,567</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 9.4%"></div>
                                        </div>
                                        <small>9.4%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        🇵🇹 Portugal
                                    </td>
                                    <td class="metrica-valor">3,891</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 8%"></div>
                                        </div>
                                        <small>8.0%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        🇪🇸 Espanha
                                    </td>
                                    <td class="metrica-valor">2,345</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 4.8%"></div>
                                        </div>
                                        <small>4.8%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                            <div class="title-icon">
                                <i class="bi bi-browser-chrome"></i>
                            </div>
                            <h6>Navegadores</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Navegador</th>
                                    <th>Visitantes</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-chrome me-2"></i>Chrome
                                    </td>
                                    <td class="metrica-valor">31,247</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 64.3%"></div>
                                        </div>
                                        <small>64.3%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-safari me-2"></i>Safari
                                    </td>
                                    <td class="metrica-valor">8,934</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 18.4%"></div>
                                        </div>
                                        <small>18.4%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-firefox me-2"></i>Firefox
                                    </td>
                                    <td class="metrica-valor">4,567</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 9.4%"></div>
                                        </div>
                                        <small>9.4%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-edge me-2"></i>Edge
                                    </td>
                                    <td class="metrica-valor">2,891</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 5.9%"></div>
                                        </div>
                                        <small>5.9%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-phone me-2"></i>Mobile Browser
                                    </td>
                                    <td class="metrica-valor">953</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 2%"></div>
                                        </div>
                                        <small>2.0%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                 <div class="tabela-card">
                    <div class="tabela-header">
                        <div class="tabela-title">
                            <div class="title-icon">
                                <i class="bi bi-browser-chrome"></i>
                            </div>
                            <h6>Navegadores</h6>
                        </div>
                        <a href="#" class="ver-mais">Ver relatório completo</a>
                    </div>
                    <div class="tabela-conteudo">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Navegador</th>
                                    <th>Visitantes</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-chrome me-2"></i>Chrome
                                    </td>
                                    <td class="metrica-valor">31,247</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 64.3%"></div>
                                        </div>
                                        <small>64.3%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-safari me-2"></i>Safari
                                    </td>
                                    <td class="metrica-valor">8,934</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 18.4%"></div>
                                        </div>
                                        <small>18.4%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-firefox me-2"></i>Firefox
                                    </td>
                                    <td class="metrica-valor">4,567</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 9.4%"></div>
                                        </div>
                                        <small>9.4%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-browser-edge me-2"></i>Edge
                                    </td>
                                    <td class="metrica-valor">2,891</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 5.9%"></div>
                                        </div>
                                        <small>5.9%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fonte-item">
                                        <i class="bi bi-phone me-2"></i>Mobile Browser
                                    </td>
                                    <td class="metrica-valor">953</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 2%"></div>
                                        </div>
                                        <small>2.0%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
