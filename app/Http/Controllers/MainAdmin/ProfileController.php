<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit(Network $network)
    {
        $user = Auth::user();
        
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }

        return view('main-admin.profile.edit', [
            'network' => $network,
            'user' => $user,
        ]);
    }

    public function update(Request $request, Network $network)
    {
        $user = Auth::user();
        
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('main-admin.profile.edit', ['network' => $network->slug])
            ->with('success', __('messages.profile.updated'));
    }

    public function updatePassword(Request $request, Network $network)
    {
        $user = Auth::user();
        
        if (!$user->isMainAdmin() || $user->network_id !== $network->id) {
            abort(403);
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('main-admin.profile.edit', ['network' => $network->slug])
            ->with('success', __('messages.profile.password_updated'));
    }
}
