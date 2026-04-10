class ControlerColor{
    constructor(){
        var inputs = document.querySelectorAll("input[type='color']");
        

        
        inputs[0].dataset.foco = "primaria"
        inputs[1].dataset.foco = "secundaria"
        inputs[2].dataset.foco = "terciaria"
        inputs[2].dataset.foco = "quaternaria"
        
        evento(Array.from(inputs), "input", this.muda.bind(this))
    }
    
    muda(){
        var input = event.currentTarget
        document.documentElement.style.setProperty(`--nown-${input.dataset.foco}`, input.value);
        document.documentElement.style.setProperty(`--nown-${input.dataset.foco}-lighter`, input.value);
        document.documentElement.style.setProperty(`--nown-${input.dataset.foco}-darker`, input.value);
    }
    
    
}

class EstiloControler{
    constructor(){

        this.montaPreview.bind(this)('primaria')
        
        this.previewElementsSelect = document.querySelector("#estiloPrevia");
        
        evento(this.previewElementsSelect, "change", () => {
                        var estilo = this.previewElementsSelect.value
                        this.previewElementStyle = estilo
                        this.montaPreview.bind(this)(estilo)
                    })
    }
    
       montaPreview(estilo){
        this.renderPreview = document.querySelector("#previewElementos");
        this.renderPreview.innerHTML = `
            <div class="preview-variaveis">
                <h5>Variáveis CSS</h5>
                <div class="var-preview-color"><span style="background-color: var(--nown-${estilo})"></span> <code>--nown-${estilo}</code></div>
                <div class="var-preview-color"><span style="background-color: var(--nown-${estilo}-lighter)"></span> <code>--nown-${estilo}-lighter</code></div>
                <div class="var-preview-color"><span style="background-color: var(--nown-${estilo}-darker)"></span> <code>--nown-${estilo}-darker</code></div>
                <div class="var-preview-color"><span style="background-color: var(--nown-${estilo}-text-over)"></span> <code>--nown-${estilo}-text-over</code> (Identificação inteligente se o texto deve ser branco ou preto)</div>
                
                <h5 class="mt-4">Cores de Texto</h5>
                <div class="var-preview-texto"><span class="text-${estilo}">Lorem ipsum dolor sit</span> <code>.text-${estilo}</code></div>
                <div class="var-preview-texto"><span class="text-${estilo}-lighter">Lorem ipsum dolor sit</span> <code>.text-${estilo}-lighter</code></div>
                <div class="var-preview-texto"><span class="text-${estilo}-darker">Lorem ipsum dolor sit</span> <code>.text-${estilo}-darker</code></div>
                
                <h5 class="mt-4">Cores de Background</h5>
                <div class="var-preview-bg"><span class="bg-${estilo}"></span> <code>.bg-${estilo}</code></div>
                <div class="var-preview-bg"><span class="bg-${estilo}-lighter"></span> <code>.bg-${estilo}-lighter</code></div>
                <div class="var-preview-bg"><span class="bg-${estilo}-darker"></span> <code>.bg-${estilo}-darker</code></div>
                <div class="var-preview-bg"><span class="bg-light bg-hover-${estilo}">PASSE O MOUSE</span> <code>.bg-hover-${estilo}</code></div>
                <div class="var-preview-bg"><span class="bg-light bg-hover-${estilo}-lighter">PASSE O MOUSE</span> <code>.bg-hover-${estilo}-lighter</code></div>
                <div class="var-preview-bg"><span class="bg-light bg-hover-${estilo}-darker">PASSE O MOUSE</span> <code>.bg-hover-${estilo}-darker</code></div>
                
                <h5 class="mt-4">Cores de Borda</h5>
                <div class="var-preview-border"><span class="bg-light border border-${estilo}"></span> <code>.border-${estilo}</code></div>
                <div class="var-preview-border"><span class="bg-light border border-${estilo}-lighter"></span> <code>.border-${estilo}-lighter</code></div>
                <div class="var-preview-border"><span class="bg-light border border-${estilo}-darker"></span> <code>.border-${estilo}-darker</code></div>
                
                <h5 class="mt-4">Botões</h5>
                <div class="var-preview-botao"><span class="btn btn-n-${estilo}">Lorem Ipsum</span> <code>.btn .btn-n-${estilo}</code></div>
                <div class="var-preview-botao"><span class="btn btn-n-${estilo}-darker">Lorem Ipsum</span> <code>.btn .btn-n-${estilo}-darker</code></div>
                <div class="var-preview-botao"><span class="btn btn-nown-style btn-n-${estilo}">Lorem Ipsum</span> <code>.btn .btn-nown-style .btn-n-${estilo}</code></div>
                <div class="var-preview-botao"><span class="btn btn-nown-style btn-n-${estilo}-darker">Lorem Ipsum</span> <code>.btn .btn-nown-style .btn-n-${estilo}-darker</code></div>
            </div>
        `;
    }
}

class MaterialThemeGenerator {
    constructor(primaryColor = "#6200EE") {
        
        this.primaryColor = primaryColor;
       
        
    }

    async loadMaterialUtilities() {
        try {
            const module = await import("https://cdn.skypack.dev/@material/material-color-utilities");
            
            console.log(module)
   
            
            
            this.module = module
            this.argbFromHex = module.argbFromHex;
            this.themeFromSourceColor = module.themeFromSourceColor;
            this.hexFromArgb = module.hexFromArgb;
            this.TonalPalette = module.TonalPalette;
        } catch (error) {
            console.error("Failed to load material utilities:", error);
            throw new Error("Material utilities could not be loaded.");
        }
    }

    async generateTheme() {
        if (!this.argbFromHex || !this.themeFromSourceColor || !this.hexFromArgb) {
            await this.loadMaterialUtilities();
        }
     

        try {
            const sourceColor = this.argbFromHex(this.primaryColor);
            
         

            const theme = this.themeFromSourceColor(sourceColor);
            

       
            
          
            return {
                light: this.extractColors(theme.schemes.light),
                dark: this.extractColors(theme.schemes.dark),
            };
        } catch (error) {
            console.error("Failed to generate theme:", error);
            return {
                light: {},
                dark: {},
            };
        }
    }

    extractColors(scheme) {
        


        const keys = [
            "primary", "onPrimary", "primaryContainer", "onPrimaryContainer",
            "secondary", "onSecondary", "secondaryContainer", "onSecondaryContainer",
            "tertiary", "onTertiary", "tertiaryContainer", "onTertiaryContainer",
            "background", "onBackground", "surface", "onSurface",
            "surfaceVariant", "onSurfaceVariant", "error", "onError",
            "errorContainer", "onErrorContainer", "outline",
            "inverseSurface", "inverseOnSurface", "inversePrimary",
        ];

        const colors = keys.reduce((colors, key) => {
            colors[key] = this.hexFromArgb(scheme[key]);
            return colors;
        }, {});
        
      
      
    
 
       
        

        return colors;
    }

}

class ControlerTema {
    constructor(light = true) {
        this.light = light;
        this.input = document.getElementById("cor");
        this.chroma = null; // Inicializa como nulo
        
        var cor = document.getElementById("cor")
        evento(cor, "input", this.change.bind(this))
    }
    
    async change(){
        var cor =  document.getElementById("cor").value
        
        const generator = new MaterialThemeGenerator(cor);
        const themes = await generator.generateTheme();
        
    
        if(this.light){
            var tema = themes.light
        }else{
            var tema = themes.dark;
        }
        
    
        
        for(let c in tema){
            if(document.getElementById(c)){
                document.getElementById(c).value = tema[c]
            }else{
                console.log(c)
            }
        }
    

    }

}


function configuracoesNownEstilo(pagina, r){
    console.log(pagina)
    switch(pagina){
        case 'elementos':
            new EstiloControler();
            break;
        case 'cores':
            new ControlerColor();
            break;
        case 'tema-light':
            new ControlerTema(true);
            break;
          case 'tema-dark':
            new ControlerTema(false);
            break;
    }
}