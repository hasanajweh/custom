<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

{{-- Impersonation Banner --}}
@if (session()->has('impersonator_id'))
    <div class="relative bg-yellow-400 px-4 py-3 text-sm font-semibold text-yellow-900">
        <div class="text-center">
            <span>You are currently impersonating another user.</span>
            <form method="POST" action="{{ route('impersonate.leave') }}" class="inline-block ml-2">
                @csrf
                <button type="submit" class="underline hover:opacity-80">Leave Impersonation</button>
            </form>
        </div>
    </div>
@endif

{{-- ENHANCEMENT: Replaced gray backgrounds with your brand theme for consistency --}}
<div class="min-h-screen bg-brand-background">
    @include('layouts.navigation')

    {{-- Main content for the Admin Layout --}}
    <div class="lg:pl-72">
        @if (isset($header))
            <header class="bg-brand-secondary shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

@include('layouts.partials.service-worker-cleanup')
@stack('scripts')

</body>
</html>
