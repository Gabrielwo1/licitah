<style>
    .politicas{
        h1, h2, h3 {
        color: var(--nown-primaria);
        margin-bottom: 10px;
    }
    
    h1 {
        font-size: 2.5em;
        margin-top: 0;
    }
    
    h2 {
    font-size: 1.5em;
    font-weight: 500;
}

    h3 {
    font-size: 1.5em;
}

    p {
    margin: 15px 0;
    text-align: justify;
}

    ul {
    margin: 15px 0;
    padding-left: 20px;
}

    li {
    margin-bottom: 5px;
}

    a {
    color: #007bff;
    text-decoration: none;
}

    a:hover {
    text-decoration: underline;
}

    }
</style>

<?

function pegaPoliticaPersonalizada($id) {
    $conn = conn(); 
    $seleciona = "SELECT * FROM politicas WHERE politica_id='$id'";
    
    $resultado = $conn->query($seleciona); 

    if ($resultado === false || $resultado->num_rows == 0) {
        return false;
    }


    $dado = $resultado->fetch_assoc();

    return [
        "titulo" => $dado["politica_titulo"], 
        "texto" => $dado["politica_texto"]
    ];
}


function pegaTermo($titulo, $termo, $default = ""){
    if(v(["politicas", $termo, "ativar"], false)){
        if(v(["politicas", $termo, "personalizar"], false) && v(["politicas", $termo, "personalizada"], false)){
            $personalizada = pegaPoliticaPersonalizada(v(["politicas", $termo, "personalizada"], false));
            
            if($personalizada){
                $titulo = $personalizada["titulo"];
                $default = $personalizada["texto"];
            }
            
            
        }
        $empresa = "";
        $nomeEmpresa = "";
        $emailEmpresa = "";
        if(v(["geral", "empresa", "nome"], false)){
            $empresa = '<span class="fw-700 fs-20">'.v(["geral", "empresa", "nome"], false).'</span>';
            $nomeEmpresa = v(["geral", "empresa", "nome"], false);
        }
        
        if(v(["geral", "empresa", "email"], false)){
            $emailEmpresa = v(["geral", "empresa", "email"], false);
        }
        
        if(!$default){
            if(file_exists(__DIR__."/politicas/".$termo.".php")){
                ob_start();
                include(__DIR__."/politicas/".$termo.".php");
                $default = ob_get_clean(); 
            }
        }
        
        
        
        
         $url = SETUP["dominio"]."politicas";
        echo '
        <div class="container politicas" style="max-width: 800px; margin: auto">
        <h1 class="fs-26 fw-700 mt-4 text-primaria">'.$titulo.'</h1>
        <a href="'.$url.'" class="text-decoration-none fw-700 fs-20">Politicas '.$empresa.'</a>
        
        '.$default.'
        ';
        echo '</div>';
    }
}


 $lista = [
            "termos-e-condicoes"=>["titulo"=>"Termos e Condições", "link"=>"termos-e-condicoes"],
            "privacidade"=>["titulo"=>"Politica de Privacidade", "link"=>"politica-de-privacidade"],
            "cookies"=>["titulo"=>"Politica de Cookies", "link"=>"politica-de-cookies"],
            "devolucao-e-reembolso"=>["titulo"=>"Politica de Devolução e Reenbolso", "link"=>"politica-de-devolucao-e-reembolso"],
            "privacidade-para-criancas"=>["titulo"=>"Politica de Privacidade para Crianças", "link"=>"politica-de-privacidade-para-criancas"],
            "avisos-legais"=>["titulo"=>"Avisos Legais", "link"=>"avisos-legais"], 
            "politica-anti-spam"=>["titulo"=>"Politica Anti-spam", "link"=>"politica-anti-spam"],
            "seguranca-da-informacao"=>["titulo"=>"Politica de Segurança da Informação", "link"=>"politica-de-seguranca-da-informacao"],
        ];

switch(count($this->caminho)){
    case 1:
         $empresa = "";
        if(v(["geral", "empresa", "nome"], false)){
            $empresa = '<span class="fw-700 fs-20 d-block mb-4">'.v(["geral", "empresa", "nome"], false).'</span>';
        }
        echo '<div class="container politicas"><h1 class="my-4">Politicas do Site</h1>'.$empresa.'<div class="row g-3">';
        foreach($lista as $chave=>$item){
            if(v(["politicas", $item["link"], "ativar"], false)){
                $url = SETUP["dominio"]."politicas/".$chave;
                 echo '
                 <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <a href="'.$url.'" class="stretched-link text-decoration-none">
                            <h2 class="text-center m-0 fw-500">'.$item["titulo"].'</h2>
                            </a>
                        </div>
                    </div>
                 </div>';
            }
        }
        echo '</div></div>';
        break;
    case 2:
        if(isset($lista[$this->caminho[1]])){
            pegaTermo($lista[$this->caminho[1]]["titulo"] ,  $lista[$this->caminho[1]]["link"]);
        }
     
        break;
    default:
        break;
    
}

?>