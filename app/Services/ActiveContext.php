<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class ActiveContext
{
    public static function setSchool(int $id): void
    {
        Session::put('active_school_id', $id);
        Session::forget('active_role');
    }

    public static function getSchool(): ?School
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $schoolId = Session::get('active_school_id');

        if ($schoolId) {
            $school = School::with('network')->find($schoolId);

            if ($school && $user->schoolUserRoles()->where('school_id', $schoolId)->exists()) {
                return $school;
            }

            Session::forget('active_school_id');
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
        $user = Auth::user();
        $school = self::getSchool();

        if (! $user || ! $school) {
            throw new InvalidArgumentException('Cannot set role without an active school or user.');
        }

        $hasRole = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $role)
            ->exists();

        if (! $hasRole) {
            throw new InvalidArgumentException('User does not have the requested role for the active school.');
        }

        Session::put('active_role', $role);
    }

    public static function getRole(): ?string
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $activeSchool = self::getSchool();

        if (! $activeSchool) {
            Session::forget('active_role');

            return null;
        }

        $role = Session::get('active_role');

        if ($role && $user->schoolUserRoles()
            ->where('school_id', $activeSchool->id)
            ->where('role', $role)
            ->exists()) {
            return $role;
        }

        Session::forget('active_role');

        $context = $user->schoolUserRoles()
            ->where('school_id', $activeSchool->id)
            ->first();

        if ($context) {
            Session::put('active_role', $context->role);

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

        if ($currentRole && $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $currentRole)
            ->exists()) {
            return $currentRole;
        }

        $derivedRole = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->value('role');

        if ($derivedRole) {
            Session::put('active_role', $derivedRole);

            return $derivedRole;
        }

        return null;
    }

    public static function ensureSchoolContext(School $school): ?string
    {
        $activeSchool = self::getSchool();

        if (! $activeSchool || $activeSchool->id !== $school->id) {
            self::setSchool($school->id);
        }

        return self::resolveRoleForSchool($school);
    }

    public static function clear(): void
    {
        Session::forget(['active_school_id', 'active_role']);
    }
}
