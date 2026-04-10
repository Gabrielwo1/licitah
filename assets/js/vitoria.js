
// Função de comparação para ordenar os times
function compararTimes(a, b) {
  // Primeiro, compare os pontos (em ordem decrescente)
  if (parseInt(a.p) > parseInt(b.p)) {
    return -1;
  } else if (parseInt(a.p) < parseInt(b.p)) {
    return 1;
  }

  // Em caso de empate nos pontos, compare as vitórias (em ordem decrescente)
  if (parseInt(a.v) > parseInt(b.v)) {
    return -1;
  } else if (parseInt(a.v) < parseInt(b.v)) {
    return 1;
  }

  // Em caso de empate nas vitórias, compare os empates (em ordem decrescente)
  if (parseInt(a.e) > parseInt(b.e)) {
    return -1;
  } else if (parseInt(a.e) < parseInt(b.e)) {
    return 1;
  }
  
  
   if (parseInt(a.gols) > parseInt(b.gols)) {
    return -1;
  } else if (parseInt(a.gols) < parseInt(b.gols)) {
    return 1;
  }

  // Em caso de empate nos gols marcados, compare os gols sofridos (em ordem crescente)
  if (parseInt(a.levados) < parseInt(b.levados)) {
    return -1;
  } else if (parseInt(a.levados) > parseInt(b.levados)) {
    return 1;
  }

  // Se todos os critérios forem iguais, mantenha a ordem original
  return 0;
}



class TabelaBrasileirao {
  constructor() {
      this.formatarData = this.formatarData.bind(this)
      this.diferencaTempo = this.diferencaTempo.bind(this)
      this.temporizador = this.temporizador.bind(this)
      this.tabulacao = this.tabulacao.bind(this)
        
    var url =  document.getElementById("tabRodadas") ?  `${dominioAdress}/apis/live/rodadas.php`:  `${dominioAdress}/apis/live/partidas.php`
        
        
      
    this.tabela = this.tabela.bind(this);
    const xhttp = new XMLHttpRequest();
    xhttp.onload = () => { // Utilize uma arrow function aqui
      this.tabela(xhttp.responseText); // Utilize a variável xhttp criada fora do escopo da arrow function
    };
    xhttp.open("GET", url);
    xhttp.send();
  }

  tabela(r) {
 
    var res = JSON.parse(r)
    
    

    var obj = res.times.sort(compararTimes); 

    if(document.getElementById("tabelaBrasileirao") ){
          var i =0;
    while(i < obj.length){
   
        var time = obj[i]
         var display = time.nome == "Vitória" ? true : false;
         var tabela = document.getElementById("tabelaBrasileirao") 

         var tr = document.createElement("TR")
         if(display){
             tr.classList.add("bg-danger", "text-light")
         }
         
         tr.innerHTML = `
         <td class="fs-12"><span class="mx-2 corpo">${i + 1}</span><img src="${time.imagem}" style="width:20px"><span class="ms-2 corpo">${time.nome}</span></td>
        <td  class="fs-10 text-center">${time.p}</td>
        <td  class="fs-10 text-center">${time.j}</td>
        <td class="fs-10 text-center">${time.v}</td>
        <td  class="fs-10 text-center">${time.e}</td>
        <td  class="fs-10 text-center">${time.d}</td>
         `
         tabela.appendChild(tr)
        i++;
        
    }
    }
  
    
    
    var partidas = res.partidas
    if(document.getElementById("ultimasPartidas")){
        
    
    var u = [];
    u.push(partidas.ultima);
    u.push(partidas.penultima);
    u.push(partidas.triultima);
      this.ultimas = this.ultimas.bind(this)
      this.ultimas(u)  
    }
    
    if(document.getElementById("proximaPartida")){
         this.proximo = this.proximo.bind(this)
         this.proximo(partidas.proxima)
    }
    
    if(document.getElementById("tabRodadas")){
        this.tabulacao(res)
    }
    
   
  }
  
  tabulacao(r){
      var mapa = [];
      var i  = 0;
      while(i < r.times.length){
          mapa[r.times[i].id] = r.times[i]
          i++;
      }
      
   
      
      
  
   
   var rodadas = r.rodadas
   var tab = document.getElementById("tabRodadas")
   var i = 0;
   var contador = 0;
   var div = document.createElement("DIV")
   var card = document.createElement("DIV")
   card.classList.add("text-contrast", "text-center", "py-4", "fs-18", "fw-700")
   card.innerHTML = `1ª  RODADA`;
   var conts = 1;
   div.appendChild(card)
    var ultimo = false;
        var prox = 0;
   while(i < rodadas.length){
    
       var ro = rodadas[i];

       var mandante = mapa[ro.rodada_mandante]
       var visitante = mapa[ro.rodada_visitante]
        var golM = ro.rodada_placarMandante
       var golV = ro.rodada_placarVisitante
        const dataFornecida = new Date(ro.rodada_data);
        const dataAtual = new Date();
        const diferenca = dataFornecida - dataAtual;
       
        if (diferenca > 0) {
     
            if(!ultimo){
                prox = i
                       var ultimo = true;
            }
             var golM = ""
             var golV = ""
        }
       
      
        
        
       
       
       var data = this.formatarData(ro.rodada_data)
 
       contador ++;
       var card = document.createElement("DIV")
      card.classList.add("card", "mb-3")
      card.innerHTML = `
  
        <div class="card-body">
            <div class="text-center fs-14 text-contrast fw-500 mb-2 text-uppercase"> ${data} | <span class="fw-700">${ro.rodada_sede}</span></div> 
            <div class="d-flex justify-content-between align-itens-center"> 
                <div class="d-flex justify-content-start gap-3 align-items-center"> 
                    <div class="fs-18 text-contrast fw-500">${mandante.sigla}</div> 
                    <img style="width:40px" src="${mandante.imagem}"> 
                    <span class="fs-25 fw-700">${golM}</span>
                </div> 
                <div> 
                    <div class="text-center text-contrast"> x </div>
                </div>
                <div class="d-flex justify-content-start gap-3 align-items-center">
                    <span class="fs-25 fw-700">${golV}</span>
                    <img style="width:40px" src="${visitante.imagem}">
                    <div class="fs-18 text-contrast fw-500">${visitante.sigla}</div> 
                </div>
            </div>
        </div>`
        
        if(contador == 10){
            div.appendChild(card)
            tab.appendChild(div)
            var div = document.createElement("DIV")
               var card = document.createElement("DIV")
                card.classList.add("text-contrast", "text-center", "py-4", "fs-18", "fw-700")
                 conts++;
                 card.innerHTML = `${conts}ª RODADA`;
                 div.appendChild(card)
                 contador = 0;
        }else{
            div.appendChild(card)
        }
        
       
       
       
       i++;
   }
   

      var slide = new Glider(document.querySelector('.listaprodutos'), {
        slidesToShow: 1,
        dots: '.pontos',
        draggable: true,
        
        arrows: {
            prev: '.anterior',
            next: '.proximo'
        },
        
        responsive: [{
            // screens greater than >= 775px
            breakpoint: 775,
            settings: {
                // Set to `auto` and provide item width to adjust to viewport
                slidesToShow: 1,
                slidesToScroll: 1,
                itemWidth: 150,
                duration: 2
            }
        }, {

            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                itemWidth: 150,
                duration: 2
            }
        }]
    });
   const slideIndexToStart = Math.floor(prox / 10);
slide.scrollItem(slideIndexToStart);

  }
  
  formatarData(dataString) {
  const meses = [
    "janeiro", "fevereiro", "março", "abril",
    "maio", "junho", "julho", "agosto",
    "setembro", "outubro", "novembro", "dezembro"
  ];

  const dataObj = new Date(dataString);
  const dia = dataObj.getDate();
  const mes = meses[dataObj.getMonth()];
  const ano = dataObj.getFullYear();
  const hora = dataObj.getHours();
  const minutos = dataObj.getMinutes();

  const dataFormatada = `${dia} de ${mes} de ${ano} às ${hora}:${minutos.toString().padStart(2, '0')}`;
  return dataFormatada;
}

  ultimas(u){
      
      var i =0;
      while(i < u.length){
          
          var rodada = u[i]
                      
                            var timeA = rodada.mandante.nome;
                            var imagenA = rodada.mandante.logo;
                            var golA = rodada.rodada_placarMandante;
                            var timeB = rodada.visitante.nome;
                            var imagenB = rodada.visitante.logo;
                            var golB = rodada.rodada_placarVisitante;
                            var data = this.formatarData(rodada.rodada_data)
                            var sede = rodada.rodada_sede
                            
                            
                            var div = document.createElement("DIV")
                            div.classList.add("position-relative","mt-3","mt-md-0")
                            div.innerHTML = `
 
                            <div class="card">
                                
                                <div class="card-body">
                          
                                <div class="nmw-wrap">
                                    <div class="d-flex justify-content-center  align-items-center">
                                        <div class="text-center">
                                            <img src="${imagenA}" alt="" style="width:60px">
                                            <strong class="d-block text-center mt-2">${timeA}</strong><div class="fs-30">${golA}</div> </div>
                                                <div>
                                                    <strong class="bg-dark mx-4 py-2 px-3 bg-vermelho rounded-pill text-light">VS</strong>
                                                </div>
                              
                         
                                                <div class="text-center">
                                                    <img src="${imagenB}" alt="" style="width:60px"> 
                                                    <strong class="d-block text-center mt-2">${timeB}</strong> 
                                                    <div class="fs-30">${golB}</div>
                                                </div>
                                        </div>
                                        <div>
                                            <div class="text-center text-vermelho corpo fw-900">BRASILEIRÃO SÉRIE B</div>
                                            <div class="text-center corpo text-uppercase fs-14">${data}</div>
                                            <div class="text-center text-contrast fs-16 fw-700">${sede}</div>
                          
                                        </div>
                        
                       
                                    </div>
                                </div>
                                 
                            </div>
                            
                        `;
                        document.getElementById("ultimasPartidas").appendChild(div)
                        i++;
                        }
  }
  
  diferencaTempo(dataString) {
  const dataFornecida = new Date(dataString);
  const dataAtual = new Date();

  const diferenca = dataFornecida - dataAtual;
  if (diferenca < 0) {
    return [0, 0, 0, 0];
  }

  const umDiaEmMS = 24 * 60 * 60 * 1000;
  const dias = Math.floor(diferenca / umDiaEmMS);
  const horas = Math.floor((diferenca % umDiaEmMS) / (60 * 60 * 1000));
  const minutos = Math.floor((diferenca % (60 * 60 * 1000)) / (60 * 1000));
  const segundos = Math.floor((diferenca % (60 * 1000)) / 1000);

  return [dias, horas, minutos, segundos];
}

  proximo(r){
      
      
      var html = '';
      var i = 0;
      
      while(i < 4){
          html = `${html}<div class="w-25 row m-0 p-0">
                                <div class="col-6 p-1">
                                    <div class="card position-relative rounded-0 p-0" style="background-color:#000">
                                        <div class="card-body d-flex position-relative he-lg-50" >
                                            <div class="text-center position-absolute top-50 start-50 translate-middle text-light fs-12 fs-lg-18 fw-700 blocoTimer">0</div>
                                        </div>
                                        <div class="position-absolute w-100 h-50 top-0 start-0" style="background-color: rgba(255, 255, 255, 0.2); ">
                                           
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-6 p-1">
                                    <div class="card position-relative rounded-0 p-0" style="background-color:#000">
                                        <div class="card-body d-flex position-relative he-lg-50">
                                            <div class="text-center position-absolute top-50 start-50 translate-middle text-light fs-12 fs-lg-18 fw-700 blocoTimer">0</div>
                                        </div>
                                        <div class="position-absolute w-100 h-50 top-0 start-0" style="background-color: rgba(255, 255, 255, 0.2);">
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `
                            
            if(i < 3){
                html = `${html}<div style="width: 6px; height: 2px; background-color: #fff;"></div>`
            }
            i++;
      }
      
     var mandanteNome = r.mandante.nome
     var mandanteLogo = r.mandante.logo
     
     var visitanteNome = r.visitante.nome
     var visitanteLogo = r.visitante.logo
     
     var sede = r.rodada_sede
     var data = this.formatarData(r.rodada_data);
     
     this.timer = r.rodada_data
      var div = `
                 <div>
                        <h2 class="text-contrast text-uppercase text-center fs-20 fw-700">Próximo Jogo</h2>
                        <div class="w-30 bg-danger m-auto" style="height: 3px">
                            
                        </div>
                    </div>
      <div class="px-4">
                        <div class="d-flex justify-content-center align-items-center gap-5">
                        <div>
                            <div class="text-center"><img src="${mandanteLogo}" style="max-width: 80px"></div>
                            <div><h4 class="mt-2 fs-16 fw-700 text-uppercase text-center">${mandanteNome}</h4></div>
                        </div>
                        <div>VS</div>
                        <div>
                            <div>
                            <div class="text-center"><img src="${visitanteLogo}" style="max-width: 80px"></div>
                            <div><h4 class="mt-2 fs-16 fw-700 text-uppercase text-center">${visitanteNome}</h4></div>
                        </div>
                            
                        </div>
                    </div>
                    </div>
                    <div>
                        <p class="text-center fw-700 fs-20 text-uppercase my-2">${sede}</p>
                        <h3 class="text-center fs-18 fw-500 text-uppercase">${data}</h3>
                        <div class="d-flex justify-content-between align-items-center m-auto" style="max-width: 400px;">
                            ${html}
                        </div>
                        
                        <div class="row m-auto my-3" style="max-width: 400px;">
                            <div class="col-3 text-center fw-700 fs-9 fs-lg-12">DIAS</div>
                            <div class="col-3 text-center fw-700 fs-9 fs-lg-12">HORAS</div>
                            <div class="col-3 text-center fw-700 fs-9 fs-lg-12">MINUTOS</div>
                            <div class="col-3 text-center fw-700 fs-9 fs-lg-12">SEGUNDOS</div>
                        </div>
                        
                    </div>
                    <div class="text-center">
                        <a class="btn btn-danger  btn-sm m-auto text-uppercase fw-700 fs-18 rounded-0 px-4" href="https://www.futebolcard.com/">Comprar Ingresso</a>
                    </div>
      `
      document.getElementById("proximaPartida").innerHTML = div
      
      setInterval(this.temporizador, 1000);
      
      
      
  }
  
  temporizador(){
      
      if(document.getElementsByClassName("blocoTimer").length > 0){
               var blocos = document.getElementsByClassName("blocoTimer")
      
      var timer = this.diferencaTempo(this.timer);
      var dias = timer[0] < 10 ? `0${timer[0].toString()}` : timer[0].toString();
      var horas = timer[1] < 10 ? `0${timer[1].toString()}` : timer[1].toString();
      var minutos = timer[2] < 10 ? `0${timer[2].toString()}` : timer[2].toString();
      var segundos = timer[3] < 10 ? `0${timer[0].toString()}` : timer[3].toString();

      blocos[0].innerText = dias[0]
      blocos[1].innerText = dias[1]
      blocos[2].innerText = horas[0]
      blocos[3].innerText = horas[1]
      blocos[4].innerText = minutos[0]
      blocos[5].innerText = minutos[1]
      blocos[6].innerText = segundos[0]
      blocos[7].innerText = segundos[1]
          
      }
 
      
    
      
  }
}