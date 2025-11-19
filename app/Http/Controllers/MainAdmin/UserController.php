<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainAdmin\StoreNetworkUserRequest;
use App\Http\Requests\MainAdmin\UpdateNetworkUserRequest;
use App\Models\Network;
use App\Models\SchoolUserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount('users')->get();

        $usersQuery = User::with(['assignedSchools', 'schoolRoles' => fn ($query) => $query->with('school')])
            ->withTrashed()
            ->where('network_id', $network->id);

        if (request('branch')) {
            $usersQuery->whereHas('schoolRoles', function ($query) {
                $query->where('school_id', request('branch'));
            });
        }

        if (request('role')) {
            $usersQuery->whereHas('schoolRoles', function ($query) {
                $query->where('role', request('role'));
            });
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
        $assignments = $this->filterAssignments($data['assignments'] ?? [], $network);

        $primarySchoolId = Arr::first($assignments)['school_id'] ?? null;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'network_id' => $network->id,
            'school_id' => $primarySchoolId,
            'is_active' => true,
        ]);

        $this->syncAssignments($user, $assignments, $network);

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User created successfully.'));
    }

    public function edit(Network $network, User $user): View
    {
        abort_unless($user->network_id === $network->id, 404);

        $branches = $network->branches()->with(['subjects', 'grades'])->get();
        $user->load(['schoolRoles', 'subjects', 'grades']);

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
        $assignments = $this->filterAssignments($data['assignments'] ?? [], $network);
        $primarySchoolId = Arr::first($assignments)['school_id'] ?? null;

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'school_id' => $primarySchoolId,
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->syncAssignments($user, $assignments, $network);

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

    protected function filterAssignments(array $assignments, Network $network): array
    {
        $allowedSchools = $network->branches()->pluck('id')->toArray();

        return collect($assignments)
            ->map(function ($assignment, $key) use ($allowedSchools) {
                $schoolId = $assignment['school_id'] ?? $key;

                if (!in_array($schoolId, $allowedSchools)) {
                    return null;
                }

                return [
                    'school_id' => (int) $schoolId,
                    'roles' => array_values(array_unique($assignment['roles'] ?? [])),
                    'subjects' => $assignment['subjects'] ?? [],
                    'grades' => $assignment['grades'] ?? [],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function syncAssignments(User $user, array $assignments, Network $network): void
    {
        DB::transaction(function () use ($user, $assignments, $network) {
            $existing = $user->schoolRoles()->get()->keyBy(fn ($role) => $role->school_id . '-' . $role->role);
            $desiredKeys = [];

            foreach ($assignments as $assignment) {
                $schoolId = $assignment['school_id'];

                foreach ($assignment['roles'] as $role) {
                    $key = $schoolId . '-' . $role;
                    $desiredKeys[$key] = true;

                    if ($existing->has($key)) {
                        $existing[$key]->update(['is_active' => true]);
                    } else {
                        SchoolUserRole::create([
                            'user_id' => $user->id,
                            'school_id' => $schoolId,
                            'role' => $role,
                            'is_active' => true,
                        ]);
                    }
                }

                if (in_array('teacher', $assignment['roles']) || in_array('supervisor', $assignment['roles'])) {
                    $this->syncSubjectsAndGrades($user, $schoolId, $assignment['subjects'] ?? [], $assignment['grades'] ?? []);
                }
            }

            foreach ($existing as $key => $role) {
                if (!isset($desiredKeys[$key])) {
                    $role->delete();
                }
            }

            // Clear subject/grade associations for branches no longer assigned
            $activeSchoolIds = collect($assignments)->pluck('school_id')->all();
            $user->subjects()->wherePivotNotIn('school_id', $activeSchoolIds)->detach();
            $user->grades()->wherePivotNotIn('school_id', $activeSchoolIds)->detach();
        });
    }

    protected function syncSubjectsAndGrades(User $user, int $schoolId, array $subjects, array $grades): void
    {
        $subjectSync = [];
        foreach ($subjects as $subjectId) {
            $subjectSync[$subjectId] = ['school_id' => $schoolId];
        }

        $gradeSync = [];
        foreach ($grades as $gradeId) {
            $gradeSync[$gradeId] = ['school_id' => $schoolId];
        }

        $user->subjects()->wherePivot('school_id', $schoolId)->detach();
        $user->subjects()->attach($subjectSync);

        $user->grades()->wherePivot('school_id', $schoolId)->detach();
        $user->grades()->attach($gradeSync);
    }
}
