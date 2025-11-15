<?php

namespace App\Traits;

use App\Models\FileSubmission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HandlesS3Storage
{
    /**
     * Generate a professional filename for download/preview
     * ✅ NOW SUPPORTS ARABIC CHARACTERS WITH PROPER UTF-8 ENCODING
     */
    protected function generateProfessionalFilename(FileSubmission $file)
    {
        $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
        $date = $file->created_at->format('Y-m-d');

        // Get submission type in Arabic
        $typeMap = [
            'exam' => 'امتحان',
            'worksheet' => 'ورقة-عمل',
            'summary' => 'ملخص',
            'daily_plan' => 'خطة-يومية',
            'weekly_plan' => 'خطة-اسبوعية',
            'monthly_plan' => 'خطة-شهرية',
            'supervisor_upload' => 'رفع-مشرف'
        ];
        $type = $typeMap[$file->submission_type] ?? 'ملف';

        // Build filename parts - KEEP ARABIC AS IS
        $parts = [$type];

        // Add subject if exists - NO TRANSLITERATION
        if ($file->subject && $file->subject->name) {
            $parts[] = $this->cleanArabicText($file->subject->name);
        }

        // Add grade if exists - NO TRANSLITERATION
        if ($file->grade && $file->grade->name) {
            $parts[] = $this->cleanArabicText($file->grade->name);
        }

        // Add teacher name - NO TRANSLITERATION
        if ($file->user && $file->user->name) {
            $parts[] = $this->cleanArabicText($file->user->name);
        }

        // Add date
        $parts[] = $date;

        // Add random ID for uniqueness
        $parts[] = substr($file->file_hash ?? uniqid(), 0, 6);

        // Combine parts with underscores
        $filename = implode('_', $parts) . '.' . $extension;

        // Clean up double underscores
        $filename = preg_replace('/_+/', '_', $filename);
        $filename = trim($filename, '_');

        return $filename;
    }

    /**
     * ✅ NEW: Clean Arabic text for filenames
     * Removes invalid characters but KEEPS Arabic
     */
    protected function cleanArabicText($text)
    {
        // Remove only problematic characters for filenames
        // Keep: Arabic, English, numbers, dash, underscore
        $cleaned = preg_replace('/[^\p{Arabic}\p{L}\p{N}\-_\s]/u', '', $text);

        // Replace spaces with dashes
        $cleaned = preg_replace('/\s+/', '-', $cleaned);

        // Remove multiple dashes
        $cleaned = preg_replace('/-+/', '-', $cleaned);

        // Trim dashes from start/end
        $cleaned = trim($cleaned, '-');

        return $cleaned;
    }

    /**
     * ✅ NEW: Encode filename for RFC 5987 (supports UTF-8/Arabic)
     */
    protected function encodeFilenameForDownload($filename)
    {
        // ASCII fallback for older browsers
        $asciiFilename = $this->convertToAscii($filename);

        // UTF-8 encoded filename for modern browsers (RFC 5987)
        $utf8Filename = rawurlencode($filename);

        // Return both formats for maximum compatibility
        return sprintf(
            'attachment; filename="%s"; filename*=UTF-8\'\'%s',
            $asciiFilename,
            $utf8Filename
        );
    }

    /**
     * ✅ NEW: Convert to ASCII for fallback
     */
    protected function convertToAscii($filename)
    {
        // Get extension
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);

        // Replace Arabic/special chars with safe ASCII
        $ascii = preg_replace('/[^\x20-\x7E]/', '_', $nameWithoutExt);

        // Clean up
        $ascii = preg_replace('/_+/', '_', $ascii);
        $ascii = trim($ascii, '_');

        // If empty after conversion, use generic name
        if (empty($ascii)) {
            $ascii = 'file_' . uniqid();
        }

        return $ascii . '.' . $extension;
    }

    /**
     * MAIN METHOD: Download File
     * This is the CORE method that handles ALL download logic
     */
    protected function downloadFile(FileSubmission $file)
    {
        // Update stats
        $file->increment('download_count');
        $file->update(['last_accessed_at' => now()]);

        // Generate professional filename
        $professionalFilename = $this->generateProfessionalFilename($file);

        // Log attempt
        Log::info('🚀 Download Initiated', [
            'file_id' => $file->id,
            'file_path' => $file->file_path,
            'original_filename' => $file->original_filename,
            'professional_filename' => $professionalFilename,
            'mime_type' => $file->mime_type,
            'file_size' => $file->file_size,
            'disk' => config('filesystems.default')
        ]);

        try {
            // Check if using S3
            if (config('filesystems.default') === 's3') {
                return $this->downloadFromS3($file, $professionalFilename);
            }

            // Local storage fallback
            return $this->downloadFromLocal($file, $professionalFilename);

        } catch (\Exception $e) {
            Log::error('❌ Download Failed', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'download' => 'Unable to download file. Please try again or contact support.'
            ]);
        }
    }

    /**
     * ✅ UPDATED: Download from S3 with UTF-8 encoding
     */
    protected function downloadFromS3(FileSubmission $file, $filename)
    {
        // Check if file exists first
        if (!Storage::disk('s3')->exists($file->file_path)) {
            Log::error('❌ File not found in S3', [
                'file_id' => $file->id,
                'file_path' => $file->file_path,
                'bucket' => config('filesystems.disks.s3.bucket')
            ]);

            return back()->withErrors([
                'download' => 'File not found in storage. Please contact support.'
            ]);
        }

        Log::info('✅ File exists in S3, generating signed URL', [
            'file_id' => $file->id,
            'file_path' => $file->file_path
        ]);

        // ✅ FIXED: Properly encode filename for RFC 5987
        $encodedFilename = $this->encodeFilenameForDownload($filename);

        // Generate temporary signed URL with proper encoding
        $url = Storage::disk('s3')->temporaryUrl(
            $file->file_path,
            now()->addMinutes(5),
            [
                'ResponseContentDisposition' => $encodedFilename,
                'ResponseContentType' => $file->mime_type,
                'ResponseCacheControl' => 'no-cache, no-store, must-revalidate'
            ]
        );

        Log::info('🔗 S3 Signed URL Generated', [
            'file_id' => $file->id,
            'url_length' => strlen($url),
            'url_preview' => substr($url, 0, 100) . '...',
            'expires_in' => '5 minutes'
        ]);

        return redirect()->away($url);
    }

    /**
     * ✅ UPDATED: Download from local storage with proper headers
     */
    protected function downloadFromLocal(FileSubmission $file, $filename)
    {
        Log::info('📂 Downloading from local storage', [
            'file_id' => $file->id,
            'file_path' => $file->file_path
        ]);

        if (!Storage::disk('local')->exists($file->file_path)) {
            Log::error('❌ File not found in local storage', [
                'file_id' => $file->id,
                'file_path' => $file->file_path
            ]);

            return back()->withErrors([
                'download' => 'File not found in storage.'
            ]);
        }

        // ✅ FIXED: Use proper headers for Arabic filenames
        $headers = [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => $this->encodeFilenameForDownload($filename),
        ];

        return response()->download(
            Storage::disk('local')->path($file->file_path),
            $filename,
            $headers
        );
    }

    /**
     * ✅ UPDATED: PREVIEW METHOD with UTF-8 encoding
     */
    protected function getFileUrl(FileSubmission $file, $expiryMinutes = 30)
    {
        $professionalFilename = $this->generateProfessionalFilename($file);

        Log::info('👁️ Preview Initiated', [
            'file_id' => $file->id,
            'file_path' => $file->file_path,
            'expiry_minutes' => $expiryMinutes
        ]);

        try {
            if (config('filesystems.default') === 's3') {
                // Check if file exists
                if (!Storage::disk('s3')->exists($file->file_path)) {
                    Log::error('❌ File not found for preview', [
                        'file_id' => $file->id,
                        'file_path' => $file->file_path
                    ]);

                    throw new \Exception('File not found in storage');
                }

                // ✅ FIXED: Use proper encoding for preview
                $encodedFilename = sprintf(
                    'inline; filename="%s"; filename*=UTF-8\'\'%s',
                    $this->convertToAscii($professionalFilename),
                    rawurlencode($professionalFilename)
                );

                $url = Storage::disk('s3')->temporaryUrl(
                    $file->file_path,
                    now()->addMinutes($expiryMinutes),
                    [
                        'ResponseContentDisposition' => $encodedFilename,
                        'ResponseContentType' => $file->mime_type,
                        'ResponseCacheControl' => 'public, max-age=3600'
                    ]
                );

                Log::info('✅ Preview URL Generated', [
                    'file_id' => $file->id,
                    'url_length' => strlen($url)
                ]);

                return $url;
            }

            // Local storage fallback
            return url(Storage::disk('local')->url($file->file_path));

        } catch (\Exception $e) {
            Log::error('❌ Preview URL Generation Failed', [
                'file_id' => $file->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Store a file to the configured disk
     */
    protected function storeFile($file, $schoolSlug, $submissionType)
    {
        $disk = config('filesystems.default', 's3');
        $path = $schoolSlug . '/' . $submissionType;

        Log::info('📤 Storing file', [
            'disk' => $disk,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize()
        ]);

        $storedPath = Storage::disk($disk)->putFile($path, $file);

        Log::info('✅ File stored successfully', [
            'stored_path' => $storedPath
        ]);

        return $storedPath;
    }

    /**
     * Delete a file from storage
     */
    protected function deleteFile($path)
    {
        $disk = config('filesystems.default', 's3');

        Log::info('🗑️ Deleting file', [
            'disk' => $disk,
            'path' => $path
        ]);

        $result = Storage::disk($disk)->delete($path);

        if ($result) {
            Log::info('✅ File deleted successfully', ['path' => $path]);
        } else {
            Log::warning('⚠️ File deletion failed or file not found', ['path' => $path]);
        }

        return $result;
    }

    /**
     * Check if file exists
     */
    protected function fileExists($path)
    {
        $disk = config('filesystems.default', 's3');
        $exists = Storage::disk($disk)->exists($path);

        Log::debug('🔍 File existence check', [
            'path' => $path,
            'exists' => $exists
        ]);

        return $exists;
    }

    /**
     * Check if file can be previewed in browser
     */
    protected function canPreviewInBrowser($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt'];
        return in_array($extension, $previewableTypes);
    }

    /**
     * Get file extension from filename
     */
    protected function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Get file size in human readable format
     */
    protected function getHumanFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}
