<?
function visibilidade($tipo, $regra) {
    $v = $tipo ?? 0;
    $v = intval($v);



    $me = [];
    $estado = isset($_SESSION["id"]) && isset($_SESSION["funcao"]) ? "logados" : "deslogado";
    $me[] = $estado;

    if ($estado == "logados") {
        $me[] = $_SESSION["funcao"];

        if (intval($_SESSION["funcao"]) === 0) {
            $me[] = "1";
        }

        $me[] = $_SESSION["tipouser"];
    }

    switch ($v) {
        case 0:
            return true;
        case 1:
            if (!is_array($regra)) {
                return true;
            }

            $commonItems = array_intersect($me, $regra);
            return !empty($commonItems);
        case 2:
            if (!is_array($regra)) {
                return false;
            }

            $commonItems = array_intersect($me, $regra);
            return empty($commonItems);
        default:
            echo "Cai no default do menu";
            break;
    }
}

function pegaEspaco($id){
    if(!v(["rodape", "espaco-".$id ,"conteudo"], false)){
        return;
    }
    
    $titulo = "";
    $barra = "";
    $conteudo = "";
    if(v(["rodape", "espaco-".$id ,"titulo"], false) && trim(v(["rodape", "espaco-".$id ,"titulo"], ""))){
        $titulo = '<h2 class="fs-16 fw-700 m-0" style="line-height: 16px;">'.trim(v(["rodape", "espaco-".$id ,"titulo"], "")).'</h2>';
    }
    
    
    if(v(["rodape", "espaco-".$id ,"divisor"], false)){
        $barra = '<div class="bg-primaria w-50 my-2" style="height: 4px;"></div>';
    }
    
    
    
    switch(intval(v(["rodape", "espaco-".$id ,"conteudo"], 1))){
        case 1:
           
            $conteudo =   '<p class="m-0">'.trim(v(["rodape", "espaco-".$id ,"texto"], "")).'</p>';
            break;
        case 2:
            $lis = [];
            if(v(["rodape", "espaco-".$id ,"menu"], false)){
                 $conteudo =  '<div class="menu"></div>';
                 $menus = pegaMenuId(v(["rodape", "espaco-".$id ,"menu"], false));
                 
                 foreach($menus as $menu){

                     if(visibilidade($menu["visibilidade"] ?? 0,  $menu["selecionados"] ?? [])){
                        $icone = isset($menu["icone"]) && $menu["icone"] && trim($menu["icone"]) ? '<span><i class="'.trim($menu["icone"]).'"></i></span>' : '';
                        
                        $link = $menu["link"];
                        if (strpos($link, "https://") === 0 || strpos($link, "http://") === 0) {} else {
                            $link = SETUP["dominio"].$link;
                        }

                        
                        
                        array_push($lis, '
                        <li class="list-group-item bg-transparent px-0">
                        <a href="'.$link.'" class="text-decoration-none d-flex gap-2 align-items-center text-contrast">'.$icone.'<span>'.$menu["nome"].'</span></a>
                        </li>');
                        
               
                        
                     }
                 }
                 
                 
            }
            if(!empty($lis)){
                $html = implode("", $lis);
                $conteudo = '
                    <div>
                        <ul class="list-group list-group-flush">
                            '.$html.'
                        </ul>
                    </div>';
            }
            
            
            
            
            break;
        case 3:
            if(v(["rodape", "espaco-".$id ,"imagem"], false)){
                
                $altura = "";
                if(v(["rodape", "espaco-".$id ,"altura"], false)){
                    $alt = intval(v(["rodape", "espaco-".$id ,"altura"], 1));
                    $altura = 'style="height: '.$alt.'px"';
                }else{
                    $altura = 'class="w-100"';
                }
                
                $obj = json_decode(v(["rodape", "espaco-".$id ,"imagem"], false), true);
                if(!empty($obj)){
                    $imagem = $obj[0];
                    $url = SETUP["dominio"]."/conteudo/uploads/".$imagem;
                    $conteudo = '<div><img src="'.$url.'"  '.$altura.'></div>';
                }
            }
            break;
    }
    
 
    
   
    
    $div = '<div class="d-flex flex-column gap-2">'.$titulo.$barra.$conteudo.'</div>';
    
    return $div;
}

if(isset($_SESSION["id"])){
    $chave = "logado";
}else{
    $chave = "deslogados";
}


if(v(["rodape", $chave ,"ativar"], false) || v(["rodape", "barra-inferior", "barra"], false)){
    
    $barraTopo = "";
    $barraFooter = "";
    $barra = "";
    if(v(["rodape", "barra-inferior", "barra"], false)){
        
        
        $itens = ["ano", "copyright", "nome", "cnpj", "personalizar", "link"];
        $htmls = [];
        foreach( $itens as $item){
            if(v(["rodape", "barra-inferior", $item], false)){
                switch($item){
                    case 'ano':
                        $formatter = new IntlDateFormatter(
                            'pt_BR',
                            IntlDateFormatter::FULL,
                            IntlDateFormatter::NONE,
                            'America/Sao_Paulo',
                            IntlDateFormatter::GREGORIAN,
                            "'<span class=\"d-none d-xl-inline\">'d 'de' MMMM 'de</span>' yyyy"
                            );
                            
                            array_push($htmls, '<span class="fw-700">' . $formatter->format(new DateTime()) . '</span>');

                        break;
                    case 'copyright':
                        array_push($htmls, '<span class="fw-700"><span class="d-none d-xl-inline">Copyright</span> ©</span>');
                        break;
                    case 'nome':
                        if(v(["geral", "empresa", "nome"], false) && trim(v(["geral", "empresa", "nome"], false))){
                             array_push($htmls, '<span class="fw-700">'.trim(v(["geral", "empresa", "nome"], false)).'</span>');
                        }
                        break;
                    case 'cnpj':
                        if(v(["geral", "empresa", "cnpj"], false) && trim(v(["geral", "empresa", "cnpj"], false))){
                             array_push($htmls, '<span class="fw-700"><span class="d-none d-xl-inline">CNPJ</span> '.trim(v(["geral", "empresa", "cnpj"], false)).'</span>');
                        }
                        break;
                    case 'link':
                        if(v(["rodape", "barra-inferior", "textolink"], false) && v(["rodape", "barra-inferior", "linkurl"], false)){
                            array_push($htmls, '<span class="fw-bold"><a class="linkColor" href="'.v(["rodape", "barra-inferior", "linkurl"], "#").'">'.v(["rodape", "barra-inferior", "textolink"], false).'</a></span>');
                        }
                        break;
                }
                
            }
        }
        
        if(!empty($htmls)){
            $justificar = v(["rodape", "barra-inferior", "justificar"], "start");
            $htmls = implode(" ", $htmls);
                 $barra = '
        <div class="card-body fs-12 p-1">
            <div class="container d-flex algin-items- gap-3 justify-content-'.$justificar.'">'.$htmls.'</div>
        </div>';
        }
   
    }
    
    if(v(["rodape", "barra-inferior", "posicao"], false)){
        $barraTopo = $barra;
    }else{
        $barraFooter = $barra;
    }
    
    
    
    $valores = [];
    if(v(["rodape", $chave, "personalizar"], false)){
         $bg = v(["rodape", $chave ,"background"] , false);
         $txt = v(["rodape", $chave ,"texto"] , false);
         
         if($bg){
             array_push($valores, "background-color: $bg");
         }
         
         if($txt){
              array_push($valores, "color: $txt");
         }

    }
    $valores = implode(" ; " , $valores);
    
   
    ?>
    <style>
    #conteudo{
        padding-bottom: 0px;
    }
    </style>
    <div class="card mt-5 py-3 rounded-0 border-0" id="rodapeDinamico">
        <?
        echo $barraTopo;
        if(v(["rodape", $chave ,"ativar"], false)){
            ?>
             <div class="card-body" style="<?=$valores?>" >
            <div class="container">
            <div class="row g-3">
                <?
            foreach([1,2,3,4] as $item){
                if(v(["rodape", $chave ,"posicao".$item], false)){
                    $mobile = "d-block";
                    $pc = "d-xl-block";
                    
                     if(v(["rodape", $chave ,"hidemobile".$item], false)){
                         $mobile = "d-none";
                     }
                     
                     if(v(["rodape", $chave ,"hidepc".$item], false)){
                         $pc = "d-xl-none";
                     }
               
                    $size = v(["rodape", $chave ,"tamanho".$item], 3);
                    ?>
                    
                    <div class="col-12 col-xl-<?=$size?> <?=$mobile?> <?=$pc?>">
                        <?=
                        pegaEspaco(v(["rodape", $chave ,"area".$item], 1));
                        ?>
                    </div>
                    <?
                }
            }
            ?>
            </div>
        </div>
        </div>
            
            <?
        }
        echo $barraFooter;
        ?>
    </div>
    <?
}

?>