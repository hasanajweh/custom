<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.errors.404.title') }} - {{ __('messages.app.name') }}</title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <!-- Animated 404 -->
    <div class="relative mb-8">
        <div class="text-[150px] md:text-[200px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600 leading-none">
            404
        </div>
        <div class="absolute inset-0 flex items-center justify-center">
            <img src="/WayUp.png" alt="{{ __('messages.app.name') }}" class="w-24 h-24 md:w-32 md:h-32 opacity-20 float-animation">
        </div>
    </div>

    <!-- Message -->
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
        {{ __('messages.errors.404.heading') }}
    </h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
        {{ __('messages.errors.404.message') }}
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="javascript:history.back()"
           class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 border border-gray-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('messages.errors.go_back') }}
        </a>
        <a href="/"
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            {{ __('messages.errors.go_home') }}
        </a>
    </div>

    <!-- Fun illustration -->
    <div class="mt-16 text-gray-400">
        <svg class="w-64 h-64 mx-auto opacity-50" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="100" cy="100" r="80" stroke="currentColor" stroke-width="2" stroke-dasharray="5 5"/>
            <path d="M70 80C70 74.4772 74.4772 70 80 70C85.5228 70 90 74.4772 90 80" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            <path d="M110 80C110 74.4772 114.477 70 120 70C125.523 70 130 74.4772 130 80" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            <path d="M70 130C80 120 90 120 100 120C110 120 120 120 130 130" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>
    </div>
</div>
</body>
</html>
