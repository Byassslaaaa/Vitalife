<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.voucher.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.voucher.create');
    }

    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Voucher creation request data:', $request->all());

        try {
            $validatedData = $request->validate([
                'description' => 'required|string',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_percentage' => 'nullable|integer|min:0|max:100|required_if:discount_type,percentage',
                'discount_amount' => 'nullable|integer|min:0|required_if:discount_type,fixed',
                'usage_limit' => 'nullable|integer|min:1',
                'expired_at' => 'nullable|date|after:today',
                'code' => 'required|string|unique:vouchers,code',
            ]);

            Log::info('Validation passed');

            // Initialize default values for fields that might be null
            $validatedData['usage_count'] = 0;
            $validatedData['is_used'] = false;

            // Set default values based on discount type
            if ($validatedData['discount_type'] === 'percentage') {
                $validatedData['discount_amount'] = 0;
            } else {
                $validatedData['discount_percentage'] = 0;
            }

            // Log the data being saved to the database
            Log::info('Attempting to save voucher with data:', $validatedData);

            $voucher = Voucher::create($validatedData);

            Log::info('Voucher created successfully with ID: ' . $voucher->id);

            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil disimpan');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan voucher: ' . $e->getMessage());
        }
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.voucher.edit', compact('voucher'));
    }

    public function show(Voucher $voucher)
    {
        return view('admin.voucher.show', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_percentage' => 'nullable|integer|min:0|max:100|required_if:discount_type,percentage',
            'discount_amount' => 'nullable|integer|min:0|required_if:discount_type,fixed',
            'usage_limit' => 'nullable|integer|min:1',
            'expired_at' => 'nullable|date|after:today',
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
        ]);

        // Set default values based on discount type
        if ($validatedData['discount_type'] === 'percentage') {
            $validatedData['discount_amount'] = 0;
        } else {
            $validatedData['discount_percentage'] = 0;
        }

        try {
            $voucher->update($validatedData);
            return redirect()->route('admin.vouchers.index')->with('success', 'Data voucher berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating voucher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data voucher: ' . $e->getMessage());
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {
            $voucher->delete();
            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus voucher: ' . $e->getMessage());
        }
    }
}
