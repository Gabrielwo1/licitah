<?
$switch = new Swith([
    "name"=>"multi",
    "descricao"=>"O sistema terá vários idiomas para os usuários",
    "titulo"=>"Multi Idiomas",
    ]);
echo $switch->html();

$idiomas = array(
    "Inglês" => "en",
    "Espanhol" => "es",
    "Francês" => "fr",
    "Alemão" => "de",
    "Chinês (Mandarim)" => "zh",
    "Árabe" => "ar",
    "Russo" => "ru",
    "Português" => "pt-BR",
    "Japonês" => "ja",
    "Hindi" => "hi",
    "Bengali" => "bn",
    "Urdu" => "ur",
    "Coreano" => "ko",
    "Italiano" => "it",
    "Holandês" => "nl",
    "Grego" => "el",
    "Turco" => "tr",
    "Sueco" => "sv",
    "Norueguês" => "no",
    "Dinamarquês" => "da",
    "Finlandês" => "fi",
    "Polonês" => "pl",
    "Tailandês" => "th",
    "Hebraico" => "he",
    "Indonésio" => "id",
);


$array = [];
$pai = v(["idioma", "geral", "idioma"], $alt = "pt-BR");
foreach($idiomas as $chave=>$valor){
    $item = [
        "chave"=>$chave,
        "valor"=>$valor
        ];
        
        
        if($valor != $pai){
            array_push($array, $item);
        }
        
}

echo '<div class="d-none">';
$input = new Input("TEXTAREA");
$input->set("name", "segundarios");
echo $input->html();
echo '</div>';


echo '<div class="d-none" id="idiomas">'.json_encode($array).'</div>';

echo '<div id="listaIdiomas" class="d-flex justify-content-between gap-2 flex-column"></div>';

echo '<button class="btn btn-primary rounded-0 mt-4" id="novoIdioma">Novo Idioma</button>';
?>