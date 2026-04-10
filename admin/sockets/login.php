<?php
session_start();
function tokenSecure(){
    $caracteres = "1234567890abcdefghijklmnopqrstuvxwzABCDEFGHIJKLMNOPQRSTUVXWYZ";
    $tamanho = 60;
    $codigo = "";
    $i = 0;
    $tamanho = strlen($caracteres);
    while($i < 60){
        $c = rand(0, $tamanho);
        $letra = $caracteres[$c];
        $codigo .= $letra; 
        
        $i++;
    }
    
    return $codigo;
}

if($_POST["codigo"] && $_SESSION["id"]){
    $codigo = $_POST["codigo"];
    
  include "websocket.php";
    
 $token = tokenSecure();    
 
 include '../conn.php';
 $conn = conn();
 $user = $_SESSION["id"];
 
 $deleta = "DELETE FROM tokenLogin WHERE token_user='$user'";
 $conn->query($deleta);
  
 $cadastra = "INSERT INTO tokenLogin (token_hash,token_user) VALUES ('$token', '$user')";
 $conn->query($cadastra);

 $data['message'] =  $token;
 $pusher->trigger("login-$codigo", 'login', $data);
}

?>