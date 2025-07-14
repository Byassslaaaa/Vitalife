<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di Vitalife</title>
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
        }

        .highlight {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Selamat Datang di Vitalife!</h1>
            <p>Platform Wellness Terbaik untuk Hidup Sehat Anda</p>
        </div>

        <div class="content">
            <h2>Halo {{ $userName }}! 👋</h2>

            <p>Selamat datang di <strong>Vitalife</strong> - platform wellness terpercaya yang akan mengubah gaya hidup
                Anda menjadi lebih sehat dan bahagia!</p>

            <div class="highlight">
                <h3>✨ Apa yang bisa Anda nikmati di Vitalife:</h3>
                <ul>
                    <li>🏋️‍♂️ <strong>Gym Premium</strong> - Fasilitas fitness lengkap dengan peralatan terbaru</li>
                    <li>🧘‍♀️ <strong>Yoga Classes</strong> - Kelas yoga untuk ketenangan jiwa dan raga</li>
                    <li>💆‍♀️ <strong>Spa & Wellness</strong> - Treatmen relaksasi untuk kesegaran optimal</li>
                    <li>📱 <strong>Booking Mudah</strong> - Sistem reservasi online yang praktis</li>
                    <li>🎯 <strong>Personal Trainer</strong> - Pendampingan ahli untuk hasil maksimal</li>
                </ul>
            </div>

            <p><strong>Informasi Akun Anda:</strong></p>
            <ul>
                <li>📧 Email: {{ $userEmail }}</li>
                <li>📞 No. HP: {{ $userPhone ?? 'Belum diisi' }}</li>
                <li>📅 Tanggal Daftar: {{ now()->format('d F Y') }}</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="btn">🚀 Mulai Perjalanan Wellness Anda</a>
            </div>

            <div class="highlight">
                <h3>🎁 Bonus Khusus Member Baru!</h3>
                <p>Dapatkan <strong>diskon 20%</strong> untuk booking pertama Anda di semua layanan. Gunakan kode:
                    <strong>WELCOME20</strong></p>
            </div>

            <p>Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi tim customer service kami
                di <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> atau melalui WhatsApp di +62
                812-3456-7890.</p>

            <p>Mari bersama-sama menciptakan gaya hidup sehat yang berkelanjutan! 💪</p>

            <p>Salam hangat,<br>
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
