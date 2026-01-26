<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $unreadCount = Notification::where('user_id', $user->user_id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Send scheduled notifications to all users
     */
    public function sendScheduledNotifications()
    {
        $users = User::all();
        $notificationTypes = [
            [
                'type' => 'read_reminder',
                'title' => '📚 Time to Read!',
                'message' => 'Don\'t forget to continue your reading journey today. Your books are waiting!',
            ],
            [
                'type' => 'explore_books',
                'title' => '🔍 Discover New Books',
                'message' => 'Explore our collection and find your next favorite book. New titles added!',
            ],
            [
                'type' => 'connect_people',
                'title' => '👥 Connect with Readers',
                'message' => 'Join our community channels and connect with fellow book lovers!',
            ],
        ];

        $sentCount = 0;
        foreach ($users as $user) {
            // Send a random notification type to each user
            $randomNotification = $notificationTypes[array_rand($notificationTypes)];
            
            Notification::create([
                'user_id' => $user->user_id,
                'type' => $randomNotification['type'],
                'title' => $randomNotification['title'],
                'message' => $randomNotification['message'],
            ]);
            
            $sentCount++;
        }

        return response()->json([
            'message' => 'Notifications sent successfully',
            'sent_to_users' => $sentCount,
        ]);
    }
}
