<?

class TyperDoc{
    public $tipo;
    function __construct($tipo){
        echo '<div id="typerDoc" data-tipo="'.$tipo.'" class="d-none"></div>';
    }
}


?>