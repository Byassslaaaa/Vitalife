<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Yoga Berhasil</title>
</head>
<body>
    <h2>Hi, {{ $booking->customer_name }}</h2>
    <p>Terima kasih telah melakukan booking yoga di {{ $booking->yoga->nama }}.</p>
    <p>Pembayaran Anda telah <b>berhasil</b>.</p>
    <ul>
        <li>Nama Yoga: {{ $booking->yoga->nama }}</li>
        <li>Tanggal: {{ $booking->booking_date }}</li>
        <li>Jam: {{ $booking->booking_time }}</li>
        <li>Tipe Kelas: {{ $booking->class_type }}</li>
        <li>Total: Rp {{ number_format($booking->total_amount,0,',','.') }}</li>
    </ul>
    <p>Silakan datang sesuai jadwal yang sudah dipilih. Sampai jumpa!</p>
</body>
</html>