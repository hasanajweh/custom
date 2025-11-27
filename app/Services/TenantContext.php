<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Support\SchoolResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TenantContext
{
    protected static ?School $resolvedSchool = null;
    protected static ?string $resolvedRole = null;

    public static function currentSchool(): ?School
    {
        if (self::$resolvedSchool) {
            return self::$resolvedSchool;
        }

        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $schoolId = Session::get('active_school_id');

        if ($schoolId && $user->schoolUserRoles()->where('school_id', $schoolId)->exists()) {
            return self::$resolvedSchool = School::find($schoolId);
        }

        $routeSchool = request()->route('branch') ?? request()->route('school');
        $candidateSchool = SchoolResolver::resolve($routeSchool);

        if ($candidateSchool && $user->schoolUserRoles()->where('school_id', $candidateSchool->id)->exists()) {
            return self::$resolvedSchool = $candidateSchool;
        }

        if ($user->school_id && $user->schoolUserRoles()->where('school_id', $user->school_id)->exists()) {
            return self::$resolvedSchool = $user->school;
        }

        $firstAssignment = $user->schoolUserRoles()->with('school')->first();

        return self::$resolvedSchool = $firstAssignment?->school;
    }

    public static function currentRole(): ?string
    {
        if (self::$resolvedRole) {
            return self::$resolvedRole;
        }

        $user = Auth::user();
        $school = self::currentSchool();

        if (!$user || !$school) {
            return null;
        }

        $availableRoles = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->pluck('role');

        $sessionRole = Session::get('active_role');

        if ($sessionRole && $availableRoles->contains($sessionRole)) {
            return self::$resolvedRole = $sessionRole;
        }

        $priority = ['admin', 'supervisor', 'teacher'];

        foreach ($priority as $role) {
            if ($availableRoles->contains($role)) {
                return self::$resolvedRole = $role;
            }
        }

        return self::$resolvedRole = $availableRoles->first();
    }

    public static function availableContextsForUser(User $user): Collection
    {
        return $user->schoolUserRoles()
            ->with(['school.network'])
            ->get()
            ->map(function ($assignment) {
                return [
                    'school' => $assignment->school,
                    'role' => $assignment->role,
                ];
            });
    }

    public static function setActiveContext(int $schoolId, string $role): void
    {
        Session::put('active_school_id', $schoolId);
        Session::put('active_role', $role);
        self::$resolvedSchool = null;
        self::$resolvedRole = null;
    }
}
