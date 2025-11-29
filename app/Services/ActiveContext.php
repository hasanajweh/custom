<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActiveContext
{
    public static function getSchool(): ?School
    {
        $schoolId = Session::get('active_school_id');

        if ($schoolId) {
            return School::with('network')->find($schoolId);
        }

        $user = Auth::user();
        if (! $user) {
            return null;
        }

        if ($user->school_id) {
            $school = School::with('network')->find($user->school_id);
            if ($school) {
                self::setSchool($school->id);
                return $school;
            }
        }

        $context = $user->availableContexts()->first();
        if ($context && $context->school) {
            self::setSchool($context->school->id);
            return $context->school;
        }

        return null;
    }

    public static function setSchool(int $schoolId): void
    {
        Session::put('active_school_id', $schoolId);
    }

    public static function getRole(): ?string
    {
        $role = Session::get('active_role');

        if ($role) {
            return $role;
        }

        $user = Auth::user();
        if (! $user) {
            return null;
        }

        if (! empty($user->role)) {
            self::setRole($user->role);
            return $user->role;
        }

        $context = $user->availableContexts()->first();
        if ($context && $context->role) {
            self::setRole($context->role);
            return $context->role;
        }

        return null;
    }

    public static function setRole(string $role): void
    {
        Session::put('active_role', $role);
    }

    public static function clear(): void
    {
        Session::forget(['active_school_id', 'active_role']);
    }
}
