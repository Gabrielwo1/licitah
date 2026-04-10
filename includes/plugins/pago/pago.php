<?
class Pago{
    public $nome;
    public $virtual;
    public $estoque;
    public $recorrente;
    public $tipo;
    public $item1;
    public $item2;
    
    function __construct($nome, $tipo){
        $this->nome = $nome;
        $this->tipo = $tipo;
        
        
        /*
        
        voltar um array, sendo o primeiro valor nativo e o segundos e é flexivel ou não
        */
        
        $this->virtual = [false, false];
        $this->estoque = [false, false];
        $this->recorrente = [false, true];
    }
    
    function set($variavel, $valor){
        $this->$variavel = $valor;
    }
    
    
    function config($array = []){
        foreach($array as $chave=>$valor){
            $this->$chave = $valor;
        }
    }
    
    function select($nome, $classe, $item1 , $item2 , $selecionado){
        $selected = intval($selecionado) == 0 ? 'selected' : '';
        $selected2 = intval($selecionado) == 1 ? 'selected' : '';
        
        return '<div class="my-2">
                    <label class="form-label">'.$nome.'</label>
                    <select class="form-select status" data-classe="'.$classe.'">
                        <option value="0" '.$selected.'>'.$item1.'</option>
                        <option value="1" '.$selected2.'>'.$item2.'</option>
                    </select>
                    <div class="variaveis '.$classe.'">
                    </div>
                </div>';
    }
    
    function render(){
        $virtual = $this->virtual[0];
        $estoque = $this->estoque[0];
        $recorrente = $this->recorrente[0];
        
        $selectVirtual = $this->virtual[1] ? $this->select('Selecione se é produto físico ou digital ?', 'virtual', 'Digital' , 'Fisico', $virtual ) : '';
            
        $selectEstoque = $this->estoque[1] ?  $this->select('Selecione se o produto é finito ?', 'estoque',  'Não', "O estoque é contado", $estoque) : '';
        
        $selectRecorrente = $this->recorrente[1] ? $this->select('Selecione se a forma de pagamento é recorrente?', 'recorrente', 'Não', "Sim", $recorrente ) : '';

        return '
        <div id="itemPago"   data-virtual="'.$virtual.'" data-estoque="'.$estoque.'" data-recorrente="'.$recorrente.'" data-tipo="'.$this->tipo.'">
            <div class="card">
                <div class="card-header">
                    <h3 class="fs-16 fw-500 text-uppercase mb-0">'.$this->nome.' produto</h3>
                </div>
                <div class="card-body">
                    <div class="my-2">
                        <label class="form-label">Preço</label>
                        <select class="form-select status" data-classe="preco">
                            <option value="0">Grátis</option>
                            <option value="1">Pago</option>
                        </select>
                        <div class="variaveis preco">
                        </div>
                    </div>
                    
                    '.$selectVirtual. 
                    $selectEstoque. 
                    $selectRecorrente . '
                    

                </div>
            </div>
        
        </div>';
    }
}

?>