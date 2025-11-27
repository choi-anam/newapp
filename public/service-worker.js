self.addEventListener('install', event => {
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  if (event.request.method === 'GET' && event.request.url.includes('/api/')) {
    // Network-first strategy untuk API requests
    event.respondWith(
      fetch(event.request).then(networkResponse => {
        return caches.open('v1').then(cache => {
          cache.put(event.request, networkResponse.clone());
          return networkResponse;
        });
      }).catch(() => {
        return caches.match(event.request);
      })
    );
  } else {
    // Cache-first strategy untuk asset statis
    event.respondWith(
      caches.open('v1').then(cache => {
        return cache.match(event.request).then(response => {
          return response || fetch(event.request).then(networkResponse => {
            if (event.request.method === 'GET' && networkResponse.ok) {
              cache.put(event.request, networkResponse.clone());
            }
            return networkResponse;
          });
        });
      })
    );
  }
});

