<?
$resposta = ["sucesso"=>true, "lista"=>[
    ["t"=>"teste", "v"=>2],
    ["t"=>"cachorro", "v"=>3, "c"=>true],
    ["t"=>"valado", "v"=>5],
    ]];

echo json_encode($resposta);

?>