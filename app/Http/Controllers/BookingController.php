<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\Yoga;
use App\Models\Gym;
use App\Models\Booking;
use App\Models\SpaService;
use App\Models\GymService;
use App\Models\YogaService;
use App\Models\SpaBooking;
use App\Models\YogaBooking;
use App\Models\GymBooking;
use App\Mail\BookingSuccessMail;
use App\Mail\YogaBookingSuccessMail;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Midtrans\Snap;

class BookingController extends Controller
{
    /**
     * Universal booking method for all service types
     */    public function book(Request $request)
    {
        // Check if user is authenticated before processing any booking
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to make a booking. Please login first.',
                'redirect' => route('login')
            ], 401);
        }

        // Determine booking type based on route or request data
        $requestPath = $request->path();
        if (str_contains($requestPath, 'gym/booking')) {
            $bookingType = 'gym';
        } elseif (str_contains($requestPath, 'yoga/booking')) {
            $bookingType = 'yoga';
        } else {
            $bookingType = $request->input('booking_type', 'spa');
        }

        // Validate booking type
        if (!in_array($bookingType, ['spa', 'yoga', 'gym'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking type'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $result = null;

            switch ($bookingType) {
                case 'spa':
                    $result = $this->processSpaBooking($request);
                    break;
                case 'yoga':
                    $result = $this->processYogaBooking($request);
                    break;
                case 'gym':
                    $result = $this->processGymBooking($request);
                    break;
            }

            DB::commit();

            // Send booking confirmation email
            $emailService = new EmailNotificationService();
            $emailSent = $emailService->sendBookingConfirmation($result['booking'], $bookingType);

            Log::info('Booking created and email notification sent', [
                'booking_id' => $result['booking']->id,
                'booking_type' => $bookingType,
                'booking_code' => $result['booking']->booking_code,
                'customer_email' => $result['booking']->customer_email,
                'email_sent' => $emailSent
            ]);

            return response()->json([
                'success' => true,
                'booking_id' => $result['booking']->id,
                'payment_token' => $result['snap_token'],
                'booking_type' => $bookingType,
                'email_sent' => $emailSent
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process Spa booking (supports both single service and multiple services)
     */
    private function processSpaBooking(Request $request)
    {
        // Support both old format (multiple services) and new format (single service)
        if ($request->has('services')) {
            // Old format with multiple services
            $request->validate([
                'spa_id' => 'required|exists:spas,id_spa',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'services' => 'required|array',
                'services.*' => 'required|exists:spa_services,id',
            ]);

            $bookingCode = 'SPA-' . strtoupper(Str::random(8));

            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $request->spa_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $total = 0;
            $itemDetails = [];

            foreach ($request->services as $serviceId) {
                $service = SpaService::findOrFail($serviceId);
                $total += $service->price;
                $itemDetails[] = [
                    'id' => $service->id,
                    'price' => (int) $service->price,
                    'quantity' => 1,
                    'name' => $service->name,
                ];
            }

            $booking->update(['total_amount' => $total]);
            $snapToken = $this->generateMidtransToken($bookingCode, $total, $booking->customer_name, $booking->customer_email, $booking->customer_phone, $itemDetails);
        } else {
            // New format with single service
            $request->validate([
                'spa_id' => 'required|exists:spas,id_spa',
                'service_id' => 'required|exists:spa_services,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date|after:today',
                'booking_time' => 'required|date_format:H:i',
                'therapist_preference' => 'nullable|in:male,female',
                'notes' => 'nullable|string|max:1000',
            ]);

            $service = SpaService::findOrFail($request->service_id);
            $bookingCode = 'SPA-' . strtoupper(Str::random(8));

            $booking = SpaBooking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $request->spa_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_date . ' ' . $request->booking_time,
                'therapist_preference' => $request->therapist_preference,
                'notes' => $request->notes,
            ]);

            $snapToken = $this->generateMidtransToken($bookingCode, $service->price, $booking->customer_name, $booking->customer_email, $booking->customer_phone, [
                'id' => $service->id,
                'price' => (int)$service->price,
                'quantity' => 1,
                'name' => $service->name,
            ]);
        }

        $booking->update(['payment_token' => $snapToken]);
        return ['booking' => $booking, 'snap_token' => $snapToken];
    }

    /**
     * Process Yoga booking (only venue booking - no therapist option)
     */
    private function processYogaBooking(Request $request)
    {
        // Support both old format and new format with services
        if ($request->has('services')) {
            // New format with multiple services
            $request->validate([
                'yoga_id' => 'required|exists:yogas,id_yoga',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'services' => 'required|array',
                'services.*' => 'required|exists:yoga_services,id',
            ]);

            $bookingCode = 'YOGA-' . strtoupper(Str::random(8));
            $yoga = Yoga::findOrFail($request->yoga_id);

            // Yoga only supports venue booking (customer comes to yoga place)
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'yoga_id' => $request->yoga_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'notes' => 'Booking Venue - Datang ke tempat yoga',
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $totalAmount = 0;
            $items = [];

            foreach ($request->services as $serviceId) {
                $service = YogaService::findOrFail($serviceId);
                $totalAmount += $service->price;

                $items[] = [
                    'id' => $service->id,
                    'price' => (int)$service->price,
                    'quantity' => 1,
                    'name' => $service->name,
                ];
            }

            $booking->update(['total_amount' => $totalAmount]);

            $snapToken = $this->generateMidtransToken($bookingCode, $totalAmount, $booking->customer_name, $booking->customer_email, $booking->customer_phone, $items);
            $booking->update(['payment_token' => $snapToken]);

            return ['booking' => $booking, 'snap_token' => $snapToken];
        } else {
            // Old format - single yoga booking
            $request->validate([
                'yoga_id' => 'required|exists:yogas,id_yoga',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'class_type_booking' => 'required|string',
                'customer_address' => 'nullable|string', // For therapist booking
            ]);

            $yoga = Yoga::findOrFail($request->yoga_id);
            $bookingCode = 'YOGA-' . strtoupper(Str::random(8));

            // Get booking type from notes field (venue vs therapist)
            $bookingType = '';
            $notes = $request->input('notes', '');
            if (strpos($notes, 'Booking Terapis') !== false) {
                $bookingType = 'terapis';
            } else {
                $bookingType = 'venue';
            }

            $booking = YogaBooking::create([
                'booking_code' => $bookingCode,
                'yoga_id' => $yoga->id_yoga,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'class_type' => $request->class_type_booking,
                'customer_address' => $bookingType === 'terapis' ? $request->customer_address : null,
                'notes' => $notes,
                'total_amount' => $yoga->harga,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $snapToken = $this->generateMidtransToken($bookingCode, $yoga->harga, $booking->customer_name, $booking->customer_email, $booking->customer_phone, [
                'id' => $yoga->id_yoga,
                'price' => (int)$yoga->harga,
                'quantity' => 1,
                'name' => $yoga->nama,
            ]);

            $booking->update(['payment_token' => $snapToken]);
            return ['booking' => $booking, 'snap_token' => $snapToken];
        }
    }

    /**
     * Process Gym booking
     */
    private function processGymBooking(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id_gym',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'service_id' => 'required|exists:gym_services,id',
        ]);

        // Check if gym_bookings table exists, if not create it
        if (!Schema::hasTable('gym_bookings')) {
            $this->createGymBookingsTable();
        }

        $service = GymService::findOrFail($request->service_id);
        $bookingCode = 'GYM-' . strtoupper(Str::random(8));

        $booking = GymBooking::create([
            'booking_code' => $bookingCode,
            'gym_id' => $request->gym_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_price' => $service->price,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $snapToken = $this->generateMidtransToken($bookingCode, $service->price, $booking->customer_name, $booking->customer_email, $booking->customer_phone, [
            'id' => $service->id,
            'price' => (int)$service->price,
            'quantity' => 1,
            'name' => $service->name,
        ]);

        $booking->update(['payment_token' => $snapToken]);
        return ['booking' => $booking, 'snap_token' => $snapToken];
    }

    /**
     * Generate Midtrans payment token
     */
    private function generateMidtransToken($orderId, $amount, $customerName, $customerEmail, $customerPhone, $itemDetails)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$amount,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'item_details' => is_array($itemDetails) && isset($itemDetails[0]) ? $itemDetails : [$itemDetails],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Get entities (spas, yogas, gyms) for booking selection
     */
    public function getEntities(Request $request)
    {
        $type = $request->input('type');

        try {
            $entities = [];

            switch ($type) {
                case 'spa':
                    $entities = Spa::where('is_active', true)
                                  ->select('id_spa as id', 'nama as name', 'alamat as address', 'harga as price')
                                  ->get();
                    break;
                case 'yoga':
                    $entities = Yoga::where('is_active', true)
                                   ->select('id_yoga as id', 'nama as name', 'lokasi as location', 'harga as price')
                                   ->get();
                    break;
                case 'gym':
                    $entities = Gym::where('is_active', true)
                                  ->select('id_gym as id', 'nama as name', 'alamat as address')
                                  ->get();
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid entity type'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'entities' => $entities,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching entities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get services for a specific type and entity
     */
    public function getServices(Request $request)
    {
        $type = $request->input('type');
        $entityId = $request->input('entity_id');

        try {
            $services = [];

            switch ($type) {
                case 'spa':
                    $services = SpaService::where('spa_id', $entityId)
                                        ->where('is_active', true)
                                        ->get();
                    break;
                case 'gym':
                    $services = GymService::where('gym_id', $entityId)
                                        ->where('is_active', true)
                                        ->get();
                    break;
            }

            return response()->json([
                'success' => true,
                'services' => $services
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gym services for a specific gym
     */
    public function getGymServices($gymId)
    {
        try {
            // Log the request for debugging
            Log::info("Getting gym services for gym ID: " . $gymId);

            $services = GymService::where('gym_id', $gymId)
                                ->where('is_active', true)
                                ->get();

            Log::info("Found " . $services->count() . " services for gym " . $gymId);

            return response()->json([
                'success' => true,
                'services' => $services
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching gym services: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching gym services: ' . $e->getMessage()
            ], 500);
        }
    }

    public function payment($id)
    {
        $booking = Booking::with('services')->findOrFail($id);
        return view('booking.payment', compact('booking'));
    }

    /**
     * Get booking details by booking code
     */
    public function getBooking($bookingCode)
    {
        try {
            $bookingType = strtolower(explode('-', $bookingCode)[0]);
            $booking = null;

            switch ($bookingType) {
                case 'spa':
                    // Try both Booking and SpaBooking models for backward compatibility
                    $booking = SpaBooking::where('booking_code', $bookingCode)->first();
                    if (!$booking) {
                        $booking = Booking::where('booking_code', $bookingCode)->first();
                    }
                    break;
                case 'yoga':
                    $booking = YogaBooking::with('yoga')->where('booking_code', $bookingCode)->firstOrFail();
                    break;
                case 'gym':
                    $booking = GymBooking::with('gym')->where('booking_code', $bookingCode)->firstOrFail();
                    break;
            }

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'booking' => $booking,
                'booking_type' => $bookingType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking($bookingCode)
    {
        try {
            $bookingType = strtolower(explode('-', $bookingCode)[0]);
            $booking = null;

            switch ($bookingType) {
                case 'spa':
                    $booking = SpaBooking::where('booking_code', $bookingCode)->first();
                    if (!$booking) {
                        $booking = Booking::where('booking_code', $bookingCode)->first();
                    }
                    break;
                case 'yoga':
                    $booking = YogaBooking::where('booking_code', $bookingCode)->firstOrFail();
                    break;
                case 'gym':
                    $booking = GymBooking::where('booking_code', $bookingCode)->firstOrFail();
                    break;
            }

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Only allow cancellation if booking is pending or confirmed
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking cannot be cancelled'
                ], 400);
            }

            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ?? '') . "\nCancelled by customer on " . now()->format('d/m/Y H:i')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling booking: ' . $e->getMessage()
            ], 500);
        }
    }

    // MIDTRANS CALLBACK / NOTIFICATION HANDLER
    public function handleMidtransCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $notif = new \Midtrans\Notification();
            $transaction = $notif->transaction_status;
            $order_id = $notif->order_id;

            // Determine booking type from order_id prefix
            $bookingType = strtolower(explode('-', $order_id)[0]);

            $booking = null;
            $emailClass = null;

            switch ($bookingType) {
                case 'spa':
                    // Try both SpaBooking and Booking models for backward compatibility
                    $booking = SpaBooking::where('booking_code', $order_id)->first();
                    if (!$booking) {
                        $booking = Booking::where('booking_code', $order_id)->first();
                    }
                    $emailClass = BookingSuccessMail::class;
                    break;
                case 'yoga':
                    $booking = YogaBooking::where('booking_code', $order_id)->first();
                    $emailClass = YogaBookingSuccessMail::class;
                    break;
                case 'gym':
                    $booking = GymBooking::where('booking_code', $order_id)->first();
                    $emailClass = BookingSuccessMail::class;
                    break;
            }

            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            // Update booking status based on transaction status
            if ($transaction == 'capture' || $transaction == 'settlement') {
                $booking->status = 'confirmed';
                $booking->payment_status = 'paid';
                $booking->save();

                // Send success email using EmailNotificationService
                if ($booking->customer_email) {
                    $emailService = new EmailNotificationService();
                    $emailSent = $emailService->sendPaymentSuccessNotification($booking, $bookingType);

                    Log::info('Payment success email notification', [
                        'booking_code' => $booking->booking_code,
                        'booking_type' => $bookingType,
                        'email' => $booking->customer_email,
                        'email_sent' => $emailSent
                    ]);
                }
            } elseif ($transaction == 'pending') {
                $booking->status = 'pending';
                $booking->payment_status = 'pending';
                $booking->save();
            } else { // failed, cancel, expire, deny
                $booking->status = 'cancelled';
                $booking->payment_status = 'failed';
                $booking->save();
            }

            $booking->payment_details = json_encode($notif);
            $booking->save();

            return response()->json(['message' => 'Notification handled'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error handling notification: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create gym_bookings table if it doesn't exist
     */
    private function createGymBookingsTable()
    {
        Schema::create('gym_bookings', function ($table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('gym_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->decimal('service_price', 10, 2);
            $table->string('status');
            $table->string('payment_status');
            $table->string('payment_token')->nullable();
            $table->datetime('booking_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();

            // Add foreign keys if the referenced tables exist
            if (Schema::hasTable('gyms')) {
                $table->foreign('gym_id')->references('id_gym')->on('gyms')->onDelete('cascade');
            }

            if (Schema::hasTable('gym_services')) {
                $table->foreign('service_id')->references('id')->on('gym_services')->onDelete('cascade');
            }
        });
    }

    public function confirmation($id)
    {
        $booking = Booking::with('services')->findOrFail($id);
        return view('booking.confirmation', compact('booking'));
    }

    /**
     * Create spa payment (for backward compatibility with spa-detail form)
     */
    public function createSpaPayment(Request $request)
    {
        try {
            // Check if user is authenticated before processing payment
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to make a booking. Please login first.',
                    'redirect' => route('login')
                ], 401);
            }

            // For legacy spa-detail forms that don't have service_id
            // Create a booking entry in the old format
            $request->validate([
                'spa_id' => 'required|exists:spas,id_spa',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'service_type' => 'required|string',
                'booking_type' => 'required|in:venue,terapis',
                'service_address' => 'required|string',
                'total_amount' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            $totalAmount = $request->total_amount;
            $adminFee = 5000;
            $servicePrice = $totalAmount - $adminFee;

            // Create legacy booking record with all required fields
            // Note: booking_type info is stored in notes for now until migration is added
            $bookingTypeNote = $request->booking_type === 'terapis' ? '[TERAPIS BOOKING] ' : '[VENUE BOOKING] ';
            $notesWithBookingType = $bookingTypeNote . ($request->notes ?? '');

            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $request->spa_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'service_type' => $request->service_type,
                'service_price' => $servicePrice,
                'admin_fee' => $adminFee,
                'total_amount' => $totalAmount,
                'service_address' => $request->service_address,
                'notes' => $notesWithBookingType,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Generate Midtrans token
            $itemDetails = [
                [
                    'id' => 'spa-service',
                    'price' => (int) $servicePrice,
                    'quantity' => 1,
                    'name' => $request->service_type,
                ],
                [
                    'id' => 'admin-fee',
                    'price' => (int) $adminFee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ]
            ];

            $snapToken = $this->generateMidtransToken(
                $bookingCode,
                $totalAmount,
                $booking->customer_name,
                $booking->customer_email,
                $booking->customer_phone,
                $itemDetails
            );

            $booking->update(['midtrans_transaction_id' => $snapToken]);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment_token' => $snapToken,
                'order_id' => $bookingCode,
                'total_amount' => $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating spa payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create yoga payment (for direct yoga booking)
     */
    public function createYogaPayment(Request $request)
    {
        try {
            // Check if user is authenticated before processing payment
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to make a booking. Please login first.',
                    'redirect' => route('login')
                ], 401);
            }

            // For yoga-detail forms
            $request->validate([
                'yoga_id' => 'required|exists:yogas,id_yoga',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required',
                'class_type_booking' => 'required|string',
                'booking_type' => 'required|in:venue,terapis',
                'total_amount' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $bookingCode = 'YOGA-' . strtoupper(Str::random(8));
            $totalAmount = $request->total_amount;
            $adminFee = 5000;
            $servicePrice = $totalAmount - $adminFee;

            // Create booking record with all required fields
            // Note: booking_type info is stored in notes for now until migration is added
            $bookingTypeNote = $request->booking_type === 'terapis' ? '[TERAPIS BOOKING] ' : '[VENUE BOOKING] ';
            $notesWithBookingType = $bookingTypeNote . ($request->notes ?? '');

            $booking = YogaBooking::create([
                'booking_code' => $bookingCode,
                'yoga_id' => $request->yoga_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'class_type' => $request->class_type_booking,
                'notes' => $notesWithBookingType,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Generate Midtrans token
            $itemDetails = [
                [
                    'id' => 'yoga-class',
                    'price' => (int) $servicePrice,
                    'quantity' => 1,
                    'name' => $request->class_type_booking,
                ],
                [
                    'id' => 'admin-fee',
                    'price' => (int) $adminFee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ]
            ];

            $snapToken = $this->generateMidtransToken(
                $bookingCode,
                $totalAmount,
                $booking->customer_name,
                $booking->customer_email,
                $booking->customer_phone,
                $itemDetails
            );

            $booking->update(['payment_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment_token' => $snapToken,
                'booking_code' => $bookingCode,
                'total_amount' => $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating yoga payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
