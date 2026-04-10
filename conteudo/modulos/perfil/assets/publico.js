class Integracoes {
    constructor() {
        this.divBling = document.getElementById('integracao-bling');
        
        if (this.divBling) {
            // Inicializar os listeners de eventos
            this.inicializarEventos();
            
            // Carregar informações da conta
            this.carregarInformacoesConta();
            
            // Adicionar listener para mensagens de popups
            evento(window, 'message', this.handlePopupMessage.bind(this))
        }
    }
    
    // Inicializa os listeners de eventos
    inicializarEventos() {
        const btnIntegrar = this.divBling.querySelector('.btn-integrar');
        if (btnIntegrar) {
            evento(btnIntegrar, 'click', this.iniciarAutenticacao.bind(this))
        }
    }
    
    // Carrega as informações da conta Bling configurada
    carregarInformacoesConta() {
        const request = new Request('/conteudo/modulos/bling/admins/operacoes.php');
        request.addData({
            acao: 'obterInfosConta'
        });
        
        request.send()
            .then(response => {
                if (response.sucesso && response.item) {
                    // Criar a exibição dos dados da conta
                    this.exibirDadosConta(response.item);
                } else if (response.erro) {
                    // Mostra o botão de integração se não houver conta configurada
                    this.exibirBotaoIntegracao();
                }
            })
            .catch(error => {
                console.error('Erro ao carregar informações da conta:', error);
                this.exibirBotaoIntegracao();
            });
    }
    
    // Exibe os dados da conta
    exibirDadosConta(item) {
        const ul = document.createElement('ul');
        ul.classList.add("list-group", "list-group-flush");
        
        const li = this.criarLinhaBling(item);
        ul.appendChild(li);
        
        this.divBling.innerHTML = '';
        this.divBling.appendChild(ul);
    }
    
    // Exibe o botão de integração
    exibirBotaoIntegracao() {
        this.divBling.innerHTML = `
            <div class="text-center p-4">
                <p class="mb-3">Você ainda não configurou a integração com o Bling.</p>
                <button class="btn btn-primary btn-integrar">Integrar com o Bling</button>
            </div>
        `;
        
        // Reajustar o listener após mudar o HTML
        const btnIntegrar = this.divBling.querySelector('.btn-integrar');
        if (btnIntegrar) {
            evento(btnIntegrar, 'click', this.iniciarAutenticacao.bind(this))

        }
    }
    
    // Cria uma linha com os dados da integração Bling
    criarLinhaBling(item) {
        const li = document.createElement('li');
        li.classList.add('list-group-item', 'd-flex', 'flex-column', 'gap-2');
        li.innerHTML = `
            <div>
                <div class="d-flex gap-2 align-items-center justify-content-end">
                    <button class="btn btn-primary atualizar-chaves">Atualizar tokens</button>
                </div>
            </div>
            
            <div><span class="fw-bold">Scope</span>: ${item.scope || 'N/A'}</div>
            <div><span class="fw-bold">State</span>: ${item.state || 'N/A'}</div>
            <div><span class="fw-bold">Code</span>: <span class="token-code">${item.code || 'N/A'}</span></div>
            <div><span class="fw-bold">Token de Acesso</span>: <span class="token-acess">${item.acess || 'N/A'}</span></div>
            <div><span class="fw-bold">Token de Refresh</span>: <span class="token-refresh">${item.refresh || 'N/A'}</span></div>
        `;
        
        const btnAtualizar = li.querySelector('.atualizar-chaves');
        if (btnAtualizar) {
            evento(btnAtualizar, 'click', () => this.atualizarChavesBling(li))
        }
        
        return li;
    }
    
    // Inicia o processo de autenticação
    async iniciarAutenticacao() {
        try {
            // Desabilitar o botão para evitar múltiplos cliques
            const btnIntegrar = this.divBling.querySelector('.btn-integrar');
            if (btnIntegrar) {
                btnIntegrar.disabled = true;
                btnIntegrar.innerHTML = `
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    Redirecionando...
                `;
            }
            
            // Obter a URL de autorização
            const request = new Request('/conteudo/modulos/bling/admins/operacoes.php');
            request.addData({
                acao: 'obterAuthUrl'
            });
            
            const response = await request.send();
            
            if (response.sucesso && response.auth_url) {
                // Redirecionar para a página de autorização
                window.location.href = response.auth_url;
            } else {
                throw new Error('Não foi possível obter a URL de autorização');
            }
        } catch (error) {
            console.error('Erro ao iniciar autenticação:', error);
            
            // Habilitar o botão novamente em caso de erro
            const btnIntegrar = this.divBling.querySelector('.btn-integrar');
            if (btnIntegrar) {
                btnIntegrar.disabled = false;
                btnIntegrar.textContent = 'Integrar com o Bling';
            }
            
            // Mostrar mensagem de erro
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Ocorreu um erro ao tentar conectar com o Bling. Tente novamente.',
                confirmButtonColor: '#3085d6'
            });
        }
    }
    
    // Atualiza as chaves do Bling
    atualizarChavesBling(li) {
        const request = new Request('/conteudo/modulos/bling/admins/operacoes.php');
        request.addData({
            acao: 'atualizarChaveBling'
        });
        
        // Mostrar indicador de carregamento
        const btnAtualizar = li.querySelector('.atualizar-chaves');
        const originalText = btnAtualizar.textContent;
        btnAtualizar.disabled = true;
        btnAtualizar.innerHTML = `
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            Atualizando...
        `;
        
        request.send()
            .then(response => {
                console.log(response)
                // Restaurar botão
                btnAtualizar.disabled = false;
                btnAtualizar.textContent = originalText;
                
                if (response.sucesso && response.item) {
                    // Atualizar os tokens na interface
                    if (response.item.acess) {
                        li.querySelector('.token-acess').textContent = response.item.acess;
                    }
                    
                    if (response.item.reflesh) {
                        li.querySelector('.token-refresh').textContent = response.item.reflesh;
                    }
                    
                    // Mostrar mensagem de sucesso
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: 'Tokens atualizados com sucesso!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Mostrar mensagem de erro
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.mensagem || 'Erro ao atualizar tokens',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.log(error)
                if (error.refresh_token_invalido) {
                    // Tratamento para refresh token inválido
                    this.handleRefreshTokenInvalido(error.redirect_url);
                    return;
                }
                
                // Restaurar botão
                btnAtualizar.disabled = false;
                btnAtualizar.textContent = originalText;
                
                // Mostrar mensagem de erro
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao tentar atualizar os tokens. Tente novamente.',
                    confirmButtonColor: '#3085d6'
                });
            });
    }
    
    // Lidar com token de refresh inválido
    handleRefreshTokenInvalido(redirectUrl) {
        Swal.fire({
            title: 'Autenticação Necessária',
            text: 'Seu token de acesso ao Bling expirou. É necessário fazer login novamente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Autenticar agora',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (result.isConfirmed) {
                // Abrir janela de autenticação
                window.location.href = redirectUrl
            }
        });
    }
    
    // Abre uma janela popup para autenticação
    abrirJanelaAutenticacao(url) {
        // Adicionar parâmetro para indicar que é uma janela popup
        const popupUrl = url + (url.includes('?') ? '&' : '?') + 'popup=true';
        
        // Calcular dimensões e posição da janela
        const width = 800;
        const height = 600;
        const left = (window.innerWidth - width) / 2;
        const top = (window.innerHeight - height) / 2;
        
        // Abrir a janela popup
        const popup = window.open(
            popupUrl,
            'bling-auth',
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes,status=yes`
        );
        
        // Verificar se a janela foi bloqueada
        if (!popup || popup.closed || typeof popup.closed === 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Bloqueio de Pop-up',
                text: 'Por favor, permita pop-ups para este site e tente novamente.',
                confirmButtonColor: '#3085d6'
            });
        }
    }
    
    // Processa mensagens recebidas de janelas popup
    handlePopupMessage(event) {
        // Verificar se a mensagem contém informações da autenticação do Bling
        if (event.data) {
            // Caso de autenticação bem-sucedida
            if (event.data.bling_auth_success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Autenticação Concluída',
                    text: 'Sua conta foi autenticada com sucesso!',
                    showConfirmButton: false,
                    timer: 1500
                });
                
                // Recarregar os dados da conta
                setTimeout(() => this.carregarInformacoesConta(), 1500);
            }
            
            // Caso de erro na autenticação
            if (event.data.bling_auth_error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro na Autenticação',
                    text: event.data.message || 'Ocorreu um erro durante a autenticação.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    }
}

class PaginaPerfilFoto{
    constructor(pai){
        this.pai = pai;
        this.capa = document.getElementById("capa")
        this.perfil = document.getElementById("imgPerfil")
        this.contador = 0;
        
    
        this.btnCapa = document.getElementById("btnCapa")
        evento(this.btnCapa, "click", this.clica.bind(this))
        evento(this.perfil, "click", this.clica.bind(this))
        

        this.inputCapa = document.getElementById("fotoCapa")
        this.inputFoto = document.getElementById("fotoPerfil")
        evento(this.inputCapa, "change", this.carregaCapa.bind(this))
        evento(this.inputFoto, "change", this.carregaFoto.bind(this))
        
        this.envio = this.envio.bind(this)
        
        this.urlPerfil = false;
        this.urlCapa = false;
        
        evento(document.getElementById("salvar"), "click", this.salvar.bind(this))
        
        
        var foto = this.pai.usuario.foto;
        var array = JSON.parse(foto)
        if(array.length == 1){
             this.setPerfil.bind(this)(array[0])
        }
        
        var capa = this.pai.usuario.capa;
        var array = JSON.parse(capa)
         if(array.length == 1){
             this.setCapa.bind(this)(array[0])
        }
       
    }
    
    setPerfil(imgOriginal){
        var img = trataImagem(imgOriginal , "grande")
        this.perfil.style = `background-image: url("${img}"); background-size: cover; background-position: center center;`
        
        this.urlPerfil = imgOriginal
    }
    
    setCapa(imgOriginal){
        var img = trataImagem(imgOriginal , "grande")
        this.capa.style = `background-image: url("${img}"); background-size: cover; background-position: center center;`
        this.urlCapa = imgOriginal
    }
    
    clica(){
         event.currentTarget.closest("div").getElementsByTagName("input")[0].click();
    }
    
    monta(r){
        var url = URL.createObjectURL(r);
        this.foco.style = `background-image: url("${url}"); background-size: cover; background-position: center center;`
        this.envio(r)
    }
    
    carregaCapa(){
        var input = event.currentTarget
        var arquivo = input.files[0];

        input.value = ""
        this.foco = this.capa
        this.pasta = "capa"
        this.dentro = "urlCapa"
        var imgCapa = new ModalCrop(URL.createObjectURL(arquivo), "16,9");
        imgCapa.callBack(this.monta.bind(this))
    }
    
    btnControler(){
        var btn = document.getElementById("salvar");
        if(this.contador == 0){
            btn.removeAttribute("disabled")
            btn.innerHTML = "Salvar"
        }else{
            btn.setAttribute("disabled", "")
            btn.innerHTML = `<div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>`
        }
    }
    
    carregaFoto(){
        var input = event.currentTarget
        var arquivo = input.files[0];
        
        input.value = ""
        this.pasta = "perfil"
        this.foco = this.perfil
        this.dentro = "urlPerfil"
        var imgCapa = new ModalCrop(URL.createObjectURL(arquivo), "1,1");
        imgCapa.callBack(this.monta.bind(this))
    }

    async envio(arquivo, chave) {
        const data = new FormData();
        data.append("filepond", arquivo);
        data.append("pasta", `usuarios`);
        this.contador++;
        this.btnControler.bind(this)();

    try {
        
        let request = new XMLHttpRequest();
         request.open("POST", `${dominio}/admin/processador.php`);
          request.upload.addEventListener('progress', (event) => {
 
        });


        request.onload = (event) => {
            if (request.status >= 200 && request.status < 300) {
                this.contador--;
                this.btnControler.bind(this)();
                try {
                    
                    var obj = JSON.parse(request.responseText);
                    
                    if(obj.sucesso){
                     
                        this[this.dentro] = [obj.nomeArquivo];
                       
                    }else{
                       alert(obj.mensagem)
                 
                    }

                } catch (error) {
                    console.error('Erro ao fazer parse da resposta:', error);
                }
            } else {
                console.error('Erro durante a requisição. Status:', request.status);
                // Tratamento de erro, se necessário
            }
        };

        request.onerror = (event) => {
            console.error('Erro durante a requisição.');
            // Tratamento de erro, se necessário
        };

      
        request.send(data);
    } catch (error) {
        console.error('Erro durante o envio:', error);
    
    }
}

    salvar(){
        

        var request = new RequestRote("perfil", "perfil");
        request.addData({
            "perfil": this.urlPerfil,
            "capa": this.urlCapa,
            "acao": "fotos"
        })
        
        request.send().then((obj)=>{
            this.pai.update();
             start.ajax();
                Swal.fire({
                    icon: "success",
                    title: "Sucesso",
                    showConfirmButton: false,
                    timer: 1500
                });
        }, (obj)=>{
            
        })
     
        
        
    }

}

class PaginaPerfilSenha{
    constructor(){
        this.trocar = document.getElementById("trocar")
        this.primario = document.getElementById("primario")
        this.secundario = document.getElementById("secundario")
        this.btnSalvar = document.getElementById("salvaTroca")
        this.btnCancelar = document.getElementById("cancelaTroca")
        
        this.senha = document.getElementById("senha")
        this.repitaSenha = document.getElementById("repitaSenha")
        
        
        
        evento(this.trocar, "click", this.troca.bind(this))
        evento(this.btnCancelar, "click", this.cancela.bind(this))
        evento(this.btnSalvar, "click", this.salvar.bind(this))
        evento(Array.from(document.getElementsByClassName("showPass")), "click", this.show.bind(this))
        evento(document.getElementById("gerar"), "click", this.gerarSenha.bind(this))
    }
    
    show(){
        var item = event.currentTarget;
        var input = item.closest(".position-relative").getElementsByTagName("input")[0]
        if(item.classList.contains("show")){
            item.classList.remove("show")
            item.getElementsByTagName("I")[0].className = "bi bi-eye"
            input.type = "password"
        }else{
            item.classList.add("show")
            item.getElementsByTagName("I")[0].className = "bi bi-eye-slash"
            input.type = "text"
        }
    }
    
    troca(){
        this.senha.value = ""
        this.repitaSenha.value = "";
        this.primario.classList.add("d-none")
        this.secundario.classList.remove("d-none")
    }
    
    cancela(){
        this.primario.classList.remove("d-none")
        this.secundario.classList.add("d-none")
    }
    
    nivel(a,b,c){
        this.erroMsg = c;
    }
    
    gerarSenha() {
          document.getElementsByClassName("showPass")[0].classList.remove("show");
        document.getElementsByClassName("showPass")[0].click();
    const letrasMaiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const letrasMinusculas = 'abcdefghijklmnopqrstuvwxyz';
    const numeros = '0123456789';
    const especiais = '-*_/';
    const caracteres = letrasMaiusculas + letrasMinusculas + numeros + especiais;

    let senha = '';

    // Garante ao menos uma letra maiúscula, uma minúscula, um número e um caractere especial
    senha += letrasMaiusculas[Math.floor(Math.random() * letrasMaiusculas.length)];
    senha += letrasMinusculas[Math.floor(Math.random() * letrasMinusculas.length)];
    senha += numeros[Math.floor(Math.random() * numeros.length)];
    senha += especiais[Math.floor(Math.random() * especiais.length)];

    // Completa os 12 caracteres com os caracteres misturados
    for (let i = senha.length; i < 12; i++) {
        const randomIndex = Math.floor(Math.random() * caracteres.length);
        senha += caracteres[randomIndex];
    }

    // Embaralha a senha para que os primeiros caracteres não sejam previsíveis
    senha = senha.split('').sort(() => Math.random() - 0.5).join('');

       this.senha.value = senha;
    this.repitaSenha.value = senha;
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
    
    salvar(){
        if(!this.validasenha.bind(this)()){
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Senha muito fraca!",
                showConfirmButton: false,
                timer: 1500
            
            });
            return;
        }
        
        if(this.senha.value !=  this.repitaSenha.value){
              Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "As senhas digitadas não coincidem",
                showConfirmButton: false,
                timer: 1500
            
            });
            return;
        }
        
        var request = new Request(`${dominio}/admin/request.php`);
        request.addData({
            mod: "perfil",
            cam: "perfil",
            acao: "senha", 
            senha:this.senha.value
        })
        request.send().then((r)=>{
              Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: "A senha foi alterada com sucesso",
                showConfirmButton: false,
                timer: 1500
            
            });
        }, (r)=>{
            
        })
     
        
        
    }
}

class PaginaPerfilVitais{
    constructor(pai){
        new Mascaras();
        this.pai = pai;
        this.user = this.pai.usuario;
        
        this.metas = this.user.grupos;
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
             
                var data = {
                    "item": this.foco,
                    "novo": this.input.value,
                    "acao": "vital"
                }
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

class BoxSessao{
    constructor(item, container){
        this.container = container
        this.pai = document.getElementById("listaSessoes")
        this.item = item
    }
    
    detector(userAgent) {
    const mobileKeywords = [
        "Mobile",
        "iPhone",
        "iPad",
        "Android",
        "Windows Phone"
    ];

    const platformKeywords = {
        windows: "Windows",
        mac: "Macintosh",
        linux: "Linux"
    };

    const browserKeywords = {
        chrome: "Chrome",
        safari: "Safari",
        firefox: "Firefox",
        edge: "Edge",
        ie: "MSIE",
        opera: "Opera",
        samsung: "SamsungBrowser"
    };

    const isMobile = mobileKeywords.some(keyword => userAgent.includes(keyword));

    if (isMobile) {
        if (userAgent.includes("Android")) {
            return ["Celular", "Android", this.extractBrowser(userAgent, browserKeywords)];
        } else if (userAgent.includes("iPhone") || userAgent.includes("iPad")) {
            return ["Celular", "iOS", this.extractBrowser(userAgent, browserKeywords)];
        } else {
            return ["Celular", "Outro", this.extractBrowser(userAgent, browserKeywords)];
        }
    } else {
        const platform = Object.keys(platformKeywords).find(key => userAgent.includes(platformKeywords[key]));
        if (platform) {
            return ["Computador", platformKeywords[platform], this.extractBrowser(userAgent, browserKeywords)];
        } else {
            const browser = Object.keys(browserKeywords).find(key => userAgent.includes(browserKeywords[key]));
            if (browser) {
                return ["Computador", "Outro", browser];
            } else {
                return ["Indefinido", "Indefinido", "Indefinido"];
            }
        }
    }
}

    extractBrowser(userAgent, browserKeywords) {
    const browser = Object.keys(browserKeywords).find(key => userAgent.includes(browserKeywords[key]));
    return browser ? browserKeywords[browser] : "Outro";
}

    render(){
        // Criação do elemento div
        var info = this.detector.bind(this)(this.item.navegador)
        var dispositivo = info[0]
        var so = info[1]
        var navegador = info[2]

       
        
        var div = document.createElement("DIV")
        div.classList.add("col-12", "col-md-6", "col-lg-3", "p-0","py-2", "p-md-2")
        
        var card = document.createElement("DIV")
        card.classList.add("card", "card-nown")
        
        var header = document.createElement("DIV")
        header.classList.add("card-header", "bg-transparent")
        
        var h2 = document.createElement("H2")
        h2.classList.add("fs-12", "m-0", "fw-700", "text-center")
        h2.innerText = `IP: ${this.item.dispositivo}`
        header.appendChild(h2)
        
        var body = document.createElement("DIV")
        body.classList.add("card-body")
        
        
        
        var icone = document.createElement("I")
        if(dispositivo == "Celular"){
            icone.classList.add("bi","bi-phone", "fs-80")
        }else{
            icone.classList.add("bi","bi-laptop", "fs-80")
        }
        
        var container = document.createElement("DIV")
        container.classList.add("text-center")
        container.appendChild(icone)
        
        const iconsMapping = {
  Windows: `bi-windows`,
  Macintosh: `bi-apple`,
  Linux: `bi-tux`,
  Android: `bi-android`,
  iOS: `bi-apple` 
};

        
        var sistema = document.createElement("H2")
        sistema.classList.add("fs-14", "fw-700", "text-center", "mt-2", "d-flex", "justify-content-center", "gap-2", "align-items-center")
        sistema.innerHTML = `<i class="bi ${iconsMapping[so]}"></i> <span>${so}</span>`
        container.appendChild(sistema)
        
        
        
        
        
        const browserIconsMapping = {
  Chrome: 'bi-browser-chrome',
  Safari: 'bi-browser-safari',
  Firefox: 'bi-browser-firefox',
  Edge: 'bi-browser-edge', 
  MSIE: 'bi-globe2', // Ícone genérico para representar Internet Explorer
  Opera: 'bi-globe2', // Ícone genérico para representar Opera
  SamsungBrowser: 'bi-globe2' // Ícone genérico para representar Samsung Browser
};

        var nav = document.createElement("H2")
        nav.classList.add("fs-14", "fw-700", "text-center", "mt-2", "d-flex", "justify-content-center", "gap-2", "align-items-center", "mt-2", "mb-0")
        nav.innerHTML = `<i class="bi ${browserIconsMapping[navegador]}"></i> <span>${navegador}</span>`
        container.appendChild(nav)

        
        body.appendChild(container)
        

        
        var footer = document.createElement("DIV")
        footer.classList.add("card-footer", "bg-transparent")
        
        var h2 = document.createElement("H2")
        h2.classList.add("fs-12", "fw-500", "text-center", "m-0")
        h2.innerText = dataFormatada(this.item.data, true)
        footer.appendChild(h2)
        
        card.appendChild(header)
        card.appendChild(body)
        card.appendChild(footer)
        
        var botao = document.createElement("BUTTON")
        botao.classList.add("btn", "btn-sm", "btn-danger", "d-block", "mt-2", "w-100")
        evento(botao, "click", this.desconectar.bind(this))
        botao.innerText = "Desconectar"
        footer.appendChild(botao)
        
        div.appendChild(card)
        this.div = div
        this.pai.appendChild(div)
    }
    
    desconectar(){
        Swal.fire({
            icon: "question",
            title: "Desconectar Disposivo?",
            showCancelButton: true,
            confirmButtonText: "Desconectar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                


                this.container.ajax({id: this.item.id, acao: "desconectar"}, this.desconectado.bind(this))
            } 
        });
    }
    
    desconectado(r){

          this.div.remove();
                Swal.fire({
                    icon: "success",
                    title: "Dispositivo Desconectado!",
                    showConfirmButton: false,
                    timer: 1500
                });
                
    }
}

class PaginaPerfilSessoes{
    constructor(){
        this.ajax = this.ajax.bind(this)
        var data = {
            "acao":"listar"
        }
        this.ajax(data, this.listar.bind(this))
        evento(document.getElementById("desconectarTudo"), "click", this.desconectar.bind(this))
    }
    
     desconectar(){
        Swal.fire({
            icon: "question",
            title: "Desconectar de todos os Disposivo?",
            showCancelButton: true,
            confirmButtonText: "Desconectar",
            cancelButtonText: `Cancelar`
        }).then((result) => {
            if (result.isConfirmed) {
                
                var data = {
                    "acao":"desconectarTudo",
                    "id": 0   
                }
                this.ajax(data, this.desconectado.bind(this))
            } 
        });
    }
    
    
    desconectado(r){
        console.log(r)
    }
    
    
    listar(r){
        var i = 0;
        while(i < r.lista.length){
            var item = r.lista[i]
            var box = new BoxSessao(item, this);
            box.render();
            i++;
        }
    }
    
    ajax(data, cb = false){
        
        
        var request = new Request(`${dominio}/admin/gerenciarSessoes.php`);
        request.addData(data)
        request.send().then((r)=>{
            cb(r);
        },(r)=>{
            
        })
        
     
    
    }
}

class ItemEndereco{
    constructor(info){
        this.info = info
    }
    
     createButton(iconName, buttonText) {
    const button = document.createElement("BUTTON");
    button.classList.add("btn", "border-0", "btn-sm");

    const span = document.createElement("SPAN");
    span.classList.add("material-symbols-outlined", "text-dark");
    span.style.fontSize = "16px";
    span.textContent = iconName;

    button.appendChild(span);

    return button;
}
    
    render(){
        let info = this.info
const col = document.createElement("DIV");
    col.classList.add("col-12", "col-xl-6", "p-2");

    const card = document.createElement("DIV");
    card.classList.add("card", "bg-light", "text-dark", "border-0");

    const cardHeader = document.createElement("DIV");
    cardHeader.classList.add("card-header", "bg-transparent", "d-flex", "justify-content-between", "align-items-center");

    const h2 = document.createElement("H2");
    h2.classList.add("m-0", "fs-16", "fw-700");
    h2.textContent = info.nome;

    const buttonContainer = document.createElement("DIV");
    buttonContainer.classList.add("d-flex", "justify-content-end", "gap-2");

    const editButton = this.createButton("edit_square", "Edit");
    const deleteButton = this.createButton("delete", "Delete");

    buttonContainer.appendChild(editButton);
    buttonContainer.appendChild(deleteButton);

    cardHeader.appendChild(h2);
    cardHeader.appendChild(buttonContainer);

    const cardBody = document.createElement("DIV");
    cardBody.classList.add("card-body");

    const ul = document.createElement("UL");
    ul.classList.add("list-group", "list-group-flush");

    const listItems = [
        ` ${info.cep} , ${info.bairro}`,
        ` ${info.rua}, ${info.numero}`,
        ` ${info.cidade}, ${info.uf}`,
        ` ${info.descricao}`
    ];

    listItems.forEach(itemText => {
        if(itemText){
            const li = document.createElement("LI");
            li.classList.add("list-group-item", "bg-light", "text-dark");
            li.textContent = itemText;
            ul.appendChild(li);
        }
    });

    cardBody.appendChild(ul);

    card.appendChild(cardHeader);
    card.appendChild(cardBody);

    col.appendChild(card);

    document.getElementById("listaAdress").appendChild(col);
}
}

class PaginaPerfilEndereco{
    constructor(pai){
        this.pai = pai
        this.form = document.getElementById("formularioEndereco")
        this.btn = document.getElementById("btnEndereco")
        
        evento(this.btn, "click", this.novo.bind(this))
        
        
        this.salvar = document.getElementById("btnSalvar")
        evento(this.salvar, "click", this.salva.bind(this))
        
        this.modal = new bootstrap.Modal('#modalNovoEndereco', {
            backdrop: 'static',
            keyboard: false
        })
        
        this.nome = document.getElementById("nome")
        this.cep = document.getElementById("cep")
        this.endereco = document.getElementById("endereco")
        this.numero = document.getElementById("numero")
        this.complemento = document.getElementById("complemento")
        this.cidade = document.getElementById("cidade")
        this.estado = document.getElementById("estado")
        this.bairro = document.getElementById("bairro")
        this.descricao = document.getElementById("descricao")
        
        this.cepValido = false;
        
        new Mascaras();
        evento(this.cep, "input", this.valida.bind(this))
    }
    
    lista(){
        var data = new FormData();
        data.append("acao", "listaEnderecos");
        this.pai.ajax(data, this.listado.bind(this))
    }
    
    listado(r){
        console.log(r)
        var i = 0;
        while(i < r.lista.length){
            var item = new ItemEndereco(r.lista[i]);
            item.render();
            i++;
        }

    }
    
    salvo(r){
        if(r. status == 1){
            this.novoEndereco.id = r.id
            var item = new ItemEndereco(this.novoEndereco);
            item.render();
            this.modal.hide();
            this.form.reset();
            this.valida.bind(this);

        }else{
            switch(r.log){
                case 1:
                    invalido(this.nome, false, "Esse nome já foi usado, escolha outro");
                    break;
                case 2:
                    invalido(this.cep, false, "Esse endereço já foi cadastrado");
                    invalido(this.numero, false, "Esse endereço já foi cadastrado");
                    break;
            }
            animarCss("#cardEnderecoModal", "shakeX");
        }
        
    }
    
    salva(){
        if(this.nome.value || this.numero.value || !this.cepValido){
             var item = {
            nome: this.nome.value,
            cep: this.cep.value,
            endereco: this.endereco.value,
            numero: this.numero.value,
            complemento: this.complemento.value,
            cidade: this.cidade.value,
            estado: this.estado.value,
            bairro: this.bairro.value,
            descricao: this.descricao.value
            }
            
            this.novoEndereco = item;
            
            var data = new FormData();
            data.append("acao", "novoEndereco")
            data.append("info", JSON.stringify(item))
            this.pai.ajax(data, this.salvo.bind(this))
            
            
        }else{
            
            
            if(!this.nome.value){
                invalido(this.nome, false, "Defina um nome para o endereço");
            }
            
            if(!this.numero.value){
                 invalido(this.numero, false, "Força o número do endereço");
            }
            
            if(!this.cepValido){
                 invalido(this.cep, false, "Digite um CEP válido");
            }
            
            
            animarCss("#cardEnderecoModal", "shakeX");
        }
    }
    
    valida(){
        if(this.cep.value.length == 9){
            var cep = this.cep.value.replace("-", "");
            var request = new XMLHttpRequest();
            request.onload = ()=>{
                var obj = JSON.parse(request.responseText)
                if(!obj.erro){
                    this.bairro.value = obj.bairro
                this.endereco.value = obj.logradouro
                this.estado.value = obj.uf
                this.cidade.value = obj.localidade
                this.cepValido = true;
                this.salvar.removeAttribute("disabled")
                }else{
                    this.salvar.setAttribute("disabled", "")
                    this.cepValido = false;
                }
                
                
      
                
      
            }
            request.open("GET", `https://viacep.com.br/ws/${cep}/json/`)
            request.send()
        }else{
            this.salvar.setAttribute("disabled", "")
        }
    }
    
    
    novo(){
        this.modal.show();
    }
}

class PaginaPerfilPerfil{
    constructor(pai){
 
   
        
        this.pai = pai
        new Mascaras();
        this.temporizador = false;
        
        
           this.inputs = {
            "nome":"inputNome",
            "sobrenome":"inputSobrenome",
            "sobre":"inputSobre",
            "aniversario":"inputAniversario",
            "genero":"inputGenero"
        };
        
        
        for(let c in this.inputs){
             this[c]= document.getElementById(this.inputs[c])
             evento(this[c] , "input", this.digitando.bind(this))
        }
        
       
        
        var request = new Request(`${dominio}/admin/request.php`);
        request.addData({
            "acao":"infoPerfil",
            "mod": "perfil",
            "cam": "perfil"
        })
        request.send().then((r)=>{
            this.monta.bind(this)(r)
        })
        
        this.btnSalvar = document.getElementById("btnSalvar")
        evento(this.btnSalvar, "click", this.salva.bind(this))
    }
    
    digitando(){
        console.log("digitando")
         clearTimeout(this.temporizador);
         this.temporizador = setTimeout(() => this.salva.bind(this)(), 500);
    }
    
    salva(){
        this.btnSalvar.setAttribute("disabled", "")
        this.btnSalvar.innerHTML = `<div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>
        </div>`
        
      
        
        var dados = {};
        
        for(let c in this.inputs){
            dados[c] = document.getElementById(this.inputs[c]).value
        }
        
        
        
          var request = new Request(`${dominio}/admin/request.php`);
        request.addData({
            "acao":"salvar",
            "mod": "perfil",
            "cam": "perfil",
            "infos": JSON.stringify(dados)
        })
        request.send().then((r)=>{
            this.salvo.bind(this)(r)
        })
        
       
        
    }
    
    salvo(r){
        this.btnSalvar.removeAttribute("disabled")
        this.btnSalvar.innerHTML = `Salvar`
        
        iziToast.success({
            icon: 'bi bi-check-circle',
    title: 'Sucesso',
    message: 'Perfil Atualizado com Sucesso'
});
    }
    
    completa(input, valor){
        if(valor){
            input.value = valor
        }
    }
    
    monta(r){
        var campos = r.campos
        console.log(r)
        this.completa(this.nome, campos.nome)
        this.completa(this.genero, campos.genero)
        //this.completa(false, campos.outro-genero)
        this.completa(this.sobre, campos.sobre)
        this.completa(this.sobrenome, campos.sobrenome)
        this.completa(this.aniversario, campos.aniversario)
    }
}

class LinhaPerfilNotificacao{
    constructor(name, config, setup){
        this.config = config;
        this.setup = setup;
        this.name = name;
    }
    
    render(){
        this.li = document.createElement("LI")
        this.li.classList.add("list-group-item","list-group-item","list-group-item-action","py-3")
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        
        var col = document.createElement("DIV")
        col.classList.add("col-8", "fw-500", "fs-16")
        col.innerText = this.name
        row.appendChild(col)
 
        
        
        
        if(this.setup.push){
            var col = document.createElement("DIV")
            col.classList.add("col-1","d-flex","align-items-center","justify-content-center")
            col.title = "Push Notification"
            
            var input = document.createElement("INPUT")
            input.classList.add("form-check-input")
            input.type="checkbox"
           
            col.appendChild(input)
            row.appendChild(col)
        }
        
        if(this.setup.email){
             var col = document.createElement("DIV")
            col.classList.add("col-1","d-flex","align-items-center","justify-content-center")
            col.title = "E-mail"
            
            var input = document.createElement("INPUT")
            input.classList.add("form-check-input")
            input.type="checkbox"
           
            col.appendChild(input)
            row.appendChild(col)

        }
        
        if(this.setup.sms){
            var col = document.createElement("DIV")
            col.classList.add("col-1","d-flex","align-items-center","justify-content-center")
            col.title = "SMS"
            
            var input = document.createElement("INPUT")
            input.classList.add("form-check-input")
            input.type="checkbox"
           
            col.appendChild(input)
            row.appendChild(col)

        }
        
        if(this.setup.whatsapp){
            var col = document.createElement("DIV")
            col.classList.add("col-1","d-flex","align-items-center","justify-content-center")
            col.title = "Whatsapp"
            
            var input = document.createElement("INPUT")
            input.classList.add("form-check-input")
            input.type="checkbox"
           
            col.appendChild(input)
            row.appendChild(col)

        }
        
        this.li.appendChild(row)
       
        return this.li;
    }
    
    get(){

    }
    
    set(){
        
    }
}

class  PaginaPerfilNotificacoes{
    constructor(){
        this.init.bind(this)();
    }
    
    header(c){
        var pai = document.getElementById("notificationControler");
        
        var card = document.createElement("DIV")
        card.classList.add("card-header")
        
        var row = document.createElement("DIV")
        row.classList.add("row")
        

        var col = document.createElement("DIV")
        col.classList.add("col-12","col-xl-8","fs-16", "fw-700")
        col.innerText = c
        row.appendChild(col)
        
        
        if(this.setup.push){
            var col = document.createElement("DIV")
            col.classList.add("col-3","col-xl-1","fs-14","d-flex","align-items-center","justify-content-center")
            col.title = "Push Notification"
            var ic = document.createElement("I")
            ic.classList.add("bi","bi-app-indicator")
            col.appendChild(ic)
            row.appendChild(col)
        }
        
        if(this.setup.email){
             var col = document.createElement("DIV")
            col.classList.add("col-3","col-xl-1","fs-14","d-flex","align-items-center","justify-content-center")
            col.title = "E-mail"
            var ic = document.createElement("I")
            ic.classList.add("bi","bi-envelope")
            col.appendChild(ic)
            row.appendChild(col)

        }
        
        if(this.setup.sms){
             var col = document.createElement("DIV")
            col.classList.add("col-3","col-xl-1","fs-14","d-flex","align-items-center","justify-content-center")
            col.title = "SMS"
            var ic = document.createElement("I")
            ic.classList.add("bi","bi-phone")
            col.appendChild(ic)
            row.appendChild(col)

        }
        
        if(this.setup.whatsapp){
             var col = document.createElement("DIV")
            col.classList.add("col-3","col-xl-1","fs-14","d-flex","align-items-center","justify-content-center")
            col.title = "Whatsapp"
            var ic = document.createElement("I")
            ic.classList.add("bi","bi-whatsapp")
            col.appendChild(ic)
            row.appendChild(col)

        }
        
        card.appendChild(row)
        pai.appendChild(card)
        

        
    }
    
    body(){
        var pai = document.getElementById("notificationControler");
        var card = document.createElement("DIV")
        card.classList.add("card-body","p-0")
        
        var ul = document.createElement("UL")
        ul.classList.add("list-group","list-group-flush")
        card.appendChild(ul)
        pai.appendChild(card)
        return ul;
    }
    
    init(){
        let request = new Request(`/conteudo/modulos/perfil/admins/notificacao.php`);
        request.addData({"acao": "minhas"})
        request.send().then((r)=>{
            var lista = r.lista;
            this.setup = r.setup;
      
            this.lis = [];
            for(let c in lista){
                this.header(c);
                var ul = this.body();
                
                for(let d in lista[c]){
                    var id = geraId();
                    this.lis[id] = new LinhaPerfilNotificacao(d, lista[c][d], this.setup);
                    ul.appendChild(this.lis[id].render())

                }
            }
        })
    }
}

class Pagina2Fatores{
    constructor(){
        this.init.bind(this)();
    }
    
    init(){
        var data = {};
        data.acao = "init"
        var itens = Array.from(document.getElementsByClassName("btnAcaoSecure"));
        var i = 0;
        while(i < itens.length){
            data[itens[i].dataset.target] = true;
            i++;
        }
        
        var request = new Request(`${dominio}/admin/autenticator.php`);
        request.addData(data)
        request.send().then((r)=>{
            var config = r.setup
            var i = 0;
            while(i < itens.length){
                var item = itens[i]
                var btn = document.createElement("BUTTON")
                btn.classList.add("btn", "d-flex", "justify-content-center", "align-items-center", "gap-2", "btn-nown-style", "btn-n-primaria")
                switch(item.dataset.target){
                    case 'viaNumero':
                          
                        if(config.doisFatores){
                            btn.innerHTML = `
                            <span><i class="bi bi-check2-circle"></i></span>
                            <span>Configurado</span>
                            `
                        }else{
                            btn.innerHTML = `
                            <span><i class="bi bi-circle"></i></span>
                            <span>Configurar</span>
                            `
                        }
                        item.appendChild(btn)
                        break;
                    case 'viaAutenticator':
                       
                        if(config.totp){
                              btn.innerHTML = `
                            <span><i class="bi bi-check2-circle"></i></span>
                            <span>Reconfigurar</span>
                            `
                            
                            evento(btn, "click", ()=>{
                                Swal.fire({
                                    icon: "question", 
  title: "Você tem certeza?",
  text: "O seu código anterior será invalidado com a conclusão da nova configuração.",

  showCancelButton: true,
  confirmButtonText: "Reconfigurar",
  cancelButtonText: `Cancelar`
}).then((result) => {
  if (result.isConfirmed) {
    this.setupTotp.bind(this)();
  } 
});
                            })
                            
                            var button = document.createElement("BUTTON")
                            button.classList.add("btn", "text-primary")
                            button.innerText = "Desvincular"
                            
                            evento(button, "click", ()=>{
                                Swal.fire({
                                    icon: "question",
                                    title: "Atenção!",
                                    text: "Essa ação poderá deixar sua conta menos segura",
                                    showCancelButton: true,
                                    confirmButtonText: "Desvincular"
                                    
                                }).then((result) => {
                                    if (result.isConfirmed) {
     var request = new Request(`${dominio}/admin/autenticator.php`);
                                request.addData({acao: "desvincular"})
                                request.send().then((r)=>{
                                    console.log(r)
                                })
  } 
    
                                });


                               
                            })
                            
                            item.appendChild(button)
        
                        }else{
                              btn.innerHTML = `
                            <span><i class="bi bi-circle"></i></span>
                            <span>Configurar</span>
                            `
                            
                          
                            evento(btn, "click", this.setupTotp.bind(this))
                        }
           
                        break;
                    case 'viaDigital':
                          
                        if(config.digital){
                               btn.innerHTML = `
                            <span><i class="bi bi-check2-circle"></i></span>
                            <span>Configurado</span>
                            `
                        }else{
                             btn.innerHTML = `
                            <span><i class="bi bi-circle"></i></span>
                            <span>Configurar</span>
                            `
                        }
          
                        break;
                }
                
                item.appendChild(btn)
                i++;
            }
            
            
            
        }, (r)=>{
            console.log(r)
        })
        
    }
    
    setupTotp(){
        if(!this.modalTotp){
            this.modalTotp = new bootstrap.Modal('#modalTotp', {
                                keyboard: false
                            }) 
        }
         
        
        
        if(!this.qrGerado){
             new Mascaras([document.getElementById("codigoApp")]);
             
             
             evento(document.getElementById("senhaApp"), "input", this.digitando.bind(this))
             evento(document.getElementById("codigoApp"), "input", this.digitando.bind(this))
             evento(document.getElementById("btnValidar"), "click", this.salvaQr.bind(this))
             

             var request = new Request(`${dominio}/admin/autenticator.php`);
             request.addData({acao: "gerar"})
             request.send().then((r)=>{
                 this.qrGerado = r.qrcode
                 
                 loadResources(`${dominioscript}/assets/bibliotecas/qrcode/index.js`).then(()=>{
            
            
                    var div = document.createElement("DIV")
                    document.getElementById("qrCodeArea").appendChild(div)
                     var qrcode = new QRCode(div, {
                         text: this.qrGerado,
                         width: 150,
                         height: 150,
                         colorDark: "#000000",
                         colorLight: "#ffffff",
                         correctLevel: QRCode.CorrectLevel.H
                     }); 
                 })
             })
        }
         document.getElementById("senhaApp").value = "";
        this.modalTotp.show();
    }
    
    salvaQr(){
        var request = new Request(`${dominio}/admin/autenticator.php`);
        var codigo = document.getElementById("codigoApp").value;
        codigo = codigo.replace(/\s+/g, ''); 
        request.addData({
            acao: "vincular", 
            "senha": document.getElementById("senhaApp").value.trim(), 
            "code": codigo
        })
        request.send().then((r)=>{
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: "Chave de segurança vinculada",
                showConfirmButton: false,
                timer: 1500
            });
            document.getElementById("codigoApp").value = "";
            document.getElementById("senhaApp").value = "";
            

            this.modalTotp.hide();
        },(r)=>{
            console.log(r)
        })
        
    }
    
    digitando(){
        if(document.getElementById("codigoApp").value.length == 7 && document.getElementById("senhaApp").value.length > 6){
            document.getElementById("btnValidar").removeAttribute("disabled")
        }else{ 
            document.getElementById("btnValidar").setAttribute("disabled", "")
        }
    }
}

class PaginaPerfil{
    constructor(canva){
        this.canva = canva;
        var url = window.location.href.replace(`${dominio}/`, "");
        var url = url.split("/");
        if(!url[url.length - 1]){
            url.pop();
        }
        this.foco = url.length == 1 ? "geral" : url[1];
        
        this.ajax = this.ajax.bind(this)
        
        this.conteudo = document.getElementById("render")
        
        this.lista = document.getElementById("listaMenu")
        this.listaMobile = document.getElementById("listaMenuMobile")
        this.itens = [];
        this.topo = document.getElementById("cardTopo")
        this.listaBtns.bind(this)()
        
        
        this.showMobile = false;
        this.btnControleMobile = document.getElementById("controlePerfilMobile")
        evento(this.btnControleMobile, "click", this.opcoesMobile.bind(this))
       
       
    }
    
    opcoesMobile(){
        if(!this.showMobile){
            this.showMobile = true;
             this.btnControleMobile.classList.add("ativo")
             document.getElementById("opcoesPerfil").classList.add("ativo")
        }else{
             this.showMobile = false;
             this.btnControleMobile.classList.remove("ativo")
             document.getElementById("opcoesPerfil").classList.remove("ativo")
        }
       
    }
    
    update(){
        this.clicar = false;
        var data = {"acao":"infos"}
        this.ajax(data, this.memoria.bind(this))
    }
    
    ativa(){
        if(this.showMobile){
             this.showMobile = false;
             this.btnControleMobile.classList.remove("ativo")
             document.getElementById("opcoesPerfil").classList.remove("ativo")
        }
       
        
        
        this.conteudo.innerHTML = `
        <div class="d-flex justify-content-center align-items-center he-200">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`
        var item = event.currentTarget
        for(let i in this.itens){
            this.itens[i].classList.remove("ativo")
            this.itens[i].style = "";
        }
        item.classList.add("ativo")
        var cor = item.dataset.cor;
        var bgcor = item.dataset.bg;
        item.style = `border-right: 4px solid ${cor}; color: ${cor}`;
        this.foco = item.dataset.foco
        window.history.pushState(null, null, `${dominio}/perfil/${this.foco}`);
        this.topo.style = `background-color: ${bgcor}; color: ${cor};`;
        this.topo.getElementsByTagName("h2")[0].innerText = item.dataset.t 
        document.getElementById("currentIcon").className = item.dataset.icon 
        
        var data = {"acao":"html"}
        this.ajax(data, this.render.bind(this))
    }
    
    js(){
        console.log(this.foco)
        switch(this.foco){
            case 'geral':
                var pagina = new PaginaPerfilVitais(this);
                break;
            case 'perfil':
                var pagina = new PaginaPerfilPerfil(this);
                break;
            case 'foto-de-perfil':
                var pagina = new PaginaPerfilFoto(this);
                break;
            case 'senha':
                var pagina = new PaginaPerfilSenha();
                break;
            case 'gerenciar-sessoes':
                var pagina = new PaginaPerfilSessoes();
                break;
            case 'enderecos':
                  var pagina = new PaginaPerfilEndereco(this);
                  pagina.lista();
                break;
            case 'notificacoes':
                var pagina = new PaginaPerfilNotificacoes();
                break;
            case 'autenticacao-de-2-fatores':
                var pagina = new Pagina2Fatores();
                break;
    
        }
    }
    
    render(r){
        if(r.html){
            this.conteudo.innerHTML = ""
            this.conteudo.innerHTML = r.html
            this.js.bind(this)()
        }else{
            this.conteudo.innerHTML = "CONTEUDO NAO ENCONTRADO"
        }
    }
    
    memoria(r){
        if(r.sucesso){
            if(this.clicar){
                this.clicar.click();
            }
            
            this.usuario = r.usuario;


            
            
            if(this.usuario.foto){
                var div = document.getElementById("imgGlobal")
                  var img = trataImagem(this.usuario.foto , "media") ||  "https://fortram.site/avatar/?size=100";
                    div.style = `background-image: url("${img}"); background-size: cover; background-position: center center;`
             
            }
            
            
            this.clicar = false;
        }else{
            alert("erro no usuario");
        }
    }
    
    cliqueMobile(){
        this.canva.hide();
    }
    
    listaBtns(){
        var lista = JSON.parse(document.getElementById("listaSetup").value)
        var clicar = false;
        for(let c in lista){
            var item = lista[c];

          
            if(!item){
                 var li = document.createElement("LI")
                li.classList.add("list-group-item","p-0","border-0")
                var hr = document.createElement("HR")
                li.appendChild(hr)
                this.lista.appendChild(li)
                this.listaMobile.appendChild(li.cloneNode(true))
            }else{
                var btn = document.createElement("BTN")
                btn.classList.add("btn", "list-group-item","list-group-item-action","d-flex","justify-content-start","align-items-center","gap-3","border-bottom-0","py-2","rounded-0", "bg-transparent")
                evento(btn, "click", this.ativa.bind(this))
                btn.dataset.cor = `var(--bs-${item["c"]})`
                btn.dataset.bg = `var(--bs-${item["bg"]}-bg-subtle)`
                btn.dataset.foco = paraUrl(item["n"])
                btn.dataset.t = item["t"]
                btn.dataset.icon = item["i"]
                if(paraUrl(item["n"]) == this.foco){
                    clicar = btn;
                }
                
                var circulo = document.createElement("DIV")
                circulo.classList.add("he-40","wi-40","rounded-circle","d-flex","justify-content-center","align-items-center", `text-${item["bg"]}-emphasis`, `bg-${item["bg"]}-subtle`)
           
                
                var icone = document.createElement("I")
                icone.className = item["i"]
    

                
                circulo.appendChild(icone)
                
                var texto = document.createElement("SPAN")
                texto.innerText = item["n"]
                circulo.appendChild(icone)
                
                btn.appendChild(circulo)
                btn.appendChild(texto)
                this.itens.push(btn)
                this.lista.appendChild(btn)
                var clone = btn.cloneNode(true);
                evento(clone, "click", this.cliqueMobile.bind(this))
                evento(clone, "click", this.ativa.bind(this))
                this.listaMobile.appendChild(clone)
          
        }
        
        
              
        
    }
    
    
    
    
    
    
        if(clicar){
            this.clicar = clicar;
            var data = {"acao":"infos"}
            this.ajax(data, this.memoria.bind(this))
        }
        
            // Obtém uma NodeList de todos os elementos que têm a classe especificada
    const buttons = document.querySelectorAll('.btnLoading');

    // Converte a NodeList em um array para poder usar forEach
    const buttonsArray = Array.from(buttons);

    // Remove cada botão do DOM
    buttonsArray.forEach(button => {
        button.parentNode.removeChild(button);
    });
    }
    
    ajax(data, cb = false){
 
        data.foco = this.foco
        let request = new RequestRote("perfil", "perfil");
        request.addData(data)
        request.send().then((obj)=>{
            cb(obj)
        }, (obj)=>{
            console.log(obj)
        })
        
    }
}

function easyCanva(btn, canva){
    if(btn){
        btn.className = "";
        btn.classList.add("btn-mobile-scroll-indicator")
        btn.innerHTML = `<div class="scroll-indicator"></div>`
    }
    
     var canva = new nownCanvas(canva, {
                dirDesktop : "left",
                dirMobile : "left",
                backdropBlur : false
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
            
            
        evento(btn, "click", ()=>{
            canva.show();
        })
        
         var hammertime = new Hammer(document.getElementById("btnCanva"));
         hammertime.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });
          hammertime.on('pan', (e)=> {
               canva.show();
          })
         return canva;
}

function initPerfil(){
    if(autenticado()){
        var url = window.location.href.split("/perfil")[1]
        let page = false;
        if(!url){
            page = "home;"
        }else{
            var limpa = url.split("#")
            if(limpa.length == 2){
                Swal.fire({
                  icon: "success",
                  title: "Sucesso",
                  text: "Integração feita com Sucesso",
                  showConfirmButton: false,
                  timer: 1500
                });
            }
            var url = limpa[0].split("/")
            var i = 0;
            var final = [];
            while(i < url.length){
                if(url[i]){
                    final.push(url[i])
                }
                i++;
                
            }
            if(final.length == 0){
                page = home;
            }else{
                page = final[0]
            }
        }
        
        switch(page){
            case 'integracoes':
                new Integracoes()
                break;
            default:
                var canva = easyCanva(document.getElementById("btnCanva") , "canvaPerfil")
                new PaginaPerfil(canva);
                break;

        }
        
        
        
        
        
       
        

    }else{
        new Restrito();
    }
}

function paginaPerfil(){
    initPerfil()
}