<style>
    
.cardVideo{
    a{
    text-decoration: none !important;
    }
    
    h2{

    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* number of lines to show */
    -webkit-box-orient: vertical;

    }
}

.canal-link:hover{
    color:var(--nown-primaria);
}

@media (min-width: 1200px) {
  .gradeInteligente {
    
    .cardVideo{
    display: flex;
    gap:10px;

    
        .card{
            width: 40%;
        
        }
        
        .contexto{
            width: 60%;
            
            .containerCanal{
                display: none;
        }
        }
    }
}
}


</style>

<div class="cardVideo position-relative">
            <div class="card border-0 bg-carregando">
                <div class="ratio ratio-16x9 ">
                    <div class="capa">
                        
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-start gap-3 mt-2 contexto">
                <!--<div>-->
                <!--    <div class="containerCanal he-50 wi-50 overflow-hidden rounded-circle">-->
                <!--        <div class="h-100 bg-carregando"></div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="d-flex flex-column gap-1 w-100">
                    <a class="btn m-0 p-0 link stretched-link">
                        <h2 class="fs-16 fw-700 m-0 titulo">
                            <div class="he-15 bg-carregando mb-1"></div>
                            <div class="he-15 bg-carregando"></div>
                        </h2>
                    </a>
                    <!--<a class="btn m-0 p-0 canal-link"><h3 class="fs-14 fw-400 m-0 canal"><div class="he-10 bg-carregando mb-1 w-50"></div></h3></a>-->
                    <!--<div class="d-flex justify-content-start fs-12 gap-2 w-100">-->
                        <!--<span class="visualizacao"><div class="he-10 bg-carregando mb-1 wi-40"></div></span>-->
                        <!--•-->
                        <span class="data"><div class="he-10 bg-carregando mb-1 wi-45"></div></span>
                    <!--</div>-->
                </div>
            </div>
        </div>