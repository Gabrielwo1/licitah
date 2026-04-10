<?
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

foreach($idiomas as $chave=>$valor){
    $item = [
        "chave"=>$chave,
        "valor"=>$valor
        ];
        array_push($array, $item);
}



$input = new Input("SELECT");
$input->set("label", "Idioma do Sistema");
$input->set("descricao", "Defina o idioma padrão do sistema.");
$input->set("name", "idioma");
$input->set("opcoes", $array);

echo $input->html();








?>