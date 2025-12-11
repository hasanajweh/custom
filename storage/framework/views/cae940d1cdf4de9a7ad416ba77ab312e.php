<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" class="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">


<?php if(session()->has('impersonator_id')): ?>
    <div class="relative bg-yellow-400 px-4 py-3 text-sm font-semibold text-yellow-900">
        <div class="text-center">
            <span>You are currently impersonating another user.</span>
            <form method="POST" action="<?php echo e(route('impersonate.leave')); ?>" class="inline-block ml-2">
                <?php echo csrf_field(); ?>
                <button type="submit" class="underline hover:opacity-80">Leave Impersonation</button>
            </form>
        </div>
    </div>
<?php endif; ?>


<div class="min-h-screen bg-brand-background">
    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="lg:pl-72">
        <?php if(isset($header)): ?>
            <header class="bg-brand-secondary shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php echo e($slot); ?>

            </div>
        </main>
    </div>
</div>

<?php echo $__env->make('layouts.partials.service-worker-cleanup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\layouts\admin.blade.php ENDPATH**/ ?>