<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.errors.503.title') }} - {{ __('messages.app.name') }}</title>
    <link rel="icon" type="image/png" href="/WayUp.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .wrench-animation { animation: wrench 2s ease-in-out infinite; }
        @keyframes wrench {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-2xl mx-auto">
    <div class="mb-8">
        <svg class="w-32 h-32 md:w-40 md:h-40 mx-auto text-gray-400 wrench-animation" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
        </svg>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ __('messages.errors.503.heading') }}</h1>
    <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">{{ __('messages.errors.503.message') }}</p>
    <div class="w-full max-w-xs mx-auto mb-8">
        <div class="bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full animate-pulse" style="width: 70%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-2">{{ __('messages.errors.503.estimated') }}: 30 {{ __('messages.errors.503.minutes') }}</p>
    </div>
    <p class="text-sm text-gray-600">
        {{ __('messages.errors.503.follow_updates') }}:
        <a href="#" class="text-blue-600 hover:text-blue-800">@ScholderEdu</a>
    </p>
</div>
</body>
</html>
