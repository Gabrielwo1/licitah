function botaoEmpresasItem(){
    var botoes = document.getElementsByClassName('grupo-nav-item')
    
    var i = 0
    var variavel = false
   
   
    while(i < botoes.length){
        evento(botoes[i], 'click', redirecaoMesmaPagina)
        
        if(botoes[i].dataset.url == 'informacoes'){
            var informacao = botoes[i]
        }
        
        if(caminho.hash() == botoes[i].dataset.url){
            botoes[i].classList.add('active')
            variavel = true
        }
        
        botoes[i].removeAttribute('disabled')

        i ++
    }
    
    if(!variavel){
        informacao.classList.add('active')
    }
}

function redirecaoMesmaPagina(){
    var url = window.location.href
    
    url = url.split('/')
    
    goUrl('empresas/'+ url[4] + '/' + event.currentTarget.dataset.url)

}

class ItemEmpresa{
    url(){
        var url = window.location.href
    
        url = url.split('/')
        
        return url[url.length - 1]
    }
    
    constructor(){
        var url = this.url.bind(this)()
        
        this.titulo = {
            "informacoes": "Informações",
            "dados": "Dados",
            "redes-sociais": "Redes Sociais",
            "endereco": "Endereço",
            "socio": "Sócios",
            "atividades": "Atividades",
            "galeria": "Galeria"
        }
        
        this.div = document.getElementById('dados-de-insercao')
        
        this.div.innerHTML = ''

        if(url != 'informacoes' && url != 'dados' && url != 'redes-sociais' && url != 'endereco' && url != 'socios' && url != 'atividades' && url != 'galeria'){
            this.caminho = url
        }
        else{
            var url2 = window.location.href
    
            url2 = url2.split('/')
            
            this.caminho = url2[4]
        }
        
        var api = new ApiNown("empresas", "Mi2IBoxrCrNP29O");
        api.setHash(this.caminho);
        api.start(this.monta.bind(this))
    }
    
    monta(r){
        this.item = r.item 
        
        
        this.montaDados.bind(this)()

    }
    
    montaDados(){
        
        console.log(this.titulo, this.caminho)
        var titulo = this.titulo[this.url.bind(this)()] ?? 'Informações'
        this.div.innerHTML = `<div class="d-flex flex-column gap-2"> <h2 class="fw-700 mb-4">${titulo}</h2> </div>`
    }
}

class Pesquisa{
    constructor(){
        nownFiles.add([`${dominio}/assets/aplicativo/taglify/tagify.js`,`${dominio}/assets/aplicativo/taglify/tagify.css`]).then(()=>{
            this.init.bind(this)();
        })
        
    }
    
    init(){
        this.estado =  document.getElementById("estado");
     
       // this.estado.addEventListener("change", this.estadou.bind(this))
        this.resultados = document.getElementById("resultados");
        this.btnpesquisa = document.getElementById("pesquisar");
        this.entradas = document.getElementsByClassName("entrada")
        this.btnrapido = document.getElementById("pesquisarrapido")
        this.btnrapido.addEventListener("click", this.rapido.bind(this))
        this.btnpesquisa.addEventListener("click" , this.pesquisar.bind(this))
        document.getElementById("baixar").addEventListener("click", this.downloadCSV.bind(this))
        this.vai = document.getElementById("vai")
        this.vai.addEventListener("click", ()=>{ this.go.bind(this)(+1)})
        this.voltar = document.getElementById("voltar")
        this.voltar.addEventListener("click", ()=>{ this.go.bind(this)(-1)})
        
        this.para = false;
        
        this.btnPara = document.getElementById("interromper")
        this.btnPara.addEventListener("click", ()=>{
            this.para = true;
        })
        
        
        this.result = [];
        this.rapidao = {};
        this.page = 1;
        this.fast = false;
        
        
        
        let request = new XMLHttpRequest()
        request.onload = ()=>{
            var obj = JSON.parse(request.responseText)
             this.inAtividade = new Tagify(document.querySelector('[data-key="ativdade"]'), {whitelist: obj,  dropdown: {
 maxItems: 30,
       enabled: 0,
}})
        }
        request.open("GET", `${dominio}/conteudo/modulos/empresas/data/cnaes.json`);
        request.send()
        this.inRazao = new Tagify(document.querySelector('[data-key="razao"]'), {})
       
        this.inBairro = new Tagify(document.querySelector('[data-key="bairro"]'), {})
        this.inCep = new Tagify(document.querySelector('[data-key="cep"]'), {})
        this.inDdd = new Tagify(document.querySelector('[data-key="ddd"]'), {})
     const naturezasJuridicas = [
{ value: "2011 - Empresa PÃºblica" },
{ value: "2038 - Sociedade de Economia Mista" },
{ value: "2046 - Sociedade AnÃ´nima Aberta" },
{ value: "2054 - Sociedade AnÃ´nima Fechada" },
{ value: "2062 - Sociedade EmpresÃ¡ria Limitada" },
{ value: "2070 - Sociedade EmpresÃ¡ria em Nome Coletivo" },
{ value: "2089 - Sociedade EmpresÃ¡ria em Comandita Simples" },
{ value: "2097 - Sociedade EmpresÃ¡ria em Comandita por AÃ§Ãµes" },
{ value: "2127 - Sociedade em Conta de ParticipaÃ§Ã£o" },
{ value: "2143 - Cooperativa" },
{ value: "2151 - ConsÃ³rcio de Sociedades" },
{ value: "2160 - Grupo de Sociedades" },
{ value: "2232 - Sociedade Simples Pura" },
{ value: "2240 - Sociedade Simples Limitada" },
{ value: "2259 - Sociedade Simples em Nome Coletivo" },
{ value: "2267 - Sociedade Simples em Comandita Simples" },
{ value: "2291 - ConsÃ³rcio Simples" },
{ value: "2305 - Empresa Individual de Responsabilidade Limitada (de Natureza EmpresÃ¡ria)" },
{ value: "2313 - Empresa Individual de Responsabilidade Limitada (de Natureza Simples)" },
{ value: "3069 - FundaÃ§Ã£o Privada" },
{ value: "3220 - OrganizaÃ§Ã£o Religiosa" },
{ value: "3301 - OrganizaÃ§Ã£o Social (OS)" },
{ value: "3999 - AssociaÃ§Ã£o Privada" },
{ value: "4120 - Produtor Rural (Pessoa FÃ­sica)" }
];

this.inNatureza = new Tagify(document.querySelector('[data-key="natureza"]'), {
whitelist: naturezasJuridicas,
dropdown: {
    enabled: 0,
    maxItems: 30,
}
});


        this.memorias = {};
        
        this.inEstado = new Tagify(document.querySelector('[data-key="estado"]'), {
            whitelist:  ["AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO"],
            enforceWhitelist: true,
              dropdown: {
                  maxItems: 30,
                  enabled: 0, 
                  closeOnSelect: false
              }
        });
        this.inCidades = new Tagify(document.querySelector('[data-key="cidades"]'), {enforceWhitelist: true});
        this.inEstado.on('change', (e) => {
                 var selecionados = [];
            if(e.detail.value){
                var obj = JSON.parse(e.detail.value)
       
            var i = 0;
            while(i < obj.length){
                 selecionados.push(obj[i].value)
                   if(!this.memorias[obj[i].value]){
                       this.memorias[obj[i].value] = true;
                        this.estadou.bind(this)(obj[i].value);
                   }
                i++;
            }
          
            
            
          
            }
            
              var excluir = [];
            for(let c in this.memorias){
                if(!selecionados.includes(c)){
                    excluir.push(c)
                
                }
            }
            
            
              for(let c in excluir){
                var x = excluir[c]
                var remover = this.memorias[x]
                this.removeCidades.bind(this)(remover)
                delete this.memorias[x];
  
            }
         
        });

    }
    
    estadou(valor){
   

    if(valor != "0"){
        let request = new XMLHttpRequest()
        request.onload = ()=>{
            var resposta = JSON.parse(request.responseText)
            
            const names = resposta.map(city => city.name);
            this.memorias[valor] = names
            this.inCidades.whitelist.push(...names);

            
    
            
        }
        request.open("GET", `https://api.casadosdados.com.br/v4/public/cnpj/busca/municipio/${valor}`)
        request.send()
    }
    }
    
    removeCidades(itemsToRemove) {
var cidadesInput = document.querySelector('[data-key="cidades"]');
var cidades = cidadesInput.value;
var cidadesObj = cidades ? JSON.parse(cidades) : [];
var array = [];
var i = 0;
while(i < cidadesObj.length){
    array.push(cidadesObj[i].value)
    i++;
}



// Remove items from the whitelist
this.inCidades.settings.whitelist = this.inCidades.settings.whitelist.filter(city => {
    array = array.filter(item => item !== city);

    return !itemsToRemove.includes(city);
});

console.log(array)

this.inCidades.removeAllTags();
cidadesObj.forEach(city => {
    this.inCidades.addTags([city.value]);
});


cidadesInput.value = JSON.stringify(this.inCidades.settings.whitelist);

// this.inCidades.dropdown.show.call(this.inCidades);
}

    formatarCNPJ(numero) {
// Remove todos os caracteres nÃ£o numÃ©ricos
numero = numero.replace(/\D/g, '');

// Formata o CNPJ
numero = numero.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');

return numero;
     }

    downloadCSV() {
    console.log(this.result);
    
    let data = this.result.map(empresa => {
        // Determina o nome a ser usado: nome_fantasia ou razao_social
        const nomeEmpresa = empresa.nome_fantasia || empresa.razao_social;
        
        var obj = {
            CNPJ: empresa.cnpj,
            Nome: empresa.nome_fantasia,
            "Razão Social": empresa.razao_social,
            "Situação": empresa.situacao_cadastral?.situacao_atual || ''
        };
       
        var extra = empresa?.extra || false;
        
        // Reorganiza o objeto com campos do extra
        if(extra) {
            // Campos básicos
            obj["Capital Social"] = extra.capital_social || '';
            obj["Data Abertura"] = extra.data_abertura || '';
            obj["Matriz/Filial"] = extra.matriz_filial || '';
            obj["Natureza Jurídica"] = extra.natureza_juridica || '';
            
            // MEI
            obj["É MEI"] = extra.mei?.eh_mei || 'Não';
            obj["Data Opção MEI"] = extra.mei?.data_opcao_mei || '';
            obj["Data Exclusão MEI"] = extra.mei?.data_exclusao_mei || '';
            
            // Simples Nacional
            obj["Optante Simples"] = extra.simples?.optante || 'Não';
            obj["Data Opção Simples"] = extra.simples?.data_opcao_simples || '';
            obj["Data Exclusão Simples"] = extra.simples?.data_exclusao_simples || '';
            
            // Atividades
            obj["CNAE Principal"] = extra.atividades?.cnae_principal || '';
            obj["CNAEs Secundários"] = extra.atividades?.cnaes_secundarios ? 
                extra.atividades.cnaes_secundarios.join(' | ') : '';
            
            // Endereço completo
            var endereco = extra.endereco;
            if(endereco) {
                obj["Endereço"] = `${endereco.logradouro || ''}, ${endereco.numero || ''} ${endereco.complemento ? '- ' + endereco.complemento : ''} - ${endereco.bairro || ''} - ${endereco.municipio || ''} - ${endereco.estado || ''} (${endereco.cep || ''})`;
                obj["CEP"] = endereco.cep || '';
                obj["Município"] = endereco.municipio || '';
                obj["Estado"] = endereco.estado || '';
                obj["Bairro"] = endereco.bairro || '';
                obj["Logradouro"] = endereco.logradouro || '';
                obj["Número"] = endereco.numero || '';
                obj["Complemento"] = endereco.complemento || '';
            } else {
                obj["Endereço"] = '';
                obj["CEP"] = '';
                obj["Município"] = '';
                obj["Estado"] = '';
                obj["Bairro"] = '';
                obj["Logradouro"] = '';
                obj["Número"] = '';
                obj["Complemento"] = '';
            }
            
            // Contato
            var contato = extra.contato;
            if(contato) {
                obj["Email"] = contato.email || '';
                obj["Telefones"] = contato.telefones ? contato.telefones.join(' | ') : '';
            } else {
                obj["Email"] = '';
                obj["Telefones"] = '';
            }
            
            // Sócios - ESTRUTURA FIXA para evitar quebras no CSV
            obj["Quantidade Sócios"] = extra.socios ? extra.socios.length : 0;
            obj["Sócios"] = extra.socios ? extra.socios.map(socio => 
                `${socio.nome} (${socio.qualificacao} - ${socio.data_entrada})`
            ).join(' | ') : '';
            
            // Sempre criar exatamente 5 campos de sócios (ajuste conforme necessário)
            for(let i = 1; i <= 5; i++) {
                if(extra.socios && extra.socios[i-1]) {
                    obj[`Sócio ${i} Nome`] = extra.socios[i-1].nome;
                    obj[`Sócio ${i} Qualificação`] = extra.socios[i-1].qualificacao;
                    obj[`Sócio ${i} Data Entrada`] = extra.socios[i-1].data_entrada;
                } else {
                    obj[`Sócio ${i} Nome`] = '';
                    obj[`Sócio ${i} Qualificação`] = '';
                    obj[`Sócio ${i} Data Entrada`] = '';
                }
            }
            
            // Situação cadastral detalhada
            if(extra.situacao_cadastral) {
                obj["Situação Detalhada"] = extra.situacao_cadastral.situacao || '';
                obj["Data Situação"] = extra.situacao_cadastral.data_situacao || '';
                obj["Motivo Situação"] = extra.situacao_cadastral.motivo_situacao || '';
            } else {
                obj["Situação Detalhada"] = '';
                obj["Data Situação"] = '';
                obj["Motivo Situação"] = '';
            }
            
            obj["Última Atualização"] = extra.ultima_atualizacao || '';
            
            console.log(extra);
        } else {
            // Se não há dados extra, preencher com valores vazios para manter estrutura
            obj["Capital Social"] = '';
            obj["Data Abertura"] = '';
            obj["Matriz/Filial"] = '';
            obj["Natureza Jurídica"] = '';
            obj["É MEI"] = '';
            obj["Data Opção MEI"] = '';
            obj["Data Exclusão MEI"] = '';
            obj["Optante Simples"] = '';
            obj["Data Opção Simples"] = '';
            obj["Data Exclusão Simples"] = '';
            obj["CNAE Principal"] = '';
            obj["CNAEs Secundários"] = '';
            obj["Endereço"] = '';
            obj["CEP"] = '';
            obj["Município"] = '';
            obj["Estado"] = '';
            obj["Bairro"] = '';
            obj["Logradouro"] = '';
            obj["Número"] = '';
            obj["Complemento"] = '';
            obj["Email"] = '';
            obj["Telefones"] = '';
            obj["Quantidade Sócios"] = '';
            obj["Sócios"] = '';
            
            // Campos fixos de sócios vazios
            for(let i = 1; i <= 5; i++) {
                obj[`Sócio ${i} Nome`] = '';
                obj[`Sócio ${i} Qualificação`] = '';
                obj[`Sócio ${i} Data Entrada`] = '';
            }
            
            obj["Situação Detalhada"] = '';
            obj["Data Situação"] = '';
            obj["Motivo Situação"] = '';
            obj["Última Atualização"] = '';
        }
       
        return obj;
    });
    
    // Resto do código para gerar o CSV
    var chaves = Object.keys(data[0]);
    
    function objectToCSVRow(obj) {
        const values = Object.values(obj);
        const escapedValues = values.map(value => {
            // Converter para string e tratar valores null/undefined
            const stringValue = value !== null && value !== undefined ? String(value) : '';
            
            if (typeof stringValue === 'string') {
                // Regex para telefones (XX-XXXXXXXX)
                const regex = /\b\d{2}-\d{8}\b/;
                if(regex.test(stringValue)) {
                    if (!stringValue) return '';
                    const somenteNumeros = stringValue.replace(/\D/g, '');
                    // Adicionar o prefixo "55"
                    return `55${somenteNumeros}`;
                }
                
                // Regex para telefones (XX-XXXXXXXXX)
                const regex2 = /\b\d{2}-\d{9}\b/;
                if(regex2.test(stringValue)) {
                    if (!stringValue) return '';
                    const somenteNumeros = stringValue.replace(/\D/g, '');
                    // Adicionar o prefixo "55"
                    return `55${somenteNumeros}`;
                }
                
                // Escapar aspas duplas e remover quebras de linha problemáticas
                const cleanValue = stringValue
                    .replace(/"/g, '""')  // Escapar aspas duplas
                    .replace(/\r?\n/g, ' ')  // Substituir quebras de linha por espaços
                    .replace(/\r/g, ' ');    // Remover retornos de carro
                
                return `"${cleanValue}"`;
            } else {
                return stringValue;
            }
        });
        return escapedValues.join(';');
    }
    
    const headers = Object.keys(data[0]).join(';');
    const rows = data.map(objectToCSVRow);
    const csvContent = [headers, ...rows].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.href = url;
    link.setAttribute('download', 'dados.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

    go(num){
         this.btnpesquisa.setAttribute("disabled", "")
     this.btnpesquisa.innerHTML = `<div class="d-flex justify-content-center">
<div class="spinner-border" role="status">
<span class="visually-hidden">Loading...</span>
</div>
</div>`
        this.result = [];
        this.resultados.innerHTML = "";
        this.page = this.page + num;
        this.search.bind(this)(this.page)
        
        if(this.page == 1){
            this.voltar.setAttribute("disabled", "")
        }else{
            console.log(this.voltar);
            this.voltar.removeAttribute("disabled")
        }
        
        document.getElementById("paginacontroler").innerText = `PÃ¡gina ${this.page}`
        
    }
    
    resultado(empresa){
        

    var card = document.createElement("DIV")
    card.classList.add("card")
    var cor = empresa["situacao_cadastral"]["situacao_atual"] == "ATIVA" ? "success" : "danger"
    card.innerHTML = `

            <div class="card-body">
            
                   <div class="d-flex justify-content-between gap-2">
                    <div class="d-flex justify-content-start gap-2">
                    <span class="fw-bold">${empresa["razao_social"]}</span>
                    <span>${this.formatarCNPJ(empresa.cnpj)}</span>
                    </div>
                    <span class="text-${cor} fw-bold">${empresa["situacao_cadastral"]["situacao_atual"]}</span>
                   </div>

               
            </div>
    `
    this.resultados.appendChild(card)
}

    btnCarregando(){
            this.btnpesquisa.setAttribute("disabled", "")
     this.btnpesquisa.innerHTML = `<div class="d-flex justify-content-center">
<div class="spinner-border" role="status">
<span class="visually-hidden">Loading...</span>
</div>
</div>`
this.btnrapido.setAttribute("disabled", "")
     this.btnrapido.innerHTML = `<div class="d-flex justify-content-center">
<div class="spinner-border" role="status">
<span class="visually-hidden">Loading...</span>
</div>
</div>`
    }
    
    pesquisar(){
                 this.fast = false;
         this.result = [];
         this.resultados.innerHTML = "";
 this.btnCarregando.bind(this)()
      this.page = 1;
      document.getElementById("paginacontroler").innerText = `Página ${this.page}`
      this.search.bind(this)(1)

    }
    
    rapido(){
        this.fast = true;
             this.resultados.innerHTML = "";
      this.btnCarregando.bind(this)()
      this.page = 1;
      document.getElementById("paginacontroler").innerText = `Página ${this.page}`
      this.search.bind(this)(1)
    }
    
    gerarIdentificador(nome, cnpj) {
// Remove caracteres especiais e converte para minÃºsculas
let nomeFormatado = nome.toLowerCase().replace(/[^a-zA-Z0-9\s]/g, '');
// Substitui espaÃ§os por hifens
nomeFormatado = nomeFormatado.replace(/\s+/g, '-');
// Retorna o identificador concatenando o nome formatado com o CNPJ
return nomeFormatado + '-' + cnpj;
}

    search(num, deleta = true){
          var respostas = {};
    
    var i = 0;
var respostas = {};

while (i < this.entradas.length) {
var r = this.entradas[i];


if (r.type && r.type === "checkbox") {
    respostas[r.dataset.key] = r.checked;
} else {
    
    try{
        var ob = JSON.parse(r.value);
        
        if (Array.isArray(ob)) {
    var itens = [];
        var v = 0;

        while (v < ob.length) {
       
            if(r.dataset.key == "ativdade" ||  r.dataset.key == "natureza"){
                var valor = ob[v].value.split("-")[0]
            }else{
                  var valor = ob[v].value;
            }
          
            itens.push(valor);
            v++;
        }
            
        respostas[r.dataset.key] = itens;
} else {
     respostas[r.dataset.key] = r.value;
}

        
    }catch(e){
        respostas[r.dataset.key] = r.value;
    }

}

i++;
}

    var info = new FormData();
    info.append("dados", JSON.stringify(respostas))
    info.append("pagina", num)
    info.append("fast", this.fast);
    var request = new XMLHttpRequest();
    request.onload = ()=>{
        var obj = JSON.parse(request.responseText)
        var data = obj.data ?? false;
       
        document.getElementById("pesquisa").classList.remove("d-none")
  
        
        if(obj.total > 0){
            
            if(obj.total < 20){
                this.vai.setAttribute("disabled", "")
            }else{
                 this.vai.removeAttribute("disabled", "")
            }
            
            document.getElementById("total").innerText = ` Encontrado ${obj.total} resultados`
            if(deleta){
                this.resultados.innerHTML = "";
            }
        
        var empresas = obj.cnpjs;

        var i = 0;
        
        
        console.log(obj)
       
        while(i < empresas.length){
            this.resultado.bind(this)(empresas[i]);
          
            
            if(data && data[i].extra){
                console.log(data[i].extra)
            }
          
            this.result.push(empresas[i])
            i++;
        } 
        
  
        
        var maximo = parseInt(document.getElementById("maximo").value ?? 20);

        if(this.result.length <  maximo && empresas.length == 20 && !this.para){
            this.btnPara.classList.remove("d-none")
            var calc = (100 / maximo) * this.result.length;
            document.getElementById("resumao").innerText = `${this.result.length} resultados de ${maximo}`
            document.getElementsByClassName("progress-bar")[0].style.width = `${parseInt(calc)}%`
            this.page++;
             this.search(this.page , false)
        }else{
            this.para = false;
            this.btnPara.classList.add("d-none")
            this.btnpesquisa.removeAttribute("disabled")
            this.btnrapido.removeAttribute("disabled")
            this.btnpesquisa.innerText = "Pesquisa Completa"
            this.btnrapido.innerText = "Pesquisa Rápida"
            document.getElementsByClassName("progress-bar")[0].style.width = `100%`
        }
        
        }else{
            document.getElementById("total").innerText = ` Nenhum Resultado Encontrado`
             this.btnpesquisa.removeAttribute("disabled")
            this.btnrapido.removeAttribute("disabled")
            this.btnpesquisa.innerText = "Pesquisa Completa"
            this.btnrapido.innerText = "Pesquisa Rápida"
            
            
            
        }
       
        
    }
    request.open("POST", `${dominio}/conteudo/modulos/empresas/admins/request.php`)
    request.send(info);
    }
}

function paginaEmpresas(){
    var fluxo = fluxoPage("empresas");
    if(fluxo.length == 0){
        new Empresas();
    }else{
        switch(fluxo[0]){
            case 'busca':
                new Pesquisa();
                break;
            default:
              botaoEmpresasItem();
                new ItemEmpresa();
            break;
        }
    }

    switch(caminho.hash()){
        case 'empresas':
            
            break;
        default:
          
            break;
    }
    
}



