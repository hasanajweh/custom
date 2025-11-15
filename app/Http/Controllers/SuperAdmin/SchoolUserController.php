<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class SchoolUserController extends Controller
{
    /**
     * Display a list of all users for a given school.
     */
    public function index(Request $request, School $school)
    {
        $query = $school->users(); // Start with the users from this school

        // Check for a search term
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->latest()->paginate(10); // Show 10 users per page

        return view('superadmin.users.index', [
            'school' => $school,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for editing a specific user.
     */
    public function edit(User $user)
    {
        return view('superadmin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'teacher', 'supervisor'])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Only update the password if a new one was provided
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('superadmin.schools.users.index', ['school' => $user->school_id])
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // We get the school ID before deleting the user, so we know where to redirect back to.
        $schoolId = $user->school_id;

        $user->delete();

        return redirect()->route('superadmin.schools.users.index', ['school' => $schoolId])
            ->with('success', 'User deleted successfully.');
    }
}
