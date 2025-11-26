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
        <label class="text-sm text-gray-600 block mb-1">@lang('Name')</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">@lang('Email')</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">@lang('Password')</label>
        <input type="password" name="password" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" @if(!isset($user)) required @endif>
        <p class="text-xs text-gray-500 mt-1">@lang('Leave blank to keep current password.')</p>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">@lang('Confirm password')</label>
        <input type="password" name="password_confirmation" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" @if(!isset($user)) required @endif>
    </div>
    @isset($user)
        <div>
            <label class="text-sm text-gray-600 block mb-1">@lang('Active')</label>
            <select name="is_active" class="w-full border border-indigo-100 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500">
                <option value="1" @selected(old('is_active', $user->is_active ?? true))>@lang('Active')</option>
                <option value="0" @selected(!old('is_active', $user->is_active ?? true))>@lang('Inactive')</option>
            </select>
        </div>
    @endisset
</div>

<div class="border-t pt-6 space-y-6 mt-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-700 font-semibold">@lang('Assign branches, roles, subjects, and grades')</p>
            <p class="text-xs text-gray-500">@lang('Craft tailored access with quick search, tagging, and RTL friendly controls.')</p>
        </div>
    </div>
    <div class="grid gap-4">
        @foreach($branches as $branch)
            @php
                $branchData = $assignmentData[$branch->id] ?? ['roles' => [], 'subjects' => [], 'grades' => []];
            @endphp
            <div class="bg-white border border-indigo-50 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $branch->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $branch->city }}</p>
                    </div>
                    <input type="hidden" name="assignments[{{ $branch->id }}][school_id]" value="{{ $branch->id }}">
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="text-sm text-gray-600 block mb-2">@lang('messages.roles_label')</label>
                        <select name="assignments[{{ $branch->id }}][roles][]" multiple class="tom-select w-full" data-placeholder="@lang('Select roles')">
                            @foreach(['admin' => __('Admin'), 'supervisor' => __('Supervisor'), 'teacher' => __('Teacher')] as $roleKey => $label)
                                <option value="{{ $roleKey }}" @selected(in_array($roleKey, $branchData['roles']))>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-2">@lang('Subjects')</label>
                        <select name="assignments[{{ $branch->id }}][subjects][]" multiple class="tom-select w-full" data-placeholder="@lang('Select subjects')">
                            @foreach($branch->subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(in_array($subject->id, $branchData['subjects']))>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-2">@lang('Grades')</label>
                        <select name="assignments[{{ $branch->id }}][grades][]" multiple class="tom-select w-full" data-placeholder="@lang('Select grades')">
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

<div class="flex justify-end pt-4">
    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">@lang('Save')</button>
</div>

@push('styles')
    @once
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
        <style>
            .ts-wrapper.multi .ts-control { min-height: 2.75rem; border-radius: 0.75rem; padding-inline: 0.5rem; }
            .ts-wrapper.multi .ts-control .item { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
            .ts-wrapper.multi .ts-control input { color: #312e81; }
            .ts-dropdown { border-radius: 0.75rem; }
        </style>
    @endonce
@endpush

@push('scripts')
    @once
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @endonce
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isRtl = document.documentElement.dir === 'rtl';

            document.querySelectorAll('.tom-select').forEach(select => {
                const control = new TomSelect(select, {
                    plugins: ['remove_button'],
                    persist: false,
                    create: false,
                    maxItems: null,
                    allowEmptyOption: true,
                    placeholder: select.dataset.placeholder || '',
                });

                if (isRtl) {
                    control.control_input?.setAttribute('dir', 'rtl');
                    control.dropdown?.setAttribute('dir', 'rtl');
                }
            });
        });
    </script>
@endpush
