<?
$titulo = "Escolha o CNPJ";
$sub = "Escolha com qual empresa você quer continuar";
$btnPrimeiro = '<button class="btn btn-nown-style d-block w-100 btn-n-primaria text-uppercase mb-4" id="addEmpresa">Crie sua Primeira Empresa</button>
<div class="d-flex align-items-center"> <button class="btn  d-block w-100 text-decoration-underline text-primaria" id="addVinculo">
                        Se Vincular em Empresa
                    </button></div>';

$seleciona = "SELECT ea_empresa as empresa FROM empresas_associacao WHERE ea_usuario='{$usuario}'";
$res = $conn->query($seleciona);
$ids = [];
if($res->num_rows > 0){
    while($dado = $res->fetch_assoc()){
        $ids[] = $dado["empresa"];
    }
}

if(!empty($ids)){
    $lista = implode(",", $ids);
    $seleciona = "SELECT empresa_nome as nome, empresa_hash as id FROM  empresas WHERE empresa_id IN ({$lista})";
    $resultado = $conn->query($seleciona);
}else{
    $resultado =  false;
}
?>