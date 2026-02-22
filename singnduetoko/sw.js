const CACHE_NAME = "tokodaffa-admin-v1";
const urlsToCache = [
  "/singnduetoko/",
  "/singnduetoko/index.php",
  "/singnduetoko/dashboard.php",
  "/singnduetoko/products.php",
  "/singnduetoko/products_create.php",
  "/singnduetoko/products_edit.php",
  "/singnduetoko/hero.php",
  "/singnduetoko/tentang.php",
  "/singnduetoko/kontak.php",
  "/src/css/style.css",
  "https://cdn.tailwindcss.com",
  "https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js",
  "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css",
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log("Cache opened");
      return cache.addAll(urlsToCache);
    }),
  );
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      if (response) {
        return response;
      }
      return fetch(event.request);
    }),
  );
});

self.addEventListener("activate", (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        }),
      );
    }),
  );
});
