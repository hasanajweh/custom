@php
    $assignmentData = collect(old('assignments', []));

    if (isset($user)) {
        $assignmentData = $user->schoolRoles
            ->groupBy('school_id')
            ->map(function ($roles) use ($user) {
                $schoolId = $roles->first()->school_id;

                return [
                    'roles' => $roles->pluck('role')->all(),
                    'subjects' => $user->subjects->where('pivot.school_id', $schoolId)->pluck('id')->all(),
                    'grades' => $user->grades->where('pivot.school_id', $schoolId)->pluck('id')->all(),
                ];
            })
            ->merge($assignmentData);
    }
@endphp

<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm text-gray-600 block">@lang('Name')</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border rounded p-2" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('Email')</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border rounded p-2" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('Password')</label>
        <input type="password" name="password" class="w-full border rounded p-2" @if(!isset($user)) required @endif>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('Confirm password')</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2" @if(!isset($user)) required @endif>
    </div>
    @isset($user)
        <div>
            <label class="text-sm text-gray-600 block">@lang('Active')</label>
            <select name="is_active" class="w-full border rounded p-2">
                <option value="1" @selected(old('is_active', $user->is_active ?? true))>@lang('Active')</option>
                <option value="0" @selected(!old('is_active', $user->is_active ?? true))>@lang('Inactive')</option>
            </select>
        </div>
    @endisset
</div>

<div class="border-t pt-4 space-y-6">
    <p class="text-sm text-gray-700 font-semibold">@lang('Assign branches, roles, subjects, and grades')</p>
    <div class="grid gap-4">
        @foreach($branches as $branch)
            @php
                $branchData = $assignmentData[$branch->id] ?? ['roles' => [], 'subjects' => [], 'grades' => []];
            @endphp
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $branch->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $branch->city }}</p>
                    </div>
                    <input type="hidden" name="assignments[{{ $branch->id }}][school_id]" value="{{ $branch->id }}">
                </div>

                <div class="flex flex-wrap gap-4">
                    @foreach(['admin' => __('Admin'), 'supervisor' => __('Supervisor'), 'teacher' => __('Teacher')] as $roleKey => $label)
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="assignments[{{ $branch->id }}][roles][]" value="{{ $roleKey }}" @checked(in_array($roleKey, $branchData['roles'])) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600 block mb-2">@lang('Subjects')</label>
                        <select name="assignments[{{ $branch->id }}][subjects][]" multiple class="w-full border rounded p-2 h-32">
                            @foreach($branch->subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(in_array($subject->id, $branchData['subjects']))>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-2">@lang('Grades')</label>
                        <select name="assignments[{{ $branch->id }}][grades][]" multiple class="w-full border rounded p-2 h-32">
                            @foreach($branch->grades as $grade)
                                <option value="{{ $grade->id }}" @selected(in_array($grade->id, $branchData['grades']))>{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="flex justify-end">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow">@lang('Save')</button>
</div>
