{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.school')

@section('title', 'Notifications - Scholder')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="mt-1 text-sm text-gray-600">Stay updated with your latest activities</p>
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read', ['school' => $school->slug]) }}">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        <i class="ri-check-double-line"></i>
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg border {{ $notification->read_at ? 'border-gray-200' : 'border-blue-200 bg-blue-50' }} overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'App\Notifications\FileUploaded')
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="ri-file-upload-line text-blue-600"></i>
                                        </div>
                                    @elseif($notification->type === 'App\Notifications\UserRegistered')
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="ri-user-add-line text-green-600"></i>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="ri-notification-3-line text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->data['title'] ?? 'New Notification' }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <p class="mt-2 text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', ['school' => $school->slug, 'notification' => $notification->id]) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <i class="ri-notification-off-line text-4xl text-gray-400"></i>
                    <p class="mt-4 text-gray-500">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
