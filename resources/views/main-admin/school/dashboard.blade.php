@extends('layouts.network')

@section('title', __('messages.dashboard.admin_dashboard') . ' - ' . $school->name)

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Back to Main Admin Banner -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-4 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="ri-eye-line text-2xl"></i>
                <div>
                    <p class="font-semibold">{{ __('messages.main_admin.viewing_as_admin') }}</p>
                    <p class="text-sm text-yellow-100">{{ $school->name }}</p>
                </div>
            </div>
            <a href="{{ route('main-admin.dashboard', ['network' => $network->slug]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-medium transition-all duration-200">
                <i class="ri-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}-line"></i>
                {{ __('messages.main_admin.back_to_main_dashboard') }}
            </a>
        </div>
    </div>

    <!-- School Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $school->name }}</h1>
                <p class="text-indigo-100 text-lg">{{ __('messages.dashboard.admin_dashboard') }}</p>
                <p class="text-indigo-200 text-sm mt-1">{{ $school->city ?? __('messages.city_not_set') }}</p>
            </div>
            <div class="mt-4 md:mt-0 {{ app()->getLocale() === 'ar' ? 'text-left' : 'text-right' }}">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                    <i class="ri-checkbox-circle-line"></i>
                    <span class="font-semibold">{{ $school->is_active ? __('messages.status.active') : __('messages.status.archived') }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-user-line text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_users') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $school->users_count ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="ri-file-list-3-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.total_files') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $school->file_submissions_count ?? 0 }}</p>
            <p class="text-xs text-green-600 mt-1">
                <i class="ri-arrow-up-line"></i> {{ $school->recent_files_count ?? 0 }} {{ __('messages.last_72_hours_label') }}
            </p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="ri-book-open-line text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.dashboard.subjects') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $school->subjects_count ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="ri-graduation-cap-line text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-1">{{ __('messages.grades_label') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $school->grades_count ?? 0 }}</p>
        </div>
    </div>

    <!-- Role Distribution -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.team_distribution') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-purple-600 font-medium mb-1">{{ __('messages.admins') }}</p>
                        <p class="text-3xl font-bold text-purple-700">{{ $roleCounts['admin'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center">
                        <i class="ri-user-star-line text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium mb-1">{{ __('messages.supervisors') }}</p>
                        <p class="text-3xl font-bold text-green-700">{{ $roleCounts['supervisor'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center">
                        <i class="ri-user-search-line text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium mb-1">{{ __('messages.teachers') }}</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $roleCounts['teacher'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center">
                        <i class="ri-user-line text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
