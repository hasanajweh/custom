{{-- resources/views/superadmin/subscriptions/edit.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'Edit Subscription')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Edit Subscription</h1>
            <p class="mt-2 text-gray-400">Modify subscription details for {{ $subscription->school->name }}</p>
        </div>

        <!-- Current Subscription Info -->
        <div class="glass rounded-lg p-6">
            <h2 class="text-lg font-medium text-white mb-4">Current Subscription Details</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">School:</span>
                    <span class="text-white ml-2">{{ $subscription->school->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Current Plan:</span>
                    <span class="text-white ml-2">{{ $subscription->plan->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Status:</span>
                    <span class="text-white ml-2">{{ ucfirst($subscription->status) }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Price:</span>
                    <span class="text-white ml-2">${{ number_format($subscription->plan->price_monthly / 100, 2) }}/mo</span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('superadmin.subscriptions.update', $subscription) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-6">Update Subscription</h2>

                <div class="space-y-6">
                    <!-- Plan -->
                    <div>
                        <label for="plan_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Subscription Plan
                        </label>
                        <select name="plan_id"
                                id="plan_id"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - ${{ number_format($plan->price_monthly / 100, 2) }}/mo
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                            Status
                        </label>
                        <select name="status"
                                id="status"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="paused" {{ $subscription->status == 'paused' ? 'selected' : '' }}>Paused</option>
                            <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-300 mb-2">
                                Start Date
                            </label>
                            <input type="date"
                                   name="starts_at"
                                   id="starts_at"
                                   value="{{ $subscription->starts_at->format('Y-m-d') }}"
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-300 mb-2">
                                End Date
                            </label>
                            <input type="date"
                                   name="ends_at"
                                   id="ends_at"
                                   value="{{ $subscription->ends_at->format('Y-m-d') }}"
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('superadmin.subscriptions.index') }}"
                   class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-glow px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
@endsection
