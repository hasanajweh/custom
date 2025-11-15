{{-- resources/views/teacher/files/show.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.supervisors.review_file') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">{{ __('messages.supervisors.review_file') }}</h1>
                    <a href="{{ route('supervisor.reviews.index', $school->slug) }}"
                       class="text-sm text-gray-600 hover:text-gray-900">
                        <i class="ri-arrow-left-line mr-1"></i> {{ __('messages.supervisors.back_to_files') }}
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- File Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.labels.title') }}</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $fileSubmission->title }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.labels.type') }}</h3>
                        <p class="mt-1">
                            @php
                                $typeLabels = [
                                    'exam' => [__('messages.files.exam'), 'bg-red-100 text-red-700'],
                                    'worksheet' => [__('messages.files.worksheet'), 'bg-yellow-100 text-yellow-700'],
                                    'summary' => [__('messages.files.summary'), 'bg-indigo-100 text-indigo-700']
                                ];
                                $type = $typeLabels[$fileSubmission->submission_type] ?? [__('messages.files.general_resource'), 'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $type[1] }}">
                            {{ $type[0] }}
                        </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.users.teacher') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $fileSubmission->user->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.users.subject') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $fileSubmission->subject->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.users.grade') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $fileSubmission->grade->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.files.file_size') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ number_format($fileSubmission->file_size / 1048576, 2) }} MB</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.files.uploaded') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $fileSubmission->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('messages.files.downloads') }}</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $fileSubmission->download_count }} {{ __('messages.dashboard.times_downloaded') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-center gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('supervisor.reviews.preview', [$school->slug, $fileSubmission->id]) }}"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-external-link-line mr-2"></i>
                        {{ __('messages.files.preview_file') }}
                    </a>
                    <a href="{{ route('supervisor.reviews.download', [$school->slug, $fileSubmission->id]) }}"
                       class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="ri-download-2-line mr-2"></i>
                        {{ __('messages.files.download_file') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
