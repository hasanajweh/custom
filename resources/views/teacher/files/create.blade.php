{{-- resources/views/teacher/files/create.blade.php - ULTRA MOBILE-FRIENDLY --}}
@extends('layouts.school')

@section('title', __('messages.files.upload_file') . ' - ' . __('messages.app.name'))

@section('content')
<div class="min-h-screen bg-slate-50 py-6 px-3 sm:py-8 sm:px-4">
    <div class="max-w-2xl mx-auto">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{-- ✅ Success Message --}}
        @if(session('upload_success'))
            @php
                $successTitleEn = __('messages.files.upload_successful', [], 'en');
                $successTitleAr = __('messages.files.upload_successful', [], 'ar');
                $successMessageEn = __('messages.files.file_has_been_uploaded', [], 'en');
                $successMessageAr = __('messages.files.file_has_been_uploaded', [], 'ar');
            @endphp

            <div class="mb-6 bg-emerald-50 border-2 border-emerald-300 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row items-center shadow-lg animate-slideDown text-center sm:text-left">
                <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center mb-3 sm:mb-0 sm:mr-4 shadow-md">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-base sm:text-lg font-bold text-emerald-900">{{ $successTitleEn }}</p>
                    <p class="text-base sm:text-lg font-bold text-emerald-900 sm:mt-1">{{ $successTitleAr }}</p>
                    <p class="text-sm sm:text-base text-emerald-700 mt-2">{{ $successMessageEn }}</p>
                    <p class="text-sm sm:text-base text-emerald-700">{{ $successMessageAr }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute top-2 right-3 text-emerald-600 hover:text-emerald-800 transition sm:static sm:ml-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ✅ Error Message --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-red-800 mb-2">There were errors:</h3>
                <ul class="text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Header --}}
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-blue-600 rounded-2xl mb-3 sm:mb-4 shadow-lg">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('messages.files.upload_resource') }}</h1>
            <p class="text-gray-600 text-sm sm:text-base">{{ __('messages.files.share_resource') }}</p>
        </div>

        {{-- ✅ Upload Form --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ tenant_route('teacher.files.store', $school) }}" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="p-5 sm:p-8 space-y-6 sm:space-y-8">
                    {{-- Toggle Buttons --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.what_type_resource') }}</label>
                        <div class="flex border border-gray-200 rounded-lg p-1 bg-gray-100 flex-col sm:flex-row gap-2 sm:gap-0">
                            <button id="generalBtn" type="button" class="form-toggle-btn active w-full sm:w-1/2 py-2">{{ __('messages.files.general_resource') }}</button>
                            <button id="planBtn" type="button" class="form-toggle-btn w-full sm:w-1/2 py-2">{{ __('messages.files.lesson_plans') }}</button>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.files.resource_title') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="{{ __('messages.placeholders.example_title') }}"
                               class="w-full px-3 py-2 sm:px-4 sm:py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500">
                    </div>


                    @php($missingAssignments = $subjects->isEmpty() || $grades->isEmpty())
                    <div id="generalResourceFields" class="space-y-6">
                        @if($missingAssignments)
                            @php($assignmentMessage = \Illuminate\Support\Facades\Lang::has('messages.files.no_assignments_warning')
                                ? __('messages.files.no_assignments_warning')
                                : 'You do not have any assigned subjects or grades yet. Please contact your administrator to continue.')
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                                {{ $assignmentMessage }}
                            </div>
                        @endif                    <div id="generalResourceFields" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.content_type') }} <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                                <label class="card-radio-label"><input type="radio" name="general_type" value="exam" class="sr-only peer" checked><span>{{ __('messages.files.exam') }}</span></label>
                                <label class="card-radio-label"><input type="radio" name="general_type" value="worksheet" class="sr-only peer"><span>{{ __('messages.files.worksheet') }}</span></label>
                                <label class="card-radio-label"><input type="radio" name="general_type" value="summary" class="sr-only peer"><span>{{ __('messages.files.summary') }}</span></label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.users.subject') }} <span class="text-red-500">*</span></label>
<select id="subject_id" name="subject_id" class="w-full px-3 py-2 sm:px-4 bg-gray-50 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500" @disabled($subjects->isEmpty())>                                    <option value="">{{ __('messages.files.select_subject') }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="grade_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.files.grade_level') }} <span class="text-red-500">*</span></label>
                                <select id="grade_id" name="grade_id" class="w-full px-3 py-2 sm:px-4 bg-gray-50 border border-gray-300 rounded-lg text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500" @disabled($grades->isEmpty())>
                                    <option value="">{{ __('messages.files.select_grade') }}</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Plan Fields --}}
                    <div id="planFields" class="hidden space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.plan_duration') }} <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                                <label class="card-radio-label"><input type="radio" name="plan_type" value="daily_plan" class="sr-only peer" checked><span>{{ __('messages.plans.daily') }}</span></label>
                                <label class="card-radio-label"><input type="radio" name="plan_type" value="weekly_plan" class="sr-only peer"><span>{{ __('messages.plans.weekly') }}</span></label>
                                <label class="card-radio-label"><input type="radio" name="plan_type" value="monthly_plan" class="sr-only peer"><span>{{ __('messages.plans.monthly') }}</span></label>
                            </div>
                        </div>
                    </div>

                    {{-- File Upload --}}
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.file_name') }} <span class="text-red-500">*</span></label>
                        <input type="file" id="file" name="file" required class="hidden" onchange="updateFileInfo(this)">
                        <label for="file" id="dropZone" class="flex justify-center items-center w-full h-28 sm:h-32 px-4 sm:px-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div id="uploadPrompt" class="text-center text-sm sm:text-base">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <p class="mt-1 text-gray-600"><span class="font-semibold text-blue-600">{{ __('messages.files.click_to_upload') }}</span> or drag & drop</p>
                                <p class="text-xs text-gray-500">{{ __('messages.files.max_size', ['size' => '10MB']) }}</p>
                            </div>
                            <div id="fileInfo" class="hidden text-center text-sm sm:text-base">
                                <p id="fileName" class="font-medium text-gray-800"></p>
                                <p id="fileSize" class="text-gray-500"></p>
                                <button type="button" onclick="removeFile(event)" class="mt-2 text-red-600 text-xs sm:text-sm hover:underline">{{ __('messages.files.remove_file') }}</button>
                            </div>
                        </label>
                    </div>

                    {{-- Hidden submission type --}}
                    <input type="hidden" id="final_submission_type" name="submission_type" value="exam">
                </div>

                <div class="px-5 sm:px-8 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                    <a href="{{ tenant_route('teacher.files.index', $school) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 w-full sm:w-auto text-center">{{ __('messages.actions.cancel') }}</a>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
                        <span id="submitText">{{ __('messages.files.upload_file') }}</span>
                        <span id="loadingSpinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('messages.status.uploading') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-toggle-btn {
        border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem;
        transition: background-color 0.2s, color 0.2s; color: #4B5563;
    }
    .form-toggle-btn.active {
        background-color: white; color: #2563EB; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .card-radio-label span {
        display: block; padding: 0.75rem; text-align: center; border: 1px solid #D1D5DB;
        border-radius: 0.5rem; font-weight: 500; transition: all 0.2s;
    }
    .peer:checked + span {
        border-color: #2563EB; background-color: #EFF6FF; color: #1D4ED8;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideDown { animation: slideDown 0.4s ease-out; }
</style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // UI Elements
            const generalBtn = document.getElementById('generalBtn');
            const planBtn = document.getElementById('planBtn');
            const generalResourceFields = document.getElementById('generalResourceFields');
            const planFields = document.getElementById('planFields');
            const finalTypeInput = document.getElementById('final_submission_type');
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectField = document.getElementById('subject_id');
            const gradeField = document.getElementById('grade_id');

            // ✅ FIXED: Toggle form function
            function toggleForm(showType) {
                const isGeneral = showType === 'general';

                generalBtn.classList.toggle('active', isGeneral);
                planBtn.classList.toggle('active', !isGeneral);
                generalResourceFields.classList.toggle('hidden', !isGeneral);
                planFields.classList.toggle('hidden', isGeneral);

                // For general resources, subject and grade are required
                if (isGeneral) {
                    subjectField.required = true;
                    gradeField.required = true;
                    subjectField.disabled = false;
                    gradeField.disabled = false;
                } else {
                    // For lesson plans, clear and disable
                    subjectField.value = '';
                    gradeField.value = '';
                    subjectField.required = false;
                    gradeField.required = false;
                    subjectField.disabled = true;
                    gradeField.disabled = true;
                }

                updateFinalSubmissionType();
            }

            // ✅ FIXED: Update hidden submission_type input
            function updateFinalSubmissionType() {
                const isPlanActive = planBtn.classList.contains('active');
                const selector = isPlanActive ? 'input[name="plan_type"]:checked' : 'input[name="general_type"]:checked';
                const selectedRadio = document.querySelector(selector);

                if (selectedRadio) {
                    finalTypeInput.value = selectedRadio.value;
                    console.log('Submission type set to:', selectedRadio.value); // Debug log
                }
            }

            // Event Listeners
            generalBtn.addEventListener('click', () => toggleForm('general'));
            planBtn.addEventListener('click', () => toggleForm('plan'));

            document.querySelectorAll('input[name="general_type"], input[name="plan_type"]').forEach(radio => {
                radio.addEventListener('change', updateFinalSubmissionType);
            });

            // ✅ Form submission with success redirect
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form
                if (!uploadForm.checkValidity()) {
                    uploadForm.reportValidity();
                    return false;
                }

                // Check file is selected
                const fileInput = document.getElementById('file');
                if (!fileInput.files || fileInput.files.length === 0) {
                    alert('Please select a file to upload');
                    return false;
                }

                // Disable submit button and show loading
                submitBtn.disabled = true;
                document.getElementById('submitText').classList.add('hidden');
                document.getElementById('loadingSpinner').classList.remove('hidden');

                // ✅ Store form data in sessionStorage before submit
                sessionStorage.setItem('fileUploadInProgress', 'true');

                // Submit the form
                uploadForm.submit();
            });

            // ✅ Check if we just completed an upload
            if (sessionStorage.getItem('fileUploadInProgress') === 'true') {
                sessionStorage.removeItem('fileUploadInProgress');

                // ✅ Auto-hide success message after 5 seconds
                setTimeout(() => {
                    const successMsg = document.querySelector('.animate-slideDown');
                    if (successMsg) {
                        successMsg.style.transition = 'opacity 0.5s ease-out';
                        successMsg.style.opacity = '0';
                        setTimeout(() => successMsg.remove(), 500);
                    }
                }, 5000);
            }

            // ✅ Initialize form on page load (handles validation errors)
            const oldSubmissionType = '{{ old("submission_type") }}';
            const planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan'];

            if (oldSubmissionType && planTypes.includes(oldSubmissionType)) {
                toggleForm('plan');
                const oldPlanRadio = document.querySelector(`input[name="plan_type"][value="${oldSubmissionType}"]`);
                if (oldPlanRadio) oldPlanRadio.checked = true;
            } else {
                toggleForm('general');
                if (oldSubmissionType && !planTypes.includes(oldSubmissionType)) {
                    const oldGeneralRadio = document.querySelector(`input[name="general_type"][value="${oldSubmissionType}"]`);
                    if (oldGeneralRadio) oldGeneralRadio.checked = true;
                }
            }

            updateFinalSubmissionType();
        });

        // File Upload & Drag-n-Drop UI
        function updateFileInfo(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('uploadPrompt').classList.add('hidden');
                document.getElementById('fileInfo').classList.remove('hidden');
            }
        }

        function removeFile(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('file').value = '';
            document.getElementById('uploadPrompt').classList.remove('hidden');
            document.getElementById('fileInfo').classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('border-blue-500'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-blue-500'), false);
        });

        dropZone.addEventListener('drop', (e) => {
            fileInput.files = e.dataTransfer.files;
            updateFileInfo(fileInput);
        });
    </script>
@endsection
