<?php

namespace App\Http\Controllers;

use App\Models\School;

abstract class TenantAwareController extends Controller
{
    /**
     * Verify that the authenticated user has access to this school
     * This is now just a helper method, not middleware
     *
     * @param School $school
     * @return void
     */
    protected function verifyTenantAccess(School $school): void
    {
        $user = auth()->user();

        // Super admins can access any school
        if ($user && $user->is_super_admin) {
            return;
        }

        // Regular users must belong to the school
        if (!$user || $user->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this school.');
        }
    }
}
