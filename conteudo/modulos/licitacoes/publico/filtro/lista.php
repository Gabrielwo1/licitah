<?
    $html = '
    <div class="filtros-wrap">
        <div class="holder">
            <label for="objeto" class="form-label" name="objeto">Objeto</label>
            
            <input type="text" class="form-control" id="objeto" placeholder="">
            
            <div>
                <input class="form-check-input me-2" type="checkbox" id="busca-exata" value="busca_exata" name="busca-exata">
                <label for="busca-exata" class="form-label">Busca Exata</label>
            </div>
            
            <label for="estado" class="form-label">Estado</label>
            
            <select class="form-select" id="estado" name="estado">
                <option value = 0>Selecione uma opção</option>
            </select>
            
            <label for="cidade" class="form-label">Cidade</label>
            <select class="form-select"  disabled id="cidade" name="cidade">
                <option value="0">Defina o estado</option>
            </select>
        </div>
        <div class="holder">
        
            <!--<label for="editalNumber" class="form-label">Nº Edital</label>--!>
            <!--<input type="text" class="form-control" id="editalNumber" name="edital" placeholder="">--!>
            
            <label for="modalidades" class="form-label">Modalidades</label>
            <select class="form-select" id="modalidades">
                <option selected value="0">Selecione uma opção</option>
            </select>
            
        </div>
        <!--<div class="holder">--!>
            <!--<label for="edital" class="form-label">Nº Edital</label>--!>
            <!--<input type="text" class="form-control" id="edital" name="edital" placeholder="">--!>
            
            <!--<label for="exampleFormControlInput1" class="form-label">Modalidades</label>--!>
            <!--<select class="form-select" aria-label="Default select example">--!>
                <!--<option selected></option>--!>
                <!--<option value="1">One</option>--!>
                <!--<option value="2">Two</option>--!>
                <!--<option value="3">Three</option>--!>
            <!--</select>--!>
        <!--</div>--!>
        
        <div class="holder">
            <label class="form-label">Data Abertura</label>
            <div class="item-flex align-items-center">
                <label class="form-label" for="data-inclusao"> De:</label> <input type="text" class="form-control mascaraInput" data-mascara="7" name="data-inclusao" id="data-inclusao" placeholder="">
            </div>
            <div class="item-flex align-items-center">
                <label class="form-label" for="data-prazo">Até:</label> <input type="text" class="form-control mascaraInput" data-mascara="7" name="data-prazo" id="data-prazo" placeholder="">
            </div>
        </div>
        <!-- <div class="holder">
            <label for="exampleFormControlInput1" class="form-label">Licitções</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Vigentes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Com edital
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Com monitoramento de chat
                </label>
            </div>
        </div> --!>
        <div class="holder">
            <label for="id-gov" class="form-label">Nº Conciliação</label>
            <input type="text" class="form-control" id="id-gov" placeholder="">
            <label for="cod-orgao" class="form-label">Código do orgão</label>
            <input type="text" class="form-control" id="cod-orgao" placeholder="" name="cod-orgao">
            <label for="esferas" class="form-label">Esfera</label>
            <select class="form-select" id="esferas" aria-label="Default select example" name="esfera">
    
            </select>
            <label for="num-processo" class="form-label">Nº processo</label>
            <input type="text" class="form-control" id="num-processo" placeholder="">
        </div>
        <div class="holder">
            <label for="situacao" class="form-label">Situação</label>
            <select class="form-select" id="situacao" aria-label="Default select example" name="situacao">
                <option value="0">Selecione uma situação</option>
                <option value="1">Publicado</option>
                <option value="2">Aberto</option>
                <option value="3">Fechado</option>
            </select>
            <label for="orgao-nome" class="form-label">Órgão</label>
            <input type="text" class="form-control" id="orgao-nome" placeholder="">
            <label for="item-nome" class="form-label">Itens</label>
            <input type="text" class="form-control" id="item-nome" placeholder="">
        </div>
        <div class="holder">
            <label for="exampleFormControlInput1" class="form-label">Concorrências</label>
            <div id="concorencias">
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
            </div>
        </div>
        <div class="holder">
            <label for="exampleFormControlInput1" class="form-label">Oportunidades</label>
            <div id="oportunidades">
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
                <div class="w-100 he-20 mb-1 bg-carregando"></div>
            </div>
            
        </div>
        <button class="btn btn-padrao" id="pesquisar">Procurar</button>
    </div>
    ';

    
    if(isMobile()){
?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?= $html ?>
            </div>
        </div>
    </div>
</div>

<?
    }
?>

<div class="container" id="encontrar_licitacoes">
    <?
        if(isMobile()){
    ?>
        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-secundario-fill d-lg-none"> <span>Procurar</span><i class="bi bi-search"></i></button>
    
    <?
        }
    ?>
    
    <div class="row">
        <?
            if(!isMobile()){
        ?>
        <div class="col-lg-3 d-none d-lg-flex">
            <?= $html ?>
        </div>
        
        <?
            }
        ?>
        
        <div class="col-lg-9" id="sessao-topo">
            <div class="boletins" id="licitacoes-pesquisada">
                <?
                $i = 0;
                while($i < 8){
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
            </div>
            <div class="d-flex w-100 justify-content-center align-items-end" id="paginacao">
                    
            </div>
        </div>
    </div>
</div>

<?
    include __DIR__.'/../modal-items.php';
    include __DIR__.'/../modais-oportunidades.php';
?>
<? include __DIR__.'/../modal-edital.php' ?>
