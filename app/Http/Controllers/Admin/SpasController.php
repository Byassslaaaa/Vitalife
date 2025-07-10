<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spa;
use App\Models\SpaDetail;
use App\Models\SpaService;
use App\Models\SpaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpasController extends Controller
{
    /**
     * Display a listing of spas with comprehensive data
     */
    public function index(Request $request)
    {
        // Remove the services relationship from eager loading since it's a JSON column
        $query = Spa::with(['spaDetail', 'spaServices']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('alamat', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('service')) {
            $query->whereHas('spaServices', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->service . '%');
            });
        }

        $spas = $query->withCount(['spaServices'])
                     ->get();

        return view('admin.spas.index', compact('spas'));
    }

    /**
     * Display spa details management page
     */
    public function detailsIndex(Request $request)
    {
        // Load spas with their details and services
        $query = Spa::with(['spaDetail', 'spaServices']);

        // Apply filters if needed
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        if ($request->filled('has_details')) {
            if ($request->has_details == '1') {
                $query->whereHas('spaDetail');
            } else {
                $query->whereDoesntHave('spaDetail');
            }
        }

        $spas = $query->withCount(['spaServices'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.spa-details.index', compact('spas'));
    }

    /**
     * Show the form for creating a new spa
     */
    public function create()
    {
        return view('admin.spas.create');
    }

    /**
     * Store a newly created spa with all related data
     */
    public function store(Request $request)
    {
        // Simplified validation to focus on core fields
        $validatedData = $request->validate([
            // Basic spa information
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'required|string|max:20',
            'waktuBuka' => 'required|array',
            'waktuBuka.*' => 'required|string',
            'maps' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'nullable|boolean',
            
            // Services data - simplified
            'services' => 'nullable|array',
            'services.*.name' => 'nullable|string|max:255',
            'services.*.description' => 'nullable|string',
            'services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle main image upload - changed to mainvita/public/images
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_spa_' . $file->getClientOriginalName();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('mainvita/public/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $filename);
                $imagePath = 'mainvita/public/images/' . $filename;
            }

            // Process services
            $services = [];
            if ($request->has('services')) {
                foreach ($request->input('services') as $index => $service) {
                    if (!empty($service['name'])) {
                        $serviceData = [
                            'name' => $service['name'],
                            'description' => $service['description'] ?? '',
                        ];

                        // Handle service image - changed to mainvita/public/images
                        if ($request->hasFile("services.{$index}.image")) {
                            $serviceFile = $request->file("services.{$index}.image");
                            $serviceFilename = time() . '_service_' . $index . '_' . $serviceFile->getClientOriginalName();
                            
                            // Create directory if it doesn't exist
                            $uploadPath = public_path('mainvita/public/images');
                            if (!file_exists($uploadPath)) {
                                mkdir($uploadPath, 0755, true);
                            }
                            
                            $serviceFile->move($uploadPath, $serviceFilename);
                            $serviceData['image'] = 'mainvita/public/images/' . $serviceFilename;
                        }

                        $services[] = $serviceData;
                    }
                }
            }

            // Create spa
            $spa = Spa::create([
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'noHP' => $validatedData['noHP'],
                'waktuBuka' => $validatedData['waktuBuka'],
                'maps' => $validatedData['maps'] ?? null,
                'image' => $imagePath,
                'is_open' => $request->has('is_open') ? true : false,
                'services' => $services,
            ]);

            // Create spa detail if provided
            if ($request->has('spa_detail')) {
                $this->createSpaDetail($spa, $request->input('spa_detail'), $request);
            }

            DB::commit();

            return redirect()->route('admin.spas.index')
                           ->with('success', 'Spa berhasil dibuat dengan lengkap!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the error for debugging
            \Log::error('Error creating spa: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified spa with all related data
     */
    public function show($id_spa)
    {
        $spa = Spa::with([
            'spaDetail',
            'spaServices' => function ($query) {
                $query->orderBy('name');
            }
        ])->findOrFail($id_spa);

        return view('admin.spas.show', compact('spa'));
    }

    /**
     * Show the form for editing the specified spa
     */
    public function edit($id_spa)
    {
        $spa = Spa::with(['spaDetail', 'spaServices'])->findOrFail($id_spa);
        return view('admin.spas.edit', compact('spa'));
    }

    /**
     * Update the specified spa with all related data
     */
    public function update(Request $request, $id_spa)
    {
        $spa = Spa::findOrFail($id_spa);
        
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'noHP' => 'required|string|max:20',
            'waktuBuka' => 'required|array',
            'waktuBuka.*' => 'required|string',
            'maps' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'nullable|boolean',
            'services' => 'nullable|array',
        ]);

        DB::beginTransaction();
        
        try {
            // Handle image upload - changed to mainvita/public/images
            $imagePath = $spa->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($spa->image && file_exists(public_path($spa->image))) {
                    unlink(public_path($spa->image));
                }
                
                $file = $request->file('image');
                $filename = time() . '_spa_' . $file->getClientOriginalName();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('mainvita/public/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $filename);
                $imagePath = 'mainvita/public/images/' . $filename;
            }

            // Process services
            $services = [];
            if ($request->has('services')) {
                foreach ($request->input('services') as $index => $service) {
                    if (!empty($service['name'])) {
                        $serviceData = [
                            'name' => $service['name'],
                            'description' => $service['description'] ?? '',
                        ];

                        // Handle service image - changed to mainvita/public/images
                        if ($request->hasFile("services.{$index}.image")) {
                            $serviceFile = $request->file("services.{$index}.image");
                            $serviceFilename = time() . '_service_' . $index . '_' . $serviceFile->getClientOriginalName();
                            
                            // Create directory if it doesn't exist
                            $uploadPath = public_path('mainvita/public/images');
                            if (!file_exists($uploadPath)) {
                                mkdir($uploadPath, 0755, true);
                            }
                            
                            $serviceFile->move($uploadPath, $serviceFilename);
                            $serviceData['image'] = 'mainvita/public/images/' . $serviceFilename;
                        } elseif (isset($spa->services[$index]['image'])) {
                            // Keep existing image if no new image uploaded
                            $serviceData['image'] = $spa->services[$index]['image'];
                        }

                        $services[] = $serviceData;
                    }
                }
            }

            // Update spa
            $spa->update([
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'noHP' => $validatedData['noHP'],
                'waktuBuka' => $validatedData['waktuBuka'],
                'maps' => $validatedData['maps'] ?? null,
                'image' => $imagePath,
                'is_open' => $request->has('is_open') ? true : false,
                'services' => $services,
            ]);

            DB::commit();

            return redirect()->route('admin.spas.index')
                           ->with('success', 'Spa berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Error updating spa: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified spa and all related data
     */
    public function destroy($id_spa)
    {
        $spa = Spa::findOrFail($id_spa);
        
        DB::beginTransaction();
        
        try {
            // Delete image if exists - updated path
            if ($spa->image && file_exists(public_path($spa->image))) {
                unlink(public_path($spa->image));
            }

            // Delete service images - updated path
            if ($spa->services) {
                foreach ($spa->services as $service) {
                    if (isset($service['image']) && file_exists(public_path($service['image']))) {
                        unlink(public_path($service['image']));
                    }
                }
            }

            // Delete spa
            $spa->delete();
            
            DB::commit();
            
            return redirect()->route('admin.spas.index')
                           ->with('success', 'Spa berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Create spa detail record
     */
    private function createSpaDetail(Spa $spa, array $detailData, Request $request)
    {
        // Simplified spa detail creation
        $spaDetailData = [
            'spa_id' => $spa->id_spa,
            'contact_person_name' => $detailData['contact_person_name'] ?? null,
            'contact_person_phone' => $detailData['contact_person_phone'] ?? null,
            'about_spa' => $detailData['about_spa'] ?? null,
            'facilities' => $detailData['facilities'] ?? [],
            'treatment_rooms' => $detailData['treatment_rooms'] ?? [],
            'therapist_info' => $detailData['therapist_info'] ?? [],
            'gallery_images' => [],
        ];

        // Only create if SpaDetail model exists
        if (class_exists('App\Models\SpaDetail')) {
            SpaDetail::create($spaDetailData);
        }
    }
}
