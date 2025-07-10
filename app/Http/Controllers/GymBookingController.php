<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymBooking;
use App\Models\GymService;
use App\Mail\GymBookingSuccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GymBookingController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'gym_id' => 'required|exists:gyms,id_gym',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'service_id' => 'required|exists:gym_services,id',
        ]);

        try {
            // Check if gym_bookings table exists, if not create it
            if (!Schema::hasTable('gym_bookings')) {
                $this->createGymBookingsTable();
            }

            DB::beginTransaction();
            
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

            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

            $params = [
                'transaction_details' => [
                    'order_id' => $bookingCode,
                    'gross_amount' => (int) $service->price,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'phone' => $booking->customer_phone,
                ],
                'item_details' => [
                    [
                        'id' => $service->id,
                        'price' => (int) $service->price,
                        'quantity' => 1,
                        'name' => $service->name,
                    ],
                ],
            ];
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $booking->update(['payment_token' => $snapToken]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getGymServices($gymId)
    {
        $services = GymService::where('gym_id', $gymId)
                              ->where('is_active', true)
                              ->get();
        
        return response()->json([
            'success' => true,
            'services' => $services
        ]);
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
}
