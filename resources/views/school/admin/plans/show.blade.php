@extends('layouts.school')

@php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? Auth::user()->school;
@endphp

@section('title', __('messages.plans.view_plan') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ tenant_route('school.admin.plans.index', $school) }}"
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    {{ __('messages.actions.back') }}
                </a>
            </div>

            <!-- Plan Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $plan->title }}</h1>
                            <div class="flex items-center space-x-4 text-blue-100">
                                <span class="flex items-center">
                                    <i class="ri-user-line mr-2"></i>
                                    {{ $plan->user->name ?? __('messages.users.unknown') }}
                                </span>
                                <span class="flex items-center">
                                    <i class="ri-calendar-line mr-2"></i>
                                    {{ $plan->created_at->format('M d, Y') }}
                                </span>
                                @if($plan->subject)
                                    <span class="flex items-center">
                                        <i class="ri-book-line mr-2"></i>
                                        {{ $plan->subject->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($plan->submission_type === 'daily_plan')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500/20 text-white border border-green-300/30">
                                    <i class="ri-calendar-line mr-2"></i>
                                    {{ __('messages.plans.daily') }}
                                </span>
                            @elseif($plan->submission_type === 'weekly_plan')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-500/20 text-white border border-blue-300/30">
                                    <i class="ri-calendar-2-line mr-2"></i>
                                    {{ __('messages.plans.weekly') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-amber-500/20 text-white border border-amber-300/30">
                                    <i class="ri-calendar-check-line mr-2"></i>
                                    {{ __('messages.plans.monthly') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- File Preview -->
                    <div class="mb-6">
                        @php
                            $extension = strtolower(pathinfo($plan->original_filename, PATHINFO_EXTENSION));
                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                        @endphp

                        @if($canPreview)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <iframe src="{{ tenant_route('school.admin.plans.preview', [$school, $plan]) }}"
                                        class="w-full h-[600px] border-0"
                                        title="{{ $plan->title }}">
                                    <p>{{ __('messages.files.preview_not_available') }}</p>
                                </iframe>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                                    <i class="ri-file-line text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.files.preview_not_available') }}</h3>
                                <p class="text-gray-600 mb-4">{{ __('messages.files.file_cannot_preview') }}</p>
                                <a href="{{ tenant_route('school.admin.plans.download', [$school, $plan]) }}"
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                                    <i class="ri-download-line mr-2"></i>
                                    {{ __('messages.files.download_file') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Plan Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.files.file_details') }}</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">{{ __('messages.files.file_name') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $plan->original_filename }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">{{ __('messages.files.file_size') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ number_format($plan->file_size / 1048576, 2) }} MB</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">{{ __('messages.files.file_type') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ strtoupper($extension) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ __('messages.plans.plan_duration') }}</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">{{ __('messages.plans.type') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        @if($plan->submission_type === 'daily_plan')
                                            {{ __('messages.plans.daily') }}
                                        @elseif($plan->submission_type === 'weekly_plan')
                                            {{ __('messages.plans.weekly') }}
                                        @else
                                            {{ __('messages.plans.monthly') }}
                                        @endif
                                    </dd>
                                </div>
                                @if($plan->subject)
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">{{ __('messages.users.subject') }}:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $plan->subject->name }}</dd>
                                    </div>
                                @endif
                                @if($plan->grade)
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">{{ __('messages.users.grade') }}:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $plan->grade->name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-center space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ tenant_route('school.admin.plans.download', [$school, $plan]) }}"
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                            <i class="ri-download-line mr-2"></i>
                            {{ __('messages.files.download_file') }}
                        </a>
                        @if($canPreview)
                            <button onclick="window.print()"
                                    class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors shadow-sm">
                                <i class="ri-printer-line mr-2"></i>
                                {{ __('messages.actions.print') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

