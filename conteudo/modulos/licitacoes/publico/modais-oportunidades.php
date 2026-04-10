<div class="modal fade" data-bs-backdrop="static" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Palavras-chaves de Interesse do seu negócios</h1>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="select-segmento" class="mb-1">Palavras-chaves<br>
                    <span class="text-terciaria">Ex: "impressora", "aluguel de veículos", "consultoria ambiental"</span></label>
                    <div class="input-group mb-3 position-relative">
                        <input type="text" class="form-control" name="tag" aria-describedby="button-addon2">
                        <div class="position-absolute end-1 top-50 translate-middle-y" style="z-index:9999">
                            <button class="btn btn-adicionar-tag" type="button" id="button-addon2"><i class="bi bi-plus"></i></button>
                        </div>
                        
                    </div>
                    <input type="text" class="form-control d-none" name="tag-cadastrada">
                    <div class="d-flex gap-2 flex-wrap align-items-center my-2" id="tagWrap">
                    </div>
                    <!--<select class="form-select" id="select-segmento">-->
                    <!--    <option>Opção 1</option>-->
                    <!--    <option>Opção 2</option>-->
                    <!--    <option>Opção 3</option>-->
                    <!--    <option>Opção 4</option>-->
                    <!--</select>-->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-padrao w-100" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Próximo</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-bs-backdrop="static" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Selecione a Abrangência de Interesse</h1>
            </div>
            
            <?
                $regioes = [
                    "Centro-Oeste" => [
                        "DF" => "Distrito Federal",
                        "GO" => "Goiás",
                        "MS" => "Mato Grosso do Sul",
                        "MT" => "Mato Grosso"
                    ],
                    "Nordeste" => [
                        "AL" => "Alagoas",
                        "BA" => "Bahia",
                        "CE" => "Ceará",
                        "MA" => "Maranhão",
                        "PB" => "Paraíba",
                        "PE" => "Pernambuco",
                        "PI" => "Piauí",
                        "RN" => "Rio Grande do Norte",
                        "SE" => "Sergipe"
                    ],
                    "Norte" => [
                        "AC" => "Acre",
                        "AM" => "Amazonas",
                        "AP" => "Amapá",
                        "PA" => "Pará",
                        "RO" => "Rondônia",
                        "RR" => "Roraima",
                        "TO" => "Tocantins"
                    ],
                    "Sudeste" => [
                        "ES" => "Espírito Santo",
                        "MG" => "Minas Gerais",
                        "RJ" => "Rio de Janeiro",
                        "SP" => "São Paulo"
                    ],
                    "Sul" => [
                        "PR" => "Paraná",
                        "RS" => "Rio Grande do Sul",
                        "SC" => "Santa Catarina"
                    ]
                ];
            
            ?>
            <div class="modal-body" id="dados-regionais">
                <ul class="list-group list-group-flush">
                    <?php
                    $i = 0;
                    foreach ($regioes as $key => $r) {
                        ?>
                        <li class="list-group-item pb-2 gap-2">
                            <label for="regiao-<?=$i?>" class="d-flex align-items-center gap-2">
                                <input class="regionais" type="checkbox" id="regiao-<?=$i?>" name="regiao" value="<?= $key ?>" /> <span class="fw-700">Região <?= $key ?></span>
                            </label>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <?php
                                foreach ($r as $chave => $es) {
                                    ?>
                                    <label class="d-flex align-items-center gap-2" for="<?= $chave ?>">
                                        <input class="check-regionais" type="checkbox" id="<?= $chave ?>" name="uf" value="<?= $chave ?>" /> <?= $es ?>
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                        </li>
                        <?php
                        
                        $i++;
                    }
                    ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-n-primaria w-100" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Voltar</button>
                <button class="btn btn-terciario w-100" id="botao-final">Selecionar</button>
                <!-- data-bs-toggle="modal"-->
            </div>
        </div>
    </div>
</div>