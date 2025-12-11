<?php $__env->startSection('title', __('messages.supervisors.title') . ' - Scholder'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Enhanced Header with Statistics -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold"><?php echo e(__('messages.supervisors.title')); ?></h1>
                    <p class="text-indigo-100 mt-2"><?php echo e(__('messages.supervisors.browse_supervisor_files')); ?></p>
                </div>
                <div class="hidden md:block">
                    <i class="ri-user-star-line text-5xl text-white/20"></i>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-100"><?php echo e(__('messages.users.total_users')); ?></p>
                            <p class="text-2xl font-bold"><?php echo e($supervisors->count()); ?></p>
                        </div>
                        <i class="ri-group-line text-2xl text-white/50"></i>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-100"><?php echo e(__('messages.supervisors.active_supervisors')); ?></p>
                            <p class="text-2xl font-bold"><?php echo e($supervisors->where('last_activity', '!=', null)->count()); ?></p>
                        </div>
                        <i class="ri-user-follow-line text-2xl text-white/50"></i>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-indigo-100"><?php echo e(__('messages.files.total_files')); ?></p>
                            <p class="text-2xl font-bold"><?php echo e($supervisors->sum('uploaded_count') ?? 0); ?></p>
                        </div>
                        <i class="ri-file-list-line text-2xl text-white/50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisors Grid View -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-user-star-line text-indigo-600 mr-2"></i>
                        <?php echo e(__('messages.navigation.supervisors')); ?>

                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500"><?php echo e($supervisors->count()); ?> <?php echo e(__('messages.users.supervisors')); ?></span>
                    </div>
                </div>
            </div>

            <?php if($supervisors->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-300 overflow-hidden">
                            <!-- Supervisor Header -->
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-indigo-600 font-bold text-lg shadow-md">
                                        <?php echo e(strtoupper(substr($supervisor->name, 0, 1))); ?>

                                    </div>
                                    <div class="text-white">
                                        <h3 class="font-semibold text-lg"><?php echo e($supervisor->name); ?></h3>
                                        <p class="text-indigo-100 text-sm"><?php echo e($supervisor->email); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Supervisor Details -->
                            <div class="p-4 space-y-3">
                                <!-- Subject -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500"><?php echo e(__('messages.users.subject')); ?>:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <i class="ri-book-line mr-1"></i>
                                        <?php echo e($supervisor->subject); ?>

                                    </span>
                                </div>

                                <!-- Uploads Stats -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500"><?php echo e(__('messages.files.uploads')); ?>:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="ri-upload-line mr-1"></i>
                                        <?php echo e($supervisor->uploaded_count ?? 0); ?>

                                    </span>
                                </div>

                                <!-- Can Review -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500"><?php echo e(__('messages.supervisors.review_files')); ?>:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                        <i class="ri-eye-line mr-1"></i>
                                        <?php echo e($supervisor->reviewed_count ?? 0); ?> <?php echo e(__('messages.files.files_found', ['count' => $supervisor->reviewed_count ?? 0])); ?>

                                    </span>
                                </div>

                                <!-- Last Activity -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500"><?php echo e(__('messages.files.upload_date')); ?>:</span>
                                    <span class="text-xs text-gray-600">
                                        <?php if($supervisor->last_activity): ?>
                                            <?php echo e($supervisor->last_activity->diffForHumans()); ?>

                                        <?php else: ?>
                                            <?php echo e(__('messages.users.never')); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    <a href="<?php echo e(tenant_route('school.admin.supervisors.files', $school, ['supervisor' => $supervisor])); ?>"
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors group-hover:shadow-md">
                                        <i class="ri-folder-line mr-1"></i>
                                        <?php echo e(__('messages.files.view_file')); ?>

                                    </a>

                                    <div class="flex items-center space-x-2">
                                        <a href="<?php echo e(tenant_route('school.admin.users.edit', [$school, $supervisor])); ?>"
                                           class="p-1.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                           title="<?php echo e(__('messages.users.edit_user')); ?>">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="ri-user-star-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.users.no_users_found')); ?></h3>
                    <p class="text-gray-500 mb-6"><?php echo e(__('messages.users.try_adjusting_search')); ?></p>
                    <a href="<?php echo e(tenant_route('school.admin.users.create', $school)); ?>?role=supervisor"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        <i class="ri-user-add-line mr-2"></i>
                        <?php echo e(__('messages.users.add_user')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\supervisor\index.blade.php ENDPATH**/ ?>