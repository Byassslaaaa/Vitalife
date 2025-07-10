<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'spa_id' => 'required|exists:spas,id_spa',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'services' => 'required|array',
            'services.*' => 'required|exists:spa_services,id',
        ]);

        try {
            DB::beginTransaction();
            $bookingCode = 'SPA-' . strtoupper(Str::random(8));
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'spa_id' => $request->spa_id,
                'customer_name' => auth()->user()->name ?? 'Guest',
                'customer_email' => auth()->user()->email ?? 'guest@example.com',
                'customer_phone' => auth()->user()->phone ?? '0000',
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'total_amount' => 0, // update below
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
            $total = 0;
            $itemDetails = [];
            foreach ($request->services as $serviceId) {
                $service = \App\Models\SpaService::findOrFail($serviceId);
                BookingService::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'price' => $service->price,
                ]);
                $total += $service->price;
                $itemDetails[] = [
                    'id' => $service->id,
                    'price' => (int) $service->price,
                    'quantity' => 1,
                    'name' => $service->name,
                ];
            }
            $booking->total_amount = $total;
            $booking->save();

            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

            $params = [
                'transaction_details' => [
                    'order_id' => $bookingCode,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'phone' => $booking->customer_phone,
                ],
                'item_details' => $itemDetails,
            ];
            $snapToken = Snap::getSnapToken($params);
            $booking->update(['payment_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function payment($id)
    {
        $booking = Booking::with('services')->findOrFail($id);
        return view('booking.payment', compact('booking'));
    }

    // MIDTRANS CALLBACK / NOTIFICATION HANDLER
    public function handleMidtransCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $booking = Booking::where('booking_code', $order_id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->status = 'pending';
                    $booking->payment_status = 'pending';
                } else {
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'paid';
                }
            }
        } elseif ($transaction == 'settlement') {
            $booking->status = 'confirmed';
            $booking->payment_status = 'paid';
        } elseif ($transaction == 'pending') {
            $booking->status = 'pending';
            $booking->payment_status = 'pending';
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $booking->status = 'cancelled';
            $booking->payment_status = 'failed';
        }

        $booking->payment_details = json_encode($notif);
        $booking->save();

        // Kirim email konfirmasi jika sukses
        if ($booking->status == 'confirmed' && $booking->customer_email) {
            Mail::to($booking->customer_email)->send(new BookingSuccessMail($booking));
        }

        return response()->json(['message' => 'Notification handled'], 200);
    }

    public function confirmation($id)
    {
        $booking = Booking::with('services')->findOrFail($id);
        return view('booking.confirmation', compact('booking'));
    }
}