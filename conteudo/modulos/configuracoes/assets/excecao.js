function moduloConfiguracoesExcecao(){
    nownFiles.add(`${dominio}/conteudo/modulos/configuracoes/assets/js.js`).then(()=>{
        new ConfiguracaoControler(1);
    })
}