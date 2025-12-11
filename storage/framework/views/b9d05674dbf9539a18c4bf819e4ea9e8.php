<?php $__env->startSection('title', __('messages.supervisors.review_files') . ' - Scholder'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e(__('messages.supervisors.files_to_review')); ?></h1>
                <p class="mt-1 text-sm text-gray-600"><?php echo e(__('messages.supervisors.review_educational_resources')); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm text-gray-500 font-medium"><?php echo e(__('messages.dashboard.total_files')); ?></p>
                    <p class="text-lg font-semibold text-gray-900"><?php echo e($files->total()); ?></p>
                </div>
            </div>
        </div>

        <!-- Comprehensive Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="ri-filter-3-line text-green-600 mr-2"></i>
                    <?php echo e(__('messages.actions.filter')); ?>

                </h3>
                <?php if(request()->anyFilled(['search', 'subject_id', 'grade_id', 'type', 'teacher_id', 'date_from', 'date_to', 'extension', 'size_min', 'size_max'])): ?>
                    <a href="<?php echo e(tenant_route('supervisor.reviews.index', $school)); ?>" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        <i class="ri-close-line mr-1"></i>
                        <?php echo e(__('messages.actions.clear')); ?>

                    </a>
                <?php endif; ?>
            </div>
            
            <form method="GET" action="<?php echo e(tenant_route('supervisor.reviews.index', $school)); ?>" class="space-y-4">
                <!-- First Row: Search, Subject, Grade -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.actions.search')); ?></label>
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="<?php echo e(__('messages.files.search_by_title')); ?>"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all pr-10">
                            <i class="ri-search-line absolute right-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Subject Filter -->
                    <?php if($subjects->isNotEmpty()): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.users.subject')); ?></label>
                        <select name="subject_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value=""><?php echo e(__('messages.files.all_files')); ?></option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Grade Filter -->
                    <?php if($grades->isNotEmpty()): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.users.grade')); ?></label>
                        <select name="grade_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value=""><?php echo e(__('messages.files.all_files')); ?></option>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade->id); ?>" <?php echo e(request('grade_id') == $grade->id ? 'selected' : ''); ?>>
                                    <?php echo e($grade->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Second Row: Type, Teacher, File Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- File Type (Submission Type) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.files.content_type')); ?></label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value=""><?php echo e(__('messages.files.all_types')); ?></option>
                            <option value="exam" <?php echo e(request('type') === 'exam' ? 'selected' : ''); ?>><?php echo e(__('messages.files.exam')); ?></option>
                            <option value="worksheet" <?php echo e(request('type') === 'worksheet' ? 'selected' : ''); ?>><?php echo e(__('messages.files.worksheet')); ?></option>
                            <option value="summary" <?php echo e(request('type') === 'summary' ? 'selected' : ''); ?>><?php echo e(__('messages.files.summary')); ?></option>
                        </select>
                    </div>

                    <!-- Teacher Filter -->
                    <?php if(isset($teachers) && $teachers->isNotEmpty()): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.users.teacher')); ?></label>
                        <select name="teacher_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value=""><?php echo e(__('messages.files.all_files')); ?></option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                    <?php echo e($teacher->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- File Extension Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.filters.file_type')); ?></label>
                        <select name="extension" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value=""><?php echo e(__('messages.files.all_files')); ?></option>
                            <option value="pdf" <?php echo e(request('extension') === 'pdf' ? 'selected' : ''); ?>><?php echo e(__('messages.files.pdf_files')); ?></option>
                            <option value="doc,docx" <?php echo e(request('extension') === 'doc,docx' ? 'selected' : ''); ?>><?php echo e(__('messages.files.word_documents')); ?></option>
                            <option value="xls,xlsx" <?php echo e(request('extension') === 'xls,xlsx' ? 'selected' : ''); ?>><?php echo e(__('messages.files.excel_files')); ?></option>
                            <option value="ppt,pptx" <?php echo e(request('extension') === 'ppt,pptx' ? 'selected' : ''); ?>><?php echo e(__('messages.files.powerpoint')); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Third Row: Date Range, File Size, Sort -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.files.date_from')); ?></label>
                        <input type="date"
                               name="date_from"
                               value="<?php echo e(request('date_from')); ?>"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.files.date_to')); ?></label>
                        <input type="date"
                               name="date_to"
                               value="<?php echo e(request('date_to')); ?>"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- File Size Min (MB) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.files.file_size')); ?> (<?php echo e(__('messages.files.mb_abbr')); ?>) - <?php echo e(__('messages.labels.minimum')); ?></label>
                        <input type="number"
                               name="size_min"
                               value="<?php echo e(request('size_min')); ?>"
                               placeholder="0"
                               min="0"
                               step="0.1"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>

                    <!-- File Size Max (MB) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.files.file_size')); ?> (<?php echo e(__('messages.files.mb_abbr')); ?>) - <?php echo e(__('messages.labels.maximum')); ?></label>
                        <input type="number"
                               name="size_max"
                               value="<?php echo e(request('size_max')); ?>"
                               placeholder="100"
                               min="0"
                               step="0.1"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Fourth Row: Sort Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.labels.sort_by', ['sort_by' => ''])); ?></label>
                        <select name="sort_by" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="created_at" <?php echo e(request('sort_by', 'created_at') === 'created_at' ? 'selected' : ''); ?>><?php echo e(__('messages.labels.date')); ?></option>
                            <option value="title" <?php echo e(request('sort_by') === 'title' ? 'selected' : ''); ?>><?php echo e(__('messages.labels.title')); ?></option>
                            <option value="file_size" <?php echo e(request('sort_by') === 'file_size' ? 'selected' : ''); ?>><?php echo e(__('messages.labels.size')); ?></option>
                            <option value="download_count" <?php echo e(request('sort_by') === 'download_count' ? 'selected' : ''); ?>><?php echo e(__('messages.files.downloads')); ?></option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.labels.sort_order', ['sort_order' => ''])); ?></label>
                        <select name="sort_order" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="desc" <?php echo e(request('sort_order', 'desc') === 'desc' ? 'selected' : ''); ?>><?php echo e(__('messages.labels.descending', ['descending' => ''])); ?></option>
                            <option value="asc" <?php echo e(request('sort_order') === 'asc' ? 'selected' : ''); ?>><?php echo e(__('messages.labels.ascending', ['ascending' => ''])); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <i class="ri-information-line mr-1"></i>
                        <?php echo e(__('messages.files.files_found', ['count' => $files->total()])); ?>

                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="this.form.reset(); this.form.submit();" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            <?php echo e(__('messages.actions.reset')); ?>

                        </button>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                            <i class="ri-filter-line mr-2"></i>
                            <?php echo e(__('messages.actions.apply_filters')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Files Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.file_title')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.teacher')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.labels.type')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.subject')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.grade')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.labels.date')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // FIXED: Safely get all attributes with fallbacks
                            $fileIcon = $file->file_icon ?? ['bg-gray-100', 'text-gray-600', 'ri-file-line'];
                            $teacherName = $file->teacher_name ?? $file->user->name ?? 'Unknown';
                            $typeInfo = $file->type_info ?? ['label' => 'File', 'classes' => 'bg-gray-100 text-gray-800', 'icon' => 'ri-file-line'];
                            $subjectName = $file->subject_name ?? 'Not specified';
                            $gradeName = $file->grade_name ?? 'Not specified';
                            $formattedSize = $file->formatted_file_size ?? '0 B';
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-xl <?php echo e($fileIcon[0]); ?> flex items-center justify-center">
                                            <i class="<?php echo e($fileIcon[2]); ?> <?php echo e($fileIcon[1]); ?> text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?php echo e($file->title); ?>

                                        </div>
                                        <div class="text-xs text-gray-500"><?php echo e($formattedSize); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-semibold">
                                        <?php echo e(substr($teacherName, 0, 1)); ?>

                                    </div>
                                    <span class="ml-3 text-sm text-gray-700 font-medium"><?php echo e($teacherName); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($typeInfo['classes']); ?>">
                                    <i class="<?php echo e($typeInfo['icon']); ?> mr-1"></i>
                                    <?php echo e($typeInfo['label']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                <?php echo e($subjectName); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                <?php echo e($gradeName); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-semibold"><?php echo e($file->created_at->format('M d, Y')); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($file->created_at->format('h:i A')); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                    $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                ?>
                                <div class="flex items-center justify-center space-x-2">
                                    
                                    <?php if($canPreview): ?>
                                        <a href="<?php echo e(tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $file->id])); ?>"
                                           target="_blank"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                           title="<?php echo e(__('messages.files.preview_file')); ?>">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled
                                                class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                title="Cannot preview <?php echo e(strtoupper($extension)); ?> files in browser">
                                            <i class="ri-eye-off-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>

                                    
                                    <a href="<?php echo e(tenant_route('supervisor.reviews.download', $school, ['fileSubmission' => $file->id])); ?>"
                                       class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                       title="<?php echo e(__('messages.files.download_file')); ?>">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>

                                    
                                    <?php if($canPreview): ?>
                                        <button onclick="printFile('<?php echo e(tenant_route('supervisor.reviews.preview', $school, ['fileSubmission' => $file->id])); ?>', '<?php echo e($file->title); ?>', '<?php echo e($teacherName); ?>', '<?php echo e($file->created_at->format('M d, Y')); ?>')"
                                                class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                title="<?php echo e(__('messages.files.print_file')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php else: ?>
                                        <button disabled
                                                class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled"
                                                title="Cannot print <?php echo e(strtoupper($extension)); ?> files in browser">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="ri-file-search-line text-3xl text-gray-400"></i>
                                    </div>
                                    <?php if(isset($subjects) && $subjects->isEmpty()): ?>
                                        <p class="text-gray-500 text-lg font-medium"><?php echo e(__('messages.dashboard.no_subjects_assigned')); ?></p>
                                        <p class="text-gray-400 text-sm mt-1"><?php echo e(__('messages.dashboard.contact_administrator')); ?></p>
                                    <?php else: ?>
                                        <p class="text-gray-500 text-lg font-medium"><?php echo e(__('messages.dashboard.no_files_to_review')); ?></p>
                                        <p class="text-gray-400 text-sm mt-1"><?php echo e(__('messages.dashboard.files_appear_when_uploaded')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($files->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-100">
                    <?php echo e($files->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php echo $__env->make('components.pwa-install-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .action-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function printFile(previewUrl, title, uploadedBy, uploadDate) {
            const printWindow = window.open(previewUrl, '_blank', 'width=900,height=700');
            if (!printWindow) {
                alert('Please allow pop-ups to print files');
                return;
            }
            printWindow.addEventListener('load', function() {
                setTimeout(() => {
                    printWindow.print();
                }, 800);
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\supervisor\reviews\index.blade.php ENDPATH**/ ?>