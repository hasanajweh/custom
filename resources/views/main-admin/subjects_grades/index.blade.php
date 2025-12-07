@extends('layouts.network')

@section('title', __('messages.main_admin.subjects_grades.title'))

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

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ __('messages.main_admin.subjects_grades.heading') }}</h1>
            <p class="text-gray-600">{{ __('messages.main_admin.subjects_grades.subtitle') }}</p>
        </div>
    </div>

    @if(session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Enhanced Network Statistics Dashboard -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-xl mb-6">
        <h2 class="text-xl font-bold mb-4">{{ __('messages.dashboard.statistics') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.main_admin.users.subjects_label') }}</p>
                <p class="text-2xl font-bold">{{ $networkStats['total_subjects'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.main_admin.users.grades_label') }}</p>
                <p class="text-2xl font-bold">{{ $networkStats['total_grades'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.branches') }}</p>
                <p class="text-2xl font-bold">{{ $networkStats['total_branches'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.files.total_files') }}</p>
                <p class="text-2xl font-bold">{{ number_format($networkStats['total_files_network']) }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.dashboard.total_downloads') }}</p>
                <p class="text-2xl font-bold">{{ number_format($networkStats['total_downloads_network']) }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.main_admin.users.subjects_label') }} {{ __('messages.status.active') }}</p>
                <p class="text-2xl font-bold">{{ $networkStats['subjects_with_files'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1">{{ __('messages.main_admin.users.grades_label') }} {{ __('messages.status.active') }}</p>
                <p class="text-2xl font-bold">{{ $networkStats['grades_with_files'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg p-6 space-y-5 border border-indigo-50">
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

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-600">{{ __('messages.main_admin.subjects_grades.assign') }}</label>
                        <div class="space-x-2 text-xs">
                            <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') }}</button>
                            <span class="text-gray-300">|</span>
                            <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') }}</button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">{{ __('messages.main_admin.subjects_grades.instructions') }}</p>
                    <select name="branches[]" multiple class="tom-select w-full" data-placeholder="{{ __('messages.main_admin.subjects_grades.assign') }}">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end">
                    <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition">{{ __('messages.actions.save') }}</button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4 border border-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('messages.main_admin.subjects_grades.existing_subjects') }}</h2>
                    <span class="text-xs text-gray-500">{{ $subjects->count() }} {{ __('messages.main_admin.users.subjects_label') }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($subjectsAnalytics as $analytics)
                        @php($subject = $analytics['subject'])
                        <div class="border rounded-2xl p-4 space-y-3 bg-gradient-to-br from-white to-indigo-50/30 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Analytics Header -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div class="bg-blue-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.files.total_files') }}</p>
                                    <p class="text-lg font-bold text-blue-700">{{ number_format($analytics['files_count']) }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.users.teachers') }}</p>
                                    <p class="text-lg font-bold text-purple-700">{{ $analytics['teachers_count'] }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.dashboard.total_downloads') }}</p>
                                    <p class="text-lg font-bold text-green-700">{{ number_format($analytics['downloads_count']) }}</p>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.dashboard.this_week') }}</p>
                                    <p class="text-lg font-bold text-orange-700">{{ $analytics['this_week_files'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="space-y-1 w-full">
                                    <p class="text-sm text-gray-500">{{ __('messages.labels.name') }}</p>
                                    <form action="{{ route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'subject', 'id' => $subject->id]) }}" method="post" class="space-y-3">
                                        @csrf
                                        @method('put')
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="{{ $subject->name }}" class="border rounded-xl p-2.5 w-full md:flex-1 focus:ring-2 focus:ring-indigo-500 bg-white shadow-inner" placeholder="{{ __('messages.labels.name') }}">
                                            <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition">{{ __('messages.actions.update') }}</button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span>{{ __('messages.main_admin.subjects_grades.assign') }}</span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') }}</button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') }}</button>
                                                </div>
                                            </div>
                                            <select name="branches[]" multiple class="tom-select w-full" data-placeholder="{{ __('messages.main_admin.subjects_grades.assign') }}">
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" @selected($subject->schools->pluck('id')->contains($branch->id))>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
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
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full font-medium">
                                    {{ $analytics['assigned_schools_count'] }} {{ __('messages.branches') }}
                                </span>
                                @forelse($subject->schools as $school)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full">{{ $school->name }}</span>
                                @empty
                                    <span class="text-gray-500 text-xs">{{ __('messages.main_admin.subjects_grades.unassigned') }}</span>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('messages.main_admin.subjects_grades.no_subjects') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4 border border-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ __('messages.main_admin.subjects_grades.existing_grades') }}</h2>
                    <span class="text-xs text-gray-500">{{ $grades->count() }} {{ __('messages.main_admin.users.grades_label') }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($gradesAnalytics as $analytics)
                        @php($grade = $analytics['grade'])
                        <div class="border rounded-2xl p-4 space-y-3 bg-gradient-to-br from-white to-emerald-50/30 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Analytics Header -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div class="bg-blue-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.files.total_files') }}</p>
                                    <p class="text-lg font-bold text-blue-700">{{ number_format($analytics['files_count']) }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.users.teachers') }}</p>
                                    <p class="text-lg font-bold text-purple-700">{{ $analytics['teachers_count'] }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.dashboard.total_downloads') }}</p>
                                    <p class="text-lg font-bold text-green-700">{{ number_format($analytics['downloads_count']) }}</p>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600">{{ __('messages.dashboard.this_week') }}</p>
                                    <p class="text-lg font-bold text-orange-700">{{ $analytics['this_week_files'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="space-y-1 w-full">
                                    <p class="text-sm text-gray-500">{{ __('messages.labels.name') }}</p>
                                    <form action="{{ route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'grade', 'id' => $grade->id]) }}" method="post" class="space-y-3">
                                        @csrf
                                        @method('put')
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="{{ $grade->name }}" class="border rounded-xl p-2.5 w-full md:flex-1 focus:ring-2 focus:ring-indigo-500 bg-white shadow-inner" placeholder="{{ __('messages.labels.name') }}">
                                            <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition">{{ __('messages.actions.update') }}</button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span>{{ __('messages.main_admin.subjects_grades.assign') }}</span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all>{{ __('messages.actions.select_all') }}</button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all>{{ __('messages.actions.deselect_all') }}</button>
                                                </div>
                                            </div>
                                            <select name="branches[]" multiple class="tom-select w-full" data-placeholder="{{ __('messages.main_admin.subjects_grades.assign') }}">
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" @selected($grade->schools->pluck('id')->contains($branch->id))>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
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
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full font-medium">
                                    {{ $analytics['assigned_schools_count'] }} {{ __('messages.branches') }}
                                </span>
                                @forelse($grade->schools as $school)
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full">{{ $school->name }}</span>
                                @empty
                                    <span class="text-gray-500 text-xs">{{ __('messages.main_admin.subjects_grades.unassigned') }}</span>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('messages.main_admin.subjects_grades.no_grades') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @once
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @endonce
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isRtl = document.documentElement.dir === 'rtl';

            document.querySelectorAll('.tom-select').forEach(select => {
                const tom = new TomSelect(select, {
                    plugins: ['remove_button'],
                    persist: false,
                    create: false,
                    maxItems: null,
                    allowEmptyOption: true,
                    placeholder: select.dataset.placeholder || '',
                    render: {
                        option: function(data, escape) {
                            return `<div class="py-2 px-3 flex items-center gap-2">${escape(data.text)}</div>`;
                        }
                    },
                });

                const container = select.closest('form');
                const selectAll = container?.querySelector('[data-select-all]');
                const clearAll = container?.querySelector('[data-clear-all]');

                selectAll?.addEventListener('click', () => {
                    tom.setValue(tom.options ? Object.keys(tom.options) : []);
                });

                clearAll?.addEventListener('click', () => tom.clear());

                if (isRtl) {
                    tom.control_input?.setAttribute('dir', 'rtl');
                    tom.dropdown?.setAttribute('dir', 'rtl');
                }
            });
        });
    </script>
@endpush
@endsection
