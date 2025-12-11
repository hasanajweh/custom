<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('messages.errors.419.title')); ?> - <?php echo e(__('messages.app.name')); ?></title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .clock-animation { animation: tick 1s ease-in-out infinite; }
        @keyframes tick {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(10deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-600 leading-none">419</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-24 h-24 md:w-32 md:h-32 text-indigo-300 clock-animation" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?php echo e(__('messages.errors.419.heading')); ?></h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto"><?php echo e(__('messages.errors.419.message')); ?></p>
    <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        <?php echo e(__('messages.errors.419.action')); ?>

    </a>
</div>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\errors\419.blade.php ENDPATH**/ ?>