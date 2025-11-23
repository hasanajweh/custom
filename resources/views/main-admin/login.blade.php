<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700 p-6" dir="rtl">
        <div class="bg-white shadow-2xl rounded-2xl w-full max-w-3xl grid md:grid-cols-2 overflow-hidden">
            <div class="bg-gradient-to-br from-indigo-700 to-purple-700 text-white p-8 flex flex-col justify-center">
                <h1 class="text-3xl font-bold mb-2">{{ $network->name }}</h1>
                <p class="text-white/80 mb-6">@lang('messages.network_overview')</p>
                <div class="text-sm text-white/80 space-y-2">
                    <p>@lang('messages.app.name') — {{ __('messages.app.tagline') }}</p>
                    <p>@lang('messages.plan'): {{ __('messages.branches_plan', [], app()->getLocale()) ?? 'Branches Plan' }}</p>
                </div>
            </div>
            <div class="p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">@lang('messages.log_in')
                    <span class="text-sm text-gray-500">(@lang('messages.main_admin_label'))</span>
                </h2>
                <form method="POST" action="{{ route('main-admin.login', ['network' => $network->slug ?? $network]) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="email">@lang('messages.email')</label>
                        <input id="email" type="email" name="email" required autofocus class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="password">@lang('messages.password')</label>
                        <input id="password" type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="mr-2">@lang('messages.remember_me')</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                                @lang('messages.forgot_password')
                            </a>
                        @endif
                    </div>
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        @lang('messages.log_in')
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
