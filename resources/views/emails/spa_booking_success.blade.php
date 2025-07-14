<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Booking Spa - Vitalife</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
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
            background: #fdf2f8;
            border: 2px solid #ec4899;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .booking-code {
            background: #ec4899;
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
            color: #ec4899;
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
            border-left: 4px solid #ec4899;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .info-value {
            color: #ec4899;
            font-weight: bold;
        }

        .spa-tips {
            background: #fff7ed;
            border: 1px solid #f97316;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>💆‍♀️ Booking Spa Berhasil!</h1>
            <p>Siap-siap untuk pengalaman relaksasi yang tak terlupakan</p>
        </div>

        <div class="content">
            <h2>Halo {{ $customerName }}! 🌸</h2>

            <p>Terima kasih telah memilih <strong>Vitalife Spa & Wellness</strong>! Booking treatment spa Anda telah
                berhasil dikonfirmasi.</p>

            <div class="booking-card">
                <div class="booking-code">
                    💆‍♀️ Kode Booking: {{ $bookingCode }}
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">👤 Nama Tamu</div>
                        <div class="info-value">{{ $customerName }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📧 Email</div>
                        <div class="info-value">{{ $customerEmail }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📅 Tanggal Treatment</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($bookingDate)->format('d F Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">⏰ Waktu</div>
                        <div class="info-value">{{ $bookingTime }}</div>
                    </div>
                    @if (isset($duration) && $duration)
                        <div class="info-item">
                            <div class="info-label">⏱️ Durasi</div>
                            <div class="info-value">{{ $duration }} menit</div>
                        </div>
                    @endif
                    @if (isset($therapistPreference) && $therapistPreference)
                        <div class="info-item">
                            <div class="info-label">👩‍⚕️ Terapis</div>
                            <div class="info-value">{{ $therapistPreference }}</div>
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
                        <strong>📝 Catatan Khusus:</strong><br>
                        {{ $notes }}
                    </div>
                @endif
            </div>

            <div class="spa-tips">
                <h3>🌟 Tips untuk Treatment Spa Anda:</h3>
                <ul>
                    <li><strong>Datang 15 menit lebih awal</strong> untuk konsultasi dan persiapan</li>
                    <li><strong>Mandi sebelum treatment</strong> untuk kebersihan optimal</li>
                    <li><strong>Informasikan kondisi kesehatan</strong> dan alergi kepada terapis</li>
                    <li><strong>Lepas semua perhiasan</strong> sebelum treatment dimulai</li>
                    <li><strong>Hindari makan berat</strong> 1 jam sebelum treatment</li>
                    <li><strong>Minum air putih</strong> untuk detoksifikasi setelah treatment</li>
                </ul>
            </div>

            <div style="background: #eff6ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>🛁 Fasilitas yang Tersedia:</h3>
                <ul>
                    <li>🚿 <strong>Private Shower</strong> dengan amenities premium</li>
                    <li>🥼 <strong>Spa Robe & Sandal</strong> disediakan</li>
                    <li>🧴 <strong>Premium Spa Products</strong> untuk treatment</li>
                    <li>🍵 <strong>Welcome Drink</strong> herbal tea atau infused water</li>
                    <li>🎵 <strong>Relaxing Music</strong> dan aromatherapy</li>
                    <li>🛌 <strong>Relaxation Area</strong> untuk istirahat setelah treatment</li>
                </ul>
            </div>

            <div style="background: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>⚠️ Kebijakan Spa:</h3>
                <ul>
                    <li>Pembatalan gratis hingga <strong>6 jam sebelum</strong> appointment</li>
                    <li>Late arrival akan mengurangi <strong>durasi treatment</strong></li>
                    <li>Mohon <strong>tidak menggunakan parfum</strong> berlebihan</li>
                    <li>Treatment tidak dianjurkan untuk <strong>ibu hamil trimester pertama</strong></li>
                    <li>Informasikan jika sedang <strong>menstruasi atau sakit</strong></li>
                </ul>
            </div>

            <p>Tim terapis profesional kami dengan pengalaman lebih dari 5 tahun siap memberikan treatment terbaik untuk
                Anda. Nikmati momen relaksasi yang tak terlupakan!</p>

            <p>Untuk pertanyaan atau bantuan:</p>
            <ul>
                <li>📧 Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
                <li>📞 WhatsApp: +62 812-3456-7890</li>
                <li>🕰️ Jam Operasional Spa: 09:00 - 21:00 WIB</li>
            </ul>

            <p>Kami menantikan kedatangan Anda untuk pengalaman spa yang menyegarkan! 🌺</p>

            <p>Dengan hangat,<br>
                <strong>Tim Vitalife Spa & Wellness</strong>
            </p>
        </div>

        <div class="footer">
            <p>💆‍♀️ <strong>Vitalife Spa & Wellness Center</strong></p>
            <p>📍 Jl. Wellness No. 123, Jakarta Selatan 12345</p>
            <p>📞 +62 21-1234-5678 | 📧 {{ $supportEmail }}</p>
            <p>🌐 <a href="{{ url('/') }}">www.vitalife.com</a></p>
            <hr style="margin: 15px 0;">
            <p><small>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</small></p>
        </div>
    </div>
</body>

</html>
