<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.errors.500.title') }} - {{ __('messages.app.name') }}</title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gear-animation { animation: rotate 4s linear infinite; }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-orange-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-600 leading-none">500</div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-24 h-24 md:w-32 md:h-32 text-red-300 gear-animation" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ __('messages.errors.500.heading') }}</h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">{{ __('messages.errors.500.message') }}</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="javascript:location.reload()" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('messages.errors.500.action') }}
        </a>
        <a href="/" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            {{ __('messages.errors.go_home') }}
        </a>
    </div>
</div>
</body>
</html>
