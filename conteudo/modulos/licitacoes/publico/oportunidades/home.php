<div class="container" id="calendar-page">
    <div class="header-oportunities">
        <h3 class="titulo">Encontre novas oportunidades para o seu negócio!</h3>
        <p>A Licitah encontra as oportunidades adequadas para o seu segmento, aproveite e alavanque a sua empresa com novas licitações</p>
        
        <?
            include __DIR__.'/../card-oportunidades.php';
        ?>
        <div class="oportunities-block">
            <div class="row">
                <div class="col-4">
                    <div class="oportunitie-block position-relative">
                        <div class="title">
                            <div class="notif"></div>
                            <h4>Oportunidades do Dia:</h4>
                        </div>
                        <div class="licitacoes total-dia"><div class="bg-carregando he-15 w-100"></div></div>
                        <button class="btn p-0 stretched-link btn-oportunidade" data-filtro="1"></button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="oportunitie-block position-relative">
                        <div class="title">
                            <div class="notif"></div>
                            <h4>Oportunidades da Semana:</h4>
                        </div>
                        <div class="licitacoes total-semana"><div class="bg-carregando he-15 w-100"></div></div>
                        <button class="btn p-0 stretched-link btn-oportunidade" data-filtro="2"></button>
                    </div>
                </div>
                <div class="col-4">
                    <div class="oportunitie-block position-relative">
                        <div class="title">
                            <div class="notif"></div>
                            <h4>Oportunidades do Mês:</h4>
                        </div>
                        <div class="licitacoes total-mes"><div class="bg-carregando he-15 w-100"></div></div>
                        <button class="btn p-0 stretched-link btn-oportunidade" data-filtro="3"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #calendar .notificacao{
            background: var(--color-2);
        }
    </style>
    <div class="calendar">
        <div class="calendar-header mb-3">
            <div class="paginacao">
                <span>Período</span>
                <div class="setas">
                    <button class="btn seta" id="anteriorBotao"><i class="bi bi-arrow-left"></i></button>
                    <span class="data" id="mesAnoDisplay">Setembro / 2023</span>
                    <button class="btn seta" id="nextButton"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <div class="botoes">
                <button class="btn btn-padrao-2 btn-escolha-calendario" data-tipo=1>Semanal</button>
                <button class="btn btn-padrao btn-escolha-calendario" data-tipo=2>Mensal</button>
            </div>
        </div>
        
        <div id="calendar">
            <div class="bg-carregando he-600 w-100"></div>
        </div>
        <!--<div class="days">-->
        <!--    <span>Domingo</span>-->
        <!--    <span>Segunda</span>-->
        <!--    <span>Terça</span>-->
        <!--    <span>Quarta</span>-->
        <!--    <span>Quinta</span>-->
        <!--    <span>Sexta</span>-->
        <!--    <span>Sábado</span>-->
            
        <!--</div>-->

        <!--<div class="bloco-dias-wrap">-->
        <!--    <div class="blocos-dias">-->
        <!--        <div class="bloco-dia">-->
        <!--            <span class="dia">1</span>-->
        <!--            <div class="notificacao">-->
        <!--                Boletim 01 - 15:00-->
        <!--            </div>-->
        <!--            <div class="notificacao visualizado">-->
        <!--                Boletim 01 - 15:00-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="bloco-dia"><span class="dia">1</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">2</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">3</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">4</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">5</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">6</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">7</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">8</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">9</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">10</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">11</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">12</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">13</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">14</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">15</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">16</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">17</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">18</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">19</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">20</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">21</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">22</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">23</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">24</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">25</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">26</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">27</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">28</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">29</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">31</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">1</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">2</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">3</span></div>-->
        <!--        <div class="bloco-dia"><span class="dia">4</span></div>-->
        <!--    </div>-->
        <!--</div>-->
        

        <!--<div class="infos">-->
        <!--    <div class="info">-->
        <!--        <div class="notif"></div>-->
        <!--        <span>Não visualizado</span>-->
        <!--    </div>-->
        <!--    <div class="info">-->
        <!--        <div class="notif"></div>-->
        <!--        <span>Visualizado</span>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
</div>
<div class="modal primeiro fade" tabindex="-1" aria-labelledby="modalOportunidades" id="modalOportunidades">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background-color: #F6F8FC">
            <div class="modal-header">
                <h5 class="modal-title">Suas Oportunidades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-3" id="boletinsWrap">
                    <div class="nenhum-registro d-none">
                        <div class="d-flex justify-content-center">
                            <div class="fs-26 fw-700">
                                Nenhuma Oportunidade Encontrada!
                            </div>
                        </div>
                    </div>
                    <?
                        $i = 0;
                        
                        while($i < 2){
                            ?>
                            <div class="boletins-wrap">
                                <div class="cabecalho">
                                    <div>
                                        <div class="d-flex gap-2">
                                            <button class="btn favorite"><div class="bg-carregando he-30"></div></button>
                                            <button class="btn favorite"><div class="bg-carregando he-30"></div></button>
                                        </div>
                                    </div>
                                    <div class="att">
                                        <div class="bg-carregando w-100 he-30"></div>
                                    </div>
                                </div>
                                <div class="infos">
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                    <div class="content"><div class="bg-carregando w-100 he-15"></div></div>
                                </div>
                                <div class="actions">
                                    <button class="btn download"><div class="bg-carregando he-30"></div></button>
                                    <button class="btn itens"><div class="bg-carregando he-30"></div></button>
                                </div>
                            </div>
                            <?
                            $i++;
                        }
                    ?>
                   
                    <!--<div class="boletins-wrap">-->
                    <!--    <div class="cabecalho">-->
                    <!--        <div>-->
                    <!--            <div class="d-flex gap-2">-->
                    <!--                <button class="btn favorite"><div class="bg-carregando he-30"></div></button>-->
                    <!--                <button class="btn favorite"><div class="bg-carregando he-30"></div></button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <div class="att">-->
                    <!--            <div class="bg-carregando w-100 he-30"></div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="infos">-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--    </div>-->
                    <!--    <div class="actions">-->
                    <!--        <button class="btn download"><div class="bg-carregando he-30"></div></button>-->
                    <!--        <button class="btn itens"><div class="bg-carregando he-30"></div></button>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="boletins-wrap">-->
                    <!--    <div class="cabecalho">-->
                    <!--        <div>-->
                    <!--            <div class="d-flex gap-2">-->
                    <!--                <button class="btn favorite"><div class="bg-carregando he-30"></div></button>-->
                    <!--                <button class="btn favorite"><div class="bg-carregando he-30"></div></button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <div class="att">-->
                    <!--            <div class="bg-carregando w-100 he-30"></div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="infos">-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--        <div class="content"><div class="bg-carregando w-100 he-15"></div></div>-->
                    <!--    </div>-->
                    <!--    <div class="actions">-->
                    <!--        <button class="btn download"><div class="bg-carregando he-30"></div></button>-->
                    <!--        <button class="btn itens"><div class="bg-carregando he-30"></div></button>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
                <div id="paginacao">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<? include __DIR__.'/../modal-items.php' ?>
<? include __DIR__.'/../modais-oportunidades.php' ?>
<? include __DIR__.'/../modal-edital.php' ?>

