<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ResolvesSchoolFromRequest;

    public function index(Request $request)
    {
        $school = $this->resolveSchool($request);

        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('school', 'notifications', 'unreadCount'));
    }

    public function markAsRead(Request $request, $school, $notification)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($notification);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read');
    }

    public function unreadCount(School $school)
    {
        $user = Auth::user();
        $unreadCount = $user->unreadNotifications()->count();

        // Get the latest unread notification
        $latestNotification = $user->unreadNotifications()
            ->latest()
            ->first();

        $response = [
            'count' => $unreadCount
        ];

        if ($latestNotification) {
            $response['latest'] = [
                'id' => $latestNotification->id,
                'title' => $latestNotification->data['title'] ?? 'New Notification',
                'message' => $latestNotification->data['message'] ?? '',
                'type' => $latestNotification->data['type'] ?? null,
                'created_at' => $latestNotification->created_at->diffForHumans()
            ];
        }

        return response()->json($response);
    }
}
