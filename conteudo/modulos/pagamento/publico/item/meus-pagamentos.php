<style>
        #secao-meus-pagamentos {
            .pagamentos-container {
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
                        padding: 1rem 2rem;
                        margin: 0 0.5rem;
                        color: #6c757d;
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
                            font-size: 1.2rem;
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
                
                .summary-cards {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                    
                    .summary-card {
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
                                color: white;
                            }
                            
                            &.total { background: linear-gradient(135deg, #2196F3, #1976D2); }
                            &.pending { background: linear-gradient(135deg, #FF9800, #F57C00); }
                            &.approved { background: linear-gradient(135deg, #4CAF50, #388E3C); }
                            &.failed { background: linear-gradient(135deg, #f44336, #d32f2f); }
                        }
                        
                        .summary-value {
                            font-size: 1.5rem;
                            font-weight: 800;
                            color: var(--nown-primaria);
                            margin-bottom: 0.25rem;
                        }
                        
                        .summary-label {
                            color: #6c757d;
                            font-size: 0.9rem;
                            margin: 0;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                    }
                }
                
                .filters-section {
                    background: rgba(255,255,255,0.7);
                    border-radius: 15px;
                    padding: 1.5rem;
                    margin-bottom: 2rem;
                    backdrop-filter: blur(10px);
                    
                    .filters-header {
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
                        
                        h6 {
                            margin: 0;
                            font-weight: 700;
                            color: #2c3e50;
                        }
                    }
                    
                    .filter-controls {
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
                        
                        .btn-filter {
                            border-radius: 10px;
                            font-weight: 600;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                transform: translateY(-2px);
                            }
                        }
                    }
                }
                
                .payments-grid {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                    
                    .payment-card {
                        background: white;
                        border-radius: 20px;
                        padding: 2rem;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                        border: 1px solid rgba(0,0,0,0.05);
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
                            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                            
                            &:before {
                                transform: scaleY(1);
                            }
                        }
                        
                        .payment-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 1.5rem;
                            padding-bottom: 1rem;
                            border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                            
                            .payment-info {
                                .payment-id {
                                    font-weight: 800;
                                    font-size: 1.2rem;
                                    color: #2c3e50;
                                    margin-bottom: 0.25rem;
                                }
                                
                                .payment-date {
                                    color: #6c757d;
                                    font-size: 0.9rem;
                                    margin: 0;
                                }
                            }
                            
                            .payment-status {
                                .status-badge {
                                    padding: 0.75rem 1.5rem;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    
                                    &.pending {
                                        background: linear-gradient(135deg, #FF9800, #F57C00);
                                        color: white;
                                    }
                                    
                                    &.processing {
                                        background: linear-gradient(135deg, #2196F3, #1976D2);
                                        color: white;
                                    }
                                    
                                    &.approved {
                                        background: linear-gradient(135deg, #4CAF50, #388E3C);
                                        color: white;
                                    }
                                    
                                    &.failed {
                                        background: linear-gradient(135deg, #f44336, #d32f2f);
                                        color: white;
                                    }
                                    
                                    &.refunded {
                                        background: linear-gradient(135deg, #9C27B0, #7B1FA2);
                                        color: white;
                                    }
                                    
                                    &.cancelled {
                                        background: linear-gradient(135deg, #9E9E9E, #757575);
                                        color: white;
                                    }
                                }
                            }
                        }
                        
                        .payment-details {
                            margin-bottom: 1.5rem;
                            
                            .payment-method-info {
                                display: flex;
                                align-items: center;
                                margin-bottom: 1rem;
                                padding: 1rem;
                                background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                                border-radius: 12px;
                                
                                .method-icon {
                                    width: 50px;
                                    height: 50px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 1rem;
                                    
                                    i {
                                        font-size: 1.5rem;
                                        color: white;
                                    }
                                    
                                    &.pix { background: linear-gradient(135deg, #32BCAD, #1B9A8A); }
                                    &.card { background: linear-gradient(135deg, #4285F4, #34A853); }
                                    &.boleto { background: linear-gradient(135deg, #FF9800, #F57C00); }
                                    &.bank { background: linear-gradient(135deg, #9C27B0, #673AB7); }
                                    &.gratis {background: linear-gradient(135deg, #4CAF50, #81C784); color: #fff;}
                                    &.admin {background: linear-gradient(135deg, #607D8B, #455A64); color: #fff;font-style: italic;}
                                }
                                
                                .method-details {
                                    flex: 1;
                                    
                                    .method-name {
                                        font-weight: 700;
                                        color: #2c3e50;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .method-description {
                                        color: #6c757d;
                                        font-size: 0.9rem;
                                        margin: 0;
                                    }
                                }
                                
                                .amount {
                                    font-weight: 800;
                                    font-size: 1.3rem;
                                    color: var(--nown-primaria);
                                }
                            }
                            
                            .transaction-details {
                                display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                                gap: 1rem;
                                
                                .detail-item {
                                    .detail-label {
                                        color: #6c757d;
                                        font-size: 0.85rem;
                                        font-weight: 600;
                                        margin-bottom: 0.25rem;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    }
                                    
                                    .detail-value {
                                        color: #2c3e50;
                                        font-weight: 600;
                                        margin: 0;
                                        
                                        &.transaction-id {
                                            font-family: 'Courier New', monospace;
                                            background: rgba(236, 1, 102, 0.1);
                                            padding: 0.25rem 0.5rem;
                                            border-radius: 6px;
                                            font-size: 0.9rem;
                                        }
                                    }
                                }
                            }
                        }
                        
                        .payment-order-info {
                            margin-bottom: 1.5rem;
                            padding: 1rem;
                            background: linear-gradient(135deg, rgba(0, 0, 128, 0.05), rgba(236, 1, 102, 0.05));
                            border-radius: 12px;
                            
                            .order-header {
                                display: flex;
                                align-items: center;
                                margin-bottom: 0.75rem;
                                
                                .order-icon {
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
                                
                                .order-number {
                                    font-weight: 700;
                                    color: #2c3e50;
                                    margin: 0;
                                }
                            }
                            
                            .order-description {
                                color: #6c757d;
                                font-size: 0.9rem;
                                margin: 0;
                                padding-left: 2.75rem;
                            }
                        }
                        
                        .payment-actions {
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
                                    box-shadow: 0 4px 15px rgba(236, 1, 102, 0.3);
                                    
                                    &:hover {
                                        box-shadow: 0 6px 20px rgba(236, 1, 102, 0.4);
                                    }
                                }
                                
                                &.btn-outline-secondary {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                                    }
                                }
                                
                                &.btn-outline-success {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
                                    }
                                }
                                
                                &.btn-outline-danger {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
                                    }
                                }
                                
                                &.btn-outline-warning {
                                    &:hover {
                                        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
                                    }
                                }
                            }
                        }
                        
                        .refund-info {
                            margin-top: 1.5rem;
                            padding: 1rem;
                            background: linear-gradient(135deg, rgba(156, 39, 176, 0.05), rgba(123, 31, 162, 0.05));
                            border-radius: 12px;
                            border: 1px solid rgba(156, 39, 176, 0.2);
                            
                            .refund-header {
                                display: flex;
                                align-items: center;
                                margin-bottom: 0.75rem;
                                
                                .refund-icon {
                                    width: 35px;
                                    height: 35px;
                                    border-radius: 8px;
                                    background: linear-gradient(135deg, #9C27B0, #7B1FA2);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    
                                    i {
                                        color: white;
                                        font-size: 1rem;
                                    }
                                }
                                
                                .refund-title {
                                    font-weight: 700;
                                    color: #2c3e50;
                                    margin: 0;
                                }
                            }
                            
                            .refund-details {
                                color: #6c757d;
                                font-size: 0.9rem;
                                margin: 0;
                                padding-left: 2.75rem;
                            }
                        }
                    }
                }
                
                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    
                    .empty-icon {
                        font-size: 4rem;
                        color: #6c757d;
                        margin-bottom: 1.5rem;
                        opacity: 0.5;
                    }
                    
                    .empty-title {
                        font-weight: 700;
                        font-size: 1.3rem;
                        color: #2c3e50;
                        margin-bottom: 0.5rem;
                    }
                    
                    .empty-subtitle {
                        color: #6c757d;
                        margin-bottom: 2rem;
                    }
                    
                    .empty-action {
                        border-radius: 25px;
                        font-weight: 700;
                        padding: 1rem 2rem;
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 25px rgba(236, 1, 102, 0.3);
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
            
            #secao-meus-pagamentos {
                .pagamentos-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .nav-tabs {
                        .nav-link {
                            color: #ccc;
                            
                            &:hover {
                                background-color: rgba(236, 1, 102, 0.2);
                            }
                        }
                    }
                    
                    .summary-cards {
                        .summary-card {
                            background: #2d2d2d;
                            border-color: #444;
                        }
                    }
                    
                    .filters-section {
                        background: rgba(45, 45, 45, 0.7);
                        
                        .filters-header {
                            h6 {
                                color: var(--nown-terciaria);
                            }
                        }
                        
                        .filter-controls {
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
                    
                    .payments-grid {
                        .payment-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .payment-header {
                                .payment-info {
                                    .payment-id {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .payment-details {
                                .payment-method-info {
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                    
                                    .method-details {
                                        .method-name {
                                            color: var(--nown-terciaria);
                                        }
                                    }
                                }
                                
                                .transaction-details {
                                    .detail-item {
                                        .detail-value {
                                            color: var(--nown-terciaria);
                                            
                                            &.transaction-id {
                                                background: rgba(236, 1, 102, 0.15);
                                            }
                                        }
                                    }
                                }
                            }
                            
                            .payment-order-info {
                                background: linear-gradient(135deg, rgba(0, 0, 128, 0.08), rgba(236, 1, 102, 0.08));
                                
                                .order-header {
                                    .order-number {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .refund-info {
                                background: linear-gradient(135deg, rgba(156, 39, 176, 0.08), rgba(123, 31, 162, 0.08));
                                border-color: rgba(156, 39, 176, 0.3);
                                
                                .refund-header {
                                    .refund-title {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                        }
                    }
                    
                    .empty-state {
                        .empty-title {
                            color: var(--nown-terciaria);
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
                box-shadow: 0 10px 30px rgba(236, 1, 102, 0.3);
                border: none;
                transition: all 0.3s ease;
                
                &:hover {
                    transform: scale(1.1);
                    box-shadow: 0 15px 40px rgba(236, 1, 102, 0.4);
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
            <h1 class="text-center"><i class="bi bi-credit-card me-3"></i>Meus Pagamentos</h1>
            <p class="subtitle">Controle total sobre suas transações financeiras dentro do sistema</p>
        </div>

        <!-- Seção Principal -->
        <section id="secao-meus-pagamentos">
            <div class="pagamentos-container">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="pagamentosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button" role="tab">
                            <i class="bi bi-list-ul"></i>Todos<span class="badge">12</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pendentes-tab" data-bs-toggle="tab" data-bs-target="#pendentes" type="button" role="tab">
                            <i class="bi bi-clock"></i>Pendentes<span class="badge">2</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="aprovados-tab" data-bs-toggle="tab" data-bs-target="#aprovados" type="button" role="tab">
                            <i class="bi bi-check-circle"></i>Aprovados<span class="badge">8</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="recusados-tab" data-bs-toggle="tab" data-bs-target="#recusados" type="button" role="tab">
                            <i class="bi bi-x-circle"></i>Recusados<span class="badge">1</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="estornos-tab" data-bs-toggle="tab" data-bs-target="#estornos" type="button" role="tab">
                            <i class="bi bi-arrow-clockwise"></i>Estornos<span class="badge">1</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="pagamentosTabsContent">
                    <!-- Todos os Pagamentos Tab -->
                    <div class="tab-pane fade show active" id="todos" role="tabpanel">
                        <!-- Cards de Resumo -->
                        <div class="summary-cards">
                            <div class="summary-card">
                                <div class="summary-icon total">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="summary-value">R$ 12.847,60</div>
                                <div class="summary-label">Total Transacionado</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon pending">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="summary-value">R$ 578,90</div>
                                <div class="summary-label">Pendentes</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon approved">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="summary-value">R$ 11.979,80</div>
                                <div class="summary-label">Aprovados</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-icon failed">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="summary-value">R$ 288,90</div>
                                <div class="summary-label">Falhas/Estornos</div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="filters-section">
                            <div class="filters-header">
                                <div class="filter-icon">
                                    <i class="bi bi-funnel"></i>
                                </div>
                                <h6>Filtros de Busca</h6>
                            </div>
                            <div class="filter-controls">
                                <input type="text" class="form-control" placeholder="Buscar por ID da transação ou pedido" style="min-width: 300px;">
                                <select class="form-select" style="width: 180px;">
                                    <option value="">Período</option>
                                    <option value="7">Últimos 7 dias</option>
                                    <option value="30">Últimos 30 dias</option>
                                    <option value="90">Últimos 3 meses</option>
                                    <option value="365">Último ano</option>
                                </select>
                                <select class="form-select" style="width: 180px;">
                                    <option value="">Método</option>
                                    <option value="pix">PIX</option>
                                    <option value="card">Cartão</option>
                                    <option value="boleto">Boleto</option>
                                    <option value="bank">Transferência</option>
                                </select>
                                <select class="form-select" style="width: 150px;">
                                    <option value="">Status</option>
                                    <option value="pending">Pendente</option>
                                    <option value="approved">Aprovado</option>
                                    <option value="failed">Recusado</option>
                                    <option value="refunded">Estornado</option>
                                </select>
                                <button class="btn btn-n-primaria btn-filter">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Lista de Pagamentos -->
                        <div class="payments-grid" id="listaPagamentos">
                           
                        </div>
                    </div>

                    <!-- Pendentes Tab -->
                    <div class="tab-pane fade" id="pendentes" role="tabpanel">
                        <div class="payments-grid">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="empty-title">Nenhum pagamento pendente</div>
                                <div class="empty-subtitle">Todos os seus pagamentos estão em dia ou foram processados.</div>
                                <button class="btn btn-n-primaria empty-action">
                                    <i class="bi bi-plus-circle me-2"></i>Fazer Nova Compra
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Aprovados Tab -->
                    <div class="tab-pane fade" id="aprovados" role="tabpanel">
                        <div class="payments-grid">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="empty-title">Pagamentos aprovados aparecerão aqui</div>
                                <div class="empty-subtitle">Todas as suas transações bem-sucedidas ficam organizadas nesta seção.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recusados Tab -->
                    <div class="tab-pane fade" id="recusados" role="tabpanel">
                        <div class="payments-grid">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="empty-title">Nenhum pagamento recusado</div>
                                <div class="empty-subtitle">Que bom! Você não possui transações recusadas.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Estornos Tab -->
                    <div class="tab-pane fade" id="estornos" role="tabpanel">
                        <div class="payments-grid">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </div>
                                <div class="empty-title">Histórico de estornos</div>
                                <div class="empty-subtitle">Aqui você encontra todos os estornos e reembolsos processados.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

 

<?

$conn = conn();
$user = $_SESSION["id"];
$seleciona = "SELECT * FROM pay_pagamentos WHERE pagamento_usuario='$user' AND pagamento_estado='2'";
$resultado = $conn->query($seleciona);

function pegaPedido($id, $conn){
    $seleciona = "SELECT * FROM pay_pedidos WHERE pedido_id='$id'";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows == 0){
        return ["id"=>"", "publico"=>""];
    }
    $dado = $resultado->fetch_assoc();
    return ["id"=>$dado["pedido_hash"], "publico"=>$dado["pedido_url"]];
    
    
}



?>


<div class="container my-4">
    <h2 class="fs-24 fw-700 mb-4">
        Meus Pagamentos
    </h2>
    <div class="card card-nown">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item active">
                    <div class="row">
                                <div class="col-4">Pedido</div>
                                <div class="col-2">Data</div>
                                <div class="col-2">Valor</div>
                                <div class="col-2">Meio</div>
                                <div class="col-2">Fatura</div>
                            </div>
                    
                </li>

                <?
                if($resultado->num_rows > 0){
                    while($dado = $resultado->fetch_assoc()){
                        $pedido = pegaPedido($dado["pagamento_pedido"], $conn);
                        echo '
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">#'.$pedido["id"].'</div>
                                <div class="col-2 d-flex align-items-center">'.$dado["pagamento_data"].'</div>
                                <div class="col-2 d-flex align-items-center">R$ '.$dado["pagamento_valor"].'</div>
                                <div class="col-2 d-flex align-items-center">PIX</div>
                                <div class="col-2"><a class="btn btn-nown-style btn-n-primaria" href="/pagamento/'.$pedido["publico"].'">Ver Fatura</a></div>
                            </div>
                        </li>';
                    }
                }
                
                ?>
            </ul>
        </div>
    </div>
</div>

