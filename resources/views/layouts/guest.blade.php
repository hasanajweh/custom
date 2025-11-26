<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Scholder'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    @php
        $currentLocale = app()->getLocale();
        $nextLocale = $currentLocale === 'ar' ? 'en' : 'ar';

        $label = $currentLocale === 'ar'
            ? __('messages.language.english')
            : __('messages.language.arabic');
    @endphp

    <div class="flex justify-end p-4">
        <form action="{{ route('locale.update') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="locale" value="{{ $nextLocale }}">
            <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
                <span>{{ $label }}</span>
            </button>
        </form>
    </div>

    @yield('content')
</body>
</html>
