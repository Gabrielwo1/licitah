<?

echo titularizador("Compartilhamento");


$switch = new Swith([
    "name"=>"share_topo",
    "descricao"=>"Habilite a ferramenta de compartilhamento no topo do artigo",
    "titulo"=>"Compartilhar - Topo",
    ]);
echo $switch->html();


$switch = new Swith([
    "name"=>"share_footer",
    "descricao"=>"Habilite a ferramenta de compartilhamento no fim do artigo",
    "titulo"=>"Compartillhar - Fianl do Artigo",
    ]);
echo $switch->html();



echo titularizador("Widgets");


$switch = new Swith([
    "name"=>"box_autor",
    "descricao"=>"Habilite o box de autor para todos os usuários",
    "titulo"=>"Box Autor",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"proximo_anterior",
    "descricao"=>"Habilite a possibilidade do usuário acessar o próximo artigo ou anterior",
    "titulo"=>"Próximo / Anterior",
    ]);
echo $switch->html();


$switch = new Swith([
    "name"=>"artigos_relacionados",
    "descricao"=>"Habilite a exibição de artigos relacionados no fim do post",
    "titulo"=>"Artigos Relacionados",
    ]);
echo $switch->html();



$switch = new Swith([
    "name"=>"comentarios",
    "descricao"=>"Habilite a área de comentários para os usuários",
    "titulo"=>"Área de Comentários",
    ]);
echo $switch->html();


echo titularizador("Exibição de Topo");

$switch = new Swith([
    "name"=>"autor_top",
    "titulo"=>"Minuatura do Autor",
    ]);
echo $switch->html();



$switch = new Swith([
    "name"=>"data_top",
    "titulo"=>"Data de Públicação",
    ]);
echo $switch->html();


$switch = new Swith([
    "name"=>"contador_vizualizacoes",
    "titulo"=>"Contador de Vizualizações",
    ]);
echo $switch->html();


$switch = new Swith([
    "name"=>"contador_comentarios",
    "titulo"=>"Contador de Comentários",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"contador_compartihamento",
    "titulo"=>"Contador de Compartilhamento",
    ]);
echo $switch->html();





?>