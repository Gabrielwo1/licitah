class UsuarioStatusControler{
    constructor(estado, usuario){
        this.usuario = usuario
        this.estado = parseInt(estado)
        this.container = document.getElementById("statusControler")
        this.container.classList.add("d-flex", "flex-column", "gap-2")

        this.select = document.getElementById("ativacaoControl").getElementsByTagName("select")[0]
        this.render.bind(this)()
        this.request = new Request("/conteudo/modulos/usuarios/admins/autoriza.php");
    }
    
    ajax(){
        this.request.send((e)=>{
            console.log(e)
        })
    }
    
    aprovar(){
        this.select.value = 1;
        this.estado = 1
        this.render();
   
        iziToast.success({
            icon: "bi bi-check-circle",
            title: 'Sucesso',
            message: `Usuário <strong>${this.usuario.usuario}</strong> está Ativo`,
            imageWidth: 50,
        });
        
        this.request.addData(
            {"acao": "autoriza",
            "user": this.usuario.id,
            "status": 1
            }
            )
        this.ajax.bind(this)()
    }
    
    banir(){

         iziToast.error({
            icon: "bi bi-check-circle",
            title: 'Sucesso',
            message: `Usuário <strong>${this.usuario.usuario}</strong> foi banido`,
            imageWidth: 50,
            
        });
        
        
        this.select.value = 2;
        this.estado = 2;
        this.render();
        
        this.request.addData(
            {"acao": "autoriza",
            "user": this.usuario.id,
            "status": 2
            }
            )
        this.ajax.bind(this)()
    }
    
    desativar(){
        iziToast.show({
            icon: "bi bi-check-circle",
            title: 'Sucesso',
            message: `Usuário <strong>${this.usuario.usuario}</strong> foi desativado`,
            imageWidth: 50,
            
        });
        
        
        this.select.value = 0;
        this.estado = 0
        this.render();
        
        this.request.addData(
            {"acao": "autoriza",
            "user": this.usuario.id,
            "status": 0
            }
            )
        this.ajax.bind(this)()
    }
    
    input(estado){
        var h3 = document.createElement("DIV")
        h3.classList.add("d-flex", "justify-content-start", "align-items-center", "gap-2", "fs-14")
        var bolinha = document.createElement("DIV")
        bolinha.classList.add("wi-10", "he-10", "rounded-circle")
        var span = document.createElement("SPAN")
        h3.appendChild(bolinha)
        h3.appendChild(span)
        switch(estado){
            case 0:
                span.innerText = "Aguardando Aprovação"
                bolinha.classList.add("bg-light")
                break;
            case 1:
                span.innerText = "Usuário Ativo"
                bolinha.classList.add("bg-success")
                break;
            case 2:
                span.innerText = "Usuário Banido"
                bolinha.classList.add("bg-danger")
                break;
        }
        this.container.appendChild(h3)
    }
    
    render(){
        console.log("renderizando")
        this.container.innerHTML = "";
        this.input.bind(this)(this.estado)

    }
}

class ModuloUsuariosUsuarioEdit{
    constructor(){
        this.inputDisplay = document.getElementById("usuario-display").getElementsByClassName("form-control")[0]
        evento(this.inputDisplay, "input", this.changeDisplay.bind(this))
        this.container = document.getElementById("containerPerfil");
        
        this.container.innerHTML = `
        <div class="header-grupo">
   <div class="header-grupo-capa">  </div>
   <div class="header-grupo-info">
      <div class="row align-items-center">
         <div class="col-lg-6">
            <div class="grupo-header-nome">
               <div class="grupo-avatar grupo-avatar-circle">  </div>
               <div class="grupo-nome">
                  <h3>${this.inputDisplay.value}</h3>
                  <h6 id="statusControler"></h6>
               </div>
            </div>
         </div>
         <div class="col-lg-6">
   <div class="grupo-header-dados">
      <div class="grupo-dados-box">
         <div class="grupo-ib">
            <div class="funcao"></div>
         </div>
      </div>
  
      <div class="grupo-dados-box">
         <div class="grupo-acoes">
            <button class="btn btn-nown-style btn-n-primaria" id="btnAcessarComo"><i class="bi bi-eye"></i><span> Acessar</span></button> 
            <div class="dropdown-center">
               <button class="btn btn-nown-style btn-n-secundaria dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span<i class="bi bi-three-dots"></span<i></button> 
               <ul class="dropdown-menu dropdown-nown">
                  <li><button class="dropdown-item aprovar" href="#"><i class="bi bi-check-circle"></i> Aprovar</button></li>
                  <li><button class="dropdown-item desativar" href="#"><i class="bi bi-circle"></i> Desativar</button></li>
                  <li><button class="dropdown-item banir" href="#"><i class="bi bi-x-circle"></i> Banir</button></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
      </div>
   </div>
</div>
        
        
        `
        
        var div = document.createElement("DIV")
        div.classList.add("wi-200", "he-200")
        div.appendChild(document.getElementById("cardFotoPerfil"))
        this.container.getElementsByClassName("grupo-avatar-circle")[0].appendChild(div)
        //<img loading="lazy" src="https://fortram.site/placeholder/1300x400" class="grupo-capa">
        var div2 = document.createElement("DIV")
        div2.classList.add("w-100", "grupo-capa")
        div2.appendChild(document.getElementById("imgCapaUser"))
        
        var card = div2.getElementsByClassName("card")[0]
        card.classList.add("rounded-0")
        this.container.getElementsByClassName("header-grupo-capa")[0].appendChild(div2)
        
       
       
        this.container.getElementsByClassName("funcao")[0].appendChild(document.getElementById("containerFuncao"))
        
        evento(document.getElementById("btnTrocarSenha"), "click", this.editaSenha.bind(this))
        evento(document.getElementById("btnSalvarSenha"), "click", this.salvarSenha.bind(this))
        evento(document.getElementById("btnCancelarSenha"), "click", this.cancelarSenha.bind(this))
        
        this.senha = document.getElementById("endPass").getElementsByClassName("form-control")[0]
        this.repetir = document.getElementById("endPass").getElementsByClassName("form-control")[1]
        
        

        evento(document.getElementsByClassName("aprovar")[0], "click", ()=>{ this.acao.bind(this)(1) })
        evento(document.getElementsByClassName("desativar")[0], "click", ()=>{ this.acao.bind(this)(2) })
        evento(document.getElementsByClassName("banir")[0], "click", ()=>{ this.acao.bind(this)(3) })
        

        let request = new Request(`/conteudo/modulos/usuarios/admins/perfil.php`);
        request.addData({acao:"infoVital", "usuario":pegaHash()});
        request.send().then((r)=>{
            this.r = r;
            this.status = new UsuarioStatusControler(r.usuario.status, r.usuario);
            evento(document.getElementById("btnAcessarComo"), "click", this.acessaComo.bind(this))
        })

    }
    
    cancelarSenha(){
        document.getElementById("endPass").classList.add("d-none")
        document.getElementById("startPass").classList.remove("d-none")
        this.senha.value = ""
        this.repetir.value = "";
    }
    
    nivel(a,b,c){
        this.erroMsg = c;
    }
    
    validasenha(){
          var senha = this.senha.value;
          var comprimentoMinimo = 8;
          var possuiNumero = /\d/.test(senha);
          var possuiLetraMaiuscula = /[A-Z]/.test(senha);
          var possuiLetraMinuscula = /[a-z]/.test(senha);
          var possuiCaracterEspecial = /[!@#$%^&*()\-_=+{}[\]:";<>?|,.\/~`]/.test(senha);
          
          var peso = 0;
          
          if (senha.length >= 8) {peso++}
          if (possuiNumero) {peso++}
          if (possuiLetraMaiuscula) {peso++}
          if (possuiLetraMinuscula) {peso++}
          if (possuiCaracterEspecial) {peso ++}
          
         
          var aprovado = false;
          
          if(peso == 0){
              this.nivel(0, "secondary", "Defina uma senha seura e que você se lembre.")
          }else if(peso < 3){
              this.nivel(1, "danger", "Sua senha é muito fraca. Crie uma senha mais forte.")
          }else if(peso == 3){
              this.nivel(2, "info", "Mais um pouco, estamos quase lá.")
          }else if(peso == 4){
              this.nivel(3, "primary", "Sua senha é válida, você pode proseguir.")
              aprovado = true;
          }else{
              this.nivel(4, "success", "Parabéns, sua senha é segura")
              aprovado = true;
          }
  
  if(aprovado){
           
            return true
    }else{

           return false;
        }
        
 
        
  
        
    
  
  }
    
    salvarSenha(){
        if(!this.validasenha.bind(this)()){
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Senha muito fraca !",
                showConfirmButton: false,
                timer: 1500
            
            });
            return;
        }
        
        if(this.senha.value != this.repetir.value){
            invalido(this.repetir, false, "As senhas não coincidem!")
             Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "As senhas não coincidem!",
                showConfirmButton: false,
                timer: 1500
            
            });
            return;
        }
        
        
        let request = new Request(`/conteudo/modulos/usuarios/admins/perfil.php`);
        request.addData({acao:"salvaSenha", "usuario":pegaHash(), "senha": this.senha.value});
        request.send().then((r)=>{
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: "Senha trocada com sucesso",
                showConfirmButton: false,
                timer: 1500
            });
            this.cancelarSenha.bind(this)()
        
        }, (r)=>{
            
        })
        
        
    }
    
    editaSenha(){
        document.getElementById("endPass").classList.remove("d-none")
        document.getElementById("startPass").classList.add("d-none")
    }
  
    acessaComo(){
        var data = {
            infos: {
                  "1": this.inputDisplay.value,
                  "id": this.r.usuario.id
            }
            
        }
        usuarioAcessarComo(event, data);

    }
    
    acao(num){
        switch(parseInt(num)){
            case 1:
                this.status.aprovar();
                break;
            case 2:
                this.status.desativar();
                break;
            case 3:
                this.status.banir();
                break;
        }
    }
    
    changeDisplay(){
        this.container.getElementsByTagName("h3")[0].innerText = this.inputDisplay.value
    }
    


}

class ModuloUsuariosUsuarioEmpresa{
    constructor(){
        let request = new Request(`/conteudo/modulos/usuarios/admins/perfil.php`);
        request.addData({acao:"infoVital", "usuario":pegaHash()});
        request.send().then((r)=>{
            this.resposta = r;
            this.inicio.bind(this)()
        })
    }
    
    inicio(){
        var api = new ApiNown('empresas', 'ZG2V2rCcN8vz2xD')
        api.isNew()
        api.setExtra(this.resposta.usuario.id)
        api.paginacao(1000)
        api.send().then((r)=>{
            var nada = false
            
            if(r.lista && r.lista.length > 0){
                var lista = r.lista
                this.lista = r.lista
                var i = 0
            
                var fragmento = document.createDocumentFragment()
                
                while(i < lista.length){
                    this.acordion = document.createElement("DIV")
                    this.acordion.classList.add("accordion");
                    this.acordion.id = this.idAcordion;
                    
                    var drop = lista[i]
                    let html = this.item.bind(this)(drop);
                    
                    if(html){
                       this.acordion.appendChild(html) 
                       fragmento.appendChild(this.acordion)
                    }
                    
                    
                    i++
                }
                
                document.getElementById("divEmpresas").getElementsByClassName("card-body")[1].appendChild(fragmento)
                
            }else{
                nada = true
                this.lista = []
            }
            
            var api = new ApiNown('empresas', 'm5eaZsHtudRJvk2')
            api.isNew()
            api.paginacao(1000)
            api.setHash(this.resposta.usuario.usuario)
            api.send().then((r)=>{
                
                var lista = r.lista
                var dados = [];
                var i = 0
                while(i < lista.length){
                    var j = 0
                    var existe = false
                    while(j < this.lista.length){
                        console.log(this.lista[j].empresa, lista[i])
                        if(this.lista[j].empresa.url == lista[i].url){
                            existe = true
                            break;
                        }
                        j++
                    }
                    
                    if(!existe){
                        var dado = {
                            'empresa' : lista[i],
                            'funcao' : 0
                        }
                        dados.push(dado)
                        
                    }
                    
                    i++;
                }

                if(dados.length > 0){
                    var i = 0
                    
                    while(i < dados.length){
                        this.acordion = document.createElement("DIV")
                        this.acordion.classList.add("accordion");
                        this.acordion.id = this.idAcordion;
                        
                        var drop = dados[i]
                        let html = this.item.bind(this)(drop);
                        
                        if(html){
                           this.acordion.appendChild(html) 
                           document.getElementById("divEmpresas").getElementsByClassName("card-body")[1].appendChild(this.acordion)
                        }
                        
                        i++
                    }
                }else{
                    if(nada){
                        document.getElementById("divEmpresas").getElementsByClassName("card-body")[1].innerHTML = 'Usuário sem Empresa Cadastrada'
                    }
                }
                console.log(r)
            }, (r)=>{
                console.log(r)
            })
        })
    }
    
    item(info){
        var id = geraId();
        var nome = info.empresa.nome ?? 'Empresa sem nome'
        if(parseInt(info.funcao) == 0){
            var funcao = 'Responsável pela Empresa'
        }else{
            var funcao = info.funcao.nome ?? 'Função não denominada'
        }
        var drop = document.createElement("DIV")
        drop.classList.add("accordion-item");
        drop.innerHTML = `
            <h2 class="accordion-header">
              <button class="accordion-button collapsed d-flex align-items-center gap-2" data-id="${info.id}" type="button" data-bs-toggle="collapse" data-bs-target="#${id}" aria-expanded="false" aria-controls="${id}">
              <span><i class="bi bi-ethernet"></i></span>
              <span>${nome}</span>
              -
              <span>${funcao}</span>
              </button>
            </h2>
            <div id="${id}" class="accordion-collapse collapse" data-bs-parent="#${this.idAcordion}">
              <div class="accordion-body d-flex flex-column gap-4">
                
               
                
              </div>
            </div>

            `
        evento(drop.getElementsByClassName('accordion-button')[0], 'click', ()=>{
            this.abriuAccordion.bind(this)(info)
        })  
        return drop
    }
     
    abriuAccordion(info){
        var botao = event.currentTarget
        var colapso = botao.getAttribute('data-bs-target');
        var id = colapso.replace('#', '');

        var cnpj =  info.empresa.cnpj ?? 'Não informado'
        var data = info.empresa.data ? dataFormatada(info.empresa.data) : 'Não informada'
        var descricao = info.empresa.descricao ?? 'Não informada'
        
        var url = info.empresa.url ?? false
        var elemento = document.getElementById(id).getElementsByClassName('accordion-body')[0];
        if(elemento.innerHTML.trim() == ''){
            elemento.innerHTML = `
            <ul class="list-group">
                <li class="list-group-item">
                    CNPJ: ${cnpj}
                </li>
                <li class="list-group-item">
                    Data de Criação: ${data}
                </li>
                <li class="list-group-item">
                    Descrição: ${descricao}
                </li>
            </ul>
            <button class="btn btn-n-primaria redirect">Visualizar</button>
            `
            
             evento(elemento.getElementsByClassName('redirect')[0], 'click', ()=>{
                if(url){
                    goUrl(`${dominio}/empresas/${url}`)
                }else{
                    iziToast.error({
                        message: 'Empresas sem url de redirecionamento',
                        timeout: 5000,
                        position: 'topRight',
                    });
                }
            })
        }
        
       
    }
}

class UsuarioPerfilVitais{
    constructor(pai, user){
        console.log(user)
        
        new Mascaras();
        this.pai = pai;
        this.user = user
        
        this.metas = []
        this.btnEmail = document.getElementById("changeemail")
        this.btnTelefone = document.getElementById("changetelefone")
        this.btnUsuario = document.getElementById("changeusuario")
        this.btnCpf = document.getElementById("changecpf")
        
        this.btnCancelar = document.getElementsByClassName("cancelarMudanca")
        evento(Array.from(this.btnCancelar), "click", this.cancelar.bind(this))
        
        evento(this.btnEmail, "click", this.email.bind(this))
        evento(this.btnTelefone, "click", this.telefone.bind(this))
        evento(this.btnUsuario, "click", this.usuario.bind(this))
        evento(this.btnCpf, "click", this.cpf.bind(this))
        
        this.btnSalvar = document.getElementsByClassName("salvarMudanca")
        evento(Array.from(this.btnSalvar), "click", this.salvar.bind(this))
        
       var modais = document.getElementsByClassName("innerConfigVital")
       var i = 0;
       this.modais = [];
       
       while(i < modais.length){
           this.modais[i] = new bootstrap.Collapse(modais[i], {
               toggle: false
           });
           i++;
       }
       
       this.foco = false;
       

       
       
       evento(document.getElementById("input-usuario"), "INPUT", this.controleUser.bind(this))
       
       var mensagem = document.createElement("SMALL")
       mensagem.classList.add("mt-2", "fs-12", "fw-500", "d-block")
       mensagem.innerText = "O nome de usuário é um identificador único e será sua URL de perfil pública."
       document.getElementById("input-usuario").closest("div").appendChild(mensagem)
       
      
       this.previas.bind(this)();
    }
    
    controleUser(){
        document.getElementById("input-usuario").value = paraUrl(document.getElementById("input-usuario").value)
    }
    
    alteravel(dateString) {
  const specifiedDate = new Date(dateString); // Cria um objeto Date a partir da string especificada
  const currentDate = new Date(); // Cria um objeto Date com a data e hora atuais

  // Calcula a diferença entre as datas em milissegundos
  const difference = currentDate - specifiedDate;

  // Converte a diferença de milissegundos para dias
  const differenceInDays = difference / (1000 * 60 * 60 * 24);

  // Verifica se a diferença é menor que 30 dias
  if (differenceInDays < 30) {
    return false;
  } else {
    return true;
  }
}

    previas(){
        var email = this.user.email.split("@")
        var trato = email[0][1]
        
        let previa = [];
        var i= 0;
        while(i < email[0].length){
            if(i < 3){
                previa.push(email[0][i])
            }else{
                previa.push("*")
            }
            i++;
        }
        
        var final = `${previa.join("")}@${email[1]}`
        document.getElementById("previa-email").innerHTML = final
        
    
        var telefone = this.user.telefone
        var trato = telefone.split("-")
        
        var i = 0;
        var focus = [];
        while(i < trato[0].length){
            focus.push("*")
            i++;
        }
        
 
        
        var final = `(**) *****-${trato[1]}`
        document.getElementById("previa-telefone").innerHTML = final

        document.getElementById("previa-usuario").innerHTML = this.user.usuario
        
        
        var cpf = this.user.cpf
        var i = 0;
        var restritos = [4,5,6,8,9,10];
        var final = [];
        while(i < cpf.length){
            if (restritos.includes(i)) {
                final.push("*")
            }else{
                final.push(cpf[i])
            }
            i++;
        }
        document.getElementById("previa-cpf").innerHTML = final.join("");
        
        
        
        if(this.metas["update_cpf"]){
            if(!this.alteravel.bind(this)(this.metas["update_cpf"])){
                this.desabilitaEdicao.bind(this)(document.getElementById("input-cpf"),this.metas["update_cpf"], "CPF");
            }
        }
        
        if(this.metas["update_usuario"]){
            if(!this.alteravel.bind(this)(this.metas["update_usuario"])){
                this.desabilitaEdicao.bind(this)(document.getElementById("input-usuario"),this.metas["update_usuario"], "Usuário");
            }
        }
        
         if(this.metas["update_telefone"]){
            if(!this.alteravel.bind(this)(this.metas["update_telefone"])){
                this.desabilitaEdicao.bind(this)(document.getElementById("input-telefone"),this.metas["update_telefone"], "Telefone");
            }
            }
            
            
           
         if(this.metas["update_email"]){
            if(!this.alteravel.bind(this)(this.metas["update_email"])){
                this.desabilitaEdicao.bind(this)(document.getElementById("input-email"),this.metas["update_email"], "E-mail");
            }
            }
    }
    
    liberaData(dateString) {
  const specifiedDate = new Date(dateString); // Cria um objeto Date a partir da string especificada
  specifiedDate.setDate(specifiedDate.getDate() + 30); // Adiciona 30 dias à data

  // Lista de nomes dos meses em português
  const meses = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
  ];

  // Formata a data no formato "10 de outubro de 2022"
  const dia = specifiedDate.getDate();
  const mes = meses[specifiedDate.getMonth()];
  const ano = specifiedDate.getFullYear();

  return `${dia} de ${mes} de ${ano}`;
}

    desabilitaEdicao(input, data, nome){
        input.closest(".py-3").innerHTML = `
        <div class="alert alert-danger mt-3" role="alert">
        <h4 class="fs-16 fw-700 text-uppercase">Ação não permitida!</h4>
        <p>O seu <strong>${nome}</strong> foi trocado recentemente.</p>
        <hr>
        <p class="mb-0">Você poderá trocar novamente o seu <strong>${nome}</strong> em <strong>${this.liberaData.bind(this)(data)}</strong></p>
        </div>
        `
    }
    
    salvar(){
        if(this.input.value == this.user[this.foco]){
            this.cancelar.bind(this)()
        }else{
            this.desabilitaTudo.bind(this)();
            
            var validador = new ValidaInfo(this.input.value, this.foco);
            var valido = validador.valida();
       
            
            if(valido){
                var data = new FormData();
                data.append("item", this.foco)
                data.append("novo", this.input.value)
                data.append("acao", "vital")
                this.pai.ajax(data, this.salvo.bind(this))
            }else{
                this.reativaTudo.bind(this)()
                switch(this.foco){
                    case 'email':
                        var mensagem = "O e-mail definida é inválido"
                        break;
                    case 'cpf':
                        var mensagem = "O CPF definido é inválido"
                        break;
                    case 'usuario':
                        var mensagem = "O usuário definido é inváldio"
                        break;
                    case 'telefone':
                         var mensagem = "O telefone definido é inváldio"
                        break;
                }
                
                 Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: mensagem,
                showConfirmButton: false,
                timer: 1500
            });
                
            }
            
        }
    }
    
    salvo(r){
        switch(this.foco){
            case 'email':
                var sucesso = "Seu e-mail foi atualizado com sucesso"
                var erro =  "Já existe um usuário usando esse e-mail"
                break;
            case 'cpf':
                var sucesso = "Seu CPF foi atualizado com sucesso"
                var erro = "Já existe um usuário usando esse CPF"
                break;
            case 'telefone':
                var sucesso = "Seu telefone foi atualizado com sucesso"
                var erro = "Já existe um usuário usando esse telefone"
                break;
            case 'usuario':
                var sucesso = "Seu nome de usuário foi atualizado com sucesso"
                var erro = "Já existe um usuário usando esse nome"
                break;
        }
        
        
        
        if(r.status == 1){
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: sucesso,
                showConfirmButton: false,
                timer: 1500
            });
            goUrl(`perfil/geral`)

        }else{
             Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: erro,
                showConfirmButton: false,
                timer: 1500
            });
            
        }
         this.reativaTudo.bind(this)();
    }
    
    desabilitaTudo(){
        var i = 0;
        while(i < this.btnSalvar.length){
            var btn = this.btnSalvar[i]
            if(btn){
               
              btn.setAttribute("disabled", "") 
              btn.innerHTML = `<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>`  
            }
            i++;
            
        }
         var i = 0;
        while(i <  this.btnCancelar.length){
            var btn = this.btnCancelar[i]
            if(btn){
              btn.setAttribute("disabled", "") 
            btn.innerHTML = `<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>`  
            }
            i++;
            
        }
    }
    
    reativaTudo(){
        var i = 0;
        while(i < this.btnSalvar.length){
            var btn = this.btnSalvar[i]
            if(btn){
              btn.removeAttribute("disabled") 
              btn.innerHTML = `Salvar`  
            }
            i++;
            
        }
         var i = 0;
        while(i <  this.btnCancelar.length){
            var btn = this.btnCancelar[i]
            if(btn){
              btn.removeAttribute("disabled") 
            btn.innerHTML = `Cancelar`  
            }
            i++;
            
        }
    }
    
    cancelar(){
        this.btnEmail.removeAttribute("disabled")
        this.btnTelefone.removeAttribute("disabled")
        this.btnUsuario.removeAttribute("disabled")
        this.btnCpf.removeAttribute("disabled")
        
        var i = 0;
        while(i < this.modais.length){
            this.modais[i].hide();
            i++;
        }

    }
    
    desabilita(){
        this.btnEmail.setAttribute("disabled", "")
        this.btnTelefone.setAttribute("disabled", "")
        this.btnUsuario.setAttribute("disabled", "")
        this.btnCpf.setAttribute("disabled", "")
    }
    
    email(){
     
        
        if(document.getElementById("input-email")){
             this.input = document.getElementById("input-email")
        this.input.value = this.user.email
           this.desabilita.bind(this)()
        }
       
      
        this.foco = "email"
        
    }
    
    telefone(){
      
        
        if(document.getElementById("input-telefone")){
            this.input = document.getElementById("input-telefone")
        this.input.value = this.user.telefone
        $(this.input).trigger('input'); 
          this.desabilita.bind(this)()
        }
       
      
        
        this.foco = "telefone"
    }
    
    usuario(){
        
        
        if(document.getElementById("input-usuario")){
            this.input = document.getElementById("input-usuario")
        this.input.value = this.user.usuario
                this.desabilita.bind(this)()
        }
        

        this.foco = "usuario"
    }
    
    cpf(){
        if(document.getElementById("input-cpf")){
             this.input = document.getElementById("input-cpf")
             this.input.value = this.user.cpf
             $(this.input).trigger('input');
              this.desabilita.bind(this)() 
        }
      
       this.foco = "cpf"
    }
}

function usuarioAcessarComo(e, obj){
    console.log(obj)

    Swal.fire({
        icon: "question",
        title: "Atenção",
        html: `Você quer acessar o sistema como <strong>${obj.Nome}</strong> ?`,
        showCancelButton: true,
        confirmButtonText: "Acessar",
        cancelButtonText: `Cancelar`
        
    }).then((result) => {
        if (result.isConfirmed) {
            var request = new Request("/admin/login.php");
            request.addData({acao:"otherlogin", "id":obj.H})
            request.send().then((e)=>{
                window.location.href = dominio
            })
        }
});
    
    console.log(obj.infos.id)
}

function moduloUsuariosUsuario(){
    new  ModuloUsuariosUsuarioEdit();
    new  ModuloUsuariosUsuarioEmpresa();
}

function moduloNovoUsuario(){
    console.log("logo existo")
}

function usuarioVerPerfil(r, s){
    goUrl(`usuarios/${s.Username}`)
}

function moduloUsuariosFuncao(){
    nownFiles.add(`${dominio}/conteudo/modulos/usuarios/assets/controlerUser.js`).then(()=>{
        new ControleUserFuncao();
    })
    
    nownFiles.add(`${dominio}/conteudo/modulos/usuarios/assets/funcaoTemporaria.js`).then(()=>{
        // new testeTemporario();
    })
}





