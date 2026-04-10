export function cripto(jsonString) {
  return new Promise((resolve, reject) => {
    try {
      const encoder = new TextEncoder();
      const data = encoder.encode(jsonString);

      crypto.subtle.digest('SHA-256', data)
        .then(hashBuffer => {
          const hashArray = Array.from(new Uint8Array(hashBuffer));
          const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');
          resolve(hashHex);
        })
        .catch(error => reject('Erro ao calcular o hash: ' + error));
    } catch (error) {
      reject('Erro ao converter objeto para JSON: ' + error);
    }
  });
}

export function pegaLocal(index) {
  if (typeof localStorage !== "undefined") {
    const data = localStorage.getItem(index);

    if (data && data !== undefined) {
      return data;
    }
  }

  return false;
}

export function defineLocal(index, value) {
  if (typeof localStorage !== "undefined") {
    localStorage.setItem(index, value);
  }
}

export function loading(pai){
    let load = document.querySelector('.loader-wrap') ? document.querySelector('.loader-wrap') : false
    load ? load.style.display = "block" : "";
    if(pai){
           pai.innerHTML = `
       <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Carregando ...</span>
            </div>
        </div>`
    }
}

export function criarIcone(classeIcone) {
        var trato = classeIcone.split(" ");
         var span = document.createElement("SPAN")
               span.classList.add("icone")
  if (trato.length == 2 && trato[0] == "bi") {

      var i = document.createElement("I")
      i.classList.add("bi", trato[1], "text-contrast")
      span.appendChild(i)

  } else {
    // Ícone Google Material Icons
     var i = document.createElement("I")
      i.classList.add("material-symbols-outlined", "text-contrast")
      i.innerText = classeIcone
      span.appendChild(i)
      

  }
  
  return span;
}

export function preventLink(){
     event.preventDefault();
 
        var link = event.currentTarget.href

        if(link.includes(dominio)){
            var caminho = link.replace(`${dominio}/`, "")
             var url = `${dominio}/${caminho}`
    window.history.pushState(null, null, url);
    start.conteudo.go();
        }else{
             window.location.href = link
        }
}  

export function evento(item, acao, funcao, valor = false){

    acao = acao.toLowerCase();
    function addEvento(elemento, valor){

        if(valor){
             elemento.addEventListener(acao, function(){ funcao(valor)});
        }else{
             elemento.addEventListener(acao, funcao);
        }
        
         var registro = {};
         registro.item = elemento
         registro.acao = acao
         registro.funcao = funcao
        
         ouvidoresEventos.push(registro);
    }
    
    if(Array.isArray(item)){
        var i = 0;
        while(i < item.length){
            addEvento(item[i],valor)

            i++;
        }
    }else{
        if(item){
           addEvento(item, valor)
        }
        
    }
}

export function ajax(infos, acao, callback = false) {
    
    var data = new FormData();
    data.append("infos", JSON.stringify(infos))
    data.append("acao", acao);
    
    if(document.getElementById("typerDoc")){
         data.append("typerDoc", document.getElementById("typerDoc").dataset.tipo);
    }

     const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (callback) {
                callback(this.responseText)
      
            } else {
                console.log(this.responseText);
            }

        }
        xhttp.open("POST", `${dominioAdress}/admin/ajax.php`);
        xhttp.send(data);
}

export function loadGoogleMaterialIcons() {
    return new Promise((resolve, reject) => {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = "https://fonts.googleapis.com/icon?family=Material+Icons";

        link.onload = () => {
            resolve("Google Material Icons loaded");
        };

        link.onerror = () => {
            reject(new Error("Failed to load Google Material Icons"));
        };

        document.head.appendChild(link);
    });
}

export function ls(url, callback = false){
    if(url){
    let src = `${url}`

    var script = document.getElementsByTagName("script")
    var i = 0;
    var have = false;
    
    while(i < script.length){
        if(script[i].src.split("?")[0] == src){
            have = true;
        }
        i++;
    }
    
    
    if(!have){
        var script = document.createElement("SCRIPT")
        script.setAttribute("src", `${src}?v=${geraId()}`)

        document.getElementsByTagName("head")[0].appendChild(script)
        script.onload = ()=>{
            console.log("sciprot carregado")
            if(callback){
                callback();
            }
            
        }
    }else{
          if(callback){
                callback();
            }
    }
  
    }
    
}

export function lcss(url) {
    return new Promise((resolve, reject) => {
        if (url) {
            const src = `${url}`;

            const links = document.getElementsByTagName("link");
            let i = 0;
            let have = false;

            while (i < links.length) {
                if (links[i].href.split("?")[0] === src) {
                    have = true;
                    break;
                }
                i++;
            }

            if (!have) {
                const link = document.createElement("link");
                link.setAttribute("href", `${src}?v=${geraId()}`);
                link.setAttribute("rel", "stylesheet");

                link.onload = () => {
                    resolve('CSS loaded');
                };

                link.onerror = () => {
                    reject(new Error('Failed to load CSS'));
                };

                document.getElementsByTagName("head")[0].appendChild(link);
            } else {
                resolve('CSS already loaded');
            }
        } else {
            reject(new Error('No URL provided'));
        }
    });
}

export function loadResources(url) {
  return new Promise((resolve, reject) => {
    // Verifica se o recurso é JavaScript ou CSS com base na extensão do arquivo
    const extension = url.slice(((url.lastIndexOf(".") - 1) >>> 0) + 2).toLowerCase();

    let element;
    if (extension === 'js') {
      element = document.createElement('script');
      element.src = url;
    } else if (extension === 'css') {
      element = document.createElement('link');
      element.rel = 'stylesheet';
      element.href = url;
    } else {
      reject(new Error('Tipo de arquivo não suportado.'));
    }

    element.onload = () => {
      resolve(element);
    };

    element.onerror = () => {
      reject(new Error(`Erro ao carregar o recurso: ${url}`));
    };

    // Adicione o elemento ao documento para iniciar o carregamento
    document.head.appendChild(element);
  });
}

export function geraId(size = 10) {
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  let randomId = '';

  for (let i = 0; i < size; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    randomId += characters.charAt(randomIndex);
  }

  return randomId;
}






