<style>
    #inicio{
        padding:0px;
    }
</style>

<div id="inicio">
    <div class="oportunidades">
        <div class="info">
            <div class="notif"></div><h4>Oportunidades do dia: </h4><span class="total-licitacoes"><div class="bg-carregando wi-100 he-20"></div></span>
        </div>
        <div>
            <button class="btn btn-terciario" data-bs-target="#exampleModalToggle" id="modal-de-oportunidades">Definir Oportunidades</button>
            <?
                if(!isset($this->caminho[1]) || (isset($this->caminho[1]) && $this->caminho[1] != 'oportunidades')){
                    ?>
                    <a class="btn btn-terciario" href="/licitacoes/oportunidades">Ver oportunidades</a>
                    <?
                }
            
            ?>
            
        </div>
    </div>
</div>
