<div class="container">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-18 m-0">Categorias</h1>
            <div>
                <div class="position-relative">
                    <input class="form-control" style="padding-left: 45px" placeholder="Procurar" type="search">
                    <span class="position-absolute position-absolute top-50  translate-middle-y" style="left: 20px"><i class="bi bi-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <?
        $i = 0;
        while($i < 30){
            ?>
            <div class="col-6 col-lg-3">
            <div class="card card-nown">
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <div class="d-flex align-items-center justify-content-center">
                            <h2 class="m-0 fs-18 text-center">Ola mundo da terra como vai</h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="#" class="text-primaria stretched-link text-decoration-none">Acessar</a>
                    </div>

                </div>
            </div>
        </div>
            <?
            $i++;
        }
        
        ?>
    </div>
</div>