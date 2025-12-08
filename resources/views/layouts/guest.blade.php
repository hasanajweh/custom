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
    <div class="flex justify-end p-4">
        <form id="lang-switcher" method="POST">
            @csrf
            <button type="button" onclick="switchLocale('{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}')" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
                {{ app()->getLocale() === 'ar' ? __('messages.language.english') : __('messages.language.arabic') }}
            </button>
        </form>
    </div>

    <script>
    function switchLocale(locale) {
        fetch("{{ route('locale.update') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('#lang-switcher input[name=_token]').value,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ locale: locale })
        }).then(() => {
            window.location.reload(); // Reload SAME URL
        });
    }
    </script>

    @yield('content')
</body>
</html>
