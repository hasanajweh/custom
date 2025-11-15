<?php

namespace App\Logging;

use App\Models\FileSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    /**
     * Log failed login attempt
     *
     * @param string $email
     * @param string $ip
     * @return void
     */
    public static function logFailedLogin(string $email, string $ip): void
    {
        Log::channel('security')->warning('Failed login attempt', [
            'email' => $email,
            'ip' => $ip,
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Log successful login
     *
     * @param User $user
     * @return void
     */
    public static function logSuccessfulLogin(User $user): void
    {
        Log::channel('security')->info('Successful login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'school_id' => $user->school_id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Log suspicious activity
     *
     * @param string $activity
     * @param array $context
     * @return void
     */
    public static function logSuspiciousActivity(string $activity, array $context = []): void
    {
        Log::channel('security')->alert('Suspicious activity detected', array_merge([
            'activity' => $activity,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String()
        ], $context));
    }

    /**
     * Log file operation
     *
     * @param string $operation
     * @param FileSubmission $file
     * @return void
     */
    public static function logFileOperation(string $operation, FileSubmission $file): void
    {
        Log::channel('file_operations')->info($operation, [
            'file_id' => $file->id,
            'school_id' => $file->school_id,
            'user_id' => auth()->id(),
            'operation' => $operation,
            'file_path' => $file->file_path,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Log unauthorized access attempt
     *
     * @param string $resource
     * @param int|null $resourceId
     * @return void
     */
    public static function logUnauthorizedAccess(string $resource, ?int $resourceId = null): void
    {
        Log::channel('security')->warning('Unauthorized access attempt', [
            'resource' => $resource,
            'resource_id' => $resourceId,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Log tenant isolation breach attempt
     *
     * @param int $attemptedSchoolId
     * @param int $userSchoolId
     * @return void
     */
    public static function logTenantIsolationBreach(int $attemptedSchoolId, int $userSchoolId): void
    {
        Log::channel('security')->critical('Tenant isolation breach attempt', [
            'user_id' => auth()->id(),
            'user_school_id' => $userSchoolId,
            'attempted_school_id' => $attemptedSchoolId,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
