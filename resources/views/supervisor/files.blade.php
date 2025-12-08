{{-- resources/views/supervisor/files.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.supervisor.files.title'))

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ tenant_route('school.admin.supervisors.index', $school) }}"
                       class="inline-flex items-center text-indigo-200 hover:text-white mb-4 transition-colors">
                        <i class="ri-arrow-left-line mr-2"></i>
                        {{ __('messages.supervisor.files.back') }}
                    </a>
                    <h1 class="text-3xl font-bold mb-2">{{ $supervisor->name }} {{ __('messages.supervisor.files.uploads') }}</h1>
                    <p class="text-indigo-100">{{ __('messages.supervisor.files.description') }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-lg rounded-xl p-4">
                    <div class="text-center">
                        <p class="text-sm text-indigo-200">{{ __('messages.supervisor.files.total_files') }}</p>
                        <p class="text-2xl font-bold">{{ $files->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ tenant_route('school.admin.supervisors.files', [$school, $supervisor]) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.common.search') }}</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('messages.common.search') }}..."
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.common.from_date') }}</label>
                    <input type="date"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.common.to_date') }}</label>
                    <input type="date"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-colors">
                        <i class="ri-filter-line mr-2"></i>
                        {{ __('messages.common.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Files Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.file') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.subject') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.size') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.date') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.downloads') }}</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.common.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($files as $file)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @php
                                        $ext = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                        $iconData = match($ext) {
                                            'pdf' => ['ri-file-pdf-line', 'bg-red-100', 'text-red-600'],
                                            'doc', 'docx' => ['ri-file-word-line', 'bg-blue-100', 'text-blue-600'],
                                            'xls', 'xlsx' => ['ri-file-excel-line', 'bg-green-100', 'text-green-600'],
                                            'ppt', 'pptx' => ['ri-file-ppt-line', 'bg-orange-100', 'text-orange-600'],
                                            default => ['ri-file-line', 'bg-gray-100', 'text-gray-600']
                                        };
                                    @endphp
                                    <div class="w-10 h-10 rounded-lg {{ $iconData[1] }} flex items-center justify-center mr-3">
                                        <i class="{{ $iconData[0] }} {{ $iconData[2] }} text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $file->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $file->original_filename }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($file->subject)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <i class="ri-book-line mr-1"></i>
                                        {{ $file->subject->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">{{ __('messages.supervisor.files.not_tagged') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ number_format($file->file_size / 1048576, 2) }} MB</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">
                                    {{ $file->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $file->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $file->download_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ tenant_route('school.admin.file-browser.preview', [$school, $file->id]) }}"
                                       target="_blank"
                                       class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="{{ __('messages.common.preview') }}">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>
                                    <a href="{{ tenant_route('school.admin.file-browser.download', [$school, $file->id]) }}"
                                       class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                       title="{{ __('messages.common.download') }}">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="ri-file-search-line text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">{{ __('messages.supervisor.files.empty.title') }}</p>
                                    <p class="text-gray-400 text-sm mt-1">{{ __('messages.supervisor.files.empty.subtitle') }}</p>
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
