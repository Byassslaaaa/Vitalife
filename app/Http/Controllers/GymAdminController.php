<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymDetail;
use App\Models\GymService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GymAdminController extends Controller
{    public function index()
    {
        $gyms = Gym::with(['gymDetail'])->get();
        $statistics = [
            'total_count' => $gyms->count(),
            'active_count' => $gyms->where('is_open', true)->count(),
            'inactive_count' => $gyms->where('is_open', false)->count(),
            'active_percentage' => $gyms->count() > 0 ? round(($gyms->where('is_open', true)->count() / $gyms->count()) * 100, 1) : 0,
        ];

        return view('admin.gyms.index', compact('gyms', 'statistics'));
    }

    public function create()
    {
        return view('admin.gyms.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'services' => 'required|array|min:3|max:3', // Gym requires exactly 3 services
            'services.*.name' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle main image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_gym_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
                $image->storeAs('images', $imageName, 'public');
                $validatedData['image'] = 'storage/images/' . $imageName;
            }

            // Set default values
            $validatedData['is_open'] = $request->input('is_open', '0') == '1' ? true : false;

            // Handle services data
            $servicesData = [];
            if ($request->has('services')) {
                foreach ($request->services as $index => $service) {
                    $serviceData = [
                        'name' => $service['name'],
                        'description' => $service['description']
                    ];

                    // Add price if provided
                    if (isset($service['price']) && $service['price'] !== null && $service['price'] !== '') {
                        $serviceData['price'] = $service['price'];
                    }

                    // Handle service image upload
                    if ($request->hasFile("services.{$index}.image")) {
                        $serviceImage = $request->file("services.{$index}.image");
                        $serviceImageName = time() . '_service_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $serviceImage->getClientOriginalName());
                        $serviceImage->storeAs('images', $serviceImageName, 'public');
                        $serviceData['image'] = 'storage/images/' . $serviceImageName;
                    }

                    $servicesData[] = $serviceData;
                }
            }
            $validatedData['services'] = $servicesData;

            Gym::create($validatedData);

            DB::commit();
            return redirect()->route('admin.gyms.index')->with('success', 'Gym berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menambahkan gym: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $gym = Gym::with(['gymDetail'])->findOrFail($id);
        return view('admin.gyms.show', compact('gym'));
    }

    public function edit($id)
    {
        $gym = Gym::with(['gymDetail'])->findOrFail($id);
        return view('admin.gyms.edit', compact('gym'));
    }

    public function update(Request $request, $id)
    {
        $gym = Gym::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'services' => 'required|array|min:3|max:3', // Gym requires exactly 3 services
            'services.*.name' => 'required|string|max:255',
            'services.*.description' => 'required|string',
            'services.*.price' => 'nullable|numeric|min:0', // Make price optional in edit
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gym->image && Storage::disk('public')->exists($gym->image)) {
                    Storage::disk('public')->delete($gym->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_gym_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('images', $imageName, 'public');
                $validatedData['image'] = 'storage/images/' . $imageName;
            }

            // Set default values
            $validatedData['is_open'] = $request->input('is_open', '0') == '1' ? true : false;

            // Handle services data
            $servicesData = [];
            if ($request->has('services')) {
                foreach ($request->services as $index => $service) {
                    $serviceData = [
                        'name' => $service['name'],
                        'description' => $service['description']
                    ];

                    // Add price if provided
                    if (isset($service['price']) && $service['price'] !== null && $service['price'] !== '') {
                        $serviceData['price'] = $service['price'];
                    } else {
                        // Keep existing price if available
                        $existingServices = $gym->services ?? [];
                        if (isset($existingServices[$index]['price'])) {
                            $serviceData['price'] = $existingServices[$index]['price'];
                        }
                    }

                    // Handle service image upload
                    if ($request->hasFile("services.{$index}.image")) {
                        // Delete old service image if exists
                        $existingServices = $gym->services ?? [];
                        if (isset($existingServices[$index]['image']) && Storage::disk('public')->exists(str_replace('storage/', '', $existingServices[$index]['image']))) {
                            Storage::disk('public')->delete(str_replace('storage/', '', $existingServices[$index]['image']));
                        }

                        $serviceImage = $request->file("services.{$index}.image");
                        $serviceImageName = time() . '_service_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $serviceImage->getClientOriginalName());
                        $serviceImage->storeAs('images', $serviceImageName, 'public');
                        $serviceData['image'] = 'storage/images/' . $serviceImageName;
                    } else {
                        // Keep existing image if available
                        $existingServices = $gym->services ?? [];
                        if (isset($existingServices[$index]['image'])) {
                            $serviceData['image'] = $existingServices[$index]['image'];
                        }
                    }

                    $servicesData[] = $serviceData;
                }
            }
            $validatedData['services'] = $servicesData;

            $gym->update($validatedData);

            DB::commit();
            return redirect()->route('admin.gyms.show', $gym->id_gym)->with('success', 'Gym berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui gym: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $gym = Gym::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete associated images
            if ($gym->image && Storage::disk('public')->exists($gym->image)) {
                Storage::disk('public')->delete($gym->image);
            }

            // Delete service images
            if ($gym->services && is_array($gym->services)) {
                foreach ($gym->services as $service) {
                    if (isset($service['image']) && Storage::disk('public')->exists($service['image'])) {
                        Storage::disk('public')->delete($service['image']);
                    }
                }
            }

            $gym->delete();

            DB::commit();
            return redirect()->route('admin.gyms.index')->with('success', 'Gym berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus gym: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $gym = Gym::findOrFail($id);
        $gym->is_open = !$gym->is_open;
        $gym->save();

        $status = $gym->is_open ? 'dibuka' : 'ditutup';
        return redirect()->back()->with('success', "Status gym berhasil diubah menjadi {$status}!");
    }
}
