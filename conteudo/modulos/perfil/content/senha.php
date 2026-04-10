<div class="card border-0">
    <div class="card-header bg-transparent">
        <h2 class="fs-18 fw-700 m-0">Troque sua Senha</h2>
    </div>
    <div class="card-body" id="primario">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fs-16 m-0 fw-700">Senha</h3>
                <p class="m-0">************</p>
            </div>
            <div><button class="btn btn-n-primaria btn-nown-style" id="trocar">Trocar Senha</button></div>
        </div>
    </div>
     <div class="card-body d-none"  id="secundario">
        <div class="mb-3">
            <label class="fw-700">Nova Senha</label>
            <div class="position-relative">
            <input class="form-control form-control-lg" id="senha"  type="password" style="padding-left: 50px" value="">
             <button class="p-0 btn position-absolute top-50 translate-middle-y showPass" style="left: 20px">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-n-primaria position-absolute top-50 translate-middle-y" style="right: 10px" id="gerar">
                    Gerar <span><i class="bi bi-arrow-repeat"></i></span>
                </button>
            </div>
        </div>
        <div class="mb-3">
            <label class="fw-700">Repita a Nova Senha</label>
            <div class="position-relative">
                <input class="form-control form-control-lg" id="repitaSenha" style="padding-left: 50px" type="password" value="">
                <button class="p-0 btn position-absolute top-50 translate-middle-y showPass" style="left: 20px">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            
        </div>
        <div class="d-flex justify-content-start gap-2">
            <button class="btn btn-n-primaria btn-lg btn-nown-style" id="salvaTroca">Salvar</button>
            <button class="btn btn-lg text-danger border-0" id="cancelaTroca">Cancelar</button>
        </div>
    </div>
</div>