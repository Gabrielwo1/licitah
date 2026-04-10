nownFiles.add(["https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js", `https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js`,"https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"]).then(() => {
     $('#cpf').mask('000.000.000-00', {
         reverse: true
     });
     $('#cnpj').mask('00.000.000/0000-00', {
         reverse: true
     });
     $('#rg').mask('00.000.000');
     $('#orgaoExp').mask('AAA');
     $('#cpf2').mask('000.000.000-00', {
         reverse: true
     });
     $('#cnpj2').mask('00.000.000/0000-00', {
         reverse: true
     });
     $('#rg2').mask('00.000.000');
     $('#orgaoExp2').mask('AAA');
     $('#data').mask('00/00/0000');
 });

function paginaDocumentacoes() {
     evento(document.getElementById('document-select'), 'change', changeDocument)
    
     evento(document.getElementById('botaoExportar'), 'click', validaExportacao)
 }

function validaExportacao(){
    var inputs = Array.from(document.getElementsByClassName('inputs-container')[0].getElementsByTagName('input')).filter(input => {
        var pai = input.closest('.col-12')
        return pai && !pai.classList.contains('d-none')
    });
    
    var i = 0 
    var fica = false
    
    while(i < inputs.length){
        if(inputs[i].value.trim() === ''){
            inputs[i].classList.add('is-invalid')
            fica = true
        }else{
            inputs[i].classList.remove('is-invalid')
        }
        i++
    }
    
    if(!fica){
        baixarPDF()  
    }
    
 }

function baixarPDF(){
    var botao = document.getElementById('botaoExportar')
    botao.setAttribute('disabled', true)
    botao.innerHTML = ` <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                      `
        
    var resposta = geraPDF(document.getElementsByClassName('card-document')[0]);
    
    resposta.then((r)=>{
          botao.innerHTML = 'EXPORTAR DOCUMENTO <i class=" ms-2 bi bi-arrow-down-circle"></i>'
          botao.removeAttribute('disabled')
    })
 }

function updateDocument() {
     document.getElementById('doc-razaoSocial').innerText = document.getElementById('razaoSocial').value || '___________';
     document.getElementById('doc-cnpj').innerText = document.getElementById('cnpj').value || '___________';
     document.getElementById('doc-ref').innerText = document.getElementById('ref').value || '___________';
     document.getElementById('doc-responsavel').innerText = document.getElementById('responsavel').value || '___________';
     document.getElementById('doc-responsavel2').innerText = document.getElementById('responsavel2').value || '___________';
     document.getElementById('doc-rg').innerText = document.getElementById('rg').value || '___________';
     document.getElementById('doc-rg2').innerText = document.getElementById('rg2').value || '___________';
     document.getElementById('doc-orgaoExp').innerText = document.getElementById('orgaoExp').value || '___________';
     document.getElementById('doc-orgaoExp2').innerText = document.getElementById('orgaoExp2').value || '___________';
     document.getElementById('doc-cpf').innerText = document.getElementById('cpf').value || '___________';
     document.getElementById('doc-cpf2').innerText = document.getElementById('cpf2').value || '___________';
     document.getElementById('doc-data').innerText = document.getElementById('data').value || '__/__/____';
 }

function updateInput() {

 }

function funcoesModelos(valor) {
     switch (parseInt(valor)) {
         case 1:
             var documento = ` <div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                 <span class="text-light">Modelo Impresso: Carta de Credenciamento</span>
              </div>
              <div class="card-document gap-4">
                 <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                    Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                    a inabilitação do licitante.
                    </span>
                 </div>
                 <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Carta de Credenciamento</span>
                 </div>
                 <div>
                    <div class="d-flex align-items-center">
                        <p class="m-0"><strong>Ref: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-ref"> _________</span>                      
                    </div>
                    <div class="document-content">
                       <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº <span class="fs-16 fw-500" id="doc-cnpj">___________</span> , por intermédio de seu representante legal, Sr(a). <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº
                       <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" class="fs-16 fw-500" id="doc-cpf">___________</span>, pela presente CREDENCIA o Sr.<span class="fs-16 fw-500" id="doc-responsavel2">___________</span>, portador da carteira de identidade nº<span class="fs-16 fw-500" id="doc-rg2">___________</span>, Órgão Expedidor <span class="fs-16 fw-500" id="doc-orgaoExp2">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf2">___________</span>, para representá-la na Licitação em epígrafe supra mencionada, 
                       outorgando-lhe poderes para concorrer, desistir, renunciar, transigir, firmar recibos, 
                       assinar Atas e outros documentos, acompanhar todo o processo Licitatório até o seu final, 
                       tomar ciência de outras propostas, podendo para tanto, praticar todos os atos necessários 
                       para o bom e fiel cumprimento deste mandato.
                       <div class="d-flex align-items-center">
                           <p class="mt-3"><strong>Data: </strong></p>
                           <span class="fs-16 fw-500 ms-2" id="doc-data"> __/__/____</span>                      
                       </div>
                       <div class="d-flex flex-column align-items-center justify-content-center">
                           <p>_____________________________________</p>
                           <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                       </div>
                    </div>
                 </div>
            </div>`
             break;
         case 2:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
              <span class="text-light">Modelo Impresso: Declaração de Elaboração Independente de Proposta</span>
            </div>
            
            <div class="card-document gap-4">
              <div>
                <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                <span class="fs-12 fw-500">
                  Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                  a inabilitação do licitante.
                </span>
              </div>
              
              <div class="d-flex justify-content-center">
                <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Elaboração Independente de Proposta</span>
              </div>
              
              <div>
                <div class="d-flex align-items-center">
                  <p class="m-0"><strong>Ref: </strong></p>
                  <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                </div>
            
                <div class="document-content">
                  <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                  <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                  <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                  <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                  <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                  DECLARA, sob as penas da lei, em especial o art.299 do Código Penal Brasileiro que:
                  
                  <!-- Corrigido o atributo style -->
                  <ul class="p-0 my-3 d-flex flex-column gap-3" style="list-style-type: none;">
                    <li>
                      I. a proposta apresentada para participar da licitação em epígrafe foi elaborada de maneira independente pelo Licitante, e o conteúdo da proposta não foi, no todo ou em parte, direta ou indiretamente, informado, discutido ou recebido de qualquer outro participante potencial ou de fato da licitação em epígrafe, por qualquer meio ou por qualquer pessoa;
                    </li>
                    <li>
                      II. a intenção de apresentar a proposta elaborada para participar da licitação em epígrafe não foi informada, discutida ou recebida de qualquer outro participante potencial ou de fato da licitação em epígrafe, por qualquer meio ou por qualquer pessoa;
                    </li>
                    <li>
                      III. que não tentou, por qualquer meio ou por qualquer pessoa, influir na decisão de qualquer outro participante potencial ou de fato da licitação em epígrafe quanto a participar ou não da referida licitação;
                    </li>
                    <li>
                      IV. que o conteúdo da proposta apresentada para participar da licitação em epígrafe não será, no todo ou em parte, direta ou indiretamente, comunicado ou discutido com qualquer outro participante potencial ou de fato da licitação em epígrafe antes da adjudicação do objeto da referida licitação;
                    </li>
                    <li>
                      V. que o conteúdo da proposta apresentada para participar da licitação em epígrafe não foi, no todo ou em parte, direta ou indiretamente, informado, discutido ou recebido de qualquer integrante deste órgão antes da abertura oficial das propostas; e
                    </li>
                    <li>
                      VI. que está plenamente ciente do teor e da extensão desta declaração e que detém plenos poderes e informações para firmá-la.
                    </li>
                  </ul>
                  
                  <div class="d-flex align-items-center">
                    <p class="mt-3"><strong>Data: </strong></p>
                    <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                  </div>
            
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <p>_____________________________________</p>
                    <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                  </div>
                </div>
              </div>
            </div>
            <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 3:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
              <span class="text-light">Modelo Impresso: Declaração de Inexistência de Fatos Impeditivos</span>
            </div>
            
            <div class="card-document gap-4">
              <div>
                <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                <span class="fs-12 fw-500">
                  Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                  a inabilitação do licitante.
                </span>
              </div>
              
              <div class="d-flex justify-content-center">
                <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Inexistência de Fatos Impeditivos</span>
              </div>
              
              <div>
                <div class="d-flex align-items-center">
                  <p class="m-0"><strong>Ref: </strong></p>
                  <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                </div>
            
                <div class="document-content">
                  <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                  <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                  <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                  <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                  <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                  DECLARA, sob as penas da lei, que até a presente data inexistem fatos impeditivos para a habilitaçãono presente processo licitatório, ciente de obrigatoriedade de declarar ocorrências posteriores.
                  
                  <div class="d-flex align-items-center">
                    <p class="mt-3"><strong>Data: </strong></p>
                    <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                  </div>
            
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <p>_____________________________________</p>
                    <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                  </div>
                </div>
              </div>
            </div>
            <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 4:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração de não Trabalho Forçado e Degradante</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de não Trabalho Forçado e Degradante</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, que não possui em sua carteira produtiva, empregados executando trabalho degradante ou forçado, observando o disposto incisos III e IV do art.1º e no inc.III do art.5º da Constituição Federal.
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 5:
             var documento =  `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração de Renúncia de Vistoria</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Renúncia de Vistoria</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, sob as penas da lei que, optamos pela não realização de vistoria assumindo inteiramente a responsabilidade ou consequências por essa omissão, mantendo as garantias que vincularem nossa proposta ao presente processo licitatório.
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 6:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração de Habilitação</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Habilitação</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, que está ciente e concorda com as condições contidas no edital e seus anexos, bem como de que cumpre plenamente os requisitos de habilitação definidos no edital em epígrafe.
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 7:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração de Micro Empresa ou Empresa de Pequeno Porte</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Micro Empresa ou Empresa de Pequeno Porte</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, sob a as penas da lei que não ultrapassou o limite de faturamento e cumpre os requisitos estabelecidos no art.3º da Lei Complementar nº123, de 14 de dezembro de 2006, sendo apta a usufruiro tratamento  favorecido estabelecido nos arts.42º ao 49 da referida Lei Complementar.
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         case 8:
             var documento = `<div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração que não Emprega Menor de Idade, Salvo na Condição de Aprendiz</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Micro Empresa ou Empresa de Pequeno Porte</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, para fins de cumprimento do disposto no inciso XXXIII do Art.7º da Constituição Federal, que não emprega menor de dezoito anos em trabalho noturno, perigoso ou insalubre e que não emprega menor de dezesseis anos.
                      
                      <div class="my-3">
                            <span class="fs-14 fw-500 text-danger"><i>Ressalva: emprega menor, a partir de quatorze anos, na condição de aprendiz.</i></span>                              
                      </div>
                      
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>`
             break;
         default:
             var documento = '<p>Opção inexistente<p>'
             break;
     }

     return documento
 }

function mostrarInput(id) {
     document.getElementById(id).classList.remove('is-invalid')
     document.getElementById(id).closest('.col-12').classList.remove('d-none')
 }

function funcaoInputs(valor) {
     switch (parseInt(valor)) {
         case 1:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             mostrarInput('responsavel2')
             mostrarInput('rg2')
             mostrarInput('orgaoExp2')
             mostrarInput('cpf2')
             break;
         case 2:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 3:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 4:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 5:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 6:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 7:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
         case 8:
             mostrarInput('ref')
             mostrarInput('razaoSocial')
             mostrarInput('cnpj')
             mostrarInput('responsavel')
             mostrarInput('rg')
             mostrarInput('orgaoExp')
             mostrarInput('cpf')
             mostrarInput('data')
             break;
     }
 }

function esconderTodosOsInputs() {
     var card = document.getElementsByClassName('card-imputs')[0]

     var inputs = card.getElementsByClassName('col-12')

     var i = 0

     while (i < inputs.length) {
         inputs[i].classList.add('d-none')
         inputs[i].getElementsByTagName('input')[0].value = ''
         i++
     }

 }

function changeDocument() {

     esconderTodosOsInputs()

     var selectedDocument = document.getElementById("document-select").value;

     var documentTemplate = document.getElementById("document-template");

     var documento = funcoesModelos(selectedDocument)

     documentTemplate.innerHTML = documento

     funcaoInputs(selectedDocument)

     if (true) {
         console.log('exemplo')
     } else if (selectedDocument === "declaracao3") {
         documentTemplate.innerHTML = `
           <div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
              <span class="text-light">Modelo Impresso: Declaração de Inexistência de Fatos Impeditivos</span>
            </div>
            
            <div class="card-document gap-4">
              <div>
                <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                <span class="fs-12 fw-500">
                  Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                  a inabilitação do licitante.
                </span>
              </div>
              
              <div class="d-flex justify-content-center">
                <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de Inexistência de Fatos Impeditivos</span>
              </div>
              
              <div>
                <div class="d-flex align-items-center">
                  <p class="m-0"><strong>Ref: </strong></p>
                  <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                </div>
            
                <div class="document-content">
                  <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                  <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                  <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                  <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                  <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                  DECLARA, sob as penas da lei, que até a presente data inexistem fatos impeditivos para a habilitaçãono presente processo licitatório, ciente de obrigatoriedade de declarar ocorrências posteriores.
                  
                  <div class="d-flex align-items-center">
                    <p class="mt-3"><strong>Data: </strong></p>
                    <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                  </div>
            
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <p>_____________________________________</p>
                    <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                  </div>
                </div>
              </div>
            </div>
            <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
            <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>
        `;


     } else if (selectedDocument === "declaracao4") {
         documentTemplate.innerHTML = `
            <div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
                  <span class="text-light">Modelo Impresso: Declaração de não Trabalho Forçado e Degradante</span>
                </div>
                
                <div class="card-document gap-4">
                  <div>
                    <span class="text-danger text-uppercase fs-12"><i>Important</i>:</span>
                    <span class="fs-12 fw-500">
                      Observar se o modelo fornecido no edital estabelece alguma informação adicional ou conflitante com este conteúdo sugerido, bem como a necessidade de reconhecimento de firma nas assinaturas, visando evitar
                      a inabilitação do licitante.
                    </span>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                    <span class="fs-16 fw-800 text-center m-0 text-uppercase">Declaração de não Trabalho Forçado e Degradante</span>
                  </div>
                  
                  <div>
                    <div class="d-flex align-items-center">
                      <p class="m-0"><strong>Ref: </strong></p>
                      <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>                      
                    </div>
                
                    <div class="document-content">
                      <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>, inscrita no CNPJ sob nº 
                      <span class="fs-16 fw-500" id="doc-cnpj">___________</span>, por intermédio de seu representante legal, Sr(a). 
                      <span class="fs-16 fw-500" id="doc-responsavel">___________</span>, portador(a) da Carteira de Identidade nº 
                      <span class="fs-16 fw-500" id="doc-rg">___________</span>, Orgão Expedidor 
                      <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span> e do CPF nº <span class="fs-16 fw-500" id="doc-cpf">___________</span>,
                      DECLARA, que não possui em sua carteira produtiva, empregados executando trabalho degradante ou forçado, observando o disposto incisos III e IV do art.1º e no inc.III do art.5º da Constituição Federal.
                      
                      <div class="d-flex align-items-center">
                        <p class="mt-3"><strong>Data: </strong></p>
                        <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>                      
                      </div>
                
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <p>_____________________________________</p>
                        <p><span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span></p>
                      </div>
                    </div>
                  </div>
                </div>
                <span class="d-none fs-16 fw-500" id="doc-rg2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-cpf2">___________</span>
                <span class="d-none fs-16 fw-500" id="doc-responsavel2">___________</span>
        `
     }

 }