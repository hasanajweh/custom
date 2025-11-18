<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @php
        $network = auth()->user()?->network ?? (request()->route('network') instanceof \App\Models\Network ? request()->route('network') : null);
        $networkSlug = $network?->slug ?? (is_string(request()->route('network')) ? request()->route('network') : '');
        $networkName = $network?->name ?? config('app.name');
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $networkName . ' - ' . __('messages.app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    @if (session()->has('impersonator_id'))
        <div class="bg-yellow-400 px-4 py-2 text-sm text-yellow-900 text-center">
            <span>@lang('messages.impersonation.active')</span>
            <form method="POST" action="{{ route('impersonate.leave') }}" class="inline-block ml-2">
                @csrf
                <button type="submit" class="underline">@lang('messages.impersonation.leave')</button>
            </form>
        </div>
    @endif

    <nav class="bg-white shadow px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <img src="/WayUp.png" alt="{{ __('messages.app.name') }}" class="h-10 w-10 rounded-lg">
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $networkName }}</p>
                    <p class="text-sm text-gray-600">@lang('messages.main_admin')</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <form method="POST" action="{{ route('main-admin.logout', ['network' => $networkSlug]) }}">
                    @csrf
                    <button class="text-sm text-gray-700 hover:text-gray-900">@lang('messages.auth.logout')</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex">
        <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-4 space-y-2">
            <a href="{{ route('main-admin.dashboard', ['network' => $networkSlug]) }}"
               class="flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('main-admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <span>@lang('messages.navigation.dashboard')</span>
            </a>
            <a href="{{ route('main-admin.users.index', ['network' => $networkSlug]) }}"
               class="flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('main-admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <span>@lang('messages.main_admin.navigation.users')</span>
            </a>
            <a href="{{ route('main-admin.hierarchy', ['network' => $networkSlug]) }}"
               class="flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('main-admin.hierarchy') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <span>@lang('messages.main_admin.navigation.hierarchy')</span>
            </a>
            <a href="{{ route('main-admin.subjects-grades', ['network' => $networkSlug]) }}"
               class="flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('main-admin.subjects-grades*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <span>@lang('messages.main_admin.navigation.subjects_grades')</span>
            </a>
            <a href="{{ route('main-admin.file-browser.index', ['network' => $networkSlug]) }}"
               class="flex items-center justify-between px-3 py-2 rounded {{ request()->routeIs('main-admin.file-browser.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                <span>@lang('messages.navigation.file_browser')</span>
            </a>
        </aside>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
