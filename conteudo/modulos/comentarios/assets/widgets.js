class Comentarios {
    constructor() {
        /*
        Para iniciar a classe, precisa criar uma div com a classe comentario
        data-multiple = true - Sinaliza que na página tem varios comentarios e eles vão aparecer via modal tanto no pc quanto desk top
        
        data-modulo - sinaliza o modulo 
        data-url - sinaliza o indentificador unico - caso seja false ou nao exista, pegará a ulr atual
        
        Ordenagens
        Recente, Antigos, Populares
        */
        this.trigers = document.getElementsByClassName("comments");
        if (this.trigers.length === 0) {
            return;
        }
        
        try {
            this.me = autenticado();
            this.eu = JSON.parse(pegaLocal("nown")).usuario.usuario;
            
            this.autores = {};
            this.autores[this.me] = {
                "d": this.eu.nome,
                "u": this.eu.user,
                "f": this.eu.foto
            };
            
            this.inicial = "populares";
            
            // Melhorar carregamento de recursos externos
            var arquivos = [
                `${dominioscript}/assets/aplicativo/zurb/tribute.min.js`, 
                `${dominioscript}/assets/aplicativo/zurb/tribute.css`
            ];
            
            // Carregar biblioteca de emojis (CDN)
            const emojiScript = document.createElement('script');
            emojiScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/emoji-picker-element/1.15.0/index.min.js';
            document.head.appendChild(emojiScript);
            
            const emojiStyle = document.createElement('link');
            emojiStyle.rel = 'stylesheet';
            emojiStyle.href = 'https://cdnjs.cloudflare.com/ajax/libs/emoji-picker-element/1.15.0/index.min.css';
            document.head.appendChild(emojiStyle);
            
            nownFiles.add(arquivos)
                .then(() => this.init())
                .catch(error => console.error("Erro ao carregar recursos:", error));
                
            // Adicionar CSS para melhorar aparência
            this.adicionarEstilos();
        } catch (error) {
            console.error("Erro na inicialização:", error);
        }
    }
    
    // Método para adicionar estilos CSS personalizados
    adicionarEstilos() {
        const styleElement = document.createElement('style');
        styleElement.textContent = `
            .comentario {
                transition: all 0.3s ease;
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 15px;
            }
            
            .comentario:hover {
                background-color: rgba(0, 0, 0, 0.02);
            }
            
            .comentario .comment-text {
                line-height: 1.5;
            }
            
            .inputComentario[contenteditable=true]:empty:before {
                content: attr(placeholder);
                color: #aaa;
                font-style: italic;
            }
            
            .inputComentario:focus {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
                border-color: #86b7fe;
            }
            
            .animate__fadeIn {
                animation-duration: 0.5s;
            }
            
            .btn-n-primaria {
                transition: all 0.2s ease;
            }
            
            .btn-n-primaria:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .caixaResposta {
                margin-left: 10px;
                padding-left: 10px;
                border-left: 1px solid #f0f0f0;
            }
            
            .comments-loader {
                padding: 15px;
                text-align: center;
                color: #6c757d;
            }
            
            .semComentarios {
                padding: 3rem 1rem;
                background-color: rgba(0, 0, 0, 0.01);
                border-radius: 12px;
                text-align: center;
                color: #6c757d;
            }
            
            .semComentarios h2 {
                margin-top: 1rem;
                font-size: 1.5rem;
                font-weight: 600;
                color: #343a40;
            }
            
            /* Estilos para o seletor de emoji */
            #emoji-picker-container {
                position: absolute;
                z-index: 1050; /* Acima de modais bootstrap */
                display: none;
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                border-radius: 12px;
                overflow: hidden;
            }
            
            emoji-picker {
                --emoji-size: 1.5rem;
                --num-columns: 8;
                --emoji-padding: 0.4rem;
                --border-color: #e0e0e0;
                --border-radius: 12px;
                --category-emoji-size: 1.25rem;
                --background: #fff;
                --indicator-color: #0d6efd;
                --input-border-color: #e0e0e0;
                --input-font-color: #333;
                --input-font-size: 0.9rem;
                --input-border-radius: 8px;
                --input-placeholder-color: #aaa;
                width: 320px;
                height: 350px;
            }
        `;
        document.head.appendChild(styleElement);
        
        // Adicionando o container para o emoji picker
        if (!document.getElementById('emoji-picker-container')) {
            const emojiContainer = document.createElement('div');
            emojiContainer.id = 'emoji-picker-container';
            emojiContainer.innerHTML = '<emoji-picker></emoji-picker>';
            document.body.appendChild(emojiContainer);
        }
    }
    
    caixaComentario(id = 0, arroba = false) {
        try {
            // Tratar caso onde a foto do usuário não existe
            var foto = `${dominioscript}/conteudo/modulos/usuarios/midias/perfil.jpg`;

            var ft = trataImagem(this.eu.foto, "mini");
            if (ft) {
                foto = ft;
            }

            var div = document.createElement("DIV");
            div.classList.add("d-flex", "gap-2", "caixaComentario", "mb-3");
            div.innerHTML = `
            <div>
                <div class="wi-35 he-35 he-xl-50 wi-xl-50 rounded-circle shadow-sm" 
                     style="background-image: url(${foto}); background-size: cover; background-position: center;"></div>
            </div>
            <div class="flex-fill position-relative">
                <div contenteditable="true" class="form-control inputComentario shadow-sm border-light" data-id='${id}' 
                     placeholder="Adicione um comentário..."></div>
                <div class="acao d-none">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <button class="btn btn-light rounded-circle emoji-button" title="Inserir emoji" type="button"><i class="bi bi-emoji-smile"></i></button>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light cancelar">Cancelar</button>
                            <button class="btn btn-n-primaria rounded-pill comentar px-4">Comentar</button>
                        </div>
                    </div>
                </div>
            </div>`;
            
            // Inicializar o mencionador
            new Mensionador(div.getElementsByClassName("inputComentario")[0]);
            
            // Configurar eventos com tratamento de erros
            this.setupCommentEvents(div);
            
            if (arroba) {
                try {
                    let inputComentario = div.getElementsByClassName("inputComentario")[0];
                    
                    // Adicionar o texto "@respostaUmUsuario " no input
                    inputComentario.innerText = "@respostaUmUsuario ";
                    
                    // Focar no input
                    inputComentario.focus();
                    
                    // Mover o cursor para o final
                    if (inputComentario.childNodes.length > 0) {
                        const textLength = inputComentario.innerText.length;
                        const range = document.createRange();
                        const selection = window.getSelection();
                        range.setStart(inputComentario.childNodes[0], textLength);
                        range.collapse(true);
                        selection.removeAllRanges();
                        selection.addRange(range);
                        
                        // Disparar o evento de input
                        let event = new Event('input', {
                            bubbles: true,
                            cancelable: true,
                        });
                        inputComentario.dispatchEvent(event);
                    }
                } catch (error) {
                    console.error("Erro ao configurar arroba:", error);
                }
            }
            
            return div;
        } catch (error) {
            console.error("Erro ao criar caixa de comentário:", error);
            return document.createElement("DIV");
        }
    }
    
    // Método para configurar eventos do comentário
    setupCommentEvents(div) {
        try {
            const inputField = div.getElementsByClassName("inputComentario")[0];
            const cancelBtn = div.getElementsByClassName("cancelar")[0];
            const submitBtn = div.getElementsByClassName("comentar")[0];
            const emojiBtn = div.getElementsByClassName("emoji-button")[0];
            
            // Usar referências de método com bind para manter o contexto
            evento(inputField, "input", this.comentando.bind(this));
            
            // Adicionar tratamento de tecla Enter
            evento(inputField, "keydown", (e) => {
                if (e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    this.comentar(e);
                }
            });
            
            evento(cancelBtn, "click", this.cancelar.bind(this));
            evento(submitBtn, "click", this.comentar.bind(this));
            
            // Configurar o botão de emoji
            if (emojiBtn) {
                evento(emojiBtn, "click", (e) => {
                    e.preventDefault();
                    // Armazenar o campo de entrada associado a este botão
                    const currentInput = emojiBtn.closest('.caixaComentario').querySelector('.inputComentario');
                    this.abrirSeletorEmoji(e, currentInput);
                });
            }
        } catch (error) {
            console.error("Erro ao configurar eventos:", error);
        }
    }
    
    // Método para inicializar o seletor de emoji
    inicializarEmojiPicker() {
        try {
            // Verificar se já inicializamos
            if (this.emojiPickerInitialized) return;
            
            // Certificar que o elemento existe
            const emojiPicker = document.querySelector('emoji-picker');
            if (!emojiPicker) return;
            
            this.emojiPickerInitialized = true;
            
            // Configurar o evento de seleção de emoji
            emojiPicker.addEventListener('emoji-click', event => {
                // Obter o input-alvo armazenado no dataset
                const targetInput = document.getElementById('emoji-picker-container').dataset.currentInput;
                if (targetInput) {
                    const inputElement = document.getElementById(targetInput);
                    if (inputElement) {
                        // Inserir o emoji de forma segura
                        this.inserirEmoji(inputElement, event.detail.unicode);
                        
                        // Esconder o seletor
                        document.getElementById('emoji-picker-container').style.display = 'none';
                    }
                }
            });
            
            // Fechar o picker ao clicar fora
            document.addEventListener('click', (e) => {
                const emojiPicker = document.getElementById('emoji-picker-container');
                if (emojiPicker && emojiPicker.style.display === 'block') {
                    // Verificar se o clique foi fora do emoji picker e do botão
                    if (!emojiPicker.contains(e.target) && 
                        !e.target.classList.contains('emoji-button') && 
                        !e.target.closest('.emoji-button')) {
                        emojiPicker.style.display = 'none';
                    }
                }
            });
        } catch (error) {
            console.error("Erro ao inicializar emoji picker:", error);
        }
    }
    
    // Método para abrir o seletor de emoji
    abrirSeletorEmoji(event, inputElement) {
        try {
            const btn = event.currentTarget;
            const emojiPicker = document.getElementById('emoji-picker-container');
            
            if (!emojiPicker) return;
            
            // Inicializar o picker se necessário
            this.inicializarEmojiPicker();
            
            // Criar um ID único para o input se não existir
            if (!inputElement.id) {
                inputElement.id = 'emoji-input-' + new Date().getTime();
            }
            
            // Armazenar o input atual no dataset do picker
            emojiPicker.dataset.currentInput = inputElement.id;
            
            // Posicionar o seletor próximo ao botão
            const btnRect = btn.getBoundingClientRect();
            emojiPicker.style.position = 'absolute';
            emojiPicker.style.top = (btnRect.bottom + window.scrollY + 5) + 'px';
            emojiPicker.style.left = (btnRect.left + window.scrollX) + 'px';
            
            // Mostrar o seletor
            emojiPicker.style.display = 'block';
            
            // Focar no input
            inputElement.focus();
        } catch (error) {
            console.error("Erro ao abrir seletor de emoji:", error);
        }
    }
    
    // Método mais simples e seguro para inserir emoji
    inserirEmoji(inputElement, emoji) {
        try {
            // Obter o texto atual
            const textoAtual = inputElement.innerText;
            const selecao = window.getSelection();
            let posicao = textoAtual.length; // padrão: fim do texto
            
            // Tentar obter a posição do cursor
            if (selecao.rangeCount > 0) {
                const range = selecao.getRangeAt(0);
                if (range.startContainer === inputElement || inputElement.contains(range.startContainer)) {
                    // Se o cursor estiver dentro do input
                    if (range.startContainer.nodeType === Node.TEXT_NODE) {
                        // Caso seja um nó de texto
                        const parentNode = range.startContainer.parentNode;
                        const nodeIndex = Array.from(parentNode.childNodes).indexOf(range.startContainer);
                        const previousSiblings = Array.from(parentNode.childNodes).slice(0, nodeIndex);
                        
                        // Calcular o offset até este nó
                        let offset = 0;
                        previousSiblings.forEach(sibling => {
                            if (sibling.nodeType === Node.TEXT_NODE) {
                                offset += sibling.textContent.length;
                            } else if (sibling.nodeType === Node.ELEMENT_NODE) {
                                offset += sibling.innerText.length;
                            }
                        });
                        
                        posicao = offset + range.startOffset;
                    }
                }
            }
            
            // Inserir o emoji na posição do cursor
            const novoTexto = textoAtual.substring(0, posicao) + emoji + textoAtual.substring(posicao);
            inputElement.innerText = novoTexto;
            
            // Disparar evento de input para atualizar a interface
            const inputEvent = new Event('input', { bubbles: true });
            inputElement.dispatchEvent(inputEvent);
            
            // Tentar restaurar o cursor após o emoji
            this.setCursorPosition(inputElement, posicao + emoji.length);
        } catch (error) {
            // Fallback: adicionar ao final
            inputElement.innerText += emoji;
            console.error("Erro ao inserir emoji:", error);
            
            // Disparar evento de input
            const inputEvent = new Event('input', { bubbles: true });
            inputElement.dispatchEvent(inputEvent);
        }
    }
    
    // Método auxiliar para posicionar o cursor
    setCursorPosition(element, position) {
        try {
            // Criar um range
            const range = document.createRange();
            const sel = window.getSelection();
            
            // Encontrar o nó de texto e o offset
            let currentNode = element.firstChild;
            let currentPos = 0;
            
            // Função para encontrar o nó na posição
            function findNodeAtPosition(node, targetPos) {
                if (!node) return { node: element, offset: 0 };
                
                if (node.nodeType === Node.TEXT_NODE) {
                    if (currentPos + node.length >= targetPos) {
                        return { node, offset: targetPos - currentPos };
                    }
                    currentPos += node.length;
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.textContent && currentPos + node.textContent.length >= targetPos) {
                        // Descer para os filhos
                        for (let i = 0; i < node.childNodes.length; i++) {
                            const result = findNodeAtPosition(node.childNodes[i], targetPos);
                            if (result) return result;
                        }
                    }
                    currentPos += node.textContent ? node.textContent.length : 0;
                }
                
                // Tentar o próximo irmão
                if (node.nextSibling) {
                    return findNodeAtPosition(node.nextSibling, targetPos);
                }
                
                // Se chegamos aqui, posicionar no final
                return { node: element, offset: element.textContent ? element.textContent.length : 0 };
            }
            
            // Se o elemento estiver vazio, usar o próprio elemento
            if (!element.firstChild) {
                element.appendChild(document.createTextNode(''));
                currentNode = element.firstChild;
            }
            
            // Encontrar o nó e o offset
            const result = findNodeAtPosition(currentNode, position);
            
            // Posicionar o cursor
            range.setStart(result.node, result.offset);
            range.collapse(true);
            
            // Aplicar a seleção
            sel.removeAllRanges();
            sel.addRange(range);
            
            // Focar no elemento
            element.focus();
        } catch (error) {
            console.error("Erro ao posicionar cursor:", error);
            // Fallback: focar no elemento
            element.focus();
        }
    }
    
    cancelar(event) {
        try {
            const btn = event.currentTarget;
            const pai = btn.closest(".caixaComentario");
            const acao = pai.getElementsByClassName("acao")[0];
            const input = pai.getElementsByClassName("inputComentario")[0];
            
            acao.classList.add("d-none");
            input.innerText = "";
        } catch (error) {
            console.error("Erro ao cancelar comentário:", error);
        }
    }
    
    // Método para atualizar o contador global de comentários
    atualizarContadorGlobal() {
        try {
            const contadorEl = document.querySelector(".contador");
            if (!contadorEl) return;
            
            // Contar todos os comentários principais atualmente na página
            const comentariosPrincipais = document.querySelectorAll("#provisorio2 > .comentario").length;
            
            if (comentariosPrincipais === 0) {
                contadorEl.innerText = "Nenhum Comentário";
            } else if (comentariosPrincipais === 1) {
                contadorEl.innerText = "Um Comentário";
            } else {
                contadorEl.innerText = `${comentariosPrincipais} Comentários`;
            }
        } catch (error) {
            console.error("Erro ao atualizar contador global:", error);
        }
    }
    
    comentar(event) {
        try {
            const btn = event.currentTarget;
            const pai = btn.closest(".caixaComentario");
            const input = pai.getElementsByClassName("inputComentario")[0];
            const paiId = parseInt(input.dataset.id || 0);
            const acao = pai.getElementsByClassName("acao")[0];
            
            if (!input.innerText.trim()) {
                return; // Evitar envio de comentários vazios
            }
            
            const texto = input.innerText;
            acao.classList.add("d-none");
            input.innerText = "";
            
            // Mostrar indicador de carregamento
            const loadingIndicator = document.createElement("DIV");
            loadingIndicator.classList.add("text-center", "my-2", "comment-loading");
            loadingIndicator.innerHTML = `
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Enviando comentário...</span>
                </div>
                <span class="ms-2 text-muted">Enviando...</span>
            `;
            
            // Determinar onde adicionar o indicador de carregamento
            // Se for um comentário novo (paiId é 0) ou se não conseguirmos encontrar o pai,
            // adicionamos no topo da lista
            if (paiId === 0) {
                document.getElementById("provisorio2").insertBefore(loadingIndicator, document.getElementById("provisorio2").firstChild);
            } else {
                // Encontrar o comentário pai (comentário principal)
                const caixa = document.querySelector(`.comentario[data-id="${paiId}"]`);
                if (caixa && caixa.getElementsByClassName("caixaResposta").length > 0) {
                    caixa.getElementsByClassName("caixaResposta")[0].insertBefore(
                        loadingIndicator, 
                        caixa.getElementsByClassName("caixaResposta")[0].firstChild
                    );
                } else {
                    document.getElementById("provisorio2").insertBefore(loadingIndicator, document.getElementById("provisorio2").firstChild);
                }
            }
            
            // Enviar comentário para o servidor
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "modulo": this.modulo,
                "url": this.url,
                "acao": "novo",
                "texto": texto,
                "pai": paiId
            });
            
            request.send().then((r) => {
                // Remover o indicador de carregamento
                document.querySelectorAll(".comment-loading").forEach(el => el.remove());
                
                var obj = {
                    "i": r.id,
                    "a": this.me,
                    "t": texto,
                    "d": 0,
                    "p": paiId,
                    "f": 0
                };
                
                var div = this.comentario(obj);
                div.classList.add("animate__fadeIn", "animate__animated");
                
                if (paiId === 0) {
                    document.getElementById("provisorio2").insertBefore(div, document.getElementById("provisorio2").firstChild);
                    
                    // Remover mensagem de "sem comentários" se existir
                    const semComentarios = document.getElementById("provisorio2").getElementsByClassName("semComentarios");
                    if (semComentarios.length > 0) {
                        semComentarios[0].remove();
                    }
                    
                    // Atualizar contador de comentários
                    this.atualizarContadorGlobal();
                } else {
                    // Adicionar à lista de respostas do comentário principal
                    const caixa = document.querySelector(`.comentario[data-id="${paiId}"]`);
                    if (caixa && caixa.getElementsByClassName("caixaResposta").length > 0) {
                        caixa.getElementsByClassName("caixaResposta")[0].insertBefore(
                            div, 
                            caixa.getElementsByClassName("caixaResposta")[0].firstChild
                        );
                        
                        // Atualizar contador de respostas
                        const respostasButton = caixa.querySelector(".respostas");
                        if (respostasButton) {
                            const span = respostasButton.querySelector("span");
                            if (span) {
                                const currentText = span.innerText;
                                if (!currentText || currentText === "") {
                                    span.innerText = "Uma Resposta";
                                } else if (currentText === "Uma Resposta") {
                                    span.innerText = "2 Respostas";
                                } else if (currentText.includes("Respostas")) {
                                    const count = parseInt(currentText.match(/\d+/) || 0) + 1;
                                    span.innerText = `${count} Respostas`;
                                }
                            }
                            respostasButton.classList.remove("d-none");
                        }
                        
                        // Expandir a seção de respostas se estiver fechada
                        const collapseEl = caixa.querySelector(".caixaResposta");
                        if (collapseEl && !collapseEl.classList.contains("show")) {
                            const bsCollapse = new bootstrap.Collapse(collapseEl);
                            bsCollapse.show();
                        }
                    } else {
                        // Fallback: adicionar ao topo da lista principal se não encontrarmos o pai
                        document.getElementById("provisorio2").insertBefore(div, document.getElementById("provisorio2").firstChild);
                        this.atualizarContadorGlobal();
                    }
                }
                
                // Fechar o collapse de resposta
                const foco = btn.closest('.collapse');
                if (foco) {
                    const bsCollapse = bootstrap.Collapse.getInstance(foco);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            }).catch((r) => {
                // Remover o indicador de carregamento
                document.querySelectorAll(".comment-loading").forEach(el => el.remove());
                
                // Adicionar mensagem de erro
                console.error("Erro ao enviar comentário:", r);
                
                // Restaurar o texto do usuário para que ele possa tentar novamente
                input.innerText = texto;
                acao.classList.remove("d-none");
                
                // Exibir alerta de erro
                const errorMessage = document.createElement("DIV");
                errorMessage.classList.add("alert", "alert-danger", "mt-2", "py-2");
                errorMessage.innerText = "Não foi possível enviar o comentário. Tente novamente.";
                pai.appendChild(errorMessage);
                
                // Remover alerta após 3 segundos
                setTimeout(() => {
                    if (errorMessage.parentNode) {
                        errorMessage.remove();
                    }
                }, 3000);
            });
        } catch (error) {
            console.error("Erro na função comentar:", error);
        }
    }
    
    comentando(event) {
        try {
            const input = event.currentTarget;
            const pai = input.closest(".caixaComentario");
            const acao = pai.getElementsByClassName("acao")[0];
            
            if (input.innerText.trim()) {
                acao.classList.remove("d-none");
            } else {
                acao.classList.add("d-none");
            }
        } catch (error) {
            console.error("Erro na função comentando:", error);
        }
    }
    
    comentario(item = false) {
        try {
            var id = geraId();
            var div = document.createElement("DIV");
            div.classList.add("comentario", "mb-3", "position-relative");
            
            if (item) {
                div.dataset.id = item.i;
                if (item.p && item.p !== 0) {
                    div.dataset.pai = item.p;
                }
            }
                
            let filhoClasse = "d-none";   
            let filhos = "";
            let data = "";
            let texto = "";
            let acoes = "";
            var foto = `${dominioscript}/conteudo/modulos/usuarios/midias/perfil.jpg`;
            
            if (item) {
                data = this.calcularTempo(item.d);
                texto = this.formatarComentario(item.t);
                
                var autor = this.autores[item.a];
                
                var nome = `<a href="${dominio}/usuarios/${autor.u}" class="text-decoration-none fw-bold text-dark">${autor.d}</a>`; 
                
                var img = trataImagem(autor.f, "mini");
                if (img) {
                    foto = img;
                }
                
                if (item.f > 0) {
                    filhos = item.f == 1 ? "Uma Resposta" : `${item.f} Respostas`;
                    filhoClasse = "";
                }
                
                // Verificar se o comentário é do usuário atual
                if (item.a === this.me) {
                    acoes = `
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><button class="dropdown-item text-primary editar-comentario" type="button" data-id="${item.i}"><i class="bi bi-pencil me-2"></i>Editar</button></li>
                            <li><button class="dropdown-item text-danger excluir-comentario" type="button" data-id="${item.i}"><i class="bi bi-trash me-2"></i>Excluir</button></li>
                        </ul>
                    </div>`;
                } else {
                    acoes = `
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><button class="dropdown-item reportar-comentario" type="button" data-id="${item.i}"><i class="bi bi-flag me-2"></i>Reportar</button></li>
                        </ul>
                    </div>`;
                }
            } else {
                data = '<div class="he-20 bg-carregando wi-50 rounded"></div>';
                texto = '<div class="he-20 bg-carregando w-100 rounded"></div>';
                nome = '<div class="he-20 bg-carregando wi-100 rounded"></div>';
                acoes = '';
            }
            
            div.innerHTML = `
                <div class="d-flex gap-3">
                <div>
                    <div class="wi-35 he-35 wi-xl-50 he-xl-50 rounded-circle shadow-sm" 
                        style="background-image: url(${foto}); background-size: cover; background-position: center;">
                    </div>
                </div>
                <div class="flex-fill d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="fw-bold fs-14">${nome}</div>
                            <div class="fs-12 text-muted">${data}</div>
                        </div>
                        <div>
                            ${acoes}
                        </div>
                    </div>
                    <div class="fs-14 text-break comment-text">
                        ${texto}
                    </div>
                    <div class="d-flex justify-content-start gap-3 align-items-center mt-1">
                        <button class="btn btn-sm btn-light rounded-pill px-3 curtir-comentario" data-id="${item ? item.i : ''}">
                            <i class="bi bi-hand-thumbs-up me-1"></i><span class="like-count">0</span>
                        </button>
                        <button class="btn btn-sm btn-light rounded-pill px-3 descurtir-comentario" data-id="${item ? item.i : ''}">
                            <i class="bi bi-hand-thumbs-down me-1"></i><span class="dislike-count">0</span>
                        </button>
                        <button class="btn btn-sm btn-light rounded-pill px-3 responder" 
                                data-bs-toggle="collapse" data-bs-target="#responder-${id}" 
                                aria-expanded="false" aria-controls="responder-${id}">
                            <i class="bi bi-reply me-1"></i>Responder
                        </button>
                    </div>
                    <div>
                        <div class="collapse mt-3" id="responder-${id}">
                        
                        </div>
                    </div>
                    
                    <div>
                        <button class="btn text-primary d-flex gap-2 align-items-center fs-14 fw-bold ${filhoClasse} respostas my-2" 
                                data-bs-toggle="collapse" data-bs-target="#calapse-${id}" 
                                aria-expanded="false" aria-controls="calapse-${id}">
                            <i class="bi bi-chevron-down"></i> <span>${filhos}</span>
                        </button>
                        <div class="collapse caixaResposta ps-3 border-start border-light" id="calapse-${id}">
                        
                        </div>
                    </div>
                </div>
                </div>
            `;
            
            if (item) {
                evento(div.getElementsByClassName("responder")[0], "click", this.responder.bind(this));
                
                if (div.getElementsByClassName("respostas").length > 0) {
                    evento(div.getElementsByClassName("respostas")[0], "click", this.respostas.bind(this));
                }
                
                if (div.getElementsByTagName("a").length > 0) {
                    evento(div.getElementsByTagName("a")[0], "click", preventLink);
                }
                
                // Configurar eventos para os botões de ação
                const editarBtn = div.querySelector(".editar-comentario");
                if (editarBtn) {
                    evento(editarBtn, "click", this.editarComentario.bind(this));
                }
                
                const excluirBtn = div.querySelector(".excluir-comentario");
                if (excluirBtn) {
                    evento(excluirBtn, "click", this.excluirComentario.bind(this));
                }
                
                const reportarBtn = div.querySelector(".reportar-comentario");
                if (reportarBtn) {
                    evento(reportarBtn, "click", this.reportarComentario.bind(this));
                }
                
                // Adicionar eventos para botões de curtir/descurtir
                const curtirBtn = div.querySelector(".curtir-comentario");
                if (curtirBtn) {
                    evento(curtirBtn, "click", this.curtirComentario.bind(this));
                }
                
                const descurtirBtn = div.querySelector(".descurtir-comentario");
                if (descurtirBtn) {
                    evento(descurtirBtn, "click", this.descurtirComentario.bind(this));
                }
            }
            
            return div;
            
        } catch (error) {
            console.error("Erro ao criar comentário:", error);
            return document.createElement("DIV");
        }
    }
    
    // Métodos para ações nos comentários
    
    editarComentario(event) {
        try {
            const btn = event.currentTarget;
            const id = btn.dataset.id;
            const comentarioEl = document.querySelector(`.comentario[data-id="${id}"]`);
            
            if (!comentarioEl) return;
            
            const textoEl = comentarioEl.querySelector(".comment-text");
            const textoOriginal = textoEl.innerHTML;
            const textoSemFormatacao = textoEl.innerText;
            
            // Substituir por um campo editável
            textoEl.innerHTML = `
                <div class="mb-2">
                    <div contenteditable="true" class="form-control edit-comentario shadow-sm border-light">${textoSemFormatacao}</div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-light btn-sm cancelar-edicao">Cancelar</button>
                    <button class="btn btn-primary btn-sm salvar-edicao">Salvar alterações</button>
                </div>
            `;
            
            // Configurar eventos
            const cancelarBtn = textoEl.querySelector(".cancelar-edicao");
            evento(cancelarBtn, "click", () => {
                textoEl.innerHTML = textoOriginal;
            });
            
            const salvarBtn = textoEl.querySelector(".salvar-edicao");
            evento(salvarBtn, "click", () => {
                const novoTexto = textoEl.querySelector(".edit-comentario").innerText;
                
                if (!novoTexto.trim()) {
                    return;
                }
                
                // Mostrar loader
                textoEl.innerHTML = `
                    <div class="text-center py-2">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Salvando alterações...</span>
                        </div>
                        <span class="ms-2 text-muted">Salvando alterações...</span>
                    </div>
                `;
                
                // Enviar para o servidor
                let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
                request.addData({
                    "modulo": this.modulo,
                    "url": this.url,
                    "acao": "editar",
                    "texto": novoTexto,
                    "id": id
                });
                
                request.send().then((r) => {
                    // Atualizar o texto com formatação
                    textoEl.innerHTML = this.formatarComentario(novoTexto);
                }).catch((r) => {
                    console.error("Erro ao editar comentário:", r);
                    textoEl.innerHTML = textoOriginal;
                    
                    // Adicionar mensagem de erro
                    const errorMessage = document.createElement("DIV");
                    errorMessage.classList.add("alert", "alert-danger", "mt-2", "py-2");
                    errorMessage.innerText = "Não foi possível editar o comentário. Tente novamente.";
                    comentarioEl.appendChild(errorMessage);
                    
                    // Remover após 3 segundos
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.remove();
                        }
                    }, 3000);
                });
            });
            
        } catch (error) {
            console.error("Erro ao editar comentário:", error);
        }
    }
    
    excluirComentario(event) {
        try {
            const btn = event.currentTarget;
            const id = btn.dataset.id;
            const comentarioEl = document.querySelector(`.comentario[data-id="${id}"]`);
            
            if (!comentarioEl) return;
            
            // Confirmar exclusão
            if (!confirm("Tem certeza que deseja excluir este comentário?")) {
                return;
            }
            
            // Adicionar estado de carregamento
            comentarioEl.classList.add("opacity-50");
            
            // Verificar se é um comentário principal ou secundário
            const isPrincipal = !comentarioEl.dataset.pai;
            const containerPai = comentarioEl.closest(".caixaResposta");
            const comentarioPai = isPrincipal ? null : document.querySelector(`.comentario[data-id="${comentarioEl.dataset.pai}"]`);
            
            // Enviar para o servidor
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "modulo": this.modulo,
                "url": this.url,
                "acao": "excluir",
                "id": id
            });
            
            request.send().then((r) => {
                // Remover comentário com animação
                comentarioEl.classList.add("animate__animated", "animate__fadeOut");
                
                setTimeout(() => {
                    comentarioEl.remove();
                    
                    // Se for um comentário principal, atualizar o contador global
                    if (isPrincipal) {
                        this.atualizarContadorGlobal();
                        
                        // Adicionar mensagem de 'sem comentários' se foi o último
                        if (document.querySelectorAll("#provisorio2 > .comentario").length === 0) {
                            let semComentarios = document.createElement("DIV");
                            semComentarios.classList.add("text-center", "semComentarios", "py-4");
                            semComentarios.innerHTML = `
                                <div><i class="bi bi-chat-square-text fs-3 text-muted mb-3"></i></div>
                                <div class="text-muted mb-2">Ainda não há nenhum comentário</div>
                                <h2 class="text-center fs-4">Seja o Primeiro</h2>
                            `;
                            document.getElementById("provisorio2").appendChild(semComentarios);
                        }
                    } 
                    // Se for um comentário secundário, atualizar contador do pai
                    else if (comentarioPai) {
                        const respostasBtn = comentarioPai.querySelector(".respostas");
                        if (respostasBtn) {
                            const span = respostasBtn.querySelector("span");
                            if (span) {
                                const respostasRestantes = containerPai.querySelectorAll(".comentario").length;
                                
                                if (respostasRestantes === 0) {
                                    span.innerText = "";
                                    respostasBtn.classList.add("d-none");
                                } else if (respostasRestantes === 1) {
                                    span.innerText = "Uma Resposta";
                                } else {
                                    span.innerText = `${respostasRestantes} Respostas`;
                                }
                            }
                        }
                    }
                }, 500);
            }).catch((r) => {
                console.error("Erro ao excluir comentário:", r);
                comentarioEl.classList.remove("opacity-50");
                
                // Mostrar mensagem de erro
                const errorMessage = document.createElement("DIV");
                errorMessage.classList.add("alert", "alert-danger", "mt-2", "py-2");
                errorMessage.innerText = "Não foi possível excluir o comentário. Tente novamente.";
                comentarioEl.appendChild(errorMessage);
                
                // Remover após 3 segundos
                setTimeout(() => {
                    if (errorMessage.parentNode) {
                        errorMessage.remove();
                    }
                }, 3000);
            });
            
        } catch (error) {
            console.error("Erro ao excluir comentário:", error);
        }
    }
    
    reportarComentario(event) {
        try {
            const btn = event.currentTarget;
            const id = btn.dataset.id;
            
            // Criar modal de denúncia
            const modalHTML = `
            <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reportModalLabel">Reportar comentário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="report-reason" class="form-label">Motivo da denúncia</label>
                                <select class="form-select" id="report-reason">
                                    <option value="spam">Spam</option>
                                    <option value="offensive">Conteúdo ofensivo</option>
                                    <option value="harassment">Assédio</option>
                                    <option value="false-info">Informação falsa</option>
                                    <option value="other">Outro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="report-details" class="form-label">Detalhes (opcional)</label>
                                <textarea class="form-control" id="report-details" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="send-report">Enviar denúncia</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
            
            // Adicionar modal ao corpo do documento
            const modalContainer = document.createElement("DIV");
            modalContainer.innerHTML = modalHTML;
            document.body.appendChild(modalContainer);
            
            // Inicializar e mostrar o modal
            const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
            reportModal.show();
            
            // Configurar evento para enviar denúncia
            const sendBtn = document.getElementById("send-report");
            evento(sendBtn, "click", () => {
                const reason = document.getElementById("report-reason").value;
                const details = document.getElementById("report-details").value;
                
                // Enviar denúncia para o servidor
                let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
                request.addData({
                    "modulo": this.modulo,
                    "url": this.url,
                    "acao": "reportar",
                    "id": id,
                    "motivo": reason,
                    "detalhes": details
                });
                
                // Mostrar loading no botão
                sendBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Enviando...
                `;
                sendBtn.disabled = true;
                
                request.send().then((r) => {
                    // Fechar modal
                    reportModal.hide();
                    
                    // Remover modal do DOM
                    setTimeout(() => {
                        modalContainer.remove();
                    }, 500);
                    
                    // Mostrar mensagem de sucesso
                    alert("Denúncia enviada com sucesso. Agradecemos pelo seu feedback.");
                }).catch((r) => {
                    console.error("Erro ao reportar comentário:", r);
                    
                    // Restaurar botão
                    sendBtn.innerHTML = "Enviar denúncia";
                    sendBtn.disabled = false;
                    
                    // Mostrar mensagem de erro
                    alert("Não foi possível enviar a denúncia. Tente novamente.");
                });
            });
            
            // Limpar modal quando for fechado
            document.getElementById('reportModal').addEventListener('hidden.bs.modal', function () {
                modalContainer.remove();
            });
            
        } catch (error) {
            console.error("Erro ao reportar comentário:", error);
        }
    }
    
    curtirComentario(event) {
        try {
            const btn = event.currentTarget;
            const id = btn.dataset.id;
            
            // Verificar se já foi curtido
            if (btn.classList.contains("active")) {
                return;
            }
            
            // Desabilitar botão de descurtir
            const descurtirBtn = btn.closest(".comentario").querySelector(".descurtir-comentario");
            if (descurtirBtn && descurtirBtn.classList.contains("active")) {
                descurtirBtn.classList.remove("active");
                descurtirBtn.style.backgroundColor = "";
                descurtirBtn.style.borderColor = "";
                
                const descurtirCount = descurtirBtn.querySelector(".dislike-count");
                if (descurtirCount) {
                    const count = parseInt(descurtirCount.textContent) - 1;
                    descurtirCount.textContent = count < 0 ? 0 : count;
                }
            }
            
            // Atualizar UI
            btn.classList.add("active");
            btn.style.backgroundColor = "#e7f1ff";
            btn.style.borderColor = "#b8daff";
            
            const likeCount = btn.querySelector(".like-count");
            if (likeCount) {
                const count = parseInt(likeCount.textContent) + 1;
                likeCount.textContent = count;
            }
            
            // Enviar para o servidor
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "modulo": this.modulo,
                "url": this.url,
                "acao": "curtir",
                "id": id
            });
            
            request.send().catch((r) => {
                console.error("Erro ao curtir comentário:", r);
                
                // Reverter UI em caso de erro
                btn.classList.remove("active");
                btn.style.backgroundColor = "";
                btn.style.borderColor = "";
                
                if (likeCount) {
                    const count = parseInt(likeCount.textContent) - 1;
                    likeCount.textContent = count < 0 ? 0 : count;
                }
            });
            
        } catch (error) {
            console.error("Erro ao curtir comentário:", error);
        }
    }
    
    descurtirComentario(event) {
        try {
            const btn = event.currentTarget;
            const id = btn.dataset.id;
            
            // Verificar se já foi descurtido
            if (btn.classList.contains("active")) {
                return;
            }
            
            // Desabilitar botão de curtir
            const curtirBtn = btn.closest(".comentario").querySelector(".curtir-comentario");
            if (curtirBtn && curtirBtn.classList.contains("active")) {
                curtirBtn.classList.remove("active");
                curtirBtn.style.backgroundColor = "";
                curtirBtn.style.borderColor = "";
                
                const curtirCount = curtirBtn.querySelector(".like-count");
                if (curtirCount) {
                    const count = parseInt(curtirCount.textContent) - 1;
                    curtirCount.textContent = count < 0 ? 0 : count;
                }
            }
            
            // Atualizar UI
            btn.classList.add("active");
            btn.style.backgroundColor = "#f8d7da";
            btn.style.borderColor = "#f5c6cb";
            
            const dislikeCount = btn.querySelector(".dislike-count");
            if (dislikeCount) {
                const count = parseInt(dislikeCount.textContent) + 1;
                dislikeCount.textContent = count;
            }
            
            // Enviar para o servidor
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "modulo": this.modulo,
                "url": this.url,
                "acao": "descurtir",
                "id": id
            });
            
            request.send().catch((r) => {
                console.error("Erro ao descurtir comentário:", r);
                
                // Reverter UI em caso de erro
                btn.classList.remove("active");
                btn.style.backgroundColor = "";
                btn.style.borderColor = "";
                
                if (dislikeCount) {
                    const count = parseInt(dislikeCount.textContent) - 1;
                    dislikeCount.textContent = count < 0 ? 0 : count;
                }
            });
            
        } catch (error) {
            console.error("Erro ao descurtir comentário:", error);
        }
    }
    
    // Novo método para formatar comentários (detecção de links, menções, etc.)
    formatarComentario(texto) {
        if (!texto) return '';
        
        // Converter URLs em links clicáveis
        texto = texto.replace(
            /(https?:\/\/[^\s]+)/g, 
            '<a href="$1" target="_blank" class="text-primary">$1</a>'
        );
        
        // Destacar menções (@usuario)
        texto = texto.replace(
            /@(\w+)/g, 
            '<span class="text-primary fw-bold">@$1</span>'
        );
        
        return texto;
    }
    
    respostas(event) {
        try {
            const btn = event.currentTarget;
            const foco = btn.getAttribute('data-bs-target');
            const targetContainer = document.querySelector(foco);
            
            if (!btn.dataset.render) {
                // Adicionar loader
                this.adicionarLoader(targetContainer);
                
                btn.dataset.render = true;
                
                const id = parseInt(btn.closest(".comentario").dataset.id);
                let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
                request.addData({"acao": "respostas", "pai": id});
                
                request.send().then((r) => {
                    // Remover loader
                    this.removerLoader(targetContainer);
                    
                    const lista = r.lista;
                    
                    // Adicionar autores ao objeto this.autores
                    for (let c in r.autores) {
                        this.autores[c] = r.autores[c];
                    }
                    
                    if (lista.length === 0) {
                        // Mostrar mensagem se não houver respostas
                        const semRespostas = document.createElement("DIV");
                        semRespostas.classList.add("text-center", "py-3", "text-muted");
                        semRespostas.innerHTML = "Não há respostas para este comentário";
                        targetContainer.appendChild(semRespostas);
                    } else {
                        // Adicionar as respostas
                        const fragmento = document.createDocumentFragment();
                        
                        lista.forEach(item => {
                            const div = this.comentario(item);
                            div.dataset.pai = id;
                            fragmento.appendChild(div);
                        });
                        
                        if (r.totalNivel > 20) {
                            // Botão carregar mais
                            const button = document.createElement("BUTTON");
                            button.classList.add("btn", "btn-outline-primary", "btn-sm", "d-block", "mx-auto", "my-3");
                            button.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Carregar mais respostas`;
                            button.dataset.pagination = 2;
                            button.dataset.pai = id;
                            evento(button, "click", this.loadMore.bind(this));
                            fragmento.appendChild(button);
                        }
                        
                        targetContainer.appendChild(fragmento);
                    }
                }).catch((r) => {
                    console.error("Erro ao carregar respostas:", r);
                    this.removerLoader(targetContainer);
                    
                    // Mostrar mensagem de erro
                    const erroDiv = document.createElement("DIV");
                    erroDiv.classList.add("alert", "alert-danger", "my-2", "py-2");
                    erroDiv.innerText = "Não foi possível carregar as respostas. Tente novamente.";
                    targetContainer.appendChild(erroDiv);
                    
                    // Remover flag para permitir tentar novamente
                    delete btn.dataset.render;
                });
            }
        } catch (error) {
            console.error("Erro ao carregar respostas:", error);
        }
    }
    
    responder(event) {
        try {
            const btn = event.currentTarget;
            const comentario = btn.closest(".comentario");
            
            // Obter o ID do comentário (próprio ID ou ID do pai se for uma resposta)
            // Isso garante que a resposta será publicada no nível principal
            let id = parseInt(comentario.dataset.id);
            
            // Se o comentário for uma resposta, usamos o ID do pai
            if (comentario.dataset.pai) {
                id = parseInt(comentario.dataset.pai);
            }
            
            // Configurar a menção ao usuário que está sendo respondido
            // Obter o nome do usuário para a menção
            const nomeUsuario = comentario.querySelector(".fw-bold.fs-14 a").innerText;
            const arroba = nomeUsuario ? true : false;
            
            const foco = btn.getAttribute('data-bs-target');
            const div = document.querySelector(foco);
            
            if (div.getElementsByClassName("caixaComentario").length === 0) {
                const caixaComentario = this.caixaComentario(id, arroba);
                
                // Se for uma resposta, precisamos adicionar o nome do usuário
                if (arroba && nomeUsuario) {
                    const inputComentario = caixaComentario.querySelector(".inputComentario");
                    if (inputComentario) {
                        inputComentario.innerText = `@${nomeUsuario} `;
                        
                        // Focar no final do texto
                        setTimeout(() => {
                            if (inputComentario.childNodes.length > 0) {
                                const range = document.createRange();
                                const sel = window.getSelection();
                                range.setStart(inputComentario.childNodes[0], inputComentario.innerText.length);
                                range.collapse(true);
                                sel.removeAllRanges();
                                sel.addRange(range);
                                
                                // Disparar evento de input para mostrar botões
                                const inputEvent = new Event('input', {bubbles: true});
                                inputComentario.dispatchEvent(inputEvent);
                            }
                        }, 10);
                    }
                }
                
                div.appendChild(caixaComentario);
            }
        } catch (error) {
            console.error("Erro ao preparar resposta:", error);
        }
    }
    
    // Métodos de utilidade
    adicionarLoader(container) {
        const loader = document.createElement("DIV");
        loader.classList.add("text-center", "py-3", "comments-loader");
        loader.innerHTML = `
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <span class="ms-2 text-muted">Carregando comentários...</span>
        `;
        container.appendChild(loader);
    }
    
    removerLoader(container) {
        const loaders = container.querySelectorAll(".comments-loader");
        loaders.forEach(loader => loader.remove());
    }
    
    massaComent() {
        try {
            if (document.getElementById("boxFlutuadorComentario")) {
                return;
            }
            
            var div = document.createElement("DIV");
            div.id = "boxFlutuadorComentario";
            div.classList.add("d-none");
            div.innerHTML = `
            <div class="controller"></div>
                <div class="px-2 pt-5 pb-3 pre">
                </div>
            </div>
            `;
            
            this.estrutura = this.estruturaFixa(true);
            
            div.getElementsByClassName("pre")[0].appendChild(this.estrutura);
            document.getElementById("conteudo").appendChild(div);
            
            var caixa = this.caixaComentario();
            document.getElementById("provisoario").appendChild(caixa);
            
            this.fluatuante = new nownCanvas("boxFlutuadorComentario", {
                dirDesktop: "bottom",
                dirMobile: "bottom",
                backdropBlur: true,
                persist: true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
        } catch (error) {
            console.error("Erro ao criar massa de comentários:", error);
        }
    }
    
    init() {
        try {
            var i = 0;
            while (i < this.trigers.length) {
                var comentario = this.trigers[i];
                if (!comentario.dataset.init) {
                    comentario.dataset.init = true;
                    this.render(comentario);
                }
                i++;
            }
            
            // Inicializar o seletor de emoji quando o DOM estiver pronto
            if (document.readyState === "complete" || document.readyState === "interactive") {
                this.inicializarEmojiPicker();
            } else {
                document.addEventListener("DOMContentLoaded", () => {
                    this.inicializarEmojiPicker();
                });
            }
        } catch (error) {
            console.error("Erro na inicialização:", error);
        }
    }
    
    calcularTempo(data) {
        if (!data) {
            return "<i class='bi bi-clock-history text-muted me-1'></i> Agora mesmo";
        }
        
        try {
            const agora = new Date();
            const dataComparar = new Date(data);
            
            // Verificar se a data é válida
            if (isNaN(dataComparar.getTime())) {
                return "<i class='bi bi-question-circle text-muted me-1'></i> Data inválida";
            }

            const diffMilissegundos = agora - dataComparar;

            // Cálculos de tempo
            const minutos = Math.floor(diffMilissegundos / (1000 * 60));
            const horas = Math.floor(diffMilissegundos / (1000 * 60 * 60));
            const dias = Math.floor(diffMilissegundos / (1000 * 60 * 60 * 24));
            const meses = Math.floor(dias / 30);
            const anos = Math.floor(dias / 365);

            // Formatação mais elegante com ícone
            if (minutos < 1) {
                return `<i class="bi bi-clock-history text-muted me-1"></i> Agora mesmo`;
            } else if (minutos < 60) {
                return `<i class="bi bi-clock text-muted me-1"></i> ${minutos} min`;
            } else if (horas < 24) {
                return `<i class="bi bi-clock text-muted me-1"></i> ${horas}h`;
            } else if (dias < 30) {
                return `<i class="bi bi-calendar3 text-muted me-1"></i> ${dias}d`;
            } else if (meses < 12) {
                return `<i class="bi bi-calendar3 text-muted me-1"></i> ${meses} ${meses === 1 ? 'mês' : 'meses'}`;
            } else {
                return `<i class="bi bi-calendar3 text-muted me-1"></i> ${anos} ${anos === 1 ? 'ano' : 'anos'}`;
            }
        } catch (error) {
            console.error("Erro ao calcular tempo:", error);
            return "<i class='bi bi-question-circle text-muted me-1'></i> Data inválida";
        }
    }
    
    loadMore(event) {
        try {
            const btn = event.currentTarget;
            btn.setAttribute("disabled", "");
            
            const paginacao = btn.dataset.pagination;
            btn.innerHTML = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            `;
            
            const pai = btn.dataset.pai || 0;
            const identificador = btn.closest(".comentador").dataset.identificador;
            
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "id": identificador,
                "modulo": this.modulo,
                "paginacao": paginacao,
                "ordenagem": this.inicial,
                "acao": "more",
                "pai": pai
            });
            
            request.send().then((r) => {
                const lista = r.lista;
                
                // Atualizar autores
                for (let c in r.autores) {
                    this.autores[c] = r.autores[c];
                }
                
                const fragmento = document.createDocumentFragment();
                
                if (lista.length === 0) {
                    // Mensagem quando não há mais comentários
                    const noMoreComments = document.createElement("DIV");
                    noMoreComments.classList.add("text-center", "text-muted", "my-3");
                    noMoreComments.innerText = "Não há mais comentários para carregar";
                    fragmento.appendChild(noMoreComments);
                } else {
                    // Adicionar comentários
                    lista.forEach(item => {
                        const comentarioElement = this.comentario(item);
                        if (pai) {
                            comentarioElement.dataset.pai = pai;
                        }
                        fragmento.appendChild(comentarioElement);
                    });
                    
                    if (lista.length === 20) {
                        // Botão "carregar mais" para próxima página
                        const button = document.createElement("BUTTON");
                        button.dataset.pagination = parseInt(paginacao) + 1;
                        
                        if (!pai) {
                            button.classList.add("btn", "btn-n-primaria", "d-block", "mx-auto", "my-3");
                            button.innerText = "Carregar Mais";
                        } else {
                            button.classList.add("btn", "btn-outline-primary", "btn-sm", "d-block", "mx-auto", "my-3");
                            button.dataset.pai = pai;
                            button.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Carregar mais respostas`;
                        }
                        
                        evento(button, "click", this.loadMore.bind(this));
                        fragmento.appendChild(button);
                    }
                }
                
                // Remover botão antigo
                btn.remove();
                
                // Adicionar novos elementos
                if (!pai) {
                    document.getElementById("provisorio2").appendChild(fragmento);
                } else {
                    const caixa = document.querySelector(`.comentario[data-id="${pai}"]`);
                    if (caixa) {
                        caixa.getElementsByClassName("caixaResposta")[0].appendChild(fragmento);
                    }
                }
            }).catch(error => {
                console.error("Erro ao carregar mais comentários:", error);
                
                // Restaurar botão
                if (!pai) {
                    btn.innerHTML = "Carregar Mais";
                } else {
                    btn.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Carregar mais respostas`;
                }
                
                btn.removeAttribute("disabled");
                
                // Adicionar mensagem de erro
                const errorMessage = document.createElement("DIV");
                errorMessage.classList.add("alert", "alert-danger", "my-2", "py-2");
                errorMessage.innerText = "Não foi possível carregar mais comentários. Tente novamente.";
                
                if (!pai) {
                    document.getElementById("provisorio2").appendChild(errorMessage);
                } else {
                    const caixa = document.querySelector(`.comentario[data-id="${pai}"]`);
                    if (caixa) {
                        caixa.getElementsByClassName("caixaResposta")[0].appendChild(errorMessage);
                    }
                }
                
                // Remover mensagem após 3 segundos
                setTimeout(() => {
                    if (errorMessage.parentNode) {
                        errorMessage.remove();
                    }
                }, 3000);
            });
        } catch (error) {
            console.error("Erro ao carregar mais comentários:", error);
        }
    }
    
    show(event) {
        try {
            const btn = event.currentTarget;
            
            this.modulo = btn.dataset.modulo;
            this.url = btn.dataset.url;
            
            this.lista.innerHTML = "";
            
            // Adicionar esqueletos de carregamento
            const fragmento = document.createDocumentFragment();
            for (let i = 0; i < 5; i++) {
                const div = this.comentario(false);
                div.classList.add("comentario-carregando");
                fragmento.appendChild(div);
            }
            this.lista.appendChild(fragmento);
            
            this.loadMessages(this.modulo, this.url, this.estrutura);
            
            this.fluatuante.show();
        } catch (error) {
            console.error("Erro ao mostrar comentários:", error);
        }
    }
    
    render(item) {
        try {
            if (item.dataset.multiple && item.dataset.multiple === "true") {
                evento(item, "click", this.show.bind(this));
                this.massaComent();
            } else {
                this.modulo = item.dataset.modulo;
                this.url = item.dataset.url || window.location.href;

                const html = this.estruturaFixa();
                item.appendChild(html);
                
                const div = this.caixaComentario();
                document.getElementById("provisoario").appendChild(div);
                
                this.loadMessages(this.modulo, this.url, html);
            }
        } catch (error) {
            console.error("Erro ao renderizar comentários:", error);
        }
    }
    
    estruturaFixa(overflow = false) {
        try {
            let html = document.createElement("DIV");
            html.classList.add("card", "border-0", "comentador", "shadow-sm");
            html.innerHTML = `
            <div class="card-header bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fs-5 fw-bold contador">
                        <div class="wi-100 he-20 bg-carregando rounded"></div>
                    </div>
                    <div>
                        <div class="d-flex gap-2 align-items-center">
                            <span><i class="bi bi-filter"></i></span>
                            <select class="border-0 filtra form-select form-select-sm">
                                <option value="populares">Populares</option>
                                <option value="recentes">Recentes</option>
                                <option value="antigos">Antigos</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header bg-transparent border-0" id="provisoario">
            </div>
            <div class="card-body d-flex flex-column gap-2 listaComentarios px-3" id="provisorio2">
            </div>
            `;
            
            if (overflow) {
                html.getElementsByClassName("listaComentarios")[0].classList.add("overflow-y-auto");
                html.classList.add("h-100");
            }
            
            this.html = html;
            this.lista = html.getElementsByClassName("listaComentarios")[0];
            evento(html.getElementsByClassName("filtra")[0], "input", this.filtra.bind(this));
            
            return html;
        } catch (error) {
            console.error("Erro ao criar estrutura fixa:", error);
            return document.createElement("DIV");
        }
    }
    
    removeComentariosCarregando() {
        try {
            // Seleciona todos os elementos com a classe 'comentario-carregando'
            const elementosCarregando = document.querySelectorAll('.comentario-carregando');

            // Remove cada um dos elementos encontrados
            elementosCarregando.forEach(elemento => {
                elemento.remove();
            });
        } catch (error) {
            console.error("Erro ao remover comentários de carregamento:", error);
        }
    }
    
    filtra(event) {
        try {
            const valor = event.currentTarget.value;

            this.lista.innerHTML = "";
            
            // Adicionar esqueletos de carregamento
            const fragmento = document.createDocumentFragment();
            for (let i = 0; i < 5; i++) {
                const div = this.comentario(false);
                div.classList.add("comentario-carregando");
                fragmento.appendChild(div);
            }
            this.lista.appendChild(fragmento);
            
            this.inicial = valor;
            this.loadMessages(this.modulo, this.url, this.html);
        } catch (error) {
            console.error("Erro ao filtrar comentários:", error);
        }
    }
    
    loadMessages(modulo, url, html = false) {
        try {
            let request = new Request(`${dominioscript}/conteudo/modulos/comentarios/admins/api.php`);
            request.addData({
                "modulo": modulo,
                "url": url,
                "paginacao": 1,
                "ordenagem": this.inicial,
                "acao": "init"
            });
            
            request.send().then((r) => {
                if (r.total === 0) {
                    this.removeComentariosCarregando();
                    
                    // Atualizar contador
                    if (html && html.getElementsByClassName("contador").length > 0) {
                        html.getElementsByClassName("contador")[0].innerText = "Nenhum Comentário";
                    }
                    
                    let primeiro = document.createElement("DIV");
                    primeiro.classList.add("text-center", "semComentarios", "py-4");
                    primeiro.innerHTML = `
                    <div><i class="bi bi-chat-square-text fs-3 text-muted mb-3"></i></div>
                    <div class="text-muted mb-2">Ainda não há nenhum comentário</div>
                    <h2 class="text-center fs-4">Seja o Primeiro</h2>
                    `;
                    
                    document.getElementById("provisorio2").appendChild(primeiro);
                    return;
                }
                
                // Atualizar contador
                if (html && html.getElementsByClassName("contador").length > 0) {
                    html.getElementsByClassName("contador")[0].innerText = r.total === 1 ? `Um Comentário` : `${r.total} Comentários`;
                }
                
                if (html) {
                    html.dataset.identificador = r.id;
                }

                // Adicionar autores ao objeto this.autores
                for (let c in r.autores) {
                    this.autores[c] = r.autores[c];
                }
                
                const lista = r.lista;
                const fragmento = document.createDocumentFragment();
                
                // Adicionar comentários
                lista.forEach(item => {
                    fragmento.appendChild(this.comentario(item));
                });
                
                if (r.totalNivel > 20) {
                    // Botão "carregar mais"
                    const button = document.createElement("BUTTON");
                    button.classList.add("btn", "btn-n-primaria", "d-block", "mx-auto", "my-3");
                    button.innerHTML = `<i class="bi bi-plus-circle me-2"></i>Carregar Mais`;
                    button.dataset.pagination = 2;
                    evento(button, "click", this.loadMore.bind(this));
                    fragmento.appendChild(button);
                }
                
                document.getElementById("provisorio2").appendChild(fragmento);
                this.removeComentariosCarregando();
                
            }).catch((r) => {
                console.error("Erro ao carregar mensagens:", r);
                
                this.removeComentariosCarregando();
                
                // Mensagem de erro
                const errorMessage = document.createElement("DIV");
                errorMessage.classList.add("alert", "alert-danger", "my-3");
                errorMessage.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Ocorreu um erro ao carregar os comentários. Tente novamente.
                `;
                
                document.getElementById("provisorio2").appendChild(errorMessage);
                
                // Botão para tentar novamente
                const retryButton = document.createElement("BUTTON");
                retryButton.classList.add("btn", "btn-outline-primary", "d-block", "mx-auto");
                retryButton.innerText = "Tentar novamente";
                evento(retryButton, "click", () => {
                    document.getElementById("provisorio2").innerHTML = "";
                    
                    // Adicionar esqueletos de carregamento
                    const fragmento = document.createDocumentFragment();
                    for (let i = 0; i < 5; i++) {
                        const div = this.comentario(false);
                        div.classList.add("comentario-carregando");
                        fragmento.appendChild(div);
                    }
                    document.getElementById("provisorio2").appendChild(fragmento);
                    
                    this.loadMessages(modulo, url, html);
                });
                
                document.getElementById("provisorio2").appendChild(retryButton);
            });
        } catch (error) {
            console.error("Erro ao carregar mensagens:", error);
        }
    }
}

