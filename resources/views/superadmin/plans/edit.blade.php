{{-- resources/views/superadmin/plans/edit.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'Edit Plan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Edit Plan</h1>
            <p class="mt-2 text-gray-400">Modify plan details for {{ $plan->name }}</p>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('superadmin.plans.update', $plan) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-6">Plan Details</h2>

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Plan Name
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $plan->name) }}"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="{{ old('slug', $plan->slug) }}"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        @error('slug')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="price_monthly" class="block text-sm font-medium text-gray-300 mb-2">
                                Monthly Price ($)
                            </label>
                            <input type="number"
                                   name="price_monthly"
                                   id="price_monthly"
                                   value="{{ old('price_monthly', $plan->price_monthly / 100) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            @error('price_monthly')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price_annually" class="block text-sm font-medium text-gray-300 mb-2">
                                Annual Price ($)
                            </label>
                            <input type="number"
                                   name="price_annually"
                                   id="price_annually"
                                   value="{{ old('price_annually', $plan->price_annually / 100) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            @error('price_annually')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Storage Limit -->
                    <div>
                        <label for="storage_limit_in_gb" class="block text-sm font-medium text-gray-300 mb-2">
                            Storage Limit (GB)
                        </label>
                        <input type="number"
                               name="storage_limit_in_gb"
                               id="storage_limit_in_gb"
                               value="{{ old('storage_limit_in_gb', $plan->storage_limit_in_gb) }}"
                               min="1"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        @error('storage_limit_in_gb')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Features
                        </label>
                        <div id="features-container" class="space-y-2">
                            @if($plan->features && is_array($plan->features))
                                @foreach($plan->features as $feature)
                                    <div class="feature-row flex items-center space-x-2">
                                        <input type="text"
                                               name="features[]"
                                               value="{{ $feature }}"
                                               class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="feature-row flex items-center space-x-2">
                                    <input type="text"
                                           name="features[]"
                                           placeholder="e.g., Unlimited users"
                                           class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addFeature()" class="mt-2 text-sm text-indigo-400 hover:text-indigo-300">
                            + Add another feature
                        </button>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ $plan->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2">
                            <span class="ml-3 text-sm text-gray-300">Active (available for new subscriptions)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Current Subscriptions Warning -->
            @if($plan->subscriptions()->exists())
                <div class="glass rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-300">
                                This plan has {{ $plan->subscriptions()->count() }} active subscriptions.
                                Changes will not affect existing subscriptions.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('superadmin.plans.index') }}"
                   class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-glow px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                    Update Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const newRow = document.createElement('div');
            newRow.className = 'feature-row flex items-center space-x-2';
            newRow.innerHTML = `
       <input type="text"
              name="features[]"
              placeholder="e.g., Priority support"
              class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
       <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300">
           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
           </svg>
       </button>
   `;
            container.appendChild(newRow);
        }

        function removeFeature(button) {
            const container = document.getElementById('features-container');
            if (container.children.length > 1) {
                button.closest('.feature-row').remove();
            }
        }
    </script>
@endsection
