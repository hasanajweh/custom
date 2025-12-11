<?php $__env->startSection('page-title', 'Platform Settings'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">Platform Settings</h1>
            <p class="mt-2 text-gray-400">Configure global settings and defaults for your platform</p>
        </div>

        <!-- Info Banner -->
        <div class="bg-blue-900 bg-opacity-20 border border-blue-500 border-opacity-30 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-blue-300">About Platform Settings</h4>
                    <p class="text-sm text-blue-200 mt-1">
                        These settings control the default behavior and configuration for your entire platform.
                        Changes here will affect all schools and new subscriptions.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('superadmin.settings.update')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- General Settings -->
            <div class="glass rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-indigo-900 bg-opacity-30 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">General Settings</h2>
                        <p class="text-sm text-gray-400">Basic platform configuration</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-300 mb-2">
                            Platform Name
                        </label>
                        <input type="text"
                               name="site_name"
                               id="site_name"
                               value="<?php echo e(old('site_name', $settings['site_name'] ?? 'aJw')); ?>"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">
                            Used in emails, page titles, and throughout the platform
                        </p>
                    </div>

                    <div>
                        <label for="default_storage_limit" class="block text-sm font-medium text-gray-300 mb-2">
                            Default Storage Limit (GB)
                        </label>
                        <input type="number"
                               name="default_storage_limit"
                               id="default_storage_limit"
                               value="<?php echo e(old('default_storage_limit', $settings['default_storage_limit'] ?? 10)); ?>"
                               min="1"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">
                            Default storage limit when creating new plans (can be overridden per plan)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="glass rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-purple-900 bg-opacity-30 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Email Configuration</h2>
                        <p class="text-sm text-gray-400">Email addresses for system notifications</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="support_email" class="block text-sm font-medium text-gray-300 mb-2">
                            Support Email Address
                        </label>
                        <input type="email"
                               name="support_email"
                               id="support_email"
                               value="<?php echo e(old('support_email', $settings['support_email'] ?? '')); ?>"
                               placeholder="support@aJw.com"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">
                            Users will be directed here for support inquiries
                        </p>
                    </div>

                    <div>
                        <label for="from_email" class="block text-sm font-medium text-gray-300 mb-2">
                            System From Email
                        </label>
                        <input type="email"
                               name="from_email"
                               id="from_email"
                               value="<?php echo e(old('from_email', $settings['from_email'] ?? '')); ?>"
                               placeholder="noreply@aJw.com"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">
                            All automated emails will be sent from this address
                        </p>
                    </div>
                </div>
            </div>

            <!-- Billing Settings -->
            <div class="glass rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-green-900 bg-opacity-30 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Billing Configuration</h2>
                        <p class="text-sm text-gray-400">Currency and tax settings</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="currency_symbol" class="block text-sm font-medium text-gray-300 mb-2">
                            Currency Symbol
                        </label>
                        <input type="text"
                               name="currency_symbol"
                               id="currency_symbol"
                               value="<?php echo e(old('currency_symbol', $settings['currency_symbol'] ?? '$')); ?>"
                               placeholder="$"
                               maxlength="3"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">e.g., $, €, £</p>
                    </div>

                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-300 mb-2">
                            Tax Rate (%)
                        </label>
                        <input type="number"
                               name="tax_rate"
                               id="tax_rate"
                               value="<?php echo e(old('tax_rate', $settings['tax_rate'] ?? 0)); ?>"
                               min="0"
                               max="100"
                               step="0.01"
                               placeholder="0.00"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <p class="mt-2 text-sm text-gray-500">Applied to subscriptions</p>
                    </div>
                </div>
            </div>

            <!-- Platform Features -->
            <div class="glass rounded-lg p-6">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-yellow-900 bg-opacity-30 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Platform Features</h2>
                        <p class="text-sm text-gray-400">Control platform-wide feature flags</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center p-4 rounded-lg border border-gray-700 hover:bg-gray-800 transition cursor-pointer">
                        <input type="checkbox"
                               name="allow_registration"
                               value="1"
                               <?php echo e(old('allow_registration', $settings['allow_registration'] ?? true) ? 'checked' : ''); ?>

                               class="w-5 h-5 text-indigo-600 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-300">Allow New School Registrations</span>
                            <p class="text-xs text-gray-500 mt-1">Enable or disable new schools from signing up</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 rounded-lg border border-red-700 hover:bg-red-900 hover:bg-opacity-10 transition cursor-pointer">
                        <input type="checkbox"
                               name="maintenance_mode"
                               value="1"
                               <?php echo e(old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : ''); ?>

                               class="w-5 h-5 text-red-600 bg-gray-800 border-gray-600 rounded focus:ring-red-500 focus:ring-2">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-300">Maintenance Mode</span>
                            <p class="text-xs text-gray-500 mt-1">⚠️ Temporarily disable access to all schools for maintenance</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <button type="reset"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                    Reset Changes
                </button>
                <button type="submit"
                        class="btn-glow px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Success Notification -->
    <?php if(session('success')): ?>
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center z-50">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\superadmin\settings\index.blade.php ENDPATH**/ ?>