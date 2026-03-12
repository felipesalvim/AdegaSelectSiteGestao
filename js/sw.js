// js/sw.js
const CACHE_NAME = 'adegaselect-v1';

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(c => {
            return c.addAll([
                './',
                './index.php',
                './css/style.css',
                './js/app.js'
            ]);
        })
    );
});

self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(res => {
            return res || fetch(e.request);
        })
    );
});