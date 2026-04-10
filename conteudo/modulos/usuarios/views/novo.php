<?



$upload = new Upload(
    [
    "accept"=>"image/*", 
    "multiple"=>false,
    "pasta"=>"artigos",
    "titulo"=>"Imagem Destaque",
    "id"=>"destaque",
    "mensagem"=>"Carregue a imagem principal do artigo. Só são aceitos formatos de imagem."
    ]
    );
    
$html = $upload->html();


$nome = new Input("INPUT");
$nome->set("name", "nome");
$nome->set("placeholder", "Titulo de Artigo");
$nome->set("obrigatorio", true);
echo $nome->html();