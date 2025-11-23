{{-- resources/views/supervisor/reviews/show.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.supervisors.review_file') . ' - Scholder')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Button -->
        <a href="{{ tenant_route('supervisor.reviews.index', $school) }}"
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors font-medium">
            <i class="ri-arrow-left-line mr-2"></i>
            {{ __('messages.supervisors.back_to_files') }}
        </a>

        <!-- File Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 text-white">
                <h1 class="text-xl font-semibold">{{ __('messages.files.file_details') }}</h1>
            </div>

            <div class="p-6 space-y-6">
                <!-- File Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.labels.title') }}</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $fileSubmission->title }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.files.uploaded_by') }}</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-semibold">
                                {{ substr($fileSubmission->teacher_name, 0, 1) }}
                            </div>
                            <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->teacher_name }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.users.subject') }}</h3>
                        <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->subject_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.users.grade') }}</h3>
                        <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->grade_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.labels.type') }}</h3>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $fileSubmission->type_info['classes'] }}">
                                <i class="{{ $fileSubmission->type_info['icon'] }} mr-1"></i>
                                {{ $fileSubmission->type_info['label'] }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.files.upload_date') }}</h3>
                        <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.files.file_size') }}</h3>
                        <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->formatted_file_size }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.files.downloads') }}</h3>
                        <p class="text-lg text-gray-900 font-medium">{{ $fileSubmission->download_count ?? 0 }} {{ __('messages.dashboard.times_downloaded') }}</p>
                    </div>
                </div>

                @if($fileSubmission->description)
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">{{ __('messages.files.description') }}</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-900">{{ $fileSubmission->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- File Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <a href="{{ tenant_route('supervisor.reviews.download', $school, ['fileSubmission' => $fileSubmission->id]) }}"
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                            <i class="ri-download-2-line mr-2"></i>
                            {{ __('messages.files.download_file') }}
                        </a>
                        <a href="{{ tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $fileSubmission->id]) }}"
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                            <i class="ri-external-link-line mr-2"></i>
                            {{ __('messages.files.preview_file') }}
                        </a>
                        <span class="text-sm text-gray-600 font-medium">
                            {{ $fileSubmission->original_filename }}
                        </span>
                    </div>
                </div>

                <!-- File Stats -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">{{ __('messages.files.file_statistics') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-2xl font-bold text-gray-900">{{ $fileSubmission->download_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600 mt-1 font-medium">{{ __('messages.files.total_downloads') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-sm font-bold text-gray-900">
                                {{ $fileSubmission->last_accessed_at ? $fileSubmission->last_accessed_at->diffForHumans() : __('messages.files.never') }}
                            </p>
                            <p class="text-xs text-gray-600 mt-1 font-medium">{{ __('messages.files.last_accessed') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($fileSubmission->file_size / 1048576, 2) }}</p>
                            <p class="text-xs text-gray-600 mt-1 font-medium">{{ __('messages.files.mb_file_size') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
