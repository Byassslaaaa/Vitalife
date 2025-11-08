<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Booking Yoga - HeaLife</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
            background: #faf5ff;
            border: 2px solid #8b5cf6;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .booking-code {
            background: #8b5cf6;
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
            color: #8b5cf6;
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
            border-left: 4px solid #8b5cf6;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .info-value {
            color: #8b5cf6;
            font-weight: bold;
        }

        .yoga-tips {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🧘‍♀️ Booking Yoga Berhasil!</h1>
            <p>Namaste! Perjalanan mindfulness Anda dimulai di sini</p>
        </div>

        <div class="content">
            <h2>Halo {{ $customerName }}! 🙏</h2>

            <p>Terima kasih telah memilih <strong>HeaLife Yoga Studio</strong>! Booking kelas yoga Anda telah berhasil
                dikonfirmasi.</p>

            <div class="booking-card">
                <div class="booking-code">
                    🧘‍♀️ Kode Booking: {{ $bookingCode }}
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">👤 Nama Peserta</div>
                        <div class="info-value">{{ $customerName }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value">{{ $customerEmail }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📅 Tanggal Kelas</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($bookingDate)->format('d F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">⏰ Waktu</div>
                        <div class="info-value">{{ $bookingTime }}</div>
                    </div>
                    @if (isset($classType) && $classType)
                        <div class="info-item">
                            <div class="info-label">🎯 Jenis Kelas</div>
                            <div class="info-value">{{ $classType }}</div>
                        </div>
                    @endif
                    @if (isset($participants) && $participants)
                        <div class="info-item">
                            <div class="info-label">👥 Jumlah Peserta</div>
                            <div class="info-value">{{ $participants }} orang</div>
                        </div>
                    @endif
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

                @if (isset($specialRequests) && $specialRequests)
                    <div style="margin-top: 15px; padding: 15px; background: #fff7ed; border-radius: 8px;">
                        <strong>📝 Permintaan Khusus:</strong><br>
                        {{ $specialRequests }}
                    </div>
                @endif
            </div>

            <div class="yoga-tips">
                <h3>🌟 Tips untuk Sesi Yoga Anda:</h3>
                <ul>
                    <li><strong>Datang 10 menit lebih awal</strong> untuk check-in dan persiapan mental</li>
                    <li><strong>Pakai pakaian yang nyaman</strong> dan mudah untuk bergerak</li>
                    <li><strong>Bawa botol air</strong> untuk menjaga hidrasi selama latihan</li>
                    <li><strong>Beri tahu instruktur</strong> jika Anda memiliki cedera atau kondisi khusus</li>
                    <li><strong>Jangan makan berat</strong> 2 jam sebelum kelas dimulai</li>
                    <li><strong>Bawa handuk kecil</strong> untuk comfort selama latihan</li>
                </ul>
            </div>

            <div style="background: #eff6ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>🧘‍♂️ Apa yang Perlu Dibawa:</h3>
                <ul>
                    <li>🧘‍♀️ <strong>Yoga Mat</strong> (atau sewa di studio Rp 10.000)</li>
                    <li>💧 <strong>Botol Air</strong> untuk hidrasi</li>
                    <li>🏃‍♀️ <strong>Pakaian Olahraga</strong> yang nyaman dan fleksibel</li>
                    <li>🧴 <strong>Handuk Kecil</strong> untuk kenyamanan</li>
                    <li>📱 <strong>Kode Booking</strong> ini di smartphone Anda</li>
                </ul>
            </div>

            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>⚠️ Kebijakan Studio:</h3>
                <ul>
                    <li>Pembatalan gratis hingga <strong>4 jam sebelum</strong> kelas dimulai</li>
                    <li>Late arrival maksimal <strong>5 menit</strong> setelah kelas dimulai</li>
                    <li>Mohon matikan <strong>ponsel atau set silent mode</strong> selama kelas</li>
                    <li>Respect terhadap sesama peserta dan instruktur</li>
                </ul>
            </div>

            <p>Kami sangat menantikan kehadiran Anda di studio! Tim instruktur berpengalaman kami siap membimbing
                perjalanan yoga Anda.</p>

            <p>Untuk pertanyaan atau bantuan:</p>
            <ul>
                <li>📧 Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
                <li>📞 WhatsApp: +62 812-3456-7890</li>
                <li>🕰️ Jam Operasional Studio: 05:30 - 21:00 WIB</li>
            </ul>

            <p>Namaste dan sampai jumpa di kelas! 🙏</p>

            <p>Dengan cinta dan cahaya,<br>
                <strong>Tim HeaLife Yoga Studio</strong>
            </p>
        </div>

        <div class="footer">
            <p>🧘‍♀️ <strong>HeaLife Yoga Studio</strong></p>
            <p>📍 Jl. Wellness No. 123, Jakarta Selatan 12345</p>
            <p>📞 +62 21-1234-5678 | 📧 {{ $supportEmail }}</p>
            <p>🌐 <a href="{{ url('/') }}">www.healife.com</a></p>
            <hr style="margin: 15px 0;">
            <p><small>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</small></p>
        </div>
    </div>
</body>

</html>
