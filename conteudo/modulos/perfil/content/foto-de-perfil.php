
<style>
   #imgPerfil {
  position: relative; 
 
}

#imgPerfil:hover::after {
  content: ''; 
  position: absolute;
  width: 100%;
  height: 100%; 
  background-color: red;
  opacity: 0.3;
  border-radius: 50%;
}
</style>

<div>
    <div class="img-thumbnail">
        <div class="bg-secondary ratio ratio-16x9" id="capa">
         <div class="d-flex justify-content-end p-2">
             <div>
                 <button id="btnCapa" class="btn btn-light wi-50 he-50 d-flex justify-content-center align-items-center"><i class="bi bi-camera"></i></button>
                 <input type="file" class="d-none" id="fotoCapa" >
             </div>
         </div>
    </div>
    </div>
    
    <div class="position-relative">
        <div class="img-thumbnail he-250 wi-250 position-absolute top-100 start-50 translate-middle rounded-circle">
            <button class="btn bg-warning w-100 h-100 rounded-circle d-flex justify-content-center align-items-center" id="imgPerfil">
                
            </button>
            <input type="file" class="d-none"  id="fotoPerfil">
        </div>
    </div>
    <div>
        <div class="he-150">
            
        </div>
        <div class="text-center">
            <button class="btn btn-n-primaria" id="salvar">Salvar</button>
      
        </div>
    </div>
</div>

