<div class="container-fluid">
    <div style="max-width: 800px" class="m-auto d-flex justify-content-between gap-2">
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