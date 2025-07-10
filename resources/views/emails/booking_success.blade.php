<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>Booking Confirmation</h2>
    <p>Dear {{ $booking->customer_name }},</p>
    <p>Your booking with the following details has been confirmed:</p>
    <ul>
        <li><strong>Booking Code:</strong> {{ $booking->booking_code }}</li>
        <li><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
        <li><strong>Date & Time:</strong> {{ date('d M Y', strtotime($booking->booking_date)) }} at {{ $booking->booking_time }}</li>
    </ul>
    <h3>Services:</h3>
    <ul>
        @foreach($booking->services as $service)
            <li>{{ $service->service_name }} (Rp {{ number_format($service->price, 0, ',', '.') }})</li>
        @endforeach
    </ul>
    <p><strong>Total:</strong> Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
    <br>
    <p>Thank you for your booking.</p>
</body>
</html> 