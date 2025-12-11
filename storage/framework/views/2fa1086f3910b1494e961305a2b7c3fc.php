<?php $__env->startSection('title', __('messages.users.archived.title')); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo e(__('messages.users.archived.heading')); ?></h1>
                <p class="mt-1 text-sm text-gray-600"><?php echo e(__('messages.users.archived.subheading')); ?></p>
            </div>
            <a href="<?php echo e(tenant_route('school.admin.users.index', $school)); ?>"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg">
                <i class="ri-arrow-left-line mr-2"></i>
                <?php echo e(__('messages.users.archived.back')); ?>

            </a>
        </div>

        <!-- Info Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start">
                <i class="ri-information-line text-blue-600 text-xl mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-blue-900"><?php echo e(__('messages.users.archived.about.title')); ?></h3>
                    <p class="text-sm text-blue-700 mt-1">
                        <?php echo e(__('messages.users.archived.about.description')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="<?php echo e(tenant_route('school.admin.users.archived', $school)); ?>">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text"
                                   name="search"
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="<?php echo e(__('messages.users.archived.search_placeholder')); ?>"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                        <i class="ri-search-line mr-2"></i><?php echo e(__('messages.common.search')); ?>

                    </button>
                </div>
            </form>
        </div>

        <!-- Archived Users Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.common.user')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.common.role')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.common.subject')); ?></th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.users.archived.archived_on')); ?></th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider"><?php echo e(__('messages.common.actions')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $archivedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors opacity-75">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br <?php echo e(getAvatarColor($user->name)); ?> flex items-center justify-center text-white font-bold text-lg mr-4 grayscale">
                                        <?php echo e(substr($user->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900"><?php echo e($user->name); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($user->email); ?></div>
                                        <div class="flex items-center mt-1">
                                            <i class="ri-archive-line text-orange-500 text-xs mr-1"></i>
                                            <span class="text-xs text-orange-600">Archived</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-700 border-red-200',
                                        'teacher' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'supervisor' => 'bg-green-100 text-green-700 border-green-200'
                                    ];
                                    $roleIcons = [
                                        'admin' => 'ri-shield-user-line',
                                        'teacher' => 'ri-user-line',
                                        'supervisor' => 'ri-user-star-line'
                                    ];
                                ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border <?php echo e($roleColors[$user->role] ?? 'bg-gray-100 text-gray-700 border-gray-200'); ?>">
                                    <i class="<?php echo e($roleIcons[$user->role] ?? 'ri-user-line'); ?> mr-1"></i>
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($user->role === 'supervisor' && $user->supervisor_subjects): ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-medium">
                                        <i class="ri-book-line mr-1"></i>
                                        <?php echo e($user->supervisor_subjects); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($user->deleted_at->format('M d, Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($user->deleted_at->diffForHumans()); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Restore Button -->
                                    <form method="POST"
                                          action="<?php echo e(tenant_route('school.admin.users.restore', [$school, $user])); ?>"
                                          onsubmit="return confirm('<?php echo e(__('messages.users.archived.restore_confirm')); ?>')"
                                          class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit"
                                                class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors"
                                                title="<?php echo e(__('messages.users.archived.restore')); ?>">
                                            <i class="ri-restart-line text-lg"></i>
                                        </button>
                                    </form>

                                    <!-- Permanent Delete Button -->
                                      <form method="POST"
                                            action="<?php echo e(tenant_route('school.admin.users.force-delete', [$school, $user])); ?>"
                                            onsubmit="return confirm('<?php echo e(__('messages.users.archived.delete_confirm')); ?>')"
                                            class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                                title="<?php echo e(__('messages.users.archived.delete')); ?>">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="ri-archive-line text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium"><?php echo e(__('messages.users.archived.empty.title')); ?></p>
                                    <p class="text-gray-400 text-sm mt-1"><?php echo e(__('messages.users.archived.empty.subtitle')); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($archivedUsers->hasPages()): ?>
                <div class="px-6 py-4 border-t border-gray-100">
                    <?php echo e($archivedUsers->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.school', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\school\admin\users\archived.blade.php ENDPATH**/ ?>