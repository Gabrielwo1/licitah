<?
$itens = [
    "nome"=>["nome"=>"Seu nome"],
    "email"=>["nome"=>"Seu E-mail"],
    "telefone"=>["nome"=>"Seu Telefone"],
    "sobre"=>["nome"=>"Sobre Mim"],
    "aniversario"=>["nome"=>"Aniversário"],
    "pais"=>["nome"=>"País"],
    "genero"=>["nome"=>"Genero"],
    "redes-sociais"=>["nome"=>"Redes Sociais"],
    ];

?>


<h2 class="fs-16">Controle a Visibilidade dos dados do seu Perfil</h2>
<p>Suas informações serão exibidas conforme a regra que você definir.</p>
<div class="list-group">



<?


foreach($itens as $item){
      echo '
      <li  class="list-group-item list-group-item-action " >
    <div class="d-flex justify-content-start align-items-center">
    <div class="w-50">
        '.$item["nome"].'
    </div>
    <div class="w-50">
        <select class="form-select">
            <option>Ninguém pode ver</option>
            <option>Somente Conexões</option>
            <option>Somente Usuários Logados</option>
            <option>Todo Mundo</option>
        </select>
    </div>
</div>
 </li>';
}


?>

</div>