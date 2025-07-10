<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GymsController extends Controller
{
    /**
     * Display a listing of the gyms.
     */
    public function index()
    {
        $gyms = Gym::orderBy('created_at', 'desc')->get();
        return view('admin.gyms.index', compact('gyms'));
    }

    /**
     * Show the form for creating a new gym.
     */
    public function create()
    {
        return view('admin.formgym');
    }

    /**
     * Store a newly created gym in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'is_open' => 'required|boolean',
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
            Gym::create($validatedData);
            return redirect()->route('admin.gyms.index')->with('success', 'Data Gym berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data Gym. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified gym.
     */
    public function show($id_gym)
    {
        $gym = Gym::findOrFail($id_gym);
        return view('admin.gyms.show', compact('gym'));
    }

    /**
     * Show the form for editing the specified gym.
     */
    public function edit($id_gym)
    {
        $gym = Gym::findOrFail($id_gym);
        return view('admin.gyms.edit', compact('gym'));
    }

    /**
     * Update the specified gym in storage.
     */
    public function update(Request $request, $id_gym)
    {
        $gym = Gym::findOrFail($id_gym);
        
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'is_open' => 'required|boolean',
            'services' => 'required|array|min:3|max:3',
            'services.*.name' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle main gym image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($gym->image && file_exists(public_path($gym->image))) {
                unlink(public_path($gym->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        // Handle services images
        $services = [];
        $oldServices = $gym->services ?? [];
        
        foreach ($request->services as $index => $service) {
            $serviceData = [
                'name' => $service['name'],
                'description' => $service['description']
            ];
            
            // If new image is uploaded
            if ($request->hasFile("services.{$index}.image")) {
                // Delete old service image if it exists
                if (isset($oldServices[$index]['image']) && file_exists(public_path($oldServices[$index]['image']))) {
                    unlink(public_path($oldServices[$index]['image']));
                }
                
                $serviceImageName = time() . '_service_' . $index . '.' . $request->file("services.{$index}.image")->extension();
                $request->file("services.{$index}.image")->move(public_path('images/services'), $serviceImageName);
                $serviceData['image'] = 'images/services/' . $serviceImageName;
            } else {
                // Keep old image if no new image is uploaded
                $serviceData['image'] = $oldServices[$index]['image'] ?? null;
            }
            
            $services[] = $serviceData;
        }
        
        $validatedData['services'] = $services;

        try {
            $gym->update($validatedData);
            return redirect()->route('admin.gyms.index')->with('success', 'Data Gym berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data Gym. Silakan coba lagi.');
        }
    }

    /**
     * Toggle gym open/close status.
     */
    public function toggleStatus($id_gym)
    {
        try {
            $gym = Gym::findOrFail($id_gym);
            $gym->update(['is_open' => !$gym->is_open]);
            
            $status = $gym->is_open ? 'dibuka' : 'ditutup';
            return redirect()->route('admin.gyms.index')->with('success', "Gym {$gym->nama} berhasil {$status}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status gym. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified gym from storage.
     */
    public function destroy($id_gym)
    {
        try {
            $gym = Gym::findOrFail($id_gym);
            
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
            return redirect()->route('admin.gyms.index')->with('success', 'Data Gym berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data Gym. Silakan coba lagi.');
        }
    }
}