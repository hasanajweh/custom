


<?php $__env->startSection('title', 'Install App - WayUp'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <img src="/WayUp.png" alt="WayUp" class="w-24 h-24 mx-auto mb-4 rounded-2xl">
                <h1 class="text-3xl font-bold mb-2">Install <?php echo e($school->name); ?> App</h1>
                <p class="text-gray-600">Get quick access from your home screen</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- iOS Instructions -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                    <div class="flex items-center mb-4">
                        <i class="ri-apple-fill text-4xl text-gray-900 mr-3"></i>
                        <h2 class="text-2xl font-bold">iPhone/iPad</h2>
                    </div>
                    <ol class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">1</span>
                            <span>Open Safari and navigate to this page</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">2</span>
                            <span>Tap the Share button <i class="ri-share-box-line text-xl"></i> at the bottom</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">3</span>
                            <span>Scroll down and tap "Add to Home Screen"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">4</span>
                            <span>Tap "Add" to install</span>
                        </li>
                    </ol>
                </div>

                <!-- Android Instructions -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                    <div class="flex items-center mb-4">
                        <i class="ri-android-fill text-4xl text-gray-900 mr-3"></i>
                        <h2 class="text-2xl font-bold">Android</h2>
                    </div>
                    <ol class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold mr-3">1</span>
                            <span>Open Chrome and navigate to this page</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold mr-3">2</span>
                            <span>Tap the menu (⋮) in the top right</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold mr-3">3</span>
                            <span>Select "Add to Home screen" or "Install app"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold mr-3">4</span>
                            <span>Tap "Add" to install</span>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="<?php echo e(tenant_route('teacher.files.index', $school)); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg transition-all">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.school', ['school' => $school], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\install-instructions.blade.php ENDPATH**/ ?>