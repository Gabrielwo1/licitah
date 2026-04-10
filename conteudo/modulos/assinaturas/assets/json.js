function moduloAssinaturasPlanos(){
    
}

function verificarDesconhecido(dado){
    if(!dado.trim()){
        return 'Desconhecido'
    }
    
    return dado
}

function exportInfosClientes(r, r1){
    console.log(r, r1)
    var request = new Request(`${dominio}/conteudo/modulos/assinaturas/admins/api.php`)
    
    request.addData({
        'acao' : 'infosUser',
        'plano' : r1.infos.id
    })
    
    Swal.fire({
        title: "Verificando Usuários",
        didOpen: () => {
            Swal.showLoading();
        },
        html: `
            Isso pode levar alguns segundos.
         `,
        allowOutsideClick: false 
    
    })
    
    
    
    request.send().then((r)=>{
        if(r.lista){
            console.log(r.lista)
            var lista = r.lista
            nownFiles.add(['https://cdn.jsdelivr.net/npm/xlsx@0.17.4/dist/xlsx.full.min.js']).then(()=>{
                if(lista.length > 0){
                    
                    var lista2 = []
                    
                    var i = 0;

                    while (i < lista.length) {
                        let enderecos = lista[i].enderecos;
                        let nome = verificarDesconhecido(lista[i].nome);
                        let cpf = verificarDesconhecido(lista[i].cpf);
                        let email = verificarDesconhecido(lista[i].email);
                
                        if (enderecos.length > 0) {
                            let j = 0;
                            while (j < enderecos.length) {
                                lista2.push({
                                    'Nome Completo': j === 0 ? nome : '""', // Mostra o nome apenas na primeira linha
                                    'CPF/CNPJ': j === 0 ? cpf : '""', // Mostra o CPF apenas na primeira linha
                                    'E-mail': j === 0 ? email : '""', // Mostra o e-mail apenas na primeira linha
                                    'CEP': verificarDesconhecido(enderecos[j].cep),
                                    'Rua': verificarDesconhecido(enderecos[j].rua),
                                    'Bairro': verificarDesconhecido(enderecos[j].bairro),
                                    'Número': verificarDesconhecido(enderecos[j].numero),
                                    'Complemento': verificarDesconhecido(enderecos[j].complemento),
                                    'Cidade': verificarDesconhecido(enderecos[j].cidade),
                                    'Estado': verificarDesconhecido(enderecos[j].estado),
                                    'País': verificarDesconhecido(enderecos[j].pais),
                                });
                                j++;
                            }
                        } else {
                            // Se não houver endereços, adiciona uma linha com "Não informado"
                            lista2.push({
                                'Nome Completo': nome,
                                'CPF/CNPJ': cpf,
                                'E-mail': email,
                                'CEP': 'Não informado',
                                'Rua': 'Não informado',
                                'Bairro': 'Não informado',
                                'Número': 'Não informado',
                                'Complemento': 'Não informado',
                                'Cidade': 'Não informado',
                                'Estado': 'Não informado',
                                'País': 'Não informado'
                            });
                        }
                        i++;
                    }
                    
                    // Criar um novo workbook e worksheet
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.json_to_sheet(lista2);
                    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                    
                    
            
                    // Convertendo o arquivo para um blob
                    const wbBlob = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
                    
                    // Convertendo o blob para um objeto Blob
                    const blob = new Blob([wbBlob], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                    
                    // Criando um URL temporÃ¡rio para o blob
                    const url = URL.createObjectURL(blob);

                    // Criando um link de download
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${r1.infos[0]}.xlsx`;
                    a.click();
                    
                    // Limpar o URL temporÃ¡rio
                    URL.revokeObjectURL(url);
                    
                    Swal.fire('Arquivo Baixado!', `O arquivo sobre o plano ${r1.infos[0]}`, 'success');
                    
                }else{
                    Swal.fire('Falha ao exportar!', 'Nenhum usuário foi identificado nesse plano', 'error');
                }
            })
        }else{
            Swal.fire('Falha ao exportar!', 'Nenhum usuário foi identificado nesse plano', 'error');
        }

        
    }, (r)=>{
        Swal.fire('Falha ao exportar!', r.mensagem, 'error');
    })
    // console.log('clicou')
}