<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\School;

class SecureFileUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    private const DANGEROUS_EXTENSIONS = [
        'exe', 'scr', 'bat', 'cmd', 'com', 'pif', 'application', 'gadget',
        'msi', 'msp', 'msc', 'vb', 'vbs', 'vbe', 'js', 'jse', 'ws', 'wsf',
        'wsc', 'wsh', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2',
        'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml', 'scf', 'lnk',
        'inf', 'reg', 'dll', 'app', 'jar', 'jsp', 'php', 'asp', 'aspx', 'htaccess'
    ];

    /**
     * Validate uploaded file for security issues
     *
     * @param UploadedFile $file
     * @return array
     */
    public function validateFile(UploadedFile $file): array
    {
        $errors = [];

        // 1. Check actual MIME type (not just extension)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $actualMime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (!in_array($actualMime, self::ALLOWED_MIME_TYPES)) {
            $errors[] = "Invalid file type. Detected: {$actualMime}. Only PDF and Office documents are allowed.";
        }

        // 2. File size check removed - unlimited file size

        // 3. Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            $errors[] = "Dangerous file extension detected: {$extension}";
        }

        // 4. Scan for embedded executables in Office documents
        if ($this->isOfficeDocument($actualMime)) {
            if ($this->containsMacros($file)) {
                $errors[] = 'Files with macros are not allowed for security reasons.';
            }
        }

        // 5. Check for double extensions
        if (preg_match('/\.[^.]+\.[^.]+$/', $file->getClientOriginalName())) {
            $errors[] = 'Double file extensions are not allowed.';
        }

        // 6. Check for null bytes
        if (strpos($file->getClientOriginalName(), "\0") !== false) {
            $errors[] = 'Invalid filename detected.';
        }

        // 7. Sanitize filename
        $sanitized = $this->sanitizeFilename($file->getClientOriginalName());
        if ($sanitized !== $file->getClientOriginalName()) {
            Log::warning('Suspicious filename detected and sanitized', [
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
    // Removed getMaxFileSize() - unlimited file size

    /**
     * Check if file is an Office document
     *
     * @param string $mime
     * @return bool
     */
    private function isOfficeDocument(string $mime): bool
    {
        return str_contains($mime, 'officedocument') ||
            str_contains($mime, 'msword') ||
            str_contains($mime, 'ms-excel') ||
            str_contains($mime, 'ms-powerpoint');
    }

    /**
     * Check if Office document contains macros
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function containsMacros(UploadedFile $file): bool
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($file->getRealPath()) === true) {
                // Check for vbaProject.bin which indicates macros
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

    /**
     * Sanitize filename
     *
     * @param string $filename
     * @return string
     */
    private function sanitizeFilename(string $filename): string
    {
        // Get extension first
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Remove null bytes
        $basename = str_replace("\0", '', $basename);

        // Allow only safe characters
        $basename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $basename);

        // Prevent hidden files
        $basename = ltrim($basename, '.');

        // Limit length
        $basename = substr($basename, 0, 200);

        return $basename . '.' . $extension;
    }

    /**
     * Store file securely
     *
     * @param UploadedFile $file
     * @param School $school
     * @return array
     * @throws \InvalidArgumentException
     */
    public function storeSecurely(UploadedFile $file, School $school): array
    {
        $validation = $this->validateFile($file);

        if (!$validation['valid']) {
            throw new \InvalidArgumentException(
                'File validation failed: ' . implode(', ', $validation['errors'])
            );
        }

        // Generate unique filename with timestamp
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(32) . '.' . $extension;

        // Store in private disk
        $path = $file->storeAs(
            "schools/{$school->id}/submissions",
            $filename,
            'local' // Change to 's3' or 'private' in production
        );

        return [
            'path' => $path,
            'original_name' => $validation['sanitized_name'],
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'hash' => $validation['hash']
        ];
    }

    /**
     * Delete file securely
     *
     * @param string $path
     * @return bool
     */
    public function deleteSecurely(string $path): bool
    {
        try {
            return Storage::disk('local')->delete($path);
        } catch (\Exception $e) {
            Log::error('Failed to delete file', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
