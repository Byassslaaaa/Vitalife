<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\SpaService;
use App\Models\SpaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SpaBookingController extends Controller
{
    /**
     * Store a new spa booking
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'spa_id' => 'required|exists:spas,id_spa',
            'service_id' => 'required|exists:spa_services,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
            'therapist_preference' => 'nullable|in:male,female',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            // Get service details
            $service = SpaService::findOrFail($validatedData['service_id']);
            
            // Generate unique booking code
            $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            while (SpaBooking::where('booking_code', $bookingCode)->exists()) {
                $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            }

            // Create booking
            $booking = SpaBooking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $validatedData['spa_id'],
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'],
                'customer_phone' => $validatedData['customer_phone'],
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'booking_date' => $validatedData['booking_date'],
                'booking_time' => $validatedData['booking_date'] . ' ' . $validatedData['booking_time'],
                'therapist_preference' => $validatedData['therapist_preference'],
                'notes' => $validatedData['notes'],
            ]);

            DB::commit();

            return redirect()->route('spa.booking.show', $booking->booking_code)
                           ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store booking via API (for AJAX requests)
     */
    public function apiStore(Request $request)
    {
        $validatedData = $request->validate([
            'spa_id' => 'required|exists:spas,id_spa',
            'service_id' => 'required|exists:spa_services,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|string',
            'therapist_preference' => 'nullable|in:male,female',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        
        try {
            // Get service details
            $service = SpaService::findOrFail($validatedData['service_id']);
            
            // Generate unique booking code
            $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            while (SpaBooking::where('booking_code', $bookingCode)->exists()) {
                $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            }

            // Create booking
            $booking = SpaBooking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $validatedData['spa_id'],
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'],
                'customer_phone' => $validatedData['customer_phone'],
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'booking_date' => $validatedData['booking_date'],
                'booking_time' => $validatedData['booking_date'] . ' ' . $validatedData['booking_time'],
                'therapist_preference' => $validatedData['therapist_preference'],
                'notes' => $validatedData['notes'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat!',
                'booking_code' => $booking->booking_code,
                'redirect_url' => route('spa.booking.show', $booking->booking_code)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking
     */
    public function show($bookingCode)
    {
        $booking = SpaBooking::with(['spa', 'service'])
                            ->where('booking_code', $bookingCode)
                            ->firstOrFail();

        return view('spa.booking.show', compact('booking'));
    }

    /**
     * Show payment page for booking
     */
    public function payment($bookingCode)
    {
        $booking = SpaBooking::with(['spa', 'service'])
                            ->where('booking_code', $bookingCode)
                            ->where('payment_status', 'pending')
                            ->firstOrFail();

        // Initialize Midtrans payment
        $paymentData = $this->initializeMidtransPayment($booking);

        return view('spa.booking.payment', compact('booking', 'paymentData'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $bookingCode)
    {
        $booking = SpaBooking::where('booking_code', $bookingCode)->firstOrFail();
        
        // Only allow cancellation if booking is pending or confirmed
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'notes' => ($booking->notes ?? '') . "\nDibatalkan oleh customer pada " . now()->format('d/m/Y H:i')
        ]);

        return redirect()->route('spa.bookings.user')
                        ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Get user's bookings
     */
    public function userBookings()
    {
        $user = auth()->user();
        
        $bookings = SpaBooking::with(['spa', 'service'])
                             ->where(function ($query) use ($user) {
                                 $query->where('customer_email', $user->email)
                                       ->orWhere('customer_phone', $user->phone);
                             })
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        return view('spa.booking.user-bookings', compact('bookings'));
    }

    /**
     * Get booking status (API)
     */
    public function getBookingStatus($bookingCode)
    {
        $booking = SpaBooking::where('booking_code', $bookingCode)->firstOrFail();

        return response()->json([
            'booking_code' => $booking->booking_code,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'formatted_datetime' => $booking->formatted_booking_date_time,
        ]);
    }

    /**
     * Handle Midtrans payment callback
     */
    public function handleMidtransCallback(Request $request)
    {
        // Verify Midtrans signature
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find booking by order ID (booking code)
        $booking = SpaBooking::where('booking_code', $request->order_id)->first();
        
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update payment status based on Midtrans response
        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'payment_token' => $request->transaction_id
                ]);
                break;
            
            case 'pending':
                $booking->update([
                    'payment_status' => 'pending',
                    'payment_token' => $request->transaction_id
                ]);
                break;
            
            case 'deny':
            case 'expire':
            case 'cancel':
                $booking->update([
                    'payment_status' => 'failed',
                    'payment_token' => $request->transaction_id
                ]);
                break;
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Initialize Midtrans payment
     */
    private function initializeMidtransPayment(SpaBooking $booking)
    {
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => $booking->service_price,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => $booking->service_id,
                    'price' => $booking->service_price,
                    'quantity' => 1,
                    'name' => $booking->service_name . ' - ' . $booking->spa->nama,
                ]
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return [
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key')
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to initialize payment: ' . $e->getMessage());
        }
    }
}