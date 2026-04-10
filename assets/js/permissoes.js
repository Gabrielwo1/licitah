class Permissoes{
    constructor(tipo){
        this.tipo = tipo;
        
        this.autorizar();
    }
    
    autorizar(){
        switch(this.tipo){
            case 'localizacao':
                
if ('geolocation' in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
      console.log(position.coords)

  });
}

                
                
                break;
        }
    }
    
    
    status(){
        
    }
}


if ('geolocation' in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
    // A posição do usuário está disponível em position.coords
  });
}

setInterval(function(){console.log(navigator.onLine)}, 2000);

function enviarNotificacao() {
  if ('Notification' in window && navigator.serviceWorker) {
    navigator.serviceWorker.ready.then(function(registration) {
      const options = {
        body: 'Corpo da Notificação',
        icon: 'caminho/do/icone.png',
      };

      registration.showNotification('Título da Notificação', options);
    });
  }
}

enviarNotificacao() ;


class NotificacaoPushManager {
  constructor() {
    this.inicializar();
    this.salva = this.salva.bind(this)
  }

  inicializar() {
    document.getElementById('solicitarPermissao').addEventListener('click', () => {
      this.solicitarPermissao();
    });
  }

  async solicitarPermissao() {
    if ('Notification' in window && 'serviceWorker' in navigator) {
      try {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
          const registration = await navigator.serviceWorker.ready;
          const applicationServerKey = 'BJ-7ASjjo_y4CfYO8U1SZgMc7bBx06uWpiNqbTHiwNADlL4MCxDSv7FnJzWk1hk43iv4LAi8jln5bvxaxEnQ_Bg';
          const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: this.urlBase64ToUint8Array(applicationServerKey),
          });
          const token = subscription.endpoint;
          this.salva(token);
        } else {
          console.log('Unable to get permission to notify.');
        }
      } catch (error) {
        console.error('Erro durante o processo:', error);
      }
    }
  }
  
  
  salva(r){
      var data = new FormData();
      data.append("acao", "registro")
      data.append("key", r)
       const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    console.log(this.responseText)
  }
  xhttp.open("POST", `${dominioAdress}/admin/pushnotification.php`);
  xhttp.send(data);
     
  }

  urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/-/g, '+')
      .replace(/_/g, '/');

    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
  }
}

// Criar uma instância da classe
const notificacaoPushManager = new NotificacaoPushManager();
