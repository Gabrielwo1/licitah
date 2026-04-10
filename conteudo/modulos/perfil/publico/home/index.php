<?
if(isset($_SESSION["id"])){
    include __DIR__."/perfil.php";
}else{
    echo '
    <div class="d-flex justify-content-center"></div>
    ';
}
?>