self.addEventListener('install', event => {
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Network-first untuk API dan halaman HTML dinamis
  if (event.request.method === 'GET' && (
    url.pathname.includes('/api/') ||
    url.pathname.includes('/admin/') ||
    url.pathname.includes('/activities') ||
    url.pathname.endsWith('.html') ||
    !url.pathname.includes('.')
  )) {
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
    // Cache-first strategy untuk asset statis (CSS, JS, images)
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

