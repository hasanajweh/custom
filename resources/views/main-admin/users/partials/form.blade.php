<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm text-gray-600 block mb-1">{{ __('messages.main_admin.users.name_label') }}</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">{{ __('messages.main_admin.users.email_label') }}</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500 {{ isset($user) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
            @if(isset($user)) disabled readonly @endif
            required
        >
        @isset($user)
            <p class="text-xs text-gray-500 mt-1">{{ __('messages.main_admin.users.email_locked') }}</p>
        @endisset
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">{{ __('messages.main_admin.users.password_label') }}</label>
        <input type="password" name="password" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" @if(!isset($user)) required @endif>
        <p class="text-xs text-gray-500 mt-1">{{ __('messages.main_admin.users.password_hint') }}</p>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1">{{ __('messages.main_admin.users.confirm_password_label') }}</label>
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
            <p class="text-sm text-gray-700 font-semibold">{{ __('messages.main_admin.users.assign_heading') }}</p>
            <p class="text-xs text-gray-500">{{ __('messages.main_admin.users.assign_subheading') }}</p>
        </div>
    </div>
    <div class="grid gap-4">
        @foreach($branches as $branch)
            @php
                $branchRoles = $assignments[$branch->id]['roles'] ?? [];
                $branchSubjects = $assignments[$branch->id]['subjects'] ?? [];
                $branchGrades = $assignments[$branch->id]['grades'] ?? [];
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
                        <label class="text-sm text-gray-600 block mb-2">{{ __('messages.roles_label') }}</label>
                        <select
                            name="assignments[{{ $branch->id }}][roles][]"
                            multiple
                            class="tom-select role-select w-full"
                            data-placeholder="{{ __('messages.main_admin.users.select_roles') }}"
                            data-branch="{{ $branch->id }}"
                        >
                            @foreach(['admin' => __('messages.roles.admin'), 'supervisor' => __('messages.roles.supervisor'), 'teacher' => __('messages.roles.teacher')] as $roleKey => $label)
                                <option value="{{ $roleKey }}" @selected(in_array($roleKey, $branchRoles))>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 space-y-4">
                        <div class="grid md:grid-cols-2 gap-4 role-block {{ in_array('teacher', $branchRoles) ? '' : 'hidden' }}" data-branch="{{ $branch->id }}" data-role="teacher">
                            <div>
                                <label class="text-sm text-gray-600 block mb-2">{{ __('messages.main_admin.users.teacher_subjects') }}</label>
                                <select name="assignments[{{ $branch->id }}][teacher_subjects][]" multiple class="tom-select w-full teacher-select"
                                        data-placeholder="{{ __('messages.main_admin.users.select_subjects') }}"
                                        data-branch="{{ $branch->id }}"
                                        data-role-target="teacher">
                                    @foreach($branch->subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(in_array($subject->id, $assignments[$branch->id]['teacher_subjects'] ?? []))>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-2">{{ __('messages.main_admin.users.teacher_grades') }}</label>
                                <select name="assignments[{{ $branch->id }}][teacher_grades][]" multiple class="tom-select w-full teacher-select"
                                        data-placeholder="{{ __('messages.main_admin.users.select_grades') }}"
                                        data-branch="{{ $branch->id }}"
                                        data-role-target="teacher">
                                    @foreach($branch->grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(in_array($grade->id, $assignments[$branch->id]['teacher_grades'] ?? []))>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 role-block {{ in_array('supervisor', $branchRoles) ? '' : 'hidden' }}" data-branch="{{ $branch->id }}" data-role="supervisor">
                            <div>
                                <label class="text-sm text-gray-600 block mb-2">{{ __('messages.main_admin.users.supervisor_subjects') }}</label>
                                <select name="assignments[{{ $branch->id }}][supervisor_subjects][]" multiple class="tom-select w-full supervisor-select"
                                        data-placeholder="{{ __('messages.main_admin.users.select_subjects') }}"
                                        data-branch="{{ $branch->id }}"
                                        data-role-target="supervisor">
                                    @foreach($branch->subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(in_array($subject->id, $assignments[$branch->id]['supervisor_subjects'] ?? []))>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block mb-2">{{ __('messages.main_admin.users.supervisor_grades') }}</label>
                                <select name="assignments[{{ $branch->id }}][supervisor_grades][]" multiple class="tom-select w-full supervisor-select"
                                        data-placeholder="{{ __('messages.main_admin.users.select_grades') }}"
                                        data-branch="{{ $branch->id }}"
                                        data-role-target="supervisor">
                                    @foreach($branch->grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(in_array($grade->id, $assignments[$branch->id]['supervisor_grades'] ?? []))>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="flex justify-end pt-4">
    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:from-indigo-700 hover:to-purple-700 transition">{{ __('messages.main_admin.users.save') }}</button>
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
            const tsInstances = {};

            const initTom = (select) => {
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

                const key = select.name + '-' + (select.dataset.branch || '');
                tsInstances[key] = control;
                select.dataset.tsKey = key;
                return control;
            };

            const toggleRoleBlocks = (branchId, roles) => {
                document.querySelectorAll(`.role-block[data-branch="${branchId}"]`).forEach(block => {
                    const role = block.dataset.role;
                    const enabled = roles.includes(role);
                    block.classList.toggle('hidden', !enabled);

                    block.querySelectorAll('select[data-role-target="' + role + '"]').forEach(sel => {
                        const key = sel.dataset.tsKey;
                        const control = tsInstances[key];
                        if (control) {
                            if (enabled) {
                                control.enable();
                            } else {
                                control.clear();
                                control.disable();
                            }
                        }
                    });
                });
            };

            document.querySelectorAll('.tom-select').forEach(select => initTom(select));

            document.querySelectorAll('.role-select').forEach(roleSelectEl => {
                const branchId = roleSelectEl.dataset.branch;
                const roleControl = tsInstances[roleSelectEl.dataset.tsKey];

                const sync = () => {
                    const roles = (roleControl?.getValue?.() || []).filter(Boolean);
                    toggleRoleBlocks(branchId, roles);
                };

                if (roleControl) {
                    roleControl.on('change', sync);
                    sync(); // initial state
                }
            });
        });
    </script>
@endpush
