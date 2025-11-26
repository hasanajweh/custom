<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Network $network)
    {
        return view('main-admin.login', ['network' => $network]);
    }

    public function login(Request $request, Network $network)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user) {
            if ($user->isMainAdmin() && $network && $user->network_id !== $network->id) {
                throw ValidationException::withMessages([
                    'email' => __('messages.invalid_network_access'),
                ]);
            }

            // Check if user is active
            if (!$user->is_active) {
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact your school administrator.',
                ]);
            }

            // Check if user is archived (soft deleted)
            if ($user->trashed()) {
                throw ValidationException::withMessages([
                    'email' => 'This account has been archived. Please contact your school administrator.',
                ]);
            }
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login timestamp
            Auth::user()->update(['last_login_at' => now()]);

            // Redirect based on role
            return $this->redirectBasedOnRole();
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();
        $school = $user->school;

        return match($user->role) {
            'main_admin' => redirect()->route('main-admin.dashboard', ['network' => $user->network?->slug]),
            'admin' => redirect()->to(tenant_route('school.admin.dashboard', $school)),
            'teacher' => redirect()->to(tenant_route('teacher.files.index', $school)),
            'supervisor' => redirect()->to(tenant_route('supervisor.reviews.index', $school)),
            default => redirect()->route('home'),
        };
    }

    public function logout(Request $request, Network $network)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($network) {
            return redirect()->route('main-admin.login', ['network' => $network->slug]);
        }

        return redirect('/');
    }
}
