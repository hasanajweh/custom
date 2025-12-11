<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'aJw')); ?> - Super Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-brand-background">
    <?php echo $__env->make('layouts.superadmin-navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="lg:pl-72 flex flex-col flex-1">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-brand-border lg:border-none">
            <button @click.stop="sidebarOpen = !sidebarOpen" type="button" class="px-4 border-r border-brand-border text-gray-500 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            <div class="flex-1 px-4 flex justify-end sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8"></div>
        </div>

        <?php if(isset($header)): ?>
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <main class="flex-1 pb-8">
            <?php echo e($slot); ?>

        </main>
    </div>
</div>
<?php if (isset($component)) { $__componentOriginaldba15f4c6f7b633bd9ca9555cfd29361 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldba15f4c6f7b633bd9ca9555cfd29361 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.session-toasts','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('session-toasts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldba15f4c6f7b633bd9ca9555cfd29361)): ?>
<?php $attributes = $__attributesOriginaldba15f4c6f7b633bd9ca9555cfd29361; ?>
<?php unset($__attributesOriginaldba15f4c6f7b633bd9ca9555cfd29361); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldba15f4c6f7b633bd9ca9555cfd29361)): ?>
<?php $component = $__componentOriginaldba15f4c6f7b633bd9ca9555cfd29361; ?>
<?php unset($__componentOriginaldba15f4c6f7b633bd9ca9555cfd29361); ?>
<?php endif; ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\components\superadmin-layout.blade.php ENDPATH**/ ?>