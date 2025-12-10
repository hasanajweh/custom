  <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Scholder') }} - Super Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar for dark theme */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s ease;
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Button hover effects */
        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
        }

        /* Card hover effects */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .pagination .page-link {
            padding: 0.5rem 1rem;
            background-color: #374151;
            color: #9CA3AF;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background-color: #4B5563;
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background-color: #4F46E5;
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-900 text-gray-100">
@auth
    @if(Auth::user()->is_super_admin)
        <div x-data="{ sidebarOpen: false }" class="h-full flex">
            <!-- Sidebar for desktop -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex w-64 flex-col">
                    <div class="flex min-h-0 flex-1 flex-col bg-gray-800 border-r border-gray-700">
                        @include('layouts.partials.superadmin-sidebar-content')
                    </div>
                </div>
            </div>

            <!-- Mobile sidebar -->
            <div x-show="sidebarOpen" class="relative z-40 lg:hidden" role="dialog" aria-modal="true">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900 bg-opacity-75"
                     @click="sidebarOpen = false"></div>

                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="fixed inset-0 z-40 flex">
                    <div class="relative flex w-full max-w-xs flex-1 flex-col bg-gray-800">
                        <div class="absolute top-0 right-0 -mr-12 pt-2">
                            <button @click="sidebarOpen = false" type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @include('layouts.partials.superadmin-sidebar-content')
                    </div>
                    <div class="w-14 flex-shrink-0"></div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-1 flex-col overflow-hidden">
                <!-- Top navigation -->
                <div class="relative z-10 flex h-16 flex-shrink-0 bg-gray-800 shadow-lg border-b border-gray-700">
                    <button @click="sidebarOpen = true" type="button" class="px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-1 items-center">
                            <h1 class="text-xl font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="ml-4 flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                            <form id="lang-switcher" method="POST">
                                @csrf
                                <button type="button" onclick="switchLocale('{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}')" class="inline-flex items-center gap-2 text-sm font-medium text-gray-200 hover:text-white">
                                    {{ app()->getLocale() === 'ar' ? __('messages.language.english') : __('messages.language.arabic') }}
                                </button>
                            </form>

                            <script>
                            function switchLocale(locale) {
                                fetch("{{ route('locale.update') }}", {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector('#lang-switcher input[name=_token]').value,
                                        "Content-Type": "application/json",
                                        "Accept": "application/json"
                                    },
                                    body: JSON.stringify({ locale: locale }),
                                    credentials: 'same-origin'
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 100);
                                })
                                .catch(error => {
                                    console.error('Language switch error:', error);
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 100);
                                });
                            }
                            </script>

                            <!-- Notifications -->
                            <button type="button" class="rounded-full bg-gray-700 p-1.5 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </button>

                            <!-- Profile dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" type="button" class="flex items-center rounded-full bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <form method="POST" action="{{ route('superadmin.logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page content -->
                <main class="flex-1 overflow-y-auto bg-gray-900">
                    <div class="py-6">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            @yield('content')
                        </div>
                    </div>
                </main>
            </div>
        </div>
    @else
        <div class="flex h-full items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-red-500">Access Denied</h1>
                <p class="mt-2 text-gray-400">You don't have permission to access this area.</p>
                <a href="/" class="mt-4 inline-block text-indigo-400 hover:text-indigo-300">Go back home</a>
            </div>
        </div>
    @endif
@else
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>
