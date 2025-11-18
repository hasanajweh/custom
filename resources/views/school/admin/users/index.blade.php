{{-- resources/views/school/admin/users/index.blade.php --}}
@extends('layouts.school')

@section('title', __('messages.users.user_management') . ' - ' . __('messages.app.name'))

@section('content')
    @php
        $tenantParams = [
            'network' => $school->network->slug,
            'branch' => $school->slug,
            'school' => $school->slug,
        ];
    @endphp
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('messages.users.user_management') }}</h1>
                        <p class="text-lg text-gray-600">{{ __('messages.navigation.manage_users') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ tenant_route('school.admin.users.archived', $tenantParams) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700">
                            <i class="ri-archive-line text-xl"></i>
                            <span>{{ __('messages.users.archived.heading') }}</span>
                        </a>
                        <a href="{{ tenant_route('school.admin.users.create', $tenantParams) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700">
                            <i class="ri-user-add-line text-xl"></i>
                            <span>{{ __('messages.users.add_new_user') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-user-line text-xl text-blue-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $teacherCount }}</div>
                    <div class="text-sm text-gray-600 mt-1 font-medium">{{ __('messages.users.teachers') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-user-star-line text-xl text-green-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $supervisorCount }}</div>
                    <div class="text-sm text-gray-600 mt-1 font-medium">{{ __('messages.users.supervisors') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-shield-user-line text-xl text-purple-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $adminCount }}</div>
                    <div class="text-sm text-gray-600 mt-1 font-medium">{{ __('messages.users.administrators') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="ri-user-forbid-line text-xl text-orange-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $inactiveCount }}</div>
                    <div class="text-sm text-gray-600 mt-1 font-medium">Inactive Users</div>
                </div>
            </div>

            <!-- Compact Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ tenant_route('school.admin.users.index', $tenantParams) }}">
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('messages.users.search_placeholder') }}"
                                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                        <select name="status" class="compact-select">
    <option value="">{{ __('messages.users.all_status') }}</option>
    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
        {{ __('messages.users.active_only') }}
    </option>
    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
        {{ __('messages.users.inactive_only') }}
    </option>
</select>
                    </div>

                    <div class="flex gap-2 flex-wrap">
                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                            <i class="ri-search-line"></i>
                            <span>{{ __('messages.actions.search') }}</span>
                        </button>
                        <a href="{{ tenant_route('school.admin.users.index', $tenantParams) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            <i class="ri-refresh-line"></i>
                            <span>{{ __('messages.actions.reset') }}</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('messages.users.all_members') }}</h2>
                    <p class="text-gray-600 mt-1">{{ $users->total() }} {{ __('messages.labels.total') }} {{ __('messages.users.title') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-8 py-4 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.full_name') }}</th>
                            <th class="px-6 py-4 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.role') }}</th>
                            <th class="px-6 py-4 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.subject') }}</th>
                            <th class="px-6 py-4 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.status') }}</th>
                            <th class="px-6 py-4 text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.last_active') }}</th>
                            <th class="px-8 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('messages.users.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-8 py-6">
                                    <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ getAvatarColor($user->name) }} flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 text-sm">{{ $user->name }}</div>
                                            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-2 mt-1">
                                                <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                            <i class="ri-checkbox-circle-fill {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                            Verified
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    @php
                                        $roleStyles = [
                                            'admin' => ['Admin', 'bg-red-100 text-red-800', 'ri-shield-user-line'],
                                            'teacher' => ['Teacher', 'bg-blue-100 text-blue-800', 'ri-user-line'],
                                            'supervisor' => ['Supervisor', 'bg-green-100 text-green-800', 'ri-user-star-line'],
                                        ];
                                        $roleData = $roleStyles[$user->role] ?? ['User', 'bg-gray-100 text-gray-800', 'ri-user-line'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $roleData[1] }} shadow-sm">
                                            <i class="{{ $roleData[2] }} {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                            {{ $roleData[0] }}
                                        </span>
                                </td>

                                <td class="px-6 py-6">
                                    @if($user->role === 'supervisor' && $user->supervisor_subjects)
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->supervisor_subjects }}</div>
                                        <div class="text-xs text-gray-500">{{ __('messages.users.subject') }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-6">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 shadow-sm">
                                                <i class="ri-checkbox-circle-fill {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                Active
                                            </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 shadow-sm">
                                                <i class="ri-close-circle-fill {{ app()->getLocale() === 'ar' ? 'ml-1' : 'mr-1' }}"></i>
                                                Inactive
                                            </span>
                                    @endif
                                </td>

                                <td class="px-6 py-6">
                                    @if($user->last_login_at)
                                        <div class="text-sm text-gray-900 font-medium">{{ $user->last_login_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->last_login_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">{{ __('messages.users.never') }}</span>
                                    @endif
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-2">
                                        <a href="{{ tenant_route('school.admin.users.edit', array_merge($tenantParams, ['user' => $user->id])) }}"
                                           class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                           title="{{ __('messages.tooltips.edit') }}">
                                            <i class="ri-edit-line text-lg"></i>
                                        </a>

                                        @if($user->id !== Auth::id())
                                            <form method="POST"
                                                  action="{{ tenant_route('school.admin.users.toggle-status', array_merge($tenantParams, ['user' => $user->id])) }}"
                                                  class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="p-2 {{ $user->is_active ? 'text-orange-600 hover:text-orange-800 hover:bg-orange-50' : 'text-green-600 hover:text-green-800 hover:bg-green-50' }} rounded-lg transition-all duration-150"
                                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="{{ $user->is_active ? 'ri-user-forbid-line' : 'ri-user-follow-line' }} text-lg"></i>
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ tenant_route('school.admin.users.destroy', array_merge($tenantParams, ['user' => $user->id])) }}"
                                                  onsubmit="return confirm('Archive this user?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                                        title="{{ __('messages.tooltips.delete') }}">
                                                    <i class="ri-archive-line text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="ri-group-line text-2xl text-gray-400"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.users.no_users_found') }}</h3>
                                            <p class="text-gray-500">{{ __('messages.users.try_adjusting_search') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700 font-medium">
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                            </div>
                            <div class="pagination-wrapper">
                                {{ $users->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Compact Select Styling */
        .compact-select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: {{ app()->getLocale() === 'ar' ? 'left 0.75rem' : 'right 0.75rem' }} center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        {{ app()->getLocale() === 'ar' ? 'padding-left: 2.25rem;' : 'padding-right: 2.25rem;' }}
}

        .compact-select:hover {
            border-color: #9ca3af;
            background-color: #f9fafb;
        }

        .compact-select:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #ffffff;
        }

        /* Pagination styling */
        .pagination-wrapper .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-wrapper .pagination .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            color: #374151;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-wrapper .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination-wrapper .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
    </style>
@endpush
