 <ul class="list-group list-group-flush">
<?
$itens = ["email"=>"E-mail",
        "telefone"=>"Telefone",
        "usuario"=>"Usuário", 
        "cpf"=>"CPF"];

$mask= [0,4,0,1];
$i =0;
foreach($itens as $chave=>$item){
   
    ?>
       <li class="list-group-item py-3">
      <div class="d-block d-xl-flex justify-content-between align-items-center">
      <div>
         <div class="fw-700 fs-16"><?=$item;?></div>
         <div class="fw-500 fs-14" id="previa-<?=$chave;?>"><span class="d-block wi-150 he-20 bg-carregando"></span></div>
      </div>
      <div class="w-100 w-xl-25 mt-2 mt-xl-0">
         <button class="btn btn-n-primaria btn-nown-style btn-sm w-100" id="change<?=$chave;?>" type="button" data-bs-toggle="collapse" data-bs-target="#vital-<?=$i?>" aria-expanded="false" aria-controls="vital-<?=$i?>">Mudar <?=$item;?></button>
      </div>
      </div>
      
         <div class="collapse innerConfigVital" id="vital-<?=$i?>">
       <div class="py-3">
      <div>
          <label class="form-label fs-12">Digite seu novo <?=$item;?></label>
         <input class="form-control mascaraInput" id="input-<?=$chave;?>"  data-mascara="<?=$mask[$i];?>">
      </div>
      <div class="d-flex justify-content-between my-3">
           <button class="btn btn-danger btn-nown-style btn-sm cancelarMudanca" id="cancelaEmail">Cancelar</button>
           <button class="btn btn-n-primaria btn-nown-style btn-sm salvarMudanca" >Salvar</button>
      </div>
      <div class="alert alert-danger fs-12" role="alert"><strong>Atenção!</strong> Ao trocar o seu <?=$item;?> , você não poderá altera-lo nos próximos 30 dias.</div>
   </div>
    </div>
      
      
   </li>
    
    
    <?
     $i++;
}


?>
 

   
</ul>

