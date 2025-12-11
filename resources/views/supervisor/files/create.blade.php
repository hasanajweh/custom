{{-- resources/views/supervisor/files/create.blade.php - COMPLETE FILE --}}
@extends('layouts.school')

{{-- LOCALIZED --}}
@section('title', __('messages.files.upload_resource') . ' - ' . $school->name)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-green-50 py-8 px-4">
        <div class="max-w-2xl mx-auto">

            {{-- 🎉 AMAZING SUCCESS BANNER - SAME AS TEACHER --}}
            @if(session('upload_success'))
                @php
                    $successTitleEn = __('messages.files.upload_successful', [], 'en');
                    $successTitleAr = __('messages.files.upload_successful', [], 'ar');
                    $successMessageEn = __('messages.files.file_has_been_uploaded', [], 'en');
                    $successMessageAr = __('messages.files.file_has_been_uploaded', [], 'ar');
                    $statusCompletedEn = __('messages.status.completed', [], 'en');
                    $statusCompletedAr = __('messages.status.completed', [], 'ar');
                @endphp

                <div class="mb-8 relative" id="successBanner">
                    {{-- Animated background particles --}}
                    <div class="absolute inset-0 overflow-hidden rounded-3xl">
                        <div class="particle particle-1"></div>
                        <div class="particle particle-2"></div>
                        <div class="particle particle-3"></div>
                    </div>

                    {{-- Main success card --}}
                    <div class="relative bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-3xl shadow-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>

                        <div class="relative p-8 flex items-center">
                            {{-- Animated success icon --}}
                            <div class="flex-shrink-0 mr-6">
                                <div class="relative">
                                    {{-- Pulsing rings --}}
                                    <div class="absolute inset-0 bg-white/30 rounded-full animate-ping"></div>
                                    <div class="absolute inset-0 bg-white/20 rounded-full animate-pulse"></div>

                                    {{-- Main icon circle --}}
                                    <div class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-xl transform hover:scale-110 transition-transform duration-300">
                                        <svg class="w-12 h-12 text-emerald-500 animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Success message content --}}
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-white mb-2 flex flex-col lg:flex-row lg:items-center lg:space-x-3">
                                    <span class="flex items-center">
                                        🎉 {{ $successTitleEn }}
                                    </span>
                                    <span class="text-2xl lg:text-3xl font-bold text-white/90">{{ $successTitleAr }}</span>
                                    <span class="mt-3 lg:mt-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                                        ✓ {{ $statusCompletedEn }} | {{ $statusCompletedAr }}
                                    </span>
                                </h3>

                                <p class="text-lg lg:text-xl text-white/95 font-medium mb-1">
                                    {{ $successMessageEn }}
                                </p>
                                <p class="text-lg lg:text-xl text-white/90 font-medium">
                                    {{ $successMessageAr }}
                                </p>

                                <div class="flex items-center space-x-4 text-sm text-white/90">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Backed up to S3
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Securely encrypted
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Ready to share
                                    </span>
                                </div>
                            </div>

                            {{-- Close button --}}
                            <button onclick="closeSuccessBanner()" class="flex-shrink-0 ml-4 p-3 hover:bg-white/20 rounded-xl transition-all duration-200 group">
                                <svg class="w-6 h-6 text-white group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Progress bar animation --}}
                        <div class="h-1 bg-white/30">
                            <div class="h-full bg-white animate-progress"></div>
                        </div>
                    </div>
                </div>

                <style>
                    @keyframes checkmark {
                        0% { stroke-dasharray: 0, 100; opacity: 0; transform: scale(0.5); }
                        50% { opacity: 1; transform: scale(1.1); }
                        100% { stroke-dasharray: 100, 100; opacity: 1; transform: scale(1); }
                    }
                    .animate-checkmark { animation: checkmark 0.6s ease-out forwards; }

                    @keyframes progress { from { width: 0%; } to { width: 100%; } }
                    .animate-progress { animation: progress 6s linear forwards; }

                    @keyframes float {
                        0%, 100% { transform: translateY(0) rotate(0deg); }
                        50% { transform: translateY(-20px) rotate(5deg); }
                    }
                    .particle {
                        position: absolute; background: white; border-radius: 50%; opacity: 0.2;
                        animation: float 3s ease-in-out infinite;
                    }
                    .particle-1 { width: 60px; height: 60px; top: 10%; left: 10%; animation-delay: 0s; }
                    .particle-2 { width: 40px; height: 40px; top: 60%; right: 15%; animation-delay: 1s; }
                    .particle-3 { width: 30px; height: 30px; bottom: 20%; left: 50%; animation-delay: 2s; }

                    @keyframes slideDown {
                        from { opacity: 0; transform: translateY(-50px) scale(0.9); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }
                    #successBanner { animation: slideDown 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); }

                    @keyframes fadeOut { to { opacity: 0; transform: translateY(-30px) scale(0.95); } }
                    .banner-fade-out { animation: fadeOut 0.4s ease-out forwards; }
                </style>

                <script>
                    function closeSuccessBanner() {
                        const banner = document.getElementById('successBanner');
                        banner.classList.add('banner-fade-out');
                        setTimeout(() => banner.remove(), 400);
                    }

                    setTimeout(() => {
                        const banner = document.getElementById('successBanner');
                        if (banner) closeSuccessBanner();
                    }, 6000);

                    // Clear form after success
                    const fileInput = document.getElementById('file');
                    if (fileInput) fileInput.value = '';
                    const titleInput = document.getElementById('title');
                    if (titleInput) titleInput.value = '';
                    const fileInfo = document.getElementById('fileInfo');
                    if (fileInfo) fileInfo.classList.add('hidden');
                </script>
            @endif

            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-3xl mb-6 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                {{-- LOCALIZED --}}
                <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ __('messages.files.upload_resource') }}</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{-- LOCALIZED --}}
                    {{ __('messages.files.files_available_teachers') }}
                </p>

                @php
                    $subjectCount = (isset($subjectNames) && str_contains($subjectNames, ',')) ? 2 : 1;
                @endphp

                @if(isset($subjectNames) && $subjectNames)
                    <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        {{-- LOCALIZED (using pluralization) --}}
                        <span class="font-semibold">{{ trans_choice('messages.subjects.total_subjects', $subjectCount) }}: {{ $subjectNames }}</span>
                    </div>
                @endif
            </div>

            {{-- Upload Form --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white">
                    <h2 class="text-2xl font-bold flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        {{-- LOCALIZED --}}
                        {{ __('messages.quick_upload') }}
                    </h2>
                    {{-- LOCALIZED --}}
                    <p class="mt-2 text-green-100">{{ __('messages.files.quick_simple_upload') }}</p>
                </div>

                <form method="POST" action="{{ tenant_route('supervisor.files.store', $school) }}"
                      enctype="multipart/form-data" class="p-8 space-y-8" id="uploadForm">
                    @csrf

                    {{-- File Title --}}
                    <div>
                        <label for="title" class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{-- LOCALIZED --}}
                            {{ __('messages.files.resource_title') }} <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               {{-- LOCALIZED --}}
                               placeholder="{{ __('messages.placeholders.example_title') }}"
                               class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-lg">
                        @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- File Upload Area --}}
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            {{-- LOCALIZED --}}
                            {{ __('messages.files.upload_file') }} <span class="text-red-500 ml-1">*</span>
                        </label>

                        <input type="file"
                               id="file"
                               name="file"
                               required
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                               class="hidden"
                               onchange="updateFileInfo(this)">

                        <label for="file" class="block cursor-pointer group">
                            <div id="dropZone" class="relative border-3 border-dashed border-gray-300 rounded-3xl p-12 text-center transition-all duration-300 hover:border-green-400 hover:bg-gradient-to-br hover:from-green-50 hover:to-teal-50 group-hover:shadow-xl">
                                <div class="relative max-w-md mx-auto">
                                    <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-teal-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    {{-- LOCALIZED --}}
                                    <p class="text-xl font-bold text-gray-800 mb-2">{{ __('messages.files.click_to_upload') }}</p>
                                    <p class="text-gray-600 mb-4">{{ __('messages.files.drag_drop') }}</p>
                                    <p class="text-sm text-gray-500">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        {{-- LOCALIZED --}}
                                        {{ __('messages.files.supports_formats') }} ({{ __('messages.files.max_size', ['size' => '100MB']) }})
                                    </p>

                                    {{-- File Info (Hidden initially) --}}
                                    <div id="fileInfo" class="hidden mt-6 bg-green-50 rounded-2xl p-4 border border-green-200 text-left">
                                        <div class="flex items-center">
                                            <div id="fileIcon" class="w-14 h-14 rounded-xl flex items-center justify-center mr-4">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p id="fileName" class="font-bold text-gray-900 truncate"></p>
                                                <p id="fileSize" class="text-sm text-gray-600"></p>
                                            </div>
                                            {{-- LOCALIZED (Accessibility Title) --}}
                                            <button type="button" onclick="removeFile(event)" class="ml-4 text-red-500 hover:text-red-700 transition-colors" title="{{ __('messages.actions.remove') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        @error('file')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-blue-900 mb-1">{{ __('messages.files.tagged_with') }}:</p>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{-- LOCALIZED --}}
                                        {{ __('messages.labels.subject') }}: <strong class="ml-1">{{ $subjectNames ?? __('messages.files.no_subjects_assigned') }}</strong>
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{-- LOCALIZED --}}
                                        {{ __('messages.labels.type') }}: <strong class="ml-1">{{ __('messages.files.supervisor_resource') }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ tenant_route('supervisor.reviews.index', $school) }}"
                           class="inline-flex items-center px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-all transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{-- LOCALIZED --}}
                            {{ __('messages.actions.cancel') }}
                        </a>

                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-xl hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                {{-- LOCALIZED --}}
                                {{ __('messages.files.upload_resource') }}
                            </span>
                            <span id="loadingSpinner" class="hidden flex items-center">
                                <svg class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{-- LOCALIZED --}}
                                {{ __('messages.status.uploading') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .border-3 { border-width: 3px; }
    </style>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('file');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');

        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-green-500', 'bg-green-50', 'scale-105');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-green-500', 'bg-green-50', 'scale-105');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-green-500', 'bg-green-50', 'scale-105');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFileInfo(fileInput);
            }
        });

        function updateFileInfo(input) {
            const file = input.files[0];
            if (file) {
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const fileIcon = document.getElementById('fileIcon').querySelector('svg');

                fileInfo.classList.remove('hidden');
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                const ext = file.name.split('.').pop().toLowerCase();
                const iconClasses = {
                    'pdf': 'text-red-600 bg-red-100',
                    'doc': 'text-blue-600 bg-blue-100',
                    'docx': 'text-blue-600 bg-blue-100',
                    'xls': 'text-green-600 bg-green-100',
                    'xlsx': 'text-green-600 bg-green-100',
                    'ppt': 'text-orange-600 bg-orange-100',
                    'pptx': 'text-orange-600 bg-orange-100'
                };

                const classes = iconClasses[ext] || 'text-gray-600 bg-gray-100';
                fileIcon.parentElement.className = `w-14 h-14 rounded-xl flex items-center justify-center mr-4 ${classes}`;
            }
        }

        function removeFile(event) {
            event.preventDefault();
            event.stopPropagation();
            fileInput.value = '';
            document.getElementById('fileInfo').classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form submission
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!uploadForm.checkValidity()) {
                uploadForm.reportValidity();
                return false;
            }

            if (!fileInput.files || fileInput.files.length === 0) {
                {{-- ✅ SYNTAX ERROR FIX: Use @js directive to safely pass string to JavaScript --}}
                alert(@js(__('messages.files.select_file_prompt')));
                return false;
            }

            submitBtn.disabled = true;
            document.getElementById('submitText').classList.add('hidden');
            document.getElementById('loadingSpinner').classList.remove('hidden');

            uploadForm.submit();
        });
    </script>
@endsection
