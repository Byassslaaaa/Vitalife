<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spesialis;
use Illuminate\Support\Facades\Storage;

class SpesialisisController extends Controller
{
    public function index()
    {
        $spesialisis = Spesialis::all();
        return view('admin.spesialisis.index', compact('spesialisis'));
    }

    public function formspesialis()
    {
        return view('admin.formspesialis');
    }

    public function show($id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        return view('admin.spesialisis.show', compact('spesialis'));
    }

    public function edit($id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        return view('admin.spesialisis.edit', compact('spesialis'));
    }

    public function update(Request $request, $id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'spesialisasi' => 'required|string',
            'tempatTugas' => 'required|string',
            'alamat' => 'required|string',
            'noHP' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($spesialis->image) {
                Storage::delete($spesialis->image);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        try {
            $spesialis->update($validatedData);
            return redirect()->route('admin.spesialisis.index')->with('success', 'Data Spesialis berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data Spesialis. Silakan coba lagi.');
        }
    }

    public function destroy($id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        
        // Delete associated image
        if ($spesialis->image) {
            Storage::delete($spesialis->image);
        }
        
        $spesialis->delete();
        return redirect()->route('admin.spesialisis.index')->with('success', 'Spesialis berhasil dihapus');
    }
}