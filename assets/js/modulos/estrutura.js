import { cripto, pegaLocal, defineLocal, loading, criarIcone, preventLink, evento, ajax, loadGoogleMaterialIcons, ls, lcss, loadResources, geraId } from 'https://nown.com.br//assets/js/modulos/functions.js?v=3';

export default class Estrutura {
    constructor() {
        if (document.getElementById("dataRender")) {

            this.paginacao = 0;
            this.dataRender = document.getElementById("dataRender");

            this.tipo = this.dataRender.dataset.tipo
            this.isItem = this.dataRender.dataset.item
            this.url = this.dataRender.dataset.url
            this.modulo = this.dataRender.dataset.modulo


            this.config = {
                tipo: this.tipo,
                isItem: this.isItem,
                url: this.url,
                modulo: this.modulo
            }

            this.banco = new BancoDeDados(this.modulo);




            this.dataRender.remove();

        }
    }


    preRender() {
        return new Promise((resolve, reject) => {
            switch (this.config.tipo) {
                case 'item':
                    this.banco.umItem(this.config.url)
                        .then((r) => {
                            resolve(r);
                        })
                        .catch((error) => {
                            reject(error);
                        });
                    break;
                default:
                    this.banco.lista()
                        .then((r) => {
                            resolve(r);
                        })
                        .catch((error) => {
                            reject(error);
                        });
                    break;
            }
        });
    }




    page(num) {
        this.paginacao = this.paginacao + num
    }

    ajax() {

        return new Promise((resolve, reject) => {
            var data = new FormData();
            data.append("tipo", this.tipo ? this.tipo : false);
            data.append("isItem", this.isItem ? this.isItem : false);
            data.append("url", this.url ? this.url : false);
            data.append("modulo", this.modulo ? this.modulo : false);
            data.append("paginacao", this.paginacao ? this.paginacao : 0);

            const xhttp = new XMLHttpRequest();
            xhttp.onload = () => {
                if (xhttp.status === 200) {
                    var obj = JSON.parse(xhttp.responseText);
                    if (obj.erro) {
                        reject(obj.resposta);
                    }

                    if (obj.sucesso) {

                        this.banco.novo(obj.resposta);

                        resolve(obj.resposta);

                        if (!this.isItem) {
                            var tamanho = obj.resposta.length;
                            if (tamanho == 10) {
                                this.page(tamanho);
                                this.ajax().then(resolve).catch(reject);
                            }
                        }
                    }
                } else {
                    reject(xhttp.statusText);
                }
            };
            xhttp.onerror = () => {
                reject("Erro na requisição");
            };
            xhttp.open("POST", `${dominioAdress}/admin/estrutura.php`);
            xhttp.send(data);
        });


    }
}