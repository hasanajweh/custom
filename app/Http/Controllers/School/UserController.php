<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\SupervisorSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request, School $school)
    {
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
            'inactiveCount'
        ));
    }

    public function archived(Request $request, School $school)
    {
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

        return view('school.admin.users.archived', compact('school', 'archivedUsers'));
    }

    public function create(School $school)
    {
        $subjects = Subject::where('school_id', $school->id)->get();
        $grades = Grade::where('school_id', $school->id)->get();

        return view('school.admin.users.create', compact('school', 'subjects', 'grades'));
    }

    public function store(Request $request, School $school)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:teacher,supervisor,admin'],
        ];

        if ($request->role === 'supervisor') {
            $rules['subject_ids'] = ['required', 'array', 'min:1'];
            $rules['subject_ids.*'] = ['exists:subjects,id'];
        }

        if ($request->role === 'teacher') {
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

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'school_id' => $school->id,
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            if ($request->role === 'supervisor' && isset($validated['subject_ids'])) {
                foreach ($validated['subject_ids'] as $subjectId) {
                    SupervisorSubject::create([
                        'supervisor_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_id' => $school->id,
                    ]);
                }
            }

            if ($user->isTeacher() && isset($validated['teacher_subject_ids'], $validated['teacher_grade_ids'])) {
                $user->syncTeacherSubjects($validated['teacher_subject_ids'], $school->id);
                $user->syncTeacherGrades($validated['teacher_grade_ids'], $school->id);
            }

            DB::commit();

            return redirect()->route('school.admin.users.index', $school->slug)
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function edit(School $school, User $user)
    {
        if ($user->school_id !== $school->id) {
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

        return view('school.admin.users.edit', compact(
            'school',
            'user',
            'subjects',
            'grades',
            'selectedSupervisorSubjectIds',
            'selectedTeacherSubjectIds',
            'selectedTeacherGradeIds'
        ));
    }

    public function update(Request $request, School $school, User $user)
    {
        if ($user->school_id !== $school->id) {
            abort(404);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:teacher,supervisor,admin'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        if ($request->role === 'supervisor') {
            $rules['subject_ids'] = ['required', 'array', 'min:1'];
            $rules['subject_ids.*'] = ['exists:subjects,id'];
        }
        
        if ($request->role === 'teacher') {
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

        DB::beginTransaction();

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            if ($request->role === 'supervisor' && isset($validated['subject_ids'])) {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();

                foreach ($validated['subject_ids'] as $subjectId) {
                    SupervisorSubject::create([
                        'supervisor_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_id' => $school->id,
                    ]);
                }
            } else if ($request->role !== 'supervisor') {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();
            }

            if ($request->role === 'teacher' && isset($validated['teacher_subject_ids'], $validated['teacher_grade_ids'])) {
                $user->syncTeacherSubjects($validated['teacher_subject_ids'], $school->id);
                $user->syncTeacherGrades($validated['teacher_grade_ids'], $school->id);
            } elseif ($request->role !== 'teacher') {
                $user->subjects()->detach();
                $user->grades()->detach();
            }
            
            DB::commit();

            return redirect()->route('school.admin.users.index', $school->slug)
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    /**
     * ⭐ TOGGLE STATUS - Activate/Deactivate (User stays visible, just can't login)
     */
    public function toggleStatus(School $school, User $user)
    {
        if ($user->school_id !== $school->id) {
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
    public function destroy(School $school, User $user)
    {
        if ($user->school_id !== $school->id) {
            abort(404);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot archive your own account.');
        }

        // ⭐ Soft delete - moves to archived
        $user->delete();

        return redirect()->route('school.admin.users.index', $school->slug)
            ->with('success', 'User moved to archived. You can restore them anytime from the Archived Users page.');
    }

    /**
     * ⭐ RESTORE from archive
     */
    public function restore(School $school, $userId)
    {
        $user = User::onlyTrashed()
            ->where('school_id', $school->id)
            ->findOrFail($userId);

        $user->restore();

        return redirect()->route('school.admin.users.archived', $school->slug)
            ->with('success', 'User restored successfully and moved back to active users list.');
    }

    /**
     * ⭐ PERMANENT DELETE - Only from archived page
     */
    public function forceDelete(School $school, $userId)
    {
        $user = User::onlyTrashed()
            ->where('school_id', $school->id)
            ->findOrFail($userId);

        DB::beginTransaction();

        try {
            // Delete supervisor subjects
            if ($user->role === 'supervisor') {
                SupervisorSubject::where('supervisor_id', $user->id)->delete();
            }

            // Permanently delete
            $user->forceDelete();

            DB::commit();

            return redirect()->route('school.admin.users.archived', $school->slug)
                ->with('success', 'User permanently deleted. This action cannot be undone.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
