@extends('layouts.superadmin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div>
            <h1 class="text-3xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="mt-2 text-gray-400">Here's what's happening with your platform today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Schools -->
            <div class="card-hover glass rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Schools</p>
                        <p class="mt-1 text-3xl font-bold text-white">{{ $totalSchools }}</p>
                        <p class="mt-1 text-sm text-green-400">
                            <span class="font-medium">+12%</span> from last month
                        </p>
                    </div>
                    <div class="rounded-full bg-indigo-600 bg-opacity-20 p-3">
                        <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="card-hover glass rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Active Subscriptions</p>
                        <p class="mt-1 text-3xl font-bold text-white">{{ $activeSubscriptions }}</p>
                        <p class="mt-1 text-sm text-green-400">
                            <span class="font-medium">{{ $activeSchools }} active</span> schools
                        </p>
                    </div>
                    <div class="rounded-full bg-green-600 bg-opacity-20 p-3">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="card-hover glass rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Users</p>
                        <p class="mt-1 text-3xl font-bold text-white">{{ $totalUsers }}</p>
                        <p class="mt-1 text-sm text-purple-400">
                            <span class="font-medium">{{ $newUsersThisMonth }}</span> this month
                        </p>
                    </div>
                    <div class="rounded-full bg-purple-600 bg-opacity-20 p-3">
                        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="card-hover glass rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Monthly Revenue</p>
                        <p class="mt-1 text-3xl font-bold text-white">${{ number_format($monthlyRevenue, 2) }}</p>
                        <p class="mt-1 text-sm text-yellow-400">
                            <span class="font-medium">+8.2%</span> growth
                        </p>
                    </div>
                    <div class="rounded-full bg-yellow-600 bg-opacity-20 p-3">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Revenue Chart -->
            <div class="glass rounded-lg p-6">
                <h3 class="text-lg font-medium text-white">Revenue Overview</h3>
                <div class="mt-4">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Users Growth Chart -->
            <div class="glass rounded-lg p-6">
                <h3 class="text-lg font-medium text-white">User Growth</h3>
                <div class="mt-4">
                    <canvas id="usersChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Schools Table -->
        <div class="glass rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-white">Recent Schools</h3>
                <a href="{{ route('superadmin.schools.index') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                    View all →
                </a>
            </div>

            <div class="overflow-hidden">
                <table class="min-w-full">
                    <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">School</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                    @foreach($recentSchools as $school)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $school->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $school->slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                               <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-indigo-900 text-indigo-200">
                                   {{ $school->activeSubscription?->plan?->name ?? 'No Plan' }}
                               </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $school->users_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($school->is_active)
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-green-900 text-green-200">
                                       Active
                                   </span>
                                @else
                                    <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-red-900 text-red-200">
                                       Inactive
                                   </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $school->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('superadmin.schools.edit', $school) }}"
                                   class="text-indigo-400 hover:text-indigo-300">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="glass rounded-lg p-6">
            <h3 class="text-lg font-medium text-white mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center">
                                @if($activity->event === 'created')
                                    <svg class="h-4 w-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                @elseif($activity->event === 'updated')
                                    <svg class="h-4 w-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-300">
                                {{ $activity->description }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueData['labels']) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($revenueData['data']) !!},
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)',
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Users Chart
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            const usersChart = new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($usersData['labels']) !!},
                    datasets: [{
                        label: 'New Users',
                        data: {!! json_encode($usersData['data']) !!},
                        backgroundColor: 'rgba(168, 85, 247, 0.8)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.6)'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
