<?php $__env->startSection('title', __('messages.dashboard.teacher_dashboard') . ' - ' . __('messages.app.name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Welcome Header with Quick Actions -->
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-6 lg:mb-0">
                        <h1 class="text-3xl font-bold mb-2"><?php echo e(__('messages.dashboard.welcome_back', ['name' => Auth::user()->name])); ?></h1>
                        <p class="text-indigo-100 text-lg"><?php echo e(__('messages.dashboard.ready_share_knowledge')); ?></p>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="<?php echo e(tenant_route('teacher.files.create', $school)); ?>"
                           class="group inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                            <i class="ri-upload-cloud-line mr-2 text-xl group-hover:animate-bounce"></i>
                            <?php echo e(__('messages.files.upload_file')); ?>

                        </a>
                        <a href="<?php echo e(tenant_route('teacher.files.index', $school)); ?>"
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur text-white rounded-xl font-semibold hover:bg-white/30 transition-all border border-white/30">
                            <i class="ri-folder-line mr-2 text-xl"></i>
                            <?php echo e(__('messages.files.my_files')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Uploads -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.dashboard.my_uploads')); ?></p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($totalUploads); ?></p>
                        <p class="text-xs text-gray-500 mt-2"><?php echo e(__('messages.dashboard.total_files_shared')); ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-upload-cloud-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Downloads -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.dashboard.total_downloads')); ?></p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($totalDownloads); ?></p>
                        <p class="text-xs text-gray-500 mt-2"><?php echo e(__('messages.dashboard.times_downloaded')); ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-download-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.dashboard.this_week')); ?></p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo e(\App\Models\FileSubmission::where('user_id', Auth::id())->where('school_id', $school->id)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()); ?></p>
                        <p class="text-xs text-gray-500 mt-2"><?php echo e(__('messages.dashboard.files_uploaded')); ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-calendar-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1"><?php echo e(__('messages.dashboard.recent_activity')); ?></p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($recentFiles->count()); ?></p>
                        <p class="text-xs text-gray-500 mt-2"><?php echo e(__('messages.dashboard.files_this_week')); ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-activity-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Files & Quick Tips -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Files (2/3 width) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900"><?php echo e(__('messages.dashboard.my_recent_files')); ?></h2>
                        <a href="<?php echo e(tenant_route('teacher.files.index', $school)); ?>"
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"><?php echo e(__('messages.actions.view_all')); ?> →</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $recentFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-all group">
                                <div class="flex items-center space-x-4">
                                    <?php
                                        $typeIcons = [
                                            'daily_plan' => 'ri-calendar-line text-blue-500',
                                            'weekly_plan' => 'ri-calendar-2-line text-purple-500',
                                            'monthly_plan' => 'ri-calendar-check-line text-green-500',
                                            'exam' => 'ri-file-text-line text-red-500',
                                            'worksheet' => 'ri-file-copy-line text-yellow-500',
                                            'summary' => 'ri-file-list-line text-indigo-500'
                                        ];
                                        $icon = $typeIcons[$file->submission_type] ?? 'ri-file-line text-gray-500';
                                    ?>
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="<?php echo e($icon); ?> text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900"><?php echo e(Str::limit($file->title, 40)); ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php echo e($file->created_at->diffForHumans()); ?>

                                            <?php if(!in_array($file->submission_type, ['daily_plan', 'weekly_plan', 'monthly_plan'])): ?>
                                                <?php if($file->subject || $file->grade): ?>
                                                    • <?php echo e($file->subject?->name); ?> <?php echo e($file->grade?->name); ?>

                                                <?php endif; ?>
                                            <?php else: ?>
                                                • <?php echo e(ucfirst(str_replace('_', ' ', $file->submission_type))); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                        <i class="ri-download-line mr-1"></i><?php echo e($file->download_count ?: 0); ?>

                                    </span>
                                    <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="<?php echo e(tenant_route('teacher.files.preview', $school, ['fileSubmission' => $file->id])); ?>"
                                           target="_blank"
                                           class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="<?php echo e(tenant_route('teacher.files.download', $school, ['fileSubmission' => $file->id])); ?>"
                                           class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ri-folder-open-line text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 mb-4"><?php echo e(__('messages.files.no_files_uploaded')); ?></p>
                                <a href="<?php echo e(tenant_route('teacher.files.create', $school)); ?>"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <i class="ri-upload-2-line mr-2"></i>
                                    <?php echo e(__('messages.files.upload_first_file')); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Tips -->
            <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 rounded-xl p-6 border border-indigo-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white mr-3">
                        <i class="ri-lightbulb-flash-line"></i>
                    </span>
                    <?php echo e(__('messages.dashboard.quick_tips')); ?>

                </h3>
                <div class="space-y-4">
                    <div class="bg-white/70 backdrop-blur rounded-xl p-4 border border-white/50 hover:shadow-md transition-all">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="ri-file-edit-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e(__('messages.files.clear_titles')); ?></p>
                                <p class="text-xs text-gray-600 mt-1"><?php echo e(__('messages.files.use_descriptive_titles')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 backdrop-blur rounded-xl p-4 border border-white/50 hover:shadow-md transition-all">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="ri-shield-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e(__('messages.files.file_formats')); ?></p>
                                <p class="text-xs text-gray-600 mt-1"><?php echo e(__('messages.files.pdf_word_excel')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 backdrop-blur rounded-xl p-4 border border-white/50 hover:shadow-md transition-all">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="ri-upload-cloud-2-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900"><?php echo e(__('messages.files.regular_uploads')); ?></p>
                                <p class="text-xs text-gray-600 mt-1"><?php echo e(__('messages.files.share_weekly')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-4 text-white mt-6">
                        <p class="text-sm font-semibold mb-2"><?php echo e(__('messages.dashboard.pro_tip')); ?></p>
                        <p class="text-xs"><?php echo e(__('messages.files.upload_best_materials')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('components.pwa-install-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\teacher\dashboard.blade.php ENDPATH**/ ?>