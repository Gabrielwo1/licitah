<?
session_start();
include __DIR__."/../../../../admin/conn.php";
$conn = conn();
$opcoes = [["v"=>0, "t"=>"Loja Principal"]];
if(isset($_SESSION["id"])){
     $seleciona = "SELECT * FROM  market_vendedores";
        $resultado = $conn->query($seleciona);
        if($resultado->num_rows > 0){
            while($dado = $resultado->fetch_assoc()){
                $opcoes[] = ["v"=>$dado["market_vendedor_id"], "t"=>$dado["market_vendedor_nome"]];
            }
        }
}

echo json_encode(["sucesso"=>true, "lista"=>$opcoes], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>