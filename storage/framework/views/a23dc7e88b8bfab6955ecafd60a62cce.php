<?php $__env->startSection('title', __('messages.supervisors.review_file') . ' - ' . __('messages.app.name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900"><?php echo e(__('messages.supervisors.review_file')); ?></h1>
                    <a href="<?php echo e(tenant_route('supervisor.reviews.index', $school)); ?>"
                       class="text-sm text-gray-600 hover:text-gray-900">
                        <i class="ri-arrow-left-line mr-1"></i> <?php echo e(__('messages.supervisors.back_to_files')); ?>

                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- File Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.labels.title')); ?></h3>
                        <p class="mt-1 text-lg font-medium text-gray-900"><?php echo e($fileSubmission->title); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.labels.type')); ?></h3>
                        <p class="mt-1">
                            <?php
                                $typeLabels = [
                                    'exam' => [__('messages.files.exam'), 'bg-red-100 text-red-700'],
                                    'worksheet' => [__('messages.files.worksheet'), 'bg-yellow-100 text-yellow-700'],
                                    'summary' => [__('messages.files.summary'), 'bg-indigo-100 text-indigo-700']
                                ];
                                $type = $typeLabels[$fileSubmission->submission_type] ?? [__('messages.files.general_resource'), 'bg-gray-100 text-gray-700'];
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($type[1]); ?>">
                            <?php echo e($type[0]); ?>

                        </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.users.teacher')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e($fileSubmission->user->name); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.users.subject')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e($fileSubmission->subject->name); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.users.grade')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e($fileSubmission->grade->name); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.files.file_size')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e(number_format($fileSubmission->file_size / 1048576, 2)); ?> MB</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.files.uploaded')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e($fileSubmission->created_at->format('M d, Y h:i A')); ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500"><?php echo e(__('messages.files.downloads')); ?></h3>
                        <p class="mt-1 text-lg text-gray-900"><?php echo e($fileSubmission->download_count); ?> <?php echo e(__('messages.dashboard.times_downloaded')); ?></p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-center gap-4 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $fileSubmission->id])); ?>"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-external-link-line mr-2"></i>
                        <?php echo e(__('messages.files.preview_file')); ?>

                    </a>
                    <a href="<?php echo e(tenant_route('supervisor.reviews.download', $school, ['fileSubmission' => $fileSubmission->id])); ?>"
                       class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="ri-download-2-line mr-2"></i>
                        <?php echo e(__('messages.files.download_file')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\teacher\files\show.blade.php ENDPATH**/ ?>