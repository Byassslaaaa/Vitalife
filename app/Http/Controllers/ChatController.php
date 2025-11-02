<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ChatConversation;
use App\Models\User;
use App\Services\ChatService;
use App\Http\Requests\ChatMessageRequest;
use App\Http\Requests\AdminChatRequest;
use Carbon\Carbon;

class ChatController extends Controller
{
    protected $chatService;

    /**
     * Constructor with dependency injection
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get or create a conversation for the current user
     */
    public function getOrCreateConversation(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $conversation = $this->chatService->getOrCreateConversation($user->id);

            return response()->json([
                'conversation' => $conversation,
                'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getOrCreateConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load conversation'], 500);
        }
    }

    /**
     * Send a message with smart AI/Admin routing (Shopee-like)
     */
    public function sendMessage(ChatMessageRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $conversationId = $request->conversation_id;
            if (!$conversationId) {
                $conversation = $this->chatService->getOrCreateConversation($user->id);
            } else {
                $conversation = ChatConversation::findOrFail($conversationId);
            }

            // Process message using new ChatService with smart routing
            $result = $this->chatService->processUserMessage(
                $conversation,
                $request->message,
                $user->id
            );

            return response()->json([
                'success' => true,
                'type' => $result['type'],
                'message' => $result['message'],
                'quick_replies' => $result['quick_replies'] ?? [],
                'suggest_admin' => $result['suggest_admin'] ?? false,
                'confidence' => $result['confidence'] ?? null,
                'admin_assigned' => $result['admin_assigned'] ?? false,
                'estimated_wait' => $result['estimated_wait'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in sendMessage: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get canned responses for admin (quick replies)
     */
    public function getCannedResponses()
    {
        try {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $responses = $this->chatService->getCannedResponses();
            return response()->json(['canned_responses' => $responses]);
        } catch (\Exception $e) {
            Log::error('Error in getCannedResponses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load canned responses'], 500);
        }
    }

    /**
     * Rate a conversation
     */
    public function rateConversation(Request $request, $conversationId)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'nullable|string|max:500'
            ]);

            $conversation = ChatConversation::findOrFail($conversationId);

            // Verify user owns this conversation
            if ($conversation->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->chatService->saveRating(
                $conversation,
                $request->rating,
                $request->feedback
            );

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas rating Anda!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in rateConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save rating'], 500);
        }
    }

    /**
     * Admin get conversations with enhanced info
     */
    public function adminGetConversations()
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversations = ChatConversation::with(['user', 'latestMessage', 'admin'])
                ->orderBy('waiting_since', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($conversation) {
                    return [
                        'id' => $conversation->id,
                        'user' => $conversation->user,
                        'admin' => $conversation->admin,
                        'status' => $conversation->status,
                        'category' => $conversation->category,
                        'latest_message' => $conversation->latestMessage,
                        'unread_by_admin' => $conversation->unread_by_admin ?? 0,
                        'unread_by_user' => $conversation->unread_by_user ?? 0,
                        'waiting_since' => $conversation->waiting_since,
                        'rating' => $conversation->rating,
                        'created_at' => $conversation->created_at,
                        'updated_at' => $conversation->updated_at,
                    ];
                });

            return response()->json(['conversations' => $conversations]);
        } catch (\Exception $e) {
            Log::error('Error in adminGetConversations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load conversations'], 500);
        }
    }

    /**
     * Admin get messages for a conversation
     */
    public function adminGetMessages($conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversation = ChatConversation::with(['messages', 'user'])->findOrFail($conversationId);

            // Mark messages as read by admin and reset counter
            $conversation->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

            $conversation->update(['unread_by_admin' => 0]);

            return response()->json([
                'conversation' => $conversation,
                'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminGetMessages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load messages'], 500);
        }
    }

    /**
     * Admin send message using ChatService
     */
    public function adminSendMessage(AdminChatRequest $request, $conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversation = ChatConversation::findOrFail($conversationId);

            // Use ChatService for admin message
            $message = $this->chatService->sendAdminMessage(
                $conversation,
                Auth::id(),
                $request->message
            );

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminSendMessage: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Admin resolve/close conversation
     */
    public function adminResolveConversation($conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);

            // Use ChatService to resolve conversation
            $this->chatService->resolveConversation($conversation, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Conversation resolved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminResolveConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to resolve conversation'], 500);
        }
    }

    /**
     * Admin transfer conversation to another admin
     */
    public function adminTransferConversation(Request $request, $conversationId)
    {
        try {
            $request->validate([
                'new_admin_id' => 'required|exists:users,id',
                'reason' => 'nullable|string|max:255'
            ]);

            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);

            // Use ChatService to transfer conversation
            $this->chatService->transferToAdmin(
                $conversation,
                $request->new_admin_id,
                Auth::id(),
                $request->reason ?? 'No reason provided'
            );

            return response()->json([
                'success' => true,
                'message' => 'Conversation transferred successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminTransferConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to transfer conversation'], 500);
        }
    }

    /**
     * Admin assign conversation to self
     */
    public function adminAssignToSelf($conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);

            // Use ChatService to assign conversation
            $this->chatService->assignToAdmin($conversation, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Conversation assigned to you'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in adminAssignToSelf: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to assign conversation'], 500);
        }
    }

    /**
     * Admin get performance stats
     */
    public function adminGetStats(Request $request)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $period = $request->input('period', 'today');
            $stats = $this->chatService->getAdminStats(Auth::id(), $period);

            return response()->json(['stats' => $stats]);
        } catch (\Exception $e) {
            Log::error('Error in adminGetStats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load stats'], 500);
        }
    }

    /**
     * Admin close conversation (deprecated - use adminResolveConversation)
     */
    public function adminCloseConversation($conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);
            $conversation->update(['status' => 'closed']);

            return response()->json(['message' => 'Conversation closed successfully']);
        } catch (\Exception $e) {
            Log::error('Error in adminCloseConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to close conversation'], 500);
        }
    }

    /**
     * Admin reopen conversation
     */
    public function adminReopenConversation($conversationId)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);
            $conversation->update(['status' => 'active', 'resolved_at' => null, 'resolved_by' => null]);

            return response()->json(['message' => 'Conversation reopened successfully']);
        } catch (\Exception $e) {
            Log::error('Error in adminReopenConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to reopen conversation'], 500);
        }
    }

    /**
     * Update admin activity status
     */
    public function updateAdminActivityStatus(Request $request)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            return response()->json(['message' => 'Admin activity updated']);
        } catch (\Exception $e) {
            Log::error('Error in updateAdminActivityStatus: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update admin activity'], 500);
        }
    }

    /**
     * Check admin activity status
     */
    public function checkAdminActivityStatus()
    {
        try {
            $adminActive = $this->isAnyAdminActive();
            return response()->json(['admin_active' => $adminActive]);
        } catch (\Exception $e) {
            Log::error('Error in checkAdminActivityStatus: ' . $e->getMessage());
            return response()->json(['admin_active' => false], 200);
        }
    }

    /**
     * Show admin chat interface
     */
    public function showAdminChat()
    {
        $this->updateAdminActivity();
        return view('admin.chat.index');
    }

    /**
     * Update admin activity timestamp
     */
    private function updateAdminActivity()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $adminId = Auth::id();
            Cache::put('admin_active_' . $adminId, Carbon::now(), 300); // 5 minutes
        }
    }

    /**
     * Check if any admin is currently active
     */
    private function isAnyAdminActive()
    {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $lastActivity = Cache::get('admin_active_' . $admin->id);
                if ($lastActivity && $lastActivity->diffInMinutes(Carbon::now()) < 5) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error checking admin activity: ' . $e->getMessage());
            return false;
        }
    }
}
