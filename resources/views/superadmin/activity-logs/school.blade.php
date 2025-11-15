@extends('layouts.superadmin')

@section('page-title', 'School Activity Logs - ' . $school->name)

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

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
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
            border-radius: 0.75rem;
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

        .school-header-bg {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%);
            position: relative;
            overflow: hidden;
        }

        .school-header-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .user-rank-badge {
            transition: all 0.3s ease;
        }

        .user-rank-badge:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        .timeline-dot {
            transition: all 0.3s ease;
        }

        .timeline-item:hover .timeline-dot {
            transform: scale(1.3);
            box-shadow: 0 0 15px currentColor;
        }

        .activity-card {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .activity-card:hover {
            border-left-color: rgb(99, 102, 241);
            background: rgba(99, 102, 241, 0.05);
            transform: translateX(5px);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8">
        <!-- Epic School Header -->
        <div class="relative overflow-hidden rounded-2xl school-header-bg animate-fade-in-up">
            <div class="glass-morphism rounded-2xl p-8">
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="p-5 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-2xl">
                            <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-200 to-purple-200">
                                    {{ $school->name }}
                                </h1>
                                @if($school->status === 'active')
                                    <span class="px-3 py-1 bg-green-900/50 text-green-300 text-sm font-bold rounded-full ring-2 ring-green-500/50">
                                        Active
                                    </span>
                                @endif
                            </div>
                            <p class="text-lg text-gray-300 font-medium">Activity Logs & Performance Metrics</p>
                            @if($school->address)
                                <p class="mt-1 text-sm text-gray-400 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $school->address }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('superadmin.schools.index') }}"
                           class="group px-5 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-white font-semibold rounded-xl transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back</span>
                        </a>
                        <a href="{{ route('superadmin.activity-logs.export', array_merge(request()->all(), ['school_id' => $school->id])) }}"
                           class="group px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-green-500/50 flex items-center space-x-2">
                            <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Export</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat-card rounded-2xl p-6 animate-slide-in-right" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Activities</p>
                        <p class="text-3xl font-black text-white mt-2">{{ number_format($stats['total_activities']) }}</p>
                        <div class="mt-2 flex items-center text-sm text-indigo-400">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                            </svg>
                            <span class="font-semibold">School Total</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-indigo-900/50 to-indigo-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-slide-in-right" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Today</p>
                        <p class="text-3xl font-black text-green-400 mt-2">{{ number_format($stats['today']) }}</p>
                        <div class="mt-2 flex items-center text-sm text-gray-400">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <span class="font-semibold">Active Now</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-green-900/50 to-emerald-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-slide-in-right" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">This Week</p>
                        <p class="text-3xl font-black text-blue-400 mt-2">{{ number_format($stats['this_week']) }}</p>
                        <div class="mt-2 flex items-center text-sm text-blue-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Weekly Stats</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-blue-900/50 to-blue-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-slide-in-right" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">This Month</p>
                        <p class="text-3xl font-black text-purple-400 mt-2">{{ number_format($stats['this_month']) }}</p>
                        <div class="mt-2 flex items-center text-sm text-purple-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">{{ now()->format('F') }}</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-purple-900/50 to-purple-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Active Users for School -->
        @if(!empty($mostActiveUsers))
            <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="glass-morphism rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gradient-to-br from-yellow-600 to-orange-600 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Top Performers</h3>
                        </div>
                        <span class="px-4 py-2 bg-gradient-to-r from-yellow-900/30 to-orange-900/30 text-yellow-400 text-sm font-bold rounded-full ring-2 ring-yellow-500/30">
                            Top 5 Most Active
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @foreach(array_slice($mostActiveUsers, 0, 5) as $index => $activeUser)
                            @if($activeUser['user'])
                                <div class="user-rank-badge group bg-gradient-to-br from-gray-800/80 to-gray-900/80 rounded-xl p-5 border border-gray-700/50 hover:border-indigo-500/50 relative overflow-hidden">
                                    <!-- Rank Badge -->
                                    @if($index < 3)
                                        <div class="absolute top-2 right-2 h-8 w-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg
                                            {{ $index === 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-yellow-900' : '' }}
                                            {{ $index === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-gray-900' : '' }}
                                            {{ $index === 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-orange-900' : '' }}">
                                            #{{ $index + 1 }}
                                        </div>
                                    @endif

                                    <div class="flex flex-col items-center text-center space-y-3">
                                        <div class="relative">
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center font-bold text-white text-xl shadow-lg group-hover:shadow-indigo-500/50 transition-shadow ring-4 ring-gray-900">
                                                {{ strtoupper(substr($activeUser['user']->name, 0, 1)) }}
                                            </div>
                                            @if($index === 0)
                                                <div class="absolute -bottom-1 -right-1">
                                                    <svg class="w-6 h-6 text-yellow-400 drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-full">
                                            <p class="text-sm font-bold text-white truncate">{{ $activeUser['user']->name }}</p>
                                            <p class="text-xs text-indigo-400 font-semibold mt-1">{{ $activeUser['user']->role }}</p>
                                            <div class="mt-3 inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-indigo-900/50 to-purple-900/50 rounded-full ring-1 ring-indigo-500/30">
                                                <svg class="w-3 h-3 text-indigo-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs font-bold text-white">{{ $activeUser['activity_count'] }}</span>
                                                <span class="text-xs text-gray-400 ml-1">acts</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="glass-morphism rounded-2xl p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white">Filter Activities</h3>
                </div>

                <form method="GET" action="{{ route('superadmin.activity-logs.school', $school) }}" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Log Type</label>
                        <select name="log_name" class="w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Types</option>
                            @foreach($logTypes as $type)
                                <option value="{{ $type }}" {{ request('log_name') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Event</label>
                        <select name="event" class="w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                    {{ ucfirst($event) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Search</span>
                            </span>
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search descriptions, users, events..."
                               class="w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div class="md:col-span-2 flex items-end space-x-3">
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-indigo-500/50 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Apply</span>
                        </button>
                        <a href="{{ route('superadmin.activity-logs.school', $school) }}" class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-white font-semibold rounded-xl transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Clear</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.7s;">
            <div class="glass-morphism rounded-2xl p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-white">School Activity Timeline</h3>
                    </div>
                    <span class="px-3 py-1 bg-indigo-900/30 text-indigo-400 text-sm font-semibold rounded-full">
                        {{ $activities->total() }} Activities
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($activities as $activity)
                        <div class="timeline-item activity-card relative pl-8 pb-6 {{ !$loop->last ? 'border-l-2 border-gray-700/50' : '' }}">
                            <!-- Timeline Dot -->
                            <div class="timeline-dot absolute -left-[9px] top-1">
                                <div class="h-4 w-4 rounded-full border-4 border-gray-900 {{
                                    $activity->event === 'created' ? 'bg-green-500 shadow-green-500/50' :
                                    ($activity->event === 'updated' ? 'bg-yellow-500 shadow-yellow-500/50' :
                                    ($activity->event === 'deleted' ? 'bg-red-500 shadow-red-500/50' : 'bg-gray-500 shadow-gray-500/50'))
                                }} shadow-lg"></div>
                            </div>

                            <!-- Activity Content -->
                            <div class="bg-gray-800/40 rounded-xl p-5 hover:bg-gray-800/60 transition-all duration-300">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 space-y-3">
                                        <!-- Header -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($activity->causer)
                                                <div class="flex items-center space-x-2 bg-gray-700/50 px-3 py-1.5 rounded-lg">
                                                    <div class="h-7 w-7 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($activity->causer->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm font-semibold text-white">{{ $activity->causer->name }}</span>
                                                </div>
                                                <span class="px-2 py-1 bg-indigo-900/30 text-indigo-300 text-xs font-medium rounded-lg">
                                                    {{ $activity->causer->role }}
                                                </span>
                                            @else
                                                <div class="flex items-center space-x-2 bg-gray-700/50 px-3 py-1.5 rounded-lg">
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-400">System</span>
                                                </div>
                                            @endif

                                            @if($activity->event)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{
                                                    $activity->event === 'created' ? 'bg-green-900/50 text-green-300 ring-1 ring-green-500/50' :
                                                    ($activity->event === 'updated' ? 'bg-yellow-900/50 text-yellow-300 ring-1 ring-yellow-500/50' :
                                                    ($activity->event === 'deleted' ? 'bg-red-900/50 text-red-300 ring-1 ring-red-500/50' : 'bg-gray-700/50 text-gray-300 ring-1 ring-gray-500/50'))
                                                }}">
                                                    {{ ucfirst($activity->event) }}
                                                </span>
                                            @endif

                                            @if($activity->log_name)
                                                <span class="px-2 py-1 bg-gray-700/50 text-gray-300 text-xs font-medium rounded-lg">
                                                    {{ ucfirst(str_replace('_', ' ', $activity->log_name)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Description -->
                                        <p class="text-sm text-gray-200 leading-relaxed font-medium">{{ $activity->description }}</p>

                                        <!-- Metadata -->
                                        <div class="flex flex-wrap items-center gap-4 text-xs">
                                            <span class="flex items-center space-x-1 text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-semibold text-white">{{ $activity->created_at->format('M d, Y • H:i:s') }}</span>
                                                <span class="text-gray-500">({{ $activity->created_at->diffForHumans() }})</span>
                                            </span>

                                            @if(!empty($activity->properties['ip_address']))
                                                <span class="flex items-center space-x-1 px-2 py-1 bg-gray-700/30 rounded-lg">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                    </svg>
                                                    <span class="font-mono text-gray-300">{{ $activity->properties['ip_address'] }}</span>
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Properties Details -->
                                        @if(count($activity->properties) > 2)
                                            <details class="group">
                                                <summary class="cursor-pointer text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center space-x-2 w-fit px-3 py-2 bg-indigo-900/20 rounded-lg hover:bg-indigo-900/30 transition-all">
                                                    <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    <span>View Technical Details</span>
                                                </summary>
                                                <div class="mt-3 p-4 bg-gray-900/80 rounded-xl border border-gray-700/50">
                                                    <pre class="text-xs text-gray-300 overflow-x-auto font-mono leading-relaxed">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </details>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-800/50 to-gray-900/50 mb-6">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-bold text-white mb-3">No Activities Found</h4>
                            <p class="text-gray-400 max-w-md mx-auto mb-6">
                                No activities have been recorded for <span class="text-indigo-400 font-semibold">{{ $school->name }}</span> matching your filters.
                            </p>
                            <a href="{{ route('superadmin.activity-logs.school', $school) }}"
                               class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Reset Filters</span>
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Epic Pagination -->
        @if($activities->hasPages())
            <div class="glass-morphism rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing <span class="font-semibold text-white">{{ $activities->firstItem() }}</span>
                        to <span class="font-semibold text-white">{{ $activities->lastItem() }}</span>
                        of <span class="font-semibold text-white">{{ $activities->total() }}</span> activities
                    </div>
                    <div class="pagination-wrapper">
                        {{ $activities->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions Panel -->
        <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.9s;">
            <div class="glass-morphism rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold text-white">Quick Actions</h4>
                            <p class="text-xs text-gray-400">Manage school activities</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('superadmin.schools.edit', $school) }}"
                           class="px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-white text-sm font-medium rounded-lg transition-all flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit School</span>
                        </a>
                        <a href="{{ route('superadmin.activity-logs.index') }}"
                           class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 text-sm font-medium rounded-lg transition-all border border-indigo-500/30 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            <span>All Activities</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll to activity
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            // Auto-refresh notification
            let lastActivityCount = {{ $stats['today'] }};

            function checkForNewActivities() {
                // This would typically be an AJAX call to check for new activities
                // For now, we'll just show a notification option
                console.log('Checking for new activities...');
            }

            // Check every 30 seconds
            setInterval(checkForNewActivities, 30000);

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + R to refresh
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    window.location.reload();
                }

                // Ctrl/Cmd + E to export
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    window.location.href = "{{ route('superadmin.activity-logs.export', array_merge(request()->all(), ['school_id' => $school->id])) }}";
                }
            });

            // Enhanced tooltips for stats
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });

            // Activity card animations on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.activity-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease-out';
                observer.observe(card);
            });
        });

        // Print functionality
        function printActivityLog() {
            window.print();
        }

        // Copy activity details
        function copyActivityDetails(activityId) {
            // Implementation for copying activity details
            console.log('Copying activity:', activityId);
        }
    </script>
@endpush

@push('styles')
    <style media="print">
        /* Print styles */
        .gradient-border::before,
        .school-header-bg::before {
            display: none;
        }

        .stat-card,
        .glass-morphism {
            background: white !important;
            color: black !important;
        }

        .text-white,
        .text-gray-200,
        .text-gray-300 {
            color: black !important;
        }

        .bg-gray-800,
        .bg-gray-900 {
            background: white !important;
            border: 1px solid #e5e7eb !important;
        }

        button,
        .hover\:bg-indigo-700 {
            display: none !important;
        }
    </style>
@endpush
