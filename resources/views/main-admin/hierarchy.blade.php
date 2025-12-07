@extends('layouts.network')

@section('title', __('messages.main_admin.hierarchy.title'))

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $network->name }}</h1>
                <p class="text-indigo-100 text-lg">{{ __('messages.main_admin.hierarchy.heading') }}</p>
                <p class="text-indigo-200 text-sm mt-1">{{ __('messages.main_admin.hierarchy.subtitle') }}</p>
            </div>
            <div class="mt-4 md:mt-0 {{ app()->getLocale() === 'ar' ? 'text-left' : 'text-right' }}">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                    <p class="text-sm text-indigo-100">{{ __('messages.total_branches') }}</p>
                    <p class="text-3xl font-bold">{{ $branches->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($branches as $branch)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                <!-- Branch Header -->
                <div class="relative h-32 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                {{ mb_substr($branch->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ $branch->name }}</h3>
                                <p class="text-sm text-indigo-100 flex items-center gap-1 mt-1">
                                    <i class="ri-map-pin-line"></i>
                                    {{ $branch->city ?? __('messages.city_not_set') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $branch->is_active ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }} shadow-lg">
                            {{ $branch->is_active ? __('messages.status.active') : __('messages.status.archived') }}
                        </span>
                    </div>
                </div>

                <!-- Branch Stats -->
                <div class="p-6">
                    <!-- Quick Stats Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center border border-blue-200">
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center mx-auto mb-2">
                                <i class="ri-user-star-line text-white text-xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-blue-700">{{ $branch->admins_count ?? 0 }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ __('messages.admins') }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center border border-green-200">
                            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center mx-auto mb-2">
                                <i class="ri-user-search-line text-white text-xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-green-700">{{ $branch->supervisors_count ?? 0 }}</p>
                            <p class="text-xs text-green-600 font-medium">{{ __('messages.supervisors') }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center border border-purple-200">
                            <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center mx-auto mb-2">
                                <i class="ri-user-line text-white text-xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-purple-700">{{ $branch->teachers_count ?? 0 }}</p>
                            <p class="text-xs text-purple-600 font-medium">{{ __('messages.teachers') }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 text-center border border-orange-200">
                            <div class="w-10 h-10 rounded-lg bg-orange-500 flex items-center justify-center mx-auto mb-2">
                                <i class="ri-book-open-line text-white text-xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-orange-700">{{ ($branch->subjects_count ?? 0) + ($branch->grades_count ?? 0) }}</p>
                            <p class="text-xs text-orange-600 font-medium">{{ __('messages.subjects') }} & {{ __('messages.grades_label') }}</p>
                        </div>
                    </div>

                    <!-- Detailed Stats -->
                    <div class="space-y-2 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="ri-book-2-line text-indigo-500"></i>
                                {{ __('messages.subjects') }}
                            </span>
                            <span class="font-bold text-gray-900">{{ $branch->subjects_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="ri-graduation-cap-line text-purple-500"></i>
                                {{ __('messages.grades_label') }}
                            </span>
                            <span class="font-bold text-gray-900">{{ $branch->grades_count ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- View School Button -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('main-admin.hierarchy.impersonate', ['network' => $network->slug, 'school' => $branch->slug]) }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="ri-eye-line"></i>
                            {{ __('messages.main_admin.view_as_admin') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($branches->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-12 text-center">
            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="ri-building-line text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.main_admin.no_branches') }}</h3>
            <p class="text-gray-500">{{ __('messages.main_admin.no_branches_description') }}</p>
        </div>
    @endif
</div>
@endsection
