<?php $__env->startSection('page-title', 'School Setup'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Final Setup</h1>
            <p class="mt-2 text-gray-400">Complete setup for <span class="text-indigo-400"><?php echo e($school->name); ?></span></p>
        </div>

        <form method="POST" action="<?php echo e(route('superadmin.schools.completeSetup', ['school' => $school->id])); ?>"
              x-data="{ selectedPlan: <?php echo e(old('plan_id', $plans->first()->id ?? 'null')); ?>, selectedTerm: '<?php echo e(old('plan_term', 'monthly')); ?>' }">
            <?php echo csrf_field(); ?>

            <!-- Subscription Plans -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-4">Choose a Subscription Plan</h2>
                <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-sm text-red-400 mb-4"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label @click="selectedPlan = <?php echo e($plan->id); ?>"
                               :class="{ 'ring-2 ring-indigo-500 border-indigo-500': selectedPlan === <?php echo e($plan->id); ?>, 'border-gray-700': selectedPlan !== <?php echo e($plan->id); ?> }"
                               class="relative flex flex-col cursor-pointer rounded-lg border bg-gray-800 p-4 hover:bg-gray-750 transition-all">
                            <input type="radio" name="plan_id" value="<?php echo e($plan->id); ?>" class="sr-only" x-model.number="selectedPlan">

                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-medium text-white"><?php echo e($plan->name); ?></h3>
                                    <p class="mt-1 text-sm text-gray-400"><?php echo e($plan->storage_limit_in_gb); ?> GB Storage</p>
                                </div>
                                <svg x-show="selectedPlan === <?php echo e($plan->id); ?>" class="h-5 w-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div class="mt-auto">
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-bold text-white">$<?php echo e(number_format($plan->price_monthly / 100, 2)); ?></span>
                                    <span class="text-sm text-gray-400 ml-1">/mo</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">or $<?php echo e(number_format($plan->price_annually / 100, 2)); ?> per year</p>
                            </div>

                            <?php if($plan->features && is_array($plan->features)): ?>
                                <ul class="mt-4 space-y-2">
                                    <?php $__currentLoopData = array_slice($plan->features, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="flex items-start">
                                            <svg class="h-4 w-4 text-green-400 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-xs text-gray-400"><?php echo e($feature); ?></span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Billing Cycle -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-4">Billing Cycle</h2>
                <div class="flex gap-x-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="plan_term" value="monthly" x-model="selectedTerm"
                               class="h-4 w-4 text-indigo-600 bg-gray-800 border-gray-600 focus:ring-indigo-500">
                        <span class="ml-3 text-sm text-gray-300">Monthly billing</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="plan_term" value="annually" x-model="selectedTerm"
                               class="h-4 w-4 text-indigo-600 bg-gray-800 border-gray-600 focus:ring-indigo-500">
                        <span class="ml-3 text-sm text-gray-300">Annual billing (save 10%)</span>
                    </label>
                </div>
            </div>

            <!-- Admin Account Creation -->
            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-6">Create School Admin Account</h2>

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Admin Name</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Admin Email</label>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="btn-glow px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                    Complete Setup & Finish
                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\schools\setup.blade.php ENDPATH**/ ?>