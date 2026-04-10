<style>
    .header-grupo {
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,150,0.09);
    background: white;
    margin-bottom: 30px;
}

.header-grupo .grupo-capa {
    width: 100% !important;
    aspect-ratio: 13 / 4;
    object-fit: cover;
    background: #eaeaea;
    border-radius: 20px 20px 0 0;
}

.header-grupo-info {
    padding: 10px 15px;
}

.grupo-header-nome {
    display: flex;
    align-items: center;
    gap: 0;
}

.grupo-avatar {
    margin-top: -85px;
    background: white;
    padding: 10px;
    border-radius: 10px;
    margin-right: 15px;
    position: relative;
}

.grupo-avatar.grupo-avatar-hex {
    mask-image: url(/conteudo/modulos/rede-social/media/hexagon-fill.svg);
    mask-repeat: no-repeat;
    mask-size: cover;
    mask-position: center;
    padding: 10px;
    margin-right: 0;
}

.grupo-avatar.grupo-avatar-circle {
    border-radius: 50%;
}

.grupo-avatar img {
    width: 190px;
    height: 190px;
    object-fit: cover;
    background: #eaeaea;
    border-radius: 10px;
}

.grupo-avatar.grupo-avatar-hex img {
    mask-image: url(/conteudo/modulos/rede-social/media/hexagon-fill.svg);
    mask-repeat: no-repeat;
    mask-size: cover;
    mask-position: center;
}

.grupo-avatar.grupo-avatar-circle img {
    border-radius: 50%;
}

.grupo-nome h3 {
    font-weight: 700;
    font-size: 25px;
}

.grupo-nome h6 {
    font-size: 15px;
    font-weight: 500;
    text-transform: uppercase;
    color: #666;
}

.grupo-header-dados {
    display: flex;
    justify-content: end;
    gap: 30px;
    align-items: center;
}

.grupo-dados-box {
    min-width: 100px;
}

.grupo-ib {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 15px;
    position: relative;
}

.grupo-ib span {
    display: block;
    font-size: 25px;
    font-weight: bold;
    text-align: center;
    color: #555;
    line-height: 1 !important;
}

.grupo-ib h5 {
    margin: 0 !important;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 500;
    letter-spacing: 1px;
}

.grupo-ib:after {
    content: '';
    display: block;
    width: 1px;
    height: 30px;
    background: rgba(0,0,0,0.15);
    position: absolute;
    top: calc(50% - 15px);
    right: -15px;
}

.grupo-dados-box:nth-of-type(3) .grupo-ib:after {
    display: none !important;
}

.grupo-dados-box:nth-of-type(3) {
    margin-right: -15px;
}

.grupo-acoes {
    display: flex;
    gap: 10px;
}

.grupo-acoes .btn {
    font-size: 18px;
    font-weight: 600;
    height: 100%;
}

.grupo-acoes .dropdown-toggle::after {
    display: none !important;
}

.card-grupo-nav {
    margin-bottom: 30px;
    padding: 0 30px;
}

.grupo-main-nav {
    display: flex;
}

.grupo-main-nav .grupo-nav-item {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 35px;
    font-size: 27px;
    color: rgba(0,0,0,0.35);
    position: relative;
    transition: .27s ease;
}

.grupo-main-nav .grupo-nav-item.active, .grupo-main-nav .grupo-nav-item:hover {
    color: black;
}

.grupo-main-nav .grupo-nav-item:after {
    content: '';
    display: block;
    width: 100%;
    height: 5px;
    position: absolute;
    bottom: 0;
    left: 0;
    background: var(--nown-primaria);
    opacity: 0;
    transition: .27s ease;
}

.grupo-main-nav .grupo-nav-item.active:after, .grupo-main-nav  .grupo-nav-item:hover:after {
    opacity: 1;
}

.header-grupo-capa {
    position: relative;
}

body.dark .header-grupo {
    background: #212529;
}

body.dark .header-grupo-capa:before {
    content: '';
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-image: linear-gradient(0deg, rgba(0,0,0,0.4), rgba(0,0,0,0.2));
}

body.dark .grupo-avatar {
    background: #212529;
}

body.dark .grupo-nome h6 {
    color: rgba(255,255,255,0.5);
}

body.dark .grupo-ib span {
    color: rgba(255,255,255,0.7);
}

body.dark .grupo-main-nav .grupo-nav-item {
    color: rgba(255,255,255,0.3);
}

body.dark .grupo-main-nav .grupo-nav-item.active, body.dark .grupo-main-nav .grupo-nav-item:hover {
    color: white;
}

.header-grupo img {
    pointer-events: none;
}

@media (max-width: 991px){
    .header-grupo {
        margin: 0 -20px 30px;
    }
    .grupo-header-nome {
        flex-direction: column;
        margin-bottom: 30px;
    }
    .grupo-nome * {
        text-align: center;
    }
    .grupo-header-dados {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    .grupo-dados-box:last-of-type {
        width: 100%;
    }
    .grupo-acoes {
        justify-content: center;
        margin-bottom: 30px;
    }
    .grupo-ib {
        gap: 10px;
        margin-bottom: 30px;
    }
    .grupo-ib:after {
        left: -5px;
    }
    .grupo-ib span {
        font-size: 20px;
    }
    
    .margin-adjust-mobile {
        margin: 0 -30px;
    }
    .card-grupo-nav {
        overflow: hidden;
        padding: 0 !important;
    }
    
    .card-grupo-nav .grupo-main-nav {
        overflow-x: auto;
        padding: 10px !important;
    }
    
    .grupo-main-nav::-webkit-scrollbar-track, .grupo-main-nav::-webkit-scrollbar
    {
    	background: transparent !important;
    }
    
    .grupo-main-nav::-webkit-scrollbar-thumb
    {
    	border-radius: 10px;
    	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    	background-color: rgba(0,0,0,0.1);
    	width: 3px !important;
    	height: 3px !important;
    }
    
    .dark .grupo-main-nav::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
    }
    
    .grupo-main-nav::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    
    .grupo-main-nav .grupo-nav-item:after {
        display: none !important;
    }
    
    .grupo-main-nav .grupo-nav-item {
        border: none !important;
        padding: 10px 30px !important;
        font-size: 20px !important;
    }
    
    .grupo-main-nav .grupo-nav-item.active {
        background: rgba(0,0,0,0.1);
        border-radius: 10px !important;
        margin: 0 10px !important;
    }
    
    .dark .grupo-main-nav .grupo-nav-item.active {
        background: rgba(255,255,255,0.3);
    }
}
</style>

<div id="itemGrupo">
    <div class="container-fluid">
        <div class="container">
            <div class="header-grupo">
                <div class="header-grupo-capa">
                    <img src="https://fortram.site/placeholder/1300x400" class="grupo-capa">
                </div>
                <div class="header-grupo-info">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="grupo-header-nome">
                                <div class="grupo-avatar grupo-avatar-hex">
                                    <img src="https://fortram.site/placeholder/200x200">
                                </div>
                                <div class="grupo-nome">
                                    <h3>Nome da Empresa</h3>
                                    <h6>Razão Social</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="grupo-header-dados">
                                <div class="grupo-dados-box">
                                    <div class="grupo-ib">
                                        <span>sdfgfgdfgd</span>
                                        <h5>CNPJ</h5>
                                    </div>
                                </div>
                                <div class="grupo-dados-box">
                                    <div class="grupo-ib">
                                        <span>3789</span>
                                        <h5>FUNCIONÁRIOS</h5>
                                    </div>
                                </div>
                                <div class="grupo-dados-box">
                                    <div class="grupo-ib">
                                        <span>899</span>
                                        <h5>SITUAÇÃO</h5>
                                    </div>
                                </div>
                                <div class="grupo-dados-box">
                                    <div class="grupo-acoes">
                                        <button class="btn btn-nown-style btn-n-primaria"><i class="icon-btn-nown bi bi-person-add"></i><span>Funcionários</span></button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="container">
            <div class="row margin-adjust-mobile">
                <div class="col-lg-12">
                    <div class="card card-nown card-grupo-nav">
                        <div class="grupo-main-nav">
                            <button data-url="informacoes" disabled  class=" btn grupo-nav-item">
                                <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/informacoes.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="dados" disabled  class=" btn grupo-nav-item">
                                <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/list.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="redes-sociais" disabled  class=" btn grupo-nav-item">
                                <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/share.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="endereco" disabled  class=" btn grupo-nav-item">
                                 <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/map.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="socios" disabled class=" btn grupo-nav-item">
                                 <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/handsshake.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="atividades" disabled class=" btn grupo-nav-item">
                                <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/maleta.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                            <button  data-url="galeria" disabled class=" btn grupo-nav-item">
                                <lord-icon src="<?= SETUP['dominio'] ?>conteudo/modulos/empresas/imagens/image.json" trigger="hover" delay="500" style="width:50px;height:50px"></lord-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dados-de-insercao">
                dsad
            </div>
        </div>
    </div>
</div>

