<?

function geraId(){
    $caracteres = "abcdefghijlmnopqrstuvxz";
    $i = 0;
    $final = "";
    while ($i < 10){
        $final .= $caracteres[rand(0, 22)];
        $i++;
    }

    return $final;
}
?>