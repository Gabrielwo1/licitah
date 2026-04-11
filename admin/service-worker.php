<?php
header("Content-Type: application/javascript; charset=utf-8");
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Service-Worker-Allowed: /');
?>
// Service Worker desabilitado — limpa tudo e se desinstala
self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.map(key => caches.delete(key)))
        ).then(() => self.clients.claim())
         .then(() => self.registration.unregister())
    );
});
