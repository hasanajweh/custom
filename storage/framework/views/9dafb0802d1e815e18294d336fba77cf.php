<?php $__env->startSection('page-title', 'Activity Log'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Activity Log</h1>
            <p class="mt-2 text-gray-400">Activity history for <span class="text-indigo-400"><?php echo e($school->name); ?></span></p>
        </div>

        <!-- Activity Timeline -->
        <div class="glass rounded-lg p-6">
            <ul role="list" class="space-y-6">
                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="relative flex gap-x-4">
                        
                        <?php if(!$loop->last): ?>
                            <div class="absolute left-3 top-8 -bottom-2 w-0.5 bg-gray-700"></div>
                        <?php endif; ?>

                        
                        <div class="relative flex h-6 w-6 flex-none items-center justify-center rounded-full
                        <?php if(Str::contains($activity->description, 'created')): ?>
                            bg-green-900/30 ring-1 ring-green-600
                        <?php elseif(Str::contains($activity->description, 'updated')): ?>
                            bg-yellow-900/30 ring-1 ring-yellow-600
                        <?php elseif(Str::contains($activity->description, 'deleted')): ?>
                            bg-red-900/30 ring-1 ring-red-600
                        <?php else: ?>
                            bg-gray-800 ring-1 ring-gray-700
                        <?php endif; ?>">
                            <?php if(Str::contains($activity->description, 'created')): ?>
                                <svg class="h-3 w-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            <?php elseif(Str::contains($activity->description, 'updated')): ?>
                                <svg class="h-3 w-3 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            <?php elseif(Str::contains($activity->description, 'deleted')): ?>
                                <svg class="h-3 w-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            <?php else: ?>
                                <div class="h-1.5 w-1.5 bg-gray-400 rounded-full"></div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-auto">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-300">
                                        <span class="font-semibold text-white"><?php echo e($activity->causer->name ?? 'System'); ?></span>
                                        <?php echo e($activity->description); ?>

                                    </p>
                                    <?php if($activity->subject): ?>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?php echo e(Str::afterLast($activity->subject_type, '\\')); ?>:
                                            <span class="text-gray-400"><?php echo e($activity->subject->name ?? $activity->subject->title ?? 'N/A'); ?></span>
                                        </p>
                                    <?php endif; ?>

                                    <?php if($activity->properties && count($activity->properties) > 0): ?>
                                        <details class="mt-2">
                                            <summary class="text-xs text-indigo-400 cursor-pointer hover:text-indigo-300">View changes</summary>
                                            <div class="mt-2 text-xs bg-gray-800 rounded p-2 text-gray-400">
                                                <pre><?php echo e(json_encode($activity->properties, JSON_PRETTY_PRINT)); ?></pre>
                                            </div>
                                        </details>
                                    <?php endif; ?>
                                </div>
                                <time datetime="<?php echo e($activity->created_at->toIso8601String()); ?>"
                                      class="flex-none text-xs text-gray-500">
                                    <?php echo e($activity->created_at->diffForHumans()); ?>

                                </time>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-center text-gray-400 py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>No activity recorded for this school yet.</p>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if($activities->hasPages()): ?>
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <?php echo e($activities->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="<?php echo e(route('superadmin.schools.index')); ?>"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                ← Back to Schools
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\schools\activity.blade.php ENDPATH**/ ?>