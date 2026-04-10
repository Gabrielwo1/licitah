<?
class PaginaTabela{
    public $id;
    
    function __construct($id){
        $this->id = $id;
    }
    
    function load(){
        $div = "";
        
        $i = 0;
        while($i < 12){
            $div .= '<div class="bg-carregando" style="height:50px"></div>';
            $i++;
        }
        
        return '<div class="d-flex flex-column justify-content-between gap-2">'.$div.'</div>';
        
    }
    
    
    
    function html(){
        $id = $this->id;
        return '

        <div class="card mt-4 border-0 shadow">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><input class="form-control form-control-sm"></div>
                <div class="d-flex justify-content-end  align-items-center gap-2">
                    <button class="btn btn-primario fs-12 fw-500 d-flex justify-content-center align-items-center gap-2"><span class="material-symbols-outlined" style="font-size: 14px">move_group</span> Exportar</button>
                    <button class="btn btn-primario fs-12 fw-500 d-flex justify-content-center align-items-center gap-2"><span class="material-symbols-outlined" style="font-size: 14px">filter_alt</span> Filtrar</button>
                    <button class="btn btn-primario fs-12 fw-500 d-flex justify-content-center align-items-center gap-2"><span class="material-symbols-outlined" style="font-size: 14px">add_circle</span> Adicioanr Novo</button>
                </div>
            </div>
            <div class="card-body">
                <div id="preLoad"> 
                    '.$this->load().'
                </div>
                <div class="d-none tabelaLoad text-contrast" id="'.geraId().'" data-filtro="'.$id.'">
                   
                </div>
            </div>
        </div>';
        
    }
}

?>