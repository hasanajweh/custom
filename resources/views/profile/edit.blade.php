{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.profile.title') . ' - Scholder')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-center">
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center font-bold text-white text-3xl mx-auto overflow-hidden">
                            <img src="{{ getRoleIcon(Auth::user()->role) }}" alt="{{ __('messages.roles.' . Auth::user()->role) }}" class="w-full h-full object-contain">
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <img src="{{ getRoleIcon(Auth::user()->role) }}" alt="{{ __('messages.roles.' . Auth::user()->role) }}" class="w-4 h-4 {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}">
                            {{ __('messages.roles.' . Auth::user()->role) }}
                        </span>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('messages.profile.member_since') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ localizedDate(Auth::user()->created_at) }}</dd>
                            </div>
                            @if(Auth::user()->role === 'supervisor')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('messages.users.subject') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ localizedSubject(Auth::user()->subject) }}</dd>
                                </div>
                            @elseif(Auth::user()->role === 'teacher' && Auth::user()->grade)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('messages.users.grade') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ localizedGrade(Auth::user()->grade) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Profile Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.profile.profile_information') }}</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ __('messages.profile.update_profile_info') }}</p>
                    </div>

                    <form method="POST" action="{{ tenant_route('profile.update', $school) }}" class="p-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.profile.full_name') }}
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', Auth::user()->name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.profile.email_address') }}
                            </label>
                            <input 
    type="email" 
    name="email" 
    value="{{ $user->email }}" 
    class="form-input w-full bg-gray-200 text-gray-600 cursor-not-allowed"
    disabled
/>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="ri-save-line mr-2"></i>
                                {{ __('messages.profile.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.profile.update_password') }}</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ __('messages.profile.ensure_long_password') }}</p>
                    </div>

                    <form method="POST" action="{{ tenant_route('profile.password.update', $school) }}" class="p-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.profile.current_password') }}
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.profile.new_password') }}
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.profile.confirm_new_password') }}
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="ri-lock-line mr-2"></i>
                                {{ __('messages.profile.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
