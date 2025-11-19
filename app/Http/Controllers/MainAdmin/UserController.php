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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->with(['subjects', 'grades'])->withCount('users')->get();

        $usersQuery = User::with(['branches', 'subjects', 'grades'])
            ->withTrashed()
            ->where('network_id', $network->id);

        if (request('branch')) {
            $branchId = (int) request('branch');
            $usersQuery->whereHas('branches', fn ($query) => $query->where('schools.id', $branchId));
        }

        if (request('role')) {
            $role = request('role');
            $usersQuery->where(function ($query) use ($role) {
                $query->where('role', $role)
                    ->orWhereHas('branches', fn ($branchQuery) => $branchQuery->wherePivot('role', $role));
            });
        }

        if (request('status') === 'archived') {
            $usersQuery->onlyTrashed();
        } elseif (request('status') === 'active') {
            $usersQuery->whereNull('deleted_at');
        }

        $users = $usersQuery->latest()->paginate(20)->withQueryString();

        return view('main-admin.users.index', compact('network', 'branches', 'users'));
    }

    public function create(Network $network): View
    {
        $branches = $network->branches()->with(['subjects', 'grades'])->get();

        return view('main-admin.users.create', compact('network', 'branches'));
    }

    public function store(StoreNetworkUserRequest $request, Network $network): RedirectResponse
    {
        $data = $request->validated();

        $assignments = collect($data['assignments'])
            ->filter(fn ($assignment) => ($assignment['enabled'] ?? false) && !empty($assignment['role']))
            ->values();

        $primaryBranchId = optional($assignments->first())['branch_id'];
        $primaryRole = optional($assignments->first())['role'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $primaryRole,
            'school_id' => $primaryBranchId,
            'network_id' => $network->id,
            'is_active' => true,
        ]);

        $this->syncAssignments($user, $assignments, $network);

        return redirect()->route('main-admin.users.index', $network)->with('status', __('User created successfully.'));
    }

    public function edit(Network $network, User $user): View
    {
        abort_unless($user->network_id === $network->id, 404);

        $branches = $network->branches()->with(['subjects', 'grades'])->get();

        $user->load(['branches', 'subjects', 'grades']);

        return view('main-admin.users.edit', compact('network', 'user', 'branches'));
    }

    public function update(UpdateNetworkUserRequest $request, Network $network, User $user): RedirectResponse
    {
        abort_unless($user->network_id === $network->id, 404);

        $data = $request->validated();

        $assignments = collect($data['assignments'])
            ->filter(fn ($assignment) => ($assignment['enabled'] ?? false) && !empty($assignment['role']))
            ->values();

        $primaryBranchId = optional($assignments->first())['branch_id'];
        $primaryRole = optional($assignments->first())['role'];

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $primaryRole,
            'school_id' => $primaryBranchId,
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

    protected function syncAssignments(User $user, $assignments, Network $network): void
    {
        $branchData = [];

        DB::table('subject_user')->where('user_id', $user->id)->delete();
        DB::table('grade_user')->where('user_id', $user->id)->delete();

        foreach ($assignments as $assignment) {
            $branch = School::where('network_id', $network->id)->find($assignment['branch_id']);
            if (! $branch) {
                continue;
            }

            $branchData[$branch->id] = ['role' => $assignment['role']];

            if (in_array($assignment['role'], ['teacher', 'supervisor'])) {
                $subjectSync = [];
                foreach ($assignment['subjects'] ?? [] as $subjectId) {
                    $subjectSync[$subjectId] = ['school_id' => $branch->id];
                }

                $gradeSync = [];
                foreach ($assignment['grades'] ?? [] as $gradeId) {
                    $gradeSync[$gradeId] = ['school_id' => $branch->id];
                }

                if ($subjectSync) {
                    $user->subjects()->syncWithoutDetaching($subjectSync);
                }

                if ($gradeSync) {
                    $user->grades()->syncWithoutDetaching($gradeSync);
                }
            }
        }

        $user->branches()->sync($branchData);
    }
}
