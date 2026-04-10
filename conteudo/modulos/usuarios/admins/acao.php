<?
$setup = [];
$setup["banco"] = [
    "bancoNome"=>"usuarios",
    "prefixo"=>"usuario",
    "hash"=>"usuario_hash",
    "autor"=>false,
    "publico"=>false,
    "render"=>false,
    ];
$setup["lista"] = [
    ["chave"=>"imagem_foto", "valor"=>"imagem", "meta"=>true, "header"=>"Foto"],
        ["chave"=>"usuario_id", "valor"=>"id", "meta"=>false, "header"=>"id"],
        ["chave"=>"usuario_display", "valor"=>"nome", "meta"=>false, "header"=>"Nome"],
        ["chave"=>"usuario_email", "valor"=>"email", "meta"=>false, "header"=>"E-mail"],
        
    ];
$setup["meta"] = [
    "bancoNome"=>"usuarios_meta",
    "item"=>"um_usuario",
    "prefixo"=>"um",
    ];
$setup["chaves"] = [
    ["chave"=>"artigo_titulo", "obrigatorio"=>true, "meta"=>false, "map"=>"nome"],
    ["chave"=>"artigo_texto", "obrigatorio"=>false, "meta"=>false, "map"=>"texto"],
    ["chave"=>"descricao", "obrigatorio"=>false, "meta"=>true, "map"=>"descricao"]
    ];
$setup["acoes"] =[
    ["nome"=>"editar", "tipo"=>"url" , "destino"=>"a/perfil/"],
    ["nome"=>"deletar", "tipo"=>"funcion", "destino"=>"deleta" ,"chave"=>"usuario_deleta"]
    ];
$setup["callBack"] = [
    "cadastro"=>"a/perfil/",
    ];
$setup["mensagens"] = [
    "cadastro"=>["sucesso"=>"Jogador Cadastrado com sucesso", "erro"=>"Falha ao Cadastrar Usuário"],
    "editar"=>["sucesso"=>"Jogador Editado com Sucesso", "erro"=>"Falha ao editar Jogador"],
    "deleta"=>["sucesso"=>"", "erro"=>""],
    ];
    
   
  

?>