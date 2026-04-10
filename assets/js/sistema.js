class CardModulo extends CicloVida{
    constructor(item, pai, fragmento = false) {
        super();
        this.pai = pai
        this.item = item
        this.versoes = pai.versoes;
        this.meus = fragmento ? fragmento : document.getElementById("meuModulos")
        this.disponiveis = document.getElementById("disponiveis")
        this.encontra = this.encontra.bind(this)
        this.ajax = this.ajax.bind(this)
    }

    encontra(nomeModulo) {
        for (let i = 0; i < this.versoes.length; i++) {
            if (this.versoes[i].modulo === nomeModulo) {
                var copia = Object.assign({}, this.versoes[i]);
                if (this.mys) {
                    this.versoes[i] = false;
                }

                return copia;
            }
        }
        return false;
    }
    
    desativa(modulo){
        var data = new FormData();
        data.append("acao", "ativador")
        data.append("valor", false)
        data.append("modulo", modulo)
        this.ajax(data, false)
    }
    
    ativa() {
        var data = new FormData();
        data.append("acao", "ativador")
        data.append("valor", event.currentTarget.checked)
        data.append("modulo", this.item.modulo)
        this.ajax(data, this.ativado.bind(this))

    }

    ativado() {
        start.ajax()
    }

    render(meus = true) {
        this.mys = meus


        var modulo = this.encontra(this.item.modulo);
        this.me = modulo;


        const divCol = document.createElement('div');
        divCol.classList.add('col-12', 'col-lg-6', 'col-xl-3');

        const divCard = document.createElement('div');
        divCard.classList.add('card', 'card-nown', 'h-100', 'position-relative', 'modulo');

        const divCardBody = document.createElement('div');
        divCardBody.classList.add('card-body', 'd-flex', 'flex-column', 'justify-content-between');

        const divTitleAndSwitch = document.createElement('div');
        divTitleAndSwitch.classList.add('d-flex', 'justify-content-between', 'align-items-center');
        
         const divDropdown = document.createElement('div');
        divDropdown.classList.add('dropdown');

        const buttonDropdown = document.createElement('button');
        buttonDropdown.classList.add('btn');
        buttonDropdown.setAttribute('type', 'button');
        buttonDropdown.setAttribute('data-bs-toggle', 'dropdown');
        buttonDropdown.setAttribute('aria-expanded', 'false');

        const spanDropdown = document.createElement('I');
        spanDropdown.classList.add('bi','bi-three-dots-vertical');
        buttonDropdown.appendChild(spanDropdown);

        const ulDropdown = document.createElement('ul');
        ulDropdown.classList.add('dropdown-menu', 'dropdown-nown', 'dropdown-menu-start');

        if (modulo) {
            if(modulo.baixavel){
               var dropdownItems = [{
                    nome: 'Instalar Dependências',
                    callBack: this.instala.bind(this)
                },
                {
                    nome: 'Desinstalar',
                    callBack: this.apaga.bind(this)
                }
            ]; 
            }else{
                var dropdownItems = [];
            }
            
        } else {
            var dropdownItems = [{
                nome: 'Instalar Dependências',
                callBack: this.instala.bind(this)
            }];
        }


        dropdownItems.forEach(itemText => {
            const liItem = document.createElement('li');
            const aItem = document.createElement('BUTTON');
            evento(aItem, "click", itemText.callBack)
            aItem.classList.add('dropdown-item');
            aItem.textContent = itemText.nome;
            liItem.appendChild(aItem);
            ulDropdown.appendChild(liItem);
        });

        
        
        if(dropdownItems.length > 0){
               divDropdown.appendChild(buttonDropdown);
               divDropdown.appendChild(ulDropdown);
        }
     
        
         if (meus) {
            divTitleAndSwitch.appendChild(divDropdown);
        }
        
        
        const h2 = document.createElement('h2');
        h2.classList.add('fs-16', 'text-center', 'm-0', "fw-500", "nome");
        h2.textContent = this.item.name;

        const divSwitch = document.createElement('div');
        divSwitch.classList.add('form-check', 'form-switch');

        const inputSwitch = document.createElement('input');
        inputSwitch.classList.add('form-check-input');
        inputSwitch.setAttribute('type', 'checkbox');
        inputSwitch.setAttribute('role', 'switch');

        if (this.item.ativo && this.item.ativo == "true") {
            inputSwitch.checked = true;
        }

        if (meus) {
            if(modulo){
                if(modulo.baixavel){
                    evento(inputSwitch, "input", this.ativa.bind(this))
                    divSwitch.appendChild(inputSwitch);  
                }else{
                    this.desativa.bind(this)(this.item.modulo)
                }
            }else{
              evento(inputSwitch, "input", this.ativa.bind(this))
              divSwitch.appendChild(inputSwitch);  
            }
           
            
            
        }

        divTitleAndSwitch.appendChild(h2);
        divTitleAndSwitch.appendChild(divSwitch);
        divCardBody.appendChild(divTitleAndSwitch);

        const divImage = document.createElement('div');
        divImage.classList.add('text-center');

        const img = document.createElement('img');
        img.classList.add('w-50');
        img.setAttribute('src', this.item.icone ?
            `${dominio}/conteudo/modulos/${this.item.modulo}/${this.item.modulo}.svg?v=12` :
            'https://preview.keenthemes.com/metronic8/demo1/assets/media/svg/illustrations/easy/1.svg');

        divImage.appendChild(img);
        divCardBody.appendChild(divImage);

        const divDescription = document.createElement('div');
        divDescription.classList.add('px-4');

        const pDescription = document.createElement('p');
        pDescription.classList.add('fs-12', 'mt-2', "text-center");
        pDescription.textContent = this.item.description

        divDescription.appendChild(pDescription);
        divCardBody.appendChild(divDescription);

        const divButtons = document.createElement('div');
        divButtons.classList.add('d-flex', 'justify-content-center', 'gap-2', 'aling-items-center');

       


        const buttonDownload = document.createElement('button');
        

        if (meus) {
 
            if (modulo) {

                if(!modulo.versao){
                    modulo.versao = this.item.versao;
                }
                
                
                if(modulo.baixavel){
                       if (this.item.versao && parseFloat(modulo.versao).toFixed(2) == parseFloat(this.item.versao).toFixed(2)) {
                    buttonDownload.classList.add('btn', 'btn-success', "d-flex", "justify-content-center", "align-items-center", "gap-2", "fs-14");
                    buttonDownload.innerHTML = `<i class="bi bi-check-circle"></i><span>Atualizado</span>`;

                    var versao = document.createElement("SPAN")
                    versao.classList.add("text-success", "fs-16", "fw-500")
                    versao.innerText = ` ${parseFloat(this.item.versao).toFixed(2)}`
                } else {
                    
            
                    let versao = this.item.versao ? `${parseFloat(this.item.versao).toFixed(2)}` : "0"
                    
                    buttonDownload.classList.add('btn', 'btn-danger', "d-flex", "justify-content-center", "align-items-center", "gap-2", "fs-14");
                    buttonDownload.textContent = `Atualizar ${versao}`;
                    evento(buttonDownload, "click", this.update.bind(this))
                    
                    
               

                    var versao1 = document.createElement("SPAN")
                    versao1.classList.add("text-success", "fs-16", "fw-500")
                    versao1.innerText = `${parseFloat(modulo.versao).toFixed(2)}`


                }
                }else{
                    buttonDownload.classList.add('btn', 'btn-danger', "d-flex", "justify-content-center", "align-items-center", "gap-2", "fs-14");
                    buttonDownload.innerHTML = `<i class="bi bi-x-circle-fill"></i> <span>Não Autorizado</span>`;

                }
             
                
                
              

            } else {

                buttonDownload.classList.add('btn', 'btn-warning', "d-flex", "justify-content-center", "align-items-center", "gap-2", "fs-14");
                buttonDownload.innerHTML = '<i class="bi bi-gear"></i><span>Desenvolvimento</span>';
            }
        } else {
            
            if(this.item.baixavel){
                buttonDownload.classList.add('btn', 'btn-contrast', "d-flex", "justify-content-center", "align-items-center", "gap-2", "fs-14");
                buttonDownload.innerHTML = '<i class="bi bi-file-arrow-down"></i><span>Baixar</span>';
                evento(buttonDownload, "click", this.baixar.bind(this))
            }else{
                buttonDownload.classList.add('btn', 'btn-success', "d-flex", "justify-content-center", "align-items-center", "gap-1", "fs-14", "fw-700");
                buttonDownload.innerHTML = '<i class="bi bi-coin"></i><span>COMPRAR</span>';
                
            }
            
        }



        divButtons.classList.add("align-items-center", "btn-acoes")


       

        divButtons.appendChild(buttonDownload);

        if (versao) {
            divButtons.appendChild(versao);
        }

        if (versao1) {
            divButtons.appendChild(versao1);
        }

        divCardBody.appendChild(divButtons);

        divCard.appendChild(divCardBody);
        divCol.appendChild(divCard);

        this.componente = divCol

        if (meus) {
            this.meus.appendChild(divCol);
        } else {
            this.disponiveis.appendChild(divCol)
        }


    }

    update(r) {
        event.currentTarget.setAttribute("disabled", "")
        event.currentTarget.innerHTML = `<div class="d-flex justify-content-center">
         <div class="spinner-border" role="status">
         <span class="visually-hidden">Loading...</span>
         </div>
         </div>`

        var data = new FormData();
        data.append("acao", "baixar")
        data.append("modulo", this.me.id)
        data.append("nomeModulo", this.item.modulo)
        this.ajax(data, this.atualizado.bind(this))

    }

    atualizado(r) {
        var btn = this.componente.getElementsByClassName("btn-danger")[0];
        var obj = JSON.parse(r)
        if (obj.sucesso) {

            Swal.fire({
                icon: 'success',
                title: 'Módulo Atualizado com Sucesso',
                showConfirmButton: false,
                timer: 1500
            })
            btn.classList.add("btn-success")
            btn.classList.remove("btn-danger")
            btn.innerHTML = `<span class="material-symbols-outlined">check_circle</span> <span>Atualizado</span>`
            this.componente.getElementsByClassName("old")[0].remove();
            btn.removeAttribute("disabled")
            var novoBotao = btn.cloneNode(true);
            btn.parentNode.replaceChild(novoBotao, btn);


        } else {
            Swal.fire({
                icon: 'error',
                title: 'Falha ao baixar atualização',
                showConfirmButton: false,
                timer: 1500
            })
            btn.innerHTML = "Atualizar"

        }
        btn.removeAttribute("disabled")
    }

    baixar(r) {

        event.currentTarget.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        var data = new FormData();
        data.append("acao", "baixar")
        data.append("modulo", this.item.id)
        data.append("nomeModulo", this.item.modulo)
        this.ajax(data, this.baixado.bind(this))
    }

    baixado(r) {
        r = JSON.parse(r)
        if (r.sucesso) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: "O módulo foi baixado com sucesso!",
                showConfirmButton: false,
                timer: 15000
            })
            this.componente.remove();
            let card = new CardModulo(r.manifest, this.pai);
            card.render();
            start.ajax();

        }
    }

    instala() {
        var data = new FormData();
        data.append("modulo", this.item.modulo)
        data.append("acao", "instalarDependencias");
        this.ajax(data, this.dependenciado.bind(this))
    }
    
    dependenciado(r){
        iziToast.success({
            icon: 'bi bi-check-circle',
    title: 'Sucesso',
    message: 'Dependências Instaladas com Sucesso'
});
    }

    apaga() {
        var data = new FormData();
        data.append("acao", "apagar")
        data.append("modulo", this.item.modulo)
        this.ajax(data, this.apagado.bind(this))
    }

    apagado(r) {
        var obj = JSON.parse(r)
        if (obj.sucesso) {
            Swal.fire({
                icon: 'success',
                title: 'Módulo Deletado com Sucesso',
                showConfirmButton: false,
                timer: 1500
            })
            this.disponiveis.appendChild(this.componente)
            this.componente.getElementsByClassName("btn-acoes")[0].remove();
        }
    }

    ajax(data, cb = false) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (cb) {
                cb(this.responseText);
            } else {
                console.log(this.responseText)
            }

        }
        xhttp.open("POST", `${dominio}/admin/sistema.php`);
        xhttp.send(data);
    }

}

class CardSistema extends CicloVida{
    constructor(ultima, sistema){
        super();
        this.ultima = parseFloat(ultima.versao).toFixed(2);
        this.sistema = parseFloat(sistema.versao).toFixed(2);
        
        
        this.versao = document.getElementById("versaoSistema")
        this.render.bind(this)()
    }
    
    render(){
        this.versao.innerHTML = this.sistema
        
        if(this.sistema != this.ultima){
            var botao = document.createElement("BUTTON")
            botao.classList.add("btn","btn-light","d-flex","justify-content-start","align-items-center","gap-2")
            var icone = document.createElement("SPAN")
            icone.innerText = "download"
            icone.classList.add("material-symbols-outlined")
            
            var texto = document.createElement("SPAN")
            texto.innerText = "Baixar Atualização"
            
            botao.appendChild(icone)
            botao.appendChild(texto)
            evento(botao, "click", this.atualizar.bind(this))
            
            document.getElementById("sistema").getElementsByClassName("card-footer")[0].appendChild(botao)
        }else{
            document.getElementById("sistema").getElementsByClassName("card-footer")[0].remove()
        }
    }
    
    
    atualizar() {
        event.currentTarget.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        
        var data = new FormData();
        data.append("acao", "baixar")
        data.append("modulo", 1) 
        this.ajax(data, this.download.bind(this))
        
    }

    download(r) {
        console.log(r)
        var obj = JSON.parse(r)

        if (obj.sucesso) {
            document.getElementById("sistema").getElementsByClassName("card-footer")[0].remove();
            
            Swal.fire({
                icon: 'success',
                title: 'Sistema Atualizado com Sucesso',
                showConfirmButton: false,
                timer: 1500
            })

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro ao atualizar o sistema',
                showConfirmButton: false,
                timer: 1500
            })
        }
    }

    ajax(data, cb = false) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (cb) {
                cb(this.responseText);
            } else {
                console.log(this.responseText)
            }

        }
        xhttp.open("POST", `${dominio}/admin/sistema.php`);
        xhttp.send(data);
    }
}

class Sistema extends CicloVida{
    constructor() {
        super();
        this.ajax = this.ajax.bind(this)
        this.modulos = document.getElementsByClassName("modulo")

        this.sistema.bind(this)();
        
        evento(document.getElementById("ativarTudo"), "click", this.ativarTudo.bind(this))
        evento(document.getElementById("desativarTudo"), "click", this.desativarTudo.bind(this))
        
    }
    
    ativarTudo(){
         Swal.fire({
            icon: 'question',
            title: "Atenção!",
            text: "Você irá ativar todos os módulos. Você quer proseguir?",
            showCancelButton: true,
            confirmButtonText: "Ativar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Módulos Ativados!", "", "success");
                
                var inputs = document.getElementsByClassName("form-check-input");
                var i = 0;
                while (i < inputs.length) {
                    inputs[i].checked = true;
                    i++;
                }
                
                let request = new Request(`${dominio}/admin/sistema.php`);
                request.addData({acao: "ativaTudo"})
                request.send().then((r)=>{
                    inputs[0].dispatchEvent(new Event("input"));
                })

                
                
            } 
            
        });
    }
    
    desativarTudo(){
        Swal.fire({
            icon: 'question',
            title: "Atenção!",
            text: "Você irá desativar todos os módulos. Você quer proseguir?",
            showCancelButton: true,
            confirmButtonText: "Desativar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("Módulos Desativados!", "", "success");
                
                var inputs = document.getElementsByClassName("form-check-input");
                var i = 0;
                while (i < inputs.length) {
                    inputs[i].checked = false;
                    
                    i++;
                }

                 let request = new Request(`${dominio}/admin/sistema.php`);
                request.addData({acao: "desativaTudo"})
                request.send().then((r)=>{
                    inputs[0].dispatchEvent(new Event("input"));
                })
                
            } 
            
        });
    }
    
    
    sistema() {
        let request = new Request(`${dominio}/admin/sistema.php`);
        request.addData({
            acao: "verificaLicenca",
            valor: true,
            modulo: true,
        })
        request.send().then((obj)=>{
       
        

        if (obj.sucesso) {
            var i = 0;
          

            this.versoes = obj.modulos
            
            
            new  CardSistema(this.versoes[0], obj.sistema);



            if (obj.meus.length > 0) {
                document.getElementById("meuModulos").innerHTML = "";
                var fragmento = document.createDocumentFragment();
                while (i < obj.meus.length) {
                    
                    var modulo = obj.meus[i]
                    let card = new CardModulo(modulo, this, fragmento);
                    card.render();
                    i++;
                }
                document.getElementById("meuModulos").appendChild(fragmento)
                
                var monkeyList = new List('listaDosMeus', { 
  valueNames: ['nome']
});
            } else {

            }


            var i = 0;
            while (i < this.versoes.length) {
                var versao = this.versoes[i]
                if (versao && this.versoes[i].modulo != 0) {
                    let card = new CardModulo(versao, this);
                    card.render(false);
                }

                i++;
            }


        } else {
            if (obj.erro) {
                Swal.fire({
                    icon: 'error',
                    title: 'Licença Inválida',
                    text: "Contacte o suporte para mais informações",
                    showConfirmButton: false,
                    timer: 15000
                })
            }
        }
        })

    }

 
    desautorizado(item, mensagem, disabled = true) {
        var mod = item
        if (!mod.closest(".card").dataset.valido) {
            var pai = mod.closest(".card");

            var footer = pai.getElementsByClassName("card-footer")[0]
            footer.classList.add("text-uppercase", "fs-12", "text-center")
            footer.innerText = `Módulo Não Autorizado`

            pai.getElementsByClassName("card-body")[0].innerText = mensagem
            if (!disabled) {
                pai.classList.add("bg-warning", "text-dark")
                footer.remove();
            } else {
                pai.classList.add("bg-danger", "text-light")
                mod.removeAttribute("checked")
                mod.setAttribute("disabled", "")

                var data = new FormData();
                data.append("acao", "ativador")
                data.append("valor", false)
                data.append("modulo", mod.id)
                this.ajax(data)

            }
        }
    }

    atualizar() {
        var data = new FormData();
        data.append("acao", "baixar")
        data.append("modulo", event.target.dataset.id)
        this.ajax(data, this.download.bind(this))
        this.btn = event.target
    }

    download(r) {

        var obj = JSON.parse(r)

        if (obj.sucesso) {
            var pai = this.btn.closest(".card-footer")
            if (pai) {
                this.btn.closest(".acao").innerHTML = '<div class="fs-13 text-success"><i class="bi bi-check-circle-fill"></i> Atualizado</div>';
                pai.getElementsByClassName("versao")[0].innerText = obj.versao

            }
            Swal.fire({
                icon: 'success',
                title: 'Módulo Atualizado com Sucesso',
                showConfirmButton: false,
                timer: 1500
            })

        } else {

        }
    }

    comprar() {
        var modulo = event.target.dataset.id
        window.open(`https://nown.com.br/modulos/${modulo}`, '_blank');
    }

    ajax(data, cb = false) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (cb) {
                cb(this.responseText);
            } else {
                console.log(this.responseText)
            }

        }
        xhttp.open("POST", `${dominio}/admin/sistema.php`);
        xhttp.send(data);
    }
}

function paginaSistema() {
    new Sistema();
}