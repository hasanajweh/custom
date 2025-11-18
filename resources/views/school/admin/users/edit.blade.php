{{-- resources/views/school/admin/users/edit.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.users.edit_user') . ' - ' . __('messages.app.name'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="ri-user-settings-line text-blue-600 mr-3"></i>
                {{ __('messages.users.edit_user') }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">{{ __('messages.users.update_user_info') }}</p>
        </div>

        <form method="POST" action="{{ tenant_route('school.admin.users.update', $school->network, $school, ['user' => $user->id]) }}" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- User Info -->
            <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center font-bold text-white text-xl">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ __('messages.profile.member_since') }} {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.full_name') }} <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.email_address') }} <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.user_role') }} <span class="text-red-500">*</span></label>
                <select id="role" name="role" required onchange="toggleRoleFields()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>{{ __('messages.roles.teacher') }}</option>
                    <option value="supervisor" {{ old('role', $user->role) === 'supervisor' ? 'selected' : '' }}>{{ __('messages.roles.supervisor') }}</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                </select>
                @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Supervisor Subjects -->
            <div id="supervisorSubjectField" class="{{ old('role', $user->role) === 'supervisor' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.subjects') }}</label>
                <div x-data="multiSelect({options: {{ $subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name]) }}, name:'subject_ids', old:{{ json_encode(old('subject_ids', $selectedSupervisorSubjectIds ?? [])) }}})" class="space-y-2">
                    <div class="flex items-center gap-4 text-sm text-blue-600 font-medium">
                        <button type="button" @click="selectAll" class="hover:underline">Select All</button>
                        <button type="button" @click="deselectAll" class="hover:underline">Deselect All</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in options" :key="opt.id">
                            <button type="button" @click="toggle(opt.id)"
                                    :class="selected.includes(opt.id) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 hover:bg-blue-100 hover:text-blue-800 focus:outline-none">
                                <span x-text="opt.name"></span>
                            </button>
                        </template>
                    </div>
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" :name="`${name}[]`" :value="id">
                    </template>
                </div>
            </div>

            <!-- Teacher Grades -->
            <div id="teacherGradeField" class="{{ old('role', $user->role) === 'teacher' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.grades.grades') }}</label>
                <div x-data="multiSelect({options: {{ $grades->map(fn($g)=>['id'=>$g->id,'name'=>$g->name]) }}, name:'teacher_grade_ids', old:{{ json_encode(old('teacher_grade_ids', $selectedTeacherGradeIds ?? [])) }}})" class="space-y-2">
                    <div class="flex items-center gap-4 text-sm text-green-600 font-medium">
                        <button type="button" @click="selectAll" class="hover:underline">Select All</button>
                        <button type="button" @click="deselectAll" class="hover:underline">Deselect All</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in options" :key="opt.id">
                            <button type="button" @click="toggle(opt.id)"
                                    :class="selected.includes(opt.id) ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 hover:bg-green-100 hover:text-green-800 focus:outline-none">
                                <span x-text="opt.name"></span>
                            </button>
                        </template>
                    </div>
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" :name="`${name}[]`" :value="id">
                    </template>
                </div>
            </div>

            <!-- Teacher Subjects -->
            <div id="teacherSubjectField" class="{{ old('role', $user->role) === 'teacher' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.subjects') }}</label>
                <div x-data="multiSelect({options: {{ $subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name]) }}, name:'teacher_subject_ids', old:{{ json_encode(old('teacher_subject_ids', $selectedTeacherSubjectIds ?? [])) }}})" class="space-y-2">
                    <div class="flex items-center gap-4 text-sm text-purple-600 font-medium">
                        <button type="button" @click="selectAll" class="hover:underline">Select All</button>
                        <button type="button" @click="deselectAll" class="hover:underline">Deselect All</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="opt in options" :key="opt.id">
                            <button type="button" @click="toggle(opt.id)"
                                    :class="selected.includes(opt.id) ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 hover:bg-purple-100 hover:text-purple-800 focus:outline-none">
                                <span x-text="opt.name"></span>
                            </button>
                        </template>
                    </div>
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" :name="`${name}[]`" :value="id">
                    </template>
                </div>
            </div>

            <!-- Change Password -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.profile.change_password') }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ __('messages.users.leave_blank_password') }}</p>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.auth.new_password') }}</label>
                        <input type="password" id="password" name="password" placeholder="{{ __('messages.users.minimum_characters', ['count' => 8]) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.auth.confirm_new_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('messages.auth.confirm_new_password') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ tenant_route('school.admin.users.index', $school->network, $school) }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-colors">
                    {{ __('messages.actions.cancel') }}
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors flex items-center">
                    <i class="ri-save-line mr-2"></i>
                    {{ __('messages.users.update_user') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    ['supervisorSubjectField','teacherGradeField','teacherSubjectField'].forEach(id=>document.getElementById(id).classList.add('hidden'));
    if (role === 'supervisor') document.getElementById('supervisorSubjectField').classList.remove('hidden');
    if (role === 'teacher') {
        document.getElementById('teacherGradeField').classList.remove('hidden');
        document.getElementById('teacherSubjectField').classList.remove('hidden');
    }
}
function multiSelect({ options, name, old }) {
    return {
        options,
        name,
        selected: old,
        toggle(id) {
            this.selected.includes(id)
                ? this.selected = this.selected.filter(i => i !== id)
                : this.selected.push(id);
        },
        selectAll() {
            this.selected = this.options.map(o => o.id);
        },
        deselectAll() {
            this.selected = [];
        },
        get selectedOptions() {
            return this.options.filter(o => this.selected.includes(o.id));
        }
    }
}
</script>
@endpush
@endsection
