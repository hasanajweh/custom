<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: JSON.parse(localStorage.getItem('darkMode') || 'false'),
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
    }
}" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic title with page context -->
    <title>
        @hasSection('title')
            @yield('title') | {{ config('app.name', 'aJw') }}
        @else
            {{ config('app.name', 'aJw') }}
        @endif
    </title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('build/site.webmanifest') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- PWA -->
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Preload critical assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic meta tags -->
    @yield('meta')


    <!-- Custom styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
@php
    $school = $school ?? Auth::user()?->school;
    $network = $network ?? $school?->network ?? Auth::user()?->network;
    $hasTenantContext = $school && $network;
@endphp
<!-- Loading bar -->
<div x-data="{
        loading: false,
        init() {
            this.loading = true;

            // Listen to Alpine.js load events
            window.addEventListener('alpine:initialized', () => {
                setTimeout(() => this.loading = false, 300);
            });

            // Listen to Livewire events
            Livewire.hook('request', () => { this.loading = true; });
            Livewire.hook('afterDomUpdate', () => { this.loading = false; });
        }
    }"
     class="fixed top-0 left-0 right-0 z-50 h-1 bg-blue-500 transition-all duration-300"
     :class="{ 'opacity-0': !loading, 'opacity-100': loading }"
     :style="`transform: translateX(${loading ? '0%' : '-100%'}); width: ${loading ? '90%' : '0%'}`"></div>

<!-- Main layout -->
<div x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        sidebarMinimized: localStorage.getItem('sidebarMinimized') === 'true',
        toggleSidebar() {
            if (window.innerWidth < 1024) {
                this.sidebarOpen = !this.sidebarOpen;
            } else {
                this.sidebarMinimized = !this.sidebarMinimized;
                localStorage.setItem('sidebarMinimized', this.sidebarMinimized);
            }
        },
        isDesktop: window.innerWidth >= 1024,
        checkScreen() {
            this.isDesktop = window.innerWidth >= 1024;
            if (this.isDesktop) {
                this.sidebarOpen = true;
            }
        }
    }"
     x-init="checkScreen"
     @resize.window.debounce.250="checkScreen"
     class="min-h-screen flex">

    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main content area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Sticky header -->
        <header class="sticky top-0 z-40 flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button @click="toggleSidebar" type="button" class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Right side controls -->
                <div class="flex items-center space-x-4">
                    <!-- Dark mode toggle -->
                    <button @click="toggleDarkMode" type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    @auth
                        <!-- User dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="flex items-center space-x-2 max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:inline-flex items-center">
                                            <span>{{ Auth::user()->name }}</span>
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @php
                                    $user = Auth::user();
                                    $userSchool = $user?->school ?? $school;
                                    if ($user && $user->role === 'main_admin') {
                                        $networkSlug = $user->network?->slug;
                                        $profileUrl = route('main-admin.dashboard', ['network' => $networkSlug]);
                                        $logoutUrl = route('main-admin.logout', ['network' => $networkSlug]);
                                    } else {
                                        $hasTenantContext = $userSchool && $userSchool->network;
                                        $profileUrl = $hasTenantContext ? tenant_route('profile.edit', $userSchool) : '#';
                                        $logoutUrl = $hasTenantContext ? tenant_route('logout', $userSchool) : '#';
                                    }
                                @endphp
                                <x-dropdown-link :href="$profileUrl">
                                    <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </x-dropdown-link>
                                <x-dropdown-link href="#">
                                    <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </x-dropdown-link>
                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                <form method="POST" action="{{ $logoutUrl }}">
                                    @csrf
                                    <x-dropdown-link :href="$logoutUrl" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto focus:outline-none" tabindex="0">
            <!-- Page heading -->
            <div class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                @yield('header')
                            </h1>
                            @hasSection('breadcrumbs')
                                <nav class="flex mt-2" aria-label="Breadcrumb">
                                    <ol class="flex items-center space-x-2">
                                        @yield('breadcrumbs')
                                    </ol>
                                </nav>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            @yield('header-actions')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<!-- Session toasts -->
<x-session-toasts />

<!-- Livewire scripts -->
@livewireScripts

@include('layouts.partials.service-worker-cleanup')
<!-- Scripts -->
@stack('scripts')

<!-- Intercom or other live chat -->
@if(config('services.intercom.enabled'))
    <script>
        window.intercomSettings = {
            app_id: "{{ config('services.intercom.app_id') }}",
            name: "{{ Auth::user()->name }}",
            email: "{{ Auth::user()->email }}",
            created_at: {{ Auth::user()->created_at->timestamp }}
        };
    </script>
    <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/' + "{{ config('services.intercom.app_id') }}";var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
@endif
</body>
</html>
