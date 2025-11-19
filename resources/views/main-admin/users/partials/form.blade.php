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

<div class="space-y-6 mt-4">
    @foreach($branches as $branch)
        @php
            $existingAssignment = isset($user)
                ? $user->branches->firstWhere('id', $branch->id)?->pivot
                : null;
            $enabled = old("assignments.{$branch->id}.enabled", $existingAssignment ? true : false);
        @endphp
        <div class="border rounded p-4 bg-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $branch->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $branch->city ?? '' }}</p>
                </div>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="assignments[{{ $branch->id }}][enabled]" value="1" @checked($enabled)>
                    <span>@lang('messages.assign_to_branch')</span>
                </label>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="text-sm text-gray-600 block">@lang('Role')</label>
                    <select name="assignments[{{ $branch->id }}][role]" class="w-full border rounded p-2">
                        <option value="">@lang('messages.select_role')</option>
                        @foreach(['admin','supervisor','teacher'] as $role)
                            <option value="{{ $role }}" @selected(old("assignments.{$branch->id}.role", $existingAssignment->role ?? '') === $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block">@lang('Subjects')</label>
                    <select name="assignments[{{ $branch->id }}][subjects][]" multiple class="w-full border rounded p-2 h-32">
                        @foreach($branch->subjects as $subject)
                            @php
                                $selectedSubjects = old("assignments.{$branch->id}.subjects", isset($user) ? $user->subjects->where('pivot.school_id', $branch->id)->pluck('id')->toArray() : []);
                            @endphp
                            <option value="{{ $subject->id }}" @selected(in_array($subject->id, $selectedSubjects))>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 block">@lang('Grades')</label>
                    <select name="assignments[{{ $branch->id }}][grades][]" multiple class="w-full border rounded p-2 h-32">
                        @foreach($branch->grades as $grade)
                            @php
                                $selectedGrades = old("assignments.{$branch->id}.grades", isset($user) ? $user->grades->where('pivot.school_id', $branch->id)->pluck('id')->toArray() : []);
                            @endphp
                            <option value="{{ $grade->id }}" @selected(in_array($grade->id, $selectedGrades))>{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="flex justify-end mt-6">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow">@lang('Save')</button>
</div>
