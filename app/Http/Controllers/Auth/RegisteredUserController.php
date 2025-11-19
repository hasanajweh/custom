<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School; // <-- Make sure this is imported
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Network $network, School $school): View
    {
        abort_unless($school->network_id === $network->id, 404);

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request, Network $network, School $school)
    {
        abort_unless($school->network_id === $network->id, 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,NULL,id,school_id,'.$school->id],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $school->users()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(tenant_route('dashboard', $school));
    }
}