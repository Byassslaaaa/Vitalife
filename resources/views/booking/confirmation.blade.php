<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-8 text-center">
                <h2 class="text-2xl font-bold mb-4 text-green-700">Booking Successful!</h2>
                <p class="mb-6">Thank you, <span class="font-semibold">{{ $booking->customer_name }}</span>.<br>
                Your booking has been received with details below:</p>

                <div class="mb-4 text-left">
                    <p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
                    <p><strong>Status:</strong>
                        @if($booking->status == 'confirmed')
                            <span class="text-green-700 font-semibold">Confirmed</span>
                        @elseif($booking->status == 'pending')
                            <span class="text-green-600 font-semibold">Done</span>
                        @else
                            <span class="text-red-600 font-semibold">Cancelled</span>
                        @endif
                    </p>
                    <p><strong>Booking Date & Time:</strong> {{ date('d M Y', strtotime($booking->booking_date)) }} at {{ $booking->booking_time }}</p>
                </div>
                <div class="mb-4 text-left">
                    <h3 class="font-semibold mb-2">Services:</h3>
                    <ul>
                        @foreach($booking->services as $service)
                            <li>- {{ $service->service_name }} (Rp {{ number_format($service->price, 0, ',', '.') }})</li>
                        @endforeach
                    </ul>
                </div>
                <div class="text-xl font-bold mt-6">
                    Total: Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                </div>
                <div class="mt-6">
                    <a href="/" class="text-blue-600 underline">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>