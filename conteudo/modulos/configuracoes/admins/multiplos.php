<?
session_start();
if(empty($_SESSION["id"])){
    return;
}
$modulos = scandir(__DIR__."/../../");
$lista = [];
foreach($modulos as $modulo){
    if($modulo != "." && $modulo != ".."){
        if(file_exists(__DIR__."/../../".$modulo."/manifest.json")){
        
            $conteudo = json_decode(file_get_contents(__DIR__."/../../".$modulo."/manifest.json"), true);

            if(isset($conteudo["multiconta"]) && $conteudo["multiconta"]){

                $lista[] = ["t"=>$conteudo["name"], "v"=>$modulo];
            }
        }
    }
}

echo json_encode(["sucesso"=>true, "lista"=>$lista], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

?>