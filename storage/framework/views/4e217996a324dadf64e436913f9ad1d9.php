<?php $__env->startSection('page-title', 'Create New School'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-medium text-red-300 mb-2">Please fix these errors:</h3>
                <ul class="text-sm text-red-200 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">Create New School</h1>
            <p class="text-gray-400 mt-2">Fill in all details to create a new school with pending subscription</p>
        </div>

        <form method="POST" action="<?php echo e(route('superadmin.schools.store')); ?>" id="createSchoolForm">
            <?php echo csrf_field(); ?>

            <!-- Network Details -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 0v10l9 4 9-4V7" />
                    </svg>
                    Network
                </h2>

                <div class="space-y-4" id="networkSection">
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2 text-gray-200">
                            <input type="radio" name="network_mode" value="existing"
                                   <?php echo e(old('network_mode', $networks->isNotEmpty() ? 'existing' : 'new') === 'existing' ? 'checked' : ''); ?>

                                   <?php echo e($networks->isEmpty() ? 'disabled' : ''); ?>

                                   class="text-indigo-500 border-gray-600 bg-gray-800 focus:ring-indigo-500">
                            <span>Use existing network</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-200">
                            <input type="radio" name="network_mode" value="new"
                                   <?php echo e(old('network_mode', $networks->isNotEmpty() ? 'existing' : 'new') === 'new' ? 'checked' : ''); ?>

                                   class="text-indigo-500 border-gray-600 bg-gray-800 focus:ring-indigo-500">
                            <span>Create new network</span>
                        </label>
                    </div>

                    <div class="existing-network space-y-3 <?php echo e(old('network_mode', $networks->isNotEmpty() ? 'existing' : 'new') === 'existing' ? '' : 'hidden'); ?>">
                        <label for="network_id" class="block text-sm font-medium text-gray-300">Select Network</label>
                        <select name="network_id" id="network_id"
                                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Choose network --</option>
                            <?php $__currentLoopData = $networks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $network): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($network->id); ?>" <?php echo e(old('network_id') == $network->id ? 'selected' : ''); ?>>
                                    <?php echo e($network->name); ?> (<?php echo e($network->slug); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['network_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="new-network space-y-4 <?php echo e(old('network_mode', $networks->isNotEmpty() ? 'existing' : 'new') === 'new' ? '' : 'hidden'); ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="network_name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Network Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="network_name" id="network_name" value="<?php echo e(old('network_name')); ?>"
                                       placeholder="Latin Schools"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                <?php $__errorArgs = ['network_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="network_slug" class="block text-sm font-medium text-gray-300 mb-2">
                                    Network Slug <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="network_slug" id="network_slug" value="<?php echo e(old('network_slug')); ?>"
                                       placeholder="latin"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">Used in the main admin login URL.</p>
                                <?php $__errorArgs = ['network_slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="main_admin_name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Main Admin Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="main_admin_name" id="main_admin_name" value="<?php echo e(old('main_admin_name')); ?>"
                                       placeholder="Network Owner"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                <?php $__errorArgs = ['main_admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="main_admin_email" class="block text-sm font-medium text-gray-300 mb-2">
                                    Main Admin Email <span class="text-red-400">*</span>
                                </label>
                                <input type="email" name="main_admin_email" id="main_admin_email" value="<?php echo e(old('main_admin_email')); ?>"
                                       placeholder="owner@network.com"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                <?php $__errorArgs = ['main_admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="main_admin_password" class="block text-sm font-medium text-gray-300 mb-2">
                                    Main Admin Password <span class="text-red-400">*</span>
                                </label>
                                <input type="password" name="main_admin_password" id="main_admin_password"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                                <?php $__errorArgs = ['main_admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label for="main_admin_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                    Confirm Password <span class="text-red-400">*</span>
                                </label>
                                <input type="password" name="main_admin_password_confirmation" id="main_admin_password_confirmation"
                                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Details -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    School Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label for="school_name" class="block text-sm font-medium text-gray-300 mb-2">
                            School Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="school_name"
                               value="<?php echo e(old('name')); ?>"
                               required
                               placeholder="Lincoln High School"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- URL Slug -->
                    <div>
                        <label for="school_slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="slug"
                               id="school_slug"
                               value="<?php echo e(old('slug')); ?>"
                               required
                               placeholder="lincoln-high"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Auto-generated from school name</p>
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Plan Details -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Branches Plan (auto-assigned)
                </h2>
                <div class="p-4 border border-gray-700 rounded-lg bg-gray-800/60">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-white"><?php echo e($plan->name); ?></h3>
                            <p class="text-sm text-gray-400"><?php echo e($plan->storage_limit_in_gb); ?> GB Storage</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-green-900 text-green-200 text-xs">Included</span>
                    </div>
                    <p class="text-sm text-gray-300">This school will automatically use the Branches Plan for unlimited branch creation.</p>
                </div>
            </div>

            <!-- Admin User Credentials -->
            <div class="glass rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    School Admin Account
                </h2>

                <div class="space-y-6">
                    <!-- Admin Name -->
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Admin Full Name <span class="text-red-400">*</span>
                        </label>
                        <input type="text"
                               name="admin_name"
                               id="admin_name"
                               value="<?php echo e(old('admin_name')); ?>"
                               required
                               placeholder="John Doe"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Admin Email -->
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Admin Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email"
                               name="admin_email"
                               id="admin_email"
                               value="<?php echo e(old('admin_email')); ?>"
                               required
                               placeholder="admin@school.com"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <?php $__errorArgs = ['admin_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="admin_password" class="block text-sm font-medium text-gray-300 mb-2">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password"
                                   name="admin_password"
                                   id="admin_password"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                            <?php $__errorArgs = ['admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                Confirm Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password"
                                   name="admin_password_confirmation"
                                   id="admin_password_confirmation"
                                   required
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-blue-900 bg-opacity-20 border border-blue-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-300">What happens when you submit?</h4>
                        <ul class="text-sm text-blue-200 mt-2 space-y-1">
                            <li>• School will be created as <strong>inactive</strong></li>
                            <li>• Admin user account will be created</li>
                            <li>• Subscription will be created with <strong>pending</strong> status</li>
                            <li>• You can activate it from the Subscriptions page</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4">
                <a href="<?php echo e(route('superadmin.schools.index')); ?>"
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-lg transition flex items-center shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create School
                </button>
            </div>
        </form>
    </div>

    <style>
        .glass {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .plan-option {
            transition: all 0.3s ease;
        }

        .plan-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('school_name');
            const slugInput = document.getElementById('school_slug');
            const networkNameInput = document.getElementById('network_name');
            const networkSlugInput = document.getElementById('network_slug');
            const modeRadios = document.querySelectorAll('input[name="network_mode"]');
            const existingBlock = document.querySelector('.existing-network');
            const newBlock = document.querySelector('.new-network');

            const toSlug = (value) => value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            nameInput.addEventListener('input', function(e) {
                slugInput.value = toSlug(e.target.value);
            });

            networkNameInput?.addEventListener('input', function(e) {
                networkSlugInput.value = toSlug(e.target.value);
            });

            modeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const isExisting = this.value === 'existing';
                    existingBlock.classList.toggle('hidden', !isExisting);
                    newBlock.classList.toggle('hidden', isExisting);
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\schools\create.blade.php ENDPATH**/ ?>