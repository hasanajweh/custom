<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\SupervisorSubject;
use App\Models\Network;
use App\Models\SchoolUserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function hasBranchRole(User $user, School $branch): bool
    {
        return $user->schoolRoles()->where('school_id', $branch->id)->exists();
    }

    private function validateContext(Network $network, School $branch): School
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $user = Auth::user();
        
        // Main admin exception: can access any school in their network
        if ($user->isMainAdmin()) {
            if ($branch->network_id !== $user->network_id) {
                abort(403, 'School does not belong to your network.');
            }
            return $branch;
        }

        // Regular admin: allow if primary school matches OR has role assignment on this branch
        if ($user->school_id !== $branch->id && !$this->hasBranchRole($user, $branch)) {
            abort(403);
        }

        return $branch;
    }

    public function index(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);
        // ⭐ CHANGED: Show ALL users (active and inactive), but not archived
        $query = User::where('school_id', $school->id);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(20);

        // Attach supervisor subjects
        foreach ($users as $user) {
            if ($user->role === 'supervisor') {
                $supervisorSubjects = SupervisorSubject::where('supervisor_id', $user->id)
                    ->with('subject')
                    ->get();

                $user->supervisor_subjects = $supervisorSubjects->isNotEmpty()
                    ? $supervisorSubjects->pluck('subject.name')->implode(', ')
                    : null;
            }
        }

        // Get counts
        $teacherCount = User::where('school_id', $school->id)->where('role', 'teacher')->where('is_active', true)->count();
        $supervisorCount = User::where('school_id', $school->id)->where('role', 'supervisor')->where('is_active', true)->count();
        $adminCount = User::where('school_id', $school->id)->where('role', 'admin')->where('is_active', true)->count();
        $inactiveCount = User::where('school_id', $school->id)->where('is_active', false)->count();

        return view('school.admin.users.index', compact(
            'school',
            'users',
            'teacherCount',
            'supervisorCount',
            'adminCount',
            'inactiveCount',
            'branch',
            'network'
        ));
    }

    public function archived(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);
        $query = User::onlyTrashed()
            ->where('school_id', $school->id);

        // Search in archived
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $archivedUsers = $query->latest('deleted_at')->paginate(20);

        // Attach supervisor subjects
        foreach ($archivedUsers as $user) {
            if ($user->role === 'supervisor') {
                $supervisorSubjects = SupervisorSubject::where('supervisor_id', $user->id)
                    ->with('subject')
                    ->get();

                $user->supervisor_subjects = $supervisorSubjects->isNotEmpty()
                    ? $supervisorSubjects->pluck('subject.name')->implode(', ')
                    : null;
            }
        }

        return view('school.admin.users.archived', compact('school', 'archivedUsers', 'branch', 'network'));
    }

    public function create(Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);
        $subjects = Subject::where('school_id', $school->id)->get();
        $grades = Grade::where('school_id', $school->id)->get();

        return view('school.admin.users.create', compact('school', 'subjects', 'grades', 'branch', 'network'));
    }

    public function store(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', Rule::in(['teacher', 'supervisor', 'admin'])],
        ];

        $selectedRoles = collect($request->input('roles', []));

        if ($selectedRoles->contains('supervisor')) {
            $rules['subject_ids'] = ['required', 'array', 'min:1'];
            $rules['subject_ids.*'] = ['exists:subjects,id'];
        }

        if ($selectedRoles->contains('teacher')) {
            $rules['teacher_subject_ids'] = ['required', 'array', 'min:1'];
            $rules['teacher_subject_ids.*'] = [
                'integer',
                Rule::exists('subjects', 'id')->where('school_id', $school->id),
            ];
            $rules['teacher_grade_ids'] = ['required', 'array', 'min:1'];
            $rules['teacher_grade_ids.*'] = [
                'integer',
                Rule::exists('grades', 'id')->where('school_id', $school->id),
            ];
        }

        $validated = $request->validate($rules);

        $roles = collect($validated['roles'])->unique()->values()->all();
        $primaryRole = collect(['admin', 'supervisor', 'teacher'])->first(fn ($role) => in_array($role, $roles, true)) ?? $roles[0];

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $primaryRole,
                'school_id' => $school->id,
                'network_id' => $school->network_id,
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            foreach ($roles as $role) {
                SchoolUserRole::updateOrCreate([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    'role' => $role,
                ]);
            }

            if ($selectedRoles->contains('supervisor') && isset($validated['subject_ids'])) {
                foreach ($validated['subject_ids'] as $subjectId) {
                    SupervisorSubject::create([
                        'supervisor_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_id' => $school->id,
                    ]);
                }
            }

            if (in_array('teacher', $roles, true) && isset($validated['teacher_subject_ids'], $validated['teacher_grade_ids'])) {
                $user->syncTeacherSubjects($validated['teacher_subject_ids'], $school->id);
                $user->syncTeacherGrades($validated['teacher_grade_ids'], $school->id);
            }

            DB::commit();

            return redirect()->to(tenant_route('school.admin.users.index', $school))
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function edit(Network $network, School $branch, User $user)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can update any user in their network
        $currentUser = Auth::user();
        if (!$currentUser->isMainAdmin() && $user->school_id !== $school->id && !$this->hasBranchRole($currentUser, $school)) {
            abort(404);
        }

        $subjects = Subject::where('school_id', $school->id)->get();
        $grades = Grade::where('school_id', $school->id)->get();

        $selectedSupervisorSubjectIds = [];
        if ($user->role === 'supervisor') {
            $selectedSupervisorSubjectIds = SupervisorSubject::where('supervisor_id', $user->id)
                ->pluck('subject_id')
                ->toArray();
        }

        $selectedTeacherSubjectIds = $user->subjects()->pluck('subjects.id')->toArray();
        $selectedTeacherGradeIds = $user->grades()->pluck('grades.id')->toArray();
        $selectedRoles = $user->schoolUserRoles()->where('school_id', $school->id)->pluck('role')->all();

        return view('school.admin.users.edit', compact(
            'school',
            'user',
            'subjects',
            'grades',
            'selectedSupervisorSubjectIds',
            'selectedTeacherSubjectIds',
            'selectedTeacherGradeIds',
            'selectedRoles',
            'branch',
            'network'
        ));
    }

    public function update(Request $request, Network $network, School $branch, User $user)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can update any user in their network
        $currentUser = Auth::user();
        if (!$currentUser->isMainAdmin() && $user->school_id !== $school->id && !$this->hasBranchRole($currentUser, $school)) {
            abort(404);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $user->id,
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', Rule::in(['teacher', 'supervisor', 'admin'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $selectedRoles = collect($request->input('roles', []));

        if ($selectedRoles->contains('supervisor')) {
            $rules['subject_ids'] = ['required', 'array', 'min:1'];
            $rules['subject_ids.*'] = ['exists:subjects,id'];
        }

        if ($selectedRoles->contains('teacher')) {
            $rules['teacher_subject_ids'] = ['required', 'array', 'min:1'];
            $rules['teacher_subject_ids.*'] = [
                'integer',
                Rule::exists('subjects', 'id')->where('school_id', $school->id),
            ];
            $rules['teacher_grade_ids'] = ['required', 'array', 'min:1'];
            $rules['teacher_grade_ids.*'] = [
                'integer',
                Rule::exists('grades', 'id')->where('school_id', $school->id),
            ];
        }
        $validated = $request->validate($rules);

        $roles = collect($validated['roles'])->unique()->values()->all();
        $primaryRole = collect(['admin', 'supervisor', 'teacher'])->first(fn ($role) => in_array($role, $roles, true)) ?? $roles[0];

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $primaryRole,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            $existingRoles = $user->schoolUserRoles()->where('school_id', $school->id)->pluck('role')->all();

            foreach (array_diff($existingRoles, $roles) as $roleToRemove) {
                $user->schoolUserRoles()
                    ->where('school_id', $school->id)
                    ->where('role', $roleToRemove)
                    ->delete();
            }

            foreach ($roles as $role) {
                SchoolUserRole::updateOrCreate([
                    'user_id' => $user->id,
                    'school_id' => $school->id,
                    'role' => $role,
                ]);
            }

            if ($selectedRoles->contains('supervisor') && isset($validated['subject_ids'])) {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();

                foreach ($validated['subject_ids'] as $subjectId) {
                    SupervisorSubject::create([
                        'supervisor_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_id' => $school->id,
                    ]);
                }
            } else if (! $selectedRoles->contains('supervisor')) {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();
            }

            if ($selectedRoles->contains('teacher') && isset($validated['teacher_subject_ids'], $validated['teacher_grade_ids'])) {
                $user->syncTeacherSubjects($validated['teacher_subject_ids'], $school->id);
                $user->syncTeacherGrades($validated['teacher_grade_ids'], $school->id);
            } elseif (! $selectedRoles->contains('teacher')) {
                $user->subjects()->detach();
                $user->grades()->detach();
            }
            
            DB::commit();

            return redirect()->to(tenant_route('school.admin.users.index', $school))
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ TOGGLE STATUS - Activate/Deactivate (User stays visible, just can't login)
     */
    public function toggleStatus(Network $network, School $branch, User $user)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can update any user in their network
        $currentUser = Auth::user();
        if (!$currentUser->isMainAdmin() && $user->school_id !== $school->id && !$this->hasBranchRole($currentUser, $school)) {
            abort(404);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        // ⭐ ONLY toggle is_active, DON'T delete
        if ($user->is_active) {
            $user->deactivate(); // Sets is_active = false
            $message = 'User deactivated. They cannot login but remain visible in the list.';
        } else {
            $user->activate(); // Sets is_active = true
            $message = 'User activated. They can now login.';
        }

        return back()->with('success', $message);
    }

    /**
     * ⭐ ARCHIVE (SOFT DELETE) - Moves user to archived page
     */
    public function destroy(Network $network, School $branch, User $user)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can update any user in their network
        $currentUser = Auth::user();
        if (!$currentUser->isMainAdmin() && $user->school_id !== $school->id && !$this->hasBranchRole($currentUser, $school)) {
            abort(404);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot archive your own account.');
        }

        // ⭐ Soft delete - moves to archived
        $user->delete();

        return redirect()->to(tenant_route('school.admin.users.index', $school))
            ->with('success', 'User moved to archived. You can restore them anytime from the Archived Users page.');
    }

    /**
     * ⭐ RESTORE from archive
     */
    public function restore(Network $network, School $branch, $userId)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can restore any user in their network
        $currentUser = Auth::user();
        $query = User::onlyTrashed();
        
        if ($currentUser->isMainAdmin()) {
            $query->whereHas('school', function($q) use ($currentUser) {
                $q->where('network_id', $currentUser->network_id);
            });
        } else {
            $query->where('school_id', $school->id);
        }
        
        $user = $query->findOrFail($userId);

        $user->restore();

        return redirect()->to(tenant_route('school.admin.users.archived', $school))
            ->with('success', 'User restored successfully and moved back to active users list.');
    }

    /**
     * ⭐ PERMANENT DELETE - Only from archived page
     */
    public function forceDelete(Network $network, School $branch, $userId)
    {
        $school = $this->validateContext($network, $branch);
        
        // Main admin exception: can delete any user in their network
        $currentUser = Auth::user();
        $query = User::onlyTrashed();
        
        if ($currentUser->isMainAdmin()) {
            $query->whereHas('school', function($q) use ($currentUser) {
                $q->where('network_id', $currentUser->network_id);
            });
        } else {
            $query->where('school_id', $school->id);
        }
        
        $user = $query->findOrFail($userId);

        DB::beginTransaction();

        try {
            // Delete supervisor subjects
            if ($user->role === 'supervisor') {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();
            }

            // Permanently delete
            $user->forceDelete();

            DB::commit();

            return redirect()->to(tenant_route('school.admin.users.archived', $school))
                ->with('success', 'User permanently deleted. This action cannot be undone.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
