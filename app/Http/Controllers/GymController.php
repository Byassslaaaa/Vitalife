<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function showGyms()
    {
        $gymTotal = Gym::all(); // Fetch all gym data
        return view('fitur.gym', compact('gymTotal'));
    }

    public function gymFilter(Request $request)
    {
        $gym = $request->input('location');
        $gymTotal = Gym::where('alamat', 'like', "%$gym%")->get();
        return view('fitur.gymFilter', compact('gymTotal'));
    }

    public function index(Request $request)
    {
        $query = Gym::query();

        // Pencarian berdasarkan nama
        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan lokasi
        if ($request->has('location') && $request->location != '') {
            $query->where('alamat', 'like', '%' . $request->location . '%');
        }

        $gymTotal = $query->get();

        return view('fitur.gym', compact('gymTotal'));
    }

    public function create()
    {
        return view('admin.formgym');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'services' => 'required|array|min:3|max:3',
            'services.*.name' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle main gym image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        // Handle services images
        $services = [];
        foreach ($request->services as $index => $service) {
            $serviceData = [
                'name' => $service['name'],
                'description' => $service['description']
            ];
            
            if ($request->hasFile("services.{$index}.image")) {
                $serviceImageName = time() . '_service_' . $index . '.' . $request->file("services.{$index}.image")->extension();
                $request->file("services.{$index}.image")->move(public_path('images/services'), $serviceImageName);
                $serviceData['image'] = 'images/services/' . $serviceImageName;
            }
            
            $services[] = $serviceData;
        }
        
        $validatedData['services'] = $services;

        try {
            $gym = Gym::create($validatedData);
            return redirect()->route('admin.gyms.index')->with('success', 'Data Gym berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data Gym. Silakan coba lagi.');
        }
    }

    public function show(Gym $gym)
    {
        return view('admin.gymShow', compact('gym'));
    }

    public function edit(Gym $gym)
    {
        return view('admin.gymEdit', compact('gym'));
    }

    public function update(Request $request, Gym $gym)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'alamat' => 'required',
            'services' => 'required|array|min:3|max:3',
            'services.*.name' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $gym->update($validatedData);

        return redirect()->route('admin.gymIndex')->with('success', 'Data Gym berhasil diperbarui');
    }

    public function destroy(Gym $gym)
    {
        // Delete main image if it exists
        if ($gym->image && file_exists(public_path($gym->image))) {
            unlink(public_path($gym->image));
        }
        
        // Delete service images if they exist
        if ($gym->services && is_array($gym->services)) {
            foreach ($gym->services as $service) {
                if (isset($service['image']) && file_exists(public_path($service['image']))) {
                    unlink(public_path($service['image']));
                }
            }
        }
        
        $gym->delete();
        return redirect()->route('admin.formgym')->with('success', 'Data Gym berhasil dihapus');
    }

    /**
     * Display the specified gym detail page.
     *
     * @param  int  $id_gym
     * @return \Illuminate\Http\Response
     */
    public function detail($id_gym)
    {
        $gym = Gym::findOrFail($id_gym);
        
        return view('fitur.gym-detail', compact('gym'));
    }
}