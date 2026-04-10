<?

/* 


## ASSOCIAÇÕES ##

Usuario para Usuário 
Usuário para Empresa
Usuário para Endereço

Empresa para Endereço
Empresa para Empresa


## TIPOS ##

*/

class Endereco{
    function __construct(){
        
    }
    
    function render(){
        switch($this->tipo){
            case 'vinculo':
                break;
            case 'desvinculo':
                break;
            case 'setDefault':
                break;
        }
    }
}

class Empresa{
    function __construct(){
        
    }
    
    function render(){
        switch($this->tipo){
            case 'vincular':
                break;
            case 'desvincular':
                break;
        }
    }
}

class Usuario{
    function __construct(){
        
    }
    
    function render(){
        switch($this->tipo){
            case 'pedido':
                break;
            case 'amizade':
                break;
            case 'cancelarPedido':
                break;
            case 'bloqueio':
                break;
            case 'desloqueio':
                break;
            }
    }
}



class Conexoes{
    public $acao;
    public $tipo;
    public $pai;
    public $filho;
    function __construct(){
        $this->acao = $_POST["acao"] ?? false;
        
        $this->pai = $_POST["pai"] ?? false;
        $this->filho = $_POST["filho"] ?? false;
        
        $this->tipo = $_POST["tipo"] ?? false;
        $this->foco = $_POST["foco"] ?? false;
        
      
        $this->chave = $chave;
    }
    
    
    function setIds($pai, $filho){
        $this->pai = $pai ?? false;
        $this->filho = $filho ?? false;
    }
    
    function setup($acao, $tipo, $foco){
        $this->acao = $acao;
        $this->tipo = $tipo;
        $this->foco = $foco;
    }
    
    

    function render(){
        if(!$this->pai || !$this->filho){
            return ["erro"=>true, "mensagem"=>"Não foram enviados IDs para vinculo"];
        }
        
        if(!$this->foco){
            return ["erro"=>true, "mensagem"=>"Não foi definido um foco válido"];
        }
        
        
        switch($acao){
            case 'usuario':
                $usuario = new Usuario();
                return $usuario->render();

                break;
            case 'empresa':
                $empresa = new Empresa();
                return $empresa->render();
                break;
            case 'endereco':
                $endereco = new Endereco();
                return $endereco->render();
                break;
            default:
                return ["erro"=>true, "mensagem"=>"Não foi enviado um vinculo válido"];
                break;
        }
    }
}

?>