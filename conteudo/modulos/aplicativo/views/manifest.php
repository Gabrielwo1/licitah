<?
function input($label, $descricao, $name, $tipo = "input"){
    switch($tipo){
        case 'textarea':
            $entrada = '<textarea class="form-control entradaManifest" name="'.$name.'"></textarea>';
            break;
        case 'color':
            $entrada = '<input class="form-control entradaManifest" name="'.$name.'" type="color" style="height: 50px">';
            break;
        default:
            $entrada = '<input class="form-control entradaManifest" name="'.$name.'" type="text">';
            break;
    }
    
    return '
    <div>
        <label>'.$label.'</label>
        '.$entrada.'
        <p class="field-desc fs-12 mb-1">'.$descricao.'</p>
    </div>';
}

function select($label, $descricao, $name, $os = []){
    
    $opts = "";
    foreach($os as $o){
        $opts .= '<option value="'.$o["v"].'">'.$o["t"].'</option>';
    }
    
    
    return '
    <div>
        <label>'.$label.'</label>
        <p class="field-desc fs-12 mb-1">'.$descricao.'</p>
        <select class="form-select entradaManifest" name="'.$name.'">'.$opts.'</select>
    </div>';
}

function checkboxInputs($label, $descricao, $nameBase, $os = []) {
    $checkboxes = "";
    foreach ($os as $index => $o) {
        $checkboxes .= '
        <div class="form-check col-xl-3 py-xl-1">
            <input class="form-check-input entradaManifestCat" type="checkbox" name="'.$nameBase.'['.$index.']" value="'.$o["v"].'" id="'.$nameBase.$index.'">
            <label class="form-check-label" for="'.$nameBase.$index.'">
                '.$o["t"].'
            </label>
        </div>';
    }
    
    return '
    <div>
        <label>'.$label.'</label>
        <p class="field-desc fs-12 mb-1">'.$descricao.'</p>
        <div class="row m-0">
        '.$checkboxes.'
        </div>
    </div>';
}



?>


<div class="row">
    <div class="col-xl-12">
        <div class="card card-nown mb-4">
            <div class="card-header">
                <h2 class="m-0 fs-18">Informações</h2>
            </div>
    <div class="card-body d-flex flex-column gap-3">
        <?
        echo input("Nome", "O nome do seu aplicativo conforme exibido para o usuário", "name");
        echo input("Nome Curto", "Usado em lançadores de aplicativos", "short_name");
        echo input("ID", "Identificador único para seu APP que é separado de campos que podem mudar ao longo do tempo (como nome ou nome curto)", "id");
        echo input("Descrição", "Usado em lojas de aplicativos e diálogos de instalação", "description", "textarea");
        echo input("Cor Principal", "Selecione a cor do tema", "theme_color", "color");
        echo input("Cor de Fundo", "Selecione a cor de fundo do aplicativo", "background_color", "color");
        


        ?>

    </div>
</div>  
 <div class="card card-nown mb-4">
            <div class="card-header">
                <h2 class="m-0 fs-18">Configurações</h2>
            </div>
    <div class="card-body d-flex flex-column gap-3">
        <?
        echo input("URL Incial", "A URL que é carregada quando seu APP inicia", "start_url");
        echo select("Direção", "A direção do texto do seu APP", "dir", 
        [
           ["t"=>"Automático", "v"=>"auto"],
           ["t"=>"Esquerda/Direita", "v"=>"ltr"], 
           ["t"=>"Direita/Esquerda", "v"=>"rtl"], 
            
        ]
        );
        echo input("Escopo", "Quais URLs podem ser carregadas dentro do seu aplicativo", "scope");
        echo select("Idioma", "O idioma primário do Aplicativo", "lang",  [
    ["v" => "pt", "t" => "Português"],

    ]);
        echo select("Orientação", "A orientação de tela padrão do seu aplicativo", "orientation", 
        [
    ["v" => "any", "t" => "Qualquer Uma - Representa qualquer orientação de tela"],
    ["v" => "natural", "t" => "Natural -  A orientação padrão do dispositivo"],
    ["v" => "landscape", "t" => "Paisagem  - Orientação horizontal"],
    ["v" => "portrait", "t" => "Retrato - Orientação vertical"],
    ["v" => "landscape-primary", "t" => "Paisagem Primária"],
    ["v" => "landscape-secondary", "t" => "Paisagem Secundária"],
    ["v" => "portrait-primary", "t" => "Retrato Primário"],
    ["v" => "portrait-secondary", "t" => "Retrato Secundário"]
    ]);
        echo select("Display", "A aparência da janela do seu aplicativo", "display", [
    ["v" => "fullscreen", "t" => "Tela Cheia"],
    ["v" => "standalone", "t" => "Independente"],
    ["v" => "minimal-ui", "t" => "UI Mínima"],
    ["v" => "browser", "t" => "Navegador"]
]);
        ?>
       
    </div>
</div>   
    <div class="card card-nown">
               <div class="card-header">
                <h2 class="m-0 fs-18">Categorias</h2>
            </div>
        <div class="card-body">
             
             <?
                 echo checkboxInputs("Categorias", "Defina as categorias em que seu aplicativo se aplica", "categoria",  [
    ["v" => "books", "t" => "Livros"],
    ["v" => "business", "t" => "Negócios"],
    ["v" => "education", "t" => "Educação"],
    ["v" => "entertainment", "t" => "Entretenimento"],
    ["v" => "finance", "t" => "Finanças"],
    ["v" => "food_drink", "t" => "Comida & Bebida"],
    ["v" => "games", "t" => "Jogos"],
    ["v" => "government_politics", "t" => "Governo & Política"],
    ["v" => "health_fitness", "t" => "Saúde & Fitness"],
    ["v" => "kids_family", "t" => "Crianças & Família"],
    ["v" => "lifestyle", "t" => "Estilo de Vida"],
    ["v" => "medical", "t" => "Médico"],
    ["v" => "music", "t" => "Música"],
    ["v" => "news_weather", "t" => "Notícias & Clima"],
    ["v" => "personal_finance", "t" => "Finanças Pessoais"],
    ["v" => "photography", "t" => "Fotografia"],
    ["v" => "productivity", "t" => "Produtividade"],
    ["v" => "reference", "t" => "Referência"],
    ["v" => "shopping", "t" => "Compras"],
    ["v" => "social", "t" => "Redes Sociais"],
    ["v" => "sports", "t" => "Esportes"],
    ["v" => "tools", "t" => "Ferramentas"],
    ["v" => "travel", "t" => "Viagem"],
    ["v" => "utilities", "t" => "Utilidades"],
    ["v" => "video_players_editors", "t" => "Reprodutores & Editores de Vídeo"],
    ["v" => "weather", "t" => "Clima"],
]);
             
             ?>
             
        </div>
    </div>
    </div>
    
</div>