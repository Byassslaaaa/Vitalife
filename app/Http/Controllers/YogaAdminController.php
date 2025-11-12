<?php

namespace App\Http\Controllers;

use App\Models\Yoga;
use App\Models\YogaDetailConfig;
use App\Models\YogaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class YogaAdminController extends Controller
{    public function index()
    {
        $yogas = Yoga::with(['detailConfig', 'yogaServices'])->get();
        $statistics = [
            'total_count' => $yogas->count(),
            'active_count' => $yogas->count(), // Semua yoga dianggap aktif karena tidak ada kolom is_open
            'inactive_count' => 0,
            'active_percentage' => 100,
        ];

        return view('admin.yogas.index', compact('yogas', 'statistics'));
    }

    public function create()
    {
        return view('admin.yogas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'harga' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'waktuBuka' => 'nullable|array',
            'waktuBuka.*' => 'nullable|string',
            'maps' => 'nullable|string',
            'class_type' => 'nullable|string',
            'services' => 'required|array|min:3|max:3',
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
                $imageName = time() . '_yoga_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
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

            Yoga::create($validatedData);

            DB::commit();
            return redirect()->route('admin.yogas.index')->with('success', 'Yoga berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menambahkan yoga: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $yoga = Yoga::with(['detailConfig', 'yogaServices'])->findOrFail($id);
        return view('admin.yogas.show', compact('yoga'));
    }

    public function edit($id)
    {
        $yoga = Yoga::with(['detailConfig', 'yogaServices'])->findOrFail($id);
        return view('admin.yogas.edit', compact('yoga'));
    }

    public function update(Request $request, $id)
    {
        $yoga = Yoga::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'nullable|string|max:20',
            'harga' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'waktuBuka' => 'nullable|array',
            'waktuBuka.*' => 'nullable|string',
            'maps' => 'nullable|string',
            'class_type' => 'nullable|string',
            'services' => 'nullable|array|max:3',
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'required_with:services|string',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($yoga->image && Storage::disk('public')->exists(str_replace('storage/', '', $yoga->image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $yoga->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_yoga_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $image->getClientOriginalName());
                $image->storeAs('images', $imageName, 'public');
                $validatedData['image'] = 'storage/images/' . $imageName;
            }

            // Set default values
            $validatedData['is_open'] = $request->input('is_open', '0') == '1' ? true : false;

            // Handle services data if provided
            if ($request->has('services') && is_array($request->services)) {
                $servicesData = [];
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
                        $existingServices = $yoga->services ?? [];
                        if (isset($existingServices[$index]['price'])) {
                            $serviceData['price'] = $existingServices[$index]['price'];
                        }
                    }

                    // Handle service image upload
                    if ($request->hasFile("services.{$index}.image")) {
                        // Delete old service image if exists
                        $existingServices = $yoga->services ?? [];
                        if (isset($existingServices[$index]['image']) && Storage::disk('public')->exists(str_replace('storage/', '', $existingServices[$index]['image']))) {
                            Storage::disk('public')->delete(str_replace('storage/', '', $existingServices[$index]['image']));
                        }

                        $serviceImage = $request->file("services.{$index}.image");
                        $serviceImageName = time() . '_service_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $serviceImage->getClientOriginalName());
                        $serviceImage->storeAs('images', $serviceImageName, 'public');
                        $serviceData['image'] = 'storage/images/' . $serviceImageName;
                    } else {
                        // Keep existing image if available
                        $existingServices = $yoga->services ?? [];
                        if (isset($existingServices[$index]['image'])) {
                            $serviceData['image'] = $existingServices[$index]['image'];
                        }
                    }

                    $servicesData[] = $serviceData;
                }
                $validatedData['services'] = $servicesData;
            }

            $yoga->update($validatedData);

            DB::commit();
            return redirect()->route('admin.yogas.index')->with('success', 'Yoga berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui yoga: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $yoga = Yoga::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete associated images
            if ($yoga->image && Storage::disk('public')->exists($yoga->image)) {
                Storage::disk('public')->delete($yoga->image);
            }

            // Delete service images
            if ($yoga->services && is_array($yoga->services)) {
                foreach ($yoga->services as $service) {
                    if (isset($service['image']) && Storage::disk('public')->exists($service['image'])) {
                        Storage::disk('public')->delete($service['image']);
                    }
                }
            }

            $yoga->delete();

            DB::commit();
            return redirect()->route('admin.yogas.index')->with('success', 'Yoga berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus yoga: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $yoga = Yoga::findOrFail($id);
        $yoga->is_open = !$yoga->is_open;
        $yoga->save();

        $status = $yoga->is_open ? 'dibuka' : 'ditutup';
        return redirect()->back()->with('success', "Status yoga berhasil diubah menjadi {$status}!");
    }
}
