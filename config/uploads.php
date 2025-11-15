<?php

$maxSizeMb = (int) env('UPLOAD_MAX_SIZE_MB', 100);

return [
    'max_size_mb' => $maxSizeMb,
    'max_size_kb' => $maxSizeMb * 1024,
    'max_size_bytes' => $maxSizeMb * 1024 * 1024,
];
