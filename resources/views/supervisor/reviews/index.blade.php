{{-- resources/views/supervisor/reviews/index.blade.php --}}
{{-- COMPLETE FIXED VERSION WITH NULL SAFETY --}}
@extends('layouts.school')

@section('title', __('messages.supervisors.review_files') . ' - Scholder')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ __('messages.supervisors.files_to_review') }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ __('messages.supervisors.review_educational_resources') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm text-gray-500 font-medium">{{ __('messages.dashboard.total_files') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $files->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Comprehensive Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="ri-filter-3-line text-green-600 mr-2"></i>
                    {{ __('messages.actions.filter') }}
                </h3>
                @if(request()->anyFilled(['search', 'subject_id', 'grade_id', 'type', 'teacher_id', 'date_from', 'date_to', 'extension', 'size_min', 'size_max']))
                    <a href="{{ tenant_route('supervisor.reviews.index', $school) }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        <i class="ri-close-line mr-1"></i>
                        {{ __('messages.actions.clear') }}
                    </a>
                @endif
            </div>
            
            <form method="GET" action="{{ tenant_route('supervisor.reviews.index', $school) }}" class="space-y-4">
                <!-- First Row: Search, Subject, Grade -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.actions.search') }}</label>
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('messages.files.search_by_title') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all pr-10">
                            <i class="ri-search-line absolute right-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Subject Filter -->
                    @if($subjects->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.subject') }}</label>
                        <select name="subject_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">{{ __('messages.files.all_files') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Grade Filter -->
                    @if($grades->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.grade') }}</label>
                        <select name="grade_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">{{ __('messages.files.all_files') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Second Row: Type, Teacher, File Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- File Type (Submission Type) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.content_type') }}</label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">{{ __('messages.files.all_types') }}</option>
                            <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>{{ __('messages.files.exam') }}</option>
                            <option value="worksheet" {{ request('type') === 'worksheet' ? 'selected' : '' }}>{{ __('messages.files.worksheet') }}</option>
                            <option value="summary" {{ request('type') === 'summary' ? 'selected' : '' }}>{{ __('messages.files.summary') }}</option>
                        </select>
                    </div>

                    <!-- Teacher Filter -->
                    @if(isset($teachers) && $teachers->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.users.teacher') }}</label>
                        <select name="teacher_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">{{ __('messages.files.all_files') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- File Extension Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.filters.file_type') }}</label>
                        <select name="extension" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">{{ __('messages.files.all_files') }}</option>
                            <option value="pdf" {{ request('extension') === 'pdf' ? 'selected' : '' }}>{{ __('messages.files.pdf_files') }}</option>
                            <option value="doc,docx" {{ request('extension') === 'doc,docx' ? 'selected' : '' }}>{{ __('messages.files.word_documents') }}</option>
                            <option value="xls,xlsx" {{ request('extension') === 'xls,xlsx' ? 'selected' : '' }}>{{ __('messages.files.excel_files') }}</option>
                            <option value="ppt,pptx" {{ request('extension') === 'ppt,pptx' ? 'selected' : '' }}>{{ __('messages.files.powerpoint') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Third Row: Date Range, File Size, Sort -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.date_from') }}</label>
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.date_to') }}</label>
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- File Size Min (MB) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.file_size') }} ({{ __('messages.files.mb_abbr') }}) - {{ __('messages.labels.minimum') }}</label>
                        <input type="number"
                               name="size_min"
                               value="{{ request('size_min') }}"
                               placeholder="0"
                               min="0"
                               step="0.1"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- File Size Max (MB) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.files.file_size') }} ({{ __('messages.files.mb_abbr') }}) - {{ __('messages.labels.maximum') }}</label>
                        <input type="number"
                               name="size_max"
                               value="{{ request('size_max') }}"
                               placeholder="100"
                               min="0"
                               step="0.1"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Fourth Row: Sort Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.labels.sort_by', ['sort_by' => '']) }}</label>
                        <select name="sort_by" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>{{ __('messages.labels.date') }}</option>
                            <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>{{ __('messages.labels.title') }}</option>
                            <option value="file_size" {{ request('sort_by') === 'file_size' ? 'selected' : '' }}>{{ __('messages.labels.size') }}</option>
                            <option value="download_count" {{ request('sort_by') === 'download_count' ? 'selected' : '' }}>{{ __('messages.files.downloads') }}</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.labels.sort_order', ['sort_order' => '']) }}</label>
                        <select name="sort_order" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>{{ __('messages.labels.descending', ['descending' => '']) }}</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>{{ __('messages.labels.ascending', ['ascending' => '']) }}</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="ri-information-line mr-1"></i>
                        {{ __('messages.files.files_found', ['count' => $files->total()]) }}
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="this.form.reset(); this.form.submit();" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            {{ __('messages.actions.reset') }}
                        </button>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                            <i class="ri-filter-line mr-2"></i>
                            {{ __('messages.actions.apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Files Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.files.file_title') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.users.teacher') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.labels.type') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.users.subject') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.users.grade') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.labels.date') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ __('messages.users.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($files as $file)
                        @php
                            // FIXED: Safely get all attributes with fallbacks
                            $fileIcon = $file->file_icon ?? ['bg-gray-100', 'text-gray-600', 'ri-file-line'];
                            $teacherName = $file->teacher_name ?? $file->user->name ?? 'Unknown';
                            $typeInfo = $file->type_info ?? ['label' => 'File', 'classes' => 'bg-gray-100 text-gray-800', 'icon' => 'ri-file-line'];
                            $subjectName = $file->subject_name ?? 'Not specified';
                            $gradeName = $file->grade_name ?? 'Not specified';
                            $formattedSize = $file->formatted_file_size ?? '0 B';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl {{ $fileIcon[0] }} flex items-center justify-center">
                                            <i class="{{ $fileIcon[2] }} {{ $fileIcon[1] }} text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $file->title }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $formattedSize }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-semibold">
                                        {{ substr($teacherName, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm text-gray-700 font-medium">{{ $teacherName }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $typeInfo['classes'] }}">
                                    <i class="{{ $typeInfo['icon'] }} mr-1"></i>
                                    {{ $typeInfo['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                {{ $subjectName }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                {{ $gradeName }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold">{{ $file->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $file->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                    $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                @endphp
                                <div class="flex items-center justify-center space-x-2">
                                    {{-- Preview Button --}}
                                    @if($canPreview)
                                        <a href="{{ tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $file->id]) }}"
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
                                    <a href="{{ tenant_route('supervisor.reviews.download', $school, ['fileSubmission' => $file->id]) }}"
                                       class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                       title="{{ __('messages.files.download_file') }}">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>

                                    {{-- Print Button --}}
                                    @if($canPreview)
                                        <button onclick="printFile('{{ tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $file->id]) }}', '{{ $file->title }}', '{{ $teacherName }}', '{{ $file->created_at->format('M d, Y') }}')"
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="ri-file-search-line text-3xl text-gray-400"></i>
                                    </div>
                                    @if(isset($subjects) && $subjects->isEmpty())
                                        <p class="text-gray-500 text-lg font-medium">{{ __('messages.dashboard.no_subjects_assigned') }}</p>
                                        <p class="text-gray-400 text-sm mt-1">{{ __('messages.dashboard.contact_administrator') }}</p>
                                    @else
                                        <p class="text-gray-500 text-lg font-medium">{{ __('messages.dashboard.no_files_to_review') }}</p>
                                        <p class="text-gray-400 text-sm mt-1">{{ __('messages.dashboard.files_appear_when_uploaded') }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($files->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $files->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .action-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
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
    </script>
@endpush
