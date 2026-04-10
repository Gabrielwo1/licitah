<style>
        /* User Navigation Analytics Styles */
        #secao-header-usuario {
            .usuario-container {
                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05) 0%, rgba(0, 0, 128, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                
                .usuario-info {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    
                    .usuario-avatar {
                        width: 80px;
                        height: 80px;
                        border-radius: 20px;
                        background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 1.5rem;
                        position: relative;
                        
                        i {
                            color: white;
                            font-size: 2rem;
                        }
                        
                        .status-badge {
                            position: absolute;
                            bottom: -5px;
                            right: -5px;
                            width: 20px;
                            height: 20px;
                            border-radius: 50%;
                            border: 3px solid white;
                            
                            &.online {
                                background: #4CAF50;
                            }
                            
                            &.offline {
                                background: #9E9E9E;
                            }
                        }
                    }
                    
                    .usuario-detalhes {
                        flex: 1;
                        
                        .usuario-id {
                            font-family: 'Courier New', monospace;
                            font-size: 1.1rem;
                            font-weight: 600;
                            color: var(--nown-primaria);
                            background: rgba(236, 1, 102, 0.1);
                            padding: 0.4rem 0.8rem;
                            border-radius: 8px;
                            margin-bottom: 0.5rem;
                            display: inline-block;
                        }
                        
                        .usuario-tipo {
                            display: flex;
                            align-items: center;
                            margin-bottom: 0.5rem;
                            
                            .tipo-badge {
                                background: linear-gradient(135deg, #4CAF50, #388E3C);
                                color: white;
                                padding: 0.25rem 0.75rem;
                                border-radius: 15px;
                                font-size: 0.8rem;
                                font-weight: 600;
                                margin-right: 0.5rem;
                                
                                &.novo {
                                    background: linear-gradient(135deg, #2196F3, #1976D2);
                                }
                                
                                &.frequente {
                                    background: linear-gradient(135deg, #FF9800, #F57C00);
                                }
                            }
                            
                            .primeira-visita {
                                font-size: 0.85rem;
                                color: #6c757d;
                            }
                        }
                        
                        .usuario-localizacao {
                            display: flex;
                            align-items: center;
                            color: #6c757d;
                            font-size: 0.9rem;
                            
                            i {
                                margin-right: 0.5rem;
                                color: var(--nown-primaria);
                            }
                        }
                    }
                    
                    .usuario-acoes {
                        display: flex;
                        flex-direction: column;
                        gap: 0.5rem;
                        
                        .btn-acao {
                            border-radius: 10px;
                            font-weight: 600;
                            padding: 0.5rem 1rem;
                            transition: all 0.3s ease;
                            white-space: nowrap;
                            
                            &:hover {
                                transform: translateY(-2px);
                            }
                        }
                    }
                }
                
                .usuario-stats {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                    gap: 1rem;
                    
                    .stat-item {
                        background: rgba(255,255,255,0.7);
                        border-radius: 12px;
                        padding: 1rem;
                        text-align: center;
                        backdrop-filter: blur(10px);
                        
                        .stat-valor {
                            font-size: 1.3rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .stat-label {
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

        #secao-timeline-sessoes {
            .timeline-container {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                border: 1px solid rgba(0,0,0,0.05);
                
                .timeline-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 2rem;
                    padding-bottom: 1rem;
                    border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                    
                    .timeline-title {
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
                    
                    .timeline-filtros {
                        display: flex;
                        gap: 0.5rem;
                        
                        .btn-filtro {
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
                
                .timeline-content {
                    position: relative;
                    
                    &:before {
                        content: '';
                        position: absolute;
                        left: 20px;
                        top: 0;
                        bottom: 0;
                        width: 2px;
                        background: linear-gradient(180deg, var(--nown-primaria), var(--nown-primaria-darker));
                    }
                    
                    .sessao-item {
                        position: relative;
                        margin-bottom: 2rem;
                        margin-left: 50px;
                        
                        &:before {
                            content: '';
                            position: absolute;
                            left: -38px;
                            top: 15px;
                            width: 12px;
                            height: 12px;
                            border-radius: 50%;
                            background: var(--nown-primaria);
                            border: 3px solid white;
                            box-shadow: 0 0 0 2px var(--nown-primaria);
                        }
                        
                        .sessao-card {
                            background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                            border: 1px solid rgba(236, 1, 102, 0.1);
                            border-radius: 15px;
                            padding: 1.5rem;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                                border-color: var(--nown-primaria);
                            }
                            
                            .sessao-header {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin-bottom: 1rem;
                                
                                .sessao-info {
                                    display: flex;
                                    align-items: center;
                                    
                                    .sessao-icon {
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
                                    
                                    .sessao-detalhes {
                                        .sessao-data {
                                            font-weight: 700;
                                            color: #2c3e50;
                                            margin-bottom: 0.1rem;
                                        }
                                        
                                        .sessao-dispositivo {
                                            font-size: 0.8rem;
                                            color: #6c757d;
                                            margin: 0;
                                        }
                                    }
                                }
                                
                                .sessao-metricas {
                                    display: flex;
                                    gap: 1rem;
                                    
                                    .metrica {
                                        text-align: center;
                                        
                                        .metrica-valor {
                                            font-weight: 700;
                                            color: var(--nown-primaria);
                                            font-size: 0.9rem;
                                        }
                                        
                                        .metrica-label {
                                            font-size: 0.7rem;
                                            color: #6c757d;
                                            text-transform: uppercase;
                                            letter-spacing: 0.5px;
                                        }
                                    }
                                }
                            }
                            
                            .sessao-paginas {
                                .paginas-header {
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    color: #6c757d;
                                    margin-bottom: 0.75rem;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                }
                                
                                .pagina-item {
                                    display: flex;
                                    align-items: center;
                                    padding: 0.5rem 0;
                                    border-bottom: 1px solid rgba(0,0,0,0.05);
                                    
                                    &:last-child {
                                        border-bottom: none;
                                    }
                                    
                                    .pagina-numero {
                                        width: 20px;
                                        height: 20px;
                                        border-radius: 50%;
                                        background: rgba(236, 1, 102, 0.1);
                                        color: var(--nown-primaria);
                                        font-size: 0.7rem;
                                        font-weight: 700;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin-right: 0.75rem;
                                        flex-shrink: 0;
                                    }
                                    
                                    .pagina-info {
                                        flex: 1;
                                        min-width: 0;
                                        
                                        .pagina-url {
                                            font-family: 'Courier New', monospace;
                                            font-size: 0.8rem;
                                            font-weight: 600;
                                            color: #2c3e50;
                                            margin-bottom: 0.1rem;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        }
                                        
                                        .pagina-titulo {
                                            font-size: 0.75rem;
                                            color: #6c757d;
                                            margin: 0;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        }
                                    }
                                    
                                    .pagina-tempo {
                                        font-size: 0.75rem;
                                        color: var(--nown-primaria);
                                        font-weight: 600;
                                        text-align: right;
                                        margin-left: 0.5rem;
                                        flex-shrink: 0;
                                    }
                                }
                            }
                            
                            .sessao-eventos {
                                margin-top: 1rem;
                                padding-top: 1rem;
                                border-top: 1px solid rgba(0,0,0,0.05);
                                
                                .eventos-header {
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    color: #6c757d;
                                    margin-bottom: 0.75rem;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                }
                                
                                .evento-item {
                                    display: flex;
                                    align-items: center;
                                    padding: 0.4rem 0;
                                    
                                    .evento-icon {
                                        width: 16px;
                                        height: 16px;
                                        border-radius: 3px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin-right: 0.5rem;
                                        flex-shrink: 0;
                                        
                                        i {
                                            font-size: 0.7rem;
                                            color: white;
                                        }
                                        
                                        &.click { background: #4CAF50; }
                                        &.scroll { background: #2196F3; }
                                        &.form { background: #FF9800; }
                                        &.download { background: #9C27B0; }
                                    }
                                    
                                    .evento-texto {
                                        font-size: 0.75rem;
                                        color: #6c757d;
                                        margin: 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        #secao-padroes-comportamento {
            .comportamento-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
                
                .comportamento-card {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.05);
                    
                    .comportamento-header {
                        display: flex;
                        align-items: center;
                        margin-bottom: 1.5rem;
                        padding-bottom: 1rem;
                        border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                        
                        .header-icon {
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
                    
                    .grafico-content {
                        width: 100%;
                        height: 200px;
                    }
                    
                    .dados-lista {
                        .dados-item {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 0.75rem 0;
                            border-bottom: 1px solid rgba(0,0,0,0.05);
                            
                            &:last-child {
                                border-bottom: none;
                            }
                            
                            .item-info {
                                display: flex;
                                align-items: center;
                                
                                .item-icon {
                                    width: 25px;
                                    height: 25px;
                                    border-radius: 5px;
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(0, 0, 128, 0.1));
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    
                                    i {
                                        color: var(--nown-primaria);
                                        font-size: 0.8rem;
                                    }
                                }
                                
                                .item-texto {
                                    font-weight: 600;
                                    color: #2c3e50;
                                    font-size: 0.9rem;
                                }
                            }
                            
                            .item-valor {
                                font-weight: 700;
                                color: var(--nown-primaria);
                                font-size: 0.9rem;
                            }
                        }
                    }
                }
            }
        }

        #secao-jornada-usuario {
            .jornada-container {
                background: linear-gradient(135deg, rgba(0, 0, 128, 0.05) 0%, rgba(236, 1, 102, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                
                .jornada-header {
                    display: flex;
                    align-items: center;
                    justify-content: between;
                    margin-bottom: 2rem;
                    
                    .header-title {
                        display: flex;
                        align-items: center;
                        
                        .title-icon {
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
                        
                        .title-text {
                            h5 {
                                margin: 0;
                                font-weight: 700;
                                color: #2c3e50;
                            }
                            
                            .subtitle {
                                font-size: 0.9rem;
                                color: #6c757d;
                                margin: 0;
                            }
                        }
                    }
                }
                
                .jornada-fluxo {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    overflow-x: auto;
                    padding: 1rem 0;
                    
                    .jornada-step {
                        flex-shrink: 0;
                        text-align: center;
                        position: relative;
                        
                        .step-circle {
                            width: 60px;
                            height: 60px;
                            border-radius: 50%;
                            background: rgba(255,255,255,0.9);
                            border: 3px solid var(--nown-primaria);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 0.5rem;
                            
                            i {
                                color: var(--nown-primaria);
                                font-size: 1.2rem;
                            }
                            
                            &.active {
                                background: var(--nown-primaria);
                                
                                i {
                                    color: white;
                                }
                            }
                        }
                        
                        .step-label {
                            font-size: 0.75rem;
                            font-weight: 600;
                            color: #2c3e50;
                            margin-bottom: 0.25rem;
                            max-width: 80px;
                        }
                        
                        .step-count {
                            font-size: 0.7rem;
                            color: #6c757d;
                            margin: 0;
                        }
                        
                        &:not(:last-child):after {
                            content: '';
                            position: absolute;
                            top: 30px;
                            right: -30px;
                            width: 60px;
                            height: 2px;
                            background: linear-gradient(90deg, var(--nown-primaria), var(--nown-primaria-darker));
                            z-index: -1;
                        }
                    }
                }
            }
        }

        /* Dark Mode Styles */
        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-header-usuario {
                .usuario-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .usuario-info {
                        .usuario-detalhes {
                            .usuario-localizacao {
                                color: #aaa;
                            }
                        }
                    }
                    
                    .usuario-stats {
                        .stat-item {
                            background: rgba(58, 58, 58, 0.7);
                        }
                    }
                }
            }
            
            #secao-timeline-sessoes {
                .timeline-container {
                    background: #2d2d2d;
                    border-color: #444;
                    
                    .timeline-header {
                        .timeline-title {
                            h5 {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                    
                    .timeline-content {
                        .sessao-item {
                            .sessao-card {
                                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                border-color: rgba(236, 1, 102, 0.2);
                                
                                .sessao-header {
                                    .sessao-info {
                                        .sessao-detalhes {
                                            .sessao-data {
                                                color: var(--nown-terciaria);
                                            }
                                        }
                                    }
                                }
                                
                                .sessao-paginas {
                                    .pagina-item {
                                        border-bottom-color: rgba(255,255,255,0.1);
                                        
                                        .pagina-info {
                                            .pagina-url {
                                                color: var(--nown-terciaria);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            #secao-padroes-comportamento {
                .comportamento-grid {
                    .comportamento-card {
                        background: #2d2d2d;
                        border-color: #444;
                        
                        .comportamento-header {
                            h6 {
                                color: var(--nown-terciaria);
                            }
                        }
                        
                        .dados-lista {
                            .dados-item {
                                border-bottom-color: rgba(255,255,255,0.1);
                                
                                .item-info {
                                    .item-texto {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            #secao-jornada-usuario {
                .jornada-container {
                    background: linear-gradient(135deg, rgba(0, 0, 128, 0.08) 0%, rgba(236, 1, 102, 0.08) 100%);
                    
                    .jornada-header {
                        .header-title {
                            .title-text {
                                h5 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                    }
                    
                    .jornada-fluxo {
                        .jornada-step {
                            .step-circle {
                                background: rgba(58, 58, 58, 0.9);
                            }
                            
                            .step-label {
                                color: var(--nown-terciaria);
                            }
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

        /* Responsividade */
        @media (max-width: 768px) {
            #secao-header-usuario {
                .usuario-container {
                    .usuario-info {
                        flex-direction: column;
                        text-align: center;
                        
                        .usuario-acoes {
                            flex-direction: row;
                            margin-top: 1rem;
                        }
                    }
                }
            }
            
            #secao-jornada-usuario {
                .jornada-container {
                    .jornada-fluxo {
                        justify-content: flex-start;
                        
                        .jornada-step {
                            &:not(:last-child):after {
                                display: none;
                            }
                        }
                    }
                }
            }
        }
    </style>
    <div class="container-fluid py-4">
        <!-- Header da Página -->
        <div class="page-header">
            <h1><i class="bi bi-person-lines-fill me-3"></i>Navegação do Usuário</h1>
            <p class="subtitle">Análise detalhada do comportamento e jornada de um usuário específico</p>
        </div>

        <!-- Seção Header do Usuário -->
        <section id="secao-header-usuario">
            <div class="usuario-container">
                <div class="usuario-info">
                    <div class="usuario-avatar">
                        <i class="bi bi-person"></i>
                        <div class="status-badge offline"></div>
                    </div>
                    <div class="usuario-detalhes">
                        <div class="usuario-id">USER-789456123</div>
                        <div class="usuario-tipo">
                            <span class="tipo-badge frequente">Usuário Frequente</span>
                            <span class="primeira-visita">Primeira visita: 15/03/2025</span>
                        </div>
                        <div class="usuario-localizacao">
                            <i class="bi bi-geo-alt"></i>
                            São Paulo, SP - Brasil | Chrome 120 no Windows
                        </div>
                    </div>
                    <div class="usuario-acoes">
                        <button class="btn btn-n-primaria btn-acao">
                            <i class="bi bi-graph-up me-1"></i>Ver Funil
                        </button>
                        <button class="btn btn-outline-secondary btn-acao">
                            <i class="bi bi-tag me-1"></i>Segmentar
                        </button>
                        <button class="btn btn-outline-secondary btn-acao">
                            <i class="bi bi-envelope me-1"></i>Contatar
                        </button>
                    </div>
                </div>
                <div class="usuario-stats">
                    <div class="stat-item">
                        <div class="stat-valor" id="totalSessoes">47</div>
                        <div class="stat-label">Sessões</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-valor" id="totalPageviews">284</div>
                        <div class="stat-label">Páginas Vistas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-valor" id="tempoTotal">12h 34m</div>
                        <div class="stat-label">Tempo Total</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-valor" id="ultimaVisita">2 dias</div>
                        <div class="stat-label">Última Visita</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-valor" id="valorUsuario">R$ 2.347</div>
                        <div class="stat-label">Valor Gerado</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-valor" id="conversoes">3</div>
                        <div class="stat-label">Conversões</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Timeline de Sessões -->
        <section id="secao-timeline-sessoes">
            <div class="timeline-container">
                <div class="timeline-header">
                    <div class="timeline-title">
                        <div class="title-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5>Histórico de Sessões</h5>
                    </div>
                    <div class="timeline-filtros">
                        <button class="btn-filtro active">Todas</button>
                        <button class="btn-filtro">Últimos 7 dias</button>
                        <button class="btn-filtro">Conversões</button>
                        <button class="btn-filtro">Mobile</button>
                    </div>
                </div>
                <div class="timeline-content">
                    <!-- Sessão 1 - Mais Recente -->
                    <div class="sessao-item">
                        <div class="sessao-card">
                            <div class="sessao-header">
                                <div class="sessao-info">
                                    <div class="sessao-icon">
                                        <i class="bi bi-laptop"></i>
                                    </div>
                                    <div class="sessao-detalhes">
                                        <div class="sessao-data">09/06/2025 - 14:32</div>
                                        <div class="sessao-dispositivo">Desktop • Chrome • São Paulo</div>
                                    </div>
                                </div>
                                <div class="sessao-metricas">
                                    <div class="metrica">
                                        <div class="metrica-valor">8</div>
                                        <div class="metrica-label">Páginas</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">12m 45s</div>
                                        <div class="metrica-label">Duração</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">R$ 4.599</div>
                                        <div class="metrica-label">Compra</div>
                                    </div>
                                </div>
                            </div>
                            <div class="sessao-paginas">
                                <div class="paginas-header">Páginas Visitadas</div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">1</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/home</div>
                                        <div class="pagina-titulo">Página Inicial</div>
                                    </div>
                                    <div class="pagina-tempo">2m 15s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">2</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/produtos/smartphones</div>
                                        <div class="pagina-titulo">Smartphones - Loja</div>
                                    </div>
                                    <div class="pagina-tempo">3m 22s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">3</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/produtos/galaxy-s24-ultra</div>
                                        <div class="pagina-titulo">Galaxy S24 Ultra - Detalhes</div>
                                    </div>
                                    <div class="pagina-tempo">4m 12s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">4</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/carrinho</div>
                                        <div class="pagina-titulo">Carrinho de Compras</div>
                                    </div>
                                    <div class="pagina-tempo">1m 34s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">5</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/checkout</div>
                                        <div class="pagina-titulo">Finalizar Compra</div>
                                    </div>
                                    <div class="pagina-tempo">1m 22s</div>
                                </div>
                            </div>
                            <div class="sessao-eventos">
                                <div class="eventos-header">Eventos e Interações</div>
                                <div class="evento-item">
                                    <div class="evento-icon click">
                                        <i class="bi bi-cursor"></i>
                                    </div>
                                    <div class="evento-texto">Clique em "Adicionar ao Carrinho" - Galaxy S24</div>
                                </div>
                                <div class="evento-item">
                                    <div class="evento-icon scroll">
                                        <i class="bi bi-arrow-down"></i>
                                    </div>
                                    <div class="evento-texto">Scroll 85% da página de produto</div>
                                </div>
                                <div class="evento-item">
                                    <div class="evento-icon form">
                                        <i class="bi bi-card-text"></i>
                                    </div>
                                    <div class="evento-texto">Preenchimento do formulário de checkout</div>
                                </div>
                                <div class="evento-item">
                                    <div class="evento-icon click">
                                        <i class="bi bi-cursor"></i>
                                    </div>
                                    <div class="evento-texto">Conversão: Compra finalizada - R$ 4.599,00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sessão 2 -->
                    <div class="sessao-item">
                        <div class="sessao-card">
                            <div class="sessao-header">
                                <div class="sessao-info">
                                    <div class="sessao-icon">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <div class="sessao-detalhes">
                                        <div class="sessao-data">08/06/2025 - 19:15</div>
                                        <div class="sessao-dispositivo">Mobile • Chrome • São Paulo</div>
                                    </div>
                                </div>
                                <div class="sessao-metricas">
                                    <div class="metrica">
                                        <div class="metrica-valor">5</div>
                                        <div class="metrica-label">Páginas</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">6m 12s</div>
                                        <div class="metrica-label">Duração</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">-</div>
                                        <div class="metrica-label">Pesquisa</div>
                                    </div>
                                </div>
                            </div>
                            <div class="sessao-paginas">
                                <div class="paginas-header">Páginas Visitadas</div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">1</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/produtos/galaxy-s24-ultra</div>
                                        <div class="pagina-titulo">Galaxy S24 Ultra - Detalhes</div>
                                    </div>
                                    <div class="pagina-tempo">2m 45s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">2</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/produtos/galaxy-s24-ultra/especificacoes</div>
                                        <div class="pagina-titulo">Especificações Técnicas</div>
                                    </div>
                                    <div class="pagina-tempo">1m 56s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">3</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/produtos/galaxy-s24-ultra/avaliacoes</div>
                                        <div class="pagina-titulo">Avaliações de Clientes</div>
                                    </div>
                                    <div class="pagina-tempo">1m 31s</div>
                                </div>
                            </div>
                            <div class="sessao-eventos">
                                <div class="eventos-header">Eventos e Interações</div>
                                <div class="evento-item">
                                    <div class="evento-icon scroll">
                                        <i class="bi bi-arrow-down"></i>
                                    </div>
                                    <div class="evento-texto">Leitura completa das especificações</div>
                                </div>
                                <div class="evento-item">
                                    <div class="evento-icon click">
                                        <i class="bi bi-cursor"></i>
                                    </div>
                                    <div class="evento-texto">Visualização de 4 avaliações de clientes</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sessão 3 -->
                    <div class="sessao-item">
                        <div class="sessao-card">
                            <div class="sessao-header">
                                <div class="sessao-info">
                                    <div class="sessao-icon">
                                        <i class="bi bi-laptop"></i>
                                    </div>
                                    <div class="sessao-detalhes">
                                        <div class="sessao-data">07/06/2025 - 10:22</div>
                                        <div class="sessao-dispositivo">Desktop • Chrome • São Paulo</div>
                                    </div>
                                </div>
                                <div class="sessao-metricas">
                                    <div class="metrica">
                                        <div class="metrica-valor">12</div>
                                        <div class="metrica-label">Páginas</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">18m 33s</div>
                                        <div class="metrica-label">Duração</div>
                                    </div>
                                    <div class="metrica">
                                        <div class="metrica-valor">-</div>
                                        <div class="metrica-label">Navegação</div>
                                    </div>
                                </div>
                            </div>
                            <div class="sessao-paginas">
                                <div class="paginas-header">Páginas Visitadas (Resumo - 12 páginas)</div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">1</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/home</div>
                                        <div class="pagina-titulo">Página Inicial</div>
                                    </div>
                                    <div class="pagina-tempo">3m 12s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">...</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">+ 10 páginas</div>
                                        <div class="pagina-titulo">Navegação exploratória</div>
                                    </div>
                                    <div class="pagina-tempo">14m 08s</div>
                                </div>
                                <div class="pagina-item">
                                    <div class="pagina-numero">12</div>
                                    <div class="pagina-info">
                                        <div class="pagina-url">/contato</div>
                                        <div class="pagina-titulo">Página de Contato</div>
                                    </div>
                                    <div class="pagina-tempo">1m 13s</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Padrões de Comportamento -->
        <section id="secao-padroes-comportamento">
            <div class="comportamento-grid">
                <!-- Horários de Acesso -->
                <div class="comportamento-card">
                    <div class="comportamento-header">
                        <div class="header-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h6>Horários de Acesso</h6>
                    </div>
                    <div class="grafico-content" id="graficoHorarios"></div>
                </div>

                <!-- Dias da Semana -->
                <div class="comportamento-card">
                    <div class="comportamento-header">
                        <div class="header-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <h6>Dias Preferidos</h6>
                    </div>
                    <div class="dados-lista">
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-1-circle"></i>
                                </div>
                                <div class="item-texto">Segunda-feira</div>
                            </div>
                            <div class="item-valor">12 sessões</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-2-circle"></i>
                                </div>
                                <div class="item-texto">Sábado</div>
                            </div>
                            <div class="item-valor">9 sessões</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-3-circle"></i>
                                </div>
                                <div class="item-texto">Terça-feira</div>
                            </div>
                            <div class="item-valor">8 sessões</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-4-circle"></i>
                                </div>
                                <div class="item-texto">Domingo</div>
                            </div>
                            <div class="item-valor">7 sessões</div>
                        </div>
                    </div>
                </div>

                <!-- Dispositivos Utilizados -->
                <div class="comportamento-card">
                    <div class="comportamento-header">
                        <div class="header-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h6>Dispositivos</h6>
                    </div>
                    <div class="grafico-content" id="graficoDispositivos"></div>
                </div>

                <!-- Categorias de Interesse -->
                <div class="comportamento-card">
                    <div class="comportamento-header">
                        <div class="header-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <h6>Interesses</h6>
                    </div>
                    <div class="dados-lista">
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="item-texto">Smartphones</div>
                            </div>
                            <div class="item-valor">89 páginas</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-headphones"></i>
                                </div>
                                <div class="item-texto">Áudio</div>
                            </div>
                            <div class="item-valor">34 páginas</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="item-texto">Informática</div>
                            </div>
                            <div class="item-valor">28 páginas</div>
                        </div>
                        <div class="dados-item">
                            <div class="item-info">
                                <div class="item-icon">
                                    <i class="bi bi-camera"></i>
                                </div>
                                <div class="item-texto">Fotografia</div>
                            </div>
                            <div class="item-valor">15 páginas</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Jornada do Usuário -->
        <section id="secao-jornada-usuario">
            <div class="jornada-container">
                <div class="jornada-header">
                    <div class="header-title">
                        <div class="title-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <div class="title-text">
                            <h5>Jornada do Usuário</h5>
                            <p class="subtitle">Fluxo típico de navegação deste usuário</p>
                        </div>
                    </div>
                </div>
                <div class="jornada-fluxo">
                    <div class="jornada-step">
                        <div class="step-circle active">
                            <i class="bi bi-house"></i>
                        </div>
                        <div class="step-label">Página Inicial</div>
                        <div class="step-count">28 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="step-label">Busca Produtos</div>
                        <div class="step-count">22 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div class="step-label">Produto</div>
                        <div class="step-count">45 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div class="step-label">Comparação</div>
                        <div class="step-count">18 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="step-label">Carrinho</div>
                        <div class="step-count">8 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="step-label">Checkout</div>
                        <div class="step-count">3 visitas</div>
                    </div>
                    <div class="jornada-step">
                        <div class="step-circle">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="step-label">Conversão</div>
                        <div class="step-count">3 conversões</div>
                    </div>
                </div>
            </div>
        </section>
    </div>

