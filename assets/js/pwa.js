function detectDeviceType() {
    const userAgent = navigator.userAgent;

    if (/Mobi|Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent)) {
      
        
        if (/Android/i.test(userAgent)) {
            return 1;
        } else if (/iPhone|iPad|iPod/i.test(userAgent)) {
            return 2;
        }
    } else {

        return 3;
    }
    return 4;
}

function isDownloadCookieValid() {

    if (document.cookie.indexOf("baixar_app") < 0) {
        return false;
    }
    else {    
        return true;
    }

}

function setDownloadCookie() {
    const cookieName = "baixar_app";
    const cookieValue = "true";
    const expirationTime = 24 * 60 * 60 * 1000;

    const currentDate = new Date();
    const expirationDate = new Date(currentDate.getTime() + expirationTime);

    document.cookie = `${cookieName}=${cookieValue}; expires=${expirationDate.toUTCString()}; path=/`;
    
    console.log("Cookie 'baixar_app' definido com sucesso!");
}

function instalarApp(){

    switch(detectDeviceType()){
        case 1:
            var icone = 'bi bi-android2'
            var texto = "Android";
            break;
        case 2:
            var icone = 'bi bi-apple'
             var texto = "Apple";
            break;
        case 3:
            var icone = 'bi bi-windows'
             var texto = "Computador";
            break;
        default:
            var icone = 'bi bi-phone'
             var texto = "Smartphone";
            break;
    }
    
        if(!isDownloadCookieValid() && window.location.href.split("/a/").length == 1){
            
     
     setTimeout(()=>{
        iziToast.show({
            icon: icone,
    title: `Baixe em seu ${texto}`,
    message: 'Aproveite nosso App',
    timeout: 10000,
    buttons:[ ['<button>Baixar App</button>', function (instance, toast) {
            instalarPrompt()
            instance.hide({
                transitionOut: 'fadeOutUp',
                
            }, toast, 'buttonName');
        }, true],
        ['<button>Agora Não</button>', function (instance, toast) {
            setDownloadCookie();
            instance.hide({
                transitionOut: 'fadeOutUp',
                
            }, toast, 'buttonName');
        }]
        
        ]
});
    }, 5000)
      
    }
    
    
    
      
    
    
     var boxOnline = document.getElementById('baixeApp')
     if(boxOnline){
          boxOnline.getElementsByClassName("icone")[0].innerHTML = icone
     var toast = bootstrap.Toast.getOrCreateInstance(boxOnline)
  
  
    
    var botao = boxOnline.getElementsByClassName("btn-close")[0]
    botao.addEventListener("click", rejeitaDownload)   


         
     }
     
    
    
   

}

function instalarPrompt() {
    // Check if the deferred prompt is available
    if (deferredInstallPrompt !== null) {
        // Show the install prompt
        deferredInstallPrompt.prompt();
        
        // Wait for the user to respond to the prompt
        deferredInstallPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            // We can only use the prompt once, so let's clear it now
            deferredInstallPrompt = null;
        });
    } else {
        // Optionally, inform the user that the app is ready for installation but the prompt cannot be shown again
        console.log("The app can be installed from the browser's menu.");
    }
}

// Service Worker desabilitado temporariamente
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for (let registration of registrations) {
            registration.unregister();
        }
    });
    caches.keys().then(function(cacheNames) {
        cacheNames.forEach(function(cacheName) {
            caches.delete(cacheName);
        });
    });
}

let deferredInstallPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
  //  console.log("pwa nao instalada")
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredInstallPrompt = e;
  
  instalarApp();
});

window.addEventListener('appinstalled', (e) => {
    iziToast.success({
            icon: "bi bi-check-circle",
            title: `Sucesso`,
            position: 'center',
            message: 'Aplicativo Baixado com Sucesso',
            timeout: 3000 
}); 
});





