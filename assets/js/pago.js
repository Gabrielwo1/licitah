  class Pago{
        constructor(){
            if(document.getElementById("itemPago")){
                
                this.card = document.getElementById("itemPago");
                 
                this.select = document.getElementById("itemPago").getElementsByClassName("status")
    
                this.variaveis = this.card.getElementsByClassName("variaveis")
                
                this.virtual = this.card.dataset.virtual;
                this.estoque = this.card.dataset.estoque;
                this.recorrente = this.card.dataset.recorrente;
                
                console.log(this.variaveis)
                
                var i  = 0;
                
                while(i < this.select.length){
                        this.select[i].addEventListener("change", this.muda.bind(this))
                    i++
                }
                
            }
        }
        
        
        
        remover(classe){
            var i = 0;
            
            while(i < this.variaveis.length){
                    if(this.variaveis[i].classList.contains(classe)){
                        this.variaveis[i].innerHTML = '';
                    }
                i++
            }
        }
        
        verficaArray(inputouSelect){
        
            var input = document.createElement('DIV')
            
            var i = 0 
            while(i < inputouSelect.length){
                
                var label = document.createElement('LABEL');
                label.classList.add('form-label', 'mt-2', 'text-capitalize')
                label.innerText = inputouSelect[i].nome;
                
                if(inputouSelect[i].valor == true){
                    
                    var select = document.createElement('select');
                    select.classList.add('form-select')
                    select.setAttribute('name', inputouSelect[i].nome)
                    
                    var j = 0
                    
                    while(j < inputouSelect[i].opcoes.length){
                        
                        var opcao = document.createElement('option');
                        
                        opcao.value = isNaN(inputouSelect[i].opcoes[j]) ? inputouSelect[i].opcoes[j].toLowerCase() : parseInt(inputouSelect[i].opcoes[j])
                        
                        opcao.innerText = inputouSelect[i].opcoes[j]
                        
                        select.appendChild(opcao)
                        
                        j++
                    }
                    
                }
                else{
                    var select = document.createElement('input');
                    select.classList.add('form-control', 'mb-2')
                    select.setAttribute('name', inputouSelect[i].nome)
                }
                
                input.appendChild(label)
                input.appendChild(select)
                i++
            
            }
            return input;
            
           
        }
        
        adicionar(classe, inputouSelect = false){
            var nome = classe == 'preco' ? `Preço` : classe;
            
            var label = document.createElement('LABEL');
            label.classList.add('form-label', 'mt-2', 'text-capitalize')
            label.innerText = nome;
            
            if(inputouSelect){
                if(Array.isArray(inputouSelect)){
                    var input = this.verficaArray.bind(this)(inputouSelect)
                }
                else{

                    var input = document.createElement('select');
                    input.classList.add('form-select')
                    input.setAttribute('name', classe)
                }
               
            }
            else{
                var input = document.createElement('input');
                input.classList.add('form-control')
                input.setAttribute('name', classe) 
            }
            
            
            var i = 0;
            
            while(i < this.variaveis.length){
                    if(this.variaveis[i].classList.contains(classe)){
                        
                        if(classe == 'preco' || classe == 'estoque'){
                            this.variaveis[i].appendChild(label)
                        }
                        this.variaveis[i].appendChild(input)
                        
                        
                    }
                i++
            }
        }


        
        muda(){
            if(parseInt(event.target.value) == 0){
                
                switch(event.target.dataset.classe){
                    case 'preco':
                        this.remover.bind(this)(event.target.dataset.classe)
                        break;
                    case 'virtual':
                        this.remover.bind(this)(event.target.dataset.classe)
                        break;
                    case 'recorrente':
                        this.remover.bind(this)(event.target.dataset.classe)
                        break;
                    case 'estoque':
                        this.remover.bind(this)(event.target.dataset.classe)
                        break;
                    default:
                        break;
                }
                
                
                
            }else{
                
                switch(event.target.dataset.classe){
                    case 'preco':
                        this.adicionar.bind(this)(event.target.dataset.classe)
                        break;
                    case 'virtual':
                        this.adicionar.bind(this)(event.target.dataset.classe, [{valor:false, nome:'altura'}, {valor:false, nome:'peso'}, {valor:false, nome:'profundidade'}, {valor:false, nome:'largura'}])
                        break;
                    case 'recorrente':
                        var i = 0
                        var array = [];
                        
                        while ( i < 24){
                            array.push(i + 1)
                            i ++
                        }
                        
                        this.adicionar.bind(this)(event.target.dataset.classe, 
                            [
                                {
                                    valor:true, 
                                    nome:'cobrança', 
                                    opcoes:[
                                        'Semanal', 
                                        'Mensal', 
                                        'Trimestral', 
                                        'Anual'
                                        ]
                                }, 
                                {
                                    valor:true,
                                    nome:'ciclo',
                                    opcoes: array
                                    
                                }
                            ]
                            )
                        break;
                    case 'estoque':
                        this.adicionar.bind(this)(event.target.dataset.classe)
                        break;
                    default:
                        break;
                }
                
            }
        }
        
    }
