<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;

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
    
    public function apply(Request $request)
    {
        $voucherCode = $request->input('voucher_code');
        
        $voucher = Voucher::where('code', $voucherCode)->first();
        
        if (!$voucher) {
            return redirect()->back()->with('error', 'Kode voucher tidak valid.');
        }
        
        // Check if voucher is expired
        if ($voucher->expired_at && Carbon::now() > $voucher->expired_at) {
            return redirect()->back()->with('error', 'Voucher sudah kadaluarsa.');
        }
        
        // Check if voucher is already used
        if ($voucher->is_used) {
            return redirect()->back()->with('error', 'Voucher sudah digunakan.');
        }
        
        // Check if voucher has reached usage limit
        if ($voucher->usage_limit && $voucher->usage_count >= $voucher->usage_limit) {
            return redirect()->back()->with('error', 'Batas penggunaan voucher sudah tercapai.');
        }
        
        // Store voucher in session for later use
        session(['active_voucher' => $voucher]);
        
        return redirect()->back()->with('success', 'Voucher berhasil diterapkan! Potongan harga: ' . $voucher->discount_percentage . '%');
    }
}
