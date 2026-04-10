<div class="container-fluid" id="gerenciando">
    

    <div class="header-boletim">
      <div class="boletins-wrap">
          <div class="cabecalho">
              <button class="btn favorite"><i class="bi bi-star"></i>Favoritar</button>
              <div class="att">
                  <span class="atualizada">Atualizada em: </span><span class="data">20/06/2023 16:51</span>
              </div>
          </div>
          <div class="infos">
              <div class="content"><span>Objeto:</span><p>Licitação Eletrônica * Escolha de proposta mais vantajosa para Aquisição de creme de leite </p></div>
              <div class="content"><span>Data:</span><p>Abertura: 24/06/2024 07:59</p></div>
              <div class="content"><span>Edital:</span><p>DL/45/2023</p></div>
              <div class="content"><span>Orgão:</span><p>MINISTÉRIO DA DEFESA - Comando da Marinha</p></div>
              <div class="content"><span>Cidade:</span><p>Rio de Janeiro - RJ</p></div>
              <div class="content"><div class="bout-valor"><span>Situação: </span><div class="notificacao red">Urgente</div><div class="valor-estimado">Valor estimado: <span class="valor">R$ 3.035</span></div></div></div>
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
          <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Suas Tarefas</button>
          <div class="separador"></div>
          <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Anotações</button>
          <div class="separador"></div>
          <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Anexos</button>
          <div class="separador"></div>
          <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false">Habilitação</button>
        </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
          <div class="tasks">
            <div class="tasks-tab">
              <div class="tasks-wrap">
                <div class="task day">
                  <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="title">Tarefas do dia</span>
                  </div>
                  <h3>12</h3>
                </div>
                <div class="task">
                  <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="title">Concluídas</span>
                  </div>
                  <h3>12</h3>
                </div>
                <div class="task">
                  <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-clock-fill"></i>
                    <span class="title">Em progresso</span>
                  </div>
                  <h3>12</h3>
                </div>
                <div class="task">
                  <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span class="title">Em atraso</span>
                  </div>
                  <h3>12</h3>
                </div>
              </div>
              <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-add"><i class="bi bi-plus-circle"></i><span class="text">Adicionar tarefa</span></button>
              
              <!-- PRIMEIRO FORMULARIO -->

              <div class="modal fade primeiro" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title" id="staticBackdropLabel">Crie sua nova tarefa</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="modal-topo">
                        <div class="content"><span>Objeto:</span><p>Licitação Eletrônica * Escolha de proposta mais vantajosa para Aquisição de creme de leite </p></div>
                        <div class="content"><span>Edital:</span><p>DL/45/2023</p></div>
                        <div class="content"><span>Orgão:</span><p>MINISTÉRIO DA DEFESA - Comando da Marinha</p></div>
                      </div>
                      <div class="formularios-popup">
                        <div class="">
                          <label for="exampleFormControlInput1" class="form-label">Email address</label>
                          <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                        </div>
                        <label for="exampleFormControlInput1" class="form-label">Subtarefas</label>
                        <div class="input-group">
                          <span class="input-group-text" id="basic-addon1"><i class="bi bi-plus-circle"></i></span>
                          <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Prazo</label>
                            <div class="input-group">
                              <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar3-week"></i></span>
                              <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                          </div>
                          <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Responsável</label>
                            <select class="form-select" aria-label="Default select example">
                              <option selected>Open this select menu</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Prioridade</label>
                            <select class="form-select" aria-label="Default select example">
                              <option selected>Open this select menu</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>
                          <div class="col-6">
                            <label for="exampleFormControlInput1" class="form-label">Anexos</label>
                            <div class="input-group">
                              <span class="input-group-text" id="basic-addon1"><i class="bi bi-upload"></i></span>
                              <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                          </div>
                        </div>
                        <label for="exampleFormControlInput1" class="form-label">Anotações</label>
                        <div class="d-flex gap-2">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="1"></textarea>
                          <button class="btn btn-send"><i class="bi bi-send-fill"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-padrao" data-bs-dismiss="modal">Salvar tarefa</button>
                      <button type="button" class="btn btn-padrao-2">Excluir tarefa</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="prox-tarefas">
              <h3 class="titulo">Próximas tarefas</h3>
              <div class="table-wrap">
                  <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Tarefa</th>
                          <th scope="col">Licitação</th>
                          <th scope="col">Prazo</th>
                          <th scope="col">Prioridade</th>
                        </tr>
                      </thead>
                      <tbody id="tabelaTarefas">
                        <!-- SEGUNDO FORMULARIO -->
                        <tr>
                          <th scope="row"><button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" class="eye btn" ><i class="bi bi-eye"></i></button></th>
                          <td><span class="nome">Nome da tarefa</span></td>
                          <td><span>Fornecimento de flores</span></td>
                          <td>
                              <div class="progresso">
                                  <div class="labels"><span class="data">22/07/24</span><div class="restam">Restam<span class="time">2 horas</span></div></div>
                                  <div class="progress progress-green" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                      <div class="progress-bar" style="width: 25%"></div>
                                  </div>
                              </div>
                          </td>
                          <td><div class="notificacao yellow">Médio</div></td>
                        </tr>
                        <tr>
                          <th scope="row"><button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" class="eye btn" ><i class="bi bi-eye"></i></button></th>
                          <td><span class="nome">Nome da tarefa</span></td>
                          <td><span>Fornecimento de flores</span></td>
                          <td>
                              <div class="progresso">
                                  <div class="labels"><span class="data">22/07/24</span><div class="restam">Restam<span class="time">2 horas</span></div></div>
                                  <div class="progress progress-green" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                      <div class="progress-bar" style="width: 25%"></div>
                                  </div>
                              </div>
                          </td>
                          <td><div class="notificacao yellow">Médio</div></td>
                        </tr>
                        <tr>
                          <th scope="row"><div class="eye"><i class="bi bi-eye"></i></div></th>
                          <td><span class="nome">Nome da tarefa</span></td>
                          <td><span>Fornecimento de flores</span></td>
                          <td>
                              <div class="progresso">
                                  <div class="labels"><span class="data">22/07/24</span><div class="restam">Restam<span class="time">2 horas</span></div></div>
                                  <div class="progress progress-green" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                      <div class="progress-bar" style="width: 25%"></div>
                                  </div>
                              </div>
                          </td>
                          <td><div class="notificacao yellow">Médio</div></td>
                        </tr>
                        <tr>
                          <th scope="row"><div class="eye"><i class="bi bi-eye"></i></div></th>
                          <td><span class="nome">Nome da tarefa</span></td>
                          <td><span>Fornecimento de flores</span></td>
                          <td>
                              <div class="progresso">
                                  <div class="labels"><span class="data">22/07/24</span><div class="restam">Restam<span class="time">2 horas</span></div></div>
                                  <div class="progress progress-green" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                      <div class="progress-bar" style="width: 25%"></div>
                                  </div>
                              </div>
                          </td>
                          <td><div class="notificacao yellow">Médio</div></td>
                        </tr>
                        <tr>
                          <th scope="row"><div class="eye"><i class="bi bi-eye"></i></div></th>
                          <td><span class="nome">Nome da tarefa</span></td>
                          <td><span>Fornecimento de flores</span></td>
                          <td>
                              <div class="progresso">
                                  <div class="labels"><span class="data">22/07/24</span><div class="restam">Restam<span class="time">2 horas</span></div></div>
                                  <div class="progress progress-green" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                      <div class="progress-bar" style="width: 25%"></div>
                                  </div>
                              </div>
                          </td>
                          <td><div class="notificacao yellow">Médio</div></td>
                        </tr>
                        
                      </tbody>
                    </table>
              </div>
              
            </div>
            <div class="todas-tarefas">
              <h3 class="titulo">Todas as tarefas</h3>

              <div class="row">
                <div class="col-lg-3">
                  <div class="mini-calendar">
                    <div class="calendar-header">
                      <div class="botoes">
                          <button class="btn btn-padrao-2">Semanal</button>
                          <button class="btn btn-padrao-2">Mensal</button>
                          <button class="btn btn-padrao">Anual</button>
                      </div>
                      <div class="paginacao">
                        <span>Período</span>
                        <div class="setas">
                            <button class="btn seta"><i class="bi bi-arrow-left"></i></button>
                            <span class="data">Setembro / 2023</span>
                            <button class="btn seta"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                    </div>
                    <div class="mini-calendar-body">
                      <div class="days">
                        <span>D</span>
                        <span>S</span>
                        <span>T</span>
                        <span>Q</span>
                        <span>Q</span>
                        <span>S</span>
                        <span>S</span>
                      </div>
                      <div class="days-wrap">
                        <div class="day">1</div>
                        <div class="day">2</div>
                        <div class="day">3</div>
                        <div class="day">4</div>
                        <div class="day">5</div>
                        <div class="day">6</div>
                        <div class="day">7</div>
                        <div class="day">8</div>
                        <div class="day">1</div>
                        <div class="day">9</div>
                        <div class="day">10</div>
                        <div class="day">11</div>
                        <div class="day">12</div>
                        <div class="day">13</div>
                        <div class="day">14</div>
                        <div class="day">15</div>
                        <div class="day">16</div>
                        <div class="day">17</div>
                        <div class="day">18</div>
                        <div class="day">19</div>
                        <div class="day">20</div>
                        <div class="day">21</div>
                        <div class="day">22</div>
                        <div class="day">23</div>
                        <div class="day">24</div>
                        <div class="day">25</div>
                        <div class="day">26</div>
                        <div class="day">27</div>
                        <div class="day">28</div>
                        <div class="day">29</div>
                        <div class="day">30</div>
                        <div class="day">31</div>
                        <div class="day">1</div>
                        <div class="day">2</div>
                        <div class="day">3</div>
                      </div>
                    </div>
                    <div class="filtros-wrap">
                      <span>Filtros</span>
                      <div class="filtros">
                        <label>Responsável</label>
                        <select class="form-select" aria-label="Default select example">
                          <option selected>Open this select menu</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                        <label>Status</label>
                        <select class="form-select" aria-label="Default select example">
                          <option selected>Open this select menu</option>
                          <option value="1">One</option>
                          <option value="2">Two</option>
                          <option value="3">Three</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-9">
                  <div class="calendar">
                    <div class="days">
                        <span>Domingo</span>
                        <span>Segunda</span>
                        <span>Terça</span>
                        <span>Quarta</span>
                        <span>Quinta</span>
                        <span>Sexta</span>
                        <span>Sábado</span>
                        
                    </div>
        
                    <div class="bloco-dias-wrap">
                        <div class="blocos-dias">
                            <div class="bloco-dia">
                                <span class="dia">1</span>
                                <div class="notificacao">
                                    Boletim 01 - 15:00
                                </div>
                                <div class="notificacao visualizado">
                                    Boletim 01 - 15:00
                                </div>
                            </div>
                            <div class="bloco-dia"><span class="dia">1</span></div>
                            <div class="bloco-dia"><span class="dia">2</span></div>
                            <div class="bloco-dia"><span class="dia">3</span></div>
                            <div class="bloco-dia"><span class="dia">4</span></div>
                            <div class="bloco-dia"><span class="dia">5</span></div>
                            <div class="bloco-dia"><span class="dia">6</span></div>
                            <div class="bloco-dia"><span class="dia">7</span></div>
                            <div class="bloco-dia"><span class="dia">8</span></div>
                            <div class="bloco-dia"><span class="dia">9</span></div>
                            <div class="bloco-dia"><span class="dia">10</span></div>
                            <div class="bloco-dia"><span class="dia">11</span></div>
                            <div class="bloco-dia"><span class="dia">12</span></div>
                            <div class="bloco-dia"><span class="dia">13</span></div>
                            <div class="bloco-dia"><span class="dia">14</span></div>
                            <div class="bloco-dia"><span class="dia">15</span></div>
                            <div class="bloco-dia"><span class="dia">16</span></div>
                            <div class="bloco-dia"><span class="dia">17</span></div>
                            <div class="bloco-dia"><span class="dia">18</span></div>
                            <div class="bloco-dia"><span class="dia">19</span></div>
                            <div class="bloco-dia"><span class="dia">20</span></div>
                            <div class="bloco-dia"><span class="dia">21</span></div>
                            <div class="bloco-dia"><span class="dia">22</span></div>
                            <div class="bloco-dia"><span class="dia">23</span></div>
                            <div class="bloco-dia"><span class="dia">24</span></div>
                            <div class="bloco-dia"><span class="dia">25</span></div>
                            <div class="bloco-dia"><span class="dia">26</span></div>
                            <div class="bloco-dia"><span class="dia">27</span></div>
                            <div class="bloco-dia"><span class="dia">28</span></div>
                            <div class="bloco-dia"><span class="dia">29</span></div>
                            <div class="bloco-dia"><span class="dia">31</span></div>
                            <div class="bloco-dia"><span class="dia">1</span></div>
                            <div class="bloco-dia"><span class="dia">2</span></div>
                            <div class="bloco-dia"><span class="dia">3</span></div>
                            <div class="bloco-dia"><span class="dia">4</span></div>
                        </div>
                    </div>
                    
        
                    <div class="infos">
                        <div class="info">
                            <div class="notif"></div>
                            <span>Não visualizado</span>
                        </div>
                        <div class="info">
                            <div class="notif"></div>
                            <span>Visualizado</span>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
          <div class="anotacoes-wrap">
            <div class="text-form">
              <span>Anotações</span>
              <div class="d-flex gap-2">
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                <button class="btn btn-send"><i class="bi bi-send-fill"></i></button>
              </div>
            </div>
            <div class="postadas-wrap">
              <div class="row">
                  <div class="col-lg-6">

                    <div class="anotacao">
                      <p class="anot-text">Lorem ipsum dolor sit amet consectetur. Molestie cras sollicitudin eu pharetra a accumsan aenean nec. Laoreet neque urna convallis massa at dui elementum commodo in.</p>
                      <div class="content data"><span><i class="bi bi-calendar3"></i></span><p>13/05/2024</p></div>
                      <div class="footer-anot">
                        <div><i class="bi bi-person-fill"></i><span>Criado por: </span><span class="user">Caique Tancredi</span></div>
                        <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-6">

                    <div class="anotacao">
                      <p class="anot-text">Lorem ipsum dolor sit amet consectetur. Molestie cras sollicitudin eu pharetra a accumsan aenean nec. Laoreet neque urna convallis massa at dui elementum commodo in.</p>
                      <div class="content data"><span><i class="bi bi-calendar3"></i></span><p>13/05/2024</p></div>
                      <div class="footer-anot">
                        <div><i class="bi bi-person-fill"></i><span>Criado por: </span><span class="user">Caique Tancredi</span></div>
                        <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-6">

                    <div class="anotacao">
                      <p class="anot-text">Lorem ipsum dolor sit amet consectetur. Molestie cras sollicitudin eu pharetra a accumsan aenean nec. Laoreet neque urna convallis massa at dui elementum commodo in.</p>
                      <div class="content data"><span><i class="bi bi-calendar3"></i></span><p>13/05/2024</p></div>
                      <div class="footer-anot">
                        <div><i class="bi bi-person-fill"></i><span>Criado por: </span><span class="user">Caique Tancredi</span></div>
                        <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-6">

                    <div class="anotacao">
                      <p class="anot-text">Lorem ipsum dolor sit amet consectetur. Molestie cras sollicitudin eu pharetra a accumsan aenean nec. Laoreet neque urna convallis massa at dui elementum commodo in.</p>
                      <div class="content data"><span><i class="bi bi-calendar3"></i></span><p>13/05/2024</p></div>
                      <div class="footer-anot">
                        <div><i class="bi bi-person-fill"></i><span>Criado por: </span><span class="user">Caique Tancredi</span></div>
                        <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                      </div>
                    </div>

                  </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
          <div class="habilitacao-tab">
            <div class="header-hab">
              <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle"></i><span class="text">Adicionar documento</span></button>

              <div class="modal primeiro fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Adicione um documento</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="upload">
                        <label for="exampleFormControlInput1" class="form-label">Busque o seu documento</label>
                        <div class="input-group">
                          <span class="input-group-text" id="basic-addon1"><i class="bi bi-upload"></i></span>
                          <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                      </div>
                      <div class="tabela-wrap">
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
                            <tbody>
                              <tr>
                                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                                <td class="nome">Foto de nota da produção</td>
                                <td>214124153-54364.png</td>
                                <td>12/05/2024</td>
                                <td><div class="eye"><i class="bi bi-eye"></i></div></td>
                              </tr>
                              <tr>
                                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                                <td class="nome">Foto de nota da produção</td>
                                <td>214124153-54364.png</td>
                                <td>12/05/2024</td>
                                <td><div class="eye"><i class="bi bi-eye"></i></div></td>
                              </tr>
                              <tr>
                                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                                <td class="nome">Foto de nota da produção</td>
                                <td>214124153-54364.png</td>
                                <td>12/05/2024</td>
                                <td><div class="eye"><i class="bi bi-eye"></i></div></td>
                              </tr>
                              <tr>
                                <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                                <td class="nome">Foto de nota da produção</td>
                                <td>214124153-54364.png</td>
                                <td>12/05/2024</td>
                                <td><div class="eye"><i class="bi bi-eye"></i></div></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer one">
                      <button type="button" class=" btn btn-padrao" data-bs-dismiss="modal">Salvar</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="search-form">
                <i class="bi bi-search"></i>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Pesquise Aqui">
              </div>
              <button class="btn btn-secundario-fill">Download</button>
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
                <tbody>
                  <tr>
                    <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                    <td class="nome">Foto de nota da produção</td>
                    <td>214124153-54364.png</td>
                    <td>12/05/2024</td>
                    <td><div class="options"><div class="eye"><i class="bi bi-eye"></i></div><button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button></div></td>
                  </tr>
                  <tr>
                    <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                    <td class="nome">Foto de nota da produção</td>
                    <td>214124153-54364.png</td>
                    <td>12/05/2024</td>
                    <td><div class="options"><div class="eye"><i class="bi bi-eye"></i></div><button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button></div></td>
                  </tr>
                  <tr>
                    <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                    <td class="nome">Foto de nota da produção</td>
                    <td>214124153-54364.png</td>
                    <td>12/05/2024</td>
                    <td><div class="options"><div class="eye"><i class="bi bi-eye"></i></div><button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button></div></td>
                  </tr>
                  <tr>
                    <th scope="row"><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"></th>
                    <td class="nome">Foto de nota da produção</td>
                    <td>214124153-54364.png</td>
                    <td>12/05/2024</td>
                    <td><div class="options"><div class="eye"><i class="bi bi-eye"></i></div><button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button></div></td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade primeiro" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title" id="staticBackdropLabel">Tarefa: <span class="nome-formulario">Leitura edital</span></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="status">
              <div class="status-icon andamento"><i class="bi bi-clock-fill"></i></div>
              <span>Em andamento</span>
            </div>

            <div class="modal-topo">
              <div class="content"><span>Objeto:</span><p>Licitação Eletrônica * Escolha de proposta mais vantajosa para Aquisição de creme de leite </p></div>
              <div class="content"><span>Edital:</span><p>DL/45/2023</p></div>
              <div class="content"><span>Orgão:</span><p>MINISTÉRIO DA DEFESA - Comando da Marinha</p></div>
            </div>

            <div class="formularios-popup">
              <div class="">
                <label for="exampleFormControlInput1" class="form-label">Nome da tarefa</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Leitura do edital">
              </div>
              <label for="exampleFormControlInput1" class="form-label">Subtarefas</label>
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1"><i class="bi bi-plus-circle"></i></span>
                <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                <span class="input-group-text data"><i class="bi bi-calendar3-week"></i> 04/05/2024</span>
              </div>

              <div class="subtarefas-wrap">
                <div class="subtarefa">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <div class="nome-subtarefa">
                    <p>Atividade sub 01</p>
                  </div>
                  <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                </div>
                <div class="subtarefa">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <div class="nome-subtarefa">
                    <p>Atividade sub 01</p>
                  </div>
                  <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                </div>
                <div class="subtarefa">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <div class="nome-subtarefa">
                    <p>Atividade sub 01</p>
                  </div>
                  <button class="btn btn-trash"><i class="bi bi-trash-fill"></i></button>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <label for="exampleFormControlInput1" class="form-label">Prazo</label>
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar3-week"></i></span>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                  </div>
                </div>
                <div class="col-6">
                  <label for="exampleFormControlInput1" class="form-label">Responsável</label>
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label for="exampleFormControlInput1" class="form-label">Prioridade</label>
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="exampleFormControlInput1" class="form-label">Anexos</label>
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-upload"></i></span>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                  </div>
                </div>
              </div>
              <label for="exampleFormControlInput1" class="form-label">Anotações</label>
              <div class="d-flex gap-2">
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="1"></textarea>
                <button class="btn btn-send"><i class="bi bi-send-fill"></i></button>
              </div>
            </div>

          </div>

          <div class="modal-footer segundo">
            <button type="button" class="btn btn-secundario-fill" data-bs-dismiss="modal">Concluir tarefa</button>
            <button type="button" class="btn btn-padrao" data-bs-dismiss="modal">Salvar tarefa</button>
            <button type="button" class="btn btn-padrao-2">Excluir tarefa</button>
          </div>
        </div>
      </div>
    </div>
    
</div>