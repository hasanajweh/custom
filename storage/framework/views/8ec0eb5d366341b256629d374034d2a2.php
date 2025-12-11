<?php $__env->startSection('page-title', 'Create Plan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Create New Plan</h1>
            <p class="mt-2 text-gray-400">Add a new subscription plan to your platform</p>
        </div>

        <!-- Create Form -->
        <form method="POST" action="<?php echo e(route('superadmin.plans.store')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="glass rounded-lg p-6">
                <h2 class="text-lg font-medium text-white mb-6">Plan Details</h2>

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Plan Name
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="<?php echo e(old('name')); ?>"
                               placeholder="e.g., Professional Plan"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                            URL Slug
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               value="<?php echo e(old('slug')); ?>"
                               placeholder="e.g., professional-plan"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-1 text-sm text-gray-500">Will be auto-generated from name if left empty</p>
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="price_monthly" class="block text-sm font-medium text-gray-300 mb-2">
                                Monthly Price ($)
                            </label>
                            <input type="number"
                                   name="price_monthly"
                                   id="price_monthly"
                                   value="<?php echo e(old('price_monthly')); ?>"
                                   step="0.01"
                                   min="0"
                                   placeholder="99.99"
                                   required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <?php $__errorArgs = ['price_monthly'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="price_annually" class="block text-sm font-medium text-gray-300 mb-2">
                                Annual Price ($)
                            </label>
                            <input type="number"
                                   name="price_annually"
                                   id="price_annually"
                                   value="<?php echo e(old('price_annually')); ?>"
                                   step="0.01"
                                   min="0"
                                   placeholder="1079.88"
                                   required
                                   class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <p class="mt-1 text-sm text-gray-500">Typically 10-20% discount from monthly</p>
                            <?php $__errorArgs = ['price_annually'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Storage Limit -->
                    <div>
                        <label for="storage_limit_in_gb" class="block text-sm font-medium text-gray-300 mb-2">
                            Storage Limit (GB)
                        </label>
                        <input type="number"
                               name="storage_limit_in_gb"
                               id="storage_limit_in_gb"
                               value="<?php echo e(old('storage_limit_in_gb', 10)); ?>"
                               min="1"
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <?php $__errorArgs = ['storage_limit_in_gb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Features
                        </label>
                        <div id="features-container" class="space-y-2">
                            <div class="feature-row flex items-center space-x-2">
                                <input type="text"
                                       name="features[]"
                                       placeholder="e.g., Unlimited users"
                                       class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addFeature()" class="mt-2 text-sm text-indigo-400 hover:text-indigo-300">
                            + Add another feature
                        </button>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   checked
                                   class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2">
                            <span class="ml-3 text-sm text-gray-300">Active (available for new subscriptions)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="<?php echo e(route('superadmin.plans.index')); ?>"
                   class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-glow px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                    Create Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const newRow = document.createElement('div');
            newRow.className = 'feature-row flex items-center space-x-2';
            newRow.innerHTML = `
        <input type="text"
               name="features[]"
               placeholder="e.g., Priority support"
               class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
        <button type="button" onclick="removeFeature(this)" class="text-red-400 hover:text-red-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
            container.appendChild(newRow);
        }

        function removeFeature(button) {
            const container = document.getElementById('features-container');
            if (container.children.length > 1) {
                button.closest('.feature-row').remove();
            }
        }

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function(e) {
            const slug = document.getElementById('slug');
            if (!slug.value || slug.value === '') {
                slug.value = e.target.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-');
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\plans\create.blade.php ENDPATH**/ ?>