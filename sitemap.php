<?php
header("Content-Type: application/xml; charset=utf-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dominio =  "https://".$_SERVER['HTTP_HOST'];

$ranqueaveis = [
    "artigos"=>["url"=>"artigos","bd"=>"artigos", "px"=>"artigo"],
    "videos"=>["url"=>"videos", "bd"=>"videos", "px"=>"video"]
    ];

$urls = [];

if(isset($_GET["grupo"])){
    $modulo = $_GET["grupo"];
    
    if(isset($ranqueaveis[$modulo])){
        $modulo = $ranqueaveis[$modulo];
        include __DIR__."/admin/conn.php";
        $conn = conn();
        $banco = $modulo["bd"];
        $prefixo = $modulo["px"];
        $link = $modulo["url"];
        $seleciona = "SELECT * FROM $banco";
        $resultado = $conn->query($seleciona);
        
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $url = $dado[$prefixo."_url"];
                $update = explode(" ", $dado[$prefixo."_update"])[0];
                array_push($urls, ["l"=>$link."/".$url, "d"=>$update]);
            }
        }
    } 
    else{
        switch($modulo){
            case "replicantes":
                break;
            case "paginas":
                break;
        }
    }
    
    
    
    
    
    
    
}else{
    foreach($ranqueaveis as $chave=>$item){
        if(is_dir(__DIR__."/conteudo/modulos/".$chave)){
            array_push($urls, ["l"=>"sitemap.php?grupo=".$chave, "d"=>"2024-01-01"]);
        }
    }
}





echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset 
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

foreach ($urls as $url) {
    echo "   <url>\n";
    echo "      <loc>" . $dominio."/".htmlspecialchars($url['l'], ENT_XML1) . "</loc>\n";
    echo "      <lastmod>" . $url['d'] . "</lastmod>\n";
    echo "   </url>\n";
}

echo '</urlset>';
?>
