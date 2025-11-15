<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating the given user.
     */
    public function start(User $user)
    {
        // You cannot impersonate another super admin.
        if ($user->is_super_admin) {
            abort(403);
        }

        // Store your original Super Admin ID in the session.
        session(['impersonator_id' => Auth::id()]);

        // Log in as the target user.
        Auth::login($user);

        // Redirect to their dashboard.
        return redirect()->to(tenant_route('dashboard', $user->school));
    }

    /**
     * Stop impersonating and return to the Super Admin account.
     */
    public function stop()
    {
        // Get your original ID from the session.
        $impersonatorId = session('impersonator_id');

        // Log out the current user.
        Auth::logout();

        // Remove the impersonator ID from the session.
        session()->forget('impersonator_id');

        // Log back in as the original Super Admin.
        Auth::loginUsingId($impersonatorId);

        // Redirect back to the Super Admin schools list.
        return redirect()->route('superadmin.schools.index');
    }
}
