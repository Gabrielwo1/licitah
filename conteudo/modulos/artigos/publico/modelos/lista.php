<style>
.artigo-topo {
    &.estilo-1 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transform: scale(1);
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 20px;
                color: white;
                font-size: 14px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                & > *:not(:last-child):after {
                    content: '–';
                    margin-left: 20px;
                }
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: flex;
                    align-items: center;
                    color: white;
                    text-decoration: none;
                    gap: 10px;
                    
                    img {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                    }
                    
                    &:hover {
                        .autor-nome {
                            opacity: 0.8;
                        }
                    }
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.1);
            }
        }
    }
    
    &.estilo-2 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            right: 0;
            width: 70%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transform: scale(1);
            transform-origin: center right;
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
            mask-image: linear-gradient(to right, transparent, white);
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 30px;
                color: white;
                font-size: 14px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: flex;
                    align-items: center;
                    color: white;
                    text-decoration: none;
                    gap: 10px;
                    
                    img {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                    }
                    
                    &:hover {
                        .autor-nome {
                            opacity: 0.8;
                        }
                    }
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.05);
            }
        }
        
        &.start-end, &.center-end, &.end-end {
            .thumb {
                left: 0;
                mask-image: linear-gradient(to left, transparent, white);
                transform-origin: left center;
            }
        }
    }
    
    &.estilo-3 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 30px;
                color: white;
                font-size: 19px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                .data {
                    display: none !important;
                }
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: none !important;
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.05);
            }
        }
    }
    
    &.estilo-4 {
        display: flex;
        position: relative;
        height: 100%;
        gap: 20px;
        flex-direction: row;
        align-items: center;
        
        .thumb {
            max-width: 40% !important;
            flex-grow: 1;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }
            
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .meta {
            display: flex;
            align-items: center;
            gap: 30px;
            color: white;
            font-size: 14px;
            min-height: 40px;
            position: relative;
            z-index: 3;
            
            .categoria {
                color: white;
                text-decoration: none;
                
                &:hover {
                    opacity: 0.8;
                }
            }
            
            .autor {
                display: flex;
                align-items: center;
                color: white;
                text-decoration: none;
                gap: 10px;
                
                img {
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                }
                
                &:hover {
                    .autor-nome {
                        opacity: 0.8;
                    }
                }
            }
        }
        
        &.start-start {
            flex-direction: row-reverse;
            align-items: start;
        }
        &.start-center {
            flex-direction: row-reverse;
            align-items: center;
        }
        &.start-end {
            flex-direction: row-reverse;
            align-items: end;
        }
        
        &.end-start {
            flex-direction: row;
            align-items: start;
        }
        &.end-center {
            flex-direction: row;
            align-items: center;
        }
        &.end-end {
            flex-direction: row;
            align-items: end;
        }
        
        &.bg-color:before {
            display: none;
        }
    }
    
    &:not(.show-img) {
        background: rgba(var(--bs-body-color-rgb), 0.1);
        .thumb {
            display: none;
        }
    }

    &:not(.show-data) .data, &:not(.show-categoria) .categoria, &:not(.show-autor-pic) .autor img, &:not(.show-autor) .autor {
        display: none !important;
    }
    
    &.hide-meta .meta {
        display: none !important;
    }
    
    &.start-start .info {
        justify-content: start !important;
        align-items: start !important;
    }
    
    &.start-center .info {
        justify-content: start !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.start-end .info {
        justify-content: start !important;
        align-items: end !important;
    }
    
    &.center-start .info {
        justify-content: center !important;
        align-items: start !important;
    }
    
    &.center-center .info {
        justify-content: center !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.center-end .info {
        justify-content: center !important;
        align-items: end !important;
    }
    
    &.end-start .info {
        justify-content: end !important;
        align-items: start !important;
    }
    
    &.end-center .info {
        justify-content: end !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.end-end .info {
        justify-content: end !important;
        align-items: end !important;
    }
    
    &.bg-color {
        &:before {
            content: '';
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(var(--nown-primaria-rgb), 0.3);
        }
    }
}

.controllers {
    label {
        margin-right: 15px !important;
        user-select: none;
    }
}

.artigo-card {
    padding-bottom: 30px;
    display: flex;
    gap: 20px;
    height: 100%;
    box-sizing: border-box;
    position: relative;
    
    .overlay-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: calc(100% - 30px);
    }
    
    .info {
        height: 100%;
        padding: 30px 0;
        
        .info-inside {
            display: flex;
            height: 100%;
            flex-direction: column;
            justify-content: center;
            
            h2 {
                font-size: 23px;
                transition: .27s ease;
            }
            
            p {
                font-size: 15px;
                height: 40px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            
            .meta {
                display: flex;
                gap: 5px 15px;
                flex-wrap: wrap;
                font-size: 12px;
                color: var(--bs-body-color);
                opacity: 0.9;
                margin-top: 10px;
                
                .categoria {
                    color: var(--bs-body-color);
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    color: var(--bs-body-color);
                    text-decoration: none;
                    
                    &:hover .autor-nome {
                        opacity: 0.8;
                    }
                    
                    img {
                        width: 20px;
                        height: 20px;
                        object-fit: cover;
                        border-radius: 50%;
                    }
                }
            }
        }
    }
            
    .thumb {
        min-width: 35%;
        max-width: 35%;
        border-radius: 15px;
        overflow: hidden;
        
        img {
            max-width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1);
            transition: .5s ease;
        }
    }
    
    
    
    &:not(.show-img) {
        background: rgba(var(--bs-body-color-rgb), 0.1);
        padding: 30px;
        border-radius: 30px;
        height: auto;
        margin-bottom: 30px;
        .thumb {
            display: none;
        }
    }

    &:not(.show-data) .data, &:not(.show-categoria) .categoria, &:not(.show-autor-pic) .autor img, &:not(.show-autor) .autor, &:not(.show-resumo) p.resumo {
        display: none !important;
    }
    
    &.hide-meta .meta {
        display: none !important;
    }
    
    .col-lg-6 &, .col-xl-6 &, .col-xxl-6 & {
        h2 {
            font-size: 19px !important;
        }
    }
    
    .col-lg-4 &, .col-xl-4 &, .col-xxl-4 & {
        h2 {
            font-size: 17px !important;
        }
    }
    
    &.order-x {
        flex-direction: row;
    }
    &.order-x-reverse {
        flex-direction: row-reverse;
    }
    
    &.order-y {
        gap: 30px;
        flex-direction: column;
        
        .info {
            padding: 0 !important;
        }
    }
    &.order-y-reverse {
        gap: 30px;
        flex-direction: column-reverse;
        
        .info {
            padding: 0 !important;
        }
    }
    
    &:hover {
        .info {
            h2 {
                color: var(--nown-primaria);
            }
        }
        
        .thumb {
            img {
                transform: scale(1.05);
            }
        }
    }
    
    &.order-y, &.order-y-reverse {
        .thumb {
            min-width: 100% !important;
            max-height: 400px !important;
            
            img {
                width: 100% !important;
            }
        }
        
        .info {
            height: auto !important;
        }
    }
}
</style>

<div class="container">
    <div class="row" style="height: 500px;">
        <div class="col-lg-6">
            <article class="artigo-topo estilo-1 show-img show-data show-categoria show-autor show-autor-pic center-center">
                <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>" class="thumb">
                <a href="#" class="overlay-link"></a>
                <div class="info">
                    <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                    <div class="meta">
                        <span class="data">Em 10/04/2024</span>
                        <a href="#" class="categoria">Nome da Cagetoria</a>
                        <a href="#" class="autor">
                            <img src="https://fortram.site/avatar/">
                            <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                        </a>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-6">
            <article class="artigo-topo estilo-1 show-img show-data show-categoria show-autor show-autor-pic center-center">
                <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>" class="thumb">
                <a href="#" class="overlay-link"></a>
                <div class="info">
                    <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                    <div class="meta">
                        <span class="data">Em 10/04/2024</span>
                        <a href="#" class="categoria">Nome da Cagetoria</a>
                        <a href="#" class="autor">
                            <img src="https://fortram.site/avatar/">
                            <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>
    
    <div class="controllers" data-target="artigo-topo">
        <label for="estilo-1"><input type="radio" id="estilo-1" value="estilo-1" name="estilo" checked> <code>.estilo-1</code></label>
        <label for="estilo-2"><input type="radio" id="estilo-2" value="estilo-2" name="estilo"> <code>.estilo-2</code></label>
        <label for="estilo-3"><input type="radio" id="estilo-3" value="estilo-3" name="estilo"> <code>.estilo-3</code></label>
        <label for="estilo-4"><input type="radio" id="estilo-4" value="estilo-4" name="estilo"> <code>.estilo-4</code></label>
        <hr>
        <label for="start-start"><input type="radio" id="start-start" value="start-start" name="position"> <code>.start-start</code></label>
        <label for="start-center"><input type="radio" id="start-center" value="start-center" name="position"> <code>.start-center</code></label>
        <label for="start-end"><input type="radio" id="start-end" value="start-end" name="position"> <code>.start-end</code></label>
        <label for="center-start"><input type="radio" id="center-start" value="center-start" name="position"> <code>.center-start</code></label>
        <label for="center-center"><input type="radio" id="center-center" value="center-center" name="position"> <code>.center-center</code></label>
        <label for="center-end"><input type="radio" id="center-end" value="center-end" name="position"> <code>.center-end</code></label>
        <label for="end-start"><input type="radio" id="end-start" value="end-start" name="position"> <code>.end-start</code></label>
        <label for="end-center"><input type="radio" id="end-center" value="end-center" name="position"> <code>.end-center</code></label>
        <label for="end-end"><input type="radio" id="end-end" value="end-end" name="position"> <code>.end-end</code></label>
        <hr>
        <label for="hide-meta"><input type="checkbox" id="hide-meta" value="hide-meta" name="meta"> <code>.hide-meta</code></label>
        <label for="show-data"><input type="checkbox" id="show-data" value="show-data" name="meta" checked> <code>.show-data</code></label>
        <label for="show-categoria"><input type="checkbox" id="show-categoria" value="show-categoria" name="meta" checked> <code>.show-categoria</code></label>
        <label for="show-autor-pic"><input type="checkbox" id="show-autor-pic" value="show-autor-pic" name="meta" checked> <code>.show-autor-pic</code></label>
        <label for="show-autor"><input type="checkbox" id="show-autor" value="show-autor" name="meta" checked> <code>.show-autor</code></label>
        <label for="show-img"><input type="checkbox" id="show-img" value="show-img" name="meta" checked> <code>.show-img</code></label>
        <label for="bg-color"><input type="checkbox" id="bg-color" value="bg-color" name="meta"> <code>.bg-color</code></label>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-lg-12">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-6">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-6">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-4">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-4">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-lg-4">
            <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
                <a href="#" class="overlay-link"></a>
                <div class="thumb">
                    <img src="https://source.unsplash.com/random/1200x600?v=<?=rand()?>">
                </div>
                <div class="info">
                    <div class="info-inside">
                        <h2>Lorem ipsum dolor sit amet consecteur adipiscing elit</h2>
                        <p class="resumo">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia massa a dictum lobortis. Donec malesuada laoreet nibh, sit amet pellentesque est mollis in. Pellentesque cursus mattis lectus.</p>
                        <div class="meta">
                            <span class="data">Em 10/04/2024</span>
                            <a href="#" class="categoria">Nome da Cagetoria</a>
                            <a href="#" class="autor">
                                <img src="https://fortram.site/avatar/">
                                <span class="autor-nome">Por <strong>Nome do Autor</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
    
    <div class="controllers" data-target="artigo-card">
        <label for="c-estilo-1"><input type="radio" id="c-estilo-1" value="estilo-1" name="c-estilo" checked> <code>.estilo-1</code></label>
        <label for="c-estilo-2"><input type="radio" id="c-estilo-2" value="estilo-2" name="c-estilo"> <code>.estilo-2</code></label>
        <label for="c-estilo-3"><input type="radio" id="c-estilo-3" value="estilo-3" name="c-estilo"> <code>.estilo-3</code></label>
        <label for="c-estilo-4"><input type="radio" id="c-estilo-4" value="estilo-4" name="c-estilo"> <code>.estilo-4</code></label>
        <hr>
        <label for="c-order-x"><input type="radio" id="c-order-x" value="order-x" name="c-position"> <code>.order-x</code></label>
        <label for="c-order-x-reverse"><input type="radio" id="c-order-x-reverse" value="order-x-reverse" name="c-position"> <code>.order-x-reverse</code></label>
        <label for="c-order-y"><input type="radio" id="c-order-y" value="order-y" name="c-position"> <code>.order-y</code></label>
        <label for="c-order-y-reverse"><input type="radio" id="c-order-y-reverse" value="order-y-reverse" name="c-position"> <code>.order-y-reverse</code></label>
        <hr>
        <label for="c-hide-meta"><input type="checkbox" id="c-hide-meta" value="hide-meta" name="c-meta"> <code>.hide-meta</code></label>
        <label for="c-show-data"><input type="checkbox" id="c-show-data" value="show-data" name="c-meta" checked> <code>.show-data</code></label>
        <label for="c-show-categoria"><input type="checkbox" id="c-show-categoria" value="show-categoria" name="c-meta" checked> <code>.show-categoria</code></label>
        <label for="c-show-autor-pic"><input type="checkbox" id="c-show-autor-pic" value="show-autor-pic" name="c-meta" checked> <code>.show-autor-pic</code></label>
        <label for="c-show-autor"><input type="checkbox" id="c-show-autor" value="show-autor" name="c-meta" checked> <code>.show-autor</code></label>
        <label for="c-show-img"><input type="checkbox" id="c-show-img" value="show-img" name="c-meta" checked> <code>.show-img</code></label>
        <label for="c-show-resumo"><input type="checkbox" id="c-show-resumo" value="show-resumo" name="c-meta" checked> <code>.show-resumo</code></label>
    </div>
    
</div>