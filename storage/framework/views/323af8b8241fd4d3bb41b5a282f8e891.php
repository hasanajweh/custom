<?php $__env->startSection('title', __('messages.main_admin.users.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.main_admin.users.heading')); ?></h1>
            <p class="text-gray-600"><?php echo e(__('messages.main_admin.users.subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('main-admin.users.create', ['network' => $network->slug])); ?>" 
           class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-5 py-3 rounded-xl shadow-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 hover:shadow-xl">
            <i class="ri-user-add-line"></i>
            <?php echo e(__('messages.main_admin.users.add')); ?>

        </a>
    </div>

    <!-- Filters -->
    <form method="get" class="bg-white p-6 rounded-2xl shadow-lg border border-indigo-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2"><?php echo e(__('messages.main_admin.users.role')); ?></label>
                <select name="role" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value=""><?php echo e(__('messages.main_admin.common.all')); ?></option>
                    <option value="admin" <?php if(request('role')==='admin'): echo 'selected'; endif; ?>><?php echo e(__('messages.roles.admin')); ?></option>
                    <option value="supervisor" <?php if(request('role')==='supervisor'): echo 'selected'; endif; ?>><?php echo e(__('messages.roles.supervisor')); ?></option>
                    <option value="teacher" <?php if(request('role')==='teacher'): echo 'selected'; endif; ?>><?php echo e(__('messages.roles.teacher')); ?></option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2"><?php echo e(__('messages.labels.status')); ?></label>
                <select name="status" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value=""><?php echo e(__('messages.main_admin.common.all')); ?></option>
                    <option value="active" <?php if(request('status')==='active'): echo 'selected'; endif; ?>><?php echo e(__('messages.status.active')); ?></option>
                    <option value="archived" <?php if(request('status')==='archived'): echo 'selected'; endif; ?>><?php echo e(__('messages.status.archived')); ?></option>
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-2"><?php echo e(__('messages.main_admin.users.branch')); ?></label>
                <select name="branch" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value=""><?php echo e(__('messages.main_admin.common.all')); ?></option>
                    <?php $__currentLoopData = $network->schools ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>" <?php if(request('branch')==$branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-3 rounded-xl shadow hover:from-gray-900 hover:to-black transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="ri-filter-3-line"></i>
                    <?php echo e(__('messages.actions.filter')); ?>

                </button>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.labels.name')); ?></th>
                        <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.labels.email')); ?></th>
                        <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.roles_label')); ?></th>
                        <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.branches')); ?></th>
                        <th class="px-6 py-4 text-<?php echo e(app()->getLocale() === 'ar' ? 'right' : 'left'); ?> text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.labels.status')); ?></th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                        <?php echo e(mb_substr($user->name, 0, 1)); ?>

                                    </div>
                                    <span class="font-semibold text-gray-900"><?php echo e($user->name); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?php echo e($user->email); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $user->schoolRoles->unique('role'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $roleColors = [
                                                'admin' => 'bg-purple-100 text-purple-700',
                                                'supervisor' => 'bg-green-100 text-green-700',
                                                'teacher' => 'bg-blue-100 text-blue-700',
                                            ];
                                        ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($roleColors[$role->role] ?? 'bg-gray-100 text-gray-700'); ?>">
                                            <?php echo e(__('messages.roles.' . $role->role)); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <?php $__currentLoopData = $user->schoolRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="text-sm">
                                            <span class="text-gray-900 font-medium"><?php echo e($role->school?->name); ?></span>
                                            <span class="text-gray-400 mx-1">•</span>
                                            <span class="text-gray-500"><?php echo e(__('messages.roles.' . $role->role)); ?></span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($user->trashed()): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        <i class="ri-archive-line"></i>
                                        <?php echo e(__('messages.status.archived')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        <i class="ri-checkbox-circle-line"></i>
                                        <?php echo e(__('messages.status.active')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('main-admin.users.edit', ['network' => $network->slug, 'user' => $user])); ?>" 
                                       class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="<?php echo e(__('messages.actions.edit')); ?>">
                                        <i class="ri-edit-line text-lg"></i>
                                    </a>
                                    <?php if($user->trashed()): ?>
                                        <form action="<?php echo e(route('main-admin.users.restore', ['network' => $network->slug, 'user' => $user->id])); ?>" method="post" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="<?php echo e(__('messages.actions.restore')); ?>">
                                                <i class="ri-refresh-line text-lg"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('main-admin.users.destroy', ['network' => $network->slug, 'user' => $user])); ?>" method="post" class="inline" onsubmit="return confirm('<?php echo e(__('messages.main_admin.common.confirm_archive')); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('delete'); ?>
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="<?php echo e(__('messages.actions.archive')); ?>">
                                                <i class="ri-archive-line text-lg"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="px-6 py-12 text-center" colspan="6">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="ri-user-search-line text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500"><?php echo e(__('messages.main_admin.users.empty')); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        <?php echo e($users->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.network', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\users\index.blade.php ENDPATH**/ ?>