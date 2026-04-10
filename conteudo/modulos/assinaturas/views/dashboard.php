 <style>
        #secao-dashboard-assinaturas {
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
                
                .kpi-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                    
                    .kpi-card {
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
                        
                        .kpi-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-bottom: 1rem;
                            
                            .kpi-icon {
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
                                
                                &.mrr { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                                &.arr { background: linear-gradient(135deg, #2196F3, #1976D2); }
                                &.subscribers { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                                &.churn { background: linear-gradient(135deg, #FF5722, #D84315); }
                                &.ltv { background: linear-gradient(135deg, #FF9800, #F57C00); }
                                &.plans { background: linear-gradient(135deg, #00BCD4, #0097A7); }
                            }
                            
                            .kpi-trend {
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
                                
                                &.neutral {
                                    background: rgba(158, 158, 158, 0.1);
                                    color: #9E9E9E;
                                }
                            }
                        }
                        
                        .kpi-value {
                            font-size: 1.8rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .kpi-label {
                            color: #6c757d;
                            font-size: 0.9rem;
                            margin-bottom: 0.5rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                        
                        .kpi-details {
                            font-size: 0.8rem;
                            color: #6c757d;
                            margin: 0;
                        }
                        
                        .kpi-comparison {
                            margin-top: 0.5rem;
                            font-size: 0.8rem;
                            
                            .comparison-item {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 0.25rem;
                                
                                .comparison-label {
                                    color: #6c757d;
                                }
                                
                                .comparison-value {
                                    font-weight: 600;
                                    color: #2c3e50;
                                }
                            }
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
                            
                            &.alert-success {
                                background: rgba(76, 175, 80, 0.1);
                                border-left-color: #4CAF50;
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
                                            
                                            .plan-badge {
                                                display: inline-block;
                                                padding: 0.25rem 0.75rem;
                                                border-radius: 15px;
                                                font-size: 0.75rem;
                                                font-weight: 600;
                                                
                                                &.basic {
                                                    background: linear-gradient(135deg, #9E9E9E, #757575);
                                                    color: white;
                                                }
                                                
                                                &.premium {
                                                    background: linear-gradient(135deg, #FF9800, #F57C00);
                                                    color: white;
                                                }
                                                
                                                &.enterprise {
                                                    background: linear-gradient(135deg, #9C27B0, #7B1FA2);
                                                    color: white;
                                                }
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
                        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                        gap: 2rem;
                        margin-bottom: 2rem;
                        
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
                
                .plans-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 1.5rem;
                    
                    .plan-card {
                        background: white;
                        border-radius: 15px;
                        padding: 1.5rem;
                        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                        border: 1px solid rgba(0,0,0,0.05);
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                        
                        &:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                        }
                        
                        &.popular {
                            border-color: var(--nown-primaria);
                            
                            &:before {
                                content: 'MAIS POPULAR';
                                position: absolute;
                                top: 1rem;
                                right: -2rem;
                                background: var(--nown-primaria);
                                color: white;
                                padding: 0.25rem 3rem;
                                font-size: 0.7rem;
                                font-weight: 700;
                                transform: rotate(45deg);
                                letter-spacing: 0.5px;
                            }
                        }
                        
                        .plan-header {
                            text-align: center;
                            margin-bottom: 1.5rem;
                            
                            .plan-icon {
                                width: 60px;
                                height: 60px;
                                border-radius: 15px;
                                margin: 0 auto 1rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                
                                i {
                                    font-size: 2rem;
                                    color: white;
                                }
                                
                                &.basic { background: linear-gradient(135deg, #9E9E9E, #757575); }
                                &.premium { background: linear-gradient(135deg, #FF9800, #F57C00); }
                                &.enterprise { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                            }
                            
                            .plan-name {
                                font-size: 1.3rem;
                                font-weight: 700;
                                color: #2c3e50;
                                margin-bottom: 0.5rem;
                            }
                            
                            .plan-price {
                                font-size: 2rem;
                                font-weight: 800;
                                color: var(--nown-primaria);
                                margin-bottom: 0.25rem;
                                
                                .period {
                                    font-size: 0.8rem;
                                    color: #6c757d;
                                    font-weight: 600;
                                }
                            }
                            
                            .plan-description {
                                color: #6c757d;
                                font-size: 0.9rem;
                                margin: 0;
                            }
                        }
                        
                        .plan-stats {
                            margin-bottom: 1.5rem;
                            
                            .stat-item {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 0.5rem;
                                
                                .stat-label {
                                    color: #6c757d;
                                    font-size: 0.9rem;
                                }
                                
                                .stat-value {
                                    font-weight: 600;
                                    color: #2c3e50;
                                    font-size: 0.9rem;
                                }
                            }
                        }
                        
                        .plan-actions {
                            display: flex;
                            gap: 0.5rem;
                            
                            .btn {
                                flex: 1;
                                border-radius: 10px;
                                font-weight: 600;
                                font-size: 0.9rem;
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
            
            #secao-dashboard-assinaturas {
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
                    
                    .kpi-grid {
                        .kpi-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .kpi-label, .kpi-details {
                                color: #ccc;
                            }
                            
                            .kpi-comparison {
                                .comparison-label {
                                    color: #aaa;
                                }
                                
                                .comparison-value {
                                    color: var(--nown-terciaria);
                                }
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
                    
                    .plans-grid {
                        .plan-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .plan-header {
                                .plan-name {
                                    color: var(--nown-terciaria);
                                }
                            }
                            
                            .plan-stats {
                                .stat-item {
                                    .stat-value {
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

        .subscription-health {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 1000;
            
            .health-indicator {
                display: flex;
                align-items: center;
                background: white;
                padding: 0.75rem 1rem;
                border-radius: 25px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border: 1px solid rgba(0,0,0,0.05);
                
                .health-score {
                    font-size: 1.2rem;
                    font-weight: 700;
                    margin-right: 0.5rem;
                    
                    &.excellent { color: #4CAF50; }
                    &.good { color: #FF9800; }
                    &.poor { color: #f44336; }
                }
                
                .health-text {
                    font-size: 0.9rem;
                    font-weight: 600;
                    color: #2c3e50;
                    margin: 0;
                }
            }
        }
    </style>

    <div class="subscription-health">
        <div class="health-indicator">
            <div class="health-score excellent">94%</div>
            <div class="health-text">Health Score</div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Header da Página -->
        <div class="page-header">
            <h1><i class="bi bi-arrow-repeat me-3"></i>Dashboard Assinaturas</h1>
            <p class="subtitle">Gestão completa de planos, assinaturas e receitas recorrentes</p>
        </div>

        <!-- Seção Principal -->
        <section id="secao-dashboard-assinaturas">
            <div class="dashboard-container">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="subscriptionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="visao-geral-tab" data-bs-toggle="tab" data-bs-target="#visao-geral" type="button" role="tab">
                            <i class="bi bi-speedometer2"></i>Visão Geral
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assinaturas-tab" data-bs-toggle="tab" data-bs-target="#assinaturas" type="button" role="tab">
                            <i class="bi bi-people"></i>Assinaturas<span class="badge">2.847</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="planos-tab" data-bs-toggle="tab" data-bs-target="#planos" type="button" role="tab">
                            <i class="bi bi-layers"></i>Planos<span class="badge">6</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="financeiro-tab" data-bs-toggle="tab" data-bs-target="#financeiro" type="button" role="tab">
                            <i class="bi bi-graph-up"></i>Financeiro<span class="badge">47</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelamentos-tab" data-bs-toggle="tab" data-bs-target="#cancelamentos" type="button" role="tab">
                            <i class="bi bi-x-circle"></i>Churn<span class="badge">23</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analises-tab" data-bs-toggle="tab" data-bs-target="#analises" type="button" role="tab">
                            <i class="bi bi-bar-chart"></i>Análises
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="subscriptionTabsContent">
                    <!-- Visão Geral Tab -->
                    <div class="tab-pane fade show active" id="visao-geral" role="tabpanel">
                        <!-- KPIs Principais -->
                        <div class="kpi-grid">
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon mrr">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="kpi-trend positive">+18.3%</div>
                                </div>
                                <div class="kpi-value">R$ 284.750</div>
                                <div class="kpi-label">MRR (Receita Mensal Recorrente)</div>
                                <div class="kpi-details">Meta mensal: R$ 300K</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">Mês anterior:</span>
                                        <span class="comparison-value">R$ 241.200</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Crescimento:</span>
                                        <span class="comparison-value">+R$ 43.550</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon arr">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div class="kpi-trend positive">+22.1%</div>
                                </div>
                                <div class="kpi-value">R$ 3.417.000</div>
                                <div class="kpi-label">ARR (Receita Anual Recorrente)</div>
                                <div class="kpi-details">Projeção baseada no MRR atual</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">Ano anterior:</span>
                                        <span class="comparison-value">R$ 2.798.400</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Crescimento:</span>
                                        <span class="comparison-value">+R$ 618.600</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon subscribers">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="kpi-trend positive">+12.7%</div>
                                </div>
                                <div class="kpi-value">2.847</div>
                                <div class="kpi-label">Assinantes Ativos</div>
                                <div class="kpi-details">347 novos este mês</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">Básico:</span>
                                        <span class="comparison-value">1.205 (42%)</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Premium:</span>
                                        <span class="comparison-value">1.389 (49%)</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Enterprise:</span>
                                        <span class="comparison-value">253 (9%)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon churn">
                                        <i class="bi bi-arrow-down-circle"></i>
                                    </div>
                                    <div class="kpi-trend negative">+0.8%</div>
                                </div>
                                <div class="kpi-value">2.3%</div>
                                <div class="kpi-label">Taxa de Churn</div>
                                <div class="kpi-details">23 cancelamentos este mês</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">Meta:</span>
                                        <span class="comparison-value">< 2%</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Indústria:</span>
                                        <span class="comparison-value">3.2%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon ltv">
                                        <i class="bi bi-trophy"></i>
                                    </div>
                                    <div class="kpi-trend positive">+15.4%</div>
                                </div>
                                <div class="kpi-value">R$ 4.380</div>
                                <div class="kpi-label">LTV Médio</div>
                                <div class="kpi-details">Lifetime Value por cliente</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">CAC:</span>
                                        <span class="comparison-value">R$ 125</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">LTV/CAC:</span>
                                        <span class="comparison-value">35.0x</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kpi-card">
                                <div class="kpi-header">
                                    <div class="kpi-icon plans">
                                        <i class="bi bi-layers"></i>
                                    </div>
                                    <div class="kpi-trend neutral">0%</div>
                                </div>
                                <div class="kpi-value">6</div>
                                <div class="kpi-label">Planos Ativos</div>
                                <div class="kpi-details">3 categorias de produtos</div>
                                <div class="kpi-comparison">
                                    <div class="comparison-item">
                                        <span class="comparison-label">Marketplace:</span>
                                        <span class="comparison-value">3 planos</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Streaming:</span>
                                        <span class="comparison-value">2 planos</span>
                                    </div>
                                    <div class="comparison-item">
                                        <span class="comparison-label">Cloud:</span>
                                        <span class="comparison-value">1 plano</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alertas Críticos -->
                        <div class="alerts-section">
                            <div class="alerts-header">
                                <h5><i class="bi bi-exclamation-triangle"></i>Alertas Críticos</h5>
                            </div>
                            <div class="alerts-grid">
                                <div class="alert-card alert-warning">
                                    <div class="alert-content">
                                        <i class="bi bi-clock alert-icon text-warning"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Renovações Pendentes</div>
                                            <div class="alert-description">47 assinaturas vencem nos próximos 7 dias</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-warning btn-sm">Revisar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-card alert-danger">
                                    <div class="alert-content">
                                        <i class="bi bi-arrow-down-circle alert-icon text-danger"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Churn Alto</div>
                                            <div class="alert-description">Taxa de cancelamento acima da meta (2.3% vs 2%)</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-danger btn-sm">Analisar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-card alert-info">
                                    <div class="alert-content">
                                        <i class="bi bi-credit-card alert-icon text-info"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Falhas de Pagamento</div>
                                            <div class="alert-description">23 pagamentos falharam nas últimas 24h</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-info btn-sm">Processar</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-card alert-success">
                                    <div class="alert-content">
                                        <i class="bi bi-trophy alert-icon text-success"></i>
                                        <div class="alert-text">
                                            <div class="alert-title">Meta Alcançada</div>
                                            <div class="alert-description">MRR superou R$ 280K pela primeira vez</div>
                                        </div>
                                        <div class="alert-action">
                                            <button class="btn btn-success btn-sm">Celebrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos de Visão Geral -->
                        <div class="charts-section">
                            <div class="charts-grid">
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-graph-up"></i>
                                            </div>
                                            <h6>Crescimento MRR</h6>
                                        </div>
                                        <div class="chart-period">
                                            <select class="form-select form-select-sm">
                                                <option>Últimos 12 meses</option>
                                                <option>Últimos 6 meses</option>
                                                <option>Últimos 3 meses</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="mrrChart" class="chart-container"></div>
                                </div>
                                
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-pie-chart"></i>
                                            </div>
                                            <h6>Distribuição por Planos</h6>
                                        </div>
                                    </div>
                                    <div id="plansChart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assinaturas Tab -->
                    <div class="tab-pane fade" id="assinaturas" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h6>Gestão de Assinaturas</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Filtros
                                        </button>
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-download me-1"></i>Exportar
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-plus me-1"></i>Nova Assinatura
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Plano</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th>Início</th>
                                                <th>Próxima Cobrança</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>João Silva</strong><br>
                                                    <small class="text-muted">joao@empresa.com</small>
                                                </td>
                                                <td><span class="plan-badge premium">Premium</span></td>
                                                <td><strong>R$ 149,90/mês</strong></td>
                                                <td><span class="badge bg-success">Ativa</span></td>
                                                <td>15/03/2024</td>
                                                <td>15/07/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Gerenciar</button>
                                                    <button class="btn btn-outline-warning action-btn">Pausar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Maria Santos</strong><br>
                                                    <small class="text-muted">maria@startup.com</small>
                                                </td>
                                                <td><span class="plan-badge enterprise">Enterprise</span></td>
                                                <td><strong>R$ 499,90/mês</strong></td>
                                                <td><span class="badge bg-success">Ativa</span></td>
                                                <td>08/01/2024</td>
                                                <td>08/07/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Gerenciar</button>
                                                    <button class="btn btn-outline-success action-btn">Upgrade</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Carlos Oliveira</strong><br>
                                                    <small class="text-muted">carlos@loja.com</small>
                                                </td>
                                                <td><span class="plan-badge basic">Básico</span></td>
                                                <td><strong>R$ 49,90/mês</strong></td>
                                                <td><span class="badge bg-warning">Vence em 3 dias</span></td>
                                                <td>22/04/2024</td>
                                                <td>14/06/2025</td>
                                                <td>
                                                    <button class="btn btn-warning action-btn">Renovar</button>
                                                    <button class="btn btn-outline-success action-btn">Upgrade</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Ana Costa</strong><br>
                                                    <small class="text-muted">ana@freelancer.com</small>
                                                </td>
                                                <td><span class="plan-badge premium">Premium</span></td>
                                                <td><strong>R$ 149,90/mês</strong></td>
                                                <td><span class="badge bg-danger">Falha no Pagamento</span></td>
                                                <td>01/05/2024</td>
                                                <td>01/06/2025</td>
                                                <td>
                                                    <button class="btn btn-danger action-btn">Recuperar</button>
                                                    <button class="btn btn-outline-secondary action-btn">Contatar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Pedro Lima</strong><br>
                                                    <small class="text-muted">pedro@comercio.com</small>
                                                </td>
                                                <td><span class="plan-badge basic">Básico</span></td>
                                                <td><strong>R$ 49,90/mês</strong></td>
                                                <td><span class="badge bg-secondary">Pausada</span></td>
                                                <td>18/02/2024</td>
                                                <td>-</td>
                                                <td>
                                                    <button class="btn btn-success action-btn">Reativar</button>
                                                    <button class="btn btn-outline-danger action-btn">Cancelar</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Planos Tab -->
                    <div class="tab-pane fade" id="planos" role="tabpanel">
                        <div class="plans-grid">
                            <div class="plan-card">
                                <div class="plan-header">
                                    <div class="plan-icon basic">
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <div class="plan-name">Marketplace Básico</div>
                                    <div class="plan-price">R$ 49,90 <span class="period">/mês</span></div>
                                    <div class="plan-description">Ideal para pequenas lojas iniciantes</div>
                                </div>
                                <div class="plan-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Assinantes:</span>
                                        <span class="stat-value">1.205</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">MRR:</span>
                                        <span class="stat-value">R$ 60.129</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Churn:</span>
                                        <span class="stat-value">3.1%</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">LTV:</span>
                                        <span class="stat-value">R$ 1.890</span>
                                    </div>
                                </div>
                                <div class="plan-actions">
                                    <button class="btn btn-outline-primary">Editar</button>
                                    <button class="btn btn-outline-secondary">Analytics</button>
                                </div>
                            </div>
                            
                            <div class="plan-card popular">
                                <div class="plan-header">
                                    <div class="plan-icon premium">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <div class="plan-name">Marketplace Premium</div>
                                    <div class="plan-price">R$ 149,90 <span class="period">/mês</span></div>
                                    <div class="plan-description">Para lojas em crescimento</div>
                                </div>
                                <div class="plan-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Assinantes:</span>
                                        <span class="stat-value">1.389</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">MRR:</span>
                                        <span class="stat-value">R$ 208.211</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Churn:</span>
                                        <span class="stat-value">1.8%</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">LTV:</span>
                                        <span class="stat-value">R$ 6.890</span>
                                    </div>
                                </div>
                                <div class="plan-actions">
                                    <button class="btn btn-outline-primary">Editar</button>
                                    <button class="btn btn-outline-secondary">Analytics</button>
                                </div>
                            </div>
                            
                            <div class="plan-card">
                                <div class="plan-header">
                                    <div class="plan-icon enterprise">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="plan-name">Enterprise</div>
                                    <div class="plan-price">R$ 499,90 <span class="period">/mês</span></div>
                                    <div class="plan-description">Para grandes operações</div>
                                </div>
                                <div class="plan-stats">
                                    <div class="stat-item">
                                        <span class="stat-label">Assinantes:</span>
                                        <span class="stat-value">253</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">MRR:</span>
                                        <span class="stat-value">R$ 126.497</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">Churn:</span>
                                        <span class="stat-value">0.9%</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-label">LTV:</span>
                                        <span class="stat-value">R$ 18.450</span>
                                    </div>
                                </div>
                                <div class="plan-actions">
                                    <button class="btn btn-outline-primary">Editar</button>
                                    <button class="btn btn-outline-secondary">Analytics</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financeiro Tab -->
                    <div class="tab-pane fade" id="financeiro" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <h6>Transações Recorrentes</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Período
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Relatório
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Cliente</th>
                                                <th>Plano</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th>Método</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>11/06/2025</td>
                                                <td>João Silva</td>
                                                <td><span class="plan-badge premium">Premium</span></td>
                                                <td><strong>R$ 149,90</strong></td>
                                                <td><span class="badge bg-success">Pago</span></td>
                                                <td>Cartão •••• 4521</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Comprovante</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>11/06/2025</td>
                                                <td>Maria Santos</td>
                                                <td><span class="plan-badge enterprise">Enterprise</span></td>
                                                <td><strong>R$ 499,90</strong></td>
                                                <td><span class="badge bg-warning">Pendente</span></td>
                                                <td>PIX</td>
                                                <td>
                                                    <button class="btn btn-warning action-btn">Processar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10/06/2025</td>
                                                <td>Carlos Oliveira</td>
                                                <td><span class="plan-badge basic">Básico</span></td>
                                                <td><strong>R$ 49,90</strong></td>
                                                <td><span class="badge bg-danger">Falhou</span></td>
                                                <td>Cartão •••• 7890</td>
                                                <td>
                                                    <button class="btn btn-danger action-btn">Tentar Novamente</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10/06/2025</td>
                                                <td>Ana Costa</td>
                                                <td><span class="plan-badge premium">Premium</span></td>
                                                <td><strong>R$ 149,90</strong></td>
                                                <td><span class="badge bg-success">Pago</span></td>
                                                <td>Boleto</td>
                                                <td>
                                                    <button class="btn btn-outline-primary action-btn">Comprovante</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelamentos Tab -->
                    <div class="tab-pane fade" id="cancelamentos" role="tabpanel">
                        <div class="data-tables">
                            <div class="table-card">
                                <div class="table-header">
                                    <div class="table-title">
                                        <div class="table-icon">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                        <h6>Análise de Churn</h6>
                                    </div>
                                    <div class="table-actions">
                                        <button class="btn btn-outline-secondary">
                                            <i class="bi bi-funnel me-1"></i>Motivos
                                        </button>
                                        <button class="btn btn-n-primaria">
                                            <i class="bi bi-shield-check me-1"></i>Programa Retenção
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Plano</th>
                                                <th>Tempo de Vida</th>
                                                <th>LTV Perdido</th>
                                                <th>Motivo</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>Roberto Silva</strong><br>
                                                    <small class="text-muted">roberto@loja.com</small>
                                                </td>
                                                <td><span class="plan-badge premium">Premium</span></td>
                                                <td>8 meses</td>
                                                <td><strong>R$ 1.199,20</strong></td>
                                                <td>Preço alto</td>
                                                <td>09/06/2025</td>
                                                <td>
                                                    <button class="btn btn-warning action-btn">Oferta Especial</button>
                                                    <button class="btn btn-outline-secondary action-btn">Contatar</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Lucia Santos</strong><br>
                                                    <small class="text-muted">lucia@empresa.com</small>
                                                </td>
                                                <td><span class="plan-badge basic">Básico</span></td>
                                                <td>3 meses</td>
                                                <td><strong>R$ 149,70</strong></td>
                                                <td>Mudança de negócio</td>
                                                <td>08/06/2025</td>
                                                <td>
                                                    <button class="btn btn-outline-success action-btn">Pausar</button>
                                                    <button class="btn btn-outline-primary action-btn">Migrar Plano</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Fernando Costa</strong><br>
                                                    <small class="text-muted">fernando@startup.com</small>
                                                </td>
                                                <td><span class="plan-badge enterprise">Enterprise</span></td>
                                                <td>14 meses</td>
                                                <td><strong>R$ 6.998,60</strong></td>
                                                <td>Concorrente</td>
                                                <td>07/06/2025</td>
                                                <td>
                                                    <button class="btn btn-danger action-btn">Win-back</button>
                                                    <button class="btn btn-outline-secondary action-btn">Pesquisa</button>
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
                                            <h6>Crescimento por Plano</h6>
                                        </div>
                                        <div class="chart-period">
                                            <select class="form-select form-select-sm">
                                                <option>Últimos 6 meses</option>
                                                <option>Último ano</option>
                                                <option>Últimos 2 anos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="growthChart" class="chart-container"></div>
                                </div>
                                
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <div class="chart-title">
                                            <div class="chart-icon">
                                                <i class="bi bi-arrow-down-up"></i>
                                            </div>
                                            <h6>Churn vs Aquisição</h6>
                                        </div>
                                    </div>
                                    <div id="churnChart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

  