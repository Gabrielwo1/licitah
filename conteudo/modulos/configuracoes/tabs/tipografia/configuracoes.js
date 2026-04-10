class TipografiaConfig{
    constructor(r){
   
        this.pre = r.respostas
        this.ajax.bind(this)()
        
        
        
    }
    
    completa(r){
   
        this.pre = r.respostas
        this.render.bind(this)()
    }
    
    selecionou(){
        var selecionado = this.renderizado[this.familia.value];
        
        this.subs.innerHTML = ""
        this.pesos.innerHMTL = ""
        
        var i = 0;
        while(i < selecionado.subsets.length){
            var sub = selecionado.subsets[i]
            var option = document.createElement("OPTION")
            option.value = sub
            option.innerText = sub
            this.subs.appendChild(option)
            i++;
        }
        
        var allowed = ['100','200','300','400','500','600','700','800','900','regular'];
        
        var i = 0;
        while(i < selecionado.variants.length){
            var sub = selecionado.variants[i]
            var option = document.createElement("OPTION")
            if(allowed.includes(sub)){
                sub = (sub == 'regular' ? '400' : sub)
                option.value = sub
                option.innerText = sub
                if(document.querySelectorAll('#pesos option[value="'+sub+'"]')[0] == null){
                    this.pesos.appendChild(option);
                }
            }
            i++;
        }
        
        sortSelect(this.pesos);
        console.log(selecionado)
        setTimeout(() => {
            this.preview.bind(this)({family: selecionado.family, weight: this.pesos.value})
        }, 100)
        
    }
    
    render(){
        this.familia = document.getElementById("fontFamily")
        this.alinhamento = document.getElementById("alinhamento")
        this.transformacao = document.getElementById("transformacao")
        this.pesos = document.getElementById("pesos")
        this.subs = document.getElementById("subs")
        
        
        

        loadResources("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css")
        loadResources("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js").then(()=>{
            $(`#fontFamily`).select2();
            $(`#fontFamily`).on('change', this.selecionou.bind(this))
        })
        
        evento(this.pesos, 'change', () => {
            this.preview.bind(this)({family: $(`#fontFamily`).val(), weight: this.pesos.value})
        });
       
       
       
        for(let i in this.renderizado){
            var option = document.createElement("OPTION")
            option.innerText = i
            option.value = i
            this.familia.appendChild(option)
            
            i++;
        }
        
    
        if(this.pre){

            if(this.pre.familia){
                this.familia.value = this.pre.familia
                this.selecionou.bind(this)()
            }
            
            if(this.pre.alinhamento){
                document.getElementById("alinhamento").value = this.pre.alinhamento
            }
            
            if(this.pre.pesos){
                document.getElementById("pesos").value = this.pre.pesos
            }
            
            if(this.pre.subs){
                document.getElementById("subs").value = this.pre.subs
            }
            
            if(this.pre.transformacao){
                document.getElementById("transformacao").value = this.pre.transformacao
            }
               
            
            if(this.pre.tamanhos){
                var obj = JSON.parse(this.pre.tamanhos)
                
                for(let i in obj){
                    for(let j in obj[i]){
                        const elemento = document.querySelector(`input[data-dispositivo="${i}"][data-prop="${j}"]`);
                        if(elemento){
                            elemento.value = obj[i][j]
                        }
                     
                    }
                }
            }

        }
        
        
    }
    
    preview(font){
        this.previewFont = document.querySelector("#previewFont")
        this.previewFont.innerHTML = `
        <style>@import url('https://fonts.googleapis.com/css2?family=${font.family}:wght@${font.weight}&display=swap');#previewFont span { font-family: '${font.family}' !important; font-weight: ${font.weight} !important;}</style>
        <span>The quick brown fox jumps over the lazy dog.</span>
        `;
    }
    
    
    get(){
        
        var tamanhos = document.getElementsByClassName("sizers")
        var sizes = {}
        
        
        var i = 0;
        while(i < tamanhos.length){
            var item = tamanhos[i]
            var dispositivo = item.dataset.dispositivo
            var propriedade = item.dataset.prop
            
            if(!sizes[dispositivo]){
                sizes[dispositivo] = {}
            }
            
            sizes[dispositivo][propriedade] = item.value
            i++;
        }
        
 
        
        var resp = {
            familia: document.getElementById("fontFamily").value,
            alinhamento : document.getElementById("alinhamento").value,
            transformacao : document.getElementById("transformacao").value,
            pesos : document.getElementById("pesos").value,
            subs : document.getElementById("subs").value,
            tamanhos: sizes,
        }
       return resp;
    }
    
    ajax(){
        const apiURL = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyAOG--V_Dg6qH3UwVGMIw2aKBkJnyX1Z9c';
        const xhr = new XMLHttpRequest();
        
        xhr.open('GET', apiURL, true);
        xhr.responseType = 'json';
        xhr.onload = ()=> {
    if (xhr.status === 200) {
      const data = xhr.response;
      this.fonts = data.items;
      
      this.renderizado = {};
      for(let i in this.fonts){
          this.renderizado[this.fonts[i].family] = this.fonts[i]
      }
      
      this.render.bind(this)()
    } else {
      console.error('Erro ao recuperar informações das fontes:', xhr.status, xhr.statusText);
    }
  };
        xhr.onerror = function () {
            console.error('Erro de rede ao fazer a solicitação.');
        };
        xhr.send();   
        
    }
}
tipografo;

function configuracoesNownTipografia(pagina, r){
    packLoad([
              `${dominio}/assets/aplicativo/jquery/min.js`,
            `${dominio}/assets/aplicativo/select2/41.css`,
            `${dominio}/assets/aplicativo/select2/41.js`,]).then(()=>{
                     tipografo = new  TipografiaConfig(r);
            })

}