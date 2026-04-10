<style>
    #conteudo{
        padding-left: 0px;
        padding-right: 0px;
    }
    
    #containerVideo {
        margin-bottom: 20px;
    }
    
    #espaceMobile{
        display:none;
        height: 0px;
    }
    
    @media (max-width: 1200px) {
        .colvideo{
            padding:0px;
        }
        
         #espaceMobile.ativo{
        display:block;
        height: 265px;
    }
        
        .containerInfo{
            padding-left: 20px;
            padding-right: 20px;
        }
        
        
    #containerVideo.ativo {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        z-index: 9999;
        margin: 0px;
    }
}
</style>
<style>
    
    .comments-viewer {
    display: block;
    position: absolute;
    bottom: -70vh;
    left: 50%;
    transform: translatex(-50%);
    width: 100%;
    max-width: 680px;
    border-radius: 30px 30px 0 0;
    box-shadow: 0 0 55px rgba(0,0,150,0.1), inset 0 0 100vw 100vw rgba(80,80,250,0.05);
    background: var(--bs-body-bg);
    transition: .27s ease;
    touch-action: pan-y;
    max-height: 70vh;
    height: 100%;
    z-index: 99999;
    overflow: hidden;
    
    .controller {
        display: block;
        width: 100%;
        height: 40px;
        position: relative;
        cursor: pointer;
        
        &:before {
            content: '';
            display: block;
            width: 40px;
            height: 6px;
            border-radius: 10px;
            background: rgba(var(--bs-body-color-rgb), 0.2);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    }
    
    .post-comments {
        height: 100%;
        display: flex;
        flex-direction: column;
        
        
        .comment-form{
                min-height: 180px;
            }
        
        
        .comments {
            overflow: auto;
            height: 100%;
            padding: 0 20px;
            
            h5 {
                font-size: 17px;
            }
            
            
            
            
            .comments-list {
            }
        }
        
        
    }
}


.comentarioAtivo{
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    z-index: 9999;
    margin: 0px;
}

.comment-form {
            flex-grow: 1;
            padding: 20px;
            box-shadow: 0 0 25px rgba(0,0,150,0.1);
            
            .holder-comment-form {
                display: flex;
                gap: 15px;
                
                .thumb {
                    img {
                        border-radius: 50%;
                        width: 40px;
                        height: 40px;
                    }
                }
                
                .input {
                    flex-grow: 1;
                    background: var(--bs-body-bg);
                    border-radius: 15px;
                    padding: 20px 0;
                    
                    textarea {
                        padding: 0 20px;
                        border: none !important;
                        background: none !important;
                        outline: none !important;
                        width: 100%;
                        height: 40px;
                        font-size: 14px;
                        line-height: 1.4;
                        resize: none;
                    }
                    
                    .actions {
                        display: flex;
                        justify-content: end;
                        padding: 0 20px;
                        
                        button {
                            border: none !important;
                            background: none !important;
                            color: rgba(var(--bs-body-color-rgb), 0.7);
                        }
                    }
                }
            }
        }

.overlay-comments {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(var(--bs-body-color-rgb), 0.5);
    z-index: 99998;
    opacity: 0;
    transition: .27s ease;
    display: none;
}
</style>

<div class="container-xxl">
    <div class="row">
        <div class="col-12 col-xl-8 colvideo">
            <div id="containerVideo">
                 <div class="ratio ratio-16x9 bg-carregando" id="videoLoading">
                     
                 </div>
            </div>
            <div class="containerInfo">
                <div id="espaceMobile">
                 
                </div>
                <h1 class="fs-18 fs-xl-28 fw-700 titulo"></h1>
                
                <div class="d-block d-xl-none">
                     <div class="card card-nown" id="cardComentario">
                    <div class="card-body">
                        <h2 class="fs-18 m-0">Comentários</h2>
                    </div>
                </div>
                </div>
               
                
                <div id="caixaComentario" class="d-none d-xl-block">
                    
                </div>
                
                
                
            </div>
           
        </div>
        <div class="col-12 col-xl-4">
            <div class="gradeInteligente d-flex flex-column gap-3 my-3" id="proximosVideos">
                 <?
        $i = 0;
        while($i < 12){
            include __DIR__."/../componentes/card.php";
            $i++;
        }
        
        ?>
            </div>
           
        </div>
    </div>
</div>

