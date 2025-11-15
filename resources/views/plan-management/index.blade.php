{{-- resources/views/school/admin/plan-management/index.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.subscription.plan_management') . ' - ' . __('messages.app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('messages.subscription.plan_management') }}</h1>
                        <p class="text-lg text-gray-600 mt-1">{{ __('messages.subscription.view_subscription') }}</p>
                    </div>
                    @if($currentSubscription)
                        <div class="flex items-center gap-2">
                            @if($currentSubscription->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold text-sm">
                                    <i class="ri-checkbox-circle-fill"></i>
                                    {{ __('messages.status.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold text-sm">
                                    <i class="ri-time-line"></i>
                                    {{ ucfirst($currentSubscription->status) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($currentSubscription)
                <!-- Current Plan Overview -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $currentSubscription->plan->name }}</h2>
                                <p class="text-gray-600 mt-1">{{ __('messages.subscription.current_subscription') }}</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="ri-vip-crown-line text-3xl text-blue-600"></i>
                            </div>
                        </div>

                        <!-- Quick Stats Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('messages.subscription.expires_on') }}</p>
                                        <p class="text-lg font-bold text-gray-900 mt-1">
                                            {{ $currentSubscription->ends_at ? $currentSubscription->ends_at->format('M d, Y') : __('messages.subscription.never') }}
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-calendar-line text-red-600"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('messages.subscription.days_remaining') }}</p>
                                        <p class="text-lg font-bold text-gray-900 mt-1">
                                            {{ $currentSubscription->ends_at ? $currentSubscription->ends_at->diffInDays(now()) : __('messages.subscription.unlimited') }}
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-time-line text-orange-600"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('messages.subscription.storage_limit') }}</p>
                                        <p class="text-lg font-bold text-gray-900 mt-1">
                                            {{ $currentSubscription->plan->storage_limit_in_gb ?? 10 }} GB
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-database-2-line text-purple-600"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">{{ __('messages.subscription.storage_used') }}</p>
                                        <p class="text-lg font-bold text-gray-900 mt-1">
                                            {{ $school->storage_used_formatted }}
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="ri-hard-drive-2-line text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Storage Usage Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ __('messages.subscription.storage_usage') }}</h3>
                            <p class="text-gray-600 mt-1">{{ __('messages.subscription.gb_used', [
                                'used' => $school->storage_used_formatted,
                                'total' => $currentSubscription->plan->storage_limit_in_gb ?? 10,
                                'percentage' => $school->storage_used_percentage
                            ]) }}</p>
                        </div>
                        <div class="storage-percentage">
                            <span class="text-3xl font-bold text-gray-900">{{ $school->storage_used_percentage }}%</span>
                        </div>
                    </div>

                    <!-- Storage Progress Bar -->
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div class="storage-bar h-4 rounded-full"
                                 style="width: {{ min($school->storage_used_percentage, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm text-gray-600">
                            <span>0 GB</span>
                            <span>{{ $currentSubscription->plan->storage_limit_in_gb ?? 10 }} GB</span>
                        </div>
                    </div>
                </div>

                <!-- Plan Features -->
                @if($currentSubscription->plan->features)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.subscription.plan_features') }}</h2>
                            <p class="text-gray-600 mt-1">{{ __('messages.subscription.features_included') }}</p>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($currentSubscription->plan->features as $feature)
                                    <div class="flex items-start gap-3 p-4 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mt-0.5">
                                            <i class="ri-check-line text-white text-sm"></i>
                                        </div>
                                        <span class="text-gray-900 font-medium">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Subscription Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.subscription.subscription_details') }}</h2>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.subscription.plan_name') }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $currentSubscription->plan->name }}</span>
                                </div>

                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.subscription.status') }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ ucfirst($currentSubscription->status) }}</span>
                                </div>

                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.labels.started') }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $currentSubscription->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.subscription.expires_on') }}</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $currentSubscription->ends_at ? $currentSubscription->ends_at->format('M d, Y') : __('messages.subscription.never') }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.subscription.days_remaining') }}</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $currentSubscription->ends_at ? $currentSubscription->ends_at->diffInDays(now()) : __('messages.subscription.unlimited') }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">{{ __('messages.labels.auto_renew') }}</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        <i class="ri-checkbox-circle-fill text-green-600"></i>
                                        {{ __('messages.status.enabled') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- No Subscription State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
                    <div class="text-center max-w-md mx-auto">
                        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ri-error-warning-line text-4xl text-yellow-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('messages.subscription.no_subscription') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('messages.subscription.contact_administrator') }}</p>
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('school.admin.dashboard', $school->slug) }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                <i class="ri-dashboard-line"></i>
                                {{ __('messages.navigation.dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Storage Bar Colors */
        .storage-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .storage-percentage {
            text-align: center;
        }

        /* RTL Support */
        [dir="rtl"] .storage-bar {
            background: linear-gradient(-90deg, #3b82f6 0%, #2563eb 100%);
        }
    </style>
@endpush
