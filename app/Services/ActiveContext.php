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

        $user = Auth::user();

        if ($schoolId) {
            $school = School::with('network')->find($schoolId);

            // Ensure the active school still belongs to the authenticated user
            if ($school && $user && $user->schoolUserRoles()
                ->where('school_id', $schoolId)
                ->exists()) {
                return $school;
            }
        }

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
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $schoolId = Session::get('active_school_id');
        $role = Session::get('active_role');

        $roleQuery = $user->schoolUserRoles();

        if ($schoolId) {
            $roleQuery->where('school_id', $schoolId);
        }

        if ($role && (clone $roleQuery)->where('role', $role)->exists()) {
            return $role;
        }

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

            if ($context->school) {
                self::setSchool($context->school->id);
            }

            return $context->role;
        }

        return null;
    }

    public static function resolveRoleForSchool(School $school): ?string
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $currentRole = Session::get('active_role');

        $hasRoleForSchool = $currentRole && $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $currentRole)
            ->exists();

        if ($hasRoleForSchool) {
            return $currentRole;
        }

        $derivedRole = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->value('role');

        if ($derivedRole) {
            self::setRole($derivedRole);
            self::setSchool($school->id);

            return $derivedRole;
        }

        return null;
    }

    public static function ensureSchoolContext(School $school): ?string
    {
        self::setSchool($school->id);

        return self::resolveRoleForSchool($school);
    }

    public static function clear(): void
    {
        Session::forget(['active_school_id', 'active_role']);
    }
}
