<?php

namespace App\Services;

use App\Models\Spa;
use App\Models\Yoga;
use App\Models\Gym;
use App\Models\Voucher;
use App\Models\SpaService;
use App\Models\YogaService;
use App\Models\GymService;
use App\Models\SpaBooking;
use App\Models\YogaBooking;
use App\Models\GymBooking;
use App\Services\PaymentService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BookingService
{
    protected $paymentService;
    protected $adminFee = 5000; // Admin fee constant

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Check for booking slot conflicts
     *
     * @param array $data Booking data with date, time, service_id
     * @param string $type Booking type (spa|yoga|gym)
     * @throws \Exception if slot is already booked
     */
    protected function checkSlotConflict(array $data, string $type): void
    {
        $bookingDate = $data['booking_date'];
        $bookingTime = $data['booking_time'] ?? null;
        $serviceId = $data['service_id'];

        // For spa bookings, check capacity instead of blocking all bookings
        // Multiple customers can book the same service at different rooms/therapists
        if ($type === 'spa' && $bookingTime) {
            $spaId = $data['spa_id'] ?? null;
            if ($spaId) {
                // Get spa capacity (default 5 concurrent services)
                $maxCapacity = 5; // TODO: Add capacity field to spas table

                $bookedCount = SpaBooking::where('spa_id', $spaId)
                    ->where('booking_date', $bookingDate)
                    ->where('booking_time', 'LIKE', '%' . $bookingTime . '%')
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereIn('payment_status', ['pending', 'paid'])
                    ->count();

                if ($bookedCount >= $maxCapacity) {
                    throw new \Exception('Spa sudah penuh untuk waktu ini. Silakan pilih waktu yang berbeda.');
                }
            }
        }

        // For yoga bookings, check class capacity and time slot
        if ($type === 'yoga') {
            $service = YogaService::find($serviceId);
            $maxCapacity = $service->capacity ?? 20; // Default 20 if not set

            $bookedCount = YogaBooking::where('service_id', $serviceId)
                ->where('booking_date', $bookingDate)
                ->whereIn('status', ['pending', 'confirmed'])
                ->whereIn('payment_status', ['pending', 'paid'])
                ->count();

            if ($bookedCount >= $maxCapacity) {
                throw new \Exception('Kelas yoga sudah penuh untuk tanggal ini. Silakan pilih tanggal lain.');
            }
        }

        // For gym bookings, check daily capacity
        if ($type === 'gym') {
            $gymId = $data['gym_id'] ?? null;
            if ($gymId) {
                $gym = Gym::find($gymId);
                $maxCapacity = $gym->capacity ?? 50; // Default 50 if not set

                $bookedCount = GymBooking::where('gym_id', $gymId)
                    ->where('booking_date', $bookingDate)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereIn('payment_status', ['pending', 'paid'])
                    ->count();

                if ($bookedCount >= $maxCapacity) {
                    throw new \Exception('Gym sudah penuh untuk tanggal ini. Silakan pilih tanggal lain.');
                }
            }
        }

        Log::info('Slot availability checked', [
            'type' => $type,
            'date' => $bookingDate,
            'time' => $bookingTime ?? 'N/A',
            'service_id' => $serviceId
        ]);
    }

    /**
     * Validate booking date and time to prevent past bookings
     *
     * @param string $bookingDate
     * @param string|null $bookingTime
     * @throws \Exception if date/time is in the past
     */
    protected function validateBookingDateTime(string $bookingDate, ?string $bookingTime = null): void
    {
        $now = now();
        $bookingDateTime = $bookingTime
            ? \Carbon\Carbon::parse($bookingDate . ' ' . $bookingTime)
            : \Carbon\Carbon::parse($bookingDate);

        if ($bookingDateTime->isPast()) {
            throw new \Exception('Tidak dapat booking untuk waktu yang sudah lewat. Silakan pilih tanggal/waktu yang akan datang.');
        }

        // Minimum booking time (configurable: 30 minutes for flexibility)
        if ($bookingTime) {
            $minutesDifference = $now->diffInMinutes($bookingDateTime, false);

            // If negative, booking is in the past
            if ($minutesDifference < 0) {
                throw new \Exception('Tidak dapat booking untuk waktu yang sudah lewat. Silakan pilih tanggal/waktu yang akan datang.');
            }

            // Require minimum 30 minutes advance booking (flexible for same-day bookings)
            $minimumMinutes = 30;
            if ($minutesDifference < $minimumMinutes) {
                throw new \Exception("Booking harus dilakukan minimal {$minimumMinutes} menit sebelum waktu layanan. Silakan pilih waktu yang lebih lama.");
            }
        }

        Log::info('Booking date/time validated', [
            'booking_date' => $bookingDate,
            'booking_time' => $bookingTime ?? 'N/A'
        ]);
    }

    /**
     * Process booking untuk semua tipe (Spa/Yoga/Gym)
     *
     * @param array $data Validated booking data
     * @param string $type Booking type (spa|yoga|gym)
     * @return array ['booking' => Model, 'snap_token' => string]
     * @throws \Exception
     */
    public function processBooking(array $data, string $type): array
    {
        Log::info('Processing booking', [
            'type' => $type,
            'customer_email' => $data['customer_email'] ?? 'unknown'
        ]);

        // Validate booking date/time (prevent past bookings)
        $this->validateBookingDateTime(
            $data['booking_date'],
            $data['booking_time'] ?? null
        );

        // Check for slot conflicts (prevent double booking)
        $this->checkSlotConflict($data, $type);

        // Get service details
        $service = $this->getService($data['service_id'], $type);

        // Validate and get voucher if provided
        $voucher = $this->validateVoucher($data['voucher_code'] ?? null);

        // Calculate total amount (SERVER-SIDE ONLY for security)
        $totalAmount = $this->calculateTotalAmount($service->price, $voucher);

        // Prepare booking data
        $bookingData = $this->prepareBookingData($data, $service, $totalAmount, $type);

        // Create booking record
        $booking = $this->saveBooking($bookingData, $type);

        // Mark voucher as used if applicable
        if ($voucher) {
            $this->markVoucherUsed($voucher);
        }

        // Generate Midtrans payment token
        $snapToken = $this->paymentService->generateToken($booking, $type);

        Log::info('Booking processed successfully', [
            'booking_code' => $booking->booking_code,
            'type' => $type,
            'amount' => $totalAmount
        ]);

        return [
            'booking' => $booking,
            'snap_token' => $snapToken
        ];
    }

    /**
     * Get service based on type and ID
     *
     * @param int $serviceId
     * @param string $type
     * @return SpaService|YogaService|GymService
     * @throws \Exception
     */
    protected function getService(int $serviceId, string $type)
    {
        $service = match($type) {
            'spa' => SpaService::where('id', $serviceId)->where('is_active', true)->first(),
            'yoga' => YogaService::where('id', $serviceId)->where('is_active', true)->first(),
            'gym' => GymService::where('id', $serviceId)->where('is_active', true)->first(),
            default => null
        };

        if (!$service) {
            throw new \Exception("Service not found or inactive for type: {$type}");
        }

        return $service;
    }

    /**
     * Validate and get voucher
     *
     * @param string|null $code
     * @return Voucher|null
     * @throws \Exception
     */
    protected function validateVoucher(?string $code): ?Voucher
    {
        if (!$code) {
            return null;
        }

        $voucher = Voucher::where('code', $code)
            ->where('is_used', false)
            ->where(function($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('usage_count < usage_limit');
            })
            ->first();

        if (!$voucher) {
            throw new \Exception('Voucher is invalid, expired, or already used');
        }

        Log::info('Voucher validated', [
            'code' => $code,
            'discount_type' => $voucher->discount_type
        ]);

        return $voucher;
    }

    /**
     * Calculate total amount (SERVER-SIDE ONLY)
     * This is critical for security - never trust client calculations
     *
     * @param int $servicePrice
     * @param Voucher|null $voucher
     * @return int
     */
    protected function calculateTotalAmount(int $servicePrice, ?Voucher $voucher): int
    {
        $discount = 0;

        if ($voucher) {
            if ($voucher->discount_type === 'percentage') {
                // Calculate percentage discount
                $discount = floor(($servicePrice * $voucher->discount_percentage) / 100);
            } else {
                // Fixed amount discount
                $discount = $voucher->discount_amount;
            }

            // Ensure discount doesn't exceed service price
            $discount = min($discount, $servicePrice);

            Log::info('Discount calculated', [
                'voucher_code' => $voucher->code,
                'service_price' => $servicePrice,
                'discount' => $discount
            ]);
        }

        $total = $servicePrice + $this->adminFee - $discount;

        // Ensure total is never negative
        return max($total, 0);
    }

    /**
     * Prepare booking data based on type
     *
     * @param array $data
     * @param object $service
     * @param int $totalAmount
     * @param string $type
     * @return array
     */
    protected function prepareBookingData(array $data, $service, int $totalAmount, string $type): array
    {
        $bookingCode = strtoupper($type) . '-' . strtoupper(Str::random(8));

        $commonData = [
            'booking_code' => $bookingCode,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_price' => $totalAmount,
            'total_amount' => $totalAmount,
            'booking_date' => $data['booking_date'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
        ];

        // Add type-specific fields
        $typeSpecificData = match($type) {
            'spa' => [
                'spa_id' => $data['spa_id'],
                'booking_time' => $data['booking_date'] . ' ' . $data['booking_time'],
                'therapist_preference' => $data['therapist_preference'] ?? null,
            ],
            'yoga' => [
                'yoga_id' => $data['yoga_id'],
                'booking_time' => $data['booking_time'] ?? null,
                'class_type' => $data['class_type'] ?? 'regular',
                'customer_address' => $data['customer_address'] ?? null,
            ],
            'gym' => [
                'gym_id' => $data['gym_id'],
            ],
            default => []
        };

        return array_merge($commonData, $typeSpecificData);
    }

    /**
     * Save booking to appropriate table based on type
     *
     * @param array $data
     * @param string $type
     * @return SpaBooking|YogaBooking|GymBooking
     * @throws \Exception
     */
    protected function saveBooking(array $data, string $type)
    {
        $booking = match($type) {
            'spa' => SpaBooking::create($data),
            'yoga' => YogaBooking::create($data),
            'gym' => GymBooking::create($data),
            default => throw new \Exception("Invalid booking type: {$type}")
        };

        Log::info('Booking saved to database', [
            'booking_code' => $booking->booking_code,
            'type' => $type,
            'id' => $booking->id
        ]);

        return $booking;
    }

    /**
     * Mark voucher as used
     *
     * @param Voucher $voucher
     * @return void
     */
    protected function markVoucherUsed(Voucher $voucher): void
    {
        $voucher->increment('usage_count');

        // Check if usage limit reached
        if ($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit) {
            $voucher->update(['is_used' => true]);
            Log::info('Voucher marked as fully used', ['code' => $voucher->code]);
        } else {
            Log::info('Voucher usage incremented', [
                'code' => $voucher->code,
                'usage_count' => $voucher->usage_count,
                'usage_limit' => $voucher->usage_limit
            ]);
        }
    }

    /**
     * Find booking by booking code across all booking tables
     *
     * @param string $bookingCode
     * @return array ['booking' => Model, 'type' => string] or null
     */
    public function findBookingByCode(string $bookingCode): ?array
    {
        // Try Spa bookings first
        $booking = SpaBooking::where('booking_code', $bookingCode)->first();
        if ($booking) {
            return ['booking' => $booking, 'type' => 'spa'];
        }

        // Try Yoga bookings
        $booking = YogaBooking::where('booking_code', $bookingCode)->first();
        if ($booking) {
            return ['booking' => $booking, 'type' => 'yoga'];
        }

        // Try Gym bookings
        $booking = GymBooking::where('booking_code', $bookingCode)->first();
        if ($booking) {
            return ['booking' => $booking, 'type' => 'gym'];
        }

        return null;
    }

    /**
     * Update booking status berdasarkan payment callback
     *
     * @param string $bookingCode
     * @param string $paymentStatus
     * @param string $bookingStatus
     * @return bool
     */
    public function updateBookingStatus(string $bookingCode, string $paymentStatus, string $bookingStatus): bool
    {
        $result = $this->findBookingByCode($bookingCode);

        if (!$result) {
            Log::error('Booking not found for status update', ['booking_code' => $bookingCode]);
            return false;
        }

        $booking = $result['booking'];
        $booking->update([
            'payment_status' => $paymentStatus,
            'status' => $bookingStatus
        ]);

        Log::info('Booking status updated', [
            'booking_code' => $bookingCode,
            'payment_status' => $paymentStatus,
            'booking_status' => $bookingStatus
        ]);

        return true;
    }

    /**
     * Cancel booking
     *
     * @param string $bookingCode
     * @param string $reason
     * @return bool
     * @throws \Exception
     */
    public function cancelBooking(string $bookingCode, string $reason = 'Customer cancellation'): bool
    {
        $result = $this->findBookingByCode($bookingCode);

        if (!$result) {
            throw new \Exception('Booking not found');
        }

        $booking = $result['booking'];

        // Check if booking can be cancelled
        if ($booking->status === 'confirmed' && $booking->payment_status === 'paid') {
            // Add business logic: e.g., only allow cancellation 24 hours before booking_date
            $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date);
            $hoursUntilBooking = now()->diffInHours($bookingDateTime, false);

            if ($hoursUntilBooking < 24) {
                throw new \Exception('Cannot cancel booking less than 24 hours before scheduled time');
            }
        }

        // Update booking status
        $booking->update([
            'status' => 'cancelled',
            'notes' => ($booking->notes ?? '') . "\nCancellation reason: " . $reason
        ]);

        // Cancel Midtrans transaction if payment is pending
        if ($booking->payment_status === 'pending' && $booking->payment_token) {
            $this->paymentService->cancelTransaction($booking->booking_code);
        }

        Log::info('Booking cancelled', [
            'booking_code' => $bookingCode,
            'reason' => $reason
        ]);

        return true;
    }

    /**
     * Get booking statistics
     *
     * @param string $type
     * @return array
     */
    public function getBookingStats(string $type = 'all'): array
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'confirmed' => 0,
            'cancelled' => 0,
            'total_revenue' => 0,
        ];

        $models = match($type) {
            'spa' => [SpaBooking::class],
            'yoga' => [YogaBooking::class],
            'gym' => [GymBooking::class],
            default => [SpaBooking::class, YogaBooking::class, GymBooking::class]
        };

        foreach ($models as $model) {
            $stats['total'] += $model::count();
            $stats['pending'] += $model::where('status', 'pending')->count();
            $stats['confirmed'] += $model::where('status', 'confirmed')->count();
            $stats['cancelled'] += $model::where('status', 'cancelled')->count();
            $stats['total_revenue'] += $model::where('payment_status', 'paid')->sum('service_price');
        }

        return $stats;
    }
}
