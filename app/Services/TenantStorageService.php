<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class TenantStorageService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'text/markdown',
        'application/json',
        'text/csv',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
    ];

    private const DANGEROUS_EXTENSIONS = [
        'exe', 'scr', 'bat', 'cmd', 'com', 'pif', 'application', 'gadget',
        'msi', 'msp', 'msc', 'vb', 'vbs', 'vbe', 'js', 'jse', 'ws', 'wsf',
        'wsc', 'wsh', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2',
        'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml', 'scf', 'lnk',
        'inf', 'reg', 'dll', 'app', 'jar', 'jsp', 'php', 'asp', 'aspx', 'htaccess'
    ];

    public static function getDiskName(?School $school = null): string
    {
        $school = $school ?? app(School::class);
        if (config('filesystems.default') === 's3') {
            return "s3_tenant_{$school->slug}";
        }
        return "local_tenant_{$school->slug}";
    }

    public static function getDisk(?School $school = null)
    {
        $school = $school ?? app(School::class);
        $diskName = self::getDiskName($school);
        if (config("filesystems.disks.{$diskName}")) {
            return Storage::disk($diskName);
        }
        self::registerTenantDisk($school);
        return Storage::disk($diskName);
    }

    public static function registerTenantDisk(School $school): void
    {
        $diskName = self::getDiskName($school);
        if (config('filesystems.default') === 's3') {
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                    'region' => config('filesystems.disks.s3.region'),
                    'bucket' => config('filesystems.disks.s3.bucket'),
                    'url' => config('filesystems.disks.s3.url'),
                    'endpoint' => config('filesystems.disks.s3.endpoint'),
                    'use_path_style_endpoint' => config('filesystems.disks.s3.use_path_style_endpoint'),
                    'root' => '',
                    'throw' => false,
                    'report' => false,
                ]
            ]);
        } else {
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 'local',
                    'root' => storage_path("app/schools/{$school->slug}"),
                    'throw' => false,
                    'report' => false,
                ]
            ]);
        }
    }

    public static function validateFile(UploadedFile $file): array
    {
        $errors = [];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $actualMime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);
        if (!in_array($actualMime, self::ALLOWED_MIME_TYPES)) {
            $errors[] = "Invalid file type detected: {$actualMime}";
        }
        $maxSize = config('uploads.max_size_bytes', 104857600);
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File too large. Maximum size is ' . config('uploads.max_size_mb', 100) . 'MB';
        }
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            $errors[] = "Dangerous file extension: {$extension}";
        }
        if (self::isOfficeDocument($actualMime)) {
            if (self::containsMacros($file)) {
                $errors[] = 'Files with macros are not allowed';
            }
        }
        if (preg_match('/\.[^.]+\.[^.]+$/', $file->getClientOriginalName())) {
            $errors[] = 'Double file extensions are not allowed';
        }
        if (strpos($file->getClientOriginalName(), "\0") !== false) {
            $errors[] = 'Invalid filename detected';
        }
        $sanitized = self::sanitizeFilename($file->getClientOriginalName());
        if ($sanitized !== $file->getClientOriginalName()) {
            Log::warning('Suspicious filename sanitized', [
                'original' => $file->getClientOriginalName(),
                'sanitized' => $sanitized,
                'ip' => request()->ip(),
                'user_id' => auth()->id()
            ]);
        }
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'sanitized_name' => $sanitized,
            'hash' => hash_file('sha256', $file->getRealPath())
        ];
    }

    public static function storeToTemp(UploadedFile $file): array
    {
        $validation = self::validateFile($file);
        if (!$validation['valid']) {
            throw new \InvalidArgumentException(
                'File validation failed: ' . implode(', ', $validation['errors'])
            );
        }
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $tempPath = $file->storeAs('temp', $filename, 'local');
        return [
            'temp_path' => $tempPath,
            'original_name' => $validation['sanitized_name'],
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'hash' => $validation['hash'],
        ];
    }

    public static function store(UploadedFile $file, string $directory = 'uploads', ?School $school = null): array
    {
        $school = $school ?? app(School::class);
        $validation = self::validateFile($file);
        if (!$validation['valid']) {
            throw new \InvalidArgumentException(
                'File validation failed: ' . implode(', ', $validation['errors'])
            );
        }
        $disk = self::getDisk($school);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;
        $stream = fopen($file->getRealPath(), 'rb');
        
        if ($stream === false) {
            throw new \RuntimeException('Unable to open file stream for upload.');
        }

        try {
            $result = $disk->writeStream($path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if ($result === false) {
            throw new \RuntimeException('Failed to write file to storage.');
        }        return [
            'path' => $path,
            'disk' => self::getDiskName($school),
            'original_name' => $validation['sanitized_name'],
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'hash' => $validation['hash'],
        ];
    }

    public static function delete(string $path, ?School $school = null): bool
    {
        $school = $school ?? app(School::class);
        $disk = self::getDisk($school);
        return $disk->delete($path);
    }

    public static function url(string $path, ?School $school = null, int $expirationMinutes = 60): string
    {
        $school = $school ?? app(School::class);
        $disk = self::getDisk($school);
        if (str_starts_with($path, "schools/{$school->slug}/")) {
            $path = str_replace("schools/{$school->slug}/", '', $path);
        }
        if (config('filesystems.default') === 's3') {
            return $disk->temporaryUrl($path, now()->addMinutes($expirationMinutes));
        }
        return $disk->url($path);
    }

    public static function exists(string $path, ?School $school = null): bool
    {
        $school = $school ?? app(School::class);
        $disk = self::getDisk($school);
        return $disk->exists($path);
    }

    public static function size(string $path, ?School $school = null): int
    {
        $school = $school ?? app(School::class);
        $disk = self::getDisk($school);
        return $disk->size($path);
    }

    public static function getTotalUsage(?School $school = null): int
    {
        $school = $school ?? app(School::class);
        $disk = self::getDisk($school);
        $totalSize = 0;
        $files = $disk->allFiles();
        foreach ($files as $file) {
            $totalSize += $disk->size($file);
        }
        return $totalSize;
    }

    public static function hasStorageSpace(int $requiredBytes, ?School $school = null): bool
    {
        $school = $school ?? app(School::class);
        $availableSpace = $school->storage_limit - $school->storage_used;
        return $availableSpace >= $requiredBytes;
    }

    public static function updateStorageUsage(int $bytes, ?School $school = null): void
    {
        $school = $school ?? app(School::class);
        $school->increment('storage_used', $bytes);
    }

    private static function isOfficeDocument(string $mime): bool
    {
        return str_contains($mime, 'officedocument') ||
            str_contains($mime, 'msword') ||
            str_contains($mime, 'ms-excel') ||
            str_contains($mime, 'ms-powerpoint');
    }

    private static function containsMacros(UploadedFile $file): bool
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                $hasMacros = $zip->locateName('vbaProject.bin') !== false;
                $zip->close();
                return $hasMacros;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to check for macros', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
        }
        return false;
    }

    private static function sanitizeFilename(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $basename = str_replace("\0", '', $basename);
        $basename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);
        $basename = ltrim($basename, '.');
        $basename = substr($basename, 0, 200);
        return $basename . '.' . $extension;
    }
}
