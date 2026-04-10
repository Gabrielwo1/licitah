<style>
        #secao-dashboard-admin {
            .dashboard-container {
                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05) 0%, rgba(0, 0, 128, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                
                .nav-tabs {
                    border: none;
                    margin-bottom: 2rem;
                    justify-content: center;
                    
                    .nav-link {
                        background: transparent;
                        border: none;
                        border-radius: 15px;
                        padding: 1rem 1.5rem;
                        margin: 0 0.25rem;
                        color: #6c757d;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                        font-size: 0.9rem;
                        
                        &:before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                            transition: left 0.5s;
                        }
                        
                        &:hover {
                            color: var(--nown-primaria);
                            background-color: rgba(236, 1, 102, 0.1);
                            transform: translateY(-2px);
                            
                            &:before {
                                left: 100%;
                            }
                        }
                        
                        &.active {
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            color: white;
                            box-shadow: 0 5px 15px rgba(236, 1, 102, 0.3);
                            transform: translateY(-2px);
                        }
                        
                        i {
                            font-size: 1rem;
                            margin-right: 0.5rem;
                        }
                        
                        .badge {
                            margin-left: 0.5rem;
                            background: rgba(255,255,255,0.2);
                            font-size: 0.7rem;
                        }
                    }
                }
                
                .tab-content {
                    .tab-pane {
                        animation: fadeIn 0.5s ease-in-out;
                    }
                }
                
                .metrics-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                    
                    .metric-card {
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
                        
                        .metric-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-bottom: 1rem;
                            
                            .metric-icon {
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
                                
                                &.revenue { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                                &.orders { background: linear-gradient(135deg, #2196F3, #1976D2); }
                                &.stores { background: linear-gradient(135deg, #FF9800, #F57C00); }
                                &.users { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                                &.products { background: linear-gradient(135deg, #00BCD4, #0097A7); }
                                &.shipping { background: linear-gradient(135deg, #FF5722, #D84315); }
                            }
                            
                            .metric-trend {
                                font-size: 0.8rem;
                                font-weight: 600;
                                padding: 0.25rem 0.5rem;
                                border-radius: 12px;
                                
                                &.positive {
                                    background: rgba(76, 175, 80, 0.1);
                                    color: #4CAF50;
                                }
                                
                                &.negative {
                                    background: rgba(244, 67, 54, 0.1);
                                    color: #f44336;
                                }
                            }
                        }
                        
                        .metric-value {
                            font-size: 1.8rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .metric-label {
                            color: #6c757d;
                            font-size: 0.9rem;
                            margin-bottom: 0.5rem;
                            font-weight: 600;
                        }
                        
                        .metric-details {
                            font-size: 0.8rem;
                            color: #6c757d;
                            margin: 0;
                        }
                    }
                }
                
                .alerts-section {
                    margin-bottom: 2rem;
                    
                    .alerts-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 1rem;
                        
                        h5 {
                            margin: 0;
                            font-weight: 700;
                            color: #2c3e50;
                            display: flex;
                            align-items: center;
                            
                            i {
                                margin-right: 0.5rem;
                                color: var(--nown-primaria);
                            }
                        }
                        
                        .view-all-btn {
                            font-size: 0.9rem;
                            border-radius: 15px;
                            padding: 0.5rem 1rem;
                        }
                    }
                    
                    .alerts-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                        gap: 1rem;
                        
                        .alert-card {
                            padding: 1rem;
                            border-radius: 12px;
                            border-left: 4px solid;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                transform: translateX(5px);
                                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                            }
                            
                            &.alert-warning {
                                background: rgba(255, 152, 0, 0.1);
                                border-left-color: #FF9800;
                            }
                            
                            &.alert-danger {
                                background: rgba(244, 67, 54, 0.1);
                                border-left-color: #f44336;
                            }
                            
                            &.alert-info {
                                background: rgba(33, 150, 243, 0.1);
                                border-left-color: #2196F3;
                            }
                            
                            .alert-content {
                                display: flex;
                                align-items: center;
                                
                                .alert-icon {
                                    margin-right: 0.75rem;
                                    font-size: 1.2rem;
                                }
                                
                                .alert-text {
                                    flex: 1;
                                    
                                    .alert-title {
                                        font-weight: 700;
                                        margin-bottom: 0.25rem;
                                        font-size: 0.9rem;
                                    }
                                    
                                    .alert-description {
                                        font-size: 0.8rem;
                                        color: #6c757d;
                                        margin: 0;
                                    }
                                }
                                
                                .alert-action {
                                    .btn {
                                        font-size: 0.8rem;
                                        padding: 0.25rem 0.75rem;
                                        border-radius: 8px;
                                    }
                                }
                            }
                        }
                    }
                }
                
                .data-tables {
                    .table-card {
                        background: white;
                        border-radius: 15px;
                        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                        border: 1px solid rgba(0,0,0,0.05);
                        margin-bottom: 2rem;
                        
                        .table-header {
                            padding: 1.5rem;
                            border-bottom: 1px solid rgba(0,0,0,0.1);
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            
                            .table-title {
                                display: flex;
                                align-items: center;
                                
                                .table-icon {
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
                                
                                h6 {
                                    margin: 0;
                                    font-weight: 700;
                                    color: #2c3e50;
                                }
                            }
                            
                            .table-actions {
                                display: flex;
                                gap: 0.5rem;
                                
                                .btn {
                                    font-size: 0.8rem;
                                    padding: 0.5rem 1rem;
                                    border-radius: 8px;
                                }
                            }
                        }
                        
                        .table-responsive {
                            .table {
                                margin: 0;
                                
                                thead {
                                    th {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                        border: none;
                                        font-weight: 700;
                                        font-size: 0.85rem;
                                        padding: 1rem;
                                        color: #2c3e50;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    }
                                }
                                
                                tbody {
                                    tr {
                                        transition: all 0.3s ease;
                                        
                                        &:hover {
                                            background-color: rgba(236, 1, 102, 0.02);
                                        }
                                        
                                        td {
                                            padding: 1rem;
                                            vertical-align: middle;
                                            font-size: 0.9rem;
                                            border-color: rgba(0,0,0,0.05);
                                            
                                            .badge {
                                                font-size: 0.75rem;
                                                padding: 0.4rem 0.8rem;
                                                border-radius: 12px;
                                                font-weight: 600;
                                            }
                                            
                                            .action-btn {
                                                font-size: 0.8rem;
                                                padding: 0.25rem 0.75rem;
                                                border-radius: 6px;
                                                margin: 0 0.25rem;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                .charts-section {
                    .charts-grid {
                        display: grid;
                        grid-template-columns: 2fr 1fr;
                        gap: 2rem;
                        margin-bottom: 2rem;
                        
                        @media (max-width: 991px) {
                            grid-template-columns: 1fr;
                        }
                        
                        .chart-card {
                            background: white;
                            border-radius: 15px;
                            padding: 1.5rem;
                            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                            border: 1px solid rgba(0,0,0,0.05);
                            
                            .chart-header {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin-bottom: 1.5rem;
                                
                                .chart-title {
                                    display: flex;
                                    align-items: center;
                                    
                                    .chart-icon {
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
                                    
                                    h6 {
                                        margin: 0;
                                        font-weight: 700;
                                        color: #2c3e50;
                                    }
                                }
                                
                                .chart-period {
                                    select {
                                        border: 1px solid #dee2e6;
                                        border-radius: 8px;
                                        padding: 0.25rem 0.75rem;
                                        font-size: 0.8rem;
                                    }
                                }
                            }
                            
                            .chart-container {
                                height: 300px;
                                width: 100%;
                            }
                        }
                    }
                }
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body.light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-dashboard-admin {
                .dashboard-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .nav-tabs {
                        .nav-link {
                            color: #ccc;
                            
                            &:hover {
                                background-color: rgba(236, 1, 102, 0.2);
                            }
                        }
                    }
                    
                    .metrics-grid {
                        .metric-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .metric-label {
                                color: #ccc;
                            }
                            
                            .metric-details {
                                color: #aaa;
                            }
                        }
                    }
                    
                    .alerts-section {
                        .alerts-header {
                            h5 {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                    
                    .data-tables {
                        .table-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .table-header {
                                border-bottom-color: #444;
                                
                                .table-title {
                                    h6 {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .table {
                                thead {
                                    th {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(0, 0, 128, 0.1));
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                tbody {
                                    tr {
                                        &:hover {
                                            background-color: rgba(236, 1, 102, 0.05);
                                        }
                                        
                                        td {
                                            color: var(--nown-terciaria);
                                            border-color: rgba(255,255,255,0.1);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    .charts-section {
                        .chart-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .chart-header {
                                .chart-title {
                                    h6 {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                        }
                    }
                }
            }
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

        .system-status {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1000;
            
            .status-indicator {
                display: flex;
                align-items: center;
                background: white;
                padding: 0.75rem 1rem;
                border-radius: 25px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border: 1px solid rgba(0,0,0,0.05);
                
                .status-dot {
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    margin-right: 0.5rem;
                    animation: pulse 2s infinite;
                    
                    &.online {
                        background: #4CAF50;
                    }
                    
                    &.warning {
                        background: #FF9800;
                    }
                    
                    &.offline {
                        background: #f44336;
                    }
                }
                
                .status-text {
                    font-size: 0.9rem;
                    font-weight: 600;
                    color: #2c3e50;
                    margin: 0;
                }
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    <!-- Status do Sistema -->
    <div class="system-status">
        <div class="status-indicator">
            <div class="status-dot online"></div>
            <div class="status-text">Sistema Online</div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Header da Página -->
        <div class="page-header">
            <h1><i class="bi bi-speedometer2 me-3"></i>Dashboard Administrativo</h1>
            <p class="subtitle">Controle total do seu marketplace, lojas e sistema de frete</p>
        </div>

        <!-- Seção Principal -->
        <section id="secao-dashboard-admin">
            <div class="dashboard-container">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="geral-tab" data-bs-toggle="tab" data-bs-target="#geral" type="button" role="tab">
                            <i class="bi bi-speedometer2"></i>Geral
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pedidos-tab" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab">
                            <i class="bi bi-bag-check"></i>Pedidos<span class="badge">47</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pagamentos-tab" data-bs-toggle="tab" data-bs-target="#pagamentos" type="button" role="tab">
                            <i class="bi bi-credit-card"></i>Pagamentos<span class="badge">12</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lojas-tab" data-bs-toggle="tab" data-bs-target="#lojas" type="button" role="tab">
                            <i class="bi bi-shop"></i>Lojas<span class="badge">8</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="frete-tab" data-bs-toggle="tab" data-bs-target="#frete" type="button" role="tab">
                            <i class="bi bi-truck"></i>Frete<span class="badge">23</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analises-tab" data-bs-toggle="tab" data-bs-target="#analises" type="button" role="tab">
                            <i class="bi bi-graph-up"></i>Análises
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="dashboardTabsContent">
                    <!-- Geral Tab -->
                    <div class="tab-pane fade show active" id="geral" role="tabpanel">
                        <!-- Métricas Principais -->
                        <div class="metrics-grid">
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon revenue">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="metric-trend positive">+18.5%</div>
                                </div>
                                <div class="metric-value">R$ 847.250</div>
                                <div class="metric-label">Faturamento Total</div>
                                <div class="metric-details">Últimos 30 dias • Meta: R$ 900K</div>
                            </div>
                            
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon orders">
                                        <i class="bi bi-bag-check"></i>
                                    </div>
                                    <div class="metric-trend positive">+12.3%</div>
                                </div>
                                <div class="metric-value">2.847</div>
                                <div class="metric-label">Pedidos Processados</div>
                                <div class="metric-details">47 aguardando aprovação</div>
                            </div>
                            
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon stores">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <div class="metric-trend positive">+5.1%</div>
                                </div>
                                <div class="metric-value">156</div>
                                <div class="metric-label">Lojas Ativas</div>
                                <div class="metric-details">8 aguardando aprovação</div>
                            </div>
                            
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon users">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="metric-trend positive">+8.7%</div>
                                </div>
                                <div class="metric-value">12.456</div>
                                <div class="metric-label">Usuários Ativos</div>
                                <div class="metric-details">3.847 novos este mês</div>
                            </div>
                            
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon products">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="metric-trend positive">+15.2%</div>
                                </div>
                                <div class="metric-value">8.924</div>
                                <div class="metric-label">Produtos Ativos</div>
                                <div class="metric-details">247 cadastrados hoje</div>
                            </div>
                            
                            <div class="metric-card">
                                <div class="metric-header">
                                    <div class="metric-icon shipping">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="metric-trend negative">-2.1%</div>
                                </div>
                                <div class="metric-value">23</div>
                                <div class="metric-label">Entregas Pendentes</div>
                                <div class="metric-details">Tempo médio: 2.3 dias</div>
                            </div>
                        </div>

                        <!-- Alertas do Sistema -->
                        <div class="alerts-section">
                            <div class="alerts-header">
                                <h5><i class="bi bi-exclamation-triangle"></i>Alertas do Sistema</h5>
                                <button class="btn btn-outline-secondary btn-sm view-all-btn">Ver Todos</button>
                            </div>
                            <div class="alerts-grid">
                                <div class="alert-card alert-warning">
                                    <div class="alert-content">
                                        <i class="bi bi-clock alert-icon text-warning"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Pagamentos Pendentes</div>
                                            <div class="alert-description">12 pagamentos aguardando aprovação manual</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-warning btn-sm">Revisar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-card alert-info">
                                    <div class="alert-content">
                                        <i class="bi bi-shop alert-icon text-info"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Novas Lojas</div>
                                            <div class="alert-description">8 lojas aguardando aprovação para ativação</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-info btn-sm">Avaliar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-card alert-danger">
                                    <div class="alert-content">
                                        <i class="bi bi-exclamation-triangle alert-icon text-danger"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Entregas Atrasadas</div>
                                            <div class="alert-description">5 entregas com mais de 5 dias de atraso</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-danger btn-sm">Verificar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <div class="charts-section">
                            <div class="charts-grid">
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-graph-up"></i>
                                            </div>
                                            <h6>Faturamento dos Últimos 30 Dias</h6>
                                        </div>
                                        <div class="chart-period">
                                            <select class="form-select form-select-sm">
                                                <option>Últimos 30 dias</option>
                                                <option>Últimos 7 dias</option>
                                                <option>Últimos 90 dias</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="revenueChart" class="chart-container"></div>
                                </div>
                                
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-pie-chart"></i>
                                            </div>
                                            <h6>Categorias Top</h6>
                                        </div>
                                    </div>
                                    <div id="categoriesChart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pedidos Tab -->
                    <div class="tab-pane fade" id="pedidos" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-bag-check"></i>
                                        </div>
                                        <h6>Gestão de Pedidos</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Filtros
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-download me-1"></i>Exportar
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Pedido</th>
                                                <th>Cliente</th>
                                                <th>Loja</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>#12847</strong></td>
                                                <td>João Silva</td>
                                                <td>TechStore Premium</td>
                                                <td><strong>R$ 1.299,90</strong></td>
                                                <td><span class="badge bg-warning">Aguardando Aprovação</span></td>
                                                <td>11/06/2025</td>
                                                <td>
                                                    <button class="btn btn-success action-btn">Aprovar</button>
                                                    <button class="btn btn-outline-danger action-btn">Recusar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>#12846</strong></td>
                                                <td>Maria Santos</td>
                                                <td>Fashion Style</td>
                                                <td><strong>R$ 459,80</strong></td>
                                                <td><span class="badge bg-primary">Processando</span></td>
                                                <td>11/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Ver Detalhes</button>
                                                    <button class="btn btn-outline-secondary action-btn">Rastrear</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>#12845</strong></td>
                                                <td>Carlos Oliveira</td>
                                                <td>Casa & Jardim</td>
                                                <td><strong>R$ 789,50</strong></td>
                                                <td><span class="badge bg-success">Entregue</span></td>
                                                <td>10/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-success action-btn">Avaliar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Histórico</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>#12844</strong></td>
                                                <td>Ana Costa</td>
                                                <td>TechStore Premium</td>
                                                <td><strong>R$ 2.199,00</strong></td>
                                                <td><span class="badge bg-info">Enviado</span></td>
                                                <td>10/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-info action-btn">Rastrear</button>
                                                    <button class="btn btn-outline-secondary action-btn">Contato</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>#12843</strong></td>
                                                <td>Pedro Lima</td>
                                                <td>Fashion Style</td>
                                                <td><strong>R$ 329,90</strong></td>
                                                <td><span class="badge bg-danger">Cancelado</span></td>
                                                <td>09/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-warning action-btn">Estornar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Analisar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagamentos Tab -->
                    <div class="tab-pane fade" id="pagamentos" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-credit-card"></i>
                                        </div>
                                        <h6>Controle de Pagamentos</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Filtros
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-download me-1"></i>Relatório
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Transação</th>
                                                <th>Pedido</th>
                                                <th>Método</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>TXN-789123</strong></td>
                                                <td>#12847</td>
                                                <td>PIX</td>
                                                <td><strong>R$ 1.299,90</strong></td>
                                                <td><span class="badge bg-warning">Pendente</span></td>
                                                <td>11/06/2025 14:32</td>
                                                <td>
                                                    <button class="btn btn-success action-btn">Aprovar</button>
                                                    <button class="btn btn-outline-danger action-btn">Recusar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>TXN-789122</strong></td>
                                                <td>#12846</td>
                                                <td>Cartão</td>
                                                <td><strong>R$ 459,80</strong></td>
                                                <td><span class="badge bg-success">Aprovado</span></td>
                                                <td>11/06/2025 09:15</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Comprovante</button>
                                                    <button class="btn btn-outline-secondary action-btn">Estornar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>TXN-789121</strong></td>
                                                <td>#12845</td>
                                                <td>Boleto</td>
                                                <td><strong>R$ 789,50</strong></td>
                                                <td><span class="badge bg-success">Aprovado</span></td>
                                                <td>10/06/2025 16:45</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Comprovante</button>
                                                    <button class="btn btn-outline-secondary action-btn">Detalhes</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>TXN-789120</strong></td>
                                                <td>#12844</td>
                                                <td>Cartão</td>
                                                <td><strong>R$ 2.199,00</strong></td>
                                                <td><span class="badge bg-danger">Recusado</span></td>
                                                <td>10/06/2025 11:22</td>
                                                <td>
                                                    <button class="btn btn-outline-warning action-btn">Revisar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Contestar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lojas Tab -->
                    <div class="tab-pane fade" id="lojas" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <h6>Gestão de Lojas</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Filtros
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-plus me-1"></i>Nova Loja
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Loja</th>
                                                <th>Proprietário</th>
                                                <th>Categoria</th>
                                                <th>Produtos</th>
                                                <th>Status</th>
                                                <th>Criada em</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>TechStore Premium</strong></td>
                                                <td>João Tech</td>
                                                <td>Eletrônicos</td>
                                                <td>156</td>
                                                <td><span class="badge bg-warning">Aguardando</span></td>
                                                <td>11/06/2025</td>
                                                <td>
                                                    <button class="btn btn-success action-btn">Aprovar</button>
                                                    <button class="btn btn-outline-danger action-btn">Recusar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fashion Style</strong></td>
                                                <td>Maria Fashion</td>
                                                <td>Roupas</td>
                                                <td>89</td>
                                                <td><span class="badge bg-success">Ativa</span></td>
                                                <td>05/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Gerenciar</button>
                                                    <button class="btn btn-outline-warning action-btn">Suspender</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Casa & Jardim</strong></td>
                                                <td>Carlos Casa</td>
                                                <td>Casa e Decoração</td>
                                                <td>234</td>
                                                <td><span class="badge bg-success">Ativa</span></td>
                                                <td>28/05/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Gerenciar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Analytics</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sports Zone</strong></td>
                                                <td>Ana Sports</td>
                                                <td>Esportes</td>
                                                <td>67</td>
                                                <td><span class="badge bg-danger">Suspensa</span></td>
                                                <td>15/05/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-success action-btn">Reativar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Revisar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Frete Tab -->
                    <div class="tab-pane fade" id="frete" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <h6>Sistema de Frete</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-geo-alt me-1"></i>Mapa
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-truck me-1"></i>Otimizar Rotas
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Rastreamento</th>
                                                <th>Pedido</th>
                                                <th>Origem</th>
                                                <th>Destino</th>
                                                <th>Status</th>
                                                <th>Previsão</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>BR123456789</strong></td>
                                                <td>#12847</td>
                                                <td>São Paulo - SP</td>
                                                <td>Rio de Janeiro - RJ</td>
                                                <td><span class="badge bg-warning">Em Trânsito</span></td>
                                                <td>13/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Rastrear</button>
                                                    <button class="btn btn-outline-secondary action-btn">Contato</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>BR123456788</strong></td>
                                                <td>#12846</td>
                                                <td>São Paulo - SP</td>
                                                <td>Belo Horizonte - MG</td>
                                                <td><span class="badge bg-info">Saiu para Entrega</span></td>
                                                <td>12/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Rastrear</button>
                                                    <button class="btn btn-outline-success action-btn">Confirmar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>BR123456787</strong></td>
                                                <td>#12845</td>
                                                <td>São Paulo - SP</td>
                                                <td>Brasília - DF</td>
                                                <td><span class="badge bg-success">Entregue</span></td>
                                                <td>11/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-success action-btn">Avaliação</button>
                                                    <button class="btn btn-outline-secondary action-btn">Comprovante</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>BR123456786</strong></td>
                                                <td>#12844</td>
                                                <td>São Paulo - SP</td>
                                                <td>Salvador - BA</td>
                                                <td><span class="badge bg-danger">Atrasado</span></td>
                                                <td>08/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-danger action-btn">Investigar</button>
                                                    <button class="btn btn-outline-warning action-btn">Priorizar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Análises Tab -->
                    <div class="tab-pane fade" id="analises" role="tabpanel">
                        <div class="charts-section">
                            <div class="charts-grid">
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-bar-chart"></i>
                                            </div>
                                            <h6>Performance por Loja</h6>
                                        </div>
                                        <div class="chart-period">
                                            <select class="form-select form-select-sm">
                                                <option>Este mês</option>
                                                <option>Últimos 3 meses</option>
                                                <option>Este ano</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="storesChart" class="chart-container"></div>
                                </div>
                                
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <h6>Entregas por Região</h6>
                                        </div>
                                    </div>
                                    <div id="regionsChart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

   