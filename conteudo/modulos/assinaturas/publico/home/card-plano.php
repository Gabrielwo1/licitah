<style>

    @media (max-width: 1199.98px) {
        .card-plano{
            background-color: transparent;
            border: 0px;
            
            .card-body{
                padding: 10px 0px;
            }
            
        }
    }
    
    @media (min-width: 1199.99px) {
        .card-plano{
         
                
             .card-body{
                padding: 10px;
            }
            
        }
    }

    .card-plano{
        border-radius: 15px;
        cursor: pointer;
      
        
        .card-header{
            padding: 10px;
        }
        
        
    }
    
    .topoplano {
        /*height: 70px;*/
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 10px;
        border-radius: 15px;
        background: linear-gradient(to right, var(--nown-primaria), var(--nown-primaria-darker));
        color: var(--nown-primaria-text-over);
    }


    .selecionada {
        display: none;
    }
    
    .dado-selcionada{
        display:none;
    }
    
    /* Mostra o elemento com a classe 'selecionada' quando o input radio correspondente está selecionado */
    .dado-selcionada:checked ~ .label-plano .selecionada {
        display: block;
    }
    
    
    @media(max-width: 1199px){
        .colAssinatura{
        display: none;
        
            
        }
    
     .colAssinatura.ativo{
        display: block;
        

        }
    }
    
    @media(min-width: 1200px){
        .colAssinatura.ativo .card-plano{
                 box-shadow: var(--bs-box-shadow);
                 transition: ease all 0.5s;
        }
    }
    
    
     .colAssinatura{
        .icone {display: none}
        }
    
     .colAssinatura.ativo{
        .icone {display: block}
        

        }
    
    
    
    .seletorMobile{
       
        .icone{
            display: none;
        }
    }
    
    .seletorMobile.ativo{
        background-color:  var(--nown-primaria);
        
         color: white;
        
        h2{
        color: white;
        }
        
        .icone{
            display: block;
        }
        
    }
</style>
<?



$card = '
    <div class="col-12 col-lg-4 colAssinatura">
        <div class="cards-replicantes">
            <input type="radio" class="d-none dado-selcionada" name="escolha-plano" id="elemento0">
            <label class="w-100 label-plano" for="elemento0">
                <div class="card card-plano">
                <div class="card-header bg-transparent border-0 d-none d-xl-block">
                    <div class="topoplano">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fs-18 fw-700 mb-1 nome" style="color: var(--nown-primaria-text-over)">Premium</h2>
                                <p style="color: var(--nown-primaria-text-over)" class="fs-14 m-0">Mais Popular</p>
                            </div>
                            <div class="he-20 selecionada">
                                <i class="bi bi-check-circle-fill fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush atributos">
                        <li class="list-group-item py-3 bg-transparent">
            <div class="d-flex d-lg-block justify-content-between align-items-center">
                <div class="fs-14 fw-700">Qualidade de vídeo e áudio</div>
                <div class="fs-12 fw-900">Excepcional</div>
            </div>
        </li>
        <li class="list-group-item py-3 bg-transparent">
            <div class="d-flex d-lg-block justify-content-between align-items-center">
                <div class="fs-14 fw-700">Qualidade de vídeo e áudio</div>
                <div class="fs-12 fw-900">Excepcional</div>
            </div>
        </li>
        <li class="list-group-item py-3 bg-transparent">
            <div class="d-flex d-lg-block justify-content-between align-items-center">
                <div class="fs-14 fw-700">Qualidade de vídeo e áudio</div>
                <div class="fs-12 fw-900">Excepcional</div>
            </div>
        </li>
                    </ul>
                </div>
            </div>
            </label>
        </div>
    </div>

';

?>

