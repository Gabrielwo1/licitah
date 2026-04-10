<div class="card mt-5 card-nown">
    <div class="card-head p-3 border-bottom d-flex justify-content-between align-items-center">
        <h2 class="text-primaria fs-16 fw-900">Categorias</h2>
        <div class="fs-12 corDestaque2">Limpar</div>
    </div>
    <div class="card-body">
        <p>Escolha quais assuntos você quer ver e clique no botão para abrir as últimas notícias.</p>
        <?
        
        $i = 0;
        
        while($i < 10){
            ?>
                <a class="btn rounded-pill border-secondary corDestaque2 m-1">Categoria <?=($i + 1) ?></a>
            <?
            
            $i++;
        }
        ?>
    </div>
    <div class="card-footer bg-transparent">
        <a class="btn rounded-pill bgCorDestaque d-block text-primaria">Ver Todas</a>
    </div>
    
</div>