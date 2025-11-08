<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\EmailNotificationService;
use App\Models\SpaService;
use App\Models\YogaService;
use App\Models\GymService;
use App\Models\Spa;
use App\Models\Yoga;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $bookingService;
    protected $paymentService;
    protected $emailService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        EmailNotificationService $emailService
    ) {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->emailService = $emailService;

        // Note: Authentication is handled at route level or method level
        // Guest bookings are allowed for spa, yoga, and gym
        // Auth check is done manually in methods that require it (myBookings, cancelBooking)
    }

    /**
     * Universal booking endpoint - MAIN ENTRY POINT
     * Handles Spa, Yoga, and Gym bookings
     */
    public function book(Request $request)
    {
        // Determine booking type from route or request
        $bookingType = $this->determineBookingType($request);

        // Validate booking request
        $validated = $this->validateBookingRequest($request, $bookingType);

        try {
            DB::beginTransaction();

            // Process booking via service (eliminates code duplication!)
            $result = $this->bookingService->processBooking($validated, $bookingType);

            DB::commit();

            // Send confirmation email asynchronously (don't fail booking if email fails)
            $emailSent = false;
            try {
                $emailSent = $this->emailService->sendBookingConfirmation(
                    $result['booking'],
                    $bookingType
                );
            } catch (\Exception $emailError) {
                Log::warning('Email notification failed but booking was successful', [
                    'booking_code' => $result['booking']->booking_code,
                    'error' => $emailError->getMessage()
                ]);
                // Continue execution - email failure should not affect booking success
            }

            Log::info('Booking completed successfully', [
                'booking_code' => $result['booking']->booking_code,
                'type' => $bookingType,
                'customer_email' => $validated['customer_email'],
                'email_sent' => $emailSent
            ]);

            return response()->json([
                'success' => true,
                'booking_id' => $result['booking']->id,
                'booking_code' => $result['booking']->booking_code,
                'payment_token' => $result['snap_token'],
                'booking_type' => $bookingType,
                'message' => 'Booking created successfully. Please complete the payment.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Booking failed', [
                'type' => $bookingType,
                'error' => $e->getMessage(),
                'customer_email' => $validated['customer_email'] ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine booking type from request path or data
     */
    protected function determineBookingType(Request $request): string
    {
        $path = $request->path();

        // Check URL path first
        if (str_contains($path, 'gym')) {
            return 'gym';
        }
        if (str_contains($path, 'yoga')) {
            return 'yoga';
        }
        if (str_contains($path, 'spa')) {
            return 'spa';
        }

        // Fallback to request parameter
        return $request->input('booking_type', 'spa');
    }

    /**
     * Validate booking request based on type
     */
    protected function validateBookingRequest(Request $request, string $type): array
    {
        // Common validation rules for all booking types
        $commonRules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            // Accept either service_id (integer) or service_type/service_name (string)
            'service_id' => 'nullable|integer|exists:' . $type . '_services,id',
            'service_type' => 'nullable|string|max:255',
            'service_name' => 'nullable|string|max:255',
            'service_price' => 'nullable|numeric|min:0',
            'booking_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ];

        // Type-specific validation rules
        $specificRules = match($type) {
            'spa' => [
                'spa_id' => 'required|integer|exists:spas,id_spa',
                'booking_time' => 'required|date_format:H:i',
                'therapist_preference' => 'nullable|in:male,female,any',
            ],
            'yoga' => [
                'yoga_id' => 'required|integer|exists:yogas,id_yoga',
                'booking_time' => 'nullable|date_format:H:i',
                'class_type' => 'required|string|max:100',
                'customer_address' => 'nullable|string|max:500',
            ],
            'gym' => [
                'gym_id' => 'required|integer|exists:gyms,id_gym',
            ],
            default => []
        };

        return $request->validate(array_merge($commonRules, $specificRules));
    }

    /**
     * Get booking by booking code
     */
    public function getBooking(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
        ]);

        try {
            $result = $this->bookingService->findBookingByCode($request->booking_code);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'booking' => $result['booking'],
                'type' => $result['type']
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching booking', [
                'booking_code' => $request->booking_code,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking'
            ], 500);
        }
    }

    /**
     * Cancel booking
     * Requires authentication
     */
    public function cancelBooking(Request $request)
    {
        // Check authentication
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to cancel booking'
            ], 401);
        }

        $request->validate([
            'booking_code' => 'required|string',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->bookingService->cancelBooking(
                $request->booking_code,
                $request->reason ?? 'Customer cancellation'
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking cancelled successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Booking cancellation failed', [
                'booking_code' => $request->booking_code,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle Midtrans payment callback/webhook
     */
    public function handleMidtransCallback(Request $request)
    {
        try {
            $notification = $request->all();

            // Verify signature untuk security
            if (!$this->paymentService->verifySignature($notification)) {
                Log::warning('Invalid Midtrans signature', [
                    'order_id' => $notification['order_id'] ?? 'unknown'
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Process payment callback
            $result = $this->paymentService->handleCallback($notification);

            // Update booking status
            $updated = $this->bookingService->updateBookingStatus(
                $result['order_id'],
                $result['payment_status'],
                $result['booking_status']
            );

            if (!$updated) {
                Log::error('Failed to update booking status from callback', [
                    'order_id' => $result['order_id']
                ]);
            }

            // Send payment confirmation email if paid
            if ($result['payment_status'] === 'paid') {
                $bookingResult = $this->bookingService->findBookingByCode($result['order_id']);
                if ($bookingResult) {
                    $this->emailService->sendPaymentConfirmation(
                        $bookingResult['booking'],
                        $bookingResult['type']
                    );
                }
            }

            Log::info('Midtrans callback processed', $result);

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Get available entities (Spas/Yogas/Gyms) for booking
     */
    public function getEntities(Request $request)
    {
        $type = $request->input('type', 'spa');

        try {
            $entities = match($type) {
                'spa' => Spa::where('is_open', true)
                    ->select('id_spa as id', 'nama as name', 'alamat as address')
                    ->get(),
                'yoga' => Yoga::select('id_yoga as id', 'nama as name', 'alamat as address')
                    ->get(),
                'gym' => Gym::where('is_open', true)
                    ->select('id_gym as id', 'nama as name', 'alamat as address')
                    ->get(),
                default => []
            };

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
     * Get services for specific entity (Spa/Yoga/Gym)
     */
    public function getServices(Request $request)
    {
        $type = $request->input('type');
        $entityId = $request->input('entity_id');

        try {
            $services = match($type) {
                'spa' => SpaService::where('spa_id', $entityId)
                    ->where('is_active', true)
                    ->get(),
                'yoga' => YogaService::where('yoga_id', $entityId)
                    ->where('is_active', true)
                    ->get(),
                'gym' => GymService::where('gym_id', $entityId)
                    ->where('is_active', true)
                    ->get(),
                default => []
            };

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
     * Get booking statistics (for admin dashboard)
     */
    public function getStats(Request $request)
    {
        $type = $request->input('type', 'all');

        try {
            $stats = $this->bookingService->getBookingStats($type);

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show booking confirmation page
     */
    public function confirmation($bookingCode)
    {
        try {
            $result = $this->bookingService->findBookingByCode($bookingCode);

            if (!$result) {
                abort(404, 'Booking not found');
            }

            return view('booking.confirmation', [
                'booking' => $result['booking'],
                'type' => $result['type']
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading confirmation page', [
                'booking_code' => $bookingCode,
                'error' => $e->getMessage()
            ]);

            abort(500, 'Error loading booking confirmation');
        }
    }

    /**
     * Show payment page
     */
    public function payment($bookingCode)
    {
        try {
            $result = $this->bookingService->findBookingByCode($bookingCode);

            if (!$result) {
                abort(404, 'Booking not found');
            }

            return view('booking.payment', [
                'booking' => $result['booking'],
                'type' => $result['type']
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading payment page', [
                'booking_code' => $bookingCode,
                'error' => $e->getMessage()
            ]);

            abort(500, 'Error loading payment page');
        }
    }

    /**
     * Get user's bookings (My Bookings page)
     * Requires authentication
     */
    public function myBookings()
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your bookings');
        }

        try {
            $user = Auth::user();
            $email = $user->email;

            // Get bookings from all types
            $spaBookings = \App\Models\SpaBooking::where('customer_email', $email)
                ->with('spa')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($booking) {
                    $booking->type = 'spa';
                    return $booking;
                });

            $yogaBookings = \App\Models\YogaBooking::where('customer_email', $email)
                ->with('yoga')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($booking) {
                    $booking->type = 'yoga';
                    return $booking;
                });

            $gymBookings = \App\Models\GymBooking::where('customer_email', $email)
                ->with('gym')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($booking) {
                    $booking->type = 'gym';
                    return $booking;
                });

            // Merge and sort all bookings
            $allBookings = $spaBookings
                ->concat($yogaBookings)
                ->concat($gymBookings)
                ->sortByDesc('created_at');

            return view('booking.my-bookings', [
                'bookings' => $allBookings
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading my bookings', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to load your bookings');
        }
    }

    /**
     * Create spa payment - Alias for book() method
     * This method exists for backward compatibility with API routes
     */
    public function createSpaPayment(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk melakukan booking.',
                'require_login' => true
            ], 401);
        }

        // Set booking type explicitly
        $request->merge(['booking_type' => 'spa']);

        // If service_type is provided instead of service_id, find or create the service
        if ($request->has('service_type') && !$request->has('service_id')) {
            $serviceName = $request->input('service_type');
            $spaId = $request->input('spa_id');
            $servicePrice = $request->input('service_price', 0);

            // Try to find existing service by name and spa_id
            $service = SpaService::where('spa_id', $spaId)
                ->where('name', $serviceName)
                ->where('is_active', true)
                ->first();

            // If not found, create a dynamic service record (or use default)
            if (!$service) {
                // Use a default service or create one dynamically
                $service = SpaService::where('spa_id', $spaId)
                    ->where('is_active', true)
                    ->first();

                if (!$service) {
                    // Create a temporary service
                    $service = SpaService::create([
                        'spa_id' => $spaId,
                        'name' => $serviceName,
                        'price' => $servicePrice,
                        'duration' => 60,
                        'is_active' => true,
                        'category' => 'Spa Service'
                    ]);
                }
            }

            // Set service_id in request
            $request->merge([
                'service_id' => $service->id,
                'service_name' => $serviceName
            ]);
        }

        // Delegate to universal book method
        return $this->book($request);
    }

    /**
     * Create yoga payment - Alias for book() method
     * This method exists for backward compatibility with API routes
     */
    public function createYogaPayment(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk melakukan booking.',
                'require_login' => true
            ], 401);
        }

        // Set booking type explicitly
        $request->merge(['booking_type' => 'yoga']);

        // Delegate to universal book method
        return $this->book($request);
    }

    /**
     * Create gym payment - Alias for book() method
     * This method exists for backward compatibility with API routes
     */
    public function createGymPayment(Request $request)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk melakukan booking.',
                'require_login' => true
            ], 401);
        }

        // Set booking type explicitly
        $request->merge(['booking_type' => 'gym']);

        // Delegate to universal book method
        return $this->book($request);
    }

    /**
     * Get gym services for a specific gym
     *
     * @param int $gymId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGymServices($gymId)
    {
        try {
            $services = \App\Models\GymService::where('gym_id', $gymId)
                ->where('is_active', true)
                ->select('id', 'name', 'price', 'duration', 'description', 'category')
                ->get();

            return response()->json([
                'success' => true,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to load gym services', [
                'gym_id' => $gymId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get yoga services for a specific yoga studio
     *
     * @param int $yogaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYogaServices($yogaId)
    {
        try {
            $services = \App\Models\YogaService::where('yoga_id', $yogaId)
                ->where('is_active', true)
                ->select('id', 'name', 'price', 'duration', 'description', 'category')
                ->get();

            return response()->json([
                'success' => true,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to load yoga services', [
                'yoga_id' => $yogaId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load services: ' . $e->getMessage()
            ], 500);
        }
    }
}
