<?php
header('Content-Type: application/json; charset=utf-8');



include 'conn.php';

class Api{
    private $tipo;
    private $filtro;
    private $quantidade;
    private $identificador;
    private $paginacao;
    private $tipagem;
    private $conn;
    private $chaveamento;
    
    function __construct($infos){
        $this->tipagem = ["produtos", "artigos", "jogadores", "diretoria", "videos", "guias", "patrocinadores", "planos", "faqs", "midias"];
        $this->conn = conn();
        $this->tipo = isset($infos["tipo"]) ? $infos["tipo"] : false;
        $this->filtro = isset($infos["filtro"]) ? $infos["filtro"] : "lista";
        $this->quantidade = isset($infos["quantidade"]) ? (int)$infos["quantidade"] : 10;
        $this->paginacao = isset($infos["paginacao"]) ? (int)$infos["paginacao"] : 1;
        $this->identificador = isset($infos["identificador"]) ? $infos["identificador"] : false;
        
        
        $this->chaveamento = [
            "artigos"=>"artigo_id",
            "produtos"=>"produto_id",
            "videos"=>"video_id",
            "midias"=>"midia_id"
            ];
        
        
    }
    
    function resposta(){
        if($this->tipo){
           $resposta = [];
           $resposta["acao"] = true;
           
           $banco = $this->tipo;
           $conn = $this->conn;
           
           if($this->filtro == "lista"){
               $inicio = ($this->paginacao - 1) * $this->quantidade;
               
               $organizador = "";
               foreach($this->chaveamento as $k=>$v){
                   if($k == $banco){
                       $organizador = "ORDER BY $v DESC";
                       break;
                   }
               }
               
               
               $seletor = "SELECT render FROM " . $banco . " ".$organizador."  LIMIT " . $inicio . ", " . $this->quantidade;

           }else{
               if($this->identificador){
                    $seletor = "SELECT render FROM $banco WHERE url_publica='$this->identificador'";
               }else{
                   $resposta["erro"] = "Não foi enviado um parametro de identificação";
                   return $resposta;
               }
           }
           
           $resultado = $conn->query($seletor);
           if($resultado->num_rows > 0){
               $lista = [];
               while($dado = $resultado->fetch_assoc()){
                   $render = $dado["render"];
                   if($render){
                       $render = json_decode($render);
                   }
                   array_push($lista, $render);
               }
               
               $resposta["sucesso"] = $lista;
           }else{
               $resposta["sucesso"] = false;
           }
           
           
           return $resposta;
        }else{
            return ["erro"=>true, "mensagem"=>"Essa API não é publica"];
        }
        
    }
}


$api = new Api($_POST);
$resposta = $api->resposta();


echo json_encode($resposta);


/*
Essa API volta informações públicas para os usuários

$_GET["tipo"] - Volta o tipo de post que você quer
$_GET["filtro"] - ["lista", "item"] - Define se deve voltar uma lista ou informação única do item
$_GET["quantidade"] - Inteiro - Define a quantidade de dados que serão devolvidos
$_GET["paginacao"] - Define o lote de informações a serem devolvidos
$_GET["identificador"] - Caso a solicitação seja do tipo item, enviar o parametro de url_publica, para ser procurado no banco de dados

*/


?>

