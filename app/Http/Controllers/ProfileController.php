<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\Network;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    private function validateContext(Network $network, School $branch): School
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        if (Auth::user()->school_id !== $branch->id) {
            abort(403);
        }

        return $branch;
    }

    public function edit(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        return view('profile.edit', compact('school'));
    }

    public function update(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->to(tenant_route('profile.edit', $school))
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->to(tenant_route('profile.edit', $school))
            ->with('success', 'Password updated successfully.');
    }

    public function updateLanguage(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $request->validate([
            'locale' => ['required', 'in:en,ar'],
        ]);

        $user = Auth::user();
        $user->update(['locale' => $request->locale]);

        app()->setLocale($request->locale);
        session()->put('locale', $request->locale);

        $fallback = url()->previous() ?: tenant_route('profile.edit', $school);

        return redirect()->to($fallback)
            ->with('success', __('messages.language_switched', ['language' => $request->locale]))
            ->with('locale', $request->locale);
    }
}
