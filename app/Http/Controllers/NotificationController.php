<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications;
        return response()->json($notifications);
    }

    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        $user->unreadNotifications->where('id', $request->id)->markAsRead();
        return response()->json(['success' => true]);
    }
}
