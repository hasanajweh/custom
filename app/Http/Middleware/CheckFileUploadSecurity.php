<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CheckFileUploadSecurity
{
    protected array $dangerousExtensions = [
        'exe', 'scr', 'bat', 'cmd', 'com', 'pif', 'application', 'gadget',
        'msi', 'msp', 'msc', 'vb', 'vbs', 'vbe', 'js', 'jse', 'ws', 'wsf',
        'wsc', 'wsh', 'ps1', 'ps1xml', 'ps2', 'ps2xml', 'psc1', 'psc2',
        'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml', 'scf', 'lnk',
        'inf', 'reg', 'dll', 'app', 'jar', 'jsp', 'php', 'asp', 'aspx', 'htaccess',
    ];

    protected array $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if (!$this->isValidFile($file)) {
                return response()->json([
                    'error' => 'Invalid or potentially dangerous file detected.',
                ], 422);
            }
        }

        return $next($request);
    }

    protected function isValidFile(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, $this->dangerousExtensions, true)) {
            return false;
        }

        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            return false;
        }

        if (in_array($mimeType, ['text/plain', 'text/html', 'text/xml'], true)) {
            $content = file_get_contents($file->getPathname());
            if ($this->containsPHPCode($content)) {
                return false;
            }
        }

        if ($file->getSize() > 104_857_600) {
            return false;
        }

        return true;
    }

    protected function containsPHPCode(string $content): bool
    {
        $dangerousPatterns = [
            '/<\?php/i',
            '/<\?=/i',
            '/<\?/i',
            '/eval\s*\(/i',
            '/base64_decode\s*\(/i',
            '/shell_exec\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/passthru\s*\(/i',
            '/`.*`/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }
}