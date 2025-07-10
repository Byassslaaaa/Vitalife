<?php

namespace App\Http\Controllers;

use App\Models\Yoga;
use App\Models\YogaBooking;
use App\Mail\YogaBookingSuccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class YogaBookingController extends Controller
{
    public function book(Request $request)
    {
        $request->validate([
            'yoga_id' => 'required|exists:yogas,id_yoga',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'class_type_booking' => 'required|string',
        ]);

        $yoga = Yoga::findOrFail($request->yoga_id);
        $bookingCode = 'YOGA-' . strtoupper(Str::random(8));
        $total = $yoga->harga;

        $booking = YogaBooking::create([
            'booking_code' => $bookingCode,
            'yoga_id' => $yoga->id_yoga,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'class_type' => $request->class_type_booking,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Konfigurasi Midtrans
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
            'item_details' => [[
                'id' => $yoga->id_yoga,
                'price' => (int)$yoga->harga,
                'quantity' => 1,
                'name' => $yoga->nama,
            ]],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $booking->update(['payment_token' => $snapToken]);

        return response()->json([
            'success' => true,
            'booking_id' => $booking->id,
            'payment_token' => $snapToken,
        ]);
    }

    public function handleMidtransCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        $booking = YogaBooking::where('booking_code', $order_id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($transaction == 'capture' || $transaction == 'settlement') {
            $booking->status = 'confirmed';
            $booking->payment_status = 'paid';
            
            // KIRIM EMAIL BERHASIL BAYAR
            if ($booking->customer_email) {
                Mail::to($booking->customer_email)->send(new YogaBookingSuccessMail($booking));
            }
        } elseif ($transaction == 'pending') {
            $booking->status = 'pending';
            $booking->payment_status = 'pending';
        } else { // gagal, cancel, expire, deny
            $booking->status = 'cancelled';
            $booking->payment_status = 'failed';
        }

        $booking->payment_details = json_encode($notif);
        $booking->save();

        return response()->json(['message' => 'Notification handled'], 200);
    }
}