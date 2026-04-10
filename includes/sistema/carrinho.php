<?
include __DIR__."/simplheader.php";
?>

    <style>
     
        /* Estrutura CSS aninhada por seções */
        #secao-carrinho {
            .carrinho-container {
                padding: 2rem;
                
                .cupom-section {
                    background: rgba(255,255,255,0.8);
                    border-radius: 12px;
                    padding: 1.25rem;
                    margin-bottom: 2rem;
                    border: 1px solid rgba(236, 1, 102, 0.1);
                    
                    .cupom-content {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 2rem;
                        
                        .cupom-title {
                            display: flex;
                            align-items: center;
                            margin: 0;
                            
                            .cupom-icon {
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
                                font-weight: 600;
                                color: #2c3e50;
                            }
                        }
                        
                        .cupom-input-group {
                            display: flex;
                            gap: 0.75rem;
                            flex: 1;
                            max-width: 400px;
                            
                            .form-control {
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                transition: all 0.3s ease;
                                
                                &:focus {
                                    border-color: var(--nown-primaria);
                                    box-shadow: 0 0 0 0.2rem rgba(236, 1, 102, 0.15);
                                }
                            }
                            
                            .btn-cupom {
                                border-radius: 8px;
                                font-weight: 600;
                                padding: 0.75rem 1.25rem;
                                transition: all 0.3s ease;
                                white-space: nowrap;
                                
                                &:hover {
                                    transform: translateY(-2px);
                                }
                            }
                        }
                        
                        .cupom-aplicado {
                            background: linear-gradient(135deg, #4CAF50, #388E3C);
                            color: white;
                            padding: 0.75rem 1.25rem;
                            border-radius: 8px;
                            font-size: 0.9rem;
                            font-weight: 600;
                            display: none;
                            
                            i {
                                margin-right: 0.5rem;
                            }
                        }
                    }
                }
                
                .carrinho-content {
                    display: flex;
                    gap: 2rem;
                    
                    .carrinho-items {
                        flex: 1;
                        
                        .section-header {
                            display: flex;
                            align-items: center;
                            margin-bottom: 1.5rem;
                            
                            .section-icon {
                                width: 45px;
                                height: 45px;
                                border-radius: 12px;
                                background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 1rem;
                                
                                i {
                                    color: white;
                                    font-size: 1.3rem;
                                }
                            }
                            
                            h3 {
                                margin: 0;
                                font-weight: 700;
                                color: #2c3e50;
                                flex: 1;
                            }
                            
                            .items-count {
                                background: rgba(236, 1, 102, 0.1);
                                color: var(--nown-primaria);
                                padding: 0.5rem 1rem;
                                border-radius: 10px;
                                font-weight: 600;
                                font-size: 0.9rem;
                            }
                        }
                        
                        .items-list {
                            display: flex;
                            flex-direction: column;
                            gap: 1rem;
                            list-style: none;
                            
                            .item-card {
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
                                    width: 3px;
                                    height: 100%;
                                    background: linear-gradient(180deg, var(--nown-primaria), var(--nown-primaria-darker));
                                    transform: scaleY(0);
                                    transition: transform 0.3s ease;
                                }
                                
                                &:hover {
                                    transform: translateY(-3px);
                                    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                                    
                                    &:before {
                                        transform: scaleY(1);
                                    }
                                }
                                
                                .item-content {
                                    display: flex;
                                    align-items: center;
                                    
                                    .item-image {
                                        width: 80px;
                                        height: 80px;
                                        border-radius: 12px;
                                        background-size: cover;
                                        background-position: center;
                                        margin-right: 1.5rem;
                                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                                        position: relative;
                                        
                                        &::after {
                                            content: '';
                                            position: absolute;
                                            top: 0;
                                            left: 0;
                                            right: 0;
                                            bottom: 0;
                                            background: linear-gradient(45deg, transparent 49%, rgba(255,255,255,0.1) 50%, transparent 51%);
                                            border-radius: 12px;
                                        }
                                    }
                                    
                                    .item-info {
                                        flex: 1;
                                        
                                        .item-name {
                                            font-weight: 700;
                                            color: #2c3e50;
                                            margin-bottom: 0.25rem;
                                            font-size: 1.1rem;
                                        }
                                        
                                        .item-category {
                                            color: #6c757d;
                                            font-size: 0.9rem;
                                            margin-bottom: 0.5rem;
                                        }
                                        
                                        .item-quantity {
                                            display: flex;
                                            align-items: center;
                                            gap: 1rem;
                                            
                                            .quantity-controls {
                                                display: flex;
                                                align-items: center;
                                                background: rgba(236, 1, 102, 0.1);
                                                border-radius: 8px;
                                                padding: 0.25rem;
                                                
                                                .qty-btn {
                                                    border: none;
                                                    background: transparent;
                                                    color: var(--nown-primaria);
                                                    width: 30px;
                                                    height: 30px;
                                                    border-radius: 6px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    transition: all 0.3s ease;
                                                    
                                                    &:hover {
                                                        background: var(--nown-primaria);
                                                        color: white;
                                                    }
                                                }
                                                
                                                .qty-input {
                                                    border: none;
                                                    background: transparent;
                                                    text-align: center;
                                                    width: 40px;
                                                    font-weight: 600;
                                                    color: var(--nown-primaria);
                                                    
                                                    &:focus {
                                                        outline: none;
                                                    }
                                                }
                                            }
                                            
                                            .unit-price {
                                                color: #6c757d;
                                                font-size: 0.9rem;
                                            }
                                        }
                                    }
                                    
                                    .item-actions {
                                        display: flex;
                                        flex-direction: column;
                                        align-items: end;
                                        gap: 1rem;
                                        
                                        .item-price {
                                            font-weight: 800;
                                            font-size: 1.2rem;
                                            color: var(--nown-primaria);
                                        }
                                        
                                        .btn-remove {
                                            width: 40px;
                                            height: 40px;
                                            border-radius: 50%;
                                            border: none;
                                            background: rgba(244, 67, 54, 0.1);
                                            color: #f44336;
                                            transition: all 0.3s ease;
                                            
                                            &:hover {
                                                background: #f44336;
                                                color: white;
                                                transform: scale(1.1);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        .carrinho-actions {
                            margin-top: 2rem;
                            padding-top: 1.5rem;
                            border-top: 2px solid rgba(236, 1, 102, 0.1);
                            
                            .btn-limpar {
                                border: 2px solid #f44336;
                                color: #f44336;
                                background: transparent;
                                padding: 0.75rem 2rem;
                                border-radius: 12px;
                                font-weight: 600;
                                transition: all 0.3s ease;
                                
                                &:hover {
                                    background: #f44336;
                                    color: white;
                                    transform: translateY(-2px);
                                    box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
                                }
                            }
                        }
                    }
                    
                    .carrinho-sidebar {
                        width: 400px;
                        
                        .frete-section {
                            background: white;
                            border-radius: 20px;
                            padding: 2rem;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                            border: 1px solid rgba(0,0,0,0.05);
                            margin-bottom: 1.5rem;
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
                                background: linear-gradient(180deg, #2196F3, #1976D2);
                                transform: scaleY(0);
                                transition: transform 0.3s ease;
                            }
                            
                            &:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 15px 40px rgba(0,0,0,0.12);
                                
                                &:before {
                                    transform: scaleY(1);
                                }
                            }
                            
                            .frete-header {
                                display: flex;
                                align-items: center;
                                margin-bottom: 1.5rem;
                                padding-bottom: 1rem;
                                border-bottom: 2px solid rgba(33, 150, 243, 0.1);
                                
                                .frete-icon {
                                    width: 45px;
                                    height: 45px;
                                    border-radius: 12px;
                                    background: linear-gradient(135deg, #2196F3, #1976D2);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 1rem;
                                    
                                    i {
                                        color: white;
                                        font-size: 1.3rem;
                                    }
                                }
                                
                                h5 {
                                    margin: 0;
                                    font-weight: 700;
                                    color: #2c3e50;
                                }
                            }
                            
                            .cep-input {
                                display: flex;
                                gap: 0.75rem;
                                margin-bottom: 1.5rem;
                                
                                .form-control {
                                    border: 2px solid #e9ecef;
                                    border-radius: 10px;
                                    flex: 1;
                                    padding: 0.875rem 1rem;
                                    transition: all 0.3s ease;
                                    
                                    &:focus {
                                        border-color: #2196F3;
                                        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.15);
                                    }
                                }
                                
                                .btn-calcular {
                                    border-radius: 10px;
                                    padding: 0.875rem 1.5rem;
                                    font-weight: 600;
                                    background: #2196F3;
                                    border: none;
                                    color: white;
                                    transition: all 0.3s ease;
                                    
                                    &:hover {
                                        background: #1976D2;
                                        transform: translateY(-2px);
                                        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
                                    }
                                }
                            }
                            
                            .opcoes-frete {
                                display: none;
                                
                                &.show {
                                    display: block;
                                    animation: fadeIn 0.5s ease-in-out;
                                }
                                
                                .opcoes-header {
                                    margin-bottom: 1rem;
                                    
                                    h6 {
                                        margin: 0;
                                        font-weight: 600;
                                        color: #2c3e50;
                                        font-size: 0.95rem;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    }
                                }
                                
                                .opcao-frete {
                                    border: 2px solid #e9ecef;
                                    border-radius: 12px;
                                    padding: 1.25rem;
                                    margin-bottom: 0.75rem;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    background: rgba(33, 150, 243, 0.02);
                                    
                                    &:hover {
                                        border-color: #2196F3;
                                        background: rgba(33, 150, 243, 0.05);
                                        transform: translateX(5px);
                                    }
                                    
                                    &.selected {
                                        border-color: #2196F3;
                                        background: rgba(33, 150, 243, 0.1);
                                        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2);
                                    }
                                    
                                    &:last-child {
                                        margin-bottom: 0;
                                    }
                                    
                                    .form-check {
                                        margin: 0;
                                        
                                        .form-check-input {
                                            &:checked {
                                                background-color: #2196F3;
                                                border-color: #2196F3;
                                            }
                                        }
                                        
                                        .form-check-label {
                                            width: 100%;
                                            
                                            .frete-info {
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                                
                                                .frete-details {
                                                    .frete-nome {
                                                        font-weight: 700;
                                                        color: #2c3e50;
                                                        margin-bottom: 0.25rem;
                                                        font-size: 1rem;
                                                    }
                                                    
                                                    .frete-prazo {
                                                        color: #6c757d;
                                                        font-size: 0.85rem;
                                                        margin: 0;
                                                    }
                                                }
                                                
                                                .frete-preco {
                                                    font-weight: 800;
                                                    color: var(--nown-primaria);
                                                    font-size: 1.2rem;
                                                    
                                                    &.gratis {
                                                        color: #4CAF50;
                                                        font-size: 1rem;
                                                        font-weight: 700;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        .resumo-section {
                            background: white;
                            border-radius: 20px;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                            overflow: hidden;
                            position: sticky;
                            top: 2rem;
                            
                            .resumo-header {
                                background: green;
                                color: white;
                                padding: 1.5rem;
                                
                                h4 {
                                    margin: 0;
                                    font-weight: 700;
                                    display: flex;
                                    align-items: center;
                                    color: white;
                                    i {
                                        margin-right: 0.75rem;
                                        font-size: 1.3rem;
                                        color: white;
                                    }
                                }
                            }
                            
                            .resumo-body {
                                padding: 0;
                                
                                .resumo-list {
                                    list-style: none;
                                    margin: 0;
                                    padding: 0;
                                    
                                    li {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                        padding: 1rem 1.5rem;
                                        border-bottom: 1px solid #f8f9fa;
                                        
                                        &:last-child {
                                            border-bottom: none;
                                        }
                                        
                                        .resumo-label {
                                            color: #6c757d;
                                            font-weight: 500;
                                        }
                                        
                                        .resumo-valor {
                                            font-weight: 700;
                                            color: var(--nown-primaria);
                                            
                                            &.total {
                                                font-size: 1.3rem;
                                                color: #2c3e50;
                                            }
                                        }
                                        
                                        &.total-row {
                                            padding: 1.5rem;
                                            border-top: 2px solid rgba(236, 1, 102, 0.1);
                                            
                                            .resumo-label {
                                                font-weight: 700;
                                                font-size: 1.1rem;
                                                color: #2c3e50;
                                            }
                                        }
                                    }
                                }
                                
                                .btn-finalizar {
                                    width: 100%;
                                    border: none;
                                    border-radius: 0;
                                    padding: 1.5rem;
                                    font-weight: 800;
                                    font-size: 1.2rem;
                                    background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                    color: white;
                                    transition: all 0.3s ease;
                                    box-shadow: 0 4px 20px rgba(var(--nown-primaria-rgb), 0.3);
                                    
                                    &:hover {
                                        transform: translateY(-3px);
                                        box-shadow: 0 8px 30px rgba(var(--nown-primaria-rgb), 0.4);
                                        background: linear-gradient(135deg, var(--nown-primaria-darker), var(--nown-primaria));
                                    }
                                    
                                    &:active {
                                        transform: translateY(-1px);
                                    }
                                }
                                
                                .seguranca-info {
                                    background: rgba(76, 175, 80, 0.1);
                                    border: 1px solid rgba(76, 175, 80, 0.2);
                                    padding: 1rem 1.5rem;
                                    font-size: 0.85rem;
                                    color: #2c3e50;
                                    
                                    i {
                                        color: #4CAF50;
                                        margin-right: 0.5rem;
                                    }
                                }
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

        @keyframes slideOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }

        .item-card.removing {
            animation: slideOut 0.5s ease-out forwards;
        }

        /* Responsividade */
        @media (max-width: 1199px) {
            #secao-carrinho {
                .carrinho-container {
                    .carrinho-content {
                        .carrinho-sidebar {
                            width: 350px;
                        }
                    }
                }
            }
        }

        @media (max-width: 991px) {
            #secao-carrinho {
                .carrinho-container {
                    .carrinho-content {
                        flex-direction: column;
                        
                        .carrinho-sidebar {
                            width: 100%;
                            
                            .resumo-section {
                                position: static;
                            }
                        }
                    }
                }
            }
        }

        @media (max-width: 767px) {
            #secao-carrinho {
                .carrinho-container {
                    .cupom-section {
                        .cupom-content {
                            flex-direction: column;
                            gap: 1rem;
                            
                            .cupom-input-group {
                                max-width: 100%;
                                
                                .form-control {
                                    margin-bottom: 0.75rem;
                                }
                            }
                        }
                    }
                    
                    .carrinho-content {
                        .carrinho-items {
                            .items-list {
                                .item-card {
                                    .item-content {
                                        flex-direction: column;
                                        text-align: center;
                                        
                                        .item-image {
                                            margin: 0 0 1rem 0;
                                        }
                                        
                                        .item-info {
                                            margin-bottom: 1rem;
                                        }
                                        
                                        .item-actions {
                                            align-items: center;
                                        }
                                    }
                                }
                            }
                        }
                        
                        .carrinho-sidebar {
                            .frete-section {
                                .cep-input {
                                    flex-direction: column;
                                    
                                    .btn-calcular {
                                        width: 100%;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /* Suporte a Dark Mode */
        body.light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-carrinho {
                .carrinho-container {
                    .cupom-section {
                        background: rgba(45, 45, 45, 0.8);
                        border-color: rgba(236, 1, 102, 0.2);
                        
                        .cupom-content {
                            .cupom-title {
                                h6 {
                                    color: var(--nown-terciaria);
                                }
                            }
                            
                            .cupom-input-group {
                                .form-control {
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
                    
                    .carrinho-content {
                        .carrinho-items {
                            .section-header {
                                h3 {
                                    color: var(--nown-terciaria);
                                }
                            }
                            
                            .items-list {
                                .item-card {
                                    background: #2d2d2d;
                                    border-color: #444;
                                    
                                    .item-content {
                                        .item-info {
                                            .item-name {
                                                color: var(--nown-terciaria);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        .carrinho-sidebar {
                            .frete-section {
                                background: #2d2d2d;
                                border-color: #444;
                                
                                .frete-header {
                                    h5 {
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                .cep-input {
                                    .form-control {
                                        background-color: #3a3a3a;
                                        border-color: #555;
                                        color: var(--nown-terciaria);
                                    }
                                }
                                
                                .opcoes-frete {
                                    .opcoes-header {
                                        h6 {
                                            color: var(--nown-terciaria);
                                        }
                                    }
                                    
                                    .opcao-frete {
                                        background: #3a3a3a;
                                        border-color: #555;
                                        
                                        .form-check {
                                            .form-check-label {
                                                .frete-info {
                                                    .frete-details {
                                                        .frete-nome {
                                                            color: var(--nown-terciaria);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                            .resumo-section {
                                background: #2d2d2d;
                                border-color: #444;
                                
                                .resumo-body {
                                    .resumo-list {
                                        li {
                                            border-bottom-color: #444;
                                            
                                            &.total-row {
                                                border-top-color: rgba(236, 1, 102, 0.2);
                                                
                                                .resumo-label {
                                                    color: var(--nown-terciaria);
                                                }
                                                
                                                .resumo-valor.total {
                                                    color: var(--nown-terciaria);
                                                }
                                            }
                                        }
                                    }
                                    
                                    .seguranca-info {
                                        background: rgba(76, 175, 80, 0.15);
                                        color: var(--nown-terciaria);
                                    }
                                }
                            }
                        }
                    }
                }
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
        <!-- Seção Principal -->
        <section id="secao-carrinho">
            <div class="carrinho-container">
                <!-- Seção de Cupom - Minimalista -->
               

                <!-- Conteúdo Principal -->
                <div class="carrinho-content">
                    
                    
                    <!-- Lista de Items -->
                    <div class="carrinho-items">
                         <div class="cupom-section">
                    <div class="cupom-content">
                        <div class="cupom-title">
                            <div class="cupom-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                            <h6>Cupom de Desconto</h6>
                        </div>
                        <div class="cupom-input-group">
                            <input type="text" class="form-control" id="inputCupom" placeholder="Digite seu código">
                            <button class="btn btn-n-primaria btn-cupom" id="btnAplicarCupom">
                                Aplicar
                            </button>
                        </div>
                        <div class="cupom-aplicado" id="cupomAplicado">
                            <i class="bi bi-check-circle"></i>
                            DESCONTO10 aplicado!
                        </div>
                    </div>
                </div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-bag"></i>
                            </div>
                            <h3>Seus Produtos</h3>
                            <div class="items-count">3 itens</div>
                        </div>

                        <div class="items-list" id="listaCarrinho">
                            
                        </div>

                        <div class="carrinho-actions text-end">
                            <button class="btn btn-limpar" id="btnLimpar">
                                <i class="bi bi-trash me-1"></i>Limpar Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="carrinho-sidebar">
                        <!-- Seção de Frete -->
                        <div class="frete-section">
                            <div class="frete-header">
                                <div class="frete-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <h5>Calcular Frete</h5>
                            </div>
                            
                            <div class="cep-input">
                                <input type="text" class="form-control" id="inputCep" placeholder="Digite seu CEP" maxlength="9">
                                <button class="btn btn-calcular" id="btnCalcularFrete">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            
                            <div class="opcoes-frete" id="opcoesFreteContainer">
                                <div class="opcoes-header">
                                    <h6>Escolha a forma de entrega:</h6>
                                </div>
                                
                                <div class="opcao-frete" data-valor="15.90">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frete" id="frete1" value="15.90">
                                        <label class="form-check-label" for="frete1">
                                            <div class="frete-info">
                                                <div class="frete-details">
                                                    <div class="frete-nome">Correios - PAC</div>
                                                    <div class="frete-prazo">Entrega em 5 a 7 dias úteis</div>
                                                </div>
                                                <div class="frete-preco">R$ 15,90</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="opcao-frete" data-valor="25.50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frete" id="frete2" value="25.50">
                                        <label class="form-check-label" for="frete2">
                                            <div class="frete-info">
                                                <div class="frete-details">
                                                    <div class="frete-nome">Correios - SEDEX</div>
                                                    <div class="frete-prazo">Entrega em 2 a 3 dias úteis</div>
                                                </div>
                                                <div class="frete-preco">R$ 25,50</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="opcao-frete" data-valor="0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="frete" id="frete3" value="0">
                                        <label class="form-check-label" for="frete3">
                                            <div class="frete-info">
                                                <div class="frete-details">
                                                    <div class="frete-nome">Frete Grátis</div>
                                                    <div class="frete-prazo">Entrega em 7 a 10 dias úteis</div>
                                                </div>
                                                <div class="frete-preco gratis">GRÁTIS</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumo da Compra -->
                        <div class="resumo-section">
                            <div class="resumo-header">
                                <h4><i class="bi bi-receipt"></i>Resumo da Compra</h4>
                            </div>
                            <div class="resumo-body">
                                <ul class="resumo-list">
                                    <li>
                                        <span class="resumo-label">Produtos (3)</span>
                                        <span class="resumo-valor" id="valorProdutos">R$ 30.002,00</span>
                                    </li>
                                    <li>
                                        <span class="resumo-label">Frete</span>
                                        <span class="resumo-valor" id="valorFrete">A calcular</span>
                                    </li>
                                    <li>
                                        <span class="resumo-label">Desconto</span>
                                        <span class="resumo-valor" id="valorDesconto">R$ 0,00</span>
                                    </li>
                                    <li class="total-row">
                                        <span class="resumo-label">Total</span>
                                        <span class="resumo-valor total" id="precoFinal">R$ 0,00</span>
                                    </li>
                                </ul>
                                
                                <button class="btn btn-finalizar" id="btnPagar">
                                    <i class="bi bi-credit-card me-2"></i>Finalizar Compra
                                </button>
                                
                                <div class="seguranca-info">
                                    <i class="bi bi-shield-check"></i>
                                    Pagamento seguro com criptografia SSL e garantido pelas leis do Código de defesa do consumidor.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

 
