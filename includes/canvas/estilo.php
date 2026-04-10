<style>
.offcanvas-nown {
    .offcanvas-header {
        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
            
            .btn-oc {
                padding: 8px;
                aspect-ratio: 1 / 1;
                border-radius: 50%;
                border: none !important;
                background: none !important;
                color: rgba(var(--bs-body-color-rgb), 0.7) !important;
                font-size: 18px;
                transition: .27s ease;
                
                &:hover {
                    background: rgba(var(--bs-body-color-rgb), 0.1) !important;
                }
            }
        }
    }
    
    .offcanvas-header, .offcanvas-footer {
        box-shadow: 0 0 35px rgba(0,0,0,0.15);
    }
    
    .offcanvas-title {
        display: flex;
        align-items: center;
        gap: 10px;
        
        i {
            font-size: 26px;
            opacity: 0.8;
        }
    }
}

.notif-separator {
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 5px;
}

.card-notif + .notif-separator {
    margin-top: 15px;
}

.card-notif {
    margin: 0 -10px;
    padding: 10px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    
    .thumb {
        img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        i {
            width: 55px;
            height: 55px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            background-color: white;
            background-image: linear-gradient(to left, rgba(var(--nown-primaria-rgb), 0.15), rgba(var(--nown-primaria-rgb), 0.15));
            border-radius: 50%;
            color: var(--nown-primaria);
        }
    }
    
    .body {
        p {
            margin: 0 0 4px !important;
            font-size: 14px;
            line-height: 1.2;
            max-height: 50px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        .meta {
            display: flex;
            align-items: center;
        }
        
        .quando {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: rgba(var(--bs-body-color-rgb), 0.6);
        }
    }
    
    a {
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    &:hover {
        background: rgba(var(--bs-body-color-rgb), 0.05);
    }
    
    &.new {
        .meta {
            .quando {
                color: var(--nown-primaria);
            }
            
            .status {
                display: block;
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: var(--nown-primaria);
                margin-right: 10px;
            }
        }
    }
}

.card-chat {
    margin: 0 -10px;
    padding: 10px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    user-select: none;
    cursor: pointer;
    
    .thumb {
        position: relative;
        
        img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .status {
            display: block;
            width: 17px;
            height: 17px;
            border: 3px solid var(--bs-body-bg);
            background: #aaa;
            border-radius: 50%;
            position: absolute;
            bottom: -2px;
            right: -2px;
        }
    }
    
    .body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-grow: 1;
        
        .message {
            flex-grow: 1;
            
            h5 {
                font-size: 16px;
                margin: 0 0 5px;
            }
            
            p {
                margin: 0 0 4px !important;
                font-size: 13px;
                line-height: 1.2;
                max-height: 18px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
            }
        }
        
        .meta {
            min-width: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            
            .quando {
                font-size: 14px;
                text-align: center;
            }
            
            .count {
                display: flex;
                justify-content: center;
                align-items: center;
                background: var(--nown-primaria);
                width: 24px;
                height: 24px;
                font-size: 12px;
                font-weight: bold;
                color: white;
                border-radius: 50%;
            }
        }
        
    }
    
    &:hover {
        background: rgba(var(--bs-body-color-rgb), 0.05);
    }
    
    &.new {
        h5, p, .meta {
            font-weight: bold;
        }
        .quando {
            color: var(--nown-primaria);
        }
    }
    
    &.online .status {
        background: var(--bs-success);
    }
    
}

.offcanvas-nown {
    .oc-nown-footer {
        display: flex;
        justify-content: center;
        text-align: center;
        padding: 10px;
        box-shadow: 0 0 25px rgba(0,0,0,0.2);
        
        a {
            text-align: center;
            font-weight: bold;
            color: var(--nown-primaria);
            text-decoration: none;
        }
    }
}

.offcanvas-nown-carrinho {
    .offcanvas-body {
        background: rgba(var(--bs-body-color-rgb), 0.09);
    }
}

.cards-cart {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.card-cart {
    padding: 15px;
    background: var(--bs-body-bg);
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
    user-select: none;
    cursor: pointer;
    
    .thumb {
        position: relative;
        
        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
        }
    }
    
    .info {
        h4 {
            font-size: 17px;
            margin: 0 0 5px;
            
            .qtd {
                &:after {
                    content: '×';
                }
            }
        }
        
        .preco {
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            
            .sub {
                font-weight: bold;
            }
        }
    }
    
}

.resumo-oc-cart {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px 20px;
    
    .subtotal {
        background: rgba(var(--bs-body-color-rgb), 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        margin: 0;
        border-radius: 10px;
        
        label {
            font-weight: bold;
        }
        
        span {
            font-size: 20px;
            font-weight: bold;
            color: var(--nown-primaria-darker);
        }
    }
        
    a {
        font-weight: bold;
        text-transform: uppercase;
        font-weight: bold;
    }
}

.offcanvas-nown:has(.oc-tab-contatos){
    .offcanvas-body {
        padding: 0 !important;
    }
}

.oc-tab-contatos {
    padding: 30px;
    height: 100%;
    background: rgba(var(--bs-body-color-rgb), 0.08);
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    
    h4 {
        font-size: 18px;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .oc-contatos {
        list-style: none;
        padding: 0;
        margin: 0 0 30px;
        
        a {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            
            i {
                margin-right: 6px;
            }
        }
    }
    
    p.endereco {
        text-align: center;
        font-size: 15px;
    }
}

.oc-tab-form {
    padding: 60px 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
    
    h4 {
        font-size: 23px;
        margin-bottom: 10px;
    }
    
    p {
        margin-bottom: 40px;
    }
    
    .input-form {
        label {
            font-weight: bold;
            font-size: 14px;
        }
        
        textarea {
            height: 120px;
            resize: none !important;
        }
        
        button {
            font-weight: bold;
        }
    }
}
</style>
