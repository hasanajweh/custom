<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ getDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Scholder') }} — @yield('title')</title>
    @php
        $networkModel = $network ?? request()->route('network') ?? (Auth::check() ? Auth::user()->network : null);
        $networkSlug = $networkModel instanceof \App\Models\Network
            ? $networkModel->slug
            : (is_string($networkModel) ? $networkModel : null);
        $userName = Auth::check() ? Auth::user()->name : '';
    @endphp
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen">
    @auth
    <div class="flex min-h-screen">
        <aside class="w-72 bg-white border-r border-slate-200 hidden lg:flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200">
                <h1 class="text-lg font-semibold text-indigo-700">{{ $networkModel?->name ?? __('Network') }}</h1>
                <p class="text-xs text-slate-500">@lang('messages.main_admin_label')</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ $networkSlug ? route('main-admin.dashboard', ['network' => $networkSlug]) : '#' }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('main-admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-700' }}">
                    <span class="font-semibold">@lang('messages.dashboard.dashboard')</span>
                </a>
                <a href="{{ $networkSlug ? route('main-admin.users.index', ['network' => $networkSlug]) : '#' }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('main-admin.users.*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-700' }}">
                    <span class="font-semibold">@lang('messages.users.manage_users')</span>
                </a>
                <a href="{{ $networkSlug ? route('main-admin.hierarchy', ['network' => $networkSlug]) : '#' }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('main-admin.hierarchy') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-700' }}">
                    <span class="font-semibold">@lang('messages.network_overview')</span>
                </a>
                <a href="{{ $networkSlug ? route('main-admin.subjects-grades', ['network' => $networkSlug]) : '#' }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('main-admin.subjects-grades*') ? 'bg-indigo-100 text-indigo-700' : 'text-slate-700' }}">
                    <span class="font-semibold">@lang('messages.subjects_grades')</span>
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-slate-200">
                <form method="POST" action="{{ $networkSlug ? route('main-admin.logout', ['network' => $networkSlug]) : '#' }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700">
                        @lang('messages.log_out')
                    </button>
                </form>
            </div>
        </aside>
        <div class="flex-1 min-h-screen">
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-semibold text-slate-800">@yield('title')</h2>
                    <p class="text-sm text-slate-500">@lang('messages.main_admin_label')</p>
                </div>
                <div class="text-sm text-slate-600">
                    {{ $userName }}
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    @endauth
</body>
</html>
