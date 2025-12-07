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
        $user = Auth::user();
        
        // Main admin exception: verify school belongs to their network
        if ($user && $user->isMainAdmin()) {
            $school = School::find($id);
            if (!$school) {
                throw new InvalidArgumentException('School not found.');
            }
            if ($school->network_id !== $user->network_id) {
                throw new InvalidArgumentException('School does not belong to your network.');
            }
        }
        
        Session::put('active_school_id', $id);
        Session::forget('active_role');
    }

    public static function getSchool(): ?School
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        // Main admin exception: can access any school in their network
        $isMainAdmin = $user->isMainAdmin();

        $schoolId = Session::get('active_school_id');

        if ($schoolId) {
            $school = School::with('network')->find($schoolId);

            if ($school) {
                // Main admin: check if school belongs to their network
                if ($isMainAdmin) {
                    if ($school->network_id === $user->network_id) {
                        return $school;
                    }
                } 
                // Regular user: check if they have a role in this school
                elseif ($user->schoolUserRoles()->where('school_id', $schoolId)->exists()) {
                    return $school;
                }
            }

            Session::forget('active_school_id');
        }

        // Main admin: return first school in their network if no active school set
        if ($isMainAdmin && $user->network_id) {
            $school = School::where('network_id', $user->network_id)->first();
            if ($school) {
                self::setSchool($school->id);
                return $school;
            }
        }

        // Regular user: get from schoolUserRoles
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

        // Main admin exception: can set any role for any school in their network
        $isMainAdmin = $user->isMainAdmin();
        
        if ($isMainAdmin) {
            // Verify school belongs to main admin's network
            if ($school->network_id !== $user->network_id) {
                throw new InvalidArgumentException('School does not belong to your network.');
            }
            // Main admin can set any role (admin, teacher, supervisor) for viewing purposes
            Session::put('active_role', $role);
            return;
        }

        // Regular user: must have the role in this school
        $hasRole = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $role)
            ->exists();

        if (! $hasRole) {
            throw new InvalidArgumentException('User does not have the requested role for the active school.');
        }

        Session::put('active_role', $role);
    }

    public static function setContext(int $schoolId, string $role): void
    {
        self::setSchool($schoolId);
        self::setRole($role);
    }

    public static function clearRole(): void
    {
        Session::forget('active_role');
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

        // Main admin exception: return session role if set, or default to 'admin'
        $isMainAdmin = $user->isMainAdmin();
        
        if ($isMainAdmin) {
            $role = Session::get('active_role');
            if ($role) {
                return $role;
            }
            // Default to 'admin' for main admin viewing schools
            Session::put('active_role', 'admin');
            return 'admin';
        }

        // Regular user: check if they have the role
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

        // Main admin exception: can use any role for schools in their network
        $isMainAdmin = $user->isMainAdmin();
        
        if ($isMainAdmin) {
            if ($school->network_id === $user->network_id) {
                $currentRole = Session::get('active_role');
                return $currentRole ?: 'admin'; // Default to admin for main admin
            }
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
