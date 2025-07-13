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
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'discount_percentage' => 'required|integer|min:0|max:100',
                'usage_limit' => 'nullable|integer|min:1',
                'expired_at' => 'nullable|date|after:today',
                'code' => 'required|string|unique:vouchers,code',
            ]);

            Log::info('Validation passed');

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $imagePath = 'images/vouchers/' . $imageName;

                // Log the image information
                Log::info('Processing image upload', [
                    'original_name' => $request->image->getClientOriginalName(),
                    'size' => $request->image->getSize(),
                    'target_path' => $imagePath
                ]);

                $request->image->move(public_path('images/vouchers'), $imageName);
                $validatedData['image'] = $imagePath;

                Log::info('Image uploaded successfully');
            }

            // Initialize default values for fields that might be null
            $validatedData['usage_count'] = 0;
            $validatedData['is_used'] = false;

            // Log the data being saved to the database
            Log::info('Attempting to save voucher with data:', $validatedData);

            $voucher = Voucher::create($validatedData);

            Log::info('Voucher created successfully with ID: ' . $voucher->id);

            return redirect()->route('admin.vouchers.show', $voucher)->with('success', 'Voucher berhasil disimpan');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'usage_limit' => 'nullable|integer|min:1',
            'expired_at' => 'nullable|date|after:today',
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($voucher->image && file_exists(public_path($voucher->image))) {
                unlink(public_path($voucher->image));
            }

            // Upload gambar baru
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/vouchers'), $imageName);
            $validatedData['image'] = 'images/vouchers/' . $imageName;
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
            if ($voucher->image && file_exists(public_path($voucher->image))) {
                unlink(public_path($voucher->image));
            }

            $voucher->delete();
            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete voucher: ' . $e->getMessage());
        }
    }
}
