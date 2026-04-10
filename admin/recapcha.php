<?php

include 'config.php';
include 'validaCapcha.php';

session_start();


// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $seguranca = validaCapcha($_POST['g-recaptcha-response']);
}

echo json_encode($seguranca);
?>
