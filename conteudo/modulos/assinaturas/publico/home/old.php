<style>
    .card-price{
        background: white;
        box-shadow: 0 5px 30px rgba(0,0,100,0.2);
        border-radius: 25px;
        padding: 30px;
        position: relative;
        z-index: 2;
    }
    
    .card-price h3 {
        font-size: 22px;
        font-weight: bold;
    }
    
    .dados-nome{
        position:absolute;
        right:-30px;
        width:100%;
        border-radius: 30px 0 0 30px;
        overflow:hidden;
    }
    
    .dados-item{
        margin-left:50px;
    } 

    .dados-item .bi-check-circle-fill{
        color:var(--bs-success);
    } 
    
    .dados-item .bi-x-circle-fill{
        color:var(--bs-danger);
    } 
    

    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 1,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }
    
    @media (min-width:992px){
        .span-alinhamento{
            padding-left:10%;
        }
    }
    
    

</style>



<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card-price overflow-hidden">
                <div class="rounded-circle he-150 wi-150 bg-light position-absolute" style="right:-50px"></div>
                <div class="bg-info text-uppercase w-100 text-light p-2 fs-20 fw-700 position-absolute d-flex align-items-center justify-content-center" style="transform: rotate(330deg);left:-35%">
                    <span class="d-inline-block span-alinhamento">New</span>
                </div>
                <div class="card-price-header mt-5">
                    <h3 class="text-center">Plano</h3>
                </div>
                <div class="he-100 position-relative w-100">
                    <div class="dados-nome">
                        <div class="bg-black align-items-center d-flex justify-content-center gap-3 text-light p-2">
                            <h3 class="">
                                 $
                            </h3>
                            <span class="fs-40 fw-600">
                                5.99
                            </span>
                            <h3 class="">
                                 por mês
                            </h3>
                        </div>
                    </div>
                </div>
                
                <div class="dados-corpo">
                    <p class="fw-600 text-center">Uma pequena descrição para complementar o card e ele não ficar muito pequeno parecendo que está vazio, desse tamanho deve estar ótimo.</p>
                    <div class="dados-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>100 MB</span>
                    </div>
                    <div class="dados-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Nenhum Custo Adicional</span>
                    </div>
                    <div class="dados-item">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Taxa de Cancelamento</span>
                    </div>
                    <div class="dados-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Suporte</span>
                    </div>
                </div>
                
                <div class="card-footer border-0 bg-transparent mt-2 text-center fs-20">
                    <a class="btn bg-black text-light fw-700 rounded-pill p-2 d-flex align-items-center gap-2 justify-content-center">
                        Assine Agora 
                        <span class="material-symbols-outlined">
                        arrow_circle_right
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>