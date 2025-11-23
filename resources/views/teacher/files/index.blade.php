{{-- resources/views/teacher/files/index.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.files.my_files') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('messages.files.my_files') }}</h1>
                        <p class="text-lg text-gray-600">{{ __('messages.files.manage_uploaded_resources') }}</p>
                    </div>
                    <a href="{{ tenant_route('teacher.files.create', $school) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="ri-upload-2-line text-xl"></i>
                        <span>{{ __('messages.files.upload_new_file') }}</span>
                    </a>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 rtl:space-x-reverse">
                        <button onclick="switchTab('general')" id="generalTab" class="tab-button active">
                            <i class="ri-file-text-line mr-2"></i>
                            {{ __('messages.files.general_resources') }}
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full ltr:ml-2 rtl:mr-2">{{ $generalFiles->total() }}</span>
                        </button>
                        <button onclick="switchTab('plans')" id="plansTab" class="tab-button">
                            <i class="ri-calendar-line mr-2"></i>
                            {{ __('messages.files.lesson_plans') }}
                            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded-full ltr:ml-2 rtl:mr-2">{{ $lessonPlans->total() }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- General Resources Section -->
            <div id="generalSection" class="tab-content">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form method="GET" action="{{ tenant_route('teacher.files.index', $school) }}" id="generalFilterForm">
                        <input type="hidden" name="tab" value="general">

                        <div class="mb-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" name="general_search" value="{{ request('general_search') }}"
                                       placeholder="{{ __('messages.files.search_by_title') }}"
                                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                            <select name="general_type" class="compact-select">
                                <option value="">{{ __('messages.files.all_types') }}</option>
                                <option value="exam" {{ request('general_type') === 'exam' ? 'selected' : '' }}>{{ __('messages.files.exam') }}</option>
                                <option value="worksheet" {{ request('general_type') === 'worksheet' ? 'selected' : '' }}>{{ __('messages.files.worksheet') }}</option>
                                <option value="summary" {{ request('general_type') === 'summary' ? 'selected' : '' }}>{{ __('messages.files.summary') }}</option>
                            </select>

                            <select name="general_subject_id" class="compact-select">
                                <option value="">{{ __('messages.subjects.all_subjects') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('general_subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>

                            <select name="general_grade_id" class="compact-select">
                                <option value="">{{ __('messages.grades.all_grades') }}</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ request('general_grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="ri-search-line"></i>
                                <span>{{ __('messages.actions.search') }}</span>
                            </button>
                            <a href="{{ tenant_route('teacher.files.index', $school) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                <i class="ri-refresh-line"></i>
                                <span>{{ __('messages.actions.reset') }}</span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- General Files Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.files.general_resources') }}</h2>
                        <p class="text-gray-600 mt-1">{{ $generalFiles->total() }} {{ __('messages.files.files_found', ['count' => $generalFiles->total()]) }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-8 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.files.file_title') }}</th>
                                <th class="px-6 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.files.content_type') }}</th>
                                <th class="px-6 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.subjects.subject_name') }} & {{ __('messages.grades.grade_name') }}</th>
                                <th class="px-6 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.files.uploaded') }}</th>
                                <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.actions.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($generalFiles as $file)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                            @php
                                                $typeIcons = [
                                                    'exam' => ['ri-file-list-3-line', 'bg-red-100', 'text-red-600'],
                                                    'worksheet' => ['ri-file-edit-line', 'bg-yellow-100', 'text-yellow-600'],
                                                    'summary' => ['ri-file-text-line', 'bg-indigo-100', 'text-indigo-600']
                                                ];
                                                $iconData = $typeIcons[$file->submission_type] ?? ['ri-file-line', 'bg-gray-100', 'text-gray-600'];
                                                $extension = strtoupper(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                                $extColors = [
                                                    'PDF' => 'bg-red-100 text-red-700',
                                                    'DOC' => 'bg-blue-100 text-blue-700',
                                                    'DOCX' => 'bg-blue-100 text-blue-700',
                                                    'XLS' => 'bg-green-100 text-green-700',
                                                    'XLSX' => 'bg-green-100 text-green-700',
                                                    'PPT' => 'bg-orange-100 text-orange-700',
                                                    'PPTX' => 'bg-orange-100 text-orange-700',
                                                ];
                                                $extColor = $extColors[$extension] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 {{ $iconData[1] }} rounded-xl flex items-center justify-center shadow-sm">
                                                    <i class="{{ $iconData[0] }} {{ $iconData[2] }} text-xl"></i>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-gray-900 text-sm truncate" title="{{ $file->title }}">
                                                    {{ Str::limit($file->title, 40) }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $extColor }}">
                                                        {{ $extension }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ number_format($file->file_size / 1048576, 2) }} MB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-6">
                                        @php
                                            $typeStyles = [
                                                'exam' => [__('messages.files.exam'), 'bg-red-100 text-red-800', 'ri-file-list-3-line'],
                                                'worksheet' => [__('messages.files.worksheet'), 'bg-yellow-100 text-yellow-800', 'ri-file-edit-line'],
                                                'summary' => [__('messages.files.summary'), 'bg-indigo-100 text-indigo-800', 'ri-file-text-line'],
                                            ];
                                            $typeData = $typeStyles[$file->submission_type] ?? [__('messages.files.general_resource'), 'bg-gray-100 text-gray-800', 'ri-file-line'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $typeData[1] }} shadow-sm">
                                            <i class="{{ $typeData[2] }} ltr:mr-1 rtl:ml-1"></i>
                                            {{ $typeData[0] }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-6">
                                        <div class="text-sm text-gray-900 font-semibold">{{ $file->subject_name ?? __('messages.subjects.not_specified') }}</div>
                                        <div class="text-xs text-gray-500">{{ $file->grade_name ?? __('messages.grades.not_specified') }}</div>
                                    </td>

                                    <td class="px-6 py-6">
                                        <div class="text-sm text-gray-900 font-medium">{{ $file->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $file->created_at->diffForHumans() }}</div>
                                    </td>

                                    <td class="px-8 py-6">
                                        @php
                                            $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                        @endphp
                                        <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                            {{-- Preview Button --}}
                                            @if($canPreview)
                                                <a href="{{ tenant_route('teacher.files.preview', $school, ['fileSubmission' => $file->id]) }}"
                                                   target="_blank"
                                                   class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                                   title="{{ __('messages.files.preview_file') }}">
                                                    <i class="ri-eye-line text-lg"></i>
                                                </a>
                                            @else
                                                <button disabled
                                                        class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                        title="Cannot preview {{ strtoupper($extension) }} files in browser">
                                                    <i class="ri-eye-off-line text-lg"></i>
                                                </button>
                                            @endif

                                            {{-- Download Button - Always Active --}}
                                            <a href="{{ tenant_route('teacher.files.download', $school, ['fileSubmission' => $file->id]) }}"
                                               class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                               title="{{ __('messages.files.download_file') }}">
                                                <i class="ri-download-line text-lg"></i>
                                            </a>

                                            {{-- Print Button --}}
                                            @if($canPreview)
                                                <button onclick="printFile('{{ tenant_route('teacher.files.preview', $school, ['fileSubmission' => $file->id]) }}', '{{ $file->title }}', '{{ Auth::user()->name }}', '{{ $file->created_at->format('M d, Y') }}')"
                                                        class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                        title="{{ __('messages.files.print_file') }}">
                                                    <i class="ri-printer-line text-lg"></i>
                                                </button>
                                            @else
                                                <button disabled
                                                        class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                        title="Cannot print {{ strtoupper($extension) }} files in browser">
                                                    <i class="ri-printer-line text-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="ri-file-line text-2xl text-gray-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.files.no_files_uploaded') }}</h3>
                                                <p class="text-gray-500">{{ __('messages.files.try_adjusting_filters') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($generalFiles->hasPages())
                        <div class="px-8 py-4 border-t border-gray-100">
                            {{ $generalFiles->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lesson Plans Section -->
            <div id="plansSection" class="tab-content hidden">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form method="GET" action="{{ tenant_route('teacher.files.index', $school) }}" id="plansFilterForm">
                        <input type="hidden" name="tab" value="plans">

                        <div class="mb-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ri-search-line text-gray-400"></i>
                                </div>
                                <input type="text" name="plans_search" value="{{ request('plans_search') }}"
                                       placeholder="{{ __('messages.files.search_by_title') }}"
                                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                            <select name="plans_type" class="compact-select">
                                <option value="">{{ __('messages.files.all_plan_types') }}</option>
                                <option value="daily_plan" {{ request('plans_type') === 'daily_plan' ? 'selected' : '' }}>{{ __('messages.files.daily_plan') }}</option>
                                <option value="weekly_plan" {{ request('plans_type') === 'weekly_plan' ? 'selected' : '' }}>{{ __('messages.files.weekly_plan') }}</option>
                                <option value="monthly_plan" {{ request('plans_type') === 'monthly_plan' ? 'selected' : '' }}>{{ __('messages.files.monthly_plan') }}</option>
                            </select>

                            <select name="plans_date_filter" class="compact-select">
                                <option value="">{{ __('messages.files.all_dates') }}</option>
                                <option value="this_week" {{ request('plans_date_filter') === 'this_week' ? 'selected' : '' }}>{{ __('messages.files.this_week') }}</option>
                                <option value="this_month" {{ request('plans_date_filter') === 'this_month' ? 'selected' : '' }}>{{ __('messages.files.this_month') }}</option>
                                <option value="last_month" {{ request('plans_date_filter') === 'last_month' ? 'selected' : '' }}>{{ __('messages.files.last_month') }}</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="ri-search-line"></i>
                                <span>{{ __('messages.actions.search') }}</span>
                            </button>
                            <a href="{{ tenant_route('teacher.files.index', $school) }}?tab=plans" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                <i class="ri-refresh-line"></i>
                                <span>{{ __('messages.actions.reset') }}</span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Plans Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.files.lesson_plans') }}</h2>
                        <p class="text-gray-600 mt-1">{{ $lessonPlans->total() }} {{ __('messages.plans.plans_found', ['count' => $lessonPlans->total()]) }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-8 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.plan_title') }}</th>
                                <th class="px-6 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.plan_type') }}</th>
                                <th class="px-6 py-4 text-left rtl:text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.uploaded') }}</th>
                                <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.actions.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($lessonPlans as $plan)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                            @php
                                                $planIcons = [
                                                    'daily_plan' => ['ri-calendar-2-line', 'bg-blue-100', 'text-blue-600'],
                                                    'weekly_plan' => ['ri-calendar-check-line', 'bg-purple-100', 'text-purple-600'],
                                                    'monthly_plan' => ['ri-calendar-event-line', 'bg-green-100', 'text-green-600']
                                                ];
                                                $iconData = $planIcons[$plan->submission_type] ?? ['ri-calendar-line', 'bg-gray-100', 'text-gray-600'];
                                                $extension = strtoupper(pathinfo($plan->original_filename, PATHINFO_EXTENSION));
                                                $extColors = [
                                                    'PDF' => 'bg-red-100 text-red-700',
                                                    'DOC' => 'bg-blue-100 text-blue-700',
                                                    'DOCX' => 'bg-blue-100 text-blue-700',
                                                ];
                                                $extColor = $extColors[$extension] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 {{ $iconData[1] }} rounded-xl flex items-center justify-center shadow-sm">
                                                    <i class="{{ $iconData[0] }} {{ $iconData[2] }} text-xl"></i>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-gray-900 text-sm truncate" title="{{ $plan->title }}">
                                                    {{ Str::limit($plan->title, 40) }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $extColor }}">
                                                        {{ $extension }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ number_format($plan->file_size / 1048576, 2) }} MB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-6">
                                        @php
                                            $planStyles = [
                                                'daily_plan' => [__('messages.plans.daily'), 'bg-blue-100 text-blue-800', 'ri-calendar-2-line'],
                                                'weekly_plan' => [__('messages.plans.weekly'), 'bg-purple-100 text-purple-800', 'ri-calendar-check-line'],
                                                'monthly_plan' => [__('messages.plans.monthly'), 'bg-green-100 text-green-800', 'ri-calendar-event-line']
                                            ];
                                            $planData = $planStyles[$plan->submission_type] ?? [__('messages.plans.plan_type'), 'bg-gray-100 text-gray-800', 'ri-calendar-line'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $planData[1] }} shadow-sm">
                                            <i class="{{ $planData[2] }} ltr:mr-1 rtl:ml-1"></i>
                                            {{ $planData[0] }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-6">
                                        <div class="text-sm text-gray-900 font-medium">{{ $plan->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $plan->created_at->diffForHumans() }}</div>
                                    </td>

                                    <td class="px-8 py-6">
                                        @php
                                            $extension = strtolower(pathinfo($plan->original_filename, PATHINFO_EXTENSION));
                                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                        @endphp
                                        <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                            {{-- Preview Button --}}
                                            @if($canPreview)
                                                <a href="{{ tenant_route('teacher.files.preview', $school, ['fileSubmission' => $plan->id]) }}"
                                                   target="_blank"
                                                   class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                                   title="{{ __('messages.files.preview_file') }}">
                                                    <i class="ri-eye-line text-lg"></i>
                                                </a>
                                            @else
                                                <button disabled
                                                        class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                        title="Cannot preview {{ strtoupper($extension) }} files in browser">
                                                    <i class="ri-eye-off-line text-lg"></i>
                                                </button>
                                            @endif

                                            {{-- Download Button - Always Active --}}
                                            <a href="{{ tenant_route('teacher.files.download', $school, ['fileSubmission' => $plan->id]) }}"
                                               class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                               title="{{ __('messages.files.download_file') }}">
                                                <i class="ri-download-line text-lg"></i>
                                            </a>

                                            {{-- Print Button --}}
                                            @if($canPreview)
                                                <button onclick="printFile('{{ tenant_route('teacher.files.preview', $school, ['fileSubmission' => $plan->id]) }}', '{{ $plan->title }}', '{{ Auth::user()->name }}', '{{ $plan->created_at->format('M d, Y') }}')"
                                                        class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                        title="{{ __('messages.files.print_file') }}">
                                                    <i class="ri-printer-line text-lg"></i>
                                                </button>
                                            @else
                                                <button disabled
                                                        class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                        title="Cannot print {{ strtoupper($extension) }} files in browser">
                                                    <i class="ri-printer-line text-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="ri-calendar-line text-2xl text-gray-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.plans.no_plans') }}</h3>
                                                <p class="text-gray-500">{{ __('messages.files.try_adjusting_filters') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($lessonPlans->hasPages())
                        <div class="px-8 py-4 border-t border-gray-100">
                            {{ $lessonPlans->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .tab-button {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            font-size: 0.875rem;
            color: #6b7280;
            transition: all 0.2s;
            cursor: pointer;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
            outline: none;
        }

        .tab-button.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-button:hover:not(.active) {
            color: #374151;
        }

        .tab-content.hidden {
            display: none;
        }

        .compact-select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.25rem;
        }

        .compact-select:hover {
            border-color: #9ca3af;
            background-color: #f9fafb;
        }

        .compact-select:focus {
            outline: none;
            border-color: #3b82f6;
            ring: 2px;
            ring-color: #3b82f6;
            background-color: #ffffff;
        }

        .action-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName + 'Tab').classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById(tabName + 'Section').classList.remove('hidden');

            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
        }

        function printFile(previewUrl, title, uploadedBy, uploadDate) {
            const printWindow = window.open(previewUrl, '_blank', 'width=900,height=700');
            if (!printWindow) {
                alert('Please allow pop-ups to print files');
                return;
            }
            printWindow.addEventListener('load', function() {
                setTimeout(() => {
                    printWindow.print();
                }, 800);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'general';
            switchTab(activeTab);
        });

        document.querySelectorAll('#generalFilterForm select, #generalFilterForm input[type="text"]').forEach(element => {
            if (element.type === 'text') {
                let timeout;
                element.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('generalFilterForm').submit();
                    }, 500);
                });
            } else {
                element.addEventListener('change', () => {
                    document.getElementById('generalFilterForm').submit();
                });
            }
        });

        document.querySelectorAll('#plansFilterForm select, #plansFilterForm input[type="text"]').forEach(element => {
            if (element.type === 'text') {
                let timeout;
                element.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('plansFilterForm').submit();
                    }, 500);
                });
            } else {
                element.addEventListener('change', () => {
                    document.getElementById('plansFilterForm').submit();
                });
            }
        });
    </script>
@endpush
