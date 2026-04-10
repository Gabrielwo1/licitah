<div class="container d-flex flex-column gap-4" id="listaDosMeus">

<div class="row">
    <div class="col-12">
        <div id="sistema"  class="card card-nown bg-primaria" style="background-repeat: no-repeat; background-size: contain; background-position-x: right; background-color: #F1416C;background-image: url(https://preview.keenthemes.com/metronic8/demo1/assets/media/patterns/vector-1.png); border-radius: 20px;}">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                     <h2 class="fs-40 mb-0 text-white">NOWN 1001</h2>
                     <h3 class="fs-40 fw-700 m-0 text-white" id="versaoSistema"><div class="bg-carregando wi-75 he-50"></div></h3>
                </div>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
    <div class="col-3">
        
    </div>
    <div class="col-3">
        
    </div>
</div>



<div class="d-flex align-items-center justify-content-between">
    <h2 class="fs-22 text-uppercase fw-700 m-0 text-contrast">Meus Módulos</h2>
<div class="d-flex gap-2">
    <div class="d-flex gap-2 align-items-center">
        <div class="position-relative">
             <span class="position-absolute top-50 translate-middle-y" style="left: 20px"><i class="bi bi-search"></i></span>
             <input  class="form-control fuzzy-search rounded-pill" placeholder="Procurar Módulo" type="search" style="padding-left: 45px">
        </div>
        <div>
            <div class="dropdown">
  <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
       <i class="bi bi-three-dots-vertical"></i>
  </button>
  <ul class="dropdown-menu dropdown-nown dropdown-menu-end">
    <li><button class="dropdown-item" id="ativarTudo">Ativar Todos</button></li>
    <li><button class="dropdown-item" id="desativarTudo">Desativar Todos</button></li>
  </ul>
</div>


         
        </div>
 
</div>

   
</div>
</div>

 <div class="row list g-4" id="meuModulos">
        <?
        $i = 0;
        while($i < 8){
            echo '<div class="col-12 col-lg-6 col-xl-3">
   <div class="card border-0 shadow h-100 position-relative modulo">
      <div class="card-body d-flex flex-column justify-content-between">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="m-0"><div class="bg-carregando he-20 wi-150"></div></h2>
            <div><div class="bg-carregando he-20 wi-50"></div></div>
         </div>
         <div class="text-center my-2"><div class="bg-carregando he-150 wi-150 m-auto"></div></div>
         <div class="px-4 my-2">
            <div class="bg-carregando he-20 mb-2"></div>
            <div class="bg-carregando he-20 mb-2"></div>
         </div>
         <div class="d-flex justify-content-center gap-2 aling-items-center">
      
               <div class="wi-50 he-40 bg-carregando">
               </div>
             <div class="wi-100 he-40 bg-carregando">
               </div>
         </div>
      </div>
   </div>
</div>';
            
            $i++;
        }
        
        ?>
    </div>


<h2 class="fs-22 text-uppercase fw-700 mt-4 text-contrast">Módulos Disponíveis para Download</h2>
<div>
    <div class="row g-4" id="disponiveis">

    </div>
</div>
</div>