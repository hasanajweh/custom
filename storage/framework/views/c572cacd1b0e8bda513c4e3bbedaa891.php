<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" class="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', config('app.name', 'Scholder')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="flex justify-end p-4">
        <form id="lang-switcher" method="POST">
            <?php echo csrf_field(); ?>
            <button type="button" onclick="switchLocale('<?php echo e(app()->getLocale() === 'ar' ? 'en' : 'ar'); ?>')" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
                <?php echo e(app()->getLocale() === 'ar' ? __('messages.language.english') : __('messages.language.arabic')); ?>

            </button>
        </form>
    </div>

    <script>
    function switchLocale(locale) {
        fetch("<?php echo e(route('locale.update')); ?>", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('#lang-switcher input[name=_token]').value,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ locale: locale }),
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Wait a moment to ensure session is saved
            setTimeout(() => {
                window.location.reload();
            }, 100);
        })
        .catch(error => {
            console.error('Language switch error:', error);
            setTimeout(() => {
                window.location.reload();
            }, 100);
        });
    }
    </script>

    <?php echo $__env->make('layouts.partials.service-worker-cleanup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\layouts\guest.blade.php ENDPATH**/ ?>