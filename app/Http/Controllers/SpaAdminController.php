<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use Illuminate\Http\Request;

class SpaAdminController extends Controller
{
    public function index()
    {
        return view('admin.spas.index');
    }

    public function create()
    {
        return view('admin.spas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
        ]);

        Spa::create($validatedData);
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil ditambahkan!');
    }

    public function show($id)
    {
        $spa = Spa::findOrFail($id);
        return view('admin.spas.show', compact('spa'));
    }

    public function edit($id)
    {
        $spa = Spa::findOrFail($id);
        return view('admin.spas.edit', compact('spa'));
    }

    public function update(Request $request, $id)
    {
        $spa = Spa::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
        ]);

        $spa->update($validatedData);
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $spa = Spa::findOrFail($id);
        $spa->delete();
        return redirect()->route('admin.spas.index')->with('success', 'Spa berhasil dihapus!');
    }
}
