<?

function boxsalvar($modulo){
    if(defined('EDITA') && EDITA == true){
        $btn = '<button class="btn btn-primary btn-sm publicar" data-acao="2">Atualizar</button>';
    }else{
        $btn = '<button class="btn btn-primary btn-sm publicar" data-acao="1">Publicar</button>';
    }
    
    return '
    <div class="card border-0 mb-4 shadow" id="cardSalvar" data-modulo="'.$modulo.'">
   <div class="card-body" data-bs-toggle="collapse" data-bs-target="#cardSalvarBox" aria-expanded="true" aria-controls="cardSalvarBox">
      <div class="d-flex justify-content-between align-items-center">
            <h3 class="fs-16 text-uppercase m-0 fw-700 text-contrast">Salvar</h3>
            <button class="btn btn-sm seta"><i class="bi bi-caret-down-fill text-contrast"></i></button>
      </div>
   </div>
   <div class="collapse show" id="cardSalvarBox">
      <div class="card-body border-top ">
         <div class="list-group  list-group-flush bg-contrast">
            <li href="#" class="list-group-item bg-contrast text-contrast">
               <div class="d-flex justify-content-between align-items-center">
                  <div>
                     <span><i class="bi bi-globe-americas fs-12"></i></span>
                     <span class="ms-2 fs-12 text-uppercase">Status:</span>
                     <span class="fw-700 fs-12 text-uppercase setup">Publicado</span>
                  </div>
                  <button class="btn text-contrast editar"><i class="bi bi-pencil-square fs-12"></i></button>
               </div>
               <div class="d-flex justify-content-between content d-none">
                  <div>
                     <select class="form-select form-select-sm status">
                        <option value="Publicado">PUBLICADO</option>
                        <option value="Rascunho">RASCUNHO</option>
                     </select>
                  </div>
                  <div><button class="btn btn-primary w-100 btn-sm ok">OK</button></div>
               </div>
            </li>
            <li href="#" class="list-group-item bg-contrast text-contrast">
               <div class="d-flex justify-content-between align-items-center">
                  <div>
                     <span><i class="bi bi-eye fs-12"></i></span>
                     <span class="ms-2 fs-12 text-uppercase">Vizibilidade:</span>
                     <span class="fw-700 fs-12 text-uppercase setup">Todos</span>
                  </div>
                  <button class="btn text-contrast editar"><i class="bi bi-pencil-square fs-12"></i></button>
               </div>
               <div class="d-flex justify-content-between content d-none">
                  <div>
                     <select class="form-select form-select-sm vizibilidade">
                        <option value="Todos">TODOS</option>
                        <option value="Logados">LOGADOS</option>
                        <option value="Deslogados">DESLOGADOS</option>
                        <option value="Premium">PREMIUM</option>
                        <option value="Grátis">GRÁTIS</option>
                     </select>
                  </div>
                  <div><button class="btn btn-primary w-100 btn-sm ok">OK</button></div>
               </div>
            </li>
            <li href="#" class="list-group-item bg-contrast text-contrast">
               <div class="d-flex justify-content-between align-items-center">
                  <div>
                     <span><i class="bi bi-calendar fs-12"></i></span>
                     <span class="ms-2 fs-12 text-uppercase">Data:</span>
                     <span class="fw-700 fs-12 text-uppercase setup">Imediatamente</span>
                  </div>
                  <button class="btn text-contrast editar"><i class="bi bi-pencil-square fs-12"></i></button>
               </div>
               <div class="content d-none">
                  <input class="form-control form-control-sm data" type="date"  min="'.date('Y-m-d').'"  value="'.date('Y-m-d').'">
                  <input class="form-control form-control-sm mt-3 horario" type="time">
                  <div class="d-flex justify-content-end mt-3"><div><button class="btn btn-primary w-100 btn-sm ok">OK</button></div></div>
               </div>
            </li>
         </div>
      </div>
      <div class="card-body">
         <div class="d-flex justify-content-end">
            '.$btn.'
         </div>
      </div>
   </div>
</div>
    ';
}

?>

