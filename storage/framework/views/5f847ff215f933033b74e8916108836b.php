<?php $__env->startSection('page-title', 'Subscription Expiry Tracker'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Subscription Expiry Tracker</h1>
                <p class="mt-2 text-gray-400">Monitor expiring and expired subscriptions</p>
            </div>
            <a href="<?php echo e(route('superadmin.subscriptions.index')); ?>"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                All Subscriptions
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass rounded-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Expiring This Week</p>
                        <p class="text-3xl font-bold text-red-400 mt-2"><?php echo e($stats['expiring_week']); ?></p>
                    </div>
                    <div class="p-3 bg-red-900 bg-opacity-30 rounded-lg">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Action required soon</p>
            </div>

            <div class="glass rounded-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Expiring This Month</p>
                        <p class="text-3xl font-bold text-yellow-400 mt-2"><?php echo e($stats['expiring_month']); ?></p>
                    </div>
                    <div class="p-3 bg-yellow-900 bg-opacity-30 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Plan renewals needed</p>
            </div>

            <div class="glass rounded-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Already Expired</p>
                        <p class="text-3xl font-bold text-orange-400 mt-2"><?php echo e($stats['expired']); ?></p>
                    </div>
                    <div class="p-3 bg-orange-900 bg-opacity-30 rounded-lg">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Immediate attention needed</p>
            </div>

            <div class="glass rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Active</p>
                        <p class="text-3xl font-bold text-green-400 mt-2"><?php echo e($stats['total_active']); ?></p>
                    </div>
                    <div class="p-3 bg-green-900 bg-opacity-30 rounded-lg">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">All subscriptions</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass rounded-lg p-4">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('superadmin.subscriptions.expiry', ['filter' => 'expiring_week'])); ?>"
                   class="px-4 py-2 rounded-lg transition <?php echo e($filter === 'expiring_week' ? 'bg-red-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'); ?>">
                    This Week
                </a>
                <a href="<?php echo e(route('superadmin.subscriptions.expiry', ['filter' => 'expiring_soon'])); ?>"
                   class="px-4 py-2 rounded-lg transition <?php echo e($filter === 'expiring_soon' ? 'bg-yellow-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'); ?>">
                    Next 30 Days
                </a>
                <a href="<?php echo e(route('superadmin.subscriptions.expiry', ['filter' => 'expired'])); ?>"
                   class="px-4 py-2 rounded-lg transition <?php echo e($filter === 'expired' ? 'bg-orange-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'); ?>">
                    Expired
                </a>
                <a href="<?php echo e(route('superadmin.subscriptions.expiry', ['filter' => 'all'])); ?>"
                   class="px-4 py-2 rounded-lg transition <?php echo e($filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600'); ?>">
                    All Active
                </a>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="glass rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                <tr class="border-b border-gray-700">
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">School</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Plan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">End Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Days Left</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $daysLeft = now()->diffInDays($subscription->ends_at, false);
                        $isExpired = $daysLeft < 0;
                        $urgencyClass = $isExpired ? 'bg-red-900 bg-opacity-20' : ($daysLeft <= 7 ? 'bg-orange-900 bg-opacity-20' : 'bg-yellow-900 bg-opacity-20');
                    ?>
                    <tr class="hover:bg-gray-800 transition-colors <?php echo e($urgencyClass); ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-600 flex items-center justify-center font-semibold text-white">
                                    <?php echo e(strtoupper(substr($subscription->school->name, 0, 2))); ?>

                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white"><?php echo e($subscription->school->name); ?></div>
                                    <div class="text-sm text-gray-400"><?php echo e($subscription->school->slug); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white font-medium"><?php echo e($subscription->plan->name); ?></div>
                            <div class="text-sm text-gray-400">$<?php echo e(number_format($subscription->plan->price_monthly / 100, 2)); ?>/mo</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white"><?php echo e($subscription->ends_at->format('M d, Y')); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($subscription->ends_at->format('h:i A')); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($isExpired): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-900 text-red-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Expired <?php echo e(abs($daysLeft)); ?> days ago
                        </span>
                            <?php elseif($daysLeft <= 3): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-900 text-red-200 animate-pulse">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo e($daysLeft); ?> <?php echo e(Str::plural('day', $daysLeft)); ?>

                        </span>
                            <?php elseif($daysLeft <= 7): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-900 text-orange-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo e($daysLeft); ?> days
                        </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900 text-yellow-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <?php echo e($daysLeft); ?> days
                        </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                            Active
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-3">
                                <a href="<?php echo e(route('superadmin.subscriptions.edit', $subscription)); ?>"
                                   class="text-indigo-400 hover:text-indigo-300 transition">
                                    Renew
                                </a>
                                <a href="<?php echo e(route('superadmin.schools.edit', $subscription->school)); ?>"
                                   class="text-gray-400 hover:text-gray-300 transition">
                                    View School
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400 text-lg">No subscriptions found for this filter</p>
                            <p class="text-gray-500 text-sm mt-2">All subscriptions are in good standing!</p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($subscriptions->hasPages()): ?>
            <div class="glass rounded-lg p-4">
                <?php echo e($subscriptions->appends(['filter' => $filter])->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\subscriptions\expiry.blade.php ENDPATH**/ ?>