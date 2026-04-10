<?
$paises = file_get_contents("https://restcountries.com/v3.1/all?fields=name,flags");
$array = json_decode($paises, true);

echo json_encode(["sucesso"=>true, "paises"=>$array]);

?>