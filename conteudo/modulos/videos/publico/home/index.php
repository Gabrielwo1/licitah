<style>
    #drop {
    display: none;
}

#procurar:focus-within + #drop {
    display: block;
}


      .header-grupo {
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0, 0, 150, 0.09);
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
    content: "";
    display: block;
    width: 1px;
    height: 30px;
    background: rgba(0, 0, 0, 0.15);
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
    color: rgba(0, 0, 0, 0.35);
    position: relative;
    transition: 0.27s ease;
}
.grupo-main-nav .grupo-nav-item.active,
.grupo-main-nav .grupo-nav-item:hover {
    color: black;
}
.grupo-main-nav .grupo-nav-item:after {
    content: "";
    display: block;
    width: 100%;
    height: 5px;
    position: absolute;
    bottom: 0;
    left: 0;
    background: var(--nown-primaria);
    opacity: 0;
    transition: 0.27s ease;
}
.grupo-main-nav .grupo-nav-item.active:after,
.grupo-main-nav .grupo-nav-item:hover:after {
    opacity: 1;
}
.header-grupo-capa {
    position: relative;
}
body.dark .header-grupo {
    background: #212529;
}
body.dark .header-grupo-capa:before {
    content: "";
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2));
}
body.dark .grupo-avatar {
    background: #212529;
}
body.dark .grupo-nome h6 {
    color: rgba(255, 255, 255, 0.5);
}
body.dark .grupo-ib span {
    color: rgba(255, 255, 255, 0.7);
}
body.dark .grupo-main-nav .grupo-nav-item {
    color: rgba(255, 255, 255, 0.3);
}
body.dark .grupo-main-nav .grupo-nav-item.active,
body.dark .grupo-main-nav .grupo-nav-item:hover {
    color: white;
}
.header-grupo img {
    pointer-events: none;
}
@media (max-width: 991px) {
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
    .grupo-main-nav::-webkit-scrollbar-track,
    .grupo-main-nav::-webkit-scrollbar {
        background: transparent !important;
    }
    .grupo-main-nav::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: rgba(0, 0, 0, 0.1);
        width: 3px !important;
        height: 3px !important;
    }
    .dark .grupo-main-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
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
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px !important;
        margin: 0 10px !important;
    }
    .dark .grupo-main-nav .grupo-nav-item.active {
        background: rgba(255, 255, 255, 0.3);
    }
}

</style>




<div class="container-fluid">
    <div style="max-width: 800px" class="m-auto d-flex justify-content-between gap-2 mt-2">
        <div class="form-control rounded-pill d-flex justify-content-between p-0">
            <div class="d-flex align-items-center he-40 flex-fill position-relative">
                <input class="h-100 d-flex align-items-center border-0 w-100 ps-4" style="border-radius: 50px 00rem 0rem 50px" type="search" placeholder="Pesquisar" id="procurar">
                <div class="position-absolute w-100 py-1 top-100 bottom-0" style="z-index:5" id="drop">
                    <div class="card">
                        <div class="card-body px-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>esquerda</div>
                                        <div>direita</div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action">A second item</li>
                                <li class="list-group-item list-group-item-action">A third item</li>
                                <li class="list-group-item list-group-item-action">A fourth item</li>
                                <li class="list-group-item list-group-item-action">And a fifth one</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-dark he-40 wi-75" style="border-radius: 0px 50rem 50rem 0px"><i class="bi bi-search"></i></button>
        </div>
        <button class="btn he-40 wi-40 rounded-circle btn-dark d-flex justify-content-center align-items-center">
            <i class="bi bi-mic-fill"></i>
        </button>
    </div>
</div>



<div class="container-fluid mt-4">
    <div style="max-width: 1400px" class="m-auto">
        <div class="row g-2" id="gridVideos">
        <?
        $i = 0;
        while($i < 12){ 
            echo '<div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 p-md-2 preLoad">';
            include __DIR__."/../componentes/card.php";
            echo '</div>';
            $i++;
        }
        
        ?>
    </div>
    </div>
</div>

