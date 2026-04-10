<?
function arrayMenus(){
    $conn = conn();
    $array = [
        [
                "valor"=>0,
                "chave"=>"Escolha um Menu"
                ]
        ];
    $seleciona = "SELECT menu_nome, menu_id FROM menus";
    $resultado = $conn->query($seleciona);
    if($resultado->num_rows > 0){
        while($dado = $resultado->fetch_assoc()){
            $item = [
                "valor"=>$dado["menu_id"],
                "chave"=>$dado["menu_nome"] ? $dado["menu_nome"] : "",
                ];
                array_push($array, $item);
        }
    }
    return $array;
}

$menus = arrayMenus();


$switch = new Swith([
    "name"=>"deslogadas",
    "descricao"=>"O sistema irá mostrar páginas para usuários não logados",
    "titulo"=>"Sistema para deslogados",
    ]);
echo $switch->html();


$switch = new Swith([
    "name"=>"lateral",
    "descricao"=>"Ativar menu Lateral para usuários deslogados",
    "titulo"=>"Menu Lateral",
    ]);
echo $switch->html();

$input = new Input("SELECT");
$input->set("name", "menuTopo");
$input->set("label", "Menu Topo");
$input->set("opcoes", $menus);
echo $input->html();


$input = new Input("SELECT");
$input->set("name", "menuFooter");
$input->set("label", "Menu Rodapé");
$input->set("opcoes", $menus);
echo $input->html();


$input = new Input("SELECT");
$input->set("name", "menuLateral");
$input->set("label", "Menu Lateral");
$input->set("opcoes", $menus);
echo $input->html();
?>