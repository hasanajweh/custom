<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    use ResolvesSchoolFromRequest;

    public function edit(Request $request)
    {
        $school = $this->resolveSchool($request);

        return view('profile.edit', compact('school'));
    }

    public function update(Request $request)
    {
        $school = $this->resolveSchool($request);

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

    public function updatePassword(Request $request)
    {
        $school = $this->resolveSchool($request);

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

    public function updateLanguage(Request $request)
    {
        $school = $this->resolveSchool($request);

        $request->validate([
            'locale' => ['required', 'in:en,ar'],
        ]);

        $user = Auth::user();
        $user->update(['locale' => $request->locale]);

        app()->setLocale($request->locale);
        session()->put('locale', $request->locale);

        return redirect()->to(tenant_route('profile.edit', $school))
            ->with('success', __('messages.language_switched', ['language' => $request->locale]));
    }
}
