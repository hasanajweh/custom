<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('messages.errors.413.title', [], 'Payload Too Large')); ?> - <?php echo e(__('messages.app.name')); ?></title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .scale-animation { animation: scaleUpDown 2s ease-in-out infinite; }
        @keyframes scaleUpDown {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-600 leading-none">413</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-24 h-24 md:w-32 md:h-32 text-purple-300 scale-animation" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?php echo e(__('messages.errors.413.heading', [], 'File Too Large')); ?></h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto"><?php echo e(__('messages.errors.413.message', [], 'The file you are trying to upload is too large.')); ?></p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <?php echo e(__('messages.errors.go_back')); ?>

        </a>
        <a href="/" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <?php echo e(__('messages.errors.go_home')); ?>

        </a>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\errors\413.blade.php ENDPATH**/ ?>