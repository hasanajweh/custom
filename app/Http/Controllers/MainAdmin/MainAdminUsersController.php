<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainAdmin\StoreNetworkUserRequest;
use App\Http\Requests\MainAdmin\UpdateNetworkUserRequest;
use App\Models\Network;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MainAdminUsersController extends Controller
{
    public function index(Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $branches = $userNetwork->schools()->withCount('users')->get();

        $usersQuery = User::with(['school'])
            ->withTrashed()
            ->where('network_id', $userNetwork->id);

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
            'network' => $userNetwork,
            'branches' => $branches,
            'users' => $users,
        ]);
    }

    public function create(Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $branches = $userNetwork->schools()->with(['subjects', 'grades'])->get();

        return view('main-admin.users.create', [
            'network' => $userNetwork,
            'branches' => $branches,
        ]);
    }

    public function store(StoreNetworkUserRequest $request, Network $network): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $data = $request->validated();
        $school = School::where('network_id', $userNetwork->id)->findOrFail($data['school_id']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'school_id' => $school->id,
            'network_id' => $userNetwork->id,
            'is_active' => true,
        ]);

        $this->syncAssignments($user, $data, $school);

        return redirect()->route('main-admin.users.index', $userNetwork->slug)->with('status', __('User created successfully.'));
    }

    public function edit(Network $network, User $user): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);
        abort_unless($user->network_id === $userNetwork->id, 404);

        $branches = $userNetwork->schools()->with(['subjects', 'grades'])->get();

        return view('main-admin.users.edit', [
            'network' => $userNetwork,
            'user' => $user,
            'branches' => $branches,
        ]);
    }

    public function update(UpdateNetworkUserRequest $request, Network $network, User $user): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);
        abort_unless($user->network_id === $userNetwork->id, 404);

        $data = $request->validated();
        $school = School::where('network_id', $userNetwork->id)->findOrFail($data['school_id']);

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

        return redirect()->route('main-admin.users.index', $userNetwork->slug)->with('status', __('User updated successfully.'));
    }

    public function destroy(Network $network, User $user): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);
        abort_unless($user->network_id === $userNetwork->id, 404);

        $user->delete();

        return redirect()->route('main-admin.users.index', $userNetwork->slug)->with('status', __('User archived successfully.'));
    }

    public function restore(Network $network, int $user): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $record = User::withTrashed()->where('network_id', $userNetwork->id)->findOrFail($user);
        $record->restore();

        return redirect()->route('main-admin.users.index', $userNetwork->slug)->with('status', __('User restored successfully.'));
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
