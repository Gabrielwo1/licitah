<?

function categorizador($tipo){
    $idControl = geraId();
    return '
    <div class="card border-0 shadow">
     <div class="card-body" data-bs-toggle="collapse" data-bs-target="#'.$idControl.'" aria-expanded="true" aria-controls="'.$idControl.'">
      <div class="d-flex justify-content-between align-items-center">
            <h3 class="fs-16 text-uppercase m-0 fw-700 text-contrast">Categoria</h3>
            <button class="btn btn-sm seta"><i class="bi bi-caret-down-fill text-contrast"></i></button>
      </div>
   </div>
             <div class="collapse show" id="'.$idControl.'">
             <div class="card-body border-top ">
             <select class="form-select" id="pluginCategorizador" data-tipo="'.$tipo.'" name="categoria">
             <option value selected disabled>Selecione a categoria</option>>Selecione a categoria</option>
                
            </select>
            </div>
            <div class="variavel">
            </div>
            <div class="card-body border-top">
            <div class="d-flex justify-content-between align-items-center">
                <div></div>
                <div><button class="btn btn-primary btn-sm nova">Adicionar Nova</button></div>
            </div>
            </div>
            </div>
    </div>
    ';
}

?>

