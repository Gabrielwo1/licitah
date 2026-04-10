<?
function call_salvo($retorno, $obj){

    if(($obj->identificador == "6OELuNNFvlytDiCP3PLKejIFh6wT0Fq0" || $obj->identificador == "RzuUa1dDEGWGDBhJCoGDDlCrHHymzNqM" || $obj->identificador == "6OELuNNFvlytDiCP3PLKejIFh6wT0Fq0") && isset($retorno["sucesso"])){
        
        $cnpj = $obj->data["fT7mi6NlLjJY7339zrXWZeM12i25ZW"] ?? $obj->data["EBAiUe8B0ToeFHoitZBpEYkXEQARzj"];
        $cnpj = $cnpj ?? $obj->data['fT7mi6NlLjJY7339zrXWZeM12i25ZW']; 
        $id = $obj->novoId;

        include __DIR__."/empresa.php";
        $empresa = new Empresa($cnpj);
        $empresa->update($id);
        $empresa->associa($_SESSION['id']);
        
    };
    
    return $retorno;
}

?>