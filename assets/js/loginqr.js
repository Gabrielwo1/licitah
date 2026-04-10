
function paginaLoginqr() {
    
    var entry = document.getElementById("refQr")

    const largura = entry.offsetWidth;
    const altura = entry.offsetHeight;

    const aspectRatio = largura / altura;
    
    var aguardando = false;

  loadJSResource(`https://unpkg.com/html5-qrcode`).then(() => {
    const html5QrCode = new Html5Qrcode("reader");
const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        var hash = decodedText.split("loginqr/")[1]
        
        if(!aguardando){
            aguardando = true;
            
             var request = new Request(`${dominio}/admin/login.php`)
        request.addData({ "hash": hash, "acao" : "loginQr"})
        request.send().then((r)=>{
            iziToast.success({
                icon: 'bi bi-box-arrow-in-right',
    title: 'Sucesso',
    message: 'Login Feito com Sucesso'
});

            goUrl(`perfil/gerenciar-sessoes`);
            
        }, (r)=>{
             aguardando = false;
        })
            
        }
       
};
const config = { fps: 10, qrbox: { width: 250, height: 250 } , aspectRatio: 2.1};



// If you want to prefer back camera
html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);


  });
}