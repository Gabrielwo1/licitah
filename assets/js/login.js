class GoogleSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;
        this.cadastro = cadastro;
        this.version = parseInt(dataSys(["login-social", "google", "versao"], 1));

        // Carrega a biblioteca apropriada com base na versão
        if (this.version === 1) {
            // Versão 1: Google Sign-In
            nownFiles.add(`https://apis.google.com/js/platform.js`).then(() => {
                this.init();
            });
        } else if (this.version === 2) {
            // Versão 2: Google Identity Services
            nownFiles.add(`https://accounts.google.com/gsi/client`).then(() => {
                this.init();
            });
        }
    }

    onSuccess(response) {
        let infos;

        if (this.version === 1) {
            // Versão 1: Google Sign-In
            var perfil = response.getBasicProfile();
            infos = {
                login: "google",
                id: perfil.getId(),
                nome: perfil.getName(),
                imagemURL: perfil.getImageUrl(),
                email: perfil.getEmail()
            };
        } else if (this.version === 2) {
            // Versão 2: Google Identity Services
            // Decodifica o token JWT para extrair informações
            const credential = response.credential;
            const payload = JSON.parse(atob(credential.split('.')[1]));
            infos = {
                login: "google",
                id: payload.sub, // sub é o ID único do usuário
                nome: payload.name,
                imagemURL: payload.picture,
                email: payload.email
            };
        }

        // Envia os dados para o backend
        var data = new FormData();
        data.append("acao", this.cadastro ? "cadastroSocial" : "loginSocial");
        data.append("infos", JSON.stringify(infos));
        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        iziToast.error({
            icon: "bi bi-shield-lock-fill",
            title: `Acesso Negado`,
            message: 'Verifique suas credenciais'
        });
        this.pai.ativaTudo();
        console.error('Erro ao fazer login:', error);
    }

    init() {
        var key = dataSys(["login-social", "google", "id"], false);
        if (!key) {
            console.error('Client ID não configurado.');
            return;
        }

        if (this.version === 1) {
            // Versão 1: Google Sign-In
            gapi.load('auth2', () => {
                this.auth2 = gapi.auth2.init({
                    client_id: key,
                    scope: 'profile',
                    plugin_name: "nown"
                });

                if (this.btn) {
                    this.auth2.attachClickHandler(
                        this.btn,
                        {},
                        this.onSuccess.bind(this),
                        this.onFailure.bind(this)
                    );
                } else {
                    console.error('Botão não encontrado.');
                }
            });
        } else if (this.version === 2) {
            // Versão 2: Google Identity Services
            if (!window.google) {
                console.error('Biblioteca Google Identity Services não carregada.');
                return;
            }

            // Inicializa o GSI
            window.google.accounts.id.initialize({
                client_id: key,
                callback: this.onSuccess.bind(this),
                error_callback: this.onFailure.bind(this)
            });

            if (this.btn) {
                // Renderiza o botão de login do Google
                window.google.accounts.id.renderButton(this.btn, {
                    theme: 'outline',
                    size: 'large',
                    text: 'signin_with'
                });
            } else {
                console.error('Botão não encontrado.');
            }
        }
    }
}

class TikTokSocial {
    constructor(pai, btn, cadastro) {
        this.pai = pai
        this.btn = btn;
        this.init();
    }

    init() {

        evento( this.btn ,'click', this.acesso.bind(this));
        console.log('Inicialização concluída.');
    }

    acesso() {
        const clientKey = 'aw7v0duj8jt3mltf';
        const redirectUri = 'https://nown.com.br/tikers';

        const csrfState = Math.random().toString(36).substring(2);

        const url = new URL('https://www.tiktok.com/v2/auth/authorize/');
        url.searchParams.append('client_key', clientKey);
        url.searchParams.append('scope', 'user.info.basic');
        url.searchParams.append('response_type', 'code');
        url.searchParams.append('redirect_uri', redirectUri);
        url.searchParams.append('state', csrfState);

        this.openModal(url.href);
    }

    
    openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
    
    openWindow(url) {
        window.open(url);
    }
}

class AppleSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        const { user } = response;
        const infos = {
            id: user,
            nome: user.name.firstName,
            email: user.email,
            login: 'apple'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com a Apple:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithApple();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithApple() {
        if (!window.AppleID) {
            console.error('A biblioteca de autenticação da Apple não está disponível.');
            return;
        }

        AppleID.auth.init({
            clientId: 'seu_client_id',
            scope: 'email name',
            redirectURI: 'https://seusite.com/apple-auth-redirect' // Substitua pelo seu URL de redirecionamento
        });

        AppleID.auth.signIn({
            scope: 'email name',
            state: 'state',
            nonce: 'nonce',
            usePopup: true // Use popup para o login da Apple
        })
        .then((response) => {
            this.onSuccess(response);
        })
        .catch((error) => {
            this.onFailure(error);
        });
    }
}

class FacebookSocial {
    constructor(pai, btn, cadastro) {
  
        this.btn = btn;
        this.pai = pai;
        this.cadastro = cadastro;
        this.btnOriginalText = this.btn ? this.btn.innerHTML : '';
        this.btnOriginalDisabled = this.btn ? this.btn.disabled : false;
        this.init();
    }

    onSuccess(response) {
        if (response.authResponse) {
            FB.api('/me', { fields: 'id, name, email, picture' }, (userInfo) => {
                try {
                    console.log(userInfo);
                    if (!userInfo || userInfo.error) {
                        throw new Error(userInfo.error?.message || 'Falha ao obter informações do usuário');
                    }
                    
                    const infos = {
                        login: 'facebook',
                        id: userInfo.id,
                        nome: userInfo.name,
                        email: userInfo.email,
                        imagemURL: userInfo.picture?.data?.url || null
                    };
                    
                    // Verifica se o email foi obtido
                    if (!infos.email) {
                        throw new Error('Não foi possível obter o email da conta do Facebook');
                    }
                    
                    const data = new FormData();
                    data.append('acao', this.cadastro ? "cadastroSocial" : "loginSocial");
                    data.append('infos', JSON.stringify(infos));
                    this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
                } catch (error) {
                    this.onFailure(error);
                }
            });
        } else {
            this.onFailure(new Error('Autorização negada pelo Facebook'));
        }
    }

    onFailure(error) {
        
        iziToast.error({
            icon: "bi bi-shield-lock-fill",
            title: `Acesso Negado`,
            message: error?.message || 'Verifique suas credenciais'
        });
        
        // Restaura o botão ao estado original
        this.restauraBotao();
        
        // Ativa todos os elementos do formulário
        this.pai.ativaTudo();
        
        console.error('Erro ao fazer login no Facebook:', error);
    }
    
    // Método para restaurar o botão ao estado original
    restauraBotao() {
        if (this.btn) {
            this.btn.innerHTML = this.btnOriginalText;
            this.btn.disabled = this.btnOriginalDisabled;
        }
    }

    init() {
        try {
            const appId = dataSys(["login-social", "facebook", "id"], false);
            if (!appId) {
               this.btn.setAttribute("disabled", "");
            }
            
            window.fbAsyncInit = () => {
                try {
                    FB.init({
                        appId: appId,
                        cookie: true,
                        xfbml: true,
                        version: dataSys(["login-social", "facebook", "versao"], 'v17.0')
                    });
                    
                    FB.AppEvents.logPageView();
                    
                    if (this.btn) {
                        this.btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            // Salva o estado original do botão antes de modificá-lo
                            this.btnOriginalText = this.btn.innerHTML;
                            this.btnOriginalDisabled = this.btn.disabled;
                            
                            // Modifica o botão para indicar que está processando
                            this.btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processando...';
                            this.btn.disabled = true;
                            
                            FB.login(this.checkLoginState.bind(this), { scope: 'email,public_profile' });
                        });
                    } else {
                        throw new Error('Botão não encontrado');
                    }
                } catch (error) {
                    this.onFailure(error);
                }
            };
            
            // Carregar o SDK do Facebook
            (function(d, s, id) {
                let js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
            
        } catch (error) {
            this.onFailure(error);
        }
    }
    
    checkLoginState() {
        try {
            FB.getLoginStatus((response) => {
                this.statusChangeCallback(response);
            });
        } catch (error) {
            this.onFailure(error);
        }
    }
    
    statusChangeCallback(response) {
        if (response.status === 'connected') {
            this.onSuccess(response);
        } else {
            let mensagem = 'Falha na autenticação';
            if (response.status === 'not_authorized') {
                mensagem = 'Permissão negada para o aplicativo';
            } else if (response.status === 'unknown') {
                mensagem = 'Usuário não está logado no Facebook';
            }
            this.onFailure(new Error(mensagem));
        }
    }
} 

class GithubSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        const { id, login, avatar_url, email, name } = response.data;

        const infos = {
            id,
            nome: name,
            email,
            login,
            foto: avatar_url,
            login: 'github'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com o GitHub:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithGithub();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithGithub() {
        // Substitua 'seu_client_id' pelo seu Client ID do GitHub
        const client_id = 'seu_client_id';
        const redirect_uri = 'https://seusite.com/github-auth-redirect'; // Substitua pelo seu URL de redirecionamento

        const url = `https://github.com/login/oauth/authorize?client_id=${client_id}&redirect_uri=${redirect_uri}&scope=user:email`;

        // Abre uma nova janela ou redireciona para o URL de autorização do GitHub
        this.openModal(url);
    }
    
        openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
}

class AmazonSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(profile) {
        const { name, email, user_id, profile_picture } = profile;

        const infos = {
            id: user_id,
            nome: name,
            email,
            foto: profile_picture,
            login: 'amazon'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com a Amazon:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithAmazon();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithAmazon() {
        // Substitua 'seu_client_id' pelo seu Client ID da Amazon
        const client_id = 'seu_client_id';
        const redirect_uri = 'https://seusite.com/amazon-auth-redirect'; // Substitua pelo seu URL de redirecionamento

        const url = `https://www.amazon.com/ap/oa?client_id=${client_id}&scope=profile&response_type=token&redirect_uri=${redirect_uri}`;

        // Abre uma nova janela ou redireciona para o URL de autorização da Amazon
        this.openModal(url);
    }
    
     openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
}

class MicrosoftSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        const { id, displayName, mail, userPrincipalName } = response;

        const infos = {
            id,
            nome: displayName,
            email: mail || userPrincipalName,
            login: 'microsoft'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com a Microsoft:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithMicrosoft();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithMicrosoft() {
        // Substitua 'seu_client_id' pelo seu Client ID da Microsoft
        const client_id = 'seu_client_id';
        const redirect_uri = 'https://seusite.com/microsoft-auth-redirect'; // Substitua pelo seu URL de redirecionamento

        const url = `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=${client_id}&response_type=token&redirect_uri=${redirect_uri}&scope=User.Read`;

        // Abre uma nova janela ou redireciona para o URL de autorização da Microsoft
        this.openModal(url);
    }
    
     openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
}

class LinkedInSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        const { id, firstName, lastName, profilePicture, emailAddress } = response;

        const infos = {
            id,
            nome: `${firstName} ${lastName}`,
            email: emailAddress,
            foto: profilePicture,
            login: 'linkedin'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com o LinkedIn:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithLinkedIn();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithLinkedIn() {
        // Substitua 'seu_client_id' e 'seu_redirect_uri' pelos valores do seu aplicativo do LinkedIn
        const client_id = 'seu_client_id';
        const redirect_uri = 'https://seusite.com/linkedin-auth-redirect'; // Substitua pelo seu URL de redirecionamento

        const url = `https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=${client_id}&redirect_uri=${redirect_uri}&scope=r_liteprofile%20r_emailaddress`;


        this.openModal(url);
    }
    
    openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
}

class TwitterSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        const { user_id, screen_name, profile_image_url_https, email } = response;

        const infos = {
            id: user_id,
            nome: screen_name,
            email: email || '', // O Twitter não fornece o email do usuário diretamente
            foto: profile_image_url_https,
            login: 'twitter'
        };

        const data = new FormData();
        data.append('acao', 'loginSocial');
        data.append('infos', JSON.stringify(infos));

        this.pai.pai.pai.ajax(data, this.pai.pai.sucesso);
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com o Twitter:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithTwitter();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithTwitter() {
        const oauthScript = document.createElement('script');
        oauthScript.src = 'https://platform.twitter.com/widgets.js';
        oauthScript.async = true;

        // Carrega o script do Twitter e aguarda o carregamento completo
        oauthScript.onload = () => {
            // Redireciona para a página de autenticação do Twitter
            window.open('https://api.twitter.com/oauth/authenticate?oauth_token=SEU_OAUTH_TOKEN', 'twitter', 'width=600,height=400');
        };

        document.head.appendChild(oauthScript);

        // Em sua aplicação, você precisará substituir 'SEU_OAUTH_TOKEN' pelo token obtido anteriormente do Twitter
    }
}

class GovBrSocial {
    constructor(pai, btn, cadastro) {
        this.btn = btn;
        this.pai = pai;

        this.init();
    }

    onSuccess(response) {
        // Processar dados de sucesso após a autenticação com o Gov.br
        console.log('Login bem-sucedido com Gov.br:', response);
        // Você precisará implementar a lógica para processar a resposta do Gov.br aqui
    }

    onFailure(error) {
        // Manipular falha no login aqui
        this.pai.ativaTudo();
        console.error('Erro ao fazer login com Gov.br:', error);
    }

    init() {
        if (this.btn) {
            this.btn.addEventListener('click', () => {
                this.signInWithGovBr();
            });
        } else {
            console.error('Botão não encontrado.');
        }
    }

    signInWithGovBr() {
        // Substitua 'SEU_CLIENT_ID', 'SEU_REDIRECT_URI' e outras informações com os dados do seu aplicativo Gov.br
        const client_id = 'SEU_CLIENT_ID';
        const redirect_uri = 'SEU_REDIRECT_URI';
        const url = `https://sso.staging.acesso.gov.br/authorize?response_type=code
        &client_id=ec4318d6-f797-4d65-b4f7-39a33bf4d544
        &scope=openid+email+profile
        &redirect_uri=http%3A%2F%2Fappcliente.com.br%2Fphpcliente%2Floginecidadao.Php
        &nonce=3ed8657fd74c&state=358578ce6728b%`;


        this.openModal(url);
    }
    
       openModal(url) {
        const modalFeatures = 'width=600,height=400,left=200,top=100';
        const modalWindow = window.open(url, 'TikTok Auth', modalFeatures);

        if (modalWindow) {
            const checkClosed = setInterval(() => {
                if (modalWindow.closed) {
                     this.pai.ativaTudo()
                    clearInterval(checkClosed);
                    
                }
            }, 1000);
        } else {
            console.error('Não foi possível abrir a janela modal. Verifique se o navegador bloqueou a abertura de pop-ups.');
        }
    }
}

class FluxoSocial{
    constructor(pai, cadastro = false){
        this.pai = pai;
        this.btns = document.getElementsByClassName("btnSocial")
        
        
        var i = 0;
        while(i < this.btns.length){
            var target = this.btns[i].dataset.target
            switch(target){
                case 'google':
                    new GoogleSocial(this, this.btns[i], cadastro)
                    break;
                case 'facebook':
                    new FacebookSocial(this, this.btns[i], cadastro)
                    break;
                case 'tiktok':
                    new TikTokSocial(this, this.btns[i], cadastro);
                    break;
                case 'apple':
                    new AppleSocial(this, this.btns[i], cadastro);
                    break;
                case 'github':
                    new GithubSocial(this, this.btns[i], cadastro);
                    break;
                case 'amazon':
                    new AmazonSocial(this, this.btns[i], cadastro);
                    break;
                case 'microsoft':
                    new MicrosoftSocial(this, this.btns[i], cadastro);
                    break;
                case 'linkedin':
                    new  LinkedInSocial(this, this.btns[i], cadastro);
                    break;
                case 'twiter':
                    new TwitterSocial(this, this.btns[i], cadastro);
                    break;
                case 'gov':
                    new GovBrSocial(this, this.btns[i], cadastro);
                    break;
            }
            evento(this.btns[i], "click", this.desativaTudo.bind(this))
            i++;
        }
    }
    
    
    ativaTudo(){
        this.pai.valida();
        var i = 0;
        while(i < this.btns.length){
            this.btns[i].removeAttribute("disabled")
            i++;
        }
    }
    
    
    desativaTudo(){
        this.pai.pai.loading(this.pai)
        var i = 0;
        while(i < this.btns.length){
            this.btns[i].setAttribute("disabled", "")
            i++;
        }
    }
}

async function redirecionamento(tipo) {
    let url = "";
    let mensagem = "";

    switch (tipo) {
        case 1:
            mensagem = "Login feito com Sucesso!";
            break;
        case 2:
            mensagem = "Cadastro feito com Sucesso!";
            break;
        case 3:
            mensagem = "Senha criada com Sucesso";
            break;
    }
    
    window.dispatchEvent(new Event("usuario-logado"));

    iziToast.success({
        icon: "bi bi-check-circle",
        title: "Sucesso",
        message: mensagem,
    });

    if (pegaLocal("rediraffterlogin")) {
        var redir = JSON.parse(pegaLocal("rediraffterlogin"));
        removeLocal("rediraffterlogin");

        let agora = Date.now();
        let diferencaEmMinutos = (agora - redir.timestamp) / 60000;

        if (diferencaEmMinutos <= 3) {
            url = redir.foco;
        }
    }

    if (tipo === 2 || tipo === 3) {
        var int = parseInt(dataSys(["paginas", "cadastro", "tiporedirecionamento"], 0));

        switch (int) {
            case 1:
                var person = dataSys(["paginas", "cadastro", "urlpersonalizada"], false);
                if (person) {
                    url = person;
                }
                break;

            case 2:
                var produto = dataSys(["paginas", "cadastro", "idprodutocarrinho"], 0);
                if (produto > 0) {
                    let request = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
                    request.addData({
                        "acao": "addCard",
                        "produto": produto,
                        "quantidade": 1
                    });

                    try {
                        let r = await request.send();
                        url = "carrinho"
                    } catch (error) {
                        console.error("Erro ao adicionar produto ao carrinho:", error);
                    }
                }
                break;

            case 3:
                var produto = dataSys(["paginas", "cadastro", "idprodutocheckout"], 0);
                if(produto > 0){
                   let requestCheckout = new Request(`${dominio}/conteudo/modulos/pagamento/admins/produto.php`);
                requestCheckout.addData({
                    "acao": "fechaUnico",
                    "produto": produto,
                    "quantidade": 1
                });

                try {
                    let r = await requestCheckout.send();
                   url = `pagamento/${r.url}`;
                } catch (error) {
                    console.error("Erro ao fechar compra:", error);
                } 
                }
                
                break;
        }
    }

    url = url.startsWith('/') ? url.slice(1) : url;

    goUrl(url);
    document.getElementsByTagName("body")[0].classList.remove("hideTop", "hideLateral");

    var afiliado = pegaLocal("afiliar");
    if (afiliado) {
        removeLocal("afiliar");
        RequestRoute.quick("afiliados", "api", {
            acao: "afiliar",
            afiliado: afiliado
        })
    }

    defineLocal("atualizarAbas", "true");
    setTimeout(() => {
        removeLocal("atualizarAbas");
    }, 1000);
}

function redirectcontrol(tipo){
    RequestRoute.quick("nown", "configuracoes", {
        acao: "afiliar",
        afiliado: afiliado
    }).then((e)=>{
          try {
            var obj = JSON.stringify(e);

  
            cripto(obj).then(hash => {

                    let assinatura = pegaLocal("assinatura");

                    if (assinatura != hash) {
                        defineLocal("nown", obj);
                        defineLocal("assinatura", hash);
                        
                        
                        redirecionamento(tipo);
                        return;

                    }
                    
                })

            } catch (error) {

                redirecionamento(tipo)
                return;
            }
        
    })

}

class WalletLogin {
    constructor() {
        this.buttons = document.querySelectorAll(".loginWallet");
        this.init();
    }
    
    init() {
        this.buttons.forEach(button => {
            button.addEventListener('click', async () => {
                // Salva o texto original do botão
                this.textButton = button.innerHTML;

                // Desabilita e muda o texto do botão
                button.disabled = true;
                button.innerText = 'Conectando...';

                const wallet = button.getAttribute('data-wallet');

                try {
                    // 1) Conecta na carteira
                    const account = await this.connectWallet(wallet);

                    // Se o usuário não concedeu acesso (account é null), encerra
                    if (!account) return;

                    // 2) Solicita o nonce
                    const nonce = await this.getNonce(account, wallet);

                    // 3) Assina o nonce
                    const signature = await this.requestSignature(wallet, account, nonce);

                    // 4) Verifica login no backend
                    await this.verifyLogin(wallet, account, signature);

                } catch (error) {
                    // Se for erro de rejeição (code 4001), podemos tratar diferente
                    if (error.code === 4001) {
                        // Usuário rejeitou a solicitação
                        iziToast.error({
                            title: 'Erro',
                            icon: 'bi bi-x-circle',
                            message: 'Você rejeitou a solicitação da carteira.'
                        });
                        
                    animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                     
                     
                    } else {
                        console.error(error);
                        iziToast.error({
                            title: 'Erro',
                            icon: 'bi bi-x-circle',
                            message: error.message || 'Ocorreu um erro ao conectar.'
                        });
                         animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                    }
                } finally {
                    // Restaura o botão
                    this.resetButton(button);
                }
            });
        });
    }

    resetButton(btn) {
        btn.disabled = false;
        btn.innerHTML = this.textButton;
    }
    
    naoInstalada(wallet) {
        iziToast.error({
            title: 'Atenção',
            icon: 'bi bi-x-circle',
            message: `Carteira ${wallet} não instalada`
        });
            animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
    }

    /**
     * Seleciona o provider certo para cada carteira
     */
    async connectWallet(wallet) {
        let provider;
        switch (wallet) {
            case 'metamask':
                provider = this.getMetamaskProvider();
                if (!provider) {
                    this.naoInstalada('Metamask');
                    return null;
                }
                return await this.requestEvmAccount(provider);

            case 'binance':
                provider = this.getBinanceProvider();
                if (!provider) {
                    this.naoInstalada('Binance');
                    return null;
                }
                return await this.requestEvmAccount(provider);

            case 'phantom':
                if (!window.solana) {
                    this.naoInstalada("Phantom");
                    return null;
                }
                return await this.requestPhantomAccount(window.solana);

            default:
                throw new Error(`Carteira "${wallet}" não suportada.`);
        }
    }

    /**
     * Garante que vamos usar especificamente o provider da Metamask
     */
    getMetamaskProvider() {
        if (window.ethereum && Array.isArray(window.ethereum.providers)) {
            // Se tiver mais de um provider EVM instalado
            return window.ethereum.providers.find((p) => p.isMetaMask);
        }
        // Se tiver somente um provider EVM e for Metamask
        if (window.ethereum?.isMetaMask) {
            return window.ethereum;
        }
        return null;
    }

    /**
     * Garante que vamos usar especificamente o provider da Binance
     */
    getBinanceProvider() {
        if (window.ethereum && Array.isArray(window.ethereum.providers)) {
            return window.ethereum.providers.find((p) => p.isBinanceChain);
        }
        if (window.BinanceChain) {
            return window.BinanceChain;
        }
        return null;
    }

    /**
     * Solicita ao provider EVM (Metamask, Binance) a lista de contas
     * e retorna apenas a primeira
     */
    async requestEvmAccount(provider) {
        try {
            const accounts = await provider.request({ method: 'eth_requestAccounts' });
            return accounts[0];
        } catch (error) {
            // Se o usuário clicar em "Rejeitar", error.code === 4001
            throw error; 
        }
    }

    /**
     * Conecta no Phantom e retorna a publicKey base58
     */
    async requestPhantomAccount(provider) {
        try {
            const response = await provider.connect(); // abre popup Phantom
            return response.publicKey.toString();
        } catch (error) {
            throw error;
        }
    }

    /**
     * Busca o nonce no backend
     */
    async getNonce(account, wallet) {
        const res = await fetch(`${dominio}/conteudo/modulos/web3/admins/acesso.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                acao: 'getnonce',
                wallet,
                account
            })
        });

        const data = await res.json();
        if (!data.sucesso) {
            throw new Error(data.message || 'Erro ao obter nonce.');
        }
        return data.nonce;
    }

    /**
     * Assina o nonce com a carteira escolhida
     */
    async requestSignature(wallet, account, nonce) {
        const message = `LoginNonce: ${nonce}`;
        try {
            switch (wallet) {
                case 'metamask':
                case 'binance':
                    // Se for metamask, usamos provider metamask;
                    // Se for binance, usamos window.BinanceChain
                    const provider = (wallet === 'metamask')
                        ? this.getMetamaskProvider()
                        : this.getBinanceProvider();
                    
                    return await provider.request({
                        method: 'personal_sign',
                        params: [message, account],
                    });

                case 'phantom':
                    const encodedMessage = new TextEncoder().encode(message);
                    const signedMessage = await window.solana.signMessage(encodedMessage, 'utf8');
                    // Converte a assinatura (Uint8Array) para base64
                    return btoa(String.fromCharCode(...new Uint8Array(signedMessage.signature)));

                default:
                    throw new Error('Assinatura não suportada para esta carteira');
            }
        } catch (error) {
            // Trata rejeição do usuário
            throw error;
        }
    }

    /**
     * Envia a assinatura para o backend verificar
     */
    async verifyLogin(wallet, account, signature) {
        const res = await fetch(`${dominio}/conteudo/modulos/web3/admins/acesso.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                acao: 'login',
                wallet,
                account,
                signature
            })
        });
        const data = await res.json();
        
        if (!data.sucesso) {
            throw new Error(data.message || 'Erro na verificação da assinatura.');
        }
        
        // Ajuste: data retornado do backend. 
        // Se for criar sessão, passe o 'data' ou parte dele, não 'r'.
        criaSessao(data);
        start.ajax().then(() => {
            redirecionamento(1);
        });
    }
}

class PaginaLogin{
   constructor(pai){
       window.history.pushState(null, null, "/acesso");
       this.pai = pai;
        this.input = document.getElementById("dadosAcesso")
        
        setTimeout(()=>{
            if(this.input){
                this.input.focus();
            }
        }, 1000)
        
        new WalletLogin();
        
        this.btnContinuar = document.getElementById("btnContinuar")
        evento(this.btnContinuar, "click", this.continuar.bind(this))
        evento(this.input, "keydown", this.detecta.bind(this))
        evento(this.input, "input", this.valida.bind(this))
        
        this.sucesso = this.sucesso.bind(this)
        
        this.data = this.data.bind(this)
        
        var config = pegaLocal("nown");
        if(config){
            this.config = JSON.parse(config).config
        }
        
        if(document.getElementById("qrCodeApp")){
            this.qrCode.bind(this)()
        }
        
        if(document.getElementById("imgAuxiliar")){
            var arquivo = trataImagem(document.getElementById("imgAuxiliar").dataset.src, "media")
            if(arquivo){
                document.getElementById("imgAuxiliar").src = arquivo
            }

        }   
        
        
        new FluxoSocial(this);
        
        var last = pegaLocal("lastLogin");
        if(this.input && last){
            this.input.value = last;
            this.valida.bind(this)();
            
        }
    }
    
    assina(code){
        
        let config = pegaLocal("nown");
        var obj = JSON.parse(config)
        loadResources("https://js.pusher.com/7.2/pusher.min.js").then(()=>{
          
              let pusher = new Pusher(obj.config.apis.websockets.key, {
              cluster: obj.config.apis.websockets.cluster
             });
          
        
             var channel = pusher.subscribe(`login-${code}`);
             channel.bind('login', (data)=> {
                 this.tokenizado.bind(this)(data.message)
             });
            
          
        })
    }
    
    tokenizado(r){
        var data = {};
        data.append("token", r)
        data.append("acao", "loginToken")
          
        RequestRoute.quick("nown", "login", data).then((r)=>{
            this.sucesso.bind(this)(r)
        })
    }
    
    qrCode(){
        let interval;
        loadResources(`${dominioscript}/assets/bibliotecas/qrcode/index.js`).then(()=>{
            
            
            var div = document.getElementById("qrCodeApp");
             if (document.getElementById("qrCodeApp")) {
                document.getElementById("qrCodeApp").innerHTML = "";
                let code = geraId();
                this.assina.bind(this)(code)
                let token = `${dominio}/loginqr/${code}`;
                var qrcode = new QRCode(document.getElementById("qrCodeApp"), {
            text: token,
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        
        
        
        const updateQRCode = () => {
              let code = geraId();
              this.assina.bind(this)(code)
            const newToken =  `${dominio}/loginqr/${code}`;
            qrcode.makeCode(newToken);
           
            if(document.getElementById("qrCodeApp")){
               setTimeout(updateQRCode, 60000);  
            }
        };

        setTimeout(updateQRCode, 60000);      

    } 
            
            
        })
    }
    
    data(pasta, grupo, config, cb = false){
        if(!this.config){
            return cb;
        }
        
        if(!this.config[pasta]){
            return cb;
        }
        
          if(!this.config[pasta][grupo]){
            return cb;
        }
        
          if(!this.config[pasta][grupo][config]){
            return cb;
        }
        
        return this.config[pasta][grupo][config];
    }
    
    tipologin(tipo){
        var config = pegaLocal("nown");
        if(!config){
            return false;
        }
        var config = JSON.parse(config).config

        if(!config.paginas){
            return false;
        }
        
        if(!config.paginas.login){
            return false;
        }
        
        if(config.paginas.login[tipo]){
            return config.paginas.login[tipo]
        }
        return false;
    }

    continuar(){
        this.pai.loading.bind(this)(this)
        let tipo = false;
        
        var filtros = ["email", "cpf", "usuario", "telefone", "cnpj"]
        
        for(let i in filtros){
            var item = filtros[i]

            var teste = new ValidaInfo(this.input.value, item);
            if(teste.valida()){
               tipo = item;
               break;
            }
        }
        
        
        if(!tipo){
             iziToast.error({
                 icon: "bi bi-shield-lock-fill",
                 title: `Acesso Negado`,
                 message: 'Verifique suas credenciais'
             });
            animarCss("#cardLogin", "shakeX");
             navigator.vibrate(500);
            this.pai.ativa.bind(this)(this)
        }else{
            let aut = false;
            if(tipo != "email"){
                aut = this.tipologin(tipo)
            }else{
                aut = true;
            }
         
            
            if(aut){
                if(tipo == "cnpj" || tipo == "cpf"){
                     var valorfinal = this.input.value.replace(/[\s.\-\/]/g, '');
                }else{
                    var valorfinal = this.input.value
                }
               
                
                var url = `${dominio}/acesso/${tipo}/${valorfinal}`
                window.history.pushState(null, null, url);
                new EstruturaDeLogin("senha");
            }else{
                var modos = ["E-mail"];
                if (this.tipologin("cpf")){modos.push("CPF")}
                if (this.tipologin("usuario")){modos.push("Usuário")}
                if (this.tipologin("telefone")){modos.push("Telefone")}
                if (this.tipologin("cnpj")){modos.push("CNPJ")}
                modos = modos.join(",")
                animarCss("#cardLogin", "shakeX");
                navigator.vibrate(500);
                this.pai.ativa.bind(this)(this)
   
                
                Swal.fire({
                    icon: "error",
                    title: "Atenção!",
                    text: `Acesse ussando ${modos}`,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            

        }
        
        

    }
    
    sucesso(r){
        console.log(r)
          if(r.sucesso){
            switch(parseInt(r.status)){
                case 1:
                    //start.ajax();
                    if(document.getElementsByClassName("grecaptcha-badge").length > 0){
                        document.getElementsByClassName("grecaptcha-badge")[0].style.display = "none"
                    }
                    criaSessao(r)
                    
                    start.ajax().then(() => {
                        console.log("sucesso")
                           redirecionamento(1);
    }, (e) => {
    console.log("Rejeitado");
});

                    break;
                case 0:
                    animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                    console.log(this)
                    this.valida.bind(this)()
               
                    break;
                case 2:
                    animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                    new EstruturaDeLogin("bloqueado");
                    defineLocal("secureBlock", r.block);
                    break;
            }
        }
    }
    
    proximo(r){
        document.getElementById("containerCard").innerHTML = r.html
    }
    
    detecta(){
         if (event.key === 'Enter' || event.keyCode === 13) {
             this.continuar.bind(this)()
         }
    }
    
    valida(){
        this.btnContinuar.innerText = "Continuar"
        if(this.input.value){
            
             this.input.value = this.input.value.trim();
   
            this.pai.ativa.bind(this)(this)
        }else{
             this.pai.desativa.bind(this)(this)
        }
    
    }
}

class PaginaSenha{
    constructor(pai){

        this.pai = pai
        var link = window.location.href.replace(`${dominio}/`, "");
        var link = link.split("#")[0]
        var trato = link.split("/");    
        
        this.capcha = false;
        
        
        this.tipo = trato[1].toLowerCase()
        this.credenciais = trato[2].toLowerCase()
        
        defineLocal("lastLogin",  this.credenciais);
        
        
        let mapa = {
            email: "E-mail",
            usuario: "Usuário",
            telefone: "Telefone",
            cpf: "CPF",
            cnpj: "CNPJ"
        }
        
      
        document.getElementById("tipo").innerText = mapa[this.tipo]

        
        var info =  new ValidaInfo(this.credenciais, this.tipo);
        var tratado = info.valida();

        if(tratado){
            this.credenciais = tratado
            document.getElementById("credenciais").innerText = tratado;
            this.estrutura.bind(this)()
            
        }else{
             new EstruturaDeLogin("login");
        }
    }
    
    detecta(){
         if (event.key === 'Enter' || event.keyCode === 13) {
             document.getElementById("btnContinuar").click()
         }
    }
    
    estrutura(){
       
        this.btnContinuar = document.getElementById("btnContinuar")
        this.input = document.getElementById("loginSenha")
        
        setTimeout(()=>{
            this.input.focus();
        }, 1000
        )
        
        if(dataSys(["seguranca", "configuracoes", "recapcha"], false) && dataSys(["seguranca", "configuracoes", "recapchacode"])){
            evento(this.btnContinuar, "click", this.vercapcha.bind(this))
        }else{
            evento(this.btnContinuar, "click", this.requisicao.bind(this))
        }
        
       
        
        
        
        
        
        
        
        evento(this.input, "keydown", this.detecta.bind(this))
        evento(this.input, "INPUT", this.valida.bind(this))
        
        
        this.btnSenha = document.getElementsByClassName("btnTogglePass")
         evento(Array.from(this.btnSenha), "click", this.show.bind(this))
      
        
    }
    
    show(){
        var i = event.currentTarget
        var type = document.getElementById(i.dataset.target).getAttribute('type') == 'password' ? 'text' : 'password';
        document.getElementById(i.dataset.target).setAttribute("type",type)
        document.getElementById(i.dataset.target).focus()
    }
    
    vercapcha(){

      this.pai.loading.bind(this)(this)
      grecaptcha.enterprise.ready(async () => {
        
        
        var capcha = dataSys(["seguranca", "configuracoes", "recapchacode"], false);
        
        if(capcha){
            try{
                const token = await grecaptcha.enterprise.execute(capcha, {action: 'LOGIN'});
                this.capcha = token
                this.requisicao.bind(this)()
            }catch{
                this.requisicao.bind(this)()
            }
            
            
        }else{
            this.requisicao.bind(this)()
        }
     
      
      });
    }
    
    valida(){
         if(this.input.value){
            
             this.input.value = this.input.value.trim();
   
            this.pai.ativa.bind(this)(this)
        }else{
             this.pai.desativa.bind(this)(this)
        }
    
    
    }
    
    requisicao(){
        
        
        RequestRoute.quick("nown", "login", {
        "login":this.credenciais,
        "senha":this.input.value,
        "tipo": this.tipo,
        "capcha":this.capcha,
        "acao":"login"
    }).then((r)=>{
        
        this.sucesso.bind(this)(r);
    }, (r)=>{
               iziToast.error({
            icon: "bi bi-shield-lock-fill",
    title: `Acesso Negado`,
    message: 'Verifique suas credenciais'
});
                   
                   
                    animarCss("#cardSenha", "shakeX");
                     navigator.vibrate(500);
                    this.pai.ativa(this);
    })
    }
    
    sucesso(r){
        
         switch(parseInt(r.status)){
                case 1:
                    
                    
                    defineLocal("token-csrf", r.token)
                    console.log("em teoria deu certo");
                    
                    
                      if(document.getElementsByClassName("grecaptcha-badge").length > 0){
                        document.getElementsByClassName("grecaptcha-badge")[0].style.display = "none"
                    }
                     criaSessao(r)
                    start.ajax().then(()=>{
                         redirecionamento(1);
                    })
                  
                   
                   
                    break;
                case 0:
                   
                      iziToast.error({
            icon: "bi bi-shield-lock-fill",
    title: `Acesso Negado`,
    message: 'Verifique suas credenciais'
});
                   
                   
                    animarCss("#cardSenha", "shakeX");
                     navigator.vibrate(500);
                    this.pai.ativa(this);
                    break;
                case 2:
                    animarCss("#cardSenha", "shakeX");
                     navigator.vibrate(500);
                    new EstruturaDeLogin("bloqueado");
                    defineLocal("secureBlock", r.block);
                    break;
            }
    }
}

class PaginaCadastro{
    constructor(pai){
        window.history.pushState(null, null, "/cadastro");
        
        this.pai = pai
        
        new WalletLogin();
        
        this.inputs = [];
        
        this.nome = document.getElementById("nomeCompleto")
        this.inputs.push(this.nome)
        evento(this.nome , "input", this.valida.bind(this))
        
        
        
        this.email = document.getElementById("email")
        this.inputs.push(this.email)
        evento(this.email, "input", this.valida.bind(this))
        
        if(document.getElementById("cpf")){
            this.cpf = document.getElementById("cpf")
            this.inputs.push(this.cpf)
            evento(this.cpf, "input", this.valida.bind(this))
        }
        
        
        if(document.getElementById("celular")){
            this.celular = document.getElementById("celular")
            this.inputs.push(this.celular)
            evento(this.celular, "input", this.valida.bind(this))
        }
        
        if(document.getElementById("cep")){
            this.cep = document.getElementById("cep")
            this.inputs.push(this.cep)
            evento(this.cep, "input", this.valida.bind(this))
            evento(this.cep, "input", this.cepvalida.bind(this))
            evento(document.getElementsByClassName("cancelCep")[0], "click", ()=>{
                this.cep.value = "";
                   this.cep.removeAttribute("dibabled")
   
                 this.cep.closest(".position-relative").classList.remove("d-none")
                 document.getElementById("resumoEndereco").classList.add("d-none")
                 document.getElementById("resumoEndereco").getElementsByClassName("resumo")[0].innerHTML = "";
                 document.getElementById("numero").closest(".form-floating").classList.add("d-none")
                 document.getElementById("complemento").closest(".form-floating").classList.add("d-none")
                 
                 
                
            })
            
        }
        
        if(document.getElementById("cnpj")){
            this.cnpj = document.getElementById("cnpj")
            this.inputs.push(this.cnpj)
            evento(this.cnpj, "input", this.valida.bind(this))
            evento(this.cnpj, "input", this.validaCNPJ.bind(this))
        }
        
        
        
        
        this.btnContinuar = document.getElementById("btnContinuar")
        evento(this.btnContinuar, "click", this.cadastrar.bind(this))
        
        evento(this.celular , "input", this.valida.bind(this))
        
        var politicas = document.getElementsByClassName("politicas");
        if(politicas.length > 0){
            evento(Array.from(document.getElementsByClassName("showpolitica")), "click", this.showpoliticas.bind(this))
            
            evento(Array.from(politicas), "input", this.valida.bind(this))
        }
        
        new Mascaras();
        this.canvaPoliticas = new bootstrap.Offcanvas('#canvaPoliticas')
        evento(document.getElementById("btnAceitarPolitica"), "click", this.aceitarPolitica.bind(this))
        
        new FluxoSocial(this, true);
    }
    
    cepvalida(){
        if(this.cep.value.length != 9){
            return;
        }
        var resultado = this.cep.closest(".position-relative").getElementsByClassName("resultado")[0]
        this.cep.setAttribute("dibabled", "")
        var cep = this.cep.value.replaceAll("-", "");
        resultado.innerHTML = `
        <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>
        `
        var request = new RequestRote("enderecos","externa");
        request.addData({
            cep: cep
        })
        request.send().then((r)=>{
            console.log(r)
        }, (r)=>{
            if(r.erro){
                iziToast.error({
                    icon: 'bi bi-x-lg',
                    title: 'Atenção',
                    message: 'Digite um CEP válido'
                });
                this.cep.removeAttribute("dibabled")
                animarCss("#cardCadastro", "shakeX");
                resultado.innerHTML = ``
            }else{
                 this.cep.closest(".position-relative").classList.add("d-none")
                 document.getElementById("resumoEndereco").classList.remove("d-none")
                 document.getElementById("resumoEndereco").getElementsByClassName("resumo")[0].innerHTML = `
                 <div class="fs-12">${r.localidade} , ${r.uf}</div>
                <div class="fs-12">${r.logradouro} , ${r.bairro}</div>
                 `
                    
                resultado.innerHTML = ``           
                 
                 
                 
                 document.getElementById("numero").closest(".form-floating").classList.remove("d-none")
                 document.getElementById("complemento").closest(".form-floating").classList.remove("d-none")
            }
        })
     
    }
     
    aceitarPolitica(){
        this.focoPolitica.checked = true;
        this.canvaPoliticas.hide();
        this.valida.bind(this)()
    }
    
    showpoliticas(){
        var btn = event.currentTarget.dataset.target
        this.focoPolitica = document.getElementById(`politica-${btn}`)
        
        
     var politicas = {
         "termos-e-condicoes":"Termos e Condições",
         "politica-de-seguranca-da-informacao":"Politica de Segurança da Informação",
         "politica-anti-spam":"Politica de Anti Span",
        "avisos-legais":"Avisos Legais",
        "politica-de-privacidade-para-criancas":"Politica de Privacidade para Crianças",
        "politica-de-devolucao-e-reembolso":"Política de Devolução e Reembolso",
        "politica-de-cookies":"Politica de Cookies",
        "politica-de-privacidade": "Politica de Privacidade"
    };
        
        if(politicas[btn]){
            document.getElementById("canvaPoliticas").getElementsByTagName("h5")[0].innerText = politicas[btn]
            
            
            RequestRoute.quick("nown", "politicas", {"politica": btn}).then((r)=>{
                document.getElementById("canvaPoliticas").getElementsByClassName("offcanvas-body")[0].innerHTML= r.texto
                document.getElementById("canvaPoliticas").getElementsByClassName("offcanvas-body")[0].scrollTop = 0;
            }, (r)=>{
                console.log(r)
            })
        }
        
        
        
        
    }
    
    validaCNPJ(){
        var cnpj = new ValidaInfo(this.cnpj.value , "cnpj");
        this.cnpjValido = cnpj.valida();
    }
    
    cadastrado(r){
        this.pai.ativa(this)
        if(r.status == 0){
            this.pai.ativa(this)
            animarCss("#cardCadastro", "shakeX");
            navigator.vibrate(500);
            
            invalido(this[r.foco], false, r.mensagem); 
            
            iziToast.error({
                icon: "bi bi-x-circle",
                title: "Atenção",
                message: r.mensagem,
            });

        }
        if(r.status == 1){
            
                 if(document.getElementsByClassName("grecaptcha-badge").length > 0){
                        document.getElementsByClassName("grecaptcha-badge")[0].style.display = "none"
                    }
                   
                    start.ajax().then(()=>{
                          if(r.senha){
                goUrl("");
            }else{
                redirecionamento(2);
            }
                    })
                  
                   
                   
                   
      
           
           
        }
    }
    
    cadastrar(){
        this.pai.loading.bind(this)(this)
        
        var valido = true;
        
        var data = {};
        data["acao"] = "cadastro"
        
        if(this.nome.value && this.nome.value.length > 10 && this.nome.value.split(" ").length > 1){
             data["nome"] = this.nome.value
            invalido(this.nome, true); 
            
        }else{
             invalido(this.nome, false, "Digite um nome válido"); 
             valido = false;
             
             iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "Digite um nome válido",
            });
        }
        

        var teste = new ValidaInfo(this.email.value, "email")
        if(!teste.valida()){
            valido = false;
             invalido(this.email, false, "O E-mail digitado é inválido"); 
             
             iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "O E-mail digitado é inválido",
                });
             
        }else{
            data["email"] = this.email.value
            invalido(this.email, true); 
        }
        
        
        if(this.cpf){
            var teste = new ValidaInfo(this.cpf.value, "cpf")
            if(!teste.valida()){
                valido = false;
                invalido(this.cpf, false, "O CPF digitado é inválido"); 
                
                iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "O CPF digitado é inválido",
                });
                
            }else{
                data["cpf"] = this.cpf.value
                invalido(this.cpf, true); 
            }
        }
        
        if(this.celular){
            var teste = new ValidaInfo(this.celular.value, "telefone")
            if(!teste.valida()){
                valido = false;
                invalido(this.celular, false, "O telefone digitado é inválido");
                
                iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "O telefone digitado é inválido",
                });
                
                
            }else{
                data["celular"] = this.celular.value
                invalido(this.celular, true); 
            }
        }
        
        if(this.cep){
            var teste = new ValidaInfo(this.cep.value, "cep")
            if(!teste.valida()){
                valido = false;
                invalido(this.cep, false, "O CEP digitado é inválido"); 
                
                iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "O CEP digitado é inválido",
                });
                
            }else{
                
                
                var numero = document.getElementById("numero").value
                if(!numero){
                     
                     valido = false;
                     invalido(document.getElementById("numero"), false, "Digite o número do seu endereço"); 
                     
                     iziToast.error({
                         icon: "bi bi-x-circle",
                         title: "Atenção",
                         message: "Não foi digitado um número válido",
                         
                     });
                }
                
                data["complemento"] = document.getElementById("complemento").value
                data["numero"] = document.getElementById("numero").value
                data["cep"] = this.cep.value
                
                

                invalido(this.cep, true); 
            }
        }
        
        if(this.cnpj){
            var teste = new ValidaInfo(this.cnpj.value , "cnpj");
            if(!teste.valida()){
                valido = false;
                invalido(this.cnpj , false, "O CNPJ digitado é inválido"); 
                
                iziToast.error({
                    icon: "bi bi-x-circle",
                    title: "Atenção",
                    message: "O CNPJ digitado é inválido",
                });
                
            }else{
                data["cnpj"] = this.cnpj.value
                invalido(this.cnpj, true); 
            }
        }
        
        
        
        if(valido){
            RequestRoute.quick("nown", "login", data).then((r)=>{
                this.cadastrado.bind(this)(r)
            }, (r)=>{
                console.log(r)
            })
        }else{
            this.pai.ativa(this)
            animarCss("#cardCadastro", "shakeX");
            navigator.vibrate(500);
        }
        
        
        
    }
    
    valida(){
        var i = 0;
        var valido = true;
        while(i < this.inputs.length){
            if(!this.inputs[i].value){
                valido = false;
            }
            i++;
        }
        
        var politicas = document.getElementsByClassName("politicas")
        var i = 0;
        if(politicas.length > 0){
            while(i < politicas.length){
                if(!politicas[i].checked){
                    console.log("validando")
                    valido = false;
                }
                
                i++;
            }
        }
        
        
        
        if(valido){
            this.pai.ativa(this)
        }else{
            this.pai.desativa(this)
        }
    }
    
    sucesso(r){
          if(r.sucesso){
            switch(parseInt(r.status)){
                case 1:
                    if(document.getElementsByClassName("grecaptcha-badge").length > 0){
                        document.getElementsByClassName("grecaptcha-badge")[0].style.display = "none"
                    }
                    criaSessao(r)
                    start.ajax().then(()=>{
                          redirecionamento(1);
                    })
                    
                    
                    
                  
                    break;
                case 0:
                    animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                    console.log(this)
                    this.valida.bind(this)()
               
                    break;
                case 2:
                    animarCss("#cardLogin", "shakeX");
                     navigator.vibrate(500);
                    new EstruturaDeLogin("bloqueado");
                    defineLocal("secureBlock", r.block);
                    break;
            }
        }
    }
}

class PaginaNovaSenha{
    constructor(pai){
        window.history.pushState(null, null, "/nova-senha");
        
        evento(document.getElementsByClassName("btnTogglePass")[0], "click", this.showSenha.bind(this))
        
        this.pai = pai
        this.senha = document.getElementById("criarSenha")
        
        
        setTimeout(()=>{
            this.senha.type = "password"
        }, 1000)
        
        setTimeout(()=>{this.senha.focus()}, 1000)
        evento(this.senha , "INPUT", this.validasenha.bind(this))

        this.btnContinuar = document.getElementById("btnContinuar")
        evento(this.btnContinuar, "click", this.salvar.bind(this))
        evento(document.getElementById("deslogar"), "click", deslogar)
    }
    
    showSenha(){
        if(this.senha.type == "password"){
            this.senha.type = "text"
        }else{
            this.senha.type = "password"
        }
    }
    
    salvar(){
        if(this.validasenha.bind(this)()){
            var data = {};
            data["acao"] = "novaSenha"
            data["senha"] = this.senha.value
            RequestRoute.quick("nown", "login", data).then((r)=>{
                this.salvo.bind(this)(r)
            })
        }
    }
    
    salvo(r){
        if(r.sucesso){
            criaSessao(r);
            start.ajax().then(() => {
                redirecionamento(3);
            })
            
          
        }
    }
    
    nivel(pnt, bg, txt){
         var divs =  document.getElementsByClassName("peso")
          var mensagem = document.getElementById("mensagemPeso")
          
          divs[0].classList.remove("bg-danger", "bg-secondary", "bg-success", "bg-secondary", "bg-info")
          divs[1].classList.remove("bg-danger", "bg-secondary", "bg-success", "bg-secondary", "bg-info")
          divs[2].classList.remove("bg-danger", "bg-secondary", "bg-success", "bg-secondary", "bg-info");
          divs[3].classList.remove("bg-danger", "bg-secondary", "bg-success", "bg-secondary", "bg-info")
          mensagem.classList.remove("text-danger", "text-secondary", "text-success", "text-secondary", "text-info")
          
          
        var i = 0;
        while(i < divs.length){
            if(i < pnt){
                divs[i].classList.add(`bg-${bg}`)
            }else{
                divs[i].classList.add("bg-secondary")
            }
            
            i++;   
        }
        

        mensagem.classList.add(`text-${bg}`)
        mensagem.innerText = txt
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
            this.pai.ativa(this)
            return true
    }else{
            this.pai.desativa(this)
           return false;
        }
        
 
        
  
        
    
  
  }
}

class PaginaRecuperacao{
    constructor(pai){
        window.history.pushState(null, null, "/recuperar-senha");
        this.pai = pai;
        
        this.btnContinuar = document.getElementById("btnRecuperar")
        this.input = document.getElementById("inputRecuperar")
        
        evento(this.btnContinuar, "click", this.recupera.bind(this))
        
        evento(this.input, "input", this.valida.bind(this))
    }
    
    recupera(){
        this.pai.loading(this)
        
        var valido = false;
        var email = new ValidaInfo(this.input.value, "email");
        
        var telefone = new ValidaInfo(this.input.value, "telefone");
        
        if(email.valida() || telefone.valida()){
            var data = {};
            data["acao"] = "recuperacao";
            data["valor"] = this.input.value

            RequestRoute.quick("nown", "login", data).then((r)=>{
                this.recuperado.bind(this)(r)
            })
           
        }else{
            animarCss("#cardRecuperar", "shakeX");
            navigator.vibrate(500);
        }

    }
    
    recuperado(r){
        if(r.resultados == 1){
           defineLocal("tokenRecuperacao", r.publico);
            new EstruturaDeLogin("dois-fatores", false);
        }else{
            document.getElementsByClassName("login-form")[0].innerHTML = `
            <div class="alert alert-success" role="alert">
                Foi enviado uma mensagem para o e-mail e telefone da conta informada, com o processo de recuperação de senha. 
            </div>
            
            `
        }

    }
    
    valida(){
        if(this.input.value){
            this.btnContinuar.removeAttribute("disabled")
        }else{
            this.btnContinuar.setAttribute("disabled", "") 
        }
    }
    
}

class ValidaAcessos {
    constructor() {
        window.history.pushState(null, null, "/valida-acessos");
        
        // Referência aos elementos do DOM
        this.telaReceberCodigo = document.getElementById("telaReceberCodigo");
        this.telaInserirCodigo = document.getElementById("telaInserirCodigo");
        this.inputs = document.querySelectorAll('.nown-control');
        this.btnContinuar = document.getElementById("btnContinuar");
        this.btnReceberCodigo = document.getElementById("btnReceberCodigo");
        this.btnReenviar = document.getElementById("btnReenviar");
        this.tempoReenvio = document.getElementById("tempoReenvio");
        
        // Estado do temporizador
        this.tempoEspera = 30;
        this.temporizadorAtivo = false;
        this.intervaloTemporizador = null;
        
        // Determinar o tipo de validação (email ou telefone)
        this.tipoValidacao = this.determinarTipoValidacao();
        
        // Inicializar os event listeners
        this.inicializarEventos();
        
        // Verificar estado inicial do botão
        this.verificarEstadoBotao();
    }
    
    determinarTipoValidacao() {
        // Verificar qual cabeçalho está presente para determinar o tipo de validação
        const temEmail = document.querySelector('h2 i.bi-envelope') !== null;
        const temTelefone = document.querySelector('h2 i.bi-phone') !== null;
        
        if (temEmail) return 'email';
        if (temTelefone) return 'telefone';
        return 'desconhecido';
    }
    
    inicializarEventos() {
        // Eventos para os botões principais
        evento(this.btnReceberCodigo, 'click', () => this.receberCodigo());
        evento(this.btnReenviar, 'click', () => this.reenviarCodigo());
        evento(this.btnContinuar, 'click', () => this.aoClicarContinuar());
        evento(document.getElementById("deslogar"), "click", this.deslogar);
        
        // Adicionar event listeners para cada campo de input
        this.inputs.forEach((input, index) => {
            // Evento de input (quando o usuário digita)
            evento(input, 'input', (e) => this.aoDigitar(e, index));
            
            // Evento de keydown (para teclas especiais como backspace)
            evento(input, 'keydown', (e) => this.aoTeclarEspecial(e, index));
            
            // Evento de paste (quando o usuário cola)
            evento(input, 'paste', (e) => this.aoColar(e));
            
            // Evento de focus (selecionar todo o texto ao focar)
            evento(input, 'focus', (e) => this.aoFocar(e));
        });
    }
    
    receberCodigo() {
        // Mostrar tela de inserir código e esconder tela de receber código
        this.telaReceberCodigo.style.display = 'none';
        this.telaInserirCodigo.style.display = 'block';
        
        // Simular requisição AJAX
        this.enviarSolicitacaoCodigoAjax();
        
        // Iniciar temporizador para reenvio
        this.iniciarTemporizador();
        
        // Focar no primeiro input
        if (this.inputs.length > 0) {
            this.inputs[0].focus();
        }
    }
    
    reenviarCodigo() {
        // Verificar se o botão não está desabilitado pelo temporizador
        if (!this.temporizadorAtivo) {
            // Simular requisição AJAX para reenvio
            this.enviarSolicitacaoCodigoAjax();
            
            // Iniciar temporizador para reenvio
            this.iniciarTemporizador();
            
            // Limpar campos
            this.limparCampos();
            
            // Focar no primeiro input
            if (this.inputs.length > 0) {
                this.inputs[0].focus();
            }
        }
    }
    
    iniciarTemporizador() {
        // Desabilitar botão de reenvio
        this.btnReenviar.disabled = true;
        this.temporizadorAtivo = true;
        this.tempoEspera = 30;
        
        // Mostrar contador
        this.tempoReenvio.style.display = 'inline';
        this.tempoReenvio.textContent = `(${this.tempoEspera}s)`;
        
        // Limpar intervalo anterior se existir
        if (this.intervaloTemporizador) {
            clearInterval(this.intervaloTemporizador);
        }
        
        // Iniciar novo intervalo
        this.intervaloTemporizador = setInterval(() => {
            this.tempoEspera--;
            this.tempoReenvio.textContent = `(${this.tempoEspera}s)`;
            
            if (this.tempoEspera <= 0) {
                // Habilitar botão novamente quando o tempo acabar
                this.btnReenviar.disabled = false;
                this.temporizadorAtivo = false;
                this.tempoReenvio.style.display = 'none';
                clearInterval(this.intervaloTemporizador);
            }
        }, 1000);
    }
    
    limparCampos() {
        // Limpar todos os campos de input
        this.inputs.forEach(input => {
            input.value = '';
        });
        
        // Desabilitar o botão continuar
        this.verificarEstadoBotao();
    }
    
    aoDigitar(e, index) {
        const valor = e.target.value;
        
        // Garantir que apenas um caractere seja inserido
        if (valor.length > 1) {
            e.target.value = valor.charAt(valor.length - 1);
        }
        
        // Se um caractere foi digitado e não é o último campo, avançar para o próximo
        if (valor.length === 1 && index < this.inputs.length - 1) {
            this.inputs[index + 1].focus();
        }
        
        // Verificar se deve habilitar ou desabilitar o botão
        this.verificarEstadoBotao();
    }
    
    aoTeclarEspecial(e, index) {
        // Se pressionar backspace em um campo vazio, voltar para o campo anterior
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
            this.inputs[index - 1].focus();
        }
    }
    
    aoColar(e) {
        e.preventDefault();
        
        // Obter o texto colado
        const textoColado = (e.clipboardData || window.clipboardData).getData('text');
        const apenasNumeros = textoColado.replace(/\D/g, '');
        
        // Se o texto colado tem pelo menos 4 caracteres
        if (apenasNumeros.length >= 4) {
            // Distribuir os caracteres entre os campos
            for (let i = 0; i < this.inputs.length; i++) {
                if (i < apenasNumeros.length) {
                    this.inputs[i].value = apenasNumeros.charAt(i);
                }
            }
            
            // Verificar estado do botão após colar
            this.verificarEstadoBotao();
        }
    }
    
    aoFocar(e) {
        // Selecionar todo o texto ao focar
        e.target.select();
    }
    
    verificarEstadoBotao() {
        if (this.todosPreenchidos()) {
            // Habilitar o botão se todos os campos estiverem preenchidos
            this.btnContinuar.disabled = false;
        } else {
            // Desabilitar o botão se algum campo estiver vazio
            this.btnContinuar.disabled = true;
        }
    }
    
    todosPreenchidos() {
        return Array.from(this.inputs).every(input => input.value.length === 1);
    }
    
    aoClicarContinuar() {
        // Obter o código completo
        const codigo = Array.from(this.inputs).map(input => input.value).join('');
        console.log(`Código de ${this.tipoValidacao} enviado:`, codigo);
        
        // Chamar a função que no futuro implementará a requisição AJAX
        this.enviarCodigoAjax(codigo);
    }
    
    enviarCodigoAjax(codigo) {
        RequestRoute.quick("nown", "validacao-user", {
               tipo: this.tipoValidacao,
              acao: "validar",
              token: codigo
        }).then((r)=>{
            redirecionamento(1);
        }, (r)=>{
            console.log(r)
        })
    }
    
    enviarSolicitacaoCodigoAjax() {
        // Função vazia para futura implementação AJAX de solicitação de código
        console.log(`Solicitando código de ${this.tipoValidacao}...`);
        
        RequestRoute.quick("nown", "validacao-user", {
            tipo: this.tipoValidacao,
            acao: "gerar"
        }).then((r)=>{
            iziToast.sucess({
                title: this.tipoValidacao == "email" ? "Email enviado com Sucesso" : 'Whatsapp enviado com Sucesso',
                message:  this.tipoValidacao == "email" ? "Verifique sua caixa de entrada" : 'Verifique suas mensagens no aplicativo',
                icon: this.tipoValidacao == "email" ? "bi bi-envelope" : 'bi bi-whatsapp' 
            });
            console.log(r)
        }, (r)=>{
            console.log(r)
        })
    }
    
    deslogar() {
        deslogar();
    }
}

class DoisFatores{
    constructor(pai){
        this.pai = pai;
        
        var url = window.location.href
        var trato = url.split("/dois-fatores/");
        
        if(trato.length == 2 && trato[1].length == 32){
                this.token = trato[1]
        }else{
            
            var teste = pegaLocal("tokenRecuperacao");
            if(!teste){
                new EstruturaDeLogin("login", false);
                return;
            }else{
                this.token = teste
            }
            
            
        }
        
        window.history.pushState(null, null, `/dois-fatores/${this.token}`);
            
        this.inputs = document.getElementsByClassName("nown-control")
        
        setTimeout(()=>{
            this.inputs[0].focus();
        }, 500)
        
        evento(Array.from(this.inputs), "input", this.valida.bind(this))
        evento(Array.from(this.inputs), "keydown", this.validaDelete.bind(this))
        evento(this.inputs[0], "paste", this.colando.bind(this))
        
        
        this.btnContinuar = document.getElementById("btnContinuar")
        evento(this.btnContinuar, "click", this.recupera.bind(this))

        
    }
    
    colando() {
   
    event.preventDefault();


    if (event.clipboardData || window.clipboardData) {
        const clipboardData = event.clipboardData || window.clipboardData;
        const pastedText = clipboardData.getData('Text'); // Obtém o texto do clipboard
        
        if(pastedText.length == 6){
            var i = 0;
            while(i < 6){
                this.inputs[i].value = pastedText[i].toUpperCase();
                
                i++;
            }
            const allFilled = Array.from(this.inputs).every(input => input.value);
            this.btnContinuar.disabled = !allFilled; 
        }
    }
}
    
    recupera(){
        var string = "";
        var i = 0;
        var valido = true;
        while(i < this.inputs.length){
            if(!this.inputs[i].value){
                valido = false;
            }else{
                string = `${string}${this.inputs[i].value}`
            }
            i++
            
        }
       
       if(valido){
            var data = {};
            data["acao"] = "validaToken"
            data["privado"] = string
            data["publico"] = this.token
            this.pai.loading(this);

            RequestRoute.quick("nown", "login", data).then((r)=>{
                this.validado.bind(this)(r)
            })

       }else{
            this.btnContinuar.disabled = true;
       }
    }
    
    validado(r){
        switch(parseInt(r.status)){
            case 0:
                animarCss("#cardDoisFatores", "shakeX");
                navigator.vibrate(500);
                this.pai.ativa.bind(this)(this)
                this.inputs[0].focus();
                break;
            case 1:
                goUrl("senha");
                break;
            case 2:
                window.history.pushState(null, null, `/dois-fatores/${r.novo.publico}`);
                defineLocal("tokenRecuperacao", r.novo.publico);
                new EstruturaDeLogin("dois-fatores", false);
                break;
        } 
    }
    
    validaDelete(){
 

        const index = parseInt(event.currentTarget.dataset.index);
        if(event.key == "Backspace" || event.key == "Delete"){
            event.preventDefault()
            if(event.currentTarget.value){
                event.currentTarget.value = ""
            }else{
                if(index != 0){
                    this.inputs[index - 1].focus();
                }
            }
        }else{
            if(event.key == "ArrowRight" || event.key == "ArrowLeft"){
                if(event.key == "ArrowRight" && this.inputs[index + 1]){
                    this.inputs[index + 1].focus();
                }
                
                 if(event.key == "ArrowLeft" && this.inputs[index - 1]){
                    this.inputs[index - 1].focus();
                }
            }else{
                event.currentTarget.value = ""
            }
            
        }
        
        const allFilled = Array.from(this.inputs).every(input => input.value);
        this.btnContinuar.disabled = !allFilled; 
        
                
    }
    
    valida(event){

    event.currentTarget.value = event.currentTarget.value.toUpperCase();


    const index = parseInt(event.currentTarget.dataset.index);
    const isDeletion = event.inputType === "deleteContentBackward";
    
    
    const allFilled = Array.from(this.inputs).every(input => input.value);
    console.log(this.inputs)
    this.btnContinuar.disabled = !allFilled; 

  
        
    if (!isDeletion) {
        if (index < this.inputs.length - 1) {
            this.inputs[index + 1].focus();
        } else if(allFilled) { 
            
            this.btnContinuar.click();
        }
    } 
}
}

class Totp{
    constructor(pai){
        this.pai = pai;
        
   
        
        var url = window.location.href
        var trato = url.split("/totp/");
        
        
        
        window.history.pushState(null, null, `/totp/`);
            
        this.inputs = document.getElementsByClassName("nown-control")
        
        setTimeout(()=>{
            this.inputs[0].focus();
        }, 500)
        
        evento(Array.from(this.inputs), "input", this.valida.bind(this))
        evento(Array.from(this.inputs), "keydown", this.validaDelete.bind(this))
        evento(this.inputs[0], "paste", this.colando.bind(this))
        
        
        this.btnContinuar = document.getElementById("btnContinuar")
        evento(this.btnContinuar, "click", this.recupera.bind(this))

        
    }
    
    colando() {
   
    event.preventDefault();


    if (event.clipboardData || window.clipboardData) {
        const clipboardData = event.clipboardData || window.clipboardData;
        const pastedText = clipboardData.getData('Text'); // Obtém o texto do clipboard
        
        if(pastedText.length == 6){
            var i = 0;
            while(i < 6){
                this.inputs[i].value = pastedText[i].toUpperCase();
                
                i++;
            }
            const allFilled = Array.from(this.inputs).every(input => input.value);
            this.btnContinuar.disabled = !allFilled; 
        }
    }
}
    
    recupera(){
        var string = "";
        var i = 0;
        var valido = true;
        while(i < this.inputs.length){
            if(!this.inputs[i].value){
                valido = false;
            }else{
                string = `${string}${this.inputs[i].value}`
            }
            i++
            
        }
       
       if(valido){
            RequestRoute.quick("nown", "autenticator", {acao: "valida", code: string}).then((r)=>{
                 redirecionamento(1);
            }, (r)=>{
                console.log(r)
            })
   

       }else{
            this.btnContinuar.disabled = true;
       }
    }
    
    validado(r){
        switch(parseInt(r.status)){
            case 0:
                animarCss("#cardDoisFatores", "shakeX");
                navigator.vibrate(500);
                this.pai.ativa.bind(this)(this)
                this.inputs[0].focus();
                break;
            case 1:
                goUrl("senha");
                break;
            case 2:
                window.history.pushState(null, null, `/dois-fatores/${r.novo.publico}`);
                defineLocal("tokenRecuperacao", r.novo.publico);
                new EstruturaDeLogin("dois-fatores", false);
                break;
        } 
    }
    
    validaDelete(){
 

        const index = parseInt(event.currentTarget.dataset.index);
        if(event.key == "Backspace" || event.key == "Delete"){
            event.preventDefault()
            if(event.currentTarget.value){
                event.currentTarget.value = ""
            }else{
                if(index != 0){
                    this.inputs[index - 1].focus();
                }
            }
        }else{
            if(event.key == "ArrowRight" || event.key == "ArrowLeft"){
                if(event.key == "ArrowRight" && this.inputs[index + 1]){
                    this.inputs[index + 1].focus();
                }
                
                 if(event.key == "ArrowLeft" && this.inputs[index - 1]){
                    this.inputs[index - 1].focus();
                }
            }else{
                event.currentTarget.value = ""
            }
            
        }
        
        const allFilled = Array.from(this.inputs).every(input => input.value);
        this.btnContinuar.disabled = !allFilled; 
        
                
    }
    
    valida(event){

    event.currentTarget.value = event.currentTarget.value.toUpperCase();


    const index = parseInt(event.currentTarget.dataset.index);
    const isDeletion = event.inputType === "deleteContentBackward";
    
    
    const allFilled = Array.from(this.inputs).every(input => input.value);
    console.log(this.inputs)
    this.btnContinuar.disabled = !allFilled; 

  
        
    if (!isDeletion) {
        if (index < this.inputs.length - 1) {
            this.inputs[index + 1].focus();
        } else if(allFilled) { 
            
            this.btnContinuar.click();
        }
    } 
}
}
 
class SeletorConta{
    constructor(pai){
         window.history.pushState(null, null, "/conta");
         this.init.bind(this)();
         this.foco = false;
    }
    
    init(){
        
        this.btn = document.getElementById("btnContinuar");
        evento(this.btn, "click", this.continuar.bind(this))
        
        this.modalNovo = new bootstrap.Modal('#modalNovaEmpresa', {
            keyboard: false
        })
        
        this.modalVinculo = new bootstrap.Modal('#modalNovoVinculo', {
            keyboard: false
        })
        
        this.btnCadastro = document.getElementById("btnCadastraEmpresa");
        
        evento(this.btnCadastro, "click", this.cadastrando.bind(this))
        
        this.btnVinculo = document.getElementById("btnCadastraVinculo");
        
        evento(this.btnVinculo, "click", this.vinculando.bind(this))
        
        this.input = document.getElementById("cnpjNova")
        
        this.input2 = document.getElementById("hashNova")
        
        evento(document.getElementById("addEmpresa"), "click", this.novo.bind(this))
        
        evento(document.getElementById("addVinculo"), "click", this.atualiza.bind(this))
        
        evento(document.getElementById("deslogar"), "click", deslogar)
        
        evento(this.input, "input", this.valida.bind(this))
        evento(this.input2, "input", this.validaCodigo.bind(this))
        new Mascaras([this.input]);
        
        
        this.contas = document.getElementsByClassName("btnContas");
        evento(Array.from(this.contas), "click", this.seleciona.bind(this))
    }
    
    cadastrando(){
        this.btnCadastro.setAttribute("disabled", "")
        this.btnCadastro.innerHTML = `<div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>
        </div>`
        this.input.setAttribute("disabled", "")
        
        var v = new ValidaInfo(this.input.value, "cnpj");
        var valido = v.valida();
        if(this.input.value.length != 18 || !valido){
             iziToast.error({
                    icon: 'bi bi-exclamation-circle',
                    title: 'Atenção',
                    message: 'Digite um CNPJ válido'
                });
                return;
        }
        
     
        RequestRoute.quick("empresas", "api", {
            acao: "novo",
            cnpj: this.input.value
        }).then((r)=>{
            this.modalNovo.hide();
            
            iziToast.success({
                    icon: 'bi bi-exclamation-circle',
                    title: 'Sucesso',
                    message: "Empresa Cadastrada com Sucesso"
                });
           
           
           
           
            new EstruturaDeLogin("conta", true);
        }, (r)=>{
            this.btnCadastro.removeAttribute("disabled", "")
             this.btnCadastro.innerHTML = `Cadastrar Empresa`
             this.input.removeAttribute("disabled")
            iziToast.error({
                    icon: 'bi bi-exclamation-circle',
                    title: 'Atenção',
                    message: r.mensagem
                });
        })
    }
    
    vinculando(){
        if(this.input2.value.length < 10){
            iziToast.error({
                icon: 'bi bi-exclamation-circle',
                title: 'Atenção',
                message: 'Digite um código válido'
            });
            return;
        }
        
        this.btnVinculo.setAttribute("disabled", "")
        this.btnVinculo.innerHTML = `<div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>
        </div>`
        this.input2.setAttribute("disabled", "")
        
        RequestRoute.quick("empresas", "api", {
            acao: "vinculo",
            hash: this.input2.value.trim()
        }).then((r)=>{
            
            this.modalVinculo.hide();
            
            iziToast.success({
                icon: 'bi bi-exclamation-circle',
                title: 'Sucesso',
                message: "Empresa Cadastrada com Sucesso"
            });
            
            if(r.hash){
                this.foco = r.hash
            }
            
            
            this.continuar.bind(this)()
        }, (r)=>{
            
            
            this.btnVinculo.removeAttribute("disabled", "")
            this.btnVinculo.innerHTML = `Vincular em Empresa`
            this.input2.removeAttribute("disabled")
            iziToast.error({
                icon: 'bi bi-exclamation-circle',
                title: 'Atenção',
                message: r.mensagem
            });
        })
    }
    
    valida(){
        var valido = false;
        
        if(this.input.value.length == 18){
            var v = new ValidaInfo(this.input.value, "cnpj");
            var valido = v.valida();
            
            
            if(!valido){
                iziToast.error({
                    icon: 'bi bi-exclamation-circle',
                    title: 'Atenção',
                    message: 'Digite um CNPJ válido'
                });
            }
        }
        
        
        
        if(valido){
            this.btnCadastro.removeAttribute("disabled")
            this.cadastrando.bind(this)();
        }
        
    }
    
    validaCodigo(){
        var valido = false;
        
        if(this.input2.value.length >= 10){
            valido = true
        }
        
        
        
        if(valido){
            this.btnVinculo.removeAttribute("disabled")
        }else{
            this.btnVinculo.setAttribute("disabled", '')
        }
    }
    
    novo(){
        this.modalNovo.show();
    }
    
    atualiza(){
        this.modalVinculo.show();
    }
    
    continuar(){
        if(!this.foco){
            Swal.fire({
                title: "Atenção!",
                text: "Para proceseguir, selecione uma conta!",
                icon: "error"
                
            });
            return;
        }
        
        RequestRoute.quick("nown", "login", {"acao": "subconta", "id":this.foco}).then((r)=>{
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: "Conta selecionada com sucesso",
                showConfirmButton: false,
                timer: 1500
            });
            start.ajax();
            goUrl("/")
            
            defineLocal("atualizarAbas", "true");
            setTimeout(()=>{
                removeLocal("atualizarAbas");
            }, 1000)
        }, ()=>{
            console.log(r)
        });
    }
    
    seleciona(){
        var foco = event.currentTarget
        var i = 0;
        while(i < this.contas.length){
            this.contas[i].classList.remove("selecionado", "active");
            i++;
        }
        
        
        foco.classList.add("selecionado", "active")
        this.foco = foco.dataset.foco
        
        this.btn.removeAttribute("disabled")
        
    }
}

class EstruturaDeLogin{
    constructor(pagina, primeiro = false){
         this.pagina = pagina;
        if(primeiro){
            
            RequestRoute.quick("nown", "login", {"acao": "secure"}).then((r)=>{
      
                this.seguro.bind(this)(r);
            }, ()=>{
                console.log("segure deu erro")
            });
        }else{
            this.start.bind(this)();
        }
    }
    
    compararTempo(dataString) {
    var dataFornecida = new Date(dataString); // Convertendo a string para objeto Date
    var dataAtual = new Date(); // Obtendo a data atual

    var diferenca = (dataAtual - dataFornecida) / 1000; // Diferença em segundos

    if (diferenca >= 5 * 60) {
        localStorage.removeItem("secureBlock");
        return 0; 
    } else {
        var segundosRestantes = Math.ceil((5 * 60) - diferenca); // Calculando os segundos restantes
        return segundosRestantes; // Retornando a quantidade de segundos restantes
    }
}
    
    seguro(r){
        console.log(r)
        
        
        if(r.block){
            new EstruturaDeLogin("bloqueado");
            return;
        }
        if(pegaLocal("secureBlock")){
            var data = pegaLocal("secureBlock");
            var segundos = this.compararTempo(data);
            if(segundos > 0){
                new EstruturaDeLogin("bloqueado");
                return;
            }
        }
        
        this.start.bind(this)()
    }
    
    start(){
     
        let map ={
            "login":"cardLogin",
            "cadastro":"cardCadastro",
            "senha": "cardSenha",
            "recuperar": "cardRecuperar",
            "bloqueado": "cardBloqueado",
            "nova-senha": "cardNovaSenha",
            "dois-fatores": "cardDoisFatores",
            "valida-acessos": "cardValida",
            "conta": "cardConta",
            "totp": "cardTotp"
            
        }
        
        this.foco = map[this.pagina];
        
       
        
        if(document.getElementById("containerCard").getElementsByClassName("card-nown").length > 0){
            var id = document.getElementById("containerCard").getElementsByClassName("card-nown")[0].id
            
            let vai = "backOutLeft"
            if(id != "cardLogin"){
                vai = "backOutRight"
            }
  
            
            animarCss(`#${id}`, vai).then(()=>{
            
             document.getElementById(id).classList.add("opacity-0")
              })
              
              setTimeout(()=>{
             
                  new CarregarComponente(
                  "sistema", this.pagina , this.estrutura.bind(this)
                  );
              }, 200)
              
        }else{

             new CarregarComponente(
            "sistema", this.pagina , this.estrutura.bind(this)
        );
        }
        
        
        if(!document.getElementById("googleCaptcha")){
            
              var config = pegaLocal("nown");
              if(config){
                  var obj = JSON.parse(config)
                  
                  if(obj.config && obj.config.seguranca && obj.config.seguranca.configuracoes && obj.config.seguranca.configuracoes.recapcha){
    
                        var script = document.createElement("SCRIPT")
                        script.id = "googleCaptcha"
                        script.src = `https://www.google.com/recaptcha/enterprise.js?render=${obj.config.seguranca.configuracoes.recapchacode}`
                        document.getElementsByTagName("head")[0].appendChild(script) 
                  }
                  
                  
                  
              }
     
            
            

           }
           else{
               if(document.getElementsByClassName("grecaptcha-badge").length > 0){
                        document.getElementsByClassName("grecaptcha-badge")[0].style.display = "block"
                    }
           }
    }
    
    estrutura(r){
        if(r.html){
             document.getElementById("containerCard").innerHTML = r.html
            var logoLogin = document.querySelectorAll(".holder-login-logo img")[0];
            var logoLogin1 = document.querySelectorAll(".holder-login-logo img")[1];
            var positions = document.getElementById(this.foco).getBoundingClientRect();
            logoLogin.style =`top: ${positions.top - 30}px; transform: translate(-50%, -100%);`;
            logoLogin1.style =`top: ${positions.top - 30}px; transform: translate(-50%, -100%);`;
           
            document.getElementById(this.foco).classList.remove("d-none")
            
             let vai = "backInLeft"
            if(this.foco != "cardLogin"){
                vai = "backInRight"
            }
            
            
            animarCss(`#${this.foco}`, vai)


            switch(this.pagina){
                case 'login':
                    new PaginaLogin(this);
                    break;
                case 'cadastro':
                    new PaginaCadastro(this)
                    break;
                case 'senha':
                     new PaginaSenha(this);
                    break;
                case 'nova-senha':
                    new PaginaNovaSenha(this);
                    break;
                case 'recuperar':
                    new PaginaRecuperacao(this);
                    break;
                case 'dois-fatores':
                     new DoisFatores(this);
                    break;
                case 'valida-acessos':
                     new ValidaAcessos(this);
                    break;
                case 'conta':
                    new SeletorConta(this);
                    break;
                case 'totp':
                    new Totp(this);
                    break;
            }
            
            
            this.links = document.getElementsByClassName("goPage")
            evento(Array.from(this.links), "click", this.go.bind(this))
            
        }
    }
    
    go(){
        console.log(event.currentTarget.dataset.page)
        new EstruturaDeLogin(event.currentTarget.dataset.page)
    }
    
    ativa(componente){
        componente.btnContinuar.innerText = "CONTINUAR"
        componente.btnContinuar.removeAttribute("disabled")
        componente.btnContinuar.classList.add("btn-nown-style", "btn-n-primaria")
        
        var inputs = document.getElementsByClassName("form-control")
        var i = 0;
        while(i < inputs.length){
            inputs[i].removeAttribute("readonly")
            inputs[i].removeAttribute("disabled")
            i++;
        }
    }
    
    desativa(componente){
        componente.btnContinuar.setAttribute("disabled", "")
       // componente.btnContinuar.classList.remove("btn-style-nonw", "btn-n-primaria")
    }
    
    loading(componente){
        
        var inputs = document.getElementsByClassName("form-control")
        var i = 0;
        while(i < inputs.length){
            inputs[i].setAttribute("readonly", "")
            inputs[i].setAttribute("disabled", "")
            i++;
        }
        
        componente.btnContinuar.setAttribute("disabled", "")
        componente.btnContinuar.innerHTML = `
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        `
    }
    
    ajax(data, cb= false){
        if(dominio != dominioscript){
            data.append("wildcard", dominio)
        }
        let request = new XMLHttpRequest();
        request.onload = ()=>{
            try{     
                var obj = JSON.parse(request.responseText)
                if(cb){
                    cb(obj)
                }
            }catch(e){
                console.log(e)
                console.log(request.responseText)
            }
        }
        request.open("POST", `${dominio}/admin/login.php`)
        request.send(data)
 
    }
}

function criaSessao(r){
        defineLocal("sessao", r.sessao);
    }

function paginaAcesso(){
    if(document.getElementById("containerCard").dataset.caminho == 3){
        new EstruturaDeLogin("senha", true);
    }else{
        new EstruturaDeLogin("login", true);
    }
    
}

function paginaCadastro(){
    if(dataSys(["geral", "geral", "cadastro"], false)){
         new EstruturaDeLogin("cadastro", true);
    }else{
        Swal.fire({
            icon: "warning",
            title: "Cadastro Desativado",
            text: "O cadastro de novos usuários está desativado pelo administrador",
            showConfirmButton: false,
            timer: 2000
            
        });
        new EstruturaDeLogin("login", true);
    }
   
}

function paginaSenha(){
    new EstruturaDeLogin("nova-senha", true);
}

function paginaTotp(){
    new EstruturaDeLogin("totp", true);
}

function paginaConta(){
     new EstruturaDeLogin("conta", true);
}

function paginaRecuperarSenha(){
    new EstruturaDeLogin("recuperar", true);
}

function paginaDoisFatores(){
     new EstruturaDeLogin("dois-fatores", true);
}

function paginaValidaAcessos(){
   new EstruturaDeLogin("valida-acessos", true);
}