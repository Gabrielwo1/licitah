
<section>
  <div class="container py-5">
    <div class="row">
      <div class="col-xl-4 col-12">
        <div class="d-flex flex-column gap-3 inputs-container stick-top">
          <div>
            <label class="mb-3 fs-22 fw-800" for="document-select">Modelos de Declarações:</label>
            <select class="select-style w-100" id="document-select">
              <option value="1">Carta de Credenciamento</option>
              <option value="2">Declaração de Elaboração Independente de Proposta</option>
              <option value="3">Declaração de Inexistência de Fatos Impeditivos</option>
              <option value="4">Declaração de Não Trabalho Forçado e Degradante</option>
              <option value="5">Declaração de Renúncia de Vistoria</option>
              <option value="6">Declaração de Habilitação</option>
              <option value="7">Declaração de Micro Empresa ou Empresa de Pequeno Porte</option>
              <option value="8">Declaração que não emprega menor de idade, salvo na condição de aprendiz</option>
            </select>
          </div>
          <div class="card-imputs bg-li">
            <div class="row g-2">
              <div class="col-lg-4 col-12">
                <input class="select-style w-100 form-control" placeholder="Referência" type="text" id="ref" oninput="updateDocument()"/>
              </div>
              <div class="col-lg-8 col-12">
                <input
                      class="select-style w-100 form-control"
                      placeholder="Razão Social:"
                      type="text"
                      id="razaoSocial"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-6 col-12">
                <input
                      class="select-style w-100 form-control"
                      placeholder="CNPJ: XX.XXX.XXX/0001-XX."
                      type="text"
                      id="cnpj"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-6 col-12">
                <input
                      class="select-style w-100 form-control"
                      placeholder="Responsável:"
                      type="text"
                      id="responsavel"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-5 col-12">
                <input class="select-style w-100 form-control" placeholder="RG:" type="text" id="rg" oninput="updateDocument()"/>
              </div>
              <div class="col-lg-2 col-12">
                <input
                      class="select-style w-100 text-uppercase form-control"
                      placeholder="Orgão Exp:"
                      type="text"
                      id="orgaoExp"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-5 col-12">
                <input
                      class="select-style w-100 text-uppercase form-control"
                      placeholder="CPF:"
                      type="text"
                      id="cpf"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-6 col-12">
                <input class="select-style w-100 form-control" placeholder="Data:" type="text" id="data" oninput="updateDocument()"/>
              </div>
              <div class="col-lg-6 col-12">
                <input
                      class="select-style w-100 form-control"
                      placeholder="Responsável nº2:"
                      type="text"
                      id="responsavel2"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-5 col-12">
                <input class="select-style w-100 form-control" placeholder="RG nº2:" type="text" id="rg2" oninput="updateDocument()"/>
              </div>
              <div class="col-lg-2 col-12">
                <input
                      class="select-style w-100 text-uppercase form-control"
                      placeholder="Orgão nº2 Exp:"
                      type="text"
                      id="orgaoExp2"
                      oninput="updateDocument()"/>
              </div>
              <div class="col-lg-5 col-12">
                <input class="select-style w-100 form-control" placeholder="CPF nº2:" type="text" id="cpf2" oninput="updateDocument()"/>
              </div>
            </div>
          </div>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-n-primaria fs-xl-14 fw-700 py-3 px-4" id="botaoExportar">EXPORTAR DOCUMENTO <i class=" ms-2 bi bi-arrow-down-circle"></i></button>
            </div>
        </div>
      </div>
      <div class="col-xl-8 col-12">
        <div class="document-container readonly">
          <div id="document-template">
            <div class="bg-primaria w-100 p-3" style="border-radius: 6px 6px 0 0;">
              <span class="text-light">Modelo Impresso: Carta de Credenciamento</span>
            </div>
            <div class="card-document gap-4">
              <div>
                <span class="text-danger text-uppercase fs-12">
                  <i>Important</i>
                  :
                </span>
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
                  <p class="m-0">
                    <strong>Ref:</strong>
                  </p>
                  <span class="fs-16 fw-500 ms-2" id="doc-ref">_________</span>
                </div>
                <div class="document-content">
                  <span class="fs-16 fw-500" id="doc-razaoSocial">___________</span>
                  , inscrita no CNPJ sob nº
                  <span class="fs-16 fw-500" id="doc-cnpj">___________</span>
                  , por intermédio de seu representante legal, Sr(a).
                  <span class="fs-16 fw-500" id="doc-responsavel">___________</span>
                  , portador(a) da Carteira de Identidade nº
                  <span class="fs-16 fw-500" id="doc-rg">___________</span>
                  , Orgão Expedidor
                  <span class="fs-16 fw-500" id="doc-orgaoExp">___________</span>
                  e do CPF nº
                  <span class="fs-16 fw-500" class="fs-16 fw-500" id="doc-cpf">___________</span>
                  , pela presente CREDENCIA o Sr.
                  <span class="fs-16 fw-500" id="doc-responsavel2">___________</span>
                  , portador da carteira de identidade nº
                  <span class="fs-16 fw-500" id="doc-rg2">___________</span>
                  , Órgão Expedidor
                  <span class="fs-16 fw-500" id="doc-orgaoExp2">___________</span>
                  e do CPF nº
                  <span class="fs-16 fw-500" id="doc-cpf2">___________</span>
                  , para representá-la na Licitação em epígrafe supra mencionada, 
                           outorgando-lhe poderes para concorrer, desistir, renunciar, transigir, firmar recibos, 
                           assinar Atas e outros documentos, acompanhar todo o processo Licitatório até o seu final, 
                           tomar ciência de outras propostas, podendo para tanto, praticar todos os atos necessários 
                           para o bom e fiel cumprimento deste mandato.
                  <div class="d-flex align-items-center">
                    <p class="mt-3">
                      <strong>Data:</strong>
                    </p>
                    <span class="fs-16 fw-500 ms-2" id="doc-data">__/__/____</span>
                  </div>
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <p>_____________________________________</p>
                    <p>
                      <span class="fs-16 fw-500 ms-2" id="doc-razaoSocial">___________</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>