<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch recent notifications (read and unread)
        $notifications = $user->notifications()->latest()->take(10)->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }
    
    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        
        if ($request->filled('id')) {
            $notification = $user->notifications()->where('id', $request->id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            $user->unreadNotifications->markAsRead();
        }
        
        return response()->json(['status' => 'success']);
    }
}
