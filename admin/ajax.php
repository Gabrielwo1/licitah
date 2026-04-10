<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
session_start();



$resposta["acao"] = false;

$dir = __DIR__."/..";

include "config.php";
include "sockets/websocket.php";
include "global.php";

$resposta = [];


function funcao($string){
   // call_user_func($string);
}

if(isset($_POST["acao"])){
    $acao = $_POST["acao"];

    if(count(explode("_" , $acao)) > 1){
 
        $modulo = explode("_" , $acao)[0];
        if(is_dir($dir."/conteudo/modulos/".$modulo) || is_dir($dir."/master/modulos/".$modulo)){

            if(file_exists($dir."/conteudo/modulos/".$modulo."/admins/acao.php") || file_exists($dir."/master/modulos/".$modulo."/admins/acao.php")){
                if(file_exists($dir."/conteudo/modulos/".$modulo."/admins/acao.php")){
                    include $dir."/conteudo/modulos/".$modulo."/admins/acao.php";
                }else{
                    include $dir."/master/modulos/".$modulo."/admins/acao.php";
                }
                     
                $resposta["acao"] = true;
                
                $acionador = explode("_" ,  $_POST["acao"])[1];
                
                $acao = isset($setup) ? new Acao($setup) : false;
                switch($acionador){
                    case 'cadastra':
                        $resposta["sucesso"] = $acao->cadastra();
                        break;
                    case 'edita':
                        $resposta["sucesso"] = $acao->edita();
                        break;
                    case 'deleta':
                        $resposta["sucesso"] = $acao->deletar();
                        break;
                    case 'lista':
                        $resposta["sucesso"] = $acao->lista();
                        break;
                    case 'item':
                        $resposta["sucesso"] = $acao->item();
                        break;
                    case 'render': 
                        $resposta["sucesso"] = $acao->renderMassa();
                        break;
                    default:
                        if(function_exists($acionador)){
                            $resposta["sucesso"] = $acionador(); 
                        }
                        break;
                }
    
                if(!isset($resposta["sucesso"])){
                    $resposta = ["erro"=>true, "Mensagem"=>"Ação Não Encontrada"]; 
                }
                 
                 
                 
                 
                 
            }
        }
    }
}

echo json_encode($resposta);

?>