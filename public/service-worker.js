const PRECACHE = 'precache-v1';
const RUNTIME = 'runtime';

// A list of local resources we always want to be cached.
const PRECACHE_URLS = [
    './offline.html', // Alias for index.html
	'assets/img/falha.png'
];

// The install handler takes care of precaching the resources we always need.
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(PRECACHE)
            .then(cache => cache.addAll(PRECACHE_URLS))
            .then(self.skipWaiting())
    );
});

self.addEventListener('activate', (e) => {
    console.log('[ServiceWorker] Activated');

    e.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(cacheNames.map((thisCacheName) => {

                if (thisCacheName.includes("PRECACHE") && thisCacheName !== PRECACHE) {
                    console.log('[ServiceWorker] Removing cached files from old cache - ', thisCacheName);
                    return caches.delete(thisCacheName);
                }

            }));
        }) // end caches.keys()
    ); // end e.waitUntil
});

self.addEventListener('fetch', (e) => {
    e.respondWith(
        caches.match(e.request).then((cachedResponse) => {

            if (cachedResponse) {
                console.log("Found in cache!")
                return cachedResponse;
            }

            return fetch(e.request)
                .then((fetchResponse) => fetchResponse)
                .catch((err) => {
                    const isHTMLPage = e.request.method == "GET" && e.request.headers.get("accept").includes("text/html");
                    if (isHTMLPage) return caches.match("./offline.html");
                });

        })
    );
});