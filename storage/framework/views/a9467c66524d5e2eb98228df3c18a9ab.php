<?php $__env->startSection('title', __('messages.main_admin.subjects_grades.title')); ?>

<?php $__env->startPush('styles'); ?>
    <?php if (! $__env->hasRenderedOnce('f7b9c303-4db7-467d-9f5f-b580ec7752c7')): $__env->markAsRenderedOnce('f7b9c303-4db7-467d-9f5f-b580ec7752c7'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
        <style>
            .ts-wrapper.multi .ts-control { min-height: 2.75rem; border-radius: 0.75rem; padding-inline: 0.5rem; }
            .ts-wrapper.multi .ts-control .item { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
            .ts-wrapper.multi .ts-control input { color: #312e81; }
            .ts-dropdown { border-radius: 0.75rem; }
        </style>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold"><?php echo e(__('messages.main_admin.subjects_grades.heading')); ?></h1>
            <p class="text-gray-600"><?php echo e(__('messages.main_admin.subjects_grades.subtitle')); ?></p>
        </div>
    </div>

    <?php if(session('status')): ?>
        <div class="rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside space-y-1 text-sm">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Enhanced Network Statistics Dashboard -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-xl mb-6">
        <h2 class="text-xl font-bold mb-4"><?php echo e(__('messages.dashboard.statistics')); ?></h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.main_admin.users.subjects_label')); ?></p>
                <p class="text-2xl font-bold"><?php echo e($networkStats['total_subjects']); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.main_admin.users.grades_label')); ?></p>
                <p class="text-2xl font-bold"><?php echo e($networkStats['total_grades']); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.branches')); ?></p>
                <p class="text-2xl font-bold"><?php echo e($networkStats['total_branches']); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.files.total_files')); ?></p>
                <p class="text-2xl font-bold"><?php echo e(number_format($networkStats['total_files_network'])); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.dashboard.total_downloads')); ?></p>
                <p class="text-2xl font-bold"><?php echo e(number_format($networkStats['total_downloads_network'])); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.main_admin.users.subjects_label')); ?> <?php echo e(__('messages.status.active')); ?></p>
                <p class="text-2xl font-bold"><?php echo e($networkStats['subjects_with_files']); ?></p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-xs text-indigo-100 mb-1"><?php echo e(__('messages.main_admin.users.grades_label')); ?> <?php echo e(__('messages.status.active')); ?></p>
                <p class="text-2xl font-bold"><?php echo e($networkStats['grades_with_files']); ?></p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg p-6 space-y-5 border border-indigo-50">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold"><?php echo e(__('messages.main_admin.subjects_grades.create_title')); ?></h2>
                    <p class="text-sm text-gray-600"><?php echo e(__('messages.main_admin.subjects_grades.assign')); ?></p>
                </div>
                <span class="text-xs px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full"><?php echo e($branches->count()); ?> <?php echo e(__('messages.labels.schools')); ?></span>
            </div>
            <form method="post" action="<?php echo e(route('main-admin.subjects-grades.store', ['network' => $network->slug])); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="space-y-2">
                    <label class="text-sm text-gray-600 block"><?php echo e(__('messages.main_admin.subjects_grades.type')); ?></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center justify-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-500 bg-gray-50">
                            <input type="radio" name="type" value="subject" class="text-indigo-600" checked>
                            <span class="font-medium"><?php echo e(__('messages.main_admin.users.subjects_label')); ?></span>
                        </label>
                        <label class="flex items-center justify-center gap-2 border rounded-lg px-3 py-2 cursor-pointer hover:border-indigo-500 bg-gray-50">
                            <input type="radio" name="type" value="grade" class="text-indigo-600">
                            <span class="font-medium"><?php echo e(__('messages.main_admin.users.grades_label')); ?></span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-gray-600 block"><?php echo e(__('messages.labels.name')); ?></label>
                    <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-indigo-500" placeholder="<?php echo e(__('messages.labels.name')); ?>" required>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm text-gray-600"><?php echo e(__('messages.main_admin.subjects_grades.assign')); ?></label>
                        <div class="space-x-2 text-xs">
                            <button type="button" class="text-indigo-700 font-semibold" data-select-all><?php echo e(__('messages.actions.select_all')); ?></button>
                            <span class="text-gray-300">|</span>
                            <button type="button" class="text-gray-600" data-clear-all><?php echo e(__('messages.actions.deselect_all')); ?></button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500"><?php echo e(__('messages.main_admin.subjects_grades.instructions')); ?></p>
                    <select name="branches[]" multiple class="tom-select w-full" data-placeholder="<?php echo e(__('messages.main_admin.subjects_grades.assign')); ?>">
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition"><?php echo e(__('messages.actions.save')); ?></button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4 border border-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold"><?php echo e(__('messages.main_admin.subjects_grades.existing_subjects')); ?></h2>
                    <span class="text-xs text-gray-500"><?php echo e($subjects->count()); ?> <?php echo e(__('messages.main_admin.users.subjects_label')); ?></span>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $subjectsAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analytics): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php ($subject = $analytics['subject']); ?>
                        <div class="border rounded-2xl p-4 space-y-3 bg-gradient-to-br from-white to-indigo-50/30 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Analytics Header -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div class="bg-blue-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.files.total_files')); ?></p>
                                    <p class="text-lg font-bold text-blue-700"><?php echo e(number_format($analytics['files_count'])); ?></p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.users.teachers')); ?></p>
                                    <p class="text-lg font-bold text-purple-700"><?php echo e($analytics['teachers_count']); ?></p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.dashboard.total_downloads')); ?></p>
                                    <p class="text-lg font-bold text-green-700"><?php echo e(number_format($analytics['downloads_count'])); ?></p>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.dashboard.this_week')); ?></p>
                                    <p class="text-lg font-bold text-orange-700"><?php echo e($analytics['this_week_files']); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="space-y-1 w-full">
                                    <p class="text-sm text-gray-500"><?php echo e(__('messages.labels.name')); ?></p>
                                    <form action="<?php echo e(route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'subject', 'id' => $subject->id])); ?>" method="post" class="space-y-3">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('put'); ?>
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="<?php echo e($subject->name); ?>" class="border rounded-xl p-2.5 w-full md:flex-1 focus:ring-2 focus:ring-indigo-500 bg-white shadow-inner" placeholder="<?php echo e(__('messages.labels.name')); ?>">
                                            <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition"><?php echo e(__('messages.actions.update')); ?></button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span><?php echo e(__('messages.main_admin.subjects_grades.assign')); ?></span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all><?php echo e(__('messages.actions.select_all')); ?></button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all><?php echo e(__('messages.actions.deselect_all')); ?></button>
                                                </div>
                                            </div>
                                            <select name="branches[]" multiple class="tom-select w-full" data-placeholder="<?php echo e(__('messages.main_admin.subjects_grades.assign')); ?>">
                                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($branch->id); ?>" <?php if($subject->schools->pluck('id')->contains($branch->id)): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <form action="<?php echo e(route('main-admin.subjects-grades.destroy', ['network' => $network->slug, 'type' => 'subject', 'id' => $subject->id])); ?>" method="post" onsubmit="return confirm('<?php echo e(__('messages.main_admin.common.confirm_archive')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('delete'); ?>
                                    <button class="text-red-600 text-sm hover:underline"><?php echo e(__('messages.actions.archive')); ?></button>
                                </form>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full font-medium">
                                    <?php echo e($analytics['assigned_schools_count']); ?> <?php echo e(__('messages.branches')); ?>

                                </span>
                                <?php $__empty_2 = true; $__currentLoopData = $subject->schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full"><?php echo e($school->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-gray-500 text-xs"><?php echo e(__('messages.main_admin.subjects_grades.unassigned')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500"><?php echo e(__('messages.main_admin.subjects_grades.no_subjects')); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4 border border-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold"><?php echo e(__('messages.main_admin.subjects_grades.existing_grades')); ?></h2>
                    <span class="text-xs text-gray-500"><?php echo e($grades->count()); ?> <?php echo e(__('messages.main_admin.users.grades_label')); ?></span>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $gradesAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analytics): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php ($grade = $analytics['grade']); ?>
                        <div class="border rounded-2xl p-4 space-y-3 bg-gradient-to-br from-white to-emerald-50/30 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Analytics Header -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <div class="bg-blue-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.files.total_files')); ?></p>
                                    <p class="text-lg font-bold text-blue-700"><?php echo e(number_format($analytics['files_count'])); ?></p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.users.teachers')); ?></p>
                                    <p class="text-lg font-bold text-purple-700"><?php echo e($analytics['teachers_count']); ?></p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.dashboard.total_downloads')); ?></p>
                                    <p class="text-lg font-bold text-green-700"><?php echo e(number_format($analytics['downloads_count'])); ?></p>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-2 text-center">
                                    <p class="text-xs text-gray-600"><?php echo e(__('messages.dashboard.this_week')); ?></p>
                                    <p class="text-lg font-bold text-orange-700"><?php echo e($analytics['this_week_files']); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="space-y-1 w-full">
                                    <p class="text-sm text-gray-500"><?php echo e(__('messages.labels.name')); ?></p>
                                    <form action="<?php echo e(route('main-admin.subjects-grades.update', ['network' => $network->slug, 'type' => 'grade', 'id' => $grade->id])); ?>" method="post" class="space-y-3">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('put'); ?>
                                        <div class="flex flex-col md:flex-row md:items-center gap-3">
                                            <input type="text" name="name" value="<?php echo e($grade->name); ?>" class="border rounded-xl p-2.5 w-full md:flex-1 focus:ring-2 focus:ring-indigo-500 bg-white shadow-inner" placeholder="<?php echo e(__('messages.labels.name')); ?>">
                                            <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition"><?php echo e(__('messages.actions.update')); ?></button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <span><?php echo e(__('messages.main_admin.subjects_grades.assign')); ?></span>
                                                <div class="space-x-2 text-xs">
                                                    <button type="button" class="text-indigo-700 font-semibold" data-select-all><?php echo e(__('messages.actions.select_all')); ?></button>
                                                    <span class="text-gray-300">|</span>
                                                    <button type="button" class="text-gray-600" data-clear-all><?php echo e(__('messages.actions.deselect_all')); ?></button>
                                                </div>
                                            </div>
                                            <select name="branches[]" multiple class="tom-select w-full" data-placeholder="<?php echo e(__('messages.main_admin.subjects_grades.assign')); ?>">
                                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($branch->id); ?>" <?php if($grade->schools->pluck('id')->contains($branch->id)): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <form action="<?php echo e(route('main-admin.subjects-grades.destroy', ['network' => $network->slug, 'type' => 'grade', 'id' => $grade->id])); ?>" method="post" onsubmit="return confirm('<?php echo e(__('messages.main_admin.common.confirm_archive')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('delete'); ?>
                                    <button class="text-red-600 text-sm hover:underline"><?php echo e(__('messages.actions.archive')); ?></button>
                                </form>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full font-medium">
                                    <?php echo e($analytics['assigned_schools_count']); ?> <?php echo e(__('messages.branches')); ?>

                                </span>
                                <?php $__empty_2 = true; $__currentLoopData = $grade->schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full"><?php echo e($school->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-gray-500 text-xs"><?php echo e(__('messages.main_admin.subjects_grades.unassigned')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500"><?php echo e(__('messages.main_admin.subjects_grades.no_grades')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <?php if (! $__env->hasRenderedOnce('ffceedf8-d195-415c-9c49-4b2fffcae47c')): $__env->markAsRenderedOnce('ffceedf8-d195-415c-9c49-4b2fffcae47c'); ?>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isRtl = document.documentElement.dir === 'rtl';

            document.querySelectorAll('.tom-select').forEach(select => {
                const tom = new TomSelect(select, {
                    plugins: ['remove_button'],
                    persist: false,
                    create: false,
                    maxItems: null,
                    allowEmptyOption: true,
                    placeholder: select.dataset.placeholder || '',
                    render: {
                        option: function(data, escape) {
                            return `<div class="py-2 px-3 flex items-center gap-2">${escape(data.text)}</div>`;
                        }
                    },
                });

                const container = select.closest('form');
                const selectAll = container?.querySelector('[data-select-all]');
                const clearAll = container?.querySelector('[data-clear-all]');

                selectAll?.addEventListener('click', () => {
                    tom.setValue(tom.options ? Object.keys(tom.options) : []);
                });

                clearAll?.addEventListener('click', () => tom.clear());

                if (isRtl) {
                    tom.control_input?.setAttribute('dir', 'rtl');
                    tom.dropdown?.setAttribute('dir', 'rtl');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.network', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\subjects_grades\index.blade.php ENDPATH**/ ?>