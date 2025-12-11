<?php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? Auth::user()->school;
?>


<?php $__env->startSection('title', __('messages.files.title') . ' - ' . __('messages.app.name')); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between flex-wrap gap-6">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?php echo e(__('messages.files.teachers_files')); ?></h1>
                        <p class="text-lg text-gray-600"><?php echo e(__('messages.files.browse_manage_resources')); ?></p>
                    </div>
                    <div class="storage-info">
                        <div class="text-sm text-gray-500 mb-2"><?php echo e(__('messages.files.storage_used')); ?></div>
                        <div class="storage-text">
                            <span class="storage-used"><?php echo e(formatBytes($school->storage_used)); ?></span>
                            <span class="storage-separator">/</span>
                            <span class="storage-limit"><?php echo e(formatBytes($school->storage_limit)); ?></span>
                        </div>
                        <div class="w-48 bg-gray-200 rounded-full h-2.5 mt-3">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: <?php echo e(min(100, ($school->storage_used / $school->storage_limit) * 100)); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compact Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="<?php echo e(tenant_route('school.admin.file-browser.index', $school)); ?>">
                    <!-- Search Bar - Full Width -->
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 <?php echo e(app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3'); ?> flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="<?php echo e(__('messages.files.search_by_title')); ?>"
                                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Compact Filter Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3 mb-4">
                        <!-- Type Filter -->
                        <div class="xl:col-span-1">
                            <select name="type" id="type" class="compact-select">
                                <option value=""><?php echo e(__('messages.files.all_types')); ?></option>
                                <option value="exam" <?php echo e(request('type') == 'exam' ? 'selected' : ''); ?>><?php echo e(__('messages.files.exams')); ?></option>
                                <option value="worksheet" <?php echo e(request('type') == 'worksheet' ? 'selected' : ''); ?>><?php echo e(__('messages.files.worksheets')); ?></option>
                                <option value="summary" <?php echo e(request('type') == 'summary' ? 'selected' : ''); ?>><?php echo e(__('messages.files.summaries')); ?></option>
                            </select>
                        </div>

                        <!-- Extension Filter -->
                        <div class="xl:col-span-1">
                            <select name="extension" id="extension" class="compact-select">
                                <option value=""><?php echo e(__('messages.files.all_files')); ?></option>
                                <option value="pdf" <?php echo e(request('extension') == 'pdf' ? 'selected' : ''); ?>>PDF</option>
                                <option value="doc,docx" <?php echo e(request('extension') == 'doc,docx' ? 'selected' : ''); ?>>Word</option>
                                <option value="xls,xlsx" <?php echo e(request('extension') == 'xls,xlsx' ? 'selected' : ''); ?>>Excel</option>
                                <option value="ppt,pptx" <?php echo e(request('extension') == 'ppt,pptx' ? 'selected' : ''); ?>>PowerPoint</option>
                                <option value="jpg,jpeg,png,gif" <?php echo e(request('extension') == 'jpg,jpeg,png,gif' ? 'selected' : ''); ?>><?php echo e(__('messages.file_types.image')); ?></option>
                            </select>
                        </div>

                        <!-- Teacher Filter -->
                        <div class="xl:col-span-2">
                            <select name="teacher_id" id="teacher_id" class="compact-select">
                                <option value=""><?php echo e(__('messages.users.teachers')); ?></option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div class="xl:col-span-1">
                            <select name="subject_id" id="subject_id" class="compact-select">
                                <option value=""><?php echo e(__('messages.subjects.all_subjects')); ?></option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                        <?php echo e($subject->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Grade Filter -->
                        <div class="xl:col-span-1">
                            <select name="grade_id" id="grade_id" class="compact-select">
                                <option value=""><?php echo e(__('messages.grades.all_grades')); ?></option>
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade->id); ?>" <?php echo e(request('grade_id') == $grade->id ? 'selected' : ''); ?>>
                                        <?php echo e($grade->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="xl:col-span-1">
                            <input type="date"
                                   name="date_from"
                                   id="date_from"
                                   value="<?php echo e(request('date_from')); ?>"
                                   class="compact-select">
                        </div>

                        <!-- Date To -->
                        <div class="xl:col-span-1">
                            <input type="date"
                                   name="date_to"
                                   id="date_to"
                                   value="<?php echo e(request('date_to')); ?>"
                                   class="compact-select">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 flex-wrap">
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-search-line"></i>
                            <span><?php echo e(__('messages.actions.apply_filters')); ?></span>
                        </button>

                        <a href="<?php echo e(tenant_route('school.admin.file-browser.index', $school)); ?>"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="ri-refresh-line"></i>
                            <span><?php echo e(__('messages.actions.reset')); ?></span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Files Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900"><?php echo e(__('messages.files.educational_resources')); ?></h2>
                    <p class="text-gray-600 mt-1"><?php echo e(__('messages.files.files_found', ['count' => $files->total()])); ?></p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-8 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.file_title')); ?></th>
                            <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.content_type')); ?></th>
                            <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.teacher')); ?></th>
                            <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.subject')); ?> & <?php echo e(__('messages.users.grade')); ?></th>
                            <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.uploaded')); ?></th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.downloads')); ?></th>
                            <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.actions')); ?></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                                $iconData = match($extension) {
                                    'pdf' => ['ri-file-pdf-line', 'bg-red-100', 'text-red-600'],
                                    'doc', 'docx' => ['ri-file-word-2-line', 'bg-blue-100', 'text-blue-600'],
                                    'xls', 'xlsx' => ['ri-file-excel-2-line', 'bg-green-100', 'text-green-600'],
                                    'ppt', 'pptx' => ['ri-file-ppt-2-line', 'bg-orange-100', 'text-orange-600'],
                                    'jpg', 'jpeg', 'png', 'gif' => ['ri-image-line', 'bg-purple-100', 'text-purple-600'],
                                    'txt' => ['ri-file-text-line', 'bg-gray-100', 'text-gray-600'],
                                    default => ['ri-file-line', 'bg-gray-100', 'text-gray-600']
                                };
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-8 py-6">
                                    <div class="flex items-center <?php echo e(app()->getLocale() === 'ar' ? 'space-x-reverse' : ''); ?> space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 <?php echo e($iconData[1]); ?> rounded-xl flex items-center justify-center shadow-sm">
                                                <i class="<?php echo e($iconData[0]); ?> <?php echo e($iconData[2]); ?> text-xl"></i>
                                            </div>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 text-sm truncate" title="<?php echo e($file->title); ?>">
                                                <?php echo e(Str::limit($file->title, 40)); ?>

                                            </div>
                                            <div class="flex items-center <?php echo e(app()->getLocale() === 'ar' ? 'space-x-reverse' : ''); ?> space-x-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                    .<?php echo e(strtoupper($extension)); ?>

                                                </span>
                                                <span class="text-xs text-gray-500"><?php echo e(formatBytes($file->file_size)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    <?php
                                        $typeStyles = [
                                            'exam' => [__('messages.files.exam'), 'bg-red-100 text-red-800', 'ri-file-list-3-line'],
                                            'worksheet' => [__('messages.files.worksheet'), 'bg-yellow-100 text-yellow-800', 'ri-file-edit-line'],
                                            'summary' => [__('messages.files.summary'), 'bg-indigo-100 text-indigo-800', 'ri-file-text-line'],
                                        ];
                                        $typeData = $typeStyles[$file->submission_type] ?? [__('messages.files.general_resource'), 'bg-gray-100 text-gray-800', 'ri-file-line'];
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold <?php echo e($typeData[1]); ?> shadow-sm">
                                        <i class="<?php echo e($typeData[2]); ?> <?php echo e(app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1'); ?>"></i>
                                        <?php echo e($typeData[0]); ?>

                                    </span>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="text-sm font-semibold text-gray-900"><?php echo e($file->user->name ?? __('messages.users.unknown')); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e(__('messages.users.teacher')); ?></div>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="text-sm text-gray-900 font-semibold"><?php echo e($file->subject_name ?? __('messages.subjects.not_specified')); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($file->grade_name ?? __('messages.grades.not_specified')); ?></div>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="text-sm text-gray-900 font-medium"><?php echo e($file->created_at->format('M d, Y')); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($file->created_at->format('g:i A')); ?></div>
                                </td>

                                <td class="px-6 py-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-blue-50 rounded-full text-xs font-semibold text-blue-700 shadow-sm">
                                        <i class="ri-download-line <?php echo e(app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1'); ?>"></i>
                                        <?php echo e($file->download_count ?? 0); ?>

                                    </span>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center <?php echo e(app()->getLocale() === 'ar' ? 'space-x-reverse' : ''); ?> space-x-2">
                                        
                                          <?php if($canPreview): ?>
                                              <a href="<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>"
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

                                        
                                          <a href="<?php echo e(tenant_route('school.admin.file-browser.download', [$school, $file])); ?>"
                                             class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                             title="<?php echo e(__('messages.files.download_file')); ?>">
                                              <i class="ri-download-line text-lg"></i>
                                          </a>

                                        
                                          <?php if($canPreview): ?>
                                              <button onclick="printFile('<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>', '<?php echo e($file->title); ?>', '<?php echo e($file->user->name ?? 'Unknown'); ?>', '<?php echo e($file->created_at->format('M d, Y')); ?>')"
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
                                <td colspan="7" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="ri-folder-open-line text-2xl text-gray-400"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.files.no_files')); ?></h3>
                                            <p class="text-gray-500"><?php echo e(__('messages.files.no_files_match')); ?></p>
                                            <p class="text-gray-400 text-sm mt-1"><?php echo e(__('messages.files.try_adjusting_filters')); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($files->hasPages()): ?>
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 font-medium">
                                <?php echo e(__('messages.files.showing_results', [
                                    'first' => $files->firstItem(),
                                    'last' => $files->lastItem(),
                                    'total' => $files->total()
                                ])); ?>

                            </div>
                            <div class="pagination-wrapper">
                                <?php echo e($files->withQueryString()->links()); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-file-list-3-line text-xl text-blue-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_files'] ?? $files->total()); ?></div>
                    <div class="text-sm text-gray-600 mt-1 font-medium"><?php echo e(__('messages.dashboard.total_files')); ?></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-database-2-line text-xl text-green-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e(formatBytes($stats['total_size'] ?? 0)); ?></div>
                    <div class="text-sm text-gray-600 mt-1 font-medium"><?php echo e(__('messages.files.total_size')); ?></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-download-cloud-line text-xl text-purple-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_downloads'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600 mt-1 font-medium"><?php echo e(__('messages.dashboard.total_downloads')); ?></div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-user-line text-xl text-amber-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['active_teachers'] ?? count($teachers)); ?></div>
                    <div class="text-sm text-gray-600 mt-1 font-medium"><?php echo e(__('messages.dashboard.active_teachers')); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Storage Info Styling - Same for LTR and RTL */
        .storage-info {
            text-align: center;
        }

        .storage-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            direction: ltr;
        }

        .storage-used {
            color: #2563eb;
        }

        .storage-separator {
            color: #9ca3af;
            font-weight: 400;
        }

        .storage-limit {
            color: #6b7280;
        }

        /* Compact Select/Input Styling */
        .compact-select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: <?php echo e(app()->getLocale() === 'ar' ? 'left 0.75rem' : 'right 0.75rem'); ?> center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        <?php echo e(app()->getLocale() === 'ar' ? 'padding-left: 2.25rem;' : 'padding-right: 2.25rem;'); ?>

}

        .compact-select:hover {
            border-color: #9ca3af;
            background-color: #f9fafb;
        }

        .compact-select:focus {
            outline: none;
            border-color: #3b82f6;
            ring: 2px;
            ring-color: #3b82f6;
            background-color: #ffffff;
        }

        input[type="date"].compact-select {
            background-image: none;
            padding: 0.625rem 0.75rem;
        }

        .action-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Pagination styling */
        .pagination-wrapper .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-wrapper .pagination .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            color: #374151;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-wrapper .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination-wrapper .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
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

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\school\admin\file-browser\index.blade.php ENDPATH**/ ?>