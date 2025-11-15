@extends('layouts.superadmin')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-gray-900 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                    Super Admin Login
                </h2>
                <p class="mt-2 text-center text-sm text-gray-400">
                    Access the administrative dashboard
                </p>
            </div>

            <div class="glass rounded-lg p-8">
                <form class="space-y-6" method="POST" action="{{ route('superadmin.login.store') }}">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   autocomplete="email"
                                   required
                                   value="{{ old('email') }}"
                                   class="block w-full appearance-none rounded-md bg-gray-800 border border-gray-600 px-3 py-2 text-white placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="current-password"
                                   required
                                   class="block w-full appearance-none rounded-md bg-gray-800 border border-gray-600 px-3 py-2 text-white placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember"
                                   name="remember"
                                   type="checkbox"
                                   class="h-4 w-4 rounded border-gray-600 bg-gray-800 text-indigo-600 focus:ring-indigo-500">
                            <label for="remember" class="ml-2 block text-sm text-gray-300">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="btn-glow flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
