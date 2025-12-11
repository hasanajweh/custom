<?php if (isset($component)) { $__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.guest-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700 p-6"
         dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">

        <?php
            $networkModel = $network instanceof \App\Models\Network
                ? $network
                : \App\Models\Network::where('slug', $network)->first();

            $networkSlug = $networkModel?->slug ?? $network;
            $networkName = $networkModel?->name ?? $network;
        ?>

        <div class="bg-white shadow-2xl rounded-2xl w-full max-w-3xl grid md:grid-cols-2 overflow-hidden">

            <!-- LEFT SIDE -->
            <div class="bg-gradient-to-br from-indigo-700 to-purple-700 text-white p-8 flex flex-col justify-center">
                <h1 class="text-3xl font-bold mb-2"><?php echo e($networkName); ?></h1>
                <p class="text-white/80 mb-6"><?php echo app('translator')->get('messages.network_overview'); ?></p>

                <div class="text-sm text-white/80 space-y-2">
                    <p><?php echo app('translator')->get('messages.app.name'); ?> — <?php echo e(__('messages.app.tagline')); ?></p>
                    <p><?php echo app('translator')->get('messages.plan'); ?>: <?php echo e(__('messages.branches_plan')); ?></p>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="p-8">

                <!-- LANGUAGE SWITCHER -->
                <div class="flex justify-end mb-4">
                    <form action="<?php echo e(route('locale.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="locale"
                               value="<?php echo e(app()->getLocale() === 'ar' ? 'en' : 'ar'); ?>">
                        <button type="submit" class="lang-toggle-btn">
                            <?php echo e(app()->getLocale() === 'ar' ? __('messages.language.english') : __('messages.language.arabic')); ?>

                        </button>
                    </form>
                </div>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <?php echo app('translator')->get('messages.log_in'); ?>
                    <span class="text-sm text-gray-500">(<?php echo app('translator')->get('messages.main_admin_label'); ?>)</span>
                </h2>

                <!-- LOGIN FORM -->
                <form method="POST"
                      action="<?php echo e(route('main-admin.login', ['network' => $networkSlug])); ?>"
                      class="space-y-4">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                            <?php echo app('translator')->get('messages.email'); ?>
                        </label>
                        <input id="email" type="email" name="email" required autofocus
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="password">
                            <?php echo app('translator')->get('messages.password'); ?>
                        </label>
                        <input id="password" type="password" name="password" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="checkbox" name="remember"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="mr-2"><?php echo app('translator')->get('messages.remember_me'); ?></span>
                        </label>

                        <?php if(Route::has('password.request')): ?>
                            <a class="text-sm text-indigo-600 hover:text-indigo-500"
                               href="<?php echo e(route('password.request')); ?>">
                                <?php echo app('translator')->get('messages.forgot_password'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
                            <?php echo e($errors->first()); ?>

                        </div>
                    <?php endif; ?>

                    <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        <?php echo app('translator')->get('messages.log_in'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a)): ?>
<?php $attributes = $__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a; ?>
<?php unset($__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a)): ?>
<?php $component = $__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a; ?>
<?php unset($__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a); ?>
<?php endif; ?>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\main-admin\login.blade.php ENDPATH**/ ?>