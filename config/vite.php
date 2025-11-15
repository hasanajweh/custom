<?php

return [
    // ✅ This matches the actual path where your manifest.json is being built
    'build_path' => 'build/',

    // Dev server config (ignored in production)
    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
        'enabled' => env('VITE_DEV_SERVER_ENABLED', false),
        ],
];
