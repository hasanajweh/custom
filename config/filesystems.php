<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // ============================================
        // PRIMARY S3 CONFIGURATION
        // ============================================
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'eu-north-1'),
            'bucket' => env('AWS_BUCKET', 'scholder'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),

            // S3-specific optimizations
            'visibility' => 'private', // Files are private by default
            'throw' => true, // Throw exceptions on errors for debugging
            'options' => [
                'CacheControl' => 'max-age=31536000', // 1 year cache
                'ServerSideEncryption' => 'AES256', // Encrypt files at rest
            ],

            // Streaming support for large files
            'stream_reads' => true,
        ],

        // Temporary uploads disk (before processing)
        'temp' => [
            'driver' => 'local',
            'root' => storage_path('app/temp'),
            'throw' => false,
            'report' => false,
        ],

        // ============================================
        // TENANT-SPECIFIC DISKS (Dynamically Created)
        // ============================================
        // These are registered at runtime by TenantStorageService
        // Format: s3_tenant_{slug} or local_tenant_{slug}

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
