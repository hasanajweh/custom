<?php $__env->startSection('page-title', 'Activity Logs'); ?>

<?php $__env->startPush('styles'); ?>
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

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(99, 102, 241, 0.6);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
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
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .user-badge {
            transition: all 0.3s ease;
        }

        .user-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4);
        }

        .filter-input {
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.2);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
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
                            Activity Logs
                        </h1>
                        <p class="mt-2 text-lg text-gray-300 font-medium">
                            Real-time monitoring across all schools
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.reload()"
                            class="group px-4 py-2 bg-gray-700/50 hover:bg-gray-600/50 text-white rounded-xl transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Refresh</span>
                    </button>
                    <a href="<?php echo e(route('superadmin.activity-logs.export', request()->all())); ?>"
                       class="group px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-green-500/50 flex items-center space-x-2">
                        <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Epic Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stat-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Activities</p>
                        <p class="text-3xl font-black text-white mt-2"><?php echo e(number_format($stats['total_activities'])); ?></p>
                        <div class="mt-2 flex items-center text-sm text-green-400">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">All time</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-indigo-900/50 to-indigo-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Today</p>
                        <p class="text-3xl font-black text-green-400 mt-2"><?php echo e(number_format($stats['today'])); ?></p>
                        <div class="mt-2 flex items-center text-sm text-gray-400">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <span class="font-semibold">Live tracking</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-green-900/50 to-emerald-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">This Week</p>
                        <p class="text-3xl font-black text-blue-400 mt-2"><?php echo e(number_format($stats['this_week'])); ?></p>
                        <div class="mt-2 flex items-center text-sm text-blue-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                            </svg>
                            <span class="font-semibold">Trending up</span>
                        </div>
                    </div>
                    <div class="stat-icon p-4 bg-gradient-to-br from-blue-900/50 to-blue-800/30 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">This Month</p>
                        <p class="text-3xl font-black text-purple-400 mt-2"><?php echo e(number_format($stats['this_month'])); ?></p>
                        <div class="mt-2 flex items-center text-sm text-purple-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold"><?php echo e(now()->format('F')); ?></span>
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

        <!-- Advanced Filters -->
        <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="glass-morphism rounded-2xl p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white">Advanced Filters</h3>
                </div>

                <form method="GET" action="<?php echo e(route('superadmin.activity-logs.index')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                                <span>School</span>
                            </span>
                        </label>
                        <select name="school_id" class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Schools</option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">From Date</label>
                        <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                               class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">To Date</label>
                        <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                               class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Log Type</label>
                        <select name="log_name" class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Types</option>
                            <?php $__currentLoopData = $logTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>" <?php echo e(request('log_name') == $type ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $type))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-3">Event</label>
                        <select name="event" class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <option value="">All Events</option>
                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($event); ?>" <?php echo e(request('event') == $event ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($event)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="md:col-span-5">
                        <label class="block text-sm font-semibold text-gray-300 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Search</span>
                            </span>
                        </label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search descriptions, users, events..."
                               class="filter-input w-full px-4 py-3 bg-gray-800/60 border-2 border-gray-700 rounded-xl text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    </div>

                    <div class="flex items-end space-x-3">
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-indigo-500/50 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Apply</span>
                        </button>
                        <a href="<?php echo e(route('superadmin.activity-logs.index')); ?>" class="px-6 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-white font-semibold rounded-xl transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Clear</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Most Active Users -->
        <?php if(!empty($mostActiveUsers)): ?>
            <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="glass-morphism rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <h3 class="text-xl font-bold text-white">Most Active Users</h3>
                        </div>
                        <span class="px-3 py-1 bg-yellow-900/30 text-yellow-400 text-sm font-semibold rounded-full">Top 5</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <?php $__currentLoopData = array_slice($mostActiveUsers, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activeUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($activeUser['user']): ?>
                                <div class="user-badge group bg-gradient-to-br from-gray-800/80 to-gray-900/80 rounded-xl p-5 border border-gray-700/50 hover:border-indigo-500/50">
                                    <div class="flex flex-col items-center text-center space-y-3">
                                        <div class="relative">
                                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center font-bold text-white text-xl shadow-lg group-hover:shadow-indigo-500/50 transition-shadow">
                                                <?php echo e(strtoupper(substr($activeUser['user']->name, 0, 1))); ?>

                                            </div>
                                            <?php if($index === 0): ?>
                                                <div class="absolute -top-2 -right-2 h-6 w-6 bg-yellow-400 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white truncate max-w-full"><?php echo e($activeUser['user']->name); ?></p>
                                            <p class="text-xs text-gray-400 mt-1"><?php echo e($activeUser['user']->role ?? 'User'); ?></p>
                                            <div class="mt-2 inline-flex items-center px-3 py-1 bg-indigo-900/50 rounded-full">
                                                <svg class="w-3 h-3 text-indigo-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs font-bold text-indigo-400"><?php echo e($activeUser['activity_count']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Activity Timeline -->
        <div class="gradient-border animate-fade-in-up" style="animation-delay: 0.7s;">
            <div class="glass-morphism rounded-2xl p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-white">Activity Timeline</h3>
                    </div>
                    <span class="px-3 py-1 bg-indigo-900/30 text-indigo-400 text-sm font-semibold rounded-full">
                        <?php echo e($activities->total()); ?> Total
                    </span>
                </div>

                <div class="space-y-6">
                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline-item activity-card relative pl-8 pb-6 <?php echo e(!$loop->last ? 'border-l-2 border-gray-700/50' : ''); ?>">
                            <!-- Timeline Dot -->
                            <div class="timeline-dot absolute -left-[9px] top-1">
                                <div class="h-4 w-4 rounded-full border-4 border-gray-900 <?php echo e($activity->event === 'created' ? 'bg-green-500 shadow-green-500/50' :
                                    ($activity->event === 'updated' ? 'bg-yellow-500 shadow-yellow-500/50' :
                                    ($activity->event === 'deleted' ? 'bg-red-500 shadow-red-500/50' : 'bg-gray-500 shadow-gray-500/50'))); ?> shadow-lg"></div>
                            </div>

                            <!-- Activity Content -->
                            <div class="bg-gray-800/40 rounded-xl p-5 hover:bg-gray-800/60 transition-all duration-300">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 space-y-3">
                                        <!-- Header -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            <?php if($activity->causer): ?>
                                                <div class="flex items-center space-x-2 bg-gray-700/50 px-3 py-1 rounded-lg">
                                                    <div class="h-6 w-6 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                        <?php echo e(strtoupper(substr($activity->causer->name, 0, 1))); ?>

                                                    </div>
                                                    <span class="text-sm font-semibold text-white"><?php echo e($activity->causer->name); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex items-center space-x-2 bg-gray-700/50 px-3 py-1 rounded-lg">
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-400">System</span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if(!empty($activity->properties['school_name'])): ?>
                                                <div class="flex items-center space-x-1 bg-indigo-900/30 px-3 py-1 rounded-lg">
                                                    <svg class="w-4 h-4 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-indigo-300"><?php echo e($activity->properties['school_name']); ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($activity->event): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold <?php echo e($activity->event === 'created' ? 'bg-green-900/50 text-green-300 ring-1 ring-green-500/50' :
                                                    ($activity->event === 'updated' ? 'bg-yellow-900/50 text-yellow-300 ring-1 ring-yellow-500/50' :
                                                    ($activity->event === 'deleted' ? 'bg-red-900/50 text-red-300 ring-1 ring-red-500/50' : 'bg-gray-700/50 text-gray-300 ring-1 ring-gray-500/50'))); ?>">
                                                    <?php echo e(ucfirst($activity->event)); ?>

                                                </span>
                                            <?php endif; ?>

                                            <?php if($activity->log_name): ?>
                                                <span class="px-2 py-1 bg-gray-700/50 text-gray-300 text-xs font-medium rounded-lg">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $activity->log_name))); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Description -->
                                        <p class="text-sm text-gray-200 leading-relaxed"><?php echo e($activity->description); ?></p>

                                        <!-- Metadata -->
                                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-400">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium"><?php echo e($activity->created_at->format('M d, Y • H:i:s')); ?></span>
                                                <span class="text-gray-500">(<?php echo e($activity->created_at->diffForHumans()); ?>)</span>
                                            </span>

                                            <?php if(!empty($activity->properties['ip_address'])): ?>
                                                <span class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                    </svg>
                                                    <span class="font-mono"><?php echo e($activity->properties['ip_address']); ?></span>
                                                </span>
                                            <?php endif; ?>

                                            <?php if(!empty($activity->properties['user_agent'])): ?>
                                                <span class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="truncate max-w-xs" title="<?php echo e($activity->properties['user_agent']); ?>">
                                                        <?php echo e(Str::limit($activity->properties['user_agent'], 40)); ?>

                                                    </span>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Properties Details -->
                                        <?php if(count($activity->properties) > 2): ?>
                                            <details class="group">
                                                <summary class="cursor-pointer text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center space-x-2 w-fit px-3 py-2 bg-indigo-900/20 rounded-lg hover:bg-indigo-900/30 transition-all">
                                                    <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    <span>View Technical Details</span>
                                                </summary>
                                                <div class="mt-3 p-4 bg-gray-900/80 rounded-xl border border-gray-700/50">
                                                    <pre class="text-xs text-gray-300 overflow-x-auto font-mono leading-relaxed"><?php echo e(json_encode($activity->properties, JSON_PRETTY_PRINT)); ?></pre>
                                                </div>
                                            </details>
                                        <?php endif; ?>
                                    </div>

                                    <!-- View Action -->
                                    <div class="ml-4">
                                        <a href="<?php echo e(route('superadmin.activity-logs.show', $activity)); ?>"
                                           class="group flex items-center space-x-2 px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-300 border border-indigo-500/30 hover:border-indigo-500/50">
                                            <span class="text-xs font-semibold">View</span>
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-20">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-800/50 mb-6">
                                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-2">No Activities Found</h4>
                            <p class="text-gray-400 max-w-md mx-auto">
                                No activities match your current filters. Try adjusting your search criteria or clearing all filters.
                            </p>
                            <a href="<?php echo e(route('superadmin.activity-logs.index')); ?>"
                               class="inline-flex items-center space-x-2 mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Reset Filters</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Epic Pagination -->
        <?php if($activities->hasPages()): ?>
            <div class="glass-morphism rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing <span class="font-semibold text-white"><?php echo e($activities->firstItem()); ?></span>
                        to <span class="font-semibold text-white"><?php echo e($activities->lastItem()); ?></span>
                        of <span class="font-semibold text-white"><?php echo e($activities->total()); ?></span> activities
                    </div>
                    <div class="pagination-wrapper">
                        <?php echo e($activities->appends(request()->all())->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Auto-refresh option
        let autoRefresh = false;
        let refreshInterval;

        function toggleAutoRefresh() {
            autoRefresh = !autoRefresh;
            if (autoRefresh) {
                refreshInterval = setInterval(() => {
                    window.location.reload();
                }, 30000); // Refresh every 30 seconds
            } else {
                clearInterval(refreshInterval);
            }
        }

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
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\activity-logs\index.blade.php ENDPATH**/ ?>