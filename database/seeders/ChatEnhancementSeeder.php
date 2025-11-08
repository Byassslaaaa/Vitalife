<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatEnhancementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed canned responses for admin
        $cannedResponses = [
            [
                'title' => 'Greeting - Welcome',
                'message' => 'Halo! Terima kasih telah menghubungi HeaLife. Saya akan membantu Anda hari ini. Ada yang bisa saya bantu?',
                'category' => 'greeting',
                'shortcut' => '/hello',
                'is_active' => true,
            ],
            [
                'title' => 'Booking - How to Book',
                'message' => 'Untuk melakukan booking, silakan kunjungi halaman layanan yang Anda inginkan (Spa/Yoga/Gym), pilih jadwal dan treatment, lalu lanjutkan ke pembayaran. Jika ada kesulitan, saya siap membantu!',
                'category' => 'booking',
                'shortcut' => '/bookhelp',
                'is_active' => true,
            ],
            [
                'title' => 'Booking - Get Booking Code',
                'message' => 'Untuk mengecek booking Anda, bisa tolong berikan booking code yang Anda terima via email?',
                'category' => 'booking',
                'shortcut' => '/getcode',
                'is_active' => true,
            ],
            [
                'title' => 'Payment - Status Check',
                'message' => 'Saya akan bantu cek status pembayaran Anda. Mohon berikan booking code Anda.',
                'category' => 'payment',
                'shortcut' => '/paystatus',
                'is_active' => true,
            ],
            [
                'title' => 'Payment - Methods',
                'message' => 'HeaLife menerima pembayaran via Transfer Bank, E-wallet (GoPay, OVO, Dana, LinkAja, ShopeePay), dan Kartu Kredit/Debit. Semua transaksi aman melalui Midtrans.',
                'category' => 'payment',
                'shortcut' => '/paymethods',
                'is_active' => true,
            ],
            [
                'title' => 'Payment - Failed',
                'message' => 'Untuk pembayaran yang gagal, mohon coba lagi dengan metode pembayaran yang berbeda. Jika masih bermasalah, booking Anda akan otomatis dibatalkan dalam 24 jam dan Anda bisa booking ulang.',
                'category' => 'payment',
                'shortcut' => '/payfail',
                'is_active' => true,
            ],
            [
                'title' => 'Voucher - How to Use',
                'message' => 'Untuk menggunakan voucher, masukkan kode voucher pada halaman pembayaran sebelum menyelesaikan transaksi. Diskon akan otomatis teraplikasi.',
                'category' => 'voucher',
                'shortcut' => '/voucheruse',
                'is_active' => true,
            ],
            [
                'title' => 'Voucher - Check Status',
                'message' => 'Saya akan bantu cek voucher Anda. Mohon berikan kode vouchernya.',
                'category' => 'voucher',
                'shortcut' => '/vouchercheck',
                'is_active' => true,
            ],
            [
                'title' => 'Cancellation - Policy',
                'message' => 'Pembatalan booking dapat dilakukan maksimal 24 jam sebelum jadwal. Untuk pembatalan, silakan hubungi kami dengan menyertakan booking code Anda.',
                'category' => 'cancellation',
                'shortcut' => '/cancel',
                'is_active' => true,
            ],
            [
                'title' => 'Reschedule - How to',
                'message' => 'Untuk reschedule booking, mohon berikan booking code Anda dan jadwal baru yang diinginkan. Reschedule dapat dilakukan maksimal 48 jam sebelum jadwal.',
                'category' => 'reschedule',
                'shortcut' => '/reschedule',
                'is_active' => true,
            ],
            [
                'title' => 'Location - Information',
                'message' => 'HeaLife memiliki berbagai cabang di Jakarta, Bandung, dan Surabaya. Untuk info lengkap lokasi dan jam operasional, silakan kunjungi halaman "Lokasi" di website kami.',
                'category' => 'location',
                'shortcut' => '/location',
                'is_active' => true,
            ],
            [
                'title' => 'Closing - Thank You',
                'message' => 'Terima kasih telah menghubungi HeaLife! Jika ada pertanyaan lain, jangan ragu untuk chat kembali. Semoga harimu menyenangkan! 😊',
                'category' => 'closing',
                'shortcut' => '/thanks',
                'is_active' => true,
            ],
            [
                'title' => 'Closing - Resolved',
                'message' => 'Senang bisa membantu! Apakah ada yang bisa saya bantu lagi? Jika sudah selesai, saya akan menutup percakapan ini.',
                'category' => 'closing',
                'shortcut' => '/resolve',
                'is_active' => true,
            ],
            [
                'title' => 'Wait - Checking',
                'message' => 'Mohon tunggu sebentar, saya sedang mengecek informasi untuk Anda...',
                'category' => 'wait',
                'shortcut' => '/wait',
                'is_active' => true,
            ],
            [
                'title' => 'Transfer - To Specialist',
                'message' => 'Untuk kasus ini, saya akan transfer Anda ke rekan admin yang lebih ahli. Mohon tunggu sebentar.',
                'category' => 'transfer',
                'shortcut' => '/transfer',
                'is_active' => true,
            ],
        ];

        foreach ($cannedResponses as $response) {
            DB::table('canned_responses')->insert(array_merge($response, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        // Seed quick replies for bot
        $quickReplies = [
            // General category
            ['text' => 'Booking Spa', 'category' => 'general', 'action' => 'navigate', 'payload' => json_encode(['url' => '/spa']), 'display_order' => 1],
            ['text' => 'Booking Yoga', 'category' => 'general', 'action' => 'navigate', 'payload' => json_encode(['url' => '/yoga']), 'display_order' => 2],
            ['text' => 'Booking Gym', 'category' => 'general', 'action' => 'navigate', 'payload' => json_encode(['url' => '/gym']), 'display_order' => 3],
            ['text' => 'Lihat Promo', 'category' => 'general', 'action' => 'navigate', 'payload' => json_encode(['url' => '/vouchers']), 'display_order' => 4],
            ['text' => 'Hubungi Admin', 'category' => 'general', 'action' => 'escalate', 'payload' => null, 'display_order' => 5],

            // Booking category
            ['text' => 'Tanya Jadwal', 'category' => 'booking', 'action' => 'ask', 'payload' => json_encode(['intent' => 'schedule']), 'display_order' => 1],
            ['text' => 'Lihat Harga', 'category' => 'booking', 'action' => 'ask', 'payload' => json_encode(['intent' => 'pricing']), 'display_order' => 2],
            ['text' => 'Cara Booking', 'category' => 'booking', 'action' => 'ask', 'payload' => json_encode(['intent' => 'how_to_book']), 'display_order' => 3],

            // Payment category
            ['text' => 'Metode Pembayaran', 'category' => 'payment', 'action' => 'ask', 'payload' => json_encode(['intent' => 'payment_methods']), 'display_order' => 1],
            ['text' => 'Cek Status Bayar', 'category' => 'payment', 'action' => 'ask', 'payload' => json_encode(['intent' => 'payment_status']), 'display_order' => 2],
            ['text' => 'Masalah Pembayaran', 'category' => 'payment', 'action' => 'escalate', 'payload' => null, 'display_order' => 3],

            // Voucher category
            ['text' => 'Cek Voucher Saya', 'category' => 'voucher', 'action' => 'navigate', 'payload' => json_encode(['url' => '/profile/vouchers']), 'display_order' => 1],
            ['text' => 'Cara Pakai Voucher', 'category' => 'voucher', 'action' => 'ask', 'payload' => json_encode(['intent' => 'how_to_use_voucher']), 'display_order' => 2],
            ['text' => 'Promo Aktif', 'category' => 'voucher', 'action' => 'navigate', 'payload' => json_encode(['url' => '/vouchers']), 'display_order' => 3],

            // Location category
            ['text' => 'Lokasi Spa', 'category' => 'location', 'action' => 'navigate', 'payload' => json_encode(['url' => '/spa']), 'display_order' => 1],
            ['text' => 'Lokasi Yoga', 'category' => 'location', 'action' => 'navigate', 'payload' => json_encode(['url' => '/yoga']), 'display_order' => 2],
            ['text' => 'Lokasi Gym', 'category' => 'location', 'action' => 'navigate', 'payload' => json_encode(['url' => '/gym']), 'display_order' => 3],

            // Service category
            ['text' => 'Paket Spa', 'category' => 'service', 'action' => 'navigate', 'payload' => json_encode(['url' => '/spa']), 'display_order' => 1],
            ['text' => 'Kelas Yoga', 'category' => 'service', 'action' => 'navigate', 'payload' => json_encode(['url' => '/yoga']), 'display_order' => 2],
            ['text' => 'Membership Gym', 'category' => 'service', 'action' => 'navigate', 'payload' => json_encode(['url' => '/gym']), 'display_order' => 3],
            ['text' => 'Lihat Harga', 'category' => 'service', 'action' => 'ask', 'payload' => json_encode(['intent' => 'pricing']), 'display_order' => 4],

            // Unknown category
            ['text' => 'Hubungi Admin', 'category' => 'unknown', 'action' => 'escalate', 'payload' => null, 'display_order' => 1],
            ['text' => 'Lihat Menu Utama', 'category' => 'unknown', 'action' => 'navigate', 'payload' => json_encode(['url' => '/home']), 'display_order' => 2],
        ];

        foreach ($quickReplies as $reply) {
            DB::table('quick_replies')->insert(array_merge($reply, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('Chat enhancement data seeded successfully!');
    }
}
