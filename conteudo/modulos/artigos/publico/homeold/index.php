

<style>
.artigo-topo {
    &.estilo-1 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transform: scale(1);
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
            
            img{
                height: 100%;
                object-fit: cover;
                object-position: 0;
            }
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 20px;
                color: white;
                font-size: 14px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                & > *:not(:last-child):after {
                    content: '–';
                    margin-left: 20px;
                }
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: flex;
                    align-items: center;
                    color: white;
                    text-decoration: none;
                    gap: 10px;
                    
                    img {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                    }
                    
                    &:hover {
                        .autor-nome {
                            opacity: 0.8;
                        }
                    }
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.1);
            }
        }
    }
    
    &.estilo-2 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            right: 0;
            width: 70%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transform: scale(1);
            transform-origin: center right;
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
            mask-image: linear-gradient(to right, transparent, white);
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 30px;
                color: white;
                font-size: 14px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: flex;
                    align-items: center;
                    color: white;
                    text-decoration: none;
                    gap: 10px;
                    
                    img {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                    }
                    
                    &:hover {
                        .autor-nome {
                            opacity: 0.8;
                        }
                    }
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.05);
            }
        }
        
        &.start-end, &.center-end, &.end-end {
            .thumb {
                left: 0;
                mask-image: linear-gradient(to left, transparent, white);
                transform-origin: left center;
            }
        }
    }
    
    &.estilo-3 {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        height: 100%;
        background: #232323;
        
        .thumb {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            transition: .5s cubic-bezier(.7,0,0,1);
            opacity: 0.5;
        }
        
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .info {
            height: 100%;
            position: relative;
            padding: 30px;
            display: flex;
            flex-direction: column-reverse;
            justify-content: end;
            
            h2 {
                font-size: 30px;
                color: white;
                max-width: 80%;
            }
            
            .meta {
                display: flex;
                align-items: center;
                gap: 30px;
                color: white;
                font-size: 19px;
                min-height: 40px;
                position: relative;
                z-index: 3;
                
                .data {
                    display: none !important;
                }
                
                .categoria {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    display: none !important;
                }
            }
        }
        
        &:hover {
            .thumb {
                transform: scale(1.05);
            }
        }
    }
    
    &.estilo-4 {
        display: flex;
        position: relative;
        height: 100%;
        gap: 20px;
        flex-direction: row;
        align-items: center;
        
        .thumb {
            max-width: 40% !important;
            flex-grow: 1;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }
            
        .overlay-link {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 3;
        }
        
        .meta {
            display: flex;
            align-items: center;
            gap: 30px;
            color: white;
            font-size: 14px;
            min-height: 40px;
            position: relative;
            z-index: 3;
            
            .categoria {
                color: white;
                text-decoration: none;
                
                &:hover {
                    opacity: 0.8;
                }
            }
            
            .autor {
                display: flex;
                align-items: center;
                color: white;
                text-decoration: none;
                gap: 10px;
                
                img {
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                }
                
                &:hover {
                    .autor-nome {
                        opacity: 0.8;
                    }
                }
            }
        }
        
        &.start-start {
            flex-direction: row-reverse;
            align-items: start;
        }
        &.start-center {
            flex-direction: row-reverse;
            align-items: center;
        }
        &.start-end {
            flex-direction: row-reverse;
            align-items: end;
        }
        
        &.end-start {
            flex-direction: row;
            align-items: start;
        }
        &.end-center {
            flex-direction: row;
            align-items: center;
        }
        &.end-end {
            flex-direction: row;
            align-items: end;
        }
        
        &.bg-color:before {
            display: none;
        }
    }
    
    &:not(.show-img) {
        background: rgba(var(--bs-body-color-rgb), 0.1);
        .thumb {
            display: none;
        }
    }

    &:not(.show-data) .data, &:not(.show-categoria) .categoria, &:not(.show-autor-pic) .autor img, &:not(.show-autor) .autor {
        display: none !important;
    }
    
    &.hide-meta .meta {
        display: none !important;
    }
    
    &.start-start .info {
        justify-content: start !important;
        align-items: start !important;
    }
    
    &.start-center .info {
        justify-content: start !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.start-end .info {
        justify-content: start !important;
        align-items: end !important;
    }
    
    &.center-start .info {
        justify-content: center !important;
        align-items: start !important;
    }
    
    &.center-center .info {
        justify-content: center !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.center-end .info {
        justify-content: center !important;
        align-items: end !important;
    }
    
    &.end-start .info {
        justify-content: end !important;
        align-items: start !important;
    }
    
    &.end-center .info {
        justify-content: end !important;
        align-items: center !important;
        
        h2 {
            text-align: center;
        }
    }
    
    &.end-end .info {
        justify-content: end !important;
        align-items: end !important;
    }
    
    &.bg-color {
        &:before {
            content: '';
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(var(--nown-primaria-rgb), 0.3);
        }
    }
}

.controllers {
    label {
        margin-right: 15px !important;
        user-select: none;
    }
}

@media (min-width: 1200px){
    .artigo-card:after{
        position: absolute;
        content: "";
        height: 1px;
        background-color: #c8c8c8;
        width: 95%;
        bottom: 0px;
        left: 2,5%;
    }
}

.artigo-card {
    padding-bottom: 30px;
    display: flex;
    gap: 20px;
    height: 100%;
    box-sizing: border-box;
    position: relative;

    
    .overlay-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: calc(100% - 30px);
    }
    
    .info {
        height: 100%;
        padding: 0px 0;
        
        .info-inside {
            display: flex;
            height: 100%;
            flex-direction: column;
            justify-content: center;
            
            h2 {
                font-size: 23px;
                transition: .27s ease;
            }
            
            p {
                font-size: 15px;
                height: 40px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            
            .meta {
                display: flex;
                gap: 5px 15px;
                flex-wrap: wrap;
                font-size: 12px;
                color: var(--bs-body-color);
                opacity: 0.9;
                margin-top: 10px;
                
                .categoria {
                    color: var(--bs-body-color);
                    text-decoration: none;
                    
                    &:hover {
                        opacity: 0.8;
                    }
                }
                
                .autor {
                    color: var(--bs-body-color);
                    text-decoration: none;
                    
                    &:hover .autor-nome {
                        opacity: 0.8;
                    }
                    
                    img {
                        width: 20px;
                        height: 20px;
                        object-fit: cover;
                        border-radius: 50%;
                    }
                }
            }
        }
    }
            
    .thumb {
        min-width: 35%;
        max-width: 35%;
        border-radius: 15px;
        overflow: hidden;
        
        img {
            max-width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1);
            transition: .5s ease;
        }
    }
    
    
    
    &:not(.show-img) {
        background: rgba(var(--bs-body-color-rgb), 0.1);
        padding: 30px;
        border-radius: 30px;
        height: auto;
        margin-bottom: 30px;
        .thumb {
            display: none;
        }
    }

    &:not(.show-data) .data, &:not(.show-categoria) .categoria, &:not(.show-autor-pic) .autor img, &:not(.show-autor) .autor, &:not(.show-resumo) p.resumo {
        display: none !important;
    }
    
    &.hide-meta .meta {
        display: none !important;
    }
    
    .col-lg-6 &, .col-xl-6 &, .col-xxl-6 & {
        h2 {
            font-size: 19px !important;
        }
    }
    
    .col-lg-4 &, .col-xl-4 &, .col-xxl-4 & {
        h2 {
            font-size: 17px !important;
        }
    }
    
    &.order-x {
        flex-direction: row;
    }
    &.order-x-reverse {
        flex-direction: row-reverse;
    }
    
    &.order-y {
        gap: 30px;
        flex-direction: column;
        
        .info {
            padding: 0 !important;
        }
    }
    &.order-y-reverse {
        gap: 30px;
        flex-direction: column-reverse;
        
        .info {
            padding: 0 !important;
        }
    }
    
    &:hover {
        .info {
            h2 {
                color: var(--nown-primaria);
            }
        }
        
        .thumb {
            img {
                transform: scale(1.05);
            }
        }
    }
    
    &.order-y, &.order-y-reverse {
        .thumb {
            min-width: 100% !important;
            max-height: 400px !important;
            
            img {
                width: 100% !important;
            }
        }
        
        .info {
            height: auto !important;
        }
    }
}


@media only screen and (max-width: 1200px) {
    
    #conteudo{
        padding: 0px !important;
    }
    
  .artigo-card{
      flex-direction: column !important;
      gap: 0px !important;
      padding: 10px !important;
      
      margin-bottom: 10px;
      
      .thumb{
      width: 100% important;
      min-width: 100% important;
      max-width: 100% !important;
      border-radius: 0px !important;

        }
        
        .info{
            padding: 10px 20px !important;
        }
  }
  
  
  body.light{
      .artigo-card{
      background-color: white;
    }
  }
  
  body.dark{
      .artigo-card{
      background-color: black;
    }
  }
}


</style>


<?

//print_r(MODULO);




$alturaTopo =  500;
$tamanhos = [
    ["size"=>"7", "colunas"=>1],
    ["size"=>"5", "colunas"=>2]
    ];
    
$porlinha = verModulo("home", "itenslinha" , 1);



$noticia = '
     <article class="artigo-topo estilo-1 show-img  center-start">
                <div src="" class="thumb"></div>
                <a href="#" class="overlay-link"></a>
                <div class="info"> 
                    <h2 class="titulo m-0">'.linhas(2, 10, 100).'</h2>
                </div>
            </article>';
    
$calc = 12 / $porlinha;

$image = verModulo("home", "posicaoimagem" , "sem");
if($image != "sem"){
    
    $posicao = $image;
    $tamanhoImagem = $posicao == "esquerda" || $posicao == "direita" ? 5 : 12;
    if($tamanhoImagem != 12){
        $orderImagem = $posicao == "direita" ? 2 : 1;
        $orderText = $posicao == "direita" ? 1 : 2;
    }else{
        $orderImagem = $posicao == "topo" ? 1 : 2;
        $orderText = $posicao == "topo" ? 2 : 1;
    }
    
    
    
    $size = $posicao == "esquerda" || $posicao == "direita" ? 7 : 12;
    $imagem = '<div class="col-'.$tamanhoImagem.' order-'.$orderImagem.'" >
                        <div class="he-250 bg-danger rounded"></div>
                </div>';
}else{
    $imagem = '';
    $size = 12;
}

$bg = verModulo("home", "backgroudcard" , "false") == "true" ? "bg-transparent" : ""; 
$pd = verModulo("home", "backgroudcard" , "false") == "true" ? "p-0" : ""; 

$resumo = verModulo("home", "resumolista" , "false") == "true" ? '<p>O Globo Rural deste domingo reprisou uma reportagem sobre a criação do pescado no sul da Bahia. Ficou com vontade? Veja formas de prepará-lo.</p>' : ""; 
$categoria = verModulo("home", "categorialista" , "false") == "true" ? '<span>Estação Agro</span>' : ""; 
$data = verModulo("home", "datalista" , "false") == "true" ? '<span>Há 4 dias</span>' : ""; 


$resto = '
    <article class="artigo-card estilo-1 show-img show-data show-categoria show-autor show-autor-pic show-resumo order-x">
        <a  class="overlay-link"></a>
        <div class="thumb">
            <div class="ratio ratio-4x3 bg-carregando"></div>
        </div>
        <div class="info">
            <div class="info-inside">
                <span class="fw-700 mb-2 fs-14  d-none">Flagrante em MG</span>
                <h2 class="titulo">
                    '.linhas(2, 10, 100).'
                </h2>
                <div class="resumo d-none d-xl-block">
                    '.linhas(2, 15, 100).'
                </div>
                <div class="meta">
                    <span class="data">Em 10/04/2024</span>
                    <a href="#" class="categoria">Nome da Categoria</a>
                </div>
            </div>
        </div>
    </article>
';


$esquerda = "";
$direita = "" ;

$sidebar = verModulo("home", "sidebar" , "false");
if($sidebar == "true"){
    $listasize = 8;
    
ob_start();
include __DIR__."/parts/widget.php";
$widget_content = ob_get_clean();

$sider = '
    <div class="col-xl-4 d-none d-xl-block">
    '.$widget_content.'
    </div>
';
   
    $posicaoSide = verModulo("home", "sidebarposition" , "esquerda"); 
    
    if($posicaoSide == "esquerda"){
        $esquerda = $sider;
    }else{
        $direita = $sider;
    }
    
    
    
    
}else{
    $listasize = 12;
}

//include __DIR__."/../../../../../includes/sistema/simplheader.php";
?>

<div id="componentemodelo" class="d-none">
    <?=$resto?>
</div>


<div style="max-width: 1150" class="m-auto">
    <div class="d-none d-xl-block">
    <div class="row he-xl-<?=$alturaTopo?>">
        <?
        $i = 0;
        while($i < count($tamanhos)){
            
            $html = "";
            
            $quantidade = $tamanhos[$i]["colunas"];
   
       
            $y = 0;
            while($y < $quantidade){
                $html .= $noticia;
                
                $y++;
            }
            
            echo '
            <div class="col-xl-'.$tamanhos[$i]["size"].'">
                <div class="h-100 d-flex justify-content-between flex-column gap-4">
                        '.$html.'
                
                </div>
                
            </div>';

            $i++;
        }
        
        ?>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <?=$esquerda?>
        <div class="col-xl-<?=$listasize?>">
            <div class="row g-2 g-xl-4"  id="lista">
                <?
                $i = 0;
                while($i < 10){
                    echo '<div class="loading">';
                    echo $resto;
                    echo '</div>';
                    $i++;
                }
                
                ?>
            </div>
            <div id="refFim"></div>
        </div>
        <?=$direita?>
    </div>
</div>
</div>

