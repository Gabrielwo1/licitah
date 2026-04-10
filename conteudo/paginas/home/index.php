

<style>
    #select-segmento{
        z-index:9999999;
    }
</style>    
    
    
    <?
    include __DIR__.'/../montador.php';
    $montador = new Montador(basename(dirname(__FILE__)));
    

        
    

    ?>
    <div class="container">
        <div class="container-fluid" id="inicio">
            <div class="oportunidades">
                <div class="info">
                    <div class="notif"></div><h4>Oportunidades do dia: </h4><span class="total-licitacoes"><div class="bg-carregando wi-100 he-20"></div></span>
                </div>
                <div>
                    <button class="btn btn-terciario" data-bs-target="#exampleModalToggle" id="modal-de-oportunidades">Definir Oportunidades</button>
                    <a class="btn btn-terciario" href="/licitacoes/oportunidades">Ver oportunidades</a>
                </div>
                
            </div>
        
            <div class="suas-licitacoes">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="titulo">Suas licitações</h3>
                    <a class="btn btn-terciario" href="/licitacoes/filtro" id="btnFiltro">Procurar Licitações</a>
                </div>
                <div class="licitacoes-wrap" id="sliderLicitacoes">
                    <div class="d-flex">
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="licitacao">
                            <div class="content objeto he-60 bg-carregando mt-2 w-100"></div>
                            <div class="content edital he-40 bg-carregando mt-2 w-100"></div>
                            <div class="content data he-40 bg-carregando mt-2 w-100"></div>
                            <div class=" he-40 bg-carregando mt-2 w-100"></div>
            
                            <div class="progresso">
                                <div class="labels bg-carregando he-100 mt-2 w-100"></div>
                                <div class="bg-carregando mt-2 he-20 w-100" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-lg-8">
                    <div class="prox-tarefas">
                        <h3 class="titulo">Próximas tarefas</h3>
                        <div class="table-wrap">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tarefa</th>
                                    <th scope="col">Prazo</th>
                                    <th scope="col">Prioridade</th>
                                  </tr>
                                </thead>
                                <tbody id="tabelaTarefas">
                                  <tr>
                                    <th scope="row"><div class="eye"><i class="bi bi-eye"></i></div></th>
                                    <td><span class="nome"><div class="bg-carregando he-15 w-100"></div></span></td>
                                    <td><span><div class="bg-carregando he-15 w-100"></div></span></td>
                                    <td>
                                        <div class="progresso">
                                            <div class="labels"><span class="data"><div class="bg-carregando he-15 w-100"></div></span><div class="restam"><div class="bg-carregando he-15 w-100"></div></div></div>
                                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar" style="width: 25%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><div class="notificacao"><div class="bg-carregando he-15 wi-100"></div></div></td>
                                  </tr>
                                  
                                </tbody>
                              </table>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="notificacoes">
                        <h3 class="titulo">Notificações</h3>
                        <div class="notificacoes-wrap" id="notificacoesWrap">
                            <div class="notificacao-2 justify-content-center">
                                <div class="left">
                                    <div class="tarefa-wrap">
                                        <p>Nenhuma tarefa registrada.</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="titulo">Empresa</h2>
                </div>
            </div>
            
            <div class="row" id="lista-empresas">
                
            </div>
        </div>
        <div class="modal fade primeiro" id="modalTarefas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="staticBackdropLabel">Nova Tarefa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-topo">
                            <div class="content"><span>Objeto:</span>
                                <p>Licitação Eletrônica * Escolha de proposta mais vantajosa para Aquisição de creme de leite</p>
                            </div>
                            <div class="content"><span>Processo:</span>
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
                                <input type="text" class="form-control nome-subtarefa" placeholder="Subtarefa" disabled="true">
        
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3-week"></i>
                                </span>
                                <input type="text" class="form-control data-subtarefa mascaraInput" placeholder="Data" disabled="true" data-mascara="9">
        
                                <button class="btn btn-primary disabled" id="btn-subtarefa" type="button">
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
                                        <input type="text" class="form-control obrigatory mascaraInput" data-mascara="9"
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
                                        <select class="form-select" name="usuario_display">
                                            <option value="">Selecione uma opção</option>
                                        </select>
                                    </div>
                                    <div class="input-usuarios">
                                        <input type="text" class="form-control" placeholder="Usuário" name="nome_responsavel"
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
                        <button type="button" class="btn btn-secundario-fill btn-concluir-tarefa disabled">Concluir tarefa</button>
                        <button type="button" class="btn btn-padrao btn-salvar-tarefa disabled">Salvar tarefa</button>
                        <button type="button" class="btn btn-padrao-2 btn-excluir-tarefa disabled">Excluir tarefa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?

    include __DIR__.'/../../modulos/licitacoes/publico/modais-oportunidades.php';
?>



