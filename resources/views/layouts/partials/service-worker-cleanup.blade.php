<script>
(function() {
    if (!('serviceWorker' in navigator)) {
        return;
    }

    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        registrations.forEach(function(registration) {
            registration.unregister();
        });
    });

    if ('caches' in window) {
        caches.keys().then(function(keys) {
            return Promise.all(keys.map(function(key) {
                return caches.delete(key);
            }));
        });
    }
})();
</script>
