<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spa;
use App\Models\SpaDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpaDetailController extends Controller
{
    /**
     * Display a listing of spa details
     */
    public function index(Request $request)
    {
        $query = Spa::with(['spaDetail', 'spaServices']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'with_details') {
                $query->whereHas('spaDetail');
            } elseif ($request->status === 'without_details') {
                $query->whereDoesntHave('spaDetail');
            }
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $spas = $query->withCount(['spaServices'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.spa-details.index', compact('spas'));
    }

    /**
     * Show the form for editing the specified spa detail
     */
    public function edit($id_spa)
    {
        $spa = Spa::with('spaDetail')->findOrFail($id_spa);
        
        // Create spa detail if it doesn't exist
        if (!$spa->spaDetail) {
            $spa->spaDetail()->create([
                'spa_id' => $spa->id_spa,
                'about_spa' => 'Detail spa akan segera diupdate.',
                'facilities' => [],
                'gallery_images' => [],
                'contact_person_name' => '',
                'contact_person_phone' => '',
            ]);
            $spa->load('spaDetail');
        }
        
        return view('admin.spa-details.edit', compact('spa'));
    }

    /**
     * Update the specified spa detail
     */
    public function update(Request $request, $id_spa)
    {
        Log::info('SpaDetail update started', ['id_spa' => $id_spa, 'request_data' => $request->all()]);
        
        $spa = Spa::findOrFail($id_spa);
        
        $validatedData = $request->validate([
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'location_maps' => 'nullable|string',
            'about_spa' => 'required|string',
            'opening_hours' => 'required|array',
            'opening_hours.*' => 'required|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'additional_services' => 'nullable|array',
            'additional_services.*.name' => 'nullable|string|max:255',
            'additional_services.*.description' => 'nullable|string',
            'additional_services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        
        try {
            // Process gallery images - changed to mainvita/public/images
            $existingGalleryImages = $spa->spaDetail->gallery_images ?? [];
            $newGalleryImages = [];
            
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    if ($image) {
                        // Delete old image if exists
                        if (isset($existingGalleryImages[$index]) && file_exists(public_path($existingGalleryImages[$index]))) {
                            unlink(public_path($existingGalleryImages[$index]));
                        }
                        
                        $filename = time() . '_gallery_' . $index . '_' . $image->getClientOriginalName();
                        
                        // Create directory if it doesn't exist
                        $uploadPath = public_path('mainvita/public/images');
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        $image->move($uploadPath, $filename);
                        $newGalleryImages[$index] = 'mainvita/public/images/' . $filename;
                    }
                }
            }
            
            // Merge with existing images (keep existing ones that weren't replaced)
            $finalGalleryImages = $existingGalleryImages;
            foreach ($newGalleryImages as $index => $newImage) {
                $finalGalleryImages[$index] = $newImage;
            }

            // Process additional service images - changed to mainvita/public/images
            $additionalServices = [];
            if (isset($validatedData['additional_services'])) {
                foreach ($validatedData['additional_services'] as $index => $service) {
                    if (!empty($service['name'])) {
                        $serviceData = [
                            'name' => $service['name'],
                            'description' => $service['description'] ?? '',
                        ];
                        
                        if ($request->hasFile("additional_services.{$index}.image")) {
                            $serviceFile = $request->file("additional_services.{$index}.image");
                            $serviceFilename = time() . '_additional_service_' . $index . '_' . $serviceFile->getClientOriginalName();
                            
                            // Create directory if it doesn't exist
                            $uploadPath = public_path('mainvita/public/images');
                            if (!file_exists($uploadPath)) {
                                mkdir($uploadPath, 0755, true);
                            }
                            
                            $serviceFile->move($uploadPath, $serviceFilename);
                            $serviceData['image'] = 'mainvita/public/images/' . $serviceFilename;
                        }
                        
                        $additionalServices[] = $serviceData;
                    }
                }
            }

            // Update spa basic info
            $spa->update([
                'waktuBuka' => $validatedData['opening_hours'],
                'maps' => $validatedData['location_maps'],
            ]);

            // Update or create spa detail
            $spa->spaDetail()->updateOrCreate(
                ['spa_id' => $spa->id_spa],
                [
                    'contact_person_name' => $validatedData['contact_person_name'],
                    'contact_person_phone' => $validatedData['contact_person_phone'],
                    'about_spa' => $validatedData['about_spa'],
                    'facilities' => $validatedData['facilities'] ?? [],
                    'gallery_images' => array_values($finalGalleryImages),
                    'additional_services' => $additionalServices,
                ]
            );

            DB::commit();
            
            Log::info('SpaDetail update successful', ['id_spa' => $id_spa]);

            return redirect()->route('admin.spa-details.index')
                           ->with('success', 'Detail spa berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('SpaDetail update failed', [
                'id_spa' => $id_spa,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Preview spa detail page
     */
    public function preview($id_spa)
    {
        $spa = Spa::with(['spaDetail', 'spaServices' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($id_spa);
        
        return redirect()->route('spa.detail', $spa->id_spa);
    }
}
