<?
echo '<div class="mb-5">';
include __DIR__."/../../../../../includes/sistema/simplheader.php";
echo '</div>';


?>

    <style>
        #secao-endereco {
            .endereco-container {
                background: linear-gradient(135deg, rgba(236, 1, 102, 0.05) 0%, rgba(0, 0, 128, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                margin-bottom: 2rem;
                max-height:600px;
                overflow-y:auto;
                
                .endereco-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                    
                    .header-info {
                        display: flex;
                        align-items: center;
                        
                        .header-icon {
                            width: 50px;
                            height: 50px;
                            border-radius: 12px;
                            background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 1rem;
                            
                            i {
                                color: white;
                                font-size: 1.5rem;
                            }
                        }
                        
                        .header-title {
                            h2 {
                                font-weight: 800;
                                color: #2c3e50;
                                margin-bottom: 0.25rem;
                                font-size: 1.5rem;
                            }
                            
                            p {
                                color: #6c757d;
                                font-size: 0.9rem;
                                margin: 0;
                            }
                        }
                    }
                    
                    .btn-novo-endereco {
                        border-radius: 12px;
                        font-weight: 600;
                        padding: 0.75rem 1.5rem;
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 6px 20px rgba(236, 1, 102, 0.4);
                        }
                    }
                }
                
                .enderecos-lista {
                    .endereco-item {
                        background: white;
                        border-radius: 15px;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                        border: 2px solid transparent;
                        transition: all 0.3s ease;
                        cursor: pointer;
                        position: relative;
                        
                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                            border-color: rgba(236, 1, 102, 0.3);
                        }
                        
                        &.selected {
                            border-color: var(--nown-primaria);
                            background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                            
                            &:before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 4px;
                                height: 100%;
                                background: linear-gradient(180deg, var(--nown-primaria), var(--nown-primaria-darker));
                                border-radius: 0 15px 15px 0;
                            }
                            
                            .endereco-badge {
                                opacity: 1;
                            }
                        }
                        
                        .endereco-content {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            
                            .endereco-info {
                                flex: 1;
                                
                                .endereco-tipo {
                                    display: inline-block;
                                    background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                                    color: white;
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 15px;
                                    font-size: 0.8rem;
                                    font-weight: 600;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    margin-bottom: 0.75rem;
                                }
                                
                                .endereco-detalhes {
                                    .endereco-linha1 {
                                        font-weight: 700;
                                        color: #2c3e50;
                                        margin-bottom: 0.25rem;
                                        font-size: 1.1rem;
                                    }
                                    
                                    .endereco-linha2 {
                                        color: #6c757d;
                                        margin-bottom: 0.25rem;
                                        font-size: 0.95rem;
                                    }
                                    
                                    .endereco-linha3 {
                                        color: #6c757d;
                                        font-size: 0.95rem;
                                        margin: 0;
                                    }
                                }
                            }
                            
                            .endereco-actions {
                                display: flex;
                                flex-direction: column;
                                gap: 0.5rem;
                                align-items: center;
                                
                                .endereco-radio {
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    border: 2px solid #ddd;
                                    position: relative;
                                    transition: all 0.3s ease;
                                    
                                    &.checked {
                                        border-color: var(--nown-primaria);
                                        background: var(--nown-primaria);
                                        
                                        &:after {
                                            content: '';
                                            position: absolute;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                            width: 8px;
                                            height: 8px;
                                            background: white;
                                            border-radius: 50%;
                                        }
                                    }
                                }
                                
                                .btn-editar {
                                    background: none;
                                    border: none;
                                    color: #6c757d;
                                    font-size: 0.9rem;
                                    padding: 0.25rem;
                                    transition: all 0.3s ease;
                                    
                                    &:hover {
                                        color: var(--nown-primaria);
                                        transform: scale(1.1);
                                    }
                                }
                            }
                        }
                        
                        .endereco-badge {
                            position: absolute;
                            top: 1rem;
                            right: 1rem;
                            background: linear-gradient(135deg, #4CAF50, #388E3C);
                            color: white;
                            padding: 0.25rem 0.75rem;
                            border-radius: 12px;
                            font-size: 0.7rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            opacity: 0;
                            transition: all 0.3s ease;
                        }
                    }
                }
            }
        }
        
        #secao-frete {
            .frete-container {
                background: linear-gradient(135deg, rgba(0, 0, 128, 0.05) 0%, rgba(236, 1, 102, 0.05) 100%);
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                margin-bottom: 2rem;
                
                .frete-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 2px solid rgba(0, 0, 128, 0.1);
                    
                    .frete-icon {
                        width: 50px;
                        height: 50px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, #FF5722, #D84315);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 1rem;
                        
                        i {
                            color: white;
                            font-size: 1.5rem;
                        }
                    }
                    
                    .frete-title {
                        h2 {
                            font-weight: 800;
                            color: #2c3e50;
                            margin-bottom: 0.25rem;
                            font-size: 1.5rem;
                        }
                        
                        p {
                            color: #6c757d;
                            font-size: 0.9rem;
                            margin: 0;
                        }
                    }
                }
                
                .frete-opcoes {
                    .frete-item {
                        background: white;
                        border-radius: 15px;
                        padding: 1.5rem;
                        margin-bottom: 1rem;
                        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
                        border: 2px solid transparent;
                        transition: all 0.3s ease;
                        cursor: pointer;
                        position: relative;
                        
                        &:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                            border-color: rgba(255, 87, 34, 0.3);
                        }
                        
                        &.selected {
                            border-color: #FF5722;
                            background: linear-gradient(135deg, rgba(255, 87, 34, 0.05), rgba(211, 67, 21, 0.05));
                            
                            &:before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 4px;
                                height: 100%;
                                background: linear-gradient(180deg, #FF5722, #D84315);
                                border-radius: 0 15px 15px 0;
                            }
                        }
                        
                        .frete-content {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            
                            .frete-info {
                                display: flex;
                                align-items: center;
                                flex: 1;
                                
                                .frete-logo {
                                    width: 60px;
                                    height: 60px;
                                    border-radius: 12px;
                                    background: linear-gradient(135deg, rgba(255, 87, 34, 0.1), rgba(211, 67, 21, 0.1));
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 1rem;
                                    
                                    i {
                                        color: #FF5722;
                                        font-size: 1.8rem;
                                    }
                                }
                                
                                .frete-detalhes {
                                    .frete-nome {
                                        font-weight: 700;
                                        color: #2c3e50;
                                        margin-bottom: 0.25rem;
                                        font-size: 1.1rem;
                                    }
                                    
                                    .frete-prazo {
                                        color: #6c757d;
                                        font-size: 0.9rem;
                                        margin-bottom: 0.25rem;
                                    }
                                    
                                    .frete-descricao {
                                        color: #6c757d;
                                        font-size: 0.85rem;
                                        margin: 0;
                                    }
                                }
                            }
                            
                            .frete-preco {
                                text-align: right;
                                
                                .preco-valor {
                                    font-weight: 800;
                                    font-size: 1.3rem;
                                    color: var(--nown-primaria);
                                    margin-bottom: 0.25rem;
                                }
                                
                                .preco-desconto {
                                    font-size: 0.8rem;
                                    color: #4CAF50;
                                    font-weight: 600;
                                    margin: 0;
                                }
                                
                                .frete-radio {
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    border: 2px solid #ddd;
                                    position: relative;
                                    transition: all 0.3s ease;
                                    margin-top: 0.5rem;
                                    margin-left: auto;
                                    
                                    &.checked {
                                        border-color: #FF5722;
                                        background: #FF5722;
                                        
                                        &:after {
                                            content: '';
                                            position: absolute;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                            width: 8px;
                                            height: 8px;
                                            background: white;
                                            border-radius: 50%;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        #secao-resumo {
            .resumo-container {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                position: sticky;
                top: 2rem;
                
                .resumo-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                    
                    .resumo-icon {
                        width: 50px;
                        height: 50px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, var(--nown-primaria), var(--nown-primaria-darker));
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 1rem;
                        
                        i {
                            color: white;
                            font-size: 1.5rem;
                        }
                    }
                    
                    h2 {
                        font-weight: 800;
                        color: #2c3e50;
                        margin: 0;
                        font-size: 1.5rem;
                    }
                }
                
                .produtos-resumo {
                    margin-bottom: 1.5rem;
                    
                    .produto-item {
                        display: flex;
                        align-items: center;
                        padding: 1rem;
                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.02), rgba(0, 0, 128, 0.02));
                        border-radius: 12px;
                        margin-bottom: 0.75rem;
                        
                        .produto-img {
                            width: 60px;
                            height: 60px;
                            border-radius: 10px;
                            object-fit: cover;
                            margin-right: 1rem;
                        }
                        
                        .produto-info {
                            flex: 1;
                            
                            .produto-nome {
                                font-weight: 600;
                                color: #2c3e50;
                                margin-bottom: 0.25rem;
                                font-size: 0.95rem;
                            }
                            
                            .produto-detalhes {
                                color: #6c757d;
                                font-size: 0.85rem;
                                margin: 0;
                            }
                        }
                        
                        .produto-preco {
                            font-weight: 700;
                            color: var(--nown-primaria);
                            font-size: 1rem;
                        }
                    }
                }
                
                .resumo-valores {
                    padding: 1rem;
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                    border-radius: 12px;
                    margin-bottom: 1.5rem;
                    
                    .valor-linha {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 0.75rem;
                        
                        &:last-child {
                            margin-bottom: 0;
                            padding-top: 0.75rem;
                            border-top: 1px solid rgba(236, 1, 102, 0.2);
                            
                            .valor-label {
                                font-weight: 800;
                                font-size: 1.1rem;
                                color: #2c3e50;
                            }
                            
                            .valor-preco {
                                font-weight: 800;
                                font-size: 1.3rem;
                                color: var(--nown-primaria);
                            }
                        }
                        
                        .valor-label {
                            color: #6c757d;
                            font-size: 0.9rem;
                            font-weight: 600;
                        }
                        
                        .valor-preco {
                            color: #2c3e50;
                            font-weight: 600;
                            font-size: 0.95rem;
                        }
                    }
                }
                
                .resumo-actions {
                    .btn-finalizar {
                        width: 100%;
                        padding: 1rem;
                        border-radius: 15px;
                        font-weight: 700;
                        font-size: 1.1rem;
                        transition: all 0.3s ease;
                        margin-bottom: 1rem;
                        
                        &:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 25px rgba(236, 1, 102, 0.4);
                        }
                    }
                    
                    .btn-continuar {
                        width: 100%;
                        padding: 0.75rem;
                        border-radius: 12px;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        
                        &:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                        }
                    }
                }
            }
        }
        
        .modal {
            .modal-dialog {
                .modal-content {
                    border-radius: 20px;
                    border: none;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                    
                    .modal-header {
                        border-bottom: 2px solid rgba(236, 1, 102, 0.1);
                        padding: 1.5rem;
                        
                        .modal-title {
                            font-weight: 800;
                            color: #2c3e50;
                            display: flex;
                            align-items: center;
                            
                            i {
                                margin-right: 0.75rem;
                                color: var(--nown-primaria);
                                font-size: 1.3rem;
                            }
                        }
                        
                        .btn-close {
                            background: none;
                            border: none;
                            font-size: 1.5rem;
                            color: #6c757d;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                color: var(--nown-primaria);
                                transform: scale(1.1);
                            }
                        }
                    }
                    
                    .modal-body {
                        padding: 2rem 1.5rem;
                        
                        .form-group {
                            margin-bottom: 1.5rem;
                            
                            label {
                                font-weight: 600;
                                color: #2c3e50;
                                margin-bottom: 0.5rem;
                                font-size: 0.9rem;
                            }
                            
                            .form-control {
                                border: 2px solid #e9ecef;
                                border-radius: 10px;
                                padding: 0.75rem 1rem;
                                transition: all 0.3s ease;
                                font-size: 0.95rem;
                                
                                &:focus {
                                    border-color: var(--nown-primaria);
                                    box-shadow: 0 0 0 0.2rem rgba(236, 1, 102, 0.15);
                                }
                            }
                        }
                    }
                    
                    .modal-footer {
                        border-top: 2px solid rgba(236, 1, 102, 0.1);
                        padding: 1.5rem;
                        
                        .btn {
                            border-radius: 12px;
                            font-weight: 600;
                            padding: 0.75rem 1.5rem;
                            transition: all 0.3s ease;
                            
                            &:hover {
                                transform: translateY(-2px);
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        body.light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        body.dark {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            
            #secao-endereco {
                .endereco-container {
                    background: linear-gradient(135deg, rgba(236, 1, 102, 0.1) 0%, rgba(0, 0, 128, 0.1) 100%);
                    
                    .endereco-header {
                        .header-info {
                            .header-title {
                                h2 {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                    }
                    
                    .enderecos-lista {
                        .endereco-item {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .endereco-content {
                                .endereco-info {
                                    .endereco-detalhes {
                                        .endereco-linha1 {
                                            color: var(--nown-terciaria);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            #secao-frete {
                .frete-container {
                    background: linear-gradient(135deg, rgba(0, 0, 128, 0.1) 0%, rgba(236, 1, 102, 0.1) 100%);
                    
                    .frete-header {
                        .frete-title {
                            h2 {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                    
                    .frete-opcoes {
                        .frete-item {
                            background: #2d2d2d;
                            border-color: #444;
                            
                            .frete-content {
                                .frete-info {
                                    .frete-detalhes {
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
            
            #secao-resumo {
                .resumo-container {
                    background: #2d2d2d;
                    
                    .resumo-header {
                        h2 {
                            color: var(--nown-terciaria);
                        }
                    }
                    
                    .produtos-resumo {
                        .produto-item {
                            background: linear-gradient(135deg, rgba(236, 1, 102, 0.05), rgba(0, 0, 128, 0.05));
                            
                            .produto-info {
                                .produto-nome {
                                    color: var(--nown-terciaria);
                                }
                            }
                        }
                    }
                    
                    .resumo-valores {
                        background: linear-gradient(135deg, rgba(236, 1, 102, 0.08), rgba(0, 0, 128, 0.08));
                        
                        .valor-linha {
                            &:last-child {
                                .valor-label {
                                    color: var(--nown-terciaria);
                                }
                            }
                            
                            .valor-preco {
                                color: var(--nown-terciaria);
                            }
                        }
                    }
                }
            }
            
            .modal {
                .modal-dialog {
                    .modal-content {
                        background: #2d2d2d;
                        
                        .modal-header {
                            .modal-title {
                                color: var(--nown-terciaria);
                            }
                        }
                        
                        .modal-body {
                            .form-group {
                                label {
                                    color: var(--nown-terciaria);
                                }
                                
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
                }
            }
            
            .page-header {
                .subtitle {
                    color: #aaa;
                }
            }
        }
    </style>
    <div class="container-nown py-4" id="fretePage">
        <!-- Header da Página -->
        <div class="page-header">
            <h1 class="text-center"><i class="bi bi-cart-check me-3"></i>Quase lá ...</h1>
            <p class="subtitle">Confirme o endereço de entrega e escolha a melhor opção de frete</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Seção de Endereço -->
                <section id="secao-endereco">
                    <div class="endereco-container animate-fade-in">
                        <div class="endereco-header">
                            <div class="header-info">
                                <div class="header-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="header-title">
                                    <h2>Endereço de Entrega</h2>
                                    <p>Selecione onde você quer receber seu pedido</p>
                                </div>
                            </div>
                            <button class="btn btn-n-primaria btn-novo-endereco" data-bs-toggle="modal" data-bs-target="#modalNovoEndereco">
                                <i class="bi bi-plus-circle me-2"></i>Novo Endereço
                            </button>
                        </div>
                        
                        <div class="enderecos-lista" id="enderecos-lista">
              
                            
            
                        </div>
                    </div>
                </section>

                <!-- Seção de Frete -->
                <section id="secao-frete">
                    <div class="frete-container animate-fade-in">
                        <div class="frete-header">
                            <div class="frete-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="frete-title">
                                <h2>Opções de Frete</h2>
                                <p>Escolha a melhor forma de receber seu pedido</p>
                            </div>
                        </div>
                        
                        <div class="frete-opcoes">
                            <div class="frete-item selected" data-frete="1">
                                <div class="frete-content">
                                    <div class="frete-info">
                                        <div class="frete-logo">
                                            <i class="bi bi-lightning"></i>
                                        </div>
                                        <div class="frete-detalhes">
                                            <div class="frete-nome">Frete Expresso</div>
                                            <div class="frete-prazo">Entrega em 1-2 dias úteis</div>
                                            <div class="frete-descricao">Ideal para compras urgentes</div>
                                        </div>
                                    </div>
                                    <div class="frete-preco">
                                        <div class="preco-valor">R$ 24,90</div>
                                        <div class="preco-desconto">Frete rápido</div>
                                        <div class="frete-radio checked"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="frete-item" data-frete="2">
                                <div class="frete-content">
                                    <div class="frete-info">
                                        <div class="frete-logo">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="frete-detalhes">
                                            <div class="frete-nome">Frete Padrão</div>
                                            <div class="frete-prazo">Entrega em 3-5 dias úteis</div>
                                            <div class="frete-descricao">Boa relação custo-benefício</div>
                                        </div>
                                    </div>
                                    <div class="frete-preco">
                                        <div class="preco-valor">R$ 12,90</div>
                                        <div class="preco-desconto">Mais econômico</div>
                                        <div class="frete-radio"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="frete-item" data-frete="3">
                                <div class="frete-content">
                                    <div class="frete-info">
                                        <div class="frete-logo">
                                            <i class="bi bi-gift"></i>
                                        </div>
                                        <div class="frete-detalhes">
                                            <div class="frete-nome">Frete Grátis</div>
                                            <div class="frete-prazo">Entrega em 5-7 dias úteis</div>
                                            <div class="frete-descricao">Para compras acima de R$ 200</div>
                                        </div>
                                    </div>
                                    <div class="frete-preco">
                                        <div class="preco-valor">Grátis</div>
                                        <div class="preco-desconto">Economia total</div>
                                        <div class="frete-radio"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
            <div class="col-lg-4">
                <!-- Seção de Resumo -->
                <section id="secao-resumo">
                    <div class="resumo-container animate-fade-in">
                        <div class="resumo-header">
                            <div class="resumo-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h2>Resumo do Pedido</h2>
                        </div>
                        
                        <div class="produtos-resumo">
                            <div class="produto-item">
                                <img src="https://placehold.co/60x60" alt="Produto" class="produto-img">
                                <div class="produto-info">
                                    <div class="produto-nome">Smartphone Galaxy S24 Ultra</div>
                                    <div class="produto-detalhes">Qtd: 1 • Cor: Preto</div>
                                </div>
                                <div class="produto-preco">R$ 4.599,00</div>
                            </div>
                            
                            <div class="produto-item">
                                <img src="https://placehold.co/60x60" alt="Produto" class="produto-img">
                                <div class="produto-info">
                                    <div class="produto-nome">Capa Protetora Premium</div>
                                    <div class="produto-detalhes">Qtd: 1 • Cor: Transparente</div>
                                </div>
                                <div class="produto-preco">R$ 89,90</div>
                            </div>
                        </div>
                        
                        <div class="resumo-valores">
                            <div class="valor-linha">
                                <span class="valor-label">Subtotal (2 itens)</span>
                                <span class="valor-preco">R$ 4.688,90</span>
                            </div>
                            <div class="valor-linha">
                                <span class="valor-label">Frete</span>
                                <span class="valor-preco" id="valor-frete">R$ 24,90</span>
                            </div>
                            <div class="valor-linha">
                                <span class="valor-label">Desconto</span>
                                <span class="valor-preco" style="color: #4CAF50;">-R$ 50,00</span>
                            </div>
                            <div class="valor-linha">
                                <span class="valor-label">Total</span>
                                <span class="valor-preco" id="valor-total">R$ 4.663,80</span>
                            </div>
                        </div>
                        
                        <div class="resumo-actions">
                            <button class="btn btn-n-primaria btn-finalizar">
                                <i class="bi bi-credit-card me-2"></i>Ir para Pagamento
                            </button>
                            <button class="btn btn-outline-secondary btn-continuar">
                                <i class="bi bi-arrow-left me-2"></i>Continuar Comprando
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Modal Novo Endereço -->
    <div class="modal fade" id="modalNovoEndereco" tabindex="-1" aria-labelledby="modalNovoEnderecoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoEnderecoLabel">
                        <i class="bi bi-plus-circle"></i>Adicionar Novo Endereço
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formNovoEndereco">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipoEndereco">Tipo de Endereço</label>
                                    <select class="form-control" id="tipoEndereco" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="casa">Casa</option>
                                        <option value="trabalho">Trabalho</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cep">CEP</label>
                                    <input type="text" class="form-control" id="cep" placeholder="00000-000" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="logradouro">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro" placeholder="Rua, Avenida, etc." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="numero">Número</label>
                                    <input type="text" class="form-control" id="numero" placeholder="123" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="complemento">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" placeholder="Apto, Bloco, etc.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bairro">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" placeholder="Nome do bairro" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cidade">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" placeholder="Nome da cidade" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select class="form-control" id="estado" required>
                                        <option value="">Selecione o estado</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="MG">Minas Gerais</option>
                                        <!-- Adicionar outros estados conforme necessário -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="referencia">Ponto de Referência</label>
                            <input type="text" class="form-control" id="referencia" placeholder="Ex: Próximo ao shopping, em frente à farmácia">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-n-primaria" id="btnSalvarEndereco">
                        <i class="bi bi-check-circle me-2"></i>Salvar Endereço
                    </button>
                </div>
            </div>
        </div>
    </div>

    
+
