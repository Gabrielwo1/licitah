 <style>

        #secao-minhas-assinaturas {
            .assinaturas-container {
                background: linear-gradient(135deg, rgba(var(--nown-primaria-rgb), 0.05) 0%, rgba(var(--nown-secundaria-rgb), 0.05) 100%);
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
                        padding: 1rem 2rem;
                        margin: 0 0.5rem;
                        color: var(--nown-primaria);
                        font-weight: 600;
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                        
                        &:before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(90deg, transparent, rgba(var(--nown-terciaria-rgb), 0.4), transparent);
                            transition: left 0.5s;
                        }
                        
                        &:hover {
                            color: var(--nown-primaria);
                            background-color: rgba(var(--nown-primaria-rgb), 0.1);
                            transform: translateY(-2px);
                            
                            &:before {
                                left: 100%;
                            }
                        }
                        
                        &.active {
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            color: var(--nown-primaria-text-over);
                            box-shadow: 0 5px 15px rgba(var(--nown-primaria-rgb), 0.3);
                            transform: translateY(-2px);
                            
                            .badge {
                                color: var(--nown-pimaria-text-over);
                            }
                        }
                        
                        i {
                            font-size: 1.2rem;
                            margin-right: 0.5rem;
                        }
                        
                        .badge {
                            margin-left: 0.5rem;
                            background: rgba(var(--nown-terciaria-rgb), 0.2);
                            font-size: 0.7rem;
                            color: var(--nown-terciaria-text-over);
                        }
                    }
                }
                
                .tab-content {
                    .tab-pane {
                        animation: fadeIn 0.5s ease-in-out;
                    }
                }
                
                .summary-cards {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                    
                    .summary-card {
                        background: var(--nown-terciaria);
                        border-radius: 15px;
                        padding: 1.5rem;
                        box-shadow: 0 5px 20px rgba(var(--nown-secundaria-rgb), 0.08);
                        border: 1px solid rgba(var(--nown-secundaria-rgb), 0.05);
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
                            box-shadow: 0 10px 30px rgba(var(--nown-secundaria-rgb), 0.15);
                            
                            &:before {
                                transform: scaleX(1);
                            }
                        }
                        
                        .summary-icon {
                            width: 50px;
                            height: 50px;
                            border-radius: 12px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-bottom: 1rem;
                            
                            i {
                                font-size: 1.5rem;
                                
                            }
                            
                            &.active { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                            &.monthly { background: linear-gradient(135deg, #2196F3, #1976D2); }
                            &.next { background: linear-gradient(135deg, #FF9800, #F57C00); }
                            &.savings { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
                        }
                        
                        .summary-value {
                            font-size: 1.5rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .summary-label {
                            
                            font-size: 0.9rem;
                            margin: 0;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                        
                        .summary-detail {
                            
                            font-size: 0.8rem;
                            margin-top: 0.5rem;
                        }
                    }
                }
                
                .subscriptions-grid {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                    
                    .subscription-card {
                        background: var(--nown-terciaria);
                        border-radius: 20px;
                        padding: 2rem;
                        box-shadow: 0 10px 30px rgba(var(--nown-secundaria-rgb), 0.08);
                        border: 1px solid rgba(var(--nown-secundaria-rgb), 0.05);
                        transition: all 0.3s ease;
                        position: relative;
                        overflow: hidden;
                        
                        &:before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 4px;
                            height: 100%;
                            background: linear-gradient(180deg, var(--nown-primaria), var(--nown-primaria-darker));
                            transform: scaleY(0);
                            transition: transform 0.3s ease;
                        }
                        
                        &:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 20px 40px rgba(var(--nown-secundaria-rgb), 0.15);
                            
                            &:before {
                                transform: scaleY(1);
                            }
                        }
                        
                        .subscription-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 1.5rem;
                            padding-bottom: 1rem;
                            border-bottom: 2px solid rgba(var(--nown-primaria-rgb), 0.1);
                            
                            .subscription-info {
                                display: flex;
                                align-items: center;
                                
                                .service-logo {
                                    width: 60px;
                                    height: 60px;
                                    border-radius: 12px;
                                    object-fit: cover;
                                    margin-right: 1rem;
                                }
                                
                                .service-details {
                                    .service-name {
                                        font-weight: 800;
                                        font-size: 1.2rem;
                                        
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .service-plan {
                                        
                                        font-size: 0.9rem;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .service-provider {
                                        color: var(--nown-primaria);
                                        font-size: 0.8rem;
                                        font-weight: 600;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                        margin: 0;
                                    }
                                }
                            }
                            
                            .subscription-status {
                                .status-badge {
                                    padding: 0.75rem 1.5rem;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    
                                    &.active {
                                        background: linear-gradient(135deg, #4CAF50, #388E3C);
                                        
                                    }
                                    
                                    &.paused {
                                        background: linear-gradient(135deg, #FF9800, #F57C00);
                                        
                                    }
                                    
                                    &.cancelled {
                                        background: linear-gradient(135deg, #f44336, #d32f2f);
                                        
                                    }
                                    
                                    &.expired {
                                        background: linear-gradient(135deg, #9E9E9E, #757575);
                                        
                                    }
                                    
                                    &.pending {
                                        background: linear-gradient(135deg, #2196F3, #1976D2);
                                        
                                    }
                                }
                            }
                        }
                        
                        .subscription-details {
                            margin-bottom: 1.5rem;
                            
                            .pricing-info {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                margin-bottom: 1.5rem;
                                padding: 1.5rem;
                                background: linear-gradient(135deg, rgba(var(--nown-primaria-rgb), 0.02), rgba(var(--nown-secundaria-rgb), 0.02));
                                border-radius: 12px;
                                
                                .price-details {
                                    .current-price {
                                        font-size: 1.8rem;
                                        font-weight: 800;
                                        color: var(--nown-primaria);
                                        margin-bottom: 0.25rem;
                                        
                                        .period {
                                            font-size: 0.8rem;
                                            
                                            font-weight: 600;
                                        }
                                    }
                                    
                                    .original-price {
                                        text-decoration: line-through;
                                        
                                        font-size: 0.9rem;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .savings {
                                        color: #4CAF50;
                                        font-size: 0.8rem;
                                        font-weight: 600;
                                        margin: 0;
                                    }
                                }
                                
                                .auto-renewal {
                                    text-align: right;
                                    
                                    .renewal-toggle {
                                        display: flex;
                                        align-items: center;
                                        margin-bottom: 0.5rem;
                                        
                                        .form-check-input {
                                            width: 50px;
                                            height: 25px;
                                            border-radius: 25px;
                                            position: relative;
                                            appearance: none;
                                            background-
                                            transition: all 0.3s ease;
                                            cursor: pointer;
                                            margin-right: 0.75rem;
                                            
                                            &:after {
                                                content: '';
                                                position: absolute;
                                                top: 2px;
                                                left: 2px;
                                                width: 21px;
                                                height: 21px;
                                                border-radius: 50%;
                                                background: var(--nown-terciaria);
                                                transition: all 0.3s ease;
                                            }
                                            
                                            &:checked {
                                                background-color: var(--nown-primaria);
                                                
                                                &:after {
                                                    transform: translateX(25px);
                                                }
                                            }
                                        }
                                        
                                        .renewal-label {
                                            font-size: 0.9rem;
                                            font-weight: 600;
                                            
                                            margin: 0;
                                        }
                                    }
                                    
                                    .next-billing {
                                        font-size: 0.8rem;
                                        
                                        margin: 0;
                                    }
                                }
                            }
                            
                            .subscription-features {
                                .features-header {
                                    display: flex;
                                    align-items: center;
                                    margin-bottom: 1rem;
                                    
                                    .features-icon {
                                        width: 35px;
                                        height: 35px;
                                        border-radius: 8px;
                                        background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin-right: 0.75rem;
                                        
                                        i {
                                            color: var(--nown-primaria-text-over);
                                            font-size: 1rem;
                                        }
                                    }
                                    
                                    .features-title {
                                        font-weight: 700;
                                        
                                        margin: 0;
                                    }
                                }
                                
                                .features-list {
                                    display: grid;
                                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                                    gap: 0.75rem;
                                    
                                    .feature-item {
                                        display: flex;
                                        align-items: center;
                                        padding: 0.5rem;
                                        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05), rgba(56, 142, 60, 0.05));
                                        border-radius: 8px;
                                        
                                        .feature-icon {
                                            color: #4CAF50;
                                            margin-right: 0.5rem;
                                            font-size: 0.9rem;
                                        }
                                        
                                        .feature-text {
                                            
                                            font-size: 0.85rem;
                                            font-weight: 600;
                                            margin: 0;
                                        }
                                    }
                                }
                            }
                        }
                        
                        .subscription-actions {
                            display: flex;
                            gap: 1rem;
                            flex-wrap: wrap;
                            
                            .action-btn {
                                border-radius: 12px;
                                font-weight: 600;
                                padding: 0.75rem 1.5rem;
                                transition: all 0.3s ease;
                                font-size: 0.9rem;
                                
                                &:hover {
                                    transform: translateY(-2px);
                                }
                                
                                &.btn-primary {
                                    box-shadow: 0 4px 15px rgba(var(--nown-primaria-rgb), 0.3);
                                    
                                    &:hover {
                                        box-shadow: 0 6px 20px rgba(var(--nown-primaria-rgb), 0.4);
                                    }
                                }
                                
                                &.btn-outline-secondary {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(var(--nown-secundaria-rgb), 0.1);
                                    }
                                }
                                
                                &.btn-outline-success {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
                                    }
                                }
                                
                                &.btn-outline-warning {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
                                    }
                                }
                                
                                &.btn-outline-danger {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
                                    }
                                }
                            }
                        }
                        
                        .billing-history {
                            margin-top: 1.5rem;
                            padding: 1rem;
                            background: linear-gradient(135deg, rgba(var(--nown-secundaria-rgb), 0.05), rgba(var(--nown-primaria-rgb), 0.05));
                            border-radius: 12px;
                            
                            .history-header {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                margin-bottom: 1rem;
                                
                                .history-title {
                                    display: flex;
                                    align-items: center;
                                    
                                    .history-icon {
                                        width: 35px;
                                        height: 35px;
                                        border-radius: 8px;
                                        background: linear-gradient(135deg, #2196F3, #1976D2);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin-right: 0.75rem;
                                        
                                        i {
                                            
                                            font-size: 1rem;
                                        }
                                    }
                                    
                                    h6 {
                                        margin: 0;
                                        font-weight: 700;
                                        
                                    }
                                }
                                
                                .view-all-btn {
                                    font-size: 0.8rem;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 15px;
                                }
                            }
                            
                            .recent-charges {
                                .charge-item {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    padding: 0.75rem 0;
                                    border-bottom: 1px solid rgba(var(--nown-secundaria-rgb), 0.1);
                                    
                                    &:last-child {
                                        border-bottom: none;
                                    }
                                    
                                    .charge-info {
                                        .charge-date {
                                            font-weight: 600;
                                            
                                            font-size: 0.9rem;
                                            margin-bottom: 0.25rem;
                                        }
                                        
                                        .charge-method {
                                            
                                            font-size: 0.8rem;
                                            margin: 0;
                                        }
                                    }
                                    
                                    .charge-amount {
                                        font-weight: 700;
                                        color: var(--nown-primaria);
                                        font-size: 0.9rem;
                                    }
                                }
                            }
                        }
                    }
                }
                
                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    
                    .empty-icon {
                        font-size: 4rem;
                        
                        margin-bottom: 1.5rem;
                        opacity: 0.5;
                    }
                    
                    .empty-title {
                        font-weight: 700;
                        font-size: 1.3rem;
                        
                        margin-bottom: 0.5rem;
                    }
                    
                    .empty-subtitle {
                        
                        margin-bottom: 2rem;
                    }
                    
                    .empty-action {
                        border-radius: 25px;
                        font-weight: 700;
                        padding: 1rem 2rem;
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 25px rgba(var(--nown-primaria-rgb), 0.3);
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
            background: linear-gradient(135deg, var(--nown-terciaria-lighter) 0%, var(--nown-terciaria-darker) 100%);
            min-height: 100vh;
        }

        body.dark {
            background: linear-gradient(135deg, var(--nown-secundaria) 0%, var(--nown-secundaria-lighter) 100%);
            min-height: 100vh;
            
            #secao-minhas-assinaturas {
                .assinaturas-container {
                    background: linear-gradient(135deg, rgba(var(--nown-primaria-rgb), 0.1) 0%, rgba(var(--nown-secundaria-rgb), 0.1) 100%);
                    
                    .nav-tabs {
                        .nav-link {
                            color: var(--nown-terciaria-darker);
                            
                            &:hover {
                                background-color: rgba(var(--nown-primaria-rgb), 0.2);
                            }
                        }
                    }
                    
                    .summary-cards {
                        .summary-card {
                            background: var(--bs-card-bg);
                            border-color: none;
                        }
                    }
                    
                    .subscriptions-grid {
                        .subscription-card {
                            background: var(--bs-card-bg);
                            border-color: none;
                            
                            .subscription-header {
                                .subscription-info {
                                    .service-details {
                                        .service-name {
                                            
                                        }
                                    }
                                }
                            }
                            
                            .subscription-details {
                                .pricing-info {
                                    background: linear-gradient(135deg, rgba(var(--nown-primaria-rgb), 0.05), rgba(var(--nown-secundaria-rgb), 0.05));
                                    
                                    .auto-renewal {
                                        .renewal-toggle {
                                            .renewal-label {
                                                
                                            }
                                        }
                                    }
                                }
                                
                                .subscription-features {
                                    .features-header {
                                        .features-title {
                                            
                                        }
                                    }
                                    
                                    .features-list {
                                        .feature-item {
                                            background: linear-gradient(135deg, rgba(76, 175, 80, 0.08), rgba(56, 142, 60, 0.08));
                                            
                                            .feature-text {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                            
                            .billing-history {
                                background: linear-gradient(135deg, rgba(var(--nown-secundaria-rgb), 0.08), rgba(var(--nown-primaria-rgb), 0.08));
                                
                                .history-header {
                                    .history-title {
                                        h6 {
                                            
                                        }
                                    }
                                }
                                
                                .recent-charges {
                                    .charge-item {
                                        border-bottom-color: rgba(var(--nown-terciaria-rgb), 0.1);
                                        
                                        .charge-info {
                                            .charge-date {
                                                
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    .empty-state {
                        .empty-title {
                            
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
                
                font-size: 1.1rem;
                margin: 0;
            }
        }

        .quick-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            
            .action-btn {
                display: block;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                margin-bottom: 1rem;
                box-shadow: 0 10px 30px rgba(var(--nown-primaria-rgb), 0.3);
                border: none;
                transition: all 0.3s ease;
                
                &:hover {
                    transform: scale(1.1);
                    box-shadow: 0 15px 40px rgba(var(--nown-primaria-rgb), 0.4);
                }
                
                i {
                    font-size: 1.5rem;
                }
            }
        }
    </style>


    <div class="container-nown py-4">
        <!-- Header da Página -->
        <div class="page-header">
            <h1 class="text-center"><i class="bi bi-arrow-repeat me-3"></i>Minhas Assinaturas</h1>
            <p class="subtitle">Gerencie todas as suas assinaturas e serviços recorrentes</p>
        </div>

        <!-- Seção Principal -->
        <section id="secao-minhas-assinaturas">
            <div class="assinaturas-container">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="assinaturasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="todas-tab" data-bs-toggle="tab" data-bs-target="#todas" type="button" role="tab">
                            <i class="bi bi-list-ul"></i>Todas<span class="badge">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ativas-tab" data-bs-toggle="tab" data-bs-target="#ativas" type="button" role="tab">
                            <i class="bi bi-check-circle"></i>Ativas<span class="badge">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pausadas-tab" data-bs-toggle="tab" data-bs-target="#pausadas" type="button" role="tab">
                            <i class="bi bi-pause-circle"></i>Pausadas<span class="badge">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="canceladas-tab" data-bs-toggle="tab" data-bs-target="#canceladas" type="button" role="tab">
                            <i class="bi bi-x-circle"></i>Canceladas<span class="badge">0</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="assinaturasTabsContent">
                    <!-- Todas as Assinaturas Tab -->
                    <div class="tab-pane fade show active" id="todas" role="tabpanel">
                        <!-- Cards de Resumo -->
                        <div class="summary-cards">
                            <div class="summary-card">
                                <div class="summary-icon active">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="summary-value">4</div>
                                <div class="summary-label">Assinaturas Ativas</div>
                                <div class="summary-detail">Renovação automática ativada</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon monthly">
                                    <i class="bi bi-calendar"></i>
                                </div>
                                <div class="summary-value">R$ 186,70</div>
                                <div class="summary-label">Gasto Mensal</div>
                                <div class="summary-detail">Próxima cobrança em 5 dias</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon next">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="summary-value">15/06</div>
                                <div class="summary-label">Próxima Cobrança</div>
                                <div class="summary-detail">Marketplace Premium - R$ 29,90</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon savings">
                                    <i class="bi bi-piggy-bank"></i>
                                </div>
                                <div class="summary-value">R$ 348,60</div>
                                <div class="summary-label">Economia Anual</div>
                                <div class="summary-detail">Com planos anuais</div>
                            </div>
                        </div>

                        <!-- Lista de Assinaturas -->
                        <div class="subscriptions-grid">
                            <!-- Assinatura 1 - Marketplace Premium (Ativa) -->
                        <!--    <div class="subscription-card">-->
                        <!--        <div class="subscription-header">-->
                        <!--            <div class="subscription-info">-->
                        <!--                <img src="https://placehold.co/60x60" alt="Logo" class="service-logo">-->
                        <!--                <div class="service-details">-->
                        <!--                    <div class="service-name">Marketplace Premium</div>-->
                        <!--                    <div class="service-plan">Plano Premium Mensal</div>-->
                        <!--                    <div class="service-provider">Marketplace Brasil</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--            <div class="subscription-status">-->
                        <!--                <span class="status-badge active">Ativa</span>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-details">-->
                        <!--            <div class="pricing-info">-->
                        <!--                <div class="price-details">-->
                        <!--                    <div class="current-price">R$ 29,90 <span class="period">/mês</span></div>-->
                        <!--                    <div class="original-price">R$ 39,90</div>-->
                        <!--                    <div class="savings">Economia de 25% • Oferta especial</div>-->
                        <!--                </div>-->
                        <!--                <div class="auto-renewal">-->
                        <!--                    <div class="renewal-toggle">-->
                        <!--                        <input type="checkbox" class="form-check-input" checked>-->
                        <!--                        <div class="renewal-label">Renovação Automática</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="next-billing">Próxima cobrança: 15/06/2025</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                                    
                        <!--            <div class="subscription-features">-->
                        <!--                <div class="features-header">-->
                        <!--                    <div class="features-icon">-->
                        <!--                        <i class="bi bi-star"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="features-title">Benefícios Inclusos</div>-->
                        <!--                </div>-->
                        <!--                <div class="features-list">-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Frete grátis ilimitado</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Cupons exclusivos</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Suporte prioritário</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Acesso antecipado</div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="billing-history">-->
                        <!--            <div class="history-header">-->
                        <!--                <div class="history-title">-->
                        <!--                    <div class="history-icon">-->
                        <!--                        <i class="bi bi-receipt"></i>-->
                        <!--                    </div>-->
                        <!--                    <h6>Últimas Cobranças</h6>-->
                        <!--                </div>-->
                        <!--                <button class="btn btn-outline-secondary btn-sm view-all-btn">Ver Todas</button>-->
                        <!--            </div>-->
                        <!--            <div class="recent-charges">-->
                        <!--                <div class="charge-item">-->
                        <!--                    <div class="charge-info">-->
                        <!--                        <div class="charge-date">15/05/2025</div>-->
                        <!--                        <div class="charge-method">Cartão •••• 4521</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="charge-amount">R$ 29,90</div>-->
                        <!--                </div>-->
                        <!--                <div class="charge-item">-->
                        <!--                    <div class="charge-info">-->
                        <!--                        <div class="charge-date">15/04/2025</div>-->
                        <!--                        <div class="charge-method">Cartão •••• 4521</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="charge-amount">R$ 29,90</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-actions">-->
                        <!--            <button class="btn btn-outline-success action-btn">-->
                        <!--                <i class="bi bi-arrow-up-circle me-1"></i>Fazer Upgrade-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-secondary action-btn">-->
                        <!--                <i class="bi bi-credit-card me-1"></i>Alterar Pagamento-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-warning action-btn">-->
                        <!--                <i class="bi bi-pause-circle me-1"></i>Pausar-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-danger action-btn">-->
                        <!--                <i class="bi bi-x-circle me-1"></i>Cancelar-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    </div>-->

                            <!-- Assinatura 2 - Streaming de Música (Ativa) -->
                        <!--    <div class="subscription-card">-->
                        <!--        <div class="subscription-header">-->
                        <!--            <div class="subscription-info">-->
                        <!--                <img src="https://placehold.co/60x60" alt="Logo" class="service-logo">-->
                        <!--                <div class="service-details">-->
                        <!--                    <div class="service-name">MusicStream Pro</div>-->
                        <!--                    <div class="service-plan">Plano Familiar</div>-->
                        <!--                    <div class="service-provider">TechStore Premium</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--            <div class="subscription-status">-->
                        <!--                <span class="status-badge active">Ativa</span>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-details">-->
                        <!--            <div class="pricing-info">-->
                        <!--                <div class="price-details">-->
                        <!--                    <div class="current-price">R$ 24,90 <span class="period">/mês</span></div>-->
                        <!--                </div>-->
                        <!--                <div class="auto-renewal">-->
                        <!--                    <div class="renewal-toggle">-->
                        <!--                        <input type="checkbox" class="form-check-input" checked>-->
                        <!--                        <div class="renewal-label">Renovação Automática</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="next-billing">Próxima cobrança: 22/06/2025</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                                    
                        <!--            <div class="subscription-features">-->
                        <!--                <div class="features-header">-->
                        <!--                    <div class="features-icon">-->
                        <!--                        <i class="bi bi-music-note"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="features-title">Benefícios Inclusos</div>-->
                        <!--                </div>-->
                        <!--                <div class="features-list">-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Música sem anúncios</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Download offline</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">6 contas familiares</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Qualidade alta</div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-actions">-->
                        <!--            <button class="btn btn-outline-secondary action-btn">-->
                        <!--                <i class="bi bi-credit-card me-1"></i>Alterar Pagamento-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-warning action-btn">-->
                        <!--                <i class="bi bi-pause-circle me-1"></i>Pausar-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-danger action-btn">-->
                        <!--                <i class="bi bi-x-circle me-1"></i>Cancelar-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    </div>-->

                            <!-- Assinatura 3 - Cloud Storage (Pausada) -->
                        <!--    <div class="subscription-card">-->
                        <!--        <div class="subscription-header">-->
                        <!--            <div class="subscription-info">-->
                        <!--                <img src="https://placehold.co/60x60" alt="Logo" class="service-logo">-->
                        <!--                <div class="service-details">-->
                        <!--                    <div class="service-name">CloudSync Storage</div>-->
                        <!--                    <div class="service-plan">Plano Business 1TB</div>-->
                        <!--                    <div class="service-provider">Casa & Jardim</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--            <div class="subscription-status">-->
                        <!--                <span class="status-badge paused">Pausada</span>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-details">-->
                        <!--            <div class="pricing-info">-->
                        <!--                <div class="price-details">-->
                        <!--                    <div class="current-price">R$ 19,90 <span class="period">/mês</span></div>-->
                        <!--                </div>-->
                        <!--                <div class="auto-renewal">-->
                        <!--                    <div class="renewal-toggle">-->
                        <!--                        <input type="checkbox" class="form-check-input">-->
                        <!--                        <div class="renewal-label">Renovação Automática</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="next-billing">Assinatura pausada desde 01/06/2025</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                                    
                        <!--            <div class="subscription-features">-->
                        <!--                <div class="features-header">-->
                        <!--                    <div class="features-icon">-->
                        <!--                        <i class="bi bi-cloud"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="features-title">Benefícios Inclusos</div>-->
                        <!--                </div>-->
                        <!--                <div class="features-list">-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">1TB de armazenamento</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Sincronização automática</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Compartilhamento seguro</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Backup automático</div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-actions">-->
                        <!--            <button class="btn btn-n-primaria action-btn">-->
                        <!--                <i class="bi bi-play-circle me-1"></i>Reativar Assinatura-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-secondary action-btn">-->
                        <!--                <i class="bi bi-credit-card me-1"></i>Alterar Pagamento-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-danger action-btn">-->
                        <!--                <i class="bi bi-x-circle me-1"></i>Cancelar Definitivamente-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    </div>-->

                            <!-- Assinatura 4 - Software Design (Cancelada) -->
                        <!--    <div class="subscription-card">-->
                        <!--        <div class="subscription-header">-->
                        <!--            <div class="subscription-info">-->
                        <!--                <img src="https://placehold.co/60x60" alt="Logo" class="service-logo">-->
                        <!--                <div class="service-details">-->
                        <!--                    <div class="service-name">DesignPro Suite</div>-->
                        <!--                    <div class="service-plan">Plano Profissional</div>-->
                        <!--                    <div class="service-provider">Fashion Style</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--            <div class="subscription-status">-->
                        <!--                <span class="status-badge cancelled">Cancelada</span>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-details">-->
                        <!--            <div class="pricing-info">-->
                        <!--                <div class="price-details">-->
                        <!--                    <div class="current-price">R$ 89,90 <span class="period">/mês</span></div>-->
                        <!--                </div>-->
                        <!--                <div class="auto-renewal">-->
                        <!--                    <div class="renewal-toggle">-->
                        <!--                        <input type="checkbox" class="form-check-input" disabled>-->
                        <!--                        <div class="renewal-label">Renovação Automática</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="next-billing">Cancelada em 25/05/2025 • Acesso até 25/06/2025</div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                                    
                        <!--            <div class="subscription-features">-->
                        <!--                <div class="features-header">-->
                        <!--                    <div class="features-icon">-->
                        <!--                        <i class="bi bi-palette"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="features-title">Benefícios que Você Tinha</div>-->
                        <!--                </div>-->
                        <!--                <div class="features-list">-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Ferramentas profissionais</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Cloud storage ilimitado</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Colaboração em equipe</div>-->
                        <!--                    </div>-->
                        <!--                    <div class="feature-item">-->
                        <!--                        <i class="bi bi-check-circle-fill feature-icon"></i>-->
                        <!--                        <div class="feature-text">Templates premium</div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                                
                        <!--        <div class="subscription-actions">-->
                        <!--            <button class="btn btn-n-primaria action-btn">-->
                        <!--                <i class="bi bi-arrow-clockwise me-1"></i>Reativar Assinatura-->
                        <!--            </button>-->
                        <!--            <button class="btn btn-outline-secondary action-btn">-->
                        <!--                <i class="bi bi-download me-1"></i>Baixar Dados-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        </div>
                    </div>

                    <!-- Ativas Tab -->
                    <div class="tab-pane fade" id="ativas" role="tabpanel">
                        <div class="subscriptions-grid">
                        <!--    <div class="empty-state">-->
                        <!--        <div class="empty-icon">-->
                        <!--            <i class="bi bi-check-circle"></i>-->
                        <!--        </div>-->
                        <!--        <div class="empty-title">Suas assinaturas ativas aparecerão aqui</div>-->
                        <!--        <div class="empty-subtitle">Todas as assinaturas com renovação ativa ficam organizadas nesta seção.</div>-->
                        <!--    </div>-->
                        </div>
                    </div>

                    <!-- Pausadas Tab -->
                    <div class="tab-pane fade" id="pausadas" role="tabpanel">
                        <div class="subscriptions-grid">
                        <!--    <div class="empty-state">-->
                        <!--        <div class="empty-icon">-->
                        <!--            <i class="bi bi-pause-circle"></i>-->
                        <!--        </div>-->
                        <!--        <div class="empty-title">Assinaturas pausadas temporariamente</div>-->
                        <!--        <div class="empty-subtitle">Você pode reativar suas assinaturas pausadas a qualquer momento.</div>-->
                        <!--    </div>-->
                        </div>
                    </div>

                    <!-- Canceladas Tab -->
                    <div class="tab-pane fade" id="canceladas" role="tabpanel">
                        <div class="subscriptions-grid">
                        <!--    <div class="empty-state">-->
                        <!--        <div class="empty-icon">-->
                        <!--            <i class="bi bi-x-circle"></i>-->
                        <!--        </div>-->
                        <!--        <div class="empty-title">Histórico de assinaturas canceladas</div>-->
                        <!--        <div class="empty-subtitle">Aqui você encontra assinaturas canceladas que ainda podem ser reativadas.</div>-->
                        <!--    </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


 