<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Scholder') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-gray-100 text-gray-900">
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">{{ auth()->user()->network->name ?? __('messages.network') }}</h1>
                <p class="text-sm text-gray-600">@lang('messages.main_admin_panel')</p>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ tenant_route('main-admin.dashboard') }}" class="font-medium text-blue-600 hover:text-blue-800">@lang('messages.dashboard.dashboard')</a>
                <a href="{{ tenant_route('main-admin.users.index') }}" class="font-medium text-blue-600 hover:text-blue-800">@lang('messages.users')</a>
                <a href="{{ tenant_route('main-admin.hierarchy') }}" class="font-medium text-blue-600 hover:text-blue-800">@lang('messages.hierarchy')</a>
                <a href="{{ tenant_route('main-admin.subjects-grades') }}" class="font-medium text-blue-600 hover:text-blue-800">@lang('messages.subjects_grades')</a>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @if(session('status'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
