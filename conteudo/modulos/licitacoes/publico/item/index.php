<?



// Dados a serem enviados no corpo da requisição (em formato de array ou JSON)

function curlLicitacao($data){
    $ch = curl_init();
    $url = SETUP['dominio'].'admin/api2.php';
    // Configurando as opções cURL
    curl_setopt($ch, CURLOPT_URL, $url);               // URL do endpoint
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // Retornar a resposta como string
    curl_setopt($ch, CURLOPT_POST, true);              // Especificando que é uma requisição POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Passando os dados no corpo da requisição
    
    // Definindo cabeçalhos (se necessário)
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded', // Tipo de conteúdo
    ]);
    
    // Executando a requisição e capturando a resposta
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {

    } else {
        return json_decode($response, TRUE);
    }
}

$data = [
    'modulo' => 'licitacoes',
    'chave' => 'zcantt1cqFJBYdr',
    'hash' => $this->caminho[1],
];

$ch = curlLicitacao($data);

$item = $ch['item']['autor']['user'];
$visualizadores = $ch['item']['visualizadores'] ? $ch['item']['visualizadores'] : json_encode([]);
$empresa = $ch['item']['empresa'];

if($empresa != $_SESSION['sub']){
    return;
}

if($item == $_SESSION['user']){

}
else{
    $data = [
        'modulo' => 'usuarios',
        'chave' => 'cBWbdxSMQd0ZSQt',
        'itens' => $visualizadores,
    ];

    $ch = curlLicitacao($data);
    $continua = false;
    if(isset($ch['sucesso'])){
        foreach($ch['lista'] as $l){
            if($l['id'] == $_SESSION['id']){
                $continua = true;
                break;
            }
        }
    }
    
    if(!$continua){
        return;
    }

}



?>

<div class="container-fluid">
    <div class="container text-end">
        <button class="btn btn-n-secundaria" id="quadro-de-acessos">Quadro de Acessos</button>
    </div>
</div>

<div class="modal" tabindex="-1" id="modal-quadro">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quadro de Acessos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush" id="lista-de-usuarios">
                    <li class="list-group-item">An item</li>
                    <li class="list-group-item">A second item</li>
                    <li class="list-group-item">A third item</li>
                    <li class="list-group-item">A fourth item</li>
                    <li class="list-group-item">And a fifth one</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="gerenciando">
    <div class="container">
        <div class="header-boletim">
            <div class="boletins-wrap" id="boletinsWrap">
                <div class="cabecalho">
                    <button class="btn favorite"><i class="bi bi-star"></i>Favoritar</button>
                    <div class="att">
                        <span class="atualizada">Atualizada em: </span>
                        <div class="data d-inline-block bg-carregando he-20 wi-100"></div>
                    </div>
                </div>
                <div class="infos">
                    <div class="content bg-carregando he-20 mb-2"></div>
                    <div class="content bg-carregando he-20 mb-2"></div>
                    <div class="content bg-carregando he-20 mb-2"></div>
                    <div class="content bg-carregando he-20 mb-2"></div>
                    <div class="content bg-carregando he-20 mb-2"></div>
                    <div class="content bg-carregando he-20"></div>
                </div>
                <div class="actions">
                    <button class="btn download"><i class="bi bi-download"></i><span class="text">Baixar edital</span></button>
                    <button class="btn itens"><span class="text">Itens</span><i class="bi bi-chevron-down"></i></button>
                </div>
            </div>
        </div>
    
        <div id="tabs-gerenciado">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="tabTarefas-tab" data-bs-toggle="tab" data-bs-target="#tabTarefas"
                        type="button" role="tab" aria-controls="tabTarefas" aria-selected="true">Suas Tarefas</button>
                    <div class="separador"></div>
                    <button class="nav-link" id="tabAnotacoes-tab" data-bs-toggle="tab" data-bs-target="#tabAnotacoes"
                        type="button" role="tab" aria-controls="tabAnotacoes" aria-selected="false">Anotações</button>
                    <div class="separador"></div>
                    <button class="nav-link" id="tabAnexo-tab" data-bs-toggle="tab" data-bs-target="#tabAnexo" type="button"
                        role="tab" aria-controls="tabAnexo" aria-selected="false">Anexos</button>
                    <div class="separador"></div>
                    <button class="nav-link" id="tabHabilitacao-tab" data-bs-toggle="tab" data-bs-target="#tabHabilitacao"
                        type="button" role="tab" aria-controls="tabHabilitacao" aria-selected="false">Habilitação</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="tabTarefas" role="tabpanel" aria-labelledby="tabTarefas-tab"
                    tabindex="0">
                    <div class="tasks">
                        <div class="tasks-tab">
                            <div class="tasks-wrap">
                                <div class="task card day">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="bi bi-clock-fill"></i>
                                        <span class="title">Em progresso</span>
                                    </div>
                                    <h3>
                                        <div class="bg-carregando he-20 wi-30"></div>
                                    </h3>
                                    <button class="btn stretched-link btn-filtro-tarefa" data-tarefa="progresso"></button>
                                </div>
                                <div class="task card border-0">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span class="title">Concluídas</span>
                                    </div>
                                    <h3>
                                        <div class="bg-carregando he-20 wi-30"></div>
                                    </h3>
                                    <button class="btn stretched-link btn-filtro-tarefa" data-tarefa="concluida"></button>
                                </div>
                                <div class="task card border-0">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="bi bi-calendar-check-fill"></i>
                                        <span class="title">Tarefas do dia</span>
                                    </div>
                                    <h3>
                                        <div class="bg-carregando he-20 wi-30"></div>
                                    </h3>
                                    <button class="btn stretched-link btn-filtro-tarefa" data-tarefa="dia"></button>
                                </div>
                                <div class="task card border-0">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span class="title">Em atraso</span>
                                    </div>
                                    <h3>
                                        <div class="bg-carregando he-20 wi-30"></div>
                                    </h3>
                                    <button class="btn stretched-link btn-filtro-tarefa" data-tarefa="atrasado"></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-add btn-criar-tarefa"><i
                                    class="bi bi-plus-circle"></i><span class="text">Adicionar tarefa</span></button>
                            <!-- PRIMEIRO FORMULARIO -->
                        </div>
    
                        <div class="prox-tarefas">
                            <h3 class="titulo" id="titulo-tabs">
                                <div class="bg-carregando wi-60"></div>
                            </h3>
                            <div class="table-wrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tarefa</th>
                                            <th scope="col">Licitação</th>
                                            <th scope="col">Prazo</th>
                                            <th scope="col">Prioridade</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabelaTarefas">
                                        <?
                                            $i = 0;
                                            while($i < 5){
                                        ?>
                                        <tr>
                                            <th scope="row"><button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop2" class="eye btn"><i
                                                        class="bi bi-eye"></i></button></th>
                                            <td><span class="nome bg-carregando he-20 wi-150"></span></td>
                                            <td><span class="bg-carregando he-20 wi-150"></span></td>
                                            <td>
                                                <div class="progresso">
                                                    <div class="labels bg-carregando he-20 wi-150"></div>
                                                    <div class="progress progress-green" role="progressbar"
                                                        aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <div class="progress-bar" style="width: 25%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="notificacao bg-carregando he-20 wi-150"></div>
                                            </td>
                                            <td>
                                                <div class="notificacao bg-carregando he-20 wi-150"></div>
                                            </td>
                                        </tr>
                                        <?      $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
    
                        </div>
                        <div class="todas-tarefas">
                            <h3 class="titulo text-dark">Todas as tarefas</h3>
    
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mini-calendar">
                                        <div class="calendar-header">
                                            <div class="botoes">
                                                <button class="btn btn-padrao btn-escolha-calendario" data-tipo="1">Dia</button>
                                                <button class="btn btn-padrao-2 btn-escolha-calendario" data-tipo="2">Semana</button>
                                                <button class="btn btn-padrao-2 btn-escolha-calendario" data-tipo="3">Mês</button>
                                            </div>
                                            
                                            <?
                                            
                                            $meses = [
                                                1 => 'Janeiro',
                                                2 => 'Fevereiro',
                                                3 => 'Março',
                                                4 => 'Abril',
                                                5 => 'Maio',
                                                6 => 'Junho',
                                                7 => 'Julho',
                                                8 => 'Agosto',
                                                9 => 'Setembro',
                                                10 => 'Outubro',
                                                11 => 'Novembro',
                                                12 => 'Dezembro'
                                            ];
                                            
                                            $mesAtual = $meses[date('n')];
                                            ?>
                                            <div class="paginacao">
                                                <span>Período</span>
                                                <div class="setas" id="calendarControls">
                                                    <button class="btn seta" id="anteriorBotao"><i class="bi bi-arrow-left" ></i></button>
                                                    <span class="data" id="mesAnoDisplay"><?= $mesAtual ?> / <?= date('Y') ?></span>
                                                    <button class="btn seta" id="nextButton"><i class="bi bi-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="mini-calendar"></div>
                                        <div class="filtros-wrap">
                                            <span>Filtros</span>
                                            <div class="filtros">
                                                <label for="responsavel">Responsável</label>
                                                <select class="form-select" id="responsavel">
                                                </select>
                                                <label>Status</label>
                                                <select class="form-select" id="status-tarefas">
                                                    <option selected>Open this select menu</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                   
                                    <div id="calendar"></div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tabAnotacoes" role="tabpanel" aria-labelledby="tabAnotacoes-tab"
                    tabindex="0">
                    <div class="anotacoes-wrap">
                        <div class="text-form">
                            <span>Anotações</span>
                            <div class="d-flex gap-2">
                                <textarea class="form-control text-anotacao" id="exampleFormControlTextarea1"
                                    rows="3"></textarea>
                                <button class="btn btn-send" id="btn-send-anotacao"><i class="bi bi-send-fill"></i></button>
                            </div>
                        </div>
                        <div class="postadas-wrap">
                            <div class="row" id="tabelaAnotacoes">
                                <div class="col-lg-6">
                                    <div class="anotacao">
                                        <p class="anot-text"><div class="bg-carregando wi-400 he-20"></div>
                                                             <div class="bg-carregando wi-400 he-20"></div></p>
                                        <div class="content data"><span><i class="bi bi-calendar3"></i></span>
                                            <p><div class="bg-carregando wi-100 he-20"></div></p>
                                        </div>
                                        <div class="footer-anot">
                                            <div>
                                                <i class="bi bi-person-fill"></i>
                                                <span>Criado por: </span><span class="user"><div class="bg-carregando wi-400 he-20"></div></span>
                                            </div>
                                            <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </div>
    
                                </div>
                                <div class="col-lg-6">
                                    <div class="anotacao">
                                        <p class="anot-text"><div class="bg-carregando wi-400 he-20"></div>
                                                             <div class="bg-carregando wi-400 he-20"></div></p>
                                        <div class="content data"><span><i class="bi bi-calendar3"></i></span>
                                            <p><div class="bg-carregando wi-100 he-20"></div></p>
                                        </div>
                                        <div class="footer-anot">
                                            <div>
                                                <i class="bi bi-person-fill"></i>
                                                <span>Criado por: </span><span class="user"><div class="bg-carregando wi-400 he-20"></div></span>
                                            </div>
                                            <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
                <div class="tab-pane fade" id="tabAnexo" role="tabpanel" aria-labelledby="tabAnexo-tab" tabindex="0">
                    <div class="habilitacao-tab">
                        <div class="header-hab"> 
                            <button type="button" data-uploader="anexo" disabled class="btn btn-add anexoUploader"><i class="bi bi-plus-circle"></i><span class="text">Adicionar
                                    documento</span></button>
                            <div class="search-form">
                                <i class="bi bi-search"></i>
                                <input type="search" class="form-control btn-search" style="border: none !important;"
                                    placeholder="Pesquise Aqui">
                            </div>
                            <button class="btn btn-secundario-fill download-anexo">Download</button>
                        </div>
                        <div class="table-hab">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Nome do arquivo</th>
                                        <th scope="col">Arquivo</th>
                                        <th scope="col">Anexado em</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelaAnexos">
                                    <tr>
                                        <th scope="row">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        </th>
                                        <td class="nome"><div class="bg-carregando wi-100 he-20"></div></td>
                                        <td><div class="bg-carregando wi-200 he-20"></div></td>
                                        <td><div class="bg-carregando wi-100 he-20"></div></td>
                                        <td>
                                            <div class="options">
                                                <div class="eye"><i class="bi bi-eye"></i></div><button
                                                    class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tabHabilitacao" role="tabpanel" aria-labelledby="tabHabilitacao-tab"
                    tabindex="0">
                    <div class="habilitacao-tab">
                        <div class="header-hab">
                            <button type="button" class="btn btn-add anexoUploader" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" disabled data-uploader="habilitacao"><i class="bi bi-plus-circle"></i><span class="text">Adicionar
                                    documento</span></button>
                            <div class="search-form">
                                <i class="bi bi-search"></i>
                                <input type="search" class="form-control btn-search" style="border: none !important;"
                                    placeholder="Pesquise Aqui">
                            </div>
                            <button class="btn btn-secundario-fill download-habilitacao">Download</button>
                        </div>
                        <div class="table-hab">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Nome do arquivo</th>
                                        <th scope="col">Arquivo</th>
                                        <th scope="col">Anexado em</th>
                                        <th scope="col">Opções</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelaHabilitacoes">
                                    <tr>
                                        <th scope="row">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        </th>
                                        <td class="nome"><div class="bg-carregando wi-100 he-20"></div></td>
                                        <td><div class="bg-carregando wi-200 he-20"></div></td>
                                        <td><div class="bg-carregando wi-100 he-20"></div></td>
                                        <td>
                                            <div class="options">
                                                <div class="eye"><i class="bi bi-eye"></i></div>
                                                <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade primeiro" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="staticBackdropLabel">Nova Tarefa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-topo">
                    <div class="content"><span>Objeto:</span>
                        <p>Licitação Eletrônica * Escolha de proposta mais vantajosa para Aquisição de creme de leite
                        </p>
                    </div>
                    <div class="content"><span>Edital:</span>
                        <p>DL/45/2023</p>
                    </div>
                    <div class="content"><span>Orgão:</span>
                        <p>MINISTÉRIO DA DEFESA - Comando da Marinha</p>
                    </div>
                </div>

                <style>
                    button#btn-subtarefa {
                        background-color: var(--color-2);
                        /* Azul primário do Bootstrap */
                        color: #fff;
                        font-weight: 7000 font-size: 14px;
                        border: none;
                    }

                    button#btn-subtarefa i {
                        margin-right: 5px;
                    }
                </style>

                <div class="formularios-popup">
                    <div class="">
                        <label for="exampleFormControlInput1" class="form-label">Nome da tarefa</label>
                        <input type="search" class="form-control nome-tarefa obrigatory" name="nome"
                            placeholder="Nome da Tarefa">
                    </div>
                    <label for="exampleFormControlInput1" class="form-label">Subtarefas</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="bi bi-file-text"></i>
                        </span>
                        <input type="text" class="form-control nome-subtarefa" placeholder="Subtarefa">

                        <span class="input-group-text">
                            <i class="bi bi-calendar3-week"></i>
                        </span>
                        <input type="datetime-local" class="form-control data-subtarefa" placeholder="Data"
                            data-mascara="">

                        <button class="btn btn-primary" id="btn-subtarefa" type="button">
                            <i class="bi bi-check-lg"></i> Adicionar
                        </button>
                    </div>


                    <div class="subtarefas-wrap" id="subtarefasW">
                        <div class="subtarefa">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <div class="nome-subtarefa">
                                <p>Atividade sub 01</p>
                                <span>04/05/2024</span>
                            </div>
                            <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                        </div>
                        <div class="subtarefa">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <div class="nome-subtarefa">
                                <p>Atividade sub 01</p>
                                <span>04/05/2024</span>
                            </div>
                            <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                        </div>
                        <div class="subtarefa">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <div class="nome-subtarefa">
                                <p>Atividade sub 01</p>
                                <span>04/05/2024</span>
                            </div>
                            <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Prazo</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="bi bi-calendar3-week"></i></span>
                                <input type="datetime-local" class="form-control obrigatory" 
                                    placeholder="Data Prazo" name="prazo" aria-label="Username"
                                    aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Prioridade</label>
                            <select class="form-select obrigatory" name="prioridade"
                                aria-label="Default select example">
                                <option value="">Selecione uma opção</option>
                                <option value="Baixa">Baixa</option>
                                <option value="Média">Média</option>
                                <option value="Alta">Alta</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Será um usuário diferente?</label>
                            <select class="form-select" aria-label="Default select example" name="usuario"
                                id="select-usuario">
                                <option value="">Selecione uma opção</option>
                                <option value="0">Não</option>
                                <option selected value="1">Sim</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Nome do Usuario</label>
                            <div class="select-usuarios">
                                <select class="form-select obrigatory" id="usuarios-select-tipo" name="usuario_display">
                                    <option value="">Selecione uma opção</option>
                                </select>
                            </div>
                            <div class="input-usuarios">
                                <input type="text" class="form-control obrigatory" placeholder="Usuário" name="nome_responsavel"
                                    aria-label="Usuário">
                            </div>
                        </div>
                    </div>
                    <label for="exampleFormControlInput1" class="form-label">Anotações</label>
                    <div class="d-flex">
                        <textarea class="form-control" name="anotacao" id="exampleFormControlTextarea1"
                            rows="1"></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer segundo">
                <button type="button" class="btn btn-secundario-fill btn-concluir-tarefa">Concluir tarefa</button>
                <button type="button" class="btn btn-padrao btn-salvar-tarefa">Salvar tarefa</button>
                <button type="button" class="btn btn-padrao-2 btn-excluir-tarefa">Excluir tarefa</button>
            </div>
        </div>
    </div>
</div>

<!--Modais-->

<!--Modal Visualização-->
<div class="modal primeiro fade" id="modalAnexo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" id="modalAnexoContent">
            <div class="modal-header">
                <h1 class="modal-title"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <style>
                .modal-body iframe {
                    width: 100% !important;
                    height: 100% !important;
                    border: 0 !important;
                    object-fit: contain !important;
                }
            </style>
            <div class="modal-body he-500 w-100 position-relative overflow-hidden">

            </div>
            <div class="modal-footer one">
                <button type="button" class="btn btn-padrao" data-bs-dismiss="modal">Fechar Anexo</button>
            </div>
        </div>
    </div>
</div>

<!--Modal adicionar documentos-->
<div class="modal primeiro fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Adicione um documento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3"> 
                    <div class="col-12 col-lg-6 col-nome">
                        <div>
                            <label for="nomeUploader" class="form-label">Nome do Anexo</label> 
                            <input type="search" class="form-control obrigatory mb-3" id="nomeUploader" placeholder="Nome do Anexo"> 
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-data">
                        <div>
                            <label for="dataValidade" class="form-label">Data de Validade</label> 
                            <input type="date" class="form-control obrigatory mb-3" id="dataValidade">
                        </div>
                    </div>
                </div>
                <div class="upload d-none" id="uploader">
                    
                </div>
                <div class="tabela-wrap d-none" id="listaAnexos">
                    <h3 class="titulo">Documentos salvos</h3>
                    <div class="table-hab">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nome do arquivo</th>
                                    <th scope="col">Arquivo</th>
                                    <th scope="col">Anexado em</th>
                                    <th scope="col">Opções</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaHabilitacao">
                                <tr>
                                    <th scope="row">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    </th>
                                    <td class="nome"><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td><div class="bg-carregando wi-200 he-20"></div></td>
                                    <td><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td>
                                        <div class="options">
                                            <div class="eye"><i class="bi bi-eye"></i></div><button
                                                class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    </th>
                                    <td class="nome"><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td><div class="bg-carregando wi-200 he-20"></div></td>
                                    <td><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td>
                                        <div class="options">
                                            <div class="eye"><i class="bi bi-eye"></i></div><button
                                                class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    </th>
                                    <td class="nome"><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td><div class="bg-carregando wi-200 he-20"></div></td>
                                    <td><div class="bg-carregando wi-100 he-20"></div></td>
                                    <td>
                                        <div class="options">
                                            <div class="eye"><i class="bi bi-eye"></i></div><button
                                                class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer one">
                <button type="button" class="btn btn-padrao" id="btn-salva-upload">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Anotações-->
<div class="modal primeiro fade" id="modalAnotacao" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" id="modalAnexoContent">
            <div class="modal-header">
                <h1 class="modal-title">Editar Anotação</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control text-anotacao" id="textoAnotacao" rows="3"></textarea>
            </div>
            <div class="modal-footer one">
                <div class="d-flex gap-2 justify-content-between">
                    <button type="button" class="btn btn-padrao" id="btn-confirma-anotacao" style="background: var(--color-2) !important;">Confirmar</button>
                    <button type="button" class="btn btn-padrao-2" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>    
</div>

<!-- Modal Items -->
<? include __DIR__.'/../modal-items.php' ?>
<? include __DIR__.'/../modal-edital.php' ?>

 <!--<div class="calendar">-->
                                    <!--    <div class="days">-->
                                    <!--        <span>Domingo</span>-->
                                    <!--        <span>Segunda</span>-->
                                    <!--        <span>Terça</span>-->
                                    <!--        <span>Quarta</span>-->
                                    <!--        <span>Quinta</span>-->
                                    <!--        <span>Sexta</span>-->
                                    <!--        <span>Sábado</span>-->
    
                                    <!--    </div>-->
    
                                    <!--    <div class="bloco-dias-wrap">-->
                                    <!--        <div class="blocos-dias">-->
                                    <!--            <div class="bloco-dia">-->
                                    <!--                <span class="dia">1</span>-->
                                    <!--                <div class="notificacao">-->
                                    <!--                    Boletim 01 - 15:00-->
                                    <!--                </div>-->
                                    <!--                <div class="notificacao visualizado">-->
                                    <!--                    Boletim 01 - 15:00-->
                                    <!--                </div>-->
                                    <!--            </div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">1</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">2</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">3</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">4</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">5</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">6</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">7</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">8</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">9</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">10</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">11</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">12</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">13</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">14</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">15</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">16</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">17</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">18</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">19</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">20</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">21</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">22</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">23</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">24</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">25</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">26</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">27</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">28</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">29</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">31</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">1</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">2</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">3</span></div>-->
                                    <!--            <div class="bloco-dia"><span class="dia">4</span></div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
    
    
                                    <!--    <div class="infos">-->
                                    <!--        <div class="info">-->
                                    <!--            <div class="notif"></div>-->
                                    <!--            <span>Não visualizado</span>-->
                                    <!--        </div>-->
                                    <!--        <div class="info">-->
                                    <!--            <div class="notif"></div>-->
                                    <!--            <span>Visualizado</span>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->

<!--<div class="mini-calendar-body">-->
                                        <!--    <div class="days">-->
                                        <!--        <span>D</span>-->
                                        <!--        <span>S</span>-->
                                        <!--        <span>T</span>-->
                                        <!--        <span>Q</span>-->
                                        <!--        <span>Q</span>-->
                                        <!--        <span>S</span>-->
                                        <!--        <span>S</span>-->
                                        <!--    </div>-->
                                        <!--    <div class="days-wrap">-->
                                        <!--        <div class="day">1</div>-->
                                        <!--        <div class="day">2</div>-->
                                        <!--        <div class="day">3</div>-->
                                        <!--        <div class="day">4</div>-->
                                        <!--        <div class="day">5</div>-->
                                        <!--        <div class="day">6</div>-->
                                        <!--        <div class="day">7</div>-->
                                        <!--        <div class="day">8</div>-->
                                        <!--        <div class="day">1</div>-->
                                        <!--        <div class="day">9</div>-->
                                        <!--        <div class="day">10</div>-->
                                        <!--        <div class="day">11</div>-->
                                        <!--        <div class="day">12</div>-->
                                        <!--        <div class="day">13</div>-->
                                        <!--        <div class="day">14</div>-->
                                        <!--        <div class="day">15</div>-->
                                        <!--        <div class="day">16</div>-->
                                        <!--        <div class="day">17</div>-->
                                        <!--        <div class="day">18</div>-->
                                        <!--        <div class="day">19</div>-->
                                        <!--        <div class="day">20</div>-->
                                        <!--        <div class="day">21</div>-->
                                        <!--        <div class="day">22</div>-->
                                        <!--        <div class="day">23</div>-->
                                        <!--        <div class="day">24</div>-->
                                        <!--        <div class="day">25</div>-->
                                        <!--        <div class="day">26</div>-->
                                        <!--        <div class="day">27</div>-->
                                        <!--        <div class="day">28</div>-->
                                        <!--        <div class="day">29</div>-->
                                        <!--        <div class="day">30</div>-->
                                        <!--        <div class="day">31</div>-->
                                        <!--        <div class="day">1</div>-->
                                        <!--        <div class="day">2</div>-->
                                        <!--        <div class="day">3</div>-->
                                        <!--    </div>-->
                                        <!--</div>-->