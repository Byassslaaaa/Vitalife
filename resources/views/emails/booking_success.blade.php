<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Booking Berhasil</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e1e1e1;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-size: 14px;
            color: #666;
        }

        .booking-card {
            background: #f0fdf4;
            border: 2px solid #10b981;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .booking-code {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            margin: 15px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .info-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .info-value {
            color: #10b981;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>✅ Booking Berhasil Dikonfirmasi!</h1>
            <p>Terima kasih telah mempercayai Vitalife untuk kebutuhan wellness Anda</p>
        </div>

        <div class="content">
            <h2>Halo {{ $customerName }}! 👋</h2>

            <p>Selamat! Booking Anda telah berhasil dikonfirmasi. Berikut adalah detail booking Anda:</p>

            <div class="booking-card">
                <div class="booking-code">
                    📋 Kode Booking: {{ $bookingCode }}
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">👤 Nama Customer</div>
                        <div class="info-value">{{ $customerName }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value">{{ $customerEmail }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📅 Tanggal Booking</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($bookingDate)->format('d F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">⏰ Waktu</div>
                        <div class="info-value">{{ $bookingTime }}</div>
                    </div>
                </div>

                <div style="text-align: center; margin: 20px 0;">
                    <div class="amount">💰 Total: Rp {{ $totalAmount }}</div>
                </div>

                <div style="text-align: center;">
                    <span class="status-badge {{ $status == 'confirmed' ? 'status-confirmed' : 'status-pending' }}">
                        Status: {{ ucfirst($status) }}
                    </span>
                    @if (isset($paymentStatus))
                        <span
                            class="status-badge {{ $paymentStatus == 'paid' ? 'status-confirmed' : 'status-pending' }}">
                            Payment: {{ ucfirst($paymentStatus) }}
                        </span>
                    @endif
                </div>

                @if (isset($paymentMethod) && $paymentMethod)
                    <div style="margin-top: 15px;">
                        <strong>💳 Metode Pembayaran:</strong> {{ $paymentMethod }}
                    </div>
                @endif

                @if (isset($notes) && $notes)
                    <div style="margin-top: 15px; padding: 15px; background: #fff7ed; border-radius: 8px;">
                        <strong>📝 Catatan:</strong><br>
                        {{ $notes }}
                    </div>
                @endif
            </div>

            <div style="background: #eff6ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>📋 Instruksi Penting:</h3>
                <ul>
                    <li>Harap datang <strong>15 menit sebelum</strong> waktu booking</li>
                    <li>Bawa <strong>ID/KTP</strong> dan simpan kode booking ini</li>
                    <li>Hubungi kami jika ada perubahan jadwal</li>
                    <li>Pembatalan gratis hingga <strong>2 jam sebelum</strong> sesi dimulai</li>
                </ul>
            </div>

            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>⚠️ Kebijakan Pembatalan:</h3>
                <p>Pembatalan kurang dari 2 jam sebelum sesi akan dikenakan biaya 50% dari total booking. Ketidakhadiran
                    tanpa pemberitahuan akan dikenakan biaya penuh.</p>
            </div>

            <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi customer service
                kami:</p>
            <ul>
                <li>📧 Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
                <li>📞 WhatsApp: +62 812-3456-7890</li>
                <li>⏰ Jam Operasional: 06:00 - 22:00 WIB</li>
            </ul>

            <p>Terima kasih telah memilih Vitalife. Nikmati pengalaman wellness terbaik bersama kami! 🌟</p>

            <p>Salam sehat,<br>
                <strong>Tim Vitalife</strong>
            </p>
        </div>

        <div class="footer">
            <p>🏢 <strong>Vitalife Wellness Center</strong></p>
            <p>📍 Jl. Wellness No. 123, Jakarta Selatan 12345</p>
            <p>📞 +62 21-1234-5678 | 📧 {{ $supportEmail }}</p>
            <p>🌐 <a href="{{ url('/') }}">www.vitalife.com</a></p>
            <hr style="margin: 15px 0;">
            <p><small>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</small></p>
        </div>
    </div>
</body>

</html>
