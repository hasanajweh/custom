@extends('layouts.network')

@section('title', __('messages.dashboard.dashboard'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $network->name }}</h1>
                <p class="text-indigo-100">{{ __('messages.network_overview') }}</p>
            </div>
            <div class="mt-4 md:mt-0 {{ app()->getLocale() === 'ar' ? 'text-left' : 'text-right' }}">
                <p class="text-sm text-indigo-100">{{ __('messages.dashboard.current_time') }}</p>
                <p class="text-2xl font-semibold">{{ now('Asia/Gaza')->format('h:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-building-line text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_branches') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ (int) ($summary['branches'] ?? 0) }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="ri-file-list-3-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_files_across_branches') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ (int) ($summary['files'] ?? 0) }}</p>
            <p class="text-xs text-green-600 mt-1">
                <i class="ri-arrow-up-line"></i> {{ (int) ($summary['recent_files'] ?? 0) }} {{ __('messages.last_72_hours_label') }}
            </p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="ri-calendar-schedule-line text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.plans_across_branches') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ (int) ($summary['plans'] ?? 0) }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="ri-book-open-line text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.dashboard.subjects') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ (int) ($summary['subjects'] ?? 0) }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="ri-graduation-cap-line text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.grades_label') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ (int) ($summary['grades'] ?? 0) }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center">
                    <i class="ri-team-line text-teal-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-2">{{ __('messages.team_distribution') }}</p>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.admins') }}</span>
                    <span class="font-semibold text-gray-900">{{ (int) ($summary['admins'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.supervisors') }}</span>
                    <span class="font-semibold text-gray-900">{{ (int) ($summary['supervisors'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('messages.teachers') }}</span>
                    <span class="font-semibold text-gray-900">{{ (int) ($summary['teachers'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Grid -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.branches') }}</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($branches as $branch)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ mb_substr($branch->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $branch->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $branch->city ?? __('messages.city_not_set') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $branch->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $branch->is_active ? __('messages.status.active') : __('messages.status.archived') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-indigo-600">{{ (int) ($branch->file_submissions_count ?? 0) }}</p>
                                <p class="text-xs text-gray-500">{{ __('messages.files_count') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ (int) ($branch->recent_files_count ?? 0) }}</p>
                                <p class="text-xs text-gray-500">{{ __('messages.recent_uploads_72h') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-3 gap-2 text-center text-sm">
                            <div>
                                <p class="font-semibold text-gray-900">{{ (int) ($branch->admins_count ?? 0) }}</p>
                                <p class="text-xs text-gray-500">{{ __('messages.admins') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ (int) ($branch->supervisors_count ?? 0) }}</p>
                                <p class="text-xs text-gray-500">{{ __('messages.supervisors') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ (int) ($branch->teachers_count ?? 0) }}</p>
                                <p class="text-xs text-gray-500">{{ __('messages.teachers') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.recent_branch_uploads') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('messages.last_72_hours_label') }}</p>
                </div>
            </div>
        </div>

        @if($recentUploads->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-file-search-line text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500">{{ __('messages.no_recent_uploads') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.title') }}</th>
                            <th class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.branch') }}</th>
                            <th class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.user') }}</th>
                            <th class="px-6 py-3 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('messages.submitted_at') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($recentUploads as $upload)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <i class="ri-file-text-line text-indigo-600"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $upload->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $upload->school?->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $upload->user?->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $upload->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
