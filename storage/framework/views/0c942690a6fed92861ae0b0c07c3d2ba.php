<?php
    $school = $school
        ?? request()->attributes->get('branch')
        ?? request()->attributes->get('school')
        ?? Auth::user()->school;

    $branch = $branch ?? $school;
    $network = $network ?? request()->attributes->get('network') ?? $school?->network ?? Auth::user()?->network;
?>

<?php $__env->startSection('title', __('messages.dashboard.admin_dashboard') . ' - ' . __('messages.app.name')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-content { display: block !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .print-header { margin-bottom: 20px; }
            .file-preview { page-break-inside: avoid; }
        }

        .action-disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* RTL Table Improvements */
        [dir="rtl"] table {
            text-align: right;
        }

        [dir="rtl"] th,
        [dir="rtl"] td {
            text-align: center;
        }

        [dir="rtl"] th:first-child,
        [dir="rtl"] td:first-child {
            text-align: center;
        }

        [dir="rtl"] th:last-child,
        [dir="rtl"] td:last-child {
            text-align: center;
        }

        /* Better vertical and horizontal alignment */
        table td {
            vertical-align: middle;
            text-align: center;
        }

        table th {
            vertical-align: middle;
            text-align: center;
        }

        /* Exception for first column (file title) - align left */
        table td:first-child {
            text-align: left;
        }

        [dir="rtl"] table td:first-child {
            text-align: right;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white no-print">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-blue-100 text-sm"><?php echo e(__('messages.dashboard.today_uploads')); ?></p>
                    <p class="text-4xl font-bold"><?php echo e($todayUploads); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-blue-100 text-sm"><?php echo e(__('messages.dashboard.this_week')); ?></p>
                    <p class="text-4xl font-bold"><?php echo e($weekUploads); ?></p>
                </div>
                <div class="text-center">
                    <p class="text-blue-100 text-sm"><?php echo e(__('messages.dashboard.total_files')); ?></p>
                    <p class="text-4xl font-bold"><?php echo e($totalFiles); ?></p>
                </div>

                <?php if(isset($topTeacher) && $topTeacher): ?>
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <i class="ri-vip-crown-fill text-yellow-300 text-sm mr-1"></i>
                            <p class="text-blue-100 text-sm"><?php echo e(__('messages.dashboard.top_contributor')); ?></p>
                        </div>
                        <div class="relative inline-block">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br <?php echo e(getAvatarColor($topTeacher->name)); ?> flex items-center justify-center mx-auto shadow-lg border-2 border-white">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white leading-none"><?php echo e($topTeacher->total_uploads); ?></div>
                                    <div class="text-xs text-white opacity-90 leading-none mt-0.5"><?php echo e(__('messages.labels.files')); ?></div>
                                </div>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-md">
                                <i class="ri-vip-crown-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-white mt-2 truncate px-2" title="<?php echo e($topTeacher->name); ?>">
                            <?php echo e(Str::limit($topTeacher->name, 20)); ?>

                        </p>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-blue-100 text-sm"><?php echo e(__('messages.dashboard.active_teachers')); ?></p>
                        <p class="text-4xl font-bold"><?php echo e($totalTeachers); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 no-print">
            <a href="<?php echo e(tenant_route('school.admin.file-browser.index', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-folder-line text-3xl text-blue-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.files.browse_files')); ?></h3>
            </a>

            <a href="<?php echo e(tenant_route('school.admin.users.create', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-user-add-line text-3xl text-green-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.users.add_user')); ?></h3>
            </a>

            <a href="<?php echo e(tenant_route('school.admin.subjects.index', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-book-line text-3xl text-orange-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.navigation.subjects')); ?></h3>
            </a>

            <a href="<?php echo e(tenant_route('school.admin.grades.index', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-graduation-cap-line text-3xl text-indigo-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.navigation.grades')); ?></h3>
            </a>

            <a href="<?php echo e(tenant_route('school.admin.plans.index', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-calendar-check-line text-3xl text-purple-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.plans.view_plan')); ?></h3>
            </a>

            <a href="<?php echo e(tenant_route('school.admin.supervisors.index', $school)); ?>"
               class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all group">
                <i class="ri-user-star-line text-3xl text-teal-600 mb-3"></i>
                <h3 class="font-semibold text-gray-900"><?php echo e(__('messages.navigation.supervisors')); ?></h3>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="ri-file-text-line mr-2 text-blue-600"></i>
                            <?php echo e(__('messages.dashboard.latest_academic_materials')); ?>

                            <span class="ml-2 text-sm font-normal text-gray-500">(<?php echo e(__('messages.dashboard.last_72_hours')); ?>)</span>
                        </h2>
                        <p class="text-gray-600 mt-1"><?php echo e(__('messages.dashboard.academic_materials_description')); ?></p>
                    </div>
                    <div class="flex items-center gap-3 no-print">
                        <?php if($academicExtensions->count() > 0): ?>
                            <select onchange="filterByExtension('academic_ext', this.value)"
                                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value=""><?php echo e(__('messages.files.all_types')); ?></option>
                                <?php $__currentLoopData = $academicExtensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <option value="<?php echo e($ext); ?>" <?php if($academicExtFilter == $ext): echo 'selected'; endif; ?>>
                                        .<?php echo e(strtoupper($ext)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <select onchange="window.location.href='<?php echo e(url()->current()); ?>?per_page=' + this.value"
                                class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <?php $__currentLoopData = [10, 20, 30, 40, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <option value="<?php echo e($size); ?>" <?php if($perPage == $size): echo 'selected'; endif; ?>>
                                    <?php echo e($size); ?> per page
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <a href="<?php echo e(tenant_route('school.admin.file-browser.index', $school)); ?>?role=teacher"
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                           <?php echo e(__('messages.actions.view_all')); ?> →
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.file_title')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.content_type')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.teacher')); ?></th>
                        
                        
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.labels.subject_grade')); ?></th>
                        
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.uploaded')); ?></th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider no-print"><?php echo e(__('messages.actions.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $academicFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm">
                                            <i class="ri-file-line text-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-sm truncate" title="<?php echo e($file->title); ?>">
                                            <?php echo e(Str::limit($file->title, 40)); ?>

                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                .<?php echo e(strtoupper($extension)); ?>

                                            </span>
                                            <span class="text-xs text-gray-500"><?php echo e(number_format($file->file_size / 1048576, 2)); ?> MB</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 shadow-sm">
                                    <i class="ri-file-list-3-line mr-1"></i>
                                    <?php echo e($file->submission_type === 'supervisor_upload'
                                        ? __('messages.files.supervisor_resource')
                                        : __('messages.files.submission_types.' . $file->submission_type)); ?>                                </span>
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
                                <div class="text-xs text-gray-500"><?php echo e($file->created_at->diffForHumans()); ?></div>
                            </td>
                            <td class="px-8 py-6 no-print">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if($canPreview): ?>
                                        <a href="<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>"
                                           target="_blank"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                           title="<?php echo e(__('messages.actions.preview')); ?>">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-eye-off-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>

                                    <a href="<?php echo e(tenant_route('school.admin.file-browser.download', [$school, $file])); ?>"
                                       class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                       title="<?php echo e(__('messages.actions.download')); ?>">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>

                                    <?php if($canPreview): ?>
                                        <button onclick="printFile('<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>', '<?php echo e($file->title); ?>', '<?php echo e($file->user->name ?? __('messages.users.unknown')); ?>', '<?php echo e($file->created_at->format('M d, Y')); ?>')"
                                                class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                title="<?php echo e(__('messages.actions.print')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="ri-file-line text-2xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        
                                        <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.dashboard.empty.academic_files_title')); ?></h3>
                                        <p class="text-gray-500"><?php echo e(__('messages.dashboard.empty.academic_files_subtitle')); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($academicFiles->hasPages()): ?>
                <div class="px-8 py-4 border-t border-gray-100 no-print">
                    <?php echo e($academicFiles->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="ri-calendar-line mr-2 text-purple-600"></i>
                            <?php echo e(__('messages.dashboard.latest_lesson_plans')); ?>

                            <span class="ml-2 text-sm font-normal text-gray-500">(<?php echo e(__('messages.dashboard.last_72_hours')); ?>)</span>
                        </h2>
                        <p class="text-gray-600 mt-1"><?php echo e(__('messages.dashboard.lesson_plans_description')); ?></p>
                    </div>
                    <div class="flex items-center gap-3 no-print">
                        <?php if($plansExtensions->count() > 0): ?>
                            <select onchange="filterByExtension('plans_ext', this.value)"
                                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                                <option value=""><?php echo e(__('messages.files.all_types')); ?></option>
                                <?php $__currentLoopData = $plansExtensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <option value="<?php echo e($ext); ?>" <?php if($plansExtFilter == $ext): echo 'selected'; endif; ?>>
                                        .<?php echo e(strtoupper($ext)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <a href="<?php echo e(tenant_route('school.admin.plans.index', $school)); ?>"
                           class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                            <?php echo e(__('messages.actions.view_all')); ?> →
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.plans.plan_title')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.plans.plan_type')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.teacher')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.uploaded')); ?></th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider no-print"><?php echo e(__('messages.actions.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $planFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shadow-sm">
                                            <i class="ri-calendar-line text-purple-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-sm truncate" title="<?php echo e($file->title); ?>">
                                            <?php echo e(Str::limit($file->title, 40)); ?>

                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                .<?php echo e(strtoupper($extension)); ?>

                                            </span>
                                            <span class="text-xs text-gray-500"><?php echo e(number_format($file->file_size / 1048576, 2)); ?> MB</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 shadow-sm">
                                    <i class="ri-calendar-check-line mr-1"></i>
                                    <?php echo e($file->submission_type === 'supervisor_upload'
                                        ? __('messages.files.supervisor_resource')
                                        : __('messages.files.submission_types.' . $file->submission_type)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-sm font-semibold text-gray-900"><?php echo e($file->user->name ?? __('messages.users.unknown')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(__('messages.users.teacher')); ?></div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-sm text-gray-900 font-medium"><?php echo e($file->created_at->format('M d, Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($file->created_at->diffForHumans()); ?></div>
                            </td>
                            <td class="px-8 py-6 no-print">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if($canPreview): ?>
                                        <a href="<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>"
                                           target="_blank"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                           title="<?php echo e(__('messages.actions.preview')); ?>">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-eye-off-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>

                                    <a href="<?php echo e(tenant_route('school.admin.file-browser.download', [$school, $file])); ?>"
                                       class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                       title="<?php echo e(__('messages.actions.download')); ?>">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>

                                    <?php if($canPreview): ?>
                                        <button onclick="printFile('<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>', '<?php echo e($file->title); ?>', '<?php echo e($file->user->name ?? __('messages.users.unknown')); ?>', '<?php echo e($file->created_at->format('M d, Y')); ?>')"
                                                class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                title="<?php echo e(__('messages.actions.print')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="ri-calendar-line text-2xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        
                                        <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.dashboard.empty.plans_title')); ?></h3>
                                        <p class="text-gray-500"><?php echo e(__('messages.dashboard.empty.plans_subtitle')); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($planFiles->hasPages()): ?>
                <div class="px-8 py-4 border-t border-gray-100 no-print">
                    <?php echo e($planFiles->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="ri-user-star-line mr-2 text-green-600"></i>
                            <?php echo e(__('messages.dashboard.supervisor_uploads')); ?>

                            <span class="ml-2 text-sm font-normal text-gray-500">(<?php echo e(__('messages.dashboard.last_72_hours')); ?>)</span>
                        </h2>
                        <p class="text-gray-600 mt-1"><?php echo e(__('messages.dashboard.supervisor_uploads_description')); ?></p>
                    </div>
                    <div class="flex items-center gap-3 no-print">
                        <?php if($supervisorExtensions->count() > 0): ?>
                            <select onchange="filterByExtension('supervisor_ext', this.value)"
                                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500">
                                <option value=""><?php echo e(__('messages.files.all_types')); ?></option>
                                <?php $__currentLoopData = $supervisorExtensions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ext): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <option value="<?php echo e($ext); ?>" <?php if($supervisorExtFilter == $ext): echo 'selected'; endif; ?>>
                                        .<?php echo e(strtoupper($ext)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <a href="<?php echo e(tenant_route('school.admin.file-browser.index', $school)); ?>?role=supervisor"
                           class="text-sm text-green-600 hover:text-green-800 font-medium">
                            <?php echo e(__('messages.actions.view_all')); ?> →
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.file_title')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.content_type')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.users.supervisor')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.labels.subject')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider"><?php echo e(__('messages.files.uploaded')); ?></th>
                        <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider no-print"><?php echo e(__('messages.actions.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $supervisorFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                            $canPreview = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp', 'txt']);
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shadow-sm">
                                            <i class="ri-file-upload-line text-green-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-sm truncate" title="<?php echo e($file->title); ?>">
                                            <?php echo e(Str::limit($file->title, 40)); ?>

                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                .<?php echo e(strtoupper($extension)); ?>

                                            </span>
                                            <span class="text-xs text-gray-500"><?php echo e(number_format($file->file_size / 1048576, 2)); ?> MB</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 shadow-sm">
                                    <i class="ri-file-list-3-line mr-1"></i>
                                    <?php echo e($file->submission_type === 'supervisor_upload'
                                        ? __('messages.files.supervisor_resource')
                                        : ($file->submission_type
                                            ? __('messages.files.submission_types.' . $file->submission_type)
                                            : __('messages.files.general_resource'))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-sm font-semibold text-gray-900"><?php echo e($file->user->name ?? __('messages.users.unknown')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e(__('messages.users.supervisor')); ?></div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-sm text-gray-900 font-semibold"><?php echo e($file->subject_name ?? __('messages.subjects.not_specified')); ?></div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-sm text-gray-900 font-medium"><?php echo e($file->created_at->format('M d, Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($file->created_at->diffForHumans()); ?></div>
                            </td>
                            <td class="px-8 py-6 no-print">
                                <div class="flex items-center justify-center space-x-2">
                                    <?php if($canPreview): ?>
                                        <a href="<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>"
                                           target="_blank"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                           title="<?php echo e(__('messages.actions.preview')); ?>">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-eye-off-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>

                                    <a href="<?php echo e(tenant_route('school.admin.file-browser.download', [$school, $file])); ?>"
                                       class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-all duration-150"
                                       title="<?php echo e(__('messages.actions.download')); ?>">
                                        <i class="ri-download-line text-lg"></i>
                                    </a>

                                    <?php if($canPreview): ?>
                                        <button onclick="printFile('<?php echo e(tenant_route('school.admin.file-browser.preview', [$school, $file])); ?>', '<?php echo e($file->title); ?>', '<?php echo e($file->user->name ?? __('messages.users.unknown')); ?>', '<?php echo e($file->created_at->format('M d, Y')); ?>')"
                                                class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-all duration-150"
                                                title="<?php echo e(__('messages.actions.print')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php else: ?>
                                        <button disabled class="p-2 text-gray-400 rounded-lg cursor-not-allowed action-disabled" title="<?php echo e(__('messages.files.cannot_preview_browser')); ?>">
                                            <i class="ri-printer-line text-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="ri-user-star-line text-2xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        
                                        <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.dashboard.empty.supervisor_title')); ?></h3>
                                        <p class="text-gray-500"><?php echo e(__('messages.dashboard.empty.supervisor_subtitle')); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($supervisorFiles->hasPages()): ?>
                <div class="px-8 py-4 border-t border-gray-100 no-print">
                    <?php echo e($supervisorFiles->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php echo $__env->make('components.pwa-install-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function printFile(previewUrl, title, uploadedBy, uploadDate) {
            const printWindow = window.open(previewUrl, '_blank', 'width=900,height=700');
            if (!printWindow) {
                alert('<?php echo e(__('messages.ui.allow_popups_print')); ?>');
                return;
            }
            printWindow.addEventListener('load', function() {
                setTimeout(() => {
                    printWindow.print();
                }, 800);
            });
        }

        function filterByExtension(paramName, value) {
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(paramName, value);
            } else {
                url.searchParams.delete(paramName);
            }
            url.searchParams.delete('academic_page');
            url.searchParams.delete('plans_page');
            url.searchParams.delete('supervisor_page');
            window.location.href = url.toString();
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\school\admin\dashboard.blade.php ENDPATH**/ ?>