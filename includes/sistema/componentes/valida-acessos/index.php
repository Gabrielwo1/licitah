<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//include_once __DIR__."/../../../../admin/conn.php";
include_once __DIR__."/../../../../admin/config.php";
include_once __DIR__."/../../../../admin/ver.php";
session_start();
$conn = conn();
$cards = "";
$i = 0;
while($i < 4){
    $cards .= '  <div class="col p-1">
                     <div class="ratio ratio-1x1">
                         <input type="text" class="form-control nown-control text-center" placeholder="-" maxlength="1" data-index="'.$i.'">
                     </div>
                 </div>';
    
    $i++;
}
$email = '
    <h2 class="fs-16 fw-500"><i class="bi bi-envelope"></i> Código do E-mail</h2>
    <div class="login-form mb-4">
        <div class="row mb-3">
            '.$cards.'
        </div>
    </div>';
    
$telefone = '
    <h2 class="fs-16 fw-500"><i class="bi bi-phone"></i> Código do Celular</h2>
    <div class="login-form mb-4">
        <div class="row mb-3">
            '.$cards.'
        </div>
    </div>';
?>
<div class="card card-nown" style="max-width: 600px;" id="cardValida">
   <div class="card-body p-0">
      <div class="p-5 overflow-x-hidden">
            <h1 class="fs-22 text-center fw-700"><i class="bi bi-fingerprint fs-20"></i> VALIDE SEUS ACESSOS</h1>
            <p class="fs-14 mb-4 text-center">Verifique os códigos enviados para você</p>
            
            <!-- Primeira tela: Receber código -->
            <div id="telaReceberCodigo" class="text-center">
                <button class="btn btn-nown-style btn-n-primaria mb-3" type="button" id="btnReceberCodigo">RECEBER CÓDIGO</button>
            </div>
            
            <!-- Segunda tela: Inserir código -->
            <div id="telaInserirCodigo" class="p-4" style="max-width: 300px; display: none; margin: 0 auto;">
                <?php
                $id = $_SESSION["id"];
                
                if(v(["paginas","cadastro","validaremail"], false)){
                    $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='email-valido' AND um_valor='1'";
                    $resultado = $conn->query($seleciona);
                    if($resultado->num_rows == 0){
                        echo $email;
                    }
                }
                
                if(v(["paginas","cadastro","validartelefone"], false)){
                    $seleciona = "SELECT * FROM usuarios_meta WHERE um_usuario='$id' AND um_chave='telefone-valido' AND um_valor='1'";
                    $resultado = $conn->query($seleciona);
                    if($resultado->num_rows == 0){
                        echo $telefone;
                    }
                }
                ?>
                
                <button class="btn w-100 btn-nown-style btn-n-primaria mb-3" disabled id="btnContinuar">CONTINUAR</button>
                
                <p class="text-center fs-14 mb-0">
                    Não recebeu o código? 
                    <button class="btn text-decoration-none text-primaria" type="button" id="btnReenviar">
                        Reenviar <span id="tempoReenvio" style="display: none;">(30s)</span>
                    </button>
                </p>
            </div>
            
            <p class="text-center fs-14 mb-0"><button class="btn goPage text-decoration-none text-primaria" id="deslogar">Deslogar</button></p>
      </div>
   </div>
</div>