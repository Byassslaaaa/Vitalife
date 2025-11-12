<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
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
     * Get or create a conversation for the current user or guest
     */
    public function getOrCreateConversation(Request $request)
    {
        try {
            $user = Auth::user();

            // Support guest users with session ID
            if (!$user) {
                $sessionId = $request->session()->getId();

                // Find or create guest conversation
                $conversation = ChatConversation::where('session_id', $sessionId)
                    ->where('is_guest', true)
                    ->where('status', '!=', 'resolved')
                    ->first();

                if (!$conversation) {
                    $conversation = ChatConversation::create([
                        'session_id' => $sessionId,
                        'is_guest' => true,
                        'status' => 'active',
                        'category' => $request->input('category', 'General Information'),
                    ]);
                }
            } else {
                $conversation = $this->chatService->getOrCreateConversation($user->id);
            }

            return response()->json([
                'conversation' => $conversation,
                'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get(),
                'is_guest' => !$user
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getOrCreateConversation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load conversation'], 500);
        }
    }

    /**
     * Send a message with smart AI/Admin routing (Shopee-like) - supports guests
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'nullable|exists:chat_conversations,id',
                'message' => 'required|string|max:1000',
                'category' => 'nullable|string',
            ]);

            $user = Auth::user();
            $conversationId = $request->conversation_id;

            // Get or create conversation
            if (!$conversationId) {
                if ($user) {
                    $conversation = $this->chatService->getOrCreateConversation($user->id);
                } else {
                    // Guest user
                    $sessionId = $request->session()->getId();
                    $conversation = ChatConversation::where('session_id', $sessionId)
                        ->where('is_guest', true)
                        ->where('status', '!=', 'resolved')
                        ->first();

                    if (!$conversation) {
                        $conversation = ChatConversation::create([
                            'session_id' => $sessionId,
                            'is_guest' => true,
                            'status' => 'active',
                            'category' => $request->input('category', 'General Information'),
                        ]);
                    }
                }
            } else {
                $conversation = ChatConversation::findOrFail($conversationId);
            }

            // Update category if provided
            if ($request->has('category') && $request->category) {
                $conversation->update(['category' => $request->category]);
            }

            // Save user message
            $userMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'user',
                'message' => $request->message,
                'is_read' => false,
            ]);

            // Update conversation
            $conversation->update([
                'unread_by_admin' => ($conversation->unread_by_admin ?? 0) + 1,
            ]);

            // Check if admin is available
            $adminActive = $this->isAnyAdminActive();

            if ($adminActive && $conversation->status === 'active') {
                // Assign to waiting for admin
                $conversation->update([
                    'status' => 'waiting_admin',
                    'waiting_since' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'type' => 'forwarded_to_admin',
                    'message' => $userMessage,
                    'bot_message' => 'Pesan Anda telah diteruskan ke admin kami. Mohon tunggu sebentar.',
                    'admin_assigned' => false,
                    'estimated_wait' => '1-2 menit',
                ]);
            }

            // Bot response for common questions
            $botResponse = $this->generateBotResponse($request->message);

            $botMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'message' => $botResponse['message'],
                'is_read' => false,
            ]);

            $conversation->update([
                'unread_by_user' => ($conversation->unread_by_user ?? 0) + 1,
            ]);

            return response()->json([
                'success' => true,
                'type' => 'bot',
                'message' => $userMessage,
                'bot_message' => $botResponse['message'],
                'quick_replies' => $botResponse['quick_replies'] ?? [],
                'suggest_admin' => !$adminActive,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in sendMessage: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate bot response for common questions
     */
    private function generateBotResponse($message)
    {
        $lowerMessage = strtolower($message);

        // Spa related
        if (str_contains($lowerMessage, 'spa') || str_contains($lowerMessage, 'pijat') || str_contains($lowerMessage, 'massage')) {
            return [
                'message' => 'Kami menyediakan berbagai layanan spa dan massage. Anda dapat melihat daftar lengkap spa center kami dengan rating dan harga di halaman Spa. Apakah ada yang ingin Anda tanyakan lebih lanjut?',
                'quick_replies' => ['Lihat Daftar Spa', 'Cara Booking Spa', 'Harga Treatment'],
            ];
        }

        // Yoga related
        if (str_contains($lowerMessage, 'yoga') || str_contains($lowerMessage, 'meditasi')) {
            return [
                'message' => 'Kami memiliki berbagai studio yoga untuk semua level, dari pemula hingga advanced. Kelas tersedia dengan instruktur bersertifikat. Apakah Anda ingin mengetahui jadwal kelas?',
                'quick_replies' => ['Lihat Studio Yoga', 'Jadwal Kelas', 'Harga Membership'],
            ];
        }

        // Gym related
        if (str_contains($lowerMessage, 'gym') || str_contains($lowerMessage, 'fitness') || str_contains($lowerMessage, 'olahraga')) {
            return [
                'message' => 'Fasilitas gym kami dilengkapi dengan peralatan modern dan personal trainer profesional. Kami menawarkan berbagai paket membership. Apa yang ingin Anda ketahui?',
                'quick_replies' => ['Lihat Gym Facilities', 'Paket Membership', 'Personal Trainer'],
            ];
        }

        // Booking related
        if (str_contains($lowerMessage, 'booking') || str_contains($lowerMessage, 'pesan') || str_contains($lowerMessage, 'reservasi')) {
            return [
                'message' => 'Untuk melakukan booking, silakan pilih layanan yang Anda inginkan (Spa/Yoga/Gym), lalu klik tombol "Book Now". Anda perlu login terlebih dahulu untuk menyelesaikan pemesanan.',
                'quick_replies' => ['Cara Login', 'Syarat Booking', 'Pembatalan'],
            ];
        }

        // Price related
        if (str_contains($lowerMessage, 'harga') || str_contains($lowerMessage, 'biaya') || str_contains($lowerMessage, 'tarif')) {
            return [
                'message' => 'Harga bervariasi tergantung lokasi dan jenis layanan. Semua harga tertera jelas di halaman detail masing-masing venue. Kami juga sering memberikan voucher diskon!',
                'quick_replies' => ['Lihat Voucher', 'Harga Spa', 'Harga Yoga', 'Harga Gym'],
            ];
        }

        // Location related
        if (str_contains($lowerMessage, 'lokasi') || str_contains($lowerMessage, 'alamat') || str_contains($lowerMessage, 'dimana')) {
            return [
                'message' => 'Kami memiliki banyak lokasi di berbagai kota. Setiap venue menampilkan alamat lengkap dan peta. Anda bisa menggunakan fitur pencarian untuk menemukan yang terdekat dengan Anda.',
                'quick_replies' => ['Cari Lokasi Terdekat', 'Lihat Semua Lokasi'],
            ];
        }

        // Operating hours
        if (str_contains($lowerMessage, 'jam') || str_contains($lowerMessage, 'buka') || str_contains($lowerMessage, 'tutup')) {
            return [
                'message' => 'Jam operasional berbeda untuk setiap venue. Anda dapat melihat jam buka dan tutup di halaman detail masing-masing tempat. Umumnya buka pukul 08:00 - 21:00.',
                'quick_replies' => ['Lihat Jam Operasional'],
            ];
        }

        // Default response
        return [
            'message' => 'Terima kasih atas pertanyaan Anda! Saya adalah bot asisten HeaLife. Saat ini tidak ada admin yang tersedia. Anda bisa menunggu admin online atau saya akan coba membantu menjawab pertanyaan umum Anda.',
            'quick_replies' => ['Info Spa', 'Info Yoga', 'Info Gym', 'Cara Booking', 'Lihat Voucher'],
        ];
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
     * Poll for new messages (for both user and guest)
     */
    public function pollMessages(Request $request, $conversationId)
    {
        try {
            $conversation = ChatConversation::findOrFail($conversationId);

            // Get messages after last_message_id if provided
            $lastMessageId = $request->input('last_message_id', 0);

            $newMessages = $conversation->messages()
                ->where('id', '>', $lastMessageId)
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark admin messages as read by user
            if ($newMessages->count() > 0) {
                $conversation->messages()
                    ->where('sender_type', 'admin')
                    ->where('is_read', false)
                    ->where('id', '>', $lastMessageId)
                    ->update(['is_read' => true, 'read_at' => now()]);

                $conversation->update(['unread_by_user' => 0]);
            }

            return response()->json([
                'success' => true,
                'messages' => $newMessages,
                'has_new' => $newMessages->count() > 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in pollMessages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to poll messages'], 500);
        }
    }

    /**
     * Admin get conversations with enhanced info (includes guests)
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
                    // Determine display name
                    $displayName = 'Guest User';
                    $displayEmail = null;

                    if ($conversation->is_guest) {
                        if ($conversation->guest_name) {
                            $displayName = $conversation->guest_name;
                        }
                        if ($conversation->guest_email) {
                            $displayEmail = $conversation->guest_email;
                        }
                    } else if ($conversation->user) {
                        $displayName = $conversation->user->name;
                        $displayEmail = $conversation->user->email;
                    }

                    return [
                        'id' => $conversation->id,
                        'is_guest' => $conversation->is_guest,
                        'display_name' => $displayName,
                        'display_email' => $displayEmail,
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
