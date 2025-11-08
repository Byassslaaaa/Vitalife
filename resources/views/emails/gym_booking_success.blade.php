<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Booking Gym - HeaLife</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            background: #fffbeb;
            border: 2px solid #f59e0b;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .booking-code {
            background: #f59e0b;
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
            color: #f59e0b;
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
            border-left: 4px solid #f59e0b;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .info-value {
            color: #f59e0b;
            font-weight: bold;
        }

        .gym-tips {
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
            <h1>💪 Booking Gym Berhasil!</h1>
            <p>Siap untuk memulai perjalanan fitness Anda?</p>
        </div>

        <div class="content">
            <h2>Halo {{ $customerName }}! 🔥</h2>

            <p>Selamat! Booking gym Anda di <strong>HeaLife Fitness Center</strong> telah berhasil dikonfirmasi.
                Saatnya mencapai goals fitness Anda!</p>

            <div class="booking-card">
                <div class="booking-code">
                    💪 Kode Booking: {{ $bookingCode }}
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">👤 Nama Member</div>
                        <div class="info-value">{{ $customerName }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value">{{ $customerEmail }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📅 Tanggal Workout</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($bookingDate)->format('d F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">⏰ Waktu</div>
                        <div class="info-value">{{ $bookingTime }}</div>
                    </div>
                    @if (isset($duration) && $duration)
                        <div class="info-item">
                            <div class="info-label">⏱️ Durasi</div>
                            <div class="info-value">{{ $duration }} jam</div>
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

                @if (isset($notes) && $notes)
                    <div style="margin-top: 15px; padding: 15px; background: #fff7ed; border-radius: 8px;">
                        <strong>📝 Catatan:</strong><br>
                        {{ $notes }}
                    </div>
                @endif
            </div>

            <div class="gym-tips">
                <h3>🏋️‍♂️ Tips untuk Workout Anda:</h3>
                <ul>
                    <li><strong>Datang 10 menit lebih awal</strong> untuk warm-up dan check-in</li>
                    <li><strong>Bawa pakaian olahraga</strong> yang nyaman dan sepatu sport</li>
                    <li><strong>Siapkan botol air</strong> untuk menjaga hidrasi selama workout</li>
                    <li><strong>Bawa handuk pribadi</strong> untuk kebersihan dan kenyamanan</li>
                    <li><strong>Jangan lupa sarapan ringan</strong> 30-60 menit sebelum workout</li>
                    <li><strong>Konsultasi dengan trainer</strong> jika Anda pemula atau memiliki program khusus</li>
                </ul>
            </div>

            <div style="background: #eff6ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>🏃‍♂️ Fasilitas Gym yang Tersedia:</h3>
                <ul>
                    <li>🏋️‍♀️ <strong>Free Weights Area</strong> - Dumbbells, barbells, dan plates lengkap</li>
                    <li>🚴‍♂️ <strong>Cardio Zone</strong> - Treadmill, elliptical, stationary bike</li>
                    <li>🤸‍♀️ <strong>Functional Training</strong> - TRX, kettlebells, battle ropes</li>
                    <li>🚿 <strong>Shower & Locker</strong> dengan amenities lengkap</li>
                    <li>🧘‍♂️ <strong>Stretching Area</strong> untuk cooldown dan flexibility</li>
                    <li>👨‍💼 <strong>Personal Trainer</strong> tersedia untuk konsultasi</li>
                </ul>
            </div>

            <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>💡 Program Fitness Recommendations:</h3>
                <ul>
                    <li>🎯 <strong>Beginner Program</strong> - 3x per minggu, focus pada form dan konsistensi</li>
                    <li>🔥 <strong>Weight Loss Program</strong> - Kombinasi cardio dan strength training</li>
                    <li>💪 <strong>Muscle Building</strong> - Progressive overload dengan compound movements</li>
                    <li>🏃‍♀️ <strong>Endurance Training</strong> - HIIT dan steady-state cardio</li>
                    <li>⚡ <strong>Functional Fitness</strong> - Movement patterns untuk daily activities</li>
                </ul>
            </div>

            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>⚠️ Gym Etiquette & Rules:</h3>
                <ul>
                    <li>Pembatalan gratis hingga <strong>2 jam sebelum</strong> sesi dimulai</li>
                    <li><strong>Wipe down equipment</strong> setelah digunakan</li>
                    <li><strong>Re-rack weights</strong> setelah selesai latihan</li>
                    <li><strong>Share equipment</strong> dan bergiliran dengan member lain</li>
                    <li><strong>No phone calls</strong> di area workout</li>
                    <li><strong>Proper attire</strong> - closed-toe shoes dan workout clothes</li>
                </ul>
            </div>

            <p>Tim trainer berpengalaman kami siap membantu Anda mencapai goals fitness. Jangan ragu untuk bertanya
                tentang form exercise, program training, atau nutrition advice!</p>

            <p>Untuk pertanyaan atau bantuan:</p>
            <ul>
                <li>📧 Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
                <li>📞 WhatsApp: +62 812-3456-7890</li>
                <li>🕰️ Jam Operasional Gym: 05:00 - 23:00 WIB</li>
            </ul>

            <p>Let's crush your fitness goals together! See you at the gym! 💪🔥</p>

            <p>Stay strong & healthy,<br>
                <strong>Tim HeaLife Fitness Center</strong>
            </p>
        </div>

        <div class="footer">
            <p>💪 <strong>HeaLife Fitness Center</strong></p>
            <p>📍 Jl. Wellness No. 123, Jakarta Selatan 12345</p>
            <p>📞 +62 21-1234-5678 | 📧 {{ $supportEmail }}</p>
            <p>🌐 <a href="{{ url('/') }}">www.healife.com</a></p>
            <hr style="margin: 15px 0;">
            <p><small>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</small></p>
        </div>
    </div>
</body>

</html>
