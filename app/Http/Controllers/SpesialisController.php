<?php

namespace App\Http\Controllers;

use App\Models\spesialis;
use Illuminate\Http\Request;

class SpesialisController extends Controller
{
    public function getWhatsAppNumber($id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        return response()->json(['whatsappNumber' => $spesialis->noHP]);
    }
    
    public function bayar($id_spesialis)
    {
        $spesialis = Spesialis::findOrFail($id_spesialis);
        return view('fitur.spesBayar', compact('spesialis'));
    }

    public function showSpes(Request $request)
    {
        $query = Spesialis::query();

        // Search by name
        if ($request->filled('nama')) {
            $searchTerm = $request->input('nama');
            $query->where('nama', 'like', '%' . $searchTerm . '%');
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            if ($request->filled('min_price')) {
                $query->where('harga', '>=', $request->input('min_price'));
            }
            if ($request->filled('max_price')) {
                $query->where('harga', '<=', $request->input('max_price'));
            }
        }

        if ($request->filled('location')) {
            $query->where('alamat', 'like', '%' . $request->input('location') . '%');
        }    

        $spesLihat = $query->get();

        return view('fitur.spesialis', compact('spesLihat'));
    }

    public function spesFilter(Request $request)
    {
        $spes = $request->input('spesialisasi');
        $spesFilter = spesialis::where('spesialisasi', 'like', "%$spes%")->get();
        return view('fitur.spesialisFilter', compact('spesFilter'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.formspesialis');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer',
            'spesialisasi' => 'required|string',
            'tempatTugas' => 'required|string',
            'alamat' => 'required|string',
            'noHP' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        try {
            $spesialis = Spesialis::create($validatedData);
            return redirect()->route('admin.spaShow', $spesialis)->with('success', 'Data Spesialis berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data Spesialis. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(spesialis $spesialis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(spesialis $spesialis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, spesialis $spesialis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(spesialis $spesialis)
    {
        //
    }
}
