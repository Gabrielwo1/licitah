<?
function seoPlugin(){
    $idControl= geraId();
    return '
    <style>
    #seoPlugin .form-control {
  overflow: auto; /* Habilita a barra de rolagem */
  scrollbar-width: thin; /* Espessura da barra de rolagem */
  scrollbar-color: #999999 #f5f5f5; /* Cor da barra de rolagem */
}

/* Estilizando a barra de rolagem */
#seoPlugin .form-control::-webkit-scrollbar {
  width: 6px; /* Largura da barra de rolagem */
}

#seoPlugin .form-control::-webkit-scrollbar-track {
  background-color: #f5f5f5; /* Cor de fundo da barra de rolagem */
}

#seoPlugin .form-control::-webkit-scrollbar-thumb {
  background-color: #999999; /* Cor do "polegar" da barra de rolagem */
}

    </style>
    <div class="card border-0 shadow" id="seoPlugin">
     <div class="card-body" data-bs-toggle="collapse" data-bs-target="#'.$idControl.'" aria-expanded="true" aria-controls="'.$idControl.'">
      <div class="d-flex justify-content-between align-items-center">
            <h3 class="fs-16 text-uppercase m-0 fw-700 text-contrast">CONFIGURAÇÃO DE SEO</h3>
            <button class="btn btn-sm seta"><i class="bi bi-caret-down-fill text-contrast"></i></button>
      </div>
   </div>
            <div class="collapse show" id="'.$idControl.'">
          <div class="card-body border-top ">
                <div>
                  <label class="form-label">Palavra Chave</label>
                  <input class="form-control" placeholder="Digite sua palavra chave">
              </div>
              <div class="mt-3">
                  <label class="form-label">Título</label>
                  <input class="form-control rounded-0 rounded-top" placeholder="Digite seu meta title">
                 <div class="progress rounded-0" role="progressbar" aria-label="Default striped example" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="height:5px"><div class="progress-bar progress-bar-striped" style="width: 0%"></div></div>
              </div>
              <div class="mt-3">
                  <label class="form-label">Meta Descrição</label>
                  <textarea class="form-control rounded-0 rounded-top" placeholder="Digite sua meta descrição"></textarea>
                  <div class="progress rounded-0" role="progressbar" aria-label="Default striped example" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="height:5px"><div class="progress-bar progress-bar-striped" style="width: 0%"></div></div>
              </div>
              
          </div>
          <div class="card-body">
          <div class="list-group">

  
 

</div>
          </div>
          </div>
      </div>';
}

?>