<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('messages.errors.422.title', [], 'Unprocessable Entity')); ?> - <?php echo e(__('messages.app.name')); ?></title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .warning-animation { animation: warning 1s ease-in-out infinite; }
        @keyframes warning {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-3deg); }
            75% { transform: rotate(3deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-orange-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-600 leading-none">422</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-24 h-24 md:w-32 md:h-32 text-yellow-300 warning-animation" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?php echo e(__('messages.errors.422.heading', [], 'Validation Error')); ?></h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto"><?php echo e(__('messages.errors.422.message', [], 'The request could not be processed due to validation errors.')); ?></p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <?php echo e(__('messages.errors.go_back')); ?>

        </a>
        <a href="/" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <?php echo e(__('messages.errors.go_home')); ?>

        </a>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\errors\422.blade.php ENDPATH**/ ?>