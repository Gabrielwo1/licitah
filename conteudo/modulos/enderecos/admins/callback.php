<?


function call_salvo($cb, $obj){
    if(isset($cb["sucesso"])){

        if($obj->mode == "novo" && $obj->identificador == "AsoYNiHaX4s4dzTkiy5XCIoR9eX4nkAM"){
            include __DIR__."/endereco.php";
            $endereco = new Endereco($obj->data["Eklk25LiGs3EQomkq46v1K8PvnR0ix"]);
            $endereco->api($obj->novoId); 
        }

    }
   
    return $cb;
}

?>