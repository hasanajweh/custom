<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.labels.name')</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border rounded p-2" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.labels.email')</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full border rounded p-2" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.labels.password')</label>
        <input type="password" name="password" class="w-full border rounded p-2" @if(!isset($user)) required @endif>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.labels.password_confirmation')</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2" @if(!isset($user)) required @endif>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.branch')</label>
        <select name="school_id" class="w-full border rounded p-2" required>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @selected(old('school_id', $user->school_id ?? '')==$branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm text-gray-600 block">@lang('messages.main_admin.users.role')</label>
        <select name="role" class="w-full border rounded p-2" required>
            <option value="admin" @selected(old('role', $user->role ?? '')==='admin')>@lang('messages.admin')</option>
            <option value="supervisor" @selected(old('role', $user->role ?? '')==='supervisor')>@lang('messages.supervisor')</option>
            <option value="teacher" @selected(old('role', $user->role ?? '')==='teacher')>@lang('messages.teacher')</option>
        </select>
    </div>
    @isset($user)
        <div>
            <label class="text-sm text-gray-600 block">@lang('messages.labels.status')</label>
            <select name="is_active" class="w-full border rounded p-2">
                <option value="1" @selected(old('is_active', $user->is_active ?? true))>@lang('messages.status.active')</option>
                <option value="0" @selected(!old('is_active', $user->is_active ?? true))>@lang('messages.status.inactive')</option>
            </select>
        </div>
    @endisset
</div>

<div class="border-t pt-4 space-y-3">
    <p class="text-sm text-gray-700 font-semibold">@lang('messages.main_admin.users.subject_grade_title')</p>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm text-gray-600 block mb-2">@lang('messages.main_admin.users.subjects_label')</label>
            <select name="subjects[]" multiple class="w-full border rounded p-2 h-40">
                @foreach($branches as $branch)
                    <optgroup label="{{ $branch->name }}">
                        @foreach($branch->subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(in_array($subject->id, old('subjects', isset($user) ? $user->subjects->pluck('id')->toArray() : [])))>{{ $subject->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600 block mb-2">@lang('messages.main_admin.users.grades_label')</label>
            <select name="grades[]" multiple class="w-full border rounded p-2 h-40">
                @foreach($branches as $branch)
                    <optgroup label="{{ $branch->name }}">
                        @foreach($branch->grades as $grade)
                            <option value="{{ $grade->id }}" @selected(in_array($grade->id, old('grades', isset($user) ? $user->grades->pluck('id')->toArray() : [])))>{{ $grade->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="flex justify-end">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow">@lang('messages.actions.save')</button>
</div>
