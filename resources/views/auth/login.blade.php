{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    @php
        $school = $school ?? request()->route('school') ?? request()->route('branch');
        $loginAction = $school ? tenant_route('login', $school) : route('login');
    @endphp
    <div class="min-h-screen flex flex-col lg:flex-row" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <!-- Language Switcher -->
        <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} z-50">
            <form id="lang-switcher" method="POST">
                @csrf
                <button type="button" onclick="switchLocale('{{ app()->getLocale() === 'ar' ? 'en' : 'ar' }}')" class="px-4 py-2 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-white hover:shadow-md transition-all duration-200 flex items-center gap-2">
                    <span>{{ app()->getLocale() === 'ar' ? '🇬🇧' : '🇸🇦' }}</span>
                    <span>{{ app()->getLocale() === 'ar' ? __('messages.language.english') : __('messages.language.arabic') }}</span>
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
        <!-- Left Side - App Info (no tenant context required) -->
        <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 p-8 lg:p-16 flex items-center justify-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 {{ app()->getLocale() === 'ar' ? '-right-20' : '-left-20' }} w-64 h-64 bg-white rounded-full"></div>
                <div class="absolute -bottom-20 {{ app()->getLocale() === 'ar' ? '-left-20' : '-right-20' }} w-72 h-72 bg-white rounded-full"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white rounded-full"></div>
            </div>
            <!-- Content -->
            <div class="relative z-10 text-white text-center lg:{{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }} max-w-lg">
                <div class="mb-8">
                    <img src="/WayUp.png" alt="{{ __('messages.app.name') }}" class="w-24 h-24 mx-auto lg:{{ app()->getLocale() === 'ar' ? 'mr-0 ml-auto' : 'mx-0' }} rounded-2xl shadow-2xl mb-6">
                </div>
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                    {{ config('app.name', 'Scholder') }}
                </h1>
                <p class="text-xl mb-6 text-white/90">{{ __('messages.school.educational_platform') }}</p>
                <div class="space-y-4 text-white/80">
                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('messages.school.streamlined_management') }}</span>
                    </div>
                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>{{ __('messages.school.share_access_materials') }}</span>
                    </div>
                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>{{ __('messages.school.collaborate_educators') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="lg:w-1/2 bg-gray-50 p-8 lg:p-16 flex items-center justify-center">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ __('messages.auth.sign_in_account') }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __('messages.auth.enter_credentials') }}
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ $loginAction }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.auth.email') }}
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autofocus
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="{{ __('messages.placeholders.email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('messages.auth.password') }}
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all {{ app()->getLocale() === 'ar' ? 'pl-12 pr-4' : 'pr-12 pl-4' }}"
                                placeholder="{{ __('messages.placeholders.password') }}">
                            <button type="button" onclick="togglePassword()" class="absolute {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} top-3.5 text-gray-500 hover:text-gray-700">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="{{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }} text-sm text-gray-600">{{ __('messages.auth.remember_me') }}</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                        {{ __('messages.auth.sign_in') }}
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        {{ __('messages.app.powered_by') }}
                        <span class="font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                            Ajw Systems
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
