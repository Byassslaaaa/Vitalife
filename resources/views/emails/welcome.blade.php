<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #4a5568;">Selamat Datang, {{ $user->name }}!</h1>
        <p>Terima kasih telah mendaftar di website kami. Kami sangat senang Anda bergabung dengan komunitas kami.</p>
        <p>Untuk memulai perjalanan Anda, silakan klik tombol di bawah ini:</p>
        <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
            <a href="{{ url('/dashboard') }}"
                style="background-color: #4299e1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Get
                Started</a>
        </div>
        <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim dukungan kami.</p>
        <p>Salam hangat,<br>Tim Website</p>
    </div>
</body>

</html>
