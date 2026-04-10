<div class="card mt-5 card-nown">
    <div class="card-head p-3 border-bottom">
        <h2 class="text-primaria fs-16 fw-900 m-0">Mais Lidas</h2>
    </div>
    <div class="card-body">
        <?
        $i = 0;
        
        while($i < 5){
            
        ?>
        <div class="card border-start-0 border-end-0 border-top-0 rounded-0 py-4">
            <div class="row">
                <div class="col-1 align-self-center">
                    <div class="text-primaria fs-20"><?= ($i + 1) ?></div>
                </div>
                <div class="col-10">
                    <h3 class="fs-16 fw-900">
                        Maior contrabandista de armas da América Latina, Diego Dirísio é preso na Argentina
                    </h3>
                </div>
                <div class="col-1">
                    
                </div>
            </div>
        </div>
        <?
        $i ++;
        }
        ?>
    </div>
</div>