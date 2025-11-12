<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VouchersController extends Controller
{
    public function index()
    {
        // Get all active vouchers (not expired)
        $vouchers = Voucher::where(function($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                    ->orWhere('usage_count', '<', \DB::raw('usage_limit'));
            })
            ->where('is_used', false)
            ->orderBy('expired_at', 'asc')
            ->get();

        return view('fitur.voucher', compact('vouchers'));
    }

    /**
     * Get public vouchers for API/AJAX requests
     */
    public function getPublicVouchers()
    {
        $vouchers = Voucher::where(function($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', Carbon::now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                    ->orWhere('usage_count', '<', \DB::raw('usage_limit'));
            })
            ->where('is_used', false)
            ->orderBy('expired_at', 'asc')
            ->get()
            ->map(function($voucher) {
                return [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'description' => $voucher->description,
                    'discount_type' => $voucher->discount_type,
                    'discount_percentage' => $voucher->discount_percentage,
                    'discount_amount' => $voucher->discount_amount,
                    'expired_at' => $voucher->expired_at ? $voucher->expired_at->format('Y-m-d') : null,
                    'usage_count' => $voucher->usage_count,
                    'usage_limit' => $voucher->usage_limit,
                ];
            });

        return response()->json([
            'success' => true,
            'vouchers' => $vouchers
        ]);
    }


    public function apply(Request $request)
    {
        $voucherCode = $request->input('voucher_code');

        $voucher = Voucher::where('code', $voucherCode)->first();

        if (!$voucher) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode voucher tidak valid.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Kode voucher tidak valid.');
        }

        // Check if voucher is expired
        if ($voucher->expired_at && Carbon::now() > $voucher->expired_at) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah kadaluarsa.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Voucher sudah kadaluarsa.');
        }

        // Check if voucher is already used
        if ($voucher->is_used) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah digunakan.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Voucher sudah digunakan.');
        }

        // Check if voucher has reached usage limit
        if ($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batas penggunaan voucher sudah tercapai.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Batas penggunaan voucher sudah tercapai.');
        }

        // Store voucher in session for later use
        session(['active_voucher' => $voucher]);

        if ($request->expectsJson()) {
            $discountText = $voucher->discount_type === 'percentage'
                ? $voucher->discount_percentage . '%'
                : 'Rp ' . number_format($voucher->discount_amount, 0, ',', '.');

            // Calculate actual discount amount for the service
            $serviceAmount = $request->input('service_amount', 0);
            $actualDiscount = 0;

            if ($voucher->discount_type === 'percentage') {
                $actualDiscount = floor(($serviceAmount * $voucher->discount_percentage) / 100);
            } else {
                $actualDiscount = min($voucher->discount_amount, $serviceAmount);
            }

            return response()->json([
                'success' => true,
                'message' => "Hemat {$discountText} (Rp " . number_format($actualDiscount, 0, ',', '.') . ")",
                'voucher' => [
                    'id' => $voucher->id,
                    'code' => $voucher->code,
                    'discount_type' => $voucher->discount_type,
                    'discount_percentage' => $voucher->discount_percentage,
                    'discount_amount' => $voucher->discount_amount,
                    'discount_text' => $discountText,
                    'actual_discount' => $actualDiscount
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Voucher berhasil diterapkan! Potongan harga: ' . $voucher->discount_percentage . '%');
    }
}
