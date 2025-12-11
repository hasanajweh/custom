<?php $__env->startSection('title', __('messages.profile.title') . ' - Scholder'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center font-bold text-white text-3xl mx-auto">
                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900"><?php echo e(Auth::user()->name); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo e(Auth::user()->email); ?></p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <?php echo e(ucfirst(Auth::user()->role)); ?>

                        </span>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500"><?php echo e(__('messages.profile.member_since')); ?></dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e(Auth::user()->created_at->format('M d, Y')); ?></dd>
                            </div>
                            <?php if(Auth::user()->role === 'supervisor'): ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('messages.users.subject')); ?></dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(Auth::user()->subject); ?></dd>
                                </div>
                            <?php elseif(Auth::user()->role === 'teacher' && Auth::user()->grade): ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('messages.users.grade')); ?></dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(Auth::user()->grade); ?></dd>
                                </div>
                            <?php endif; ?>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Profile Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900"><?php echo e(__('messages.profile.profile_information')); ?></h2>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e(__('messages.profile.update_profile_info')); ?></p>
                    </div>

                    <form method="POST" action="<?php echo e(tenant_route('profile.update', $school)); ?>" class="p-6 space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo e(__('messages.profile.full_name')); ?>

                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="<?php echo e(old('name', Auth::user()->name)); ?>"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo e(__('messages.profile.email_address')); ?>

                            </label>
                            <input 
    type="email" 
    name="email" 
    value="<?php echo e($user->email); ?>" 
    class="form-input w-full bg-gray-200 text-gray-600 cursor-not-allowed"
    disabled
/>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="ri-save-line mr-2"></i>
                                <?php echo e(__('messages.profile.save_changes')); ?>

                            </button>
                        </div>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900"><?php echo e(__('messages.profile.update_password')); ?></h2>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e(__('messages.profile.ensure_long_password')); ?></p>
                    </div>

                    <form method="POST" action="<?php echo e(tenant_route('profile.password.update', $school)); ?>" class="p-6 space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo e(__('messages.profile.current_password')); ?>

                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo e(__('messages.profile.new_password')); ?>

                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php echo e(__('messages.profile.confirm_new_password')); ?>

                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="ri-lock-line mr-2"></i>
                                <?php echo e(__('messages.profile.update_password')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\profile\edit.blade.php ENDPATH**/ ?>