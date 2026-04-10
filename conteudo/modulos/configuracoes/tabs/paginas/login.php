<?

$switch = new Swith([
    "name"=>"login",
    "descricao"=>"O sistema de login está ativo para todos os usuários",
    "titulo"=>"Login",
    ]);
echo $switch->html();


$input = new Input("SELECT");
$input->set("label", "Largura Box");
$input->set("descricao", "Defina a largura do Box para computadores. O layout sempre será de 100% em dispositivos moveis.");
$input->set("name", "largura");
$input->set("opcoes",[
["chave"=>"100%","valor"=>12],
["chave"=>"90%","valor"=>11],
["chave"=>"80%","valor"=>10],
["chave"=>"70%","valor"=>9],
["chave"=>"60%","valor"=>7],
["chave"=>"50%","valor"=>6],
["chave"=>"40%","valor"=>5],
["chave"=>"30%","valor"=>4],
]);

echo $input->html();



$switch = new Swith([
    "name"=>"box",
    "descricao"=>"Ativar o Box Auxiliar",
    "titulo"=>"Box Auxiliar",
    ]);
echo $switch->html();


$input = new Input("SELECT");
$input->set("label", "Posição do Box");
$input->set("name", "posicaoBox");
$input->set("opcoes",[
["chave"=>"Direita","valor"=>0],
["chave"=>"Esquerda","valor"=>1]]);
echo $input->html();

echo '<div class="my-4"></div>';


$input = new Input("SELECT");
$input->set("label", "Conteudo Auxiliar");
$input->set("name", "auxiliar");
$input->set("descricao", "Defina o que irá compor o box auxiliar ao login");
$input->set("opcoes",[
["chave"=>"Nenhum","valor"=>0],
["chave"=>"QR Code","valor"=>1],
["chave"=>"Imagem","valor"=>2],
]);
echo $input->html();


$input = new Input("INPUT");
$input->set("label", "Título Auxiliar");
$input->set("name", "tituloAuxiliar");
$input->set("descricao", "Título que irá compor o box auxiliar");
echo $input->html();


$input = new Input("TEXTAREA");
$input->set("label", "Texto Auxiliar");
$input->set("name", "textoAuxiliar");
$input->set("descricao", "Texto que acompanha o box auxiliar");
echo $input->html();


echo '<div class="my-4"></div>';


echo titularizador("TIPOS DE LOGIN");
$switch = new Swith([
    "name"=>"login-email",
    "descricao"=>"Usuário logará usando E-mail",
    "titulo"=>"E-mail",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"login-telefone",
    "descricao"=>"Usuário logará usando Telefone",
    "titulo"=>"Telefone",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"login-cpf",
    "descricao"=>"Usuário logará usando CPF",
    "titulo"=>"CPF",
    ]);
echo $switch->html();

$switch = new Swith([
    "name"=>"login-usuario",
    "descricao"=>"Usuário logará usando nome de Usuário",
    "titulo"=>"Usuário",
    ]);
echo $switch->html();




echo titularizador("ESTILO DA PAGINA");

echo '<div class="row">';

echo '<div class="col-4">';
$input = new Input("INPUT");
$input->set("label", "Cor do Background");
$input->set("name", "corBaxkground");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-4">';
$input = new Input("INPUT");
$input->set("label", "Cor do Box");
$input->set("name", "corBox");
$input->set("type", "color");
echo $input->html();
echo '</div>';

echo '<div class="col-4">';
$input = new Input("INPUT");
$input->set("label", "Cor do Texto");
$input->set("name", "corTexto");
$input->set("type", "color");
echo $input->html();
echo '</div>';



echo '<div>';





?>