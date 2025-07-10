<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatNotificationController extends Controller
{
    /**
     * Get unread message count for admin
     */
    public function getUnreadCount()
    {
        try {
            // Check if user is admin
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
            
            $count = ChatMessage::where('sender_type', 'user')
                ->where('is_read', false)
                ->count();
                
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Error in getUnreadCount: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get unread count. ' . $e->getMessage()], 500);
        }
    }
}
