<?
session_start();
header("Content-Type: application/json");
require_once("websocket.php");
    
    
    $pusher =  websocket();
    $socket_id = $_POST['socket_id'];
    $user_id = $_SESSION["id"];
    $peer = $_GET["peer"] ?? false;
    $presence_data = array('user_id' => $user_id, 'user_info' => 
    array(
        'nome' => $_SESSION["nome"],
        'email' =>$_SESSION["email"],
        'funcao' =>$_SESSION["funcao"],
        'ativo' =>$_SESSION["ativo"],
        'foto' =>$_SESSION["foto"],
        "peer"=>$peer
        )
    );

    echo $pusher->presence_auth($_POST['channel_name'], $socket_id, $user_id, $presence_data);





?>