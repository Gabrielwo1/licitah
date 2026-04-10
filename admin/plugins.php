<?


$dir = scandir(__DIR__."/../includes/plugins");


foreach($dir as $item){
    if($item != "." && $item != ".." && $item != "index.php"){
        if(file_exists(__DIR__."/../includes/plugins/".$item."/".$item.".php")){
            $diretorio =  __DIR__."/../includes/plugins/".$item."/".$item.".php";
       
            include $diretorio;
        }
    }
}


$dir = scandir(__DIR__."/../includes/classes");

// Verifique se o arquivo existe antes de tentar incluí-lo
foreach ($dir as $item) {
    if ($item != "." && $item != ".." && $item != "index.php") {
        $diretorio = __DIR__."/../includes/classes/".$item;

        if (file_exists($diretorio)) {
            include $diretorio;
        } 
    }
}





?>