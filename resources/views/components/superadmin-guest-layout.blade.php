<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'aJw') }} - Super Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-brand-background">
    <!-- Include the dedicated Super Admin Sidebar -->
    @include('layouts.superadmin-navigation')

    <div class="lg:pl-72 flex flex-col flex-1">
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-brand-border lg:border-none">
            <button @click.stop="sidebarOpen = !sidebarOpen" type="button" class="px-4 border-r border-brand-border text-gray-500 lg:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            <div class="flex-1 px-4 flex justify-end sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8"></div>
        </div>

        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-1 pb-8">
            {{ $slot }}
        </main>
    </div>
</div>
<x-session-toasts />
@stack('scripts')
</body>
</html>
