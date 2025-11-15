{{-- resources/views/superadmin/users/index.blade.php --}}
@extends('layouts.superadmin')

@section('page-title', 'School Users')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Users</h1>
                <p class="mt-2 text-gray-400">Manage users for <span class="text-indigo-400">{{ $school->name }}</span></p>
            </div>
            <a href="{{ route('superadmin.schools.index') }}"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                ← Back to Schools
            </a>
        </div>

        <!-- User Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-white">{{ $users->total() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Admins</p>
                        <p class="text-2xl font-bold text-purple-400">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Teachers</p>
                        <p class="text-2xl font-bold text-blue-400">{{ $users->where('role', 'teacher')->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>

            <div class="glass rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Supervisors</p>
                        <p class="text-2xl font-bold text-green-400">{{ $users->where('role', 'supervisor')->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="glass rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                <tr class="border-b border-gray-700">
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Joined</th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full {{ getAvatarColor($user->name) }} flex items-center justify-center font-semibold text-white">
                                    {{ getInitials($user->name) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                    @if($user->subject)
                                        <div class="text-xs text-gray-400">{{ $user->subject }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ getRoleBadgeClass($user->role) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-400 rounded-full"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                               <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('superadmin.users.edit', ['school' => $school, 'user' => $user]) }}"
                                   class="text-indigo-400 hover:text-indigo-300 transition">
                                    Edit
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('superadmin.users.destroy', ['school' => $school, 'user' => $user]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="text-red-400 hover:text-red-300 transition">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="glass rounded-lg p-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
