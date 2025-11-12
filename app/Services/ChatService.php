<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * AI confidence threshold - if below this, escalate to admin
     */
    protected const AI_CONFIDENCE_THRESHOLD = 0.7;

    /**
     * Maximum wait time before auto-assigning to admin (seconds)
     */
    protected const MAX_WAIT_TIME = 30;

    /**
     * Maximum active chats per admin
     */
    protected const MAX_CHATS_PER_ADMIN = 5;

    /**
     * Get or create conversation for user
     */
    public function getOrCreateConversation(int $userId): ChatConversation
    {
        return ChatConversation::firstOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'active',
            ]
        );
    }

    /**
     * Process user message with smart AI/Admin routing
     */
    public function processUserMessage(ChatConversation $conversation, string $message, int $userId): array
    {
        // Save user message
        $userMessage = $this->saveMessage($conversation, $userId, $message, 'user');

        // Update conversation activity
        $conversation->update([
            'unread_by_admin' => DB::raw('unread_by_admin + 1')
        ]);

        // Analyze message and determine response strategy
        $analysis = $this->analyzeMessage($message);

        if ($analysis['needs_admin'] || $analysis['confidence'] < self::AI_CONFIDENCE_THRESHOLD) {
            // Escalate to admin
            return $this->escalateToAdmin($conversation, $userMessage, $analysis['reason']);
        }

        // Generate AI response
        $aiResponse = $this->generateSmartResponse($message, $analysis);

        // Save AI response
        $botMessage = $this->saveMessage($conversation, null, $aiResponse['text'], 'bot');

        // If AI suggests admin might help better, include that info
        if ($analysis['confidence'] < 0.85) {
            $aiResponse['suggest_admin'] = true;
        }

        return [
            'type' => 'ai_response',
            'message' => $botMessage,
            'quick_replies' => $aiResponse['quick_replies'] ?? [],
            'suggest_admin' => $aiResponse['suggest_admin'] ?? false,
            'confidence' => $analysis['confidence']
        ];
    }

    /**
     * Analyze message to determine AI confidence and routing
     */
    protected function analyzeMessage(string $message): array
    {
        $message = strtolower(trim($message));

        // Keywords that definitely need admin
        $adminKeywords = [
            'komplain', 'refund', 'pembatalan', 'cancel', 'masalah pembayaran',
            'tidak puas', 'keluhan', 'lapor', 'berbicara dengan manusia',
            'hubungi admin', 'speak to admin', 'talk to human'
        ];

        foreach ($adminKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return [
                    'needs_admin' => true,
                    'confidence' => 0.0,
                    'reason' => 'User explicitly requested admin or mentioned sensitive topic',
                    'category' => 'admin_required'
                ];
            }
        }

        // Detect category and confidence
        $category = $this->detectCategory($message);
        $confidence = $this->calculateConfidence($message, $category);

        return [
            'needs_admin' => false,
            'confidence' => $confidence,
            'category' => $category,
            'reason' => null
        ];
    }

    /**
     * Detect message category
     */
    protected function detectCategory(string $message): string
    {
        $categories = [
            'booking' => ['booking', 'pesan', 'reservasi', 'jadwal', 'tanggal'],
            'payment' => ['bayar', 'payment', 'transfer', 'harga', 'biaya', 'cost'],
            'voucher' => ['voucher', 'diskon', 'promo', 'discount', 'kupon'],
            'location' => ['lokasi', 'alamat', 'dimana', 'where', 'location', 'address'],
            'service' => ['layanan', 'service', 'treatment', 'spa', 'yoga', 'gym'],
            'general' => ['halo', 'hello', 'hi', 'hai', 'terima kasih', 'thanks']
        ];

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $category;
                }
            }
        }

        return 'unknown';
    }

    /**
     * Calculate AI confidence score
     */
    protected function calculateConfidence(string $message, string $category): float
    {
        $baseConfidence = [
            'general' => 0.95,
            'location' => 0.90,
            'service' => 0.85,
            'booking' => 0.75,
            'payment' => 0.70,
            'voucher' => 0.80,
            'unknown' => 0.50
        ];

        $confidence = $baseConfidence[$category] ?? 0.50;

        // Reduce confidence for very long messages (complex questions)
        $wordCount = str_word_count($message);
        if ($wordCount > 20) {
            $confidence -= 0.15;
        }

        // Reduce confidence for question marks (likely needs specific answer)
        if (substr_count($message, '?') > 1) {
            $confidence -= 0.10;
        }

        return max(0.0, min(1.0, $confidence));
    }

    /**
     * Generate smart AI response with quick replies
     */
    protected function generateSmartResponse(string $message, array $analysis): array
    {
        $category = $analysis['category'];

        $responses = $this->getResponsesByCategory($category);
        $text = $responses['text'];
        $quickReplies = $responses['quick_replies'] ?? [];

        return [
            'text' => $text,
            'quick_replies' => $quickReplies
        ];
    }

    /**
     * Get responses by category with quick replies
     */
    protected function getResponsesByCategory(string $category): array
    {
        $responses = [
            'general' => [
                'text' => "Halo! Terima kasih telah menghubungi HeaLife. Saya asisten virtual yang siap membantu Anda. Ada yang bisa saya bantu?",
                'quick_replies' => [
                    'Booking Spa',
                    'Booking Yoga',
                    'Booking Gym',
                    'Lihat Promo',
                    'Hubungi Admin'
                ]
            ],
            'booking' => [
                'text' => "Untuk melakukan booking, silakan pilih layanan yang Anda inginkan. Anda bisa booking melalui website kami atau saya bantu hubungkan dengan admin untuk proses booking.",
                'quick_replies' => [
                    'Booking Spa',
                    'Booking Yoga',
                    'Booking Gym',
                    'Tanya Jadwal',
                    'Hubungi Admin'
                ]
            ],
            'payment' => [
                'text' => "HeaLife menerima pembayaran melalui transfer bank, e-wallet (GoPay, OVO, Dana), dan kartu kredit. Semua transaksi aman menggunakan Midtrans. Ada yang ingin ditanyakan tentang pembayaran?",
                'quick_replies' => [
                    'Metode Pembayaran',
                    'Cek Status Bayar',
                    'Masalah Pembayaran',
                    'Hubungi Admin'
                ]
            ],
            'voucher' => [
                'text' => "Anda bisa menggunakan voucher saat proses booking. Masukkan kode voucher di halaman pembayaran untuk mendapatkan diskon. Voucher yang sudah dipakai tidak bisa digunakan lagi.",
                'quick_replies' => [
                    'Cek Voucher Saya',
                    'Cara Pakai Voucher',
                    'Promo Aktif',
                    'Hubungi Admin'
                ]
            ],
            'location' => [
                'text' => "HeaLife memiliki cabang Spa, Yoga Studio, dan Gym di berbagai lokasi. Anda bisa cek lokasi lengkap di website kami atau saya hubungkan dengan admin untuk info lebih detail.",
                'quick_replies' => [
                    'Lokasi Spa',
                    'Lokasi Yoga',
                    'Lokasi Gym',
                    'Hubungi Admin'
                ]
            ],
            'service' => [
                'text' => "HeaLife menyediakan layanan Spa treatment, kelas Yoga, dan fasilitas Gym dengan berbagai paket. Setiap layanan memiliki harga dan benefit yang berbeda. Mau info detail tentang layanan apa?",
                'quick_replies' => [
                    'Paket Spa',
                    'Kelas Yoga',
                    'Membership Gym',
                    'Lihat Harga',
                    'Hubungi Admin'
                ]
            ],
            'unknown' => [
                'text' => "Maaf, saya kurang memahami pertanyaan Anda. Biar saya hubungkan dengan admin kami yang akan membantu Anda lebih baik.",
                'quick_replies' => [
                    'Hubungi Admin',
                    'Lihat Menu Utama'
                ]
            ]
        ];

        return $responses[$category] ?? $responses['unknown'];
    }

    /**
     * Escalate conversation to admin
     */
    protected function escalateToAdmin(ChatConversation $conversation, ChatMessage $userMessage, ?string $reason): array
    {
        // Mark as waiting for admin
        $conversation->update([
            'status' => 'waiting_admin',
            'waiting_since' => now()
        ]);

        // Try to assign to available admin
        $admin = $this->findAvailableAdmin();

        if ($admin) {
            $this->assignToAdmin($conversation, $admin->id);

            // Notify admin
            $this->notifyAdmin($admin, $conversation);

            $message = "Saya akan menghubungkan Anda dengan admin kami. Mohon tunggu sebentar, admin akan segera membalas.";
        } else {
            $message = "Saat ini semua admin sedang membantu customer lain. Anda berada di urutan antrian. Admin akan segera membalas Anda.";
        }

        // Send automated message
        $botMessage = $this->saveMessage($conversation, null, $message, 'bot');

        // Cache this conversation needs admin attention
        Cache::put('conversation_needs_admin_' . $conversation->id, true, 3600);

        return [
            'type' => 'escalated',
            'message' => $botMessage,
            'admin_assigned' => $admin ? true : false,
            'estimated_wait' => $admin ? 30 : 120 // seconds
        ];
    }

    /**
     * Find available admin using smart assignment algorithm
     */
    protected function findAvailableAdmin(): ?User
    {
        // Get all admins sorted by workload
        $admins = User::where('role', 'admin')
            ->where('is_active', true)
            ->withCount(['activeChats' => function ($query) {
                $query->where('status', 'active')
                      ->where('admin_id', '!=', null);
            }])
            ->having('active_chats_count', '<', self::MAX_CHATS_PER_ADMIN)
            ->orderBy('active_chats_count', 'asc')
            ->get();

        // Check cache for recently active admins
        foreach ($admins as $admin) {
            $lastActivity = Cache::get('admin_activity_' . $admin->id);
            if ($lastActivity && now()->diffInMinutes($lastActivity) < 5) {
                return $admin;
            }
        }

        // Return admin with least workload
        return $admins->first();
    }

    /**
     * Assign conversation to specific admin
     */
    public function assignToAdmin(ChatConversation $conversation, int $adminId): void
    {
        $conversation->update([
            'admin_id' => $adminId,
            'status' => 'active',
            'assigned_at' => now()
        ]);

        Log::info("Conversation {$conversation->id} assigned to admin {$adminId}");
    }

    /**
     * Send message from admin
     */
    public function sendAdminMessage(ChatConversation $conversation, int $adminId, string $message): ChatMessage
    {
        // Update admin activity
        Cache::put('admin_activity_' . $adminId, now(), 600); // 10 minutes

        // Save message
        $chatMessage = $this->saveMessage($conversation, $adminId, $message, 'admin');

        // Update conversation
        $conversation->update([
            'unread_by_user' => DB::raw('unread_by_user + 1'),
            'unread_by_admin' => 0
        ]);

        return $chatMessage;
    }

    /**
     * Save message to database
     */
    protected function saveMessage(ChatConversation $conversation, ?int $senderId, string $message, string $senderType): ChatMessage
    {
        return ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $senderType === 'user' ? $senderId : null,
            'admin_id' => $senderType === 'admin' ? $senderId : null,
            'sender_type' => $senderType,
            'message' => $message,
            'is_read' => false
        ]);
    }

    /**
     * Notify admin about new conversation
     */
    protected function notifyAdmin(User $admin, ChatConversation $conversation): void
    {
        // Store notification in cache
        $notifications = Cache::get('admin_notifications_' . $admin->id, []);
        $notifications[] = [
            'conversation_id' => $conversation->id,
            'user_id' => $conversation->user_id,
            'time' => now()->toDateTimeString()
        ];
        Cache::put('admin_notifications_' . $admin->id, $notifications, 3600);

        // In production, send real-time notification via WebSocket/Pusher
        Log::info("Admin {$admin->id} notified about conversation {$conversation->id}");
    }

    /**
     * Mark conversation as resolved
     */
    public function resolveConversation(ChatConversation $conversation, int $adminId): void
    {
        $conversation->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $adminId
        ]);

        // Send rating request message
        $ratingMessage = "Terima kasih telah menggunakan layanan HeaLife! Kami mohon feedback Anda tentang pelayanan kami. Silakan berikan rating 1-5 bintang.";
        $this->saveMessage($conversation, null, $ratingMessage, 'bot');

        Log::info("Conversation {$conversation->id} resolved by admin {$adminId}");
    }

    /**
     * Save chat rating
     */
    public function saveRating(ChatConversation $conversation, int $rating, ?string $feedback = null): void
    {
        $conversation->update([
            'rating' => $rating,
            'rating_feedback' => $feedback,
            'rated_at' => now()
        ]);

        Log::info("Conversation {$conversation->id} rated {$rating} stars");
    }

    /**
     * Get canned responses for admin
     */
    public function getCannedResponses(): array
    {
        return [
            'greeting' => 'Halo! Terima kasih telah menghubungi HeaLife. Saya akan membantu Anda hari ini.',
            'booking_help' => 'Untuk proses booking, saya akan bantu Anda step by step. Layanan apa yang ingin Anda booking?',
            'payment_help' => 'Untuk masalah pembayaran, bisa tolong berikan booking code Anda?',
            'voucher_help' => 'Untuk voucher, silakan berikan kode voucher Anda dan saya akan cek statusnya.',
            'closing' => 'Terima kasih! Jika ada pertanyaan lain, jangan ragu untuk chat lagi. Semoga harimu menyenangkan!',
            'transfer' => 'Baik, saya akan transfer chat Anda ke rekan admin lain yang lebih ahli di bidang ini.',
        ];
    }

    /**
     * Transfer conversation to another admin
     */
    public function transferToAdmin(ChatConversation $conversation, int $newAdminId, int $currentAdminId, string $reason): void
    {
        $conversation->update([
            'admin_id' => $newAdminId,
            'transferred_from' => $currentAdminId,
            'transferred_at' => now()
        ]);

        // Send transfer notification message
        $message = "Chat Anda telah di-transfer ke admin lain yang akan melanjutkan membantu Anda.";
        $this->saveMessage($conversation, null, $message, 'bot');

        // Notify new admin
        $newAdmin = User::find($newAdminId);
        if ($newAdmin) {
            $this->notifyAdmin($newAdmin, $conversation);
        }

        Log::info("Conversation {$conversation->id} transferred from admin {$currentAdminId} to {$newAdminId}. Reason: {$reason}");
    }

    /**
     * Get admin performance stats
     */
    public function getAdminStats(int $adminId, string $period = 'today'): array
    {
        $query = ChatConversation::where('admin_id', $adminId);

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
        }

        $conversations = $query->get();

        return [
            'total_chats' => $conversations->count(),
            'resolved_chats' => $conversations->where('status', 'resolved')->count(),
            'average_rating' => round($conversations->whereNotNull('rating')->avg('rating'), 2),
            'average_response_time' => $this->calculateAverageResponseTime($adminId, $period),
            'active_chats' => $conversations->where('status', 'active')->count()
        ];
    }

    /**
     * Calculate average response time for admin
     */
    protected function calculateAverageResponseTime(int $adminId, string $period): int
    {
        // This would calculate based on time between user message and admin reply
        // Simplified for now - return in seconds
        return 45; // 45 seconds average
    }
}
