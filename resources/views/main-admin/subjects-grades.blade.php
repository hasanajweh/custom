@extends('layouts.network')

@section('title', __('messages.main_admin.subjects_grades.title'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ __('messages.main_admin.subjects_grades.heading') }}</h1>
            <p class="text-gray-600">{{ __('messages.main_admin.subjects_grades.subtitle') }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-xl shadow p-5 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold">{{ __('messages.main_admin.subjects_grades.create_title') }}</h2>
                    <p class="text-sm text-gray-600">{{ __('messages.main_admin.subjects_grades.assign') }}</p>
                </div>
                <span class="text-xs px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full">{{ $branches->count() }} {{ __('messages.labels.schools') }}</span>
            </div>
            <form method="post" action="{{ route('main-admin.subjects-grades.store', ['network' => $network->slug]) }}" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm text-gray-600 block">{{ __('messages.main_admin.subjects_grades.type') }}</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-500 bg-gray-50">
                            <input type="radio" name="type" value="subject" class="text-indigo-600" checked>
                            <span class="font-medium">{{ __('messages.main_admin.users.subjects_label') }}</span>
                        </label>
                        <label class="flex items-center justify-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-500 bg-gray-50">
                            <input type="radio" name="type" value="grade" class="text-indigo-600">
                            <span class="font-medium">{{ __('messages.main_admin.users.grades_label') }}</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-gray-600 block">{{ __('messages.labels.name') }}</label>
                    <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" placeholder="{{ __('messages.labels.name') }}" required>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-600">{{ __('messages.main_admin.subjects_grades.assign') }}</label>
                        <div class="space-x-2 text-xs">
                            <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') ?? 'Select all' }}</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') ?? 'Deselect all' }}</button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">{{ __('messages.main_admin.subjects_grades.instructions') ?? 'Choose at least one school; this determines where the subject/grade is available.' }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 border rounded-lg p-3 max-h-64 overflow-y-auto branch-list">
                        @foreach($branches as $branch)
                            <label class="flex items-center gap-2 px-2 py-2 rounded hover:bg-indigo-50 border border-transparent">
                                <input type="checkbox" name="branches[]" value="{{ $branch->id }}" class="branch-checkbox text-indigo-600">
                                <span class="text-sm font-medium">{{ $branch->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">{{ __('messages.actions.save') }}</button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('messages.main_admin.subjects_grades.existing_subjects') }}</h2>
                    <span class="text-xs text-gray-500">{{ $subjects->count() }} {{ __('messages.main_admin.users.subjects_label') }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($subjects as $subject)
                        <div class="border rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-500">{{ __('messages.labels.name') }}</p>
                                    <form action="{{ route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'subject', 'id' => $subject->id]) }}" method="post" class="space-y-3">
                                        @csrf
                                        @method('put')
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="{{ $subject->name }}" class="border rounded-lg p-2 w-full md:flex-1 focus:ring-1 focus:ring-indigo-500">
                                            <button class="bg-gray-900 text-white px-4 py-2 rounded-lg">{{ __('messages.actions.update') }}</button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span>{{ __('messages.main_admin.subjects_grades.assign') }}</span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') ?? 'Select all' }}</button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') ?? 'Deselect all' }}</button>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border rounded-lg p-3 max-h-44 overflow-y-auto branch-list">
                                                @foreach($branches as $branch)
                                                    <label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-indigo-50 border border-transparent">
                                                        <input type="checkbox" name="branches[]" value="{{ $branch->id }}" class="branch-checkbox text-indigo-600" @checked($subject->schools->pluck('id')->contains($branch->id))>
                                                        <span class="text-sm">{{ $branch->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('main-admin.subjects-grades.destroy', ['network' => $network->slug, 'type' => 'subject', 'id' => $subject->id]) }}" method="post" onsubmit="return confirm('{{ __('messages.main_admin.common.confirm_archive') }}')">
                                    @csrf
                                    @method('delete')
                                    <button class="text-red-600 text-sm hover:underline">{{ __('messages.actions.archive') }}</button>
                                </form>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                @foreach($subject->schools as $school)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full">{{ $school->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('messages.main_admin.subjects_grades.no_subjects') ?? 'No subjects created yet.' }}</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('messages.main_admin.subjects_grades.existing_grades') }}</h2>
                    <span class="text-xs text-gray-500">{{ $grades->count() }} {{ __('messages.main_admin.users.grades_label') }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($grades as $grade)
                        <div class="border rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1 w-full">
                                    <p class="text-sm text-gray-500">{{ __('messages.labels.name') }}</p>
                                    <form action="{{ route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'grade', 'id' => $grade->id]) }}" method="post" class="space-y-3">
                                        @csrf
                                        @method('put')
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="{{ $grade->name }}" class="border rounded-lg p-2 w-full md:flex-1 focus:ring-1 focus:ring-indigo-500">
                                            <button class="bg-gray-900 text-white px-4 py-2 rounded-lg">{{ __('messages.actions.update') }}</button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span>{{ __('messages.main_admin.subjects_grades.assign') }}</span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') ?? 'Select all' }}</button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') ?? 'Deselect all' }}</button>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border rounded-lg p-3 max-h-44 overflow-y-auto branch-list">
                                                @foreach($branches as $branch)
                                                    <label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-indigo-50 border border-transparent">
                                                        <input type="checkbox" name="branches[]" value="{{ $branch->id }}" class="branch-checkbox text-indigo-600" @checked($grade->schools->pluck('id')->contains($branch->id))>
                                                        <span class="text-sm">{{ $branch->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('main-admin.subjects-grades.destroy', ['network' => $network->slug, 'type' => 'grade', 'id' => $grade->id]) }}" method="post" onsubmit="return confirm('{{ __('messages.main_admin.common.confirm_archive') }}')">
                                    @csrf
                                    @method('delete')
                                    <button class="text-red-600 text-sm hover:underline">{{ __('messages.actions.archive') }}</button>
                                </form>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                @foreach($grade->schools as $school)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full">{{ $school->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('messages.main_admin.subjects_grades.no_grades') ?? 'No grades created yet.' }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.branch-list').forEach(list => {
                const form = list.closest('form');
                if (!form) return;

                const selectAll = form.querySelector('[data-select-all]');
                const clearAll = form.querySelector('[data-clear-all]');
                const checkboxes = list.querySelectorAll('.branch-checkbox');

                selectAll?.addEventListener('click', () => checkboxes.forEach(cb => cb.checked = true));
                clearAll?.addEventListener('click', () => checkboxes.forEach(cb => cb.checked = false));
            });
        });
    </script>
@endpush
@endsection
