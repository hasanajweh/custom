@extends('layouts.superadmin')

@section('page-title', 'Manage Subscriptions')

@push('styles')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .stat-icon {
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .glass-morphism {
            background: rgba(31, 41, 55, 0.4);
            backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-border {
            position: relative;
            background: rgba(31, 41, 55, 0.6);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 0.75rem;
            padding: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .subscription-row {
            transition: all 0.3s ease;
        }

        .subscription-row:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateX(5px);
        }

        .action-button {
            transition: all 0.2s ease;
        }

        .action-button:hover {
            transform: scale(1.1);
        }

        .status-badge {
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .filter-input {
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.2);
        }

        .pending-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .revenue-badge {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .table-header {
            background: linear-gradient(180deg, rgba(55, 65, 81, 0.5) 0%, rgba(31, 41, 55, 0.5) 100%);
            backdrop-filter: blur(10px);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8">
        <!-- Epic Header with Gradient -->
        <div class="relative overflow-hidden rounded-2xl p-8 glass-morphism animate-fade-in-up">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 via-purple-600/20 to-pink-600/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="p-4 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-2xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-200 to-purple-200">
                            Subscriptions Management
                        </h1>
                        <p class="mt-2 text-lg text-gray-300 font-medium">
                            Monitor and manage all school subscriptions
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="revenue-badge px-6 py-3 rounded-xl">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-white/80 font-medium">Total Revenue</p>
                                <p class="text-xl font-bold text-white">${{ number_format($totalRevenue / 100, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Active Subscriptions -->
            <div class="stat-card rounded-2xl p-6 border-l-4 border-green-500 animate-slide-in-right" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Active</p>
                        <p class="text-4xl font-black text-green-400 mt-2">{{ $activeCount }}</p>
                        <div class="mt-3 flex items-center text-sm text-green-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Running smoothly</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-green-900/50 to-emerald-800/30 rounded-2xl">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Subscriptions -->
            <div class="stat-card rounded-2xl p-6 border-l-4 border-yellow-500 animate-slide-in-right" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Pending Activation</p>
                        <p class="text-4xl font-black text-yellow-400 mt-2">{{ $subscriptions->where('status', 'pending')->count() }}</p>
                        <div class="mt-3 flex items-center text-sm text-yellow-300">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2 pending-pulse"></div>
                            <span class="font-semibold">Awaiting action</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-yellow-900/50 to-orange-800/30 rounded-2xl">
                        <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expiring Soon -->
            <div class="stat-card rounded-2xl p-6 border-l-4 border-orange-500 animate-slide-in-right" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Expiring Soon</p>
                        <p class="text-4xl font-black text-orange-400 mt-2">{{ $expiringSoonCount }}</p>
                        <div class="mt-3 flex items-center text-sm text-orange-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Next 30 days</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-orange-900/50 to-red-800/30 rounded-2xl">
                        <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cancelled -->
            <div class="stat-card rounded-2xl p-6 border-l-4 border-red-500 animate-slide-in-right" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Cancelled</p>
                        <p class="text-4xl font-black text-red-400 mt-2">{{ $cancelledCount }}</p>
                        <div class="mt-3 flex items-center text-sm text-red-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Inactive</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-red-900/50 to-pink-800/30 rounded-2xl">
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="gradient-border rounded-2xl animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="glass-morphism rounded-2xl p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white">Advanced Filters</h3>
                </div>

                <form method="GET" action="{{ route('superadmin.subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Search Schools</span>
                            </span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by school name..."
                                   class="filter-input w-full pl-10 pr-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Status</label>
                        <select name="status" class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <!-- Plan Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Plan</label>
                        <select name="plan" class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Plans</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-4 flex items-center justify-end space-x-3">
                        <a href="{{ route('superadmin.subscriptions.index') }}"
                           class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-white font-semibold rounded-xl transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Clear</span>
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-indigo-500/50 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Apply Filters</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="gradient-border rounded-2xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="glass-morphism">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="table-header">
                        <tr class="border-b border-gray-700">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">School</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">Plan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">Period</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-300">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                        @forelse($subscriptions as $index => $subscription)
                            <tr class="subscription-row" style="animation: fadeInUp 0.5s ease-out {{ ($index * 0.05) }}s forwards; opacity: 0;">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 flex-shrink-0 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center font-bold text-white text-lg shadow-lg">
                                            {{ strtoupper(substr($subscription->school->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-white">{{ $subscription->school->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $subscription->school->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-white">{{ $subscription->plan->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $subscription->plan->storage_limit_in_gb }} GB</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @if(isset($subscription->status))
                                        @if($subscription->status === 'pending')
                                            <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-yellow-900/50 text-yellow-200 ring-2 ring-yellow-500/50">
                                                    <span class="w-2 h-2 mr-2 bg-yellow-400 rounded-full pending-pulse"></span>
                                                    Pending
                                                </span>
                                        @elseif($subscription->status === 'active')
                                            <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-900/50 text-green-200 ring-2 ring-green-500/50">
                                                    <span class="w-2 h-2 mr-2 bg-green-400 rounded-full"></span>
                                                    Active
                                                </span>
                                        @elseif($subscription->status === 'paused')
                                            <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-orange-900/50 text-orange-200 ring-2 ring-orange-500/50">
                                                    <span class="w-2 h-2 mr-2 bg-orange-400 rounded-full"></span>
                                                    Paused
                                                </span>
                                        @elseif($subscription->status === 'cancelled')
                                            <span class="status-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-900/50 text-red-200 ring-2 ring-red-500/50">
                                                    <span class="w-2 h-2 mr-2 bg-red-400 rounded-full"></span>
                                                    Cancelled
                                                </span>
                                        @else
                                            <span class="text-sm text-gray-400">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($subscription->starts_at)
                                            <div class="text-sm text-gray-300">{{ $subscription->starts_at->format('M d, Y') }}</div>
                                        @endif
                                        @if($subscription->ends_at)
                                            <div class="text-sm text-gray-300">{{ $subscription->ends_at->format('M d, Y') }}</div>
                                            @if($subscription->ends_at->isPast())
                                                <span class="text-xs text-red-400">Expired</span>
                                            @elseif($subscription->ends_at->diffInDays() <= 30)
                                                <span class="text-xs text-yellow-400">{{ $subscription->ends_at->diffInDays() }} days left</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500">No dates</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-base font-bold text-white">${{ number_format($subscription->plan->price_monthly / 100, 2) }}</div>
                                    <div class="text-xs text-gray-400">per month</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="action-button p-2 bg-indigo-600/20 hover:bg-indigo-600/30 rounded-lg" title="Edit">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if(isset($subscription->status))
                                            @if($subscription->status === 'pending')
                                                <form method="POST" action="{{ route('superadmin.subscriptions.activate', $subscription) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="action-button p-2 bg-green-600/20 hover:bg-green-600/30 rounded-lg" onclick="return confirm('Activate subscription for {{ $subscription->school->name }}?')" title="Activate">
                                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @elseif($subscription->status === 'active')
                                                <form method="POST" action="{{ route('superadmin.subscriptions.pause', $subscription) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="action-button p-2 bg-yellow-600/20 hover:bg-yellow-600/30 rounded-lg" title="Pause">
                                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @elseif($subscription->status === 'paused')
                                                <form method="POST" action="{{ route('superadmin.subscriptions.resume', $subscription) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="action-button p-2 bg-green-600/20 hover:bg-green-600/30 rounded-lg" title="Resume">
                                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        <a href="{{ route('superadmin.schools.edit', $subscription->school) }}" class="action-button p-2 bg-purple-600/20 hover:bg-purple-600/30 rounded-lg" title="View School">
                                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-800/50 rounded-full mb-4">
                                            <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-white mb-2">No Subscriptions Found</h3>
                                        <p class="text-gray-400 max-w-md mb-6">No subscriptions match your current filters.</p>
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('superadmin.subscriptions.index') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-xl">Clear Filters</a>
                                            <a href="{{ route('superadmin.schools.create') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl">Create School</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($subscriptions->hasPages())
            <div class="glass-morphism rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing <span class="font-semibold text-white">{{ $subscriptions->firstItem() }}</span>
                        to <span class="font-semibold text-white">{{ $subscriptions->lastItem() }}</span>
                        of <span class="font-semibold text-white">{{ $subscriptions->total() }}</span> subscriptions
                    </div>
                    <div class="pagination-wrapper">
                        {{ $subscriptions->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats Footer -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.8s;">
            <div class="glass-morphism rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Average Subscription Value</p>
                        <p class="text-2xl font-bold text-white mt-1">
                            ${{ $activeCount > 0 ? number_format($totalRevenue / $activeCount / 100, 2) : '0.00' }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-900/30 rounded-xl">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-morphism rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Renewal Rate</p>
                        <p class="text-2xl font-bold text-white mt-1">
                            {{ $totalCount > 0 ? number_format(($activeCount / $totalCount) * 100, 1) : '0' }}%
                        </p>
                    </div>
                    <div class="p-3 bg-green-900/30 rounded-xl">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-morphism rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-medium">Churn Rate</p>
                        <p class="text-2xl font-bold text-white mt-1">
                            {{ $totalCount > 0 ? number_format(($cancelledCount / $totalCount) * 100, 1) : '0' }}%
                        </p>
                    </div>
                    <div class="p-3 bg-red-900/30 rounded-xl">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let hasPendingSubscriptions = {{ $subscriptions->where('status', 'pending')->count() > 0 ? 'true' : 'false' }};

        if (hasPendingSubscriptions) {
            console.log('Pending subscriptions detected');
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('input[name="search"]')?.focus();
            }

            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                window.location.reload();
            }
        });

        @if(session('success'))
        setTimeout(() => {
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                successMessage.style.transition = 'opacity 0.5s';
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 500);
            }
        }, 5000);
        @endif
    </script>
@endpush
