<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gym;
use App\Models\GymDetail;
use Illuminate\Support\Facades\Storage;

class GymsDetailController extends Controller
{
    /**
     * Display a listing of gym detail pages.
     */
    public function index()
    {
        $gyms = Gym::with('gymDetail')->get();
        return view('admin.gym-details.index', compact('gyms'));
    }

    /**
     * Show the form for editing the specified gym detail.
     */
    public function edit($id)
    {
        $gym = Gym::with('gymDetail')->findOrFail($id);
        
        // Create gym detail if it doesn't exist
        if (!$gym->gymDetail) {
            GymDetail::create([
                'gym_id' => $gym->id_gym,
                'gallery_images' => [],
                'additional_services' => [],
                'opening_hours' => [],
                'facilities' => []
            ]);
            $gym->load('gymDetail');
        }
        
        return view('admin.gym-details.edit', compact('gym'));
    }

    /**
     * Update the specified gym detail in storage.
     */
    public function update(Request $request, $id)
    {
        $gym = Gym::findOrFail($id);
        
        $validatedData = $request->validate([
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:20',
            'location_maps' => 'required|string',
            'about_gym' => 'required|string',
            'opening_hours' => 'required|array',
            'opening_hours.*' => 'required|string',
            'additional_services' => 'nullable|array',
            'additional_services.*.name' => 'nullable|string|max:255',
            'additional_services.*.description' => 'nullable|string',
            'additional_services.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                if ($image) {
                    $imageName = time() . '_gallery_' . $index . '.' . $image->extension();
                    $image->move(public_path('images/gallery'), $imageName);
                    $galleryImages[] = 'images/gallery/' . $imageName;
                }
            }
        }

        // Keep existing images if no new ones uploaded
        $gymDetail = $gym->gymDetail;
        if (empty($galleryImages) && $gymDetail) {
            $galleryImages = $gymDetail->gallery_images ?? [];
        }

        // Sanitize maps URL
        $validatedData['location_maps'] = $this->sanitizeMapsUrl($validatedData['location_maps']);

        // Prepare additional services with image handling
        $additionalServices = [];
        if ($request->has('additional_services')) {
            foreach ($request->additional_services as $index => $service) {
                if (!empty($service['name']) && !empty($service['description'])) {
                    $serviceData = [
                        'name' => $service['name'],
                        'description' => $service['description']
                    ];
            
                    // Handle image upload for additional service
                    if (isset($service['image']) && $service['image']) {
                        $imageName = time() . '_additional_service_' . $index . '.' . $service['image']->extension();
                        $service['image']->move(public_path('images/services'), $imageName);
                        $serviceData['image'] = 'images/services/' . $imageName;
                    } else {
                        // Keep existing image if no new one uploaded
                        $existingServices = $gym->gymDetail->additional_services ?? [];
                        if (isset($existingServices[$index]['image'])) {
                            $serviceData['image'] = $existingServices[$index]['image'];
                        }
                    }
            
                    $additionalServices[] = $serviceData;
                }
            }
        }

        $gymDetailData = [
            'gym_id' => $gym->id_gym,
            'gallery_images' => $galleryImages,
            'contact_person_name' => $validatedData['contact_person_name'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'location_maps' => $validatedData['location_maps'],
            'about_gym' => $validatedData['about_gym'],
            'opening_hours' => $validatedData['opening_hours'],
            'additional_services' => $additionalServices,
            'facilities' => $validatedData['facilities'] ?? []
        ];

        // Update or create gym detail
        GymDetail::updateOrCreate(
            ['gym_id' => $gym->id_gym],
            $gymDetailData
        );
        
        return redirect()->route('admin.gym-details.index')->with('success', 'Detail gym berhasil diperbarui');
    }

    /**
     * Preview the gym detail page.
     */
    public function preview($id)
    {
        $gym = Gym::with('gymDetail')->findOrFail($id);
        return view('fitur.gym-detail', compact('gym'));
    }

    /**
     * Sanitize and format Google Maps embed code.
     */
    private function sanitizeMapsUrl($embedCode)
    {
        if (empty($embedCode)) {
            return '';
        }

        // Clean up the embed code
        $embedCode = trim($embedCode);
        
        // If it's already a complete iframe, validate and return it
        if (strpos($embedCode, '<iframe') !== false && strpos($embedCode, '</iframe>') !== false) {
            // Basic security: ensure it's from Google Maps
            if (strpos($embedCode, 'google.com/maps/embed') !== false) {
                // Add security attributes if not present
                if (strpos($embedCode, 'loading="lazy"') === false) {
                    $embedCode = str_replace('<iframe', '<iframe loading="lazy"', $embedCode);
                }
                if (strpos($embedCode, 'referrerpolicy=') === false) {
                    $embedCode = str_replace('<iframe', '<iframe referrerpolicy="no-referrer-when-downgrade"', $embedCode);
                }
                // Ensure proper styling
                if (strpos($embedCode, 'width="100%"') === false) {
                    $embedCode = str_replace('width="600"', 'width="100%"', $embedCode);
                }
                if (strpos($embedCode, 'height="300"') === false) {
                    $embedCode = str_replace('height="450"', 'height="300"', $embedCode);
                }
                return $embedCode;
            }
        }
        
        // If it's just a URL, try to extract and create iframe
        if (strpos($embedCode, 'google.com/maps/embed') !== false) {
            // Extract URL from iframe src if pasted
            if (preg_match('/src=[\'"]([^\'"]+)[\'"]/', $embedCode, $matches)) {
                $url = $matches[1];
            } else {
                $url = $embedCode;
            }
            
            return '<iframe src="' . htmlspecialchars($url) . '" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        }
        
        return '';
    }
}
