class PageCalendario{
    constructor(estrutura, container = false){
        this.estrutura = estrutura;
         
        this.container = !container ? document.getElementById("conteudo") : container
        
        this.container.innerHTML = "";
        
        new HeaderPage(estrutura);
        
        this.id = estrutura.id;
        this.master = estrutura.master
        this.modulo = estrutura.modulo
        
        this.vizu = "mes"
        this.modeView = "Mês"
        
        
        this.init.bind(this)();
    }
    
    filtra(){
        this.body.classList.toggle("ativo")
    }
    
    init(){
        var container = document.createElement("DIV")
        container.classList.add("container")
        
        var card = document.createElement("DIV")
        card.classList.add("card", 'card-nown', "opacity-0", "overflow-hidden")
        container.appendChild(card)
        
        var header = document.createElement("DIV")
        header.classList.add("card-header")
        card.appendChild(header)
    
    
        let cabecalho = document.createElement('div');
        cabecalho.className = 'd-flex justify-content-between';
        
        let grupoEsquerda = document.createElement('div');
        grupoEsquerda.className = 'd-flex justify-content-start gap-2';
        
        let botaoFiltro = document.createElement('button');
        botaoFiltro.className = 'btn btn-n-primaria wi-50 he-50 d-flex align-items-center justify-content-center';
        botaoFiltro.innerHTML = '<i class="bi bi-funnel-fill"></i>';
        grupoEsquerda.appendChild(botaoFiltro);
        evento(botaoFiltro, "click", this.filtra.bind(this))
        
        let divBusca = document.createElement('div');
        divBusca.className = 'position-relative';
        
        let campoBusca = document.createElement('input');
        campoBusca.className = 'form-control he-50';
        campoBusca.placeholder = 'Procurar ...';
        campoBusca.style.paddingLeft = '35px';
        divBusca.appendChild(campoBusca);
        
        let iconeBusca = document.createElement('span');
        iconeBusca.className = 'position-absolute top-50 translate-middle-y';
        iconeBusca.style.left = '15px';
        iconeBusca.innerHTML = '<i class="bi bi-search"></i>';
        divBusca.appendChild(iconeBusca);
        
        grupoEsquerda.appendChild(divBusca);
        cabecalho.appendChild(grupoEsquerda);
        
        let grupoDireita = document.createElement('div');
        grupoDireita.className = 'd-flex gap-2';
        
        let botaoCalendario = document.createElement('button');
        botaoCalendario.className = 'btn btn-nown-style wi-50 he-50 btn-n-primaria d-flex align-items-center justify-content-center';
        botaoCalendario.id = 'modoCalendario';
        botaoCalendario.innerHTML = '<i class="bi bi-calendar"></i>';
        this.modoCalendario = botaoCalendario
        evento(botaoCalendario, "click", this.modoC.bind(this))
        grupoDireita.appendChild(botaoCalendario);
        
        let botaoLista = document.createElement('button');
        botaoLista.className = 'btn btn-n-secundaria wi-50 he-50 btn-n-primaria d-flex align-items-center justify-content-center';
        botaoLista.id = 'modoLista';
        botaoLista.innerHTML = '<i class="bi bi-list"></i>';
        this.modoLista = botaoLista;
        evento(botaoLista, "click", this.modoL.bind(this))
        grupoDireita.appendChild(botaoLista);
        
        let grupoModo = document.createElement('div');
        grupoModo.className = 'btn-group';
        grupoModo.setAttribute('role', 'group');
        
        let botaoModo = document.createElement('button');
        botaoModo.className = 'btn btn-n-primaria dropdown-toggle';
        botaoModo.setAttribute('data-bs-toggle', 'dropdown');
        botaoModo.setAttribute('aria-expanded', 'false');
        botaoModo.id = 'timeMode';
        botaoModo.textContent = 'Mês';
        
        let listaModo = document.createElement('ul');
        listaModo.className = 'dropdown-menu';
        
        let opcoes = [
    { foco: 'Dia', mode: 'dayGridWeek', oculto: false },
    { foco: 'Semana', mode: 'timeGridWeek', oculto: false },
    { foco: 'Mês', mode: 'dayGridMonth', oculto: true },
    { foco: 'Ano', mode: 'multiMonthYear', oculto: false }
];

        opcoes.forEach(opcao => {
    let item = document.createElement('li');
    let botaoItem = document.createElement('button');
    evento(botaoItem, "click", this.modo.bind(this))
    botaoItem.className = 'dropdown-item viewMode' + (opcao.oculto ? ' d-none' : '');
    botaoItem.setAttribute('data-foco', opcao.foco);
    botaoItem.setAttribute('data-mode', opcao.mode);
    botaoItem.textContent = opcao.foco;
    item.appendChild(botaoItem);
    listaModo.appendChild(item);
});

        grupoModo.appendChild(botaoModo);
        grupoModo.appendChild(listaModo);
        grupoDireita.appendChild(grupoModo);
        
        cabecalho.appendChild(grupoDireita);
        header.appendChild(cabecalho)
        
        var calendario = document.createElement("DIV")
        
        var body = document.createElement("DIV")
        body.classList.add("card-body", "body-calendar", "position-relative", "overflow-visible")
        this.body = body;
        
        this.calendariozinho = document.createElement("DIV")
        this.calendariozinho.classList.add("px-3", "position-absolute", "wi-300", "h-100", "filtro", "top-0", "py-4")
        this.calendariozinho.style.left = "-300px"
        
        this.small = document.createElement("DIV")
        this.small.classList.add("d-flex", "justify-content-center")
        this.calendariozinho.appendChild(this.small)
        body.appendChild(this.calendariozinho)
        
        
        body.appendChild(calendario)
        
        

        
        card.appendChild(body)

        this.container.appendChild(container)
        nownFiles.add([`/assets/aplicativo/calendario/index.js`, `https://cdnjs.cloudflare.com/ajax/libs/datedreamer/0.2.1/datedreamer.min.js`]).then(()=>{
 
        this.calendar = new FullCalendar.Calendar(calendario, {
          initialView: 'dayGridMonth',
           dayMaxEventRows: true, 
        moreLinkText: function(num) {
          return `+ ${num}`;
        },
          locale: 'pt-br',
          themeSystem: 'bootstrap5',
           buttonText: {
      today: 'Hoje' 
    },
          datesSet: () =>{
              card.classList.remove("opacity-0")
          },
          eventContent: (arg)=> {
     

            
            let title = arg.event.title
            
            var div = document.createElement("DIV")
            var h2 = document.createElement("span")
            h2.classList.add("fs-14","fw-500", "m-0")
            h2.innerText = title
      
            var esquerda = document.createElement("DIV")
     
            esquerda.classList.add("d-flex", "gap-2")
            
          
            var span = document.createElement("SPAN")
            span.innerText = arg.timeText
            span.classList.add("fs-12")
                
            
            
            var primeira = document.createElement("DIV")
            primeira.classList.add("d-flex", "justify-content-between")
            primeira.appendChild(esquerda)
            primeira.appendChild(span)
            
            var src = false;
            if(arg.event.extendedProps.img){
                src = trataImagem(arg.event.extendedProps.img, "mini");
            }
             if (src) {
                
                let img = document.createElement('DIV');
                img.style = `background-image: url(${src}); background-size: cover`;
                img.classList.add("img-thumbnail", "wi-20", "he-20")

                
                esquerda.appendChild(img)
     
            }
            
            esquerda.appendChild(h2)
            

             
            div.appendChild(primeira)
            div.classList.add("w-100", "p-1")
            
            
            var img = trataImagem(arg.event.extendedProps.img, "mini");

           
            return { domNodes: [div]};
        },
          eventClick: (info)=>{
               info.jsEvent.preventDefault();
                    var config = this.config

                    if(config.linkar > 0){
                        var url = `${config.link}${info.event.url}`
                        goUrl(url);
                    }

             
  
          },
           dayMaxEventRows: 5, 
  views: {
    dayGridMonth: {
      dayMaxEventRows: 5
    }
  }
          
        });
        this.calendar.setOption('locale', 'pt-br');
        this.calendar.render();
        
        
         new datedreamer.calendar({
                element: this.small, 
                theme: "lite-purple",
                 onChange: (e) => {
                     this.calendar.gotoDate(e.detail)

                 }
            })
        
        let request = new Request(`${dominio}/admin/brain.php`);
        request.addData({
            "tipo": "infoCalendar",
            "identificador": this.id,
            "master" : this.master,
            "modulo" :  this.modulo
        })
        request.send().then((r)=>{
            this.config = r.config
            console.log(r)
             this.calendar.addEventSource(r.lista)

        }, (r)=>{
            console.log(r)
        })
            
            
        }, ()=>{
            
        })
        

        
        
    }
    
        modo(){
        var elementsArray = Array.from(document.getElementsByClassName("viewMode"));


elementsArray.forEach(function(element) {
  element.classList.remove("d-none");
});

       
         event.currentTarget.classList.add("d-none")
         document.getElementById("timeMode").innerText = event.currentTarget.dataset.foco
         this.modeView = event.currentTarget.dataset.foco
         
         if(this.vizu == "mes"){
                  switch(this.modeView){
            case 'Dia':
                    this.calendar.changeView("dayGridWeek");
                break;
            case 'Semana':
                    this.calendar.changeView("timeGridWeek");
                break;
            case 'Mês':
                    this.calendar.changeView("dayGridMonth");
                break;
            case 'Ano':
                    this.calendar.changeView("multiMonthYear");
                break;
                  }
             
             
         }else{
               switch(this.modeView){
            case 'Dia':
                    this.calendar.changeView("listDay");
                break;
            case 'Semana':
                    this.calendar.changeView("listWeek");
                break;
            case 'Mês':
                    this.calendar.changeView("listMonth");
                break;
            case 'Ano':
                    this.calendar.changeView("listYear");
                break;
        }
         }
     
     
    }
    
      modoC(){
        if(true){
            this.vizu = "mes"
            
            this.modoCalendario.classList.add("btn-n-primaria")
            this.modoCalendario.classList.remove("btn-n-secundaria")
            
            this.modoLista.classList.add("btn-n-secundaria")
            this.modoLista.classList.remove("btn-n-primaria")
        
            switch(this.modeView){
            case 'Dia':
                    this.calendar.changeView("dayGridWeek");
                break;
            case 'Semana':
                    this.calendar.changeView("timeGridWeek");
                break;
            case 'Mês':
                    this.calendar.changeView("dayGridMonth");
                break;
            case 'Ano':
                    this.calendar.changeView("multiMonthYear");
                break;
                  }
        
        }
        
    }
    
    modoL(){

         if(true){
             
            
             this.vizu = "lista"
             this.modoCalendario.classList.remove("btn-n-primaria")
             this.modoCalendario.classList.add("btn-n-secundaria")
             
             this.modoLista.classList.remove("btn-n-secundaria")
             this.modoLista.classList.add("btn-n-primaria")
        
        switch(this.modeView){
            case 'Dia':
                    this.calendar.changeView("listDay");
                break;
            case 'Semana':
                    this.calendar.changeView("listWeek");
                break;
            case 'Mês':
                    this.calendar.changeView("listMonth");
                break;
            case 'Ano':
                    this.calendar.changeView("listYear");
                break;
        }

     
         }
         
    }
}