<?php
header("Content-Type: application/javascript; charset=utf-8");
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache'); 
header('Expires: 0');
header('Service-Worker-Allowed: /');

$manifestPath = __DIR__ . "/manifest.json";
$manifest = json_decode(file_get_contents($manifestPath), true);

$versao = str_replace(".", "", $manifest["versao"]);
echo "importScripts('https://js.pusher.com/beams/service-worker.js');\n";
echo "const CACHE_NAME = 'nown-" . $versao . "';\n";
?>


// Force SW update on all clients
self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', () => self.clients.claim());

const urlsToCache = [
    "/assets/js/index.js", 
    "/assets/js/nown.js", 
    "/assets/js/pwa.js",
    "/assets/aplicativo/bootstrap/min.css",
    "/assets/aplicativo/bootstrap/min.js",
    "/assets/css/botstrapFortram.css",
    "/assets/css/index.css",
    "/assets/aplicativo/dexie/min.js",
    "/assets/aplicativo/animate/min.css",
    "/assets/aplicativo/jquery/min.js",
    "/assets/aplicativo/quill/min.js",
    "/assets/aplicativo/quill/min.css",
    "/assets/aplicativo/filepond/min.js",
    "/assets/aplicativo/filepond/min.css",
    "/assets/aplicativo/sweetalert2/11105.js",
    "/assets/bibliotecas/tagify/tagify.js",
    "/assets/bibliotecas/tagify/tagify.css",
    "/assets/aplicativo/select2/41.css",
    "/assets/aplicativo/select2/41.js",
    "/assets/aplicativo/datatable/min.css",
    "/assets/aplicativo/sweetalert2/min.js",
    "/assets/aplicativo/pdfmake/min.js",
     "/assets/aplicativo/datatable/min.js",
     "/assets/aplicativo/peerjs/peerjs.min.js"
    ];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // O recurso está no cache, então o retorna
                if (response) {
                    return response;
                }
                // Se o recurso não estiver no cache, tenta buscar na rede
                return fetch(event.request).catch(() => {
                    // Se também falhar a busca na rede, retorna a página offline
                    return caches.match('/offline.php');
                });
            })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(cacheName => cacheName !== CACHE_NAME)
                    .map(cacheName => {
                        return caches.delete(cacheName);
                    })
            );
        })
    );
});

self.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'visible') {
       console.log('A aplicação está aberta.');
    } else {
    console.log('A aplicação está fechada.');
    }
});

self.addEventListener('message', function(event) {
    if (event.data.action === 'cacheResources') {
        event.waitUntil(
            caches.open(CACHE_NAME).then(function(cache) {
                return cache.addAll(event.data.urls);
            })
        );
    }
});

self.addEventListener('push', function(event) {
    let data = event.data.json();

    switch(data.tipo){
        case 'notificacao':
            const options = {
        body: data.body,
        icon: 'https://s2.glbimg.com/9L5QaMt_eENU00bRFajcmwo2pU0=/94x94/top/smart/filters:max_age(3600)/https://s01.video.glbimg.com/deo/vi/32/25/12122532',
        badge: 'https://nown.com.br/conteudo/uploads/imagens/usuario/perfil/2024/05/xth074b9c7n51h3_1716230474/mini.webp',
        // aqui você pode adicionar mais opções, como actions, vibrate, etc.
    };
    

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
            break;
    }

    
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();


  const data = event.notification.data;
  if(data.link){
      event.waitUntil(clients.openWindow("/artigos"));
  }

});