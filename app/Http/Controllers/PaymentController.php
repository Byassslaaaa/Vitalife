<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->get();
        return response()->json($payments);
    }

    /**
     * Update the payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $kode)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Dikonfirmasi,Ditolak',
        ]);

        $payment = Payment::where('kode', $kode)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        $payment->status = $request->status;
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'payment' => $payment
        ]);
    }

    /**
     * Save a new payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'bank' => 'required|string|max:50',
                'kode' => 'required|string|max:50|unique:payments',
                'jumlah' => 'required',
                'tanggal' => 'required|date',
                'status' => 'required|string|max:50',
            ]);

            $payment = Payment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimpan',
                'payment' => $payment
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error saving payment: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(function($arr) { return implode(', ', $arr); }, $e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
