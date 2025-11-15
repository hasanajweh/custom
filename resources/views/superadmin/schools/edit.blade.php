@extends('layouts.superadmin')

@section('page-title', 'Edit School')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6" x-data="{
    storageGB: {{ old('storage_limit_gb', $school->storage_limit / 1073741824) }},
    selectedPlan: {{ old('plan_id', $currentSubscription?->plan_id ?? 'null') }}
}">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Edit School</h1>
            <p class="mt-2 text-gray-400">Update school information for {{ $school->name }}</p>
        </div>

        <!-- School Stats -->
        <div class="grid grid-cols-4 gap-4">
            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Users</p>
                        <p class="text-2xl font-bold text-white">{{ $school->users()->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Files</p>
                        <p class="text-2xl font-bold text-white">{{ $school->fileSubmissions()->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Storage Used</p>
                        <p class="text-2xl font-bold text-white">{{ round($school->storage_used / 1073741824, 1) }} GB</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Usage</p>
                        <p class="text-2xl font-bold text-white">{{ $school->storage_used_percentage }}%</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('superadmin.schools.update', $school) }}">
            @csrf
            @method('PATCH')

            <!-- Basic Information -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Basic Information</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            School Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $school->name) }}"
                               required
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug', $school->slug) }}"
                               required
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        @error('slug')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="flex items-center p-3 rounded-lg border border-gray-700 hover:bg-gray-800 transition cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ $school->is_active ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-medium text-gray-300">School is Active</span>
                    </label>
                </div>
            </div>

            <!-- Storage Management -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Storage Management</h2>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-300">
                            Storage Limit
                        </label>
                        <span class="text-2xl font-bold text-indigo-400" x-text="storageGB + ' GB'"></span>
                    </div>

                    <!-- Storage Slider -->
                    <input type="range"
                           name="storage_limit_gb"
                           x-model="storageGB"
                           min="1"
                           max="1000"
                           step="1"
                           class="w-full h-3 bg-gray-700 rounded-lg appearance-none cursor-pointer slider">

                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>1 GB</span>
                        <span>250 GB</span>
                        <span>500 GB</span>
                        <span>750 GB</span>
                        <span>1000 GB</span>
                    </div>

                    <!-- Storage Bar -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-400">Current Usage</span>
                            <span class="text-gray-300">
                            {{ round($school->storage_used / 1073741824, 2) }} GB /
                            <span x-text="storageGB"></span> GB
                        </span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                            @php
                                $usedGB = $school->storage_used / 1073741824;
                                $percentage = min(($usedGB / ($school->storage_limit / 1073741824)) * 100, 100);
                            @endphp
                            <div class="h-full {{ $percentage >= 90 ? 'bg-red-500' : ($percentage >= 75 ? 'bg-orange-500' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-green-500')) }} transition-all duration-300"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ round($percentage, 1) }}% used</p>
                    </div>

                    @error('storage_limit_gb')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subscription Plan -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Subscription Plan</h2>

                @if($currentSubscription)
                    <div class="mb-6 p-4 bg-blue-900 bg-opacity-20 border border-blue-500 border-opacity-30 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-300">
                                    Current Plan: <strong>{{ $currentSubscription->plan->name }}</strong>
                                    • Status: <strong class="uppercase">{{ $currentSubscription->status }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($plans as $plan)
                        <label @click="selectedPlan = {{ $plan->id }}"
                               :class="{ 'ring-2 ring-indigo-500 border-indigo-500': selectedPlan === {{ $plan->id }} }"
                               class="relative flex flex-col cursor-pointer rounded-xl border border-gray-700 bg-gray-800 p-4 hover:border-gray-600 transition-all">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" class="sr-only" x-model="selectedPlan">

                            <div x-show="selectedPlan === {{ $plan->id }}"
                                 class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>

                            <h3 class="text-base font-bold text-white mb-2">{{ $plan->name }}</h3>

                            <div class="mb-3">
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-bold text-white">${{ number_format($plan->price_monthly / 100, 2) }}</span>
                                    <span class="text-gray-400 ml-1 text-xs">/mo</span>
                                </div>
                            </div>

                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-4 h-4 text-indigo-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7"></path>
                                </svg>
                                {{ $plan->storage_limit_in_gb }} GB
                            </div>
                        </label>
                    @endforeach
                </div>

                <p class="mt-4 text-sm text-gray-500">
                    Changing the plan will update the subscription. The new plan will take effect immediately.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <button type="button"
                        onclick="if(confirm('Are you sure? This will delete all associated data including users and files.')) { document.getElementById('delete-form').submit(); }"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Delete School
                </button>

                <div class="flex space-x-4">
                    <a href="{{ route('superadmin.schools.index') }}"
                       class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-glow px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden delete form -->
        <form id="delete-form" action="{{ route('superadmin.schools.destroy', $school) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <style>
        /* Custom slider styling */
        .slider::-webkit-slider-thumb {
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6366f1;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .slider::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6366f1;
            cursor: pointer;
            border: none;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .slider::-webkit-slider-thumb:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }

        .slider::-moz-range-thumb:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }
    </style>
@endsection
