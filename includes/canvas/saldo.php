<style>
    #ocWalet{
        
        .resumo-oc-cart{
        .saldovisivel{
            display: none;
        }
        
        .saldoEscondido{
            display: block;
        }
        
        
        &.visivel{
            .saldovisivel{
                display: block;
            }
            
            .saldoEscondido{
            display: none;
            }
        }
        }
        
    }
</style>

<div class="nown-canvas" id="ocWalet">
    <div class="nown-canvas-content">
        <header>
            <h3>Carteira</h3>
            <button class="btn-close-nown-canvas" data-close-canvas></button>
        </header>
        <main>
          <div class="resumo-oc-cart">
            <div class="d-flex justify-content-between fs-22">
                <div class="fw-700">Saldo</div>
                <div class="d-flex justify-content-end gap-2 align-items-center">
                    <span class="fw-700 saldovisivel">
                         <span>R$</span>
                         <span class="totalSaldo">0,00</span>
                    </span>
                    <span class="saldoEscondido fs-16">
                       ******
                    </span>
                    <span><button class="btn btn-sm btnver"><i class="bi bi-eye fs-16"></i></button></span>
                </div>
            </div>
        </div>
        </main>
        <footer>
             <div class="d-flex justify-content-between align-items-center">
                 <div>
                     <button class="btn text-primaria text-decoration-underline addSaldo">Adicionar Saldo</a>
                 </div>
                 <div>
                     <button class="btn btn-n-primaria btn-nown-style verCarteira">Ver Carteira</button>
                 </div>
             </div>
        </footer>
    </div>
    <div class="controller"></div>
</div>