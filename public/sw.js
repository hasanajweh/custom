// Enhanced Service Worker for Scholder PWA - Production Ready
// Version: 5.1.0 - Fixed navigation freezing issue

const CACHE_NAME = 'scholder-v5.1';
const DYNAMIC_CACHE = 'scholder-dynamic-v5.1';
const IMAGE_CACHE = 'scholder-images-v5.1';

// Critical assets to cache immediately
const STATIC_ASSETS = [
    '/',
    '/offline.html',
    '/WayUp.png',
    '/Scholder-192.png',
    '/Scholder-512.png',
];

// ========================================
// INSTALL - Cache Static Assets
// ========================================
self.addEventListener('install', event => {
    console.log('[SW v5.1] Installing...');

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('[SW v5.1] Caching static assets');
                return Promise.allSettled(
                    STATIC_ASSETS.map(url => {
                        return cache.add(new Request(url, {
                            cache: 'reload',
                            credentials: 'same-origin'
                        })).catch(err => {
                            console.warn('[SW v5.1] Failed to cache:', url, err);
                        });
                    })
                );
            })
            .then(() => {
                console.log('[SW v5.1] Installation complete');
                return self.skipWaiting();
            })
            .catch(err => {
                console.error('[SW v5.1] Installation failed:', err);
            })
    );
});

// ========================================
// ACTIVATE - Clean Old Caches
// ========================================
self.addEventListener('activate', event => {
    console.log('[SW v5.1] Activating...');

    event.waitUntil(
        Promise.all([
            // Clean old caches
            caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames
                        .filter(cacheName => {
                            return cacheName.startsWith('scholder-') &&
                                cacheName !== CACHE_NAME &&
                                cacheName !== DYNAMIC_CACHE &&
                                cacheName !== IMAGE_CACHE;
                        })
                        .map(cacheName => {
                            console.log('[SW v5.1] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        })
                );
            }),
            // Take control of all clients immediately
            self.clients.claim()
        ])
            .then(() => {
                console.log('[SW v5.1] Activation complete');
            })
    );
});

// ========================================
// FETCH - Network First with Smart Caching
// ========================================
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-HTTP(S) requests (chrome-extension://, etc.)
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // Skip WebSocket upgrades
    if (request.headers.get('upgrade') === 'websocket') {
        return;
    }

    // Handle HTML navigation requests - ONLY when offline to prevent freezing
    if (request.mode === 'navigate') {
        // Let navigation pass through normally when online
        // Only intercept when we detect we're offline
        event.respondWith(handleNavigationRequest(request));
        return;
    }

    // Handle image requests
    if (request.destination === 'image') {
        event.respondWith(handleImageRequest(request));
        return;
    }

    // Handle API and POST requests
    if (url.pathname.includes('/api/') || request.method !== 'GET') {
        event.respondWith(handleApiRequest(request));
        return;
    }

    // Handle all other requests (CSS, JS, fonts, etc.)
    event.respondWith(handleResourceRequest(request));
});

// Navigation Request Handler (HTML pages)
async function handleNavigationRequest(request) {
    try {
        // Try network first - use no-cache to ensure fresh content
        const networkResponse = await fetch(request, {
            cache: 'no-store',
            credentials: 'same-origin'
        });

        // If network succeeds, return immediately and cache in background
        if (networkResponse && networkResponse.ok) {
            // Cache in background without blocking the response
            const responseClone = networkResponse.clone();
            caches.open(DYNAMIC_CACHE).then(cache => {
                // Only cache if response is HTML
                const contentType = networkResponse.headers.get('content-type') || '';
                if (contentType.includes('text/html')) {
                    cache.put(request, responseClone).catch(() => {
                        // Silently fail - caching is not critical
                    });
                }
            }).catch(() => {
                // Silently fail background operations
            });

            return networkResponse;
        }

        // If response not ok, try cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        return networkResponse;
    } catch (error) {
        // Network failed - we're offline, try cache
        console.log('[SW v5.1] Network failed for navigation, trying cache');

        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Return offline page
        const offlinePage = await caches.match('/offline.html');
        return offlinePage || new Response('Offline', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
                'Content-Type': 'text/html'
            })
        });
    }
}

// Image Request Handler
async function handleImageRequest(request) {
    try {
        // Check cache first for images
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            // Update cache in background
            fetchAndCache(request, IMAGE_CACHE);
            return cachedResponse;
        }

        // Fetch from network
        const response = await fetch(request);

        // Cache if successful
        if (response && response.ok) {
            const cache = await caches.open(IMAGE_CACHE);
            cache.put(request, response.clone()).catch(err => {
                console.warn('[SW v5] Failed to cache image:', err);
            });
        }

        return response;
    } catch (error) {
        console.log('[SW v5] Image load failed:', request.url);

        // Return placeholder SVG
        return new Response(
            '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#f3f4f6"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af" font-family="sans-serif" font-size="12">Image unavailable</text></svg>',
            {
                status: 200,
                headers: new Headers({
                    'Content-Type': 'image/svg+xml',
                    'Cache-Control': 'no-cache'
                })
            }
        );
    }
}

// API Request Handler
async function handleApiRequest(request) {
    try {
        // Always try network first for API calls
        const response = await fetch(request);

        // Cache successful GET requests
        if (request.method === 'GET' && response && response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone()).catch(err => {
                console.warn('[SW v5] Failed to cache API response:', err);
            });
        }

        return response;
    } catch (error) {
        console.log('[SW v5] API request failed, checking cache');

        // Try cache for GET requests only
        if (request.method === 'GET') {
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
        }

        // Return error response
        throw error;
    }
}

// Static Resource Request Handler (CSS, JS, fonts)
async function handleResourceRequest(request) {
    const url = new URL(request.url);
    
    // For JavaScript files, always try network first to avoid stale code
    if (url.pathname.endsWith('.js') || request.headers.get('accept')?.includes('application/javascript')) {
        try {
            // Network first for JS to ensure fresh code
            const response = await fetch(request, {
                cache: 'no-store',
                credentials: 'same-origin'
            });

            if (response && response.ok) {
                // Cache in background
                const responseClone = response.clone();
                caches.open(DYNAMIC_CACHE).then(cache => {
                    cache.put(request, responseClone).catch(() => {
                        // Silently fail
                    });
                }).catch(() => {
                    // Silently fail
                });
            }

            return response;
        } catch (error) {
            // If network fails, try cache
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
            throw error;
        }
    }

    // For other resources (CSS, fonts), use cache-first strategy
    try {
        // Check cache first
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            // Update cache in background
            fetchAndCache(request, DYNAMIC_CACHE);
            return cachedResponse;
        }

        // Not in cache, fetch from network
        const response = await fetch(request);

        // Cache if successful
        if (response && response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone()).catch(err => {
                console.warn('[SW v5] Failed to cache resource:', err);
            });
        }

        return response;
    } catch (error) {
        console.log('[SW v5] Resource load failed:', request.url);

        // Try cache one more time
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        throw error;
    }
}

// Helper: Fetch and cache in background
function fetchAndCache(request, cacheName) {
    fetch(request)
        .then(response => {
            if (response && response.ok) {
                return caches.open(cacheName).then(cache => {
                    return cache.put(request, response);
                });
            }
        })
        .catch(err => {
            // Silently fail background updates
            console.warn('[SW v5] Background update failed:', err);
        });
}

// ========================================
// BACKGROUND SYNC (for offline file uploads)
// ========================================
self.addEventListener('sync', event => {
    console.log('[SW v5] Background sync triggered:', event.tag);

    if (event.tag === 'upload-pending-files') {
        event.waitUntil(syncPendingUploads());
    }
});

async function syncPendingUploads() {
    console.log('[SW v5] Syncing pending uploads...');

    try {
        // Get pending uploads from IndexedDB
        const db = await openUploadDatabase();
        const pending = await getPendingUploads(db);

        console.log(`[SW v5] Found ${pending.length} pending uploads`);

        for (const upload of pending) {
            try {
                const formData = new FormData();
                formData.append('title', upload.title);
                formData.append('file', upload.file);
                formData.append('submission_type', upload.submission_type);

                if (upload.subject_id) formData.append('subject_id', upload.subject_id);
                if (upload.grade_id) formData.append('grade_id', upload.grade_id);

                const response = await fetch(upload.url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': upload.csrf_token
                    }
                });

                if (response.ok) {
                    await removePendingUpload(db, upload.id);
                    console.log('[SW v5] Upload synced:', upload.title);
                }
            } catch (error) {
                console.error('[SW v5] Failed to sync upload:', error);
            }
        }
    } catch (error) {
        console.error('[SW v5] Sync failed:', error);
    }
}

// ========================================
// MESSAGE HANDLER
// ========================================
self.addEventListener('message', event => {
    console.log('[SW v5] Message received:', event.data);

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        event.waitUntil(
            caches.open(DYNAMIC_CACHE)
                .then(cache => cache.addAll(event.data.urls))
        );
    }

    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => caches.delete(cacheName))
                );
            })
        );
    }
});

// ========================================
// INDEXEDDB HELPERS
// ========================================
function openUploadDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('ScholderUploads', 1);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('pending')) {
                db.createObjectStore('pending', { keyPath: 'id', autoIncrement: true });
            }
        };
    });
}

function getPendingUploads(db) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['pending'], 'readonly');
        const store = transaction.objectStore('pending');
        const request = store.getAll();

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
    });
}

function removePendingUpload(db, id) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['pending'], 'readwrite');
        const store = transaction.objectStore('pending');
        const request = store.delete(id);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve();
    });
}

console.log('[SW v5.1] Service Worker loaded successfully - Navigation freezing fix applied');
