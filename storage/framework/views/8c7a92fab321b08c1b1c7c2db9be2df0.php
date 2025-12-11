<?php $__env->startSection('page-title', 'Edit School'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-5xl mx-auto space-y-6" x-data="{
    storageGB: <?php echo e(old('storage_limit_gb', $school->storage_limit / 1073741824)); ?>

}">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Edit School</h1>
            <p class="mt-2 text-gray-400">Update school information for <?php echo e($school->name); ?></p>
        </div>

        <!-- School Stats -->
        <div class="grid grid-cols-4 gap-4">
            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Users</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($school->users()->count()); ?></p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Files</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($school->fileSubmissions()->count()); ?></p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Storage Used</p>
                        <p class="text-2xl font-bold text-white"><?php echo e(round($school->storage_used / 1073741824, 1)); ?> GB</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Usage</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($school->storage_used_percentage); ?>%</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('superadmin.schools.update', $school)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- Basic Information -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Basic Information</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            School Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="<?php echo e(old('name', $school->name)); ?>"
                               required
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="<?php echo e(old('slug', $school->slug)); ?>"
                               required
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="network_id" class="block text-sm font-medium text-gray-300 mb-2">Network</label>
                    <select name="network_id" id="network_id"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <?php $__currentLoopData = $networks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $network): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($network->id); ?>" <?php echo e(old('network_id', $school->network_id) == $network->id ? 'selected' : ''); ?>>
                                <?php echo e($network->name); ?> (<?php echo e($network->slug); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['network_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mt-6">
                    <label class="flex items-center p-3 rounded-lg border border-gray-700 hover:bg-gray-800 transition cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               <?php echo e($school->is_active ? 'checked' : ''); ?>

                               class="w-5 h-5 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-medium text-gray-300">School is Active</span>
                    </label>
                </div>
            </div>

            <!-- Storage Management -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Storage Management</h2>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-300">
                            Storage Limit
                        </label>
                        <span class="text-2xl font-bold text-indigo-400" x-text="storageGB + ' GB'"></span>
                    </div>

                    <!-- Storage Slider -->
                    <input type="range"
                           name="storage_limit_gb"
                           x-model="storageGB"
                           min="1"
                           max="1000"
                           step="1"
                           class="w-full h-3 bg-gray-700 rounded-lg appearance-none cursor-pointer slider">

                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>1 GB</span>
                        <span>250 GB</span>
                        <span>500 GB</span>
                        <span>750 GB</span>
                        <span>1000 GB</span>
                    </div>

                    <!-- Storage Bar -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-400">Current Usage</span>
                            <span class="text-gray-300">
                            <?php echo e(round($school->storage_used / 1073741824, 2)); ?> GB /
                            <span x-text="storageGB"></span> GB
                        </span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                            <?php
                                $usedGB = $school->storage_used / 1073741824;
                                $percentage = min(($usedGB / ($school->storage_limit / 1073741824)) * 100, 100);
                            ?>
                            <div class="h-full <?php echo e($percentage >= 90 ? 'bg-red-500' : ($percentage >= 75 ? 'bg-orange-500' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-green-500'))); ?> transition-all duration-300"
                                 style="width: <?php echo e($percentage); ?>%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e(round($percentage, 1)); ?>% used</p>
                    </div>

                    <?php $__errorArgs = ['storage_limit_gb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Subscription Plan -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-semibold text-white mb-6">Subscription Plan</h2>

                <?php if($currentSubscription): ?>
                    <div class="mb-6 p-4 bg-blue-900 bg-opacity-20 border border-blue-500 border-opacity-30 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-300">
                                    Current Plan: <strong><?php echo e($currentSubscription->plan->name); ?></strong>
                                    • Status: <strong class="uppercase"><?php echo e($currentSubscription->status); ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="p-4 border border-gray-700 rounded-lg bg-gray-800/60">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Assigned Plan</p>
                            <p class="text-lg font-semibold text-white"><?php echo e($currentSubscription?->plan?->name ?? $plans->first()->name); ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-green-900 text-green-200 text-xs">Branches Plan</span>
                    </div>
                    <p class="text-sm text-gray-300 mt-3">Plan changes are locked to the Branches Plan to keep all branches consistent.</p>
                    <input type="hidden" name="plan_id" value="<?php echo e($plans->first()->id); ?>">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <button type="button"
                        onclick="if(confirm('Are you sure? This will delete all associated data including users and files.')) { document.getElementById('delete-form').submit(); }"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Delete School
                </button>

                <div class="flex space-x-4">
                    <a href="<?php echo e(route('superadmin.schools.index')); ?>"
                       class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-glow px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden delete form -->
        <form id="delete-form" action="<?php echo e(route('superadmin.schools.destroy', $school)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </div>

    <style>
        /* Custom slider styling */
        .slider::-webkit-slider-thumb {
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6366f1;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .slider::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6366f1;
            cursor: pointer;
            border: none;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        .slider::-webkit-slider-thumb:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }

        .slider::-moz-range-thumb:hover {
            background: #4f46e5;
            transform: scale(1.1);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\schools\edit.blade.php ENDPATH**/ ?>