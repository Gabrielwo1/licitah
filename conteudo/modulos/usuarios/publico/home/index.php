<?
   if(!verModulo("paginas", "diretoriopublico", false)){
       return;
   }
   ?>
<div>
   <style>.cardGrupo{ .imgGrupo{ border: white 5px solid; box-shadow: 0 6px 21px 0 rgba(0, 0, 0, .12); &:after{ content: url(https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/themes/cirkle/assets/img/chat_round_shape5.png); position: absolute; top:50%; left: 50%; transform: translate(-50%, -50%); } } .member-thumb { margin-left: -60px; list-style: disc; padding-left: 20px; text-align: center; li { display: inline-block; margin-right: -20px; cursor: pointer; border: 2px solid #fff; border-radius: 50%; img{ border-radius: 50%; height: 35px; width: 35px; }
      }
      }
      } 
   </style>
   <div class="container">
      <div class="d-flex flex-column gap-3">
         <div class="card card-nown" style="background-image: linear-gradient(to right, #ff9800, #ffea00);">
            <div class="card-body p-0">
               <div class="row">
                  <div class="col-6 p-5">
                     <div class="h-100 w-100 d-flex justify-content-center flex-column">
                        <h1 class="text-light">Usuários</h1>
                        <span class="fw-700 fs-18 text-light fw-700" id="tamanhoTotal">25 grupos</span> 
                     </div>
                  </div>
                  <div class="col-6 background" style="background-image: url(https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/2021/05/shape_7-1.png)"> <img loading="lazy" src="https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/2021/05/people_2.png"> </div>
               </div>
            </div>
         </div>
         <div class="card card-nown">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div>
                     <div class="position-relative"> <input class="form-control ps-5" placeholder="Procurar Grupos" id="inputProcurar"> <span class="position-absolute top-50 translate-middle-y" style="left: 20px"> <i class="bi bi-search"></i> </span> </div>
                  </div>
                  <div class="d-flex justify-content-end gap-2 align-items-center">
                     <div>
                        <select class="form-select form-select-sm" id="filtroGrupo">
                           <option value="novos">Novos Grupos</option>
                           <option value="populares">Mais Populares</option>
                           <option value="antigos">Mais Antigos</option>
                           <option value="meus">Meus Grupos</option>
                        </select>
                     </div>
                     <div> <button class="btn btn-n-primaria">Criar Grupo</button> </div>
                  </div>
               </div>
            </div>
         </div>
         <div>
            <div class="row g-3 row-usuarios">
           
           
            </div>
            <div class="d-flex justify-content-end">
               <div>
                  <div id="navarea">
                     <nav class="mt-2"></nav>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

