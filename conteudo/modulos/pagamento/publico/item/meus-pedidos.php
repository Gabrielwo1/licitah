 <style>
        #secao-meus-pedidos {
            .pedidos-container {
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
                
                .orders-grid {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                    
                    .order-card {
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
                        
                        .order-header {
                            display: flex;
                            justify-content: between;
                            align-items: center;
                            margin-bottom: 1.5rem;
                            padding-bottom: 1rem;
                            border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                            
                            .order-info {
                                .order-number {
                                    font-weight: 800;
                                    font-size: 1.2rem;
                                    color: #2c3e50;
                                    margin-bottom: 0.25rem;
                                }
                                
                                .order-date {
                                    color: #6c757d;
                                    font-size: 0.9rem;
                                    margin: 0;
                                }
                            }
                            
                            .order-status {
                                .status-badge {
                                    padding: 0.75rem 1.5rem;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    
                                    &.waiting-payment {
                                        background: linear-gradient(135deg, #FF9800, #F57C00);
                                        color: white;
                                    }
                                    
                                    &.confirmed {
                                        background: linear-gradient(135deg, #2196F3, #1976D2);
                                        color: white;
                                    }
                                    
                                    &.preparing {
                                        background: linear-gradient(135deg, #9C27B0, #7B1FA2);
                                        color: white;
                                    }
                                    
                                    &.shipped {
                                        background: linear-gradient(135deg, #FF5722, #D84315);
                                        color: white;
                                    }
                                    
                                    &.delivered {
                                        background: linear-gradient(135deg, #4CAF50, #388E3C);
                                        color: white;
                                    }
                                    
                                    &.cancelled {
                                        background: linear-gradient(135deg, #f44336, #d32f2f);
                                        color: white;
                                    }
                                }
                            }
                        }
                        
                        .order-details {
                            margin-bottom: 1.5rem;
                            
                            .store-info {
                                display: flex;
                                align-items: center;
                                margin-bottom: 1rem;
                                
                                .store-icon {
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 8px;
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1), rgba(0, 0, 128, 0.1));
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 1rem;
                                    
                                    i {
                                        color: var(--nown-primaria);
                                        font-size: 1.2rem;
                                    }
                                }
                                
                                .store-name {
                                    font-weight: 600;
                                    color: #2c3e50;
                                    margin: 0;
                                }
                            }
                            
                            .products-list {
                                .product-item {
                                    display: flex;
                                    align-items: center;
                                    padding: 1rem;
                                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                                    border-radius: 12px;
                                    margin-bottom: 0.75rem;
                                    transition: all 0.3s ease;
                                    
                                    &:hover {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                        transform: translateX(5px);
                                    }
                                    
                                    &:last-child {
                                        margin-bottom: 0;
                                    }
                                    
                                    .product-image {
                                        width: 60px;
                                        height: 60px;
                                        border-radius: 10px;
                                        object-fit: cover;
                                        margin-right: 1rem;
                                        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
                                    }
                                    
                                    .product-info {
                                        flex: 1;
                                        
                                        .product-name {
                                            font-weight: 600;
                                            color: #2c3e50;
                                            margin-bottom: 0.25rem;
                                            font-size: 0.95rem;
                                        }
                                        
                                        .product-details {
                                            color: #6c757d;
                                            font-size: 0.85rem;
                                            margin: 0;
                                        }
                                    }
                                    
                                    .product-price {
                                        font-weight: 700;
                                        color: var(--nown-primaria);
                                        font-size: 1rem;
                                    }
                                }
                            }
                        }
                        
                        .order-summary {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 1.5rem;
                            padding: 1rem;
                            background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                            border-radius: 12px;
                            
                            .summary-info {
                                .total-label {
                                    color: #6c757d;
                                    font-size: 0.9rem;
                                    margin-bottom: 0.25rem;
                                }
                                
                                .total-value {
                                    font-weight: 800;
                                    font-size: 1.3rem;
                                    color: var(--nown-primaria);
                                    margin: 0;
                                }
                            }
                            
                            .payment-method {
                                display: flex;
                                align-items: center;
                                
                                .payment-icon {
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
                                
                                .payment-text {
                                    font-weight: 600;
                                    color: #2c3e50;
                                    font-size: 0.9rem;
                                    margin: 0;
                                }
                            }
                        }
                        
                        .order-actions {
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
                            }
                        }
                        
                        .tracking-info {
                            margin-top: 1.5rem;
                            padding: 1rem;
                            background: linear-gradient(135deg, rgba(0, 0, 128, 0.05), rgba(236, 1, 102, 0.05));
                            border-radius: 12px;
                            
                            .tracking-header {
                                display: flex;
                                align-items: center;
                                margin-bottom: 1rem;
                                
                                .tracking-icon {
                                    width: 35px;
                                    height: 35px;
                                    border-radius: 8px;
                                    background: linear-gradient(135deg, #FF5722, #D84315);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    
                                    i {
                                        color: white;
                                        font-size: 1rem;
                                    }
                                }
                                
                                .tracking-text {
                                    font-weight: 700;
                                    color: #2c3e50;
                                    margin: 0;
                                }
                            }
                            
                            .tracking-code {
                                font-family: 'Courier New', monospace;
                                background: rgba(255,255,255,0.7);
                                padding: 0.5rem 1rem;
                                border-radius: 8px;
                                font-weight: 600;
                                color: var(--nown-primaria);
                                margin-bottom: 1rem;
                                border: 1px solid rgba(236, 1, 102, 0.2);
                            }
                            
                            .estimated-delivery {
                                font-size: 0.9rem;
                                color: #6c757d;
                                margin: 0;
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
            
            #secao-meus-pedidos {
                .pedidos-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .nav-tabs {
                        .nav-link {
                            color: #ccc;
                            
                            &:hover {
                                background-color: rgba(236, 1, 102, 0.2);
                            }
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
                    
                    .orders-grid {
                        .order-card {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .order-header {
                                .order-info {
                                    .order-number {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .order-details {
                                .store-info {
                                    .store-name {
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                .products-list {
                                    .product-item {
                                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                                        
                                        .product-info {
                                            .product-name {
                                                color: var(--nown-terciaria);
                                            }
                                        }
                                    }
                                }
                            }
                            
                            .order-summary {
                                background: linear-gradient(135deg, rgba(236, 1, 102, 0.08), rgba(0, 0, 128, 0.08));
                                
                                .payment-method {
                                    .payment-text {
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                            
                            .tracking-info {
                                background: linear-gradient(135deg, rgba(0, 0, 128, 0.08), rgba(236, 1, 102, 0.08));
                                
                                .tracking-header {
                                    .tracking-text {
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                .tracking-code {
                                    background: rgba(58, 58, 58, 0.7);
                                    border-color: rgba(236, 1, 102, 0.3);
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
            <h1 class="text-center"><i class="bi bi-bag-check me-3"></i>Meus Pedidos</h1>
            <p class="subtitle">Acompanhe todos os seus pedidos em um só lugar</p>
        </div>

        <!-- Seção Principal -->
        <section id="secao-meus-pedidos">
            <div class="pedidos-container">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="pedidosTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button" role="tab">
                            <i class="bi bi-list-ul"></i>Todos<span class="badge">8</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="andamento-tab" data-bs-toggle="tab" data-bs-target="#andamento" type="button" role="tab">
                            <i class="bi bi-clock"></i>Em Andamento<span class="badge">3</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="entregues-tab" data-bs-toggle="tab" data-bs-target="#entregues" type="button" role="tab">
                            <i class="bi bi-check-circle"></i>Entregues<span class="badge">4</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cancelados-tab" data-bs-toggle="tab" data-bs-target="#cancelados" type="button" role="tab">
                            <i class="bi bi-x-circle"></i>Cancelados<span class="badge">1</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="pedidosTabsContent">
                    <!-- Todos os Pedidos Tab -->
                    <div class="tab-pane fade show active" id="todos" role="tabpanel">
                        <!-- Filtros -->
                        <div class="filters-section">
                            <div class="filters-header">
                                <div class="filter-icon">
                                    <i class="bi bi-funnel"></i>
                                </div>
                                <h6>Filtros</h6>
                            </div>
                            <div class="filter-controls">
                                <input type="text" class="form-control" placeholder="Buscar por número do pedido ou produto" style="min-width: 300px;">
                                <select class="form-select" style="width: 180px;">
                                    <option value="">Período</option>
                                    <option value="7">Últimos 7 dias</option>
                                    <option value="30">Últimos 30 dias</option>
                                    <option value="90">Últimos 3 meses</option>
                                    <option value="365">Último ano</option>
                                </select>
                                <select class="form-select" style="width: 150px;">
                                    <option value="">Loja</option>
                                    <option value="techstore">TechStore</option>
                                    <option value="fashion">Fashion Style</option>
                                    <option value="casa">Casa & Jardim</option>
                                </select>
                                <button class="btn btn-n-primaria btn-filter">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Lista de Pedidos -->
                        <div class="orders-grid">
                            <!-- Pedido 1 - Enviado -->
                            <div class="order-card">
                                <div class="order-header d-flex justify-content-between align-items-center">
                                    <div class="order-info">
                                        <div class="order-number">Pedido #12345</div>
                                        <div class="order-date">Realizado em 08/06/2025</div>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge shipped">Enviado</span>
                                    </div>
                                </div>
                                
                                <div class="order-details">
                                    <div class="store-info">
                                        <div class="store-icon">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <div class="store-name">TechStore Premium</div>
                                    </div>
                                    
                                    <div class="products-list">
                                        <div class="product-item">
                                            <img src="https://placehold.co/60x60" alt="Produto" class="product-image">
                                            <div class="product-info">
                                                <div class="product-name">Smartphone Galaxy S24 Ultra</div>
                                                <div class="product-details">Cor: Preto | Qtd: 1</div>
                                            </div>
                                            <div class="product-price">R$ 4.599,00</div>
                                        </div>
                                        <div class="product-item">
                                            <img src="https://placehold.co/60x60" alt="Produto" class="product-image">
                                            <div class="product-info">
                                                <div class="product-name">Capa Protetora Premium</div>
                                                <div class="product-details">Cor: Transparente | Qtd: 1</div>
                                            </div>
                                            <div class="product-price">R$ 89,90</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-summary">
                                    <div class="summary-info">
                                        <div class="total-label">Total do Pedido</div>
                                        <div class="total-value">R$ 4.688,90</div>
                                    </div>
                                    <div class="payment-method">
                                        <div class="payment-icon">
                                            <i class="bi bi-credit-card"></i>
                                        </div>
                                        <div class="payment-text">Cartão de Crédito</div>
                                    </div>
                                </div>
                                
                                <div class="tracking-info">
                                    <div class="tracking-header">
                                        <div class="tracking-icon">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <div class="tracking-text">Código de Rastreamento</div>
                                    </div>
                                    <div class="tracking-code">BR123456789SE</div>
                                    <div class="estimated-delivery">Previsão de entrega: 12/06/2025</div>
                                </div>
                                
                                <div class="order-actions">
                                    <button class="btn btn-n-primaria action-btn">
                                        <i class="bi bi-truck me-1"></i>Rastrear Pedido
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-chat-dots me-1"></i>Falar com Vendedor
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-arrow-repeat me-1"></i>Comprar Novamente
                                    </button>
                                </div>
                            </div>

                            <!-- Pedido 2 - Entregue -->
                            <div class="order-card">
                                <div class="order-header d-flex justify-content-between align-items-center">
                                    <div class="order-info">
                                        <div class="order-number">Pedido #12344</div>
                                        <div class="order-date">Realizado em 02/06/2025 • Entregue em 05/06/2025</div>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge delivered">Entregue</span>
                                    </div>
                                </div>
                                
                                <div class="order-details">
                                    <div class="store-info">
                                        <div class="store-icon">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <div class="store-name">Fashion Style</div>
                                    </div>
                                    
                                    <div class="products-list">
                                        <div class="product-item">
                                            <img src="https://placehold.co/60x60" alt="Produto" class="product-image">
                                            <div class="product-info">
                                                <div class="product-name">Camisa Social Masculina</div>
                                                <div class="product-details">Tamanho: M | Cor: Azul | Qtd: 2</div>
                                            </div>
                                            <div class="product-price">R$ 299,80</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-summary">
                                    <div class="summary-info">
                                        <div class="total-label">Total do Pedido</div>
                                        <div class="total-value">R$ 299,80</div>
                                    </div>
                                    <div class="payment-method">
                                        <div class="payment-icon">
                                            <i class="bi bi-qr-code"></i>
                                        </div>
                                        <div class="payment-text">PIX</div>
                                    </div>
                                </div>
                                
                                <div class="order-actions">
                                    <button class="btn btn-outline-success action-btn">
                                        <i class="bi bi-star me-1"></i>Avaliar Produto
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-arrow-repeat me-1"></i>Comprar Novamente
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-receipt me-1"></i>Ver Nota Fiscal
                                    </button>
                                </div>
                            </div>

                            <!-- Pedido 3 - Aguardando Pagamento -->
                            <div class="order-card">
                                <div class="order-header d-flex justify-content-between align-items-center">
                                    <div class="order-info">
                                        <div class="order-number">Pedido #12343</div>
                                        <div class="order-date">Realizado em 10/06/2025</div>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge waiting-payment">Aguardando Pagamento</span>
                                    </div>
                                </div>
                                
                                <div class="order-details">
                                    <div class="store-info">
                                        <div class="store-icon">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                        <div class="store-name">Casa & Jardim</div>
                                    </div>
                                    
                                    <div class="products-list">
                                        <div class="product-item">
                                            <img src="https://placehold.co/60x60" alt="Produto" class="product-image">
                                            <div class="product-info">
                                                <div class="product-name">Conjunto de Panelas Antiaderente</div>
                                                <div class="product-details">5 Peças | Cor: Vermelho | Qtd: 1</div>
                                            </div>
                                            <div class="product-price">R$ 289,90</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-summary">
                                    <div class="summary-info">
                                        <div class="total-label">Total do Pedido</div>
                                        <div class="total-value">R$ 289,90</div>
                                    </div>
                                    <div class="payment-method">
                                        <div class="payment-icon">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="payment-text">Boleto Bancário</div>
                                    </div>
                                </div>
                                
                                <div class="order-actions">
                                    <button class="btn btn-n-primaria action-btn">
                                        <i class="bi bi-credit-card me-1"></i>Efetuar Pagamento
                                    </button>
                                    <button class="btn btn-outline-danger action-btn">
                                        <i class="bi bi-x-lg me-1"></i>Cancelar Pedido
                                    </button>
                                    <button class="btn btn-outline-secondary action-btn">
                                        <i class="bi bi-chat-dots me-1"></i>Falar com Vendedor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Em Andamento Tab -->
                    <div class="tab-pane fade" id="andamento" role="tabpanel">
                        <div class="orders-grid">
                            <!-- Pedidos em andamento apareceriam aqui -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="empty-title">Nenhum pedido em andamento</div>
                                <div class="empty-subtitle">Todos os seus pedidos foram entregues ou estão aguardando ação.</div>
                                <button class="btn btn-n-primaria empty-action">
                                    <i class="bi bi-plus-circle me-2"></i>Fazer Novo Pedido
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Entregues Tab -->
                    <div class="tab-pane fade" id="entregues" role="tabpanel">
                        <div class="orders-grid">
                            <!-- Pedidos entregues apareceriam aqui -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="empty-title">Pedidos entregues aparecerão aqui</div>
                                <div class="empty-subtitle">Quando seus pedidos forem entregues, você poderá vê-los nesta seção.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelados Tab -->
                    <div class="tab-pane fade" id="cancelados" role="tabpanel">
                        <div class="orders-grid">
                            <!-- Pedidos cancelados apareceriam aqui -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="empty-title">Nenhum pedido cancelado</div>
                                <div class="empty-subtitle">Que bom! Você não possui pedidos cancelados.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

 
