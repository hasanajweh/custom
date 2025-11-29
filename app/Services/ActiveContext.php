<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActiveContext
{
    public static function setSchool(int $id): void
    {
        Session::put('active_school_id', $id);
    }

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

        $context = $user->schoolUserRoles()
            ->with('school.network')
            ->first();

        if ($context && $context->school) {
            self::setSchool($context->school->id);

            return $context->school;
        }

        return null;
    }

    public static function setRole(string $role): void
    {
        Session::put('active_role', $role);
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

        $schoolId = Session::get('active_school_id');

        if ($schoolId) {
            $context = $user->schoolUserRoles()
                ->where('school_id', $schoolId)
                ->first();

            if ($context) {
                self::setRole($context->role);

                return $context->role;
            }
        }

        $context = $user->schoolUserRoles()->first();

        if ($context) {
            self::setRole($context->role);

            return $context->role;
        }

        return null;
    }

    public static function clear(): void
    {
        Session::forget(['active_school_id', 'active_role']);
    }
}
