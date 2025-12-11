<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm text-gray-600 block mb-1"><?php echo app('translator')->get('Name'); ?></label>
        <input type="text" name="name" value="<?php echo e(old('name', $user->name ?? '')); ?>" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1"><?php echo app('translator')->get('Email'); ?></label>
        <input
            type="email"
            name="email"
            value="<?php echo e(old('email', $user->email ?? '')); ?>"
            class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500 <?php echo e(isset($user) ? 'bg-gray-100 cursor-not-allowed' : ''); ?>"
            <?php if(isset($user)): ?> disabled readonly <?php endif; ?>
            required
        >
        <?php if(isset($user)): ?>
            <p class="text-xs text-gray-500 mt-1"><?php echo app('translator')->get('Email cannot be changed once created.'); ?></p>
        <?php endif; ?>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1"><?php echo app('translator')->get('Password'); ?></label>
        <input type="password" name="password" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" <?php if(!isset($user)): ?> required <?php endif; ?>>
        <p class="text-xs text-gray-500 mt-1"><?php echo app('translator')->get('Leave blank to keep current password.'); ?></p>
    </div>
    <div>
        <label class="text-sm text-gray-600 block mb-1"><?php echo app('translator')->get('Confirm password'); ?></label>
        <input type="password" name="password_confirmation" class="w-full border border-indigo-100 rounded-xl p-3 bg-white shadow-inner focus:ring-2 focus:ring-indigo-500" <?php if(!isset($user)): ?> required <?php endif; ?>>
    </div>
    <?php if(isset($user)): ?>
        <div>
            <label class="text-sm text-gray-600 block mb-1"><?php echo app('translator')->get('Active'); ?></label>
            <select name="is_active" class="w-full border border-indigo-100 rounded-xl p-3 bg-white focus:ring-2 focus:ring-indigo-500">
                <option value="1" <?php if(old('is_active', $user->is_active ?? true)): echo 'selected'; endif; ?>><?php echo app('translator')->get('Active'); ?></option>
                <option value="0" <?php if(!old('is_active', $user->is_active ?? true)): echo 'selected'; endif; ?>><?php echo app('translator')->get('Inactive'); ?></option>
            </select>
        </div>
    <?php endif; ?>
</div>

<div class="border-t pt-6 space-y-6 mt-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-700 font-semibold"><?php echo app('translator')->get('Assign branches, roles, subjects, and grades'); ?></p>
            <p class="text-xs text-gray-500"><?php echo app('translator')->get('Craft tailored access with quick search, tagging, and RTL friendly controls.'); ?></p>
        </div>
    </div>
    <div class="grid gap-4">
        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $branchRoles = $assignments[$branch->id]['roles'] ?? [];
                $branchSubjects = $assignments[$branch->id]['subjects'] ?? [];
                $branchGrades = $assignments[$branch->id]['grades'] ?? [];
            ?>
            <div class="bg-white border border-indigo-50 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo e($branch->name); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo e($branch->city); ?></p>
                    </div>
                    <input type="hidden" name="assignments[<?php echo e($branch->id); ?>][school_id]" value="<?php echo e($branch->id); ?>">
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="text-sm text-gray-600 block mb-2"><?php echo app('translator')->get('messages.roles_label'); ?></label>
                        <select name="assignments[<?php echo e($branch->id); ?>][roles][]" multiple class="tom-select w-full" data-placeholder="<?php echo app('translator')->get('Select roles'); ?>">
                            <?php $__currentLoopData = ['admin' => __('Admin'), 'supervisor' => __('Supervisor'), 'teacher' => __('Teacher')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roleKey); ?>" <?php if(in_array($roleKey, $branchRoles)): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-2"><?php echo app('translator')->get('Subjects'); ?></label>
                        <select name="assignments[<?php echo e($branch->id); ?>][subjects][]" multiple class="tom-select w-full" data-placeholder="<?php echo app('translator')->get('Select subjects'); ?>">
                            <?php $__currentLoopData = $branch->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php if(in_array($subject->id, $branchSubjects)): echo 'selected'; endif; ?>><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 block mb-2"><?php echo app('translator')->get('Grades'); ?></label>
                        <select name="assignments[<?php echo e($branch->id); ?>][grades][]" multiple class="tom-select w-full" data-placeholder="<?php echo app('translator')->get('Select grades'); ?>">
                            <?php $__currentLoopData = $branch->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade->id); ?>" <?php if(in_array($grade->id, $branchGrades)): echo 'selected'; endif; ?>><?php echo e($grade->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="flex justify-end pt-4">
    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:from-indigo-700 hover:to-purple-700 transition"><?php echo app('translator')->get('Save'); ?></button>
</div>

<?php $__env->startPush('styles'); ?>
    <?php if (! $__env->hasRenderedOnce('da840ae2-05b0-4dd1-b5dd-c50ba131da3c')): $__env->markAsRenderedOnce('da840ae2-05b0-4dd1-b5dd-c50ba131da3c'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
        <style>
            .ts-wrapper.multi .ts-control { min-height: 2.75rem; border-radius: 0.75rem; padding-inline: 0.5rem; }
            .ts-wrapper.multi .ts-control .item { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
            .ts-wrapper.multi .ts-control input { color: #312e81; }
            .ts-dropdown { border-radius: 0.75rem; }
        </style>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php if (! $__env->hasRenderedOnce('d6286a6d-37de-4ecf-833c-49152de995af')): $__env->markAsRenderedOnce('d6286a6d-37de-4ecf-833c-49152de995af'); ?>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isRtl = document.documentElement.dir === 'rtl';

            document.querySelectorAll('.tom-select').forEach(select => {
                const control = new TomSelect(select, {
                    plugins: ['remove_button'],
                    persist: false,
                    create: false,
                    maxItems: null,
                    allowEmptyOption: true,
                    placeholder: select.dataset.placeholder || '',
                });

                if (isRtl) {
                    control.control_input?.setAttribute('dir', 'rtl');
                    control.dropdown?.setAttribute('dir', 'rtl');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\users\partials\form.blade.php ENDPATH**/ ?>