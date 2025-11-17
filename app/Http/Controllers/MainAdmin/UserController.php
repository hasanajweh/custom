<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainAdmin\StoreNetworkUserRequest;
use App\Http\Requests\MainAdmin\UpdateNetworkUserRequest;
use App\Models\Grade;
use App\Models\Network;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount('users')->get();

        $usersQuery = User::with(['school'])
            ->withTrashed()
            ->where('network_id', $network->id);

        if (request('branch')) {
            $usersQuery->where('school_id', request('branch'));
        }

        if (request('role')) {
            $usersQuery->where('role', request('role'));
        }

        if (request('status') === 'archived') {
            $usersQuery->onlyTrashed();
        } elseif (request('status') === 'active') {
            $usersQuery->whereNull('deleted_at');
        }

        $users = $usersQuery->latest()->paginate(20)->withQueryString();

        return view('main-admin.users.index', [
            'network' => $network,
            'branches' => $branches,
            'users' => $users,
        ]);
    }

    public function create(Network $network): View
    {
        $branches = $network->branches()->with(['subjects', 'grades'])->get();

        return view('main-admin.users.create', [
            'network' => $network,
            'branches' => $branches,
        ]);
    }

    public function store(StoreNetworkUserRequest $request, Network $network): RedirectResponse
    {
        $data = $request->validated();
        $school = School::where('network_id', $network->id)->findOrFail($data['school_id']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'school_id' => $school->id,
            'network_id' => $network->id,
            'is_active' => true,
        ]);

        $this->syncAssignments($user, $data, $school);

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User created successfully.'));
    }

    public function edit(Network $network, User $user): View
    {
        abort_unless($user->network_id === $network->id, 404);

        $branches = $network->branches()->with(['subjects', 'grades'])->get();

        return view('main-admin.users.edit', [
            'network' => $network,
            'user' => $user,
            'branches' => $branches,
        ]);
    }

    public function update(UpdateNetworkUserRequest $request, Network $network, User $user): RedirectResponse
    {
        abort_unless($user->network_id === $network->id, 404);

        $data = $request->validated();
        $school = School::where('network_id', $network->id)->findOrFail($data['school_id']);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'school_id' => $school->id,
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->syncAssignments($user, $data, $school);

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User updated successfully.'));
    }

    public function destroy(Network $network, User $user): RedirectResponse
    {
        abort_unless($user->network_id === $network->id, 404);

        $user->delete();

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User archived successfully.'));
    }

    public function restore(Network $network, int $user): RedirectResponse
    {
        $record = User::withTrashed()->where('network_id', $network->id)->findOrFail($user);
        $record->restore();

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User restored successfully.'));
    }

    protected function syncAssignments(User $user, array $data, School $school): void
    {
        if (in_array($data['role'], ['teacher', 'supervisor'])) {
            $subjects = $data['subjects'] ?? [];
            $grades = $data['grades'] ?? [];
            $subjectSync = [];
            foreach ($subjects as $subjectId) {
                $subjectSync[$subjectId] = ['school_id' => $school->id];
            }

            $gradeSync = [];
            foreach ($grades as $gradeId) {
                $gradeSync[$gradeId] = ['school_id' => $school->id];
            }

            $user->subjects()->sync($subjectSync);
            $user->grades()->sync($gradeSync);
        } else {
            $user->subjects()->detach();
            $user->grades()->detach();
        }
    }
}
