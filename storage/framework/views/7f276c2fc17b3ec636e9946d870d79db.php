<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('messages.errors.403.title', [], 'Access Denied')); ?> - <?php echo e(__('messages.app.name')); ?></title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .coin-animation { animation: coinFlip 2s ease-in-out infinite; }
        @keyframes coinFlip {
            0% { transform: rotateY(0deg); }
            50% { transform: rotateY(180deg); }
            100% { transform: rotateY(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-emerald-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 leading-none">403</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-24 h-24 md:w-32 md:h-32 text-green-300 coin-animation" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.51-1.31c-.562-.649-1.413-1.076-2.353-1.253V5z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?php echo e(__('messages.errors.403.heading', [], 'Access Denied')); ?></h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto"><?php echo e(__('messages.errors.403.message', [], 'Sorry, you do not have permission to access this page. Please contact your administrator if you believe this is a mistake.')); ?></p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">

        <a href="/" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <?php echo e(__('messages.errors.go_home')); ?>

        </a>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\eXtreme\Documents\custom\resources\views\errors\403.blade.php ENDPATH**/ ?>