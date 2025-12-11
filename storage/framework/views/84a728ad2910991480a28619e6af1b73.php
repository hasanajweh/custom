<?php $__env->startSection('page-title', 'Manage Plans'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Subscription Plans</h1>
                <p class="mt-2 text-gray-400">Manage your platform's subscription plans and pricing</p>
            </div>
            <a href="<?php echo e(route('superadmin.plans.create')); ?>"
               class="btn-glow inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Plan
            </a>
        </div>

        <!-- Plans Table -->
        <div class="glass rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                <tr class="border-b border-gray-700">
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Plan Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Price (Monthly/Annually)</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Storage Limit</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Features</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-white"><?php echo e($plan->name); ?></div>
                                <div class="text-sm text-gray-400"><?php echo e($plan->slug); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">
                                <span class="font-medium">$<?php echo e(number_format($plan->price_monthly / 100, 2)); ?></span>/mo
                            </div>
                            <div class="text-sm text-gray-400">
                                $<?php echo e(number_format($plan->price_annually / 100, 2)); ?>/yr
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                </svg>
                                <span class="text-sm text-white"><?php echo e($plan->storage_limit_in_gb); ?> GB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <?php if($plan->features && is_array($plan->features)): ?>
                                    <ul class="text-sm text-gray-400 space-y-1">
                                        <?php $__currentLoopData = array_slice($plan->features, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-green-400 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <?php echo e($feature); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($plan->features) > 2): ?>
                                            <li class="text-xs text-gray-500">+<?php echo e(count($plan->features) - 2); ?> more</li>
                                        <?php endif; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">No features listed</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($plan->is_active): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="<?php echo e(route('superadmin.plans.edit', $plan)); ?>"
                                   class="text-indigo-400 hover:text-indigo-300 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('superadmin.plans.destroy', $plan)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this plan?')"
                                            class="text-red-400 hover:text-red-300 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-400 mb-4">No plans have been created yet.</p>
                                <a href="<?php echo e(route('superadmin.plans.create')); ?>"
                                   class="text-indigo-400 hover:text-indigo-300">
                                    Create your first plan
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\plans\index.blade.php ENDPATH**/ ?>