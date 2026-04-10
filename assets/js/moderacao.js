function atualizasessao(){
    if(!start.websocket.valid){
        var request = new Request("admin/autoriza.php");
        request.addData({"acao": "updateSession"})
        request.send().then((e)=>{
            if(parseInt(e.status) > 0){
                goUrl("");
            }else{
                setTimeout(()=>{
                     atualizasessao();
                }, 30000)
            }

        })
       
       
       
    }
}

function paginaAguardandoAprovacao(){
    atualizasessao();
    
    var btn = document.createElement("BUTTON")
    btn.innerText = "Sair"
    btn.classList.add("btn", "btn-n-primaria")
    document.getElementById("btnAreas").appendChild(btn)
    evento(btn, "click", deslogar);
    
}