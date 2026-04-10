<style>
#videoArea.videoTocando.sticky { position: fixed; bottom: 10px; right: 10px; width: 400px; height: auto; z-index: 1000; max-width: 80%;
}
.artigo-conteudo{ img{ max-width: 100%; } p{ margin-bottom: 5px !important; font-size: 16px; }
}
.artigo-galeria.has-gap:not(.slider) .item { padding: 15px;
}
.artigo-galeria .item { position: relative; display: flex; justify-content: center; align-items: center; padding:10px; img{ position: relative; z-index: 0; width: 100%; height: auto; display: block; transition: transform 2s cubic-bezier(0.2, 1, 0.2, 1); }
}
.artigo-galeria .item .image-wrapper { overflow: hidden; position: relative; width: 100%; height: auto; display: block;
}
.artigo-galeria .item .image-wrapper .overlay { content: ''; position: absolute; z-index: 1; top: 0; left: 0; width: 100%; height: 100%; background-color: var(--color); opacity: 0; -webkit-transition: all 0.2s ease-in-out; -o-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
}
.altura-automatica{ height:auto !important;
}</style>

<div class="container">
    <div class="smartAds" data-modulo="artigos" data-code="topo-artigos" data-pagina="item">
        
    </div>
   <div class="d-flex justify-content-center">
        <div class="w-100" style="max-width: 650px">
            <div class="d-flex flex-column gap-4">
                <div class="d-flex flex-column gap-4">
                <h1 class="m-0 fs-32 fw-500" id="titulo">
                    <?
                    echo linhas(3, 30, "100%");
                    ?>
                </h1>
            <div id="descricao">
                <?
                    echo linhas(2, 20, "100%");
                    ?>
            </div>
         
            <div>
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fs-14" id="autorArea"><div class="bg-carregando he-20 wi-100"></div></div>
                        <div class="fs-14" id="fonteArea"><div class="bg-carregando he-20 wi-100"></div></div>
                        <div class="fs-14 text-contrast" id="dataArea"><div class="bg-carregando he-20 wi-100"></div></div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn btn-n-primaria d-flex justify-content-center align-items-center gap-2 px-3 rounded-pill btnCompartilhar"  data-estrutura="artigos">
                            <span><i class="bi bi-share"></i></span>
                            <span class="d-none d-xl-block">Compartilhar</span>
                            </button>
                        <button class="btn">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            </div>
            <div>
                <div id="containerImagem">
                  
                    <div class="ratio ratio-4x3 bg-carregando rounded">
                    
                </div>
                </div>
            </div>
            
            <div>
                <div class="fs-18" id="textoArea">
                    <?
                    echo linhas(20, 18, "100%");
                    ?>
                </div>
             </div>
             
            <div class="artigo-galeria row g-5 shuffle">
                <div class="shuffle-container g-5"></div>
            </div>
           
            <div class="smartAds" data-modulo="artigos" data-code="fim-artigos" data-pagina="item"></div>
           
            <div>
               <div class="comments" data-modulo="artigos">
                   
               </div>
           </div>
            
            <div class="d-flex flex-column gap-4">
                <div class="border-bottom"></div>
                <h2>Veja Também</h2>
                <div id="gridArtigos" class="d-flex flex-column gap-4"></div>
            </div>
            </div>
            
        </div>
   </div>
</div>