<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $schoolSlug = $request->route('school');
        $school = is_string($schoolSlug)
            ? School::where('slug', $schoolSlug)->firstOrFail()
            : $schoolSlug;

        return view('profile.edit', compact('school'));
    }

    public function update(Request $request)
    {
        $schoolSlug = $request->route('school');
        $school = is_string($schoolSlug)
            ? School::where('slug', $schoolSlug)->firstOrFail()
            : $schoolSlug;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.edit', ['school' => $school->slug])
            ->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $schoolSlug = $request->route('school');
        $school = is_string($schoolSlug)
            ? School::where('slug', $schoolSlug)->firstOrFail()
            : $schoolSlug;

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.edit', ['school' => $school->slug])
            ->with('success', 'Password updated successfully.');
    }
}
