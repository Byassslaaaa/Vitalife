<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Carbon\Carbon;

class ChatController extends Controller
{
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

            // Find active conversation or create a new one
            $conversation = ChatConversation::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$conversation) {
                $conversation = ChatConversation::create([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'category' => $request->category ?? null,
                ]);
            }

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
     * Send a message (AI always tries first, fallback triggers admin)
        */
        public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|exists:chat_conversations,id',
                'message' => 'required|string',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $conversation = ChatConversation::findOrFail($request->conversation_id);

            // Update category if provided
            if ($request->has('category') && $conversation->category === null) {
                $conversation->update(['category' => $request->category]);
            }

            // Create user message
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'message' => $request->message,
                'sender_type' => 'user',
                'is_read' => false,
            ]);

            Log::info('User message created', ['message_id' => $message->id, 'content' => $request->message]);

            // AI always attempts to answer
            $aiResponse = null;
            try {
                $aiResponse = $this->generateAiResponse($conversation, $request->message);
                Log::info('AI response generated', ['ai_response_id' => $aiResponse->id ?? 'null']);
            } catch (\Exception $e) {
                Log::error('AI Response Error: ' . $e->getMessage());
                $aiResponse = ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'message' => "Maaf, saya sedang mengalami gangguan teknis. Admin akan membantu Anda segera.",
                    'sender_type' => 'ai',
                    'is_read' => true,
                ]);
            }

            // If fallback message (AI tidak bisa jawab), trigger admin notification
            if ($aiResponse && $this->isFallbackMessage($aiResponse->message)) {
                $this->notifyAdmins($conversation, $message);
                Log::info('Admin notification triggered', ['conversation_id' => $conversation->id]);
            }

            // Return response
            return response()->json([
                'success' => true,
                'message' => $message,
                'ai_response' => $aiResponse,
                'admin_active' => $this->isAnyAdminActive(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error in sendMessage: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if a message is a fallback (AI tidak bisa jawab secara spesifik)
     */
    private function isFallbackMessage($msg)
    {
        $fallbackStrings = [
            "admin akan membantu Anda",
            "Admin akan membantu Anda",
            "admin will assist you shortly",
            "An admin will assist you shortly",
            "gangguan teknis",
            "For specific details, an admin will assist you shortly"
        ];
        
        foreach ($fallbackStrings as $pattern) {
            if (stripos($msg, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Notify admins when AI fallback occurs
     */
    private function notifyAdmins($conversation, $message)
    {
        try {
            // Set flag untuk admin notification
            Cache::put('admin_notification_' . $conversation->id, [
                'conversation_id' => $conversation->id,
                'user_message' => $message->message,
                'timestamp' => now()
            ], 3600); // 1 hour

            Log::info('Admin notification set', ['conversation_id' => $conversation->id]);
        } catch (\Exception $e) {
            Log::error('Error notifying admins: ' . $e->getMessage());
        }
    }

    /**
     * Generate AI response (simplified version without OpenAI for now)
     */
    private function generateAiResponse($conversation, $userMessage)
    {
        try {
            Log::info('Generating AI response', ['user_message' => $userMessage, 'category' => $conversation->category]);

            // For now, use rule-based responses instead of OpenAI
            $aiReply = $this->generateRuleBasedResponse($conversation->category, $userMessage);
            
            Log::info('AI reply generated', ['reply' => $aiReply]);

            // Save AI response to database
            $aiMessage = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'message' => $aiReply,
                'sender_type' => 'ai',
                'is_read' => true,
            ]);

            return $aiMessage;

        } catch (\Exception $e) {
            Log::error('Error in generateAiResponse: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate rule-based response (fallback dari OpenAI)
     */
    private function generateRuleBasedResponse($category, $userMessage)
    {
        $userMessageLower = strtolower($userMessage);
        
        // Greeting responses
        if (preg_match('/\b(halo|hai|hello|hi|selamat)\b/i', $userMessage)) {
            return "Halo! Selamat datang di Vitalife. Saya siap membantu Anda dengan pertanyaan seputar layanan wellness kami. Ada yang bisa saya bantu?";
        }

        // Category-specific responses
        switch ($category) {
            case 'Facilities & Accommodations':
                if (strpos($userMessageLower, 'spa') !== false) {
                    return "Vitalife menyediakan fasilitas spa lengkap dengan ruang pijat, sauna, dan area relaksasi. Kami memiliki berbagai treatment spa untuk kesehatan dan kecantikan Anda. Untuk informasi lebih detail tentang paket dan harga, admin akan membantu Anda segera.";
                } elseif (strpos($userMessageLower, 'yoga') !== false) {
                    return "Kami memiliki studio yoga yang nyaman dengan instruktur berpengalaman. Tersedia kelas yoga untuk berbagai level dari pemula hingga advanced. Untuk jadwal kelas dan pendaftaran, admin akan membantu Anda segera.";
                } elseif (strpos($userMessageLower, 'fasilitas') !== false || strpos($userMessageLower, 'facility') !== false) {
                    return "Vitalife memiliki fasilitas lengkap termasuk spa, studio yoga, ruang konsultasi kesehatan, dan area event. Semua fasilitas dirancang untuk memberikan pengalaman wellness terbaik. Admin akan membantu Anda dengan detail fasilitas yang Anda butuhkan.";
                } else {
                    return "Untuk informasi fasilitas dan akomodasi Vitalife, admin akan membantu Anda dengan detail yang Anda butuhkan.";
                }

            case 'Health & Security':
                if (strpos($userMessageLower, 'protokol') !== false || strpos($userMessageLower, 'kesehatan') !== false) {
                    return "Vitalife menerapkan protokol kesehatan dan keamanan yang ketat untuk memastikan kenyamanan dan keselamatan semua tamu. Kami mengikuti standar internasional dalam layanan wellness. Untuk detail protokol spesifik, admin akan membantu Anda segera.";
                } else {
                    return "Kesehatan dan keamanan Anda adalah prioritas utama kami. Admin akan membantu Anda dengan informasi detail tentang protokol yang kami terapkan.";
                }

            case 'Cancellations & Refunds':
                if (strpos($userMessageLower, 'batal') !== false || strpos($userMessageLower, 'cancel') !== false) {
                    return "Kebijakan pembatalan Vitalife memungkinkan pembatalan gratis hingga 24 jam sebelum jadwal appointment. Untuk kasus khusus dan detail kebijakan refund, admin akan membantu Anda segera.";
                } else {
                    return "Untuk informasi pembatalan dan refund, admin akan membantu Anda dengan detail kebijakan yang berlaku.";
                }

            case 'Payments & Promotions':
                if (strpos($userMessageLower, 'promo') !== false || strpos($userMessageLower, 'diskon') !== false || strpos($userMessageLower, 'voucher') !== false) {
                    return "Vitalife memiliki berbagai promo menarik! Anda bisa cek voucher terbaru di halaman voucher website kami. Untuk promo khusus dan penawaran terbaru, admin akan membantu Anda segera.";
                } elseif (strpos($userMessageLower, 'bayar') !== false || strpos($userMessageLower, 'payment') !== false) {
                    return "Kami menerima berbagai metode pembayaran termasuk transfer bank, e-wallet, dan kartu kredit. Untuk detail pembayaran dan cicilan, admin akan membantu Anda segera.";
                } else {
                    return "Untuk informasi pembayaran dan promosi terbaru, admin akan membantu Anda dengan penawaran terbaik.";
                }

            default:
                // General responses
                if (strpos($userMessageLower, 'terima kasih') !== false || strpos($userMessageLower, 'thanks') !== false) {
                    return "Sama-sama! Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya.";
                } elseif (strpos($userMessageLower, 'jam') !== false || strpos($userMessageLower, 'buka') !== false || strpos($userMessageLower, 'tutup') !== false) {
                    return "Vitalife buka setiap hari dengan jam operasional yang fleksibel. Untuk jam operasional detail dan booking appointment, admin akan membantu Anda segera.";
                } else {
                    return "Terima kasih atas pertanyaan Anda. Admin akan membantu Anda dengan informasi yang lebih detail segera.";
                }
        }
    }

    /**
     * Admin get conversations
     */
    public function adminGetConversations()
    {
        try {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversations = ChatConversation::with(['user', 'latestMessage'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($conversation) {
                    return [
                        'id' => $conversation->id,
                        'user' => $conversation->user,
                        'status' => $conversation->status,
                        'category' => $conversation->category,
                        'latest_message' => $conversation->latestMessage,
                        'unread_count' => $conversation->unreadMessagesCount(),
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
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversation = ChatConversation::with(['messages', 'user'])->findOrFail($conversationId);
            
            // Mark user messages as read
            $conversation->messages()
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true]);

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
     * Admin send message
     */
    public function adminSendMessage(Request $request, $conversationId)
    {
        try {
            $request->validate([
                'message' => 'required|string',
            ]);

            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $this->updateAdminActivity();

            $conversation = ChatConversation::findOrFail($conversationId);

            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'admin_id' => auth()->id(),
                'message' => $request->message,
                'sender_type' => 'admin',
                'is_read' => true,
            ]);

            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            Log::error('Error in adminSendMessage: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Admin close conversation
     */
    public function adminCloseConversation($conversationId)
    {
        try {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
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
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = ChatConversation::findOrFail($conversationId);
            $conversation->update(['status' => 'active']);

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
            if (!auth()->check() || auth()->user()->role !== 'admin') {
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
        if (auth()->check() && auth()->user()->role === 'admin') {
            $adminId = auth()->id();
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
