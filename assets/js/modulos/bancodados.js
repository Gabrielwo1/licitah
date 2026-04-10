import { cripto, pegaLocal, defineLocal, loading, criarIcone, preventLink, evento, ajax, loadGoogleMaterialIcons, ls, lcss, loadResources, geraId } from 'https://nown.com.br//assets/js/modulos/functions.js?v=3';

export default class BancoDeDados {
    constructor(tabela) {
        
        this.db = new Dexie(tabela);
        this.db.version(1).stores({
            paginas: '++url'
        });

  }
  
    async lista(quantidade, paginacao) {
        try {
            if (quantidade && paginacao) {
                // Caso quantidade e paginacao sejam fornecidos,
                // retorne a quantidade de itens na página de paginacao
                const items = await this.db.paginas
                    .offset(paginacao * quantidade)
                    .limit(quantidade)
                    .toArray();
                return items;
            } else {
                // Caso contrário, retorne todos os itens
                const items = await this.db.paginas.toArray();
                return items;
            }
        } catch (error) {
            console.error('Erro ao listar os itens:', error);
        }
    }

   async umItem(url) {
        try {

            const item = await this.db.paginas.get(url);
            if (item) {
                return item;
            } else {
                return false;
            }
        } catch (error) {
            console.error('Erro ao procurar o item:', error);
        }
    }
  
  novo(array){
      
      this.db.paginas.bulkPut(array)
  }
  
}