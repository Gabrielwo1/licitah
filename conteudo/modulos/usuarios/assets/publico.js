class FluxoUsuarios{
    constructor(){
        if(autenticado()){
            this.me = parseInt(autenticado())
        }else{
            this.me = false;
        }

        this.btns = [];
        this.init.bind(this)();
    
    }
    
    home(){
       this.procura.bind(this)();
     
       this.loading(document.getElementsByClassName("row-usuarios")[0])
       bounce(document.getElementById("inputProcurar"), 500, (r)=>{
           if(r){
               this.procura.bind(this)(r);
           }else{
               this.procura.bind(this)(false);
           }
         })
        evento(document.getElementById("inputProcurar"), "input", ()=>{
            if(document.getElementsByClassName("componenteCarregando").length == 0){
                this.loading(document.getElementsByClassName("row-usuarios")[0]);
            }
        })
         
    }
    
    
    loading(pai){
        pai.innerHTML = "";
        var fragmento = document.createDocumentFragment();
        var i = 0;
        while(i < 12){
            fragmento.appendChild(this.cardLoading.bind(this)())
            
            i++;
        }
        pai.appendChild(fragmento)
        
    }
    
    cardLoading(){

       
        var div = document.createElement("DIV")
        div.classList.add("col-12","col-xl-3", "componenteCarregando")
        div.innerHTML = `
       
      <div class="card-gerenciar-usuario position-relative">
         <div class="cover bg-carregando">
            <div class="he-80">
            </div>
         </div>
         <div class="avatar"> 
            <div class="bg-carregando wi-100 he-100 rounded-circle"></div>
         </div>
         <div class="info">
           <h4><div class="he-20 bg-carregando w-100"></div></h4>
            <p class="contato text-center"><div class="bg-carregando w-50 m-auto" style="height: 16px"></div></p>
         </div>
      </div>
   
        
        
        `

        return div;
    }
    
    cardUser(user){

        var foto = trataImagem(user.f, "mini")
        if(!foto){
            foto = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`
        }
         var capa = trataImagem(user.c, "media")
         if(!capa){
             capa = `${dominio}/conteudo/modulos/usuarios/midias/capa.webp`
         }
         
         console.log(user)
        var div = document.createElement("DIV")
        div.classList.add("col-6","col-md-4","col-xl-3")
        div.innerHTML = `
       

                  <div class="card card-nown overflow-hidden cardGrupo position-relative h-100">
                     <div class="card-header p-0 position-relative">
                        <div class="ratio ratio-21x9 background" style="background-image:url(${capa})"></div>
                        <div class="position-absolute start-50 translate-middle-x" style="bottom: -50px">
                           <div class="wi-100 he-100 rounded-circle imgGrupo background" style="background-image:url(${foto})">
                           </div>
                        </div>
                     </div>
                     <div class="card-body" style="padding-top: 70px">
                        <a href="${dominio}/usuarios/${user.u}" class="stretched-link text-decoration-none">
                           <h2 class="fs-18 fw-500 text-center text-decoration-none">${user.d}</h2>
                        </a>
                        <p class="text-center fs-12">Grupo Público</p>
                        <div class="d-flex justify-content-center">
                           <div class="flex-fill">
                              <ul class="member-thumb">
                                 <li><a href="https://www.radiustheme.com/demo/wordpress/themes/cirkle/members/thomas/"><img loading="lazy" decoding="async" src="https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/avatars/18/60b0843598f70-bpfull.jpg" class="avatar user-18-avatar avatar-150 photo" width="150" height="150" alt="Profile picture of Thomas Carew"></a></li>
                                 <li><a href="https://www.radiustheme.com/demo/wordpress/themes/cirkle/members/monta/"><img loading="lazy" decoding="async" src="https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/avatars/9/60af608a940df-bpfull.jpg" class="avatar user-9-avatar avatar-150 photo" width="150" height="150" alt="Profile picture of Monta Ellis"></a></li>
                                 <li><a href="https://www.radiustheme.com/demo/wordpress/themes/cirkle/members/shan/"><img loading="lazy" decoding="async" src="https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/avatars/13/60b07abe8d8ac-bpfull.jpg" class="avatar user-13-avatar avatar-150 photo" width="150" height="150" alt="Profile picture of Shan Foster"></a></li>
                                 <li><a href="https://www.radiustheme.com/demo/wordpress/themes/cirkle/members/john/"><img loading="lazy" decoding="async" src="https://www.radiustheme.com/demo/wordpress/themes/cirkle/wp-content/uploads/avatars/17/60af313134eef-bpfull.jpg" class="avatar user-17-avatar avatar-150 photo" width="150" height="150" alt="Profile picture of John Caius"></a></li>
                                 <li><a href="https://www.radiustheme.com/demo/wordpress/themes/cirkle/groups/robin-hood/"><i class="icofont-plus"></i></a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-flex justify-content-center gap-3">
                           <div>
                              <div class="fs-20 fw-700 text-center">0</div>
                              <div class="fs-12 text-center cinza">Postagens</div>
                           </div>
                           <div>
                              <div class="he-30 bg-secondary" style="width: 1px"></div>
                           </div>
                           <div>
                              <div class="fs-20 fw-700 text-center">1</div>
                              <div class="fs-12 text-center cinza">Participantes</div>
                           </div>
                        </div>
                     </div>
                  </div>
       
   
        
        
        `
        evento(Array.from(div.getElementsByTagName("a")), "click", preventLink)
        return div;
    }
    
    procura(termo = false){
        
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
        var obj = {
            acao: "procurar"
        }
        if(termo){
            obj.termo = termo
        }
        request.addData(obj)
        request.send().then((r)=>{
            var lista = document.getElementsByClassName("row-usuarios")[0];
            var resultados = r.resultados;
             var fragmento = document.createDocumentFragment();
            if(resultados.length > 0){
                var i = 0;
            while(i < resultados.length){
                var card = this.cardUser.bind(this)(resultados[i]);
                fragmento.appendChild(card)
                i++;
            }
            }else{
                var div = document.createElement("DIV")
                div.innerHTML = `Nenhum usuário encontrado.`
                fragmento.appendChild(div)
            }
           
            
            lista.appendChild(fragmento)
            limpaLoad();
         
        },(r)=>{
            console.log(r)
        })
    }
    
    seguir(){
        var btn = event.currentTarget;
        let add = false;
        let acao = false;
        
        
        if(btn.classList.contains("seguir")){
            add = "seguindo";
            acao = "seguir";
        }
        
        if(btn.classList.contains("seguindo")){
            add = "seguir";
            acao = "desseguir";
        }
        
        if(btn.classList.contains("seguirdevolta")){
            add = "seguindo";
            acao = "seguir";
        }
        
        btn.classList.remove("seguir", "seguindo", "seguirdevolta");
        btn.classList.add(add)
        
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
        request.addData({"acao": "vincular", "user": this.user, "vinculo": acao})
        request.send().then((r)=>{
            if(acao == "seguir"){
                document.getElementById("seguidores").innerText = parseInt(document.getElementById("seguidores").innerText) + 1
            }else{
                document.getElementById("seguidores").innerText = parseInt(document.getElementById("seguidores").innerText) - 1
            }
            console.log(r)
        }, (r)=>{
            console.log(r)
        })
        
        
        
        
    }
    
    bloquear(){
Swal.fire({
  title: "Você tem certeza?",
  text: `Você está prestes a bloquear ${this.membro.display} . Com isso vocês não irão interagir um com o outro, nem com seu conteúdo gerado.`,
  icon: `question`,
  showCancelButton: true,
  confirmButtonText: "Bloquear",
  cancelButtonText: `Cancelar`
}).then((result) => {

  if (result.isConfirmed) {
       let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
    request.addData({"acao": "vincular", "user": this.user, "vinculo": "bloquear"})
    request.send().then((r)=>{
         goUrl("/")
         return;
    })
        
    Swal.fire({
        icon: "success",
        title: "Sucesso",
        text: "Usuário Bloqueado",
        showConfirmButton: false,
        timer: 1500
    });
  } 
});
    }
    
    liberarBtn(id, evt){
        if(!this.btns[id]){
            this.btns[id] = document.getElementById(id)
            evento(this.btns[id], "click", evt)
        }
        
        this.btns[id].closest(".list-group-item").classList.remove("d-none")
        
    }
    
    item(user){


        this.user = user;

        
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
        request.addData({"acao": "vinculo", "user": user})
        request.send().then((r)=>{
            

            
            var vinculos = r.vinculos;
            
            if(vinculos[5]){
                 if(document.getElementById("bloquear")){
                naoExiste();
                return;
                 }
           
            }else{
                if(document.getElementById("bloquear")){
                    evento(document.getElementById("bloquear"), "click", this.bloquear.bind(this))
                }
            }
            
            if(document.getElementById("btnSeguir")){
                this.btnSeguir = document.getElementById("btnSeguir");
                evento(this.btnSeguir, "click", this.seguir.bind(this))
                
                if(!vinculos[2]){
                    this.btnSeguir.classList.add("seguir")
                }else{
                    if(vinculos[2].length == 2){
                       this.btnSeguir.classList.add("seguindo") 
                    }else{
                        var v = vinculos[2][0]
                        if(v["um"] ==  this.me){
                            this.btnSeguir.classList.add("seguindo") 
                        }else{
                            this.btnSeguir.classList.add("seguirdevolta") 
                        }
                    }
                }

            }
            
            if(document.getElementById("add-amizade")){
                evento(document.getElementById("add-amizade"), "click", this.controlerAmizade.bind(this))
                
                
                if(vinculos[1]){
                    let amizade = vinculos[1][0];
                    if(parseInt(amizade.aprovado)){
                        this.liberarBtn("btnDesfazerAmizade", this.amizade.bind(this));
                    }else{
                        if(amizade.um == this.me){
                            this.liberarBtn("btnCancelarPedido", this.amizade.bind(this));
                        }else{
                            this.liberarBtn("btnAceitar", this.amizade.bind(this));
                            this.liberarBtn("btnRejeitar", this.amizade.bind(this));
                        }
                    }
                }else{
                    this.liberarBtn("btnSolicitar", this.amizade.bind(this));
                }
                
                
                this.boxAmizade = new nownCanvas("boxAmizade", {
                dirDesktop : "bottom",
                dirMobile : "bottom",
                backdropBlur : true,
                persist:  true,
                // backdropClose : false,
                // escapeClose : false,
                // desktopPan : false,
                // mobilePan : false,
            });
                
                
            }
            
            
            
            let api = new ApiNown("usuarios", "Tu84AqqGZESDZXa")
            api.setHash(user);
            api.send().then((r)=>{
            if(!r.item){
                naoExiste();
                return;
            }
            this.membro = r.item
            
            
            if(this.membro.id == autenticado()){
                document.getElementsByClassName("grupo-acoes")[0].closest(".grupo-dados-box").remove();
            }

            
            
            var foto = trataImagem(this.membro.foto, "media");
            let img = `${dominio}/conteudo/modulos/usuarios/midias/perfil.jpg`
            if(foto){
                img = foto;
            }
            var imagem = document.createElement("IMG")
            imagem.src = img;
            document.getElementById("avatarFoto").appendChild(imagem)
            
            
            var capa = trataImagem(this.membro.capa, "grande")
            if(capa){
                document.getElementsByClassName("header-grupo-capa")[0].getElementsByTagName("IMG")[0].src = capa
            }
            
            document.getElementsByClassName("nome")[0].innerText = this.membro.display
            document.getElementsByClassName("nome")[1].innerText = this.membro.display
            
                
            }, (r)=>{
            console.log(r)
        })
            
            
            let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
            request.addData({"acao": "init", "user": user})
            request.send().then((r)=>{
                var numeros = r.numeros;
                
                document.getElementById("seguidores").innerText = numeros.seguidores
                document.getElementById("seguindo").innerText = numeros.seguindo

            }, (r)=>{
                console.log(r)
            })
            
            
            
            
        }, (r)=>{
            console.log(r)
        })

    }
    
    amizade(){
        this.boxAmizade.hide();
        
        let btn = event.currentTarget
        
        for(let c in this.btns){
            this.btns[c].closest(".list-group-item").classList.add("d-none")
        }
        let vinculo = false;
        switch(btn.id){
            case 'btnSolicitar':
                this.liberarBtn("btnCancelarPedido", this.amizade.bind(this))
                vinculo = "amizade";
                break;
            case 'btnAceitar':
                this.liberarBtn("btnDesfazerAmizade", this.amizade.bind(this))
                vinculo = "amizade";
                break;
            case 'btnCancelarPedido':
                this.liberarBtn("btnSolicitar", this.amizade.bind(this))
                vinculo = "desfazer-amizade";
                break;
            case 'btnRejeitar':
                vinculo = "desfazer-amizade";
                this.liberarBtn("btnSolicitar", this.amizade.bind(this))
                break;
            case 'btnDesfazerAmizade':
                vinculo = "desfazer-amizade";
                this.liberarBtn("btnSolicitar", this.amizade.bind(this))
                break;
        }
        
        
        let request = new Request(`${dominio}/conteudo/modulos/usuarios/admins/api.php`);
        request.addData({"acao": "vincular", "user": this.user, "vinculo": vinculo})
        request.send().then((r)=>{
            
            console.log(r)
        }, (r)=>{
            console.log(r)
        })
        
        
        
   
    }
    
    controlerAmizade(){
        this.boxAmizade.show();
    }
    
    init(){
        var fluxo =  fluxoPage("usuarios");
       
        if(fluxo.length == 0){
            this.home.bind(this)();
        }else{
            this.item.bind(this)(fluxo[0])
        }
        
    }
}

function paginaUsuarios(){
    new FluxoUsuarios();
}