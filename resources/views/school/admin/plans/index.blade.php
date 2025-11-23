@extends('layouts.school')

@php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? Auth::user()->school;
@endphp

@section('title', __('messages.plans.title') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('messages.plans.teacher_plans') }}</h1>
                        <p class="text-lg text-gray-600">{{ __('messages.plans.browse_manage_plans') }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.plans.total_plans') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_plans'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                            <i class="ri-file-list-3-line text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.plans.daily_plans') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['daily_plans'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <i class="ri-calendar-line text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.plans.weekly_plans') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['weekly_plans'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                            <i class="ri-calendar-2-line text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ __('messages.plans.monthly_plans') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['monthly_plans'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl">
                            <i class="ri-calendar-check-line text-2xl text-amber-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.plans.filter_plans') }}</h2>
                    <p class="text-gray-600">{{ __('messages.plans.use_filters') }}</p>
                </div>

                <form method="GET" action="{{ tenant_route('school.admin.plans.index', $school) }}" class="space-y-6">
                    <!-- Search Bar -->
                    <div class="w-full">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.plans.search_plans') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('messages.plans.search_by_title') }}"
                                   class="w-full pl-12 pr-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="plan_type" class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.plans.plan_type') }}</label>
                            <select name="plan_type" id="plan_type"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors duration-200">
                                <option value="">{{ __('messages.files.all_plan_types') }}</option>
                                <option value="daily_plan" {{ request('plan_type') === 'daily_plan' ? 'selected' : '' }}>{{ __('messages.plans.daily_plans') }}</option>
                                <option value="weekly_plan" {{ request('plan_type') === 'weekly_plan' ? 'selected' : '' }}>{{ __('messages.plans.weekly_plans') }}</option>
                                <option value="monthly_plan" {{ request('plan_type') === 'monthly_plan' ? 'selected' : '' }}>{{ __('messages.plans.monthly_plans') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.users.teacher') }}</label>
                            <select name="teacher_id" id="teacher_id"
                                    class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors duration-200">
                                <option value="">{{ __('messages.users.teachers') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.files.from_date') }}</label>
                            <input type="date"
                                   name="date_from"
                                   id="date_from"
                                   value="{{ request('date_from') }}"
                                   class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-3">{{ __('messages.files.to_date') }}</label>
                            <input type="date"
                                   name="date_to"
                                   id="date_to"
                                   value="{{ request('date_to') }}"
                                   class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 space-x-2">
                            <i class="ri-search-line"></i>
                            <span>{{ __('messages.plans.search_plans') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Plans Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.plans.all_plans') }}</h2>
                    <p class="text-gray-600 mt-1">{{ __('messages.plans.plans_found', ['count' => $plans->total()]) }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.files.title_file') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.type') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.teacher') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.plans.uploaded') }}</th>
                            <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                @php
                                                    $extension = $plan->file_path ? strtoupper(pathinfo($plan->file_path, PATHINFO_EXTENSION)) : 'FILE';
                                                @endphp
                                                <span class="text-xs font-bold text-blue-600">{{ $extension }}</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 text-sm truncate">{{ $plan->title }}</div>
                                            <div class="text-xs text-gray-500 mt-1">.{{ strtolower($extension) }} {{ __('messages.files.file') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    @if($plan->submission_type === 'daily_plan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="ri-calendar-line mr-1"></i>
                                            {{ __('messages.plans.daily') }}
                                        </span>
                                    @elseif($plan->submission_type === 'weekly_plan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="ri-calendar-2-line mr-1"></i>
                                            {{ __('messages.plans.weekly') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <i class="ri-calendar-check-line mr-1"></i>
                                            {{ __('messages.plans.monthly') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">{{ substr($plan->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="text-sm text-gray-900">{{ $plan->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="text-sm text-gray-900">{{ $plan->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $plan->created_at->format('l') }} • {{ $plan->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $extension = strtolower(pathinfo($plan->file_path, PATHINFO_EXTENSION));
                                        $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                    @endphp
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Preview Button --}}
                                        @if($canPreview)
                                            <a href="{{ tenant_route('school.admin.plans.show', [$school, $plan]) }}"
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
                                        <a href="{{ tenant_route('school.admin.plans.download', [$school, $plan]) }}"
                                           class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                           title="{{ __('messages.files.download_file') }}">
                                            <i class="ri-download-line text-lg"></i>
                                        </a>

                                        {{-- Print Button --}}
                                        @if($canPreview)
                                            <button onclick="printFile('{{ tenant_route('school.admin.plans.show', [$school, $plan]) }}', '{{ $plan->title }}', '{{ $plan->user->name }}', '{{ $plan->created_at->format('M d, Y') }}')"
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
                                            <i class="ri-file-list-3-line text-2xl text-gray-400"></i>
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

                @if($plans->hasPages())
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                {{ __('messages.files.showing_results', [
                                    'first' => $plans->firstItem(),
                                    'last' => $plans->lastItem(),
                                    'total' => $plans->total()
                                ]) }}
                            </div>
                            <div class="pagination-wrapper">
                                {{ $plans->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pagination-wrapper .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-wrapper .pagination .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-wrapper .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination-wrapper .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
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
