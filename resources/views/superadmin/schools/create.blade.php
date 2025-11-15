@extends('layouts.superadmin')

@section('page-title', 'Create New School')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-red-300 mb-2">Please fix these errors:</h3>
                <ul class="text-sm text-red-200 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">Create New School</h1>
            <p class="text-gray-400 mt-2">Fill in all details to create a new school with pending subscription</p>
        </div>

        <form method="POST" action="{{ route('superadmin.schools.store') }}" id="createSchoolForm">
            @csrf

            <!-- School Details -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    School Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label for="school_name" class="block text-sm font-medium text-gray-300 mb-2">
                            School Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="school_name"
                               value="{{ old('name') }}"
                               required
                               placeholder="Lincoln High School"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL Slug -->
                    <div>
                        <label for="school_slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="slug"
                               id="school_slug"
                               value="{{ old('slug') }}"
                               required
                               placeholder="lincoln-high"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Auto-generated from school name</p>
                        @error('slug')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Plan Selection -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Subscription Plan <span class="text-red-400 ml-1">*</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($plans as $plan)
                        <label for="plan_{{ $plan->id }}"
                               class="plan-option block border-2 rounded-lg transition cursor-pointer {{ $loop->first ? 'border-indigo-500 bg-indigo-900 bg-opacity-20' : 'border-gray-700 hover:border-gray-600' }}">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <input type="radio"
                                           name="plan_id"
                                           id="plan_{{ $plan->id }}"
                                           value="{{ $plan->id }}"
                                           {{ old('plan_id') == $plan->id ? 'checked' : ($loop->first && !old('plan_id') ? 'checked' : '') }}
                                           required
                                           class="w-5 h-5 text-indigo-600 bg-gray-800 border-gray-600 focus:ring-indigo-500">
                                    <div class="check-icon {{ $loop->first ? '' : 'hidden' }}">
                                        <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-white mb-1">{{ $plan->name }}</h3>
                                <p class="text-sm text-gray-400 mb-3">{{ $plan->storage_limit_in_gb }} GB Storage</p>

                                <div class="text-2xl font-bold text-indigo-400 mb-1">
                                    ${{ number_format($plan->price_monthly / 100, 2) }}
                                </div>
                                <p class="text-xs text-gray-400">per month</p>

                                @if($plan->features && is_array($plan->features) && count($plan->features) > 0)
                                    <div class="mt-3 space-y-1">
                                        @foreach(array_slice($plan->features, 0, 2) as $feature)
                                            <div class="flex items-center text-xs text-gray-300">
                                                <svg class="w-3 h-3 mr-1 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>{{ $feature }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full text-center py-8 bg-red-900/20 border border-red-500 rounded-lg">
                            <p class="text-red-300 mb-4">No plans available. Please create a plan first.</p>
                            <a href="{{ route('superadmin.plans.create') }}"
                               class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                                Create Plan
                            </a>
                        </div>
                    @endforelse
                </div>

                @error('plan_id')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin User Credentials -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    School Admin Account
                </h2>

                <div class="space-y-6">
                    <!-- Admin Name -->
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Admin Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="admin_name"
                               id="admin_name"
                               value="{{ old('admin_name') }}"
                               required
                               placeholder="John Doe"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        @error('admin_name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Email -->
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Admin Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email"
                               name="admin_email"
                               id="admin_email"
                               value="{{ old('admin_email') }}"
                               required
                               placeholder="admin@school.com"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        @error('admin_email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="admin_password" class="block text-sm font-medium text-gray-300 mb-2">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password"
                                   name="admin_password"
                                   id="admin_password"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                            @error('admin_password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                Confirm Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password"
                                   name="admin_password_confirmation"
                                   id="admin_password_confirmation"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-blue-900 bg-opacity-20 border border-blue-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-300">What happens when you submit?</h4>
                        <ul class="text-sm text-blue-200 mt-2 space-y-1">
                            <li>• School will be created as <strong>inactive</strong></li>
                            <li>• Admin user account will be created</li>
                            <li>• Subscription will be created with <strong>pending</strong> status</li>
                            <li>• You can activate it from the Subscriptions page</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('superadmin.schools.index') }}"
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-lg transition flex items-center shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create School
                </button>
            </div>
        </form>
    </div>

    <style>
        .glass {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .plan-option {
            transition: all 0.3s ease;
        }

        .plan-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('school_name');
            const slugInput = document.getElementById('school_slug');
            const planRadios = document.querySelectorAll('input[name="plan_id"]');

            // Auto-generate slug from name
            nameInput.addEventListener('input', function(e) {
                const slug = e.target.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            });

            // Plan selection visual feedback
            planRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selection from all
                    document.querySelectorAll('.plan-option').forEach(option => {
                        option.classList.remove('border-indigo-500', 'bg-indigo-900', 'bg-opacity-20');
                        option.classList.add('border-gray-700');
                        option.querySelector('.check-icon')?.classList.add('hidden');
                    });

                    // Add to selected
                    const selected = this.closest('.plan-option');
                    if (selected) {
                        selected.classList.add('border-indigo-500', 'bg-indigo-900', 'bg-opacity-20');
                        selected.classList.remove('border-gray-700');
                        const checkIcon = selected.querySelector('.check-icon');
                        if (checkIcon) {
                            checkIcon.classList.remove('hidden');
                        }
                    }
                });
            });
        });
    </script>
@endsection
