<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainAdmin\StoreNetworkUserRequest;
use App\Http\Requests\MainAdmin\UpdateNetworkUserRequest;
use App\Models\Network;
use App\Models\SchoolUserRole;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->withCount('users')->get();

        $users = $network->allUsers(true);

        $users->load(['assignedSchools', 'schoolRoles' => fn($query) => $query->with('school')]);

        if (request('role')) {
            $users = $users->filter(function (User $user) {
                return $user->schoolRoles->contains('role', request('role'));
            });
        }

        if (request('status') === 'archived') {
            $users = $users->filter(fn (User $user) => $user->trashed());
        } elseif (request('status') === 'active') {
            $users = $users->filter(fn (User $user) => !$user->trashed());
        }

        $users = $users
            ->sortByDesc('created_at')
            ->values();

        $perPage = 20;
        $page = request('page', 1);
        $users = new LengthAwarePaginator(
            $users->forPage($page, $perPage),
            $users->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('main-admin.users.index', [
            'network' => $network,
            'branches' => $branches,
            'users' => $users,
        ]);
    }

    public function create(Network $network): View
    {
        $branches = $network->branches()->with(['subjects', 'grades'])->get();

        $assignments = $this->normalizeAssignmentsForView(request()->old('assignments', []), $branches);

        return view('main-admin.users.create', [
            'network' => $network,
            'branches' => $branches,
            'assignments' => $assignments,
        ]);
    }

    public function store(StoreNetworkUserRequest $request, Network $network): RedirectResponse
    {
        $data = $request->validated();
        $assignments = $this->normalizeAssignments($data['assignments'] ?? [], $network);

        $primarySchoolId = array_key_first($assignments);

        DB::transaction(function () use ($data, $network, $assignments, $primarySchoolId) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'network_id' => $network->id,
                'school_id' => $primarySchoolId,
                'is_active' => true,
            ]);

            foreach ($assignments as $schoolId => $items) {
                foreach ($items['roles'] as $role) {
                    SchoolUserRole::create([
                        'user_id' => $user->id,
                        'school_id' => $schoolId,
                        'role' => $role,
                        'is_active' => true,
                    ]);
                }

                $subjects = [];
                foreach ($items['subjects'] as $subjectId) {
                    $subjects[$subjectId] = ['school_id' => $schoolId];
                }

                if (!empty($subjects)) {
                    $user->subjects()->attach($subjects);
                }

                $grades = [];
                foreach ($items['grades'] as $gradeId) {
                    $grades[$gradeId] = ['school_id' => $schoolId];
                }

                if (!empty($grades)) {
                    $user->grades()->attach($grades);
                }
            }
        });

        return redirect()->route('main-admin.users.index', ['network' => $network->slug])
            ->with('status', __('messages.main_admin.users.user_created'));
    }

    public function edit(Network $network, User $user): View
    {
        abort_unless($user->network_id === $network->id, 404);

        $branches = $network->branches()->with(['subjects', 'grades'])->get();
        $user->load(['schoolRoles', 'subjects', 'grades']);

        $assignments = request()->old('assignments');

        if (is_null($assignments)) {
            $assignments = $user->schoolRoles
                ->groupBy('school_id')
                ->map(function ($roles) use ($user) {
                    $schoolId = $roles->first()->school_id;

                    $subjects = $user->subjects->where('pivot.school_id', $schoolId)->pluck('id')->all();
                    $grades = $user->grades->where('pivot.school_id', $schoolId)->pluck('id')->all();

                    return [
                        'roles' => $roles->pluck('role')->all(),
                        'subjects' => $subjects,
                        'grades' => $grades,
                        'teacher_subjects' => $subjects,
                        'teacher_grades' => $grades,
                        'supervisor_subjects' => $subjects,
                        'supervisor_grades' => $grades,
                    ];
                })
                ->toArray();
        }

        $assignments = $this->normalizeAssignmentsForView($assignments ?? [], $branches);

        return view('main-admin.users.edit', [
            'network' => $network,
            'user' => $user,
            'branches' => $branches,
            'assignments' => $assignments,
        ]);
    }

    public function update(UpdateNetworkUserRequest $request, Network $network, User $user): RedirectResponse
    {
        abort_unless($user->network_id === $network->id, 404);

        $data = $request->validated();
        $assignments = $this->normalizeAssignments($data['assignments'] ?? [], $network);
        $primarySchoolId = array_key_first($assignments);

        $user->fill([
            'name' => $data['name'],
            'school_id' => $primarySchoolId,
            'is_active' => $data['is_active'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->syncAssignments($user, $assignments, $network);

        return redirect()->route('main-admin.users.index', ['network' => $network->slug])
            ->with('status', __('messages.main_admin.users.user_updated'));
    }

    public function destroy(Network $network, User $user): RedirectResponse
    {
        abort_unless($user->network_id === $network->id, 404);

        $user->delete();

        return redirect()->route('main-admin.users.index', ['network' => $network->slug])
            ->with('status', __('User archived successfully.'));
    }

    public function restore(Network $network, int $user): RedirectResponse
    {
        $record = User::withTrashed()->where('network_id', $network->id)->findOrFail($user);
        $record->restore();

        return redirect()->route('main-admin.users.index', ['network' => $network->slug])
            ->with('status', __('User restored successfully.'));
    }

    protected function normalizeAssignments(array $assignments, Network $network): array
    {
        $allowedSchools = $network->branches()->pluck('id')->all();
        $normalized = [];

        foreach ($assignments as $key => $assignment) {
            $schoolId = (int) ($assignment['school_id'] ?? $key);

            if (!in_array($schoolId, $allowedSchools, true)) {
                continue;
            }

            $teacherSubjects = array_values(array_unique($assignment['teacher_subjects'] ?? []));
            $teacherGrades = array_values(array_unique($assignment['teacher_grades'] ?? []));
            $supervisorSubjects = array_values(array_unique($assignment['supervisor_subjects'] ?? []));
            $supervisorGrades = array_values(array_unique($assignment['supervisor_grades'] ?? []));

            $subjects = array_values(array_unique(array_merge(
                $assignment['subjects'] ?? [],
                $teacherSubjects,
                $supervisorSubjects
            )));

            $grades = array_values(array_unique(array_merge(
                $assignment['grades'] ?? [],
                $teacherGrades,
                $supervisorGrades
            )));

            $normalized[$schoolId] = [
                'school_id' => $schoolId,
                'roles' => array_values(array_unique($assignment['roles'] ?? [])),
                'subjects' => $subjects,
                'grades' => $grades,
                'teacher_subjects' => $teacherSubjects,
                'teacher_grades' => $teacherGrades,
                'supervisor_subjects' => $supervisorSubjects,
                'supervisor_grades' => $supervisorGrades,
            ];
        }

        ksort($normalized);

        return $normalized;
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

                $this->syncSubjectsAndGrades($user, $schoolId, $assignment['subjects'] ?? [], $assignment['grades'] ?? []);
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

    protected function normalizeAssignmentsForView(array $assignments, $branches): array
    {
        foreach ($branches as $branch) {
            $branchId = $branch->id;

            if (!isset($assignments[$branchId])) {
                $assignments[$branchId] = [];
            }

            $assignments[$branchId]['roles'] = $assignments[$branchId]['roles'] ?? [];
            $assignments[$branchId]['subjects'] = $assignments[$branchId]['subjects'] ?? [];
            $assignments[$branchId]['grades'] = $assignments[$branchId]['grades'] ?? [];
            $assignments[$branchId]['teacher_subjects'] = $assignments[$branchId]['teacher_subjects'] ?? $assignments[$branchId]['subjects'];
            $assignments[$branchId]['teacher_grades'] = $assignments[$branchId]['teacher_grades'] ?? $assignments[$branchId]['grades'];
            $assignments[$branchId]['supervisor_subjects'] = $assignments[$branchId]['supervisor_subjects'] ?? $assignments[$branchId]['subjects'];
            $assignments[$branchId]['supervisor_grades'] = $assignments[$branchId]['supervisor_grades'] ?? $assignments[$branchId]['grades'];
        }

        return $assignments;
    }
}
