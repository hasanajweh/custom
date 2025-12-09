{{-- resources/views/supervisor/dashboard.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.dashboard.supervisor_dashboard') . ' - Scholder')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ __('messages.dashboard.welcome_back', ['name' => Auth::user()->name]) }}!</h1>
                    <p class="text-green-100">{{ __('messages.dashboard.supervisor_dashboard') }} • {{ $school->name }}</p>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    <p class="text-sm text-green-100">{{ __('messages.dashboard.current_time') }}</p>
                    <p class="text-2xl font-semibold">{{ now('Asia/Gaza')->format('h:i A') }}</p>

                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Files Reviewed -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.files_reviewed') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalReviewed) }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.total_reviewed') }}</p>
                        @if($lastWeekReviews > 0)
                            <p class="text-xs mt-1 {{ $thisWeekReviews >= $lastWeekReviews ? 'text-green-600' : 'text-red-600' }}">
                                <i class="ri-arrow-{{ $thisWeekReviews >= $lastWeekReviews ? 'up' : 'down' }}-line"></i>
                                {{ abs($thisWeekReviews - $lastWeekReviews) }} {{ __('messages.dashboard.this_week') }}
                            </p>
                        @endif
                    </div>
                    <div class="w-14 h-14 bg-green-200 rounded-full flex items-center justify-center">
                        <i class="ri-checkbox-circle-line text-green-700 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- This Week Reviews -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.this_week') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $thisWeekReviews }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.files_reviewed') }}</p>
                        @if($lastWeekReviews > 0)
                            @php
                                $change = $thisWeekReviews - $lastWeekReviews;
                                $percentChange = $lastWeekReviews > 0 ? round(($change / $lastWeekReviews) * 100, 1) : 0;
                            @endphp
                            <p class="text-xs mt-1 {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <i class="ri-arrow-{{ $change >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ abs($percentChange) }}% {{ $change >= 0 ? __('messages.stats.increase') : __('messages.stats.decrease') }}
                            </p>
                        @endif
                    </div>
                    <div class="w-14 h-14 bg-blue-200 rounded-full flex items-center justify-center">
                        <i class="ri-calendar-check-line text-blue-700 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- This Month Reviews -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.this_month') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $thisMonthReviews }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.files_reviewed') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-200 rounded-full flex items-center justify-center">
                        <i class="ri-calendar-line text-purple-700 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Downloads -->
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.total_downloads') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalDownloads ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.times_downloaded') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-orange-200 rounded-full flex items-center justify-center">
                        <i class="ri-download-line text-orange-700 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Analytics Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Active Teachers -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.active_teachers') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTeachers }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.in_your_subjects') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="ri-group-line text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Average Review Time -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ __('messages.dashboard.avg_review_time') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $avgReviewTime }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ __('messages.dashboard.minutes_per_file') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="ri-timer-line text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Files by Type Summary -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                <div>
                    <p class="text-sm text-gray-600 mb-3">{{ __('messages.files.content_type') }}</p>
                    <div class="space-y-2">
                        @php
                            $typeLabels = [
                                'exam' => __('messages.files.exam'),
                                'worksheet' => __('messages.files.worksheet'),
                                'summary' => __('messages.files.summary'),
                            ];
                        @endphp
                        @foreach($filesByType as $type => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $typeLabels[$type] ?? ucfirst($type) }}</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Trend Chart -->
        @if(isset($weeklyTrend) && count($weeklyTrend) > 0)
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.dashboard.recent_activity') }} - {{ __('messages.dashboard.this_week') }}</h3>
            <div class="flex items-end justify-between h-48 space-x-2">
                @foreach($weeklyTrend as $week)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-gray-100 rounded-t-lg flex items-end justify-center" style="height: {{ $weeklyTrend->max('count') > 0 ? ($week['count'] / $weeklyTrend->max('count')) * 100 : 0 }}%">
                            <div class="w-full bg-gradient-to-t from-green-500 to-emerald-400 rounded-t-lg"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">{{ $week['week'] }}</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $week['count'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Contributing Teachers -->
        @if(isset($topTeachers) && $topTeachers->count() > 0)
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.dashboard.top_contributor') }} - {{ __('messages.users.teachers') }}</h3>
            <div class="space-y-3">
                @foreach($topTeachers as $index => $teacher)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $teacher->name }}</p>
                                <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-indigo-600">{{ $teacher->file_submissions_count }}</p>
                            <p class="text-xs text-gray-500">{{ __('messages.files.files_found', ['count' => $teacher->file_submissions_count]) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Reviews & Subject Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Files for Review -->
            <div class="bg-white rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.dashboard.files_to_review') }}</h2>
                        <a href="{{ tenant_route('supervisor.reviews.index', $school) }}"
                           class="text-sm text-green-600 hover:text-green-800 font-medium">{{ __('messages.actions.view_all') }}</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentFiles as $file)
                            @php
                                // FIXED: Safely get file icon with fallback
                                $fileIcon = $file->file_icon ?? ['bg-gray-100', 'text-gray-600', 'ri-file-line'];
                                $teacherName = $file->teacher_name ?? $file->user->name ?? 'Unknown';
                            @endphp
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 rounded-lg {{ $fileIcon[0] }} flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $fileIcon[2] }} {{ $fileIcon[1] }} text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $file->title }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ __('messages.labels.by') }} {{ $teacherName }} • {{ $file->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ tenant_route('supervisor.reviews.show', $school, ['fileSubmission' => $file->id]) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-xs font-medium hover:bg-green-200 transition-colors whitespace-nowrap ml-3">
                                    {{ __('messages.actions.review') }}
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="ri-file-search-line text-4xl text-gray-300"></i>
                                <p class="text-gray-500 mt-2">{{ __('messages.dashboard.no_files_to_review') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Subject Statistics -->
            <div class="bg-white rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.dashboard.my_subjects_overview') }}</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($subjectStats as $subject)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-medium text-gray-900">{{ $subject->name }}</h3>
                                    <span class="text-sm text-gray-600 font-semibold">{{ $subject->files_count ?? 0 }} {{ __('messages.labels.files') }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div class="bg-white rounded p-2 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium">{{ __('messages.dashboard.teachers') }}</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $subject->teachers_count ?? 0 }}</p>
                                    </div>
                                    <div class="bg-white rounded p-2 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium">{{ __('messages.dashboard.this_week') }}</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $subject->week_files ?? 0 }}</p>
                                    </div>
                                    <div class="bg-white rounded p-2 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium">{{ __('messages.dashboard.downloads') }}</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $subject->downloads ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="ri-book-line text-4xl text-gray-300"></i>
                                <p class="text-gray-500 mt-2">{{ __('messages.dashboard.no_subjects_assigned') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">{{ __('messages.dashboard.recent_activity') }}</h2>
            <div class="space-y-4">
                @forelse($recentActivity as $activity)
                    @php
                        // FIXED: Safely access nested properties
                        $teacherName = optional($activity->file)->teacher_name ?? optional(optional($activity->file)->user)->name ?? 'Unknown';
                        $fileTitle = optional($activity->file)->title ?? 'Untitled';
                        $subjectName = optional($activity->file)->subject_name ?? optional(optional($activity->file)->subject)->name ?? 'Unknown Subject';
                    @endphp
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="ri-file-line text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">{{ $teacherName }}</span> {{ __('messages.dashboard.uploaded') }}
                                <span class="font-medium">{{ $fileTitle }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->created_at->diffForHumans() }} •
                                {{ $subjectName }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">{{ __('messages.dashboard.no_recent_activity') }}</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
