<?
class Upload{
        private $multiple;
        private $accept;
        private $textoUpload;
        private $pasta;
        private $titulo;
        private $mensagem;
        private $layout;
        private $id;
        
    function __construct($array = []){
        $this->multiple = false;
        $this->accept = "image/*";
        $this->textoUpload = "Imagem Destacada";
        $this->pasta = "base";
        $this->titulo = false;
        $this->mensagem = false;
        $this->layout = 1;
        
        
        foreach($array as $chave=>$item){
            $this->$chave = $item;
        }
    }
    
    
    function html(){
        


        
        if($this->multiple){
            $botao = '<div class="col-4 d-flex align-items-center">
            <label class="w-100 d-block">
                    <input type="file" class="d-none fileupload" accept="'.$this->accept.'" data-pasta="'.$this->pasta.'" multiple>
                    <div class="d-flex justify-content-center align-items-center bg-light w-100 h-100"><i class="bi bi-plus-square-dotted text-contrast"></i></div>
                </label>
                   
                </div>';
        }else{
            $botao ='<div class="col-12">
                       <label class="d-block">
                            <input type="file" class="d-none fileupload" accept="'.$this->accept.'"   data-pasta="'.$this->pasta.'" id="'.$this->id.'">
                            <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/svg/files/blank-image.svg" class="w-100">
                      </label>
                    </div>';
        }
        
        
        $idControl = geraId();
        $titulo = isset($this->titulo) && $this->titulo ? $this->titulo : "";
        $mensagem = isset($this->mensagem) && $this->mensagem ? '<div class="fs-12 text-center">'.$this->mensagem.'</div>' : '';
        if($this->layout == 1){
             return '
        <style>
    .deletaMidia{
        display: none;
    }
    
    .itemPrevia:hover .deletaMidia{
        display: flex;
    }
</style>
         <div class="card mb-4 border-0 shadow">
          <div class="card-body" data-bs-toggle="collapse" data-bs-target="#'.$idControl.'" aria-expanded="true" aria-controls="'.$idControl.'">
      <div class="d-flex justify-content-between align-items-center">
            <h3 class="fs-16 text-uppercase m-0 fw-700 text-contrast"> '.$titulo.'</h3>
            <button class="btn btn-sm seta"><i class="bi bi-caret-down-fill text-contrast"></i></button>
      </div>
   </div>
                   
                   <div class="collapse show" id="'.$idControl.'">
                   
                   
                    <div class="card-body border-top ">
                         <form method="post" enctype="multipart/form-data">
        <div class="uploader">
            <div class="row previa ratio ratio-1x1 m-0">
                '.$botao.'
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div></div>
                <button class="btn text-link" type="button">Biblioteca</button>
            </div>
            '.$mensagem.'
        </div>
        </form>
                    </div>
                    </div>
                </div>
                
                
       ';
        }
        else{
             return '
        <style>
    .deletaMidia{
        display: none;
    }
    
    .itemPrevia:hover .deletaMidia{
        display: flex;
    }
</style>
         <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="uploader">
                        <div class="row previa">'.$botao.'</div>
                    </div>
                </form>
            </div>
        </div>';
        }
       
    }
}
?>