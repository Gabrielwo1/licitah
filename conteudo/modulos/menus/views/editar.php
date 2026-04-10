<?

$input = new Input("TEXTAREA");
$input->set("name", "estrutura");
echo '<div class="d-none">';
echo $input->html();
echo '</div>';
$config = [
    "titulo"=>"Editar Menu",
    "btns"=>[
        ["texto"=>"Todos os Menus", "info"=>"a/jogadores/novo"],
        ]
    ];
$cabecalho = new Cabecalho($config);
echo $cabecalho->html();


$items = [
    ["titulo"=>"Link Interno"], 
    ["titulo"=>"Link Externo"],
    ["titulo"=>"Separador"],
    ];

$menu = "";
$t = 0;
foreach($items as $item){
    $id = geraId();
    $t++;
    $menu .= '
    <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed text-uppercase fs-14 fw-500" type="button" data-bs-toggle="collapse" data-bs-target="#'.$id.'" aria-expanded="false" aria-controls="'.$id.'">
        '.$item["titulo"].'
      </button>
    </h2>
    <div id="'.$id.'" class="accordion-collapse collapse" data-bs-parent="#itensMenu">
      <div class="accordion-body">
          <div>
              <label>URL</label>
              <input class="form-control link">
          </div>
           <div class="mt-2">
              <label>Texto do Link</label>
              <input class="form-control texto">
          </div>
      </div>
      <div class="accordion-body bg-light">
          <div class="d-flex justify-content-end">
              <button class="btn btn-contrast novo-item" data-t="'.$t.'">Adicionar Item</button>
          </div>
       
      </div>
    </div>
  </div>
    
    
    
    ';
}

$input = new Input("input");
$input->set("name", "titulo");


?>

<style>
    .filhos{
        display: flex;
    }
    
    .filhos .filhos{
        display: none;
    }
</style>
<div>
    <?
    echo $input->html();
    ?>
</div>

<div class="row mt-4">
    <div class="col-12 col-lg-4">
        <div class="accordion" id="itensMenu">
            <?
            echo $menu;
            ?>
        </div>
        
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
           
            <div class="card-body">
                <div id="listaItens" class="d-flex flex-column gap-2">
                    
                </div>
            </div>
         
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <button id="deletar" class="btn text-danger">Deletar Menu</button>
                    <button id="salvar" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="setupMenu" tabindex="-1"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-20 m-0 text-uppercase">Editar Menu</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div>
              <label class="form-label fs-14 fw-500 text-uppercase">URL</label>
              <input class="form-control" name="url">
          </div>
           <div>
              <label class="form-label fs-14 fw-500 text-uppercase mt-3">Texto do Link</label>
                <input class="form-control" name="texto">
          </div>
           <div>
              <label  class="form-label fs-14 fw-500 text-uppercase mt-3">Vizibilidade</label>
                <select class="form-select" name="vizibilidade" id="listadeFuncoes">
                    <option value="0">Mostrar para todos</option>
                    <option value="1">Mostrar apenas para</option>
                    <option value="2">Esconder apenas para</option>
                </select>
            </div>
            <div class="row">
                <div class="col-6">
                      <div>
              <label class="form-label fs-14 fw-500 text-uppercase mt-3">Etiqueta</label>
                <input class="form-control" name="etiqueta">
          </div>
                </div>
                <div class="col-6">
                      <div>
              <label class="form-label fs-14 fw-500 text-uppercase mt-3">Icone</label>
                <input class="form-control" name="icone">
          </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex justify-content-between w-100">
            <div>
                 <button type="button" class="btn text-danger" id="deletarItem">Deletar</button>
            </div>
            <div>
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary btnSalvar">Salvar Item</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>